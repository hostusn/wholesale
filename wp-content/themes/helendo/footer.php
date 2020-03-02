<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Helendo
 */

$footer_css = array(
	'site-footer',
	'footer-layout-' . helendo_get_option( 'footer_layout' ),
	intval( helendo_get_option( 'footer_border_top' ) ) ? 'has-border' : ''
);

?>

<?php do_action( 'helendo_before_site_content_close' ); ?>
</div><!-- #content -->
<?php do_action( 'helendo_before_footer' ) ?>
<footer id="site-footer" class="<?php echo esc_attr( implode( ' ', $footer_css ) ); ?>">
	<?php do_action( 'helendo_footer' ) ?>
</footer><!-- #colophon -->
<?php do_action( 'helendo_after_footer' ) ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
