<?php
# TODO: Check if video is no longer in the playlist. If it's not. then remove the playlist ID from the `playlist` field.

# Need these for database_definitions.php and email_definitions.php
# Only need to change the RUN_ON_DEVELOPMENT definition.
# TRUE if you want this script to work on your Development machine, FALSE for Staging and Production.
if(!defined('RUN_ON_DEVELOPMENT')) define('RUN_ON_DEVELOPMENT', FALSE);
define('RUN_ON_STAGING', FALSE);
//define('RUN_ON_STAGING', ((RUN_ON_DEVELOPMENT===TRUE) ? TRUE : TRUE));

# Need this for the database insert.
if(!defined('DOMAIN_NAME')) define('DOMAIN_NAME', 'jamtheforce.com');
# Need this for YouTube Redirect URL ($yt=$video_obj->getYouTubeObject(FULL_DOMAIN);).
if(!defined('FULL_DOMAIN')) define('FULL_DOMAIN', 'jamtheforce.com/');

# Need this for API_definitions.php
# The domain name of the developement application. (doesn't end with a slash)
define('DEVELOPMENT_DOMAIN', 'jamtheforce.dev');

# Change the directory to where this cron script is located.
chdir(dirname(__FILE__));

# Get the Path definitions.
require '../data/path_definitions.php';
# Get the database definitions.
require DATA_FILES.'database_definitions.php';
# Get the API definitions.
require DATA_FILES.'API_definitions.php';
# Get the Utility Class.
require_once MODULES.'Utility'.DS.'Utility.php';

# Get the DB Class needed to operate with MySQL.
require_once MODULES.'Database'.DS.'ezdb.class.php';
DB::init();
$db=DB::get_instance();
$db->quick_connect(DBUSER, DBPASS, DBASE, HOSTNAME);

# Get the Video Class.
require_once MODULES.'Media'.DS.'Video.php';
# Instantiate the new Video object.
$video_obj=new Video();
# Get the YouTube instance. Starts the YouTubeService if it's not already started.
$yt=$video_obj->getYouTubeObject(FULL_DOMAIN);

# Get the Category class.
require_once MODULES.'Content'.DS.'Category.php';
# Instantiate a new Category object.
$playlist_obj=new Category();
# get the categories from the `categories` table.
$playlist_obj->getCategories(NULL, '`id`, `api`', 'id', 'ASC', ' WHERE `product` IS NULL AND `api` IS NOT NULL');
# Set the playlists to a variable.
$all_playlists=$playlist_obj->getAllCategories();

foreach($all_playlists as $playlist_row)
{
	# Get the Videos from the database.
	$db_videos=$db->get_results('SELECT `api` FROM `'.DBPREFIX.'videos` WHERE `playlist` LIKE \'%-'.$playlist_row->id.'-%\'', ARRAY_A);

	# Decode the `api` field in the `categories` table.
	$playlist_api_decoded=json_decode($playlist_row->api);

	# Set the videos from the playlist on YouTube to a variable.
	$playlist_items=$yt->PlaylistItems($playlist_api_decoded->youtube_playlist_id);

	# If there are videos in this playlist and videos in the database for this playlist.
	if(!empty($db_videos))
	{
		# Go through the database videos array and only set the `youtube_id`.
		array_walk($db_videos, function(&$value, $index_not_used)
		{
			# Decode the `api` field in the `videos` table.
			$video_api_decoded=json_decode($value['api']);

			# Overwrite value
			$value=$video_api_decoded->youtube_id;
		});

		$filtered_array=array_filter($playlist_items, function($value) use($db_videos)
		{
			# Is the videoId from the playlist_item in the database array?
			$item_is_in_db=in_array($value['videoId'], $db_videos);

			# Return what's not in the database array.
			return !$item_is_in_db;
		});

		# If the filtered array is not empty.
		if(!empty($filtered_array))
		{
			foreach($filtered_array as $filtered)
			{
				# json_encode the YouTube ID so we can compare it to the `videos` table.
				$yt_api_encoded=json_encode(array('youtube_id' => $filtered['videoId']), JSON_FORCE_OBJECT);

				# Get video that matches the videoId on YouTube.
				$current_video=$db->get_row('SELECT `id`, `playlist` FROM `'.DBPREFIX.'videos` WHERE `api` LIKE \'%'.$yt_api_encoded.'%\' LIMIT 1');

				# If there are video's matching the playlist videos on YouTube.
				if(!empty($current_video))
				{
					# Add this playlist to the video.
					$update_video_playlist=$db->query('UPDATE `'.DBPREFIX.'videos` SET `playlist` = CONCAT(`playlist`, \''.$db->quote($db->escape($playlist_row->id)).'-\') WHERE `id` = '.$db->quote($current_video->id).' LIMIT 1');
				}
				else
				{
					# Create a video list request.
					$video_list_response=$yt->listVideos('snippet', array('id' => $filtered['videoId']));

					# If this video has not been deleted from YouTube.
					if(($filtered['title']!='Deleted Video') && ($video_list_response[0]['status']['embeddable']===TRUE))
					{
						# Convert the YouTube publishedAt value to insert into the database.
						$video_date=date('Y-m-d', strtotime($filtered['publishedAt']));

						# json_encode the YouTube ID and Thumbnail.
						$insert_json=json_encode(array('youtube_id' => $filtered['videoId'], 'youtube_thumbnails' => $filtered['thumbnails']), JSON_FORCE_OBJECT);

						# Insert video into the `videos` table.
						$insert_playlist_video=$db->query('INSERT INTO `videos` (`title`, `description`, `playlist`, `date`, `api`, `new`) VALUES ('.$db->quote($db->escape(str_ireplace(DOMAIN_NAME, '%{domain_name}', $filtered['title']))).', '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?\r?(\n\r)?)/i", "$1\n", str_replace(array("\r\n", "\n", "\r"), '<br />', htmlspecialchars_decode($filtered['description']))))).', \'-'.$db->quote($playlist_row->id).'-\', '.$db->quote($video_date).', '.$db->quote($insert_json).', 0)');
					}
				}
			}
		}
	}
	# If there are videos in this playlist but NO videos in the database for this playlist.
	if(!empty($playlist_items) && (empty($db_videos)))
	{
		# Get the Videos.
		$video_obj->getVideos(NULL, '`playlist`', 'id', 'DESC');
		# Set the returned Video records to a variable.
		$all_videos=$video_obj->getAllVideos();

		foreach($all_videos as $videos)
		{
			# Trim dashes(-) off both ends of the string.
			$value=trim($videos->playlist, '-');
			# Explode the string into an array.
			$playlists[]=explode('-', $value);
		}

		foreach($playlist_items as $playlist_key=>$playlist_value)
		{
			# json_encode the YouTube ID so we can compare it to the `videos` table. Trim the bracket off the end of the string.
			$yt_api_encoded=rtrim(json_encode(array('youtube_id' => $playlist_value['videoId']), JSON_FORCE_OBJECT), '}');

			# Get video that matches the videoId on YouTube.
			$current_playlists=$db->get_row('SELECT `id`, `playlist` FROM `'.DBPREFIX.'videos` WHERE `api` LIKE \'%'.$yt_api_encoded.'%\' LIMIT 1');

			# If there are video's matching the playlist videos on YouTube.
			if(isset($current_playlists))
			{
				# Add this playlist to the video.
				$update_video_playlist=$db->query('UPDATE `'.DBPREFIX.'videos` SET `playlist` = CONCAT(`playlist`, \''.$db->quote($db->escape($playlist_row->id)).'-\') WHERE `id` = '.$db->quote($current_playlists->id).' LIMIT 1');

				# Remove the video from the array in case the rest need to be added to the database.
				unset($playlist_items[$playlist_key]);
			}
		}

		# Loops through videos in the playlist on YouTube.
		foreach($playlist_items as $playlist_item)
		{
			# Create a video list request.
			$video_list_response=$yt->listVideos('snippet,status', array('id' => $playlist_item['videoId']));

			# If this video has not been deleted from YouTube.
			if(($playlist_item['title']!='Deleted Video') && ($video_list_response[0]['status']['embeddable']===TRUE))
			{
				# Convert the YouTube publishedAt value to insert into the database.
				$video_date=date('Y-m-d', strtotime($playlist_item['publishedAt']));

				# json_encode the YouTube ID and Thumbnails.
				$insert_json=json_encode(array('youtube_id' => $playlist_item['videoId'], 'youtube_thumbnails' => $playlist_item['thumbnails']), JSON_FORCE_OBJECT);

				# Insert video into the `videos` table.
				$insert_playlist_video=$db->query('INSERT INTO `videos` (`title`, `description`, `playlist`, `date`, `api`, `new`) VALUES ('.$db->quote($db->escape(str_ireplace(DOMAIN_NAME, '%{domain_name}', $playlist_item['title']))).', '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?\r?(\n\r)?)/i", "$1\n", str_replace(array("\r\n", "\n", "\r"), '<br />', htmlspecialchars_decode($playlist_item['description']))))).', \'-'.$db->quote($playlist_row->id).'-\', '.$db->quote($video_date).', '.$db->quote($insert_json).', 0)');
			}
		}
	}
}