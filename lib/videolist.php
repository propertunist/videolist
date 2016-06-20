<?php

/**
 * @param array $hook_params
 * @return array
 */
function videolist_get_regular_platforms(array $hook_params) {
	$platforms = array();
	$path = dirname(dirname(__FILE__)) . '/classes/Videolist/Platform';
	foreach (scandir($path) as $filename) {
		if (preg_match('/^(\\w+)\\.php$/', $filename, $m)) {
			$class = 'Videolist_Platform_' . $m[1];
			$platform = new $class();
			if ($platform instanceof Videolist_PlatformInterface) {
				/* @var Videolist_PlatformInterface $platform */
				$platforms[$platform->getType()][] = $platform;
			}
		}
	}
	$platforms = elgg_trigger_plugin_hook('videolist:prepare', 'platforms', $hook_params, $platforms);
	return $platforms;
}

/**
 * @param array $platforms
 * @param string $url
 * @return array|bool
 */
function videolist_find_matching_platform(array $platforms, $url) {
	foreach ($platforms as $type => $list) {
		/* @var Videolist_PlatformInterface[] $list */
		foreach ($list as $platform) {
			$attributes = $platform->parseUrl($url);
			if ($attributes) {
				$attributes['videotype'] = $type;
				return array($attributes, $platform);
			}
		}
	}
	return false;
}

/**
 * @param string $url
 * @return array [array $attributes, Videolist_PlatformInterface $platform]
 */
function videolist_parse_url($url) {
	$params = array(
		'url' => $url,
	);
	$platforms = videolist_get_regular_platforms($params);
	if ($match = videolist_find_matching_platform($platforms, $url)) {
		return $match;
	}
	/* @var Videolist_PlatformInterface[] $list */
	$platforms = array();
	$list = (require dirname(__FILE__) . '/oembed_list.php');
	$list = elgg_trigger_plugin_hook('videolist:prepare', 'oembed_list', $params, $list);
	foreach ($list as $item) {
		// create only oembed platforms that will match
		if (preg_match($item[2], $url)) {
			$platform = new Videolist_OembedPlatform($item[0], $item[2], new Videolist_OembedService($item[1]));
			$platforms[$platform->getType()][] = $platform;
		}
	}
	if ($match = videolist_find_matching_platform($platforms, $url)) {
		return $match;
	}
	return false;
}

function videolist_remove_thumbnails($thumbnail, $entity_owner_guid) 
{
    //delete standard thumbnail image
    if ($thumbnail) 
    {
        $delfile = new ElggFile();
        $delfile->owner_guid = $entity_owner_guid;
        $delfile->setFilename($thumbnail);
        $delfile->delete();
    }
    return true;
}