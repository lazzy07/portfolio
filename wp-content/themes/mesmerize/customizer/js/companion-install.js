jQuery(document).ready(function () {
    tb_show('Mesmerize Companion', '#TB_inline?width=400&height=430&inlineId=mesmerize_homepage');
    jQuery('#TB_closeWindowButton').hide();
    jQuery('#TB_window').css({
        'z-index': '5000001',
        'height': '480px',
        'width': '780px'
    })
    jQuery('#TB_overlay').css({
        'z-index': '5000000'
    });

});
