<?php
/**
 * Display the latest videolist items
 *
 * Generally used in a sidebar.
 *
 * @uses $vars['container_guid'] The videolist container
 * @uses $vars['limit']          The number of comments to display
 */

$container_guid = elgg_extract('container_guid', $vars, ELGG_ENTITIES_ANY_VALUE);

$container = get_entity($container_guid);

$options = array(
	'container_guid' => $container_guid,
	'limit' => elgg_extract('limit', $vars, 6),
	'type' => 'object',
	'subtypes' => 'videolist_item',
	'full_view' => false,
	'pagination' => false,
);

if($container) {
	$title = elgg_echo('videolist:user', array($container->name));
} else {
	$title = elgg_echo('videolist');
}

elgg_push_context('gallery');
$content = elgg_list_entities($options);
elgg_pop_context('gallery');

echo elgg_view_module('aside', $title, $content);
