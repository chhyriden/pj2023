<?php

class tds_doctors_pro_module1 extends td_module {

    function __construct( $post_data_array, $module_atts = array() ) {
        //run the parrent constructor
        parent::__construct( $post_data_array, $module_atts );
    }

    function render( $shortcode_class = '', $style_class = '' ) {

        ob_start();

//        $curr_template_type = tdb_state_template::get_template_type();
//
//            global $tdb_state_single;
//            $post_user_reviews_data = $tdb_state_single->post_user_reviews->__invoke();
//
//            $reviews_average = $post_user_reviews_data['reviews_average'];
//
//        echo '<span style="color: #fff;">';
//        var_dump($reviews_average);
//        echo '</span>';

        $average_rating = ('' != td_util::get_overall_post_rating($this->post->ID)) ? td_util::get_overall_post_rating($this->post->ID) : '-';

        $image_size = $this->get_shortcode_att('image_size', '', $style_class);

        if (empty($image_size)) {
            $image_size = 'td_696x0';
        }


        $additional_classes_array = array('tdb_module_loop');
        $additional_classes_array = apply_filters( 'td_composer_module_exclusive_class', $additional_classes_array, $this->post );
        $title_length = $this->get_shortcode_att('mc1_tl', '', $style_class);
        $title_tag = $this->get_shortcode_att('mc1_title_tag', '', $style_class);
        $price = $this->show_custom_field_value('tdcf_starting_price');

        /** Get the taxonomies*/
        $categories = $this->get_shortcode_att('categories', '', $style_class);
        $cat_limit = $this->get_shortcode_att('cat_limit', '', $style_class);
        if ('' !== $categories) {
            $categories_arr = explode(',', $categories);
            $categories_arr = array_map('trim', $categories_arr);
        }
        ?>

            <style>

            </style>

        <div class="<?php echo $this->get_module_classes($additional_classes_array);?>">
            <div class="td-module-container td-doctors-pro-wrap1">
                <div class="td-doctors-pro-image-container1"> <?php echo $this->get_image($image_size, true);?> </div>
                <div class="td-doctors-pro-meta-info-container1">
                    <?php if( td_util::get_overall_post_rating($this->post->ID) ) { ?>
                        <div class="td-doctors-pro-review-score1">
                            <?php echo $this->show_user_ratings_stars(); ?>
                            <span><?php echo td_util::get_overall_post_rating($this->post->ID) ?> out of 5</span>
                        </div>
                    <?php } ?>

                    <div class="td-doctors-pro-title1"><?php echo $this->get_title($title_length, $title_tag); ?></div>
                    <div class="td-doctors-pro-specialty1">
                        <?php $terms = get_the_terms( $this->post->ID, 'tdtax_specialty');
                        if ( false !== $terms && !is_wp_error($terms) ) {
                            foreach( $terms as $term ) {
                                echo '<a href="'.get_term_link($term->term_id).'">'.$term->name.'</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="td-doctors-pro-bottom-wrap1">
                        <div class="td-doctors-pro-location-wrap1">
                        <?php echo td_util::get_custom_svg_icon('doctors_location'); ?>
                        <?php $terms = get_the_terms( $this->post->ID, 'tdtax_location');
                        if ( false !== $terms && !is_wp_error($terms) ) {
                            foreach ($terms as $term) {
                                    if (get_term_meta($term->term_id, 'tdb-location-type', true) == 'city') {
                                        echo '<div class="td-doctors-pro-location1">';
                                        echo '<a href="' . get_term_link($term->term_id) . '">' . $term->name . '</a>';
                                        echo '</div>';
                                    }
                                }
                            }
                        ?>
                        </div>

                        <div class="td-doctors-pro-price-wrap1">
                            <div class="td-doctors-pro-price-icon1">
                                <?php echo td_util::get_custom_svg_icon('doctors_price'); ?>
                            </div>
                            <?php if ( $price != '' ) { ?>
                            <div class="td-doctors-pro-price-text1"><?php echo $price; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php return ob_get_clean();
    }
}
