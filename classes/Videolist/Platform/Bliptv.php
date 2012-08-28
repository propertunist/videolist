<?php

class Videolist_Platform_Bliptv implements Videolist_PlatformInterface
{
    public function getType()
    {
        return "bliptv";
    }

    public function parseUrl($url)
    {
		$scheme = "http\\:";
		$hostname = "blip\\.tv";
		$path = "(?:/[a-zA-Z0-9\\-]+){2}";
		if (preg_match("~^$scheme//$hostname($path)~", $url, $m)) {
			return array(
				'video_id' => $m[1],
				'video_url' => "http://blip.tv{$m[1]}",
			);
		}
		return false;
    }

    public function getData($parsed)
    {
        $video_id = $parsed['video_id'];

        $buffer = file_get_contents('http://blip.tv'.$video_id.'?skin=rss');
        $xml = new SimpleXMLElement($buffer);

		return array(
            'title' => (string) current($xml->xpath('/rss/channel/item/title')),
            'description' => strip_tags(current($xml->xpath('/rss/channel/item/blip:puredescription'))),
            'thumbnail' => (string) current($xml->xpath('/rss/channel/item/media:thumbnail/@url')),
            'embedurl' => (string) current($xml->xpath('/rss/channel/item/blip:embedUrl')),
        );
    }
}
