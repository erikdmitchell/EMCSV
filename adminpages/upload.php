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




