<?php
/**
 * Add results to db admin page
 *
 * @since 0.1.0
 */
?>


attachment_id
has_header
emcsv_post_type
emcsv_post_status (0 - use csv)
emcsv_map (0 no go)

basically we need to pass the number of rows in the csv file, then our function graps that row, maps and stores the data

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
$options=array(
	'attachment_id' => $_POST['attachment_id'],
	'has_header' => $_POST['has_header'],
	'post_type' => $_POST['emcsv_post_type'],
	'post_status' => '',
	'fields_map' => 'cleant post[emcsv_map]',
	'csv_rows' => 0,
);
?>

<script>
	// adds the load to the uploads page //
	$('#emcsv-wp-loader').EMWPLoader(<?php echo $options; ?>);
</script>