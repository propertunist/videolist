<?php
/**
 * Move all blog icons to the new (default) location
 *
 */

$path = 'admin/upgrades/videolist_move_icons';
$session = elgg_get_session();

$completed = (bool) get_input('upgrade_completed', false);
if ($completed) {
	// done with the upgrade
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath($path);
	if (!empty($upgrade)) {
		$session->remove('videolist_move_icon_offset');

		$upgrade->setCompleted();
		forward(REFERER);
	}
}

$error_offset = (int) get_input('offset');
$start_offset = (int) $session->get('videolist_move_icon_offset', 0);

$offset = $error_offset + $start_offset;

// get icon sizes for blogs
$icon_sizes = elgg_get_icon_sizes('object', 'blog');

$result = [
	'numSuccess' => 0,
	'numErrors' => 0,
];

// get blogs with icons
$batch = new ElggBatch('elgg_get_entities', [
	'type' => 'object',
	'subtype' => 'videolist_item',
	'limit' => 10, // using small limit because of icon resizing memory usage
	'offset' => $offset,
//	'metadata_name' => 'icontime',
]);
/* @var $entity \ElggBlog */
foreach ($batch as $entity) {

	$old_file = new ElggFile();
	$old_file->owner_guid = $entity->getOwnerGUID();
	$old_file->setFilename("videolist/{$entity->getGUID()}.jpg");

	if ((!$old_file->exists())&&($item->hasIcon('small'))) {
		// this video has already been upgraded
		// no move needed

		$result['numSuccess']++;
		continue;
	}

	if ($entity->saveIconFromElggFile($old_file)) {
		// moved icon to correct location
		$result['numSuccess']++;

		// cleanup old files
		if (!$old_file->exists()) {
				continue;
		}

		$old_file->delete();
	} else {
		// error occured
		$result['numErrors']++;
	}
}

// store the offset as Elgg doesn't report success
$session->set('videolist_move_icon_offset', ($start_offset + $result['numSuccess']));

// report status
echo json_encode($result);
