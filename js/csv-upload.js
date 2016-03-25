jQuery(document).ready(function($) {

	// add file button click //
	$('#add-file').click(function(event) {
		var options={
			uploader_title : 'File Upload Box',
			uploader_button_text : 'Select'
		};

		$(this).mediaUploader(event,options,function(attachment) {
			$('#file').val(attachment.url);
		});
	});

	// adds the load to the uploads page //
	$('#emcsv-wp-loader').EMWPLoader();

});