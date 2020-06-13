<?php
// Creating the widget 
trait Color_Widget {

    public function get_title( $args, $instance, bool $center = true, $class = "", $after = "" ) {
        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        $title .= $after;

        // Inject the user's choice of text color into the inline styling.
        if ( ! empty( $this->get_text_color( $instance ) ) ) {
            $args['before_title'] = preg_replace( '/(?<=\sstyle=["\'])/', 'color: '.$this->get_text_color( $instance ). ";", $args['before_title']);
        }
        if ($center) {
            $args['before_title'] = preg_replace( '/(?<=\sclass=["\'])/', "widget-title-center ", $args['before_title']);
        }
        $args['before_title'] = preg_replace( '/(?<=\sclass=["\'])/', $class." ", $args['before_title']);
        if ( ! empty( $title ) ) {
            return $args['before_title'] . $title . $args['after_title'];
        }

        return '';
    }

    public function get_text_color( $instance ) {
        return isset( $instance['text_color'] ) ? esc_html($instance['text_color']) : '';
    }

    public function get_before_widget( $args, $instance, $extra_class = "" ) {
        $bg_color = isset( $instance['bg_color'] ) ? esc_html($instance['bg_color']) : '';
        
        // Inject the user's choice of background color into the inline styling.
        if ( ! empty( $bg_color ) ) {
            $args['before_widget'] = preg_replace( '/(?<=\sstyle=["\'])/', 'background-color: '.$bg_color . ";", $args['before_widget']);
        }
        $args['before_widget'] = preg_replace( '/(?<=\sclass=["\'])/', $extra_class." ", $args['before_widget']);
        return $args['before_widget']; 
    }

    // Widget Backend 
    public function color_form( $instance, string $default_txt = "", string $default_bg = "") {
        //echo "<pre>", print_r($instance), "</pre>";
        include_once 'inc/color_script.php';
        $this->color_form_item( $instance, 'text_color', 'Choose text color:', $default_txt);
        $this->color_form_item( $instance, 'bg_color', 'Choose background color:', $default_bg);
    }

    public function color_form_item( $instance, $name, string $prompt, $default = "" ) {

        if ( isset( $instance[ $name ]) ) {
            $new_color = $instance[ $name ];
        }

        else {
            $new_color = esc_html( $default );
        }
        // Widget admin form
        ?>

        <p><label for="<?php echo $this->get_field_id($name); ?>"><?php _e($prompt, 'sydney-child'); ?></label> 
        <input id="<?php echo $this->get_field_id($name); ?>" type="text" value="<?php echo $new_color; ?>" class="my-color-field" data-default-color="<?php echo $default; ?>" name="<?php echo $this->get_field_name($name); ?>" />
        </p>
         
    <?php 
    }
     
    // Updating widget replacing old instances with new
    public function color_update( $new_instance, $old_instance ) {
        $instance['text_color'] = sanitize_hex_color($new_instance['text_color']);
        $instance['bg_color'] = sanitize_hex_color($new_instance['bg_color']);
    return $instance;
    }

    public function hover_color(string $selector, string $color, string $property = "color", int $steps = 50) {
        if (!empty($color)) {
            if ($property != "color") {$property.="-color";}
            $property.= ": ";
            $output = "#".$this->id." ".$selector." {";
                $output.= $property.$color.";";
            $output.= "}";
            $output.= "#".$this->id." ".$selector.":hover {";
                $output.= $property.$this->adjust_brightness($color, $steps)."!important;";
            $output.= "}";
        }
        return $output;
    }

    // https://stackoverflow.com/a/11951022
    public static function adjust_brightness(string $hex, int $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
}
?>