<?php

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

function videolist_get_data_vimeo($parsed){
	$video_id = $parsed['video_id'];
	
	$buffer = file_get_contents("http://vimeo.com/api/v2/video/$video_id.xml");
	$xml = new SimpleXMLElement($buffer);
	
	$videos = $xml->children();
	$video = $videos[0];
	
	return array(
		'title' => sanitize_string($video->title),
		'description' => strip_tags($video->description),
		'thumbnail' => sanitize_string($video->thumbnail_medium),
	);
}
