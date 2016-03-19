<?php
// set/define some globals //
define('EMCSVUPLOAD_PATH', plugin_dir_path(__FILE__));

// require our files //
require_once(EMCSVUPLOAD_PATH.'class-csv-upload.php');
require_once(EMCSVUPLOAD_PATH.'functions.php');

/*
hook our admin pages up
the idea is to have a function/shortcode we can drop in that will basically run itself
it would have our admin pages and then it could run within the admin section on its own
*/
?>