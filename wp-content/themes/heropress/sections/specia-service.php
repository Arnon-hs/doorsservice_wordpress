<?php 
	$hide_show_service= get_theme_mod('hide_show_service','on'); 
	$service_title= get_theme_mod('service_title'); 
	$service_description= get_theme_mod('service_description');
	if($hide_show_service == 'on') :
?>
	<section id="specia-service" class="service-version-three service-version-one">
		<div class="container">
			<div class="row text-center padding-top-60 padding-bottom-30">
				<div class="col-sm-12">
				<?php if ($service_title) : ?>
					<h2 class="section-heading wow zoomIn"><?php echo wp_filter_post_kses($service_title); ?></h2>
				<?php endif; ?>
				
				<?php if ($service_description) : ?>
					<p class="section-description"><?php echo esc_html($service_description); ?></p>
				<?php endif; ?>
				</div>
			</div>
			<div class="row text-center padding-bottom-60">
				<?php 
					for($service =1; $service<4; $service++) 
					{
						if( get_theme_mod('service-page'.$service)) 
						{
							$service_query = new WP_query('page_id='.get_theme_mod('service-page'.$service,true));
							while( $service_query->have_posts() ) 
							{ 
								$service_query->the_post();
								$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
								$img_arr[] = $image;
								$id_arr[] = $post->ID;
							}    
						}
					}
				?>
				<?php if(!empty($id_arr))
				{ ?>
				<?php 
					$i=1;
					foreach($id_arr as $id)
					{ 
						$title	= get_the_title( $id ); 
						$post	= get_post($id); 
						
						$content = $post->post_content;
						$content = apply_filters('the_content', $content);
						$content = str_replace(']]>', ']]>', $content);
				?> 
				<div class="col-md-4 col-md-4 col-xs-12">
					<?php $image = wp_get_attachment_url( get_post_thumbnail_id($post->ID)); ?>
					<?php if( !empty($image) ) { ?>
						<div class="hero-service-box hero-service-image" style="background: url('<?php echo esc_url( $image ); ?>') no-repeat scroll 0 0 / cover rgba(0, 0, 0, 0);">
					<?php } else { ?>
						<div class="hero-service-box hero-service-color">
					<?php
						} 
					?>
					<?php if( get_post_meta(get_the_ID(),'icons', true ) ): ?>
						<i class="fa <?php echo get_post_meta( get_the_ID(),'icons', true); ?>"></i>
					<?php	endif;	?>
					
						<h3><a href="<?php echo esc_url( get_permalink() ); ?>"> <?php echo esc_html($title); ?> </a></h3>
						<?php echo $content;	?>
						<a href="<?php echo esc_url( get_permalink() ); ?>" title=""><?php echo _e('Read More','heropress'); ?></a>
					</div>
				</div>
				<?php $i++; 
				}  ?>
			</div>
			<?php } wp_reset_postdata(); ?>
		</div>
	</section>
<div class="clearfix"></div>
<?php endif; ?>

