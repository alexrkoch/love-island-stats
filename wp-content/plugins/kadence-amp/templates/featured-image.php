<?php
$featured_image = $this->get( 'featured_image' );

if ( empty( $featured_image ) ) {
	return;
}

$amp_html = $featured_image['amp_html'];
?>
<div class="amp-wp-article-featured-image feature-content">
	<?php echo $amp_html; // amphtml content; no kses ?>
</div>
