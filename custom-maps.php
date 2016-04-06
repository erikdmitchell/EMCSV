<?php
/**
 * emcsv_get_csv_maps_table_rows function.
 *
 * @access public
 * @param bool $echo (default: true)
 * @return void
 */
function emcsv_get_csv_maps_table_rows($echo=true) {
	$html=null;
	$options=get_option('emcsv_map_templates', array());

	if (!$options || empty($options))
		return false;

	//$options=unserialize($options);

	foreach ($options as $id => $option) :
		$html.='<tr>';
			$html.='<td>'.$id.'</td>';
			$html.='<td>'.$option['template_name'].'</td>';
			$html.='<td>';
				$html.='<a href="'.home_url().esc_url( add_query_arg( array('action' => 'edit', 'id' => $id ) ) ).'">'.__('Edit', 'emcsv').'</a> | <a href="'.home_url().esc_url( add_query_arg( array('action' => 'delete', 'id' => $id ) ) ).'">'.__('Delete', 'emcsv').'</a>';
			$html.='</td>';
		$html.='</tr>';
	endforeach;

	if ($echo)
		echo $html;

	return $html;
}

/**
 * emcsv_get_custom_map_url function.
 *
 * @access public
 * @param string $action (default: '')
 * @return void
 */
function emcsv_get_custom_map_url($action='') {
	if ($action && $action!='')
		$action="&action=$action";

	return admin_url('/admin.php?page=emcsv-preset-maps'.$action);
}

/**
 * emcsv_get_map_template_values function.
 *
 * @access public
 * @param float $id (default: -1)
 * @return void
 */
function emcsv_get_map_template_values($id=-1) {
	if ($id<0 || $id=='')
		return false;

	$options=get_option('emcsv_map_templates', array());

	if (!isset($options[$id]))
		return false;

	return $options[$id];
}

/**
 * emcsv_get_map_id function.
 *
 * @access public
 * @param bool $id (default: false)
 * @return void
 */
function emcsv_get_map_id($_id=false) {
	if ($_id) :
		$id=$_id;
	elseif (isset($_GET['id'])) :
		$id=$_GET['id'];
	elseif (isset($_POST['id'])) :
		$id=$_POST['id'];
	else :
		$options=get_option('emcsv_map_templates', array());
		$id=count($options);
	endif;

	return $id;
}

/**
 * emcsv_map_template_check_value function.
 *
 * @access public
 * @param array $options (default: array())
 * @param string $name (default: '')
 * @return void
 */
function emcsv_map_template_check_value($options=array(),$name='') {
	if (empty($options))
		return '';

	// cycle through options and see if we have matches //
	foreach ($options as $option => $value) :
		if (is_array($value)) :
			foreach ($value as $a_option => $a_value) :
				if ($a_option==$name) :
					return $a_value;
					//break;
				endif;
			endforeach;
		else :
			if ($option==$name) :
				return $value;
				//break;
			endif;
		endif;
	endforeach;

	return '';
}

/**
 * emcsv_get_fields function.
 *
 * @access public
 * @return void
 */
function emcsv_get_fields($raw=false) {
	if ($raw) :
		$wp_fields=emcsv_get_wordpress_fields();
		$meta_fields=emcsv_get_meta_keys(true);
		$taxonomies=emcsv_get_all_taxonomies();

		$fields=$wp_fields+$meta_fields+$taxonomies;

		return $fields;
	endif;

	$fields=array(
		'post' => array(
			'name' =>	'WordPress Fields',
			'fields' => emcsv_get_wordpress_fields(),
		),
		'custom_fields' => array(
			'name' =>	'Custom Fields',
			'fields' => emcsv_get_meta_keys(true),
		),
		'taxonomies' => array(
			'name' =>	'Taxonomy Fields',
			'fields' => emcsv_get_all_taxonomies(),
		),
	);

	return $fields;
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

	$wp_fields=apply_filters('emcsv_get_wordpress_fields', $wp_fields);

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
  global $wpdb, $wp_meta_boxes;

  $where=array();
  $meta_box_fields=array();
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

	// this gets all custom fields attached to posts //
	$sql="
		SELECT DISTINCT(pm.meta_key)
		FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p
        ON p.ID = pm.post_id
        $where
        ORDER BY meta_key
	";
	$custom_fields=apply_filters('emcsv_get_meta_keys_custom_fields', $wpdb->get_col($sql), $sql);

	// get fields from registered metaboxes //
	if (!empty($wp_meta_boxes)) :
		foreach ($wp_meta_boxes as $meta_box) :
			$callback_array=emcsv_find_meta_box_callback_fields($meta_box);

			foreach ($callback_array as $field) :
				$meta_box_fields[]=$field;
			endforeach;
		endforeach;
	endif;

	$meta_box_fields=apply_filters('emcsv_get_meta_keys_meta_box_fields', $meta_box_fields, $wp_meta_boxes);

	// combine sql and registered fields //
	$custom_fields=array_merge($custom_fields, $meta_box_fields);

  return $custom_fields;
}

/**
 * emcsv_find_meta_box_callback_fields function.
 *
 * @access public
 * @param array $meta_box (default: array())
 * @return void
 */
function emcsv_find_meta_box_callback_fields($meta_box=array()) {
	foreach ($meta_box as $location => $boxes) :
		foreach ($boxes as $priority => $box) :
			foreach ($box as $id => $values) :
				if (isset($values['callback']))
					return $values['callback'];
			endforeach;
		endforeach;
	endforeach;
}

/**
 * emcsv_get_all_taxonomies function.
 *
 * @access public
 * @return void
 */
function emcsv_get_all_taxonomies() {
	global $wpdb;

	$taxonomies=get_taxonomies();

	return $taxonomies;
}

/**
 * emcsv_update_map_template function.
 *
 * @access public
 * @return void
 */
function emcsv_update_map_template() {
	if (!isset($_POST['emcsvupload']) || !wp_verify_nonce($_POST['emcsvupload'], 'emcsv_update_map_template'))
		return false;

	$options=get_option('emcsv_map_templates', array());
	$form_data=array();

	$form_data['template_name']=sanitize_text_field($_POST['template_name']); // store template name

	// get any filled in field values and store //
	foreach ($_POST['fields'] as $name => $value) :
		if ($value!='') :
			$form_data['fields'][$name]=sanitize_text_field($value);
		endif;
	endforeach;

	if (isset($_POST['id']) && is_numeric($_POST['id'])) :
		$options[$_POST['id']]=$form_data; // update array
	endif;

	update_option('emcsv_map_templates', $options); // update stored option

	echo '<div class="updated">Template #'.$_POST['id'].' updated.</div>';
}
add_action('admin_init','emcsv_update_map_template');

/**
 * emcsv_delete_map_template function.
 *
 * @access public
 * @return void
 */
function emcsv_delete_map_template() {
	if (!isset($_POST['emcsvupload']) || !wp_verify_nonce($_POST['emcsvupload'], 'emcsv_custom_map_delete'))
		return false;

	$options=get_option('emcsv_map_templates', array());

	unset($options[$_POST['id']]); // remove option from array

	update_option('emcsv_map_templates', $options); // update stored option

	wp_redirect(emcsv_get_custom_map_url());
	exit;
}
add_action('admin_init','emcsv_delete_map_template');
?>