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

	add_menu(elgg_echo('videolist'), $CONFIG->wwwroot . "mod/videolist/all.php");

	// Extend system CSS with our own styles
	elgg_extend_view('css','videolist/css');

	// Load the language file - default is english
	register_translations($CONFIG->pluginspath . "videolist/languages/");

	// Register a page handler, so we can have nice URLs
	register_page_handler('videolist','videolist_page_handler');

	//extend this plugin for groups
	elgg_extend_view('groups/tool_latest','videolist/groupprofile_videolist');

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

	register_plugin_hook('profile_menu', 'profile', 'videolist_profile_menu');

	// register for embed
	register_plugin_hook('embed_get_sections', 'all', 'videolist_embed_get_sections');
	register_plugin_hook('embed_get_items', 'videolist', 'videolist_embed_get_items');

	// override icons for ElggEntity::getIcon()
	register_plugin_hook('entity:icon:url', 'user', 'profile_usericon_hook');
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
		//add_submenu_item(sprintf(elgg_echo("videolist:group"), page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/owned/" . page_owner_entity()->username);
	} else if (get_context() == "videolist") {
		/**********************************************************************************************
		****if user is OR is not registered user then show him following page menus to choose from
		***********************************************************************************************/
		/*
		add_submenu_item(elgg_echo('videolist:home'),$CONFIG->wwwroot."pg/videolist/". $page_owner->username);

		add_submenu_item(elgg_echo('videolist:new'),$CONFIG->wwwroot."pg/videolist/new");

		add_submenu_item(elgg_echo('videolist:find'),$CONFIG->wwwroot."pg/videolist/search/");
		*/
	} else if (get_context() == "group") {
		//add_submenu_item(sprintf(elgg_echo("videolist:home"),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/owned/" . page_owner_entity()->username);
		if ($page_owner->canEdit()) {
			//add_submenu_item(sprintf(elgg_echo('videolist:browsemenu'),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/browse/". page_owner_entity()->username);
			//add_submenu_item(sprintf(elgg_echo('videolist:new'),page_owner_entity()->name), $CONFIG->wwwroot . "pg/videolist/new/". page_owner_entity()->username);
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

function videolist_profile_menu($hook, $entity_type, $return_value, $params) {
	global $CONFIG;

	$return_value[] = array(
		'text' => elgg_echo('videolist'),
		'href' => "{$CONFIG->url}pg/videolist/owned/{$params['owner']->username}",
	);

	return $return_value;
}


/**
 * Register videolist as an embed type.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function videolist_embed_get_sections($hook, $type, $value, $params) {
	$value['videolist'] = array(
		'name' => elgg_echo('videolist'),
		'layout' => 'gallery',
	);

	return $value;
}

/**
 * Return a list of videos for embedding
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 */
function videolist_embed_get_items($hook, $type, $value, $params) {
	$options = array(
		'owner_guid' => get_loggedin_userid(),
		'type_subtype_pair' => array('object' => 'videolist'),
		'count' => TRUE
	);

	$count = elgg_get_entities($options);
	$value['count'] += $count;

	unset($options['count']);
	$options['offset'] = $params['offset'];
	$options['limit'] = $params['limit'];

	$items = elgg_get_entities($options);

	$value['items'] = array_merge($items, $value['items']);

	return $value;
}

/**
 * Returns the URL of the icon for $entity at $size.
 *
 * @param ElggEntity $entity
 * @param string $size Not used yet.  Not sure if possible.
 */
function videolist_get_entity_icon_url(ElggEntity $entity, $size = 'medium') {

	// tiny thumbnails are too small to be useful, so give a generic video icon
	if ($size == 'tiny') {
		global $CONFIG;
		return "{$CONFIG->url}mod/videolist/graphics/video_icon_tiny.png";
	}

	return $entity->thumbnail;
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
register_action("videolist/edit", false, $CONFIG->pluginspath . "videolist/actions/edit.php");
register_action("videolist/tubesearch", false, $CONFIG->pluginspath . "videolist/actions/tubesearch.php");
register_action("videolist/delete", false, $CONFIG->pluginspath . "videolist/actions/delete.php");