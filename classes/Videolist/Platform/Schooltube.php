<?php

class Videolist_Platform_Schooltube implements Videolist_PlatformInterface
{
	public function getType()
	{
		return "schooltube";
	}

	public function parseUrl($url)
	{
		$scheme = "https?\\:";
		$hostname = "www\\.schooltube\\.com";
		$path = "/(?:v|video|embed)/";
		$id = "[0-9a-f]{16,}";
		if (preg_match("~^$scheme//$hostname{$path}($id)~", $url, $m)) {
			return array(
				'video_id' => $m[1],
				'video_url' => "http://www.schooltube.com/video/{$m[1]}",
			);
		}
		return false;
	}

	public function getData($parsed)
	{
		return array(
			'title' => '',
			'description' => '',
		);
	}
}
