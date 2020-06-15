/**
 * JS file which handles the admin interface for the gmuj_widget_image trait
 */

jQuery(document).ready(function ($) {

    // Debug output
    //console.log("gmuj_widget_image script ready");

    if (typeof $var == 'undefined') {

        $var = 0;

        // When upload image button is clicked...
        $(document).on("click", ".gmuj-image-upload-form .upload_image_button", function (e) {
            
            // Debug output
            //console.log("gmuj_widget_image upload image button clicked");

            // Prevent default action
            e.preventDefault();

            // Store element which was clicked (the upload image button)
            var $button = $(this);
            
            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or upload image',
                library: { // remove these to show all
                    type: 'image' // specific mime
                },
                button: {
                      text: 'Select'
                },
                multiple: 'false'  // Set to true to allow multiple files to be selected
            });
       
            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                // We set multiple to false so only get one image from the uploader
                var attachment = file_frame.state().get('selection').first().toJSON();
                $button.parent().siblings('.upload_image_field').val(attachment.url);
                $button.parent().siblings().find('.image_alt_field').val(attachment.alt);
                $button.parent().siblings('.widget-form-img-preview').children('img').attr("src", attachment.url); 
                $button.parent().siblings('.widget-form-img-preview').show();
                $button.parent().siblings().find('input').trigger('change');
            });
       
            // Finally, open the modal
            file_frame.open();
            //console.log("upload image modal opened");

        });


        // When image thumbnail is clicked...
        $(document).on("click", ".gmuj-image-upload-form .widget-form-img-preview", function (e) {

            // Pretend that the user clicked the upload image button
            $(this).parent().find(".upload_image_button").trigger("click");

        });


        // When clear image button is clicked...
        jQuery(document).on("click", ".gmuj-image-upload-form .clear_image_button", function (e) {

            // Prevent default action
            e.preventDefault();
            
            // Store element which was clicked (the clear image button)
            var $button = $(this);
            
            // Debug output
                //console.log("clear image button clicked!");
            // Clear image upload field
                $button.parent().siblings('.upload_image_field').val("");
            // Clear value of image alt field
                $button.parent().siblings().find('.image_alt_field').val("");
            // Clear value of source attribute of preview image tag
                $button.parent().siblings().find('img').attr("src", ""); 
            // Hide preview image
                $button.parent().siblings().find('div.widget-form-img-preview').children('img').hide();
            // Trigger change
                $button.parent().siblings().find('input').trigger('change');

       });

    }

});
