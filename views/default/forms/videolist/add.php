<?php

/**
 * Elgg Video Plugin
 * This plugin allows users to create a library of youtube/vimeo/metacafe videos
 * @file - the add user interface
 * @package Elgg
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Prateek Choudhary <synapticfield@gmail.com>
 * @copyright Prateek Choudhary
 */
// Make sure we're logged in (send us to the front page if not)
gatekeeper();
$page_owner = page_owner_entity();
$error = array(
							'no-video' => 1
							);
$error_msg = array(
							'no-video' => "Please enter a valid video url"
							);

$container_guid = get_input("container_guid");
set_page_owner($container_guid);

$confirm_action = get_input('video_action');
$guid = get_input('guid');
if(page_owner_entity() instanceof ElggGroup){
	//if in a group, set the access level to default to the group
	$access_id = page_owner_entity()->group_acl;
}else{
	$access_id = get_default_access(get_loggedin_user());
}
//if it is a group, pull out the group access view
if(page_owner_entity() instanceof ElggGroup){
	$options = group_access_options(page_owner_entity());
}else{
	$options = '';
}
$title_videourl = get_input('title_videourl');
$Pagecontainer = get_input('page');
$get_addvideourl = get_input('add_videourl');
$timestamp = time();
$token = generate_action_token(time());
if (!empty($get_addvideourl) && ($Pagecontainer == "youtube")) {
	$title_add_videourl = "http://www.youtube.com/watch?v=".$get_addvideourl;
} else if(!empty($get_addvideourl) && ($Pagecontainer == "metacafe")) {
	$title_add_videourl = "http://www.metacafe.com/api/item/".$get_addvideourl;
} else if(!empty($get_addvideourl) && ($Pagecontainer == "vimeo")) {
	$title_add_videourl = "http://vimeo.com/".$get_addvideourl;
} else {
	$title_add_videourl = "";
}

$tags = get_input('videolisttags');


$body = '<form action="'.$_SERVER['php_self'].'" method="post" id="add_video_form">';
$body .= elgg_view('input/hidden',array('internalname'=>'video_action', 'value'=>'add_video'));
$body .= elgg_view('input/hidden',array('internalname'=>'guid', 'value'=>$vars['guid']));


$body .= '<p><label>'.elgg_echo("videolist:title_videourl").'<br />';
$body .= elgg_view("input/text",array('internalname' => 'title_videourl','value'=>$title_add_videourl));
if($error['no-video'] == 0) {
	$body .= '<div class="videolist_error">'.$error_msg['no-video'].'</div>';
}
$body .= '</label></p>';

$body .= '<p><label>'.elgg_echo('videolist:tags');
$body .= elgg_view('input/tags', array('internalname' => 'videolisttags', 'value' => $tags));
$body .= '</label></p>';

$body .= '<p><label>'.elgg_echo("videolist:title_access").'<br />';
$body .= elgg_view('input/access',array('internalname'=>'access_id', 'value' => $access_id, 'options' => $options));
$body .= '</label></p>';
$body .= elgg_view('input/submit', array('internalname'=>'submit','value'=>elgg_echo('videolist:submit')));
$body .= '</form>';

print $body;
