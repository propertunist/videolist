<?php
/**
 * Elgg Videolist Plugin -
 * This plugin allows users to delete videos
 *
 * @package Elgg
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */
// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get the current page's owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($_SESSION['guid']);
}

// Get input data
$guid = (int) get_input('video_id');

// Make sure we actually have permission to edit
$videos = get_entity($guid);
if ($videos->getSubtype() == "videolist" && $videos->canEdit()) {
	// Get owning user
	$owner = get_entity($videos->getOwner());

	// Delete it!
	$rowsaffected = $videos->delete();
	if ($rowsaffected > 0) {
		// Success message
		system_message(elgg_echo("videos:deleted"));
	} else {
		register_error(elgg_echo("videos:notdeleted"));
	}
	// Forward to the main video list page
	//forward("pg/videolist/owned/" . page_owner_entity()->username);
	forward($_SERVER['HTTP_REFERER']);
}