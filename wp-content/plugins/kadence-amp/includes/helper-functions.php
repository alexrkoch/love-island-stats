<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function kadence_amp_options() {
	return get_option( 'kadence_amp' );
}
function kadence_amp_theme_info() {
	$the_theme = wp_get_theme();
	if ( $the_theme->get( 'Name' ) == 'Pinnacle Premium' || $the_theme->get( 'Template') == 'pinnacle_premium' ) {
		return 'is_kadence_pinnacle_premium';
	} else if ( $the_theme->get( 'Name' ) == 'Ascend - Premium' || $the_theme->get( 'Template') == 'ascend_premium' ) {
		return 'is_kadence_ascend_premium';
	} else if( $the_theme->get( 'Name' ) == 'Virtue - Premium' || $the_theme->get( 'Template') == 'virtue_premium' ) {
		return 'is_kadence_virtue_premium';
	} else {
		return 'is_not_kadence';
	}
}
function kt_amp_url( $id = '' ) {
	if ( empty( $id ) ) {
		$id = url_to_postid( $link );
	}
	$permalink     = get_permalink( $id );
	$parsed_url    = wp_parse_url( get_permalink( $id ) );
	$structure     = get_option( 'permalink_structure' );
	$use_query_var = (
		// If pretty permalinks aren't available, then query var must be used.
		empty( $structure )
		||
		// If there are existing query vars, then always use the amp query var as well.
		! empty( $parsed_url['query'] )
		||
		// If the post type is hierarchical then the /amp/ endpoint isn't available.
		is_post_type_hierarchical( get_post_type( $id ) )
		||
		// Attachment pages don't accept the /amp/ endpoint.
		'attachment' === get_post_type( $id )
	);
	if ( $use_query_var ) {
		$amp_url = add_query_arg( KADENCE_AMP_QUERY, '', $permalink );
	} else {
		$amp_url = preg_replace( '/#.*/', '', $permalink );
		$amp_url = trailingslashit( $amp_url ) . user_trailingslashit( KADENCE_AMP_QUERY, 'single_amp' );
		if ( ! empty( $parsed_url['fragment'] ) ) {
			$amp_url .= '#' . $parsed_url['fragment'];
		}
	}
	/**
	 * Filters AMP permalink.
	 *
	 * @since 0.2
	 * @since 1.0 This filter does not apply when 'amp' theme support is present.
	 *
	 * @param false $amp_url AMP URL.
	 * @param int   $post_id Post ID.
	 */
	return apply_filters( 'amp_get_permalink', $amp_url, $id );
}
/**
 * Get the URL for the current request.
 *
 * This is essentially the REQUEST_URI prefixed by the scheme and host for the home URL.
 * This is needed in particular due to subdirectory installs.
 *
 * @since 1.0
 *
 * @return string Current URL.
 */
function kt_amp_get_current_url() {
	$url = preg_replace( '#(^https?://[^/]+)/.*#', '$1', home_url( '/' ) );
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$url = esc_url_raw( $url . wp_unslash( $_SERVER['REQUEST_URI'] ) );
	} else {
		$url .= '/';
	}
	return $url;
}

function kt_amp_get_asset_url( $file ) {
	return KADENCE_AMP_URL .'assets/'. $file;
}

if ( ! function_exists( 'sanitize_hex_color' ) ) {
	function sanitize_hex_color( $color ) {
		if ( '' === $color ) {
			return '';
		}

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			return $color;
		}
	}
}

add_action( 'init', 'kadence_amp_load_embeds' );
function kadence_amp_load_embeds() {
	add_filter( 'amp_content_embed_handlers', 'kadence_blocks_amp_embed_handlers');
	function kadence_blocks_amp_embed_handlers( $embeds ) {
		$embeds = array_merge( $embeds, array( 'Kadence_Blocks_Row_AMP_Block_Handler' => array(), 'Kadence_Blocks_Btn_AMP_Block_Handler' => array(), 'Kadence_Blocks_Info_AMP_Block_Handler' => array(), 'Kadence_Blocks_Spacer_AMP_Block_Handler' => array(), 'Kadence_Blocks_Tabs_AMP_Block_Handler' => array() ) );
		return $embeds;
	}
}
/**
 * Get content embed handlers.
 *
 * @param WP_Post $post Post that the content belongs to. Deprecated when theme supports AMP, as embeds may apply
 *                      to non-post data (e.g. Text widget).
 * @return array Embed handlers.
 */
function kadence_amp_get_content_embed_handlers( $post = null ) {

	/**
	 * Filters the content embed handlers.
	 *
	 * @since 0.2
	 * @since 0.7 Deprecated $post parameter.
	 *
	 * @param array   $handlers Handlers.
	 * @param WP_Post $post     Post. Deprecated. It will be null when `amp_is_canonical()`.
	 */
	return apply_filters( 'amp_content_embed_handlers',
		array(
			'AMP_Core_Block_Handler'        => array(),
			'AMP_Twitter_Embed_Handler'     => array(),
			'AMP_YouTube_Embed_Handler'     => array(),
			'AMP_DailyMotion_Embed_Handler' => array(),
			'AMP_Vimeo_Embed_Handler'       => array(),
			'AMP_SoundCloud_Embed_Handler'  => array(),
			'AMP_Instagram_Embed_Handler'   => array(),
			'AMP_Issuu_Embed_Handler'       => array(),
			'AMP_Meetup_Embed_Handler'      => array(),
			'AMP_Vine_Embed_Handler'        => array(),
			'AMP_Facebook_Embed_Handler'    => array(),
			'AMP_Pinterest_Embed_Handler'   => array(),
			'AMP_Playlist_Embed_Handler'    => array(),
			'AMP_Reddit_Embed_Handler'      => array(),
			'AMP_Tumblr_Embed_Handler'      => array(),
			'AMP_Gallery_Embed_Handler'     => array(),
			'AMP_Gfycat_Embed_Handler'      => array(),
			'AMP_Hulu_Embed_Handler'        => array(),
			'AMP_Imgur_Embed_Handler'       => array(),
			'WPCOM_AMP_Polldaddy_Embed'     => array(),
		),
		$post
	);
}
/**
 * Get content sanitizers.
 *
 * @param WP_Post $post Post that the content belongs to. Deprecated when theme supports AMP, as sanitizers apply
 *                      to non-post data (e.g. Text widget).
 * @return array Embed handlers.
 */
function kadence_amp_get_content_sanitizers( $post = null ) {

	/**
	 * Filters the content sanitizers.
	 *
	 * @since 0.2
	 * @since 0.7 Deprecated $post parameter. It will be null when `amp_is_canonical()`.
	 *
	 * @param array   $handlers Handlers.
	 * @param WP_Post $post     Post. Deprecated.
	 */
	$sanitizers = apply_filters( 'amp_content_sanitizers',
		array(
			'AMP_Img_Sanitizer'               => array(),
			'AMP_Form_Sanitizer'              => array(),
			'AMP_Comments_Sanitizer'          => array(),
			'AMP_Video_Sanitizer'             => array(),
			'AMP_O2_Player_Sanitizer'         => array(),
			'AMP_Audio_Sanitizer'             => array(),
			'AMP_Playbuzz_Sanitizer'          => array(),
			'AMP_Embed_Sanitizer'             => array(),
			'AMP_Iframe_Sanitizer'            => array(
				'add_placeholder' => true,
			),
			'AMP_Gallery_Block_Sanitizer'     => array( // Note: Gallery block sanitizer must come after image sanitizers since itś logic is using the already sanitized images.
				'carousel_required' => true, // For back-compat.
			),
			'AMP_Block_Sanitizer'             => array(), // Note: Block sanitizer must come after embed / media sanitizers since it's logic is using the already sanitized content.
			'AMP_Script_Sanitizer'            => array(),
			'AMP_Style_Sanitizer'             => array(),
			'AMP_Tag_And_Attribute_Sanitizer' => array(), // Note: This whitelist sanitizer must come at the end to clean up any remaining issues the other sanitizers didn't catch.
		),
		$post
	);

	// Force style sanitizer and whitelist sanitizer to be at end.
	foreach ( array( 'AMP_Style_Sanitizer', 'AMP_Tag_And_Attribute_Sanitizer' ) as $class_name ) {
		if ( isset( $sanitizers[ $class_name ] ) ) {
			$sanitizer = $sanitizers[ $class_name ];
			unset( $sanitizers[ $class_name ] );
			$sanitizers[ $class_name ] = $sanitizer;
		}
	}

	return $sanitizers;
}

/**
 * Generate HTML for AMP scripts that have not yet been printed.
 *
 * This is adapted from `wp_scripts()->do_items()`, but it runs only the bare minimum required to output
 * the missing scripts, without allowing other filters to apply which may cause an invalid AMP response.
 * The HTML for the scripts is returned instead of being printed.
 *
 * @since 0.7.2
 * @see WP_Scripts::do_items()
 * @see AMP_Base_Embed_Handler::get_scripts()
 * @see AMP_Base_Sanitizer::get_scripts()
 *
 * @param array $scripts Script handles mapped to URLs or true.
 * @return string HTML for scripts tags that have not yet been done.
 */
function kadence_amp_render_scripts( $scripts ) {
	$script_tags = '';

	/*
	 * Make sure the src is up to date. This allows for embed handlers to override the
	 * default extension version by defining a different URL.
	 */
	foreach ( $scripts as $handle => $src ) {
		if ( is_string( $src ) && wp_script_is( $handle, 'registered' ) ) {
			wp_scripts()->registered[ $handle ]->src = $src;
		}
	}

	foreach ( array_diff( array_keys( $scripts ), wp_scripts()->done ) as $handle ) {
		if ( ! wp_script_is( $handle, 'registered' ) ) {
			continue;
		}

		$script_dep   = wp_scripts()->registered[ $handle ];
		$script_tags .= amp_filter_script_loader_tag(
			sprintf(
				"<script type='text/javascript' src='%s'></script>\n", // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
				esc_url( $script_dep->src )
			),
			$handle
		);

		wp_scripts()->done[] = $handle;
	}
	return $script_tags;
}
