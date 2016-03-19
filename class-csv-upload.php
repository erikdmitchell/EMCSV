<?php
/**
 * EMCSVUpload class.
 */
class EMCSVUpload {

	public $version='0.1.0';
	public $csv_headers=array();

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
		add_action('wp_ajax_add_csv_row_to_db',array($this,'ajax_add_csv_row_to_db'));
	}

	/**
	 * admin_scripts_styles function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_scripts_styles() {
		wp_enqueue_script('csv-upload-script',plugins_url('js/csv-upload.js',__FILE__),array('jquery'),$this->version);
		wp_enqueue_script('custom-media-uploader', plugins_url('lib/js/custom-media-uploader.js', __FILE__), array('jquery'));

		wp_enqueue_media();
	}

	/**
	 * upload_file_input function.
	 *
	 * @access public
	 * @return void
	 */
	public function upload_file_input() {
		$html=null;

		$html.='<tr>';
			$html.='<th scope="row">';
				$html.='<label for="csv_file">'.__('CSV File','EM').'</label>';
			$html.='</th>';
			$html.='<td>';
				$html.='<input type="text" name="file" id="file" class="regular-text file validate" value="" />';
				$html.='<input type="button" name="add_csv_file" id="add_csv_file" class="button button-secondary" value="'.__('Add File','EM').'">';
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr>';
			$html.='<th scope="row">'.__('Header Row','EM').'</th>';
			$html.='<td>';
				$html.='<label for="has_header">';
					$html.='<input name="has_header" type="checkbox" id="has_header" value="1">';
					$html.=''.__('First row of csv file is a header row.','EM');
				$html.='</label>';
			$html.='</td>';
		$html.='</tr>';

		return $html;
	}

	/**
	 * map_csv_header_fields function.
	 *
	 * @access public
	 * @return void
	 */
	public function map_csv_header_fields() {
		$html=null;

		foreach ($this->csv_headers as $header) :
			$html.=$header.'<br />';
		endforeach;

		return $html;
	}

	/**
	 * get_headers function.
	 *
	 * @access public
	 * @return void
	 */
	public function get_headers() {
		return $this->csv_headers;
	}

	/**
	 * map_file_dropdown function.
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	public function map_file_dropdown($args=array()) {
		$html=null;
		$default_args=array(
			'fields' => array(
				'post_title' => 'Title',
				'post_content' => 'Content',
				'post_parent' => 'Parent',
				// order
			),
			'name' => 'map_fields',
			'id' => 'map_fields',
			'default_option' => '-- Select One --',
			'default_value' => 0,
		);
		$args=array_merge_recursive_distinct($default_args,$args);
		$args=apply_filters('emcsv_map_file_dropdown_args',$args);

		extract($args);

		$html.='<select name="'.$name.'[]" id="'.$id.'">';
			$html.='<option value="'.$default_value.'">'.__($default_option,'EM').'</option>';
			foreach ($fields as $value => $option) :
				$html.='<option value="'.$value.'">'.__($option,'EM').'</option>';
			endforeach;
		$html.'</select>';

		return $html;
	}

	/**
	 * get_csv_header function.
	 *
	 * @access public
	 * @param string $filename (default: '')
	 * @param string $delimiter (default: ')
	 * @param mixed '
	 * @return void
	 */
	public function get_csv_header($filename='',$delimiter=',') {
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

		$this->csv_headers=$header;
	}

	/**
	 * process_csv_file function.
	 *
	 * @access public
	 * @param int $attachment_id (default: 0)
	 * @param array $map_fields (default: array())
	 * @param string $post_type (default: 'post')
	 * @param int $has_header (default: 0)
	 * @return void
	 */
	public function process_csv_file($attachment_id=0,$map_fields=array(),$post_type='post',$has_header=0) {
		global $wpdb;

		if (empty($map_fields) || !$attachment_id)
			return false;

		$attachment_path=get_attached_file($attachment_id);

		$data=$this->csv_to_array(array(
			'filename' => $attachment_path,
			'header' => $map_fields,
			'skip_first_row' => $has_header,
		));

		echo $this->csv_ajax_loader_form($data,$post_type);
	}

	/**
	 * csv_ajax_loader_form function.
	 *
	 * @access protected
	 * @param array $data (default: array())
	 * @param string $post_type (default: 'post')
	 * @return void
	 */
	protected function csv_ajax_loader_form($data=array(),$post_type='post') {
		$html=null;

		$html.='<div class="csv-insert-into-db">';
			$html.='<div class="title">'.__('Inserting data into database','EM').'</div>';
			$html.='<div id="loader-notes"></div>';
			$html.='<div id="loader-data">'.__('Processing','EM').' <span class="counter">0</span> '.__('of').' <span class="total">0</span> '.__('rows','EM').'</div>';
			$html.='<div id="loader-results"></div>';
		$html.='</div>';

		$html.="
			<script>
				jQuery('#add-to-db-loader').emJQloader({
					loaderDiv: jQuery('#add-to-db-loader'),
					csvArr: ".json_encode($data).",
					ajax_action: 'add_csv_row_to_db',
					extra_data: {
						'post_type' : '$post_type'
					}
				});
			</script>
		";
		return $html;
	}

	/**
	 * ajax_add_csv_row_to_db function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_add_csv_row_to_db() {
		$return=array();
		$post=array();
		$row=$_POST['row'];
		$post_id=0;

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

		echo json_encode($return);

		wp_die();
	}

	/**
	 * get_attachment_id_from_url function.
	 *
	 * @access public
	 * @param string $url (default: '')
	 * @return void
	 */
	public function get_attachment_id_from_url($url='') {
		global $wpdb;

		$attachment=$wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url ));

		return $attachment[0];
	}

	/**
	 * csv_to_array function.
	 *
	 * Based on Jay Williams csv_to_array function. http://gist.github.com/385876
	 */
	public function csv_to_array($args=array()) {
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

		if (empty($header))
			return false;

		ini_set('auto_detect_line_endings',TRUE); // added for issues with MAC

		$data=array();
		$row_counter=1;

		if (($handle = fopen($filename, 'r')) !== false) :

			while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) :
				if ($skip_first_row && $row_counter==1) :
					// skip
				else :
					$data[]=array_combine($header,$row);
				endif;

				$row_counter++;
			endwhile;

			fclose($handle);
		endif;

		return $data;
	}

}

$EMCSVUpload=new EMCSVUpload();
?>