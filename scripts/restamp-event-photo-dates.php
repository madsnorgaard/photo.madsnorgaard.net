<?php
/**
 * Re-stamp every event_photo's post_date from its EXIF capture_date meta.
 *
 * The bulk importer inserts posts with post_date = time-of-import, so the wall's
 * "All nights" view (ordered by post date ascending) shows photos in IMPORT
 * order, not when they were taken. This walks all event_photo posts and sets
 * post_date / post_date_gmt to the recorded capture_date, so ordering by date
 * becomes true chronological order across nights AND within each night.
 *
 * Idempotent: re-running sets the same values. Safe to run repeatedly.
 *
 * Run (dry run first):
 *   DRY_RUN=1 ddev wp eval-file scripts/restamp-event-photo-dates.php
 * Then live:
 *   ddev wp eval-file scripts/restamp-event-photo-dates.php
 *
 * On production (inside the photo docker dir):
 *   docker compose run --rm -e DRY_RUN=1 cli \
 *     wp eval-file wp-content/uploads/_ingest/restamp-event-photo-dates.php
 *   docker compose run --rm cli \
 *     wp eval-file wp-content/uploads/_ingest/restamp-event-photo-dates.php
 */

global $wpdb;

$dry_run = getenv( 'DRY_RUN' ) === '1';
echo $dry_run ? "=== DRY RUN (no writes) ===\n\n" : "=== LIVE RE-STAMP ===\n\n";

if ( ! post_type_exists( 'event_photo' ) ) {
    echo "ERROR: event_photo CPT not registered. Activate the event-archive plugin first.\n";
    return;
}

$ids = get_posts( [
    'post_type'      => 'event_photo',
    'post_status'    => 'any',
    'fields'         => 'ids',
    'posts_per_page' => -1,
    'no_found_rows'  => true,
] );

echo 'Found ' . count( $ids ) . " event_photo posts.\n\n";

$updated   = 0;
$unchanged = 0;
$fallback  = 0;
$nodate    = 0;

foreach ( $ids as $id ) {
    $capture = get_post_meta( $id, 'capture_date', true );
    $ts      = $capture ? strtotime( $capture ) : false;

    // Fallback: no EXIF date -> use the night's date (YYYY-MM-DD parsed from the
    // event_set child term name) at noon, so it still lands in the right night.
    if ( ! $ts ) {
        $terms = wp_get_object_terms( $id, 'event_set' );
        foreach ( $terms as $t ) {
            if ( $t->parent && preg_match( '/(\d{4}-\d{2}-\d{2})/', $t->name, $m ) ) {
                $ts = strtotime( $m[1] . ' 12:00:00 UTC' );
                break;
            }
        }
        if ( $ts ) {
            $fallback++;
        } else {
            $nodate++;
            continue; // nothing to go on; leave post_date as-is.
        }
    }

    $new = gmdate( 'Y-m-d H:i:s', $ts );
    $cur = get_post_field( 'post_date', $id );

    if ( $cur === $new ) {
        $unchanged++;
        continue;
    }

    if ( $dry_run ) {
        if ( $updated < 10 ) {
            echo "  [dry] {$id}: {$cur} -> {$new}\n";
        }
        $updated++;
        continue;
    }

    // Direct UPDATE avoids wp_update_post side effects (and keeps it fast for
    // thousands of rows). Both post_date and post_date_gmt set to the UTC value
    // so 'orderby=date' is consistent.
    $wpdb->update(
        $wpdb->posts,
        [ 'post_date' => $new, 'post_date_gmt' => $new ],
        [ 'ID' => $id ],
        [ '%s', '%s' ],
        [ '%d' ]
    );
    clean_post_cache( $id );
    $updated++;

    if ( 0 === $updated % 250 ) {
        echo "  ... {$updated} re-stamped\n";
    }
}

echo "\n=== Done ===\n";
echo "Re-stamped:           {$updated}\n";
echo "Already correct:      {$unchanged}\n";
echo "Used night fallback:  {$fallback}\n";
echo "No date at all:       {$nodate}\n";
if ( $dry_run ) {
    echo "\nDRY RUN - re-run without DRY_RUN=1 to apply.\n";
}
