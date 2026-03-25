<?php
/**
 * Photography site settings.
 * - Disable the 2560px big image threshold (preserve originals).
 * - Raise WP memory limit for image processing.
 */

// Disable automatic downscaling of large images on upload.
add_filter( 'big_image_size_threshold', '__return_false' );

// Tell WP to use more memory for image operations.
if ( ! defined( 'WP_MEMORY_LIMIT' ) ) {
    define( 'WP_MEMORY_LIMIT', '512M' );
}
