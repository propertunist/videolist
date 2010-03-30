<?php
/**
 * Elgg Video Plugin
 * This plugin allows users to create a library of videos
 *
 * @package Elgg
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */
function videolist_init() {
	global $CONFIG;

	if (isloggedin()) {
		add_menu(elgg_echo('videolist'), $CONFIG->wwwroot . "mod/videolist/all.php");
	}

	// Extend system CSS with our own styles
	elgg_extend_view('css','videolist/css');

	// Load the language file - default is english
	register_translations($CONFIG->pluginspath . "videolist/languages/");

	// Register a page handler, so we can have nice URLs
	register_page_handler('videolist','videolist_page_handler');

	//extend this plugin for groups
	elgg_extend_view('groups/left_column','videolist/groupprofile_videolist');

	if (is_callable('register_notification_object')) {
		register_notification_object('object', 'videolist', elgg_echo('videolist:new'));
	}

	register_plugin_hook('object:notifications','object','videolist_object_notifications_intercept');

	// Register URL handler
	register_entity_url_handler('video_url','object', 'videolist');
	register_entity_url_handler('video_url','object', 'watch');

	//register entity url handler
	register_entity_url_handler('videolist_url','object','videolist');

	// Register entity type
	register_entity_type('object','videolist');
}

/**
 * videolist page handler; allows the use of fancy URLs
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function videolist_page_handler($page) {
	if (isset($page[0])) {
		switch($page[0]) {
			case "owned": if (isset($page[1])) set_input('username',$page[1]);
								@include(dirname(__FILE__) . "/index.php");
								break;
			case "friends":     @include(dirname(__FILE__) . "/friends.php");
								break;
			case "search":		@include(dirname(__FILE__) . "/all.php");
								break;
			case "video":		@include(dirname(__FILE__) . "/video.php");
								break;
			case "new": if (isset($page[3])) set_input('add_videourl',$page[3]);
									if (isset($page[5])) set_input('page',$page[5]);
									if (isset($page[1])) set_input('container',$page[1]);
								@include(dirname(__FILE__) . "/new.php");
								break;
			case "watch":	set_input('video_id',$page[1]);
								@include(dirname(__FILE__) . "/watch.php");
								break;
			case "browse":	if (isset($page[1])) set_input('container',$page[1]);
								@include(dirname(__FILE__) . "/browse.php");
								break;
		default : if (isset($page[1])) set_input('username',$page[1]);
								@include(dirname(__FILE__) . "/index.php");
								break;
		}
	// If the URL is just 'videolist/username', or just 'videolist/', load the standard index file
	} else {
		if (isset($page[1])) {
			set_input('username',$page[1]);
		}

		include(dirname(__FILE__) . "/index.php");
		return true;
	}

	return false;
}


function videolist_pagesetup() {
	global $CONFIG;
	$page_owner = page_owner_entity();

	if ($page_owner instanceof ElggGroup && get_context() == "groups") {
		add_submenu_item(sprintf(elgg_echo("videolist:home"), page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/owned/" . page_owner_entity()->username);
	} else if (get_context() == "videolist") {
		/**********************************************************************************************
		****if user is OR is not registered user then show him following page menus to choose from
		***********************************************************************************************/
		/*
		add_submenu_item(elgg_echo('videolist:home'),$CONFIG->wwwroot."pg/videolist/". $page_owner->username);

		add_submenu_item(elgg_echo('videolist:new'),$CONFIG->wwwroot."pg/videolist/new");

		add_submenu_item(elgg_echo('videolist:find'),$CONFIG->wwwroot."pg/videolist/search/");
		*/
		if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
			//add_submenu_item(sprintf(elgg_echo("videolist:home"),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/owned/" . page_owner_entity()->username);
			add_submenu_item(sprintf(elgg_echo('videolist:new'),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/new/". page_owner_entity()->username);
			add_submenu_item(sprintf(elgg_echo('videolist:browsemenu'),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/browse/". page_owner_entity()->username);
			//add_submenu_item(sprintf(elgg_echo('videolist:find'),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/search/");
		} else if (page_owner() && $page_owner instanceof ElggUser) {
			add_submenu_item(sprintf(elgg_echo("videolist:home"),$page_owner->name), $CONFIG->wwwroot . "pg/videolist/owned/". $page_owner->username);
		}
	} else if (get_context() == "groupsvideos") {
		add_submenu_item(sprintf(elgg_echo("videolist:home"),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/owned/" . page_owner_entity()->username);
		if ($page_owner->canEdit()) {
			add_submenu_item(sprintf(elgg_echo('videolist:browsemenu'),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/browse/". page_owner_entity()->username);
			add_submenu_item(sprintf(elgg_echo('videolist:new'),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/new/". page_owner_entity()->username);
		}
	}
}

function video_url($entity) {
	global $CONFIG;
	$video_id = $entity->video_id;
	return $CONFIG->url . "pg/videolist/watch/" . $entity->getGUID() . "/" . $video_id;
}

function videolist_url($videolistpage) {
	global $CONFIG;

	$owner = $videolistpage->container_guid;
	$userdata = get_entity($owner);
	$title = $videolistpage->title;
	$title = friendly_title($title);
	return $CONFIG->url . "pg/videolist/watch/" . $videolistpage->getGUID();
}

/**
 * Event handler for videolist
 *
 */
function videolist_object_notifications($event, $object_type, $object) {
	static $flag;
	if (!isset($flag)) {
		$flag = 0;
	}

	if (is_callable('object_notifications')) {
		if ($object instanceof ElggObject) {
			if ($object->getSubtype() == 'videolist') {
				if ($flag == 0) {
					$flag = 1;
					object_notifications($event, $object_type, $object);
				}
			}
		}
	}
}

/**
 * Intercepts the notification on an event of new video being created and prevents a notification from going out
 * (because one will be sent on the annotation)
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 * @return unknown
 */
function videolist_object_notifications_intercept($hook, $entity_type, $returnvalue, $params) {
	if (isset($params)) {
		if ($params['event'] == 'create' && $params['object'] instanceof ElggObject) {
			if ($params['object']->getSubtype() == 'videolist') {
				return true;
			}
		}
	}
	return null;
}

// Register a handler for adding videos
register_elgg_event_handler('create', 'videolist', 'videolist_create_event_listener');

// Register a handler for delete videos
register_elgg_event_handler('delete', 'videolist', 'videolist_delete_event_listener');

// Make sure the status initialisation function is called on initialisation
register_elgg_event_handler('init','system','videolist_init');

register_elgg_event_handler('pagesetup','system','videolist_pagesetup');
register_elgg_event_handler('annotate','all','videolist_object_notifications');

// Register actions
global $CONFIG;

register_action("videolist/add", false, $CONFIG->pluginspath . "videolist/actions/add.php");
register_action("videolist/tubesearch", false, $CONFIG->pluginspath . "videolist/actions/tubesearch.php");
register_action("videolist/remove", false, $CONFIG->pluginspath . "videolist/actions/delete.php");