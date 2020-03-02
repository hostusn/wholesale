<?php
/**
 * Template part for displaying the sign-in
 *
 * @package Helendo
 */

if ( ! function_exists( 'WC' ) ) {
	return;
}
?>
<div class="header-account">
	<?php if ( is_user_logged_in() ) : ?>
		<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>">
			<i class="icon-user"></i>
		</a>

		<div class="account-links">
			<div class="submenu__arrow"></div>
			<ul>
				<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
					<li class="account-link--<?php echo esc_attr( $endpoint ); ?>">
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="underline-hover"><?php echo esc_html( $label ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php else : ?>
		<a id="header-account-icon" class="header-account-icon" href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" >
			<i class="icon-user"></i>
		</a>
	<?php endif; ?>
</div>