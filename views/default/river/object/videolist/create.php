<?php

$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
$object = get_entity($vars['item']->object_guid);
$thumbnail = $object->thumbnail;
$is_group = get_entity($object->container_guid);

$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$title = $object->title;

if(!$title) {
	$title = "untitled";
}

$string = sprintf(elgg_echo("videolist:river:created"),$url) . " ";
$string .= elgg_echo("videolist:river:item") . " titled <a href=\"" . $object->getURL() . "\">" . $title . "</a>";
//if the video was added to a group, show that unless displayed on the group profile
if(($is_group instanceof ElggGroup) && (get_context() != 'groups')){
	$string .= " " . elgg_echo('videolist:ingroup') . " " . $is_group->name;
}
$string .= "<span class='entity_subtext'>" . friendly_time($object->time_created) . "</span>";
if (isloggedin()){
	$string .= "<a class='river_comment_form_button link'>Comment</a>";
	$string .= elgg_view('likes/forms/link', array('entity' => $object));
}
$string .= "<div class=\"river_content_display\">";
$string .= "<a href=\"" . $object->getURL() . "\"><img src='".$thumbnail."' width='120' class='tubesearch'/></a>";
$string .= "</div>";

echo $string;