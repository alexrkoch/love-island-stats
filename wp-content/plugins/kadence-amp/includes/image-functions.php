<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function kt_amp_get_image( $width = null, $height = null, $crop = true, $id = null ) {
    if(empty($id)) {
        $id = get_post_thumbnail_id();
    }
    if( ! empty( $id ) ) {
    	$get_image = Kadence_AMP_Get_Image::getInstance();
        $image = $get_image->process( $id, $width, $height );

        $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
        $return_array = array(
            'src' => $image[0],
            'width' => $image[1],
            'height' => $image[2],
            'srcset' => $image[3],
            'alt' => $alt,
            'full' => $image[4],
        );
    } else {
        $return_array = array(
            'src' => '',
            'width' => '',
            'height' => '',
            'alt' => '',
            'srcset' => '',
            'full' => '',
        );
    }

    return $return_array;
}