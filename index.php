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

$owner = page_owner_entity();

//get videolist GUID
$container_guid = get_input('username');
if(isset($container_guid) && !empty($container_guid)) {
	$container_guid = explode(":", $container_guid);

	if ($container_guid[0] == "group") {
		$container = get_entity($container_guid[1]);
		set_context("groupsvideos");
		//$page_owner = page_owner_entity();
	}
}
//set page owner
//set_page_owner($videolist_guid);

$title = sprintf(elgg_echo("videolist:home"), "$owner->name");

// Get objects
$area2 = elgg_view_title($title);
$area2 .= elgg_list_entities(array('types' => 'object', 'subtypes' => 'videolist', 'container_guids' => page_owner(), 'limit' => 10));

//$area2 .= elgg_view("staticvideo/index");

set_context('videolist');
$body = elgg_view_layout('one_column_with_sidebar',$area1. $area2);

// Finally draw the page
page_draw($title, $body);