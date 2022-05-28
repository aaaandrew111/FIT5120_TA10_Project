<?php

namespace OXI_ACCORDIONS_PLUGINS\Classes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Bootstrap
 *
 * author @biplob018
 */
class Bootstrap {

    use \OXI_ACCORDIONS_PLUGINS\Helper\Helper;

    // instance container
    private static $instance = null;

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct() {

        do_action('oxi-accordions-plugin/before_init');
        // Load translation
        add_action('init', array($this, 'i18n'));
        new \OXI_ACCORDIONS_PLUGINS\Classes\API();
        $this->load_shortcode();

        if (is_admin()) {
            $this->User_Admin();
            $this->User_Reviews();
            if (isset($_GET['page']) && 'oxi-accordions-style-view' === sanitize_text_field($_GET['page'])) {
                new \OXI_ACCORDIONS_PLUGINS\Includes\Frontend();
            }
        }
    }

    /**
     * Load Textdomain
     *
     * @since 2.0.1
     * @access public
     */
    public function i18n() {
        load_plugin_textdomain('oxi-accordions-plugin');
    }

    public function load_shortcode() {
        add_shortcode('oxi_accordions', [$this, 'accordions_shortcode']);
        $Widget = new \OXI_ACCORDIONS_PLUGINS\Includes\Widget();
        add_filter('widget_text', 'do_shortcode');
        add_action('widgets_init', array($Widget, 'register_accordions_widget'));
    }

    public function User_Admin() {
        add_filter('oxi-accordions-plugin/support-and-comments', array($this, $this->fixed_data('737570706f7274616e64636f6d6d656e7473')));
        add_filter('oxi-accordions-plugin/pro_version', array($this, $this->fixed_data('636865636b5f63757272656e745f6163636f7264696f6e73')));
        add_filter('oxi-accordions-plugin/admin_menu', array($this, $this->fixed_data('6f78696c61625f61646d696e5f6d656e75')));
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_head', [$this, 'admin_icon']);
        add_action('admin_init', array($this, 'redirect_on_activation'));
    }

}
