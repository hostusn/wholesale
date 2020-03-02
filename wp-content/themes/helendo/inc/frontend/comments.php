<?php
/**
 * Custom functions for hook comments
 *
 * @package Helendo
 */

/**
 * Custom fields comment form
 *
 * @since  1.0
 *
 * @return  array  $fields
 */
if ( ! function_exists( 'helendo_comment_form_fields' ) ) :
	function helendo_comment_form_fields() {
		global $commenter, $aria_req;

		$fields = array(
			'author' => '<p class="comment-form-author col-md-6 col-sm-12">' .
				'<input id ="author" placeholder="' . esc_attr__( 'Name', 'helendo' ) . '" name="author" type="text" required value="' . esc_attr( $commenter['comment_author'] ) .
				'" size    ="30"' . $aria_req . ' /></p>',

			'email'  => '<p class="comment-form-email col-md-6 col-sm-12">' .
				'<input id ="email" placeholder="' . esc_attr__( 'Email', 'helendo' ) . '" name="email" type="email" required value="' . esc_attr( $commenter['comment_author_email'] ) .
				'" size    ="30"' . $aria_req . ' /></p>',

			'url'    => '<p class="comment-form-url col-md-12 col-sm-12">' .
				'<input id ="url" placeholder="' . esc_attr__( 'Website', 'helendo' ) . '" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
				'" size    ="30" /></p>'
		);

		return $fields;
	}
endif;

add_filter( 'comment_form_default_fields', 'helendo_comment_form_fields' );