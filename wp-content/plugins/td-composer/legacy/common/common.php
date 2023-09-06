<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 07.03.2019
 * Time: 16:12
 */

td_api_autoload::add('td_remote_http', TDC_PATH . '/legacy/common/wp_booster/td_remote_http.php');
td_api_autoload::add('td_remote_video', TDC_PATH . '/legacy/common/wp_booster/td_remote_video.php');
td_api_autoload::add('td_weather', TDC_PATH . '/legacy/common/wp_booster/td_weather.php');
td_api_autoload::add('td_pinterest', TDC_PATH . '/legacy/common/wp_booster/td_pinterest.php');
td_api_autoload::add('td_exchange', TDC_PATH . '/legacy/common/wp_booster/td_exchange.php');
td_api_autoload::add('td_instagram', TDC_PATH . '/legacy/common/wp_booster/td_instagram.php');
td_api_autoload::add('td_covid19', TDC_PATH . '/legacy/common/wp_booster/td_covid19.php');
td_api_autoload::add('td_flickr', TDC_PATH . '/legacy/common/wp_booster/td_flickr.php');


/* ----------------------------------------------------------------------------
 * video thumbnail & featured video embeds support
 */
td_api_autoload::add('td_video_support', TDC_PATH . '/legacy/common/wp_booster/td_video_support.php');
add_action('save_post', array('td_video_support', 'on_save_post_get_video_thumb'), 12 );
add_action('admin_notices', array('td_video_support', 'td_twitter_on_admin_notices') );
add_action('admin_notices', array('td_video_support', 'td_twitter_class_on_admin_notices') );
add_filter('embed_oembed_html', array('td_video_support', 'embed_oembed_html_process_embed_html'), 10, 4 );

td_api_autoload::add('td_audio_support', TDC_PATH . '/legacy/common/wp_booster/td_audio_support.php');
add_action('save_post', array('td_audio_support', 'on_save_post_get_audio_thumb'), 12 );


if ( is_admin() && ! td_util::is_mobile_theme() ) {

    /**
     * Custom content metaboxes (the select sidebar dropdown/post etc)
     */
    require_once( 'wp_booster/td_metabox_generator.php' );
    require_once( 'wp_booster/wp-admin/content-metaboxes/td_templates_settings.php' );

    add_action( 'td_demo', function() {
        require_once('wp-admin/panel/td_demo_installer.php');
        require_once('wp-admin/panel/td_demo_util.php');
    });
}


class tdc_global_blocks extends td_global_blocks {

    static function add_lazy_shortcode($block_id) {
        td_global_blocks::$global_id_lazy_instances[] = $block_id;
        add_shortcode($block_id, array('td_global_blocks', 'proxy_function'));
    }
}


/** ---------------------------------------------------------------------------
 * front end user compiled css @see  td_css_generator.php
 */
function td_include_user_compiled_css() {
    if ( ! is_admin() ) {

        if ( td_util::is_mobile_theme() ) {

            // add the global css compiler
            $compiled_css = td_css_generator_mob(); // get it live (compile at runtime)

            if ( ! empty( $compiled_css ) ) {
                td_css_buffer::add_to_header( $compiled_css );
            }

        } else {

            // add the global css compiler
            if ( TD_DEPLOY_MODE == 'dev' ) {
                $compiled_css = td_css_generator(); // get it live WARNING - it will always appear as autoloaded on DEV
            } else {
                $compiled_css = td_util::get_option('tds_user_compile_css'); // get it from the cache - do not compile at runtime
            }

            if (!empty($compiled_css)) {
                td_css_buffer::add_to_header($compiled_css);
            }

            $demo_state = td_util::get_loaded_demo_id();
            if ($demo_state !== false) {
                if (td_global::$demo_list[$demo_state]['td_css_generator_demo'] === true) {
                    require_once(td_global::$demo_list[$demo_state]['folder'] . 'td_css_generator_demo.php');
                    $demo_compiled_css = td_css_demo_gen();
                    if (!empty($demo_compiled_css)) {
                        td_css_buffer::add_to_header(PHP_EOL . PHP_EOL . PHP_EOL .'/* Style generated by theme for demo: ' . $demo_state . ' */'  . PHP_EOL);
                        td_css_buffer::add_to_header($demo_compiled_css);
                    }
                }
            }
        }
    }
}
add_action('wp_head', 'td_include_user_compiled_css', 10);



if( TD_THEME_NAME == 'Newsmag' || ( TD_THEME_NAME == 'Newspaper' && defined('TD_STANDARD_PACK') ) ) {
    /*
     * Register 'top-menu' header
     */
    add_action( 'init', function() {
        register_nav_menus(
            array(
                'top-menu' => 'Top Header Menu',
            )
        );
    }, 9);


    /* ----------------------------------------------------------------------------
     * more articles box
     */
    if (!td_util::is_mobile_theme()) {
        td_api_autoload::add('td_more_article_box', TDC_PATH . '/legacy/common/wp_booster/td_more_article_box.php');
        add_action('wp_footer', array('td_more_article_box', 'on_wp_footer_render_box'));
    }
}



/**
 * share translation - upload it on our server
 */
add_action('wp_ajax_td_ajax_share_translation', function() {

    if (!empty($_POST['td_translate']) && is_array($_POST['td_translate'])) {
        //don't save escape slashes into the database
        $translation_data = stripslashes_deep($_POST);
        //build query - necessary for multi level arrays
        $translation_data = http_build_query($translation_data);

        //api url
        $api_url = 'http://api.tagdiv.com/user_translations/add_full_user_translation';

        //curl init
        $curl = curl_init($api_url);

        //curl setup
        //curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //return not necessary
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $translation_data);

        //curl execute
        $api_response = curl_exec($curl);

        //on error
        if ($api_response === false) {
            td_log::log(__FILE__, __FUNCTION__, 'Failed to send translation', $translation_data);
        }
    }
});



/*  ----------------------------------------------------------------------------\
    used by ie8 - there is no other way to add js for ie8 only
 */
add_action('wp_head', 'add_ie_html5_shim');
function add_ie_html5_shim () {
    echo '<!--[if lt IE 9]>';
    echo '<script src="' . td_global::$http_or_https . '://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>';
    echo '<![endif]-->
    ';
}

/**
 * mail functions can't be used in theme (just in plugins)
 */
add_action( 'td_wp_mail', function( $user_email, $title, $message ) {
    if ( !wp_mail($user_email, $title, $message) ) {
        wp_die( 'The email could not be sent.' . "<br />\n" . 'Possible reason: your host may have disabled the mail() function.' );
    }
}, 10, 3);



/*  -----------------------------------------------------------------------------
    Our custom admin bar
 */
add_action('admin_bar_menu', 'td_custom_menu', 1000);
function td_custom_menu() {
    global $wp_admin_bar;
    if(!is_super_admin() || !is_admin_bar_showing()) return;

    $wp_admin_bar->add_menu(array(
        'parent' => 'site-name',
        'title' => '<span class="td-admin-bar-red">Theme panel</span>',
        'href' => admin_url('admin.php?page=td_theme_panel'),
        'id' => 'td-menu1'
    ));

    $wp_admin_bar->add_menu( array(
            'id'   => 'our_support_item',
            'meta' => array('title' => 'Theme support', 'target' => '_blank'),
            'title' => 'Theme support',
            'href' => 'https://forum.tagdiv.com' )
    );
}


/**
 * return the decoded demo file settings
 */
add_filter( 'td_demo_installer', function( $file_path ) {
    //read the settings file
    return unserialize(base64_decode(file_get_contents($file_path, true)));
});


function tdc_b64_decode( $val ) {
    return base64_decode( $val );
}

function tdc_b64_encode( $val ) {
    return base64_encode( $val );
}


/**
 * This should be totally removed! It's not reliable.
 * It has been moved here because of 'htmlspecialchars_decode' (it can't be used in theme - only 'wp_specialchars_decode')
 */
if (!function_exists('mb_convert_encoding')) {
    function mb_convert_encoding($string, $to_encoding = '', $from_encoding = '') {
        return htmlspecialchars_decode(utf8_decode(htmlentities($string, ENT_QUOTES | ENT_HTML5, 'utf-8', false)));
    }
}

/**
 * @import can't be used in theme
 *
 * @param $compiled_css
 * @param $fonts_to_load
 * @param $td_options
 */
function tdc_load_google_fonts( &$compiled_css, $fonts_to_load, $td_options ) {

	$google_fonts_names = td_fonts::get_google_fonts_for_url( $fonts_to_load );

	if ( ! empty( $google_fonts_names )) {
		$compiled_css .= '@import url("https://fonts.googleapis.com/css?family=' . $google_fonts_names  . '&display=swap' . '");' . PHP_EOL;
	}

	foreach ( $fonts_to_load as $font_id => $font_weights ) {

        if ( ! is_numeric( $font_id ) && 'DEFAULT' !== $font_id && ! empty( td_fonts::$font_names_google_list[ $font_id ] ) && 0 === strpos( $font_id, 'file_' ) ) {

        	$font_family_name = td_fonts::$font_names_google_list[ $font_id ];
            $font_file_link = $td_options['td_fonts_user_inserted']['font_' . $font_id];

            $compiled_css .= ' @font-face {' .
                'font-family:"' . $font_family_name . '";' .
                'src:local("' . $font_family_name . '"), url(' . $font_file_link . ') format("woff");
                  font-display: swap;
            }' . PHP_EOL;

        }
    }
}


if (TD_THEME_NAME === 'Newspaper' ) {
    add_theme_support('post-formats', array('video', 'audio'));
} else {
    add_theme_support('post-formats', array('video'));
}



add_action('admin_head', function() {

    if ( tdc_state::is_live_editor_ajax() || tdc_state::is_live_editor_iframe() ) {
        return;
    }

    global $post;

    if ( $post instanceof  WP_Post ) {

        $post_id = $post->ID;

        // check if we have a specific template set on the current post
        $td_post_theme_settings = td_util::get_post_meta_array( $post_id, 'td_post_theme_settings' );

        $tdb_template_id = '';

        if ( ! empty( $td_post_theme_settings[ 'td_post_template' ] ) ) {
            $single_template_id = $td_post_theme_settings[ 'td_post_template' ];

            // make sure the template exists, maybe it was deleted or something
            if ( td_global::is_tdb_template( $single_template_id, true ) ) {

                $tdb_template_id = td_global::tdb_get_template_id( $single_template_id );
            }

        } else {

            // read the global setting
            $default_template_id = td_util::get_option( 'td_default_site_post_template' );

            // make sure the template exists, maybe it was deleted or something
            if ( td_global::is_tdb_template( $default_template_id, true ) ) {

                // load the default tdb template
                $tdb_template_id = td_global::tdb_get_template_id( $default_template_id );
            }
        }

        if ( !empty( $tdb_template_id ) ) {

            // load the cloud template
            $wp_query_template = new WP_Query( array(
                    'p'         => $tdb_template_id,
                    'post_type' => 'tdb_templates',
                )
            );

            // if we have a template look for the 'tdb_single_comments' shortcode
            if ( ! empty( $wp_query_template ) && $wp_query_template->have_posts() ) {
            	$style = '';
            	$content_width = '';
                td_get_template_style( $wp_query_template->post, $style, $content_width );

                if ( ! empty( $style )) {
                	echo $style;
                }

                if ( ! empty( $content_width )) {
                	echo '<style>/* custom css */ .td-gutenberg-editor .editor-styles-wrapper .wp-block {max-width: ' . $content_width . 'px}</style>';
                	// for now it's only needed in Guttenberg
                    td_js_buffer::add_variable('tdContentWidth', $content_width);
                }
            }
        }
    }
});



function td_get_template_style( $template = null, &$style = '', &$content_width = '' ) {

	if ( ! is_null( $template) && $template instanceof WP_Post ) {

		preg_match_all( '/\[\s*tdb_single_content(\X*)\]\s*\[/miU', $template->post_content, $content_matches );
		if ( is_array( $content_matches ) && count( $content_matches ) && ! empty( $content_matches[ 1 ] ) && is_array( $content_matches[ 1 ] ) ) {

			$result_style = '';

			foreach ( $content_matches[ 1 ] as $str_atts ) {

				$rendered_shortcode_content = do_shortcode( '[tdb_single_content ' . $str_atts . ' ]' );

				$content_width = get_post_meta( $template->ID, 'tdc_single_post_content_width', true );

				// find inline css
				preg_match_all( '/\/\* custom css \*\/(\X*)<\/style>/miU', $rendered_shortcode_content, $style_matches );

				if ( count( $style_matches ) && is_array( $style_matches[ 0 ] ) ) {
					foreach ( $style_matches[ 0 ] as $style_match ) {
						// find inline css
						$result_style .= preg_replace( '/.tdi_(\X*)/miU', '.td-gutenberg-editor .editor-styles-wrapper .wp-block', $style_match );
					}
				}
			}

			if ( ! empty( $result_style ) ) {
				$style = '<style>' . $result_style;
			}
		}
	}
}


/**
 * add a cookie exception for darkmode in the case of WP Super Cache plugin
 */
add_action('init', 'add_wpsc_cookie_darkmode');
function add_wpsc_cookie_darkmode() {
    do_action( 'wpsc_add_cookie', 'td_dark_mode' );
}