<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
		<?php 
		$this->load_parts( array( 'style' ) );
		$this->load_parts( array( 'woo_style' ) );
		do_action( 'amp_post_template_css', $this );
		?>
	</style>
</head>

<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">

<?php $this->load_parts( array( 'header-bar' ) ); ?>
<div class="main">
	<div class="amp-wp-product">
		<?php 
		/**
		* Get all product content
		*/ 
		global $product;
		$product = wc_get_product( get_the_ID() );
		do_action( 'kt_amp_build_product', $this, $product ); ?>
	</div>
</div>

<?php $this->load_parts( array( 'footer' ) ); ?>

<?php do_action( 'amp_post_template_footer', $this ); ?>

</body>
</html>
