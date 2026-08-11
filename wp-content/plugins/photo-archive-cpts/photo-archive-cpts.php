<?php
/**
 * Plugin Name: Photo Archive CPTs
 * Plugin URI: https://photo.madsnorgaard.net
 * Description: Registers the Photo and Story custom post types for the documentary archive.
 * Version: 1.3.0
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
        'rest_base'         => 'series',
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
        'rest_base'         => 'subjects',
        'rewrite'           => [ 'slug' => 'subject' ],
        'show_admin_column' => true,
    ] );
}
add_action( 'init', 'pac_register_subject_taxonomy' );

/**
 * Photo metadata as native registered meta (no ACF).
 *
 * ACF 5.x used to define these four fields and surface them in REST via a
 * get_fields() merge; the keys and storage are identical plain wp_postmeta
 * rows, so this registration is drop-in data-compatible. The Nuxt frontend
 * reads meta.archive_number / location / date_taken / camera.
 */
function pac_sanitize_date_taken( $value ): string {
    $value = is_string( $value ) ? trim( $value ) : '';
    return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ? $value : '';
}

function pac_register_photo_meta(): void {
    $auth = static function ( $allowed, $meta_key, $object_id ) {
        return current_user_can( 'edit_post', $object_id );
    };

    register_post_meta( 'photo', 'archive_number', [
        'type'              => 'integer',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'absint',
        'auth_callback'     => $auth,
    ] );
    register_post_meta( 'photo', 'location', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => $auth,
    ] );
    register_post_meta( 'photo', 'date_taken', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'pac_sanitize_date_taken',
        'auth_callback'     => $auth,
    ] );
    register_post_meta( 'photo', 'camera', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => $auth,
    ] );
}
add_action( 'init', 'pac_register_photo_meta' );

/**
 * Admin metabox for the photo metadata (replaces the ACF field group UI).
 */
function pac_add_photo_metabox(): void {
    add_meta_box( 'pac_photo_metadata', __( 'Photo Metadata', 'photo-archive-cpts' ), 'pac_render_photo_metabox', 'photo', 'normal', 'high' );
}
add_action( 'add_meta_boxes_photo', 'pac_add_photo_metabox' );

function pac_render_photo_metabox( \WP_Post $post ): void {
    wp_nonce_field( 'pac_photo_meta', 'pac_photo_meta_nonce' );
    $archive_number = get_post_meta( $post->ID, 'archive_number', true );
    $location       = get_post_meta( $post->ID, 'location', true );
    $date_taken     = get_post_meta( $post->ID, 'date_taken', true );
    $camera         = get_post_meta( $post->ID, 'camera', true );
    ?>
    <p>
        <label for="pac_archive_number"><strong><?php esc_html_e( 'Archive Number', 'photo-archive-cpts' ); ?></strong></label><br>
        <input type="number" id="pac_archive_number" name="pac_archive_number" min="1" step="1" required
               value="<?php echo esc_attr( $archive_number ); ?>">
        <span class="description"><?php esc_html_e( 'Sequential archive number (e.g. 001, 042). Displayed prominently in the ledger.', 'photo-archive-cpts' ); ?></span>
    </p>
    <p>
        <label for="pac_location"><strong><?php esc_html_e( 'Location', 'photo-archive-cpts' ); ?></strong></label><br>
        <input type="text" id="pac_location" name="pac_location" class="regular-text"
               value="<?php echo esc_attr( $location ); ?>">
        <span class="description"><?php esc_html_e( 'Where the photograph was taken (e.g. "Kabul, Afghanistan")', 'photo-archive-cpts' ); ?></span>
    </p>
    <p>
        <label for="pac_date_taken"><strong><?php esc_html_e( 'Date Taken', 'photo-archive-cpts' ); ?></strong></label><br>
        <input type="date" id="pac_date_taken" name="pac_date_taken"
               value="<?php echo esc_attr( $date_taken ); ?>">
    </p>
    <p>
        <label for="pac_camera"><strong><?php esc_html_e( 'Camera', 'photo-archive-cpts' ); ?></strong></label><br>
        <input type="text" id="pac_camera" name="pac_camera" class="regular-text"
               value="<?php echo esc_attr( $camera ); ?>">
        <span class="description"><?php esc_html_e( 'Camera and/or film used', 'photo-archive-cpts' ); ?></span>
    </p>
    <?php
}

function pac_save_photo_metabox( int $post_id ): void {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! isset( $_POST['pac_photo_meta_nonce'] ) || ! wp_verify_nonce( $_POST['pac_photo_meta_nonce'], 'pac_photo_meta' ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = [
        'archive_number' => absint( $_POST['pac_archive_number'] ?? 0 ),
        'location'       => sanitize_text_field( wp_unslash( $_POST['pac_location'] ?? '' ) ),
        'date_taken'     => pac_sanitize_date_taken( wp_unslash( $_POST['pac_date_taken'] ?? '' ) ),
        'camera'         => sanitize_text_field( wp_unslash( $_POST['pac_camera'] ?? '' ) ),
    ];
    foreach ( $fields as $key => $value ) {
        if ( $value === '' || $value === 0 ) {
            delete_post_meta( $post_id, $key );
        } else {
            update_post_meta( $post_id, $key, $value );
        }
    }
}
add_action( 'save_post_photo', 'pac_save_photo_metabox' );

/**
 * Add structured block data to story REST responses.
 *
 * Parses Gutenberg blocks from post_content and returns a clean JSON
 * representation that the Nuxt frontend can render with custom Vue
 * components instead of relying on content.rendered HTML.
 *
 * When the request includes ?_resolve_photos=1 the response also
 * includes a resolved_photos map with full image data for every
 * photo referenced in photo-embed / photo-sequence blocks.
 */
function pac_add_blocks_data( \WP_REST_Response $response, \WP_Post $post, \WP_REST_Request $request ): \WP_REST_Response {
    $blocks = parse_blocks( $post->post_content );
    $blocks_data = pac_extract_blocks( $blocks );
    $response->data['blocks_data'] = $blocks_data;

    // Optionally resolve photo data for embedded photo references.
    $resolve = $request->get_param( '_resolve_photos' );
    if ( $resolve && in_array( $resolve, [ '1', 'true', true, 1 ], true ) ) {
        $photo_ids = pac_collect_photo_ids( $blocks_data );
        $response->data['resolved_photos'] = pac_resolve_photos( $photo_ids );
    }

    return $response;
}
add_filter( 'rest_prepare_story', 'pac_add_blocks_data', 20, 3 );

/**
 * Walk parsed blocks and build a structured array.
 *
 * Only allowlisted block types are included. Unknown blocks are
 * silently skipped so the API surface stays predictable.
 *
 * @param array $blocks Output of parse_blocks().
 * @return array Structured block data.
 */
function pac_extract_blocks( array $blocks ): array {
    $output = [];

    // Attribute allowlists per custom block type.
    $attr_allowlists = [
        'photo-archive-blocks/photo-embed'    => [ 'photoId', 'showCaption', 'alignment' ],
        'photo-archive-blocks/photo-sequence'  => [ 'photoIds', 'caption' ],
        'photo-archive-blocks/section-break'   => [ 'number' ],
    ];

    // Core blocks where we keep sanitised innerHTML.
    $core_content_blocks = [
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/list-item',
        'core/image',
        'core/quote',
    ];

    foreach ( $blocks as $block ) {
        $name = $block['blockName'] ?? null;

        // Skip empty freeform blocks (whitespace between blocks).
        if ( null === $name ) {
            continue;
        }

        // Custom blocks with explicit attribute allowlists.
        if ( isset( $attr_allowlists[ $name ] ) ) {
            $safe_attrs = [];
            foreach ( $attr_allowlists[ $name ] as $key ) {
                if ( isset( $block['attrs'][ $key ] ) ) {
                    $safe_attrs[ $key ] = $block['attrs'][ $key ];
                }
            }
            $output[] = [
                'type'  => str_replace( 'photo-archive-blocks/', '', $name ),
                'attrs' => $safe_attrs,
            ];
            continue;
        }

        // Pull-quote: keep sanitised inner HTML.
        if ( 'photo-archive-blocks/pull-quote' === $name ) {
            $output[] = [
                'type'    => 'pull-quote',
                'content' => wp_kses_post( trim( $block['innerHTML'] ?? '' ) ),
            ];
            continue;
        }

        // Core content blocks: keep sanitised inner HTML.
        if ( in_array( $name, $core_content_blocks, true ) ) {
            $html = trim( $block['innerHTML'] ?? '' );
            if ( '' === $html ) {
                continue;
            }
            $output[] = [
                'type'    => str_replace( 'core/', '', $name ),
                'content' => wp_kses_post( $html ),
            ];
            continue;
        }

        // All other blocks are silently skipped.
    }

    return $output;
}

/**
 * Collect unique photo IDs from structured blocks_data.
 *
 * @param array $blocks_data Output of pac_extract_blocks().
 * @return int[] Unique photo post IDs (max 50).
 */
function pac_collect_photo_ids( array $blocks_data ): array {
    $ids = [];

    foreach ( $blocks_data as $block ) {
        if ( 'photo-embed' === $block['type'] && ! empty( $block['attrs']['photoId'] ) ) {
            $ids[] = absint( $block['attrs']['photoId'] );
        }
        if ( 'photo-sequence' === $block['type'] && ! empty( $block['attrs']['photoIds'] ) ) {
            foreach ( $block['attrs']['photoIds'] as $id ) {
                $ids[] = absint( $id );
            }
        }
    }

    // Deduplicate and cap at 50 to prevent abuse.
    return array_slice( array_unique( array_filter( $ids ) ), 0, 50 );
}

/**
 * Resolve an array of photo post IDs into a map of photo data
 * with image URLs and photo metadata.
 *
 * @param int[] $photo_ids Photo post IDs.
 * @return array Associative array keyed by photo ID.
 */
function pac_resolve_photos( array $photo_ids ): array {
    $resolved = [];

    foreach ( $photo_ids as $photo_id ) {
        $post = get_post( $photo_id );
        if ( ! $post || 'photo' !== $post->post_type || 'publish' !== $post->post_status ) {
            continue;
        }

        $thumbnail_id = (int) get_post_thumbnail_id( $post->ID );
        $images       = [];
        if ( $thumbnail_id ) {
            foreach ( [ 'thumbnail', 'medium', 'large', 'full' ] as $size ) {
                $src = wp_get_attachment_image_src( $thumbnail_id, $size );
                if ( $src ) {
                    $images[ $size ] = [
                        'url'    => $src[0],
                        'width'  => $src[1],
                        'height' => $src[2],
                    ];
                }
            }
        }

        $archive_number = get_post_meta( $post->ID, 'archive_number', true );
        $location       = get_post_meta( $post->ID, 'location', true );
        $date_taken     = get_post_meta( $post->ID, 'date_taken', true );
        $camera         = get_post_meta( $post->ID, 'camera', true );

        $resolved[ $photo_id ] = [
            'id'            => $post->ID,
            'title'         => get_the_title( $post ),
            'slug'          => $post->post_name,
            'archiveNumber' => $archive_number ? str_pad( absint( $archive_number ), 3, '0', STR_PAD_LEFT ) : null,
            'location'      => $location ?: null,
            'dateTaken'     => $date_taken ?: null,
            'camera'        => $camera ?: null,
            'excerpt'       => get_the_excerpt( $post ),
            'images'        => $images,
        ];
    }

    return $resolved;
}

/**
 * Register the 'project' CPT + 'project_cat' taxonomy when mauer-stills-portfolio
 * is gone (headless cutover). Args are copied verbatim from that plugin so the
 * REST shape (/wp/v2/project, rewrite slug 'proj') is identical. While the
 * mauer plugin is active (it registers at init 0/10, we run at 15) these
 * guards make this a no-op, so the handoff is a pure plugin deactivation.
 */
function pac_register_project_fallback(): void {
    if ( ! post_type_exists( 'project' ) ) {
        register_post_type( 'project', [
            'labels'             => [
                'name'          => __( 'Portfolio', 'photo-archive-cpts' ),
                'singular_name' => __( 'Portfolio', 'photo-archive-cpts' ),
                'menu_name'     => __( 'Portfolio', 'photo-archive-cpts' ),
                'add_new_item'  => __( 'Add New Project', 'photo-archive-cpts' ),
                'edit_item'     => __( 'Edit Project', 'photo-archive-cpts' ),
                'all_items'     => __( 'All Projects', 'photo-archive-cpts' ),
            ],
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => [ 'slug' => 'proj' ],
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'supports'           => [ 'title', 'editor', 'thumbnail', 'comments', 'revisions' ],
            'show_in_rest'       => true,
        ] );
    }
    if ( ! taxonomy_exists( 'project_cat' ) ) {
        register_taxonomy( 'project_cat', [ 'project' ], [
            'labels'            => [
                'name'          => __( 'Project Categories', 'photo-archive-cpts' ),
                'singular_name' => __( 'Project Category', 'photo-archive-cpts' ),
                'menu_name'     => __( 'Project Categories', 'photo-archive-cpts' ),
            ],
            'show_ui'           => true,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'proj-cat' ],
            'sort'              => true,
        ] );
    }
}
add_action( 'init', 'pac_register_project_fallback', 15 );

/**
 * Enable REST API for the project_cat taxonomy (registered by mauer-stills-portfolio).
 *
 * The premium plugin does not set show_in_rest, so we patch it here
 * to make project categories available via the WP REST API.
 */
function pac_enable_project_cat_rest(): void {
    $tax = get_taxonomy( 'project_cat' );
    if ( $tax ) {
        $tax->show_in_rest = true;
        $tax->rest_base    = 'project-categories';
    }
}
add_action( 'init', 'pac_enable_project_cat_rest', 20 );

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
