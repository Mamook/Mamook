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

# Define the path to the folder containing the application files. (ends with a slash)
define('APPLICATION_FOLDER', BASE_PATH.'application'.DS);

# Define the path to the modules folder. (ends with a slash)
define('MODULES', APPLICATION_FOLDER.'modules'.DS);

# Add MODULES and modules/Social/ (for GoogleClient) to the php include path.
set_include_path(MODULES.PATH_SEPARATOR.MODULES.'Social'.DS);

# Define the path to the views folder. (ends with a slash)
define('VIEWS', APPLICATION_FOLDER.'views'.DS);

# Define where the js/ directory is (ie. /home/user/domain/application/js/) (ends with a slash)
define('JAVASCRIPTS', APPLICATION_FOLDER.'js'.DS);

# Define the path to the bodega. (ends with a slash)
define('BODEGA', BASE_PATH.'bodega'.DS);

# Define the path to the bodega. (ends with a slash)
define('CACHE', BASE_PATH.'cache'.DS);

# The absolute path to images folder. (ends with a slash)
define('IMAGES_PATH', BASE_PATH.'public'.DS.'images'.DS);

# Define where the Templates directory is (ie. /hsphere/home/user/domain.com/templatesFolder/) (ends with a slash)
define('TEMPLATES', BASE_PATH.'templates'.DS);

# Define where the Temp directory is (ie. /hsphere/home/user/domain.com/tmpFolder/) (ends with a slash)
define('TEMP', BASE_PATH.'tmp'.DS);

# Define where the Logs directory is (ie. /hsphere/home/user/domain.com/logsFolder/) (ends with a slash)
define('LOGS', BASE_PATH.'logs'.DS);

# Define where the public audio directory is (ie. audioFolder/) (ends with a slash)
define('AUDIO_PATH', BASE_PATH.'public'.DS.'media'.DS.'audio'.DS);

# Define where the public video directory is (ie. videoFolder/) (ends with a slash)
define('VIDEOS_PATH', BASE_PATH.'public'.DS.'media'.DS.'videos'.DS);

# Define where the secure audio directory is (ie. secure/admin/ManageMedia/audioFolder/) (ends with a slash)
define('SECURE_AUDIO_PATH', 'secure'.DS.'admin'.DS.'ManageMedia'.DS.'audio'.DS);

# Define where the secure video directory is (ie. secure/admin/ManageMedia/videosFolder/) (ends with a slash)
define('SECURE_VIDEOS_PATH', 'secure'.DS.'admin'.DS.'ManageMedia'.DS.'videos'.DS);