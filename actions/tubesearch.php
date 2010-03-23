<?php      
   
   /**
	 * Elgg Video Plugin
	 * This plugin allows users to create a library of youtube/vimeo/metacafe videos
	 * @file - allows search for video from vimeo/youtube/and metacafe
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Prateek Choudhary <synapticfield@gmail.com>
	 * @copyright Prateek Choudhary
	 */

// Get the current page's owner	
//set_page_owner($_SESSION['container_guid']);

$page_ownerx = get_entity(get_input('container'));
if(!$page_ownerx)
	$page_ownerx = get_user_by_username(get_input('container'));
set_page_owner($page_ownerx->getGUID());
$page_owner = page_owner_entity();

if($page_owner->type == "group")
	$container = "group:".$page_ownerx->getGUID();
else
	$container = $page_ownerx->username;

if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($_SESSION['guid']);
}

global $CONFIG; 
    $queryFeed = get_input('q');
    $start_index = get_input('start_index');
    $results_perpage = 10;
    $queryCatgory = get_input('page');
if (!isset($queryFeed) || empty($queryFeed)) {
        
} 
else 
{
     $q = $queryFeed;
     if($queryCatgory == "youtube")
     {
     	$feedURL = "http://gdata.youtube.com/feeds/api/videos?vq=".$queryFeed."&orderby=relevance&start-index=".$start_index."&max-results=10";
      $sxml = simplexml_load_file($feedURL);

      $counts = $sxml->children('http://a9.com/-/spec/opensearchrss/1.0/');
      $total = $counts->totalResults; 
      $startOffset = $counts->startIndex; 
      $endOffset = ($startOffset-1) + $counts->itemsPerPage;    

			$body = '<div id="paginateSearch">';
			$rem = floor($total/10);
			$rem*=10;
			if($rem<$total)
			 $last = $rem+1;
			 $lpVid = $total - $rem;
			if($startOffset==1 && ($endOffset)==$total){}
			 else if($startOffset==1 && ($endOffset)<$total){
					$body .=  '<a href="javascript:void(0);">first</a> | ';
					$body .=  '<a href="javascript:void(0);">previous</a> | ';
					$body .=  '<a href="javascript:sendSearchRequest('.($endOffset+1).');">next</a> | ';
					$body .=  '<a href="javascript:sendSearchRequest('.$last.');">last</a>';
			 }
			 else if($startOffset>1 && ($endOffset)<$total){
			$body .=  '<a href="javascript:sendSearchRequest(1);">first</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($startOffset-10).');">previous</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($endOffset+1).');">next</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.$last.');">last</a>';
			 }
			 else if($startOffset>1 && ($endOffset+$lpVid)>=$total){
			$body .=  '<a href="javascript:sendSearchRequest(1);">first</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($startOffset-10).');">previous</a> | ';
			$body .=  '<a href="javascript:void(0);">next</a> | ';
			$body .=  '<a href="javascript:void(0);">last</a>';
			 }
			$body .=  '</div>';
			$body .= '<div id="videosearch-tablecontainer">';
			$k = 0;$counter = 0;
			foreach ($sxml->entry as $entry) {
							$k++;
						  $media = $entry->children('http://search.yahoo.com/mrss/');
						  $attrs = $media->group->player->attributes();
						  $watch = $attrs['url']; 
						  $vid_array = explode("?v=", $watch);
						  if(preg_match("/&/", $vid_array[1])){
						  	$vid_array = explode("&", $vid_array[1]);
						  	$vid_array[1] = $vid_array[0];
						  }
						  	
						  $attrs = $media->group->thumbnail[0]->attributes();
						  $thumbnail = $attrs['url']; 
						  $yt = $media->children('http://gdata.youtube.com/schemas/2007');
						  $attrs = $yt->duration->attributes();
						  $length = $attrs['seconds']; 
						  $gd = $entry->children('http://schemas.google.com/g/2005'); 
						  if ($gd->rating) {
						    $attrs = $gd->rating->attributes();
						    $rating = $attrs['average']; 
						  } else {
						    $rating = 0; 
						  }
						  $tags = array();
						  $tags[] = $media->group->keywords;
					$showEncodedVideo = preg_replace('/(http:)(\/\/)(www.)([^ \/"]*)([^ >"]*)watch\?(v=)([^ >"]*)/i', '$1$2$3$4$5v/$7', $watch);
			$body .=  '<div class="parentTabClass"><table id="parentTab" cellpadding="4" cellspacing="4" border="1">';
			$body .=  '<tr class="searchvideorow">';

			$body .=  '<td class="tabcellText" width="15%">';
			$body .=  "<span class=\"HoverLink\"><a href=\"javascript:void(0);\" onclick=\"showV_idFeed('".$showEncodedVideo."', ".$k.")\"><img src=\"".$thumbnail."\" width=\"90%\" height=\"90%\" class=\"tubesearch\"/></a></span>";
			$body .=  '<div id="vidContainer'.$k.'" class="videoDisp"></div></td>';

			$body .=  '<td class="tabcellDesc" width="60%">';
			$body .=  "<a href=\"javascript:void(0);\" onclick=\"showV_idFeed('".$showEncodedVideo."', ".$k.")\">".$media->group->title."</a><br>";
			$body .=  "<b>Duration : </b>" . sprintf("%0.2f", $length/60) . " min.<br /><b>user rating : </b>".$rating."<br/>";
			$body .=  "<b>Description : </b>".substr($media->group->description, 0, 140)." ...";
			$body .=  '</td>';

			//$body .=  "<td class=\"tabcellText\" width=\"12%\"><a href=\"javascript:void(0);\" onclick=\"javascript:showV_idFeed('".$showEncodedVideo."', ".$k.")\">play</a> | <a href=\"javascript:void(0);\" onclick=\"javascript:InsertVideoUrl('".$showEncodedVideo."','".$tags[$counter]."');\">add</a></td>";

			$body .=  "<td class=\"tabcellText\" width=\"15%\"><input type=\"button\" name=\"play\" value=\"Play\" onclick=\"javascript:showV_idFeed('".$showEncodedVideo."', ".$k.")\" class=\"submit_button\"> <a href=\"".$CONFIG->wwwroot."pg/videolist/new/".$container."/title_videourl/".$vid_array[1]."/page/".$queryCatgory."\");\"><input type=\"button\" name=\"add\" value=\"Add\" class=\"submit_button\"></a></td>";

			$body .=  '</tr>';
			$body .=  '</table></div>';
			}
			$body .=  '</div>';
			print $body;
		}
		else if($queryCatgory == "metacafe")
    {	
    	
    	
    	$feedURL = "http://www.metacafe.com/api/videos/?vq=".$queryFeed."&orderby=rating&start-index=".$start_index."&max-results=10";
			
			
			$sxml = new DomDocument;
			$sxml->load($feedURL);
      $total = 999; 
      $startOffset = $start_index; 
      $endOffset = ($startOffset-1) + $results_perpage;    
			
			$body = '<div id="paginateSearch">';
			$rem = floor($total/10);
			$rem*=10;
			if($rem<$total)
			 $last = $rem+1;
			 $lpVid = $total - $rem;
			if($startOffset==1 && ($endOffset)==$total){}
			 else if($startOffset==1 && ($endOffset)<$total){
					$body .=  '<a href="javascript:void(0);">first</a> | ';
					$body .=  '<a href="javascript:void(0);">previous</a> | ';
					$body .=  '<a href="javascript:sendSearchRequest('.($endOffset+1).');">next</a> | ';
					$body .=  '<a href="javascript:sendSearchRequest('.$last.');">last</a>';
			 }
			 else if($startOffset>1 && ($endOffset)<$total){
			$body .=  '<a href="javascript:sendSearchRequest(1);">first</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($startOffset-10).');">previous</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($endOffset+1).');">next</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.$last.');">last</a>';
			 }
			 else if($startOffset>1 && ($endOffset+$lpVid)>=$total){
			$body .=  '<a href="javascript:sendSearchRequest(1);">first</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($startOffset-10).');">previous</a> | ';
			$body .=  '<a href="javascript:void(0);">next</a> | ';
			$body .=  '<a href="javascript:void(0);">last</a>';
			 }
			$body .=  '</div>';
			$k = 0;
			$body .= '<div id="videosearch-tablecontainer">';
			$myitem = $sxml->getElementsByTagName('item');
			foreach($myitem as $searchNode){
				$k++;
				$xmlTitle = $searchNode->getElementsByTagName("title");
				$valueTitle = $xmlTitle->item(0)->nodeValue; 
		
				$xmlLink = $searchNode->getElementsByTagName("link");
				$valueLink = $xmlLink->item(0)->nodeValue; 
		
				$xmlDesc = $searchNode->getElementsByTagName("description");
				$valueDesc = $xmlDesc->item(0)->nodeValue; 
		
				$pattern = '/<img[^>]+src[\\s=\'"]';
				$pattern .= '+([^"\'>\\s]+)/is';
				if(preg_match($pattern,$valueDesc,$match)){
					$thumbnail = $match[1];
				}
		
				$pattern = '/<a[^>]+href[\\s=\'"]';
				$pattern .= '+([^"\'>\\s]+)/is';
				if(preg_match($pattern,$valueDesc,$match)){
					$anchor_src = $match[1];
				}
				
				$encodedVideoUrlArray = explode("/watch/", $valueLink);
				$showEncodedVideo = $encodedVideoUrlArray[1];
				$metacafevideoIdArray = explode("/", $showEncodedVideo);
				
				$ot = "<p>";
				$ct = "</p>";
				$string = trim($valueDesc);
				$start = intval(strpos($string, $ot) + strlen($ot));
				$desc_src = substr($string,$start,intval(strpos($string,$ct) - $start)); 
				
				$body .=  '<div class="parentTabClass">';
				$body .= '<table id="parentTab" cellpadding="4" cellspacing="4" border="1">';
				$body .=  '<tr class="searchvideorow">';

				$body .=  '<td class="tabcellText" width="15%">';
				$body .=  "<span class=\"HoverLink\"><a href=\"javascript:void(0);\" onclick=\"showV_idFeedMetacafe('".$showEncodedVideo."', ".$k.")\"><img src=\"".$thumbnail."\" width=\"90%\" height=\"90%\" class=\"tubesearch\"/></a></span>";
				$body .=  '<div id="vidContainer'.$k.'" class="videoDisp"></div></td>';

				$body .=  '<td class="tabcellDesc" width="60%">';
				$body .=  "<a href=\"javascript:void(0);\" onclick=\"showV_idFeedMetacafe('".$showEncodedVideo."', ".$k.")\">".$valueTitle."</a><br>";
				//$body .=  "<b>Duration : </b>" . sprintf("%0.2f", $length/60) . " min.<br /><b>user rating : </b>".$rating."<br/>";
				$body .=  "<b>Description : </b>".$desc_src;
				$body .=  '</td>';

				//$body .=  "<td class=\"tabcellText\" width=\"12%\"><a href=\"javascript:void(0);\" onclick=\"javascript:showV_idFeed('".$showEncodedVideo."', ".$k.")\">play</a> | <a href=\"javascript:void(0);\" onclick=\"javascript:InsertVideoUrl('".$showEncodedVideo."','".$tags[$counter]."');\">add</a></td>";

				$body .=  "<td class=\"tabcellText\" width=\"15%\"><input type=\"button\" name=\"play\" value=\"Play\" onclick=\"javascript:showV_idFeedMetacafe('".$showEncodedVideo."', ".$k.")\" class=\"submit_button\"> <a href=\"".$CONFIG->wwwroot."pg/videolist/new/".$container."/title_videourl/".$metacafevideoIdArray[0]."/page/".$queryCatgory."\");\"><input type=\"button\" name=\"add\" value=\"Add\" class=\"submit_button\"></a></td>";

				$body .=  '</tr>';
				$body .=  '</table>';
				$body .= '</div>';
			}
			$body .=  '</div>';
			print $body;
		}
		else if($queryCatgory == "vimeo")
    {	
    	require_once(dirname(dirname(__FILE__)) . "/models/lib/class.vimeo.php");
    	// Now lets do the search query. We will get an response object containing everything we need
			$oResponse = VimeoVideosRequest::search($queryFeed);

			// We want the result videos as an array of objects
			$aoVideos = $oResponse->getVideos();
			
			// Just for code completion
			$oVideo = new VimeoVideoEntity();

			$total = count($aoVideos); 
      $startOffset = $start_index; 
     
      $endOffset = ($startOffset-1) + $results_perpage;    
			$body = '<div id="paginateSearch">';
			$rem = floor($total/10);
			$rem*=10;
			if($rem<$total)
			 $last = $rem+1;
			 $lpVid = $total - $rem;
			if($startOffset==1 && ($endOffset)==$total){}
			 else if($startOffset==1 && ($endOffset)<$total){
					$body .=  '<a href="javascript:void(0);">first</a> | ';
					$body .=  '<a href="javascript:void(0);">previous</a> | ';
					$body .=  '<a href="javascript:sendSearchRequest('.($endOffset+1).');">next</a> | ';
					$body .=  '<a href="javascript:sendSearchRequest('.$last.');">last</a>';
			 }
			 else if($startOffset>1 && ($endOffset)<$total){
			$body .=  '<a href="javascript:sendSearchRequest(1);">first</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($startOffset-10).');">previous</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($endOffset+1).');">next</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.$last.');">last</a>';
			 }
			 else if($startOffset>1 && ($endOffset+$lpVid)>=$total){
			$body .=  '<a href="javascript:sendSearchRequest(1);">first</a> | ';
			$body .=  '<a href="javascript:sendSearchRequest('.($startOffset-10).');">previous</a> | ';
			$body .=  '<a href="javascript:void(0);">next</a> | ';
			$body .=  '<a href="javascript:void(0);">last</a>';
			 }
			$body .=  '</div>';
			$body .= '<div id="videosearch-tablecontainer">';
			$counter = 0;$k = 0;
			foreach($aoVideos as $oVideo) {
				$k++;	
				if(($counter > $startOffset) && ($counter < $endOffset))
				{
						//get all thumbnails
						$aThumbnails = array();
						foreach($oVideo->getThumbnails() as $oThumbs) {
							$aThumbnails[] = $oThumbs->getImageContent();
						}
						
						foreach($aThumbnails as $thumbnailArray){
							$thumbnail = $thumbnailArray;
							break;
						}
						//print_r($oVideo);
						$title = $oVideo->getTitle();
						$description = $oVideo->getCaption();
						$url = $oVideo->getUrl();
						$rating = $oVideo->getNumberOfLikes();
						$playedTimes = $oVideo->getNumberOfPlays();
						// Print all tags
						$aTags = array();
						foreach($oVideo->getTags() as $oTag) {
								$aTags[] = $oTag->getTag();
						}
						$play_idArray = explode("http://vimeo.com/", $url);
						$embedidArray = explode("/", $play_idArray[1]);
						$body .=  '<div class="parentTabClass">';
						$body .= '<table id="parentTab" cellpadding="4" cellspacing="4" border="1">';
						$body .=  '<tr class="searchvideorow">';

						$body .=  '<td class="tabcellText" width="15%">';
						$body .=  "<span class=\"HoverLink\"><a href=\"javascript:showV_idFeedVimeo('".$embedidArray[0]."', ".$k.")\"><img src=\"".$thumbnail."\" width=\"90%\" height=\"90%\" class=\"tubesearch\"/></a></span>";
						$body .=  '<div id="vidContainer'.$k.'" class="videoDisp"></div></td>';

						$body .=  '<td class="tabcellDesc" width="60%">';
						$body .=  "<a href=\"javascript:void(0);\" onclick=\"javascript:showV_idFeedVimeo('".$embedidArray[0]."', ".$k.")\">".$title."</a><br>";
						$body .=  "<b>User Likes : </b>".$rating."<br/>";
						$body .=  "<b>Played : </b>".$playedTimes." times<br/>";
						$body .=  "<b>Description : </b>".$description." ...<br/>";
						$body .=  "<b>Tags : </b>".implode(', ', $aTags);
						$body .=  '</td>';
					
						$body .=  "<td class=\"tabcellText\" width=\"15%\"><input type=\"button\" name=\"play\" value=\"Play\" onclick=\"javascript:showV_idFeedVimeo('".$embedidArray[0]."', ".$k.")\" class=\"submit_button\"> <a href=\"".$CONFIG->wwwroot."pg/videolist/new/".$container."/title_videourl/".$embedidArray[0]."/page/".$queryCatgory."\");\"><input type=\"button\" name=\"add\" value=\"Add\" class=\"submit_button\"></a></td>";

						$body .=  '</tr>';
						$body .=  '</table>';
						$body .= '</div>';
				}
						$counter++;
			}
			$body .=  '</div>';
			print $body;
    }
}
exit;
?>
