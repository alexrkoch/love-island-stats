<?php
/**
 * Plugin Name: Kadence Simple Share
 * Plugin URI: https://www.kadencewp.com/
 * Description: Simple Sharing plugin, no tracking or loading of extra js
 * Version: 1.2.4
 * Author: Kadence WP
 * Author URI: https://www.kadencewp.com/
 * Text Domain: kadence-simple-share
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * Load Translation
 */
function kt_share_load_textdomain() {
	load_plugin_textdomain( 'kadence-simple-share', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'kt_share_load_textdomain' );


if ( ! defined( 'KTSS_PATH' ) ) {
	define( 'KTSS_PATH', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
}
if ( ! defined( 'KTSS_URL' ) ) {
	define( 'KTSS_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Main Class.
 */
class Kadence_Simple_Share {
	/**
	 * Construct class.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
		add_action( 'after_setup_theme', array( $this, 'kt_share_remove_woo' ) );
	}
	/**
	 * Get options and meta boxes.
	 */
	public function on_plugins_loaded() {
		require_once KTSS_PATH . '/admin/admin_options.php';
		require_once KTSS_PATH . '/admin/metaboxes.php';
	}
	/**
	 * Initiate everything.
	 */
	public function init() {
		add_action( 'get_header', array( $this, 'kadence_simple_share_content' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'kadence_simple_share_enqueue_scripts' ) );
		add_shortcode( 'kadence_simple_share', array( $this, 'kadence_simple_share_content_output_shortcode' ) );
		add_filter( 'get_the_excerpt', array( $this, 'kadence_simple_share_content_excerpt' ), 99 );
		// add_filter( 'cmb2_admin_init', array($this, 'kt_share_metaboxes') );
	}
	/**
	 * Get options and meta boxes.
	 */
	public function kadence_simple_share_content_excerpt( $excerpt ) {
		global $kt_share, $post;
		if ( ! isset( $kt_share ) || 'post' != get_post_type() ) {
			return $excerpt;
		}
		if ( isset( $kt_share['enable_posts_excerpts'] ) && 1 == $kt_share['enable_posts_excerpts'] ) {
			$show = get_post_meta( $post->ID, '_kt_share_control', true );
			if ( empty( $show ) || 'default' == $show || 'show' == $show ) {
				if ( isset( $kt_share['excerpt_sharing_location'] ) && 'both' == $kt_share['excerpt_sharing_location'] ) {
					$excerpt = '<div class="excerpt_sharing_before_output">' . $this->kadence_simple_share_content_output() . '</div>' . $excerpt . '<div class="excerpt_sharing_after_output">' . $this->kadence_simple_share_content_output() . '</div>';
				} else if ( isset( $kt_share['excerpt_sharing_location'] ) && 'after' == $kt_share['excerpt_sharing_location'] ) {
					$excerpt = $excerpt . '<div class="excerpt_sharing_after_output">' . $this->kadence_simple_share_content_output() . '</div>';
				} else {
					$excerpt = '<div class="excerpt_sharing_before_output">' . $this->kadence_simple_share_content_output() . '</div>' . $excerpt;
				}
			}
		}
		return $excerpt;
	}
	/**
	 * Check for Kadence standard Themes.
	 */
	public function kt_share_theme_is_kadence() {
		if ( class_exists( 'kt_api_manager' ) ) {
			return true;
		}
		return false;
	}
	/**
	 * Get options and meta boxes.
	 */
	public function kt_share_remove_woo() {
		if ( ! class_exists( 'Redux' ) ) {
			return;
		}
		if ( ! class_exists( 'woocommerce' ) ) {
			Redux::removefield( 'kt_share', 'enable_products' );
		}
	}
	public function kadence_simple_share_content() {
		global $kt_share, $post;
		if ( ! isset( $kt_share ) ) {
			die();
		}
		$post_type = get_post_type();
		$kadence = $this->kt_share_theme_is_kadence();
		if ( is_home() ) {
			$post_id = get_option( 'page_for_posts' );
		} else if ( isset( $post ) ) {
			$post_id = $post->ID;
		}
		if ( isset( $post_id ) ) {
			$show = get_post_meta( $post_id, '_kt_share_control', true );
		}
		if ( $kadence ) {
			if ( $post_type == 'post' ) {
				if ( empty( $show ) || $show == 'default' ) {
					if ( isset( $kt_share['enable_posts'] ) && $kt_share['enable_posts'] == 1 ) {
						$show = 'show';
					} else {
						$show = 'hide';
					}
				}
				if ( $show == 'show' ) {
					if ( isset( $kt_share['sharing_location'] ) ) {
						if ( $kt_share['sharing_location'] == 'both' ) {
							add_action( 'kadence_single_post_content_before', array( $this, 'kadence_simple_share_content_output_before' ) );
							add_action( 'kadence_single_post_content_after', array( $this, 'kadence_simple_share_content_output_after' ) );
						} elseif ( $kt_share['sharing_location'] == 'after' ) {
							add_action( 'kadence_single_post_content_after', array( $this, 'kadence_simple_share_content_output_after' ) );
						} else {
							add_action( 'kadence_single_post_content_before', array( $this, 'kadence_simple_share_content_output_before' ) );
						}
					} else {
						add_action( 'kadence_single_post_content_before', array( $this, 'kadence_simple_share_content_output_before' ) );
					}
				}
			} else if ( $post_type == 'portfolio' ) {
				if ( empty( $show ) || $show == 'default' ) {
					if ( isset( $kt_share['enable_portfolio'] ) && $kt_share['enable_portfolio'] == 1 ) {
						$show = 'show';
					} else {
						$show = 'hide';
					}
				}
				if ( $show == 'show' ) {
					if ( isset( $kt_share['sharing_location'] ) ) {
						if ( $kt_share['sharing_location'] == 'both' ) {
							add_action( 'kadence_single_portfolio_before_content', array( $this, 'kadence_simple_share_content_output_before' ) );
							add_action( 'kadence_single_portfolio_after_content', array( $this, 'kadence_simple_share_content_output_after' ) );
						} elseif ( $kt_share['sharing_location'] == 'after' ) {
							add_action( 'kadence_single_portfolio_after_content', array( $this, 'kadence_simple_share_content_output_after' ) );
						} else {
							add_action( 'kadence_single_portfolio_before_content', array( $this, 'kadence_simple_share_content_output_before' ) );
						}
					} else {
						add_action( 'kadence_single_portfolio_before_content', array( $this, 'kadence_simple_share_content_output_before' ) );
					}
				}
			} else if ( $post_type == 'product' ) {
				if ( empty( $show ) || $show == 'default' ) {
					if ( isset( $kt_share['enable_products'] ) && $kt_share['enable_products'] == 1 ) {
						$show = 'show';
					} else {
						$show = 'hide';
					}
				}
				if ( $show == 'show' ) {
							add_action( 'woocommerce_share', array( $this, 'kadence_simple_share_content_output_after' ), 10 );
				}
			} else if ( $post_type == 'page' ) {
				if ( empty( $show ) || $show == 'default' ) {
					if ( isset( $kt_share['enable_pages'] ) && $kt_share['enable_pages'] == 1 ) {
						$show = 'show';
					} else {
						$show = 'hide';
					}
				}
				if ( $show == 'show' ) {
					if ( isset( $kt_share['sharing_location'] ) ) {
						if ( $kt_share['sharing_location'] == 'both' ) {
							add_action( 'kadence_page_content_before', array( $this, 'kadence_simple_share_content_output_before' ) );
							add_action( 'kadence_page_footer', array( $this, 'kadence_simple_share_content_output_after' ), 10 );
						} elseif ( $kt_share['sharing_location'] == 'after' ) {
							add_action( 'kadence_page_footer', array( $this, 'kadence_simple_share_content_output_after' ), 10 );
						} else {
							add_action( 'kadence_page_content_before', array( $this, 'kadence_simple_share_content_output_before' ) );
						}
					} else {
						add_action( 'kadence_page_content_before', array( $this, 'kadence_simple_share_content_output_before' ) );
					}
				}
			}
		} else {
			if ( $post_type == 'post' ) {
				if ( empty( $show ) || $show == 'default' ) {
					if ( isset( $kt_share['enable_posts'] ) && $kt_share['enable_posts'] == 1 ) {
						$show = 'show';
					} else {
						$show = 'hide';
					}
				}
				if ( $show == 'show' ) {
					if ( isset( $kt_share['sharing_location'] ) ) {
						if ( $kt_share['sharing_location'] == 'both' ) {
							add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_before' ), 5 );
							add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_after' ), 100 );
						} elseif ( $kt_share['sharing_location'] == 'after' ) {
							add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_after' ), 100 );
						} else {
							add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_before' ), 5 );
						}
					} else {
						add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_before' ), 5 );
					}
				}
			} else if ( $post_type == 'product' ) {
				if ( empty( $show ) || $show == 'default' ) {
					if ( isset( $kt_share['enable_products'] ) && $kt_share['enable_products'] == 1 ) {
						$show = 'show';
					} else {
						$show = 'hide';
					}
				}
				if ( $show == 'show' ) {
							add_action( 'woocommerce_share', array( $this, 'kadence_simple_share_content_output_after' ), 10 );
				}
			} else if ( $post_type == 'page' ) {
				if ( empty( $show ) || $show == 'default' ) {
					if ( isset( $kt_share['enable_pages'] ) && $kt_share['enable_pages'] == 1 ) {
						$show = 'show';
					} else {
						$show = 'hide';
					}
				}
				if ( $show == 'show' ) {
					if ( isset( $kt_share['sharing_location'] ) ) {
						if ( $kt_share['sharing_location'] == 'both' ) {
							add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_before' ), 5 );
							add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_after' ), 100 );
						} elseif ( $kt_share['sharing_location'] == 'after' ) {
							add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_after' ), 100 );
						} else {
							add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_before' ), 5 );
						}
					} else {
						add_filter( 'the_content', array( $this, 'kadence_simple_share_content_filter_output_before' ), 5 );
					}
				}
			}
		}
	}
	public function kadence_simple_share_content_output() {
		global $kt_share;
		if ( isset( $kt_share['enabled_sharing']['enabled'] ) ) {
			$enabled_sharing = $kt_share['enabled_sharing']['enabled'];
		} else {
			$enabled_sharing = array(
				'facebook' => 'facebook',
				'twitter' => 'twitter',
			);
		}
		if ( isset( $kt_share['sharing_align'] ) ) {
			$align = $kt_share['sharing_align'];
		} else {
			$align = 'left';
		}
		if ( isset( $kt_share['sharing_style'] ) ) {
			$style = $kt_share['sharing_style'];
		} else {
			$style = 'style_01';
		}
		if ( isset( $kt_share['sharing_size'] ) ) {
			$size = $kt_share['sharing_size'];
		} else {
			$size = 'normal';
		}
		if ( isset( $kt_share['sharing_text'] ) && ! empty( $kt_share['sharing_text'] ) ) {
			$sharing_text = '<div class="kt_before_share_text">' . $kt_share['sharing_text'] . '</div>';
		} else {
			$sharing_text = '';
		}
		$tooltip = false;
		$tool_text = null;
		$tool_align = null;
		if ( isset( $kt_share['enable_tooltip'] ) && $kt_share['enable_tooltip'] == 1 ) {
			$tooltip = true;
			if ( isset( $kt_share['tooltip_align'] ) && ! empty( $kt_share['tooltip_align'] ) ) {
				$tool_align = $kt_share['tooltip_align'];
			} else {
				$tool_align = 'bottom';
			}
			if ( isset( $kt_share['tooltip_text'] ) ) {
				$tool_text = $kt_share['tooltip_text'];
			} else {
				$tool_text = 'Share on';
			}
		}
		$kt_share_output = null;
		if ( $enabled_sharing ) :
			$kt_share_output .= '<div class="kt_simple_share_container kt-social-align-' . $align . ' kt-socialstyle-' . $style . ' kt-social-size-' . $size . '">';
			$kt_share_output .= $sharing_text;
			foreach ( $enabled_sharing as $key => $value ) {
				switch ( $key ) {
					case 'facebook':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_facebook_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_facebook();
						}
						break;
					case 'twitter':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_twitter_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_twitter();
						}
						break;
					case 'xing':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_xing_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_xing();
						}
						break;
					case 'whatsapp':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_whatsapp_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_whatsapp();
						}
						break;
					case 'linkedin':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_linkedin_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_linkedin();
						}
						break;
					case 'tumblr':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_tumblr_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_tumblr();
						}
						break;
					case 'digg':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_digg_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_digg();
						}
						break;
					case 'vk':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_vk_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_vk();
						}
						break;
					case 'stumbleupon':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_stumbleupon_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_stumbleupon();
						}
						break;
					case 'pinterest':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_pinterest_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_pinterest();
						}
						break;
					case 'reddit':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_reddit_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_reddit();
						}
						break;
					case 'email':
						if ( $tooltip ) {
							$kt_share_output .= $this->kt_share_email_tooltip( $tool_align, $tool_text );
						} else {
							$kt_share_output .= $this->kt_share_email();
						}
						break;
				}
			}
			$kt_share_output .= '</div>';
			endif;

		return $kt_share_output;
	}
	public function kadence_simple_share_content_output_after() {
		echo '<div class="kt_simple_share_container kt_share_aftercontent">' . $this->kadence_simple_share_content_output() . '</div>';
	}
	public function kadence_simple_share_content_filter_output_after( $content ) {
		if ( doing_filter( 'get_the_excerpt' ) ) {
			return $content;
		}
		return $content . '<div class="kt_simple_share_container kt_share_aftercontent">' . $this->kadence_simple_share_content_output() . '</div>';
	}
	public function kadence_simple_share_content_output_before() {
		echo '<div class="kt_simple_share_container kt_share_beforecontent">' . $this->kadence_simple_share_content_output() . '</div>';
	}
	public function kadence_simple_share_content_filter_output_before( $content ) {
		if ( doing_filter( 'get_the_excerpt' ) ) {
			return $content;
		}
		return '<div class="kt_simple_share_container kt_share_beforecontent">' . $this->kadence_simple_share_content_output() . '</div>' . $content;
	}
	public function kadence_simple_share_content_output_shortcode() {
		return '<div class="kt_simple_share_container kt_share_shortcode">' . $this->kadence_simple_share_content_output() . '</div>';
	}
	public function kt_share_facebook() {
		return '<a class="kt_facebook_share" href="http://www.facebook.com/sharer.php?u=' . get_permalink() . '" target="_blank"><i class="kts-iconfacebook"></i></a>';
	}
	public function kt_share_facebook_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_facebook_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' Facebook" data-tooltip="' . esc_attr( $tool_text ) . ' Facebook" href="http://www.facebook.com/sharer.php?u=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconfacebook"></i></a>';
	}
	public function kt_share_twitter() {
		$twitter_title = urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );
		return '<a class="kt_twitter_share" href="http://twitter.com/share?url=' . get_permalink() . '&text=' . esc_attr( $twitter_title ) . '" target="_blank"><i class="kts-icontwitter"></i></a>';
	}
	public function kt_share_twitter_tooltip( $tool_align, $tool_text ) {
		$twitter_title = urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );
		return '<a class="kt_twitter_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' Twitter" data-tooltip="' . esc_attr( $tool_text ) . ' Twitter" href="http://twitter.com/share?url=' . esc_attr( get_permalink() ) . '&text=' . esc_attr( $twitter_title ) . '" target="_blank"><i class="kts-icontwitter"></i></a>';
	}
	public function kt_share_xing() {
		return '<a class="kt_xing_share" href="https://www.xing.com/social_plugins/share/new?url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconxing"></i></a>';
	}
	public function kt_share_xing_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_xing_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' XING" data-tooltip="' . esc_attr( $tool_text ) . ' XING" href="https://www.xing.com/social_plugins/share/new?url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconxing"></i></a>';
	}
	public function kt_share_whatsapp() {
		return '<a class="kt_whatsapp_share" href="whatsapp://send?text=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconwhatsapp"></i></a>';
	}
	public function kt_share_whatsapp_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_whatsapp_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' WhatsApp" data-tooltip="' . esc_attr( $tool_text ) . ' WhatsApp" href="whatsapp://send?text=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconwhatsapp"></i></a>';
	}
	public function kt_share_vk() {
		return '<a class="kt_vk_share" href="http://vkontakte.ru/share.php?url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconvk"></i></a>';
	}
	public function kt_share_vk_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_vk_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' VK" data-tooltip="' . esc_attr( $tool_text ) . ' VK" href="http://vkontakte.ru/share.php?url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconvk"></i></a>';
	}
	public function kt_share_linkedin() {
		return '<a class="kt_linkedin_share" href="http://www.linkedin.com/shareArticle?mini=true&url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconlinkedin2"></i></a>';
	}
	public function kt_share_linkedin_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_linkedin_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' LinkedIn" data-tooltip="' . esc_attr( $tool_text ) . ' LinkedIn" href="http://www.linkedin.com/shareArticle?mini=true&url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconlinkedin2"></i></a>';
	}
	public function kt_share_tumblr() {
		return '<a class="kt_tumblr_share" href="http://www.tumblr.com/share/link?url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-icontumblr"></i></a>';
	}
	public function kt_share_tumblr_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_tumblr_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' Tumblr" data-tooltip="' . esc_attr( $tool_text ) . ' Tumblr" href="http://www.tumblr.com/share/link?url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-icontumblr"></i></a>';
	}
	public function kt_share_stumbleupon() {
		return '<a class="kt_stumbleupon_share" href="http://www.stumbleupon.com/submit?url=' . esc_attr( get_permalink() ) . '&amp;title=' . esc_attr( get_the_title() ) . '" target="_blank"><i class="kts-iconstumbleupon"></i></a>';
	}
	public function kt_share_stumbleupon_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_stumbleupon_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' StumbleUpon" data-tooltip="' . esc_attr( $tool_text ) . ' StumbleUpon" href="http://www.stumbleupon.com/submit?url=' . esc_attr( get_permalink() ) . '&amp;title=' . esc_attr( get_the_title() ) . '" target="_blank"><i class="kts-iconstumbleupon"></i></a>';
	}
	public function kt_share_digg() {
		return '<a class="kt_digg_share" href="http://www.digg.com/submit?url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-icondigg"></i></a>';
	}
	public function kt_share_digg_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_digg_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' Digg" data-tooltip="' . esc_attr( $tool_text ) . ' Digg" href="http://www.digg.com/submit?url=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-icondigg"></i></a>';
	}
	public function kt_share_reddit() {
		return '<a class="kt_reddit_share" href="http://reddit.com/submit?url=' . esc_attr( get_permalink() ) . '&amp;title=' . esc_attr( get_the_title() ) . '" target="_blank"><i class="kts-icon-reddit"></i></a>';
	}
	public function kt_share_reddit_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_reddit_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' Reddit" data-tooltip="' . esc_attr( $tool_text ) . ' Reddit" href="http://reddit.com/submit?url=' . esc_attr( get_permalink() ) . '&amp;title=' . esc_attr( get_the_title() ) . '" target="_blank"><i class="kts-icon-reddit"></i></a>';
	}
	public function kt_share_pinterest() {
		global $post, $kt_share;
		if ( has_post_thumbnail( $post->ID ) && 1 == $kt_share['pinterest_featured'] ) {
			// Get the featured image.
			$url_post_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$url_post_thumb = $url_post_thumb[0];
						// Pinterest share link.
			return '<a data-site="pinterest-featured" href="http://pinterest.com/pin/create/bookmarklet/?is_video=false&url=' . esc_attr( get_permalink() ) . '&media=' . esc_attr( $url_post_thumb ) . '&description=' . esc_attr( get_the_title() ) . '" target="_blank" class="kt_pinterest_share"><i class="kts-icon-pinterest"></i></a>';
		} else { // No featured image set.
			return "<a class='kt_pinterest_share kt_no_pop_window' href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;//assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());'><i class='kts-icon-pinterest'></i></a>";
		}
	}
	/**
	 * Pinterest tooltip.
	 *
	 * @param string $tool_align the alignment.
	 * @param string $tool_text the text align.
	 */
	public function kt_share_pinterest_tooltip( $tool_align, $tool_text ) {
		global $post, $kt_share;
		if ( has_post_thumbnail( $post->ID ) && 1 == $kt_share['pinterest_featured'] ) {
			// Get the featured image.
			$url_post_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$url_post_thumb = $url_post_thumb[0];
						// Pinterest share link.
			return '<a data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' Pinterest" data-tooltip="' . esc_attr( $tool_text ) . ' Pinterest" href="http://pinterest.com/pin/create/bookmarklet/?is_video=false&url=' . esc_attr( get_permalink() ) . '&media=' . esc_attr( $url_post_thumb ) . '&description=' . esc_attr( get_the_title() ) . '" target="_blank" class="kt_pinterest_share"><i class="kts-icon-pinterest"></i></a>';
		} else { // No featured image set.
			return "<a class='kt_pinterest_share kt_no_pop_window' data-toggle='tooltip' data-placement='" . esc_attr( $tool_align ) . "' aria-label='" . esc_attr( $tool_text ) . " Pinterest' data-tooltip='" . esc_attr( $tool_text ) . " Pinterest' href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;//assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());'><i class='kts-icon-pinterest'></i></a>";
		}
	}
	public function kt_share_email() {
		return '<a class="kt_email_share" href="mailto:?subject=' . esc_attr( get_the_title() ) . '&body=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconenvelop4"></i></a>';
	}
	public function kt_share_email_tooltip( $tool_align, $tool_text ) {
		return '<a class="kt_email_share" data-placement="' . esc_attr( $tool_align ) . '" aria-label="' . esc_attr( $tool_text ) . ' Email" data-tooltip="' . esc_attr( $tool_text ) . ' Email" href="mailto:?subject=' . esc_attr( get_the_title() ) . '&body=' . esc_attr( get_permalink() ) . '" target="_blank"><i class="kts-iconenvelop4"></i></a>';
	}
	public function kadence_simple_share_enqueue_scripts() {
		global $kt_share;
		wp_enqueue_style( 'kadence_share_css', KTSS_URL . 'assets/css/kt-social.css', false, '127' );
		if ( isset( $kt_share['sharing_link_exit'] ) && 'popup' === $kt_share['sharing_link_exit'] ) {
			wp_enqueue_script( 'kadence_share_js', KTSS_URL . 'assets/js/kt-social-min.js', array(), 127, true );
		}
	}

}

$GLOBALS['kadence_simple_share'] = new Kadence_Simple_Share();

/* Updater */
add_action( 'after_setup_theme', 'kadence_simple_share_updating', 1 );
function kadence_simple_share_updating() {
	require_once 'wp-updates-plugin.php';
	$kadence_simple_share_updater = new PluginUpdateChecker_2_0( 'https://kernl.us/api/v1/updates/57a37cbe1d258384118780a6/', __FILE__, 'kadence-simple-share', 1 );
}
