<?php

/**
 * Register the options
 */
function makenzie_lite_customizer_register_options( $options ) {

	$prefix = MAKENZIE_LITE_CUSTOMIZER_PREPEND;


	// General imagecolorset

	// Top Bar Options
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'colors',
		'title' => __( 'Colors', 'makenzie-lite' ),
	);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Accent Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'accent_color',
			'def'	=> '#c99d6e',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Text Link Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'text_link_color',
			'def'	=> '#c99d6e',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Text Link Color Hover', 'makenzie-lite' ),
			'id'	=> $prefix . 'text_link_color_hover',
			'def'	=> '#000',
		);


	// Top Bar Options
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'makenzie_lite_top_bar_options',
		'title' => __( '- Toolbar Options & Colors', 'makenzie-lite' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'on' => __( 'On', 'makenzie-lite' ),
				'off' => __( 'Off', 'makenzie-lite' ),
			),
			'title' => __( 'Display Toolbar', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_on_off',
			'def'	=> 'on',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'on' => __( 'On', 'makenzie-lite' ),
				'off' => __( 'Off', 'makenzie-lite' ),
			),
			'title' => __( 'Display Social Icons', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_social_on_off',
			'def'	=> 'off',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'on' => __( 'On', 'makenzie-lite' ),
				'off' => __( 'Off', 'makenzie-lite' ),
			),
			'title' => __( 'Display Menu', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_menu_on_off',
			'def'	=> 'on',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'on' => __( 'On', 'makenzie-lite' ),
				'off' => __( 'Off', 'makenzie-lite' ),
			),
			'title' => __( 'Display Search Field', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_search_on_off',
			'def'	=> 'off',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'left' => __( 'Left', 'makenzie-lite' ),
				'center' => __( 'Center', 'makenzie-lite' ),
				'right' => __( 'Right', 'makenzie-lite' ),
			),
			'title' => __( 'Top Bar Menu Alignment', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_alignment',
			'def'	=> 'center',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Toolbar Padding', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_padding',
			'def'	=> '0 0',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Toolbar Sub-Nav Margin-Top', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_sub_margin',
			'def'	=> '42px',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Background Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_bg_color',
			'def'	=> '#000',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_nav_text_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Hover/Active Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_hover_text_color',
			'def'	=> '#c99d6e',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Arrow Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_arrow_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Sub-Nav Background Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_nav_background_color',
			'def'	=> '#1a1b1d',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Sub-Nav Border Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_sub_nav_border_color',
			'def'	=> '#1a1b1d',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Sub-Nav Separator Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_sub_nav_separator_color',
			'def'	=> '#3d3b3b',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Sub-Nav Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_sub_nav_text_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Sub-Nav Hover/Active Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_sub_nav_hover_text_color',
			'def'	=> '#999',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Sub-Nav Arrow Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_bar_sub_nav_arrow_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Social Icons Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_social_icons_color',
			'def'	=> '#dd9933',
		);

			$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Social Icons Hover Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_social_icons_hover_color',
			'def'	=> '#999',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Search Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_search_text_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Toolbar Search Text Active Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'top_search_text_active_color',
			'def'	=> '#fff',
		);

	// Header Options & Colors
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'makenzie_lite_header_options',
		'title' => __( '- Header Options & Colors', 'makenzie-lite' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'header-1' => __( 'Header 1', 'makenzie-lite' ),
			),
			'title' => __( 'Header Style', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_style',
			'def'	=> 'header-1',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'on' => __( 'On', 'makenzie-lite' ),
				'off' => __( 'Off', 'makenzie-lite' ),
			),
			'title' => __( 'Display Search Box', 'makenzie-lite' ),
			'id'	=> $prefix . 'search_on_off',
			'def'	=> 'on',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'on' => __( 'On', 'makenzie-lite' ),
				'off' => __( 'Off', 'makenzie-lite' ),
			),
			'title' => __( 'Display Social Icons', 'makenzie-lite' ),
			'id'	=> $prefix . 'social_on_off',
			'def'	=> 'on',
		);


		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Header Background Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_bg_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Header 2 Navigation Background Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_3_bg_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Header 2 Navigation Border Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_3_border_color',
			'def'	=> '#f3f2f1',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Site Title Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'site_title_color',
			'def'	=> '#000',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Site Tagline Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'site_tagline_color',
			'def'	=> '#c0711e',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Header Social Icons Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'social_icons_color',
			'def'	=> '#000',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Header Social Icons Hover Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'social_icons_hover_color',
			'def'	=> '#c0711e',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Header Search Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'search_text_color',
			'def'	=> '#666',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Header Search Text Active Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'search_text_active_color',
			'def'	=> '#444',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Header Search Background Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'search_background_color',
			'def'	=> '#f7f7f7',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Navigation Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_nav_text_color',
			'def'	=> '#222',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Navigation Hover/Active Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_nav_hover_text_color',
			'def'	=> '#d47d22',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Navigation Arrow Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_nav_arrow_color',
			'def'	=> '#222',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Sub-Nav Background Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_sub_nav_background_color',
			'def'	=> '#1a1b1d',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Sub-Nav Border Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_sub_nav_border_color',
			'def'	=> '#1a1b1d',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Sub-Nav Separator Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_sub_nav_separator_color',
			'def'	=> '#303030',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Sub-Nav Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_sub_nav_text_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Sub-Nav Hover/Active Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_sub_nav_hover_text_color',
			'def'	=> '#999',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Desktop Sub-Nav Arrow Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'desktop_sub_nav_arrow_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Menu Toggle Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_toggle_color',
			'def'	=> '#999',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Menu Background Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_bg_color',
			'def'	=> '#000',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Menu Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_text_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Menu Hover / Current Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_hover_color',
			'def'	=> '#333',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Menu Separator Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_separator_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Menu Arrow Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_arrow_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Social Icons Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_social_icons_color',
			'def'	=> '#fff',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Social Icons Hover Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_social_icons_hover_color',
			'def'	=> '#999',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Search Text Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_search_text_color',
			'def'	=> '#a9a9a9',
		);

		$options[] = array(
			'type'	=> 'option_color',
			'title' => __( 'Mobile Search Text Active Color', 'makenzie-lite' ),
			'id'	=> $prefix . 'mobile_search_text_active_color',
			'def'	=> '#fff',
		);


	// Homepage Layout Options
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'makenzie_lite_homepage_options',
		'title' => __( '- Other Options', 'makenzie-lite' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'wide' => __( 'Wide Layout', 'makenzie-lite' ),
				'boxed' => __( 'Boxed Layout', 'makenzie-lite' ),
			),
			'title' => __( 'Wide / Boxed Layout', 'makenzie-lite' ),
			'id'	=> $prefix . 'wide_boxed',
			'def'	=> 'boxed',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Boxed Layout Spacing Top', 'makenzie-lite' ),
			'id'	=> $prefix . 'boxed_padding',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Boxed Layout Border Radius', 'makenzie-lite' ),
			'id'	=> $prefix . 'boxed_radius',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'post-s1' => __( 'Listing Style 1', 'makenzie-lite' ),
				'post-s2' => __( 'Listing Style 2', 'makenzie-lite' ),
				'post-s3' => __( 'Listing Style 3', 'makenzie-lite' ),
				'post-s4' => __( 'Listing Style 4', 'makenzie-lite' ),
				'post-s5' => __( 'Listing Style 5', 'makenzie-lite' ),
			),
			'title' => __( 'Posts Listing Style (Blog Template)', 'makenzie-lite' ),
			'id'	=> $prefix . 'posts_style_template',
			'def'	=> 'post-s1',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'on' => __( 'On', 'makenzie-lite' ),
				'off' => __( 'Off', 'makenzie-lite' ),
			),
			'title' => __( 'Sidebar On/Off', 'makenzie-lite' ),
			'id'	=> $prefix . 'sidebar_on_off',
			'def'	=> 'on',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'right' => __( 'Right', 'makenzie-lite' ),
				'left' => __( 'Left', 'makenzie-lite' ),
			),
			'title' => __( 'Sidebar Position', 'makenzie-lite' ),
			'id'	=> $prefix . 'sidebar_position',
			'def'	=> 'right',
		);


	// Homepage Layout Options
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'makenzie_lite_social_icons',
		'title' => __( '- Social Icons', 'makenzie-lite' ),
	);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Facebook URL', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_facebook',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Twitter URL', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_twitter',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Instagram URL', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_instagram',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Pinterest URL', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_pinterest',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Behance URL', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_behance',
			'def'	=> '',
		);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Etsy URL', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_etsy',
			'def'	=> '',
		);

			$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Youtube URL', 'makenzie-lite' ),
			'id'	=> $prefix . 'header_youtube',
			'def'	=> '',
		);


	// Single Post Layout Options
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'makenzie_lite_single_options',
		'title' => __( '- Single Post Layout Options', 'makenzie-lite' ),
	);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'on' => __( 'On', 'makenzie-lite' ),
				'off' => __( 'Off', 'makenzie-lite' ),
			),
			'title' => __( 'Sidebar On/Off', 'makenzie-lite' ),
			'id'	=> $prefix . 'single_sidebar_on_off',
			'def'	=> 'on',
		);

		$options[] = array(
			'type'	=> 'option_select',
			'opts'  => array(
				'right' => __( 'Right', 'makenzie-lite' ),
				'left' => __( 'Left', 'makenzie-lite' ),
			),
			'title' => __( 'Sidebar Position', 'makenzie-lite' ),
			'id'	=> $prefix . 'single_sidebar_position',
			'def'	=> 'right',
		);

	// Footer Options
	$options[] = array(
		'type'	=> 'section',
		'id'	=> 'makenzie_lite_footer_options',
		'title' => __( '- Footer Options', 'makenzie-lite' ),
	);

		$options[] = array(
			'type'	=> 'option_text',
			'title' => __( 'Footer Text Left', 'makenzie-lite' ),
			'id'	=> $prefix . 'footer_text_left',
			'def'	=> 'Copyright &copy; 2020. All Rights reserved. ',
		);

	return $options;

} add_filter( 'makenzie_lite_customizer_register', 'makenzie_lite_customizer_register_options', 10, 1 );

/**
 * Output custom CSS from color options
 */
function makenzie_lite_customizer_frontend_custom_css() {

	// will store CSS in this var
	$custom_css = '';


	// Accent color Background
	if ( $accent_color = makenzie_lite_get_theme_mod( 'accent_color', '#c99d6e' ) ) {
		$custom_css .= '.continue-reading a, .addtoany_list a > span, .pagination ul li.active a, .pagination ul li a:hover, .about-author, .post-navigation a:hover, .post-navigation a:focus, .comment-form .submit:hover, .comment-form .submit:active, .comment-form .submit:focus, .tags-links a, .tags-links a:visited { background-color: ' . sanitize_hex_color($accent_color) . '!important } ';
	}

	// Accent Color border
	if ( $accent_color_border = makenzie_lite_get_theme_mod( 'accent_color', '#c99d6e' ) ) {
		$custom_css .= '.pagination ul li.active a, .pagination ul li a:hover, .post-navigation a:hover, .post-navigation a:focus { border: 1px solid ' . sanitize_hex_color($accent_color_border) . ' } ';
	}

	// Accent Color
	if ( $accent_color_color = makenzie_lite_get_theme_mod( 'accent_color', '#c99d6e' ) ) {
		$custom_css .= '.slick-dots li.slick-active button:before, .about-author-signature { color:' . sanitize_hex_color($accent_color_color) . ' } ';
	}

	// Default link colors
	if ( $text_link_color = makenzie_lite_get_theme_mod( 'text_link_color', '#c99d6e' ) ) {
		$custom_css .= 'a, a:visited { color: ' . sanitize_hex_color($text_link_color) . ' } ';
	}

	if ( $text_link_color_hover = makenzie_lite_get_theme_mod( 'text_link_color_hover', '#000' ) ) {
		$custom_css .= 'a:hover { color: ' . sanitize_hex_color($text_link_color_hover) . ' } ';
	}

	// body text color
	if ( $body_text_color = makenzie_lite_get_theme_mod( 'body_text_color', false ) ) {
		$custom_css .= 'body { color: ' . sanitize_hex_color($body_text_color) . ' } ';
	}

	// top bar bg color
	if ( $top_bar_bg_color = makenzie_lite_get_theme_mod( 'top_bar_bg_color', '#000' ) ) {
		$custom_css .= '#top-bar { background-color: ' . sanitize_hex_color($top_bar_bg_color) . ' } ';
	}

	// top bar text color
	if ( $top_bar_nav_text_color = makenzie_lite_get_theme_mod( 'top_bar_nav_text_color', '#fff' ) ) {
		$custom_css .= '#top-bar #top-bar-navigation a { color: ' . sanitize_hex_color($top_bar_nav_text_color) . ' } ';
	}

	// top bar hover color
	if ( $top_bar_hover_text_color = makenzie_lite_get_theme_mod( 'top_bar_hover_text_color', '#c99d6e' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { #top-bar #top-bar-navigation .current-menu-item a,#top-bar #top-bar-navigation .current_page_item a, #top-bar #top-bar-navigation a:hover, #top-bar #top-bar-navigation .sub-menu a:hover, #top-bar #top-bar-navigation a:active, #top-bar #top-bar-navigation .sub-menu a:active, #top-bar #top-bar-navigation a:focus, #top-bar #top-bar-navigation .sub-menu a:focus  { color: ' . sanitize_hex_color($top_bar_hover_text_color) . ' } }  ';
	}

	// top bar arrow color
	if ( $top_bar_arrow_color = makenzie_lite_get_theme_mod( 'top_bar_arrow_color', '#fff' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .top-bar-nav .dropdown-toggle:after { color: ' . sanitize_hex_color($top_bar_arrow_color) . ' } } ';
	}

	// top bar sub navigation background color
	if ( $top_bar_nav_background_color = makenzie_lite_get_theme_mod( 'top_bar_nav_background_color', '#1a1b1d' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .top-bar-nav.main-navigation ul ul.toggled-on  { background-color: ' . sanitize_hex_color($top_bar_nav_background_color) . ' } } ';
	}

	// top bar sub navigation border color
	if ( $top_bar_sub_nav_border_color = makenzie_lite_get_theme_mod( 'top_bar_sub_nav_border_color', '#1a1b1d' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .top-bar-nav.main-navigation ul ul.toggled-on  { border-color: ' . sanitize_hex_color($top_bar_sub_nav_border_color) . ' } } ';
	}

	// top bar sub navigation separator color
	if ( $top_bar_sub_nav_separator_color = makenzie_lite_get_theme_mod( 'top_bar_sub_nav_separator_color', '#3d3b3b' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .top-bar-nav.main-navigation li li { border-color: ' . sanitize_hex_color($top_bar_sub_nav_separator_color) . ' } } ';
	}

	// top bar sub navigation text color
	if ( $top_bar_sub_nav_text_color = makenzie_lite_get_theme_mod( 'top_bar_sub_nav_text_color', '#fff' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .top-bar-nav.main-navigation .sub-menu a { color: ' . sanitize_hex_color($top_bar_sub_nav_text_color) . ' } } ';
	}

	// top bar sub navigation hover color
	if ( $top_bar_sub_nav_hover_text_color = makenzie_lite_get_theme_mod( 'top_bar_sub_nav_hover_text_color', '#999' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .top-bar-nav.main-navigation .sub-menu .current-menu-item a, .top-bar-nav.main-navigation .sub-menu a:hover, .top-bar-nav.main-navigation .sub-menu a:active, .top-bar-nav.main-navigation .sub-menu a:focus  { color: ' . sanitize_hex_color($top_bar_sub_nav_hover_text_color) . ' } } ';
	}

	// top bar sub navigation arrow color
	if ( $top_bar_sub_nav_arrow_color = makenzie_lite_get_theme_mod( 'top_bar_sub_nav_arrow_color', '#fff' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .top-bar-nav .sub-menu .dropdown-toggle:after { color: ' . sanitize_hex_color($top_bar_sub_nav_arrow_color) . ' } } ';
	}

	// header bg color
	if ( $header_bg_color = makenzie_lite_get_theme_mod( 'header_bg_color', '#fff' ) ) {
		$custom_css .= '.site-header { background-color: ' . sanitize_hex_color($header_bg_color) . ' } ';
	}

	// header 3 bg color
	if ( $header_3_bg_color = makenzie_lite_get_theme_mod( 'header_3_bg_color', '#fff' ) ) {
		$custom_css .= '#desktop-site-navigation.header-3-nav { background-color: ' . sanitize_hex_color($header_3_bg_color). ' } ';
	}

	// header 3 bg color
	if ( $header_3_border_color = makenzie_lite_get_theme_mod( 'header_3_border_color', '#f3f2f1' ) ) {
		$custom_css .= '#desktop-site-navigation.header-3-nav, .header-3-nav #primary-menu { border-color: ' . sanitize_hex_color($header_3_border_color) . ' } ';
	}

	// site title color
	if ( $site_title_color = makenzie_lite_get_theme_mod( 'site_title_color', '#000' ) ) {
		$custom_css .= '.site-header-wrapper .site-title a { color: ' . sanitize_hex_color($site_title_color) . ' } ';
	}

	// site tagline color
	if ( $site_tagline_color = makenzie_lite_get_theme_mod( 'site_tagline_color', '#d47d22' ) ) {
		$custom_css .= '.site-header-wrapper .site-description { color: ' . sanitize_hex_color($site_tagline_color) . ' } ';
	}

	// top bar social icons color
	if ( $top_social_icons_color = makenzie_lite_get_theme_mod( 'top_social_icons_color', '#dd9933' ) ) {
		$custom_css .= '#top-bar #social-header li a { color: ' . sanitize_hex_color($top_social_icons_color) . ' } ';
	}

	// top bar social icons color hover
	if ( $top_social_icons_hover_color = makenzie_lite_get_theme_mod( 'top_social_icons_hover_color', false ) ) {
		$custom_css .= '#top-bar #social-header li a:hover, #top-bar #social-header li a:focus, #top-bar #social-header li a:active { color: ' . sanitize_hex_color($top_social_icons_hover_color) . ' } ';
	}

	// header social icons color
	if ( $social_icons_color = makenzie_lite_get_theme_mod( 'social_icons_color', '#000000' ) ) {
		$custom_css .= '.social-header li a { color: ' . sanitize_hex_color($social_icons_color) . ' } ';
	}

	// header social icons color hover
	if ( $social_icons_hover_color = makenzie_lite_get_theme_mod( 'social_icons_hover_color', '#c0711e' ) ) {
		$custom_css .= '.social-header li a:hover, .social-header li a:focus, .social-header li a:active { color: ' . sanitize_hex_color($social_icons_hover_color) . ' } ';
	}

	// top bar search text color
	if ( $top_search_text_color = makenzie_lite_get_theme_mod( 'top_search_text_color', false ) ) {
		$custom_css .= '#top-bar ::-webkit-input-placeholder { color: ' . sanitize_hex_color($top_search_text_color) . ' } #top-bar ::-moz-placeholder { color: ' . sanitize_hex_color($top_search_text_color) . ' } ';
	}

	// Top bar search text color hover
	if ( $top_search_text_active_color = makenzie_lite_get_theme_mod( 'top_search_text_active_color', false ) ) {
		$custom_css .= '#top-bar .search-form input:focus { color: ' . sanitize_hex_color($top_search_text_active_color) . ' } ';
	}

	// header search text color
	if ( $search_text_color = makenzie_lite_get_theme_mod( 'search_text_color', '#666' ) ) {
		$custom_css .= 'header ::-webkit-input-placeholder { color: ' . sanitize_hex_color($search_text_color) . ' } header ::-moz-placeholder { color: ' . sanitize_hex_color($search_text_color) . ' } ';
	}

	// header search text color
	if ( $search_text_active_color = makenzie_lite_get_theme_mod( 'search_text_active_color', '#444' ) ) {
		$custom_css .= '.site-header-wrapper .search-form input:focus, .site-header-wrapper .search-form input { color: ' . sanitize_hex_color($search_text_active_color) . ' } ';
	}

	// mobile  search text color
	if ( $mobile_search_text_color = makenzie_lite_get_theme_mod( 'mobile_search_text_color', false ) ) {
		$custom_css .= '.mobile-search ::-webkit-input-placeholder { color: ' . sanitize_hex_color($mobile_search_text_color) . ' } .mobile-search ::-moz-placeholder { color: ' . sanitize_hex_color($mobile_search_text_color) . ' } ';
	}

	// mobile  search text color hover
	if ( $mobile_search_text_active_color = makenzie_lite_get_theme_mod( 'mobile_search_text_active_color', false ) ) {
		$custom_css .= '.site-header-wrapper .mobile-search .search-form input:focus { color: ' . sanitize_hex_color($mobile_search_text_active_color) . ' } ';
	}

	// header search background color
	if ( $search_background_color = makenzie_lite_get_theme_mod( 'search_background_color', '#f7f7f7' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .site-header .search-form { background-color: ' . sanitize_hex_color($search_background_color) . ' } } ';
	}

	// desktop navigation text color
	if ( $desktop_nav_text_color = makenzie_lite_get_theme_mod( 'desktop_nav_text_color', '#222' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .main-navigation a, .header-3-nav.main-navigation a { color: ' . sanitize_hex_color($desktop_nav_text_color) . ' } } ';
	}

	// desktop navigation hover color
	if ( $desktop_nav_hover_text_color = makenzie_lite_get_theme_mod( 'desktop_nav_hover_text_color', '#d47d22' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .main-navigation .current-menu-item a,.main-navigation .current_page_item a, .main-navigation a:hover, .main-navigation a:active, .main-navigation a:focus  { color: ' . sanitize_hex_color($desktop_nav_hover_text_color) . ' } } ';
	}

	// desktop navigation arrow color
	if ( $desktop_nav_arrow_color = makenzie_lite_get_theme_mod( 'desktop_nav_arrow_color', '#222' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .dropdown-toggle:after, .header-3-nav .dropdown-toggle:after { color: ' . sanitize_hex_color($desktop_nav_arrow_color) . ' } } ';
	}

	// desktop sub navigation background color
	if ( $desktop_sub_nav_background_color = makenzie_lite_get_theme_mod( 'desktop_sub_nav_background_color', '#1a1b1d' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .main-navigation ul ul.toggled-on, .header-3-nav.main-navigation ul ul.toggled-on  { background-color: ' . sanitize_hex_color($desktop_sub_nav_background_color) . ' } } ';
	}

	// desktop sub navigation border color
	if ( $desktop_sub_nav_border_color = makenzie_lite_get_theme_mod( 'desktop_sub_nav_border_color', '#1a1b1d' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .main-navigation ul ul.toggled-on, .header-3-nav.main-navigation ul ul.toggled-on  { border-color: ' . sanitize_hex_color($desktop_sub_nav_border_color) . ' } } ';
	}

	// desktop sub navigation separator color
	if ( $desktop_sub_nav_separator_color = makenzie_lite_get_theme_mod( 'desktop_sub_nav_separator_color', '#303030' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .main-navigation .sub-menu li, .header-3-nav.main-navigation li li { border-color: ' . sanitize_hex_color($desktop_sub_nav_separator_color) . ' } } ';
	}

	// desktop sub navigation text color
	if ( $desktop_sub_nav_text_color = makenzie_lite_get_theme_mod( 'desktop_sub_nav_text_color', '#fff' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .main-navigation .sub-menu a, .main-navigation .current-menu-item .sub-menu a { color: ' . sanitize_hex_color($desktop_sub_nav_text_color) . ' } } ';
	}

	// header border radius
	if ( $boxed_radius = makenzie_lite_get_theme_mod( 'boxed_radius', false ) ) {
		$custom_css .= '@media screen and (min-width: 101em) { .site-header, #top-bar, #page.boxed { border-radius: ' . esc_attr($boxed_radius) . ' } } ';
	}

	// desktop sub navigation hover color
	if ( $desktop_sub_nav_hover_text_color = makenzie_lite_get_theme_mod( 'desktop_sub_nav_hover_text_color', false ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .main-navigation .current-menu-item .sub-menu .current-menu-item a, .main-navigation .sub-menu a:hover, .main-navigation .sub-menu a:active, .main-navigation .sub-menu a:focus  { color: ' . sanitize_hex_color($desktop_sub_nav_hover_text_color) . ' } } ';
	}

	// body top spacing
	if ( $boxed_padding = makenzie_lite_get_theme_mod( 'boxed_padding', false ) ) {
		$custom_css .= '@media screen and (min-width: 101em) { body { padding-top: ' . esc_attr($boxed_padding) . ' } } ';
	}

	// desktop sub navigation arrow color
	if ( $desktop_sub_nav_arrow_color = makenzie_lite_get_theme_mod( 'desktop_sub_nav_arrow_color', false ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .sub-menu .dropdown-toggle:after { color: ' . sanitize_hex_color($desktop_sub_nav_arrow_color) . ' } } ';
	}

	// mobile menu toggle color
	if ( $mobile_toggle_color = makenzie_lite_get_theme_mod( 'mobile_toggle_color', false ) ) {
		$custom_css .= '.menu-toggle { color: ' . sanitize_hex_color($mobile_toggle_color) . ' } ';
	}

	// mobile menu background color
	if ( $mobile_bg_color = makenzie_lite_get_theme_mod( 'mobile_bg_color', false ) ) {
		$custom_css .= '.nav-wrapper { background-color: ' . sanitize_hex_color($mobile_bg_color) . ' } ';
	}

	// mobile menu text color
	if ( $mobile_text_color = makenzie_lite_get_theme_mod( 'mobile_text_color', false ) ) {
		$custom_css .= '.main-navigation #primary-menu-mobile a { color: ' . sanitize_hex_color($mobile_text_color) . ' } ';
	}

	// mobile menu current / hover / activbe text color
	if ( $mobile_hover_color = makenzie_lite_get_theme_mod( 'mobile_hover_color', false ) ) {
		$custom_css .= '.main-navigation #primary-menu-mobile .current-menu-item a, .main-navigation #primary-menu-mobile a:hover, .main-navigation #primary-menu-mobile a:active, .main-navigation #primary-menu-mobile a:focus  { color: ' . sanitize_hex_color($mobile_hover_color) . ' } ';
	}

	// mobile navigation separator color
	if ( $mobile_separator_color = makenzie_lite_get_theme_mod( 'mobile_separator_color', false ) ) {
		$custom_css .= '.main-navigation #primary-menu-mobile li { border-color: ' . sanitize_hex_color($mobile_separator_color) . ' } ';
	}

	// mobile menu arrow color
	if ( $mobile_arrow_color = makenzie_lite_get_theme_mod( 'mobile_arrow_color', false ) ) {
		$custom_css .= '#primary-menu-mobile .dropdown-toggle:after { color: ' . sanitize_hex_color($mobile_arrow_color) . ' } ';
	}

	// mobile social icons color
	if ( $mobile_social_icons_color = makenzie_lite_get_theme_mod( 'mobile_social_icons_color', false ) ) {
		$custom_css .= '#social-mobile li a { color: ' . sanitize_hex_color($mobile_social_icons_color) . ' } ';
	}

	// mobile social icons color
	if ( $mobile_social_icons_hover_color = makenzie_lite_get_theme_mod( 'mobile_social_icons_hover_color', false ) ) {
		$custom_css .= '#social-mobile li a:hover, #social-mobile li a:focus, #social-mobile li a:active { color: ' . sanitize_hex_color($mobile_social_icons_hover_color) . ' } ';
	}

	// top bar padding
	if ( $top_bar_padding = makenzie_lite_get_theme_mod( 'top_bar_padding', false ) ) {
		$custom_css .= '#top-bar { padding: ' . esc_attr($top_bar_padding) . ' }';
	}

	// top bar sub navigation margin
	if ( $top_bar_sub_margin = makenzie_lite_get_theme_mod( 'top_bar_sub_margin', '42px' ) ) {
		$custom_css .= '@media screen and (min-width: 62em) { .top-bar-nav.main-navigation ul ul.toggled-on { top: ' . esc_attr($top_bar_sub_margin) . ' } }';
	}

	// append to style.css
	wp_add_inline_style( 'vendors', $custom_css );

} add_action( 'wp_enqueue_scripts', 'makenzie_lite_customizer_frontend_custom_css', 100 );

/**
 * Add options to customizer
 */
function makenzie_lite_customizer_register( $wp_customize ) {

	$customizer_options = apply_filters( 'makenzie_lite_customizer_register', array() );

	$section_priority = 200;
	$setting_priority = 5;
	$current_section_id = '';
	$current_setting_id = '';

	foreach ( $customizer_options as $customizer_option ) {

		if( $customizer_option['type'] == 'section' ){

			/* New Section */

			$section_priority += 50;
			$setting_priority = 5;
			$current_section_id = $customizer_option['id'];

			if ( ! isset( $customizer_option['descr'] ) )
				$customizer_option['descr'] = '';

			$wp_customize->add_section( $current_section_id, array(
				'title' => $customizer_option['title'],
				'priority' => $section_priority,
				'description' => $customizer_option['descr']
			) );

		} elseif ( $customizer_option['type'] == 'option_color' ) {

			/* New Option (COLOR) */

			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;

			$wp_customize->add_setting( $current_setting_id, array(
				'default' => $customizer_option['def'],
				'type' => 'theme_mod',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $current_setting_id, array(
					'label' => $customizer_option['title'],
					'section' => $current_section_id,
					'priority' => $setting_priority
				) ) );

		} elseif ( $customizer_option['type'] == 'option_text' ) {

			/* New Option (TEXT) */

			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;

			if ( ! isset( $customizer_option['descr'] ) )
				$customizer_option['descr'] = '';

			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );

				$wp_customize->add_control( $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'description' => $customizer_option['descr'],
					'section' 	=> $current_section_id,
					'type'		=> 'text',
					'priority'	=> $setting_priority
				));

		} elseif ( $customizer_option['type'] == 'option_textarea' ) {

			/* New Option (TEXT) */

			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;

			if ( ! isset( $customizer_option['descr'] ) )
				$customizer_option['descr'] = '';

			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );

				$wp_customize->add_control( $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'description' => $customizer_option['descr'],
					'section' 	=> $current_section_id,
					'type'		=> 'textarea',
					'priority'	=> $setting_priority
				));

		} elseif ( $customizer_option['type'] == 'option_select' ) {

			/* New Option (SELECT) */

			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;

			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );

				$wp_customize->add_control( $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'section' 	=> $current_section_id,
					'type'		=> 'select',
					'choices'	=> $customizer_option['opts'],
					'priority'	=> $setting_priority,
				));

		} elseif ( $customizer_option['type'] == 'option_checkbox' ) {

			/* New Option (checkbox) */

			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;

			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_html',
			) );

				$wp_customize->add_control( $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'section' 	=> $current_section_id,
					'type'		=> 'checkbox',
					'priority'	=> $setting_priority,
				));

		} elseif ( $customizer_option['type'] == 'option_image' ) {

			/* New Option (image) */

			$current_setting_id = $customizer_option['id'];
			$setting_priority += 5;

			$wp_customize->add_setting( $current_setting_id, array(
				'default'	=> $customizer_option['def'],
				'type'		=> 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			) );

				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $current_setting_id, array(
					'label'		=> $customizer_option['title'],
					'section' 	=> $current_section_id,
					'priority'	=> $setting_priority,
				) ) );
		}
	}
} add_action( 'customize_register', 'makenzie_lite_customizer_register' );
