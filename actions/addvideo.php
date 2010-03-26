<?php

	 /**
	 * Elgg Video Plugin
	 * This plugin allows users to create a library of youtube/vimeo/metacafe videos
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Prateek Choudhary <synapticfield@gmail.com>
	 * @copyright Prateek Choudhary
	 */
		
// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get the current page's owner	
set_page_owner($_SESSION['container_guid']);

$page_owner = page_owner_entity();

if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($_SESSION['guid']);
}

if($page_owner->type == "group")
	$entity_referer = $page_owner->type.":".$page_owner->getGUID();
else 
	$entity_referer = $page_owner->username;

require_once(dirname(dirname(__FILE__)) . "/models/lib/class.vimeo.php");

function fetchyoutubeDatatitle($videoid){
  $buffer = file_get_contents('http://www.youtube.com/api2_rest?method=youtube.videos.get_details&dev_id=rG48P7iz0eo&video_id='.$videoid);
  /**
	** generate XML View 
	**/
	$xml_buffer = new SimpleXMLElement($buffer);
 $vidDataTitle = $xml_buffer->video_details->title;
 return $vidDataTitle;       
  //return "";    
}

function fetchyoutubeDatadesc($videoid){
  $buffer = file_get_contents('http://www.youtube.com/api2_rest?method=youtube.videos.get_details&dev_id=rG48P7iz0eo&video_id='.$videoid);
  /**
  ** generate XML View 
  **/
  $xml_buffer = new SimpleXMLElement($buffer);
  $vidDataDesc = $xml_buffer->video_details->description;
  return $vidDataDesc; 
  //return "";          
}

function getVimeoInfoDataTitle($iGetVideoId){
			// Now lets do the search query. We will get an response object containing everything we need
			$oResponse = VimeoVideosRequest::getInfo($iGetVideoId);
			// We want the result videos as an array of objects
			$aoVideos = $oResponse->getVideo();
			$title = $aoVideos->getTitle();
			return $title;
}

function getVimeoInfoDataDesc($iGetVideoId){
			// Now lets do the search query. We will get an response object containing everything we need
			$oResponse = VimeoVideosRequest::getInfo($iGetVideoId);
			// We want the result videos as an array of objects
			$aoVideos = $oResponse->getVideo();
			$description = $aoVideos->getCaption();
			return $description;
}

function getVimeoInfoImage($iGetVideoId){
			// Now lets do the search query. We will get an response object containing everything we need
			$oResponse = VimeoVideosRequest::getInfo($iGetVideoId);
			// We want the result videos as an array of objects
			$aoVideos = $oResponse->getVideo();
			//get all thumbnails

			$aThumbnails = array();
			foreach($aoVideos->getThumbnails() as $oThumbs) {
				$aThumbnails[] = $oThumbs->getImageContent();
			}
			
			foreach($aThumbnails as $thumbnailArray){
				$thumbnail = $thumbnailArray;
				break;
			}
		
			return $thumbnail;
}

function fetchyoutubeDatathumbnail($videoId){
			$thumbnail = "http://img.youtube.com/vi/".$videoId."/default.jpg";
			return $thumbnail;
}

function metacafeFetchData($getVideoId){
			$feedURL = "http://www.metacafe.com/api/item/".$getVideoId;
			$sxml = new DomDocument;
			$sxml->load($feedURL);
			$myitem = $sxml->getElementsByTagName('item');
			return $myitem;
}

function fetchmetacafeTitle($getVideoId){
				$myitem = metacafeFetchData($getVideoId);
				foreach($myitem as $searchNode){
					$xmlTitle = $searchNode->getElementsByTagName("title");
					$valueTitle = $xmlTitle->item(0)->nodeValue; 
				}
				return $valueTitle;
}

function fetchmetacafeDesc($getVideoId){
				$myitem = metacafeFetchData($getVideoId);
				foreach($myitem as $searchNode){
					$xmlDesc = $searchNode->getElementsByTagName("description");
					$valueDesc = $xmlDesc->item(0)->nodeValue;
					$ot = "<p>";
					$ct = "</p>";
					$string = trim($valueDesc);
					$start = intval(strpos($string, $ot) + strlen($ot));
					$desc_src = substr($string,$start,intval(strpos($string,$ct) - $start)); 
				}
				return $desc_src;
}

function fetchmetacafeImg($getVideoId){
				$myitem = metacafeFetchData($getVideoId);
				foreach($myitem as $searchNode){
					$xmlDesc = $searchNode->getElementsByTagName("description");
					$valueDesc = $xmlDesc->item(0)->nodeValue;
					$pattern = '/<img[^>]+src[\\s=\'"]';
					$pattern .= '+([^"\'>\\s]+)/is';
					if(preg_match($pattern,$valueDesc,$match)){
						$thumbnail = $match[1];
					}
				}
				return $thumbnail;
}

			$pageContainer = $_SESSION['Pagecontainer'];

	
	// Initialise a new ElggObject
			$videolist = new ElggObject();
	// Tell the system it's a blog post
			$videolist->subtype = "videolist";
	// Set its owner to the current user
			$videolist->owner_guid = $_SESSION['user']->getGUID();
			
	// Set container of the video whether it was uploaded to groups or profile
			$videolist->container_guid = $_SESSION['container_guid'];
	// For now, set its access to public (we'll add an access dropdown shortly)
			$videolist->access_id = $_SESSION['candidate_profile_video_access_id'];
	
	// In order to Set its title and description appropriately WE need the video ID
			$videolist->url = $_SESSION['candidate_profile_video'];
			
			if($pageContainer == "youtube"){
				$videoIDArray = split("/v/", $videolist->url);
				$videolist->video_id = $videoIDArray[1];
				// Now set the video title and description appropriately	
				$videolist->title = fetchyoutubeDatatitle($videoIDArray[1]);
				$videolist->desc = fetchyoutubeDatadesc($videoIDArray[1]);
				$videolist->thumbnail = fetchyoutubeDatathumbnail($videoIDArray[1]);
				$videolist->videotype = "youtube";
			}
			else if($pageContainer == "metacafe"){
				$videolist->video_id = $_SESSION['candidate_profile_video'];
				// Now set the video title and description appropriately	
				$videolist->title = fetchmetacafeTitle($_SESSION['candidate_profile_video']);
				$videolist->desc = fetchmetacafeDesc($_SESSION['candidate_profile_video']);
				$videolist->thumbnail = fetchmetacafeImg($_SESSION['candidate_profile_video']);
				$videolist->videotype = "metacafe";
			}
			else if($pageContainer == "vimeo"){
				$videolist->video_id = $_SESSION['candidate_profile_video'];
				
				// Now set the video title and description appropriately	
				$videolist->title = getVimeoInfoDataTitle($_SESSION['candidate_profile_video']);
				$videolist->desc = getVimeoInfoDataDesc($_SESSION['candidate_profile_video']);
				$videolist->thumbnail = getVimeoInfoImage($_SESSION['candidate_profile_video']);
				$videolist->videotype = "vimeo";
			}
	
	// Before we can set metadata, we need to save the blog post
			if (!$videolist->save()) {
				register_error(elgg_echo("videolist:error"));
				forward("pg/videolist/new");
			}
	//add video tags
			$videolist_tags_array = string_to_tag_array($_SESSION['videolisttags']);
			if (is_array($videolist_tags_array)) {
				$videolist->tags = $videolist_tags_array;
			}
	
    // add to river
	    add_to_river('river/object/videolist/create', 'create', $_SESSION['user']->guid, $videolist->guid);
	        		
	// add_to_river('river/object/blog/create','create',$_SESSION['user']->guid,$blog->guid);
	// Success message
			system_message(elgg_echo("videolist:posted"));
	// Remove the videolist cache
			unset($_SESSION['candidate_profile_video_access_id']); unset($_SESSION['candidate_profile_video']); 
			unset($_SESSION['videolisttags']);unset($_SESSION['Pagecontainer']);
	// Forward to the main videolist page
	
forward("pg/videolist/owned/".page_owner_entity()->username);

// Remove the videolist cache
			unset($_SESSION['candidate_profile_video_access_id']); unset($_SESSION['candidate_profile_video']); 
			unset($_SESSION['videolisttags']);unset($_SESSION['Pagecontainer']);unset($_SESSION['container_guid']);

?>
