<?php

namespace OXI_ACCORDIONS_PLUGINS\Oxilab;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Reviews
 *
 * author @biplob018
 */
class Reviews {

    /**
     * Revoke this function when the object is created.
     *
     */
    public function __construct() {
        if (!current_user_can('manage_options')) {
            return;
        }
        add_action('admin_notices', array($this, 'first_install'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_oxi_accordions_reviews_notice', array($this, 'notice_dissmiss'));
        add_action('admin_notices', array($this, 'dismiss_button_scripts'));
    }

    /**
     * Admin Notice Ajax  loader
     * @return void
     */
    public function notice_dissmiss() {
        if (isset($_POST['_wpnonce']) || wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'oxi_accordions_reviews_notice')):
            $notice = isset($_POST['notice']) ? sanitize_text_field($_POST['notice']) : '';
            if ($notice == 'maybe'):
                $data = strtotime("now");
                update_option('accordions_or_faqs_activation_date', $data);
            else:
                update_option('accordions_or_faqs_no_bug', $notice);
            endif;
            echo esc_html($notice);
        else:
            return;
        endif;

        die();
    }

    /**
     * First Installation Track
     * @return void
     */
    public function first_install() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $image = OXI_ACCORDIONS_URL . 'assets/image/logo.png';
        echo' <div class="notice notice-info put-dismiss-noticenotice-has-thumbnail shortcode-addons-review-notice oxi-accordions-review-notice">
                    <div class="shortcode-addons-notice-thumbnail">
                        <img src="' . esc_url($image) . '" alt=""></div>
                    <div class="shortcode-addons--notice-message">
                        <p>Hey, You’ve using <strong>Accordions - Multiple Accordions or FAQs Builders</strong> more than 1 week – that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.!</p>
                        <ul class="shortcode-addons--notice-link">
                            <li>
                                <a href="https://wordpress.org/plugins/accordions-or-faqs/" target="_blank">
                                    <span class="dashicons dashicons-external"></span>Ok, you deserve it!
                                </a>
                            </li>
                            <li>
                                <a class="oxi-accordions-support-reviews" sup-data="success" href="#">
                                    <span class="dashicons dashicons-smiley"></span>I already did
                                </a>
                            </li>
                            <li>
                                <a class="oxi-accordions-support-reviews" sup-data="maybe" href="#">
                                    <span class="dashicons dashicons-calendar-alt"></span>Maybe Later
                                </a>
                            </li>
                            <li>
                                <a href="https://wordpress.org/support/plugin/accordions-or-faqs/">
                                    <span class="dashicons dashicons-sos"></span>I need help
                                </a>
                            </li>
                            <li>
                                <a class="oxi-accordions-support-reviews" sup-data="never"  href="#">
                                    <span class="dashicons dashicons-dismiss"></span>Never show again
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>';
    }

    /**
     * Admin Notice CSS file loader
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxi-accordions-admin-notice', OXI_ACCORDIONS_URL . '/Oxilab/css/notice.css', false, 'accordions-or-faqs');
        $this->dismiss_button_scripts();
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function dismiss_button_scripts() {
        wp_enqueue_script('oxi-accordions-admin-notice', OXI_ACCORDIONS_URL . '/Oxilab/js/notice.js', false, 'accordions-or-faqs');
        wp_localize_script('oxi-accordions-admin-notice', 'oxi_accordions_reviews_notice', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('oxi_accordions_reviews_notice')));
    }

}
