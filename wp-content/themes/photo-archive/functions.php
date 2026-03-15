<?php
/**
 * Photo Archive theme functions.
 *
 * @package photo-archive
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue theme styles and scripts.
 */
function photo_archive_enqueue(): void {
    // Google Fonts
    wp_enqueue_style(
        'photo-archive-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=IBM+Plex+Mono:wght@400;500&display=swap',
        [],
        null
    );

    // Theme stylesheet (style.css is registered automatically by WordPress for FSE themes)
    // Add any extra CSS here:
    wp_enqueue_style(
        'photo-archive-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [ 'photo-archive-fonts' ],
        wp_get_theme()->get( 'Version' )
    );

    // View Transitions + archive interactions
    wp_enqueue_script(
        'photo-archive-transitions',
        get_template_directory_uri() . '/assets/js/transitions.js',
        [],
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'photo_archive_enqueue' );

/**
 * Theme setup.
 */
function photo_archive_setup(): void {
    add_theme_support( 'block-templates' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ] );

    // Enable wide/full alignment for editorial layouts
    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'photo_archive_setup' );

/**
 * Add archive_number to the REST API response for ordering on the frontend.
 */
function photo_archive_rest_photo_meta( \WP_REST_Response $response, \WP_Post $post ): \WP_REST_Response {
    if ( 'photo' !== $post->post_type ) {
        return $response;
    }

    $archive_number = get_post_meta( $post->ID, 'archive_number', true );
    $response->data['archive_number'] = $archive_number ? (int) $archive_number : null;

    return $response;
}
add_filter( 'rest_prepare_photo', 'photo_archive_rest_photo_meta', 10, 2 );
