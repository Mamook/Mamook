<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Session
 *
 * The Session class is used to access and manipulate Sessions and data
 * stored in them.
 */
class Session
{
	/*** data members ***/

	private static $session;
	private $sessname='';

	/*** End data members ***/



	/*** magic methods ***/

	/**
	* Constructor
	*
	* Safely calls session_start(). Also enables sessions to span sub domains. It names the session (which is necessary for session_set_cookie_params() to work. If calling this class before setting.php, $sessname (the session name) AND $cookiepath (the path for cookies) MUST be defined.
	*
	* @param	string
	* @access	public
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
				if(LOGIN_LIFE_SHORT != '')
				{
					$life=LOGIN_LIFE_SHORT;
				}
			}
			# Set the max life of the session in seconds
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
					if(COOKIE_PATH != '')
					{ # If the cookie path has been defined in settings.php, we'll use that path.
						$cookiepath=COOKIE_PATH;
					}
				}
			}
			session_set_cookie_params($life, $cookiepath, '.'.DOMAIN_NAME, $secure);

			/* Read the current save path for the session files and append our own directory to this path. Note: In order to make that platform independent, we need to check for the file-seperator first. Now we check if the directory already has been created, if not, create one. Then we set the new path for the session files. */
			# get the session save path
			$save_path=session_save_path();
			# Find out if our custom_session folder exists. If not, let's make it.
			if(!is_dir(BASE_PATH.'custom_sessions'.DS.'.'))
			{
				mkdir(BASE_PATH.'custom_sessions', 0755);
			}
			# Is our custom_sessions folder the session save path? If not, let's make it so.
			if($save_path !== BASE_PATH.'custom_sessions')
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
					if(SESSIONS_NAME != '')
					{ # If the session name has been defined in settings.php, we'll give the session that name.
						$sessname=SESSIONS_NAME;
					}
				}
			}
			$this->setSessname($sessname);
			# Name the session
			session_name($this->getSessname());

			# session must be started before anything
			session_start();
			# set the s_set session so we can tell if session_start has been called already
			$_SESSION['s_set']=1;
		}
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	* setSessname
	*
	* Sets the data member $sessname. Returns FALSE on failure.
	*
	* @param	$name (Must be numeric.)
	* @access	public
	*/
	public function setSessname($sessname)
	{
		# Clean it up...
		$sessname=trim($sessname);
		if (!empty($sessname))
		{
			$this->sessname=$sessname;
		}
		else { return FALSE; }
	} #==== End -- setSessname

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	* getSessname
	*
	* Returns the data member $sessname. Returns FALSE on failure.
	*
	* @access	public
	*/
	public function getSessname()
	{
		if (isset($this->sessname) && !empty($this->sessname))
		{
			return $this->sessname;
		}
		else { return FALSE; }
	} #==== End -- getSessname

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	* checkCookies
	*
	* Checks to see if cookies are enabled. Returns TRUE if they are, FALSE if they aren't.
	*
	* @param		$js (Tell Javascript to check for cookies. Only works in conjunction with Document::addJSErrorBox(). Default is FALSE.)
	* @access		public
	*/
	public function checkCookies($js=FALSE)
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
	} #==== End -- checkCookies

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance($sessname=NULL, $cookiepath=NULL, $secure=FALSE, $sesh_id=NULL)
	{
		if(!self::$session)
		{
			self::$session=new Session($sessname, $cookiepath, $secure, $sesh_id);
		}
		return self::$session;
	} #==== End -- getInstance

	/**
	* setPostLogin
	*
	* Sets the _post_login Session to the current page.
	*
	* @access	public
	*/
	public function setPostLogin()
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
			(strpos(WebUtility::removeIndex(FULL_URL), WebUtility::removeSchemeName(WebUtility::removeIndex(PAYPAL_URL)))===FALSE))
		{
			# Set the page to a variable.
			$post_login=WebUtility::removeIndex(FULL_DOMAIN.HERE).GET_QUERY;

			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();

			# Set the Session.
			$_SESSION['_post_login']=$post_login;
		}
	} #==== End -- setPostLogin

	/**
	* loseSessionData
	*
	* Unsets session data from the passed index.
	*
	* @access	public
	*/
	public function loseSessionData($index)
	{
		# Remove session data.
		unset($_SESSION[$index]);
	} #==== End -- loseSessionData

	/*** End public methods ***/

} #==== End Session class.