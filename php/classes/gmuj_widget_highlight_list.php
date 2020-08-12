<?php

/**
 * php file which defines custom widget class: highlight list
 */


/**
 * custom widget class definition: highlight list
 */
class gmuj_widget_highlight_list extends WP_Widget {
    use gmuj_widget_image;
    
    // Set maximum number of highlight list items
    const MAX_COUNT = 8;

	/**
	 * function to instantiate widget class
	 */
    public function __construct() {
        WP_Widget::__construct(
        // Base ID of your widget
        'gmuj_widget_highlight_list', 
        // Widget name will appear in UI
        '(M) Highlight List',
        // Widget description
        array('description' => 'Display multiple images with titles and descriptions.') 
        );
    }
   
	/**
	 * function to render the widget (front-end)
	 */
    public function widget( $args, $instance ) {
        for ($i = 1; $i <= self::MAX_COUNT; $i++) {
            $highlight_list_items['name-'.$i] = isset( $instance['name-'.$i] ) ? strip_tags($instance['name-'.$i]) : '';
            $highlight_list_items['image-'.$i] = isset( $instance['image-'.$i] ) ? esc_url($instance['image-'.$i]) : '';
            $highlight_list_items['text-'.$i] = isset( $instance['text-'.$i] ) ? strip_tags($instance['text-'.$i]) : '';
            $highlight_list_items['url-'.$i] = isset( $instance['url-'.$i] ) ? esc_url_raw($instance['url-'.$i]) : '';
            $highlight_list_items['new_tab-'.$i] = isset( $instance['new_tab-'.$i] ) ? strip_tags($instance['new_tab-'.$i]) : '';
        }
        $count = isset($instance['count'])? strip_tags($instance['count']) : '';

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

        // Begin grid container (to hold the highlight list items)
        echo "<div class='widget_gmuj_widget_highlight_list_grid_container'>";

        // Loop through highlight list items
        for ($i = 1; $i <= $count; $i++) {

            // Begin highlight link
            echo '<a class="widget_gmuj_widget_highlight_list_item" ';
            echo 'href="'.$highlight_list_items['url-'.$i].'" ';
            // Open link in new tab if specified
            if($highlight_list_items['new_tab-'.$i] == 'true'){
                echo 'target="_blank" ';
            }
            echo '>';

            // Output highlight image
            echo $this->gmuj_widget_image_render( $instance, "widget_gmuj_widget_highlight_list_item_image", 'image-'.$i, false);

            // Output highlight title
            echo'<h4 class="highlight-name">'.$highlight_list_items['name-'.$i].'</h4>';

            // Output highlight description
            if (!empty($highlight_list_items['text-'.$i])){
                echo'<div class="highlight-description">'.$highlight_list_items['text-'.$i].'</div>';
            }

            // End highlight link
            echo'</a>';
        }

        // End grid container
        echo"</div>";
        ?>

	    <?php 

		// Finish widget output
		echo $args['after_widget'];
    }


	/**
	 * function to display widget edit form
	 */
    public function form( $instance ) {

        /**
         * Output jQuery script which supports the highlight list widget editor
         * 
         * For some reason, this code does not work well if placed in an external js file. The number of items select box functionality does not persist after the widget is saved.
         * 
         */
        ?>

        <!-- jQuery script providing functionality for the highlight list widget interface -->
        <script>
            jQuery(document).ready(function ($) {
                
                // Debug output
                console.log("script loaded: gmuj_widget_highlight_list");

                // Function to run when the highlight count select box changes
                $(".gmuj-widget-highlight-list-item-count").change(function () {

                    // Debug output
                    console.log("Highlight item count changed");

                    // Show the alert message
                    $(".gmuj-widget-highlight-list-item-count-change-message").css("display", "unset");

                    // Hide the widget items to force the user to save
                    $(".gmuj-widget-highlight-list-items").css("display", "none");

                });

            });
        </script>

        <?php

        // Title
        // Do we have a title?
        if (isset($instance['title'])) {
            // If so, store it
            $title = $instance['title'];
        }

        // Subtitle
        // Do we have a sub-title?
        if (isset( $instance['title_sub'])) {
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
                <select id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" class="gmuj-widget-highlight-list-item-count">
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
            <div class="gmuj-widget-highlight-list-item-count-change-message">The count has changed. Please save to update visible fields.</div>
            <?php

            // Output highlight items container
            ?>
            <div class="gmuj-widget-highlight-list-items">
                <?php 

                // Loop through highlight items
                for ($i = 1; $i <= $count; $i++) {

                    ?>
                    <div class='gmuj-widget-highlight-list-item gmuj-widget-highlight-list-item-<?php echo $i ?>'>
                        
                        <!-- highlight list item heading -->
                        <p class="gmuj-widget-highlight-list-item-heading">Item <?php echo $i ?></p>
                		
                        <!-- highlight list item name -->
                        <p>
                            <label for="<?php echo $this->get_field_id('name-'.$i); ?>">Item Name:</label></br>
                            <input class="widefat" id="<?php echo $this->get_field_id('name-'.$i); ?>" type="text" value="<?php echo $instance['name-'.$i]; ?>" name="<?php echo $this->get_field_name('name-'.$i); ?>" />
                        </p>

                        <!-- highlight list item image form -->
                    	<?php 
                            $this->gmuj_widget_image_form_item($instance, 'image-'.$i, 'Item Image: ', true, "", ""); 
                        ?>
                        
                        <!-- highlight list item description -->
                        <p>
                            <label for="<?php echo $this->get_field_id('text-'.$i); ?>">Item Description: </label>
                            </br>
                            <textarea class="widefat" id="<?php echo $this->get_field_id('text-'.$i); ?>" type="text" name="<?php echo $this->get_field_name('text-'.$i); ?>" ><?php echo $instance['text-'.$i]; ?></textarea>
                        </p>

                        <!-- highlight list item link -->
                        <p>
                            <label for="<?php echo $this->get_field_id('url-'.$i); ?>">Item Link:</label>
                            <input class="widefat" id="<?php echo $this->get_field_id('url-'.$i); ?>" name="<?php echo $this->get_field_name('url-'.$i); ?>" type="text" value="<?php echo $instance['url-'.$i]; ?>" />
                        </p>

                        <!-- open in a new tab? -->
                        <p>
                            <label for="<?php echo $this->get_field_id('new_tab-'.$i); ?>">Open in a new tab?</label>
                            <input type="checkbox" id="<?php echo $this->get_field_id('new_tab-'.$i); ?>" name="<?php echo $this->get_field_name('new_tab-'.$i); ?>" value="true" <?php if ($instance['new_tab-'.$i]=='true') echo 'checked="checked"'; ?> />
                        </p>

                    </div> <!--/.highlight-list-item-->
                    
                <?php 
                    } // end of highlight items loop
                ?> 

            </div> <!--/.highlight-widget-items-->

        <?php
    }

	/**
	 * function to update widget data, replacing old instances with new
	 */
	public function update( $new_instance, $old_instance ) {

        // Sanitize and store widget fields
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['title_sub'] = strip_tags($new_instance['title_sub']);
		$instance['count'] = strip_tags($new_instance['count']);
		// Loop through item fields
        for ($i = 1; $i <= self::MAX_COUNT; $i++) {
			$instance['name-'.$i] = strip_tags($new_instance['name-'.$i]);
			$instance['image-'.$i] = esc_url($new_instance['image-'.$i]);
			$instance['image-'.$i."_alt"] = $instance['name-'.$i];
			$instance['text-'.$i] = strip_tags($new_instance['text-'.$i]);
			$instance['url-'.$i] = esc_url_raw($new_instance['url-'.$i]);
            $instance['new_tab-'.$i] = strip_tags($new_instance['new_tab-'.$i]);
		}
        
        // Return
		return $instance;

	}

}
