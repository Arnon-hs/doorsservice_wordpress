<?php
function specia_blog_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Blog Section Panel
	=========================================*/
	$wp_customize->add_panel( 
		'blog_panel', 
		array(
			'priority'      => 128,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Blog Section', 'specia'),
		) 
	);
	
	// Blog Settings Section // 
	$wp_customize->add_section(
        'blog_setting',
        array(
        	'priority'      => 1,
            'title' 		=> __('Settings','specia'),
			'panel'  		=> 'blog_panel',
		)
    );
	
	$wp_customize->add_setting( 
		'hide_show_blog' , 
			array(
			'default' => 'on',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_select',
			'transport'         => $selective_refresh,
		) 
	);
	
	$wp_customize->add_control(
		'hide_show_blog' , 
		array(
			'label'          => __( 'Hide / Show Section', 'specia' ),
			'section'        => 'blog_setting',
			'settings'   	 => 'hide_show_blog',
			'type'           => 'radio',
			'choices'        => 
			array(
				'on' => __( 'Show', 'specia' ),
				'off' => __( 'Hide', 'specia' )
			) 
		) 
	);
	
	// Blog Header Section // 
	$wp_customize->add_section(
        'blog_header',
        array(
        	'priority'      => 2,
            'title' 		=> __('Header','specia'),
			'panel'  		=> 'blog_panel',
		)
    );
	
	// Blog Title // 
	$wp_customize->add_setting(
    	'blog_title',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_html',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'blog_title',
		array(
		    'label'   => __('Title','specia'),
		    'section' => 'blog_header',
			'settings'   	 => 'blog_title',
			'type'           => 'text',
		)  
	);
	
	// Blog Description // 
	$wp_customize->add_setting(
    	'blog_description',
    	array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'specia_sanitize_text',
			'transport'         => $selective_refresh,
		)
	);	
	
	$wp_customize->add_control( 
		'blog_description',
		array(
		    'label'   => __('Description','specia'),
		    'section' => 'blog_header',
			'settings'   	 => 'blog_description',
			'type'           => 'textarea',
		)  
	);
	
	// Blog Content Section // 
	$wp_customize->add_section(
        'blog_content',
        array(
        	'priority'      => 3,
            'title' 		=> __('Content','specia'),
			'panel'  		=> 'blog_panel',
		)
    );
	
	// Blog Display Setting // 
	$wp_customize->add_setting(
    	'blog_display_num',
    	array(
	        'default'			=> 3,
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback'	=> 'specia_sanitize_integer',
		)
	);	

	$wp_customize->add_control( 
		'blog_display_num',
		array(
		    'label'   		=> __('Select number of Posts','specia'),
		    'section' 		=> 'blog_content',
			'settings'   	 => 'blog_display_num',
			'type' 			=> 'select',
			'choices'        => 
			array(
				'3' => '3',
				'6' => '6',
				'9' => '9'
				
			) 
		)  
	);
		
}

add_action( 'customize_register', 'specia_blog_setting' );

// Portfolio section selective refresh
function specia_home_blog_section_partials( $wp_customize ){
	
	// hide_show_blog
	$wp_customize->selective_refresh->add_partial(
		'hide_show_blog', array(
			'selector' => '.latest-blog',
			'container_inclusive' => true,
			'render_callback' => 'blog_setting',
			'fallback_refresh' => true,
		)
	);
	
	// blog_title
	$wp_customize->selective_refresh->add_partial( 'blog_title', array(
		'selector'            => '.latest-blog .section-heading',
		'settings'            => 'blog_title',
		'render_callback'  => 'specia_blog_title_render_callback',
	
	) );
	
	// blog_description
	$wp_customize->selective_refresh->add_partial( 'blog_description', array(
		'selector'            => '.latest-blog .section-description',
		'settings'            => 'blog_description',
		'render_callback'  => 'specia_blog_description_render_callback',
	
	) );
	}

add_action( 'customize_register', 'specia_home_blog_section_partials' );

// blog_title
function specia_blog_title_render_callback() {
	return get_theme_mod( 'blog_title' );
}
// blog_description
function specia_blog_description_render_callback() {
	return get_theme_mod( 'blog_description' );
}
?>