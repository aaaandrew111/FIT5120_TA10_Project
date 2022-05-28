<?php

namespace OXI_ACCORDIONS_PLUGINS\Includes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Settings
 *
 * author @biplob018
 */
class Settings {

    use \OXI_ACCORDIONS_PLUGINS\Helper\Additional;

    public $roles;
    public $saved_role;
    public $license;
    public $status;

    /**
     * Constructor of Oxilab Accordions Home Page
     *
     * @since 2.0.0
     */
    public function __construct() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $this->admin();
        $this->admin_ajax();
        $this->Render();
    }

    public function admin() {
        global $wp_roles;
        $this->roles = $wp_roles->get_names();
        $this->saved_role = get_option('oxi_accordions_user_permission');
        $this->license = get_option('accordions_or_faqs_license_key');
        $this->status = get_option('accordions_or_faqs_license_status');
    }

    public function Render() {
        ?>
        <div class="wrap">
            <?php
            echo apply_filters('oxi-accordions-plugin/admin_menu', TRUE);
            ?>
            <div class="oxi-addons-row oxi-addons-admin-settings">
                <form method="post">
                    <h2>General</h2>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="oxi_accordions_user_permission">Who Can Edit?</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <select name="oxi_accordions_user_permission" id="oxi_accordions_user_permission">
                                            <?php foreach ($this->roles as $key => $role) { ?>
                                                <option value="<?php echo esc_attr($key); ?>" <?php selected($this->saved_role, $key); ?>><?php echo esc_html($role); ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="oxi-addons-settings-connfirmation oxi_accordions_user_permission"></span>
                                        <br>
                                        <p class="description"><?php _e('Select the Role who can manage This Plugins.'); ?> <a target="_blank" href="https://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table">Help</a></p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_font_awesome">Font Awesome Support</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label for="oxi_addons_font_awesome[yes]">
                                            <input type="radio" class="radio" id="oxi_addons_font_awesome[yes]" name="oxi_addons_font_awesome" value="yes" <?php checked('yes', get_option('oxi_addons_font_awesome'), true); ?>>Yes</label>
                                        <label for="oxi_addons_font_awesome[no]">
                                            <input type="radio" class="radio" id="oxi_addons_font_awesome[no]" name="oxi_addons_font_awesome" value=""  <?php checked('', get_option('oxi_addons_font_awesome'), true); ?>>No
                                        </label>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_font_awesome"></span>
                                        <br>
                                        <p class="description">Load Font Awesome CSS at shortcode loading, If your theme already loaded select No for faster loading</p>
                                    </fieldset>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <br>
                    <br>

                    <h2>Product License</h2>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="accordions_or_faqs_license_key">License Key</label>
                                </th>
                                <td class="valid">
                                    <input type="text" class="regular-text" id="accordions_or_faqs_license_key" name="accordions_or_faqs_license_key" value="<?php echo $this->license; ?>">
                                    <span class="oxi-addons-settings-connfirmation accordions_or_faqs_license_massage">
                                        <?php
                                        if ($this->status == 'valid' && empty($this->license)):
                                            echo _e('<span class="oxi-confirmation-success"></span>');
                                        elseif ($this->status == 'valid' && !empty($this->license)):
                                            echo _e('<span class="oxi-confirmation-success"></span>');
                                        elseif (!empty($this->license)):
                                            echo _e('<span class="oxi-confirmation-failed"></span>');
                                        else:
                                            echo _e('<span class="oxi-confirmation-blank"></span>');
                                        endif;
                                        ?>
                                    </span>
                                    <span class="oxi-addons-settings-connfirmation accordions_or_faqs_license_text">
                                        <?php
                                        if ($this->status == 'valid' && empty($this->license)):
                                            echo _e('<span class="oxi-addons-settings-massage">Pre Active</span>');
                                        elseif ($this->status == 'valid' && !empty($this->license)):
                                            echo _e('<span class="oxi-addons-settings-massage">Active</span>');
                                        elseif (!empty($this->license)):
                                            echo _e('<span class="oxi-addons-settings-massage">' . esc_html($this->status) . '</span>');
                                        else:
                                            echo _e('<span class="oxi-addons-settings-massage"></span>');
                                        endif;
                                        ?>
                                    </span>
                                    <p class="description">Activate your License to get direct plugin updates and official support.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_ajax() {
        $this->admin_settings_additional();
        wp_enqueue_script('oxi-accordions-settings-page', OXI_ACCORDIONS_URL . '/assets/backend/custom/settings.js', false, 'accordions-or-faqs');
    }

}
