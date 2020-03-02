<?php
/**
 * Load and register widgets
 *
 * @package Helendo
 */

require_once HELENDO_ADDONS_DIR . '/inc/widgets/socials.php';
require_once HELENDO_ADDONS_DIR . '/inc/widgets/instagram.php';
require_once HELENDO_ADDONS_DIR . '/inc/widgets/nav-menu.php';

/**
 * Register widgets
 *
 * @since  1.0
 *
 * @return void
 */
function helendo_register_widgets() {
	if ( class_exists( 'WC_Widget' ) ) {
		require_once HELENDO_ADDONS_DIR . '/inc/widgets/product-cat.php';
		require_once HELENDO_ADDONS_DIR . '/inc/widgets/filter-price-list.php';
		require_once HELENDO_ADDONS_DIR . '/inc/widgets/woo-attributes-filter.php';
		require_once HELENDO_ADDONS_DIR . '/inc/widgets/widget-layered-nav-filters.php';
		require_once HELENDO_ADDONS_DIR . '/inc/widgets/product-tag.php';
		require_once HELENDO_ADDONS_DIR . '/inc/widgets/product-sort-by.php';

		register_widget( 'Helendo_Widget_Product_Cat' );
		register_widget( 'Helendo_Price_Filter_List_Widget' );
		register_widget( 'Helendo_Widget_Attributes_Filter' );
		register_widget( 'Helendo_Widget_Layered_Nav_Filters' );
		register_widget( 'Helendo_Widget_Product_Tag_Cloud' );
		register_widget( 'Helendo_Product_SortBy_Widget' );
	}

	register_widget( 'Helendo_Social_Links_Widget' );
	register_widget( 'Helendo_Instagram_Widget' );
	register_widget( 'Helendo_Nav_Menu_Widget' );
}
add_action( 'widgets_init', 'helendo_register_widgets', 100 );
