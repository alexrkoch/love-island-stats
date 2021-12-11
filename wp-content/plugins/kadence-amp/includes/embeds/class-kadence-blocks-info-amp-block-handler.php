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
class Kadence_Blocks_Info_AMP_Block_Handler extends AMP_Base_Embed_Handler {

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
	public $block_name = 'kadence/infobox';

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
		if ( ! wp_style_is( 'kt-blocks-info', 'enqueued' ) && ! amp_is_canonical() ) {
			wp_register_style( 'kt-blocks-info', false );
			wp_enqueue_style( 'kt-blocks-info' );
			$info_css = $this->build_amp_css();
			$content = '<style id="kt-blocks-info" type="text/css">' . $info_css . '</style>' . $content;
		}
		if ( isset( $attributes['uniqueID'] ) ) {
			$unique_id = $attributes['uniqueID'];
			$style_id = 'kt-blocks' . esc_attr( $unique_id );
			if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
				if ( ! empty( $this->kadence_blocks_class ) && method_exists( $this->kadence_blocks_class, 'blocks_infobox_array' ) ) {
					$css = $this->kadence_blocks_class->blocks_infobox_array( $attributes, $unique_id );
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
		$css = ".kadence-info-box-image-intrisic {
    height: 0;
}
.kt-info-halign-center {
	text-align: center;
}
.kt-info-halign-center .kadence-info-box-image-inner-intrisic-container {
	margin: 0 auto;
}
.kt-info-halign-right {
	text-align: right;
}
.kt-info-halign-right .kadence-info-box-image-inner-intrisic-container {
	margin: 0 0 0 auto;
}
.kt-info-halign-left {
	text-align: left;
}
.kt-info-halign-left .kadence-info-box-image-inner-intrisic-container {
	margin: 0 auto 0 0;
}
.kt-blocks-info-box-media-align-top .kt-blocks-info-box-media {
	display: inline-block;
}
.kt-blocks-info-box-media-align-top .kt-infobox-textcontent {
	display: block;
}
.kt-blocks-info-box-text {
    color: #555555;
}
.wp-block-kadence-infobox .kt-blocks-info-box-text {
    margin-bottom: 0;
}
.kt-blocks-info-box-link-wrap:hover {
	background: #f2f2f2;
	border-color: #eeeeee;
}
.kt-blocks-info-box-media, .kt-blocks-info-box-link-wrap {
	border: 0 solid transparent;
	transition: all 0.3s cubic-bezier(.17,.67,.35,.95);
}
.kt-blocks-info-box-title, .kt-blocks-info-box-text, .kt-blocks-info-box-learnmore {
	transition: all 0.3s cubic-bezier(.17,.67,.35,.95);
}
.kt-blocks-info-box-media {
	border-color:#444444;
	color: #444444;
	padding: 10px;
	margin: 0 15px 0 15px;
}
.kt-blocks-info-box-link-wrap:hover .kt-blocks-info-box-media {
	border-color:#444444;
}
.kt-blocks-info-box-link-wrap {
	display: block;
	background:#f2f2f2;
	padding:20px;
	border-color: #eeeeee;
}
.kt-blocks-info-box-learnmore {
	border: 0 solid transparent;
	display: block;
	text-decoration: none;
}
.kt-blocks-info-box-learnmore:hover, .kt-blocks-info-box-learnmore:focus {
	text-decoration: none;
}
.wp-block-kadence-infobox .kt-blocks-info-box-learnmore-wrap {
    display: inline-block;
    width: auto;
}
.kt-blocks-info-box-media-align-left {
    display: flex;
    align-items: center;
    justify-content: flex-start;
}
.kt-blocks-info-box-media-align-right {
    display: flex;
    align-items: center;
	justify-content: flex-start;
	flex-direction: row-reverse;
}";

		return $css;
	}
}
