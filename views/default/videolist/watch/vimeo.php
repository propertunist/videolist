<?php

$vars['src'] = "//player.vimeo.com/video/" . $vars['entity']->video_id . "?byline=0";
echo elgg_view('videolist/iframe', $vars);
