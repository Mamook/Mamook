<?php /* framework/application/modules/Content/Publisher.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Publisher
 *
 * The Publisher Class is used access and maintain the `publishers` table in the database.
 */
class Publisher
{
	/*** data members ***/

	private $all_publishers=NULL;
	private $id=NULL;
	private $publisher=NULL;
	private $contributor=NULL;
	private $cont_id=NULL;
	private $recent_contributor=NULL;
	private $recent_cont_id=NULL;
	private $last_edit='0000-00-00';
	private $date='0000-00-00';
	private $info=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllPublishers
	 *
	 * Sets the data member $all_publishers.
	 *
	 * @param		$publishers (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllPublishers($publishers)
	{
		# Check if the passed value is empty.
		if(!empty($publishers))
		{
			# Explicitly make it an array.
			$publishers=(array)$publishers;
			# Set the data member.
			$this->all_publishers=$publishers;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_publishers=NULL;
		}
	} #==== End -- setAllPublishers

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param		$id
	 * @access	public
	 */
	public function setID($id)
	{
		# Check if the passed $id is NULL.
		if(!empty($id) && $id!=='add')
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();

			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->id=(int)$id;
			}
			else
			{
				throw new Exception('The passed publisher\'s id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setPublisher
	 *
	 * Sets the data member $publisher.
	 *
	 * @param		$publisher
	 * @access	public
	 */
	public function setPublisher($publisher)
	{
		# Check if the passed value is empty.
		if(!empty($publisher))
		{
			# Strip slashes and decode any html entities.
			$publisher=html_entity_decode(stripslashes($publisher), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$publisher=trim($publisher);
			# Set the data member.
			$this->publisher=$publisher;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->publisher=NULL;
		}
	} #==== End -- setPublisher

	/**
	 * setContributor
	 *
	 * Sets the data member $contributor.
	 *
	 * @param		$object
	 * @access	public
	 */
	public function setContributor($object)
	{
		# Set the data member.
		$this->contributor=$object;
	} #==== End -- setContributor

	/**
	 * setContID
	 *
	 * Sets the data member $cont_id.
	 *
	 * @param		Integer		$id 	(The contributors's id.)
	 * @access	public
	 */
	public function setContID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($id))
		{
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
				# Get the Contributor class.
				require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
				# Instantiate a new Contributor object.
				$cont=new Contributor();
				# Get the contributor name.
				$cont->getThisContributor($id, 'id', FALSE);
				# Set the Contributor object to the data member making it available outside the method.
				$this->setContributor($cont);
				# Set the data member.
				$this->cont_id=$id;
			}
			else
			{
				throw new Exception('The passed contributor id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_id=NULL;
		}
	} #==== End -- setContID

	/**
	 * setRecentContributor
	 *
	 * Sets the data member $recent_contributor.
	 *
	 * @param		$object
	 * @access	public
	 */
	public function setRecentContributor($object)
	{
		# Set the data member.
		$this->recent_contributor=$object;
	} #==== End -- setRecentContributor

	/**
	 * setRecentContID
	 *
	 * Sets the data member $recent_cont_id.
	 *
	 * @param		Integer		$id 	(The recent contributors's id.)
	 * @access	public
	 */
	public function setRecentContID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed value is empty.
		if(!empty($id))
		{
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
				# Get the Contributor class.
				require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
				# Instantiate a new Contributor object.
				$cont=new Contributor();
				# Get the contributor name.
				$cont->getThisContributor($id, 'id', FALSE);
				# Set the Contributor object to the data member making it available outside the method.
				$this->setRecentContributor($cont);
				# Set the data member.
				$this->recent_cont_id=$id;
			}
			else
			{
				throw new Exception('The passed recent contributor id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->recent_cont_id=NULL;
		}
	} #==== End -- setRecentContID

	/**
	 * setLastEdit
	 *
	 * Sets the data member $last_edit.
	 *
	 * @param		$date
	 * @access	public
	 */
	public function setLastEdit($date)
	{
		# Check if the passed value is empty.
		if(!empty($date) && ($date!=='0000-00-00') && ($date!=='1970-02-31'))
		{
			# Clean it up,
			$date=trim($date);
			# Set the data member.
			$this->last_edit=$date;
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->last_edit='0000-00-00';
		}
	} #==== End -- setLastEdit

	/**
	 * setDate
	 *
	 * Sets the data member $date.
	 *
	 * @param		$date
	 * @access	public
	 */
	public function setDate($date)
	{
		# Check if the passed value is empty.
		if(!empty($date) && ($date!=='0000-00-00') && ($date!=='1970-02-31'))
		{
			# Clean it up,
			$date=trim($date);
			# Set the data member.
			$this->date=$date;
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->date='0000-00-00';
		}
	} #==== End -- setDate

	/**
	 * setInfo
	 *
	 * Sets the data member $info.
	 *
	 * @param		$info
	 * @access	public
	 */
	public function setInfo($info)
	{
		# Bring the content instance into scope.
		$main_content=Content::getInstance();

		# Check if the passed value is empty.
		if(!empty($info))
		{
			# The the site name.
			$site_name=$main_content->getSiteName();
			# Strip slashes, convert new lines to <br /> and decode any html entities.
			$info=html_entity_decode(nl2br(stripslashes($info)), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$info=trim($info);
			# Replace any tokens with their correlating value.
			$info=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $info);
		}
		else
		{
			# Explicitly set the value to NULL.
			$info=NULL;
		}
		# Set the data member.
		$this->info=$info;
	} #==== End -- setInfo

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllPublishers
	 *
	 * Returns the data member $all_publishers.
	 *
	 * @access	public
	 */
	public function getAllPublishers()
	{
		return $this->all_publishers;
	} #==== End -- getAllPublishers

	/**
	 * getID
	 *
	 * Returns the data member $id.
	 *
	 * @access	public
	 */
	public function getID()
	{
		return $this->id;
	} #==== End -- getID

	/**
	 * getPublisher
	 *
	 * Returns the data member $publisher.
	 *
	 * @access	public
	 */
	public function getPublisher()
	{
		return $this->publisher;
	} #==== End -- getPublisher

	/**
	 * getContributor
	 *
	 * Returns the data member $contributor.
	 *
	 * @access	public
	 */
	public function getContributor()
	{
		return $this->contributor;
	} #==== End -- getContID

	/**
	 * getContID
	 *
	 * Returns the data member $cont_id.
	 *
	 * @access	public
	 */
	public function getContID()
	{
		return $this->cont_id;
	} #==== End -- getContID

	/**
	 * getRecentContributor
	 *
	 * Returns the data member $recent_contributor.
	 *
	 * @access	public
	 */
	public function getRecentContributor()
	{
		return $this->recent_contributor;
	} #==== End -- getRecentContID

	/**
	 * getRecentContID
	 *
	 * Returns the data member $recent_cont_id.
	 *
	 * @access	public
	 */
	public function getRecentContID()
	{
		return $this->recent_cont_id;
	} #==== End -- getRecentContID

	/**
	 * getLastEdit
	 *
	 * Returns the data member $last_edit.
	 *
	 * @access	public
	 */
	public function getLastEdit()
	{
		return $this->last_edit;
	} #==== End -- getLastEdit

	/**
	 * getDate
	 *
	 * Returns the data member $date.
	 *
	 * @access	public
	 */
	public function getDate()
	{
		return $this->date;
	} #==== End -- getDate

	/**
	 * getInfo
	 *
	 * Returns the data member $info.
	 *
	 * @access	public
	 */
	public function getInfo()
	{
		return $this->info;
	} #==== End -- getInfo

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllPublishers
	 *
	 * Returns the number of publishers in the database.
	 *
	 * @param		$limit 		(The limit of records to count.)
	 * @param		$and_sql 	(Extra AND statements in the query.)
	 * @access	public
	 */
	public function countAllPublishers($limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		try
		{
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'publishers`'.(($and_sql===NULL) ? '' : ' WHERE '.$and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllPublishers

	/**
	 * deletePublisher
	 *
	 * Removes a publisher from the `publishers` table.
	 *
	 * @param		$id				int (The id of the publisher in the `publishers` table.
	 * @access	public
	 */
	public function deletePublisher($id, $redirect=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Bring the Login object into scope.
			global $login;
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if a redirect URL was passed.
			if($redirect===NULL)
			{
				# Set the redirect to the default.
				$redirect=PROTOCAL.FULL_DOMAIN.Utility::removeIndex(HERE);
			}
			# Check if the passed redirect URL was FALSE.
			if($redirect===FALSE)
			{
				# Set the value to NULL (no redirect).
				$redirect===NULL;
			}
			# Check if the passed id was empty.
			if(!empty($id))
			{
				# Validate the passed id as an integer.
				if($validator->isInt($id)!==TRUE)
				{
					# Set a nice message to the session.
					$_SESSION['message']='That publisher was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);
				}
				else
				{
					# Set the post's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					# Check if the publisher is premium content or not.
					$this_publisher=$this->getThisPublisher($id);
					# Check if the publisher was found.
					if($this_publisher===TRUE)
					{
						try
						{
							# Delete the publisher from the `publishers` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'publishers` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Check if the publisher was deleted.
							if($deleted!==NULL)
							{
								# Set a nice message to display to the user.
								$_SESSION['message']='The publisher "'.$this->getPublisher().'" was successfully deleted.';

								# Redirect the user.
								$doc->redirect($redirect);
								return TRUE;
							}
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
						}
						catch(Exception $e)
						{
							throw $e;
						}
					}
					else
					{
						# Set a nice message to the session.
						$_SESSION['message']='The publisher was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
					}
				}
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deletePublisher

	/***
	 * displayPublisher
	 *
	 * Retrieves the publisher's information from the database sets to to a display array.
	 *
	 * @param		String 	$value 	(May be the publisher id or the publisher's name.)
	 * @param		Boolean $id 		(TRUE if the passed $value is an id, FALSE if it is a name.)
	 * @return	array
	 * @access	public
	 */
	public function displayPublisher($value, $id=FALSE)
	{
		# Get the publisher info and set it to the data members.
		$publisher=$this->getThisPublisher($value, $id);
		# Check if the publisher was returned.
		if($publisher===TRUE)
		{
			# Set the publisher's information to variables in the scope of this method.
			$publisher=$this->getPublisher();
			$cont_id=$this->getContID();
			$recent_cont_id=$this->getRecentContID();
			$date=$this->getDate();
			$last_edit=$this->getLastEdit();
			$info=$this->getInfo();

			# Create new array to hold all display content.
			$display_content=array('publisher'=>NULL, 'info'=>NULL, 'contributor'=>NULL, 'recent_contributor'=>NULL);

			# Set the publisher's name to a variable.
			$profile_name='<span class="profile-publisher">';
			$profile_name.='<span>'.$publisher.'</span>';
			$profile_name.='</span>';
			# Set the name XHTML to the display content array.
			$display_content['publisher']=$profile_name;
			# Check if the publisher's info is available.
			if(!empty($info))
			{
				# Set the publisher's info to a variable.
				$profile_info='<span class="profile-bio">';
				$profile_info.='<span class="label">Information:</span>';
				$profile_info.='<span>'.$info.'</span></span>';
				# Set the info XHTML to the display content array.
				$display_content['info']=$profile_info;
			}

			# Check if there is a contributor id.
			if($cont_id!==NULL)
			{
				# Set the Contributor object to a variable.
				$contributor=$this->getContributor();
				# Set the contributor's privacy setting to a variable.
				$cont_privacy=$contributor->getContPrivacy();
				# Convert the date to a timestamp.
				$date_timestamp=strtotime($date);
				# Check if the contributor should be hidden.
				if($cont_privacy!==NULL)
				{
					# Create a variable to hold the contributor display XHTML and open a list tag.
					$profile_contributor='<span class="post-author">';
					$profile_contributor.='<span class="label">Posted by</span> <a href="'.APPLICATION_URL.'profile/?contributor='.$cont_id.'" title="'.$contributor->getContName().'">'.$contributor->getContName().'</a>'.(($date!=='0000-00-00') ? ' on <span class="post-date"><span class="post-month">'.date("F", $date_timestamp).'</span> <span class="post-day">'.date("d", $date_timestamp).'</span>, <span class="post-year">'.date("Y", $date_timestamp).'</span></span>' : '');
					$profile_contributor.='</span>';
					# Check if the contributor should be displayed to all.
					if($cont_privacy==0)
					{
						# Set the contributor content to the array.
						$display_content['contributor']=$profile_contributor;
					}
					# Check if the contributor should be displayed to logged in users only.
					elseif($cont_privacy==1)
					{
						# Check if the User is logged in.
						if($login->isLoggedIn()===TRUE)
						{
							# Set the contributor content to the array.
							$display_content['contributor']=$profile_contributor;
						}
					}
				}
				# Check if there is a recent contributor id.
				if($recent_cont_id!==NULL)
				{
					# Set the Contributor object to a variable.
					$recent_contributor=$this->getRecentContributor();
					# Set the recent contributor's privacy setting to a variable.
					$recent_cont_privacy=$recent_contributor->getContPrivacy();
					# Convert the last edit date to a timestamp.
					$last_edit=strtotime($last_edit);
					# Check if the recent contributor should be hidden.
					if($recent_cont_privacy!==NULL)
					{
						# Create a variable to hold the recent contributor display XHTML and open a list tag.
						$profile_recent_contributor='<span class="post-editor">';
						$profile_recent_contributor.='<span class="label">Edited by</span> <a href="'.APPLICATION_URL.'profile/?contributor='.$recent_cont_id.'" title="'.$recent_contributor->getContName().'">'.$recent_contributor->getContName().'</a> on <span class="edit-date"><span class="edit-month">'.date("F", $last_edit).'</span> <span class="edit-day">'.date("d", $last_edit).'</span>, <span class="edit-year">'.date("Y", $last_edit).'</span></span>';
						$display_recent_cont.='</span>';
						# Check if the recent contributor should be displayed to all.
						if($recent_cont_privacy==0)
						{
							# Set the recent contributor content to the array.
							$display_content['recent_contributor']=$profile_recent_contributor;
						}
						# Check if the recent contributor should be displayed to logged in users only.
						elseif($recent_cont_privacy==1)
						{
							# Check if the User is logged in.
							if($login->isLoggedIn()===TRUE)
							{
								# Set the recent contributor content to the array.
								$display_content['recent_contributor']=$profile_recent_contributor;
							}
						}
					}
				}
			}
			return $display_content;
		}
		return NULL;
	} #==== End -- displayPublisher

	/**
	 * displayPublisherList
	 *
	 * Returns a list (table) of publishers.
	 *
	 * @param		$select 	(Whether or not the list should be a radio select display.)
	 * @access	public
	 */
	public function displayPublisherList($select=FALSE)
	{
		# Bring the Login object into scope.
		global $login;
		# Bring the content instance into scope.
		$main_content=Content::getInstance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# The the site name.
			$site_name=$main_content->getSiteName();
			# Count the publishers.
			$content_count=$this->countAllPublishers();
			# Check if there was returned content.
			if($content_count>0)
			{
				# Create an empty array to hold query parameters.
				$params_a=array();
				# Set the default sort order to a variable.
				$sort_dir='ASC';
				# Set the default "sort by" to a variable.
				$sort_by='name';
				# Set the default sort direction of publishers for the publisher sorting link to a variable.
				$name_dir='DESC';
				# Check if GET data for publisher has been passed and it is an integer.
				if(isset($_GET['publisher']) && $validator->isInt($_GET['publisher'])===TRUE)
				{
					# Set the query to the query parameters array.
					$params_a['publisher']='publisher='.$_GET['publisher'];
				}
				# Check if this should be a selectable list and that GET data for "select" has been passed.
				if($select===TRUE && isset($_GET['select']))
				{
					# Reset the $select variable to the value "select" indicating that this conditional is all TRUE.
					$select='select';
					# Get rid of any "publisher" GET query; it can't be passed with "select".
					unset($params_a['publisher']);
					# Set the query to the query parameters array.
					$params_a['select']='select';
				}
				# Check if GET data for "by_name" has been passed and it equals "ASC" or "DESC" and that GET data for "by_title" has not also been passed.
				if(isset($_GET['by_name']) && ($_GET['by_name']==='ASC' OR $_GET['by_name']==='DESC'))
				{
					# Set the query to the query parameters array.
					$params_a['by_name']='by_name='.$_GET['by_name'];
					# Check if the order is to be descending.
					if($_GET['by_name']==='DESC')
					{
						# Reset the default "sort by" to "DESC".
						$sort_dir='DESC';
						# Reset the sort direction of publishers for the publisher sorting link to "ASC".
						$name_dir='ASC';
					}
				}
				# Implode the query parameters array to a string sepparated by ampersands.
				$params=implode('&amp;', $params_a);
				# Get rid of the "by_name" and "by_title" indexes of the array.
				unset($params_a['by_name']);
				# Implode the query parameters array to a string sepparated by ampersands for the publisher and title sorting links.
				$query_params=implode('&amp;', $params_a);
				# Set the default value for displaying an edit button and a delete button to FALSE.
				$edit=FALSE;
				$delete=FALSE;

				# Check if the logged in User has access to edit a branch.
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

				# Set the newly created WHERE clause to a variable.
				$and_sql='';
				# Get the Publishers.
				$this->getPublishers($paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', $sort_by, $sort_dir, $and_sql);
				# Set the returned Publisher records to a variable.
				$all_publishers=$this->getAllPublishers();

				# Start a table for the publishers and set the markup to a variable.
				$table_header='<table class="'.(($select==='select') ? 'select': 'table').'-file">';
				# Set the table header for the publisher column to a variable.
				$general_header='<th><a href="'.ADMIN_URL.'ManageContent/publishers/?'.$query_params.((!empty($query_params)) ? '&amp;' : '').'by_name='.$name_dir.'" title="Order by publisher name">Publisher</a></th>';
				# Set the table header for the info column to a variable.
				$general_header.='<th>Info</th>';
				# Check if this is a select list.
				if($select==='select')
				{
					# Get the FormGenerator class.
					require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
					# Instantiate a new FormGenerator object.
					$fg=new FormGenerator('post', 'http'.($validator->isSSL()===TRUE ? 's' : '').'://'.FULL_URL, 'post', '_top', FALSE, 'publisher-list');
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
				# Loop through the all_publishers array.
				foreach($all_publishers as $row)
				{
					# Instantiate a new Publisher object.
					$publisher=New Publisher();
					# Set the relevant returned field values File data members.
					$publisher->setInfo($row->info);
					$publisher->setID($row->id);
					$publisher->setPublisher($row->name);
					/* Set the relevant Publisher data members to local variables. */
					# Replace any tokens with their correlating value.
					$publisher_info=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $publisher->getInfo());
					$publisher_id=$publisher->getID();
					# Replace any tokens with their correlating value.
					$publisher_name=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $publisher->getPublisher());
					# Create empty variables for the edit and delete buttons.
					$edit_content=NULL;
					$delete_content=NULL;
					# Set the publisher markup to the $general_data variable.
					$general_data='<td>'.(($select==='select') ? '<label for="publisher'.$publisher_id.'">' : '' ).'<a href="'.APPLICATION_URL.'profile/?publisher='.$publisher_id.'" title="'.$publisher_name.' on '.DOMAIN_NAME.'" target="_blank">'.$publisher_name.'</a>'.(($select==='select') ? '</label>' : '' ).'</td>';
					# Add the title markup to the $general_data variable.
					$general_data.='<td>'.((!empty($publisher_info)) ? ' <span class="entry">'.$publisher_info.'</span>' : '').'</td>';
					# Check if there should be an edit button displayed.
					if($edit===TRUE)
					{
						# Set the edit button to a variable.
						$edit_content='<a href="'.ADMIN_URL.'ManageContent/publishers/?publisher='.$publisher_id.'" class="button-edit" title="Edit '.$publisher_name.'">Edit</a>';
					}
					# Check f there should be a delete button displayed.
					if($delete===TRUE)
					{
						# Set the delete button to a variable.
						$delete_content='<a href="'.ADMIN_URL.'ManageContent/publishers/?publisher='.$publisher_id.'&amp;delete" class="button-delete" title="Delete '.$publisher_name.'">Delete</a>';
					}
					# Check if this is a select list.
					if($select==='select')
					{
						# Open a tr and td tag and add them to the form.
						$fg->addFormPart('<tr><td>');
						# Create the radio button for this publisher.
						$fg->addElement('radio', array('name'=>'publisher_info', 'value'=>$publisher_id.':'.$publisher_name, 'id'=>'publisher'.$publisher_id));
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
					$fg->addElement('submit', array('name'=>'publisher', 'value'=>'Select'), '', NULL, 'submit-publisher');
					# Close the fieldset.
					$fg->addFormPart('</fieldset>');
					# Set the form to a local variable.
					$display='<h4>Select a publisher below</h4>'.$fg->display();
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
				$display='<h3>There are no publishers to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayPublisherList

	/**
	 * getPublishers
	 *
	 * Retrieves records from the `publishers` table.
	 *
	 * @param		$limit (The LIMIT of the records.)
	 * @param		$fields (The name of the field(s) to be retrieved.)
	 * @param		$order (The name of the field to order the records by.)
	 * @param		$direction (The direction to order the records.)
	 * @param		$and_sql (Extra AND statements in the query.)
	 * @return	Boolean (TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getPublishers($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `publishers` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'publishers`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllPublishers($records);
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
	} #==== End -- getPublishers

	/**
	 * getThisPublisher
	 *
	 * Retrieves publisher info from the `publishers` table in the Database for the passed id or publisher name and sets it to the data member.
	 *
	 * @param		String	$value 	(The name or id of the publisher to retrieve.)
	 * @param		Boolean $id 		(TRUE if the passed $value is an id, FALSE if not.)
	 * @return	Boolean 				(TRUE if a record is returned, FALSE if not.)
	 * @access	public
	 */
	public function getThisPublisher($value, $id=TRUE)
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
				# Set the publisher id to the data member "cleaning" it.
				$this->setID($value);
				# Get the publisher id and reset it to the variable.
				$value=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='name';
				# Set the publisher name to the data member "cleaning" it.
				$this->setPublisher($value);
				# Get the publisher name and reset it to the variable.
				$value=$this->getPublisher();
			}
			# Get the publisher info from the Database.
			$publisher=$db->get_row('SELECT `id`, `name`, `date`, `info`, `contributor`, `recent_contributor`, `last_edit` FROM `'.DBPREFIX.'publishers` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($publisher!==NULL)
			{
				# Set the publisher's id to the data member.
				$this->setID($publisher->id);
				# Set the publisher's name to the data member.
				$this->setPublisher($publisher->name);
				# Set the contributor id to the data member.
				$this->setContID($publisher->contributor);
				# Set the recent contributor id to the data member.
				$this->setRecentContID($publisher->recent_contributor);
				# Set the date to the data member.
				$this->setDate($publisher->date);
				# Set the last edit date to the data member.
				$this->setLastEdit($publisher->last_edit);
				# Set the publisher's info to the data member.
				$this->setInfo($publisher->info);
				return TRUE;
			}
			# Return FALSE because the publisher wasn't in the table.
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
	} #==== End -- getThisPublisher

	/*** End public methods ***/

} # End Publisher class.