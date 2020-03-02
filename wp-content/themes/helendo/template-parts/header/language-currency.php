<?php
/**
 * Template part for displaying the language
 *
 * @package Helendo
 */

$el = helendo_get_option( 'header_language_currency' );

if ( empty( $el ) ) {
	return;
}

?>

<div class="header-language-currency helendo-language-currency">
	<?php if ( in_array( 'language', $el ) ) : ?>

	<div class="widget-language language list-dropdown">
		<?php echo helendo_language_switcher(); ?>
	</div>

	<?php endif; ?>

	<?php if ( in_array( 'currency', $el ) ) : ?>

	<div class="widget-currency currency widget-lan-cur list-dropdown">
		<?php echo helendo_currency_switcher(); ?>
	</div>
	<?php endif; ?>
</div>

