<?php

class Videolist_Platform_Internetarchive implements Videolist_PlatformInterface
{
	public function getType()
	{
		return "internetarchive";
	}

	public function parseUrl($url)
	{
		$scheme = "https?\\:";
		$hostname = "(?:www\\.)?archive\\.org";
		$path = "/(?:details|embed|download)/";
		$id = "[0-9a-zA-Z\\-_]{3,}";
		if (preg_match("~^$scheme//$hostname{$path}($id)~", $url, $m)) {
			return array(
				'video_id' => $m[1],
				'video_url' => "http://archive.org/details/{$m[1]}",
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
