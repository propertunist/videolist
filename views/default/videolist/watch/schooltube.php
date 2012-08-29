<?php

$vars['src'] = "http://www.schooltube.com/embed/" . $vars['entity']->video_id;
echo elgg_view('videolist/iframe', $vars);
