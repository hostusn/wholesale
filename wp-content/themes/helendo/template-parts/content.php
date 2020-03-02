<?php
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Helendo
 */
global $wp_query;
global $helendo_post;
global $helendo_s_post;

$current = $wp_query->current_post + 1;

$layout    = helendo_get_layout();
$blog_view = helendo_get_option( 'blog_view' );

$metas = helendo_get_option( 'blog_entry_meta' );

$count_text = intval( helendo_get_option( 'excerpt_length' ) );
$columns    = 12 / intval( helendo_get_option( 'blog_columns' ) );

$css_blog = 'blog-wapper';

$size = 'helendo-post-large';


if ( $count_text <= 0 ) {
	$css_blog .= ' no-content';
}

if ( $blog_view == 'classic' && $layout == 'full-content' ) {
	$size = 'helendo-post-full';
}

if ( $blog_view == 'list' ) {
	$size = 'helendo-post-list';

} elseif ( $blog_view == 'grid' ) {
	$size = 'helendo-post-grid';

	if ( $columns == 6 ) {
		$size = 'helendo-post-grid-v2';
	}

	$css_blog .= ' col-flex-md-' . $columns . ' col-flex-xs-6';

} elseif ( $blog_view == 'masonry' ) {
	if ( $current % 12 == 1 || $current % 12 == 6 || $current % 12 == 9 ) {
		$size = 'helendo-blog-masonry-1';
	} elseif ( $current % 12 == 3 || $current % 12 == 4 ) {
		$size = 'helendo-blog-masonry-3';
	} else {
		$size = 'helendo-blog-masonry-2';
	}

	$css_blog .= ' blog-masonry-wrapper col-md-4 col-xs-6';
}

if ( isset( $helendo_post['css'] ) ) {
	$css_blog .= $helendo_post['css'];
	$blog_view = 'grid';
	$size      = 'helendo-post-grid';
}
if ( isset( $helendo_post['size_fix'] ) ) {
	$size = $helendo_post['size_fix'];
}

if ( isset( $helendo_s_post['s_css'] ) ) {
	$css_blog .= $helendo_s_post['s_css'];
	$blog_view = 'grid';
}

if ( isset( $helendo_s_post['s_size'] ) ) {
	$size = $helendo_s_post['s_size'];
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $css_blog ); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() ): ?>
			<div class="entry-thumbnail">
				<?php
				helendo_post_thumbnail( $size );
				if ( $blog_view == 'grid' ) {
					helendo_post_entry_footer();
				}
				?>
			</div>
		<?php endif; ?>
		<?php
		if ( $blog_view == 'masonry' ) {
			helendo_post_entry_meta();
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
		} else {
			if ( helendo_is_blog() && $blog_view == 'grid' && helendo_get_option( 'blog_grid_style' ) == '2' || ( $helendo_post['style'] == 'blog-grid-style-2' ) ) {
				?>
				<div class="entry-meta">
					<?php
					if ( in_array( 'cat', $metas ) ) {
						$category = get_the_category();
						if ( ! empty( $category ) ) {
							echo '<span class="meta"><a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '" class="cat-links">' . esc_html( $category[0]->name ) . '</a></span>';
						}
					}

					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
					helendo_post_entry_meta();
					?>
				</div>
				<?php
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
				helendo_post_entry_meta();
			}
		}
		?>

	</header>
	<!-- .entry-header -->

	<?php helendo_post_entry_content(); ?><!--    entry content-->

	<footer class="entry-footer">
		<?php helendo_post_entry_footer(); ?>
	</footer>
	<!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
