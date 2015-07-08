<?php /* Requires PHP5+ */

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
	 * Determines if the passed param is a valid email.
	 * Returns FALSE if the email address is not valid.
	 *
	 * @param	$email (The email to check.)
	 * @access	public
	 */
	public function validEmail($email, $ping=FALSE)
	{
		$email=trim($email);
		$validate=new EmailAddressValidator();
		$valid=$validate->check_email_address($email, $ping);
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
	 * Will set the version of the IP address to the $ip_version data member.
	 *
	 * @access	public
	 * @param	$ip						The IP address to validate
	 * @return	boolean
	 */
	public function ipValid($ip)
	{
		# Get the IP Class.
		require_once Utility::locateFile(MODULES.'IP'.DS.'IP.php');
		# Create a new IP object.
		$ip_obj=IP::getInstance();
		return $ip_obj->ipValid($ip);
	} #=== End -- ipValid

	/*** End public methods ***/

} # End Validator class.



/**
 * EmailAddressValidator Class
 * http://code.google.com/p/php-email-address-validation/
 *
 * Released under New BSD license
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * Sample Code
 * ----------------
 * $validator = new EmailAddressValidator;
 * if($validator->check_email_address('test@example.org'))
 * {
 * 	# Email address is technically valid
 * }
 */

class EmailAddressValidator
{

	/**
	 * check_email_address
	 *
	 * Check email address validity
	 *
	 * @param	strEmailAddress	Email address to be checked
	 * @param	ping						Should it ping the domain name to see if it is an active domain?
	 * @return								True if email is valid, FALSE if not
	 * @access								public
	 */
	public function check_email_address($strEmailAddress, $ping=FALSE)
	{
		# If magic quotes is "on", email addresses with quote marks will
		# fail validation because of added escape characters. Uncommenting
		# the next four lines will allow for this issue.
		//if(get_magic_quotes_gpc())
		//{
		//    $strEmailAddress = stripslashes($strEmailAddress);
		//}

		# Control characters are not allowed
		if(preg_match('/[\x00-\x1F\x7F-\xFF]/', $strEmailAddress))
		{
			return FALSE;
		}

		# Check email length - min 3 (a@a), max 256
		if(!$this->check_text_length($strEmailAddress, 3, 256))
		{
			return FALSE;
		}

		# Split it into sections using last instance of "@"
		$intAtSymbol = strrpos($strEmailAddress, '@');
		if($intAtSymbol===FALSE)
		{
			# No "@" symbol in email.
			return FALSE;
		}
		$arrEmailAddress[0] = substr($strEmailAddress, 0, $intAtSymbol);
		$arrEmailAddress[1] = substr($strEmailAddress, $intAtSymbol + 1);

		/*
		 Count the "@" symbols. Only one is allowed, except where
		 contained in quote marks in the local part. Quickest way to
		 check this is to remove anything in quotes. We also remove
		 characters escaped with backslash, and the backslash
		 character.
		 */
		$arrTempAddress[0] = preg_replace('/\./'
			,''
			,$arrEmailAddress[0]);
		$arrTempAddress[0] = preg_replace('/"[^"]+"/'
			,''
			,$arrTempAddress[0]);
		$arrTempAddress[1] = $arrEmailAddress[1];
		$strTempAddress = $arrTempAddress[0] . $arrTempAddress[1];

		# Then check - should be no "@" symbols.
		if(strrpos($strTempAddress, '@')!==FALSE)
		{
			# "@" symbol found
			return FALSE;
		}

		# Check local portion
		if(!$this->check_local_portion($arrEmailAddress[0]))
		{
			return FALSE;
		}

		# Check domain portion
		if(!$this->check_domain_portion($arrEmailAddress[1], $ping))
		{
			return FALSE;
		}

		# If we're still here, all checks above passed. Email is valid.
		return TRUE;
	}

	/**
	 * check_local_portion
	 *
	 * Checks email section before "@" symbol for validity
	 *
	 * @param	strLocalPortion     Text to be checked
	 * @return	True if local portion is valid, FALSE if not
	 * @access	protected
	 */
	protected function check_local_portion($strLocalPortion)
	{
		# Local portion can only be from 1 to 64 characters, inclusive.
		# Please note that servers are encouraged to accept longer local
		# parts than 64 characters.
		if(!$this->check_text_length($strLocalPortion, 1, 64))
		{
			return FALSE;
		}
		# Local portion must be:
		# 1) a dot-atom (strings separated by periods)
		# 2) a quoted string
		# 3) an obsolete format string (combination of the above)
		$arrLocalPortion=explode('.', $strLocalPortion);
		for($i=0, $max=sizeof($arrLocalPortion); $i<$max; $i++)
		{
			if(!preg_match('.^('.
				'([A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]'.
				'[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]{0,63})'.
				'|'.
				'("[^\\\"]{0,62}")'.
				')$.',
				$arrLocalPortion[$i]))
			{
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * check_domain_portion
	 *
	 * Checks email section after "@" symbol for validity
	 *
	 * @param	strDomainPortion     Text to be checked
	 * @return	True if domain portion is valid, FALSE if not
	 * @access	protected
	 */
	protected function check_domain_portion($strDomainPortion, $ping=FALSE)
	{
		# Total domain can only be from 1 to 255 characters, inclusive
		if(!$this->check_text_length($strDomainPortion, 1, 255))
		{
			return FALSE;
		}
		# Check if domain is IP, possibly enclosed in square brackets.
		if(preg_match('/^(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
			.'(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}$/'
			,$strDomainPortion) ||
			preg_match('/^\[(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
			.'(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}\]$/'
			,$strDomainPortion))
		{
			return TRUE;
		}
		else
		{
			$arrDomainPortion=explode('.', $strDomainPortion);
			if(sizeof($arrDomainPortion)<2)
			{
				return FALSE; # Not enough parts to domain
			}
			for($i=0, $max=sizeof($arrDomainPortion); $i<$max; $i++)
			{
				# Each portion must be between 1 and 63 characters, inclusive
				if(!$this->check_text_length($arrDomainPortion[$i], 1, 63))
				{
					return FALSE;
				}
				if (!preg_match('/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|'
					.'([A-Za-z0-9]+))$/', $arrDomainPortion[$i]))
				{
					return FALSE;
				}
				if($i==$max-1)
				{ # TLD cannot be only numbers
					if(strlen(preg_replace('/[0-9]/', '', $arrDomainPortion[$i])) <= 0)
					{
						return FALSE;
					}
				}
			}
		}
		if($ping!==FALSE)
		{
			# Check if the email host is a valid host.
			if(checkdnsrr($strDomainPortion)===FALSE)
			{
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * check_text_length
	 *
	 * Check given text length is between defined bounds
	 *
	 * @param	strText     Text to be checked
	 * @param	intMinimum  Minimum acceptable length
	 * @param	intMaximum  Maximum acceptable length
	 * @return	True if string is within bounds (inclusive), FALSE if not
	 * @access	protected
	 */
	protected function check_text_length($strText, $intMinimum, $intMaximum)
	{
		# Minimum and maximum are both inclusive
		$intTextLength = strlen($strText);
		if(($intTextLength<$intMinimum) || ($intTextLength>$intMaximum))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

} # End EmailAddressValidator