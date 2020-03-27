<section class="breadcrumb hero" style="background: url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/images/breadcrumb.jpg') no-repeat fixed 0 0 / cover rgba(0, 0, 0, 0);">
    <div class="background-overlay">
        <div class="container">
            <div class="row padding-top-100 padding-bottom-150">
                <div class="col-md-12 col-xs-12 col-sm-12 text-center hero-breadcrumb">
                     <h2>
						<?php 
							if ( is_day() ) : 
							
								printf( __( 'Daily Archives: %s', 'heropress' ), get_the_date() );
							
							elseif ( is_month() ) :
							
								printf( __( 'Monthly Archives: %s', 'heropress' ), (get_the_date( 'F Y' ) ));
								
							elseif ( is_year() ) :
							
								printf( __( 'Yearly Archives: %s', 'heropress' ), (get_the_date( 'Y' ) ) );
								
							elseif ( is_category() ) :
							
								printf( __( 'Category Archives: %s', 'heropress' ), (single_cat_title( '', false ) ));

							elseif ( is_tag() ) :
							
								printf( __( 'Tag Archives: %s', 'heropress' ), (single_tag_title( '', false ) ));
								
							elseif ( is_404() ) :
						
								printf( __( 'Error 404', 'heropress' ));
								
							elseif ( is_search() ) :
						
								printf( __( 'Search Results for: %s', 'heropress' ), (get_search_query() ));		
								
							elseif ( is_author() ) :
							
								printf( __( 'Author: %s', 'heropress' ), (get_the_author( '', false ) ));			
								
							elseif ( class_exists( 'WooCommerce' ) ) : 
								
								if ( is_shop() ) {
									woocommerce_page_title();
								}
								
								elseif ( is_cart() ) {
									the_title();
								}
								
								elseif ( is_checkout() ) {
									the_title();
								}
								
								else {
									the_title();
								}
							
							else :
									the_title();
								
							endif;
							
						?>
					</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="clearfix"></div>