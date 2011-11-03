<?php
/**
 * All videos
 *
 * @package ElggVideolist
 */

elgg_push_breadcrumb(elgg_echo('videolist'));

elgg_register_title_button();

$limit = get_input("limit", 10);

$title = elgg_echo('videolist:all');

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'videolist',
	'limit' => $limit,
	'full_view' => FALSE
));

// get the latest comments on all videos
$comments = elgg_get_annotations(array(
	'type' => 'object',
	'subype' => 'videolist',
	'annotation_names' => array('generic_comment'),
	'limit' => 4,
	'order_by' => 'time_created desc',
));
$sidebar = elgg_view('annotation/latest_comments', array('comments' => $comments));

// tag-cloud display
$sidebar .= elgg_view_tagcloud(array(
	'type' => 'object',
	'subtype' => 'videolist',
	'limit' => 50,
));

elgg_set_context('videolist');
$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

// Finally draw the page
echo elgg_view_page($title, $body);
