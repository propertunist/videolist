<?php
/**
 * List of oembed providers
 *
 * To alter this list, register for the plugin hook ['videolist:prepare', 'oembed_list']
 */

return array(
	//   [type,           JSON-emitting endpoint,                    item url pattern]
	array('viddler',      'http://lab.viddler.com/services/oembed/', '~^http\\://([a-z0-9]+\\.)*viddler\\.com/.+~'  ),
	array('hulu',         'http://www.hulu.com/api/oembed.json',     '~^http\\://www\\.hulu\\.com/watch/.+~'        ),
	array('revision3',    'http://revision3.com/api/oembed/',        '~^http\\://([a-z0-9]+\\.)*revision3\\.com/.+~'),
	array('collegehumor', 'http://www.collegehumor.com/oembed.json', '~^http\\://www\\.collegehumor\\.com/video/.+~'),
	array('slideshare',   'http://www.slideshare.net/api/oembed/2',  '~^http\\://www\\.slideshare\\.net/[^/]+/.+~'  ),
);
