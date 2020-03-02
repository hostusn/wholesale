<?php
/**
 * Template Product hooks.
 *
 * @package Helendo
 */

/**
 * Class of general template.
 */
class Helendo_WooCommerce_Template_Catalog {
	/**
	 * Initialize.
	 */
	public static function init() {
		// Parse query for shop columns.
		add_action( 'parse_request', array( __CLASS__, 'parse_request' ) );

		// Change products columns.
		add_filter( 'loop_shop_columns', array( __CLASS__, 'columns' ) );

		// Need an early hook to ajaxify update mini shop cart
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'add_to_cart_fragments' ) );

		// Remove shop page title
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// remove add to cart link
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );

		// Wrap product loop content
		add_action( 'woocommerce_before_shop_loop_item', array( __CLASS__, 'open_product_inner' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item', array( __CLASS__, 'close_product_inner' ), 100 );

		add_action( 'woocommerce_shop_loop_item_title', array( __CLASS__, 'open_product_details' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item', array( __CLASS__, 'close_product_details' ), 100 );

		// Remove product link
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		// Add product thumbnail inner
		add_action( 'woocommerce_before_shop_loop_item', array( __CLASS__, 'open_product_thumbnail' ), 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( __CLASS__, 'close_product_thumbnail' ), 100 );

		// Add product link
		add_action( 'woocommerce_before_shop_loop_item', array( __CLASS__, 'product_link_open' ), 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( __CLASS__, 'product_link_close' ), 20 );

		// Add product action button
		add_action( 'woocommerce_before_shop_loop_item_title', array( __CLASS__, 'product_action_button' ), 20 );

		// Add product title link
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		add_action( 'woocommerce_shop_loop_item_title', array( __CLASS__, 'template_loop_product_title' ), 10 );

		add_filter( 'woocommerce_pagination_args', array( __CLASS__, 'loop_pagination_args' ) );

		// add product attribute
		add_action( 'woocommerce_after_shop_loop_item_title', array( __CLASS__, 'product_attribute' ), 15 );

		// Remove catalog ordering
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		// Remove shop result count
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

		// Add Shop Toolbar
		add_action( 'woocommerce_before_shop_loop', array( __CLASS__, 'get_shop_toolbar' ), 20 );
		add_action( 'woocommerce_before_shop_loop', array( __CLASS__, 'get_shop_toolbar_mobile' ), 30 );
		// Add Shop Topbar
		add_action( 'woocommerce_before_shop_loop', array( __CLASS__, 'shop_topbar' ), 30 );

		// Change label orderby option default
		add_filter( 'woocommerce_catalog_orderby', array( __CLASS__, 'helendo_catalog_get_orderby_options' ) );
		add_filter( 'woocommerce_default_catalog_orderby', array( __CLASS__, 'helendo_catalog_get_orderby_default' ) );

		// Add div before shop loop
		add_action( 'woocommerce_before_shop_loop', array( __CLASS__, 'before_shop_loop' ), 40 );

		// Add div after shop loop
		add_action( 'woocommerce_after_shop_loop', array( __CLASS__, 'after_shop_loop' ), 20 );


		// Add loading icon ajax
		add_action( 'woocommerce_before_shop_loop', array( __CLASS__, 'shop_loading' ), 60 );


		add_filter( 'posts_search', array( __CLASS__, 'product_search_sku' ), 9 );
	}

	/**
	 * Ajaxify update cart viewer
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	public static function add_to_cart_fragments( $fragments ) {
		global $woocommerce;

		if ( empty( $woocommerce ) ) {
			return $fragments;
		}

		ob_start();

		$action      = helendo_get_option( 'header_cart_action' );
		$behaviour   = helendo_get_option( 'header_cart_behaviour' );
		$m_behaviour = helendo_get_option( 'header_mobile_cart_behaviour' );

		$attr   = '';
		$m_attr = '';

		if ( $action == 'click' && $behaviour == 'panel' ) {
			$attr = 'data-target=cart-panel';
		}

		if ( $m_behaviour == 'panel' ) {
			$m_attr = 'data-mobil-target=cart-mobile-panel';
		}

		?>

        <a href="<?php echo esc_url( wc_get_cart_url() ) ?>"
           class="cart-contents" <?php echo esc_attr( $attr ); ?> <?php echo esc_attr( $m_attr ); ?>>
            <i class="icon-bag2"></i>
            <span class="counter cart-counter"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        </a>

		<?php
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Parse request to change the shop columns and products per page
	 */
	public static function parse_request() {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( isset( $_REQUEST['products_columns'] ) ) {
			wc_setcookie( 'products_columns', intval( $_REQUEST['products_columns'] ) );
		}
	}

	/**
	 * Change the shop columns.
	 *
	 * @param  int $columns The default columns.
	 *
	 * @return int
	 */
	public static function columns( $columns ) {
		if ( is_search() ) {
			if ( isset( $_POST['search_columns'] ) ) {
				$columns = intval( $_REQUEST['search_columns'] );
			}
		} else {
			if ( ! empty( $_REQUEST['products_columns'] ) ) {
				$columns = intval( $_REQUEST['products_columns'] );
			} elseif ( ! empty( $_COOKIE['products_columns'] ) ) {
				$columns = intval( $_COOKIE['products_columns'] );
			}
		}

		return $columns;
	}


	/**
	 * Wrap product content
	 * Open a div
	 *
	 * @since 1.0
	 */
	public static function open_product_inner() {
		echo '<div class="product-inner  clearfix">';
	}

	/**
	 * Wrap product content
	 * Close a div
	 *
	 * @since 1.0
	 */
	public static function close_product_inner() {
		echo '</div>';
	}

	/**
	 * Open product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return string
	 */
	public static function open_product_details() {
		echo '<div class="product-details">';
	}

	/**
	 * Close product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return string
	 */
	public static function close_product_details() {
		echo '</div>';
	}

	public static function open_product_thumbnail() {
		echo '<div class="product-thumbnail helendo-product-thumbnail">';
	}

	public static function close_product_thumbnail() {
		echo '</div>';
	}

	public static function product_link_open() {
		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
	}

	public static function product_link_close() {
		echo '</a>';
	}

	public static function product_action_button() {
		global $product;
		echo '<div class="actions-button">';

		echo '<a href="' . $product->get_permalink() . '" data-id="' . esc_attr( $product->get_id() ) . '"  class="helendo-product-quick-view button hidden-sm hidden-xs"><i class="p-icon icon-plus" title="' . esc_attr__( 'Quick View', 'helendo' ) . '" data-rel="tooltip"></i></a>';

		if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
			woocommerce_template_loop_add_to_cart();
		}

		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}


		echo '</div>';

	}

	/**
	 * Shop loading
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public static function before_shop_loop() {
		if ( ! helendo_is_catalog() ) {
			return;
		}
		echo '<div id="helendo-shop-content" class="helendo-shop-content">';
	}

	/**
	 * Shop loading
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public static function after_shop_loop() {
		if ( ! helendo_is_catalog() ) {
			return;
		}
		echo '</div>';
	}

	/**
	 * Shop loading
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public static function shop_loading() {
		if ( ! helendo_is_catalog() ) {
			return;
		}
		echo '<div class="helendo-catalog-loading">
				<span class="helendo-loader"></span>
			</div>';
	}

	/**
	 * Shop Topbar
	 */
	/**
	 * Display a top bar on top of product archive
	 *
	 * @since 1.0
	 */
	public static function shop_topbar() {
		if ( ! helendo_is_catalog() ) {
			return;
		}

		$sections = array(
			'left'  => helendo_get_option( 'shop_toolbar_left' ),
			'right' => helendo_get_option( 'shop_toolbar_right' ),
		);

		$element = array();

		foreach ( $sections as $section => $items ) {
			foreach ( $items as $item ) {
				$element[] = $item['item'];
			}
		}

		if ( ! in_array( 'filter', $element ) ) {
			return;
		}

		?>
        <div id="helendo-shop-topbar" class="widgets-area shop-topbar">
            <div class="widget-panel-header shop-topbar__header hidden-lg">
                <a href="#" class="close-canvas-panel"><span aria-hidden="true" class="icon-cross2"></span></a>
            </div>
            <div class="shop-topbar__content">
				<?php
				$sidebar = 'catalog-filter';
				if ( is_active_sidebar( $sidebar ) ) {
					dynamic_sidebar( $sidebar );
				}
				?>
            </div>

            <div class="shop-topbar__footer shop-filter-actived">
				<?php
				$link = helendo_get_page_base_url();

				if ( $_GET ) {
					printf( '<a href="%s" id="remove-filter-actived" class="remove-filter-actived"><i class="icon-cross2"></i>%s</a>', esc_url( $link ), esc_html__( 'Clear All Filter', 'helendo' ) );
				}
				?>
            </div>
        </div>

		<?php
	}

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	public static function template_loop_product_title() {
		echo '<h2 class="woocommerce-loop-product__title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
	}

	public static function loop_pagination_args( $args ) {
		if ( ! helendo_is_catalog() ) {
			return $args;
		}

		$next_text = esc_html__( 'Next Page', 'helendo' ) . '<i class="icon-chevron-right"></i>';
		if ( helendo_get_option( 'catalog_nav_type' ) == 'infinite' && ! is_search() ) {
			$next_text    = '<span id="helendo-products-loading" class="dots-loading"><span>.</span><span>.</span><span>.</span>' . esc_html__( 'Loading', 'helendo' ) . '<span>.</span><span>.</span><span>.</span></span>';
			$args['type'] = 'plain';
		}

		$args['prev_text'] = '<i class="icon-chevron-left"></i>' . esc_html__( 'Previous Page', 'helendo' );
		$args['next_text'] = $next_text;

		return $args;
	}

	/**
	 * Display product attribute
	 *
	 * @since 1.0
	 */
	public static function product_attribute() {

		$default_attribute = sanitize_title( helendo_get_option( 'product_attribute' ) );

		if ( $default_attribute == '' || $default_attribute == 'none' ) {
			return;
		}

		$default_attribute = 'pa_' . $default_attribute;

		global $product;
		$attributes         = maybe_unserialize( get_post_meta( $product->get_id(), '_product_attributes', true ) );
		$product_attributes = maybe_unserialize( get_post_meta( $product->get_id(), 'attributes_extra', true ) );

		if ( $product_attributes == 'none' ) {
			return;
		}

		if ( $product_attributes == '' ) {
			$product_attributes = $default_attribute;
		}

		$variations = self::get_variations( $product_attributes );

		if ( ! $attributes ) {
			return;
		}

		$swatches_settings = get_post_meta( $product->get_id(), 'tawcvs_swatches', true );

		foreach ( $attributes as $attribute ) {


			if ( $product->get_type() == 'variable' ) {
				if ( ! $attribute['is_variation'] ) {
					continue;
				}
			}

			$settings = '';
			if ( ! empty( $swatches_settings ) ) {
				$settings = $swatches_settings[ $attribute['name'] ];
			}


			if ( sanitize_title( $attribute['name'] ) == $product_attributes ) {

				echo '<div class="helendo-attr-swatches">';
				if ( $attribute['is_taxonomy'] ) {
					$post_terms = wp_get_post_terms( $product->get_id(), $attribute['name'] );

					$attr_type = '';

					if ( function_exists( 'TA_WCVS' ) ) {
						if ( ! empty( $settings ) ) {
							$attr_type = $settings['type'];
						} else {
							$attr = TA_WCVS()->get_tax_attribute( $attribute['name'] );
							if ( $attr ) {
								$attr_type = $attr->attribute_type;
							}
						}

					}
					$found = false;
					foreach ( $post_terms as $term ) {
						$css_class = '';


						if ( is_wp_error( $term ) || empty( $term ) ) {
							continue;
						}

						if ( ! isset( $term->slug ) ) {
							continue;
						}

						if ( $variations && isset( $variations[ $term->slug ] ) ) {
							$attachment_id = $variations[ $term->slug ];
							$attachment    = wp_get_attachment_image_src( $attachment_id, 'shop_catalog' );
							$image_srcset  = wp_get_attachment_image_srcset( $attachment_id, 'shop_catalog' );

							if ( $attachment_id == get_post_thumbnail_id() && ! $found ) {
								$css_class .= ' selected';
								$found     = true;
							}

							if ( $attachment ) {
								$css_class .= ' helendo-swatch-variation-image';
								$img_src   = $attachment[0];
								echo '' . self::swatch_html( $term, $attr_type, $img_src, $css_class, $image_srcset, $settings );
							}

						}
					}
				}
				echo '</div>';
				break;
			}
		}

	}

	/**
	 * Get variations
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_variations( $default_attribute ) {
		global $product;

		$variations = array();
		if ( $product->get_type() == 'variable' ) {
			$args = array(
				'post_parent' => $product->get_id(),
				'post_type'   => 'product_variation',
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
				'fields'      => 'ids',
				'post_status' => 'publish',
				'numberposts' => - 1,
			);

			if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
				$args['meta_query'][] = array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => '=',
				);
			}

			$thumbnail_id = get_post_thumbnail_id();

			$posts = get_posts( $args );

			foreach ( $posts as $post_id ) {
				$attachment_id = get_post_thumbnail_id( $post_id );
				$attribute     = self::get_variation_attributes( $post_id, 'attribute_' . $default_attribute );

				if ( ! $attachment_id ) {
					$attachment_id = $thumbnail_id;
				}

				if ( $attribute ) {
					$variations[ $attribute[0] ] = $attachment_id;
				}

			}

		}

		return $variations;
	}

	/**
	 * Get variation attribute
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public static function get_variation_attributes( $child_id, $attribute ) {
		global $wpdb;

		$values = array_unique(
			$wpdb->get_col(
				$wpdb->prepare(
					"SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s AND post_id IN (" . $child_id . ")",
					$attribute
				)
			)
		);

		return $values;
	}

	/**
	 * Print HTML of a single swatch
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public static function swatch_html( $term, $attr_type, $img_src, $css_class, $image_srcset, $settings ) {
		$name = $term->name;
		if ( empty( $settings ) ) {
			$settings         = array();
			$settings['type'] = $attr_type;
		}
		switch ( $attr_type ) {
			case 'color':
				$color = self::get_swatch_property( $term, $settings );
				list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );
				$html = sprintf(
					'<span class="swatch swatch-color %s" data-src="%s" data-src-set="%s" title="%s"><span class="sub-swatch" style="background-color:%s;color:%s;"></span> </span>',
					esc_attr( $css_class ),
					esc_url( $img_src ),
					esc_attr( $image_srcset ),
					esc_attr( $name ),
					esc_attr( $color ),
					"rgba($r,$g,$b,0.5)"
				);
				break;

			case 'image':
				$image = self::get_swatch_property( $term, $settings );
				$image = $image ? wp_get_attachment_image_src( $image, 'thumbnail' ) : '';
				$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
				$html  = sprintf(
					'<span class="swatch swatch-image %s" data-src="%s" data-src-set="%s" title="%s"><img src="%s" alt="%s"></span>',
					esc_attr( $css_class ),
					esc_url( $img_src ),
					esc_attr( $image_srcset ),
					esc_attr( $name ),
					esc_url( $image ),
					esc_attr( $name )
				);

				break;

			default:
				$label = self::get_swatch_property( $term, $settings );
				$label = $label ? $label : $name;
				$html  = sprintf(
					'<span class="swatch swatch-label %s" data-src="%s" data-src-set="%s" title="%s">%s</span>',
					esc_attr( $css_class ),
					esc_url( $img_src ),
					esc_attr( $image_srcset ),
					esc_attr( $name ),
					esc_html( $label )
				);
				break;

		}

		return $html;
	}

	/**
	 * Get swatch property
	 *
	 * @param string|object $term
	 * @param array $settings
	 *
	 * @return string
	 */
	public static function get_swatch_property( $term, $settings ) {
		$key   = is_object( $term ) ? $term->term_id : sanitize_title( $term );
		$value = '';
		$type  = $settings['type'];

		if ( isset ( $settings['swatches'] ) ) {
			$value = isset( $settings['swatches'][ $key ] ) && isset( $settings['swatches'][ $key ][ $type ] ) ? $settings['swatches'][ $key ][ $type ] : '';
		}

		if ( empty( $value ) && is_object( $term ) ) {
			$value = get_term_meta( $term->term_id, $type, true );
		}

		return $value;

	}

	public static function helendo_catalog_get_orderby_options( $catalog_orderby_options = '' ) {
		$catalog_orderby_options['menu_order'] = esc_html__( 'Default', 'helendo' );
		$catalog_orderby_options['popularity'] = esc_html__( 'Popularity', 'helendo' );
		$catalog_orderby_options['rating']     = esc_html__( 'Average rating', 'helendo' );
		$catalog_orderby_options['date']       = esc_html__( 'Newness', 'helendo' );
		$catalog_orderby_options['price']      = esc_html__( 'Price: low to high', 'helendo' );
		$catalog_orderby_options['price-desc'] = esc_html__( 'Price: high to low', 'helendo' );

		return $catalog_orderby_options;
	}

	public static function helendo_catalog_get_orderby_default( $orderby ) {
		$orderby = empty( $orderby ) ? 'menu_order' : $orderby;

		return $orderby;
	}

	/**
	 * Get shop toolbar
	 *
	 */
	public static function get_shop_toolbar() {
		if ( ! helendo_is_catalog() ) {
			return;
		}

		$sections = array(
			'left'  => helendo_get_option( 'shop_toolbar_left' ),
			'right' => helendo_get_option( 'shop_toolbar_right' ),
		);

		$sections = apply_filters( 'helendo_shop_toolbar_sections', $sections );

		$sections = array_filter( $sections );

		if ( empty( $sections ) ) {
			return;
		}
		?>
        <div id="helendo-shop-toolbar" class="shop-toolbar hidden-md hidden-sm hidden-xs">
			<?php foreach ( $sections as $section => $items ) : ?>
                <div class="shop-toolbar__items shop-toolbar__item--<?php echo esc_attr($section); ?>">
                    <div class="shop-toolbar__items-wrapper">
						<?php
						foreach ( $items as $item ) {
							switch ( $item['item'] ) {
								case 'cat':
									echo '<div class="shop-toolbar__item shop-toolbar__item--cat">';
									helendo_get_taxs_list( 'product_cat' );
									echo '</div>';
									break;

								case 'found':
									$found = '';

									global $wp_query;
									if ( $wp_query && isset( $wp_query->found_posts ) ) {
										if ( $wp_query->found_posts > 1 ) {
											$label = esc_html__( ' Products', 'helendo' );
										} else {
											$label = esc_html__( ' Product', 'helendo' );
										}
										$found = '<span>' . $wp_query->found_posts . ' </span>' . $label . ' ' . esc_html__( 'Found', 'helendo' );
									}

									printf( '<div class="shop-toolbar__item shop-toolbar__item--product-found">%s</div>', $found );

									break;

								case 'sort-by':
									echo '<div class="shop-toolbar__item shop-toolbar__item--sort-by">';
									echo '<i class="hidden icon-sort-amount-asc"></i>';
									woocommerce_catalog_ordering();
									echo '</div>';

									break;

								case 'result':
									echo '<div class="shop-toolbar__item shop-toolbar__item--result">';
									woocommerce_result_count();
									echo '</div>';


									break;

								case 'columns':
									echo '<div class="shop-toolbar__item shop-toolbar__item--columns">';
									self::helendo_columns_switcher();
									echo '</div>';
									break;

								case 'filter':
									printf(
										'<div class="shop-toolbar__item shop-toolbar__item--filter">
										<a id="helendo-catalog-toggle-filter" href="#">%s<i class="icon-plus"></i></a>
									</div>',
										esc_html__( 'Filter', 'helendo' ),
										esc_html__( 'Filter', 'helendo' )
									);

									break;

								default:
									do_action( 'helendo_shop_toolbar_item', $item['item'] );
									break;
							}
						}
						?>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
		<?php
	}

	/**
	 * Get shop toolbar mobile
	 */
	public static function get_shop_toolbar_mobile() {
		if ( ! helendo_is_catalog() ) {
			return;
		}

		$els = (array) helendo_get_option( 'shop_toolbar_mobile' );

		if ( empty( $els ) ) {
			return;
		}

		$count = count( $els );

		?>
        <div id="helendo-shop-toolbar-mobile"
             class="shop-toolbar-mobile items-<?php echo esc_attr( $count ) ?> hidden-lg">
            <div class="shop-toolbar__items-wrapper">
				<?php
				foreach ( $els as $el ) :
					switch ( $el ) {
						case 'sort-by':
							echo '<div class="shop-toolbar__item shop-toolbar__item--sort-by ">';
							echo '<i class="icon-sort-amount-asc"></i>';
							woocommerce_catalog_ordering();
							echo '</div>';

							break;

						case 'found':
							$found = '';

							global $wp_query;
							if ( $wp_query && isset( $wp_query->found_posts ) ) {
								if ( $wp_query->found_posts > 1 ) {
									$label = esc_html__( ' Products', 'helendo' );
								} else {
									$label = esc_html__( ' Product', 'helendo' );
								}
								$found = '<span>' . $wp_query->found_posts . ' </span>' . $label . ' ' . esc_html__( 'Found', 'helendo' );
							}

							printf( '<div class="shop-toolbar__item shop-toolbar__item--product-found">%s</div>', $found );

							break;

						case 'result':
							echo '<div class="shop-toolbar__item shop-toolbar__item--result">';
							woocommerce_result_count();
							echo '</div>';


							break;

						case 'filter':
							printf(
								'<div class="shop-toolbar__item shop-toolbar__item--filter">
									<a id="helendo-catalog-canvas-filter" href="#"><i class="icon-equalizer"></i>%s</a>
								</div>',
								esc_html__( 'Filter', 'helendo' )
							);

							break;

						default:
							do_action( 'helendo_shop_toolbar_item_mobile', $el );
							break;
					}
				endforeach;
				?>
            </div>
        </div>
		<?php
	}

	public static function helendo_columns_switcher() {
		if ( ! woocommerce_products_will_display() ) {
			return;
		}

		if ( ! empty( $_REQUEST['products_columns'] ) ) {
			$current = intval( $_REQUEST['products_columns'] );
		} elseif ( ! empty( $_COOKIE['products_columns'] ) ) {
			$current = intval( $_COOKIE['products_columns'] );
		} else {
			$current = wc_get_loop_prop( 'columns' );
		}

		$columns   = apply_filters( 'helendo_columns_switcher_options', helendo_get_option( 'shop_toolbar_columns' ) );
		$columns[] = $current;
		$columns   = array_unique( $columns );
		$columns   = array_filter( $columns );
		asort( $columns );
		?>

        <div class="columns-switcher">
			<?php
			foreach ( $columns as $column ) {
				$tag   = $column == $current ? 'span' : 'a';
				$class = 'column-selector ' . ( $column == $current ? 'active' : '' );

				if ( $column == 6 ) {
					$icon = '<span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#grid6"></use></svg></span>';
				} elseif ( $column == 5 ) {
					$icon = '<span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#grid5"></use></svg></span>';
				} elseif ( $column == 4 ) {
					$icon = '<span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#grid4"></use></svg></span>';
				} else {
					$icon = '<span class="svg-icon"><svg viewBox="0 0 20 20"><use xlink:href="#grid3"></use></svg></span>';
				}

				printf(
					'<%1$s %2$s class="%3$s" %4$s>%5$s</%1$s>',
					$tag,
					'a' == $tag ? sprintf( 'href="%s"', esc_url( add_query_arg( array( 'products_columns' => $column ) ) ) ) : '',
					$class,
					'a' == $tag ? 'rel="nofollow"' : '',
					$icon
				);
			}
			?>
        </div>

		<?php
	}

	/**
	 * Search SKU
	 *
	 * @since 1.0
	 */
	public static function product_search_sku( $where ) {
		global $pagenow, $wpdb, $wp;

		if ( ( is_admin() && 'edit.php' != $pagenow )
		     || ! is_search()
		     || ! isset( $wp->query_vars['s'] )
		     || ( isset( $wp->query_vars['post_type'] ) && 'product' != $wp->query_vars['post_type'] )
		     || ( isset( $wp->query_vars['post_type'] ) && is_array( $wp->query_vars['post_type'] ) && ! in_array( 'product', $wp->query_vars['post_type'] ) )
		) {
			return $where;
		}
		$search_ids = array();
		$terms      = explode( ',', $wp->query_vars['s'] );

		foreach ( $terms as $term ) {
			//Include the search by id if admin area.
			if ( is_admin() && is_numeric( $term ) ) {
				$search_ids[] = $term;
			}
			// search for variations with a matching sku and return the parent.

			$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $term ) ) );

			//Search for a regular product that matches the sku.
			$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';", wc_clean( $term ) ) );

			$search_ids = array_merge( $search_ids, $sku_to_id, $sku_to_parent_id );
		}

		$search_ids = array_filter( array_map( 'absint', $search_ids ) );

		if ( sizeof( $search_ids ) > 0 ) {
			$where = str_replace( ')))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . "))))", $where );
		}

		return $where;
	}
}

