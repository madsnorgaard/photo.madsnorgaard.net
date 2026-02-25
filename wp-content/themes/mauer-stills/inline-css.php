<?php

if (!function_exists('mauer_stills_add_inline_css_for_typography')) :

	function mauer_stills_add_inline_css_for_typography() {

		$css_to_add = "";

		/* (!) IMPORTANT NOTICE
		As the vast majority of font sizes can be changed in the custom typography settings, they are
		introduced as inline styles here and nowhere else (e.g. they are not in style.css).
		This is so to follow the spirit of DRY programming (not to double the code). */

		// (!) These styles are added only with custom typography enabled or scheme preview enabled (for demo purposes, via cookies).
		if ( (function_exists('get_field') && get_field('custom_typography', 'option')) || isset($_COOKIE['preview_font_scheme']) ) {
			$custom_fonts = mauer_stills_get_custom_fonts_details();

			ob_start();?>


				/* Custom font families and weights */

				/* Bolded text weight */
				b, strong, blockquote, .wp-block-quote:not(.is-large):not(.is-style-large), blockquote cite, .wp-block-quote:not(.is-large):not(.is-style-large) cite {font-weight: <?php echo wp_kses_post($custom_fonts[1]['bolded_weight']); ?>;}

				/* Font 1: Body font */
				body, .entry-content p em, .entry-content p i, .section-main-content div.sharedaddy h3.sd-title {
					font-family: <?php echo wp_kses_post($custom_fonts[1]['family']); ?>;
					font-weight: <?php echo wp_kses_post($custom_fonts[1]['weight']); ?>;
					line-height: <?php echo wp_kses_post($custom_fonts[1]['line_height']); ?>;
				}

				/* Font 2: Headings font (h1-h6) */
				h1, h2, h3, h4, h5, h6 {
					font-family: <?php echo wp_kses_post($custom_fonts[2]['family']); ?>;
					font-weight: <?php echo wp_kses_post($custom_fonts[2]['weight']); ?>;
				}

				/* Font 3: Navbar, Footer, titles in Portfolio, buttons, Portfolio categories menu, Blog meta */
				.mauer-navbar, .mauer-navbar .text-logo, .dropdown-menu>li>a, #footer, .portfolio-project-tile .entry-title, .portfolio-categories, .entry-meta {
					font-family: <?php echo wp_kses_post($custom_fonts[3]['family']); ?>;
					font-weight: <?php echo wp_kses_post($custom_fonts[3]['weight']); ?>;
				}
				input[type="submit"], button, .button, .wp-block-button__link {
					font-family: <?php echo wp_kses_post($custom_fonts[3]['family']); ?>;
					font-weight: <?php echo wp_kses_post($custom_fonts[3]['buttons_weight']); ?>;
				}
				/* For visual consistency, font-weight of footer text is same as body (Font 1). */
				#footer {
					font-weight: <?php echo wp_kses_post($custom_fonts[1]['weight']); ?>;
				}

				/* Font 4: Full entries titles, blog posts titles in feeds */
				.entry-full .entry-title, .mauer-blog-feed .entry-title, .blog-post-in-more .entry-title {
					font-family: <?php echo wp_kses_post($custom_fonts[4]['family']); ?>;
					font-weight: <?php echo wp_kses_post($custom_fonts[4]['weight']); ?>;
				}

				/* Font 5: Portfolio welcome phrase font */
				.portfolio-welcome-phrase {
					font-family: <?php echo wp_kses_post($custom_fonts[5]['family']); ?>;
					font-weight: <?php echo wp_kses_post($custom_fonts[5]['weight']); ?>;
				}

			<?php
			$css_to_add .= ob_get_clean();
		}


		// (!) These styles are added regardless of whether custom typography is enabled or disabled. They provide font-size properties.

		$original_font_1_size = 17;

		if ( (function_exists('get_field') && get_field('custom_typography', 'option')) || isset($_COOKIE['preview_font_scheme']) ) {
			$custom_fonts = mauer_stills_get_custom_fonts_details();
			// To proportionally scale elements using Font 1 we multiply their original font sizes
			// by the ratio: font size set in custom typography options : original font size of body element ($original_font_1_size).
			$font_1_coeff = $custom_fonts[1]['size'] / $original_font_1_size;
			// For the other font sizes the ratio by which they should be adjusted is provided explicitly - with the 'Tweak size' property in custom typography options.
			$font_2_coeff = $custom_fonts[2]['size_tweak'] / 100;
			$font_3_coeff = $custom_fonts[3]['size_tweak'] / 100;
			$font_4_coeff = $custom_fonts[4]['size_tweak'] / 100;
			$font_5_coeff = $custom_fonts[5]['size_tweak'] / 100;
		} else {
			// If custom typography is not enabled (and we're not in preview mode), all the coeffs are 1 (original font sizes).
			$custom_fonts = array();
			$custom_fonts[1]['size'] = $original_font_1_size;
			$font_1_coeff = 1;
			$font_2_coeff = 1;
			$font_3_coeff = 1;
			$font_4_coeff = 1;
			$font_5_coeff = 1;
		}

		ob_start();?>


			/* Font sizes */
			/* the first font-size declaration in each of the following rules is a fallback for browsers with no CSS calc() support. */

			/* Font 1 - font sizes */
			body {font-size: <?php echo wp_kses_post($custom_fonts[1]['size']) ?>px} /* the only font-size property that is explicitly provided when using custom typography */
			blockquote:not(.is-large):not(.is-style-large), .wp-block-quote:not(.is-large):not(.is-style-large) {font-size: 23px; font-size: calc(23px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.wp-block-quote.is-large p, .wp-block-quote.is-style-large p {font-size: 29px; font-size: calc(29px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			blockquote cite, .wp-block-quote:not(.is-large):not(.is-style-large) cite, .wp-block-pullquote cite {font-size: 12px; font-size: calc(12px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.wp-block-quote.is-large cite, .wp-block-quote.is-style-large cite {font-size: 14px; font-size: calc(14px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			@media (max-width: 767px) {blockquote, .wp-block-quote:not(.is-large):not(.is-style-large) {font-size: 21px; font-size: calc(21px * <?php echo wp_kses_post($font_1_coeff) ?>);}}
			.section-main-content select, .section-main-content textarea, .section-main-content input[type="text"],
			.section-main-content input[type="password"], .section-main-content input[type="date"], .section-main-content input[type="month"],
			.section-main-content input[type="time"], .section-main-content input[type="week"], .section-main-content input[type="number"],
			.section-main-content input[type="email"], .section-main-content input[type="url"], .section-main-content input[type="search"],
			.section-main-content input[type="tel"], .section-main-content input[type="color"], .section-main-content .form-control {
				font-size: 16px!important; font-size: calc(16px * <?php echo wp_kses_post($font_1_coeff) ?>)!important;
			}
			span.wpcf7-not-valid-tip, div.wpcf7-validation-errors, div.wpcf7-mail-sent-ok {font-size: 14px; font-size: calc(14px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.entry-excerpt {font-size: 15px; font-size: calc(15px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.search-popup input.search-input {font-size: 32px; font-size: calc(32px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.search-popup .search-input-p:before {font-size: 22px; font-size: calc(22px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			@media (max-width: 767px) {
				.search-popup input.search-input {font-size: 24px; font-size: calc(24px * <?php echo wp_kses_post($font_1_coeff) ?>);}
				.search-popup .search-input-p:before {font-size: 15px; font-size: calc(15px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			}
			.mauer-stills-gallery-pswp figcaption, .wp-caption-text, .entry-thumb-caption, .wp-block-image figcaption, .wp-block-embed figcaption {font-size: 13px; font-size: calc(13px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.pswp__caption__center {font-size: 14px; font-size: calc(14px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.comment-reply-title small {font-size: 12px; font-size: calc(12px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.section-main-content .comment-respond .comment-notes, .section-main-content .comment-notes,
			.section-main-content .logged-in-as, .section-main-content .subscribe-label, .section-main-content .comment-date {
				font-size: 14px; font-size: calc(14px * <?php echo wp_kses_post($font_1_coeff) ?>);
			}
			li.pingback {font-size: 16px; font-size: calc(16px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.comment-text {font-size: 15px; font-size: calc(15px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			#comment-nav-below .nav-previous, #comment-nav-below .nav-next {font-size: 16px; font-size: calc(16px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.form-allowed-tags, .form-allowed-tags code {font-size: 15px; font-size: calc(15px * <?php echo wp_kses_post($font_1_coeff) ?>);}
			.section-main-content div.sharedaddy h3.sd-title {font-size: 17px; font-size: calc(17px * <?php echo wp_kses_post($font_1_coeff) ?>);}


			/* Font 2 - font sizes */
			h1 {font-size: 25px; font-size: calc(33px * <?php echo wp_kses_post($font_2_coeff) ?>);}
			h2 {font-size: 23px; font-size: calc(28px * <?php echo wp_kses_post($font_2_coeff) ?>);}
			h3 {font-size: 20px; font-size: calc(23px * <?php echo wp_kses_post($font_2_coeff) ?>);}
			h4 {font-size: 17px; font-size: calc(19px * <?php echo wp_kses_post($font_2_coeff) ?>);}
			h5 {font-size: 15px; font-size: calc(14px * <?php echo wp_kses_post($font_2_coeff) ?>);}
			h6 {font-size: 13px; font-size: calc(13px * <?php echo wp_kses_post($font_2_coeff) ?>);}

			.more-posts-heading {font-size: 17px; font-size: calc(17px * <?php echo wp_kses_post($font_2_coeff) ?>);}
			.widget h4 {font-size: 18px; font-size: calc(18px * <?php echo wp_kses_post($font_2_coeff) ?>);}
			.comment-reply-title, .comments-title {font-size: 17px; font-size: calc(17px * <?php echo wp_kses_post($font_2_coeff) ?>);}
			.widget>h4:first-child {font-size: 18px; font-size: calc(18px * <?php echo wp_kses_post($font_2_coeff) ?>);}


			/* Font 3 - font sizes */
			input[type="submit"], .section-main-content input[type="submit"], button, .button, .wp-block-button__link {
				font-size: 13px; font-size: calc(13px * <?php echo wp_kses_post($font_3_coeff) ?>);
			}
			input[type="submit"].button-smaller, .section-main-content input[type="submit"].button-smaller,
			button.button-smaller, .comment-respond input[type="submit"] {
				font-size: 12px; font-size: calc(12px * <?php echo wp_kses_post($font_3_coeff) ?>);
			}
			.widget input[type="submit"], .widget button {font-size: 11px; font-size: calc(11px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.mauer-navbar .text-logo {font-size: 20px; font-size: calc(20px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.portfolio-categories {font-size: 11px; font-size: calc(11px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.portfolio-project-tile .entry-title {font-size: 16px; font-size: calc(16px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.posts-grid.posts-flow-2-cols .portfolio-project-tile .entry-title {font-size: 17px; font-size: calc(17px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			@media (max-width: 999px) {.posts-grid.posts-flow-2-cols .portfolio-project-tile .entry-title {font-size: 16px; font-size: calc(16px * <?php echo wp_kses_post($font_3_coeff) ?>);}}
			.mauer-more-projects-wrapper .portfolio-project-tile .entry-title {font-size: 15px; font-size: calc(15px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.masonry-grid-4cols .portfolio-project-tile .entry-title {font-size: 15px; font-size: calc(15px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.entry-meta {font-size: 11px; font-size: calc(11px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.mauer-blog-feed .entry-meta {font-size: 10px; font-size: calc(10px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.blog-post-in-more .entry-meta {font-size: 10px; font-size: calc(10px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.sd-social-icon .sd-button span.share-count {font-size: 9px!important; font-size: calc(9px * <?php echo wp_kses_post($font_3_coeff) ?>)!important;}
			.pswp__counter {font-size: 11px; font-size: calc(11px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.mauer-navbar {font-size: 18px; font-size: calc(18px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.footer-copyright {font-size: 14px; font-size: calc(14px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.widget {font-size: 15px; font-size: calc(15px * <?php echo wp_kses_post($font_3_coeff) ?>);}
			.widgetized-area select, .widgetized-area textarea, .widgetized-area input[type="text"], .widgetized-area input[type="password"],
			.widgetized-area input[type="date"], .widgetized-area input[type="month"], .widgetized-area input[type="time"], .widgetized-area input[type="week"],
			.widgetized-area input[type="number"], .widgetized-area input[type="email"], .widgetized-area input[type="url"], .widgetized-area input[type="search"],
			.widgetized-area input[type="tel"], .widgetized-area input[type="color"], .widgetized-area .form-control {
				font-size: 15px!important; font-size: calc(15px * <?php echo wp_kses_post($font_3_coeff) ?>)!important;
			}
			.tagcloud a {font-size: 13px!important; font-size: calc(13px * <?php echo wp_kses_post($font_3_coeff) ?>)!important;}
			.dropdown-menu {font-size: 15px; font-size: calc(15px * <?php echo wp_kses_post($font_3_coeff) ?>);}



			/* Font 4 - font sizes */
			.blog-post-in-more .entry-title {font-size: 27px; font-size: calc(27px * <?php echo wp_kses_post($font_4_coeff) ?>);}
			.entry-full .entry-title {font-size: 53px; font-size: calc(53px * <?php echo wp_kses_post($font_4_coeff) ?>);}
			@media (max-width: 599px) {.entry-full .entry-title {font-size: 40px; font-size: calc(40px * <?php echo wp_kses_post($font_4_coeff) ?>);}}
			@media (min-width: 600px) and (max-width: 767px) {.entry-full .entry-title {font-size: 43px; font-size: calc(43px * <?php echo wp_kses_post($font_4_coeff) ?>);}}
			.mauer-blog-feed-list .entry-title {font-size: 36px; font-size: calc(36px * <?php echo wp_kses_post($font_4_coeff) ?>);}
			.mauer-blog-feed-2-cols .entry-title {font-size: 32px; font-size: calc(32px * <?php echo wp_kses_post($font_4_coeff) ?>);}



			/* Font 5 - font sizes */
			.portfolio-welcome-phrase {font-size: 39px; font-size: calc(39px * <?php echo wp_kses_post($font_5_coeff) ?>);}
			@media (max-width: 767px) {.portfolio-welcome-phrase {font-size: 34px; font-size: calc(34px * <?php echo wp_kses_post($font_5_coeff) ?>);}}



		<?php
		$css_to_add .= ob_get_clean();
		wp_add_inline_style('mauer-stills-stylesheet', $css_to_add);

	}

endif;

add_action('wp_enqueue_scripts', 'mauer_stills_add_inline_css_for_typography');





if (!function_exists('mauer_stills_hex2rgb')) :
	function mauer_stills_hex2rgb( $colour ) {
		if ($colour[0] == '#' ) {$colour = substr( $colour, 1 );}
		if (strlen( $colour ) == 6) {
			list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
		} elseif (strlen( $colour) == 3 ) {
			list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
		} else {
			return false;
		}
		return hexdec($r) . ',' . hexdec($g) .',' . hexdec($b);
	}
endif;





if (!function_exists('mauer_stills_add_inline_css_for_colors')) :

	function mauer_stills_add_inline_css_for_colors() {

		if ( (function_exists('get_field') && get_field('custom_colors', 'option')) || isset($_COOKIE['preview_color_scheme']) ) {

			ob_start();
			include(get_template_directory() . '/js/precookedColorAndFontSchemes.json');
			$json = ob_get_clean();
			$schemes_array = json_decode($json, true);

			// default colors, also needed to introduce keys for further iteration
			$colors = $schemes_array['color_scheme_1'];

			if (isset($_COOKIE['preview_color_scheme'])) {
				$colors = $schemes_array['color_scheme_' . $_COOKIE['preview_color_scheme']];
			}
			elseif (get_field('custom_colors', 'option')) {
				foreach ($colors as $k => $color) {
					$colors[$k] = get_field($k, 'option');
				}
			}

			$css_to_add = '';
			ob_start(); ?>

			/* Custom colors */

			body, .mauer-preloader, .mauer-navbar .navbar-nav>li>.dropdown-menu,
			body .sd-social-icon .sd-button span.share-count {
				background-color: <?php echo wp_kses_post($colors['background_color']) ?>;
			}

			.search-popup {
				background-color: <?php echo wp_kses_post($colors['background_color']) ?>;
				background-color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['background_color'])) ?>, 0.95);
			}

			body, .mauer-navbar .navbar-nav>li>a, .logo-link, blockquote,
			.image-imaginary-square-and-title a, .image-imaginary-square-and-title a:hover, .image-imaginary-square-and-title a:focus,
			.portfolio-project-tile a, .portfolio-project-tile a:hover, .portfolio-project-tile a:focus,
			body .sd-social-icon .sd-button span.share-count, .search-popup input.search-input,
			#comments .comment-heading a, #comments .logged-in-as a:hover,
			.entry-title a, .entry-title a:hover, .entry-title a:focus,
			.mauer-blog-feed .entry-title, .blog-post-in-more .entry-title,
			.mauer-navbar .text-logo {
				color: <?php echo wp_kses_post($colors['primary_color']) ?>;
			}

			.dropdown-menu>li>a {
				color: <?php echo wp_kses_post($colors['primary_color']) ?>!important;
			}

			.search-popup .mauer-close:before, .search-popup .mauer-close:after {
				background-color: <?php echo wp_kses_post($colors['primary_color']) ?>;
			}

			blockquote, .wp-block-quote:not(.is-large):not(.is-style-large), .mauer-spinner {
				border-color: <?php echo wp_kses_post($colors['primary_color']) ?>;
			}
			.mauer-spinner {border-top-color: transparent;}

			.portfolio-categories, .portfolio-categories a, .entry-meta, .entry-tags a, .entry-tags i,
			.sd-social-icon a.sd-button:before, .mauer-posts-archive-heading .mauer-archive-type, .mauer-search-query-in-results,
			.search-popup .search-input-p:before, .mauer-stills-gallery-pswp figcaption, .wp-caption-text, .entry-thumb-caption, .wp-block-image figcaption, .wp-block-embed figcaption,
			.footer-copyright, .footer-copyright a, .section-main-content .comment-respond .comment-notes, .section-main-content .comment-notes,
			.section-main-content .logged-in-as, .section-main-content .subscribe-label, .section-main-content .comment-date,
			#comments .logged-in-as a, .icon-links-in-navbar a, .entry-meta a, .more-link, .tile-content .more-link,
			blockquote cite, .wp-block-quote:not(.is-large):not(.is-style-large) cite, .wp-block-quote.is-large cite,
			.wp-block-quote.is-style-large cite, .wp-block-pullquote cite {
				color: <?php echo wp_kses_post($colors['secondary_color']) ?>;
			}

			.mauer-navbar .navbar-nav>li>a:hover, .mauer-navbar .navbar-nav>li>a:focus,
			.mauer-navbar .navbar-nav>.active>a, .mauer-navbar .navbar-nav>.active>a:hover,
			.mauer-navbar .navbar-nav>.active>a:focus, .mauer-navbar .current-menu-item>a,
			.mauer-navbar .current-menu-item>a:hover, .mauer-navbar .current-menu-item>a:focus,
			.mauer-navbar .navbar-nav .current-menu-ancestor>a, .mauer-navbar .navbar-nav .current-menu-ancestor>a:hover,
			.mauer-navbar .navbar-nav .current-menu-ancestor>a:focus,
			.mauer-navbar .navbar-nav>.open>a, .mauer-navbar .navbar-nav>.open>a:hover, .mauer-navbar .navbar-nav>.open>a:focus {
				color: <?php echo wp_kses_post($colors['active_menu_item_color']) ?>;
			}

			.dropdown-menu>li>a:hover, .dropdown-menu>li>a:focus,
			.dropdown-menu>.active>a, .dropdown-menu>.active>a:hover, .dropdown-menu>.active>a:focus {
				color: <?php echo wp_kses_post($colors['active_menu_item_color']) ?>!important;
			}

			a, #comments .comment-heading a:hover, #comments .comment-heading a:focus,
			.portfolio-categories a:hover, .portfolio-categories a:focus, .portfolio-categories .current-cat a,
			.footer-copyright a:hover, .footer-copyright a:focus,
			.entry-meta a:hover, .entry-meta a:focus,
			.more-link:hover, .more-link:focus,
			.tile-content .more-link:hover, .tile-content .more-link:focus {
				color: <?php echo wp_kses_post($colors['links_color']) ?>;
			}

			.section-main-content a {
				border-color: <?php echo wp_kses_post($colors['links_underline_color']) ?>;
			}

			.section-main-content a:not(.wp-block-button__link):not(.button):hover, .section-main-content a:not(.wp-block-button__link):not(.button):focus {
				color: <?php echo wp_kses_post($colors['links_color']) ?>;
				border-color: <?php echo wp_kses_post($colors['links_hover_underline_color']) ?>;
			}

			.section-main-content a.entry-thumb-link:hover, .section-main-content a.entry-thumb-link:focus {
				color: <?php echo wp_kses_post($colors['primary_color']) ?>;
			}

			.icon-links-in-navbar a:hover, .icon-links-in-navbar a:focus,
			body .sd-social-icon .sd-content ul li[class*='share-']) a:hover,
			body .sd-social-icon .sd-content ul li[class*='share-']) div.option a:hover,
			body .sharedaddy .sd-content a:hover:before, body .sharedaddy .sd-content a:focus:before {
				color: <?php echo wp_kses_post($colors['links_color']) ?>;
				opacity: 1;
				-moz-opacity: 1;
				-khtml-opacity: 1;
			}

			.mauer-navbar .navbar-toggle .icon-bar,
			input[type="submit"], .section-main-content input[type="submit"], button, .button,
			#footer input[type="submit"]:hover, #footer button:hover, #footer .button:hover,
			.wp-block-button__link {
				background-color: <?php echo wp_kses_post($colors['buttons_color']) ?>;
			}

			input[type="submit"], .section-main-content input[type="submit"], button, .button,
			.wp-block-button__link,
			.is-style-outline .wp-block-button__link:hover,
			.is-style-outline .wp-block-button__link:focus {
				color: <?php echo wp_kses_post($colors['buttons_text_color']) ?>;
			}

			input[type="submit"]:hover, .section-main-content input[type="submit"]:hover,
			button:hover, .section-main-content .button:hover,
			.wp-block-button__link:hover {
				background-color: <?php echo wp_kses_post($colors['buttons_color']) ?>;
				background-color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['buttons_color'])) ?>, 0.91);
				color: <?php echo wp_kses_post($colors['buttons_text_color']) ?>;
			}

			.is-style-outline .wp-block-button__link:hover, .is-style-outline .wp-block-button__link:focus {
				border-color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['buttons_color'])) ?>, 0.91);
			}

			.icon-links-in-navbar:before, .menu-item-with-top-divider:before {
				background-color: <?php echo wp_kses_post($colors['header_dash_color']) ?>;
			}

			.wp-block-separator {
				border-color: <?php echo wp_kses_post($colors['header_dash_color']) ?>;
			}

			.entry-full .entry-title:after {
				background-color: <?php echo wp_kses_post($colors['page_title_dash_color']) ?>;
			}

			::-webkit-input-placeholder {color: <?php echo wp_kses_post($colors['input_placeholders_color']) ?>;} /* WebKit, Blink, Edge */
			:-moz-placeholder {color: <?php echo wp_kses_post($colors['input_placeholders_color']) ?>; opacity: 1;} /* Mozilla Firefox 4 to 18 */
			::-moz-placeholder {color: <?php echo wp_kses_post($colors['input_placeholders_color']) ?>; opacity: 1;} /* Mozilla Firefox 19+ */
			:-ms-input-placeholder {color: <?php echo wp_kses_post($colors['input_placeholders_color']) ?>;} /* Internet Explorer 10-11 */
			:placeholder-shown {color: <?php echo wp_kses_post($colors['input_placeholders_color']) ?>;} /* Standard (https://drafts.csswg.org/selectors-4/#placeholder) */

			.section-main-content select, .section-main-content textarea,
			.section-main-content input[type="text"], .section-main-content input[type="password"],
			.section-main-content input[type="date"], .section-main-content input[type="month"],
			.section-main-content input[type="time"], .section-main-content input[type="week"],
			.section-main-content input[type="number"], .section-main-content input[type="email"], .section-main-content input[type="url"],
			.section-main-content input[type="search"], .section-main-content input[type="tel"],
			.section-main-content input[type="color"], .section-main-content .form-control,
			.entry-content table, .entry-excerpt table, .comment-text table,
			.entry-content tbody, .entry-excerpt tbody, .comment-text tbody,
			.wp-block-table td, .wp-block-table th,
			.wp-block-table.is-style-stripes td, .wp-block-table.is-style-stripes th {
				border-color: <?php echo wp_kses_post($colors['input_underline_color']) ?>!important;
			}

			.section-main-content select {
				border-color: <?php echo wp_kses_post($colors['input_underline_color']) ?>!important;
			}

			.sub-section-pre-footer, .section-other-projects {
				background-color: <?php echo wp_kses_post($colors['pre_footer_background_color']) ?>;
			}

			.widget>h4:first-child, .mauer-special-h4, .mauer-more-projects-wrapper .entry-title {
				color: <?php echo wp_kses_post($colors['pre_footer_headings_color']) ?>;
			}

			.sub-section-pre-footer, .widget, .sub-section-pre-footer a, .sub-section-pre-footer a.tag-cloud-link {
				color: <?php echo wp_kses_post($colors['pre_footer_text_color']) ?>;
			}

			.sub-section-pre-footer a, .sub-section-pre-footer a.tag-cloud-link {
				border-color: <?php echo wp_kses_post($colors['pre_footer_details_color']) ?>;
			}

			.sub-section-pre-footer a.tag-cloud-link:hover, .sub-section-pre-footer a.tag-cloud-link:focus {
				border-color: <?php echo wp_kses_post($colors['pre_footer_text_color']) ?>!important;
				border-color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['pre_footer_text_color'])) ?>, 0.68)!important;
			}

			.widgetized-area select, .widgetized-area textarea, .widgetized-area input[type="text"], .widgetized-area input[type="password"],
			.widgetized-area input[type="date"], .widgetized-area input[type="month"], .widgetized-area input[type="time"],
			.widgetized-area input[type="week"], .widgetized-area input[type="number"], .widgetized-area input[type="email"],
			.widgetized-area input[type="url"], .widgetized-area input[type="search"], .widgetized-area input[type="tel"],
			.widgetized-area input[type="color"], .widgetized-area .form-control {
				border-bottom-color: <?php echo wp_kses_post($colors['pre_footer_details_color']) ?>!important;
			}

			.widgetized-area select {
				border-color: <?php echo wp_kses_post($colors['pre_footer_details_color']) ?>!important;
			}

			.mauer-special-h4:before, .widget>h4:first-child:before, #footer input[type="submit"], #footer button, #footer .button {
				background-color: <?php echo wp_kses_post($colors['pre_footer_details_color']) ?>;
			}

			.widgetized-area select:hover, .widgetized-area textarea:hover, .widgetized-area input[type="text"]:hover, .widgetized-area input[type="password"]:hover,
			.widgetized-area input[type="date"]:hover, .widgetized-area input[type="month"]:hover, .widgetized-area input[type="time"]:hover,
			.widgetized-area input[type="week"]:hover, .widgetized-area input[type="number"]:hover, .widgetized-area input[type="email"]:hover,
			.widgetized-area input[type="url"]:hover, .widgetized-area input[type="search"]:hover, .widgetized-area input[type="tel"]:hover,
			.widgetized-area input[type="color"]:hover, .widgetized-area .form-control:hover,
			.widgetized-area select:focus, .widgetized-area textarea:focus, .widgetized-area input[type="text"]:focus, .widgetized-area input[type="password"]:focus,
			.widgetized-area input[type="date"]:focus, .widgetized-area input[type="month"]:focus, .widgetized-area input[type="time"]:focus,
			.widgetized-area input[type="week"]:focus, .widgetized-area input[type="number"]:focus, .widgetized-area input[type="email"]:focus,
			.widgetized-area input[type="url"]:focus, .widgetized-area input[type="search"]:focus, .widgetized-area input[type="tel"]:focus,
			.widgetized-area input[type="color"]:focus, .widgetized-area .form-control:focus,
			.widgetized-area a:hover, .widgetized-area a:focus {
				border-bottom-color: <?php echo wp_kses_post($colors['pre_footer_text_color']) ?>!important;
				border-bottom-color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['pre_footer_text_color'])) ?>, 0.68)!important;
			}

			.sub-section-pre-footer ::-webkit-input-placeholder {color: <?php echo wp_kses_post($colors['pre_footer_text_color']) ?>; color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['pre_footer_text_color'])) ?>, 0.52);} /* WebKit, Blink, Edge */
			.sub-section-pre-footer :-moz-placeholder {color: <?php echo wp_kses_post($colors['pre_footer_text_color']) ?>; color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['pre_footer_text_color'])) ?>, 0.52); opacity: 1;} /* Mozilla Firefox 4 to 18 */
			.sub-section-pre-footer ::-moz-placeholder {color: <?php echo wp_kses_post($colors['pre_footer_text_color']) ?>; color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['pre_footer_text_color'])) ?>, 0.52); opacity: 1;} /* Mozilla Firefox 19+ */
			.sub-section-pre-footer :-ms-input-placeholder {color: <?php echo wp_kses_post($colors['pre_footer_text_color']) ?>; color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['pre_footer_text_color'])) ?>, 0.52);} /* Internet Explorer 10-11 */
			.sub-section-pre-footer :placeholder-shown {color: <?php echo wp_kses_post($colors['pre_footer_text_color']) ?>; color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['background_color'])) ?>, 0.52);} /* Standard (https://drafts.csswg.org/selectors-4/#placeholder) */

			.sub-section-pre-footer input[type="submit"],
			.sub-section-pre-footer button, .sub-section-pre-footer .button,
			.section-other-projects input[type="submit"],
			.section-other-projects button, .section-other-projects .button {
				background-color: <?php echo wp_kses_post($colors['pre_footer_buttons_color']) ?>;
				color: <?php echo wp_kses_post($colors['pre_footer_buttons_text_color']) ?>;
			}

			.sub-section-pre-footer input[type="submit"]:hover,
			.sub-section-pre-footer button:hover, .sub-section-pre-footer .button:hover,
			.section-other-projects input[type="submit"]:hover,
			.section-other-projects button:hover, .section-other-projects .button:hover {
				background-color: <?php echo wp_kses_post($colors['pre_footer_buttons_color']) ?>;
				background-color: rgba(<?php echo wp_kses_post(mauer_stills_hex2rgb($colors['pre_footer_buttons_color'])) ?>, 0.91);
				color: <?php echo wp_kses_post($colors['pre_footer_buttons_text_color']) ?>;
			}


			<?php
			$css_to_add .= ob_get_clean();
			wp_add_inline_style('mauer-stills-stylesheet', $css_to_add);

		}
	}

endif;

add_action('wp_enqueue_scripts', 'mauer_stills_add_inline_css_for_colors');





if (!function_exists('mauer_stills_add_inline_css_for_logo')) :

	function mauer_stills_add_inline_css_for_logo() {
		if (function_exists('get_field') && get_field('logo_to_use', 'option') == 'image') {

			$default_val = 40;

			$css_to_add = "";
			if (get_field('logo_image_height', 'option')) {
				$val = get_field('logo_image_height', 'option');
			} else {
				$val = $default_val;
			}
			ob_start();?>
			.mauer-navbar .logo-link {
				height: <?php echo wp_kses_post($val); ?>px;
			}
			.mauer-navbar .navbar-toggle {height: <?php echo wp_kses_post($val); ?>px;}
			<?php
			$css_to_add .= ob_get_clean();
			wp_add_inline_style('mauer-stills-stylesheet', $css_to_add);
		}
	}

endif;

add_action('wp_enqueue_scripts', 'mauer_stills_add_inline_css_for_logo');

?>