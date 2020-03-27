<?php
/**
 * Flash Admin Class.
 *
 * @author  ThemeGrill
 * @package flash
 * @since   1.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Flash_Admin' ) ) :

	/**
	 * Flash_Admin Class.
	 */
	class Flash_Admin {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'wp_loaded', array( __CLASS__, 'hide_notices' ) );
			add_action( 'wp_loaded', array( $this, 'admin_notice' ) );
			add_action( 'wp_ajax_import_button', array( $this, 'flash_ajax_import_button_handler' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'flash_ajax_enqueue_scripts' ) );
		}

		/**
		 * Localize array for import button AJAX request.
		 */
		public function flash_ajax_enqueue_scripts() {
			wp_enqueue_script( 'updates' );
			wp_enqueue_script( 'flash-plugin-install-helper', get_template_directory_uri() . '/js/plugin-handle.js', array( 'jquery' ), 1, true );
			wp_localize_script(
				'flash-plugin-install-helper', 'flash_plugin_helper',
				array(
					'activating' => esc_html__( 'Activating ', 'flash' ),
				)
			);
			$translation_array = array(
				'uri'      => esc_url( admin_url( '/themes.php?page=demo-importer&browse=all&flash-hide-notice=welcome' ) ),
				'btn_text' => esc_html__( 'Processing...', 'flash' ),
				'nonce'    => wp_create_nonce( 'flash_demo_import_nonce' ),
			);
			wp_localize_script( 'flash-plugin-install-helper', 'flash_redirect_demo_page', $translation_array );
		}

		/**
		 * Handle the AJAX process while import or get started button clicked.
		 */
		public function flash_ajax_import_button_handler() {
			check_ajax_referer( 'flash_demo_import_nonce', 'security' );
			$state = '';
			if ( is_plugin_active( 'themegrill-demo-importer/themegrill-demo-importer.php' ) ) {
				$state = 'activated';
			} elseif ( file_exists( WP_PLUGIN_DIR . '/themegrill-demo-importer/themegrill-demo-importer.php' ) ) {
				$state = 'installed';
			}
			if ( 'activated' === $state ) {
				$response['redirect'] = admin_url( '/themes.php?page=demo-importer&browse=all&flash-hide-notice=welcome' );
			} elseif ( 'installed' === $state ) {
				$response['redirect'] = admin_url( '/themes.php?page=demo-importer&browse=all&flash-hide-notice=welcome' );
				if ( current_user_can( 'activate_plugin' ) ) {
					$result = activate_plugin( 'themegrill-demo-importer/themegrill-demo-importer.php' );
					if ( is_wp_error( $result ) ) {
						$response['errorCode']    = $result->get_error_code();
						$response['errorMessage'] = $result->get_error_message();
					}
				}
			} else {
				wp_enqueue_script( 'plugin-install' );
				$response['redirect'] = admin_url( '/themes.php?page=demo-importer&browse=all&flash-hide-notice=welcome' );
				/**
				 * Install Plugin.
				 */
				include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				$api      = plugins_api( 'plugin_information', array(
					'slug'   => sanitize_key( wp_unslash( 'themegrill-demo-importer' ) ),
					'fields' => array(
						'sections' => false,
					),
				) );
				$skin     = new WP_Ajax_Upgrader_Skin();
				$upgrader = new Plugin_Upgrader( $skin );
				$result   = $upgrader->install( $api->download_link );
				if ( $result ) {
					$response['installed'] = 'succeed';
				} else {
					$response['installed'] = 'failed';
				}
				// Activate plugin.
				if ( current_user_can( 'activate_plugin' ) ) {
					$result = activate_plugin( 'themegrill-demo-importer/themegrill-demo-importer.php' );
					if ( is_wp_error( $result ) ) {
						$response['errorCode']    = $result->get_error_code();
						$response['errorMessage'] = $result->get_error_message();
					}
				}
			}
			wp_send_json( $response );
			exit();
		}

		/**
		 * Add admin menu.
		 */
		public function admin_menu() {
			$theme = wp_get_theme( get_template() );

			$page = add_theme_page( esc_html__( 'About', 'flash' ) . ' ' . $theme->display( 'Name' ), esc_html__( 'About', 'flash' ) . ' ' . $theme->display( 'Name' ), 'activate_plugins', 'flash-sitelibrary', array(
				$this,
				'sitelibrary_screen',
			) );
			add_action( 'admin_print_styles-' . $page, array( $this, 'enqueue_styles' ) );
		}

		/**
		 * Enqueue styles.
		 */
		public function enqueue_styles() {
			global $flash_version;

			wp_enqueue_style( 'flash-welcome', get_template_directory_uri() . '/css/welcome.css', array(), $flash_version );
		}

		/**
		 * Add admin notice.
		 */
		public function admin_notice() {
			global $flash_version, $pagenow;

			wp_enqueue_style( 'flash-message', get_template_directory_uri() . '/css/message.css', array(), $flash_version );

			// Let's bail on theme activation.
			$notice_nag = get_option( 'flash_admin_notice_welcome' );
			if ( ! $notice_nag ) {
				add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
			}
		}

		/**
		 * Hide a notice if the GET variable is set.
		 */
		public static function hide_notices() {
			if ( isset( $_GET['flash-hide-notice'] ) && isset( $_GET['_flash_notice_nonce'] ) ) {
				if ( ! wp_verify_nonce( $_GET['_flash_notice_nonce'], 'flash_hide_notices_nonce' ) ) {
					wp_die( __( 'Action failed. Please refresh the page and retry.', 'flash' ) );
				}

				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( __( 'Cheatin&#8217; huh?', 'flash' ) );
				}

				$hide_notice = sanitize_text_field( $_GET['flash-hide-notice'] );
				update_option( 'flash_admin_notice_' . $hide_notice, 1 );

				// Hide.
				if ( 'welcome' === $_GET['flash-hide-notice'] ) {
					update_option( 'flash_admin_notice_' . $hide_notice, 1 );
				} else { // Show.
					delete_option( 'flash_admin_notice_' . $hide_notice );
				}
			}
		}

		/**
		 * Show welcome notice.
		 */
		public function welcome_notice() {
			?>
			<div id="message" class="updated flash-message">
				<a class="flash-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'activated' ), add_query_arg( 'flash-hide-notice', 'welcome' ) ), 'flash_hide_notices_nonce', '_flash_notice_nonce' ) ); ?>">
					<?php esc_html_e( 'Dismiss', 'flash' ); ?>
				</a>

				<div class="flash-message-wrapper">
					<div class="flash-logo">
						<img src="<?php echo get_template_directory_uri(); ?>/img/flash-logo.png" alt="<?php esc_html_e( 'Flash', 'flash' ); ?>" /><?php // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped, Squiz.PHP.EmbeddedPhp.SpacingBeforeClose ?>
					</div>

					<p>
						<?php printf( esc_html__( 'Welcome! Thank you for choosing Flash! To fully take advantage of the best our theme can offer please make sure you visit our %1$swelcome page%2$s.', 'flash' ), '<a href="' . esc_url( admin_url( 'themes.php?page=flash-welcome' ) ) . '">', '</a>' ); ?>

						<span class="plugin-install-notice"><?php esc_html_e( 'Clicking the button below will install and activate the ThemeGrill demo importer plugin.', 'flash' ); ?></span>
					</p>

					<div class="submit">
						<a class="btn-get-started button button-primary button-hero" href="#" data-name="" data-slug="" aria-label="<?php esc_html_e( 'Get started with Flash', 'flash' ); ?>"><?php esc_html_e( 'Get started with Flash', 'flash' ); ?></a>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Intro text/links shown to all about pages.
		 *
		 * @access private
		 */
		private function intro() {
			$theme         = wp_get_theme( get_template() );
			$flash_version = $theme['Version'];
			?>
			<div class="header">
				<div class="info">
					<h1>
						<?php esc_html_e( 'About', 'flash' ); ?>
						<?php echo $theme->display( 'Name' ); ?>
						<span class="version-container"><?php echo esc_html( $flash_version ); ?></span>
					</h1>

					<div class="tg-about-text about-text">
						<?php echo $theme->display( 'Description' ); ?>
					</div>

					<a href="https://themegrill.com/" target="_blank" class="wp-badge tg-welcome-logo"></a>
				</div>
			</div>

			<p class="flash-actions">
				<a href="<?php echo esc_url( 'https://themegrill.com/themes/flash/?utm_source=flash-about&utm_medium=theme-info-link&utm_campaign=theme-info' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Theme Info', 'flash' ); ?></a>

				<a href="<?php echo esc_url( apply_filters( 'flash_demo_url', 'https://demo.themegrill.com/flash/demos/' ) ); ?>" class="button button-secondary docs" target="_blank"><?php esc_html_e( 'View Free Demos', 'flash' ); ?></a>

				<a href="<?php echo esc_url( apply_filters( 'flash_pro_theme_url', 'https://themegrill.com/themes/flash/?utm_source=flash-about&utm_medium=view-pro-link&utm_campaign=view-pro#free-vs-pro' ) ); ?>" class="button button-primary docs" target="_blank"><?php esc_html_e( 'View Pro', 'flash' ); ?></a>

				<a href="<?php echo esc_url( apply_filters( 'flash_pro_demo_url', 'https://demo.themegrill.com/flash-pro/demos/' ) ); ?>" class="button button-primary docs" target="_blank"><?php esc_html_e( 'View Pro Demos', 'flash' ); ?></a>

				<?php
				if ( is_plugin_active( 'themegrill-demo-importer/themegrill-demo-importer.php' ) ) { ?>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=demo-importer' ) ); ?>" class="button button-secondary">
						<?php esc_html_e( 'Demo Importer', 'flash' ); ?>
					</a>
				<?php } ?>

				<a href="<?php echo esc_url( 'https://wordpress.org/support/theme/flash/reviews/?filter=5' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Rate this theme', 'flash' ); ?></a>
			</p>

			<h2 class="nav-tab-wrapper">
				<a class="nav-tab <?php if ( empty( $_GET['tab'] ) && $_GET['page'] == 'flash-sitelibrary' ) {
					echo 'nav-tab-active';
				} ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'flash-sitelibrary' ), 'themes.php' ) ) ); ?>">
					<?php esc_html_e( 'Site Library', 'flash' ); ?>
				</a>
				<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'welcome' ) {
					echo 'nav-tab-active';
				} ?>" href="<?php echo esc_url( admin_url( add_query_arg( array(
					'page' => 'flash-sitelibrary',
					'tab'  => 'welcome',
				), 'themes.php' ) ) ); ?>">
					<?php esc_html_e( 'Getting Started', 'flash' ); ?>
				</a>
				<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'supported_plugins' ) {
					echo 'nav-tab-active';
				} ?>" href="<?php echo esc_url( admin_url( add_query_arg( array(
					'page' => 'flash-sitelibrary',
					'tab'  => 'supported_plugins',
				), 'themes.php' ) ) ); ?>">
					<?php esc_html_e( 'Supported Plugins', 'flash' ); ?>
				</a>
				<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'free_vs_pro' ) {
					echo 'nav-tab-active';
				} ?>" href="<?php echo esc_url( admin_url( add_query_arg( array(
					'page' => 'flash-sitelibrary',
					'tab'  => 'free_vs_pro',
				), 'themes.php' ) ) ); ?>">
					<?php esc_html_e( 'Free Vs Pro', 'flash' ); ?>
				</a>
				<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'changelog' ) {
					echo 'nav-tab-active';
				} ?>" href="<?php echo esc_url( admin_url( add_query_arg( array(
					'page' => 'flash-sitelibrary',
					'tab'  => 'changelog',
				), 'themes.php' ) ) ); ?>">
					<?php esc_html_e( 'Changelog', 'flash' ); ?>
				</a>
			</h2>
			<?php
		}

		/**
		 * Site library screen page.
		 */
		public function sitelibrary_screen() {
			$current_tab = empty( $_GET['tab'] ) ? 'library' : sanitize_title( $_GET['tab'] );

			// Look for a {$current_tab}_screen method.
			if ( is_callable( array( $this, $current_tab . '_screen' ) ) ) {
				return $this->{$current_tab . '_screen'}();
			}

			// Fallback to about screen.
			return $this->sitelibrary_display_screen();
		}

		/**
		 * Render site library.
		 */
		public function sitelibrary_display_screen() {
			?>
			<div class="wrap about-wrap">
				<?php
				$this->intro();
				?>

				<div class="plugin-install-notice">
					<?php esc_html_e( 'Clicking the "Import" button in any of the demos below will install and activate the ThemeGrill demo importer plugin.', 'flash' ); ?>
				</div>

				<?php
				// Display site library.
				echo FLash_Site_Library::flash_site_library_page_content();
				?>
			</div>
			<?php
		}

		/**
		 * Welcome screen page.
		 */
		public function welcome_screen() {
			$this->about_screen();
		}

		/**
		 * Output the about screen.
		 */
		public function about_screen() {
			$theme = wp_get_theme( get_template() );
			?>
			<div class="wrap about-wrap">

				<?php $this->intro(); ?>

				<div class="changelog point-releases">
					<div class="under-the-hood two-col">
						<div class="col">
							<h3><?php esc_html_e( 'Import Demo', 'flash' ); ?></h3>
							<p><?php esc_html_e( 'Needs ThemeGrill Demo Importer plugin.', 'flash' ) ?></p>

							<div class="submit">
								<a class="btn-get-started button button-primary button-hero" href="#" data-name="" data-slug="" aria-label="<?php esc_html_e( 'Import', 'flash' ); ?>"><?php esc_html_e( 'Import', 'flash' ); ?></a>
							</div>
						</div>

						<div class="col">
							<h3><?php esc_html_e( 'Theme Customizer', 'flash' ); ?></h3>
							<p><?php esc_html_e( 'All Theme Options are available via Customize screen.', 'flash' ) ?></p>
							<p>
								<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Customize', 'flash' ); ?></a>
							</p>
						</div>

						<div class="col">
							<h3><?php esc_html_e( 'Documentation', 'flash' ); ?></h3>
							<p><?php esc_html_e( 'Please view our documentation page to setup the theme.', 'flash' ) ?></p>
							<p>
								<a href="<?php echo esc_url( 'https://docs.themegrill.com/flash/?utm_source=flash-about&utm_medium=documentation-link&utm_campaign=documentation' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Documentation', 'flash' ); ?></a>
							</p>
						</div>

						<div class="col">
							<h3><?php esc_html_e( 'Got theme support question?', 'flash' ); ?></h3>
							<p><?php esc_html_e( 'Please put it in our dedicated support forum.', 'flash' ) ?></p>
							<p>
								<a href="<?php echo esc_url( 'https://themegrill.com/support-forum/?utm_source=flash-about&utm_medium=support-forum-link&utm_campaign=support-forum' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Support Forum', 'flash' ); ?></a>
							</p>
						</div>

						<div class="col">
							<h3><?php esc_html_e( 'Need more features?', 'flash' ); ?></h3>
							<p><?php esc_html_e( 'Upgrade to PRO version for more exciting features.', 'flash' ) ?></p>
							<p>
								<a href="<?php echo esc_url( 'https://themegrill.com/themes/flash/?utm_source=flash-about&utm_medium=view-pro-link&utm_campaign=view-pro#free-vs-pro' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'View Pro', 'flash' ); ?></a>
							</p>
						</div>

						<div class="col">
							<h3><?php esc_html_e( 'Got sales related question?', 'flash' ); ?></h3>
							<p><?php esc_html_e( 'Please send it via our sales contact page.', 'flash' ) ?></p>
							<p>
								<a href="<?php echo esc_url( 'https://themegrill.com/contact/?utm_source=flash-about&utm_medium=contact-page-link&utm_campaign=contact-page' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Contact Page', 'flash' ); ?></a>
							</p>
						</div>

						<div class="col">
							<h3>
								<?php
								esc_html_e( 'Translate', 'flash' );
								echo ' ' . $theme->display( 'Name' );
								?>
							</h3>
							<p><?php esc_html_e( 'Click below to translate this theme into your own language.', 'flash' ) ?></p>
							<p>
								<a href="<?php echo esc_url( 'http://translate.wordpress.org/projects/wp-themes/flash' ); ?>" class="button button-secondary">
									<?php
									esc_html_e( 'Translate', 'flash' );
									echo ' ' . $theme->display( 'Name' );
									?>
								</a>
							</p>
						</div>

					</div>
				</div>

				<div class="return-to-dashboard flash">
					<?php if ( current_user_can( 'update_core' ) && isset( $_GET['updated'] ) ) : ?>
						<a href="<?php echo esc_url( self_admin_url( 'update-core.php' ) ); ?>">
							<?php is_multisite() ? esc_html_e( 'Return to Updates', 'flash' ) : esc_html_e( 'Return to Dashboard &rarr; Updates', 'flash' ); ?>
						</a> |
					<?php endif; ?>
					<a href="<?php echo esc_url( self_admin_url() ); ?>"><?php is_blog_admin() ? esc_html_e( 'Go to Dashboard &rarr; Home', 'flash' ) : esc_html_e( 'Go to Dashboard', 'flash' ); ?></a>
				</div>
			</div>
			<?php
		}

		/**
		 * Output the changelog screen.
		 */
		public function changelog_screen() {
			global $wp_filesystem;

			?>
			<div class="wrap about-wrap">

				<?php $this->intro(); ?>

				<p class="about-description"><?php esc_html_e( 'View changelog below.', 'flash' ); ?></p>

				<?php
				$changelog_file = apply_filters( 'flash_changelog_file', get_template_directory() . '/README.md' );

				// Check if the changelog file exists and is readable.
				if ( $changelog_file && is_readable( $changelog_file ) ) {
					WP_Filesystem();
					$changelog      = $wp_filesystem->get_contents( $changelog_file );
					$changelog_list = $this->parse_changelog( $changelog );

					echo wp_kses_post( $changelog_list );
				}
				?>
			</div>
			<?php
		}

		/**
		 * Parse changelog from readme file.
		 *
		 * @param  string $content
		 *
		 * @return string
		 */
		private function parse_changelog( $content ) {
			$matches   = null;
			$regexp    = '~==\s*Changelog\s*==(.*)($)~Uis';
			$changelog = '';

			if ( preg_match( $regexp, $content, $matches ) ) {
				$changes = explode( '\r\n', trim( $matches[1] ) );

				$changelog .= '<pre class="changelog">';

				foreach ( $changes as $index => $line ) {
					$changelog .= wp_kses_post( preg_replace( '~(=\s*Version\s*(\d+(?:\.\d+)+)\s*=|$)~Uis', '<span class="title">${1}</span>', $line ) );
				}

				$changelog .= '</pre>';
			}

			return wp_kses_post( $changelog );
		}

		/**
		 * Output the supported plugins screen.
		 */
		public function supported_plugins_screen() {
			?>
			<div class="wrap about-wrap">

				<?php $this->intro(); ?>

				<p class="about-description"><?php esc_html_e( 'This theme recommends following plugins.', 'flash' ); ?></p>
				<ol>
					<li>
						<a href="<?php echo esc_url( 'https://wordpress.org/plugins/social-icons/' ); ?>" target="_blank"><?php esc_html_e( 'Social Icons', 'flash' ); ?></a>
						<?php esc_html_e( ' by ThemeGrill', 'flash' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://wordpress.org/plugins/easy-social-sharing/' ); ?>" target="_blank"><?php esc_html_e( 'Easy Social Sharing', 'flash' ); ?></a>
						<?php esc_html_e( ' by ThemeGrill', 'flash' ); ?>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://wordpress.org/plugins/contact-form-7/' ); ?>" target="_blank"><?php esc_html_e( 'Contact Form 7', 'flash' ); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://wordpress.org/plugins/woocommerce/' ); ?>" target="_blank"><?php esc_html_e( 'WooCommerce', 'flash' ); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://wordpress.org/plugins/restaurantpress/' ); ?>" target="_blank"><?php esc_html_e( 'RestaurantPress', 'flash' ); ?></a>
						<?php esc_html_e( ' by ThemeGrill', 'flash' ); ?></li>
				</ol>

			</div>
			<?php
		}

		/**
		 * Output the free vs pro screen.
		 */
		public function free_vs_pro_screen() {
			?>
			<div class="wrap about-wrap">

				<?php $this->intro(); ?>

				<p class="about-description"><?php esc_html_e( 'Upgrade to PRO version for more exciting features.', 'flash' ); ?></p>

				<table>
					<thead>
					<tr>
						<th class="table-feature-title"><h3><?php esc_html_e( 'Features', 'flash' ); ?></h3></th>
						<th><h3><?php esc_html_e( 'Flash', 'flash' ); ?></h3></th>
						<th><h3><?php esc_html_e( 'Flash Pro', 'flash' ); ?></h3></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td><h3><?php esc_html_e( 'Header Layouts', 'flash' ); ?></h3></td>
						<td><?php esc_html_e( '3', 'flash' ); ?></td>
						<td><?php esc_html_e( '6', 'flash' ); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Google Fonts', 'flash' ); ?></h3></td>
						<td><?php esc_html_e( '3', 'flash' ); ?></td>
						<td><?php esc_html_e( '800+', 'flash' ); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Font Size options', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Color Palette', 'flash' ); ?></h3></td>
						<td><?php esc_html_e( 'Primary Color Option', 'flash' ); ?></td>
						<td><?php esc_html_e( '13+ Color Options', 'flash' ); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Translation Ready', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'WooCommerce Compatible', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'YITH Wishlist Compatible', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'WPML Compatible', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Polylang Compatible', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Footer Copyright Editor', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Custom Widget Area Builder', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Video Background', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Widget Animations', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Demo Content', 'flash' ); ?></h3></td>
						<td><?php esc_html_e( '3', 'flash' ); ?></td>
						<td><?php esc_html_e( '7', 'flash' ); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'Support', 'flash' ); ?></h3></td>
						<td><?php esc_html_e( 'Forum', 'flash' ); ?></td>
						<td><?php esc_html_e( 'Forum + Emails/Support Ticket', 'flash' ); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Heading Widget', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td>
							<span class="dashicons dashicons-yes"></span>(<?php esc_html_e( '3 New Styles', 'flash' ); ?>
							)
						</td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Service Widget', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td>
							<span class="dashicons dashicons-yes"></span>(<?php esc_html_e( '3 New Styles', 'flash' ); ?>
							)
						</td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Call To Action Widget', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td>
							<span class="dashicons dashicons-yes"></span>(<?php esc_html_e( '3 New Styles', 'flash' ); ?>
							)
						</td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Testimonial Widget', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td>
							<span class="dashicons dashicons-yes"></span>(<?php esc_html_e( '4 New Styles', 'flash' ); ?>
							)
						</td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Team Widget', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td>
							<span class="dashicons dashicons-yes"></span>(<?php esc_html_e( '4 New Styles', 'flash' ); ?>
							)
						</td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Portfolio Widget', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span>(<?php esc_html_e( '1 New Style', 'flash' ); ?>
							)
						</td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Animated Number Counter', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td>
							<span class="dashicons dashicons-yes"></span>(<?php esc_html_e( '4 New Styles', 'flash' ); ?>
							)
						</td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Blog', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td>
							<span class="dashicons dashicons-yes"></span>(<?php esc_html_e( '3 New Styles', 'flash' ); ?>
							)
						</td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Google Map', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Post Slider', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Progress Bar', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Instagram Slider', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Pricing Table', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: Separator', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: WooCommerce Category Slider', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e( 'FT: WooCommerce Product Tab', 'flash' ); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="btn-wrapper">
							<a href="<?php echo esc_url( apply_filters( 'flash_pro_theme_url', 'https://themegrill.com/themes/flash/?utm_source=flash-free-vs-pro-table&utm_medium=view-pro-link&utm_campaign=view-pro#free-vs-pro' ) ); ?>" class="button button-secondary docs" target="_blank"><?php _e( 'View Pro', 'flash' ); ?></a>
						</td>
					</tr>
					</tbody>
				</table>

			</div>
			<?php
		}
	}

endif;

return new Flash_Admin();
