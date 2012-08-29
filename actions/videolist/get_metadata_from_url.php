<?php
elgg_load_library('elgg:videolist');
$url = get_input('url', '', false);
$url = elgg_trigger_plugin_hook('videolist:preprocess', 'url', array(), $url);
if (!$url) {
	$result = array(
		'error' => true,
		'msg' => elgg_echo('videolist:error:no_url'),
	);
} else {
	$attributesPlatform = videolist_parse_url($url);

	if (!$attributesPlatform) {
		$result = array(
			'error' => true,
			'msg' => elgg_echo('videolist:error:invalid_url'),
		);
	} else {
    	list ($attributes, $platform) = $attributesPlatform;
		/* @var Videolist_PlatformInterface $platform */
		$platform_data = $platform->getData($attributes);
		$result = array(
			'error' => false,
			'data' => array(
				'title' => $platform_data['title'],
				'description' => $platform_data['description'],
			),
		);
	}
}

echo json_encode($result);

exit;
