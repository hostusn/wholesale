<?php
/**
 * Add more data for user
 *
 * @package Baroque
 */

/**
 * Add more contact method for user
 *
 * @param array $methods
 *
 * @return array
 */
function helendo_user_contact_methods( $methods ) {
	$methods['facebook']   = esc_html__( 'Facebook', 'helendo' );
	$methods['twitter']    = esc_html__( 'Twitter', 'helendo' );
	$methods['googleplus'] = esc_html__( 'Google Plus', 'helendo' );
	$methods['pinterest']  = esc_html__( 'Pinterest', 'helendo' );
	$methods['rss']        = esc_html__( 'Rss', 'helendo' );

	return $methods;
}

add_filter( 'user_contactmethods', 'helendo_user_contact_methods' );