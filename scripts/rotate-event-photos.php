<?php
/**
 * Rotate specific event_photo images to upright and regenerate their renditions.
 *
 * For photos that were exported sideways with no EXIF orientation flag (so
 * nothing auto-rotates them), this physically rotates the stored full image and
 * rebuilds every thumbnail size from it. Lossy by one re-encode (q92) — fine for
 * a one-off correction.
 *
 * Run (dry run first, always):
 *   IDS="2287,2290" DEG=90 DRY_RUN=1 \
 *     docker compose --profile cli run --rm -T -e IDS -e DEG -e DRY_RUN \
 *       cli wp eval-file - < /tmp/rotate-event-photos.php
 *
 * Then live (drop DRY_RUN):
 *   IDS="2287,2290" DEG=90 \
 *     docker compose --profile cli run --rm -T -e IDS -e DEG \
 *       cli wp eval-file - < /tmp/rotate-event-photos.php
 *
 * Options (environment variables):
 *   IDS    Comma-separated list of event_photo POST ids (the ?photo=<id> from the
 *          wall) or attachment ids.                                    (required)
 *   DEG    CLOCKWISE degrees to rotate to make the photo upright: 90, 180 or 270.
 *          Default 90 (portrait shot held with the grip up needs 90 CW).
 *   DRY_RUN=1   List what would change, touch nothing.
 *   FORCE=1     Rotate again even if already rotated by a previous run (use to
 *               correct a wrong direction; otherwise already-fixed ids are skipped).
 */

require_once ABSPATH . 'wp-admin/includes/image.php';

$ids   = array_values( array_filter( array_map( 'intval', explode( ',', (string) getenv( 'IDS' ) ) ) ) );
$deg   = (int) ( getenv( 'DEG' ) ?: 90 );
$dry   = getenv( 'DRY_RUN' ) === '1';
$force = getenv( 'FORCE' ) === '1';

echo $dry ? "=== DRY RUN (nothing will change) ===\n" : "=== LIVE ROTATE ===\n";

if ( empty( $ids ) ) {
    echo "ERROR: no IDS given.\n";
    return;
}
if ( ! in_array( $deg, [ 90, 180, 270 ], true ) ) {
    echo "ERROR: DEG must be 90, 180 or 270 (clockwise). Got '{$deg}'.\n";
    return;
}

// GD imagerotate() turns COUNTER-clockwise for positive angles, so convert the
// requested clockwise degrees.
$ccw = ( 360 - ( $deg % 360 ) ) % 360;

$done = 0;
$skip = 0;

foreach ( $ids as $id ) {
    $post = get_post( $id );
    if ( ! $post ) {
        echo "skip {$id}: no such post\n";
        $skip++;
        continue;
    }

    // Accept either the event_photo post id or the attachment id directly.
    $mid = ( 'attachment' === $post->post_type ) ? $id : (int) get_post_thumbnail_id( $id );
    if ( ! $mid ) {
        echo "skip {$id}: no attached image\n";
        $skip++;
        continue;
    }

    $already = (int) get_post_meta( $mid, '_orientation_fixed', true );
    if ( $already && ! $force ) {
        echo "skip {$id} (att {$mid}): already rotated {$already}CW — pass FORCE=1 to rotate again\n";
        $skip++;
        continue;
    }

    $full = get_attached_file( $mid );
    if ( ! $full || ! file_exists( $full ) ) {
        echo "skip {$id} (att {$mid}): file missing\n";
        $skip++;
        continue;
    }

    echo ( $dry ? '[dry] ' : '' ) . "rotate post {$id} (att {$mid}) {$deg}CW: " . basename( $full ) . "\n";
    if ( $dry ) {
        continue;
    }

    $src = @imagecreatefromjpeg( $full );
    if ( ! $src ) {
        echo "  ERROR: could not read JPEG\n";
        $skip++;
        continue;
    }

    // Remove the old size files (their names encode the old dimensions, so they'd
    // otherwise be orphaned after regeneration).
    $old = wp_get_attachment_metadata( $mid );
    if ( ! empty( $old['sizes'] ) ) {
        $dir = dirname( $full );
        foreach ( $old['sizes'] as $s ) {
            if ( ! empty( $s['file'] ) && file_exists( "{$dir}/{$s['file']}" ) ) {
                @unlink( "{$dir}/{$s['file']}" );
            }
        }
    }

    $rot = imagerotate( $src, $ccw, 0 );
    imagejpeg( $rot, $full, 92 );
    imagedestroy( $src );
    imagedestroy( $rot );

    // Rebuild all renditions from the now-upright full.
    $meta = wp_generate_attachment_metadata( $mid, $full );
    wp_update_attachment_metadata( $mid, $meta );

    // wp-cli runs as root; make the rewritten files web-readable (www-data = 33).
    @chown( $full, 33 ); @chgrp( $full, 33 );
    if ( ! empty( $meta['sizes'] ) ) {
        $dir = dirname( $full );
        foreach ( $meta['sizes'] as $s ) {
            if ( ! empty( $s['file'] ) ) {
                @chown( "{$dir}/{$s['file']}", 33 );
                @chgrp( "{$dir}/{$s['file']}", 33 );
            }
        }
    }

    update_post_meta( $mid, '_orientation_fixed', ( $already + $deg ) % 360 );

    echo '  done -> ' . ( $meta['width'] ?? '?' ) . 'x' . ( $meta['height'] ?? '?' ) . "\n";
    $done++;
}

echo "\n=== Done === rotated: {$done}  skipped: {$skip}\n";
if ( $dry ) {
    echo "This was a DRY RUN. Re-run without DRY_RUN=1 to rotate for real.\n";
}
