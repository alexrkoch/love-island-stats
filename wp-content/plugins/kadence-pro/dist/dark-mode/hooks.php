<?php
/**
 * Functions for dark mode.
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use function Kadence\render_custom_logo;
use function is_customize_preview;


/**
 * Output Dark mode Mobile Logo.
 */
function dark_mode_mobile_logo() {
	if ( kadence()->option( 'dark_mode_enable' ) && kadence()->option( 'dark_mode_custom_logo' ) ) {
		if ( kadence()->option( 'dark_mode_custom_mobile_logo' ) ) {
			render_custom_logo( 'dark_mode_mobile_logo', 'kadence-dark-mode-logo' );
		} else {
			render_custom_logo( 'dark_mode_logo', 'kadence-dark-mode-logo' );
		}
	}
}
add_action( 'before_kadence_mobile_logo_output', 'Kadence_Pro\dark_mode_mobile_logo' );
/**
 * Output Darkmode Logo.
 */
function dark_mode_logo() {
	if ( kadence()->option( 'dark_mode_enable' ) && kadence()->option( 'dark_mode_custom_logo' ) ) {
		render_custom_logo( 'dark_mode_logo', 'kadence-dark-mode-logo' );
	}
}
add_action( 'before_kadence_logo_output', 'Kadence_Pro\dark_mode_logo' );
/**
 * Output Header Switch.
 */
function header_color_switcher() {
	if ( kadence()->option( 'dark_mode_enable' ) ) {
		echo '<div class="kadence-color-palette-header-switcher">';
		echo output_color_switcher( kadence()->option( 'header_dark_mode_switch_type' ), kadence()->option( 'header_dark_mode_switch_style' ), kadence()->option( 'header_dark_mode_light_icon' ), kadence()->option( 'header_dark_mode_dark_icon' ), kadence()->option( 'header_dark_mode_light_switch_title' ), kadence()->option( 'header_dark_mode_dark_switch_title' ), kadence()->option( 'header_dark_mode_switch_tooltip' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}
}
add_action( 'kadence_header_dark_mode', 'Kadence_Pro\header_color_switcher' );
/**
 * Output Mobile Switch.
 */
function mobile_color_switcher() {
	if ( kadence()->option( 'dark_mode_enable' ) ) {
		echo '<div class="kadence-color-palette-mobile-switcher">';
		echo output_color_switcher( kadence()->option( 'mobile_dark_mode_switch_type' ), kadence()->option( 'mobile_dark_mode_switch_style' ), kadence()->option( 'mobile_dark_mode_light_icon' ), kadence()->option( 'mobile_dark_mode_dark_icon' ), kadence()->option( 'mobile_dark_mode_light_switch_title' ), kadence()->option( 'mobile_dark_mode_dark_switch_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}
}
add_action( 'kadence_mobile_dark_mode', 'Kadence_Pro\mobile_color_switcher' );
/**
 * Output Footer Switch.
 */
function footer_color_switcher() {
	if ( kadence()->option( 'dark_mode_enable' ) ) {
		echo '<div class="kadence-color-palette-footer-switcher">';
		echo output_color_switcher( kadence()->option( 'footer_dark_mode_switch_type' ), kadence()->option( 'footer_dark_mode_switch_style' ), kadence()->option( 'footer_dark_mode_light_icon' ), kadence()->option( 'footer_dark_mode_dark_icon' ), kadence()->option( 'footer_dark_mode_light_switch_title' ), kadence()->option( 'footer_dark_mode_dark_switch_title' ), kadence()->option( 'footer_dark_mode_switch_tooltip' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}
}
add_action( 'kadence_footer_dark_mode', 'Kadence_Pro\footer_color_switcher' );
/**
 * Output Fixed Switch.
 */
function fixed_color_switcher() {
	if ( kadence()->option( 'dark_mode_enable' ) && kadence()->option( 'dark_mode_switch_show' ) ) {
		echo '<div class="kadence-color-palette-fixed-switcher kcpf-position-' . esc_attr( kadence()->option( 'dark_mode_switch_position' ) ) . ' vs-lg-' . ( kadence()->sub_option( 'dark_mode_switch_visibility', 'desktop' ) ? 'true' : 'false' ) . ' vs-md-' . ( kadence()->sub_option( 'dark_mode_switch_visibility', 'tablet' ) ? 'true' : 'false' ) . ' vs-sm-' . ( kadence()->sub_option( 'dark_mode_switch_visibility', 'mobile' ) ? 'true' : 'false' ) . '">';
		echo output_color_switcher( kadence()->option( 'dark_mode_switch_type' ), kadence()->option( 'dark_mode_switch_style' ), kadence()->option( 'dark_mode_light_icon' ), kadence()->option( 'dark_mode_dark_icon' ), kadence()->option( 'dark_mode_light_switch_title' ), kadence()->option( 'dark_mode_dark_switch_title' ), kadence()->option( 'dark_mode_switch_tooltip' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}
}
add_action( 'wp_footer', 'Kadence_Pro\fixed_color_switcher' );
/**
 * Output Header Switch.
 */
function header_learndash_color_switcher() {
	if ( kadence()->option( 'dark_mode_enable' ) && kadence()->option( 'dark_mode_learndash_enable' ) ) {
		echo '<div class="kadence-color-palette-header-switcher">';
		echo output_color_switcher( kadence()->option( 'header_dark_mode_switch_type' ), kadence()->option( 'header_dark_mode_switch_style' ), kadence()->option( 'header_dark_mode_light_icon' ), kadence()->option( 'header_dark_mode_dark_icon' ), kadence()->option( 'header_dark_mode_light_switch_title' ), kadence()->option( 'header_dark_mode_dark_switch_title' ), kadence()->option( 'header_dark_mode_switch_tooltip' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}
}
add_action( 'learndash-focus-header-usermenu-after', 'Kadence_Pro\header_learndash_color_switcher' );
/**
 * Output Switch.
 */
function output_color_switcher( $type = 'icon', $style = 'button', $light_icon = 'sun', $dark_icon = 'moon', $light_text = 'Light', $dark_text = 'Dark', $tooltip = false ) {
	echo '<div class="kadence-color-palette-switcher kcps-style-' . esc_attr( $style ) . ' kcps-type-' . esc_attr( $type ) . '">';
	echo '<button class="kadence-color-palette-toggle kadence-color-toggle" aria-label="' . esc_attr__( 'Change site color palette', 'kadence-pro' ) . '">';
	switch ( $type ) {
		case 'text':
			echo '<span class="kadence-color-palette-light">';
				echo '<span class="kadence-color-palette-label">';
					echo esc_html( $light_text );
				echo '</span>';
			echo '</span>';
			echo '<span class="kadence-color-palette-dark">';
				echo '<span class="kadence-color-palette-label">';
					echo esc_html( $dark_text );
				echo '</span>';
			echo '</span>';
			break;
		case 'both':
			echo '<span class="kadence-color-palette-light">';
				echo '<span class="kadence-color-palette-icon">';
				kadence()->print_icon( $light_icon, $light_text );
				echo '</span>';
				echo '<span class="kadence-color-palette-label">';
					echo esc_html( $light_text );
				echo '</span>';
			echo '</span>';
			echo '<span class="kadence-color-palette-dark">';
				echo '<span class="kadence-color-palette-icon">';
				kadence()->print_icon( $dark_icon, $dark_text );
				echo '</span>';
				echo '<span class="kadence-color-palette-label">';
					echo esc_html( $dark_text );
				echo '</span>';
			echo '</span>';
			break;
		default:
			echo '<span class="kadence-color-palette-light"' . ( $tooltip ? 'data-tooltip-drop="' . esc_attr( $light_text ) . '"' : '' ) . '>';
				echo '<span class="kadence-color-palette-icon">';
				kadence()->print_icon( $light_icon, $light_text );
				echo '</span>';
			echo '</span>';
			echo '<span class="kadence-color-palette-dark"' . ( $tooltip ? 'data-tooltip-drop="' . esc_attr( $dark_text ) . '"' : '' ) . '>';
				echo '<span class="kadence-color-palette-icon">';
				kadence()->print_icon( $dark_icon, $dark_text );
				echo '</span>';
			echo '</span>';
			break;
	}
	echo '</button>';
	echo '</div>';
}
