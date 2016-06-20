<?php

$vars['src'] = "https://www.youtube-nocookie.com/embed/" . $vars['entity']->video_id . "?html5=1";
echo elgg_view('videolist/iframe', $vars);
