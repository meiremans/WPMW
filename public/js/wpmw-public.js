(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

})(jQuery);


function wpmu_wizard_step2() {

    jQuery('#wizard2').removeClass('hidden');
    jQuery('#wizard1').addClass('hidden');

    return false
}
function wpmu_wizard_step3() {

    jQuery('#wizard3').removeClass('hidden');
    jQuery('#wizard2').addClass('hidden');

    return false
}
function wpmu_wizard_step4() {

    jQuery('#wizard4').removeClass('hidden');
    jQuery('#wizard3').addClass('hidden');

}
function wpmu_wizard_step5() {
    jQuery('#wizard4').addClass('hidden');
    submitNewSite();

}


function submitNewSite(e,theForm) {
    e.preventDefault();
    var data = jQuery(theForm).serialize();
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        dataType: "html",
        data: data,
        success: function () {
            window.alert("SUCCESS");
        }
    });
}
