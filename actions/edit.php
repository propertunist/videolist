<?php
/**
 * Elgg video edit
 */

global $CONFIG;
	
// Get variables
$title = strip_tags(get_input("title_videourl"));
$tags = get_input("tags");
$access_id = (int) get_input("access_id");
	
$guid = (int) get_input('video_guid');

if (!$video = get_entity($guid)) {
	register_error(elgg_echo("videolist:noentity"));
	forward($CONFIG->wwwroot . "pg/videolist/" . $_SESSION['user']->username);
	exit;
}
	
$result = false;

$container_guid = $video->container_guid;
$container = get_entity($container_guid);
	
if ($video->canEdit()) {
	
	$video->access_id = $access_id;
	$video->title = $title;
	
	// Save tags
	$tags = explode(",", $tags);
	$video->tags = $tags;
	$result = $video->save();
}
	
if ($result)
	system_message(elgg_echo("videolist:editsaved"));
else
	register_error(elgg_echo("videolist:editfailed"));
	
forward($CONFIG->wwwroot . "pg/videolist/" . $container->username);