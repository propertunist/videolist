<?php

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

function videolist_get_data_metacafe($parsed){
	$video_id = $parsed['video_id'];
	
	$buffer = file_get_contents("http://www.metacafe.com/api/item/$video_id");
	$xml = new SimpleXMLElement($buffer);
	
	return array(
		'title' => sanitize_string(current($xml->xpath('/rss/channel/item/title'))),
		'description' => strip_tags(current($xml->xpath('/rss/channel/item/description'))),
		'thumbnail' => sanitize_string(current($xml->xpath('/rss/channel/item/media:thumbnail/@url'))),
		'embedurl' => sanitize_string(current($xml->xpath('/rss/channel/item/media:content/@url'))),
	);
}
