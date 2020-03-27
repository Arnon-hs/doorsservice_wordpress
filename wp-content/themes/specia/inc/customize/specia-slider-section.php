<?php
function specia_slider_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Slider Section Panel
	=========================================*/
	$wp_customize->add_panel( 
		'home_section', 
		array(
			'priority'      => 128,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Slider Section', 'specia'),
		) 
	);
	

	/*=========================================
	Slider Settings Section
	=========================================*/
	// Slider Settings Section // 
	$wp_customize->add_section(
        'slider_setting',
        array(
        	'priority'      => 1,
            'title' 		=> __('Settings','specia'),
			'panel'  		=> 'home_section',
		)
    );
	
	// Slider Hide/ Show Setting // 
	$wp_customize->add_setting( 
		'hide_show_slider' , 
			array(
			'default' => 'on',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_select',
			'transport'         => $selective_refresh,
		) 
	);

	$wp_customize->add_control(
	'hide_show_slider' , 
		array(
			'label'          => __( 'Hide / Show Section', 'specia' ),
			'section'        => 'slider_setting',
			'settings'   	 => 'hide_show_slider',
			'type'           => 'radio',
			'choices'        => 
			array(
				'on' => __( 'Show', 'specia' ),
				'off' => __( 'Hide', 'specia' )
			) 
		) 
	);
	
	// Slider Settings Section // 
	$wp_customize->add_section(
        'slider_content',
        array(
        	'priority'      => 2,
            'title' 		=> __('Content','specia'),
			'panel'  		=> 'home_section',
		)
    );
	
	// Slider 1 //
	$wp_customize->add_setting(
	'slider-page1',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'specia_sanitize_integer',
		)
	);
		
	$wp_customize->add_control(
	'slider-page1',
		array(
			'type'	=> 'dropdown-pages',
			'allow_addition' => true,
			'label'	=> __('Select Page','specia'),
			'section'	=> 'slider_content',
			'settings'   	 => 'slider-page1',
		)
	);	
	
	// Slider 2 //
	$wp_customize->add_setting(
	'slider-page2',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'specia_sanitize_integer',
		)
	);
		
	$wp_customize->add_control(
	'slider-page2',
		array(
			'type'	=> 'dropdown-pages',
			'allow_addition' => true,
			'label'	=> __('Select Page','specia'),
			'section'	=> 'slider_content',
			'settings'   	 => 'slider-page2',
		)
	);	
	
	// Slider 3 //
	$wp_customize->add_setting(
	'slider-page3',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'specia_sanitize_integer',
		)
	);
		
	$wp_customize->add_control(
	'slider-page3',
		array(
			'type'	=> 'dropdown-pages',
			'allow_addition' => true,
			'label'	=> __('Select Page','specia'),
			'section'	=> 'slider_content',
			'settings'   	 => 'slider-page3',
		)
	);
}

add_action( 'customize_register', 'specia_slider_setting' );

// Slider selective refresh
function specia_home_slider_section_partials( $wp_customize ){
	
	// hide_show_slider
	$wp_customize->selective_refresh->add_partial(
		'hide_show_slider', array(
			'selector' => '.slider-version',
			'container_inclusive' => true,
			'render_callback' => 'slider_setting',
			'fallback_refresh' => true,
		)
	);
	}

add_action( 'customize_register', 'specia_home_slider_section_partials' );
?>