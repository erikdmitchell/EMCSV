<?php
/*
function emcsv_file_input_field() {
	global $EMCSVUpload;

	echo $EMCSVUpload->upload_file_input();
}
*/

/*
function emcsv_map_csv_header_fields($return=false) {
	global $EMCSVUpload;

	if ($return)
		return $EMCSVUpload->map_csv_header_fields();

	echo $EMCSVUpload->map_csv_header_fields();
}
*/

function emcsv_map_file_dropdown($args=array(),$return=false) {
	global $EMCSVUpload;

	if ($return)
		return $EMCSVUpload->map_file_dropdown($args);

	echo $EMCSVUpload->map_file_dropdown($args);
}

/*
function emcsv_process_file($attachment_id=0,$map_fields=array(),$post_type='post',$has_header=0) {
	global $EMCSVUpload;

	$EMCSVUpload->process_csv_file($attachment_id,$map_fields,$post_type,$has_header);
}
*/

// used
function emcsv_get_csv_maps_dropdown() {
	echo 'function to be built';
	// we need an option to store (and a page to manage) preset dropdowns
/*
		$html=null;
		$maps=$wpdb->get_results("SELECT * FROM $wpdb->ulm_map");

		$html.='<select name="map_id" id="map_name_dropdown">';
			$html.='<option value="0">'.__('-- Select One --','ulm').'</option>';
			foreach ($maps as $map) :
				$html.='<option value="'.$map->id.'">'.$map->name.'</option>';
			endforeach;
		$html.='</select>';

		return $html;
*/
}

/*
function emcsv_map_fields_output($dropdown_args=array()) {
	global $EMCSVUpload;

	$html=null;
	$headers=$EMCSVUpload->get_headers();

	$html.='<tr>';
		$html.='<th scope="row">';
			$html.='<label for="header_fields">'.__('Header Fields','EM').'</label>';
		$html.='</th>';
		$html.='<th scope="row">';
			$html.='<label for="map_fields">'.__('Map Fields','EM').'</label>';
		$html.='</th>';
	$html.='</tr>';

	foreach ($headers as $header) :
		$html.='<tr>';
			$html.='<td>'.$header.'</td>';
			$html.='<td>';
				$html.=emcsv_map_file_dropdown($dropdown_args,true);
			$html.='</td>';
		$html.='</tr>';
	endforeach;

	echo $html;
}
*/

/**
 * emcsv_map_fields function.
 *
 * @access public
 * @param bool $file (default: false)
 * @param bool $has_header (default: false)
 * @return void
 */
function emcsv_map_fields($file=false,$has_header=false) {
	echo emcsv_get_csv_map_fields($file,$has_header);
}

function emcsv_get_csv_map_fields($file=false,$has_header=false) {
	if (!$file)
		return false;

	$html=null;
	$column_counter=0;
	$attachment_id=emcsv_get_attachment_id($file);
	$attachment_path=get_attached_file($attachment_id);
	$csv_headers=emcsv_get_csv_header($attachment_path);
//print_r($csv_headers);
 	//$html.='<div class="csv-map">';
		$html.='<tr>';
			$html.='<th>';
				$html.=__('WP Fields','emcsvupload');

				emcsv_get_fields();

/*
wordpress fields
	post_title [Name: post_title]
custom fields (option to add and would match _cf)
	client [Name: client]
terms/taxonomies fields
	post_category [Name: post_category]


CLEAR NEXT
*/
			$html.='</th>';
			$html.='<th>';
				$html.=__('CSV Header','emcsvupload');
			$html.='</th>';
		$html.='</tr>';
		foreach ($csv_headers as $header) :
			$html.='<tr>';
				$html.='<th scope="row" id="col-'.$column_counter.'">';
					//$html.='<label for="db_fields[col-'.$column_counter.']">'.$header.'</label>';
				$html.='</th>';
				$html.='<td class="csv-header-fields">';
					$html.=emcsv_csv_headers_dropdown('emcsv_headers[]',$attachment_path, ',', false);
				$html.='</td>';
			$html.='</tr>';
			$column_counter++;
		endforeach;
	//$html.='</div>';

	return $html;
}

/**
 * emcsv_get_attachment_id function.
 *
 * @access public
 * @param string $file_url (default: '')
 * @return void
 */
function emcsv_get_attachment_id($file_url='') {
	global $wpdb;

	$attachment_id=0;
	$attachment=$wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $file_url ));

	if ($attachment)
		$attachment_id=$attachment;

	return $attachment_id;
}

/**
 * emcsv_csv_headers_dropdown function.
 *
 * @access public
 * @param string $name (default: 'emcsvname')
 * @param string $attachment_path (default: '')
 * @param string $delimiter (default: ')
 * @param mixed '
 * @param bool $echo (default: true)
 * @return void
 */
function emcsv_csv_headers_dropdown($name='emcsvname', $attachment_path='', $delimiter=',', $echo=true) {
	$html=null;
	$headers=emcsv_get_csv_header($attachment_path);

	$html.='<select name='.$name.' class="csv-headers-dropdown">';
		$html.='<option value="0">-- Select One --</option>';
		foreach ($headers as $header) :
			$html.='<option value="'.$header.'">'.$header.'</option>';
		endforeach;
	$html.='</select>';

	if ($echo)
		echo $html;

	return $html;
}

/**
 * emcsv_get_csv_header function.
 *
 * @access public
 * @param string $filename (default: '')
 * @param string $delimiter (default: ')
 * @param mixed '
 * @return void
 */
function emcsv_get_csv_header($filename='',$delimiter=',') {
	if (!file_exists($filename) || !is_readable($filename))
		return false;

	ini_set('auto_detect_line_endings',TRUE); // added by EM for issues with MAC

	$counter=1;
	$header=array();

	if (($handle = fopen($filename, 'r')) !== false) :

		while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) :
			$header=$row;

			if ($counter>=1)
				break;

			$counter++;
		endwhile;

		fclose($handle);
	endif;

	return $header;
}

function emcsv_get_fields() {
	$wp_fields=emcsv_get_wordpress_fields();
	$custom_fields=emcsv_get_meta_keys();
}

/**
 * emcsv_get_wordpress_fields function.
 *
 * @access public
 * @return void
 */
function emcsv_get_wordpress_fields() {
	$wp_fields=array(
		'post_title',
		'post_content',
		'post_excerpt',
		'post_date',
		'post_name',
		'post_author',
		'featured_image',
		'post_parent',
		'post_status',
		'post_format',
		'menu_order',
	);
	// apply_filters

	return $wp_fields;
}
function emcsv_get_custom_fields() {
	$default_fields=array(
		'post_title',
		'post_content',
		'post_excerpt',
		'post_date',
		'post_name',
		'post_author',
		'featured_image',
		'post_parent',
		'post_status',
		'post_format',
		'menu_order',
	);
	$wp_fields=wp_parse_args($fields,$default_fields);

	return $wp_fields;
}

function emcsv_get_meta_keys($type=false, $status=false) {
    global $wpdb;

    $where=array();

    if ($type)
    	$where[]="p.post_type = '{$type}'";

    if ($status)
    	$where[]="p.post_status = '{$status}'";

    if (empty($where)) :
    	$where='';
    else :
		$where=' WHERE '.implode(' AND ', $where);
    endif;
echo "SELECT pm.meta_key FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        $where";
    $results = $wpdb->get_col("
        SELECT pm.meta_key FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        $where
    ");

/*
_wp_
_menu_
*/
echo '<pre>';
print_r($results);
echo '</pre>';
    return $results;
}
?>