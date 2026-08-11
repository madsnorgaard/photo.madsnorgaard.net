<?php
/**
 * Plugin Name: Suppress known deprecation notices
 * Description: Silences PHP 8.x deprecation noise from third-party plugins we
 * cannot patch (currently only Wordfence's get_class() usage). Everything else
 * delegates to default error handling. Successor to suppress-acf5-notices.php,
 * whose ACF/Bootstrap blocks died with the 2026-08 headless cutover.
 */

// Must-use plugins load before regular plugins, so this handler is in place
// before Wordfence initializes. Returning true fully suppresses the error.
set_error_handler(
    static function ( int $errno, string $errstr, string $errfile ): bool {
        if (
            ( $errno & ( E_DEPRECATED | E_USER_DEPRECATED ) )
            && str_contains( $errfile, DIRECTORY_SEPARATOR . 'wordfence' . DIRECTORY_SEPARATOR )
            && str_contains( $errstr, 'get_class() without arguments' )
        ) {
            return true;
        }
        return false;
    },
    E_DEPRECATED | E_USER_DEPRECATED
);
