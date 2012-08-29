<?php

$html = $vars['entity']->embed_html;
$width = $vars['width'];
$height = $vars['height'];

// at least try to change size
$html = preg_replace('~\\b(width="?)\\d+~', "\${1}$width", $html);
$html = preg_replace('~\\b(height="?)\\d+~', "\${1}$height", $html);

// yes, we're basically accepting markup as is from a 3rd party :/
echo $html;
