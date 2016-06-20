<div class="videolist-admin-panel" style="padding:20px;">
<?php 
	$google_api_key = elgg_get_plugin_setting('google_API_key','videolist');
	if (!$google_api_key) 
	{
		$google_api_key = 'enter a google API key here';
		//elgg_set_plugin_setting('google_API_key',$google_api_key,'ureka_theme');
	}	

    echo "<h2>";
    echo elgg_echo('videolist:admin:title');
    echo "</h2>";
    echo '<br/><br/>';
    echo "<h3>";
    echo elgg_echo('videolist:admin:title:api');
    echo "</h3>";    
    echo '<br/><br/>';
    echo "<h4>";
    echo elgg_echo('videolist:admin:youtube_api') . ': ';
    echo "</h4>";
    echo elgg_view('input/text', array(
                'name' => 'params[google_API_key]',
                'value' => $google_api_key));
    echo '<br/><br/>';
	