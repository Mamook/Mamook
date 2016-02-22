<?php /* framework/application/modules/Validator/Validator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Validator
 *
 * The Validator Class is used to validate stuff.
 *
 */
class Validator
{
	/*** data members ***/

	protected $error=array();
	private static $validator;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setError
	 *
	 * Sets the data member $error.
	 *
	 * @param	$error
	 * @param	$key
	 * @access	public
	 */
	public function setError($error, $key=NULL)
	{
		# Make sure the id is an integer and set the variable.
		$this->error[(($key===NULL) ? '' : $key)]=trim($error);
	} #==== End -- setError

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getError
	 *
	 * Returns the data member $error. Throws an error on failure.
	 *
	 * @access	public
	 */
	public function getError($key=NULL)
	{
		if(isset($this->error) && !empty($this->error))
		{
			if($key===NULL)
			{
				return $this->error;
			}
			else
			{
				return $this->error[$key];
			}
		}
		else
		{
			throw new Exception('The error was not set!');
		}
	} #==== End -- getError

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$validator)
		{
			self::$validator=new Validator();
		}
		return self::$validator;
	} #==== End -- getInstance

	/**
	 * isEmpty
	 *
	 * Determines if the passed variable or array is not set and is empty.
	 * Returns TRUE is the passed variable or array is empty.
	 *
	 * @param	$var (The variable to check.)
	 * @param	$min (The minimum length of a passed string.)
	 * @param	$max (The maximum length of a passed string.)
	 * @access	public
	 */
	public function isEmpty($var, $min=1, $max=256)
	{
		$empty=FALSE;
		if(is_array($var))
		{
			if(empty($var))
			{
				$empty=TRUE;
			}
		}
		elseif(trim($var)=='' || strlen($var)<$min || strlen($var)>$max)
		{
			$empty=TRUE;
		}
		return $empty;
	} #==== End -- isEmpty

	# validate integer string
	public function isInt($string)
	{
		$valid=TRUE;
		if(!isset($string) || !is_numeric($string) || (intval($string)!=$string))
		{
			$valid=FALSE;
		}
		return $valid;
	} #==== End -- isInt

	# validate numeric
	public function isNumber($number)
	{
		$valid=TRUE;
		if(!isset($number) || !is_numeric($number))
		{
			$valid=FALSE;
		}
		return $valid;
	} #==== End -- isNumber

	# validate if value is within a range
	public function isInRange($value, $min=1, $max=99)
	{
		$valid=TRUE;
		if(!isset($value) || ($value<$min) || ($value>$max))
		{
			$valid=FALSE;
		}
		return $valid;
	} #==== End -- isInRange

	# validate alphabetic string
	public function isAlphabetic($string)
	{
		$valid=TRUE;
		if(!isset($string) || !preg_match('/^[a-z]+$/i', $string))
		{
			$valid=FALSE;
		}
		return $valid;
	} #==== End -- isAlphabetic

	# validate alphanumeric string
	public function isAlphanum($string)
	{
		$valid=FALSE;
		if(!empty($string)&&(preg_match('/[a-z]+/i', $string)>0)&&(preg_match('/\d+/i', $string)>0))
		{
			$valid=TRUE;
		}
		return $valid;
	} #==== End -- isAlphanum

	/**
	 * validEmail
	 *
	 * Determines if the passed email address is valid.
	 * Returns FALSE if the email address is not valid.
	 *
	 * @param	$email (The email to check.)
	 * @param	$ping (Should it ping the domain name to see if it is an active domain?)
	 * @access	public
	 */
	public function validEmail($email, $ping=FALSE)
	{
		# Set valid to FALSE by default.
		$valid=FALSE;
		# Clean up the passed email address.
		$email=trim($email);

		# Check if the passed email address is NOT empty. It should be at least 5 characters and no more than 256 characters.
		$empty_email=$this->isEmpty($email, 5, 256);
		if($empty_email===FALSE)
		{
			# Get the EmailAddressValidator class and instantiate a new instance.
			require_once Utility::locateFile(MODULES.'Validator'.DS.'EmailAddressValidator.php');
			$validate=new EmailAddressValidator();
			$valid=$validate->check_email_address($email, $ping);
		}
		return $valid;
	} #==== End -- validEmail

	/**
	 * validURL
	 *
	 * Determines if the passed param is a valid URL.
	 * Returns FALSE if the URL address is not valid.
	 *
	 * @param	$url (The URL to check.)
	 * @access	public
	 */
	public function validURL($url)
	{
		$url=trim($url);
		if(empty($url))
		{
			$valid=FALSE;
		}
		else
		{
			/*** Make sure it's a properly formatted URL. ***/
			# Scheme
			$urlregex='^(https?|s?ftp\:\/\/)|(mailto\:)';
			# User and password (optional)
			$urlregex.='([\w\d\+!\*\(\)\,\;\?&=\$_\.\-]+(\:[\w\d\+!\*\(\)\,\;\?&=\$_\.\-]+)?@)?';
			/*** Hostname or IP ***/
			# http://x = allowed (ex. http://localhost, http://routerlogin)
			//$urlregex.='[a-z\d\+\$_\-]+(\.[a-z\d\+\$_\-]+)*';
			# http://x.x = minimum
			$urlregex.="[a-z\d\+\$_\-]+(\.[a-z\d+\$_\-]+)+";
			# http://x.xx(x) = minimum
			//$urlregex .= "([a-z\d\+\$_\-]+\.)*[a-z\d\+\$_\-]{2,3}";
			/*** USE ONLY ONE OF THE ABOVE ***/
			# Port (optional)
			$urlregex.='(\:[\d]{2,5})?';
			# Path (optional)
			//$urlregex.='(\/([a-z\d\+\$_\-]\.\?)+)*\/?';
			# GET Query (optional)
			$urlregex.='(\?[\w\+&\$_\.\-][\w\d\;\:@\/&%=\+\$_\.\-]*)?';
			# Anchor (optional)
			$urlregex.='(\#[\w\.\-][\w\d\+\$_\.\-]*)?$';
			if(preg_match('/'.$urlregex.'/i', $url))
			{
				$valid=TRUE;
			}
			else
			{
				# The link wasn't properly formatted.
				$valid=FALSE;
			}
		}
		return $valid;
	} #==== End -- validURL

	/**
	 * isSSL
	 *
	 * Determines if the connection is secure.
	 * Returns FALSE if not.
	 *
	 * @access	public
	 */
	public function isSSL()
	{
		# For Apache and IIS.
		if(
				(
					# Check for a staging(test) site where SSL is not available, but we want it to act like it is.
					(DOMAIN_NAME===STAGING_DOMAIN) &&
					(
						strpos(HERE, 'secure')!==FALSE OR
						strpos(FULL_DOMAIN, 'secure')!==FALSE
					) OR
					(
						# Check $_SERVER['https'].
						isset($_SERVER['https']) &&
						(
							# For Apache.
							$_SERVER['https']==1 OR
							# For IIS.
							$_SERVER['https']=='on'
						)
					)
				) OR
				(
					# Check $_SERVER['SERVER_PORT'] for others or if $_SERVER['https'] is unavailable.
					isset($_SERVER['SERVER_PORT']) &&
					$_SERVER['SERVER_PORT']==SECURE_PORT
				)
			)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	} #==== End -- isSSL

	/**
	 * ipValid
	 *
	 * A wrapper method for ipValid() from the IP calss.
	 *
	 * Will determine if a given IP address is valid or not.
	 *
	 * @access	public
	 * @param		$ip						The IP address to validate
	 * @return	boolean
	 */
	public function ipValid($ip)
	{
		# Set valid to FALSE by default.
		$valid=FALSE;
		# Clean up the passed email address.
		$ip=trim($ip);

		# Check if the passed email address is NOT empty.
		# It should be at least 7 characters (see http://stackoverflow.com/questions/22288483/whats-the-minimum-length-of-an-ip-address-in-string-representation)
		# and no more than 45 characters (see http://stackoverflow.com/questions/1076714/max-length-for-client-ip-address).
		$empty_email=$this->isEmpty($email, 7, 45);
		if($empty_email===FALSE)
		{
			# Get the IP Class.
			require_once Utility::locateFile(MODULES.'IP'.DS.'IP.php');
			# Create a new IP object.
			$ip_obj=IP::getInstance();
			$valid=$ip_obj->ipValid($ip);
		}
		return $valid;
	} #=== End -- ipValid

	/*** End public methods ***/

} # End Validator class.