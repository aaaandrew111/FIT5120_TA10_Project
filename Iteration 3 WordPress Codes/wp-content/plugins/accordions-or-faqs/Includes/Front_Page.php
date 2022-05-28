<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Index
 *
 * author @biplob018
 */
class Front_Page {

    use \OXI_ACCORDIONS_PLUGINS\Helper\Additional;

    /**
     * Database
     *
     * @since 2.0.1
     */
    public $database;

    public function __construct() {
        $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database;
        $this->additional_load();
        $this->public_render();
    }

    public function database_data() {
        return $this->database->wpdb->get_results($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE type = %s ', 'accordions-or-faqs'), ARRAY_A);
    }

    public function additional_load() {
        $this->database_data();
        $this->admin_front_additional();
        $this->manual_import_json();
        apply_filters('oxi-accordions-plugin/admin_menu', TRUE);
    }

    /**
     * Generate safe path
     * @since v2.0.1
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function manual_import_json() {
        if (!empty($_REQUEST['_wpnonce'])) {
            $nonce = $_REQUEST['_wpnonce'];
        }

        if (!empty($_POST['importdatasubmit']) && sanitize_text_field($_POST['importdatasubmit']) == 'Save') {
            if (!wp_verify_nonce($nonce, 'oxi-accordions-ultimate-import')) {
                die('You do not have sufficient permissions to access this page.');
            } else {
                if (isset($_FILES['importaccordionsfile'])) :

                    if (!current_user_can('upload_files')):
                        wp_die(esc_html('You do not have permission to upload files.'));
                    endif;

                    $allowedMimes = array(
                        'json' => 'text/plain'
                    );

                    $fileInfo = wp_check_filetype(basename($_FILES['importaccordionsfile']['name']), $allowedMimes);
                    if (empty($fileInfo['ext'])) {
                        wp_die(esc_html('You do not have permission to upload files.'));
                    }

                    $content = json_decode(file_get_contents($_FILES['importaccordionsfile']['tmp_name']), true);

                    if (empty($content)) {
                        return new \WP_Error('file_error', 'Invalid File');
                    }
                    $style = $content['style'];

                    if (!is_array($style) || $style['type'] != 'accordions-or-faqs') {
                        return new \WP_Error('file_error', 'Invalid Content In File');
                    }

                    $ImportApi = new \OXI_ACCORDIONS_PLUGINS\Classes\API();
                    $new_slug = $ImportApi->post_json_import($content);
                    echo '<script type="text/javascript"> document.location.href = "' . $new_slug . '"; </script>';
                    exit;
                endif;
            }
        }
    }

    public function public_render() {
        ?>
        <div class="oxi-addons-row">
            <?php
            $this->admin_header();
            $this->created_shortcode();
            $this->create_new();
            ?>
        </div>
        <?php
    }

    public function admin_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Oxilab Accordions › Home
                </h1>
                <p> Collect Accordions Shortcode, Edit, Delect, Clone or Export it. </p>
            </div>
        </div>
        <?php
    }

    /**
     * Plugin Name Convert to View
     *
     * @since 2.0.1
     */
    public function name_converter($data) {
        $data = str_replace('tyle', 'tyle ', $data);
        return ucwords($data);
    }

    public function create_new() {
        echo '<div class="oxi-addons-row">
                        <div class="oxi-addons-col-1 oxi-import">
                            <div class="oxi-addons-style-preview">
                                <div class="oxilab-admin-style-preview-top">
                                    <a href="#" id="oxilab-accordions-import-json">
                                        <div class="oxilab-admin-add-new-item">
                                            <span>
                                                <i class="fas fa-plus-circle oxi-icons"></i>
                                                Import Accordions
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>';

        echo '<div class="modal fade" id="oxi-addons-style-create-modal" >
                        <form method="post" id="oxi-addons-style-modal-form">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Tabs Clone</h4>
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

                                        <input type="hidden" id="oxistyleid" name="oxistyleid" value="">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="addonsdatasubmit" id="addonsdatasubmit" value="Save">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    ';
        echo '<div class="modal fade" id="oxi-addons-style-import-modal" >
                        <form method="post" id="oxi-addons-import-modal-form" enctype="multipart/form-data">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Import Form</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input class="form-control" type="file" name="importaccordionsfile" accept=".json,application/json,.zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="importdatasubmit" id="importdatasubmit" value="Save">
                                    </div>
                                </div>
                            </div>
                               ' . wp_nonce_field("oxi-accordions-ultimate-import") . '
                        </form>
                    </div>';
    }

    public function created_shortcode() {
        $return = ' <div class="oxi-addons-row"> <div class="oxi-addons-row table-responsive abop" style="margin-bottom: 20px; opacity: 0; height: 0px">
                        <table class="table table-hover widefat oxi_addons_table_data" style="background-color: #fff; border: 1px solid #ccc">
                            <thead>
                                <tr>
                                    <th style="width: 15%">ID</th>
                                    <th style="width: 25%">Name</th>
                                    <th style="width: 35%">Shortcode</th>
                                    <th style="width: 25%">Edit Delete</th>
                                </tr>
                            </thead>
                            <tbody>';
        foreach ($this->database_data() as $value) {
            $id = $value['id'];
            $return .= _('<tr>');
            $return .= _('<td>' . esc_html($id) . '</td>');
            $return .= _('<td>' . esc_html(ucwords($value['name'])) . '</td>');
            $return .= _('<td><span>Shortcode &nbsp;&nbsp;<input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="[oxi_accordions id=&quot;' . esc_attr($id) . '&quot;]"></span> <br>'
                    . '<span>Php Code &nbsp;&nbsp; <input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="&lt;?php echo do_shortcode(&#039;[oxi_accordions id=&quot;' . esc_attr($id) . '&quot;]&#039;); ?&gt;"></span></td>');
            $return .= _('<td>
                       <a href="' . esc_url(admin_url("admin.php?page=oxi-accordions-ultimate-new&styleid=" . esc_attr($id) . "")) . '"  title="Edit"  class="btn btn-info" style="float:left; margin-right: 5px; margin-left: 5px;">Edit</a>
                       <form method="post" class="oxi-addons-style-delete">
                               <input type="hidden" name="oxideleteid" id="oxideleteid" value="' . esc_attr($id) . '">
                               <button class="btn btn-danger" style="float:left"  title="Delete"  type="submit" value="delete" name="addonsdatadelete">Delete</button>
                       </form>
                       <a href="' . esc_url_raw(rest_url()) . 'oxiaccordionsultimate/v1/shortcode_export?styleid=' . $id . '&_wpnonce=' . wp_create_nonce('wp_rest') . '"  title="Export"  class="btn btn-info" style="float:left; margin-right: 5px; margin-left: 5px;">Export</a>
                </td>');
            $return .= _(' </tr>');
        }
        $return .= _('      </tbody>
                </table>
            </div>
            <br>
            <br></div>');
        echo $return;
    }

}
