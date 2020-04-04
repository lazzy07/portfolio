<div id="mesmerize_homepage" style="display:none; ">
    <div class="mesmerize-popup" style="">
        <div>
            <div class="mesmerize_cp_column">
                <h3 class="mesmerize_title"><?php esc_html_e('Please Install the Mesmerize Companion Plugin to Enable All the Theme Features', 'mesmerize') ?></h3>
                <h4><?php esc_html_e('Here\'s what you\'ll get:', 'mesmerize'); ?></h4>
                <ul class="mesmerize-features-list">
                    <li><?php esc_html_e('Beautiful ready-made homepage', 'mesmerize'); ?></li>
                    <li><?php esc_html_e('Drag and drop page customization', 'mesmerize'); ?></li>
                    <li><?php esc_html_e('25+ predefined content sections', 'mesmerize'); ?></li>
                    <li><?php esc_html_e('Live content editing', 'mesmerize'); ?></li>
                    <li><?php esc_html_e('5 header types', 'mesmerize'); ?></li>
                    <li><?php esc_html_e('3 footer types', 'mesmerize'); ?></li>
                    <li><?php esc_html_e('and many other features', 'mesmerize'); ?></li>
                </ul>
            </div>
            <div class="mesmerize_cp_column">
                <?php if (apply_filters('mesmerize_companion_show_popup_screenshot', true)): ?>
                    <img class="popup-theme-screenshot" src="<?php echo esc_attr(get_template_directory_uri() . "/screenshot.jpg") ?>"/>
                <?php else: ?>
                    <?php do_action('mesmerize_companion_popup_screenshot'); ?>
                <?php endif; ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="footer">
            <label class="disable-popup-cb">
                <input type="checkbox" id="disable-popup-cb"/>
                <?php esc_html_e("Don't show this popup in the future", 'mesmerize'); ?>
            </label>
            <script type="text/javascript">
                jQuery('#disable-popup-cb').click(function () {
                    jQuery.post(
                        ajaxurl,
                        {
                            value: this.checked ? 1 : 0,
                            action: "companion_disable_popup",
                            companion_disable_popup_wpnonce: '<?php echo wp_create_nonce("companion_disable_popup"); ?>'
                        }
                    )
                });
            </script>
            <a class="button button-large button-link mesmerize-popup-cancel" onclick="tb_remove();"> <?php esc_html_e('Maybe later', 'mesmerize') ?> </a>
            <?php
            if (\Mesmerize\Companion_Plugin::$plugin_state['installed']) {
                $link  = \Mesmerize\Companion_Plugin::get_activate_link();
                $label = esc_html__('Activate now', 'mesmerize');
            } else {
                $link  = \Mesmerize\Companion_Plugin::get_install_link();
                $label = esc_html__('Install now', 'mesmerize');
            }
            printf('<a class="install-now button button-large button-orange" href="%1$s">%2$s</a>', esc_url($link), $label);
            ?>
        </div>
    </div>
</div>
