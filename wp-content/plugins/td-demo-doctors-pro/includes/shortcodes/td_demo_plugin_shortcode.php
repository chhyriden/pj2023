<?php

/**
 * Class td_demo_plugin
 */

class td_demo_plugin_shortcode extends td_block {

	public function get_custom_css() {

        // $unique_block_class
        $unique_block_class = $this->block_uid;

		$compiled_css = '';

		/** @noinspection CssInvalidAtRule */
		$raw_css = "<style></style>";

		$td_css_res_compiler = new td_css_res_compiler( $raw_css );
		$td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->get_all_atts() );

		$compiled_css .= $td_css_res_compiler->compile_css();

		return $compiled_css;

	}

	static function cssMedia( $res_ctx ) {

        $res_ctx->load_settings_raw( 'td_demo_plugin_shortcode', 1 );

	}

	function __construct() {
		parent::disable_loop_block_features();
	}

	function render( $atts, $content = null ) {

		parent::render( $atts );

		$buffy = '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

			$buffy .= $this->get_block_css(); // get block css
			$buffy .= $this->get_block_js(); // get block js

			$buffy .= '<div class="tds-block-inner td-fix-index">';
		        $buffy .= '<pre>' . __CLASS__ . '</pre>';
			$buffy .= '</div>';

		$buffy .= '</div>';

		return $buffy;
	}

}
