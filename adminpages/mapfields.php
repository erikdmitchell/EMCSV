<?php
/**
 * CSV map fields admin page
 *
 * @since 0.1.0
 */
?>

<div class="emcsvupload-map-fields">
	<h2><?php _e('Map Fields','emcsv'); ?></h2>
	<form method="post" action="" id="csv-map-fields-form">
		<input type="hidden" name="action" value="map_fields">
		<input type="hidden" name="attachment_id" value="<?php echo emcsv_get_attachment_id($_GET['file']); ?>">
		<input type="hidden" name="has_header" value="<?php echo $_GET['has_header']; ?>">
		<?php wp_nonce_field('emcsv_map_fields','emcsv'); ?>

		<div class="csv-header-map-name map-row">
			<label for="map_name"><?php _e('Map Name', 'emcsv'); ?></label>
			<?php echo emcsv_get_csv_maps_dropdown(); ?>
			<a href="<?php echo admin_url('admin.php?page=emcsv-preset-maps'); ?>">Add Preset Map</a>
		</div>

		<div class="emcsv-post-type map-row">
			<label for="emcsv_post_types"><?php _e('Select Post Type', 'emcsv'); ?></label>
			<?php echo emcsv_get_post_types_dropdown(); ?>
		</div>

		<div class="emcsv-post-status map-row">
			<label for="emcsv_post_status"><?php _e('Import with post status:', 'emcsv'); ?></label>
			<?php echo emcsv_get_post_status_dropdown(); ?>
		</div>

		<?php emcsv_map_fields($_GET['file'], $_GET['has_header']); ?>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Import', 'emcsv'); ?>">
			<input type="button" name="emcsv_clear_mapping" id="emcsv_clear_mapping" class="button button-secondary" value="<?php _e('Clear Mapping', 'emcsv'); ?>">
		</p>
	</form>
</div>