<?php
/**
 * Add csv admin page (main)
 *
 * @since 0.1.0
 */
?>
<div class="emcsvupload">
	<h2><?php _e('Add File','emcsvupload'); ?></h2>
	<form method="post" action="" id="add-file-form">
		<input type="hidden" name="action" value="add_file">
		<?php wp_nonce_field('emcsv_add_file','emcsvupload'); ?>

		<table class="form-table add-file">
			<tbody>
				<tr class="upload-csv-file">
					<th scope="row"><label for="file"><?php _e('File','emcsvupload'); ?></label></th>
					<td>
						<input type="text" name="file" id="file" class="regular-text file validate" value="" />
						<input type="button" name="add-file" id="add-file" class="button button-secondary" value="<?php _e('Add File','emcsvupload'); ?>">
					</td>
				</tr>
				<tr class="has-header">
					<th scope="row"><?php _e('Header Row','emcsvupload'); ?></th>
					<td>
						<label for="has_header">
							<input name="has_header" type="checkbox" id="has_header" value="1">
							<?php _e('First row of csv file is a header row.','emcsvupload'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="button" name="add-file" id="add-file" class="button button-primary" value="<?php _e('Process File','emcsvupload'); ?>">
		</p>
	</form>
</div>