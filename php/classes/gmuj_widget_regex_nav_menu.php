<?php

/**
 * php file which defines custom widget class: regex nav menu
 */


/**
 * custom widget class definition: regex nav menu
 */
class gmuj_widget_regex_nav_menu extends WP_Nav_Menu_Widget {

	/**
	 * function to instantiate widget class
	 */
	public function __construct() {

		WP_Widget::__construct(
		// Base ID of your widget
		'gmuj_widget_regex_nav_menu', 
		// Widget name will appear in UI
		'(M) Nav Menu',
		// Widget description
		array( 'description' => 'Navigation menu widget which is displayed based on matching the active page URL slug against a provided regular expression.') 
		);

	}

	/**
	 * function to render the widget (front-end)
	 */
	public function widget( $args, $instance ) {

		// Get menu.
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( ! $nav_menu ) {
			return;
		}

		$default_title = __( 'Menu' );
		$title         = ! empty( $instance['title'] ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );


		// Get current page URL slug
		global $wp;
		$current_slug = add_query_arg( array(), $wp->request );
		
		// Output current URL slug
		//echo '<p>Current URL slug: '.$current_slug.'</p>';

		// Get regex criteria
        $regex_criteria=isset($instance['regex_criteria']) ? $instance['regex_criteria'] : '';

		// Output widget regex content, if it is not empty
		//if (!empty($instance['regex_criteria'])) {
		//	echo '<p>Regex criteria: '.$instance['regex_criteria'].'</p>';
		//}

        // Does the regex criteria match the current URL slug?
        // OR is this a homepage widget area, in which case we should display the widget regardless of the regex criteria
        if ( preg_match('/sidebar-homepage/i', $args['id']) || preg_match('/'.$regex_criteria.'/i', $current_slug) ) {

			// Begin widget output
			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';

			$format = apply_filters( 'navigation_widgets_format', $format );

			if ( 'html5' === $format ) {
				// The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
				$title      = trim( strip_tags( $title ) );
				$aria_label = $title ? $title : $default_title;

				$nav_menu_args = array(
					'fallback_cb'          => '',
					'menu'                 => $nav_menu,
					'container'            => 'nav',
					'container_aria_label' => $aria_label,
					'items_wrap'           => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				);
			} else {
				$nav_menu_args = array(
					'fallback_cb' => '',
					'menu'        => $nav_menu,
				);
			}

			wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );

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

			// Regex criteria
			if (isset($instance['regex_criteria'])) {
			    // If so, store it
			    $regex_criteria = $instance['regex_criteria'];
			    // But first fix the auto-escaping of slash chars we did when saving
			    $regex_criteria = str_replace("\/","/",$regex_criteria);
			} else {
				$regex_criteria = '';
			}

        // Display input fields

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

			// Regex criteria, but auto-escape slash characters so they don't mess up the regex match (the system will think the first slash denotes the end of the pattern)
			$instance['regex_criteria'] = str_replace("/","\/",$new_instance['regex_criteria']);

		return $instance;

	}

}
