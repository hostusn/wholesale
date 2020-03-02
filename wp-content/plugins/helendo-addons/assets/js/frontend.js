(function ( $ ) {
	'use strict';

	var helendo = helendo || {};

	helendo.init = function () {
		helendo.$body = $( document.body ),
			helendo.$window = $( window ),
			helendo.$header = $( '#masthead' );

		this.videoLightBox();
		this.progressbarShortcode();
		this.imageCarousel();
		this.bannerCarousel();
		this.filterHandle();
		this.loadProducts();
		this.productsCarousel();
		this.instagramCarousel();
		this.toolTipIcon();
		this.eventCountDown();
		this.gmaps();
	};

	/*
	 * Toggle video banner play button
	 */
	helendo.videoLightBox = function () {

		var $images = $( '.mf-video-banner' );

		if ( !$images.length ) {
			return;
		}

		var $links = $images.find( 'a.photoswipe' ),
			items = [];

		$links.each( function () {
			var $a = $( this );

			items.push( {
				html: $a.data( 'href' )
			} );

		} );

		$images.on( 'click', 'a.photoswipe', function ( e ) {
			e.preventDefault();

			var index = $links.index( $( this ) ),
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
				$( '.mf-video-wrapper' ).find( 'iframe' ).each( function () {
					$( this ).attr( 'src', $( this ).attr( 'src' ) );
				} );
			} );
		} );
	};

	helendo.imageCarousel = function () {

		$.each( helendoShortCode.imageCarousel, function ( id, imagesData ) {
			var $sliders = $( document.getElementById( id ) );
			var $ic_font_size;

			if ( imagesData.ic_font_size ) {
				$ic_font_size = 'style="font-size:' + imagesData.ic_font_size + ';"';
			} else {
				$ic_font_size = '';
			}

			$sliders.not( '.slick-initialized' ).slick( {
				slidesToShow  : imagesData.slide,
				slidesToScroll: imagesData.scroll,
				infinite      : true,
				arrows        : imagesData.nav,
				autoplay      : imagesData.autoplay,
				autoplaySpeed : imagesData.speed,
				dots          : imagesData.dot,
				prevArrow     : '<div class="helendo-left-arrow"><span class="dl-icon-next svg-icon" ' + $ic_font_size + '><i class="icon-arrow-left"></i></span></div>',
				nextArrow     : '<div class="helendo-right-arrow"><span class="dl-icon-next svg-icon" ' + $ic_font_size + '><i class="icon-arrow-right"></i></span></div>',
				responsive    : [
					{
						breakpoint: 1200,
						settings  : {
							arrows: false,
							dots  : imagesData.m_dot
						}
					},
					{
						breakpoint: 768,
						settings  : {
							slidesToShow  : 3,
							slidesToScroll: 3,
							arrows        : false,
							dots          : imagesData.m_dot
						}
					},
					{
						breakpoint: 481,
						settings  : {
							slidesToShow  : imagesData.m_show,
							slidesToScroll: imagesData.m_show,
							arrows        : false,
							dots          : imagesData.m_dot
						}
					}
				]
			} );
		} );
	};

	helendo.bannerCarousel = function () {
		$.each( helendoShortCode.bannerCarousel, function ( id, imagesData ) {
			var $sliders = $( document.getElementById( id ) ),
				$container = $sliders.siblings( '.slider-arrows' ).find( '.container' );

			$sliders.imagesLoaded().always( function () {
				$sliders.on( 'init', function () {
					$sliders.closest( '.helendo_banners_carousel' ).addClass( 'slider-loaded' );
				} );

				$sliders.not( '.slick-initialized' ).slick( {
					slidesToShow : 1,
					infinite     : imagesData.autoplay,
					centerMode   : true,
					initialSlide : imagesData.initial,
					arrows       : imagesData.nav,
					autoplay     : imagesData.autoplay,
					autoplaySpeed: imagesData.speed,
					dots         : imagesData.dot,
					prevArrow    : '<div class="helendo-left-arrow"><i class="icon-arrow-left"></i></div>',
					nextArrow    : '<div class="helendo-right-arrow"><i class="icon-arrow-right"></i></div>',
					centerPadding: '16.4%',
					focusOnSelect: true,
					appendArrows : $container,
					responsive   : [
						{
							breakpoint: 1366,
							settings  : {
								arrows: false
							}
						},
						{
							breakpoint: 768,
							settings  : {
								arrows       : false,
								centerMode   : false,
								initialSlide : 0,
								centerPadding: '0%'
							}
						},
						{
							breakpoint: 481,
							settings  : {
								arrows      : false,
								centerMode  : false,
								initialSlide: 0
							}
						}
					]
				} );
			} );
		} );
	};

	helendo.progressbarShortcode = function () {
		$( '.helendo-progressbar ' ).waypoint( function () {
			$( '.helendo-progressbar  .line-progress' ).addClass( 'animate_progress' );

		}, { offset: '100%' } );
	};

	helendo.filterHandle = function () {
		var $parent = $( '.helendo-products' );

		if ( $parent.hasClass( 'filter-by-group' ) ) {
			$parent.find( '.filter li:first' ).addClass( 'active' );
		}

		$parent.on( 'click', '.filter li', function ( e ) {
			e.preventDefault();

			var $this = $( this ),
				$grid = $this.closest( '.helendo-products' );

			if ( $this.hasClass( 'active' ) ) {
				return;
			}

			$this.addClass( 'active' ).siblings( '.active' ).removeClass( 'active' );

			var filter = $this.attr( 'data-filter' ),
				$container = $grid.find( '.product-wrapper' );

			var data = {
				attr : $grid.data( 'attr' ),
				nonce: $grid.data( 'nonce' )
			};

			if ( $grid.hasClass( 'filter-by-group' ) ) {
				data.type = filter;
			} else {
				data.attr.category = filter;
			}

			if ( $grid.hasClass( 'helendo-products-carousel' ) ) {
				data.load_more = $grid.data( 'load_more' );
			}

			$grid.addClass( 'loading' );

			wp.ajax.send( 'helendo_load_products', {
				data   : data,
				success: function ( response ) {
					var css,
						$_products = $( response );

					if ( $grid.hasClass( 'helendo-products-carousel' ) ) {
						css = 'helendoFadeIn';
					} else {
						css = 'helendoFadeInUp';
					}

					$grid.removeClass( 'loading' );

					$_products.find( 'ul.products > li' ).addClass( 'product helendoAnimation ' + css );
					$container.children( 'div.woocommerce, .load-more' ).remove();
					$container.append( $_products );

					helendo.productsCarousel();
					helendo.toolTipIcon();
				}
			} );
		} );
	};

	/**
	 * Products
	 */
	helendo.loadProducts = function () {
		helendo.$body.on( 'click', '.ajax-load-products', function ( e ) {
			e.preventDefault();

			var $el = $( this ),
				page = $el.data( 'page' );

			if ( $el.hasClass( 'loading' ) ) {
				return;
			}

			$el.addClass( 'loading' );

			wp.ajax.send( 'helendo_load_products', {
				data: {
					page : page,
					type : $el.data( 'type' ),
					nonce: $el.data( 'nonce' ),
					attr : $el.data( 'attr' )
				},

				success: function ( data ) {
					$el.removeClass( 'loading' );
					var $data = $( data ),
						$products = $data.find( 'ul.products > li' ),
						$button = $data.find( '.ajax-load-products' ),
						$container = $el.closest( '.helendo-products' ),
						$grid = $container.find( 'ul.products' );

					// If has products
					if ( $products.length ) {
						// Add classes before append products to grid
						$products.addClass( 'product' );

						for ( var index = 0; index < $products.length; index++ ) {
							$( $products[index] ).css( 'animation-delay', index * 100 + 100 + 'ms' );
						}
						$products.addClass( 'helendoFadeInUp helendoAnimation' );
						$grid.append( $products );

						if ( $button.length ) {
							$el.replaceWith( $button );
						} else {
							$el.slideUp();
						}
					}

					helendo.toolTipIcon();
				}
			} );
		} );
	};

	helendo.productsCarousel = function () {

		if ( helendoShortCode.length === 0 || typeof helendoShortCode.productsCarousel === 'undefined' ) {
			return;
		}

		$.each( helendoShortCode.productsCarousel, function ( id, sliderData ) {
			var $sliders = $( document.getElementById( id ) ),

				$container = $sliders.find( 'ul.products' );

			var font_size = '';

			if ( sliderData.nav_size ) {
				font_size = ' style="font-size:' + sliderData.nav_size + '"';
			} else {
				font_size = '';
			}

			$container.imagesLoaded().always( function () {
				$container.not( '.slick-initialized' ).slick( {
					slidesToShow  : sliderData.show,
					slidesToScroll: sliderData.scroll,
					infinite      : true,
					arrows        : sliderData.nav,
					autoplay      : sliderData.autoplay,
					autoplaySpeed : sliderData.autoplay_speed,
					dots          : sliderData.dot,
					prevArrow     : '<div class="helendo-left-arrow"><i class="icon-arrow-left" ' + font_size + '></i></div>',
					nextArrow     : '<div class="helendo-right-arrow"><i class="icon-arrow-right" ' + font_size + '></i></div>',
					responsive    : [
						{
							breakpoint: 1200,
							settings  : {
								slidesToShow  : 3,
								slidesToScroll: 3,
								arrows        : false,
								dots          : sliderData.dot_mobile
							}
						},
						{
							breakpoint: 768,
							settings  : {
								slidesToShow  : 2,
								slidesToScroll: 2,
								arrows        : false,
								dots          : sliderData.dot_mobile
							}
						},
						{
							breakpoint: 480,
							settings  : {
								slidesToShow  : sliderData.m_show,
								slidesToScroll: sliderData.m_show,
								arrows        : false,
								dots          : sliderData.dot_mobile
							}
						}
					]
				} );
			} );
		} );
	};

	helendo.instagramCarousel = function () {
		var $slider = $( '.helendo-instagram-shortcode' ),
			$columns = $slider.data( 'columns' ),
			$mColumns = $slider.data( 'mobile' ),
			$container = $slider.find( 'ul.instagram-photos' );

		$container.not( '.slick-initialized' ).slick( {
			slidesToShow  : $columns,
			slidesToScroll: $columns,
			infinite      : true,
			arrows        : true,
			autoplay      : false,
			autoplaySpeed : 2000,
			dots          : false,
			prevArrow     : '<div class="helendo-left-arrow"><i class="icon-arrow-left"></i></div>',
			nextArrow     : '<div class="helendo-right-arrow"><i class="icon-arrow-right"></i></div>',
			responsive    : [
				{
					breakpoint: 1200,
					settings  : {
						slidesToShow  : $columns - 1,
						slidesToScroll: $columns - 1,
						arrows        : false,
						dots          : false
					}
				},
				{
					breakpoint: 768,
					settings  : {
						slidesToShow  : $mColumns,
						slidesToScroll: $mColumns,
						arrows        : false,
						dots          : false
					}
				},
				{
					breakpoint: 481,
					settings  : {
						slidesToShow  : parseInt( $mColumns ) > 1 ? parseInt( $mColumns - 1 ) : 1,
						slidesToScroll: parseInt( $mColumns ) > 1 ? parseInt( $mColumns - 1 ) : 1,
						arrows        : false,
						dots          : true
					}
				}
			]
		} );
	};

	/**
	 * Init Google maps
	 */
	helendo.gmaps = function () {

		if ( helendoShortCode.length === 0 || typeof helendoShortCode.map === 'undefined' ) {
			return;
		}

		var mapOptions = {
				scrollwheel       : false,
				draggable         : true,
				zoom              : 10,
				mapTypeId         : google.maps.MapTypeId.ROADMAP,
				panControl        : false,
				zoomControl       : true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL
				},
				scaleControl      : false,
				streetViewControl : false

			},
			customMap;

		$.each( helendoShortCode.map, function ( id, mapData ) {

			var styles =
				[
					{
						'featureType': 'all',
						'elementType': 'all',
						'stylers'    : [
							{
								'hue': '#e7ecf0'
							}
						]
					},
					{
						'featureType': 'poi',
						'elementType': 'all',
						'stylers'    : [
							{
								'visibility': 'off'
							}
						]
					},
					{
						'featureType': 'road',
						'elementType': 'all',
						'stylers'    : [
							{
								'saturation': -70
							}
						]
					},
					{
						'featureType': 'transit',
						'elementType': 'all',
						'stylers'    : [
							{
								'visibility': 'off'
							}
						]
					},
					{
						'featureType': 'water',
						'elementType': 'all',
						'stylers'    : [
							{
								'visibility': 'simplified'
							},
							{
								'saturation': -60
							}
						]
					}
				];

			customMap = new google.maps.StyledMapType( styles,
				{ name: 'Styled Map' } );

			var map,
				marker,
				location = new google.maps.LatLng( mapData.lat, mapData.lng );

			// Update map options
			mapOptions.zoom = parseInt( mapData.zoom, 10 );
			mapOptions.center = location;
			mapOptions.mapTypeControlOptions = {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP]
			};

			// Init map
			map = new google.maps.Map( document.getElementById( id ), mapOptions );

			// Create marker options
			var markerOptions = {
				map     : map,
				position: location
			};
			if ( mapData.marker ) {
				markerOptions.icon = {
					url: mapData.marker
				};
			}

			map.mapTypes.set( 'map_style', customMap );
			map.setMapTypeId( 'map_style' );

			// Init marker
			marker = new google.maps.Marker( markerOptions );

			//if (mapData.info) {
			//	var infoWindow = new google.maps.InfoWindow({
			//		content : '<div class="info-box mf-map">' + mapData.info + '</div>',
			//		maxWidth: 600
			//	});
			//
			//	google.maps.event.addListener(marker, 'click', function () {
			//		infoWindow.open(map, marker);
			//	});
			//}

		} );
	};

	// tooltip icon
	helendo.toolTipIcon = function () {
		$( '.helendo-product-thumbnail' ).find( '[data-rel=tooltip]' ).tooltip( {
			classes     : { 'ui-tooltip': 'helendo-tooltip' },
			tooltipClass: 'helendo-tooltip',
			position    : { my: 'center bottom', at: 'center top-13' },
			create      : function () {
				$( '.ui-helper-hidden-accessible' ).remove();
			}
		} );

		$( document.body ).on( 'added_to_cart', function () {
			$( '.helendo-product-thumbnail' ).find( '.added_to_cart' ).tooltip( {
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

	helendo.eventCountDown = function () {
		var $wrapper = $( '.helendo-time-format' );

		if ( $wrapper.length <= 0 ) {
			return;
		}

		$wrapper.each( function () {
			var $eventDate = $( this );

			var diff = $( this ).find( '.helendo-time-countdown' ).html();

			$eventDate.find( '.helendo-time-countdown' ).FlipClock( diff, {
				clockFace: 'DailyCounter',
				countdown: true,
				labels   : [helendoShortCode.days, helendoShortCode.hours, helendoShortCode.minutes, helendoShortCode.seconds]
			} );
		} );
	};

	/**
	 * Document ready
	 */
	$( function () {
		helendo.init();
	} );

})( jQuery );