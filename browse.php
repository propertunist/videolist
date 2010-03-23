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
	
		//get videolist GUID
	$container_guid = get_input('container');
	$parent_container = "";
	if(isset($container_guid) && !empty($container_guid)){
		$container_guid = explode(":", $container_guid);
		if($container_guid[0] == "group"){
			$container = get_entity($container_guid[1]);
			set_page_owner($container->getGUID());
			$page_owner = page_owner_entity();
			set_context("groupsvideos");
		}
		else{
			$page_owner = page_owner_entity();
			if ($page_owner === false || is_null($page_owner)) {
				$page_owner = $_SESSION['user'];
				set_page_owner($_SESSION['guid']);
			}
		}
	}
	// Get the current page's owner	
	
	
	$title = sprintf(elgg_echo("videolist:browse"), $page_owner->name);
	
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view("forms/browsetube");
	
	$body = elgg_view_layout('one_column_with_sidebar', $area1 . $area2);
	
	page_draw($title, $body);
?>
