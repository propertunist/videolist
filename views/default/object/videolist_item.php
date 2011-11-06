<?php
/*****************************************************************************************
/* youtube video pluggin
/* @author : Prateek Choudhary <synapticfield@gmail.com>
/* YouTube/vimeo/metacafe video Object file
/* @copyright Prateek.Choudhary
/*****************************************************************************************/

$video_file = $vars['entity'];
$full_view = $vars['full_view'];

if(!$full_view) {
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
	$watch_URL = $vars['url']."videolist/watch/".$video_guid;
	
	$object_acl = get_readable_access_level($video_file->access_id);
	// metadata block, - access level, edit, delete, + options view extender
	$info = "<div class='entity_metadata'><span class='access_level'>" . $object_acl . "</span>";

	// view for plugins to extend	
	$info .= elgg_view('videolist/options', array('entity' => $video_file));
					
	// include edit and delete options
	if ($owner->canEdit()) {
		$info .= "<span class='entity_edit'><a href=\"{$vars['url']}mod/videolist/edit.php?video={$video_guid}\">" . elgg_echo('edit') . "</a></span>";
		$info .= "<span class='delete_button'>" . elgg_view('output/confirmlink',array('href' => $vars['url'] . "action/videolist/delete?video=" . $video_guid, 'text' => elgg_echo("delete"),'confirm' => elgg_echo("videolist:delete:confirm"),)). "</span>";  
	}
	$info .= "</div>";
	
	if(get_input('show_viewtype') == "all") {
		$info .= '<p class="entity_title"><a href="' .$watch_URL. '">'.$title.'</a></p>';
		$info .= "<p class='entity_subtext'><a href=\"{$vars['url']}videolist/owned/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
		$info .= "</p>";
		$icon = "<a class='video_icon' href=\"{$watch_URL}\">" . elgg_view("videolist/icon", array("mimetype" => $mime, 'thumbnail' => $thumbnail, 'video_guid' => $video_guid, 'size' => 'small')) . "</a>";
		echo "<div class='video_entity'>".elgg_view_listing($icon, $info)."</div>";
	} else {
		$info .= '<p class="entity_title"><a href="' .$watch_URL. '">'.$title.'</a></p>';
		$info .= "<p class='entity_subtext'><a href=\"{$vars['url']}videolist/owned/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
		$info .= "</p>";
		$icon = "<a class='video_icon' href=\"{$watch_URL}\">" . elgg_view("videolist/icon", array("mimetype" => $mime, 'thumbnail' => $thumbnail, 'video_guid' => $video_guid, 'size' => 'small')) . "</a>";
		echo "<div class='video_entity'>".elgg_view_listing($icon, $info)."</div>";
	}
} else {
	$videodiv = '';
	$width = "600";
	$height = "400";
	$file = $vars['entity'];
	
	$videos = get_entity($vars['entity']);
	$title = $videos->title;
	$url = $videos->url;
	$videoid = $videos->video_id;
	$tags = $videos->tags;
	
	$videodiv .= "<div class='video_view'>";
				 
	// display any tags for the Video
	if (!empty($tags)) {
		$videodiv .= "<p class='tags margin_none'>";
		$videodiv .= elgg_view('output/tags',array('value' => $tags));
		$videodiv .= "</p>";
	}
	
	if ($videos->videotype == "youtube") {
		$videodiv .= "<br /><object width=\"$width\" height=\"$height\"><param name=\"movie\" value=\"http://{$url}&hl=en&fs=1&showinfo=0&auoplay=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://{$url}&hl=en&fs=1&showinfo=0&autoplay=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"$width\" height=\"$height\" wmode=\"transparent\"></embed></object>";
	} else if($videos->videotype == "metacafe"){
		$videoid_id = $videoid;
		$path = explode("/", $videos->thumbnail);
		$path = array_reverse($path);
		$thumbnailArray = explode(".", $path[0]);
		$videoid = $videoid_id."/".$thumbnailArray[0].".swf";
		$videodiv .= "<br /><embed src=\"http://www.metacafe.com/fplayer/".$videoid."\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"$width\" height=\"$height\" wmode=\"transparent\" name=\"Metacafe_".$videoid_id."\"></embed>";
	} else if($videos->videotype == "vimeo") {
		$videodiv .= "<br /><object width=\"$width\" height=\"$height\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"wmode\" value=\"transparent\"></param><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id=".$videoid."&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id=".$videoid."&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"$width\" height=\"$height\" wmode=\"transparent\"></embed></object>";
	}

	$videodiv .= "</div>";
	$videodiv .= elgg_view_comments($videos);
	print $videodiv;
}
