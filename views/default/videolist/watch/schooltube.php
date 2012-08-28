<?php

$video_id = $vars['entity']->video_id;
$width = $vars['width'];
$height = $vars['height'];

echo "<iframe src=\"http://www.schooltube.com/embed/$video_id\" width=\"$width\" height=\"$height\" frameborder=\"0\" webkitAllowFullScreen allowFullScreen></iframe>";
