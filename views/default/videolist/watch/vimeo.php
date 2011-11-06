<?php

$video_id = $vars['video_id'];
$width = $vars['width'];
$height = $vars['height'];

echo "<iframe src=\"http://player.vimeo.com/video/$video_id?byline=0&amp;color=e11531&amp;autoplay=1\" width=\"$width\" height=\"$height\" frameborder=\"0\" webkitAllowFullScreen allowFullScreen></iframe>";
