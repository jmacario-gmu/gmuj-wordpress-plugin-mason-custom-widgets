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


// Include custom widget classes
	// Call-to-action menu
	include('php/classes/gmuj_widget_cta_menu.php');


/**
 * Hook into the widgets_init action to load our custom widgets
 */
add_action( 'widgets_init', 'gmuj_load_custom_widgets' );
function gmuj_load_custom_widgets() {

	// Register custom widgets
		// Call-to-action menu
		register_widget('gmuj_widget_cta_menu');
}

/**
 * Enqueue styles related to our custom widgets
 */
add_action( 'wp_enqueue_scripts', 'gmuj_custom_widgets_enqueue_styles' );
function gmuj_custom_widgets_enqueue_styles() {

	// Enqueue the plugin stylesheet
	wp_enqueue_style(
		'gmuj_custom_widgets', //stylesheet name
		plugin_dir_url(__FILE__) . 'css/custom-widgets.css' //path to stylesheet
	);

}
