<?php
/**
 * Functions of stylesheets and CSS
 *
 * @package Helendo
 */

if ( ! function_exists( 'helendo_get_inline_style' ) ) :
	/**
	 * Get inline style data
	 */
	function helendo_get_inline_style() {
		$css = '';

		$css .= helendo_get_home_boxed_background();

		// Header height
		$css .= '
			.header-main,
			.header-main .main-navigation ul.menu > li,
			.header-main .helendo-language-currency,
			.header-main .header-account,
			.header-main .header-cart.hover-action,
			.header-main .header-search.form
				{ height: ' . intval( helendo_get_option( 'header_main_height' ) ) . 'px; }
			';
		$css .= '
				.header-bottom,
				.header-bottom .main-navigation ul.menu > li,
				.header-bottom .helendo-language-currency,
				.header-bottom .header-account,
				.header-bottom .header-cart.hover-action,
				.header-bottom .header-search.form
					{ height: ' . intval( helendo_get_option( 'header_bottom_height' ) ) . 'px; }
			';

		// Height Sticky Height
		$css .= '
			.site-header.minimized .header-main,
			.site-header.minimized .header-main .main-navigation ul.menu > li,
			.site-header.minimized .header-main .helendo-language-currency,
			.site-header.minimized .header-main .header-account,
			.site-header.minimized .header-main .header-cart.hover-action,
			.site-header.minimized .header-main .header-search.form
				{ height: ' . intval( helendo_get_option( 'sticky_header_main_height' ) ) . 'px; }
			';
		$css .= '
			.site-header.minimized .header-bottom,
			.site-header.minimized .header-bottom .main-navigation ul.menu > li,
			.site-header.minimized .header-bottom .helendo-language-currency,
			.site-header.minimized .header-bottom .header-account,
			.site-header.minimized .header-bottom .header-cart.hover-action,
			.site-header.minimized .header-bottom .header-search.form
				{ height: ' . intval( helendo_get_option( 'sticky_header_bottom_height' ) ) . 'px; }
			';

		// Header Mobile Height
		$css .= '
			.header-mobile
				{ height: ' . intval( helendo_get_option( 'mobile_header_height' ) ) . 'px; }
			';

		// Container Width
		if ( 'helendo-container' == helendo_get_option( 'header_container' ) ) {
			$css .= '
				.site-header .helendo-container {
					margin-left: ' . intval( helendo_get_option( 'header_container_width' ) ) . 'px;
					margin-right: ' . intval( helendo_get_option( 'header_container_width' ) ) . 'px;
				}';
		}

		if ( 'helendo-container' == helendo_get_option( 'footer_container' ) ) {
			$css .= '
				.site-footer .helendo-container {
					margin-left: ' . intval( helendo_get_option( 'footer_container_width' ) ) . 'px;
					margin-right: ' . intval( helendo_get_option( 'footer_container_width' ) ) . 'px;
				}';
		}

		// Footer Height
		$css .= ' .footer-main {
					padding-top: ' . intval( helendo_get_option( 'footer_main_top_spacing' ) ) . 'px;
					padding-bottom: ' . intval( helendo_get_option( 'footer_main_bottom_spacing' ) ) . 'px;
				}';

		$css .= ' .footer-widget {
					padding-top: ' . intval( helendo_get_option( 'footer_widgets_top_spacing' ) ) . 'px;
					padding-bottom: ' . intval( helendo_get_option( 'footer_widgets_bottom_spacing' ) ) . 'px;
				}';

		/* Color Scheme */
		$color_scheme_option = helendo_get_option( 'color_scheme' );

		if ( intval( helendo_get_option( 'custom_color_scheme' ) ) ) {
			$color_scheme_option = helendo_get_option( 'custom_color' );
		}

		// Don't do anything if the default color scheme is selected.
		if ( $color_scheme_option ) {
			$css .= helendo_get_color_scheme_css( $color_scheme_option );
		}

		/* Typography */

		$css .= helendo_typography_css();

		return apply_filters( 'helendo_inline_style', $css );
	}
endif;

/**
 * Get Page Css
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'helendo_get_home_boxed_background' ) ) :
	function helendo_get_home_boxed_background() {
		$id            = get_post_meta( get_the_ID(), 'image', true );
		$bg_color      = get_post_meta( get_the_ID(), 'color', true );
		$bg_horizontal = get_post_meta( get_the_ID(), 'background_horizontal', true );
		$bg_vertical   = get_post_meta( get_the_ID(), 'background_vertical', true );
		$bg_repeat     = get_post_meta( get_the_ID(), 'background_repeat', true );
		$bg_attachment = get_post_meta( get_the_ID(), 'background_attachment', true );
		$bg_size       = get_post_meta( get_the_ID(), 'background_size', true );

		$url = wp_get_attachment_image_src( $id, 'full' );

		$class = '.page-template-template-home-boxed';

		$bg_css = ! empty( $bg_color ) ? "background-color: {$bg_color};" : '';
		$bg_css .= ! empty( $url ) ? "background-image: url( " . esc_url( $url[0] ) . " );" : '';

		$bg_css .= ! empty( $bg_repeat ) ? "background-repeat: {$bg_repeat};" : '';

		if ( ! empty( $bg_horizontal ) || ! empty( $bg_vertical ) ) {
			$bg_css .= "background-position: {$bg_horizontal} {$bg_vertical};";
		}

		$bg_css .= ! empty( $bg_attachment ) ? "background-attachment: {$bg_attachment};" : '';

		$bg_css .= ! empty( $bg_size ) ? "background-size: {$bg_size};" : '';

		if ( $bg_css ) {
			$bg_css = $class . '{' . $bg_css . '}';
			$bg_css .= '@media (max-width: 1199px) { ' . $class . ' { background: #fff; } }';
		}

		return $bg_css;
	}

endif;

if ( ! function_exists( 'helendo_get_color_scheme_css' ) ) :

/**
 * Returns CSS for the color schemes.
 *
 *
 * @param array $colors Color scheme colors.
 *
 * @return string Color scheme CSS.
 */
function helendo_get_color_scheme_css( $colors ) {
	return <<<CSS

	.helendo-section-title.style-2 .title:after,
	.helendo-latest-post.blog-grid-style-1 .hl-latest-post__header h3:after,
	.helendo-newletter__style-1 h4:after,
	.header-v3 .main-navigation ul.menu > li > a:after,
	.header-cart .counter,.header-wishlist .counter,
	.blog-grid .blog-wapper:hover .entry-title:after,
	.helendo-section-title.style-4 .title:after,
	.helendo-product-feature.style-3 .info-product .info-wrapter .title:after,
	.helendo-catalog-sorting-mobile .woocommerce-ordering ul li a.active
	{background-color: $colors}

	.helendo-icon-box.style-3.hover-2 .main-icon .header-icon .helendo-icon,
	.helendo-icon-box.style-4.hover-2 .main-icon .header-icon .helendo-icon,
	.helendo-time-countdown .timer .digits,
	.helendo_banners_grid .banner-item:hover .banner-wrapper .description,
	.helendo-countdown__style-2 .flip-clock-wrapper .flip-wrapper .inn,
	.main-navigation .menu .is-mega-menu .dropdown-submenu .menu-item-mega > a:hover,
	.helendo-social-links-widget ul li a:hover,
	.entry-meta .meta:hover,
	.entry-meta .meta:hover a,
	.entry-meta .meta.author,
	.entry-meta .meta.author a,
	.helendo-single-post-socials-share .helendo-social-share li a:hover,
	.blog-grid.blog-grid-style-2 .author-link,
	.blog-grid.blog-grid-style-2 .blog-wapper:hover .cat-links,
	.blog-grid .blog-wapper:hover .count-cmt-blog,
	.blog-grid .blog-wapper:hover .count-cmt-blog i,
	.comment-respond .logged-in-as a:hover,
	.footer-widget .helendo-social-links-widget ul li a:hover,
	.footer-main .footer-socials-menu a:hover,
	.error404 .error-404 .icon,
	.error404 .error-404 .description a,
	.menu-sidebar .helendo-language-currency ul a:hover,
	.menu-sidebar .helendo-language-currency ul li.actived a
	{color: $colors}

CSS;
}

endif;

if ( ! function_exists( 'helendo_typography_css' ) ) :
	/**
	 * Get typography CSS base on settings
	 *
	 * @since 1.1.6
	 */
	function helendo_typography_css() {
		$css        = '';

		if ( ! class_exists( 'Kirki' ) ) {
			return $css;
		}

		$properties = array(
			'font-family'    => 'font-family',
			'font-size'      => 'font-size',
			'variant'        => 'font-weight',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'color'          => 'color',
			'text-transform' => 'text-transform',
			'text-align'     => 'text-align',
		);

		$settings = array(
			'body_typo'        => 'body',
			'heading1_typo'    => 'h1',
			'heading2_typo'    => 'h2',
			'heading3_typo'    => 'h3',
			'heading4_typo'    => 'h4',
			'heading5_typo'    => 'h5',
			'heading6_typo'    => 'h6',
			'menu_typo'        => '.main-navigation a, .menu-sidebar ul.menu li a',
			'sub_menu_typo'    => '.main-navigation li li a, .menu-sidebar ul.menu .sub-menu li a',
			'footer_text_typo' => '.site-footer',
		);

		foreach ( $settings as $setting => $selector ) {
			$typography = helendo_get_option( $setting );
			$default    = (array) helendo_get_option_default( $setting );
			$style      = '';

			foreach ( $properties as $key => $property ) {
				if ( isset( $typography[$key] ) && ! empty( $typography[$key] ) ) {
					if ( isset( $default[$key] ) && strtoupper( $default[$key] ) == strtoupper( $typography[$key] ) ) {
						continue;
					}

					$value = 'font-family' == $key ? rtrim( trim( $typography[ $key ] ), ',' ) : $typography[ $key ];
					$value = 'variant' == $key ? str_replace( 'regular', '400', $value ) : $value;

					if ( $value ) {
						$style .= $property . ': ' . $value . ';';
					}
				}
			}

			if ( ! empty( $style ) ) {
				$css .= $selector . '{' . $style . '}';
			}
		}

		return $css;
	}
endif;