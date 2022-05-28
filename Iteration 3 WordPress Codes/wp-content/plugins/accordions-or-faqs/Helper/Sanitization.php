<?php

namespace OXI_ACCORDIONS_PLUGINS\Helper;

if (!defined('ABSPATH'))
    exit;

/**
 *
 * author @biplob018
 */
use OXI_ACCORDIONS_PLUGINS\Helper\Controls as Controls;

trait Sanitization {

    /**
     * font settings sanitize
     * works at layouts page to adding font Settings sanitize
     */
    public function AdminTextSenitize($data) {
        $data = str_replace('\\\\"', '&quot;', $data);
        $data = str_replace('\\\"', '&quot;', $data);
        $data = str_replace('\\"', '&quot;', $data);
        $data = str_replace('\"', '&quot;', $data);
        $data = str_replace('"', '&quot;', $data);
        $data = str_replace('\\\\&quot;', '&quot;', $data);
        $data = str_replace('\\\&quot;', '&quot;', $data);
        $data = str_replace('\\&quot;', '&quot;', $data);
        $data = str_replace('\&quot;', '&quot;', $data);
        $data = str_replace("\\\\'", '&apos;', $data);
        $data = str_replace("\\\'", '&apos;', $data);
        $data = str_replace("\\'", '&apos;', $data);
        $data = str_replace("\'", '&apos;', $data);
        $data = str_replace("\\\\&apos;", '&apos;', $data);
        $data = str_replace("\\\&apos;", '&apos;', $data);
        $data = str_replace("\\&apos;", '&apos;', $data);
        $data = str_replace("\&apos;", '&apos;', $data);
        $data = str_replace("'", '&apos;', $data);
        $data = str_replace('<', '&lt;', $data);
        $data = str_replace('>', '&gt;', $data);
        $data = sanitize_text_field($data);
        return $data;
    }

    /*
     * Oxi Accordions Style Admin Panel header
     *
     * @since 2.0.1
     */

    public function start_section_header($id, array $arg = []) {
        echo '<ul class="oxi-addons-tabs-ul">   ';
        foreach ($arg['options'] as $key => $value) {
            echo '<li ref="#shortcode-addons-section-' . esc_attr($key) . '">' . esc_html($value) . '</li>';
        }
        echo '</ul>';
    }

    /*
     * Oxi Accordions Style Admin Panel Body
     *
     * @since 2.0.1
     */

    public function start_section_tabs($id, array $arg = []) {
        echo '<div class="oxi-addons-tabs-content-tabs" id="shortcode-addons-section-';
        if (array_key_exists('condition', $arg)) :
            foreach ($arg['condition'] as $value) {
                echo $value;
            }
        endif;
        echo '"  ' . (array_key_exists('padding', $arg) ? 'style="padding: ' . esc_attr($arg['padding']) . '"' : '') . '>';
    }

    /*
     * Oxi Accordions Style Admin Panel end Body
     *
     * @since 2.0.1
     */

    public function end_section_tabs() {
        echo '</div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Col 6 or Entry devider
     *
     * @since 2.0.1
     */

    public function start_section_devider() {
        echo '<div class="oxi-addons-col-6">';
    }

    /*
     * Oxi Accordions Style Admin Panel end Entry Divider
     *
     * @since 2.0.1
     */

    public function end_section_devider() {
        echo '</div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Form Dependency
     *
     * @since 2.0.1
     */

    public function forms_condition(array $arg = []) {

        if (array_key_exists('condition', $arg)) :
            $i = $arg['condition'] != '' ? count($arg['condition']) : 0;
// echo $i;
            $data = '';
            $s = 1;
            $form_condition = array_key_exists('form_condition', $arg) ? $arg['form_condition'] : '';
            foreach ($arg['condition'] != '' ? $arg['condition'] : [] as $key => $value) {
                if (is_array($value)):
                    $c = count($value);
                    $crow = 1;
                    if ($c > 1 && $i > 1):
                        $data .= '(';
                    endif;
                    foreach ($value as $item) {
                        $data .= $form_condition . $key . ' === \'' . $item . '\'';
                        if ($crow < $c) :
                            $data .= ' || ';
                            $crow++;
                        endif;
                    }
                    if ($c > 1 && $i > 1):
                        $data .= ')';
                    endif;
                elseif ($value == 'COMPILED') :
                    $data .= $form_condition . $key;
                elseif ($value == 'EMPTY') :
                    $data .= $form_condition . $key . ' !== \'\'';
                elseif (empty($value)) :
                    $data .= $form_condition . $key;
                else :
                    $data .= $form_condition . $key . ' === \'' . $value . '\'';
                endif;
                if ($s < $i) :
                    $data .= ' && ';
                    $s++;
                endif;
            }
            if (!empty($data)) :
                return 'data-condition="' . $data . '"';
            endif;
        endif;
    }

    /*
     * Oxi Accordions Style Admin Panel Each Accordions
     *
     * @since 2.0.1
     */

    public function start_controls_section($id, array $arg = []) {
        $defualt = ['showing' => FALSE];
        $arg = array_merge($defualt, $arg);
        $condition = $this->forms_condition($arg);
        echo '<div class="oxi-addons-content-div ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '" ' . $condition . '>
                    <div class="oxi-head">
                    ' . $arg['label'] . '
                    <div class="oxi-head-toggle"></div>
                    </div>
                    <div class="oxi-addons-content-div-body">';
    }

    /*
     * Oxi Accordions Style Admin Panel end Each Accordions
     *
     * @since 2.0.1
     */

    public function end_controls_section() {
        echo '</div></div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Section Inner Accordions
     * This Accordions like inner tabs as Normal view and Hover View
     *
     * @since 2.0.1
     */

    public function start_controls_tabs($id, array $arg = []) {
        $defualt = ['options' => ['normal' => 'Normal', 'hover' => 'Hover']];
        $arg = array_merge($defualt, $arg);
        $condition = $this->forms_condition($arg);
        echo '<div class="shortcode-form-control shortcode-control-type-control-tabs ' . (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '') . '" ' . $condition . ' >
                <div class="shortcode-form-control-content shortcode-form-control-content-tabs">
                    <div class="shortcode-form-control-field">';
        foreach ($arg['options'] as $key => $value) {
            echo '  <div class="shortcode-control-type-control-tab-child">
			<div class="shortcode-control-content">
				' . $value . '
                        </div>
                    </div>';
        }
        echo '</div>
              </div>
              <div class="shortcode-form-control-content">';
    }

    /*
     * Oxi Accordions Style Admin Panel end Section Inner Accordions
     *
     * @since 2.0.1
     */

    public function end_controls_tabs() {
        echo '</div> </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Section Inner Accordions Child
     *
     * @since 2.0.1
     */

    public function start_controls_tab() {
        echo '<div class="shortcode-form-control-content shortcode-form-control-tabs-content shortcode-control-tab-close">';
    }

    /*
     * Oxi Accordions Style Admin Panel End Section Inner Accordions Child
     *
     * @since 2.0.1
     */

    public function end_controls_tab() {
        echo '</div>';
    }

    /*
     * Oxi Accordions Style Admin Panel  Section Popover
     *
     * @since 2.0.1
     */

    public function start_popover_control($id, array $arg = [], $data = []) {
        if ($this->render_condition_control($id, $data, $arg)):
            $this->Popover_Condition = true;
        else:
            $this->Popover_Condition = false;
        endif;

        $condition = $this->forms_condition($arg);
        $separator = (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '');
        echo '  <div class="shortcode-form-control shortcode-control-type-popover ' . $separator . '" ' . $condition . '>
                    <div class="shortcode-form-control-content shortcode-form-control-content-popover">
                        <div class="shortcode-form-control-field">
                            <label for="" class="shortcode-form-control-title">' . $arg['label'] . '</label>
                            <div class="shortcode-form-control-input-wrapper">
                                <span class="dashicons popover-set"></span>
                            </div>
                        </div>
                        ' . (array_key_exists('description', $arg) ? '<div class="shortcode-form-control-description">' . $arg['description'] . '</div>' : '') . '

                    </div>
                    <div class="shortcode-form-control-content shortcode-form-control-content-popover-body">

               ';
    }

    /*
     * Oxi Accordions Style Admin Panel end Popover
     *
     * @since 2.0.1
     */

    public function end_popover_control() {
        $this->Popover_Condition = true;
        echo '</div></div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Form Add Control.
     * Call All Input Control from here Based on Control Name.
     *
     * @since 2.0.1
     */

    public function add_control($id, array $data = [], array $arg = []) {
        /*
         * Responsive Control Start
         * @since 2.0.1
         */
        $responsive = $responsiveclass = '';
        if (array_key_exists('responsive', $arg)) :
            if ($arg['responsive'] == 'laptop') :
                $responsiveclass = 'shortcode-addons-form-responsive-desktop';
            elseif ($arg['responsive'] == 'tab') :
                $responsiveclass = 'shortcode-addons-form-responsive-tablet';
            elseif ($arg['responsive'] == 'mobile') :
                $responsiveclass = 'shortcode-addons-form-responsive-mobile';
            endif;
            $responsive = '<div class="shortcode-form-control-responsive-switchers">
                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-desktop" data-device="desktop">
                                    <span class="dashicons dashicons-desktop"></span>
                                </a>
                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-tablet" data-device="tablet">
                                    <span class="dashicons dashicons-tablet"></span>
                                </a>
                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-mobile" data-device="mobile">
                                    <span class="dashicons dashicons-smartphone"></span>
                                </a>
                            </div>';

        endif;

        if (array_key_exists('customresponsive', $arg)):
            $arg['responsive'] = $arg['customresponsive'];
        endif;
        $defualt = [
            'type' => 'text',
            'label' => 'Input Text',
            'default' => '',
            'label_on' => esc_html__('Yes', 'accordions-or-faqs'),
            'label_off' => esc_html__('No', 'accordions-or-faqs'),
            'placeholder' => esc_html__('', 'accordions-or-faqs'),
            'selector-data' => TRUE,
            'render' => TRUE,
            'responsive' => 'laptop',
        ];

        /*
         * Data Currection while Its comes from group Control
         */
        if (array_key_exists('selector-value', $arg)) :
            foreach ($arg['selector'] as $key => $value) {
                $arg['selector'][$key] = $arg['selector-value'];
            }
        endif;

        $arg = array_merge($defualt, $arg);
        if ($arg['type'] == 'animation'):
            $arg['type'] = 'select';
            $arg['options'] = [
                '' => esc_html__('None', 'accordions-or-faqs'),
                'bounce' => esc_html__('Bounce', 'accordions-or-faqs'),
                'flash' => esc_html__('Flash', 'accordions-or-faqs'),
                'pulse' => esc_html__('Pulse', 'accordions-or-faqs'),
                'rubberBand' => esc_html__('RubberBand', 'accordions-or-faqs'),
                'shake' => esc_html__('Shake', 'accordions-or-faqs'),
                'swing' => esc_html__('Swing', 'accordions-or-faqs'),
                'tada' => esc_html__('Tada', 'accordions-or-faqs'),
                'wobble' => esc_html__('Wobble', 'accordions-or-faqs'),
                'jello' => esc_html__('Jello', 'accordions-or-faqs'),
            ];
        endif;

        $condition = $this->forms_condition($arg);
        $toggle = (array_key_exists('toggle', $arg) ? 'shortcode-addons-form-toggle' : '');
        $separator = (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '');

        $loader = (array_key_exists('loader', $arg) ? $arg['loader'] == TRUE ? ' shortcode-addons-control-loader ' : '' : '');
        echo '<div class="shortcode-form-control shortcode-control-type-' . $arg['type'] . ' ' . $separator . ' ' . $toggle . ' ' . $responsiveclass . ' ' . $loader . '" ' . $condition . '>
                <div class="shortcode-form-control-content">
                    <div class="shortcode-form-control-field">';
        echo '<label for="" class="shortcode-form-control-title">' . $arg['label'] . '</label>';
        echo $responsive;

        $fun = $arg['type'] . '_admin_control';
        echo $this->$fun($id, $data, $arg);
        echo '      </div>';
        echo (array_key_exists('description', $arg) ? '<div class="shortcode-form-control-description">' . $arg['description'] . '</div>' : '');

        echo ' </div>
        </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Responsive Control.
     * Can Possible to modify any Add control to Responsive Control
     *
     * @since 2.0.1
     */

    public function add_responsive_control($id, array $data = [], array $arg = []) {
        $lap = $id . '-lap';
        $tab = $id . '-tab';
        $mob = $id . '-mob';
        $laparg = ['responsive' => 'laptop'];

        $this->add_control($lap, $data, array_merge($arg, $laparg));

        if ($arg['type'] == 'dimensions' || $arg['type'] == 'slider'):
            $tabarg = [
                'responsive' => 'tab',
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
            ];
            $mobarg = [
                'responsive' => 'mobile',
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
            ];
        elseif ($arg['type'] == 'number'):
            $tabarg = [
                'responsive' => 'tab',
                'default' => '',
            ];
            $mobarg = [
                'responsive' => 'mobile',
                'default' => '',
            ];
        else:
            $tabarg = [
                'responsive' => 'tab',
            ];
            $mobarg = [
                'responsive' => 'mobile',
            ];
        endif;

        $this->add_control($tab, $data, array_merge($arg, $tabarg));
        $this->add_control($mob, $data, array_merge($arg, $mobarg));
    }

    /*
     * Oxi Accordions Style Admin Panel Group Control.
     *
     * @since 2.0.1
     */

    public function add_group_control($id, array $data = [], array $arg = []) {
        $defualt = [
            'type' => 'text',
            'description' => '',
            'simpledescription' => ''
        ];
        $arg = array_merge($defualt, $arg);
        $fun = $arg['type'] . '_admin_group_control';
        echo $this->$fun($id, $data, $arg);
    }

    public function add_rearrange_control($id, array $data = [], array $arg = []) {
        $condition = $this->forms_condition($arg);
        $separator = (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '');
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        echo '<div class="shortcode-form-control shortcode-control-type-' . $arg['type'] . ' ' . $separator . '" ' . $condition . '>
                <div class="shortcode-form-control-content">
                    <div class="shortcode-form-control-field">
                        <label for="" class="shortcode-form-control-title">' . $arg['label'] . '</label>
                    </div>
                    <div class="shortcode-form-rearrange-fields-wrapper" vlid="#' . $id . '">';
        $rearrange = explode(',', $value);
        foreach ($rearrange as $k => $vl) {
            if ($vl != ''):
                echo '  <div class="shortcode-form-repeater-fields" id="' . $vl . '">
                            <div class="shortcode-form-repeater-controls">
                                <div class="shortcode-form-repeater-controls-title">
                                    ' . ($arg['fields'][$vl]['label']) . '
                                </div>
                            </div>
                        </div>';
            endif;
        }
        echo '          <div class="shortcode-form-control-input-wrapper">
                            <input type="hidden" value="' . $value . '" name="' . $id . '" id="' . $id . '">
                        </div>
                    </div>
                </div>
            </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Heading Input.
     *
     * @since 2.0.1
     */

    public function heading_admin_control($id, array $data = [], array $arg = []) {
        echo ' ';
    }

    /*
     * Oxi Accordions Style Admin Panel Switcher Input.
     *
     * @since 2.0.1
     */

    public function separator_admin_control($id, array $data = [], array $arg = []) {
        echo '';
    }

    public function multiple_selector_handler($data, $val) {

        $val = preg_replace_callback('/\{\{\K(.*?)(?=}})/', function ($match)use ($data) {
            $ER = explode('.', $match[0]);
            if (strpos($match[0], 'SIZE') !== FALSE):
                $size = array_key_exists($ER[0] . '-size', $data) ? $data[$ER[0] . '-size'] : '';
                $match[0] = str_replace('.SIZE', $size, $match[0]);
            endif;
            if (strpos($match[0], 'UNIT') !== FALSE):
                $size = array_key_exists($ER[0] . '-choices', $data) ? $data[$ER[0] . '-choices'] : '';
                $match[0] = str_replace('.UNIT', $size, $match[0]);
            endif;
            if (strpos($match[0], 'VALUE') !== FALSE):
                $size = array_key_exists($ER[0], $data) ? $data[$ER[0]] : '';
                $match[0] = str_replace('.VALUE', $size, $match[0]);
            endif;
            return str_replace($ER[0], '', $match[0]);
        }, $val);
        return str_replace("{{", '', str_replace("}}", '', $val));
    }

    /*
     * Oxi Accordions Style Admin Panel Switcher Input.
     *
     * @since 2.0.1
     */

    public function switcher_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retun = [];
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    if (!empty($val) && $this->render_condition_control($id, $data, $arg)) {
                        $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                        $file = str_replace('{{VALUE}}', $value, $val);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        if (!empty($value)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    }
                    $retun[$key][$key]['type'] = ($val != '' ? 'CSS' : 'HTML');
                    $retun[$key][$key]['value'] = $val;
                }
            endif;
        }
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($retun)) : '';

        echo '  <div class="shortcode-form-control-input-wrapper">
                    <label class="shortcode-switcher">
                        <input type="checkbox" ' . ($value == $arg['return_value'] ? 'checked ckdflt="true"' : '') . ' value="' . $arg['return_value'] . '"  name="' . $id . '" id="' . $id . '"  retundata="' . $retunvalue . '"/>
                        <span data-on="' . $arg['label_on'] . '" data-off="' . $arg['label_off'] . '"></span>
                    </label>
                </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Text Input.
     *
     * @since 2.0.1
     */

    public function text_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('link', $arg)) :
            echo '<div class="shortcode-form-control-input-wrapper shortcode-form-control-input-link">
                     <input type="text"  name="' . $id . '" id="' . $id . '" value="' . htmlspecialchars($value) . '" placeholder="' . $arg['placeholder'] . '" retundata=\'' . $retunvalue . '\'>
                     <span class="dashicons dashicons-admin-generic"></span>';
        else :
            echo '<div class="shortcode-form-control-input-wrapper">
                <input type="text"  name="' . $id . '" id="' . $id . '" value="' . htmlspecialchars($value) . '" placeholder="' . $arg['placeholder'] . '" retundata=\'' . $retunvalue . '\'>
            </div>';
        endif;
    }

    /*
     * Oxi Accordions Style Admin Panel Hidden Input.
     *
     * @since 2.0.1
     */

    public function hidden_admin_control($id, array $data = [], array $arg = []) {

        $value = array_key_exists($id, $data) ? $data[$id] : '';

        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $this->render_condition_control($id, $data, $arg)) :
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                    $file = str_replace('{{VALUE}}', $value, $val);
                    if (strpos($file, '{{') !== FALSE):
                        $file = $this->multiple_selector_handler($data, $file);
                    endif;
                    if (!empty($value)):
                        $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                    endif;
                }
            endif;
        endif;
        echo ' <div class="shortcode-form-control-input-wrapper">
                   <input type="hidden" value="' . $value . '" name="' . $id . '" id="' . $id . '">
               </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Textarea Input.
     *
     * @since 2.0.1
     */

    public function textarea_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        echo '<div class="shortcode-form-control-input-wrapper">
                 <textarea  name="' . $id . '" id="' . $id . '" retundata=\'' . $retunvalue . '\' class="shortcode-form-control-tag-area" rows="' . (int) ((strlen($value) / 50) + 4) . '" placeholder="' . $arg['placeholder'] . '">' . str_replace('&nbsp;', '  ', str_replace('<br>', '&#13;&#10;', $value)) . '</textarea>
              </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel WYSIWYG Input.
     *
     * @since 2.0.1
     */

    public function wysiwyg_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        echo ' <div class="shortcode-form-control-input-wrapper"  retundata=\'' . $retunvalue . '\'>';
        echo wp_editor(
                $value, $id, $settings = array(
            'textarea_name' => $id,
            'wpautop' => false,
            'textarea_rows' => 7,
            'force_br_newlines' => true,
            'force_p_newlines' => false
                )
        );
        echo ' </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Image Input.
     *
     * @since 2.0.1
     */

    public function image_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $alt = array_key_exists($id . '-alt', $data) ? $data[$id . '-alt'] : '';
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <div class="shortcode-addons-media-control ' . (empty($value) ? 'shortcode-addons-media-control-hidden-button' : '') . '">
                        <div class="shortcode-addons-media-control-pre-load">
                        </div>
                        <div class="shortcode-addons-media-control-image-load" style="background-image: url(' . $value . ');" ckdflt="background-image: url(' . $value . ');">
                            <div class="shortcode-addons-media-control-image-load-delete-button">
                            </div>
                        </div>
                        <div class="shortcode-addons-media-control-choose-image">
                            Choose Image
                        </div>
                    </div>
                    <input type="hidden" class="shortcode-addons-media-control-link" id="' . $id . '" name="' . $id . '" value="' . $value . '" >
                    <input type="hidden" class="shortcode-addons-media-control-link-alt" id="' . $id . '-alt" name="' . $id . '-alt" value="' . $alt . '" >
                </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Number Input.
     *
     * @since 2.0.1
     */

    public function number_admin_control($id, array $data = [], array $arg = []) {

        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $this->render_condition_control($id, $data, $arg)) :
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                    $file = str_replace('{{VALUE}}', $value, $val);
                    if (strpos($file, '{{') !== FALSE):
                        $file = $this->multiple_selector_handler($data, $file);
                    endif;
                    if (!empty($value)):
                        $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                    endif;
                }
            endif;
        endif;

        $defualt = ['min' => 0, 'max' => 1000, 'step' => 1,];
        $arg = array_merge($defualt, $arg);
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input id="' . $id . '" name="' . $id . '" type="number" min="' . $arg['min'] . '" max="' . $arg['max'] . '" step="' . $arg['step'] . '" value="' . $value . '"  responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Slider Input.
     *
     * @since 2.0.1
     * Done With Number Information
     */

    public function slider_admin_control($id, array $data = [], array $arg = []) {
        $unit = array_key_exists($id . '-choices', $data) ? $data[$id . '-choices'] : $arg['default']['unit'];
        $size = array_key_exists($id . '-size', $data) ? $data[$id . '-size'] : $arg['default']['size'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $arg['render'] == TRUE && $this->render_condition_control($id, $data, $arg)) :
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    if ($size != '' && $val != '') :
                        $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                        $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                        $file = str_replace('{{SIZE}}', $size, $val);
                        $file = str_replace('{{UNIT}}', $unit, $file);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        if (!empty($size)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    endif;
                }
            endif;
        endif;
        if (array_key_exists('range', $arg)) :
            if (count($arg['range']) > 1) :
                echo ' <div class="shortcode-form-units-choices">';
                foreach ($arg['range'] as $key => $val) {
                    $rand = rand(10000, 233333333);
                    echo '<input id="' . $id . '-choices-' . $rand . '" type="radio" name="' . $id . '-choices"  value="' . $key . '" ' . ($key == $unit ? 'checked' : '') . '  min="' . $val['min'] . '" max="' . $val['max'] . '" step="' . $val['step'] . '">
                      <label class="shortcode-form-units-choices-label" for="' . $id . '-choices-' . $rand . '">' . $key . '</label>';
                }
                echo '</div>';
            endif;
        endif;
        $unitvalue = array_key_exists($id . '-choices', $data) ? 'unit="' . $data[$id . '-choices'] . '"' : '';
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <div class="shortcode-form-slider" id="' . $id . '-slider' . '"></div>
                    <div class="shortcode-form-slider-input">
                        <input name="' . $id . '-size" custom="' . (array_key_exists('custom', $arg) ? '' . $arg['custom'] . '' : '') . '" id="' . $id . '-size' . '" type="number" min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $size . '" default-value="' . $size . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                    </div>
                </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Select Input.
     *
     * @since 2.0.1
     */

    public function select_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retun = [];

        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    if (!empty($value) && !empty($val) && $arg['render'] == TRUE && $this->render_condition_control($id, $data, $arg)) {
                        $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                        $file = str_replace('{{VALUE}}', $value, $val);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        if (!empty($value)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    }
                    $retun[$key][$key]['type'] = ($val != '' ? 'CSS' : 'HTML');
                    $retun[$key][$key]['value'] = $val;
                }
            endif;
        }
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($retun)) : '';
        $multiple = (array_key_exists('multiple', $arg) && $arg['multiple']) == true ? TRUE : FALSE;

        echo '<div class="shortcode-form-control-input-wrapper">
                <div class="shortcode-form-control-input-select-wrapper">
                <select id="' . $id . '" class="shortcode-addons-select-input ' . ($multiple ? 'js-example-basic-multiple' : '' ) . '" ' . ($multiple ? 'multiple' : '' ) . ' name="' . $id . '' . ($multiple ? '[]' : '' ) . '"  responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>';
        foreach ($arg['options'] as $key => $val) {
            if (is_array($val)):
                if (isset($val[0]) && $val[0] == true):
                    echo '<optgroup label="' . $val[1] . '">';
                else:
                    echo '</optgroup>';
                endif;
            else:
                if (is_array($value)):
                    $new = array_flip($value);
                    echo ' <option value="' . $key . '" ' . (array_key_exists($key, $new) ? 'selected' : '') . '>' . $val . '</option>';
                else:
                    echo ' <option value="' . $key . '" ' . ($value == $key ? 'selected' : '') . '>' . $val . '</option>';
                endif;
            endif;
        }
        echo '</select>
                </div>
            </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Choose Input.
     *
     * @since 2.0.1
     */

    public function choose_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retun = [];

        $operator = array_key_exists('operator', $arg) ? $arg['operator'] : 'text';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    if (!empty($val) && $this->render_condition_control($id, $data, $arg)) {
                        $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                        $file = str_replace('{{VALUE}}', $value, $val);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        if (!empty($value)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    }
                    $retun[$key][$key]['type'] = ($val != '' ? 'CSS' : 'HTML');
                    $retun[$key][$key]['value'] = $val;
                }
            endif;
        }
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($retun)) : '';
        echo '<div class="shortcode-form-control-input-wrapper">
                <div class="shortcode-form-choices" responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>';
        foreach ($arg['options'] as $key => $val) {
            echo '  <input id="' . $id . '-' . $key . '" type="radio" name="' . $id . '" value="' . $key . '" ' . ($value == $key ? 'checked  ckdflt="true"' : '') . '>
                                    <label class="shortcode-form-choices-label" for="' . $id . '-' . $key . '" tooltip="' . (isset($val['title']) ? $val['title'] : '') . '">
                                        ' . (($operator == 'text') ? $val['title'] : '<i class="' . $val['icon'] . '" aria-hidden="true"></i>') . '
                                    </label>';
        }
        echo '</div>
        </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Color Input.
     *
     * @since 2.0.1
     */

    public function render_condition_control($id, array $data = [], array $arg = []) {
        if (!$this->Popover_Condition):
            return false;
        endif;
        if (array_key_exists('condition', $arg)):
            foreach ($arg['condition'] as $key => $value) {
                if (array_key_exists('conditional', $arg) && $arg['conditional'] == 'outside'):
                    $data = $this->style;
                elseif (array_key_exists('conditional', $arg) && $arg['conditional'] == 'inside' && isset($arg['form_condition'])):
                    $key = $arg['form_condition'] . $key;
                endif;
                if (strpos($key, '&') !== FALSE):
                    return true;
                endif;
                if (!array_key_exists($key, $data)):
                    return false;
                endif;
                if ($data[$key] != $value):
                    if (is_array($value)):
                        $t = false;
                        foreach ($value as $val) {
                            if ($data[$key] == $val):
                                $t = true;
                            endif;
                        }
                        return $t;
                    endif;
                    if ($value == 'EMPTY' && $data[$key] != '0'):
                        return true;
                    endif;
                    if (strpos($data[$key], '&') !== FALSE):
                        return true;
                    endif;
                    return false;
                endif;
            }
        endif;
        return true;
    }

    public function color_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $arg['render'] == TRUE && $this->render_condition_control($id, $data, $arg)) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                    $file = str_replace('{{VALUE}}', $value, $val);
                    if (strpos($file, '{{') !== FALSE):
                        $file = $this->multiple_selector_handler($data, $file);
                    endif;
                    if (!empty($value)):
                        $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                    endif;
                }
            endif;
        }
        $type = array_key_exists('oparetor', $arg) ? 'data-format="rgb" data-opacity="TRUE"' : '';
        echo '<div class="shortcode-form-control-input-wrapper">
                <input ' . $type . ' type="text"  class="oxi-addons-minicolor" id="' . $id . '" name="' . $id . '" value="' . $value . '" responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\' custom="' . (array_key_exists('custom', $arg) ? '' . $arg['custom'] . '' : '') . '">
             </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Icon Selector.
     *
     * @since 2.0.1
     */

    public function icon_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input type="text"  class="oxi-admin-icon-selector" id="' . $id . '" name="' . $id . '" value="' . $value . '" retundata="' . $retunvalue . '">
                    <span class="input-group-addon"></span>
                </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Font Selector.
     *
     * @since 2.0.1
     */

    public function font_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        if ($value != '' && array_key_exists($value, $this->google_font)) :
            $this->font[$value] = $value;
        endif;

        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE) {
            if (array_key_exists('selector', $arg) && $value != '') :
                foreach ($arg['selector'] as $key => $val) {
                    if ($arg['render'] == TRUE && !empty($val)) :
                        $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                        $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                        $file = str_replace('{{VALUE}}', str_replace("+", ' ', $value), $val);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        if (!empty($value)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    endif;
                }
            endif;
        }
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';

        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input type="text"  class="shortcode-addons-family" id="' . $id . '" name="' . $id . '" value="' . $value . '" responsive="' . $arg['responsive'] . '" retundata="' . $retunvalue . '">
                </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Date and Time Selector.
     *
     * @since 2.0.1
     */

    public function date_time_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $format = 'date';
        if (array_key_exists('time', $arg)) :
            if ($arg['time'] == TRUE) :
                $format = 'datetime-local';
            endif;
        endif;
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input type="' . $format . '"  id="' . $id . '" name="' . $id . '" value="' . $value . '">
                </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Gradient Selector.
     *
     * @since 2.0.1
     */

    public function gradient_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $this->render_condition_control($id, $data, $arg)) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    if ($arg['render'] == TRUE) :
                        $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                        $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                        $file = str_replace('{{VALUE}}', $value, $val);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        if (!empty($value)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    endif;
                }
            endif;
        }
        $background = (array_key_exists('gradient', $arg) ? $arg['gradient'] : '');
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input type="text" background="' . $background . '"  class="oxi-addons-gradient-color" id="' . $id . '" name="' . $id . '" value="' . $value . '" responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Dimensions Selector.
     *
     * @since 2.0.1
     */

    public function dimensions_admin_control($id, array $data = [], array $arg = []) {
        $unit = array_key_exists($id . '-choices', $data) ? $data[$id . '-choices'] : $arg['default']['unit'];
        $top = array_key_exists($id . '-top', $data) ? $data[$id . '-top'] : $arg['default']['size'];
        $bottom = array_key_exists($id . '-bottom', $data) ? $data[$id . '-bottom'] : $top;
        $left = array_key_exists($id . '-left', $data) ? $data[$id . '-left'] : $top;
        $right = array_key_exists($id . '-right', $data) ? $data[$id . '-right'] : $top;

        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        $ar = [$top, $bottom, $left, $right];
        $unlink = (count(array_unique($ar)) === 1 ? '' : 'link-dimensions-unlink');
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $arg['render'] == TRUE) {
            if (array_key_exists('selector', $arg)) :
                if (is_numeric($top) && is_numeric($right) && is_numeric($bottom) && is_numeric($left) && $this->render_condition_control($id, $data, $arg)) :
                    foreach ($arg['selector'] as $key => $val) {
                        $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                        $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                        $file = str_replace('{{UNIT}}', $unit, $val);
                        $file = str_replace('{{TOP}}', $top, $file);
                        $file = str_replace('{{RIGHT}}', $right, $file);
                        $file = str_replace('{{BOTTOM}}', $bottom, $file);
                        $file = str_replace('{{LEFT}}', $left, $file);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                    }
                endif;
            endif;
        }

        if (array_key_exists('range', $arg)) :
            if (count($arg['range']) > 1) :
                echo ' <div class="shortcode-form-units-choices">';
                foreach ($arg['range'] as $key => $val) {
                    $rand = rand(10000, 233333333);
                    echo '<input id="' . $id . '-choices-' . $rand . '" type="radio" name="' . $id . '-choices"  value="' . $key . '" ' . ($key == $unit ? 'checked' : '') . '  min="' . $val['min'] . '" max="' . $val['max'] . '" step="' . $val['step'] . '">
                      <label class="shortcode-form-units-choices-label" for="' . $id . '-choices-' . $rand . '">' . $key . '</label>';
                }
                echo '</div>';
            endif;
        endif;
        $unitvalue = array_key_exists($id . '-choices', $data) ? 'unit="' . $data[$id . '-choices'] . '"' : '';
        echo '<div class="shortcode-form-control-input-wrapper">
                <ul class="shortcode-form-control-dimensions">
                    <li class="shortcode-form-control-dimension">
                        <input id="' . $id . '-top" input-id="' . $id . '" name="' . $id . '-top" type="number"  min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $top . '" default-value="' . $top . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                        <label for="' . $id . '-top" class="shortcode-form-control-dimension-label">Top</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                       <input id="' . $id . '-right" input-id="' . $id . '" name="' . $id . '-right" type="number"  min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $right . '" default-value="' . $right . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                         <label for="' . $id . '-right" class="shortcode-form-control-dimension-label">Right</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                       <input id="' . $id . '-bottom" input-id="' . $id . '" name="' . $id . '-bottom" type="number"  min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $bottom . '" default-value="' . $bottom . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                       <label for="' . $id . '-bottom" class="shortcode-form-control-dimension-label">Bottom</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                        <input id="' . $id . '-left" input-id="' . $id . '" name="' . $id . '-left" type="number"  min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $left . '" default-value="' . $left . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                         <label for="' . $id . '-left" class="shortcode-form-control-dimension-label">Left</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                        <button type="button" class="shortcode-form-link-dimensions ' . $unlink . '"  data-tooltip="Link values together"></button>
                    </li>
                </ul>
            </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Typography.
     *
     * @since 2.0.1
     * Simple Interface Enable
     */

    public function typography_admin_group_control($id, array $data = [], array $arg = []) {
        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;

        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = '';
        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
        endif;
        if (array_key_exists('loader', $arg)) :
            $loader = 'loader';
            $loadervalue = $arg['loader'];
        endif;

        $this->start_popover_control(
                $id, [
            'label' => array_key_exists('label', $arg) ? $arg['label'] : 'Typography',
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'description' => $arg['description'],
            'separator' => $separator,
                ]
        );
        $this->add_control(
                $id . '-font', $data, [
            'label' => esc_html__('Font Family', 'accordions-or-faqs'),
            'type' => Controls::FONT,
            $selectorvalue => 'font-family:\'{{VALUE}}\';',
            $selector_key => $selector,
            $loader => $loadervalue
                ]
        );
        if (!array_key_exists('typo-font-size', $arg) || $arg['typo-font-size'] == true):
            $this->add_responsive_control(
                    $id . '-size', $data, [
                'label' => esc_html__('Size', 'accordions-or-faqs'),
                'type' => Controls::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => '',
                ],
                $loader => $loadervalue,
                $selectorvalue => 'font-size: {{SIZE}}{{UNIT}};',
                $selector_key => $selector,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'vm' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                    ]
            );
        endif;

        $this->add_control(
                $id . '-weight', $data, [
            'label' => esc_html__('Weight', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            $selectorvalue => 'font-weight: {{VALUE}};',
            $loader => $loadervalue,
            $selector_key => $selector,
            'options' => [
                '100' => esc_html__('100', 'accordions-or-faqs'),
                '200' => esc_html__('200', 'accordions-or-faqs'),
                '300' => esc_html__('300', 'accordions-or-faqs'),
                '400' => esc_html__('400', 'accordions-or-faqs'),
                '500' => esc_html__('500', 'accordions-or-faqs'),
                '600' => esc_html__('600', 'accordions-or-faqs'),
                '700' => esc_html__('700', 'accordions-or-faqs'),
                '800' => esc_html__('800', 'accordions-or-faqs'),
                '900' => esc_html__('900', 'accordions-or-faqs'),
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'normal' => esc_html__('Normal', 'accordions-or-faqs'),
                'bold' => esc_html__('Bold', 'accordions-or-faqs')
            ],
                ]
        );
        $this->add_control(
                $id . '-transform', $data, [
            'label' => esc_html__('Transform', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'uppercase' => esc_html__('Uppercase', 'accordions-or-faqs'),
                'lowercase' => esc_html__('Lowercase', 'accordions-or-faqs'),
                'capitalize' => esc_html__('Capitalize', 'accordions-or-faqs'),
                'none' => esc_html__('Normal', 'accordions-or-faqs'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'text-transform: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_control(
                $id . '-style', $data, [
            'label' => esc_html__('Style', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'normal' => esc_html__('normal', 'accordions-or-faqs'),
                'italic' => esc_html__('Italic', 'accordions-or-faqs'),
                'oblique' => esc_html__('Oblique', 'accordions-or-faqs'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'font-style: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_control(
                $id . '-decoration', $data, [
            'label' => esc_html__('Decoration', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'underline' => esc_html__('Underline', 'accordions-or-faqs'),
                'overline' => esc_html__('Overline', 'accordions-or-faqs'),
                'line-through' => esc_html__('Line Through', 'accordions-or-faqs'),
                'none' => esc_html__('None', 'accordions-or-faqs'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'text-decoration: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        if (array_key_exists('include', $arg)) :
            if ($arg['include'] == 'align_normal') :
                $this->add_responsive_control(
                        $id . '-align', $data, [
                    'label' => esc_html__('Text Align', 'accordions-or-faqs'),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => esc_html__('Default', 'accordions-or-faqs'),
                        'left' => esc_html__('Left', 'accordions-or-faqs'),
                        'center' => esc_html__('Center', 'accordions-or-faqs'),
                        'right' => esc_html__('Right', 'accordions-or-faqs'),
                    ],
                    $loader => $loadervalue,
                    $selectorvalue => 'text-align: {{VALUE}};',
                    $selector_key => $selector,
                        ]
                );
            else :
                $this->add_responsive_control(
                        $id . '-justify', $data, [
                    'label' => esc_html__('Justify Content', 'accordions-or-faqs'),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => esc_html__('Default', 'accordions-or-faqs'),
                        'flex-start' => esc_html__('Flex Start', 'accordions-or-faqs'),
                        'flex-end' => esc_html__('Flex End', 'accordions-or-faqs'),
                        'center' => esc_html__('Center', 'accordions-or-faqs'),
                        'space-around' => esc_html__('Space Around', 'accordions-or-faqs'),
                        'space-between' => esc_html__('Space Between', 'accordions-or-faqs'),
                    ],
                    $loader => $loadervalue,
                    $selectorvalue => 'justify-content: {{VALUE}};',
                    $selector_key => $selector,
                        ]
                );
                $this->add_responsive_control(
                        $id . '-align', $data, [
                    'label' => esc_html__('Align Items', 'accordions-or-faqs'),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => esc_html__('Default', 'accordions-or-faqs'),
                        'stretch' => esc_html__('Stretch', 'accordions-or-faqs'),
                        'baseline' => esc_html__('Baseline', 'accordions-or-faqs'),
                        'center' => esc_html__('Center', 'accordions-or-faqs'),
                        'flex-start' => esc_html__('Flex Start', 'accordions-or-faqs'),
                        'flex-end' => esc_html__('Flex End', 'accordions-or-faqs'),
                    ],
                    $loader => $loadervalue,
                    $selectorvalue => 'align-items: {{VALUE}};',
                    $selector_key => $selector,
                        ]
                );
            endif;
        endif;

        $this->add_responsive_control(
                $id . '-l-height', $data, [
            'label' => esc_html__('Line Height', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            $loader => $loadervalue,
            $selectorvalue => 'line-height: {{SIZE}}{{UNIT}};',
            $selector_key => $selector,
                ]
        );
        $this->add_responsive_control(
                $id . '-l-spacing', $data, [
            'label' => esc_html__('Letter Spacing', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 0.1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.01,
                ],
            ],
            $loader => $loadervalue,
            $selectorvalue => 'letter-spacing: {{SIZE}}{{UNIT}};',
            $selector_key => $selector,
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Oxi Accordions Style Admin Panel Media Group Control.
     *
     * @since 2.0.1
     *
     * Works at any version
     */

    public function media_admin_group_control($id, array $data = [], array $arg = []) {
        $type = array_key_exists('default', $arg) ? $arg['default']['type'] : 'media-library';
        $value = array_key_exists('default', $arg) ? $arg['default']['link'] : '';
        $level = array_key_exists('label', $arg) ? $arg['label'] : 'Photo Source';
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        echo '<div class="shortcode-form-control" style="padding: 0;" ' . $this->forms_condition($arg) . '>';
        $this->add_control(
                $id . '-select', $data, [
            'label' => esc_html__($level, 'accordions-or-faqs'),
            'type' => Controls::CHOOSE,
            'loader' => TRUE,
            'default' => $type,
            'separator' => $separator,
            'options' => [
                'media-library' => [
                    'title' => esc_html__('Media Library', 'accordions-or-faqs'),
                    'icon' => 'fa fa-align-left',
                ],
                'custom-url' => [
                    'title' => esc_html__('Custom URL', 'accordions-or-faqs'),
                    'icon' => 'fa fa-align-center',
                ]
            ],
                ]
        );
        $this->add_control(
                $id . '-image', $data, [
            'label' => esc_html__('Image', 'accordions-or-faqs'),
            'type' => Controls::IMAGE,
            'loader' => TRUE,
            'default' => $value,
            'condition' => [
                $id . '-select' => 'media-library',
            ],
            'simpledescription' => $arg['description'],
            'description' => $arg['description'],
                ]
        );
        $this->add_control(
                $id . '-url', $data, [
            'label' => esc_html__('Image URL', 'accordions-or-faqs'),
            'type' => Controls::TEXT,
            'default' => $value,
            'loader' => TRUE,
            'placeholder' => 'www.example.com/image.jpg',
            'condition' => [
                $id . '-select' => 'custom-url',
            ],
            'simpledescription' => $arg['description'],
            'description' => $arg['description'],
                ]
        );

        echo '</div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Box Shadow Control.
     *
     * @since 2.0.1
     * Only Works At Customizable Version
     */

    public function boxshadow_admin_group_control($id, array $data = [], array $arg = []) {

        $cond = $condition = $boxshadow = '';
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $true = TRUE;
        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = '';
        if (!array_key_exists($id . '-shadow', $data)):
            $data[$id . '-shadow'] = 'yes';
        endif;
        if (!array_key_exists($id . '-blur-size', $data)):
            $data[$id . '-blur-size'] = 0;
        endif;
        if (!array_key_exists($id . '-horizontal-size', $data)):
            $data[$id . '-horizontal-size'] = 0;
        endif;
        if (!array_key_exists($id . '-vertical-size', $data)):
            $data[$id . '-vertical-size'] = 0;
        endif;

        if (array_key_exists($id . '-shadow', $data) && $data[$id . '-shadow'] == 'yes' && array_key_exists($id . '-color', $data) && array_key_exists($id . '-blur-size', $data) && array_key_exists($id . '-spread-size', $data) && array_key_exists($id . '-horizontal-size', $data) && array_key_exists($id . '-vertical-size', $data)) :
            $true = ($data[$id . '-blur-size'] == 0 || empty($data[$id . '-blur-size'])) && ($data[$id . '-spread-size'] == 0 || empty($data[$id . '-spread-size'])) && ($data[$id . '-horizontal-size'] == 0 || empty($data[$id . '-horizontal-size'])) && ($data[$id . '-vertical-size'] == 0 || empty($data[$id . '-vertical-size'])) ? TRUE : FALSE;
            $boxshadow = ($true == FALSE ? '-webkit-box-shadow:' . (array_key_exists($id . '-type', $data) ? $data[$id . '-type'] : '') . ' ' . $data[$id . '-horizontal-size'] . 'px ' . $data[$id . '-vertical-size'] . 'px ' . $data[$id . '-blur-size'] . 'px ' . $data[$id . '-spread-size'] . 'px ' . $data[$id . '-color'] . ';' : '');
            $boxshadow .= ($true == FALSE ? '-moz-box-shadow:' . (array_key_exists($id . '-type', $data) ? $data[$id . '-type'] : '') . ' ' . $data[$id . '-horizontal-size'] . 'px ' . $data[$id . '-vertical-size'] . 'px ' . $data[$id . '-blur-size'] . 'px ' . $data[$id . '-spread-size'] . 'px ' . $data[$id . '-color'] . ';' : '');
            $boxshadow .= ($true == FALSE ? 'box-shadow:' . (array_key_exists($id . '-type', $data) ? $data[$id . '-type'] : '') . ' ' . $data[$id . '-horizontal-size'] . 'px ' . $data[$id . '-vertical-size'] . 'px ' . $data[$id . '-blur-size'] . 'px ' . $data[$id . '-spread-size'] . 'px ' . $data[$id . '-color'] . ';' : '');
        endif;

        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
            $boxshadow = array_key_exists($id . '-shadow', $data) && $data[$id . '-shadow'] == 'yes' ? $boxshadow : '';
            foreach ($arg['selector'] as $key => $val) {
                $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                $this->CSSDATA['laptop'][$class][$boxshadow] = $boxshadow;
            }
        endif;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Box Shadow', 'accordions-or-faqs'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => $arg['description'],
                ]
        );
        $this->add_control(
                $id . '-shadow', $data, [
            'label' => esc_html__('Shadow', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'default' => '',
            'label_on' => esc_html__('Yes', 'accordions-or-faqs'),
            'label_off' => esc_html__('None', 'accordions-or-faqs'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-type', $data, [
            'label' => esc_html__('Type', 'accordions-or-faqs'),
            'type' => Controls::CHOOSE,
            'default' => '',
            'options' => [
                '' => [
                    'title' => esc_html__('Outline', 'accordions-or-faqs'),
                    'icon' => 'fa fa-align-left',
                ],
                'inset' => [
                    'title' => esc_html__('Inset', 'accordions-or-faqs'),
                    'icon' => 'fa fa-align-center',
                ],
            ],
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );

        $this->add_control(
                $id . '-horizontal', $data, [
            'label' => esc_html__('Horizontal', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->add_control(
                $id . '-vertical', $data, [
            'label' => esc_html__('Vertical', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->add_control(
                $id . '-blur', $data, [
            'label' => esc_html__('Blur', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->add_control(
                $id . '-spread', $data, [
            'label' => esc_html__('Spread', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'separator' => TRUE,
            'type' => Controls::COLOR,
            'oparetor' => 'RGB',
            'default' => '#CCC',
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Oxi Accordions Style Admin Panel Text Shadow .
     *
     * @since 2.0.1
     * Only Works at Customizable Options
     */

    public function textshadow_admin_group_control($id, array $data = [], array $arg = []) {

        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $cond = $condition = $textshadow = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $true = TRUE;
        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = '';
        if (array_key_exists($id . '-color', $data) && array_key_exists($id . '-blur-size', $data) && array_key_exists($id . '-horizontal-size', $data) && array_key_exists($id . '-vertical-size', $data)) :
            $true = ($data[$id . '-blur-size'] == 0 || empty($data[$id . '-blur-size'])) && ($data[$id . '-horizontal-size'] == 0 || empty($data[$id . '-horizontal-size'])) && ($data[$id . '-vertical-size'] == 0 || empty($data[$id . '-vertical-size'])) ? TRUE : FALSE;
            $textshadow = ($true == FALSE ? 'text-shadow: ' . $data[$id . '-horizontal-size'] . 'px ' . $data[$id . '-vertical-size'] . 'px ' . $data[$id . '-blur-size'] . 'px ' . $data[$id . '-color'] . ';' : '');
        endif;
        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
            foreach ($arg['selector'] as $key => $val) {
                $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                $this->CSSDATA['laptop'][$class][$textshadow] = $textshadow;
            }
        endif;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Text Shadow', 'accordions-or-faqs'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => $arg['description'],
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'oparetor' => 'RGB',
            'default' => '#FFF',
            'custom' => $id . '|||||text-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
                ]
        );
        $this->add_control(
                $id . '-blur', $data, [
            'label' => esc_html__('Blur', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'separator' => TRUE,
            'custom' => $id . '|||||text-shadow',
            'render' => FALSE,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector
                ]
        );
        $this->add_control(
                $id . '-horizontal', $data, [
            'label' => esc_html__('Horizontal', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'custom' => $id . '|||||text-shadow',
            'render' => FALSE,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector
                ]
        );
        $this->add_control(
                $id . '-vertical', $data, [
            'label' => esc_html__('Vertical', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'custom' => $id . '|||||text-shadow',
            'render' => FALSE,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector
                ]
        );

        $this->end_popover_control();
    }

    /*
     * Oxi Accordions Style Admin Panel Text Shadow .
     *
     * @since 2.0.1
     *
     * Simple Interface Enable
     */

    public function animation_admin_group_control($id, array $data = [], array $arg = []) {
        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;

        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Animation', 'accordions-or-faqs'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'simpledescription' => 'Customize how long your animation will works',
            'description' => 'Customize animation with animation type, Animation Duration with Delay and Looping Options',
                ]
        );
        $this->add_control(
                $id . '-type', $data, [
            'label' => esc_html__('Type', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                'optgroup0' => [true, 'Attention Seekers'],
                '' => esc_html__('None', 'accordions-or-faqs'),
                'optgroup1' => [false],
                'optgroup2' => [true, 'Attention Seekers'],
                'bounce' => esc_html__('Bounce', 'accordions-or-faqs'),
                'flash' => esc_html__('Flash', 'accordions-or-faqs'),
                'pulse' => esc_html__('Pulse', 'accordions-or-faqs'),
                'rubberBand' => esc_html__('RubberBand', 'accordions-or-faqs'),
                'shake' => esc_html__('Shake', 'accordions-or-faqs'),
                'swing' => esc_html__('Swing', 'accordions-or-faqs'),
                'tada' => esc_html__('Tada', 'accordions-or-faqs'),
                'wobble' => esc_html__('Wobble', 'accordions-or-faqs'),
                'jello' => esc_html__('Jello', 'accordions-or-faqs'),
                'optgroup3' => [false],
                'optgroup4' => [true, 'Bouncing Entrances'],
                'bounceIn' => esc_html__('BounceIn', 'accordions-or-faqs'),
                'bounceInDown' => esc_html__('BounceInDown', 'accordions-or-faqs'),
                'bounceInLeft' => esc_html__('BounceInLeft', 'accordions-or-faqs'),
                'bounceInRight' => esc_html__('BounceInRight', 'accordions-or-faqs'),
                'bounceInUp' => esc_html__('BounceInUp', 'accordions-or-faqs'),
                'optgroup5' => [false],
                'optgroup6' => [true, 'Fading Entrances'],
                'fadeIn' => esc_html__('FadeIn', 'accordions-or-faqs'),
                'fadeInDown' => esc_html__('FadeInDown', 'accordions-or-faqs'),
                'fadeInDownBig' => esc_html__('FadeInDownBig', 'accordions-or-faqs'),
                'fadeInLeft' => esc_html__('FadeInLeft', 'accordions-or-faqs'),
                'fadeInLeftBig' => esc_html__('FadeInLeftBig', 'accordions-or-faqs'),
                'fadeInRight' => esc_html__('FadeInRight', 'accordions-or-faqs'),
                'fadeInRightBig' => esc_html__('FadeInRightBig', 'accordions-or-faqs'),
                'fadeInUp' => esc_html__('FadeInUp', 'accordions-or-faqs'),
                'fadeInUpBig' => esc_html__('FadeInUpBig', 'accordions-or-faqs'),
                'optgroup7' => [false],
                'optgroup8' => [true, 'Flippers'],
                'flip' => esc_html__('Flip', 'accordions-or-faqs'),
                'flipInX' => esc_html__('FlipInX', 'accordions-or-faqs'),
                'flipInY' => esc_html__('FlipInY', 'accordions-or-faqs'),
                'optgroup9' => [false],
                'optgroup10' => [true, 'Lightspeed'],
                'lightSpeedIn' => esc_html__('LightSpeedIn', 'accordions-or-faqs'),
                'optgroup11' => [false],
                'optgroup12' => [true, 'Rotating Entrances'],
                'rotateIn' => esc_html__('RotateIn', 'accordions-or-faqs'),
                'rotateInDownLeft' => esc_html__('RotateInDownLeft', 'accordions-or-faqs'),
                'rotateInDownRight' => esc_html__('RotateInDownRight', 'accordions-or-faqs'),
                'rotateInUpLeft' => esc_html__('RotateInUpLeft', 'accordions-or-faqs'),
                'rotateInUpRight' => esc_html__('RotateInUpRight', 'accordions-or-faqs'),
                'optgroup13' => [false],
                'optgroup14' => [true, 'Sliding Entrances'],
                'slideInUp' => esc_html__('SlideInUp', 'accordions-or-faqs'),
                'slideInDown' => esc_html__('SlideInDown', 'accordions-or-faqs'),
                'slideInLeft' => esc_html__('SlideInLeft', 'accordions-or-faqs'),
                'slideInRight' => esc_html__('SlideInRight', 'accordions-or-faqs'),
                'optgroup15' => [false],
                'optgroup16' => [true, 'Zoom Entrances'],
                'zoomIn' => esc_html__('ZoomIn', 'accordions-or-faqs'),
                'zoomInDown' => esc_html__('ZoomInDown', 'accordions-or-faqs'),
                'zoomInLeft' => esc_html__('ZoomInLeft', 'accordions-or-faqs'),
                'zoomInRight' => esc_html__('ZoomInRight', 'accordions-or-faqs'),
                'zoomInUp' => esc_html__('ZoomInUp', 'accordions-or-faqs'),
                'optgroup17' => [false],
                'optgroup18' => [true, 'Specials'],
                'hinge' => esc_html__('Hinge', 'accordions-or-faqs'),
                'rollIn' => esc_html__('RollIn', 'accordions-or-faqs'),
                'optgroup19' => [false],
            ],
                ]
        );
        $this->add_control(
                $id . '-duration', $data, [
            'label' => esc_html__('Duration (ms)', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 1000,
            ],
            'range' => [
                'px' => [
                    'min' => 00,
                    'max' => 10000,
                    'step' => 100,
                ],
            ],
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->add_control(
                $id . '-delay', $data, [
            'label' => esc_html__('Delay (ms)', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => 00,
                    'max' => 10000,
                    'step' => 100,
                ],
            ],
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->add_control(
                $id . '-offset', $data, [
            'label' => esc_html__('Offset', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 100,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->add_control(
                $id . '-looping', $data, [
            'label' => esc_html__('Looping', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'default' => '',
            'loader' => TRUE,
            'label_on' => esc_html__('Yes', 'accordions-or-faqs'),
            'label_off' => esc_html__('No', 'accordions-or-faqs'),
            'return_value' => 'yes',
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Oxi Accordions Style Admin Panel Border .
     *
     * @since 2.0.1
     * Complete Simple Version
     */

    public function border_admin_group_control($id, array $data = [], array $arg = []) {
        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = $render = '';
        $rn = [];
        if (array_key_exists('selector', $arg)) :

            foreach ($arg['selector'] as $key => $value) {
                if ($value != ''):
                    $rn[$key] = $value;
                else:
                    $rn[$key] = 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};';
                endif;
            }
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
        endif;
        if (array_key_exists('loader', $arg)) :
            $loader = 'loader';
            $loadervalue = $arg['loader'];
        endif;
        if (array_key_exists($id . '-type', $data) && $data[$id . '-type'] == '') :
            $render = 'render';
        endif;

        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Border', 'accordions-or-faqs'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => $arg['description'],
                ], $data
        );

        $this->add_control(
                $id . '-type', $data, [
            'label' => esc_html__('Type', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('None', 'accordions-or-faqs'),
                'solid' => esc_html__('Solid', 'accordions-or-faqs'),
                'dotted' => esc_html__('Dotted', 'accordions-or-faqs'),
                'dashed' => esc_html__('Dashed', 'accordions-or-faqs'),
                'double' => esc_html__('Double', 'accordions-or-faqs'),
                'groove' => esc_html__('Groove', 'accordions-or-faqs'),
                'ridge' => esc_html__('Ridge', 'accordions-or-faqs'),
                'inset' => esc_html__('Inset', 'accordions-or-faqs'),
                'outset' => esc_html__('Outset', 'accordions-or-faqs'),
                'hidden' => esc_html__('Hidden', 'accordions-or-faqs'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'border-style: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_responsive_control(
                $id . '-width', $data, [
            'label' => esc_html__('Width', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            $render => FALSE,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.01,
                ],
            ],
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
            $loader => $loadervalue,
            'selector' => $rn
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            $render => FALSE,
            'default' => '#fff',
            $loader => $loadervalue,
            $selectorvalue => 'border-color: {{VALUE}};',
            $selector_key => $selector,
            'condition' => [
                $id . '-type' => 'EMPTY'
            ],
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Oxi Accordions Style Admin Panel Border .
     *
     * @since 2.0.1
     * Complete Simple Version
     */

    public function singleborder_admin_group_control($id, array $data = [], array $arg = []) {
        //Render Value is {{SIZE}}, {{TYPE}}, {{COLOR}}
        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $selector_key = $loader = $loadervalue = $render = '';
        $selector = [];
        if (array_key_exists('selector', $arg)) :
            $selector_key = 'selector';
            foreach ($arg['selector'] as $key => $value) {
                $v = str_replace('{{SIZE}}', '{{' . $id . '-width.SIZE}}', str_replace('{{TYPE}}', '{{' . $id . '-type.VALUE}}', str_replace('{{COLOR}}', '{{' . $id . '-color.VALUE}}', $value)));
                $selector[$key] = $v;
            }
        endif;

        if (array_key_exists('loader', $arg)) :
            $loader = 'loader';
            $loadervalue = $arg['loader'];
        endif;
        if (array_key_exists($id . '-type', $data) && $data[$id . '-type'] == '') :
            $render = 'render';
        endif;

        $this->start_popover_control(
                $id, [
            'label' => $arg['label'],
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => $arg['description'],
                ],
                $data
        );
        $this->add_control(
                $id . '-type', $data, [
            'label' => esc_html__('Type', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('None', 'accordions-or-faqs'),
                'solid' => esc_html__('Solid', 'accordions-or-faqs'),
                'dotted' => esc_html__('Dotted', 'accordions-or-faqs'),
                'dashed' => esc_html__('Dashed', 'accordions-or-faqs'),
                'double' => esc_html__('Double', 'accordions-or-faqs'),
                'groove' => esc_html__('Groove', 'accordions-or-faqs'),
                'ridge' => esc_html__('Ridge', 'accordions-or-faqs'),
                'inset' => esc_html__('Inset', 'accordions-or-faqs'),
                'outset' => esc_html__('Outset', 'accordions-or-faqs'),
                'hidden' => esc_html__('Hidden', 'accordions-or-faqs'),
            ],
            $loader => $loadervalue,
            $selector_key => $selector,
                ]
        );
        $this->add_control(
                $id . '-width', $data, [
            'label' => esc_html__('Size', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 1,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 30,
                    'step' => 1,
                ],
            ],
            $loader => $loadervalue,
            $selector_key => $selector,
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            $render => FALSE,
            'default' => '',
            $loader => $loadervalue,
            $selector_key => $selector,
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Oxi Accordions Style Admin Panel Background .
     *
     * @since 2.0.1
     * Simple Interface Enable
     */

    public function background_admin_group_control($id, array $data = [], array $arg = []) {

        $backround = '';
        $render = FALSE;
        if (array_key_exists($id . '-color', $data)) :
            $color = $data[$id . '-color'];
            if (array_key_exists($id . '-img', $data) && $data[$id . '-img'] == 'yes') :
                if (strpos(strtolower($color), 'gradient') === FALSE) :
                    $color = 'linear-gradient(0deg, ' . $color . ' 0%, ' . $color . ' 100%)';
                endif;
                if ($data[$id . '-select'] == 'media-library') :
                    $backround .= 'background: ' . $color . ', url(\'' . $data[$id . '-image'] . '\') ' . $data[$id . '-repeat'] . ' ' . $data[$id . '-position'] . ';';
                else :
                    $backround .= 'background: ' . $color . ', url(\'' . $data[$id . '-url'] . '\') ' . $data[$id . '-repeat'] . ' ' . $data[$id . '-position'] . ';';
                endif;
            else :
                $backround .= 'background: ' . $color . ';';
            endif;
        endif;
        if (array_key_exists('selector', $arg)) :
            foreach ($arg['selector'] as $key => $val) {
                $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                $class = str_replace('{{WRAPPER}}', $this->CSSWRAPPER, $key);
                $this->CSSDATA['laptop'][$class][$backround] = $backround;
                $render = TRUE;
            }
        endif;

        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = '';
        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
        endif;
        if (array_key_exists('loader', $arg)) :
            $loader = 'loader';
            $loadervalue = $arg['loader'];
        endif;
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'condition' => array_key_exists('condition', $arg) ? $arg['condition'] : '',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'simpledescription' => $arg['simpledescription'],
            'description' => $arg['description'],
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::GRADIENT,
            'gradient' => $id,
            'oparetor' => 'RGB',
            'render' => FALSE,
            $selectorvalue => '',
            $selector_key => $selector,
                ]
        );

        $this->add_control(
                $id . '-img', $data, [
            'label' => esc_html__('Image', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'loader' => TRUE,
            'label_on' => esc_html__('Yes', 'accordions-or-faqs'),
            'label_off' => esc_html__('No', 'accordions-or-faqs'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-select', $data, [
            'label' => esc_html__('Photo Source', 'accordions-or-faqs'),
            'separator' => TRUE,
            'loader' => TRUE,
            'type' => Controls::CHOOSE,
            'default' => 'media-library',
            'options' => [
                'media-library' => [
                    'title' => esc_html__('Media Library', 'accordions-or-faqs'),
                    'icon' => 'fa fa-align-left',
                ],
                'custom-url' => [
                    'title' => esc_html__('Custom URL', 'accordions-or-faqs'),
                    'icon' => 'fa fa-align-center',
                ]
            ],
            'condition' => [
                $id . '-img' => 'yes',
            ],
                ]
        );
        $this->add_control(
                $id . '-image', $data, [
            'label' => esc_html__('Image', 'accordions-or-faqs'),
            'type' => Controls::IMAGE,
            'default' => '',
            'loader' => TRUE,
            'condition' => [
                $id . '-select' => 'media-library',
                $id . '-img' => 'yes',
            ],
                ]
        );
        $this->add_control(
                $id . '-url', $data, [
            'label' => esc_html__('Image URL', 'accordions-or-faqs'),
            'type' => Controls::TEXT,
            'default' => '',
            'loader' => TRUE,
            'placeholder' => 'www.example.com/image.jpg',
            'condition' => [
                $id . '-select' => 'custom-url',
                $id . '-img' => 'yes',
            ],
                ]
        );
        $this->add_control(
                $id . '-position', $data, [
            'label' => esc_html__('Position', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => 'center center',
            'render' => $render,
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'top left' => esc_html__('Top Left', 'accordions-or-faqs'),
                'top center' => esc_html__('Top Center', 'accordions-or-faqs'),
                'top right' => esc_html__('Top Right', 'accordions-or-faqs'),
                'center left' => esc_html__('Center Left', 'accordions-or-faqs'),
                'center center' => esc_html__('Center Center', 'accordions-or-faqs'),
                'center right' => esc_html__('Center Right', 'accordions-or-faqs'),
                'bottom left' => esc_html__('Bottom Left', 'accordions-or-faqs'),
                'bottom center' => esc_html__('Bottom Center', 'accordions-or-faqs'),
                'bottom right' => esc_html__('Bottom Right', 'accordions-or-faqs'),
            ],
            'loader' => TRUE,
            'condition' => [
                $id . '-img' => 'yes',
                '((' . $id . '-select === \'media-library\' && ' . $id . '-image !== \'\') || (' . $id . '-select === \'custom-url\' && ' . $id . '-url !== \'\'))' => 'COMPILED',
            ],
                ]
        );
        $this->add_control(
                $id . '-attachment', $data, [
            'label' => esc_html__('Attachment', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'render' => $render,
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'scroll' => esc_html__('Scroll', 'accordions-or-faqs'),
                'fixed' => esc_html__('Fixed', 'accordions-or-faqs'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'background-attachment: {{VALUE}};',
            $selector_key => $selector,
            'condition' => [
                $id . '-img' => 'yes',
                '((' . $id . '-select === \'media-library\' && ' . $id . '-image !== \'\') || (' . $id . '-select === \'custom-url\' && ' . $id . '-url !== \'\'))' => 'COMPILED',
            ],
                ]
        );
        $this->add_control(
                $id . '-repeat', $data, [
            'label' => esc_html__('Repeat', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => 'no-repeat',
            'render' => $render,
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'no-repeat' => esc_html__('No-Repeat', 'accordions-or-faqs'),
                'repeat' => esc_html__('Repeat', 'accordions-or-faqs'),
                'repeat-x' => esc_html__('Repeat-x', 'accordions-or-faqs'),
                'repeat-y' => esc_html__('Repeat-y', 'accordions-or-faqs'),
            ],
            'loader' => TRUE,
            'condition' => [
                $id . '-img' => 'yes',
                '((' . $id . '-select === \'media-library\' && ' . $id . '-image !== \'\') || (' . $id . '-select === \'custom-url\' && ' . $id . '-url !== \'\'))' => 'COMPILED',
            ],
                ]
        );
        $this->add_responsive_control(
                $id . '-size', $data, [
            'label' => esc_html__('Size', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => 'cover',
            'render' => $render,
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'auto' => esc_html__('Auto', 'accordions-or-faqs'),
                'cover' => esc_html__('Cover', 'accordions-or-faqs'),
                'contain' => esc_html__('Contain', 'accordions-or-faqs'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'background-size: {{VALUE}};',
            $selector_key => $selector,
            'condition' => [
                $id . '-img' => 'yes',
                '((' . $id . '-select === \'media-library\' && ' . $id . '-image !== \'\') || (' . $id . '-select === \'custom-url\' && ' . $id . '-url !== \'\'))' => 'COMPILED',
            ],
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Oxi Accordions Style Admin Panel Background .
     *
     * @since 2.0.1
     * Simple Interfaece Enable
     */

    public function url_admin_group_control($id, array $data = [], array $arg = []) {
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        else :
            $cond = $condition = '';
        endif;
        $form_condition = array_key_exists('form_condition', $arg) ? $arg['form_condition'] : '';
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $this->add_control(
                $id . '-url', $data, [
            'label' => esc_html__('Link', 'accordions-or-faqs'),
            'type' => Controls::TEXT,
            'link' => TRUE,
            'separator' => $separator,
            'placeholder' => 'http://www.example.com/',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition
                ]
        );
        echo '<div class="shortcode-form-control-content shortcode-form-control-content-popover-body">';

        $this->add_control(
                $id . '-target', $data, [
            'label' => esc_html__('New Window?', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'default' => '',
            'label_on' => esc_html__('Yes', 'accordions-or-faqs'),
            'label_off' => esc_html__('No', 'accordions-or-faqs'),
            'return_value' => 'yes',
                ]
        );
        echo '</div>' . (array_key_exists('description', $arg) ? '<div class="shortcode-form-control-description">' . $arg['description'] . '</div>' : '') . '</div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Column Size.
     *
     * @since 2.0.1
     * Complete Simple Interface
     */

    public function column_admin_group_control($id, array $data = [], array $arg = []) {

        $selector = array_key_exists('selector', $arg) ? $arg['selector'] : '';
        $select = array_key_exists('selector', $arg) ? 'selector' : '';
        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $this->add_control(
                $lap = $id . '-lap', $data, [
            'label' => esc_html__('Column Size', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'responsive' => 'laptop',
            'description' => $arg['description'],
            'default' => 'oxi-bt-col-lg-12',
            'options' => [
                'oxi-bt-col-lg-12' => esc_html__('Col 1', 'accordions-or-faqs'),
                'oxi-bt-col-lg-6' => esc_html__('Col 2', 'accordions-or-faqs'),
                'oxi-bt-col-lg-4' => esc_html__('Col 3', 'accordions-or-faqs'),
                'oxi-bt-col-lg-3' => esc_html__('Col 4', 'accordions-or-faqs'),
                'oxi-bt-col-lg-5' => esc_html__('Col 5', 'accordions-or-faqs'),
                'oxi-bt-col-lg-2' => esc_html__('Col 6', 'accordions-or-faqs'),
                'oxi-bt-col-lg-8' => esc_html__('Col 8', 'accordions-or-faqs'),
                'oxi-bt-col-lg-1' => esc_html__('Col 12', 'accordions-or-faqs'),
            ],
            'description' => 'Define how much column you want to show into single rows. Customize possible with desktop or tab or mobile Settings.',
            $select => $selector,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition
                ]
        );
        $this->add_control(
                $tab = $id . '-tab', $data, [
            'label' => esc_html__('Column Size', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'responsive' => 'tab',
            'default' => 'oxi-bt-col-md-12',
            'description' => $arg['description'],
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'oxi-bt-col-md-12' => esc_html__('Col 1', 'accordions-or-faqs'),
                'oxi-bt-col-md-6' => esc_html__('Col 2', 'accordions-or-faqs'),
                'oxi-bt-col-md-4' => esc_html__('Col 3', 'accordions-or-faqs'),
                'oxi-bt-col-md-3' => esc_html__('Col 4', 'accordions-or-faqs'),
                'oxi-bt-col-md-2' => esc_html__('Col 6', 'accordions-or-faqs'),
                'oxi-bt-col-md-1' => esc_html__('Col 12', 'accordions-or-faqs'),
            ],
            'description' => 'Define how much column you want to show into single rows. Customize possible with desktop or tab or mobile Settings.',
            $select => $selector,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition
                ]
        );
        $this->add_control(
                $mob = $id . '-mob', $data, [
            'label' => esc_html__('Column Size', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => 'oxi-bt-col-lg-12',
            'responsive' => 'mobile',
            'description' => $arg['description'],
            'options' => [
                '' => esc_html__('Default', 'accordions-or-faqs'),
                'oxi-bt-col-sm-12' => esc_html__('Col 1', 'accordions-or-faqs'),
                'oxi-bt-col-sm-6' => esc_html__('Col 2', 'accordions-or-faqs'),
                'oxi-bt-col-sm-4' => esc_html__('Col 3', 'accordions-or-faqs'),
                'oxi-bt-col-sm-3' => esc_html__('Col 4', 'accordions-or-faqs'),
                'oxi-bt-col-sm-5' => esc_html__('Col 5', 'accordions-or-faqs'),
                'oxi-bt-col-sm-2' => esc_html__('Col 6', 'accordions-or-faqs'),
                'oxi-bt-col-sm-1' => esc_html__('Col 12', 'accordions-or-faqs'),
            ],
            'description' => 'Define how much column you want to show into single rows. Customize possible with desktop or tab or mobile Settings.',
            $select => $selector,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition
                ]
        );
    }

    /*
     *
     *
     * Templates Substitute Data
     *
     *
     *
     *
     */
    /*
     * Oxi Accordions Style Admin Panel Template Substitute Control.
     *
     * @since 2.0.1
     */

    public function add_substitute_control($id, array $data = [], array $arg = []) {
        $fun = $arg['type'] . '_substitute_control';
        echo $this->$fun($id, $data, $arg);
    }

    /*
     * Oxi Accordions Style Admin Panel Template Substitute Modal Opener.
     *
     * @since 2.0.1
     */

    public function modalopener_substitute_control($id, array $data = [], array $arg = []) {
        $default = [
            'showing' => FALSE,
            'title' => 'Add New Items',
            'sub-title' => 'Add New Items'
        ];
        $arg = array_merge($default, $arg);
        /*
         * esc_html($arg['title']) = 'Add New Items';
         * $arg['sub-title'] = 'Add New Items 02';
         *
         */

        $condition = $this->forms_condition($arg);
        echo ' <div class="oxi-addons-item-form shortcode-addons-templates-right-panel ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '" ' . $condition . '>
                    <div class="oxi-addons-item-form-heading shortcode-addons-templates-right-panel-heading">
                        ' . esc_html($arg['title']) . '
                         <div class="oxi-head-toggle"></div>
                         </div>
                    <div class="oxi-addons-item-form-item shortcode-addons-templates-right-panel-body" id="oxi-addons-list-data-modal-open">
                        <span>
                            <i class="dashicons dashicons-plus-alt oxi-icons"></i>
                            ' . $arg['sub-title'] . '
                        </span>
                    </div>
                </div>';
    }

    public function shortcodename_substitute_control($id, array $data = [], array $arg = []) {
        $default = [
            'showing' => FALSE,
            'title' => 'Shortcode Name',
            'placeholder' => 'Set Your Shortcode Name'
        ];
        $arg = array_merge($default, $arg);
        /*
         * esc_html(esc_html($arg['title'])) = 'Add New Items';
         * $arg['sub-title'] = 'Add New Items 02';
         *
         */

        $condition = $this->forms_condition($arg);
        echo ' <div class="oxi-addons-shortcode  shortcode-addons-templates-right-panel ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '" ' . $condition . '>
                <div class="oxi-addons-shortcode-heading  shortcode-addons-templates-right-panel-heading">
                    ' . esc_html($arg['title']) . '
                    <div class="oxi-head-toggle"></div>
                </div>
                <div class="oxi-addons-shortcode-body  shortcode-addons-templates-right-panel-body">
                    <form method="post" id="shortcode-addons-name-change-submit">
                        <div class="input-group my-2">
                            <input type="hidden" class="form-control" name="addonsstylenameid" value="' . $data['id'] . '">
                            <input type="text" class="form-control" name="addonsstylename" placeholder=" ' . $arg['placeholder'] . '" value="' . $data['name'] . '">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" id="addonsstylenamechange">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>';
    }

    /*
     * Oxi Accordions Style Admin Panel Template Shortcode Info.
     *
     * @since 2.0.1
     */

    public function shortcodeinfo_substitute_control($id, array $data = [], array $arg = []) {
        $default = [
            'showing' => FALSE,
            'title' => 'Shortcode',
        ];
        $arg = array_merge($default, $arg);
        /*
         * esc_html($arg['title']) = 'Add New Items';
         * $arg['sub-title'] = 'Add New Items 02';
         *
         */
        $condition = $this->forms_condition($arg);
        echo ' <div class="oxi-addons-shortcode shortcode-addons-templates-right-panel ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '" ' . $condition . '>
                <div class="oxi-addons-shortcode-heading  shortcode-addons-templates-right-panel-heading">
                    ' . esc_html($arg['title']) . '
                    <div class="oxi-head-toggle"></div>
                </div>
                <div class="oxi-addons-shortcode-body shortcode-addons-templates-right-panel-body">
                    <em>Shortcode for posts/pages/plugins</em>
                    <p>Copy &amp;
                    paste the shortcode directly into any WordPress post, page or Page Builder.</p>
                    <input type="text" class="form-control" onclick="this.setSelectionRange(0, this.value.length)" value="[oxi_accordions id=&quot;' . $id . '&quot;]">
                    <span></span>
                    <em>Shortcode for templates/themes</em>
                    <p>Copy &amp;
                    paste this code into a template file to include the slideshow within your theme.</p>
                    <input type="text" class="form-control" onclick="this.setSelectionRange(0, this.value.length)" value="<?php echo do_shortcode(\'[oxi_accordions  id=&quot;' . $id . '&quot;]\'); ?>">
                    <span></span>
                </div>
            </div>';
    }

    public function rearrange_substitute_control($id, array $data = [], array $arg = []) {
        $default = [
            'showing' => FALSE,
            'title' => 'Accordions Rearrange',
            'sub-title' => 'Accordions Rearrange'
        ];
        $arg = array_merge($default, $arg);
        /*
         * esc_html($arg['title']) = 'Add New Items';
         * $arg['sub-title'] = 'Add New Items 02';
         *
         */
        $condition = $this->forms_condition($arg);
        echo '  <div class="oxi-addons-item-form shortcode-addons-templates-right-panel ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '" ' . $condition . '>
                    <div class="oxi-addons-item-form-heading shortcode-addons-templates-right-panel-heading">
                        ' . esc_html($arg['title']) . '
                        <div class="oxi-head-toggle"></div>
                    </div>
                    <div class="oxi-addons-item-form-item shortcode-addons-templates-right-panel-body" id="oxi-addons-rearrange-data-modal-open">
                        <span>
                        <i class="dashicons dashicons-plus-alt oxi-icons"></i>
                        ' . $arg['sub-title'] . '
                        </span>
                    </div>
                </div>
                <div id="oxi-addons-list-rearrange-modal" class="modal fade bd-example-modal-sm" role="dialog">
                    <div class="modal-dialog">
                        <form id="oxi-addons-form-rearrange-submit">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Rearrange Content</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12 alert text-center" id="oxi-addons-list-rearrange-saving">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </div>
                                    <ul class="col-12 list-group" id="oxi-addons-modal-rearrange">
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" id="oxi-addons-list-rearrange-data">
                                    <button type="button" id="oxi-addons-list-rearrange-close" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <input type="submit" id="oxi-addons-list-rearrange-submit" class="btn btn-primary" value="Save">
                                </div>
                            </div>
                        </form>
                    <div id="modal-rearrange-store-file">
                        ' . $id . '
                    </div>
                </div>
            </div>';
    }

}
