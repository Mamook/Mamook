<?php /* framework/application/modules/Cache/Cache.php */


/**
 * Cache
 *
 * The Cache class is used to access and store data in the application's cache.
 *
 * To use this class, you can instantiate a new Cache object and pass all the necessary params
 * and values in at that time. ie.
 *
 * $cache=new Cache($cachable_data, $unique_name, $cache_extension, 3600, $directory);
 *
 * Or, you can set those values after the Cache object is instantiated. ie.
 *
 * $cache=new Cache();
 * $cache->setFileData($cachable_data);
 * $cache->setUniqueName($unique_name);
 * $cache->setExt($cache_extension);
 * $cache->setCacheTime(3600);
 * $cache->setCacheDir($directory);
 *
 * Or you can do a combination of the two.
 * Finally, call the createCache method to make the cache.
 * To retrieve a cache, call the retrieveCacheContents method. This method also accepts a unique
 * name and directory param. So this method could be handled like:
 *
 * $cache=new Cache(NULL, NULL, 'cache');
 * $contents=$cache->retrieveCacheContents($unique_name, $cahce_dir);
 */
class Cache
{
	/*** data members ***/

		private $cache_dir;
		private $cache_name;
		private $cache_time;
		private $ext;
		private $file_data;
		private $time_now=NULL;
		private $unique_name;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * The constructor for the class. Sets the $file_data, $unique_name, $ext, $cache_time, and
	 * $cache_dir data members.
	 *
	 * @param		$data							The cacheable data. Default is NULL.
	 * @param		$unique_name			The unique portion of the cache file name. Default is empty.
	 * @param		$cache_extension	The extension portion of the cache file name. Default is "cache".
	 * @param		$cache_time				The amount of time in seconds to keep the cache file. Default is
	 * 3600
	 * @param		$cache_dir				The name of the directory where the cache file resides.
	 * @access	public
	 */
	public function __construct($data=NULL, $unique_name='', $cache_extension='cache', $cache_time=3600, $cache_dir=CACHE)
	{
		# Set the passed data to be cached to the $file_data data member.
		$this->setFileData($data);
		# Set the passed cache extension name to the $ext data member.
		$this->setExt($cache_extension);
		# Set the passed unique portion of the cache name to the $unique_name data member.
		$this->setUniqueName($unique_name);
		# Set the passed cache expiration time to the $cache_time data member.
		$this->setCacheTime($cache_time);
		# Set the passed cache directory to the $cache_dir data member.
		$this->setCacheDir($cache_dir);
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setCacheDir
	 *
	 * Sets the data member $cache_dir.
	 *
	 * @param		$dir: The path to the cache directory. Not the cache filename.
	 * @access	public
	 */
	public function setCacheDir($dir=CACHE)
	{
		# Check if the passed value is empty.
		if(empty($dir))
		{
			# Explicitly set the value to the default (the CACHE constant).
			$dir=CACHE;
		}
		# Normalize the path (remove the directory separator).
		$dir=rtrim($dir, DS);
		# Set the data member. Ensure the directory ends with a directory separator.
		$this->cache_dir=$dir.DS;
	} #==== End -- setCacheDir

	/**
	 * setCacheName
	 *
	 * Sets the data member $cache_name.
	 *
	 * @param		$cache_name: The name of the cache file. The cache file name
	 * will look like 39284756.unique.name.extension
	 *
	 * @access	public
	 */
	public function setCacheName($cache_name)
	{
		# Check if the passed value is empty or is an array or is NOT a string.
		if(empty($cache_name) OR is_array($cache_name) OR !is_string($cache_name))
		{
			# Explicitly set the value to NULL.
			$cache_name=NULL;
		}
		# Set the data member.
		$this->cache_name=$cache_name;
	} #==== End -- setCacheName

	/**
	 * setCacheTime
	 *
	 * Sets the data member $cache_time.
	 *
	 * @param		$seconds: The time (in seconds) for a cache file to exist.
	 * @access	public
	 */
	public function setCacheTime($seconds)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty or is not an integer.
		if(empty($seconds) OR ($validator->isInt($seconds)!==TRUE))
		{
			# Explicitly set the value to the default (3600).
			$seconds=3600;
		}
		# Set the data member.
		$this->cache_time=$seconds;
	} #==== End -- setCacheTime

	/**
	 * setExt
	 *
	 * Sets the data member $ext.
	 *
	 * @param		$extension: The extension for the cache file.
	 * @access	public
	 */
	public function setExt($extension)
	{
		# Check if the passed value is empty or is an array.
		if(empty($extension) OR is_array($extension) OR !is_string($extension))
		{
			# Explicitly set the value to NULL.
			$extension=NULL;
		}
		# Set the data member.
		$this->ext=$extension;
	} #==== End -- setExt

	/**
	 * setFileData
	 *
	 * Sets the data member $file_data.
	 *
	 * @param		$data		The data to be cached. Must be a string.
	 * @access	public
	 */
	public function setFileData($data)
	{
		# Check if the passed value is empty.
		if(empty($data) OR is_array($data) OR !is_string($data))
		{
			# Explicitly set the value to NULL.
			$data=NULL;
		}
		# Set the data member.
		$this->file_data=$data;
	} #==== End -- setFileData

	/**
	 * setTimeNow
	 *
	 * Sets the data member $time_now.
	 *
	 * @param		$time: The time. Default is NULL which will cause the current server time to be set.
	 * @access	public
	 */
	public function setTimeNow($time=NULL)
	{
		# Check if the passed value is empty.
		if(empty($time))
		{
			# Explicitly set the value to NULL.
			$time=time();
		}
		$time=date('U', $time);
		# Set the data member.
		$this->time_now=$time;
	} #==== End -- setTimeNow

	/**
	 * setUniqueName
	 *
	 * Sets the data member $unique_name.
	 *
	 * @param		$unique_name: A unique name for the cache file to differentiate it from
	 * other similar cache files.
	 * @access	public
	 */
	public function setUniqueName($unique_name)
	{
		# Check if the passed value is empty or is an array or is NOT a string.
		if(empty($unique_name) OR is_array($unique_name) OR !is_string($unique_name))
		{
			# Explicitly set the value to NULL.
			$unique_name=NULL;
		}
		# Set the data member.
		$this->unique_name=$unique_name;
	} #==== End -- setUniqueName

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getCacheDir
	 *
	 * Returns the data member $cache_dir.
	 *
	 * @access	public
	 */
	public function getCacheDir()
	{
		return $this->cache_dir;
	} #==== End -- getCacheDir

	/**
	 * getCacheName
	 *
	 * Returns the data member $cache_name.
	 *
	 * @access	public
	 */
	public function getCacheName()
	{
		return $this->cache_name;
	} #==== End -- getCacheName

	/**
	 * getCacheTime
	 *
	 * Returns the data member $cache_time.
	 *
	 * @access	public
	 */
	public function getCacheTime()
	{
		return $this->cache_time;
	} #==== End -- getCacheTime

	/**
	 * getExt
	 *
	 * Returns the data member $ext.
	 *
	 * @access	public
	 */
	public function getExt()
	{
		return $this->ext;
	} #==== End -- getExt

	/**
	 * getFileData
	 *
	 * Returns the data member $file_data.
	 *
	 * @access	public
	 */
	public function getFileData()
	{
		return $this->file_data;
	} #==== End -- getFileData

	/**
	 * getTimeNow
	 *
	 * Returns the data member $time_now.
	 *
	 * @access	public
	 */
	public function getTimeNow()
	{
		# Check if the time_now data member is empty.
		if(empty($this->time_now))
		{
			# Set the time_now data member to the current time.
			$this->setTimeNow();
		}
		return $this->time_now;
	} #==== End -- getTimeNow

	/**
	 * getUniqueName
	 *
	 * Returns the data member $unique_name.
	 *
	 * @access	public
	 */
	public function getUniqueName()
	{
		return $this->unique_name;
	} #==== End -- getUniqueName

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * createCache
	 *
	 * Makes the cache directory and file.
	 *
	 * @access	public
	 */
	public function createCache()
	{
	# Set the current cache directory to a local variable.
		$dir=$this->getCacheDir();
		# Make surte the cache directory is there.
		$this->createCacheDir($dir);
		# Clean out any old files from the cache directory.
		$this->cleanCache($dir);
		# Create the cache file.
		if($this->makeCacheFile()===FALSE)
		{
			throw new Exception("Couldn't create the cache file at: ".$dir, E_RECOVERABLE_ERROR);
		}
	} #==== End -- createCache

	/**
	 * retrieveCacheContents
	 *
	 * Returns the contents of the cache file that matches the passed name and directory.
	 *
	 * @param		$unique_name	The unique portion of the cache file name.
	 * @param		$cache_dir		The name of the directory where the cache file resides.
	 * @access	public
	 * @return	False on failure. The file contents on success as a string.
	 */
	public function retrieveCacheContents($unique_name, $cache_dir=NULL)
	{
		# Set the passed directory to the data member, effectively cleaning it.
		$this->setCacheDir($cache_dir);
		# Set the data member to a local variable.
		$cache_dir=$this->getCacheDir();
		# Check if the passed directory actually is a directory.
		if(is_dir($cache_dir))
		{
			# Open the directory.
			if($dh=opendir($cache_dir))
			{
				# Loop through the files in the directory.
				while(FALSE!==($file_name=readdir($dh)))
				{
					# Make sure it isn't a system file, svn folder, or a subdirectory.
					if($file_name!=='.' && $file_name!=='..' && $file_name!=='.svn' && !is_dir($cache_dir.$file_name))
					{
						# Get the file's expiration time from it's name.
						$expiration=$this->extrapolateExpiry($file_name);
						# Check if the file is expired.
						if(!empty($expiration)&&$this->getTimeNow()>=$expiration)
						{
							# Remove the file.
							unlink($cache_dir.$file_name);
						}
						else
						{
							# Remove the expiration fromthe front of the filename.
							$file_name=str_replace($expiration.'.', '', $file_name);
							# Check if the current file name matches the passed file name.
							if($unique_name===$file_name)
							{
								$contents=file_get_contents($cache_dir.$expiration.'.'.$unique_name);
								# Close the directory.
								closedir($dh);
								# Return the contents.
								return $contents;
							}
						}
					}
				}
				# Close the directory.
				closedir($dh);
			}
		}
		# Didn't find that cache file.
		return FALSE;
	} #==== End -- retrieveCacheContents

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * cleanCache
	 *
	 * Looks through the cach directory and removes any expired cache files.
	 *
	 * @param		string		$cache_dir 	The name of the directory.
	 * @access	protected
	 */
	protected function cleanCache($cache_dir=NULL)
	{
		# Check if a directory was passed.
		if(empty($cache_dir))
		{
			# Get the cache_dir data member and set it to the local value.
			$cache_dir=$this->getCacheDir();
		}
		# Normalize the path (remove the directory separator).
		$cache_dir=rtrim($cache_dir, DS);
		# Ensure the directory ends with a directory separator.
		$cache_dir=$cache_dir.DS;
		# Check if the passed directory actually is a directory.
		if(is_dir($cache_dir))
		{
			# Open the directory.
			if($dh=opendir($cache_dir))
			{
				while($file_name=readdir($dh))
				{
					# Make sure it isn't a system file or svn folder.
					if($file_name!='.' && $file_name!='..' && $file_name!='.svn')
					{
						if(is_dir($cache_dir.$file_name))
						{
							$this->cleanCache($cache_dir.$file_name);
						}
						else
						{
							# Get the file's expiration time from it's name.
							$expiration=$this->extrapolateExpiry($file_name);

							# Check if the file is expired.
							if(!empty($expiration)&&$this->getTimeNow()>=$expiration)
							{
								# Remove the file.
								unlink($cachedir.$file_name);
							}
						}
					}
				}
				# Close the directory.
				closedir($dh);
			}
		}
	} #==== End -- cleanCache

	/**
	 * createCacheDir
	 *
	 * Makes the cache directory if it doesn't already exist then makes sure it is writable.
	 *
	 * @param		string		$cache_dir 	The name of the directory.
	 * @access	protected
	 */
	protected function createCacheDir($cache_dir=NULL)
	{
		# Set the passed directory to the data member, effectively cleaning it.
		$this->setCacheDir($cache_dir);
		# Get the cache_dir data member and set it to the local value.
		$cache_dir=$this->getCacheDir();
		# Check if the cache directory exists already and is, in fact, a directory.
		if(file_exists($cache_dir)===FALSE && is_dir($cache_dir)===FALSE)
		{
			# Attempt to create the cache directory.
			if(mkdir($cache_dir)===FALSE)
			{
				throw new Exception("Can't make cache directory at: ".$cache_dir, E_RECOVERABLE_ERROR);
			}
		}
		# Check if the cache directory if readable and writable.
		if((is_readable($cache_dir)==FALSE) && (is_writable($cache_dir)!==TRUE))
		{
			throw new Exception('Cache directory at "'.$cache_dir.'" is not readable and writable!', E_RECOVERABLE_ERROR);
		}
	} #==== End -- createCacheDir

	/**
	 * extrapolateExpiry
	 *
	 * Gets the file's expiration time from it's name. The cache file name will look like
	 * 39284756.unique.name.extension
	 *
	 * @param		$file_name	The name of the cache file. Must be a string.
	 * @access	protected
	 */
	protected function extrapolateExpiry($file_name=NULL)
	{
		# Check if a file name was passed.
		if(empty($file_name))
		{
			$file_name=$this->getCacheName();
		}
		# Get the location of the first "." in the file name.
		$dot_pos=strpos($file_name, '.');
		# Return the extracted file's expiry date from the name of the file.
		return substr_replace($file_name, '', $dot_pos);
	} #==== End -- extrapolateExpiry

	/**
	 * generateFileName
	 *
	 * Generates the cache file's name and sets it to the $cache_name data member. The cache file name
	 * will look like 39284756.unique.name.extension
	 *
	 * @access	protected
	 */
	protected function generateFileName()
	{
		# Set the cache extension name to a local variable.
		$ext=$this->getExt();
		# Set the unique portion of the cache name to a local variable.
		$unique_name=$this->getUniqueName();
		# Add the cache life time to the current time to get the actual expry date.
		$date=$this->getTimeNow()+$this->getCacheTime();
		# Concatonate the elements together and set the chache name to the data member.
		$this->setCacheName($date.'.'.$unique_name.'.'.$ext);
	} #==== End -- generateFileName

	/**
	 * makeCacheFile
	 *
	 * Creates the actual cache file and saves the data to it.
	 *
	 * @access	protected
	 */
	protected function makeCacheFile()
	{
		# Create the chache file name.
		$this->generateFileName();
		# Write the cache file.
		return file_put_contents($this->getCacheDir().$this->getCacheName(), $this->getFileData());
	} #==== End -- makeCacheFile

	/*** End protected methods ***/

} #=== End Cache class.