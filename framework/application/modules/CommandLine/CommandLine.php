<?php /* framework/application/modules/CommandLine/CommandLine.php */

# Create a short constant for the Path Separator if it is not already defined.
if(!defined('PS')) define('PS', PATH_SEPARATOR);


/**
 * CommandLine
 *
 * The CommandLine Class is used to execute scripts "behind the scenes" from the command line.
 *
 * To use this class, first instantiate a new instance of the class passing the
 * language of the script (if not php.) Then use the runScript method to pass and
 * run the script. The language can be reset to run a different script.
 *
 * @example		$cl=new CommandLine('python');
 *				$cl->runScript('Python/myPythonScript.py');
 *				$cl->setLanguage('php');
 *				$cl->runScript('BackgroundPHP/myPHP_Script.php');
 */
class CommandLine
{
	/*** data members ***/

	private $language='php';
	private $return_value=FALSE;
	private $script=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * Description of the constructor.
	 *
	 * @param string $language The scripting language of the script to run.
	 * @param boolean $return_value
	 * @throws Exception
	 */
	public function __construct($language='php', $return_value=FALSE)
	{
		try
		{
			# Set the script language to the data member.
			$this->setLanguage($language);
			$this->return_value;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setLanguage
	 *
	 * Sets the data member $language.
	 *
	 * @param string $language
	 */
	public function setLanguage($language)
	{
		# Check if the passed value is empty.
		if(empty($language) OR !is_string($language))
		{
			# Explicitly set the language to "php".
			$language='php';
		}
		# Set the data member.
		$this->language=$language;
	}

	/**
	 * setScript
	 *
	 * Sets the data member $script.
	 *
	 * @param string $script
	 * @throws Exception
	 */
	public function setScript($script)
	{
		# Check if the passed value is empty.
		if(empty($script) OR !(is_string($script) OR is_array($script)))
		{
			# Explicitly set the language to "php".
			throw new Exception('There must be a filename or command passed to run in the background!', E_RECOVERABLE_ERROR);
		}
		if(is_array($script))
		{
			$executable=$this->getExecutable();
			$new_commands=array();
			# Loop through the Array.
			foreach($script as $current_script)
			{
				$current_script=trim($current_script);
				$new_commands[]=$executable.' '.$current_script;
			}
			# Separate the values with ( && ).
			$script=implode(' && ', $new_commands);
			$script=ltrim($script, $executable.' ');
		}
// 		if($this->getLanguage()!='ffmpeg')
// 		{
// 			# Explicitly remove the BASE_PATH from the filename for normalization.
// 			$script=str_replace(BASE_PATH, '', $script);
// 			# Prepend the filename with the BASE_PATH to make a complete path.
// 			$script=BASE_PATH.$script;
// 		}
		# Set the data member.
		$this->script=$script;
	}

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getLanguage
	 *
	 * Returns the data member $language.
	 */
	private function getLanguage()
	{
		return $this->language;
	}

	/**
	 * getScript
	 *
	 * Returns the data member $script.
	 */
	public function getScript()
	{
		return $this->script;
	}

	/**
	 * @return bool
	 */
	public function getReturnValue()
	{
		return $this->return_value;
	}

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * runScript
	 *
	 * Runs the passed script in the background. Errors on Linux are written to a log file.
	 *
	 * @param string $script       The path to the script to execute.
	 * @param array|string $params Add extra arguments after the script. Can be an array or a string, NOT an object.
	 * @return string
	 * @throws Exception
	 */
	public function runScript($script=NULL, $params=NULL)
	{
		try
		{
			# Set the script to the data member effectively "cleaning" it.
			$this->setScript($script);
			# Get the correct executable.
			$executable=$this->getExecutable();

			$command=$executable.' '.$this->getScript();

			# Check if $params is set, and if it's an array.
			if(isset($params) && is_array($params))
			{
				# Call the flatten function.
				$new_params=Utility::flattenArray($params);

				# Get the array keys.
				$keys=array_keys($new_params);
				# Separate the keys with a pipe (|).
				$pipe_separated_keys=implode('|', $keys);

				# Get the array values.
				$values=array_values($new_params);
				# Separate the values with a pipe (|).
				$pipe_separated_values=implode('|', $values);

				$command.=' "'.$pipe_separated_keys.'" "'.$pipe_separated_values.'"';
			}
			# If $params is not an array or an object.
			elseif(isset($params) && !is_object($params))
			{
				$command.=$params;
			}

			//passthru($doc->$getExecutableMethod().' '.BASE_PATH.$script.' '.$argv_parameter.' >> '.LOGS.'log_file.log 2>&1 &');
			# Run it in the background.
			return $this->execInBackground($command);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * execInBackground
	 *
	 * Execute the passed command in the background. On linux errors are written to a log file.
	 *
	 * @param string $command The command to execute in the background.
	 * @return null|string
	 * @throws Exception
	 */
	protected function execInBackground($command)
	{
		try
		{
			# Debugging.
			//echo $command.'<br>'."\n";

			# Check if this is a Windows server.
			if($this->isWindows()===TRUE)
			{
				# Execute the script in the background Windows style.
				pclose(popen('start /B '. $command, 'r'));
			}
			else
			{
				if($this->getReturnValue()===FALSE)
				{
					# Execute the script in the background Linux style. Writes errors to a log file.
					exec($command.' >> '.LOGS.COMMAND_LINE_LOG.' &');
					//exec($command." 2>&1 &", $error_result);
				}
				elseif($this->getReturnValue()!==FALSE)
				{
					# Execute the script in the background Linux style. Writes errors to a log file.
					return system($command);
				}
			}
			# NOTE: Doing the logging this way makes the browser hang.
			# TODO: Use try and catch in the command line scripts instead.
			/*
			# If $error_result is not empty, there was an error (2>&1).
			if(!empty($error_result))
			{
				# If $error_result is an array, convert it to a string seperated by PHP End of Line.
				if(is_array($error_result))
				{
					$error_result=implode(PHP_EOL, $error_result);
				}
				# Get the Logger Class.
				require_once Utility::locateFile(MODULES.'Logger'.DS.'Logger.php');
				# Create a new Logger object, and set the log file to use.
				$logger_obj=new Logger(COMMAND_LINE_LOG);
				# Write exec() output to log file.
				$logger_obj->writeLogFile($error_result);
				# Close log file.
				$logger_obj->closeLogFile();
			}
			*/
			return NULL;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End protected methods ***/



	/*** private methods ***/

	/**
	 * getExecutable
	 *
	 * Returns the path to the current executable as a String.
	 *
	 * @return string The executable location on success, otherwise FALSE.
	 * @throws Exception
	 */
	public function getExecutable()
	{
		try
		{
			# Get the correct executable.
			$getExecutableMethod='get'.strtoupper($this->getLanguage()).'_Executable';

			$executable=$this->$getExecutableMethod();

			if($this->getLanguage()=='php' || $this->getLanguage()=='python')
			{
				$executable.=' -f';
			}
			return $executable;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getFFMPEG_Executable
	 *
	 * Determine the location of the ffmpeg executable.
	 *
	 * @return string The executable location on success, otherwise FALSE.
	 * @throws Exception
	 */
	public function getFFMPEG_Executable()
	{
		# Set the default executable path as FALSE.
		$ffmpeg_executable_path=FALSE;

		try
		{
			# Explode the PATH into an array of individual paths.
			$paths=explode(PS, getenv('PATH'));
			# Loop through the paths.
			foreach($paths as $path)
			{
				# Set the default executable to a local variable.
				$ffmpeg_executable_path=$path.DS.'ffmpeg';
				# Check if this is a windows server.
				if($this->isWindows()===TRUE)
				{
					$ffmpeg_executable_path.='.exe';
					# Check if "php.exe" explicitly exists in the path. Is so, set this path to the local variable.
					if(strstr($path, 'ffmpeg.exe'))
					{
						$ffmpeg_executable_path=$path;
						break;
					}
				}
				# Check if the path to the executable in the local variable actually exists.
				if(file_exists($ffmpeg_executable_path) && is_file($ffmpeg_executable_path))
				{
					 break;
				}
			}
			return $ffmpeg_executable_path;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getFFPROBE_Executable
	 *
	 * Determine the location of the ffprobe executable.
	 *
	 * @return string The executable location on success, otherwise FALSE.
	 * @throws Exception
	 */
	public function getFFPROBE_Executable()
	{
		# Set the default executable path as FALSE.
		$ffprobe_executable_path=FALSE;

		try
		{
			# Explode the PATH into an array of individual paths.
			$paths=explode(PS, getenv('PATH'));
			# Loop through the paths.
			foreach($paths as $path)
			{
				# Set the default executable to a local variable.
				$ffprobe_executable_path=$path.DS.'ffprobe';
				# Check if this is a windows server.
				if($this->isWindows()===TRUE)
				{
					$ffprobe_executable_path.='.exe';
					# Check if "php.exe" explicitly exists in the path. Is so, set this path to the local variable.
					if(strstr($path, 'ffprobe.exe'))
					{
						$ffprobe_executable_path=$path;
						break;
					}
				}
				# Check if the path to the executable in the local variable actually exists.
				if(file_exists($ffprobe_executable_path) && is_file($ffprobe_executable_path))
				{
					break;
				}
			}

			return $ffprobe_executable_path;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getNode_Executable
	 *
	 * Determine the location of the node executable.
	 *
	 * @return string The executable location on success, otherwise FALSE.
	 * @throws Exception
	 */
	public function getNode_Executable()
	{
		# Set the default executable path as FALSE.
		$node_executable_path=FALSE;

		try
		{
			# Explode the PATH into an array of individual paths.
			$paths=explode(PS, getenv('PATH'));
			# Loop through the paths.
			foreach($paths as $path)
			{
				# Set the default executable to a local variable.
				$node_executable_path=$path.DS.'node';
				# Check if this is a windows server.
				if($this->isWindows()===TRUE)
				{
					$node_executable_path.='.exe';
					# Check if "php.exe" explicitly exists in the path. Is so, set this path to the local variable.
					if(strstr($path, 'node.exe'))
					{
						$node_executable_path=$path;
						break;
					}
				}
				# Check if the path to the executable in the local variable actually exists.
				if(file_exists($node_executable_path) && is_file($node_executable_path))
				{
					 break;
				}
			}
			return $node_executable_path;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getPHP_Executable
	 *
	 * Determine the location of the PHP executable.
	 *
	 * @return string The executable location on success, otherwise FALSE.
	 * @throws Exception
	 */
	public function getPHP_Executable()
	{
		# Set the default executable path as FALSE.
		$php_executable_path=FALSE;

		try
		{
			# Set the default executable to a local variable.
			$php_executable=DS.'php'.(($this->isWindows()===TRUE) ? '.exe' : '');
			# First try the PHP_BINARY constant.
			if(defined('PHP_BINARY') && file_exists(PHP_BINARY) && is_file(PHP_BINARY))
			{
				$php_executable_path=PHP_BINARY;
			}
			# Try the PHP_BINDIR constant.
			#	NOTE: PHP will give an error for file_exists() if the path is not in open_basedir.
			#		"Error: Warning: file_exists(): open_basedir restriction in effect. File(/usr/local/bin/php) is not within the allowed path(s):"
			elseif(defined('PHP_BINDIR') && file_exists(PHP_BINDIR.$php_executable) && is_file(PHP_BINDIR.$php_executable))
			{
				$php_executable_path=PHP_BINDIR.$php_executable;
			}
			else
			{
				# Explode the PATH into an array of individual paths.
				$paths=explode(PS, getenv('PATH'));
				# Loop through the paths.
				foreach($paths as $path)
				{
					# Set the default executable to a local variable.
					$php_executable_path=$path.$php_executable;
					# Check if this is a windows server and if "php.exe" explicitly exists in the path. Is so, set this path to the local variable.
					if(($this->isWindows()===TRUE) && strstr($path, 'php.exe'))
					{
						$php_executable_path=$path;
						break;
					}
					# Check if the path to the executable in the local variable actually exists.
					if(file_exists($php_executable_path) && is_file($php_executable_path))
					{
						 break;
					}
				}
			}
			return $php_executable_path;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getPYTHON_Executable
	 *
	 * Determine the location of the Python executable.
	 *
	 * @return string The executable location on success, otherwise FALSE.
	 * @throws Exception
	 */
	public function getPYTHON_Executable()
	{
		# Set the default executable path as FALSE.
		$python_executable_path=FALSE;

		try
		{
			# Explode the PATH into an array of individual paths.
			$paths=explode(PS, getenv('PATH'));
			# Loop through the paths.
			foreach($paths as $path)
			{
				# Set the default executable to a local variable.
				$python_executable_path=$path.DS.'python';
				# Check if this is a windows server.
				if($this->isWindows()===TRUE)
				{
					$python_executable_path.='.exe';
					# Check if "php.exe" explicitly exists in the path. Is so, set this path to the local variable.
					if(strstr($path, 'python.exe'))
					{
						$python_executable_path=$path;
						break;
					}
				}
				# Check if the path to the executable in the local variable actually exists.
				if(file_exists($python_executable_path) && is_file($python_executable_path))
				{
					 break;
				}
			}
			return $python_executable_path;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * isWindows
	 *
	 * Determines if PHP is installed on a Windows server or not.
	 *
	 * @return bool TRUE if on Windows, FALSE if not.
	 * @throws Exception
	 */
	private function isWindows()
	{
		try
		{
			# Check if the $_SERVER['WINDIR'] server variable is set. If it is, then this must be a windows server.
			return isset($_SERVER['WINDIR']);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End private methods ***/
}
