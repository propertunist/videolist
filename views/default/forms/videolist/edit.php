<?php
/**
 * Videolist edit form body
 *
 * @package ElggVideolist
 */

elgg_load_js('elgg.videolist');

$variables = elgg_get_config('videolist');

if(empty($vars['guid'])){
	unset($variables['title']);
	unset($variables['description']);
} else {
	unset($variables['video_url']);
}

foreach ($variables as $name => $type) {
?>
<div>
	<label><?php echo elgg_echo("videolist:$name") ?></label>
	<?php
		if ($type != 'longtext') {
			echo '<br />';
		}
	?>
	<?php echo elgg_view("input/$type", array(
			'name' => $name,
			'value' => $vars[$name],
		));
	?>
</div>
<?php
}

if(empty($vars['guid'])){
	// add title and description fields in a hidden section to be revealed later by JS
	// and videotype, thumbnail as hidden fields
?>
<div id="videolist-metadata" style="display:none">
	<label><?php echo elgg_echo("videolist:title") ?></label><br />
	<?php echo elgg_view("input/text", array(
			'name' => 'title',
			'value' => $vars['title'],
		));
	?>
	<label><?php echo elgg_echo("videolist:description") ?></label>
	<?php 
		echo elgg_view("input/longtext", array(
			'name' => 'description',
			'value' => $vars['description'],
		));
		echo elgg_view("input/hidden", array(
			'name' => 'videotype',
		));
		echo elgg_view("input/hidden", array(
			'name' => 'thumbnail',
		));
	?>
</div>
<?php
}

$cats = elgg_view('categories', $vars);
if (!empty($cats)) {
	echo $cats;
}

echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'video_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if(empty($vars['guid'])){
	echo elgg_view('input/submit', array('id'=>'videolist-continue-button','value' => elgg_echo('videolist:continue')));
	echo elgg_view('input/submit', array('id'=>'videolist-submit-button','value' => elgg_echo('save'),'style'=>'display:none'));
} else {
	echo elgg_view('input/submit', array('id'=>'videolist-submit-button','value' => elgg_echo('save')));
}

echo '</div>';
