<?php
/*
 * functions.php
 */

if (!isset($content_width)) {$content_width = 1280;}





if (!function_exists('mauer_stills_theme_setup')) :

	function mauer_stills_theme_setup() {
		# Includes
		include_once(get_template_directory() . '/includes/wp-bootstrap-navwalker/wp-bootstrap-navwalker.php');
		require_once(get_template_directory() . '/includes/TGMPA/class-tgm-plugin-activation.php');
		include_once(get_template_directory() . '/includes/acf-fields.php' );
		include_once(get_template_directory() . '/inline-css.php' );
		# Theme features
		register_nav_menu('primary', esc_html__('Primary Menu', 'mauer-stills'));
		add_theme_support('post-thumbnails');
		add_theme_support('automatic-feed-links');
		add_theme_support('title-tag');
		add_theme_support('wp-block-styles');
		add_theme_support( 'responsive-embeds' );
		add_theme_support('editor-styles');
		if (intval(substr(get_bloginfo('version'), 0, strpos(get_bloginfo('version'), '.'))) >= 5) { // WP5 and newer
			add_editor_style('/css/editor-styles.css');
		}

		mauer_stills_add_image_sizes();

		# Other
		load_theme_textdomain('mauer-stills', get_template_directory() . '/lang');
		update_option('image_default_link_type','none');
		update_option('image_default_align', 'none');
		set_user_setting('align', 'none'); // since WP 4.4 user setting overrides the option
		update_option('image_default_size', 'full');
		set_user_setting('imgsize', 'full'); // since WP 4.4 user setting overrides the option

	}

endif;

add_action('after_setup_theme', 'mauer_stills_theme_setup');





if (!function_exists('mauer_stills_after_switch_theme')) :
	function mauer_stills_after_switch_theme() {
	  update_option("date_format", "M j, Y");
	}
endif;

add_action('after_switch_theme', 'mauer_stills_after_switch_theme');





if (!function_exists('mauer_stills_enqueue_scripts_and_styles')) :

	function mauer_stills_enqueue_scripts_and_styles() {

		$the_theme = wp_get_theme(); $the_theme_ver = $the_theme->get('Version');

		# Styles
		wp_enqueue_style('bootstrap', get_template_directory_uri() . "/includes/bootstrap/css/bootstrap.min.css", array(), $the_theme_ver);
		if (mauer_stills_should_load_bundled_font()) {wp_enqueue_style('HKGrotesk', get_template_directory_uri() . '/fonts/HKGrotesk/stylesheet.css', array(), $the_theme_ver);}
		wp_enqueue_style('mauer-stills-google-fonts', mauer_stills_google_fonts_url(), array(), $the_theme_ver);
		wp_enqueue_style('font-awesome', get_template_directory_uri() . '/includes/font-awesome/css/font-awesome.min.css', array(), $the_theme_ver);

		// Making theme stylesheet depend on bootstrap so that it loads after bootstrap and overrides its styles
		wp_enqueue_style('mauer-stills-stylesheet', get_template_directory_uri() . '/style.css', array('bootstrap'), $the_theme_ver);

		// Show sharing buttons when a project is used as a front page
		// and Jetpack is configured not to show the buttons on 'Front Page, Archive Pages, and Search Results'.
		if (class_exists('Jetpack')
		&& in_array('sharedaddy', Jetpack::get_active_modules())
		&& is_front_page()
		&& (function_exists('get_field') && get_field('custom_front_page_displays') == 'project'))
		{
			wp_enqueue_style('sharedaddy-sharing', plugins_url() .'/jetpack/modules/sharedaddy/sharing.css', array(), $the_theme_ver);
			wp_enqueue_style('jetpack-social-logos', plugins_url() . '/jetpack/_inc/social-logos/social-logos.min.css', array(), $the_theme_ver);
		}


		# Scripts
		// Setting the second parameter of wp_enqueue_script to true forces WP to place the script in the footer
		// (however if it belongs to a group which is loaded in the header this can be overriden)
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/includes/bootstrap/js/bootstrap.min.js', array('jquery'), $the_theme_ver, true);
		wp_enqueue_script('jquery-masonry', '', array('jquery', 'imagesLoaded'), $the_theme_ver, true);
		wp_enqueue_script('placeholdersJS', get_template_directory_uri() . '/includes/placeholdersJS/placeholders.jquery.min.js', array('jquery'), $the_theme_ver, true);
		wp_enqueue_script('textareaAutosize', get_template_directory_uri() . '/includes/textareaAutosize/dist/autosize.min.js', array('jquery'), $the_theme_ver, true);
		wp_enqueue_script('mauer-stills-general-js', get_template_directory_uri() . '/js/general.js', array('jquery'), $the_theme_ver, true);
		if (is_singular() && get_option('thread_comments')) {wp_enqueue_script('comment-reply');}
		wp_enqueue_script('flexibilityJS', get_template_directory_uri() . '/includes/flexibilityJS/flexibility.js', array('jquery'), $the_theme_ver, true);

		//HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries
		wp_enqueue_script('html5shiv', get_template_directory_uri() . '/includes/html5Shiv/html5shiv.min.js', array('jquery'), $the_theme_ver, false);
		wp_script_add_data('html5shiv', 'conditional', 'lt IE 9');
		wp_enqueue_script('respond', get_template_directory_uri() . '/includes/respondJS/respond.min.js', array('jquery'), $the_theme_ver, false);
		wp_script_add_data('respond', 'conditional', 'lt IE 9');

	}

endif;

add_action('wp_enqueue_scripts', 'mauer_stills_enqueue_scripts_and_styles');





if (!function_exists('mauer_stills_should_load_bundled_font')) :

	function mauer_stills_should_load_bundled_font() {
		$should_load = true;
		if (function_exists('get_field')) {
			if (get_field('custom_typography', 'option')) {
				$custom_fonts = mauer_stills_get_custom_fonts_details();
				$should_load = false;
				for ($j=1; $j <= count($custom_fonts) ; $j++) {
					if ($custom_fonts[$j]['source'] == 'bundled') {
						$should_load = true;
						break;
					}
				}
			}
		}
		return $should_load;
	}

endif;





if (!function_exists('mauer_stills_enqueue_admin_scripts_and_styles')) :
	function mauer_stills_enqueue_admin_scripts_and_styles() {
		$the_theme = wp_get_theme(); $the_theme_ver = $the_theme->get('Version');
		wp_enqueue_style('mauer-stills-admin-styles', get_template_directory_uri() . '/css/admin-styles.css', array(), $the_theme_ver);
		wp_enqueue_script('mauer-stills-admin-scripts', get_template_directory_uri() . '/js/adminScripts.js', array('jquery'), $the_theme_ver, true);
		$mauer_stills_admin_scripts_translation = array(
			'message1' => esc_html__('The font scheme will now be preloaded into the fields. It will be applied to the website, when you click the Update button.', 'mauer-stills'),
			'message2' => esc_html__('The color scheme will now be preloaded into the fields. It will be applied to the website, when you click the Update button.', 'mauer-stills'),
			'message3' => esc_html__('of 220–270 recommended', 'mauer-stills'),
			'themeUrl' => get_template_directory_uri(),
		);
		wp_localize_script('mauer-stills-admin-scripts', 'mauerStillsAdminScriptsTranslationObject', $mauer_stills_admin_scripts_translation);

		wp_enqueue_style('mauer-stills-admin-google-fonts', mauer_stills_google_fonts_url(), array(), $the_theme_ver);
		wp_enqueue_style('mauer-stills-admin-hk-grotesk', get_template_directory_uri() . '/fonts/HKGrotesk/stylesheet.css', array(), $the_theme_ver);
	}
endif;

add_action('admin_enqueue_scripts', 'mauer_stills_enqueue_admin_scripts_and_styles');





if (!function_exists('mauer_stills_update_acf_settings')) :
	function mauer_stills_update_acf_settings() {
		acf_update_setting('show_admin', false); // Hide ACF field group menu item.
		acf_update_setting('show_updates', false); // Hide update nag and and eliminate TGMPA update conflict.
	}
endif;
add_action('acf/init', 'mauer_stills_update_acf_settings');





if (!function_exists('mauer_stills_acf_control_special_field_1_appearance')) :
	function mauer_stills_acf_control_special_field_1_appearance($field) {
		if (function_exists('get_field') && get_field('portfolio_layout', 'option') != 'flow_2cols') { $field['wrapper']['class'] = 'hidden';}
		return $field;
	}
endif;

add_filter('acf/load_field/name=smaller_featured_image', 'mauer_stills_acf_control_special_field_1_appearance');





if (!function_exists('mauer_stills_acf_control_special_field_2_appearance')) :
	function mauer_stills_acf_control_special_field_2_appearance($field) {
		if (function_exists('get_field') && get_field('portfolio_layout', 'option') == 'flow_2cols') {$field['wrapper']['class'] = 'hidden';}
		return $field;
	}
endif;

add_filter('acf/load_field/name=additional_fields_special_message', 'mauer_stills_acf_control_special_field_2_appearance');





if (!function_exists('mauer_stills_acf_add_options_page')) :

	function mauer_stills_acf_add_options_page() {
		if(function_exists('acf_add_options_page')) {

			acf_add_options_page(array(
				'menu_title'	=> esc_html__('Theme Options', 'mauer-stills'),
				'menu_slug' 	=> 'mauer-theme-options',
				'redirect'		=> true
			));

			acf_add_options_sub_page(array(
				'menu_title'	=> esc_html__('General', 'mauer-stills'),
				'parent_slug'	=> 'mauer-theme-options',
			));

			acf_add_options_sub_page(array(
				'menu_title'	=> esc_html__('Colors', 'mauer-stills'),
				'parent_slug'	=> 'mauer-theme-options',
			));

			acf_add_options_sub_page(array(
				'menu_title'	=> esc_html__('Fonts', 'mauer-stills'),
				'parent_slug'	=> 'mauer-theme-options',
			));

		}
	}

endif;

add_action('init', 'mauer_stills_acf_add_options_page');





if (!function_exists('mauer_stills_add_image_sizes')) :

	function mauer_stills_add_image_sizes() {
		// Most image sizes are upscaled - for better sharpness, typically by around 30%.
		// When changing these sizes, mind that the 'Load More' button max-width CSS rule is based on how wide an image can get.
		add_image_size('mauer_stills_thumb_1', 780);				// PSWP half-wide thumb
		add_image_size('mauer_stills_thumb_2', 892, 920);		// 2-col masonry portfolio thumbs
		add_image_size('mauer_stills_thumb_3', 624);				// blog post thumb in list and in masonry, page (alternative) cover
		add_image_size('mauer_stills_thumb_4', 586, 586);		// 3-col portfolio thumbs, related projects thumbs
		add_image_size('mauer_stills_thumb_5', 420);				// 4-col masonry portfolio thumbs, related blog posts thumbs
		add_image_size('mauer_stills_thumb_6', 1440, 1440);	// PSWP full-width thumb, post and page covers, Facebook OG Images.
		add_image_size('mauer_stills_thumb_7', 2100, 2100);	// PSWP full-size images

	}

endif;





// Alters the 'big image size' threshold that has appeared in WP 5.3
add_filter('big_image_size_threshold', '4000', 9999);





if (!function_exists('mauer_stills_wp_link_pages_parameters')) :

	function mauer_stills_wp_link_pages_parameters() {
		$r = array(
			'before'           => '<div class="mauer-wp-linked-pages-holder"><p>' . esc_html__('Pages:', 'mauer-stills'),
			'after'            => '</p></div>',
			'link_before'      => '',
			'link_after'       => '',
			'next_or_number'   => 'number',
			'separator'        => '&nbsp; ',
			'nextpagelink'     => esc_html__('Next page', 'mauer-stills'),
			'previouspagelink' => esc_html__('Previous page', 'mauer-stills'),
			'pagelink'         => '%',
			'echo'             => 1
		);
		return $r;
	}

endif;





if (!function_exists('mauer_stills_append_page_links_to_content')) :

	function mauer_stills_append_page_links_to_content($content) {
		if (is_singular() && is_main_query()) {
			ob_start();
			wp_link_pages(mauer_stills_wp_link_pages_parameters());
			$new_content = ob_get_clean();
			$content .= $new_content;
		}
		return $content;
	}

endif;

add_filter('the_content', 'mauer_stills_append_page_links_to_content');





if (!function_exists('mauer_stills_width_attribute_removal')) :

	// This does not work for the already existing images.
	// A CSS solution has been implemented. This code is here for older browsers.
	function mauer_stills_width_attribute_removal( $html ) {
		 $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
		 return $html;
	}

endif;

add_filter('post_thumbnail_html', 'mauer_stills_width_attribute_removal', 100);
add_filter('image_send_to_editor', 'mauer_stills_width_attribute_removal', 100);





// Just in case
if (!function_exists('mauer_stills_strip_half_tag_from_content_output')) :
	function mauer_stills_strip_half_tag_from_content_output($content) {
		$content = preg_replace('/(<p.*?class="wp-caption-text">.*?)(#half)(.*?<\/p>)/', '$1$3', $content);
		return $content;
	}
endif;

add_filter('the_content','mauer_stills_strip_half_tag_from_content_output', 9999);





if (!function_exists('mauer_stills_wp_embed_wrapper')) :
	function mauer_stills_wp_embed_wrapper($html) {
		return '<div class="mauer-wp-embed-wrapper">' . $html . '</div>';
	}
endif;
add_filter('embed_oembed_html', 'mauer_stills_wp_embed_wrapper', 10, 1);
// When Jetpack's 'Shortcode Embeds' module is active (see /wp-admin/admin.php?page=jetpack_modules),
// it removes the embed_oembed_html filter and introduces its own video_embed_html.
// It also wraps the output html in a span.
add_filter('video_embed_html', 'mauer_stills_wp_embed_wrapper', 10, 1);




if (!function_exists('mauer_stills_favicon_fallback')) :

	function mauer_stills_favicon_fallback($tags) {
		// if there's no site icon set in Appearance > Customize, use a fallback favicon.
		if (!has_site_icon()) {

			if (function_exists('get_field') && get_field('favicon_fallback', 'option')) {
				$monogram = get_field('favicon_fallback', 'option');
			} else {
				$monogram = substr(get_bloginfo("name"), 0, 1);
			}

			if (preg_match("/^[a-zA-Z]/", $monogram, $matches)) {
				$the_letter = strtolower($matches[0]);
			} else {
				$the_letter = 'universal';
			}

			$fallback_favicon_url = get_template_directory_uri() . '/img/favicons/' . $the_letter . '.png';
			echo '<link rel="icon" type="image/png" href="' . esc_url($fallback_favicon_url) . '" />';
		}
	}

endif;

add_filter('wp_head', 'mauer_stills_favicon_fallback');





if (!function_exists('mauer_stills_get_portfolio_layout_setting')) :

	function mauer_stills_get_portfolio_layout_setting() {
		$r = "";

		if (isset($_GET['portfolio_demo_layout'])) {$layout_style = $_GET['portfolio_demo_layout'];}

		$possible_layouts = array('flow_2cols', 'grid_3cols', 'masonry_4cols');

		if (isset($layout_style) && in_array($layout_style, $possible_layouts)) {
			$r = $layout_style;
		}
		elseif(function_exists('get_field') && get_field('portfolio_layout', 'option')) {
			$r = get_field('portfolio_layout', 'option');
		}
		else {
			$r = "grid_3cols";
		}

		return $r;
	}

endif;





if (!function_exists('mauer_stills_get_blog_layout_setting')) :

	function mauer_stills_get_blog_layout_setting() {
		$r = "";

		if (isset($_GET['blog_demo_layout'])) {$layout_style = $_GET['blog_demo_layout'];}

		$possible_layouts = array('list', 'flow_2cols', 'grid_2cols');

		if (isset($layout_style) && in_array($layout_style, $possible_layouts)) {
			$r = $layout_style;
		}
		elseif(function_exists('get_field') && get_field('blog_layout', 'option')) {
			$r = get_field('blog_layout', 'option');
		}
		else {
			$r = 'flow_2cols';
		}

		return $r;
	}

endif;





if (!function_exists('mauer_stills_get_project_posts_per_page')) :

	function mauer_stills_get_project_posts_per_page() {
		// default value
		$project_posts_per_page = 9;

		// for portfolio page
		if ((!isset($_GET["portfolio_demo_layout"])) && (function_exists('get_field'))) {
			$project_posts_per_page = get_field('project_posts_per_page','option');
		}
		// for demo purposes
		else {
			$demo_layout_style = $_GET["portfolio_demo_layout"];
			switch ($demo_layout_style) {
				case 'flow_2cols':
					$project_posts_per_page = 9;
					break;
				case 'grid_3cols':
					$project_posts_per_page = 9;
					break;
				case 'masonry_4cols':
					$project_posts_per_page = 11;
					break;
			}
		}

		// for serving the requested amount of projects at the end of single project page
		if (isset($_GET['project_posts_per_page'])) {$project_posts_per_page = $_GET['project_posts_per_page'];}

		return $project_posts_per_page;
	}

endif;





if (!function_exists('mauer_stills_get_post_thumbnail_id')) :

	function mauer_stills_get_post_thumbnail_id($post_thumbnail_id) {
		$r = $post_thumbnail_id;
		if (isset($_GET["demo_swap_thumbs"])) {
			$thumbs_to_swap = explode('~', $_GET["demo_swap_thumbs"]);
			foreach ($thumbs_to_swap as $k => $thumb_to_swap) {
				list($initial, $substitute) = explode('for', $thumb_to_swap, 2);
				$substitutes[$initial] = $substitute;
			}
			if (array_key_exists($post_thumbnail_id, $substitutes)) {
				$r = $substitutes[$post_thumbnail_id];
			}
		}
		return $r;
	}

endif;





if (!function_exists('mauer_stills_swap_thumbs')) :

	function mauer_stills_swap_thumbs($html, $post_id, $post_thumbnail_id, $size) {
		$filtered_id = mauer_stills_get_post_thumbnail_id($post_thumbnail_id);
		if ($filtered_id && $filtered_id != $post_thumbnail_id && wp_get_attachment_image($filtered_id, $size)) {
			$html = str_replace('<img ', '<img data-demo-swapped-thumb-for="' . $filtered_id . '" ', wp_get_attachment_image($filtered_id, $size));
		}
		return $html;
	}

endif;

add_filter('post_thumbnail_html', 'mauer_stills_swap_thumbs', 10, 4);





if (!function_exists('mauer_stills_display_portfolio_categories_menu')) :

	function mauer_stills_display_portfolio_categories_menu() {
		if (function_exists('get_field')):
			if (get_field('show_portfolio_categories_menu', 'option')):
				echo '<ul class="portfolio-categories">';
					if (is_tax('project_cat')) {$show_option_all = _x( 'All', 'the first item of the Portfolio categories menu', 'mauer-stills' );}
					else {$show_option_all = false;}
					wp_list_categories(array('taxonomy'=>'project_cat', 'orderby'=>'slug', 'title_li'=>'', 'separator'=>'', 'style'=>'list', 'show_option_all'=>$show_option_all));
				echo '</ul>';
			endif;
		endif;
	}

endif;





if (!function_exists('mauer_stills_get_the_term_list')) :

	function mauer_stills_get_the_term_list($id, $taxonomy, $separator = " ") {
		$r = "";
		$terms = wp_get_object_terms($id, $taxonomy);
		if (!empty($terms)) {
			foreach ($terms as $k => $a_term) {
				$r .= $a_term->name;
				if ($k != count($terms)-1) {$r .= $separator;}
			}
		}
		return $r;
	}

endif;





if (!function_exists('mauer_stills_cpt_posts_per_page')) :

	function mauer_stills_cpt_posts_per_page($query) {
		if (!is_admin() && $query->is_main_query()) {

			if (isset($_GET["portfolio_demo_layout"])) {
				$query->set('posts_per_page', mauer_stills_get_project_posts_per_page());
			}
			else {
				if (($query->is_post_type_archive('project') || is_tax('project_cat')) && get_field('project_posts_per_page','option')) {
					$query->set('posts_per_page', mauer_stills_get_project_posts_per_page());
				}
			}

		}
	}

endif;

add_action('pre_get_posts', 'mauer_stills_cpt_posts_per_page');





if (!function_exists('mauer_stills_copyright_text')) :

	function mauer_stills_copyright_text() {
		if (function_exists('get_field')) {
			$r = "";
			$text = get_field('copyright_text', 'option');
			if (mb_strlen(trim($text))!=0) {
				$r .= "&copy; ";
				if (get_field('copyright_include_year', 'option')) {
					if (get_field('copyright_year', 'option')) {
						if (get_field('copyright_year', 'option') == date('Y')) {$r .= date('Y') . " ";}
						elseif (get_field('copyright_year', 'option') < date('Y')) {$r .= get_field('copyright_year', 'option') . "&ndash;" .  date('Y') . " ";}
					} else {
						$r .= date('Y') . " ";
					}
				}
				$r .= $text;
				return $r;
			}
		}
	}

endif;





if (!function_exists('mauer_stills_register_widgetized_areas')) :

	function mauer_stills_register_widgetized_areas() {
			register_sidebar(array(
				'name'          => esc_html__('Blog widgets 1 - Leftmost', 'mauer-stills'),
				'id'            => 'widgetized-area-1',
				'description'   => esc_html__('Widgetized area', 'mauer-stills'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>'
			));
			register_sidebar(array(
				'name'          => esc_html__('Blog widgets 2 - Center Left', 'mauer-stills'),
				'id'            => 'widgetized-area-2',
				'description'   => esc_html__('Widgetized area', 'mauer-stills'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>'
			));
			register_sidebar(array(
				'name'          => esc_html__('Blog widgets 3 - Center Right', 'mauer-stills'),
				'id'            => 'widgetized-area-3',
				'description'   => esc_html__('Widgetized area', 'mauer-stills'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>'
			));
			register_sidebar(array(
				'name'          => esc_html__('Blog widgets 4 - Rightmost', 'mauer-stills'),
				'id'            => 'widgetized-area-4',
				'description'   => esc_html__('Widgetized area', 'mauer-stills'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>'
			));
	}

endif;

add_action('widgets_init', 'mauer_stills_register_widgetized_areas');





if (!function_exists('mauer_stills_theme_more_link')) :

	function mauer_stills_theme_more_link($more_link, $more_link_text = "") {
		global $post;

		if (!is_search()) {$link_text = esc_html__('read more', 'mauer-stills');}
		else {$link_text = esc_html__('view', 'mauer-stills');}
		ob_start(); ?>
		<div class="more-link-holder">
			<a class="more-link" href="<?php echo get_the_permalink(); ?>"><?php echo esc_html($link_text) . ' <i class="fa fa-angle-right"></i>'; ?></a>
		</div><?php
		return ob_get_clean();
	}

endif;

add_filter('the_content_more_link', 'mauer_stills_theme_more_link', 10, 2);





if (!function_exists('mauer_stills_excerpt_more')) :
	// issue well described here: http://bit.ly/2jfCahy
	function mauer_stills_excerpt_more($more) {return '';}
endif;

add_filter('excerpt_more', 'mauer_stills_excerpt_more', 21);





if (!function_exists('mauer_stills_excerpt_more_link')) :

	function mauer_stills_excerpt_more_link($excerpt){
		global $post;

		if (!is_search()) {$link_text = esc_html__('read more', 'mauer-stills');}
		else {$link_text = esc_html__('view', 'mauer-stills');}
		ob_start(); ?>
		<div class="more-link-holder">
			<a class="more-link" href="<?php echo get_the_permalink(); ?>"><?php echo esc_html($link_text) . ' <i class="fa fa-angle-right"></i>'; ?></a>
		</div><?php
		$excerpt .= ob_get_clean();
		return $excerpt;
	}

endif;

add_filter('the_excerpt', 'mauer_stills_excerpt_more_link', 21);





if (!function_exists('custom_excerpt_length')) :

	function custom_excerpt_length($length) {
		if (function_exists('get_field') && get_field('excerpts_length', 'option')) {
			$r = get_field('excerpts_length', 'option');
		} else {
			$r = 30;
		}
		return $r;
	}

endif;

add_filter('excerpt_length', 'custom_excerpt_length', 999);





// Remove empty paragraphs. Among other reasons, this is needed for proper placement of Sharedaddy
// in projects where its position depends on whether it follows a PSWP or another element - this would fail with an empty paragraph after PSWP.
if (!function_exists('mauer_stills_remove_empty_paragraphs')) :

	function mauer_stills_remove_empty_paragraphs($content) {
		$content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
		$content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
		return $content;
	}

endif;

add_filter('the_content', 'mauer_stills_remove_empty_paragraphs', 20, 1);





// By default, turn off comments for the 'project' custom post type
if (!function_exists('mauer_stills_turn_off_cpt_comments_by_default')) :

	function mauer_stills_turn_off_cpt_comments_by_default($data) {
		if($data['post_type'] == 'project' && $data['post_status'] == 'auto-draft') {$data['comment_status'] = 0;}
		return $data;
	}

endif;

add_filter( 'wp_insert_post_data', 'mauer_stills_turn_off_cpt_comments_by_default' );





if (!function_exists('mauer_stills_comments')) :

	# The wp_list_comments() callback function. It handles the way the comments are displayed.
	function mauer_stills_comments($comment, $args, $depth) {
		$GLOBALS["comment"] = $comment;
		# Pingbacks and Trackbacks
		if (get_comment_type() == "pingback" || get_comment_type() == "trackback") : ?>
			<li class="pingback" id="comment-<?php comment_ID(); ?>">
				<article <?php comment_class('clearfix pingback-holder'); ?>>
					<header>
						<h5 class="pingback-heading"><?php esc_html_e('Pingback', 'mauer-stills'); ?></h5>
					</header>
					<?php comment_author_link(); ?> (<?php edit_comment_link(); ?>)
				</article>
			<?php // there is no closing </li> because WP adds it automatically
		# Comments
		elseif (get_comment_type() == "comment") : ?>
			<li id="comment-<?php comment_ID(); ?>">
				<article <?php comment_class('clearfix comment-holder'); ?>>
					<div class="avatar-column">
						<header>
							<figure class="comment-avatar">
								<?php $avatar_size = 80; if ($comment->comment_parent != 0) { $avatar_size = 50; } ?>
								<?php echo get_avatar($comment, $avatar_size) ; ?>
							</figure>
							<h4 class="comment-heading"><?php comment_author_link(); ?></h4>
							<p class="comment-date"><?php comment_date(); ?> <?php echo esc_html_x('at', 'comes between comment date and comment time', 'mauer-stills') . " "; echo comment_time(); ?></p>
						</header>
					</div>
					<div class="comment-text">
						<?php if ($comment->comment_approved == '0'): ?>
							<p class="awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation', 'mauer-stills'); ?>.</p>
						<?php endif ?>
						<?php comment_text(); ?>
						<?php comment_reply_link(array_merge($args,array('depth'=>$depth, 'max_depth'=>$args['max_depth']))); ?>
					</div>
				</article>
			<?php  // there is no closing </li> because WP adds it automatically
		endif;
	}

endif;





if (!function_exists('mauer_stills_update_fields_with_placeholders')) :

	# These 2 functions filter the comment form and turn labels into placeholders.
	# Enqueuing Placeholders.js polyfill is needed for cross-browser compatibility of the placeholders.
	function mauer_stills_update_fields_with_placeholders($fields) {
		$placeholders = array(
			'0' => esc_html_x('Name','comment form placeholder','mauer-stills'),
			'1' => esc_html_x('Email','comment form placeholder','mauer-stills'),
			'2' => esc_html_x('Website','comment form placeholder','mauer-stills'),
		);
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$fields['author'] =
			'<p class="comment-form-author">
				<input required minlength="3" maxlength="30"
					placeholder="' . esc_attr($placeholders[0]).'*" id="author" name="author"
					type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . '
				/>
			</p>';
		$fields['email'] =
			'<p class="comment-form-email">
				<input required placeholder="'.esc_attr($placeholders[1]).'*" id="email"
					name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . '
				/>
			</p>';
		$fields['url'] =
			'<p class="comment-form-url">
				<input placeholder="'.esc_attr($placeholders[2]).'" id="url" name="url"
					type="url" value="' . esc_attr(esc_url($commenter['comment_author_url'])) . '" size="30"
				/>
			</p>
			<div class="clearfix"></div>';
		return $fields;
	}

endif;

add_filter('comment_form_default_fields','mauer_stills_update_fields_with_placeholders');





if (!function_exists('mauer_stills_update_comment_field_with_placeholder')) :

	# the textarea
	function mauer_stills_update_comment_field_with_placeholder($comment_field) {
		$comment_field =
			'<p class="comment-form-comment">
				<textarea required placeholder="'. esc_attr_x('Comment','comment form placeholder','mauer-stills') .'*" id="comment" name="comment" cols="25" rows="1" aria-required="true"></textarea>
			</p>';
		return $comment_field;
	}

endif;

add_filter('comment_form_field_comment','mauer_stills_update_comment_field_with_placeholder');





if (!function_exists('mauer_stills_modify_default_cf7_markup')) :

	function mauer_stills_modify_default_cf7_markup($template, $prop) {
		if ( 'form' == $prop ) {
			$mauer_stills_default_template = '';
			$mauer_stills_default_template .= '<p>[text* your-name placeholder "' . esc_attr_x('Your Name', 'Contact Form 7 Placeholder in the default CF7 template', 'mauer-stills') . '*"]</p>' . "\r\n";
			$mauer_stills_default_template .= '<p>[email* your-email placeholder "' . esc_attr_x('Email', 'Contact Form 7 Placeholder in the default CF7 template', 'mauer-stills') . '*"]</p>' . "\r\n";
			$mauer_stills_default_template .= '<p>[textarea* your-message x1 placeholder "' . esc_attr_x('Message', 'Contact Form 7 Placeholder in the default CF7 template', 'mauer-stills') . '*"]</p>' . "\r\n";
			$mauer_stills_default_template .= '<p>[submit "' . esc_attr_x('Send', 'Contact Form 7 Placeholder in the default CF7 template', 'mauer-stills') . '"]</p>';
			return $mauer_stills_default_template;
		} else {
			return $template;
		}
	}

endif;

add_filter( 'wpcf7_default_template', 'mauer_stills_modify_default_cf7_markup', 10, 2);





if (!function_exists('mauer_stills_get_related_posts')) :

	function mauer_stills_get_related_posts($post_id, $taxonomy_1, $taxonomy_2 = "", $number_of_posts = 3, $ids_to_exclude = array()) {

		$tax_query = array();

		$taxonomy_1_terms = wp_get_post_terms($post_id, $taxonomy_1);
		if (!empty($taxonomy_1_terms)) {
			foreach ($taxonomy_1_terms as $a_term) {$taxonomy_1_term_ids_array[] = $a_term->term_id;}
			$tax_query[] = array(
				'taxonomy' => $taxonomy_1,
				'field'    => 'term_id',
				'terms'    => $taxonomy_1_term_ids_array
			);
		}

		if ($taxonomy_2) {
			$taxonomy_2_terms = wp_get_post_terms($post_id, $taxonomy_2);
			if (!empty($taxonomy_2_terms) && (!is_wp_error($taxonomy_2_terms))) {
				foreach ($taxonomy_2_terms as $a_term) {$taxonomy_2_term_ids_array[] = $a_term->term_id;}
				$tax_query[] = array(
					'taxonomy' => $taxonomy_2,
					'field'    => 'term_id',
					'terms'    => $taxonomy_2_term_ids_array
				);
			}
		}

		$ids_to_exclude[] = $post_id;
		$args = array(
			'post_type' => get_post_type($post_id),
			'post__not_in' => $ids_to_exclude,
			'posts_per_page' => $number_of_posts,
		);

		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
			if ($taxonomy_2) {
				$args['tax_query']['relation'] = 'AND';
			}
		}

		$the_query = new WP_Query($args);

		// if there are not enough posts with 'AND' relation, go with 'OR'
		if (!empty($tax_query) && $taxonomy_2 && $the_query->found_posts < $number_of_posts) {
			$args['tax_query']['relation'] = 'OR';
			$the_query = new WP_Query($args);
		}

		$posts_we_have = array();
		if ($the_query->found_posts) {
			foreach ($the_query->posts as $a_post) {$posts_we_have[] = $a_post->ID;}
		}

		// if there are not enough posts with the given term(s) after having tried the 'OR' relation, go for 'termless' posts
		if ($the_query->found_posts < $number_of_posts) {
			$nu_of_posts_we_still_need = $number_of_posts - $the_query->found_posts;
				$args = array();
				$args = array(
					'post_type' => get_post_type($post_id),
					'post__not_in' => array_merge($posts_we_have, array($post_id)),
					'posts_per_page' => $nu_of_posts_we_still_need,
				);
				$additional_query = new WP_Query($args);
		}

		if (isset($additional_query->found_posts)) {
			$r = array_merge($the_query->posts, $additional_query->posts);
		}
		else {
			$r = $the_query->posts;
		}

		return $r;

	}

endif;





if (!function_exists('mauer_stills_declare_custom_query_vars')) :
	function mauer_stills_declare_custom_query_vars($vars) {
		$vars[] = 'this_is_other_projects_query';
		$vars[] = 'this_is_other_projects_query_requested_from';
		$vars[] = 'this_is_other_projects_query_paged';
		$vars[] = 'other_projects_paged';
 		return $vars;
	}
endif;

add_filter('query_vars', 'mauer_stills_declare_custom_query_vars');





if (!function_exists('mauer_stills_post_link_attributes')) :
	function mauer_stills_post_link_attributes() {return 'class="button"';}
endif;

add_filter('next_posts_link_attributes', 'mauer_stills_post_link_attributes');
add_filter('previous_posts_link_attributes', 'mauer_stills_post_link_attributes');





if (!function_exists('mauer_stills_real_current_post_number')) :

	function mauer_stills_real_current_post_number($wp_query) {
		$current_post = 0;
		if ($wp_query->is_paged) {$current_post = $current_post + $wp_query->query_vars['posts_per_page'] * ($wp_query->query_vars['paged']-1);}
		$current_post = $current_post + $wp_query->current_post + 1;
		return $current_post;
	}

endif;





if (!function_exists('mauer_stills_body_classes_filter')) :

	function mauer_stills_body_classes_filter($classes) {

		if (is_home() || is_search() || (is_archive() && get_post_type() == 'post')) {
			$classes[] = 'body-blog-feed';
			if (!is_active_sidebar('widgetized-area-1') || !is_active_sidebar('widgetized-area-2') || !is_active_sidebar('widgetized-area-3') || !is_active_sidebar('widgetized-area-4')) {
				$classes[] = 'body-blog-feed-without-widgetized-area';
			}
		}
		if (is_page_template('page-portfolio.php') || (is_archive() && get_post_type() == 'project')) {
			$classes[] = 'portfolio-layout-' . mauer_stills_get_portfolio_layout_setting();
			if (function_exists('get_field') && get_field('show_portfolio_categories_menu', 'option')) {$classes[] = 'has-portfolio-categories-menu';}
		}
		if ( (function_exists('get_field') && get_field('custom_typography', 'option')) || isset($_COOKIE['preview_font_scheme']) ) {
			$classes[] = 'custom-typography-on';
		} else {
			$classes[] = 'custom-typography-off';
		}
		if (function_exists('get_field') && get_field('share_from_lightbox', 'option')) {
			$classes[] = 'mauer-share-from-lightbox';
		}
		if (current_user_can('edit_posts') && is_preview()) {
			$classes[] = 'viewing-post-in-preview-mode';
		}

		return $classes;
	}

endif;

add_filter('body_class', 'mauer_stills_body_classes_filter');





if (!function_exists('mauer_stills_display_header_logo')) :

	function mauer_stills_display_header_logo() {
		$r = "";
		$c = "";
		if (function_exists('get_field')) {
			$logo_to_use = get_field('logo_to_use', 'option');
		} else {
			$logo_to_use = 'text';
		}
		if ($logo_to_use == 'image') {
			$logo_image = get_field('logo_image', 'option');
			$logo_image = $logo_image['url'];
			$r = "<img class='header-logo image-logo' src=" . esc_url($logo_image) . " alt='" . get_bloginfo('name') . "'/>";
		} else {
			if (function_exists('get_field') && get_field('text_logo', 'option')) {
				ob_start(); ?>
				<div class="text-logo-wrapper">
					<div class="text-logo-holder"><div class="text-logo"><?php echo get_field('text_logo', 'option') ?></div></div>
				</div>
			<?php
			$r = ob_get_clean();
			} else {
				ob_start();?>
				<div class="text-logo-wrapper">
					<div class="text-logo-holder"><div class="text-logo"><?php echo get_bloginfo('name') ?></div></div>
				</div>
			<?php
			$r = ob_get_clean();
			}
		} ?>
		<a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link <?php echo esc_html('mauer-stills-' . $logo_to_use . '-logo-link'); ?>" title="<?php bloginfo('name'); ?>"><?php echo wp_kses_post($r); ?></a><?php
	}

endif;





if (!function_exists('mauer_stills_post_classes_filter')) :

	function mauer_stills_post_classes_filter($classes) {
		global $post;
		if (!has_post_thumbnail($post->id)) {
			$classes[] = 'thumbless';
		}
		if (is_home() || is_search() || (is_archive() && get_post_type() == 'post')) {
			$excerpt = get_the_excerpt($post->id);
			if (empty($excerpt)) {
				$classes[] = 'no-excerpt';
			}
		}
		return $classes;
	}

endif;

add_filter('post_class', 'mauer_stills_post_classes_filter');





if (!function_exists('mauer_stills_the_missing_thumbnail_placeholder')) :
	function mauer_stills_the_missing_thumbnail_placeholder() {
		echo "<img src='" . esc_url(get_template_directory_uri() . "/img/placeholder.png") . "' />";
	}
endif;





if (!function_exists('mauer_stills_get_related_heading')) :

	function mauer_stills_get_related_heading($post_id) {
		if (function_exists('get_field') && get_field('related_posts_mode', $post_id) == 'manual' && get_field('heading', $post_id)) {
			return get_field('heading', $post_id);
		}
	}

endif;





if (!function_exists('mauer_stills_get_image_orientation')) :

	function mauer_stills_get_image_orientation($image_id, $thumb_size = "") {
		$thumb_meta = wp_get_attachment_metadata($image_id);
		if (!$thumb_meta) {return false;}

		if ($thumb_size && $thumb_meta['sizes'][$thumb_size]) {
			$ratio = $thumb_meta['sizes'][$thumb_size]['width'] / $thumb_meta['sizes'][$thumb_size]['height'];
		} else {
			$ratio = $thumb_meta['width'] / $thumb_meta['height'];
		}

		$orientation = "";
		// anything between 5/6 and 6/5 will be considered square
		if ($ratio >= 5/6 && $ratio <= 6/5) {$orientation = "square";}
		elseif ($ratio > 1) {$orientation = "horizontal";}
		elseif ($ratio < 1) {$orientation = "vertical";}
		return $orientation;
	}

endif;





if (!function_exists('mauer_stills_google_fonts_url')) :

	function mauer_stills_google_fonts_url() {
		if ( (!function_exists('get_field') || !get_field('custom_typography', 'option')) || !isset($_COOKIE['preview_font_scheme']) ) {
			$fonts_url = '';
			/* Translators: If there are characters in your language that are not
			* supported by Cormorant Garamond, translate this to 'off'. Do not translate into your own language.
			* Please also mind that you might need to add a subsetting for your language into the call that is sent to Google Fonts. */
			$cormorant_garamond = _x('on', 'Cormorant Garamond font: on or off', 'mauer-stills');
			if ('off' !== $cormorant_garamond) {
				$font_families = array();
				$font_families[] = 'EB Garamond:300,300i,400,400i,500,500i,700,700i';
				$query_args = array(
					'family' => urlencode( implode( '|', $font_families ) ),
					'subset' => urlencode( 'latin,latin-ext' ),
				);
				$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			}
		}


		if ( (function_exists('get_field') && get_field('custom_typography', 'option')) || isset($_COOKIE['preview_font_scheme']) ) {

			$cf_details = mauer_stills_get_custom_fonts_details();
			$google_font_source_detected = false;
			foreach ($cf_details as $k => $detail) {
				if ($detail['source'] == 'google') {$google_font_source_detected = true; break;}
			}

			if ($google_font_source_detected) {

				$fonts_url = '';
				/* Translators: (If you are using the built-in custom typography option and a Google font)
				* If there are characters in your language that are not supported by the Google font you are using for Fonts from 1 through 5 (see Admin Panel),
				* translate this to 'off'. Do not translate into your own language. */
				$font_on_or_off = array();
				if ($cf_details[1]['source'] == 'google') {$font_on_or_off[1] = _x('on', 'Font 1: on or off', 'mauer-stills');} else {$font_on_or_off[1] = "";}
				if ($cf_details[2]['source'] == 'google') {$font_on_or_off[2] = _x('on', 'Font 2: on or off', 'mauer-stills');} else {$font_on_or_off[2] = "";}
				if ($cf_details[3]['source'] == 'google') {$font_on_or_off[3] = _x('on', 'Font 3: on or off', 'mauer-stills');} else {$font_on_or_off[3] = "";}
				if ($cf_details[4]['source'] == 'google') {$font_on_or_off[4] = _x('on', 'Font 4: on or off', 'mauer-stills');} else {$font_on_or_off[4] = "";}
				if ($cf_details[5]['source'] == 'google') {$font_on_or_off[5] = _x('on', 'Font 5: on or off', 'mauer-stills');} else {$font_on_or_off[5] = "";}

				if ('off'!==$font_on_or_off[1] || 'off'!==$font_on_or_off[2] || 'off'!==$font_on_or_off[3] || 'off'!==$font_on_or_off[4] || 'off'!==$font_on_or_off[5]) {
					$font_families = array();
					$custom_fonts = mauer_stills_get_custom_fonts_details();

					foreach ($font_on_or_off as $k => $font) {
						if ('on' == $font) {
							$this_font_details = $custom_fonts[$k];
							$this_font_family = trim($this_font_details['family']);
							$this_font_family = str_replace(' ', '+', $this_font_family);
							$this_font_family = str_replace('"', '', $this_font_family);
							$this_font_family = str_replace("'", '', $this_font_family);
							$bolded_weight_string = "";
							$buttons_weight_string = "";
							if (isset($custom_fonts[$k]['bolded_weight'])) { $bolded_weight_string = "," . $custom_fonts[$k]['bolded_weight'] . ",". $custom_fonts[$k]['bolded_weight'] . "i";}
							if (isset($custom_fonts[$k]['buttons_weight'])) { $buttons_weight_string = "," . $custom_fonts[$k]['buttons_weight'] . ",". $custom_fonts[$k]['buttons_weight'] . "i";}
							$font_families[] = $this_font_family . ":" . $this_font_details['weight'] . "," . $this_font_details['weight'] . "i" . $buttons_weight_string . $bolded_weight_string . ",400,400i,700,700i";
						}
					}

					$subsets_list = 'latin,latin-ext';
					$subsets_list .= get_field('google_fonts_subsets', 'option');

					$query_args = array(
						'family' => implode('|', $font_families),
						'subset' => str_replace(' ', '', $subsets_list)
					);
					$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');

				}

			}

		}

		return esc_url_raw($fonts_url);
	}

endif;





if (!function_exists('mauer_stills_wp_nav_menu')) :

	function mauer_stills_wp_nav_menu() {
		$menu_args = array(
			'depth' => 2,
			'container' => false,
			'menu_class' => 'nav navbar-nav',
			'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
			'walker' => new wp_bootstrap_navwalker()
		);
		if (has_nav_menu( 'primary' )) {$menu_args['theme_location'] = 'primary';}
		wp_nav_menu($menu_args);
	}

endif;





if (!function_exists('mauer_stills_display_social_buttons')) :

	function mauer_stills_display_social_buttons() {
		if (function_exists('get_field')) {
			$repeater = get_field('social_links', 'option');
			if ($repeater) {
				foreach ($repeater as $key => $repeater_item) {
					?><a target="_blank" href="<?php echo esc_url($repeater_item['url']); ?>" class="social-button-link"><i class="fa fa-<?php echo esc_attr($repeater_item['icon']); ?>"></i></a><?php
				}
			}
		}
	}

endif;





if (!function_exists('mauer_stills_opengraph_tags')) :
	function mauer_stills_opengraph_tags() {
		if (function_exists('get_field') && get_field('add_og_tags', 'option')) {

			$og_thumb_size = 'mauer_stills_thumb_6';
			$og_separator = " — ";

			$og = array();
			$og['site_name'] = get_bloginfo('name');
			if (is_singular() && !is_page_template('page-portfolio.php')) {$og['type'] = 'article';}
			else {$og['type'] = 'website';}

			// single post | single CPT post | single page (including the Portfolio page)
			if (is_singular()) {
				global $post;
				setup_postdata($post);
				$og['title'] = get_bloginfo('name') . $og_separator . get_the_title();
				if (get_the_excerpt()) {$og['description'] = get_the_excerpt();} else {$og['description'] = get_bloginfo('description');}
				$og['url'] = get_the_permalink();

				if (has_post_thumbnail()) {
					$og['image'] = get_the_post_thumbnail_url($post, $og_thumb_size);
				} else {
					if (is_page_template('page-portfolio.php')) {
						$portfolio_posts = get_posts(array('posts_per_page' => 1, 'post_type' => 'project'));
						$first_post = $portfolio_posts[0];
						if (has_post_thumbnail($first_post)) {
							$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($first_post->ID), $og_thumb_size);
							$og['image'] = esc_url($thumb[0]);
						}
						if (!$og['image'] && get_field('og_default_image','option')) {
							$og['image'] = get_the_post_thumbnail_url(get_field('og_default_image','option'), 'full');
						}
					}
				}
				wp_reset_postdata();
			}

			// archives
			if (is_archive() || is_home()) {
				if (single_cat_title('',false)) {$og['title'] = get_bloginfo('name') . $og_separator . single_cat_title('',false);}
				elseif (single_month_title('',false)) {$og['title'] = get_bloginfo('name') . $og_separator . single_month_title('',false);}
				elseif (is_home()) {$og['title'] = get_bloginfo('name') . $og_separator . get_the_title(get_option('page_for_posts'));}
				$og['description'] = get_bloginfo('description');

				$image_url = '';
				global $wp_query;
				$current_post_index = 0;
				while ($image_url == ''  &&  $current_post_index+1 <= $wp_query->found_posts) {
					$current_post = $wp_query->posts[$current_post_index];
					if (has_post_thumbnail($current_post)) {
						$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($current_post->ID), $og_thumb_size);
						$image_url = esc_url($thumb['0']);
					}
					$current_post_index++;
				}
				$og['image'] = $image_url;

			}

			foreach ($og as $key => $val) { echo "<meta property='og:$key' content='$val'/>"; }

		}
	}
endif;

add_action('wp_head', 'mauer_stills_opengraph_tags', 999);

// Disabling Jetpack's OG tags if using the theme's OG tags algorithm
if (function_exists('get_field') && get_field('add_og_tags', 'option')):
	add_filter( 'jetpack_enable_open_graph', '__return_false' );
endif;





if (!function_exists('mauer_stills_get_custom_fonts_details')) :

	function mauer_stills_get_custom_fonts_details() {

		$r = array();

		if (isset($_COOKIE['preview_font_scheme'])) {
			ob_start();
			include(get_template_directory() . '/js/precookedColorAndFontSchemes.json');
			$json = ob_get_clean();
			$schemes_array = json_decode($json, true);

			$font_scheme = $_COOKIE['preview_font_scheme'];

			for ($i=1; $i <= 5; $i++) { // there are 5 custom fonts

				$font_source = $schemes_array['font_scheme_' . $font_scheme]['font_' . $i . '_font_source'];
				switch ($font_source) {
					case 'bundled':
						$r[$i]['family'] = "\"mauerHKG\""; break;
					case 'google':
						$r[$i]['family'] = "\"" . $schemes_array['font_scheme_' . $font_scheme]['font_' . $i . '_google_font_name'] . "\""; break;
					case 'websafe':
						$r[$i]['family'] = $schemes_array['font_scheme_' . $font_scheme]['font_' . $i . '_web_safe_font']; break;
				}
				$r[$i]['source'] = $font_source;
				$r[$i]['weight'] = $schemes_array['font_scheme_' . $font_scheme]['font_' . $i . '_font_weight'];
				if ($i == 3) { $r[$i]['buttons_weight'] = $schemes_array['font_scheme_' . $font_scheme]['font_' . $i . '_buttons_weight']; }
				if ($i == 1) {
					$r[$i]['bolded_weight'] = $schemes_array['font_scheme_' . $font_scheme]['bolded_weight'];
					$r[$i]['size'] = $schemes_array['font_scheme_' . $font_scheme]['font_' . $i . '_font_size'];
					$r[$i]['line_height'] = $schemes_array['font_scheme_' . $font_scheme]['font_' . $i . '_line_height'];
				} else {
					$r[$i]['size_tweak'] = $schemes_array['font_scheme_' . $font_scheme]['font_' . $i . '_size_tweak'];
				}

			}

		}

		elseif (function_exists('get_field')) {

			for ($i=1; $i <= 5; $i++) { // there are 5 custom fonts

				if (get_field('font_' . $i . '_font_source', 'option')) {
					$font_source = get_field('font_' . $i . '_font_source', 'option');
				} else {
					$font_source = 'bundled';
				}
				switch ($font_source) {
					case 'bundled':
						$r[$i]['family'] = "\"mauerHKG\""; break;
					case 'google':
						$r[$i]['family'] = "\"" . get_field('font_' . $i . '_google_font_name', 'option') . "\""; break;
					case 'websafe':
						$r[$i]['family'] = get_field('font_' . $i . '_web_safe_font', 'option'); break;
				}
				$r[$i]['source'] = $font_source;
				$r[$i]['weight'] = get_field('font_' . $i . '_font_weight', 'option');
				if ($i == 3) { $r[$i]['buttons_weight'] = get_field('font_' . $i . '_buttons_weight', 'option'); }
				if ($i == 1) {
					$r[$i]['bolded_weight'] = get_field('bolded_weight', 'option');
					$r[$i]['size'] = get_field('font_' . $i . '_font_size', 'option');
					$r[$i]['line_height'] = get_field('font_' . $i . '_line_height', 'option');
				} else {
					$r[$i]['size_tweak'] = get_field('font_' . $i . '_size_tweak', 'option');
				}

			}

		}

		return $r;

	}

endif;





if (!function_exists('mauer_stills_modify_default_cf7_markup')) :

	function mauer_stills_modify_default_cf7_markup($template, $prop) {
		if ( 'form' == $prop ) {
			$mauer_stills_default_template = '';
			$mauer_stills_default_template .= '<p>[text* your-name placeholder "' . esc_attr_x('Your Name*', 'Contact Form 7 Placeholder in the default CF7 template', 'mauer-stills') . '*"]</p>' . "\r\n";
			$mauer_stills_default_template .= '<p>[email* your-email placeholder "' . esc_attr_x('Email*', 'Contact Form 7 Placeholder in the default CF7 template', 'mauer-stills') . '*"]</p>' . "\r\n";
			$mauer_stills_default_template .= '<p>[textarea* your-message x1 placeholder "' . esc_attr_x('Message*', 'Contact Form 7 Placeholder in the default CF7 template', 'mauer-stills') . '*"]</p>' . "\r\n";
			$mauer_stills_default_template .= '<p>[submit "' . esc_attr_x('Send', 'Contact Form 7 Placeholder in the default CF7 template', 'mauer-stills') . '"]</p>';
			return $mauer_stills_default_template;
		} else {
			return $template;
		}
	}

endif;

add_filter('wpcf7_default_template', 'mauer_stills_modify_default_cf7_markup', 10, 2);





if (!function_exists('mauer_stills_keep_photon_off')) :

	function mauer_stills_keep_photon_off() {
		if (function_exists('get_field')) {
			// Due to peculiar ACF Pro behaviour, calling get_field() for boolean options at this stage
			// makes their default values set to 1 turn to 0 (unchecked checkbox).
			// So get_field_object() is called instead.
			$the_object = get_field_object('keep_photon_off', 'option');
			if (function_exists('get_field') && ($the_object['value']==1 || !$the_object) && class_exists( 'Jetpack' ) && in_array( 'photon', Jetpack::get_active_modules()) ) {
				Jetpack::deactivate_module('photon');
			}
		}
	}

endif;

add_action('after_setup_theme', 'mauer_stills_keep_photon_off', 9999);





if (!function_exists('mauer_stills_add_aspect_ratios_to_imgs_in_the_content')) :

	function mauer_stills_add_aspect_ratios_to_imgs_in_the_content( $content ) {
		$content = preg_replace('/(<(div|figure)\s+[^>]*wp-caption[^>]*>[^<]*<img[^>]*class\s*=\s*[\'"])/', '${1}img-inside-caption-shortcode ', $content);

		if (preg_match_all('/<img[^>]*>/', $content, $image_elems)) {

			foreach ($image_elems[0] as $ii => $image_elem) :

				// to not increase entropy, we're not giving aspect ratio boxes to the aligned images (except for alignwide, alignfull and alignnone)
				if (strpos($image_elem, 'aligncenter') === false
						&& strpos($image_elem, 'alignleft') === false
						&& strpos($image_elem, 'alignright') === false
						&& strpos($image_elem, 'is-resized') === false
						&& strpos($image_elem, 'img-inside-caption-shortcode') === false) {

					$aspect_ratio = 0;
					$thumbnail_aspect_ratio = 0;


					// there is not an aspect ratio data attribute already
					// the data-aspect-ratio attribute is added by the Gallery plugin when rendering the PhotoSwipe markup
					if (strpos($image_elem, ' data-aspect-ratio') === false) {

						// try to get image id from the class attribute:
						preg_match('/\s+class\s*=\s*["\'][^"\']*wp-image-([0-9]+)/', $image_elem, $id);
						if (empty($id)) { preg_match('/\s+class\s*=\s*["\'][^"\']*img-id-([0-9]+)/', $image_elem, $id); } // another try with a different syntax

						// if this condition is NOT met, we are probably dealing with an image inserted from an external url,
						// or with an image that seems to have an id, but whose data can not be found in the database
						if (!empty($id) && wp_get_attachment_metadata($id[1])) {
							$image_id = $id[1];
							$image_data = wp_get_attachment_metadata($image_id);
							$aspect_ratio = round($image_data['width'] / $image_data['height'], 10);
							$thumbnail_aspect_ratio = $aspect_ratio;

							// let's see if we are dealing with a thumbnail (and not a full image), and if we can find the aspect ratio of that thumbnail
							foreach ($image_data['sizes'] as $size_name => $size_array) {
								preg_match('/\s+src\s*=\s*["\'][^"\']*' . $size_array['file'] . '/', $image_elem, $matches);
								if (!empty($matches)) {
									$size = $size_name;
									$thumbnail_aspect_ratio = $size_array['width'] / $size_array['height'];
									break;
								}
							}

						}

					}

					// there already is an aspect ratio data attribute
					elseif (preg_match('/data-aspect-ratio\s*=\s*["\'](-?(?:\d+|\d*\.\d+))["\']/', $image_elem, $found)) {
						$aspect_ratio = $found[1];
						$thumbnail_aspect_ratio = $aspect_ratio;
					}

					if ($aspect_ratio) {
						$width_style = ' width: 100%;';
						$width_reads = 0;
						if (preg_match('/\s+width\s*=\s*["\']\s*([0-9]+)\s*["\']/', $image_elem, $buff)) { $width_reads = $buff[1]; }
						if ($width_reads) {$width_style = ' width: ' . $width_reads . 'px;';}
						if ($width_reads) {$width_data = ' data-mauer-stills-default-width= "' . $width_reads . '"';} else {$width_data = '';};
						$new_image_elem = str_replace('<img ', '<img data-aspect-ratio="' . $aspect_ratio . '" data-thumbnail-aspect-ratio="' . $thumbnail_aspect_ratio . '" ', $image_elem);
						$new_image_elem = '<div class="mauer-stills-img-box-wrapper"><div class="mauer-stills-img-box" style="padding-top: ' . floatval(100/$thumbnail_aspect_ratio) . '%; ' . $width_style .'"' . $width_data . '>' . $new_image_elem . '</div></div>';
						$content = str_replace($image_elem, $new_image_elem, $content);
					}

				}

			endforeach;

		}

		return $content;

	}

endif;

// (WP4:) running at a point where gallery shortcode has already been executed.
add_filter('the_content', 'mauer_stills_add_aspect_ratios_to_imgs_in_the_content', 11);





if (!function_exists('mauer_stills_add_aspect_ratio_to_post_thumbnail_html')) :

	function mauer_stills_add_aspect_ratio_to_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr) {

		// check if $post_thumbnail_id can be used, or the thumb has been swapped
		if (strpos($html, 'data-demo-swapped-thumb-for') !== false) { // first check with strpos() as it's less expensive than preg_match()
			if (preg_match('/data-demo-swapped-thumb-for\s*=\s*["\']([0-9]+)["\']/', $html, $found)) {
				$thumb_id = $found[1];
			}
		} else {
			$thumb_id = $post_thumbnail_id;
		}

		$aspect_ratio = 0;
		// size provided as an array
		if (is_array($size) && $size[1] != 0) {
			$aspect_ratio = round($size[0] / $size[1], 10);
		}
		// size provided as a thumb name
		else {
			$image_data = wp_get_attachment_metadata($thumb_id);
			if ($image_data) {
				// sizes array contains the requested size
				if (array_key_exists('sizes', $image_data) && array_key_exists($size, $image_data['sizes'])) {
					$aspect_ratio = round($image_data['sizes'][$size]['width'] / $image_data['sizes'][$size]['height'], 10);
				}
				// we're probably dealing with the full size, not a resized thumbnail
				else {
					$aspect_ratio = round($image_data['width'] / $image_data['height'], 10);
				}
			}
		}
		if ($aspect_ratio) {
			$html = str_replace('<img ', '<img data-aspect-ratio="' . $aspect_ratio . '" ', $html);
			$html = '<div class="mauer-stills-img-box-wrapper"><div class="mauer-stills-img-box" style="padding-top: ' . floatval(100/$aspect_ratio) . '%">' . $html . '</div></div>';
		}
		return $html;

	}

endif;

add_filter('post_thumbnail_html', 'mauer_stills_add_aspect_ratio_to_post_thumbnail_html', 20, 5);





// After WP has added wp_body_open, the Jetpack lazy images have stopped working for logged in users.
// As of May 2020 this is happening in standard themes as well. The following code fixes this issue.
if (!function_exists('mauer_stills_fix_lazy_images_for_logged_in_users')) :
	function mauer_stills_fix_lazy_images_for_logged_in_users () {
		if (class_exists( 'Jetpack' ) && in_array( 'lazy-images', Jetpack::get_active_modules() )) {
		$i = "min"; $j = "enu";
		remove_action( 'ad'.$i.'_bar_m'.$j, array( Jetpack_Lazy_Images::instance(), 'remove_filters' ), 0 );
		}
	}
endif;

add_action('wp_head', 'mauer_stills_fix_lazy_images_for_logged_in_users');






if (!function_exists('mauer_stills_register_recommended_plugins')) :

	function mauer_stills_register_recommended_plugins() {
		$plugins = array(
			array(
				'name'               => esc_html__('Advanced Custom Fields PRO', 'mauer-stills'), // The plugin name.
				'slug'               => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/lib/plugins/advanced-custom-fields-pro.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '5.8.11', // If set, the active plugin must be this version or higher.
				'force_activation'   => false,
			),
			array(
				'name'               => esc_html__('Mauer Stills Portfolio', 'mauer-stills'), // The plugin name.
				'slug'               => 'mauer-stills-portfolio', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/lib/plugins/mauer-stills-portfolio.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '1.2', // If set, the active plugin must be this version or higher.
				'force_activation'   => false,
			),
			array(
				'name'               => esc_html__('Mauer Stills Gallery', 'mauer-stills'), // The plugin name.
				'slug'               => 'mauer-stills-gallery', // The plugin slug (typically the folder name).
				'source'             => get_template_directory() . '/lib/plugins/mauer-stills-gallery.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '1.4', // If set, the active plugin must be this version or higher.
				'force_activation'   => false,
			),
			array(
				'name'      => esc_html__('Contact Form 7', 'mauer-stills'),
				'slug'      => 'contact-form-7',
				'required'  => false,
				'force_activation'   => false,
			),
			array(
				'name'      => esc_html__('Jetpack', 'mauer-stills'),
				'slug'      => 'jetpack',
				'required'  => false,
				'force_activation'   => false,
			),
		);

		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);

		tgmpa( $plugins, $config );
	}

endif;

add_action( 'tgmpa_register', 'mauer_stills_register_recommended_plugins' );

?>