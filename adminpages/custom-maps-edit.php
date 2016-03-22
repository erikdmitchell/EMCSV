<?php
/**
 * custom maps page
 *
 * @since 0.1.0
 */
?>

<div class="emcsv-custom-maps update">
	<h2><?php _e('Update Custom Maps', 'emcsvupload'); ?></h2>

	<?php $values=emcsv_get_map_template_values(emcsv_get_map_id()); ?>

	<form name="emcsv_map_template" method="post" action="">
		<input type="hidden" name="action" value="updated">
		<input type="hidden" name="id" value="<?php echo emcsv_get_map_id(); ?>">
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

</div>