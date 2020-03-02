jQuery( document ).ready( function ( $ ) {
	"use strict";

	// Show/hide settings for post format when choose post format
	var $format = $( '#post-formats-select' ).find( 'input.post-format' ),
		$formatBox = $( '#post-format-settings' );

	$format.on( 'change', function () {
        var type = $(this).filter(':checked').val();
        postFormatSettings(type);
	} );
	$format.filter( ':checked' ).trigger( 'change' );

    $(document.body).on('change', '.editor-post-format .components-select-control__input', function () {
        var type = $(this).val();
        postFormatSettings(type);
    });

    $(window).load(function () {
        var $el = $(document.body).find('.editor-post-format .components-select-control__input'),
            type = $el.val();
        postFormatSettings(type);
    });

    function postFormatSettings(type) {
        $formatBox.hide();
        if ($formatBox.find('.rwmb-field').hasClass(type)) {
            $formatBox.show();
        }

        $formatBox.find('.rwmb-field').slideUp();
        $formatBox.find('.' + type).slideDown();
    }

	// Show/hide settings for template settings
	$( '#page_template' ).on( 'change', function () {

        pageHeaderSettings($(this));

	} ).trigger( 'change' );

    $(document.body).on('change', '.editor-page-attributes__template .components-select-control__input', function () {
        pageHeaderSettings($(this));
    });

    $(window).load(function () {
        var $el = $(document.body).find('.editor-page-attributes__template .components-select-control__input');
        pageHeaderSettings($el);
    });

    function pageHeaderSettings($el) {

        if (
            $el.val() == 'template-home-page.php' ||
            $el.val() == 'template-home-boxed.php' ||
            $el.val() == 'homepage-fullwidth.php' ||
            $el.val() == 'template-home-left-sidebar.php'
        ) {
            $( '#page-header-settings' ).hide();
        } else {
            $( '#page-header-settings' ).show();
        }

        if (
            $el.val() == 'template-home-page.php' ||
            $el.val() == 'template-home-boxed.php' ||
            $el.val() == 'homepage-fullwidth.php' ||
            $el.val() == 'template-home-left-sidebar.php'
        ) {
            $( '#header-video' ).show();
        } else {
            $( '#header-video' ).hide();
        }

        if ( $el.val() == 'template-home-left-sidebar.php' ) {
            $( '#header-left-sidebar-item' ).show();
        } else {
            $( '#header-left-sidebar-item' ).hide();
        }

        if ( $el.val() == 'template-home-boxed.php' ) {
            $( '#boxed-settings' ).show();
        } else {
            $( '#boxed-settings' ).hide();
        }
    }
} );
