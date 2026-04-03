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
    // photo-embed block — dynamic render for content.rendered fallback
    register_block_type( PAB_PLUGIN_DIR . 'blocks/photo-embed/', [
        'render_callback' => 'pab_render_photo_embed',
    ] );

    // photo-sequence block — dynamic render for content.rendered fallback
    register_block_type( PAB_PLUGIN_DIR . 'blocks/photo-sequence/', [
        'render_callback' => 'pab_render_photo_sequence',
    ] );

    // pull-quote block
    register_block_type( PAB_PLUGIN_DIR . 'blocks/pull-quote/' );

    // section-break block
    register_block_type( PAB_PLUGIN_DIR . 'blocks/section-break/' );
}
add_action( 'init', 'pab_register_blocks' );

/**
 * Server-side render for photo-embed block.
 *
 * Produces semantic HTML so content.rendered includes a useful
 * fallback for SEO, RSS, and any consumer that does not use
 * the structured blocks_data endpoint.
 *
 * @param array $attrs Block attributes.
 * @return string HTML markup.
 */
function pab_render_photo_embed( array $attrs ): string {
    $photo_id = absint( $attrs['photoId'] ?? 0 );
    if ( ! $photo_id ) {
        return '';
    }

    $post = get_post( $photo_id );
    if ( ! $post || 'photo' !== $post->post_type || 'publish' !== $post->post_status ) {
        return '';
    }

    $show_caption = $attrs['showCaption'] ?? true;
    $alignment    = $attrs['alignment'] ?? 'none';
    $align_class  = 'none' !== $alignment ? ' align' . esc_attr( $alignment ) : '';

    $thumbnail_id = (int) get_post_thumbnail_id( $post->ID );
    if ( ! $thumbnail_id ) {
        return '';
    }

    $img = wp_get_attachment_image( $thumbnail_id, 'large', false, [
        'class'   => 'photo-embed__image',
        'loading' => 'lazy',
    ] );

    $archive_number = get_post_meta( $post->ID, 'archive_number', true );
    $number_display = $archive_number ? str_pad( absint( $archive_number ), 3, '0', STR_PAD_LEFT ) : '';
    $title          = esc_html( get_the_title( $post ) );
    $permalink      = esc_url( get_permalink( $post ) );

    $caption_html = '';
    if ( $show_caption ) {
        $caption_html = sprintf(
            '<figcaption class="photo-embed__caption"><span class="photo-embed__number">%s</span> <span class="photo-embed__title">%s</span></figcaption>',
            esc_html( $number_display ),
            $title
        );
    }

    return sprintf(
        '<figure class="wp-block-photo-archive-blocks-photo-embed photo-embed%s"><a href="%s">%s</a>%s</figure>',
        $align_class,
        $permalink,
        $img,
        $caption_html
    );
}

/**
 * Server-side render for photo-sequence block.
 *
 * Produces a horizontal strip of photo figures for content.rendered.
 *
 * @param array $attrs Block attributes.
 * @return string HTML markup.
 */
function pab_render_photo_sequence( array $attrs ): string {
    $photo_ids = $attrs['photoIds'] ?? [];
    if ( ! is_array( $photo_ids ) || empty( $photo_ids ) ) {
        return '';
    }

    $caption = $attrs['caption'] ?? '';
    $figures = '';

    foreach ( array_slice( $photo_ids, 0, 50 ) as $photo_id ) {
        $photo_id = absint( $photo_id );
        $post     = get_post( $photo_id );
        if ( ! $post || 'photo' !== $post->post_type || 'publish' !== $post->post_status ) {
            continue;
        }

        $thumbnail_id = (int) get_post_thumbnail_id( $post->ID );
        if ( ! $thumbnail_id ) {
            continue;
        }

        $img       = wp_get_attachment_image( $thumbnail_id, 'medium_large', false, [
            'class'   => 'photo-sequence__image',
            'loading' => 'lazy',
        ] );
        $title     = esc_html( get_the_title( $post ) );
        $permalink = esc_url( get_permalink( $post ) );

        $figures .= sprintf(
            '<figure class="photo-sequence__item"><a href="%s">%s</a><figcaption>%s</figcaption></figure>',
            $permalink,
            $img,
            $title
        );
    }

    if ( '' === $figures ) {
        return '';
    }

    $caption_html = '';
    if ( $caption ) {
        $caption_html = sprintf(
            '<p class="photo-sequence__caption">%s</p>',
            esc_html( $caption )
        );
    }

    return sprintf(
        '<div class="wp-block-photo-archive-blocks-photo-sequence photo-sequence">%s%s</div>',
        $figures,
        $caption_html
    );
}

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
