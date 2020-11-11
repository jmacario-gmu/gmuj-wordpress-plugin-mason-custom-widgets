<?php

/**
 * php file which defines custom widget class: regex content
 */


/**
 * custom widget class definition: regex content
 */
class gmuj_widget_regex_content extends WP_Widget_Custom_HTML {

	/**
	 * function to instantiate widget class
	 */
	public function __construct() {

		WP_Widget::__construct(
		// Base ID of your widget
		'gmuj_widget_regex_content', 
		// Widget name will appear in UI
		'(M) Regex Content',
		// Widget description
		array( 'description' => 'Custom HTML content widget which is displayed based on matching the active page URL slug against a provided regular expression.') 
		);

	}

	/**
	 * function to render the widget (front-end)
	 */
	public function widget( $args, $instance ) {

		// Get global post variable
		global $post;

		// Remember the original post
		$original_post = $post;

		// Override global $post so filters and shortcodes apply in a consistent context
			// Is this a single post?
			if (is_singular()) {
				// Use this post
				$post = get_queried_object(); // Make sure post is always the queried object on singular queries (not from another sub-query that failed to clean up the global $post).
			} else {
				// Use no post
				$post = null; // Nullify the $post global during widget rendering to prevent shortcodes from running with the unexpected context on archive queries.
			}

		// 
		$instance = array_merge($this->default_instance,$instance);

		// Prepare instance data that looks like a normal text widget
		$simulated_text_widget_instance = array_merge(
			$instance,
			array(
				'text'   => isset($instance['content']) ? $instance['content'] : '',
				'filter' => false, // Because wpautop is not applied.
				'visual' => false, // Because it wasn't created in TinyMCE.
			)
		);

		unset($simulated_text_widget_instance['content']); // Was moved to 'text' prop.

		/** This filter is documented in wp-includes/widgets/class-wp-widget-text.php */
		$content = apply_filters('widget_text',$instance['content'],$simulated_text_widget_instance,$this);

		// Adds noreferrer and noopener relationships, without duplicating values, to all HTML 'a' elements that have a target.
		$content = wp_targeted_link_rel($content);

		// Filter the content of the custom HTML widget
		$content = apply_filters('widget_custom_html_content',$content, $instance,$this);

		// Restore the original post global
		$post = $original_post;



		// Get current page URL slug
		global $wp;
		$current_slug = add_query_arg( array(), $wp->request );
		
		// Output current URL slug
		//echo '<p>Current URL slug: '.$current_slug.'</p>';

		// Get regex criteria
		$regex_criteria=$instance['regex_criteria'];

		// Output widget regex content, if it is not empty
		//if (!empty($instance['regex_criteria'])) {
		//	echo '<p>Regex criteria: '.$instance['regex_criteria'].'</p>';
		//}

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

			// Output widget content
			echo $content;

			// Finish widget output
			echo $args['after_widget'];

		}

    }

	/**
	 * function to display widget edit form
	 */
	public function form($instance) {

		// Display standard custom HTML widget form
		parent::form($instance);

        // Get existing field values, or set default values

			// Subtitle
			// Do we have a sub-title?
			if (isset( $instance['title_sub'])) {
				// If so, store it
				$title_sub = $instance['title_sub'];
			}

			// Regex criteria
			if (isset($instance['regex_criteria'])) {
			    // If so, store it
			    $regex_criteria = $instance['regex_criteria'];
			    // But first fix the auto-escaping of slash chars we did when saving
			    $regex_criteria = str_replace("\/","/",$regex_criteria);
			}

        // Display input fields

			// Sub-title
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title_sub'); ?>">Sub-title:</label><br />
				<input class="widefat" id="<?php echo $this->get_field_id('title_sub'); ?>" type="text" value="<?php echo $title_sub ?>" name="<?php echo $this->get_field_name('title_sub'); ?>" />
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
	 * function to update widget data, replacing old instances with new
	 */
	public function update($new_instance,$old_instance) {

		// Update standard custom HTML widget fields
		$instance = parent::update($new_instance, $old_instance);

		// Update additional fields

			// Sub-title field
			$instance['title_sub'] = strip_tags($new_instance['title_sub']);

			// Regex criteria, but auto-escape slash characters so they don't mess up the regex match (the system will think the first slash denotes the end of the pattern)
			$instance['regex_criteria'] = str_replace("/","\/",$new_instance['regex_criteria']);

		return $instance;

	}

}
