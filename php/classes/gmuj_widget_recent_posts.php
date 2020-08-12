<?php

/**
 * php file which defines custom widget class: recent posts
 */


/**
 * custom widget class definition: recent posts
 */
class gmuj_widget_recent_posts extends WP_Widget {

    /**
     * function to instantiate widget class
     */
    public function __construct() {
        WP_Widget::__construct(
        // Base ID of your widget
        'gmuj_widget_recent_posts', 
        // Widget name will appear in UI
        '(M) Recent Posts',
        // Widget description
        array('description' => 'Show your most recent posts.') 
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
                <label for="<?php echo $this->get_field_id( 'category' ); ?>">Enter the slug for your category or leave empty to show posts from all categories.</label>
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

    }

    /**
     * function to update widget data, replacing old instances with new
     */
    function update($new_instance, $old_instance) {

        // Sanitize and store widget fields
        $instance['title']     = strip_tags($new_instance['title']);
        $instance['title_sub'] = strip_tags($new_instance['title_sub']);
        $instance['number']    = strip_tags($new_instance['number']);
        $instance['category']  = strip_tags($new_instance['category']);

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

        // Run query for posts
        $r = new WP_Query(array(
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'posts_per_page'      => $number_of_posts,
            'ignore_sticky_posts' => 1,
            'category_name'       => $category
        ));

        // Begin widget output
        echo $args['before_widget'];

        // Do we have posts to display?
        if ($r->have_posts()){

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

            // Begin grid container (to hold the highlight list items)
            ?>
            <div class='widget_gmuj_widget_highlight_list_grid_container'>

                <?php 
                // Loop through posts
                while ($r->have_posts()): $r->the_post(); 

                    // Begin post link
                    echo'<a class="widget_gmuj_widget_highlight_list_item" href="'.get_permalink(get_page_by_path(get_post_field('post_name'))).'">';

                    // Do we have an image for this post?
                        if (has_post_thumbnail()) { 
                            // If so, output it
                            //the_post_thumbnail(); 
                            the_post_thumbnail('post-thumbnail', ['class' => 'widget_gmuj_widget_highlight_list_item_image']);
                        } else {
                            // If not, get the path of a default image
                                // First, choose a random brand color
                                $random_color=gmuj_random_brand_color();
                                // Next, generate the image file path using the random color
                                $image=plugins_url().'/gmuj-wordpress-plugin-mason-custom-widgets/images/mason-default-image-'.$random_color.'-640x480.png';
                                // Output the HTML image tag
                                echo "<img class='widget_gmuj_widget_highlight_list_item_image' src='". $image. "' />";
                        }

                    // Output post title
                    echo'<h4 class="highlight-name">'.get_the_title().'</h4>';

                    // Output posr excerpt
                    echo'<div class="highlight-description">'.get_the_excerpt().'</div>';

                    // End post link
                    echo'</a>';

                endwhile; // End post loop
                ?>

            </div>

            <?php
        }

        // Finish widget output
        echo $args['after_widget'];

    }

}
