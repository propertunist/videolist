<?php

class Videolist_Platform_Youtube implements Videolist_PlatformInterface
{
    public function getType()
    {
        return "youtube";
    }

    public function parseUrl($url)
    {
        $scheme = "https?\\:";
		$hostname = "(?:youtu\\.be|(?:www\\.)?youtube\\.com)";
		$path = "/(?:vi?/|(?:watch)?\\?vi?=)?";
		$id = "[a-zA-Z0-9\\-_]{4,}";
		if (preg_match("~^$scheme//$hostname{$path}($id)~", $url, $m)) {
			return array(
				'video_id' => $m[1],
				'video_url' => "http://www.youtube.com/watch?v={$m[1]}",
			);
		}
		return false;
    }

    public function getData($parsed)
    {
        $video_id = $parsed['video_id'];

        $buffer = file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$video_id);
        $xml = new SimpleXMLElement($buffer);

        return array(
            'title' => (string)$xml->title,
            'description' => strip_tags($xml->content),
            'thumbnail' => "http://img.youtube.com/vi/$video_id/default.jpg",
        );
    }
}
