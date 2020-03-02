<?php
/**
 * General template hooks.
 *
 * @package Helendo
 */

/**
 * Class of general template.
 */
class Helendo_WooCommerce_Template {
	/**
	 * Initialize.
	 */
	public static function init() {
		// Disable the default WooCommerce stylesheet.
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'scripts' ), 20 );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );

		// Remove default WooCommerce wrapper.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', array( __CLASS__, 'wrapper_before' ) );
		add_action( 'woocommerce_after_main_content', array( __CLASS__, 'wrapper_after' ) );

		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		if ( intval( helendo_get_option( 'catalog_badges' ) ) ) {
			add_action( 'woocommerce_before_shop_loop_item_title', array( __CLASS__, 'product_ribbons' ) );
		}

		// Remove breadcrumb, use theme's instead
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		// Orders account
		add_action( 'woocommerce_account_dashboard', 'woocommerce_account_orders', 5 );
		add_action( 'woocommerce_account_dashboard', 'woocommerce_account_edit_address', 15 );

		// Change possition cross sell
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		if ( intval( helendo_get_option( 'cross_sells_products' ) ) ) {
			add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
		}

		// Change columns and total of cross sell
		add_filter( 'woocommerce_cross_sells_columns', array( __CLASS__, 'cross_sells_columns' ) );
		add_filter( 'woocommerce_cross_sells_total', array( __CLASS__, 'cross_sells_numbers' ) );

		if ( function_exists( 'wsl_render_auth_widget_in_wp_login_form' ) ) {
			add_action( 'woocommerce_login_form_end', 'wsl_render_auth_widget_in_wp_login_form' );
			add_action( 'woocommerce_register_form_end', 'wsl_render_auth_widget_in_wp_login_form' );
		}

		add_action( 'wp_ajax_update_wishlist_count', array( __CLASS__, 'update_wishlist_count' ) );
		add_action( 'wp_ajax_nopriv_update_wishlist_count', array( __CLASS__, 'update_wishlist_count' ) );
	}

	/**
	 * WooCommerce specific scripts & stylesheets.
	 *
	 * @return void
	 */
	public static function scripts() {
		wp_enqueue_style( 'helendo-woocommerce', get_template_directory_uri() . '/woocommerce.css' );
		if ( wp_script_is( 'wc-add-to-cart-variation', 'registered' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		wp_add_inline_style( 'helendo-woocommerce', self::get_inline_style() );

	}


	/**
	 * Add 'woocommerce-active' class to the body tag.
	 *
	 * @param  array $classes CSS classes applied to the body tag.
	 *
	 * @return array $classes modified to include 'woocommerce-active' class.
	 */
	public static function body_class( $classes ) {
		$classes[] = 'woocommerce-active';

		// Adds a class of product layout.
		if ( is_product() ) {
			$product_layout = helendo_get_option( 'product_layout' );
			$classes[]      = 'product-' . $product_layout;
		}


		return $classes;
	}

	/**
	 * Before Content.
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 */
	public static function wrapper_before() {
		?>
		<div id="primary" class="content-area <?php helendo_content_columns(); ?>">
		<main id="main" class="site-main">
		<?php
	}

	/**
	 * Ajaxify update count wishlist
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public static function update_wishlist_count() {
		if ( ! function_exists( 'YITH_WCWL' ) ) {
			return;
		}

		wp_send_json( YITH_WCWL()->count_products() );

	}

	/**
	 * After Content.
	 * Closes the wrapping divs.
	 */
	public static
	function wrapper_after() {
		?>
		</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}

	public
	static function product_ribbons() {
		global $product;

		$badges = helendo_get_option( 'badges' );

		if ( empty( $badges ) ) {
			return;
		}
		$output        = array();
		$custom_badges = maybe_unserialize( get_post_meta( $product->get_id(), 'custom_badges_text', true ) );
		if ( $custom_badges ) {

			$output[] = '<span class="custom ribbon">' . esc_html( $custom_badges ) . '</span>';

		} else {
			if ( ! $product->is_in_stock() && in_array( 'outofstock', $badges ) ) {
				$outofstock = helendo_get_option( 'outofstock_text' );
				if ( ! $outofstock ) {
					$outofstock = esc_html__( 'Out Of Stock', 'helendo' );
				}
				$output[] = '<span class="out-of-stock ribbon">' . esc_html( $outofstock ) . '</span>';
			} elseif ( $product->is_on_sale() && in_array( 'sale', $badges ) ) {
				$percentage = 0;
				$save       = 0;
				if ( $product->get_type() == 'variable' ) {
					$available_variations = $product->get_available_variations();
					$percentage           = 0;
					$save                 = 0;

					for ( $i = 0; $i < count( $available_variations ); $i ++ ) {
						$variation_id     = $available_variations[$i]['variation_id'];
						$variable_product = new WC_Product_Variation( $variation_id );
						$regular_price    = $variable_product->get_regular_price();
						$sales_price      = $variable_product->get_sale_price();
						if ( empty( $sales_price ) ) {
							continue;
						}
						$max_percentage = $regular_price ? round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) ) : 0;
						$max_save       = $regular_price ? $regular_price - $sales_price : 0;

						if ( $percentage < $max_percentage ) {
							$percentage = $max_percentage;
						}

						if ( $save < $max_save ) {
							$save = $max_save;
						}
					}
				} elseif ( $product->get_type() == 'simple' || $product->get_type() == 'external' ) {
					$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
					$save       = $product->get_regular_price() - $product->get_sale_price();
				}
				if ( $percentage ) {
					$output[] = '<span class="onsale ribbon"><span class="sep">-</span>' . $percentage . '%' . '</span>';
				}

			} elseif ( $product->is_featured() && in_array( 'hot', $badges ) ) {
				$hot = helendo_get_option( 'hot_text' );
				if ( ! $hot ) {
					$hot = esc_html__( 'Hot', 'helendo' );
				}
				$output[] = '<span class="featured ribbon">' . esc_html( $hot ) . '</span>';
			} elseif ( ( time() - ( 60 * 60 * 24 * helendo_get_option( 'product_newness' ) ) ) < strtotime( get_the_time( 'Y-m-d' ) ) && in_array( 'new', $badges ) ||
				get_post_meta( $product->get_id(), '_is_new', true ) == 'yes'
			) {
				$new = helendo_get_option( 'new_text' );
				if ( ! $new ) {
					$new = esc_html__( 'New', 'helendo' );
				}
				$output[] = '<span class="newness ribbon">' . esc_html( $new ) . '</span>';
			}
		}

		if ( $output ) {
			echo sprintf( '<span class="ribbons">%s</span>', implode( '', $output ) );
		}
	}

	/**
	 * Change number of columns when display cross sells products
	 *
	 * @param  int $cross_columns
	 *
	 * @return int
	 */
	public static function cross_sells_columns( $cross_columns ) {
		return intval( helendo_get_option( 'cross_sells_products_columns' ) );
	}

	/**
	 * Change number of columns when display cross sells products
	 *
	 * @param  int $cross_numbers
	 *
	 * @return int
	 */
	public static function cross_sells_numbers( $cross_numbers ) {
		return intval( helendo_get_option( 'cross_sells_products_numbers' ) );
	}

	/**
	 * Get inline style
	 */
	public static function get_inline_style() {
		$inline_css = '';
		$hot_color  = helendo_get_option( 'hot_color' );
		if ( ! empty( $hot_color ) ) {
			$inline_css .= '.woocommerce .ribbons .ribbon.featured {background-color:' . $hot_color . '}';
		}

		$outofstock_color = helendo_get_option( 'outofstock_color' );
		if ( ! empty( $outofstock_color ) ) {
			$inline_css .= '.woocommerce .ribbons .ribbon.out-of-stock {background-color:' . $outofstock_color . '}';
		}

		$new_color = helendo_get_option( 'new_color' );
		if ( ! empty( $new_color ) ) {
			$inline_css .= '.woocommerce .ribbons .ribbon {background-color:' . $new_color . '}';
		}

		$sale_color = helendo_get_option( 'sale_color' );
		if ( ! empty( $sale_color ) ) {
			$inline_css .= '.woocommerce .ribbons .ribbon.onsale {background-color:' . $sale_color . '}';
		}

		return $inline_css;
	}
}
