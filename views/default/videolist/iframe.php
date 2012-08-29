<?php

$attrs = elgg_format_attributes(array(
	'width' => $vars['width'],
	'height' => $vars['height'],
	'src' => $vars['src'],
	'frameborder' => '0',
));

echo "<iframe $attrs allowFullScreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>";
