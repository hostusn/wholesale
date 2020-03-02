<?php
/**
 * Custom functions for post
 *
 * @package Helendo
 */

if ( ! function_exists( 'helendo_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function helendo_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
		/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'helendo' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'helendo_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function helendo_posted_by() {
		$byline = sprintf(
		/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'helendo' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'helendo_post_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function helendo_post_entry_meta() {
		$blog_view = helendo_get_option( 'blog_view' );
		$metas     = helendo_get_option( 'post_entry_meta' );
		if ( helendo_is_blog() ) {
			$metas = helendo_get_option( 'blog_entry_meta' );
		}

		if ( empty( $metas ) ) {
			return;
		}

		$meta = array();

		if ( in_array( 'date', $metas ) ) {
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

			$time_string = sprintf(
				$time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() )
			);

			$meta[] = sprintf( '<div class="meta date">%s</div>', $time_string );
		}

		if ( in_array( 'author', $metas ) ) {
			$meta[] = sprintf(
				'<div class="meta author">' .
				'<span>%s </span>' .
				'<a class="url fn n" href="%s">%s</a></div>',
				esc_html__( 'by', 'helendo' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			);
		}

		if ( in_array( 'cat', $metas ) ) {
			$category = get_the_terms( get_the_ID(), 'category' );

			$cat_html = '';

			if ( ! is_wp_error( $category ) && $category ) {
				$cat_html = sprintf(
					'<a href="%s" class="cat-links">%s</a>',
					esc_url( get_term_link( $category[0], 'category' ) ),
					esc_html( $category[0]->name )
				);
			}

			if ( $cat_html ) {
				$meta[] = sprintf( '<div class="meta cat"><span>%s </span>%s</div>', esc_html__( 'in', 'helendo' ), $cat_html );
			}
		}
		$cmt = '';
		if ( in_array( 'cmt', $metas ) ) {

			$comment_count = get_comments_number();
			if ( $blog_view == 'grid' ) {
				$cmt = sprintf( '<div class="count-cmt-blog"><i class="icon_chat"></i> %s</div>', $comment_count );
			} else {
				$comment_text = $comment_count == 1 ? esc_html__( 'comment', 'helendo' ) : esc_html__( 'comments', 'helendo' );
				$meta[]       = sprintf( '<div class="meta cmt">%s %s</div>', $comment_count, $comment_text );
			}
		}


		echo '<div class="entry-meta"><div class="list-meta" >' . implode( '', $meta ) . '</div> ' . $cmt . '</div> ';
	}
endif;

if ( ! function_exists( 'helendo_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function helendo_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'helendo' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'helendo' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'helendo' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'helendo' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
					/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'helendo' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'helendo' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'helendo_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function helendo_post_thumbnail( $size = 'thumbnail' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_single() ) :
			?>

            <div class="post-thumbnail">
				<?php the_post_thumbnail( $size ); ?>
            </div>

		<?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail( $size );
				?>
            </a>

		<?php
		endif;
	}
endif;

if ( ! function_exists( 'helendo_content_limit' ) ) :
	/**
	 * Prints content limit.
	 */
	function helendo_content_limit( $num_words, $more = "&hellip;" ) {
		$content = get_the_excerpt();

		// Remove inline styles / scripts
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		// Truncate $content to $max_char
		$content = wp_trim_words( $content, $num_words );

		if ( $more ) {
			echo sprintf(
				'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
				$content,
				get_permalink(),
				sprintf( esc_attr__( 'Continue reading &quot;%s&quot;', 'helendo' ), the_title_attribute( 'echo=0' ) ),
				esc_html( $more )
			);
		} else {
			echo sprintf( '<div class="entry-content"><p>%s</p></div>', $content );
		}
	}
endif;

if ( ! function_exists( 'helendo_post_entry_content' ) ) :

	function helendo_post_entry_content() {

		$excerpt_length = intval( helendo_get_option( 'excerpt_length' ) );

		helendo_content_limit( $excerpt_length, '' );

	}
endif;

if ( ! function_exists( 'helendo_post_entry_footer' ) ) :
	function helendo_post_entry_footer() {
		global $post;
		$permalink = esc_url( get_permalink( $post->ID ) );

		$read_more = wp_kses( helendo_get_option( 'blog_read_more' ), wp_kses_allowed_html( 'post' ) );

		printf( '<a href="%s" class="btn-blog"> %s </a>', $permalink, $read_more );
	}
endif;

if ( ! function_exists( 'helendo_single_post_entry_footer' ) ) :
	function helendo_single_post_entry_footer() {
		$socials = helendo_get_option( 'post_socials_share' );

		if ( ! is_singular( 'post' ) || ( empty( $socials ) && has_tag() == false ) || ! intval( helendo_get_option( 'show_post_social_share' ) ) && has_tag() == false ) {
			return;
		}

		echo '<div class="entry-footer">';
		if ( function_exists( 'helendo_addons_share_link_socials' ) && intval( helendo_get_option( 'show_post_social_share' ) ) && ( ! empty( $socials ) ) ) {
			echo '<div class="helendo-single-post-socials-share">';
			echo esc_html__( 'Share this story on :', 'helendo' );
			echo helendo_addons_share_link_socials( $socials, get_the_title(), get_the_permalink(), get_the_post_thumbnail() );
			echo '</div>';
		};

		if ( has_tag() ) :
			the_tags( '<div class="tag-list"><span class="tag-title">' . esc_html__( 'Tags: ', 'helendo' ) . '</span>', ', ', '</div>' );
		endif;
		echo '</div>';
	}
endif;

/**
 * Get author box
 *
 * @since  1.0
 *
 */
if ( ! function_exists( 'helendo_author_box' ) ) :
	function helendo_author_box() {
		if ( ! intval( helendo_get_option( 'show_author_box' ) ) ) {
			return;
		}

		if ( ! get_the_author_meta( 'description' ) ) {
			return;
		}

		$socials = array(
			'facebook'   => esc_html__( 'Facebook', 'helendo' ),
			'twitter'    => esc_html__( 'Twitter', 'helendo' ),
			'googleplus' => esc_html__( 'Google Plus', 'helendo' ),
			'pinterest'  => esc_html__( 'Pinterest', 'helendo' ),
			'rss'        => esc_html__( 'Rss', 'helendo' ),
		);

		$links = array();
		foreach ( $socials as $social => $name ) {
			$link = get_the_author_meta( $social, get_the_author_meta( 'ID' ) );
			if ( empty( $link ) ) {
				continue;
			}
			$links[] = sprintf(
				'<li><a href="%s" target="_blank">%s</a></li>',
				esc_url( $link ),
				esc_html( $name )
			);
		}

		?>
        <div class="post-author">
            <div class="row">
                <div class="col-xs-12">
                    <div class="post-author-box clearfix">
                        <div class="post-author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>
                        </div>
                        <div class="post-author-info">
                            <h3 class="author-name"><?php the_author_meta( 'display_name' ); ?></h3>

                            <p><?php the_author_meta( 'description' ); ?></p>
							<?php
							if ( ! empty( $links ) ) {
								echo sprintf( '<ul class="author-socials">%s</ul>', implode( '', $links ) );
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
endif;

/**
 * Check is blog
 *
 * @since  1.0
 */

if ( ! function_exists( 'helendo_is_blog' ) ) :
	function helendo_is_blog() {


		if ( ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && 'post' == get_post_type() ) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check is catalog
 *
 * @return bool
 */
if ( ! function_exists( 'helendo_is_catalog' ) ) :
	function helendo_is_catalog() {

		if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check homepage
 *
 * @since  1.0
 *
 */

if ( ! function_exists( 'helendo_is_homepage' ) ) :
	function helendo_is_homepage() {
		if (
			is_page_template( 'template-home-page.php' ) ||
			is_page_template( 'template-home-left-sidebar.php' ) ||
			is_page_template( 'template-home-boxed.php' ) ||
			is_page_template( 'homepage-fullwidth.php' )
		) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check page template
 *
 * @since  1.0
 *
 */

if ( ! function_exists( 'helendo_is_page_template' ) ) :
	function helendo_is_page_template() {
		if ( helendo_is_homepage() || is_page_template( 'template-fullwidth.php' ) ) {
			return true;
		}

		return false;
	}

endif;

if ( ! function_exists( 'helendo_get_the_post_navigation' ) ) :

	/**
	 * @param array $args
	 *
	 * @return string*
	 */

	function helendo_get_the_post_navigation( $args = array() ) {
		$left = sprintf(
			'<i class="icon-chevron-left"></i><span class="blog-nav nav-previous">%s<span class="title-nav">%s</span></span>',
			'%title',
			esc_html__( 'Previous', 'helendo' )

		);

		$right = sprintf(
			'<span class="blog-nav nav-next">%s<span class="title-nav">%s</span></span><i class="icon-chevron-right"></i>',
			'%title',
			esc_html__( 'Next', 'helendo' )
		);

		$args = wp_parse_args(
			$args, array(
				'prev_text'          => $left,
				'next_text'          => $right,
				'in_same_term'       => false,
				'excluded_terms'     => '',
				'taxonomy'           => 'category',
				'screen_reader_text' => esc_attr__( 'Post navigation', 'helendo' ),
			)
		);

		$navigation = '';

		$previous = get_previous_post_link(
			'%link',
			$args['prev_text'],
			$args['in_same_term'],
			$args['excluded_terms'],
			$args['taxonomy']
		);

		$next = get_next_post_link(
			'%link',
			$args['next_text'],
			$args['in_same_term'],
			$args['excluded_terms'],
			$args['taxonomy']
		);

		// Only add markup if there's somewhere to navigate to.
		if ( $previous || $next ) {
			$navigation = _navigation_markup( $previous . $next, 'post-navigation', $args['screen_reader_text'] );
		}

		return $navigation;
	}

endif;

/**
 *
 * post navigation
 *
 */
if ( ! function_exists( 'helendo_the_post_navigation' ) ) :
	function helendo_the_post_navigation( $args = array() ) {
		echo helendo_get_the_post_navigation( $args );
	}

endif;


/**
 * Get Revolution Sliders
 */
if ( ! function_exists( 'helendo_get_rev_sliders' ) ) :
	function helendo_get_rev_sliders() {

		if ( ! class_exists( 'RevSlider' ) ) {
			return;
		}

		$slider     = new RevSlider();
		$arrSliders = $slider->getArrSliders();

		$revsliders = array();

		if ( $arrSliders ) {
			$revsliders[0] = esc_html__( 'Choose a slider', 'helendo' );
			foreach ( $arrSliders as $slider ) {
				$revsliders[ $slider->getAlias() ] = $slider->getTitle();
			}
		} else {
			$revsliders[0] = esc_html__( 'No sliders found', 'helendo' );
		}

		return $revsliders;
	}

endif;


/**
 * show taxonomy filter
 *
 * @return string
 */

if ( ! function_exists( 'helendo_get_taxs_list' ) ) :
	function helendo_get_taxs_list( $taxonomy = 'category' ) {

		$term_id   = 0;
		$cats      = $output = '';
		$found     = false;
		$number    = 3;
		$cats_slug = '';
		$classes   = array( 'helendo-taxs-list' );

		if ( helendo_is_catalog() ) {
			$number    = intval( helendo_get_option( 'shop_toolbar_categories_numbers' ) );
			$cats_slug = wp_kses_post( helendo_get_option( 'shop_toolbar_categories' ) );
			$classes[] = 'helendo-products-cat';
		}

		if ( is_tax( $taxonomy ) || is_category() ) {

			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$term_id = $queried_object->term_id;
			}
		}

		if ( $cats_slug ) {
			$cats_slug = explode( ',', $cats_slug );

			foreach ( $cats_slug as $slug ) {
				$cat = get_term_by( 'slug', $slug, $taxonomy );

				if ( $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'selected';
						$found        = true;
					}

					$cats .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_url( get_term_link( $cat ) ), esc_attr( $cat_selected ), esc_html( $cat->name ) );
				}
			}

		} else {
			$args = array(
				'number'  => $number,
				'orderby' => 'count',
				'order'   => 'DESC',

			);

			$categories = get_terms( $taxonomy, $args );
			if ( ! is_wp_error( $categories ) && $categories ) {
				foreach ( $categories as $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'selected';
						$found        = true;
					}

					$cats .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_url( get_term_link( $cat ) ), esc_attr( $cat_selected ), esc_html( $cat->name ) );
				}
			}
		}

		$cat_selected = $found ? '' : 'selected';

		$text = apply_filters( 'helendo_tax_list_text', esc_html__( 'All', 'helendo' ) );

		if ( $cats ) {
			$url = get_page_link( get_option( 'page_for_posts' ) );
			if ( 'posts' == get_option( 'show_on_front' ) ) {
				$url = home_url( '/' );
			} elseif ( helendo_is_catalog() ) {
				$url = get_permalink( wc_get_page_id( 'shop' ) );
			}

			$output = sprintf(
				'<ul>
					<li><a href="%s" class="%s">%s</a></li>
					 %s
				</ul>',
				esc_url( $url ),
				esc_attr( $cat_selected ),
				$text,
				$cats
			);
		}

		if ( $output ) {
			$output = apply_filters( 'helendo_tax_html', $output );

			printf( '<div class="%s">%s</div>', esc_attr( implode( ' ', $classes ) ), $output );
		}
	}

endif;

/**
 * Get current page URL for layered nav items.
 * @return string
 */
if ( ! function_exists( 'helendo_get_page_base_url' ) ) :
	function helendo_get_page_base_url() {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url( '/' );
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$queried_object = get_queried_object();
			$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
		}

		return $link;
	}
endif;

/**
 * Conditional function to check if current page is the maintenance page.
 *
 * @return bool
 */
function helendo_is_maintenance_page() {
	if ( ! helendo_get_option( 'maintenance_enable' ) ) {
		return false;
	}

	if ( current_user_can( 'super admin' ) ) {
		return false;
	}

	$page_id = helendo_get_option( 'maintenance_page' );

	if ( ! $page_id ) {
		return false;
	}

	return is_page( $page_id );
}
