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

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get objects
$video_id = (int) get_input('video_id');

// If we can get out the video corresponding to video_id object ...
if ($videos = get_entity($video_id)) {
	set_page_owner($videos->container_guid);
	$videos_container = get_entity($videos->container_guid);

	if($videos_container->type == "group") {
		set_context("groupsvideos");
	}
	$page_owner = page_owner_entity();
	$title = sprintf(elgg_echo("videolist:home"),page_owner_entity()->name);
	// Display it
	$area2 = elgg_view("object/watch",array(
									'entity' => $video_id,
									'entity_owner' => $page_owner,
									'full' => true
									));
	$body = elgg_view_layout("one_column_with_sidebar", $area1.$area2, $area3);
} else {
		// video not found
		$body = "<p class='margin_top'>".elgg_echo('videolist:none:found')."</p>";
		$title = elgg_echo("video:none");
}

// Finally draw the page
page_draw($title, $body);
