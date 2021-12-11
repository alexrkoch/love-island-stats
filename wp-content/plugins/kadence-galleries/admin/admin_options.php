<?php 
/**
* 
*/
add_action( "after_setup_theme", 'kt_galleries_run_redux', 1);
function kt_galleries_run_redux() {
   if ( class_exists( 'Redux' ) ) {
      return;
    }
    require_once( KTG_PATH . '/admin/redux/framework.php');
}
add_action( "after_setup_theme", 'kt_galleries_add_sections', 2);
function kt_galleries_add_sections() {
    if ( ! class_exists( 'Redux' ) ) {
		return;
    }
    // Get Raw Login options
    $kt_galleries = get_option( 'kt_galleries' );
	if ( isset( $kt_galleries['enable_photoshelter'] ) && $kt_galleries['enable_photoshelter'] == '1' ) {
	    ob_start(); 
		
		global $ps_login_err;
		global $psc;
		
		// force a plugin auth check with idle
		if( isset( $psc ) ) {
			try {
				$auth_chk = $psc->idle();
			} catch ( Exception $e ) {}
		}
		$options = get_option( 'photoshelter' );
		$cookie = get_option( 'ps_cookies' );
		if( $cookie ) {
			$offset = count( $cookie ) - 1;
			$last_cookie = $cookie['ch_' . $offset];
		} else {
			$last_cookie = false;
		}
		
		if( $last_cookie ) {
			?>
			<p class="ps-ok-notice notices"><?php echo sprintf(__('You are logged in as %s.', 'kadence-galleries' ), $options['username']); ?></p>
			<?php 
		} else {

			$ps_en = false;
			if( ! get_option( 'photoshelter' ) ) {
				echo '<p class="ps-error-notice notices">' . __('Not Logged In','kadence-galleries') . '</p>';
				$ps_en = true;
			}
			if ( ! $psc->logged_in && get_option('photoshelter_logged_in') != '1' && !$ps_en ) {
					echo '<p class="ps-error-notice notices">' . get_option('photoshelter_logged_in') . '</p>';
			}
			?>
			<a href="<?php echo admin_url('edit.php?post_type=kt_gallery&page=kt-photoshelter');?>"><?php echo __( 'Login Here', 'kadence-galleries' ); ?></a>
		<?php
		}
		?>

		<?php
		$raw_content = ob_get_contents();
		ob_end_clean();
	} else {
		$raw_content = '<p class="ps-error-notice notices">' . __( 'Not Logged In', 'kadence-galleries' ) . '</p><a href="' . admin_url( 'edit.php?post_type=kt_gallery&page=kt-photoshelter' ) . '">' . __( 'Login Here', 'kadence-galleries' ) . '</a>';
	}

    $opt_name = "kt_galleries";
    $args = array(
        'opt_name'             => $opt_name,
        'display_name'         => 'Kadence Galleries Settings',
        'display_version'      => '',
        'menu_type'            => 'submenu',
        'page_parent'		   => 'edit.php?post_type=kt_gallery',
        'allow_sub_menu'       => true,
        'menu_title'           => __('Settings', 'kadence-galleries'),
        'page_title'           => __('Kadence Galleries Settings', 'kadence-galleries'),
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
        'menu_icon'            => 'dashicons-welcome-widgets-menus',
        'show_import_export'   => false,
        'save_defaults'        => true,
        'page_slug'            => 'kt-gallery-settings',
        'ajax_save'            => true,
        'default_show'         => false,
        'default_mark'         => '',
        'footer_credit' => __('Thank you for using Kadence Galleries by <a href="https://kadencethemes.com/" target="_blank">Kadence Themes</a>.', 'kadence-galleries'),
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

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url' => 'https://www.facebook.com/KadenceThemes',
        'title' => 'Follow Kadence Themes on Facebook', 
        'icon' => 'dashicons dashicons-facebook',
    );
    $args['share_icons'][] = array(
        'url' => 'https://www.twitter.com/KadenceThemes',
        'title' => 'Follow Kadence Themes on Twitter', 
        'icon' => 'dashicons dashicons-twitter',
    );
    $args['share_icons'][] = array(
        'url' => 'https://www.instagram.com/KadenceThemes',
        'title' => 'Follow Kadence Themes on Instagram', 
        'icon' => 'dashicons dashicons-format-image',
    );


    // Add content after the form.
    //$args['footer_text'] = '';

    Redux::setArgs( $opt_name, $args );
    Redux::setSection( $opt_name, array(
	    'icon' => 'dashicons-admin-generic',
	    'icon_class' => 'dashicons',
	    'id' => 'kt_gallery_settings',
	    'title' => __('Gallery Settings', 'kadence-galleries'),
	    'desc' => "",
	    'fields' => array(
	        array(
	            'id'=>'gallery_lightbox',
	            'type' => 'select',
	            'title' => __('Gallery Lightbox', 'kadence-galleries'),
	            'options' => array('photoswipe' => __('Photoswipe', 'kadence-galleries'), 'magnific' => __('Magnific Popup', 'kadence-galleries')),
	            'default' => 'photoswipe',
	            'width' => 'width:60%',
	            ),
	        array(
	            'id'=>'gallery_lightbox_skin',
	            'type' => 'select',
	            'title' => __('Gallery Lightbox Skin', 'kadence-galleries'),
	            'options' => array('light' => __('Light', 'kadence-galleries'),'dark' => __('Dark', 'kadence-galleries')),
	            'default' => 'light',
	            'width' => 'width:60%',
	            ),
	        array(
				'id'      => 'album_post_per_page',
				'title'   => __( 'Gallery Albums, items per page', 'kadence-galleries' ),
				'type'    => 'slider',
				'default' => '10',
				'min'     => '1',
				'step'    => '1',
				'max'     => '40',
	        ),
	    ),
	    )
    );
    Redux::setSection( $opt_name, array(
	    'icon' => 'dashicons-admin-generic',
	    'icon_class' => 'dashicons',
	    'id' => 'kt_gallery_sources',
	    'title' => __('External Sources (Depreciated)', 'kadence-galleries'),
	    'desc' => "",
	    'fields' => array(
	        array(
                'id'=>'enable_photoshelter',
                'type' => 'switch', 
                'title' => __('Enable Photoshelter (Depreciated)', 'kadence-woo-extras'),
                'subtitle' => __('This allows you use photoshelter galleries as the source for your Kadence Gallery', 'kadence-woo-extras'),
                "default" => 0,
            ),
            array(
                'id'=>'kt_photoshelter',
                'type' => 'raw',
                'full_width' => true,
                'title' => __('Login to Photoshelter', 'kadence-woo-extras'),
                'subtitle' => __('This will allow you to access your Photoshelter galleries', 'kadence-woo-extras'),
                'content'  => $raw_content,
                'required' => array('enable_photoshelter','=','1'),
            ),
	    ),
	)
    );

    Redux::setExtensions( 'kt_galleries', KTG_PATH . 'admin/options_assets/extensions/' );
  }
function kt_galleries_override_redux_css() {
  wp_dequeue_style( 'redux-admin-css' );
  wp_register_style('ktg-redux-custom-css', KTG_URL . '/admin/options_assets/css/style.css', false, 101);    
  wp_enqueue_style('ktg-redux-custom-css');
  //wp_dequeue_style( 'select2-css');
  wp_dequeue_style( 'redux-elusive-icon' );
  wp_dequeue_style( 'redux-elusive-icon-ie7' );
}

add_action('redux-enqueue-kt_galleries', 'kt_galleries_override_redux_css');