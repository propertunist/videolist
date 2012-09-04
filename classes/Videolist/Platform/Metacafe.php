<?php

class Videolist_Platform_Metacafe implements Videolist_PlatformInterface
{
    public function getType()
    {
        return "metacafe";
    }

    public function parseUrl($url)
    {
		$scheme = "https?\\:";
		$hostname = "www\\.metacafe\\.com";
		$path = "/(?:watch|fplayer)/";
		$id = "[0-9]{4,}";
		if (preg_match("~^$scheme//$hostname{$path}($id)~", $url, $m)) {
			return array(
				'video_id' => $m[1],
				'video_url' => "http://www.metacafe.com/watch/{$m[1]}",
			);
		}
		return false;
    }

    public function getData($parsed)
    {
        $video_id = $parsed['video_id'];

		$xml = videolist_fetch_xml("http://www.metacafe.com/api/item/$video_id");
		if (!$xml) {
			return array(
				'title' => '',
				'description' => '',
			);
		}

        return array(
            'title' => (string)current($xml->xpath('/rss/channel/item/title')),
            'description' => strip_tags(current($xml->xpath('/rss/channel/item/description'))),
            'thumbnail' => (string)current($xml->xpath('/rss/channel/item/media:thumbnail/@url')),
            'embedurl' => (string)current($xml->xpath('/rss/channel/item/media:content/@url')),
        );
    }
}
