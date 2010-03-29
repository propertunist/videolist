<?php

/**
 * Elgg Video Plugin
 * This plugin allows users to create a library of youtube/vimeo/metacafe videos
 * @file - the add user interface
 * @package Elgg
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */
// Make sure we're logged in (send us to the front page if not)
gatekeeper();
$page_owner = page_owner_entity();
$error = array(
							'no-video' => 1
							);
$error_msg = array(
							'no-video' => "Please enter a valid video url"
							);

$container_guid = get_input("container_guid");
set_page_owner($container_guid);

$confirm_action = get_input('video_action');
$guid = get_input('guid');
$access_id = get_input('access_id');
$title_videourl = get_input('title_videourl');
$Pagecontainer = get_input('page');
$get_addvideourl = get_input('add_videourl');
$timestamp = time();
$token = generate_action_token(time());
if (!empty($get_addvideourl) && ($Pagecontainer == "youtube")) {
	$title_add_videourl = "http://www.youtube.com/watch?v=".$get_addvideourl;
} else if(!empty($get_addvideourl) && ($Pagecontainer == "metacafe")) {
	$title_add_videourl = "http://www.metacafe.com/api/item/".$get_addvideourl;
} else if(!empty($get_addvideourl) && ($Pagecontainer == "vimeo")) {
	$title_add_videourl = "http://vimeo.com/".$get_addvideourl;
} else {
	$title_add_videourl = "";
}

$tags = get_input('videolisttags');

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

$body = '<form action="'.$_SERVER['php_self'].'" method="post" class="margin_top">';
$body .= elgg_view('input/hidden',array('internalname'=>'video_action', 'value'=>'add_video'));
$body .= elgg_view('input/hidden',array('internalname'=>'guid', 'value'=>$vars['guid']));


$body .= '<p><label>'.elgg_echo("videolist:title_videourl").'<br />';
$body .= elgg_view("input/text",array('internalname' => 'title_videourl','value'=>$title_add_videourl));
if($error['no-video'] == 0) {
	$body .= '<div class="videolist_error">'.$error_msg['no-video'].'</div>';
}
$body .= '</label></p>';

$body .= '<p><label>'.elgg_echo('videolist:tags');
$body .= elgg_view('input/tags', array('internalname' => 'videolisttags', 'value' => $tags));
$body .= '</label></p>';

$body .= '<p><label>'.elgg_echo("videolist:title_access").'<br />';
$body .= elgg_view('input/access',array('internalname'=>'access_id', 'value' => $access_id));
$body .= '</label></p>';
$body .= elgg_view('input/submit', array('internalname'=>'submit','value'=>elgg_echo('videolist:submit')));
$body .= '</form>';

print $body;
