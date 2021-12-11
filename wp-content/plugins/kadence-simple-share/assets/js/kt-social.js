(function() {
	'use strict';
	var socialLinkClicked = function( e ) {
		e.preventDefault()
		var url = e.target.href;
		var opts   = 'status=1' +
					',titlebar=no' +
		            ',width='  + 575 +
		            ',height=' + 520 +
		            ',top='    + ( window.innerHeight - 520 ) / 2  +
		            ',left='   + ( window.innerWidth - 575 ) / 2;
  		window.open( url, 'share', opts );
	};
	var kadenceSocialPop = function() {
		var socialLinks = document.querySelectorAll( '.kt_simple_share_container a:not(.kt_no_pop_window)' );
		for(var i = 0, len = socialLinks.length; i < len; i++) {
			socialLinks[i].addEventListener( 'click', socialLinkClicked, false );
		}
	};
	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', kadenceSocialPop );
	} else {
		kadenceSocialPop();
	}
})();