<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Shortcode
 *
 * author @biplob018
 */
class Shortcode {

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $database;

    /**
     * Style ID
     *
     * @since 2.0.1
     */
    public $styleid;

    /**
     * Database Parent Table
     *
     * @since 2.0.1
     */
    public $style_table;

    /**
     * Database Import Table
     *
     * @since 2.0.1
     */
    public $child_table;

    /**
     * Database Import Table
     *
     * @since 2.0.1
     */
    public $import_table;
    public $define_user;

    /**
     * Constructor
     *
     * @since 2.0.0
     */
    public function __construct($styleid = '', $user = 'user') {
        if (!empty((int) $styleid)):
            $this->styleid = $styleid;
            $this->define_user = $user;
            $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
            $this->get_data();
        endif;
    }

    /**
     * Get Data From Database
     *
     * @since 2.0.1
     */
    public function get_data() {
        //style Data
        $this->style_table = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $this->styleid), ARRAY_A);

        if (!is_array($this->style_table)):
            return;
        endif;
        //Trasient
        $this->get_transient();
        //Child Data
        $this->child_table = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $this->styleid), ARRAY_A);

        $this->render_html();
    }

    /**
     * Confirm Transient
     *
     * @since 2.0.1
     */
    public function get_transient() {
        if ($this->define_user == 'admin'):
            $response = get_transient('accordions-or-faqs-template-' . $this->styleid);
            if ($response):
                $new = [
                    'rawdata' => $response,
                    'stylesheet' => '',
                    'font_family' => ''
                ];
                $this->style_table = array_merge($this->style_table, $new);
            endif;
        endif;
    }

    public function render_html() {
        $CLASS = '\OXI_ACCORDIONS_PLUGINS\Layouts\Template';
        if (class_exists($CLASS)):
            new $CLASS($this->style_table, $this->child_table, $this->define_user);
        endif;
    }

}
