<?php
/**
 * Results csv map admin page
 *
 * @since 0.1.0
 */
?>

<?php
$column_counter=0;
$has_header=0;

if (isset($_POST['has_header']))
	$has_header=1;
?>
<div class="ulm-map-fields">
	<h2><?php _e('Map Fields','ulm'); ?></h2>
	<form method="post" action="" id="map-fields-form">
		<input type="hidden" name="action" value="map_fields">
		<input type="hidden" name="attachment_id" value="<?php echo ulm_get_attachment_id($_POST['file']); ?>">
		<input type="hidden" name="has_header" value="<?php echo $has_header; ?>">
		<input type="hidden" name="event_id" value="<?php echo $_POST['event']; ?>">
		<input type="hidden" name="db_field_names" id="db_field_names" value="">
		<?php wp_nonce_field('map_fields','ulm_map_fields'); ?>

		<div class="csv-header-map-name">
			<label for="map_name"><?php _e('Name','ulm'); ?></label>
			<?php echo $this->get_csv_maps_dropdown(); ?>
			<a href="#" id="add-new-map-name"><?php _e('or Add New Name','ulm'); ?></a>
			<input type="text" name="map_name" id="map_name" class="regular-text active validate" value="" />
		</div>

		<table class="form-table">
			<tbody>
				<?php ulm_csv_map_fields($_POST['file'],$has_header); ?>
				<?php echo ulm_get_player_code_field($_POST['file'],$has_header); ?>
				<tr>
					<th scope="row">Points</th>
					<td>
						<label for="">Points Type</label>
						<?php echo get_ulm_points_dropdown(); ?>
						<br />
						<label for="">Points Field</label>
						FIELDS DD
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="button" name="button" id="add_to_db_btn" class="button button-primary" value="Add to DB">
		</p>
	</form>
</div>