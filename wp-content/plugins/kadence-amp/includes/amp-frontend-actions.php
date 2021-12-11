<?php
// Callbacks for adding AMP-related things to the main theme

add_action( 'wp_head', 'amp_frontend_add_canonical' );

function amp_frontend_add_canonical() {
	if ( false === apply_filters( 'amp_frontend_show_canonical', true ) ) {
		return;
	}
	$current_url = kt_amp_get_current_url();
	if ( is_singular() ) {
		$amp_url = kt_amp_url( get_queried_object_id() );
	} else {
		$amp_url = add_query_arg( KADENCE_AMP_QUERY, '', $current_url );
	}
	printf( '<link rel="amphtml" href="%s" />', esc_url( $amp_url ) );
}