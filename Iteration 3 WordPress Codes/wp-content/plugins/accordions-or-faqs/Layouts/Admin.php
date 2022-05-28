<?php

namespace OXI_ACCORDIONS_PLUGINS\Layouts;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Admin
 *
 * author @biplob018
 */
use OXI_ACCORDIONS_PLUGINS\Helper\Controls as Controls;

class Admin {

    use \OXI_ACCORDIONS_PLUGINS\Helper\Additional;
    use \OXI_ACCORDIONS_PLUGINS\Helper\Sanitization;

    /**
     * Current Elements ID
     *
     * @since 2.0.1
     */
    public $oxiid;

    /**
     * Current Elements Style Data
     *
     * @since 2.0.1
     */
    public $style = [];

    /**
     * Current Elements Style Full
     *
     * @since 2.0.1
     */
    public $dbdata;

    /**
     * Current Elements Child Data
     *
     * @since 2.0.1
     */
    public $child;

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.1
     */
    public $CSSDATA = [];

    /**
     * Current Elements Global DATA WRAPPER
     *
     * @since 2.0.1
     */
    public $WRAPPER;

    /**
     * Current Elements Global DATA WRAPPER
     *
     * @since 2.0.1
     */
    public $CSSWRAPPER;

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $database;

    /**
     * Define Oxilab Tabs Elements Font Family
     *
     * @since 2.0.1
     */
    public $font = [];

    /**
     * Define Oxilab Tabs Imported Font Family
     *
     * @since 2.0.1
     */
    public $font_family = [];

    /**
     * Define Oxilab Tabs  Google Font Family
     *
     * @since 2.0.1
     */
    public $google_font = [];

    /**
     * Define Oxilab Tabs  Elements Type
     *
     * @since 2.0.1
     */
    public $Popover_Condition = true;
    public $Get_Nested_Accordions = [];

    public function __construct($type = '') {
        $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
        $this->oxiid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        $this->WRAPPER = '.oxi-accordions-wrapper-' . $this->oxiid;
        $this->CSSWRAPPER = '.oxi-accordions-wrapper-' . $this->oxiid . ' > .oxi-addons-row';
        if ($type != 'admin') {
            $this->hooks();
            $this->render();
        }
    }

    /**
     * Template Modal opener
     * Define Multiple Data With Single Data
     *
     * @since 2.0.1
     */
    public function modal_opener() {
        $this->add_substitute_control('', [], [
            'type' => Controls::MODALOPENER,
            'title' => esc_html__('Accordions Data Form', 'accordions-or-faqs'),
            'sub-title' => esc_html__('Open Form', 'accordions-or-faqs'),
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Name
     * Define Name
     *
     * @since 2.0.1
     */
    public function shortcode_name() {
        $this->add_substitute_control('', $this->dbdata, [
            'type' => Controls::SHORTCODENAME,
            'title' => esc_html__('Shortcode Name', 'accordions-or-faqs'),
            'placeholder' => esc_html__('Set Your Shortcode Name', 'accordions-or-faqs'),
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Information
     * Parent Sector where users will get Information
     *
     * @since 2.0.1
     */
    public function shortcode_info() {
        $this->add_substitute_control($this->oxiid, $this->dbdata, [
            'type' => Controls::SHORTCODEINFO,
            'title' => esc_html__('Shortcode', 'accordions-or-faqs'),
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Modal Form Data
     * return always false and abstract with current Style Template
     *
     * @since 2.0.1
     */
    public function modal_form_data() {
        $this->form = 'single';
    }

    /**
     * Template Parent Modal Form
     *
     * @since 2.0.1
     */
    public function modal_form() {

        echo '<div class="modal fade" id="oxi-addons-list-data-modal" >
                <div class="modal-dialog modal-lg">
                    <form method="post" id="oxi-template-modal-form">
                         <div class="modal-content">';
        $this->modal_form_data();
        echo '              <div class="modal-footer">
                                <input type="hidden" id="shortcodeitemid" name="shortcodeitemid" value="">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success" id="oxi-template-modal-submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
              </div>';
    }

    /**
     * Template Parent Item Data Rearrange
     *
     * @since 2.0.0
     */
    public function Rearrange() {
        return '<li class="list-group-item" id="{{id}}">{{oxi-accordions-modal-title}}</li>';
    }

    /**
     * Template Parent Item Data Rearrange
     *
     * @since 2.0.1
     */
    public function shortcode_rearrange() {
        $rearrange = $this->Rearrange();
        if (!empty($rearrange)):
            $this->add_substitute_control($rearrange, [], [
                'type' => Controls::REARRANGE,
                'showing' => TRUE,
                'condition' => [
                    'oxi-accordions-content-type' => 'content'
                ],
            ]);
        endif;
    }

    /**
     * Template CSS Render
     *
     * @since 2.0.1
     */
    public function template_css_render($style) {
        $styleid = $style['style-id'];
        $this->oxiid = $styleid;
        $this->WRAPPER = '.oxi-accordions-wrapper-' . $this->oxiid;
        $this->CSSWRAPPER = '.oxi-accordions-wrapper-' . $this->oxiid . ' > .oxi-addons-row';
        $this->style = $style;
        ob_start();
        $dt = $this->import_font_family();
        $dt .= $this->register_controls();
        ob_end_clean();

        $fullcssfile = '';
        foreach ($this->CSSDATA as $key => $responsive) {
            $tempcss = '';
            foreach ($responsive as $class => $classes) {
                $tempcss .= $class . '{';
                foreach ($classes as $properties) {
                    $tempcss .= $properties;
                }
                $tempcss .= '}';
            }
            if ($key == 'laptop'):
                $fullcssfile .= $tempcss;
            elseif ($key == 'tab'):
                $fullcssfile .= '@media only screen and (min-width : 769px) and (max-width : 993px){';
                $fullcssfile .= $tempcss;
                $fullcssfile .= '}';
            elseif ($key == 'mobile'):
                $fullcssfile .= '@media only screen and (max-width : 768px){';
                $fullcssfile .= $tempcss;
                $fullcssfile .= '}';
            endif;
        }
        $font = json_encode($this->font);
        $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET stylesheet = %s WHERE id = %d", $fullcssfile, $styleid));
        $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET font_family = %s WHERE id = %d", $font, $styleid));
        return 'success';
    }

    /**
     * Template CSS Render
     *
     * @since 2.0.1
     */
    public function inline_template_css_render($style) {
        $styleid = $style['style-id'];
        $this->style = $style;
        $this->oxiid = $styleid;
        $this->WRAPPER = '.oxi-accordions-wrapper-' . $this->oxiid;
        $this->CSSWRAPPER = '.oxi-accordions-wrapper-' . $this->oxiid . ' > .oxi-addons-row';

        ob_start();
        $dt = $this->import_font_family();
        $dt = $this->register_controls();
        ob_end_clean();
        $fullcssfile = '';
        foreach ($this->CSSDATA as $key => $responsive) {
            $tempcss = '';
            foreach ($responsive as $class => $classes) {
                $tempcss .= $class . '{';
                foreach ($classes as $properties) {
                    $tempcss .= $properties;
                }
                $tempcss .= '}';
            }
            if ($key == 'laptop'):
                $fullcssfile .= $tempcss;
            elseif ($key == 'tab'):
                $fullcssfile .= '@media only screen and (min-width : 769px) and (max-width : 993px){';
                $fullcssfile .= $tempcss;
                $fullcssfile .= '}';
            elseif ($key == 'mobile'):
                $fullcssfile .= '@media only screen and (max-width : 768px){';
                $fullcssfile .= $tempcss;
                $fullcssfile .= '}';
            endif;
        }

        foreach ($this->font as $value) {
            wp_enqueue_style('' . $value . '', 'https://fonts.googleapis.com/css?family=' . $value . '');
        }
        return $fullcssfile;
    }

    /**
     * Template Parent Render
     *
     * @since 2.0.1
     */
    public function render() {
        ?>
        <div class="wrap">
            <div class="oxi-addons-wrapper">
                <?php
                apply_filters('oxi-accordions-plugin/admin_menu', TRUE);
                ?>
                <div class="oxi-addons-style-20-spacer"></div>
                <div class="oxi-addons-row">
                    <?php
                    apply_filters('oxi-accordions-plugin/support-and-comments', TRUE);
                    ?>
                    <div class="oxi-addons-wrapper oxi-addons-image-tabs-mode">
                        <div class="oxi-addons-settings" id="oxisettingsreload">
                            <div class="oxi-addons-style-left">
                                <form method="post" id="oxi-addons-form-submit">
                                    <div class="oxi-addons-style-settings">
                                        <div class="oxi-addons-tabs-wrapper">
                                            <?php
                                            $this->register_controls();
                                            ?>
                                        </div>
                                        <div class="oxi-addons-setting-save">
                                            <button type="button" class="btn btn-danger" id="oxi-addons-setting-reload">Reload</button>
                                            <input type="hidden"  id="oxilab-preview-color" name="oxilab-preview-color" value="<?php echo is_array($this->style) ? array_key_exists('oxilab-preview-color', $this->style) ? esc_attr($this->style['oxilab-preview-color']) : '#FFF' : '#FFF'; ?>">
                                            <input type="hidden"  id="style-id" name="style-id" value="<?php echo esc_attr($this->dbdata['id']); ?>">
                                            <button type="button" class="btn btn-success" id="oxi-addons-templates-submit"> Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="oxi-addons-style-right">
                                <?php
                                $this->modal_opener();
                                $this->shortcode_name();
                                $this->shortcode_info();
                                $this->shortcode_rearrange();
                                $this->modal_form();
                                ?>
                            </div>
                        </div>
                        <div class="oxi-addons-Preview" id="oxipreviewreload">
                            <div class="oxi-addons-wrapper">
                                <div class="oxi-addons-style-left-preview">
                                    <div class="oxi-addons-style-left-preview-heading">
                                        <div class="oxi-addons-style-left-preview-heading-left oxi-addons-image-tabs-sortable-title">
                                            Preview
                                            <div class="shortcode-form-control-responsive-switchers">
                                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-desktop" data-device="desktop">
                                                    <span class="dashicons dashicons-desktop"></span>
                                                </a>
                                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-tablet" data-device="tablet">
                                                    <span class="dashicons dashicons-tablet"></span>
                                                </a>
                                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-mobile" data-device="mobile">
                                                    <span class="dashicons dashicons-smartphone"></span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="oxi-addons-style-left-preview-heading-right">
                                            <input type="text" data-format="rgb" data-opacity="TRUE" class="oxi-addons-minicolor" id="oxi-addons-2-0-color" name="oxi-addons-2-0-color" value="<?php echo(is_array($this->style) ? array_key_exists('oxilab-preview-color', $this->style) ? $this->style['oxilab-preview-color'] : '#FFF' : '#FFF'); ?>">
                                        </div>
                                    </div>
                                    <div class="oxi-addons-preview-wrapper">
                                        <div class="oxi-addons-preview-data" id="oxi-addons-preview-data" template-wrapper="<?php echo esc_attr($this->WRAPPER); ?> > .oxi-addons-row" template-id="#oxi-<?php echo esc_attr(strtolower($this->dbdata['type'])); ?>-wrapper-<?php echo esc_attr($this->dbdata['id']); ?>">
                                            <iframe  src="<?php echo esc_url(admin_url('admin.php?page=oxi-accordions-style-view&styleid=' . $this->oxiid)); ?>"
                                                     id="oxi-addons-preview-iframe"
                                                     class="oxi-addons-preview-iframe"
                                                     width="100%" scrolling="no"
                                                     frameborder="0"></iframe>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="OXIAADDONSCHANGEDPOPUP" class="modal fade">
                        <div class="modal-dialog modal-confirm  bounceIn ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="icon-box">

                                    </div>
                                </div>
                                <div class="modal-body text-center">
                                    <h4></h4>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }
       /**
     * Template hooks
     *
     * @since 2.0.1
     */
    public function hooks() {
        $this->admin_elements_editior_loader();
        $this->dbdata = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $this->oxiid), ARRAY_A);

        $Get_Nested_Accordions = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT id, name FROM {$this->database->parent_table} WHERE type = %s ORDER by id ASC", 'accordions-or-faqs'), ARRAY_A);
        foreach ($Get_Nested_Accordions as $key => $value) {
            if ($value['id'] != $this->oxiid):
                $this->Get_Nested_Accordions[$value['id']] = !empty($value['name']) ? $value['name'] : 'Accordions id ' . $value['id'];
            endif;
        }

        $this->child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $this->oxiid), ARRAY_A);
        if (!empty($this->dbdata['rawdata'])):
            $s = json_decode(stripslashes($this->dbdata['rawdata']), true);
            if (is_array($s)):
                $this->style = $s;
            endif;
        endif;
        $this->import_font_family();
    }

    public function register_controls() {
        $this->start_section_header(
                'shortcode-addons-start-tabs', [
            'options' => [
                'button-settings' => esc_html__('General Settings', 'accordions-or-faqs'),
                'custom' => esc_html__('Custom CSS', 'accordions-or-faqs'),
            ]
                ]
        );
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-tabs-start-tabs' => esc_html__('button-settings', 'accordions-or-faqs'),
            ]
                ]
        );
        $this->start_section_devider();
        $this->register_general();
        $this->end_section_devider();

        $this->start_section_devider();
        $this->register_heading();
        $this->end_section_devider();
        $this->end_section_tabs();

        $this->start_section_tabs(
                'oxi-tabs-start-tabs', [
            'condition' => [
                'oxi-tabs-start-tabs' => 'custom'
            ],
            'padding' => '10px'
                ]
        );

        $this->start_controls_section(
                'oxi-tabs-start-tabs-css', [
            'label' => esc_html__('Custom CSS', 'accordions-or-faqs'),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-tabs-custom-css', $this->style, [
            'label' => esc_html__('', 'accordions-or-faqs'),
            'type' => Controls::TEXTAREA,
            'default' => '',
            'description' => esc_html__('Custom CSS Section. You can add custom css into textarea.')
                ]
        );
        $this->end_controls_section();
        $this->end_section_tabs();
    }

    public function str_replace_first($from, $to, $content) {
        $from = '/' . preg_quote($from, '/') . '/';
        return preg_replace($from, $to, $content, 1);
    }

    public function thumbnail_sizes() {
        $default_image_sizes = get_intermediate_image_sizes();
        $thumbnail_sizes = array();
        foreach ($default_image_sizes as $size) {
            $image_sizes[$size] = $size . ' - ' . intval(get_option("{$size}_size_w")) . ' x ' . intval(get_option("{$size}_size_h"));
            $thumbnail_sizes[$size] = str_replace('_', ' ', ucfirst($image_sizes[$size]));
        }
        return $thumbnail_sizes;
    }

 

}
