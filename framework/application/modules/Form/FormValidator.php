<?php /* framework/application/modules/Form/FormValidator.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the Validator Class
require_once Utility::locateFile(MODULES.'Validator'.DS.'Validator.php');

class FormValidator extends Validator
{
	/*** data members ***/

	private $errors=array();
	private $recaptcha_error;

	/*** End data members ***/



	/*** magic methods ***/

	# Constructor
	public function __construct()
	{
		return;
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	* setErrors
	*
	* Sets the data member $errors.
	*
	* @param	$error (The error string to set.)
	* @access	public
	*/
	public function setErrors($error)
	{
		$error=trim($error);
		$this->errors[]=$error;
	} #==== End -- setErrors

	/**
	* setReCaptchaError
	*
	* Sets the data member $recaptcha_error.
	*
	* @param	$error (The error string to set.)
	* @access	protected
	*/
	protected function setReCaptchaError($error)
	{
		$error=trim($error);
		$this->recaptcha_error=$error;
	} #==== End -- setReCaptchaError

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	* getErrors
	*
	* Returns the data member $errors.
	*
	* @access	public
	*/
	public function getErrors()
	{
		return $this->errors;
	} #==== End -- getErrors

	/**
	* getReCaptchaError
	*
	* Returns the data member $recaptcha_error.
	*
	* @access	public
	*/
	public function getReCaptchaError()
	{
		return $this->recaptcha_error;
	} #==== End -- getReCaptchaError

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * validateEmpty
	 *
	 * Determines if the POST variable associated with the passed field is not set and is empty.
	 *
	 * @param	$field	(The field of the POST variable to check.)
	 * @param	$min 		(The minimum length of a passed string.)
	 * @param	$max 		(The maximum length of a passed string.)
	 * @access	public
	 */
	/*public function validateEmpty($field, $errorMessage, $min=4, $max=32)
	{
		$valid=TRUE;
		if(!isset($_POST[$field]) || trim($_POST[$field])=='' || strlen($_POST[$field])<$min || strlen($_POST[$field])>$max)
		{
			$valid=FALSE;
		}
		return $valid;
	} #==== End -- isEmpty*/

	# validate empty field
	public function validateEmpty($field, $errorMessage, $min=4, $max=32)
	{
		$empty=$this->isEmpty(((isset($_POST[$field])) ? $_POST[$field] : ''), $min, $max);
		if($empty===TRUE)
		{
			$this->setErrors($errorMessage);
		}
		return $empty;
	}

	# validate integer field
	public function validateInt($field, $errorMessage)
	{
		$valid=$this->isInt(((isset($_POST[$field])) ? $_POST[$field] : ''));
		if($valid===FALSE)
		{
			$this->setErrors($errorMessage);
		}
		return $valid;
	}

	# validate numeric field
	public function validateNumber($field, $errorMessage)
	{
		$valid=$this->isNumber(((isset($_POST[$field])) ? $_POST[$field] : ''));
		if($valid===FALSE)
		{
			$this->setErrors($errorMessage);
		}
		return $valid;
	}

	# validate if field is within a range
	public function validateRange($field, $errorMessage, $min=1, $max=99)
	{
		$valid=$this->isInRange(((isset($_POST[$field])) ? $_POST[$field] : ''), $min, $max);
		if($valid===FALSE)
		{
			$this->setErrors($errorMessage);
		}
		return $valid;
	}

	# validate alphabetic field
	public function validateAlphabetic($field, $errorMessage)
	{
		$valid=$this->isAlphabetic(((isset($_POST[$field])) ? $_POST[$field] : ''));
		if($valid===FALSE)
		{
			$this->setErrors($errorMessage);
		}
		return $valid;
	}

	# validate alphanumeric field
	public function validateAlphanum($field, $errorMessage)
	{
		$valid=$this->isAlphanum(((isset($_POST[$field])) ? $_POST[$field] : ''));
		if($valid===FALSE)
		{
			$this->setErrors($errorMessage);
		}
		return $valid;
	}

	# validate email
	public function validateEmail($email, $errorMessage, $ping=FALSE)
	{
		$valid=$this->validEmail(((isset($_POST[$email])) ? $_POST[$email] : ''), $ping);
		if($valid===FALSE)
		{
			$this->setErrors($errorMessage);
		}
		return $valid;
	}

	/**
	 * validURL
	 *
	 * Determines if the passed param is a valid URL.
	 * Returns FALSE if the URL address is not valid.
	 *
	 * @param	$url (The URL to check.)
	 * @access	public
	 */
	public function validateURL($url, $errorMessage)
	{
		$valid=parent::validURL(((isset($_POST[$url])) ? $_POST[$url] : ''));
		if($valid===FALSE)
		{
			$this->setErrors($errorMessage);
		}
		return $valid;
	} #==== End -- validURL

	# check for errors
	public function checkErrors()
	{
		if(count($this->getErrors())>0)
		{
			return TRUE;
		}
		return FALSE;
	}

	# return errors
	public function displayErrors()
	{
		$errorOutput='<ul>';
		foreach($this->errors as $err)
		{
			$errorOutput.='<li>'.$err.'</li>';
		}
		$errorOutput.='</ul>';
		return $errorOutput;
	}

	/**
	* reCaptchaCheckAnswer
	*
	* Receive the response from the reCaptcha server.
	*
	* @param	$privkey			(The Private key supplied by reCAPTCHA associated with this domain name.)
	* @param	$remoteip 		(The IP address from which the user is viewing the current page.)
	* @param	$challenge 		(The challenge the user needs to match.)
	* @param	$response 		(The user's response to the challenge.)
	* @param	$extra_params (Extra parameters (an array).)
	* @param	$message 			(The error message.)
	* @access	public
	*/
	public function reCaptchaCheckAnswer($privkey, $remoteip, $challenge, $response, $extra_params=array(), $message='The reCAPTCHA wasn\'t entered correctly. Please try it again.')
	{
		# Get the reCaptcha Library.
		require_once Utility::locateFile(MODULES.'Form'.DS.'recaptchalib.php');
		$response=recaptcha_check_answer($privkey, $remoteip, $challenge, $response, $extra_params);
		if($response->is_valid===FALSE)
		{
			$this->setReCaptchaError($response->error);
			$this->setErrors($message);
		}
		return $response->is_valid;
	} #==== End -- reCaptchaCheckAnswer

	/*** End public methods ***/



	/*** private methods ***/

	# private method 'windnsrr()' for Windows systems
	private function windnsrr($hostName, $recType='')
	{
		if(!empty($hostName))
		{
			if($recType=='')$recType="MX";
			exec("nslookup -type=$recType $hostName",$result);
			foreach($result as $line)
			{
				if(preg_match("/^$hostName/",$line))
				{
					return true;
				}
			}
			return false;
		}
		return false;
	}

	/*** End public methods ***/

} #==== End FormValidator class.