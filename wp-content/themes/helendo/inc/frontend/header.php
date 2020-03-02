<?php
/**
 * Hooks for template header
 *
 * @package Helendo
 */


/**
 * Enqueue scripts and styles.
 */
function helendo_scripts() {
	/**
	 * Register and enqueue styles
	 */
	wp_register_style( 'helendo-fonts', helendo_fonts_url(), array(), '20180831' );
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.7' );
	wp_register_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.7.0' );
	wp_register_style( 'eleganticons', get_template_directory_uri() . '/css/eleganticons.min.css', array(), '1.0.0' );
	wp_register_style( 'linearicons', get_template_directory_uri() . '/css/linearicons.min.css', array(), '1.0.0' );
	wp_register_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '4.7.0' );
	wp_register_style( 'photoswipe', get_template_directory_uri() . '/css/photoswipe.min.css', array(), '4.7.0' );
	wp_enqueue_style(
		'helendo', get_template_directory_uri() . '/style.css', array(
		'helendo-fonts',
		'font-awesome',
		'bootstrap',
		'eleganticons',
		'linearicons',
		'slick',
		'photoswipe',
	), '20180831'
	);

	wp_add_inline_style( 'helendo', helendo_get_inline_style() );

	/**
	 * Register and enqueue scripts
	 */

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/plugins/html5shiv.min.js', array(), '3.7.2' );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/plugins/respond.min.js', array(), '1.4.2' );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

	wp_register_script( 'photoswipe', get_template_directory_uri() . '/js/plugins/photoswipe.min.js', array(), '4.1.1', true );
	wp_register_script( 'photoswipe-ui', get_template_directory_uri() . '/js/plugins/photoswipe-ui.min.js', array( 'photoswipe' ), '4.1.1', true );
	wp_register_script( 'isotope', get_template_directory_uri() . '/js/plugins/isotope.pkgd.min.js', array(), '2.2.2', true );
	wp_register_script( 'slick', get_template_directory_uri() . '/js/plugins/slick.min.js', array(), '1.0', true );
	wp_register_script( 'waypoints', get_template_directory_uri() . '/js/plugins/waypoints.min.js', array(), '2.0.2', true );
	wp_register_script( 'flipclock', get_template_directory_uri() . '/js/plugins/flipclock.min.js', array(), '1.0', true );
	wp_register_script( 'isInViewport', get_template_directory_uri() . '/js/plugins/isInViewport.min.js', array(), '1.1.0', true );
	wp_register_script( 'notify', get_template_directory_uri() . '/js/plugins/notify.min.js', array(), '1.0.0', true );

	wp_enqueue_style( 'photoswipe' );
	wp_enqueue_script( 'photoswipe-ui' );

	$photoswipe_skin = 'photoswipe-default-skin';
	if ( wp_style_is( $photoswipe_skin, 'registered' ) && ! wp_style_is( $photoswipe_skin, 'enqueued' ) ) {
		wp_enqueue_style( $photoswipe_skin );
	}

	wp_enqueue_script(
		'helendo', get_template_directory_uri() . "/js/scripts$min.js", array(
		'jquery',
		'slick',
		'imagesloaded',
		'isotope',
		'photoswipe',
		'waypoints',
		'flipclock',
		'isInViewport',
		'notify',
		'jquery-ui-tooltip'
	), '20180831', true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script(
		'helendo', 'helendoData', array(
			'direction'              => is_rtl() ? 'true' : 'false',
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
			'nonce'                  => wp_create_nonce( '_helendo_nonce' ),
			'search_content_type'    => helendo_get_option( 'header_search_type' ),
			'header_ajax_search'     => intval( helendo_get_option( 'header_search_ajax' ) ),
			'nothing_found_text'     => apply_filters( 'helendo_nothing_found_text', esc_html__( 'Nothing found', 'helendo' ) ),
			'days'                   => esc_html__( 'days', 'helendo' ),
			'hours'                  => esc_html__( 'hours', 'helendo' ),
			'minutes'                => esc_html__( 'minutes', 'helendo' ),
			'seconds'                => esc_html__( 'seconds', 'helendo' ),
			'product_gallery'        => intval( helendo_get_option( 'product_images_lightbox' ) ),
			'catalog_mobile_columns' => intval( helendo_get_option( 'catalog_mobile_columns' ) ),
			'add_to_cart_ajax'       => intval( helendo_get_option( 'product_add_to_cart_ajax' ) ),
			'nl_days'                => intval( helendo_get_option( 'newsletter_reappear' ) ),
			'nl_seconds'             => intval( helendo_get_option( 'newsletter_visible' ) ) == 2 ? intval( helendo_get_option( 'newsletter_seconds' ) ) : 0,
			'l10n'                   => array(
				'added_to_cart_notice'  => intval( helendo_get_option( 'added_to_cart_notice' ) ),
				'notice_text'           => esc_html__( 'has been added to your cart.', 'helendo' ),
				'notice_texts'          => esc_html__( 'have been added to your cart.', 'helendo' ),
				'cart_text'             => esc_html__( 'View Cart', 'helendo' ),
				'cart_link'             => function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '',
				'cart_notice_auto_hide' => intval( helendo_get_option( 'cart_notice_auto_hide' ) ) > 0 ? intval( helendo_get_option( 'cart_notice_auto_hide' ) ) * 1000 : 0,
			),
			'product_carousel'       => array(
				'related'     => intval( helendo_get_option( 'related_product_dot' ) ),
				'upsells'     => intval( helendo_get_option( 'upsells_product_dot' ) ),
				'cross_sells' => intval( helendo_get_option( 'cross_sells_product_dot' ) ),
				'instagram'   => intval( helendo_get_option( 'instagram_photos_dot' ) ),
			),
			'single_product_layout'  => helendo_get_option( 'product_page_sidebar' )
		)
	);
}

add_action( 'wp_enqueue_scripts', 'helendo_scripts' );

/**
 * Display the site header
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'helendo_show_header' ) ) :
	function helendo_show_header() {
		if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
			return;
		}

		if ( 'default' == helendo_get_option( 'header_type' ) ) {
			helendo_prebuild_header( helendo_get_option( 'header_layout' ) );
		} else {
			// Header main.
			$sections = array(
				'left'   => helendo_get_option( 'header_main_left' ),
				'center' => helendo_get_option( 'header_main_center' ),
				'right'  => helendo_get_option( 'header_main_right' ),
			);

			$classes = array( 'header-main', 'header-contents', 'hidden-md hidden-xs hidden-sm' );

			helendo_header_contents( $sections, array( 'class' => $classes ) );

			// Header bottom.
			$sections = array(
				'left'   => helendo_get_option( 'header_bottom_left' ),
				'center' => helendo_get_option( 'header_bottom_center' ),
				'right'  => helendo_get_option( 'header_bottom_right' ),
			);

			$border = helendo_get_option( 'header_bottom_border_top' );
			$border = $border ? 'has-border' : '';

			$classes = array( 'header-bottom', 'header-contents', 'hidden-md hidden-xs hidden-sm', $border );

			helendo_header_contents( $sections, array( 'class' => $classes ) );
		}
	}
endif;
add_action( 'helendo_header', 'helendo_show_header' );

/**
 * Display header elements - header left sidebar template
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'helendo_show_header_left_sidebar' ) ) :
	function helendo_show_header_left_sidebar() {
		if ( ! is_page_template( 'template-home-left-sidebar.php' ) ) {
			return;
		}

		$items = get_post_meta( get_the_ID(), 'header_items', false );

		if ( empty( $items ) ) {
			return;
		}

		$items_array = array();

		if ( in_array( 'search', $items ) ) {
			$items_array[] = array( 'item' => 'search' );
		}

		if ( in_array( 'wishlist', $items ) ) {
			$items_array[] = array( 'item' => 'wishlist' );
		}

		if ( in_array( 'cart', $items ) ) {
			$items_array[] = array( 'item' => 'cart' );
		}

		if ( in_array( 'account', $items ) ) {
			$items_array[] = array( 'item' => 'account' );
		}

		$main_sections = array(
			'left'   => array(),
			'center' => array(),
			'right'  => $items_array
		);

		$bottom_sections = array();

		$classes = array( 'header-main', 'header-contents', 'hidden-md hidden-xs hidden-sm' );
		helendo_header_contents( $main_sections, array( 'class' => $classes ) );

		$classes = array( 'header-bottom', 'header-contents', 'hidden-md hidden-xs hidden-sm' );
		helendo_header_contents( $bottom_sections, array( 'class' => $classes ) );
	}
endif;
add_action( 'helendo_header', 'helendo_show_header_left_sidebar' );

/**
 * Filter Header container class
 */
if ( ! function_exists( 'helendo_get_header_container_class' ) ) :
	function helendo_get_header_container_class( $container = 'container' ) {
		$header_layout = helendo_get_option( 'header_layout' );
		if ( 'custom' == helendo_get_option( 'header_type' ) || is_page_template( 'template-home-left-sidebar.php' ) ) {
			$container = helendo_get_option( 'header_container' );

		} else {
			if ( 'v1' != $header_layout && 'v5' != $header_layout ) {
				$container = 'helendo-container';
			}
		}

		return $container;
	}
endif;
add_filter( 'helendo_header_container_class', 'helendo_get_header_container_class' );

/**
 * Header Video
 */
if ( ! function_exists( 'helendo_get_header_banner' ) ) :

	function helendo_get_header_banner() {
		$header_banner = get_post_meta( get_the_ID(), 'header_banner', true );
		$sliders       = get_post_meta( get_the_ID(), 'slider', true );

		if ( ! $header_banner ) {
			return;
		}

		if ( ! $sliders ) {
			return;
		}

		if ( ! helendo_is_homepage() ) {
			return;
		}

		$output = sprintf( '<div class="section-sliders">%s</div>', do_shortcode( '[rev_slider_vc alias="' . $sliders . '"]' ) );

		printf(
			'<div class="header-video">%s</div>',
			$output
		);
	}

endif;

add_action( 'helendo_before_header', 'helendo_get_header_banner', 10 );

/**
 * Display the header minimized
 *
 * @since 1.0.0
 */
function helendo_header_minimized() {
	if ( ! intval( helendo_get_option( 'header_sticky' ) ) ) {
		return;
	}

	if ( helendo_is_maintenance_page() ) {
		return;
	}

	$css_class = 'helendo-header-' . helendo_get_option( 'header_layout' );

	printf( '<div id="helendo-header-minimized" class="helendo-header-minimized %s"></div>', esc_attr( $css_class ) );

}

add_action( 'helendo_before_header', 'helendo_header_minimized' );

if ( ! function_exists( 'helendo_mobile_header_left_icons' ) ) :
	/**
	 * Display mobile header icons
	 */
	function helendo_mobile_header_left_icons() {
		$icons = helendo_get_option( 'mobile_header_left_icons' );

		if ( empty( $icons ) ) {
			return;
		}

		foreach ( $icons as $icon ) {
			$icon['item'] = $icon['item'] ? $icon['item'] : key( helendo_mobile_header_left_icons_option() );

			get_template_part( 'template-parts/header/' . $icon['item'] );
		}
	}
endif;

if ( ! function_exists( 'helendo_mobile_header_right_icons' ) ) :
	/**
	 * Display mobile header icons
	 */
	function helendo_mobile_header_right_icons() {
		$icons = helendo_get_option( 'mobile_header_right_icons' );

		if ( empty( $icons ) ) {
			return;
		}

		foreach ( $icons as $icon ) {
			$icon['item'] = $icon['item'] ? $icon['item'] : key( helendo_mobile_header_right_icons_option() );

			get_template_part( 'template-parts/header/' . $icon['item'] );
		}
	}
endif;

