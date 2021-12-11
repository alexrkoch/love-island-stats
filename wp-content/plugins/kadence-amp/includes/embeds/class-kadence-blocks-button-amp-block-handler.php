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
class Kadence_Blocks_Btn_AMP_Block_Handler extends AMP_Base_Embed_Handler {

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
	public $block_name = 'kadence/advancedbtn';

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
		if ( ! wp_style_is( 'kt-blocks-btn', 'enqueued' ) && ! amp_is_canonical() ) {
			wp_register_style( 'kt-blocks-btn', false );
			wp_enqueue_style( 'kt-blocks-btn' );
			$btn_css = $this->build_amp_css();
			$content = '<style id="kt-blocks-btn" type="text/css">' . $btn_css . '</style>' . $content;
		}
		if ( isset( $attributes['uniqueID'] ) ) {
			$unique_id = $attributes['uniqueID'];
			$style_id = 'kt-blocks' . esc_attr( $unique_id );
			if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
				if ( ! empty( $this->kadence_blocks_class ) && method_exists( $this->kadence_blocks_class, 'blocks_advanced_btn_array' ) ) {
					$css = $this->kadence_blocks_class->blocks_advanced_btn_array( $attributes, $unique_id );
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
		$css = ".kt-button {
			padding: 8px 16px;
		z-index: 1;
		position: relative;
		cursor: pointer;
		font-size: 18px;
		display: flex;
		line-height: 1.6;
		text-decoration: none;
		text-align: center;
		justify-content: center;
		border-style: solid;
		transition: all .3s ease-in-out;
		border-width:2px;
		border-radius: 3px;
		border-color:#555555;
		overflow: hidden;
		background:transparent;
		color:#555555;
		}
		.kt-button:hover, .kt-button:focus {
		text-decoration: none;
		}
		.kt-btn-svg-icon.kt-btn-side-right {
		padding-left: 5px;
		}
		.kt-btn-svg-icon.kt-btn-side-left {
		padding-right: 5px;
		}
		.kt-btn-has-text-false .kt-btn-svg-icon {
		padding-left:0;
		padding-right:0;
		}
		.kt-btn-wrap {
		display: inline-block;
		margin-bottom: 5px;
		}
		.kt-btn-align-center {
		text-align: center;
		}
		.kt-btn-align-left {
		text-align: left;
		}
		.kt-btn-align-right {
		text-align: right;
		}
		.wp-block-kadence-advancedbtn .kt-btn-wrap:last-child {
		margin-right: 0;
		}
		.wp-block-kadence-advancedbtn .kt-btn-wrap {
		margin-right: 5px;
		}";

		return $css;
	}
}
