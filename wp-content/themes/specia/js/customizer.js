/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );


	//header_email
	wp.customize(
		'header_email', function( value ) {
			value.bind(
				function( newval ) {
					$( '.header-top-info-1 .header-email' ).text( newval );
				}
			);
		}
	);
	
	//header_contact_num
	wp.customize(
		'header_contact_num', function( value ) {
			value.bind(
				function( newval ) {
					$( '.header-top-info-1 .header-contact' ).text( newval );
				}
			);
		}
	);
	
	//call_action_button_label
	wp.customize(
		'call_action_button_label', function( value ) {
			value.bind(
				function( newval ) {
					$( '.call-to-action a' ).text( newval );
				}
			);
		}
	);
	
	//service_title
	wp.customize(
		'service_title', function( value ) {
			value.bind(
				function( newval ) {
					$( '.service-version-one .section-heading' ).text( newval );
				}
			);
		}
	);
	
	//service_description
	wp.customize(
		'service_description', function( value ) {
			value.bind(
				function( newval ) {
					$( '.service-version-one .section-description' ).text( newval );
				}
			);
		}
	);
	
	//features_title
	wp.customize(
		'features_title', function( value ) {
			value.bind(
				function( newval ) {
					$( '.features-version-one .section-heading' ).text( newval );
				}
			);
		}
	);
	
	//features_description
	wp.customize(
		'features_description', function( value ) {
			value.bind(
				function( newval ) {
					$( '.features-version-one .section-description' ).text( newval );
				}
			);
		}
	);
	
	//portfolio_title
	wp.customize(
		'portfolio_title', function( value ) {
			value.bind(
				function( newval ) {
					$( '.portfolio-version .section-heading' ).text( newval );
				}
			);
		}
	);
	
	//portfolio_description
	wp.customize(
		'portfolio_description', function( value ) {
			value.bind(
				function( newval ) {
					$( '.portfolio-version .section-description' ).text( newval );
				}
			);
		}
	);
	
	//blog_title
	wp.customize(
		'blog_title', function( value ) {
			value.bind(
				function( newval ) {
					$( '.latest-blog .section-heading' ).text( newval );
				}
			);
		}
	);
	
	//blog_description
	wp.customize(
		'blog_description', function( value ) {
			value.bind(
				function( newval ) {
					$( '.latest-blog .section-description' ).text( newval );
				}
			);
		}
	);
	
	//copyright_content
	wp.customize(
		'copyright_content', function( value ) {
			value.bind(
				function( newval ) {
					$( '.footer-copyright .copyright' ).text( newval );
				}
			);
		}
	);
} )( jQuery );