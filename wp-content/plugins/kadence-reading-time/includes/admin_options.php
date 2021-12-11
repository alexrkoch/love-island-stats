<?php
/**
 * Setup the reading time settings.
 *
 * @package Kadence Reading Time.
 */

/**
 * Load redux if needed.
 */
function kt_reading_run_redux() {
	if ( class_exists( 'Redux' ) ) {
		return;
	}
	require_once( KT_READING_TIME_PATH . 'includes/redux/framework.php' );
}
add_action( 'after_setup_theme', 'kt_reading_run_redux', 2 );

/**
 * Add settings to panel.
 */
function kt_reading_time_settings() {
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}

	$opt_name = 'kt_reading_time';
	$args = array(
		'opt_name'             => $opt_name,
		'display_name'         => 'Kadence Reading Time',
		'display_version'      => '',
		'menu_type'            => 'submenu',
		'page_parent'          => 'options-general.php',
		'allow_sub_menu'       => false,
		'menu_title'           => __( 'Reading Time', 'kadence-reading-time' ),
		'page_title'           => __( 'Kadence Reading Time', 'kadence-reading-time' ),
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
		'menu_icon'            => 'dashicons-cart',
		'show_import_export'   => false,
		'save_defaults'        => true,
		'page_slug'            => 'ktreadingtime',
		'ajax_save'            => true,
		'default_show'         => false,
		'default_mark'         => '',
		'footer_credit' => __( 'Thank you for using Kadence Reading Time by <a href="http://kadencewp.com/" target="_blank">Kadence WP</a>.', 'kadence-reading-time' ),
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
	$the_theme = wp_get_theme();
	if ( 'Kadence' == $the_theme->get( 'Name' ) || 'kadence' == $the_theme->get( 'Template' ) ) {
		$args['page_parent'] = 'themes.php';
		$args['page_slug' ]  = 'kadence-readingtime';
	}
	$fields = array(
		array(
			'id' => 'enable_reading_single',
			'type' => 'switch',
			'title' => __( 'Insert reading time before content on single posts (you can override on post)', 'kadence-reading-time' ),
			'default' => 1,
		),
		array(
			'id' => 'enable_reading_excerpt',
			'type' => 'switch',
			'title' => __( 'Insert reading time for excerpts on archive pages', 'kadence-reading-time' ),
			'default' => 1,
		),
		array(
			'id' => 'words_per_minute',
			'type'  => 'slider',
			'title' => __( 'Choose Words per minute to calculate read time by.', 'kadence-reading-time' ),
			'subtitle' => __( 'The default is 250', 'kadence-reading-time' ),
			'default'   => '250',
			'min'       => '80',
			'step'      => '2',
			'max'       => '600',
		),
		array(
			'id' => 'pre_label',
			'type' => 'text',
			'title' => __( 'Label', 'ascend' ),
			'subtitle' => __( 'Default: Reading Time:', 'ascend' ),
		),
		array(
			'id' => 'post_label',
			'type' => 'text',
			'title' => __( 'Postfix', 'ascend' ),
			'subtitle' => __( 'Default: minutes', 'ascend' ),
		),
		array(
			'id' => 'post_label_min',
			'type' => 'text',
			'title' => __( 'Postfix for singular, when there is minimum of 1', 'ascend' ),
			'subtitle' => __( 'Default: minute', 'ascend' ),
		),
	);
	if ( 'Kadence' == $the_theme->get( 'Name' ) || 'kadence' == $the_theme->get( 'Template' ) ) {
		$fields[] = array(
			'id' => 'enable_reading_in_meta',
			'type' => 'switch',
			'title' => __( 'Insert reading time in post Meta', 'kadence-reading-time' ),
			'default' => 1,
		);
	}
	Redux::setArgs( $opt_name, $args );
	Redux::setSection(
		$opt_name,
		array(
			'icon' => 'dashicons-media-document',
			'icon_class' => 'dashicons',
			'id' => 'kt_reading_time_settings',
			'title' => __( 'Reading Time Settings', 'kadence-reading-time' ),
			'fields' => $fields,
		)
	);

	Redux::setExtensions( 'kt_reading_time', KT_READING_TIME_PATH . 'includes/options_assets/extensions/' );
}
add_action( 'after_setup_theme', 'kt_reading_time_settings', 2 );

/**
 * Update the styles for the panel.
 */
function kt_reading_time_override_redux_css() {
	wp_dequeue_style( 'redux-admin-css' );
	wp_register_style( 'ksp-redux-custom-css', KT_READING_TIME_URL . 'includes/options_assets/css/style.css', false, 101 );
	wp_enqueue_style( 'ksp-redux-custom-css' );
	wp_dequeue_style( 'redux-elusive-icon' );
	wp_dequeue_style( 'redux-elusive-icon-ie7' );
}

add_action( 'redux-enqueue-kt_reading_time', 'kt_reading_time_override_redux_css' );
