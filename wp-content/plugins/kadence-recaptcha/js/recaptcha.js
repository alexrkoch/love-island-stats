// Write/rewrite form HTML structure and block/unblock send button.
function change_button ( value, address  ) {
	var a, ele;
	if ( value === null ) {
		jQuery('form').find( '.kt-g-recaptcha' ).each(function(){
			ele = jQuery( this ).closest('form').find( '[type=submit]' );
			if ( ele.length > 0 ) {
				ele.attr( 'disabled', '' );
			}
		});
	}
	if ( value === true ) {
		jQuery('form').find( '.kt-g-recaptcha' ).each(function(){
			ele = jQuery( this ).closest('form').find( '[type=submit]' ); 
			if ( ele.length > 0 ) {
				ele.removeAttr ('disabled');
			}
			//jQuery( this ).closest('form').append( '<input type="hidden" name="ktwpiprv" value="' + address + '">' );
		});
	}
}

// Ajax connection for verifying response through the secret key
var ktrespVerifyCallback = function( ktresp ) {
	change_button( true, '' );
	// jQuery.ajax({
	// 	url : ktrecap.ajax_url,
	// 	type : 'POST',
	// 	data : { 
	// 		'action' : 'kadence_verify_recaptcha',
	// 		'resp'	 : ktresp,
	// 	}, 
	// 	dataType : 'json',
	// 	success : function( ktrespr ) {
	// 		if ( ktrespr.data.result === 'OK' ) {
	// 			change_button( true, ktrespr.data.address );
	// 		} else {
	// 			console.log( ktrespr );
	// 		}
	// 	},
	// 	error : function( errorThrown ) {
	// 		console.log( errorThrown );
	// 	}
	// });
};
if ( typeof wc_checkout_params !== 'undefined' ) {
	jQuery( document.body ).on( 'checkout_error', function(){
		setTimeout(function(){ 
			change_button( null, null );
			var widget_id = jQuery( '#kt_g_recaptcha_checkout' ).attr( 'rcid' );
			grecaptcha.reset( widget_id );
		}, 100);
	});
	jQuery( document.body ).on( 'updated_checkout', function(){
		setTimeout(function(){
			change_button( null, null );
			if ( jQuery( '#kt_g_recaptcha_checkout' ).children().length > 0 ) {
				var widget_id = jQuery( '#kt_g_recaptcha_checkout' ).attr( 'rcid' );
				grecaptcha.reset( widget_id );
			} else {
				kt_reload_captcha_checkout();
			}
		}, 100);
	});
}
// Global onload Method
var ktrecaploadCallback = function() {
	jQuery('form').find( '.kt-g-recaptcha' ).each(function(){
		var single_ktrecap = grecaptcha.render( jQuery( this ).attr('id'), {
			'sitekey' : ktrecap.recaptcha_skey,
			'theme' : ktrecap.recaptcha_theme,
			'type' : ktrecap.recaptcha_type,
			'size' : ktrecap.recaptcha_size,
			'tabindex' : 0,
			'callback' : ktrespVerifyCallback
		} );
		jQuery( this ).attr( 'rcid', single_ktrecap );
	});
};
// Global onload Method
var kt_reload_captcha_checkout = function() {
	jQuery('form').find( '#kt_g_recaptcha_checkout' ).each(function(){
		var single_ktrecap = grecaptcha.render( 'kt_g_recaptcha_checkout', {
			'sitekey' : ktrecap.recaptcha_skey,
			'theme' : ktrecap.recaptcha_theme,
			'type' : ktrecap.recaptcha_type,
			'size' : ktrecap.recaptcha_size,
			'tabindex' : 0,
			'callback' : ktrespVerifyCallback
		} );
		jQuery( this ).attr( 'rcid', single_ktrecap );
	});
};
(function ($) { change_button ( null, null); })(jQuery);


