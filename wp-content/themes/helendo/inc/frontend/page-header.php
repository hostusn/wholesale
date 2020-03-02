<?php
/**
 * Custom functions that act on page header templates
 *
 * @package Helendo
 */

/**
 * Get page header
 *
 * @since  1.0
 *
 *
 */

if ( ! function_exists( 'helendo_page_header' ) ) :
	function helendo_page_header() {
		get_template_part( 'template-parts/page', 'header' );
	}

endif;
add_action( 'helendo_after_header', 'helendo_page_header' );

/**
 * The archive title
 *
 * @since  1.0
 *
 * @param  array $title
 *
 * @return mixed
 */
function helendo_the_archive_title( $title ) {
	if ( is_search() ) {
		$title = sprintf( esc_html__( 'Search Results', 'helendo' ) );
	} elseif ( is_404() ) {
		$title = sprintf( esc_html__( 'Page Not Found', 'helendo' ) );
	} elseif ( is_page() ) {
		$title = get_the_title();
	} elseif ( is_home() && is_front_page() ) {
		$title = esc_html__( 'The Latest Posts', 'helendo' );
	} elseif ( is_home() && ! is_front_page() ) {
		$title = get_the_title( get_option( 'page_for_posts' ) );
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$title = get_the_title();
	} elseif ( is_single() ) {
		$title = get_the_title();
	} elseif ( is_post_type_archive( 'portfolio_project' ) ) {
		$title = get_the_title( get_option( 'drf_portfolio_page_id' ) );
	} elseif ( is_tax() || is_category() ) {
		$title = single_term_title( '', false );
	}

	if ( get_option( 'woocommerce_shop_page_id' ) ) {
		if ( is_front_page() && ( get_option( 'woocommerce_shop_page_id' ) == get_option( 'page_on_front' ) ) ) {
			$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
		}
	}


	return $title;
}

add_filter( 'get_the_archive_title', 'helendo_the_archive_title', 30 );

if ( ! function_exists( 'helendo_page_header_style' ) ) :
	/**
	 * Get inline style data
	 */
	function helendo_page_header_style( $inline_css ) {
		if ( helendo_is_blog() ) {
			$padding_top    = helendo_get_option( 'page_header_blog_padding_top' );
			$padding_bottom = helendo_get_option( 'page_header_blog_padding_bottom' );
		} elseif ( is_singular( 'post' ) ) {
			$padding_top    = helendo_get_option( 'page_header_post_padding_top' );
			$padding_bottom = helendo_get_option( 'page_header_post_padding_bottom' );
		} elseif ( is_page() ) {
			$padding_top    = helendo_get_option( 'page_header_page_padding_top' );
			$padding_bottom = helendo_get_option( 'page_header_page_padding_bottom' );

		} elseif ( helendo_is_catalog() ) {
			$padding_top    = helendo_get_option( 'page_header_catalog_padding_top' );
			$padding_bottom = helendo_get_option( 'page_header_catalog_padding_bottom' );
		} elseif ( is_singular( 'product' ) ) {
			$padding_top    = helendo_get_option( 'page_header_product_padding_top' );
			$padding_bottom = helendo_get_option( 'page_header_product_padding_bottom' );
		} else {
			$padding_top    = helendo_get_option( 'page_header_padding_top' );
			$padding_bottom = helendo_get_option( 'page_header_padding_bottom' );
		}

		if ( $padding_top != '50' ) {
			$inline_css .= '.page-header { padding-top: ' . intval( $padding_top ) . 'px; }';
		}
		if ( $padding_bottom != '50' ) {
			$inline_css .= '.page-header { padding-bottom: ' . intval( $padding_bottom ) . 'px; }';
		}

		return $inline_css;


	}

endif;
add_filter( 'helendo_inline_style', 'helendo_page_header_style' );