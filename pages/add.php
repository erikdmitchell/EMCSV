<?php
/**
 * Add results admin page
 *
 * @since 0.1.0
 */
?>
<div class="ulm-add-results">
	<h2><?php _e('Add Results','ulm'); ?></h2>
	<form method="post" action="" id="process-file-form">
		<input type="hidden" name="action" value="process_file">
		<?php wp_nonce_field('process_file','ulm_process_file'); ?>

		<table class="form-table ulm-results">
			<tbody>
				<tr class="event">
					<th scope="row"><label for="event"><?php _e('Event','ulm'); ?></label></th>
					<td>
						<?php echo $this->get_events_dropdown(); ?>
					</td>
				</tr>
				<tr class="upload-csv-file">
					<th scope="row"><label for="file"><?php _e('File','ulm'); ?></label></th>
					<td>
						<input type="text" name="file" id="file" class="regular-text file validate" value="" />
						<input type="button" name="add-file" id="add-file" class="button button-secondary" value="<?php _e('Add File','ulm'); ?>">
					</td>
				</tr>
				<tr class="has-header">
					<th scope="row"><?php _e('Header Row','ulm'); ?></th>
					<td>
						<label for="has_header">
							<input name="has_header" type="checkbox" id="has_header" value="1">
							<?php _e('First row of csv file is a header row.','ulm'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="button" name="process-file" id="process-file" class="button button-primary" value="<?php _e('Process File','ulm'); ?>">
		</p>
	</form>
</div>