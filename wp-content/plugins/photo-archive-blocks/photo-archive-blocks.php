<?php
/**
 * Plugin Name: Photo Archive Blocks
 * Plugin URI: https://photo.madsnorgaard.net
 * Description: Custom Gutenberg blocks for the photo archive: photo-embed, photo-sequence, pull-quote, section-break.
 * Version: 1.1.0
 * Author: Mads Nørgaard
 * Text Domain: photo-archive-blocks
 * Requires at least: 6.4
 * Requires PHP: 8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PAB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PAB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PAB_VERSION', '1.1.0' );

/**
 * Register all blocks.
 */
function pab_register_blocks(): void {
    // photo-embed block
    register_block_type( PAB_PLUGIN_DIR . 'blocks/photo-embed/' );

    // photo-sequence block
    register_block_type( PAB_PLUGIN_DIR . 'blocks/photo-sequence/' );

    // pull-quote block
    register_block_type( PAB_PLUGIN_DIR . 'blocks/pull-quote/' );

    // section-break block
    register_block_type( PAB_PLUGIN_DIR . 'blocks/section-break/' );
}
add_action( 'init', 'pab_register_blocks' );

/**
 * REST endpoint: single photo data for editor (Gutenberg block) preview.
 *
 * Restricted to users who can edit posts — this endpoint is only for the
 * block editor, not for the Nuxt frontend. The frontend uses /wp/v2/photos/{id}.
 */
function pab_register_rest_routes(): void {
    register_rest_route( 'photo-archive-blocks/v1', '/photo/(?P<id>\d+)', [
        'methods'             => \WP_REST_Server::READABLE,
        'callback'            => 'pab_get_photo_data',
        'permission_callback' => fn() => current_user_can( 'edit_posts' ),
        'args'                => [
            'id' => [
                'validate_callback' => fn( $v ) => is_numeric( $v ),
                'sanitize_callback' => 'absint',
            ],
        ],
    ] );
}
add_action( 'rest_api_init', 'pab_register_rest_routes' );

function pab_get_photo_data( \WP_REST_Request $request ): \WP_REST_Response|\WP_Error {
    $post = get_post( $request['id'] );

    // Only return published photos — editors should see drafts in the normal
    // block editor, not through this endpoint.
    if ( ! $post || 'photo' !== $post->post_type || 'publish' !== $post->post_status ) {
        return new \WP_Error( 'not_found', 'Photo not found', [ 'status' => 404 ] );
    }

    $archive_number = get_post_meta( $post->ID, 'archive_number', true );
    $thumbnail_url  = get_the_post_thumbnail_url( $post->ID, 'large' );

    return rest_ensure_response( [
        'id'             => $post->ID,
        'title'          => get_the_title( $post ),
        'archive_number' => $archive_number ? absint( $archive_number ) : null,
        'thumbnail'      => $thumbnail_url ?: null,
        'permalink'      => get_permalink( $post ),
        'excerpt'        => get_the_excerpt( $post ),
    ] );
}
