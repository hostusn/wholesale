<?php
/**
 * Plugin Name: Helendo Addons
 * Plugin URI: http://drfuri.com/plugins/helendo-addons.zip
 * Description: Extra elements for WPBakery Page Builder. It was built for Helendo theme.
 * Version: 1.0.1
 * Author: Grixbase
 * Author URI: http://drfuri.com/
 * License: GPL2+
 * Text Domain: helendo
 * Domain Path: /lang/
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'HELENDO_ADDONS_DIR' ) ) {
	define( 'HELENDO_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'HELENDO_ADDONS_URL' ) ) {
	define( 'HELENDO_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

require_once HELENDO_ADDONS_DIR . '/inc/visual-composer.php';
require_once HELENDO_ADDONS_DIR . '/inc/shortcodes.php';
require_once HELENDO_ADDONS_DIR . '/inc/socials.php';
require_once HELENDO_ADDONS_DIR . '/inc/user.php';
require_once HELENDO_ADDONS_DIR . '/inc/widgets/widgets.php';

if ( is_admin() ) {
	require_once HELENDO_ADDONS_DIR . '/inc/importer.php';
}

/**
 * Init
 */
function helendo_vc_addons_init() {
	load_plugin_textdomain( 'helendo', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

	new Helendo_VC;
	new Helendo_Shortcodes;
}

add_action( 'after_setup_theme', 'helendo_vc_addons_init', 20 );