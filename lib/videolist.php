<?php

function videolist_parseurl_youtube($url) {
	$parsed = parse_url($url);
	parse_str($parsed['query'], $query);
	
	if ($parsed['host'] != 'www.youtube.com' || $parsed['path'] != '/watch' || !isset($query['v'])) {
		return false;
	}
	
	return array(
		'videotype' => 'youtube',
		'video_id' => $query['v'],
	);
}

function videolist_parseurl_vimeo($url) {
	$parsed = parse_url($url);
	$path = explode('/', $parsed['path']);
	
	if ($parsed['host'] != 'vimeo.com' || !(int) $path[1]) {
		return false;
	}

	return array(
		'videotype' => 'vimeo',
		'video_id' => $path[1],
	);
}

function videolist_parseurl_metacafe($url) {
	$parsed = parse_url($url);
	$path = explode('/', $parsed['path']);

	if ($parsed['host'] != 'www.metacafe.com' || $path[1] != 'watch' || !(int) $path[2]) {
		return false;
	}
	
	return array(
		'videotype' => 'metacafe',
		'video_id' => $path[2],
	);
}

function videolist_parseurl_bliptv($url) {
	$parsed = parse_url($url);
	$path = explode('/', $parsed['path']);

	if ($parsed['host'] != 'blip.tv' || count($path) < 3) {
		return false;
	}
	
	return array(
		'videotype' => 'bliptv',
		'video_id' => $parsed['path'],
	);
}

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

function videolist_parseurl($url){
	if ($parsed = videolist_parseurl_youtube($url)) return $parsed;
	elseif ($parsed = videolist_parseurl_vimeo($url)) return $parsed;
	elseif ($parsed = videolist_parseurl_metacafe($url)) return $parsed;
	elseif ($parsed = videolist_parseurl_bliptv($url)) return $parsed;
	elseif ($parsed = videolist_parseurl_gisstv($url)) return $parsed;
	else return array();
}

function videolist_get_data($video_parsed_url) {
	$videotype = $video_parsed_url['videotype'];
	$video_id = $video_parsed_url['video_id'];
	switch($videotype){
		case 'youtube': return videolist_get_data_youtube($video_id);
		case 'vimeo': return videolist_get_data_vimeo($video_id);
		case 'metacafe': return videolist_get_data_metacafe($video_id);
		case 'bliptv': return videolist_get_data_bliptv($video_id);
		case 'gisstv': return videolist_get_data_gisstv($video_id);
		default: return array();
	}
}


function videolist_get_data_youtube($video_id){
	$buffer = file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$video_id);
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => sanitize_string($xml->title),
		'description' => strip_tags($xml->content),
		'thumbnail' => "http://img.youtube.com/vi/$video_id/default.jpg",
		'video_id' => $video_id,
		'videotype' => 'youtube',
	);
}

function videolist_get_data_vimeo($video_id){
	$buffer = file_get_contents("http://vimeo.com/api/v2/video/$video_id.xml");
	$xml = new SimpleXMLElement($buffer);
	
	$videos = $xml->children();
	$video = $videos[0];
	
	return array(
		'title' => sanitize_string($video->title),
		'description' => strip_tags($video->description),
		'thumbnail' => sanitize_string($video->thumbnail_medium),
		'video_id' => $video_id,
		'videotype' => 'vimeo',
	);
}

function videolist_get_data_metacafe($video_id){
	$buffer = file_get_contents("http://www.metacafe.com/api/item/$video_id");
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => sanitize_string(current($xml->xpath('/rss/channel/item/title'))),
		'description' => strip_tags(current($xml->xpath('/rss/channel/item/description'))),
		'thumbnail' => sanitize_string(current($xml->xpath('/rss/channel/item/media:thumbnail/@url'))),
		'embedurl' => sanitize_string(current($xml->xpath('/rss/channel/item/media:content/@url'))),
		'video_id' => $video_id,
		'videotype' => 'metacafe',
	);
}

function videolist_get_data_bliptv($video_id){
	$buffer = file_get_contents('http://blip.tv'.$video_id.'?skin=rss');
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => sanitize_string(current($xml->xpath('/rss/channel/item/title'))),
		'description' => strip_tags(current($xml->xpath('/rss/channel/item/description'))),
		'thumbnail' => sanitize_string(current($xml->xpath('/rss/channel/item/media:thumbnail/@url'))),
		'embedurl' => sanitize_string(current($xml->xpath('/rss/channel/item/blip:embedUrl'))),
		'video_id' => $video_id,
		'videotype' => 'bliptv',
	);
}

function videolist_get_data_gisstv($video_id){
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
	return array_merge($data, array(
		'video_id' => $video_id,
		'videotype' => 'gisstv',
	));
}
