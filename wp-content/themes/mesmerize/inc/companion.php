<?php

namespace Mesmerize;

class Companion_Plugin
{
    private static $instance = false;
    private static $slug;

    public static $plugin_state;
    public static $config = array();

    public function __construct($config)
    {
        self::$config = $config;
        self::$slug   = $config['slug'];
        add_action('tgmpa_register', array(__CLASS__, 'tgma_register'));
        add_action('wp_ajax_companion_disable_popup', array(__CLASS__, 'companion_disable_popup'));
    }

    public static function companion_disable_popup()
    {
        $nonce = @$_POST['companion_disable_popup_wpnonce'];

        if (! wp_verify_nonce($nonce, "companion_disable_popup")) {
            die("wrong nonce");
        }

        $value  = intval(@$_POST['value']);
        $option = isset($_POST['option']) ? @$_POST['option'] : "mesmerize_companion_disable_popup";

        update_option($option, $value);
    }

    public static function tgma_register()
    {
        self::$plugin_state = self::get_plugin_state(self::$slug);
    }

    public static function get_plugin_state($plugin_slug)
    {
        $tgmpa     = \TGM_Plugin_Activation::get_instance();
        $installed = $tgmpa->is_plugin_installed($plugin_slug);

        return array(
            'installed' => $installed,
            'active'    => $installed && $tgmpa->is_plugin_active($plugin_slug),
        );
    }

    public static function get_install_link($slug = false)
    {
        if (! $slug) {
            $slug = self::$slug;
        }

        return add_query_arg(
            array(
                'action'   => 'install-plugin',
                'plugin'   => $slug,
                '_wpnonce' => wp_create_nonce('install-plugin_' . $slug),
            ),
            network_admin_url('update.php')
        );
    }

    public static function get_activate_link($slug = false)
    {
        if (! $slug) {
            $slug = self::$slug;
        }
        $tgmpa = \TGM_Plugin_Activation::get_instance();
        $path  = $tgmpa->plugins[$slug]['file_path'];

        return add_query_arg(array(
            'action'        => 'activate',
            'plugin'        => rawurlencode($path),
            'plugin_status' => 'all',
            'paged'         => '1',
            '_wpnonce'      => wp_create_nonce('activate-plugin_' . $path),
        ), network_admin_url('plugins.php'));
    }

    public static function show_companion_popup()
    {

        add_action('admin_enqueue_scripts', array('\Mesmerize\Companion_Plugin', 'thickbox'));
        add_action('customize_controls_print_footer_scripts', array('\Mesmerize\Companion_Plugin', 'output_companion_message'));
    }

    public static function thickbox($hook)
    {
        add_thickbox();
        wp_enqueue_style('dashicons');
        wp_enqueue_style('mesmerize_customizer_css', get_template_directory_uri() . '/customizer/css/companion-install.css');
    
        $is_popup_disabled = get_option("mesmerize_companion_disable_popup", 0);
    
        if (1 === intval($is_popup_disabled)) {
            return;
        }
        
        wp_enqueue_script('mesmerize_customizer_js', get_template_directory_uri() . '/customizer/js/companion-install.js', array('jquery'), false, true);
    
    }

    public static function output_companion_message()
    {
        mesmerize_require("/customizer/companion-popup.php");
    }

    public static function check_companion($wp_customize)
    {
        $plugin_state = self::$plugin_state;

        if (! $plugin_state['installed'] || ! $plugin_state['active']) {
            $wp_customize->add_setting('companion_install', array(
                'default'           => '',
                'sanitize_callback' => 'esc_attr',
            ));


            if (! $plugin_state['installed']) {
                $wp_customize->add_control(
                    new Install_Companion_Control(
                        $wp_customize,
                        'mesmerize_page_content',
                        array(
                            'section'      => 'page_content',
                            'settings'     => 'companion_install',
                            'label'        => self::$config['install_label'],
                            'msg'          => self::$config['install_msg'],
                            'plugin_state' => $plugin_state,
                            'slug'         => self::$slug,
                        )
                    )
                );
            } else {
                $wp_customize->add_control(
                    new Activate_Companion_Control(
                        $wp_customize,
                        'mesmerize_page_content',
                        array(
                            'section'      => 'page_content',
                            'settings'     => 'companion_install',
                            'label'        => self::$config['activate_label'],
                            'msg'          => self::$config['activate_msg'],
                            'plugin_state' => $plugin_state,
                            'slug'         => self::$slug,
                        )
                    )
                );
            }

            Companion_Plugin::show_companion_popup($plugin_state);
        }
    }

    // static functions
    public static function getInstance($config)
    {
        if (! self::$instance) {
            self::$instance = new Companion_Plugin($config);
        }

        return self::$instance;
    }

    public static function init($config)
    {
        Companion_Plugin::getInstance($config);
    }
}
