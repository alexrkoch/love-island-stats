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
class Kadence_Blocks_Row_AMP_Block_Handler extends AMP_Base_Embed_Handler {

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
	public $block_name = 'kadence/rowlayout';

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
		if ( ! wp_style_is( 'kt-blocks-rowlayout', 'enqueued' ) ) {
			wp_register_style( 'kt-blocks-rowlayout', false );
			wp_enqueue_style( 'kt-blocks-rowlayout' );
			$row_css = $this->build_amp_css();
			$content = '<style id="kt-blocks-rowlayout" type="text/css">' . $row_css . '</style>' . $content;
		}
		if ( isset( $attributes['uniqueID'] ) ) {
			$unique_id = $attributes['uniqueID'];
			$style_id = 'kt-blocks' . esc_attr( $unique_id );
			if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
				if ( ! empty( $this->kadence_blocks_class ) && method_exists( $this->kadence_blocks_class, 'row_layout_array_css' ) ) {
					$css = $this->kadence_blocks_class->row_layout_array_css( $attributes, $unique_id );
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
		$css = ".kt-row-layout-inner {
		    position: relative;
		}
		.kt-row-column-wrap {
		    padding: 25px 0 25px 0;
		    position: relative;
		    z-index: 10;
		}
		.kt-row-has-bg > .kt-row-column-wrap, .alignfull .kt-row-column-wrap {
		    padding-left: 15px;
		    padding-right: 15px;
		}
		.wp-block-kadence-rowlayout:before {
		    clear: both;
		    content: '';
		    display: table;
		}
		.kt-row-layout-overlay {
		    top: 0;
		    left: 0;
		    position: absolute;
		    opacity: 0.3;
		    height: 100%;
		    width: 100%;
		    z-index: 0;
		}
		.kt-inside-inner-col {
			border: 0 solid transparent;
		}
		.kt-row-valign-middle > .wp-block-kadence-column {
		    justify-content: center;
		}
		.kt-row-valign-bottom > .wp-block-kadence-column {
		    justify-content: flex-end;
		}
		.kt-row-layout-bottom-sep {
			position: absolute;
			height: 100px;
			bottom: 0;
			left: 0;
			overflow: hidden;
			right: 0;
			z-index: 1;
		}
		.kt-row-layout-bottom-sep svg {
			position: absolute;
			bottom: 0px;
			left: 50%;
			transform: translateX(-50%);
			width: 100.2%;
			height: 100%;
			display: block;
		}
		.kt-row-layout-top-sep {
			position: absolute;
			height: 100px;
			top: 0;
			left: 0;
			overflow: hidden;
			right: 0;
			z-index: 1;
		}
		.kt-row-layout-top-sep svg {
			position: absolute;
			top: 0px;
		    left: 50%;
			transform: translateX(-50%) rotate(180deg);
			width: 100.2%;
			height: 100%;
			display: block;
		}.kt-mobile-layout-row.kt-v-gutter-default > .wp-block-kadence-column {
	        margin-bottom: 30px;
	    }
	    .kt-mobile-layout-row.kt-v-gutter-skinny > .wp-block-kadence-column {
	        margin-bottom: 10px;
	    }
	    .kt-mobile-layout-row.kt-v-gutter-narrow > .wp-block-kadence-column {
	        margin-bottom: 20px;
	    }
	    .kt-mobile-layout-row.kt-v-gutter-wide > .wp-block-kadence-column {
	        margin-bottom: 40px;
	    }
	    .kt-mobile-layout-row.kt-v-gutter-wider > .wp-block-kadence-column {
	        margin-bottom: 60px;
	    }
	    .kt-mobile-layout-row.kt-v-gutter-widest > .wp-block-kadence-column {
	        margin-bottom: 80px;
	    }
	    .kt-mobile-layout-row:not(.kt-v-gutter-none) > .wp-block-kadence-column:last-child {
	        margin-bottom: 0px;
		}";

		return $css;
	}
}
