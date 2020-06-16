<?php

/**
 * php file which defines custom widget class: project list
 */


/**
 * custom widget class definition: project list
 */
class gmuj_widget_project_list extends WP_Widget {
    use gmuj_widget_image;
    
    // Set maximum number of project list items
    const MAX_COUNT = 4;

	/**
	 * function to instantiate widget class
	 */
    public function __construct() {
        WP_Widget::__construct(
        // Base ID of your widget
        'gmuj_widget_project_list', 
        // Widget name will appear in UI
        '(Mason) Project List', 
        // Widget description
        array('description' => 'Display multiple images with titles and descriptions.') 
        );
    }
   
	/**
	 * function to render the widget (front-end)
	 */
    public function widget( $args, $instance ) {
        for ($i = 1; $i <= self::MAX_COUNT; $i++) {
            $projects['name-'.$i] = isset( $instance['name-'.$i] ) ? strip_tags($instance['name-'.$i]) : '';
            $projects['image-'.$i] = isset( $instance['image-'.$i] ) ? esc_url($instance['image-'.$i]) : '';
            $projects['text-'.$i] = isset( $instance['text-'.$i] ) ? strip_tags($instance['text-'.$i]) : '';
            $projects['url-'.$i] = isset( $instance['url-'.$i] ) ? esc_url_raw($instance['url-'.$i]) : '';
        }
        $count = isset($instance['count'])? strip_tags($instance['count']) : '';

		// Begin widget output
		echo $args['before_widget'];

		// Output widget title
		echo $args['before_title'];
		echo $instance['title'];
		echo $args['after_title'];

        // Begin grid container (to hold the project list items)
        echo "<div class='widget_gmuj_widget_project_list_grid_container'>";

        // Loop through project list items
        for ($i = 1; $i <= $count; $i++) {

            // Begin project link
            echo'<a class="widget_gmuj_widget_project_list_item" href="'.$projects['url-'.$i].'">';

            // Output project image
            echo $this->gmuj_widget_image_render( $instance, "widget_gmuj_widget_project_list_item_image", 'image-'.$i, false);

            // Output project title
            echo'<h4 class="project-name">'.$projects['name-'.$i].'</h4>';

            // Output project description
            if (!empty($projects['text-'.$i])){
                echo'<div class="project-description">'.$projects['text-'.$i].'</div>';
            }

            // End project link
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
         * Output jQuery script which supports the project list widget editor
         * 
         * For some reason, this code does not work well if placed in an external js file. The number of items select box functionality does not persist after the widget is saved.
         * 
         */
        ?>

        <!-- jQuery script providing functionality for the project list widget interface -->
        <script>
            jQuery(document).ready(function ($) {
                
                // Debug output
                console.log("script loaded: gmuj_widget_project_list");

                // Function to run when the project count select box changes
                $(".gmuj-widget-project-list-item-count").change(function () {

                    // Debug output
                    console.log("Project item count changed");

                    // Show the alert message
                    $(".gmuj-widget-project-list-item-count-change-message").css("display", "unset");

                    // Hide the widget items to force the user to save
                    $(".gmuj-widget-project-list-items").css("display", "none");

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

            // Count of items
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('count'); ?>">How many items? </label>
                <select id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" class="gmuj-widget-project-list-item-count">
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
            <div class="gmuj-widget-project-list-item-count-change-message">The count has changed. Please save to update visible fields.</div>
            <?php

            // Output project items container
            ?>
            <div class="gmuj-widget-project-list-items">
                <?php 

                // Loop through project items
                for ($i = 1; $i <= $count; $i++) {

                    ?>
                    <div class='gmuj-widget-project-list-item gmuj-widget-project-list-item-<?php echo $i ?>'>
                        
                        <!-- Project list item heading -->
                        <p class="gmuj-widget-project-list-item-heading">Item <?php echo $i ?></p>
                		
                        <!-- Project list item name -->
                        <p>
                            <label for="<?php echo $this->get_field_id('name-'.$i); ?>">Item Name:</label></br>
                            <input class="widefat" id="<?php echo $this->get_field_id('name-'.$i); ?>" type="text" value="<?php echo $instance['name-'.$i]; ?>" name="<?php echo $this->get_field_name('name-'.$i); ?>" />
                        </p>

                        <!-- Project list item image form -->
                    	<?php 
                            $this->gmuj_widget_image_form_item($instance, 'image-'.$i, 'Item Image: ', true, "", ""); 
                        ?>
                        
                        <!-- Project list item description -->
                        <p>
                            <label for="<?php echo $this->get_field_id('text-'.$i); ?>">Item Description: </label>
                            </br>
                            <textarea class="widefat" id="<?php echo $this->get_field_id('text-'.$i); ?>" type="text" name="<?php echo $this->get_field_name('text-'.$i); ?>" ><?php echo $instance['text-'.$i]; ?></textarea>
                        </p>

                        <!-- Project list item link -->
                        <p>
                            <label for="<?php echo $this->get_field_id('url-'.$i); ?>">Item Link:</label>
                            <input class="widefat" id="<?php echo $this->get_field_id('url-'.$i); ?>" name="<?php echo $this->get_field_name('url-'.$i); ?>" type="text" value="<?php echo $instance['url-'.$i]; ?>" />
                        </p>
                    
                    </div> <!--/.project-list-item-->
                    
                <?php 
                    } // end of project items loop
                ?> 

            </div> <!--/.project-widget-items-->

        <?php
    }

	/**
	 * function to update widget data, replacing old instances with new
	 */
	public function update( $new_instance, $old_instance ) {

        // Sanitize and store widget fields
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		// Loop through item fields
        for ($i = 1; $i <= self::MAX_COUNT; $i++) {
			$instance['name-'.$i] = strip_tags($new_instance['name-'.$i]);
			$instance['image-'.$i] = esc_url($new_instance['image-'.$i]);
			$instance['image-'.$i."_alt"] = $instance['name-'.$i];
			$instance['text-'.$i] = strip_tags($new_instance['text-'.$i]);
			$instance['url-'.$i] = esc_url_raw($new_instance['url-'.$i]);
		}
        
        // Return
		return $instance;

	}

}
