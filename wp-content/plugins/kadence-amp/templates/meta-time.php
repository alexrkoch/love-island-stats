<div class="amp-wp-meta amp-wp-posted-on">
	<time datetime="<?php echo esc_attr( date( 'c', $this->get( 'post_publish_timestamp' ) ) ); ?>">
		<?php
		echo get_the_date( get_option( 'date_format' ) );
		?>
	</time>
</div>
