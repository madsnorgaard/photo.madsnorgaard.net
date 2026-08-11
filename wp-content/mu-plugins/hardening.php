<?php
/**
 * Plugin Name: Hardening fallback
 * Description: Defines file-modification lockdown constants when wp-config
 * hasn't (production sets them via WORDPRESS_CONFIG_EXTRA in docker-compose;
 * this covers local DDEV, whose wp-config.php is ddev-generated and
 * shouldn't be edited). Constants are checked at admin runtime, so defining
 * them at mu-plugin load is early enough.
 */

if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}
if ( ! defined( 'DISALLOW_FILE_MODS' ) ) {
    define( 'DISALLOW_FILE_MODS', true );
}
