<?php

/**
 * Elgg Video Plugin
 * This plugin allows users to create a library of youtube/vimeo/metacafe videos
 * @file - load youtube label
 * @package Elgg
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */

$body = '<p><label>'.elgg_echo("videolist:title_search_tube").'<br />';
//$body .= elgg_view("input/text",array('internalname' => 'title_search','value' => '', 'id' => 'title_search'));
$body .= "<div style='width:100%;'>";
$body .= "<div style='float:left;width:10%;'>";
$body .= "<a href=\"http://www.youtube.com\"><img src='".$vars['url']."mod/videolist/graphics/badge3.gif' height='30'/></a>";
$body .= "</div>";
$body .= "<div style='float:left;width:45%;'>";
$body .= "<input type=\"text\" name=\"title_search\" value=\"\" id=\"title_search\" size=\"30\"/> &nbsp;&nbsp;";
if($error['no-search'] == 0) {
	$body .= '<div class="videolist_error">'.$error_msg['no-search'].'</div>';
}
$body .= "</div>";
//$body .= "<div>";
//$body .= '<input type="submit" value="Submit" class="submit_button" name="submit" onclick="sendSearchRequest(1);"/>';
$body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('videolist:searchTubeVideos')));
//$body .= "</div>";
$body .= "</div>";
$body .= '</label></p>';

print $body;