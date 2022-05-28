<?php

/*
  Plugin Name: Accordions - Multiple Accordions or FAQs Builders
  Plugin URI: https://www.oxilabdemos.com/accordions
  Description: Accordions, Most easiest accordions or faqs builder Plugin. Create multiple accordion or  collapse faqs with this.
  Author: Biplob Adhikari
  Author URI: http://www.oxilab.org/
  Version: 2.2.2
 */
if (!defined('ABSPATH'))
    exit;

define('OXI_ACCORDIONS_FILE', __FILE__);
define('OXI_ACCORDIONS_BASENAME', plugin_basename(__FILE__));
define('OXI_ACCORDIONS_PATH', plugin_dir_path(__FILE__));
define('OXI_ACCORDIONS_URL', plugins_url('/', __FILE__));
define('OXI_ACCORDIONS_PLUGIN_VERSION', '2.2.2');
define('OXI_ACCORDIONS_TEXTDOMAIN', 'accordions-or-faqs');
define('OXI_ACCORDIONS_PREMIUM', 'oxi-accordions-plugin/pro_version');

/**
 * Including composer autoloader globally.
 *
 * @since 2.0.1
 */
require_once OXI_ACCORDIONS_PATH . 'Helper/autoloader.php';

/**
 * Run plugin after all others plugins
 *
 * @since 2.0.1
 */
add_action('plugins_loaded', function () {
    \OXI_ACCORDIONS_PLUGINS\Classes\Bootstrap::instance();
});

/**
 * Activation hook
 *
 * @since 2.0.1
 */
register_activation_hook(__FILE__, function () {
    $Installation = new \OXI_ACCORDIONS_PLUGINS\Helper\Installation();
    $Installation->plugin_activation_hook();
});

/**
 * Upgrade hook
 *
 * @since 2.0.1
 */
add_action('upgrader_process_complete', function ($upgrader_object, $options) {
    $Installation = new \OXI_ACCORDIONS_PLUGINS\Helper\Installation();
    $Installation->plugin_upgrade_hook($upgrader_object, $options);
}, 10, 2);
