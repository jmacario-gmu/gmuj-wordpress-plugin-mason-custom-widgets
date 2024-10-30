<?php

/**
 * Main plugin file for the Mason WordPress: Custom Widgets plugin
 */

/**
 * Plugin Name:       Mason WordPress: Mason Custom Widgets
 * Author:            Mason Web Operations and Services
 * Plugin URI:        https://github.com/jmacario-gmu/gmuj-wordpress-plugin-mason-custom-widgets
 * Description:       Mason WordPress plugin which implements Mason-specific custom widgets.
 * Version:           1.0.9
 */


// Exit if this file is called directly.
	if (!defined('WPINC')) {
		die;
	}

// Set up auto-updates
	require 'plugin-update-checker/plugin-update-checker.php';
	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/jmacario-gmu/gmuj-wordpress-plugin-mason-custom-widgets/',
	__FILE__,
	'gmuj-wordpress-plugin-mason-custom-widgets'
	);

// Include custom functions for this plugin
	// Color
	require(plugin_dir_path(__FILE__). '/php/functions-color.php');

/**
 * Hook into the widgets_init action to load our custom widgets
 */
add_action( 'widgets_init', 'gmuj_load_and_register_custom_widgets' );
function gmuj_load_and_register_custom_widgets() {

	// Include widget traits
		include('php/traits/gmuj_widget_image.php');

	// Include custom widget classes
		// Call-to-action list
		require('php/classes/gmuj_widget_cta_list.php');
		// Call-to-action menu
		require('php/classes/gmuj_widget_cta_menu.php');
		// Highlight item
		require('php/classes/gmuj_widget_highlight_item.php');
		// Highlight list
		require('php/classes/gmuj_widget_highlight_list.php');
		// Recent posts
		require('php/classes/gmuj_widget_recent_posts.php');
		// Site alert ribbon
		require('php/classes/gmuj_widget_alert_ribbon.php');
		// Regex content
		require('php/classes/gmuj_widget_regex_content.php');
        // people list
        require('php/classes/gmuj_widget_people_list.php');
		// Regex nav menu
		require('php/classes/gmuj_widget_regex_nav_menu.php');

	// Register custom widgets
		// Call-to-action list
		register_widget('gmuj_widget_cta_list');
		// Call-to-action menu
		register_widget('gmuj_widget_cta_menu');
		// Highlight item
		register_widget('gmuj_widget_highlight_item');
		// Highlight list
		register_widget('gmuj_widget_highlight_list');
		// Recent posts
		register_widget('gmuj_widget_recent_posts');
		// Alert Ribbon
		register_widget('gmuj_widget_alert_ribbon');
		// Regex content
		register_widget('gmuj_widget_regex_content');
		// People list
		register_widget('gmuj_widget_people_list');
		// Regex nav menu
		register_widget('gmuj_widget_regex_nav_menu');

}

/**
 * Enqueue public styles related to our custom widgets
 */
add_action('wp_enqueue_scripts','gmuj_custom_widgets_enqueue_styles');
function gmuj_custom_widgets_enqueue_styles() {

	// Enqueue the plugin public stylesheets

	// Enqueue the custom widgets stylesheets

	// Custom widgets stylesheet
	wp_enqueue_style(
		'gmuw_custom_widgets',
		plugin_dir_url(__FILE__) . 'css/custom-widgets.css',
        '',
        time()
	);

	// custom widget - alert ribbon
	wp_enqueue_style(
		'gmuw_custom_widgets_alert_ribbon',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-alert-ribbon.css',
		'',
		time()
	);

	// custom widget - calls-to-action list
	wp_enqueue_style(
		'gmuw_custom_widgets_cta_list',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-cta-list.css',
		'',
		time()
	);

	// custom widget - calls-to-action menu
	wp_enqueue_style(
		'gmuw_custom_widgets_cta_menu',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-cta-menu.css',
		'',
		time()
	);

	// custom widget - highlight item
	wp_enqueue_style(
		'gmuw_custom_widgets_highlight_item',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-highlight-item.css',
		'',
		time()
	);

	// custom widget - highlight list
	wp_enqueue_style(
		'gmuw_custom_widgets_highlight_list',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-highlight-list.css',
		'',
		time()
	);

	// custom widget - people list
	wp_enqueue_style(
		'gmuw_custom_widgets_people_list',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-people-list.css',
		'',
		time()
	);

	// custom widget - recent posts
	wp_enqueue_style(
		'gmuw_custom_widgets_recent_posts',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-recent-posts.css',
		'',
		time()
	);

	// custom widget - regex nav menu
	wp_enqueue_style(
		'gmuw_custom_widgets_regex_nav_menu',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-regex-nav-menu.css',
		'',
		time()
	);

	// custom widget - shared - display list
	wp_enqueue_style(
		'gmuw_custom_widgets_shared_display_list',
		plugin_dir_url(__FILE__) . 'css/custom-widgets-shared-display-list.css',
		'',
		time()
	);

}

/**
 * Enqueue public javascript related to our custom widgets
 */
add_action('wp_enqueue_scripts','gmuj_custom_widgets_enqueue_scripts');
function gmuj_custom_widgets_enqueue_scripts() {

	// Enqueue the plugin public javascripts

	// javascript to store alert ribbon open/closed state
	wp_enqueue_script('gmuj_widget_alert_ribbon', plugin_dir_url(__FILE__) . 'js/gmuj_widget_alert_ribbon.js', array('jquery'));

}

/**
 * Enqueue admin styles related to our custom widgets
 */
add_action('admin_enqueue_scripts','gmuj_custom_widgets_enqueue_styles_admin');
function gmuj_custom_widgets_enqueue_styles_admin() {

	// enqueue the plugin admin stylesheets

	// custom widgets admin
	wp_enqueue_style(
		'gmuw_custom_widgets_admin',
		plugin_dir_url(__FILE__) . 'css/admin.css',
		'',
		time()
	);

	// default admin styles
	wp_enqueue_style(
		'gmuw_custom_widgets_admin_default',
		plugin_dir_url(__FILE__) . 'css/admin-default.css',
		'',
		time()
	);

	// widget image select field
	wp_enqueue_style(
		'gmuw_custom_widgets_admin_field_image_select',
		plugin_dir_url(__FILE__) . 'css/admin-widget-field-image-select.css',
		'',
		time()
	);

	// calls-to-action list widget
	wp_enqueue_style(
		'gmuw_custom_widgets_admin_cta_list',
		plugin_dir_url(__FILE__) . 'css/admin-widget-cta-list.css',
		'',
		time()
	);

	// highlight list widget
	wp_enqueue_style(
		'gmuw_custom_widgets_admin_highlight_list',
		plugin_dir_url(__FILE__) . 'css/admin-widget-highlight-list.css',
		'',
		time()
	);

}

/**
 * Enqueue admin javascript related to our custom widgets
 */
add_action('admin_enqueue_scripts','gmuj_custom_widgets_enqueue_scripts_admin');
function gmuj_custom_widgets_enqueue_scripts_admin() {

	// Enqueue the plugin admin javascripts
	wp_enqueue_script( 'gmuj_widget_image', plugin_dir_url(__FILE__) . 'js/gmuj_widget_image.js' );

}