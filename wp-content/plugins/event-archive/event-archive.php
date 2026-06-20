<?php
/**
 * Plugin Name: Event Archive
 * Plugin URI: https://photo.madsnorgaard.net
 * Description: Lightweight, reusable data model for bulk event-photo walls (e.g. Cold Turkey Cape Town). Registers the event_photo CPT, the hierarchical event_set taxonomy, like / "I was there" reaction counters, and the server-to-server write routes that power them. Deliberately separate from the curated documentary archive (photo-archive-cpts) so the two never collide.
 * Version: 1.0.0
 * Author: Mads Nørgaard
 * Text Domain: event-archive
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Slug of the top-level event_set term that all Cold Turkey nights live under.
 * Kept as a constant so the import script and the REST query agree on one value.
 */
if ( ! defined( 'EVA_DEFAULT_EVENT_SLUG' ) ) {
    define( 'EVA_DEFAULT_EVENT_SLUG', 'cold-turkey-cape-town' );
}

// ─── 1. event_photo CPT ─────────────────────────────────────────────────────
// Uncaptioned bulk photographs. No editor, no excerpt, no required ACF fields -
// the whole point is that thousands of these can be imported with zero metadata.
function eva_register_event_photo_cpt(): void {
    $labels = [
        'name'               => __( 'Event Photos', 'event-archive' ),
        'singular_name'      => __( 'Event Photo', 'event-archive' ),
        'add_new_item'       => __( 'Add New Event Photo', 'event-archive' ),
        'edit_item'          => __( 'Edit Event Photo', 'event-archive' ),
        'view_item'          => __( 'View Event Photo', 'event-archive' ),
        'search_items'       => __( 'Search Event Photos', 'event-archive' ),
        'not_found'          => __( 'No event photos found', 'event-archive' ),
        'menu_name'          => __( 'Event Photos', 'event-archive' ),
    ];

    register_post_type( 'event_photo', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'event-photo', 'with_front' => false ],
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-images-alt2',
        // No 'editor'/'excerpt': these carry no caption. 'custom-fields' keeps
        // the registered meta visible/editable in wp-admin for spot fixes.
        'supports'           => [ 'title', 'thumbnail', 'custom-fields' ],
        'rest_base'          => 'event-photos',
    ] );
}
add_action( 'init', 'eva_register_event_photo_cpt' );

// ─── 2. event_set taxonomy (hierarchical: event > night) ────────────────────
// One parent term per event (e.g. "Cold Turkey Cape Town"); one child term per
// night/set. Mirrors the existing hierarchical 'subject' taxonomy so the Nuxt
// frontend already understands the term.parent shape. Querying the parent term
// returns all descendant nights (WP include_children default), which is how the
// wall shows "all nights blended".
function eva_register_event_set_taxonomy(): void {
    $labels = [
        'name'              => __( 'Event Sets', 'event-archive' ),
        'singular_name'     => __( 'Event Set', 'event-archive' ),
        'search_items'      => __( 'Search Event Sets', 'event-archive' ),
        'all_items'         => __( 'All Event Sets', 'event-archive' ),
        'parent_item'       => __( 'Parent Event', 'event-archive' ),
        'edit_item'         => __( 'Edit Event Set', 'event-archive' ),
        'add_new_item'      => __( 'Add New Event Set', 'event-archive' ),
        'menu_name'         => __( 'Event Sets', 'event-archive' ),
    ];

    register_taxonomy( 'event_set', [ 'event_photo' ], [
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rest_base'         => 'event-sets',
        'rewrite'           => [ 'slug' => 'event-set' ],
        'show_admin_column' => true,
    ] );
}
add_action( 'init', 'eva_register_event_set_taxonomy' );

// ─── 3. Meta in REST ────────────────────────────────────────────────────────
// Counts are written by code (never by editors), and capture_date / source_filename
// are set by the importer. register_post_meta (not ACF) keeps this CPT free of the
// required-field machinery the curated 'photo' ACF group carries.
function eva_register_meta(): void {
    register_post_meta( 'event_photo', 'like_count', [
        'type'         => 'integer',
        'single'       => true,
        'default'      => 0,
        'show_in_rest' => true,
        // Counts are public-readable but only ever written server-side.
        'auth_callback' => '__return_false',
    ] );

    register_post_meta( 'event_photo', 'there_count', [
        'type'         => 'integer',
        'single'       => true,
        'default'      => 0,
        'show_in_rest' => true,
        'auth_callback' => '__return_false',
    ] );

    register_post_meta( 'event_photo', 'capture_date', [
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'auth_callback' => '__return_false',
    ] );

    // Original upload filename - used by the importer for idempotent re-runs.
    register_post_meta( 'event_photo', 'source_filename', [
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => false,
        'auth_callback' => '__return_false',
    ] );
}
add_action( 'init', 'eva_register_meta' );

// ─── 3b. event_note CPT (moderated per-night guestbook) ─────────────────────
// A short memory left on a night. Created pending; only published (approved by
// an editor in wp-admin) notes are ever shown publicly.
function eva_register_event_note_cpt(): void {
    register_post_type( 'event_note', [
        'labels'             => [
            'name'          => __( 'Event Notes', 'event-archive' ),
            'singular_name' => __( 'Event Note', 'event-archive' ),
            'menu_name'     => __( 'Guestbook', 'event-archive' ),
        ],
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'rest_base'          => 'event-notes',
        'menu_position'      => 8,
        'menu_icon'          => 'dashicons-format-chat',
        'supports'           => [ 'title', 'editor', 'custom-fields' ],
    ] );

    register_taxonomy_for_object_type( 'event_set', 'event_note' );

    register_post_meta( 'event_note', 'author_name', [
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
        'auth_callback' => '__return_false',
    ] );
}
add_action( 'init', 'eva_register_event_note_cpt' );

// Attach event_set to event_note as well (so notes filter by night).
add_action( 'init', function (): void {
    register_taxonomy_for_object_type( 'event_set', 'event_note' );
}, 11 );

// ─── 4. Reuse existing security on the new CPT ──────────────────────────────
// photo-api-security.php defines pas_require_published_for_public and
// pas_cap_pagination as global functions but only wires them to the curated
// CPTs. Re-use them here (do not edit that plugin) so event_photo gets the same
// publish-only-for-public and per_page <= 50 guarantees. function_exists guards
// keep this plugin standalone if the security plugin is ever deactivated.
add_action( 'init', function (): void {
    if ( function_exists( 'pas_require_published_for_public' ) ) {
        add_filter( 'rest_event_photo_query', 'pas_require_published_for_public' );
        // Guestbook notes: pending notes must never leak to the public.
        add_filter( 'rest_event_note_query', 'pas_require_published_for_public' );
    }
    if ( function_exists( 'pas_cap_pagination' ) ) {
        add_filter( 'rest_event_photo_query', 'pas_cap_pagination' );
        add_filter( 'rest_event_note_query', 'pas_cap_pagination' );
    }
}, 20 );

// ─── 5. Reaction write routes (event-archive/v1) ────────────────────────────
// Browsers cannot reach these: the CORS layer in photo-api-security only allows
// GET/OPTIONS, and the permission_callback below demands the internal token,
// which only the Nuxt BFF holds. The Nuxt server proxies the public's taps here.
add_action( 'rest_api_init', function (): void {
    register_rest_route( 'event-archive/v1', '/photos/(?P<id>\d+)/like', [
        'methods'             => 'POST',
        'callback'            => 'eva_handle_like',
        'permission_callback' => 'eva_internal_permission',
        'args'                => [
            'id' => [
                'validate_callback' => static fn( $v ) => is_numeric( $v ),
            ],
        ],
    ] );

    register_rest_route( 'event-archive/v1', '/photos/(?P<id>\d+)/there', [
        'methods'             => 'POST',
        'callback'            => 'eva_handle_there',
        'permission_callback' => 'eva_internal_permission',
        'args'                => [
            'id' => [
                'validate_callback' => static fn( $v ) => is_numeric( $v ),
            ],
        ],
    ] );

    // Guestbook: leave a memory on a night. Created pending for moderation.
    register_rest_route( 'event-archive/v1', '/notes', [
        'methods'             => 'POST',
        'callback'            => 'eva_handle_note',
        'permission_callback' => 'eva_internal_permission',
    ] );

    // GET /top?count=12 — most-liked photo IDs, descending. Read-only and
    // public: core wp/v2 cannot order event-photos by the like_count meta, so
    // the "Top picks" rail needs this. Returns only IDs (no PII).
    register_rest_route( 'event-archive/v1', '/top', [
        'methods'             => 'GET',
        'callback'            => 'eva_handle_top',
        'permission_callback' => '__return_true',
    ] );
} );

/**
 * GET /event-archive/v1/top?count=12
 *
 * Returns the IDs of the most-liked published event_photo posts (descending),
 * which the Nuxt BFF then expands to full photos via wp/v2 ?include.
 */
function eva_handle_top( WP_REST_Request $request ): WP_REST_Response {
    $count = (int) $request->get_param( 'count' );
    $count = max( 1, min( 50, $count ?: 12 ) );

    $q = new WP_Query( [
        'post_type'      => 'event_photo',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'meta_key'       => 'like_count',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'meta_query'     => [
            [ 'key' => 'like_count', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC' ],
        ],
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ] );

    return new WP_REST_Response( [ 'ids' => array_map( 'intval', $q->posts ) ], 200 );
}

/**
 * POST /event-archive/v1/notes  { set, name, message, hp }
 *
 * Creates a PENDING event_note (held for manual approval). The honeypot + the
 * per-IP rate limit are belt-and-braces on top of the token gate, since the
 * Nuxt BFF forwards public submissions here.
 */
function eva_handle_note( \WP_REST_Request $request ) {
    // Honeypot: real users never fill this.
    if ( '' !== trim( (string) $request->get_param( 'hp' ) ) ) {
        return new \WP_REST_Response( [ 'ok' => true ], 200 ); // silently ignore bots
    }

    $set_slug = sanitize_title( (string) $request->get_param( 'set' ) );
    $name     = trim( wp_strip_all_tags( (string) $request->get_param( 'name' ) ) );
    $message  = trim( wp_strip_all_tags( (string) $request->get_param( 'message' ) ) );

    if ( '' === $name || '' === $message ) {
        return new \WP_Error( 'invalid', 'Name and message are required.', [ 'status' => 422 ] );
    }
    $name    = mb_substr( $name, 0, 60 );
    $message = mb_substr( $message, 0, 600 );

    $term = get_term_by( 'slug', $set_slug, 'event_set' );
    if ( ! $term ) {
        return new \WP_Error( 'invalid_set', 'Unknown night.', [ 'status' => 422 ] );
    }

    // Per-IP rate limit: at most 3 notes per hour.
    $ip  = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) );
    $key = 'eva_notes_' . md5( $ip );
    $hits = (int) get_transient( $key );
    if ( $hits >= 3 ) {
        return new \WP_Error( 'too_many', 'Too many memories just now. Try again later.', [ 'status' => 429 ] );
    }
    set_transient( $key, $hits + 1, HOUR_IN_SECONDS );

    $post_id = wp_insert_post( [
        'post_type'    => 'event_note',
        'post_status'  => 'pending', // held for approval
        'post_title'   => $name . ' — ' . wp_trim_words( $message, 6 ),
        'post_content' => $message,
    ], true );

    if ( is_wp_error( $post_id ) ) {
        return new \WP_Error( 'failed', 'Could not save the memory.', [ 'status' => 500 ] );
    }

    update_post_meta( $post_id, 'author_name', $name );
    wp_set_object_terms( $post_id, [ (int) $term->term_id ], 'event_set' );

    return new \WP_REST_Response( [ 'ok' => true, 'pending' => true ], 201 );
}

/**
 * Permission gate for the write routes: a valid internal token only.
 *
 * Reuses the exact constant/env convention from photo-api-security.php so there
 * is one shared secret (PHOTO_API_INTERNAL_TOKEN) across both plugins.
 */
function eva_internal_permission(): bool {
    $expected = defined( 'PHOTO_API_INTERNAL_TOKEN' )
        ? PHOTO_API_INTERNAL_TOKEN
        : ( getenv( 'PHOTO_API_INTERNAL_TOKEN' ) ?: '' );
    if ( '' === $expected ) {
        return false;
    }

    $provided = isset( $_SERVER['HTTP_X_INTERNAL_TOKEN'] )
        ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_INTERNAL_TOKEN'] ) )
        : '';

    return '' !== $provided && hash_equals( $expected, $provided );
}

/**
 * POST /event-archive/v1/photos/{id}/like
 */
function eva_handle_like( \WP_REST_Request $request ) {
    return eva_increment_reaction( (int) $request['id'], 'like_count' );
}

/**
 * POST /event-archive/v1/photos/{id}/there
 */
function eva_handle_there( \WP_REST_Request $request ) {
    return eva_increment_reaction( (int) $request['id'], 'there_count' );
}

/**
 * Shared reaction-increment logic for like_count / there_count.
 *
 * Idempotent within a per-IP-per-photo window: a repeat tap returns the current
 * count without incrementing (a smooth 200, never an error), so the UX never
 * shows a failure. The client also dedupes via localStorage; this transient is
 * defence in depth. The IP is hashed, never stored raw.
 *
 * Uses a direct SQL increment to avoid the read-then-write race when two taps
 * for the same photo land simultaneously.
 *
 * @param int    $id        event_photo post ID.
 * @param string $meta_key  'like_count' or 'there_count'.
 * @return \WP_REST_Response|\WP_Error
 */
function eva_increment_reaction( int $id, string $meta_key ) {
    $post = get_post( $id );
    if ( ! $post || 'event_photo' !== $post->post_type || 'publish' !== $post->post_status ) {
        return new \WP_Error( 'not_found', 'Event photo not found.', [ 'status' => 404 ] );
    }

    $ip   = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) );
    $dedupe_key = 'eva_' . $meta_key . '_' . md5( $ip . '_' . $id );

    if ( get_transient( $dedupe_key ) ) {
        // Already counted this IP+photo recently - return the current value.
        return new \WP_REST_Response( [
            'id'      => $id,
            $meta_key => (int) get_post_meta( $id, $meta_key, true ),
        ], 200 );
    }

    global $wpdb;
    // Ensure a row exists, then atomically increment it.
    if ( '' === get_post_meta( $id, $meta_key, true ) ) {
        update_post_meta( $id, $meta_key, 0 );
    }
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    $wpdb->query( $wpdb->prepare(
        "UPDATE {$wpdb->postmeta} SET meta_value = meta_value + 1 WHERE post_id = %d AND meta_key = %s",
        $id,
        $meta_key
    ) );
    wp_cache_delete( $id, 'post_meta' );

    // Block this IP+photo from re-counting for 30 days.
    set_transient( $dedupe_key, 1, 30 * DAY_IN_SECONDS );

    return new \WP_REST_Response( [
        'id'      => $id,
        $meta_key => (int) get_post_meta( $id, $meta_key, true ),
    ], 200 );
}

// ─── 6. Activation / deactivation ───────────────────────────────────────────
function eva_activate(): void {
    eva_register_event_photo_cpt();
    eva_register_event_set_taxonomy();
    eva_register_event_note_cpt();
    eva_register_meta();

    // Seed the parent event term so the importer and the wall agree it exists.
    if ( ! term_exists( EVA_DEFAULT_EVENT_SLUG, 'event_set' ) ) {
        wp_insert_term( 'Cold Turkey Cape Town', 'event_set', [ 'slug' => EVA_DEFAULT_EVENT_SLUG ] );
    }

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'eva_activate' );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
