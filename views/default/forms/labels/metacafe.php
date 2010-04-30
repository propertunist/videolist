<?php

/**
 * Elgg Video Plugin
 * This plugin allows users to create a library of youtube/vimeo/metacafe videos
 * @file - load metacafe label
 * @package Elgg
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */

$body = '<p class="margin_none"><label>'.elgg_echo("videolist:title_search_tube").'</label></p>';
$body .= "<div  class='search_videos clearfloat'>";
$body .= "<div style='float:left;'>";
$body .= "<a href=\"http://www.metacafe.com\"><img src='".$vars['url']."mod/videolist/graphics/metacafe.jpg' width='120'/></a>";
$body .= "</div>";
$body .= "<div style='float:left;width:45%;'>";
$body .= "<input type=\"text\" name=\"title_search\" value=\"\" id=\"title_search\" size=\"30\"/> &nbsp;&nbsp;";
if($error['no-search'] == 0) {
	$body .= '<div class="videolist_error">'.$error_msg['no-search'].'</div>';
}
$body .= "</div>";
$body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('videolist:searchTubeVideos:metacafe')));
$body .= "</div>";

print $body;