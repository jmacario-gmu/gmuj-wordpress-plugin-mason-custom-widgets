<?php

/**
 * php file which defines custom widget class: people list
 */


/**
 * custom widget class definition: recent posts
 */
class gmuj_widget_people_list extends WP_Widget {

    /**
     * function to instantiate widget class
     */
    public function __construct() {
        WP_Widget::__construct(
        // Base ID of your widget
        'gmuj_widget_people_list', 
        // Widget name will appear in UI
        '(M) People List',
        // Widget description
        array('description' => 'A list of people using the person custom post type.') 
        );
    }

    /**
     * function to display widget edit form
     */
    function form($instance) {

        // Get existing field values and set default values if needed
            // Title
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            // Sub-title
            $title_sub = isset($instance['title_sub']) ? esc_attr($instance['title_sub']) : '';
            // Category
            $category = isset($instance['category']) ? esc_attr($instance['category']) : '';
            // Number
                $number = isset( $instance['number'] ) ? intval( $instance['number'] ) : 4;
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
            // Title
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <?php

            // Sub-title
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title_sub'); ?>">Sub-title:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title_sub'); ?>" name="<?php echo $this->get_field_name('title_sub'); ?>" type="text" value="<?php echo $title_sub; ?>" />
            </p>
            <?php

            // Category
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'category' ); ?>">Enter the slug for your group or leave empty to show people from all groups.</label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" type="text" value="<?php echo $category; ?>" size="3" />
            </p> 
            <?php

            // Number
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of posts to show (-1 shows all of them):</label>
                <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
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
    function update($new_instance, $old_instance) {

        // Sanitize and store widget fields
            // Title
            $instance['title']     = strip_tags($new_instance['title']);
            // Subtitle
            $instance['title_sub'] = strip_tags($new_instance['title_sub']);
            // Number of posts
            $instance['number']    = strip_tags($new_instance['number']);
            // Category of posts
            $instance['category']  = strip_tags($new_instance['category']);
            // Regex criteria, but auto-escape slash characters so they don't mess up the regex match (the system will think the first slash denotes the end of the pattern)
            $instance['regex_criteria'] = str_replace("/","\/",$new_instance['regex_criteria']);

        // Return
        return $instance;

    }

    /**
     * function to render the widget (front-end)
     */
    function widget($args, $instance) {

        // Set number of posts to display
        if (!empty($instance['number'])) {
            $number_of_posts=intval($instance['number']);
        } else {
            $number_of_posts=-1;
        }
        if (!$number_of_posts) { 
            $number_of_posts=-1;
        }

        // Category of posts
        $category = isset($instance['category']) ? esc_attr($instance['category']) : '';

        // Do we have a category?
        if (!empty($category)) {
            // Query for only those people in the selected category
                $r = new WP_Query(array(
                    'post_type'           => 'person',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'groups',
                            'field'    => 'slug',
                            'terms' => $category,
                        ),
                    ),
                    'no_found_rows'       => true,
                    'post_status'         => 'publish',
                    'posts_per_page'      => $number_of_posts,
                    'ignore_sticky_posts' => 1,
                    'meta_key'            => 'gmuj_name_last',
                    'orderby'             => array( 'meta_value' => 'ASC')
                ));            
        } else {
            //Query for all people
                $r = new WP_Query(array(
                    'post_type'           => 'person',
                    'no_found_rows'       => true,
                    'post_status'         => 'publish',
                    'posts_per_page'      => $number_of_posts,
                    'ignore_sticky_posts' => 1,
                    'meta_key'            => 'gmuj_name_last',
                    'orderby'             => array( 'meta_value' => 'ASC')
                )); 
        }

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

            // Do we have posts to display?
            if ($r->have_posts()){

                // Output widget title, if it is not empty
                if (!empty($instance['title'])) {
                    echo PHP_EOL;
                    echo $args['before_title'];
                    echo $instance['title'];
                    echo $args['after_title'];
                }

                // Output widget sub-title, if it is not empty
                if (!empty($instance['title_sub'])) {
                    echo PHP_EOL;
                    echo '<p class="widget-title-sub">'.$instance['title_sub'].'</p>';
                }

                // Begin grid container (to hold the highlight list items)
                echo PHP_EOL;
                ?>
                <div class='widget_gmuj_widget_display_list_grid_container'>

                    <?php
                    // Loop through posts
                    while ($r->have_posts()): $r->the_post();

                        // Begin post link
                        echo PHP_EOL;
                        echo'<a class="widget_gmuj_widget_display_list_item" href="'.get_permalink().'">';

                        // Do we have an image for this post?
                            echo PHP_EOL."\t";
                            if (has_post_thumbnail()) {
                                // If so, output it
                                //the_post_thumbnail();
                                the_post_thumbnail('post-thumbnail', ['class' => 'widget_gmuj_widget_display_list_item_image']);
                            } else {
                                // If not, get the path of a default image
                                    // First, choose a random brand color
                                    $random_color=gmuj_random_brand_color();
                                    // Next, generate the image file path using the random color
                                    $image=plugins_url().'/gmuj-wordpress-plugin-mason-custom-widgets/images/mason-default-image-'.$random_color.'-640x480.png';
                                    // Output the HTML image tag
                                    echo "<img class='widget_gmuj_widget_display_list_item_image' src='". $image. "' alt='' />";
                            }

                        // Output person name
                        echo PHP_EOL."\t";
                        echo'<h4 class="widget_gmuj_widget_display_list_item_name">'.get_the_title().'</h4>';

                        // Output person title
                        if ( get_post_meta( get_the_ID(), 'gmuj_person_title', true ) ) {
                            echo PHP_EOL."\t";
                            echo '<h4 class="person-title">'.get_post_meta( get_the_ID(), 'gmuj_person_title', true ).'</h4>';
                        }

                        // End post link
                        echo PHP_EOL;
                        echo'</a>';

                    endwhile; // End post loop
                    ?>

                </div>

                <?php
            }

            // Finish widget output
            echo $args['after_widget'];

        }

        // Since we ran another query inside the regular WordPress loop, reset the post global to the current post in the main query
        wp_reset_postdata();

    }

}
