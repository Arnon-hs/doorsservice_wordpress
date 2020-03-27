<?php
/**
 * Search form
 *
 * @package Makenzie_Blog
 */
?>

 <form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-form-inner">
		<input type="text" value="" name="s" id="s" placeholder="<?php esc_attr_e( 'Type & Hit Enter ...', 'makenzie-lite' ); ?>" />
		<div class="search-form-submit"><input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Submit', 'makenzie-lite' ); ?>"></div>
	</div>
</form>
