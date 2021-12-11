<?php global $product;
echo '<amp-selector role="tablist" layout="container" class="ampTabContainer ampstart-headerbar-nav" keyboard-select-mode="select">';
		$tabs    = apply_filters( 'woocommerce_product_tabs', array() );
		$i = 1;
foreach ( $tabs as $key => $tab ) :
	echo '<div role="tab" class="tabButton h4 ampstart-nav-item"';
	if ( 1 == $i ) {
		echo 'selected '; }
	echo 'option="' . esc_attr( $key ) . '">';
	echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key );
	echo '</div>';
	echo '<div role="tabpanel" class="tabContent p1 p">';
	if ( 'description' == $key ) {
		$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'kadence-amp' ) ) );
		if ( $heading ) :
			echo '<h2>' . $heading . '</h2>';
		endif;
		echo $this->get( 'post_amp_content' );
	} else if ( 'additional_information' == $key ) {
		$heading = esc_html( apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional information', 'kadence-amp' ) ) );
		if ( $heading ) :
			echo '<h2>' . $heading . '</h2>';
		endif;
		wc_display_product_attributes( $product );
	} else if ( 'reviews' == $key ) {
		echo '<h2>' . $tabs['reviews']['title'] . '</h2>';
		echo '<div id="comments" class="section-content comments-area">';
		if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) ) {
				printf( _n( '%1$s review for %2$s%3$s%4$s', '%1$s reviews for %2$s%3$s%4$s', $count, 'kadence-amp' ), $count, '<span>', get_the_title(), '</span>' );
		} else {
					_e( 'Reviews', 'kadence-amp' );
		}
						$comments_html = $this->get( 'comments_html' );
		if ( $comments_html ) {
			echo $comments_html;
		} else {
			echo '<p class="woocommerce-noreviews">' . __( 'There are no reviews yet.', 'kadence-amp' ),'</p>';
		}
						$comments_link_url = $this->get( 'comments_link_url' );
		if ( $comments_link_url ) {
			$comments_link_text = $this->get( 'comments_link_text' );
			echo '<div class="amp-wp-comments-link">';
			echo '<a href="' . $comments_link_url . '" class="amp-button">';
				echo $comments_link_text;
			echo '</a>';
			echo '</div>';
		}
						echo '</div>';

	} else if ( isset( $tab['callback'] ) ) {
		echo $this->get( 'product_tab_' . $key );
	}
	echo '</div>';
	$i ++;
		endforeach;
echo '</amp-selector>';
