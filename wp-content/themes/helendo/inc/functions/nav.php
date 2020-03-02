<?php
function helendo_load_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}
	$view_more = wp_kses( helendo_get_option( 'view_more_text' ), wp_kses_allowed_html( 'post' ) );

	$next_text = '<span id="helendo-posts-loading" class="dots-loading"><span>.</span><span>.</span><span>.</span>' . $view_more . '<span>.</span><span>.</span><span>.</span></span>';

	?>
    <nav class="navigation paging-navigation">
        <div class="nav-links">
			<?php if ( get_next_posts_link() ) : ?>
                <div id="helendo-blog-previous-ajax" class="nav-previous-ajax">
					<?php next_posts_link( sprintf( '%s', $next_text ) ); ?>
                </div>
			<?php endif; ?>
        </div>
    </nav>
	<?php
}

/**
 * Display numeric pagination
 *
 * @since 1.0
 * @return void
 */
function helendo_numeric_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	?>
    <nav class="navigation numeric-navigation">
		<?php
		$big  = 999999999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => '<i class="icon-chevron-left"></i>' . esc_html__( 'Previous Page', 'helendo' ),
			'next_text' => esc_html__( 'Next Page', 'helendo' ) . '<i class="icon-chevron-right"></i>',
			'type'      => 'plain',
		);

		echo paginate_links( $args );
		?>
    </nav>
	<?php
}

?>