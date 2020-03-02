<?php
/**
* Custom functions for the maintenance mode.
*
* @package Konte
*/


/**
 * Redirect to the target page if the maintenance mode is enabled.
 */
function helendo_maintenance_redirect() {
	if ( ! helendo_get_option( 'maintenance_enable' ) ) {
		return;
	}

	if ( current_user_can( 'super admin' ) ) {
		return;
	}

	$mode     = helendo_get_option( 'maintenance_mode' );
	$page_id  = helendo_get_option( 'maintenance_page' );
	$code     = 'maintenance' == $mode ? 503 : 200;
	$page_url = $page_id ? get_page_link( $page_id ):  '';

	// Use default message.
	if ( ! $page_id || ! $page_url ) {
		if ( 'coming_soon' == $mode ) {
			$message = sprintf( '<h1>%s</h1><p>%s</p>', esc_html__( 'Coming Soon', 'helendo' ), esc_html__( 'Our website is under construction. We will be here soon with our new awesome site.', 'helendo' ) );
		} else {
			$message = sprintf( '<h1>%s</h1><p>%s</p>', esc_html__( 'Website Under Maintenance', 'helendo' ), esc_html__( 'Our website is currently undergoing scheduled maintenance. Please check back soon.', 'helendo' ) );
		}

		wp_die( $message, get_bloginfo( 'name' ), array( 'response' => $code ) );
	}

	// Add body classes.
	add_filter( 'body_class', 'helendo_maintenance_page_body_class' );

	// Redirect to the correct page.
	if ( ! is_page( $page_id ) ) {
		wp_redirect( $page_url );
		exit;
	} else {
		if ( ! headers_sent() ) {
			status_header( $code );
		}

		remove_action( 'helendo_header', 'helendo_header' );
		remove_action( 'helendo_before_content_wrapper', 'helendo_single_page_header' );

		if ( ! is_page_template() ) {
			add_filter( 'helendo_inline_style', 'helendo_maintenance_page_background' );
			add_action( 'helendo_before_header', 'helendo_maintenance_page_header', 1 );
		}
	}
}

add_action( 'template_redirect', 'helendo_maintenance_redirect', 1 );

/**
 * Add classes for maintenance mode.
 *
 * @param array $classes
 * @return array
 */
function helendo_maintenance_page_body_class( $classes ) {
	if ( ! helendo_get_option( 'maintenance_enable' ) ) {
		return $classes;
	}

	if ( current_user_can( 'super admin' ) ) {
		return $classes;
	}

	$classes[] = 'maintenance-mode';

	if ( helendo_is_maintenance_page() ) {
		$classes[] = 'maintenance-page';
		$classes[] = 'maintenance-layout-fullscreen';
	}

	return $classes;
}

/**
 * Set the background image for the maintenance page layout Fullscreen.
 *
 * @param string $css
 * @return string
 */
function helendo_maintenance_page_background( $css ) {
	if ( has_post_thumbnail() ) {
		$css .= '.maintenance-page {background-image: url( ' . esc_url( get_the_post_thumbnail_url( null, 'full' ) ) . ' )}';
	}

	return $css;
}

/**
 * Konte
 *
 * @return void
 */
function helendo_maintenance_page_header() {
	?>

	<div class="site-header maintenance-header transparent text-<?php echo esc_attr( helendo_get_option( 'maintenance_textcolor' ) ) ?>">
		<div class="container">
			<div class="header-items">
				<?php get_template_part( 'template-parts/header/logo' ); ?>
			</div>
		</div>
	</div>

	<?php
}