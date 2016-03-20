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
		<?php $values=emcsv_get_map_template_values(); ?>

		<div class="emcsv-maps-nav">
			<a href="<?php echo wp_nonce_url(admin_url('tools.php?page=dummy'), 'emcsv-goto-preset-map', 'emcsv_preset_map'); ?>">View Map Templates</a>
		</div>

		<form name="emcsv_map_template" method="post" action="">
			<input type="hidden" name="action" value="updated">
			<input type="hidden" name="id" value="<?php emcsv_map_id(); ?>">
			<?php wp_nonce_field('emcsv_update_map_template', 'emcsvupload'); ?>

			<div class="template_name">
				<label for="template_name">Template Name</label><input type="text" name="template_name" id="template_name" class="regular-text" value="<?php echo emcsv_map_template_check_value($values, 'template_name'); ?>">
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
							<td><input type="text" name="fields[<?php echo $field; ?>]" class="regular-text" value="<?php echo emcsv_map_template_check_value($values,$field); ?>"></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
			</p>
		</form>
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