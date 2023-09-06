<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 13.07.2017
 * Time: 9:38
 */

class tds_doctors_pro_module_style1 extends td_style {

	private $unique_block_class;
    private $unique_style_class;
	private $atts = array();
	private $index_style;

	function __construct( $atts, $unique_block_class = '', $index_style = '') {
		$this->atts = $atts;
		$this->unique_block_class = $unique_block_class;
		$this->index_style = $index_style;
	}

	private function get_css() {

        $compiled_css = '';

        $unique_style_class = $this->unique_style_class;

        $in_composer = td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax();
        $in_element = td_global::get_in_element();
        $unique_block_class_prefix = '';
        if( $in_element || $in_composer ) {
            $unique_block_class_prefix = 'tdc-row .';

            if( $in_element && $in_composer ) {
                $unique_block_class_prefix = 'tdc-row-composer .';
            }
        }
        $unique_block_class = $unique_block_class_prefix . $this->unique_block_class;

        $unique_block_modal_class = $this->unique_block_class . '_m';


		$raw_css =
			"<style>
			
                /* @style_specific_tds_doctors_pro_module_style1 */
                               
                .tds_doctors_pro_module_style1 .page-nav {
                    padding: 0 20px;
                }
                .td-module-container.td-doctors-pro-wrap1 {
                    display: flex;
                    flex-direction: row !important;
                }
                .td-doctors-pro-meta-info-container1 {
                    display: flex;
                    flex-direction: column;
                    flex-wrap: nowrap;
                    justify-content: center;
                    flex: 1;
                }
                .td-doctors-pro-bottom-wrap1 {
                    display: flex;
                    flex-direction: row;
                    flex-wrap: nowrap;
                    align-items: stretch;
                }
                .td-doctors-pro-review-score1 {
                    display: flex;
                    aligh-items: center;
                }
                .td-doctors-pro-review-score1 .td-user-rev-stars {
                    margin-right: 8px;
                }
                .td-doctors-pro-review-score1 .td-user-rev-star {
                    font-size: 11px;
                    margin-right: 2px;
                }
                .td-doctors-pro-review-score1 .td-user-rev-star-half,
                .td-doctors-pro-review-score1 .td-user-rev-star-full {
                    color: var(--doc-custom-color-2);
                }
                .tdc-wm-custom-svg-icon svg path {
                    stroke: var(--doc-custom-color-2);
                }
                .td-doctors-pro-location-wrap1,
                .td-doctors-pro-price-wrap1 {
                    text-align: center;
                    width: 50%;
                    border-radius: 6px;
                    background-color: #e6ebea;
                }
                .td-doctors-pro-location-wrap1 .td-doctors-pro-location1 a {
                    display: block;
                }
                .td-doctors-pro-image-container1 .entry-thumb {
                    -webkit-box-shadow: 0px 16px 24px -16px rgba(0, 33, 33, 0.32);
                    box-shadow: 0px 16px 24px -16px rgba(0, 33, 33, 0.32);
                }
                .td-doctors-pro-location-wrap1 svg,
                .td-doctors-pro-price-wrap1 svg {
                    min-height: 25px;
                }
                
                
                .td_flex_block_6.td-flex-doctors-module.tds_doctors_pro_module_style1 .td_block_inner {
                    display: block !important;
                }
                .td_flex_block_6.td-flex-doctors-module .td-module-container.td-doctors-pro-wrap1 {
                    display: flex;
                    flex-direction: column !important;
                }
                                
			
                /* @image_height */
				.$unique_block_class .td-image-wrap {
					padding-bottom: @image_height;
				}
				/* @image_width */
				.$unique_block_class .td-doctors-pro-image-container1 {
				 	flex: 0 0 @image_width;
				 	width: @image_width;
			    }
				/* @image_radius */
				.$unique_block_class .entry-thumb,
				.$unique_block_class .entry-thumb:before,
				.$unique_block_class .entry-thumb:after {
					border-radius: @image_radius;
				}			

			    /* @mix_type */
                html:not([class*='ie']) .$unique_block_class .entry-thumb:before {
                    content: '';
                    width: 100%;
                    height: 100%;
                    position: absolute;
                    top: 0;
                    left: 0;
                    opacity: 1;
                    transition: opacity 1s ease;
                    -webkit-transition: opacity 1s ease;
                    mix-blend-mode: @mix_type;
                }
                /* @color */
                html:not([class*='ie']) .$unique_block_class .entry-thumb:before {
                    background: @color;
                }
                /* @mix_gradient */
                html:not([class*='ie']) .$unique_block_class .entry-thumb:before {
                    @mix_gradient;
                }
                /* @mix_type_h */
                @media (min-width: 1141px) {
                    html:not([class*='ie']) .$unique_block_class .entry-thumb:after {
                        content: '';
                        width: 100%;
                        height: 100%;
                        position: absolute;
                        top: 0;
                        left: 0;
                        opacity: 0;
                        transition: opacity 1s ease;
                        -webkit-transition: opacity 1s ease;
                        mix-blend-mode: @mix_type_h;
                    }
                    html:not([class*='ie']) .$unique_block_class .td-module-container:hover .entry-thumb:after {
                        opacity: 1;
                    }
                }
                /* @color_h */
                html:not([class*='ie']) .$unique_block_class .entry-thumb:after {
                    background: @color_h;
                }
                /* @mix_gradient_h */
                html:not([class*='ie']) .$unique_block_class .entry-thumb:after {
                    @mix_gradient_h;
                }
                /* @mix_type_off */
                html:not([class*='ie']) .$unique_block_class .td-module-container:hover .entry-thumb:before {
                    opacity: 0;
                }
                /* @effect_on */
                html:not([class*='ie']) .$unique_block_class .entry-thumb {
                    filter: @fe_brightness @fe_contrast @fe_saturate;
                    transition: all 1s ease;
                    -webkit-transition: all 1s ease;
                }
                /* @effect_on_h */
                @media (min-width: 1141px) {
                    html:not([class*='ie']) .$unique_block_class .td-module-container:hover .entry-thumb {
                        filter: @fe_brightness_h @fe_contrast_h @fe_saturate_h;
                    }
                }               
			
				/* @modules_on_row */
				.$unique_block_class .td_module_wrap:not(.tdb_module_rec) {
					width: @modules_on_row;
				}
				
				/* @modules_gap */
				.$unique_block_class .td_module_wrap {
					padding-left: @modules_gap;
					padding-right: @modules_gap;
				}
				/* @m_padding */
				.$unique_block_class .td-module-container {
					padding: @m_padding;
				}
				/* @modules_border_size */
				.$unique_block_class .td_module_wrap {
				    border-width: @modules_border_size;
				    border-style: solid;
				    border-color: #000;
				}
				/* @modules_border_style */
				.$unique_block_class .td_module_wrap {
				    border-style: @modules_border_style;
				}
				/* @modules_border_color */
				.$unique_block_class .td_module_wrap {
				    border-color: @modules_border_color;
				}
				.$unique_block_class .td_module_wrap:last-child {
				    border: none;
				}
                .$unique_block_class .tds_doctors_pro_module1 {
                padding-bottom: 0;
                }
                                
                /* @excl_show */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    display: @excl_show;
                }
                /* @excl_txt */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    content: '@excl_txt';
                }
                /* @excl_margin */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    margin: @excl_margin;
                }
                /* @excl_padd */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    padding: @excl_padd;
                }
                /* @all_excl_border */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    border: @all_excl_border @all_excl_border_style @all_excl_border_color;
                }
                /* @excl_radius */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    border-radius: @excl_radius;
                }
                
                /* @excl_color */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    color: @excl_color;
                }
                /* @excl_color_h */
                .$unique_block_class .td-module-exclusive:hover .td-module-title a:before {
                    color: @excl_color_h;
                }
                /* @excl_bg */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    background-color: @excl_bg;
                }
                /* @excl_bg_h */
                .$unique_block_class .td-module-exclusive:hover .td-module-title a:before {
                    background-color: @excl_bg_h;
                }
                /* @excl_border_color_h */
                .$unique_block_class .td-module-exclusive:hover .td-module-title a:before {
                    border-color: @excl_border_color_h;
                }
                
                /* @f_excl */
                .$unique_block_class .td-module-exclusive .td-module-title a:before {
                    @f_excl
                }
                
                
                /* margins & paddings here */
                               
                /* @td_doctors_pro_meta_info_container1_margin */
                .$unique_block_class .td-doctors-pro-meta-info-container1 {
                    margin: @td_doctors_pro_meta_info_container1_margin;
                }
                
                /* @td_doctors_pro_review_score1_margin */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-review-score1 {
                    margin: @td_doctors_pro_review_score1_margin;
                }
                
                /* @td_doctors_pro_title1_margin */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-title1 .entry-title {
                    margin: @td_doctors_pro_title1_margin;
                }
                               
                /* @td_doctors_pro_specialty1_margin */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-specialty1 {
                    margin: @td_doctors_pro_specialty1_margin;
                }
                
                 /* @td_doctors_pro_location_wrap1_padding */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-location-wrap1 {
                    padding: @td_doctors_pro_location_wrap1_padding;
                }
                
                /* @td_doctors_pro_price_wrap1_margin */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-price-wrap1 {
                    margin: @td_doctors_pro_price_wrap1_margin;
                }
                
                /* @td_doctors_pro_price_wrap1_padding */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-price-wrap1 {
                    padding: @td_doctors_pro_price_wrap1_padding;
                }
                
                                
                /* fonts here */
                                
                /* @f_review_score_doctors_pro1 */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-review-score1 span {
                    @f_review_score_doctors_pro1;
                }
                /* @f_art_title_doctors_pro1 */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-title1 .entry-title {
                    @f_art_title_doctors_pro1;
                }
                /* @f_cat_specialty1 */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-specialty1 a {
                    @f_cat_specialty1;
                }
                /* @f_cat_location1 */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-location1 a {
                    @f_cat_location1;
                }
                /* @f_price_tag_doctors_pro1 */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-price-text1 {
                    @f_price_tag_doctors_pro1;
                }
                
                
                /* colors here */
                
                /* @td_doctors_pro_review_score1_color */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-review-score1 span {
                    color: @td_doctors_pro_review_score1_color;
                }
                
                /* @td_doctors_pro_title1_color */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-title1 .entry-title > a {
                    color: @td_doctors_pro_title1_color;
                }
                
                /* @td_doctors_pro_title1_color_hover */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-title1 .entry-title a:hover {
                    color: @td_doctors_pro_title1_color_hover;
                }
                
                /* @td_doctors_pro_specialty1_color */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-specialty1 a {
                    color: @td_doctors_pro_specialty1_color;
                }
                                
                /* @td_doctors_pro_specialty1_color_hover */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-specialty1 a:hover {
                    color: @td_doctors_pro_specialty1_color_hover;
                }
                
                /* @td_doctors_pro_location1_color */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-location1 a {
                    color: @td_doctors_pro_location1_color;
                }
                                
                /* @td_doctors_pro_location1_color_hover */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-location1 a:hover {
                    color: @td_doctors_pro_location1_color_hover;
                }
              
                /* @td_doctors_pro_price_text1_color */
                .$unique_block_class .td-doctors-pro-meta-info-container1 .td-doctors-pro-price-text1 {
                    color: @td_doctors_pro_price_text1_color;
                }
                				
			</style>";


        $td_css_res_compiler = new td_css_res_compiler( $raw_css );
        $td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->atts);

        $compiled_css .= $td_css_res_compiler->compile_css();
		return $compiled_css;
	}

    /**
     * Callback pe media
     *
     * @param $responsive_context td_res_context
     * @param $atts
     */
    static function cssMedia( $res_ctx ) {

        $res_ctx->load_settings_raw( 'style_specific_tds_doctors_pro_module_style1', 1 );

        /*-- GENERAL -- */
        // modules per row
        $modules_on_row = $res_ctx->get_style_att('modules_on_row', __CLASS__);
        $res_ctx->load_settings_raw( 'modules_on_row', $modules_on_row );
        if ( $modules_on_row == '' ) {
            $modules_on_row = '100%';
        }

        // modules clearfix
        $clearfix = 'clearfix';
        $padding = 'padding';
        if ( $res_ctx->is( 'all' ) ) {
            $clearfix = 'clearfix_desktop';
            $padding = 'padding_desktop';
        }
        switch ($modules_on_row) {
            case '100%':
                $res_ctx->load_settings_raw( $padding,  '1' );
                break;
            case '50%':
                $res_ctx->load_settings_raw( $clearfix,  '2n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+2' );
                break;
            case '33.33333333%':
                $res_ctx->load_settings_raw( $clearfix,  '3n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+3' );
                break;
            case '25%':
                $res_ctx->load_settings_raw( $clearfix,  '4n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+4' );
                break;
            case '20%':
                $res_ctx->load_settings_raw( $clearfix,  '5n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+5' );
                break;
            case '16.66666667%':
                $res_ctx->load_settings_raw( $clearfix,  '6n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+6' );
                break;
            case '14.28571428%':
                $res_ctx->load_settings_raw( $clearfix,  '7n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+7' );
                break;
            case '12.5%':
                $res_ctx->load_settings_raw( $clearfix,  '8n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+8' );
                break;
            case '11.11111111%':
                $res_ctx->load_settings_raw( $clearfix,  '9n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+9' );
                break;
            case '10%':
                $res_ctx->load_settings_raw( $clearfix,  '10n+1' );
                $res_ctx->load_settings_raw( $padding,  '-n+10' );
                break;
        }

        // modules gap
        $modules_gap = $res_ctx->get_style_att('modules_gap', __CLASS__);
        $res_ctx->load_settings_raw( 'modules_gap', $modules_gap );
        if ( $modules_gap == '' ) {
            $res_ctx->load_settings_raw( 'modules_gap', '24px');
        } else if ( is_numeric( $modules_gap ) ) {
            $res_ctx->load_settings_raw( 'modules_gap', $modules_gap / 2 .'px' );
        }

        // modules padding
        $m_padding = $res_ctx->get_style_att('m_padding', __CLASS__);
        $res_ctx->load_settings_raw( 'm_padding', $m_padding );
        if ( is_numeric( $m_padding ) ) {
            $res_ctx->load_settings_raw( 'm_padding', $m_padding . 'px' );
        }

        // modules border size
        $modules_border_size = $res_ctx->get_style_att('modules_border_size', __CLASS__);
        $res_ctx->load_settings_raw( 'modules_border_size', $modules_border_size );
        if( $modules_border_size != '' && is_numeric( $modules_border_size ) ) {
            $res_ctx->load_settings_raw( 'modules_border_size', $modules_border_size . 'px' );
        }
        // modules border style
        $res_ctx->load_settings_raw( 'modules_border_style', $res_ctx->get_style_att('modules_border_style', __CLASS__) );
        $res_ctx->load_settings_raw( 'modules_border_color', $res_ctx->get_style_att('modules_border_color', __CLASS__) );

        // image_height
        $image_height = $res_ctx->get_style_att('image_height', __CLASS__);
        if ( is_numeric( $image_height ) ) {
            $res_ctx->load_settings_raw( 'image_height', $image_height . '%' );
        } else {
            $res_ctx->load_settings_raw( 'image_height', $image_height );
        }
        // image_width
        $image_width = $res_ctx->get_style_att('image_width', __CLASS__);
        if ( is_numeric( $image_width ) ) {
            $res_ctx->load_settings_raw( 'image_width', $image_width . '%' );
        } else {
            $res_ctx->load_settings_raw( 'image_width', $image_width );
        }

        // image radius
        $image_radius = $res_ctx->get_style_att('image_radius', __CLASS__);
        $res_ctx->load_settings_raw( 'image_radius', $image_radius );
        if ( is_numeric( $image_radius ) ) {
            $res_ctx->load_settings_raw( 'image_radius', $image_radius . 'px' );
        }


        // image mix blend
        $mix_type = $res_ctx->get_style_att('mix_type', __CLASS__);
        if ( $mix_type != '' ) {
            $res_ctx->load_settings_raw('mix_type', $res_ctx->get_style_att('mix_type', __CLASS__));
        }
        $res_ctx->load_color_settings( 'mix_color', 'color', 'mix_gradient', '', '', __CLASS__ );

        $mix_type_h = $res_ctx->get_style_att('mix_type_h', __CLASS__);
        if ( $mix_type_h != '' ) {
            $res_ctx->load_settings_raw('mix_type_h', $res_ctx->get_style_att('mix_type_h', __CLASS__));
        } else {
            $res_ctx->load_settings_raw('mix_type_off', 1);
        }
        $res_ctx->load_color_settings( 'mix_color_h', 'color_h', 'mix_gradient_h', '', '', __CLASS__ );

        // image effects
        $res_ctx->load_settings_raw('fe_brightness', 'brightness(1)');
        $res_ctx->load_settings_raw('fe_contrast', 'contrast(1)');
        $res_ctx->load_settings_raw('fe_saturate', 'saturate(1)');

        $fe_brightness = $res_ctx->get_style_att('fe_brightness', __CLASS__);
        if ($fe_brightness != '1') {
            $res_ctx->load_settings_raw('fe_brightness', 'brightness(' . $fe_brightness . ')');
            $res_ctx->load_settings_raw('effect_on', 1);
        }
        $fe_contrast = $res_ctx->get_style_att('fe_contrast', __CLASS__);
        if ($fe_contrast != '1') {
            $res_ctx->load_settings_raw('fe_contrast', 'contrast(' . $fe_contrast . ')');
            $res_ctx->load_settings_raw('effect_on', 1);
        }
        $fe_saturate = $res_ctx->get_style_att('fe_saturate', __CLASS__);
        if ($fe_saturate != '1') {
            $res_ctx->load_settings_raw('fe_saturate', 'saturate(' . $fe_saturate . ')');
            $res_ctx->load_settings_raw('effect_on', 1);
        }

        // image effects hover
        $res_ctx->load_settings_raw('fe_brightness_h', 'brightness(1)');
        $res_ctx->load_settings_raw('fe_contrast_h', 'contrast(1)');
        $res_ctx->load_settings_raw('fe_saturate_h', 'saturate(1)');

        $fe_brightness_h = $res_ctx->get_style_att('fe_brightness_h', __CLASS__);
        $fe_contrast_h = $res_ctx->get_style_att('fe_contrast_h', __CLASS__);
        $fe_saturate_h = $res_ctx->get_style_att('fe_saturate_h', __CLASS__);

        if ($fe_brightness_h != '1') {
            $res_ctx->load_settings_raw('fe_brightness_h', 'brightness(' . $fe_brightness_h . ')');
            $res_ctx->load_settings_raw('effect_on_h', 1);
        }
        if ($fe_contrast_h != '1') {
            $res_ctx->load_settings_raw('fe_contrast_h', 'contrast(' . $fe_contrast_h . ')');
            $res_ctx->load_settings_raw('effect_on_h', 1);
        }
        if ($fe_saturate_h != '1') {
            $res_ctx->load_settings_raw('fe_saturate_h', 'saturate(' . $fe_saturate_h . ')');
            $res_ctx->load_settings_raw('effect_on_h', 1);
        }
        // make hover to work
        if ($fe_brightness_h != '1' || $fe_contrast_h != '1' || $fe_saturate_h != '1') {
            $res_ctx->load_settings_raw('effect_on', 1);
        }
        if ($fe_brightness != '1' || $fe_contrast != '1' || $fe_saturate != '1') {
            $res_ctx->load_settings_raw('effect_on_h', 1);
        }

        // stuff
        /*-- EXCLUSIVE LABEL -- */
        if( !empty( has_filter('td_composer_map_exclusive_label_array', 'td_subscription::add_exclusive_label_settings') ) ) {
            // *- layout -* //
            // show exclusive label
            $excl_show = $res_ctx->get_style_att('excl_show', __CLASS__);
            $res_ctx->load_settings_raw( 'excl_show', $excl_show );
            if( $excl_show == '' ) {
                $res_ctx->load_settings_raw( 'excl_show', 'inline-block' );
            }

            // exclusive label text
            $res_ctx->load_settings_raw( 'excl_txt', $res_ctx->get_style_att('excl_txt', __CLASS__) );

            // exclusive label margin
            $excl_margin = $res_ctx->get_style_att('excl_margin', __CLASS__);
            $res_ctx->load_settings_raw( 'excl_margin', $excl_margin );
            if( $excl_margin != '' && is_numeric( $excl_margin ) ) {
                $res_ctx->load_settings_raw( 'excl_margin', $excl_margin . 'px' );
            }

            // exclusive label padding
            $excl_padd = $res_ctx->get_style_att('excl_padd', __CLASS__);
            $res_ctx->load_settings_raw( 'excl_padd', $excl_padd );
            if( $excl_padd != '' && is_numeric( $excl_padd ) ) {
                $res_ctx->load_settings_raw( 'excl_padd', $excl_padd . 'px' );
            }

            // exclusive label border size
            $excl_border = $res_ctx->get_style_att('all_excl_border', __CLASS__);
            $res_ctx->load_settings_raw( 'all_excl_border', $excl_border );
            if( $excl_border != '' && is_numeric( $excl_border ) ) {
                $res_ctx->load_settings_raw( 'all_excl_border', $excl_border . 'px' );
            }
            // exclusive label border style
            $res_ctx->load_settings_raw( 'all_excl_border_style', $res_ctx->get_style_att('all_excl_border_style', __CLASS__) );
            // exclusive label border radius
            $excl_radius = $res_ctx->get_style_att('excl_radius', __CLASS__);
            $res_ctx->load_settings_raw( 'excl_radius', $excl_radius );
            if( $excl_radius != '' && is_numeric( $excl_radius ) ) {
                $res_ctx->load_settings_raw( 'excl_radius', $excl_radius . 'px' );
            }

            // *- colors -* //
            $res_ctx->load_settings_raw( 'excl_color', $res_ctx->get_style_att('excl_color', __CLASS__) );
            $res_ctx->load_settings_raw( 'excl_color_h', $res_ctx->get_style_att('excl_color_h', __CLASS__) );
            $res_ctx->load_settings_raw( 'excl_bg', $res_ctx->get_style_att('excl_bg', __CLASS__) );
            $res_ctx->load_settings_raw( 'excl_bg_h', $res_ctx->get_style_att('excl_bg_h', __CLASS__) );
            $excl_border_color = $res_ctx->get_style_att('all_excl_border_color', __CLASS__);
            $res_ctx->load_settings_raw( 'all_excl_border_color', $excl_border_color );
            if( $excl_border_color == '' ) {
                $res_ctx->load_settings_raw( 'all_excl_border_color', '#000' );
            }
            $res_ctx->load_settings_raw( 'excl_border_color_h', $res_ctx->get_style_att('excl_border_color_h', __CLASS__) );


            // *- fonts -* //
            $res_ctx->load_font_settings( 'f_excl', __CLASS__ );
        }

        // margin container meta info
        $td_doctors_pro_meta_info_container1_margin = $res_ctx->get_style_att('td_doctors_pro_meta_info_container1_margin', __CLASS__);
        $res_ctx->load_settings_raw( 'td_doctors_pro_meta_info_container1_margin', $td_doctors_pro_meta_info_container1_margin );
        if( $td_doctors_pro_meta_info_container1_margin != '' && is_numeric( $td_doctors_pro_meta_info_container1_margin ) ) {
            $res_ctx->load_settings_raw( 'td_doctors_pro_meta_info_container1_margin', $td_doctors_pro_meta_info_container1_margin . 'px' );
        }
        // review score name margins
        $td_doctors_pro_review_score1_margin = $res_ctx->get_style_att('td_doctors_pro_review_score1_margin', __CLASS__);
        $res_ctx->load_settings_raw( 'td_doctors_pro_review_score1_margin', $td_doctors_pro_review_score1_margin );
        if( $td_doctors_pro_review_score1_margin != '' && is_numeric( $td_doctors_pro_review_score1_margin ) ) {
            $res_ctx->load_settings_raw( 'td_doctors_pro_review_score1_margin', $td_doctors_pro_review_score1_margin . 'px' );
        }
        // title margins
        $td_doctors_pro_title1_margin = $res_ctx->get_style_att('td_doctors_pro_title1_margin', __CLASS__);
        $res_ctx->load_settings_raw( 'td_doctors_pro_title1_margin', $td_doctors_pro_title1_margin );
        if( $td_doctors_pro_title1_margin != '' && is_numeric( $td_doctors_pro_title1_margin ) ) {
            $res_ctx->load_settings_raw( 'td_doctors_pro_title1_margin', $td_doctors_pro_title1_margin . 'px' );
        }

        // specialty margin
        $td_doctors_pro_specialty1_margin = $res_ctx->get_style_att('td_doctors_pro_specialty1_margin', __CLASS__);
        $res_ctx->load_settings_raw( 'td_doctors_pro_specialty1_margin', $td_doctors_pro_specialty1_margin );
        if ( is_numeric( $td_doctors_pro_specialty1_margin ) ) {
            $res_ctx->load_settings_raw( 'td_doctors_pro_specialty1_margin', $td_doctors_pro_specialty1_margin . 'px' );
        }
        // location padding
        $td_doctors_pro_location_wrap1_padding = $res_ctx->get_style_att('td_doctors_pro_location_wrap1_padding', __CLASS__);
        $res_ctx->load_settings_raw( 'td_doctors_pro_location_wrap1_padding', $td_doctors_pro_location_wrap1_padding );
        if ( is_numeric( $td_doctors_pro_location_wrap1_padding ) ) {
            $res_ctx->load_settings_raw( 'td_doctors_pro_location_wrap1_padding', $td_doctors_pro_location_wrap1_padding . 'px' );
        }
        // price wrap margin
        $td_doctors_pro_price_wrap1_margin = $res_ctx->get_style_att('td_doctors_pro_price_wrap1_margin', __CLASS__);
        $res_ctx->load_settings_raw( 'td_doctors_pro_price_wrap1_margin', $td_doctors_pro_price_wrap1_margin );
        if ( is_numeric( $td_doctors_pro_price_wrap1_margin ) ) {
            $res_ctx->load_settings_raw( 'td_doctors_pro_price_wrap1_margin', $td_doctors_pro_price_wrap1_margin . 'px' );
        }
        // price wrap padding
        $td_doctors_pro_price_wrap1_padding = $res_ctx->get_style_att('td_doctors_pro_price_wrap1_padding', __CLASS__);
        $res_ctx->load_settings_raw( 'td_doctors_pro_price_wrap1_padding', $td_doctors_pro_price_wrap1_padding );
        if ( is_numeric( $td_doctors_pro_price_wrap1_padding ) ) {
            $res_ctx->load_settings_raw( 'td_doctors_pro_price_wrap1_padding', $td_doctors_pro_price_wrap1_padding . 'px' );
        }

        //fonts for doctors_pro
        $res_ctx->load_font_settings( 'f_review_score_doctors_pro1', __CLASS__ );
        $res_ctx->load_font_settings( 'f_art_title_doctors_pro1', __CLASS__ );
        $res_ctx->load_font_settings( 'f_cat_specialty1', __CLASS__ );
        $res_ctx->load_font_settings( 'f_cat_location1', __CLASS__ );
        $res_ctx->load_font_settings( 'f_price_tag_doctors_pro1', __CLASS__ );


        //colors
        $res_ctx->load_settings_raw( 'td_doctors_pro_review_score1_color', $res_ctx->get_style_att('td_doctors_pro_review_score1_color', __CLASS__) );
        $res_ctx->load_settings_raw( 'td_doctors_pro_title1_color', $res_ctx->get_style_att('td_doctors_pro_title1_color', __CLASS__) );
        $res_ctx->load_settings_raw( 'td_doctors_pro_title1_color_hover', $res_ctx->get_style_att('td_doctors_pro_title1_color_hover', __CLASS__) );
        $res_ctx->load_settings_raw( 'td_doctors_pro_specialty1_color', $res_ctx->get_style_att('td_doctors_pro_specialty1_color', __CLASS__) );
        $res_ctx->load_settings_raw( 'td_doctors_pro_specialty1_color_hover', $res_ctx->get_style_att('td_doctors_pro_specialty1_color_hover', __CLASS__) );
        $res_ctx->load_settings_raw( 'td_doctors_pro_location1_color', $res_ctx->get_style_att('td_doctors_pro_location1_color', __CLASS__) );
        $res_ctx->load_settings_raw( 'td_doctors_pro_location1_color_hover', $res_ctx->get_style_att('td_doctors_pro_location1_color_hover', __CLASS__) );
        $res_ctx->load_settings_raw( 'td_doctors_pro_price_text1_color', $res_ctx->get_style_att('td_doctors_pro_price_text1_color', __CLASS__) );
    }

	function render( $index_style = '' ) {
		if ( ! empty( $index_style ) ) {
			$this->index_style = $index_style;
		}
        $this->unique_style_class = td_global::td_generate_unique_id();

		return $this->get_style($this->get_css());
	}

	function get_style_att( $att_name ) {
		return $this->get_att( $att_name ,__CLASS__, $this->index_style );
	}

	function get_atts() {
		return $this->atts;
	}
}
