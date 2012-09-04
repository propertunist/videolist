<?php

class Videolist_Platform_Youtube implements Videolist_PlatformInterface
{
    public function getType()
    {
        return "youtube";
    }

    public function parseUrl($url)
    {
        $hostname_pattern = "(?:youtu\\.be|(?:www\\.)?youtube\\.com)";
		if (!preg_match("~^https?\\://$hostname_pattern/(.+)~i", $url, $m)) {
			return false;
		}
		$path = $m[1];
		$id_pattern = "[a-zA-Z0-9\\-_]{4,}";
		if (preg_match("~(?:^vi?/|\\bvi?=)($id_pattern)~", $path, $m)) {
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

		$xml = videolist_fetch_xml('http://gdata.youtube.com/feeds/api/videos/'.$video_id);
		if (!$xml) {
			return array(
				'title' => '',
				'description' => '',
			);
		}

        return array(
            'title' => (string)$xml->title,
            'description' => strip_tags($xml->content),
            'thumbnail' => "http://img.youtube.com/vi/$video_id/default.jpg",
        );
    }
}
