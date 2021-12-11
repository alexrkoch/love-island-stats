<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! function_exists( 'kt_amp_output_breadcrumbs' ) ) {
	function kt_amp_output_breadcrumbs() {
	  	global $post;

	  	$home 				= __('Home', 'kadence-amp');
		$homelink 			= home_url('/');
		$page_for_posts 	= get_option( 'page_for_posts' );
		$wrap_before    	= '<div id="amp-bread"><div class="amp-breadcrumb-container">';
		$wrap_after     	= '</div></div>';
		$delimiter 			= apply_filters('kadence_amp_breadcrumb_delimiter', '&raquo;');
		$delimiter_before   = '<span class="bc-delimiter">';
		$delimiter_after    = '</span>';
		$sep            	= ' ' . $delimiter_before . $delimiter . $delimiter_after . ' ';
		$link_before    	= '<span>';
		$link_after    		= '</span>';
		$link_in_before 	= '<span>';
		$link_in_after  	= '</span>';
		$link           	= $link_before . '<a href="%1$s" itemprop="url">' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
		$before 			= '<span class="kad-breadcurrent">';
	 	$after 				= '</span>';
	 	$home_link      	= $link_before . '<a href="' . esc_url( $homelink ) . '" itemprop="url" class="kad-bc-home">' . $link_in_before . esc_html( $home ) . $link_in_after . '</a>' . $link_after . $sep;
	 	$shop_bread = '';
		if ( class_exists( 'woocommerce' ) ) {
		    $shop_page_id = wc_get_page_id( 'shop' );
		    $shop_page    = get_post( $shop_page_id );
		    if (get_option( 'page_on_front' ) !== $shop_page_id ) {
		        $shop_bread = $link_before . '<a href="' . esc_url(get_permalink( $shop_page )) . '" itemprop="url" class="kad-bc-shop">'. $link_in_before . get_the_title($shop_page_id) . $link_in_after . '</a>' . $link_after . $sep;
		    }
		}

	  	if ( ! is_front_page() ) {

	    echo $wrap_before . $home_link;
	  	
	  		do_action('kadence_amp_breadcrumbs_after_home');

	    	if ( is_category() ) {
	       		if( ! empty( $page_for_posts ) ) { 
		            $bparentpagelink 	= get_page_link( $page_for_posts ); 
		            $bparenttitle 		= get_the_title( $page_for_posts );
		            echo sprintf($link, esc_url($bparentpagelink), esc_html($bparenttitle)) . $sep;
		        } 
		      	$thiscat = get_category(get_query_var('cat'), false);
		      	if ($thiscat->parent != 0) {
		      		$cats = get_category_parents($thiscat->parent, TRUE, $sep);
		      		$cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
					$cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1 itemprop="url">' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
					echo $cats.$sep;
		      	}
	      
	      		echo $before . get_the_title() . $after;
	  
	    	}  else if ( is_tag() ) {
			    if( !empty( $page_for_posts )){ 
		            $bparentpagelink 	= get_page_link( $page_for_posts ); 
		            $bparenttitle 		= get_the_title( $page_for_posts );
		            echo sprintf($link, esc_url($bparentpagelink), esc_html($bparenttitle)) . $sep;
		        } 
			    echo $before . single_tag_title('', false) . $after;
	    	} else if ( is_tax('post_format') ) {
		    	$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
		      	if( !empty( $page_for_posts )){ 
		            $bparentpagelink 	= get_page_link( $page_for_posts ); 
		            $bparenttitle 		= get_the_title( $page_for_posts );
		            echo sprintf($link, esc_url($bparentpagelink), esc_html($bparenttitle)) . $sep;
		        } 
	      		echo $before . esc_html($term->name) . $after;
	    	} elseif ( is_search() ) {
	      		echo $before . __('Search results for', 'kadence-amp'). ' "' . get_search_query() . '"' . $after;
	  
	    	} elseif ( is_day() ) {
	    		echo sprintf($link, esc_url(get_year_link(get_the_time('Y'))), get_the_time('Y')) . $sep;
	    		echo sprintf($link, esc_url(get_month_link(get_the_time('Y')),get_the_time('m')), get_the_time('F')) . $sep;

	      		echo $before . get_the_time('d') . $after;
	  
	    	} elseif ( is_month() ) {
	    		echo sprintf($link, esc_url(get_year_link(get_the_time('Y'))), get_the_time('Y')) . $sep;

	      		echo $before . get_the_time('F') . $after;
	  
	    	} elseif ( is_year() ) {

	      		echo $before . get_the_time('Y') . $after;
	  
	    	} elseif ( is_single() && !is_attachment()) {
	      		$post_type = get_post_type();
	      		// PORTFOLIO
	      		if($post_type == "portfolio") {
	                $main_term = '';
	                if(class_exists('WPSEO_Primary_Term')) {
	              		$WPSEO_term = new WPSEO_Primary_Term('portfolio-type', $post->ID);
						$WPSEO_term = $WPSEO_term->get_primary_term();
						$WPSEO_term = get_term($WPSEO_term);
						if (is_wp_error($WPSEO_term)) { 
							if ( $terms = wp_get_post_terms( $post->ID, 'portfolio-type', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
								$main_term = $terms[0];
							}
						} else {
							$main_term = $WPSEO_term;
						}
	              	} elseif ( $terms = wp_get_post_terms( $post->ID, 'portfolio-type', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
	                    $main_term = $terms[0];
	                }
	            	if($main_term){
	                    $ancestors = get_ancestors( $main_term->term_id, 'portfolio-type' );
	                    $ancestors = array_reverse( $ancestors );
	                    foreach ( $ancestors as $ancestor ) {
	                    	$ancestor = get_term( $ancestor, 'portfolio-type' );
	                    	echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'portfolio-type' )), $ancestor->name) . $sep;
	                    }
	                    echo sprintf($link, esc_url(get_term_link( $main_term->slug, 'portfolio-type' )), $main_term->name) . $sep;
	            	}
	            // PRODUCT
	        	} else if($post_type == "product") {
	              	echo $shop_bread;
	              	$main_term = '';
	              	// check if yoast category set and there is a primary
	              	if(class_exists('WPSEO_Primary_Term')) {
	              		$WPSEO_term = new WPSEO_Primary_Term('product_cat', $post->ID);
						$WPSEO_term = $WPSEO_term->get_primary_term();
						$WPSEO_term = get_term($WPSEO_term);
						if (is_wp_error($WPSEO_term)) { 
							if ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
								$main_term = $terms[0];
							}
						} else {
							$main_term = $WPSEO_term;
						}
	              	} elseif ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
	                    $main_term = $terms[0];
	                }
	                if($main_term){
	                	$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
	                    $ancestors = array_reverse( $ancestors );
	                    foreach ( $ancestors as $ancestor ) {
	                        $ancestor = get_term( $ancestor, 'product_cat' );
	                        echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'product_cat' )), esc_html($ancestor->name)) . $sep;
	                    }
	                    echo sprintf($link, esc_url(get_term_link( $main_term->slug, 'product_cat' )), esc_html($main_term->name)) . $sep;
	                }
	            // podcast
	          	} else if($post_type == "podcast") {
	          		$main_term = '';
	              	// check if yoast category set and there is a primary
	              	if(class_exists('WPSEO_Primary_Term')) {
	              		$WPSEO_term = new WPSEO_Primary_Term('series', $post->ID);
						$WPSEO_term = $WPSEO_term->get_primary_term();
						$WPSEO_term = get_term($WPSEO_term);
						if (is_wp_error($WPSEO_term)) { 
							if ( $terms = wp_get_post_terms( $post->ID, 'series', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
								$main_term = $terms[0];
							}
						} else {
							$main_term = $WPSEO_term;
						}
	              	} elseif ( $terms = wp_get_post_terms( $post->ID, 'series', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
	                    $main_term = $terms[0];
	                }
	                if($main_term){
	                	$ancestors = get_ancestors( $main_term->term_id, 'series' );
	                    $ancestors = array_reverse( $ancestors );
	                    foreach ( $ancestors as $ancestor ) {
	                        $ancestor = get_term( $ancestor, 'series' );
	                        echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'series' )), esc_html($ancestor->name)) . $sep;
	                    }
	                    echo sprintf($link, esc_url(get_term_link( $main_term->slug, 'series' )), esc_html($main_term->name)) . $sep;
	                }
	            // EVENTS
	          	} else if($post_type == "event") {
	          		$main_term = '';
	              	// check if yoast category set and there is a primary
	              	if(class_exists('WPSEO_Primary_Term')) {
	              		$WPSEO_term = new WPSEO_Primary_Term('event-category', $post->ID);
						$WPSEO_term = $WPSEO_term->get_primary_term();
						$WPSEO_term = get_term($WPSEO_term);
						if (is_wp_error($WPSEO_term)) { 
							if ( $terms = wp_get_post_terms( $post->ID, 'event-category', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
								$main_term = $terms[0];
							}
						} else {
							$main_term = $WPSEO_term;
						}
	              	} elseif ( $terms = wp_get_post_terms( $post->ID, 'event-category', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
	                    $main_term = $terms[0];
	                }
	                if($main_term){
	                	$ancestors = get_ancestors( $main_term->term_id, 'event-category' );
	                    $ancestors = array_reverse( $ancestors );
	                    foreach ( $ancestors as $ancestor ) {
	                        $ancestor = get_term( $ancestor, 'event-category' );
	                        echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'event-category' )), esc_html($ancestor->name)) . $sep;
	                    }
	                    echo sprintf($link, esc_url(get_term_link( $main_term->slug, 'event-category' )), esc_html($main_term->name)) . $sep;
	                }
	            // TRIBE
	          	} else if($post_type == "tribe_events") {

	          			echo sprintf($link, esc_url(tribe_get_events_link()), esc_html(tribe_get_event_label_plural())) . $sep;
	          	// STAFF
	          	} else if($post_type == "staff") {
	               	$main_term = '';
	              	// check if yoast category set and there is a primary
	              	if(class_exists('WPSEO_Primary_Term')) {
	              		$WPSEO_term = new WPSEO_Primary_Term('staff-group', $post->ID);
						$WPSEO_term = $WPSEO_term->get_primary_term();
						$WPSEO_term = get_term($WPSEO_term);
						if (is_wp_error($WPSEO_term)) { 
							if ( $terms = wp_get_post_terms( $post->ID, 'staff-group', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
								$main_term = $terms[0];
							}
						} else {
							$main_term = $WPSEO_term;
						}
	              	} elseif ( $terms = wp_get_post_terms( $post->ID, 'staff-group', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
	                    $main_term = $terms[0];
	                }
	                if($main_term){
	                    $ancestors = get_ancestors( $main_term->term_id, 'staff-group' );
	                    $ancestors = array_reverse( $ancestors );
	                        foreach ( $ancestors as $ancestor ) {
	                            $ancestor = get_term( $ancestor, 'staff-group' );
	                           echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'staff-group' )), esc_html($ancestor->name)) . $sep;
	                        }
	                   	echo sprintf($link, esc_url(get_term_link( $main_term->slug, 'staff-group' )), esc_html($main_term->name)) . $sep;
	                }
	                // TESTIMONIAL
	          	} else if($post_type == "testimonial") {
	               	$main_term = '';
	              	// check if yoast category set and there is a primary
	              	if(class_exists('WPSEO_Primary_Term')) {
	              		$WPSEO_term = new WPSEO_Primary_Term('testimonial-group', $post->ID);
						$WPSEO_term = $WPSEO_term->get_primary_term();
						$WPSEO_term = get_term($WPSEO_term);
						if (is_wp_error($WPSEO_term)) { 
							if ( $terms = wp_get_post_terms( $post->ID, 'testimonial-group', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
								$main_term = $terms[0];
							}
						} else {
							$main_term = $WPSEO_term;
						}
	              	} elseif ( $terms = wp_get_post_terms( $post->ID, 'testimonial-group', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
	                    $main_term = $terms[0];
	                }
	                if($main_term){
	                    $ancestors = get_ancestors( $main_term->term_id, 'testimonial-group' );
	                    $ancestors = array_reverse( $ancestors );
	                        foreach ( $ancestors as $ancestor ) {
	                            $ancestor = get_term( $ancestor, 'testimonial-group' );
	                           echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'testimonial-group' )), esc_html($ancestor->name)) . $sep;
	                        }
	                   	echo sprintf($link, esc_url(get_term_link( $main_term->slug, 'testimonial-group' )), esc_html($main_term->name)) . $sep;
	                }
	       		// POST
	          	} else if($post_type == "post") {
	          		if( !empty( $page_for_posts )){ 
		              	$bparentpagelink = get_page_link( $page_for_posts ); 
		              	$bparenttitle = get_the_title( $page_for_posts );
		              	echo sprintf($link, esc_url($bparentpagelink), esc_html($bparenttitle)) . $sep;
		            }
		            // check if yoast category set and there is a primary
	              	if(class_exists('WPSEO_Primary_Term')) {
	              		$WPSEO_term = new WPSEO_Primary_Term('category', $post->ID);
						$WPSEO_term = $WPSEO_term->get_primary_term();
						$WPSEO_term = get_term($WPSEO_term);
						if (is_wp_error($WPSEO_term)) { 
							if ( $terms = wp_get_post_terms( $post->ID, 'category', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
								$main_term = $terms[0];
							}
						} else {
							$main_term = $WPSEO_term;
						}
	              	} elseif ( $terms = wp_get_post_terms( $post->ID, 'category', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
	                    $main_term = $terms[0];
	                }
	                if($main_term){
	                	$ancestors = get_ancestors( $main_term->term_id, 'category' );
	                    $ancestors = array_reverse( $ancestors );
	                    foreach ( $ancestors as $ancestor ) {
	                        $ancestor = get_term( $ancestor, 'category' );
	                        echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'category' )), esc_html($ancestor->name)) . $sep;
	                    }
	                    echo sprintf($link, esc_url(get_term_link( $main_term->slug, 'category' )), esc_html($main_term->name)) . $sep;
	                }
	          	}

	            echo $before . get_the_title() . $after;

	    	} elseif (is_tax('portfolio-type')) {
	            $ancestors = get_ancestors( get_queried_object()->term_id, 'portfolio-type' );
	            $ancestors = array_reverse( $ancestors );
	            foreach ( $ancestors as $ancestor ) {
	            	$ancestor = get_term( $ancestor, 'portfolio-type' );
	            	echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'portfolio-type' )), esc_html($ancestor->name)) . $sep;
	            }
	           
	            echo $before . get_the_title() . $after;

	    	} elseif (is_tax('portfolio-tag')) {

	            echo $before . get_the_title() . $after;

	    	} elseif ( is_tax('product_cat') ) {
	        	echo $shop_bread;

	        	$ancestors = get_ancestors( get_queried_object()->term_id, 'product_cat' );
	            $ancestors = array_reverse( $ancestors );
	        	foreach ( $ancestors as $ancestor ) {
	         		$ancestor = get_term( $ancestor, 'product_cat' );
	         		echo sprintf($link, esc_url(get_term_link( $ancestor->slug, 'product_cat' )), esc_html($ancestor->name)) . $sep;
	        	}
	        
	      		echo $before . get_the_title() . $after;

	  		} elseif ( is_tax('product_tag') ) {
	  			echo $shop_bread;
	  			echo $before . get_the_title() . $after;

	  		} elseif (class_exists('woocommerce') && is_shop()) {
	      		$shop_page_id = wc_get_page_id( 'shop' );
	            $page_title   = get_the_title( $shop_page_id );
	      		echo $before . $page_title . $after;

	    	} elseif (class_exists('woocommerce') && (is_account_page() || is_checkout())) {
		    	if ( is_wc_endpoint_url() && ( $endpoint = WC()->query->get_current_endpoint() ) && ( $endpoint_title = WC()->query->get_endpoint_title( $endpoint ) ) ) {
		    		echo sprintf($link, esc_url(get_permalink()), get_the_title()) . $sep;
					echo $before . esc_html($endpoint_title) . $after;
				} else {
		      		echo $before . get_the_title() . $after;
		      	}
	    	} elseif ( is_attachment() ) {
	    		$parent = $post->post_parent;
	    		if($parent) {
	    			echo sprintf($link, esc_url(get_permalink( $parent )), get_the_title($parent)) . $sep;
	    		}

	      		echo $before . get_the_title() . $after;
	    	} elseif ( is_home() ) {
	      		echo $before . get_the_title(get_option( 'page_for_posts' )) . $after;
	  
	    	} elseif ( is_attachment() ) {
	      		echo ' ' . $before . get_the_title() . $after;
	  
	    	} elseif ( is_home() ) {
	      		echo $before . get_the_title(get_option( 'page_for_posts' )) . $after;
	  
	    	} elseif ( is_page() && !$post->post_parent ) {
	      		echo $before . get_the_title() . $after;
	  
	    	} elseif ( is_page() && $post->post_parent ) {
	      		$parent_id  = $post->post_parent;
		      	$parentcrumbs = array();
		      	while ($parent_id) {
		        	$page = get_page($parent_id);
		        	$parentcrumbs[] = sprintf($link,esc_url(get_permalink($page->ID)), get_the_title($page->ID)) . $sep;
		        	$parent_id  = $page->post_parent;
		      	}
	      		$parentcrumbs = array_reverse($parentcrumbs);
	      		foreach ($parentcrumbs as $parentcrumb) {
	      			echo $parentcrumb;
	      		}
	      		echo $before . get_the_title() . $after;
	  
	    	} elseif ( is_author() ) {
	    		if( !empty( $page_for_posts )){ 
		            $bparentpagelink 	= get_page_link( $page_for_posts ); 
		            $bparenttitle 		= get_the_title( $page_for_posts );
		            echo sprintf($link, esc_url($bparentpagelink), esc_html($bparenttitle)) . $sep;
		     	} 
	       		global $author;
	      		$userdata = get_userdata($author);
	      		echo $before . esc_html($userdata->display_name) . $after;
	  
		    } elseif (is_archive()) {

	            echo $before . get_the_title() . $after;

	     	} elseif ( is_404() ) {
		      	echo $before . __('Error 404', 'kadence-amp') . $after;
		    }	
	  
	   		// Paged
		    if ( get_query_var('paged') ) {
		      	echo ' - ' .__('Page', 'kadence-amp') . ' ' . esc_html(get_query_var('paged'));
		    }
	  
	    	echo $wrap_after;
	  
	  	}
	}
}