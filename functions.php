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

/*
function emcsv_map_file_dropdown($args=array(),$return=false) {
	global $EMCSVUpload;

	if ($return)
		return $EMCSVUpload->map_file_dropdown($args);

	echo $EMCSVUpload->map_file_dropdown($args);
}
*/

/*
function emcsv_process_file($attachment_id=0,$map_fields=array(),$post_type='post',$has_header=0) {
	global $EMCSVUpload;

	$EMCSVUpload->process_csv_file($attachment_id,$map_fields,$post_type,$has_header);
}
*/

/**
 * emcsv_get_csv_maps_dropdown function.
 *
 * @access public
 * @param bool $echo (default: false)
 * @return void
 */
function emcsv_get_csv_maps_dropdown($echo=false) {
	$html=null;
	$option=get_option('emcsv_csv_maps', array());

	if (!$option || empty($option)) :
		$html.='No preset maps.';
	else :

	endif;

	if ($echo)
		echo $html;

	return $html;
}

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

/**
 * emcsv_get_csv_map_fields function.
 *
 * @access public
 * @param bool $file (default: false)
 * @param bool $has_header (default: false)
 * @return void
 */
function emcsv_get_csv_map_fields($file=false,$has_header=false) {
	if (!$file)
		return false;

	$html=null;
	$column_counter=0;
	$attachment_id=emcsv_get_attachment_id($file);
	$attachment_path=get_attached_file($attachment_id);
	$csv_headers=emcsv_get_csv_header($attachment_path);
	$wp_fields=emcsv_get_fields();

	$html.='<table class="form-table emcsv-map-form">';
		$html.='<tbody>';

			foreach ($wp_fields as $fields_arr) :
				$html.='<tr>';
					$html.='<th>';
						$html.=__($fields_arr['name'], 'emcsvupload');
					$html.='</th>';
					$html.='<th>';
						$html.=__('CSV Header','emcsvupload');
					$html.='</th>';
				$html.='</tr>';

				foreach ($fields_arr['fields'] as $field) :
					$html.='<tr class="fields-row">';
						$html.='<td class="field">';
							$html.=$field;
						$html.='</td>';
						$html.='<td class="cesv-header">';
							$html.=emcsv_csv_headers_dropdown('emcsv_map['.$field.']',$attachment_path, ',', false);
						$html.='</td>';
					$html.='</tr>';
				endforeach;

			endforeach;

		$html.='</tbody>';
	$html.='</table>';

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

/**
 * emcsv_get_custom_fields function.
 *
 * @access public
 * @return void
 */
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

/**
 * emcsv_get_meta_keys function.
 *
 * @access public
 * @param bool $show_hidden (default: false)
 * @param bool $wp_defaults (default: false)
 * @param bool $type (default: false)
 * @param bool $status (default: false)
 * @return void
 */
function emcsv_get_meta_keys($show_hidden=false, $wp_defaults=false, $type=false, $status=false) {
    global $wpdb;

    $where=array();
    $wp_default_prefixes=array(
		'_wp_',
		'_menu_',
		'_edit_',
		'_thumbnail_',
		'_oembed_',
	);

    // check if we display "hidden" custom fields (starts with '_') //
    if (!$show_hidden)
		$where[]="left(pm.meta_key,1) != '_'";

	// if we hide wp defaults, then we remove a series of basic meta fields that wp uses by default //
	if (!$wp_defaults) :
		foreach ($wp_default_prefixes as $prefix) :
			$where[]="left(pm.meta_key,".strlen($prefix).") != '{$prefix}'";
		endforeach;
	endif;

	// show metas from a specific post type //
    if ($type)
    	$where[]="p.post_type = '{$type}'";

	// show mets from a specific post sttus //
    if ($status)
    	$where[]="p.post_status = '{$status}'";

    if (empty($where)) :
    	$where='';
    else :
		$where=' WHERE '.implode(' AND ', $where);
    endif;

	$sql="
		SELECT DISTINCT(pm.meta_key)
		FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p
        ON p.ID = pm.post_id
        $where
	";

    $results = $wpdb->get_col($sql);

    return $results;
}

/**
 * emcsv_get_all_taxonomies function.
 *
 * @access public
 * @return void
 */
function emcsv_get_all_taxonomies() {
	global $wpdb;

	$taxonomies=$wpdb->get_col("SELECT DISTINCT(taxonomy) FROM {$wpdb->term_taxonomy}");

	return $taxonomies;
}

/**
 * emcsv_get_post_types_dropdown function.
 *
 * @access public
 * @param array $args (default: array())
 * @param string $operator (default: 'and')
 * @param bool $echo (default: false)
 * @return void
 */
function emcsv_get_post_types_dropdown($args=array(), $operator='and', $echo=false) {
	$html=null;
	$post_types=get_post_types($args, 'names', $operator);

	$html.='<select name="emcsv_post_type" id="emcsv_post_type">';
		$html.='<option value="0">-- Select One --</option>';
		foreach ($post_types as $value => $name) :
			$html.='<option value="'.$value.'">'.$name.'</option>';
		endforeach;
	$html.='</select>';

	if ($echo)
		echo $html;

	return $html;
}

/**
 * emcsv_get_post_status_dropdown function.
 *
 * @access public
 * @param bool $echo (default: false)
 * @return void
 */
function emcsv_get_post_status_dropdown($echo=false) {
	$html=null;
	$post_statuses=array(
		'0' => 'Status in CSV',
		'publish' => 'Publish',
		'draft' => 'Draft',
		'pending' => 'Pending',
		'private' => 'Private',
	);

	$html.='<select name="emcsv_post_status" id="emcsv_post_status">';
		foreach ($post_statuses as $value => $name) :
			$html.='<option value="'.$value.'">'.$name.'</option>';
		endforeach;
	$html.='</select>';

	if ($echo)
		echo $html;

	return $html;
}

/**
 * emcsv_upload_check_post_type function.
 *
 * @access public
 * @param int $post_type (default: 0)
 * @return void
 */
function emcsv_upload_check_post_type($post_type=0) {
	if ($post_type=='' || $post_type==0)
		$post_type='post';

	return $post_type;
}

/**
 * emcsv_upload_check_post_status function.
 *
 * @access public
 * @param string $post_status (default: 'publish')
 * @param int $attachment_id (default: 0)
 * @return void
 */
function emcsv_upload_check_post_status($post_status='publish', $attachment_id=0) {
	if ($post_status=='' || $post_status==0) :
		if ($attachment_id==0) :
			return 'publish';
		else :
			$attachment_path=get_attached_file($attachment_id);
			$csv_headers=emcsv_get_csv_header($attachment_path);

			// check our headers to make sure post_status exists //
			foreach ($csv_headers as $header) :
				if ($header=='post_status') :
					return 'csv';
				endif;
			endforeach;
		endif;
	endif;

	return $post_status;
}

/**
 * emcsv_upload_clean_fields_map function.
 *
 * @access public
 * @param array $map (default: array())
 * @return void
 */
function emcsv_upload_clean_fields_map($map=array()) {
	if (empty($map))
		return array();

	// removes all elements with value of 0 //
	return array_filter($map);
}

function emcsv_ajax_add_csv_row_to_db() {
	$return=array();
	$post=array();
	$row=$_POST['row'];
	$post_id=0;

/*
	// trim whitespace from row //
	foreach ($row as $key => $value) :
		$row[$key]=trim($value);
	endforeach;

	// santaize title for security //
	if (isset($row['post_title']))
		$post['post_title']=wp_strip_all_tags($row['post_title']);

	// set post type via our passed variable if not included in $post //
	if (isset($row['post_type'])) :
		$post['post_type']=$row['post_type'];
	elseif ($_POST['post_type']) :
		$post['post_type']=$_POST['post_type'];
	else :
		$post['post_type']='post';
	endif;

	// set post status tu published if not set //
	if (!isset($post['post_status']))
		$post['post_status']='publish';

	// check for and set our post parent //
	if (isset($row['post_parent']) && $row['post_parent']!='') :
		$parent=get_page_by_title($row['post_parent'],'OBJECT',$post['post_type']);

		// if we have it, set the id, else set null //
		if ($parent) :
			$post['post_parent']=$parent->ID;
		else :
			$post['post_parent']=null;
		endif;
	endif;

	// insert post //
	$post_id=wp_insert_post($post,true);

	do_action('emcsv_row_to_db_after_insert_post',$post_id,$post,$row);

	// process our return //
	if (!$post_id) :
		$return[]='<div class="error">'.__('Failed to add row to database.','EM').'</div>';
	elseif (is_wp_error($post_id)) :
		$return[]=$post_id->get_error_message();
	else :
		$return[]='<div class="updated">'.__('Row added to database. (ID: '.$post_id.')','EM').'</div>';
	endif;
*/

	echo json_encode($return);

	wp_die();
}
add_action('em_wp_loader_run', 'emcsv_ajax_add_csv_row_to_db');

/**
 * csv_to_array function.
 *
 * Based on Jay Williams csv_to_array function. http://gist.github.com/385876
 */
function emcsv_csv_to_array($args=array()) {
	global $emcsv_uploaded_csv_array;

	$default_args=array(
		'filename' => '',
		'header' => array(),
		'delimiter' => ',',
		'skip_first_row' => true,
	);
	$args=array_merge($default_args,$args);

	extract($args);

	if (!file_exists($filename) || !is_readable($filename))
		return false;

	// what if we have a csv file without a header? //
	if (empty($header))
		return false;

	ini_set('auto_detect_line_endings',TRUE); // added for issues with MAC

	$data=array();
	$row_counter=1;

	if (($handle = fopen($filename, 'r')) !== false) :

		while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) :
			if ($skip_first_row && $row_counter==1) :
				//continue; // skip
			else :
				$data[]=array_combine($header,$row);
			endif;

			$row_counter++;
		endwhile;

		fclose($handle);
	endif;

	$emcsv_uploaded_csv_array=$data; // populate global

	return;
}
?>