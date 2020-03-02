<?php
/**
 * Template Name: Home Page Full Width
 *
 * The template file for home page full width page.
 *
 * @package Helendo
 */

get_header(); ?>
<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		the_content();
	endwhile;
endif;
?>
<?php get_footer(); ?>
