<?php
/**
 * The sidebar containing the main widget area
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Helendo
 */

if ( helendo_get_layout() == 'full-content' ) {
	return;
}
$sidebar = 'blog-sidebar';

if ( helendo_is_catalog() ) {
	$sidebar = 'catalog-sidebar';
}

if ( is_singular( 'product' ) ) {
	$sidebar = 'product-sidebar';
}

?>

<aside id="primary-sidebar" class="widget-area primary-sidebar col-md-3 col-sm-12 col-xs-12 <?php echo esc_attr( $sidebar ); ?>">
	<?php
	if ( is_active_sidebar( $sidebar ) ) {
		dynamic_sidebar( $sidebar );
	} else {
		if ( is_singular( 'product' ) ) {
			the_widget(
				'WC_Widget_Product_Search',
				array(
					'title' => esc_html__( 'Search', 'helendo' )
				),
				array(
					'before_widget' => '<div class="widget %s">',
					'after_widget'  => '</div>',
					'before_title'  => '<div class="widget-title">',
					'after_title'   => '</div>',
					'widget_id'     => 'WC_Widget_Product_Search_1'
				)
			);

			the_widget(
				'WC_Widget_Products',
				array(
					'title'  => esc_html__( 'Best Sellers', 'helendo' ),
					'number' => 3,
					'show'   => 'featured'
				),
				array(
					'before_widget' => '<div class="widget %s">',
					'after_widget'  => '</div>',
					'before_title'  => '<div class="widget-title">',
					'after_title'   => '</div>',
					'widget_id'     => 'WC_Widget_Products_1'
				)
			);

			the_widget(
				'WC_Widget_Product_Categories',
				array(
					'title' => esc_html__( 'Categories', 'helendo' )
				),
				array(
					'before_widget' => '<div class="widget %s">',
					'after_widget'  => '</div>',
					'before_title'  => '<div class="widget-title">',
					'after_title'   => '</div>',
					'widget_id'     => 'WC_Widget_Product_Categories_1'
				)
			);
		}
	}
	?>
</aside><!-- #secondary -->
