<?php
/**
 * Template file for displaying mobile header
 *
 * @package Helendo
 */
?>

<div class="mobile-header-icons mobile-header-icons--left">
	<?php helendo_mobile_header_left_icons(); ?>
</div>

<?php if ( helendo_get_option( 'mobile_custom_logo' ) && ( $logo = helendo_get_option( 'mobile_logo' ) ) ) : ?>
	<div class="mobile-logo site-branding">
		<a href="<?php echo esc_url( home_url( '/' ) ) ?>" class="logo">
			<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>">
		</a>
	</div>
<?php else : ?>
	<?php get_template_part( 'template-parts/header/logo' ); ?>
<?php endif; ?>

<div class="mobile-header-icons mobile-header-icons--right">
	<?php
	helendo_mobile_header_right_icons();
	get_template_part( 'template-parts/header/menu' );
	?>
</div>
