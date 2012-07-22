<?php 
// load json2 to support older browsers
elgg_load_js('elgg.videolist.json2');
?>
//<script>
elgg.provide('elgg.videolist');

elgg.videolist.init = function () {
	$('#videolist-continue-button').click(elgg.videolist.getMetadata);
}

elgg.videolist.getMetadata = function(e) {
	elgg.action('videolist/get_metadata_from_url', {data: {url: $('[name="video_url"]').val()}, success: elgg.videolist.handleMetadata});
	e.preventDefault();
	return false;
}

elgg.videolist.handleMetadata = function(result) {
	if (result.error) {
		elgg.register_error(result.msg);
	} else {
		$('[name="videotype"]').val(result.videotype);
		// populate any input fields that exist with data from the video provider
		$.each(result.data, function(k,v) {
			if ($('[name="'+k+'"]').length > 0) {
				// flatten arrays and objects just in case
				// for example, Youtube returns the title as an object indexed by 0
				if (Object.prototype.toString.call( v ) === '[object Array]') {
					if (v.length > 0) {
						$('[name="'+k+'"]').val(v[0])
					}
				} else if (typeof v == 'object') {
					$('[name="'+k+'"]').val(v[0])
				} else {
					$('[name="'+k+'"]').val(v)
				}
			}
		});
		// special handing for TinyMCE's description field
		var description = result.data["description"];
		if (typeof tinyMCE == "object") {
			tinyMCE.activeEditor.setContent(description);
		}
		// we also return the video data as a JSON string
		$('[name="video_data"]').val(JSON.stringify(result.data));
		
		$('#videolist-metadata').show();
		$('#videolist-continue-button').hide();
		$('#videolist-submit-button').show();
	}	
}
elgg.register_hook_handler('init', 'system', elgg.videolist.init);
//</script>
