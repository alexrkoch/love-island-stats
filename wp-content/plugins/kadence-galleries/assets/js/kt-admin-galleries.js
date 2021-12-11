jQuery(document).ready(function ($) {
		var galleryID          = '#_kt_gal_photoshelter_id_id';
		var galleryName          = '#_kt_gal_photoshelter_id_name';
		var galleryNameShow          = '.selected_gallery_name';
		var galleryimage          = '#_kt_gal_photoshelter_id_url';
		var galleryimageurl          = '#sheltergalleryimage';
        var storeSendToEditor = '';
        var newSendToEditor   = '';

        jQuery(document).ready(function() {
            storeSendToEditor = window.send_to_editor;
			newSendToEditor   = function(html) {
                    $(galleryName).val(html.name);
                    $(galleryID).val(html.id);
                    $(galleryNameShow).html(html.name);
                    $(galleryimage).val(html.url);
                    $(galleryimageurl).attr('src', html.url);
                    $(galleryimageurl).css('display', 'block');
                    tb_remove();
                    window.send_to_editor = storeSendToEditor;
                };
        });

       	jQuery('#photoshelter-select').click(function() {
            window.send_to_editor = newSendToEditor;
            var iframe = jQuery(this).data('iframe');
            tb_show('', iframe);
            return false;
        });

});

(function($){
    "use strict";
    
    $.ktgallery = $.ktgallery || {};
    
    $(document).ready(function () {
        $.ktgallery();
    });

    $.ktgallery = function(){
        // When the user clicks on the Add/Edit gallery button, we need to display the gallery editing
        $('body').on({
            click: function(event){
                var current_gallery = $(this).closest('.kt_meta_image_gallery');

                if (event.currentTarget.id === 'kt-clear-gallery') {
                    //remove value from input 
                    
                    var rmVal = current_gallery.find('.gallery_values').val('');

                    //remove preview images
                    current_gallery.find(".kt_gallery_images").html("");

                    return;

                }

                // Make sure the media gallery API exists
                if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
                    return;
                }
                event.preventDefault();
                // Activate the media editor
                var $$ = $(this);

                var val = current_gallery.find('.gallery_values').val();
                wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
                    template: function(view){
                      return;
                    },
                });
                var final;
                if (!val) {
                    var options = {
					frame: 'post',
					       state: 'gallery',
					       multiple: true
					};

					var frame = wp.media.editor.open('gallery_values',options);
                } else {
                    final = '[gallery ids="' + val + '"]';
                    frame = wp.media.gallery.edit(final);
                }
				                
                // When the gallery-edit state is updated, copy the attachment ids across
                frame.state('gallery-edit').on( 'update', function( selection ) {
                	frame.detach();
                    //clear screenshot div so we can append new selected images
                    current_gallery.find(".kt_gallery_images").html("");
                    
                    var element, preview_html= "", preview_img, img_id;
                    var ids = selection.models.map(function(e){
                        element = e.toJSON();
                        preview_img = typeof element.sizes.thumbnail !== 'undefined'  ? element.sizes.thumbnail.url : element.url ;
                        img_id = element.id;
                        preview_html = '<a class="of-uploaded-image edit-meta" data-attachment-id="'+img_id+'" href="#"><img class="kt-gallery-image" src="'+preview_img+'" /></a>';
                        current_gallery.find(".kt_gallery_images").append(preview_html);
                        return e.id;
                    });
                    current_gallery.find('.gallery_values').val(ids.join(','));
                    current_gallery.find( '.gallery_values' );
    
                });


                return false;
            }
        }, '.kt-gal-gallery-attachments');
    };
})(jQuery);

(function($){
    "use strict";
    
    $.kt_attachment_gallery = $.kt_attachment_gallery || {};
    
    $(document).ready(function () {
        $.kt_attachment_gallery();
    });

    $.kt_attachment_gallery = function(){
        // When the user clicks on the Add/Edit gallery button, we need to display the gallery editing
        $('body').on({
            click: function(event){
                var current_gallery = $(this).closest('.kt_meta_image_gallery');
                var selected = $(this).data('attachment-id');

                // Make sure the media gallery API exists
                if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
                    return;
                }

                event.preventDefault();
                // Activate the media editor
                 wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
                    template: function(view){
                      return;
                    },
                });
                var $$ = $(this);
                var val = current_gallery.find('.gallery_values').val();
                var final = '[gallery ids="' + val + '"]';
                var frame = wp.media.gallery.edit(final);
                
                // When the gallery-edit state is updated, copy the attachment ids across
                frame.state('gallery-edit').on( 'update', function( selection ) {

                    //clear screenshot div so we can append new selected images
                    current_gallery.find(".kt_gallery_images").html("");
                    
                    var element, preview_html= "", preview_img, img_id;
                    var ids = selection.models.map(function(e){
                        element = e.toJSON();
                        preview_img = typeof element.sizes.thumbnail !== 'undefined'  ? element.sizes.thumbnail.url : element.url ;
                        img_id = element.id;
                        preview_html = '<a class="of-uploaded-image edit-meta" data-attachment-id="'+img_id+'" href="#"><img class="kt-gallery-image" src="'+preview_img+'" /></a>';
                        current_gallery.find(".kt_gallery_images").append(preview_html);
                        return e.id;
                    });
                    current_gallery.find('.gallery_values').val(ids.join(','));
                    current_gallery.find( '.gallery_values' );
    
                });


                return false;
            }
        }, '.edit-meta');
    };
})(jQuery);

