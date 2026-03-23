<?php
/**
 * Plugin Name: Suppress ACF 5.x Compatibility Notices
 * Description: Silences PHP 8.x/WP 6.7+ deprecation noise from ACF Pro 5.x and Bootstrap 3 IE conditionals. Remove this file once ACF Pro is upgraded to 6.x.
 */

// Must-use plugins load before regular plugins, so this error handler
// is in place before ACF Pro is initialized. WordPress will subsequently
// call error_reporting(E_ALL) via wp_debug_mode(), but set_error_handler()
// intercepts at a higher priority — our handler fires regardless, and
// returning true fully suppresses the error (no display, no log, no PHP log).

set_error_handler(
    static function ( int $errno, string $errstr, string $errfile ): bool {

        // ── ACF Pro 5.x: dynamic property deprecations ─────────────────
        // PHP 8.2+ deprecates implicit creation of dynamic properties.
        // ACF Pro 5.x creates dozens of them across its classes.
        if (
            ( $errno & ( E_DEPRECATED | E_USER_DEPRECATED ) )
            && str_contains( $errfile, DIRECTORY_SEPARATOR . 'advanced-custom-fields' )
        ) {
            return true;
        }

        // ── WP 6.7+: translation loading too early for ACF domain ───────
        // ACF 5.x triggers load_plugin_textdomain() before the init hook.
        // WP 6.7 added a check and emits E_USER_NOTICE via _doing_it_wrong().
        if (
            ( $errno & ( E_NOTICE | E_USER_NOTICE ) )
            && str_contains( $errstr, 'Translation loading for the acf domain' )
        ) {
            return true;
        }

        // ── WP 6.9+: IE conditional comments from Bootstrap 3 ──────────
        // The parent Mauer Stills theme uses Bootstrap 3 which registered
        // scripts with IE conditional comments. WP 6.9 deprecated this API.
        if (
            ( $errno & ( E_DEPRECATED | E_USER_DEPRECATED ) )
            && str_contains( $errstr, 'IE conditional comments are ignored' )
        ) {
            return true;
        }

        // ── Wordfence: get_class() without arguments (PHP 8.1+) ─────────
        if (
            ( $errno & ( E_DEPRECATED | E_USER_DEPRECATED ) )
            && str_contains( $errfile, DIRECTORY_SEPARATOR . 'wordfence' . DIRECTORY_SEPARATOR )
            && str_contains( $errstr, 'get_class() without arguments' )
        ) {
            return true;
        }

        // Everything else: delegate to default PHP error handling.
        return false;
    },
    E_DEPRECATED | E_USER_DEPRECATED | E_NOTICE | E_USER_NOTICE
);
