<?php
/*
Plugin Name: EMCSV
Plugin URI:
Description: Used for help with developing plugin classes and code.
Version:     0.1.0
Author:      Erik Mitchell
Author URI:  http://erikmitchell.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: emcsv
*/

// set/define some globals //
define('EMCSVUPLOAD_PATH', plugin_dir_path(__FILE__));

// require our files //
require_once(EMCSVUPLOAD_PATH.'class-csv-upload.php'); // the actual csv upload class
require_once(EMCSVUPLOAD_PATH.'functions.php'); // general functions for the plugin
require_once(EMCSVUPLOAD_PATH.'custom-maps.php'); // custom maps functionality
require_once(EMCSVUPLOAD_PATH.'lib/get-template-part.php'); // a get template part function


/**
 * emcsv_admin_menu function.
 *
 * @access public
 * @return void
 */
function emcsv_admin_menu() {
	add_menu_page('CSV Upload', 'CSV Upload', 'manage_options', 'emcsv', 'emcsv_admin_page');
	add_submenu_page('emcsv', 'Preset Maps', 'Preset Maps', 'manage_options', 'emcsv-preset-maps', 'emcsv_preset_maps');
}
add_action('admin_menu', 'emcsv_admin_menu');


/**
 * emcsv_admin_page function.
 *
 * @access public
 * @return void
 */
function emcsv_admin_page() {
	$slug=emcsv_get_template_slug();

	echo '<div class="wrap">';
		echo '<h1>CSV Uploader</h1>';

		emscvupload_get_template_part($slug);
	echo '</div>';
}

/**
 * emcsv_preset_maps function.
 *
 * @access public
 * @return void
 */
function emcsv_preset_maps() {
	echo '<div class="wrap">';
		echo '<h1>CSV Uploader</h1>';

		if (isset($_GET['action'])) :
			if ($_GET['action']=='add' || $_GET['action']=='edit') :
				emscvupload_get_template_part('custom','maps-edit');
			elseif ($_GET['action']=='delete') :
				emscvupload_get_template_part('custom','maps-delete');
			endif;
		else :
			emscvupload_get_template_part('custom','maps');
		endif;
	echo '</div>';
}

/**
 * emcsv_get_template_slug function.
 *
 * @access public
 * @return void
 */
function emcsv_get_template_slug() {
	$slug='main'; // default

	if (isset($_POST['action']) && $_POST['action']=='map_fields') : //wp_verify_nonce($_POST['emcsvupload'], 'emcsv_map_fields')) :
		return 'upload'; // upload after mapping
	elseif (isset($_GET['emcsvupload']) && wp_verify_nonce($_GET['emcsvupload'], 'emcsv_add_file')) :
		return 'mapfields'; // after we add our file
	endif;

	return $slug;
}
?>