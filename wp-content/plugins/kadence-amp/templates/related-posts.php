<?php
$categories = get_the_category( $this->ID );
if ( $categories ) {
	$category_ids = array();
	foreach ( $categories as $individual_category ) {
		$category_ids[] = $individual_category->term_id;
	}
}
$args = array(
	'orderby'        => 'rand',
	'category__in'   => $category_ids,
	'post__not_in'   => array( $this->ID ),
	'posts_per_page' => 3,
);
$amp_related = new WP_Query( apply_filters( 'kadence_amp_related_posts_args', $args ) );
if ( $amp_related ) :
	echo '<div class="amp-related-posts">';
	echo '<h3>' . esc_html__( 'Related Posts', 'kadence_amp' ) . '</h3>';
	echo '<ul>';
	while ( $amp_related->have_posts() ) :
		$amp_related->the_post();
		$product_id = get_the_id();
		$plink      = kt_amp_url( $product_id );
		echo '<li class="flex items-center">';
		if ( has_post_thumbnail( $product_id ) ) {
			$img = kt_amp_get_image( '80', '80', true, get_post_thumbnail_id( $product_id ) );
			echo '<a href="' . esc_url( $plink ) . '" class="amp-related-img-link" title="' . esc_attr( get_the_title() ) . '">';
			echo '<amp-img src="' . esc_url( $img['src'] ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" alt="' . esc_attr( $img['alt'] ) . '" ' . $img['srcset'] . ' class="amp-related-img"></amp-img>';
			echo '</a>';
		}
			echo '<div class="amp-related-title">';
				echo '<a href="' . esc_url( $plink ) . '" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</a>';
				echo '<div class="amp-related-meta amp-wp-meta">' . get_the_date( get_option( 'date_format' ) ) . '</div>';
			echo '</div>';
		echo '</li>';
	endwhile;
	echo '</div>';
endif;
wp_reset_postdata();
