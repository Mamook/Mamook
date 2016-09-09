<?php /* framework/application/modules/Content/Comment.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

/**
 * Comment
 *
 * The Comment Class is used access and maintain the `comments` table in the database.
 *
 */
class Comment
{
	private $all_comments=NULL;
	private $id=NULL;
	private $date='0000-00-00 00:00:00';
	private $content=NULL;
	private $comment_parent=NULL;
	private $user_id=NULL;
	private $video_id=NULL;

	/**
	 * getAllComments
	 *
	 * Returns the data member $all_comments.
	 *
	 * @access    public
	 */
	public function getAllComments()
	{
		return $this->all_comments;
	}

	/**
	 * getID
	 *
	 * Returns the data member $id.
	 *
	 * @access    public
	 */
	public function getID()
	{
		return $this->id;
	}

	/**
	 * getDate
	 *
	 * Returns the data member $date.
	 *
	 * @access    public
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * getContent
	 *
	 * Returns the data member $content.
	 *
	 * @access    public
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * getCommentParent
	 *
	 * Returns the data member $comment_parent.
	 *
	 * @access    public
	 */
	public function getCommentParent()
	{
		return $this->comment_parent;
	}

	/**
	 * getUserID
	 *
	 * Returns the data member $user_id.
	 *
	 * @access    public
	 */
	public function getUserID()
	{
		return $this->user_id;
	}

	/**
	 * getVideoID
	 *
	 * Returns the data member $video.
	 *
	 * @access    public
	 */
	public function getVideoID()
	{
		return $this->video_id;
	}

	/**
	 * getComments
	 *
	 * Retrieves records from the `comments` table.
	 *
	 * @param int $limit        (The LIMIT of the records.)
	 * @param string $fields    (The name of the field(s) to be retrieved.)
	 * @param string $order     (The name of the field to order the records by.)
	 * @param string $direction (The direction to order the records.)
	 * @param string $where
	 * @return bool
	 * @throws Exception
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
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getThisComment
	 *
	 * Retrieves comment info from the `comments` table for the passed id or parent id and sets it to the data member.
	 *
	 * @param $id
	 * @return bool
	 * @throws Exception
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
			$comment=$db->get_row('SELECT `video_id`, `user_id`, `date`, `content`, `comment_parent` FROM `'.DBPREFIX.'comments` WHERE `id` = '.$db->quote($id).' LIMIT 1');
			# Check if a row was returned.
			if($comment!==NULL)
			{
				# Set the comment's post date to the data member.
				$this->setDate($comment->date);
				# Set the comment content to the data member.
				$this->setContent($comment->content);
				# Set the comment's parent id to the data member.
				$this->setCommentParent($comment->comment_parent);
				# Set the comment author's User ID to the data member.
				$this->setUserID($comment->user_id);
				# Set the comment's video id to the data member.
				$this->setVideoID($comment->video_id);

				return TRUE;
			}

			# Return FALSE because the branch wasn't in the table.
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
	}

	/**
	 * removeUser
	 *
	 * Remove's a User's from the comment data in the `comments` table.
	 *
	 * @param int /array $user_id        The comment author's User ID.
	 * @return bool
	 * @throws Exception
	 */
	public function removeUser($user_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			$where='';
			# Check if the passed $user_id is an integer.
			if($validator->isInt($user_id)===TRUE)
			{
				# Set the User ID to the data member, effectively cleaning it up.
				$this->setUserID($user_id);
				# Reset the variable from the data member.
				$user_id=$this->getUserID();
				# Create where statement.
				$where='= '.$db->quote($user_id).' LIMIT 1';
			}
			# An array of users was passed into the method.
			elseif(is_array($user_id))
			{
				# Create where statement.
				$where='IN ('.implode(', ', $user_id).')';
			}
			# Set the `user` value to NULL for this comment author's User ID in the `comments` table.
			$update_comments=$db->query('UPDATE `'.DBPREFIX.'comments` SET `user_id` = NULL WHERE `user_id` '.$where);
			# Check that there was a result.
			if($update_comments)
			{
				return TRUE;
			}

			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error removing User ID: '.$user_id.' from the comments\'s data: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * setAllComments
	 *
	 * Sets the data member $all_comments.
	 *
	 * @param $comments (May be an array or a string. The method makes it into an array regardless.)
	 * @access protected
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
	}

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 *
	 * @param        $id
	 * @throws Exception
	 * @access    protected
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
	}

	/**
	 * setDate
	 *
	 * Sets the data member $date.
	 *
	 * @param    $date
	 * @access    protected
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
	}

	/**
	 * setContent
	 *
	 * Sets the data member $content.
	 *
	 * @param string $content
	 * @access protected
	 */
	protected function setContent($content)
	{
		# Bring the content instance into scope.
		$main_content=Content::getInstance();

		# Check if the passed value is empty.
		if(!empty($content))
		{
			# Get the site name.
			$site_name=$main_content->getSiteName();
			# Strip slashes and decode any html entities.
			$content=html_entity_decode(stripslashes($content), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$content=trim($content);
			# Replace any tokens with their correlating value.
			$content=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $content);
			# Set the data member.
			$this->content=$content;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->content=NULL;
		}
	}

	/**
	 * setCommentParent
	 *
	 * Sets the data member $comment_parent.
	 *
	 * @param  $comment_parent
	 * @throws Exception
	 * @access protected
	 */
	protected function setCommentParent($comment_parent)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($comment_parent))
		{
			# Clean it up.
			$comment_parent=trim($comment_parent);
			# Check if the passed $parent is an integer.
			if($validator->isInt($comment_parent)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->comment_parent=(int)$comment_parent;
			}
			else
			{
				throw new Exception('The passed comment\'s parent id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->comment_parent=NULL;
		}
	}

	/**
	 * setUserID
	 *
	 * Sets the data member $user_id.
	 *
	 * @param $user_id
	 * @throws Exception
	 * @access protected
	 */
	protected function setUserID($user_id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($user_id))
		{
			# Clean it up.
			$user_id=trim($user_id);
			# Check if the passed $user_id is an integer.
			if($validator->isInt($user_id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->user_id=(int)$user_id;
			}
			else
			{
				throw new Exception('The passed comment author\'s User ID was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->user_id=NULL;
		}
	}

	/**
	 * setVideoID
	 *
	 * Sets the data member $video_id.
	 *
	 * @param $video_id
	 * @throws Exception
	 * @access protected
	 */
	protected function setVideoID($video_id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($video_id))
		{
			# Clean it up.
			$video_id=trim($video_id);
			# Check if the passed $video is an integer.
			if($validator->isInt($video_id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->video_id=(int)$video_id;
			}
			else
			{
				throw new Exception('The passed comment\'s video id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->video_id=NULL;
		}
	}
} # End Comment class.