<?php /* framework/application/command_line/Media/VideoUpload.php */


# Put the keys into an array.
$keys=explode('|', $argv[1]);
# Put the values into an array.
$values=explode('|', $argv[2]);
# Combine the keys and values arrays.
$passed_data=array_combine($keys, $values);

$session_path=$passed_data['SessionPath'];
$session_id=$passed_data['SessionId'];

# Need these for database_definitions.php and email_definitions.php
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('DOMAIN_NAME')) define('DOMAIN_NAME', $passed_data['Environment']);
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('DEVELOPMENT_DOMAIN')) define('DEVELOPMENT_DOMAIN', $passed_data['DevEnvironment']);
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('STAGING_DOMAIN')) define('STAGING_DOMAIN', $passed_data['StagingEnvironment']);
# Need this for YouTube Redirect URL ($yt=$video_obj->getYouTubeObject(FULL_DOMAIN);).
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
require_once Utility::locateFile(MODULES.'Database'.DS.'ezdb.class.php');
DB::init(DB_TYPE);
$db=DB::get_instance();
$db->quick_connect(DBUSER, DBPASS, DBASE, HOSTNAME);

# Get the session data.
$session=Utility::returnSessionData($session_id, $session_path);
$audio_data=$session['video_upload'];

# Get the Audio Class.
//require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
# Instantiate the new Video object.
//$video_obj=new Video();
# Get the Soundcloud instance. Starts the SoundcloudService if it's not already started.
//$soundcloud_obj=$video_obj->getSoundcloudObject(FULL_DOMAIN);

# Get the YouTubeUpload class.
require_once Utility::locateFile(MODULES.'media'.DS.'YouTubeUpload.php');

# If there is an audio file.
if(!empty($audio_data['FileName']))
{
	if(isset($audio_data['InsertID']))
	{
		# Set the path to the audio on the server.
		$audio_path=BODEGA.'audio'.DS.$audio_data['FileName'];

		if(empty($audio_data['ImageID']))
		{
			# Get the getID3 Class.
			require_once Utility::locateFile(MODULES.'Vendor'.DS.'getID3'.DS.'getid3'.DS.'getid3.php');
			# Instantiate the new getID3 object.
			$getID3=new getID3;

			# Remove the file extension.
			$thumbnail_no_ext=substr($audio_data['FileName'], 0, strrpos($audio_data['FileName'], '.'));

			# Set the path to the original thumbnail on the server.
			$original_thumbnail=IMAGES_PATH.'original'.DS.$thumbnail_no_ext.'.jpg';

			$audio_file_info=$getID3->analyze($audio_path);
			if(isset($audio_file_info['comments']['picture'][0]))
			{
				# Get image data.
				$thumbnail_data=$audio_file_info['comments']['picture'][0]['data'];

				# Create original thumbnail image.
				file_put_contents($original_thumbnail, $thumbnail_data);
			}

			if(file_exists($original_thumbnail))
			{
				# Get the FileHandler Class.
				require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
				# Instantiate the new FileHandler object.
				$file_handler=new FileHandler();

				# Resize the image and save the new image to the target folder.
				$resize_image=$file_handler->reduceImage($original_thumbnail, IMAGES_PATH.$thumbnail_no_ext.'.jpg', '320', '180', '75', FALSE);

				# Insert the thumbnail image into the `images` table.
				$sql='INSERT INTO `'.DBPREFIX.'images` ('.
					'`title`, '.
					'`image`, '.
					'`category`, '.
					' `contributor`'.
					') VALUES ('.
					$db->quote($db->escape(str_ireplace(DOMAIN_NAME, '%{domain_name}', $audio_data['Title']))).', '.
					$db->quote($db->escape($thumbnail_no_ext.'.jpg')).', '.
					$db->quote($db->escape($audio_data['Playlists'])).', '.
					$db->quote($audio_data['ContID']).
					')';
				# Run the SQL query.
				$db->query($sql);

				# Assign the image ID to a variable.
				$image_id=$db->get_insert_id();

				# Update the audion file to insert the image ID.
				$db->query('UPDATE `'.DBPREFIX.'audio` SET `image` = '.$db->escape($image_id).' WHERE `id` = '.$db->quote($audio_data['InsertID']).' LIMIT 1');
			}
		}
	}
	/*
	# Want to move editing audio here.
	elseif(isset($audio_data['ID']))
	{
		# Get new uploaded videos from the database.
		$video_row=$db->get_row('SELECT `api` FROM `'.DBPREFIX.'videos` WHERE `id` = '.$video_data['ID']);

		# Decode the `api` field.
		$api_decoded=json_decode($video_row->api);
		# Get the YouTube Video ID.
		$video_yt_id=$api_decoded->youtube_id;

		# Create a video list request.
		$listResponse=$yt->listVideos('snippet,status', array('id' => $video_yt_id));
		$videoList=$listResponse['items'];

		# Since a unique video id is given, it will only return 1 video.
		$video=$videoList[0];

		# Associate the snippet and status objects with a new video resource.
		$google_video=new Google_Service_YouTube_Video();
		$google_video->setSnippet($google_video_snippet);
		$google_video->setStatus($google_video_status);

		# Setting the defer flag to true tells the client to return a request which can be called
		# with ->execute(); instead of making the API call immediately.
		$client->setDefer(TRUE);

		$update_response=$yt->updateVideo('snippet,status', $updateVideo);

		# If you want to make other calls after the file upload, set setDefer back to false
		$client->setDefer(FALSE);
	}
	*/
}