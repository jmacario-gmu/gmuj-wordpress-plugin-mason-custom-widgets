<?php

/**
 * Main plugin file for the Mason WordPress: Custom Widgets plugin
 */

/**
 * Plugin Name:       Mason Custom Widgets
 * Author:            Jan Macario
 * Plugin URI:        https://github.com/jmacario-gmu/gmuj-wordpress-plugin-mason-custom-widgets
 * Description:       Mason WordPress plugin which implements Mason-specific custom widgets.
 * Version:           0.0.1
 */


// Exit if this file is called directly.
if (!defined('WPINC')) {
	die;
}


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

	// Register custom widgets
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

}

/**
 * Enqueue public styles related to our custom widgets
 */
add_action('wp_enqueue_scripts','gmuj_custom_widgets_enqueue_styles');
function gmuj_custom_widgets_enqueue_styles() {

	// Enqueue the plugin public stylesheets

	// Custom widgets stylesheet
	wp_enqueue_style(
		'gmuj_custom_widgets', //stylesheet name
		plugin_dir_url(__FILE__) . 'css/custom-widgets.css' //path to stylesheet
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

	// Enqueue the plugin admin stylesheets
	wp_enqueue_style(
		'gmuj_widget_field_image_select', //stylesheet name
		plugin_dir_url(__FILE__) . 'css/admin.css' //path to stylesheet
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