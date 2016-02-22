<?php /* framework/application/modules/Validator/EmailAddressValidator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


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