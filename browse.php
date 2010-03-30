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

//get videolist GUID
$container_guid = get_input('container');
$parent_container = "";
if(isset($container_guid) && !empty($container_guid)) {
	$container_guid = explode(":", $container_guid);

	if ($container_guid[0] == "group") {
		$container = get_entity($container_guid[1]);
		set_page_owner($container->getGUID());
		$page_owner = page_owner_entity();
		set_context("groupsvideos");
	} else {
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
	}
}

elgg_push_breadcrumb(elgg_echo('videolist:find'), $CONFIG->wwwroot."mod/videolist/all.php");
elgg_push_breadcrumb(elgg_echo("videolist:browsemenu"));

$title = elgg_echo("videolist:browsemenu");

$area1 = elgg_view('navigation/breadcrumbs');
$area1 .= elgg_view_title($title);
$area2 .= elgg_view("forms/browsetube");

// get the latest comments on all videos
$comments = get_annotations(0, "object", "videolist", "generic_comment", "", 0, 4, 0, "desc");
$area3 = elgg_view('annotation/latest_comments', array('comments' => $comments));

$body = elgg_view_layout('one_column_with_sidebar', $area1.$area2, $area3);

page_draw($title, $body);