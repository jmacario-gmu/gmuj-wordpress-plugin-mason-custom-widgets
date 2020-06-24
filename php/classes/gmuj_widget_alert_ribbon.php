<?php

/**
 * php file which defines custom widget class: alert ribbon
 */


/**
 * custom widget class definition: alert ribbon
 */
class gmuj_widget_alert_ribbon extends WP_Widget{

	/**
	 * function to instantiate widget class
	 */
	public function __construct() {
		WP_Widget::__construct(
		// Base ID of your widget
		'gmuj_widget_alert_ribbon',
		// Widget name will appear in UI
		'(Mason) Alert Ribbon',
		// Widget description
		array('description' => 'Displays a prominent alert ribbon across the top of each page in your site.')
		);
	}

	/**
	 * function to output widget front-end
	 */
	public function widget($args,$instance) {

		// If the 'display alert' box is checked (i.e. if this alert should be displayed publicly...
		if($instance['alert_display'] == 'true'){

			// Begin widget output
			echo $args['before_widget'];

			// Checkbox element used to toggle alert between open and closed states
			echo sprintf('<input class="gmuj_alert_ribbon_toggle" id="%s_toggle" type="checkbox">',$args['widget_id']);
			echo sprintf('<label class="gmuj_alert_ribbon_toggle_label" for="%s_toggle"><span>%s</span></label>',$args['widget_id'],$instance['alert_title']);

			// Display alert item
			echo '<div class="gmuj_widget_alert_ribbon_item">';	
			echo '<div class="gmuj_widget_alert_ribbon_item_content">';

			// Output widget title
			echo $args['before_title'];
			echo $instance['alert_title'];
			echo $args['after_title'];

			echo '<div class="gmuj_widget_alert_ribbon_summary">';

			// Alert summary
			echo $instance['alert_summary'];

			// Read more link
			// If we have a link...
			if(!empty($instance['alert_link'])) {
				// Display the link
				echo sprintf('<a class="gmuj_widget_alert_ribbon_link" href="%s">Read more</a>',$instance['alert_link']);
			}

			echo '</div><!--/.gmuj_widget_alert_ribbon_summary-->';

			echo '</div><!--/.gmuj_widget_alert_ribbon_item_content-->';

			echo '</div><!--/.gmuj_widget_alert_ribbon_item-->';

			// Finish widget output
			echo $args['after_widget'];

		}

	}

	/**
	 * function to display widget edit form
	 */
	public function form($instance) {

		// Get existing field values and set default values if needed
			// Alert title
			$alert_title = (isset( $instance['alert_title'])) ? $instance['alert_title'] : 'Alert:';

			// Alert summary
			$alert_summary = (isset( $instance['alert_summary'])) ? $instance['alert_summary'] : 'Enter your summary here.';

			// Alert link
			$alert_link = (isset( $instance['alert_link'])) ? $instance['alert_link'] : 'http://example.com';

			// Alert display
			$alert_display = (isset( $instance['alert_display'])) ? $instance['alert_display'] : '';

		// Display input fields
			// Title
			?>
			<p>
				<label for="<?php echo $this->get_field_id('alert_title'); ?>">Alert title:</label><br />
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" type="text" value="<?php echo $alert_title ?>" name="<?php echo $this->get_field_name('alert_title'); ?>" />
			</p>

			<?php
			// Summary
			?>
			<p>
				<label for="<?php echo $this->get_field_id('alert_summary'); ?>">Alert summary:</label><br />
				<input class="widefat" id="<?php echo $this->get_field_id('alert_summary'); ?>" type="text" value="<?php echo $alert_summary ?>" name="<?php echo $this->get_field_name('alert_summary'); ?>" />
			</p>

			<?php
			// Link to more information
			?>
			<p>
				<label for="<?php echo $this->get_field_id('alert_link'); ?>">Link to more information:</label><br />
				<input class="widefat" id="<?php echo $this->get_field_id('alert_link'); ?>" name="<?php echo $this->get_field_name('alert_link'); ?>" type="text" value="<?php echo $alert_link; ?>" />
			</p>

			<?php
			// Checkbox to display/hide item (for archiving alerts that you might want to reuse later)
			?>
			<p>
				<label for="<?php echo $this->get_field_id('alert_display'); ?>">Display alert?</label>
				<input type="checkbox" id="<?php echo $this->get_field_id('alert_display'); ?>" name="<?php echo $this->get_field_name('alert_display'); ?>" value="true" <?php if ($alert_display=='true') echo 'checked="checked"'; ?> />
			</p>


			<?php

	}

	/**
	 * Summary: function to update widget data
	 */
	public function update( $new_instance, $old_instance ) {

		// Sanitize and store widget fields
			// Title field
			$instance['alert_title'] = strip_tags($new_instance['alert_title']);
			// Summary
			$instance['alert_summary'] = strip_tags($new_instance['alert_summary']);
			// Link
			$instance['alert_link'] = strip_tags($new_instance['alert_link']);
			// Display toggle to maximize/minimize alert
			$instance['alert_display'] = strip_tags($new_instance['alert_display']);

		// Return
		return $instance;

	}

}