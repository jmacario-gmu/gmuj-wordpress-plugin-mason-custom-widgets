<?php

/**
 * php file which defines custom widget class: call-to-action menu
 */


/**
 * custom widget class definition: call-to-action menu
 */
class gmuj_widget_cta_menu extends WP_Widget{

	/**
	 * function to instantiate widget class
	 */
	public function __construct() {
		WP_Widget::__construct(
		// Base ID of your widget
		'gmuj_widget_call_to_action_menu',
		// Widget name will appear in UI
		'(M) Call-to-Action Menu',
		// Widget description
		array('description' => 'Generates a list of call-to-action buttons using a navigation menu.')
		);
	}

	/**
	 * function to output widget front-end
	 */
	public function widget($args,$instance) {

		// Do we have a menu specified?
		if (isset($instance['related_menu'])) {

	        // Get current page URL slug
	        global $wp;
	        $current_slug = add_query_arg( array(), $wp->request );

	        // Get regex criteria
	        $regex_criteria=$instance['regex_criteria'];

	        // Does the regex criteria match the current URL slug?
	        if ( preg_match('/'.$regex_criteria.'/i', $current_slug) ) {

				// Begin widget output
				echo $args['before_widget'];

				// Output widget title, if it is not empty
				if (!empty($instance['title'])) {
					echo $args['before_title'];
					echo $instance['title'];
					echo $args['after_title'];
				}

				// Output widget sub-title, if it is not empty
				if (!empty($instance['title_sub'])) {
					echo '<p class="widget-title-sub">'.$instance['title_sub'].'</p>';
				}

				// Output call-to-action menu
				wp_nav_menu(
					array(
						'container' => false,
						'menu' => $instance['related_menu'],
						'menu_class' => 'cta-menu',
						'depth'=> '1',
						'link_after' => ' <span class="fa fa-chevron-circle-right"></span>' // Add FontAwesome right arrow after link text
					)
				);

				// Finish widget output
				echo $args['after_widget'];

			}

		}

	}

	/**
	 * function to display widget edit form
	 */
	public function form( $instance ) {

		// Get existing field values and set default values if needed

			// Title
			// Do we have a title?
			if (isset( $instance['title'])) {
				// If so, store it
				$title = $instance['title'];
			}

			// Subtitle
			// Do we have a sub-title?
			if (isset( $instance['title_sub'])) {
				// If so, store it
				$title_sub = $instance['title_sub'];
			}

			// Related menu
			// Do we have a menu specified?
			if (isset($instance['related_menu'])) {
				// If so, store it
				$related_menu = $instance[ 'related_menu' ];
			}

            // Regex criteria
            if (isset($instance['regex_criteria'])) {
                // If so, store it
                $regex_criteria = $instance['regex_criteria'];
                // But first fix the auto-escaping of slash chars we did when saving
                $regex_criteria = str_replace("\/","/",$regex_criteria);
            }

		// Display input fields
			// Title
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><br />
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" type="text" value="<?php echo $title ?>" name="<?php echo $this->get_field_name('title'); ?>" />
			</p>
			<?php

			// Sub-title
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title_sub'); ?>">Sub-title:</label><br />
				<input class="widefat" id="<?php echo $this->get_field_id('title_sub'); ?>" type="text" value="<?php echo $title_sub ?>" name="<?php echo $this->get_field_name('title_sub'); ?>" />
			</p>
			<?php

			// Related menu
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

            // Regex criteria
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('regex_criteria'); ?>">Regex criteria for display: </label>
                <input type="text" id="<?php echo $this->get_field_id('regex_criteria'); ?>" name="<?php echo $this->get_field_name('regex_criteria'); ?>" value="<?php echo $regex_criteria ?>" />
                <br />
                This widget will only appear if the regular expression provided matches the URL slug of the current page. Leaving this blank will result in this widget appearing on all pages.
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
            // Regex criteria, but auto-escape slash characters so they don't mess up the regex match (the system will think the first slash denotes the end of the pattern)
            $instance['regex_criteria'] = str_replace("/","\/",$new_instance['regex_criteria']);

		// Return
		return $instance;

	}

}