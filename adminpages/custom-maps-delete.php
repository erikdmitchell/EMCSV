<?php
/**
 * custom maps page
 *
 * @since 0.1.0
 */
?>

<div class="emcsv-custom-maps delete">
	<h2><?php _e('Delete Custom Map', 'emcsvupload'); ?></h2>

	<form name="emcsv_delete_map_template" method="post">
		<input type="hidden" name="action" value="delete">
		<input type="hidden" name="id" value=<?php echo $_GET['id']; ?>>
		<?php wp_nonce_field('emcsv_custom_map_delete', 'emcsvupload'); ?>

		<p>
			Delete template <?php echo $_GET['id']; ?>?
		</p>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Delete">
		</p>
	</form>

</div>