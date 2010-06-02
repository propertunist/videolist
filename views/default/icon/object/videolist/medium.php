<?php
/**
 * Display a video's icon.
 */

$entity = elgg_get_array_value('entity', $vars, NULL);
$size = 'medium';

if ($entity) {
	echo videolist_get_entity_icon_url($entity, $size);
}