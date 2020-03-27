<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Makenzie_Blog
 */

	$makenzie_lite_footer_text_left = makenzie_lite_get_theme_mod( 'footer_text_left', __( 'Copyright 2020. All Rights reserved.', 'makenzie-lite' ) );

?>
			</div><!-- #grid-x -->
		</div><!-- #grid container -->
	</div><!-- #content -->

	<footer id="colophon">
		<div class="site-footer">
			<div class="grid-container fluid">
				<div class="grid-x grid-padding-x">
					<?php
					if ( is_active_sidebar( 'footer' ) ) :
						dynamic_sidebar( 'footer' );
					endif;
					?>
				</div>
			</div>
		</div>
	</footer><!-- #footer-widgets -->
	<div class="small-site-footer">
		<div class="grid-container fluid">
			<div class="grid-x grid-padding-x">
				<?php if ( $makenzie_lite_footer_text_left ) : ?>
					<div class="cell small-12 medium-6">
						<?php echo esc_attr( $makenzie_lite_footer_text_left ); ?>
						<?php
						if ( function_exists( 'the_privacy_policy_link' ) ) {
							the_privacy_policy_link( '<span class="privacy_policy">', '</span>' );
						}
						?>
					</div>
				<?php endif; ?>
				<div class="cell small-12 medium-6 medium-text-right">
					<a href="<?php echo esc_url( __( 'https://wplook.com/', 'makenzie-lite' ) ); ?>" rel="nofollow">
						<?php
							printf( __( 'Made with %1$s by %2$s', 'makenzie-lite' ), '<i class="fas fa-heart"></i>', __( 'WPlook Themes', 'makenzie-lite' ) );
						?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
