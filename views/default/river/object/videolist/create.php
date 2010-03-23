<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$thumbnail = $object->thumbnail;
	//$url = $object->getURL();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$title = $object->title;
	if(!$title)
		$title = "untitled";
	$string = sprintf(elgg_echo("videolist:river:created"),$url) . " ";
	$string .= elgg_echo("videolist:river:item") . " titled <a href=\"" . $object->getURL() . "\">" . $title . "</a>";
	$string .= "<div class=\"river_content_display\">";
	$string .= "<a href=\"" . $object->getURL() . "\"><img src='".$thumbnail."' width='120' class='tubesearch'/></a>";
	$string .= "</div>";

	echo $string;