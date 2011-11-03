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

//Load youtube menu
$body .= "<div class='elgg_horizontal_tabbed_nav margin_top'>";
$body .= 		"<ul id='videonav'>";
$body .= 			"<li class='selected' id='YT'>";
$body .= 				"<a href=\"".$vars['url']."videolist/browse/".$getcontainer_guid."?q=youtube\">YouTube</a>";
$body .= 			"</li>";
$body .= 			"<li id='MC'>";
$body .= 				"<a href=\"".$vars['url']."videolist/browse/".$getcontainer_guid."?q=metacafe\">Metacafe</a>";
$body .= 			"</li>";
$body .= 			"<li id='VM'>";
$body .= 				"<a href=\"".$vars['url']."videolist/browse/".$getcontainer_guid."?q=vimeo\">Vimeo</a>";
$body .= 			"</li>";
$body .= 		"</ul>";
$body .= "</div>";

$body .= '<form action="javascript:sendSearchRequest(1);" method="get" id="browse_video_form">';
$body .= elgg_view('input/hidden',array('internalname'=>'video_action', 'value'=>'search_video'));
$body .= elgg_view('input/hidden',array('internalname'=>'guid', 'value'=>$vars['guid']));

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

$body .= 	'<div id="loading_search_results"></div>';
$body .= 	'<div id="responseSearch" align="center"></div>';

print $body;


?>

<script type="text/javascript">
var page = "<?php echo $browseCat;?>";
var container = "<?php echo $container;?>";
$('#videonav li').removeClass();
switch(page) {
case "youtube" : $('#YT').removeClass().addClass('selected');
									break;
case "metacafe" : $('#MC').removeClass().addClass('selected');
									break;
case "vimeo" : $('#VM').removeClass().addClass('selected');
									break;
/*
case "googlevideos" : $('#GV').removeClass().addClass('active');
									break;
*/
default : 	$('#YT').removeClass().addClass('selected');
									break;
}

function sendSearchRequest(p){
var queryFeed = $("#title_search").val();
if(trim(queryFeed) != '') {
	$("#loading_search_results").html("<div class='ajax_loader'></div>");
	var elggTS = "<?php echo time(); ?>";
	var elggToken = "<?php echo generate_action_token(time()); ?>";
	$.ajax({
		type: "GET",
		url: "<?php echo $vars['url']; ?>"+"action/videolist/tubesearch",
		data: "bustcache="+new Date().getTime()+"&__elgg_ts="+elggTS+"&__elgg_token="+elggToken+"&page="+page+"&q="+queryFeed+"&start_index="+p+"&container="+container,
		success: function(html){
			$("#loading_search_results").html("");
			$("#responseSearch").html('');
			$("#responseSearch").html(html);
		}
	});
}
else{}
}

function showV_idFeed(param, param2){
var arg = param;
var embed_video = "<div class='close_video'><a href='javascript:void(0);' onclick='javascript:closeit("+param2+");'>close</a></div><object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0' width='475' height='350'>"+
							"<param name='movie' value='"+arg+"&amp;autoplay=1'>"+
							"<param name='quality' value='high'>"+
							"<param name='bgcolor' value='#000000'>"+
							"<!--[if !IE]> <-->"+
							"<object data='"+arg+"&amp;autoplay=1' width='475' height='350' autoplay=1 type='application/x-shockwave-flash'>"+
							"<param name='quality' value='high'>"+
							"<param name='bgcolor' value='#000000'>"+
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
	var embed_video = "<div class='close_video'><a href='javascript:void(0);' onclick='javascript:closeit("+param2+");'>close</a></div><object width=\"475\" height=\"350\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"Metacafe_"+argArray[0]+"\" value=\"http://www.metacafe.com/fplayer/"+arg+"&amp;autoplay=1\" /><embed src=\"http://www.metacafe.com/fplayer/"+arg+"&amp;autoplay=1\" type=\"application/x-shockwave-flash\" name=\"Metacafe_"+argArray[0]+"\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"475\" height=\"350\"></embed></object>";
	
	disableScreen(embed_video, param2);
}

function showV_idFeedVimeo(param, param2){
	var arg = param;
	var embed_video = "<div class='close_video'><a href='javascript:void(0);' onclick='javascript:closeit("+param2+");'>close</a></div><object width=\"475\" height=\"350\"><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id="+arg+"&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id="+arg+"&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"475\" height=\"350\"></embed></object>";
	disableScreen(embed_video, param2);
}


function getPageScroll() {
	var xScroll, yScroll;
	if (self.pageYOffset) {
	  yScroll = self.pageYOffset;
	  xScroll = self.pageXOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
	  yScroll = document.documentElement.scrollTop;
	  xScroll = document.documentElement.scrollLeft;
	} else if (document.body) {// all other Explorers
	  yScroll = document.body.scrollTop;
	  xScroll = document.body.scrollLeft;	
	}
	return new Array(xScroll,yScroll) 
}

function getPageHeight() {
	var windowHeight
	if (self.innerHeight) {	// all except Explorer
	  windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
	  windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
	  windowHeight = document.body.clientHeight;
	}	
	return windowHeight
}

function getPageWidth() {
	var windowWidth;
	if( typeof( window.innerWidth ) == 'number' ) {
	windowWidth = window.innerWidth; //Non-IE
	} else if( document.documentElement && ( document.documentElement.clientWidth ) ) {
	windowWidth = document.documentElement.clientWidth; //IE 6+ in 'standards compliant mode'
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
	windowWidth = document.body.clientWidth; //IE 4 compatible
	}
	return windowWidth
}

function disableScreen(embed_video, param2) {
	var getContainer = "#vidContainer"+param2;
	$('body').append("<div id='page_overlay'/>");
	$('#page_overlay').css({
				backgroundColor: "#000000",
				opacity: "0.7"
			}).fadeIn();
			
	$(getContainer).css({
					top: getPageScroll()[1] + (getPageHeight() / 10),
					left: ((getPageWidth() / 2) - (400)),
					height: "0px"
      }).animate( {height:"390px"}, 600 );			
	
	document.getElementById("vidContainer"+param2).innerHTML = embed_video;
}

function trim(stringToTrim){
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
			$("#loading_search_results").html("");
			$("#responseSearch").html('');
			$("#responseSearch").html(html);
		}
	});

}
</script>
