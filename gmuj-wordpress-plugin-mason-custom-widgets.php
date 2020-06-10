<?php

/**
 * Summary: Main plugin file for the Mason WordPress: Custom Widgets plugin
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
 * Summary: Enqueue styles related to our custom widgets
 */
add_action( 'wp_enqueue_scripts', 'gmuj_custom_widgets_enqueue_styles' );
function gmuj_custom_widgets_enqueue_styles() {

	// Enqueue the plugin stylesheet
	wp_enqueue_style(
		'gmuj_custom_widgets', //stylesheet name
		plugin_dir_url(__FILE__) . 'css/custom-widgets.css' //path to stylesheet
	);

}

/**
 * Summary: Hook into the widgets_init action to load our custom widgets
 */
add_action( 'widgets_init', 'gmuj_load_custom_widgets' );
function gmuj_load_custom_widgets() {

	// Register custom widgets
		// Menu button
		register_widget('menu_button_widget');
}


/**
 * Summary: Define custom widget - menu button class
 */
class menu_button_widget extends WP_Widget{

	/**
	 * Summary: function to instantiate widget class
	 */
    public function __construct() {
        WP_Widget::__construct(
        // Base ID of your widget
        'gmuj_widget_call_to_action_menu',
        // Widget name will appear in UI
        '(Mason) Call-to-Action Menu',
        // Widget description
        array('description' => 'Generates a list of call-to-action buttons using a navigation menu.')
        );
    }

	/**
	 * Summary: function to output widget
	 */
    public function widget($args,$instance) {

        // Do we have a menu specified?
        if (isset($instance['related_menu'])) {

			// Begin widget output
			echo '<li class="widget widget-cta-menu">';

			// Output widget title
			echo '<h3 class="widget-title">'.$instance['title'].'</h3>';

			// Output widget sub-title
			echo '<p class="widget-title-sub">'.$instance['title_sub'].'</p>';

			// Output call-to-action menu
			wp_nav_menu(
				array(
					'container' => false,
					'menu' => $instance['related_menu'],
					'menu_class' => 'cta-menu',
					'depth'=> '1',
					'link_after' => ' <span class="fa fa-chevron-circle-right"></span>'
				)
			);

			// Finish widget output
			echo '</li>';

		}

    }

	/**
	 * Summary: function to display widget edit form
	 */
    public function form( $instance ) {

        // Do we have a title?
		if (isset( $instance['title'])) {
			// If so, store it
			$title = $instance['title'];
		} else {
			// If not, set a default title
			$title = "Do Something!:";
		}

        // Do we have a sub-title?
		if (isset( $instance['title_sub'])) {
			// If so, store it
			$title_sub = $instance['title_sub'];
		} else {
			// If not, set a default sub-title
			$title_sub = "Preferably one of these things...";
		}

		// Do we have a menu specified?
        if (isset($instance['related_menu'])) {
			// If so, store it
            $related_menu = $instance[ 'related_menu' ];
        }

        // Display title input field
        ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><br />
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" type="text" value="<?php echo $title ?>" name="<?php echo $this->get_field_name('title'); ?>" />
	    </p>

	    <?php
        // Display sub-title input field
        ?>

		<p>
			<label for="<?php echo $this->get_field_id('title_sub'); ?>">Sub-title:</label><br />
			<input class="widefat" id="<?php echo $this->get_field_id('title_sub'); ?>" type="text" value="<?php echo $title_sub ?>" name="<?php echo $this->get_field_name('title_sub'); ?>" />
	    </p>

	    <?php
	    // Display menu input field
	    ?>

	    <p>
			<label for="<?php echo $this->get_field_id('related_menu'); ?>">Menu:</label><br />
			<select id="<?php echo $this->get_field_id('related_menu'); ?>" name="<?php echo $this->get_field_name('related_menu'); ?>">
	            <!--<option value="nope">Default Menu</option>-->
	            <?php foreach (wp_get_nav_menus() as $menu) {
	                $menu = $menu->to_array();
	                echo '<option value="'.$menu['slug'].'" ';
	                if ($related_menu == $menu['slug']) {echo " selected";}
	                echo '>'.$menu['name'].'</option>';
	            } ?>
	        </select>
	    </p>

		<?php

	}

	/**
	 * Summary: function to update widget data
	 */
	public function update( $new_instance, $old_instance ) {

		// Sanitize and store widget fields
			// Title field
			$instance['title'] = strip_tags($new_instance['title']);
			// Sub-title field
			$instance['title_sub'] = strip_tags($new_instance['title_sub']);
			// Related menu field
			$instance['related_menu'] = strip_tags($new_instance['related_menu']);

		// Return
		return $instance;

	}

}
