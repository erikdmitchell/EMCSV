<?php
/**
 * Main admin page
 *
 * @since 0.1.0
 */
?>
<div class="emcsvupload add-file">
	<h2><?php _e('Add File', 'emcsv'); ?></h2>
	<form name="emcsv_add_file" action="" method="post">
		<input type="hidden" name="action" value="add_file_form">
		<?php wp_nonce_field('emcsv_add_file', 'emcsvupload'); ?>
		<input type="submit" name="submit" id="add_results_form" class="button button-secondary" value="<?php _e('Add File', 'emcsvupload'); ?>">
	</form>
</div>