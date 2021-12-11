<?php
/**
 * Template Header Bar
 *
 * @package Kadence AMP.
 */

?>
<header id="top" class="amp-wp-header">
	<div>
		<?php
		do_action( 'kt_amp_header_content', $this );
		?>
	</div>
</header>
<?php
do_action( 'kt_amp_header_after', $this );
