<?php
/**
 * Elgg Video Plugin
 * This plugin allows users to create a library of videos for groups
 *
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */

?>
<div id="filerepo_widget_layout">
<h2><?php echo elgg_echo("videolist:group"); ?></h2>

<?php

//the number of files to display
$number = (int) $vars['entity']->num_display;
if (!$number)
	$number = 5;

//get the user's files
$videos = get_user_objects($vars['entity']->guid, "videolist", $number, 0);

//if there are some files, go get them
if ($videos) {

	//display in list mode
	echo "<div class=\"filerepo_widget_singleitem\" style=\"width:310px;padding:5px;\">";

	foreach($videos as $f){
		$mime = $f->mimetype;
		$owner = get_entity($f->getOwner());
		$numcomments = elgg_count_comments($f);
		echo "<div class=\"filerepo_listview_icon\" style=\"float:left;width:90px;padding:8px 0 0 0;\"><a href=\"{$vars['url']}pg/videolist/watch/{$f->guid}\"><img src=\"".$f->thumbnail."\" border=\"0\" width=\"85\" /></a></div>";
		echo "<div class=\"filerepo_widget_content\" style=\"width:210px;margin-left:100px;\">";
		echo "<div class=\"filerepo_listview_title\"><p class=\"filerepo_title\" style=\"font-weight:normal;font-size:12px;\"><a href=\"{$vars['url']}pg/videolist/watch/{$f->guid}\">" . $f->title ."</a></p><br />by <a href=\"{$vars['url']}pg/profile/{$owner->username}\">{$owner->name}</a>";
		if ($numcomments) {
			echo "<br /><a href=\"{$vars['url']}pg/videolist/watch/{$f->guid}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a>";
			echo "</div>";
		}
		echo "<div class=\"filerepo_listview_date\"><p class=\"filerepo_timestamp\"><small>" . friendly_time($f->time_created) . "</small></p></div>";
		echo "</div><div class=\"clearfloat\" style=\"height:8px;\"></div>";

	}
	echo "</div>";

	//get a link to the users files
	$users_file_url = $vars['url'] . "pg/videolist/owned/" . page_owner_entity()->username;

	echo "<div class=\"forum_latest\"><a href=\"{$users_file_url}\">" . elgg_echo("videolist:groupall") . "</a></div>";

} else {
	echo "<div class=\"forum_latest\">" . elgg_echo("videolist:none") . "</div>";
}

?>
<div class="clearfloat" /></div>
</div>
