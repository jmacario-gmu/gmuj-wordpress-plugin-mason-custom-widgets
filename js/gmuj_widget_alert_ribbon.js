/**
 * JS file for connecting the alert titles to the checkboxes that control open/closed states
 */

jQuery(document).ready(function ($) {
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
