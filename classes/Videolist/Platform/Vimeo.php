<?php

class Videolist_Platform_Vimeo implements Videolist_PlatformInterface
{
    public function getType()
    {
        return "vimeo";
    }

    public function parseUrl($url)
    {
		$scheme = "https?\\:";
		$hostname = "vimeo\\.com";
		$path1 = "/(?:groups/[a-zA-Z0-9]+/)?(?:video/)?";
		$path2 = "/[a-z]+\\.swf\\?clip_id=";
		$id = "[0-9]{5,}";
		if (preg_match("~^$scheme//$hostname(?:$path1|$path2)($id)~", $url, $m)) {
			return array(
				'video_id' => $m[1],
				'video_url' => "http://vimeo.com/{$m[1]}",
			);
		}
		return false;
    }

    public function getData($parsed)
    {
        $video_id = $parsed['video_id'];

        $buffer = file_get_contents("http://vimeo.com/api/v2/video/$video_id.xml");
        $xml = new SimpleXMLElement($buffer);

        $videos = $xml->children();
        $video = $videos[0];

        return array(
            'title' => (string)$video->title,
            'description' => strip_tags($video->description),
            'thumbnail' => (string)$video->thumbnail_medium,
        );
    }
}
