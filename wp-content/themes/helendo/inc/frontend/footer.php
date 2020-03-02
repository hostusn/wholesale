<?php
/**
 * Hooks for template footer
 *
 * @package Helendo
 */


/**
 * Show footer
 */

if ( ! function_exists( 'helendo_show_footer' ) ) :
	function helendo_show_footer() {
		get_template_part( 'template-parts/footers/footer' );
	}

endif;

add_action( 'helendo_footer', 'helendo_show_footer', 30 );

/**
 * Show footer widgets
 */

if ( ! function_exists( 'helendo_show_footer_widgets' ) ) :
	function helendo_show_footer_widgets() {
		if ( ! intval( helendo_get_option( 'footer_widgets' ) ) ) {
			return;
		}

		get_template_part( 'template-parts/footers/footer-widget' );
	}

endif;

add_action( 'helendo_footer', 'helendo_show_footer_widgets', 10 );

/**
 * Adds photoSwipe dialog element
 */
function helendo_gallery_images_lightbox() {
	?>
	<div id="pswp" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="pswp__bg"></div>

		<div class="pswp__scroll-wrap">

			<div class="pswp__container">
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
			</div>

			<div class="pswp__ui pswp__ui--hidden">

				<div class="pswp__top-bar">


					<div class="pswp__counter"></div>

					<button class="pswp__button pswp__button--close"
							title="<?php esc_attr_e( 'Close (Esc)', 'helendo' ) ?>"></button>

					<button class="pswp__button pswp__button--share"
							title="<?php esc_attr_e( 'Share', 'helendo' ) ?>"></button>

					<button class="pswp__button pswp__button--fs"
							title="<?php esc_attr_e( 'Toggle fullscreen', 'helendo' ) ?>"></button>

					<button class="pswp__button pswp__button--zoom"
							title="<?php esc_attr_e( 'Zoom in/out', 'helendo' ) ?>"></button>

					<div class="pswp__preloader">
						<div class="pswp__preloader__icn">
							<div class="pswp__preloader__cut">
								<div class="pswp__preloader__donut"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
					<div class="pswp__share-tooltip"></div>
				</div>

				<button class="pswp__button pswp__button--arrow--left"
						title="<?php esc_attr_e( 'Previous (arrow left)', 'helendo' ) ?>">
				</button>

				<button class="pswp__button pswp__button--arrow--right"
						title="<?php esc_attr_e( 'Next (arrow right)', 'helendo' ) ?>">
				</button>

				<div class="pswp__caption">
					<div class="pswp__caption__center"></div>
				</div>

			</div>

		</div>

	</div>
	<?php
}

add_action( 'helendo_after_footer', 'helendo_gallery_images_lightbox' );

/**
 * Add off canvas menu sidebar to footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'helendo_off_canvas_menu_sidebar' ) ) :
	function helendo_off_canvas_menu_sidebar() {


		?>
		<div id="menu-sidebar-panel" class="menu-sidebar helendo-off-canvas-panel">
			<div class="widget-canvas-content">
				<div class="widget-panel-header">
					<a href="#" class="close-canvas-panel"><span aria-hidden="true" class="icon-cross2"></span></a>
				</div>
				<div class="widget-panel-content hidden-md hidden-sm hidden-xs">
					<?php
					$menu_sidebar_els = helendo_get_option( 'menu_sidebar_el' );

					if ( $menu_sidebar_els == 'widget' ) {
						$sidebar = 'menu-sidebar';
						if ( is_active_sidebar( $sidebar ) ) {
							dynamic_sidebar( $sidebar );
						}
					} else {
						helendo_nav_menu( false );
					}

					?>
				</div>

				<div class="widget-panel-content hidden-lg">
					<?php

					$menu_sidebar_els = helendo_get_option( 'menu_sidebar_mobile_el' );

					if ( $menu_sidebar_els == 'widget' ) {
						$sidebar = 'mobile-menu-sidebar';
						if ( is_active_sidebar( $sidebar ) ) {
							dynamic_sidebar( $sidebar );
						} else {
							$sidebar = 'menu-sidebar';
							if ( is_active_sidebar( $sidebar ) ) {
								dynamic_sidebar( $sidebar );
							}
                        }
					} else {
						helendo_nav_menu( false );
					}
					?>
				</div>
				<div class="widget-panel-footer">
				</div>
			</div>
		</div>
		<?php
	}

endif;

add_action( 'wp_footer', 'helendo_off_canvas_menu_sidebar' );

/**
 * Display a layer to close canvas panel everywhere inside page
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'helendo_site_canvas_layer' ) ) :
	function helendo_site_canvas_layer() {
		?>
		<div id="off-canvas-layer" class="helendo-off-canvas-layer"></div>
		<?php
	}

endif;

add_action( 'wp_footer', 'helendo_site_canvas_layer' );

/**
 * Add off canvas shopping cart to footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'helendo_off_canvas_cart' ) ) :
	function helendo_off_canvas_cart() {
		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}

		?>
		<div id="cart-panel" class="cart-panel woocommerce mini-cart helendo-off-canvas-panel">
			<div class="widget-canvas-content">
				<div class="widget-cart-header  widget-panel-header">
					<a href="#" class="close-canvas-panel"><span aria-hidden="true" class="icon-cross2"></span></a>
				</div>
				<div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
				</div>
			</div>
			<div class="mini-cart-loading"><span class="helendo-loader"></span></div>
		</div>
		<?php
	}

endif;

add_action( 'wp_footer', 'helendo_off_canvas_cart' );

/**
 * Add search modal to footer
 */
if ( ! function_exists( 'helendo_search_modal' ) ) :
	function helendo_search_modal() {

		?>
		<div id="search-modal" class="search-modal helendo-modal" tabindex="-1" role="dialog">
			<div class="helendo-container">
				<div class="modal-header">
					<h2 class="modal-title"><?php esc_html_e( 'Search', 'helendo' ); ?></h2>
					<a href="#" class="close-modal">
						<i class="icon-cross"></i>
					</a>
				</div>
				<div class="modal-content container">
					<form method="get" class="instance-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
						$number = apply_filters( 'helendo_product_cats_search_number', 4 );
						$cats   = '';
						if ( helendo_get_option( 'header_search_type' ) == 'product' ) {
							$args = array(
								'number'       => $number,
								'orderby'      => 'count',
								'order'        => 'desc',
								'hierarchical' => false,
								'taxonomy'     => 'product_cat',
							);
							$cats = get_terms( $args );
						}
						?>
						<?php if ( ! is_wp_error( $cats ) && $cats ) : ?>
							<div class="product-cats">
								<label>
									<input type="radio" name="product_cat" value="" checked="checked">
									<span class="line-hover"><?php esc_html_e( 'All', 'helendo' ) ?></span>
								</label>

								<?php foreach ( $cats as $cat ) : ?>
									<label>
										<input type="radio" name="product_cat"
											   value="<?php echo esc_attr( $cat->slug ); ?>">
										<span class="line-hover"><?php echo esc_html( $cat->name ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<div class="search-fields">
							<input type="text" name="s" placeholder="<?php esc_attr_e( 'Search', 'helendo' ); ?>"
								   value="<?php echo esc_attr(get_search_query()); ?>"
								   class="search-field" autocomplete="off">
							<?php if ( helendo_get_option( 'header_search_type' ) == 'product' ) { ?>
								<input type="hidden" name="post_type" value="product">
							<?php } ?>
							<input type="submit" class="btn-submit">
							<span class="search-icon"><i class="icon-magnifier"></i></span>
							<div class="text-center loading">
								<span class="helendo-loader"></span>
							</div>
						</div>
					</form>
					<div class="search-results modal-content__search-results">
						<div class="searched-items woocommerce blog-grid"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

endif;

add_action( 'wp_footer', 'helendo_search_modal' );

/**
 * Add search modal to footer
 */
if ( ! function_exists( 'helendo_header_left_sidebar' ) ) :
	function helendo_header_left_sidebar() {
		if ( ! is_page_template( 'template-home-left-sidebar.php' ) ) {
			return;
		}
		?>
		<div id="header-left-sidebar" class="header-left-sidebar">
			<div class="header-left-sidebar__content">
				<div class="header-left-sidebar__content-header"></div>
				<div class="header-left-sidebar__content-body">
					<div class="logo">
						<?php get_template_part( 'template-parts/header/logo' ) ?>
					</div>
					<?php
					$sidebar = 'header-left-sidebar';
					if ( is_active_sidebar( $sidebar ) ) {
						dynamic_sidebar( $sidebar );
					}
					?>
				</div>
				<div class="header-left-sidebar__content-footer"></div>
			</div>
		</div>

		<?php
	}
endif;
add_action( 'wp_footer', 'helendo_header_left_sidebar' );
/**
 * Adds quick view modal to footer
 */
if ( ! function_exists( 'helendo_quick_view_modal' ) ) :
	function helendo_quick_view_modal() {
		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}
		?>

		<div id="quick-view-modal" class="quick-view-modal helendo-modal woocommerce single-product" tabindex="-1"
			 role="dialog">
			<div class="modal-content">
				<a href="#" class="close-modal">
					<i class="icon-cross"></i>
				</a>

				<div class="container">
					<div class="helendo-product-content">
						<div class="product">
							<div class="row">
								<div class="col-md-6 col-sm-12 col-xs-12 product-images-wrapper">
								</div>
								<div class="col-md-6 col-sm-12 col-xs-12  product-summary">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="helendo-loader"></div>
		</div>

		<?php
	}

endif;

add_action( 'wp_footer', 'helendo_quick_view_modal' );

/**
 * Add login modal to footer
 */

if ( ! function_exists( 'helendo_login_modal' ) ) :
	function helendo_login_modal() {

		if ( ! shortcode_exists( 'woocommerce_my_account' ) ) {
			return;
		}

		if ( is_user_logged_in() ) {
			return;
		}
		?>

		<div id="helendo-login-modal" class="login-modal helendo-modal woocommerce-account" tabindex="-1" role="dialog">
			<div id="off-login-layer" class="helendo-off-login-layer"></div>
			<div class="modal-content">
				<div class="container">
					<?php echo do_shortcode( '[woocommerce_my_account]' ) ?>
				</div>
			</div>
		</div>

		<?php
	}

endif;

add_action( 'wp_footer', 'helendo_login_modal' );

/**
 * Add newsletter popup on the footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'helendo_newsletter_popup' ) ) :
	function helendo_newsletter_popup() {
		if ( ! helendo_get_option( 'newsletter_popup' ) ) {
			return;
		}

		if ( ! intval( helendo_get_option( 'newsletter_home_popup' ) ) ) {
			if ( helendo_is_homepage() || is_front_page() ) {
				return;
			}
		}

		$helendo_newletter = '';
		if ( isset( $_COOKIE['helendo_newletter'] ) ) {
			$helendo_newletter = $_COOKIE['helendo_newletter'];
		}

		if ( ! empty( $helendo_newletter ) ) {
			return;
		}

		$output = array();

		if ( $desc = helendo_get_option( 'newsletter_content' ) ) {
			$output[] = sprintf( '<div class="n-desc">%s</div>', wp_kses( $desc, wp_kses_allowed_html( 'post' ) ) );
		}

		if ( $form = helendo_get_option( 'newsletter_form' ) ) {
			$output[] = sprintf( '<div class="n-form">%s</div>', do_shortcode( wp_kses( $form, wp_kses_allowed_html( 'post' ) ) ) );
		}

		$output[] = sprintf( '<a href="#" class="n-close">%s</a>', apply_filters( 'helendo_newsletter_notices', esc_html__( 'Don\'t show this popup again', 'helendo' ) ) );

		?>
		<div id="helendo-newsletter-popup" class="helendo-modal helendo-newsletter-popup" tabindex="-1"
			 aria-hidden="true">
			<div id="off-newsletter-layer" class="helendo-off-newsletter-layer"></div>
			<div class="modal-content">
				<a href="#" class="close-modal">
					<i class="icon-cross"></i>
				</a>

				<div class="newletter-content">
					<?php $image = helendo_get_option( 'newsletter_bg_image' );
					if ( $image ) {
						echo sprintf( '<div class="n-image" style="background-image:url(%s)"></div>', esc_url( $image ) );
					} ?>
					<div class="nl-inner">
						<?php echo implode( '', $output ) ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
endif;

add_action( 'wp_footer', 'helendo_newsletter_popup' );

/**
 * Display back to top
 *
 * @since 1.0.0
 */
function helendo_back_to_top() {
	if ( ! intval( helendo_get_option( 'back_to_top' ) ) ) {
		return;
	}

	$style = helendo_get_option( 'back_to_top_style' );
	?>
	<a id="scroll-top" class="backtotop style-<?php echo esc_attr( $style ); ?>" href="#">
		<i class="icon-arrow-up"></i>
	</a>
	<?php
}

add_action( 'wp_footer', 'helendo_back_to_top' );