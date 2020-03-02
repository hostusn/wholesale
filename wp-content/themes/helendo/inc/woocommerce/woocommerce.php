<?php
/**
 * WooCommerce Compatibility File
 *
 * @link    https://woocommerce.com/
 *
 * @package Helendo
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function helendo_woocommerce_setup() {
	add_theme_support( 'woocommerce', array( 'single_image_width' => 700 ) );
	if ( intval( helendo_get_option( 'product_image_zoom' ) ) ) {
		add_theme_support( 'wc-product-gallery-zoom' );
	}
	add_theme_support( 'wc-product-gallery-slider' );
}

add_action( 'after_setup_theme', 'helendo_woocommerce_setup' );

/**
 * WooCommerce initialize.
 */
function helendo_woocommerce_init() {
	if ( is_admin() ) {
		Helendo_WooCommerce_Settings::init();
	}
	Helendo_WooCommerce_Template::init();
	Helendo_WooCommerce_Template_Catalog::init();
	Helendo_WooCommerce_Template_Product::init();
}

add_action( 'wp_loaded', 'helendo_woocommerce_init' );

require get_theme_file_path( '/inc/woocommerce/settings.php' );
require get_theme_file_path( '/inc/woocommerce/theme-options.php' );
require get_theme_file_path( '/inc/woocommerce/template.php' );
require get_theme_file_path( '/inc/woocommerce/template-product.php' );
require get_theme_file_path( '/inc/woocommerce/template-catalog.php' );
