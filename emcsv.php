<?php
/*
Plugin Name: EMCSV
Plugin URI:
Description: A powerful yet simple CSV file uploader.
Version:     0.1.1
Author:      Erik Mitchell
Author URI:  http://erikmitchell.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: emcsv
*/

// set/define some globals //
define('EMCSV_PATH', plugin_dir_path(__FILE__));

// require our files //
require_once(EMCSV_PATH.'functions.php'); // general functions for the plugin
require_once(EMCSV_PATH.'custom-maps.php'); // custom maps functionality
require_once(EMCSV_PATH.'lib/get-template-part.php'); // a get template part function
require_once(EMCSV_PATH.'lib/updater/updater.php'); // our github updater class

$emcsv_active=true;
$emcsv_version='0.1.1';

/**
 * is_emcsv_active function.
 *
 * @access public
 * @return void
 */
function is_emcsv_active() {
	global $emcsv_active;

	$emcsv_active=true;

	return true;
}

/**
 * emsv_version function.
 *
 * @access public
 * @return void
 */
function emsv_version() {
	global $emcsv_version;

	echo $emcsv_version;
}

/**
 * emcsv_admin_menu function.
 *
 * @access public
 * @return void
 */
function emcsv_admin_menu() {
	add_menu_page(__('CSV Upload', 'emcsv'), __('CSV Upload', 'emcsv'), 'manage_options', 'emcsv', 'emcsv_admin_page', 'dashicons-upload');
	add_submenu_page('emcsv', __('Preset Maps', 'emcsv'), __('Preset Maps', 'emcsv'), 'manage_options', 'emcsv-preset-maps', 'emcsv_preset_maps');
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
		echo '<h1>'.__('CSV Uploader', 'emcsv').'</h1>';

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
	elseif (isset($_GET['emcsv']) && wp_verify_nonce($_GET['emcsv'], 'emcsv_add_file')) :
		return 'mapfields'; // after we add our file
	endif;

	return $slug;
}

/**
 * emcsv_admin_scripts_styles function.
 *
 * @access public
 * @param mixed $hook
 * @return void
 */
function emcsv_admin_scripts_styles($hook) {
// csv-upload_page_emcsv-preset-maps // preset maps
	if ($hook=='toplevel_page_emcsv') :
		wp_enqueue_script('csv-upload-script',plugins_url('js/csv-upload.js',__FILE__),array('jquery')); // main
		wp_enqueue_script('custom-media-uploader', plugins_url('lib/js/custom-media-uploader.js', __FILE__), array('jquery')); // main
		wp_enqueue_script('emcsv-mapfields-script', plugins_url('js/mapfields.js', __FILE__), array('jquery')); // mapfields
		wp_enqueue_script('em-wp-loader-script', plugins_url('js/em-wp-loader.js', __FILE__), array('jquery')); // upload

		wp_enqueue_style('em-wp-loader-style', plugins_url('css/em-wp-loader.css', __FILE__)); // upload

		wp_enqueue_media(); // main
	endif;

	wp_enqueue_style('emcsv-admin-style', plugins_url('css/admin.css', __FILE__)); // all
}
add_action('admin_enqueue_scripts','emcsv_admin_scripts_styles');

/**
 * emcsv_github_update_check function.
 *
 * ur github update settings
 *
 * @access public
 * @return void
 */
function emcsv_github_update_check() {
	if (!is_admin())
		return false; // note the use of is_admin() to double check that this is happening in the admin

	$username='erikdmitchell';
	$repo_name='emcsv';
	$folder_name='emcsv';
    $config = array(
        'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
        'proper_folder_name' => $folder_name, // this is the name of the folder your plugin lives in
        'api_url' => 'https://api.github.com/repos/'.$username.'/'.$repo_name, // the github API url of your github repo
        'raw_url' => 'https://raw.github.com/'.$username.'/'.$repo_name.'/master', // the github raw url of your github repo
        'github_url' => 'https://github.com/'.$username.'/'.$repo_name, // the github url of your github repo
        'zip_url' => 'https://github.com/'.$username.'/'.$repo_name.'/zipball/master', // the zip url of the github repo
        'sslverify' => true // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
        'requires' => '3.8', // which version of WordPress does your plugin require?
        'tested' => '4.4.2', // which version of WordPress is your plugin tested up to?
        'readme' => 'readme.txt', // which file to use as the readme for the version number
        //'access_token' => '', // Access private repositories by authorizing under Appearance > Github Updates when this example plugin is installed
    );
    new WP_GitHub_Updater($config);
}
add_action('admin_init', 'emcsv_github_update_check');
?>