<?php
/**
* Elgg Video Plugin > Edit view
*/
// Make sure we're logged in (send us to the front page if not)
gatekeeper();
$page_owner = page_owner_entity();
$container_guid = $vars['entity']->container_guid;
$owner = get_entity($container_guid);
if($owner instanceof ElggGroup){
	$options = group_access_options($owner);
}else{
	$options = '';
}
?>

<form action="<?php echo $vars['url']; ?>action/videolist/edit" enctype="multipart/form-data" method="post" id="edit_video_form">

	<p><label><?php echo elgg_echo("title"); ?><br />
		<?php echo elgg_view("input/text", array("internalname" => "title_videourl","value" => $vars['entity']->title));?>
	</label></p>
	
	<p><label><?php echo elgg_echo("tags"); ?><br />
		<?php echo elgg_view("input/tags", array("internalname" => "tags","value" => $vars['entity']->tags));?>
	</label></p>
	
	<p><label><?php echo elgg_echo('access'); ?><br />
		<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $vars['entity']->access_id, 'options' => $options)); ?>
	</label></p>
	
	<p>
	<?php
		echo "<input type='hidden' name=\"container_guid\" value=\"{$container_guid}\" />";
		
		if (isset($vars['entity'])) {
			echo "<input type='hidden' name=\"video_guid\" value=\"{$vars['entity']->getGUID()}\" />";
		}
		echo elgg_view('input/securitytoken');
	?>
		<input type="submit" value="<?php echo elgg_echo("save"); ?>" />
	</p>

</form>