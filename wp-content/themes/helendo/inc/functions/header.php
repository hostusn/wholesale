<?php
/**
 * Custom functions that act on header templates
 *
 * @package Helendo
 */

/**
 * Register fonts
 *
 * @since  1.0.0
 *
 * @return string
 */

if ( ! function_exists( 'helendo_fonts_url' ) ):
	function helendo_fonts_url() {
		$fonts_url = '';

		/* Translators: If there are characters in your language that are not
		* supported by Montserrat, translate this to 'off'. Do not translate
		* into your own language.
		*/
		if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'helendo' ) ) {
			$font_families[] = 'Roboto:300,300i,400,400i,500,500i,700,700i';
		}
		if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'helendo' ) ) {
			$font_families[] = 'Montserrat:300,300i,400,400i,500,500i,700,700i';
		}
		if ( 'off' !== _x( 'on', 'Libre Baskerville font: on or off', 'helendo' ) ) {
			$font_families[] = 'Libre Baskerville:300,300i,400,400i,500,500i,700,700i';
		}
		if ( 'off' !== _x( 'on', 'Mr De Haviland font: on or off', 'helendo' ) ) {
			$font_families[] = 'Mr De Haviland:300,300i,400,400i,500,500i,700,700i';
		}
		if ( 'off' !== _x( 'on', 'Prata font: on or off', 'helendo' ) ) {
			$font_families[] = 'Prata:300,300i,400,400i,500,500i,700,700i';
		}

		if ( ! empty( $font_families ) ) {
			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
	}
endif;

if ( ! function_exists( 'helendo_header_contents' ) ) :
	/**
	 * Display header items
	 */
	function helendo_header_contents( $sections, $atts = array() ) {
		if ( false == array_filter( $sections ) ) {
			return;
		}

		$classes = array();
		if ( isset( $atts['class'] ) ) {
			$classes = (array) $atts['class'];
			unset( $atts['class'] );
		}

		if ( empty( $sections['left'] ) && empty( $sections['right'] ) ) {
			unset( $sections['left'] );
			unset( $sections['right'] );
		}

		if ( ! empty( $sections['center'] ) ) {
			$classes[]    = 'has-center';
			$center_items = wp_list_pluck( $sections['center'], 'item' );

			if ( in_array( 'logo', $center_items ) ) {
				$classes[] = 'logo-center';
			}

			if ( in_array( 'menu-primary', $center_items ) ) {
				$classes[] = 'menu-center';
			}

			if ( empty( $sections['left'] ) && empty( $sections['right'] ) ) {
				$classes[] = 'no-sides';
			}
		} else {
			$classes[] = 'no-center';
			unset( $sections['center'] );

			if ( empty( $sections['left'] ) ) {
				unset( $sections['left'] );
			}

			if ( empty( $sections['right'] ) ) {
				unset( $sections['right'] );
			}
		}
		$attr = '';
		foreach ( $atts as $name => $value ) {
			$attr .= ' ' . $name . '=' . esc_attr( $value ) . '';
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo esc_attr( $attr ); ?>>
			<div class="helendo-header-container <?php echo esc_attr( apply_filters( 'helendo_header_container_class', 'container' ) ); ?>">

				<?php foreach ( $sections as $section => $items ) : ?>
					<?php
					$class      = '';
					$item_names = wp_list_pluck( $items, 'item' );

					if ( in_array( 'menu-primary', $item_names ) ) {
						$class .= ' has-menu';
					}

					if ( in_array( 'language-currency', $item_names ) ) {
						$class .= ' has-list-dropdown';
					}

					?>
					<div class="header-<?php echo esc_attr( $section ) ?>-items header-items <?php echo esc_attr( $class ) ?>">
						<?php helendo_header_items( $items ); ?>
					</div>

				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'helendo_header_items' ) ) :
	/**
	 * Display header items
	 */
	function helendo_header_items( $items ) {
		if ( empty( $items ) ) {
			return;
		}

		foreach ( $items as $item ) {
			if ( ! isset( $item['item'] ) ) {
				continue;
			}
			get_template_part( 'template-parts/header/' . $item['item'] );
		}
	}
endif;

if ( ! function_exists( 'helendo_prebuild_header' ) ) :
	/**
	 * Display pre-build header
	 *
	 * @param string $version
	 */
	function helendo_prebuild_header( $version = 'v1' ) {
		switch ( $version ) {
			case 'v1':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'search' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'language-currency' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'menu' ),
					),
				);
				$bottom_sections = array();
				break;

			case 'v2':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'menu' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'wishlist' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'account' ),
					),
				);
				$bottom_sections = array();
				break;

			case 'v3':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
					),
					'center' => array(
						array( 'item' => 'menu-primary' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'wishlist' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'account' ),
						array( 'item' => 'menu' ),
					),
				);
				$bottom_sections = array();
				break;

			case 'v4':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'phone' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'wishlist' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'account' ),
						array( 'item' => 'menu' ),
					),
				);
				$bottom_sections = array();
				break;

			case 'v5':
				$logo_sticky = intval( helendo_get_option( 'header_sticky' ) ) ? array( 'item' => 'logo' ) : array( 'item' => '' );

				$main_sections   = array(
					'center' => array(
						array( 'item' => 'logo' ),
					),
				);
				$bottom_sections = array(
					'left'   => array(
						array( 'item' => 'phone' ),
						$logo_sticky
					),
					'center' => array(
						array( 'item' => 'menu-primary' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'account' ),
					),
				);
				break;

			case 'v6':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
					),
					'center' => array(
						array( 'item' => 'menu-primary' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'menu' ),
					),
				);
				$bottom_sections = array();
				break;

			case 'v7':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'logo' ),
					),
					'center' => array(
						array( 'item' => 'menu-primary' ),
						array( 'item' => 'phone' ),
					),
					'right'  => array(
						array( 'item' => 'search' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'menu' ),
					),
				);
				$bottom_sections = array();
				break;

			case 'v8':
				$main_sections   = array(
					'left'   => array(
						array( 'item' => 'search' ),
					),
					'center' => array(
						array( 'item' => 'logo' ),
					),
					'right'  => array(
						array( 'item' => 'language-currency' ),
						array( 'item' => 'account' ),
						array( 'item' => 'cart' ),
						array( 'item' => 'menu' ),
					),
				);
				$bottom_sections = array();
				break;

			default:
				$main_sections   = array();
				$bottom_sections = array();
				break;
		}

		$classes = array( 'header-main', 'header-contents', 'hidden-md hidden-xs hidden-sm' );
		helendo_header_contents( $main_sections, array( 'class' => $classes ) );

		$border = helendo_get_option( 'header_bottom_border_top' );

		$border = $border ? 'has-border' : '';

		$classes = array( 'header-bottom', 'header-contents', 'hidden-md hidden-xs hidden-sm', $border );
		helendo_header_contents( $bottom_sections, array( 'class' => $classes ) );
	}
endif;

/**
 * Print HTML of currency switcher
 * It requires plugin WooCommerce Currency Switcher installed
 */
if ( ! function_exists( 'helendo_currency_switcher' ) ) :
	function helendo_currency_switcher( $show_desc = false ) {
		$currency_dd = '';
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;

			$key_cur = 'name';
			if ( $show_desc ) {
				$key_cur = 'description';
			}

			$currencies    = $WOOCS->get_currencies();
			$currency_list = array();
			foreach ( $currencies as $key => $currency ) {
				if ( $WOOCS->current_currency == $key ) {
					array_unshift(
						$currency_list, sprintf(
							'<li class="actived"><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s</a></li>',
							esc_attr( $currency['name'] ),
							esc_html( $currency[$key_cur] )
						)
					);
				} else {
					$currency_list[] = sprintf(
						'<li><a href="#" class="woocs_flag_view_item" data-currency="%s">%s</a></li>',
						esc_attr( $currency['name'] ),
						esc_html( $currency[$key_cur] )
					);
				}
			}

			$currency_dd = sprintf(
				'<span class="current">%s</span>' .
				'<ul>%s</ul>',
				$currencies[$WOOCS->current_currency][$key_cur],
				implode( "\n\t", $currency_list )
			);


		} elseif ( class_exists( 'Alg_WC_Currency_Switcher' ) ) {
			$function_currencies    = alg_get_enabled_currencies();
			$currencies             = get_woocommerce_currencies();
			$selected_currency      = alg_get_current_currency_code();
			$selected_currency_name = '';
			$currency_list          = array();
			$first_link             = '';
			foreach ( $function_currencies as $currency_code ) {
				if ( isset( $currencies[$currency_code] ) ) {
					$the_text = alg_format_currency_switcher( $currencies[$currency_code], $currency_code, false );
					$the_link = '<li><a id="alg_currency_' . $currency_code . '" href="' . add_query_arg( 'alg_currency', $currency_code ) . '">' . $the_text . '</a></li>';
					if ( $currency_code != $selected_currency ) {
						$currency_list[] = $the_link;
					} else {
						$first_link             = $the_link;
						$selected_currency_name = $the_text;
					}
				}
			}
			if ( '' != $first_link ) {
				$currency_list = array_merge( array( $first_link ), $currency_list );
			}

			if ( ! empty( $currency_list ) && ! empty( $selected_currency_name ) ) {
				$currency_dd = sprintf(
					'<span class="current">%s</span>' .
					'<ul>%s</ul>',
					$selected_currency_name,
					implode( "\n\t", $currency_list )
				);
			}

		}

		return $currency_dd;
	}

endif;

/**
 * Print HTML of language switcher
 * It requires plugin WPML installed
 */
if ( ! function_exists( 'helendo_language_switcher' ) ) :
	function helendo_language_switcher() {
		$language_dd = '';
		global $sitepress;
		if ( ! method_exists( $sitepress, 'get_ls_languages' ) ) {
			return $language_dd;
		}
		$languages = $sitepress->get_ls_languages();
		if ( $languages ) {
			$lang_list = array();
			$current   = '';
			foreach ( (array) $languages as $code => $language ) {
				$lang = $code;
				if ( $language['translated_name'] ) {
					$lang = $language['translated_name'];
				} elseif ( $language['tag'] ) {
					$lang = $language['tag'];
				}
				if ( ! $language['active'] ) {
					$lang_list[] = sprintf(
						'<li class="%s"><a href="%s">%s</a></li>',
						esc_attr( $code ),
						esc_url( $language['url'] ),
						$lang
					);
				} else {
					$current = $language;
					array_unshift(
						$lang_list, sprintf(
							'<li class="active %s"><a href="%s">%s</a></li>',
							esc_attr( $code ),
							esc_url( $language['url'] ),
							$lang
						)
					);
				}
			}
			$lang = esc_html( $current['language_code'] );
			if ( $current['translated_name'] ) {
				$lang = $current['translated_name'];
			} elseif ( $current['tag'] ) {
				$lang = $current['tag'];
			}
			$language_dd = sprintf(
				'<div class="lang_sel">' .
				'<ul>' .
				'<li>' .
				'<span class="current lang_sel_sel icl-en">%s</span>' .
				'<ul>%s</ul>' .
				'</li>' .
				'</ul>' .
				'</div>',
				$lang,
				implode( "\n\t", $lang_list )
			);
		}

		return $language_dd;
		?>
		<?php
	}

endif;

/**
 * Get nav menu
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'helendo_nav_menu' ) ) :
	function helendo_nav_menu( $mega_menu = true ) {
		$class   = array( 'menu' );
		$classes = implode( ' ', $class );

		$args = array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => $classes,
		);

		if ( $mega_menu == true ) {
			$args['walker'] = new Helendo_Mega_Menu_Walker();
		}

		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( $args );
		}
	}
endif;

/**
 * Mini Cart
 */
if ( ! function_exists( 'helendo_mini_cart' ) ) :
	function helendo_mini_cart() {
		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}

		if ( helendo_get_option( 'header_cart_action' ) == 'click' ) {
			return;
		}
		?>
		<div class="woocommerce mini-cart">
			<div class="widget-canvas-content">
				<div class="mini-cart__arrow"></div>
				<div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
				</div>
			</div>
		</div>
		<?php
	}

endif;

/**
 * Header Extra Classes
 */
if ( ! function_exists( 'helendo_header_extra_classes' ) ) :
	function helendo_header_extra_classes() {
		$classes          = array();
		$header_sticky_el = helendo_get_option( 'header_sticky_el' );

		if ( ! in_array( 'header_main', $header_sticky_el ) ) {
			$classes[] = 'header-main-no-sticky';
		}

		if ( ! in_array( 'header_bottom', $header_sticky_el ) ) {
			$classes[] = 'header-bottom-no-sticky';
		}

		return $classes;
	}

endif;