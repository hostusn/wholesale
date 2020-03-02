<?php
/**
 * Template part for displaying the wishlist icon
 *
 * @package Henlendo
 */
if ( ! function_exists( 'WC' ) ) {
	return;
}

if ( ! defined( 'YITH_WCWL' ) ) {
	return;
}
?>


<div class="header-wishlist">
	<a href="<?php echo esc_url( get_permalink( yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ) ) ); ?>"
	   class="wishlist-contents">
		<span class="svg-icon icon-heart-o"><i class="icon-heart"></i></span>
		<span class="counter wishlist-counter"><?php echo intval( yith_wcwl_count_products() ); ?></span>
	</a>
</div>
