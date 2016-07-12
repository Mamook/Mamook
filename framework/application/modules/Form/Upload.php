<?php /* Requires PHP5+ */

class Upload
{
	/*** data members ***/

	private $errors=array();
	private $file;
	private $name;
	private $temp_name;
	private $type;
	private $size=NULL;
	private $ext;

	/*** End data members ***/



	/*** magic methods ***/

	# Constructor
	public function __construct($file)
	{
		# If there was an uploaded file, we process it.
		if((is_uploaded_file($file['tmp_name'])===TRUE) && ($file['error'] !== UPLOAD_ERR_NO_FILE))
		{
			$this->setFile($file);
			$this->setName($file['name']);
			$this->setTempName($file['tmp_name']);
			$this->setType($file['type']);
			$this->setSize($file['size']);
		}
		else
		{
			$this->setErrors('There was no file uploaded.');
			return FALSE;
		}
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	* setErrors
	*
	* Sets the data member $errors.
	*
	* @param	$error (The error string to set.)
	* @access	public
	*/
	public function setErrors($error)
	{
		$error=trim($error);
		$this->errors[]=$error;
	} #==== End -- setErrors

	/**
	* setFile
	*
	* Sets the data member $file.
	*
	* @param	$file (The uploaded file.)
	* @access	public
	*/
	public function setFile($file)
	{
		$this->name=$file;
	} #==== End -- setFile

	/**
	* setName
	*
	* Sets the data member $name.
	*
	* @param	$name (The name of the file.)
	* @access	public
	*/
	public function setName($name)
	{
		$this->name=$name;
	} #==== End -- setName

	/**
	* setTempName
	*
	* Sets the data member $temp_name.
	*
	* @param	$temp_name (PHP's temporary name of the file.)
	* @access	public
	*/
	public function setTempName($temp_name)
	{
		$this->temp_name=$temp_name;
	} #==== End -- setTempName

	/**
	* setType
	*
	* Sets the data member $type.
	*
	* @param	$type (The type of file.)
	* @access	public
	*/
	public function setType($type)
	{
		$this->type=$type;
	} #==== End -- setType

	/**
	* setSize
	*
	* Sets the data member $name.
	*
	* @param	$size (The size of the file in bytes.)
	* @access	public
	*/
	public function setSize($size)
	{
		$this->size=$size;
	} #==== End -- setSize

	/**
	* setExt
	*
	* Sets the data member $ext.
	*
	* @param	$ext (The extension of the file.)
	* @access	public
	*/
	public function setExt($ext)
	{
		$this->ext=$ext;
	} #==== End -- setExt

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	* getErrors
	*
	* Returns the data member $errors.
	*
	* @access	public
	*/
	public function getErrors()
	{
		return $this->errors;
	} #==== End -- getErrors

	/**
	* getFile
	*
	* Returns the data member $file.
	*
	* @access	public
	*/
	public function getFile()
	{
		return $this->file;
	} #==== End -- getFile

	/**
	* getName
	*
	* Returns the data member $name.
	*
	* @access	public
	*/
	public function getName()
	{
		return $this->name;
	} #==== End -- getName

	/**
	* getTempName
	*
	* Returns the data member $temp_name.
	*
	* @access	public
	*/
	public function getTempName()
	{
		return $this->temp_name;
	} #==== End -- getTempName

	/**
	* getType
	*
	* Returns the data member $type.
	*
	* @access	public
	*/
	public function getType()
	{
		return $this->type;
	} #==== End -- getType

	/**
	* getSize
	*
	* Returns the data member $size.
	*
	* @access	public
	*/
	public function getSize()
	{
		return $this->size;
	} #==== End -- getSize

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

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * uploadFile
	 *
	 * Uploads a user's File.
	 *
	 * @param	$source_path			The path to the folder where we will keep the file.
	 * @param	$allowed_ext			The acceptable file types.
	 * @param	$target_path			The complete path to where the copy will be stored.
	 * @param	$new_name				The name to be given to the uploaded file.
	 * @param	$max_size				The maximum allowed size of the uploaded file.
	 * @param	$resize					TRUE to resize the uploaded image, FALSE to leave it alone.
	 * @param	$max_width				The maximum width of the resized image.
	 * @param	$max_height				The maximum width of the resized image.
	 * @param	$quality				The quality of the resized image.
	 * @return	bool
	 * @access	public
	 */
	public function uploadFile($source_path, $allowed_ext, $target_path, $new_name=NULL, $max_size=7340032, $resize=TRUE, $max_width=120, $max_height=90, $quality=75)
	{
		if($this->checkErrors()===FALSE)
		{
			# Get Image class.
			require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
			# Create new $file object.
			$file=new FileHandler();

			# Create variables for file related data members.
			$name=$this->getName();
			$temp_name=$this->getTempName();
			$type=$this->getType();
			$size=$this->getSize();
			$ext=$file->getFileExtension($name);
			# Set the extension to the ext data member.
			$this->setExt($ext);

			# Create an empty variable that indicates whether the file was uploaded or not. Default is FALSE.
			$uploaded=FALSE;

			# Create an empty variable that assumes the file is not an image.
			$image=FALSE;
			# Create an empty variable to hold the array of allowed document extensions.
			$doc_ext=array();

			# Create a variable that assumes there is no GD library support.
			$image_ext=FALSE;

			# Loop through allowed extensions.
			foreach($allowed_ext as $extension)
			{
				# Check if 'images' has been passed as an allowed exception.
				if($extension!=='images')
				{
					# Add the exception to the $doc_ext array (excluding image extensions).
					$doc_ext[]=$extension;
				}
			}
			# Get the servers GD Library supported extensions and set them to a variable.
			$image_ext=$file->getGDSupportedImageTypes();
			# Check if the uploaded files extension is in the supported image extension array.
			if(in_array($ext, $image_ext))
			{
				# The uploaded file is an image.
				$image=TRUE;
			}

			# Check if there is GD Library support and if the uploaded file is an image.
			if(($image_ext!==FALSE) && ($image===TRUE))
			{
				try
				{
					# Upload the image.
					$uploaded=$this->uploadImage($source_path, $target_path, $new_name, $max_size, $resize, $max_width, $max_height, $quality);
				}
				catch(Exception $e)
				{
					throw $e;
				}
			}
			elseif(in_array($ext, $doc_ext))
			{
				try
				{
					# Upload the document.
					$uploaded=$this->uploadDoc($source_path, $allowed_ext, $new_name, $max_size);
				}
				catch(Exception $e)
				{
					throw $e;
				}
			}
			else
			{
				# Fill the error variable.
				$this->setErrors('That file type is not accepted. Acceptable file types are: '.implode(", ", $allowed_ext).'.');
			}
		}
		return $uploaded;
	} #==== End -- uploadFile

	/**
	 * uploadDoc
	 *
	 * Uploads a user's Document.
	 *
	 * @param	$source_path			The path to the folder where we will keep the document.
	 * @param	$allowed_ext			The acceptable file types.
	 * @param	$new_name				The name to be given to the uploaded document.
	 * @param	$max_size				The maximum allowed size of the uploaded document.
	 * @return	bool
	 * @access	public
	 */
	public function uploadDoc($source_path, $allowed_ext, $new_name=NULL, $max_size=7340032)
	{
		if($this->checkErrors()===FALSE)
		{
			# Get File class.
			require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
			# Create new $file object.
			$file=new FileHandler();

			# Create variables for file related data members.
			$u_name=$this->getName();
			$u_temp_name=$this->getTempName();
			$u_type=$this->getType();
			$u_size=$this->getSize();

			if(empty($new_name))
			{
				$new_name=$u_name;
			}
			else
			{
				# Get the documents extension.
				$ext=$file->getFileExtension($u_name);
				# Add the proper extension to the passed new name.
				$new_name=$new_name.'.'.$ext;
			}

			# Set the maximum length of a filename.
			$file->setMaxLengthFilename(255);
			# Check the filename for unacceptable characters.
			$name_check=$file->checkFileName($new_name);
			if($name_check!==TRUE)
			{
				# Fill the error variable.
				$this->setErrors($name_check);
			}

			# Check if a file by that name already exists in our directory.
			$file_duplicate=$file->checkFileDuplicate($source_path, $new_name);
			if($file_duplicate!==FALSE)
			{
				# Fill the error variable.
				$this->setErrors($file_duplicate);
			}

			# Check if file submitted is smaller than or equal to $max_size (default is 7MB or 7340032 bytes.)
			if($u_size>$max_size)
			{
				# Fill the error variable.
				$this->setErrors('The file must be smaller than '.(($max_size/1024)/1024).'MB. Please reduce the size of the file and try again.');
			}

			# Check if the file is of an acceptable type.
			if($file->checkFileType($u_name, $allowed_ext)!==TRUE)
			{
				# Fill the error variable.
				$this->setErrors('That file type is not accepted. Acceptable file types are: '.implode(", ", $allowed_ext).'.');
			}

			# Any errors so far?
			if($this->checkErrors()===FALSE)
			{
				# Copy the file over to it's destination directory (If anything goes wrong we'll remove it.)
				if(move_uploaded_file($u_temp_name, $source_path.$new_name)===FALSE)
				{
					# Fill the error variable.
					$this->setErrors('There was an error uploading your file "'.$u_name.'". If this error persists, please contact the Webmaster at: <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>');
				}
				if($this->checkErrors()===FALSE)
				{
					$this->setName($new_name);
					return TRUE;
				}
			}
		}
		return FALSE;
	} #==== End -- uploadDoc

	/**
	 * uploadImage
	 *
	 * Uploads and resizes an image.
	 *
	 * @param	$source_path				The path to the folder where we will keep the original file.
	 * @param	$target_path				The complete path to where the copy will be stored.
	 * @param	$new_name					The name to be given to the uploaded image.
	 * @param	$max_size					The maximum allowed size of the uploaded file.
	 * @param	$resize_target				TRUE to resize the uploaded image, FALSE to leave it alone.
	 * @param	$max_width					The maximum width of the resized image.
	 * @param	$max_height					The maximum width of the resized image.
	 * @param	$quality					The quality of the resized image.
	 * @param	$resize_source				TRUE to resize the original image, FALSE to leave it alone.
	 * @param	$max_source_width			The maximum width of the resized original image.
	 * @param	$max_source_height			The maximum width of the resized original image.
	 * @param	$source_quality				The quality of the resized original image.
	 * @param	$proportional				TRUE if this image should proportional size, FALSE if you want the image $max_width x $max_height.
	 * @return	bool
	 * @access	public
	 */
	public function uploadImage($source_path, $target_path, $new_name=NULL, $max_size=7340032, $resize_target=TRUE, $max_width=120, $max_height=90, $quality=75, $resize_source=FALSE, $max_source_width=800, $max_source_height=800, $source_quality=100, $proportional=TRUE)
	{
		# If there was an uploaded file, we process it.
		if($this->checkErrors()===FALSE)
		{
			# Get the image class.
			require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
			# Instantiate an FileHandler object.
			$file_handler=new FileHandler();

			# Create variables for file related data members.
			$img_name=$this->getName();
			$img_temp_name=$this->getTempName();
			$img_type=$this->getType();
			$img_size=$this->getSize();

			# What file types will we accept?
			$allowed_types=$file_handler->getGDSupportedImageTypes();

			# Check if a new name for the image was passed.
			if(empty($new_name))
			{
				# Use the images current name.
				$new_name=$img_name;
			}
			else
			{
				# Get the documents extension.
				$ext=$file_handler->getFileExtension($img_name);
				# Make the extension conform.
				switch($ext)
				{
					case 'jpeg':
							$ext='jpg';
							break;
					case 'tiff':
							$ext='tif';
							break;
				}
				# Add the proper extension to the passed new name.
				$new_name=$new_name.'.'.$ext;
			}

			# Set the path to the original file.
			$source=$source_path.$new_name;
			# Set the path to the copy.
			$target=$target_path.$new_name;

			# Set the maximum length of a filename.
			$file_handler->setMaxLengthFilename(255);
			# Check the filename for unacceptable characters.
			$name_check=$file_handler->checkFileName($new_name);
			if($name_check!==TRUE)
			{
				# Fill the error variable.
				$this->setErrors($name_check);
			}

			# Check if a file by that name already exists in our directory.
			$file_duplicate=$file_handler->checkFileDuplicate($source_path, $new_name);
			if($file_duplicate!==FALSE)
			{
				# Fill the error variable.
				$this->setErrors($file_duplicate);
			}

			# Check if a file by that name already exists in our directory.
			if($file_handler->checkFileDuplicate($source_path, $new_name)===TRUE)
			{
				# Fill the error variable.
				$this->setErrors('Stopped');
			}

			# Check if file submitted is smaller than or equal to $max_size (default is 7MB or 7340032 bytes.)
			if($img_size>$max_size)
			{
				# Fill the error variable.
				$this->setErrors('The file must be smaller than '.(($max_size/1024)/1024).'MB. Please reduce the size of the file and try again.');
			}

			# Check if the image is an acceptable type.
			if($file_handler->checkFileType($img_name, $allowed_types)!==TRUE)
			{
				# Fill the error variable.
				$this->setErrors('That image type is not accepted. Acceptable file types are: '.implode(", ", $allowed_types).'.');
			}

			if($this->checkErrors()===FALSE)
			{
				# Copy the image over to it's destination directory (If anything goes wrong we'll remove it.)
				if(move_uploaded_file($img_temp_name, $source_path.$new_name)===FALSE)
				{
					# Fill the error variable.
					$this->setErrors('There was an error uploading your image. If this error persists, please contact the Webmaster at: <a href="mailto:'.ADMIN_EMAIL.'">'.ADMIN_EMAIL.'</a>');
				}
				if($this->checkErrors()===FALSE)
				{
					# Check if the target file should be resized.
					if($resize_target===TRUE)
					{
						if($proportional===TRUE)
						{
							# Resize the image and save the new image to the target folder.
							$resize_image=$file_handler->reduceImage($source_path.$new_name, $target_path.$new_name, $max_width, $max_height, $quality);
						}
						else
						{
							# Resize the image and save the new image to the target folder.
							$resize_image=$file_handler->reduceImage($source_path.$new_name, $target_path.$new_name, $max_width, $max_height, $quality, FALSE);
						}
						if($resize_image!==TRUE)
						{
							# Fill the error variable.
							$this->setErrors($resize_image);
							# Remove the original file.
							$this->deleteFile($source_path.$new_name);
							return FALSE;
						}
					}
					# Check if the source image should be resized.
					if($resize_source===TRUE)
					{
						if($proportional===TRUE)
						{
							# Resize the image and save the new image to the target folder.
							$resize_image=$file_handler->reduceImage($source_path.$new_name, BASE_PATH.'tmp'.DS.'delete'.$new_name, $max_source_width, $max_source_height, $source_quality);
						}
						else
						{
							# Resize the image and save the new image to the target folder.
							$resize_image=$file_handler->reduceImage($source_path.$new_name, BASE_PATH.'tmp'.DS.'delete'.$new_name, $max_source_width, $max_source_height, $source_quality, FALSE);
						}
						if($resize_image!==TRUE)
						{
							# Fill the error variable.
							$this->setErrors($resize_image);
							# Remove the original file.
							$this->deleteFile($source_path.$new_name);
							# Remove the copy.
							$this->deleteFile($target_path.$new_name);
							return FALSE;
						}
						else
						{
							# Remove the original file.
							$this->deleteFile($source_path.$new_name);
							# Copy the original image to it's destination directory.
							if(rename(BASE_PATH.'tmp'.DS.'delete'.$new_name, $source_path.$new_name)===FALSE)
							{
								# Remove the original file.
								$this->deleteFile(BASE_PATH.'tmp'.DS.'delete'.$new_name);
								# Remove the copy.
								$this->deleteFile($target_path.$new_name);
								# Create a message and send them to the "starting" point.
								throw new Exception('There was an error moving the resized image, '.$new_name.', to it\'s destination directory from the temp folder.', E_RECOVERABLE_ERROR);
							}
						}
					}
					$this->setName($new_name);
					return TRUE;
				}
			}
		}
		return FALSE;
	} #==== End -- uploadImage

	/**
	 * deleteFile
	 *
	 * Removes the passed file from the system. A wrapper method for the deleteFile method of the FileHandler class.
	 *
	 * @param	string $source				The complete path to the file. (ie: /home/user/bodega/file.jpg)
	 * @param	boolean $multi_file			TRUE if $source has a wildcard to delete multiple files (ie: /home/user/bodega/file-*.jpg).
	 * @return	boolean						Returns TRUE on success. Returns FALSE if the passed source is not a fvalid file. Throws an exception on failure.
	 * @access	public
	 */
	public function deleteFile($source, $multi_file=FALSE)
	{
		try
		{
			# Get the File class.
			require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
			# Instantiate a new File object.
			$file=new FileHandler();
			# Delete the file.
			$deleted=$file->deleteFile($source, $multi_file);

			return $deleted;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteFile

	# check for errors
	public function checkErrors()
	{
		if(count($this->getErrors())>0)
		{
			return TRUE;
		}
		return FALSE;
	} #==== End -- checkErrors

	/*** End public methods ***/

} # End Upload class.