<?php
/**
 * Woocommerce Ajax
 *
 * @package Helendo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helendo_WooCommerce_Settings class.
 */
class Helendo_Search_Ajax {

	/**
	 * Constructor.
	 */
	public static function init() {
		add_action( 'wp_ajax_helendo_search_products', array( __CLASS__, 'instance_search_result' ) );
		add_action( 'wp_ajax_nopriv_helendo_search_products', array( __CLASS__, 'instance_search_result' ) );

	}

	/**
	 * Search products
	 *
	 * @since 1.0
	 */
	public static function instance_search_result() {
		if ( apply_filters( 'helendo_check_ajax_referer', true ) ) {
			check_ajax_referer( '_helendo_nonce', 'nonce' );
		}

		$response = array();
		$classes  = array( 'search-results-wrapper' );
		$tag      = '';
		$button   = '';

		$number_items = intval( helendo_get_option( 'header_search_number' ) );
		if ( isset( $_POST['search_type'] ) && $_POST['search_type'] == 'all' ) {
			$response  = self::instance_search_every_things_result($number_items);
			$tag       = 'div';
			$classes[] = 'helendo-post-list';
		} else {
			$response  = self::instance_search_products_result($number_items);
			$tag       = 'ul';
			$classes[] = 'products columns-3';
		}

		if ( empty( $response ) ) {
			if ( isset( $_POST['search_type'] ) && $_POST['search_type'] == 'all' ) {
				$response[] = sprintf( '<div class="not-found">%s</div>', esc_html__( 'Nothing found', 'helendo' ) );
			} else {
				$response[] = sprintf( '<li class="not-found">%s</li>', esc_html__( 'Nothing found', 'helendo' ) );
			}
		} else {
			$button = '<div class="view-more text-center"><a href="#" class="button alt">' . apply_filters( 'helendo_instance_search_button', esc_html__( 'View More', 'helendo' ) ) . '</a></div>';
		}

		$output = sprintf( '<%1$s class="%2$s">%3$s</%1$s>%4$s', $tag, esc_attr( implode( ' ', $classes ) ), implode( ' ', $response ), $button );

		wp_send_json_success( $output );
		die();
	}

	public static function instance_search_products_result($number_items) {
		$response = array();
		$args_sku = array(
			'post_type'        => 'product',
			'posts_per_page'   => $number_items,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args_variation_sku = array(
			'post_type'        => 'product_variation',
			'posts_per_page'   => $number_items,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args = array(
			'post_type'        => 'product',
			'posts_per_page'   => $number_items,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0
		);

		if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$args['tax_query'][]         = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['exclude-from-search'],
				'operator' => 'NOT IN',
			);
		}
		if ( isset( $_POST['cat'] ) && ! empty( $_POST['cat'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $_POST['cat'],
			);

			$args_sku['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $_POST['cat'],
				),

			);
		}

		$products = array(
			'products_sku'           => new WP_Query( $args_sku ),
			'products_s'             => new WP_Query( $args ),
			'products_variation_sku' => new WP_Query( $args_variation_sku ),
		);
		$post_ids = array();
		foreach ( $products as $product ) {
			if ( $product->have_posts() ) {
				while ( $product->have_posts() ) : $product->the_post();
					$id = get_the_ID();
					if ( in_array( $id, $post_ids ) ) {
						continue;
					}
					$post_ids[] = $id;
					ob_start();
					wc_get_template_part( 'content', 'product' );
					$response[] = ob_get_clean();
				endwhile; // end of the loop.
				wp_reset_postdata();
			}
		}

		return $response;
	}

	public static function instance_search_every_things_result($number_items) {
		$response = array();
		$args     = array(
			'post_type'        => 'any',
			'posts_per_page'   => $number_items,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0,
		);

		$posts    = new WP_Query( $args );
		$post_ids = array();
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) : $posts->the_post();
				$id = get_the_ID();
				if ( ! in_array( $id, $post_ids ) ) {
					$post_ids[] = $id;
					$response[] = sprintf(
						'<div class="blog-wapper">' .
						'<a class="post-thumbnail" href="%s">' .
						'%s' .
						'</a>' .
						'<a class="entry-title" href="%s">' .
						'%s' .
						'</a>' .
						'</div>',
						esc_url( get_the_permalink() ),
						get_the_post_thumbnail(),
						esc_url( get_the_permalink() ),
						get_the_title()
					);
				}
			endwhile; // end of the loop.
			wp_reset_postdata();
		}

		return $response;
	}
}

/**
 * WooCommerce initialize.
 */
function helendo_search_ajax_init() {
	Helendo_Search_Ajax::init();
}

add_action( 'wp_loaded', 'helendo_search_ajax_init' );