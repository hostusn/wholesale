<?php

/**
 * Define theme shortcodes
 *
 * @package Helendo
 */
class Helendo_Shortcodes {

	/**
	 * Store variables for js
	 *
	 * @var array
	 */
	public $l10n = array();

	/**
	 * Store variables for maps
	 *
	 * @var array
	 */
	public $maps = array();
	public $api_key = '';

	/**
	 * Check if WooCommerce plugin is actived or not
	 *
	 * @var bool
	 */
	private $wc_actived = false;

	/**
	 * Construction
	 *
	 * @return Helendo_Shortcodes
	 */
	function __construct() {
		$this->wc_actived = function_exists( 'is_woocommerce' );

		$shortcodes = array(
			'helendo_empty_space',
			'helendo_video_banner',
			'helendo_icon_box',
			'helendo_section_title',
			'helendo_progress_bar',
			'helendo_images_modern',
			'helendo_contact_form_7',
			'helendo_socials',
			'helendo_gmap',
			'helendo_countdown',
			'helendo_product_feature',
			'helendo_about_us',
			'helendo_banners_grid',
			'helendo_banner_large',
			'helendo_image_carousel',
			'helendo_banners_carousel',
			'helendo_latest_post',
			'helendo_button',
			'helendo_product',
			'helendo_newletter',
			'helendo_products_grid',
			'helendo_products_carousel',
			'helendo_instagram',
		);


		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, $shortcode ) );
		}

		add_action( 'wp_footer', array( $this, 'footer' ) );

		add_action( 'wp_ajax_nopriv_helendo_load_products', array( $this, 'ajax_load_products' ) );
		add_action( 'wp_ajax_helendo_load_products', array( $this, 'ajax_load_products' ) );
	}

	public function footer() {
		// Load Google maps only when needed
		if ( isset( $this->l10n['map'] ) ) {
			echo '<script>if ( typeof google !== "object" || typeof google.maps !== "object" )
				document.write(\'<script src="//maps.google.com/maps/api/js?sensor=false&key=' . $this->api_key . '"><\/script>\')</script>';
		}

		wp_enqueue_script(
			'shortcodes', HELENDO_ADDONS_URL . '/assets/js/frontend.js', array(
			'jquery',
			'imagesloaded',
			'wp-util',
		), '20171018', true
		);

		$this->l10n['days']    = esc_html__( 'days', 'helendo' );
		$this->l10n['hours']   = esc_html__( 'hours', 'helendo' );
		$this->l10n['minutes'] = esc_html__( 'minutes', 'helendo' );
		$this->l10n['seconds'] = esc_html__( 'seconds', 'helendo' );

		wp_localize_script( 'shortcodes', 'helendoShortCode', $this->l10n );
	}

	/**
	 * Ajax load products
	 */
	function ajax_load_products() {
		check_ajax_referer( 'helendo_get_products', 'nonce' );

		$attr = $_POST['attr'];

		$attr['load_more'] = isset( $_POST['load_more'] ) ? $_POST['load_more'] : true;
		$attr['page']      = isset( $_POST['page'] ) ? $_POST['page'] : 1;

		$type = isset( $_POST['type'] ) ? $_POST['type'] : '';

		$products = $this->get_wc_products( $attr, $type );

		wp_send_json_success( $products );
	}

	/**
	 * @param string $atts
	 *
	 * @return string
	 */
	protected function helendo_font_size( $atts ) {
		$atts = preg_replace( '/\s+/', '', $atts );

		$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
		// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
		$regexr   = preg_match( $pattern, $atts, $matches );
		$value    = isset( $matches[1] ) ? (float) $matches[1] : (float) $atts;
		$unit     = isset( $matches[2] ) ? $matches[2] : 'px';
		$fontSize = $value . $unit;

		return $fontSize;
	}

	/**
	 * @param $atts
	 * @param $type
	 *
	 * @return string
	 */
	function get_wc_products( $atts, $type = 'products' ) {
		if ( ! class_exists( 'WC_Shortcode_Products' ) ) {
			return '';
		}

		$attr = array(
			'limit'    => intval( $atts['limit'] ),
			'columns'  => intval( $atts['columns'] ),
			'page'     => $atts['page'],
			'category' => $atts['category'],
			'paginate' => true,
			'orderby'  => $atts['orderby'],
			'order'    => $atts['order'],
		);

		$current_page = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] );

		if ( isset( $attr['page'] ) ) {
			$_GET['product-page'] = $attr['page'];
		}

		$shortcode = new WC_Shortcode_Products( $attr, $type );

		$args = $shortcode->get_query_args();
		$html = $shortcode->get_content();

		$products   = new WP_Query( $args );
		$total_page = $products->max_num_pages;

		if ( isset( $atts['load_more'] ) && $atts['load_more'] && $total_page > 1 ) {
			if ( $attr['page'] < $total_page ) {
				$html .= sprintf(
					'<div class="load-more text-center">
						<a href="#" class="ajax-load-products" data-page="%s" data-type="%s" data-attr="%s" data-nonce="%s" rel="nofollow">
							<span class="button-text">%s</span>
							<span class="loading-icon">
								<span class="loading-text">%s</span>
								<span class="icon_loading helendo-spin su-icon"></span>
							</span>
						</a>
					</div>',
					esc_attr( $attr['page'] + 1 ),
					esc_attr( $type ),
					esc_attr( json_encode( $attr ) ),
					esc_attr( wp_create_nonce( 'helendo_get_products' ) ),
					esc_html__( 'Discover More', 'helendo' ),
					esc_html__( 'Loading', 'helendo' )
				);
			}
		}

		if ( isset( $attr['page'] ) ) {
			$_GET['product-page'] = $current_page;
		}

		return $html;
	}

	/**
	 * Get empty space
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function helendo_empty_space( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'height'        => '',
				'height_mobile' => '',
				'height_tablet' => '',
				'height_medium' => '',
				'bg_color'      => '',
				'el_class'      => '',
			), $atts
		);

		$css_class = array(
			'helendo-empty-space',
			$atts['el_class'],
		);

		$style = '';

		if ( $atts['bg_color'] ) {
			$style = 'background-color:' . $atts['bg_color'] . ';';
		}

		$height = $atts['height'] ? (float) $atts['height'] : 0;

		if ( ! empty( $atts['height_medium'] ) || $atts['height_medium'] == '0' ) {
			$height_medium = (float) $atts['height_medium'];
		} else {
			$height_medium = $height;
		}

		if ( ! empty( $atts['height_tablet'] ) || $atts['height_tablet'] == '0' ) {
			$height_tablet = (float) $atts['height_tablet'];
		} else {
			$height_tablet = $height_medium;
		}

		if ( ! empty( $atts['height_mobile'] ) || $atts['height_mobile'] == '0' ) {
			$height_mobile = (float) $atts['height_mobile'];
		} else {
			$height_mobile = $height_tablet;
		}

		$inline_css        = $height >= 0.0 ? ' style="height: ' . esc_attr( $height ) . 'px"' : '';
		$inline_css_medium = $height_medium >= 0.0 ? ' style="height: ' . esc_attr( $height_medium ) . 'px"' : '';
		$inline_css_tablet = $height_tablet >= 0.0 ? ' style="height: ' . esc_attr( $height_tablet ) . 'px"' : '';
		$inline_css_mobile = $height_mobile >= 0.0 ? ' style="height: ' . esc_attr( $height_mobile ) . 'px"' : '';

		return sprintf(
			'<div class="%s" style="%s">' .
			'<div class="helendo_empty_space_lg hidden-md hidden-sm hidden-xs" %s></div>' .
			'<div class="helendo_empty_space_md hidden-lg hidden-sm hidden-xs" %s></div>' .
			'<div class="helendo_empty_space_sm hidden-lg hidden-md hidden-xs" %s></div>' .
			'<div class="helendo_empty_space_xs hidden-lg hidden-md hidden-sm" %s></div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$style,
			$inline_css,
			$inline_css_medium,
			$inline_css_tablet,
			$inline_css_mobile
		);
	}

	/**
	 * Get limited words from given string.
	 * Strips all tags and shortcodes from string.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $num_words The maximum number of words
	 * @param string $more More link.
	 *
	 * @return string Limited content.
	 */
	protected function helendo_addons_content_limit( $content, $num_words, $more = "&hellip;" ) {
		// Strip tags and shortcodes so the content truncation count is done correctly
		$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'helendo_content_limit_allowed_tags', '<script>,<style>' ) );

		// Remove inline styles / scripts
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		// Truncate $content to $max_char
		$content = wp_trim_words( $content, $num_words );

		if ( $more ) {
			return sprintf(
				'<div class="excerpt">%s <a href="%s" class="more-link" title="%s">%s</a></div>',
				$content,
				get_permalink(),
				sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'helendo' ), the_title_attribute( 'echo=0' ) ),
				esc_html( $more )
			);
		}

		return sprintf( '<div class="excerpt">%s</div>', $content );
	}

	/**
	 * Get vc link
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	protected function get_vc_link( $atts, $content ) {
		$attributes = array(
			'class' => 'helendo-link',
		);

		$link = vc_build_link( $atts['link'] );

		if ( ! empty( $link['url'] ) ) {
			$attributes['href'] = $link['url'];
		}

		if ( ! $content ) {
			$content             = $link['title'];
			$attributes['title'] = $content;
		}

		if ( ! empty( $link['target'] ) ) {
			$attributes['target'] = $link['target'];
		}

		if ( ! empty( $link['rel'] ) ) {
			$attributes['rel'] = $link['rel'];
		}

		$attr = array();

		foreach ( $attributes as $name => $v ) {
			$attr[] = $name . '="' . esc_attr( $v ) . '"';
		}

		$button = sprintf(
			'<%1$s %2$s>%3$s</%1$s>',
			empty( $attributes['href'] ) ? 'span' : 'a',
			implode( ' ', $attr ),
			$content
		);

		return $button;
	}

	/**
	 * @param        $image
	 * @param string $size
	 *
	 * @return string
	 */

	protected function get_vc_image( $image, $size = 'thumbnail' ) {
		$image_src = '';
		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$image = wpb_getImageBySize(
				array(
					'attach_id'  => $image,
					'thumb_size' => $size,
				)
			);

			if ( $image['thumbnail'] ) {
				$image_src = $image['thumbnail'];
			} elseif ( $image['p_img_large'] ) {
				$image_src = sprintf( '<img src="%s">', esc_url( $image['p_img_large'][0] ) );
			}

		}

		if ( empty( $image_src ) ) {
			$image_src = wp_get_attachment_image( $image, $size );
		}

		return $image_src;
	}

	// Section title
	function helendo_section_title( $atts, $content ) {
		$atts          = shortcode_atts(
			array(
				'style'           => '1',
				'title'           => '',
				'emphasize_words' => '',
				'alignment'       => 'left',
				'font_size'       => '',
				'text_color'      => '',
				'content_color'   => '',
				'line_height'     => '',
				'font_weight'     => '',
				'read_more'       => '',
				'link'            => '',
				'text_read_more'  => '',
				'el_class'        => '',
			), $atts
		);
		$style         = $atts['style'];
		$alignment     = $style == 4 ? 'center' : $atts['alignment'];
		$size          = $this->helendo_font_size( $atts['font_size'] );
		$font_size     = ! empty( $atts['font_size'] ) ? sprintf( 'font-size:%s;', $size ) : '';
		$font_weight   = ! empty( $atts['font_weight'] ) ? sprintf( 'font-weight:%s;', $atts['font_weight'] ) : '';
		$line_height   = ! empty( $atts['line_height'] ) ? sprintf( 'line-height:%s;', $this->helendo_font_size( $atts['line_height'] ) ) : '';
		$text_color    = ! empty( $atts['text_color'] ) ? sprintf( 'color:%s;', $atts['text_color'] ) : '';
		$content_color = ! empty( $atts['content_color'] ) ? sprintf( 'color:%s;', $atts['content_color'] ) : '';
		$text_style    = ! empty( $font_size || $text_color || $line_height || $font_weight ) ? sprintf( 'style="%s %s %s %s"', $font_size, $text_color, $line_height, $font_weight ) : '';
		if ( ! empty( $atts['emphasize_words'] ) ) {
			$title = str_replace( $atts['emphasize_words'], sprintf( '<span>%s</span>', $atts['emphasize_words'] ), $atts['title'] );
			$title = sprintf( '<h3 class="title" %s>%s</h3>', $text_style, $title );
		} else {
			$title = ! empty( $atts['title'] ) ? sprintf( '<h3 class="title" %s>%s</h3>', $text_style, $atts['title'] ) : '';
		}
		$readmore = '';
		if ( ! empty( $atts['read_more'] ) ) :
			$text_read_more = ! empty( $atts['text_read_more'] ) ? $atts['text_read_more'] : esc_html__( 'Read more', 'helendo' );
			$link           = ! empty( $atts['link'] ) ? vc_build_link( $atts['link'] )['url'] : '#';
			$readmore       = sprintf( '<a class="read-more" href="%s">%s</a>', $link, $text_read_more );
		endif;

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$descr = ! empty( $content ) ? sprintf( '<div class="description" %s>%s</div>', $content_color, $content ) : '';

		$class = array(
			'helendo-section-title',
			sprintf( 'text-%s', $alignment ),
			sprintf( 'style-%s', $style ),
			$atts['el_class']
		);

		return sprintf(
			'<div class="%s">%s %s %s</div>',
			implode( ' ', $class ), $title, $descr, $readmore
		);
	}

	/**
	 * Get image box carousel
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function helendo_video_banner( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'video'       => '',
				'min_height'  => '500',
				'image'       => '',
				'image_size'  => '',
				'video_style' => 'full',
				'btn_text'    => esc_html__( 'Watch Project Video', 'helendo' ),
				'el_class'    => '',

			), $atts
		);

		if ( empty( $atts['video'] ) ) {
			return '';
		}

		$css_class = array(
			'mf-video-banner',
			$atts['el_class'],
		);

		$min_height  = intval( $atts['min_height'] );
		$video_html  = $src = $btn = '';
		$style       = array();
		$video_url   = $atts['video'];
		$video_w     = '1024';
		$video_h     = '768';
		$video_style = $atts['video_style'];

		if ( $min_height ) {
			$style[] = 'min-height:' . $min_height . 'px;';
		}

		if ( $atts['image'] ) {
			$image = wp_get_attachment_image_src( $atts['image'], 'full' );
			if ( $image ) {
				$src = $image[0];
			}
			$style[] = 'background-image:url(' . $src . ');';
		}

		if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
			$args = array(
				'width'  => $video_w,
				'height' => $video_h
			);
			if ( $oembed = @wp_oembed_get( $video_url, $args ) ) {
				$video_html = $oembed;
			}
			if ( $video_html ) {
				$video_html = sprintf( '<div class="mf-wrapper"><div class="mf-video-wrapper">%s</div></div>', $video_html );
			}
		}

		return sprintf(
			'<div class="helendo-video-banner"><div class="video-wrapter helendo-video-%s %s" style="%s">
				<div class="mf-video-content"><a href="#" data-href="%s" class="photoswipe"><span class="linear-ic-play"></span></a></div>
			</div></div> ',
			$video_style,
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( implode( ' ', $style ) ),
			esc_attr( $video_html )
		);
	}

	/**
	 * Get icon box
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function helendo_icon_box( $atts, $content ) {
		$atts      = shortcode_atts(
			array(
				'style'            => '1',
				'align'            => 'left',
				'theme'            => 'light',
				'title'            => '',
				'text_color'       => '',
				'icon_type'        => 'fa_font',
				'icon_fontawesome' => '',
				'linearicons'      => '',
				'image'            => '',
				'image_size'       => '',
				'icon_color'       => '',
				'icon_size'        => '',
				'readmore'         => '',
				'padding'          => '',
				'background_image' => '',
				'background_color' => '',
				'ic_hover'         => '1',
				'row'              => '1',
				'background_size'  => 'auto',
				'el_class'         => '',
			), $atts
		);
		$style     = $atts['style'];
		$icon_type = $atts['icon_type'];

		$icon_color          = $atts['icon_color'];
		$image_ids           = $atts['image'];
		$icon_size           = ! empty( $atts['icon_size'] ) ? $this->helendo_font_size( $atts['icon_size'] ) : '';
		$style_ic_color      = ! empty( $icon_color ) ? sprintf( 'color: %s;', $icon_color ) : '';
		$icon_size           = ! empty( $icon_size ) ? sprintf( 'font-size: %s;', $icon_size ) : '';
		$style_ic            = ! empty( $icon_color ) || ! empty( $icon_size ) ? sprintf( 'style="%s"', $style_ic_color . $icon_size ) : '';
		$position            = ! empty( $atts['align'] ) ? sprintf( 'text-%s', $atts['align'] ) : '';
		$padding             = ! empty( $atts['padding'] ) ? sprintf( 'padding: %s;', $atts['padding'] ) : '';
		$el_class            = ! empty( $atts['el_class'] ) ? sprintf( '%s', $atts['el_class'] ) : '';
		$theme               = ! empty( $atts['theme'] ) ? sprintf( '%s', $atts['theme'] ) : '';
		$background_image_id = ! empty( $atts['background_image'] ) ? $atts['background_image'] : '';
		$background_color    = ! empty( $atts['background_color'] ) ? sprintf( 'style="background-color: %s;"', $atts['background_color'] ) : '';
		$background_size     = ! empty( $atts['background_size'] ) ? sprintf( 'background-size: %s;', $atts['background_size'] ) : '';

		$ic_hover = '';
		if ( $style == 3 ) :
			$ic_hover = sprintf( 'hover-%s', $atts['ic_hover'] );
		endif;

		$background_image_url = ! empty( $background_image_id ) ? wp_get_attachment_image_src( $background_image_id, 'full' )[0] : '';
		$image_background     = ! empty( $background_image_url ) ? sprintf( 'background-image:url(%s);', $background_image_url ) : '';
		$style_sheet          = ! empty( $padding ) || ! empty( $background_image_url ) ? sprintf( 'style="%s"', $padding . $image_background . $background_size ) : '';

		switch ( $icon_type ) {
			case 'fa_font':
				$value = $atts['icon_fontawesome'];
				$icon  = sprintf( "<span class='%s helendo-icon' %s></span>", $value, $style_ic );
				break;

			case 'linearicons':
				$icon = sprintf( "<span class='helendo-icon' %s><i class='%s'></i></span>", $style_ic, $atts['linearicons'] );
				break;

			case 'image':
				$image_thumb = $this->get_vc_image( $image_ids, $atts['image_size'] );
				$icon        = "<div class='helendo-icon'>$image_thumb</div>";
				break;
			default:
				$icon = "";
		}

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$entry_icon = ! empty( $icon ) ? sprintf( '<div class="header-icon">%s</div>', $icon ) : '';
		$link       = vc_build_link( $atts['title'] );
		$href       = ! empty( $link['url'] ) ? sprintf( ' href="%s"', $link['url'] ) : '';
		$title      = ! empty( $link['title'] ) ? $link['title'] : '';
		$tag        = ! empty( $link['url'] ) ? 'a' : 'span';

		$entry_title = ! empty( $title ) ? sprintf( '<div class="iconbox-title"><h3 class="title"><%s%s>%s</%s></h3></div>', $tag, $href, $title, $tag ) : '';
		$descr       = ! empty( $content ) ? sprintf( '<div class="iconbox-desc">%s</div>', $content ) : '';

		$class      = array(
			'helendo-icon-box',
			sprintf( 'style-%s', $style ),
			esc_attr__( $position, 'helendo' ),
			sprintf( ' helendo-style-%s', $theme ),
			esc_attr__( $ic_hover, 'helendo' ),
			sprintf( 'row-%s', $atts['row'] ),
			esc_attr__( $el_class, 'helendo' ),
		);
		$background = '';
		if ( $style != 3 ) {
			$background = sprintf( '<div class="background-image" %s></div><div class="backgound-color" %s></div>', $style_sheet, $background_color );
		}
		if ( $style == 3 ) {
			$main = sprintf( '<div class="main-icon">%s<div class="iconbox-content">%s</div></div>', $entry_icon, $entry_title . $descr );
		} else {
			$main = sprintf( '<div class="main-icon">%s%s</div>', $entry_icon, $entry_title . $descr );
		}

		return sprintf(
			'<div class="%s" >%s%s</div>',
			implode( ' ', $class ), $background, $main
		);
	}

	/**
	 * Get progress bar
	 *
	 * @since  1.0
	 *
	 * @return string
	 */

	function helendo_progress_bar( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'el_class' => '',
				'setting'  => '',
			), $atts
		);

		$infor   = vc_param_group_parse_atts( $atts['setting'] );
		$outputs = array();

		if ( ! empty( $infor ) ) {
			foreach ( $infor as $key => $value ) {
				$number = ! empty( $value['number'] ) ? esc_attr__( $value['number'] . '%' ) : '';
				$title  = ! empty( $value['title'] ) ? esc_attr__( $value['title'] ) : '';

				$outputs[] = sprintf(
					'<div class="progress-bar">
						<div class="text">
							<span class="title">%s</span>
							<span class="number">%s</span>
						</div>
						<div class="progress">
							<span class="line-progress" style="width: %s;"></span>
						</div>
					</div>',
					$title, $number, $number
				);
			}
		}

		return sprintf(
			'<div class="helendo-progressbar %s">%s</div>',
			$atts['el_class'], implode( '', $outputs )
		);
	}

	/**
	 * Get progress bar
	 *
	 * @since  1.0
	 *
	 * @return string
	 */

	function helendo_images_modern( $atts, $content ) {
		$atts      = shortcode_atts(
			array(
				'el_class' => '',
				'images'   => '',
			), $atts
		);
		$image_ids = explode( ',', $atts['images'] );

		$output = array();

		$i = 1;
		foreach ( $image_ids as $image_id ) {

			$thumb_size = $img_class = '';

			if ( $i % 5 == 1 ) {
				$thumb_size = '780x770';
				$img_class  = 'image-first item-large';
			} elseif ( $i % 5 == 2 || $i % 5 == 3 || $i % 5 == 4 ) {
				$thumb_size = '380x380';
				$img_class  = 'image-second item-small';
			} elseif ( $i % 5 == 0 ) {
				$thumb_size = '780x380';
				$img_class  = 'image-last item-medium';
			}

			$imgs     = $this->get_vc_image( $image_id, $thumb_size );
			$output[] = sprintf( '<div class="images item-modern %s"><div class="item-wrapper">%s</div></div>', $img_class, $imgs );
			$i ++;
		}

		return sprintf(
			'<div class="helendo-images-masonry clearfix">
				<div class="images-sizer"></div>
				<div class="gutter-sizer"></div>
				%s
			</div>', implode( '', $output )
		);
	}

	// Contact form 7
	function helendo_contact_form_7( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'          => '',
				'style'          => '1',
				'color'          => 'dark',
				'style_sub'      => '1',
				'form'           => '',
				'form_bg'        => '',
				'padding_top'    => '',
				'padding_right'  => '',
				'padding_bottom' => '',
				'padding_left'   => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'helendo-contact-form-7',
			'form-' . $atts['color'],
			'style-' . $atts['style'],
			'border-' . $atts['style_sub'],
			$atts['el_class']
		);

		$title = $atts['title'] ? sprintf( '<h3>%s</h3>', $atts['title'] ) : '';

		$style = array();

		$p_top    = intval( $atts['padding_top'] );
		$p_right  = intval( $atts['padding_right'] );
		$p_bottom = intval( $atts['padding_bottom'] );
		$p_left   = intval( $atts['padding_left'] );

		if ( $atts['form_bg'] ) {
			$style[] = 'background-color:' . $atts['form_bg'] . ';';
		}

		if ( $atts['padding_top'] ) {
			$style[] = 'padding-top: ' . $p_top . 'px;';
		}

		if ( $atts['padding_right'] ) {
			$style[] = 'padding-right: ' . $p_right . 'px;';
		}

		if ( $atts['padding_bottom'] ) {
			$style[] = 'padding-bottom: ' . $p_bottom . 'px;';
		}

		if ( $atts['padding_left'] ) {
			$style[] = 'padding-left: ' . $p_left . 'px;';
		}

		return sprintf(
			'<div class="%s" style="%s">%s%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $style ),
			$title,
			do_shortcode( '[contact-form-7 id="' . esc_attr( $atts['form'] ) . '" title=" ' . get_the_title( $atts['form'] ) . ' "]' )
		);
	}

	function helendo_socials( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'       => esc_html__( 'Follow us :', 'helendo' ),
				'socials'     => '',
				'title_color' => '',
				'el_class'    => '',
			), $atts
		);

		$css_class = array(
			'helendo-socials text-left',
			$atts['el_class'],
		);

		$socials       = vc_param_group_parse_atts( $atts['socials'] );
		$social_output = array();
		if ( ! empty( $socials ) ) {
			foreach ( $socials as $name => $value ) {
				$icon_type = $value['icon_type'];

				switch ( $icon_type ) {
					case 'fontawesome':
						$icon_html = '<i class="' . esc_attr( $value['icon_fontawesome'] ) . '"></i>';
						break;
					case 'linearicons':
						$icon_html = ! empty( $value['linearicons'] ) ? sprintf( '<i class="%s"></i>', $value['linearicons'] ) : '';
						break;
					case 'image':
						$icon_html = ! empty( $value['image'] ) ? wp_get_attachment_image( $value['image'], 'full' ) : '';
						break;
					default:
						$icon_html = '';
				}

				if ( $value['link'] ) {
					$link = sprintf( '<a href="%s" target="_blank">%s</a>', $value['link'], $icon_html );
				} else {
					$link = $icon_html;
				}

				$social_output[] = sprintf( '<li>%s</li>', $link );

			}
		}

		return sprintf(
			'<div class="%s"><h4 %s>%s</h4><ul>%s</ul></div>',
			esc_attr( implode( ' ', $css_class ) ),
			! empty( $atts['title_color'] ) ? sprintf( 'style="color: %s;"', $atts['title_color'] ) : '',
			$atts['title'],
			implode( '', $social_output )
		);
	}

	/*
	 * GG Maps shortcode
	 */
	function helendo_gmap( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'api_key'  => '',
				'lat'      => '',
				'lng'      => '',
				'marker'   => '',
				'width'    => '',
				'height'   => '587',
				'zoom'     => '7',
				'el_class' => '',
			), $atts
		);

		$class = array(
			'helendo-map-shortcode',
			$atts['el_class'],
		);

		$style = '';
		if ( $atts['width'] ) {
			$unit = 'px;';
			if ( strpos( $atts['width'], '%' ) ) {
				$unit = '%;';
			}

			$atts['width'] = intval( $atts['width'] );
			$style         .= 'width: ' . $atts['width'] . $unit;
		}
		if ( $atts['height'] ) {
			$unit = 'px;';
			if ( strpos( $atts['height'], '%' ) ) {
				$unit = '%;';
			}

			$atts['height'] = intval( $atts['height'] );
			$style          .= 'height: ' . $atts['height'] . $unit;
		}
		if ( $atts['zoom'] ) {
			$atts['zoom'] = intval( $atts['zoom'] );
		}

		$id   = uniqid( 'helendo_map_' );
		$html = sprintf(
			'<div class="%s"><div id="%s" class="helendo-map" style="%s"></div></div>',
			implode( ' ', $class ),
			$id,
			$style
		);

		$marker = '';
		if ( $atts['marker'] ) {

			if ( filter_var( $atts['marker'], FILTER_VALIDATE_URL ) ) {
				$marker = $atts['marker'];
			} else {
				$attachment_image = wp_get_attachment_image_src( intval( $atts['marker'] ), 'full' );
				$marker           = $attachment_image ? $attachment_image[0] : '';
			}
		}

		$this->api_key = $atts['api_key'];

		$this->l10n['map'][ $id ] = array(
			'type'   => 'normal',
			'lat'    => $atts['lat'],
			'lng'    => $atts['lng'],
			'zoom'   => $atts['zoom'],
			'marker' => $marker,
			'height' => $atts['height'],
		);

		return $html;
	}

	/**
	 * Count down shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function helendo_countdown( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'    => '1',
				'date'     => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'helendo-countdown helendo-time-format',
			'helendo-countdown__style-' . $atts['style'],
			$atts['el_class'],
		);

		$second = 0;
		if ( $atts['date'] ) {
			$second_current = strtotime( date_i18n( 'Y/m/d H:i:s' ) );
			$date           = new DateTime( $atts['date'] );
			if ( $date ) {
				$second_discount = strtotime( date_i18n( 'Y/m/d H:i:s', $date->getTimestamp() ) );
				if ( $second_discount > $second_current ) {
					$second = $second_discount - $second_current;
				}
			}
		}

		$time_html = sprintf( '<div class="helendo-time-countdown">%s</div>', $second );

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$time_html
		);
	}

	/**
	 * Product feature shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function helendo_product_feature( $atts, $content ) {
		$atts         = shortcode_atts(
			array(
				'style'             => '1',
				'position'          => 'left',
				'url'               => '',
				'title_header'      => '',
				'title'             => '',
				'text_after_price'  => '',
				'price'             => '',
				'image'             => '',
				'image_size'        => '',
				'readmore'          => '',
				'text_readmore'     => '',
				'content_spacing'   => '',
				'text_position'     => 'bottom',
				'layout_spacing'    => '',
				'info_left_spacing' => '',
				'el_class'          => '',
			), $atts
		);
		$style        = $atts['style'];
		$position     = $atts['position'];
		$link         = vc_build_link( $atts['url'] );
		$title_header = $atts['title_header'];
		$title        = $atts['title'];
		$price        = $atts['price'];
		$image        = $this->get_vc_image( $atts['image'], $atts['image_size'] );
		$readmore     = $atts['readmore'];
		$spacing      = $this->helendo_font_size( $atts['content_spacing'] );

		$text_after_price  = ! empty( $atts['text_after_price'] ) ? $atts['text_after_price'] : esc_html__( 'Just from', 'helendo' );
		$text_position     = $style == 2 && ! empty( $atts['text_position'] ) ? $atts['text_position'] : '';
		$layout_spacing    = $style == 2 && ! empty( $atts['layout_spacing'] ) ? $this->helendo_font_size( $atts['layout_spacing'] ) : '';
		$info_left_spacing = $style == 2 && ! empty( $atts['info_left_spacing'] ) ? $this->helendo_font_size( $atts['info_left_spacing'] ) : '';

		$div_layout_spacing    = ! empty( $layout_spacing ) ? sprintf( '<div class="layout-spacing" style="width: %s"></div>', $layout_spacing ) : '';
		$div_info_left_spacing = ! empty( $info_left_spacing ) ? sprintf( '<div class="layout-spacing" style="width: %s"></div>', $info_left_spacing ) : '';

		if ( ! $atts['image'] ) {
			return;
		}

		if ( ! empty( $title_header ) && $style == 3 ) {
			$div_title_header = sprintf( '%s', $title_header );
		} else {
			$div_title_header = '';
		}

		$out_put = array();

		$class = array(
			'helendo-product-feature',
			sprintf( 'style-%s', $style ),
			sprintf( 'align-%s', $position ),
			$style == 2 ? 'row' : '',
			$atts['el_class']
		);

		if ( $style == 1 ) {
			$text_readmore = ! empty( $atts['text_readmore'] ) ? $atts['text_readmore'] : esc_html__( 'Discover Now', 'helendo' );
		} elseif ( $style == 2 ) {
			$text_readmore = ! empty( $atts['text_readmore'] ) ? $atts['text_readmore'] : esc_html__( 'Shop Now', 'helendo' );
		}

		$out_put[] = ( $style == 1 ) || ( $style == 3 ) && ! empty( $atts['content_spacing'] ) ? sprintf( '<div class="line-spacing" style="height: %s;"></div>', $spacing ) : '';

		if ( ! empty( $title_header ) && $style == 3 ) {
			$out_put[] = sprintf( '<div class="sub-title">%s</div>', $div_title_header );
		}
		$out_put[] = ! empty( $title ) ? sprintf( '<h3 class="title" title="%s"><a href="%s">%s</a></h3>', $title, $link['url'], $title ) : '';

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		if ( $style == 3 || $style == 2 ) {
			$out_put[] = sprintf( '<div class="description">%s</div>', $content );
		}

		if ( $style == 1 ) {
			$out_put[] = sprintf( '<div class="price"><span>%s</span> %s</div>', $text_after_price, $price );
		} elseif ( $style == 3 ) {
			$out_put[] = sprintf( '<a href="%s" class="price">%s %s <i class="icon-arrow-right"></i></a>', $link['url'], $text_after_price, $price );
		}

		if ( $style == 1 ) {
			$out_put[] = ! empty( $readmore ) ? sprintf( '<a href="%s" class="read-more">%s<span class="arrow_carrot-2right"></span></a>', $link['url'], $text_readmore ) : '';
		} elseif ( $style == 2 ) {
			$out_put[] = ! empty( $readmore ) ? sprintf( '<a href="%s" class="read-more">%s<span class="icon-arrow-right"></span></a>', $link['url'], $text_readmore ) : '';
		}

		$row_1 = $row_2 = '';
		if ( $style == 1 ) {
			$row_1 = 'col-xs-12 col-sm-4';
			$row_2 = 'col-xs-12 col-sm-8';
		}
		if ( $style == 3 ) {
			$row_1 = $row_2 = 'col-xs-12 col-sm-6';
		}

		$info_product = sprintf( '<div class="%s info-product info-%s %s">%s<div class="info-wrapter">%s</div></div>', $row_1, $position, $text_position, $div_info_left_spacing, implode( '', $out_put ) );
		$thumbnail    = sprintf( '<div class="%s product-thumbnail"><a href="%s">%s</a></div>', $row_2, $link['url'], $image );

		return sprintf(
			'<div class="%s">%s<div class="row inline-spacing"> %s </div></div>',
			implode( ' ', $class ),
			$div_layout_spacing,
			$style == 2 && $text_position == 'bottom' ? $thumbnail . $info_product : $info_product . $thumbnail
		);
	}

	/**
	 * About us shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function helendo_about_us( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'           => '1',
				'text_align'      => 'left',
				'url'             => '',
				'title'           => '',
				'readmore'        => '',
				'text_readmore'   => '',
				'content_spacing' => '',
				'el_class'        => '',
			), $atts
		);

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$html          = array();
		$style         = $atts['style'];
		$text_align    = $atts['text_align'];
		$link          = vc_build_link( $atts['url'] );
		$title         = $atts['title'];
		$readmore      = $atts['readmore'];
		$spacing       = ! empty( $spacing ) ? $this->helendo_font_size( $atts['content_spacing'] ) : '';
		$html[]        = ! empty( $title ) ? sprintf( '<h3 class="title" title="%s">%s</h3>', $title, $title ) : '';
		$html[]        = ! empty( $content ) ? sprintf( '<div class="description">%s</div>', $content ) : '';
		$text_readmore = ! empty( $atts['text_readmore'] ) ? $atts['text_readmore'] : esc_html__( 'More About Us', 'helendo' );
		$html[]        = ! empty( $spacing ) ? sprintf( '<div class="line-spacing" style="height: %s;"></div>', $spacing ) : '';
		$html[]        = ! empty( $readmore ) ? sprintf( '<a href="%s" class="read-more">%s <span class="arrow_carrot-2right"></span></a>', $link['url'], $text_readmore ) : '';

		$class = array(
			'helendo-about-us',
			sprintf( 'style-%s', $style ),
			sprintf( 'text-%s', $text_align ),
			$atts['el_class']
		);

		return sprintf(
			'<div class="%s">%s</div>',
			implode( ' ', $class ),
			implode( '', $html )
		);
	}

	/**
	 * Banner grid shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function helendo_banners_grid( $atts, $content ) {
		$atts          = shortcode_atts(
			array(
				'style'         => '1',
				'setting'       => '',
				'image_size'    => '',
				'text_readmore' => '',
				'position'      => 'out-content',
				'el_class'      => '',
			), $atts
		);
		$style         = $atts['style'];
		$text_readmore = $atts['text_readmore'];
		$image_size    = $atts['image_size'];
		$position      = $atts['position'];

		$css_class = array(
			'helendo_banners_grid',
			sprintf( 'style-%s', $style ),
			sprintf( 'position-%s', $position ),
			$atts['el_class'],
			$style == 3 ? 'row' : '',
		);

		$infor   = vc_param_group_parse_atts( $atts['setting'] );
		$outputs = array();
		$count   = 1;

		$size1 = $size2 = $size3 = $size4 = '';
		if ( $style == 1 ) {
			$size1 = '1420x945';
			$size2 = '945x945';
			$size3 = '1260x625';
			$size4 = '1893x630';
		} elseif ( $style == 2 ) {
			$size1 = '290x400';
			$size2 = '590x400';
			$size3 = '600x260';
		}
		if ( ! empty( $infor ) ) {
			foreach ( $infor as $key => $value ) {

				if ( ! empty( $value['image'] ) ) {

					if ( $style == 1 ) {
						if ( $count == 1 ) {
							$size  = $size1;
							$class = "col-xs-12 col-sm-12 col-md-9";
						} elseif ( $count == 2 || $count == 3 ) {
							$size  = $size2;
							$class = "col-xs-12 col-sm-6 col-md-3";
						} elseif ( $count == 4 ) {
							$size  = $size2;
							$class = "col-xs-12 col-sm-5 col-md-4";
						} elseif ( $count == 5 ) {
							$size  = $size3;
							$class = "col-xs-12 col-sm-7 col-md-8";
						} elseif ( $count == 6 || $count == 7 ) {
							$size  = $size2;
							$class = "col-xs-12 col-sm-6 col-md-6";
						} else {
							$size  = $size4;
							$class = "col-xs-12 col-sm-12 col-md-12";
							$count = 1;
						}
					} elseif ( $style == 3 ) {
						$size  = ! empty( $image_size ) ? $image_size : '570x340';
						$class = "col-xs-12 col-sm-6";
					} else {
						if ( $count == 1 || $count == 3 ) {
							$size  = $size1;
							$class = "col-xs-12 col-sm-3 image-size-1";
						} elseif ( $count == 2 ) {
							$size  = $size2;
							$class = "col-xs-12 col-sm-6 image-size-2";
						} else {
							$size  = $size3;
							$class = "col-xs-12 col-sm-6 image-size-3";
						}
					}
					$font_size   = ! empty( $value['font_size'] ) ? sprintf( 'font-size: %s;', $this->helendo_font_size( $value['font_size'] ) ) : '';
					$title_color = ! empty( $value['title_color'] ) ? sprintf( 'style="color: %s;"', $value['title_color'] ) : '';
					$style_title = ! empty( $font_size ) || ! empty( $title_color ) ? sprintf( 'style=" %s"', $font_size . $title_color ) : '';

					$image       = ! empty( $value['image'] ) ? $this->get_vc_image( $value['image'], $size ) : '';
					$outputs[]   = sprintf( '<div class="banner-item %s product-%s">', $class, $count );
					$outputs[]   = sprintf( '<div class="banner-wrapper">' );
					$link        = ! empty( $value['link'] ) ? vc_build_link( $value['link'] ) : '';
					$url         = ! empty( $value['link'] ) ? $link['url'] : '#';
					$title       = ! empty( $value['title'] ) ? $value['title'] : '';
					$description = ! empty( $value['description'] ) ? $value['description'] : '';

					$outputs[] = sprintf( '<h3 class="title" title="%s" %s><a href="%s">%s</a></h3>', $title, $style_title, $url, $title );
					$outputs[] = sprintf( '<div class="description">%s</div>', $description );
					if ( $style == '3' ) {
						$text_readmore = ! empty( $text_readmore ) ? esc_attr__( $text_readmore, 'helendo' ) : esc_html__( 'Discover now', 'helendo' );
						$outputs[]     = sprintf( '<a href="%s" class="read-more">%s <span class="icon-arrow-right"></span></a>', $link['url'], $text_readmore );
					}
					$outputs[] = sprintf( '</div>' );
					if ( $style == 2 ) {
						$url_image = ! empty( $value['image'] ) ? wp_get_attachment_image_src( $value['image'], "full" )[0] : '';
						$outputs[] = ! empty( $value['image'] ) ? sprintf( '<div class="thumbnail size-%s"><a href="%s" style="background-image: url(%s)"></a></div>', $count, $url, $url_image ) : '';
					} else {
						$outputs[] = sprintf( '<div class="thumbnail size-%s"><a href="%s">%s</a></div>', $count, $url, $image );
					}
					$outputs[] = sprintf( '</div>' );

					$count ++;
				}

			}
		}

		return sprintf( '<div class="%s">%s</div>', implode( ' ', $css_class ), implode( '', $outputs ) );
	}

	/**
	 * Banner large shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function helendo_banner_large( $atts, $content ) {
		$atts      = shortcode_atts(
			array(
				'columns'       => '1',
				'link'          => '',
				'image'         => '',
				'title'         => '',
				'text_bold'     => '',
				'image_size'    => '',
				'text_readmore' => '',
				'el_class'      => '',
			), $atts
		);
		$columns   = $atts['columns'];
		$css_class = array(
			'helendo_banners_large',
			sprintf( 'helendo_banners_large-%s-columns', $columns ),
			esc_attr__( $atts['el_class'], 'helendo' ),

		);

		$outputs       = array();
		$outputs[]     = sprintf( '<div class="banner-wrapper">' );
		$link          = ! empty( $atts['link'] ) ? vc_build_link( $atts['link'] ) : '';
		$url           = ! empty( $atts['link'] ) ? $link['url'] : '#';
		$title         = $atts['title'];
		$text_readmore = ! empty( $atts['text_readmore'] ) ? $atts['text_readmore'] : esc_html__( 'Discover now', 'helendo' );
		$image         = ! empty( $atts['image'] ) ? $this->get_vc_image( $atts['image'], $atts['image_size'] ) : '';
		$text_bold     = $atts['text_bold'];
		if ( ! empty( $text_bold ) && $columns == 1 ) {
			$title = str_replace( $text_bold, sprintf( '<span>%s</span>', $text_bold ), $title );
		}

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$outputs[] = sprintf( '<h3 class="title">%s</h3>', $title );
		$outputs[] = sprintf( '<div class="description">%s</div>', $content );
		$outputs[] = sprintf( '<a href="%s" class="read-more">%s <span class="icon-arrow-right"></span></a>', $link['url'], $text_readmore );
		$outputs[] = sprintf( '</div>' );
		$outputs[] = sprintf( '<div class="thumbnail"><a href="%s">%s</a></div>', $url, $image );

		return sprintf( '<div class="%s">%s</div>', implode( ' ', $css_class ), implode( '', $outputs ) );
	}


	// Image carousel
	function helendo_image_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'item'           => '1',
				'ic_font_size'   => '',
				'nav'            => '',
				'dots'           => '',
				'autoplay'       => '',
				'autoplay_speed' => '2000',
				'image_size'     => '',
				'setting'        => '',
				'm_show'         => 1,
				'dots_mobile'    => '',
				'el_class'       => '',
			), $atts
		);

		// add class
		$css_class = array(
			'helendo-image-carousel',
			'helendo-image-carouselmobile-' . $atts['m_show'] . 'columns',
			$atts['el_class']
		);

		if ( $atts['dots'] ) {
			$dot = true;
		} else {
			$dot = false;
		}

		if ( $atts['nav'] ) {
			$nav = true;
		} else {
			$nav = false;
		}

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		if ( $atts['dots_mobile'] ) {
			$dots_mobile = true;
		} else {
			$dots_mobile = false;
		}

		$id    = uniqid( 'helendo-image-carousel-' );
		$slide = $scroll = $perrow = intval( $atts['item'] );
		$speed = intval( $atts['autoplay_speed'] );

		$ic_font_size                       = ! empty( $atts['ic_font_size'] ) ? $atts['ic_font_size'] : '';
		$this->l10n['imageCarousel'][ $id ] = array(
			'slide'        => $slide,
			'scroll'       => $scroll,
			'dot'          => $dot,
			'nav'          => $nav,
			'autoplay'     => $autoplay,
			'speed'        => $speed,
			'ic_font_size' => $ic_font_size,
			'm_dot'        => $dots_mobile,
			'm_show'       => intval( $atts['m_show'] )
		);


		// param content
		$infor   = vc_param_group_parse_atts( $atts['setting'] );
		$outputs = array();

		if ( ! empty( $infor ) ) {
			foreach ( $infor as $key => $value ) {

				$image = isset( $value['image'] ) && ! empty( $value['image'] ) ? $this->get_vc_image( $value['image'], $atts['image_size'] ) : '';

				$link = ! empty( $value['link'] ) ? vc_build_link( $value['link'] ) : '';

				if ( isset( $link['url'] ) && ! empty( $link['url'] ) ) {
					$href      = $link['url'];
					$outputs[] = sprintf(
						'<div class="item-img">' .
						'<a href="%s">%s</a>' .
						'</div>',
						$href,
						$image
					);
				} else {
					$outputs[] = sprintf(
						'<div class="item-img">' .
						'%s' .
						'</div>',
						$image
					);
				}

			}
		}

		return sprintf(
			'<div id="%s" class="%s">' .
			'%s' .
			'</div>',
			$id,
			implode( ' ', $css_class ),
			implode( '', $outputs )
		);
	}

	/**
	 * Shortcode to display latest post
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function helendo_latest_post( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'    => '1',
				'title'    => '',
				'text_btn' => '',
				'limit'    => '3',
				'el_class' => '',
			), $atts
		);

		$css_class    = array(
			'helendo-latest-post blog-grid',
			'blog-grid-style-' . $atts['style'],
			$atts['el_class'],
		);
		$titte        = $atts['title'] ? sprintf( '<h3>%s</h3>', $atts['title'] ) : '';
		$text_btn     = $atts['text_btn'] ? sprintf( '<a href="%s">%s <i class="icon-arrow-right"></i></a>', esc_url( get_permalink( get_option( 'page_for_posts' ) ) ), $atts['text_btn'] ) : '';
		$entry_header = ( $atts['title'] || $atts['text_btn'] ) ? sprintf( '<div class="hl-latest-post__header">%s %s</div>', $titte, $text_btn ) : '';

		$output = array();

		$query_args = array(
			'posts_per_page'      => $atts['limit'],
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );

		while ( $query->have_posts() ) : $query->the_post();
			global $helendo_post;

			$helendo_post['css'] = ' col-flex-md-4 col-flex-sm-6 col-flex-xs-6';


			$helendo_post['style']    = 'blog-grid-style-1';
			$helendo_post['size_fix'] = 'helendo-post-grid';

			if ( $atts['style'] == '2' ) {
				$helendo_post['style']    = 'blog-grid-style-2';
				$helendo_post['size_fix'] = 'helendo-post-grid-s2';
			}


			ob_start();
			get_template_part( 'template-parts/content', get_post_format() );
			$output[] = ob_get_clean();

		endwhile;
		wp_reset_postdata();

		return sprintf(
			'<div class="%s">' .
			'%s' .
			'<div class="post-list row-flex">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$entry_header,
			implode( '', $output )
		);
	}

	/**
	 * Banner grid shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function helendo_banners_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'          => '1',
				'setting'        => '',
				'text_readmore'  => '',
				'image_size'     => '',
				'nav'            => '',
				'dots'           => '',
				'autoplay'       => '',
				'autoplay_speed' => '2000',
				'initial_slide'  => 1,
				'el_class'       => '',
			), $atts
		);

		$text_readmore = $atts['text_readmore'];
		$image_size    = $atts['image_size'];
		$style         = $atts['style'];

		$css_class = array(
			'helendo_banners_carousel',
			sprintf( 'helendo_banners_carousel-style-%s', $style ),
			$atts['el_class'],
		);


		$id      = uniqid( 'helendo-banner-carousel-' );
		$slide   = $scroll = $perrow = 1;
		$speed   = intval( $atts['autoplay_speed'] );
		$initial = intval( $atts['initial_slide'] );

		if ( $atts['dots'] ) {
			$dot = true;
		} else {
			$dot = false;
		}

		if ( $atts['nav'] ) {
			$nav = true;
		} else {
			$nav = false;
		}

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		$this->l10n['bannerCarousel'][ $id ] = array(
			'slide'    => $slide,
			'scroll'   => $scroll,
			'dot'      => $dot,
			'nav'      => $nav,
			'autoplay' => $autoplay,
			'speed'    => $speed,
			'initial'  => $initial,
		);

		$infor   = vc_param_group_parse_atts( $atts['setting'] );
		$outputs = array();


		if ( ! empty( $infor ) ) {
			foreach ( $infor as $key => $value ) {

				$size        = ! empty( $image_size ) ? $image_size : '1170x650';
				$class       = "";
				$outputs[]   = sprintf( '<div class="banner-item %s">', $class );
				$outputs[]   = sprintf( '<div class="banner-wrapper">' );
				$link        = ! empty( $value['link'] ) ? vc_build_link( $value['link'] ) : '';
				$url         = ! empty( $value['link'] ) ? $link['url'] : '#';
				$title       = ! empty( $value['title'] ) ? $value['title'] : '';
				$description = ! empty( $value['description'] ) ? $value['description'] : '';
				$image       = ! empty( $value['image'] ) ? $this->get_vc_image( $value['image'], $size ) : '';

				if ( $style == 2 ) :$outputs[] = sprintf( '<div class="description">%s</div>', $description ); endif;

				$outputs[] = sprintf( '<h3 class="title" title="%s"><a href="%s">%s</a></h3>', $title, $url, $title );

				if ( $style == 1 ) :$outputs[] = sprintf( '<div class="description">%s</div>', $description ); endif;

				$text_readmore = ! empty( $text_readmore ) ? esc_attr__( $text_readmore, 'helendo' ) : esc_html__( 'Discover now', 'helendo' );
				$outputs[]     = sprintf( '<a href="%s" class="read-more">%s</a>', $link['url'], $text_readmore );

				$outputs[] = sprintf( '</div>' );
				$outputs[] = sprintf( '<div class="thumbnail"><a href="%s">%s</a></div>', $url, $image );
				$outputs[] = sprintf( '</div>' );
			}
		}

		return sprintf(
			'<div class="%s"><div class="banner-wrapper" id="%s">%s</div><div class="slider-arrows"><div class="container"></div></div></div>',
			implode( ' ', $css_class ),
			$id,
			implode( '', $outputs )
		);
	}


	/**
	 * Get button
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function helendo_button( $atts, $content ) {
		$atts           = shortcode_atts(
			array(
				'style'            => 'classic',
				'text_bt'          => '',
				'link'             => '',
				'font_size'        => '',
				'font_weight'      => '',
				'bt_align'         => 'left',
				'icon_type'        => 'none',
				'icon_color'       => '',
				'icon_size'        => '50px',
				'linearicons'      => '',
				'icon_fontawesome' => '',
				'image_size'       => '',
				'image'            => '',
				'elegant'          => '',
				'el_class'         => '',

			), $atts
		);
		$icon_type      = $atts['icon_type'];
		$text_bt        = $atts['text_bt'];
		$link           = vc_build_link( $atts['link'] );
		$link           = $link['url'];
		$bt_align       = $atts['bt_align'];
		$el_class       = $atts['el_class'];
		$image_ids      = $atts['image'];
		$font_style[]   = ! empty( $atts['font_size'] ) ? sprintf( 'font-size:%s;', $this->helendo_font_size( $atts['font_size'] ) ) : '';
		$font_style[]   = ! empty( $atts['font_weight'] ) ? sprintf( 'font-weight:%s;', $atts['font_weight'] ) : '';
		$style          = ! empty( $font_style ) ? sprintf( 'style="%s"', implode( '', $font_style ) ) : '';
		$style_ic_color = ! empty( $icon_color ) ? "color:$icon_color;" : '';
		$size           = $this->helendo_font_size( $atts['icon_size'] );
		$icon_size      = ! empty( $icon_size ) ? "font-size:$size;" : '';
		$style_ic       = "style='$style_ic_color $icon_size '";

		$class = array(
			'helendo-button-group',
			sprintf( 'helendo-button-%s', $atts['style'] ),
			sprintf( 'helendo-align-%s', $bt_align ),
			$atts['el_class'],
		);

		switch ( $icon_type ) {
			case 'none':
				$icon = '';
				break;
			case 'fa_font':
				$value = $atts['icon_fontawesome'];
				$icon  = "<span class='$value' $style_ic></span>";
				break;
			case 'linearicons':
				$icon = ! empty( $atts['linearicons'] ) ? sprintf( ' <i class="%s" %s></i>', $atts['linearicons'], $style_ic ) : '';
				break;
			case 'image':
				$image_thumb = $this->get_vc_image( $image_ids, $atts['image_size'] );
				$icon        = "<span class='svg-icon'>$image_thumb</span>";
				break;
			default:
				$icon = "";
		}

		return sprintf( '<div class="%s"><a href="%s" class="helendo-button %s" %s><span>%s</span> %s</a></div>', implode( ' ', $class ), $link, $el_class, $style, $text_bt, $icon );
	}

	function helendo_product( $atts, $content ) {
		$atts = shortcode_atts(
			array(

				'alignment'   => 'left',
				'title'       => '',
				'link'        => '',
				'image'       => '',
				'image_size'  => '',
				'top_spacing' => '',
				'el_class'    => '',
			), $atts
		);

		$class = array(
			'helendo-product-shortcode',
			$atts['el_class']
		);

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$alignment     = $atts['alignment'];
		$size          = ! empty( $atts['image_size'] ) ? $atts['image_size'] : 'full';
		$value_spacing = ! empty( $atts['top_spacing'] ) ? $this->helendo_font_size( $atts['top_spacing'] ) : '';
		$top_spacing   = ! empty( $value_spacing ) ? sprintf( '<div class="product-spacing" style="height: %s"></div>', $value_spacing ) : '';

		$product_link  = ! empty( $atts['link'] ) ? vc_build_link( $atts['link'] )['url'] : '';
		$product_image = ! empty( $atts['image'] ) ? $this->get_vc_image( $atts['image'], $size ) : '';
		$product_title = ! empty( $atts['title'] ) ? $atts['title'] : '';
		$product_desc  = ! empty( $content ) ? $content : '';

		$img_html     = sprintf( '<div class="image-product"><a href="%s">%s</a></div>', $product_link, $product_image );
		$title_html   = sprintf( '<h3 class="title"><a href="%s">%s</a></h3>', $product_link, $product_title );
		$content_hrml = sprintf( '<div class="content-product">%s</div>', $product_desc );

		$outputs = sprintf(
			'<div class="item-wrapper product-item">%s<div class="inside-content conten-align-%s"> %s %s %s</div></div>',
			$top_spacing,
			$alignment,
			$img_html,
			$title_html,
			$content_hrml
		);

		return sprintf(
			'<div class="%s">%s</div>',
			implode( ' ', $class ),
			$outputs
		);
	}

	function helendo_newletter( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'     => '1',
				'title'     => '',
				'font_size' => '',
				'desc'      => '',
				'image'     => '',
				'form'      => '',
				'box_bk'    => '',
				'el_class'  => '',
			), $atts
		);

		$css   = array();
		$style = array();

		$class_name_title = 'helendo-newletter--title__' . $this->get_id_number( __FUNCTION__ );

		$css_class = array(
			'helendo-newletter',
			'helendo-newletter__style-' . $atts['style'],
			$class_name_title,
			$atts['el_class'],
		);

		if ( $atts['box_bk'] ) {
			$style[] = 'background-color:' . $atts['box_bk'] . '';
		}

		if ( $atts['image'] ) {
			$image   = wp_get_attachment_image_src( $atts['image'], 'full' );
			$style[] = 'background-image:url(' . $image[0] . ')';
		}

		if ( $atts['box_bk'] || $atts['image'] ) {
			$css_class[] = 'has-bg';
		}

		if ( $atts['font_size'] ) {
			$font_size = $this->helendo_font_size( $atts['font_size'] );
			$css[]     = ".$class_name_title h4 { font-size: " . $font_size . "}";
		}

		$title = $atts['title'] ? sprintf( '<h4 class="title">%s</h4>', $atts['title'] ) : '';

		if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
			$content = wpb_js_remove_wpautop( $content, true );
		}

		$desc   = $content ? sprintf( '<div class="desc">%s</div>', $content ) : '';
		$output = $form = $col_header = $col_form = '';

		if ( $atts['style'] == '1' || $atts['style'] == '2' ) {
			$col_header = 'col-flex-md-5 col-flex-sm-12 col-flex-xs-12';
			$col_form   = 'col-flex-md-7 col-flex-sm-12 col-flex-xs-12';
		}

		if ( $atts['style'] == '3' ) {
			$col_header = 'col-flex-xs-12';
			$col_form   = 'col-flex-xs-12';
		}

		if ( $atts['style'] == '4' ) {
			$col_header = 'col-flex-md-6 col-flex-sm-12 col-flex-xs-12';
			$col_form   = 'col-flex-md-6 col-flex-sm-12 col-flex-xs-12';
		}

		if ( $atts['style'] == '5' ) {
			$col_header = 'col-flex-xs-12';
			$col_form   = 'col-flex-xs-12';
		}

		if ( $title || $desc ) {
			$output = sprintf( '<div class="newsletter-header %s">%s%s</div>', esc_attr( $col_header ), $title, $desc );
		}

		if ( $atts['form'] ) {
			$form = sprintf( '<div class="newsletter-form %s">%s</div>', esc_attr( $col_form ), do_shortcode( '[mc4wp_form id="' . esc_attr( $atts['form'] ) . '" title=" ' . get_the_title( $atts['form'] ) . ' "]' ) );
		}

		return sprintf(
			'<style type="text/css">%s</style>
			<div class="%s" style="%s">
				<div class="newsletter-wrapper container">
					<div class="row-flex">%s%s</div>
				</div>
			</div>',
			implode( "\n", $css ),
			esc_attr( implode( ' ', $css_class ) ),
			implode( ';', $style ),
			$output,
			$form
		);
	}


	/**
	 * Product Grid
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function helendo_products_grid( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'     => '',
				'subtitle'  => '',
				'style'     => '1',
				'limit'     => 6,
				'columns'   => 3,
				'orderby'   => 'title',
				'order'     => 'ASC',
				'filter'    => 'category',
				'tabs'      => '',
				'category'  => '',
				'link'      => '',
				'load_more' => false,
				'btn_style' => 'border-bottom',
				'font_size' => '',
				'el_class'  => '',
			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class = array(
			'helendo-products-grid helendo-products',
			'style-' . $atts['style'],
			$atts['btn_style'],
			$atts['el_class']
		);

		if ( $atts['load_more'] ) {
			$css_class[] = 'load-more-enabled';
		}

		$output = array();
		$title  = '';
		$filter = array();
		$type   = 'products';

		$attr      = array(
			'limit'   => intval( $atts['limit'] ),
			'columns' => intval( $atts['columns'] ),
			'orderby' => $atts['orderby'],
			'order'   => $atts['order'],
		);
		$font_size = ! empty( $atts['font_size'] ) ? sprintf( 'style="font-size: %s"', $this->helendo_font_size( $atts['font_size'] ) ) : '';
		$tabs      = vc_param_group_parse_atts( $atts['tabs'] );

		if ( $atts['title'] ) {
			$title = sprintf( '<h3 class="title" %s>%s</h3>', $font_size, $atts['title'] );
		}

		if ( $atts['subtitle'] ) {
			$title .= sprintf( '<div class="subtitle">%s</div>', $atts['subtitle'] );
		}

		if ( $title ) {
			$title = '<div class="section-title">' . $title . '</div>';
		}

		if ( $atts['filter'] == 'category' ) {
			$filter[] = sprintf( '<li class="active" data-filter=""><span>%s</span></li>', esc_html__( 'All Products', 'helendo' ) );

			if ( $atts['category'] ) {
				$cats = explode( ',', $atts['category'] );

				foreach ( $cats as $cat ) {
					$cat      = get_term_by( 'slug', $cat, 'product_cat' );
					$filter[] = sprintf( '<li class="" data-filter="%s"><span>%s</span></li>', esc_attr( $cat->slug ), esc_html( $cat->name ) );
				}
			} else {
				$terms = get_terms( 'product_cat' );

				foreach ( $terms as $term ) {
					$filter[] = sprintf( '<li class="" data-filter="%s"><span>%s</span></li>', esc_attr( $term->slug ), esc_html( $term->name ) );
				}
			}

		} else {
			$css_class[] = 'filter-by-group';

			if ( $tabs ) {
				$type = $tabs[0]['products'];

				foreach ( $tabs as $tab ) {
					if ( isset( $tab['title'] ) ) {
						$filter[] = sprintf( '<li class="" data-filter="%s"><span>%s</span></li>', esc_attr( $tab['products'] ), esc_html( $tab['title'] ) );
					}
				}
			}
		}

		$link = '';

		$atts['page'] = 1;

		if ( $atts['link'] && $atts['style'] == '3' ) {
			$link = '<div class="product-btn">' . $this->get_vc_link( $atts, '' ) . '</div>';
		}

		$filter_html = '<div class="carousel-filter"> <ul class="nav-filter filter clearfix">' . implode( "\n", $filter ) . '</ul></div>';

		$output[] = $atts['style'] == '3' ? $title : '';
		$output[] = '<div class="product-header">';
		$output[] = $atts['style'] != '3' ? $title : '';
		$output[] = $filter_html;
		$output[] = $link;
		$output[] = '</div>';
		$output[] = '<div class="product-wrapper">';
		$output[] = '<div class="product-loading"><span class="helendo-loader"></span></div>';
		$output[] = $this->get_wc_products( $atts, $type );
		$output[] = '</div>';

		return sprintf(
			'<div class="%s" data-attr="%s" data-load_more="%s" data-nonce="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( json_encode( $attr ) ),
			esc_attr( $atts['load_more'] ),
			esc_attr( wp_create_nonce( 'helendo_get_products' ) ),
			implode( '', $output )
		);
	}

	/**
	 * Product Carousel
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function helendo_products_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'             => '',
				'subtitle'          => '',
				'font_size'         => '',
				'style'             => '1',
				'limit'             => 6,
				'columns'           => 3,
				'orderby'           => 'title',
				'order'             => 'ASC',
				'filter'            => 'category',
				'tabs'              => '',
				'category'          => '',
				'nav'               => '',
				'dots'              => '',
				'categories_filter' => '',
				'autoplay'          => '',
				'autoplay_speed'    => 2000,
				'speed'             => 1000,
				'slide_to_show'     => 4,
				'slide_to_scroll'   => 1,
				'font_size_nav'     => '',
				'm_show'            => 2,
				'dots_mobile'       => '',
				'el_class'          => '',
			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class = array(
			'helendo-products-carousel helendo-products',
			'style-' . $atts['style'],
			$atts['el_class']
		);

		$output = array();
		$title  = '';
		$filter = array();
		$type   = 'products';

		$attr = array(
			'limit'   => intval( $atts['limit'] ),
			'columns' => intval( $atts['columns'] ),
			'orderby' => $atts['orderby'],
			'order'   => $atts['order'],
		);

		$autoplay_speed = intval( $atts['autoplay_speed'] );
		$speed          = intval( $atts['speed'] );
		$show           = intval( $atts['slide_to_show'] );
		$scroll         = intval( $atts['slide_to_scroll'] );

		$font_size_nav = ! empty( $atts['font_size_nav'] ) ? $this->helendo_font_size( $atts['font_size_nav'] ) : '';

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		if ( $atts['nav'] ) {
			$nav         = true;
			$css_class[] = 'nav-enable';
		} else {
			$nav = false;
		}

		if ( $atts['dots'] ) {
			$dot         = true;
			$css_class[] = 'dots-enable';
		} else {
			$dot = false;
		}

		if ( $atts['dots_mobile'] ) {
			$dots_mobile = true;
		} else {
			$dots_mobile = false;
		}

		$id = uniqid( 'helendo-product-carousel-' );

		$this->l10n['productsCarousel'][ $id ] = array(
			'nav'           => $nav,
			'dot'           => $dot,
			'dot_mobile'    => $dots_mobile,
			'autoplay'      => $autoplay,
			'autoplaySpeed' => $autoplay_speed,
			'speed'         => $speed,
			'show'          => $show,
			'scroll'        => $scroll,
			'nav_size'      => $font_size_nav,
			'm_show'        => intval( $atts['m_show'] )
		);

		$tabs = vc_param_group_parse_atts( $atts['tabs'] );

		if ( $atts['title'] ) {
			$style_title = ! empty( $atts['font_size'] ) ? sprintf( 'style="font-size: %s"', $this->helendo_font_size( $atts['font_size'] ) ) : '';
			$title       = sprintf( '<h3 class="title" %s>%s</h3>', $style_title, $atts['title'] );
		}

		if ( $atts['subtitle'] ) {
			$title .= sprintf( '<div class="subtitle">%s</div>', $atts['subtitle'] );
		}

		if ( $title ) {
			$title = '<div class="section-title">' . $title . '</div>';
		}

		if ( $atts['filter'] == 'category' ) {
			$filter[] = sprintf( '<li class="active" data-filter=""><span>%s</span></li>', esc_html__( 'All Products', 'helendo' ) );

			if ( $atts['category'] ) {
				$cats = explode( ',', $atts['category'] );

				foreach ( $cats as $cat ) {
					$cat      = get_term_by( 'slug', $cat, 'product_cat' );
					$filter[] = sprintf( '<li class="" data-filter="%s"><span>%s</span></li>', esc_attr( $cat->slug ), esc_html( $cat->name ) );
				}
			} else {
				$terms = get_terms( 'product_cat' );

				foreach ( $terms as $term ) {
					$filter[] = sprintf( '<li class="" data-filter="%s"><span>%s</span></li>', esc_attr( $term->slug ), esc_html( $term->name ) );
				}
			}

		} else {
			$css_class[] = 'filter-by-group';

			if ( $tabs ) {
				$type = $tabs[0]['products'];

				foreach ( $tabs as $tab ) {
					if ( isset( $tab['title'] ) ) {
						$filter[] = sprintf( '<li class="" data-filter="%s"><span>%s</span></li>', esc_attr( $tab['products'] ), esc_html( $tab['title'] ) );
					}
				}
			}
		}

		$atts['page'] = 1;
		$filter_html  = '<div class="carousel-filter"><ul class="nav-filter filter clearfix">' . implode( "\n", $filter ) . '</ul></div>';
		if ( $atts['categories_filter'] ) {
			$filter_html = '';
		}

		$output[] = empty( $title ) && empty( $filter_html ) ? '' : '<div class="product-header">';
		$output[] = $title;
		$output[] = $filter_html;
		$output[] = empty( $title ) && empty( $filter_html ) ? '' : '</div>';
		$output[] = '<div class="product-wrapper" id="' . esc_attr( $id ) . '">';
		$output[] = '<div class="product-loading"><span class="helendo-loader"></span></div>';
		$output[] = $this->get_wc_products( $atts, $type );
		$output[] = '</div>';

		return sprintf(
			'<div class="%s" data-attr="%s" data-load_more="0" data-nonce="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( json_encode( $attr ) ),
			esc_attr( wp_create_nonce( 'helendo_get_products' ) ),
			implode( '', $output )
		);
	}

	/*
	 * Instagram
	 */
	function helendo_instagram( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'type'         => 'type_user',
				'access_token' => '',
				'user'         => '',
				'number'       => 10,
				'columns'      => '5',
				'm_columns'    => '3',
				'size'         => 'low_resolution',
				'video'        => false,
				'target'       => '_blank',
				'el_class'     => '',
			), $atts
		);

		$css_class = array(
			'helendo-instagram-shortcode',
			$atts['el_class'],
		);

		if ( ! $atts['user'] ) {
			return '';
		}

		$user         = ! empty( $atts['user'] ) ? $atts['user'] : '';
		$access_token = ! empty( $atts['access_token'] ) ? $atts['access_token'] : '';
		$numbers      = ! empty( $atts['number'] ) ? $atts['number'] : '';
		$columns      = ! empty( $atts['columns'] ) ? $atts['columns'] : '';
		$image_size   = ! empty( $atts['size'] ) ? $atts['size'] : 'low_resolution';

		$icon = HELENDO_ADDONS_URL . 'assets/images/instagram-icon.png';
		$icon = sprintf( '<img src="%s" alt="">', esc_url( $icon ) );
		$icon = apply_filters( 'helendo_instagram_shortcode_icon', sprintf( '<span class="instagram-icon">%s</span>', $icon ) );

		$instagram_array = array();
		if ( $atts['type'] == 'type_user' ) {
			$instagram_array = $this->instagram_get_photos_by_user( $numbers, $user );
		} else {
			$instagram_array = $this->instagram_get_photos_by_token( $numbers, $access_token );
		}

		$output = array();

		if ( is_wp_error( $instagram_array ) ) {
			echo wp_kses_post( $instagram_array->get_error_message() );
		} else {
			$count = 0;
			foreach ( $instagram_array as $instagram_item ) {
				if ( ! empty( $default_hashtag ) && isset( $instagram_item['tags'] ) ) {
					if ( ! in_array( $default_hashtag, $instagram_item['tags'] ) ) {
						continue;
					}
				}

				$image_link = $instagram_item[ $image_size ];
				$image_url  = $instagram_item['link'];
				$image_html = sprintf( '<img src="%s" alt="%s">', esc_url( $image_link ), esc_attr( '' ) );

				$output[] = '<li class="instagram-item">';
				$output[] .= '<a class="insta-item" href="' . esc_url( $image_url ) . '" target="' . esc_attr( $atts['target'] ) . '">';
				$output[] .= $icon;
				$output[] .= $image_html;
				$output[] .= '</a>';
				$output[] .= '</li>' . "\n";

				$count ++;
				$numbers = intval( $numbers );
				if ( $numbers > 0 ) {
					if ( $count == $numbers ) {
						break;
					}
				}
			}
		}

		$columns   = apply_filters( 'helendo_instagram_shortcode_columns', $atts['columns'] );
		$m_columns = apply_filters( 'helendo_instagram_shortcode_mobile_columns', $atts['m_columns'] );

		return sprintf(
			'<div class="%s" data-columns="%s" data-mobile="%s">
				<ul class="instagram-photos clearfix">%s</ul>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( intval( $columns ) ),
			esc_attr( intval( $m_columns ) ),
			implode( ' ', $output )
		);
	}

	/**
	 * Get instagram photo
	 *
	 * @param string $hashtag
	 * @param int $numbers
	 * @param string $title
	 * @param string $columns
	 */
	function instagram_get_photos_by_user( $number, $instagram_user ) {
		global $post;

		if ( empty( $instagram_user ) ) {
			return '';
		}

		$instagram_user = trim( strtolower( $instagram_user ) );

		$url              = 'https://instagram.com/' . str_replace( '@', '', $instagram_user );
		$transient_prefix = 'user' . $number;
		$instagram        = get_transient( 'helendo_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_user ) );

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
	 * Get instagram photo
	 *
	 * @param string $hashtag
	 * @param int $numbers
	 * @param string $title
	 * @param string $columns
	 */
	function instagram_get_photos_by_token( $numbers, $instagram_access_token ) {
		global $post;

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
	 * Helper function to get coordinates for map
	 *
	 * @since 1.0.0
	 *
	 * @param string $address
	 * @param bool $refresh
	 *
	 * @return array
	 */
	protected function get_coordinates( $address, $refresh = false ) {
		$address_hash = md5( $address );
		$coordinates  = get_transient( $address_hash );
		$results      = array( 'lat' => '', 'lng' => '' );

		if ( $refresh || $coordinates === false ) {
			$args     = array( 'address' => urlencode( $address ), 'sensor' => 'false' );
			$url      = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );

			if ( is_wp_error( $response ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'helendo' );

				return $results;
			}

			$data = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $data ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'helendo' );

				return $results;
			}

			if ( $response['response']['code'] == 200 ) {
				$data = json_decode( $data );

				if ( $data->status === 'OK' ) {
					$coordinates = $data->results[0]->geometry->location;

					$results['lat']     = $coordinates->lat;
					$results['lng']     = $coordinates->lng;
					$results['address'] = (string) $data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $results, 3600 * 24 * 30 * 3 );
				} elseif ( $data->status === 'ZERO_RESULTS' ) {
					$results['error'] = esc_html__( 'No location found for the entered address.', 'helendo' );
				} elseif ( $data->status === 'INVALID_REQUEST' ) {
					$results['error'] = esc_html__( 'Invalid request. Did you enter an address?', 'helendo' );
				} else {
					$results['error'] = esc_html__( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'helendo' );
				}
			} else {
				$results['error'] = esc_html__( 'Unable to contact Google API service.', 'helendo' );
			}
		} else {
			$results = $coordinates; // return cached results
		}

		return $results;
	}

	/**
	 * Filter images only
	 *
	 * @param array $item
	 *
	 * @return bool
	 */
	protected function image_only_filter( $item ) {
		return $item['type'] == 'image';
	}

	/**
	 * Get ID number of a shortcode
	 *
	 * @param string $shortcode
	 *
	 * @return int
	 */
	protected function get_id_number( $shortcode ) {
		if ( isset( $this->ids[ $shortcode ] ) ) {
			$this->ids[ $shortcode ] ++;
		} else {
			$this->ids[ $shortcode ] = 1;
		}

		return $this->ids[ $shortcode ];
	}

}