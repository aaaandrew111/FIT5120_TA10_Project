<?php

namespace OXI_ACCORDIONS_PLUGINS\Oxilab;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Support
 *
 * author @biplob018
 */
class Recommend {

    const GET_LOCAL_PLUGINS = 'get_all_oxilab_plugins';
    const PLUGINS = 'https://www.oxilab.org/wp-json/oxilabplugins/v2/all_plugins';

    public $get_plugins = [];
    public $current_plugins = 'accordions-or-faqs/index.php';

    /**
     * Revoke this function when the object is created.
     *
     */
    public function __construct() {

        if (!current_user_can('manage_options')) {
            return;
        }
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }
        $this->extension();
        add_action('admin_notices', array($this, 'install_plugins'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_oxi_accordions_admin_recommended', array($this, 'notice_dissmiss'));
        add_action('admin_notices', array($this, 'dismiss_button_scripts'));
    }

    public function extension() {
        $response = get_transient(self::GET_LOCAL_PLUGINS);
        if (!$response || !is_array($response)) {
            $URL = self::PLUGINS;
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::GET_LOCAL_PLUGINS, $response, 10 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        $this->get_plugins = $response;
    }

    /**
     * First Installation Track
     * @return void
     */
    public function install_plugins() {
        $installed_plugins = get_plugins();

        $plugin = [];
        $i = 1;

        foreach ($this->get_plugins as $key => $value) {
            if (!isset($installed_plugins[$value['modules-path']])):
                $plugin[$i] = $value;
                $i++;
            endif;
        }

        $recommend = [];

        for ($p = 1; $p < 100; $p++):
            if (isset($plugin[$p])):
                if (isset($plugin[$p]['dependency']) && $plugin[$p]['dependency'] != ''):
                    if (isset($installed_plugins[$plugin[$p]['dependency']])):
                        $recommend = $plugin[$p];
                        $p = 100;
                    endif;
                elseif ($plugin[$p]['modules-path'] != $this->current_plugins):
                    $recommend = $plugin[$p];
                    $p = 100;
                endif;
            else:
                $p = 100;
            endif;
        endfor;

        if (count($recommend) > 2 && $recommend['modules-path'] != ''):
            $plugin = explode('/', $recommend['modules-path'])[0];

            $massage = sprintf('<p>Thank you for using my Accordions - Multiple Accordions or FAQs Builders. %s</p>', $recommend['modules-massage']);

            $install_url = wp_nonce_url(add_query_arg(array('action' => 'install-plugin', 'plugin' => $plugin), admin_url('update.php')), 'install-plugin' . '_' . $plugin);
            echo'<div class="oxi-addons-admin-notifications oxi-accordions-admin-notifications">
                        <h3>
                            <span class="dashicons dashicons-flag"></span>
                            Notifications
                        </h3>
                        <p></p>
                        <div class="oxi-addons-admin-notifications-holder">
                            <div class="oxi-addons-admin-notifications-alert">
                                ' . $massage . '
                                <p>' . sprintf('<a href="%s" class="button button-large button-primary">%s</a>', esc_url($install_url), esc_html__('Install Now', 'accordions-or-faqs')) . ' &nbsp;&nbsp;<a href="#" class="button button-large button-secondary oxi-plugins-admin-recommended-dismiss" sup-data="done">No, Thanks</a></p>
                            </div>
                        </div>
                        <p></p>
                    </div>';
        endif;
    }

    /**
     * Admin Notice CSS file loader
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxilab_accorions-admin-notice-css', OXI_ACCORDIONS_URL . '/Oxilab/css/notice.css', false, 'accordions-or-faqs');
        $this->dismiss_button_scripts();
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function dismiss_button_scripts() {
        wp_enqueue_script('oxi-accordions-admin-recommend', OXI_ACCORDIONS_URL . '/Oxilab/js/recommend.js', false, 'accordions-or-faqs');
        wp_localize_script('oxi-accordions-admin-recommend', 'oxi_accordions_admin_recommended', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('oxi_accordions_admin_recommended')));
    }

    /**
     * Admin Notice Ajax  loader
     * @return void
     */
    public function notice_dissmiss() {
        if (isset($_POST['_wpnonce']) || wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'oxi_accordions_admin_recommended')):
            $data = 'done';
            update_option('accordions_or_faqs_recommended', $data);
            echo esc_html('done');
        else:
            return;
        endif;
        die();
    }

}
