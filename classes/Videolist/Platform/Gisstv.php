<?php

class Videolist_Platform_Gisstv implements Videolist_PlatformInterface
{
    public function getType()
    {
        return "gisstv";
    }

    public function parseUrl($url)
    {
        $parsed = parse_url($url);
        $path = explode('/', $parsed['path']);

        if ($parsed['host'] != 'giss.tv' || $path[1] != 'dmmdb') {
            return false;
        }

        if($path[2] == 'contents' && isset($path[3])) {
            $video_id = $path[3];
        } elseif($path[3] == 'contents' && isset($path[4])) {
            $video_id = $path[4];
        } else {
            return false;
        }

        return array(
            'video_id' => $video_id,
			// @todo output a canonical URL for every video (don't trust user input)
        );
    }

    public function getData($parsed)
    {
        $video_id = $parsed['video_id'];

		$xml = videolist_fetch_xml('http://giss.tv/dmmdb//rss.php');

        $data = array(
			'title' => '',
			'description' => '',
		);
		if ($xml) {
			foreach($xml->xpath('/rss/channel/item') as $item) {
				if ($item->link === 'http://giss.tv/dmmdb//contents/'.$video_id) {
					$data['title'] = (string)$item->title;
					$data['description'] = strip_tags($item->description);
					$data['thumbnail'] = (string)$item->thumbnail;
					break;
				}
			}
		}
        return $data;
    }
}
