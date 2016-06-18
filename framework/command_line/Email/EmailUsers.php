<?php /* framework/command_line/Email/EmailUsers.php */

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

if(RUN_ON_STAGING===TRUE || RUN_ON_DEVELOPMENT===TRUE)
{
	# Turn Debugging on or off.
	define('DEBUG_APP', TRUE);
}
else
{
	# Turn Debugging on or off.
	define('DEBUG_APP', FALSE);
}

# Get the Path definitions.
require '../../../../../data/path_definitions.php';
# Get the database definitions.
require DATA_FILES.'database_definitions.php';
# Get the Email definitions.
require DATA_FILES.'email_definitions.php';
# Get the User Privileges.
require DATA_FILES.'user_privileges.php';
# Get the Utility Class.
require UTILITY_CLASS;
# Get the Validator Class.
require Utility::locateFile(MODULES.'Validator'.DS.'Validator.php');

# Get the DB Class needed to operate with MySQL.
require_once Utility::locateFile(MODULES.'Vendor'.DS.'ezDB'.DS.'ezdb.class.php');
DB::init(DB_TYPE);
$db=DB::get_instance();
$db->quick_connect(DBUSER, DBPASS, DBASE, HOSTNAME);

# Get the Email class.
require_once Utility::locateFile(MODULES.'Email'.DS.'Email.php');
# Instantiate a new Email object.
$email_obj=new Email();
$email_obj->sendMultipleEmails($passed_data);