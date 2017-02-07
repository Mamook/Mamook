<?php

# Put the keys into an array.
$keys=explode('|', $argv[1]);
# Put the values into an array.
$values=explode('|', $argv[2]);
# Combine the keys and values arrays.
$passed_data=array_combine($keys, $values);

# Need these for database_definitions.php and email_definitions.php
# Need this for the Image class and APPLICATION_URL.
if(!defined('DOMAIN_NAME'))
{
	define('DOMAIN_NAME', $passed_data['Environment']);
}
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('DEVELOPMENT_DOMAIN'))
{
	define('DEVELOPMENT_DOMAIN', $passed_data['DevEnvironment']);
}
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('STAGING_DOMAIN'))
{
	define('STAGING_DOMAIN', $passed_data['StagingEnvironment']);
}
# Need this for YouTube Redirect URL ($youtube_obj=$video_obj->getYouTubeObject(FULL_DOMAIN);).
if(!defined('FULL_DOMAIN'))
{
	define('FULL_DOMAIN', DOMAIN_NAME.'/');
}
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

# Get the Path definitions.
require '../../../../../data/path_definitions.php';
# Get the Utility Class.
require UTILITY_CLASS;

# Get CommandLine class.
require_once Utility::locateFile(MODULES.'CommandLine'.DS.'CommandLine.php');
# Instantiate the new CommandLine object.
$commandline_obj_ffmpeg=new CommandLine('ffmpeg');
# Convert audio files.
if($passed_data['MediaType']=='audio')
{
	# Instantiate the new CommandLine object.
	$commandline_obj_ffprobe=new CommandLine('ffprobe', TRUE);
	# Get the duration of the file.
	$duration=$commandline_obj_ffprobe->runScript('-v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '.BODEGA.'audio'.DS.$passed_data['FileName']);

	if($duration>5)
	{
		if($duration>30)
		{
			$start_time=($duration/2)-15;
			$length=30;
		}
		else
		{
			$start_time=0;
			$length=($duration/2);
		}

		# Create a sample file.
		#	Go to the half way point of the file and create a 30 second clip.
		$commandline_obj_ffmpeg->runScript('-y -ss '.$start_time.' -i '.BODEGA.'audio'.DS.$passed_data['FileName'].' -t '.$length.' -acodec libmp3lame -ac 2 -ab 128k '.AUDIO_PATH.'files'.DS.$passed_data['FileNameNoExt'].'.mp3');
	}
}
