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
</div>

<?php
global $emcsv_uploaded_csv_array;

// builds options array for js //
$options=array(
	'ids' => array_keys($emcsv_uploaded_csv_array),
	'extra_fields' => array(
		'attachment_id' => $_POST['attachment_id'],
		'has_header' => $_POST['has_header'],
		'post_type' => emcsv_upload_check_post_type($_POST['emcsv_post_type']),
		'post_status' => emcsv_upload_check_post_status($_POST['emcsv_post_status'],$_POST['attachment_id']),
		'fields_map' => emcsv_upload_clean_fields_map($_POST['emcsv_map']),
	),
);
?>

<script>
	// adds the loader to the uploads page //
	jQuery('#emcsv-wp-loader').EMWPLoader(<?php echo json_encode($options); ?>);
</script>