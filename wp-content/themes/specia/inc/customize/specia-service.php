<?php
function specia_service_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service Settings Panel
	=========================================*/
	$wp_customize->add_panel( 
		'service_panel', 
		array(
			'priority'      => 128,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Service Section', 'specia'),
		) 
	);
	
	// Service Settings Section // 
	$wp_customize->add_section(
        'service_setting',
        array(
        	'priority'      => 1,
            'title' 		=> __('Settings','specia'),
			'panel'  		=> 'service_panel',
		)
    );
	
	$wp_customize->add_setting( 
		'hide_show_service' , 
			array(
			'default' => 'on',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_select',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_service' , 
		array(
			'label'          => __( 'Hide / Show Section', 'specia' ),
			'section'        => 'service_setting',
			'settings'   	 => 'hide_show_service',
			'type'           => 'radio',
			'choices'        => 
			array(
				'on' => __( 'Show', 'specia' ),
				'off' => __( 'Hide', 'specia' )
			)
		) 
	);
	
	// Service Header Section // 
	$wp_customize->add_section(
        'service_header',
        array(
        	'priority'      => 2,
            'title' 		=> __('Header','specia'),
			'panel'  		=> 'service_panel',
		)
    );
	
	// Service Title // 
	$wp_customize->add_setting(
    	'service_title',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'service_title',
		array(
		    'label'   => __('Title','specia'),
		    'section' => 'service_header',
			'settings'   	 => 'service_title',
			'type'           => 'text',
		)  
	);
	
	// Service Description // 
	$wp_customize->add_setting(
    	'service_description',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_text',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'service_description',
		array(
		    'label'   => __('Description','specia'),
		    'section' => 'service_header',
			'settings'   	 => 'service_description',
			'type'           => 'textarea',
		)  
	);
	
	// Service Content Section // 
	$wp_customize->add_section(
        'service_content',
        array(
        	'priority'      => 3,
            'title' 		=> __('Content','specia'),
			'panel'  		=> 'service_panel',
		)
    );
	
	// Service 1 //
	$wp_customize->add_setting(
	'service-page1',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'specia_sanitize_integer',
		)
	);
		
	$wp_customize->add_control(
	'service-page1',
		array(
			'type'	=> 'dropdown-pages',
			'allow_addition' => true,
			'label'	=> __('Select Page','specia'),
			'section'	=> 'service_content',
			'settings'   	 => 'service-page1',
		)
	);	
	
	// Service 2 //
	$wp_customize->add_setting(
	'service-page2',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'specia_sanitize_integer',
		)
	);
		
	$wp_customize->add_control(
	'service-page2',
		array(
			'type'	=> 'dropdown-pages',
			'allow_addition' => true,
			'label'	=> __('Select Page','specia'),
			'section'	=> 'service_content',
			'settings'   	 => 'service-page2',
		)
	);	
	
	// Service 3 //
	$wp_customize->add_setting(
	'service-page3',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'specia_sanitize_integer',
		)
	);
		
	$wp_customize->add_control(
	'service-page3',
		array(
			'type'	=> 'dropdown-pages',
			'allow_addition' => true,
			'label'	=> __('Select Page','specia'),
			'section'	=> 'service_content',
			'settings'   	 => 'service-page3',
		)
	);
	
}

add_action( 'customize_register', 'specia_service_setting' );

// Service section selective refresh
function specia_home_service_section_partials( $wp_customize ){
	
	// hide_show_service
	$wp_customize->selective_refresh->add_partial(
		'hide_show_service', array(
			'selector' => '.service-version-one',
			'container_inclusive' => true,
			'render_callback' => 'service_setting',
			'fallback_refresh' => true,
		)
	);
	
	// service_title
	$wp_customize->selective_refresh->add_partial( 'service_title', array(
		'selector'            => '.service-version-one .section-heading',
		'settings'            => 'service_title',
		'render_callback'  => 'specia_home_service_title_render_callback',
	
	) );
	
	// service_description
	$wp_customize->selective_refresh->add_partial( 'service_description', array(
		'selector'            => '.service-version-one .section-description',
		'settings'            => 'service_description',
		'render_callback'  => 'specia_home_service_description_render_callback',
	
	) );
	}

add_action( 'customize_register', 'specia_home_service_section_partials' );

// service_title
function specia_home_service_title_render_callback() {
	return get_theme_mod( 'service_title' );
}
// service_description
function specia_home_service_description_render_callback() {
	return get_theme_mod( 'service_description' );
}
?>