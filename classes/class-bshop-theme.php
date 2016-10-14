<?php

/**
 * Helper class of BShop theme.
 *
 * @class BShopTheme
 */
final class BShopTheme {
    
	/**
	 * Loaidng bshop theme css file
	 * @method stylesheet
	 */
	static public function stylesheet() {
		echo '<link rel="stylesheet" href="' . BSHOP_URL . '/style.css" />';
	}

	/**
	 * Adding sub menu toogle effect in responsive menu
	 * @method bshop_rm_toggle_sub_menu_button
	 */
	function bshop_rm_toggle_sub_menu_button() {
		wp_enqueue_script( 
			'toggle-sub-menu', 
			BSHOP_URL . '/js/toggle.sub.menu.js', 
			array('jquery'), 
			BSHOP_THEME_VERSION, 
			true
		);

		$output = array(
			'subMenu'  => __( 'Menu', 'fl-automator' ),
		);
		wp_localize_script( 'toggle-sub-menu', 'menuL10n', $output );
	}

	/**
	 * Adding woocommerce css file
	 * @method woo_stylesheet
	 */
	static public function woo_stylesheet() {
		echo '<link rel="stylesheet" href="' . BSHOP_URL . '/css/woo.css" />';
	}

	/**
	 * Reposition WooCommerce Left Sidebar
	 * @method woo_stylesheet
	 */
	static public function bshop_wc_wrapper_start() {
		$layout = FLTheme::get_setting('fl-woo-layout');
		$col_size = $layout == 'no-sidebar' ? '12' : '8';

		echo '<div class="container">';
		echo '<div class="row">';
		echo '<div class="fl-content ';
		FLTheme::content_class('woo');
		echo '">';
	}

	/**
	 * Renders the closing markup for WooCommerce pages.
	 *
	 * @since 1.0
	 * @return void
	 */
	static public function bshop_wc_wrapper_end() {
		//$layout = FLTheme::get_setting('fl-woo-layout');

		echo '</div>';
		FLTheme::sidebar('left', 'woo');
		FLTheme::sidebar('right', 'woo');
		echo '</div>';
		echo '</div>';
	}

	/**
	 * epositioning grid list toggle buttons
	 * @method bshop_wc_gridlist_toggle_button
	 */
	static public function bshop_wc_gridlist_toggle_button() {
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

	/**
	 * Adding back image on hover image
	 * @method bshop_wc_product_image_back
	 */
	static public function bshop_wc_product_image_back() {
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

	/**
	 * Remove rating
	 * @method bshop_wc_remove_template_loop_rating
	 */
	static public function bshop_wc_remove_template_loop_rating() {
		if( ! is_shop() && ! is_singular( 'product') && ! is_cart() && ! is_product_category() && ! is_product_tag() && ! is_product_taxonomy() )
			return;

		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	}

	/**
	 * Remove "Product Description" heading
	 * @method bshop_wc_product_image_back
	 */
	static public function bshop_wc_remove_product_description_heading( $heading ) {
		return;
	}

	/**
	 * Create full width product page
	 * @method bshop_wc_full_width_layout
	 */
	static public function bshop_wc_full_width_layout( $mods ) {
		if( is_singular( 'product' ) && $mods['fl-woo-layout'] !== 'no-sidebar' ) {
			$mods['fl-woo-layout'] = 'no-sidebar';
			$mods['fl-woo-sidebar-size'] = 0;
		}

		return $mods;
	}

	/**
 	 * Displaying 3 related products on product view page
 	 * @method bshop_wc_related_products_per_page
 	 */
	static public function bshop_wc_related_products_per_page( $args ) {
		global $product;

		if ( empty( $product ) || ! $product->exists() ) {
			return $args;
		}

		$posts_per_page = 3;

		if ( ! $related = $product->get_related( $posts_per_page ) ) {
			return $args;
		}

		$args['posts_per_page'] = $posts_per_page;
		$args['post__in']       = $related;

		return $args;
	}
}