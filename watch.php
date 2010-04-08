<?php
/**
 * Elgg Video Plugin
 * This plugin allows users to create a library of youtube/vimeo/metacafe videos
 *
 * @package Elgg
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */
global $CONFIG;

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get objects
$video_id = (int) get_input('video_id');
$video = get_entity($video_id);

// If we can get out the video corresponding to video_id object ...
if ($videos = get_entity($video_id)) {
	set_page_owner($videos->container_guid);
	$videos_container = get_entity($videos->container_guid);
	// set up breadcrumbs
	$page_owner = page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) {
		$page_owner = $_SESSION['user'];
		set_page_owner($page_owner->getGUID());
	}
	elgg_push_breadcrumb(elgg_echo('videolist:all'), $CONFIG->wwwroot."mod/videolist/all.php");
	elgg_push_breadcrumb(sprintf(elgg_echo("videolist:user"),$page_owner->name), $CONFIG->wwwroot."pg/videolist/".$page_owner->username);
	elgg_push_breadcrumb(sprintf($video->title));
	$area1 = elgg_view('navigation/breadcrumbs');

	if($videos_container->type == "group") {
		set_context("groupsvideos");
	}
	$page_owner = page_owner_entity();
	$pagetitle = sprintf(elgg_echo("videolist:home"),page_owner_entity()->name);
	$title = $videos->title;
	
	$area1 .= "<div id='content_header' class='clearfloat'><div class='content_header_title'><h2>".$title."</h2></div>";
	if ($videos->canEdit()) {
		$area1 .= "<div class='content_header_options'>
					<a class='action_button' href=\"{$CONFIG->wwwroot}mod/videolist/edit.php?video={$videos->getGUID()}\">".elgg_echo('edit')."</a>";

		$area1 .= elgg_view('output/confirmlink',array(	
							'href' => $CONFIG->wwwroot . "action/videolist/delete?video=" . $videos->getGUID(),
							'text' => elgg_echo('delete'),
							'is_action' => true,
							'confirm' => elgg_echo('document:delete:confirm'),
							'class' => 'action_button disabled'))."</div>";  
	}
	$area1 .= "</div>";
	
	// Display it
	$area2 .= elgg_view("object/watch",array(
						'entity' => $video_id,
						'entity_owner' => $page_owner,
						'full' => true
						));
	$body = elgg_view_layout("one_column_with_sidebar", $area1.$area2, $area3);
} else {
		// video not found
		$body = "<p class='margin_top'>".elgg_echo('videolist:none:found')."</p>";
		$pagetitle = elgg_echo("video:none");
}

// Finally draw the page
page_draw($pagetitle, $body);
