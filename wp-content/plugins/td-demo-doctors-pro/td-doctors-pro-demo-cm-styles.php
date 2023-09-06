<?php
/**
 * Doctors Pro Demo Plugin Custom Modules & Styles Class
 */

class td_doctors_pro_demo_cm_styles {

	var $td_demo_cmstyles_path = '';
	var $td_demo_cmstyles_url = '';

    function __construct( $plugin_path, $plugin_url ) {

		$this->td_demo_cmstyles_path = $plugin_path;
		$this->td_demo_cmstyles_url = $plugin_url;

        // Hook used to add or modify items via API, called early for registering styles
        add_action( 'td_global_after', function() {
            self::register_styles();
        }, 9 );

        // Hook used to add or modify items via API
        add_action( 'td_global_after', function() {

            /**
             * Modules
             */
            td_api_module::add( 'tds_doctors_pro_module1',
                array(
                    'file'                         => $this->td_demo_cmstyles_path . '/includes/modules/tds_doctors_pro_module1.php',
                    'text'                         => 'Doctors Module 1',
                    'img'                          => '',
                    'used_on_blocks'               => array(),
                    'excerpt_title'                => 25,
                    'excerpt_content'              => 25,
                    'enabled_excerpt_in_panel'     => false,
                    'enabled_on_more_articles_box' => false,
                    'enabled_on_loops'             => false,
                    'uses_columns'                 => false,
                    // if the module uses columns on the page template + loop
                    'category_label'               => false,
                    'class'                        => 'td_module_wrap td-animation-stack',
                    'group'                        => ''
                    // '' - main theme, 'mob' - mobile theme, 'woo' - woo theme
                )
            );
        });
	}

    /*
     * Modules styles
     */
    public function register_styles() {
        td_api_style::add( 'tds_doctors_pro_module_style1', array(
                'group' => 'tds_module_loop_style',
                'title' => 'Doctors PRO Module 1',
                'file' => $this->td_demo_cmstyles_path . '/includes/styles/tds_module_loop_style/tds_doctors_pro_module_style1.php',
                'params' => array_merge(
                    array(
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'General',
                            "value"      => "",
                            "class"      => "",
                            "group"      => "Module",
                        ),
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Layout',
                            "value"      => "",
                            "class"      => "tdc-separator-small",
                            "group"      => "Module",
                        ),
                        array(
                            "param_name"  => "modules_on_row",
                            "type"        => "dropdown-responsive",
                            "value"       => array(
                                '1'  => '100%',
                                '2'  => '50%',
                                '3'  => '33.33333333%',
                                '4'  => '25%',
                                '5'  => '20%',
                                '6'  => '16.66666667%',
                                '7'  => '14.28571428%',
                                '8'  => '12.5%',
                                '9'  => '11.11111111%',
                                '10' => '10%',
                            ),
                            "heading"     => 'Modules per row',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-dropdown-small",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/layout_modules_per_row.png",
                        ),
                        array(
                            "param_name"  => "modules_gap",
                            "type"        => "textfield-responsive",
                            "value"       => '48',
                            "heading"     => 'Modules gap',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-small",
                            "placeholder" => "40",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/layout_modules_gap.png",
                        ),
                        array(
                            "param_name"  => "m_padding",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Modules padding',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/layout_modules_padding.png",
                        ),
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Border',
                            "value"      => "",
                            "class"      => "tdc-separator-small",
                            "group"      => "Module",
                        ),
                        array(
                            "param_name"  => "modules_border_size",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Border width',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/layout_border_width.png",
                        ),
                        array(
                            "param_name"  => "modules_border_style",
                            "type"        => "dropdown",
                            "value"       => array(
                                'Solid'  => '',
                                'Dotted' => 'dotted',
                                'Dashed' => 'dashed',
                            ),
                            "heading"     => 'Border style',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-dropdown-big",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/module_border_style.png",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "",
                            "heading"     => 'Border color',
                            "param_name"  => "modules_border_color",
                            "value"       => '#eaeaea',
                            "description" => '',
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/module_border_color.png",
                        ),
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Image',
                            "value"      => "",
                            "class"      => "tdc-separator-small",
                            "group"      => "Module",
                        ),
                        array(
                            "param_name"  => "image_height",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Image height (percent)',
                            "description" => "Default value in percent",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-small",
                            "placeholder" => "50",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/layout_image_height.png",
                        ),
                        array(
                            "param_name"  => "image_width",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Image width (0-100 percent)',
                            "description" => "Default value in percent",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-small",
                            "placeholder" => "100",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/layout_image_width.png",
                        ),
                        array(
                            "param_name"  => "image_size",
                            "type"        => "dropdown",
                            "value"       => array(
                                'Medium - Default - 696x0px'  => '',
                                '-- [No crop] --' => '__',
                                'XSmall - 150x0px' => 'td_150x0',
                                'Small - 300x0px' => 'td_300x0',
                                'Large - 1068x0px' => 'td_1068x0',
                                'Full - 1920x0px'  => 'td_1920x0',
                                '-- [Other sizes] --' => '__',
                                '218x150px' => 'td_218x150',
                                '324x400px'  => 'td_324x400',
                                '485x360' => 'td_485x360'
                            ),
                            "heading"     => 'Image size',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-dropdown-big",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/module_image_size.png",
                        ),
                        array(
                            "param_name"  => "image_radius",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Image radius',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-small",
                            "placeholder" => "0",
                            "group"       => "Module",
                            "info_img" => "https://cloud.tagdiv.com/help/layout_image_radius.png",
                        ),
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Title',
                            "value"      => "",
                            "class"      => "tdc-separator-small",
                            "group"      => "Module",
                        ),
                        array(
                            "param_name" => "mc1_title_tag",
                            "type" => "dropdown",
                            "value" => array(
                                'Default - H3' => '',
                                'H1' => 'h1',
                                'H2' => 'h2',
                                'H4' => 'h4',
                                'Paragraph' => 'p'
                            ),
                            "heading" => 'Title tag (SEO)',
                            "description" => "",
                            "holder" => "div",
                            "class" => "tdc-dropdown-big",
                            "info_img" => "https://cloud.tagdiv.com/help/module_title_seo.png",
                            "group" => "Module"
                        ),

                        array(
                            "param_name"  => "mc1_tl",
                            "type"        => "textfield",
                            "value"       => '',
                            "heading"     => 'Title length',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-small",
                            "placeholder" => '25',
                            "info_img" => "https://cloud.tagdiv.com/help/title_length.png",
                            "group" => "Module"
                        ),
                    ),
                    td_config_helper::mix_blend('Module'),
                    td_config_helper::image_filters(),
                    td_config::get_map_exclusive_label_array(),
                    array(
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Doctors PRO',
                            "value"      => "",
                            "class"      => "",
                            "group"      => "Module",
                        ),
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Categories/Tags',
                            "value"      => "",
                            "class"      => "tdc-separator-small",
                            "group"      => "Module",
                        ),
                        array(
                            "param_name"  => "categories",
                            "type"        => "textfield",
                            "value"       => '',
                            "heading"     => 'Categories/Tags',
                            "description" => "Comma separated slugs",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-extrabig",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name"  => "cat_limit",
                            "type"        => "textfield",
                            "value"       => '3',
                            "heading"     => 'Categories/Tags limit',
                            "description" => "Limit the categories/tags displayed",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-small",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Paddings and Margins',
                            "value"      => "",
                            "class"      => "tdc-separator-small",
                            "group"      => "Module",
                        ),
                        array(
                            "param_name"  => "td_doctors_pro_meta_info_container1_margin",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Meta Info Margin',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name"  => "td_doctors_pro_review_score1_margin",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Review Margin',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name"  => "td_doctors_pro_title1_margin",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Title Margin',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name"  => "td_doctors_pro_specialty1_margin",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Specialty Margin',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name"  => "td_doctors_pro_location_wrap1_padding",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Location Padding',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name"  => "td_doctors_pro_price_wrap1_margin",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Price Margin',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name"  => "td_doctors_pro_price_wrap1_padding",
                            "type"        => "textfield-responsive",
                            "value"       => '',
                            "heading"     => 'Price Padding',
                            "description" => "",
                            "holder"      => "div",
                            "class"       => "tdc-textfield-big",
                            "placeholder" => "0px 0px 0px 0px",
                            "group"       => "Module",
                        ),
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Fonts',
                            "value"      => "",
                            "class"      => "tdc-separator-small",
                            "group"      => "Module",
                        )
                    ),
                    td_config_helper::get_map_block_font_array( 'f_review_score_doctors_pro1', false, 'Review Score', "Module", '', '', '', '' ),
                    td_config_helper::get_map_block_font_array( 'f_art_title_doctors_pro1', true, 'Article Title', "Module", '', '', '', '' ),
                    td_config_helper::get_map_block_font_array( 'f_cat_specialty1', false, 'Specialty Text', "Module", '', '', '', '' ),
                    td_config_helper::get_map_block_font_array( 'f_cat_location1', false, 'Location Text', "Module", '', '', '', '' ),
                    td_config_helper::get_map_block_font_array( 'f_price_tag_doctors_pro1', false, 'Price Text', "Module", '', '', '', '' ),
                    array(
                        array(
                            "param_name" => "separator",
                            "type"       => "text_separator",
                            'heading'    => 'Colors',
                            "value"      => "",
                            "class"      => "tdc-separator-small",
                            "group"      => "Module",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "",
                            "heading"     => 'Average Score',
                            "param_name"  => "td_doctors_pro_review_score1_color",
                            "value"       => '#B6BCC1',
                            "description" => '',
                            "group"       => "Module",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "td-colorpicker-double-a",
                            "heading"     => 'Title Color',
                            "param_name"  => "td_doctors_pro_title1_color",
                            "value"       => '#3A454E',
                            "description" => '',
                            "group"       => "Module",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "td-colorpicker-double-b",
                            "heading"     => 'Title Color Hover',
                            "param_name"  => "td_doctors_pro_title1_color_hover",
                            "value"       => '#00CFAA',
                            "description" => '',
                            "group"       => "Module",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "td-colorpicker-double-a",
                            "heading"     => 'Specialty Color',
                            "param_name"  => "td_doctors_pro_specialty1_color",
                            "value"       => '',
                            "description" => '',
                            "group"       => "Module",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "td-colorpicker-double-b",
                            "heading"     => 'Specialty Color Hover',
                            "param_name"  => "td_doctors_pro_specialty1_color_hover",
                            "value"       => '',
                            "description" => '',
                            "group"       => "Module",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "td-colorpicker-double-a",
                            "heading"     => 'Location Color',
                            "param_name"  => "td_doctors_pro_location1_color",
                            "value"       => '',
                            "description" => '',
                            "group"       => "Module",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "td-colorpicker-double-b",
                            "heading"     => 'Location Color Hover',
                            "param_name"  => "td_doctors_pro_location1_color_hover",
                            "value"       => '',
                            "description" => '',
                            "group"       => "Module",
                        ),
                        array(
                            "type"        => "colorpicker",
                            "holder"      => "div",
                            "class"       => "",
                            "heading"     => 'Price Text Color',
                            "param_name"  => "td_doctors_pro_price_text1_color",
                            "value"       => '#eaeaea',
                            "description" => '',
                            "group"       => "Module",
                        )
                    )
                )
            )
        );
    }
}

