<?php
/**
 * custom maps page
 *
 * @since 0.1.0
 */
?>

<div class="emcsvupload-custom-maps">
	<h2><?php _e('Custom Maps', 'emcsvupload'); ?></h2>

	<table class="widefat fixed">
		<thead>
			<tr>
				<th>ID</th>
				<th>Map Name</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php emcsv_get_csv_maps_table_rows(); ?>
		</tbody>
	</table>

	<p class="submit">
		<a href="<?php echo emcsv_get_custom_map_url('add'); ?>" class="button button-primary">Add New Map</a>
	</p>
</div>