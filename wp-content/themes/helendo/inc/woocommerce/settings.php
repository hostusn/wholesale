<?php
/**
 * Functions and Hooks for product meta box data
 *
 * @package Helendo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helendo_WooCommerce_Settings class.
 */
class Helendo_WooCommerce_Settings {

	/**
	 * Constructor.
	 */
	public static function init() {
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return false;
		}
		// Add form
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'product_meta_fields' ) );
		add_action( 'woocommerce_product_data_tabs', array( __CLASS__, 'product_meta_tab' ) );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'product_meta_fields_save' ) );

		add_action( 'wp_ajax_product_meta_fields', array( __CLASS__, 'instance_product_meta_fields' ) );
		add_action( 'wp_ajax_nopriv_product_meta_fields', array( __CLASS__, 'instance_product_meta_fields' ) );
	}

	/**
	 * Get product data fields
	 *
	 */
	public static function instance_product_meta_fields() {
		$post_id = $_POST['post_id'];
		ob_start();
		self::create_product_extra_fields( $post_id );
		$response = ob_get_clean();
		wp_send_json_success( $response );
		die();
	}

	/**
	 * Product data tab
	 */
	public static function product_meta_tab( $product_data_tabs ) {
		$product_data_tabs['helendo_instagram'] = array(
			'label'  => esc_html__( 'Instagram', 'helendo' ),
			'target' => 'product_helendo_instagram',
			'class'  => 'product-helendo_instagram'
		);

		$product_data_tabs['helendo_attributes_extra'] = array(
			'label'  => esc_html__( 'Extra', 'helendo' ),
			'target' => 'product_attributes_extra',
			'class'  => 'product-attributes-extra'
		);


		return $product_data_tabs;
	}

	/**
	 * Add product data fields
	 *
	 */
	public static function product_meta_fields() {
		global $post;
		self::create_product_extra_fields( $post->ID );
	}

	/**
	 * product_meta_fields_save function.
	 *
	 * @param mixed $post_id
	 */
	public static function product_meta_fields_save( $post_id ) {
		if ( isset( $_POST['product_instagram_hashtag'] ) ) {
			$woo_data = $_POST['product_instagram_hashtag'];
			update_post_meta( $post_id, 'product_instagram_hashtag', $woo_data );
		}

		if ( isset( $_POST['attributes_extra'] ) ) {
			$woo_data = $_POST['attributes_extra'];
			update_post_meta( $post_id, 'attributes_extra', $woo_data );
		}

		if ( isset( $_POST['custom_badges_text'] ) ) {
			$woo_data = $_POST['custom_badges_text'];
			update_post_meta( $post_id, 'custom_badges_text', $woo_data );
		}

		if ( isset( $_POST['_is_new'] ) ) {
			$woo_data = $_POST['_is_new'];
			update_post_meta( $post_id, '_is_new', $woo_data );
		} else {
			update_post_meta( $post_id, '_is_new', 0 );
		}

	}

	/**
	 * Create product meta fields
	 *
	 * @param $post_id
	 */
	public static function create_product_extra_fields( $post_id ) {
		echo '<div id="product_helendo_instagram" class="panel woocommerce_options_panel">';
		woocommerce_wp_text_input(
			array(
				'id'       => 'product_instagram_hashtag',
				'label'    => esc_html__( 'Hashtag', 'helendo' ),
				'desc_tip' => esc_html__( 'Enter the hashtag for which photos will be displayed. If no hashtag is entered, no photos will display.', 'helendo' ),
			)
		);
		echo '</div>';


		echo '<div id="product_attributes_extra" class="panel woocommerce_options_panel">';
		$attributes = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );
		if ( ! $attributes ) : ?>
			<div id="message" class="inline notice woocommerce-message">
				<p><?php esc_html_e( 'You need to add attributes on the Attributes tab.', 'helendo' ); ?></p>
			</div>

		<?php else :
			$options         = array();
			$options['']     = esc_html__( 'Default', 'helendo' );
			$options['none'] = esc_html__( 'None', 'helendo' );
			foreach ( $attributes as $attribute ) {
				$options[sanitize_title( $attribute['name'] )] = wc_attribute_label( $attribute['name'] );
			}
			woocommerce_wp_select(
				array(
					'id'       => 'attributes_extra',
					'label'    => esc_html__( 'Product Attribute', 'helendo' ),
					'desc_tip' => esc_html__( 'Show product attribute for each item listed under the item name.', 'helendo' ),
					'options'  => $options
				)
			);

		endif;
		woocommerce_wp_text_input(
			array(
				'id'       => 'custom_badges_text',
				'label'    => esc_html__( 'Custom Badge Text', 'helendo' ),
				'desc_tip' => esc_html__( 'Enter this optional to show your badges.', 'helendo' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'          => '_is_new',
				'label'       => esc_html__( 'New product?', 'helendo' ),
				'description' => esc_html__( 'Enable to set this product as a new product. A "New" badge will be added to this product.', 'helendo' ),
			)
		);
		echo '</div>';

	}

}