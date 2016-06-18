<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * EmailFormPopulator
 *
 * The EmailFormPopulator Class is used populate emailing forms.
 *
 */
class EmailFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $email_object=NULL;
	private $initiate_curl='yupyes';

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setEmailObject
	 *
	 * Sets the data member $email_object.
	 *
	 * @param		$object
	 * @access	protected
	 */
	protected function setEmailObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->email_object=$object;
	} #==== End -- setEmailObject

	/**
	 * setInitiate_cURL
	 *
	 * Sets the data member $initiate_curl.
	 *
	 * @param		boolean		$boolean
	 * @access	protected
	 */
	protected function setInitiate_cURL($boolean)
	{
		# Check if the passed value is positive.
		if($boolean!=='yupyes')
		{
			# Explicitly set the value to negative.
			$boolean='noway';
		}
		# Set the data member.
		$this->initiate_curl=$boolean;
	} #==== End -- setInitiate_cURL

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getEmailObject
	 *
	 * Returns the data member $email_object.
	 *
	 * @access	public
	 */
	public function getEmailObject()
	{
		return $this->email_object;
	} #==== End -- getEmailObject

	/**
	 * getInitiate_cURL
	 *
	 * Returns the data member $initiate_curl.
	 *
	 * @access	public
	 */
	public function getInitiate_cURL()
	{
		return $this->initiate_curl;
	} #==== End -- getInitiate_cURL

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populateEmailForm
	 *
	 * Populates the Certificate Application form.
	 *
	 * @param	$data						An array of values tp populate the form with.
	 * @param	$index						The session index that has the data we want to set.
	 * @access	public
	 */
	public function populateEmailForm($data=array(), $index=NULL)
	{
		try
		{
			# Get the Email class.
			require_once Utility::locateFile(MODULES.'Email'.DS.'Email.php');
			# Instantiate a new Email object.
			$email=new Email();
			# Set the Email object to the email data member for use outside of this method.
			$this->setEmailObject($email);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray($index);

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getEmailObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populateEmailForm

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (EmailFormPopulator or SubContent).
	 *
	 * @access	protected
	 */
	protected function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['send']) && ($_POST['send']==='Send Email')))
			{
				# Set the data array to a local variable.
				$data=$this->getData();
				# Set the Email object to a variable.
				$email=$this->getEmailObject();

				# Check if the cURL value was passed via POST data.
				if(isset($_POST['cURL']))
				{
					# Set the email Email data member.
					$data['Initiate_cURL']=$_POST['cURL'];
				}

				# Check if the email category was passed via POST data.
				if(isset($_POST['email']))
				{
					# Set the email Email data member.
					$data['SenderEmail']=$_POST['email'];
				}

				# Check if FILES data was sent.
				if(isset($_FILES['file']))
				{
					$file=$_FILES['file'];
					if(!empty($file) && !is_array($file))
					{
						$file=unserialize($file);
					}
					# Set the file data to the Email data member.
					$data['Attachment']=$file;
				}

				# Check if the message POST data was sent.
				if(isset($_POST['mesg']))
				{
					$html=FALSE;
					if(isset($_POST['html']) && ($_POST['html']==='yes2yes'))
					{
						$html=TRUE;
						# Set the is_html Email data member effectively "cleaning" it.
						$data['IsHTML']='yes2yes';
					}
					# Set the message Email data member effectively "cleaning" it.
					$email->setMessage($_POST['mesg'], $html);
					# Set the message to the data array.
					$data['Message']=$email->getMessage();
				}

				# Check if the realname was passed via POST data.
				if(isset($_POST['realname']))
				{
					# Set the realname Email data member effectively "cleaning" it.
					$data['SenderName']=$_POST['realname'];
				}

				# Check if subject POST data was sent.
				if(isset($_POST['subject']))
				{
					# Set the subject to the Email data member.
					$data['Subject']=$_POST['subject'];
				}

				# Check if to POST data was sent.
				if(isset($_POST['to']))
				{
					$to=$_POST['to'];
					if(!empty($to) && !is_array($to))
					{
						$to=unserialize($to);
					}
					# Set the to data member.
					$data['Recipients']=$to;
				}
				# Reset the data array to the data member.
				$this->setData($data);
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setPostDataToDataArray

	/*** End protected methods ***/

} # End EmailFormPopulator class.