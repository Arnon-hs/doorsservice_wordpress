<?php
/**
 * About page configuration
 *
 * @package Shop Isle
 */

/**
 * Class Shoisle_About_Page
 */
class Shopisle_Admin_Page {

	/**
	 * Add the about page.
	 */
	public function do_about_page() {
		$theme_args       = wp_get_theme();
		$this->theme_name = apply_filters( 'ti_wl_theme_name', $theme_args->__get( 'Name' ) );
		$this->theme_slug = $theme_args->__get( 'stylesheet' );

		/*
		 * About page instance
		 */
		$config = array(
			'welcome_notice'      => array(
				'type'            => 'custom',
				'notice_class'    => 'ti-welcome-notice updated',
				'dismiss_option'  => 'shop_isle_notice_dismissed',
				'render_callback' => array( $this, 'welcome_notice_content' ),
			),
			'footer_messages'     => array(
				'type'     => 'custom',
				'messages' => array(
					array(
						'heading'   => __( 'Leave us a review', 'shop-isle' ),
						// translators: %s - theme name
						'text'      => sprintf( __( 'Are you are enjoying %s? We would love to hear your feedback.', 'shop-isle' ), $this->theme_name ),
						'link_text' => __( 'Submit a review', 'shop-isle' ),
						'link'      => 'https://wordpress.org/support/theme/shop-isle/reviews/#new-post',
					),
				),
			),
			'getting_started'     => array(
				'type'    => 'columns-3',
				'title'   => __( 'Getting Started', 'shop-isle' ),
				'content' => array(
					array(
						'title' => esc_html__( 'Recommended actions', 'shop-isle' ),
						'text'  => esc_html__( 'We have compiled a list of steps for you to take so we can ensure that the experience you have using one of our products is very easy to follow.', 'shop-isle' ),
					),
					array(
						'title'  => esc_html__( 'Read full documentation', 'shop-isle' ),
						// translators: %s - theme name
						'text'   => sprintf( esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use %s.', 'shop-isle' ), $this->theme_name ),
						'button' => array(
							'label'     => esc_html__( 'Documentation', 'shop-isle' ),
							'link'      => apply_filters( 'shop-isle-documentation-link', 'https://docs.themeisle.com/article/421-shop-isle-documentation-wordpress-org?utm_medium=aboutshopisle&utm_source=documentation&utm_campaign=shopisle' ),
							'is_button' => false,
							'blank'     => true,
						),
					),
					array(
						'title'  => esc_html__( 'Go to the Customizer', 'shop-isle' ),
						'text'   => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.', 'shop-isle' ),
						'button' => array(
							'label'     => esc_html__( 'Go to the Customizer', 'shop-isle' ),
							'link'      => esc_url( admin_url( 'customize.php' ) ),
							'is_button' => true,
							'blank'     => true,
						),
					),
				),
			),
			'recommended_plugins' => array(
				'type'    => 'plugins',
				'title'   => esc_html__( 'Useful Plugins', 'shop-isle' ),
				'plugins' => array(
					'woocommerce',
					'optimole-wp',
					'themeisle-companion',
					'cartflows',
					'otter-blocks',
					'elementor',
				),
			),
			'support'             => array(
				'type'    => 'columns-3',
				'title'   => __( 'Support', 'shop-isle' ),
				'content' => array(
					array(
						'icon'   => 'dashicons dashicons-sos',
						'title'  => esc_html__( 'Contact Support', 'shop-isle' ),
						// translators: %s - theme name
						'text'   => sprintf( esc_html__( 'We want to make sure you have the best experience using %1$s, and that is why we have gathered all the necessary information here for you. We hope you will enjoy using %1$s as much as we enjoy creating great products.', 'shop-isle' ), $this->theme_name ),
						'button' => array(
							'label'     => esc_html__( 'Contact Support', 'shop-isle' ),
							'link'      => apply_filters( 'shop_isle_contact_support_link', 'https://wordpress.org/themes/shop-isle/' ),
							'is_button' => true,
							'blank'     => true,
						),
					),
					array(
						'icon'   => 'dashicons dashicons-admin-customizer',
						'title'  => esc_html__( 'Create a child theme', 'shop-isle' ),
						'text'   => esc_html__( "If you want to make changes to the theme's files, those changes are likely to be overwritten when you next update the theme. In order to prevent that from happening, you need to create a child theme. For this, please follow the documentation below.", 'shop-isle' ),
						'button' => array(
							'label'     => esc_html__( 'View how to do this', 'shop-isle' ),
							'link'      => 'http://docs.themeisle.com/article/14-how-to-create-a-child-theme',
							'is_button' => false,
							'blank'     => true,
						),
					),
					array(
						'icon'   => 'dashicons dashicons-controls-skipforward',
						'title'  => esc_html__( 'Speed up your site', 'shop-isle' ),
						'text'   => esc_html__( 'If you find yourself in a situation where everything on your site is running very slowly, you might consider having a look at the documentation below where you will find the most common issues causing this and possible solutions for each of the issues.', 'shop-isle' ),
						'button' => array(
							'label'     => esc_html__( 'View how to do this', 'shop-isle' ),
							'link'      => 'http://docs.themeisle.com/article/63-speed-up-your-wordpress-site',
							'is_button' => false,
							'blank'     => true,
						),
					),
				),
			),
			'changelog'           => array(
				'type'  => 'changelog',
				'title' => __( 'Changelog', 'shop-isle' ),
			),
			'custom_tabs'         => array(
				'free_pro' => array(
					'title'           => __( 'Free vs PRO', 'shop-isle' ),
					'render_callback' => array( $this, 'free_pro_render' ),
				),
			),
		);
		if ( class_exists( 'TI_About_Page', false ) ) {
			TI_About_Page::init( apply_filters( 'shop_isle_about_page_array', $config ) );
		}
	}

	/**
	 * Free vs Pro tab content
	 */
	public function free_pro_render() {
		$free_pro = array(
			'free_theme_name'     => 'ShopIsle',
			'pro_theme_name'      => 'ShopIsle Pro',
			'pro_theme_link'      => apply_filters( 'shop_isle_upgrade_link_from_child_theme_filter', 'https://themeisle.com/themes/shop-isle-pro/upgrade/?utm_medium=freevspro&utm_source=getpro&utm_campaign=shopisle' ),
			/* translators: s - theme name */
			'get_pro_theme_label' => sprintf( __( 'Get %s now!', 'shop-isle' ), 'ShopIsle Pro' ),
			'features_type'       => 'table',
			'features'            => array(
				array(
					'title'       => __( 'WooCommerce Compatible', 'shop-isle' ),
					'description' => __( 'The easiest way to set up your online store with WooCommerce plugin.', 'shop-isle' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Frontpage Sections', 'shop-isle' ),
					'description' => __( 'PRO version comes with extra Services section, Ribbon section, Categories section, Shortcodes section, and Map section.', 'shop-isle' ),
					'is_in_lite'  => 'true',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Add new sections', 'shop-isle' ),
					'description' => __( 'You can create a section by adding shortcodes from any plugins.', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Map section', 'shop-isle' ),
					'description' => __( 'Embed your location to your website.', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Services Section', 'shop-isle' ),
					'description' => __( 'You can add as many services as you want. For each service, you can add an icon, a title, a subtitle and its link.', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Ribbon Section', 'shop-isle' ),
					'description' => __( 'Use a smart call-to-action to engage with your visitors.', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Categories section', 'shop-isle' ),
					'description' => __( 'Showcase a specific category of products on the front page.', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Drag and Drop Section Reorder', 'shop-isle' ),
					'description' => __( 'Arrange the sections by your priorities.', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Enhanced Cart', 'shop-isle' ),
					'description' => __( 'The menu mini-cart helps the customers get a fast overview of the products added in the cart.', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Translation Ready', 'shop-isle' ),
					'description' => __( 'ShopIsle Pro is fully compatible with WPML and Polylang. It can easily be used for multilingual sites.', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
				array(
					'title'       => __( 'Quality Support', 'shop-isle' ),
					'description' => __( '24/7 HelpDesk Professional Support', 'shop-isle' ),
					'is_in_lite'  => 'false',
					'is_in_pro'   => 'true',
				),
			),
		);

		$output = '';

		if ( ! empty( $free_pro ) ) {
			if ( ! empty( $free_pro['features_type'] ) ) {
				echo '<div class="feature-section">';
				echo '<div id="free_pro" class="ti-about-page-tab-pane ti-about-page-fre-pro">';
				switch ( $free_pro['features_type'] ) {
					case 'image':
						if ( ! empty( $free_pro['features_img'] ) ) {
							$output .= '<img src="' . $free_pro['features_img'] . '">';
							if ( ! empty( $free_pro['pro_theme_link'] ) && ! empty( $free_pro['get_pro_theme_label'] ) ) {
								$output .= '<a href="' . esc_url( $free_pro['pro_theme_link'] ) . '" target="_blank" class="button button-primary button-hero">' . wp_kses_post( $free_pro['get_pro_theme_label'] ) . '</a>';
							}
						}
						break;
					case 'table':
						if ( ! empty( $free_pro['features'] ) ) {
							$output .= '<table class="free-pro-table">';
							$output .= '<thead>';
							$output .= '<tr class="ti-about-page-text-right">';
							$output .= '<th></th>';
							$output .= '<th>' . esc_html( $free_pro['free_theme_name'] ) . '</th>';
							$output .= '<th>' . esc_html( $free_pro['pro_theme_name'] ) . '</th>';
							$output .= '</tr>';
							$output .= '</thead>';
							$output .= '<tbody>';
							foreach ( $free_pro['features'] as $feature ) {
								$output .= '<tr>';
								if ( ! empty( $feature['title'] ) || ! empty( $feature['description'] ) ) {
									$output .= '<td>';
									$output .= $this->get_feature_title_and_description( $feature );
									$output .= '</td>';
								}
								if ( ! empty( $feature['is_in_lite'] ) && ( $feature['is_in_lite'] == 'true' ) ) {
									$output .= '<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>';
								} else {
									$output .= '<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>';
								}
								if ( ! empty( $feature['is_in_pro'] ) && ( $feature['is_in_pro'] == 'true' ) ) {
									$output .= '<td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>';
								} else {
									$output .= '<td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>';
								}
								echo '</tr>';

							}

							if ( ! empty( $free_pro['pro_theme_link'] ) && ! empty( $free_pro['get_pro_theme_label'] ) ) {
								$output .= '<tr>';
								$output .= '<td>';
								if ( ! empty( $free_pro['banner_link'] ) && ! empty( $free_pro['banner_src'] ) ) {
									$output .= '<a target="_blank" href="' . $free_pro['banner_link'] . '"><img src="' . $free_pro['banner_src'] . '" class="free_vs_pro_banner"></a>';
								}
								$output .= '</td>';
								$output .= '<td colspan="2" class="ti-about-page-text-right"><a href="' . esc_url( $free_pro['pro_theme_link'] ) . '" target="_blank" class="button button-primary button-hero">' . wp_kses_post( $free_pro['get_pro_theme_label'] ) . '</a></td>';
								$output .= '</tr>';
							}
							$output .= '</tbody>';
							$output .= '</table>';
						}
						break;
				}
				echo $output;
				echo '</div>';
				echo '</div>';
			}
		}// End if().
	}

	/**
	 * Display feature title and description
	 *
	 * @param array $feature Feature data.
	 */
	public function get_feature_title_and_description( $feature ) {
		$output = '';
		if ( ! empty( $feature['title'] ) ) {
			$output .= '<h3>' . wp_kses_post( $feature['title'] ) . '</h3>';
		}
		if ( ! empty( $feature['description'] ) ) {
			$output .= '<p>' . wp_kses_post( $feature['description'] ) . '</p>';
		}

		return $output;
	}

	/**
	 * Enqueue Customizer Script.
	 */
	public function enqueue_customizer_script() {
		wp_enqueue_script(
			'shop-isle-customizer-preview',
			get_template_directory_uri() . '/assets/js/admin/customizer.js',
			array(
				'jquery',
			),
			TI_ABOUT_PAGE_VERSION,
			true
		);
	}

	/**
	 * Render welcome notice content
	 */
	public function welcome_notice_content() {
		$theme_args = wp_get_theme();
		$name       = apply_filters( 'ti_wl_theme_name', $theme_args->__get( 'Name' ) );
		$template   = $theme_args->get( 'Template' );
		$slug       = $theme_args->__get( 'stylesheet' );
		$theme_page = ! empty( $template ) ? $template . '-welcome' : $slug . '-welcome';

		$notice_template = '
			<div class="ti-notice-wrapper">
				<div class="ti-notice-text">%1$s</div>
			</div>';

		$ob_btn = sprintf(
			/* translators: 1 - options page url, 2 - button text */
			'<a href="%1$s" class="button button-primary" style="text-decoration: none;">%2$s</a>',
			esc_url( admin_url( 'themes.php?page=' . $theme_page ) ),
			esc_html__( 'Go to the theme settings', 'shop-isle' )
		);

		$content = sprintf(
			/* translators: 1 - notice title, 2 - notice message, 3 - options page button, 4 - starter sites button, 5 - notice closing button */
			'<p>%1$s</p>
					<p>%2$s</p>',
			sprintf(
				/* translators: %s - theme name */
				esc_html__( '%s is now installed and ready to use. We\'ve assembled some links to get you started.', 'shop-isle' ),
				$name
			),
			$ob_btn
		);

		echo sprintf(
			$notice_template,
			$content
		);
	}

	/**
	 * Render admin head css.
	 */
	public function admin_head_css() {
		?>
		<style type="text/css">
			.appearance_page_shop-isle-pro-welcome li[data-tab-id="sites_library"] {
				display: none;
			}

			.appearance_page_shop-isle-pro-welcome #changelog .changelog {
				margin-left: 30px;
			}

			.appearance_page_shop-isle-pro-welcome #changelog .changelog h2 {
				text-align: left;
				font-size: 23px;
				font-weight: 500;
				margin-left: -20px;
			}
		</style>
		<?php
	}
}

$admin = new Shopisle_Admin_Page();
add_action( 'customize_preview_init', array( $admin, 'enqueue_customizer_script' ) );
add_action( 'init', array( $admin, 'do_about_page' ) );
add_action( 'admin_head', array( $admin, 'admin_head_css' ) );
