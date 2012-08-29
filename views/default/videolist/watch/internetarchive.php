<?php

$vars['src'] = "http://archive.org/embed/" . $vars['entity']->video_id;
echo elgg_view('videolist/iframe', $vars);
