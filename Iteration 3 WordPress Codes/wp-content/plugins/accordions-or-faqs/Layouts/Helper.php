<?php

namespace OXI_ACCORDIONS_PLUGINS\Layouts;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Helper
 *
 * @author Oxizen
 */
use OXI_ACCORDIONS_PLUGINS\Layouts\Admin;
use OXI_ACCORDIONS_PLUGINS\Helper\Controls as Controls;

class Helper extends Admin {

    use \OXI_ACCORDIONS_PLUGINS\Helper\Post_Query;

    public function register_controls() {

        $this->start_section_header(
                'shortcode-addons-start-tabs', [
            'options' => [
                'general-settings' => esc_html__('General Settings', 'accordions-or-faqs'),
                'heading-settings' => esc_html__('Heading Settings', 'accordions-or-faqs'),
                'description-settings' => esc_html__('Description Settings', 'accordions-or-faqs'),
                'custom' => esc_html__('Custom CSS', 'accordions-or-faqs'),
            ]
                ]
        );
        // General Section
        $this->register_general_parent();

        // Heading Section
        $this->register_heading_parent();

        //Description Section
        $this->register_description_parent();

        //Custom CSS
        $this->register_custom_parent();
    }

    public function register_general_parent() {
        //General Section
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-accordions-start-tabs' => 'general-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_gen_general();
        $this->register_post_query_settings();
        $this->register_search_options();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_gen_design();

        $this->end_section_devider();
        $this->end_section_tabs();
        //General Section End
        //
    }

    public function register_gen_general() {
        $this->start_controls_section(
                'oxi-accordions-gen', [
            'label' => esc_html__('General Settings', 'accordions-or-faqs'),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-accordions-content-type', $this->style, [
            'label' => esc_html__('Content Type', 'accordions-or-faqs'),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 'content',
            'loader' => TRUE,
            'options' => [
                'content' => [
                    'title' => esc_html__('Content', 'accordions-or-faqs'),
                ],
                'post' => [
                    'title' => esc_html__('Post', 'accordions-or-faqs'),
                ],
            ],
            'description' => 'Select accordion type as Content or Post.',
                ]
        );
        $this->add_control(
                'oxi-accordions-type', $this->style, [
            'label' => esc_html__('Accordions', 'accordions-or-faqs'),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'loader' => TRUE,
            'default' => 'toggle',
            'options' => [
                'toggle' => [
                    'title' => esc_html__('Toggle', 'accordions-or-faqs'),
                ],
                'accordions' => [
                    'title' => esc_html__('Accordions', 'accordions-or-faqs'),
                ],
            ],
            'description' => 'Choose accordions type to open content with Toggle or Accordions method.',
                ]
        );
        $this->add_control(
                'oxi-accordions-animation-duration', $this->style, [
            'label' => esc_html__('Animation Duration', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'ms',
                'size' => '',
            ],
            'range' => [
                'ms' => [
                    'min' => 0,
                    'max' => 3000,
                    'step' => 1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card' => 'transition-duration:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set content on off animation durations as mili seconds.',
                ]
        );

        $this->add_control(
                'oxi-accordions-trigger', $this->style, [
            'label' => esc_html__('Activator Event', 'accordions-or-faqs'),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 'click',
            'loader' => TRUE,
            'options' => [
                'click' => [
                    'title' => esc_html__('Click', 'accordions-or-faqs'),
                ],
                'hover' => [
                    'title' => esc_html__('Hover', 'accordions-or-faqs'),
                ],
                'auto' => [
                    'title' => esc_html__('Auto', 'accordions-or-faqs'),
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-single-card' => '',
            ],
            'description' => 'Accordions will will play at Click or Hover, Click event will works at auto play',
                ]
        );
        $this->add_control(
                'oxi-accordions-auto-play-duration', $this->style, [
            'label' => esc_html__('Auto Play Duration', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-trigger' => 'auto',
            ],
            'default' => [
                'unit' => 'ms',
                'size' => '',
            ],
            'range' => [
                'ms' => [
                    'min' => 1000,
                    'max' => 12000,
                    'step' => 100,
                ],
            ],
            'description' => 'Set auto play on off durations as mili seconds.',
                ]
        );

        $this->end_controls_section();
    }

    /*
     * @return void
     * Start Post Query for Display Post
     */

    public function register_post_query_settings() {
        $this->start_controls_section(
                'display-post',
                [
                    'label' => esc_html__('Post Query', 'accordions-or-faqs'),
                    'showing' => true,
                    'condition' => [
                        'oxi-accordions-content-type' => 'post'
                    ],
                ]
        );
        $this->add_control(
                'display_post_post_type',
                $this->style,
                [
                    'label' => esc_html__('Post Type', 'accordions-or-faqs'),
                    'loader' => TRUE,
                    'type' => Controls::SELECT,
                    'default' => 'post',
                    'options' => $this->post_type(),
                    'description' => 'Select Post Type for Query.'
                ]
        );
        $this->add_control(
                'display_post_author',
                $this->style,
                [
                    'label' => esc_html__('Author', 'accordions-or-faqs'),
                    'loader' => TRUE,
                    'type' => Controls::SELECT,
                    'multiple' => true,
                    'options' => $this->post_author(),
                    'description' => 'Confirm Author list if you wanna those author post only.'
                ]
        );
        foreach ($this->post_type() as $key => $value) {
            if ($key != 'page') :
                $this->add_control(
                        $key . '_category',
                        $this->style,
                        [
                            'label' => esc_html__(' Category', 'accordions-or-faqs'),
                            'type' => Controls::SELECT,
                            'multiple' => true,
                            'loader' => TRUE,
                            'options' => $this->post_category($key),
                            'condition' => [
                                'display_post_post_type' => $key
                            ],
                            'description' => 'Confirm Category list if you wanna those Category post only.',
                        ]
                );
                $this->add_control(
                        $key . '_tag',
                        $this->style,
                        [
                            'label' => esc_html__(' Tags', 'accordions-or-faqs'),
                            'type' => Controls::SELECT,
                            'multiple' => true,
                            'loader' => TRUE,
                            'options' => $this->post_tags($key),
                            'condition' => [
                                'display_post_post_type' => $key
                            ],
                            'description' => 'Confirm Post Tags if you wanna show those tags post only.',
                        ]
                );
            endif;

            $this->add_control(
                    $key . '_include',
                    $this->style,
                    [
                        'label' => esc_html__(' Include Post', 'accordions-or-faqs'),
                        'type' => Controls::SELECT,
                        'multiple' => true,
                        'loader' => TRUE,
                        'options' => $this->post_include($key),
                        'condition' => [
                            'display_post_post_type' => $key
                        ],
                        'description' => 'Only those post will viewing in Post list.',
                    ]
            );
            $this->add_control(
                    $key . '_exclude',
                    $this->style,
                    [
                        'label' => esc_html__(' Exclude Post', 'accordions-or-faqs'),
                        'type' => Controls::SELECT,
                        'multiple' => true,
                        'loader' => TRUE,
                        'options' => $this->post_exclude($key),
                        'condition' => [
                            'display_post_post_type' => $key
                        ],
                        'description' => 'Those Post can\'t viewing.',
                    ]
            );
        }


        $this->add_control(
                'display_post_per_page',
                $this->style,
                [
                    'label' => esc_html__('Maximum Post', 'accordions-or-faqs'),
                    'type' => Controls::NUMBER,
                    'loader' => TRUE,
                    'min' => 1,
                    'description' => 'How many Post You want to Viewing into page.',
                ]
        );
        $this->add_control(
                'display_post_offset',
                $this->style,
                [
                    'label' => esc_html__('Offset', 'accordions-or-faqs'),
                    'type' => Controls::NUMBER,
                    'loader' => TRUE,
                    'description' => 'Confirm offset of your Query.',
                ]
        );
        $this->add_control(
                'display_post_orderby',
                $this->style,
                [
                    'label' => esc_html__(' Order By', 'accordions-or-faqs'),
                    'type' => Controls::SELECT,
                    'default' => 'ID',
                    'loader' => TRUE,
                    'options' => [
                        'ID' => 'Post ID',
                        'author' => 'Post Author',
                        'title' => 'Title',
                        'date' => 'Date',
                        'modified' => 'Last Modified Date',
                        'parent' => 'Parent Id',
                        'rand' => 'Random',
                        'comment_count' => 'Comment Count',
                        'menu_order' => 'Menu Order',
                    ],
                    'description' => 'Set Post Query Order by Condition.',
                ]
        );

        $this->add_control(
                'display_post_ordertype',
                $this->style,
                [
                    'label' => esc_html__('Order Type', 'accordions-or-faqs'),
                    'type' => Controls::SELECT,
                    'loader' => TRUE,
                    'options' => [
                        'asc' => 'Ascending',
                        'desc' => 'Descending',
                    ],
                    'description' => 'Set Post Query Order by Condition.',
                ]
        );
        $this->end_controls_section();
    }

    /*
     * @return void
     * Start frequently asked questions Query
     */

    public function register_search_options() {
        $this->start_controls_section(
                'search-options',
                [
                    'label' => esc_html__('Search Options', 'accordions-or-faqs'),
                    'showing' => true,
                    'condition' => [
                        'oxi-accordions-search-option' => 'active'
                    ],
                ]
        );
        $this->add_control(
                'oxi-accordions-search-option-text', $this->style, [
            'label' => esc_html__('Placeholder Text', 'accordions-or-faqs'),
            'type' => Controls::TEXT,
            'default' => 'Search your FAQ',
            'description' => 'Customize search option text.',
                ]
        );
        $this->add_control(
                'oxi-accordions-search-option-alignment', $this->style, [
            'label' => esc_html__('Alignment', 'accordions-or-faqs'),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_ICON,
            'default' => 'left',
            'options' => [
                'left' => [
                    'icon' => esc_html__('fas fa-align-left', 'accordions-or-faqs'),
                ],
                'center' => [
                    'icon' => esc_html__('fas fa-align-center', 'accordions-or-faqs'),
                ],
                'right' => [
                    'icon' => esc_html__('fas fa-align-right', 'accordions-or-faqs'),
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options' => 'text-align: {{VALUE}};',
            ],
            'description' => 'Confirm Expand or Collapse alignment as left, center or right',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-search-option-width', $this->style, [
            'label' => esc_html__('Width', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Search button’s Width.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-search-option-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search > .oxi-accordions-ultimate-type-search' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search > .oxi-accordions-ultimate-type-search::-webkit-input-placeholder' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search > .oxi-icons' => '',
            ],
            'description' => 'Customize the typography options for the Search Text.',
                ]
        );
        $this->add_control(
                'oxi-accordions-search-option-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search .oxi-accordions-ultimate-type-search' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search .oxi-accordions-ultimate-type-search:focus' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search .oxi-accordions-ultimate-type-search:active' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search .oxi-accordions-ultimate-type-search:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search > .oxi-accordions-ultimate-type-search::-webkit-input-placeholder' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search > .oxi-icons' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the search button color.',
                ]
        );
        $this->add_control(
                'oxi-accordions-search-option-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize search button with color, gradient or image properties.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-search-option-border', $this->style, [
            'type' => Controls::BORDER,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search' => '',
            ],
            'description' => 'Customize border for search button.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-search-option-radius', $this->style, [
            'label' => esc_html__('Border Radius', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the search button.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-search-option-padding', $this->style, [
            'label' => esc_html__('Padding', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Adjust your padding for search button.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-search-option-margin', $this->style, [
            'label' => esc_html__('Margin', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-ultimate-search-options > .oxi-accordions-ultimate-search' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Adjust your margin for Search button.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_gen_design() {
        $this->start_controls_section(
                'oxi-accordions-heading', [
            'label' => esc_html__('Accordions Settings', 'accordions-or-faqs'),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-accordions-preloader', $this->style, [
            'label' => esc_html__('Preloader', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'label_on' => esc_html__('True', 'accordions-or-faqs'),
            'label_off' => esc_html__('False', 'accordions-or-faqs'),
            'return_value' => 'yes',
            'description' => 'Accordion will be hidden until page load completed.',
                ]
        );
        $this->add_control(
                'oxi-accordions-expand-collapse', $this->style, [
            'label' => esc_html__('Expand & Collapse Icon', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'label_on' => esc_html__('True', 'accordions-or-faqs'),
            'label_off' => esc_html__('False', 'accordions-or-faqs'),
            'return_value' => 'oxi-accordions-expand-collapse-active',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-header-card > .oxi-accordions-header-body .oxi-accordions-expand-collapse' => '',
            ],
            'description' => 'Show/hide expand and collapse icon.',
                ]
        );
        $this->add_control(
                'oxi-accordions-search-option', $this->style, [
            'label' => esc_html__('Search Option', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'label_on' => esc_html__('True', 'accordions-or-faqs'),
            'label_off' => esc_html__('False', 'accordions-or-faqs'),
            'return_value' => 'active',
            'loader' => TRUE,
            'description' => 'Show/hide search FAQs option or frequently asked questions.',
                ]
        );

        $this->add_control(
                'oxi-accordions-headding-additional', $this->style, [
            'label' => esc_html__('Heading Additional', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'label_on' => esc_html__('True', 'accordions-or-faqs'),
            'label_off' => esc_html__('False', 'accordions-or-faqs'),
            'return_value' => 'oxi-accordions-headding-additional-active',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-header-card > .oxi-accordions-header-body .oxi-accordions-header-content' => '',
            ],
            'condition' => [
                'oxi-accordions-content-type' => 'content',
            ],
            'description' => 'Show/hide Heading additional items  like image or icon or number.',
                ]
        );
        $this->add_control(
                'oxi-accordions-content-height', $this->style, [
            'label' => esc_html__('Fixed Content Height', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'loader' => TRUE,
            'label_on' => esc_html__('True', 'accordions-or-faqs'),
            'label_off' => esc_html__('False', 'accordions-or-faqs'),
            'return_value' => 'oxi-accordions-content-height',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-content-body' => '',
            ],
            'description' => 'Check to display collapsible accordion content in a limited amount of space. Extra Settings available under Description Settings',
                ]
        );

        $this->start_controls_tabs(
                'oxi-accordions-gen-start-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', 'accordions-or-faqs'),
                        'active' => esc_html__('Active', 'accordions-or-faqs'),
                    ]
                ]
        );

        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-gen-bg', $this->style, [
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the background of the all bodies on normal mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-gen-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body' => ''
                    ],
                    'description' => 'Customize border of each accordions. Set type, width, and color.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-gen-boxshadow', $this->style, [
            'type' => Controls::BOXSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body' => ''
            ],
            'description' => 'Add one or more shadows into each section and customize other box-shadow options.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-gen-ac-bg', $this->style, [
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the background of the accordions on active mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-gen-hover-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body' => ''
                    ],
                    'description' => 'Customize border of the accotdions. Set type, width, and color.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-gen-hover-boxshadow', $this->style, [
            'type' => Controls::BOXSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body' => '',
            ],
            'description' => 'Add one or more shadows into accordions section and customize other box-shadow options.',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-general-border-radius', $this->style, [
            'label' => esc_html__('Border Radius', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
            ],
            'description' => 'Add rounded corners to the header’s section.',
                ]
        );

        $this->add_responsive_control(
                'oxi-accordions-general-margin', $this->style, [
            'label' => esc_html__('Margin', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some apace at outside each accordions.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_heading_parent() {
        //Heading Section
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-accordions-start-tabs' => 'heading-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_header_general();
        $this->register_header_expand_collapse_icon();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_header_title();

        $this->register_header_sub_title();
        $this->register_header_additional();
        $this->end_section_devider();
        $this->end_section_tabs();
    }

    public function register_header_general() {
        $this->start_controls_section(
                'oxi-accordions-head-expand-collapse-icon-head', [
            'label' => esc_html__('Header General', 'accordions-or-faqs'),
            'showing' => TRUE,
                ]
        );

        $this->start_controls_tabs(
                'oxi-accordions-head-start-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', 'accordions-or-faqs'),
                        'active' => esc_html__('Active', 'accordions-or-faqs'),
                    ]
                ]
        );

        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-bg', $this->style, [
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the background of the header on normal mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body' => ''
                    ],
                    'description' => 'Customize border of the header. Set type, width, and color.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-ac-bg', $this->style, [
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the background of the header on active mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-hover-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body' => ''
                    ],
                    'description' => 'Customize border of the header. Set type, width, and color.',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
                'oxi-accordions-head-boxshadow', $this->style, [
            'type' => Controls::BOXSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body' => '',
            ],
            'description' => 'Add one or more shadows into header section and customize other box-shadow options.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-radius', $this->style, [
            'label' => esc_html__('Border Radius', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the header’s section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-padding', $this->style, [
            'label' => esc_html__('Padding', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some space inside of the header section.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_expand_collapse_icon() {
        $this->start_controls_section(
                'oxi-accordions-head-expand-collapse-icon', [
            'label' => esc_html__('Expand & Collapse Icon', 'accordions-or-faqs'),
            'showing' => true,
            'condition' => [
                'oxi-accordions-expand-collapse' => 'oxi-accordions-expand-collapse-active',
            ],
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-position', $this->style, [
            'label' => esc_html__('Location', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'loader' => TRUE,
            'label_on' => esc_html__('Outside', 'accordions-or-faqs'),
            'label_off' => esc_html__('Inside', 'accordions-or-faqs'),
            'return_value' => 'oxi-accordions-head-expand-collapse-position-outside',
            'description' => 'Set the location of expand & collapse as beside title or Outside Title',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-expand-collapse-location', $this->style, [
            'label' => esc_html__('Right or Left', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'label_on' => esc_html__('Right', 'accordions-or-faqs'),
            'label_off' => esc_html__('Left', 'accordions-or-faqs'),
            'return_value' => 'oxi-accordions-head-expand-collapse-right-position',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-single-card' => '',
            ],
            'description' => 'Set the location of expand & collapse.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-type', $this->style, [
            'label' => esc_html__('Expand & Collapse Type', 'accordions-or-faqs'),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 'oxi-accordions-head-expand-collapse-type-icon',
            'options' => [
                'oxi-accordions-head-expand-collapse-type-icon' => [
                    'title' => esc_html__('Icon', 'accordions-or-faqs'),
                ],
                'oxi-accordions-head-expand-collapse-type-number' => [
                    'title' => esc_html__('Number', 'accordions-or-faqs'),
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-expand-collapse' => '',
            ],
            'description' => 'Choose a expand and collapse icon style.',
                ]
        );
        $this->add_control(
                'oxi-accordions-icon-style', $this->style, [
            'label' => esc_html__('Icon Style', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-icon',
            ],
            'options' => [
                'angle' => esc_html__('angle', 'accordions-or-faqs'),
                'angle-2' => esc_html__('Agnle 2', 'accordions-or-faqs'),
                'angle-3' => esc_html__('angle-3', 'accordions-or-faqs'),
                'angle-double' => esc_html__('angle-double', 'accordions-or-faqs'),
                'angle-double-down' => esc_html__('angle-double-down', 'accordions-or-faqs'),
                'angle-double-up' => esc_html__('angle-double-up', 'accordions-or-faqs'),
                'arrow' => esc_html__('arrow', 'accordions-or-faqs'),
                'arrow-up' => esc_html__('arrow-up', 'accordions-or-faqs'),
                'carret' => esc_html__('carret', 'accordions-or-faqs'),
                'carret-up' => esc_html__('carret-up', 'accordions-or-faqs'),
                'chevron' => esc_html__('chevron', 'accordions-or-faqs'),
                'hand' => esc_html__('hand', 'accordions-or-faqs'),
                'plus' => esc_html__('plus', 'accordions-or-faqs'),
                'tick' => esc_html__('tick', 'accordions-or-faqs'),
                'custom' => esc_html__('custom', 'accordions-or-faqs'),
            ],
            'description' => 'Choose a expand and collapse icon style.',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-expand-icon', $this->style, [
            'label' => esc_html__('Expand Icon', 'accordions-or-faqs'),
            'type' => Controls::ICON,
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-icon',
                'oxi-accordions-icon-style' => 'custom',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-single-card .oxi-accordions-expand-icon .oxi-icons' => '',
            ],
            'description' => 'Select expand icon from font awesome icon list panel.',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-collapse-icon', $this->style, [
            'label' => esc_html__('Collapse Icon', 'accordions-or-faqs'),
            'type' => Controls::ICON,
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-icon',
                'oxi-accordions-icon-style' => 'custom',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-single-card .oxi-accordions-collapse-icon .oxi-icons' => '',
            ],
            'description' => 'Select collapse icon from font awesome icon list panel.',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-start-number', $this->style, [
            'label' => esc_html__('Starting Number', 'accordions-or-faqs'),
            'type' => Controls::NUMBER,
            'default' => '0',
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-number',
            ],
            'description' => 'Set Frist Number of Collapse as 1 will increase automaticlly.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-interface',
                $this->style,
                [
                    'label' => esc_html__('Customization Interface', 'accordions-or-faqs'),
                    'type' => Controls::CHOOSE,
                    'operator' => Controls::OPERATOR_TEXT,
                    'loader' => TRUE,
                    'default' => '',
                    'options' => [
                        '' => [
                            'title' => esc_html__('Simple', 'accordions-or-faqs'),
                        ],
                        'oxi-accordions-head-expand-collapse-icon-customizable-interface' => [
                            'title' => esc_html__('Customizable', 'accordions-or-faqs'),
                        ],
                    ],
                    'selector' => [
                        '{{WRAPPER}} .oxi-accordions-expand-collapse' => '',
                    ],
                    'description' => 'Set the icon customization interface either simple or fully customizable.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-width', $this->style, [
            'label' => esc_html__('Width & Height', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'oxi-accordions-head-expand-collapse-icon-customizable-interface',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-head-expand-collapse-icon-customizable-interface' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon’s Width and Height.',
                ]
        );

        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-size', $this->style, [
            'label' => esc_html__('Icon Size', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 20,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-icon',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-expand-collapse .oxi-icons' => 'font-size:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon Size (PX, % or EM).',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-expand-collapse-number-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-expand-collapse .oxi-accordions-expand-collapse-number' => '',
            ],
            'condition' => [
                'oxi-accordions-head-expand-collapse-type' => 'oxi-accordions-head-expand-collapse-type-number',
            ],
            'description' => 'Customize the Typography options for the Number.',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-expand-collapse-shape', $this->style, [
            'label' => esc_html__('Backgroud Shape', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'oxi-accordions-head-expand-collapse-icon-customizable-interface',
            ],
            'options' => [
                'oxi-accordion-expand-0' => esc_html__('Basic', 'accordions-or-faqs'),
                'oxi-accordion-expand-1' => esc_html__('Shape 01', 'accordions-or-faqs'),
                'oxi-accordion-expand-2' => esc_html__('Shape 02', 'accordions-or-faqs'),
                'oxi-accordion-expand-3' => esc_html__('Shape 03', 'accordions-or-faqs'),
                'oxi-accordion-expand-4' => esc_html__('Shape 04', 'accordions-or-faqs'),
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-head-expand-collapse-icon-customizable-interface' => '',
            ],
            'description' => 'Choose a expand and collapse icon style.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-head-expand-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', 'accordions-or-faqs'),
                        'active' => esc_html__('Active', 'accordions-or-faqs'),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-expand-collapse .oxi-icons' => 'color: {{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-expand-collapse .oxi-accordions-expand-collapse-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Icon or Number Color on Normal Mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'oxi-accordions-head-expand-collapse-icon-customizable-interface',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-head-expand-collapse-icon-customizable-interface:before' => 'background: {{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-head-expand-collapse-icon-customizable-interface:after' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize Icon Background with Color, Gradient or Image properties for Normal Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-expand-collapse-icon-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'oxi-accordions-head-expand-collapse-icon-customizable-interface',
                'oxi-accordions-head-expand-collapse-shape' => 'oxi-accordion-expand-0',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordion-expand-0.oxi-accordions-head-expand-collapse-icon-customizable-interface:before' => '',
            ],
            'description' => 'Customize Border of the Icon. Set Type, Width, and Color.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-ac-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-expand-collapse .oxi-icons' => 'color: {{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-expand-collapse .oxi-accordions-expand-collapse-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Icon’s Color on Active Mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-expand-collapse-icon-ac-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'oxi-accordions-head-expand-collapse-icon-customizable-interface',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-head-expand-collapse-icon-customizable-interface:before' => 'background: {{VALUE}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-head-expand-collapse-icon-customizable-interface:after' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize Icon Background with Color, Gradient or Image properties for Active Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-expand-collapse-icon-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'oxi-accordions-head-expand-collapse-icon-customizable-interface',
                'oxi-accordions-head-expand-collapse-shape' => 'oxi-accordion-expand-0',
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style .oxi-accordions-single-card.oxi-accordions-expand .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordion-expand-0.oxi-accordions-head-expand-collapse-icon-customizable-interface:before' => '',
            ],
            'description' => 'Customize Border of the Icon. Set Type, Width, and Color for Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-border-radius', $this->style, [
            'label' => esc_html__('Border Radius', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-accordions-head-expand-collapse-icon-interface' => 'oxi-accordions-head-expand-collapse-icon-customizable-interface',
                'oxi-accordions-head-expand-collapse-shape' => 'oxi-accordion-expand-0',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordion-expand-0.oxi-accordions-head-expand-collapse-icon-customizable-interface' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordion-expand-0.oxi-accordions-head-expand-collapse-icon-customizable-interface:before' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Icon’s  Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-expand-collapse-icon-margin', $this->style, [
            'label' => esc_html__('Margin', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-expand-collapse-' . $this->oxiid . '.oxi-accordions-expand-collapse ' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Icon.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_header_title() {
        $this->start_controls_section(
                'oxi-accordions-head-title', [
            'label' => esc_html__('Title Settings', 'accordions-or-faqs'),
            'showing' => TRUE,
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-title-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => '',
            ],
            'description' => 'Customize the typography options for the accordions’s title.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-head-title-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', 'accordions-or-faqs'),
                        'active' => esc_html__('Active', 'accordions-or-faqs'),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-title-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the title color on normal mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-title-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => '',
            ],
            'description' => 'Add one or more shadows into title texts and customize other text-shadow options.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-title-ac-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the title color on active mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-title-ac-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body .oxi-accordions-main-title' => '',
            ],
            'description' => 'Add one or more shadows into title texts and customize other text-shadow options.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-accordions-head-title-margin', $this->style, [
            'label' => esc_html__('Margin', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-body  .oxi-accordions-main-title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some space outside of the title.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_sub_title() {
        $this->start_controls_section(
                'oxi-accordions-head-sub-title', [
            'label' => esc_html__('Sub Title Settings', 'accordions-or-faqs'),
            'showing' => true,
            'condition' => [
                'oxi-accordions-content-type' => 'content'
            ],
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-sub-title-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-sub-title' => '',
            ],
            'description' => 'Customize the typography options for the accordions’s sub title.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-accordions-head-sub-title-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', 'accordions-or-faqs'),
                        'active' => esc_html__('Active', 'accordions-or-faqs'),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-sub-title-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-sub-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the sub title color on normal mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-sub-title-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-sub-title' => '',
            ],
            'description' => 'Add one or more shadows into sub title texts and customize other text-thadow options.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-sub-title-ac-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-sub-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the sub title color on active mode.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-head-sub-title-ac-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-sub-title' => '',
            ],
            'description' => 'Add one or more shadows into sub title texts and customize other text-shadow options.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-accordions-head-sub-title-margin', $this->style, [
            'label' => esc_html__('Margin', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-sub-title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some space outside of the sub title.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_additional() {
        $this->start_controls_section(
                'oxi-accordions-head-additional', [
            'label' => esc_html__('Additional Settings', 'accordions-or-faqs'),
            'showing' => true,
            'condition' => [
                'oxi-accordions-content-type' => 'content',
                'oxi-accordions-headding-additional' => 'oxi-accordions-headding-additional-active'
            ],
                ]
        );

        $this->add_control(
                'oxi-accordions-head-additional-location', $this->style, [
            'label' => esc_html__('Title Additional Location', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'label_on' => esc_html__('Right', 'accordions-or-faqs'),
            'label_off' => esc_html__('Left', 'accordions-or-faqs'),
            'return_value' => 'oxi-accordions-header-additional-right-position',
            'selector' => [
                '{{WRAPPER}} .oxi-accordions-header-card > .oxi-accordions-header-body .oxi-accordions-header-content' => '',
            ],
            'description' => 'Set the Location of Title’s Additionals (Icon, Image, or Number.',
                ]
        );

        $this->add_control(
                'oxi-accordions-head-additional-interface',
                $this->style,
                [
                    'label' => esc_html__('Customization Interface', 'accordions-or-faqs'),
                    'type' => Controls::CHOOSE,
                    'operator' => Controls::OPERATOR_TEXT,
                    'loader' => TRUE,
                    'default' => '',
                    'options' => [
                        '' => [
                            'title' => esc_html__('Simple', 'accordions-or-faqs'),
                        ],
                        'oxi-accordions-head-additional-customizable-interface' => [
                            'title' => esc_html__('Customizable', 'accordions-or-faqs'),
                        ],
                    ],
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-additional-icon' => '',
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-li-number' => '',
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-li-image' => '',
                    ],
                    'description' => 'Set Title’s additionals customization interface either simple or fully customizable.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-additional-width', $this->style, [
            'label' => esc_html__('Width', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-additional-interface' => 'oxi-accordions-head-additional-customizable-interface',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-additional-icon.oxi-accordions-head-additional-customizable-interface' => 'width:{{SIZE}}{{UNIT}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-li-number.oxi-accordions-head-additional-customizable-interface' => 'width:{{SIZE}}{{UNIT}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-li-image.oxi-accordions-head-additional-customizable-interface' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set width of Title’s additionals. Works at all bodies .',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-additional-height', $this->style, [
            'label' => esc_html__('Height', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-head-additional-interface' => 'oxi-accordions-head-additional-customizable-interface',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-additional-icon.oxi-accordions-head-additional-customizable-interface' => 'height:{{SIZE}}{{UNIT}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-li-number.oxi-accordions-head-additional-customizable-interface' => 'height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set Height of Title’s additionals. Works at icon and number.',
                ]
        );

        $this->add_responsive_control(
                'oxi-accordions-head-additional-size', $this->style, [
            'label' => esc_html__('Icon Size', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 20,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-icons' => 'font-size:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the icon size (PX, % or EM).',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-additional-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-li-number' => '',
            ],
            'description' => 'Customize the typography options for the numbers.',
                ]
        );

        $this->start_controls_tabs(
                'oxi-accordions-head-additional-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', 'accordions-or-faqs'),
                        'active' => esc_html__('Active', 'accordions-or-faqs'),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-head-additional-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-icons' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the icon or number color on normal mode, works for icon or number',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-additional-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'condition' => [
                'oxi-accordions-head-additional-interface' => 'oxi-accordions-head-additional-customizable-interface',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon.oxi-accordions-head-additional-customizable-interface' => 'background: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-header-li-number.oxi-accordions-head-additional-customizable-interface' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize icon or number background with color, gradient or image properties for normal mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-additional-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-additional-interface' => 'oxi-accordions-head-additional-customizable-interface',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon.oxi-accordions-head-additional-customizable-interface' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-header-li-number.oxi-accordions-head-additional-customizable-interface' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-header-li-image.oxi-accordions-head-additional-customizable-interface' => '',
            ],
            'description' => 'Customize border for all bodies including image.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-head-additional-ac-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-icons' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-li-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the icon or number color on active mode.',
                ]
        );
        $this->add_control(
                'oxi-accordions-head-additional-ac-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'condition' => [
                'oxi-accordions-head-additional-interface' => 'oxi-accordions-head-additional-customizable-interface',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon.oxi-accordions-head-additional-customizable-interface' => 'background: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-header-li-number.oxi-accordions-head-additional-customizable-interface' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize icon or number background with color, gradient or image properties for active mode.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-head-additional-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-accordions-head-additional-interface' => 'oxi-accordions-head-additional-customizable-interface',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon.oxi-accordions-head-additional-customizable-interface' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-header-li-number.oxi-accordions-head-additional-customizable-interface' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card.oxi-accordions-expand > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-header-li-image.oxi-accordions-head-additional-customizable-interface' => '',
            ],
            'description' => 'Customize border for all bodies incluging image. Set type, width, and color for active mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-accordions-head-additional-border-radius', $this->style, [
            'label' => esc_html__('Border Radius', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-accordions-head-additional-interface' => 'oxi-accordions-head-additional-customizable-interface',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content  .oxi-accordions-additional-icon.oxi-accordions-head-additional-customizable-interface' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content  .oxi-accordions-header-li-number.oxi-accordions-head-additional-customizable-interface' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content  .oxi-accordions-header-li-image.oxi-accordions-head-additional-customizable-interface' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the all addional bodies section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-head-additional-margin', $this->style, [
            'label' => esc_html__('Margin', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-additional-icon' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-header-li-number' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-header-card .oxi-accordions-header-content .oxi-accordions-header-li-image' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some space outside of title additional bodies.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_description_parent() {
        //Description Section
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-accordions-start-tabs' => 'description-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_desc_general();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_desc_content();
        $this->register_content_height();
        $this->end_section_devider();
        $this->end_section_tabs();
    }

    public function register_desc_general() {
        $this->start_controls_section(
                'oxi-accordions-desc-general', [
            'label' => esc_html__('General Settings', 'accordions-or-faqs'),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-general-bg', $this->style, [
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize the content’s background with color, gradient or image properties.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-general-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card' => ''
                    ],
                    'description' => 'Customize border of the content body. Set type, width, and color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-general-radius', $this->style, [
            'label' => esc_html__('Border Radius', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the content’s section.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-animation', $this->style, [
            'label' => esc_html__('Animation', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                'optgroup0' => [true, 'Attention Seekers'],
                '' => esc_html__('None', 'accordions-or-faqs'),
                'optgroup1' => [false],
                'optgroup2' => [true, 'Attention Seekers'],
                'animate__bounce' => esc_html__('Bounce', 'accordions-or-faqs'),
                'animate__flash' => esc_html__('Flash', 'accordions-or-faqs'),
                'animate__pulse' => esc_html__('Pulse', 'accordions-or-faqs'),
                'animate__rubberBand' => esc_html__('RubberBand', 'accordions-or-faqs'),
                'animate__shakeX' => esc_html__('ShakeX', 'accordions-or-faqs'),
                'animate__shakeY' => esc_html__('ShakeY', 'accordions-or-faqs'),
                'animate__headShake' => esc_html__('HeadShake', 'accordions-or-faqs'),
                'animate__swing' => esc_html__('Swing', 'accordions-or-faqs'),
                'animate__tada' => esc_html__('Tada', 'accordions-or-faqs'),
                'animate__wobble' => esc_html__('Wobble', 'accordions-or-faqs'),
                'animate__jello' => esc_html__('Jello', 'accordions-or-faqs'),
                'animate__heartBeat' => esc_html__('HeartBeat', 'accordions-or-faqs'),
                'optgroup3' => [false],
                'optgroup4' => [true, 'Back Entrances'],
                'animate__backInDown' => esc_html__('BackInDown', 'accordions-or-faqs'),
                'animate__backInLeft' => esc_html__('BackInLeft', 'accordions-or-faqs'),
                'animate__backInRight' => esc_html__('BackInRight', 'accordions-or-faqs'),
                'animate__backInUp' => esc_html__('BackInUp', 'accordions-or-faqs'),
                'optgroup5' => [false],
                'optgroup6' => [true, 'Bouncing Entrances'],
                'animate__bounceIn' => esc_html__('BounceIn', 'accordions-or-faqs'),
                'animate__bounceInDown' => esc_html__('BounceInDown', 'accordions-or-faqs'),
                'animate__bounceInLeft' => esc_html__('BounceInLeft', 'accordions-or-faqs'),
                'animate__bounceInRight' => esc_html__('BounceInRight', 'accordions-or-faqs'),
                'animate__bounceInUp' => esc_html__('BounceInUp', 'accordions-or-faqs'),
                'optgroup7' => [false],
                'optgroup8' => [true, 'Fading Entrances'],
                'animate__fadeIn' => esc_html__('FadeIn', 'accordions-or-faqs'),
                'animate__fadeInDown' => esc_html__('FadeInDown', 'accordions-or-faqs'),
                'animate__fadeInDownBig' => esc_html__('FadeInDownBig', 'accordions-or-faqs'),
                'animate__fadeInLeft' => esc_html__('FadeInLeft', 'accordions-or-faqs'),
                'animate__fadeInLeftBig' => esc_html__('FadeInLeftBig', 'accordions-or-faqs'),
                'animate__fadeInRight' => esc_html__('FadeInRight', 'accordions-or-faqs'),
                'animate__fadeInRightBig' => esc_html__('FadeInRightBig', 'accordions-or-faqs'),
                'animate__fadeInUp' => esc_html__('FadeInUp', 'accordions-or-faqs'),
                'animate__fadeInUpBig' => esc_html__('FadeInUpBig', 'accordions-or-faqs'),
                'animate__fadeInTopLeft' => esc_html__('FadeInTopLeft', 'accordions-or-faqs'),
                'animate__fadeInTopRight' => esc_html__('FadeInTopRight', 'accordions-or-faqs'),
                'animate__fadeInBottomLeft' => esc_html__('FadeInBottomLeft', 'accordions-or-faqs'),
                'animate__fadeInBottomRight' => esc_html__('FadeInBottomRight', 'accordions-or-faqs'),
                'optgroup9' => [false],
                'optgroup10' => [true, 'Flippers'],
                'animate__flip' => esc_html__('Flip', 'accordions-or-faqs'),
                'animate__flipInX' => esc_html__('FlipInX', 'accordions-or-faqs'),
                'animate__flipInY' => esc_html__('FlipInY', 'accordions-or-faqs'),
                'optgroup11' => [false],
                'optgroup12' => [true, 'Lightspeed'],
                'animate__lightSpeedInRight' => esc_html__('LightSpeedInRight', 'accordions-or-faqs'),
                'animate__lightSpeedInLeft' => esc_html__('LightSpeedInLeft', 'accordions-or-faqs'),
                'optgroup13' => [false],
                'optgroup14' => [true, 'Rotating Entrances'],
                'animate__rotateIn' => esc_html__('RotateIn', 'accordions-or-faqs'),
                'animate__rotateInDownLeft' => esc_html__('RotateInDownLeft', 'accordions-or-faqs'),
                'animate__rotateInDownRight' => esc_html__('RotateInDownRight', 'accordions-or-faqs'),
                'animate__rotateInUpLeft' => esc_html__('RotateInUpLeft', 'accordions-or-faqs'),
                'animate__rotateInUpRight' => esc_html__('RotateInUpRight', 'accordions-or-faqs'),
                'optgroup14' => [false],
                'optgroup16' => [true, 'Sliding Entrances'],
                'animate__slideInUp' => esc_html__('SlideInUp', 'accordions-or-faqs'),
                'animate__slideInDown' => esc_html__('SlideInDown', 'accordions-or-faqs'),
                'animate__slideInLeft' => esc_html__('SlideInLeft', 'accordions-or-faqs'),
                'animate__slideInRight' => esc_html__('SlideInRight', 'accordions-or-faqs'),
                'optgroup17' => [false],
                'optgroup18' => [true, 'Zoom Entrances'],
                'animate__zoomIn' => esc_html__('ZoomIn', 'accordions-or-faqs'),
                'animate__zoomInDown' => esc_html__('ZoomInDown', 'accordions-or-faqs'),
                'animate__zoomInLeft' => esc_html__('ZoomInLeft', 'accordions-or-faqs'),
                'animate__zoomInRight' => esc_html__('ZoomInRight', 'accordions-or-faqs'),
                'animate__zoomInUp' => esc_html__('ZoomInUp', 'accordions-or-faqs'),
                'optgroup19' => [false],
                'optgroup20' => [true, 'Specials'],
                'animate__hinge' => esc_html__('Hinge', 'accordions-or-faqs'),
                'animate__jackInTheBox' => esc_html__('JackInTheBox', 'accordions-or-faqs'),
                'animate__rollIn' => esc_html__('RollIn', 'accordions-or-faqs'),
                'optgroup21' => [false],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body' => '',
            ]
                ]
        );

        $this->add_control(
                'oxi-accordions-content-animation-duration', $this->style, [
            'label' => esc_html__('Animation Duration', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-accordions-desc-animation' => 'EMPTY',
            ],
            'default' => [
                'unit' => 'ms',
                'size' => '',
            ],
            'range' => [
                'ms' => [
                    'min' => 100,
                    'max' => 7000,
                    'step' => 1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body' => ' -webkit-animation-duration: {{SIZE}}{{UNIT}};animation-duration: {{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set content on off animation durations as mili seconds.',
                ]
        );

        $this->add_responsive_control(
                'oxi-accordions-desc-general-padding', $this->style, [
            'label' => esc_html__('Padding', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some space around the content body including background color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-general-margin', $this->style, [
            'label' => esc_html__('Margin', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some space outside of the content body.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_desc_content() {
        $this->start_controls_section(
                'oxi-accordions-desc-content', [
            'label' => esc_html__('Content Settings', 'accordions-or-faqs'),
            'showing' => TRUE,
                ]
        );

        $this->add_group_control(
                'oxi-accordions-desc-content-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > p' => '',
            ],
            'description' => 'Customize the Typography options for the accordions’s contents.',
                ]
        );
        $this->add_control(
                'oxi-accordions-desc-content-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > p' => 'color: {{VALUE}};',
            ],
            'description' => 'Select content color of your accordions.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-desc-content-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > p' => '',
            ],
            'description' => 'Add  shadows into the content texts and customize other text-shadow options.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-desc-content-padding', $this->style, [
            'label' => esc_html__('Content Padding', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > p' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Adjust your content padding for peragraph tag.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_content_height() {
        $this->start_controls_section(
                'oxi-accordions-content-height', [
            'label' => esc_html__('Fixed Content Height', 'accordions-or-faqs'),
            'showing' => TRUE,
            'condition' => [
                'oxi-accordions-content-height' => 'oxi-accordions-content-height',
            ],
                ]
        );
        $this->add_control(
                'oxi-accordions-content-mx-height-interface',
                $this->style,
                [
                    'label' => esc_html__('Interface', 'accordions-or-faqs'),
                    'type' => Controls::CHOOSE,
                    'operator' => Controls::OPERATOR_TEXT,
                    'loader' => TRUE,
                    'default' => '',
                    'options' => [
                        '' => [
                            'title' => esc_html__('Scroll', 'accordions-or-faqs'),
                        ],
                        'oxi-accordions-content-mx-height-interface-button' => [
                            'title' => esc_html__('Button', 'accordions-or-faqs'),
                        ],
                    ],
                    'selector' => [
                        '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card .oxi-accordions-content-body' => '',
                    ],
                    'description' => 'Set Title’s additionals customization interface either simple or fully customizable.',
                ]
        );

        $this->add_responsive_control(
                'oxi-accordions-content-mx-height', $this->style, [
            'label' => esc_html__('Maximum Height', 'accordions-or-faqs'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style .oxi-accordions-content-body.oxi-accordions-content-height' => 'max-height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set fixed accordion content panel height.',
                ]
        );
        $this->add_control(
                'oxi-accordions-content-mx-height-expand-text', $this->style, [
            'label' => esc_html__('Expand Text', 'accordions-or-faqs'),
            'type' => Controls::TEXT,
            'default' => 'Lorem Ipsum',
            'condition' => [
                'oxi-accordions-content-mx-height-interface' => 'oxi-accordions-content-mx-height-interface-button',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-content-expand-open' => '',
            ],
            'description' => 'Customize your Expand Button text.',
                ]
        );
        $this->add_control(
                'oxi-accordions-content-mx-height-collapse-text', $this->style, [
            'label' => esc_html__('Collapse Text', 'accordions-or-faqs'),
            'type' => Controls::TEXT,
            'default' => 'Close',
            'condition' => [
                'oxi-accordions-content-mx-height-interface' => 'oxi-accordions-content-mx-height-interface-button',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card .oxi-accordions-content-expand-close' => '',
            ],
            'description' => 'Customize your Expand Button text.',
                ]
        );
        $this->add_control(
                'oxi-accordions-content-mx-height-alignment', $this->style, [
            'label' => esc_html__('Alignment', 'accordions-or-faqs'),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_ICON,
            'default' => 'left',
            'condition' => [
                'oxi-accordions-content-mx-height-interface' => 'oxi-accordions-content-mx-height-interface-button',
            ],
            'options' => [
                'left' => [
                    'icon' => esc_html__('fas fa-align-left', 'accordions-or-faqs'),
                ],
                'center' => [
                    'icon' => esc_html__('fas fa-align-center', 'accordions-or-faqs'),
                ],
                'right' => [
                    'icon' => esc_html__('fas fa-align-right', 'accordions-or-faqs'),
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button' => 'text-align: {{VALUE}};',
            ],
            'description' => 'Confirm Expand or Collapse alignment as left, center or right',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-content-mx-height-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'condition' => [
                'oxi-accordions-content-mx-height-interface' => 'oxi-accordions-content-mx-height-interface-button',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button' => '',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button i' => '',
            ],
            'description' => 'Customize the typography options for the numbers.',
                ]
        );

        $this->start_controls_tabs(
                'oxi-accordions-content-mx-height-tabs',
                [
                    'options' => [
                        'expand' => esc_html__('Expand ', 'accordions-or-faqs'),
                        'collapse' => esc_html__('Collapse', 'accordions-or-faqs'),
                    ],
                    'condition' => [
                        'oxi-accordions-content-mx-height-interface' => 'oxi-accordions-content-mx-height-interface-button',
                    ],
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-accordions-content-mx-height-expand-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-open' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-open i' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the expand color.',
                ]
        );
        $this->add_control(
                'oxi-accordions-content-mx-height-expand-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize expand background with color, gradient or image properties.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-content-mx-height-expand-border', $this->style, [
            'type' => Controls::BORDER,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-body' => '',
            ],
            'description' => 'Customize border for expand body.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-accordions-content-mx-height-collapse-color', $this->style, [
            'label' => esc_html__('Color', 'accordions-or-faqs'),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-close' => 'color: {{VALUE}};',
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-close i' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the collapse color.',
                ]
        );
        $this->add_control(
                'oxi-accordions-content-mx-height-collapse-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => esc_html__('Background', 'accordions-or-faqs'),
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body.oxi-button-expand > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-body' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize collapse background with color, gradient or image properties.',
                ]
        );

        $this->add_group_control(
                'oxi-accordions-content-mx-height-collapse-border', $this->style, [
            'type' => Controls::BORDER,
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body.oxi-button-expand > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-body' => '',
            ],
            'description' => 'Customize border for collapse. Set type, width, and color for active mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-accordions-content-mx-height-radius', $this->style, [
            'label' => esc_html__('Border Radius', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-accordions-content-mx-height-interface' => 'oxi-accordions-content-mx-height-interface-button',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button .oxi-accordions-content-expand-body' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the expand or collapse bodies.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-content-mx-height-padding', $this->style, [
            'label' => esc_html__('Padding', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-accordions-content-mx-height-interface' => 'oxi-accordions-content-mx-height-interface-button',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button > .oxi-accordions-content-expand-body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Adjust your padding for expand or collapse.',
                ]
        );
        $this->add_responsive_control(
                'oxi-accordions-content-mx-height-margin', $this->style, [
            'label' => esc_html__('Margin', 'accordions-or-faqs'),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-accordions-content-mx-height-interface' => 'oxi-accordions-content-mx-height-interface-button',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-accordions-ultimate-style > .oxi-accordions-single-card > .oxi-accordions-head-outside-body > .oxi-accordions-content-card > .oxi-accordions-content-body > .oxi-accordions-content-expand-button' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Adjust your margin for expand or collapse.',
                ]
        );
        $this->end_controls_section();
    }
     public function modal_form_data() {
        echo '<div class="modal-header">
                    <h4 class="modal-title">Accordions Modal Form</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">';
        $this->add_control(
                'oxi-accordions-modal-default', [], [
            'label' => esc_html__('Default Open', 'accordions-or-faqs'),
            'type' => Controls::SWITCHER,
            'label_on' => esc_html__('Yes', 'accordions-or-faqs'),
            'label_off' => esc_html__('No', 'accordions-or-faqs'),
            'return_value' => 'yes',
            'description' => 'Expand this accordion on page load.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-title', [], [
            'label' => esc_html__('Title', 'accordions-or-faqs'),
            'type' => Controls::TEXT,
            'default' => 'Lorem Ipsum',
            'description' => 'Add Title of your Accordions else Make it Blank.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-sub-title', [], [
            'label' => esc_html__('Sub Title', 'accordions-or-faqs'),
            'type' => Controls::TEXT,
            'description' => 'Add Sub Title of your Accordions else Make it Blank.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-title-additional', [], [
            'label' => esc_html__('Title Additional', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'condition' => [
                'oxi-accordions-headding-additional' => 'oxi-accordions-headding-additional-active',
            ],
            'options' => [
                '' => esc_html__('None', 'accordions-or-faqs'),
                'icon' => esc_html__('Icon', 'accordions-or-faqs'),
                'number' => esc_html__('Number', 'accordions-or-faqs'),
                'image' => esc_html__('Image', 'accordions-or-faqs'),
            ],
            'description' => 'Add the Additional elements beside the Accordions’s Title (Icon, Number or Image).',
                ]
        );

        $this->add_control(
                'oxi-accordions-modal-icon', [], [
            'label' => esc_html__('Icon', 'accordions-or-faqs'),
            'type' => Controls::ICON,
            'default' => 'fab fa-facebook-f',
            'condition' => [
                'oxi-accordions-modal-title-additional' => 'icon',
            ],
            'description' => 'Select Icon from Font Awesome Icon list Panel.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-number', [], [
            'label' => esc_html__('Number', 'accordions-or-faqs'),
            'type' => Controls::NUMBER,
            'default' => 1,
            'condition' => [
                'oxi-accordions-modal-title-additional' => 'number',
            ],
            'description' => 'Write the Number as Title Additionals.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-modal-image', [],
                [
                    'label' => esc_html__('Image', 'accordions-or-faqs'),
                    'type' => Controls::MEDIA,
                    'condition' => [
                        'oxi-accordions-modal-title-additional' => 'image',
                    ],
                    'description' => 'Add an Image from Media Library or Input a custom Image URL.'
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-components-type', [], [
            'label' => esc_html__('Choose Components', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => 'wysiwyg',
            'options' => [
                'wysiwyg' => esc_html__('WYSIWYG Editor', 'accordions-or-faqs'),
                'nested-accordions' => esc_html__('Nested Accordions', 'accordions-or-faqs'),
                'link' => esc_html__('Custom Link', 'accordions-or-faqs'),
            ],
            'description' => 'Se the accordions’s Content type as Content or Custom Link.',
                ]
        );
        $this->add_group_control(
                'oxi-accordions-modal-link', [], [
            'label' => esc_html__('Link', 'accordions-or-faqs'),
            'type' => Controls::URL,
            'condition' => [
                'oxi-accordions-modal-components-type' => 'link',
            ],
            'description' => 'Add Custom link with opening type.',
                ]
        );
        $this->add_control(
                'oxi-accordions-modal-desc', [], [
            'label' => esc_html__('Description', 'accordions-or-faqs'),
            'type' => Controls::WYSIWYG,
            'default' => '',
            'condition' => [
                'oxi-accordions-modal-components-type' => 'wysiwyg',
            ],
            'description' => 'Add your Tab’s Description.',
                ]
        );

        $this->add_control(
                'oxi-accordions-modal-nested-accordions', [], [
            'label' => esc_html__('Select Accordions', 'accordions-or-faqs'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => $this->Get_Nested_Accordions,
            'condition' => [
                'oxi-accordions-modal-components-type' => 'nested-accordions',
            ],
            'description' => 'Select Accordions to Create Nested.',
                ]
        );

        echo '</div>';
    }

    public function register_custom_parent() {
        ///Custom CSS
        $this->start_section_tabs(
                'oxi-accordions-start-tabs', [
            'condition' => [
                'oxi-accordions-start-tabs' => 'custom'
            ],
            'padding' => '10px'
                ]
        );

        $this->start_controls_section(
                'oxi-accordions-start-tabs-css', [
            'label' => esc_html__('Custom CSS', 'accordions-or-faqs'),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-accordions-custom-css', $this->style, [
            'label' => esc_html__('', 'accordions-or-faqs'),
            'type' => Controls::TEXTAREA,
            'default' => '',
            'description' => 'Custom CSS Section. You can add custom css into textarea.'
                ]
        );
        $this->end_controls_section();
        $this->end_section_tabs();
    }

   

}
