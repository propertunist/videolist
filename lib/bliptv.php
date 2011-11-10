<?php

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

function videolist_get_data_bliptv($parsed){
	$video_id = $parsed['video_id'];
	
	$buffer = file_get_contents('http://blip.tv'.$video_id.'?skin=rss');
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => sanitize_string(current($xml->xpath('/rss/channel/item/title'))),
		'description' => strip_tags(current($xml->xpath('/rss/channel/item/description'))),
		'thumbnail' => sanitize_string(current($xml->xpath('/rss/channel/item/media:thumbnail/@url'))),
		'embedurl' => sanitize_string(current($xml->xpath('/rss/channel/item/blip:embedUrl'))),
	);
}
