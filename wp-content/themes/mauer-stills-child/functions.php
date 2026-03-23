<?php
/**
 * Child theme functions.
 * Enqueues child stylesheet after parent.
 */

add_action( 'wp_enqueue_scripts', 'mauer_stills_child_enqueue' );
function mauer_stills_child_enqueue(): void {
    wp_enqueue_style(
        'mauer-stills-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [ 'mauer-stills-stylesheet' ],
        wp_get_theme()->get( 'Version' )
    );
}
