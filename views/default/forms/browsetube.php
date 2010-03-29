<?php

/**
 * Elgg Video Plugin
 * This plugin allows users to create a library of youtube/vimeo/metacafe videos
 * @file - load the browse view
 * @package Elgg
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */

$getcontainer_guid = get_input("container");
$container_guid = explode(":", $getcontainer_guid);
if($container_guid[0] == "group"){
	$container = $container_guid[1];
} else{
	$container = $getcontainer_guid;
}
$error = array(
							'no-search' => 1
							);
$error_msg = array(
							'no-search' => "Please enter a valid search term"
							);
$browseCat = get_input('q');
if(empty($browseCat) || !isset($browseCat)) {
	$browseCat = "youtube";
}

$confirm_action = get_input('video_action');

if(isset($confirm_action) && ($confirm_action == 'search_video')) {
	if(isset($title_search) && ($title_search != '')) {
		$error['no-search'] = 0;
	} else {
		$error['no-search'] = 1;
	}
}

//$body = '<div class="videolist-content">';
//Load youtube menu
$body .= "<br /><div class=\"elgg_horizontal_tabbed_nav\">";
$body .= 		"<ul id=\"videonav\">";
$body .= 			"<li class=\"active\" id=\"YT\">";
$body .= 				"<a href=\"".$vars['url']."pg/videolist/browse/".$getcontainer_guid."?q=youtube\">YouTube</a>";
$body .= 			"</li>";
$body .= 			"<li id=\"MC\">";
$body .= 				"<a href=\"".$vars['url']."pg/videolist/browse/".$getcontainer_guid."?q=metacafe\">Metacafe</a>";
$body .= 			"</li>";
$body .= 			"<li id=\"VM\">";
$body .= 				"<a href=\"".$vars['url']."pg/videolist/browse/".$getcontainer_guid."?q=vimeo\">Vimeo</a>";
$body .= 			"</li>";
/*
$body .= 			"<li id=\"GV\">";
$body .= 				"<a href=\"".$vars['url']."pg/videolist/browse?q=googlevideos\">Google Videos</a>";
$body .= 			"</li>";
*/
$body .= 		"</ul>";
$body .= "</div>";

$body .= "<div class=\"clearfloat\"></div>";

//$body .= "<div id=\"videosearch-interface\">";
$body .= '<form action="javascript:sendSearchRequest(1);" method="get">';
//$body .= "<form action=\"".$vars['url']."action/videolist/tubesearch\" method=\"get\">";
$body .= elgg_view('input/hidden',array('internalname'=>'video_action', 'value'=>'search_video'));
$body .= elgg_view('input/hidden',array('internalname'=>'guid', 'value'=>$vars['guid']));
//$body .= elgg_view('input/hidden',array('internalname'=>'start_index', 'value'=>1));

switch($browseCat) {
	case "youtube" :
		$body .= elgg_view('forms/labels/youtube');
		break;
	case "metacafe" :
		$body .= elgg_view('forms/labels/metacafe');
		break;
	case "vimeo" :
		$body .= elgg_view('forms/labels/vimeo');
		break;
	case "googlevideos" :
		$body .= elgg_view('forms/labels/googlevideos');
		break;
	default :
		$body .= elgg_view('forms/labels/youtube');
		break;
}
$body .= elgg_view('input/hidden',array('internalname'=>'page', 'value'=>$browseCat));
$body .= '</form>';
//$body .= '</div>';
//$body .= '</div>';

$body .= '<div id="SearchContainer">';
$body .= 	'<div id="loadingSearch">';
$body .= 	'</div>';
$body .= 	'<div id="responseSearch" align="center">';
$body .= 	'</div>';
$body .= '</div>';

print $body."<br /><br />";


?>
<style type="text/css">
#videosearch-menu{
position:relative;
width:675px;
height:31px;
border:0px solid #CCC;
border-width:0px 0px 1px 0px;
margin:0px 0px 15px 0px;
}
#videosearch-menu ul{
position:relative;
text-align:left;
width:600px;
}
#videosearch-menu ul li{
position:relative;
float:left;
list-style-type:none;
min-width:20%;
cursor:pointer;
margin:0px 22px 0px -20px;
padding:8px 5px 5px 3px;
border:0px solid #CCC;
border-width:0px 0px 0px 0px;
text-align:center;
}

</style>
<script type="text/javascript">
var page = "<?php echo $browseCat;?>";
var container = "<?php echo $container;?>";
$('#videonav li').removeClass();
switch(page)
{
case "youtube" : $('#YT').removeClass().addClass('active');
									break;
case "metacafe" : $('#MC').removeClass().addClass('active');
									break;
case "vimeo" : $('#VM').removeClass().addClass('active');
									break;
case "googlevideos" : $('#GV').removeClass().addClass('active');
									break;
default : 	$('#YT').removeClass().addClass('active');
									break;
}

function sendSearchRequest(p){
var queryFeed = $("#title_search").val();
if(trim(queryFeed) != '')
{
	$("#loadingSearch").html("<div align=\"center\" class=\"ajax_loader\"></div>");
	var elggTS = "<?php echo time(); ?>";
	var elggToken = "<?php echo generate_action_token(time()); ?>";
	$.ajax({
		type: "GET",
		url: "<?php echo $vars['url']; ?>"+"action/videolist/tubesearch",
		data: "bustcache="+new Date().getTime()+"&__elgg_ts="+elggTS+"&__elgg_token="+elggToken+"&page="+page+"&q="+queryFeed+"&start_index="+p+"&container="+container,
		success: function(html){
			$("#loadingSearch").html("");
			$("#responseSearch").html('');
			$("#responseSearch").html(html);
		}
	});
}
else{}
}

function showV_idFeed(param, param2){
var arg = param;
var embed_video = "<div style='text-align:right;'><a href='javascript:void(0);' onclick='javascript:closeit("+param2+");'>close</a></div><object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0' width='475' height='350'>"+
							"<param name='movie' value='"+arg+"&amp;autoplay=1'>"+
							"<param name='quality' value='high'>"+
							"<param name='bgcolor' value='#CEEFFF'>"+
							"<!--[if !IE]> <-->"+
							"<object data='"+arg+"&amp;autoplay=1' width='475' height='350' autoplay=1 type='application/x-shockwave-flash'>"+
							"<param name='quality' value='high'>"+
							"<param name='bgcolor' value='#CEEFFF'>"+
							"<param name='pluginurl' value='http://www.adobe.com/go/getflashplayer'>"+
							"FAIL (the browser should render some flash content, not this)."+
							"</object>"+
							"<!--> <![endif]-->"+
							"</object>";
disableScreen(embed_video, param2);
}

function showV_idFeedMetacafe(param, param2){
var argArray = param.split("/");
var arg = argArray[0]+"/"+argArray[1]+".swf";
var embed_video = "<div style='text-align:right;'><a href='javascript:void(0);' onclick='javascript:closeit("+param2+");'>close</a></div><object width=\"475\" height=\"350\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"Metacafe_"+argArray[0]+"\" value=\"http://www.metacafe.com/fplayer/"+arg+"&amp;autoplay=1\" /><embed src=\"http://www.metacafe.com/fplayer/"+arg+"&amp;autoplay=1\" type=\"application/x-shockwave-flash\" name=\"Metacafe_"+argArray[0]+"\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"475\" height=\"350\"></embed></object>";

disableScreen(embed_video, param2);
}

function showV_idFeedVimeo(param, param2){
var arg = param;
var embed_video = "<div style='text-align:right;'><a href='javascript:void(0);' onclick='javascript:closeit("+param2+");'>close</a></div><object width=\"475\" height=\"350\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id="+arg+"&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id="+arg+"&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"475\" height=\"350\"></embed></object>";
disableScreen(embed_video, param2);
}

function disableScreen(embed_video, param2)
{
var getContainer = "#vidContainer"+param2;
$('#page_container').append("<div id='page_overlay' style='position:absolute;'/>");
$('#page_overlay').css({
			backgroundColor: "#000",
			opacity: "0.8",
			width: $(window).width(),
			height: $('#page_container').height(),
			top: "0px",
			left: -(($(window).width() - $('body').width()) / 2)
		}).fadeIn();
$(getContainer).css("width", "0%");
$(getContainer).animate( { width:"45%"}, 300 );
document.getElementById("vidContainer"+param2).innerHTML = embed_video;
}

function trim(stringToTrim)
{
return ltrim(rtrim(stringToTrim));
}

function ltrim(stringToTrim) {
return stringToTrim.replace(/^\s+/,"");
}

function rtrim(stringToTrim) {
return stringToTrim.replace(/\s+$/,"");
}

function closeit(param){
document.getElementById("vidContainer"+param).innerHTML = "";
document.getElementById("vidContainer"+param).style.display = "none";
$('#page_overlay').remove();
}

function InsertVideoUrl(param, param2){
	var actionAction = "add_video";
	var access_id = 2;
	var elggTS = "<?php echo time(); ?>";
	var elggToken = "<?php echo generate_action_token(time()); ?>";
	$.ajax({
		type: "GET",
		url: "<?php echo $vars['url']; ?>"+"action/videolist/add",
		data: "bustcache="+new Date().getTime()+"&__elgg_ts="+elggTS+"&__elgg_token="+elggToken+"&video_action="+actionAction+"&title_videourl="+param+"&videolisttags="+param2+"&access_id="+access_id,
		success: function(html){
			$("#loadingSearch").html("");
			$("#responseSearch").html('');
			$("#responseSearch").html(html);
		}
	});

}
</script>
