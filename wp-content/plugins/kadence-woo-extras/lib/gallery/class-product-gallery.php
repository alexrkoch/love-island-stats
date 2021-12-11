<?php
/**
 * This overrides woocommerce.
 *
 * @package Kadence Woo Extras
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class to build out custom product gallery.
 *
 * @category class
 */
class Kadence_Shop_Kit_Product_Gallery {

	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_filter( 'wc_get_template', array( $this, 'filter_in_product_gallery' ), 20, 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 150 );
		add_action( 'init', array( $this, 'remove_theme_filter' ), 20 );
		add_filter( 'cmb2_admin_init', array( $this, 'product_gallery_metaboxes' ) );
		add_filter( 'cmb2_textarea_attributes', array( $this, 'product_gallery_metaboxes_args' ), 10, 4 );
		add_filter( 'woocommerce_get_image_size_single', array( $this, 'product_gallery_sizes' ) );
		add_filter( 'woocommerce_gallery_thumbnail_size', array( $this, 'product_variation_sizes' ) );
		// Add the filter for editing the custom url field.
		add_filter( 'attachment_fields_to_edit', array( $this, 'add_media_link_input' ), null, 2 );
		// Add the filter for saving the custom url field.
		add_filter( 'attachment_fields_to_save', array( $this, 'add_media_link_save' ), null , 2 );
	}
	/**
	 * Add new image meta video link input.
	 */
	public function add_media_link_input( $form_fields, $post ) {
		$form_fields['kt_woo_product_video'] = array(
			'label' => __( 'Product Video URL (Youtube, Vimeo)', 'kadence-woo-extras' ),
			'helps' => __( 'Used in Product Gallery for Popup Video', 'kadence-woo-extras' ),
			'input' => 'text',
			'value' => get_post_meta( $post->ID, '_kt_woo_product_video', true ),
		);
		return $form_fields;
	}
	/**
	 * Save new image meta video link input.
	 */
	public function add_media_link_save( $post, $attachment ) {

		if ( isset( $attachment['kt_woo_product_video'] ) ) {
			update_post_meta( $post['ID'], '_kt_woo_product_video', esc_url_raw( $attachment['kt_woo_product_video'] ) );
		}
		return $post;
	}
	/**
	 * Override the Product image template.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 */
	public function filter_in_product_gallery( $template, $template_name, $args, $template_path, $default_path ) {
		if ( 'single-product/product-image.php' != $template_name ) {
			return $template;
		}

		return KADENCE_WOO_EXTRAS_PATH . 'lib/gallery/product-image.php';
	}
	/**
	 * This maintains backwards compatibility.
	 */
	public function kt_woo_product_gallery() {
		require_once KADENCE_WOO_EXTRAS_PATH . 'lib/gallery/product-image.php';
	}
	/**
	 * Add in the gallery scripts and styles.
	 */
	public function enqueue_scripts() {
		if ( is_singular( 'product' ) ) {

			wp_enqueue_style( 'kadence_product_gallery_css', KADENCE_WOO_EXTRAS_URL . 'lib/gallery/css/kt-product-gallery.css', false, KADENCE_WOO_EXTRAS_VERSION );
			wp_enqueue_script( 'kadence_product_gallery_zoom', KADENCE_WOO_EXTRAS_URL . 'lib/gallery/js/min/jquery.elevateZoom.min.js', array( 'jquery' ), KADENCE_WOO_EXTRAS_VERSION, true );
			wp_enqueue_script( 'kadence-gal-slick', KADENCE_WOO_EXTRAS_URL . 'lib/gallery/js/min/slick-min.js', array( 'jquery' ), KADENCE_WOO_EXTRAS_VERSION, true );
			if ( ! theme_is_kadence() ) {
				wp_enqueue_script( 'magnific_popup', KADENCE_WOO_EXTRAS_URL . 'lib/gallery/js/min/magnific-popup-min.js', array( 'jquery' ), KADENCE_WOO_EXTRAS_VERSION, true );
				wp_enqueue_style( 'magnific_popup_css', KADENCE_WOO_EXTRAS_URL . 'lib/gallery/css/magnific-popup.css', false, KADENCE_WOO_EXTRAS_VERSION );
			} else {
				$the_theme = wp_get_theme();
				if ( $the_theme->get( 'Name' ) == 'Virtue - Premium' || $the_theme->get( 'Template' ) == 'virtue_premium' ) {
					if ( ! wp_script_is( 'virtue_lightbox', 'enqueued' ) ) {
						wp_enqueue_script( 'magnific_popup', KADENCE_WOO_EXTRAS_URL . 'lib/gallery/js/min/magnific-popup-min.js', array( 'jquery' ), KADENCE_WOO_EXTRAS_VERSION, true );
						wp_enqueue_style( 'magnific_popup_css', KADENCE_WOO_EXTRAS_URL . 'lib/gallery/css/magnific-popup.css', false, KADENCE_WOO_EXTRAS_VERSION );
					}
				}
			}
			wp_enqueue_script( 'kadence_product_gallery', KADENCE_WOO_EXTRAS_URL . 'lib/gallery/js/kt_product_gallery.js', array( 'jquery', 'kadence-gal-slick' ), KADENCE_WOO_EXTRAS_VERSION, true );
			$lightbox_translation_array = array(
				'loading' => __( 'Loading...', 'kadence-woo-extras' ),
				'of'      => '%curr% ' . __( 'of', 'kadence-woo-extras' ) . ' %total%',
				'error'   => __( 'The Image could not be loaded.', 'kadence-woo-extras' ),
			);
			wp_localize_script( 'kadence_product_gallery', 'kpg_lightbox', $lightbox_translation_array );
		}
	}
	/**
	 * Remove custom theme filter effecting controls..
	 */
	public function remove_theme_filter() {
		remove_filter( 'woocommerce_get_image_size_single', 'virtue_woo_product_gallery_sizes' );
	}
	/**
	 * Filter Product Image sizes for Woocommerce
	 *
	 * @param array $size the gallery sizes.
	 */
	public function product_gallery_sizes( $size ) {
		global $kt_woo_extras;
		if ( isset( $kt_woo_extras['product_gallery_custom_size'] ) && false == $kt_woo_extras['product_gallery_custom_size'] ) {
			return $size;
		}
		if ( isset( $kt_woo_extras['ga_image_width'] ) ) {
			$productimgwidth = $kt_woo_extras['ga_image_width'];
		} else {
			$productimgwidth = 465;
		}
		if ( isset( $kt_woo_extras['ga_image_ratio'] ) ) {
			$img_ratio = $kt_woo_extras['ga_image_ratio'];
		} else {
			$img_ratio = 'square';
		}
		if ( $img_ratio == 'custom' ) {
			if ( isset( $kt_woo_extras['ga_image_height'] ) ) {
				$productimgheight = $kt_woo_extras['ga_image_height'];
			} else {
				$productimgheight = 465;
			}
		} elseif ( $img_ratio == 'portrait' ) {
			$tempproductimgheight = $productimgwidth * 1.35;
			$productimgheight = floor( $tempproductimgheight );
		} elseif ( $img_ratio == 'landscape' ) {
			$tempproductimgheight = $productimgwidth / 1.35;
			$productimgheight = floor( $tempproductimgheight );
		} elseif ( $img_ratio == 'landscape32' ) {
			$tempproductimgheight = $productimgwidth / 1.5;
			$productimgheight = floor( $tempproductimgheight );
		} elseif ( $img_ratio == 'widelandscape' ) {
			$tempproductimgheight = $productimgwidth / 2;
			$productimgheight = floor( $tempproductimgheight );
		} else {
			$productimgheight = $productimgwidth;
		}
		$size = array(
			'width'  => $productimgwidth,
			'height' => $productimgheight,
			'crop'   => 1,
		);
		return $size;
	}
	/**
	 * Filter Product Image sizes for Woocommerce
	 *
	 * @param array $sizes the gallery sizes.
	 */
	public function product_variation_sizes( $sizes ) {
		global $kt_woo_extras;
		if ( isset( $kt_woo_extras['ga_image_width'] ) ) {
			$productimgwidth = $kt_woo_extras['ga_image_width'];
		} else {
			$productimgwidth = 465;
		}
		if ( isset( $kt_woo_extras['ga_image_ratio'] ) ) {
			$img_ratio = $kt_woo_extras['ga_image_ratio'];
		} else {
			$img_ratio = 'square';
		}
		if ( $img_ratio == 'custom' ) {
			if ( isset( $kt_woo_extras['ga_image_height'] ) ) {
				$productimgheight = $kt_woo_extras['ga_image_height'];
			} else {
				$productimgheight = 465;
			}
		} elseif ( $img_ratio == 'portrait' ) {
			$tempproductimgheight = $productimgwidth * 1.35;
			$productimgheight = floor( $tempproductimgheight );
		} elseif ( $img_ratio == 'landscape' ) {
			$tempproductimgheight = $productimgwidth / 1.35;
			$productimgheight = floor( $tempproductimgheight );
		} elseif ( $img_ratio == 'landscape32' ) {
			$tempproductimgheight = $productimgwidth / 1.5;
			$productimgheight = floor( $tempproductimgheight );
		} elseif ( $img_ratio == 'widelandscape' ) {
			$tempproductimgheight = $productimgwidth / 2;
			$productimgheight = floor( $tempproductimgheight );
		} else {
			$productimgheight = $productimgwidth;
		}
		if ( isset( $kt_woo_extras['ga_slider_layout'] ) ) {
			$layout = $kt_woo_extras['ga_slider_layout'];
			if ( $layout == 'left' || $layout == 'right' ) {
				$vlayout = 'true';
			} else {
				$vlayout = 'false';
			}
		} else {
			$layout  = 'above';
			$vlayout = 'false';
		}
		if ( isset( $kt_woo_extras['ga_thumb_columns'] ) ) {
			$thumb_columns = $kt_woo_extras['ga_thumb_columns'];
		} else {
			$thumb_columns = 7;
		}
		if ( 'true' == $vlayout ) {
			$thumb_productimgheight = floor( $productimgheight / $thumb_columns ) - 4;
		} else {
			$thumb_productimgwidth = floor( $productimgwidth / $thumb_columns );
		}
		if ( isset( $kt_woo_extras['ga_thumb_image_ratio'] ) ) {
			$thumb_img_ratio = $kt_woo_extras['ga_thumb_image_ratio'];
		} else {
			$thumb_img_ratio = 'square';
		}
		if ( $vlayout == 'true' ) {
			if ( $thumb_img_ratio == 'portrait' ) {
				$tempproductimgwidth   = $thumb_productimgheight / 1.35;
				$thumb_productimgwidth = floor( $tempproductimgwidth );
			} elseif ( $thumb_img_ratio == 'landscape' ) {
				$tempproductimgwidth   = $thumb_productimgheight * 1.35;
				$thumb_productimgwidth = floor( $tempproductimgwidth );
			} elseif ( $thumb_img_ratio == 'landscape32' ) {
				$tempproductimgwidth   = $thumb_productimgheight * 1.5;
				$thumb_productimgwidth = floor( $tempproductimgwidth );
			} elseif ( $thumb_img_ratio == 'widelandscape' ) {
				$tempproductimgwidth   = $thumb_productimgheight * 2;
				$thumb_productimgwidth = floor( $tempproductimgwidth );
			} elseif ( $thumb_img_ratio == 'inherit' ) {
				$thumb_productimgwidth  = 120;
				$thumb_productimgheight = null;
			} else {
				$thumb_productimgwidth = $thumb_productimgheight;
			}
		} else {
			if ( $thumb_img_ratio == 'portrait' ) {
				$tempproductimgheight = $thumb_productimgwidth * 1.35;
				$thumb_productimgheight = floor( $tempproductimgheight );
			} elseif ( $thumb_img_ratio == 'landscape' ) {
				$tempproductimgheight = $thumb_productimgwidth / 1.35;
				$thumb_productimgheight = floor( $tempproductimgheight );
			} elseif ( $thumb_img_ratio == 'landscape32' ) {
				$tempproductimgheight = $thumb_productimgwidth / 1.5;
				$thumb_productimgheight = floor( $tempproductimgheight );
			} elseif ( $thumb_img_ratio == 'widelandscape' ) {
				$tempproductimgheight = $thumb_productimgwidth / 2;
				$thumb_productimgheight = floor( $tempproductimgheight );
			} elseif ( $thumb_img_ratio == 'inherit' ) {
				$thumb_productimgheight = null;
			} else {
				$thumb_productimgheight = $thumb_productimgwidth;
			}
		}
		$thumb_productimgwidth = 2 * $thumb_productimgwidth;
		$thumb_productimgheight = 2 * $thumb_productimgheight;
		$size = array( $thumb_productimgwidth, $thumb_productimgheight );

		return $size;
	}
	/**
	 * Add args to metabox settings.
	 */
	public function product_gallery_metaboxes_args( $args, $type_defaults, $field, $types ) {
		if ( '_kt_woo_gallery_shortcode' === $field->args['id'] ) {
			$args['rows'] = 2;
		}
		return $args;
	}
	/**
	 * Add in gallery options for a shortcode override.
	 */
	public function product_gallery_metaboxes() {

		$prefix = '_kt_woo_';
		$kadence_product_gallery = new_cmb2_box(
			array(
				'id'            => $prefix . 'gallery_override',
				'title'         => __( 'Override product Gallery', 'kadence-woo-extras' ),
				'object_types'  => array( 'product' ), // Post type.
				'priority'   => 'low',
			)
		);
		$kadence_product_gallery->add_field(
			array(
				'name'          => __( 'Replace Gallery with Shortcode', 'kadence-woo-extras' ),
				'id'            => $prefix . 'gallery_shortcode',
				'type'          => 'textarea_code',
				'rows' => 4,
				'options' => array(
					'textarea_rows'      => 4,
					'disable_codemirror' => true,
				),
			)
		);
	}
}

$GLOBALS['kt_product_gallery'] = Kadence_Shop_Kit_Product_Gallery::get_instance();
