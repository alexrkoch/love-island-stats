<?php
/**
 * Product Includes Featured and gallery images
 *
 * @package Kadence AMP
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'kt_amp_product_image_gallery' ) ) {
	function kt_amp_product_image_gallery( $amp_template ) {
		/**
		* Product Includes Featured and gallery images
		*/
		$product = wc_get_product();
		$attachment_ids = $product->get_gallery_image_ids();
		echo '<div class="product-gallery">';
			if ( count( $attachment_ids ) ) {
				array_unshift( $attachment_ids, $product->get_image_id() );
				echo '<amp-carousel type="slides" layout="responsive" width="400" height="400">';
				foreach ($attachment_ids as $value) {
					$img = kt_amp_get_image( 600, 600, true, $value );
					echo '<amp-img src="' . esc_url( $img['src'] ) . '"
						' . $img['srcset'] . '
						width="400"
						height="400"
						layout="responsive"
						on="tap:gallery-lightbox"
						role="button"
						tabindex="0">
						</amp-img>';
				}
				echo '</amp-carousel>';
			} else {
				$amp_template->load_parts( array( 'featured-image' ) );
			}
			echo '<amp-image-lightbox id="gallery-lightbox"
			  layout="nodisplay">
			  <div on="tap:gallery-lightbox.close"
			    role="button"
			    tabindex="0">
			    <button class="ampstart-btn caps m2 close-gallery-button"
			      on="tap:gallery-lightbox.close"
			      role="button"
			      tabindex="0">';
					esc_html_e( 'Close', 'kadence-amp' );
				echo '</button>
			  </div>
			</amp-image-lightbox>
		</div>
	</div>';
		
	}
}
if ( ! function_exists( 'kt_amp_product_title' ) ) {
	function kt_amp_product_title( $amp_template ) {
		echo '<div class="amp-wp-product-header amp-wp-article-header">
			<h1 class="amp-product-title amp-wp-title flex-space-between full-flex">'.wp_kses_data( $amp_template->get( 'post_title' ) ).'</h1>
		</div>';
		
	}
}
if ( ! function_exists( 'kt_amp_product_rating' ) ) {
	function kt_amp_product_rating( $amp_template, $product ) {
		if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
			return;
		}
		echo '<div class="amp-rating">';
			echo $amp_template->get( 'product_amp_rating' );
		echo '</div>';
	}
}
if ( ! function_exists( 'kt_amp_product_price' ) ) {
	function kt_amp_product_price( $amp_template, $product ) {
		echo '<div class="amp-price-contain">';
		if ( $price_html = $product->get_price_html() ) : 
			echo '<p class="price-description amp-variable-product">
				<span class="price">'.$price_html.'</span>
			</p>';
		endif; 
		$availability = $product->get_availability(); 
		if( isset( $availability[ 'availability' ] ) && ! empty( $availability[ 'availability' ] ) ) { 
			$class = preg_replace('/[ \t]+/', '-', preg_replace('/\s*$^\s*/m', "\n", $availability[ 'availability' ]) );
					echo '<p class="stock '.esc_attr( $class ).'">'.wp_kses_post( $availability['availability'] ).'</p>';
		}
		echo '</div>';
		
	}
}
if ( ! function_exists( 'kt_amp_product_shortdescription' ) ) {
	function kt_amp_product_shortdescription( $amp_template, $product ) {
		echo '<div class="amp-product-shortdesc amp-wp-article-content">';
			echo $amp_template->get( 'product_amp_short_description' );
		echo '</div>';
	}
}
function kt_amp_sanitize_for_js( $string ) {
	$string = str_replace( '-', '_', $string );
	$string = str_replace( ' ', '_', $string );
	return sanitize_title( $string );
}
/**
 * Find matching product variation
 *
 * @param WC_Product $product
 * @param array $attributes
 * @return int Matching variation ID or 0.
 */
function kt_amp_get_product_variation_id( $product, $attributes ) {

	foreach ( $attributes as $key => $value ) {
		if ( strpos( $key, 'attribute_' ) === 0 ) {
			continue;
		}

		unset( $attributes[ $key ] );
		$attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
	}

	if ( class_exists( 'WC_Data_Store' ) ) {

		$data_store = WC_Data_Store::load( 'product' );
		return $data_store->find_matching_product_variation( $product, $attributes );

	} else {

		return $product->get_matching_variation( $attributes );

	}

}
if ( ! function_exists( 'kt_amp_product_add_to_cart' ) ) {
	function kt_amp_product_add_to_cart( $amp_template, $product ) {
		$kadence_amp = kadence_amp_options();
		if ( $product->is_in_stock() ) :
			if ( $product->is_type( 'variable' ) || ( 1 == $kadence_amp[ 'show_quantity' ] && ! $product->is_type( 'external' ) ) ) {
				$add_to_cart_url = wc_get_cart_url();
				if( $product->is_type( 'variable' ) ) {
						$default_attr = $product->get_default_attributes();
						$attributes_set = $product->get_variation_attributes(); 
						$output = $product->get_available_variations();
						// Figure out which attributes are defined in variations
						$defined = array();
						foreach ($output as $vkey => $variation) {
							$idstring = '';
							$i = 1;
							foreach ( $variation['attributes'] as $akey => $avalue ) {
								if ( empty( $avalue ) ) {
									$avalue = 'any';
								} else {
									$defined[] = $avalue;
								}
								if ( 1 == $i ) {
									$idstring .= $avalue;
								} else {
									$idstring .= '_'.$avalue;
								}
								$i++;
							}
							$output[$idstring] = $output[$vkey];
							unset($output[$vkey]);
						}
						?>
						<amp-state id="product">
							<script type="application/json">
								{<?php 
									$a = 1;
									foreach ( $attributes_set as $attribute_name => $options ) {
										if( 1 != $a ) { echo ','; }
										$att = str_replace( 'attribute', '', sanitize_title( $attribute_name ) );
										$any = isset( $default_attr[$att] ) ? $default_attr[$att] : 'any';
										echo '"set_' . kt_amp_sanitize_for_js( $attribute_name ) . '": "' . $any . '"';
										$a++;
									}
									echo ',"set_quantity":1,';
									echo '"display_price": '.wc_get_price_to_display( $product ).',';
									?>
									    "a": {
									    	<?php 
									    	$c = 1;
									    	foreach ( $attributes_set as $attribute_name => $options ) {
									    		if( 1 != $c ) { echo ','; }
									    		echo '"' . kt_amp_sanitize_for_js( $attribute_name ) . '": {';
									    		$t = 1;
												foreach ( $options as $option ) {
													if( 1 != $t ) { echo ','; }
													echo '"'.$option.'":';
													if (in_array($option, $defined)){ echo '"'.$option.'"';} else {echo '"any"';}
													$t++;
												}
												$c++;
												echo '}';
									    	} ?>
									    },
										"v_a" : 
										<?php echo wp_json_encode( $output ); ?>
								}
							</script>
						</amp-state>
			<?php } else { ?>
					<amp-state id="product">
							<script type="application/json">
								{<?php 
									echo '"set_quantity":' . apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ) . ',';
									echo '"display_price": ' . wc_get_price_to_display( $product );
									?>
								}
							</script>
						</amp-state>

			<?php } ?>
					<form action="<?php echo esc_attr( $add_to_cart_url ); ?>" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" method="get" target="_blank" class="kt-amp-product-form full-flex flex-space-between flex flex-wrap" id="order">
							<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
						<?php if( $product->is_type( 'variable' ) ) { ?>
							<div class="amp-product-options full-flex">
								<?php foreach ( $attributes_set as $attribute_name => $options ): ?>
									<div class="amp-atribute-select-block full-flex flex-space-between flex">
										<label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?>:</label>
										<?php $att = sanitize_title( $attribute_name ); ?>
											<select name="attribute_<?php echo sanitize_title( $attribute_name ); ?>" class="amp-attribute-select" on="change:AMP.setState({ <?php echo  "product: { set_" .kt_amp_sanitize_for_js( $attribute_name ) . " : (product.a." . kt_amp_sanitize_for_js( $attribute_name ) . "[event.value]) }";?> })" >
											<option value=""><?php echo  __( 'Choose an option', 'kadence-amp' ); ?></option>
											<?php foreach ( $options as $option ): ?>
												<?php
												$term_obj = get_term_by( 'slug', $option, $attribute_name );
												if ( ! empty( $term_obj ) && is_object( $term_obj ) ) {
													$option_name = $term_obj->name;
												} else {
													$option_name = $option;
												}
												?>
												<option value="<?php echo esc_attr( $option ); ?>" <?php if ( isset( $default_attr[$att] ) && $default_attr[$att] == $option ) {echo 'selected';}?> > <?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $option_name ) ); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								<?php endforeach; ?>
							</div>
							<?php } ?>
							<div class="amp-product-action full-flex flex flex-space-between flex-align-center">
							<?php if( $product->is_type( 'variable' ) ) { ?>
								<div [class]="abs(product.v_a[<?php
									$b = 1; 
									foreach ( $attributes_set as $attribute_name => $options ) {
										if ( 1 == $b ) {
											echo "(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";

										} else {
											echo "+'_'+(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";
										}
										$b++;
									} ?>].display_price) != '0' ? 'amp_price_show amp_var_price':'amp_price_hide amp_var_price'" class="amp_price_hide amp_var_price">
									<span class="amp-price-currency"><?php echo get_woocommerce_currency_symbol(); ?> </span>
									<span class="amp-price-number" [text]="abs(product.v_a[<?php
									$b = 1; 
									foreach ( $attributes_set as $attribute_name => $options ) {
										if ( 1 == $b ) {
											echo "(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";

										} else {
											echo "+'_'+(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";
										}
										$b++;
									} ?>].display_price)" ></span>
									</div>
								
								
							<?php }
							if ( 1 == $kadence_amp[ 'show_quantity' ] && true != $product->get_sold_individually() ) : ?>
								<?php $step = apply_filters( 'woocommerce_quantity_input_step', 1, $product );
										$defaults = array(
											'input_id'     => uniqid( 'quantity_' ),
											'input_name'   => 'quantity',
											'input_value'  => '1',
											'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
											'max_value'    => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
											'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
											'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
											'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
											'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
											'product_name' => $product ? $product->get_title() : '',
										);

										$args = apply_filters( 'woocommerce_quantity_input_args', $defaults, $product );
								 ?>
								<div class="amp-product-quantity">
									<span class="amp-qty-minus" tabindex="2" role="click" on="<?php echo 'tap:AMP.setState({product:{set_quantity:(product.set_quantity == 1 ? 2 : product.set_quantity)-' . $args['step'] . ' } })' ;?>">-</span>
									<span class="amp-qty" [text]="abs(product.set_quantity)"><?php echo apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ); ?></span>
									<span class="amp-qty-plus" tabindex="2" role="click" on="<?php echo 'tap:AMP.setState({product:{set_quantity:((product.set_quantity)+' . $args['step'] . ') } })'; ?>">+</span>
								</div>
								<input type="hidden" name="quantity" [value]="abs(product.set_quantity)" value="<?php echo apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ); ?>" />
							<?php endif; ?>
							<div class="kt-amp-add-to-cart">
								<?php if( $product->is_type( 'variable' ) ) { ?>
									<?php
									$variation_id = kt_amp_get_product_variation_id( $product, $default_attr );
									?>

									<input type="hidden" name="variation_id" value="<?php echo esc_attr( $variation_id ); ?>" [value]="abs(product.v_a[<?php
									$b = 1;
									foreach ( $attributes_set as $attribute_name => $options ) {
										if ( 1 == $b ) {
											echo "(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";

										} else {
											echo "+'_'+(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";
										}
										$b++;
									} ?>].variation_id)">
									<div class="hide_out_of_stock" [class]="((product.v_a[<?php
									$b = 1; 
									foreach ( $attributes_set as $attribute_name => $options ) {
										if ( 1 == $b ) {
											echo "(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";

										} else {
											echo "+'_'+(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";
										}
										$b++;
									} ?>].is_in_stock) != false ) && ((product.v_a[<?php
									$b = 1; 
									foreach ( $attributes_set as $attribute_name => $options ) {
										if ( 1 == $b ) {
											echo "(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";

										} else {
											echo "+'_'+(product.set_" . kt_amp_sanitize_for_js( $attribute_name ) . ")";
										}
										$b++;
									} ?>].is_purchasable) != false ) ? 'hide_out_of_stock':'show_out_of_stock'">
										<?php echo apply_filters('kt_amp_out_of_stock', __( 'Out of stock', 'kadence-amp' ) ); ?>
									</div>
								<?php } ?>
								<input class="amp-button btn" type="submit" value="<?php echo esc_attr( $product->single_add_to_cart_text() ); ?>">
							</div>
						</div>
					</form>
			<?php
			} elseif ( $product->is_type( 'external' ) ) {
				$add_to_cart_url = $product->add_to_cart_url();
				do_action( 'kt_amp_before_add_to_cart_button' );
				?>
				<div class="kt-amp-add-to-cart">
					<a class="amp-button btn" href="<?php echo esc_url( $add_to_cart_url ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>
				</div>
				<?php
				do_action( 'kt_amp_after_add_to_cart_button' );
			} else {
				$add_to_cart_url = wc_get_cart_url();
				$add_to_cart_url = add_query_arg( 'add-to-cart', $product->get_id(), $add_to_cart_url ); 
				do_action( 'kt_amp_before_add_to_cart_button' ); ?>
				<div class="kt-amp-add-to-cart">
					<a class="amp-button btn" href="<?php echo esc_url( $add_to_cart_url ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>
				</div>
				<?php do_action( 'kt_amp_after_add_to_cart_button' );
			}
		endif; 
	}
}
if ( ! function_exists( 'kt_amp_product_meta' ) ) {
	function kt_amp_product_meta( $amp_template, $product ) {
		echo '<div class="amp-product-meta">';
			if ( wc_product_sku_enabled() && $product->get_sku() ) {
				echo '<small class="amp-sku">'.__( 'SKU', 'kadence-amp' ).': '.$product->get_sku().'</small>';
			}

			echo wc_get_product_category_list( $product->get_id(), ', ', '<small class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</small>' ); 

			echo wc_get_product_tag_list( $product->get_id(), ', ', '<small class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</small>' );

		echo '</div>';
	}
}
if ( ! function_exists( 'kt_amp_product_tabs' ) ) {
	function kt_amp_product_tabs( $amp_template, $product ) {
		$amp_template->load_parts( array( 'product-tabs' ) );
	}
}
if ( ! function_exists( 'kt_amp_product_related' ) ) {
	function kt_amp_product_related( $amp_template, $product ) {

		$related = $amp_template->get( 'product_amp_related' );
		if ( $related ) : 
		?>
		<div class="amp-related-products clearfix">
			<h2><?php esc_html_e( 'Related Products', 'kadence-amp' ); ?></h2>
			<div class="amp-kt-products-grid clearfix">
				<?php 
				echo $related;
				?>
			</div>
		</div>
		<?php
		endif;
	}
}
add_action('amp_wc_product_loop', 'kt_amp_product_loop');
if ( ! function_exists( 'kt_amp_product_loop' ) ) {
	function kt_amp_product_loop( $product_id ) {
		
		$rproduct = wc_get_product( $product_id );
		$img = kt_amp_get_image( '300', '300', true, get_post_thumbnail_id( $product_id ) );
		$link = kt_amp_url( $product_id );
		?>
		<div class="amp-kt-product-item">
			<a class="amp-product-item-link" href="<?php echo esc_url( $link ); ?>">
				<?php if ( isset( $img['src'] ) ): ?>
					<amp-img 
					         src="<?php echo $img['src']; ?>"
					         alt="<?php esc_attr_e( $rproduct->get_title() ); ?>"
					         width="<?php echo $img['width']; ?>"
					         height="<?php echo $img['height']; ?>"
					         <?php echo $img['srcset']; ?> >
					</amp-img>
				<?php endif; ?>
				<p class="amp-product-item-title"><?php echo $rproduct->get_title(); ?></p>
			</a>
			<?php
			echo '<div class="amp-rating">';
				$rating_count = $rproduct->get_rating_count();
				$average      = $rproduct->get_average_rating();

				echo wc_get_rating_html( $average, $rating_count );
			echo '</div>';
				?>
				<p class="amp-product-item-price"><?php echo $rproduct->get_price_html(); ?></p>
		</div>
		<?php
	}
}