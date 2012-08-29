<?php

$embedurl = $vars['entity']->embedurl;
$video_id = $vars['entity']->video_id;
$width = $vars['width'];
$height = $vars['height'];

$attrs = elgg_format_attributes(array(
	'flashvars' => 'playerVars=autoPlay=no',
	'src' => $embedurl,
	'width' => $width,
	'height' => $height,
	'wmode' => 'transparent',
	'allowFullScreen' => 'true',
	'allowScriptAccess' => 'always',
	'name' => "Metacafe_$video_id",
	'pluginspage' => 'http://www.macromedia.com/go/getflashplayer',
	'type' => 'application/x-shockwave-flash',
));

echo "<embed $attrs></embed>";
