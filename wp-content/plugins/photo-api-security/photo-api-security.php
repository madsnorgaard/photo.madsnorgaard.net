<?php
/**
 * Plugin Name: Photo API Security
 * Description: Security hardening for WordPress REST API — disables XML-RPC, prevents user enumeration, adds security headers, rate-limits unauthenticated REST calls, and restricts CPT content to published posts.
 * Version: 1.0.0
 * Author: Mads Nørgaard
 * Text Domain: photo-api-security
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ─── 1. CORS ────────────────────────────────────────────────────────────────
// Replace WordPress's permissive default CORS with an explicit allowlist.
// Only the Nuxt frontend domains and local dev origins are permitted.
// Write operations are blocked at the method level (GET, OPTIONS only).
add_action( 'rest_api_init', function () {
    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
    add_filter( 'rest_pre_serve_request', 'pas_send_cors_headers', 10, 3 );
}, 15 );

function pas_send_cors_headers( bool $served, \WP_HTTP_Response $result, \WP_REST_Request $request ): bool {
    $origin = isset( $_SERVER['HTTP_ORIGIN'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_ORIGIN'] ) ) : '';

    $allowed_origins = [
        'https://madsnorgaard.net',
        'https://www.madsnorgaard.net',
        'https://madsnorgaard.net.ddev.site',
        'http://localhost:3000',
        'http://localhost:3001',
    ];

    if ( in_array( $origin, $allowed_origins, true ) ) {
        header( 'Access-Control-Allow-Origin: ' . $origin );
        header( 'Access-Control-Allow-Methods: GET, OPTIONS' );
        header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
        header( 'Access-Control-Allow-Credentials: true' );
        header( 'Vary: Origin' );
    }

    // Respond immediately to preflight requests — no further processing needed.
    if ( 'OPTIONS' === strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ?? '' ) ) ) ) {
        status_header( 200 );
        exit;
    }

    return $served;
}

// ─── 2. Disable XML-RPC ─────────────────────────────────────────────────────
add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove the X-Pingback header so the xmlrpc.php URL is not advertised.
add_filter( 'wp_headers', function ( array $headers ): array {
    unset( $headers['X-Pingback'] );
    return $headers;
} );

// Hard-block at the HTTP level: check XMLRPC_REQUEST on init, which fires
// after wp-load.php but before the IXR server processes any method calls.
// xmlrpc_enabled only disables WP-specific methods; this blocks everything
// including IXR base methods like system.listMethods.
add_action( 'init', function (): void {
    if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
        status_header( 403 );
        header( 'Content-Type: text/plain; charset=UTF-8' );
        exit( 'XML-RPC services are disabled on this site.' );
    }
} );

// ─── 3. Block user enumeration ──────────────────────────────────────────────

// Remove /wp/v2/users endpoint for unauthenticated visitors.
// Authenticated editors still need it for the block editor's author picker.
add_filter( 'rest_endpoints', function ( array $endpoints ): array {
    if ( ! is_user_logged_in() ) {
        unset( $endpoints['/wp/v2/users'] );
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
} );

// Block the /?author=N query-string redirect for unauthenticated visitors.
add_action( 'template_redirect', function (): void {
    if ( isset( $_GET['author'] ) && ! is_user_logged_in() ) { // phpcs:ignore WordPress.Security.NonceVerification
        wp_safe_redirect( home_url( '/' ), 301 );
        exit;
    }
} );

// ─── 4. Remove WordPress version fingerprints ───────────────────────────────
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// Strip ?ver=x.x.x from enqueued CSS/JS URLs.
add_filter( 'style_loader_src', 'pas_strip_asset_version', 9999 );
add_filter( 'script_loader_src', 'pas_strip_asset_version', 9999 );
function pas_strip_asset_version( string $src ): string {
    if ( str_contains( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

// ─── 5. Security response headers ───────────────────────────────────────────
// Sent on all page responses and REST API responses.
add_action( 'send_headers', 'pas_send_security_headers' );
add_filter( 'rest_post_dispatch', function ( \WP_REST_Response $response ): \WP_REST_Response {
    pas_send_security_headers();
    return $response;
} );

function pas_send_security_headers(): void {
    if ( headers_sent() ) {
        return;
    }
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'X-XSS-Protection: 1; mode=block' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
    header_remove( 'X-Powered-By' );
}

// ─── 6. REST API rate limiting for unauthenticated visitors ─────────────────
// Uses transients: 120 requests per IP per 60 seconds.
// Authenticated users are exempt (admin, Application Password sessions).
add_filter( 'rest_pre_dispatch', function ( $result, \WP_REST_Server $server, \WP_REST_Request $request ) {
    if ( $result !== null || is_user_logged_in() ) {
        return $result;
    }

    $ip  = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) );
    $key = 'pas_rate_' . md5( $ip );
    $hits = (int) get_transient( $key );

    if ( $hits >= 120 ) {
        return new \WP_Error(
            'too_many_requests',
            __( 'Too many requests. Please try again later.', 'photo-api-security' ),
            [ 'status' => 429 ]
        );
    }

    // set_transient with 0-count resets the expiry to a full minute window.
    set_transient( $key, $hits + 1, 60 );

    return $result;
}, 10, 3 );

// ─── 7. Restrict Application Passwords to administrators only ───────────────
// Application Passwords are the supported way to authenticate API clients.
// Restricting creation to admins prevents privilege escalation.
add_filter( 'wp_is_application_passwords_available_for_user', function ( bool $available, \WP_User $user ): bool {
    return user_can( $user, 'manage_options' );
}, 10, 2 );

// ─── 8. Restrict CPT REST endpoints to published posts for public requests ──
// Unauthenticated visitors must not see draft or pending content via the API.
add_filter( 'rest_photo_query',   'pas_require_published_for_public' );
add_filter( 'rest_story_query',   'pas_require_published_for_public' );
add_filter( 'rest_project_query', 'pas_require_published_for_public' );

function pas_require_published_for_public( array $args ): array {
    if ( ! current_user_can( 'edit_posts' ) ) {
        $args['post_status'] = 'publish';
    }
    return $args;
}
