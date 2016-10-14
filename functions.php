<?php

/**
 * Function file of BShop ecommerce child theme
 *
 * @author 		WP Beaver World
 * @link 		http://www.wpbeaverworld.com
 * @license		GPL-2.0+
 * @copyright	Copyright (c) 2016 WP Beaver World
 *
 * @since 		1.0
 */

//* Defines
define( 'BSHOP_DIR', get_stylesheet_directory() );
define( 'BSHOP_URL', get_stylesheet_directory_uri() );
define( 'BSHOP_THEME_VERSION', '1.0' );

add_action( 'after_setup_theme', 'bshop_setup' );
function bshop_setup() {
	//* Classes
	require_once 'classes/class-bshop-theme.php';

	//* Add style.css file
	add_action( 'fl_head', 'BShopTheme::stylesheet' );

	//* Adding sub menu toogle effect in responsive menu
	add_action( 'wp_enqueue_scripts', 'BShopTheme::bshop_rm_toggle_sub_menu_button' );

	if( ! FLTheme::is_plugin_active( 'woocommerce' ) )
		return;

	//* Add woo.css file
	add_action( 'fl_head', 'BShopTheme::woo_stylesheet' );

	//* Repositioning grid list toggle buttons
	add_action( 'wp', 'BShopTheme::bshop_wc_gridlist_toggle_button', 50 );

	//* Adding back image on hover image
	add_action( 'woocommerce_before_shop_loop_item_title', 'BShopTheme::bshop_wc_product_image_back', 10 );

	//* Remove rating from product box
	add_action( 'woocommerce_after_shop_loop_item_title', 'BShopTheme::bshop_wc_remove_template_loop_rating', 1 );

	//* Removing "Product Description" heading
	add_filter( 'woocommerce_product_description_heading', 'BShopTheme::bshop_wc_remove_product_description_heading' );

	//* Remove sidebar from single product details page
	//add_filter( 'fl_theme_mods', 'BShopTheme::bshop_wc_full_width_layout' );

	//* Alter related products limit
	add_filter( 'woocommerce_related_products_args', 'BShopTheme::bshop_wc_related_products_per_page', 99 );

	//* Enable shortcode in Text Widget
	add_filter( 'widget_text', 'do_shortcode' );
}

function bshop_wc_resorder_markup_open() {
	echo '<div class="resorder-wrap">' . "\n";
}

function bshop_wc_resorder_markup_close() {
	echo '</div>' . "\n";
}

add_action( 'init', 'init_bshop_wc', 9 );
function init_bshop_wc() {
	if( FLTheme::get_setting('fl-woo-layout') !== 'sidebar-left' )
		return;

	//* Removing WooCommerce wrapper hooks from parant theme
	remove_action( 'init', 'FLTheme::init_woocommerce' );

	remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
	remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	add_action('woocommerce_before_main_content', 'BShopTheme::bshop_wc_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'BShopTheme::bshop_wc_wrapper_end', 10);
}