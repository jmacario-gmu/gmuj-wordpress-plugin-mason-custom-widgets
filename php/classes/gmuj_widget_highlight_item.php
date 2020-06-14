<?php

/**
 * php file which defines custom widget class: highlight item
 */


/**
 * custom widget class definition: highlight item
 */
class gmuj_widget_highlight_item extends WP_Widget_Custom_HTML {
	use Image_Widget;

	/**
	 * function to instantiate widget class
	 */
	public function __construct() {

		WP_Widget::__construct(
		// Base ID of your widget
		'gmuj_widget_highlight_item', 
		// Widget name will appear in UI
		'(Mason) Highlight Item', 
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
		echo '<li class="widget widget-highlight-item">';

		// Title
		echo '<h3 class="highlight-item-title">' . $instance['title'] . '</h3>';
		
		// Widget content wrapper
		echo '<div class="highlight-item-content-wrapper">';

		// Widget content
		echo '<div class="highlight-item-content">' . $content . '</div>';
		// Image
		if ($has_image) {
			echo $this->image_render($instance, "highlight-item-image");
		}

		// End widget content wrapper
		echo '</div>';

		// Finish widget output
		echo '</li>';
    }

	/**
	 * function to display widget edit form
	 */
	public function form($instance) {
		parent::form($instance);
		$this->image_script();
		$this->image_form_item($instance, 'image', 'Image: ', true);
	}
	 
	/**
	 * function to update widget data, replacing old instances with new
	 */
	public function update($new_instance,$old_instance) {

		$instance = parent::update($new_instance, $old_instance);

		$instance = array_merge($instance,$this->image_update($new_instance,$old_instance));

		return $instance;

	}

}
