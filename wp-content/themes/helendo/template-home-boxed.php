<?php
/**
 * Template Name: Home Boxed
 *
 * The template file for displaying home page in box.
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