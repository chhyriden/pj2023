<?php
/*
Plugin Name: tagDiv Doctors PRO Demo Plugin
Plugin URI: http://tagdiv.com
Description: tagDiv plugin with custom post types, taxonomies & custom fields and more for Doctors PRO Demo.
Author: tagDiv
Version: 1.0.0
Author URI: http://tagdiv.com
*/

defined( 'ABSPATH' ) || exit;

// load demo custom modules & styles
require_once('td-doctors-pro-demo-cm-styles.php');
new td_doctors_pro_demo_cm_styles( dirname(__FILE__ ), plugins_url( __FILE__ ) );

add_action( 'tdc_init', function() {
	new td_doctors_pro_demo_plugin();
}, 11 );

class td_doctors_pro_demo_plugin {

	var $plugin_url = '';
	var $plugin_path = '';

	public function __construct() {
		$this->plugin_url = plugins_url(__FILE__ );
		$this->plugin_path = dirname(__FILE__ );

		/**
		 * example on how to add new custom filter option for tdb_filters_loop_sorting_options shortcode
		 */

		// this adds sorting options for dropwdown & composer
		//add_filter( 'tdb_filters_loop_sorting_options', function ( $options ) {
		//
		//	// new custom sorting options
		//	$new_custom_sorting_options = array(
		//		'Caca order' => 'caca_order',
		//		'Hello World' => 'hello_world',
		//		'Lorem Ipsum' => 'lorem_ips'
		//	);
		//
		//	return array_merge(
		//		$options,
		//		$new_custom_sorting_options
		//	);
		//
		//});

		// this adds filter functionality for td query
		//add_filter( 'td_data_source_blocks_query_args', function ( $args, $atts ) {
		//
		//	// block type
		//	$block_type = $atts['block_type'] ?? null;
		//
		//	// get block sort
		//	$sort = $atts['sort'] ?? '';
		//
		//	// apply it on `tdb_filters_loop` blocks
		//	if ( $block_type === 'tdb_filters_loop' ) {
		//
		//		// set `caca_order` wp query args
		//		if ( $sort === 'caca_order' ) {
		//			$args['meta_key'] = 'tdcf_test_field';
		//			$args['orderby'] = 'meta_value_num';
		//			$args['order'] = 'ASC';
		//		}
		//
		//	}
		//
		//	return $args;
		//
		//}, 10, 2 );

		/**
		 * ACF
		 */

		// this will allow custom fields meta box on posts when the acf plugin is active
		add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );

		// add local acf fields
		add_action( 'init', array( $this, 'acf_add_local_field_groups' ) );

		// export acf fields: (via json sync)
		add_filter( 'acf/settings/save_json', array( $this, 'set_acf_json_save_folder' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'add_acf_json_load_folder' ) );

		// sync json acf-json fields @see td-eastcoast-check-pro-demo/includes/acf-json
		add_action( 'admin_init', array( $this, 'sync_acf_fields' ) );

		/**
		 * CPT UI
		 */
		add_action( 'after_setup_theme',  function () {
            // we need priority 9, to avoid invalid tax error
            add_action( 'init', array( $this, 'cptui_register_my_cpts' ), 9 );
			add_action( 'init', array( $this, 'cptui_register_my_taxes' ), 9 );
		});

		// load plugin's config/shortcodes
		add_action( 'tdc_loaded', array( $this, 'td_demo_plugin_config' ), 10 );

	}

	public function set_acf_json_save_folder( $path ) {
		return $this->plugin_path . '/includes/acf-json';
	}

	public function add_acf_json_load_folder( $paths ) {
		unset( $paths[0] );

		$paths[] = $this->plugin_path . '/includes/acf-json';

		return $paths;
	}

	public function acf_add_local_field_groups() {

		/*
		 * ACF > Export Field Groups - Generated PHP goes here
		 */

	}

	public function sync_acf_fields() {

		if( ! function_exists('acf_get_field_groups' ) )
			return;

		$groups = acf_get_field_groups();
		$sync = array();

		// return here if no field groups
		if( empty( $groups ) )
			return;

		// find json field groups which have not yet been imported
		foreach( $groups as $group ) {
			$local    = acf_maybe_get( $group, 'local', false );
			$modified = acf_maybe_get( $group, 'modified', 0 );
			$private  = acf_maybe_get( $group, 'private', false );

			// ignore db/php/private field groups
			if( $local !== 'json' || $private ) {
				// do nothing
				continue;
			// append to sync if not yet in database
			} elseif( ! $group['ID'] ) {
				$sync[ $group['key'] ] = $group;
			// append to sync if "json" modified time is newer than database
			} elseif( $modified && $modified > get_post_modified_time( 'U', true, $group['ID'], true ) ) {
				$sync[ $group['key'] ]  = $group;
			}
		}

		// return here if no sync needed
		if( empty( $sync ) )
			return;

		foreach( $sync as $key => $v ) {

			// append fields
			if( acf_have_local_fields( $key ) ) {
				$sync[$key]['fields'] = acf_get_local_fields( $key );
			}

			// import
			$field_group = acf_import_field_group( $sync[$key] );

		}

	}

	// register cptui post types
	function cptui_register_my_cpts() {

        /**
         * Post Type: Doctors.
         */

        $labels = [
            "name" => __( "Doctors", "newspaper" ),
            "singular_name" => __( "Doctor", "newspaper" ),
            "menu_name" => __( "My Doctors", "newspaper" ),
            "all_items" => __( "All Doctors", "newspaper" ),
            "add_new" => __( "Add new", "newspaper" ),
            "add_new_item" => __( "Add new Doctor", "newspaper" ),
            "edit_item" => __( "Edit Doctor", "newspaper" ),
            "new_item" => __( "New Doctor", "newspaper" ),
            "view_item" => __( "View Doctor", "newspaper" ),
            "view_items" => __( "View Doctors", "newspaper" ),
            "search_items" => __( "Search Doctors", "newspaper" ),
            "not_found" => __( "No Doctors found", "newspaper" ),
            "not_found_in_trash" => __( "No Doctors found in trash", "newspaper" ),
            "parent" => __( "Parent Doctor:", "newspaper" ),
            "featured_image" => __( "Featured image for this Doctor", "newspaper" ),
            "set_featured_image" => __( "Set featured image for this Doctor", "newspaper" ),
            "remove_featured_image" => __( "Remove featured image for this Doctor", "newspaper" ),
            "use_featured_image" => __( "Use as featured image for this Doctor", "newspaper" ),
            "archives" => __( "Doctor archives", "newspaper" ),
            "insert_into_item" => __( "Insert into Doctor", "newspaper" ),
            "uploaded_to_this_item" => __( "Upload to this Doctor", "newspaper" ),
            "filter_items_list" => __( "Filter Doctors list", "newspaper" ),
            "items_list_navigation" => __( "Doctors list navigation", "newspaper" ),
            "items_list" => __( "Doctors list", "newspaper" ),
            "attributes" => __( "Doctors attributes", "newspaper" ),
            "name_admin_bar" => __( "Doctor", "newspaper" ),
            "item_published" => __( "Doctor published", "newspaper" ),
            "item_published_privately" => __( "Doctor published privately.", "newspaper" ),
            "item_reverted_to_draft" => __( "Doctor reverted to draft.", "newspaper" ),
            "item_scheduled" => __( "Doctor scheduled", "newspaper" ),
            "item_updated" => __( "Doctor updated.", "newspaper" ),
            "parent_item_colon" => __( "Parent Doctor:", "newspaper" ),
        ];

        $args = [
            "label" => __( "Doctors", "newspaper" ),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "rest_namespace" => "wp/v2",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => [ "slug" => "tdcpt_doctors", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "editor", "thumbnail", "excerpt", "custom-fields" ],
            "taxonomies" => [ "tdtax_specialty", "tdtax_gender", "tdtax_location" ],
            "show_in_graphql" => false,
        ];

        register_post_type( "tdcpt_doctors", $args );

        /**
         * Post Type: Contacts.
         */

        $labels = [
            "name" => __( "Contacts", "newspaper" ),
            "singular_name" => __( "Contact", "newspaper" ),
            "menu_name" => __( "My Contacts", "newspaper" ),
            "all_items" => __( "All Contacts", "newspaper" ),
            "add_new" => __( "Add new", "newspaper" ),
            "add_new_item" => __( "Add new Contact", "newspaper" ),
            "edit_item" => __( "Edit Contact", "newspaper" ),
            "new_item" => __( "New Contact", "newspaper" ),
            "view_item" => __( "View Contact", "newspaper" ),
            "view_items" => __( "View Contacts", "newspaper" ),
            "search_items" => __( "Search Contacts", "newspaper" ),
            "not_found" => __( "No Contacts found", "newspaper" ),
            "not_found_in_trash" => __( "No Contacts found in trash", "newspaper" ),
            "parent" => __( "Parent Contact:", "newspaper" ),
            "featured_image" => __( "Featured image for this Contact", "newspaper" ),
            "set_featured_image" => __( "Set featured image for this Contact", "newspaper" ),
            "remove_featured_image" => __( "Remove featured image for this Contact", "newspaper" ),
            "use_featured_image" => __( "Use as featured image for this Contact", "newspaper" ),
            "archives" => __( "Contact archives", "newspaper" ),
            "insert_into_item" => __( "Insert into Contact", "newspaper" ),
            "uploaded_to_this_item" => __( "Upload to this Contact", "newspaper" ),
            "filter_items_list" => __( "Filter Contacts list", "newspaper" ),
            "items_list_navigation" => __( "Contacts list navigation", "newspaper" ),
            "items_list" => __( "Contacts list", "newspaper" ),
            "attributes" => __( "Contacts attributes", "newspaper" ),
            "name_admin_bar" => __( "Contact", "newspaper" ),
            "item_published" => __( "Contact published", "newspaper" ),
            "item_published_privately" => __( "Contact published privately.", "newspaper" ),
            "item_reverted_to_draft" => __( "Contact reverted to draft.", "newspaper" ),
            "item_scheduled" => __( "Contact scheduled", "newspaper" ),
            "item_updated" => __( "Contact updated.", "newspaper" ),
            "parent_item_colon" => __( "Parent Contact:", "newspaper" ),
        ];

        $args = [
            "label" => __( "Contacts", "newspaper" ),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "rest_namespace" => "wp/v2",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => [ "slug" => "tdcpt_contacts", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "editor", "thumbnail" ],
            "show_in_graphql" => false,
        ];

        register_post_type( "tdcpt_contacts", $args );
    }


    // register cptui taxonomies
	function cptui_register_my_taxes() {

        /**
         * Taxonomy: Specialties.
         */

        $labels = [
            "name" => __( "Specialties", "newspaper" ),
            "singular_name" => __( "Specialty", "newspaper" ),
            "menu_name" => __( "Specialties", "newspaper" ),
            "all_items" => __( "All Specialties", "newspaper" ),
            "edit_item" => __( "Edit Specialty", "newspaper" ),
            "view_item" => __( "View Specialty", "newspaper" ),
            "update_item" => __( "Update Specialty name", "newspaper" ),
            "add_new_item" => __( "Add new Specialty", "newspaper" ),
            "new_item_name" => __( "New Specialty name", "newspaper" ),
            "parent_item" => __( "Parent Specialty", "newspaper" ),
            "parent_item_colon" => __( "Parent Specialty:", "newspaper" ),
            "search_items" => __( "Search Specialties", "newspaper" ),
            "popular_items" => __( "Popular Specialties", "newspaper" ),
            "separate_items_with_commas" => __( "Separate Specialties with commas", "newspaper" ),
            "add_or_remove_items" => __( "Add or remove Specialties", "newspaper" ),
            "choose_from_most_used" => __( "Choose from the most used Specialties", "newspaper" ),
            "not_found" => __( "No Specialties found", "newspaper" ),
            "no_terms" => __( "No Specialties", "newspaper" ),
            "items_list_navigation" => __( "Specialties list navigation", "newspaper" ),
            "items_list" => __( "Specialties list", "newspaper" ),
            "back_to_items" => __( "Back to Specialties", "newspaper" ),
            "name_field_description" => __( "The name is how it appears on your site.", "newspaper" ),
            "parent_field_description" => __( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "newspaper" ),
            "slug_field_description" => __( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "newspaper" ),
            "desc_field_description" => __( "The description is not prominent by default; however, some themes may show it.", "newspaper" ),
        ];


        $args = [
            "label" => __( "Specialties", "newspaper" ),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => true,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => [ 'slug' => 'tdtax_specialty', 'with_front' => true, ],
            "show_admin_column" => true,
            "show_in_rest" => true,
            "show_tagcloud" => false,
            "rest_base" => "tdtax_specialty",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "rest_namespace" => "wp/v2",
            "show_in_quick_edit" => true,
            "sort" => false,
            "show_in_graphql" => false,
        ];
        register_taxonomy( "tdtax_specialty", [ "tdcpt_doctors" ], $args );

        /**
         * Taxonomy: Genders.
         */

        $labels = [
            "name" => __( "Genders", "newspaper" ),
            "singular_name" => __( "Gender", "newspaper" ),
            "menu_name" => __( "Genders", "newspaper" ),
            "all_items" => __( "All Genders", "newspaper" ),
            "edit_item" => __( "Edit Gender", "newspaper" ),
            "view_item" => __( "View Gender", "newspaper" ),
            "update_item" => __( "Update Gender name", "newspaper" ),
            "add_new_item" => __( "Add new Gender", "newspaper" ),
            "new_item_name" => __( "New Gender name", "newspaper" ),
            "parent_item" => __( "Parent Gender", "newspaper" ),
            "parent_item_colon" => __( "Parent Gender:", "newspaper" ),
            "search_items" => __( "Search Genders", "newspaper" ),
            "popular_items" => __( "Popular Genders", "newspaper" ),
            "separate_items_with_commas" => __( "Separate Genders with commas", "newspaper" ),
            "add_or_remove_items" => __( "Add or remove Genders", "newspaper" ),
            "choose_from_most_used" => __( "Choose from the most used Genders", "newspaper" ),
            "not_found" => __( "No Genders found", "newspaper" ),
            "no_terms" => __( "No Genders", "newspaper" ),
            "items_list_navigation" => __( "Genders list navigation", "newspaper" ),
            "items_list" => __( "Genders list", "newspaper" ),
            "back_to_items" => __( "Back to Genders", "newspaper" ),
            "name_field_description" => __( "The name is how it appears on your site.", "newspaper" ),
            "parent_field_description" => __( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "newspaper" ),
            "slug_field_description" => __( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "newspaper" ),
            "desc_field_description" => __( "The description is not prominent by default; however, some themes may show it.", "newspaper" ),
        ];


        $args = [
            "label" => __( "Genders", "newspaper" ),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => [ 'slug' => 'tdtax_gender', 'with_front' => true, ],
            "show_admin_column" => true,
            "show_in_rest" => true,
            "show_tagcloud" => false,
            "rest_base" => "tdtax_gender",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "rest_namespace" => "wp/v2",
            "show_in_quick_edit" => true,
            "sort" => false,
            "show_in_graphql" => false,
        ];
        register_taxonomy( "tdtax_gender", [ "tdcpt_doctors" ], $args );

        /**
         * Taxonomy: Locations.
         */

        $labels = [
            "name" => __( "Locations", "newspaper" ),
            "singular_name" => __( "Location", "newspaper" ),
            "menu_name" => __( "Locations", "newspaper" ),
            "all_items" => __( "All Locations", "newspaper" ),
            "edit_item" => __( "Edit Location", "newspaper" ),
            "view_item" => __( "View Location", "newspaper" ),
            "update_item" => __( "Update Location name", "newspaper" ),
            "add_new_item" => __( "Add new Location", "newspaper" ),
            "new_item_name" => __( "New Location name", "newspaper" ),
            "parent_item" => __( "Parent Location", "newspaper" ),
            "parent_item_colon" => __( "Parent Location:", "newspaper" ),
            "search_items" => __( "Search Locations", "newspaper" ),
            "popular_items" => __( "Popular Locations", "newspaper" ),
            "separate_items_with_commas" => __( "Separate Locations with commas", "newspaper" ),
            "add_or_remove_items" => __( "Add or remove Locations", "newspaper" ),
            "choose_from_most_used" => __( "Choose from the most used Locations", "newspaper" ),
            "not_found" => __( "No Locations found", "newspaper" ),
            "no_terms" => __( "No Locations", "newspaper" ),
            "items_list_navigation" => __( "Locations list navigation", "newspaper" ),
            "items_list" => __( "Locations list", "newspaper" ),
            "back_to_items" => __( "Back to Locations", "newspaper" ),
            "name_field_description" => __( "The name is how it appears on your site.", "newspaper" ),
            "parent_field_description" => __( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "newspaper" ),
            "slug_field_description" => __( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "newspaper" ),
            "desc_field_description" => __( "The description is not prominent by default; however, some themes may show it.", "newspaper" ),
        ];


        $args = [
            "label" => __( "Locations", "newspaper" ),
            "labels" => $labels,
            "public" => true,
            "publicly_queryable" => true,
            "hierarchical" => true,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => [ 'slug' => 'tdtax_location', 'with_front' => true, ],
            "show_admin_column" => false,
            "show_in_rest" => true,
            "show_tagcloud" => false,
            "rest_base" => "tdtax_location",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "rest_namespace" => "wp/v2",
            "show_in_quick_edit" => false,
            "sort" => false,
            "show_in_graphql" => false,
        ];
        register_taxonomy( "tdtax_location", [ "doctors", "tdcpt_doctors" ], $args );
    }

    public function td_demo_plugin_config() {

		//td_api_block::add( 'td_demo_plugin_shortcode',
		//	array(
		//		"map_in_td_composer" => true,
		//		"name" => 'TD Demo Plugin',
		//		"base" => 'td_demo_plugin',
		//		'tdc_category' => 'Single post',
		//		"file" => $this->plugin_path . '/includes/shortcodes/td_demo_plugin_shortcode.php',
		//		"params" => array(),
		//	)
		//);

	}

}
