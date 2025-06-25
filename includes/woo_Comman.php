<?php

class woo_Comman {
    public function __construct() {
        // No code here
    }
}

function woo_get_product($post_id) {
    $product = get_post($post_id);
    return $product;
}

function woo_returndata($post_id) {
    $woo_select_type = get_post_meta($post_id, 'woo_select_type', true);
    if (empty($woo_select_type)) {
        $woo_select_type = 'all';
    }
    $woo_select_tax_val = get_post_meta($post_id, 'woo_select_tax_val', true);
    $woo_product_show = get_post_meta($post_id, 'woo_product_show', true);
    if (empty($woo_product_show)) {
        $woo_product_show = 5;
    }
    $woo_show_per_column = get_post_meta($post_id, 'woo_show_per_column', true);
    if (empty($woo_show_per_column)) {
        $woo_show_per_column = 3;
    }
    $woo_thum = get_post_meta($post_id, 'woo_thum', true);
    if (empty($woo_thum)) {
        $woo_thum = 'yes';
    }
    $woo_order_by = get_post_meta($post_id, 'woo_order_by', true);
    if (empty($woo_order_by)) {
        $woo_order_by = 'ASC';
    }
    $woo_order = get_post_meta($post_id, 'woo_order', true);
    if (empty($woo_order)) {
        $woo_order = 'name';
    }
    $woo_layout = get_post_meta($post_id, 'woo_layout', true);
    if (empty($woo_layout)) {
        $woo_layout = 'list';
    }
    $arr_make = array('featured', 'sale');
    $caclass = 'productsbycat_' . $woo_layout;
    $arggs = array(
        'post_type' => 'productproduct',
        'posts_per_page' => $woo_product_show,
        'orderby' => $woo_order_by,
        'order' => $woo_order,
    );

    if ($woo_select_type === 'all') {
        $selected_productproducts = get_post_meta($post_id, 'woo_selected_productproducts', true);
        if (!empty($selected_productproducts)) {
            $arggs['post__in'] = $selected_productproducts;
        } else {
            $arggs['posts_per_page'] = -1; // Retrieve all productproducts
        }
    } elseif (!in_array($woo_select_type, $arr_make) && $woo_select_tax_val != '') {
        $tacar = array(
            'taxonomy' => $woo_select_type,
            'field' => 'term_id',
            'terms' => $woo_select_tax_val,
            'operator' => '=',
        );
        $arggs['tax_query'] = array($tacar);
    } elseif ($woo_select_type === 'featured') {
        if (class_exists('WooCommerce')) {
            $arggs['tax_query'] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                ),
            );
        } else {
            // Handle the case where WooCommerce is not active
            // You might want to add a fallback behavior here
        }
    }
    ?>
    <div class="woocommerce <?php echo esc_attr($caclass); ?>">
        <?php
        $loop = new WP_Query($arggs);
        while ($loop->have_posts()): $loop->the_post();
            $product = woo_get_product($loop->post->ID);
            ?>
            <div class="woo-product">
                <?php if ($woo_layout == 'list') { ?>
                    <div class="row text-center">
                        <?php
                        $button_link = get_field('button_link');
                        ?>
                        <div class="gmwproduct_grid2-title"><h3><a href="<?php echo esc_url($button_link); ?>"> <?php echo esc_attr($loop->post->post_title); ?></a></h3></div>
                    </div>
                    <div class="row text-center">
                        <?php
                        $price = get_field('price');
                        $button_link = get_field('button_link');
                        ?>
                        <div class="gmproduct_grid2-price"><h3><?php echo '<a href="' . $button_link . '">' . $price . '</a>'; ?></h3></div>
                    </div>
                    <!-- <div class="row text-center">
                        <?php
                        $short_description = get_field('short_descriptionShort_Description');
                        ?>
                        <div class="gmproduct_grid2-description"><?php echo esc_html($short_description); ?></div>
                    </div> -->
                    <div class="row">
                        <div class="product-description"><?php the_content(); ?></div>
                    </div>

                   

                    <div class="wp-block-button">
                        <?php
                        $button_title = get_field('button_title');
                        $button_link = get_field('button_link'); ?>
                        <a class="wp-block-button__link has-background wp-element-button" href="<?php echo esc_url($button_link); ?>" style="background-color:#c98f69" data-wpel-link="external" target="_blank" rel="noopener"> <strong><?php echo esc_html($button_title); ?></strong></a>
                    </div>
                    
                <?php } else { ?>
                    
                    <div class="woo-innder">
                                    <?php
                                $primary_category = '';
                                $primary_category_id = get_post_meta(get_the_ID(), '_yoast_wpseo_primary_category', true);
                                if ($primary_category_id) {
                                    $primary_category = get_cat_name($primary_category_id);
                                }
                                ?>
                                <div class="product-info">
                                    <div class="product-category-grid"><?php echo esc_html($primary_category); ?></div>
                                    <h3 class="product-title-grid">
                                        <?php
                                        $button_link = get_field('button_link');
                                        ?>
                                        <a href="<?php echo esc_url($button_link); ?>"><?php echo esc_attr($loop->post->post_title); ?></a>
                                    </h3>
                                </div>

                        
                                <?php
                                $button_link = get_field('button_link');
                                $post_thumbnail_url = get_the_post_thumbnail_url();
                                ?>
                                <div class="lefmss">
                                <a href="<?php echo esc_url($button_link); ?>"><img src="<?php echo esc_url($post_thumbnail_url); ?>" /></a>
                                </div>

                        <div class="product-info-button">
                            <?php
                            $button_title = get_field('button_title');
                            ?>
                            <div class="product-button-grid">
                            <?php
                            $button_link = get_field('button_link');
                            ?>
                            <a href="<?php echo esc_url($button_link); ?>"><?php echo esc_html($button_title); ?></a></div>
                        </div>
                    </div>
                      
                <?php } ?>
            </div>
        <?php endwhile;
        wp_reset_query();
        ?>
    </div>
    <?php
}
?>