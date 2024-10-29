<?php
   function thumb($url) {
		//$this->EE =& get_instance();
		
		/* set up plugin parameters */
		$url = trim($url);

		/* check if slashes are encoded and decodes html if so */
		if(preg_match("/&#47;/", $url)) {
			$url = html_entity_decode($url);
		}
		
		/* 
			Configuration of each video service :
		
			- regexp : regular expression for identifying the video service and extracting the video ID
			- img : pattern of the thumbnail/XML URL to call from an embed tag (%s : video id)
			
		*/
		
		$services = array();
		
		$services['youtube']['img'] = "http://img.youtube.com/vi/%s/default.jpg";
		//$services['youtube']['img'] = "http://img.youtube.com/vi/%s/0.jpg";
		//$services['youtube']['regexp'] = array('/^https?:\/\/(www\.)?youtube\.com.*\/watch\?v=(\S*)/', 1);
		$services['youtube']['regexp'] = array('/youtube.com\/watch\?v=(\S*)/', 1);

		$services['vimeo']['img'] = "http://vimeo.com/api/v2/video/%s.xml";
		$services['vimeo']['regexp'] = array('/^https?:\/\/(www\.)?vimeo\.com\/([0-9]*)/', 2);
				
		$services['dailymotion']['img'] = "http://www.dailymotion.com/thumbnail/video/%s";
		$services['dailymotion']['regexp'] = array('/^https?:\/\/(www\.)?dailymotion\.com\/video\/([^\/]*)/', 2);
		
		$services['veoh']['img'] = "http://www.veoh.com/rest/video/%s/details";
		$services['veoh']['regexp'] = array('/^https?:\/\/(www\.)?veoh\.com.*\/watch\/([^\/]*)/', 2);
		
		$services['myspace']['img'] = "http://mediaservices.myspace.com/services/rss.ashx?type=video&videoID=%s";
		$services['myspace']['regexp'] = array('/^https?:\/\/vids\.myspace\.com.*videoid=([0-9]*)/', 1);
		
		$services['metacafe']['img'] = "http://www.metacafe.com/thumb/%s.jpg";
		$services['metacafe']['regexp'] = array('/^https?:\/\/(www\.)?metacafe\.com.*\/watch\/([0-9]*)\/.*/', 2);
		
		$services['revver']['img'] = 'http://frame.revver.com/frame/320x240/%s.jpg';
		$services['revver']['regexp'] = array('/^https?:\/\/(www\.)?revver\.com\/video\/([0-9]*)\/.*/', 2);
		
		/* convert a public URL into an appropriate video tag */
		
		foreach($services as $service => $s) {
			if(preg_match($s['regexp'][0], $url, $matches, PREG_OFFSET_CAPTURE) > 0) {
				//print_r($matches);
				$match_key = $s['regexp'][1];
				$video_id = $matches[$match_key][0];
				if(isset($s['img'])) {
					
					if($service == 'vimeo'){
						$img_url = sprintf($s['img'], $video_id);
						$xml = simplexml_load_string(file_get_contents($img_url));
						$img_url = $xml->video->thumbnail_large;
					}elseif($service == 'veoh'){
						$img_url = sprintf($s['img'], $video_id);
						$xml = simplexml_load_string(file_get_contents($img_url));
						$img_url = $xml->video['fullMedResImagePath'];
					}elseif($service == 'myspace'){
						$img_url = sprintf($s['img'], $video_id);
						$xml = simplexml_load_string(str_replace('media:thumbnail','mediathumbnail',file_get_contents($img_url)));
						$img_url = $xml->channel->item->mediathumbnail['url'];
					}else{
						/* Video packed in an embed tag */
						$img_url = sprintf($s['img'], $video_id);
					}					
				} 
			}
		}
		return $img_url;
	}
?>