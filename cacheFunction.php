<?php

function getAmtyThumbCachePath(){
	$dir = WP_CONTENT_DIR . "/amtythumbcache/";
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
	return $dir;
}

function getAmtyThumbPluginURL(){
	return WP_PLUGIN_URL . "/amtythumb/";
}

function getAmtyThumbPluginPath(){
	return WP_PLUGIN_DIR . "/amtythumb/";
}

function getAmtyThumbCacheURL(){
	return WP_CONTENT_URL . "/amtythumbcache/";
}

//empty image cache and all thumbnails from file system
function amty_clearImageCacheSoft(){
	$query = new WP_Query( 'posts_per_page=-1' );
	while ( $query->have_posts() ) : $query->the_post();
		delete_post_meta(get_the_ID(), 'amtyThumb');
	endwhile;
	wp_reset_postdata();
}

function amty_clearImageCacheHard(){
	if($handle=opendir(getAmtyThumbCachePath())){
		while ( ($file = readdir($handle)) !==false) {
			@unlink(getAmtyThumbCachePath().$file);
		}
		closedir($handle);
	}
}

function amty_clearImageCacheFull(){
	amty_clearImageCacheSoft();
	amty_clearImageCacheHard();
}


//delete an image from cache and its all thumbnails from file system
function amty_deletePostFromCache($postId){
	if(get_post_meta($postId,'amtyThumb',true) != '' ){
		if($handle=opendir(getAmtyThumbCachePath())){
			while ( ($file = readdir($handle)) !==false) {
				if(preg_match('/^'. $postId .'_.*\.jpg/', $file)){
					@unlink(getAmtyThumbCachePath().$file);
				}
			}
			closedir($handle);
		}
		delete_post_meta($postId, 'amtyThumb');
	}
}
//put 1st image of the post into cache if does not present.
//if force != 0 put 1st image of the post into cache even if presents.
function amty_putIntoImageCache($postId,$force=0,$default_img=''){
	$metaVal = get_post_meta($postId,'amtyThumb',true);
	$imgExt = '.gif';
	if($force == 0 && $metaVal != ''){
		//do nothing
	}else{
		$img = amty_take_first_img_by_id($postId);
		if($img ==''){//image not present
			if($default_img != ''){//custom default image
				$imgExt = getImageExtension($default_img);
				$img = $default_img;
			}
			else{
				$img = getAmtyThumbPluginURL(). "amtytextthumb.gif";
			}
		}else{
			$imgExt = getImageExtension($img);
			if($imgExt != ''){
				$imageString = file_get_contents($img);
				$img = getAmtyThumbCachePath() . $postId . $imgExt;
				file_put_contents($img,$imageString);
			}else{
				$img = getAmtyThumbPluginURL(). "invalid.gif";
			}
		}
		update_post_meta($postId,'amtyThumb',$img);
		update_post_meta($postId,'amtyThumbExt',$imgExt);
	}
}

//cache images for uncached posts
function amty_populateCache($force=0){
	$query = new WP_Query( 'posts_per_page=-1' );
	while ( $query->have_posts() ) : $query->the_post();
		amty_putIntoImageCache(get_the_ID(),$force);
	endwhile;
	wp_reset_postdata();
}

function amty_populateCacheAllManual($w,$h,$c,$zc){
	$query = new WP_Query( 'posts_per_page=-1' );
	print "caching for : ";
	while ( $query->have_posts() ) : $query->the_post();
		print $pid .",";
		$pid = get_the_ID();
		amty_lead_img($w,$h,$c,'','',$zc,$pid,'','');
	endwhile;
	wp_reset_postdata();
}

function amty_populateCacheManual($w,$h,$c,$zc,$pid){
	amty_lead_img($w,$h,$c,'','',$zc,$pid,'','');
}

//empty current acche and repopulate it for all posts
function amty_repopulateImageCache(){
	amty_populateCache(1);
}

function amty_getImageCacheCount(){
	$cnt=0;
	$query = new WP_Query( 'posts_per_page=-1' );
	while ( $query->have_posts() ) : $query->the_post();
		$metaVal = get_post_meta(get_the_ID(),'amtyThumb',true);
		if($metaVal != ''){
			$cnt= $cnt + 1;
		}
	endwhile;
	wp_reset_postdata();
	return $cnt;
}

function amty_getFilesInCacheFolder(){
	$count = 0; 
    $dir = getAmtyThumbCachePath();
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false){
            if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) 
                $count++;
        }
    }
	return $count;
}

function reportBrokenImage(){
	$query = new WP_Query( 'posts_per_page=-1' );
	while ( $query->have_posts() ) : $query->the_post();
		$pid = get_the_ID();
		$metaVal = get_post_meta($pid,'amtyThumb',true);
		if($metaVal != '' && !isImage($metaVal)){
			echo "PostID :" . $pid . ". Broken imahe URL : " + $metaVal;
		}
	endwhile;
	wp_reset_postdata();
}
?>