<?php
/**
 * Helendo theme customizer
 *
 * @package Helendo
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helendo_Customize {
	/**
	 * Customize settings
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * The class constructor
	 *
	 * @param array $config
	 */
	public function __construct( $config ) {
		$this->config = $config;

		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}

		$this->register();
	}

	/**
	 * Register settingsheade
	 */
	public function register() {

		/**
		 * Add the theme configuration
		 */
		if ( ! empty( $this->config['theme'] ) ) {
			Kirki::add_config(
				$this->config['theme'], array(
					'capability'  => 'edit_theme_options',
					'option_type' => 'theme_mod',
				)
			);
		}

		/**
		 * Add panels
		 */
		if ( ! empty( $this->config['panels'] ) ) {
			foreach ( $this->config['panels'] as $panel => $settings ) {
				Kirki::add_panel( $panel, $settings );
			}
		}

		/**
		 * Add sections
		 */
		if ( ! empty( $this->config['sections'] ) ) {
			foreach ( $this->config['sections'] as $section => $settings ) {
				Kirki::add_section( $section, $settings );
			}
		}

		/**
		 * Add fields
		 */
		if ( ! empty( $this->config['theme'] ) && ! empty( $this->config['fields'] ) ) {
			foreach ( $this->config['fields'] as $name => $settings ) {
				if ( ! isset( $settings['settings'] ) ) {
					$settings['settings'] = $name;
				}

				Kirki::add_field( $this->config['theme'], $settings );
			}
		}
	}

	/**
	 * Get config ID
	 *
	 * @return string
	 */
	public function get_theme() {
		return $this->config['theme'];
	}

	/**
	 * Get customize setting value
	 *
	 * @param string $name
	 *
	 * @return bool|string
	 */
	public function get_option( $name ) {

		$default = $this->get_option_default( $name );

		return get_theme_mod( $name, $default );
	}

	/**
	 * Get default option values
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function get_option_default( $name ) {
		if ( ! isset( $this->config['fields'][ $name ] ) ) {
			return false;
		}

		return isset( $this->config['fields'][ $name ]['default'] ) ? $this->config['fields'][ $name ]['default'] : false;
	}
}

/**
 * This is a short hand function for getting setting value from customizer
 *
 * @param string $name
 *
 * @return bool|string
 */
function helendo_get_option( $name ) {
	global $helendo_customize;

	$value = false;

	if ( class_exists( 'Kirki' ) ) {
		$value = Kirki::get_option( 'helendo', $name );
	} elseif ( ! empty( $helendo_customize ) ) {
		$value = $helendo_customize->get_option( $name );
	}

	return apply_filters( 'helendo_get_option', $value, $name );
}

/**
 * Get default option values
 *
 * @param $name
 *
 * @return mixed
 */
function helendo_get_option_default( $name ) {
	global $helendo_customize;

	if ( empty( $helendo_customize ) ) {
		return false;
	}

	return $helendo_customize->get_option_default( $name );
}

/**
 * Move some default sections to `general` panel that registered by theme
 *
 * @param object $wp_customize
 */
function helendo_customize_modify( $wp_customize ) {
	$wp_customize->get_section( 'title_tagline' )->panel     = 'general';
	$wp_customize->get_section( 'static_front_page' )->panel = 'general';
}

add_action( 'customize_register', 'helendo_customize_modify' );

/**
 * Options of header items
 *
 * @return array
 */
function helendo_header_items_option() {
	return apply_filters(
		'helendo_header_items_option', array(
			'0'                 => esc_html__( 'Select a item', 'helendo' ),
			'logo'              => esc_html__( 'Logo', 'helendo' ),
			'menu-primary'      => esc_html__( 'Primary Menu', 'helendo' ),
			'menu'              => esc_html__( 'Menu Icon', 'helendo' ),
			'search'            => esc_html__( 'Search Icon', 'helendo' ),
			'cart'              => esc_html__( 'Cart Icon', 'helendo' ),
			'wishlist'          => esc_html__( 'Wishlist Icon', 'helendo' ),
			'account'           => esc_html__( 'Account Icon', 'helendo' ),
			'language-currency' => esc_html__( 'Language & Currency', 'helendo' ),
			'phone'             => esc_html__( 'Phone', 'helendo' ),
		)
	);
}

/**
 * Options of mobile header icons
 *
 * @return array
 */
function helendo_mobile_header_left_icons_option() {
	return apply_filters(
		'helendo_mobile_header_left_icons_option', array(
			'cart'          => esc_html__( 'Cart Icon', 'helendo' ),
			'wishlist'      => esc_html__( 'Wishlist Icon', 'helendo' ),
			'search-mobile' => esc_html__( 'Search Icon', 'helendo' ),
			'account'       => esc_html__( 'Account Icon', 'helendo' ),
		)
	);
}

/**
 * Options of mobile header icons
 *
 * @return array
 */
function helendo_mobile_header_right_icons_option() {
	return apply_filters(
		'helendo_mobile_header_right_icons_option', array(
			'search-mobile' => esc_html__( 'Search Icon', 'helendo' ),
			'cart'          => esc_html__( 'Cart Icon', 'helendo' ),
			'wishlist'      => esc_html__( 'Wishlist Icon', 'helendo' ),
			'account'       => esc_html__( 'Account Icon', 'helendo' ),
		)
	);
}

/**
 * Options of footer items
 *
 * @return array
 */
function helendo_footer_items_option() {
	return apply_filters(
		'helendo_footer_items_option', array(
			'0'         => esc_html__( 'Select a item', 'helendo' ),
			'logo'      => esc_html__( 'Logo', 'helendo' ),
			'copyright' => esc_html__( 'Copyright', 'helendo' ),
			'menu'      => esc_html__( 'Footer menu', 'helendo' ),
			'social'    => esc_html__( 'Social menu', 'helendo' ),
			'text'      => esc_html__( 'Custom text', 'helendo' ),
		)
	);
}

/**
 * Get customize settings
 *
 * @return array
 */
function helendo_customize_settings() {
	/**
	 * Customizer configuration
	 */

	$settings = array(
		'theme' => 'helendo',
	);

	$panels = array(
		'general'     => array(
			'priority' => 5,
			'title'    => esc_html__( 'General', 'helendo' ),
		),
		'typography'  => array(
			'priority' => 10,
			'title'    => esc_html__( 'Typography', 'helendo' ),
		),
		'styling'     => array(
			'title'    => esc_html__( 'Styling', 'helendo' ),
			'priority' => 10,
		),
		'header'      => array(
			'title'      => esc_html__( 'Header', 'helendo' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
		'page_header' => array(
			'title'      => esc_html__( 'Page Header', 'helendo' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
		'blog'        => array(
			'title'      => esc_html__( 'Blog', 'helendo' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
		'footer'      => array(
			'title'      => esc_html__( 'Footer', 'helendo' ),
			'priority'   => 100,
			'capability' => 'edit_theme_options',
		),
		'mobile'      => array(
			'title'      => esc_html__( 'Mobile', 'helendo' ),
			'priority'   => 100,
			'capability' => 'edit_theme_options',
		),
	);

	$sections = array(
		// Maintenance
		'maintenance'              => array(
			'title'      => esc_html__( 'Maintenance', 'helendo' ),
			'priority'   => 5,
			'capability' => 'edit_theme_options',
		),

		// Typography
		'body_typo'                => array(
			'title'       => esc_html__( 'Body', 'helendo' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'heading_typo'             => array(
			'title'       => esc_html__( 'Heading', 'helendo' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'header_typo'              => array(
			'title'       => esc_html__( 'Header', 'helendo' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'footer_typo'              => array(
			'title'       => esc_html__( 'Footer', 'helendo' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),

		// Styling Section
		'backtotop'                => array(
			'title'       => esc_html__( 'Back to Top', 'helendo' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),
		'color_scheme'             => array(
			'title'       => esc_html__( 'Color Scheme', 'helendo' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),

		// Header Sections
		'header_logo'              => array(
			'title'       => esc_html__( 'Header Logo', 'helendo' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_layout'            => array(
			'title'       => esc_html__( 'Header Layout', 'helendo' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_main'              => array(
			'title'    => esc_html__( 'Header Main', 'helendo' ),
			'priority' => 40,
			'panel'    => 'header',
		),
		'header_bottom'            => array(
			'title'    => esc_html__( 'Header Bottom', 'helendo' ),
			'priority' => 50,
			'panel'    => 'header',
		),
		'header_search'            => array(
			'title'    => esc_html__( 'Search', 'helendo' ),
			'priority' => 70,
			'panel'    => 'header',
		),
		'header_phone'             => array(
			'title'    => esc_html__( 'Phone', 'helendo' ),
			'priority' => 70,
			'panel'    => 'header',
		),
		'header_cart'              => array(
			'title'    => esc_html__( 'Cart', 'helendo' ),
			'priority' => 80,
			'panel'    => 'header',
		),
		'header_account'           => array(
			'title'    => esc_html__( 'Account', 'helendo' ),
			'priority' => 90,
			'panel'    => 'header',
		),
		'header_language_currency' => array(
			'title'    => esc_html__( 'Language & Currency', 'helendo' ),
			'priority' => 90,
			'panel'    => 'header',
		),
		// Page Header Sections

		'page_header_site'    => array(
			'title'    => esc_html__( 'On Whole Site', 'helendo' ),
			'priority' => 90,
			'panel'    => 'page_header',
		),
		'page_header_page'    => array(
			'title'    => esc_html__( 'On Page', 'helendo' ),
			'priority' => 90,
			'panel'    => 'page_header',
		),
		'page_header_blog'    => array(
			'title'    => esc_html__( 'On Blog Page', 'helendo' ),
			'priority' => 90,
			'panel'    => 'page_header',
		),
		'page_header_post'    => array(
			'title'    => esc_html__( 'On Post Page', 'helendo' ),
			'priority' => 90,
			'panel'    => 'page_header',
		),
		'page_header_catalog' => array(
			'title'    => esc_html__( 'On Catalog Page', 'helendo' ),
			'priority' => 90,
			'panel'    => 'page_header',
		),
		'page_header_product' => array(
			'title'    => esc_html__( 'On Product Page', 'helendo' ),
			'priority' => 90,
			'panel'    => 'page_header',
		),

		// Blog Sections

		'blog_page'   => array(
			'title'       => esc_html__( 'Blog Page', 'helendo' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),
		'single_post' => array(
			'title'       => esc_html__( 'Single Post', 'helendo' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),

		// Footer Sections

		'newsletter' => array(
			'title'       => esc_html__( 'Newsletter', 'helendo' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'footer',
		),

		'footer_layout'  => array(
			'title'       => esc_html__( 'Footer Layout', 'helendo' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'footer',
		),
		'footer_widgets' => array(
			'title'    => esc_html__( 'Footer Widgets', 'helendo' ),
			'priority' => 40,
			'panel'    => 'footer',
		),
		'footer_main'    => array(
			'title'    => esc_html__( 'Footer Main', 'helendo' ),
			'priority' => 40,
			'panel'    => 'footer',
		),
		'footer_bottom'  => array(
			'title'    => esc_html__( 'Footer Bottom', 'helendo' ),
			'priority' => 40,
			'panel'    => 'footer',
		),
		'footer_menu'    => array(
			'title'    => esc_html__( 'Footer Menu', 'helendo' ),
			'priority' => 40,
			'panel'    => 'footer',
		),
		'footer_socials' => array(
			'title'    => esc_html__( 'Footer Socials', 'helendo' ),
			'priority' => 40,
			'panel'    => 'footer',
		),

		// Mobile

		'header_mobile'       => array(
			'title'    => esc_html__( 'Header', 'helendo' ),
			'priority' => 40,
			'panel'    => 'mobile',
		),
		'header_mobile_cart'  => array(
			'title'    => esc_html__( 'Header Cart', 'helendo' ),
			'priority' => 40,
			'panel'    => 'mobile',
		),
		'canvas_panel_mobile' => array(
			'title'    => esc_html__( 'Canvas Panel', 'helendo' ),
			'priority' => 40,
			'panel'    => 'mobile',
		),
		'product_carousel'    => array(
			'title'    => esc_html__( 'Product Carousel', 'helendo' ),
			'priority' => 40,
			'panel'    => 'mobile',
		),
	);

	$fields = array(
		// Maintenance
		'maintenance_enable'           => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Enable Maintenance Mode', 'helendo' ),
			'description' => esc_html__( 'Put your site into maintenance mode', 'helendo' ),
			'default'     => false,
			'section'     => 'maintenance',
		),
		'maintenance_mode'             => array(
			'type'        => 'radio',
			'label'       => esc_html__( 'Mode', 'helendo' ),
			'description' => esc_html__( 'Select the correct mode for your site', 'helendo' ),
			'tooltip'     => wp_kses_post( sprintf( __( 'If you are putting your site into maintenance mode for a longer perior of time, you should set this to "Coming Soon". Maintenance will return HTTP 503, Comming Soon will set HTTP to 200. <a href="%s" target="_blank">Learn more</a>', 'helendo' ), 'https://yoast.com/http-503-site-maintenance-seo/' ) ),
			'default'     => 'maintenance',
			'section'     => 'maintenance',
			'choices'     => array(
				'maintenance' => esc_attr__( 'Maintenance', 'helendo' ),
				'coming_soon' => esc_attr__( 'Coming Soon', 'helendo' ),
			),
		),
		'maintenance_page'             => array(
			'type'    => 'dropdown-pages',
			'label'   => esc_html__( 'Maintenance Page', 'helendo' ),
			'default' => 0,
			'section' => 'maintenance',
		),
		'maintenance_textcolor'        => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Text Color', 'helendo' ),
			'default' => 'dark',
			'section' => 'maintenance',
			'choices' => array(
				'dark'  => esc_attr__( 'Dark', 'helendo' ),
				'light' => esc_attr__( 'Light', 'helendo' ),
			),
		),

		// Typography
		'body_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Body', 'helendo' ),
			'section'  => 'body_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '400',
				'font-size'      => '16px',
				'line-height'    => '1.5',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#666666',
				'text-transform' => 'none',
			),
		),
		'heading1_typo'                => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 1', 'helendo' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '500',
				'font-size'      => '60px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000000',
				'text-transform' => 'none',
			),
		),
		'heading2_typo'                => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 2', 'helendo' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '500',
				'font-size'      => '40px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000000',
				'text-transform' => 'none',
			),
		),
		'heading3_typo'                => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 3', 'helendo' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '500',
				'font-size'      => '30px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000000',
				'text-transform' => 'none',
			),
		),
		'heading4_typo'                => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 4', 'helendo' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '500',
				'font-size'      => '24px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000000',
				'text-transform' => 'none',
			),
		),
		'heading5_typo'                => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 5', 'helendo' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '500',
				'font-size'      => '18px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000000',
				'text-transform' => 'none',
			),
		),
		'heading6_typo'                => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 6', 'helendo' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '500',
				'font-size'      => '16px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000000',
				'text-transform' => 'none',
			),
		),
		'menu_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Menu', 'helendo' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '500',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '18px',
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'sub_menu_typo'                => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Sub Menu', 'helendo' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Roboto',
				'variant'        => '400',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '16px',
				'color'          => '#999999',
				'text-transform' => 'none',
			),
		),
		'footer_text_typo'             => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Footer Text', 'helendo' ),
			'section'  => 'footer_typo',
			'priority' => 10,
			'default'  => array(
				'font-family' => 'Roboto',
				'variant'     => '400',
				'subsets'     => array( 'latin-ext' ),
				'font-size'   => '16px',
			),
		),

		// Styling
		'back_to_top'                  => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Back to Top', 'helendo' ),
			'section'  => 'backtotop',
			'default'  => 0,
			'priority' => 10,
		),
		'back_to_top_style'            => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Style', 'helendo' ),
			'default' => '1',
			'section' => 'backtotop',
			'choices' => array(
				'1' => esc_attr__( 'Style 1', 'helendo' ),
				'2' => esc_attr__( 'Style 2', 'helendo' ),
			),
		),

		// Color Scheme
		'color_scheme'                 => array(
			'type'     => 'palette',
			'label'    => esc_html__( 'Base Color Scheme', 'helendo' ),
			'default'  => '',
			'section'  => 'color_scheme',
			'priority' => 10,
			'choices'  => array(
				''        => array( '#dcb14a' ),
				'#7cafca' => array( '#7cafca' ),
				'#cc0000' => array( '#cc0000' )
			),
		),
		'custom_color_scheme'          => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Custom Color Scheme', 'helendo' ),
			'default'  => 0,
			'section'  => 'color_scheme',
			'priority' => 10,
		),
		'custom_color'                 => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Color', 'helendo' ),
			'default'         => '',
			'section'         => 'color_scheme',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'custom_color_scheme',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Logo
		'logo_type'                    => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Logo Type', 'helendo' ),
			'default' => 'image',
			'section' => 'header_logo',
			'choices' => array(
				'image' => esc_html__( 'Image', 'helendo' ),
				'svg'   => esc_html__( 'SVG', 'helendo' ),
			),
		),
		'logo_svg'                     => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Logo SVG', 'helendo' ),
			'section'         => 'header_logo',
			'description'     => esc_html__( 'Paste SVG code of your logo here', 'helendo' ),
			'output'          => array(
				array(
					'element' => '.site-branding .logo',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'logo_type',
					'operator' => '==',
					'value'    => 'svg',
				),
			),
		),
		'logo'                         => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Logo', 'helendo' ),
			'default'         => '',
			'section'         => 'header_logo',
			'active_callback' => array(
				array(
					'setting'  => 'logo_type',
					'operator' => '==',
					'value'    => 'image',
				),
			),
		),
		'logo_dimension'               => array(
			'type'            => 'dimensions',
			'label'           => esc_html__( 'Logo Dimension', 'helendo' ),
			'default'         => array(
				'width'  => '',
				'height' => '',
			),
			'section'         => 'header_logo',
			'active_callback' => array(
				array(
					'setting'  => 'logo_type',
					'operator' => '!=',
					'value'    => 'text',
				),
			),
		),
		'logo_transparent'                         => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Logo Home Page Transparent', 'helendo' ),
			'default'         => '',
			'section'         => 'header_logo',
			'active_callback' => array(
				array(
					'setting'  => 'header_transparent',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'logo_transparent_dimension'               => array(
			'type'            => 'dimensions',
			'label'           => esc_html__( 'Logo Home Page Transparent Dimension', 'helendo' ),
			'default'         => array(
				'width'  => '',
				'height' => '',
			),
			'section'         => 'header_logo',
			'active_callback' => array(
				array(
					'setting'  => 'logo_type',
					'operator' => '!=',
					'value'    => 'text',
				),
			),
		),
		// Header Layout
		'header_type'                  => array(
			'type'        => 'radio',
			'label'       => esc_html__( 'Header Type', 'helendo' ),
			'description' => esc_html__( 'Select a default header or custom header', 'helendo' ),
			'section'     => 'header_layout',
			'default'     => 'default',
			'priority'    => 10,
			'choices'     => array(
				'default' => esc_html__( 'Default', 'helendo' ),
				'custom'  => esc_html__( 'Custom', 'helendo' ),
			),
		),
		'header_layout'                => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Header Layout', 'helendo' ),
			'section'         => 'header_layout',
			'default'         => 'v1',
			'priority'        => 10,
			'choices'         => array(
				'v1' => esc_html__( 'Header v1', 'helendo' ),
				'v2' => esc_html__( 'Header v2', 'helendo' ),
				'v3' => esc_html__( 'Header v3', 'helendo' ),
				'v4' => esc_html__( 'Header v4', 'helendo' ),
				'v5' => esc_html__( 'Header v5', 'helendo' ),
				'v6' => esc_html__( 'Header v6', 'helendo' ),
				'v7' => esc_html__( 'Header v7', 'helendo' ),
				'v8' => esc_html__( 'Header v8', 'helendo' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'header_container'             => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Header Container Width', 'helendo' ),
			'default'         => 'container',
			'section'         => 'header_layout',
			'choices'         => array(
				'container'         => esc_html__( 'Standard', 'helendo' ),
				'helendo-container' => esc_html__( 'Full Width', 'helendo' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'header_container_width'       => array(
			'type'            => 'slider',
			'label'           => esc_html__( 'Header Margin', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_layout',
			'default'         => 100,
			'choices'         => array(
				'min'  => 0,
				'max'  => 200,
				'step' => 5,
			),
			'js_vars'         => array(
				array(
					'element'  => '.site-header .helendo-container',
					'property' => 'margin-left',
					'units'    => 'px',
				),
				array(
					'element'  => '.site-header .helendo-container',
					'property' => 'margin-right',
					'units'    => 'px',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_container',
					'operator' => '==',
					'value'    => 'helendo-container',
				),
			),
		),
		'header_layout_custom_field_1' => array(
			'type'    => 'custom',
			'section' => 'header_layout',
			'default' => '<hr/>',
		),
		'header_sticky'                => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Header Sticky', 'helendo' ),
			'default'  => 0,
			'section'  => 'header_layout',
			'priority' => 100,
		),

		'header_transparent'                 => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Header Transparent', 'helendo' ),
			'default'     => 1,
			'section'     => 'header_layout',
			'priority'    => 100,
			'description' => esc_html__( 'Check this to enable header transparent in homepage only.', 'helendo' ),
		),
		'header_text_color'                => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Text Color', 'helendo' ),
			'section'         => 'header_layout',
			'default'         => '',
			'priority'        => 10,
			'choices'         => array(
				'' => esc_html__( 'Dark', 'helendo' ),
				'light' => esc_html__( 'Light', 'helendo' ),
			),
		),

		'header_sticky_el'                   => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Header Sticky Elements', 'helendo' ),
			'section'         => 'header_layout',
			'default'         => array( 'header_main' ),
			'priority'        => 100,
			'choices'         => array(
				'header_main'   => esc_html__( 'Header Main', 'helendo' ),
				'header_bottom' => esc_html__( 'Header Bottom', 'helendo' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_sticky',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'menu_sidebar_el'                    => array(
			'type'        => 'radio',
			'label'       => esc_html__( 'Menu Sidebar Elements', 'helendo' ),
			'section'     => 'header_layout',
			'description' => esc_html__( 'Choose Default if you just need show primary menu on Menu Sidebar. Choose Widget if you want to show elements from Menu Sidebar Widget', 'helendo' ),
			'default'     => array( 'default' ),
			'priority'    => 100,
			'choices'     => array(
				'default' => esc_html__( 'Default', 'helendo' ),
				'widget'  => esc_html__( 'Widget', 'helendo' ),
			),
		),

		// Header main
		'header_main_left'                   => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Left Items', 'helendo' ),
			'description'     => esc_html__( 'Control items on the left side of header main', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_main',
			'default'         => array(),
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'          => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_header_items_option(),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'header_main_center'                 => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Center Items', 'helendo' ),
			'description'     => esc_html__( 'Control items at the center of header main', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_main',
			'default'         => array(),
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'          => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_header_items_option(),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'header_main_right'                  => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Right Items', 'helendo' ),
			'description'     => esc_html__( 'Control items on the right of header main', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_main',
			'default'         => array(),
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'          => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_header_items_option(),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'header_main_hr'                     => array(
			'type'    => 'custom',
			'section' => 'header_main',
			'default' => '<hr>',
		),
		'header_main_height'                 => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Height', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'header_main',
			'default'   => '120',
			'choices'   => array(
				'min' => 50,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.header-main',
					'property' => 'height',
					'units'    => 'px',
				),
			),
		),
		'sticky_header_main_height'          => array(
			'type'            => 'slider',
			'label'           => esc_html__( 'Height', 'helendo' ),
			'description'     => esc_html__( 'Adjust Header Main height when Header Sticky is enable', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_main',
			'default'         => '80',
			'choices'         => array(
				'min' => 50,
				'max' => 500,
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_sticky',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.header-sticky .site-header.minimized .header-main',
					'property' => 'height',
					'units'    => 'px',
				),
			),
		),

		// Header Bottom
		'header_bottom_left'                 => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Left Items', 'helendo' ),
			'description'     => esc_html__( 'Control items on the left side of header bottom', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_bottom',
			'default'         => array(),
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'          => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_header_items_option(),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'header_bottom_center'               => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Center Items', 'helendo' ),
			'description'     => esc_html__( 'Control items at the center of header bottom', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_bottom',
			'default'         => array(),
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'          => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_header_items_option(),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'header_bottom_right'                => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Right Items', 'helendo' ),
			'description'     => esc_html__( 'Control items on the right of header bottom', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_bottom',
			'default'         => array(),
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'          => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_header_items_option(),
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_type',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'header_bottom_hr'                   => array(
			'type'    => 'custom',
			'section' => 'header_bottom',
			'default' => '<hr>',
		),
		'header_bottom_border_top'           => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Border Top', 'helendo' ),
			'section'  => 'header_bottom',
			'default'  => 0,
			'priority' => 10,
		),
		'header_bottom_height'               => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Height', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'header_bottom',
			'default'   => '90',
			'choices'   => array(
				'min' => 50,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.header-bottom',
					'property' => 'height',
					'units'    => 'px',
				),
			),
		),
		'sticky_header_bottom_height'        => array(
			'type'            => 'slider',
			'label'           => esc_html__( 'Height', 'helendo' ),
			'description'     => esc_html__( 'Adjust Header Bottom height when Header Sticky is enable', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'header_bottom',
			'default'         => '80',
			'choices'         => array(
				'min' => 50,
				'max' => 500,
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_sticky',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'js_vars'         => array(
				array(
					'element'  => '.header-sticky .site-header.minimized .header-bottom',
					'property' => 'height',
					'units'    => 'px',
				),
			),
		),

		// Header Search
		'header_search_style'                => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Style', 'helendo' ),
			'default' => 'form',
			'section' => 'header_search',
			'choices' => array(
				'form' => esc_html__( 'Icon and search field', 'helendo' ),
				'icon' => esc_html__( 'Icon only', 'helendo' ),
			),
		),
		'header_search_type'                 => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Search For', 'helendo' ),
			'default' => 'all',
			'section' => 'header_search',
			'choices' => array(
				'all'     => esc_html__( 'Search for everything', 'helendo' ),
				'product' => esc_html__( 'Search for products', 'helendo' ),
			),
		),
		'header_search_number'               => array(
			'type'    => 'number',
			'label'   => esc_html__( 'Number Items', 'helendo' ),
			'default' => 3,
			'section' => 'header_search',
		),
		'header_search_ajax'                 => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'AJAX Search', 'helendo' ),
			'section'     => 'header_search',
			'default'     => 0,
			'priority'    => 90,
			'description' => esc_html__( 'Check this option to enable AJAX search in the header', 'helendo' ),
		),

		// Header Cart
		'header_cart_action'                 => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Cart Action', 'helendo' ),
			'default' => 'click',
			'section' => 'header_cart',
			'choices' => array(
				'hover' => esc_html__( 'Hover', 'helendo' ),
				'click' => esc_html__( 'Click', 'helendo' ),
			),
		),
		'header_cart_behaviour'              => array(
			'type'            => 'radio',
			'label'           => esc_html__( 'Cart Icon Behaviour', 'helendo' ),
			'default'         => 'panel',
			'section'         => 'header_cart',
			'choices'         => array(
				'panel' => esc_attr__( 'Open the cart panel', 'helendo' ),
				'link'  => esc_attr__( 'Open the cart page', 'helendo' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_cart_action',
					'operator' => '==',
					'value'    => 'click',
				),
			),
		),

		// Header Account
		'header_account_behaviour'           => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Account Icon Behaviour', 'helendo' ),
			'default' => 'panel',
			'section' => 'header_account',
			'choices' => array(
				'panel' => esc_attr__( 'Open the login panel', 'helendo' ),
				'link'  => esc_attr__( 'Open My Account page', 'helendo' ),
			),
		),

		// Header Phone
		'header_phone'                       => array(
			'type'    => 'textarea',
			'label'   => esc_html__( 'Phone Number', 'helendo' ),
			'default' => '',
			'section' => 'header_phone',
		),
		// Header Language & Currency
		'header_language_currency'           => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Language & Currency', 'helendo' ),
			'section'  => 'header_language_currency',
			'default'  => array( 'language', 'currency' ),
			'priority' => 10,
			'choices'  => array(
				'language' => esc_html__( 'Language', 'helendo' ),
				'currency' => esc_html__( 'Currency', 'helendo' ),
			),
		),
		// NewsLetter
		'newsletter_popup'                   => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Enable NewsLetter Popup', 'helendo' ),
			'default'     => 0,
			'section'     => 'newsletter',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show newsletter popup.', 'helendo' ),
		),
		'newsletter_home_popup'              => array(
			'type'        => 'toggle',
			'label'       => esc_html__( "Show in Homepage", 'helendo' ),
			'default'     => 1,
			'section'     => 'newsletter',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to disable newsletter popup in the homepage..', 'helendo' ),
		),
		'newsletter_bg_image'                => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Background Image', 'helendo' ),
			'default'  => '',
			'section'  => 'newsletter',
			'priority' => 20,
		),
		'newsletter_content'                 => array(
			'type'     => 'textarea',
			'label'    => esc_html__( 'Content', 'helendo' ),
			'default'  => '',
			'section'  => 'newsletter',
			'priority' => 20,
		),
		'newsletter_form'                    => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'NewsLetter Form', 'helendo' ),
			'default'     => '',
			'description' => sprintf( wp_kses_post( 'Enter the shortcode of MailChimp form . You can edit your sign - up form in the <a href= "%s" > MailChimp for WordPress form settings </a>.', 'helendo' ), admin_url( 'admin.php?page=mailchimp-for-wp-forms' ) ),
			'section'     => 'newsletter',
			'priority'    => 20,
		),
		'newsletter_reappear'                => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Reappear', 'helendo' ),
			'default'     => '1',
			'section'     => 'newsletter',
			'priority'    => 20,
			'description' => esc_html__( 'Reappear after how many day(s) using Cookie', 'helendo' ),
		),
		'newsletter_visible'                 => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Visible', 'helendo' ),
			'default'  => '1',
			'section'  => 'newsletter',
			'priority' => 20,
			'choices'  => array(
				'1' => esc_html__( 'After page loaded', 'helendo' ),
				'2' => esc_html__( 'After how many seconds', 'helendo' ),
			),
		),
		'newsletter_seconds'                 => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Seconds', 'helendo' ),
			'default'         => '10',
			'section'         => 'newsletter',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'newsletter_visible',
					'operator' => '==',
					'value'    => '2',
				),
			),
		),
		// Page Header Site
		'page_header'                        => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'helendo' ),
			'section'     => 'page_header_site',
			'description' => esc_html__( 'Enable to show a page header for whole site below the site header', 'helendo' ),
			'priority'    => 20,
			'default'     => 1,
		),
		'page_header_full_width'             => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'helendo' ),
			'section'  => 'page_header_site',
			'priority' => 20,
			'default'  => 0,
		),
		'page_header_els'                    => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Elements', 'helendo' ),
			'section'     => 'page_header_site',
			'default'     => array( 'breadcrumb', 'title' ),
			'priority'    => 20,
			'choices'     => array(
				'title'      => esc_html__( 'Title', 'helendo' ),
				'breadcrumb' => esc_html__( 'BreadCrumb', 'helendo' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'helendo' ),
		),
		'page_header_padding_top'            => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Top', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_site',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
		),
		'page_header_padding_bottom'         => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Bottom', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_site',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
		),
		// Page Header Blog
		'page_header_blog'                   => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'helendo' ),
			'section'     => 'page_header_blog',
			'description' => esc_html__( 'Enable to show a page header for whole site below the site header', 'helendo' ),
			'priority'    => 20,
			'default'     => 1,
		),
		'page_header_blog_full_width'        => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'helendo' ),
			'section'  => 'page_header_blog',
			'priority' => 20,
			'default'  => 0,
		),
		'page_header_blog_els'               => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Elements', 'helendo' ),
			'section'     => 'page_header_blog',
			'default'     => array( 'breadcrumb', 'title' ),
			'priority'    => 20,
			'choices'     => array(
				'title'      => esc_html__( 'Title', 'helendo' ),
				'breadcrumb' => esc_html__( 'BreadCrumb', 'helendo' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'helendo' ),
		),
		'page_header_blog_padding_top'       => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Top', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_blog',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
		),
		'page_header_blog_padding_bottom'    => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Bottom', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_blog',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
		),
		// Page Header Post
		'page_header_post'                   => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'helendo' ),
			'section'     => 'page_header_post',
			'description' => esc_html__( 'Enable to show a page header for whole site below the site header', 'helendo' ),
			'priority'    => 20,
			'default'     => 0,
		),
		'page_header_post_full_width'        => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'helendo' ),
			'section'  => 'page_header_post',
			'priority' => 20,
			'default'  => 0,
		),
		'page_header_post_els'               => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Elements', 'helendo' ),
			'section'     => 'page_header_post',
			'default'     => array( 'breadcrumb', 'title' ),
			'priority'    => 20,
			'choices'     => array(
				'title'      => esc_html__( 'Title', 'helendo' ),
				'breadcrumb' => esc_html__( 'BreadCrumb', 'helendo' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'helendo' ),
		),
		'page_header_post_padding_top'       => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Top', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_post',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
		),
		'page_header_post_padding_bottom'    => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Bottom', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_post',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
		),
		// Page Header Page
		'page_header_page'                   => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'helendo' ),
			'section'     => 'page_header_page',
			'description' => esc_html__( 'Enable to show a page header for whole site below the site header', 'helendo' ),
			'priority'    => 20,
			'default'     => 1,
		),
		'page_header_page_full_width'        => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'helendo' ),
			'section'  => 'page_header_page',
			'priority' => 20,
			'default'  => 0,
		),
		'page_header_page_els'               => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Elements', 'helendo' ),
			'section'     => 'page_header_page',
			'default'     => array( 'breadcrumb', 'title' ),
			'priority'    => 20,
			'choices'     => array(
				'title'      => esc_html__( 'Title', 'helendo' ),
				'breadcrumb' => esc_html__( 'BreadCrumb', 'helendo' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'helendo' ),
		),
		'page_header_page_padding_top'       => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Top', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_page',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
		),
		'page_header_page_padding_bottom'    => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Bottom', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_page',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
		),
		// Page Header Catalog
		'page_header_catalog'                => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'helendo' ),
			'section'     => 'page_header_catalog',
			'description' => esc_html__( 'Enable to show a page header for whole site below the site header', 'helendo' ),
			'priority'    => 20,
			'default'     => 1,
		),
		'page_header_catalog_full_width'     => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'helendo' ),
			'section'  => 'page_header_catalog',
			'priority' => 20,
			'default'  => 0,
		),
		'page_header_catalog_els'            => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Elements', 'helendo' ),
			'section'     => 'page_header_catalog',
			'default'     => array( 'breadcrumb', 'title' ),
			'priority'    => 20,
			'choices'     => array(
				'title'      => esc_html__( 'Title', 'helendo' ),
				'breadcrumb' => esc_html__( 'BreadCrumb', 'helendo' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'helendo' ),
		),
		'page_header_catalog_padding_top'    => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Top', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_catalog',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
		),
		'page_header_catalog_padding_bottom' => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Bottom', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_catalog',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
		),
		// Page Header Catalog
		'page_header_product'                => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Page Header', 'helendo' ),
			'section'     => 'page_header_product',
			'description' => esc_html__( 'Enable to show a page header for whole site below the site header', 'helendo' ),
			'default'     => 1,
			'priority'    => 20,
		),
		'page_header_product_full_width'     => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'helendo' ),
			'section'  => 'page_header_product',
			'priority' => 20,
			'default'  => 0,
		),
		'page_header_product_els'            => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Elements', 'helendo' ),
			'section'     => 'page_header_product',
			'default'     => array( 'breadcrumb', 'title' ),
			'priority'    => 20,
			'choices'     => array(
				'title'      => esc_html__( 'Title', 'helendo' ),
				'breadcrumb' => esc_html__( 'BreadCrumb', 'helendo' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'helendo' ),
		),
		'page_header_product_padding_top'    => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Top', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_product',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
		),
		'page_header_product_padding_bottom' => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Padding Bottom', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'page_header_product',
			'default'   => '50',
			'priority'  => 20,
			'choices'   => array(
				'min' => 10,
				'max' => 500,
			),
			'js_vars'   => array(
				array(
					'element'  => '.page-header',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
		),

		// Blog Page
		'blog_view'                          => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Blog View', 'helendo' ),
			'section'  => 'blog_page',
			'default'  => 'classic',
			'priority' => 10,
			'choices'  => array(
				'classic' => esc_html__( 'Classic', 'helendo' ),
				'grid'    => esc_html__( 'Grid', 'helendo' ),
				'list'    => esc_html__( 'List', 'helendo' ),
				'masonry' => esc_html__( 'Masonry', 'helendo' ),
			),
		),
		'blog_layout'                        => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Blog Layout', 'helendo' ),
			'section'         => 'blog_page',
			'default'         => 'content-sidebar',
			'priority'        => 10,
			'description'     => esc_html__( 'Select default sidebar for the blog page.', 'helendo' ),
			'choices'         => array(
				'content-sidebar' => esc_html__( 'Right Sidebar', 'helendo' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'helendo' ),
				'full-content'    => esc_html__( 'Full Content', 'helendo' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_view',
					'operator' => '==',
					'value'    => 'classic',
				),
			),
		),
		'blog_grid_style'                    => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Blog grid style', 'helendo' ),
			'section'         => 'blog_page',
			'default'         => '1',
			'priority'        => 10,
			'description'     => esc_html__( 'Select style for the blog grid page.', 'helendo' ),
			'choices'         => array(
				'1' => esc_html__( 'Style 1', 'helendo' ),
				'2' => esc_html__( 'Style 2', 'helendo' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_view',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
		'blog_columns'                       => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Blog Columns', 'helendo' ),
			'section'         => 'blog_page',
			'default'         => '3',
			'priority'        => 10,
			'description'     => esc_html__( 'Select default blog columns for the blog page.', 'helendo' ),
			'choices'         => array(
				'3' => esc_html__( '3 Columns', 'helendo' ),
				'2' => esc_html__( '2 Columns', 'helendo' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_view',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
		'excerpt_length'                     => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Excerpt Length', 'helendo' ),
			'section'  => 'blog_page',
			'default'  => 50,
			'priority' => 10,
		),
		'blog_entry_meta'                    => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Entry Meta', 'helendo' ),
			'section'  => 'blog_page',
			'default'  => array( 'date', 'author', 'cat' ),
			'choices'  => array(
				'date'   => esc_html__( 'Date', 'helendo' ),
				'author' => esc_html__( 'Author', 'helendo' ),
				'cat'    => esc_html__( 'Categories', 'helendo' ),
				'cmt'    => esc_html__( 'Count comment', 'helendo' ),
			),
			'priority' => 10,
		),
		'blog_read_more'                     => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Blog Read More', 'helendo' ),
			'section'  => 'blog_page',
			'default'  => esc_html__( 'Read more', 'helendo' ),
			'priority' => 10,
		),
		'type_nav'                           => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Type of Navigation', 'helendo' ),
			'section'  => 'blog_page',
			'default'  => 'numberic',
			'priority' => 10,
			'choices'  => array(
				'numberic'  => esc_html__( 'Numberic', 'helendo' ),
				'view_more' => esc_html__( 'Infinite Scroll', 'helendo' ),
			),
		),
		'view_more_text'                     => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Blog Scroll Text', 'helendo' ),
			'section'         => 'blog_page',
			'default'         => esc_html__( 'LOADING', 'helendo' ),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'type_nav',
					'operator' => '==',
					'value'    => 'view_more',
				),
			),
		),

		// Single Post
		'single_post_layout'                 => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Single Post Layout', 'helendo' ),
			'section'     => 'single_post',
			'default'     => 'full-content',
			'priority'    => 10,
			'description' => esc_html__( 'Select default sidebar for the single post page.', 'helendo' ),
			'choices'     => array(
				'content-sidebar' => esc_html__( 'Right Sidebar', 'helendo' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'helendo' ),
				'full-content'    => esc_html__( 'Full Content', 'helendo' ),
			),
		),
		'post_entry_meta'                    => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Entry Meta', 'helendo' ),
			'section'  => 'single_post',
			'default'  => array( 'date', 'author', 'cat', 'title' ),
			'choices'  => array(
				'title'  => esc_html__( 'Title', 'helendo' ),
				'date'   => esc_html__( 'Date', 'helendo' ),
				'author' => esc_html__( 'Author', 'helendo' ),
				'cat'    => esc_html__( 'Categories', 'helendo' ),
			),
			'priority' => 10,
		),
		'show_author_box'                    => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Author Box', 'helendo' ),
			'section'  => 'single_post',
			'default'  => 1,
			'priority' => 10,
		),
		'post_custom_field_1'                => array(
			'type'    => 'custom',
			'section' => 'single_post',
			'default' => '<hr/>',
		),

		'show_post_social_share' => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Socials Share', 'helendo' ),
			'description' => esc_html__( 'Check this option to show socials share in the single post page.', 'helendo' ),
			'section'     => 'single_post',
			'default'     => 0,
			'priority'    => 10,
		),

		'post_socials_share'            => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Socials Share', 'helendo' ),
			'section'         => 'single_post',
			'default'         => array( 'facebook', 'twitter', 'google', 'tumblr' ),
			'choices'         => array(
				'facebook'  => esc_html__( 'Facebook', 'helendo' ),
				'twitter'   => esc_html__( 'Twitter', 'helendo' ),
				'google'    => esc_html__( 'Google Plus', 'helendo' ),
				'tumblr'    => esc_html__( 'Tumblr', 'helendo' ),
				'pinterest' => esc_html__( 'Pinterest', 'helendo' ),
				'linkedin'  => esc_html__( 'Linkedin', 'helendo' ),
			),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'show_post_social_share',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'single_post_col'               => array(
			'type'        => 'slider',
			'label'       => esc_html__( 'Single Post Columns', 'helendo' ),
			'description' => esc_html__( 'Set Columns for Header Section and Comment area of Single Post', 'helendo' ),
			'section'     => 'single_post',
			'transport'   => 'auto',
			'default'     => 12,
			'choices'     => array(
				'min'  => '1',
				'max'  => '12',
				'step' => '1',
			),
		),
		'single_post_col_offset'        => array(
			'type'        => 'slider',
			'label'       => esc_html__( 'Single Post Offset Columns', 'helendo' ),
			'description' => esc_html__( 'Increase the left margin of Header Section and Comment area in Single Post by number columns', 'helendo' ),
			'section'     => 'single_post',
			'transport'   => 'auto',
			'default'     => 0,
			'choices'     => array(
				'min'  => '0',
				'max'  => '11',
				'step' => '1',
			),
		),
		// Footer Layout
		'footer_layout'                 => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Footer Layout', 'helendo' ),
			'default' => '1',
			'section' => 'footer_layout',
			'choices' => array(
				'1' => esc_html__( 'Layout 1', 'helendo' ),
				'2' => esc_html__( 'Layout 2', 'helendo' ),
			),
		),
		'footer_fixed'                  => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Footer Fixed', 'helendo' ),
			'section'  => 'footer_layout',
			'default'  => 0,
			'priority' => 10,
		),
		'footer_border_top'             => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Footer Border Top', 'helendo' ),
			'section'  => 'footer_layout',
			'default'  => 0,
			'priority' => 10,
		),
		'footer_layout_divide_1'        => array(
			'type'    => 'custom',
			'default' => '<hr>',
			'section' => 'footer_layout',
		),
		'footer_container'              => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Footer Container Width', 'helendo' ),
			'default' => 'container',
			'section' => 'footer_layout',
			'choices' => array(
				'container'         => esc_html__( 'Standard', 'helendo' ),
				'helendo-container' => esc_html__( 'Full Width', 'helendo' ),
			),
		),
		'footer_container_width'        => array(
			'type'            => 'slider',
			'label'           => esc_html__( 'Footer Margin', 'helendo' ),
			'transport'       => 'postMessage',
			'section'         => 'footer_layout',
			'default'         => 100,
			'choices'         => array(
				'min'  => 0,
				'max'  => 200,
				'step' => 5,
			),
			'js_vars'         => array(
				array(
					'element'  => '.site-footer .helendo-container',
					'property' => 'margin-left',
					'units'    => 'px',
				),
				array(
					'element'  => '.site-footer .helendo-container',
					'property' => 'margin-right',
					'units'    => 'px',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_container',
					'operator' => '==',
					'value'    => 'helendo-container',
				),
			),
		),
		'footer_layout_divide_2'        => array(
			'type'    => 'custom',
			'default' => '<hr>',
			'section' => 'footer_layout',
		),
		'footer_main_top_spacing'       => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Footer Main Top Spacing', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'footer_layout',
			'default'   => '145',
			'choices'   => array(
				'min' => 30,
				'max' => 200,
			),
			'js_vars'   => array(
				array(
					'element'  => '.footer-main',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
		),
		'footer_main_bottom_spacing'    => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Footer Main Bottom Spacing', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'footer_layout',
			'default'   => '160',
			'choices'   => array(
				'min' => 30,
				'max' => 200,
			),
			'js_vars'   => array(
				array(
					'element'  => '.footer-main',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
		),

		// Footer Widget
		'footer_widgets'                => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Footer Widgets', 'helendo' ),
			'section'  => 'footer_widgets',
			'default'  => 0,
			'priority' => 10,
		),
		'footer_widgets_columns'        => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Footer Widgets Columns', 'helendo' ),
			'section'     => 'footer_widgets',
			'default'     => '4',
			'priority'    => 10,
			'choices'     => array(
				'1' => esc_html__( '1 Columns', 'helendo' ),
				'2' => esc_html__( '2 Columns', 'helendo' ),
				'3' => esc_html__( '3 Columns', 'helendo' ),
				'4' => esc_html__( '4 Columns', 'helendo' ),
			),
			'description' => esc_html__( 'Go to Appearance/Widgets/Footer Widget 1, 2, 3, 4 to add widgets content.', 'helendo' ),
		),
		'footer_widgets_divide_1'       => array(
			'type'    => 'custom',
			'default' => '<hr>',
			'section' => 'footer_widgets',
		),
		'footer_widgets_top_spacing'    => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Footer Widget Top Spacing', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'footer_widgets',
			'default'   => '100',
			'choices'   => array(
				'min' => 10,
				'max' => 200,
			),
			'js_vars'   => array(
				array(
					'element'  => '.footer-widget',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
		),
		'footer_widgets_bottom_spacing' => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Footer Widget Bottom Spacing', 'helendo' ),
			'transport' => 'postMessage',
			'section'   => 'footer_widgets',
			'default'   => '50',
			'choices'   => array(
				'min' => 10,
				'max' => 200,
			),
			'js_vars'   => array(
				array(
					'element'  => '.footer-widget',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
		),
		// Footer Main
		'footer_main_left'              => array(
			'type'        => 'repeater',
			'label'       => esc_html__( 'Left Items', 'helendo' ),
			'description' => esc_html__( 'Control left items of the footer', 'helendo' ),
			'transport'   => 'postMessage',
			'section'     => 'footer_main',
			'default'     => array(),
			'row_label'   => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'      => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_footer_items_option(),
				),
			),
		),
		'footer_main_center'            => array(
			'type'        => 'repeater',
			'label'       => esc_html__( 'Center Items', 'helendo' ),
			'description' => esc_html__( 'Control center items of the footer', 'helendo' ),
			'transport'   => 'postMessage',
			'section'     => 'footer_main',
			'default'     => array(),
			'row_label'   => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'      => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_footer_items_option(),
				),
			),
		),
		'footer_main_right'             => array(
			'type'        => 'repeater',
			'label'       => esc_html__( 'Right Items', 'helendo' ),
			'description' => esc_html__( 'Control right items of the footer', 'helendo' ),
			'transport'   => 'postMessage',
			'section'     => 'footer_main',
			'default'     => array(),
			'row_label'   => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'      => array(
				'item' => array(
					'type'    => 'select',
					'default' => 'copyright',
					'choices' => helendo_footer_items_option(),
				),
			),
		),
		'footer_main_divide_1'          => array(
			'type'    => 'custom',
			'default' => '<hr>',
			'section' => 'footer_main',
		),
		'footer_main_border'            => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Border Line', 'helendo' ),
			'description' => esc_html__( 'Display a divide line on top', 'helendo' ),
			'default'     => 0,
			'section'     => 'footer_main',
		),
		'footer_main_divide_2'          => array(
			'type'    => 'custom',
			'default' => '<hr>',
			'section' => 'footer_main',
		),
		'footer_copyright'              => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Footer Copyright', 'helendo' ),
			'description' => esc_html__( 'Display copyright info on the left side of footer', 'helendo' ),
			'default'     => '',
			'section'     => 'footer_main',
		),
		'footer_main_text'              => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Custom Text', 'helendo' ),
			'description' => esc_html__( 'The content of the Custom Text item', 'helendo' ),
			'section'     => 'footer_main',
		),
		'footer_main_divide_3'          => array(
			'type'    => 'custom',
			'default' => '<hr>',
			'section' => 'footer_main',
		),
		'footer_main_logo_type'         => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Logo Type', 'helendo' ),
			'default' => 'image',
			'section' => 'footer_main',
			'choices' => array(
				'image' => esc_html__( 'Image', 'helendo' ),
				'svg'   => esc_html__( 'SVG', 'helendo' ),
			),
		),
		'footer_main_logo_svg'          => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Logo SVG', 'helendo' ),
			'section'         => 'footer_main',
			'description'     => esc_html__( 'Paste SVG code of your logo here', 'helendo' ),
			'output'          => array(
				array(
					'element' => '.footer-logo a',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_main_logo_type',
					'operator' => '==',
					'value'    => 'svg',
				),
			),
		),
		'footer_main_logo'              => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Logo', 'helendo' ),
			'default'         => '',
			'section'         => 'footer_main',
			'active_callback' => array(
				array(
					'setting'  => 'footer_main_logo_type',
					'operator' => '==',
					'value'    => 'image',
				),
			),
		),

		// Footer Bottom
		'footer_bottom_left'            => array(
			'type'        => 'repeater',
			'label'       => esc_html__( 'Left Items', 'helendo' ),
			'description' => esc_html__( 'Control left items of the footer', 'helendo' ),
			'transport'   => 'postMessage',
			'section'     => 'footer_bottom',
			'default'     => array(),
			'row_label'   => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'      => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_footer_items_option(),
				),
			),
		),
		'footer_bottom_center'          => array(
			'type'        => 'repeater',
			'label'       => esc_html__( 'Center Items', 'helendo' ),
			'description' => esc_html__( 'Control center items of the footer', 'helendo' ),
			'transport'   => 'postMessage',
			'section'     => 'footer_bottom',
			'default'     => array(),
			'row_label'   => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'      => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_footer_items_option(),
				),
			),
		),
		'footer_bottom_right'           => array(
			'type'        => 'repeater',
			'label'       => esc_html__( 'Right Items', 'helendo' ),
			'description' => esc_html__( 'Control right items of the footer', 'helendo' ),
			'transport'   => 'postMessage',
			'section'     => 'footer_bottom',
			'default'     => array(),
			'row_label'   => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'      => array(
				'item' => array(
					'type'    => 'select',
					'default' => 'copyright',
					'choices' => helendo_footer_items_option(),
				),
			),
		),
		'footer_bottom_divide_1'        => array(
			'type'    => 'custom',
			'default' => '<hr>',
			'section' => 'footer_bottom',
		),
		'footer_bottom_copyright'       => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Footer Copyright', 'helendo' ),
			'description' => esc_html__( 'Display copyright info on the left side of footer', 'helendo' ),
			'default'     => sprintf( '%s %s. ' . esc_html__( 'All rights reserved', 'helendo' ), '&copy; ' . date( 'Y' ), get_bloginfo( 'name' ) ),
			'section'     => 'footer_bottom',
		),
		'footer_bottom_text'            => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Custom Text', 'helendo' ),
			'description' => esc_html__( 'The content of the Custom Text item', 'helendo' ),
			'section'     => 'footer_bottom',
		),
		'footer_bottom_divide_2'        => array(
			'type'    => 'custom',
			'default' => '<hr>',
			'section' => 'footer_bottom',
		),
		'footer_bottom_logo_type'       => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Logo Type', 'helendo' ),
			'default' => 'image',
			'section' => 'footer_bottom',
			'choices' => array(
				'image' => esc_html__( 'Image', 'helendo' ),
				'svg'   => esc_html__( 'SVG', 'helendo' ),
			),
		),
		'footer_bottom_logo_svg'        => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Logo SVG', 'helendo' ),
			'section'         => 'footer_bottom',
			'description'     => esc_html__( 'Paste SVG code of your logo here', 'helendo' ),
			'output'          => array(
				array(
					'element' => '.footer-logo a',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_main_logo_type',
					'operator' => '==',
					'value'    => 'svg',
				),
			),
		),
		'footer_bottom_logo'            => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Logo', 'helendo' ),
			'default'         => '',
			'section'         => 'footer_bottom',
			'active_callback' => array(
				array(
					'setting'  => 'footer_bottom_logo_type',
					'operator' => '==',
					'value'    => 'image',
				),
			),
		),
		'footer_menu'                   => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Footer Menu', 'helendo' ),
			'section'   => 'footer_menu',
			'priority'  => 10,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_attr__( 'Item', 'helendo' ),
			),
			'fields'    => array(
				'link_url'  => array(
					'type'    => 'text',
					'label'   => esc_html__( 'URL', 'helendo' ),
					'default' => '',
				),
				'link_text' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Link Text', 'helendo' ),
					'default' => '',
				),
			),
		),
		'footer_socials'                => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Footer Socials', 'helendo' ),
			'section'   => 'footer_socials',
			'priority'  => 10,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_attr__( 'Social', 'helendo' ),
			),
			'default'   => array(
				array(
					'link_url' => 'https://facebook.com/',
				),
				array(
					'link_url' => 'https://twitter.com/',
				),
				array(
					'link_url' => 'https://rss.com/',
				),
			),
			'fields'    => array(
				'link_url' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Social URL', 'helendo' ),
					'default' => '',
				),
			),
		),

		// Mobile
		//Header

		'mobile_header_height'         => array(
			'type'      => 'slider',
			'label'     => esc_html__( 'Header Height', 'helendo' ),
			'transport' => 'postMessage',
			'default'   => '60',
			'section'   => 'header_mobile',
			'choices'   => array(
				'min' => 40,
				'max' => 200,
			),
			'js_vars'   => array(
				array(
					'element'  => '.header-mobile',
					'property' => 'height',
					'units'    => 'px',
				),
			),
		),
		'mobile_custom_logo'           => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Mobile Custom Logo', 'helendo' ),
			'description' => esc_html__( 'Use a different logo on mobile', 'helendo' ),
			'section'     => 'header_mobile',
			'default'     => false,
		),
		'mobile_logo'                  => array(
			'type'            => 'image',
			'section'         => 'header_mobile',
			'active_callback' => array(
				array(
					'setting'  => 'mobile_custom_logo',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		'mobile_logo_position'         => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Logo Position', 'helendo' ),
			'default' => 'center',
			'section' => 'header_mobile',
			'choices' => array(
				'center' => esc_attr__( 'Center', 'helendo' ),
				'left'   => esc_attr__( 'Left', 'helendo' ),
			),
		),
		'mobile_header_left_icons'     => array(
			'type'        => 'repeater',
			'label'       => esc_html__( 'Header Left Icons', 'helendo' ),
			'description' => esc_html__( 'Control icons on the left side of mobile header', 'helendo' ),
			'transport'   => 'postMessage',
			'section'     => 'header_mobile',
			'default'     => array( array( 'item' => 'search-mobile' ) ),
			'row_label'   => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'      => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_mobile_header_left_icons_option(),
				),
			),
		),
		'mobile_header_right_icons'    => array(
			'type'        => 'repeater',
			'label'       => esc_html__( 'Header Right Icons', 'helendo' ),
			'description' => esc_html__( 'Control icons on the right side of mobile header', 'helendo' ),
			'transport'   => 'postMessage',
			'section'     => 'header_mobile',
			'default'     => array( array( 'item' => 'cart' ) ),
			'row_label'   => array(
				'type'  => 'field',
				'value' => esc_attr__( 'Item', 'helendo' ),
				'field' => 'item',
			),
			'fields'      => array(
				'item' => array(
					'type'    => 'select',
					'choices' => helendo_mobile_header_right_icons_option(),
				),
			),
		),
		'menu_sidebar_mobile_el'       => array(
			'type'        => 'radio',
			'label'       => esc_html__( 'Menu Sidebar Elements', 'helendo' ),
			'section'     => 'header_mobile',
			'description' => esc_html__( 'Choose Default if you just need show primary menu on Menu Sidebar. Choose Widget if you want to show elements from Menu Sidebar Widget', 'helendo' ),
			'default'     => array( 'default' ),
			'priority'    => 100,
			'choices'     => array(
				'default' => esc_html__( 'Default', 'helendo' ),
				'widget'  => esc_html__( 'Widget', 'helendo' ),
			),
		),
		// Header Cart
		'header_mobile_cart_behaviour' => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Cart Icon Behaviour', 'helendo' ),
			'default' => 'panel',
			'section' => 'header_mobile_cart',
			'choices' => array(
				'panel' => esc_attr__( 'Open the cart panel', 'helendo' ),
				'link'  => esc_attr__( 'Open the cart page', 'helendo' ),
			),
		),
		// Canvas
		'canvas_panel_width_mobile'    => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Canvas Panel Width', 'helendo' ),
			'default' => 'standard',
			'section' => 'canvas_panel_mobile',
			'choices' => array(
				'standard' => esc_attr__( 'Standard', 'helendo' ),
				'full'     => esc_attr__( 'Full Width', 'helendo' ),
			),
		),
		// Product Carousel
		'related_product_dot'          => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Dots', 'helendo' ),
			'description' => esc_html__( 'Related Product - Show Dots on Mobile', 'helendo' ),
			'default'     => true,
			'section'     => 'product_carousel',
		),
		'upsells_product_dot'          => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Dots', 'helendo' ),
			'description' => esc_html__( 'Upsells Product - Show Dots on Mobile', 'helendo' ),
			'default'     => true,
			'section'     => 'product_carousel',
		),
		'cross_sells_product_dot'      => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Dots', 'helendo' ),
			'description' => esc_html__( 'Cross-sells Product - Show Dots on Mobile', 'helendo' ),
			'default'     => true,
			'section'     => 'product_carousel',
		),
		'instagram_photos_dot'         => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Dots', 'helendo' ),
			'description' => esc_html__( 'Instagram Photos - Show Dots on Mobile', 'helendo' ),
			'default'     => true,
			'section'     => 'product_carousel',
		),
	);

	$settings['panels']   = apply_filters( 'helendo_customize_panels', $panels );
	$settings['sections'] = apply_filters( 'helendo_customize_sections', $sections );
	$settings['fields']   = apply_filters( 'helendo_customize_fields', $fields );

	return $settings;
}

$helendo_customize = new Helendo_Customize( helendo_customize_settings() );