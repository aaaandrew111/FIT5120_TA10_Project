<?php

namespace OXI_ACCORDIONS_PLUGINS\Classes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of API
 *
 * author @biplob018
 */
class API {

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $database;
    public $request;
    public $styleid;
    public $childid;

    /**
     * Constructor of plugin class
     *
     * @since 2.0.1
     */
    public function __construct() {
        $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
        $this->build_api();
    }

    public function build_api() {
        add_action('rest_api_init', function () {
            register_rest_route(untrailingslashit('oxiaccordionsultimate/v1/'), '/(?P<action>\w+)/', array(
                'methods' => array('GET', 'POST'),
                'callback' => [$this, 'api_action'],
                'permission_callback' => array($this, 'get_permissions_check'),
            ));
        });
    }

    public function get_permissions_check($request) {
        $user_role = get_option('oxi_accordions_user_permission');
        $role_object = get_role($user_role);
        $first_key = '';
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
            reset($role_object->capabilities);
            $first_key = key($role_object->capabilities);
        } else {
            $first_key = 'manage_options';
        }
        return current_user_can($first_key);
    }

    public function api_action($request) {
        $this->request = $request;
        $wpnonce = $request['_wpnonce'];
        if (!wp_verify_nonce($wpnonce, 'wp_rest')):
            return new \WP_REST_Request('Invalid URL', 422);
        endif;

        $this->rawdata = addslashes($request['rawdata']);
        $this->styleid = (int) $request['styleid'];
        $this->childid = (int) $request['childid'];

        $action_class = strtolower($request->get_method()) . '_' . sanitize_key($request['action']);
        if (method_exists($this, $action_class)):
            return $this->{$action_class}();
        else:
            return die(esc_html__('Security check', 'accordions-or-faqs'));
        endif;
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

    public function validate_post($data = '') {
        $rawdata = [];
        if (!empty($data)):
            $arrfiles = json_decode(stripslashes($data), true);
        else:
            $data = $this->rawdata;
            $arrfiles = json_decode(stripslashes($this->rawdata), true);
        endif;
        if (is_array($arrfiles)):
            $rawdata = array_map(array($this, 'allowed_html'), $arrfiles);
        else:
            $rawdata = $this->allowed_html($data);
        endif;
        return $rawdata;
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function array_replace($arr = [], $search = '', $replace = '') {
        array_walk($arr, function (&$v) use ($search, $replace) {
            $v = str_replace($search, $replace, $v);
        });
        return $arr;
    }

    public function post_create_new_accordions() {
        $params = $this->validate_post();
        $folder = $this->safe_path(OXI_ACCORDIONS_PATH . 'demo-template/');
        $filename = sanitize_text_field($params['template-id']);
        $name = sanitize_text_field($params['addons-style-name']);
        $data = json_decode(file_get_contents($folder . $filename), true);

        if (empty($data)) {
            return new \WP_Error('file_error', 'Invalid File');
        }

        $content = $data['style'];

        if (!is_array($content) || $content['type'] != 'accordions-or-faqs') {
            return new \WP_Error('file_error', 'Invalid Content In File');
        }

        return $this->post_json_import($data, $name);
    }

    public function post_json_import($params, $name = 'truee') {

        if (!is_array($params) || $params['style']['type'] != 'accordions-or-faqs') {
            return new \WP_Error('file_error', 'Invalid Content In File');
        }
        $style = $params['style'];
        $child = $params['child'];
        if ($name != 'truee'):
            $style['name'] = $name;
        endif;
        $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, type, rawdata) VALUES ( %s, %s, %s)", array($style['name'], 'accordions-or-faqs', $style['rawdata'])));
        $redirect_id = $this->database->wpdb->insert_id;

        if ($redirect_id > 0):
            $raw = json_decode(stripslashes($style['rawdata']), true);
            $raw['style-id'] = $redirect_id;
            $CLASS = '\OXI_ACCORDIONS_PLUGINS\Layouts\Helper';
            $CLASS = new $CLASS('admin');
            $CLASS->template_css_render($raw);
            foreach ($child as $value) {
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d,  %s)", array($redirect_id, $value['rawdata'])));
            }
            return admin_url("admin.php?page=oxi-accordions-ultimate-new&styleid=$redirect_id");
        endif;
    }

    public function post_shortcode_delete() {
        $styleid = (int) $this->styleid;
        if ($styleid):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->parent_table} WHERE id = %d", $styleid));
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE styleid = %d", $styleid));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    public function post_shortcode_deactive() {
        $params = $this->validate_post();
        $id = (int) $params['oxideletestyle'];
        if ($id > 0):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->import_table} WHERE name = %s and type = %s", $id, 'accordions-or-faqs'));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    public function post_shortcode_active() {
        $params = $this->validate_post();
        $id = (int) $params['oxiimportstyle'];
        if ($id > 0):
            $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->import_table} (type, name) VALUES (%s, %s)", array('accordions-or-faqs', $id)));
            return admin_url("admin.php?page=oxi-accordions-ultimate-new#Template_" . $id);
        else:
            return 'Silence is Golden';
        endif;
    }

    public function get_shortcode_export() {
        $styleid = (int) $this->styleid;
        if ($styleid):
            $style = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->parent_table} WHERE id = %d", $styleid), ARRAY_A);
            $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
            $filename = 'accordions-or-faqs-template-' . $styleid . '.json';
            $files = [
                'style' => $style,
                'child' => $child,
            ];
            $finalfiles = json_encode($files);
            $this->send_file_headers($filename, strlen($finalfiles));
            @ob_end_clean();
            flush();
            echo $finalfiles;
            die;
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Send file headers.
     *
     *
     * @param string $file_name File name.
     * @param int    $file_size File size.
     */
    private function send_file_headers($file_name, $file_size) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file_size);
    }

    /**
     * Template Style Data
     *
     * @since 2.0.1
     */
    public function post_elements_template_style() {
        $settings = json_decode(stripslashes($this->rawdata), true);
        $stylesheet = '';
        if ((int) $this->styleid):
            $transient = 'accordions-or-faqs-template-' . $this->styleid;
            delete_transient($transient);
            $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET rawdata = %s, stylesheet = %s WHERE id = %d", $this->rawdata, $stylesheet, $this->styleid));
            $CLASS = '\OXI_ACCORDIONS_PLUGINS\Layouts\Helper';
            $CLASS = new $CLASS('admin');
            return $CLASS->template_css_render($settings);
        endif;
    }

    /**
     * Template Name Change
     *
     * @since 2.0.1
     */
    public function post_template_name() {
        $settings = $this->validate_post();
        $name = sanitize_text_field($settings['addonsstylename']);
        $id = $settings['addonsstylenameid'];
        if ((int) $id):
            $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET name = %s WHERE id = %d", $name, $id));
            return 'success';
        endif;
        return 'Silence is Golden';
    }

    /**
     * Template Name Change
     *
     * @since 2.0.1
     */
    public function post_elements_rearrange_modal_data() {
        if ((int) $this->styleid):
            $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $this->styleid), ARRAY_A);
            $render = [];
            foreach ($child as $k => $value) {
                $data = json_decode(stripcslashes($value['rawdata']));
                $render[$value['id']] = $data;
            }
            return json_encode($render);
        endif;
        return 'Silence is Golden';
    }

    /**
     * Template Name Change
     *
     * @since 2.0.1
     */
    public function post_elements_template_rearrange_save_data() {
        $params = explode(',', $this->validate_post());
        foreach ($params as $value) {
            if ((int) $value):
                $data = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE id = %d ", $value), ARRAY_A);
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s)", array($data['styleid'], $data['rawdata'])));
                $redirect_id = $this->database->wpdb->insert_id;
                if ($redirect_id == 0) {
                    return;
                }
                if ($redirect_id != 0) {
                    $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE id = %d", $value));
                }
            endif;
        }
        return 'success';
    }

    /**
     * Template Modal Data
     *
     * @since 2.0.1
     */
    public function post_elements_template_modal_data() {
        if ((int) $this->styleid):
            if ((int) $this->childid):
                $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->child_table} SET rawdata = %s WHERE id = %d", $this->rawdata, $this->childid));
            else:
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s )", array($this->styleid, $this->rawdata)));
            endif;
        endif;
        return 'ok';
    }

    /**
     * Template Template Render
     *
     * @since 2.0.1
     */
    public function post_elements_template_render_data() {
        $transient = 'accordions-or-faqs-template-' . $this->styleid;
        set_transient($transient, $this->rawdata, 1 * HOUR_IN_SECONDS);
        return 'Transient Done';
    }

    /**
     * Template Modal Data Edit Form
     *
     * @since 2.0.1
     */
    public function post_elements_template_modal_data_edit() {
        if ((int) $this->childid):
            $listdata = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE id = %d ", $this->childid), ARRAY_A);
            $returnfile = json_decode(stripslashes($listdata['rawdata']), true);
            $returnfile['shortcodeitemid'] = $this->childid;
            return json_encode($returnfile);
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Template Child Delete Data
     *
     * @since 2.0.1
     */
    public function post_elements_template_modal_data_delete() {
        if ((int) $this->childid):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE id = %d ", $this->childid));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Admin Notice API  loader
     * @return void
     */
    public function post_oxi_recommended() {
        $data = 'done';
        update_option('accordions-or-faqs-recommended', $data);
        return $data;
    }

    /**
     * Admin Notice Recommended  loader
     * @return void
     */
    public function post_notice_dissmiss() {
        $notice = sanitize_text_field($this->request['notice']);
        if ($notice == 'maybe'):
            $data = strtotime("now");
            update_option('accordions-or-faqs-activation-date', $data);
        else:
            update_option('accordions-or-faqs-activation-notice', $notice);
        endif;
        return $notice;
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_user_permission() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = $this->validate_post();
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_accordions_user_permission', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_font_awesome() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = $this->validate_post();
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_font_awesome', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin License
     * @return void
     */
    public function post_oxi_license() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = $this->validate_post();
        $new = sanitize_text_field($rawdata['license']);
        $old = get_option('accordions_or_faqs_license_key');
        $status = get_option('accordions_or_faqs_license_status');
        if ($new == ''):
            if ($old != '' && $status == 'valid'):
                $this->deactivate_license($old);
            endif;
            delete_option('accordions_or_faqs_license_key');
            $data = ['massage' => '<span class="oxi-confirmation-blank"></span>', 'text' => ''];
        else:
            update_option('accordions_or_faqs_license_key', $new);
            delete_option('accordions_or_faqs_license_status');
            $r = $this->activate_license($new);
            if ($r == 'success'):
                $data = ['massage' => '<span class="oxi-confirmation-success"></span>', 'text' => 'Active'];
            else:
                $data = ['massage' => '<span class="oxi-confirmation-failed"></span>', 'text' => $r];
            endif;
        endif;
        return $data;
    }

    public function deactivate_license($key) {
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $key,
            'item_name' => urlencode('Accordions - Multiple Accordions or FAQs Builders'),
            'url' => home_url()
        );
        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = esc_html('An error occurred, please try again.');
            }
            return $message;
        }
        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ($license_data->license == 'deactivated') {
            delete_option('accordions_or_faqs_license_status');
            delete_option('accordions_or_faqs_license_key');
        }
        return 'success';
    }

    public function activate_license($key) {
        $api_params = array(
            'edd_action' => 'activate_license',
            'license' => $key,
            'item_name' => urlencode('Accordions - Multiple Accordions or FAQs Builders'),
            'url' => home_url()
        );

        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = esc_html('An error occurred, please try again.');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {

                switch ($license_data->error) {

                    case 'expired' :

                        $message = sprintf(
                                'Your license key expired on %s.', date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;

                    case 'revoked' :

                        $message = esc_html('Your license key has been disabled.');
                        break;

                    case 'missing' :

                        $message = esc_html('Invalid license.');
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = esc_html('Your license is not active for this URL.');
                        break;

                    case 'item_name_mismatch' :

                        $message = sprintf(esc_html('This appears to be an invalid license key for %s.'), 'accordions-or-faqs');
                        break;

                    case 'no_activations_left':

                        $message = esc_html('Your license key has reached its activation limit.');
                        break;

                    default :

                        $message = esc_html('An error occurred, please try again.');
                        break;
                }
            }
        }

        if (!empty($message)) {
            return $message;
        }
        update_option('accordions_or_faqs_license_status', $license_data->license);
        return 'success';
    }

}
