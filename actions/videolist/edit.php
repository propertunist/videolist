<?php
/**
 * Create or edit a video
 *
 * @package ElggVideolist
 */

$variables = elgg_get_config('videolist');
$input = array();
foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

// Get guids
$videolist_guid = (int)get_input('videolist_guid');
$container_guid = (int)get_input('container_guid');

elgg_make_sticky_form('videolist');

elgg_load_library('elgg:videolist');

if (!$input['video_url']) {
	register_error(elgg_echo('videolist:error:no_url'));
	forward(REFERER);
}

$parsed_url = videolist_parseurl($input['video_url']);

if(!$parsed_url) {
	register_error(elgg_echo('videolist:error:invalid_url'));
	forward(REFERER);
}

if ($video_guid) {
	$video = get_entity($video_guid);
	if (!$video || !$video->canEdit()) {
		register_error(elgg_echo('videolist:error:no_save'));
		forward(REFERER);
	}
	$new_video = false;
} else {
	$video = new ElggObject();
	$video->subtype = 'videolist_item';
	$new_video = true;
}

$input = array_merge($input, videolist_get_data($parsed_url));

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$video->$name = $value;
	}
}

$video->container_guid = $container_guid;

if ($video->save()) {

	elgg_clear_sticky_form('videolist');

	system_message(elgg_echo('videolist:saved'));

	if ($new_video) {
		add_to_river('river/object/videolist_item/create', 'create', elgg_get_logged_in_user_guid(), $video->guid);
	}

	forward($video->getURL());
} else {
	register_error(elgg_echo('videolist:error:no_save'));
	forward(REFERER);
}
