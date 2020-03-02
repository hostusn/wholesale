<?php
/**
 * The template for displaying archive pages
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Helendo
 */

get_header();

?>

	<div id="primary" class="content-area <?php helendo_content_columns(); ?>">
		<main id="main" class="site-main">
			<div class="helendo-post-list row-flex">

				<?php if ( have_posts() ) : ?>

					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

					endwhile;

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div>

			<?php helendo_numeric_pagination(); ?>
		</main>
		<!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
