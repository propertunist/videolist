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

// Get input data
$guid = (int) get_input('video');

// Make sure we actually have permission to edit
$video = get_entity($guid);
if ($video->getSubtype() == "videolist" && $video->canEdit()) {
	// Get owning user
	$owner = get_entity($video->getOwner());

	// Delete it!
	$rowsaffected = $video->delete();
	if ($rowsaffected > 0) {
		// Success message
		system_message(elgg_echo("videos:deleted"));
	} else {
		register_error(elgg_echo("videos:notdeleted"));
	}
	// Forward to the main video list page
	forward($_SERVER['HTTP_REFERER']);
}