<?php
/**
 * Main results admin page
 *
 * @since 0.1.0
 */
?>
<div class="ulm-results edit">
	<?php $players=ulm_get_event_results($_GET['event_id']); ?>
	<?php $header=$players[0]; ?>

	<h2><?php _e('Edit Results','ulm'); ?></h2>
	<div id="ajax-response"></div>
	<div class="event-info">
		<div class="ulm-event-name"><?php echo get_the_title($_GET['event_id']); ?></div>
		<div class="ulm-event-actions"><span class="delete"><a href="" data-event-id="<?php echo $_GET['event_id']; ?>"><i class="fa fa-trash"></i></a></span></div>
	</div>
	<div class="event-results">
		<div class="header">
			<?php foreach ($header->results as $field) : ?>
				<div class="result-field <?php ulm_convert_to_class($field->name); ?>"><?php echo $field->name; ?></div>
			<?php endforeach; ?>
		</div>
		<div class="players">
			<form name="ulm_-edit-results-form" action="" method="post">
				<input type="hidden" name="action" value="edit_results">
				<?php wp_nonce_field('edit_form','ulm_results'); ?>
				<?php foreach ($players as $player) : ?>
					<div id="player-<?php echo $player->ID; ?>" class="player">
						<div class="result">
							<?php foreach ($player->results as $result) : ?>
								<div class="result-field <?php ulm_convert_to_class($result->name); ?>">
									<input type="text" name="edit_players[<?php echo $player->results_id; ?>][<?php echo $result->name; ?>]" value="<?php echo $result->value; ?>" />
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Results"></p>
			</form>
		</div>
	</div>
</div>