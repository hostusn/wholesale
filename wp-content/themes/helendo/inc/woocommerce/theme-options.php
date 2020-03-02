<?php
/**
 * Theme options of WooCommerce.
 *
 * @package Helendo
 */

/**
 * Adds theme options sections of WooCommerce.
 *
 * @param array $sections Theme options sections.
 *
 * @return array
 */
function helendo_woocommerce_customize_sections( $sections ) {
	$sections = array_merge(
		$sections, array(
			'shop'                => array(
				'title'    => esc_html__( 'Shop', 'helendo' ),
				'priority' => 60,
				'panel'    => 'woocommerce',
			),
			'shop_toolbar'        => array(
				'title'    => esc_html__( 'Shop Toolbar', 'helendo' ),
				'priority' => 60,
				'panel'    => 'woocommerce',
			),
			'shop_badge'          => array(
				'title'       => esc_html__( 'Badges', 'helendo' ),
				'description' => '',
				'priority'    => 60,
				'panel'       => 'woocommerce',
				'capability'  => 'edit_theme_options',
			),
			'single_product'      => array(
				'title'    => esc_html__( 'Single Product', 'helendo' ),
				'priority' => 60,
				'panel'    => 'woocommerce',
			),
			'upsells_product'     => array(
				'title'    => esc_html__( 'Upsells Products', 'helendo' ),
				'priority' => 60,
				'panel'    => 'woocommerce',
			),
			'related_product'     => array(
				'title'    => esc_html__( 'Related Products', 'helendo' ),
				'priority' => 60,
				'panel'    => 'woocommerce',
			),
			'instagram_photos'    => array(
				'title'    => esc_html__( 'Instagram Photos', 'helendo' ),
				'priority' => 60,
				'panel'    => 'woocommerce',
			),
			'cross_sells_product' => array(
				'title'    => esc_html__( 'Cross-sells Products', 'helendo' ),
				'priority' => 60,
				'panel'    => 'woocommerce',
			),
			'shop_mobile'         => array(
				'title'    => esc_html__( 'Shop', 'helendo' ),
				'priority' => 60,
				'panel'    => 'mobile',
			),
		)
	);

	return $sections;
}

add_filter( 'helendo_customize_sections', 'helendo_woocommerce_customize_sections' );

/**
 * Adds theme options of WooCommerce.
 *
 * @param array $settings Theme options.
 *
 * @return array
 */
function helendo_woocommerce_customize_fields( $fields ) {

	// Product page.
	$fields = array_merge(
		$fields, array(
			'catalog_layout'                  => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Catalog Layout', 'helendo' ),
				'default'     => 'full-content',
				'section'     => 'shop',
				'priority'    => 70,
				'description' => esc_html__( 'Select layout for catalog.', 'helendo' ),
				'choices'     => array(
					'sidebar-content' => esc_html__( 'Left Sidebar', 'helendo' ),
					'content-sidebar' => esc_html__( 'Right Sidebar', 'helendo' ),
					'full-content'    => esc_html__( 'Full Content', 'helendo' ),
				),
			),
			'catalog_full_width'              => array(
				'type'            => 'toggle',
				'label'           => esc_html__( 'Catalog Full Width', 'helendo' ),
				'default'         => '0',
				'section'         => 'shop',
				'priority'        => 70,
				'active_callback' => array(
					array(
						'setting'  => 'catalog_layout',
						'operator' => 'in',
						'value'    => array( 'full-content' ),
					),
				),
			),
			'catalog_without_gutter'          => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Catalog Without Gutter', 'helendo' ),
				'default'  => '0',
				'section'  => 'shop',
				'priority' => 70,
			),
			'catalog_with_border'             => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Catalog With Border', 'helendo' ),
				'default'  => '0',
				'section'  => 'shop',
				'priority' => 70,
			),
			'catalog_mobile_columns'          => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Catalog Columns', 'helendo' ),
				'default'     => '1',
				'section'     => 'shop',
				'priority'    => 70,
				'description' => esc_html__( 'Select catalog columns on mobile.', 'helendo' ),
				'choices'     => array(
					'1' => esc_html__( '1 Column', 'helendo' ),
					'2' => esc_html__( '2 Columns', 'helendo' ),
				),
			),
			'catalog_filter_mobile'           => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Filter Mobile Sidebar', 'helendo' ),
				'default'     => '0',
				'section'     => 'shop',
				'priority'    => 70,
				'description' => esc_html__( 'The Catalog filter display as sidebar', 'helendo' ),
			),
			'product_attribute'               => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Product Attribute', 'helendo' ),
				'section'     => 'shop',
				'default'     => 'none',
				'priority'    => 70,
				'choices'     => helendo_product_attributes(),
				'description' => esc_html__( 'Show product attribute for each item listed under the item name.', 'helendo' ),
			),
			'added_to_cart_notice'            => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Added to Cart Notification', 'helendo' ),
				'description' => esc_html__( 'Display a notification when a product is added to cart', 'helendo' ),
				'section'     => 'shop',
				'priority'    => 70,
				'default'     => 1,
			),
			'cart_notice_auto_hide'           => array(
				'type'        => 'number',
				'label'       => esc_html__( 'Cart Notification Auto Hide', 'helendo' ),
				'description' => esc_html__( 'How many seconds you want to hide the notification.', 'helendo' ),
				'section'     => 'shop',
				'priority'    => 70,
				'default'     => 3,
			),
			'catalog_ajax_filter'             => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Ajax For Filtering', 'helendo' ),
				'section'     => 'shop',
				'description' => esc_html__( 'Check this option to use ajax for filtering in the catalog page.', 'helendo' ),
				'default'     => 1,
				'priority'    => 70
			),
			'catalog_nav_type'                => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Type of Navigation', 'helendo' ),
				'section'  => 'shop',
				'default'  => 'numbers',
				'priority' => 90,
				'choices'  => array(
					'numbers'  => esc_html__( 'Page Numbers', 'helendo' ),
					'infinite' => esc_html__( 'Infinite Scroll', 'helendo' ),
				),
			),
			//Shop Toolbar
			'shop_toolbar_left'               => array(
				'type'        => 'repeater',
				'label'       => esc_html__( 'Left Items', 'helendo' ),
				'description' => esc_html__( 'Control items on the left side of shop toolbar', 'helendo' ),
				'transport'   => 'postMessage',
				'section'     => 'shop_toolbar',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'helendo' ),
					'field' => 'item',
				),
				'fields'      => array(
					'item' => array(
						'type'    => 'select',
						'choices' => helendo_shop_toolbar_items_option(),
					),
				),
			),
			'shop_toolbar_right'              => array(
				'type'        => 'repeater',
				'label'       => esc_html__( 'Right Items', 'helendo' ),
				'description' => esc_html__( 'Control items on the right of shop toolbar', 'helendo' ),
				'transport'   => 'postMessage',
				'section'     => 'shop_toolbar',
				'default'     => array(),
				'row_label'   => array(
					'type'  => 'field',
					'value' => esc_attr__( 'Item', 'helendo' ),
					'field' => 'item',
				),
				'fields'      => array(
					'item' => array(
						'type'    => 'select',
						'choices' => helendo_shop_toolbar_items_option(),
					),
				),
			),
			'shop_toolbar_custom'             => array(
				'type'    => 'custom',
				'section' => 'shop_toolbar',
				'default' => '<hr>',
			),
			'shop_toolbar_categories_numbers' => array(
				'type'    => 'number',
				'label'   => esc_html__( 'Categories Numbers', 'helendo' ),
				'section' => 'shop_toolbar',
				'default' => 3,
			),
			'shop_toolbar_categories'         => array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Custom Categories', 'helendo' ),
				'section'     => 'shop_toolbar',
				'default'     => '',
				'description' => esc_html__( 'Enter categories slug you want to display. Each slug is separated by comma character ",". If empty, it will display default', 'helendo' ),
			),
			'shop_toolbar_custom_2'           => array(
				'type'    => 'custom',
				'section' => 'shop_toolbar',
				'default' => '<hr>',
			),
			'shop_toolbar_columns'            => array(
				'type'    => 'multicheck',
				'label'   => esc_html__( 'Columns Switcher', 'helendo' ),
				'default' => array( '3', '4', '5' ),
				'choices' => array(
					'3' => esc_attr__( '3 Columns', 'helendo' ),
					'4' => esc_attr__( '4 Columns', 'helendo' ),
					'5' => esc_attr__( '5 Columns', 'helendo' ),
					'6' => esc_attr__( '6 Columns', 'helendo' ),
				),
				'section' => 'shop_toolbar',
			),

			//Badge
			'catalog_badges'                  => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Catalog Badges', 'helendo' ),
				'section'     => 'shop_badge',
				'default'     => 1,
				'priority'    => 20,
				'description' => esc_html__( 'Check this to show badges in the catalog page.', 'helendo' ),
			),
			'badges'                          => array(
				'type'        => 'multicheck',
				'label'       => esc_html__( 'Badges', 'helendo' ),
				'section'     => 'shop_badge',
				'default'     => array( 'hot', 'new', 'sale', 'outofstock' ),
				'priority'    => 20,
				'choices'     => array(
					'hot'        => esc_html__( 'Hot', 'helendo' ),
					'new'        => esc_html__( 'New', 'helendo' ),
					'sale'       => esc_html__( 'Sale', 'helendo' ),
					'outofstock' => esc_html__( 'Out Of Stock', 'helendo' ),
				),
				'description' => esc_html__( 'Select which badges you want to show', 'helendo' ),
			),
			'hot_text'                        => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Custom Hot Text', 'helendo' ),
				'section'         => 'shop_badge',
				'default'         => 'Hot',
				'priority'        => 20,
				'active_callback' => array(
					array(
						'setting'  => 'badges',
						'operator' => 'contains',
						'value'    => 'hot',
					),
				),
			),
			'hot_color'                       => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Custom Hot Color', 'helendo' ),
				'default'         => '',
				'section'         => 'shop_badge',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'active_callback' => array(
					array(
						'setting'  => 'badges',
						'operator' => 'contains',
						'value'    => 'hot',
					),
				),
			),
			'hot_color_custom'                => array(
				'type'     => 'custom',
				'section'  => 'shop_badge',
				'default'  => '<hr>',
				'priority' => 20,
			),
			'outofstock_text'                 => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Custom Out Of Stock Text', 'helendo' ),
				'section'         => 'shop_badge',
				'default'         => 'Out Of Stock',
				'priority'        => 20,
				'active_callback' => array(
					array(
						'setting'  => 'badges',
						'operator' => 'contains',
						'value'    => 'outofstock',
					),
				),
			),
			'outofstock_color'                => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Custom Out Of Stock Color', 'helendo' ),
				'default'         => '',
				'section'         => 'shop_badge',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'active_callback' => array(
					array(
						'setting'  => 'badges',
						'operator' => 'contains',
						'value'    => 'outofstock',
					),
				),
			),
			'outofstock_color_custom'         => array(
				'type'     => 'custom',
				'section'  => 'shop_badge',
				'default'  => '<hr>',
				'priority' => 20,
			),
			'new_text'                        => array(
				'type'            => 'text',
				'label'           => esc_html__( 'Custom New Text', 'helendo' ),
				'section'         => 'shop_badge',
				'default'         => 'New',
				'priority'        => 20,
				'active_callback' => array(
					array(
						'setting'  => 'badges',
						'operator' => 'contains',
						'value'    => 'new',
					),
				),
			),
			'new_color'                       => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Custom New Color', 'helendo' ),
				'default'         => '',
				'section'         => 'shop_badge',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'active_callback' => array(
					array(
						'setting'  => 'badges',
						'operator' => 'contains',
						'value'    => 'new',
					),
				),
			),
			'new_color_custom'                => array(
				'type'     => 'custom',
				'section'  => 'shop_badge',
				'default'  => '<hr>',
				'priority' => 20,
			),
			'sale_color'                      => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Custom Sale Color', 'helendo' ),
				'default'         => '',
				'section'         => 'shop_badge',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'active_callback' => array(
					array(
						'setting'  => 'badges',
						'operator' => 'contains',
						'value'    => 'sale',
					),
				),
			),
			'sale_color_custom'               => array(
				'type'     => 'custom',
				'section'  => 'shop_badge',
				'default'  => '<hr>',
				'priority' => 20,
			),
			'product_newness'                 => array(
				'type'            => 'number',
				'label'           => esc_html__( 'Product Newness', 'helendo' ),
				'section'         => 'shop_badge',
				'default'         => 3,
				'priority'        => 20,
				'description'     => esc_html__( 'Display the "New" badge for how many days?', 'helendo' ),
				'active_callback' => array(
					array(
						'setting'  => 'badges',
						'operator' => 'contains',
						'value'    => 'new',
					),
				),
			),
			//Product
			'product_image_zoom'              => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Image Zoom', 'helendo' ),
				'section'     => 'single_product',
				'default'     => 1,
				'description' => esc_html__( 'Check this option to show a bigger size product image on mouseover', 'helendo' ),
				'priority'    => 40,
			),
			'product_images_lightbox'         => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Images Lightbox', 'helendo' ),
				'section'     => 'single_product',
				'default'     => 1,
				'description' => esc_html__( 'Check this option to open product gallery images in a lightbox', 'helendo' ),
				'priority'    => 40,
			),
			'product_thumbnail_numbers'       => array(
				'type'     => 'number',
				'label'    => esc_html__( 'Thumbnail Numbers', 'helendo' ),
				'section'  => 'single_product',
				'default'  => 5,
				'priority' => 40,
			),
			'product_add_to_cart_ajax'        => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Add to cart with AJAX', 'helendo' ),
				'section'     => 'single_product',
				'default'     => 1,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to enable add to cart with AJAX on the product page.', 'helendo' ),
			),
			'product_sidebar_custom'          => array(
				'type'     => 'custom',
				'section'  => 'single_product',
				'default'  => '<hr>',
				'priority' => 40,
			),
			'product_page_sidebar'            => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Sidebar', 'helendo' ),
				'section'     => 'single_product',
				'default'     => 'full-content',
				'priority'    => 40,
				'choices'     => array(
					'full-content'    => esc_html__( 'No Sidebar', 'helendo' ),
					'content-sidebar' => esc_html__( 'Content Sidebar', 'helendo' ),
					'sidebar-content' => esc_html__( 'Sidebar Content', 'helendo' ),
				),
				'description' => esc_html__( 'Select default sidebar for product page.', 'helendo' ),

			),
			'product_socials_custom'          => array(
				'type'     => 'custom',
				'section'  => 'single_product',
				'default'  => '<hr>',
				'priority' => 40,
			),
			'product_share_socials'           => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Product Share Socials', 'helendo' ),
				'section'     => 'single_product',
				'default'     => 1,
				'description' => esc_html__( 'Check this option to show share socials in the single product.', 'helendo' ),
				'priority'    => 40,
			),
			'product_social_icons'            => array(
				'type'            => 'multicheck',
				'label'           => esc_html__( 'Socials', 'helendo' ),
				'section'         => 'single_product',
				'default'         => array( 'twitter', 'facebook', 'google', 'pinterest', 'linkedin', 'vkontakte' ),
				'priority'        => 40,
				'choices'         => array(
					'twitter'   => esc_html__( 'Twitter', 'helendo' ),
					'facebook'  => esc_html__( 'Facebook', 'helendo' ),
					'google'    => esc_html__( 'Google Plus', 'helendo' ),
					'pinterest' => esc_html__( 'Pinterest', 'helendo' ),
					'linkedin'  => esc_html__( 'Linkedin', 'helendo' ),
					'vkontakte' => esc_html__( 'Vkontakte', 'helendo' ),
					'whatsapp'  => esc_html__( 'Whatsapp', 'helendo' ),
					'email'     => esc_html__( 'Email', 'helendo' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'product_share_socials',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			// Upsells Products
			'upsells_products'                => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Upsells Products', 'helendo' ),
				'section'     => 'upsells_product',
				'default'     => 0,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to show upsells products in single product page', 'helendo' ),
			),
			'upsells_products_title'          => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Upsells Products Title', 'helendo' ),
				'section'  => 'upsells_product',
				'default'  => esc_html__( 'You may also like', 'helendo' ),
				'priority' => 40,
			),
			'upsells_products_columns'        => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Upsells Products Columns', 'helendo' ),
				'section'     => 'upsells_product',
				'default'     => '4',
				'priority'    => 40,
				'description' => esc_html__( 'Specify how many columns of upsells products you want to show on single product page', 'helendo' ),
				'choices'     => array(
					'3' => esc_html__( '3 Columns', 'helendo' ),
					'4' => esc_html__( '4 Columns', 'helendo' ),
					'5' => esc_html__( '5 Columns', 'helendo' ),
					'6' => esc_html__( '6 Columns', 'helendo' ),
				),
			),
			'upsells_products_numbers'        => array(
				'type'        => 'number',
				'label'       => esc_html__( 'Upsells Products Numbers', 'helendo' ),
				'section'     => 'upsells_product',
				'default'     => 6,
				'priority'    => 40,
				'description' => esc_html__( 'Specify how many numbers of upsells products you want to show on single product page', 'helendo' ),
			),
			// Related Products
			'related_products'                => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Related Products', 'helendo' ),
				'section'     => 'related_product',
				'default'     => 0,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to show related products in single product page', 'helendo' ),
			),
			'related_products_title'          => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Related Products Title', 'helendo' ),
				'section'  => 'related_product',
				'default'  => esc_html__( 'Related products', 'helendo' ),
				'priority' => 40,
			),
			'related_products_columns'        => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Related Products Columns', 'helendo' ),
				'section'     => 'related_product',
				'default'     => '4',
				'priority'    => 40,
				'description' => esc_html__( 'Specify how many columns of related products you want to show on single product page', 'helendo' ),
				'choices'     => array(
					'3' => esc_html__( '3 Columns', 'helendo' ),
					'4' => esc_html__( '4 Columns', 'helendo' ),
					'5' => esc_html__( '5 Columns', 'helendo' ),
					'6' => esc_html__( '6 Columns', 'helendo' ),
				),
			),
			'related_products_numbers'        => array(
				'type'        => 'number',
				'label'       => esc_html__( 'Related Products Numbers', 'helendo' ),
				'section'     => 'related_product',
				'default'     => 6,
				'priority'    => 40,
				'description' => esc_html__( 'Specify how many numbers of related products you want to show on single product page', 'helendo' ),
			),
			// Products Instagram
			'product_instagram'               => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Instagram Photos', 'helendo' ),
				'section'     => 'instagram_photos',
				'default'     => 1,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to show instagram photos in single product page', 'helendo' ),
			),
			'instagram_access_method'        => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Access method', 'helendo' ),
				'section'     => 'instagram_photos',
				'default'     => 'token',
				'priority'    => 40,
				'description' => esc_html__( 'Select method to access to your instagram', 'helendo' ),
				'choices'     => array(
					'token' => esc_html__( 'Access Token', 'helendo' ),
					'user'  => esc_html__( 'User', 'helendo' ),
				),
			),
			'instagram_token'                 => array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Access Token', 'helendo' ),
				'section'     => 'instagram_photos',
				'default'     => '',
				'priority'    => 40,
				'description' => esc_html__( 'Enter your Access Token', 'helendo' ),
				'active_callback' => array(
					array(
						'setting'  => 'instagram_access_method',
						'operator' => '==',
						'value'    => 'token',
					),
				),
			),
			'instagram_user'                  => array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'User', 'helendo' ),
				'section'     => 'instagram_photos',
				'default'     => '',
				'priority'    => 40,
				'description' => esc_html__( 'Enter your user', 'helendo' ),
				'active_callback' => array(
					array(
						'setting'  => 'instagram_access_method',
						'operator' => '==',
						'value'    => 'user',
					),
				),
			),
			'product_instagram_title'         => array(
				'type'     => 'textarea',
				'label'    => esc_html__( 'Product Instagram Title', 'helendo' ),
				'section'  => 'instagram_photos',
				'default'  => esc_html__( 'See It Styled On Instagram', 'helendo' ),
				'priority' => 40,
			),
			'product_instagram_columns'       => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Instagram Photos Columns', 'helendo' ),
				'section'     => 'instagram_photos',
				'default'     => '5',
				'priority'    => 40,
				'description' => esc_html__( 'Specify how many columns of Instagram Photos you want to show on single product page', 'helendo' ),
				'choices'     => array(
					'3' => esc_html__( '3 Columns', 'helendo' ),
					'4' => esc_html__( '4 Columns', 'helendo' ),
					'5' => esc_html__( '5 Columns', 'helendo' ),
					'6' => esc_html__( '6 Columns', 'helendo' ),
					'7' => esc_html__( '7 Columns', 'helendo' ),
				),
			),
			'product_instagram_numbers'       => array(
				'type'        => 'number',
				'label'       => esc_html__( 'Instagram Photos Numbers', 'helendo' ),
				'section'     => 'instagram_photos',
				'default'     => 10,
				'priority'    => 40,
				'description' => esc_html__( 'Specify how many Instagram Photos you want to show on single product page.', 'helendo' ),
			),
			'product_instagram_image_size'    => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Instagram Image Size', 'helendo' ),
				'section'  => 'instagram_photos',
				'default'  => 'low_resolution',
				'priority' => 40,
				'choices'  => array(
					'low_resolution'      => esc_html__( 'Low', 'helendo' ),
					'thumbnail'           => esc_html__( 'Thumbnail', 'helendo' ),
					'standard_resolution' => esc_html__( 'Standard', 'helendo' ),
				),
			),
			// Upsells Products
			'cross_sells_products'            => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Upsells Products', 'helendo' ),
				'section'     => 'cross_sells_product',
				'default'     => 1,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to show cross-sells products in the cart page', 'helendo' ),
			),
			'cross_sells_products_title'      => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Upsells Products Title', 'helendo' ),
				'section'  => 'cross_sells_product',
				'default'  => esc_html__( 'You may be interested in...', 'helendo' ),
				'priority' => 40,
			),
			'cross_sells_products_columns'    => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Upsells Products Columns', 'helendo' ),
				'section'     => 'cross_sells_product',
				'default'     => '4',
				'priority'    => 40,
				'description' => esc_html__( 'Specify how many columns of upsells products you want to show on the cart page', 'helendo' ),
				'choices'     => array(
					'3' => esc_html__( '3 Columns', 'helendo' ),
					'4' => esc_html__( '4 Columns', 'helendo' ),
					'5' => esc_html__( '5 Columns', 'helendo' ),
					'6' => esc_html__( '6 Columns', 'helendo' ),
				),
			),
			'cross_sells_products_numbers'    => array(
				'type'        => 'number',
				'label'       => esc_html__( 'Upsells Products Numbers', 'helendo' ),
				'section'     => 'cross_sells_product',
				'default'     => 6,
				'priority'    => 40,
				'description' => esc_html__( 'Specify how many numbers of upsells products you want to show on the cart page', 'helendo' ),
			),

			// Mobile
			'shop_toolbar_mobile'             => array(
				'type'     => 'multicheck',
				'label'    => esc_html__( 'Shop Toolbar Mobile', 'helendo' ),
				'section'  => 'shop_mobile',
				'default'  => array( 'sort-by', 'filter' ),
				'priority' => 40,
				'choices'  => array(
					'sort-by' => esc_html__( 'Sort by', 'helendo' ),
					'found'   => esc_html__( 'Products found', 'helendo' ),
					'result'  => esc_html__( 'Result', 'helendo' ),
					'filter'  => esc_html__( 'Filter', 'helendo' ),
				),

			),
		)
	);

	return $fields;
}

add_filter( 'helendo_customize_fields', 'helendo_woocommerce_customize_fields' );

/**
 * Get product attributes
 *
 * @return array
 */
function helendo_product_attributes() {
	$output = array();
	if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
		$attributes_tax = wc_get_attribute_taxonomies();
		if ( $attributes_tax ) {
			$output['none'] = esc_html__( 'None', 'helendo' );

			foreach ( $attributes_tax as $attribute ) {
				$output[$attribute->attribute_name] = $attribute->attribute_label;
			}

		}
	}

	return $output;
}

/**
 * Options of shop toolbar items
 *
 * @return array
 */
function helendo_shop_toolbar_items_option() {
	return apply_filters(
		'helendo_shop_toolbar_items_option', array(
			''        => esc_html__( 'Select an item', 'helendo' ),
			'cat'     => esc_html__( 'Categories', 'helendo' ),
			'found'   => esc_html__( 'Products found', 'helendo' ),
			'sort-by' => esc_html__( 'Sort by', 'helendo' ),
			'result'  => esc_html__( 'Result', 'helendo' ),
			'columns' => esc_html__( 'Columns Switcher', 'helendo' ),
			'filter'  => esc_html__( 'Filter', 'helendo' ),
		)
	);
}