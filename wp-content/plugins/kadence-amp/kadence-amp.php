<?php
/**
 * Plugin Name: Kadence AMP
 * Description: Adds Accelerated Mobile Pages Support for your WordPress blog, plus AMP support for products.
 * Version: 1.0.21
 * Author: Kadence WP
 * Author URI: http://www.kadencewp.com/
 * License: GPLv2 or later
 *
 * @package Kadence AMP
 */

/**
 * Print admin notice regarding having an old version of PHP.
 */
function kt_amp_print_php_version_admin_notice() {
	?>
	<div class="notice notice-error">
			<p><?php esc_html_e( 'The AMP plugin requires PHP 5.3+. Please contact your host to update your PHP version.', 'amp' ); ?></p>
		</div>
	<?php
}
if ( version_compare( phpversion(), '5.3', '<' ) ) {
	add_action( 'admin_notices', 'kt_amp_print_php_version_admin_notice' );
	return;
}

/**
 * Define constants
 */
define( 'KADENCE_AMP_PATH', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
define( 'KADENCE_AMP_URL', plugin_dir_url( __FILE__ ) );
define( 'KADENCE_AMP_VERSION', '1.0.21' );
define( 'KADENCE_AMP_QUERY', apply_filters( 'kadence_amp_query_var', 'amp' ) );
if ( ! defined( 'AMP__FILE__' ) ) {
	define( 'AMP__FILE__', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'vendor/amp/amp.php' );
}
if ( ! defined( 'AMP__DIR__' ) ) {
	define( 'AMP__DIR__', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'vendor/amp' );
}
/**
 * Activate init.
 */
function kadence_amp_activation() {
	// Make sure rules added before flush.
	if ( ! did_action( 'kt_amp_init' ) ) {
		$ktamp_instance = Kadence_AMP::get_instance();
		$ktamp_instance->kadence_amp_init();
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'kadence_amp_activation' );

/**
 * Deactivate clean up.
 */
function kadence_amp_deactivation() {
	// We need to manually remove the amp endpoint.
	global $wp_rewrite;
	foreach ( $wp_rewrite->endpoints as $index => $endpoint ) {
		if ( KADENCE_AMP_QUERY === $endpoint[1] ) {
			unset( $wp_rewrite->endpoints[ $index ] );
			break;
		}
	}

	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'kadence_amp_deactivation' );

/**
 * Load up the admin options.
 */
require_once KADENCE_AMP_PATH . 'admin/amp_options.php';

/**
 * Load Up the AMP framework.
 *
 * @category class
 */
class Kadence_AMP {
	/**
	 * Instance Control
	 *
	 * @var null
	 */
	protected static $instance = null;
	/**
	 * Instance Control
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Constructor
	 */
	public function __construct() {
		// Translation.
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// CMB.
		require_once KADENCE_AMP_PATH . 'vendor/cmb/init.php';

		// Image Processing.
		require_once KADENCE_AMP_PATH . 'includes/class-kadence-image-processing.php';
		require_once KADENCE_AMP_PATH . 'includes/class-kadence-amp-get-image.php';
		// Helper.
		require_once KADENCE_AMP_PATH . 'includes/helper-functions.php';
		require_once KADENCE_AMP_PATH . 'includes/image-functions.php';
		require_once KADENCE_AMP_PATH . 'includes/template-functions.php';
		require_once KADENCE_AMP_PATH . 'includes/product-template-functions.php';
		require_once KADENCE_AMP_PATH . 'includes/breadcrumb-template-functions.php';

		if ( ! defined( 'AMP__VERSION' ) ) {
			require_once KADENCE_AMP_PATH . 'includes/custom-amp-override-functions.php';
			require_once KADENCE_AMP_PATH . 'vendor/amp/includes/class-amp-http.php';
			require_once KADENCE_AMP_PATH . 'vendor/amp/includes/class-amp-autoloader.php';
			AMP_Autoloader::register();
			require_once KADENCE_AMP_PATH . 'vendor/amp/back-compat/back-compat.php';
			require_once KADENCE_AMP_PATH . 'vendor/amp/includes/amp-helper-functions.php';
			define( 'AMP__VERSION', '0.7.2' );
			/**
			 * Register AMP scripts regardless of whether AMP is enabled or it is the AMP endpoint
			 * for the sake of being able to use AMP components on non-AMP documents ("dirty AMP").
			 */
			add_action( 'wp_default_scripts', 'amp_register_default_scripts' );

			// Ensure async and custom-element/custom-template attributes are present on script tags.
			add_filter( 'script_loader_tag', 'amp_filter_script_loader_tag', PHP_INT_MAX, 2 );
			// Ensure crossorigin=anonymous is added to font links.
			add_filter( 'style_loader_tag', 'amp_filter_font_style_loader_tag_with_crossorigin_anonymous', 10, 4 );
		}

		// Init AMP.
		add_action( 'after_setup_theme', array( $this, 'kadence_amp_theme_init' ), 5 );

	}

	/**
	 * Constructor
	 */
	public function kadence_amp_theme_init() {
		// Vendors.
		// AMP
		// Helper.
		require_once KADENCE_AMP_PATH . 'includes/embeds/class-kadence-blocks-row-amp-block-handler.php';
		require_once KADENCE_AMP_PATH . 'includes/embeds/class-kadence-blocks-info-amp-block-handler.php';
		require_once KADENCE_AMP_PATH . 'includes/embeds/class-kadence-blocks-button-amp-block-handler.php';
		require_once KADENCE_AMP_PATH . 'includes/embeds/class-kadence-blocks-spacer-amp-block-handler.php';
		require_once KADENCE_AMP_PATH . 'includes/embeds/class-kadence-blocks-tabs-amp-block-handler.php';
		// Init AMP.
		add_action( 'init', array( $this, 'kadence_amp_init' ), 0 );
	}
	/**
	 * Init
	 */
	public function kadence_amp_init() {
		do_action( 'kt_amp_init' );

		add_filter( 'allowed_redirect_hosts', array( 'AMP_HTTP', 'filter_allowed_redirect_hosts' ) );

		AMP_HTTP::purge_amp_query_vars();
		AMP_HTTP::send_cors_headers();
		AMP_HTTP::handle_xhr_request();

		add_rewrite_endpoint( KADENCE_AMP_QUERY, EP_PERMALINK );

		register_nav_menus( array( 'amp_menu' => __( 'AMP Navigation', 'kadence-amp' ) ) );

		add_filter( 'request', array( $this, 'amp_force_query_var_value' ) );

		add_action( 'init', array( $this, 'add_supported_types' ), 1000 );

		add_action( 'parse_query', array( $this, 'amp_correct_query_when_is_front_page' ) );

		add_filter( 'cmb2_admin_init', array( $this, 'amp_metaboxes' ) );

		add_action( 'admin_bar_menu', array( $this, 'amp_add_admin_bar_view_link' ), 100 );

		add_filter( 'amp_frontend_show_canonical', array( $this, 'hide_amp' ) );

		add_filter( 'kadence_amp_skip_post', array( $this, 'disable_amp' ), 20, 2 );

		if ( class_exists( 'Jetpack' ) && ! ( defined( 'IS_WPCOM' ) && IS_WPCOM ) && version_compare( JETPACK__VERSION, '6.2-alpha', '<' ) ) {
			require_once KADENCE_AMP_PATH . 'vendor/amp/jetpack-helper.php';
		}
		add_filter( 'request', array( $this, 'amp_force_query_var_value' ) );

		add_action( 'wp', array( $this, 'amp_maybe_add_actions' ) );

		add_filter( 'old_slug_redirect_url', array( $this, 'amp_redirect_old_slug_to_new_url' ) );
	}

	/**
	 * Add "View AMP" admin bar item for Transitional/Reader mode.
	 *
	 * Note that when theme support is present (in Native/Transitional modes), the admin bar item will be further amended by
	 * the `AMP_Validation_Manager::add_admin_bar_menu_items()` method.
	 *
	 * @see \AMP_Validation_Manager::add_admin_bar_menu_items()
	 *
	 * @param WP_Admin_Bar $wp_admin_bar Admin bar.
	 */
	public function amp_add_admin_bar_view_link( $wp_admin_bar ) {
		if ( is_admin() ) {
			return;
		}

		if ( is_singular() ) {
			$post      = get_queried_object();
			$supports  = $this->post_supports_amp( $post );
			$available = ( $post instanceof WP_Post ) && $supports;
		} else {
			$available = false;
		}
		if ( ! $available ) {
			// @todo Add note that AMP is not available?
			return;
		}

		if ( is_singular() ) {
			$href = kt_amp_url( get_queried_object_id() );
		} else {
			$href = add_query_arg( KADENCE_AMP_QUERY, '', kt_amp_get_current_url() );
		}

		$icon = '&#x1F517;'; // LINK SYMBOL.

		$parent = array(
			'id'    => 'amp',
			'title' => sprintf(
				'<span id="amp-admin-bar-item-status-icon">%s</span> %s',
				$icon,
				esc_html( __( 'AMP', 'kadence-amp' ) )
			),
			'href'  => esc_url( $href ),
		);

		$wp_admin_bar->add_menu( $parent );
	}

	/**
	 * Make sure the `amp` query var has an explicit value.
	 *
	 * This avoids issues when filtering the deprecated `query_string` hook.
	 *
	 * @since 0.3.3
	 *
	 * @param array $query_vars Query vars.
	 * @return array Query vars.
	 */
	public function amp_force_query_var_value( $query_vars ) {
		if ( isset( $query_vars[ KADENCE_AMP_QUERY ] ) && '' === $query_vars[ KADENCE_AMP_QUERY ] ) {
			$query_vars[ KADENCE_AMP_QUERY ] = 1;
		}
		return $query_vars;
	}

	/**
	 * Redirects the old AMP URL to the new AMP URL.
	 *
	 * If post slug is updated the amp page with old post slug will be redirected to the updated url.
	 *
	 * @since 0.5
	 * @deprecated This function is irrelevant when 'amp' theme support is added.
	 *
	 * @param string $link New URL of the post.
	 * @return string URL to be redirected.
	 */
	public function amp_redirect_old_slug_to_new_url( $link ) {

		if ( is_amp_endpoint() && ! amp_is_canonical() ) {
			$link = trailingslashit( trailingslashit( $link ) . KADENCE_AMP_QUERY );
		}

		return $link;
	}
	/**
	 * Supported Types
	 */
	public static function amp_get_supported_types() {
		$supported   = array();
		$kadence_amp = get_option( 'kadence_amp' );
		if ( isset( $kadence_amp['post_types'] ) && ! empty( $kadence_amp['post_types'] ) ) {
			foreach ( $kadence_amp['post_types'] as $key => $value ) {
				$supported[] = $value;
			}
		} else {
			$supported = array( 'posts', 'products' );
		}
		return $supported;
	}
	/**
	 * Add Supported Types
	 */
	public static function add_supported_types() {
		$types = Kadence_AMP::amp_get_supported_types();
		foreach ( $types as $type ) {
			add_post_type_support( $type, KADENCE_AMP_QUERY );
		}
	}
	/**
	 * Fix up WP_Query for front page when amp query var is present.
	 *
	 * Normally the front page would not get served if a query var is present other than preview, page, paged, and cpage.
	 *
	 * @since 0.6
	 * @see WP_Query::parse_query()
	 * @link https://github.com/WordPress/wordpress-develop/blob/0baa8ae85c670d338e78e408f8d6e301c6410c86/src/wp-includes/class-wp-query.php#L951-L971
	 *
	 * @param WP_Query $query Query.
	 */
	public function amp_correct_query_when_is_front_page( WP_Query $query ) {
		$is_front_page_query = (
			$query->is_main_query()
			&&
			$query->is_home()
			&&
			// Is AMP endpoint.
			false !== $query->get( KADENCE_AMP_QUERY, false )
			&&
			// Is query not yet fixed uo up to be front page.
			! $query->is_front_page()
			&&
			// Is showing pages on front.
			'page' === get_option( 'show_on_front' )
			&&
			// Has page on front set.
			get_option( 'page_on_front' )
			&&
			// See line in WP_Query::parse_query() at <https://github.com/WordPress/wordpress-develop/blob/0baa8ae/src/wp-includes/class-wp-query.php#L961>.
			0 === count( array_diff( array_keys( wp_parse_args( $query->query ) ), array( KADENCE_AMP_QUERY, 'preview', 'page', 'paged', 'cpage' ) ) )
		);
		if ( $is_front_page_query ) {
			$query->is_home     = false;
			$query->is_page     = true;
			$query->is_singular = true;
			$query->set( 'page_id', get_option( 'page_on_front' ) );
		}
	}
	/**
	 * Load AMP content?
	 */
	public function amp_maybe_add_actions() {
		global $wp_query;
		if ( ! ( is_singular() || $wp_query->is_posts_page ) || is_feed() ) {
			return;
		}
		$is_amp_endpoint = $this->is_amp_endpoint();
		/**
		 * Queried post object.
		 *
		 * @var WP_Post $post
		 */
		$post = get_queried_object();

		$supports = $this->post_supports_amp( $post );

		if ( ! $supports ) {
			if ( $is_amp_endpoint ) {
				/*
				 * Temporary redirect is used for admin users because reader mode and AMP support can be enabled by user at any time,
				 * so they will be able to make AMP available for this URL and see the change without wrestling with the redirect cache.
				 */
				wp_safe_redirect( get_permalink( $post->ID ), current_user_can( 'manage_options' ) ? 302 : 301 );
				exit;
			}
			return;
		}
		if ( $is_amp_endpoint ) {
			global $wp;
			wp_parse_str( $wp->matched_query, $path_args );
			if ( isset( $path_args[ KADENCE_AMP_QUERY ] ) && '' !== $path_args[ KADENCE_AMP_QUERY ] ) {
				wp_safe_redirect( amp_get_permalink( $post->ID ), 301 );
				exit;
			}

			$this->amp_prepare_render();
		} else {
			$this->amp_add_frontend_actions();
		}
	}
	/**
	 * Is amp endpoint?
	 */
	public function is_amp_endpoint() {
		global $pagenow, $wp_query;

		// Short-circuit for cron, CLI, admin requests or requests to non-frontend pages.
		if ( wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) || is_admin() || in_array( $pagenow, array( 'wp-login.php', 'wp-signup.php', 'wp-activate.php', 'repair.php' ), true ) ) {
			return false;
		}

		if ( ! did_action( 'parse_query' ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( "is_amp_endpoint() was called before the 'parse_query' hook was called. This function will always return 'false' before the 'parse_query' hook is called.", 'kadence-amp' ) ), '0.4.2' );
		}
		$has_amp_query_var = (
			isset( $_GET[ KADENCE_AMP_QUERY ] ) // WPCS: CSRF OK.
			||
			false !== get_query_var( KADENCE_AMP_QUERY, false )
		);
		return $has_amp_query_var;
	}
	/**
	 * Make sure post supports amp.
	 *
	 * @param WP_Post|int $post Post.
	 */
	public function post_supports_amp( $post ) {
		// Because `add_rewrite_endpoint` doesn't let us target specific post_types :(.
		if ( isset( $post->post_type ) && ! post_type_supports( $post->post_type, KADENCE_AMP_QUERY ) ) {
			return false;
		}

		if ( post_password_required( $post ) ) {
			return false;
		}

		if ( true === apply_filters( 'kadence_amp_skip_post', false, $post->ID, $post ) ) {
			return false;
		}

		return true;
	}
	/**
	 * Hook into template redirect.
	 */
	public function amp_prepare_render() {
		add_action( 'template_redirect', array( $this, 'amp_render' ), 11 );
	}
	/**
	 * Make sure it's the right format.
	 */
	public function amp_render() {
		// Note that queried object is used instead of the ID so that the_preview for the queried post can apply.
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$this->amp_render_post( $post );
			exit;
		}
	}
	/**
	 * Render AMP post template.
	 *
	 * @param WP_Post|int $post Post.
	 * @global WP_Query $wp_query
	 */
	public function amp_render_post( $post ) {
		if ( ! ( $post instanceof WP_Post ) ) {
			$post = get_post( $post );
			if ( ! $post ) {
				return;
			}
		}
		$post_id = $post->ID;

		$this->amp_load_classes();

		// Prevent New Relic from causing invalid AMP responses due the NREUM script it injects after the meta charset.
		if ( extension_loaded( 'newrelic' ) ) {
			newrelic_disable_autorum();
		}
		do_action( 'pre_amp_render_post', $post_id );

		$this->amp_add_post_template_actions();
		$template = new Kadence_AMP_Template( $post );
		$template->load();
	}
	public function amp_add_frontend_actions() {
		require_once( KADENCE_AMP_PATH . 'includes/amp-frontend-actions.php' );
	}
	public function amp_load_classes() {
		require_once( KADENCE_AMP_PATH . 'includes/class-kadence-amp-template.php' ); // this loads everything else.
	}
	public function amp_add_post_template_actions() {
		require_once( KADENCE_AMP_PATH . 'includes/amp-template-scripts.php' );
		kt_amp_template_scripts_init_hooks();
		kt_amp_template_init_hooks();
	}
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'kadence-amp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
	}
	public function amp_metaboxes() {

		$prefix = '_kt_amp_';
		$amp_content = new_cmb2_box( array(
			'id'            => $prefix . 'amp_settings',
			'title'         => __( 'AMP Settings', 'kadence-amp' ),
			'object_types'  => $this->amp_get_supported_types(),
			'priority'      => 'low',
		) );
		$amp_content->add_field( array(
			'name'          => __( 'Disable/Enable AMP version of this post', 'kadence-amp' ),
			'id'            => $prefix . 'override',
			'type'          => 'select',
			'default'       => 'enable',
			'options'      	=> array(
				'enable'     	=> __( 'Enable', 'kadence-amp' ),
				'disable'    	=> __( 'Disable', 'kadence-amp' ),
			),
		) );
		$amp_content->add_field( array(
			'name'    => __( 'Override AMP Content', 'kadence-amp' ),
			'desc'    => 'Leave empty to use standard content',
			'id'      => $prefix . 'content',
			'type'    => 'wysiwyg',
			'options' => array(),
		) );
	}
	public function hide_amp( $input ) {
		global $post;
		$amp_enable = get_post_meta( $post->ID, '_kt_amp_override', true );
		if ( 'disable' == $amp_enable ) {
			$input = false;
		}
		return $input;
	}
	public function disable_amp( $input, $id ) {
		$amp_enable = get_post_meta( $id , '_kt_amp_override', true );
		if ( 'disable' == $amp_enable ) {
			$input = true;
		}
		return $input;
	}

}
add_action( 'plugins_loaded', array( 'Kadence_AMP', 'get_instance' ) );


/**
 * Plugin Updates
 */
function kadence_amp_updating() {
	require_once KADENCE_AMP_PATH . 'kadence-update-checker/kadence-update-checker.php';
	require_once KADENCE_AMP_PATH . 'admin/kadence-activation/kadence-plugin-api-manager.php';
	if ( get_option( 'kt_api_manager_kadence_amp_activated' ) === 'Activated' ) {
		$kadence_amp_update_checker = Kadence_Update_Checker::buildUpdateChecker(
			'https://kernl.us/api/v1/updates/5a1f5ec8e30087095fbb1c1e/',
			__FILE__,
			'kadence-amp'
		);
	}
}
add_action( 'after_setup_theme', 'kadence_amp_updating', 1 );
