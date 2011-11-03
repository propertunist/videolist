<?php
/**
 * Elgg tidypic icon
 * Optionally you can specify a size.
 *
 * @package ElggFile
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008
 * @link http://elgg.com/
 */

if($vars['videolist']){
	echo "<img src=\"".elgg_get_site_url()."mod/videolist/graphics/icons/Video_Icon.jpg\" border=\"0\" />";
} else {
	$mime = $vars['mimetype'];
	if (isset($vars['thumbnail'])) {
		$thumbnail = $vars['thumbnail'];
	} else {
		$thumbnail = false;
	}

	$size = $vars['size'];
	if ($size != 'large') {
		$size = 'small';
	}

	if ($thumbnail && strpos($mime, "image/")!==false) {
		echo "<img src=\"{$thumbnail}\" border=\"0\" />";
	} else {
		if ($size == 'large') {
			echo "<img src=\"{$thumbnail}\" border=\"0\" />";
		} else {
			echo "<img src=\"".elgg_get_site_url()."mod/videolist/graphics/icons/Video_Icon.jpg\" border=\"0\" />";
		}
	}
}
