(function ( $ ) {
	'use strict';

	var helendo = helendo || {};
	helendo.init = function () {
		helendo.$body = $( document.body ),
			helendo.$window = $( window ),
			helendo.$header = $( '#site-header' );

		// Add class
		this.addAndRemoveClass();
		this.scrollTop();
		this.newLetterPopup();

		// Header
		this.stickyHeader();
		this.canvasPanel();
		this.instanceSearch();
		this.menuSideBar();
		this.modalPopup();
		this.megaMenu();

		// Blog
		this.blogLayout();
		this.blogLoadingAjax();

		// Catalog
		this.addWishlist();
		this.productQuickView();
		this.productsLoadInfinite();
		this.productAttribute();
		this.showAddedToCartNotice();
		this.showFilterContent();
		this.filterAjax();
		this.filterScroll();
		this.toolTipIcon();
		this.catalogSorting();

		// Single Product
		this.productThumbnail();
		this.productVideo();
		this.productGallery();
		this.productQuantity();
		this.singleProductCarousel();
		this.productVariation();
		this.addToCartAjax();
		this.crossSellsProductCarousel();

		// login
		this.loginTab();

		// Footer
		this.fixedFooter();
	};

	helendo.addAndRemoveClass = function () {
		helendo.$header.find( '.header-items > div:last-child' ).addClass( 'last-child' );

		if ( helendo.$body.hasClass( 'helendo-search-form' ) ) {
			helendo.$window.on( 'resize', function () {
				if ( $( this ).width() < 1200 ) {
					helendo.$header.find( '.header-search' ).removeClass( 'form search-modal' );
				} else {
					helendo.$header.find( '.header-search' ).addClass( 'form search-modal' );
				}
			} );
		}
	};

	helendo.megaMenu = function () {
		helendo.$header.find( '#primary-menu .menu-item.is-mega-menu' ).each( function () {
			var wsubWidth = $( this ).children( '.dropdown-submenu' ).width(),
				parentWidth = $( this ).closest( '.helendo-header-container' ).width(),
				wWidth = $( this ).outerWidth(),
				offsetLeft = $( this ).position().left + (wWidth / 2),
				offsetRight = (parentWidth - $( this ).position().left) + (wWidth / 2),
				left = offsetLeft - (wsubWidth / 2),
				right = offsetRight - (wsubWidth / 2);

			if ( right < 0 ) {
				$( this ).removeClass( 'has-width' ).addClass( 'align-right' );
			} else if ( left < 0 ) {
				$( this ).removeClass( 'has-width' ).addClass( 'align-left' );
			}

		} );

	};

	// Scroll Top
	helendo.scrollTop = function () {
		var $scrollTop = $( '#scroll-top' );
		helendo.$window.scroll( function () {
			if ( helendo.$window.scrollTop() > helendo.$window.height() ) {
				$scrollTop.addClass( 'show-scroll' );
			} else {
				$scrollTop.removeClass( 'show-scroll' );
			}
		} );

		// Scroll effect button top
		$scrollTop.on( 'click', function ( event ) {
			event.preventDefault();
			$( 'html, body' ).stop().animate( {
					scrollTop: 0
				},
				800
			);
		} );

		// Home Boxed Scroll position
		if ( helendo.$body.hasClass( 'page-template-template-home-boxed' ) ) {
			var wWindow = helendo.$window.outerWidth( true ),
				right;

			if ( wWindow > 1330 ) {
				helendo.$window.on( 'resize', function () {
					right = ( wWindow - 1330 ) / 2 + 20;
					$scrollTop.css( 'right', right );
				} ).trigger( 'resize' );
			}
		}
	};

	// Toggle Menu Sidebar
	helendo.menuSideBar = function () {
		var $menuSidebar = $( '#menu-sidebar-panel, #header-left-sidebar' );
		$menuSidebar.find( '.menu .menu-item-has-children > a' ).prepend( '<span class="toggle-menu-children"><i class="arrow_triangle-down"></i></span>' );
		$menuSidebar.find( 'li.menu-item-has-children > a' ).on( 'click', function ( e ) {
			e.preventDefault();

			$( this ).closest( 'li' ).siblings().find( 'ul.sub-menu, ul.dropdown-submenu' ).slideUp();
			$( this ).closest( 'li' ).siblings().removeClass( 'active' );

			$( this ).closest( 'li' ).children( 'ul.sub-menu, ul.dropdown-submenu' ).slideToggle();
			$( this ).closest( 'li' ).toggleClass( 'active' );

		} );
	};

	/**
	 * Product instance search
	 */
	helendo.instanceSearch = function () {

		if ( helendoData.header_ajax_search != '1' ) {
			return;
		}

		var xhr = null,
			searchCache = {},
			$modal = $( '.search-modal' ),
			$form = $modal.find( 'form' ),
			$search = $form.find( 'input.search-field' ),
			$submit = $form.find( 'input.btn-submit' ),
			$results = $modal.find( '.search-results' );

		$modal.on( 'keyup', '.search-field', function ( e ) {
			var valid = false,
				$parent = $( this ).closest( '.search-modal' );

			if ( typeof e.which == 'undefined' ) {
				valid = true;
			} else if ( typeof e.which == 'number' && e.which > 0 ) {
				valid = !e.ctrlKey && !e.metaKey && !e.altKey;
			}

			if ( !valid ) {
				return;
			}

			if ( xhr ) {
				xhr.abort();
			}

			search( $parent );
		} ).on( 'change', '.product-cats input', function () {
			if ( xhr ) {
				xhr.abort();
			}

			var $parent = $( this ).closest( '.search-modal' );

			search( $parent );
		} ).on( 'focusout', '.search-field', function () {
			var $parent = $( this ).closest( '.search-modal' );

			if ( $search.val().length < 2 ) {
				$parent.removeClass( 'searching searched actived found-products found-no-product invalid-length' );
			}
		} );

		outSearch();

		/**
		 * Private function for search
		 */
		function search( $el ) {
			var keyword = $search.val(),
				cat = '';

			if ( $el.find( '.product-cats' ).length > 0 ) {
				cat = $el.find( '.product-cats input:checked' ).val();
			}

			if ( keyword.length < 2 ) {
				$el.removeClass( 'searching searched actived found-products found-no-product' ).addClass( 'invalid-length' );
				return;
			}

			$el.removeClass( 'found-products found-no-product' ).addClass( 'searching' );

			var keycat = keyword + cat;

			if ( keycat in searchCache ) {
				var result = searchCache[keycat];

				$el.removeClass( 'searching' );

				$el.addClass( 'found-products' );

				$results.find( '.woocommerce' ).html( result.products );

				$( document.body ).trigger( 'helendo_ajax_search_request_success', [$results] );

				$results.find( '.woocommerce, .buttons' ).slideDown( function () {
					$el.removeClass( 'invalid-length' );
				} );

				$el.addClass( 'searched actived' );

				helendo.toolTipIcon();

			} else {
				xhr = $.ajax( {
					url     : helendoData.ajax_url,
					dataType: 'json',
					method  : 'post',
					data    : {
						action     : 'helendo_search_products',
						nonce      : helendoData.nonce,
						term       : keyword,
						cat        : cat,
						search_type: helendoData.search_content_type
					},
					success : function ( response ) {
						var $products = response.data;

						$el.removeClass( 'searching' );

						$el.addClass( 'found-products' );

						$results.find( '.searched-items' ).html( $products );

						$results.find( '.searched-items' ).slideDown( function () {
							$el.removeClass( 'invalid-length' );
						} );

						$( document.body ).trigger( 'helendo_ajax_search_request_success', [$results] );

						// Cache
						searchCache[keycat] = {
							found   : true,
							products: $products
						};

						$el.addClass( 'searched actived' );

						helendo.toolTipIcon();
					}
				} );
			}
		}

		/**
		 * Private function for click out search
		 */
		function outSearch() {
			var $modal = $( '.header-search.search-modal' ),
				$search = $modal.find( 'input.search-field' );
			if ( $modal.length <= 0 ) {
				return;
			}

			helendo.$window.on( 'scroll', function () {
				if ( helendo.$window.scrollTop() > 10 ) {
					$modal.removeClass( 'show found-products searched' );
				}
			} );

			$( document ).on( 'click', function ( e ) {
				var target = e.target;
				if ( !$( target ).closest( '.header-search' ) ) {
					$modal.removeClass( 'searching searched found-products found-no-product invalid-length' );
				}
			} );

			$modal.on( 'click', '.search-icon', function ( e ) {
				if ( $modal.hasClass( 'actived' ) ) {
					e.preventDefault();
					$search.val( '' );
					$modal.removeClass( 'searching searched actived found-products found-no-product invalid-length' );
				}
			} );
		}

		$modal.on( 'click', '.view-more', function ( e ) {
			e.preventDefault();

			$submit.trigger( 'click' );
		} );
	};

	// Sticky Header
	helendo.stickyHeader = function () {

		if ( !helendo.$body.hasClass( 'header-sticky' ) ) {
			return;
		}

		helendo.$window.on( 'scroll', function () {
			var scrollTop = 20,
				scroll = helendo.$window.scrollTop(),
				hHeader = helendo.$header.outerHeight( true ),
				hHeaderVideo = $( '.header-video' ).outerHeight( true );

			scrollTop = scrollTop + hHeader + hHeaderVideo;

			if ( scroll > scrollTop ) {
				helendo.$header.addClass( 'minimized' );
				$( '#helendo-header-minimized' ).addClass( 'minimized' );
			} else {
				helendo.$header.removeClass( 'minimized' );
				$( '#helendo-header-minimized' ).removeClass( 'minimized' );
			}
		} );

		helendo.$window.on( 'resize', function () {
			var hHeader = helendo.$header.outerHeight( true ),
				$h = $( '#helendo-header-minimized' );

			if ( !helendo.$body.hasClass( 'header-transparent' ) ) {
				$h.height( hHeader );
			}
		} ).trigger( 'resize' );
	};

	// Canvas

	helendo.canvasPanel = function () {

		helendo.$window.on( 'resize', function () {
			if ( helendo.$window.width() > 1199 ) {
				helendo.$header.on( 'click', '[data-target="cart-panel"]', function ( e ) {
					e.preventDefault();
					helendo.openCanvasPanel( $( '#cart-panel' ) );
				} );
			} else {
				helendo.$header.on( 'click', '[data-mobil-target="cart-mobile-panel"]', function ( e ) {
					e.preventDefault();
					helendo.openCanvasPanel( $( '#cart-panel' ) );
				} );
			}
		} ).trigger( 'resize' );

		helendo.$header.on( 'click', '[data-target="menu-panel"]', function ( e ) {
			e.preventDefault();
			helendo.openCanvasPanel( $( '#menu-sidebar-panel' ) );
		} );

		helendo.$body.on( 'click', '#helendo-catalog-canvas-filter', function ( e ) {
			e.preventDefault();
			helendo.openCanvasPanel( $( '#helendo-shop-topbar' ) );
		} );

		helendo.$body.on( 'click', '#off-canvas-layer, .close-canvas-panel', function ( e ) {
			e.preventDefault();
			helendo.closeCanvasPanel();
		} );

		helendo.$header.on( 'click', '[data-target="search-modal"]', function ( e ) {
			e.preventDefault();
			helendo.openModal( $( '#search-modal' ) );
			$( '#search-modal' ).find( '.search-field' ).focus();
		} );

		helendo.$body.on( 'click', '.close-modal', function ( e ) {
			e.preventDefault();
			helendo.closeModal();
		} );
	};

	helendo.openCanvasPanel = function ( $panel ) {
		helendo.$body.addClass( 'open-canvas-panel' );
		$panel.addClass( 'open' );
	};

	helendo.closeCanvasPanel = function () {
		helendo.$body.removeClass( 'open-canvas-panel' );
		$( '.helendo-off-canvas-panel' ).removeClass( 'open' );
		$( '#helendo-shop-topbar' ).removeClass( 'open' );
	};

	/**
	 * Open modal
	 *
	 * @param $modal
	 */
	helendo.openModal = function ( $modal ) {
		helendo.$body.addClass( 'modal-open' );
		$modal.fadeIn();
		$modal.addClass( 'open' );
	};

	/**
	 * Close modal
	 */
	helendo.closeModal = function () {
		helendo.$body.removeClass( 'modal-open' );
		$( '.helendo-modal' ).fadeOut( function () {
			$( this ).removeClass( 'open' );
		} );
	};

	helendo.modalPopup = function () {
		helendo.$header.on( 'click', '#header-account-icon', function ( e ) {
			e.preventDefault();
			helendo.openModal( $( '#helendo-login-modal' ) );
		} );

		helendo.$body.on( 'click', '#off-login-layer', function ( e ) {
			e.preventDefault();
			helendo.closeModal();
		} );
	};

	// Blog isotope
	helendo.blogLayout = function () {
		if ( !helendo.$body.hasClass( 'blog-masonry' ) ) {
			return;
		}

		helendo.$body.imagesLoaded( function () {
			helendo.$body.find( '.helendo-post-list' ).isotope( {
				itemSelector: '.blog-masonry-wrapper',
				layoutMode  : 'masonry'
			} );

		} );
	};

	// Loading Ajax
	helendo.blogLoadingAjax = function () {

		if ( !helendo.$body.hasClass( 'helendo-blog-page' ) ) {
			return;
		}

		helendo.$window.on( 'scroll', function () {
			if ( helendo.$body.find( '#helendo-posts-loading' ).is( ':in-viewport' ) ) {
				helendo.$body.find( '#helendo-posts-loading' ).closest( 'a' ).click();
			}
		} ).trigger( 'scroll' );

		// Blog page
		helendo.$body.on( 'click', '#helendo-blog-previous-ajax a', function ( e ) {
			e.preventDefault();

			if ( $( this ).data( 'requestRunning' ) ) {
				return;
			}

			$( this ).data( 'requestRunning', true );


			var $posts = $( this ).closest( '.site-main' ),
				$postList = $posts.find( '.helendo-post-list' ),
				$pagination = $( this ).parents( '.navigation' ),
				$parent = $( this ).parent();

			$parent.addClass( 'loading' );

			$.get(
				$( this ).attr( 'href' ),
				function ( response ) {
					var content = $( response ).find( '.helendo-post-list' ).children( '.blog-wapper' ),
						$pagination_html = $( response ).find( '.navigation' ).html();

					$pagination.html( $pagination_html );
					content.addClass( 'animated helendoFadeInUp' );

					if ( helendo.$body.hasClass( 'blog-masonry' ) ) {
						content.imagesLoaded( function () {
							$postList.append( content ).isotope( 'insert', content );
							$pagination.find( 'a' ).data( 'requestRunning', false );
							$parent.addClass( 'loading' );
						} );
					} else {
						$postList.append( content );
						$pagination.find( 'a' ).data( 'requestRunning', false );
						$parent.addClass( 'loading' );
					}
				}
			);
		} );

	};

	/**
	 * Change product thumbnail
	 */
	helendo.productThumbnail = function () {
		var $gallery = $( '.woocommerce-product-gallery' ),
			$video = $gallery.find( '.helendo-product-video' ),
			$thumbnail = $gallery.find( '.flex-control-thumbs' );

		$gallery.imagesLoaded( function () {
			setTimeout( function () {
				if ( $thumbnail.length < 1 ) {
					return;
				}
				var columns = $gallery.data( 'columns' ),
					count = $thumbnail.find( 'li' ).length,
					vertical = true,
					prevArrow = '<span class="icon-chevron-up slick-prev-arrow"></span>',
					nextArrow = '<span class="icon-chevron-down slick-next-arrow"></span>';

				if ( helendoData.single_product_layout !== 'full-content' ) {
					vertical = false;
					prevArrow = '<span class="icon-chevron-left slick-prev-arrow"></span>',
						nextArrow = '<span class="icon-chevron-right slick-next-arrow"></span>';
				}

				if ( count > columns ) {
					$thumbnail.not( '.slick-initialized' ).slick( {
						slidesToShow  : columns,
						vertical      : vertical,
						slidesToScroll: 1,
						infinite      : false,
						prevArrow     : prevArrow,
						nextArrow     : nextArrow,
						responsive    : [
							{
								breakpoint: 1200,
								settings  : {
									vertical: false
								}
							},
							{
								breakpoint: 400,
								settings  : {
									slidesToShow: 3,
									vertical    : false
								}
							}
						]
					} );
				} else {
					$thumbnail.addClass( 'no-slick' );
				}

				if ( $video.length > 0 ) {
					$gallery.addClass( 'has-video' );
					if ( $gallery.hasClass( 'video-first' ) ) {
						$thumbnail.find( 'li' ).first().append( '<i class="i-video fa fa-play"></i>' );
					} else {
						$thumbnail.find( 'li' ).last().append( '<i class="i-video fa fa-play"></i>' );
					}
				}
			}, 100 );

		} );
	};

	/**
	 * Product Video
	 */
	helendo.productVideo = function () {
		var $gallery = $( '.woocommerce-product-gallery' ),
			$video = $gallery.find( '.helendo-product-video' ),
			$thumbnail = $gallery.find( '.flex-control-thumbs' );

		if ( $video.length < 1 ) {
			return;
		}

		var gallery_height = $gallery.find( '.woocommerce-product-gallery__image:not(.helendo-product-video)' ).height();

		if ( gallery_height > 0 ) {
			$gallery.height( gallery_height );
		}

		var found = false,
			last = false;

		$thumbnail.on( 'click', 'li', function () {

			var thumbsCount = $( this ).siblings().length,
				index = $( this ).index();

			last = true;
			if ( $gallery.hasClass( 'video-first' ) ) {
				if ( index == 0 ) {
					last = false;
					found = false;
				}

			} else {
				if ( index == thumbsCount ) {
					last = false;
					found = false;
				}

			}

			if ( !found && last ) {
				var $iframe = $video.find( 'iframe' ),
					$wp_video = $video.find( 'video.wp-video-shortcode' );

				if ( $iframe.length > 0 ) {
					$iframe.attr( 'src', $iframe.attr( 'src' ) );
				}
				if ( $wp_video.length > 0 ) {
					$wp_video[0].pause();
				}
				found = true;
			}

			return false;

		} );

		$thumbnail.find( 'li' ).on( 'click', '.i-video', function ( e ) {
			e.preventDefault();
			$( this ).closest( 'li' ).find( 'img' ).trigger( 'click' );
		} );
	};

	/**
	 * Show photoSwipe lightbox
	 */
	helendo.productGallery = function () {
		var $images = $( '.woocommerce-product-gallery' );

		if ( helendoData.product_gallery != '1' ) {
			$images.on( 'click', '.woocommerce-product-gallery__image', function ( e ) {
				e.preventDefault();
			} );

			return;
		}

		if ( !$images.length ) {
			return;
		}

		$images.on( 'click', '.woocommerce-product-gallery__image', function ( e ) {
			e.preventDefault();
			imagesPhotoPopup( $( this ) );

		} );


		function imagesPhotoPopup( $this ) {
			var items = [],
				$links = $this.closest( '.woocommerce-product-gallery' ).find( '.woocommerce-product-gallery__image' );
			$links.each( function () {
				var $el = $( this );
				if ( $el.hasClass( 'helendo-product-video' ) ) {
					items.push( {
						html: $el.find( '.helendo-video-content' ).html()
					} );

				} else {
					items.push( {
						src: $el.children( 'a' ).attr( 'href' ),
						w  : $el.find( 'img' ).attr( 'data-large_image_width' ),
						h  : $el.find( 'img' ).attr( 'data-large_image_height' )
					} );
				}

			} );

			var index = $images.find( '.flex-active-slide, .slick-current' ).index(),
				options = {
					index              : index,
					bgOpacity          : 0.85,
					showHideOpacity    : true,
					mainClass          : 'pswp--minimal-dark',
					barsSize           : { top: 0, bottom: 0 },
					captionEl          : false,
					fullscreenEl       : false,
					shareEl            : false,
					tapToClose         : true,
					tapToToggleControls: false
				};

			var lightBox = new PhotoSwipe( document.getElementById( 'pswp' ), window.PhotoSwipeUI_Default, items, options );
			lightBox.init();

			lightBox.listen( 'close', function () {
				$( '.helendo-video-wrapper' ).find( 'iframe' ).each( function () {
					$( this ).attr( 'src', $( this ).attr( 'src' ) );
				} );

				$( '.helendo-video-wrapper' ).find( 'video' ).each( function () {
					$( this )[0].pause();
				} );
			} );
		}
	};

	/**
	 * Change product quantity
	 */
	helendo.productQuantity = function () {
		helendo.$body.on( 'click', '.quantity .increase, .quantity .decrease', function ( e ) {
			e.preventDefault();

			var $this = $( this ),
				$qty = $this.siblings( '.qty' ),
				current = parseInt( $qty.val(), 10 ),
				min = parseInt( $qty.attr( 'min' ), 10 ),
				max = parseInt( $qty.attr( 'max' ), 10 );

			min = min ? min : 1;
			max = max ? max : current + 1;

			if ( $this.hasClass( 'decrease' ) && current > min ) {
				$qty.val( current - 1 );
				$qty.trigger( 'change' );
			}
			if ( $this.hasClass( 'increase' ) && current < max ) {
				$qty.val( current + 1 );
				$qty.trigger( 'change' );
			}
		} );
	};

	/**
	 * Related & upsell slider
	 */
	helendo.singleProductCarousel = function () {

		if ( !helendo.$body.hasClass( 'single-product' ) ) {
			return;
		}

		var $upsells = helendo.$body.find( '#helendo-upsells-products' ),
			$related = helendo.$body.find( '#helendo-related-products' ),
			$instagram = helendo.$body.find( '#helendo-product-instagram' ),
			upSellDot = false, relatedDot = false, instagramDot = false;

		if ( helendoData.product_carousel.upsells == '1' ) {
			upSellDot = true;
		}

		if ( helendoData.product_carousel.related == '1' ) {
			relatedDot = true;
		}

		if ( helendoData.product_carousel.instagram == '1' ) {
			instagramDot = true;
		}

		if ( $related.length > 0 ) {
			var $related_products = $related.find( '.products' ),
				$related_columns = $related.data( 'columns' );
			helendo.productsCarousel( $related_products, $related_columns, relatedDot );
		}


		if ( $upsells.length > 0 ) {
			var $upsells_products = $upsells.find( '.products' ),
				$upsells_columns = $upsells.data( 'columns' );
			helendo.productsCarousel( $upsells_products, $upsells_columns, upSellDot );
		}

		if ( $instagram.length > 0 ) {
			var $instagram_photos = $instagram.find( '.products' ),
				$instagram_columns = $instagram.data( 'columns' );
			helendo.productsCarousel( $instagram_photos, $instagram_columns, instagramDot );
		}

	};

	/**
	 * Related & upsell slider
	 */
	helendo.crossSellsProductCarousel = function () {

		var $crossSells = helendo.$body.find( '#helendo-cross-sells-products' );

		if ( $crossSells.length < 1 ) {
			return;
		}

		var $products = $crossSells.find( '.products' ),
			$columns = $crossSells.data( 'columns' ),
			dot = false;

		if ( helendoData.product_carousel.cross_sells == '1' ) {
			dot = true;
		}

		helendo.productsCarousel( $products, $columns, dot );

	};

	helendo.productsCarousel = function ( $products, columns, dot ) {
		$products.not( '.slick-initialized' ).slick( {
			slidesToShow  : parseInt( columns ),
			slidesToScroll: parseInt( columns ),
			arrows        : true,
			infinite      : false,
			prevArrow     : '<span class="icon-arrow-left slick-prev-arrow"></span>',
			nextArrow     : '<span class="icon-arrow-right slick-next-arrow"></span>',
			responsive    : [
				{
					breakpoint: 1367,
					settings  : {
						slidesToShow  : parseInt( columns ),
						slidesToScroll: parseInt( columns ),
						arrows        : false
					}
				},
				{
					breakpoint: 1200,
					settings  : {
						slidesToShow  : parseInt( columns ) > 4 ? 4 : parseInt( columns ),
						slidesToScroll: parseInt( columns ) > 4 ? 4 : parseInt( columns ),
						arrows        : false
					}
				},
				{
					breakpoint: 992,
					settings  : {
						slidesToShow  : 3,
						slidesToScroll: 3,
						arrows        : false
					}
				},
				{
					breakpoint: 768,
					settings  : {
						slidesToShow  : 2,
						slidesToScroll: 2,
						arrows        : false,
						dots          : dot
					}
				},
				{
					breakpoint: 481,
					settings  : {
						slidesToShow  : parseInt( helendoData.catalog_mobile_columns ),
						slidesToScroll: parseInt( helendoData.catalog_mobile_columns ),
						arrows        : false,
						dots          : dot
					}
				}
			]
		} );
	};

	helendo.productVariation = function () {
		var $form = $( '.variations_form' );
		helendo.$body.on( 'tawcvs_initialized', function () {
			$form.unbind( 'tawcvs_no_matching_variations' );
			$form.on( 'tawcvs_no_matching_variations', function ( event, $el ) {
				event.preventDefault();

				$form.find( '.woocommerce-variation.single_variation' ).show();
				if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
					$form.find( '.single_variation' ).slideDown( 200 ).html( '<p>' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>' );
				}
			} );

		} );

		$form.find( 'td.value' ).on( 'change', 'select', function () {
			var value = $( this ).find( 'option:selected' ).text();
			$( this ).closest( 'tr' ).find( 'td.label .helendo-attr-value' ).html( value );
		} );
	};

	// tooltip icon
	helendo.toolTipIcon = function () {
		$( document.body ).find( '.helendo-product-thumbnail' ).find( '[data-rel=tooltip]' ).tooltip( {
			classes     : { 'ui-tooltip': 'helendo-tooltip' },
			tooltipClass: 'helendo-tooltip',
			position    : { my: 'center bottom', at: 'center top-13' },
			create      : function () {
				$( '.ui-helper-hidden-accessible' ).remove();
			}
		} );

		$( document.body ).on( 'added_to_cart', function () {
			$( document.body ).find( '.helendo-product-thumbnail' ).find( '.added_to_cart' ).tooltip( {
				offsetTop   : -15,
				content     : function () {
					return $( this ).html();
				},
				classes     : { 'ui-tooltip': 'helendo-tooltip' },
				tooltipClass: 'helendo-tooltip',
				position    : { my: 'center bottom', at: 'center top-13' },
				create      : function () {
					$( '.ui-helper-hidden-accessible' ).remove();
				}
			} );
		} );
	};

	// add wishlist
	helendo.addWishlist = function () {
		$( 'ul.products li.product .yith-wcwl-add-button' ).on( 'click', 'a', function () {
			$( this ).addClass( 'loading' );
		} );

		helendo.$body.on( 'added_to_wishlist', function () {
			$( 'ul.products li.product .yith-wcwl-add-button a' ).removeClass( 'loading' );
		} );

		// update wishlist count
		helendo.$body.on( 'added_to_wishlist removed_from_wishlist', function () {
			$.ajax( {
				url     : helendoData.ajax_url,
				dataType: 'json',
				method  : 'post',
				data    : {
					action: 'update_wishlist_count'
				},
				success : function ( data ) {
					helendo.$header.find( '.header-wishlist .wishlist-counter' ).html( data );
				}
			} );
		} );
	};

	/**
	 * Toggle product quick view
	 */
	helendo.productQuickView = function () {

		helendo.$body.on( 'click', '.helendo-product-quick-view', function ( e ) {
			e.preventDefault();
			var $a = $( this );

			var url = $a.attr( 'href' ),
				$modal = $( '#quick-view-modal' ),
				$product = $modal.find( '.product' ),
				$product_sumary = $modal.find( '.product-summary' ),
				$product_images = $modal.find( '.product-images-wrapper' );

			$product.removeClass().addClass( 'invisible' );
			$product_sumary.html( '' );
			$product_images.html( '' );
			$modal.addClass( 'loading' );
			helendo.openModal( $modal );

			$.get( url, function ( response ) {
				var $html = $( response ),
					$response_summary = $html.find( '#content' ).find( '.entry-summary' ),
					$response_images = $html.find( '#content' ).find( '.woocommerce-product-gallery' ),
					$variations = $response_summary.find( '.variations_form' ),
					productClasses = $html.find( '.product' ).attr( 'class' );

				// Remove unused elements
				$product.addClass( productClasses );
				$product_sumary.html( $response_summary );
				$response_images.removeAttr( 'style' );
				$product_images.html( $response_images );

				var $carousel = $product_images.find( '.woocommerce-product-gallery__wrapper' );

				$modal.removeClass( 'loading' );
				$product.removeClass( 'invisible' );

				$carousel.not( '.slick-initialized' ).slick( {
					slidesToShow  : 1,
					slidesToScroll: 1,
					infinite      : false,
					prevArrow     : '<span class="icon-chevron-left slick-prev-arrow"></span>',
					nextArrow     : '<span class="icon-chevron-right slick-next-arrow"></span>'
				} );

				$carousel.imagesLoaded( function () {
					//Force height for images
					$carousel.addClass( 'loaded' );
				} );

				$carousel.find( '.woocommerce-product-gallery__image' ).on( 'click', 'a', function ( e ) {
					e.preventDefault();
				} );

				if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
					$variations.wc_variation_form();
					$variations.find( '.variations select' ).change();
				}

				if ( typeof $.fn.tawcvs_variation_swatches_form !== 'undefined' ) {
					$variations.tawcvs_variation_swatches_form();
				}

				helendo.productVariation();
				helendo.productGallery();

			}, 'html' );

		} );

		$( '#quick-view-modal' ).on( 'click', function ( e ) {
			var target = e.target;
			if ( $( target ).closest( 'div.product' ).length <= 0 ) {
				helendo.closeModal();
			}
		} );
	};

	// Loading Ajax
	helendo.productsLoadInfinite = function () {
		if ( !helendo.$body.hasClass( 'helendo-catalog-page' ) ) {
			return;
		}

		helendo.$window.on( 'scroll', function () {
			if ( helendo.$body.find( '#helendo-products-loading' ).is( ':in-viewport' ) ) {
				helendo.$body.find( '#helendo-products-loading' ).closest( '.next' ).click();
			}
		} ).trigger( 'scroll' );

		helendo.$body.on( 'click', '.woocommerce-pagination .next', function ( e ) {

			e.preventDefault();

			var $el = $( this );
			if ( $el.data( 'requestRunning' ) ) {
				return;
			}

			$el.data( 'requestRunning', true );

			var $pagination = $el.closest( '.woocommerce-pagination' ),
				$products = $pagination.prev( '.products' ),
				href = $el.closest( '.next' ).attr( 'href' );

			$.get(
				href,
				function ( response ) {
					var content = $( response ).find( 'ul.products' ).children( 'li.product' ),
						$pagination_html = $( response ).find( '.woocommerce-pagination' ).html();

					$pagination.html( $pagination_html );

					for ( var index = 0; index < content.length; index++ ) {
						$( content[index] ).css( 'animation-delay', index * 100 + 100 + 'ms' );
					}

					content.addClass( 'animated helendoFadeInUp' );

					$products.append( content );
					$pagination.find( '.next' ).data( 'requestRunning', false );
					$( document.body ).trigger( 'helendo_shop_ajax_loading_success' );

					helendo.toolTipIcon();
				}
			);
		} );
	};

	// Product Attribute
	helendo.productAttribute = function () {
		helendo.$body.on( 'click', '.helendo-swatch-variation-image', function ( e ) {
			e.preventDefault();
			$( this ).siblings( '.helendo-swatch-variation-image' ).removeClass( 'selected' );
			$( this ).addClass( 'selected' );
			var imgSrc = $( this ).data( 'src' ),
				imgSrcSet = $( this ).data( 'src-set' ),
				$mainImages = $( this ).closest( 'li.product' ).find( '.helendo-product-thumbnail' ),
				$image = $mainImages.find( 'img' ).first(),
				imgWidth = $image.first().width(),
				imgHeight = $image.first().height();

			$mainImages.addClass( 'image-loading' );
			$mainImages.css( {
				width  : imgWidth,
				height : imgHeight,
				display: 'block'
			} );

			$image.attr( 'src', imgSrc );

			if ( imgSrcSet ) {
				$image.attr( 'srcset', imgSrcSet );
			}

			$image.load( function () {
				$mainImages.removeClass( 'image-loading' );
				$mainImages.removeAttr( 'style' );
			} );
		} );
	};

	helendo.showAddedToCartNotice = function () {

		$( document.body ).on( 'added_to_cart', function ( event, fragments, cart_hash, $thisbutton ) {
			var product_title = $thisbutton.attr( 'data-title' ) + ' ' + helendoData.l10n.notice_text,
				$message = '';

			helendo.addedToCartNotice( $message, product_title, false, 'success' );

		} );
	};

	helendo.addedToCartNotice = function ( $message, $content, single, className ) {
		if ( helendoData.l10n.added_to_cart_notice != '1' || !$.fn.notify ) {
			return;
		}

		$message += '<a href="' + helendoData.l10n.cart_link + '" class="btn-button">' + helendoData.l10n.cart_text + '</a>';

		if ( single ) {
			$message = '<div class="message-box">' + $message + '</div>';
		}

		$.notify.addStyle( 'helendo', {
			html: '<div><i class="icon-checkmark-circle message-icon"></i><span data-notify-text/>' + $message + '<span class="close icon-cross2"></span> </div>'
		} );
		$.notify( $content, {
			autoHideDelay: helendoData.l10n.cart_notice_auto_hide,
			className    : className,
			style        : 'helendo',
			showAnimation: 'fadeIn',
			hideAnimation: 'fadeOut'
		} );
	};

	// Add to cart ajax
	helendo.addToCartAjax = function () {

		if ( helendoData.add_to_cart_ajax == '0' ) {
			return;
		}

		var found = false;
		helendo.$body.on( 'click', '.single_add_to_cart_button', function ( e ) {
			var $el = $( this ),
				$cartForm = $el.closest( 'form.cart' ),
				$productWrapper = $el.closest( 'div.product' );

			if ( $productWrapper.hasClass( 'product-type-external' ) ) {
				return;
			}

			if ( $cartForm.length > 0 ) {
				e.preventDefault();
			} else {
				return;
			}

			if ( $el.hasClass( 'disabled' ) ) {
				return;
			}

			$el.addClass( 'loading' );
			if ( found ) {
				return;
			}
			found = true;

			var formdata = $cartForm.serializeArray(),
				currentURL = window.location.href;

			if ( $el.val() != '' ) {
				formdata.push( { name: $el.attr( 'name' ), value: $el.val() } );
			}
			$.ajax( {
				url    : window.location.href,
				method : 'post',
				data   : formdata,
				error  : function () {
					window.location = currentURL;
				},
				success: function ( response ) {
					if ( !response ) {
						window.location = currentURL;
					}


					if ( typeof wc_add_to_cart_params !== 'undefined' ) {
						if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
							window.location = wc_add_to_cart_params.cart_url;
							return;
						}
					}

					$( document.body ).trigger( 'updated_wc_div' );

					var $message = '',
						className = 'success';
					if ( $( response ).find( '.woocommerce-message' ).length > 0 ) {
						$message = $( response ).find( '.woocommerce-message' ).html();
					}

					if ( $( response ).find( '.woocommerce-error' ).length > 0 ) {
						$message = $( response ).find( '.woocommerce-error' ).html();
						className = 'error';
					}

					if ( $( response ).find( '.woocommerce-info' ).length > 0 ) {
						$message = $( response ).find( '.woocommerce-info' ).html();
					}

					$el.removeClass( 'loading' );

					if ( $message ) {
						helendo.addedToCartNotice( $message, ' ', true, className );
					}

					found = false;

				}
			} );

		} );

	};

	helendo.loginTab = function () {
		var $tabs = $( '.helendo-tabs' );
		$tabs.each( function () {
			var $el = $( this ).find( '.tabs-nav a' ),
				$panels = $( this ).find( '.tabs-panel' );

			$el.on( 'click', function ( e ) {
				e.preventDefault();

				var $tab = $( this ),
					index = $tab.parent().index();

				if ( $tab.hasClass( 'active' ) ) {
					return;
				}

				$tabs.find( '.tabs-nav a' ).removeClass( 'active' );
				$tab.addClass( 'active' );
				$panels.removeClass( 'active' );
				$panels.filter( ':eq(' + index + ')' ).addClass( 'active' );
			} );
		} );
	};

	// Newsletter popup

	helendo.newLetterPopup = function () {
		var $modal = $( '#helendo-newsletter-popup' ),
			days = parseInt( helendoData.nl_days ),
			seconds = parseInt( helendoData.nl_seconds );

		if ( $modal.length < 1 ) {
			return;
		}

		helendo.$window.on( 'load', function () {
			setTimeout( function () {
				helendo.openModal( $modal );
			}, seconds * 1000 );
		} );

		$modal.on( 'click', '.close-modal', function ( e ) {
			e.preventDefault();
			closeNewsLetter( days );
			helendo.closeModal();
		} );

		$modal.on( 'click', '.n-close', function ( e ) {
			e.preventDefault();
			closeNewsLetter( 30 );
			helendo.closeModal();
		} );
	}

	helendo.addAndRemoveClass = function () {
		helendo.$header.find( '.header-items > div:last-child' ).addClass( 'last-child' );

		if ( helendo.$body.hasClass( 'helendo-search-form' ) ) {
			helendo.$window.on( 'resize', function () {
				if ( $( this ).width() < 1200 ) {
					helendo.$header.find( '.header-search' ).removeClass( 'form search-modal' );
				} else {
					helendo.$header.find( '.header-search' ).addClass( 'form search-modal' );
				}
			} );
		}
	};

	helendo.megaMenu = function () {
		helendo.$header.find( '#primary-menu .menu-item.is-mega-menu' ).each( function () {
			var wsubWidth = $( this ).children( '.dropdown-submenu' ).width(),
				parentWidth = $( this ).closest( '.helendo-header-container' ).width(),
				wWidth = $( this ).outerWidth(),
				offsetLeft = $( this ).position().left + (wWidth / 2),
				offsetRight = (parentWidth - $( this ).position().left) + (wWidth / 2),
				left = offsetLeft - (wsubWidth / 2),
				right = offsetRight - (wsubWidth / 2);

			if ( right < 0 ) {
				$( this ).removeClass( 'has-width' ).addClass( 'align-right' );
			} else if ( left < 0 ) {
				$( this ).removeClass( 'has-width' ).addClass( 'align-left' );
			}

		} );

	};

	// Scroll Top
	helendo.scrollTop = function () {
		var $scrollTop = $( '#scroll-top' );
		helendo.$window.scroll( function () {
			if ( helendo.$window.scrollTop() > helendo.$window.height() ) {
				$scrollTop.addClass( 'show-scroll' );
			} else {
				$scrollTop.removeClass( 'show-scroll' );
			}
		} );

		// Scroll effect button top
		$scrollTop.on( 'click', function ( event ) {
			event.preventDefault();
			$( 'html, body' ).stop().animate( {
					scrollTop: 0
				},
				800
			);
		} );

		// Home Boxed Scroll position
		if ( helendo.$body.hasClass( 'page-template-template-home-boxed' ) ) {
			var wWindow = helendo.$window.outerWidth( true ),
				right;

			if ( wWindow > 1330 ) {
				helendo.$window.on( 'resize', function () {
					right = (wWindow - 1330) / 2 + 20;
					$scrollTop.css( 'right', right );
				} ).trigger( 'resize' );
			}
		}
	};

	// Toggle Menu Sidebar
	helendo.menuSideBar = function () {
		var $menuSidebar = $( '#menu-sidebar-panel, #header-left-sidebar' );
		$menuSidebar.find( '.menu .menu-item-has-children > a' ).prepend( '<span class="toggle-menu-children"><i class="arrow_triangle-down"></i></span>' );
		$menuSidebar.find( 'li.menu-item-has-children > a' ).on( 'click', function ( e ) {
			e.preventDefault();

			$( this ).closest( 'li' ).siblings().find( 'ul.sub-menu, ul.dropdown-submenu' ).slideUp();
			$( this ).closest( 'li' ).siblings().removeClass( 'active' );

			$( this ).closest( 'li' ).children( 'ul.sub-menu, ul.dropdown-submenu' ).slideToggle();
			$( this ).closest( 'li' ).toggleClass( 'active' );

		} );
	};

	// Sticky Header
	helendo.stickyHeader = function () {

		if ( !helendo.$body.hasClass( 'header-sticky' ) ) {
			return;
		}

		helendo.$window.on( 'scroll', function () {
			var scrollTop = 20,
				scroll = helendo.$window.scrollTop(),
				hHeader = helendo.$header.outerHeight( true ),
				hHeaderVideo = $( '.header-video' ).outerHeight( true );

			scrollTop = scrollTop + hHeader + hHeaderVideo;

			if ( scroll > scrollTop ) {
				helendo.$header.addClass( 'minimized' );
				$( '#helendo-header-minimized' ).addClass( 'minimized' );
			} else {
				helendo.$header.removeClass( 'minimized' );
				$( '#helendo-header-minimized' ).removeClass( 'minimized' );
			}
		} );

		helendo.$window.on( 'resize', function () {
			var hHeader = helendo.$header.outerHeight( true ),
				$h = $( '#helendo-header-minimized' );

			if ( !helendo.$body.hasClass( 'header-transparent' ) ) {
				$h.height( hHeader );
			}
		} ).trigger( 'resize' );
	};

	// Canvas

	helendo.canvasPanel = function () {

		helendo.$window.on( 'resize', function () {
			if ( helendo.$window.width() > 1199 ) {
				helendo.$header.on( 'click', '[data-target="cart-panel"]', function ( e ) {
					e.preventDefault();
					helendo.openCanvasPanel( $( '#cart-panel' ) );
				} );
			} else {
				helendo.$header.on( 'click', '[data-mobil-target="cart-mobile-panel"]', function ( e ) {
					e.preventDefault();
					helendo.openCanvasPanel( $( '#cart-panel' ) );
				} );
			}
		} ).trigger( 'resize' );

		helendo.$header.on( 'click', '[data-target="menu-panel"]', function ( e ) {
			e.preventDefault();
			helendo.openCanvasPanel( $( '#menu-sidebar-panel' ) );
		} );

		helendo.$body.on( 'click', '#helendo-catalog-canvas-filter', function ( e ) {
			e.preventDefault();
			helendo.openCanvasPanel( $( '#helendo-shop-topbar' ) );
		} );

		helendo.$body.on( 'click', '#off-canvas-layer, .close-canvas-panel', function ( e ) {
			e.preventDefault();
			helendo.closeCanvasPanel();
		} );

		helendo.$header.on( 'click', '[data-target="search-modal"]', function ( e ) {
			e.preventDefault();
			helendo.openModal( $( '#search-modal' ) );
			$( '#search-modal' ).find( '.search-field' ).focus();
		} );

		helendo.$body.on( 'click', '.close-modal', function ( e ) {
			e.preventDefault();
			helendo.closeModal();
		} );
	};

	helendo.openCanvasPanel = function ( $panel ) {
		helendo.$body.addClass( 'open-canvas-panel' );
		$panel.addClass( 'open' );
	};

	helendo.closeCanvasPanel = function () {
		helendo.$body.removeClass( 'open-canvas-panel' );
		$( '.helendo-off-canvas-panel' ).removeClass( 'open' );
		$( '#helendo-shop-topbar' ).removeClass( 'open' );
	};

	/**
	 * Open modal
	 *
	 * @param $modal
	 */
	helendo.openModal = function ( $modal ) {
		helendo.$body.addClass( 'modal-open' );
		$modal.fadeIn();
		$modal.addClass( 'open' );
	};

	/**
	 * Close modal
	 */
	helendo.closeModal = function () {
		helendo.$body.removeClass( 'modal-open' );
		$( '.helendo-modal' ).fadeOut( function () {
			$( this ).removeClass( 'open' );
		} );
	};

	helendo.modalPopup = function () {
		helendo.$header.on( 'click', '#header-account-icon', function ( e ) {
			e.preventDefault();
			helendo.openModal( $( '#helendo-login-modal' ) );
		} );

		helendo.$body.on( 'click', '#off-login-layer', function ( e ) {
			e.preventDefault();
			helendo.closeModal();
		} );
	};

	// Blog isotope
	helendo.blogLayout = function () {
		if ( !helendo.$body.hasClass( 'blog-masonry' ) ) {
			return;
		}

		helendo.$body.imagesLoaded( function () {
			helendo.$body.find( '.helendo-post-list' ).isotope( {
				itemSelector: '.blog-masonry-wrapper',
				layoutMode  : 'masonry'
			} );

		} );
	};

	// Loading Ajax
	helendo.blogLoadingAjax = function () {

		if ( !helendo.$body.hasClass( 'helendo-blog-page' ) ) {
			return;
		}

		helendo.$window.on( 'scroll', function () {
			if ( helendo.$body.find( '#helendo-posts-loading' ).is( ':in-viewport' ) ) {
				helendo.$body.find( '#helendo-posts-loading' ).closest( 'a' ).click();
			}
		} ).trigger( 'scroll' );

		// Blog page
		helendo.$body.on( 'click', '#helendo-blog-previous-ajax a', function ( e ) {
			e.preventDefault();

			if ( $( this ).data( 'requestRunning' ) ) {
				return;
			}

			$( this ).data( 'requestRunning', true );


			var $posts = $( this ).closest( '.site-main' ),
				$postList = $posts.find( '.helendo-post-list' ),
				$pagination = $( this ).parents( '.navigation' ),
				$parent = $( this ).parent();

			$parent.addClass( 'loading' );

			$.get(
				$( this ).attr( 'href' ),
				function ( response ) {
					var content = $( response ).find( '.helendo-post-list' ).children( '.blog-wapper' ),
						$pagination_html = $( response ).find( '.navigation' ).html();

					$pagination.html( $pagination_html );
					content.addClass( 'animated helendoFadeInUp' );

					if ( helendo.$body.hasClass( 'blog-masonry' ) ) {
						content.imagesLoaded( function () {
							$postList.append( content ).isotope( 'insert', content );
							$pagination.find( 'a' ).data( 'requestRunning', false );
							$parent.addClass( 'loading' );
						} );
					} else {
						$postList.append( content );
						$pagination.find( 'a' ).data( 'requestRunning', false );
						$parent.addClass( 'loading' );
					}
				}
			);
		} );

	};

	/**
	 * Change product thumbnail
	 */
	helendo.productThumbnail = function () {
		var $gallery = $( '.woocommerce-product-gallery' ),
			$video = $gallery.find( '.helendo-product-video' ),
			$thumbnail = $gallery.find( '.flex-control-thumbs' );

		$gallery.imagesLoaded( function () {
			setTimeout( function () {
				if ( $thumbnail.length < 1 ) {
					return;
				}
				var columns = $gallery.data( 'columns' ),
					count = $thumbnail.find( 'li' ).length,
					vertical = true,
					prevArrow = '<span class="icon-chevron-up slick-prev-arrow"></span>',
					nextArrow = '<span class="icon-chevron-down slick-next-arrow"></span>';

				if ( helendoData.single_product_layout !== 'full-content' ) {
					vertical = false;
					prevArrow = '<span class="icon-chevron-left slick-prev-arrow"></span>',
						nextArrow = '<span class="icon-chevron-right slick-next-arrow"></span>';
				}

				if ( count > columns ) {
					$thumbnail.not( '.slick-initialized' ).slick( {
						slidesToShow  : columns,
						vertical      : vertical,
						slidesToScroll: 1,
						infinite      : false,
						prevArrow     : prevArrow,
						nextArrow     : nextArrow,
						responsive    : [
							{
								breakpoint: 1200,
								settings  : {
									vertical: false
								}
							},
							{
								breakpoint: 400,
								settings  : {
									slidesToShow: 3,
									vertical    : false
								}
							}
						]
					} );
				} else {
					$thumbnail.addClass( 'no-slick' );
				}

				if ( $video.length > 0 ) {
					$gallery.addClass( 'has-video' );
					if ( $gallery.hasClass( 'video-first' ) ) {
						$thumbnail.find( 'li' ).first().append( '<i class="i-video fa fa-play"></i>' );
					} else {
						$thumbnail.find( 'li' ).last().append( '<i class="i-video fa fa-play"></i>' );
					}
				}
			}, 100 );

		} );
	};

	/**
	 * Product Video
	 */
	helendo.productVideo = function () {
		var $gallery = $( '.woocommerce-product-gallery' ),
			$video = $gallery.find( '.helendo-product-video' ),
			$thumbnail = $gallery.find( '.flex-control-thumbs' );

		if ( $video.length < 1 ) {
			return;
		}

		var gallery_height = $gallery.find( '.woocommerce-product-gallery__image:not(.helendo-product-video)' ).height();

		if ( gallery_height > 0 ) {
			$gallery.height( gallery_height );
		}

		var found = false,
			last = false;

		$thumbnail.on( 'click', 'li', function () {

			var thumbsCount = $( this ).siblings().length,
				index = $( this ).index();

			last = true;
			if ( $gallery.hasClass( 'video-first' ) ) {
				if ( index == 0 ) {
					last = false;
					found = false;
				}

			} else {
				if ( index == thumbsCount ) {
					last = false;
					found = false;
				}

			}

			if ( !found && last ) {
				var $iframe = $video.find( 'iframe' ),
					$wp_video = $video.find( 'video.wp-video-shortcode' );

				if ( $iframe.length > 0 ) {
					$iframe.attr( 'src', $iframe.attr( 'src' ) );
				}
				if ( $wp_video.length > 0 ) {
					$wp_video[0].pause();
				}
				found = true;
			}

			return false;

		} );

		$thumbnail.find( 'li' ).on( 'click', '.i-video', function ( e ) {
			e.preventDefault();
			$( this ).closest( 'li' ).find( 'img' ).trigger( 'click' );
		} );
	};

	/**
	 * Show photoSwipe lightbox
	 */
	helendo.productGallery = function () {
		var $images = $( '.woocommerce-product-gallery' );

		if ( helendoData.product_gallery != '1' ) {
			$images.on( 'click', '.woocommerce-product-gallery__image', function ( e ) {
				e.preventDefault();
			} );

			return;
		}

		if ( !$images.length ) {
			return;
		}

		$images.on( 'click', '.woocommerce-product-gallery__image', function ( e ) {
			e.preventDefault();
			imagesPhotoPopup( $( this ) );

		} );


		function imagesPhotoPopup( $this ) {
			var items = [],
				$links = $this.closest( '.woocommerce-product-gallery' ).find( '.woocommerce-product-gallery__image' );
			$links.each( function () {
				var $el = $( this );
				if ( $el.hasClass( 'helendo-product-video' ) ) {
					items.push( {
						html: $el.find( '.helendo-video-content' ).html()
					} );

				} else {
					items.push( {
						src: $el.children( 'a' ).attr( 'href' ),
						w  : $el.find( 'img' ).attr( 'data-large_image_width' ),
						h  : $el.find( 'img' ).attr( 'data-large_image_height' )
					} );
				}

			} );

			var index = $images.find( '.flex-active-slide, .slick-current' ).index(),
				options = {
					index              : index,
					bgOpacity          : 0.85,
					showHideOpacity    : true,
					mainClass          : 'pswp--minimal-dark',
					barsSize           : { top: 0, bottom: 0 },
					captionEl          : false,
					fullscreenEl       : false,
					shareEl            : false,
					tapToClose         : true,
					tapToToggleControls: false
				};

			var lightBox = new PhotoSwipe( document.getElementById( 'pswp' ), window.PhotoSwipeUI_Default, items, options );
			lightBox.init();

			lightBox.listen( 'close', function () {
				$( '.helendo-video-wrapper' ).find( 'iframe' ).each( function () {
					$( this ).attr( 'src', $( this ).attr( 'src' ) );
				} );

				$( '.helendo-video-wrapper' ).find( 'video' ).each( function () {
					$( this )[0].pause();
				} );
			} );
		}
	};

	/**
	 * Change product quantity
	 */
	helendo.productQuantity = function () {
		helendo.$body.on( 'click', '.quantity .increase, .quantity .decrease', function ( e ) {
			e.preventDefault();

			var $this = $( this ),
				$qty = $this.siblings( '.qty' ),
				current = parseInt( $qty.val(), 10 ),
				min = parseInt( $qty.attr( 'min' ), 10 ),
				max = parseInt( $qty.attr( 'max' ), 10 );

			min = min ? min : 1;
			max = max ? max : current + 1;

			if ( $this.hasClass( 'decrease' ) && current > min ) {
				$qty.val( current - 1 );
				$qty.trigger( 'change' );
			}
			if ( $this.hasClass( 'increase' ) && current < max ) {
				$qty.val( current + 1 );
				$qty.trigger( 'change' );
			}
		} );
	};

	/**
	 * Related & upsell slider
	 */
	helendo.singleProductCarousel = function () {

		if ( !helendo.$body.hasClass( 'single-product' ) ) {
			return;
		}

		var $upsells = helendo.$body.find( '#helendo-upsells-products' ),
			$related = helendo.$body.find( '#helendo-related-products' ),
			$instagram = helendo.$body.find( '#helendo-product-instagram' ),
			upSellDot = false, relatedDot = false, instagramDot = false;

		if ( helendoData.product_carousel.upsells == '1' ) {
			upSellDot = true;
		}

		if ( helendoData.product_carousel.related == '1' ) {
			relatedDot = true;
		}

		if ( helendoData.product_carousel.instagram == '1' ) {
			instagramDot = true;
		}

		if ( $related.length > 0 ) {
			var $related_products = $related.find( '.products' ),
				$related_columns = $related.data( 'columns' );
			helendo.productsCarousel( $related_products, $related_columns, relatedDot );
		}


		if ( $upsells.length > 0 ) {
			var $upsells_products = $upsells.find( '.products' ),
				$upsells_columns = $upsells.data( 'columns' );
			helendo.productsCarousel( $upsells_products, $upsells_columns, upSellDot );
		}

		if ( $instagram.length > 0 ) {
			var $instagram_photos = $instagram.find( '.products' ),
				$instagram_columns = $instagram.data( 'columns' );
			helendo.productsCarousel( $instagram_photos, $instagram_columns, instagramDot );
		}

	};

	/**
	 * Related & upsell slider
	 */
	helendo.crossSellsProductCarousel = function () {

		var $crossSells = helendo.$body.find( '#helendo-cross-sells-products' );

		if ( $crossSells.length < 1 ) {
			return;
		}

		var $products = $crossSells.find( '.products' ),
			$columns = $crossSells.data( 'columns' ),
			dot = false;

		if ( helendoData.product_carousel.cross_sells == '1' ) {
			dot = true;
		}

		helendo.productsCarousel( $products, $columns, dot );

	};

	helendo.productsCarousel = function ( $products, columns, dot ) {
		$products.not( '.slick-initialized' ).slick( {
			slidesToShow  : parseInt( columns ),
			slidesToScroll: parseInt( columns ),
			arrows        : true,
			infinite      : false,
			prevArrow     : '<span class="icon-arrow-left slick-prev-arrow"></span>',
			nextArrow     : '<span class="icon-arrow-right slick-next-arrow"></span>',
			responsive    : [
				{
					breakpoint: 1367,
					settings  : {
						slidesToShow  : parseInt( columns ),
						slidesToScroll: parseInt( columns ),
						arrows        : false
					}
				},
				{
					breakpoint: 1200,
					settings  : {
						slidesToShow  : parseInt( columns ) > 4 ? 4 : parseInt( columns ),
						slidesToScroll: parseInt( columns ) > 4 ? 4 : parseInt( columns ),
						arrows        : false
					}
				},
				{
					breakpoint: 992,
					settings  : {
						slidesToShow  : 3,
						slidesToScroll: 3,
						arrows        : false
					}
				},
				{
					breakpoint: 768,
					settings  : {
						slidesToShow  : 2,
						slidesToScroll: 2,
						arrows        : false,
						dots          : dot
					}
				},
				{
					breakpoint: 481,
					settings  : {
						slidesToShow  : parseInt( helendoData.catalog_mobile_columns ),
						slidesToScroll: parseInt( helendoData.catalog_mobile_columns ),
						arrows        : false,
						dots          : dot
					}
				}
			]
		} );
	};

	helendo.productVariation = function () {
		var $form = $( '.variations_form' );
		helendo.$body.on( 'tawcvs_initialized', function () {
			$form.unbind( 'tawcvs_no_matching_variations' );
			$form.on( 'tawcvs_no_matching_variations', function ( event, $el ) {
				event.preventDefault();

				$form.find( '.woocommerce-variation.single_variation' ).show();
				if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
					$form.find( '.single_variation' ).slideDown( 200 ).html( '<p>' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>' );
				}
			} );

		} );

		$form.find( 'td.value' ).on( 'change', 'select', function () {
			var value = $( this ).find( 'option:selected' ).text();
			$( this ).closest( 'tr' ).find( 'td.label .helendo-attr-value' ).html( value );
		} );
	};

	// tooltip icon
	helendo.toolTipIcon = function () {
		$( document.body ).find( '.helendo-product-thumbnail' ).find( '[data-rel=tooltip]' ).tooltip( {
			classes     : { 'ui-tooltip': 'helendo-tooltip' },
			tooltipClass: 'helendo-tooltip',
			position    : { my: 'center bottom', at: 'center top-13' },
			create      : function () {
				$( '.ui-helper-hidden-accessible' ).remove();
			}
		} );

		$( document.body ).on( 'added_to_cart', function () {
			$( document.body ).find( '.helendo-product-thumbnail' ).find( '.added_to_cart' ).tooltip( {
				offsetTop   : -15,
				content     : function () {
					return $( this ).html();
				},
				classes     : { 'ui-tooltip': 'helendo-tooltip' },
				tooltipClass: 'helendo-tooltip',
				position    : { my: 'center bottom', at: 'center top-13' },
				create      : function () {
					$( '.ui-helper-hidden-accessible' ).remove();
				}
			} );
		} );
	};



	/**
	 * Toggle product quick view
	 */
	helendo.productQuickView = function () {

		helendo.$body.on( 'click', '.helendo-product-quick-view', function ( e ) {
			e.preventDefault();
			var $a = $( this );

			var url = $a.attr( 'href' ),
				$modal = $( '#quick-view-modal' ),
				$product = $modal.find( '.product' ),
				$product_sumary = $modal.find( '.product-summary' ),
				$product_images = $modal.find( '.product-images-wrapper' );

			$product.removeClass().addClass( 'invisible' );
			$product_sumary.html( '' );
			$product_images.html( '' );
			$modal.addClass( 'loading' );
			helendo.openModal( $modal );

			$.get( url, function ( response ) {
				var $html = $( response ),
					$response_summary = $html.find( '#content' ).find( '.entry-summary' ),
					$response_images = $html.find( '#content' ).find( '.woocommerce-product-gallery' ),
					$variations = $response_summary.find( '.variations_form' ),
					productClasses = $html.find( '.product' ).attr( 'class' );

				// Remove unused elements
				$product.addClass( productClasses );
				$product_sumary.html( $response_summary );
				$response_images.removeAttr( 'style' );
				$product_images.html( $response_images );

				var $carousel = $product_images.find( '.woocommerce-product-gallery__wrapper' );

				$modal.removeClass( 'loading' );
				$product.removeClass( 'invisible' );

				$carousel.not( '.slick-initialized' ).slick( {
					slidesToShow  : 1,
					slidesToScroll: 1,
					infinite      : false,
					prevArrow     : '<span class="icon-chevron-left slick-prev-arrow"></span>',
					nextArrow     : '<span class="icon-chevron-right slick-next-arrow"></span>'
				} );

				$carousel.imagesLoaded( function () {
					//Force height for images
					$carousel.addClass( 'loaded' );
				} );

				$carousel.find( '.woocommerce-product-gallery__image' ).on( 'click', 'a', function ( e ) {
					e.preventDefault();
				} );

				if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
					$variations.wc_variation_form();
					$variations.find( '.variations select' ).change();
				}

				if ( typeof $.fn.tawcvs_variation_swatches_form !== 'undefined' ) {
					$variations.tawcvs_variation_swatches_form();
				}

				helendo.productVariation();
				helendo.productGallery();

			}, 'html' );

		} );

		$( '#quick-view-modal' ).on( 'click', function ( e ) {
			var target = e.target;
			if ( $( target ).closest( 'div.product' ).length <= 0 ) {
				helendo.closeModal();
			}
		} );
	};

	// Loading Ajax
	helendo.productsLoadInfinite = function () {
		if ( !helendo.$body.hasClass( 'helendo-catalog-page' ) ) {
			return;
		}

		helendo.$window.on( 'scroll', function () {
			if ( helendo.$body.find( '#helendo-products-loading' ).is( ':in-viewport' ) ) {
				helendo.$body.find( '#helendo-products-loading' ).closest( '.next' ).click();
			}
		} ).trigger( 'scroll' );

		helendo.$body.on( 'click', '.woocommerce-pagination .next', function ( e ) {

			e.preventDefault();

			var $el = $( this );
			if ( $el.data( 'requestRunning' ) ) {
				return;
			}

			$el.data( 'requestRunning', true );

			var $pagination = $el.closest( '.woocommerce-pagination' ),
				$products = $pagination.prev( '.products' ),
				href = $el.closest( '.next' ).attr( 'href' );

			$.get(
				href,
				function ( response ) {
					var content = $( response ).find( 'ul.products' ).children( 'li.product' ),
						$pagination_html = $( response ).find( '.woocommerce-pagination' ).html();

					$pagination.html( $pagination_html );

					for ( var index = 0; index < content.length; index++ ) {
						$( content[index] ).css( 'animation-delay', index * 100 + 100 + 'ms' );
					}

					content.addClass( 'animated helendoFadeInUp' );

					$products.append( content );
					$pagination.find( '.next' ).data( 'requestRunning', false );
					$( document.body ).trigger( 'helendo_shop_ajax_loading_success' );

					helendo.toolTipIcon();
				}
			);
		} );
	};

	// Product Attribute
	helendo.productAttribute = function () {
		helendo.$body.on( 'click', '.helendo-swatch-variation-image', function ( e ) {
			e.preventDefault();
			$( this ).siblings( '.helendo-swatch-variation-image' ).removeClass( 'selected' );
			$( this ).addClass( 'selected' );
			var imgSrc = $( this ).data( 'src' ),
				imgSrcSet = $( this ).data( 'src-set' ),
				$mainImages = $( this ).closest( 'li.product' ).find( '.helendo-product-thumbnail' ),
				$image = $mainImages.find( 'img' ).first(),
				imgWidth = $image.first().width(),
				imgHeight = $image.first().height();

			$mainImages.addClass( 'image-loading' );
			$mainImages.css( {
				width  : imgWidth,
				height : imgHeight,
				display: 'block'
			} );

			$image.attr( 'src', imgSrc );

			if ( imgSrcSet ) {
				$image.attr( 'srcset', imgSrcSet );
			}

			$image.load( function () {
				$mainImages.removeClass( 'image-loading' );
				$mainImages.removeAttr( 'style' );
			} );
		} );
	};

	helendo.showAddedToCartNotice = function () {

		$( document.body ).on( 'added_to_cart', function ( event, fragments, cart_hash, $thisbutton ) {
			var product_title = $thisbutton.attr( 'data-title' ) + ' ' + helendoData.l10n.notice_text,
				$message = '';

			helendo.addedToCartNotice( $message, product_title, false, 'success' );

		} );
	};

	helendo.addedToCartNotice = function ( $message, $content, single, className ) {
		if ( helendoData.l10n.added_to_cart_notice != '1' || !$.fn.notify ) {
			return;
		}

		$message += '<a href="' + helendoData.l10n.cart_link + '" class="btn-button">' + helendoData.l10n.cart_text + '</a>';

		if ( single ) {
			$message = '<div class="message-box">' + $message + '</div>';
		}

		$.notify.addStyle( 'helendo', {
			html: '<div><i class="icon-checkmark-circle message-icon"></i><span data-notify-text/>' + $message + '<span class="close icon-cross2"></span> </div>'
		} );
		$.notify( $content, {
			autoHideDelay: helendoData.l10n.cart_notice_auto_hide,
			className    : className,
			style        : 'helendo',
			showAnimation: 'fadeIn',
			hideAnimation: 'fadeOut'
		} );
	};

	// Add to cart ajax
	helendo.addToCartAjax = function () {

		if ( helendoData.add_to_cart_ajax == '0' ) {
			return;
		}

		var found = false;
		helendo.$body.on( 'click', '.single_add_to_cart_button', function ( e ) {
			var $el = $( this ),
				$cartForm = $el.closest( 'form.cart' ),
				$productWrapper = $el.closest( 'div.product' );

			if ( $productWrapper.hasClass( 'product-type-external' ) ) {
				return;
			}

			if ( $cartForm.length > 0 ) {
				e.preventDefault();
			} else {
				return;
			}

			if ( $el.hasClass( 'disabled' ) ) {
				return;
			}

			$el.addClass( 'loading' );
			if ( found ) {
				return;
			}
			found = true;

			var formdata = $cartForm.serializeArray(),
				currentURL = window.location.href;

			if ( $el.val() != '' ) {
				formdata.push( { name: $el.attr( 'name' ), value: $el.val() } );
			}
			$.ajax( {
				url    : window.location.href,
				method : 'post',
				data   : formdata,
				error  : function () {
					window.location = currentURL;
				},
				success: function ( response ) {
					if ( !response ) {
						window.location = currentURL;
					}


					if ( typeof wc_add_to_cart_params !== 'undefined' ) {
						if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
							window.location = wc_add_to_cart_params.cart_url;
							return;
						}
					}

					$( document.body ).trigger( 'updated_wc_div' );

					var $message = '',
						className = 'success';
					if ( $( response ).find( '.woocommerce-message' ).length > 0 ) {
						$message = $( response ).find( '.woocommerce-message' ).html();
					}

					if ( $( response ).find( '.woocommerce-error' ).length > 0 ) {
						$message = $( response ).find( '.woocommerce-error' ).html();
						className = 'error';
					}

					if ( $( response ).find( '.woocommerce-info' ).length > 0 ) {
						$message = $( response ).find( '.woocommerce-info' ).html();
					}

					$el.removeClass( 'loading' );

					if ( $message ) {
						helendo.addedToCartNotice( $message, ' ', true, className );
					}

					found = false;

				}
			} );

		} );

	};

	helendo.loginTab = function () {
		var $tabs = $( '.helendo-tabs' );
		$tabs.each( function () {
			var $el = $( this ).find( '.tabs-nav a' ),
				$panels = $( this ).find( '.tabs-panel' );

			$el.on( 'click', function ( e ) {
				e.preventDefault();

				var $tab = $( this ),
					index = $tab.parent().index();

				if ( $tab.hasClass( 'active' ) ) {
					return;
				}

				$tabs.find( '.tabs-nav a' ).removeClass( 'active' );
				$tab.addClass( 'active' );
				$panels.removeClass( 'active' );
				$panels.filter( ':eq(' + index + ')' ).addClass( 'active' );
			} );
		} );

	};

	// Newsletter popup

	helendo.newLetterPopup = function () {
		var $modal = $( '#helendo-newsletter-popup' ),
			days = parseInt( helendoData.nl_days ),
			seconds = parseInt( helendoData.nl_seconds );

		if ( $modal.length < 1 ) {
			return;
		}

		helendo.$window.on( 'load', function () {
			setTimeout( function () {
				helendo.openModal( $modal );
			}, seconds * 1000 );
		} );

		$modal.on( 'click', '.close-modal', function ( e ) {
			e.preventDefault();
			closeNewsLetter( days );
			helendo.closeModal();
		} );

		$modal.on( 'click', '.n-close', function ( e ) {
			e.preventDefault();
			closeNewsLetter( 30 );
			helendo.closeModal();
		} );

		$modal.find( '.mc4wp-form' ).submit( function () {
			closeNewsLetter( days );
		} );

		function closeNewsLetter( days ) {
			var date = new Date(),
				value = date.getTime();

			date.setTime( date.getTime() + (days * 24 * 60 * 60 * 1000) );

			document.cookie = 'helendo_newletter=' + value + ';expires=' + date.toGMTString() + ';path=/';
		}
	};

	helendo.catalogSorting = function () {
		var $sortingMobile = $( '#helendo-catalog-sorting-mobile' );

		$( '#helendo-shop-toolbar-mobile' ).on( 'click', '.shop-toolbar__item--sort-by', function ( e ) {
			e.preventDefault();
			$sortingMobile.addClass( 'sort-by-active' );

		} );

		$sortingMobile.on( 'click', '.cancel-order', function ( e ) {
			e.preventDefault();
			$sortingMobile.removeClass( 'sort-by-active' );
		} );
	};

	/**
	 * Shop
	 */
		// Show Filter widget
	helendo.showFilterContent = function () {
		var $shopTopbar = $( '#helendo-shop-topbar' );

		helendo.$window.on( 'resize', function () {
			$shopTopbar.find( '.widget-title' ).next().removeAttr( 'style' );
		} ).trigger( 'resize' );

		$( '#helendo-catalog-toggle-filter' ).on( 'click', function ( e ) {
			e.preventDefault();
			$( this ).toggleClass( 'active' );
			$shopTopbar.slideToggle();
			$shopTopbar.toggleClass( 'active' );
			helendo.$body.toggleClass( 'show-filters-content' );
		} );
	};

	// Get price js slider
	helendo.priceSlider = function () {
		// woocommerce_price_slider_params is required to continue, ensure the object exists
		if ( typeof woocommerce_price_slider_params === 'undefined' ) {
			return false;
		}

		if ( $( '.catalog-sidebar' ).find( '.widget_price_filter' ).length <= 0 && $( '#helendo-shop-topbar' ).find( '.widget_price_filter' ).length <= 0 ) {
			return false;
		}

		// Get markup ready for slider
		$( 'input#min_price, input#max_price' ).hide();
		$( '.price_slider, .price_label' ).show();

		// Price slider uses jquery ui
		var min_price = $( '.price_slider_amount #min_price' ).data( 'min' ),
			max_price = $( '.price_slider_amount #max_price' ).data( 'max' ),
			current_min_price = parseInt( min_price, 10 ),
			current_max_price = parseInt( max_price, 10 );

		if ( $( '.price_slider_amount #min_price' ).val() != '' ) {
			current_min_price = parseInt( $( '.price_slider_amount #min_price' ).val(), 10 );
		}
		if ( $( '.price_slider_amount #max_price' ).val() != '' ) {
			current_max_price = parseInt( $( '.price_slider_amount #max_price' ).val(), 10 );
		}

		$( document.body ).bind( 'price_slider_create price_slider_slide', function ( event, min, max ) {
			if ( woocommerce_price_slider_params.currency_pos === 'left' ) {

				$( '.price_slider_amount span.from' ).html( woocommerce_price_slider_params.currency_symbol + min );
				$( '.price_slider_amount span.to' ).html( woocommerce_price_slider_params.currency_symbol + max );

			} else if ( woocommerce_price_slider_params.currency_pos === 'left_space' ) {

				$( '.price_slider_amount span.from' ).html( woocommerce_price_slider_params.currency_symbol + ' ' + min );
				$( '.price_slider_amount span.to' ).html( woocommerce_price_slider_params.currency_symbol + ' ' + max );

			} else if ( woocommerce_price_slider_params.currency_pos === 'right' ) {

				$( '.price_slider_amount span.from' ).html( min + woocommerce_price_slider_params.currency_symbol );
				$( '.price_slider_amount span.to' ).html( max + woocommerce_price_slider_params.currency_symbol );

			} else if ( woocommerce_price_slider_params.currency_pos === 'right_space' ) {

				$( '.price_slider_amount span.from' ).html( min + ' ' + woocommerce_price_slider_params.currency_symbol );
				$( '.price_slider_amount span.to' ).html( max + ' ' + woocommerce_price_slider_params.currency_symbol );

			}

			$( document.body ).trigger( 'price_slider_updated', [min, max] );
		} );
		if ( typeof $.fn.slider !== 'undefined' ) {
			$( '.price_slider' ).slider( {
				range  : true,
				animate: true,
				min    : min_price,
				max    : max_price,
				values : [current_min_price, current_max_price],
				create : function () {

					$( '.price_slider_amount #min_price' ).val( current_min_price );
					$( '.price_slider_amount #max_price' ).val( current_max_price );

					$( document.body ).trigger( 'price_slider_create', [current_min_price, current_max_price] );
				},
				slide  : function ( event, ui ) {

					$( 'input#min_price' ).val( ui.values[0] );
					$( 'input#max_price' ).val( ui.values[1] );

					$( document.body ).trigger( 'price_slider_slide', [ui.values[0], ui.values[1]] );
				},
				change : function ( event, ui ) {

					$( document.body ).trigger( 'price_slider_change', [ui.values[0], ui.values[1]] );
				}
			} );
		}
	};

	// Filter Ajax
	helendo.filterAjax = function () {

		if ( !helendo.$body.hasClass( 'catalog-ajax-filter' ) ) {
			return;
		}

		helendo.$body.on( 'price_slider_change', function ( event, ui ) {
			var form = $( '.price_slider' ).closest( 'form' ).get( 0 ),
				$form = $( form ),
				url = $form.attr( 'action' ) + '?' + $form.serialize();

			$( document.body ).trigger( 'helendo_catelog_filter_ajax', url, $( this ) );
		} );


		helendo.$body.on( 'click', '#remove-filter-actived', function ( e ) {
			e.preventDefault();
			var url = $( this ).attr( 'href' );
			$( document.body ).trigger( 'helendo_catelog_filter_ajax', url, $( this ) );
		} );

		helendo.$body.find( '#helendo-shop-toolbar' ).find( '.woocommerce-ordering' ).on( 'click', 'a', function ( e ) {
			e.preventDefault();
			$( this ).addClass( 'active' );
			var url = $( this ).attr( 'href' );
			$( document.body ).trigger( 'helendo_catelog_filter_ajax', url, $( this ) );
		} );

		helendo.$body.find( '#helendo-shop-topbar, .catalog-sidebar' ).on( 'click', 'a', function ( e ) {
			var $widget = $( this ).closest( '.widget' );
			if ( $widget.hasClass( 'widget_product_tag_cloud' ) ||
				$widget.hasClass( 'widget_product_categories' ) ||
				$widget.hasClass( 'widget_layered_nav_filters' ) ||
				$widget.hasClass( 'widget_layered_nav' ) ||
				$widget.hasClass( 'product-sort-by' ) ||
				$widget.hasClass( 'helendo-price-filter-list' ) ) {
				e.preventDefault();
				$( this ).closest( 'li' ).addClass( 'chosen' );
				var url = $( this ).attr( 'href' );
				$( document.body ).trigger( 'helendo_catelog_filter_ajax', url, $( this ) );
			}

			if ( $widget.hasClass( 'widget_product_tag_cloud' ) ) {
				$( this ).addClass( 'selected' );
			}

			if ( $widget.hasClass( 'product-sort-by' ) ) {
				$( this ).addClass( 'active' );
			}
		} );

		helendo.$body.find( '#helendo-shop-toolbar .helendo-products-cat' ).on( 'click', 'a', function ( e ) {
			e.preventDefault();

			$( this ).addClass( 'selected' );
			$( this ).closest( 'li' ).siblings( 'li' ).find( 'a' ).removeClass( 'selected' );
			var url = $( this ).attr( 'href' );
			$( document.body ).trigger( 'helendo_catelog_filter_ajax', url, $( this ) );
		} );

		helendo.$body.on( 'helendo_catelog_filter_ajax', function ( e, url, element ) {

			var $container = $( '#helendo-shop-content' ),
				$container_nav = $( '#primary-sidebar' ),
				$shopTopbar = $( '#helendo-shop-topbar' ),
				$shopToolbar = $( '#helendo-shop-toolbar' ),
				$ordering = $( '.shop-toolbar .woocommerce-ordering' ),
				$found = $( '.shop-toolbar .shop-toolbar__item--product-found' ),
				$pageHeader = $( '.page-header' ),
				$result = $( '.shop-toolbar .shop-toolbar__item--result' );

			if ( $shopToolbar.length > 0 ) {
				var position = $shopToolbar.offset().top - 260;
				$( 'html, body' ).stop().animate( {
						scrollTop: position
					},
					1200
				);
			}

			$( '.helendo-catalog-loading' ).addClass( 'show' );

			if ( '?' == url.slice( -1 ) ) {
				url = url.slice( 0, -1 );
			}

			url = url.replace( /%2C/g, ',' );

			history.pushState( null, null, url );

			$( document.body ).trigger( 'helendo_ajax_filter_before_send_request', [url, element] );

			if ( helendo.ajaxXHR ) {
				helendo.ajaxXHR.abort();
			}

			helendo.ajaxXHR = $.get( url, function ( res ) {

				var $sContent = $( res ).find( '#helendo-shop-content' ).length > 0 ? $( res ).find( '#helendo-shop-content' ).html() : '';

				$container.html( $sContent );
				$container_nav.html( $( res ).find( '#primary-sidebar' ).html() );
				$shopTopbar.html( $( res ).find( '#helendo-shop-topbar' ).html() );

				if ( $( res ).find( '.shop-toolbar .woocommerce-ordering' ).length > 0 ) {
					$ordering.html( $( res ).find( '.shop-toolbar .woocommerce-ordering' ).html() );
				}

				$found.html( $( res ).find( '.shop-toolbar .shop-toolbar__item--product-found' ).html() );
				$result.html( $( res ).find( '.shop-toolbar .shop-toolbar__item--result' ).html() );
				$pageHeader.html( $( res ).find( '.page-header-catalog' ).html() );

				helendo.priceSlider();

				$( '.helendo-catalog-loading' ).removeClass( 'show' );

				$( document.body ).trigger( 'helendo_ajax_filter_request_success', [res, url] );

				helendo.toolTipIcon();

			}, 'html' );
		} );
	};

	helendo.filterScroll = function () {
		var $sidebar = $( '.catalog-sidebar' ),
			$categories = $( '.helendo_widget_product_categories > ul', $sidebar ),
			$filter = $( '.helendo_attributes_filter > ul', $sidebar );

		helendo.filterElement( $categories );
		helendo.filterElement( $filter );
	};

	helendo.filterElement = function ( $element ) {
		var h = $element.outerHeight( true ),
			dataHeight = $element.data( 'height' );

		if ( h > dataHeight ) {
			$element.addClass( 'scroll-enable' );
			$element.css( 'height', dataHeight );
		}
	};

	// Fixed Footer
	helendo.fixedFooter = function () {
		var $footer = $( '#site-footer' );

		if ( $footer.length <= 0 ) {
			$footer.addClass( 'no-sticky' );
			return;
		}

		if ( helendo.$body.height() < helendo.$window.height() ) {
			$footer.addClass( 'no-sticky' );
			return;
		}

		if ( !helendo.$body.hasClass( 'footer-fixed' ) ) {
			$footer.addClass( 'no-sticky' );
			return;
		}

		helendo.$window.on( 'resize', function () {
			var fHeight = $footer.outerHeight( true ),
				wHeight = helendo.$window.height(),
				pBottom = 0;

			if ( fHeight > wHeight ) {
				$footer.addClass( 'no-sticky' );
			} else {
				$footer.removeClass( 'no-sticky' );
				pBottom = fHeight;
			}

			if ( helendo.$window.width() < 1200 ) {
				pBottom = 0;
			}

			helendo.$body.css( 'margin-bottom', pBottom );

		} ).trigger( 'resize' );
	};

	/**
	 * Document ready
	 */
	$( function () {
		helendo.init();
	} );

})( jQuery );