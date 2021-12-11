<?php
/*
Plugin Name: Kadence Reading Time
Description: A Simple plugin to add reading time to your posts.
Version: 1.0.3
Author: Kadence WP
Author URI: http://kadenceWP.com/
License: GPLv2 or later
Text Domain: kadence-reading-time
*/

class Kadence_Reading_Time {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
	}
	public function on_plugins_loaded() {

		define( 'KT_READING_TIME_PATH', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
		define( 'KT_READING_TIME_URL', plugin_dir_url( __FILE__ ) );
		define( 'KT_READING_TIME_VERSION', '1.0.3' );

		// Admin Options.
		require_once KT_READING_TIME_PATH . 'includes/admin_options.php';
		require_once KT_READING_TIME_PATH . 'includes/metaboxes.php';

		add_shortcode( 'kt_reading_time', array( $this, 'shortcode_function' ) );

		load_plugin_textdomain( 'kadence-reading-time', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		add_action( 'init', array( $this, 'on_init' ) );

	}
	public function on_init() {
		$kt_reading_time = get_option( 'kt_reading_time' );
		$the_theme = wp_get_theme();
		if ( $kt_reading_time['enable_reading_single'] ) {
			if ( 'Ascend - Premium' == $the_theme->get( 'Name' ) || 'ascend_premium' == $the_theme->get( 'Template' ) ) {
				add_action( 'ascend_after_post_meta', array( $this, 'ascend_content' ) );
			} else if ( 'Virtue - Premium' == $the_theme->get( 'Name' ) || 'virtue_premium' == $the_theme->get( 'Template' ) ) {
				add_action( 'virtue_after_post_meta_subhead', array( $this, 'virtue_content' ) );
				add_action( 'virtue_bold_after_post_meta_subhead', array( $this, 'vbold_content' ) );
			} else if ( 'Pinnacle Premium' == $the_theme->get( 'Name' ) || 'pinnacle_premium' == $the_theme->get( 'Template' ) ) {
				add_action( 'pinnacle_after_post_meta_subhead', array( $this, 'pinnacle_content' ) );
			} else if ( 'Kadence' == $the_theme->get( 'Name' ) || 'kadence' == $the_theme->get( 'Template' ) ) {
				if ( isset( $kt_reading_time['enable_reading_in_meta'] ) && 0 == $kt_reading_time['enable_reading_in_meta'] ) {
					add_filter( 'the_content', array( $this, 'filter_the_content' ) );
				} else {
					add_action( 'kadence_after_entry_meta', array( $this, 'kadence_content' ) );
				}
			} else {
				add_filter( 'the_content', array( $this, 'filter_the_content' ) );
			}
		}
		if ( $kt_reading_time['enable_reading_excerpt'] ) {
			if ( 'Ascend - Premium' == $the_theme->get( 'Name' ) || 'ascend_premium' == $the_theme->get( 'Template' ) ) {
				add_action( 'ascend_after_post_meta', array( $this, 'ascend_excerpt' ) );
				add_action( 'ascend_after_grid_post_footer_meta', array( $this, 'ascend_grid_excerpt' ) );
			} else if ( 'Virtue - Premium' == $the_theme->get( 'Name' ) || 'virtue_premium' == $the_theme->get( 'Template' ) ) {
				add_action( 'virtue_after_post_meta_subhead', array( $this, 'virtue_excerpt' ) );
				add_action( 'virtue_after_post_meta_tooltip', array( $this, 'virtue_grid_excerpt' ) );
				add_action( 'virtue_bold_after_post_meta_subhead', array( $this, 'vbold_excerpt' ) );
			} else if ( 'Pinnacle Premium' == $the_theme->get( 'Name' ) || 'pinnacle_premium' == $the_theme->get( 'Template' ) ) {
				add_action( 'pinnacle_after_post_meta_subhead', array( $this, 'pinnacle_excerpt' ) );
			} else if ( 'Kadence' == $the_theme->get( 'Name' ) || 'kadence' == $the_theme->get( 'Template' ) ) {
				if ( isset( $kt_reading_time['enable_reading_in_meta'] ) && 0 == $kt_reading_time['enable_reading_in_meta'] ) {
					add_filter( 'get_the_excerpt', array( $this, 'filter_the_excerpt' ) );
				} else {
					add_action( 'kadence_blocks_post_loop_header_meta', array( $this, 'kadence_excerpt' ), 30 );
					add_action( 'kadence_after_loop_entry_meta', array( $this, 'kadence_excerpt' ) );
				}
			} else {
				add_filter( 'get_the_excerpt', array( $this, 'filter_the_excerpt' ) );
			}
		}
	}
	static function getlabels( $kt_reading_time ) {
		if ( isset( $kt_reading_time['pre_label'] ) && ! empty( $kt_reading_time['pre_label'] ) ) {
			$label = $kt_reading_time['pre_label'];
		} else {
			$label = __( 'Reading Time:', 'kadence-reading-time' );
		}
		if ( isset( $kt_reading_time['post_label'] ) && ! empty( $kt_reading_time['post_label'] ) ) {
			$postfix = $kt_reading_time['post_label'];
		} else {
			$postfix = __( 'minutes', 'kadence-reading-time' );
		}
		if ( isset( $kt_reading_time['post_label_min'] ) && ! empty( $kt_reading_time['post_label_min'] ) ) {
			$postfix_singular = $kt_reading_time['post_label_min'];
		} else {
			$postfix_singular = __( 'minute', 'kadence-reading-time' );
		}
		return array(
			'label' => $label,
			'postfix' => $postfix,
			'postfix_singular' => $postfix_singular,
		);
	}
	public function get_inner_string( $postid ) {
		global $kt_reading_time;
		$thelabels = $this->getlabels( $kt_reading_time );

		$custom_time = get_post_meta( $postid, '_kt_custom_reading_time', true );
		if ( isset( $custom_time ) && ! empty( $custom_time ) ) {
			$reading_time = $custom_time;
		} else {
			$reading_time = $this->calculate_reading_time( $postid, $kt_reading_time );
		}

		if ( $reading_time > 1 ) {
			$post_fix = $thelabels['postfix'];
		} else {
			$post_fix = $thelabels['postfix_singular'];
		}
		return '<span class="kt-reading-time-label">' . esc_html( $thelabels['label'] ) . '</span> ' . esc_html( $reading_time ) . ' <span class="kt-reading-time-postfix">' . esc_html( $post_fix ) . '</span>';
	}
	public function vbold_content() {
		// Make sure single
		if ( ! is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<div class="kt-reading-time" style="display:inline"><span class="kt-reading-middle-dot"> - </span>' . wp_kses_post( $inner ) . '</div>';
	}
	public function vbold_excerpt() {
		// Make sure not single
		if ( is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<div class="kt-reading-time" style="display:inline"><span class="kt-reading-middle-dot"> - </span>' . wp_kses_post( $inner ) . '</div>';
	}
	public function ascend_content() {
		// Make sure single
		if ( ! is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<div class="kt-reading-time" style="display:inline-block"><span class="kt-reading-middle-dot">&middot;</span> ' . wp_kses_post( $inner ) . '</div>';
	}
	public function pinnacle_content() {
		// Make sure single
		if ( ! is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<div class="kt-reading-time" style="display:inline-block"><span class="kt-reading-middle-dot">&middot;</span> <span style="font-weight: 700; text-transform: uppercase;">' . wp_kses_post( $inner ) . '</span></div>';
	}
	public function pinnacle_excerpt() {
		// Make sure not single
		if ( is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<div class="kt-reading-time" style="display:inline-block"><span class="kt-reading-middle-dot">&middot;</span> <span style="font-weight: 700; text-transform: uppercase;">' . wp_kses_post( $inner ) . '</span></div>';
	}
	public function virtue_content() {
		// Make sure single
		if ( ! is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<span class="kt-reading-time-divider">|</span>  <div class="kt-reading-time" style="display:inline-block"><i class="icon-clock"></i> ' . wp_kses_post( $inner ) . '</div>';
	}
	public function kadence_content() {
		// Make sure single
		if ( ! is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<span class="kt-reading-time-wrap"><span class="kt-reading-time"> ' . wp_kses_post( $inner ) . ' </span></span>';
	}
	public function kadence_excerpt() {
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<span class="kt-reading-time-wrap"><span class="kt-reading-time"> ' . wp_kses_post( $inner ) . ' </span></span>';
	}
	public function virtue_excerpt() {
		// Make sure not single
		if ( is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<span class="kt-reading-time-divider">|</span>  <div class="kt-reading-time" style="display:inline-block"><i class="icon-clock"></i> ' . wp_kses_post( $inner ) . '</div>';
	}
	public function virtue_grid_excerpt() {
		// Make sure not single
		if ( is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		global $kt_reading_time;
		$thelabels = $this->getlabels( $kt_reading_time );

		$custom_time = get_post_meta( $postid, '_kt_custom_reading_time', true );
		if ( isset( $custom_time ) && ! empty( $custom_time ) ) {
			$reading_time = $custom_time;
		} else {
			$reading_time = $this->calculate_reading_time( $postid, $kt_reading_time );
		}
		if ( $reading_time > 1 ) {
			$post_fix = $thelabels['postfix'];
		} else {
			$post_fix = $thelabels['postfix_singular'];
		}

		echo '<span class="kt-reading-time-divider">|</span> <div class="kt-reading-time" style="display:inline-block" data-toggle="tooltip" data-placement="top" data-original-title="' . esc_attr( $thelabels['label'] ) . ' ' . esc_attr( $reading_time ) . ' ' . esc_attr( $post_fix ) . '"><i class="icon-clock"></i></div>';
	}
	public function ascend_excerpt() {
		// Make sure not single
		if ( is_single() ) {
			return;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		$inner = $this->get_inner_string( $postid );

		echo '<div class="kt-reading-time" style="display:inline-block"><span class="kt-reading-middle-dot">&middot;</span> ' . wp_kses_post( $inner ) . '</div>';
	}
	public function ascend_grid_excerpt() {
		// Make sure not single
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return;
		}
		global $kt_reading_time;
		$thelabels = $this->getlabels( $kt_reading_time );

		$custom_time = get_post_meta( $postid, '_kt_custom_reading_time', true );
		if ( isset( $custom_time ) && ! empty( $custom_time ) ) {
			$reading_time = $custom_time;
		} else {
			$reading_time = $this->calculate_reading_time( $postid, $kt_reading_time );
		}
		if ( $reading_time > 1 ) {
			$post_fix = $thelabels['postfix'];
		} else {
			$post_fix = $thelabels['postfix_singular'];
		}
		echo '<span class="kt-reading-time" style="float:right; padding:0 5px;"><span><span class="kt_color_gray" data-toggle="tooltip" data-placement="top" data-original-title="' . esc_attr( $thelabels['label'] ) . ' ' . esc_attr( $reading_time ) . ' ' . esc_attr( $post_fix ) . '">
	                 <i class="kt-icon-clock-o"></i>
                </span></span></span>';

	}
	public function images_time( $count_images, $words_per_minute ) {
		$additional_time = 0;
		// For the first image add 12 seconds, second image add 11, ..., for image 10+ add 3 seconds
		for ( $i = 1; $i <= $count_images; $i++ ) {
			if ( $i >= 10 ) {
				$additional_time += 3 * (int) $words_per_minute / 60;
			} else {
				$additional_time += ( 12 - ( $i - 1 ) ) * (int) $words_per_minute / 60;
			}
		}

		return $additional_time;
	}
	public function calculate_reading_time( $postid, $kt_reading_time ) {

		$post = get_post( $postid );

		$words = str_word_count( strip_tags( $post->post_content ) );
		$count_images = substr_count( strtolower( $post->post_content ), '<img ' );
		$image_words = $this->images_time( $count_images, $kt_reading_time['words_per_minute'] );
		$total_words = $words + $image_words;
		$reading_time = ceil( $total_words / $kt_reading_time['words_per_minute'] );

		// If the reading time is 0 then return it as < 1 instead of 0.
		if ( $reading_time < 1 ) {
			$reading_time = __( '< 1', 'reading-time-wp' );
		}

		return $reading_time;
	}
	function filter_the_content( $content ) {
		if ( 'post' != get_post_type() ) {
			return $content;
		}
		// Make sure this is the real thing.
		if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) {
			return $content;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return $content;
		}
		$string = $this->get_inner_string( $postid );

		return '<div class="kt-reading-time">' . $string . '</div>' . $content;

	}

	public function filter_the_excerpt( $content ) {
		if ( 'post' != get_post_type() ) {
			return $content;
		}
		$postid = get_the_ID();
		$disable = get_post_meta( $postid, '_kt_disable_reading_time', true );
		if ( isset( $disable ) && 'true' == $disable ) {
			return $content;
		}
		$string = $this->get_inner_string( $postid );

		return '<div class="kt-reading-time">' . $string . '</div>' . $content;
	}
	public function shortcode_function( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'label' => '',
					'postfix' => '',
					'postfix_singular' => '',
				),
				$atts
			)
		);

		global $kt_reading_time;
		if ( ! empty( $label ) ) {
			$label = $label;
		} else if ( isset( $kt_reading_time['pre_label'] ) && ! empty( $kt_reading_time['pre_label'] ) ) {
			$label = $kt_reading_time['pre_label'];
		} else {
			$label = __( 'Reading Time:', 'kadence-reading-time' );
		}
		if ( ! empty( $postfix ) ) {
			$postfix = $postfix;
		} else if ( isset( $kt_reading_time['post_label'] ) && ! empty( $kt_reading_time['post_label'] ) ) {
			$postfix = $kt_reading_time['post_label'];
		} else {
			$postfix = __( 'minutes', 'kadence-reading-time' );
		}
		if ( ! empty( $postfix_singular ) ) {
			$postfix_singular = $postfix_singular;
		} else if ( isset( $kt_reading_time['post_label_min'] ) && ! empty( $kt_reading_time['post_label_min'] ) ) {
			$postfix_singular = $kt_reading_time['post_label_min'];
		} else {
			$postfix_singular = __( 'minute', 'kadence-reading-time' );
		}

		$postid = get_the_ID();

		$custom_time = get_post_meta( $postid, '_kt_custom_reading_time', true );
		if ( isset( $custom_time ) && ! empty( $custom_time ) ) {
			$reading_time = $custom_time;
		} else {
			$reading_time = $this->calculate_reading_time( $postid, $kt_reading_time );
		}

		if ( $reading_time > 1 ) {
			$post_fix = $postfix;
		} else {
			$post_fix = $postfix_singular;
		}
		return '<div class="kt-reading-time kt-reading-time-shortcode"><span class="kt-reading-time-label">' . esc_html( $label ) . '</span> ' . esc_html( $reading_time ) . ' <span class="kt-reading-time-postfix">' . esc_html( $post_fix ) . '</span></div>';
	}
}

new Kadence_Reading_Time();
/**
 * Plugin Updates
 */
function kt_reading_time_updating() {
	require_once KT_READING_TIME_PATH . 'kadence-update-checker/kadence-update-checker.php';
	$Kadence_Reading_Time_Update_Checker = Kadence_Update_Checker::buildUpdateChecker(
		'https://kernl.us/api/v1/updates/5aa3304776c93f682ddb6db2/',
		__FILE__,
		'kadence-reading-time'
	);

}
add_action( 'after_setup_theme', 'kt_reading_time_updating', 1 );
