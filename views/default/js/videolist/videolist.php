<?php 
// load json2 to support older browsers
elgg_load_js('elgg.videolist.json2');
?>
//<script>
elgg.provide('elgg.videolist');

elgg.videolist.init = function () {
	$('#videolist-continue-button').click(elgg.videolist.getMetadata);
};

elgg.videolist.getMetadata = function(e) {
	elgg.action('videolist/get_metadata_from_url', {
		data: { url: $('[name="video_url"]').val() },
		success: elgg.videolist.handleMetadata
	});
	e.preventDefault();
	return false;
};

elgg.videolist.handleMetadata = function(result) {
	if (result.error) {
		elgg.register_error(result.msg);
	} else {
		// populate any input fields that exist with data from the video provider
		$.each(result.data, function(k, v) {
			var $input = $('[name="' + k + '"]');
			if ($input.length > 0) {
				// flatten arrays and objects just in case
				// for example, Youtube returns the title as an object indexed by 0
				if (Object.prototype.toString.call( v ) === '[object Array]') {
					if (v.length > 0) {
						$input.val(v[0]);
					}
				} else if (typeof v == 'object') {
					$input.val(v[0]);
				} else {
					$input.val(v);
				}
			}
			$('input[name="video_url"]').prop('readonly', true);
		});
		// special handing for TinyMCE's description field
		var description = result.data["description"];
		if (window.tinyMCE) {
			tinyMCE.activeEditor.setContent(description);
		}
		$('#videolist-metadata').show().removeClass('hidden');
		$('#videolist-continue-button').hide();
		$('#videolist-submit-button').show();
		$('input[name="title"]')[0].focus();
	}	
};

elgg.register_hook_handler('init', 'system', elgg.videolist.init);
//</script>
