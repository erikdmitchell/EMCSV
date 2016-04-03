<?php
/**
 * Add results to db admin page
 *
 * @since 0.1.0
 */
?>
<div class="emcsvupload-upload">
	<h2><?php _e('Upload', 'emcsv'); ?></h2>

	<div id="emcsv-wp-loader">
		<div id="jq-loader-wrap">
			<div id="jq-loader-percent">0%</div>
			<div class="fill"></div>
		</div>
		<div class="jq-counter-details"><span class="current"></span> out of <span class="total"></span> processed.</div>
		<div class="jq-update-final"></div>
		<div id="jq-loader-data"></div>
		<button class="jq-loader-btn button button-primary"><?php _e('Import CSV','emcsv'); ?></button>
	</div>

	<div class="import-description">
		Click button --- this will happen.
		Then hide this?
	</div>
</div>

<?php
// builds options array for js //
$attachment_path=get_attached_file($_POST['attachment_id']);
$csv_headers=emcsv_get_csv_header($attachment_path);
$csv_array=emcsv_csv_to_array(array(
	'filename' => $attachment_path,
	'header' => $csv_headers,
));

$options=array(
	'ids' => array_keys($csv_array),
	'ajax_action' => 'emcsv_add_row',
	'extra_fields' => array(
		'attachment_id' => $_POST['attachment_id'],
		'attachment_path' => $attachment_path,
		'csv_headers' => $csv_headers,
		'has_header' => $_POST['has_header'],
		'post_type' => emcsv_upload_check_post_type($_POST['emcsv_post_type']),
		'post_status' => emcsv_upload_check_post_status($_POST['emcsv_post_status'],$_POST['attachment_id']),
		'fields_map' => emcsv_upload_clean_fields_map($_POST['emcsv_map']),
		'csv_array' => $csv_array,
	),
);
?>
<script>
	// adds the loader to the uploads page //
	jQuery('#emcsv-wp-loader').EMWPLoader(<?php echo json_encode($options); ?>);
</script>