<?php
/*
Plugin Name: Mauer Stills Portfolio
Plugin URI: http://stills.mauer.co
Description: Adds portfolio functionality for use with Mauer Themes' Stills.
Author: Mauer Themes
Version: 1.1
Author URI: http://mauer.co
Text Domain: mauer-stills-portfolio
*/





if (!function_exists('mauer_stills_portfolio_plugin_init')) :
	function mauer_stills_portfolio_plugin_init() {
		load_plugin_textdomain('mauer-stills-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/lang');
	}
endif;

add_action('init', 'mauer_stills_portfolio_plugin_init');





if (!function_exists('mauer_stills_portfolio_register_custom_post_types')) :

	function mauer_stills_portfolio_register_custom_post_types() {
		$labels = array(
			'name'               => _x( 'Portfolio', 'post type general name', 'mauer-stills-portfolio' ),
			'singular_name'      => _x( 'Portfolio', 'post type singular name', 'mauer-stills-portfolio' ),
			'menu_name'          => _x( 'Portfolio', 'admin menu', 'mauer-stills-portfolio' ),
			'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'mauer-stills-portfolio' ),
			'add_new'            => __( 'Add New', 'mauer-stills-portfolio' ),
			'add_new_item'       => __( 'Add New Project', 'mauer-stills-portfolio' ),
			'new_item'           => __( 'New Project', 'mauer-stills-portfolio' ),
			'edit_item'          => __( 'Edit Project', 'mauer-stills-portfolio' ),
			'view_item'          => __( 'View Project', 'mauer-stills-portfolio' ),
			'all_items'          => __( 'All Projects', 'mauer-stills-portfolio' ),
			'search_items'       => __( 'Search Projects', 'mauer-stills-portfolio' ),
			'not_found'          => __( 'No Projects found.', 'mauer-stills-portfolio' ),
			'not_found_in_trash' => __( 'No Projects found in Trash.', 'mauer-stills-portfolio' )
		);
		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array('slug' => 'proj'),
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => 5,
			'supports'            => array('title', 'editor', 'thumbnail', 'comments', 'revisions'),
			'show_in_rest'				=> true
		);
		register_post_type('project', $args);

	}

endif;

add_action('init', 'mauer_stills_portfolio_register_custom_post_types');





if (!function_exists('mauer_stills_portfolio_register_custom_taxonomies')) :

	function mauer_stills_portfolio_register_custom_taxonomies() {
		$labels = array(
			'name'              => _x( 'Project Categories', 'taxonomy general name', 'mauer-stills-portfolio' ),
			'singular_name'     => _x( 'Project Category', 'taxonomy singular name', 'mauer-stills-portfolio' ),
			'search_items'      => __( 'Search Project Categories', 'mauer-stills-portfolio' ),
			'all_items'         => __( 'All Project Categories', 'mauer-stills-portfolio' ),
			'edit_item'         => __( 'Edit Project Category', 'mauer-stills-portfolio' ),
			'update_item'       => __( 'Update Project Category', 'mauer-stills-portfolio' ),
			'add_new_item'      => __( 'Add New Project Category', 'mauer-stills-portfolio' ),
			'new_item_name'     => __( 'New Project Category Name', 'mauer-stills-portfolio' ),
			'menu_name'         => __( 'Project Categories', 'mauer-stills-portfolio' ),
		);
		$args = array(
			'labels'              => $labels,
			'show_ui'             => true,
			'hierarchical'        => true,
			'show_admin_column'   => true,
			'query_var'           => true,
			'rewrite'             => array('slug' => 'proj-cat'),
			'sort'                => true,
		);
		register_taxonomy('project_cat', array('project'), $args);

	}

endif;

add_action('init', 'mauer_stills_portfolio_register_custom_taxonomies', 0);


?>
