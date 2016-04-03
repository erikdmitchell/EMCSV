<?php
/**
 * custom maps page
 *
 * @since 0.1.0
 */
?>

<div class="emcsvupload-custom-maps">
	<h2><?php _e('Custom Maps', 'emcsv'); ?></h2>

	<table class="widefat fixed">
		<thead>
			<tr>
				<th><?php _e('ID', 'emcsv'); ?></th>
				<th><?php _e('Map Name', 'emcsv'); ?></th>
				<th><?php _e('Action', 'emcsv'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php emcsv_get_csv_maps_table_rows(); ?>
		</tbody>
	</table>

	<p class="submit">
		<a href="<?php echo emcsv_get_custom_map_url('add'); ?>" class="button button-primary"><?php _e('Add New Map', 'emcsv'); ?></a>
	</p>
</div>