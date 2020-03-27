<?php
function specia_features_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Features Section Panel
	=========================================*/
	$wp_customize->add_panel( 
		'features_panel', 
		array(
			'priority'      => 128,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Features Section', 'specia'),
		) 
	);
	
	// Features Settings Section // 
	$wp_customize->add_section(
        'features_setting',
        array(
        	'priority'      => 1,
             'title' 		=> __('Settings','specia'),
			'panel'  		=> 'features_panel',
		)
    );
	
	$wp_customize->add_setting( 
		'hide_show_features' , 
			array(
			'default' => 'on',
			'capability' 	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_select',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_features' , 
		array(
			'label'          => __( 'Hide / Show Section', 'specia' ),
			'section'        => 'features_setting',
			'settings'   	 => 'hide_show_features',
			'type'           => 'radio',
			'choices'        => 
			array(
				'on' => __( 'Show', 'specia' ),
				'off' => __( 'Hide', 'specia' )
			) 
		) 
	);
	
	// Features Header Section // 
	$wp_customize->add_section(
        'features_header',
        array(
        	'priority'      => 1,
            'title' 		=> __('Header','specia'),
			'panel'  		=> 'features_panel',
		)
    );
	
	// Features Title // 
	$wp_customize->add_setting(
    	'features_title',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'features_title',
		array(
		    'label'   => __('Title','specia'),
		    'section' => 'features_header',
			'settings'   	 => 'features_title',
			'type'           => 'text',
		)  
	);
	
	// Features Description // 
	$wp_customize->add_setting(
    	'features_description',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'features_description',
		array(
		    'label'   => __('Description','specia'),
		    'section' => 'features_header',
			'settings'   	 => 'features_description',
			'type'           => 'textarea',
		)  
	);
	
}

add_action( 'customize_register', 'specia_features_setting' );

// Feature section selective refresh
function specia_home_feature_section_partials( $wp_customize ){
	
	// hide_show_features
	$wp_customize->selective_refresh->add_partial(
		'hide_show_features', array(
			'selector' => '.features-version-one',
			'container_inclusive' => true,
			'render_callback' => 'features_setting',
			'fallback_refresh' => true,
		)
	);
	
	// features_title
	$wp_customize->selective_refresh->add_partial( 'features_title', array(
		'selector'            => '.features-version-one .section-heading',
		'settings'            => 'features_title',
		'render_callback'  => 'specia_home_features_title_render_callback',
	
	) );
	
	// features_description
	$wp_customize->selective_refresh->add_partial( 'features_description', array(
		'selector'            => '.features-version-one .section-description',
		'settings'            => 'features_description',
		'render_callback'  => 'specia_home_features_description_render_callback',
	
	) );
	}

add_action( 'customize_register', 'specia_home_feature_section_partials' );

// features_title
function specia_home_features_title_render_callback() {
	return get_theme_mod( 'features_title' );
}
// features_description
function specia_home_features_description_render_callback() {
	return get_theme_mod( 'features_description' );
}
?>