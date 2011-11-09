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

function videolist_parseurl($url){
	if ($parsed = videolist_parseurl_youtube($url)) return $parsed;
	elseif ($parsed = videolist_parseurl_vimeo($url)) return $parsed;
	elseif ($parsed = videolist_parseurl_metacafe($url)) return $parsed;
	elseif ($parsed = videolist_parseurl_bliptv($url)) return $parsed;
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
		default: return array();
	}
}


function videolist_get_data_youtube($video_id){
	$buffer = file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$video_id);
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => sanitize_string($xml->title),
		'description' => sanitize_string($xml->content),
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
		'description' => sanitize_string($video->description),
		'thumbnail' => sanitize_string($video->thumbnail_medium),
		'video_id' => $video_id,
		'videotype' => 'vimeo',
	);
}

function videolist_get_data_metacafe($video_id){ //FIXME
	$buffer = file_get_contents("http://www.metacafe.com/api/item/$video_id");
	$xml = new SimpleXMLElement($buffer);
	
	$children = $xml->children();
	$channel = $children[1];
	
	preg_match('/<img[^>]+src[\\s=\'"]+([^"\'>\\s]+)/is', $channel->description, $matches);
	
	return array(
		'title' => $channel->title,
		'description' => $channel->description,
		'thumbnail' => $matches[1],
		'video_id' => $video_id,
		'videotype' => 'metacafe',
	);
}

function videolist_get_data_bliptv($video_id){
	$buffer = file_get_contents('http://blip.tv'.$video_id.'?skin=rss');
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => current($xml->xpath('/rss/channel/item/title')),
		'description' => current($xml->xpath('/rss/channel/item/description')),
		'thumbnail' => current($xml->xpath('/rss/channel/item/media:thumbnail/@url')),
		'embedurl' => current($xml->xpath('/rss/channel/item/blip:embedUrl')),
		'video_id' => $video_id,
		'videotype' => 'bliptv',
	);
}
