<?php
// set/define some globals //
define('EMCSVUPLOAD_PATH', plugin_dir_path(__FILE__));

// require our files //
require_once(EMCSVUPLOAD_PATH.'class-csv-upload.php');
require_once(EMCSVUPLOAD_PATH.'functions.php');
require_once(EMCSVUPLOAD_PATH.'lib/get-template-part.php');

/*
hook our admin pages up
the idea is to have a function/shortcode we can drop in that will basically run itself
it would have our admin pages and then it could run within the admin section on its own

-------
X TWEAK get-template-part.php
emscvupload_get_template_part();
include admin pages
test running of admin pages as is
rework pages like ultimate csv (see dropbox for visiuals)
*/

function emcsvupload() {
	$slug=emcsv_get_template_slug();

	echo '<div class="wrap">';
		echo '<h1>CSV Uploader</h1>';

		emscvupload_get_template_part($slug);
	echo '</div>';
}

function emcsv_get_template_slug() {
	$slug='main'; // default

	if (isset($_GET['emcsvupload']) && wp_verify_nonce($_GET['emcsvupload'], 'emcsv_add_file')) :
		$slug='mapfields'; // after we add our file
	elseif (isset($_GET['emcsv_preset_map']) && wp_verify_nonce($_GET['emcsv_preset_map'], 'emcsv-goto-preset-map')) :
		$slug='maps'; // our add/edit custom maps screen
	endif;


	return $slug;
}

/**
 * emcsv_referer_page function.
 *
 * @access public
 * @param bool $echo (default: true)
 * @return void
 */
function emcsv_referer_page($echo=true) {
	$page='';

	if (isset($_GET['page']))
		$page=$_GET['page'];

	if ($echo)
		echo $page;

	return $page;
}
?>