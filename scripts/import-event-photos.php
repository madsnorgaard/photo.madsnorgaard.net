<?php
/**
 * Bulk-import event photographs into the event_photo CPT.
 *
 * Walks a base directory of per-night subfolders, sideloads every image, creates
 * one uncaptioned event_photo per file, assigns it to the matching event_set
 * child term (parent = the event), and records the EXIF capture date. Designed
 * for thousands of files: it is idempotent (safe to re-run) and logs progress.
 *
 * Run (dry run first, always):
 *   DRY_RUN=1 IMPORT_DIR=wp-content/uploads/_ingest/cold-turkey-cape-town \
 *     ddev wp eval-file scripts/import-event-photos.php
 *
 * Then live:
 *   IMPORT_DIR=wp-content/uploads/_ingest/cold-turkey-cape-town \
 *     ddev wp eval-file scripts/import-event-photos.php
 *
 * Options (environment variables):
 *   IMPORT_DIR   Base folder. Each immediate subfolder = one night/set.       (required)
 *   EVENT_SLUG   Parent event_set slug. Default: cold-turkey-cape-town.
 *   EVENT_NAME   Parent event_set display name. Default: "Cold Turkey Cape Town".
 *   DRY_RUN=1    Print intended actions, create nothing.
 *   LIMIT=N      Stop after N images (handy for a first live smoke test).
 *
 * ─── HOW TO EXPORT YOUR SETS (do this in Lightroom / Apple Photos first) ─────
 *   • One folder per night, named YYYY-MM-DD (the folder name becomes the set
 *     label and the filter chip on the wall). A trailing " - Free text" is
 *     allowed and shown as-is, e.g. "2011-03-13 - The Final Sunday".
 *   • Format:   JPEG, quality ~80 (the frontend re-encodes to AVIF/WebP at
 *               serve time, so the source only needs to be a clean JPEG).
 *   • Size:     long edge 2560 px. Big enough for a full-screen lightbox on
 *               retina, small enough that thousands of files stay manageable.
 *   • Colour:   sRGB (otherwise browsers that ignore profiles wash the colour).
 *   • Metadata: STRIP GPS / location, but KEEP the capture date/time
 *               (the importer reads EXIF DateTimeOriginal for ordering).
 *   • Keep your full-res / RAW originals OFFLINE as the master backup. Do not
 *     upload them: WordPress preserves originals and generates ~8 renditions,
 *     so multi-thousand 24MP uploads would balloon disk use.
 * ─────────────────────────────────────────────────────────────────────────────
 */

if ( ! function_exists( 'media_handle_sideload' ) ) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
}

// Generate only the sizes the wall uses (thumbnail/medium/large) for these
// imports - skips the ~7 documentary-theme renditions, so imports are far
// faster and lighter on disk. Scoped to this run.
add_filter( 'intermediate_image_sizes_advanced', static function ( $sizes ) {
    return array_intersect_key( $sizes, array_flip( [ 'thumbnail', 'medium', 'large' ] ) );
} );

// wp-cli runs as root here, so newly written files would be root-owned and the
// web server returns 403. chown each imported attachment (+ its sizes) to the
// web user (www-data = uid/gid 33 on the Debian WP image) so they serve.
function eva_chown_attachment( int $attach_id ): void {
    $file = get_attached_file( $attach_id );
    if ( ! $file ) { return; }
    @chown( $file, 33 ); @chgrp( $file, 33 );
    $meta = wp_get_attachment_metadata( $attach_id );
    if ( ! empty( $meta['sizes'] ) ) {
        $dir = dirname( $file );
        foreach ( $meta['sizes'] as $s ) {
            if ( ! empty( $s['file'] ) ) {
                @chown( "$dir/{$s['file']}", 33 );
                @chgrp( "$dir/{$s['file']}", 33 );
            }
        }
    }
}

$dry_run     = getenv( 'DRY_RUN' ) === '1';
$import_dir  = rtrim( (string) getenv( 'IMPORT_DIR' ), '/' );
$event_slug  = getenv( 'EVENT_SLUG' ) ?: ( defined( 'EVA_DEFAULT_EVENT_SLUG' ) ? EVA_DEFAULT_EVENT_SLUG : 'cold-turkey-cape-town' );
$event_name  = getenv( 'EVENT_NAME' ) ?: 'Cold Turkey Cape Town';
$limit       = (int) ( getenv( 'LIMIT' ) ?: 0 );

echo $dry_run ? "=== DRY RUN MODE (nothing will be created) ===\n\n" : "=== LIVE IMPORT MODE ===\n\n";

if ( ! post_type_exists( 'event_photo' ) ) {
    echo "ERROR: event_photo CPT not registered. Activate the event-archive plugin first.\n";
    return;
}
if ( '' === $import_dir || ! is_dir( $import_dir ) ) {
    echo "ERROR: IMPORT_DIR is not a directory: '{$import_dir}'\n";
    return;
}

/**
 * Find-or-create a term and return its term_id.
 */
function eva_ensure_term( string $name, string $slug, int $parent, bool $dry_run ): int {
    $existing = get_term_by( 'slug', $slug, 'event_set' );
    if ( $existing ) {
        return (int) $existing->term_id;
    }
    if ( $dry_run ) {
        echo "  [dry] would create term '{$name}' (slug: {$slug}, parent: {$parent})\n";
        return 0;
    }
    $res = wp_insert_term( $name, 'event_set', [ 'slug' => $slug, 'parent' => $parent ] );
    if ( is_wp_error( $res ) ) {
        echo "  WARN: could not create term '{$name}': " . $res->get_error_message() . "\n";
        return 0;
    }
    return (int) $res['term_id'];
}

// Ensure the parent event term exists.
$parent_term_id = eva_ensure_term( $event_name, $event_slug, 0, $dry_run );
echo "Parent event: '{$event_name}' (slug: {$event_slug}, term_id: {$parent_term_id})\n\n";

$image_exts = [ 'jpg', 'jpeg', 'png', 'webp', 'JPG', 'JPEG', 'PNG', 'WEBP' ];

$subdirs = glob( $import_dir . '/*', GLOB_ONLYDIR );
if ( empty( $subdirs ) ) {
    echo "No night subfolders found under {$import_dir}. Expected one folder per night.\n";
    return;
}

$total_imported = 0;
$total_skipped  = 0;

foreach ( $subdirs as $night_dir ) {
    $folder   = basename( $night_dir );
    // Folder "YYYY-MM-DD - Free text" -> slug "<event>-yyyy-mm-dd-free-text".
    $set_slug = sanitize_title( $event_slug . '-' . $folder );
    $set_name = $folder;

    echo "── Set: {$set_name}\n";
    $child_term_id = eva_ensure_term( $set_name, $set_slug, $parent_term_id, $dry_run );

    // Collect image files in this night folder.
    $files = [];
    foreach ( $image_exts as $ext ) {
        $files = array_merge( $files, glob( $night_dir . '/*.' . $ext ) ?: [] );
    }
    sort( $files );

    foreach ( $files as $file ) {
        if ( $limit && $total_imported >= $limit ) {
            echo "\nLIMIT of {$limit} reached - stopping.\n";
            break 2;
        }

        $source_name = basename( $file );

        // Idempotency: skip if an event_photo already has this source_filename.
        $dupes = get_posts( [
            'post_type'   => 'event_photo',
            'post_status' => 'any',
            'meta_key'    => 'source_filename',
            'meta_value'  => $source_name,
            'fields'      => 'ids',
            'numberposts' => 1,
        ] );
        if ( ! empty( $dupes ) ) {
            $total_skipped++;
            continue;
        }

        if ( $dry_run ) {
            echo "  [dry] would import {$source_name} -> set '{$set_name}'\n";
            $total_imported++;
            continue;
        }

        // Read EXIF capture date before sideload (wp_read_image_metadata wants a path).
        $capture_date = '';
        $meta         = @wp_read_image_metadata( $file );
        if ( ! empty( $meta['created_timestamp'] ) ) {
            $capture_date = gmdate( 'c', (int) $meta['created_timestamp'] );
        }

        // Create the (captionless) post first so we can attach media to it.
        $post_id = wp_insert_post( [
            'post_type'   => 'event_photo',
            'post_status' => 'publish',
            // Non-displayed title for wp-admin legibility only; the frontend
            // never renders it. Derived from the filename, not a caption.
            'post_title'  => pathinfo( $source_name, PATHINFO_FILENAME ),
        ], true );

        if ( is_wp_error( $post_id ) ) {
            echo "  WARN: post insert failed for {$source_name}: " . $post_id->get_error_message() . "\n";
            continue;
        }

        // Sideload the file into the media library (generates all renditions).
        $file_array = [ 'name' => $source_name, 'tmp_name' => $file ];
        // Copy to a temp location media_handle_sideload can safely move.
        $tmp = wp_tempnam( $source_name );
        if ( $tmp && copy( $file, $tmp ) ) {
            $file_array['tmp_name'] = $tmp;
        }
        $attach_id = media_handle_sideload( $file_array, $post_id );

        if ( is_wp_error( $attach_id ) ) {
            echo "  WARN: sideload failed for {$source_name}: " . $attach_id->get_error_message() . "\n";
            wp_delete_post( $post_id, true );
            @unlink( $tmp );
            continue;
        }

        set_post_thumbnail( $post_id, $attach_id );
        eva_chown_attachment( $attach_id ); // make it web-readable (no more 403)
        update_post_meta( $post_id, 'source_filename', $source_name );
        update_post_meta( $post_id, 'like_count', 0 );
        update_post_meta( $post_id, 'there_count', 0 );
        if ( $capture_date ) {
            update_post_meta( $post_id, 'capture_date', $capture_date );
        }
        if ( $child_term_id ) {
            wp_set_object_terms( $post_id, [ $child_term_id ], 'event_set' );
        }

        $total_imported++;
        if ( 0 === $total_imported % 50 ) {
            echo "  ... {$total_imported} imported\n";
        }
    }
}

echo "\n=== Done ===\n";
echo "Imported: {$total_imported}\n";
echo "Skipped (already present): {$total_skipped}\n";
if ( $dry_run ) {
    echo "\nThis was a DRY RUN. Re-run without DRY_RUN=1 to import for real.\n";
}
