<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>

    <ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
                <li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<?php
					echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
						'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">
							<i class="icon-cross2"></i>
						</a>',
						esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
						esc_html__( 'Remove this item', 'helendo' ),
						esc_attr( $product_id ),
						esc_attr( $cart_item_key ),
						esc_attr( $_product->get_sku() )
					), $cart_item_key );
					?>
                    <div class="helendo-mini-cart-thumbnail">
						<?php if ( ! $_product->is_visible() ) : ?>
							<?php echo str_replace( array(
								'http:',
								'https:',
							), '', $thumbnail ); ?>
						<?php else : ?>
                            <a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php echo str_replace( array(
									'http:',
									'https:',
								), '', $thumbnail ); ?>
                            </a>
						<?php endif; ?>
                    </div>
                    <div class="helendo-mini-cart-content">
						<?php if ( ! $_product->is_visible() ) : ?>
							<?php echo wp_kses_post( $product_name ); ?>
						<?php else : ?>
                            <a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php echo wp_kses_post( $product_name ); ?>
                            </a>
						<?php endif; ?>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>

						<?php echo apply_filters(
							'woocommerce_widget_cart_item_quantity',
							'<div class="quantity">' .
							sprintf( '<span class="mini-cart-quantity"><label>%s:</label> %s</span> <span class="mini-cart-price"><label>%s:</label> %s</span>',
								esc_html__( 'Qty ', 'helendo' ),
								$cart_item['quantity'],
								esc_html__( 'Price ', 'helendo' ),
								$product_price ) .
							'</div>',
							$cart_item,
							$cart_item_key
						); ?>
                    </div>
                </li>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
    </ul>
	<p class="woocommerce-mini-cart__total total">
		<?php
		/**
		 * Woocommerce_widget_shopping_cart_total hook.
		 *
		 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
		 */
		do_action( 'woocommerce_widget_shopping_cart_total' );
		?>
	</p>
    <div class="helendo-cart-panel-footer">
        <p class="woocommerce-mini-cart__total total">
            <strong><?php esc_html_e( 'Subtotal', 'helendo' ); ?>
                :</strong> <?php echo WC()->cart->get_cart_subtotal(); ?>
        </p>

		<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

		<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

		<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
	</div>
<?php else : ?>

    <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'helendo' ); ?></p>

<?php endif; ?>
<?php do_action( 'woocommerce_after_mini_cart' ); ?>
