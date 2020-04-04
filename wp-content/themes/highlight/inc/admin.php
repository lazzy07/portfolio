<?php

add_action('admin_init', 'highlight_admin_init_welcome_notice', 0);
add_action('admin_init', 'highlight_admin_init_enqueue_assets');

add_action('wp_ajax_highlight_dismiss_welcome_popup', 'highlight_admin_ajax_welcome_notice_dismiss');

function highlight_admin_init_enqueue_assets()
{
    
    wp_enqueue_style(
        'highlight_admin_init_welcome_notice',
        highlight_get_stylesheet_directory_uri() . "/assets/admin/admin.css"
    );
    wp_enqueue_script(
        'highlight_admin_init_welcome_notice',
        highlight_get_stylesheet_directory_uri() . "/assets/admin/admin.js",
        array('jquery')
    );
}

function highlight_admin_init_welcome_notice()
{
    global $pagenow;
  
    if($pagenow === "update.php"){
        return;
    }
    
    if ( ! get_option('highlight_welcome_notice_dismissed', false)) {
        
        
        if (class_exists("\\Mesmerize\\Companion")) {
            return;
        }
        
        add_action('admin_notices', 'highlight_welcome_notice_render_content', 0);
    }
}

function highlight_welcome_notice_cntent()
{
    ob_start();
    mesmerize_require("/customizer/companion-popup.php");
    $popup = ob_get_clean();
    
    $popup = str_replace("onclick=\"tb_remove();\"", "onclick=\"highlight_close_popup(this);\"", $popup);
    $popup = str_replace("install-now button button-large button-orange", "button button-hero button-primary install-now", $popup);
    $title = __('To enable all the theme features, please install the companion plugin', 'highlight');
    $popup = preg_replace("#<img class=\"popup-theme-screenshot\"(.*?)>#", "<div class='image-scroll'></div>", $popup);
    $popup = preg_replace("#<h3 class=\"mesmerize_title\">(.*?)</h3>#", "<h3 class=\"mesmerize_title\">${title}</h3>", $popup);
    
    return $popup;
}

function highlight_welcome_notice_render_content()
{
    ?>
    <div class="notice is-dismissible highlight-welcome-notice">
        <div class="notice-content-wrapper">
            <?php echo highlight_welcome_notice_cntent(); ?>
        </div>
    </div>
    <?php
}

function highlight_admin_ajax_welcome_notice_dismiss()
{
    update_option('highlight_welcome_notice_dismissed', true);
}
