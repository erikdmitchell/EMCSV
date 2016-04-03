jQuery(document).ready(function($) {

	// clear mapping button press //
	$('#emcsv_clear_mapping').click(function(e) {
		e.preventDefault();

		resetForm($('#csv-map-fields-form'));
	});

	// preset map dropdown //
	$('#emcsv_preset_map').change(function() {
		var data={
			'action' : 'emcsv_preset_map_change',
			'id' : $(this).val()
		};

		// get fields via ajax //
		$.post(ajaxurl, data, function (response) {
			var data=$.parseJSON(response);

			// cycle through our fields //
			$.each(data, function (field, csvField) {
				var $csvHeader=$('table.emcsv-map-form').find("[data-field='" + field + "']");
				var $dropdown=$csvHeader.find('.csv-headers-dropdown');
				var $presetSpan=$csvHeader.find('.preset-map-field');

				$dropdown.val(csvField);

				$presetSpan.text(csvField);
			});
		});
	});

});

function resetForm($form) {
    $form.find('input:text, input:password, input:file, textarea').val('');
    $form.find('select').val(0);
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
}