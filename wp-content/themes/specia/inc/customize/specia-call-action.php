<?php
function specia_call_action_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Call Action Section Panel
	=========================================*/
	$wp_customize->add_panel( 
		'call_panel', 
		array(
			'priority'      => 128,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Call To Action Section', 'specia'),
		) 
	);
	
	// Call to Action //
	$wp_customize->add_section(
        'call_action_setting',
        array(
        	'priority'      => 1,
            'title' 		=> __('Settings','specia'),
			'panel'  		=> 'call_panel',
		)
    );
	
	$wp_customize->add_setting( 
		'hide_show_call_actions' , 
			array(
			'default' => 'on',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_select',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
	'hide_show_call_actions' , 
		array(
			'label'          => __( 'Hide / Show Section', 'specia' ),
			'section'        => 'call_action_setting',
			'settings'       => 'hide_show_call_actions',
			'type'           => 'radio',
			'choices'        => 
			array(
				'on' => __( 'Show', 'specia' ),
				'off'=> __( 'Hide', 'specia' )
			)  
		) 
	);
	
	// Call Action Settings Section // 
	$wp_customize->add_section(
        'call_action_content',
        array(
        	'priority'      => 2,
            'title' 		=> __('Content','specia'),
			'panel'  		=> 'call_panel',
		)
    );
	
	// Call Action Text// 
	
	$wp_customize->add_setting(
	'call_action_page',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'specia_sanitize_integer',
		)
	);
		
	$wp_customize->add_control(
	'call_action_page',
		array(
			'type'	=> 'dropdown-pages',
			'allow_addition' => true,
			'label'	=> __('Select Page','specia'),
			'section'	=> 'call_action_content',
			'settings'  => 'call_action_page',
		)
	);
	
	// Call Button Label // 
	$wp_customize->add_setting(
    	'call_action_button_label',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_text',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'call_action_button_label',
		array(
		    'label'   => __('Button Text','specia'),
		    'section' => 'call_action_content',
			'settings'       => 'call_action_button_label',
			'type'           => 'text',
		)  
	);
	
	// Call Button link // 
	$wp_customize->add_setting(
    	'call_action_button_link',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_url',
		)
	);	
	
	$wp_customize->add_control( 
		'call_action_button_link',
		array(
		    'label'   => __('Button Link','specia'),
		    'section' => 'call_action_content',
			'settings'       => 'call_action_button_link',
			'type'           => 'text',
		)  
	);
	
	
	$wp_customize->add_setting(
		'call_action_button_target'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'call_action_button_target',
			array(
				'type' => 'checkbox',
				'label' => __('Open link in a new tab','specia'),
				'section' => 'call_action_content',
				'settings' => 'call_action_button_target',
			)
	);
	
}

add_action( 'customize_register', 'specia_call_action_setting' );

// Call to action selective refresh
function specia_home_cta_section_partials( $wp_customize ){

	// hide_show_call_actions
	$wp_customize->selective_refresh->add_partial(
		'hide_show_call_actions', array(
			'selector' => '.call-to-action',
			'container_inclusive' => true,
			'render_callback' => 'call_action_setting',
			'fallback_refresh' => true,
		)
	);
	
	// call_action_button_label
	$wp_customize->selective_refresh->add_partial( 'call_action_button_label', array(
		'selector'            => '.call-to-action a',
		'settings'            => 'call_action_button_label',
		'render_callback'  => 'specia_call_action_button_label_render_callback',
	
	) );
	}

add_action( 'customize_register', 'specia_home_cta_section_partials' );

// call_action_button_label
function specia_call_action_button_label_render_callback() {
	return get_theme_mod( 'call_action_button_label' );
}

?>