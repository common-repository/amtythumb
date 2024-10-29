<?php

//get First attached image
function amty_get_firstimage($post_id='', $size='thumbnail') {
	 $id = (int) $post_id;
	 $args = array(
	  'post_type' => 'attachment',
	  'post_mime_type' => 'image',
	  'numberposts' => 1,
	  'order' => 'ASC',
	  'orderby' => 'menu_order ID',
	  'post_status' => null,
	  'post_parent' => $id
	 );
	 $attachments = get_posts($args);
	 if ($attachments) {
	   $img = wp_get_attachment_image_src($attachments[0]->ID, $size);
	   return $img[0];
	 }else{
	   return '';
	 }
}

function amty_take_first_img_by_id($id) {
		$img='';
		$attach_img='';
		$uploaded_img = '';

		$temp = $wp_query;  // assign orginal query to temp variable for later use
		$wp_query = null;
		global $wpdb;
		$image_data = $wpdb->get_results("SELECT guid, post_content, post_mime_type, post_title FROM wp_posts WHERE id = $id");
		$wp_query = $temp;
		  
		$match_count = preg_match_all("/<img[^']*?src=\"([^']*?)\"[^']*?\/?>/", $image_data[0]->post_content, $match_array, PREG_PATTERN_ORDER);
		
		if($match_count == 0){
			$img = thumb($image_data[0]->post_content);
		}

		if( $img == '') $img = $match_array[1][0];//doubt
		
		if( $img != '')	return $img;
		
		$attach_img = amty_get_firstimage($output->guid);
		if( $attach_img != '')	return $attach_img;

		$first_image_data = array ($image_data[0]);
		foreach($first_image_data as $output) {
		if (substr($output->post_mime_type, 0, 5) == 'image'){
				$uploaded_img = $output->guid;
				break;
			}
		}

		if( $uploaded_img != '')	return $uploaded_img;
		return '';
}

function isImage($img){ 
    if(!getimagesize($img)){ 
        return FALSE; 
    }else{ 
        return TRUE; 
    } 
} 

?>