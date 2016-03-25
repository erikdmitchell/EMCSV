<?php
/**
 * EMCSVUpload class.
 */
class EMCSVUpload {




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