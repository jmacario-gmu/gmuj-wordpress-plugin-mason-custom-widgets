<?php
// Creating the widget 
trait Image_Widget {

    // Widget Backend 
    public static function image_script(bool $multiple = false, $width = "100%") { 
        include_once 'inc/image_script.php'; 
    }

    public function image_form_item( $instance, $name, string $prompt, bool $preview, $default = "", $force_alt = "" ) {

        if ( isset( $instance[ $name ]) ) {
            $image = $instance[ $name ];
        } else {
            $image = $default;
        }

        if (!empty($force_alt)) {
            $image_alt = $force_alt;
        } elseif ( isset( $instance[ $name."_alt" ]) ) {
            $image_alt = $instance[ $name."_alt" ];
        }
        // Widget admin form
        ?>

        <p>
            <label for="<?php echo $this->get_field_id($name); ?>"><?php _e($prompt); ?></label>   <br/> 
            <?php if ($preview) { ?>
                <div class="widget-form-img-preview" style="<?php if (empty($image)) {echo 'display: none;';} ?>" >
                    <img src="<?php echo esc_url( $image ); ?>" style="display: block; max-height: 150px; margin-left: auto; margin-right: auto;" />
                </div>
            <?php } ?> 
            <input class="widefat upload_image_field" id="<?php echo $this->get_field_id($name); ?>" name="<?php echo $this->get_field_name($name); ?>" value="<?php echo $image;?>" <?php if ($preview) {echo "type='hidden'";} ?>/><br/>
                <?php if (empty($force_alt)) { ?>
                    <label for="<?php echo $this->get_field_id($name)."_alt"; ?>">Alt text: (<span style="color:red">Recommended for accessibility</span>)</label> 
                    <input class="image_alt_field" id="<?php echo $this->get_field_id($name."_alt"); ?>" name="<?php echo $this->get_field_name($name."_alt"); ?>" value="<?php echo $image_alt;?>" />
                <?php } ?>
            <br/><input class="upload_image_button button button-primary" type="button" value="Choose Image" /> <input class="clear_image_button button button-secondary" type="button" value="Clear Image" /> 
        </p>
    <?php 
    }

    public function image_render( $instance, string $class = "", string $name ="image", bool $do_hover = true) {
        if ( isset( $instance[ $name ]) ) {
            $image = $instance[ $name ];
        } else {
            $image = $default;
        }
        if ( isset( $instance[ $name."_alt" ]) ) {
            $image_alt = $instance[ $name."_alt" ];
        }
        $output = "<img class='".$class."' src='".$image."' alt='".$image_alt;
        if ($do_hover) {$output .= "' title='".$image_alt;}
        $output .= "'/>";

        return $output;
    }

    public function image_update( $new_instance, $old_instance, string $name = "image" ) {
        $instance[ $name ] = esc_url($new_instance[ $name ]);
        $instance[ $name."_alt" ] = strip_tags($new_instance[ $name."_alt" ]);

        return $instance;
    }
}
?>