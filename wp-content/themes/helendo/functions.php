<?php
/**
 * Helendo functions and definitions
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Helendo
 */

if ( ! function_exists( 'helendo_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function helendo_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'helendo', get_template_directory() . '/languages' );

		// Theme supports
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );

		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		add_theme_support( 'customize-selective-refresh-widgets' );

		add_editor_style( 'css/editor-style.css' );

		// Load regular editor styles into the new block-based editor.
		add_theme_support( 'editor-styles' );

		// Load default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		add_theme_support( 'align-wide' );

		add_theme_support( 'align-full' );

		add_image_size( 'helendo-single-post-thumb', 1170, 672, true );
		add_image_size( 'helendo-post-full', 1170, 500, true );
		add_image_size( 'helendo-post-large', 870, 500, true );
		add_image_size( 'helendo-post-list', 1170, 370, true );

		add_image_size( 'helendo-post-grid', 570, 370, true );
		add_image_size( 'helendo-post-grid-v2', 585, 240, true );
		add_image_size( 'helendo-post-grid-s2', 570, 416, true );

		add_image_size( 'helendo-blog-masonry-1', 570, 400, true );
		add_image_size( 'helendo-blog-masonry-2', 570, 770, true );
		add_image_size( 'helendo-blog-masonry-3', 570, 585, true );

		add_image_size( 'helendo-search-thumb', 570, 570, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'helendo' ),
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'helendo_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function helendo_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'helendo_content_width', 640 );
}

add_action( 'after_setup_theme', 'helendo_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function helendo_widgets_init() {
	$sidebars = array(
		'blog-sidebar'        => esc_html__( 'Blog Sidebar', 'helendo' ),
		'menu-sidebar'        => esc_html__( 'Menu Sidebar', 'helendo' ),
		'catalog-filter'      => esc_html__( 'Catalog Filter', 'helendo' ),
		'catalog-sidebar'     => esc_html__( 'Catalog Sidebar', 'helendo' ),
		'header-left-sidebar' => esc_html__( 'Header Left Sidebar', 'helendo' ),
	);

	if ( helendo_get_option( 'product_page_sidebar' ) != 'full-content' ) {
		$sidebars['product-sidebar'] = esc_html__( 'Product Sidebar', 'helendo' );
	}

	// Register sidebars
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'description'   => esc_html__( 'Add widgets here in order to display on pages', 'helendo' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	register_sidebar(
		array(
			'name'          => esc_html__( 'Mobile Menu Sidebar', 'helendo' ),
			'id'            => 'mobile-menu-sidebar',
			'description'   => esc_html__( 'Add widgets here in order to display menu sidebar on mobile', 'helendo' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	if ( intval( helendo_get_option( 'footer_widgets' ) ) ) {
		for ( $i = 1; $i < 5; $i ++ ) {
			register_sidebar(
				array(
					'name'          => sprintf( esc_html__( 'Footer Sidebar %s', 'helendo' ), $i ),
					'id'            => 'footer-' . $i,
					'description'   => esc_html__( 'Add widgets here in order to display on footer', 'helendo' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				)
			);
		}
	}
}

add_action( 'widgets_init', 'helendo_widgets_init' );

/**
 * Custom functions for the theme.
 */
require get_template_directory() . '/inc/functions/header.php';
require get_template_directory() . '/inc/functions/page-header.php';
require get_template_directory() . '/inc/functions/layout.php';
require get_template_directory() . '/inc/functions/entry.php';
require get_template_directory() . '/inc/functions/comments.php';
require get_template_directory() . '/inc/functions/breadcrumbs.php';
require get_template_directory() . '/inc/functions/nav.php';
require get_template_directory() . '/inc/functions/style.php';
require get_template_directory() . '/inc/mega-menu/class-mega-menu-walker.php';

/**
 * Custom functions for the theme by hooking
 */

require get_template_directory() . '/inc/frontend/entry.php';
require get_template_directory() . '/inc/frontend/header.php';
require get_template_directory() . '/inc/frontend/page-header.php';
require get_template_directory() . '/inc/frontend/layout.php';
require get_template_directory() . '/inc/frontend/comments.php';
require get_template_directory() . '/inc/frontend/footer.php';
require get_template_directory() . '/inc/frontend/maintenance.php';
require get_template_directory() . '/inc/frontend/mobile.php';
require get_template_directory() . '/inc/frontend/search-ajax.php';

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce/woocommerce.php';
}

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/backend/plugins.php';
	require get_template_directory() . '/inc/backend/meta-boxes.php';
	require get_template_directory() . '/inc/backend/editor.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu.php';
}