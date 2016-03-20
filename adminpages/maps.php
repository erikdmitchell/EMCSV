<?php
/**
 * custom maps page
 *
 * @since 0.1.0
 */
?>

<div class="emcsvupload-custom-maps">
	<h2><?php _e('Custom Maps', 'emcsvupload'); ?></h2>

	<?php if (isset($_GET['action']) && $_GET['action']=='delete') : ?>
		delete
	<?php elseif (isset($_GET['action'])) : ?>
		<div class="template_name">
			<label for="template_name">Template Name</label><input type="text" name="template_name" id="template_name" class="regular-text" value="">
		</div>

		<table class="widefat fixed">
			<thead>
				<tr>
					<th>Fields</th>
					<th>CSV Header</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach (emcsv_get_fields(true) as $field) : ?>
					<tr>
						<td><?php echo $field; ?></td>
						<td>???????</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<p class="submit">
			<a href="<?php emcsv_custom_map_url('update'); ?>" class="button button-primary">Save Changes</a>
		</p>
	<?php else : ?>
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
			<a href="<?php emcsv_custom_map_url('add'); ?>" class="button button-primary">Add New Map</a>
		</p>
	<?php endif; ?>

</div>