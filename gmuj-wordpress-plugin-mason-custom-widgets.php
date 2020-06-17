<?php

/**
 * Main plugin file for the Mason WordPress: Custom Widgets plugin
 */

/**
 * Plugin Name:       Mason WordPress: Custom Widgets
 * Author:            Jan Macario
 * Plugin URI:        https://github.com/jmacario-gmu/gmuj-wordpress-plugin-mason-custom-widgets
 * Description:       Mason WordPress plugin which implements Mason-specific custom widgets.
 * Version:           0.0.1
 */


// Exit if this file is called directly.
if (!defined('WPINC')) {
	die;
}

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
		// Project list
		require('php/classes/gmuj_widget_project_list.php');
		// Recent posts
		require('php/classes/gmuj_widget_recent_posts.php');

	// Register custom widgets
		// Call-to-action menu
		register_widget('gmuj_widget_cta_menu');
		// Highlight item
		register_widget('gmuj_widget_highlight_item');
		// Projects
		register_widget('gmuj_widget_project_list');
		// Recent posts
		register_widget('gmuj_widget_recent_posts');

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
	wp_enqueue_script( 'gmuj_widget_image', plugin_dir_url( __FILE__ ) . 'js/gmuj_widget_image.js' );

}