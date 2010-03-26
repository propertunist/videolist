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
		gatekeeper();
		
	//get videolist GUID
	$container_guid = get_input('container');
  if(isset($container_guid) && !empty($container_guid)){
		$container_guid = explode(":", $container_guid);
		if($container_guid[0] == "group"){
			$container = get_entity($container_guid[1]);
			set_page_owner($container->getGUID());
			$page_owner = page_owner_entity();
			set_context("groupsvideos");
			set_input("container_guid", $container->getGUID());
		}
		else{
			// Get the current page's owner	
			$page_owner = page_owner_entity();
			if ($page_owner === false || is_null($page_owner)) {
				$page_owner = $_SESSION['user'];
				set_page_owner($_SESSION['guid']);
				set_input("container_guid", $_SESSION['guid']);
			}
		}
	}

	$title = sprintf(elgg_echo("videolist:new"), $page_owner->name);
	
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view("forms/add");
	$body = elgg_view_layout('one_column_with_sidebar', $area1 . $area2);
	
	page_draw($title, $body);
?>
