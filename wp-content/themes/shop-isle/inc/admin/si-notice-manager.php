<?php
/**
 *  Notice manager class.
 *
 * @package ShopIsle
 */

/**
 * Class Notice_Manager
 */
class Si_Notice_Manager {

	/**
	 *  Hooks for notices.
	 */
	public function init() {
		add_action( 'admin_notices', array( $this, 'rop_notice' ) );
		add_action( 'admin_init', array( $this, 'ignore_rop_notice' ) );

	}

	/**
	 *  Notice render function
	 */
	public function rop_notice() {
		if ( ! $this->should_display_notice() ) {
			return;
		}

		echo '<div class="notice notice-warning" style="position:relative;">';
		printf( '<a href="%s" class="notice-dismiss" style="text-decoration:none;"></a>', '?rop_nag_ignore=0' );
		echo '<p>';

		printf(
			/* translators: Link to documentation */
			esc_html__( 'You seem to have a large number of products on your website! %s', 'shop-isle' ),
			sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				esc_url( 'https://docs.revive.social/article/978-using-revive-old-posts-with-woocommerce?utm_source=wp_dashboard&utm_campaign=rop_notice' ),
				esc_html__( 'Learn how to increase product engagement using our Revive Old Posts plugin!', 'shop-isle' )
			)
		);
		echo '</p>';
		echo '</div>';
	}

	/**
	 *  Decide if the notice should be displayed or not.
	 */
	private function should_display_notice() {
		if ( ! class_exists( 'WooCommerce', false ) ) {
			return false;
		}

		$is_dismissed = get_option( 'si_rop_notice', 'false' );
		if ( $is_dismissed === 'true' ) {
			return false;
		}

		$total_products = count(
			get_posts(
				array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'fields'         => 'ids',
					'posts_per_page' => '-1',
				)
			)
		);
		if ( $total_products < 20 ) {
			return false;
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Ignore notice.
	 */
	public function ignore_rop_notice() {
		/* If user clicks to ignore the notice, update the option */
		if ( isset( $_GET['rop_nag_ignore'] ) && '0' == $_GET['rop_nag_ignore'] ) {
			update_option( 'si_rop_notice', 'true' );
		}
	}
}

$notice_manager = new Si_Notice_Manager();
$notice_manager->init();
