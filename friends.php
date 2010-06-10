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

// Start engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

global $CONFIG;
	
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($page_owner->getGUID());
}

// get the filter menu
$friend_link = $CONFIG->wwwroot . "pg/videolist/friends/" . $page_owner->username;
// get the filter menu
$area1 = elgg_view("page_elements/content_header", array('context' => "friends", 'type' => 'videolist', 'friend_link' => $friend_link));
	
// List videos
set_context('search');
$area2 .= list_user_friends_objects($page_owner->getGUID(), 'videolist', 10, false, false);
set_context('videolist');
		
// include a view for plugins to extend
$area3 = elgg_view("videolist/sidebar", array("object_type" => 'videolist'));

// fetch & display latest comments on friends videos
$comments = get_annotations(0, "object", "videolist", "generic_comment", "", 0, 4, 0, "desc");
$area3 .= elgg_view('annotation/latest_comments', array('comments' => $comments));

// tag-cloud display
$area3 .= display_tagcloud(0, 50, 'tags', 'object', 'videolist');
				
// Format page
$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2, $area3);
		
// Draw it
echo page_draw(elgg_echo('videolist:friends'),$body);
