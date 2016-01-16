<?php
# TODO: As we add more services (Vimeo) we'll check for rejection and if they're rejected from everything we'll stream from the server.
# TODO: Delete video from playlists when deleting from YouTube.

/**
 * This script runs every 10 minutes. Had to escape the forward slash.
 * *\/10      00      *       *       *       /opt/local/bin/php <Full Path to Cron Folder>/cron/new_media_Dev.php
 *
 * Edit the domains from jamtheforce.com/.dev to your domain.
 *
 * Use dev/new_mediac_Dev.php if you need this for your development machine.
 * Use dev/new_mediac_Staging.php if you need this for your staging server.
 */

# Need this for YouTube Redirect URL ($yt=$video_obj->getYouTubeObject(FULL_DOMAIN);).
if(!defined('FULL_DOMAIN')) define('FULL_DOMAIN', 'jamtheforce.com/');
# Need this for the database insert.
if(!defined('DOMAIN_NAME')) define('DOMAIN_NAME', 'jamtheforce.com');

# Need this for API_definitions.php
# The domain name of the developement application. (doesn't end with a slash)
define('DEVELOPMENT_DOMAIN', 'jamtheforce.dev');

# Need these for database_definitions.php and email_definitions.php
# Only need to change the RUN_ON_DEVELOPMENT definition.
# TRUE if you want this script to work on your Development machine, FALSE for Staging and Production.
if(!defined('RUN_ON_DEVELOPMENT')) define('RUN_ON_DEVELOPMENT', TRUE);
if(!defined('RUN_ON_STAGING')) define('RUN_ON_STAGING', FALSE);

# Change the directory to where this cron script is located.
chdir(dirname(__FILE__));

# Get the Path definitions.
require '../data/path_definitions.php';
# Get the database definitions.
require DATA_FILES.'database_definitions.php';
# Get the API definitions.
require DATA_FILES.'API_definitions.php';
# Get the Email definitions.
require DATA_FILES.'email_definitions.php';
# Get the Utility Class.
require_once UTILITY_CLASS;

# Get the DB Class needed to operate with MySQL.
require_once Utility::locateFile(MODULES.'Database'.DS.'ezdb.class.php');
DB::init(DB_TYPE);
$db=DB::get_instance();
$db->quick_connect(DBUSER, DBPASS, DBASE, HOSTNAME);

# Get the Contributor Class.
require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
# Instantiate a new Contributor object.
$contributor_obj=new Contributor();

# Get the Validator Class.
require_once Utility::locateFile(MODULES.'Validator'.DS.'Validator.php');

# Get the Video Class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
# Instantiate the new Video object.
$video_obj=new Video();
# Get the YouTube instance. Starts the YouTubeService if it's not already started.
$yt=$video_obj->getYouTubeObject(FULL_DOMAIN);

# Get new uploaded videos from the database.
$get_new_videos=$db->get_results('SELECT `id`, `file_name`, `contributor`, `api` FROM `'.DBPREFIX.'videos` WHERE `new` IS NULL');

# If there are new videos...
if($get_new_videos>0)
{
	# Loop through the new videos
	foreach($get_new_videos as $new_video)
	{
		# Has the video been processed? Default is TRUE. will be changed to FALSE if the video still has "uploaded" status.
		$video_processed=TRUE;

		# Decode the `api` field.
		$api_decoded=json_decode($new_video->api);
		# Get the YouTube Video ID.
		$video_yt_id=$api_decoded->youtube_id;

		if(isset($new_video->file_name))
		{
			# Set the path to the video on the server.
			$video_path=BODEGA.'videos'.DS.$new_video->file_name;
		}

		# Get the contributors (uploaders) information.
		$contributor_obj->getThisContributor($new_video->contributor, 'id');

		$to=$contributor_obj->getContEmail();
		$reply_to=SMTP_FROM;
		$subject="Video status from ".DOMAIN_NAME;
		$body='';

		# Check the video status.
		$check_status=$yt->listVideos('status', array('id' => $video_yt_id));

		# Did YouTube return results?
		if(!empty($check_status['items']))
		{
			# Loop through the videos from YouTube.
			foreach($check_status['items'] as $status)
			{
				if($status['status']['uploadStatus']=="uploaded")
				{
					# The video has not been processed yet so do not send an email.
					$video_processed=FALSE;
				}
				# Check to see if the YouTube upload was a success.
				elseif($status['status']['uploadStatus']=="processed")
				{
					# Tell the user the video was uploaded.
					$body.='Your video has been uploaded to YouTube and can be viewed at http://'.FULL_DOMAIN.'media/videos/?video='.$new_video->id;
				}
				# Check if the uploaded video status is rejected.
				elseif($status['status']['uploadStatus']=="rejected")
				{
					if(isset($new_video->file_name))
					{
						# Get the Upload class.
						require_once Utility::locateFile(MODULES.'Form'.DS.'Upload.php');
						# Instantiate an Upload object.
						$upload=new Upload($video_path);
						# Delete video file from server.
						$upload->deleteFile($video_path);

						# Delete rejected video from YouTube
						$delete_response=$yt->deleteVideo($video_yt_id);
					}

					# Need to delete the entry from the database as well.
					$db->query('DELETE FROM `'.DBPREFIX.'videos` WHERE `id` = '.$db->quote($new_video->id).' LIMIT 1');

					# Check if the rejection status was a duplicate.
					if($status['status']['rejectionReason']=="duplicate")
					{
						# Tell the user the video was a duplicate.
						$body.='Your video was rejected because it was a duplicate video';
					}
				}
			}
		}
		else
		{
			$body.='Your video was not found on YouTube';
			$video_processed=TRUE;
		}

		# Update database if the video has been "processed".
		if($video_processed===TRUE)
		{
			# Get the Email class.
			require_once Utility::locateFile(MODULES.'Email'.DS.'Email.php');
			# Instantiate a new Email object.
			$mail=new Email();

			$mail->sendEmail($subject, $to, $body, $reply_to);

			# Set new videos to old.
			$db->query('UPDATE `'.DBPREFIX.'videos` SET `new` = 0 WHERE `id` = '.$db->quote($new_video->id).' LIMIT 1');
		}
	}
}

# Get the Audio Class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
# Instantiate the new Audio object.
$audio_obj=new Audio();
# Get the Soundcloud instance. Starts the SoundcloudService if it's not already started.
//$soundcloud_obj=$audio_obj->getSoundcloudObject(FULL_DOMAIN);

# Get new uploaded audio from the database.
$get_new_audio=$db->get_results('SELECT `id`, `file_name`, `contributor`, `api` FROM `'.DBPREFIX.'audio` WHERE `new` IS NULL');

# If there are new audio...
if($get_new_audio>0)
{
	# Loop through the new audio.
	foreach($get_new_audio as $new_audio)
	{
		# Has the audio been processed? Default is TRUE. will be changed to FALSE if the audio still has "uploaded" status.
		$audio_processed=TRUE;

		# Get the contributors (uploaders) information.
		$contributor_obj->getThisContributor($new_audio->contributor, 'id');

		$to=$contributor_obj->getContEmail();
		$reply_to=SMTP_FROM;
		$subject="Audio status from ".DOMAIN_NAME;
		$body='';

		# Decode the `api` field.
		$api_decoded=json_decode($new_audio->api);

		if(isset($api_decoded->soundcloud_id))
		{
			# Get the Soundcloud Audio ID.
			$audio_sc_id=$api_decoded->soundcloud_id;
		}
		else
		{
			# Tell the user the audio was uploaded.
			$body.='Your audio has been uploaded viewed at http://'.FULL_DOMAIN.'media/audio/?audio='.$new_audio->id;
		}

		if(isset($new_audio->file_name))
		{
			# Set the path to the audio on the server.
			$audio_path=BODEGA.'audio'.DS.$new_audio->file_name;
		}

/*
		# Check the audio status.
		$check_status=$soundcloud_obj->listAudio('status', array('id' => $audio_sc_id));

		# Did Soundcloud return results?
		if(!empty($check_status['items']))
		{
			# Loop through the audio from Soundcloud.
			foreach($check_status['items'] as $status)
			{
				if($status['status']['uploadStatus']=="uploaded")
				{
					# The audio has not been processed yet so do not send an email.
					$audio_processed=FALSE;
				}
				# Check to see if the Soundcloud upload was a success.
				elseif($status['status']['uploadStatus']=="processed")
				{
					# Tell the user the audio was uploaded.
					$body.='Your audio has been uploaded to YouTube and can be viewed at http://'.FULL_DOMAIN.'media/audio/?audio='.$new_audio->id;
				}
				# Check if the uploaded audio status is rejected.
				elseif($status['status']['uploadStatus']=="rejected")
				{
					if(isset($new_audio->file_name))
					{
						# Get the Upload class.
						require_once Utility::locateFile(MODULES.'Form'.DS.'Upload.php');
						# Instantiate an Upload object.
						$upload=new Upload($audio_path);
						# Delete audio file from server.
						$upload->deleteFile($audio_path);

						# Delete rejected audio from Soundcloud.
						$delete_response=$soundcloud_obj->deleteAudio($audio_sc_id);
					}

					# Need to delete the entry from the database as well.
					$db->query('DELETE FROM `'.DBPREFIX.'audio` WHERE `id` = '.$db->quote($new_audio->id).' LIMIT 1');

					# Check if the rejection status was a duplicate.
					if($status['status']['rejectionReason']=="duplicate")
					{
						# Tell the user the audio was a duplicate.
						$body.='Your audio was rejected because it was a duplicate audio';
					}
				}
			}
		}
		else
		{
			$body.='Your audio was not found on Soundcloud';
			$audio_processed=TRUE;
		}
*/

		# Update database if the audio has been "processed".
		if($audio_processed===TRUE)
		{
			# Get the Email class.
			require_once Utility::locateFile(MODULES.'Email'.DS.'Email.php');
			# Instantiate a new Emailb object.
			$mail=new Email();

			$mail->sendEmail($subject, $to, $body, $reply_to);

			# Set new audio to old.
			$db->query('UPDATE `'.DBPREFIX.'audio` SET `new` = 0 WHERE `id` = '.$db->quote($new_audio->id).' LIMIT 1');
		}
	}
}