<?php
/**
 * Elgg videolist english language pack.
 *
 * @package ElggVideolist
 */

return array(

	/**
	 * Menu items and titles
	 */

	'videolist' => "Videos",
	'videolist:owner' => "%s's videos",
	'videolist:friends' => "Friends' videos",
	'videolist:all' => "All site videos",
	'videolist:add' => "Add video",

	'videolist:group' => "Group videos",
	'groups:enablevideolist' => 'Enable group videos',

	'videolist:edit' => "Edit this video",
	'videolist:delete' => "Delete this video",

	'videolist:new' => "A new video",
	'notification:summary:subject:object:videolist_item' => "New video added: %s",
  'notification:summary:create:object:videolist_item' => "New video called %s",
	'videolist:notification:body' => '%s added a new video:

%s
%s

View and comment on the new video:
%s
',
	'videolist:delete:confirm' => 'Are you sure you want to delete this video?',
	'item:object:videolist_item' => 'Video',
	'videolist:nogroup' => 'This group does not have any video yet',
	'videolist:more' => 'More videos',
	'videolist:none' => 'No videos posted yet.',

	/**
	* River
	**/

	'river:create:object:videolist_item' => '%s created the video %s',
	'river:update:object:videolist_item' => '%s updated the video %s',
	'river:comment:object:videolist_item' => '%s commented on the video titled %s',

	/**
	 * Form fields
	 */

	'videolist:title' => 'Title',
	'videolist:description' => 'Description',
	'videolist:video_url' => 'Enter video URL',
	'videolist:access_id' => 'Who can see you posted this video?',
	'videolist:tags' => 'Add tags',

	/**
	 * Status and error messages
	 */
	'videolist:error:no_save' => 'There was an error in saving the video, please try after sometime',
	'videolist:saved' => 'Your video has been saved successfully!',
	'videolist:deleted' => 'Your video was removed successfully!',
	'videolist:deletefailed' => 'Unfortunately, this video could not be removed now. Please try again later',
        'videolist:error:no_url' => 'No URL was supplied',
        'videolist:error:invalid_url' => 'That URL is not for a supported video site',
        'videolist:error:empty_provider_data' => 'No data could be retrieved for this video',


	/**
	 * Widget
	 **/

	'videolist:num_videos' => 'Number of videos to display',
	'videolist:widget:description' => 'Your personal video playlist.',
	'videolist:continue' => "Continue",


        /**
         * Admin
         */

        'videolist:admin:title' => 'Configuration options for videolist',
        'videolist:admin:title:api' => 'API configuration',
        'videolist:admin:youtube_api' => 'Google / Youtube',
				'admin:upgrades:videolist_move_icons:description' => 'Convert Videolist icons to the Elgg 2.2+ icon format.',
				'admin:upgrades:videolist_move_icons' => 'Videolist Icon Upgrade',
);
