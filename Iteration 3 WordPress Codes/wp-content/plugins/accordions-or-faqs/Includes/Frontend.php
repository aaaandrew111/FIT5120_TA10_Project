<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Render
 *
 * author @biplob018
 */
class Frontend {

    /**
     * Current Elements ID
     *
     * @since 2.0.1
     */
    public $oxiid;

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $database;

    /**
     * Template constructor.
     */
    public function __construct() {
        $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
        add_action('admin_init', array($this, 'maybe_load_template'));
        add_action('admin_menu', array($this, 'add_dashboard_page'));
        add_action('network_admin_menu', array($this, 'add_dashboard_page'));
    }
     public function allowed_html($rawdata) {
        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'br' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'table' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'button' => array(
                'class' => array(),
                'type' => array(),
                'value' => array(),
            ),
            'thead' => array(),
            'tbody' => array(),
            'tr' => array(),
            'td' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(
                'class' => array(),
            ),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );
        if (is_array($rawdata)):
            return $rawdata = array_map(array($this, 'allowed_html'), $rawdata);
        else:
            return wp_kses($rawdata, $allowed_tags);
        endif;
    }

    public function validate_post($files = '') {
        
        $rawdata = [];
        if (!empty($files)):
            $data = json_decode(stripslashes($files), true);
        endif;
        if (is_array($data)):
            $rawdata = array_map(array($this, 'allowed_html'), $data);
        else:
            $rawdata = $this->allowed_html($files);
        endif;
       
        return $rawdata;
    }

    /**
     * Register page through WordPress's hooks.
     */
    public function add_dashboard_page() {
        add_dashboard_page('', '', 'read', 'oxi-accordions-style-view', '');
    }

    public function maybe_load_template() {
        $this->oxiid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        $page = (isset($_GET['page']) ? $this->validate_post($_GET['page']) : '');
        if ('oxi-accordions-style-view' !== $page || $this->oxiid < 0) {
            return;
        }
        // Don't load the interface if doing an ajax call.
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }
        set_current_screen();
        // Remove an action in the Gutenberg plugin ( not core Gutenberg ) which throws an error.
        remove_action('admin_print_styles', 'gutenberg_block_editor_admin_print_styles');
        $this->load_template();
    }

    private function load_template() {
        $this->enqueue_scripts();
        $this->template_header();
        $this->template_content();
        $this->template_footer();

        exit;
    }

    public function enqueue_scripts() {
        wp_enqueue_style('oxilab-tabs-bootstrap', OXI_ACCORDIONS_URL . 'assets/backend/css/bootstrap.min.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_style('font-awsome.min', OXI_ACCORDIONS_URL . 'assets/frontend/css/font-awsome.min.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_style('oxilab-template-css', OXI_ACCORDIONS_URL . 'assets/backend/css/template.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_script('oxilab-template-js', OXI_ACCORDIONS_URL . 'assets/backend/custom/frontend.js', false, OXI_ACCORDIONS_PLUGIN_VERSION);
    }

    public function template_header() {
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
            <meta name="viewport" content="width=device-width"/>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title><?php esc_html_e('Accordions - Multiple Accordions or FAQs Builders', 'accordions-or-faqs'); ?></title>
            <?php wp_head(); ?>
        </head>
        <body class="shortcode-addons-template-body" id="shortcode-addons-template-body">
            <?php
        }

        /**
         * Outputs the content of the current step.
         */
        public function template_content() {
            if ($this->oxiid > 0):
                $CLASS = '\OXI_ACCORDIONS_PLUGINS\Includes\Shortcode';
                if (class_exists($CLASS)):
                    new $CLASS($this->oxiid, 'admin');
                endif;
            endif;
        }

        /**
         * Outputs the simplified footer.
         */
        public function template_footer() {
            ?>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    }

}
