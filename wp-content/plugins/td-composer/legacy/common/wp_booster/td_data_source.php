<?php
class td_data_source {

    static $fake_loop_offset = 0; //used by the found row hook in templates to fix pagination. The blocks do not use this since we use custom pagination there.


    /**
     * converts a pagebuilde array to a wordpress query args array
     * creates the $args array from shortcodes - used by the pagebuilde + widgets + by the metabox_to_args
     * @param string $atts - the shortcode string
     * @param string $paged - page number  /1  or  /2
     * @return array
     */
    static function shortcode_to_args($atts = '', $paged = '') {
        //print_r($atts);
        extract( shortcode_atts(
                array(
                    'post_ids' => '',
                    'category_ids' => '',
                    'in_all_terms' => '',
                    'include_children' => '',
                    'category_id' => '',
                    'tag_slug' => '',
                    'sort' => '',
                    'limit' => '', /* 'limit' => 5, */
                    'autors_id' => '',
                    'installed_post_types' => '',
                    'posts_per_page' => '',  //!!!! se poate sa nu mai fie folosit
                    'offset' => '',
                    'live_filter' => '',
                    'live_filter_cur_post_id' => '', //this is auto generated by the block render ( add_live_filter_atts ) only when it's needed - it's the current post id
                    'live_filter_cur_post_author' => '', //auto generated - author_id of current post
                    'search_query' => '', // search keyword
                    'date_query' => '', // date parameters
                    'tag_id' => '', // current tag filter
                    'taxonomies' => '', // taxonomies slugs for the 'cur_post_same_taxonomies' > live_filter
                ),
                $atts
            )
        );

        //init the array
        $wp_query_args = array(
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish'
        );

	    /*  ----------------------------------------------------------------------
	        jetpack sorting - this will return here if that's the case because it dosn't work with other filters (it's site wide, no category + this or other combinations)
	    */
	    if ( $sort == 'jetpack_popular_2' ) {
		    if (function_exists('stats_get_csv')) {
			    // the damn jetpack api cannot return only posts so it may return pages. That's why we query with a bigger + 5 limit
			    // so that if the api returns also 5 pages mixed with the post we will still have the desired number of posts
			    // NOTE: stats_get_csv has a cache built in!

			    $jetpack_api_posts = stats_get_csv('postviews', array(
				    'days' => 2,
				    'limit' => $limit + 5
			    ));

			    if (!empty($jetpack_api_posts) and is_array($jetpack_api_posts)) {
                    $jetpack_api_posts_ids = wp_list_pluck($jetpack_api_posts, 'post_id');

                        // Filter the returned posts. Remove all posts that do not match the default 'post' Post Type.
                        foreach ( $jetpack_api_posts_ids as $k => $post_id ) {
                            if ( get_post_type($post_id) != 'post' ) {
                                unset( $jetpack_api_posts_ids[$k] );
                            }
                        }

				    $wp_query_args['post__in'] = $jetpack_api_posts_ids;
                    $wp_query_args['orderby'] = 'post__in';
				    $wp_query_args['posts_per_page'] = $limit;

				    return $wp_query_args;
			    }
		    }
		    return array(); // empty array makes WP_Query not run. Usually the return value of this function is feed directly to a new WP_Query
	    }

	    if ( 0 === strpos( $sort,'custom_order' ) ) {
	    	if ( function_exists( 'td_get_custom_order_ids' ) ) {
	    		$custom_order_post_ids = td_get_custom_order_ids($sort);
	    		if ( !empty( $custom_order_post_ids) && is_array( $custom_order_post_ids ) ) {
	    			$wp_query_args['post__in'] = $custom_order_post_ids;
                    $wp_query_args['orderby'] = 'post__in';
                    $wp_query_args['posts_per_page'] = count( $custom_order_post_ids );
			    }
		    }
	    }

        //the query goes only via $category_ids - for both options ($category_ids and $category_id) also $category_ids overwrites $category_id
        if ( !empty( $category_id ) and empty( $category_ids ) ) {
            $category_ids = $category_id;
        }

        // modified for taxonomy support
        if ( !empty( $category_ids ) ) {

            $tax_ids = explode (',', $category_ids );
            $taxonomies_array = array();
            $terms_not_in = array();
            $terms_in = array();
	        $block_type = $atts['block_type'] ?? '';

            foreach ( $tax_ids as $tax_id ) {

                if ( intval( $tax_id ) < 0 ) {
                    $tax_id = str_replace('-', '', $tax_id );
                    $terms_not_in[] = $tax_id;
	                $term_in = false;
                } else {
                    $terms_in[] = $tax_id;
	                $term_in = true;
                }

                $tax_args = get_term($tax_id);
                if ( $tax_args instanceof WP_Term ) {
                    $taxonomies_array[$tax_args->taxonomy][] = $tax_args->slug;

					if ( $term_in ) { // term in
						$taxonomies_array[$tax_args->taxonomy]['terms_in'][] = $tax_id;
					} else { // term not in
						$taxonomies_array[$tax_args->taxonomy]['terms_not_in'][] = $tax_id;
					}

                }
            }

            /**
             * category_ids cannot be empty
             * so we check if there is only one taxonomy id or more
             * stop and run old code if the category is the only taxonomy
             */
            if( count( $taxonomies_array ) === 1 ) {

                if ( isset( $taxonomies_array['category'] ) ) {

                    if( !empty( $in_all_terms ) ) {
                        $wp_query_args['category__and'] = explode(',', $category_ids);
                    } else {
                        $wp_query_args['cat'] = $category_ids;
                    }

                } else {

                    foreach ( $taxonomies_array as $taxonomy => $terms_array ) {
                        $wp_query_args['tax_query'] = array(
                            'relation' => empty($terms_in) ? 'OR' : 'AND',
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'term_id',
                                'terms' => $terms_in,
                                'include_children' => empty( $include_children ),
                                'operator' => ( !empty( $in_all_terms ) && $block_type !== 'tdb_filters_loop' && count( $terms_array ) > 1 ) ? 'AND' : 'IN'
                            ),
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'term_id',
                                'terms' => $terms_not_in,
                                'include_children' => empty( $include_children ),
                                'operator' => 'NOT IN'
                            ),

                        );
                    }
                }

            } elseif( count( $taxonomies_array ) > 1 ) {
				//print_r( $taxonomies_array );

                $tax_query = array();
                $tax_query['relation'] = 'OR';
//                if( !empty( $in_all_terms ) ) {
//                    $tax_query['relation'] = 'AND';
//                }

                $taxonomy_tax_in_query = array();
                $taxonomy_tax_not_in_query = array();

                // this case uses the same logic as the @see single tax case above, because we need to add the tax_query relation param "OR"/"AND"
                foreach ( $taxonomies_array as $taxonomy => $terms_array ) {
	                $taxonomy_terms_in = !empty( $terms_array['terms_in'] ) ? $terms_array['terms_in'] : array();
	                $taxonomy_terms_not_in = !empty( $terms_array['terms_not_in'] ) ? $terms_array['terms_not_in'] : array();

					if ( !empty($taxonomy_terms_in) ) {
						$taxonomy_tax_in_query['relation'] = !empty( $in_all_terms ) ? 'AND' : 'OR';
                        $taxonomy_tax_in_query[] = array(
							'taxonomy' => $taxonomy,
							'field' => 'term_id',
							'terms' => $taxonomy_terms_in,
							'include_children' => empty( $include_children ),
							'operator' => ( !empty( $in_all_terms ) && $block_type !== 'tdb_filters_loop' ) ? 'AND' : 'IN'
						);
					}

                    if ( !empty($taxonomy_terms_not_in) ) {
//						$taxonomy_terms_not_in['relation'] = 'OR';
                        $taxonomy_tax_not_in_query[] = array(
							'taxonomy' => $taxonomy,
							'field' => 'term_id',
							'terms' => $taxonomy_terms_not_in,
							'include_children' => empty( $include_children ),
							'operator' => 'NOT IN'
						);
                    }
                }

                if ( !empty($taxonomy_tax_not_in_query)) {
                    $tax_query['relation'] = !empty($taxonomy_tax_in_query) ? 'AND' : $tax_query['relation'];
                    $tax_query[] = $taxonomy_tax_in_query;
                    $tax_query[] = $taxonomy_tax_not_in_query;
                } else {
                    $tax_query['relation'] = !empty( $in_all_terms) ? 'AND' : 'OR';
                    $tax_query[] = $taxonomy_tax_in_query;
                }

                $wp_query_args['tax_query'] = $tax_query;
            }
        }

        if ( !empty( $tag_slug ) ) {
            //$wp_query_args['tag'] = trim($tag_slug);

            $tag_slugs = explode(',', trim( $tag_slug ) );
            $tag_not_in = array();
            $tag_in = array();
            foreach ( $tag_slugs as $td_tag ) {
                if ( !empty($td_tag) ) {
                    $td_tag = trim($td_tag);
                    // substr() can be replaced with str_starts_with() from php8 or WP5.9
                    if ( substr($td_tag, 0, 1) === '-' ) {
                        $td_tag = ltrim($td_tag, "-");
                        $tag_obj = get_term_by('slug', $td_tag, 'post_tag');
                        if ( $tag_obj ) {
                            $tag_not_in[] = $tag_obj->term_id;
                        }
                    } else {
                        $tag_obj = get_term_by('slug', $td_tag, 'post_tag');
                        if ( $tag_obj ) {
                            $tag_in[] = $tag_obj->term_id;
                        }
                    }
                }
            }

            $wp_query_args ['tag__in']= $tag_in;
            $wp_query_args ['tag__not_in']= $tag_not_in;

        }

        switch ( $sort ) {
            case 'featured':
                if (!empty($category_ids)) {
                    //for each category, get the object and compose the slug
                    $cat_id_array = explode (',', $category_ids);

                    foreach ($cat_id_array as &$cat_id) {
                        $cat_id = trim($cat_id);

                        //get the category object
                        $td_tmp_cat_obj =  get_category($cat_id);
                        if ( !empty($td_tmp_cat_obj) ) {
                            //make the $args
                            if (empty($wp_query_args['category_name'])) {
                                $wp_query_args['category_name'] = $td_tmp_cat_obj->slug; //get by slug (we get the children categories too)
                            } else {
                                $wp_query_args['category_name'] .= ',' . $td_tmp_cat_obj->slug; //get by slug (we get the children categories too)
                            }
                        }
                        unset($td_tmp_cat_obj);
                    }
                }

                $wp_query_args['cat'] = get_cat_ID(TD_FEATURED_CAT); //add the fetured cat
                break;
            case 'oldest_posts':
                $wp_query_args['order'] = 'ASC';
                break;
            case 'modified_date':
                $wp_query_args['orderby'] = 'post_modified';
                break;
            case 'popular':
                $wp_query_args['meta_key'] = td_page_views::$post_view_counter_key;
                $wp_query_args['orderby'] = 'meta_value_num';
                $wp_query_args['order'] = 'DESC';
                break;
            case 'popular7':
                $wp_query_args['meta_query'] = array(
                    'relation' => 'AND',
                    array(
                        'key'     => td_page_views::$post_view_counter_7_day_total,
                        'type'    => 'numeric'
                    ),
                    array(
                        'key'     => td_page_views::$post_view_counter_7_day_last_date,
                        'value'   => ( date('U') - 604800 ), // current date minus 7 days
                        'type'    => 'numeric',
                        'compare' => '>'
                    )
                );
                $wp_query_args['orderby'] = td_page_views::$post_view_counter_7_day_total;
                $wp_query_args['order'] = 'DESC';
                break;
            case 'popular1': // popular last 24 hours (last day)
                $wp_query_args['meta_query'] = array(
                    'relation' => 'AND',
                    array(
                        'key'     => td_page_views::$post_views_last_24_hours_total,
                        'type'    => 'numeric'
                    ),
                    array(
                        'key'     => td_page_views::$post_view_counter_7_day_last_date,
                        'value'   => ( date('U') - 86400 ), // current date minus 1 day(24 hours)
                        'type'    => 'numeric',
                        'compare' => '>'
                    )
                );
                $wp_query_args['orderby'] = td_page_views::$post_views_last_24_hours_total;
                $wp_query_args['order'] = 'DESC';
                break;
            case 'popular2': // popular last 48 hours (last 2 days)
                $wp_query_args['meta_query'] = array(
                    'relation' => 'AND',
                    array(
                        'key'     => td_page_views::$post_views_last_48_hours_total,
                        'type'    => 'numeric'
                    ),
                    array(
                        'key'     => td_page_views::$post_view_counter_7_day_last_date,
                        'value'   => ( date('U') - 172800 ), // current date minus 2 days(48 hours)
                        'type'    => 'numeric',
                        'compare' => '>'
                    )
                );
                $wp_query_args['orderby'] = td_page_views::$post_views_last_48_hours_total;
                $wp_query_args['order'] = 'DESC';
                break;
            case 'review_high':
                $wp_query_args['meta_key'] = 'td_review_key';
                $wp_query_args['orderby'] = 'meta_value_num';
                $wp_query_args['order'] = 'DESC';
                break;
            case 'random_posts':
                $wp_query_args['orderby'] = 'rand';
                break;
            case 'alphabetical_order':
                $wp_query_args['orderby'] = 'title';
                $wp_query_args['order'] = 'ASC';
                break;
            case 'comment_count':
                $wp_query_args['orderby'] = 'comment_count';
                $wp_query_args['order'] = 'DESC';
                break;
            case 'random_today':
                $wp_query_args['orderby'] = 'rand';
                $wp_query_args['year'] = date('Y');
                $wp_query_args['monthnum'] = date('n');
                $wp_query_args['day'] = date('j');
                break;
            case 'random_7_day':
                $wp_query_args['orderby'] = 'rand';
                $wp_query_args['date_query'] = array(
                            'column' => 'post_date_gmt',
                            'after' => '1 week ago'
                            );
                break;
        }

        if ( !empty( $autors_id ) ) {
            $wp_query_args['author'] = $autors_id;
        }

        //add post_type to query
        if ( !empty( $installed_post_types ) ) {
            $array_selected_post_types = array();
            $expl_installed_post_types = explode(',', $installed_post_types );

            foreach ( $expl_installed_post_types as $val_this_post_type ) {
                if ( trim( $val_this_post_type ) != '' ) {
                    $array_selected_post_types[] = trim( $val_this_post_type );
                }
            }

            $wp_query_args['post_type'] = $array_selected_post_types; // $installed_post_types;
        }


        /**
         * the live filters are generated in td_block.php and are added when the block is rendered on the page in the atts of the block
         * @see td_block::add_live_filter_atts
         */
        if ( !empty( $live_filter ) ) {
            switch ( $live_filter ) {
                case 'cur_post_same_tags':

                    $tags = wp_get_post_tags($live_filter_cur_post_id);
                    if ( $tags ) {
                        $taglist = array();
                        for ($i = 0; $i <= 4; $i++) {
                            if (!empty($tags[$i])) {
                                $taglist[] = $tags[$i]->term_id;
                            } else {
                                break;
                            }
                        }
                        $wp_query_args['tag__in'] = $taglist;
                        $wp_query_args['post__not_in'] = array($live_filter_cur_post_id);

                        //print_r($wp_query_args);
                        //die;

                    }
                    break;

                case 'cur_post_same_author':
                    $wp_query_args['author'] = $live_filter_cur_post_author;
                    $wp_query_args['post__not_in'] = array($live_filter_cur_post_id);
                    break;

                case 'cur_post_same_categories':
                    //print_r($atts);
                    $wp_query_args['category__in'] = wp_get_post_categories($live_filter_cur_post_id);
                    $wp_query_args['post__not_in'] = array($live_filter_cur_post_id);
                    break;

                case 'cur_post_same_taxonomies':

					// tax query init
	                $tax_query = array();
	                $tax_query['relation'] = 'OR';

					// taxonomies
	                $taxonomies_array = array();
					if ( !empty( $taxonomies ) ) {
						$taxonomies_slugs = explode (',', $taxonomies );

						if ( !empty( $taxonomies_slugs ) ) {
							foreach ( $taxonomies_slugs as $tax_slug ) {
								$tax_slug = trim( $tax_slug );

								if ( taxonomy_exists( $tax_slug ) ) {
									$taxonomies_array[] = $tax_slug;
								}

							}
						}

					}

					// get current post taxonomies
	                $post_taxonomies = get_object_taxonomies( get_post_type( $live_filter_cur_post_id ) );
	                foreach ( $post_taxonomies as $taxonomy ) {

						if ( !empty( $taxonomies_array ) && !in_array( $taxonomy, $taxonomies_array ) )
							continue;

		                $post_tax_terms = wp_get_post_terms( $live_filter_cur_post_id, $taxonomy, array( 'fields' => 'ids' ) );
		                if ( is_array( $post_tax_terms ) ) {
							$tax_query[] = array(
								'taxonomy' => $taxonomy,
								'terms' => $post_tax_terms,
								'field' => 'term_id',
								'include_children' => false,
							);
		                }
	                }

					//echo '<pre>' . print_r( $tax_query, true ) . '</pre>';

                    $wp_query_args['tax_query'] = $tax_query;
                    $wp_query_args['post__not_in'] = array( $live_filter_cur_post_id );

                    break;

            }
        }

        //show only unique posts if that setting is enabled on the template
        /*
         if ( td_unique_posts::$show_only_unique == true ) {
            $wp_query_args['post__not_in'] = td_unique_posts::$rendered_posts_ids;
        }
        */
        if ( td_unique_posts::$unique_articles_enabled == true ) {
            $wp_query_args['post__not_in'] = td_unique_posts::$rendered_posts_ids;
        }

        // post in section
        if ( !empty( $post_ids ) ) {

            // split posts id string
            $post_id_array = explode (',', $post_ids);

            $post_in = array();
            $post_not_in = array();

            // split ids into post_in and post_not_in
            foreach ( $post_id_array as $post_id ) {
                $post_id = trim($post_id);

                // check if the ID is actually a number
                if (is_numeric($post_id)) {
                    if (intval($post_id) < 0) {
                        $post_not_in [] = str_replace('-', '', $post_id);
                    } else {
                        $post_in [] = $post_id;
                    }
                }
            }

            // don't pass an empty post__in because it will return had_posts()
            if (!empty($post_in)) {
                $wp_query_args['post__in'] = $post_in;
                $wp_query_args['orderby'] = 'post__in';
            }

            // check if the post__not_in is already set, if it is merge it with $post_not_in
            if (!empty($post_not_in)) {
                if (!empty($wp_query_args['post__not_in'])){
                    $wp_query_args['post__not_in'] = array_merge($wp_query_args['post__not_in'], $post_not_in);
                } else {
                    $wp_query_args['post__not_in'] = $post_not_in;
                }
            }
        }


        //custom pagination limit
        if ( empty( $limit ) ) {
            $limit = get_option('posts_per_page');
        }
        $wp_query_args['posts_per_page'] = $limit;

        //custom pagination
        if ( !empty( $paged ) ) {
            $wp_query_args['paged'] = $paged;
        } else {
            $wp_query_args['paged'] = 1;
        }

        // offset + custom pagination - if we have offset, wordpress overwrites the pagination and works with offset + limit
        if ( !empty( $offset ) and $paged > 1 ) {
            $wp_query_args['offset'] = $offset + ( ( $paged - 1 ) * $limit) ;
        } else {
            $wp_query_args['offset'] = $offset ;
        }


        //set this variable to pass it to the filter that fixes the pagination on the templates with fake loops. It is not used on blocks because the blocks have custom pagination
        self::$fake_loop_offset = $offset;

        if ( !empty( $search_query ) ) {
            $wp_query_args['s'] = $search_query;
        }

        if ( !empty( $date_query ) ) {
            foreach ( $date_query as $type => $value ) {
                if ( $value === '' ) {
                    unset($date_query[$type]);
                }
            }
            $wp_query_args['date_query'] = array($date_query);
        }

		// current tag filter
	    if ( !empty( $tag_id ) ) {
		    $wp_query_args['tag_id'] = $tag_id;
	    }

        //print_r($wp_query_args);

        return $wp_query_args;
    }




    /**
     * converts a post metabox value array to a wordpress query args array
     * @param $td_homepage_loop_filter - the post loop filer metadata array [$td_homepage_loop will be applied actually]
     * @param string $paged
     * @return array
     */
    static function metabox_to_args($td_homepage_loop_filter, $paged = '') {


        $wp_query_args = self::shortcode_to_args($td_homepage_loop_filter, $paged);



        //$wp_query_args['paged'] = $paged;

        if (!empty($td_homepage_loop_filter['show_featured_posts'])) {
            if (empty($wp_query_args['cat'])) {
                $wp_query_args['cat'] = '-' . get_cat_ID(TD_FEATURED_CAT);
            } else {
                $wp_query_args['cat'] .= ',-' . get_cat_ID(TD_FEATURED_CAT);
            }
        }


        $wp_query_args['ignore_sticky_posts'] = 0;

        // custom pagination for the fake template loops
        if (isset($wp_query_args['offset']) and $wp_query_args['offset'] > 0) {
            //fix reported posts for the fake loops
            add_filter('found_posts', array(__CLASS__, 'td_hook_fix_offset_pagination'), 1, 2 );
        }


        //print_r($wp_query_args);

        return $wp_query_args;
    }

    // custom pagination for the fake template loops - used by hook
    static function td_hook_fix_offset_pagination($found_posts, $query) {
        remove_filter('found_posts','td_hook_fix_offset_pagination');
        return (int)$found_posts - (int)td_data_source::$fake_loop_offset;
    }





    /**
     * is used by all the blocks
     * @param string $atts
     * @param string $paged - is used by ajax
     * @param string $type - set this to 'products' to query products post type
     * @return WP_Query
     */
    static function &get_wp_query ( $atts = array(), $paged = '', $type = '' ) { // by ref

    	if ( $type === 'products' ) {

    		$block_type = $atts['block_type'] ?? '';

	        //echo '<pre>';
	        //print_r($atts);
		    //echo '</pre>';

		    global $td_woo_products_atts_ds, $td_woo_attributes_filters_ds;
		    $td_woo_products_atts_ds = $td_woo_attributes_filters_ds = array();

		    // limit
		    if ( isset( $atts['limit'] ) ) {
			    $limit = $atts['limit'];
		    }

		    // offset
		    $offset = 0;
		    if ( isset( $atts['offset'] ) && !empty( $atts['offset'] ) ) {
			    $offset = $atts['offset'];
		    }

		    // process sorting
		    $atts['orderby'] = $atts['sort'] ?? '';

		    // products_ids
		    $products_ids = $atts['products_ids'] ?? '';
		    if ( !empty( $products_ids ) ) {
			    $products_ids_array = explode(',', $products_ids ); // split products ids string

			    $products_in = array();
			    $products_not_in = array();

			    // split ids into post_in and post_not_in
			    foreach ( $products_ids_array as $product_id ) {
				    $product_id = trim($product_id);

				    // check if the ID is actually a number
				    if ( is_numeric( $product_id ) ) {
					    if ( intval( $product_id ) < 0 ) {
						    $products_not_in[] = str_replace('-', '', $product_id);
					    } else {
						    $products_in[] = $product_id;
					    }
				    }
			    }

			    if ( !empty( $products_in ) ) {
				    $td_woo_products_atts_ds['post__in'] = $products_in;
			    }

			    if ( !empty( $products_not_in ) ) {
				    $td_woo_products_atts_ds['post__not_in'] = $products_not_in;
			    }

		    }

		    // product cat
		    $product_cat = $atts['product_cat'] ?? '';

		    // product categories ids
		    $product_categories_ids = $atts['product_categories_ids'] ?? '';

		    // the query goes only via $product_categories_ids - for both options ( $product_categories_ids and $product_cat )
		    // ...also $product_categories_ids overwrites $product_cat
		    if ( !empty( $product_cat ) and empty( $product_categories_ids ) ) {
			    $product_categories_ids = $product_cat;
		    }

		    if ( !empty( $product_categories_ids ) ) {

                $ids = explode (',', $product_categories_ids);

			    $product_cat_in = array();
			    $product_cat_not_in = array();
			    foreach ( $ids as $id ) {
				    $product_cat_id = str_replace('-', '', $id );
				    $product_cat = get_term( $product_cat_id );

				    if ( !$product_cat instanceof WP_Term )
					    continue;

				    if ( intval( $id ) < 0 ) {
					    // exclude
					    $product_cat_not_in[] = $id;
				    } else {
					    // include
					    $product_cat_in[] = $id;
				    }
			    }

			    if ( !empty( $product_cat_in ) ) {
				    $atts['category'] = implode(",", $product_cat_in);
			    }

			    if ( !empty( $product_cat_not_in ) ) {
				    $atts['category'] = implode(",", $product_cat_not_in);
				    $atts['cat_operator'] = 'NOT IN';
			    }
		    }

		    // product tag
		    $product_tag_slug = $atts['product_tag_slug'] ?? '';
		    if ( !empty( $product_tag_slug ) ) {
			    $atts['tag'] = $product_tag_slug;
		    }

		    // cache
		    $atts['cache'] = false; // should shortcode output be cached

		    // pagination
		    $atts['paginate'] = true; // should results be paginated

		    if ( $block_type === 'td_woo_products_loop' ) {
			    $page = absint( empty( $paged ) ? ( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] ) : $paged );
		    } else {
			    $page = absint( empty( $paged ) ? 1 : $paged );
		    }

		    // offset
		    if ( $page > 1 && isset( $limit ) ) {
			    $offset = intval($offset) + ( ( $page - 1 ) * (int)$limit );
		    }

		    if ( !empty($offset) ) {
			    $td_woo_products_atts_ds['offset'] = $offset;
		    }

		    if ( $page > 1 ) {
			    $td_woo_products_atts_ds['paged'] = $page;
		    } elseif ( $page === 1 && $block_type !== 'td_woo_products_loop' ) {
			    $td_woo_products_atts_ds['paged'] = $page;
		    }

		    // search query
		    $search_query = $atts['s'] ?? '';
		    if ( !empty( $search_query ) ) {
			    $td_woo_products_atts_ds['s'] = $search_query;
		    }

		    // single product template related filter query
		    $single_product_page_filter = $atts['single_product_page_filter'] ?? '';
		    if ( $block_type === 'td_woo_products_block' && !empty( $single_product_page_filter ) ) {

		    	// this flag is used to detect when the single product page filter ( related, upsells, related cats/tags ) is used
			    $td_woo_products_atts_ds['single_product_page_filter'] = true;

			    // process products to be excluded
			    $excludes_ids_array = array();
			    if ( isset( $atts['p_id'] ) ) {
				    $excludes_ids_array[] = $atts['p_id']; // current product
			    }
			    $excludes_ids_array = array_unique(
			    	array_merge(
					    $excludes_ids_array,
					    $atts['p_upsells_ids'] ?? array() // upsells products
				    )
			    );

			    switch( $single_product_page_filter ) {

				    case 'upsells':
					    if( !empty( $atts['p_upsells_ids'] ) ) {
						    $td_woo_products_atts_ds['post__in'] = $atts['p_upsells_ids'];
					    }
					    break;

				    case 'related':

					    if ( !empty( $excludes_ids_array ) ) {
						    $td_woo_products_atts_ds['post__not_in'] = $excludes_ids_array;
					    }

					    $r_tax_query = array();

					    if ( !empty( $atts['p_cats_ids'] ) && !empty( $atts['p_tags_slugs'] ) ) {
						    $r_tax_query['relation'] = 'OR';
					    }

					    if ( !empty( $atts['p_cats_ids'] ) ) {
						    $r_tax_query[] = array(
							    'taxonomy' => 'product_cat',
							    'field'    => 'term_id',
							    'terms'    => $atts['p_cats_ids'],
						    );
					    }

					    if ( !empty( $atts['p_tags_slugs'] ) ) {
						    $r_tax_query[] = array(
							    'taxonomy' => 'product_tag',
							    'field'    => 'slug',
							    'terms'    => $atts['p_tags_slugs'],
						    );
					    }

					    if ( !empty( $r_tax_query ) ) {
						    $td_woo_products_atts_ds['tax_query'][] = $r_tax_query;
					    }

					    break;

				    case 'related_categories':

					    if ( !empty( $excludes_ids_array ) ) {
						    $td_woo_products_atts_ds['post__not_in'] = $excludes_ids_array;
					    }

					    $r_tax_query = array();

					    if ( !empty( $atts['p_cats_ids'] ) ) {
						    $r_tax_query[] = array(
							    'taxonomy' => 'product_cat',
							    'field'    => 'term_id',
							    'terms'    => $atts['p_cats_ids'],
						    );
					    }

					    if ( !empty( $r_tax_query ) ) {
						    $td_woo_products_atts_ds['tax_query'][] = $r_tax_query;
					    }

					    break;

				    case 'related_tags':

					    if ( !empty( $excludes_ids_array ) ) {
						    $td_woo_products_atts_ds['post__not_in'] = $excludes_ids_array;
					    }

					    $r_tax_query = array();

					    if ( !empty( $atts['p_tags_slugs'] ) ) {
						    $r_tax_query[] = array(
							    'taxonomy' => 'product_tag',
							    'field'    => 'slug',
							    'terms'    => $atts['p_tags_slugs'],
						    );
					    }

					    if ( !empty( $r_tax_query ) ) {
						    $td_woo_products_atts_ds['tax_query'][] = $r_tax_query;
					    }

					    break;

			    }

		    }

		    // apply td woo filters >>> we need to detect ajax requests here to process attributes filters and adjust the woocommerce_shortcode_products_query accordingly...
		    // on ajax requests we don't have the $_GET query filters available, so we need to set filters as shortcode attributes
		    if ( defined('TD_WOO') && class_exists( 'td_woo_util' ) ) {

		        if ( td_woo_util::is_ajax() || ( tdb_state_template::get_template_type() === 'woo_shop_base' || !tdb_state_template::get_template_type() ) ) {
				    if ( $block_type === 'td_woo_products_loop' && !empty( $atts['td_woo_attributes_filters'] ) && is_array( $atts['td_woo_attributes_filters'] ) ) {

					    global $td_woo_attributes_filters_multiple_selection;
					    $td_woo_attributes_filters_multiple_selection = $atts['td_woo_attributes_filters_ms'] ?? array();

						// set attributes filters
					    foreach ( $atts['td_woo_attributes_filters'] as $tax => $tax_terms_filters_list ) {
						    $taxonomy = str_replace( 'tdw_', '', $tax );
						    if ( strpos( $taxonomy, 'pa_' ) !== false ) {
							    $terms = array_map( 'sanitize_title', explode( ',', $tax_terms_filters_list ) );
							    $td_woo_attributes_filters_ds[$taxonomy] = $terms;
						    }
					    }

				    }
			    }

		    }

		    // used to alter the products query args and pass our own query arguments( product attributes filters ) that can't be passed through attributes..
		    add_filter( 'woocommerce_shortcode_products_query', function ( $query_args, $attributes, $type ) {

			    global $td_woo_products_atts_ds, $td_woo_attributes_filters_ds, $td_woo_attributes_filters_multiple_selection;

			    if ( !empty( $td_woo_attributes_filters_ds ) ) {
				    foreach ( $td_woo_attributes_filters_ds as $taxonomy => $terms ) {

						$operator = isset( $td_woo_attributes_filters_multiple_selection[$taxonomy] ) && $td_woo_attributes_filters_multiple_selection[$taxonomy] ? 'IN' : 'AND';

					    $query_args['tax_query'][] = array(
						    'taxonomy' => $taxonomy,
						    'terms'    => $terms,
						    'field'    => 'slug',
						    'operator' => $operator,
					    );

				    }
			    }

			    if ( !empty( $query_args['orderby'] ) && 'favourites' === $query_args['orderby'] ) {
			    	$query_args['orderby'] = 'post__in';
				    $query_args['post__in'] = td_woo_util::get_favourite_products();
				    $query_args['ignore_sticky_posts'] = 1;
			    }

			    return ( isset( $td_woo_products_atts_ds['single_product_page_filter'] ) && $td_woo_products_atts_ds['single_product_page_filter'] ) ? array_merge_recursive( $query_args, $td_woo_products_atts_ds ) : array_merge( $query_args, $td_woo_products_atts_ds );

		    }, 10, 3 );

		    global $td_woo_loop_products_data;

		    // used to get the query results
		    add_filter( 'woocommerce_shortcode_products_query_results', function ($results, $wc_shortcode_products_instance) {
			    global $td_woo_loop_products_data;
			    $td_woo_loop_products_data = json_decode( json_encode($results), true );
			    return $results;
		    }, 10, 2 );

		    /*
			 * call the WC_Shortcode_Products get_content method to trigger the woocommerce_shortcode_products_query_results hook and set the $td_woo_loop_products global
			 */
		    $shortcode = new WC_Shortcode_Products($atts);
		    $shortcode->get_content();

		    /*
			 * reset the woo products block atts & woo attributes filters globals
			 *
			 * fix for applying the `woocommerce_shortcode_products_query` filter when running through td woo state
			 *
			 */
		    $td_woo_products_atts_ds = array();

		    $td_query = $td_woo_loop_products_data;

	    } else {
		    $args = self::shortcode_to_args($atts, $paged);

		    $args = apply_filters( 'td_data_source_blocks_query_args', $args, $atts );

			// in composer flag
		    $in_composer = td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax();

			// on ajax block call flag
		    $td_block_ajax = td_util::tdc_is_td_block_ajax();

			// cache td_query
		    $td_query_cache = !empty( $atts['td_query_cache'] ) && !$in_composer && !$td_block_ajax;

		    // td_query cache expiration
		    $td_query_cache_expiration = !empty( $atts['td_query_cache_expiration'] ) ? $atts['td_query_cache_expiration'] : MINUTE_IN_SECONDS;

			// if block cache is enabled add td_query cache expiration to wp query args
		    // this will invalidate cache if td_query cache expiration changes
		    if ( $td_query_cache ) {
				$args['td_query_cache_expiration'] = absint( $td_query_cache_expiration );
		    }

		    // generate the transient name based on the query args
		    $td_query_transient_name = 'td_query_' . md5( wp_json_encode($args) );

		    // when using rand, we'll cache a number of random queries and pull those to avoid querying rand on each page load
		    if ( !empty( $args['orderby'] ) && 'rand' === $args['orderby'] ) {
			    $rand_index = wp_rand( 0, max( 1, absint( apply_filters( 'td_query_max_rand_cache_count', 5 ) ) ) );
			    $td_query_transient_name .= $rand_index;
		    }

			// if cache is enabled but not when editing ( is td composer ) get the td query cache
		    $td_query_transient_value = $td_query_cache ? get_transient( $td_query_transient_name ) : false;

		    if ( $td_query_transient_value ) {
			    $td_query = $td_query_transient_value;
		    } else {
			    $td_query = new WP_Query($args);

			    if ( $td_query_cache ) {
				    $td_query_transient_expiration = absint( apply_filters( 'td_query_transient_expiration', $td_query_cache_expiration, $args, $atts ) );
				    set_transient( $td_query_transient_name, $td_query, $td_query_transient_expiration );
			    }

		    }

	    }

        return $td_query;
    }


    /**
     * used by the ajax search feature
     * @param $search_string
     * @param $limit
     * @param $post_type - the post type to use for the search query
     * @return WP_Query
     */
    static function &get_wp_query_search( $search_string, $limit = 5, $post_type = '' ) {
        $args = array(
            's' => $search_string,
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'td_block_query' => 'search_query'
        );

	    if ( !empty( $post_type ) ) {
	    	$args['post_type'] = $post_type;
	    }

        $td_query = new WP_Query($args);
        return $td_query;
    }

}

