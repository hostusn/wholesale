<?php
/**
 * Template part for displaying the search icon
 *
 * @package Henlendo
 */

$menu_class       = '';
$menu_sidebar_els = helendo_get_option( 'menu_sidebar_el' );
if ( $menu_sidebar_els == 'widget' ) {
	if ( ! is_active_sidebar( 'menu-sidebar' ) ) {
		$menu_class = 'hidden-lg';
	}
} else {
	if ( ! has_nav_menu( 'primary' ) ) {
		$menu_class = 'hidden-lg';
	}
}

$menu_sidebar_els = helendo_get_option( 'menu_sidebar_mobile_el' );
if ( $menu_sidebar_els == 'widget' ) {
	$sidebar = 'mobile-menu-sidebar';
	if ( ! is_active_sidebar( 'mobile-menu-sidebar' ) && ! is_active_sidebar( 'menu-sidebar' ) ) {
		$menu_class .= ' hidden-md hidden-sm hidden-xs';
	}
} else {
	if ( ! has_nav_menu( 'primary' ) ) {
		$menu_class .= ' hidden-md hidden-sm hidden-xs';
	}
}
if ( ! is_active_sidebar( 'mobile-menu-sidebar' ) ) {
	if ( ! is_active_sidebar( 'menu-sidebar' ) && ! has_nav_menu( 'primary' ) ) {
		$menu_class .= ' hidden-md hidden-sm hidden-xs';
	}
}

?>
<div class="header-hamburger hamburger-menu <?php echo esc_attr( $menu_class ); ?>" data-target="hamburger-fullscreen">
    <span class="menu-icon" data-target="menu-panel"><i class="icon-menu"></i></span>
</div>
