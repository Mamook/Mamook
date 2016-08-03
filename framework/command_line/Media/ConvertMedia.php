<?php /* framework/command_line/Media/ConvertMedia.php */

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

# Get the Path definitions.
require '../../../../../data/path_definitions.php';
# Get the Utility Class.
require UTILITY_CLASS;

# Get CommandLine class.
require_once Utility::locateFile(MODULES.'CommandLine'.DS.'CommandLine.php');
# Instantiate the new CommandLine object.
$commandline_obj_ffmpeg=new CommandLine('ffmpeg');
# Convert video files.
if($passed_data['MediaType']=='video')
{
	# Convert to webm.
	$commandline_obj_ffmpeg->runScript('-y -i '.BODEGA.'videos'.DS.$passed_data['FileName'].' -c:v libvpx -vf "scale=trunc(oh*a/2)*2:\'min(ih,480)\'" -quality good -cpu-used 0 -crf 26 -b:v 600k -qmin 10 -qmax 42 -maxrate 500k -bufsize 1000k -threads 1 -codec:a libvorbis -b:a 128k -f webm '.VIDEOS_PATH.'files'.DS.$passed_data['FileNameNoExt'].'.webm');
	# Convert to h264 mp4.
	$commandline_obj_ffmpeg->runScript('-y -i '.BODEGA.'videos'.DS.$passed_data['FileName'].' -c:v libx264 -pix_fmt yuv420p -vf "scale=trunc(oh*a/2)*2:\'min(ih,480)\'" -preset slow -crf 26 -b:v 500k -maxrate 500k -bufsize 1000k -profile:v high -level 4.2 -threads 1 -codec:a libfdk_aac -b:a 128k -movflags +faststart '.VIDEOS_PATH.'files'.DS.$passed_data['FileNameNoExt'].'.mp4');
}
elseif($passed_data['MediaType']=='audio')
{
	# Convert to 128bit mp3.
	$commandline_obj_ffmpeg->runScript('-y -i '.BODEGA.'audio'.DS.$passed_data['FileName'].' -acodec libmp3lame -ac 2 -ab 128k '.AUDIO_PATH.'files'.DS.$passed_data['FileNameNoExt'].'.mp3');
}