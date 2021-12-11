<?php 
/**
 * Get options setup
 *
 * @package Kadence AMP
 */

const ICON_BASE64_SVG = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB3aWR0aD0iNjJweCIgaGVpZ2h0PSI2MnB4IiB2aWV3Qm94PSIwIDAgNjIgNjIiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+ICAgICAgICA8dGl0bGU+QU1QLUJyYW5kLUJsYWNrLUljb248L3RpdGxlPiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4gICAgPGRlZnM+PC9kZWZzPiAgICA8ZyBpZD0iYW1wLWxvZ28taW50ZXJuYWwtc2l0ZSIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+ICAgICAgICA8ZyBpZD0iQU1QLUJyYW5kLUJsYWNrLUljb24iIGZpbGw9IiMwMDAwMDAiPiAgICAgICAgICAgIDxwYXRoIGQ9Ik00MS42Mjg4NjY3LDI4LjE2MTQzMzMgTDI4LjYyNDM2NjcsNDkuODAzNTY2NyBMMjYuMjY4MzY2Nyw0OS44MDM1NjY3IEwyOC41OTc1LDM1LjcwMTY2NjcgTDIxLjM4MzgsMzUuNzEwOTY2NyBDMjEuMzgzOCwzNS43MTA5NjY3IDIxLjMxNTYsMzUuNzEzMDMzMyAyMS4yODM1NjY3LDM1LjcxMzAzMzMgQzIwLjYzMzYsMzUuNzEzMDMzMyAyMC4xMDc2MzMzLDM1LjE4NzA2NjcgMjAuMTA3NjMzMywzNC41MzcxIEMyMC4xMDc2MzMzLDM0LjI1ODEgMjAuMzY3LDMzLjc4NTg2NjcgMjAuMzY3LDMzLjc4NTg2NjcgTDMzLjMyOTEzMzMsMTIuMTY5NTY2NyBMMzUuNzI0NCwxMi4xNzk5IEwzMy4zMzYzNjY3LDI2LjMwMzUgTDQwLjU4NzI2NjcsMjYuMjk0MiBDNDAuNTg3MjY2NywyNi4yOTQyIDQwLjY2NDc2NjcsMjYuMjkzMTY2NyA0MC43MDE5NjY3LDI2LjI5MzE2NjcgQzQxLjM1MTkzMzMsMjYuMjkzMTY2NyA0MS44Nzc5LDI2LjgxOTEzMzMgNDEuODc3OSwyNy40NjkxIEM0MS44Nzc5LDI3LjczMjYgNDEuNzc0NTY2NywyNy45NjQwNjY3IDQxLjYyNzgzMzMsMjguMTYwNCBMNDEuNjI4ODY2NywyOC4xNjE0MzMzIFogTTMxLDAgQzEzLjg3ODcsMCAwLDEzLjg3OTczMzMgMCwzMSBDMCw0OC4xMjEzIDEzLjg3ODcsNjIgMzEsNjIgQzQ4LjEyMDI2NjcsNjIgNjIsNDguMTIxMyA2MiwzMSBDNjIsMTMuODc5NzMzMyA0OC4xMjAyNjY3LDAgMzEsMCBMMzEsMCBaIiBpZD0iRmlsbC0xIj48L3BhdGg+ICAgICAgICA8L2c+ICAgIDwvZz48L3N2Zz4=';

/**
 * Load Redux if needed
 */
function kadence_amp_run_redux() {
	if ( class_exists( 'Redux' ) ) {
		return;
	}
	require_once KADENCE_AMP_PATH . 'admin/redux/framework.php';
}
add_action( 'after_setup_theme', 'kadence_amp_run_redux', 1 );

/**
 * Add redux sections
 */
function kadence_amp_add_sections() {
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}
	$args = array(
		'public' => true,
	);
	$post_types = get_post_types( $args );
	$all_post_types = array();
	foreach ( $post_types  as $post_type ) {
		if ( 'kt_gallery' !== $post_type && 'kad_slider' !== $post_type && 'attachment' !== $post_type ) {
			array_push( $all_post_types, $post_type );
		}
	}
	$opt_name = 'kadence_amp';

	$args = array(
		'opt_name'             => $opt_name,
		'display_name'         => 'Kadence AMP',
		'display_version'      => '',
		'menu_type'            => 'menu',
		'allow_sub_menu'       => true,
		'menu_title'           => __( 'AMP Settings', 'kadence-amp' ),
		'page_title'           => __( 'Kadence AMP Settings', 'kadence-amp' ),
		'google_api_key'       => 'AIzaSyALkgUvb8LFAmrsczX56ZGJx-PPPpwMid0',
		'google_update_weekly' => false,
		'async_typography'     => false,
		'admin_bar'            => false,
		'dev_mode'             => false,
		'use_cdn'              => false,
		'update_notice'        => false,
		'customizer'           => false,
		'forced_dev_mode_off'  => true,
		'page_permissions'     => 'edit_pages',
		'menu_icon'            => ICON_BASE64_SVG,
		'show_import_export'   => false,
		'save_defaults'        => true,
		'show_options_object'  => false,
		'page_slug'            => 'kampoptions',
		'ajax_save'            => true,
		'default_show'         => false,
		'default_mark'         => '',
		'footer_credit'        => __( 'Thank you for using the Kadence AMP by <a href="http://kadencethemes.com/" target="_blank">Kadence Themes</a>.', 'kadence-amp' ),
		'hints'                => array(
			'icon'          => 'kt-icon-question',
			'icon_position' => 'right',
			'icon_color'    => '#444',
			'icon_size'     => 'normal',
			'tip_style'     => array(
				'color'   => 'dark',
				'shadow'  => true,
				'rounded' => false,
				'style'   => '',
			),
			'tip_position'  => array(
				'my' => 'top left',
				'at' => 'bottom right',
			),
			'tip_effect'    => array(
				'show' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'mouseover',
				),
				'hide' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'click mouseleave',
				),
			),
		),
	);

	$args['share_icons'][] = array(
		'url' => 'https://www.facebook.com/KadenceWP',
		'title' => 'Follow Kadence WP on Facebook', 
		'icon' => 'dashicons dashicons-facebook',
	);
	$args['share_icons'][] = array(
		'url' => 'https://www.twitter.com/KadenceWP',
		'title' => 'Follow Kadence WP on Twitter', 
		'icon' => 'dashicons dashicons-twitter',
	);
	$args['share_icons'][] = array(
		'url' => 'https://www.instagram.com/KadenceWP',
		'title' => 'Follow Kadence WP on Instagram', 
		'icon' => 'dashicons dashicons-format-image',
	);
	$args['share_icons'][] = array(
		'url' => 'http://www.youtube.com/c/KadenceWP',
		'title' => 'Follow Kadence WP on YouTube', 
		'icon' => 'dashicons dashicons-video-alt3',
	);

	Redux::setArgs( $opt_name, $args );
	Redux::setSection( $opt_name,
		array(
			'icon'       => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id'         => 'kt_amp_general',
			'title'      => __( 'AMP General Settings', 'kadence-amp' ),
			'desc'       => '',
			'fields'     => array(
				array(
					'id'       => 'post_types',
					'type'     => 'select',
					'multi'	   => true,
					'title'    => __( 'Enable AMP for the following post types', 'kadence-amp' ),
					'data'     => 'post_types',
					'default' => array( 'post' ),
				),
				array(
					'id'=>'google_analytics',
					'type' => 'text', 
					'title' => __( 'Google Analytics Tracking ID', 'kadence-amp' ),
					'subtitle' => __( 'Example UA-XXXX-Y', 'kadence-amp' ),
					'default' => '',
				),
			),
		) 
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_header',
			'title' => __( 'AMP Header', 'kadence-amp' ),
			'desc' => '',
			'fields' => array(
				array(
					'id'=>'head_bg',
					'type' => 'color',
					'title' => __( 'Header Background Color', 'kadence-amp' ), 
					'default' => '#ffffff',
					'transparent'=>false,
					'validate' => 'color',
				),
				array(
					'id'=>'info_amp_logo',
					'type' => 'info',
					'desc' => __( 'AMP Logo', 'kadence-amp' ),
				),
				array(
					'id'=>'logo_style',
					'type' => 'select', 
					'title' => __( 'Logo Type', 'kadence-amp' ),
					'default' => 'text',
					'width' => 'width:60%',
					'options' => array('text' => __( 'Text', 'kadence-amp' ), 'image' => __( 'Image', 'kadence-amp' ), 'image_text' => __( 'Image and text', 'kadence-amp' )),
				),
				array(
					'id'=>'logo_image',
					'type' => 'media', 
					'url' => true,
					'title' => __( 'Logo Image', 'kadence-amp' ),
					'subtitle' => __( 'The image will be resized to have a max height of 32px but will not be cropped', 'kadence-amp' ),
					'required' => array('logo_style','!=','text' ),
				),
				array(
					'id'=>'logo_text',
					'type' => 'text', 
					'title' => __( 'Logo Text', 'kadence-amp' ),
					'default' => '',
					'required' => array('logo_style','!=','image' ),
				),
				array(
					'id'=>'logo_text_font',
					'type' => 'typography', 
					'title' => __( 'Logo Text Font', 'kadence-amp' ),
					'font-family'=>true, 
					'google'=>true, 
					'font-backup'=>false,
					'font-style'=>true,
					'subsets'=>true, 
					'font-size'=>false,
					'line-height'=>false,
					'letter-spacing' => true,
					'text-align' => false,
					'color'=>true,
					'preview'=>true,
					'subtitle'=> __('Choose size and style your site title', 'kadence-amp' ),
					'default'=> array(
						'font-family'=>'Source Sans Pro',
						'color'=>'#444444', 
						'font-style'=>'400',
						'letter-spacing'=>'0px',
					),
					'required' => array('logo_style','!=','image' ),
				),
				array(
					'id'=>'info_amp_menu',
					'type' => 'info',
					'desc' => __( 'AMP Menu Button', 'kadence-amp' ),
				),
				array(
					'id'=>'menu_style',
					'type' => 'select', 
					'title' => __( 'Menu Button Type', 'kadence-amp' ),
					'default' => 'icon',
					'width' => 'width:60%',
					'options' => array('icon' => __( 'Icon', 'kadence-amp' ), 'text' => __( 'Text', 'kadence-amp' )),
				),
				array(
					'id'=>'menu_icon_color',
					'type' => 'color',
					'title' => __( 'Menu Button Icon Color', 'kadence-amp' ), 
					'default' => '#444444',
					'transparent'=>false,
					'validate' => 'color',
					'required' => array('menu_style','!=','text' ),
				),
				array(
					'id'=>'menu_text',
					'type' => 'text', 
					'title' => __( 'Menu Button Text', 'kadence-amp' ),
					'default' => __( 'Menu', 'kadence-amp' ),
					'required' => array('menu_style','!=','icon' ),
				),
				array(
					'id'=>'menu_text_font',
					'type' => 'typography', 
					'title' => __( 'Menu Button Text Font', 'kadence-amp' ),
					'font-family'=>true, 
					'google'=>true, 
					'font-backup'=>false,
					'font-style'=>true,
					'subsets'=>true, 
					'font-size'=>false,
					'line-height'=>false,
					'letter-spacing' => true,
					'text-align' => false,
					'color'=>true,
					'preview'=>true,
					'subtitle'=> __('Choose style your Menu Button text', 'kadence-amp' ),
					'default'=> array(
						'font-family'=>'Source Sans Pro',
						'color'=>'#444444', 
						'font-style'=>'600',
						'letter-spacing'=>'0px',
					),
					'required' => array('menu_style','!=','icon' ),
				),
				array(
					'id'=>'info_amp_menu_popout',
					'type' => 'info',
					'desc' => __( 'AMP Menu - Make sure to assign a menu to your AMP navigation in your apperance > menus page', 'kadence-amp' ),
					'subtitle' => __( 'AMP Menu - Make sure to assign a menu to your AMP navigation in your apperance > menus page', 'kadence-amp' ),
				),
				array(
					'id'=>'menu_bg_color',
					'type' => 'color',
					'title' => __( 'Menu Popout Background Color', 'kadence-amp' ), 
					'default' => '#444444',
					'transparent'=>false,
					'validate' => 'color',
				),
				array(
					'id'=>'menu_font',
					'type' => 'typography', 
					'title' => __( 'Menu Popout Font', 'kadence-amp' ),
					'font-family'=>true, 
					'google'=>true, 
					'font-backup'=>false,
					'font-style'=>true,
					'subsets'=>true, 
					'font-size'=>false,
					'line-height'=>false,
					'letter-spacing' => true,
					'text-align' => false,
					'color'=>true,
					'preview'=>true,
					'subtitle'=> __('Choose style your popout menu', 'kadence-amp' ),
					'default'=> array(
						'font-family'=>'Source Sans Pro',
						'color'=>'#ffffff', 
						'font-style'=>'400',
						'letter-spacing'=>'0px',
					),
				),
			),
		) 
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_body',
			'title' => __( 'AMP Body', 'kadence-amp' ),
			'desc' => '',
			'fields' => array(
				array(
					'id'=>'highlight_color',
					'type' => 'color',
					'title' => __( 'Highlight Color', 'kadence-amp' ), 
					'subtitle'=> __('Choose the color for links and buttons', 'kadence-amp' ),
					'default' => '#ce534d',
					'transparent' => false,
					'validate' => 'color',
				),
				array(
					'id'=>'body_bg',
					'type' => 'color',
					'title' => __( 'Body Background Color', 'kadence-amp' ), 
					'default' => '#ffffff',
					'transparent'=>false,
					'validate' => 'color',
				),
				array(
					'id'=>'h_font',
					'type' => 'typography', 
					'title' => __( 'h1, h2, h3, h4, h5, h6 font family', 'kadence-amp' ),
					'font-family'=>true, 
					'google'=>true, 
					'font-backup'=>false,
					'font-style'=>true,
					'subsets'=>true, 
					'font-size'=>false,
					'line-height'=>false,
					'letter-spacing' => true,
					'text-align' => false,
					'color'=>true,
					'preview'=>true,
					'subtitle'=> __('Choose style your body font', 'kadence-amp' ),
					'default'=> array(
						'font-family'=>'Source Sans Pro',
						'color'=>"#444444", 
						'font-style'=>'400',
						'letter-spacing'=>'0px',
					),
				),
				array(
					'id'=>'body_font',
					'type' => 'typography', 
					'title' => __( 'Body font', 'kadence-amp' ),
					'font-family'=>true, 
					'google'=>true, 
					'font-backup'=>false,
					'font-style'=>true,
					'subsets'=>true, 
					'font-size'=>false,
					'line-height'=>false,
					'letter-spacing' => true,
					'text-align' => false,
					'color'=>true,
					'preview'=>true,
					'subtitle'=> __("Choose style your body font", 'kadence-amp' ),
					'default'=> array(
						'font-family'=>'Source Sans Pro',
						'color'=>"#444444", 
						'font-style'=>'400',
						'letter-spacing'=>'0px',
					),
				),
				array(
					'id'=>'muted_text_color',
					'type' => 'color',
					'title' => __( 'Muted text color', 'kadence-amp' ), 
					'subtitle'=> __("Font color for meta content like category, tags etc.", 'kadence-amp' ),
					'default' => '#777',
					'transparent' => false,
					'validate' => 'color',
				),
			),
		) 
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_footer',
			'title' => __( 'AMP Footer', 'kadence-amp' ),
			'desc' => "",
			'fields' => array(
				array(
					'id'=>'footer_bg',
					'type' => 'color',
					'title' => __( 'Footer Background Color', 'kadence-amp' ), 
					'default' => '#444',
					'transparent'=> false,
					'validate' => 'color',
				),
				array(
					'id'=>'footer_font',
					'type' => 'typography', 
					'title' => __( 'Footer font', 'kadence-amp' ),
					'font-family'=>false, 
					'google'=>false, 
					'font-backup'=>false,
					'font-style'=>false,
					'subsets'=>false, 
					'font-size'=>true,
					'line-height' => true,
					'letter-spacing' => false,
					'text-align' => false,
					'color' => true,
					'preview' => true,
					'subtitle'=> __("Choose color and size for the footer font", 'kadence-amp' ),
					'default'=> array(
						'color'=>"#ffffff",
						'font-style'=>'400',
						'font-size'=>'12px',
						'line-height'=>'20px',
					),
				),
				array(
					'id'=>'footer_text',
					'type' => 'textarea',
					'title' => __( 'Footer Content', 'kadence-amp' ), 
					'subtitle' => __( 'Write your own copyright text here. You can use the following shortcodes in your footer text: [copyright] [site-name] [the-year]', 'kadence-amp' ),
					'default' => '[copyright] [the-year] [site-name]',
				),
				array(
					'id' => 'show_back_to_top',
					'type' => 'switch', 
					'title' => __( 'Show back to top link', 'kadence-amp' ),
					"default" => 1,
				),
				array(
					'id'=>'back_to_top_text',
					'type' => 'text',
					'title' => __( 'Back to top Text', 'kadence-amp' ), 
					'default' => __( 'Back to top', 'kadence-amp' ),
				),
				array(
					'id' => 'show_original',
					'type' => 'switch', 
					'title' => __( 'Show "View Original Version" link', 'kadence-amp' ),
					"default" => 1,
				),
				array(
					'id'=>'show_original_text',
					'type' => 'text',
					'title' => __( 'View Original Version Text', 'kadence-amp' ), 
					'default' => __( 'View original version', 'kadence-amp' ),
				),
			),
		) 
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_post',
			'title' => __( 'AMP Single Post', 'kadence-amp' ),
			'desc' => "",
			'fields' => array(
				array(
					'id'       => 'post_layout',
					'type'     => 'sortable',
					'title'    => __( 'AMP Post Template', 'kadence-amp' ),
					'subtitle' => __( 'Check box to enable and sort for output order (Top First).', 'kadence-amp' ),
					'mode'     => 'checkbox',
					'options'  => array(
						"ad_one"  			=> __("Ad Block #1", 'kadence-amp' ),
						"breadcrumbs"   	=> __("Breadcrumbs", 'kadence-amp' ),
						"original_button"	=> __("Button to original version", 'kadence-amp' ),
						"feature"  			=> __("Post Head Feature", 'kadence-amp' ),
						"title"  			=> __("Title", 'kadence-amp' ),
						"meta"   			=> __("Meta Block", 'kadence-amp' ),
						"content" 			=> __("Post Content", 'kadence-amp' ),
						"categories"		=> __("Categories", 'kadence-amp' ),
						"tags"				=> __("Tags", 'kadence-amp' ),
						"share"				=> __("Share Buttons", 'kadence-amp' ),
						"comments" 			=> __("Post Comments", 'kadence-amp' ),
						"related"			=> __("Related Posts", 'kadence-amp' ),
						"ad_two"			=> __("Ad Block #2", 'kadence-amp' ),
					),
					'default' => array(
						"ad_one"  			=> false,
						"breadcrumbs"   	=> false,
						"original_button"	=> false,
						"feature"  			=> true,
						"title"  			=> true,
						"meta"   			=> true,
						"content" 			=> true,
						"categories"		=> true,
						"tags"				=> true,
						"share"				=> true,
						"comments" 			=> true,
						"related"			=> true,
						"ad_two"			=> false,
					),
				),
				array(
					'id'=>'info_amp_meta_block',
					'type' => 'info',
					'desc' => __( 'Meta Block Settings', 'kadence-amp' ),
				),
				array(
					'id' => 'show_date',
					'type' => 'switch', 
					'title' => __( 'Show Publish Date', 'kadence-amp' ),
					"default" => 1,
				),
				array(
					'id' => 'show_author',
					'type' => 'switch', 
					'title' => __( 'Show Author', 'kadence-amp' ),
					"default" => 1,
				),
				array(
					'id'=>'info_amp_post_schema',
					'type' => 'info',
					'desc' => __( 'Post Schema', 'kadence-amp' ),
				),
				array(
					'id'=>'post_schema',
					'type' => 'select', 
					'title' => __( 'Schema Post Content Type', 'kadence-amp' ),
					'default' => 'BlogPosting',
					'width' => 'width:60%',
					'options' => array('BlogPosting' => __( 'BlogPosting', 'kadence-amp' ), 'NewsArticle' => __( 'NewsArticle', 'kadence-amp' )),
				),
			),
		) 
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id'    => 'kt_amp_product',
			'title' => __( 'AMP Single Product', 'kadence-amp' ),
			'desc'  => '',
			'fields' => array(
				array(
					'id'       => 'product_layout',
					'type'     => 'sortable',
					'title'    => __( 'AMP Product Template', 'kadence-amp' ),
					'subtitle' => __( 'Check box to enable and sort for output order (Top First).', 'kadence-amp' ),
					'mode'     => 'checkbox',
					'options'  => array(
						"ad_one"  			=> __("Ad Block #1", 'kadence-amp' ),
						"breadcrumbs"   	=> __("Breadcrumbs", 'kadence-amp' ),
						"original_button"	=> __("Button to original version", 'kadence-amp' ),
						"image_gallery"  	=> __("Image Gallery", 'kadence-amp' ),
						"title"  			=> __("Title", 'kadence-amp' ),
						"rating" 			=> __("Rating", 'kadence-amp' ),
						"price" 			=> __("Price", 'kadence-amp' ),
						"shortdescription"	=> __("Product Short Description", 'kadence-amp' ),
						"add_to_cart" 		=> __("Add to Cart Block", 'kadence-amp' ),
						"meta"   			=> __("Product Meta Block", 'kadence-amp' ),
						"share"				=> __("Share Buttons", 'kadence-amp' ),
						"tabs"				=> __("Product Tabs", 'kadence-amp' ),
						"related"			=> __("Related Products", 'kadence-amp' ),
						"ad_two"			=> __("Ad Block #2", 'kadence-amp' ),
					),
					'default' => array(
						"ad_one"  			=> false,
						"breadcrumbs"   	=> false,
						"original_button"	=> false,
						"image_gallery"  	=> true,
						"title"  			=> true,
						"rating" 			=> true,
						"price" 			=> true,
						"add_to_cart" 		=> true,
						"meta"   			=> true,
						"share"				=> true,
						"tabs"				=> true,
						"related"			=> true,
						"ad_two"			=> false,
					),
				),
				array(
					'id'=>'info_amp_add_to_cart',
					'type' => 'info',
					'desc' => __( 'Add to Cart Block Settings', 'kadence-amp' ),
				),
				array(
					'id' => 'show_quantity',
					'type' => 'switch', 
					'title' => __( 'Show quantity option', 'kadence-amp' ),
					"default" => 0,
				),
				array(
					'id'=>'info_amp_meta',
					'type' => 'info',
					'desc' => __( 'Meta Block Settings', 'kadence-amp' ),
				),
				array(
					'id' => 'show_sku',
					'type' => 'switch', 
					'title' => __( 'Show SKU', 'kadence-amp' ),
					"default" => 1,
				),
				array(
					'id' => 'show_product_cats',
					'type' => 'switch', 
					'title' => __( 'Show Categories', 'kadence-amp' ),
					"default" => 1,
				),
				array(
					'id' => 'show_product_tags',
					'type' => 'switch', 
					'title' => __( 'Show Tags', 'kadence-amp' ),
					"default" => 1,
				),
			),
		) 
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_page',
			'title' => __( 'AMP Single Page', 'kadence-amp' ),
			'desc' => "",
			'fields' => array(
				array(
					'id'       => 'page_layout',
					'type'     => 'sortable',
					'title'    => __( 'AMP Page Template', 'kadence-amp' ),
					'subtitle' => __( 'Check box to enable and sort for output order (Top First).', 'kadence-amp' ),
					'mode'     => 'checkbox',
					'options'  => array(
						"ad_one"  			=> __("Ad Block #1", 'kadence-amp' ),
						"breadcrumbs"   	=> __("Breadcrumbs", 'kadence-amp' ),
						"original_button"	=> __("Button to original version", 'kadence-amp' ),
						"feature"  			=> __("Page Feature Image", 'kadence-amp' ),
						"title"  			=> __("Title", 'kadence-amp' ),
						"content" 			=> __("Page Content", 'kadence-amp' ),
						"share"				=> __("Share Buttons", 'kadence-amp' ),
						"comments" 			=> __("Page Comments", 'kadence-amp' ),
						"ad_two"			=> __("Ad Block #2", 'kadence-amp' ),
					),
					'default' => array(
						"ad_one"  			=> false,
						"breadcrumbs"   	=> false,
						"original_button"	=> false,
						"feature"  			=> false,
						"title"  			=> true,
						"content" 			=> true,
						"share"				=> false,
						"comments" 			=> false,
						"ad_two"			=> false,
					),
				),
			),
		)
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_singular',
			'title' => __( 'AMP Singular', 'kadence-amp' ),
			'desc' =>  __( 'This applies to all other post types.', 'kadence-amp' ),
			'fields' => array(
				array(
					'id'       => 'singular_layout',
					'type'     => 'sortable',
					'title'    => __( 'AMP Singular Template', 'kadence-amp' ),
					'subtitle' => __( 'Check box to enable and sort for output order (Top First).', 'kadence-amp' ),
					'mode'     => 'checkbox',
					'options'  => array(
						"ad_one"  			=> __("Ad Block #1", 'kadence-amp' ),
						"breadcrumbs"   	=> __("Breadcrumbs", 'kadence-amp' ),
						"original_button"	=> __("Button to original version", 'kadence-amp' ),
						"feature"  			=> __("Featured Image", 'kadence-amp' ),
						"title"  			=> __("Title", 'kadence-amp' ),
						"content" 			=> __("Content", 'kadence-amp' ),
						"share"				=> __("Share Buttons", 'kadence-amp' ),
						"comments" 			=> __("Comments", 'kadence-amp' ),
						"ad_two"			=> __("Ad Block #2", 'kadence-amp' ),
					),
					'default' => array(
						"ad_one"  			=> false,
						"breadcrumbs"   	=> false,
						"original_button"	=> false,
						"feature"  			=> false,
						"title"  			=> true,
						"content" 			=> true,
						"share"				=> false,
						"comments" 			=> false,
						"ad_two"			=> false,
					),
				),
			),
		) 
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_social',
			'title' => __( 'AMP Social', 'kadence-amp' ),
			'desc' => "",
			'fields' => array(
				array(
					'id'       => 'social_options',
					'type'     => 'sortable',
					'title'    => __( 'AMP Social Share', 'kadence-amp' ),
					'subtitle' => __( 'Check box to enable and sort for output order (Top First).', 'kadence-amp' ),
					'mode'     => 'checkbox',
					'options'  => array(
						"facebook"		=> __("Facebook", 'kadence-amp' ),
						"twitter"		=> __("Twitter", 'kadence-amp' ),
						"linkedin"		=> __("LinkedIn", 'kadence-amp' ),
						"pinterest"		=> __("Pinterest", 'kadence-amp' ),
						"email"   		=> __("Email", 'kadence-amp' ),
					),
					'default' => array(
						"facebook"		=> true,
						"twitter"		=> true,
						"linkedin"		=> true,
						"pinterest"		=> false,
						"email"   		=> false,
					),
				),
			),
		)
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_ads',
			'title' => __( 'AMP Ad Settings', 'kadence-amp' ),
			'desc' => "",
			'fields' => array(
				array(
					'id'=>'ad_block_1_custom',
					'type' => 'textarea',
					'title' => __( 'Custom Ad #1', 'kadence-amp' ), 
					'subtitle' => sprintf( '%s <a href="https://www.ampproject.org/docs/reference/components/amp-ad">%s</a>', __( 'Place Ad code here in accordance with', 'kadence-amp' ), __( 'Amp Ad', 'kadence-amp' ) ),
					'default' => '',
				),
				array(
					'id'=>'ad_block_2_custom',
					'type' => 'textarea',
					'title' => __( 'Custom Ad #2', 'kadence-amp' ), 
					'subtitle' => sprintf( '%s <a href="https://www.ampproject.org/docs/reference/components/amp-ad">%s</a>', __( 'Place Ad code here in accordance with', 'kadence-amp' ), __( 'Amp Ad', 'kadence-amp' ) ),
					'default' => '',
				),
			),
		) 
	);
	Redux::setSection( $opt_name,
		array(
			'icon' => 'kt-icon-font-size',
			'icon_class' => 'icon-large',
			'id' => 'kt_amp_custom_css',
			'title' => __( 'AMP Custom CSS', 'kadence-amp' ),
			'desc' => "",
			'fields' => array(
				array(
					'id'=>'custom_css',
					'type' => 'textarea',
					'title' => __( 'Custom CSS', 'kadence-amp' ), 
					'default' => '',
				),
			),
		) 
	);

    Redux::setExtensions( 'kadence_amp', KADENCE_AMP_PATH . '/admin/extensions/' );
	add_filter( "redux/options/kadence_amp/data/post_types", 'kadence_amp_all_custom_posts' );
}
add_action( 'after_setup_theme', 'kadence_amp_add_sections', 2 );

function kadence_amp_override_redux_css() {
	wp_dequeue_style( 'redux-admin-css' );
	wp_register_style( 'kadence_amp-redux-custom-css', KADENCE_AMP_URL . 'admin/css/admin-options.css', false, KADENCE_AMP_VERSION );    
	wp_enqueue_style( 'kadence_amp-redux-custom-css' );
	wp_dequeue_style( 'redux-elusive-icon' );
	wp_dequeue_style( 'redux-elusive-icon-ie7' );
}

add_action( 'redux-enqueue-kadence_amp', 'kadence_amp_override_redux_css' );

/**
 * Get all post types.
 *
 * @param string $exclude post type name to exclude.
 */
function kadence_amp_all_custom_posts( $data ) {
	global $wp_post_types;
	$args = array(
		'public' => true,
		'exclude_from_search' => false,
	);
	$data = array();
	$output         = 'names'; // names or objects, note names is the default.
	$operator       = 'and'; // 'and' or 'or'.

	$post_types = get_post_types( $args, $output, $operator );

	ksort( $post_types );

	foreach ( $post_types as $name => $title ) {
		if ( 'kt_gallery' !== $name && 'kad_slider' !== $name && 'attachment' !== $name ) {
			if ( isset ( $wp_post_types[ $name ]->labels->menu_name ) ) {
				$data[ $name ] = $wp_post_types[ $name ]->labels->menu_name;
			} else {
				$data[ $name ] = ucfirst( $name );
			}
		}
	}

	return $data;
}