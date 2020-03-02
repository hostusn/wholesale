<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Helendo
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action('helendo_before_site'); ?>
<div id="page" class="site">
	<?php do_action('helendo_before_header'); ?>
    <header id="site-header" class="site-header <?php echo esc_attr( implode( ' ', helendo_header_extra_classes() ) ) ?>">
		<?php do_action('helendo_header'); ?>
    </header>
    <!-- #masthead -->
	<?php do_action('helendo_after_header'); ?>

    <div id="content" class="site-content">
		<?php do_action( 'helendo_after_site_content_open' ); ?>
