/**
 * arabic theme scripts
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */

// jQuery(document).on( 'tinymce-editor-init', function( event, editor ) {
//     jQuery('#mceu_34-body #mceu_16 button').click();
// });

is_tinyMCE_active = false;
if ( typeof( tinyMCE) != "undefined" ) {
    if ( tinyMCE.activeEditor == null || tinyMCE.activeEditor.isHidden() != false ) {
        is_tinyMCE_active = true;
        jQuery('#mceu_34-body #mceu_16 button').click();
    }
}

jQuery(document).ready(function ($) {
    jQuery('.TriMS-admin-theme #wpbody-content #poststuff #titlewrap #title-prompt-text').html('أدخل العنوان هنا');
});
