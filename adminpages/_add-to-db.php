<?php
/**
 * Add results to db admin page
 *
 * @since 0.1.0
 */
?>

<div class="ulm-add-to-db">
	<h2><?php _e('Add to DB','ulm'); ?></h2>

	<div id="add-to-db-loader">
		<div class="results-insert-into-db">
			<div class="title"><?php _e('Inserting Results into Database','ulm'); ?></div>
			<div id="loader-notes"></div>
			<div id="loader-data"><?php _e('Processing','ulm'); ?> <span class="counter"></span> <?php _e('of'); ?> <span class="total"></span> <?php _e('rows','ulm'); ?></div>
			<div id="loader-results"></div>
		</div>
	</div>
</div>

<script>
	// add our loader to our add to db field //
	jQuery('#add-to-db-loader').emJQloader({
		loaderDiv: jQuery('#add-to-db-loader'),
		csvArr: <?php echo json_encode($this->csv_file); ?>,
		extra_data: {
			'map_id' : <?php echo $this->map_id; ?>,
			'event_id' : <?php echo $this->event_id; ?>,
			'fields_to_ignore' : <?php echo json_encode($this->fields_to_ignore); ?>,
			'code_field' : '<?php echo $this->code_field; ?>',
		}
	});
</script>