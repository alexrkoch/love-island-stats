/*
 * waitForImages
 * MIT License
 */
 !function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){var b="waitForImages";a.waitForImages={hasImageProperties:["backgroundImage","listStyleImage","borderImage","borderCornerImage","cursor"],hasImageAttributes:["srcset"]},a.expr[":"]["has-src"]=function(b){return a(b).is('img[src][src!=""]')},a.expr[":"].uncached=function(b){return!!a(b).is(":has-src")&&!b.complete},a.fn.waitForImages=function(){var f,g,h,c=0,d=0,e=a.Deferred();if(a.isPlainObject(arguments[0])?(h=arguments[0].waitForAll,g=arguments[0].each,f=arguments[0].finished):1===arguments.length&&"boolean"===a.type(arguments[0])?h=arguments[0]:(f=arguments[0],g=arguments[1],h=arguments[2]),f=f||a.noop,g=g||a.noop,h=!!h,!a.isFunction(f)||!a.isFunction(g))throw new TypeError("An invalid callback was supplied.");return this.each(function(){var i=a(this),j=[],k=a.waitForImages.hasImageProperties||[],l=a.waitForImages.hasImageAttributes||[],m=/url\(\s*(['"]?)(.*?)\1\s*\)/g;h?i.find("*").addBack().each(function(){var b=a(this);b.is("img:has-src")&&!b.is("[srcset]")&&j.push({src:b.attr("src"),element:b[0]}),a.each(k,function(a,c){var e,d=b.css(c);if(!d)return!0;for(;e=m.exec(d);)j.push({src:e[2],element:b[0]})}),a.each(l,function(a,c){var d=b.attr(c);return!d||void j.push({src:b.attr("src"),srcset:b.attr("srcset"),element:b[0]})})}):i.find("img:has-src").each(function(){j.push({src:this.src,element:this})}),c=j.length,d=0,0===c&&(f.call(i[0]),e.resolveWith(i[0])),a.each(j,function(h,j){var k=new Image,l="load."+b+" error."+b;a(k).one(l,function b(h){var k=[d,c,"load"==h.type];if(d++,g.apply(j.element,k),e.notifyWith(j.element,k),a(this).off(l,b),d==c)return f.call(i[0]),e.resolveWith(i[0]),!1}),j.srcset&&(k.srcset=j.srcset),k.src=j.src})}),e.promise()}});

jQuery(document).ready(function ($) {
	   	// $(document).on( 'pswpTap', function ( event ) {
		// 	   console.log( 'here' );
	   	// 	if (typeof event.target.offsetParent.className !== 'undefined') {
		// 	   	if(event.target.offsetParent.className == 'pswp__caption'){
		// 			kadenceToggleCaption();
		// 			   console.log( 'here>>' );
		// 		   //	$('.pswp__ui').toggleClass('pswp__ui--hidden');
		// 		} else if(event.target.offsetParent.className == 'pswp__caption active'){
		// 			kadenceToggleCaption();
		// 		}
		// 	}
	   	// });
	    var kt_initIso = function(container, selector) {
	    	var filter = container.data('gallery-filter');
	    	var glightbox = container.data('gallery-lightbox');
	        container.waitForImages( function(){
	            container.removeClass('kt-loadeding');
	            container.addClass('kt-loaded');
	            container.siblings('.kt-galleries-loading').removeClass('kt-loadeding');
	            container.siblings('.kt-galleries-loading').addClass('kt-loaded');
	            // init .isotopeb
	            container.isotopeb({
	            	masonry: {columnWidth: selector},
	            	layoutMode:'masonry',
	                itemSelector: selector,
	                transitionDuration: '0.4s',
	            });
	            var isochild = container.find('.kt-gal-fade-in');
				isochild.each(function(i){
					jQuery(this).delay(i*50).animate({'opacity':1},150);
				});
				if(filter == true) {
					var thisparent = container.parents('.kt-gal-outer');
					var thisfilters = thisparent.find('.kt-filters');
					if(thisfilters.length) {
						thisfilters.find('a').on( 'click', function( event ) {
							event.preventDefault();
							var filtr = jQuery(this).attr('data-filter');
							 	container.isotopeb({ filter: filtr });
							 	var filteredelems = container.isotopeb('getFilteredItemElements');
							 	var elems = container.isotopeb('getItemElements');
							 	$(elems).addClass('kt-filtered-out');
							    $(filteredelems).removeClass('kt-filtered-out');
							    if(glightbox =='magnific') {
		        					kt_initMagnific(container);
		       					} else {
									initPhotoSwipeFromDOM('.kt-galleries-container');
								}
						});
						var $optionSets = jQuery('.kt-filters .kt-option-set'),
		          		$optionLinks = $optionSets.find('a');	
						$optionLinks.click(function(){ 
							var $this = jQuery(this); if ( $this.hasClass('selected') ) {return false;}
							var $optionSet = $this.parents('.kt-option-set'); $optionSet.find('.selected').removeClass('selected'); $this.addClass('selected');
						});
					}
				}
	        });
	    };
	    var kt_initPack = function(container, selector) {
	    	var filter = container.data('gallery-filter');
	    	var glightbox = container.data('gallery-lightbox');
	    	if($('body.rtl').length){
				var iso_rtl = false;
			} else {
				var iso_rtl = true;
			}
	        container.waitForImages( function(){
	            container.removeClass('kt-loadeding');
	            container.addClass('kt-loaded');
	            container.siblings('.kt-galleries-loading').removeClass('kt-loadeding');
	            container.siblings('.kt-galleries-loading').addClass('kt-loaded');
	            // init .isotopeb
	            container.isotopeb({
					layoutMode: 'packery',
					percentPosition: true,
					itemSelector: selector, 
					transitionDuration: '.8s', 
					packery: {
					  horizontal: true,
					  columnWidth: '.mosaic-grid-size'
					},
					isOriginLeft: iso_rtl
				});
	            var isochild = container.find('.kt-gal-fade-in');
				isochild.each(function(i){
					jQuery(this).delay(i*50).animate({'opacity':1},150);
				});
				if(filter == true) {
					var thisparent = container.parents('.kt-gal-outer');
					var thisfilters = thisparent.find('.kt-filters');
					if(thisfilters.length) {
						thisfilters.find('a').on( 'click', function( event ) {
							event.preventDefault();
							var filtr = jQuery(this).attr('data-filter');
							 	container.isotopeb({ filter: filtr });
							 	var filteredelems = container.isotopeb('getFilteredItemElements');
							 	var elems = container.isotopeb('getItemElements');
							 	$(elems).addClass('kt-filtered-out');
							    $(filteredelems).removeClass('kt-filtered-out');
							    if(glightbox =='magnific') {
		        					kt_initMagnific(container);
		       					} else {
									initPhotoSwipeFromDOM('.kt-galleries-container');
								}
						});
						var $optionSets = jQuery('.kt-filters .kt-option-set'),
		          		$optionLinks = $optionSets.find('a');	
						$optionLinks.click(function(){ 
							var $this = jQuery(this); if ( $this.hasClass('selected') ) {return false;}
							var $optionSet = $this.parents('.kt-option-set'); $optionSet.find('.selected').removeClass('selected'); $this.addClass('selected');
						});
					}
				}
	        });
	    };
	    var kt_initTiles = function(container, selector) {
	    	var height = container.data('gallery-height'),
	    	lastrow = container.data('gallery-lastrow'),
	    	margins = container.data('gallery-margins');
	        container.waitForImages( function(){
	            container.removeClass('kt-loadeding');
	            container.addClass('kt-loaded');
	            container.siblings('.kt-galleries-loading').removeClass('kt-loadeding');
	            container.siblings('.kt-galleries-loading').addClass('kt-loaded');
	            // init tiles
				container.justifiedGallery({
					rowHeight: height,
					lastRow : lastrow,
					captions:false,
					margins:margins,
					//selector:'.kt-gallery-item',
					waitThumbnailsLoad:false,
				});
	            var isochild = container.find('.kt-gal-fade-in');
				isochild.each(function(i){
					jQuery(this).delay(i*50).animate({'opacity':1},150);
				});
	        });
	    };
	    var kt_initMagnific = function(container) {
	    	container.each(function(){
				$(this).find('.kt-gallery-item:not(.kt-filtered-out):not(.kt-gal-external) a.kt-no-lightbox').magnificPopup({
					type: 'image',
					gallery: {
						enabled:true
						},
						image: {
							titleSrc: function(item) {
							return item.el.find('.kt-gallery-caption-container').html();
							}
						},
					removalDelay: 500, //delay removal by X to allow out-animation
					callbacks: {
						    beforeOpen: function() {
						      // just a hack that adds mfp-anim class to markup 
						         $('body').addClass('kt-galleries-mag-pop');
						    }
						},
					});
			});
	    }
	    $('.kt-local-gallery').on('destroy', function() {
		  setTimeout(function() {
		    $('.pswp').removeClass().addClass('pswp');
		  }, 100);
		});
/* Event handlers */
var initcaptionToggle = function() {
    $('.pswp__caption').on( 'click', kadenceToggleCaption );
};

var kadenceToggleCaption = function() {
    $('.pswp__caption:not(.pswp__caption--fake)').toggleClass('active');
    $('.pswp__button--show--caption.kt-btn-hide').toggleClass('force-hide');
    $('.pswp__button--show--caption.kt-btn-show').toggleClass('force-hide');
};
var initPhotoSwipeTemplate = function() {
	if ( ! $('.kadence-galleries-pswp').length ) {
		$(document.body).append('<div class="pswp kadence-galleries-pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="' + kadenceGallery.close + '"></button><button class="pswp__button pswp__button--share" title="' + kadenceGallery.share + '"></button><button class="pswp__button pswp__button--fs" title="' + kadenceGallery.togglefull + '"></button><button class="pswp__button pswp__button--zoom" title="' + kadenceGallery.zoom + '"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="' + kadenceGallery.prev + '"></button><button class="pswp__button pswp__button--arrow--right" title="' + kadenceGallery.next + '"></button><div class="pswp__caption"><div class="pswp__caption__center"></div><button class="pswp__button pswp__button--show--caption pswp__single-tap kt-btn-show">' + kadenceGallery	.fullcaption + '</button><button class="pswp__button pswp__button--show--caption pswp__single-tap kt-btn-hide force-hide">' + kadenceGallery.mincaption + '</button></div></div></div></div>');
	}
}
/* Photoswipe gallery */
var initPhotoSwipeFromDOM = function (gallerySelector ) {

	    // parse slide data (url, title, size ...) from DOM elements 
	    // (children of gallerySelector)
	    var parseThumbnailElements = function(el) {
	        var thumbElements = $(el).find('.kt-gallery-item:not(.kt-filtered-out):not(.kt-gal-external)'),
	            numNodes = thumbElements.length,
	            items = [],
	            figureEl,
	            linkEl,
	            size,
	            item;

	        for(var i = 0; i < numNodes; i++) {

	            figureEl = thumbElements[i]; // <figure> element

	            // include only element nodes 
	            if(figureEl.nodeType !== 1) {
	                continue;
	            }

	            linkEl = figureEl.children[0]; // <a> element

	            size = linkEl.getAttribute('data-size').split('x');

	            // create slide object
	            item = {
	                src: linkEl.getAttribute('href'),
	                w: parseInt(size[0], 10),
	                h: parseInt(size[1], 10)
	            };


	            //if(figureEl.children.children.length > 1) {
	                // <figcaption> content
	                item.title = figureEl.children[0].children[2].children[0].innerHTML; 
	            //}

	            if(linkEl.children.length > 0) {
	                // <img> thumbnail element, retrieving thumbnail url
	                item.msrc = linkEl.children[0].getAttribute('src');
	            } 

	            item.el = figureEl; // save link to element for getThumbBoundsFn
	            items.push(item);
	        }

	        return items;
	    };

	    // find nearest parent element
	    var closest = function closest(el, fn) {
	        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
	    };

	    // triggers when user clicks on thumbnail
	    var onThumbnailsClick = function(e) {
	        e = e || window.event;
	        e.preventDefault ? e.preventDefault() : e.returnValue = false;
	        var eTarget = e.target || e.srcElement;
	        // find root element of slide
	        var clickedListItem = closest(eTarget, function(el) {
	        	if( $(el).hasClass('kadence-galleries-pinterest-btn') ) {
	        		 return ($(el).hasClass('kadence-galleries-pinterest-btn'));
	        	} else {
	               return ($(el).hasClass('kt-gallery-item'));
	           }
	        } );

	        if( ! clickedListItem ) {
	            return;
	        }
	         if($(clickedListItem).hasClass('kadence-galleries-pinterest-btn') ) {
	        	var target = $(clickedListItem).attr('target'); 
	        	var link = $(clickedListItem).attr('href'); 
        		window.open(link,target);
	        	return;
	        }
	        // var externaltarget = closest(eTarget, function(el) {
	        if($(clickedListItem).hasClass('kt-gal-external') ) {
	        	var target = $(clickedListItem).children('a.kt-no-lightbox').attr('target'); 
	        	var link = $(clickedListItem).children('a.kt-no-lightbox').attr('href'); 
        		if(!target || target == '' || target == '_self') {
        			window.location = link;
        		} else {
        			window.open(link,target);
        		}
	        	return;
	        }

	        // find index of clicked item by looping through all child nodes
	        // alternatively, you may define index via data- attribute
	        var clickedGallery = clickedListItem.parentNode,
	            childNodes = $(clickedGallery).find('.kt-gallery-item:not(.kt-filtered-out):not(.kt-gal-external)'),
	            numChildNodes = childNodes.length,
	            nodeIndex = 0,
	            index;
	        for (var i = 0; i < numChildNodes; i++) {
	            if(childNodes[i].nodeType !== 1) { 
	                continue; 
	            }

	            if(childNodes[i] === clickedListItem) {
	                index = nodeIndex;
	                break;
	            }
	            nodeIndex++;
	        }



	        if(index >= 0) {
	            // open PhotoSwipe if valid index found
	            openPhotoSwipe( index, clickedGallery );
	        }
	        return false;
	    };

	    // parse picture index and gallery index from URL (#&pid=1&gid=2)
	    var photoswipeParseHash = function() {
	        var hash = window.location.hash.substring(1),
	        params = {};

	        if(hash.length < 5) {
	            return params;
	        }

	        var vars = hash.split('&');
	        for (var i = 0; i < vars.length; i++) {
	            if(!vars[i]) {
	                continue;
	            }
	            var pair = vars[i].split('=');  
	            if(pair.length < 2) {
	                continue;
	            }           
	            params[pair[0]] = pair[1];
	        }

	        if(params.gid) {
	            params.gid = parseInt(params.gid, 10);
	        }

	        return params;
	    };

	    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
	        var pswpElement = document.querySelectorAll('.pswp')[0],
	            gallery,
	            options,
	            items;

	        items = parseThumbnailElements(galleryElement);

	        // define options (if needed)
	        options = {

	            // define gallery index (for URL)
	            galleryUID: galleryElement.getAttribute('data-pswp-uid'),

	            getThumbBoundsFn: function(index) {
	                // See Options -> getThumbBoundsFn section of documentation for more info
	                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
	                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
	                    rect = thumbnail.getBoundingClientRect(); 

	                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
	            },
	            // Function builds caption markup
	            addCaptionHTMLFn: function(item, captionEl, isFake) {
	            		if ( ! item.title || item.title == 'null' ) {
	                        captionEl.children[0].innerHTML = '';
	                        if (!isFake) {
	                         	$(captionEl).find('.pswp__button--show--caption.kt-btn-show').hide();
	                       	 	$(captionEl).find('.pswp__button--show--caption.kt-btn-hide').hide();
	                        	$(captionEl).addClass('caption-empty');
	                        }
	                        return false;
	                    }

	                    captionEl.children[0].innerHTML = item.title;

	                    // Check for real caption (non caption sizer)
	                    if (!isFake) {
	                        // Caption handlers and positioning
	                        var $captionEl = $(captionEl);
	                        $captionEl.removeClass('caption-empty');
	                        $captionEl.find('.pswp__caption__center').css('max-width', 'none');
	                        var $captionCenterEl = $captionEl.find('.pswp__caption__center');
	                        var imgWidth = item.w;
	                        var captionOffset = 0;
	                        if (item.initialPosition !== undefined) {
	                            captionOffset = -(item.initialPosition.y) + 2; // Add 2 to compensate for positioning bug
	                        }
	                        if (item.fitRatio !== undefined && item.fitRatio < 1) {
	                            imgWidth = parseInt(parseFloat(item.w) * parseFloat(item.fitRatio));
	                        }

	                        if($captionCenterEl.width() < (imgWidth - 20)) {
	                        	$captionEl.addClass('caption-shown-full');
								$captionEl.removeClass('caption-trunk');
	                        	//$('.pswp__button--show--caption.kt-btn-hide').hide();
	                        } else {
	                        	$captionEl.addClass('caption-trunk');
								$captionEl.removeClass('caption-shown-full');
	                        	//$('.pswp__button--show--caption.kt-btn-hide').show();
	                        }

	                        $captionEl.find('.pswp__caption__center').css('max-width', imgWidth);
	                        $captionEl.css({
                            '-webkit-transform' : 'translate3d(0,' + captionOffset + 'px, 0)',
                            '-moz-transform'    : 'translate3d(0,' + captionOffset + 'px, 0)',
                            '-ms-transform'     : 'translate3d(0,' + captionOffset + 'px, 0)',
                            '-o-transform'      : 'translate3d(0,' + captionOffset + 'px, 0)',
                            'transform'         : 'translate3d(0,' + captionOffset + 'px, 0)'
                        });
	                    }
	                    return false;
	                },

	            shareButtons: [
	                    {id:'facebook', label:'Share on Facebook', url:'https://www.facebook.com/sharer/sharer.php?u={{url}}'},
	                    {id:'twitter', label:'Tweet', url:'https://twitter.com/intent/tweet?text={{text}}&url={{url}}'},
	                    {id:'pinterest', label:'Pin it', url:'http://www.pinterest.com/pin/create/button/?url={{url}}&media={{image_url}}&description={{text}}'},
	                    {id:'download', label:'Download Image', url:'{{raw_image_url}}', download:true}
	                ]

	        };

	        // PhotoSwipe opened from URL
	        if(fromURL) {
	            if(options.galleryPIDs) {
	                // parse real index when custom PIDs are used 
	                // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
	                for(var j = 0; j < items.length; j++) {
	                    if(items[j].pid == index) {
	                        options.index = j;
	                        break;
	                    }
	                }
	            } else {
	                // in URL indexes start from 1
	                options.index = parseInt(index, 10) - 1;
	            }
	        } else {
	            options.index = parseInt(index, 10);
	        }

	        // exit if index not found
	        if( isNaN(options.index) ) {
	            return;
	        }

	        if(disableAnimation) {
	            options.showAnimationDuration = 0;
	        }

	        // Pass data to PhotoSwipe and initialize it
	        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
	        gallery.init();
	    };

	    // loop through all gallery elements and bind events
	    var galleryElements = document.querySelectorAll( gallerySelector );
	    for(var i = 0, l = galleryElements.length; i < l; i++) {
	        galleryElements[i].setAttribute('data-pswp-uid', i+1);
	        galleryElements[i].onclick = onThumbnailsClick;
	    }

	    // Parse URL and open gallery if it contains #&pid=3&gid=1
	    var hashData = photoswipeParseHash();
	    if(hashData.pid && hashData.gid) {
	        openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
	    }
	};

	$('.kt-photoshelter-gallery').each(function(){
		var gallerycontainer = $(this);
		var imgWidth = $(this).data('image-width');
		var imgHeight = $(this).data('image-height');
		var ratio = $(this).data('ratio');
		var gtype = $(this).data('gallery-type');
		var linkto = $(this).data('images-link');
		var apiKey = $(this).data('api');
	    var galleryID = $(this).data('gallery-id');
	    var glightbox = $(this).data('gallery-lightbox');
	    var data = '';
	    var galleryClass = '.kt-gallery-id-'+galleryID;
	    var galEp = '/psapi/v3/gallery/' + galleryID + '/images';
	    var params = [
	        'api_key=' + apiKey,
	        "fields=image_id,file_name,file_size"
	    ];
	    if (location.protocol != 'https:' ) {
	    	var protocol = 'http';
	    	var imgProtocal = false;
	    } else {
			var protocol = 'https';
			var imgProtocal = true;
	    }
	    var paramsStr = params.join("&");
	    var initEp = galEp + '?' + paramsStr;
	    var extendGal = {
	        "GalleryImage": {
	            "fields": "image_id,file_name,file_size",
	            "params": []
	        },
	        "Image": {},
	        "Iptc": {
	            "fields": "caption"
	        },
	        "ImageLink": {
	            "fields": "link,base",
	            "params": {
	            	"f_https_link": imgProtocal,
	                "image_mode": "fit",
	                "image_size": "300x300"
	            }
	        }
	    };
	    if(ratio == 'cropped') {
        	var img_ratio = 'fill';
        } else {
        	var img_ratio = 'fit';
        }
        if(linkto == 'photoshelter') {
        	glightbox = 'none';
        }
	    var extendStr = "extend=" + JSON.stringify(extendGal);

	    var request = protocol + '://www.photoshelter.com' + initEp + "&" + extendStr;

	    $(function() {
	        $.get(request, function(data) {
	            initialize(data);
	        });
	    });
	    var buildGallery = function(response) {
		    var markup = '';
		    var gallery = response.data.GalleryImage;
		    for (var i in gallery) {
		        var m = buildImageString(gallery[i]);
		        markup += m;
		    }
		    gallerycontainer.append(markup);
		};
		var buildPackGallery = function(response) {
		    var markup = '';
		    var gallery = response.data.GalleryImage;
		    var count = gallery.length;
		    for (var i in gallery) {
		    	var item =  +i+1;
		        var m = buildpackImageString(gallery[i], item, count);
		        markup += m;
		    }
		    gallerycontainer.append(markup);
		};
		var buildpackImageString = function(galleryImage, item, count) {
			if(8 == count || 16 == count) {
				var image_width = 400,
				image_height = 400,
				size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-4 ktg-ss-mosaic-4 mosaic-grid-size'; 
				if(item == 1 || item == 16) {
		            image_width = 800;
		            image_height = 400;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-8'; 
		        } else if (item == 3 || item == 13) {
		            image_width = 400;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-4 ktg-ss-mosaic-4'; 
		        } else if (item == 2 || item == 5 || item == 10 || item == 14) {
		            image_width = 800;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-8 mosaic-sm-wide-grid-size'; 
		        }
		    } else if(count == '6' || count == '12' || count == '24' || count == '30') {
		    	if(item > 12) {
		    		item = Math.round(item - 12);
		    	}
		    	var image_width = 400,
		            image_height = 400,
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-4 ktg-ss-mosaic-4 mosaic-grid-size'; 
		        if(item == 1 || item == 5 || item == 8 || item == 12) {
		            image_width = 800;
		            image_height = 400;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-8'; 
		        } else if (item == 3 || item == 9) {
		            image_width = 400;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-4 ktg-ss-mosaic-4'; 
		        } else if (item == 2 || item == 10) {
		            image_width = 800;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-8 mosaic-large-grid-size'; 
		        }
		    } else if(count == '7' || count == '14' || count == '21' || count == '28' ) {
		    	if(item > 14) {
		    		item = Math.round(item - 14);
		    	}
		    	var image_width = 400,
		            image_height = 400,
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-4 ktg-ss-mosaic-6 mosaic-grid-size'; 
		        if(item == 7 || item == 8) {
		            image_width = 800;
		            image_height = 400;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-12'; 
		        } else if (item == 3 || item == 11) {
		            image_width = 400;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-4 ktg-ss-mosaic-6'; 
		        } else if (item == 1) {
		            image_width = 800;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-12 mosaic-large-grid-size'; 
		        }  else if (item == 13) {
		            image_width = 800;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-4 ktg-ss-mosaic-6 mosaic-large-wide-grid-size mosaic-ss-tall-grid-size'; 
		        }
		    } else if(count == '10' || count == '20') {
		    	if(item > 10) {
		    		item = Math.round(item - 10);
		    	}
		        if($i == 2) {
		            image_width = 800;
		            image_height = 400;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-8'; 
		        } else if ($i == 6) {
		            image_width = 800;
		            image_height = 400;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-12'; 
		        } else if ($i == 5) {
		            image_width = 800;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-4 ktg-ss-mosaic-12'; 
		        }else if ($i == 8) {
		            image_width = 800;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-6 ktg-sm-mosaic-8 ktg-ss-mosaic-8 mosaic-sm-wide-grid-size'; 
		        } else if ($i == 1 || $i == 7 ) {
		            image_width = 400;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-4 ktg-ss-mosaic-4 mosaic-tall-grid-size';
		        } else {
		            image_width = 400;
		            image_height = 400;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-4 ktg-ss-mosaic-4 mosaic-grid-size';  
		        }
			} else {
				if(item > 19) {
		    		item = Math.round(item - 19);
		    	}
				var image_width = 400,
				image_height = 400,
				size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-3 ktg-ss-mosaic-6 mosaic-grid-size'; 
				if(item == 5 || item == 16) {
		            image_width = 800;
		            image_height = 400;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-6 ktg-ss-mosaic-12 mosaic-sm-square-grid-size mosaic-ss-inherit-wide'; 
		        }else if(item == 17) {
		            image_width = 800;
		            image_height = 400;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-6 ktg-ss-mosaic-12'; 
		        } else if (item == 3 || item == 13) {
		            image_width = 400;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-3 ktg-ss-mosaic-6'; 
		        } else if (item == 8) {
		            image_width = 400;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-25 ktg-mosaic-3 ktg-sm-mosaic-3 ktg-ss-mosaic-6 mosaic-tall-grid-size mosaic-tall-sm-square-grid-size'; 
		        } else if (item == 1 || item == 12 || item == 18 || item == 19) {
		            image_width = 800;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-6 ktg-ss-mosaic-12 mosaic-large-wide-grid-size'; 
		        } else if (item == 10) {
		            image_width = 800;
		            image_height = 800;
		            size_class = 'ktg-xxl-mosaic-40 ktg-mosaic-6 ktg-sm-mosaic-6 ktg-ss-mosaic-6 mosaic-large-wide-grid-size'; 
		        }
		    }
	        var imageID = galleryImage.image_id;
	        var fileName = encodeURI(galleryImage.Image.file_name);
	        var link = galleryImage.ImageLink.link;
	        var base = galleryImage.ImageLink.base;
	        var token = galleryImage.ImageLink.token;
	        var caption = galleryImage.Image.Iptc.caption;
	        var headline = galleryImage.Image.Iptc.headline;
	        var title = galleryImage.Image.Iptc.title;
	        var sizeFull = galleryImage.Image.screen_width + 'x' + galleryImage.Image.screen_height;
	        var srcsetThumb = base + '/sec=' + token + '/'+img_ratio+'='+Math.round(image_width*2)+'x'+Math.round(image_height*2)+'/' + fileName;
	        var srcThumb = base + '/sec=' + token + '/'+img_ratio+'='+image_width+'x'+image_height+'/' + fileName;
	        var srcFull = base + '/sec=' + token + '/fit=1600x1200/' + fileName;
	        var single_link = srcFull;
	        if(linkto == 'photoshelter') {
		    	single_link = 'https://www.photoshelter.com/gallery-image/'+galleryID+'/'+imageID;
	        }
	        var paddingbtn = Math.round((image_height/image_width)*100);
	        if(caption == null) {
	         	var caption_class = 'kt-no-caption';
	         	caption = '';
	        } else {
	         	var caption_class = 'kt-has-caption';
	        }
	        var m = '<div class="kt-gallery-item '+caption_class+' '+size_class+'">' +
	                    '<a href="'+single_link+'" class="kt-no-lightbox kt-gal-fade-in" data-size="'+sizeFull+'">' +
	                    	'<div itemprop="image" class="kt-gallery-intrinsic" itemscope="" itemtype="http://schema.org/ImageObject" style="padding-bottom:'+paddingbtn+'%">' +
	                    		'<div class="kt-gallery-intrinsic-inner">' +
	                        	'<img src="'+srcThumb+'" alt="'+headline+'" itemprop="contentUrl" width="'+image_width+'" height="'+image_height+'"  />' +
	                        	'<meta itemprop="url" content="'+srcThumb+'">'+
	                        	'<meta itemprop="width" content="'+image_width+'">'+
	                        	'<meta itemprop="height" content="'+image_height+'">'+
	                        	'</div>'+
	                        '</div>'+
	                        '<div class="kt-gallery-item-overlay">' +
	                        	'<div class="kt-overlay-border"></div>'+
	                        	'<div class="kt-gallery-align-vertical">'+ 
	                        		'<i class="kt-gallery-item-icon"></i>'+
	                        	'</div>'+
	                        '</div>' +
		                    '<div class="kt-gallery-caption-container">'+
		                    	'<div class="kt-gallery-caption">'+caption+'</div>' +
		                    '</div>' +
	                    '</a>' +
	                '</div>';

	        return m;
	    };
		var buildImageString = function(galleryImage) {
	        var imageID = galleryImage.image_id;
	        var fileName = encodeURI(galleryImage.Image.file_name);
	        var link = galleryImage.ImageLink.link;
	        var base = galleryImage.ImageLink.base;
	        var token = galleryImage.ImageLink.token;
	        var caption = galleryImage.Image.Iptc.caption;
	        var headline = galleryImage.Image.Iptc.headline;
	        var title = galleryImage.Image.Iptc.title;
	        var sizeFull = galleryImage.Image.screen_width + 'x' + galleryImage.Image.screen_height;
	        if(ratio == 'notcropped' && gtype == 'masonry') {
		        var ratio_width = galleryImage.Image.screen_width;
		        var ratio_height = galleryImage.Image.screen_height;
		        var ratiosize = ratio_height/ratio_width;
		        imgHeight = Math.round(ratiosize*imgWidth);
		    } else if(ratio == 'notcropped' && gtype == 'tiles') {
		        var ratio_width = galleryImage.Image.screen_width;
		        var ratio_height = galleryImage.Image.screen_height;
		        var ratiosize = ratio_width/ratio_height;
		        imgWidth = Math.round(imgHeight*ratiosize);
		    }
	        var srcsetThumb = base + '/sec=' + token + '/'+img_ratio+'='+Math.round(imgWidth*2)+'x'+Math.round(imgHeight*2)+'/' + fileName;
	        var srcThumb = base + '/sec=' + token + '/'+img_ratio+'='+imgWidth+'x'+imgHeight+'/' + fileName;
	        var srcFull = base + '/sec=' + token + '/fit=1600x1200/' + fileName;
	        var single_link = srcFull;
	        if(linkto == 'photoshelter') {
		    	single_link = 'https://www.photoshelter.com/gallery-image/'+galleryID+'/'+imageID;
	        }
	         if(caption == null) {
	         	var caption_class = 'kt-no-caption';
	         	caption = '';
	         } else {
	         	var caption_class = 'kt-has-caption';
	         }
	        var m = '<div class="kt-gallery-item '+caption_class+'">' +
	                    '<a href="'+single_link+'" class="kt-no-lightbox kt-gal-fade-in" data-size="'+sizeFull+'">' +
	                    	'<div itemprop="image" class="kt-image-item" itemscope="" itemtype="http://schema.org/ImageObject">' +
	                        	'<img src="'+srcThumb+'" alt="'+headline+'" itemprop="contentUrl" width="'+imgWidth+'" height="'+imgHeight+'" srcset="'+srcThumb+' 1x, '+srcsetThumb+' 2x" />' +
	                        	'<meta itemprop="url" content="'+srcThumb+'">'+
	                        	'<meta itemprop="width" content="'+imgWidth+'">'+
	                        	'<meta itemprop="height" content="'+imgHeight+'">'+
	                        '</div>'+
	                        '<div class="kt-gallery-item-overlay">' +
	                        	'<div class="kt-overlay-border"></div>'+
	                        	'<div class="kt-gallery-align-vertical">'+ 
	                        		'<i class="kt-gallery-item-icon"></i>'+
	                        	'</div>'+
	                        '</div>' +
		                    '<div class="kt-gallery-caption-container">'+
		                    	'<div class="kt-gallery-caption">'+caption+'</div>' +
		                    '</div>' +
	                    '</a>' +
	                '</div>';

	        return m;
	    };

	    var initialize = function(data) {
	        // Build gallery into DOM
	        if(gtype == 'packery'){
	        	 buildPackGallery(data);
	        } else {
	        	 buildGallery(data);
	        }
	        // Init lightbox functionality
	        if(glightbox =='magnific') {
	        	kt_initMagnific(gallerycontainer);
	        } else if(glightbox =='none') {
	        	// Do nothing
	        } else {
	        	initPhotoSwipeTemplate();
	        	initPhotoSwipeFromDOM('.kt-galleries-container');
		        // Init caption toggle behavior
	        	initcaptionToggle();
	        }
	        // Init masonry grid
	        if(gtype == 'tiles') {
	       		kt_initTiles(gallerycontainer, '.kt-gallery-item');
	       	} else if(gtype == 'packery'){
		       		kt_initPack(gallerycontainer, '.kt-gallery-item');
		    } else {
	       		kt_initIso(gallerycontainer, '.kt-gallery-item');
	       	}
	        // Init caption toggle behavior
	        initcaptionToggle();
	        
	    };
	});
	$('.kt-local-gallery').each(function(){
			var gallerycontainer = $(this);
			var gtype = $(this).data('gallery-type');
			var glightbox = $(this).data('gallery-lightbox');
			var id = $(this).data('gallery-id');

		    var initialize = function() {
		        // Init lightbox functionality
		        if(glightbox =='magnific') {
		        	kt_initMagnific(gallerycontainer);
		        } else if(glightbox =='none') {
	        	// Do nothing
	        	} else {
	        		initPhotoSwipeTemplate();
		        	initPhotoSwipeFromDOM( '.kt-gallery-' + id );
			        // Init caption toggle behavior
		        	initcaptionToggle();
		        }
		        // Init grid
		        if(gtype == 'tiles') {
		       		kt_initTiles(gallerycontainer, '.kt-gallery-item');
		       	} else if(gtype == 'packery'){
		       		kt_initPack(gallerycontainer, '.kt-gallery-item');
		       	} else {
		       		kt_initIso(gallerycontainer, '.kt-gallery-item');
		       	}

		    };

		    initialize();
	});

	if ($('.woocommerce-tabs .kt-local-gallery').length) {
		$('.woocommerce-tabs .kt-local-gallery').each(function(){
			var $container = $(this),
			gtype = $(this).data('gallery-type');
			function kt_gall_woo_refreash_iso(){
				$container.isotopeb('layout');
			}
			$('.woocommerce-tabs ul.tabs li a' ).on( 'click', function() {
				if( gtype == 'tiles' ) {
					$container.justifiedGallery();
				} else {
					setTimeout(kt_gall_woo_refreash_iso, 50);
				}
			});
		});
	}
	if ($('.panel-body .kt-local-gallery').length) {
		$('.tab-pane .kt-local-gallery').each(function(){
			var $container = $(this),
			gtype = $(this).data('gallery-type');
			$('.panel-group').on('shown.bs.collapse', function  (e) {
				if(gtype == 'tiles') {
					$container.justifiedGallery();
				} else {
					$container.isotopeb('layout');
				}
			});
		});
	}
	if ($('.tab-pane .kt-local-gallery').length) {
		$('.tab-pane .kt-local-gallery').each(function(){
			var $container = $(this),
			gtype = $(this).data('gallery-type');
			$('.sc_tabs').on('shown.bs.tab', function  (e) {
				if(gtype == 'tiles') {
					$container.justifiedGallery();
				} else {
					$container.isotopeb('layout');
				}
			});
		});
	}
});

