<?php
	 /**
	 * Elgg Videolist Plugin
	 * This plugin allows users to create a library of youtube videos
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Prateek Choudhary <synapticfield@gmail.com>
	 * @copyright Prateek Choudhary
	 */

	 if ($foreach = get_entities('object','',$vars['entity']->guid)) {
	 	foreach($foreach as $videos)
	 		echo elgg_view_entity($videos);
	 }
	 
?>
