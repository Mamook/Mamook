<?php /* framework/application/modules/Session/Session.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

/**
 * Session
 *
 * The Session class is used to access and manipulate Sessions and data
 * stored in them.
 */
class Session
{
	/*** data members ***/

	private $message=FALSE;
	private static $session;
	private $sessname=FALSE;

	/*** End data members ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * Safely calls session_start().
	 * Also enables sessions to span sub domains. It names the session (which is necessary for session_set_cookie_params() to work).
	 * If calling this class before setting.php, $sessname (the session name) AND $cookiepath (the path for cookies) MUST be defined.
	 *
	 * @param string $sessname
	 * @param string $cookiepath
	 * @param boolean $secure
	 * @param string $sesh_id
	 */
	public function __construct($sessname=NULL, $cookiepath=NULL, $secure=FALSE, $sesh_id=NULL)
	{
		# Check if a session ID was passed.
		if($sesh_id!==NULL)
		{
			session_id($sesh_id);
		}
		# Is a session already started?
		if(!isset($_SESSION['s_set']))
		{
			# Set the default session life (60*60*4*1 = 14400 = 4 hrs {seconds*minutes*hours*days}).
			$life=14400;
			# Check if the session life was defined in settings.php.
			if(defined('LOGIN_LIFE_SHORT'))
			{
				# Check if the defined life is blank.
				if(LOGIN_LIFE_SHORT!='')
				{
					$life=LOGIN_LIFE_SHORT;
				}
			}
			# Set the max life of the session in seconds.
			ini_set('session.gc_maxlifetime', $life);

			# If we haven't been given a session name, we will give it one.
			if(empty($cookiepath))
			{
				# Set the default cookie path be the root of the site.
				$cookiepath=DS;
				# Check if the cookie path was defined in settings.php.
				if(defined('COOKIE_PATH'))
				{
					# Check if the defined path is blank.
					if(COOKIE_PATH!='')
					{
						# If the cookie path has been defined in settings.php, we'll use that path.
						$cookiepath=COOKIE_PATH;
					}
				}
			}
			session_set_cookie_params($life, $cookiepath, '.'.DOMAIN_NAME, $secure);

			/*
			 * Read the current save path for the session files and append our own directory to this path.
			 * Note: In order to make that platform independent, we need to check for the file-seperator first.
			 * Now we check if the directory already has been created, if not, create one.
			 * Then we set the new path for the session files.
			 */
			# Get the session save path.
			$save_path=session_save_path();
			# Find out if our custom_session folder exists. If not, let's make it.
			if(!is_dir(BASE_PATH.'custom_sessions'.DS.'.'))
			{
				mkdir(BASE_PATH.'custom_sessions', 0755);
			}
			# Is our custom_sessions folder the session save path? If not, let's make it so.
			if($save_path!==BASE_PATH.'custom_sessions')
			{
				session_save_path(BASE_PATH.'custom_sessions');
			}

			# If we haven't been given a session name, we will give it one.
			if(empty($sessname))
			{
				# Set the default session name.
				$sessname='PHPSESSID';
				# Check if the session name was defined in settings.php.
				if(defined('SESSIONS_NAME'))
				{
					# Check if the defined name is blank.
					if(SESSIONS_NAME!='')
					{
						# If the session name has been defined in settings.php, we'll give the session that name.
						$sessname=SESSIONS_NAME;
					}
				}
			}
			$this->setSessname($sessname);
			# Name the session.
			session_name($this->getSessname());

			# Session must be started before anything.
			session_start();
			# Set the s_set session so we can tell if session_start has been called already.
			$_SESSION['s_set']=1;
		}

		# Capture any messages set before this page was loaded.
		$this->captureMessage();
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setMessage
	 *
	 * Sets the data member $message. If an empty value is passed, the data member will
	 * be set with FALSE. Returns the set data member value.
	 *
	 * @param string $message
	 * @return bool
	 */
	private function setMessage($message)
	{
		# Clean it up...
		$message=trim($message);
		# Check if the passed value is now empty.
		if(empty($message))
		{
			# Explicitly set the data member to false.
			$message=FALSE;
		}
		# Set the data member.
		$this->message=$message;
		# Return the data member after it has gone through the get method.
		return $this->getMessage();
	}

	/**
	 * setSessname
	 *
	 * Sets the data member $sessname. If an empty value is passed, the data member will
	 * be set with FALSE. Returns the set data member value.
	 *
	 * @param $sessname
	 * @return bool
	 */
	public function setSessname($sessname)
	{
		# Clean it up...
		$sessname=trim($sessname);
		# Check if the passed value is now empty.
		if(empty($sessname))
		{
			# Explicitly set the data member to false.
			$sessname=FALSE;
		}
		# Set the data member.
		$this->sessname=$sessname;

		# Return the data member after it has gone through the get method.
		return $this->getSessname();
	}

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getMessage
	 *
	 * Returns the data member $message.
	 *
	 * @access    public
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * getSessname
	 *
	 * Returns the data member $sessname.
	 *
	 * @access    public
	 */
	public function getSessname()
	{
		return $this->sessname;
	}

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * checkCookies
	 *
	 * Checks to see if cookies are enabled. Returns TRUE if they are, FALSE if they aren't.
	 *
	 * @return bool
	 */
	public function checkCookies()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		global $error, $check_cookies;

		$error=$doc->getError();
		if($error===NULL)
		{
			$error='';
		}

		# Tell the Javascript to check for cookies.
		$check_cookies=TRUE;

		# Check if the session cookie is set.
		if(isset($_COOKIE[SESSIONS_NAME]))
		{
			return TRUE;
		}
		# If the session cookie is not set, the browser must not be accepting cookies.
		else
		{
			if(array_key_exists('HTTP_COOKIE', $_SERVER))
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @param null $sessname
	 * @param null $cookiepath
	 * @param bool $secure
	 * @param null $sesh_id
	 * @return Session
	 */
	public static function getInstance($sessname=NULL, $cookiepath=NULL, $secure=FALSE, $sesh_id=NULL)
	{
		if(!self::$session)
		{
			self::$session=new Session($sessname, $cookiepath, $secure, $sesh_id);
		}

		return self::$session;
	}

	/**
	 * keepSessionData
	 *
	 * Determines if the $_SESSION data is cleared or kept. This is used when doing a redirect.
	 * If FALSE, all $_SESSION data is cleared. If TRUE, the data is kept and the current message
	 * is set to the 'message' index of the $_SESSION array.
	 *
	 * @param boolean $keep_session_data        A Boolean indicating whether the $_SESSION data should be kept (TRUE) or not (FALSE).
	 * @return bool
	 */
	public function keepSessionData($keep_session_data=TRUE)
	{
		# Check if it is indicated that sessions should NOT be kept.
		if($keep_session_data!==TRUE)
		{
			# Loop through the SESSION.
			foreach($_SESSION as $index=>$value)
			{
				# Check if the current index is NOT "s_set". This is the index that keeps the current session active.
				if($index!=='s_set')
				{
					# Unset the SESSION data for the current index.
					$this->loseSessionData($index);
				}
			}
		}
		else
		{
			$current_message=$this->getMessage();
			if($current_message!==FALSE)
			{
				$_SESSION['message']=$current_message;
			}
		}

		return $keep_session_data;
	}

	/**
	 * loseAllSessionData
	 *
	 * Unsets ALL session data.
	 */
	public function loseAllSessionData()
	{
		# Remove session data.
		unset($_SESSION);
		# Destroy what's left.
		session_destroy();
	}

	/**
	 * loseSessionData
	 *
	 * Unsets session data from the passed index.
	 *
	 * @param $index
	 */
	public function loseSessionData($index)
	{
		# Remove session data.
		unset($_SESSION[$index]);
	}

	/**
	 * saveSessionFile
	 *
	 * End the current session and store session data.
	 */
	public function saveSessionFile()
	{
		# Write session data to file. Ends session.
		session_write_close();
		# Re-start session.
		session_start();
	}

	/**
	 * setPostLogin
	 *
	 * Sets the _post_login Session to the current page.
	 *
	 * @param string $post_login Used to force a post_login URL. Default is the value in the REDIRECT_AFTER_LOGIN constant
	 */
	public function setPostLogin($post_login=REDIRECT_AFTER_LOGIN)
	{
		if($post_login==REDIRECT_AFTER_LOGIN)
		{
			# Check if the user is viewing a page NOT to be set to the _post_login Session.
			if(
				(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeSchemeName(LOGOUT_PAGE))===FALSE) &&
				(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeSchemeName(LOGIN_PAGE.'register/'))===FALSE) &&
				(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeSchemeName(LOGIN_PAGE.'confirm.php'))===FALSE) &&
				(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeSchemeName(LOGIN_PAGE.'LostPassword/'))===FALSE) &&
				(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeSchemeName(LOGIN_PAGE.'ResendEmail/'))===FALSE) &&
				(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeSchemeName(WebUtility::removeIndex(LOGIN_PAGE)))===FALSE) &&
				(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeIndex(WebUtility::removeSchemeName(DOWNLOADS)))===FALSE) &&
				(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeSchemeName(WebUtility::removeIndex(PAYPAL_URL)))===FALSE)
			)
			{
				# Set the page to a variable.
				$post_login=WebUtility::removeIndex(PROTOCAL.FULL_DOMAIN.HERE).GET_QUERY;
			}
			elseif(isset($_SESSION['_post_login']) && !empty($_SESSION['_post_login']))
			{
				$post_login=$_SESSION['_post_login'];
			}
		}

		# Set the Session.
		$_SESSION['_post_login']=$post_login;
	}

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * captureMessage
	 *
	 * Captures any messages set the SESSION.
	 */
	private function captureMessage()
	{
		# Check if the "message" index is set to the SESSION and it isn't empty.
		if(isset($_SESSION['message']) && !empty($_SESSION['message']))
		{
			# Set the value to the data member.
			$this->setMessage($_SESSION['message']);
			# Clear the message
			unset($_SESSION['message']);
		}
	}

	/*** End private methods ***/
}