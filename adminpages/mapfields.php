<?php
/**
 * CSV map admin page
 *
 * @since 0.1.0
 */
?>

<div class="emcsvupload-map-fields">
	<h2><?php _e('Map Fields','emcsvupload'); ?></h2>
	<form method="post" action="" id="map-fields-form">
		<input type="hidden" name="action" value="map_fields">
		<input type="hidden" name="attachment_id" value="<?php echo emcsv_get_attachment_id($_GET['file']); ?>">
		<input type="hidden" name="has_header" value="<?php echo $_GET['has_header']; ?>">
		<input type="hidden" name="db_field_names" id="db_field_names" value="">
		<?php wp_nonce_field('emcsv_map_fields','emcsvupload'); ?>

		<div class="csv-header-map-name map-row">
			<label for="map_name"><?php _e('Map Name', 'emcsvupload'); ?></label>
			<?php echo emcsv_get_csv_maps_dropdown(); ?>
			<a href="<?php echo emcsv_add_preset_map_url(); ?>">Add Preset Map</a>
		</div>

		<div class="emcsv-post-type map-row">
			<label for="emcsv_post_types"><?php _e('Select Post Type', 'emcsvupload'); ?></label>
			<?php echo emcsv_get_post_types_dropdown(); ?>
		</div>

		<div class="emcsv-post-status map-row">
			<label for="emcsv_post_status"><?php _e('Import with post status:', 'emcsvupload'); ?></label>
			<?php echo emcsv_get_post_status_dropdown(); ?>
		</div>

		<?php emcsv_map_fields($_GET['file'], $_GET['has_header']); ?>

		<p class="submit">
			<input type="button" name="reset" id="reset" class="button button-primary" value="Clear Mapping">
			<input type="button" name="button" id="add_to_db_btn" class="button button-primary" value="Import">
		</p>
	</form>
</div>