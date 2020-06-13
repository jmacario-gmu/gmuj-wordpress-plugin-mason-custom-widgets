<style>
    .wp-color-result:not([style]), .wp-color-result[style=""] {
        background-image:url("<?php echo get_stylesheet_directory_uri(); ?>/images/x.png") !important;
        background-repeat: no-repeat !important;
    }
</style>
<script>
    jQuery(document).ready(function($){
        var myOptions = {
            // a callback to fire whenever the color changes to a valid color
            change:_.throttle(function(){
                //console.log("my-color-field changed!");
                $(this).trigger('change');}, 3000),
            clear:_.throttle(function(){
                //console.log("my-color-field cleared!");
                $(this).trigger('change');}, 3000)
        };
        $('.my-color-field').wpColorPicker(myOptions);
    });
</script>