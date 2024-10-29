<?php

include ("videothumb.php");
include ("supportingFunction.php");
include ("cacheFunction.php");

/*
It fetch first image from the post
save it to cache. only if post_id is given.
and return resized image url or <img>
*/
function amty_lead_img($w='',$h='',$constrain='',$img='',$percent='',$zc='',$post_id = '',$img_url_only = 'y',$default_img = '') {
	
	$pid=-1;
	//$img_uri='';
	$imgExt = '';
	if($img == ''){
		if($post_id == ''){
			global $id;
			$pid=$id;
		}
		else{
			$pid=$post_id;
		}
		//put valid or default image into cache
		amty_putIntoImageCache($pid,0,$default_img);
		$img = get_post_meta($pid,'amtyThumb',true);
		$imgExt = get_post_meta($pid,'amtyThumbExt',true);
	}else{
		if(isImage($img)){//to avoid invalid path or 404 errors
			$img = getAmtyThumbPluginURL() . "invalid.gif";
		}
	}
	
	//$imgExt = getImageExtension($img);
	//To save image on disk
	$img_uri = getAmtyThumbCachePath() . $pid . "_" . $w . "_" . $h . $imgExt;
	
	if($pid == -1 || !file_exists($img_uri)) { //for specific image resizging, caching is not required. it'll be saved with -1 pid
		//resize and save it with $img_uri name
		@resizeImg($img,$percent,$constrain,$w,$h,$zc,$img_uri);
	}
	
	//Actual image url
	$resized_img = getAmtyThumbCacheURL() . $pid . "_" . $w . "_" . $h . $imgExt;
	
	if($img_url_only == "y"){
		$out = $resized_img;
	}else{
		$out = '<img src="'.$resized_img.'" />';
	}
	
	return $out;
}//function end

function getImageExtension($imgURL){
	$imgInfo = @getimagesize($imgURL);
	if($imgInfo[2] == IMAGETYPE_JPEG){
		return ".jpg";
	}elseif($imgInfo[2] == IMAGETYPE_GIF){
		return ".gif";
	}elseif($imgInfo[2] == IMAGETYPE_PNG){
		return ".png";
	}else{
		return '';
	}
}

function resizeImg($img,$percent,$constrain,$w,$h,$zc,$imgPath){
	// get image size of img
	$imgInfo = @getimagesize($img);	
	// image width
	$sw = $imgInfo[0];
	// image height
	$sh = $imgInfo[1];
	if( $sh >0 AND $sw > 0){
		if ($percent > 0) {
			// calculate resized height and width if percent is defined
			$percent = $percent * 0.01;
			$w = $sw * $percent;
			$h = $sh * $percent;
		} else {
			if (isset ($w) AND !isset ($h)) {
				// autocompute height if only width is set
				$h = (100 / ($sw / $w)) * .01;
				$h = @round ($sh * $h);
			} elseif (isset ($h) AND !isset ($w)) {
				// autocompute width if only height is set
				$w = (100 / ($sh / $h)) * .01;
				$w = @round ($sw * $w);
			} elseif (isset ($h) AND isset ($w) AND $constrain > 0) {
				// get the smaller resulting image dimension if both height
				// and width are set and $constrain is also set
				$hx = (100 / ($sw / $w)) * .01;
				$hx = @round ($sh * $hx);

				$wx = (100 / ($sh / $h)) * .01;
				$wx = @round ($sw * $wx);

				if ($hx < $h) {
					$h = (100 / ($sw / $w)) * .01;
					$h = @round ($sh * $h);
				} else {
					$w = (100 / ($sh / $h)) * .01;
					$w = @round ($sw * $w);
				}
			}
		}
	}
	
	// Create the resized image destination
	$thumb = @ImageCreateTrueColor ($w, $h);
		
	$im = '';
	if($imgInfo[2] == IMAGETYPE_JPEG){
		$im = @ImageCreateFromJPEG ($img);
	}elseif($imgInfo[2] == IMAGETYPE_GIF){
		$im = @ImageCreateFromGIF ($img);
	}elseif($imgInfo[2] == IMAGETYPE_PNG){
		imagealphablending( $thumb, false );
		imagesavealpha( $thumb, true );
		$im = @ImageCreateFromPNG ($img);
	}else{
		$im = false;
	}

	if (!$im) {
		// We get errors from PHP's ImageCreate functions...
		// So let's echo back the contents of the actual image.
		readfile ($img);
	} else {
		
		// Copy from image source, resize it, and paste to image destination
		
		$src_x = $src_y = 0;
		if( $zc > 0) {
			echo "cropping image" . $zc;
			$new_width = $w;
			$new_height = $h;
			$width = imagesx($im);
			$height = imagesy($im);
			
			if( $new_width > $width ) {
				$new_width = $width;
			}
			if( $new_height > $height ) {
				$new_height = $height;
			}
		
			$src_w = $width;
			$src_h = $height;

			$cmp_x = $width  / $new_width;
			$cmp_y = $height / $new_height;

			// calculate x or y coordinate and width or height of source

			if ( $cmp_x > $cmp_y ) {

				$src_w = round( ( $width / $cmp_x * $cmp_y ) );
				$src_x = round( ( $width - ( $width / $cmp_x * $cmp_y ) ) / 2 );

			} elseif ( $cmp_y > $cmp_x ) {

				$src_h = round( ( $height / $cmp_y * $cmp_x ) );
				$src_y = round( ( $height - ( $height / $cmp_y * $cmp_x ) ) / 2 );

			}
			
			$w = $new_width;
			$h = $new_height;
			$sw = $src_w;
			$sh = $src_h;
		}
		
		
		@ImageCopyResampled ($thumb, $im, 0, 0, $src_x, $src_y, $w, $h, $sw, $sh);
	
		if($thumb != ''){
				if($imgInfo[2] == IMAGETYPE_JPEG){
					imagejpeg($thumb, $imgPath , 100);
				}elseif($imgInfo[2] == IMAGETYPE_GIF){
					imagegif($thumb, $imgPath );
				}elseif($imgInfo[2] == IMAGETYPE_PNG){
					imagepng($thumb, $imgPath, 9);
				}
		}
			
		// Free up memory
		imagedestroy($thumb);
	}
}

?>