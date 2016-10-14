<?php

/**
 * Helpers file for WooCommerce plugin
 *
 * @subpackage 	lib
 * @package 	bshop
 *
 * @author 		WP Beaver World
 * @since 		1.0
 */
add_action( 'wp', 'bshop_wc_gridlist_toggle_button', 50 );
//* Repositioning grid list toggle buttons
function bshop_wc_gridlist_toggle_button() {
  global $WC_List_Grid;

  if( is_object( $WC_List_Grid ) ) {
    if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
      
      remove_action( 'woocommerce_before_shop_loop', array( $WC_List_Grid, 'gridlist_toggle_button' ), 30 );
      
      add_action( 'woocommerce_before_shop_loop', 'bshop_wc_resorder_markup_open', 15 );
      add_action( 'woocommerce_before_shop_loop', array( $WC_List_Grid, 'gridlist_toggle_button' ), 18 );
      add_action( 'woocommerce_before_shop_loop', 'bshop_wc_resorder_markup_close', 30 );
    }  
  }
}

function bshop_wc_resorder_markup_open() {
  echo '<div class="resorder-wrap">' . "\n";
}

function bshop_wc_resorder_markup_close() {
  echo '</div>' . "\n";
}

add_action( 'woocommerce_before_shop_loop_item_title', 'bshop_wc_product_image_back', 10 );
//* Adding back image on hover image
function bshop_wc_product_image_back() {
	global $product;

	$attachment_ids = $product->get_gallery_attachment_ids();
	if ( $attachment_ids ) {
		foreach ( $attachment_ids as $attachment_id ) {

			$image_title 	= esc_attr( get_the_title( $attachment_id ) );
			$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );

			$image = wp_get_attachment_image( 
				$attachment_id, 
				apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' ), 0, $attr = array(
						'title'	=> $image_title,
						'alt'	=> $image_title,
						'class' => 'product-image-back'
					)
			);

			if ( ! $image )
				continue;

			echo $image;       
			return;
		}
	}
}

add_action( 'woocommerce_after_shop_loop_item_title', 'bshop_wc_remove_template_loop_rating', 1 );
//* Remove rating
function bshop_wc_remove_template_loop_rating() {
	if( ! is_shop() && ! is_singular( 'product') && ! is_product_category() && ! is_product_tag() && ! is_product_taxonomy() )
		return;

	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
}