<?php

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the Media class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Media.php');

/**
 * Image
 *
 * The Image Class is used access and maintain the `images` table in the database.
 *
 */
class Image extends Media
{
	/*** data members ***/

	private static $image_obj;
	private $all_images=array();
	private $image=NULL;
	private $height=NULL;
	private $hide;
	private $width=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllImages
	 *
	 * Sets the data member $images.
	 *
	 * @param	$images					May be an array or a string. The method makes it into an array regardless.
	 * @access	protected
	 */
	protected function setAllImages($images)
	{
		# Check if the passed value is empty.
		if(!empty($images))
		{
			# Explicitly make it an array.
			$images=(array)$images;
			# Set the data member.
			$this->all_images=$images;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->all_images=array();
		}
	} #==== End -- setAllImages

	/**
	 * setImage
	 *
	 * Sets the data member $image.
	 *
	 * @param	$image
	 * @access	public
	 */
	public function setImage($image)
	{
		# Check if the passed value is empty.
		if(!empty($image))
		{
			# Clean it up.
			$image=trim($image);
			# Set the data member.
			$this->image=$image;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->image=NULL;
		}
	} #==== End -- setImage

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 * Extends setID in Media.
	 *
	 * @param	int $id					A numeric ID representing the image.
	 * @param	string $media_type		The type of media that the ID represents. Default is "image".
	 * @access	public
	 */
	public function setID($id, $media_type='image')
	{
		try
		{
			# Check if the passed $id is empty.
			if($id=='add' OR $id=='select')
			{
				# Explicitly set the data member to NULL.
				$id=NULL;
			}
			parent::setID($id, $media_type);
		}
		catch(Exception $error)
		{
			throw $error;
		}
	} #==== End -- setID

	/**
	 * setHeight
	 *
	 * Sets the data member $height.
	 *
	 * @param	$height
	 * @access	public
	 */
	public function setHeight($height)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($height))
		{
			# Clean it up.
			$height=trim($height);
			# Check if the passed $height is an integer.
			if($validator->isInt($height)===TRUE)
			{
				# Explicitly make it an integer.
				$height=(int)$height;
			}
			else
			{
				throw new Exception('The passed height was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$height=NULL;
		}
		# Set the data member.
		$this->height=$height;
	} #==== End -- setHeight

	/**
	 * setHide
	 *
	 * Sets the data member $hide.
	 *
	 * @param	$hide
	 * @access	public
	 */
	public function setHide($hide)
	{
		# Check if it is NULL.
		if($hide!==NULL)
		{
			# Explicitly set the data member to 0.
			$this->hide=0;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->hide=NULL;
		}
	} #==== End -- setHide

	/**
	 * setWidth
	 *
	 * Sets the data member $width.
	 *
	 * @param	$width
	 * @access	public
	 */
	public function setWidth($width)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($width))
		{
			# Clean it up.
			$width=trim($width);
			# Check if the passed $width is an integer.
			if($validator->isInt($width)===TRUE)
			{
				# Explicitly make it an integer.
				$width=(int)$width;
			}
			else
			{
				throw new Exception('The passed width was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$width=NULL;
		}
		# Set the data member.
		$this->width=$width;
	} #==== End -- setWidth

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllImages
	 *
	 * Returns the data member $all_images.
	 *
	 * @access	public
	 */
	public function getAllImages()
	{
		return $this->all_images;
	} #==== End -- getAllImages

	/**
	 * getImage
	 *
	 * Returns the data member $image.
	 *
	 * @access	public
	 */
	public function getImage()
	{
		return $this->image;
	} #==== End -- getImage

	/**
	 * getHeight
	 *
	 * Returns the data member $height.
	 *
	 * @access	public
	 */
	public function getHeight()
	{
		return $this->height;
	} #==== End -- getHeight

	/**
	 * getHide
	 *
	 * Returns the data member $hide.
	 *
	 * @access	public
	 */
	public function getHide()
	{
		return $this->hide;
	} #==== End -- getHide

	/**
	 * getWidth
	 *
	 * Returns the data member $width.
	 *
	 * @access	public
	 */
	public function getWidth()
	{
		return $this->width;
	} #==== End -- getWidth

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllImages
	 *
	 * Returns the number of images in the database.
	 *
	 * @param	$limit 					The limit of records to count.
	 * @param	$where					WHERE statements in the query.
	 * @access	public
	 */
	public function countAllImages($limit=NULL, $where=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'images`'.(($where===NULL) ? '' : ' WHERE '.$where).(($limit===NULL) ? '' : ' LIMIT '.$limit));
			return $count;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- countAllImages

	/**
	 * deleteImage
	 *
	 * Removes an image from the `images` table and the actual image from the system.
	 *
	 * @param	int $id					The id of the image in the `images` table.
	 * @access	public
	 */
	public function deleteImage($id, $redirect=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed id was empty.
			if(!empty($id))
			{
				# Check if a redirect URL was passed.
				if($redirect===NULL)
				{
					# Set the redirect to the default.
					$redirect=PROTOCAL.FULL_DOMAIN.HERE;
				}
				# Check if the passed redirect URL was FALSE.
				if($redirect===FALSE)
				{
					# Set the value to NULL (no redirect).
					$redirect===NULL;
				}
				# Validate the passed id as an integer.
				if($validator->isInt($id)===TRUE)
				{
					# Set the post's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					# Check if the image is premium content or not.
					$this_image=$this->getThisImage($id);
					# Check if the image was found.
					if($this_image!==TRUE)
					{
						# Set a nice message to the session.
						$_SESSION['message']='The image was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
						# If there is no redirect, return FALSE.
						return FALSE;
					}
					# Set the image's categories data member to a local variable.
					$image_cats=$this->getCategories();
					# Set the image's name data member to a local variable.
					$image_name=$this->getImage();
					# Get the FileHandler class.
					require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
					# Instantiate a new FileHandler object.
					$file_handler=new FileHandler();
					# Delete the image.
					if(($file_handler->deleteFile(IMAGES_PATH.$image_name)===TRUE) && ($file_handler->deleteFile(IMAGES_PATH.'original'.DS.$image_name)===TRUE))
					{
						try
						{
							# Delete the image from the `images` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'images` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Set a nice message to display to the user.
							$_SESSION['message']='The image '.$image_name.' was successfully deleted.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
							# If there is no redirect, return TRUE.
							return TRUE;
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('Error occured: '.$ez->error.', but the image itself was deleted.<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
						}
						catch(Exception $e)
						{
							throw $e;
						}
					}
					else
					{
						# Set a message to display to the user.
						$_SESSION['message']='That was not a valid image for deletion.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
					}
				}
				else
				{
					# Set a nice message to the session.
					$_SESSION['message']='That image was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);
				}
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteImage

	/**
	 * displayImage
	 *
	 * Displays the image associated with the SubContent record.
	 *
	 * @param		$return					TRUE to return the string, FALSE to echo it.
	 * @param		$image_name			The name of the image to display.
	 * @param		$image_title		The title of the image to display.
	 * @param		$image_link
	 * @return	String
	 * @access	public
	 */
	public function displayImage($return=FALSE, $image_name=NULL, $image_title=NULL, $image_link=FW_POPUP_HANDLE)
	{
		try
		{
			# Check if an image name was passed.
			if(!empty($image_name))
			{
				# Set the image name to the data member.
				$this->setImage($image_name);
			}
			# Check if an image title was passed.
			if(!empty($image_title))
			{
				# Set the image title to the data member.
				$this->setTitle($image_title);
			}
			# Try to get the image name from the data member and reset the variable.
			$image_name=$this->getImage();
			# Create an empty variable for the XHTML.
			$display_image='';
			if(!empty($image_name))
			{
				# Check if there should be a link for the image.
				if(!empty($image_link))
				{
					# Check if the image link is the fwPupUp handle.
					if($image_link==FW_POPUP_HANDLE)
					{
						$image_link='<a href="'.IMAGES.'original/'.$image_name.'" rel="'.FW_POPUP_HANDLE.'" title="'.$this->getTitle().'" class="image-link" target="_blank">%{insert_content}</a>';
					}
					else
					{
						$image_link=$image_link.'%{insert_content}</a>';
					}
				}
				else
				{
					# Explicitly set the image link to an empty variable.
					$image_link='%{insert_content}';
				}
				# Set the image markup to the display variable.
				$display_image.=str_replace('%{insert_content}', '<img src="'.IMAGES.$image_name.'" class="image" alt="'.$this->getTitle().'"/>', $image_link);
			}
			if($return===FALSE)
			{
				echo $display_image;
			}
			else { return $display_image; }
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayImage

	/**
	 * displayImageList
	 *
	 * Returns a selectable list of images.
	 *
	 * @param	$select
	 * @access	public
	 */
	public function displayImageList($select=FALSE)
	{
		# Bring the Login object into scope.
		global $login;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Count the returned images.
			$content_count=$this->countAllImages();
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='image';
				# Set the default sort direction of images for the image sorting link to a variable.
				$image_dir='DESC';
				# Set the default sort direction of titles for the title sorting link to a variable.
				$title_dir='DESC';
				# Check if GET data for image has been passed and it is an integer.
				if(isset($_GET['image']) && $validator->isInt($_GET['image'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['image']='image='.$_GET['image'];
				}
				# Check if this should be a selectable list and that GET data for "select" has been passed.
				if($select===TRUE && isset($_GET['select']))
				{
					# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
					$select='select';
					# Get rid of any "image" GET query; it can't be passed with "select".
					unset($params_a['image']);
					# Set the query to the query parameters array.
					$params_a['select']='select';
				}
				# Check if GET data for "by_image" has been passed and it equals "ASC" or "DESC" and that GET data for "by_title" has not also been passed.
				if(isset($_GET['by_image']) && ($_GET['by_image']==='ASC' OR $_GET['by_image']==='DESC') && !isset($_GET['by_title']))
				{
					# Set the query to the query parameters array.
					$params_a['by_image']='by_image='.$_GET['by_image'];
					# Check if the order is to be descending.
					if($_GET['by_image']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of images for the image sorting link to "ASC".
						$image_dir='ASC';
					}
				}
				# Check if GET data for "by_title" has been passed and it equals "ASC" or "DESC" and that GET data for "by_image" has not also been passed.
				if(isset($_GET['by_title']) && ($_GET['by_title']==='ASC' OR $_GET['by_title']==='DESC') && !isset($_GET['by_image']))
				{
					# Set the query to the query parameters array.
					$params_a['by_title']='by_title='.$_GET['by_title'];
					# Reset the default "sort by" to "title".
					$sort_by='title';
					# Check if the order is to be descending.
					if($_GET['by_title']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of titles for the title sorting link to "ASC".
						$title_dir='ASC';
					}
				}
				# Implode the query parameters array to a string sepparated by ampersands.
				$params=implode('&amp;', $params_a);
				# Get rid of the "by_image" and "by_title" indexes of the array.
				unset($params_a['by_image']);
				unset($params_a['by_title']);
				# Implode the query parameters array to a string sepparated by ampersands for the image and title sorting links.
				$query_params=implode('&amp;', $params_a);
				# Set the default value for displaying an edit button and a delete button to FALSE.
				$edit=FALSE;
				$delete=FALSE;

				# Check if the logged in User has access to editing a branch.
				if($login->checkAccess(ALL_BRANCH_USERS)===TRUE && $select!=='select')
				{
					# Set the default value for displaying an edit button and a delete button to TRUE.
					$edit=TRUE;
					$delete=TRUE;
				}
				# Get the PageNavigator Class.
				require_once Utility::locateFile(MODULES.'PageNavigator'.DS.'PageNavigator.php');
				# Create a new PageNavigator object.
				$paginator=new PageNavigator(25, 4, CURRENT_PAGE, 'page', $content_count, $params);
				$paginator->setStrFirst('First Page');
				$paginator->setStrLast('Last Page');
				$paginator->setStrNext('Next Page');
				$paginator->setStrPrevious('Previous Page');

				# Get the Images.
				$this->getImages($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir);
				# Set the returned Image records to a variable.
				$all_images=$this->getAllImages();

				# Start a table for the images and set the markup to a variable.
				$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-image">';
				# Set the table header for the image column to a variable.
				$general_header='<th><a href="'.ADMIN_URL.'ManageMedia/images/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_image='.$image_dir.'" title="Order by image name">View</a></th>';
				# Add the table header for the title column to the $general_header variable.
				$general_header.='<th><a href="'.ADMIN_URL.'ManageMedia/images/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_title='.$title_dir.'" title="Order by title">Title</a></th>';
				# Check if this is a select list.
				if($select==='select')
				{
					# Get the FormGenerator class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
					# Instantiate a new FormGenerator object.
					$fg=new FormGenerator('post', PROTOCAL.FULL_URL, 'post', '_top', FALSE, 'image-list');
					# Create the hidden submit check input.
					$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
					# Open the fieldset tag.
					$fg->addFormPart('<fieldset>');
					# Add a table header for the Select column and concatenate the table header.
					$table_header.='<th>Select</th>'.$general_header;
					# Add the table header to the form.
					$fg->addFormPart($table_header);
				}
				else
				{
					# Concatenate the table header.
					$table_header.=$general_header;
					# Check if edit and delete buttons should be displayed.
					if($delete===TRUE OR $edit===TRUE)
					{
						# Concatenate the options header to the table header.
						$table_header.='<th>Options</th>';
					}
				}
				# Creat an empty variable for the table body.
				$table_body='';
				# Loop through the all_images array.
				foreach($all_images as $row)
				{
					# Instantiate a new Image object.
					$image_obj=new Image();
					# Set the relevant returned field values File data members.
					$image_obj->setDescription($row->description);
					$image_obj->setID($row->id);
					$image_obj->setImage($row->image);
					$image_obj->setTitle($row->title);
					# Get the relevant Image data members to local variables.
					$image_desc=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $image_obj->getDescription());
					$image_id=$image_obj->getID();
					$image_name=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $image_obj->getImage());
					$image_title=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $image_obj->getTitle());
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					$delete_content=NULL;
					# Set the image markup to the $general_data variable.
					$general_data='<td><a href="'.IMAGES.'original/'.$image_name.'" title="'.$image_title.'" rel="'.FW_POPUP_HANDLE.'"><img src="'.IMAGES.$image_name.'" alt="'.$image_name.'" /></a></td>';
					# Add the title markup to the $general_data variable.
					$general_data.='<td>'.(($select==='select') ? '<label for="image'.$image_id.'">' : '' ).'"'.$image_title.'"'.((!empty($image_desc)) ? ' <span class="entry">'.$image_desc.'</span>' : '').(($select==='select') ? '</label>' : '' ).'</td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="'.ADMIN_URL.'ManageMedia/images/?image='.$image_id.'" class="button-edit" title="Edit this">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageMedia/images/?image='.$image_id.'&amp;delete" class="button-delete" title="Delete This">Delete</a>';
					}
					# Check if this is a select list.
					if($select==='select')
					{
						# Open a tr and td tag and add them to the form.
						$fg->addFormPart('<tr><td>');
						# Create the radio button for this image.
						$fg->addElement('radio', array('name'=>'image_info', 'value'=>$image_id.':'.$image_name, 'id'=>'image'.$image_id));
						# Reset the $table_body variable with the general data closing the radio button's td tag and closing the tr.
						$table_body='</td>'.$general_data.'</tr>';
						# Add the table body to the form.
						$fg->addFormPart($table_body);
					}
					else
					{
						# Concatenate the general data to the $table_body variable first opening a new tr.
						$table_body.='<tr>'.$general_data;
						# Check if there should be edit or Delete buttons displayed.
						if($delete===TRUE OR $edit===TRUE)
						{
							# Concatenate the button(s) to the $table_body variable wrapped in td tags.
							$table_body.='<td>'.$edit_content.$delete_content.'</td>';
						}
						# Close the current tr.
						$table_body.='</tr>';
					}
				}
				# Check if this is a select list.
				if($select==='select')
				{
					# Close the table.
					$fg->addFormPart('</table>');
					# Add the submit button.
					$fg->addElement('submit', array('name'=>'image', 'value'=>'Select Image'), '', NULL, 'submit-image');
					$fg->addElement('submit', array('name'=>'image', 'value'=>'Go Back'), '', NULL, 'submit-back');
					# Close the fieldset.
					$fg->addFormPart('</fieldset>');
					# Set the form to a local variable.
					$display='<h4>Select an image below</h4>'.$fg->display();
				}
				else
				{
					# Concatenate the table header and body and close the table setting it all to a local variable.
					$display=$table_header.$table_body.'</table>';
				}
				# Add the pagenavigator to the display variable.
				$display.=$paginator->getNavigator();
			}
			else
			{
				$display='<h3>There are no images to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayImageList

	/**
	 * getImages
	 *
	 * Retrieves records from the `images` table.
	 *
	 * @param	$limit					The LIMIT of the records.
	 * @param	$fields					The name of the field(s) to be retrieved.
	 * @param	$order					The name of the field to order the records by.
	 * @param	$direction				The direction to order the records.
	 * @param	$and_sql				Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getImages($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `images` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'images`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllImages($records);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getImages

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$image_obj)
		{
			self::$image_obj=new Image();
		}
		return self::$image_obj;
	} #==== End -- getInstance

	/**
	 * getThisImage
	 *
	 * Retrieves image info from the `images` table in the Database for the passed id or image name and sets it to the data member.
	 *
	 * @param	string $value			The name or id of the image to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean					TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisImage($value, $id=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed $value is an id.
			if($id===TRUE)
			{
				# Set the field to search for $value.
				$field='id';
				# Set the image id to the data member "cleaning" it.
				$this->setID($value);
				# Get the image id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				if($id===FALSE)
				{
					# Set the field to search for $value.
					$field='image';
					# Set the image name to the data member "cleaning" it.
					$this->setImage($value);
					# Get the image name and reset it to the variable.
					$value=$this->getImage();
				}
				else
				{
					# Set the field to search for $value.
					$field=$id;
					# Create the name of the set method.
					$set_method='set'.ucfirst($id);
					# Create the name of the set method.
					$get_method='get'.ucfirst($id);
					# Check if the setter exists in this class.
					if((method_exists($this, $set_method)===TRUE) && (method_exists($this, $get_method)===TRUE))
					{
						# Set the image name to the data member "cleaning" it.
						$this->$set_method($value);
						# Set the image name to the data member "cleaning" it.
						$value=$this->$get_method();
					}
					else
					{
						throw new Exception($id.' is not a valid field in the image table.', E_RECOVERABLE_ERROR);
					}
				}
			}
			# Get the image info from the Database.
			$image=$db->get_row('SELECT `id`, `image`, `title`, `description`, `location`, `category`, `contributor`, `recent_contributor`, `last_edit`, `hide` FROM `'.DBPREFIX.'images` WHERE `'.$field.'` = '.$db->quote($value).' LIMIT 1');
			# Check if a row was returned.
			if($image!==NULL)
			{
				# Set the image id to the data member.
				$this->setID($image->id);
				# Set the image name to the data member.
				$this->setImage($image->image);
				# Pass the file category id(s) to the setCategory method, thus setting the data member with the category name(s).
				$this->setCategories($image->category);
				# Set the contributor id to the data member.
				$this->setContID($image->contributor);

				# Set the image description to a variable.
				$description=$image->description;
				# Replace any tokens with their correlating value.
				$description=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $description);
				# Strip slashes and decode any html entities.
				$description=((empty($description)) ? '' : html_entity_decode(stripslashes($description), ENT_COMPAT, 'UTF-8'));
				# Convert new lines to <br />.
				$description=nl2br($description);
				# Set the image description to the data member.
				$this->setDescription($description);

				# Set the whether the image should be hidden or not to the data member.
				$this->setHide($image->hide);
				# Set the image location to the data member.
				$this->setLocation($image->location);
				# Set the image title to the variable.
				$title=$image->title;
				# Decode any html entities.
				$title=html_entity_decode($title);
				# Re-encode any html entities including quotes as UTF-8.
				$title=htmlentities($title, ENT_QUOTES, 'UTF-8', FALSE);
				# Set the image title to the data member.
				$this->setTitle($title);
				return TRUE;
			}
			# Return FALSE because the image wasn't in the table.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			# Throw an exception because there was a Database connection error.
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- getThisImage

	/*** End public methods ***/

} # End Image class.