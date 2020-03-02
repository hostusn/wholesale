<?php
/**
 * Hooks for importer
 *
 * @package Drcore
 */


/**
 * Importer the demo content
 *
 * @since  1.0
 *
 */
function helendo_vc_addons_importer() {
	return array(
		array(
			'name'       => 'Home Default',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-default/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-default/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-default/widgets.wie',
			'sliders'    => 'http://demo.grixbase.com/soo-importer/helendo/home-default/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Default',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Boxed',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-boxed/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-boxed/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-boxed/widgets.wie',
			'sliders'    => 'http://demo.grixbase.com/soo-importer/helendo/home-boxed/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Boxed',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Carousel',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-carousel/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-carousel/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-carousel/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Carousel',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Categories',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-categories/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-categories/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-categories/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Categories',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Collection',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-collection/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-collection/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-collection/widgets.wie',
			'sliders'    => 'http://demo.grixbase.com/soo-importer/helendo/home-collection/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Collection',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Full Width',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-full-width/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-full-width/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-full-width/widgets.wie',
			'sliders'    => 'http://demo.grixbase.com/soo-importer/helendo/home-full-width/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Full Width',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Instagram',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-instagram/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-instagram/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-instagram/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Instagram',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Left Sidebar',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-left-sidebar/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-left-sidebar/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-left-sidebar/widgets.wie',
			'sliders'    => 'http://demo.grixbase.com/soo-importer/helendo/home-left-sidebar/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Left Sidebar',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Metro',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-metro/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-metro/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-metro/widgets.wie',
			'pages'      => array(
				'front_page' => 'Home Metro',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Minimal',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-minimal/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-minimal/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-minimal/widgets.wie',
			'sliders'    => 'http://demo.grixbase.com/soo-importer/helendo/home-minimal/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Minimal',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Parallax',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-parallax/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-parallax/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-parallax/widgets.wie',
			'sliders'    => 'http://demo.grixbase.com/soo-importer/helendo/home-parallax/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Parallax',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		array(
			'name'       => 'Home Video',
			'preview'    => 'http://demo.grixbase.com/soo-importer/helendo/home-video/preview.jpg',
			'content'    => 'http://demo.grixbase.com/soo-importer/helendo/demo-content.xml',
			'customizer' => 'http://demo.grixbase.com/soo-importer/helendo/home-video/customizer.dat',
			'widgets'    => 'http://demo.grixbase.com/soo-importer/helendo/home-video/widgets.wie',
			'sliders'    => 'http://demo.grixbase.com/soo-importer/helendo/home-video/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Video',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary' => 'primary-menu',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 500,
					'height' => 500,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
	);
}

add_filter( 'soo_demo_packages', 'helendo_vc_addons_importer', 20 );
