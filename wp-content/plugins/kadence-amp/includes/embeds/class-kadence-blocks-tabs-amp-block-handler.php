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
class Kadence_Blocks_Tabs_AMP_Block_Handler extends AMP_Base_Embed_Handler {

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
	public $block_name = 'kadence/tabs';

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
	public function changeName($node, $name) {
		    $childnodes = array();
		    foreach ($node->childNodes as $child){
		        $childnodes[] = $child;
		    }
		    $newnode = $node->ownerDocument->createElement($name);
		    foreach ($childnodes as $child){
		        $child2 = $node->ownerDocument->importNode($child, true);
		        $newnode->appendChild($child2);
		    }
		    foreach ($node->attributes as $attrName => $attrNode) {
		        $attrName = $attrNode->nodeName;
		        $attrValue = $attrNode->nodeValue;
		        $newnode->setAttribute($attrName, $attrValue);
		    }
		    $node->parentNode->replaceChild($newnode, $node);
		    return $newnode;
		}
	/**
	 * Render Tab Block CSS Inline
	 *
	 * @param array  $attributes the blocks attribtues.
	 * @param string $content the blocks content.
	 */
	public function render( $attributes, $content ) {
		if ( isset( $attributes['uniqueID'] ) ) {
			$unique_id = $attributes['uniqueID'];
		} else {
			$unique_id = wp_rand( 3, 9000 );
		}
		$doc = new DOMDocument();
		libxml_use_internal_errors( true );
		$content = mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' );
		$doc->loadHTML(
			// loadHTML expects ISO-8859-1, so we need to convert the post content to
			// that format. We use htmlentities to encode Unicode characters not
			// supported by ISO-8859-1 as HTML entities. However, this function also
			// converts all special characters like < or > to HTML entities, so we use
			// htmlspecialchars_decode to decode them.
			htmlspecialchars_decode(
				utf8_decode(
					htmlentities(
						$content,
						ENT_COMPAT,
						'UTF-8',
						false
					)
				),
				ENT_COMPAT
			)
		);
		libxml_use_internal_errors( false );

		//@$doc->loadHTML( $content ); //supress parsing errors with @

		$lists = $doc->getElementsByTagName( 'ul' );
		$active_tab = 1;
		foreach ( $lists as $list ) {
			if ( $list->getAttribute('class') == 'kt-tabs-title-list' ) {
				$list = $this->changeName($list, 'amp-selector');
				$list->setAttribute( 'on', 'select:tabs-'. $unique_id . '.toggle(index=event.targetOption, value=true)' );
				$items = $list->getElementsByTagName( 'li' );
				$i = 1;
				foreach ( $items as $item ) {
					//$number = $item->getAttribute( 'data-tab' );
					$item->setAttribute( 'option', $i - 1 );
					$item->setAttribute( 'role', 'tab' );
					$item->setAttribute( 'id', 'sample3-tab' . $i );
					$item->setAttribute( 'aria-controls', 'sample3-tabpanel' . $i );
					$class = $item->getAttribute('class');
					if ( strpos( $class, 'kt-tab-title-active' ) !== false ) {
						$item->setAttribute( 'selected', true );
						$active_tab = $i;
					}
					$i ++;
				}
			}
		}
		$divs = $doc->getElementsByTagName( 'div' );
		foreach ( $divs as $div ) {
			if ( $div->getAttribute('class') == 'kt-tabs-content-wrap' ) {
				$div = $this->changeName($div, 'amp-selector');
				$div->setAttribute( 'id', 'tabs-'. $unique_id );
				$child_divs = $div->getElementsByTagName( 'div' );
				$i = 1;
				foreach ( $child_divs as $child_div ) {
					$class = $child_div->getAttribute('class');
					if ( strpos( $class, 'wp-block-kadence-tab' ) !== false ) {
						$child_div->setAttribute( 'role', 'tabpanel' );
						$child_div->setAttribute( 'option', '' );
						$child_div->setAttribute( 'id', 'sample3-tabpanel' . $i );
						$child_div->setAttribute( 'aria-labelledby', 'sample3-tab' . $i );
						if ( $i === $active_tab ) {
							$child_div->setAttribute( 'selected', true );
						}
						$i ++;
					}
				}
			}
		}
		$content = $doc->saveHTML();
		if ( ! wp_style_is( 'kadence-blocks-tabs', 'enqueued' ) && ! amp_is_canonical() ) {
			wp_register_style( 'kadence-blocks-tabs', false );
			wp_enqueue_style( 'kadence-blocks-tabs' );
			$css = $this->build_amp_css();
			$css = str_replace( ".kt-tab-title-active", "[selected]", $css );
			$content = '<style id="kadence-blocks-tabs" type="text/css">' . $css . '</style>' . $content;
		}
		if ( isset( $attributes['uniqueID'] ) ) {
			$unique_id = $attributes['uniqueID'];
			$style_id = 'kt-blocks' . esc_attr( $unique_id );
			if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
				if ( ! empty( $this->kadence_blocks_class ) && method_exists( $this->kadence_blocks_class, 'blocks_tabs_array' ) ) {
					$css = $this->kadence_blocks_class->blocks_tabs_array( $attributes, $unique_id );
					$css = str_replace( ".kt-tab-title-active", "[selected]", $css );
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
		$css = "amp-selector.kt-tabs-content-wrap [role=tabpanel] {
      display: none;
    }
    amp-selector.kt-tabs-content-wrap [role=tabpanel][selected] {
      outline: none;
      display: block;
    }.wp-block-kadence-tabs .kt-tabs-title-list{margin:0;padding:0;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;list-style:none}.wp-block-kadence-tabs .kt-tabs-title-list li{margin:0 4px -1px 0;cursor:pointer;list-style:none; outline:0;}.wp-block-kadence-tabs .kt-tabs-title-list li a.kt-tab-title{padding:8px 16px;display:-ms-flexbox;display:flex;color:#444;-ms-flex-align:center;align-items:center;border-style:solid;border-color:transparent;border-width:1px 1px 0 1px;border-top-left-radius:4px;border-top-right-radius:4px;text-decoration:none;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out}.wp-block-kadence-tabs .kt-tabs-title-list li a.kt-tab-title:focus{outline:0;text-decoration:none}.wp-block-kadence-tabs .kt-tabs-title-list li a.kt-tab-title:hover{text-decoration:none}.wp-block-kadence-tabs .kt-tabs-title-list li.kt-tab-title-active{z-index:4;text-decoration:none;position:relative}.kt-tabs-icon-side-top .kt-tab-title{-ms-flex-direction:column;flex-direction:column}.kt-tabs-accordion-title.kt-tabs-icon-side-top .kt-tab-title{-ms-flex-align:start;align-items:flex-start}.kt-tabs-accordion-title .kt-tab-title{padding:8px 16px;display:-ms-flexbox;display:flex;color:#444;-ms-flex-align:center;align-items:center;border-style:solid;border-color:transparent;border-width:1px 1px 0 1px;border-top-left-radius:4px;border-top-right-radius:4px;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out}.kt-tabs-accordion-title.kt-tab-title-active{z-index:4}.kt-tabs-accordion-title.kt-tab-title-active .kt-tab-title{background-color:#fff;border-color:#dee2e6}.wp-block-kadence-tabs .kt-tab-inner-content-inner p:last-child{margin-bottom:0}.kt-tab-alignment-center>.kt-tabs-title-list,.kt-tab-alignment-center>.kt-tabs-content-wrap>.kt-tabs-accordion-title a{-ms-flex-pack:center;justify-content:center}.kt-tab-alignment-right>.kt-tabs-title-list,.kt-tab-alignment-right>.kt-tabs-content-wrap>.kt-tabs-accordion-title a{-ms-flex-pack:end;justify-content:flex-end}.kt-tabs-content-wrap:before,.kt-tabs-content-wrap:after{content:'';clear:both;display:table}.kt-tabs-content-wrap{position:relative}.kt-tabs-wrap{margin:0 auto}.kt-tabs-wrap .wp-block-kadence-tab{border:1px solid #dee2e6;padding:20px;text-align:left}.kb-tab-titles-wrap{display:-ms-inline-flexbox;display:inline-flex;-ms-flex-direction:column;flex-direction:column}.kt-title-sub-text{font-size:14px;line-height:24px}.kt-tabs-layout-vtabs:after,.kt-tabs-wrap:after{clear:both;display:table;content:''}.kt-tabs-layout-vtabs>.kt-tabs-title-list{float:left;width:30%;-ms-flex-direction:column;flex-direction:column}.kt-tabs-layout-vtabs>.kt-tabs-title-list li{margin:0 -1px 4px 0}.kt-tabs-layout-vtabs>.kt-tabs-title-list li .kt-tab-title{border-width:1px 0px 1px 1px;border-top-left-radius:0;border-top-right-radius:0}.kt-tabs-layout-vtabs>.kt-tabs-title-list li.kt-tabs-icon-side-top .kt-tab-title{-ms-flex-align:start;align-items:flex-start}.kt-tabs-layout-vtabs>.kt-tabs-content-wrap{float:left;width:70%}.kt-tabs-layout-vtabs.kt-tab-alignment-left>.kt-tabs-title-list li .kt-tab-title{-ms-flex-align:center;align-items:center;-ms-flex-pack:start;justify-content:flex-start}.kt-tabs-layout-vtabs.kt-tab-alignment-center>.kt-tabs-title-list{-ms-flex-pack:start;justify-content:flex-start}.kt-tabs-layout-vtabs.kt-tab-alignment-center>.kt-tabs-title-list li{text-align:center}.kt-tabs-layout-vtabs.kt-tab-alignment-center>.kt-tabs-title-list li .kt-tab-title{-ms-flex-pack:center;justify-content:center;-ms-flex-align:center;align-items:center}.kt-tabs-layout-vtabs.kt-tab-alignment-center>.kt-tabs-title-list li .kb-tab-titles-wrap{-ms-flex-align:center;align-items:center}.kt-tabs-layout-vtabs.kt-tab-alignment-right>.kt-tabs-title-list{-ms-flex-pack:start;justify-content:flex-start}.kt-tabs-layout-vtabs.kt-tab-alignment-right>.kt-tabs-title-list li{text-align:right}.kt-tabs-layout-vtabs.kt-tab-alignment-right>.kt-tabs-title-list li .kt-tab-title{-ms-flex-pack:end;justify-content:flex-end;-ms-flex-align:center;align-items:center}.kt-tabs-layout-vtabs.kt-tab-alignment-right>.kt-tabs-title-list li .kb-tab-titles-wrap{-ms-flex-align:end;align-items:flex-end}.kt-tabs-svg-show-only .kt-button-text,.kt-tabs-svg-show-only .kb-tab-titles-wrap{display:none}.kt-tabs-accordion-title a{padding:8px 16px;display:-ms-flexbox;display:flex;color:#444;-ms-flex-align:center;align-items:center;border-style:solid;border-color:transparent;border-width:1px 1px 0 1px}.wp-block-kadence-tabs .kt-tabs-content-wrap .kt-tabs-accordion-title .kt-tab-title{border-radius:0}.kt-tabs-svg-show-only .kt-title-text{display:none}.kt-title-svg-side-left{padding-right:5px}.kt-title-svg-side-right{padding-left:5px}.kt-tabs-svg-show-only .kt-title-svg-side-right{padding-left:0px}.kt-tabs-svg-show-only .kt-title-svg-side-left{padding-right:0px}.kt-tabs-accordion-title{display:none}@media (min-width: 767px) and (max-width: 1024px){.kt-tabs-tablet-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-title-list{float:none;width:100%;-ms-flex-direction:row;flex-direction:row}.kt-tabs-tablet-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-title-list li{margin:0 4px -1px 0}.kt-tabs-tablet-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-title-list li .kt-tab-title{border-width:1px 1px 0px 1px;border-top-left-radius:4px;border-top-right-radius:4px}.kt-tabs-tablet-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-title-list li.kt-tabs-icon-side-top .kt-tab-title{-ms-flex-align:center;align-items:center}.kt-tabs-tablet-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-content-wrap{float:none;width:100%}.kt-tabs-tablet-layout-accordion>.kt-tabs-title-list{display:none}.kt-tabs-tablet-layout-accordion>.kt-tabs-content-wrap>.kt-tabs-accordion-title{display:block}.kt-tabs-tablet-layout-accordion>.kt-tabs-content-wrap{float:none;width:100%}.kt-tabs-tablet-layout-vtabs .kt-tabs-title-list{float:left;width:30%;-ms-flex-direction:column;flex-direction:column}.kt-tabs-tablet-layout-vtabs .kt-tabs-title-list li{margin:0 -1px 4px 0}.kt-tabs-tablet-layout-vtabs .kt-tabs-title-list li .kt-tab-title{border-width:1px 0px 1px 1px;border-top-left-radius:0;border-top-right-radius:0}.kt-tabs-tablet-layout-vtabs .kt-tabs-title-list li.kt-tabs-icon-side-top .kt-tab-title{-ms-flex-align:start;align-items:flex-start}.kt-tabs-tablet-layout-vtabs .kt-tabs-content-wrap{float:left;width:70%}.kt-tabs-tablet-layout-vtabs.kt-tab-alignment-center .kt-tabs-title-list{-ms-flex-pack:start;justify-content:flex-start}.kt-tabs-tablet-layout-vtabs.kt-tab-alignment-center .kt-tabs-title-list li{text-align:center}.kt-tabs-tablet-layout-vtabs.kt-tab-alignment-center .kt-tabs-title-list li .kt-tab-title{-ms-flex-pack:center;justify-content:center}.kt-tabs-tablet-layout-vtabs.kt-tab-alignment-right .kt-tabs-title-list{-ms-flex-pack:start;justify-content:flex-start}.kt-tabs-tablet-layout-vtabs.kt-tab-alignment-right .kt-tabs-title-list li{text-align:right}.kt-tabs-tablet-layout-vtabs.kt-tab-alignment-right .kt-tabs-title-list li .kt-tab-title{-ms-flex-pack:end;justify-content:flex-end}}@media (max-width: 767px){.kt-tabs-mobile-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-title-list{float:none;width:100%;-ms-flex-direction:row;flex-direction:row}.kt-tabs-mobile-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-title-list li{margin:0 4px -1px 0}.kt-tabs-mobile-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-title-list li .kt-tab-title{border-width:1px 1px 0px 1px;border-top-left-radius:4px;border-top-right-radius:4px}.kt-tabs-mobile-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-title-list li.kt-tabs-icon-side-top .kt-tab-title{-ms-flex-align:center;align-items:center}.kt-tabs-mobile-layout-tabs.kt-tabs-layout-vtabs .kt-tabs-content-wrap{float:none;width:100%}.kt-tabs-mobile-layout-accordion>.kt-tabs-title-list{display:none}.kt-tabs-mobile-layout-accordion>.kt-tabs-content-wrap>.kt-tabs-accordion-title{display:block}.kt-tabs-mobile-layout-accordion>.kt-tabs-content-wrap{float:none;width:100%}.kt-tabs-mobile-layout-vtabs .kt-tabs-title-list{float:left;width:30%;-ms-flex-direction:column;flex-direction:column}.kt-tabs-mobile-layout-vtabs .kt-tabs-title-list li{margin:0 -1px 4px 0}.kt-tabs-mobile-layout-vtabs .kt-tabs-title-list li .kt-tab-title{border-width:1px 0px 1px 1px;border-top-left-radius:0;border-top-right-radius:0}.kt-tabs-mobile-layout-vtabs .kt-tabs-title-list li.kt-tabs-icon-side-top .kt-tab-title{-ms-flex-align:start;align-items:flex-start}.kt-tabs-mobile-layout-vtabs .kt-tabs-content-wrap{float:left;width:70%}.kt-tabs-mobile-layout-vtabs.kt-tab-alignment-center .kt-tabs-title-list{-ms-flex-pack:start;justify-content:flex-start}.kt-tabs-mobile-layout-vtabs.kt-tab-alignment-center .kt-tabs-title-list li{text-align:center}.kt-tabs-mobile-layout-vtabs.kt-tab-alignment-center .kt-tabs-title-list li .kt-tab-title{-ms-flex-pack:center;justify-content:center}.kt-tabs-mobile-layout-vtabs.kt-tab-alignment-right .kt-tabs-title-list{-ms-flex-pack:start;justify-content:flex-start}.kt-tabs-mobile-layout-vtabs.kt-tab-alignment-right .kt-tabs-title-list li{text-align:right}.kt-tabs-mobile-layout-vtabs.kt-tab-alignment-right .kt-tabs-title-list li .kt-tab-title{-ms-flex-pack:end;justify-content:flex-end}}ul.kt-tabs-title-list.kb-tab-title-columns-8>li{-ms-flex:0 1 12.5%;flex:0 1 12.5%}ul.kt-tabs-title-list.kb-tab-title-columns-7>li{-ms-flex:0 1 14.28%;flex:0 1 14.28%}ul.kt-tabs-title-list.kb-tab-title-columns-6>li{-ms-flex:0 1 16.67%;flex:0 1 16.67%}ul.kt-tabs-title-list.kb-tab-title-columns-5>li{-ms-flex:0 1 20%;flex:0 1 20%}ul.kt-tabs-title-list.kb-tab-title-columns-4>li{-ms-flex:0 1 25%;flex:0 1 25%}ul.kt-tabs-title-list.kb-tab-title-columns-3>li{-ms-flex:0 1 33.33%;flex:0 1 33.33%}ul.kt-tabs-title-list.kb-tab-title-columns-2>li{-ms-flex:0 1 50%;flex:0 1 50%}ul.kt-tabs-title-list.kb-tab-title-columns-1>li{-ms-flex:0 1 100%;flex:0 1 100%}ul.kt-tabs-title-list.kb-tab-title-columns-1>li>.kt-tab-title{margin-right:0px !important}ul.kt-tabs-title-list.kb-tabs-list-columns>li:last-child>.kt-tab-title{margin-right:0px !important}ul.kt-tabs-title-list.kb-tabs-list-columns .kt-tab-title{-ms-flex-pack:center;justify-content:center;text-align:center}ul.kt-tabs-title-list.kb-tabs-list-columns{word-break:break-word}.kt-tab-alignment-center ul.kt-tabs-title-list.kb-tabs-list-columns .kb-tab-titles-wrap{-ms-flex-align:center;align-items:center}.kt-tab-alignment-right ul.kt-tabs-title-list.kb-tabs-list-columns .kb-tab-titles-wrap{-ms-flex-align:end;align-items:flex-end}";

		return $css;
	}
}
