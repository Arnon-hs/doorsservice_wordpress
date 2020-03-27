<header role="banner" class="hero">
	<nav class='navbar navbar-default <?php echo esc_attr(specia_sticky_menu()); ?>' role='navigation'>
		
		<div class="container">

			<!-- Mobile Display -->
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand">
					<?php
						if(has_custom_logo())
						{	
							the_custom_logo();
						}
						else { 
					?> 
						<span class="site-title"><?php echo esc_html(bloginfo('name')); ?></span>
					<?php	
						}
					?>
					
					<?php
						$description = get_bloginfo( 'description');
						if ($description) : ?>
							<p class="site-description"><?php echo esc_html($description); ?></p>
					<?php endif; ?>
				</a>
				
				
				
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only"><?php _e('Toggle navigation','heropress');?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- /Mobile Display -->

			<!-- Menu Toggle -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<?php
				$extra_html  = '<ul id="%1$s" class="%2$s">%3$s';
				
				$extra_html .= "<li><div class='heropress-cart'>";
				
				if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					$count = WC()->cart->cart_contents_count;
					$cart_url = wc_get_cart_url();
					
					$extra_html .= "<a href='$cart_url' class='cart-icon'>"."<i class='fa fa-cart-arrow-down'></i>";
					
					if ( $count > 0 ) {
						
						$extra_html .= "<span class='count'>$count</span>";
					
					}
					
					else {
						
						$extra_html .= "<span class='count'>0</span>";
					}
					
					$extra_html .= "</a>";
				}
				
				$extra_html .= "</div></li>";
				
				$extra_html .= '</ul>';
				
				wp_nav_menu( 
					array(  
						'theme_location' => 'primary_menu',
						'container'  => '',
						'menu_class' => 'nav navbar-nav navbar-right',
						'fallback_cb' => 'specia_fallback_page_menu',
						'items_wrap'  => $extra_html,
						'walker' => new specia_nav_walker()
						 ) 
					);
			?>
			</div>
			<!-- Menu Toggle -->
			
		</div>
	</nav>
</header>
<div class="clearfix"></div>
<?php 
if ( !is_page_template( 'templates/template-homepage-one.php' )) {
		specia_breadcrumbs_style(); 
	}
?>