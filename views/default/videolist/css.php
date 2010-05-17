<?php
/**
 * Elgg Candidate Profile Video Plugin
 * This plugin allows users to create a library of youtube videos
 *
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */
?>

.videolist_error{
	color:red;
	font-weight:bold;
}

/* video listing */
.video_entity .entity_listing:first-child {
	border-top:0;
}
.entity_listing_icon .video_icon {
	width:150px;
	height:95px;
	display:table-cell;
	text-align:center;
	vertical-align: middle;
	background-color: black;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
.video_entity .entity_listing_info {
	width:560px;
	margin-left:15px;
}

/* single video view page */
.video_view {
	text-align: center;
	margin-top:5px;
	padding-bottom:20px;
	border-bottom:1px solid #CCCCCC;
}
.video_view embed {
	margin-top:20px;
}

/* search for videos */
#loading_search_results .ajax_loader {
	margin:10px 0;
}
.search_videos {
	width:100%;
	margin-top:4px;
}
.search_videos .submit_button {
	margin:0;
}
.search_videos #title_search {
	margin-left:14px;
	margin-right:14px;
}

/* find videos search results list */
#videosearch_results .video_entity {
	padding:10px 0;
	border-top:1px dotted #CCCCCC;
}
#videosearch_results .video_entity table {
	width:100%;
}
#videosearch_results .video_actions {
	text-align: right;
	width:15%;
}
#videosearch_results .video_entity .entity_title {
	margin:0;
}

/* pop-up video player */
#page_overlay {
	position: fixed;
	top: 0px;
	left: 0px;
	height:100%;
	width:100%;
	z-index:299999;
}
.video_popup{
	position:absolute;
	display:none;
	padding:5px 10px 10px 10px;
	background:black;
	z-index:300000;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
}
.close_video {
	margin-bottom:5px;
	text-align: right;
}
.close_video a {
	color:white;
}
