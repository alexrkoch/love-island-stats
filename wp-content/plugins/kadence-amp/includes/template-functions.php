<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function kt_amp_template_init_hooks() {
	// Head Fonts.
	add_action( 'amp_post_template_head', 'kt_amp_fonts' );
	// Header Logo.
	add_action( 'kt_amp_header_content', 'kt_amp_header_logo', 10 );
	// Header Menu Icon.
	add_action( 'kt_amp_header_content', 'kt_amp_header_menu', 20 );
	// Header sidebar Menu.
	add_action( 'amp_post_template_footer', 'kt_amp_sidebar', 10 );
	// Scripts.
	add_filter( 'amp_post_template_data', 'kt_amp_add_amp_script', 20);

	add_action( 'kt_amp_build_product', 'kt_amp_build_product', 10, 2);

	add_action( 'kt_amp_build_post', 'kt_amp_build_post', 10 );

	add_action( 'kt_amp_build_page', 'kt_amp_build_page', 10 );

	add_action( 'kt_amp_build_singular', 'kt_amp_build_singular', 10 );

	add_action( 'kt_amp_build_footer', 'kt_amp_footer', 10 );

	add_action( 'kt_amp_build_footer', 'kt_amp_footer_link', 20 );

	add_action( 'kt_amp_build_footer', 'kt_amp_footer_original', 30 );

	add_filter( 'amp_post_template_analytics', 'kt_amp_add_google_analytics' );
}
if ( ! function_exists( 'kt_amp_add_google_analytics' ) ) {

	function kt_amp_add_google_analytics( $analytics ) {
		$kadence_amp = kadence_amp_options();
		if ( isset( $kadence_amp['google_analytics'] ) && ! empty( $kadence_amp['google_analytics'] ) ) {
			$analytics[ $kadence_amp['google_analytics'] ] = array(
				'type' => 'googleanalytics',
				'attributes' => array(),
				'config_data' => array(
					'vars' => array(
						'account' => $kadence_amp[ 'google_analytics' ]
					),
					'triggers' => array(
						'trackPageview' => array(
							'on' => 'visible',
							'request' => 'pageview',
						),
					),
				),
			);
		}

		return $analytics;
	}
}

if ( ! function_exists( 'kt_amp_footer' ) ) {
	function kt_amp_footer() {
		$kadence_amp = kadence_amp_options();
		if ( isset( $kadence_amp[ 'footer_text' ] ) && ! empty( $kadence_amp[ 'footer_text' ] ) ) {
			echo '<div class="amp-footer-content">';
			$footertext = $kadence_amp[ 'footer_text' ];
			$footertext = str_replace('[copyright]','&copy;',$footertext);
			$footertext = str_replace('[the-year]',date('Y'),$footertext);
			$footertext = str_replace('[site-name]',get_bloginfo('name'),$footertext);

			echo do_shortcode( $footertext );
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'kt_amp_footer_link' ) ) {
	function kt_amp_footer_link() {
		$kadence_amp = kadence_amp_options();
		if ( isset( $kadence_amp[ 'show_back_to_top' ] ) && 1 == $kadence_amp[ 'show_back_to_top' ] ) {
			if ( empty( $kadence_amp[ 'show_original_text' ] ) ) {
				$kadence_amp[ 'back_to_top_text' ] = __('Back to top', 'kadence-amp' );
			}
			echo '<a href="#top" class="back-to-top">'.esc_html( $kadence_amp[ 'back_to_top_text' ] ).'</a>';
		}
	}
}
if ( ! function_exists( 'kt_amp_footer_original' ) ) {
	function kt_amp_footer_original( $amp_template ) {
		$kadence_amp = kadence_amp_options();
		if ( isset( $kadence_amp[ 'show_original' ] ) && 1 == $kadence_amp[ 'show_original' ] ) {
			if ( empty( $kadence_amp[ 'show_original_text' ] ) ) {
				$kadence_amp[ 'show_original_text' ] = __('View original version', 'kadence-amp' );
			}
			echo '<div class="show_original"><a href="'.get_permalink().'" class="show_original_link">'.esc_html( $kadence_amp[ 'show_original_text' ] ).'</a></div>';
		}
	}
}
/**
 * Build Amp Singular
 *
 * @param object $amp_template the template object.
 */
function kt_amp_build_singular( $amp_template ) {
	$kadence_amp = kadence_amp_options();
	if ( isset( $kadence_amp['singular_layout'] ) ) {
		$layout = array();
		foreach ( $kadence_amp['singular_layout'] as $key => $value ) {
			if ( 1 == $value ) {
				$layout[ $key ] = $value;
			}
		}
	} else {
		// Default layout show baisc setup
		$layout = array(
			"title"  			=> 1,
			"content" 			=> 1,
		);
	}
	if ($layout):

		foreach ($layout as $key => $value ) {

			switch( $key ) {

				case 'ad_one':
					kt_amp_ad_one( $amp_template );
				break;
				case 'breadcrumbs':
					kt_amp_breadcrumbs( $amp_template );
				break;
				case 'original_button':
					kt_amp_original_button( $amp_template );
				break;
				case 'feature':
					kt_amp_post_feature( $amp_template );
				break;
				case 'title':
					kt_amp_post_title( $amp_template );
				break;
				case 'content':
					kt_amp_post_content( $amp_template );
				break;
				case 'share':
					kt_amp_share( $amp_template );
				break;
				case 'comments':
					kt_amp_post_comments( $amp_template );
				break;
				case 'ad_two':
					kt_amp_ad_two( $amp_template );
				break;
			}

		}

	endif;

}
/**
 * Build Amp Page
 *
 * @param object $amp_template the template object.
 */
function kt_amp_build_page( $amp_template ) {
	$kadence_amp = kadence_amp_options();
	if ( isset( $kadence_amp['page_layout'] ) ) {
		$layout = array();
		foreach ( $kadence_amp['page_layout'] as $key => $value ) {
			if ( 1 == $value ) {
				$layout[ $key ] = $value;
			}
		}
	} else {
		// Default layout show baisc setup
		$layout = array(
			"title"  			=> 1,
			"content" 			=> 1,
		);
	}
	if ($layout):

		foreach ($layout as $key => $value ) {

			switch( $key ) {

				case 'ad_one':
					kt_amp_ad_one( $amp_template );
				break;
				case 'breadcrumbs':
					kt_amp_breadcrumbs( $amp_template );
				break;
				case 'original_button':
					kt_amp_original_button( $amp_template );
				break;
				case 'feature':
					kt_amp_post_feature( $amp_template );
				break;
				case 'title':
					kt_amp_post_title( $amp_template );
				break;
				case 'content':
					kt_amp_post_content( $amp_template );
				break;
				case 'share':
					kt_amp_share( $amp_template );
				break;
				case 'comments':
					kt_amp_post_comments( $amp_template );
				break;
				case 'ad_two':
					kt_amp_ad_two( $amp_template );
				break;
			}

		}

	endif;

}
function kt_amp_build_post( $amp_template ) {
	$kadence_amp = kadence_amp_options();
	if ( isset( $kadence_amp[ 'post_layout' ] ) ) { 
		$layout = array();
		foreach ( $kadence_amp[ 'post_layout' ] as $key => $value ) {
			if( $value == 1 ) {
				$layout[$key] = $value;
			}
		}
	} else {
		// Default layout show baisc setup
		$layout = array(
			"feature"  			=> 1,
			"title"  			=> 1,
			"meta" 				=> 1,
			"content" 			=> 1,
			"categories"		=> 1,
			"tags"				=> 1,
			"share"				=> 1,
			"comments"			=> 1,
			"related"			=> 1,
		);
	}
	if ($layout):

		foreach ($layout as $key => $value ) {

			switch( $key ) {

				case 'ad_one':
					kt_amp_ad_one( $amp_template );
				break;
				case 'breadcrumbs':
					kt_amp_breadcrumbs( $amp_template );
				break;
				case 'original_button':
					kt_amp_original_button( $amp_template );
				break;
				case 'feature':
					kt_amp_post_feature( $amp_template );
				break;
				case 'title':
					kt_amp_post_title( $amp_template );
				break;
				case 'meta':
					kt_amp_post_meta( $amp_template );
				break;
				case 'content':
					kt_amp_post_content( $amp_template );
				break;
				case 'categories':
					kt_amp_post_categories( $amp_template );
				break;
				case 'tags':
					kt_amp_post_tags( $amp_template );
				break;
				case 'share':
					kt_amp_share( $amp_template );
				break;
				case 'comments':
					kt_amp_post_comments( $amp_template );
				break;
				case 'related':
					kt_amp_post_related( $amp_template );
				break;
				case 'ad_two':
					kt_amp_ad_two( $amp_template );
				break;
			}

		}

	endif;

}
if ( ! function_exists( 'kt_amp_post_feature' ) ) {
	function kt_amp_post_feature( $amp_template ) {
		$amp_template->load_parts( array( 'featured-image' ) );
	}
}
if ( ! function_exists( 'kt_amp_post_title' ) ) {
	function kt_amp_post_title( $amp_template ) {
		echo '<header class="amp-wp-article-header">';
			echo '<h1 class="amp-wp-title">'.wp_kses_data( $amp_template->get( 'post_title' ) ).'</h1>';
		echo '</header>';
	}
}
if ( ! function_exists( 'kt_amp_post_meta' ) ) {
	function kt_amp_post_meta( $amp_template ) {
		$kadence_amp = kadence_amp_options();
		$args = array();
		if( isset( $kadence_amp['show_date'] ) && 1 == $kadence_amp['show_date']) {
			$args[] = 'meta-time';
		}
		if( isset( $kadence_amp['show_author'] ) && 1 == $kadence_amp['show_author']) {
			$args[] = 'meta-author';
		}

		echo '<div class="amp-wp-article-meta">';
		$amp_template->load_parts( apply_filters( 'amp_post_article_header_meta', $args ) );
		echo '</div>';
	}
}
if ( ! function_exists( 'kt_amp_post_content' ) ) {
	function kt_amp_post_content( $amp_template ) {
		echo '<div class="amp-wp-page-content">';
			echo $amp_template->get( 'post_amp_content' );
		echo '</div>';
	}
}
if ( ! function_exists( 'kt_amp_post_categories' ) ) {
	function kt_amp_post_categories( $amp_template ) {
		$categories = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'kadence-amp' ), '', $amp_template->ID );
		 if ( $categories ) : 
			echo '<div class="amp-wp-meta amp-wp-tax-category">';
			printf( esc_html__( 'Categories: %s', 'amp' ), $categories ); 
			echo '</div>';
		endif; 
	}
}
if ( ! function_exists( 'kt_amp_post_tags' ) ) {
	function kt_amp_post_tags( $amp_template ) {
		$tags = get_the_tag_list('', _x( ', ', 'Used between list items, there is a space after the comma.', 'amp' ), '', $amp_template->ID );
		if ( $tags && ! is_wp_error( $tags ) ) : 
			echo '<div class="amp-wp-meta amp-wp-tax-tag">';
				printf( esc_html__( 'Tags: %s', 'amp' ), $tags );
			echo '</div>';
		endif;
	}
}

if ( ! function_exists( 'kt_amp_post_comments' ) ) {
	function kt_amp_post_comments( $amp_template ) {
		echo '<div class="amp-comments">';
			echo '<div id="comments" class="section-content comments-area">';
					$comments_html = $amp_template->get( 'comments_html' );
					if ( $comments_html ) : 
						echo $comments_html;
					endif;
				echo '</div>';
				$comments_link_url = $amp_template->get( 'comments_link_url' );
				if ( $comments_link_url ) : 
					$comments_link_text = $amp_template->get( 'comments_link_text' ); ?>
				<div class="amp-wp-comments-link">
					<a href="<?php echo esc_url( $comments_link_url ); ?>">
						<?php echo esc_html( $comments_link_text ); ?>
					</a>
				</div>
			<?php endif; 
		echo '</div>';
	}
}
if ( ! function_exists( 'kt_amp_post_related' ) ) {
	function kt_amp_post_related( $amp_template ) {
		$amp_template->load_parts( array( 'related-posts' ) );
	}
}
function kt_amp_build_product( $amp_template, $product ) {
	$kadence_amp = kadence_amp_options();
	if ( isset( $kadence_amp[ 'product_layout' ] ) ) { 
		$layout = array();
		foreach ( $kadence_amp[ 'product_layout' ] as $key => $value ) {
			if( $value == 1 ) {
				$layout[$key] = $value;
			}
		}
	} else {
		// Default layout show baisc setup
		$layout = array(
			"image_gallery"  	=> 1,
			"title"  			=> 1,
			"rating" 			=> 1,
			"shortdescription" 	=> 1,
			"price" 			=> 1,
			"add_to_cart" 		=> 1,
			"meta"   			=> 1,
			"categories"		=> 1,
			"tags"				=> 1,
			"share"				=> 1,
			"tabs"				=> 1,
			"related"			=> 1,
		);
	}
	if ($layout):

		foreach ($layout as $key => $value ) {

			switch( $key ) {

				case 'ad_one':
					kt_amp_ad_one( $amp_template );
				break;
				case 'breadcrumbs':
					kt_amp_breadcrumbs( $amp_template );
				break;
				case 'original_button':
					kt_amp_original_button( $amp_template );
				break;
				case 'image_gallery':
					kt_amp_product_image_gallery( $amp_template, $product );
				break;
				case 'title':
					kt_amp_product_title( $amp_template, $product );
				break;
				case 'rating':
					kt_amp_product_rating( $amp_template, $product );
				break;
				case 'price':
					kt_amp_product_price( $amp_template, $product );
				break;
				case 'shortdescription':
					kt_amp_product_shortdescription( $amp_template, $product );
				break;
				case 'add_to_cart':
					kt_amp_product_add_to_cart( $amp_template, $product );
				break;
				case 'meta':
					kt_amp_product_meta( $amp_template, $product );
				break;
				case 'share':
					kt_amp_share( $amp_template );
				break;
				case 'tabs':
					kt_amp_product_tabs( $amp_template, $product );
				break;
				case 'related':
					kt_amp_product_related( $amp_template, $product );
				break;
				case 'ad_two':
					kt_amp_ad_two( $amp_template );
				break;
			}

		}

	endif;

}
if ( ! function_exists( 'kt_amp_ad_one' ) ) {
	function kt_amp_ad_one( $amp_template ) {
		$kadence_amp = kadence_amp_options();
		if ( isset($kadence_amp['ad_block_1_custom'] ) && ! empty( $kadence_amp['ad_block_1_custom'] ) ) {
			echo $kadence_amp['ad_block_1_custom'];
		}
	}
}
if ( ! function_exists( 'kt_amp_breadcrumbs' ) ) {
	function kt_amp_breadcrumbs( $amp_template ) {
		kt_amp_output_breadcrumbs();
	}
}
if ( ! function_exists( 'kt_amp_original_button' ) ) {
	function kt_amp_original_button( $amp_template ) {
		$kadence_amp = kadence_amp_options();
		if ( empty( $kadence_amp[ 'show_original_text' ] ) ) {
			$kadence_amp[ 'show_original_text' ] = __('View original version', 'kadence-amp' );
		}
		echo '<div class="mod_show_original"><a href="'.get_permalink().'" class="mod_show_original_link">'.esc_html( $kadence_amp[ 'show_original_text' ] ).'</a></div>';
	}
}
if ( ! function_exists( 'kt_amp_share' ) ) {
	function kt_amp_share( $amp_template ) {
		$kadence_amp = kadence_amp_options();
		if ( isset( $kadence_amp[ 'social_options' ] ) ) { 
			$layout = array();
			foreach ( $kadence_amp[ 'social_options' ] as $key => $value ) {
				if ( $value == 1 && $key !== 'googleplus' ) {
					$layout[ $key ] = $value;
				}
			}
		} else {
			// Default layout show baisc setup
			$layout = array(
				"facebook"  	=> 1,
				"twitter"  		=> 1,
				"linkedin" 		=> 1,
				"pinterest" 	=> 0,
				"email" 		=> 0,
			);
		}
		if ($layout):
			echo '<div class="amp-share-box">';
			
			foreach ($layout as $key => $value ) {
				switch( $key ) {
					case 'facebook':
						echo '<amp-social-share type="facebook" width="60" height="44" data-share-endpoint="http://www.facebook.com/sharer.php?u=' . esc_attr( get_permalink() ) . '"></amp-social-share>';
					break;
					case 'twitter':
						echo '<amp-social-share type="twitter" width="60" height="44"></amp-social-share>';
					break;
					case 'linkedin':
						echo '<amp-social-share type="linkedin" width="60" height="44"></amp-social-share>';
					break;
					case 'googleplus':
						echo '<amp-social-share type="gplus" width="60" height="44"></amp-social-share>';
					break;
					case 'pinterest':
						echo '<amp-social-share type="pinterest" data-do="buttonPin" width="60" height="44"></amp-social-share>';
					break;
					case 'email':
						echo '<amp-social-share type="email" width="60" height="44"></amp-social-share>';
					break;
				}
			}
			echo '</div>';
		endif;
	}
}
if ( ! function_exists( 'kt_amp_ad_two' ) ) {
	function kt_amp_ad_two( $amp_template ) {
		$kadence_amp = kadence_amp_options();
		if ( isset($kadence_amp['ad_block_2_custom'] ) && ! empty( $kadence_amp['ad_block_2_custom'] ) ) {
			echo $kadence_amp['ad_block_2_custom'];
		}
	}
}
if ( ! function_exists( 'kt_amp_header_logo' ) ) {
	function kt_amp_header_logo( $amp_template ) {
		$kadence_amp = kadence_amp_options();

		echo '<a href="'.esc_url( $amp_template->get( 'home_url' ) ).'">';

			if ( isset( $kadence_amp[ 'logo_style' ] ) && ( 'image' === $kadence_amp[ 'logo_style' ]  || 'image_text' === $kadence_amp[ 'logo_style' ] ) ) {
				$img = kt_amp_get_image( null, '32', false, $kadence_amp[ 'logo_image' ][ 'id' ] );
				echo '<amp-img src="'.esc_url( $img[ 'src' ] ).'" width="'.esc_attr( $img[ 'width' ] ).'" height="'.esc_attr( $img[ 'height' ] ).'" alt="'.esc_attr( $img[ 'alt' ] ).'" '.$img[ 'srcset' ].' layout="responsive" class="amp-wp-site-icon kt-amp-logo"></amp-img>';		
			} 
			if ( ! isset( $kadence_amp[ 'logo_style' ] ) || 'image' != $kadence_amp[ 'logo_style' ] ) { 
				if ( isset( $kadence_amp[ 'logo_text' ] ) ) {
					echo esc_html( $kadence_amp[ 'logo_text' ] );
				} else {
					echo esc_html( $amp_template->get( 'blog_name' ) );
				}
			}
		echo '</a>';
	}
}
if ( ! function_exists( 'kt_amp_header_menu' ) ) {
	function kt_amp_header_menu( $amp_template ) {
		$kadence_amp = kadence_amp_options();
		echo '<div class="amp-accordion-header">';
		if ( isset( $kadence_amp[ 'menu_style' ] ) && 'text' === $kadence_amp[ 'menu_style' ] ) {
			echo '<button class="amp-menu-sidebar" on="tap:sidebar.toggle">';
			if( ! empty( $kadence_amp[ 'menu_text' ] ) ) {
					echo esc_html( $kadence_amp[ 'menu_text' ] );
				} else {
					echo esc_html__( 'Menu', 'kadence_amp' );
				}
			echo '</button>';
        } else {
        	echo '<button class="amp-menu-sidebar amp-icon-button" on="tap:sidebar.toggle"></button>';
        }
        echo '</div>';
	}
}
if ( ! function_exists( 'kt_amp_sidebar' ) ) {
	function kt_amp_sidebar( $amp_template ) {
		echo '<amp-sidebar id="sidebar" layout="nodisplay" side="right">';
			echo '<div class="amp-sidebar-navigation">';
				echo '<div role="button" tabindex="0" on="tap:sidebar.close" class="close-amp-sidebar">X</div>';
				if ( has_nav_menu( 'amp_menu' ) ) {
					$menu = wp_nav_menu( array( 'theme_location' => 'amp_menu' , 'echo' => false ) );
				} else {
					$menu = '';
				}
        		echo  apply_filters( 'amp_nav_menu', $menu );
  			echo '</div>';
		echo '</amp-sidebar>';
	}
}
if ( ! function_exists( 'kt_amp_add_amp_script' ) ) {
	function kt_amp_add_amp_script( $data ) {
		$kadence_amp = kadence_amp_options();
		$data['amp_component_scripts']['amp-sidebar'] = 'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js';
		$data['amp_component_scripts']['amp-social-share'] = 'https://cdn.ampproject.org/v0/amp-social-share-0.1.js';
		if ( is_singular( 'product' ) ) {
			$data['amp_component_scripts']['amp-carousel'] = 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js';
			$data['amp_component_scripts']['amp-selector'] = 'https://cdn.ampproject.org/v0/amp-selector-0.1.js';
			$data['amp_component_scripts']['amp-bind'] = 'https://cdn.ampproject.org/v0/amp-bind-0.1.js';
			$data['amp_component_scripts']['amp-lightbox'] = 'https://cdn.ampproject.org/v0/amp-lightbox-0.1.js';
			$data['amp_component_scripts']['amp-image-lightbox'] = 'https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js';
			$data['amp_component_scripts']['amp-form'] = 'https://cdn.ampproject.org/v0/amp-form-0.1.js';
		}
		if ( isset( $kadence_amp[ 'ad_block_1_custom' ] ) && !empty( $kadence_amp[ 'ad_block_1_custom' ] ) ||  isset( $kadence_amp[ 'ad_block_2_custom' ] ) && !empty( $kadence_amp[ 'ad_block_2_custom' ] ) ){
			if ( isset( $kadence_amp['ad_block_1_custom'] ) && ! empty( $kadence_amp['ad_block_1_custom'] ) && strpos( $kadence_amp['ad_block_1_custom'], 'amp-auto-ads' ) !== false ) {
				$data['amp_component_scripts']['amp-auto-ads'] = 'https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js';
			} else if ( isset( $kadence_amp['ad_block_2_custom'] ) && ! empty( $kadence_amp['ad_block_2_custom'] ) && strpos( $kadence_amp['ad_block_2_custom'], 'amp-auto-ads' ) !== false ) {
				$data['amp_component_scripts']['amp-auto-ads'] = 'https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js';
			} else {
				$data['amp_component_scripts']['amp-ad'] = 'https://cdn.ampproject.org/v0/amp-ad-0.1.js';
			}
		}
		if ( ! empty( $data['amp_analytics'] ) ) {
			$data['amp_component_scripts']['amp-analytics'] = 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js';
		}
		return $data;
	}
}
if ( ! function_exists( 'kt_amp_fonts' ) ) {
	function kt_amp_fonts( $amp_template ) {
		$kadence_amp = kadence_amp_options();
		$fonts = array();
		$link    = "";
        $subsets = array();

		if( isset( $kadence_amp['logo_text_font'] ) && 1 == $kadence_amp['logo_text_font']['google'] && 'image' != $kadence_amp['logo_style']) {
			$fonts = array(
				$kadence_amp['logo_text_font']['font-family'] => array(
					'font-style' => array(
							0 => $kadence_amp['logo_text_font']['font-weight'],
					),
					'subset' => array(
							0 => $kadence_amp['logo_text_font']['subsets'],
					),
				),
			);
		}
		if( isset( $kadence_amp['menu_text_font'] ) && 1 == $kadence_amp['menu_text_font']['google'] && 'icon' != $kadence_amp['menu_style'] ) {
			if(! isset( $fonts[ $kadence_amp[ 'menu_text_font' ][ 'font-family' ] ] ) ) {
				$fonts = array(
					$kadence_amp['menu_text_font']['font-family'] => array(
						'font-style' => array(
								0 => $kadence_amp['menu_text_font']['font-weight'],
						),
						'subset' => array(
								0 => $kadence_amp['menu_text_font']['subsets'],
						),
					),
				);
			} else {
				if( ! in_array($kadence_amp['menu_text_font']['font-style'], $fonts[ $kadence_amp[ 'menu_text_font' ][ 'font-family' ] ] ) ) {
					array_push($fonts[ $kadence_amp[ 'menu_text_font' ][ 'font-family' ] ][ 'font-style' ], $kadence_amp['menu_text_font']['font-weight']);
				}
			}
		}
		if( isset( $kadence_amp['menu_font'] ) && 1 == $kadence_amp['menu_font']['google'] ) {
			if(! isset( $fonts[ $kadence_amp[ 'menu_font' ][ 'font-family' ] ] ) ) {
				$fonts = array(
					$kadence_amp['menu_font']['font-family'] => array(
						'font-style' => array(
								0 => $kadence_amp['menu_font']['font-weight'],
						),
						'subset' => array(
								0 => $kadence_amp['menu_font']['subsets'],
						),
					),
				);
			} else {
				if( ! in_array($kadence_amp['menu_font']['font-style'], $fonts[ $kadence_amp[ 'menu_font' ][ 'font-family' ] ] ) ) {
					array_push($fonts[ $kadence_amp[ 'menu_font' ][ 'font-family' ] ][ 'font-style' ], $kadence_amp['menu_font']['font-weight']);
				}
			}
		}
		if( isset( $kadence_amp['h_font'] ) && 1 == $kadence_amp['h_font']['google'] ) {
			if(! isset( $fonts[ $kadence_amp[ 'h_font' ][ 'font-family' ] ] ) ) {
				$fonts = array(
					$kadence_amp['h_font']['font-family'] => array(
						'font-style' => array(
								0 => $kadence_amp['h_font']['font-weight'],
						),
						'subset' => array(
								0 => $kadence_amp['h_font']['subsets'],
						),
					),
				);
			} else {
				if( ! in_array($kadence_amp['h_font']['font-style'], $fonts[ $kadence_amp[ 'h_font' ][ 'font-family' ] ] ) ) {
					array_push($fonts[ $kadence_amp[ 'h_font' ][ 'font-family' ] ][ 'font-style' ], $kadence_amp['h_font']['font-weight']);
				}
			}
		}
		if( isset( $kadence_amp['body_font'] ) && 1 == $kadence_amp['body_font']['google'] ) {
			if(! isset( $fonts[ $kadence_amp[ 'body_font' ][ 'font-family' ] ] ) ) {
				$fonts = array(
					$kadence_amp['body_font']['font-family'] => array(
						'font-style' => array(
								0 => $kadence_amp['body_font']['font-weight'],
						),
						'subset' => array(
								0 => $kadence_amp['body_font']['subsets'],
						),
					),
				);
			} else {
				if( ! in_array($kadence_amp['body_font']['font-style'], $fonts[ $kadence_amp[ 'body_font' ][ 'font-family' ] ] ) ) {
					array_push($fonts[ $kadence_amp[ 'body_font' ][ 'font-family' ] ][ 'font-style' ], $kadence_amp['body_font']['font-weight']);
				}
			}
		}
		foreach ( $fonts as $family => $font ) {
			if ( ! empty( $link ) ) {
				$link .= "%7C"; // Append a new font to the string
			}
			$link .= $family;
			if ( ! empty( $font['font-style'] ) || ! empty( $font['all-styles'] ) ) {
				$link .= ':';
				if ( ! empty( $font['all-styles'] ) ) {
					$link .= implode( ',', $font['all-styles'] );
				} else if ( ! empty( $font['font-style'] ) ) {
					$link .= implode( ',', $font['font-style'] );
				}
			}

			if ( ! empty( $font['subset'] ) ) {
				foreach ( $font['subset'] as $subset ) {
					if ( ! in_array( $subset, $subsets ) ) {
			    		array_push( $subsets, $subset );
					}
				}
			}
		}

		if ( ! empty( $subsets ) ) {
			$link .= "&subset=" . implode( ',', $subsets );
		}

		if ( $link ) {
			echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . str_replace( '|', '%7C', $link ).'">';
		}

	}
}