<?php
/**
* Elgg videolist item delete
* 
* @package ElggVideolist
*/

$guid = (int) get_input('guid');
elgg_load_library('elgg:videolist');
$videolist_item = get_entity($guid);
if (!$videolist_item->guid) {
	register_error(elgg_echo("videolist:deletefailed"));
	forward('videolist/all');
}

if (!$videolist_item->canEdit()) {
	register_error(elgg_echo("videolist:deletefailed"));
	forward($videolist_item->getURL());
}

$container = $videolist_item->getContainerEntity();
$url = $videolist_item->getURL();

$thumbnail = "videolist/" . $videolist_item->guid . '.jpg';
$video_owner_guid = $videolist_item->getOwnerGUID();

if (!$videolist_item->delete()) {
	register_error(elgg_echo("videolist:deletefailed"));
} else {
        $result = videolist_remove_thumbnails($thumbnail, $video_owner_guid);
	system_message(elgg_echo("videolist:deleted"));
}

// we can't come back to video url because it's deleted
if($url != $_SERVER['HTTP_REFERER']) {
	forward(REFERER);
}

if (elgg_instanceof($container, 'group')) {
	forward("videolist/group/$container->guid/all");
} else {
	forward("videolist/owner/$container->username");
}

	forward("videolist/owner/$container->username");
}
