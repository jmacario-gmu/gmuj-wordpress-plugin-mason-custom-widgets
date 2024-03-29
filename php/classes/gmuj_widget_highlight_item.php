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



        // Get current page URL slug
        global $wp;
        $current_slug = add_query_arg( array(), $wp->request );

        // Get regex criteria
        $regex_criteria=isset($instance['regex_criteria']) ? $instance['regex_criteria'] : '';

		// Does the regex criteria match the current URL slug?
		// OR is this a homepage widget area, in which case we should display the widget regardless of the regex criteria
		if ( preg_match('/sidebar-homepage/i', $args['id']) || preg_match('/'.$regex_criteria.'/i', $current_slug) ) {

			// Begin widget output
			echo $args['before_widget'];

            // Output widget title, if it is not empty
            if (!empty($instance['title'])) {
                echo $args['before_title'];
                echo $instance['title'];
                echo $args['after_title'];
            }

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

            // Regex criteria
            if (isset($instance['regex_criteria'])) {
                // If so, store it
                $regex_criteria = $instance['regex_criteria'];
                // But first fix the auto-escaping of slash chars we did when saving
                $regex_criteria = str_replace("\/","/",$regex_criteria);
            } else {
				$regex_criteria = '';
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

        // Regex criteria
        ?>
        <p class="regex_criteria">
            <label for="<?php echo $this->get_field_id('regex_criteria'); ?>">Display this widget on which URLs (regex): </label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('regex_criteria'); ?>" name="<?php echo $this->get_field_name('regex_criteria'); ?>" value="<?php echo $regex_criteria ?>" />
            <br />
            This widget will only appear on a page if all or part of the URL of the page in question matches the text provided above. Leaving this field blank will result in this widget appearing on all pages. Note that this text is processed as a regular expression (regex), so you can use all the regular expression options in this field.
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
            // Regex criteria, but auto-escape slash characters so they don't mess up the regex match (the system will think the first slash denotes the end of the pattern)
            $instance['regex_criteria'] = str_replace("/","\/",$new_instance['regex_criteria']);

			// Update custom image trait fields
			$instance = array_merge($instance,$this->gmuj_widget_image_update($new_instance,$old_instance));

		return $instance;

	}

}
