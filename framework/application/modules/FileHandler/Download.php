<?php /* Requires PHP5+ */

###################################################################
# File Download 2.0
###################################################################
# Adapted to PHP5 by BigTalk Jon Ryser http://JonRyser.com
# Visit http://www.zubrag.com/scripts/ for original script updates
###################################################################
# Sample download link:
#    download_page.php?f=somefile.zip
#
# Sample download link (browser will try to save with new file name):
#    download_page.php?f=somefile.zip&fc=samefile_newname.zip
###################################################################

# Get the parent class.
require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
# Get config file
require_once Utility::locateFile(MODULES.'FileHandler'.DS.'DownloadConfig.php');

/**
* Download
*
* The Download Class is used to force download files of pre-determined types and control which users with which privileges may download.
*
*/
class Download extends FileHandler
{
	/*** data members ***/

	private $allowed_ext;
	private $index;
	private $file_name;
	private $download_name;
	private $file_path;
	private $mime_type;

	/*** End data members ***/



	/*** magic methods ***/

	# Constructor
	public function __construct($path='', $index='f', $time_limit=0, $new_name_index='fc')
	{
		global $allowed_ext;

		$this->checkAllowedReferrer();
		$this->setAllowedExt($allowed_ext);
		$this->setIndex($index);
		$this->setTimeLimit($time_limit);
		$filename=$this->getFileName();
		$this->setFilePath(BASE_DIR.$path.$filename);
		$file_path=$this->getFilePath();
		//echo $_SERVER['HTTP_RANGE']; exit;
		$available=$this->checkFileDuplicate(BASE_DIR.$path, $filename);
		if($available===FALSE)
		{
			throw new Exception('The file you have requested is not available.', E_RECOVERABLE_ERROR);
		}
		$file_size=$this->findFileSize($file_path);
		$extension=$this->getFileExtension($filename);
		$this->checkAllowedExtension();
		if($allowed_ext[$extension]=='')
		{
			$mtype=$this->getMimeType($filename);
			if($mtype===FALSE)
			{
				$mtype='';
			}
			if($mtype=='')
			{
				$mtype="application/force-download";
			}
		}
		else
		{
			# Get mime type defined in DownloadConfig.php
			$mtype=$allowed_ext[$extension];
		}
		$this->changeFileName($new_name_index);
		$save_as=$this->getDownloadName();
		# Turn off output buffering to decrease cpu usage.
		@ob_end_clean();
		# required for IE, otherwise Content-Disposition may be ignored
		if(ini_get('zlib.output_compression'))
		{
			ini_set('zlib.output_compression', 'Off');
		}
		# set headers
		header("Content-Description: File Transfer");
		header("Content-Type: $mtype");
		header("Content-Disposition: attachment; filename=\"$save_as\"");
		header("Content-Transfer-Encoding: binary");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Pragma: public");
		header("Expires: 0");

		# multipart-download and download resuming support
		if(isset($_SERVER['HTTP_RANGE']))
		{
			list($a, $range)=explode("=", $_SERVER['HTTP_RANGE'], 2);
			list($range)=explode(",", $range, 2);
			list($range, $range_end)=explode("-", $range);
			$range=intval($range);
			if(!$range_end)
			{
				$range_end=$file_size-1;
			}
			else
			{
				$range_end=intval($range_end);
			}
			$new_length=$range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$file_size");
		}
		else
		{
			$range=NULL;
			$new_length=$file_size;
			header("Content-Length: ".$file_size);
		}
		$this->outputFile($new_length, $range);
		$this->creatLog();
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	* setAllowedExt
	*
	* Sets the data member $allowed_ext.
	*
	* @param	$allowed_ext (The file types to allow download.)
	* @access	private
	*/
	private function setAllowedExt($allowed_ext)
	{
		# Make sure the $allowed_ext variable is an array and set the data member.
		$this->allowed_ext=(array)$allowed_ext;
	} #==== End -- setAllowedExt

	/**
	* setIndex
	*
	* Sets the data member $index.
	*
	* @param	$index (The name of the GET Data index to use.)
	* @access	private
	*/
	private function setIndex($index)
	{
		$this->index=trim($index);
	} #==== End -- setIndex

	/**
	* setFileName
	*
	* Sets the data member $file_name.
	* Removes any path info to avoid hacking by adding relative path, etc.
	*
	* @param	$file_name (The name of the file.)
	* @access	private
	*/
	private function setFileName($file_name)
	{
		$file_name=trim($file_name);
		$this->file_name=basename($file_name);
	} #==== End -- setFileName

	/**
	* setDownloadName
	*
	* Sets the data member $download_name.
	* Removes any path info to avoid hacking by adding relative path, etc.
	*
	* @param	$download_name (The new name of the file.)
	* @access	private
	*/
	private function setDownloadName($download_name)
	{
		$download_name=trim($download_name);
		$this->download_name=basename($download_name);
	} #==== End -- setDownloadName

	/**
	* setFilePath
	*
	* Sets the data member $file_path.
	*
	* @param	$file_path (The path to the file.)
	* @access	private
	*/
	private function setFilePath($file_path)
	{
		$this->file_path=$file_path;
	} #==== End -- setFilePath

	/**
	* setMime
	*
	* Sets the data member $mime_type.
	*
	* @param	$mime (The mime type of the file.)
	* @access	public
	*/
	public function setMime($mime)
	{
		$this->mime_type=$mime;
	} #==== End -- setMime

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	* getAllowedExt
	*
	* Returns the data member $allowed_ext. Throws an error on failure.
	*
	* @access	private
	*/
	private function getAllowedExt()
	{
		return $this->allowed_ext;
	} #==== End -- getAllowedExt

	/**
	* getIndex
	*
	* Returns the data member $index. Throws an error on failure.
	*
	* @access	private
	*/
	private function getIndex()
	{
		return $this->index;
	} #==== End -- getIndex

	/**
	* getFileName
	*
	* Returns the data member $file_name. Throws an error on failure.
	*
	* @access	private
	*/
	private function getFileName()
	{
		return $this->file_name;
	} #==== End -- getFileName

	/**
	* getDownloadName
	*
	* Returns the data member $download_name.
	*
	* @access	private
	*/
	private function getDownloadName()
	{
		return $this->download_name;
	} #==== End -- getDownloadName

	/**
	* getFilePath
	*
	* Returns the data member $file_path. Throws an error on failure.
	*
	* @access	private
	*/
	private function getFilePath()
	{
		return $this->file_path;
	} #==== End -- getFilePath

	/**
	* getMime
	*
	* Returns the data member $mime_type.
	*
	* @access	public
	*/
	public function getMime()
	{
		return $this->mime_type;
	} #==== End -- getMime

	/*** End accessor methods ***/



	/*** public methods ***/

	/*** End public methods ***/



	/*** private methods ***/

	/**
	* checkAllowedReferrer
	*
	* Checks if hotlinking is not allowed. If not, makes hackers think there are server problems.
	*
	* @access private
	*/
	private function checkAllowedReferrer()
	{
		if(isset($_SERVER['HTTP_REFERER']) && ALLOWED_REFERRER!=='')
		{
			if(strpos(strtoupper($_SERVER['HTTP_REFERER']), strtoupper(ALLOWED_REFERRER))===FALSE)
			{
				die("Internal server error. Please contact system administrator.");
			}
		}
	} # ----End checkAllowedReferrer

	/**
	* setTimeLimit
	*
	* Makes sure program execution doesn't time out. Also checks if a filename was sent via GET data and sets the $fie_name data member.
	*
	* @param	$time_limit (The maximum script execution time in seconds. Default 0 means no limit.)
	* @access private
	*/
	private function setTimeLimit($time_limit=0)
	{
		set_time_limit(0);

		if(!isset($_GET[$this->getIndex()]) || empty($_GET[$this->getIndex()]))
		{
			throw new Exception("Please specify file name for download.", E_USER_WARNING);
		}
		else
		{
			$this->setFileName($_GET[$this->getIndex()]);
		}
	} # ----End setTimeLimit

	/**
	* checkAllowedExtension
	*
	* Checks if the extension is allowed.
	*
	* @access private
	*/
	private function checkAllowedExtension()
	{
		$ext=$this->getExtension();
		$allowed_ext=$this->getAllowedExt();
		if(!array_key_exists($ext, $allowed_ext))
		{
			throw new Exception('File type not allowed.', E_USER_WARNING);
		}
	} # ----End checkAllowedExtension

	/**
	* getMimeType
	*
	* Derives the mime type from a passed file($filename) and returns it. Returns FALSE on failure.
	*
	* @param	$filename (The file we're getting the mime type of.)
	* @access	public
	*/
	public function getMimeType($filename)
	{
		$mime_type=$this->findMimeType($filename);
		if($mime_type!==FALSE)
		{
			$this->setMime($mime_type);
			return $this->getMime();
		}
		else { return FALSE; }
	} #==== End -- getMimeType

	/**
	* changeFileName
	*
	* The browser will try to save file with this filename, regardless original filename.
	*
	* @param	$new_name_index (The name of the GET Data index to use.)
	* @access	public
	*/
	public function changeFileName($new_name_index='fc')
	{
		if(!isset($_GET[$new_name_index]) || empty($_GET[$new_name_index]))
		{
			$this->setDownloadName($this->getFileName());
		}
		else
		{
			# remove some bad chars
			$asfname=str_replace(array('"',"'",'\\','/'), '', $_GET[$new_name_index]);
			if($asfname==='') { $asfname='NoName'; }
			$this->setDownloadName($asfname);
		}
	} #==== End -- changeFileName

	/**
	* outputFile
	*
	* Output the file itself.
	*
	* @param	$range
	* @param	$new_length
	* @access	public
	*/
	public function outputFile($new_length, $range=NULL)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		$bytes_send=0;
		$file_path=$this->getFilePath();
		if($file=fopen($file_path, 'rb'))
		{
			if(isset($_SERVER['HTTP_RANGE']))
			{
				fseek($file, $range);
			}
			while(!feof($file) && (!connection_aborted()) && ($bytes_send<$new_length))
			{
				$buffer=fread($file, CHUNKSIZE);
				print($buffer); //echo($buffer); # is also possible
				flush();
				$bytes_send += strlen($buffer);
			}
			if(fclose($file))
			{
				if(isset($_SESSION['_post_login']))
				{
					$doc->redirect($_SESSION['_post_login']);
				}
				else
				{
					$doc->redirect(APPLICATION_URL);
				}
			}
		}
		else
		{
			throw new Exception('I couldn\'t open file to download.', E_RECOVERABLE_ERROR);
		}
	} #==== End -- outputFile

	/**
	* creatLog
	*
	* Creates the Download log file..
	*
	* @access	public
	*/
	public function creatLog()
	{
		if(LOG_DOWNLOADS===FALSE) die();

		$fname=$this->getFileName();
		$f=@fopen(LOG_FILE, 'a+');
		if($f)
		{
			@fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
			@fclose($f);
		}
	} #==== End -- creatLog

	/*** End private methods ***/

} # End Download class.