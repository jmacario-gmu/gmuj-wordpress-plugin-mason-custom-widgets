/**
 * JS file to support the alert widget
 */

jQuery(document).ready(function ($) {

    // When the alert title is clicked-on, toggle the checkbox which controls whether the alert details are displayed
    $('.gmuj_widget_alert_ribbon_item h3').click(function(){
        toggleState($(this));
    });
    function toggleState(childItem){
        var targetCheckbox = $(childItem).closest('.gmuj_alert_ribbon_wrapper').children('.gmuj_alert_ribbon_toggle');
        if(targetCheckbox.prop('checked') == true){
            targetCheckbox.prop('checked',false);
        }else{
            targetCheckbox.prop('checked',true);
        }
    }

});
