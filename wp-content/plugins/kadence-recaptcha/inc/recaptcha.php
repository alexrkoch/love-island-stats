<?php
/**
 * Main Kadence Recaptcha Class
 *
 * @package Kadence reCAPTCHA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Main Kadence reCAPTCHA Class
 */
class Kadence_Recaptcha_Fontend {
	/**
	 * The instance.
	 *
	 * @var null
	 */
	private static $instance = null;
	/**
	 * Defaults.
	 *
	 * @var null
	 */
	private static $default_values = null;
	/**
	 * The captcha count.
	 *
	 * @var number
	 */
	private static $captcha_count = 0;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Loading styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_styles' ) );

		add_action( 'wp_ajax_kadence_verify_recaptcha', array( $this, 'verify_recaptcha' ) );
		add_action( 'wp_ajax_nopriv_kadence_verify_recaptcha', array( $this, 'verify_recaptcha' ) );
		add_action( 'comment_form_after_fields', array( $this, 'additional_fields' ), 500 );
		// Post and woocommerce.
		add_action( 'pre_comment_on_post', array( $this, 'after_verify_recaptcha' ), 10, 1 );
		// Login.
		add_action( 'login_enqueue_scripts', array( $this, 'register_login_scripts_styles' ) );
		add_action( 'login_form', array( $this, 'login_form_field' ) );
		add_filter( 'login_form_middle', array( $this, 'login_form_field_filter' ), 10, 2 );

		// CLASSIC THEMES
		// Testimonial add.
		add_filter( 'kadence-testimonial-spam-field', array( $this, 'testimonial_spam_field' ), 20 );
		// Testimonial check.
		add_filter( 'kadence-testimonial-spam-check', array( $this, 'testimonial_spam_check' ), 20, 2 );
		// Contact add.
		add_filter( 'kadence-contact-spam-field', array( $this, 'contact_spam_field' ), 20 );
		// Contact check.
		add_filter( 'kadence-contact-spam-check', array( $this, 'contact_spam_check' ), 20, 2 );

		// RCP Login.
		add_action( 'rcp_login_form_fields_before_submit', array( $this, 'rcp_login_form_field' ) );
		// Account.
		add_action( 'woocommerce_login_form', array( $this, 'woocommerce_login_form_field' ) );
		add_filter( 'wp_authenticate_user', array( $this, 'auth_login' ), 10, 2 );

		// Lost Password.
		add_action( 'woocommerce_lostpassword_form', array( $this, 'woocommerce_lost_password_form_field' ) );
		add_action( 'lostpassword_form', array( $this, 'lost_password_form_field' ) );
		add_action( 'lostpassword_post', array( $this, 'verify_lost_password' ) );

		// Registration.
		add_action( 'register_form', array( $this, 'registration_form_field' ), 99 );
		add_action( 'woocommerce_register_form', array( $this, 'registration_form_field' ), 99 );
		add_filter( 'registration_errors', array( $this, 'verify_registration' ), 10, 3 );
		add_filter( 'woocommerce_registration_errors', array( $this, 'verify_registration' ), 10, 3 );

		// RCP Registration.
		// add_action( 'rcp_before_registration_submit_field', array( $this, 'registration_form_field' ), 99 );

		// Woocommerce Checkout Add.
		add_action( 'woocommerce_review_order_before_submit', array( $this, 'woocommerce_checkout_form_field' ), 5 );
		// Woocommerce Checkout Check.
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'woocommerce_checkout_verify' ), 10, 2 );

	}
	/**
	 * Check Recaptcha
	 *
	 * @return bool
	 */
	public function run_verify_recaptcha() {
		if ( 1 == self::settings( 'enable_v3' ) ) {
			if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
				return false;
			}
			$secret = trim( self::settings( 'v3_re_secret_key' ) );
			if ( $this->verify_v3_recaptcha( $_POST['g-recaptcha-response'], $secret ) ) {
				return true;
			} else {
				return false;
			}
		} else {
			require KT_RECAPTCHA_PATH . 'inc/google-recaptcha.php';
			if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
				return false;
			}
			$secret     = trim( self::settings( 'kt_re_secret_key' ) );
			$recaptcha  = new KT_ReCaptcha( $secret );
			$response   = $recaptcha->verify( $_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'] );
			//$last_test   = $response->get_error_codes();
			// // we know that we'll obtain an error but the "correct" error
			// if ( isset( $last_test[0] ) && $last_test[0] !== 'timeout-or-duplicate' ) {
			// 	return false;
			// }
			if ( $response->is_success() ) {
				return true;
			}
			return false;
		}
	}
	/**
	 * Check Recaptcha for lost password
	 *
	 * @param object $error the error object.
	 */
	public function verify_lost_password( $error ) {
		if ( is_user_logged_in() ) {
			return;
		}
		if ( 1 == self::settings( 'enable_for_lost_password' ) ) {
			$verified = $this->run_verify_recaptcha();
			if ( $verified ) {
				return;
			} else {
				$error->add(
					'invalid_captacha',
					__( '<strong>ERROR</strong>: Invalid Captcha.', 'kadence-recaptcha' )
				);
				return;
			}
		}
	}
	/**
	 * Check Recaptcha for login.
	 *
	 * @param object $user the user object.
	 * @param string $password the user password.
	 */
	public function auth_login( $user, $password ) {
		if ( 1 == self::settings( 'enable_for_login' ) ) {
			$verified = $this->run_verify_recaptcha();
			if ( $verified ) {
				return $user;
			} else {
				return new WP_Error(
					'invalid_captacha',
					__( '<strong>ERROR</strong>: Invalid Captcha.', 'kadence-recaptcha' )
				);
			}
		}
		return $user;
	}
	/**
	 * Check Recaptcha for registration
	 *
	 * @param object $errors the error object.
	 * @param string $sanitized_user_login the username.
	 * @param string $user_email the user password.
	 */
	public function verify_registration( $errors, $sanitized_user_login, $user_email ) {
		if ( 1 == self::settings( 'enable_for_registration' ) ) {
			if ( class_exists( 'woocommerce' ) && is_checkout() ) {
				if ( apply_filters( 'kadence_recaptcha_on_checkout_registration', self::settings( 'enable_for_woocommerce_checkout' ) ) ) {
					$verified = $this->run_verify_recaptcha();
				} else {
					// don't prevent checkout.
					$verified = true;
				}
			} else {
				$verified = $this->run_verify_recaptcha();
			}
			if ( $verified ) {
				return $errors;
			} else {
				$errors->add(
					'invalid_captacha',
					__( '<strong>ERROR</strong>: Invalid Captcha.', 'kadence-recaptcha' )
				);
				return $errors;
			}
		}
		return $errors;
	}
	/**
	 * Add form fields.
	 */
	public function registration_form_field() {
		$enabled = false;
		if ( 1 == self::settings( 'enable_for_registration' ) ) {
			$enabled = true;
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				echo $this->recaptcha_field();
			} else {
				echo $this->consent_field();
			}
		}

	}
	/**
	 * Add form fields.
	 */
	public function rcp_login_form_field() {
		$enabled = false;
		if ( 1 == self::settings( 'enable_for_login' ) ) {
			$enabled = true;
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				echo $this->recaptcha_field();
			} else {
				echo $this->consent_field();
			}
		}

	}
	/**
	 * Add form fields.
	 */
	public function woocommerce_login_form_field() {
		$enabled = false;
		if ( 1 == self::settings( 'enable_for_login' ) ) {
			$enabled = true;
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				echo $this->recaptcha_field();
			} else {
				echo $this->consent_field();
			}
		}

	}
	/**
	 * Add form fields.
	 */
	public function woocommerce_lost_password_form_field() {
		$enabled = false;
		if ( 1 == self::settings( 'enable_for_lost_password' ) ) {
			$enabled = true;
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				echo $this->recaptcha_field();
			} else {
				echo $this->consent_field();
			}
		}

	}
	/**
	 * Add form fields.
	 */
	public function lost_password_form_field() {
		$enabled = false;
		if ( 1 == self::settings( 'enable_for_lost_password' ) ) {
			$enabled = true;
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				echo $this->recaptcha_field( '', '', '', 'margin-left: -15px;margin-bottom: 10px; margin-right: -15px;' );
			} else {
				echo $this->consent_field();
			}
		}

	}
	/**
	 * Add form fields.
	 */
	public function woocommerce_checkout_form_field() {
		$enabled = false;
		if ( 1 == self::settings( 'enable_for_woocommerce_checkout' ) ) {
			$enabled = true;
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				if ( 1 == self::settings( 'enable_v3' ) ) {
					wp_enqueue_script( 'kadence-checkout-recaptcha' );
				}
				echo $this->recaptcha_field( '<div class="google-recaptcha-checkout-wrap">', '</div>', 'kt_g_recaptcha_checkout' );
			} else {
				echo $this->consent_field();
			}
		}
	}
	/**
	 * Check Recaptcha for checkout.
	 *
	 * @param object $data the data object.
	 * @param object $errors the errors object.
	 */
	public function woocommerce_checkout_verify( $data, $errors ) {
		if ( 1 == self::settings( 'enable_for_woocommerce_checkout' ) ) {

			$is_reg_enable   = apply_filters( 'woocommerce_checkout_registration_enabled', 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' ) );
			$is_reg_required = apply_filters( 'woocommerce_checkout_registration_required', 'yes' !== get_option( 'woocommerce_enable_guest_checkout' ) );

			if ( ! is_user_logged_in() && 1 == self::settings( 'enable_for_registration' ) && $is_reg_enable && ( $is_reg_required || ! empty( $data['createaccount'] ) ) ) {
				// verification done during ragistration, So no need any more verification
				return;
			} else {
				$verified = $this->run_verify_recaptcha();
				if ( $verified ) {
					return;
				} else {
					$errors->add(
						'invalid_captacha',
						__( '<strong>ERROR</strong>: Invalid Captcha.', 'kadence-recaptcha' )
					);
					return;
				}
			}
		}
	}
	/**
	 * Add form fields.
	 */
	public function login_form_field() {
		$enabled = false;
		if ( 1 == self::settings( 'enable_for_login' ) ) {
			$enabled = true;
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				echo $this->recaptcha_field( '', '', '', 'margin-left: -15px;margin-bottom: 10px; margin-right: -15px;' );
			} else {
				echo $this->consent_field();
			}
		}
	}
	/**
	 * Login Form Filter for when wp_login_form() is called.
	 *
	 * @param $html the html that should be part of the form.
	 * @param $args the array of args for the form.
	 */
	public function login_form_field_filter( $html, $args ) {
		$enabled = false;
		if ( 1 == self::settings( 'enable_for_login' ) ) {
			$enabled = true;
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				$html = $this->recaptcha_field( '', '', '', 'margin-left: -15px; margin-right: -15px;' );
			} else {
				$html = $this->consent_field();
			}
		}
		return $html;
	}
	public function register_login_scripts_styles() {
		if ( 1 == self::settings( 'enable_for_login' ) ) {
			if ( ! $this->permitted() ) {
				wp_enqueue_style( 'kadence-recaptcha-permission-css', KT_RECAPTCHA_URL . 'css/recaptcha.css', array(), KT_RECAPTCHA_VERSION, 'all' );
			}
			if ( 1 == self::settings( 'enable_v3' ) ) {
				$url = 'https://www.google.com/recaptcha/api.js';
				$url = add_query_arg(
					array(
						'render' => self::settings( 'v3_re_site_key' ),
					),
					$url
				);
				wp_register_script( 'ktv3-google-recaptcha', $url, array(), KT_RECAPTCHA_VERSION, true );
				$recaptcha_script = "grecaptcha.ready(function () { var kt_recaptcha_inputs = document.getElementsByClassName('kt-g-recaptcha'); if ( ! kt_recaptcha_inputs.length ) { return; } for (var i = 0; i < kt_recaptcha_inputs.length; i++) { const e = i; grecaptcha.execute('" . self::settings( 'v3_re_site_key' ) . "', { action: 'kt_verify_recaptcha' }).then(function (token) { kt_recaptcha_inputs[e].setAttribute('value', token); }); }; setInterval(function(){ for (var i = 0; i < kt_recaptcha_inputs.length; i++) { const e = i; grecaptcha.execute('" . self::settings( 'v3_re_site_key' ) . "', { action: 'kt_verify_recaptcha' }).then(function (token) { kt_recaptcha_inputs[e].setAttribute('value', token); }); } }, 60000); });";
				wp_add_inline_script( 'ktv3-google-recaptcha', $recaptcha_script, 'after' );
				wp_enqueue_script( 'ktv3-google-recaptcha' );
			} else {
				wp_register_script( 'kadence-recaptcha-permission-js', KT_RECAPTCHA_URL . 'js/permission-recaptcha.js', array( 'jquery' ), KT_RECAPTCHA_VERSION, true );
				$permission_translation_array = array(
					'permission_cookie' => self::settings( 'consent_cookie' ),
				);
				wp_localize_script( 'kadence-recaptcha-permission-js', 'ktpercap', $permission_translation_array );
				$translation_array = array(
					'ajax_url'        => admin_url( 'admin-ajax.php' ),
					'recaptcha_elem'  => null,
					'recaptcha_id'    => 'kt-g-recaptcha',
					'recaptcha_skey'  => self::settings( 'kt_re_site_key' ),
					'recaptcha_theme' => self::settings( 'kt_re_theme' ),
					'recaptcha_size'  => self::settings( 'kt_re_size' ),
					'recaptcha_type'  => self::settings( 'kt_re_type' ),
				);

				wp_register_script( 'kadence-recaptcha-js', KT_RECAPTCHA_URL . 'js/recaptcha.js', array( 'jquery' ), KT_RECAPTCHA_VERSION, true );
				wp_localize_script( 'kadence-recaptcha-js', 'ktrecap', $translation_array );

				// reCAPTCHA Google script.
				$url = 'https://www.google.com/recaptcha/api.js';
				$url = add_query_arg(
					array(
						'onload' => 'ktrecaploadCallback',
						'render' => 'explicit',
					),
					$url
				);
				wp_register_script( 'kt-google-recaptcha', $url, array( 'jquery', 'kadence-recaptcha-js' ), KT_RECAPTCHA_VERSION, true );
			}
		}
	}
	/**
	 * Load Styles.
	 */
	public function register_scripts_styles() {
		// If it's not permitted we need to load the styles.
		if ( ! $this->permitted() ) {
			wp_enqueue_style( 'kadence-recaptcha-permission-css', KT_RECAPTCHA_URL . 'css/recaptcha.css', array(), KT_RECAPTCHA_VERSION, 'all' );
		}
		if ( 1 == self::settings( 'enable_v3' ) ) {
			$url = 'https://www.google.com/recaptcha/api.js';
			$url = add_query_arg(
				array(
					'render' => self::settings( 'v3_re_site_key' ),
				),
				$url
			);
			wp_register_script( 'ktv3-google-recaptcha', $url, array(), KT_RECAPTCHA_VERSION, true );
			$recaptcha_script = "grecaptcha.ready(function () { var kt_recaptcha_inputs = document.getElementsByClassName('kt-g-recaptcha'); if ( ! kt_recaptcha_inputs.length ) { return; } for (var i = 0; i < kt_recaptcha_inputs.length; i++) { const e = i; grecaptcha.execute('" . self::settings( 'v3_re_site_key' ) . "', { action: 'kt_verify_recaptcha' }).then(function (token) { kt_recaptcha_inputs[e].setAttribute('value', token); }); }; setInterval(function(){ for (var i = 0; i < kt_recaptcha_inputs.length; i++) { const e = i; grecaptcha.execute('" . self::settings( 'v3_re_site_key' ) . "', { action: 'kt_verify_recaptcha' }).then(function (token) { kt_recaptcha_inputs[e].setAttribute('value', token); }); } }, 60000); });";
			wp_add_inline_script( 'ktv3-google-recaptcha', $recaptcha_script, 'after' );
			// checkout scripts.
			$translation_array = array(
				'recaptcha_skey'  => self::settings( 'v3_re_site_key' ),
			);
			wp_register_script( 'kadence-checkout-recaptcha', KT_RECAPTCHA_URL . 'js/recaptcha_v3.js', array( 'jquery', 'ktv3-google-recaptcha' ), KT_RECAPTCHA_VERSION, true );
			wp_localize_script( 'kadence-checkout-recaptcha', 'ktrecap', $translation_array );

			if ( apply_filters( 'kadence_recaptcha_v3_load_every_page', true ) ) {
				wp_enqueue_script( 'ktv3-google-recaptcha' );
			}
		} else {
			wp_register_script( 'kadence-recaptcha-permission-js', KT_RECAPTCHA_URL . 'js/permission-recaptcha.js', array( 'jquery' ), KT_RECAPTCHA_VERSION, true );
			$permission_translation_array = array(
				'permission_cookie' => self::settings( 'consent_cookie' ),
			);
			wp_localize_script( 'kadence-recaptcha-permission-js', 'ktpercap', $permission_translation_array );

			wp_register_script( 'kadence-recaptcha-js', KT_RECAPTCHA_URL . 'js/recaptcha.js', array( 'jquery' ), KT_RECAPTCHA_VERSION, true );
			$translation_array = array(
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
				'recaptcha_elem'  => null,
				'recaptcha_id'    => 'kt-g-recaptcha',
				'recaptcha_skey'  => self::settings( 'kt_re_site_key' ),
				'recaptcha_theme' => self::settings( 'kt_re_theme' ),
				'recaptcha_size'  => self::settings( 'kt_re_size' ),
				'recaptcha_type'  => self::settings( 'kt_re_type' ),
			);
			wp_localize_script( 'kadence-recaptcha-js', 'ktrecap', $translation_array );

			// reCAPTCHA Google script.
			$url = 'https://www.google.com/recaptcha/api.js';
			$url = add_query_arg(
				array(
					'onload' => 'ktrecaploadCallback',
					'render' => 'explicit',
				),
				$url
			);
			wp_register_script( 'kt-google-recaptcha', $url, array( 'jquery', 'kadence-recaptcha-js' ), KT_RECAPTCHA_VERSION, true );
		}
	}

	/**
	 * Verify.
	 */
	public function verify_recaptcha() {

		require( KT_RECAPTCHA_PATH . 'inc/google-recaptcha.php' );

		if ( ! isset( $_POST['resp'] ) ) {
			die( json_encode( array( 'success' => false ) ) );
		}
		$secret     = trim( self::settings( 'kt_re_secret_key' ) );
		$recaptcha  = new KT_ReCaptcha( $secret );
		$response   = $recaptcha->verify( $_POST['resp'], $_SERVER['REMOTE_ADDR'] );

		if ( $response->is_success() ) {
			$data = array(
				'success' => true,
				'data' => array(
					'result' => 'OK',
					'address' => $_SERVER['REMOTE_ADDR'],
				),
			);
		} else {
			$data = array(
				'success' => false,
				'data' => $response->get_error_codes(),
			);
		}

		die( json_encode( $data ) );

	}
	/**
	 * Add Fields.
	 *
	 * @param array $field the form fields.
	 */
	public function testimonial_spam_field( $field ) {
		if ( 1 == self::settings( 'enable_for_testimonial' ) ) {
			if ( $this->permitted() ) {
				$out = $this->recaptcha_field();
			} else {
				$out = $this->consent_field();
			}
			return array(
				'input' => $out,
			);
		}
		return $field;
	}
	/**
	 * Add Fields.
	 *
	 * @param array $field the form fields.
	 */
	public function testimonial_spam_check( $is_human, $the_post = null ) {
		if ( 1 == self::settings( 'enable_for_testimonial' ) ) {
			if ( $this->permitted() ) {
				$verified = $this->run_verify_recaptcha();
				if ( $verified ) {
					return $is_human;
				} else {
					$is_human = false;
				}
			} else {
				$is_human = false;
			}
		}
		return $is_human;
	}

	public function contact_spam_field( $field ) {
		if ( 1 == self::settings( 'enable_for_contact' ) ) {
			if ( $this->permitted() ) {
				$out = $this->recaptcha_field();
			} else {
				$out = $this->consent_field();
			}
			return array(
				'input' => $out,
			);
		}
		return $field;
	}
	public function contact_spam_check( $is_human, $the_post = null ) {

		if ( 1 == self::settings( 'enable_for_contact' ) ) {
			if ( $this->permitted() ) {
				$verified = $this->run_verify_recaptcha();
				if ( $verified ) {
					return $is_human;
				} else {
					$is_human = false;
				}
			} else {
				$is_human = false;
			}
		}
		return $is_human;
	}

	public static function settings( $key ) {
		// Get raw value
		$stored_value = self::get_stored_value( $key, self::get_default_value( $key ) );

		// Allow developers to override.
		return apply_filters( 'kt_recaptcha_option_value', $stored_value, $key );
	}

	public static function get_stored_value( $key, $default = '' ) {
		// Get all stored values.
		$stored = (array) ( apply_filters( 'kadence_recaptcha_network', false ) ? get_site_option( 'kt_recaptcha', array() ) : get_option( 'kt_recaptcha', array() ) );

		// Check if value exists in stored values array.
		if ( ! empty( $stored ) && ( ( isset( $stored[ $key ] ) && '0' === $stored[ $key ] ) || ! empty( $stored[ $key ] ) ) ) {
			return $stored[ $key ];
		}

		// Stored value not found, use default value.
		return $default;
	}
	/**
	 * Get default options.
	 */
	public static function get_default_values() {
		if ( is_null( self::$default_values ) ) {
			// strip out all whitespace  convert the string to all lowercase.
			$name_clean = str_replace( ' ', '_', get_bloginfo( 'name' ) );
			$name_clean = strtolower( preg_replace( '/[^A-Za-z0-9\-]/', '', $name_clean ) );

			self::$default_values = array(
				'kt_re_secret_key'       => '',
				'kt_re_site_key'         => '',
				'kt_re_align'            => 'left',
				'kt_re_theme'            => 'light',
				'kt_re_size'             => 'normal',
				'kt_re_type'             => 'image',
				'enable_for_comments'    => 1,
				'enable_for_contact'     => 0,
				'enable_for_testimonial' => 0,
				'enable_for_login'       => 0,
				'enable_for_woocommerce' => 1,
				'enable_for_myaccount'   => 0,
				'enable_permission'      => 0,
				'consent_label'          => '',
				'consent_btn'            => __( 'I Consent', 'kadence-recaptcha' ),
				'consent_cookie'         => $name_clean . '_privacy_consent',
				'v3_re_secret_key'       => '',
				'v3_re_site_key'         => '',
				'enable_v3'              => 0,
			);
		}
		return self::$default_values;
	}
	/**
	 * Get default option value.
	 *
	 * @param string $key the option key.
	 */
	public static function get_default_value( $key ) {
		// Get default values.
		$default_values = self::get_default_values();

		// Check if such key exists and return default value.
		return isset( $default_values[ $key ] ) ? $default_values[ $key ] : '';
	}
	/**
	 * Check to see if we have permission to talk with Google.
	 */
	public function permitted() {
		$permitted = true;
		// if we don't have the option enabled lets move on.
		if ( 1 == self::settings( 'enable_permission' ) && 1 != self::settings( 'enable_v3' ) ) {
			$permitted      = false;
			$consent_cookie = strtolower( str_replace( ' ', '', self::settings( 'consent_cookie' ) ) );

			// if consent cookie name is set and that cookie exists then permit cookies.
			if ( $consent_cookie && self::cookie_get( $consent_cookie ) ) {
				$permitted = true;
			}
		}

		return apply_filters( 'kadence_recaptcha_permitted', $permitted );
	}
	/**
	 * Get the cookies to check for permissions.
	 *
	 * @param string $name the name of the cookie.
	 */
	public static function cookie_get( $name ) {
		return isset( $_COOKIE[ $name ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $name ] ) ) : false;
	}
	/**
	 * Set the cookies so we know we got permission.
	 *
	 * @param string $name the name of the cookie.
	 * @param string $value the yes for permission.
	 * @param number $expire how long the cookie should last.
	 */
	public static function cookie_set( $name, $value, $expire = 0 ) {
		wc_setcookie( $name, $value, $expire );
		return true;
	}
	/**
	 * Add to comments additional fields.
	 */
	public function additional_fields() {
		$enabled = false;
		if ( is_singular( 'product' ) ) {
			if ( 1 == self::settings( 'enable_for_woocommerce' ) ) {
				$enabled = true;
			}
		} else {
			if ( 1 == self::settings( 'enable_for_comments' ) ) {
				$enabled = true;
			}
		}
		if ( $enabled ) {
			if ( $this->permitted() ) {
				echo $this->recaptcha_field(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo $this->consent_field(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
	/**
	 * Get the total amount of captchas.
	 */
	public function total_captcha() {
		return self::$captcha_count;
	}
	/**
	 * Output the recaptcha field.
	 *
	 * @param string $before content before the field.
	 * @param string $after content after the field.
	 * @param string $id the field id.
	 * @param string $styles the fields styles.
	 */
	public function recaptcha_field( $before = '', $after = '', $id = null, $styles = '' ) {
		self::$captcha_count++;
		$number = $this->total_captcha();
		if ( 1 == self::settings( 'enable_v3' ) ) {
			wp_enqueue_script( 'ktv3-google-recaptcha' );
			$out  = '<input type="hidden" class="kt-g-recaptcha" id="kt_g_recaptcha_' . esc_attr( $number ) . '" name="g-recaptcha-response">';
			return apply_filters( 'kadence_recaptcha_v3_input', $out );
		} else {
			wp_enqueue_script( 'kadence-recaptcha-js' );
			wp_enqueue_script( 'kt-google-recaptcha' );
			$out  = $before;
			$out .= '<p id="kt-container-g-recaptcha" class="google-recaptcha-container recaptcha-align-' . esc_attr( self::settings( 'kt_re_align' ) ) . '" style="text-align:' . esc_attr( self::settings( 'kt_re_align' ) ) . ';' . esc_attr( $styles ) . '">';
			$out .= '<span id="' . ( ! empty( $id ) ? $id : 'kt_g_recaptcha_' . esc_attr( $number ) ) . '" class="kt-g-recaptcha g-recaptcha" data-forced="0" style="display:inline-block;">';
			$out .= '</span>';
			$out .= '</p>';
			$out .= $after;
			return apply_filters( 'kadence_recaptcha_field', $out );
		}
	}
	/**
	 * Output the consent field.
	 */
	public function consent_field() {
		wp_enqueue_script( 'kadence-recaptcha-permission-js' );
		$out  = '<div id="kt-permission-container-g-recaptcha" class="consent-google-recaptcha-container">';
		$out .= '<div class="consent-google-recaptcha-label">';
		$out .= self::consent_label();
		$out .= '</div>';
		$out .= '<a href="#" id="kt-permission-consent" class="btn button">';
		$out .= self::consent_btn();
		$out .= '</a>';
		$out .= '<div class="consent-google-recaptcha-cookie-note">';
		$out .= self::consent_cookie_notice();
		$out .= '</div>';
		$out .= '</div>';
		return apply_filters( 'kadence_recaptcha_consent_field', $out );
	}
	/**
	 * Output the consent field label.
	 */
	public static function consent_label() {
		$label = self::settings( 'consent_label' );
		if ( ! empty( $label ) ) {
			$consent_label = $label;
		} else {
			if ( function_exists( 'the_privacy_policy_link' ) ) {
				$privacy_link = get_the_privacy_policy_link();
			}
			if ( ! empty( $privacy_link ) ) {
				/* translators:  %s: privacy page link */
				$consent_label = sprintf( __( 'To use this form you must consent to our %s.', 'kadence-recaptcha' ), $privacy_link );
			} else {
				$consent_label = __( 'To use this form you must consent to our privacy policy.', 'kadence-recaptcha' );
			}
		}
		return apply_filters( 'kadence_recaptcha_consent_label', $consent_label );
	}
	/**
	 * Output the consent field button.
	 */
	public static function consent_btn() {
		$consent_btn = self::settings( 'consent_btn' );

		return apply_filters( 'kadence_recaptcha_consent_btn', $consent_btn );
	}
	/**
	 * Output the consent cookie notice.
	 */
	public static function consent_cookie_notice() {
		$consent_notice = __( '*Cookies must be enabled in your browser', 'kadence-recaptcha' );

		return apply_filters( 'kadence_recaptcha_consent_cookie_notice', $consent_notice );
	}
	/**
	 * Second verification process, just in case of someone breaks reCAPTCHA manually
	 *
	 * @param string $comment_post_id the post id.
	 */
	public function after_verify_recaptcha( $comment_post_id ) {
		$enabled = false;
		if ( 'product' == get_post_type( $comment_post_id ) ) {
			if ( self::settings( 'enable_for_woocommerce' ) ) {
				$enabled = true;
			}
		} else {
			if ( 1 == self::settings( 'enable_for_comments' ) ) {
				$enabled = true;
			}
		}
		if ( $enabled ) {
			$user = wp_get_current_user();
			if ( $user->exists() ) {
				return;
			}
			if ( $this->permitted() ) {
				$verified = $this->run_verify_recaptcha();
				if ( $verified ) {
					return;
				} else {
					wp_die(
						'<p>' . esc_html__( 'Sorry, it seems you\'re a robot.', 'kadence-recaptcha' ) . '</p>',
						'',
						array(
							'response' => 403,
							'back_link' => true,
						)
					);
				}
			} else {
				wp_die(
					'<p>' . esc_html__( 'Sorry, to post a comment or login you must consent to our privacy policy and have cookies enabled.', 'kadence-recaptcha' ) . '</p>',
					'',
					array(
						'response' => 403,
						'back_link' => true,
					)
				);
			}
		}
		return;
	}
	/**
	 * Check Recaptcha v3
	 *
	 * @param string $token Recaptcha token.
	 * @param string $secret Recaptcha secret key.
	 *
	 * @return bool
	 */
	private function verify_v3_recaptcha( $token, $secret ) {
		$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
		if ( ! $secret ) {
			return false;
		}
		$args = array(
			'body' => array(
				'secret'   => $secret,
				'response' => $token,
			),
		);
		$verify_request = wp_remote_post( $recaptcha_url, $args );
		if ( is_wp_error( $verify_request ) ) {
			return false;
		}
		$response = wp_remote_retrieve_body( $verify_request );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$response = json_decode( $response, true );

		if ( ! isset( $response['success'] ) ) {
			return false;
		}
		return $response['success'];
	}
}
Kadence_Recaptcha_Fontend::get_instance();
