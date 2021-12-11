<?php
/**
 * Amp Template class
 *
 * @package Kadence AMP
 */

/*
require_once( AMP__DIR__ . '/includes/utils/class-amp-dom-utils.php' );
require_once( AMP__DIR__ . '/includes/utils/class-amp-html-utils.php' );
require_once( AMP__DIR__ . '/includes/utils/class-amp-string-utils.php' );
require_once( AMP__DIR__ . '/includes/utils/class-amp-wp-utils.php' );

require_once( AMP__DIR__ . '/includes/class-amp-content.php' );

require_once( AMP__DIR__ . '/includes/sanitizers/class-amp-style-sanitizer.php' );
require_once( AMP__DIR__ . '/includes/sanitizers/class-amp-blacklist-sanitizer.php' );
require_once( AMP__DIR__ . '/includes/sanitizers/class-amp-tag-and-attribute-sanitizer.php' );
require_once( AMP__DIR__ . '/includes/sanitizers/class-amp-img-sanitizer.php' );
require_once( AMP__DIR__ . '/includes/sanitizers/class-amp-video-sanitizer.php' );
require_once( AMP__DIR__ . '/includes/sanitizers/class-amp-iframe-sanitizer.php' );
require_once( AMP__DIR__ . '/includes/sanitizers/class-amp-audio-sanitizer.php' );
require_once( AMP__DIR__ . '/includes/sanitizers/class-amp-playbuzz-sanitizer.php' );

require_once( AMP__DIR__ . '/includes/embeds/class-amp-twitter-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-youtube-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-dailymotion-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-vimeo-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-soundcloud-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-gallery-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-instagram-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-vine-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-facebook-embed.php' );
require_once( AMP__DIR__ . '/includes/embeds/class-amp-pinterest-embed.php' );
*/

/**
 * Kadence AMP template Class
 *
 * @category class.
 */
class Kadence_AMP_Template {

	const SITE_ICON_SIZE    = 300;
	const CONTENT_MAX_WIDTH = 600;

	/**
	 * Template Directory var
	 *
	 * @var path.
	 */
	private $template_dir;
	/**
	 * Am data
	 *
	 * @var data.
	 */
	private $data;
	/**
	 * Post ID.
	 *
	 * @var int
	 */
	public $ID;

	/**
	 * Post.
	 *
	 * @since 0.2
	 * @var WP_Post
	 */
	public $post;

	/**
	 * Am data
	 *
	 * @param object $post the posts object.
	 */
	public function __construct( $post ) {
		$this->template_dir = apply_filters( 'amp_post_template_dir', KADENCE_AMP_PATH . 'templates' );

		if ( $post instanceof WP_Post ) {
			$this->post = $post;
		} else {
			$this->post = get_post( $post );
		}
		// Make sure we have a post, or bail if not.
		if ( is_a( $this->post, 'WP_Post' ) ) {
			$this->ID = $this->post->ID;
		} else {
			return;
		}
		// No errors with products.
		if ( is_singular( 'product' ) ) {
			global $product;
			$product = wc_get_product( $this->ID );
		}
		// Unhook Pagebuilder if custom content.
		$custom_amp_content = get_post_meta( $this->ID, '_kt_amp_content', true );
		if ( ! empty( $custom_amp_content ) ) {
			$this->post->post_content = $custom_amp_content;
			add_filter( 'siteorigin_panels_filter_content_enabled', '__return_false' );
		}
		// Set max width.
		$content_max_width = self::CONTENT_MAX_WIDTH;
		$content_max_width = apply_filters( 'amp_content_max_width', $content_max_width );

		$this->data = array(
			'content_max_width'     => $content_max_width,
			'document_title'        => function_exists( 'wp_get_document_title' ) ? wp_get_document_title() : wp_title( '', false ), // back-compat with 4.3.
			'canonical_url'         => get_permalink( $this->ID ),
			'home_url'              => home_url(),
			'blog_name'             => get_bloginfo( 'name' ),
			'html_tag_attributes'   => array(),
			'body_class'            => '',
			'site_icon_url'         => apply_filters( 'amp_site_icon_url', function_exists( 'get_site_icon_url' ) ? get_site_icon_url( self::SITE_ICON_SIZE ) : '' ),
			'placeholder_image_url' => kt_amp_get_asset_url( 'images/placeholder-icon.png' ),
			'featured_image'        => false,
			'comments_html'         => false,
			'comments_link_url'     => false,
			'comments_link_text'    => false,
			'product_amp_related'   => false,
			'amp_runtime_script'    => 'https://cdn.ampproject.org/v0.js',
			'amp_component_scripts' => array(),
			'font_urls'             => array(),
			'post_amp_styles'       => array(),
			'post_amp_stylesheets'  => array(),
			'metadata'              => '',

			/**
			 * Add amp-analytics tags.
			 *
			 * This filter allows you to easily insert any amp-analytics tags without needing much heavy lifting.
			 */
			'amp_analytics'         => apply_filters( 'amp_post_template_analytics', array(), $this->post ),
		);

		$this->build_post_content();
		// $this->build_post_data();
		$this->build_html_tag_attributes();
		if ( is_singular( 'product' ) ) {
			$this->build_product_data( $product );
		} else {
			$this->build_post_data();
		}

		$this->data = apply_filters( 'amp_post_template_data', $this->data, $this->post );
	}
	/**
	 * Get data property.
	 *
	 * @param string $property the data key.
	 * @param string $default the data default.
	 */
	public function get( $property, $default = null ) {
		if ( isset( $this->data[ $property ] ) ) {
			return $this->data[ $property ];
		} else {
			// translators: %s is the data key.
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Called for non-existant key ("%s").', 'kadence-amp' ), esc_html( $property ) ), '0.1' );
		}
		return $default;
	}
	/**
	 * Load function
	 */
	public function load() {
		switch ( true ) {
			case is_front_page() && is_home():
				$this->load_parts( array( 'archive' ) );
				break;
			case is_front_page():
				$this->load_parts( array( 'single-page' ) );
				break;
			case is_home():
				$this->load_parts( array( 'archive' ) );
				break;
			case is_page():
				$this->load_parts( array( 'single-page' ) );
				break;
			case is_singular( 'product' ):
				$this->load_parts( array( 'single-product' ) );
				break;
			case is_singular( 'post' ):
				$this->load_parts( array( 'single-post' ) );
				break;
			case is_singular():
				$this->load_parts( array( 'single' ) );
				break;
			default:
				$this->load_parts( array( 'single' ) );
				break;
		}
	}
	/**
	 * Load Parts
	 *
	 * @param array $templates the templates name.
	 */
	public function load_parts( $templates ) {
		foreach ( $templates as $template ) {
			$file = $this->get_template_path( $template );
			$this->verify_and_include( $file, $template );
		}
	}
	/**
	 * Get Template Path from name.
	 *
	 * @param string $template the template name.
	 */
	private function get_template_path( $template ) {
		return sprintf( '%s/%s.php', $this->template_dir, $template );
	}

	/**
	 * Add data to instance data.
	 *
	 * @param array $data the data to add.
	 */
	private function add_data( $data ) {
		$this->data = array_merge( $this->data, $data );
	}
	/**
	 * Add data to instance data.
	 *
	 * @param string $key the data key.
	 * @param string $value the data to add.
	 */
	private function add_data_by_key( $key, $value ) {
		$this->data[ $key ] = $value;
	}
	/**
	 * Add data to instance data.
	 *
	 * @param string $key the data key.
	 * @param string $value the data to add.
	 */
	private function merge_data_for_key( $key, $value ) {
		if ( is_array( $this->data[ $key ] ) ) {
			$this->data[ $key ] = array_merge( $this->data[ $key ], $value );
		} else {
			$this->add_data_by_key( $key, $value );
		}
	}
	/**
	 * Create Post content.
	 */
	private function build_post_data() {
		$post_title              = get_the_title( $this->ID );
		$post_publish_timestamp  = get_the_date( 'U', $this->ID );
		$post_modified_timestamp = get_post_modified_time( 'U', false, $this->post );
		$post_author             = get_userdata( $this->post->post_author );

		$this->add_data(
			array(
				'post'                    => $this->post,
				'post_id'                 => $this->ID,
				'post_title'              => $post_title,
				'post_publish_timestamp'  => $post_publish_timestamp,
				'post_modified_timestamp' => $post_modified_timestamp,
				'post_author'             => $post_author,
			)
		);
		if ( is_singular( 'post' ) ) {
			$kadence_amp             = kadence_amp_options();
			if ( isset( $kadence_amp['post_schema'] ) && 'NewsArticle' === $kadence_amp['post_schema'] ) {
				$schematype = 'NewsArticle';
			} else {
				$schematype = 'BlogPosting';
			}
			$metadata = array(
				'@context'         => 'http://schema.org',
				'@type'            => $schematype,
				'mainEntityOfPage' => $this->get( 'canonical_url' ),
				'publisher'        => array(
					'@type' => 'Organization',
					'name'  => $this->get( 'blog_name' ),
				),
				'headline'         => $post_title,
				'datePublished'    => date( 'c', $post_publish_timestamp ),
				'dateModified'     => date( 'c', $post_modified_timestamp ),
				'author'           => array(
					'@type' => 'Person',
					'name'  => $post_author->display_name,
				),
			);

			$site_icon_url = $this->get( 'site_icon_url' );
			if ( $site_icon_url ) {
				$metadata['publisher']['logo'] = array(
					'@type'  => 'ImageObject',
					'url'    => $site_icon_url,
					'height' => self::SITE_ICON_SIZE,
					'width'  => self::SITE_ICON_SIZE,
				);
			}

			$image_metadata = $this->get_post_image_metadata();
			if ( $image_metadata ) {
				$metadata['image'] = $image_metadata;
			}
			$this->add_data_by_key( 'metadata', apply_filters( 'amp_post_template_metadata', $metadata, $this->post ) );
		}
		$this->build_post_featured_image();
		$this->build_post_commments_data();
	}
	/**
	 * Create Product content.
	 *
	 * @param object $product the object
	 */
	private function build_product_data( $product ) {
		$post_title              = get_the_title( $this->ID );
		$post_publish_timestamp  = get_the_date( 'U', $this->ID );
		$post_modified_timestamp = get_post_modified_time( 'U', false, $this->post );

		$this->add_data(
			array(
				'post'                    => $this->post,
				'post_id'                 => $this->ID,
				'post_title'              => $post_title,
				'post_publish_timestamp'  => $post_publish_timestamp,
				'post_modified_timestamp' => $post_modified_timestamp,
			)
		);
		if ( $product->is_in_stock() ) {
			$availability = 'http://schema.org/InStock';
		} else {
			$availability = 'https://schema.org/OutOfStock';
		}
		$rating_count = $product->get_rating_count();
		$average      = $product->get_average_rating();
		$metadata = array(
			'@context' => 'http://schema.org',
			'@type' => 'Product',
			'mainEntityOfPage' => $this->get( 'canonical_url' ),
			'name' => $post_title,
			'description' => wc_clean( wpautop( do_shortcode( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ) ) ),
			'aggregateRating' => array(
				'@type' => 'AggregateRating',
				'ratingValue' => $average,
				'ratingCount' => $rating_count,
			),
			'offers' => array(
				'@type' => 'Offer',
				'price' => $product->get_price(),
				'priceCurrency' => get_woocommerce_currency(),
				'availability' => $availability,
				'url' => $this->get( 'canonical_url' ),
				'seller' => array(
					'@type' => 'Organization',
					'name' => get_bloginfo( 'name' ),
					'url' => home_url(),
				),
			),
		);
		if ( $product->is_type( 'variable' ) ) {
			$min_price = $product->get_variation_price( 'min', false );
			$max_price = $product->get_variation_price( 'max', false );
			if ( $min_price and $max_price ) {
				unset( $metadata['offers']['price'] );
				unset( $metadata['offers']['priceCurrency'] );
				$_metadata['offers']['priceSpecification'] = array(
					'price'         => $product->get_price(),
					'minPrice'      => $min_price,
					'maxPrice'      => $max_price,
					'priceCurrency' => get_woocommerce_currency(),
				);
			}
		}
		$image_metadata = $this->get_post_image_metadata();
		if ( $image_metadata ) {
			$metadata['image'] = $image_metadata;
		}

		$this->add_data_by_key( 'metadata', apply_filters( 'amp_post_template_metadata', $metadata, $this->post ) );
		$this->build_product_featured_image();
		$this->build_product_short_description();
		$this->build_product_rating( $product );
		$this->build_product_review_data( $product );
		$this->build_product_tabs( $product );
		$this->build_product_related( $product );
	}

	private function build_product_review_data() {

		$comments_open = comments_open( $this->ID );

		$comments = get_comments(
			array(
				'post_id' => $this->ID,
				'order' => 'ASC',
			)
		);
		if ( count( $comments ) > 0 ) {
			ob_start();
			echo '<h3>';
				printf( _n( 'One Response ', '%1$s Responses ', get_comments_number(), 'kadence-amp' ), number_format_i18n( get_comments_number() ), get_the_title() );
			echo '</h3>';
			echo '<ul class="comment-list amp-reviews">';
				wp_list_comments( array( 'callback' => 'woocommerce_comments' ), $comments );
			echo '</ul>';
			$comments = ob_get_clean();

			list( $sanitized_html, $comment_scripts, $comment_styles )  = AMP_Content_Sanitizer::sanitize(
				$comments,
				array(
					'AMP_Img_Sanitizer' => array(),
					'AMP_Style_Sanitizer' => array(),
				)
			);

		} else {
			$sanitized_html = false;
		}
		if ( ! $comments_open ) {
			$comments_link_url = false;
		} else {
			$comments_link_url = str_replace( '#respond', '#reviews', get_comments_link( $this->ID ) );
		}
		$comments_link_text = __( 'Add a review', 'kadence-amp' );

		$this->add_data(
			array(
				'comments_html' => $sanitized_html,
				'comments_link_url' => $comments_link_url,
				'comments_link_text' => $comments_link_text,
			)
		);
		if ( isset( $comment_scripts ) ) {
			$this->merge_data_for_key( 'amp_component_scripts', $comment_scripts );
		}
		if ( isset( $comment_styles ) ) {
			$this->merge_data_for_key( 'post_amp_styles', $comment_styles );
		}
	}

	private function build_post_commments_data() {
		if ( ! post_type_supports( $this->post->post_type, 'comments' ) ) {
			return;
		}

		$comments_open = comments_open( $this->ID );

		$comments = get_comments(
			array(
				'post_id' => $this->ID,
				'order' => 'ASC',
			)
		);
		if ( count( $comments ) > 0 ) {
			ob_start();
			echo '<h3>';
				printf( _n( 'One Response ', '%1$s Responses ', get_comments_number(), 'kadence-amp' ), number_format_i18n( get_comments_number() ), get_the_title() );
			echo '</h3>';
			echo '<ul class="comment-list amp-reviews">';
				wp_list_comments( array( 'avatar_size' => 48 ), $comments );
			echo '</ul>';
			$comments = ob_get_clean();

				list( $sanitized_html, $comment_scripts, $comment_styles )  = AMP_Content_Sanitizer::sanitize(
					$comments,
					array(
						'AMP_Img_Sanitizer' => array(),
						'AMP_Tag_And_Attribute_Sanitizer' => array(),
					)
				);

		} else {
			$sanitized_html = false;
		}
		if ( ! $comments_open ) {
			$comments_link_url = false;
		} else {
			$comments_link_url = get_comments_link( $this->ID );
		}
		$comments_link_text = __( 'Leave a Comment', 'kadence-amp' );

		$this->add_data(
			array(
				'comments_html' => $sanitized_html,
				'comments_link_url' => $comments_link_url,
				'comments_link_text' => $comments_link_text,
			)
		);
	}
	/**
	 * Build post content.
	 */
	private function build_post_content() {
		$amp_content = new AMP_Content(
			$this->post->post_content,
			kadence_amp_get_content_embed_handlers( $this->post ),
			kadence_amp_get_content_sanitizers( $this->post ),
			array(
				'content_max_width' => $this->get( 'content_max_width' ),
			)
		);

		$this->add_data_by_key( 'post_amp_content', $amp_content->get_amp_content() );
		$this->merge_data_for_key( 'amp_component_scripts', $amp_content->get_amp_scripts() );
		// $this->merge_data_for_key( 'post_amp_styles', $amp_content->get_amp_styles() );
		$this->add_data_by_key( 'post_amp_stylesheets', $amp_content->get_amp_stylesheets() );
	}

	private function build_product_short_description() {
		$html = apply_filters( 'woocommerce_short_description', $this->post->post_excerpt );
		list( $short_html, $short_scripts, $short_styles ) = AMP_Content_Sanitizer::sanitize(
			$html,
			array(
				'AMP_Style_Sanitizer' => array(),
				// 'AMP_Blacklist_Sanitizer' => array(),
				'AMP_Img_Sanitizer' => array(),
				'AMP_Video_Sanitizer' => array(),
				'AMP_Audio_Sanitizer' => array(),
				'AMP_Playbuzz_Sanitizer' => array(),
				'AMP_Iframe_Sanitizer' => array(
					'add_placeholder' => true,
				),
				'AMP_Tag_And_Attribute_Sanitizer' => array(),
			)
		);
		$this->add_data_by_key( 'product_amp_short_description', $short_html );
		if ( $short_scripts ) {
			$this->merge_data_for_key( 'amp_component_scripts', $short_scripts );
		}
		if ( $short_styles ) {
			$this->merge_data_for_key( 'post_amp_styles', $short_styles );
		}
	}
	private function build_product_rating( $product ) {
		$rating_count = $product->get_rating_count();
		$average      = $product->get_average_rating();

		$html = wc_get_rating_html( $average, $rating_count );
		list( $rating_html, $rating_scripts, $rating_styles ) = AMP_Content_Sanitizer::sanitize(
			$html,
			array(
				'AMP_Style_Sanitizer' => array(),
			)
		);
		$this->add_data_by_key( 'product_amp_rating', $rating_html );
		if ( $rating_scripts ) {
			$this->merge_data_for_key( 'amp_component_scripts', $rating_scripts );
		}
		if ( $rating_styles ) {
			$this->merge_data_for_key( 'post_amp_styles', $rating_styles );
		}
	}
	private function build_product_tabs() {
		$tabs    = apply_filters( 'woocommerce_product_tabs', array() );
		foreach ( $tabs as $key => $tab ) :
			if ( 'description' != $key && 'reviews' != $key && 'additional_information' != $key && isset( $tab['callback'] ) ) {
				ob_start();
				call_user_func( $tab['callback'], $key, $tab );
				$html = ob_get_clean();
				list( $tab_html, $tab_scripts, $tab_styles ) = AMP_Content_Sanitizer::sanitize(
					$html,
					array(
						'AMP_Style_Sanitizer' => array(),
						// 'AMP_Blacklist_Sanitizer' => array(),
						'AMP_Img_Sanitizer' => array(),
						'AMP_Video_Sanitizer' => array(),
						'AMP_Audio_Sanitizer' => array(),
						'AMP_Playbuzz_Sanitizer' => array(),
						'AMP_Iframe_Sanitizer' => array(
							'add_placeholder' => true,
						),
						'AMP_Tag_And_Attribute_Sanitizer' => array(),
					)
				);
				$this->add_data_by_key( 'product_tab_' . $key, $tab_html );
				if ( $tab_scripts ) {
					$this->merge_data_for_key( 'amp_component_scripts', $tab_scripts );
				}
				if ( $tab_styles ) {
					$this->merge_data_for_key( 'post_amp_styles', $tab_styles );
				}
			}
		endforeach;
	}
	private function build_product_related( $product ) {

		if ( ! $related = wc_get_related_products( $product->get_id(), 4 ) ) {
			return;
		}
		ob_start();
		foreach ( $related as $product_id ) :
					do_action( 'amp_wc_product_loop', $product_id );
		endforeach;
		$html = ob_get_clean();
		list( $related_html, $related_scripts, $related_styles ) = AMP_Content_Sanitizer::sanitize(
			$html,
			array(
				'AMP_Style_Sanitizer' => array(),
			)
		);
		$this->add_data_by_key( 'product_amp_related', $related_html );
		if ( $related_scripts ) {
			$this->merge_data_for_key( 'amp_component_scripts', $related_scripts );
		}
		if ( $related_styles ) {
			$this->merge_data_for_key( 'post_amp_styles', $related_styles );
		}
	}
	private function build_product_featured_image() {
		$post_id = $this->ID;
		if ( ! has_post_thumbnail( $post_id ) ) {
			return;
		}
		// Image
		$featured_html = get_the_post_thumbnail( $post_id, 'large' );

		list( $sanitized_html, $featured_scripts, $featured_styles ) = AMP_Content_Sanitizer::sanitize(
			$featured_html,
			array(
				'AMP_Img_Sanitizer' => array(),
				'AMP_Tag_And_Attribute_Sanitizer' => array(),
			),
			array(
				'content_max_width' => $this->get( 'content_max_width' ),
			)
		);
		$this->add_data_by_key(
			'featured_image',
			array(
				'amp_html' => $sanitized_html,
			)
		);

		if ( $featured_scripts ) {
			$this->merge_data_for_key( 'amp_component_scripts', $featured_scripts );
		}

		if ( $featured_styles ) {
			$this->merge_data_for_key( 'post_amp_styles', $featured_styles );
		}
	}
	private function build_post_featured_image() {
		$post_id = $this->ID;
		if ( is_singular( 'post' ) ) {
			$featureformat = kadence_amp_theme_info();
			if ( 'is_kadence_pinnacle_premium' == $featureformat ) {
				if ( function_exists( 'kt_get_post_head_content' ) ) {
					$head = kt_get_post_head_content();
				} else if ( function_exists( 'pinnacle_get_post_head_content' ) ) {
					$head = pinnacle_get_post_head_content();
				} else {
					$head = 'none';
				}
			} else if ( 'is_kadence_ascend_premium' == $featureformat ) {
				if ( function_exists( 'ascend_get_post_head_content' ) ) {
					$head = ascend_get_post_head_content();
				} else {
					$head = 'none';
				}
			} else if ( 'is_kadence_virtue_premium' == $featureformat ) {
				$head = get_post_meta( $post_id, '_kad_blog_head', true );
				if ( empty( $head ) || $head == 'default' ) {
					global $virtue_premium;
					if ( ! empty( $virtue_premium['post_head_default'] ) ) {
						$head = $virtue_premium['post_head_default'];
					} else {
						$head = 'none';
					}
				}
			} else {
				$head = 'image';
			}
		} else {
			$head = 'image';
		}
		// Skip if set to none
		if ( 'none' == $head ) {
			return;
		}

		if ( 'flex' == $head || 'imgcarousel' == $head || 'carouselslider' == $head || 'carousel' == $head || 'thumbslider' == $head || 'gallery' == $head ) {
			// Gallery
			$image_gallery = get_post_meta( $post_id, '_kad_image_gallery', true );
			$attachments = array_filter( explode( ',', $image_gallery ) );
			foreach ( $attachments as $attachment_id ) {
				list( $url, $width, $height ) = wp_get_attachment_image_src( $attachment_id, array( 600, 480 ), true );

				if ( ! $url ) {
					continue;
				}
				$urls[] = array(
					'url' => $url,
					'width' => $width,
					'height' => $height,
				);
			}
			$images = array();
			if ( isset( $urls ) ) {
				foreach ( $urls as $image ) {
					$images[] = AMP_HTML_Utils::build_tag(
						'amp-img',
						array(
							'src' => $image['url'],
							'width' => $image['width'],
							'height' => $image['height'],
							'layout' => 'responsive',
						)
					);
				}
			}

			$sanitized_html = AMP_HTML_Utils::build_tag(
				'amp-carousel',
				array(
					'width' => 600,
					'height' => 480,
					'type' => 'slides',
					'layout' => 'responsive',
				),
				implode( PHP_EOL, $images )
			);
			$this->merge_data_for_key( 'amp_component_scripts', array( 'amp-carousel' => 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js' ) );

		} else if ( 'video' == $head ) {
			// Video
			$video = get_post_meta( $post_id, '_kad_post_video', true );
			if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
				if ( strpos( $video, 'youtube' ) > 0 ) {
					// Youtube
					$video_id = ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video, $match ) ) ? $match[1] : false;
					$sanitized_html = AMP_HTML_Utils::build_tag(
						'amp-youtube',
						array(
							'data-videoid' => $video_id,
							'layout' => 'responsive',
							'width' => 600,
							'height' => 338,
						)
					);
					$featured_scripts = array( 'amp-youtube' => 'https://cdn.ampproject.org/v0/amp-youtube-0.1.js' );
				} else if ( strpos( $video, 'vimeo' ) > 0 ) {
					$video_id = ( preg_match( '~(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*)"?(?:[^>]*></iframe>)?(?:<p>.*</p>)?~ix', $video, $match ) ) ? $match[1] : false;
					$sanitized_html = AMP_HTML_Utils::build_tag(
						'amp-vimeo',
						array(
							'data-videoid' => $video_id,
							'layout' => 'responsive',
							'width' => 600,
							'height' => 338,
						)
					);
					$featured_scripts = array( 'amp-vimeo' => 'https://cdn.ampproject.org/v0/amp-vimeo-0.1.js' );
				} else {
					ob_start();
					echo do_shortcode( wp_oembed_get( $video ) );
					$video_html = ob_get_clean();
					list( $sanitized_html, $featured_scripts, $featured_styles )  = AMP_Content_Sanitizer::sanitize(
						$video_html,
						array(
							'AMP_Img_Sanitizer' => array(),
							'AMP_Video_Sanitizer' => array(),
							'AMP_Audio_Sanitizer' => array(),
							'AMP_Playbuzz_Sanitizer' => array(),
							'AMP_Iframe_Sanitizer' => array(
								'add_placeholder' => true,
							),
							'AMP_Tag_And_Attribute_Sanitizer' => array(),
						)
					);
				}
			} else {
				ob_start();
					echo do_shortcode( $video );
				$video_html  = ob_get_clean();
				list( $sanitized_html, $featured_scripts, $featured_styles )  = AMP_Content_Sanitizer::sanitize(
					$video_html,
					array(
						'AMP_Img_Sanitizer' => array(),
						'AMP_Video_Sanitizer' => array(),
						'AMP_Audio_Sanitizer' => array(),
						'AMP_Playbuzz_Sanitizer' => array(),
						'AMP_Iframe_Sanitizer' => array(
							'add_placeholder' => true,
						),
						'AMP_Tag_And_Attribute_Sanitizer' => array(),
					)
				);
			}
		} else {
			// Skip featured image if no featured image is available.
			if ( ! has_post_thumbnail( $post_id ) ) {
				return;
			}
			// Image
			$featured_html = get_the_post_thumbnail( $post_id, 'large' );

			$featured_id = get_post_thumbnail_id( $post_id );

			// If an image with the same ID as the featured image exists in the content, skip the featured image markup.
			// Prevents duplicate images, which is especially problematic for photo blogs.
			// A bit crude but it's fast and should cover most cases.
			$post_content = $this->post->post_content;
			if ( false !== strpos( $post_content, 'wp-image-' . $featured_id )
				|| false !== strpos( $post_content, 'attachment_' . $featured_id ) ) {
				return;
			}

			list( $sanitized_html, $featured_scripts, $featured_styles ) = AMP_Content_Sanitizer::sanitize(
				$featured_html,
				array(
					'AMP_Img_Sanitizer' => array(),
					'AMP_Tag_And_Attribute_Sanitizer' => array(),
				),
				array(
					'content_max_width' => $this->get( 'content_max_width' ),
				)
			);
		}

		$this->add_data_by_key(
			'featured_image',
			array(
				'amp_html' => $sanitized_html,
			)
		);

		if ( isset( $featured_scripts ) && ! empty( $featured_scripts ) ) {
			$this->merge_data_for_key( 'amp_component_scripts', $featured_scripts );
		}

		if ( isset( $featured_styles ) && ! empty( $featured_styles ) ) {
			$this->merge_data_for_key( 'post_amp_styles', $featured_styles );
		}
	}

	/**
	 * Grabs featured image or the first attached image for the post
	 *
	 * TODO: move to a utils class?
	 */
	private function get_post_image_metadata() {
		$post_image_meta = null;
		$post_image_id = false;

		if ( has_post_thumbnail( $this->ID ) ) {
			$post_image_id = get_post_thumbnail_id( $this->ID );
		} else {
			$attached_image_ids = get_posts(
				array(
					'post_parent' => $this->ID,
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'posts_per_page' => 1,
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'fields' => 'ids',
					'suppress_filters' => false,
				)
			);

			if ( ! empty( $attached_image_ids ) ) {
				$post_image_id = array_shift( $attached_image_ids );
			}
		}

		if ( ! $post_image_id ) {
			return false;
		}

		$post_image_src = wp_get_attachment_image_src( $post_image_id, 'full' );

		if ( is_array( $post_image_src ) ) {
			$post_image_meta = array(
				'@type' => 'ImageObject',
				'url' => $post_image_src[0],
				'width' => $post_image_src[1],
				'height' => $post_image_src[2],
			);
		}

		return $post_image_meta;
	}

	private function build_html_tag_attributes() {
		$attributes = array();

		if ( function_exists( 'is_rtl' ) && is_rtl() ) {
			$attributes['dir'] = 'rtl';
		}

		$lang = get_bloginfo( 'language' );
		if ( $lang ) {
			$attributes['lang'] = $lang;
		}

		$this->add_data_by_key( 'html_tag_attributes', $attributes );
	}

	private function verify_and_include( $file, $template_type ) {
		$located_file = $this->locate_template( $file );
		if ( $located_file ) {
			$file = $located_file;
		}

		$file = apply_filters( 'amp_post_template_file', $file, $template_type, $this->post );
		if ( ! $this->is_valid_template( $file ) ) {
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Path validation for template (%1$s) failed. Path cannot traverse and must be located in `%2$s`.', 'kadence-amp' ), esc_html( $file ), 'WP_CONTENT_DIR' ), '0.1' );
			return;
		}

		do_action( 'amp_post_template_include_' . $template_type, $this );
		include( $file );
	}


	private function locate_template( $file ) {
		$search_file = sprintf( 'amp/%s', basename( $file ) );
		return locate_template( array( $search_file ), false );
	}

	private function is_valid_template( $template ) {
		if ( false !== strpos( $template, '..' ) ) {
			return false;
		}

		if ( false !== strpos( $template, './' ) ) {
			return false;
		}

		if ( ! file_exists( $template ) ) {
			return false;
		}

		return true;
	}
}
