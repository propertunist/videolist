<?php
/**
* Elgg Edit Video
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

gatekeeper();

$video_file = (int) get_input('video');
if ($video_file = get_entity($video_file)) {
	
	// Set the page owner
	$page_owner = page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) {
		$container_guid = $video_file->container_guid;
		if (!empty($container_guid))
			if ($page_owner = get_entity($container_guid)) {
				set_page_owner($container_guid->guid);
			}
		if (empty($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
	}
		
	if ($video_file->canEdit()) {
		// set up breadcrumbs
		elgg_push_breadcrumb(elgg_echo('videolist:all'), elgg_get_site_url()."videolist/all.php");
		elgg_push_breadcrumb(sprintf(elgg_echo("videolist:user"),$page_owner->name), elgg_get_site_url()."videolist/".$page_owner->username);
		elgg_push_breadcrumb(sprintf(elgg_echo("videolist:edit")));
		
		$area1 = elgg_view('navigation/breadcrumbs');
		$area1 .= elgg_view_title($title = elgg_echo('videolist:edit'));
		$area2 = elgg_view("forms/edit",array('entity' => $video_file));
		$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2, $area3);
		page_draw(elgg_echo("videolist:edit"), $body);
	}
} else {
	forward();
}

?>
