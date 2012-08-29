<?php

$vars['src'] = "https://www.youtube-nocookie.com/embed/" . $vars['entity']->video_id;
echo elgg_view('videolist/iframe', $vars);
