<?php
add_action( 'wp_enqueue_scripts', 'helendo_child_enqueue_scripts', 20 );
function helendo_child_enqueue_scripts() {
	wp_enqueue_style( 'helendo-child-style', get_stylesheet_uri() );
	if ( is_rtl() ) {
		wp_enqueue_style( 'helendo-rtl', get_template_directory_uri() . '/rtl.css', array(), '20181121' );
	}
}