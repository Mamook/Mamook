<?php /* framework/application/modules/User/WordPressUser.php */

# Get the parent User class.
require_once Utility::locateFile(MODULES.'User'.DS.'User.php');

/**
 * WordPressUser
 *
 * The WordPressUser Class is used to access and manipulate WordPress User info outside of a WordPress installation.
 *
 */
class WordPressUser extends User
{
	protected $wp_id=NULL;

	/**
	 * setWP_ID
	 *
	 * Sets the data member $wp_id.
	 *
	 * @param    $wp_id                    The User's WorpdPress ID number.
	 * @throws Exception
	 */
	public function setWP_ID($wp_id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the value is empty.
		if(!empty($wp_id))
		{
			# Clean it up.
			$wp_id=trim($wp_id);
			# Make sure the WordPress ID is an integer.
			if($validator->isInt($wp_id)===TRUE)
			{
				# Explicitly make it an integer.
				$wp_id=(int)$wp_id;
			}
			else
			{
				throw new Exception('The WordPress ID passed was not a number!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set it to NULL.
			$wp_id=NULL;
		}
		# Set the data member.
		$this->wp_id=$wp_id;
	}

	/**
	 * Returns the data member $wp_id. Throws an error on failure.
	 *
	 * @return null
	 * @throws Exception
	 */
	public function getWP_ID()
	{
		if(!empty($this->wp_id))
		{
			return $this->wp_id;
		}
		else
		{
			throw new Exception('The User\'s WordPress ID was not set!', E_RECOVERABLE_ERROR);
		}
	}

	/**
	 * deleteWP_User
	 *
	 * Delete's a WordPress user via WordPRess ID. Throws an error on failure.
	 *
	 * @param $wp_id                    The User's WordPress ID.
	 * @throws Exception
	 */
	public function deleteWP_User($wp_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			$where='';
			# Check if the passed $user_id is an integer.
			if($validator->isInt($wp_id)===TRUE)
			{
				# Set the passed WordPress ID to the data member, effectively cleaning it up.
				$this->setWP_ID($wp_id);
				# Reset the $wp_id variable from the data member.
				$wp_id=$this->getWP_ID();
				# Create where statement.
				$where=' = '.$db->quote($wp_id).' LIMIT 1';
			}
			# An array of users was passed into the method.
			elseif(is_array($wp_id))
			{
				# Create where statement.
				$where=' IN ('.implode(', ', $wp_id).')';
			}

			# NOTE: Can we use the Wordpress function?
			/*
			$wpdb=$db;
			# Get the WordPress user administration API.
			require_once Utility::locateFile(WP_INSTALL_PATH.'wp-admin'.DS.'includes'.DS.'user.php');
			# Delete user.
			wp_delete_user($wp_id);
			*/

			# NOTE: Delete User (Does not work with Multisite installations)
			# Delete the User from the `usermeta` table.
			$db->query('DELETE FROM `'.WP_DBPREFIX.'usermeta` WHERE `user_id`'.$where);
			# Delete the User from the WordPress `users` table.
			$db->query('DELETE FROM `'.WP_DBPREFIX.'users` WHERE `ID`'.$where);
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

	# NOTE: Add $reassign feature.
	/**
	 * deleteWP_User
	 *
	 * Delete's a WordPress user via WordPRess ID. Throws an error on failure.
	 *
	 * @param $wp_id                       The User's WordPress ID.
	 * @param $reassign                    A User's WordPress ID to reasign this User's posts to. -> DOESN'T WORK YET!!!
	 */
	/*
	public function deleteWP_User($wp_id, $reassign=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Set the passed WordPress ID to the data member, effectively cleaning it up.
			$this->setWP_ID($wp_id);
			# Reset the $wp_id variable from the data member.
			$wp_id=$this->getWP_ID();

			# Check if the passed reassign variable is empty.
			if(empty($reassign))
			{
				# Get the WordPressSubContent class.
				require_once Utility::locateFile(MODULES.'Content'.DS.'WordPressSubContent.php');
				# Instantiate a new WordPressSubContent object.
				$wp_subcontent=new WordPressSubContent();
				# Retrieve all the id's of all posts by this User.
				$wp_subcontent->getWPPosts(NULL, $fields='ID', 'post_date', 'DESC', ' AND `post_author` = '.$db->quote($wp_id), FALSE);
				# Get the retrieved records from the data member and set it to an array variable.
				$wp_posts=$wp_subcontent->getAllWPPosts();
				# Check if there were records returned.
				if($wp_posts!==NULL)
				{
					# Loop through the posts.
					foreach($wp_posts as $post)
					{
#--->					# Delete the post. (THIS DOES NOTHING RIGHT NOW!!!! MUST BE IMPLEMENTED!!!!)
						//$wp_subcontent->deleteWP_Post($post->ID);
					}
				}

				# NOTE: Clean links
				# Retrieve the id's of all links owned by this User.
				$link_ids=$db->get_results('SELECT `link_id` FROM `'.WP_DBPREFIX.'links` WHERE `link_owner` = '.$db->quote($wp_id));
				# Check if there were records returned.
				if($link_ids!==NULL)
				{
					# Loop through the link id's.
					foreach($link_ids as $link_id)
					{
#--->					# Delete the link. (THIS DOES NOTHING RIGHT NOW!!!! MUST BE IMPLEMENTED!!!!)
						//$this->deleteWP_Link($link_id);
					}
				}
			}
			else
			{
				# Explicitly make the reassign id an integer.
				$reassign=(int)$reassign;
				# Reassign the posts.
				$db->quick_update(WP_DBPREFIX.'posts', array('post_author'=>$reassign), array('post_author'=>$wp_id));
				# Reassign the links.
				$db->quick_update(WP_DBPREFIX.'links', array('link_owner'=>$reassign), array('link_owner'=>$wp_id));
			}

			# NOTE: Delete User (Does not work with Multisite installations)
			# Delete the User from the `usermeta` table.
			$db->query('DELETE FROM `'.WP_DBPREFIX.'usermeta` WHERE `user_id` = '.$db->quote($wp_id));
			# Delete the User from the WordPress `users` table.
			$db->query('DELETE FROM `'.WP_DBPREFIX.'users` WHERE `ID` = '.$db->quote($wp_id));
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
	*/

	/**
	 * getWP_Nickname
	 *
	 * Retrieves the User's WordPress nickname via WordPRess ID. Throws an error on failure.
	 *
	 * @param    $user_WP_id                The User's WordPress ID.
	 * @return null
	 * @throws ezDB_Error
	 */
	public function getWP_Nickname($user_WP_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		if(!empty($user_WP_id))
		{
			try
			{
				$user=$db->get_row("SELECT `meta_value` FROM `".WP_DBPREFIX."usermeta` WHERE `user_id` = ".$db->quote($db->escape($user_WP_id))." AND `meta_key` = ".$db->quote($db->escape('nickname'))." LIMIT 1");
				if($user!==NULL)
				{
					$this->setNickname($user->meta_value);

					return $this->getNickname();
				}
			}
			catch(ezDB_Error $ez)
			{
				throw new ezDB_Error($ez->error, $ez->errno);
			}
		}

		return NULL;
	}

	/**
	 * getWP_Password
	 *
	 * Retrieves the User's WordPress password via WordPRess ID. Throws an error on failure.
	 *
	 * @param    $user_WP_id                The User's WordPress ID.
	 * @return
	 * @throws ezDB_Error
	 */
	public function getWP_Password($user_WP_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			$user=$db->get_row("SELECT `user_pass` FROM `".WP_DBPREFIX."users` WHERE `ID` = ".$db->quote($db->escape($user_WP_id))." LIMIT 1");
		}
		catch(ezDB_Error $ez)
		{
			throw new ezDB_Error($ez->error, $ez->errno);
		}

		return $user->user_pass;
	}

	/**
	 * getWP_UserID
	 *
	 * Retrieves the User's WordPress ID via username. Throws an error on failure.
	 *
	 * @param    $username                The User's login.
	 * @return null
	 * @throws Exception
	 * @throws ezDB_Error
	 */
	public function getWP_UserID($username=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		if(empty($username))
		{
			try
			{
				$query="SELECT `ID` FROM `".WP_DBPREFIX."users` WHERE `user_login` = ".$db->quote($db->escape($this->findUsername()))." LIMIT 1";
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
		else
		{
			$query='SELECT `ID` FROM `'.WP_DBPREFIX.'users` WHERE `user_login` = '.$db->quote($db->escape($username)).' LIMIT 1';
		}
		try
		{
			$user=$db->get_row($query);
			if($user)
			{
				return $user->ID;
			}
			else
			{
				return NULL;
			}
		}
		catch(ezDB_Error $ez)
		{
			throw new ezDB_Error($ez->error, $ez->errno);
		}
	}

	/**
	 * updateWP_DisplayName
	 *
	 * Updates the User's WordPress display name. Throws an error on failure.
	 *
	 * @param    $id                        The User's WordPress ID.
	 * @param    $display_name              The new display name.
	 * @return
	 * @throws ezDB_Error
	 */
	public function updateWP_DisplayName($id, $display_name)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			$update_displayname=$db->query('UPDATE `'.WP_DBPREFIX.'users` SET `display_name` = '.$db->quote($db->escape($display_name)).' WHERE `ID` = '.$db->quote($id));

			return $update_displayname;
		}
		catch(ezDB_Error $ez)
		{
			throw new ezDB_Error($ez->error, $ez->errno);
		}
	}

	/**
	 * updateWP_Nickname
	 *
	 * Updates the User's WordPress nickname. Throws an error on failure.
	 *
	 * @param    $id                      The User's WordPress ID.
	 * @param    $nickname                The new nickname.
	 * @return
	 * @throws ezDB_Error
	 * @access    public
	 */
	public function updateWP_Nickname($id, $nickname)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			$update_nickname=$db->query('UPDATE `'.WP_DBPREFIX.'usermeta` SET `meta_value` = '.$db->quote($db->escape($nickname)).' WHERE `meta_key` = '.$db->quote($db->escape('nickname')).' AND `user_id` = '.$db->quote($id));

			return $update_nickname;
		}
		catch(ezDB_Error $ez)
		{
			throw new ezDB_Error($ez->error, $ez->errno);
		}
	}

	/**
	 * updateWP_Password
	 *
	 * Updates the User's WordPress password. Throws an error on failure.
	 *
	 * @param    $username                The User's WordPress username.
	 * @param    $password                The new password.
	 * @throws Exception
	 * @throws ezDB_Error
	 */
	public function updateWP_Password($username, $password)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			$update_wp_password=$db->query('UPDATE `'.WP_DBPREFIX.'users` SET `user_pass` = '.$db->quote($db->escape($password)).' WHERE `user_login` = '.$db->quote($db->escape($username)).' LIMIT 1');
		}
		catch(ezDB_Error $ez)
		{
			throw new ezDB_Error($ez->error, $ez->errno);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * updateWP_UserAccessLevel
	 *
	 * Updates the User's WordPress access level. Throws an error on failure.
	 *
	 * @param    $username                 The username to update.
	 * @param    $new_level                The new access  level.
	 * @throws ezDB_Error
	 */
	public function updateWP_UserAccessLevel($username, $new_level)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Get the WordPress User ID.
		$id=$this->getWP_UserID($username);

		switch($new_level)
		{
			case 90:
				$user_level=NULL;
				$capabilities='a:1:{s:10:"subscriber";b:1;}';
				break;
			case 92:
				$user_level=2;
				$capabilities='a:1:{s:6:"author";b:1;}';
				break;
			default:
				return;
		}
		try
		{
			# Check if this user already has a value for user_level.
			$current_user_level=$db->query('SELECT `user_id` FROM `'.WP_DBPREFIX.'usermeta` WHERE `meta_key` = '.$db->quote(WP_DBPREFIX.'user_level').' AND `user_id` = '.$id);
			# Check if the row was returned.
			if(!empty($current_user_level))
			{
				# Check if there is no user_level.
				if(empty($user_level))
				{
					# Delete the row.
					$db->query('DELETE FROM `'.WP_DBPREFIX.'usermeta` WHERE `meta_key` = '.$db->quote(WP_DBPREFIX.'user_level').' AND `user_id` = '.$id);
				}
				else
				{
					# Update the user's user_level.
					$db->query('UPDATE `'.WP_DBPREFIX.'usermeta` SET `meta_value` = '.$user_level.' WHERE `meta_key` = '.$db->quote(WP_DBPREFIX.'user_level').' AND `user_id` = '.$id);
				}
			}
			elseif(!empty($user_level))
			{
				# Insert a new row containing the user_level value.
				$db->query('INSERT INTO `'.WP_DBPREFIX.'usermeta` (`user_id`, `meta_key`, `meta_value`) VALUES ('.$id.', '.$db->quote(WP_DBPREFIX.'user_level').', '.$user_level.')');
			}
			# Update the user's capabilities.
			$db->query('UPDATE `'.WP_DBPREFIX.'usermeta` SET `meta_value` = '.$db->quote($db->escape($capabilities)).' WHERE `meta_key` = '.$db->quote(WP_DBPREFIX.'capabilities').' AND `user_id` = '.$id);
		}
		catch(ezDB_Error $ez)
		{
			throw new ezDB_Error($ez->error, $ez->errno);
		}
	}

	/**
	 * updateWP_Username
	 *
	 * Updates the User's WordPress username. Throws an error on failure.
	 *
	 * @param    $id                        The User's WordPress ID.
	 * @param    $username                  The new username.
	 * @throws ezDB_Error
	 */
	public function updateWP_Username($id, $username)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			$db->query('UPDATE `'.WP_DBPREFIX.'users` SET `user_login` = '.$db->quote($db->escape($username)).' WHERE `ID` = '.$db->quote($id));
		}
		catch(ezDB_Error $ez)
		{
			throw new ezDB_Error($ez->error, $ez->errno);
		}
	}
} # End WordPressUser class.