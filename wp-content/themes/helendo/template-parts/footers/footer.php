<?php
/**
 * Template part for displaying footer main
 *
 * @package Helendo
 */

$sections = array(
	'left'   => helendo_get_option( 'footer_main_left' ),
	'center' => helendo_get_option( 'footer_main_center' ),
	'right'  => helendo_get_option( 'footer_main_right' ),
);

$sections_bottom = array(
	'left'   => helendo_get_option( 'footer_bottom_left' ),
	'center' => helendo_get_option( 'footer_bottom_center' ),
	'right'  => helendo_get_option( 'footer_bottom_right' ),
);

$sections = apply_filters( 'helendo_footer_main_sections', $sections );

$sections_bottom = apply_filters( 'helendo_footer_bottom_sections', $sections_bottom );

$sections        = array_filter( $sections );
$sections_bottom = array_filter( $sections_bottom );

if ( empty( $sections ) && empty( $sections_bottom ) ) {
	return;
}

$container = apply_filters( 'helendo_footer_container_class', helendo_get_option( 'footer_container' ), 'main' );

$border          = helendo_get_option( 'footer_main_border' );
$container_class = ( $container == 'container' ) ? 'container' : '';
?>
<div class="footer-main site-info">
	<?php if ( $border ) : ?>
        <div class="footer-border <?php echo esc_attr( $container_class ); ?>">
            <hr class="divider">
        </div>
	<?php endif; ?>

    <div class="<?php echo esc_attr( $container ); ?>">
		<?php if ( ! empty( $sections ) ) : ?>
            <div class="footer-top footer-container">
				<?php foreach ( $sections as $section => $items ) : ?>

                    <div class="footer-items footer-<?php echo esc_attr($section); ?>">
						<?php
						foreach ( $items as $item ) {
							switch ( $item['item'] ) {
								case 'copyright':
									echo '<div class="copyright">' . wp_kses_post( helendo_get_option( 'footer_copyright' ) ) . '</div>';
									break;

								case 'logo':
									$logo_type = helendo_get_option( 'footer_main_logo_type' );

									if ( 'svg' == $logo_type ) :
										$logo      = helendo_get_option( 'footer_main_logo_svg' );
										$logo_html = sprintf( '<span class="logo-svg">%s</span>', wp_kses_post( $logo ) );
									else:
										$logo = helendo_get_option( 'footer_main_logo' );

										$logo_html = $logo ? sprintf( '<img src="%s" alt="%s">', esc_url( $logo ), esc_attr( get_bloginfo( 'name' ) ) ) : '';

									endif;

									printf( '<div class="footer-logo"><a href="%s">%s</a></div>', esc_url( home_url( '/' ) ), $logo_html );
									break;

								case 'menu':
									$footer_menu = ( array ) helendo_get_option( 'footer_menu' );
									if ( ! empty( $footer_menu ) ) {
										echo '<nav class="menu-footer-menu-container">';
										echo '<ul class="footer-menu menu">';
										foreach( $footer_menu as $items ) {

										    if( empty( $items ) ) {
										        continue;
                                            }

											echo sprintf(
												'<li><a href="%s">%s</a></li>',
												esc_url( $items['link_url'] ),
												$items['link_text']
											);
										}
										echo '</ul>';
										echo '</nav>';
									}
									break;

								case 'social':
									$footer_socials = ( array ) helendo_get_option( 'footer_socials' );
									if ( ! empty( $footer_socials ) ) {
										echo '<div class="footer-socials-menu">';
										echo apply_filters( 'helendo_label_before_footer_socials_menu', sprintf( '<label>%s</label>', esc_html__( 'Follow Us On Social', 'helendo' ) ) );
										echo '<div class="menu-footer-menu-container">';
										echo '<ul class="footer-socials menu">';
										foreach( $footer_socials as $items ) {
											if( empty( $items ) ) {
												continue;
											}

											echo sprintf(
												'<li><a href="%s"></a></li>',
												esc_url( $items['link_url'] )
											);
										}
										echo '</ul>';
										echo '</div>';
										echo '</div>';
									}
									break;

								case 'text':
									if ( $footer_custom_text = helendo_get_option( 'footer_main_text' ) ) {
										echo '<div class="custom-text">' . wp_kses_post( $footer_custom_text ) . '</div>';
									}
									break;

								default:
									do_action( 'helendo_footer_main_item', $item['item'] );
									break;
							}
						}
						?>
                    </div>

				<?php endforeach; ?>
            </div>
		<?php endif ?>

		<?php if ( ! empty( $sections_bottom ) ) : ?>
            <div class="footer-bottom footer-container">
				<?php foreach ( $sections_bottom as $section => $items ) : ?>

                    <div class="footer-items footer-<?php echo esc_attr($section); ?>">
						<?php
						foreach ( $items as $item ) {
							switch ( $item['item'] ) {
								case 'copyright':
									echo '<div class="copyright">' . wp_kses_post( helendo_get_option( 'footer_bottom_copyright' ) ) . '</div>';
									break;

								case 'logo':
									$logo_type = helendo_get_option( 'footer_bottom_logo_type' );

									if ( 'svg' == $logo_type ) :
										$logo      = helendo_get_option( 'footer_bottom_logo_svg' );
										$logo_html = sprintf( '<span class="logo-svg">%s</span>', wp_kses_post( $logo ) );
									else:
										$logo = helendo_get_option( 'footer_bottom_logo' );

										if ( ! $logo ) {
											$logo = $logo ? $logo : get_theme_file_uri( '/images/logo.png' );
										}

										$logo_html = $logo ? sprintf( '<img src="%s" alt="%s">', esc_url( $logo ), esc_attr( get_bloginfo( 'name' ) ) ) : '';

									endif;

									printf( '<div class="footer-logo"><a href="%s">%s</a></div>', esc_url( home_url( '/' ) ), $logo_html );
									break;

								case 'menu':
									$footer_menu = ( array ) helendo_get_option( 'footer_menu' );
									if ( ! empty( $footer_menu ) ) {
										echo '<nav class="menu-footer-menu-container">';
										echo '<ul class="footer-menu menu">';
										foreach( $footer_menu as $items ) {

											if( empty( $items ) ) {
												continue;
											}

											echo sprintf(
												'<li><a href="%s">%s</a></li>',
												esc_url( $items['link_url'] ),
												$items['link_text']
											);
										}
										echo '</ul>';
										echo '</nav>';
									}
									break;

								case 'social':
									$footer_socials = ( array ) helendo_get_option( 'footer_socials' );
									if ( ! empty( $footer_socials ) ) {
										echo '<div class="footer-socials-menu">';
										echo apply_filters( 'helendo_label_before_footer_socials_menu', sprintf( '<label>%s</label>', esc_html__( 'Follow Us On Social', 'helendo' ) ) );
										echo '<div class="menu-footer-menu-container">';
										echo '<ul class="footer-socials menu">';
										foreach( $footer_socials as $items ) {
											if( empty( $items ) ) {
												continue;
											}

											echo sprintf(
												'<li><a href="%s"></a></li>',
												esc_url( $items['link_url'] )
											);
										}
										echo '</ul>';
										echo '</div>';
										echo '</div>';
									}
									break;

								case 'text':
									if ( $footer_custom_text = helendo_get_option( 'footer_bottom_text' ) ) {
										echo '<div class="custom-text">' . wp_kses_post( $footer_custom_text ) . '</div>';
									}
									break;

								default:
									do_action( 'helendo_footer_bottom_item', $item['item'] );
									break;
							}
						}
						?>
                    </div>

				<?php endforeach; ?>
            </div>
		<?php endif ?>

    </div>
</div>
