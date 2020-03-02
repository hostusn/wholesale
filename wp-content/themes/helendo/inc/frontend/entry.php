<?php
/**
 * Hooks and functions for blog and other content types
 *
 * @package helendo
 */

if ( ! function_exists( 'helendo_pre_get_posts' ) ) :
	function helendo_pre_get_posts( $query ) {
		if ( is_admin() ) {
			return;
		}

		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( ( $query->get( 'page_id' ) == get_option( 'page_on_front' ) || is_front_page() )
			&& ( get_option( 'woocommerce_shop_page_id' ) !=  get_option( 'page_on_front' ) ) ) {
			return;
		}

		if ( isset( $_POST['number_search_items'] ) && $query->is_search() ) {
			$query->set( 'posts_per_page', intval( $_REQUEST['number_search_items'] ) );
		}
	}
endif;

add_action( 'pre_get_posts', 'helendo_pre_get_posts' );

/**
 * Add icon list as svg at the footer
 * It is hidden
 */
function helendo_include_shadow_icons() {
	echo '<div id="del-svg-defs" class="del-svg-defs hidden">';
	include get_template_directory() . '/images/sprite.svg';
	echo '</div>';
}

add_action( 'helendo_before_site', 'helendo_include_shadow_icons' );


/**
 * Change markup of archive and category widget to include .count for post count
 *
 * @param string $output
 *
 * @return string
 */

if( ! function_exists('helendo_widget_archive_count') ) {
	function helendo_widget_archive_count( $output ) {
		$output = preg_replace( '|\((\d+)\)|', '<span class="posts-count">(\\1)</span>', $output );

		return $output;
	}
}

add_filter( 'wp_list_categories', 'helendo_widget_archive_count' );
add_filter( 'get_archives_link', 'helendo_widget_archive_count' );