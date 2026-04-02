<?php
/**
 * Plugin Name: Plausible Analytics
 * Description: Privacy-first analytics via Plausible CE (self-hosted).
 */

add_action('wp_head', function() { ?>
<script defer data-domain="photo.madsnorgaard.net" src="https://analytics.theazanianprepper.online/js/script.file-downloads.hash.outbound-links.js"></script>
<script>window.plausible = window.plausible || function() { (window.plausible.q = window.plausible.q || []).push(arguments) }</script>
<?php });
