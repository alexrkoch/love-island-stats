<?php
/**
 * Kadence Simple Share options.
 *
 * @package Kadence Simple Share
 */

/**
 * Check for Redux.
 */
function kt_share_run_redux() {
	if ( class_exists( 'Redux' ) ) {
		return;
	}
	require_once KTSS_PATH . '/admin/redux/framework.php';
}
add_action( 'after_setup_theme', 'kt_share_run_redux', 1 );

/**
 * Add Redux admin Panel.
 */
function kt_share_add_sections() {
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}

	$opt_name = 'kt_share';
	$args = array(
		'opt_name'             => $opt_name,
		'display_name'         => 'Kadence Simple Social Share',
		'display_version'      => '',
		'menu_type'            => 'submenu',
		'allow_sub_menu'       => false,
		'page_parent'          => 'themes.php',
		'menu_title'           => __( 'Social Share Settings', 'kadence-simple-share' ),
		'page_title'           => __( 'Kadence Simple Social Share', 'kadence-simple-share' ),
		'google_api_key'       => 'AIzaSyALkgUvb8LFAmrsczX56ZGJx-PPPpwMid0',
		'google_update_weekly' => false,
		'async_typography'     => false,
		'admin_bar'            => false,
		'dev_mode'             => false,
		'use_cdn'              => false,
		'update_notice'        => false,
		'customizer'           => false,
		'forced_dev_mode_off'  => true,
		'page_permissions'     => 'manage_options',
		'menu_icon'            => '',
		'show_import_export'   => false,
		'save_defaults'        => true,
		'page_slug'            => 'ktsharepoptions',
		'ajax_save'            => true,
		'default_show'         => false,
		'show_options_object'  => false,
		'default_mark'         => '',
		'footer_credit' => __( 'Thank you for using Kadence Simple Share by <a href="http://kadencewp.com/" target="_blank">Kadence WP</a>.', 'kadence-simple-share' ),
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

	// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
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
			'icon' => 'dashicons-art',
			'icon_class' => 'dashicons',
			'id' => 'kt_share_options',
			'title' => __( 'Sharing Options', 'kadence-slider' ),
			'desc' => '',
			'fields' => array(
				array(
					'id'      => 'enabled_sharing',
					'type'    => 'sorter',
					'title'   => __( 'Enabled Sharing Sites', 'kadence-simple-share' ),
					'desc'    => __( 'Enabled and rearange the order for your social networks', 'kadence-simple-share' ),
					'options' => array(
						'disabled' => array(
							'linkedin'   => 'Linkedin',
							'digg'   => 'Digg',
							'tumblr'   => 'Tumblr',
							'vk'   => 'VK',
							'stumbleupon'   => 'StumbleUpon',
							'email'   => 'Email',
							'pinterest'   => 'Pinterest',
							'reddit'   => 'Reddit',
							'xing'   => 'XING',
							'whatsapp'   => 'WhatsApp',
						),
						'enabled'  => array(
							'facebook' => 'Facebook',
							'twitter'     => 'Twitter',
						),
					),
				),
				array(
					'id' => 'sharing_style',
					'type' => 'image_select',
					'compiler' => false,
					'customizer' => true,
					'title' => __( 'Sharing Style', 'kadence-simple-share' ),
					'subtitle' => __( 'Choose a style for your social icons', 'kadence-simple-share' ),
					'options' => array(
						'style_01' => array(
							'alt' => 'Style 01',
							'img' => KTSS_URL . '/assets/img/style_01.jpg',
						),
						'style_02' => array(
							'alt' => 'Style 02',
							'img' => KTSS_URL . '/assets/img/style_02.jpg',
						),
						'style_03' => array(
							'alt' => 'Style 03',
							'img' => KTSS_URL . '/assets/img/style_03.jpg',
						),
						'style_04' => array(
							'alt' => 'Style 04',
							'img' => KTSS_URL . '/assets/img/style_04.jpg',
						),
						'style_05' => array(
							'alt' => 'Style 05',
							'img' => KTSS_URL . '/assets/img/style_05.jpg',
						),
					),
					'default' => 'style_01',
				),
				array(
					'id' => 'sharing_size',
					'type' => 'select',
					'title' => __( 'Sharing Size', 'kadence-simple-share' ),
					'options' => array(
						'normal' => __( 'Normal', 'kadence-simple-share' ),
						'large' => __( 'Large', 'kadence-simple-share' ),
					),
					'default' => 'normal',
					'width' => 'width:60%',
				),
				array(
					'id' => 'sharing_text',
					'type' => 'text',
					'customizer' => true,
					'title' => __( 'Optional Text before icons', 'kadence-simple-share' ),
					'default' => '',
				),
				array(
					'id' => 'sharing_location',
					'type' => 'select',
					'title' => __( 'Sharing Placement', 'kadence-simple-share' ),
					'options' => array(
						'before' => __( 'Before Content', 'kadence-simple-share' ),
						'after' => __( 'After Content', 'kadence-simple-share' ),
						'both' => __( 'Both before and after content', 'kadence-simple-share' ),
					),
					'default' => 'before',
					'width' => 'width:60%',
				),
				array(
					'id' => 'sharing_align',
					'type' => 'select',
					'title' => __( 'Sharing Align', 'kadence-simple-share' ),
					'options' => array(
						'left' => __( 'Left', 'kadence-simple-share' ),
						'center' => __( 'Center', 'kadence-simple-share' ),
						'right' => __( 'Right', 'kadence-simple-share' ),
					),
					'default' => 'left',
					'width' => 'width:60%',
				),
				array(
					'id' => 'enable_posts',
					'type' => 'switch',
					'customizer' => false,
					'title' => __( 'Enable for Blog posts?', 'kadence-simple-share' ),
					'subtitle' => __( 'Choose to enable sharing icons on blog posts', 'kadence-simple-share' ),
					'default'       => 1,
				),
				array(
					'id' => 'enable_posts_excerpts',
					'type' => 'switch',
					'customizer' => false,
					'title' => __( 'Enable for Blog posts excerpts?', 'kadence-simple-share' ),
					'subtitle' => __( 'Choose to enable sharing icons on blog posts excerpts', 'kadence-simple-share' ),
					'default'       => 0,
				),
				array(
					'id' => 'excerpt_sharing_location',
					'type' => 'select',
					'title' => __( 'Excerpt Sharing Placement', 'kadence-simple-share' ),
					'options' => array(
						'before' => __( 'Before Content', 'kadence-simple-share' ),
						'after' => __( 'After Content', 'kadence-simple-share' ),
						'both' => __( 'Both before and after content', 'kadence-simple-share' ),
					),
					'default' => 'before',
					'required' => array( 'enable_posts_excerpts', '=', '1' ),
					'width' => 'width:60%',
				),
				array(
					'id' => 'enable_pages',
					'type' => 'switch',
					'customizer' => false,
					'title' => __( 'Enable for Pages?', 'kadence-simple-share' ),
					'subtitle' => __( 'Choose to enable sharing icons on pages', 'kadence-simple-share' ),
					'default'  => 0,
				),
				array(
					'id' => 'enable_portfolio',
					'type' => 'switch',
					'customizer' => false,
					'title' => __( 'Enable for Portfolio posts?', 'kadence-simple-share' ),
					'subtitle' => __( 'Choose to enable sharing icons on portfolio posts', 'kadence-simple-share' ),
					'default'       => 0,
				),
				array(
					'id' => 'enable_products',
					'type' => 'switch',
					'customizer' => false,
					'title' => __( 'Enable for Products?', 'kadence-simple-share' ),
					'subtitle' => __( 'Choose to enable sharing icons on products (note only one positon for products)', 'kadence-simple-share' ),
					'default'       => 1,
				),
				array(
					'id' => 'enable_tooltip',
					'type' => 'switch',
					'customizer' => false,
					'title' => __( 'Enable for tooltips', 'kadence-simple-share' ),
					'subtitle' => __( 'Choose to enable popup tooltips for social links', 'kadence-simple-share' ),
					'default'       => 1,
				),
				array(
					'id' => 'tooltip_align',
					'type' => 'select',
					'title' => __( 'Tooltip Align', 'kadence-simple-share' ),
					'options' => array(
						'bottom' => __( 'Bottom', 'kadence-simple-share' ),
						'top' => __( 'Top', 'kadence-simple-share' ),
					),
					'default' => 'bottom',
					'width' => 'width:60%',
				),
				array(
					'id' => 'tooltip_text',
					'type' => 'text',
					'customizer' => true,
					'title' => __( 'Tooltip text before social network name', 'kadence-simple-share' ),
					'default' => '',
				),
				array(
					'id' => 'pinterest_featured',
					'type' => 'switch',
					'customizer' => false,
					'title' => __( 'Force featured image for Pinterest share', 'kadence-simple-share' ),
					'subtitle' => __( 'This will make the feartured image the only image to share on pinterest', 'kadence-simple-share' ),
					'default'       => 0,
				),
				array(
					'id' => 'sharing_link_exit',
					'type' => 'select',
					'title' => __( 'Load Share Link in', 'kadence-simple-share' ),
					'options' => array(
						'popup' => __( 'Small Popup Window', 'kadence-simple-share' ),
						'newtab' => __( 'New Tab', 'kadence-simple-share' ),
					),
					'default' => 'popup',
					'width' => 'width:60%',
				),
			),
		)
	);

	Redux::setExtensions( 'kt_share', KTSS_PATH . '/admin/options_assets/extensions/' );
}
add_action( 'after_setup_theme', 'kt_share_add_sections', 2 );
/**
 * Override Redux options.
 */
function kt_share_override_redux_css() {
	wp_dequeue_style( 'redux-admin-css' );
	wp_register_style( 'ksp-redux-custom-css', KTSS_URL . '/admin/options_assets/css/style.css', false, 101 );
	wp_enqueue_style( 'ksp-redux-custom-css' );
	// wp_dequeue_style( 'select2-css');
	wp_dequeue_style( 'redux-elusive-icon' );
	wp_dequeue_style( 'redux-elusive-icon-ie7' );
}

add_action( 'redux-enqueue-kt_share', 'kt_share_override_redux_css' );
