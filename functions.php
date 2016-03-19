<?php
function emcsv_file_input_field() {
	global $EMCSVUpload;

	echo $EMCSVUpload->upload_file_input();
}

function emcsv_map_csv_header_fields($return=false) {
	global $EMCSVUpload;

	if ($return)
		return $EMCSVUpload->map_csv_header_fields();

	echo $EMCSVUpload->map_csv_header_fields();
}

function emcsv_map_file_dropdown($args=array(),$return=false) {
	global $EMCSVUpload;

	if ($return)
		return $EMCSVUpload->map_file_dropdown($args);

	echo $EMCSVUpload->map_file_dropdown($args);
}

function emcsv_get_headers($file_url='',$delimiter=',') {
	global $EMCSVUpload;

	$attachment_id=$EMCSVUpload->get_attachment_id_from_url($file_url);
	$attachment_path=get_attached_file($attachment_id);

	$EMCSVUpload->get_csv_header($attachment_path);
}

function emcsv_get_attachment_id($file_url=false) {
	if (!$file_url)
		return false;

	global $EMCSVUpload;

	return $EMCSVUpload->get_attachment_id_from_url($file_url);
}

function emcsv_process_file($attachment_id=0,$map_fields=array(),$post_type='post',$has_header=0) {
	global $EMCSVUpload;

	$EMCSVUpload->process_csv_file($attachment_id,$map_fields,$post_type,$has_header);
}

function emcsv_map_fields($dropdown_args=array()) {
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
?>