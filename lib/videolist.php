<?php

function video_youtube_parse_url($url) {
	if (!preg_match('/(http:\/\/)([a-zA-Z]{2,3}\.)(youtube\.com\/)(.*)/', $url, $matches)) {
	return false;
	}

	$domain = $matches[2] . $matches[3];
	$path = $matches[4];

	if (!preg_match('/^(watch\?v=)([a-zA-Z0-9_-]*)(&.*)?$/',$path, $matches)) {
	return false;
	}

	$hash = $matches[2];
	return $domain . 'v/' . $hash;
}

function video_vimeo_parse_url($url) {
	if (!preg_match('/(http:\/\/)([a-zA-Z]{2,3}\.)(vimeo\.com\/)(.*)/', $url, $matches)) {
		return false;
	}

	$domain = $matches[2] . $matches[3];
	$path = $matches[4];

	$hash = $matches[2];

	return $domain . '/' . $hash;
}

function video_metacafe_parse_url($url) {
	if (!preg_match('/(http:\/\/)([a-zA-Z]{2,3}\.)(metacafe\.com\/)(.*)/', $url, $matches)) {
		return false;
	}

	$domain = $matches[2] . $matches[3];
	$path = $matches[4];

	$hash = $matches[2];

	return $domain . '/' . $hash;
}

if(isset($confirm_action) && ($confirm_action == 'add_video')) {
	if(isset($title_videourl) && ($title_videourl != '')) {
		if($Pagecontainer != "youtube" || $Pagecontainer != "vimeo" || $Pagecontainer != "metacafe"){
			if(preg_match("/youtube/i", $title_videourl)) {
				$Pagecontainer = "youtube";
			}

			if(preg_match("/vimeo/i", $title_videourl)) {
				$Pagecontainer = "vimeo";
			}

			if(preg_match("/metacafe/i", $title_videourl)) {
				$Pagecontainer = "metacafe";
			}
		}
		if($Pagecontainer == "youtube") {
			$is_valid_video = video_youtube_parse_url($title_videourl);
		} else if($Pagecontainer == "vimeo") {
			$is_valid_video = video_vimeo_parse_url($title_videourl);
			$is_valid_video = $get_addvideourl;
		} else if($Pagecontainer == "metacafe"){
			$is_valid_video = video_metacafe_parse_url($title_videourl);
			$is_valid_video = $get_addvideourl;
		}

		if($is_valid_video) {
			$error['no-video'] = 1;
			$_SESSION['candidate_profile_video'] = $is_valid_video;
			$_SESSION['candidate_profile_video_access_id'] = $access_id;
			$_SESSION['videolisttags'] = $tags;
			$_SESSION['Pagecontainer'] = $Pagecontainer;
			$_SESSION['container_guid'] = $container_guid;
			$url = "action/videolist/add?__elgg_ts={$timestamp}&__elgg_token={$token}";
			forward($url);
		}
		else
			$error['no-video'] = 0;
	}
	else {
		$error['no-video'] = 0;
	}
}
