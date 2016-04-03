<?php
/**
 * emcsv_get_csv_maps_dropdown function.
 *
 * @access public
 * @param bool $echo (default: false)
 * @return void
 */
function emcsv_get_csv_maps_dropdown($echo=false) {
	$html=null;
	$option=get_option('emcsv_map_templates', array());

	if (!$option || empty($option)) :
		$html.='No preset maps.';
	else :
		$html.='<select name="preset_map" id="emcsv_preset_map">';
			$html.='<option value="-1">-- '.__('Select One', 'emcsv').' --</option>';
			foreach ($option as $key => $arr) :
				$html.='<option value="'.$key.'">'.$arr['template_name'].'</option>';
			endforeach;
		$html.='</select>';
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

			foreach ($wp_fields as $type => $fields_arr) :
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
						$html.='<td class="csv-header" data-field="'.$field.'">';
							$html.=emcsv_csv_headers_dropdown('emcsv_map['.$type.']['.$field.']',$attachment_path, ',', false);
							$html.='<span class="preset-map-field"></span>';
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
 * emcsv_get_custom_fields function.
 *
 * @access public
 * @return void
 */
/*
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
*/

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
	if ($post_type=='' || !$post_type)
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
	// if no post status //
	if ($post_status=='' || !$post_status) :
		// there's no attachment, so we bail //
		if ($attachment_id==0) :
			return 'publish';
		else :
			$post_status=false; // reset
			$attachment_path=get_attached_file($attachment_id);
			$csv_headers=emcsv_get_csv_header($attachment_path);

			// check our headers to make sure post_status exists //
			foreach ($csv_headers as $header) :
				if ($header=='post_status') :
					$post_status='csv';
				endif;
			endforeach;

			// there's nothing in the csv file, so we change it here //
			if (!$post_status) :
				return 'publish';
			else :
				return $post_status;
			endif;
		endif;
	endif;
echo "<p>$post_status</p>";
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
	foreach ($map as $key => $arr) :
		$map[$key]=array_filter($arr);
	endforeach;

	return $map;
}

function ajax_emcsv_add_csv_row_to_db() {
	// check we have vaild id and array value exists //
	if ($_POST['id']<0 || !isset($_POST['extra_fields']['csv_array'][$_POST['id']]))
		return false;
print_r($_POST);
	$post_data=array();
	$post_custom_fields=array();
	$post_taxonomies=array();
	//$return=array();
	$post_id=0;
	$row=$_POST['extra_fields']['csv_array'][$_POST['id']]; // get our row
	$fields_map=$_POST['extra_fields']['fields_map'];
	$post_type=$_POST['extra_fields']['post_type'];
	$post_status=$_POST['extra_fields']['post_status'];
	$clean_row=emcsv_map_arrays($row, $fields_map);

	// if csv, get the post status in csv, if not found, use publish
	if ($post_status=='csv') :
		if (isset($row['post_status']) && $row['post_status']!='') :
			$post_status=$row['post_status'];
		else :
			$post_status='publish';
		endif;
	endif;

	// if not post type set, do post //
	if (!$post_type || $post_type=='')
		$post_type='post';

	// our clean row contians a cleaned up array with three types: post, custom_fields, taxonomies //
	// we must do our post stuff first //
	if (isset($clean_row['post'])) :
		$post_data=$clean_row['post'];
	else :
		return false; // NO POST STUFF NO GO
	endif;

	// set custom fields //
	if (isset($clean_row['custom_fields']))
		$post_custom_fields=$clean_row['custom_fields'];

	// set taxonomies //
	if (isset($clean_row['taxonomies']))
		$post_taxonomies=$clean_row['taxonomies'];

	// add post //
	$post_data=emcsv_clean_post_arr($post_data, $post_type); // clean and sanitize data
	$post_data['post_type']=$post_type;
	$post_data['post_status']=$post_status;
print_r($post_data);
	//$post_id=wp_insert_post($post_data); // insert post

	// check our post id, if not id or we havean error, we bail //
	if (!$post_id) :
		$return=array(
			'error' => __('Failed to add row to database.', 'emcsv'),
			'success' => false
		);
	elseif (is_wp_error($post_id)) :
		$return=array(
			'error' => $post_id->get_error_message(),
			'success' => false
		);
	else :
		$return=array(
			'error' => false,
			'success' => __('Post (row) added to database. (ID: '.$post_id.')', 'emcsv')
		);
	endif;

	do_action('emcsv_after_insert_post', $post_id, $post_data, $clean_row);

	echo json_encode($return);

	wp_die();
}
add_action('wp_ajax_emcsv_add_row', 'ajax_emcsv_add_csv_row_to_db');

/**
 * csv_to_array function.
 *
 * Based on Jay Williams csv_to_array function. http://gist.github.com/385876
 */
function emcsv_csv_to_array($args=array()) {
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

	return $data;
}

/**
 * emcsv_map_arrays function.
 *
 * @access public
 * @param array $arr (default: array())
 * @param array $map (default: array())
 * @return void
 */
function emcsv_map_arrays($arr=array(), $map=array()) {
	if (empty($arr) || empty($map))
		return $arr;

	$mapped_arr=array();

	// finad matches and replace the key with the db field name //
	foreach ($map as $type => $type_arr) :
		foreach ($type_arr as $key => $value) :
			if (isset($arr[$value])) :
				$mapped_arr[$type][$key]=$arr[$value];
			endif;
		endforeach;
	endforeach;

	return $mapped_arr;
}

/**
 * emcsv_clean_post_arr function.
 *
 * @access public
 * @param array $arr (default: array())
 * @return void
 */
function emcsv_clean_post_arr($arr=array(), $post_type='post') {
	if (empty($arr))
		return $arr;

	foreach ($arr as $key => $value) :
		// clean our title //
		if ($key=='post_title') :
			$arr[$key]=wp_strip_all_tags($value);
		else :
			$arr[$key]=sanitize_text_field($value); // sanitize everything else
		endif;
	endforeach;

	// check for and set our post parent, if it is numberic we assume it's the id and bail //
	if (isset($arr['post_parent']) && $arr['post_parent']!='') :
		if (!is_numeric($arr['post_parent']))

		$parent=get_page_by_title($arr['post_parent'],'OBJECT', $post_type);

		// if we have it, set the id, else set null //
		if ($parent) :
			$arr['post_parent']=$parent->ID;
		else :
			$arr['post_parent']=null;
		endif;
	endif;

	return $arr;
}

/**
 * ajax_emcsv_preset_map_change function.
 *
 * @access public
 * @return void
 */
function ajax_emcsv_preset_map_change() {
	if ($_POST['id']=='' || $_POST['id']<0)
		return false;

	$fields=array();
	$option=get_option('emcsv_map_templates', array());

	foreach ($option as $key => $arr) :
		if ($_POST['id']==$key) :
			$fields=$arr['fields'];
		endif;
	endforeach;

	if (empty($fields))
		return false;

	echo json_encode($fields);

	wp_die();
}
add_action('wp_ajax_emcsv_preset_map_change', 'ajax_emcsv_preset_map_change');
?>