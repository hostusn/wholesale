<?php
/**
 * Register required, recommended plugins for theme
 *
 * @package Helendo
 */

/**
 * Register required plugins
 *
 * @since  1.0
 */
function helendo_register_required_plugins() {
	$plugins = array(
		array(
			'name'               => esc_html__( 'Meta Box', 'helendo' ),
			'slug'               => 'meta-box',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'Kirki', 'helendo' ),
			'slug'               => 'kirki',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'WooCommerce', 'helendo' ),
			'slug'               => 'woocommerce',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'WPBakery Page Builder', 'helendo' ),
			'slug'               => 'js_composer',
			'source'             => get_template_directory() . '/plugins/js_composer.zip',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'Helendo Addons', 'helendo' ),
			'slug'               => 'helendo-addons',
			'source'             => get_template_directory() . '/plugins/helendo-addons.zip',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			'version'            => '1.0.1',
		),
		array(
			'name'               => esc_html__( 'Revolution Slider', 'helendo' ),
			'slug'               => 'revslider',
			'source'             => get_template_directory() . '/plugins/revslider.zip',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'Woocommerce Deals', 'helendo' ),
			'slug'               => 'woocommerce-deals',
			'source'             => get_template_directory() . '/plugins/woocommerce-deals.zip',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'Contact Form 7', 'helendo' ),
			'slug'               => 'contact-form-7',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'MailChimp for WordPress', 'helendo' ),
			'slug'               => 'mailchimp-for-wp',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'YITH WooCommerce Wishlist', 'helendo' ),
			'slug'               => 'yith-woocommerce-wishlist',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'Variation Swatches for WooCommerce', 'helendo' ),
			'slug'               => 'variation-swatches-for-woocommerce',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
	);
	$config  = array(
		'domain'       => 'helendo',
		'default_path' => '',
		'menu'         => 'install-required-plugins',
		'has_notices'  => true,
		'is_automatic' => false,
		'message'      => '',
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'helendo' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'helendo' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'helendo' ),
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'helendo' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'helendo' ),
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'helendo' ),
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'helendo' ),
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'helendo' ),
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'helendo' ),
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'helendo' ),
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'helendo' ),
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'helendo' ),
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'helendo' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'helendo' ),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'helendo' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'helendo' ),
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'helendo' ),
			'nag_type'                        => 'updated',
		),
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'helendo_register_required_plugins' );
