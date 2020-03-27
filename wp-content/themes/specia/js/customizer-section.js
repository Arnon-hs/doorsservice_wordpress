( function( api ) {

	// Extends our custom "example-1" section.
	api.sectionConstructor['plugin-section'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );


function speciawpfrontpagesectionsscroll( section_id ){
    var scroll_section_id;

    var $contents = jQuery('#customize-preview iframe').contents();

    switch ( section_id ) {
		
		case 'accordion-panel-header_section':
        scroll_section_id = "specia-header";
        break;
		
        case 'accordion-panel-home_section':
        scroll_section_id = "specia-slider";
        break;

        case 'accordion-panel-call_panel':
        scroll_section_id = "specia-cta";
        break;

        case 'accordion-panel-service_panel':
        scroll_section_id = "specia-service";
        break;

        case 'accordion-panel-features_panel':
        scroll_section_id = "specia-feature";
        break;

        case 'accordion-panel-portfolio_panel':
        scroll_section_id = "specia-portfolio";
        break;
		
		case 'accordion-panel-blog_panel':
        scroll_section_id = "specia-blog";
        break;
		
		case 'accordion-panel-footer_section':
        scroll_section_id = "specia-footer";
        break;
		
    }

    if( $contents.find('#'+scroll_section_id).length > 0 ){
        $contents.find("html, body").animate({
        scrollTop: $contents.find( "#" + scroll_section_id ).offset().top
        }, 1000);
    }
}

 jQuery('body').on('click', '#customize-theme-controls .accordion-section .accordion-section-title', function(event) {
        var section_id = jQuery(this).parent('.control-panel-default').attr('id');
        speciawpfrontpagesectionsscroll( section_id );
});