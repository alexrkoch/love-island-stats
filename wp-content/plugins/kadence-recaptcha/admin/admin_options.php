<?php
/**
 * Load Redux.
 */
function kt_recaptcha_run_redux() {
	if ( class_exists( 'Redux' ) ) {
		return;
	}
	require_once KT_RECAPTCHA_PATH . 'admin/redux/framework.php';
}
add_action( 'after_setup_theme', 'kt_recaptcha_run_redux', 1 );

add_action( 'after_setup_theme', 'kt_recaptcha_add_sections', 2 );
function kt_recaptcha_add_sections() {

	if ( ! class_exists( 'Redux' ) ) {
		return;
	}

	$opt_name = 'kt_recaptcha';
	$args = array(
		'opt_name'             => $opt_name,
		'display_name'         => 'Kadence Recaptcha',
		'display_version'      => '',
		'page_parent'          => ( apply_filters( 'kadence_recaptcha_network', false ) ? 'settings.php' : 'options-general.php' ),
		'menu_type'            => 'submenu',
		'allow_sub_menu'       => false,
		'menu_title'           => __( 'Recaptcha Settings', 'kadence-recaptcha' ),
		'page_title'           => __( 'Kadence Recaptcha Settings', 'kadence-recaptcha' ),
		'google_api_key'       => 'AIzaSyALkgUvb8LFAmrsczX56ZGJx-PPPpwMid0',
		'google_update_weekly' => false,
		'async_typography'     => false,
		'admin_bar'            => false,
		'dev_mode'             => false,
		'use_cdn'              => false,
		'update_notice'        => false,
		'network_admin'        => apply_filters( 'kadence_recaptcha_network', false ),
		'network_sites'        => ( apply_filters( 'kadence_recaptcha_network', false ) ? false : true ),
		'database'             => ( apply_filters( 'kadence_recaptcha_network', false ) ? 'network' : '' ),
		'customizer'           => false,
		'forced_dev_mode_off'  => true,
		'page_permissions'     => 'manage_options',
		'menu_icon'            => 'dashicons-cart',
		'show_import_export'   => false,
		'save_defaults'        => true,
		'page_slug'            => 'ktrecaptchaoptions',
		'ajax_save'            => true,
		'default_show'         => false,
		'default_mark'         => '',
		'footer_credit'        => __( 'Thank you for using Kadence Recaptcha by <a href="https://kadencewp.com/" target="_blank">Kadence WP</a>.', 'kadence-recaptcha' ),
		'hints'                => array(
			'icon'          => 'kt-icon-question',
			'icon_position' => 'right',
			'icon_color'    => '#444',
			'icon_size'     => 'normal',
			'tip_style'     => array(
				'color'   => 'dark',
				'shadow'  => true,
				'rounded' => false,
				'style'   => '',
			),
			'tip_position'  => array(
				'my' => 'top left',
				'at' => 'bottom right',
			),
			'tip_effect'    => array(
				'show' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'mouseover',
				),
				'hide' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'click mouseleave',
				),
			),
		),
	);

	$args['share_icons'][] = array(
		'url' => 'https://www.facebook.com/KadenceWP',
		'title' => 'Follow Kadence WP on Facebook',
		'icon' => 'dashicons dashicons-facebook',
	);
	$args['share_icons'][] = array(
		'url' => 'https://www.twitter.com/KadenceWP',
		'title' => 'Follow Kadence WP on Twitter',
		'icon' => 'dashicons dashicons-twitter',
	);
	$args['share_icons'][] = array(
		'url' => 'https://www.instagram.com/KadenceWP',
		'title' => 'Follow Kadence WP on Instagram',
		'icon' => 'dashicons dashicons-format-image',
	);
	$args['share_icons'][] = array(
		'url' => 'http://www.youtube.com/c/KadenceWP',
		'title' => 'Follow Kadence WP on YouTube',
		'icon' => 'dashicons dashicons-video-alt3',
	);

	// Add content after the form.
	// $args['footer_text'] = '';

	Redux::setArgs( $opt_name, $args );
	Redux::setSection(
		$opt_name,
		array(
			'icon' => 'dashicons-clipboard',
			'icon_class' => 'dashicons',
			'id' => 'kt_recaptcha_settings',
			'title' => __( 'Google reCaptcha API Keys', 'kadence-recaptcha' ),
			'desc' => '',
			'fields' => array(
				array(
					'id' => 'enable_v3',
					'type' => 'select',
					'title' => __( 'reCaptcha version', 'kadence-recaptcha' ),
					'options' => array(
						0 => __( 'Version 2', 'kadence-recaptcha' ),
						1 => __( 'Version 3', 'kadence-recaptcha' ),
					),
					'default' => 0,
					'width' => 'width:60%',
				),
				array(
					'id' => 'kt_recaptcha_api_info',
					'type' => 'info',
					'desc' => __( 'Google reCaptcha V2 API Keys', 'kadence-recaptcha' ),
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'kt_re_site_key',
					'type' => 'text',
					'title' => __( 'Site Key:', 'kadence-recaptcha' ),
					'subtitle' => sprintf( __( 'Get API keys here: %s', 'kadence-recaptcha' ), '<a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/</a>' ),
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'kt_re_secret_key',
					'type' => 'text',
					'title' => __( 'Secret Key:', 'kadence-recaptcha' ),
					'subtitle' => sprintf( __( 'Get API keys here: %s', 'kadence-recaptcha' ), '<a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/</a>' ),
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'kt_recaptcha_v3_api_info',
					'type' => 'info',
					'desc' => __( 'Google reCaptcha V3 API Keys', 'kadence-recaptcha' ),
					'required' => array( 'enable_v3', '=', 1 ),
				),
				array(
					'id' => 'v3_re_site_key',
					'type' => 'text',
					'title' => __( 'Site Key:', 'kadence-recaptcha' ),
					'subtitle' => sprintf( __( 'Get API keys here: %s', 'kadence-recaptcha' ), '<a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/</a>' ),
					'required' => array( 'enable_v3', '=', 1 ),
				),
				array(
					'id' => 'v3_re_secret_key',
					'type' => 'text',
					'title' => __( 'Secret Key:', 'kadence-recaptcha' ),
					'subtitle' => sprintf( __( 'Get API keys here: %s', 'kadence-recaptcha' ), '<a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/</a>' ),
					'required' => array( 'enable_v3', '=', 1 ),
				),
				array(
					'id' => 'kt_recaptcha_enable_info',
					'type' => 'info',
					'desc' => __( 'reCaptcha form options', 'kadence-recaptcha' ),
				),
				array(
					'id' => 'enable_for_comments',
					'type' => 'switch',
					'title' => __( 'Enable for Post and Page Comments', 'kadence-recaptcha' ),
					'default' => 1,
				),
				array(
					'id' => 'enable_for_login',
					'type' => 'switch',
					'title' => __( 'Enable for Login', 'kadence-recaptcha' ),
					'default' => 0,
				),
				array(
					'id' => 'enable_for_lost_password',
					'type' => 'switch',
					'title' => __( 'Enable for Lost Password Form', 'kadence-recaptcha' ),
					'default' => 0,
				),
				array(
					'id' => 'enable_for_registration',
					'type' => 'switch',
					'title' => __( 'Enable for Registration', 'kadence-recaptcha' ),
					'default' => 0,
				),
				array(
					'id' => 'enable_for_woocommerce_checkout',
					'type' => 'switch',
					'title' => __( 'Enable for Woocommerce Checkout', 'kadence-recaptcha' ),
					'default' => 0,
				),
				array(
					'id' => 'enable_for_woocommerce',
					'type' => 'switch',
					'title' => __( 'Enable for Woocommerce Reviews', 'kadence-recaptcha' ),
					'default' => 1,
				),
				array(
					'id' => 'kt_recaptcha_design_info',
					'type' => 'info',
					'desc' => __( 'Google reCaptcha Style', 'kadence-recaptcha' ),
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'kt_re_theme',
					'type' => 'select',
					'title' => __( 'Choose a theme', 'kadence-recaptcha' ),
					'options' => array(
						'light' => __( 'Light', 'kadence-recaptcha' ),
						'dark' => __( 'Dark', 'kadence-recaptcha' ),
					),
					'default' => 'light',
					'width' => 'width:60%',
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'kt_re_size',
					'type' => 'select',
					'title' => __( 'Choose a size', 'kadence-recaptcha' ),
					'options' => array(
						'normal' => __( 'Normal', 'kadence-recaptcha' ),
						'compact' => __( 'Compact', 'kadence-recaptcha' ),
					),
					'default' => 'normal',
					'width' => 'width:60%',
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'kt_re_type',
					'type' => 'select',
					'title' => __( 'Choose a type', 'kadence-recaptcha' ),
					'options' => array(
						'image' => __( 'Image', 'kadence-recaptcha' ),
						'audio' => __( 'Audio', 'kadence-recaptcha' ),
					),
					'default' => 'image',
					'width' => 'width:60%',
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'kt_re_align',
					'type' => 'select',
					'title' => __( 'Choose a alignment', 'kadence-recaptcha' ),
					'options' => array(
						'left' => __( 'Left', 'kadence-recaptcha' ),
						'center' => __( 'Center', 'kadence-recaptcha' ),
						'right' => __( 'Right', 'kadence-recaptcha' ),
					),
					'default' => 'left',
					'width' => 'width:60%',
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'kt_recaptcha_gdpr_info',
					'type' => 'info',
					'desc' => __( 'Privacy Consent', 'kadence-recaptcha' ),
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'enable_permission',
					'type' => 'switch',
					'title' => __( 'Enable privacy consent required before reCaptcha scripts are loaded', 'kadence-recaptcha' ),
					'default' => 0,
					'required' => array( 'enable_v3', '!=', 1 ),
				),
				array(
					'id' => 'consent_label',
					'type' => 'text',
					'title' => __( 'Consent Label', 'kadence-recaptcha' ),
					'required' => array(
						array( 'enable_permission', '=', 1 ),
						array( 'enable_v3', '!=', 1 ),
					),
				),
				array(
					'id' => 'consent_btn',
					'type' => 'text',
					'title' => __( 'Consent Button Text', 'kadence-recaptcha' ),
					'required' => array(
						array( 'enable_permission', '=', 1 ),
						array( 'enable_v3', '!=', 1 ),
					),
				),
				array(
					'id' => 'consent_cookie',
					'type' => 'text',
					'title' => __( 'Consent Cookie Name', 'kadence-recaptcha' ),
					'subtitle' => __( 'You can use a custom cookie name or one that matches another consent plugin.', 'kadence-recaptcha' ),
					'required' => array(
						array( 'enable_permission', '=', 1 ),
						array( 'enable_v3', '!=', 1 ),
					),
				),
			),
		)
	);
	if ( Kadence_Recaptcha::is_kadence_theme() ) {
		Redux::setSection(
			$opt_name,
			array(
				'icon' => 'dashicons-art',
				'icon_class' => 'dashicons',
				'id' => 'kt_theme_recaptcha_settings',
				'title' => __( 'Theme Specific Enable Options', 'kadence-recaptcha' ),
				'desc' => '',
				'fields' => array(
					array(
						'id' => 'enable_for_contact',
						'type' => 'switch',
						'title' => __( 'Enable for theme contact form', 'kadence-recaptcha' ),
						'default' => 0,
					),
					array(
						'id' => 'enable_for_testimonial',
						'type' => 'switch',
						'title' => __( 'Enable for testimonial form', 'kadence-recaptcha' ),
						'default' => 0,
					),
				),
			)
		);
	}

	Redux::setExtensions( 'kt_recaptcha', KT_RECAPTCHA_PATH . 'admin/options_assets/extensions/' );
}
function kt_recaptcha_override_redux_css() {
	wp_dequeue_style( 'redux-admin-css' );
	wp_register_style( 'ksp-redux-custom-css', KT_RECAPTCHA_URL . 'admin/options_assets/css/style.css', false, 101 );
	wp_enqueue_style( 'ksp-redux-custom-css' );
	wp_dequeue_style( 'redux-elusive-icon' );
	wp_dequeue_style( 'redux-elusive-icon-ie7' );
}

add_action( 'redux-enqueue-kt_recaptcha', 'kt_recaptcha_override_redux_css' );
