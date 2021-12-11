<?php
/**
 * Add Variation Gallery Options to Woocommerce Variation Products.
 *
 * @package Kadence Woo Extras
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to Add Variation Gallery Options to Woocommerce Variation Products.
 *
 * @category class
 */
class Kadence_Variation_Gallery {
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
	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'extra_fields' ), 10, 3 );
		add_action( 'woocommerce_save_product_variation', array( $this, 'save_extra_variation_image' ), 10, 2 );
	}
	/**
	 * Save extra images.
	 *
	 * @param numeric $post_id the post ID.
	 */
	public function save_extra_variation_image( $variation_id, $loop ) {
		if ( isset( $_POST['_kt_extra_variation_img_ids'][ $variation_id ] ) ) {
			$image_ids = wp_unslash( $_POST['_kt_extra_variation_img_ids'][ $variation_id ] );
			$update_ids = $image_ids;
			if ( $image_ids ) {
				$update_ids = array();
				$attachment_ids = explode( ',', $image_ids );
				foreach ( $attachment_ids as $id ) {
					$id = absint( $id );
					if ( ! empty( $id ) ) {
						$update_ids[] = $id;
					}
				}
				$update_ids = implode( ',', $update_ids );
			}
			update_post_meta( $variation_id, '_kt_extra_variation_img_ids', sanitize_text_field( $update_ids ) );
		}
	}
	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {
		$screen       = get_current_screen();
		$screen_id    = $screen ? $screen->id : '';
		if ( in_array( $screen_id, array( 'product', 'edit-product' ) ) ) {
			wp_enqueue_media();
			wp_enqueue_style( 'kadence-variation-images-admin', KADENCE_WOO_EXTRAS_URL . 'lib/variation-gallery/css/variation-images-admin.css', false, KADENCE_WOO_EXTRAS_VERSION );
			wp_enqueue_script( 'kadence-variation-images-admin', KADENCE_WOO_EXTRAS_URL . 'lib/variation-gallery/js/variation-images-admin.js', array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), KADENCE_WOO_EXTRAS_VERSION, true );
		}
	}
	/**
	 * Add Extra Fields.
	 *
	 * @param number $loop the loop number.
	 * @param array $variation_data the meta data.
	 * @param object $variation the post object.
	 */
	public function extra_fields( $loop, $variation_data, $variation ) {

		$variation_images = get_post_meta( $variation->ID, '_kt_extra_variation_img_ids', true );
		$output = '';
		if ( isset( $variation_images ) && ! empty( $variation_images ) ) {
			$attachment_ids = explode( ',', $variation_images );
			foreach ( $attachment_ids as $id ) {
				if ( ! empty( $id ) ) {
					$attachment = wp_get_attachment_image( $id, 'thumbnail' );
					$output .= '<li class="image" data-attachment-id="' . esc_attr( $id ) . '">';
					$output .= $attachment;
					$output .= '<ul class="actions">';
					$output .= '<li><a href="#" class="delete">' . esc_html__( 'Delete', 'kadence-woo-extras' ) . '</a></li>';
					$output .= '</ul>';
					$output .= '</li>';
				}
			}
		}
		echo '<div data-product_variation_id="' . esc_attr( $variation->ID ) . '" class="form-row form-row-full kwsv-variations-images-wrapper">';
		echo '<h4>' . esc_html__( 'Variation Image Gallery', 'kadence-woo-extras' ) . '</h4>';
		echo '<div class="kwsv-gallery-wrap"><ul class="kwsv-gallery-list">' . $output . '</ul></div>';
		/* Hidden field*/
		woocommerce_wp_hidden_input(
			array( 
				'id'          => '_kt_extra_variation_img_ids[' . $variation->ID . ']',
				'desc_tip'    => 'true',
				'value'       => esc_attr( $variation_images ),
				'class' 	  => 'kwsv_gallery_images',
			)
		);
		echo '<p class="add_variation_images hide-if-no-js">';
		echo '<a href="#" onclick="return false;" data-product_variation_id="' . esc_attr( $variation->ID ) . '" class="button button-primary kwsv-upload-variation-img" data-choose="' . esc_attr__( 'Add images to product gallery', 'kadence-woo-extras' ) . '" data-update="' . esc_attr__( 'Add to gallery', 'kadence-woo-extras' ) . '" data-delete="' . esc_attr__( 'Delete image', 'kadence-woo-extras' ) . '" data-text="' . esc_attr__( 'Delete', 'kadence-woo-extras' ) . '">' . esc_html__( 'Add Variation Images', 'kadence-woo-extras' ) . '</a>';
		echo '</p>';
		echo '</div>';
	}
}
Kadence_Variation_Gallery::get_instance();
