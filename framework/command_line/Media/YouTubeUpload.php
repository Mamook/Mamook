<?php /* framework/command_line/Media/YouTubeUpload.php */

# Put the keys into an array.
$keys=explode('|', $argv[1]);
# Put the values into an array.
$values=explode('|', $argv[2]);
# Combine the keys and values arrays.
$passed_data=array_combine($keys, $values);

# Need these for database_definitions.php and email_definitions.php
# Need this for the Image class and APPLICATION_URL.
if(!defined('DOMAIN_NAME')) define('DOMAIN_NAME', $passed_data['Environment']);
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('DEVELOPMENT_DOMAIN')) define('DEVELOPMENT_DOMAIN', $passed_data['DevEnvironment']);
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('STAGING_DOMAIN')) define('STAGING_DOMAIN', $passed_data['StagingEnvironment']);
# Need this for YouTube Redirect URL ($youtube_obj=$video_obj->getYouTubeObject(FULL_DOMAIN);).
if(!defined('FULL_DOMAIN')) define('FULL_DOMAIN', DOMAIN_NAME.'/');
# Define the url that points to our application. (ends with a slash)
define('APPLICATION_URL', 'http://'.DOMAIN_NAME.'/');
# Set to TRUE to see the nasty errors for debugging, FALSE to hide them.
if(DOMAIN_NAME===DEVELOPMENT_DOMAIN)
{
	define('RUN_ON_DEVELOPMENT', TRUE);
}
else
{
	define('RUN_ON_DEVELOPMENT', FALSE);
}
# Set to TRUE if on the staging site, FALSE if not.
if(DOMAIN_NAME===STAGING_DOMAIN)
{
	define('RUN_ON_STAGING', TRUE);
}
else
{
	define('RUN_ON_STAGING', FALSE);
}
# The url where our secure pages are. (ie. https://sub.domain.com/folder/)
if(RUN_ON_STAGING===TRUE)
{
	define('SECURE_URL', APPLICATION_URL.'secure/');
}
else
{
	define('SECURE_URL', 'https://'.DOMAIN_NAME.'/secure/');
}

# Get the Path definitions.
require '../../../../../data/path_definitions.php';
# Get the database definitions.
require DATA_FILES.'database_definitions.php';
# Get the API definitions.
require DATA_FILES.'API_definitions.php';
# Get the Utility Class.
require UTILITY_CLASS;

# Get the DB Class needed to operate with MySQL.
require_once Utility::locateFile(MODULES.'Vendor'.DS.'ezDB'.DS.'ezdb.class.php');
DB::init(DB_TYPE);
$db=DB::get_instance();
$db->quick_connect(DBUSER, DBPASS, DBASE, HOSTNAME);

# Get the Video Class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
# Instantiate the new Video object.
$video_obj=Video::getInstance();
# Get the YouTube instance. Starts the YouTubeService if it's not already started.
$youtube_obj=$video_obj->getYouTubeObject(FULL_DOMAIN);
# Get Google Client
$client=$video_obj->getGoogleClient();

# Get the Validator Class.
require_once Utility::locateFile(MODULES.'Validator'.DS.'Validator.php');
# Get the Category class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
# Instantiate a new Category object.
$category_obj=new Category();
# Trim the first dash (-).
$trimmed_categories=ltrim($passed_data['Categories'], '-');
# Since YouTube only allows one category, get the first category.
#	Trim off the first dash (-) then get the string until the next dash.
$category_id=substr($trimmed_categories, 0, strpos($trimmed_categories, '-'));
# Get the category from the database.
$category_obj->getThisCategory($category_id);
# Decode the `api` field in the `categories` table.
$category_api_decoded=json_decode($category_obj->getAPI());
# If this category is on YouTube, get YouTube's ID for this category.
if(isset($category_api_decoded->YouTube->category_id))
{
	# Category ID on YouTube.
	$youtube_category_id=$category_api_decoded->YouTube->category_id;
}
# If there is a video file, not an embed code.
if(!empty($passed_data['FileName']))
{
	# Specify the size of each chunk of data, in bytes. Set a higher value for
	# reliable connection as fewer chunks lead to faster uploads. Set a lower
	# value for better recovery on less reliable connections.
	$chunk_size_bytes=2*1024*1024;

	# Create a snippet with title, description, tags and category ID
	# Create an asset resource and set its snippet metadata and type.
	# This example sets the video's title, description, keyword tags, and
	# video category.
	$google_video_snippet=new Google_Service_YouTube_VideoSnippet();
	$google_video_snippet->setTitle($passed_data['Title']);
	$google_video_snippet->setDescription($passed_data['Description']);
	//$google_video_snippet->setTags(array($youtube_category_id));

	# Numeric video category. See
	# https://developers.google.com/youtube/v3/docs/videoCategories/list
	# 	25 = News & Politics
	# 	27 = Education
	# 	29 = Nonprofits & Activism
	if(isset($youtube_category_id))
	{
		$google_video_snippet->setCategoryId($youtube_category_id);
	}

	# Create a video status with privacy status. Valid statuses are "public", "private" and "unlisted".
	$google_video_status=new Google_Service_YouTube_VideoStatus();

	# If "This site has the legal rights to display" (1).
	if($passed_data['Availability']==1) $privacy_setting='public';
	# If "This site does not yet have the lega rights to display" (0),
	#	"Internal Document Only" (2),
	#	"Can not distribute" (3).
	else $privacy_setting='unlisted';

	# Set video privacy.
	$google_video_status->privacyStatus=$privacy_setting;

	# If this is a new video (not editted).
	if(isset($passed_data['NewMedia']) && $passed_data['NewMedia']==TRUE)
	{
		# Set the path to the video on the server.
		$video_path=BODEGA.'videos'.DS.$passed_data['FileName'];

		# Associate the snippet and status objects with a new video resource.
		$google_video=new Google_Service_YouTube_Video();
		$google_video->setSnippet($google_video_snippet);
		$google_video->setStatus($google_video_status);

		# Setting the defer flag to true tells the client to return a request which can be called
		# with ->execute(); instead of making the API call immediately.
		$client->setDefer(TRUE);

		# Create a video insert request.
		$insert_response=$youtube_obj->insertVideo('status,snippet', $google_video);

		# Create a MediaFileUpload object for resumable uploads.
		$media_file_upload=new Google_Http_MediaFileUpload($client, $insert_response, 'video/*', NULL, TRUE, $chunk_size_bytes);
		$media_file_upload->setFileSize(filesize($video_path));

		# Set $upload_status to FALSE by default.
		$upload_status=FALSE;

		# Read the media file and upload it chunk by chunk.
		$handle=fopen($video_path, "rb");
		while(!$upload_status && !feof($handle))
		{
			$chunk=fread($handle, $chunk_size_bytes);
			$upload_status=$media_file_upload->nextChunk($chunk);
		}
		fclose($handle);

		# Get YouTube's ID for this video.
		$video_id=$upload_status['id'];

		# If there is a custom thumbnail image.
		if(!empty($passed_data['ImageID']))
		{
			# Get the image information from the database, and set them to data members.
			$video_obj->getThisImage($passed_data['ImageID']);
			# Set the Image object to a variable.
			$image_obj=$video_obj->getImageObj();
			# Set the path to the original thumbnail on the server.
			$original_thumbnail=IMAGES_PATH.'original'.DS.$image_obj->getImage();
			# Call the API's thumbnails.set method to upload the image and associate it with the appropriate video.
			$insert_thumbnail_response=$youtube_obj->insertThumbnail($video_id);
			# Create a MediaFileUpload object for resumable uploads.
			$media_thumbnail_upload=new Google_Http_MediaFileUpload($client, $insert_thumbnail_response, 'image/jpeg', NULL, TRUE, $chunk_size_bytes);
			$media_thumbnail_upload->setFileSize(filesize($original_thumbnail));
			# Set $thumbnail_upload_status to FALSE by default.
			$thumbnail_upload_status=FALSE;
			# Read file and upload chunk by chunk
			$thumbnail_handle=fopen($original_thumbnail, "rb");
			while(!$thumbnail_upload_status && !feof($thumbnail_handle))
			{
				$thumbnail_chunk=fread($thumbnail_handle, $chunk_size_bytes);
				$thumbnail_upload_status=$media_thumbnail_upload->nextChunk($thumbnail_chunk);
			}
			fclose($thumbnail_handle);
		}

		# If you want to make other calls after the file upload, set setDefer back to false
		$client->setDefer(FALSE);

		# Add the video to playlists.
		# Create a resource id with video id and kind.
		$resourceId=new Google_Service_YouTube_ResourceId();
		$resourceId->setVideoId($video_id);
		$resourceId->setKind('youtube#video');

		# Get the YouTube playlists from the database.
		$get_playlists=$db->get_results('SELECT `id`, `api` FROM `'.DBPREFIX.'playlists` WHERE `api` IS NOT NULL');
		# Trim the dashes off the playlist string.
		$playlists_string=trim($passed_data['Playlists'], '-');
		# Turn the string into an array.
		$playlists_array=explode('-', $playlists_string);
		# Loop through the playlists.
		foreach($get_playlists as $playlists)
		{
			# Find the playlist in the $passed_data array.
			if(in_array($playlists->id, $playlists_array))
			{
				# Decode the `api` field in the `playlists` table.
				$playlist_api_decoded=json_decode($playlists->api);
				# Create a snippet with resource id.
				$playlistItemSnippet=new Google_Service_YouTube_PlaylistItemSnippet();

				# TODO: If YouTube->playlist_id does not exist, create playlist, and update `playlist` table.

				$playlistItemSnippet->setPlaylistId($playlist_api_decoded->YouTube->playlist_id);
				$playlistItemSnippet->setResourceId($resourceId);
				# Create a playlist item request request with snippet.
				$playlistItem=new Google_Service_YouTube_PlaylistItem();
				$playlistItem->setSnippet($playlistItemSnippet);
				# Execute the request and return an object containing information about the new playlistItem.
				$playlistItemResponse=$youtube_obj->PlaylistItemsInsert('snippet,contentDetails', $playlistItem);
			}
		}
		# json_encode the YouTube ID.
		$insert_json=json_encode(array('youtube_id'=>$video_id), JSON_FORCE_OBJECT);
		# Insert the YouTube ID into the database entry.
		$db->query('UPDATE `'.DBPREFIX.'videos` SET `api`='.$db->quote($db->escape($insert_json)).' WHERE `id`='.$db->quote($passed_data['ID']).' LIMIT 1');
	}
	# Edit video on YouTube.
	if((!isset($passed_data['NewMedia'])) || (isset($passed_data['NewMedia']) && $passed_data['NewMedia']==FALSE))
	{
		# Get new uploaded videos from the database.
		$video_row=$db->get_row('SELECT `api` FROM `'.DBPREFIX.'videos` WHERE `id`='.$passed_data['ID']);

		# Decode the `api` field.
		$api_decoded=json_decode($video_row->api);
		# Get the YouTube Video ID.
		$video_yt_id=$api_decoded->youtube_id;

		# Create a video list request.
		$listResponse=$youtube_obj->listVideos('snippet,status', array('id'=>$video_yt_id));
		$videoList=$listResponse['items'];

		# Since a unique video id is given, it will only return 1 video.
		$video=$videoList[0];

		$videoSnippet=$video['snippet'];
		$videoStatus=$video['status'];

		if(isset($youtube_category_id))
		{
			$videoSnippet['categoryId']=$youtube_category_id;
		}
		$videoSnippet['description']=$passed_data['Description'];
		$videoSnippet['title']=$passed_data['Title'];
		$videoStatus['privacyStatus']=(($passed_data['Availability']==1) ? "public" : "private");

/*
		$tags=$videoSnippet['tags'];

		# Preserve any tags already associated with the video. If the video does
		# not have any tags, create a new list. Replace the values "tag1" and
		# "tag2" with the new tags you want to associate with the video.
		if(is_null($tags))
		{
			$tags=array("tag1", "tag2");
		}
		else
		{
			array_push($tags, "tag1", "tag2");
		}
		$videoSnippet['tags'] = $tags;
*/

		# Create a video update request.
		$update_response=$youtube_obj->updateVideo('snippet,status', $video);

		# If the image ID has been changed, then update the thumbnail on YouTube.
		if($passed_data['OldImageID']!=$passed_data['ImageID'])
		{
			# Setting the defer flag to true tells the client to return a request which can be called
			# with ->execute(); instead of making the API call immediately.
			$client->setDefer(TRUE);
			# Get the image information from the database, and set them to data members.
			$video_obj->getThisImage($passed_data['ImageID']);
			# Set the Image object to a variable.
			$image_obj=$video_obj->getImageObj();
			# Set the path to the original thumbnail on the server.
			$original_thumbnail=IMAGES_PATH.'original'.DS.$image_obj->getImage();
			# Call the API's thumbnails.set method to upload the image and associate it with the appropriate video.
			$insert_thumbnail_response=$youtube_obj->insertThumbnail($video_yt_id);
			# Create a MediaFileUpload object for resumable uploads.
			$media_thumbnail_upload=new Google_Http_MediaFileUpload($client, $insert_thumbnail_response, 'image/jpeg', NULL, TRUE, $chunk_size_bytes);
			$media_thumbnail_upload->setFileSize(filesize($original_thumbnail));
			# Set $thumbnail_upload_status to FALSE by default.
			$thumbnail_upload_status=FALSE;
			# Read file and upload chunk by chunk
			$thumbnail_handle=fopen($original_thumbnail, "rb");
			while(!$thumbnail_upload_status && !feof($thumbnail_handle))
			{
				$thumbnail_chunk=fread($thumbnail_handle, $chunk_size_bytes);
				$thumbnail_upload_status=$media_thumbnail_upload->nextChunk($thumbnail_chunk);
			}
			fclose($thumbnail_handle);
			# If you want to make other calls after the file upload, set setDefer back to false
			$client->setDefer(FALSE);
		}
	}
}