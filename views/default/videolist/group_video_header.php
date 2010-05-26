<?php
/**
* Page header view, when visiting a group's videos
**/
 
$user = page_owner_entity();
$user_name = elgg_view_title($user->name . "'s " . elgg_echo('videos'));
$url = $CONFIG->wwwroot . "pg/videolist/browse/". $user->username . "/";
if(isloggedin())	
	$upload_link = "<a href=\"{$url}\" class='action_button'>" . elgg_echo('videolist:browsemenu') . '</a>';
else
	$upload_link = '';
?>
<div id="content_header" class="clearfloat">
	<div class="content_header_title">
		<?php echo $user_name; ?>
	</div>
	<div class="content_header_options">
		<?php echo $upload_link; ?>
	</div>
</div>