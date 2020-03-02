<?php
/**
 * Template part for displaying the logo
 *
 * @package Helendo
 */

$logo_type        = helendo_get_option( 'logo_type' );
$logo_transparent = helendo_get_option( 'logo_transparent' );
$style            = $style_transparent = $class = '';

if ( 'svg' == $logo_type ) :
	$logo = helendo_get_option( 'logo_svg' );
else:
	$logo = helendo_get_option( 'logo' );

	if ( ! $logo ) {
		$logo = $logo ? $logo : get_theme_file_uri( '/images/logo.svg' );
	}

	$dimension = helendo_get_option( 'logo_dimension' );
	$style     = ! empty( $dimension['width'] ) ? ' width="' . esc_attr( $dimension['width'] ) . '"' : '';
	$style     .= ! empty( $dimension['width'] ) ? ' height="' . esc_attr( $dimension['height'] ) . '"' : '';

	$dimension_transparent = helendo_get_option( 'logo_transparent_dimension' );
	$style_transparent     = ! empty( $dimension_transparent['width'] ) ? ' width="' . esc_attr( $dimension_transparent['width'] ) . '"' : '';
	$style_transparent     .= ! empty( $dimension_transparent['width'] ) ? ' height="' . esc_attr( $dimension_transparent['height'] ) . '"' : '';
endif;

if ( ! empty( $logo_transparent ) ) {
	$class = 'active-logo';
}
?>
<div class="site-branding">
    <a href="<?php echo esc_url( home_url( '/' ) ) ?>" class="logo <?php echo esc_attr( $class ) ?>">
		<?php if ( 'svg' == $logo_type ) : ?>
            <span class="logo-svg"><?php echo wp_kses_post( $logo ); ?></span>
		<?php else : ?>
            <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>"
                 class="logo-dark logo-main" <?php echo wp_kses_post( $style ) ?>>
			<?php if ( ! empty( $logo_transparent ) && intval( helendo_get_option( 'header_transparent' ) ) ): ?>
                <img src="<?php echo esc_url( $logo_transparent ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>"
                     class="logo-dark logo-transparent" <?php echo wp_kses_post( $style_transparent ) ?>>
			<?php endif; ?>
		<?php endif; ?>
    </a>

	<?php if ( is_front_page() && is_home() ) : ?>
        <h1 class="site-title">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
        </h1>
	<?php else : ?>
        <p class="site-title">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
        </p>
	<?php endif; ?>

	<?php if ( ( $description = get_bloginfo( 'description', 'display' ) ) || is_customize_preview() ) : ?>
        <p class="site-description"><?php echo wp_kses_post( $description ); /* WPCS: xss ok. */ ?></p>
	<?php endif; ?>
</div>
