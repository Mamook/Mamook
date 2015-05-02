<?php /* framework/application/modules/Content/Comment.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Comment
 *
 * The Comment Class is used access and maintain the `comments` table in the database.
 *
 */
class Comment
{
	/*** data members ***/

	private $all_comments=NULL;
	private $id=NULL;
	private $date='0000-00-00 00:00:00';
	private $content=NULL;
	private $parent=NULL;
	private $user=NULL;
	private $video=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * @access	public
	 */
	public function __construct()
	{
		return;
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAllComments
	 *
	 * Sets the data member $all_comments.
	 *
	 * @param		$comments (May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllComments($comments)
	{
		# Check if the passed value is empty.
		if(!empty($comments))
		{
			# Explicitly make it an array.
			$comments=(array)$comments;
			# Set the data member.
			$this->all_comments=$comments;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_comments=NULL;
		}
	} #==== End -- setAllComments

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param		$id
	 * @access	protected
	 */
	protected function setID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
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
				throw new Exception('The passed comment id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setID

	/**
	 * setDate
	 *
	 * Sets the data member $date.
	 *
	 * @param	$date
	 * @access	protected
	 */
	protected function setDate($date)
	{
		# Check if the passed value is empty.
		if(!empty($date))
		{
			# Clean it up.
			$date=trim($date);
			# Set the data member.
			$this->date=$date;
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->date='0000-00-00 00:00:00';
		}
	} #==== End -- setDate

	/**
	 * setContent
	 *
	 * Sets the data member $content.
	 *
	 * @param	string					$content
	 * @access	protected
	 */
	protected function setContent($content)
	{
		# Bring the content object into scope.
		global $main_content;

		# Check if the passed value is empty.
		if(!empty($content))
		{
			# Get the site name.
			$site_name=$main_content->getSiteName();
			# Strip slashes and decode any html entities.
			$content=html_entity_decode(stripslashes($content), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$content=trim($content);
			# Replace any domain or site name tokens with the current domain name and site name.
			$content=str_ireplace(array('%{domain_name}', '%{site_name}'), array(DOMAIN_NAME, $site_name), $content);
			# Set the data member.
			$this->content=$content;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->content=NULL;
		}
	} #==== End -- setContent

	/**
	 * setParent
	 *
	 * Sets the data member $parent.
	 *
	 * @param	$id
	 * @access	protected
	 */
	protected function setParent($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
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
				throw new Exception('The passed comment\'s parent id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setParent

	/**
	 * setUser
	 *
	 * Sets the data member $user.
	 *
	 * @param		$id
	 * @access	protected
	 */
	protected function setUser($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
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
				throw new Exception('The passed comment author\'s User ID was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setUser

	/**
	 * setVideo
	 *
	 * Sets the data member $video.
	 *
	 * @param		$id
	 * @access	protected
	 */
	protected function setVideo($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
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
				throw new Exception('The passed comment\'s video id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->id=NULL;
		}
	} #==== End -- setVideo

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllComments
	 *
	 * Returns the data member $all_comments.
	 *
	 * @access	public
	 */
	public function getAllComments()
	{
		return $this->all_comments;
	} #==== End -- getAllComments

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
	 * getContent
	 *
	 * Returns the data member $content.
	 *
	 * @access	public
	 */
	public function getContent()
	{
		return $this->content;
	} #==== End -- getContent

	/**
	 * getParent
	 *
	 * Returns the data member $parent.
	 *
	 * @access	public
	 */
	public function getParent()
	{
		return $this->parent;
	} #==== End -- getParent

	/**
	 * getUser
	 *
	 * Returns the data member $user.
	 *
	 * @access	public
	 */
	public function getUser()
	{
		return $this->user;
	} #==== End -- getUser

	/**
	 * getVideo
	 *
	 * Returns the data member $video.
	 *
	 * @access	public
	 */
	public function getVideo()
	{
		return $this->video;
	} #==== End -- getVideo

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * getComments
	 *
	 * Retrieves records from the `comments` table.
	 *
	 * @param		$limit 			(The LIMIT of the records.)
	 * @param		$fields 		(The name of the field(s) to be retrieved.)
	 * @param		$order 			(The name of the field to order the records by.)
	 * @param		$direction 	(The direction to order the records.)
	 * @param		$and_sql 		(Extra AND statements in the query.)
	 * @return	Boolean 		(TRUE if records are returned, FALSE if not.)
	 * @access	public
	 */
	public function getComments($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `comments` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'comments`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllComments($records);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: ' . $ez->message . '<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getComments

	/**
	 * getThisComment
	 *
	 * Retrieves comment info from the `comments` table for the passed id or parent id and sets it to the data member.
	 *
	 * @param		String		$value 	(The id of the comment to retrieve.)
	 * @return	Boolean 					(TRUE if a record is returned, FALSE if not.)
	 * @access	public
	 */
	public function getThisComment($id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Set the comment id to the data member "cleaning" it.
			$this->setID($id);
			# Get the comment id and reset it to the variable.
			$id=$this->getID();

			# Get the comment info from the Database.
			$comment=$db->get_row('SELECT `video`, `user`, `date`, `content`, `parent` FROM `'.DBPREFIX.'comments` WHERE `id` = '.$db->quote($id).' LIMIT 1');
			# Check if a row was returned.
			if($comment!==NULL)
			{
				# Set the comment's post date to the data member.
				$this->setDate($comment->date);
				# Set the comment content to the data member.
				$this->setContent($comment->content);
				# Set the comment's parent id to the data member.
				$this->setParent($comment->parent);
				# Set the comment author's User ID to the data member.
				$this->setUser($comment->user);
				# Set the comment's video id to the data member.
				$this->setVideo($comment->video);
				return TRUE;
			}
			# Return FALSE because the branch wasn't in the table.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			# Throw an exception because there was a Database connection error.
			throw new Exception('Error occured: ' . $ez->message . '<br />Code: ' . $ez->code . '<br />Last query: '. $ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- getThisComment

	/**
	 * removeUser
	 *
	 * Remove's a User's from the comment data in the `comments` table.
	 *
	 * @param	string $user_id			The comment author's User ID.
	 * @access	public
	 * @return	Boolean
	 */
	public function removeUser($user_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Set the User ID to the data member, effectively cleaning it up.
			$this->setUser($user_id);
			# Reset the variable from the data member.
			$user_id=$this->getUser();
			# Set the `user` value to NULL for this comment author's User ID in the `comments` table.
			$update_comments=$db->query('UPDATE `'.DBPREFIX.'comments` SET `user` = NULL WHERE `user` = '.$db->quote($user_id));
			# Check that there was a result.
			if($update_comments>=1) { return TRUE; }
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error removing User ID: '.$user_id.' from the comments\'s data: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		return FALSE;
	} #==== End -- removeUser

	/*** End public methods ***/

} # End Comment class.