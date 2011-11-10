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

elgg_register_event_handler('init', 'system', 'videolist_init');

function videolist_init() {
	
	elgg_register_library('elgg:videolist', elgg_get_plugins_path() . 'videolist/lib/videolist.php');

	// add a site navigation item
	$item = new ElggMenuItem('videolist', elgg_echo('videolist'), 'videolist/all');
	elgg_register_menu_item('site', $item);

	// Extend system CSS with our own styles
	elgg_extend_view('css','videolist/css');

	// Load the language file - default is english
	register_translations(elgg_get_plugins_path() . "videolist/languages/");

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('videolist', 'videolist_page_handler');
	
	// Language short codes must be of the form "videolist:key"
	// where key is the array key below
	elgg_set_config('videolist', array(
		'video_url' => 'url',
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'access_id' => 'access',
	));

	// extend group main page
	elgg_extend_view('groups/tool_latest', 'videolist/group_module');

	if (is_callable('register_notification_object')) {
		register_notification_object('object', 'videolist', elgg_echo('videolist:new'));
	}
	
	// Register a handler for adding videos
	elgg_register_event_handler('create', 'videolist', 'videolist_create_event_listener');

	// Register a handler for delete videos
	elgg_register_event_handler('delete', 'videolist', 'videolist_delete_event_listener');
	
	// Register entity type for search
	elgg_register_entity_type('object', 'videolist_item');

	// add a file link to owner blocks
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'videolist_owner_block_menu');
	elgg_register_event_handler('annotate','all','videolist_object_notifications');

	elgg_register_plugin_hook_handler('object:notifications','object','videolist_object_notifications_intercept');

	//register entity url handler
	elgg_register_entity_url_handler('object', 'videolist_item', 'videolist_url');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'videolist_icon_url_override');

	// Register entity type
	elgg_register_entity_type('object','videolist');

	// register for embed
	elgg_register_plugin_hook_handler('embed_get_sections', 'all', 'videolist_embed_get_sections');
	elgg_register_plugin_hook_handler('embed_get_items', 'videolist', 'videolist_embed_get_items');
	
	// Register actions
	$actions_path = elgg_get_plugins_path() . "videolist/actions/videolist";
	elgg_register_action("videolist/add", "$actions_path/add.php");
	elgg_register_action("videolist/edit", "$actions_path/edit.php");
	elgg_register_action("videolist/tubesearch", "$actions_path/tubesearch.php");
	elgg_register_action("videolist/delete", "$actions_path/delete.php");
}

/**
 * Dispatches blog pages.
 * URLs take the form of
 *  All videos:       videolist/all
 *  User's videos:    videolist/owner/<username>
 *  Friends' videos:  videolist/friends/<username>
 *  Video watch:      videolist/watch/<guid>/<title>
 *  Video browse:     videolist/browse
 *  New video:        videolist/add/<guid>
 *  Edit video:       videolist/edit/<guid>
 *  Group videos:     videolist/group/<guid>/all
 *
 * Title is ignored
 *
 * @param array $page
 * @return NULL
 */
function videolist_page_handler($page) {
	
	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$videolist_dir = elgg_get_plugins_path() . 'videolist/pages/videolist';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			include "$videolist_dir/owner.php";
			break;
		case 'friends':
			include "$videolist_dir/friends.php";
			break;
		case 'watch':
			set_input('guid', $page[1]);
			include "$videolist_dir/watch.php";
			break;
		case 'add':
			include "$videolist_dir/add.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$videolist_dir/edit.php";
			break;
		case 'browse':
			include "$videolist_dir/browse.php";
			break;
		case 'group':
			include "$videolist_dir/owner.php";
			break;
		case 'all':
		default:
			include "$videolist_dir/all.php";
			break;
	}
}

/**
 * Add a menu item to the user ownerblock
 */
function videolist_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "videolist/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('videolist', elgg_echo('videolist'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->videolist_enable != "no") {
			$url = "videolist/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('videolist', elgg_echo('videolist:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

function video_url($entity) {
	$video_id = $entity->video_id;
	return elgg_get_site_url() . "videolist/watch/" . $entity->getGUID() . "/" . $video_id;
}

function videolist_url($videolist_item) {
	$guid = $videolist_item->guid;
	$title = elgg_get_friendly_title($videolist_item->title);
	return elgg_get_site_url() . "videolist/watch/$guid/$title";
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
		'layout' => 'list',
		'icon_size' => 'medium',
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
 * Override the default entity icon for videoslist items
 *
 * @return string Relative URL
 */
function videolist_icon_url_override($hook, $type, $returnvalue, $params) {
	$videolist_item = $params['entity'];
	$size = $params['size'];
	
	if($videolist_item->getSubtype() != 'videolist_item'){
		return $returnvalue;
	}
	
	// tiny thumbnails are too small to be useful, so give a generic video icon
	if ($size != 'tiny' && isset($videolist_item->thumbnail)) {
		return $videolist_item->thumbnail;
	}

	if (in_array($size, array('tiny', 'small', 'medium'))){
		return "mod/videolist/graphics/videolist_icon_{$size}.png";
	}
}
