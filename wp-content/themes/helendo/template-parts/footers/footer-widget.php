<?php
/**
 * Template part for displaying footer widgets
 *
 * @package Helendo
 */

if (
	is_active_sidebar( 'footer-1' ) == false &&
	is_active_sidebar( 'footer-2' ) == false &&
	is_active_sidebar( 'footer-3' ) == false &&
	is_active_sidebar( 'footer-4' ) == false
) {
	return;
}

$columns = max( 1, absint( helendo_get_option( 'footer_widgets_columns' ) ) );

$container = apply_filters( 'helendo_footer_container_class', helendo_get_option( 'footer_container' ), 'widgets' );

?>
<div class="footer-widget columns-<?php echo esc_attr( $columns ) ?>">
	<div class="<?php echo esc_attr( $container ); ?>">
		<div class="footer-widget-wrapper">
			<?php for ( $i = 1; $i <= $columns; $i ++ ) : ?>
				<div class="footer-sidebar footer-<?php echo esc_attr( $i ) ?>">
					<?php
					ob_start();
					dynamic_sidebar( "footer-$i" );
					$output = ob_get_clean();
					echo apply_filters('helendo_footer_widget_content',$output,$i);
					?>
				</div>
			<?php endfor; ?>

		</div>
	</div>
</div>
