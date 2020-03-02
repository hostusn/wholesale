<?php
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Helendo
 */

$css_blog    = 'single-blog';
$col         = helendo_get_option( 'single_post_col' );
$offset      = helendo_get_option( 'single_post_col_offset' );
$setoff      = $offset != 0 ? 'col-md-offset-' . $offset : '';
$post_metas  = helendo_get_option( 'post_entry_meta' );
$title_class = 'hidden';
if ( ! empty( $post_metas ) && in_array( 'title', $post_metas ) ) {
	$title_class = '';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $css_blog ); ?>>
    <header class="entry-header">
		<?php helendo_post_thumbnail( 'helendo-single-post-thumb' ); ?>
	    <?php the_title( '<h1 class="entry-title ' . esc_attr( $title_class ) . '">', '</h1>' ) ?>
        <div class="row">
            <div class="col-md-<?php echo esc_attr( $col ) ?> <?php echo esc_attr( $setoff ) ?> col-xs-12 col-sm-12">
				<?php helendo_post_entry_meta(); ?>
            </div>
        </div>
    </header>
    <!-- .entry-header -->

    <div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'helendo' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'helendo' ),
				'after'  => '</div>',
			)
		);
		?>
    </div>
    <!-- .entry-content -->
	<?php helendo_single_post_entry_footer(); ?>
</article><!-- #post-<?php the_ID(); ?> -->
