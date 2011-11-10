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

function videolist_get_data_youtube($parsed){
	$video_id = $parsed['video_id'];
	
	$buffer = file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$video_id);
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => sanitize_string($xml->title),
		'description' => strip_tags($xml->content),
		'thumbnail' => "http://img.youtube.com/vi/$video_id/default.jpg",
	);
}
