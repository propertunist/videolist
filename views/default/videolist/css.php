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




/* /////////////////////////////////////////// @todo clean up / update rules below */
#video-list-main{
	padding:10px;
}
#videocontainer{
	width:100%;
	margin-bottom:10px;
}
#videobox{
	width:98%;
	text-align:center;
	margin-top:10px;
}
#videoDescbox{
	width:95%;
	margin:0px 5px 0px 8px;
}
#videoActionbox{
	width:90%;
	margin:0px 5px 0px 8px;
	float:left;
}
th{
	text-align:center;
	font-weight:bold;
	font-size:13px;
}
#parentTab{
	-moz-border-radius-bottomleft:8px;
	-moz-border-radius-bottomright:8px;
	-moz-border-radius-topleft:8px;
	-moz-border-radius-topright:8px;
	border-top:1px solid #CCC;
	border-bottom:6px solid #CCC;
	border-left:1px solid #CCC;
	border-right:5px solid #CCC;
	background:#FFFFFF;
	margin-top:7px;
}
.tabcellDesc{
	padding:5px 5px 5px 10px;
	text-align: left;
}
.tabcellText{
	padding:5px;
	text-align: center;
}
.videoDisp{
	position:absolute;
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;
	-moz-border-radius-bottomleft:5px;
	-moz-border-radius-bottomright:5px;
	border:1px solid #000000;
	display:none;
	padding:10px;
	margin-left:120px;
	margin-top:-100px;
	background:#FFFFFF;
	z-index:300000;
}
.tubesearch{
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;
	-moz-border-radius-bottomleft:5px;
	-moz-border-radius-bottomright:5px;
	border:1px solid #666666;
}
.searchvideorow{
	padding:10px;
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;
	-moz-border-radius-bottomleft:5px;
	-moz-border-radius-bottomright:5px;
	border:1px solid #CCCCCC;
}
#videosearch-tablecontainer{
	-moz-border-radius-bottomleft:5px;
	-moz-border-radius-bottomright:5px;
	border:1px solid #CCCCCC;
	background:#FFF;
	padding:10px;
}
#videosearch-interface{
	border-color:#CCCCCC;
	border-style:solid;
	border-width:0 1px 1px;
	margin-top:-15px;
	padding:15px 0 15px 20px;
}
