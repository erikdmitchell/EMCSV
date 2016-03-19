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
	echo '<div class="wrap">';
		echo '<h1>CSV Uploader</h1>';
		emscvupload_get_template_part('main');
	echo '</div>';
}
?>