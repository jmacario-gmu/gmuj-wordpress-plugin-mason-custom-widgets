<?php

/**
 * php file which defines custom widget class: call to action list
 */


/**
 * custom widget class definition: call to action list
 */
class gmuj_widget_cta_list extends WP_Widget {
    
    // Set maximum number of cta list items
    const MAX_COUNT = 8;

	/**
	 * function to instantiate widget class
	 */
    public function __construct() {
        WP_Widget::__construct(
        // Base ID of your widget
        'gmuj_widget_cta_list', 
        // Widget name will appear in UI
        '(M) Call-to-Action List',
        // Widget description
        array('description' => 'Display call-to-action buttons with titles and icons.') 
        );
    }

	/**
	 * function to render the widget (front-end)
	 */
    public function widget( $args, $instance ) {
        for ($i = 1; $i <= self::MAX_COUNT; $i++) {
            $cta_list_items['name-'.$i] = isset( $instance['name-'.$i] ) ? strip_tags($instance['name-'.$i]) : '';
            $cta_list_items['image-'.$i] = isset( $instance['image-'.$i] ) ? strip_tags($instance['image-'.$i]) : '';
            $cta_list_items['url-'.$i] = isset( $instance['url-'.$i] ) ? esc_url_raw($instance['url-'.$i]) : '';
        }
        $count = isset($instance['count'])? strip_tags($instance['count']) : '';

        // Get current page URL slug
        global $wp;
        $current_slug = add_query_arg( array(), $wp->request );

        // Get regex criteria
        $regex_criteria=$instance['regex_criteria'];

        // Does the regex criteria match the current URL slug?
        if ( preg_match('/'.$regex_criteria.'/i', $current_slug) ) {

            // Begin widget output
            echo $args['before_widget'];

            // Output widget title
            echo $args['before_title'];
            echo $instance['title'];
            echo $args['after_title'];

            // Output widget sub-title, if it is not empty
            if (!empty($instance['title_sub'])) {
                echo '<p class="widget-title-sub">'.$instance['title_sub'].'</p>';
            }

            // Begin list element (to hold the cta list items)
            echo '<ul class="cta-menu">';

            // Loop through cta list items
            for ($i = 1; $i <= $count; $i++) {

                // Begin CTA list item, taking into account whether an image has been selected
                if (!empty($cta_list_items['image-'.$i])) {
                    echo '<li style="background-image:url(\'/wp-content/plugins/gmuj-wordpress-plugin-mason-custom-widgets/images/'.$cta_list_items['image-'.$i].'\')">';
                } else {
                    echo '<li>';
                }

                // Begin cta link
                echo'<a href="'.$cta_list_items['url-'.$i].'">';

                // Output cta title
                echo $cta_list_items['name-'.$i];

                // Add icon
                echo ' <span class="fa fa-chevron-circle-right"></span>';

                // End cta link
                echo'</a>';

                // End cta link
                echo'</li>';

            }

            // End list element
            echo '</ul>';
            ?>

            <?php

            // Finish widget output
            echo $args['after_widget'];

        }

    }


	/**
	 * function to display widget edit form
	 */
    public function form( $instance ) {

        /**
         * Output jQuery script which supports the cta list widget editor
         * 
         * For some reason, this code does not work well if placed in an external js file. The number of items select box functionality does not persist after the widget is saved.
         * 
         */
        ?>

        <!-- jQuery script providing functionality for the cta list widget interface -->
        <script>
            jQuery(document).ready(function ($) {
                
                // Debug output
                console.log("script loaded: gmuj_widget_cta_list");

                // Function to run when the cta count select box changes
                $(".gmuj-widget-cta-list-item-count").change(function () {

                    // Debug output
                    console.log("cta item count changed");

                    // Show the alert message
                    $(".gmuj-widget-cta-list-item-count-change-message").css("display", "unset");

                    // Hide the widget items to force the user to save
                    $(".gmuj-widget-cta-list-items").css("display", "none");

                });

            });
        </script>

        <?php

        // Get existing field values, or set default values

            // Title
            // Do we have a title?
            if (isset($instance['title'])) {
                // If so, store it
                $title = $instance['title'];
            }

            // Sub-Title
            // Do we have a subtitle?
            if (isset($instance['title_sub'])) {
                // If so, store it
                $title_sub = $instance['title_sub'];
            }

            // Count of items
            // Do we have a count?
            if (isset($instance['count'])) {
                // If so, store it
                $count = $instance['count'];
            } else {
                // If not, set a default count
                $count = 4;
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
                <label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
                <br />
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

            // Count of items
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('count'); ?>">How many items? </label>
                <select id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" class="gmuj-widget-cta-list-item-count">
                <?php 
                for ($i = 1; $i <=self::MAX_COUNT; $i++) {
                    echo "<option value='".$i."'";
                    if ($count == $i) {echo " selected";}
                    echo ">".$i."</option>";
                }
                ?>
                </select>
            </p>
            <?php

            // Item count change message
            ?>
            <div class="gmuj-widget-cta-list-item-count-change-message">The count has changed. Please save to update visible fields.</div>
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

            // Output cta items container
            ?>
            <div class="gmuj-widget-cta-list-items">
                <?php 

                // Loop through cta items
                for ($i = 1; $i <= $count; $i++) {

                    ?>
                    <div class='gmuj-widget-cta-list-item gmuj-widget-cta-list-item-<?php echo $i ?>'>
                        
                        <!-- cta list item heading -->
                        <p class="gmuj-widget-cta-list-item-heading">Item <?php echo $i ?></p>
                		
                        <!-- cta list item name -->
                        <p>
                            <label for="<?php echo $this->get_field_id('name-'.$i); ?>">Item Name:</label></br>
                            <input class="widefat" id="<?php echo $this->get_field_id('name-'.$i); ?>" type="text" value="<?php echo $instance['name-'.$i]; ?>" name="<?php echo $this->get_field_name('name-'.$i); ?>" />
                        </p>

                        <!-- cta list item link -->
                        <p>
                            <label for="<?php echo $this->get_field_id('url-'.$i); ?>">Item Link:</label>
                            <input class="widefat" id="<?php echo $this->get_field_id('url-'.$i); ?>" name="<?php echo $this->get_field_name('url-'.$i); ?>" type="text" value="<?php echo $instance['url-'.$i]; ?>" />
                        </p>

                        <!-- cta list item image -->
                        <p>
                            <label for="<?php echo $this->get_field_id('image-'.$i); ?>">Item Image:</label></br>
                            <!--
                            <input class="widefat" id="<?php echo $this->get_field_id('image-'.$i); ?>" type="text" value="<?php echo $instance['image-'.$i]; ?>" name="<?php echo $this->get_field_name('image-'.$i); ?>" />
                            -->
                            <select id="<?php echo $this->get_field_id('image-'.$i); ?>" name="<?php echo $this->get_field_name('image-'.$i); ?>">
                                <option value="">Select image...</option>
                                <?php
                                // declare array of images
                                   $image_options = array(
                                    array("name"=>"Alert","file"=>"cta-alert.png"),
                                    array("name"=>"Athletics (star)","file"=>"cta-athletics.png"),
                                    array("name"=>"Books","file"=>"cta-books.png"),
                                    array("name"=>"Calendar","file"=>"cta-calendar.png"),
                                    array("name"=>"Financial","file"=>"cta-financial.png"),
                                    array("name"=>"Form","file"=>"cta-form.png"),
                                    array("name"=>"Graduate","file"=>"cta-graduate.png"),
                                    array("name"=>"Information","file"=>"cta-info.png"),
                                    array("name"=>"Mason M","file"=>"cta-mason.png"),
                                    array("name"=>"Music","file"=>"cta-music.png"),
                                    array("name"=>"News","file"=>"cta-news.png"),
                                    array("name"=>"Person","file"=>"cta-person.png"),
                                    array("name"=>"Question","file"=>"cta-question.png"),
                                    array("name"=>"Research","file"=>"cta-research.png"),
                                    array("name"=>"Theater","file"=>"cta-theater.png"),
                                    array("name"=>"Triforce","file"=>"cta-triforce.png"),
                                    array("name"=>"VA Seal","file"=>"cta-va-seal.png"),
                                );
                                // Loop through image options
                                foreach ($image_options as $image_option) {
                                    echo "<option value='".$image_option["file"]."'";
                                    if ($instance['image-'.$i] == $image_option["file"]) {echo " selected";}
                                    echo ">".$image_option["name"]."</option>";                            }
                                ?>
                            </select>
                        </p>
                        
                    </div> <!--/.cta-list-item-->
                    
                <?php 
                    } // end of cta items loop
                ?> 

            </div> <!--/.cta-widget-items-->

        <?php
    }

	/**
	 * function to update widget data, replacing old instances with new
	 */
	public function update( $new_instance, $old_instance ) {

        // Sanitize and store widget fields
            // Title field
            $instance['title'] = strip_tags($new_instance['title']);
            // Sub-title field
            $instance['title_sub'] = strip_tags($new_instance['title_sub']);
            // Item count field
            $instance['count'] = strip_tags($new_instance['count']);
            // Regex criteria, but auto-escape slash characters so they don't mess up the regex match (the system will think the first slash denotes the end of the pattern)
            $instance['regex_criteria'] = str_replace("/","\/",$new_instance['regex_criteria']);
            // Loop through item fields
            for ($i = 1; $i <= self::MAX_COUNT; $i++) {
                $instance['name-'.$i] = strip_tags($new_instance['name-'.$i]);
                $instance['image-'.$i] = strip_tags($new_instance['image-'.$i]);
                $instance['url-'.$i] = esc_url_raw($new_instance['url-'.$i]);
            }
        
        // Return
		return $instance;

	}

}
