<?php
/*
Plugin Name: Mauer Stills Gallery
Plugin URI: http://stills.mauer.co/easy-galleries-with-half
Description: Adds a custom Gutenberg block for use with Mauer Themes' Stills. Has legacy support (via the [gallery] shortcode).
Author: Mauer Themes
Version: 1.1
Author URI: http://mauer.co
Text Domain: mauer-stills-gallery
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {exit;}





if (!function_exists('mauer_stills_gallery_plugin_init')) :
	function mauer_stills_gallery_plugin_init() {
		load_plugin_textdomain('mauer-stills-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/lang');
	}
endif;
add_action('init', 'mauer_stills_gallery_plugin_init');





if (!function_exists('mauer_stills_gallery_enqueue_scripts_and_styles')) :
	function mauer_stills_gallery_enqueue_scripts_and_styles() {
		$plugin_meta = get_plugin_data( __FILE__ );
		// styles
		wp_enqueue_style('photoswipe', plugins_url('/includes/photoSwipe/photoswipe.css', __FILE__ ), array(), $plugin_meta['Version']);
		wp_enqueue_style('photoswipe-default-skin', plugins_url('/includes/photoSwipe/default-skin/default-skin.css', __FILE__ ), array(), $plugin_meta['Version']);
		// scripts
		wp_enqueue_script('photoswipe', plugins_url('/includes/photoSwipe/photoswipe.min.js', __FILE__ ), array('jquery'), $plugin_meta['Version'], true);
		wp_enqueue_script('photoswipe-ui-default', plugins_url('/includes/photoSwipe/photoswipe-ui-default.min.js', __FILE__ ), array('photoswipe'), $plugin_meta['Version'], true);
		wp_enqueue_script('mauer-stills-gallery-photoswipe-builder', plugins_url('/js/photoSwipeGalleryBuilder.js', __FILE__ ), array('photoswipe-ui-default'), $plugin_meta['Version'], true);
	}
endif;
add_action('wp_enqueue_scripts', 'mauer_stills_gallery_enqueue_scripts_and_styles');





if (!function_exists('mauer_stills_gallery_assets')) :
	function mauer_stills_gallery_assets() {
		$plugin_meta = get_plugin_data( __FILE__ );
		wp_enqueue_script('mauer_stills_gallery_general', plugins_url( '/js/general.js', __FILE__ ), array(), $plugin_meta['Version'], true);
		// wp_enqueue_style('mauer_stills_gallery-style-css', plugins_url( '/css/style.css', __FILE__ ), array(), $plugin_meta['Version']);
	}
endif;
add_action( 'enqueue_block_assets', 'mauer_stills_gallery_assets' );





if (!function_exists('mauer_stills_gallery_editor_assets')) :
	function mauer_stills_gallery_editor_assets() {
		$plugin_meta = get_plugin_data( __FILE__ );
		wp_enqueue_script('mauer_stills_gallerys-build-js', plugins_url( '/dist/blocks.build.js', __FILE__ ), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-plugins', 'wp-components', 'wp-editor', 'wp-edit-post', 'wp-api' ), $plugin_meta['Version'], true);
		wp_enqueue_style('mauer_stills_gallery-editor-css', plugins_url( '/css/editor.css', __FILE__ ), array( 'wp-edit-blocks' ), $plugin_meta['Version']);
	}
endif;
add_action( 'enqueue_block_editor_assets', 'mauer_stills_gallery_editor_assets' );





if (!function_exists('mauer_stills_gallery_add_pswp_markup')) :

	function mauer_stills_gallery_add_pswp_markup() {
		ob_start(); ?>

		<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="pswp__bg"></div>
			<div class="pswp__scroll-wrap">

				<div class="pswp__container">
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
				</div>

				<div class="pswp__ui pswp__ui--hidden">
					<div class="pswp__top-bar">
						<button class="pswp__button pswp__button--close" title="<?php esc_attr_e('Close (Esc)', 'mauer-stills-gallery'); ?>"></button>
						<button class="pswp__button pswp__button--share" title="<?php esc_attr_e('Share', 'mauer-stills-gallery'); ?>"></button>
						<button class="pswp__button pswp__button--fs" title="<?php esc_attr_e('Toggle fullscreen', 'mauer-stills-gallery'); ?>"></button>
						<button class="pswp__button pswp__button--zoom" title="<?php esc_attr_e('Zoom in/out', 'mauer-stills-gallery'); ?>"></button>
						<div class="pswp__counter"></div>
						<div class="pswp__preloader">
							<div class="pswp__preloader__icn">
								<div class="pswp__preloader__cut">
									<div class="pswp__preloader__donut"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
						<div class="pswp__share-tooltip"></div>
					</div>
					<button class="pswp__button pswp__button--arrow--left" title="<?php esc_attr_e('Previous (arrow left)', 'mauer-stills-gallery'); ?>">
					</button>
					<button class="pswp__button pswp__button--arrow--right" title="<?php esc_attr_e('Next (arrow right)', 'mauer-stills-gallery'); ?>">
					</button>
					<div class="pswp__caption">
						<div class="pswp__caption__center"></div>
					</div>
				</div>
			</div>
		</div>

		<?php
		echo ob_get_clean();
	}

endif;

add_action( 'wp_footer', 'mauer_stills_gallery_add_pswp_markup', 100 );





// [gallery] shortcode handler for legacy support (if you're not using the Gutenberg block)
if (!function_exists('mauer_stills_gallery_shortcode_handler')) :

	function mauer_stills_gallery_shortcode_handler($output, $atts) {
		global $post;
		$atts = shortcode_atts(array(
				'order' => 'ASC',
				'orderby' => 'menu_order ID',
				'id' => $post->ID,
				'itemtag' => 'dl',
				'icontag' => 'dt',
				'captiontag' => 'dd',
				'columns' => 2,
				'size' => 'thumbnail',
				'include' => '',
				'exclude' => ''
		), $atts);

		$id = intval($atts['id']);
		if ('RAND' == $atts['order']) {$atts['orderby'] = 'none';};

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		} else {
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		}


		if (empty($attachments)) {return esc_html__('No images in the gallery', 'mauer-stills-gallery');}

		$output .= "<div class=\"mauer-stills-gallery-pswp-wrapper\">\n";
		$output .= "<div class=\"mauer-stills-gallery-pswp\" data-style=\"display: flex;\" itemscope itemtype=\"http://schema.org/ImageGallery\">\n";

		$img_num = 0;

		foreach ($attachments as $id => $attachment) {

			$class = 'mauer-stills-gallery-pswp-tile';
			$post_excerpt = $attachment->post_excerpt;
			$post_alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true);
			$magic_word = "#half";

			// half size (checking by the magic word in the caption)
			if (strpos(trim($post_excerpt), $magic_word) === 0) {
				$class .= ' mauer-stills-gallery-pswp-tile-half';
				$reg_thumb_size = 'mauer_stills_thumb_1';
			// full size
			} else {
				$class .= ' mauer-stills-gallery-pswp-tile-full';
				$reg_thumb_size = 'mauer_stills_thumb_6';
			}

			$post_excerpt = trim(str_replace($magic_word, "", $post_excerpt));

			$img_reg = wp_get_attachment_image_src($id, $reg_thumb_size);
			$img_full = wp_get_attachment_image_src($id, 'mauer_stills_thumb_7');

			// the width in PSWP galleries is controlled by percents of the .mauer-stills-gallery-pswp,
			// and .mauer-stills-gallery-pswp's width is determined via JS.
			$img_class = 'mauer-stills-img-adaptable-height mauer-stills-gallery-pswp-img';
			if ($post_excerpt) {$class .= " mauer-stills-pswp-tile-has-caption";}

			$output .=	"<figure class='" . esc_attr($class) . "' itemprop='associatedMedia' itemscope itemtype='http://schema.org/ImageObject'>";
			$output .=		"<a class='mauer-stills-gallery-pswp-big-img-link' href='" . esc_url($img_full[0]) . "' itemprop='contentUrl' data-size='" . esc_attr( $img_full[1] . "x" . $img_full[2] ) . "'>";
			$output .= 			"<img src='" . esc_url($img_reg[0]) . "' class='" . esc_attr($img_class) . "' alt='" . esc_attr($post_alt) . "' itemprop='thumbnail' />";
			$output .=		"</a>";
			if ($post_excerpt) {$output .=		"<figcaption itemprop='caption description'>" . esc_html($post_excerpt) . "</figcaption>";}
			$output .=	"</figure>";

			$img_num++;

		}

		$output .= "</div>";
		$output .= "</div>\n";

		return $output;

	}

endif;

add_filter('post_gallery', 'mauer_stills_gallery_shortcode_handler', 10, 2);





if (!function_exists('mauer_stills_filter_block_output')) :

	function mauer_stills_filter_block_output($block_content, $block) {
		if ('mauer-stills/gallery' === $block['blockName']) {
			$block_content = preg_replace('/(<figcaption.*?>.*?)(#half)(.*?<\/figcaption>)/', '$1$3', $block_content);
		}
		return $block_content;
	}

endif;

add_filter('render_block', 'mauer_stills_filter_block_output', 10, 2);


?>