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
        $API_key = elgg_get_plugin_setting('google_API_key', 'videolist');
        $video_id = $parsed['video_id'];

        $buffer = file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$video_id . '&key=' . $API_key);
    
        $decoded_buffer =json_decode($buffer, true);

       if ($decoded_buffer['items'][0])
        {
        return array(
            'title' => $decoded_buffer['items'][0]['snippet']['title'],
            'description' => strip_tags($decoded_buffer['items'][0]['snippet']['description']),
            'thumbnail' => "https://img.youtube.com/vi/$video_id/0.jpg",
        );
        }
        else
            {
                register_error(elgg_echo('videolist:error:empty_provider_data'));
                error_log('youtube video does not exist: ' . $video_id);
                return false;
            }
    }
}