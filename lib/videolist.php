<?php

define('YOUTUBE', 1);
define('VIMEO', 2);
define('METACAFE', 3);

function videolist_parseurl_youtube($url) {
	if (!preg_match('/(http:\/\/)([a-zA-Z]{2,3}\.)(youtube\.com\/)(.*)/', $url, $matches)) {
	return false;
	}

	$domain = $matches[2] . $matches[3];
	$path = $matches[4];

	if (!preg_match('/^(watch\?v=)([a-zA-Z0-9_-]*)(&.*)?$/',$path, $matches)) {
	return false;
	}

	$hash = $matches[2];
	
	return array(
		'domain' => $domain,
		'video_id' => $hash,
	);
}

function videolist_parseurl_vimeo($url) {
	if (!preg_match('/(http:\/\/)([a-zA-Z]{2,3}\.)*(vimeo\.com\/)(.*)/', $url, $matches)) {
		return false;
	}

	$domain = $matches[2] . $matches[3];
	$hash = $matches[4];

	return array(
		'domain' => $domain,
		'video_id' => $hash,
	);
}

function videolist_parseurl_metacafe($url) {
	if (!preg_match('/(http:\/\/)([a-zA-Z]{2,3}\.)(metacafe\.com\/)(.*)/', $url, $matches)) {
		return false;
	}

	$domain = $matches[2] . $matches[3];
	$path = $matches[4];

	$hash = $matches[2];

	return array(
		'domain' => $domain,
		'video_id' => $hash,
	);
}

function videolist_parseurl($url){
	if ($parsed = videolist_parseurl_youtube($url)){
		$parsed['site'] = YOUTUBE;
		return $parsed;
	} elseif ($parsed = videolist_parseurl_vimeo($url)) {
		$parsed['site'] = VIMEO;
		return $parsed;
	} elseif ($parsed = videolist_parseurl_metacafe($url)) {
		$parsed['site'] = METACAFE;
		return $parsed;
	} else {
		return array();
	}
}

function videolist_get_data($video_parsed_url) {
	$site = $video_parsed_url['site'];
	$video_id = $video_parsed_url['video_id'];
	switch($site){
		case YOUTUBE: return videolist_get_data_youtube($video_id);
		case VIMEO: return videolist_get_data_vimeo($video_id);
		case METACAFE: return videolist_get_data_metacafe($video_id);
		default: return array();
	}
}


function videolist_get_data_youtube($video_id){
	$buffer = file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$video_id);
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => sanitize_string($xml->title),
		'description' => sanitize_string($xml->content),
		'icon' => "http://img.youtube.com/vi/$video_id/default.jpg",
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
		'icon' => sanitize_string($video->thumbnail_medium),
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
		'icon' => $matches[1],
		'video_id' => $video_id,
		'videotype' => 'metacafe',
	);
}
