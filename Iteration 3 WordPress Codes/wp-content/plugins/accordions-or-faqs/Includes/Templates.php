<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Templates
 *
 * author @biplob018
 */
class Templates {

    public $local_template;

    use \OXI_ACCORDIONS_PLUGINS\Helper\Helper;
    use \OXI_ACCORDIONS_PLUGINS\Helper\Additional;

    public $imported = '';
    public $totalpage = 1;
    public $TEMPLATE;

    /**
     * Constructor of Oxilab Accordions Home Page
     *
     * @since 2.0.0
     */
    public function __construct() {
        $this->css_js_load();
        $this->render_html();
    }

    public function css_js_load() {
        $this->admin_template_additional();
        apply_filters('oxi-accordions-plugin/admin_menu', TRUE);

        $this->imported = isset($_GET['layouts']) ? $this->validate_post($_GET['layouts']) : 0;
        $this->get_local_tempalte();
    }

    public function get_local_tempalte() {
        $basename = array_map('basename', glob(OXI_ACCORDIONS_PATH . 'demo-template/' . '*.json', GLOB_BRACE));
        $this->totalpage = ceil(count($basename) / 10);
        $c = $this->imported * 10;
        foreach ($basename as $key => $value) {
            $onlyname = explode('faqs-template-', str_replace('.json', '', $value))[1];
            $count = ((int) $onlyname - $c);
            if ((int) $onlyname && $count > 0 && $count < 11):
                $this->local_template[$onlyname] = $value;
            endif;
        }
        ksort($this->local_template);
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function render_html() {
        ?>
        <div class="oxi-addons-row">
            <?php
            $this->template_header();
            $this->template_body();
            ?>
        </div>
        <?php
    }

    public function template_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Oxilab Accordions › Create New</h1>
                <p> Select Accordions layouts, give your Accordions name and create new Accordions. </p>
            </div>
        </div>
        <?php
    }

    public function template_body() {
        ?>
        <div class="oxi-addons-row">
            <?php
            foreach ($this->local_template as $key => $value) {
                $template_data = json_decode(file_get_contents($this->safe_path(OXI_ACCORDIONS_PATH . 'demo-template/') . $value), true);
                $Cls = 'OXI_ACCORDIONS_PLUGINS\Layouts\Template';
                ?>
                <div class="oxi-addons-col-1">
                    <div class="oxi-addons-style-preview">
                        <div class="oxi-addons-style-preview-top">
                            <?php
                            if (class_exists($Cls) && isset($template_data['style']['rawdata'])):
                                new $Cls($template_data['style'], $template_data['child']);
                            endif;
                            ?>
                        </div>
                        <div class="oxi-addons-style-preview-bottom">
                            <div class="oxi-addons-style-preview-bottom-left">
                                <?php echo esc_html($template_data['style']['name']); ?>
                            </div>
                            <div class="oxi-addons-style-preview-bottom-right">
                                <?php
                                if ($key > 10 && apply_filters(OXI_ACCORDIONS_PREMIUM, true) == false):
                                    echo'<button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Pro Only"  type="submit" value="pro only" name="addonsstyleproonly">Pro Only</button>  ';
                                else:
                                    echo '<button type="button" class="btn btn-success oxi-addons-addons-template-create oxi-addons-addons-js-create" data-toggle="modal" template-id="' . esc_attr($value) . '">Create Style</button>';
                                endif;
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        $this->create_new_modal();
    }

    public function create_new_modal() {
        //  echo '';
        if (($this->imported + 1) < $this->totalpage):
            echo '<div class="oxi-addons-row">
                        <div class="oxi-addons-col-1 oxi-import">
                            <div class="oxi-addons-style-preview">
                                <div class="oxilab-admin-style-preview-top">
                                     <a href="' . esc_url(admin_url("admin.php?page=oxi-accordions-ultimate-new&layouts=" . ($this->imported + 1))) . '">
                                        <div class="oxilab-admin-add-new-item">
                                            <span>
                                                <i class="fas fa-arrow-right oxi-icons"></i>
                                                More Templates
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>';
        endif;

        echo '<div class="modal fade" id="oxi-addons-style-create-modal" >
                        <form method="post" id="oxi-addons-style-modal-form">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">New Accordions</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class=" form-group row">
                                            <label for="addons-style-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Give your Shortcode Name Here">Name</label>
                                            <div class="col-sm-6 addons-dtm-laptop-lock">
                                                <input class="form-control" type="text" value="" id="addons-style-name"  name="addons-style-name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="template-id" name="template-id" value="">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="addonsdatasubmit" id="addonsdatasubmit" value="Save">
                                      </div>
                                </div>
                            </div>
                        </form>
                    </div>';
    }

}
