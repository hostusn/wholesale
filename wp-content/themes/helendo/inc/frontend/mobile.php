<?php
/**
 * Custom template functions that act on mobile
 *
 * @package Helendo
 */


/**
 * Mobile header.
 */
function helendo_mobile_header() {
	$classes = array(
		'header-mobile hidden-lg',
		'logo-' . helendo_get_option( 'mobile_custom_logo' ) ? 'custom' : 'default',
		'logo-' . helendo_get_option( 'mobile_logo_position' ),
	);
	$classes = apply_filters( 'helendo_mobile_header_class', $classes );
	?>

	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<div class="helendo-header-container container">
			<?php get_template_part( 'template-parts/mobile-header' ); ?>
		</div>
	</div>

	<?php
}

add_action( 'helendo_header', 'helendo_mobile_header', 99 );

function mobile_catalog_sorting_popup() {
	$els = (array) helendo_get_option( 'shop_toolbar_mobile' );

	if ( empty( $els ) ) {
		return;
	}

	if ( ! in_array( 'sort-by', $els ) ) {
		return;
	}

	echo '<div class="helendo-catalog-sorting-mobile" id="helendo-catalog-sorting-mobile">';

	woocommerce_catalog_ordering();

	echo '</div>';
}
add_action( 'wp_footer', 'mobile_catalog_sorting_popup' );