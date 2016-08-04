<?php /* data/path_definitions.php */

/*
 * Check if BASE_PATH has already been defined. If it has, it was defined in settings.php and these scripts
 * are being used in a browser. If it hasn't, then these can be used "behind the scenes" in a script called
 * via the command line.
 */
if(!defined('BASE_PATH'))
{
	# Define backslash or forward slash for *NIX and IIS systems.
	define('DS', DIRECTORY_SEPARATOR);
	# Attempt to determine the full-server path to the 'root' folder in order to reduce the possibility of path problems. (ends with a slash)
	define('BASE_PATH', rtrim(realpath(dirname(__FILE__)), 'data'));
	# Define the path to data. (ends with a slash)
	define('DATA_FILES', BASE_PATH.'data'.DS);
}

# Define the path to the framework folder containing the application files. (ends with a slash)
define('FW_FOLDER', BASE_PATH.'framework'.DS);

# Define the path to the folder containing the application files. (ends with a slash)
define('APPLICATION_FOLDER', BASE_PATH.'application'.DS);

# Define the path to the framework folder containing the application files. (ends with a slash)
define('FW_APPLICATION_FOLDER', FW_FOLDER.'application'.DS);

# Define the path to the command_line folder. (ends with a slash)
define('COMMAND_LINE', BASE_PATH.'command_line'.DS);

# Define the path to the framework command_line folder. (ends with a slash)
define('FW_COMMAND_LINE', FW_FOLDER.'command_line'.DS);

# Define the path to the controllers folder. (ends with a slash)
define('CONTROLLERS', APPLICATION_FOLDER.'controllers'.DS);

# Define the path to the framework controllers folder. (ends with a slash)
define('FW_CONTROLLERS', FW_APPLICATION_FOLDER.'controllers'.DS);

# Define the path to the modules folder. (ends with a slash)
define('MODULES', APPLICATION_FOLDER.'modules'.DS);

# Define the path to the framework modules folder. (ends with a slash)
define('FW_MODULES', FW_APPLICATION_FOLDER.'modules'.DS);

# Define the path to the UTILITY module. If there is a custum UTILITY module, comment the next line and uncomment the one after.
define('UTILITY_CLASS', FW_MODULES.'Utility'.DS.'Utility.php');
//define('UTILITY_CLASS', MODULES.'Utility'.DS.'Utility.php');

# Define the path to the WebUtility module. If there is a custum UTILITY module, comment the next line and uncomment the one after.
define('WEBUTILITY_CLASS', FW_MODULES.'Utility'.DS.'WebUtility.php');
//define('WEBUTILITY_CLASS', MODULES.'Utility'.DS.'WebUtility.php');

# Define the path to the views folder. (ends with a slash)
define('VIEWS', APPLICATION_FOLDER.'views'.DS);

# Define the path to the framework views folder. (ends with a slash)
define('FW_VIEWS', FW_APPLICATION_FOLDER.'views'.DS);

# Define where the js/ directory is (ie. /home/user/domain/application/js/) (ends with a slash)
define('JAVASCRIPTS', APPLICATION_FOLDER.'js'.DS);

# Define where the framework js/ directory is (ie. /home/user/domain/application/js/) (ends with a slash)
define('FW_JAVASCRIPTS', FW_APPLICATION_FOLDER.'js'.DS);

# Define the path to the bodega. (ends with a slash)
define('BODEGA', BASE_PATH.'bodega'.DS);

# Define the path to the bodega. (ends with a slash)
define('CACHE', BASE_PATH.'cache'.DS);

# The absolute path to images folder. (ends with a slash)
define('IMAGES_PATH', BASE_PATH.'public'.DS.'images'.DS);

# Define where the Templates directory is (ie. /hsphere/home/user/domain.com/templatesFolder/) (ends with a slash)
define('TEMPLATES', APPLICATION_FOLDER.'templates'.DS);

# Define where the Framework Templates directory is (ie. /hsphere/home/user/domain.com/templatesFolder/) (ends with a slash)
define('FW_TEMPLATES', FW_APPLICATION_FOLDER.'templates'.DS);

# Define where the Temp directory is (ie. /hsphere/home/user/domain.com/tmpFolder/) (ends with a slash)
define('TEMP', BASE_PATH.'tmp'.DS);

# Define where the Logs directory is (ie. /hsphere/home/user/domain.com/logsFolder/) (ends with a slash)
define('LOGS', BASE_PATH.'logs'.DS);
# Define the Command Line log file name.
define('COMMAND_LINE_LOG', 'cl_log_file.log');
# Define the Cron log file name.
define('CRON_LOG', 'cron.log');
# Define the Downloads log file name.
define('DOWNLOADS_LOG', 'downloads.log');
# Define the CHANGELOG file name.
define('CHANGELOG', 'CHANGELOG');

# Define where the public media directory is (ie. mediaFolder/) (ends with a slash)
define('MEDIA_PATH', BASE_PATH.'public'.DS.'media'.DS);

# Define where the public audio directory is (ie. audioFolder/) (ends with a slash)
define('AUDIO_PATH', MEDIA_PATH.'audio'.DS);

# Define where the public video directory is (ie. videoFolder/) (ends with a slash)
define('VIDEOS_PATH', MEDIA_PATH.'videos'.DS);

# Define where the secure audio directory is (ie. secure/admin/ManageMedia/audioFolder/) (ends with a slash)
define('SECURE_AUDIO_PATH', 'secure'.DS.'admin'.DS.'ManageMedia'.DS.'audio'.DS);

# Define where the secure video directory is (ie. secure/admin/ManageMedia/videosFolder/) (ends with a slash)
define('SECURE_VIDEOS_PATH', 'secure'.DS.'admin'.DS.'ManageMedia'.DS.'videos'.DS);

# Define the path to the vendor directory (ends with a slash)
define('VENDOR_FOLDER', MODULES.'Vendor'.DS);

# Define the path to the Framework vendor directory (ends with a slash)
define('FW_VENDOR_FOLDER', FW_MODULES.'Vendor'.DS);

# Define the path to the root vendor directory (ends with a slash)
define('ROOT_VENDOR_FOLDER', FW_FOLDER.'vendor'.DS);

# Add modules/Vendor/ (for GoogleClient) to the php include path.
set_include_path(get_include_path().PATH_SEPARATOR.MODULES.PATH_SEPARATOR.FW_MODULES.PATH_SEPARATOR.VENDOR_FOLDER.PATH_SEPARATOR.FW_VENDOR_FOLDER.PATH_SEPARATOR.ROOT_VENDOR_FOLDER);