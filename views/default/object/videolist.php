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
	if (get_input('search_viewtype') == "gallery") {
		$videodiv .= "<div class=\"filerepo_gallery_item\">";
		$videodiv .= "<div id='videobox'>";
		$videodiv .= $title."<br />";
		$videodiv .= "<a href='".$watch_URL."'>";
		$videodiv .= "<img src='".$thumbnail."' width='120' class='tubesearch'/>";
		$videodiv .= "</a>";

		$videodiv .= "</div>";
		//$videodiv .= "<div id='videoDescbox'>";
		//$videodiv .= "<span class='title'>".elgg_echo('videolist:videoTitle')." : </span>".$title."<br />";
		//$videodiv .= "</div>";

		$numcomments = elgg_count_comments($video_file);
		$videodiv .= "<div id='videoActionbox'>";

		if ($numcomments) {
			$videodiv .= "<a href=\"{$watch_URL}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a> <br />";
		}

		if($video_file->canEdit()) {
			$videodiv .=  elgg_view("output/confirmlink", array(
															'href' => $vars['url'] . "action/videolist/remove?video_id=" . $video_guid,
															'text' => elgg_echo('delete'),
															'confirm' => elgg_echo('deleteconfirm'),
														));
		}

		$videodiv .= "</div></div>";
		$videodiv .= "<div class=\"clearfloat\"></div>";
		print $videodiv;
	} else if(get_input('show_viewtype') == "all") {
		$info .= '<p><a href="' .$watch_URL. '">'.$title.'</a></p>';
		$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/profile/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
		$info .= "</p>";
		$icon = "<a href=\"{$watch_URL}\">" . elgg_view("videolist/icon", array("mimetype" => $mime, 'thumbnail' => $thumbnail, 'video_guid' => $video_guid, 'size' => 'small')) . "</a>";

		echo elgg_view_listing($icon, $info);
	} else {
		/*
		$videodiv .= "<a href='".$vars['url']."pg/videolist/watch/".$video_guid."'>";
		$videodiv .= "<img src='http://img.youtube.com/vi/".$video_id."/default.jpg' width='50' alt='unable to fetch image'/>";
		$videodiv .= "</a> &nbsp;&nbsp;<a href='".$vars['url']."pg/videolist/watch/".$video_guid."'><span class='title'>Title : </span>".$title;
		$videodiv .= "</a><br />";
		*/
		//video list-entity view
		$info = '<p><a href="' .$watch_URL. '">'.$title.'</a></p>';
		$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/profile/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
		$info .= "</p>";
		$icon = "<a href=\"{$watch_URL}\">" . elgg_view("videolist/icon", array("mimetype" => $mime, 'thumbnail' => $thumbnail, 'video_guid' => $video_guid, 'size' => 'small')) . "</a>";

		echo elgg_view_listing($icon, $info);
	}
} else {
	echo "No videos were found.";
}