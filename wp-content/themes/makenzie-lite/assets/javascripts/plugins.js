"use strict";

/**
 * File plugins.js.
 */
( function( $ ) {

	var autoplay1 = $('#slider-1').data('autoplay');
	if (autoplay1 > 0) {
		var autoState1 = true;
	} else {
		var autoState1 = false;
	}

	$('#slider-1 .slider-content')
	.on('init', function(slick) {
	$('#slider-1 .slider-content').fadeTo( 600 , 1, function() {
	});
	})
	.slick({
		arrows: true,
		dots: false,
		fade: true,
		autoplay: autoState1,
 		autoplaySpeed: autoplay1,
		adaptiveHeight: true,
		mobileFirst: true,
		cssEase: 'linear'
	});


	// Wrap centered images in a new figure element

	$('img.aligncenter').wrap('<figure class="centered-image"></figure>');

	// Toggle visibility for the search form

	var $searchform = $('.site-header .search-form');

	$('.search-toggle').click(function() {
		$(this).toggleClass('search-toggle-active');
		$('.custom-logo-link, .site-title').toggleClass('hide-logo');
		if($($searchform).css('opacity') == 0) {
		    $($searchform).fadeTo( 400 , 1, function() {
		$('.site-header .search-form input').focus();
		});
		} else {
			 $($searchform).fadeTo( 400 , 0, function() {
			 	 $($searchform).css("display", "none");
		});
		}
	});

} )( jQuery );