<?php
/**
 * Class AMP_Core_Block_Handler
 *
 * @package AMP
 */

/**
 * Class AMP_Core_Block_Handler
 *
 * @since 1.0
 */
class Kadence_Blocks_Spacer_AMP_Block_Handler extends AMP_Base_Embed_Handler {

	/**
	 * Original block callback.
	 *
	 * @var array
	 */
	public $original_row_callback;

	/**
	 * Kadence Blocks Class.
	 *
	 * @var object
	 */
	public $kadence_blocks_class;

	/**
	 * Block name.
	 *
	 * @var string
	 */
	public $block_name = 'kadence/spacer';

	/**
	 * Register embed.
	 */
	public function register_embed() {
		if ( class_exists( 'WP_Block_Type_Registry' ) ) {
			$registry = WP_Block_Type_Registry::get_instance();
			$block    = $registry->get_registered( $this->block_name );

			if ( $block ) {
				$this->original_row_callback = $block->render_callback;
				$block->render_callback      = array( $this, 'render' );
			}
		}
		if ( class_exists( 'Kadence_Blocks_Frontend' ) ) {
			$this->kadence_blocks_class = Kadence_Blocks_Frontend::get_instance();
		}
	}

	/**
	 * Unregister embed.
	 */
	public function unregister_embed() {
		if ( class_exists( 'WP_Block_Type_Registry' ) ) {
			$registry = WP_Block_Type_Registry::get_instance();
			$block    = $registry->get_registered( $this->block_name );

			if ( $block && ! empty( $this->original_row_callback ) ) {
				$block->render_callback      = $this->original_row_callback;
				$this->original_row_callback = null;
			}
		}
	}

	/**
	 * Render Row Block CSS Inline
	 *
	 * @param array  $attributes the blocks attribtues.
	 * @param string $content the blocks content.
	 */
	public function render( $attributes, $content ) {
		if ( ! wp_style_is( 'kt-blocks-spacer', 'enqueued' ) && ! amp_is_canonical() ) {
			wp_register_style( 'kt-blocks-spacer', false );
			wp_enqueue_style( 'kt-blocks-spacer' );
			$btn_css = $this->build_amp_css();
			$content = '<style id="kt-blocks-spacer" type="text/css">' . $btn_css . '</style>' . $content;
		}
		if ( isset( $attributes['uniqueID'] ) ) {
			$unique_id = $attributes['uniqueID'];
			$style_id = 'kt-blocks' . esc_attr( $unique_id );
			if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
				if ( ! empty( $this->kadence_blocks_class ) && method_exists( $this->kadence_blocks_class, 'blocks_spacer_array' ) ) {
					$css = $this->kadence_blocks_class->blocks_spacer_array( $attributes, $unique_id );
					$content = '<style id="' . $style_id . '" type="text/css">' . $css . '</style>' . $content;
				}
			}
		}
		return $content;
	}
	/**
	 * Render Row Block General CSS Inline
	 */
	public function build_amp_css() {
		$css = ".kt-block-spacer {
    position: relative;
    height: 60px;
}
.kt-divider {
    width: 100%;
    border-top: solid 1px #eee;
    position: absolute;
    top: 50%;
    left: 50%;
        margin: 0;
    padding: 0;
    border-bottom: 0;
    border-left: 0;
    border-right: 0;
    transform: perspective(1px) translate(-50%, -50%);
}
.kt-block-spacer-halign-left .kt-divider {
    left: 0;
    transform: perspective(1px) translate(0%, -50%);
}
.kt-block-spacer-halign-right .kt-divider {
    left: auto;
    right:0;
    transform: perspective(1px) translate(0%, -50%);
}";

		return $css;
	}
}
