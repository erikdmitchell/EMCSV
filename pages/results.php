<?php
/**
 * Main results admin page
 *
 * @since 0.1.0
 */
?>
<div class="ulm-results">
	<h2><?php _e('Add Results','ulm'); ?></h2>
	<form name="ulm_add_results" action="" method="post">
		<input type="hidden" name="action" value="add_file_form">
		<?php wp_nonce_field('add_file_form','ulm_results'); ?>
		<input type="submit" name="submit" id="add_results_form" class="button button-secondary" value="<?php _e('Add Results','ulm'); ?>">
	</form>

	<h2><?php _e('Results in Database','ulm'); ?></h2>
	<?php ulm_events_list(array('has_results' => 1)); ?>
</div>