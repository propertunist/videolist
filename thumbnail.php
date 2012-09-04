<?php
/**
 * Elgg file thumbnail
 *
 * @package ElggFile
 */

// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get videolist item GUID
$guid = (int) get_input('guid', 0);

// Get file thumbnail size
$size = get_input('size', 'small');

$item = get_entity($guid);
if (!elgg_instanceof($item, 'object', 'videolist_item')) {
	exit;
}

$readfile = new ElggFile();
$readfile->owner_guid = $item->owner_guid;
$readfile->setFilename("videolist/{$item->guid}.jpg");
if ($readfile->exists()) {
	$contents = $readfile->grabFile();
	$content_length = strlen($contents);
	header("Content-type: image/jpeg");

	// cache image for 10 days
	header('Expires: ' . date('r', time() + 864000));
	header("Pragma: public", true);
	header("Cache-Control: public", true);
} else {
	// icon was deleted

	// stop using this script to fetch thumb
	$ignore_access = elgg_set_ignore_access(true);
	$item->deleteMetadata('thumbnail');
	elgg_set_ignore_access($ignore_access);

	if (in_array($size, array('tiny', 'medium', 'small'))) {
		$filename = elgg_get_plugins_path() . "videolist/graphics/videolist_icon_$size.png";
		$contents = file_get_contents($filename);
		$content_length = strlen($contents);
		header("Content-type: image/jpeg");
	} else {
		header('Status: 404 Not Found');
		exit;
	}
}

header("Content-Length: $content_length");
echo $contents;
exit;
