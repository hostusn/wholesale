<?php
/**
 * Custom layout functions
 *
 * @package Helendo
 */


/**
 * Get classes for content area
 *
 * @since  1.0
 *
 * @return string of classes
 */

if ( ! function_exists( 'helendo_content_container_class' ) ) :
	function helendo_content_container_class() {
		if (
			is_page_template( 'template-home-left-sidebar.php' ) ||
			is_page_template( 'template-home-page.php' ) ||
			is_page_template( 'template-home-boxed.php' ) ||
			is_page_template( 'template-fullwidth.php' )
		) {
			return 'container-fluid';

		} elseif ( helendo_is_catalog() ) {
			if ( intval( helendo_get_option( 'catalog_full_width' ) ) && helendo_get_layout() == 'full-content' ) {
				if ( intval( helendo_get_option( 'catalog_without_gutter' ) ) ) {
					return 'container-fluid';
				}
				return 'helendo-container';
			}
		} elseif ( is_page_template( 'homepage-fullwidth.php' ) ) {
			return 'helendo-container-full-width';
		}

		return 'container';
	}

endif;


if ( ! function_exists( 'helendo_get_layout' ) ) :
	function helendo_get_layout() {

		$layout = helendo_get_option( 'blog_layout' );
		if ( is_singular( 'post' ) ) {
			$layout = helendo_get_option( 'single_post_layout' );
			if ( ! is_active_sidebar( 'blog-sidebar' ) ) {
				$layout = 'full-content';
			}
		} elseif ( helendo_is_blog() || is_search() ) {
			if ( 'classic' != helendo_get_option( 'blog_view' ) ) {
				$layout = 'full-content';
			}

			if ( ! is_active_sidebar( 'blog-sidebar' ) ) {
				$layout = 'full-content';
			}
		} elseif ( helendo_is_catalog() ) {
			$layout = helendo_get_option( 'catalog_layout' );
		} elseif ( is_page() || is_404() ) {
			$layout = 'full-content';
		} elseif ( is_singular( 'product' ) ) {
			$layout = helendo_get_option( 'product_page_sidebar' );
		}

		return apply_filters( 'helendo_site_layout', $layout );
	}

endif;

/**
 * Get Bootstrap column classes for content area
 *
 * @since  1.0
 *
 * @return array Array of classes
 */

if ( ! function_exists( 'helendo_get_content_columns' ) ) :
	function helendo_get_content_columns( $layout = null ) {
		$layout  = $layout ? $layout : helendo_get_layout();
		$classes = array( 'col-md-9', 'col-sm-12', 'col-xs-12' );

		if ( $layout == 'full-content' ) {
			$classes = array( 'col-md-12' );
		}

		return $classes;
	}

endif;

/**
 * Echos Bootstrap column classes for content area
 *
 * @since 1.0
 */

if ( ! function_exists( 'helendo_content_columns' ) ) :
	function helendo_content_columns( $layout = null ) {
		echo implode( ' ', helendo_get_content_columns( $layout ) );
	}
endif;
