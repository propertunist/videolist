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

// Render the video upload page
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the current page's owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($_SESSION['guid']);
}

$title = sprintf(elgg_echo("videolist:search"));

// Get objects
$area1 = elgg_view('page_elements/content_header', array('context' => "everyone", 'type' => 'videolist'));
set_input('show_viewtype', 'all');
$area2 .= elgg_list_entities(array('types' => 'object', 'subtypes' => 'videolist', 'limit' => 10, 'full_view' => TRUE, 'view_type_toggle' => FALSE, 'pagination' => TRUE));
// include a view for plugins to extend
$area3 .= elgg_view("videolist/sidebar", array("object_type" => 'videolist'));

// get the latest comments on all videos
$comments = get_annotations(0, "object", "videolist", "generic_comment", "", 0, 4, 0, "desc");
$area3 .= elgg_view('annotation/latest_comments', array('comments' => $comments));

set_context('videolist');
$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2, $area3);

// Finally draw the page
page_draw($title, $body);