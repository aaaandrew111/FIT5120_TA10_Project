<?php

namespace OXI_ACCORDIONS_PLUGINS\Layouts;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Template
 *
 * author @biplob018
 */
class Template {

    /**
     * Current Elements id
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
    public $dbdata = [];

    /**
     * Current Elements multiple list data
     *
     * @since 2.0.1
     */
    public $child = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.1
     */
    public $CSSDATA = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.1
     */
    public $inline_css;

    /**
     * Current Elements Global JS Handle
     *
     * @since 2.0.1
     */
    public $JSHANDLE = 'oxi-accordions-ultimate';

    /**
     * Current Elements Global DATA WRAPPER
     *
     * @since 2.0.1
     */
    public $WRAPPER;

    /**
     * Current Elements Admin Control
     *
     * @since 2.0.1
     */
    public $admin;

    /**
     * load constructor
     *
     * @since 2.0.1
     */

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $database;

    /**
     * Public Attribute
     *
     * @since 2.0.1
     */
    public $attribute;

    /**
     * Public Accordions Preloader
     *
     * @since 2.0.1
     */
    public $accordions_preloader;

    /**
     * Define Accordions Type as Toggle or Accordions
     *
     * @since 2.0.1
     */
    public $accordions_type;
    public $public_attribute;

    /**
     * Public arg
     *
     * @since 2.0.1
     */
    public $arg;

    /**
     * Public keys
     *
     * @since 2.0.1
     */
    public $keys;

    public function __construct(array $dbdata = [], array $child = [], $admin = 'user', array $arg = [], array $keys = []) {
        if (count($dbdata) > 0):
            $this->dbdata = $dbdata;
            $this->child = $child;
            $this->admin = $admin;
            $this->arg = $arg;
            $this->keys = $keys;
            $this->database = new \OXI_ACCORDIONS_PLUGINS\Helper\Database();
            if (array_key_exists('id', $this->dbdata)):
                $this->oxiid = $this->dbdata['id'];
            else:
                $this->oxiid = rand(100000, 200000);
            endif;
            $this->loader();
        endif;
    }

    /**
     * Current element loader
     *
     * @since 2.0.1
     */
    public function loader() {
        $this->style = json_decode(stripslashes($this->dbdata['rawdata']), true);
        $this->CSSDATA = $this->dbdata['stylesheet'];
        $this->WRAPPER = 'oxi-accordions-wrapper-' . $this->dbdata['id'];
        $this->hooks();
    }

    /**
     * load css and js hooks
     *
     * @since 2.0.1
     */
    public function hooks() {

        $this->public_jquery();
        $this->public_css();
        $this->public_frontend_loader();
        $this->public_attribute();
        $this->render();
        $inlinecss = $this->inline_public_css() . $this->inline_css . (array_key_exists('oxi-accordions-custom-css', $this->style) ? $this->style['oxi-accordions-custom-css'] : '');
        $inlinejs = $this->inline_public_jquery();
        if ($this->CSSDATA == '' && $this->admin == 'admin') {
            $cls = '\OXI_ACCORDIONS_PLUGINS\Layouts\Helper';
            $CLASS = new $cls('admin');
            $inlinecss .= $CLASS->inline_template_css_render($this->style);
        } else {

            echo $this->font_familly_validation(json_decode(($this->dbdata['font_family'] != '' ? $this->dbdata['font_family'] : "[]"), true));
            $inlinecss .= $this->CSSDATA;
        }
        if ($inlinejs != ''):
            if ($this->admin == 'admin'):
                echo _('<script>
                        (function ($) {
                            setTimeout(function () {');
                echo $inlinejs;
                echo _('    }, 2000);
                        })(jQuery)</script>');
            else:
                $jquery = '(function ($) {' . $inlinejs . '})(jQuery);';
                wp_add_inline_script($this->JSHANDLE, $jquery);
            endif;
        endif;
        if ($inlinecss != ''):
            $inlinecss = html_entity_decode($inlinecss);
            if ($this->admin == 'admin'):
                //only load while ajax called
                echo _('<style>');
                echo $inlinecss;
                echo _('</style>');
            else:
                wp_add_inline_style('oxi-accordions-ultimate', $inlinecss);
            endif;
        endif;
    }

    /**
     * front end loader css and js
     *
     * @since 2.0.1
     */
    public function public_frontend_loader() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxi-accordions-ultimate', OXI_ACCORDIONS_URL . 'assets/frontend/css/style.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_style('oxi-plugin-animate', OXI_ACCORDIONS_URL . 'assets/frontend/css/animate.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_script('oxi-accordions-collapse.min', OXI_ACCORDIONS_URL . 'assets/frontend/js/collapse.js', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        wp_enqueue_script('oxi-accordions-ultimate', OXI_ACCORDIONS_URL . 'assets/frontend/js/accordions.js', false, OXI_ACCORDIONS_PLUGIN_VERSION);
    }

    /**
     * load current element render since 2.0.1
     *
     * @since 2.0.1
     */
    public function render() {
        ?>
        <div class="oxi-addons-container oxi-accordions-wrapper <?php echo esc_attr($this->WRAPPER) ?>" id="<?php echo esc_attr($this->WRAPPER) ?>">
            <div class="oxi-addons-row">
                <?php
                if ($this->admin == 'admin'):
                    ?>
                    <input type="hidden" id="oxi-addons-iframe-background-color" name="oxi-addons-iframe-background-color" value=" <?php
                           if (is_array($this->style)):
                               if (array_key_exists('oxilab-preview-color', $this->style)):
                                   echo esc_attr($this->style['oxilab-preview-color']);
                               else:
                                   echo '#FFF';
                               endif;
                           else:
                               echo '#FFF';
                           endif;
                           ?>">
                    <?php
                endif;
                $this->default_render($this->style, $this->child, $this->admin);
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * load current element render since 2.0.1
     *
     * @since 2.0.1
     */
    public function public_attribute() {
        $this->style;
        $data = ' data-oxi-trigger="' . (array_key_exists('oxi-accordions-trigger', $this->style) ? $this->style['oxi-accordions-trigger'] . '' : 'click') . '" ';
        $data .= 'data-oxi-accordions-type="' . (array_key_exists('oxi-accordions-type', $this->style) ? '' . $this->style['oxi-accordions-type'] . '' : 'toggle') . '" ';
        $data .= 'data-oxi-auto-play="' . (array_key_exists('oxi-accordions-auto-play-duration-size', $this->style) ? '' . $this->style['oxi-accordions-auto-play-duration-size'] . '' : '3000') . '" ';

        $this->accordions_preloader = isset($this->style['oxi-accordions-preloader']) && $this->style['oxi-accordions-preloader'] == 'yes' ? 'style="opacity:0"' : '';
        $this->accordions_type = isset($this->style['oxi-accordions-type']) && $this->style['oxi-accordions-type'] == 'accordions' ? 'data-parent="#' . $this->WRAPPER . '"' : '';

        if (isset($this->style['oxi-accordions-content-type']) && $this->style['oxi-accordions-content-type'] === 'post'):
            $this->post_query();
        endif;
        return $this->public_attribute = $data;
    }

    /**
     * load public jquery
     *
     * @since 2.0.1
     */
    public function public_jquery() {
        echo _('');
    }

    /**
     * load public css
     *
     * @since 2.0.1
     */
    public function public_css() {
        echo _('');
    }

    /**
     * load inline public jquery
     *
     * @since 2.0.1
     */
    public function inline_public_jquery() {
        echo _('');
    }

    /**
     * load inline public css
     *
     * @since 2.0.1
     */
    public function inline_public_css() {
        echo _('');
    }

    public function default_render($style, $child, $admin) {







        echo '<div class="oxi-accordions-ultimate-style oxi-accordions-ultimate-template-' . esc_attr($this->oxiid) . '  oxi-accordions-clearfix oxi-accordions-preloader" ' . $this->public_attribute . ' ' . $this->accordions_preloader . '>';

        if ($style['oxi-accordions-search-option'] == 'active'):
            echo '<div class="oxi-accordions-ultimate-search-options">
                            <div class="oxi-accordions-ultimate-search">
                             <input type="search" class="oxi-accordions-ultimate-type-search" placeholder="Search your FAQ" value="" onkeyup="this.setAttribute(\'value\', this.value);">
                               <i class="oxi-icons fas fa-search"></i>
                            </div>
                          </div>';
        endif;

        $number = 1;

        foreach ($child as $key => $val) {
            $value = json_decode(stripslashes($val['rawdata']), true);

            $expand = '<div class="oxi-accordions-expand-collapse-' . esc_attr($this->oxiid) . ' oxi-accordions-expand-collapse ' . $style['oxi-accordions-head-expand-collapse-icon-interface'] . ' ' . $style['oxi-accordions-expand-collapse'] . ' ' . $style['oxi-accordions-head-expand-collapse-type'] . ' ' . $style['oxi-accordions-head-expand-collapse-shape'] . '">' . $this->expand_collapse_icon_number_render($style, $number) . '</div>';

            echo '<div class="oxi-accordions-single-card oxi-accordions-single-card-' . esc_attr($this->oxiid) . ' ' . ( isset($this->style['oxi-accordions-head-expand-collapse-location']) ? $this->style['oxi-accordions-head-expand-collapse-location'] : '') . ' oxi-accordions-single-card-' . esc_attr($this->oxiid) . '-' . $number . ' ' . $style['oxi-accordions-head-expand-collapse-position'] . '" id="oxi-accordions-single-card-' . esc_attr($this->oxiid) . '-' . $number . '">';
            if ($style['oxi-accordions-head-expand-collapse-position'] == 'oxi-accordions-head-expand-collapse-position-outside'):
                echo $expand;
            endif;
            echo '<div class="oxi-accordions-head-outside-body">';
            /*
             * Header Child Loop Start
             */
            echo '<div class="oxi-accordions-header-card">';
            echo '  <div class="oxi-accordions-header-body  oxi-accordions-header oxi-accordions-clearfix"   data-oxitoggle="oxicollapse" data-oxitarget="#oxi-accordions-content-' . esc_attr($this->oxiid) . '-' . esc_attr($number) . '" aria-expanded="false" ' . $this->accordions_url_render($value) . '>';
            if ($style['oxi-accordions-head-expand-collapse-position'] != 'oxi-accordions-head-expand-collapse-position-outside'):
                echo $expand;
            endif;
            echo '      <div class="oxi-accordions-header-content ' . $style['oxi-accordions-headding-additional'] . ' ' . $style['oxi-accordions-head-additional-location'] . '">';
            if ($style['oxi-accordions-content-type'] == 'content'):
                if ($value['oxi-accordions-modal-title-additional'] == 'icon'):
                    echo $this->icon_special_rander($value['oxi-accordions-modal-icon']);
                elseif ($value['oxi-accordions-modal-title-additional'] == 'number'):
                    echo $this->number_special_charecter($value['oxi-accordions-modal-number']);
                elseif ($value['oxi-accordions-modal-title-additional'] == 'image'):
                    echo$this->image_special_render('oxi-accordions-modal-image', $value);
                endif;
            endif;
            echo $this->title_special_charecter($value, 'oxi-accordions-modal-title', 'oxi-accordions-modal-sub-title');

            echo '      </div>
                     </div>
                    </div>';

            /*
             * Content Child Loop Start
             */
            $content_height = (isset($style['oxi-accordions-content-height']) ? $style['oxi-accordions-content-height'] : '') . ' ' . (isset($style['oxi-accordions-content-mx-height-interface']) ? $style['oxi-accordions-content-mx-height-interface'] : '') . ' ';
            $animation = isset($style['oxi-accordions-desc-animation']) ? $style['oxi-accordions-desc-animation'] : '';

            echo '  <div class="oxicollapse ' . $this->default_open($value) . ' oxi-accordions-content-card oxi-accordions-content-card-' . esc_attr($this->oxiid) . '  ' . ($this->admin == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="oxi-accordions-content-' . esc_attr($this->oxiid) . '-' . $number . '" ' . $this->accordions_type . '>';
            echo '     <div class="oxi-accordions-content-body ' . $content_height . '"  oxi-animation="' . $animation . '">';
            echo $this->accordions_content_render($style, $value);
            if ($style['oxi-accordions-content-height'] == 'oxi-accordions-content-height' && $style['oxi-accordions-content-mx-height-interface'] == 'oxi-accordions-content-mx-height-interface-button'):
                echo '<div class="oxi-accordions-content-expand-button">'
                . '        <div class="oxi-accordions-content-expand-body">'
                . '             <div class="oxi-accordions-content-expand-open">' . $this->text_render($style['oxi-accordions-content-mx-height-expand-text']) . '</div> '
                . '             <div class="oxi-accordions-content-expand-close">' . $this->text_render($style['oxi-accordions-content-mx-height-collapse-text']) . '</div>'
                . '        </div>'
                . ' </div>';
            endif;

            if ($this->admin == 'admin' && $style['oxi-accordions-content-type'] != 'post'):
                echo $this->admin_edit_panel($val['id']);
            endif;
            echo '      </div>
                    </div>

                    </div>
                </div>';
            $number++;
        }

        echo '</div>';
    }

    public function post_query() {
        $style = $this->style;
        $args = [
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'post_type' => $style['display_post_post_type'],
            'orderby' => $style['display_post_orderby'],
            'order' => $style['display_post_ordertype'],
            'posts_per_page' => $style['display_post_per_page'],
            'offset' => $style['display_post_offset'],
            'tax_query' => [],
        ];

        if (!empty($style['display_post_author'])):
            $args['author__in'] = $style['display_post_author'];
        endif;

        $type = $style['display_post_post_type'];

        if (!empty($style[$type . '_exclude'])) {
            $args['post__not_in'] = $style[$type . '_exclude'];
        }
        if (!empty($style[$type . '_include'])) {
            $args['post__in'] = $style[$type . '_include'];
        }
        if ($type != 'page') :
            if (!empty($style[$type . '_category'])) :
                $args['tax_query'][] = [
                    'taxonomy' => $type == 'post' ? 'category' : $type . '_category',
                    'field' => 'term_id',
                    'terms' => $style[$type . '_category'],
                ];
            endif;
            if (!empty($style[$type . '_tag'])) :
                $args['tax_query'][] = [
                    'taxonomy' => $type . '_tag',
                    'field' => 'term_id',
                    'terms' => $style[$type . '_tag'],
                ];
            endif;
            if (!empty($args['tax_query'])) :
                $args['tax_query']['relation'] = 'OR';
            endif;
        endif;

        $query = new \WP_Query($args);
        $postdata = [];
        $i = 1;
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $data = $this->defualt_value($i);
                $query->the_post();
                $data['shortcodeitemid'] = $this->oxiid;
                $data['oxi-accordions-modal-title'] = get_the_title();
                $data['oxi-accordions-modal-components-type'] = 'post';
                $data['oxi-accordions-modal-components-post'] = get_the_ID();
                $postdata[$i] = [
                    'id' => '',
                    'styleid' => $this->oxiid,
                    'rawdata' => json_encode($data),
                ];
                $i++;
            }
        }

        $this->child = $postdata;
    }

    /**
     * load default render
     *
     * @since 2.0.1
     */
    public function Json_Decode($rawdata) {
        return $rawdata != '' ? json_decode(stripcslashes($rawdata), true) : [];
    }

    public function font_familly_validation($data = []) {
        $api = get_option('oxi_addons_google_font');
        if ($api == 'no'):
            return;
        endif;
        foreach ($data as $value) {
            wp_enqueue_style('' . $value . '', 'https://fonts.googleapis.com/css?family=' . $value . '');
        }
    }

    public function array_render($id, $style) {
        if (array_key_exists($id, $style)):
            return $style[$id];
        endif;
    }

    public function text_render($data) {
        $r = do_shortcode(str_replace('spTac', '&nbsp;', str_replace('spBac', '<br>', html_entity_decode($data))), $ignore_html = false);
        return $r;
    }

    public function url_render($id, $style) {
        $link = [];
        if (array_key_exists($id . '-url', $style) && $style[$id . '-url'] != ''):
            $link['url'] = $style[$id . '-url'];
            if (array_key_exists($id . '-target', $style) && $style[$id . '-target'] != '0'):
                $link['target'] = $style[$id . '-target'];
            else:
                $link['target'] = '';
            endif;
        endif;
        return $link;
    }

    public function media_render($id, $style) {
        $url = '';
        if (array_key_exists($id . '-select', $style)):
            if ($style[$id . '-select'] == 'media-library'):
                $url = $style[$id . '-image'];
            else:
                $url = $style[$id . '-url'];
            endif;
            if (array_key_exists($id . '-image-alt', $style) && $style[$id . '-image-alt'] != ''):
                $r = 'src="' . esc_url($url) . '" alt="' . esc_html($style[$id . '-image-alt']) . '" ';
            else:
                $r = 'src="' . esc_url($url) . '" ';
            endif;
            return $r;
        endif;
    }

    public function excerpt($limit = 10) {
        $limit++;
        $excerpt = explode(' ', get_the_excerpt(), $limit);
        if (count($excerpt) >= $limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt) . '...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }
        $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
        return $excerpt;
    }

    public function post_title($limit = 10) {
        $limit++;
        $title = explode(' ', get_the_title(), $limit);
        if (count($title) >= $limit) {
            array_pop($title);
            $title = implode(" ", $title) . '...';
        } else {
            $title = implode(" ", $title);
        }
        return $title;
    }

    public function truncate($str, $length = 24) {
        if (mb_strlen($str) > $length) {
            return mb_substr($str, 0, $length) . '...';
        } else {
            return $str;
        }
    }

    public function accordions_url_render($style) {
        if (isset($style['oxi-accordions-modal-components-type']) && $style['oxi-accordions-modal-components-type'] == 'link'):
            $data = $this->url_render('oxi-accordions-modal-link', $style);
            if (count($data) >= 1):
                echo _($data);
                return ' data-link=\'' . json_encode($data) . '\'';
            endif;
        endif;
    }

    public function default_open($value) {
        if (isset($value['oxi-accordions-modal-default']) && $value['oxi-accordions-modal-default'] == 'yes'):
            return 'show';
        endif;
    }

    public function accordions_content_render($style, $child) {
        $content = '';
        if ($child['oxi-accordions-modal-components-type'] == 'nested-accordions'):
            $$content = $this->accordions_content_render_nested_accordions($style, $child);
        elseif ($child['oxi-accordions-modal-components-type'] == 'post'):

            $post_id = $child['oxi-accordions-modal-components-post'];
            $post_content = get_post($post_id);
            $content = $post_content->post_content;
            $content = apply_filters('the_content', $content);
        else:
            $content = $this->special_charecter($child['oxi-accordions-modal-desc']);
        endif;
        return $content;
    }

    public function accordions_content_render_nested_accordions($style, $child) {
        $shortcode = array_key_exists('oxi-accordions-modal-nested-accordions', $child) ? $child['oxi-accordions-modal-nested-accordions'] : '';
        if ($shortcode > 0):
            ob_start();
            new \OXI_ACCORDIONS_PLUGINS\Includes\Shortcode($shortcode, 'user');
            return ob_get_clean();
        endif;
        return;
    }

    public function special_charecter($data) {
        $data = html_entity_decode($data);
        $data = str_replace("\'", "'", $data);
        $data = str_replace('\"', '"', $data);
        $data = do_shortcode($data, $ignore_html = false);
        return $data;
    }

    public function title_special_charecter($array, $title, $subtitle) {
        $r = '<div class=\'oxi-accordions-header-title\'>';
        $t = false;
        if (!empty($array[$title]) && $array[$title] != ''):
            $t = true;
            $r .= '<div class=\'oxi-accordions-main-title\'>' . $this->special_charecter($array[$title]) . '</div>';
        endif;
        if (!empty($array[$subtitle]) && $array[$subtitle] != ''):
            $t = true;
            $r .= '<div class=\'oxi-accordions-sub-title\'>' . $this->special_charecter($array[$subtitle]) . '</div>';
        endif;
        $r .= '</div>';
        if ($t):
            return $r;
        endif;
    }

    public function number_special_charecter($data) {
        if (!empty($data) && $data != ''):
            return '<div class="oxi-accordions-header-li-number ' . (isset($this->style['oxi-accordions-head-additional-interface']) ? $this->style['oxi-accordions-head-additional-interface'] : '') . '">' . $this->special_charecter($data) . '</div>';
        endif;
    }

    public function image_special_render($id = '', $array = []) {
        $value = $this->media_render($id, $array);
        if (!empty($value)):
            return ' <img  class="oxi-accordions-header-li-image ' . (isset($this->style['oxi-accordions-head-additional-interface']) ? $this->style['oxi-accordions-head-additional-interface'] : '') . '" ' . $value . '>';
        endif;
    }

    public function icon_special_rander($id = '') {
        $value = $this->font_awesome_render($id);
        if (!empty($value)):
            return ' <div class="oxi-accordions-additional-icon oxi-accordions-additional-icon-' . esc_attr($this->oxiid) . ' ' . (isset($this->style['oxi-accordions-head-additional-interface']) ? $this->style['oxi-accordions-head-additional-interface'] : '') . '"> ' . $value . '</div>';
        endif;
    }

    public function font_awesome_render($data) {
        if (empty($data) || $data == ''):
            return;
        endif;
        $fadata = get_option('oxi_addons_font_awesome');
        if ($fadata == 'yes'):
            wp_enqueue_style('font-awsome.min', OXI_ACCORDIONS_URL . 'assets/frontend/css/font-awsome.min.css', false, OXI_ACCORDIONS_PLUGIN_VERSION);
        endif;
        $files = '<i class="' . esc_attr($data) . ' oxi-icons"></i>';
        return $files;
    }

    public function expand_collapse_icon_number_render($style = [], $number) {
        $data = '';
        if (isset($style['oxi-accordions-head-start-number'])):
            $data .= '<div class="oxi-accordions-expand-collapse-number">' . ($style['oxi-accordions-head-start-number'] + $number - 1) . '</div>';
        endif;
        if (isset($style['oxi-accordions-head-expand-icon']) && isset($style['oxi-accordions-head-collapse-icon'])):
            $data .= '<div class="oxi-accordions-expand-collapse-icon">
                        <div class="oxi-accordions-expand-icon">
                        ' . $this->font_awesome_render($style['oxi-accordions-head-expand-icon']) . '
                        </div>
                        <div class="oxi-accordions-collapse-icon">
                         ' . $this->font_awesome_render($style['oxi-accordions-head-collapse-icon']) . '
                        </div>
                    </div>';
        endif;
        return $data;
    }

    public function admin_edit_panel($id) {
        $data = '';
        if ($this->admin == 'admin'):
            $data = '   <div class="oxi-addons-admin-absulote">
                            <div class="oxi-addons-admin-absulate-edit">
                                <button class="btn btn-primary shortcode-addons-template-item-edit" type="button" value="' . esc_attr($id) . '">Edit</button>
                            </div>
                            <div class="oxi-addons-admin-absulate-delete">
                                <button class="btn btn-danger shortcode-addons-template-item-delete" type="submit" value="' . esc_attr($id) . '">Delete</button>
                            </div>
                        </div>';
        endif;
        return $data;
    }

    public function defualt_value($id) {
        return [
            'oxi-accordions-modal-default' => '',
            'oxi-accordions-modal-title' => '',
            'oxi-accordions-modal-sub-title' => '',
            'oxi-accordions-modal-title-additional' => '',
            'oxi-accordions-modal-icon' => '',
            'oxi-accordions-modal-number' => '',
            'oxi-accordions-modal-image-select' => 'media-library',
            'oxi-accordions-modal-image-image' => '',
            'oxi-accordions-modal-image-image-alt' => '',
            'oxi-accordions-modal-image-url' => '',
            'oxi-accordions-modal-components-type' => 'wysiwyg',
            'oxi-accordions-modal-link-url' => '',
            'oxi-accordions-modal-desc' => '',
            'shortcodeitemid' => $id,
            'oxi-accordions-modal-link-target' => '0',
            'oxi-accordions-modal-nested-tabs' => '',
            'oxi-accordions-modal-nested-accordions' => ''
        ];
    }

}
