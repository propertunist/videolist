<?php

function videolist_parseurl_gisstv($url) {
	$parsed = parse_url($url);
	$path = explode('/', $parsed['path']);

	if ($parsed['host'] != 'giss.tv' || $path[1] != 'dmmdb') {
		return false;
	}
	
	if($path[2] == 'contents' && isset($path[3])) {
		$video_id = $path[3];
	} elseif($path[3] == 'contents' && isset($path[4])) {
		$video_id = $path[4];
	} else {
		return false;
	}
	
	return array(
		'videotype' => 'gisstv',
		'video_id' => $video_id,
	);
}

function videolist_get_data_gisstv($parsed){
	$video_id = $parsed['video_id'];
	
	$buffer = file_get_contents('http://giss.tv/dmmdb//rss.php');
	$xml = new SimpleXMLElement($buffer);
	
	$data = array();
	foreach($xml->xpath('/rss/channel/item') as $item){
		if(sanitize_string($item->link) == 'http://giss.tv/dmmdb//contents/'.$video_id) {
			$data['title'] = sanitize_string($item->title);
			$data['description'] = strip_tags($item->description);
			$data['thumbnail'] = sanitize_string($item->thumbnail);
			break;
		}
	}
	return $data;
}
