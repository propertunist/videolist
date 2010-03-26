<?php
   /**
	 * Elgg Videolist Plugin -
	 * This plugin allows users to watch videos 
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Prateek Choudhary <synapticfield@gmail.com>
	 * @copyright Prateek Choudhary
	 */
$videodiv = '';
$width = "600";
$height = "400";
$file = $vars['entity'];
if(isset($vars['entity']))
{

$videos = get_entity($vars['entity']);
$title = $videos->title;
$url = $videos->url;
$videoid = $videos->video_id;
$videodiv = "<h2>".$title."</h2>";
if($videos->videotype == "youtube"){
	$videodiv .= "<div align=\"center\" style=\"margin-top:20px;\"><object width=\"$width\" height=\"$height\"><param name=\"movie\" value=\"http://{$url}&hl=en&fs=1&showinfo=0&auoplay=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://{$url}&hl=en&fs=1&showinfo=0&autoplay=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"$width\" height=\"$height\"></embed></object>";
}
else if($videos->videotype == "metacafe"){
	$videoid_id = $videoid;
	$path = explode("/", $videos->thumbnail);
	$path = array_reverse($path);
	$thumbnailArray = explode(".", $path[0]);
	$videoid = $videoid_id."/".$thumbnailArray[0].".swf";
	$videodiv .= "<div align=\"center\" style=\"margin-top:20px;\"><embed src=\"http://www.metacafe.com/fplayer/".$videoid."\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"$width\" height=\"$height\" name=\"Metacafe_".$videoid_id."\"></embed>";

}
else if($videos->videotype == "vimeo"){
	$videodiv .= "<div align=\"center\" style=\"margin-top:20px;\"><object width=\"$width\" height=\"$height\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id=".$videoid."&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id=".$videoid."&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"$width\" height=\"$height\"></embed></object>";
}
$videodiv .= "</div>";
$videodiv .= elgg_view_likes($videos);
$videodiv .= elgg_view_comments($videos);
print $videodiv;

//echo elgg_view_comments($videos);
}
?>
