jQuery(document).ready(function($) {

	// clear mapping button press //
	$('#emcsv_clear_mapping').click(function(e) {
		e.preventDefault();

		resetForm($('#csv-map-fields-form'));
	});

});

function resetForm($form) {
    $form.find('input:text, input:password, input:file, textarea').val('');
    $form.find('select').val(0);
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
}