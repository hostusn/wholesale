<?php
/**
 * Template part for displaying the cart icon
 *
 * @package Helendo
 */
if ( ! function_exists( 'WC' ) ) {
	return;
}

$action      = helendo_get_option( 'header_cart_action' );
$behaviour   = helendo_get_option( 'header_cart_behaviour' );
$m_behaviour = helendo_get_option( 'header_mobile_cart_behaviour' );
$attr        = '';
$m_attr      = '';

if ( $action == 'click' && $behaviour == 'panel' ) {
	$attr = 'data-target=cart-panel';
}

if ( $m_behaviour == 'panel' ) {
	$m_attr = 'data-mobil-target=cart-mobile-panel';
}

?>

<div class="header-cart <?php echo esc_attr( $action ); ?>-action <?php echo esc_attr( $m_behaviour ) ?>-mobile-action">
	<a href="<?php echo esc_url( wc_get_cart_url() ) ?>" class="cart-contents" <?php echo  esc_attr( $attr ); ?> <?php echo esc_attr( $m_attr ); ?>>
		<i class="icon-bag2"></i>
		<span class="counter cart-counter"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
	</a>
	<?php helendo_mini_cart(); ?>
</div>
