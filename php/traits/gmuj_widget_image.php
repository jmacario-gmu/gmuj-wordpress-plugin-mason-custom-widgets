<?php
/**
 * php file defining a custom image selector for a wigdet 
 */

/**
 * Define the gmuj_widget_image trait 
 */
trait gmuj_widget_image {

    /**
     * Render the image on the front-end 
     */
    public function gmuj_widget_image_render( $instance, string $class = "", string $name ="image", bool $do_hover = true) {

        if (isset($instance[$name])) {
            $image = $instance[$name];
        } else {
            $image = $default;
        }
        if (isset($instance[$name."_alt"])) {
            $image_alt = $instance[$name."_alt"];
        }
        $output = "<img class='".$class."' src='".$image."' alt='".$image_alt;
        if ($do_hover) {$output .= "' title='".$image_alt;}
        $output .= "'/>";

        return $output;

    }

    /**
     * Load the admin image select widget 
     */
    public function gmuj_widget_image_form_item( $instance, $name, string $prompt, bool $preview, $default = "", $force_alt = "" ) {

        if (isset($instance[$name])) {
            $image = $instance[$name];
        } else {
            $image = $default;
        }

        if (!empty($force_alt)) {
            $image_alt = $force_alt;
        } elseif (isset($instance[$name."_alt"])) {
            $image_alt = $instance[$name."_alt"];
        }
        // Widget admin form
        ?>

        <div class="gmuj-image-upload-form">
           
            <!-- Image input label -->
            <p>
                <label for="<?php echo $this->get_field_id($name); ?>"><?php echo $prompt; ?></label>
            </p>

            <!-- Image buttons -->
            <p>
                <!-- Choose image -->
                <input class="upload_image_button button button-primary" type="button" value="Choose Image" /> 
                <!-- Clear image -->
                <input class="clear_image_button button button-secondary" type="button" value="Clear Image" /> 
            </p>

            <!-- Image preview -->
            <?php if ($preview) { ?>
                <div class="widget-form-img-preview" style="<?php if (empty($image)) {echo 'display: none;';} ?>" >
                    <img src="<?php echo esc_url($image); ?>" />
                </div>
            <?php } ?>

            <input class="widefat upload_image_field" id="<?php echo $this->get_field_id($name); ?>" name="<?php echo $this->get_field_name($name); ?>" value="<?php echo $image;?>" <?php if ($preview) {echo "type='hidden'";} ?>/>

            <?php if (empty($force_alt)) { ?>
                <p>
                    <label for="<?php echo $this->get_field_id($name)."_alt"; ?>">Image alternative text (alt text):</label>
                    <br />
                    <input class="widefat image_alt_field" id="<?php echo $this->get_field_id($name."_alt"); ?>" name="<?php echo $this->get_field_name($name."_alt"); ?>" value="<?php echo $image_alt;?>" />
                </p>
            <?php } ?>

        </div>
    <?php 
    }

    /**
     * Update the admin image select widget instance 
     */
    public function gmuj_widget_image_update($new_instance, $old_instance, string $name = "image") {

        $instance[$name] = esc_url($new_instance[$name]);
        $instance[$name."_alt"] = strip_tags($new_instance[$name."_alt"]);

        return $instance;

    }

}
