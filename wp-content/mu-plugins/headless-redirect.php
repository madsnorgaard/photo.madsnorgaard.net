<?php
/**
 * Plugin Name: Headless redirect
 * Description: photo.madsnorgaard.net is a headless backend — every public
 * HTML route 301s to its madsnorgaard.net equivalent (map:
 * docs/headless-cutover-audit.md). template_redirect structurally never fires
 * for /wp-json (REST short-circuits at parse_request), wp-admin, the hidden
 * login slug (wps-hide-login exits on wp_loaded), admin-ajax/cron, or static
 * /wp-content assets served by Apache — so none of those need excluding here.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const HEADLESS_REDIRECT_BASE = 'https://madsnorgaard.net';

add_action( 'template_redirect', function () {
    if ( is_admin() ) {
        return;
    }
    // Keep machine endpoints functional rather than bouncing them to HTML.
    if ( is_robots() || is_favicon() ) {
        return;
    }

    $base = HEADLESS_REDIRECT_BASE;

    if ( is_singular( 'project' ) ) {
        $target = $base . '/proj/' . get_post_field( 'post_name' );
    } elseif ( is_post_type_archive( 'project' ) || is_tax( 'project_cat' ) ) {
        $target = $base . '/archive';
    } elseif ( is_singular( 'post' ) ) {
        $target = $base . '/post/' . get_post_field( 'post_name' );
    } elseif ( is_category() ) {
        $target = $base . '/category/' . get_queried_object()->slug;
    } elseif ( is_page( 'biography' ) ) {
        $target = $base . '/cv';
    } else {
        // Home, remaining pages, feeds, date/author archives, search, 404s.
        $target = $base . '/';
    }

    // wp_redirect (not wp_safe_redirect): the target host is external by design.
    wp_redirect( $target, 301 );
    exit;
} );
