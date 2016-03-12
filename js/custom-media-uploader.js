/**
 * custom media uploader
 * @version: 1.0.0
 */
(function($) {

	var file_frame;

	$.fn.mediaUploader=function(event,options,callback) {
		var settings=$.extend({
			uploader_title : 'Media Uploader',
			uploader_button_text : 'Add Image'
			//type - text,image
		}, options);

	  event.preventDefault();

	  // If the media frame already exists, reopen it.
	  if ( file_frame ) {
	    file_frame.open();
	    return;
	  }

	  // Create the media frame.
	  //var selection = selection(); // (gallery)
	  file_frame = wp.media.frames.file_frame = wp.media({
		  // id (gallery) -- commented out
		  // frame: 'post' (gallery)
		  // state: 'gallery-edit' (gallery)
		  editing: true,
	    title: settings.uploader_title,
	    library: {
		    type: 'text'
		  },
	    button: {
	      text: settings.uploader_button_text,
	    },
	    multiple: false  // Set to true to allow multiple files to be selected \\ true (gallery)
	    // selection: selection (gallery)
	  });

	  // When an image is selected, run a callback.
	  file_frame.on( 'select', function() {
	    // We set multiple to false so only get one image from the uploader
	    attachment = file_frame.state().get('selection').first().toJSON();

	    // Do something with "return attachment" via callback

			callback(attachment);
	  });

	  // When an image/gallery is selected, run a callback. (gallery)
	  /*
	  file_frame.on( 'update', function() {
	    var controller=file_frame.states.get('gallery-edit');
	    var library=controller.get('library');
	    var ids=library.pluck('id');

			$('#bg-slider-images').val(ids);

			var data={
				'action':'mdw_theme_options_gallery_update',
				'ids':ids
			}

	    $.post(ajaxurl, data, function (response) {
				var images=$.parseJSON(response);
				$('#mdw-bg-images-gallery').html(images);
			});
	  });
	  */

	  // Gets initial gallery-edit images. Function modified from wp.media.gallery.edit in wp-includes/js/media-editor.js.source.html
	  /*
		function selection() {
	    var shortcode = wp.shortcode.next( 'gallery', wp.media.view.settings.mdwThemeOptions.shortcode );
	    var defaultPostId = wp.media.gallery.defaults.id;
	    var attachments;
	    var selection;

	    // Bail if we didn't match the shortcode or all of the content.
	    if ( ! shortcode )
	        return;

	    // Ignore the rest of the match object.
	    shortcode = shortcode.shortcode;

	    if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
	        shortcode.set( 'id', defaultPostId );

	    attachments = wp.media.gallery.attachments( shortcode );
	    selection = new wp.media.model.Selection( attachments.models, {
	        props:    attachments.props.toJSON(),
	        multiple: true
	    });

	    selection.gallery = attachments.gallery;

	    // Fetch the query's attachments, and then break ties from the
	    // query to allow for sorting.
	    selection.more().done( function() {
	        // Break ties with the query.
	        selection.props.set({ query: false });
	        selection.unmirror();
	        selection.props.unset('orderby');
	    });

	    return selection;
		} // function [selection]
		*/

	  // Finally, open the modal
	  file_frame.open();

	};

}(jQuery));