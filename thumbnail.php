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
if (!$item || $item->getSubtype() != "videolist_item") {
	exit;
}

$readfile = new ElggFile();
$readfile->owner_guid = $item->owner_guid;
$readfile->setFilename("videolist/{$item->guid}.jpg");
$contents = $readfile->grabFile();

// caching images for 10 days
header("Content-type: image/jpeg");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . strlen($contents));

echo $contents;
exit;
