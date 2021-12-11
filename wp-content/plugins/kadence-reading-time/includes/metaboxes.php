<?php
/**
 * Register a meta box using a class.
 */
class Kadence_Reading_Time_Meta_Box {
 
    /**
     * Constructor.
     */
    public function __construct() {
		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}
 
	/**
	* Meta box initialization.
	*/
	public function init_metabox() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
	}
 
	/**
	* Adds the meta box.
	*/
	public function add_metabox() {
		add_meta_box(
		'kt-reading-time-meta',
		__( 'Reading Time', 'kadence-reading-time' ),
			array( $this, 'render_metabox' ),
			'post',
			'advanced',
			'default'
		);
	}
 
	/**
	* Renders the meta box.
	*/
	public function render_metabox( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'kt_reading_nonce_action', 'kt_reading_nonce' );
		$output = '<div class="kt_meta_boxes">';
			$output .= '<div class="kt_meta_box kt_disable_reading_time" style="padding: 10px 0 0; border-bottom:1px solid #e9e9e9;">';
				$output .= '<div style="width: 18%; padding: 0 2% 0 0; float: left;">';
					$output .= '<label for="_kt_disable_reading_time" style="font-weight: 600;">'. esc_html__('Disable Reading Time?', 'kadence-reading-time') .'</label>';
				$output .= '</div>';
				$output .= '<div style="width: 80%;float: right;">';
					$option_values = array('false', 'true');
					$select_value = get_post_meta( $post->ID, '_kt_disable_reading_time', true );
					$output .= '<select name="_kt_disable_reading_time">';
                    foreach( $option_values as $key => $value )  {
                        if( $value == $select_value ) {
							$output .= '<option value="'.esc_attr( $value ).'" selected>'.esc_attr($value).'</option>';  
						} else {
							$output .= '<option value="'.esc_attr( $value ).'">'.esc_attr($value).'</option>';  
                        }
                    }
                    $output .= '</select>';
				$output .= '</div>';
				$output .= '<div class="clearfixit" style="padding: 5px 0; clear:both;"></div>';
			$output .= '</div>';
			$output .= '<div class="kt_meta_box kt_custom_reading_time" style="padding: 10px 0 0; border-bottom:1px solid #e9e9e9; margin: 0 0 .8em; clear:both;">';
				$output .= '<div style="width: 18%; padding: 0 2% 0 0; float: left;">';
					$output .= '<label for="_kt_custom_reading_time"  style="font-weight: 600;">'. esc_html__('Custom Reading Time', 'kadence-reading-time') .'</label>';
				$output .= '</div>';
				$output .= '<div style="width: 80%;float: right;">';
					$field_value = get_post_meta( $post->ID, '_kt_custom_reading_time', true );
					$output .= '<input type="text" name="_kt_custom_reading_time" id="_kt_custom_reading_time" class="_kt_custom_reading_time" value="'. esc_attr( $field_value ) .'" style="width:300px;"/>';
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
        $nonce_name   = isset( $_POST['kt_reading_nonce'] ) ? $_POST['kt_reading_nonce'] : '';
        $nonce_action = 'kt_reading_nonce_action';
 
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
        if ( isset( $_POST[ '_kt_disable_reading_time' ] ) ) {
			$kt_disable_reading_time_value = sanitize_text_field( $_POST[ '_kt_disable_reading_time' ] );
			update_post_meta( $post_id , '_kt_disable_reading_time', $kt_disable_reading_time_value);
		}

		if ( isset( $_POST[ '_kt_custom_reading_time' ] ) ) {
			$kt_custom_reading_time_value = sanitize_text_field( $_POST[ '_kt_custom_reading_time' ] );
			update_post_meta( $post_id , '_kt_custom_reading_time', $kt_custom_reading_time_value);
		}
    }
}
 
new Kadence_Reading_Time_Meta_Box();