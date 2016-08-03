<?php /* framework/command_line/Media/AudioUpload.php */

# NOTE: This script is useless until we implement Ajax to call this script to TRULY upload a video in the background.

/*
# Put the keys into an array.
$keys=explode('|', $argv[1]);
# Put the values into an array.
$values=explode('|', $argv[2]);
# Combine the keys and values arrays.
$passed_data=array_combine($keys, $values);

# Need these for database_definitions.php and email_definitions.php
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('DOMAIN_NAME')) define('DOMAIN_NAME', $passed_data['Environment']);
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('DEVELOPMENT_DOMAIN')) define('DEVELOPMENT_DOMAIN', $passed_data['DevEnvironment']);
# Need this for the FileHandler class and APPLICATION_URL.
if(!defined('STAGING_DOMAIN')) define('STAGING_DOMAIN', $passed_data['StagingEnvironment']);
# Need this for SoundCloud Redirect URL ($soundcloud_obj=$audio_obj->getSoundCloudObject(FULL_DOMAIN);).
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

# Check if the SoundCloud credentials are available.
if($passed_data['SoundCloud']==='post_soundcloud')
{
	# Get CommandLine class.
	require_once Utility::locateFile(MODULES.'CommandLine'.DS.'CommandLine.php');
	# Instantiate the new CommandLine object.
	$commandline_obj=new CommandLine();
	# Run the upload script.
	#	runScript() turns a multidimensional array into a single dimensional array and seperates the keys from the values.
	#		ex: php ScriptName.php Key1|Key2|Key3 Value1|Value2|Value3
	$commandline_obj->runScript(Utility::locateFile(COMMAND_LINE.'Media'.DS.'SoundCloudUpload.php'), $passed_data);
}

# If there is an audio file.
if(!empty($passed_data['FileName']))
{
	if(isset($passed_data['InsertID']))
	{
		# Set the path to the audio on the server.
		$audio_path=BODEGA.'audio'.DS.$passed_data['FileName'];

		if(empty($passed_data['ImageID']))
		{
			# Get the getID3 Class.
			require_once Utility::locateFile(MODULES.'Vendor'.DS.'getID3'.DS.'getid3'.DS.'getid3.php');
			# Instantiate the new getID3 object.
			$getID3=new getID3;

			# Remove the file extension.
			$thumbnail_no_ext=substr($passed_data['FileName'], 0, strrpos($passed_data['FileName'], '.'));

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
					$db->quote($db->escape(str_ireplace(DOMAIN_NAME, '%{domain_name}', $passed_data['Title']))).', '.
					$db->quote($db->escape($thumbnail_no_ext.'.jpg')).', '.
					$db->quote($db->escape($passed_data['Playlists'])).', '.
					$db->quote($passed_data['ContID']).
					')';
				# Run the SQL query.
				$db->query($sql);

				# Assign the image ID to a variable.
				$image_id=$db->get_insert_id();

				# Update the audion file to insert the image ID.
				$db->query('UPDATE `'.DBPREFIX.'audio` SET `image` = '.$db->escape($image_id).' WHERE `id` = '.$db->quote($passed_data['InsertID']).' LIMIT 1');
			}
		}
	}
}
*/