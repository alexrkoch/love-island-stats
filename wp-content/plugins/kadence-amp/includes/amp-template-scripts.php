<?php
// Callbacks for adding content to an AMP template.
function kt_amp_template_scripts_init_hooks() {
	add_action( 'amp_post_template_css', 'kt_amp_post_template_add_styles', 99 );
	add_action( 'amp_post_template_head', 'kt_amp_post_template_add_scripts' );
	add_action( 'amp_post_template_head', 'kt_amp_post_template_add_boilerplate_css' );
	add_action( 'amp_post_template_footer', 'kt_amp_post_template_add_analytics_data' );
	if ( ! defined( 'WPSEO_VERSION' ) && ! defined( 'AIOSEO_FILE' ) && ! defined( 'RANK_MATH_VERSION' ) && ! defined( 'SEOPRESS_VERSION' ) ) {
		add_action( 'amp_post_template_head', 'kt_amp_post_template_add_canonical' );
	}
	if ( defined( 'SEOPRESS_VERSION' ) ) {
		add_action( 'amp_post_template_head', 'kt_amp_post_template_add_title' );
		add_action( 'amp_post_template_head', 'kt_amp_post_template_add_canonical' );
		add_action( 'amp_post_template_head', 'kt_amp_post_template_add_schemaorg_metadata' );
		if ( seopress_get_toggle_titles_option() == '1' ) {
			add_action( 'amp_post_template_head', 'seopress_titles_the_description', 1 );
		}
		if ( seopress_get_toggle_social_option() == '1' ) {
			add_action( 'amp_post_template_head', 'seopress_load_social_options', 0 );
			add_action( 'amp_post_template_head', 'seopress_social_facebook_og_url_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_facebook_og_site_name_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_facebook_og_locale_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_facebook_og_type_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_facebook_og_author_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_fb_title_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_fb_desc_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_fb_img_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_facebook_link_ownership_id_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_facebook_admin_id_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_facebook_app_id_hook', 1 );
			// Titter.
			add_action( 'amp_post_template_head', 'seopress_social_twitter_card_summary_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_twitter_card_site_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_twitter_card_creator_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_twitter_title_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_twitter_desc_hook', 1 );
			add_action( 'amp_post_template_head', 'seopress_social_twitter_img_hook', 1 );
		}
	}
}
// Load Necessary for SEO PRESS.
if ( defined( 'SEOPRESS_VERSION' ) ) {
	if ( seopress_get_toggle_titles_option() == '1' ) {
		seopress_load_titles_options();
	}
}
function kt_amp_post_template_add_title( $amp_template ) {
	?>
	<title><?php echo esc_html( $amp_template->get( 'document_title' ) ); ?></title>
	<?php
}

function kt_amp_post_template_add_canonical( $amp_template ) {
	?>
	<link rel="canonical" href="<?php echo esc_url( $amp_template->get( 'canonical_url' ) ); ?>" />
	<?php
}

function kt_amp_post_template_add_scripts( $amp_template ) {
	echo kadence_amp_render_scripts( array_merge(
		array(
			// Just in case the runtime has been overridden by amp_post_template_data filter.
			'amp-runtime' => $amp_template->get( 'amp_runtime_script' ),
		),
		$amp_template->get( 'amp_component_scripts', array() )
	) ); // WPCS: xss ok.

}

function kt_amp_post_template_add_fonts( $amp_template ) {
	$font_urls = $amp_template->get( 'font_urls', array() );
	foreach ( $font_urls as $slug => $url ) : ?>
		<link rel="stylesheet" href="<?php echo esc_url( $url ); ?>">
	<?php endforeach;
}

function kt_amp_post_template_add_boilerplate_css( $amp_template ) {
	?>
	<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
	<?php
}

function kt_amp_post_template_add_schemaorg_metadata( $amp_template ) {
	$metadata = $amp_template->get( 'metadata' );
	if ( empty( $metadata ) ) {
		return;
	}
	?>
	<script type="application/ld+json"><?php echo wp_json_encode( $metadata ); ?></script>
	<?php
}

function kt_amp_post_template_add_styles( $amp_template ) {
	$stylesheets = $amp_template->get( 'post_amp_stylesheets' );
	if ( ! empty( $stylesheets ) ) {
		echo '/* Inline stylesheets */' . PHP_EOL; // WPCS: XSS OK.
		echo implode( '', $stylesheets ); // WPCS: XSS OK.
	}

	$styles = $amp_template->get( 'post_amp_styles' );
	$kadence_amp = kadence_amp_options();
	if ( ! empty( $styles ) ) {
		echo '/* Inline styles */' . PHP_EOL;
		foreach ( $styles as $selector => $declarations ) {
			$declarations = implode( ';', $declarations ) . ';';
			printf( '%1$s{%2$s}', $selector, $declarations );
		}
	}
	if( ! empty( $kadence_amp[ 'custom_css' ] ) ) {
		echo '/* Custom styles */' . PHP_EOL;
		echo $kadence_amp[ 'custom_css' ];
	}
}

function kt_amp_post_template_add_analytics_data( $amp_template ) {
	$analytics_entries = $amp_template->get( 'amp_analytics' );
	if ( empty( $analytics_entries ) ) {
		return;
	}

	foreach ( $analytics_entries as $id => $analytics_entry ) {
		if ( ! isset( $analytics_entry['type'], $analytics_entry['attributes'], $analytics_entry['config_data'] ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( 'Analytics entry for %s is missing one of the following keys: `type`, `attributes`, or `config_data` (array keys: %s)', 'amp' ), esc_html( $id ), esc_html( implode( ', ', array_keys( $analytics_entry ) ) ) ), '0.3.2' );
			continue;
		}
		$script_element = AMP_HTML_Utils::build_tag( 'script', array(
			'type' => 'application/json',
		), wp_json_encode( $analytics_entry['config_data'] ) );

		$amp_analytics_attr = array_merge( array(
			'id' => $id,
			'type' => $analytics_entry['type'],
		), $analytics_entry['attributes'] );

		echo AMP_HTML_Utils::build_tag( 'amp-analytics', $amp_analytics_attr, $script_element );
	}
}
