<?php
/**
 * Videolist edit form body
 *
 * @package ElggVideolist
 */

elgg_load_js('elgg.videolist');

$variables = elgg_get_config('videolist');

unset($variables['video_url']);

$vars['videolist_variables'] = $variables;

$input_bit = elgg_view('videolist/input_bit',$vars);

if(empty($vars['guid'])){
	// add title and description fields in a hidden section to be revealed later by JS
	// and videotype, thumbnail as hidden fields
?>
<div>
<label><?php echo elgg_echo("videolist:video_url") ?></label><br />
<?php
	echo elgg_view("input/text", array(
		'name' => 'video_url',
		'value' => $vars['video_url'],
	));
?>
</div>
<div id="videolist-metadata" class="hidden">
	<?php echo $input_bit;?>
</div>
<?php
} else {
	echo $input_bit;
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
