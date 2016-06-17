<?php /* framework/application/modules/FileHandler/FileHandler.php */

/**
 * FileHandler
 *
 * The FileHandler Class is used to manipulate files and images.
 * =>Command line safe<=
 *
 * @dependencies	Requires "data/path_definitions.php".
 */
class FileHandler
{
	/*** data members ***/

	private $extension='';
	private $max_length_filename=100;
	private $file_size;
	# The width of the image.
	private $width='';
	# The height of the image.
	private $height='';
	# The IMAGETYPE_XXX constant of the image.
	private $imagetype='';
	# The text string with the correct height="yyy" width="xxx" string that can be used directly in an IMG tag.
	private $width_height_string='';
	# The mime type of the image.
	private $mime='';
	# This will be 3 for RGB pictures and 4 for CMYK pictures.
	private $channels='';
	# The number of bits for each color.
	private $bits='';
	# The dimensions (width and height) of the image.
	private $image_info=array();

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setExtension
	 *
	 * Sets the data member $extension. Returns FALSE on failure.
	 *
	 * @param	$extension
	 * @access	public
	 */
	public function setExtension($extension)
	{
		if(isset($extension) && !empty($extension))
		{
			$this->extension=$extension;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setExtension

	/**
	 * setMaxLengthFilename
	 *
	 * Sets the data member $max_length_filename. Returns FALSE on failure.
	 *
	 * @param	$length					The length of the filename. It must be numeric with no decimals.
	 * @access	public
	 */
	public function setMaxLengthFilename($length)
	{
		if(isset($length) && !empty($length) && preg_match('/^[0-9]{1,}$/', $length))
		{
			$this->max_length_filename=(int)$length;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setMaxLengthFilename

	/**
	 * setFileSize
	 *
	 * Sets the data member $file_size. Throws an exception on failure.
	 *
	 * @param	$file_size
	 * @access	public
	 */
	public function setFileSize($file_size)
	{
		if(is_numeric($file_size))
		{
			$this->file_size=$file_size;
		}
		else
		{
			throw new Exception('A valid file size was not set.', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setFileSize

	/**
	 * setWidth
	 *
	 * Sets the data member $width. Returns FALSE on failure.
	 *
	 * @param	$width					Must be numeric.
	 * @access	public
	 */
	public function setWidth($width)
	{
		# Clean it up...
		$width=trim($width);
		if(!empty($width) && is_numeric($width))
		{
			$this->width=$width;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setWidth

	/**
	 * setHeight
	 *
	 * Sets the data member $height. Returns FALSE on failure.
	 *
	 * @param	$height					Must be numeric.
	 * @access	public
	 */
	public function setHeight($height)
	{
		# Clean it up...
		$height=trim($height);
		if(!empty($height) && is_numeric($height))
		{
			$this->height=$height;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setHeight

	/**
	 * setImageType
	 *
	 * Sets the data member $imagetype. Returns FALSE on failure.
	 *
	 * @param	$imagetype				Must be a IMAGETYPE_XXX constant.
	 * @access	public
	 */
	public function setImageType($imagetype)
	{
		# Clean it up...
		$imagetype=trim($imagetype);
		# Check if we have PHP 5.3 or higher.
		if(strnatcmp(floatval(phpversion()),'5.3') < 0)
		{
			if(!defined('IMAGETYPE_UNKNOWN'))
			{
				define('IMAGETYPE_UNKNOWN',0);
			}
			if(!defined('IMAGETYPE_ICO'))
			{
				define('IMAGETYPE_ICO',17);
			}
			if(!defined('IMAGETYPE_COUNT'))
			{
				define('IMAGETYPE_COUNT',18);
			}
		}
		# The IMAGETYPE constants.
		$imagtype_constants=array(
			IMAGETYPE_UNKNOWN,
			IMAGETYPE_GIF,
			IMAGETYPE_JPEG,
			IMAGETYPE_PNG,
			IMAGETYPE_SWF,
			IMAGETYPE_PSD,
			IMAGETYPE_BMP,
			IMAGETYPE_TIFF_II,
			IMAGETYPE_TIFF_MM,
			IMAGETYPE_JPC,
			IMAGETYPE_JP2,
			IMAGETYPE_JPX,
			IMAGETYPE_JB2,
			//IMAGETYPE_SWC, // Constant not supported on some builds of PHP 5.3
			IMAGETYPE_IFF,
			IMAGETYPE_WBMP,
			IMAGETYPE_XBM,
			IMAGETYPE_ICO,
			IMAGETYPE_COUNT);
		# The IMAGETYPE constants' numeric values.
		$imagtype_values=range(0, 18);
		if(in_array($imagetype, $imagtype_constants) || in_array($imagetype, $imagtype_values))
		{
			$this->imagetype=$imagetype;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setImageType

	/**
	 * setWidthHeightString
	 *
	 * Sets the data member $width_height_string. Returns FALSE on failure.
	 *
	 * @param	$string
	 * @access	public
	 */
	public function setWidthHeightString($string)
	{
		# The string should not be empty.
		if(isset($string) && !empty($string))
		{
			# Clean it up and set the Data member.
			$this->width_height_string=strip_tags(strtolower(trim($string)));
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setWidthHeightString

	/**
	 * setMime
	 *
	 * Sets the data member $mime. Returns FALSE on failure.
	 *
	 * @param	$mime
	 * @access	public
	 */
	public function setMime($mime)
	{
		# Clean it up...
		$mime=trim($mime);
		# The Mime types.
		$mimetype_values=array('image/gif', 'image/jpeg', 'image/png', 'application/x-shockwave-flash', 'image/psd', 'image/bmp', 'image/tiff', 'application/octet-stream', 'image/jp2', 'image/iff', 'image/vnd.wap.wbmp', 'image/xbm', 'image/vnd.microsoft.icon');
		if(in_array($mime, $mimetype_values))
		{
			$this->mime=$mime;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setMime

	/**
	 * setChannels
	 *
	 * Sets the data member $channels. Returns FALSE on failure.
	 *
	 * @param	$channels
	 * @access	public
	 */
	public function setChannels($channels)
	{
		# Clean it up...
		$channels=trim($channels);
		# $channels should equal 3 for RGB pictures and 4 for CMYK pictures.
		if(($channels==3) || ($channels==4))
		{
			$this->channels=$channels;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setChannels

	/**
	 * setBits
	 *
	 * Sets the data member $bits. Returns FALSE on failure.
	 *
	 * @param	$bits
	 * @access	public
	 */
	public function setBits($bits)
	{
		# Clean it up...
		$bits=trim($bits);
		# This should not be empty and should be numeric.
		if(!empty($bits) && is_numeric($bits))
		{
			$this->bits=$bits;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- setBits

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getExtension
	 *
	 * Returns the data member $extension. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getExtension()
	{
		if(isset($this->extension) && !empty($this->extension))
		{
			return $this->extension;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getExtension

	/**
	 * getMaxLengthFilename
	 *
	 * Returns the data member $max_length_filename. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getMaxLengthFilename()
	{
		if(isset($this->max_length_filename) && !empty($this->max_length_filename))
		{
			return $this->max_length_filename;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getMaxLengthFilename

	/**
	 * getFileSize
	 *
	 * Returns the data member $file_size.
	 *
	 * @access	public
	 */
	public function getFileSize()
	{
		return $this->file_size;
	} #==== End -- getFileSize

	/**
	 * getWidth
	 *
	 * Returns the data member $width. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getWidth()
	{
		if(isset($this->width) && !empty($this->width))
		{
			return $this->width;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getWidth

	/**
	 * getHeight
	 *
	 * Returns the data member $height. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getHeight()
	{
		if(isset($this->height) && !empty($this->height))
		{
			return $this->height;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getHeight

	/**
	 * getImageType
	 *
	 * Returns the data member $imagetype. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getImageType()
	{
		if(isset($this->imagetype) && !empty($this->imagetype))
		{
			return $this->imagetype;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getImageType

	/**
	 * getWidthHeightString
	 *
	 * Returns the data member $width_height_string. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getWidthHeightString()
	{
		if(isset($this->width_height_string) && !empty($this->width_height_string))
		{
			return $this->width_height_string;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getWidthHeightString

	/**
	 * getMime
	 *
	 * Returns the data member $mime. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getMime()
	{
		if(isset($this->mime) && !empty($this->mime))
		{
			return $this->mime;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getMime

	/**
	 * getChannels
	 *
	 * Returns the data member $channels. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getChannels()
	{
		if(isset($this->channels) && !empty($this->channels))
		{
			return $this->channels;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getChannels

	/**
	 * getBits
	 *
	 * Returns the data member $bits. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getBits()
	{
		if(isset($this->bits) && !empty($this->bits))
		{
			return $this->bits;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getBits

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * checkFileDuplicate
	 *
	 * Checks the passed directory ($dir) for the passed filename ($filename).
	 * If we find the file name, return a message. If not, return FALSE.
	 *
	 * @param	$dir					The directory we're searching.
	 * @param	$filename				The filename we're looking for.
	 * @access	public
	 */
	public function checkFileDuplicate($dir, $filename)
	{
		# Create an instance of the directory class and open the directory.
		$dir=dir($dir);
		# Compare files in $dir folder to filename
		while(($file=$dir->read())!==FALSE)
		{
			if($file==$filename)
			{
				# It is TRUE, we found a duplicate of the filename. Return an error message.
				return 'This filename already exists. Please select another file or change the filename.';
			}
		}
		# We didn't find a duplicate. Return FALSE (ie. Check file dupicate? False.)
		return FALSE;
	} #==== End -- checkFileDuplicate

	/**
	 * checkFileName
	 *
	 * Checks passed filename($the_name) for length and unacceptable characters.
	 * If the file is too long or has unacceptable characters, it returns the error. Otherwise, it returns TRUE.
	 *
	 * @param	$the_name				The filename we're checking
	 * @access	public
	 */
	public function checkFileName($the_name)
	{
		if(!empty($the_name))
		{
			if(strlen($the_name) > $this->getMaxLengthFilename())
			{
				return "The filename exceeds the maximum length of ".$this->getMaxLengthFilename()." characters.";
			}
			else
			{
				if(preg_match("/^[a-z0-9_\.\@\-]{1,}\.[a-z0-9_]{1,5}$/i", $the_name))
				{
					return TRUE;
				}
				else
				{
					return 'The name of your file contains unacceptable characters. Acceptable characters are letters(UPPERCASE or lowercase), numbers, period(.), and underscore(_). Spaces(\' \') are not allowed.';
				}
			}
		}
		else
		{
			return 'There was no filename.';
		}
	} #==== End -- checkFileName

	/**
	 * checkFileType
	 *
	 * Checks passed file($imagename) against array($allowedtypes).
	 * If the file's extension is not in the array, it returns the error. If it is, it returns TRUE.
	 *
	 * @param	$imagename				The file we're checking.
	 * @param	$allowedtypes			Array of allowed file extensions without "."
	 * @access	public
	 */
	public function checkFileType($filename, $allowedtypes=array())
	{
		# Get the file extension.
		$extension=$this->getFileExtension($filename);
		# Check if the extension is allowed.
		if(in_array($extension, $allowedtypes))
		{
			# It is. Return TRUE.
			return TRUE;
		}
		# It's not. Return an error message.
		return 'That file type is not allowed.';
	} #==== End -- checkFileType

	/**
	 * createImageFromSource
	 *
	 * Creates a new image from a source image. Returns FALSE on failure.
	 *
	 * @param	$source					The file we're resizing. Must be a path, not URL.
	 * @access	public
	 */
	public function createImageFromSource($source)
	{
		if($this->getImageType()===FALSE)
		{
			$this->getImageInfo($source);
		}
		# Open a temp of the source image.
		switch ($this->getImageType()) # Assumes that image info has already been set to Data members.
		{
			# If it's a jpg...
			case IMAGETYPE_JPEG:
				$image=@imagecreatefromjpeg($source);
				break;
			# If it's a gif...
			case IMAGETYPE_GIF:
				if($this->isAnimatedGif($source)===TRUE)
				{
					/*
					$gifDecoder=new GIFDecoder(fread(fopen($source, "rb" ), filesize($source)));
					$i=1;
					foreach ($gifDecoder->GIFGetFrames() as $frame)
					{
						if($i < 10)
						{
							fwrite(fopen("frames/frame0$i.gif", "wb" ), $frame);
						}
						else {
							fwrite(fopen("frames/frame$i.gif", "wb"), $frame);
						}
						$i++;
					}
					*/
				}
				$image=@imagecreatefromgif($source);
				break;
			# If it's a png...
			case IMAGETYPE_PNG:
				$image=@imagecreatefrompng($source);
				break;
			# If it's something else, we want to stop and send an error.
			default:
				$image=FALSE;
				break;
		}
		return $image;
	} #==== End -- createImageFromSource

	/**
	 * deleteFile
	 *
	 * Removes the passed file from the system.
	 *
	 * @param	string $source				The complete path to the file. (ie: /home/user/bodega/file.jpg)
	 * @param	boolean $multi_file			TRUE if $source has a wildcard to delete multiple files (ie: /home/user/bodega/file-*.jpg).
	 * @return	boolean						Returns TRUE on success. Returns FALSE if the passed source is not a fvalid file. Throws an exception on failure.
	 * @access	public
	 */
	public function deleteFile($source, $multi_file=FALSE)
	{
		if($multi_file===TRUE)
		{
			$delete_files=array_map('unlink', glob($source));
			# NOTE: Just delete them, if they don't exist, why throw an error?
			/*
			if(empty($delete_files))
			{
				$filenames=implode(',', array_keys($delete_files));
				throw new Exception('There was an error removing the files "'.$filenames.'" from the system!', E_RECOVERABLE_ERROR);
			}
			*/
			return TRUE;
		}
		else
		{
			if(is_file($source))
			{
				if(unlink($source)!==TRUE)
				{
					throw new Exception('There was an error removing the file "'.$source.'" from the system!', E_RECOVERABLE_ERROR);
				}
				return TRUE;
			}
		}
		return FALSE;
	} #==== End -- deleteFile

	/**
	 * editFile
	 *
	 * Edits the contents of a passed file.
	 *
	 * @param 	$file_to_edit			The full path to the file (must include the file name.)
	 * @param 	$content				The content to add to the file.
	 * @param 	$reset					Indicates if the file should get erased.
	 * @return	Boolean					The number of bytes written on success, FALSE on failure.
	 * @access	public
	 */
	public function editFile($file_to_edit='', $content=NULL, $reset=FALSE)
	{
		# Create a variable that indicates if the file was edited. The default is FALSE.
		$edited=FALSE;
		# Create a variable to hold the mode to be used when opening the file.
		#	By default set the mode to be used when opening the file to "a"
		#		(Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it.)
		$mode='a';
		# Check if the file should be reset (erased.)
		if($reset!==FALSE)
		{
			# Attempt to erase the file and set the returned boolean to the reset variable.
			$edited=$this->eraseFile($file_to_edit);
			# Check if edited is still FALSE.
			if($edited===FALSE)
			{
				# Set the mode to "w" to overwrite the document.
				$mode='w';
			}
		}
		# Open the file for writing. If it opened successfully, this variable will indicate that the file is open.
		$opened=fopen($file_to_edit, $mode);
		# Check if there was content passed and that the file is opened..
		if(($content!=='' OR $content!==NULL) && $opened!==FALSE)
		{
			# Write to the file.
			$edited=fwrite($opened, $content);
			# Close the file.
			fclose($opened);
		}
		# Return the value of the "edited" variable. It will be a boolean; true on success, false on failure.
		return $edited;
	} #==== End -- editFile

	/**
	 * eraseFile
	 *
	 * Erases the contents of a file.
	 *
	 * @param 	$path_to_file			The complete path to the file to erase (including the file name.)
	 * @return	Boolean					The number of bytes written on success, FALSE on failure.
	 * @access	public
	 */
	public function eraseFile($path_to_file='')
	{
		# Create a variable to indice if the file was erased. The default is FALSE.
		$erased=FALSE;
		# Check if there was a file path passed.
		if(!empty($path_to_file))
		{
			# Open the file for writing. If it opened successfully, this variable will indicate that the file is open.
			$opened=fopen($path_to_file, 'w');
			# Check if the file opened.
			if($opened!==FALSE)
			{
				# Overwrite the log file with a new line; effectively erasing the file.
				$erased=fwrite($opened, '');
				# Close the file.
				fclose($opened);
			}
		}
		# Return the value of the "erased" variable. The number of bytes written on success, false on failure.
		return $erased;
	} #==== End -- eraseFile

	/**
	 * findFileSize
	 *
	 * Finds the size of a file in bytes.
	 *
	 * @param	$file_path				The path to the file.
	 * @access	public
	 */
	public function findFileSize($file_path)
	{
		$this->setFileSize(filesize($file_path));
		return $this->getFileSize();
	} #==== End -- findFileSize

	/**
	 * findMimeType
	 *
	 * Derives the mime type from a passed file($filename) and returns it. Returns FALSE on failure.
	 *
	 * @param	$filename				The file we're getting the mime type of.
	 * @access	public
	 */
	public function findMimeType($filename)
	{
		if(!empty($filename))
		{
			# Check if the mime_content_type function is installed.
			if(function_exists('mime_content_type'))
			{
				# It is. Use it.
				return mime_content_type($filename);
			}
			# Check if the finfo_open function is installed (PECL must be installed.)
			elseif(function_exists('finfo_open'))
			{
				# It is. Use it.
				$finfo=new finfo(FILEINFO_MIME);
				$mime_type=$finfo->buffer(file_get_contents($filename));
				return $mime_type;
			}
			# We don't have a reliable way of finding the mime type. Return FALSE.
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	} #==== End -- findMimeType

	/**
	 * getFileExtension
	 *
	 * Derives the file extension from a passed file ($filename), sets the $extension data member, and returns it.
	 *
	 * @param	$imagename				The file we're getting the extension of.
	 * @access	public
	 */
	public function getFileExtension($filename)
	{
		# Change to lowercase
		$filename=strtolower($filename);
		# Trim any spaces at end of string. Just in case.
		$filename=rtrim($filename);
		# Get the characters after the last "." That should be the file extension.
		$this->setExtension(substr(strrchr($filename, '.'), 1));
		return $this->getExtension();
	} #==== End -- getFileExtension

	/**
	 * getGDSupportedImageTypes
	 *
	 * Returns the image types (ie. 'jpg', 'jpeg', 'gif' etc) supported by the version of GD linked into the current PHP installation as an array.
	 * Returns FALSE if there no GD image support.
	 * (This method ignores IMG_WBMP and IMG_XPM.)
	 *
	 * @access	public
	 */
	public function getGDSupportedImageTypes()
	{
		$image_types=array();
		# Get image types supported on this build.
		if(imagetypes() & IMG_JPG)
		{
			$image_types[]='jpg';
			$image_types[]='jpeg';
		}
		if(imagetypes() & IMG_GIF)
		{
			$image_types[]='gif';
		}
		if(imagetypes() & IMG_PNG)
		{
			$image_types[]='png';
		}
		if(count($image_types)==0)
		{
			return FALSE;
		}
    	else
    	{
    		return $image_types;
    	}
	} #==== End -- getGDSupportedImageTypes

	/**
	 * getImageInfo
	 *
	 * Gets information about the passed image ($imagename) and stores it in an array. Returns FALSE on failure.
	 * Index 0 and 1 contains respectively the width and the height of the image.
	 * Index 2 is one of the IMAGETYPE_XXX constants indicating the type of the image.
	 * Index 3 is a text string with the correct height="yyy" width="xxx" string that can be used directly in an IMG tag.
	 * mime is the correspondant MIME type of the image. This information can be used to deliver images with correct the HTTP Content-type header.
	 * channels will be 3 for RGB pictures and 4 for CMYK pictures.
	 * bits is the number of bits for each color.
	 * This function also sets the image_info, width, height, imagetype, width_height_string, mime, channels, and bits class data members.
	 *
	 * @param	$imagename				The name of the image.
	 * @access	public
	 */
	public function getImageInfo($imagename)
	{
		# If the file exists.
		if(file_exists($imagename))
		{
			# Get information about the image.
			$this->image_info=getimagesize($imagename);
			$this->setWidth($this->image_info[0]);
			$this->setHeight($this->image_info[1]);
			$this->setImageType($this->image_info[2]);
			$this->setWidthHeightString($this->image_info[3]);
			$this->setMime($this->image_info['mime']);
			# Check if there is a value for 'channels' (must be a 3 for RGB or a 4 for CMYK.)
			if(!empty($this->image_info['channels']))
			{
				$this->setChannels($this->image_info['channels']);
			}
			$this->setBits($this->image_info['bits']);
			return $this->image_info;
		}
		return FALSE;
	} #==== End -- getImageInfo

	/**
	 * getImageTypeExtenstion
	 *
	 * Returns the file extension of the passed image type constant. Returns NULL on failure.
	 *
	 * @param	$type					The image type constant - ie IMAGE_JPG.
	 * @param	$dot					Whether to prepend a dot to the extension or not. Default to TRUE.
	 * @access	public
	 */
	public function getImageTypeExtenstion($type, $dot=TRUE)
	{
		# If we are using PHP earlier than 5.3...
		if(!function_exists('image_type_to_extension'))
		{
			$e=array(1 => 'gif', 'jpeg', 'png', 'swf', 'psd', 'bmp', 'tiff', 'tiff', 'jpc', 'jp2', 'jpf', 'jb2', 'swc', 'aiff', 'wbmp', 'xbm');
			# We are expecting an integer.
			$type=(int)$type;
			if(!isset($type) || empty($type) || !is_numeric($type))
			{
				trigger_error('Invalid IMAGETYPE_XXX constant.', E_USER_NOTICE);
				return NULL;
			}
			if(!isset($e[$type]))
			{
				trigger_error('That IMAGETYPE cannot be found.', E_USER_NOTICE);
				return NULL;
			}
			$extension=(($dot) ? '.' : '').$e[$type];
		}
		else
		{
			$extension=image_type_to_extension($type, $dot);
		}
		return $extension;
	} #==== End -- getImageTypeExtenstion

	/**
	 * getImageTypeMimeType
	 *
	 * Returns the mime type of the passed image type constant. Returns NULL on failure.
	 *
	 * @param	$type					The image type constant - ie IMAGE_JPG.
	 * @access	public
	 */
	public function getImageTypeMimeType($type='')
	{
		# We are expecting an integer.
		$type=(int)$type;
		# Check if the passed image_type ($type) is empty or not numeric.
		if(empty($type) || !is_numeric($type))
		{
			# Check if we have an IMAGE_TYPE already set to a Data Member.
			if($this->getImageType()!==FALSE)
			{

				$type=$this->getImageType();
			}
			else
			{
				trigger_error('Invalid IMAGETYPE_XXX constant.', E_USER_NOTICE);
				return NULL;
			}
		}
		# If we are using PHP earlier than 5.3...
		if(!function_exists('image_type_to_mime_type'))
		{
			$m=array(1 => 'image/gif', 'image/jpeg', 'image/png', 'application/x-shockwave-flash', 'image/psd', 'image/bmp', 'image/tiff', 'image/tiff', 'application/octet-stream', 'image/jp2', 'application/octet-stream', 'application/octet-stream', 'application/x-shockwave-flash', 'image/iff', 'image/vnd.wap.wbmp', 'image/xbm');
			if(!isset($m[$type]))
			{
				trigger_error('That IMAGETYPE cannot be found.', E_USER_NOTICE);
				return NULL;
			}
			$mime=$m[$type];
		}
		else
		{
			$mime=image_type_to_mime_type($type);
		}
		return $mime;
	} #==== End -- getImageTypeMimeType

	/**
	 * getMimeTypeFromExtension
	 *
	 * Derives the mime type from a passed file's($filename) extension and returns it. Returns FALSE on failure.
	 *
	 * @param	$filename				The file we're getting the mime type of.
	 * @access	public
	 */
	public function getMimeTypeFromExtension($filename)
	{
		if(!empty($filename))
		{
			$mime_types=array(
				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',

				# images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',

				# archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',

				# audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',

				# adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',

				# ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
				'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

				# open office
				'odf' => 'application/vnd.oasis.opendocument.formula',
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);
			if(array_key_exists($this->getFileExtension($filename), $mime_types))
			{
				return $mime_types[$this->getExtension()];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	} #==== End -- getMimeTypeFromExtension

	/**
	 * isAnimatedGif
	 *
	 * Checks if the passed gif is animated.
	 * Returns TRUE if it is, FALSE if it is not.
	 * If the passed file is not a gif, it returns NULL and sends an error message.
	 *
	 * @param	$gif					The file we're checking. Must be a path, not URL.
	 * @access	public
	 */
	public function isAnimatedGif($gif)
	{
		if($this->getImageType()===FALSE)
		{
			$this->getImageInfo($gif);
		}
		if($this->getImageType()==IMAGETYPE_GIF)
		{
			if(!($fh=@fopen($gif, 'rb')))
			{
				return FALSE;
			}
			$count=0;
			/*
			An animated gif contains multiple "frames", with each frame having a header made up of:
			* a static 4-byte sequence (\x00\x21\xF9\x04)
			* 4 variable bytes
			* a static 2-byte sequence (\x00\x2C)
			*/

			# Read through the file til the end of the file or a 2nd frame header.
			while(!feof($fh) && $count < 2)
			{
				$chunk = fread($fh, 1024 * 100); //read 100kb at a time
				$count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00\x2C#s', $chunk, $matches);
			}
			fclose($fh);
			if($count > 1)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			trigger_error('The file is not a gif image.', E_USER_NOTICE);
			return NULL;
		}
	} #==== End -- isAnimatedGif

	/**
	 * reduceImage
	 *
	 * Resizes an image and saves the new image to ($target) folder.
	 *
	 * @param	$source					The file we're resizing. Must be a path, not URL.
	 * @param	$new_width				Width to resize to. Default is 0.
	 * @param	$new_height				Height to resize to. Default is 0.
	 * @param	$target					The folder and name of new file to save the resized image to. Must be a path, not URL.
	 * @param	$quality				The quality to resize to: 1 to 100. Default is 75.
	 * @param	$proportional			TRUE to maintain aspect ratio. FALSE won't. Default is TRUE.
	 * @param	$output					Where to output the file to. Options are: browser, file, return. Default is file.
	 * @param	$delete_original		TRUE to delete original file. Default is FALSE.
	 * @param	$use_linux_commands		TRUE to use linux command when dealing with files. Default is FALSE.
	 * @access	public
	 */
	public function reduceImage($source, $target, $new_width=0, $new_height=0, $quality=75, $proportional=TRUE, $output='file', $delete_original=FALSE, $use_linux_commands=FALSE)
	{
		# Get information about the uploaded image.
		if($this->getImageInfo($source)===FALSE)
		{
			return 'Couldn\'t get image info.';
		}

		# Make sure the image type and the extension match.
		if($this->validateExtensionFromImageType($source)===TRUE)
		{
			# The image type and extension match, we may proceed.
			# Make sure image is not already smaller than target thumbnail size.
			if(($new_width >= $this->getWidth()) && ($new_height >= $this->getHeight()))
			{
				# Our image is already smaller than the target thumbnail size, so we won't be resizing it.
				$resize=FALSE;
			}
			else
			{
				$resize=TRUE;
				# If we want to maintain aspect ratio.
				if($proportional===TRUE)
				{
					# Scale image maintaining aspect ratio.
					$ratio=$this->getWidth()/$this->getHeight();
		     		if($ratio >= 1) $scale=$new_width/$this->getWidth();
		     		else $scale=$new_height/$this->getHeight();
			     	$final_width=$this->getWidth()*$scale;
					$final_height=$this->getHeight()*$scale;
				}
				# Don't maintain aspect ratio.
				else
				{
					# Set the final width and height.
					$final_width=($new_width <= 0) ? $this->getWidth() : $new_width;
					$final_height=($new_height <= 0) ? $this->getHeight() : $new_height;
				}
			} # End else

			if($resize===TRUE)
			{
				# Open a temp of the source image.
				$image_in=$this->createImageFromSource($source);

				# See if the open worked.
				if($image_in!==FALSE)
				{
					# Create a temp of the newly resized image.
					$image_out=imagecreatetruecolor($final_width, $final_height);
					if($image_out !== FALSE)
					{
						# Resize the image.
						if($this->resizeImageByType($image_in, $image_out, $this->getWidth(), $this->getHeight(), $final_width, $final_height, $this->getImageType())===TRUE)
						{
							# Save the image.
							if($this->saveImageAs($image_out, $target, $quality, $this->getImageType())===TRUE)
							{
								# Destroy our temp images.
								imagedestroy($image_out);
								imagedestroy($image_in);
								if($delete_original===TRUE)
								{
									if(unlink($source)===FALSE) return 'Couldn\'t delete the original image!';
								}
								return TRUE;
							}
							else return 'Couldn\'t save the new image!';
						}
						else return 'Couldn\'t resize the image!';
					} # End if
					else return 'Couldn\'t create a true color image!';
				} # End if
				else return 'Couldn\'t open the file!';
			} # End if
			else
			{
				# Don't resize it. Copy it to the destination folder.
				if(copy($source, $target)===TRUE) return TRUE;
				else return 'Couldn\'t copy the image to the thumbnail folder!';
			}
		} # End if
		else return 'The file extension does not match this image type!';
	} #==== End -- reduceImage

	/**
	 * cleanFilename
	 *
	 * Cleans the file name.
	 *
	 * @param	string $filename		The filename we're cleaning.
	 * @return	string $filename
	 * @access	public
	 */
	public function cleanFilename($filename)
	{
		# Use the title up till any colon.
		$clean_filename=substr_replace($filename, '', strpos($filename.':', ':'));

		# Create an array of characters/patterns to replace.
		$pattern=array('/-\ +/', '/[\ +,+]/', '/\.+/', '/[\&+\^+@+\#+$+\%+\*+:+;+\!+\?+]/', '/-+/', '/(\.-)+/', '/(-\.)+/', '/_+/', '/(\._)+/', '/(_\.)+/', '/(_-)+/', '/(-_)+/', '/[\'+\"+]/');
		# Create an array of corresponding replacements.
		$replacement=array('-', '.', '.', '_', '-', '-', '-', '_', '_', '_', '_', '_', '');
		# Replace defined characters and patterns as well as any special characters.
		$clean_filename=Utility::htmlToText(preg_replace($pattern, $replacement, $clean_filename));
		# Trim any '.' or '_' off the ends.
		$filename=trim(substr_replace($clean_filename, '', 255), '. _');

		return $filename;
	} #==== End -- cleanFilename

	/**
	 * resizeImageType
	 *
	 * Saves a new image to the $target as the specified image type.
	 * Returns TRUE on success and FALSE on failure.
	 *
	 * @param	$image_in				Source image link resource.
	 * @param	$image_out				Destination image link resource.
	 * @param	$old_width				Source width.
	 * @param	$old_height				Source height.
	 * @param	$final_width			Destination width.
	 * @param	$final_height			Destination height.
	 * @param	$imagetype				Must be a IMAGETYPE_XXX constant or equivalant int value.
	 *										It may be left out if the $imagetype data member is already set.
	 * @access	public
	 */
	public function resizeImageByType($image_in, $image_out, $old_width, $old_height, $final_width, $final_height, $imagetype=NULL)
	{
		# Check if the $imagetype datamember is set.
		if($this->getImageType()===FALSE)
		{
			# It's NOT set. Check if the imagetype was passed to the method.
			if($imagetype === NULL)
			{
				# It WASN'T. Return an error message.
				trigger_error('No IMAGETYPE_XXX constant provided.', E_USER_NOTICE);
				return FALSE;
			}
			else
			{
				$type=$imagetype;
			} # The image type was passed to the method. Use that.
		}
		else
		{
			$type=$this->getImageType();
		} # The $imagetype data member was set. Use that.
		# Resize the image.
		switch ($type)
		{
			# If it's a jpg...
			case IMAGETYPE_JPEG:
				if(imagecopyresampled($image_out, $image_in, 0, 0, 0, 0, $final_width, $final_height, $old_width, $old_height)===TRUE)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
				break;
			# If it's a gif...
			case IMAGETYPE_GIF:
				imagealphablending($image_out, FALSE);
				imagesavealpha($image_out, TRUE);
				$transparent=imagecolorallocatealpha($image_out, 255, 255, 255, 127);
				imagefilledrectangle($image_out, 0, 0, $final_width, $final_height, $transparent);
				if(imagecopyresized($image_out, $image_in, 0, 0, 0, 0, $final_width, $final_height, $old_width, $old_height)===TRUE)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
				break;
			# If it's a png...
			case IMAGETYPE_PNG:
				imagealphablending($image_out, FALSE);
				imagesavealpha($image_out, TRUE);
				$transparent=imagecolorallocatealpha($image_out, 255, 255, 255, 127);
				imagefilledrectangle($image_out, 0, 0, $final_width, $final_height, $transparent);
				if(imagecopyresampled($image_out, $image_in, 0, 0, 0, 0, $final_width, $final_height, $old_width, $old_height)===TRUE)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
				break;
			default:
				return FALSE;
		} # End switch
	} #==== End -- resizeImageType

	/**
	 * saveImageAs
	 *
	 * Saves a new image to the $target as the specified image type.
	 * Returns TRUE on success and FALSE on failure.
	 *
	 * @param	$image					An image resource, returned by one of the image creation functions, such as imagecreatetruecolor().
	 * @param	$target					The path to save the file to. Must be a path, not a URL. If not set or NULL, the raw image stream will be outputted directly.
	 * @param	$quality				For jpg only: Ranges from 0 (worst quality, smaller file) to 100 (best quality, biggest file).
	 * @param	$imagetype				Must be a IMAGETYPE_XXX constant or equivalant int value. It may be left out if the $imagetype data member is already set.
	 * @access	public
	 */
	public function saveImageAs($image, $target, $quality=75, $imagetype=NULL)
	{
		# Check if the $imagetype datamember is set.
		if($this->getImageType()===FALSE)
		{
			# It's NOT set. Check if the imagetype was passed to the method.
			if($imagetype===NULL)
			{
				# It WASN'T. Return an error message.
				trigger_error('No IMAGETYPE_XXX constant provided.', E_USER_NOTICE);
				return FALSE;
			}
			else
			{
				$type=$imagetype;
			} # The image type was passed to the method. Use that.
		}
		else
		{
			$type=$this->getImageType();
		} # The $imagetype data member was set. Use that.
		# Save the new image to the target folder.
		switch ($type)
		{
			# If it's a jpg...
			case IMAGETYPE_JPEG:
				if(imagejpeg($image, $target, $quality)===TRUE)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
				break;
			# If it's a gif...
			case IMAGETYPE_GIF:
				if(imagegif($image, $target)===TRUE)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
				break;
			# If it's a png...
			case IMAGETYPE_PNG:
				if(imagepng($image, $target)===TRUE)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
				break;
			default:
				# Not an acceptable IMAGETYPE_XXX constant. Return FALSE.
				return FALSE;
		} # End switch
	} #==== End -- saveImageAs

	/**
	 * serveAudio
	 *
	 * @param	$file					The name of the file to serve.
	 * @param	$premium				TRUE if the file is a "premium" file, NULL if it is not..
	 * @access	public
	 */
	public function serveAudio($file, $premium=NULL)
	{
		# Check if the passed file is "premium" content.
		if(!empty($premium))
		{
			# Explicitly set the variable to the premium folder name.
			$premium='premium'.DS;
		}
		# Check if there was a file passed.
		if(empty($file))
		{
			# Throw an error.
			throw new Exception('The audio file wasn\'t set.');
		}
		else
		{
			if($this->checkFileName($file)!==TRUE)
			{
				# Throw an error.
				throw new Exception('Unacceptable audio file name.');
			}
		}
		# Check if the file exists in  the system.
		if(!file_exists(BODEGA.$premium.$file))
		{
			# Throw an error.
			throw new Exception('The audio file doesn\'t exist.');
		}

		$mime=$this->getMimeTypeFromExtension($file);

		# Send a header with the file attached.
		header('Content-Disposition: attachment; filename="'.$file.'"');
		# Send a header with the MP3 content type.
		header('Content-type: '.$mime);
		# Send a header telling the browser not to cache the page.
		header('Cache-Control: no-cache, must-revalidate');
		# Send a header with an expireation in the past.
		header('Expires: Sun, 18 May 1980 08:32:00 PST');
		# Read the file and write it to the output buffer.
		readfile(BODEGA.$premium.$file);
	} #==== End -- serveAudio

	/**
	 * validateExtensionFromImageType
	 *
	 * Validates the file extension against the IMAGETYPE_XXX constant.
	 * returns TRUE if they match, FALSE if they don't.
	 *
	 * @param	$source					The image file. Must be a path, not URL.
	 * @access	public
	 */
	public function validateExtensionFromImageType($source)
	{
		# Get the file extension of the image from the file name.
		$file_ext=$this->getFileExtension($source);
		# Check if the image type has been set.
		if($this->getImageType()===FALSE)
		{
			# Get the image info and set the image data members.
			$this->getImageInfo($source);
		}
		# Get the file extension of the image from the IMAGETYPE_XXX constant.
		$image_ext=$this->getImageTypeExtenstion($this->getImageType(), FALSE);

		# Make sure the image type and the extension match.
		# jpeg image type matches the jpg extension.
		if(($image_ext=='jpeg') && ($file_ext=='jpg'))
		{
			$ext_match=TRUE;
		}
		# They match if the extension and the image type are the same.
		elseif($image_ext == $file_ext)
		{
			$ext_match=TRUE;
		}
		else
		{
			# We don't have a match.
			$ext_match=FALSE;
		}
		return $ext_match;
	} #==== End -- validateExtensionFromImageType

	/*** End public methods ***/

} # End FileHandler class.