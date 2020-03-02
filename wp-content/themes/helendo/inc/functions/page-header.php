<?php
/**
 * Custom functions that act on page header templates
 *
 * @package Helendo
 */

/**
 * Get page header layout
 *
 * @return array
 */

if ( ! function_exists( 'helendo_get_page_header' ) ) :
	function helendo_get_page_header() {
		if ( is_404() || helendo_is_homepage() ) {
			return false;
		}

		if ( helendo_is_blog() ) {
			if ( ! intval( helendo_get_option( 'page_header_blog' ) ) ) {
				return false;
			}
			$page_header = helendo_get_option( 'page_header_blog_els' );

			if ( intval( helendo_get_option( 'page_header_blog_full_width' ) ) ) {
				array_push( $page_header, 'full_width' );
			}

		} elseif ( is_singular( 'post' ) ) {
			if ( ! intval( helendo_get_option( 'page_header_post' ) ) ) {
				return false;
			}
			$page_header = helendo_get_option( 'page_header_post_els' );
			if ( intval( helendo_get_option( 'page_header_post_full_width' ) ) ) {
				array_push( $page_header, 'full_width' );
			}

		} elseif ( is_page() ) {
			if ( ! intval( helendo_get_option( 'page_header_page' ) ) ) {
				return false;
			}

			$page_header = helendo_get_option( 'page_header_page_els' );
			if ( intval( helendo_get_option( 'page_header_page_full_width' ) ) ) {
				array_push( $page_header, 'full_width' );
			}


		} elseif ( helendo_is_catalog() ) {
			if ( ! intval( helendo_get_option( 'page_header_catalog' ) ) ) {
				return false;
			}
			$page_header = helendo_get_option( 'page_header_catalog_els' );
			if ( intval( helendo_get_option( 'page_header_catalog_full_width' ) ) ) {
				array_push( $page_header, 'full_width' );
			}
		} elseif ( is_singular( 'product' ) ) {
			if ( ! intval( helendo_get_option( 'page_header_product' ) ) ) {
				return false;
			}
			$page_header = helendo_get_option( 'page_header_product_els' );
			if ( intval( helendo_get_option( 'page_header_product_full_width' ) ) ) {
				array_push( $page_header, 'full_width' );
			}

		} else {
			if ( ! intval( helendo_get_option( 'page_header' ) ) ) {
				return false;
			}
			$page_header = helendo_get_option( 'page_header_els' );
		}

		$page_header = helendo_custom_page_header( $page_header );

		return $page_header;


	}

endif;


/**
 * Get custom page header layout
 *
 * @return array
 */
if ( ! function_exists( 'helendo_custom_page_header' ) ) :
	function helendo_custom_page_header( $page_header ) {

		if ( ! is_singular( 'page' ) && ! is_singular( 'post' ) && ! is_singular( 'product' ) && ! ( function_exists( 'is_shop' ) && is_shop() ) ) {
			return $page_header;
		}
		$post_id = get_the_ID();
		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$post_id = get_option( 'woocommerce_shop_page_id' );
		}

		if ( empty( $page_header ) ) {
			return false;
		}

		$hide_page_header = get_post_meta( $post_id, 'hide_page_header', true );
		if ( $hide_page_header ) {
			return false;
		}
		if ( get_post_meta( $post_id, 'hide_breadcrumb', true ) ) {

			$key = array_search( 'breadcrumb', $page_header );
			if ( $key !== false ) {
				unset( $page_header[ $key ] );
			}
		}

		if ( get_post_meta( $post_id, 'hide_title', true ) ) {

			$key = array_search( 'title', $page_header );
			if ( $key !== false ) {
				unset( $page_header[ $key ] );
			}
		}

		if ( get_post_meta( $post_id, 'full_width', true ) ) {
			array_push( $page_header, 'full_width' );
		}

		return $page_header;
	}
endif;