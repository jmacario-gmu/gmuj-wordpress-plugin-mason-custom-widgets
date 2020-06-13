<style>
    .widget-form-img-preview{
        max-width: <?php echo $width; ?>; 
        cursor: pointer;
    }
</style>

<script>
    
    // Original: https://vedmant.com/using-wordpress-media-library-in-widgets-options/
    jQuery(document).ready(function ($) {
        //console.log("upload_image_button_widget ready!");
        if (typeof $var == 'undefined') {
            $var = 0;
            $(document).on("click", ".widget-form-img-preview", function (e) {
                $(this).siblings(".upload_image_button").trigger("click");
            });
            $(document).on("click", ".upload_image_button", function (e) {
                e.preventDefault();
                var $button = $(this);
                //console.log("upload_image_button_widget clicked!");
           
                // Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select or upload image',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                          text: 'Select'
                    },
                    multiple: <?php echo json_encode($multiple);?>  // Set to true to allow multiple files to be selected
                });
           
                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader
                    var attachment = file_frame.state().get('selection').first().toJSON();
                    $button.siblings('.upload_image_field').val(attachment.url);
                    $button.siblings('.image_alt_field').val(attachment.alt);
                    $button.siblings('.widget-form-img-preview').children('img').attr("src", attachment.url); 
                    $button.siblings('.widget-form-img-preview').show();
                    $button.siblings('input').trigger('change');
                });
           
                // Finally, open the modal
                file_frame.open();
                //console.log("upload_image_button_widget modal opened!");
            });
            $(document).on("click", ".clear_image_button", function (e) {
                e.preventDefault();
                var $button = $(this);
                //console.log("clear_image_button_widget clicked!");
                $button.siblings('.upload_image_field').val("");
                $button.siblings('img').attr("src", ""); 
                $button.siblings('img').hide();
                $button.siblings('input').trigger('change');
           });
        }
    });
</script>