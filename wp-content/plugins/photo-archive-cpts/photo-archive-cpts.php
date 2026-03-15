<?php
/**
 * Plugin Name: Photo Archive CPTs
 * Plugin URI: https://photo.madsnorgaard.net
 * Description: Registers the Photo and Story custom post types for the documentary archive.
 * Version: 1.0.0
 * Author: Mads Nørgaard
 * Text Domain: photo-archive-cpts
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the 'photo' CPT.
 */
function pac_register_photo_cpt(): void {
    $labels = [
        'name'                  => __( 'Photos', 'photo-archive-cpts' ),
        'singular_name'         => __( 'Photo', 'photo-archive-cpts' ),
        'add_new_item'          => __( 'Add New Photo', 'photo-archive-cpts' ),
        'edit_item'             => __( 'Edit Photo', 'photo-archive-cpts' ),
        'new_item'              => __( 'New Photo', 'photo-archive-cpts' ),
        'view_item'             => __( 'View Photo', 'photo-archive-cpts' ),
        'search_items'          => __( 'Search Photos', 'photo-archive-cpts' ),
        'not_found'             => __( 'No photos found', 'photo-archive-cpts' ),
        'not_found_in_trash'    => __( 'No photos found in trash', 'photo-archive-cpts' ),
        'menu_name'             => __( 'Photos', 'photo-archive-cpts' ),
    ];

    register_post_type( 'photo', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true, // Gutenberg + REST API enabled
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'archive', 'with_front' => false ],
        'capability_type'    => 'post',
        'has_archive'        => 'archive',
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-format-image',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'rest_base'          => 'photos',
    ] );
}
add_action( 'init', 'pac_register_photo_cpt' );

/**
 * Register the 'story' CPT (documentary essays that embed photos).
 */
function pac_register_story_cpt(): void {
    $labels = [
        'name'               => __( 'Stories', 'photo-archive-cpts' ),
        'singular_name'      => __( 'Story', 'photo-archive-cpts' ),
        'add_new_item'       => __( 'Add New Story', 'photo-archive-cpts' ),
        'edit_item'          => __( 'Edit Story', 'photo-archive-cpts' ),
        'view_item'          => __( 'View Story', 'photo-archive-cpts' ),
        'search_items'       => __( 'Search Stories', 'photo-archive-cpts' ),
        'not_found'          => __( 'No stories found', 'photo-archive-cpts' ),
        'menu_name'          => __( 'Stories', 'photo-archive-cpts' ),
    ];

    register_post_type( 'story', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'stories' ],
        'capability_type'    => 'post',
        'has_archive'        => 'stories',
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-text-page',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'rest_base'          => 'stories',
    ] );
}
add_action( 'init', 'pac_register_story_cpt' );

/**
 * Register the 'series' taxonomy for photos.
 */
function pac_register_series_taxonomy(): void {
    $labels = [
        'name'          => __( 'Series', 'photo-archive-cpts' ),
        'singular_name' => __( 'Series', 'photo-archive-cpts' ),
        'search_items'  => __( 'Search Series', 'photo-archive-cpts' ),
        'all_items'     => __( 'All Series', 'photo-archive-cpts' ),
        'edit_item'     => __( 'Edit Series', 'photo-archive-cpts' ),
        'add_new_item'  => __( 'Add New Series', 'photo-archive-cpts' ),
        'menu_name'     => __( 'Series', 'photo-archive-cpts' ),
    ];

    register_taxonomy( 'series', [ 'photo', 'story' ], [
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'series' ],
        'show_admin_column' => true,
    ] );
}
add_action( 'init', 'pac_register_series_taxonomy' );

/**
 * Register 'subject' taxonomy (documentary subjects).
 */
function pac_register_subject_taxonomy(): void {
    register_taxonomy( 'subject', [ 'photo', 'story' ], [
        'labels'            => [
            'name'          => __( 'Subjects', 'photo-archive-cpts' ),
            'singular_name' => __( 'Subject', 'photo-archive-cpts' ),
            'menu_name'     => __( 'Subjects', 'photo-archive-cpts' ),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'subject' ],
        'show_admin_column' => true,
    ] );
}
add_action( 'init', 'pac_register_subject_taxonomy' );

/**
 * Register ACF field group for Photo metadata.
 * Falls back gracefully if ACF is not active.
 */
function pac_register_acf_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( [
        'key'      => 'group_photo_metadata',
        'title'    => 'Photo Metadata',
        'fields'   => [
            [
                'key'           => 'field_archive_number',
                'label'         => 'Archive Number',
                'name'          => 'archive_number',
                'type'          => 'number',
                'instructions'  => 'Sequential archive number (e.g. 001, 042). Displayed prominently in the ledger.',
                'required'      => 1,
                'min'           => 1,
                'step'          => 1,
            ],
            [
                'key'           => 'field_photo_location',
                'label'         => 'Location',
                'name'          => 'location',
                'type'          => 'text',
                'instructions'  => 'Where the photograph was taken (e.g. "Kabul, Afghanistan")',
                'required'      => 0,
            ],
            [
                'key'           => 'field_date_taken',
                'label'         => 'Date Taken',
                'name'          => 'date_taken',
                'type'          => 'date_picker',
                'display_format' => 'Y',
                'return_format'  => 'Y-m-d',
                'required'      => 0,
            ],
            [
                'key'           => 'field_camera',
                'label'         => 'Camera',
                'name'          => 'camera',
                'type'          => 'text',
                'instructions'  => 'Camera and/or film used',
                'required'      => 0,
            ],
        ],
        'location' => [
            [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'photo' ] ],
        ],
        'style'       => 'seamless',
        'label_placement' => 'top',
    ] );
}
add_action( 'acf/init', 'pac_register_acf_fields' );

/**
 * Expose ACF fields in the REST API for the photo CPT.
 */
function pac_expose_acf_in_rest( \WP_REST_Response $response, \WP_Post $post ): \WP_REST_Response {
    if ( 'photo' !== $post->post_type || ! function_exists( 'get_fields' ) ) {
        return $response;
    }
    $fields = get_fields( $post->ID );
    if ( $fields ) {
        $response->data['meta'] = array_merge(
            $response->data['meta'] ?? [],
            $fields
        );
    }
    return $response;
}
add_filter( 'rest_prepare_photo', 'pac_expose_acf_in_rest', 10, 2 );

/**
 * Flush rewrite rules on plugin activation.
 */
function pac_activate(): void {
    pac_register_photo_cpt();
    pac_register_story_cpt();
    pac_register_series_taxonomy();
    pac_register_subject_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pac_activate' );

/**
 * Flush rewrite rules on deactivation.
 */
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
