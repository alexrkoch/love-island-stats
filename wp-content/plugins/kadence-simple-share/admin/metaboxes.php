<?php
/**
 * Register a meta box using a class.
 */
class Kadence_Simple_Share_Meta_Box {

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'load-post.php', array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	/**
	 * Meta box initialization.
	 */
	public function init_metabox() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
	}

	/**
	 * Adds the meta box.
	 */
	public function add_metabox() {
		add_meta_box(
			'_kt_share_share_control',
			__( 'Simple Share Control', 'kadence-simple-share' ),
			array( $this, 'render_metabox' ),
			array( 'product', 'post', 'portfolio', 'page' ),
			'side',
			'low'
		);
	}

	/**
	 * Renders the meta box.
	 */
	public function render_metabox( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'kt_share_nonce_action', 'kt_share_nonce' );
		$output = '<div class="kt_meta_boxes">';
			$output .= '<div class="kt_meta_box kt_simple_meta" style="padding: 10px 0 0;">';
				$output .= '<div style="padding-bottom:10px;">';
					$output .= '<label for="_kt_share_control" style="font-weight: 600;">' . esc_html__( 'Simple Sharing Display', 'kadence-simple-share' ) . '</label>';
				$output .= '</div>';
				$output .= '<div>';
					$option_values = array(
						'default'       => __( 'Default', 'kadence-simple-share' ),
						'show'          => __( 'Show', 'kadence-simple-share' ),
						'hide'          => __( 'Hide', 'kadence-simple-share' ),
					);
					$select_value = get_post_meta( $post->ID, '_kt_share_control', true );
					$output .= '<select style="width:100%; box-sizing: border-box;" name="_kt_share_control">';
					foreach ( $option_values as $key => $value ) {
						if ( $key == $select_value ) {
							$output .= '<option value="' . esc_attr( $key ) . '" selected>' . esc_attr( $value ) . '</option>';
						} else {
							$output .= '<option value="' . esc_attr( $key ) . '">' . esc_attr( $value ) . '</option>';
						}
					}
					$output .= '</select>';
					$output .= '</div>';
					$output .= '<div class="clearfixit" style="padding: 5px 0; clear:both;"></div>';
					$output .= '</div>';
					$output .= '</div>';

					echo $output;
	}

	/**
	 * Handles saving the meta box.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @return null
	 */
	public function save_metabox( $post_id, $post ) {
		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['kt_share_nonce'] ) ? $_POST['kt_share_nonce'] : '';
		$nonce_action = 'kt_share_nonce_action';

		// Check if nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}

		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( isset( $_POST['_kt_share_control'] ) ) {
			$kt_share_control_value = sanitize_text_field( $_POST['_kt_share_control'] );
			update_post_meta( $post_id, '_kt_share_control', $kt_share_control_value );
		}
	}
}

new Kadence_Simple_Share_Meta_Box();
