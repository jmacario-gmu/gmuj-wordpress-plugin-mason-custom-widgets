<?php

/**
 * php file which defines custom widget class: highlight item
 */


/**
 * custom widget class definition: highlight item
 */
class gmuj_widget_highlight_item extends WP_Widget_Custom_HTML {
	use gmuj_widget_image;

	/**
	 * function to instantiate widget class
	 */
	public function __construct() {

		WP_Widget::__construct(
		// Base ID of your widget
		'gmuj_widget_highlight_item', 
		// Widget name will appear in UI
		'(M) Highlight Item',
		// Widget description
		array( 'description' => 'A text blurb next to an image.') 
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

		// Do we have an image?
		$has_image = !empty($instance['image']);

		// Begin widget output
		echo $args['before_widget'];

		// Output widget title
		echo $args['before_title'];
		echo $instance['title'];
		echo $args['after_title'];
		
		// Output image, if one is specified
		if ($has_image) {

			// Get image float setting and use it to set the variable specifiying the appropriate HTML class
			if ($instance['float']=='left') {
				$float='highlight-item-image-left';
			} else {
				$float='highlight-item-image-right';
			}

			// Output image, along with specified float class
			echo $this->gmuj_widget_image_render($instance, "highlight-item-image $float");

		}

		// Output widget content
		echo $content;

		// Finish widget output
		echo $args['after_widget'];
    }

	/**
	 * function to display widget edit form
	 */
	public function form($instance) {

		// Display standard custom HTML widget form
		parent::form($instance);
		// Display custom image trait form
		$this->gmuj_widget_image_form_item($instance, 'image', 'Image: ', true);

        // Get existing field values, or set default values
			// Float
	        if (isset($instance['float'])) {
	            // If so, store it
	            $float = $instance['float'];
	        } else {
	            // If not, set a default float
	            $float = 'right';
	        }

        // Image float
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('float'); ?>">Float? </label>
            <select id="<?php echo $this->get_field_id('float'); ?>" name="<?php echo $this->get_field_name('float'); ?>">
            <?php
            echo "<option value='right'";
            if ($float == 'right') {echo " selected";}
            echo ">Right</option>";
            echo "<option value='left'";
            if ($float == 'left') {echo " selected";}
            echo ">Left</option>";
            ?>
            </select>
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

			// Float
			$instance['float'] = strip_tags($new_instance['float']);

			// Update custom image trait fields
			$instance = array_merge($instance,$this->gmuj_widget_image_update($new_instance,$old_instance));

		return $instance;

	}

}
