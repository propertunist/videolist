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
		alert(JSON.stringify(result));
		$('[name="videotype"]').val(result.videotype);
		$('[name="thumbnail"]').val(result.data["thumbnail"]);
		$('[name="title"]').val(result.data["title"][0]);
		var description = result.data["description"];
		if (typeof tinyMCE == "object") {
			tinyMCE.activeEditor.setContent(description);
		} else {
			$('[name="description"]').html(description);
		}
		
		$('#videolist-metadata').show();
		$('#videolist-continue-button').hide();
		$('#videolist-submit-button').show();
	}	
}
elgg.register_hook_handler('init', 'system', elgg.videolist.init);
//</script>
