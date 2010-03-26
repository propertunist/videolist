<?php
/**
 * Elgg Candidate Profile Plugin - file search.php
 * This plugin allows users to create custom candidate profile
 *
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */


$owner = $_SESSION['guid'];
$number = 10;
$owner_videos = get_entities("object", "videolist", $_SESSION['guid'], $number);
$videodiv = '';
$width = "390";
$height = "275";
?>
<div id="video-list-main">
<?php
if(!empty($owner_videos)) {
	foreach($owner_videos as $node) {
		$url = $node->url;
		$title = $node->title;
		$video_guid = $node->guid;
		$video_id = $node->video_id;
		$videodiv .= "<div id='videobox'>";
		$videodiv .= "<a href='".$vars['url']."pg/videolist/watch/".$video_guid."'>";
		$videodiv .= "<img src='http://img.youtube.com/vi/".$video_id."/default.jpg' width='150' alt='no video'/>";
		$videodiv .= "</a>";

		$videodiv .= "</div>";
		$videodiv .= "<div id='videoDescbox'>";
		$videodiv .= "<span class='title'>Title : </span>".$title."<br />";
		$videodiv .= "</div>";

		$videodiv .= "<div id='videoActionbox'>";
		$videodiv .=  elgg_view("output/confirmlink", array(
																	'href' => $vars['url'] . "action/videolist/remove?video_id=" . $video_guid,
																	'text' => elgg_echo('delete'),
																	'confirm' => elgg_echo('deleteconfirm'),
																));
		/*
		$videodiv .= "<a href='".$vars['url']."pg/videolist/remove/".$video_id."'>";
		$videodiv .= "delete";
		$videodiv .= "</a>";
		*/
		$videodiv .= "</div>";
		$videodiv .= "<div class=\"clearfloat\"></div>";
	}

	print $videodiv;
} else {
	echo "No videos were found.";
}
?>
</div>
