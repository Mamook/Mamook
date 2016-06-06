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

# Get the session data.
$session=Utility::returnSessionData($session_id, $session_path);
$video_data=$session['video_upload'];

# Check if the YouTube credentials are available.
if($video_data['YouTube']==='post_youtube')
{
	# Get CommandLine class.
	require_once Utility::locateFile(MODULES.'CommandLine'.DS.'CommandLine.php');
	# Instantiate the new CommandLine object.
	$cl=new CommandLine();
	# Run the upload script.
	$cl->runScript(Utility::locateFile(COMMAND_LINE.'Media'.DS.'YouTubeUpload.php'), $passed_data);
}

# If there is a video file.
if(!empty($video_data['FileName']))
{
	# This is a new video (not being editted).
	if(isset($video_data['NewVideo']) && $video_data['NewVideo']===TRUE)
	{
		# Set the path to the video on the server.
		$video_path=BODEGA.'video'.DS.$video_data['FileName'];
		if(empty($video_data['ImageID']))
		{
			# Remove the file extension.
			$thumbnail_no_ext=substr($video_data['FileName'], 0, strrpos($video_data['FileName'], '.'));
			# Set the path to the original thumbnail on the server.
			$original_thumbnail=IMAGES_PATH.'original'.DS.$thumbnail_no_ext.'.jpg';
			if(file_exists($original_thumbnail))
			{
				# Create an empty variable for the category id's.
				$category_ids=NULL;
				# Check if there are categories.
				if(!empty($video_data['Categories']))
				{
					# Change the values for the id's.
					$categories=array_flip($video_data['Categories']);
					# Separate the category id's with dashes (-).
					$category_ids='-'.implode('-', $categories).'-';
				}
				# Get the FileHandler Class.
				require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
				# Instantiate the new FileHandler object.
				$file_handler=new FileHandler();
				# Resize the image and save the new image to the target folder.
				$resize_image=$file_handler->reduceImage($original_thumbnail, IMAGES_PATH.$thumbnail_no_ext.'.jpg', '320', '180', '75', FALSE);
				# Insert the thumbnail image into the `images` table.
				$sql='INSERT INTO `'.DBPREFIX.'images` ('.
					'`title`,'.
					' `image`,'.
					((!empty($category_ids)) ? ' `category`,' : '').
					' `contributor`'.
					') VALUES ('.
					$db->quote($db->escape(str_ireplace(DOMAIN_NAME, '%{domain_name}', $video_data['Title']))).', '.
					$db->quote($db->escape($thumbnail_no_ext.'.jpg')).', '.
					((!empty($category_ids)) ? $db->quote($category_ids).', ' : '').
					$db->quote($video_data['ContID']).
					')';
				# Run the SQL query.
				$db->query($sql);
				# Assign the image ID to a variable.
				$image_id=$db->get_insert_id();
				# Update the videon file to insert the image ID.
				$db->query('UPDATE `'.DBPREFIX.'videos` SET `image`='.$db->escape($image_id).' WHERE `id`='.$db->quote($video_data['ID']).' LIMIT 1');
			}
		}
	}
	/*
	# Want to move editing video here.
	if((!isset($video_data['NewVideo'])) || (isset($video_data['NewVideo']) && $video_data['NewVideo']===FALSE))
	{
		# Get new uploaded videos from the database.
		$video_row=$db->get_row('SELECT `api` FROM `'.DBPREFIX.'videos` WHERE `id`='.$video_data['ID']);
		# Decode the `api` field.
		$api_decoded=json_decode($video_row->api);
		# Get the YouTube Video ID.
		$video_yt_id=$api_decoded->youtube_id;
		# Create a video list request.
		$listResponse=$youtube_obj->listVideos('snippet,status', array('id'=>$video_yt_id));
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
		$update_response=$youtube_obj->updateVideo('snippet,status', $updateVideo);
		# If you want to make other calls after the file upload, set setDefer back to false
		$client->setDefer(FALSE);
	}
	*/
}
# Remove the video upload session.
unset($video_data['video_upload']);