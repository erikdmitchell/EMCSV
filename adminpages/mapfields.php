<?php
/**
 * CSV map admin page
 *
 * @since 0.1.0
 */
?>

<?php
$column_counter=0;
?>
<div class="emcsvupload-map-fields">
	<h2><?php _e('Map Fields','emcsvupload'); ?></h2>
	<form method="post" action="" id="map-fields-form">
		<input type="hidden" name="action" value="map_fields">
		<input type="hidden" name="attachment_id" value="<?php echo emcsv_get_attachment_id($_GET['file']); ?>">
		<input type="hidden" name="has_header" value="<?php echo $_GET['has_header']; ?>">
		<input type="hidden" name="db_field_names" id="db_field_names" value="">
		<?php wp_nonce_field('emcsv_map_fields','emcsvupload'); ?>

Select post type (dd)<br>
Import with post status(dd)<br>

		<div class="csv-header-map-name">
			<label for="map_name"><?php _e('Name', 'emcsvupload'); ?></label>
			<?php echo emcsv_get_csv_maps_dropdown(); ?>
			<a href="#" id="add-new-map-name"><?php _e('or Add New Name', 'emcsvupload'); ?></a>
			<input type="text" name="map_name" id="map_name" class="regular-text active validate" value="" />
		</div>

		<?php emcsv_map_fields($_GET['file'], $_GET['has_header']); ?>

		<p class="submit">
			<input type="button" name="reset" id="reset" class="button button-primary" value="Clear Mapping">
			<input type="button" name="button" id="add_to_db_btn" class="button button-primary" value="Import">
		</p>
	</form>
</div>