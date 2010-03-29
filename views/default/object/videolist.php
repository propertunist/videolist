<?php
/*****************************************************************************************
/* youtube video pluggin
/* @author : Prateek Choudhary <synapticfield@gmail.com>
/* YouTube/vimeo/metacafe video Object file
/* @copyright Prateek.Choudhary
/*****************************************************************************************/

global $CONFIG;

$video_file = $vars['entity'];

if(!empty($video_file)) {
	$url = $video_file->url;
	$title = $video_file->title;
	$video_guid = $video_file->guid;
	$video_id = $video_file->video_id;
	$videotype = $video_file->videotype;
	$videothumbnail = $video_file->thumbnail;
	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = friendly_time($vars['entity']->time_created);

	$mime = "image/html";
	$thumbnail = $videothumbnail;
	$watch_URL = $vars['url']."pg/videolist/watch/".$video_guid;
	
	$object_acl = get_readable_access_level($video_file->access_id);
	// metadata block, - access level, edit, delete, + options view extender
	$info = "<div class='entity_metadata'><span class='access_level'>" . $object_acl . "</span>";

	// view for plugins to extend	
	$info .= elgg_view('videolist/options', array('entity' => $video_file));
					
	// include edit and delete options
	if ($owner->canEdit()) {
		$info .= "<span class='entity_edit'><a href=\"{$vars['url']}mod/videolist/edit.php?file_guid={$video_guid}\">" . elgg_echo('edit') . "</a></span>";
		$info .= "<span class='delete_button'>" . elgg_view('output/confirmlink',array('href' => $vars['url'] . "action/videolist/delete?file=" . $video_guid, 'text' => elgg_echo("delete"),'confirm' => elgg_echo("videolist:delete:confirm"),)). "</span>";  
	}
	$info .= "</div>";
	
	if(get_input('show_viewtype') == "all") {
		$info .= '<p class="entity_title"><a href="' .$watch_URL. '">'.$title.'</a></p>';
		$info .= "<p class='entity_subtext'><a href=\"{$vars['url']}pg/profile/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
		$info .= "</p>";
		$icon = "<a class='video_icon' href=\"{$watch_URL}\">" . elgg_view("videolist/icon", array("mimetype" => $mime, 'thumbnail' => $thumbnail, 'video_guid' => $video_guid, 'size' => 'small')) . "</a>";
		echo "<div class='video_entity'>".elgg_view_listing($icon, $info)."</div>";
	} else {
		$info .= '<p class="entity_title"><a href="' .$watch_URL. '">'.$title.'</a></p>';
		$info .= "<p class='entity_subtext'><a href=\"{$vars['url']}pg/profile/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
		$info .= "</p>";
		$icon = "<a class='video_icon' href=\"{$watch_URL}\">" . elgg_view("videolist/icon", array("mimetype" => $mime, 'thumbnail' => $thumbnail, 'video_guid' => $video_guid, 'size' => 'small')) . "</a>";
		echo "<div class='video_entity'>".elgg_view_listing($icon, $info)."</div>";
	}
} else {
	echo "<p class='margin_top'>".elgg_echo('videolist:none:found')."</p>";
}