<?php
function amty_displayThumb($postid){
	$metaVal = get_post_meta($postid,'amtyThumb',true);
	echo '<div style="float:left;width:50%;">';
	if($metaVal != ''){
		echo "<br />First cached image from post";
		echo '<br /><a href="' . $metaVal . '" class="thickbox"><img src="'.$metaVal.'" width="300" alt="Cache the image before displaying the thumbnail" /></a><br />';
	}
	echo '</div>';
	echo '<div style="float:left;width:50%;">';
	$dir = getAmtyThumbCachePath();
	$url =  getAmtyThumbCacheURL();
	echo "<br />Image path on server : " . $dir;
	echo "<br />Image url : " . $url;
	echo "<br />Images cached on File system";
	if($handle=opendir($dir)){
		while ( ($file = readdir($handle)) !==false) {
			if(preg_match('/^'. $postid .'_.*\.jpg/', $file)){
				echo '<br /><center><a href="' . $url.'/'.$file . '" class="thickbox"><img src="'.$url.'/'.$file.'" width="300" alt="Cache the image before displaying the thumbnail" /></a><br />'.$file.'</center><br />';
			}
		}
		closedir($handle);
	}
	echo '</div><div style="clear:both;"></div>';
}

function amty_testPlugin($imgurl,$pid,$w,$h,$percent,$constrain,$zc){
	
	if($pid != ''){
		$starttime = time();
		$img = amty_take_first_img_by_id($pid);
		$endtime = time();
		echo "<br />Time to extract image from post: " . ($endtime - $starttime);
	}elseif($imgurl != ''){
		$img = $imgurl;
	}
	//echo $img;
	$ext = getImageExtension($img);
	$img_uri = getAmtyThumbPluginPath() . "testimage" . $ext;
	$img_url = getAmtyThumbPluginURL() . "testimage" . $ext;
	//echo $img_uri;
	$starttime = time();
	$endtime = time();
	@unlink($img_uri);
	
	@resizeImg($img,$percent,$constrain,$w,$h,$zc,$img_uri);
	echo "<br />Time to resize image: " . ($endtime - $starttime);
	
	echo '<br />Original Image<br />';
	echo '<img src="'.$img.'" />';
	echo '<br />Resized Image<br />';
	echo '<img src="'.$img_url.'" />';
}

?>