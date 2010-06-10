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

if (is_callable('group_gatekeeper')) group_gatekeeper();

$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($page_owner->getGUID());
}

//get videolist GUID
$container_guid = get_input('username');
if(isset($container_guid) && !empty($container_guid)) {
	$container_guid = explode(":", $container_guid);

	if ($container_guid[0] == "group") {
		$container = get_entity($container_guid[1]);
		set_context("groupsvideos");
	}
}

elgg_push_breadcrumb(elgg_echo('videolist:find'), $CONFIG->wwwroot."mod/videolist/all.php");
elgg_push_breadcrumb(sprintf(elgg_echo("videolist:home"),$page_owner->name));
$title = sprintf(elgg_echo("videolist:home"), "$owner->name");

//set videolist header
if(page_owner() == get_loggedin_userid()) {
	// get the filter menu
	$friend_link = $CONFIG->wwwroot . "pg/videolist/friends/" . $page_owner->username;
	$area1 .= elgg_view('page_elements/content_header', array('context' => "mine", 'type' => 'videolist', 'friend_link' => $friend_link));
}elseif(page_owner_entity() instanceof ElggGroup){
	$area1 .= elgg_view('navigation/breadcrumbs');	
	$area1 .= elgg_view('videolist/group_video_header');
} else {
	$area1 .= elgg_view('navigation/breadcrumbs');
	$area1 .= elgg_view('page_elements/content_header_member', array('type' => 'videolist'));
}


// include a view for plugins to extend
$area3 = elgg_view("videolist/sidebar", array("object_type" => 'videolist'));

// get the latest comments on all videos
$comments = get_annotations(0, "object", "videolist", "generic_comment", "", 0, 4, 0, "desc");
$area3 .= elgg_view('annotation/latest_comments', array('comments' => $comments));

// tag-cloud display
$area3 .= display_tagcloud(0, 50, 'tags', 'object', 'videolist');

// Get objects
$area2 = elgg_list_entities(array('types' => 'object', 'subtypes' => 'videolist', 'container_guids' => page_owner(), 'limit' => 10));

set_context('videolist');
$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2, $area3);

// Finally draw the page
page_draw($title, $body);