<?php
/**
	 * Elgg Videolist Plugin
	 * This plugin allows users to create a library of youtube videos
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Prateek Choudhary <synapticfield@gmail.com>
	 * @copyright Prateek Choudhary
	 */

	
	//the number of files to display
	$number = (int) $vars['entity']->num_display;
	if (!$number)
		$number = 5;
	
	$owner = page_owner_entity();	
	$owner_videos = get_entities("object", "videolist", page_owner(), $order_by="time_created desc", $limit=$number);
	//echo "<div class=\"clearfloat\"></div>";
	if ($owner_videos) {
	  echo '<div id="profile_video_widget_container">';
		foreach($owner_videos as $videos){
			$url = $videos->url;
			$title = $videos->title;
			$video_id = $videos->video_id;
			$videothumbnail = $videos->thumbnail;
			echo '<div id="profile_video_image_container">';
				//get video cover image
				echo '<div id="videothumbnail-box">';
					echo "<a href='".$vars['url']."pg/videolist/watch/".$videos->guid."'>";
						echo "<img src=\"".$videothumbnail."\" width=\"75\"/>";
			  	echo '</a>';
			  echo '</div>';
			  	echo '<div id="videotitle-box">';
			  		echo "<a href='".$vars['url']."pg/videolist/watch/".$videos->guid."'>";
			  			echo $title;
			  		echo '</a>';
			  		$numcomments = elgg_count_comments($videos);
						if ($numcomments)
						echo "<br /><span class='vid-comment-widget'><a href=\"".$vars['url']."pg/videolist/watch/".$videos->guid."\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a></span> <br />";
		    	echo '</div>';
				echo '</div>';
			}
			echo "</div>";	      	
      echo "<div class=\"clearfloat\"></div>";
      //get a link to the users videos
      $users_video_url = $vars['url'] . "pg/videolist/owned/" . $owner->username;
      echo "<div style=\"margin-left:10px;\">";
      echo "<span class=\"profile_album_link\"><a href=\"{$users_video_url}\">" . elgg_echo('video:more') . "</a></span>";
      echo "</div>";	   
      echo "<div id=\"widget-boundary\"></div>";		
		}
		else {
			echo elgg_echo("album:none");
		}

?>
