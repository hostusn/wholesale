<?php
/**
 * Custom layout functions  by hooking templates
 *
 * @package Helendo
 */


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function helendo_body_classes( $classes ) {
	$header_type = helendo_get_option( 'header_type' );

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( is_singular( 'post' ) ) {
		$classes[] = helendo_get_option( 'single_post_layout' );
	}
	if ( helendo_is_blog() || is_search() ) {
		$classes[] = 'helendo-blog-page';
		$classes[] = 'blog-' . helendo_get_option( 'blog_view' );
		$classes[] = helendo_get_option( 'blog_layout' );

		if ( 'grid' == helendo_get_option( 'blog_view' ) ) {
			$classes[] = 'blog-grid-style-' . helendo_get_option( 'blog_grid_style' );
		}
	} elseif ( helendo_is_catalog() ) {
		$classes[] = 'navigation-type-' . helendo_get_option( 'catalog_nav_type' );
		$classes[] = 'helendo-catalog-page';
		$classes[] = 'helendo-catalog-mobile-' . intval( helendo_get_option( 'catalog_mobile_columns' ) ) . '-columns';

		if ( intval( helendo_get_option( 'catalog_without_gutter' ) ) ) {
			$classes[] = 'catalog-without-gutter';
		}

		if ( intval( helendo_get_option( 'catalog_with_border' ) ) ) {
			$classes[] = 'catalog-with-border';
		}

		if ( intval( helendo_get_option( 'catalog_ajax_filter' ) ) ) {
			$classes[] = 'catalog-ajax-filter';
		}

		if ( intval( helendo_get_option( 'catalog_full_width' ) ) ) {
			$classes[] = 'catalog-full-width';
		}

		if ( intval( helendo_get_option( 'catalog_filter_mobile' ) ) ) {
			$classes[] = 'filter-mobile-enable';
		}
	}

	$search_type   = helendo_get_option( 'header_search_style' );
	$classes[] = 'helendo-search-' . $search_type;

	$classes[] = helendo_get_layout();

	$classes[] = 'header-' . $header_type;

	if ( $header_type == 'default' ) {
		$classes[] = 'header-' . helendo_get_option( 'header_layout' );
	}

	if ( helendo_is_page_template() ) {
		$classes[] = 'helendo-page-template';
	}

	if ( helendo_is_homepage() && intval( helendo_get_option( 'header_transparent' ) ) ) {
		$classes[] = 'header-transparent';
	}

	if ( intval( helendo_get_option( 'header_sticky' ) ) && ! helendo_is_maintenance_page() ) {
		$classes[] = 'header-sticky';
	}

	if ( helendo_get_option( 'header_text_color' ) == 'light' ) {
		$classes[] = 'header-text-' . helendo_get_option( 'header_text_color' );
	}

	$header_main_left    = wp_list_pluck( helendo_get_option( 'header_main_left' ), 'item' );
	$header_main_right   = wp_list_pluck( helendo_get_option( 'header_main_right' ), 'item' );
	$header_bottom_left  = wp_list_pluck( helendo_get_option( 'header_bottom_left' ), 'item' );
	$header_bottom_right = wp_list_pluck( helendo_get_option( 'header_bottom_right' ), 'item' );

	$header_left  = array_merge( $header_main_left, $header_bottom_left );
	$header_right = array_merge( $header_main_right, $header_bottom_right );

	if ( $header_type == 'custom' ) {
		if ( in_array( 'menu', $header_left ) ) {
			$classes[] = 'menu-sidebar-left';
		}

		if ( in_array( 'menu', $header_right ) ) {
			$classes[] = 'menu-sidebar-right';
		}

		if ( in_array( 'cart', $header_left ) ) {
			$classes[] = 'cart-sidebar-left';
		}

		if ( in_array( 'cart', $header_right ) ) {
			$classes[] = 'cart-sidebar-right';
		}
	} else {
		if ( helendo_get_option( 'header_layout' ) == 'v2' ) {
			$classes[] = 'menu-sidebar-left';
		}
	}

	$canvas_panel = helendo_get_option( 'canvas_panel_width_mobile' );
	$classes[] = 'canvas-panel-' . $canvas_panel;

	if ( intval( helendo_get_option( 'footer_fixed' ) ) ) {
		$classes[] = 'footer-fixed';
	}

	return $classes;
}

add_filter( 'body_class', 'helendo_body_classes' );

/**
 * Print the open tags of site content container
 */

if ( ! function_exists( 'helendo_open_site_content_container' ) ) :
	function helendo_open_site_content_container() {

		printf( '<div class="%s"><div class="row">', esc_attr( apply_filters( 'helendo_site_content_container_class', helendo_content_container_class() ) ) );
	}
endif;

add_action( 'helendo_after_site_content_open', 'helendo_open_site_content_container' );

/**
 * Print the close tags of site content container
 */

if ( ! function_exists( 'helendo_close_site_content_container' ) ) :
	function helendo_close_site_content_container() {
		print( '</div></div>' );
	}

endif;

add_action( 'helendo_before_site_content_close', 'helendo_close_site_content_container' );

