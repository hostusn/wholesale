<?php
/**
 * Template part for displaying phone number
 *
 * @package Henlendo
 */

?>

<div class="header-phone">
	<span class="phone-number">
		<?php echo apply_filters( 'helendo_header_phone_icon', '<i class="icon-telephone"></i>' ); ?>
		<?php echo helendo_get_option( 'header_phone' ); ?>
	</span>
</div>
