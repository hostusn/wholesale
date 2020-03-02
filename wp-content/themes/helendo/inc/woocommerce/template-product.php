<?php
/**
 * Template Product hooks.
 *
 * @package Helendo
 */

/**
 * Class of general template.
 */
class Helendo_WooCommerce_Template_Product {
	/**
	 * Initialize.
	 */

	public static function init() {

		add_action( 'woocommerce_before_single_product_summary', array( __CLASS__, 'wrapper_before_summary' ), 5 );
		add_action( 'woocommerce_after_single_product_summary', array( __CLASS__, 'wrapper_after_summary' ), 5 );

		add_filter( 'woocommerce_product_thumbnails_columns', array( __CLASS__, 'get_product_thumbnails_columns' ) );

		add_filter( 'woocommerce_single_product_image_thumbnail_html', array(
			__CLASS__,
			'get_product_image_thumbnail_html'
		), 20, 2 );

		add_action( 'woocommerce_product_thumbnails', array( __CLASS__, 'get_product_thumbnails_video' ), 30 );

		add_filter( 'woocommerce_single_get_product_gallery_classes', array(
			__CLASS__,
			'get_product_gallery_classes'
		) );

		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'get_product_stock' ) );

		add_filter( 'woocommerce_get_availability_text', array( __CLASS__, 'get_product_availability_text' ), 20, 2 );

		add_action( 'woocommerce_share', array( __CLASS__, 'get_product_share_socials' ) );

		add_filter( 'woocommerce_product_description_heading', '__return_false' );

		add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );

		if ( ! intval( helendo_get_option( 'related_products' ) ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}
		add_filter( 'woocommerce_output_related_products_args', array( __CLASS__, 'get_related_products_args' ) );

		if ( ! intval( helendo_get_option( 'upsells_products' ) ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		}
		add_filter( 'woocommerce_upsell_display_args', array( __CLASS__, 'get_upsell_products_args' ) );

		add_action( 'woocommerce_after_single_product_summary', array( __CLASS__, 'product_instagram_photos' ), 10 );

		// Change variable text
		add_filter(
			'woocommerce_dropdown_variation_attribute_options_args', array(
				__CLASS__,
				'variation_attribute_options'
			)
		);

		// Single Product Button
		add_action( 'woocommerce_after_add_to_cart_button', array( __CLASS__, 'yith_button' ), 50 );
		add_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'open_button_group' ), 10 );
		add_action( 'woocommerce_after_add_to_cart_button', array( __CLASS__, 'close_button_group' ), 100 );

		add_filter( 'yith_wcwl_positions', array( __CLASS__, 'yith_wcwl_positions' ) );

		add_filter( 'tawc_deals_l10n_text', array( __CLASS__, 'deals_l10n_text' ) );

	}

	/**
	 * Before Summary
	 *
	 * @return void
	 */
	public static function wrapper_before_summary() {
		?>
        <div class="helendo-product-detail">
		<?php

	}

	/**
	 * After Summary
	 *
	 * @return void
	 */
	public static function wrapper_after_summary() {
		?>
        </div>
		<?php

	}

	/**
	 * Product thumbnails columns
	 *
	 * @return int
	 */
	public static function get_product_thumbnails_columns() {
		return intval( helendo_get_option( 'product_thumbnail_numbers' ) );

	}

	/**
	 * Product thumbnails html
	 *
	 * @return string
	 */
	public static function get_product_image_thumbnail_html( $html, $post_thumbnail_id ) {
		global $product;

		if ( get_post_meta( $product->get_id(), 'video_position', true ) == '1' ) {
			return $html;
		}
		if ( $product->get_image_id() != $post_thumbnail_id ) {
			return $html;
		}

		return self::get_product_video() . $html;

	}

	/**
	 * Product thumbnails
	 *
	 * @return string
	 */
	public static function get_product_thumbnails_video() {
		global $product;
		$video_first = get_post_meta( $product->get_id(), 'video_position', true );
		if ( $video_first == '2' ) {
			return;
		}

		echo self::get_product_video();

	}

	/**
	 * Product thumbnails
	 *
	 * @return string
	 */
	public static function get_product_gallery_classes( $classes ) {
		global $product;
		$video_first = get_post_meta( $product->get_id(), 'video_position', true );
		$classes[]   = $video_first == '2' ? 'video-first' : '';

		return $classes;
	}

	/**
	 * Product stock
	 *
	 * @return string
	 */
	public static function get_product_stock() {
		global $product;
		if ( $product->get_type() != 'simple' ) {
			return;
		}

		echo '<div class="helendo-stock">';
		echo wc_get_stock_html( $product );
		echo '</div>';
	}

	/**
	 * Product availability text
	 *
	 * @return string
	 */
	public static function get_product_availability_text( $availability, $product ) {
		if ( ! $product->get_type() == 'simple' ) {
			return $availability;
		}

		if ( ! $product->managing_stock() && $product->is_in_stock() ) {
			$availability = esc_html__( 'In stock', 'helendo' );
		}

		return $availability;
	}

	/**
	 * Get product share socials
	 */
	public static function get_product_share_socials() {

		if ( ! function_exists( 'helendo_addons_share_link_socials' ) ) {
			return;
		}

		if ( ! intval( helendo_get_option( 'product_share_socials' ) ) ) {
			return;
		}

		$image   = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		$socials = helendo_get_option( 'product_social_icons' );
		echo '<div class="product_socials">';
		echo '<span class="label">' . esc_html__( 'Share this items :', 'helendo' ) . '</span>';
		echo helendo_addons_share_link_socials( $socials, get_the_title(), get_the_permalink(), $image );
		echo '</div>';
	}


	/**
	 * Product Video
	 *
	 * @return string
	 */
	public static function get_product_video() {
		global $product;
		$video_image  = get_post_meta( $product->get_id(), 'video_thumbnail', true );
		$video_url    = get_post_meta( $product->get_id(), 'video_url', true );
		$video_width  = 1024;
		$video_height = 768;
		$video_html   = '';
		if ( $video_image ) {
			$video_thumb = wp_get_attachment_image_src( $video_image, 'shop_thumbnail' );
			// If URL: show oEmbed HTML
			if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {

				$atts = array(
					'width'  => $video_width,
					'height' => $video_height
				);

				if ( $oembed = @wp_oembed_get( $video_url, $atts ) ) {
					$video_html = $oembed;
				} else {
					$atts = array(
						'src'    => $video_url,
						'width'  => $video_width,
						'height' => $video_height
					);

					$video_html = wp_video_shortcode( $atts );

				}
			}
			if ( $video_html ) {
				$video_thumb   = $video_thumb ? $video_thumb[0] : '';
				$video_main    = wp_get_attachment_image( $video_image, 'woocommerce_single' );
				$vid_html      = '<div class="helendo-video-wrapper">' . $video_html . '</div>';
				$video_wrapper = sprintf( '<div class="helendo-video-content">%s</div><a class="helendo-video-icon" href="#">%s</a>', $vid_html, $video_main );
				$video_html    = '<div data-thumb="' . esc_url( $video_thumb ) . '" class="woocommerce-product-gallery__image helendo-product-video">' . $video_wrapper  . '</div>';
			}
		}

		return $video_html;
	}

	/**
	 * Related products args
	 *
	 * @return array
	 */
	public static function get_related_products_args( $args ) {
		$args['posts_per_page'] = intval( helendo_get_option( 'related_products_numbers' ) );

		return $args;
	}

	/**
	 * Upsells products args
	 *
	 * @return array
	 */
	public static function get_upsell_products_args( $args ) {
		$args['posts_per_page'] = intval( helendo_get_option( 'upsells_products_numbers' ) );

		return $args;
	}

	/**
	 * Display instagram photos by hashtag
	 *
	 * @return string
	 */
	public static function product_instagram_photos() {

		if ( ! intval( helendo_get_option( 'product_instagram' ) ) ) {
			return;
		}


		global $post;
		$default_hashtag = get_post_meta( $post->ID, 'product_instagram_hashtag', true );
		if ( empty( $default_hashtag ) ) {
			return;
		}
		$numbers    = helendo_get_option( 'product_instagram_numbers' );
		$title      = helendo_get_option( 'product_instagram_title' );
		$columns    = helendo_get_option( 'product_instagram_columns' );
		$image_size = helendo_get_option( 'product_instagram_image_size' );

		$instagram_array = array();

		$instagram_by    = helendo_get_option( 'instagram_access_method' );
		if ( $instagram_by == 'user' ) {
			$instagram_array = self::instagram_get_photos_by_user( $numbers, false );
		} else {
			$instagram_array = self::instagram_get_photos_by_token( $numbers, false );
		}

		$columns         = intval( $columns );
		echo '<div id="helendo-product-instagram" class="helendo-product-instagram" data-columns="' . esc_attr( $columns ) . '" >';
		echo sprintf( '<h2>%s</h2>', wp_kses( $title, wp_kses_allowed_html( 'post' ) ) );
		echo '<ul class="products">';

		$output = array();

		if ( is_wp_error( $instagram_array ) ) {
			echo wp_kses_post( $instagram_array->get_error_message() );
		} elseif(  $instagram_array ) {
			$count = 0;
			foreach ( $instagram_array as $instagram_item ) {
				if ( ! empty( $default_hashtag ) && isset( $instagram_item['tags'] ) ) {
					if ( ! in_array( $default_hashtag, $instagram_item['tags'] ) ) {
						continue;
					}
				}

				$image_link  = $instagram_item[ $image_size ];
				$image_url   = $instagram_item['link'];
				$image_html = sprintf( '<img src="%s" alt="%s">', esc_url( $image_link ), esc_attr( '' ) );

				$output[] = '<li class="product">' . '<a class="insta-item" href="' . esc_url( $image_url ) . '" target="_blank">' . $image_html . '<i class="social_instagram"></i></a>' . '</li>' . "\n";
				$count ++;
				$numbers = intval( $numbers );
				if ( $numbers > 0 ) {
					if ( $count == $numbers ) {
						break;
					}
				}
			}

			if ( ! empty( $output ) ) {
				echo implode( '', $output );
			} else {
				esc_html_e( 'Instagram did not return any images.', 'helendo' );
			}
		} else {
			esc_html_e( 'Instagram did not return any images.', 'helendo' );
		}

		echo '</ul></div>';
	}

	/**
	 * Get instagram photo
	 *
	 * @param string $hashtag
	 * @param int $numbers
	 * @param string $title
	 * @param string $columns
	 */
public static function instagram_get_photos_by_token( $numbers, $instagram_access_token = false ) {
		global $post;

		if ( ! $instagram_access_token ) {
			$instagram_access_token = helendo_get_option( 'instagram_token' );
		}

		if ( empty( $instagram_access_token ) ) {
			return '';
		}

		$transient_prefix = 'token' . $numbers;
		$instagram        = get_transient( 'helendo_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_access_token ) );
		if ( false === $instagram ) {

			$url = 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $instagram_access_token;


			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'unable_communicate', esc_html__( 'Unable to communicate with Instagram.', 'helendo' ) );

			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_200', esc_html__( 'Instagram did not return a 200.', 'helendo' ) );

			}

			$insta_array = json_decode( $remote['body'], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'helendo' ) );
			}


			if ( isset( $insta_array['data'] ) ) {
				$results = $insta_array['data'];
			} else {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'helendo' ) );
			}

			if ( ! is_array( $results ) ) {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'helendo' ) );
			}

			foreach ( $results as $item ) {
				$instagram[] = array(
					'tags'                => $item['tags'],
					'link'                => $item['link'],
					'thumbnail'           => $item['images']['thumbnail']['url'],
					'low_resolution'      => $item['images']['low_resolution']['url'],
					'standard_resolution' => $item['images']['standard_resolution']['url'],
				);

			}

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = serialize( $instagram );
				set_transient( 'helendo_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_access_token ), $instagram, HOUR_IN_SECONDS * 2 );
			}
		}

		if ( ! empty( $instagram ) ) {
			return unserialize( $instagram );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'helendo' ) );

		}
	}

	/**
	 * Get instagram photo
	 *
	 * @param string $hashtag
	 * @param int $numbers
	 * @param string $title
	 * @param string $columns
	 */
public static function instagram_get_photos_by_user( $number, $instagram_user = false ) {
		global $post;

		if ( ! $instagram_user ) {
			$instagram_user = helendo_get_option( 'instagram_user' );
		}

		if ( empty( $instagram_user ) ) {
			return '';
		}
		$default_hashtag = get_post_meta( $post->ID, 'product_instagram_hashtag', true );
		if ( ! empty( $default_hashtag ) ) {
			$url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $default_hashtag );
			$transient_prefix = 'tag' . $number . $default_hashtag;
		} else {
			$url              = 'https://instagram.com/' . str_replace( '@', '', $instagram_user );
			$transient_prefix = 'user' . $number;
		}
		$instagram = get_transient( 'helendo_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_user ) );
		if ( false === $instagram ) {

			$remote = wp_remote_get( $url );


			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'helendo' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'helendo' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'helendo' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'helendo' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'helendo' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				$instagram[] = array(
					'link'                => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
					'thumbnail'           => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
					'low_resolution'      => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
					'standard_resolution' => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = serialize( $instagram );
				set_transient( 'helendo_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_user ), $instagram, HOUR_IN_SECONDS * 2 );
			}
		}

		if ( ! empty( $instagram ) ) {
			return unserialize( $instagram );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'helendo' ) );

		}
	}
	
	/**
	 * Change variation text
	 *
	 * @since 1.0
	 */
	public static function variation_attribute_options( $args ) {
		$attribute = $args['attribute'];
		if ( function_exists( 'wc_attribute_label' ) && $attribute ) {
			$args['show_option_none'] = esc_html__( 'Select', 'helendo' ) . ' ' . wc_attribute_label( $attribute );
		}

		return $args;
	}

	/**
	 * Wrap button group
	 * Open a div
	 *
	 * @since 1.0
	 */
	public static function open_button_group() {
		echo '<div class="single-button-wrapper">';
	}

	/**
	 * Wrap button group
	 * Close a div
	 *
	 * @since 1.0
	 */
	public static function close_button_group() {
		echo '</div>';
	}

	/**
	 * Display wishlist_button
	 *
	 * @since 1.0
	 */
	public static function yith_button() {

		if ( ! shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			return;
		}
		echo '<div class="actions-button">';
		echo '<div class="helendo-wishlist-button">';
		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		echo '</div>';
		echo '</div>';
	}

	public static function yith_wcwl_positions( $positions ) {
		$positions['add-to-cart'] = array( 'hook' => '', 'priority' => 31 );

		return $positions;
	}

	public static function deals_l10n_text( $deals ) {
		$deals = array(
			'days'    => esc_html__( 'd', 'helendo' ),
			'hours'   => esc_html__( 'h', 'helendo' ),
			'minutes' => esc_html__( 'm', 'helendo' ),
			'seconds' => esc_html__( 's', 'helendo' ),
		);

		return $deals;
    }

}
