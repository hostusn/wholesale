<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Helendo
 */

get_header();
$col = helendo_get_option('single_post_col') ;
$offset = helendo_get_option('single_post_col_offset');
$setoff = $offset != 0 ? 'col-md-offset-'.$offset : '';
?>

	<div id="primary" class="content-area <?php helendo_content_columns(); ?>">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'single' );
			
			helendo_author_box();

			helendo_the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
            ?>
            <div class="row">
                <div class="col-md-<?php echo esc_attr($col) ?> <?php echo esc_attr($setoff) ?> col-xs-12 col-sm-12">
                    <?php
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                 </div>
            </div>

		<?php
		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
