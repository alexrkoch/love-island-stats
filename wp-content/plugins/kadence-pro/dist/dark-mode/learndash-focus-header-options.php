<?php
/**
 * Dark Mode Options.
 *
 * @package Kadence_Pro
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;
Theme_Customizer::add_settings(
	array(
		'dark_mode_learndash_enable' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'sfwd_lesson_layout',
			'priority'     => 10,
			'default'      => kadence()->default( 'dark_mode_learndash_enable' ),
			'label'        => esc_html__( 'Enable Dark Mode Switch in LearnDash Focus Mode Header?', 'kadence-pro' ),
			'transport'    => 'refresh',
		),
	)
);

