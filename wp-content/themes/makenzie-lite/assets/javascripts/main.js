"use strict";

jQuery(document).ready(function($){

	// initiate foundation js
	$(document).foundation();

	$('.main-navigation li').mouseenter(function(){
		$(this).children('.dropdown-toggle:not(.toggle-on)').trigger('click');
		}).mouseleave(function(){
		$(this).children('.dropdown-toggle.toggle-on').trigger('click');
	});

	// Show header
	$('.site-header-wrapper, .menu-toggle').css('opacity', '1');

	function makenzie_slider5_nav() {
		var $windowSize = $(window).width();
		var $navNum = $('#slider-5-nav .slick-track div').size();

		if ($navNum == 1) {
			var $slider5navWidth = '160';
		} else if ($navNum == 2) {
			var $slider5navWidth = '305';
		} else if ($navNum == 3) {
			var $slider5navWidth = '450';
		} else if ($navNum >= 4) {
			var $slider5navWidth = '594';
		}

		var $slider5position = ($windowSize - $slider5navWidth) / 2;
		$('#slider-5-nav').css('width', $slider5navWidth);
		$('#slider-5-nav').css('left', $slider5position);

	}

	function makenzie_mobile_custom_bg() {
		var $windowSize = $(window).width();
		if ($windowSize < 970) {
			$('body').css({'background-color' : 'inherit'});
		}
	}

	function makenzie_elements_position() {

		var $windowSize = $(window).width();
		var $space = 45;
		var $headerHeight = $(".site-header-wrapper").height();
		var $headerouterHeight = $(".site-header-wrapper").outerHeight();
		var $headerHeight = $(".header-1,.header-2,.header-3").height();
		var $headerWidth = $(".header-1 .small-12,.header-2 .small-12,.header-3 .small-12").width();
		//var $logoWidth = $(".site-branding").outerWidth(true);
		//var $logoHeight = $(".site-branding").outerHeight();
		var $headerElementsHeight = $(".header-1-elements").height();
		//var $headerElementsPosition = ($logoHeight - $headerElementsHeight) / 2;
		//var $headerElementsWidth = ($headerWidth - $logoWidth) - 5;
		var $leftMenuWidth = $("#desktop-site-navigation-left").width();
		var $leftMenuHeight = $("#desktop-site-navigation-left").height();
		var $leftMenuHorizontalPosition = $space + $leftMenuWidth;
		var $leftMenuVerticalPosition = ($headerHeight - $leftMenuHeight) / 2;
		var $rightMenuWidth = $("#desktop-site-navigation-right").width()
		var $rightMenuHeight = $("#desktop-site-navigation-right").height();
		var $rightMenuHorizontalPosition = $space + $rightMenuWidth;
		var $rightMenuVerticalPosition = ($headerHeight - $rightMenuHeight) / 2;
		var $socialHeight = $("#social-header").height();
		var $socialPosition = ($headerouterHeight - $socialHeight) / 2;
		var $header2socialPosition = ($headerouterHeight - $socialHeight) / 2 + 20;
		var $header3socialPosition = ($headerouterHeight - $socialHeight) / 2 + 20;
		var $searchHeight = $(".site-header .search-form").height();
		var $searchPosition = ($headerouterHeight - $searchHeight) / 2;
		var $searchMobilePosition = ($headerHeight - $searchHeight) / 2 + 10;


		//$('.header-1-elements').css({'top' : $headerElementsPosition, 'width' : $headerElementsWidth});
		$('.header-2 #desktop-site-navigation-left').css({'left' : - $leftMenuHorizontalPosition, 'top' : $leftMenuVerticalPosition - 1});
		$('.header-2 #desktop-site-navigation-right').css({'right' : - $rightMenuHorizontalPosition, 'top' : $rightMenuVerticalPosition - 1});
		$('.header-2 #social-header').css('top', $header2socialPosition);
		$('.header-3 #social-header').css('top', $header3socialPosition);

		if ($windowSize > 970) {
			$('.header-2 .desktop-search .search-form').css('top', $searchPosition);
			$('.header-3 .desktop-search .search-form').css('top', $searchPosition);
		} else {
			$('.header-2 .desktop-search .search-form').css('top', $searchMobilePosition);
			$('.header-3 .desktop-search .search-form').css('top', $searchMobilePosition);
		}

	}

	function makenzie_slider_position() {

		var $slider1Width = $("#slider-1 .slider-content").width();
		var $slider1ContentWidth = $("#slider-1 .entry-title a").width();
		var $arrow1Height = $("#slider-1 .slick-arrow").height();
		var $arrow1HorizontalPosition = ((($slider1Width - $slider1ContentWidth) / 2) - $arrow1Height) / 2 ;
		$('#slider-1 .slick-prev').css('left', $arrow1HorizontalPosition);
		$('#slider-1 .slick-next').css('right', $arrow1HorizontalPosition);

		var $windowSize = $(window).width();
		var $slider4Width = $("#slider-4 .slider-content").width();
		var $arrow4Height = $("#slider-4 .slick-arrow").height();
		var $arrow4HorizontalPosition = ((($windowSize - $slider4Width) / 2) - $arrow4Height) / 2;
		$('#slider-4 .slick-prev').css('left', (-$arrow4HorizontalPosition) - 40);
		$('#slider-4 .slick-next').css('right', (-$arrow4HorizontalPosition) - 40);

	}
	makenzie_slider5_nav();
	//makenzie_logo_position();
	makenzie_mobile_custom_bg();
	makenzie_slider_position();
	makenzie_elements_position();


	$( window ).resize(function() {

		makenzie_slider5_nav();
		//makenzie_logo_position();
		makenzie_mobile_custom_bg();
		makenzie_slider_position();
		makenzie_elements_position();
	});

});