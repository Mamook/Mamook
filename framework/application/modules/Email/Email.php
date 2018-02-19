<?php /* Requires PHP5+ */

namespace Mamoon\Email;

use Exception;
use PHPMailer;
use phpmailerException;

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

/**
 * Email
 *
 * The Email Class is used to send email.
 *
 * @dependencies    application/modules/Vendor/ezDB/ezdb.class.php
 *                    application/modules/Validator/Validator.php
 *                    data/email_definitions.php
 *                    data/path_definitions.php
 *                    data/user_privileges.php
 */
class Email
{
	/*** data members ***/

	private static $email;
	private $allowed_types=NULL;
	private $attachment=NULL;
	private $confirmation_template=NULL;
	private $display=NULL;
	private $email_page=NULL;
	private $is_html='no.means.no';
	private $max_file_size=7340032;
	private $message=NULL;
	private $recipients=NULL;
	private $sender_email=NULL;
	private $sender_name=NULL;
	private $site_name=NULL;
	private $subject=NULL;
	private $template=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * Gets the singleton instance of this class.
	 */
	public static function getInstance()
	{
		if(!self::$email)
		{
			self::$email=new Email();
		}

		return self::$email;
	}

	/**
	 * Sets the data member $allowed_types.
	 *
	 * @param array $types
	 */
	public function setAllowedTypes($types)
	{
		# Check if the passed value is empty or is an not array.
		if(empty($types) OR !is_array($types))
		{
			# Explicitly set the value to NULL.
			$types=NULL;
		}
		# Set the data member.
		$this->allowed_types=$types;
	}

	/**
	 * Sets the data member $attachment.
	 *
	 * @param $path
	 */
	public function setAttachment($path)
	{
		# Check if the passed value is empty.
		if(!empty($path))
		{
			# Check if the passed value is an array.
			if(!is_array($path))
			{
				# Clean it up.
				$path=trim($path);
			}
			# Set the data member.
			$this->attachment=$path;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->attachment=NULL;
		}
	}

	/**
	 * Sets the data member $confirmation_template.
	 *
	 * @param $path
	 */
	public function setConfirmationTemplate($path)
	{
		# Check if the passed value is empty.
		if(!empty($path))
		{
			# Clean it up.
			$path=trim($path);
			# Check if this is a file.
			if(is_file($path)===FALSE)
			{
				# Set the data member.
				$path=NULL;
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$path=NULL;
		}
		# Set the data member.
		$this->confirmation_template=$path;
	}

	/**
	 * Sets the data member $display.
	 *
	 * @param $display
	 */
	public function setDisplay($display)
	{
		# Check if the passed value is empty.
		if(!empty($display))
		{
			# Clean it up.
			$display=trim($display);
			# Set the data member.
			$this->display=$display;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->display=NULL;
		}
	}

	/**
	 * Sets the data member $email_page.
	 *
	 * @param string $email_page
	 */
	public function setEmailPage($email_page)
	{
		# Check if the passed value is empty.
		if(!empty($email_page))
		{
			# Clean it up.
			$email_page=trim($email_page);
			# Set the data member.
			$this->email_page=$email_page;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->email_page=NULL;
		}
	}

	/**
	 * Sets the data member $is_html.
	 *
	 * @param string $boolean Either "yes2yes"->TRUE or "no.means.no"->FALSE
	 */
	public function setIsHTML($boolean)
	{
		# Check if the passed value is empty.
		if($boolean!=='yes2yes')
		{
			# Explicitly set the data member to FALSE.
			$this->is_html='no.means.no';
		}
		# Explicitly set the data member to TRUE.
		$this->is_html='yes2yes';
	}

	/**
	 * Sets the data member $max_file_size.
	 *
	 * @param int $bytes The maximum allowed size of the file in bytes.
	 */
	public function setMaxFileSize($bytes)
	{
		# Check if the passed value is empty.
		if(!empty($bytes))
		{
			# Clean it up.
			$bytes=trim($bytes);
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed value is an integer.
			if($validator->isInt($bytes)===TRUE)
			{
				# Set the data member.
				$this->max_file_size=$bytes;
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->max_file_size=NULL;
		}
	}

	/**
	 * Sets the data member $message.
	 *
	 * @param $message
	 */
	public function setMessage($message, $html=FALSE)
	{
		# Check if the passed value is empty.
		if(!empty($message))
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			$level=4;
			# Check if this should be html.
			if($html===TRUE)
			{
				$level=5;
			}
			# Clean it up.
			$message=$db->sanitize($message, $level);
			# Set the data member.
			$this->message=$message;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->message=NULL;
		}
	}

	/**
	 * Sets the data member $recipients.
	 *
	 * @param string $recipients
	 * @param boolean $csv Whether to set the values as a comma separated string.
	 */
	public function setRecipients($recipients, $csv=TRUE)
	{
		# Check if the passed value is empty.
		if(!empty($recipients))
		{
			# Explicitly make it an array.
			$recipients=(array)$recipients;
			# Check if it should be comma separated values.
			if($csv===TRUE)
			{
				$recipients=implode(',', $recipients);
			}
			# Set the data member.
			$this->recipients=$recipients;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->recipients=NULL;
		}
	}

	/**
	 * Sets the data member $sender_email.
	 *
	 * @param $email
	 */
	public function setSenderEmail($email)
	{
		# Check if the passed value is empty.
		if(!empty($email))
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Clean it up.
			$email=$db->sanitize($email);
			# Set the data member.
			$this->sender_email=$email;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sender_email=NULL;
		}
	}

	/**
	 * Sets the data member $sender_name.
	 *
	 * @param $realname
	 */
	public function setSenderName($realname)
	{
		# Check if the passed value is empty.
		if(!empty($realname))
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Clean it up.
			$realname=$db->sanitize($realname);
			# Set the data member.
			$this->sender_name=$realname;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sender_name=NULL;
		}
	}

	/**
	 * Sets the data member $site_name.
	 *
	 * @param $site_name
	 */
	public function setSiteName($site_name)
	{
		# Check if the passed value is empty.
		if(!empty($site_name))
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Clean it up.
			$site_name=$db->sanitize($site_name);
			# Set the data member.
			$this->site_name=$site_name;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->site_name=NULL;
		}
	}

	/**
	 * Sets the data member $subject.
	 *
	 * @param $subject
	 */
	public function setSubject($subject)
	{
		# Check if the passed value is empty.
		if(!empty($subject))
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Clean it up.
			$subject=$db->sanitize($subject);
			# Set the data member.
			$this->subject=$subject;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->subject=NULL;
		}
	}

	/**
	 * Sets the data member $template.
	 *
	 * @param $path
	 */
	public function setTemplate($path)
	{
		# Check if the passed value is empty.
		if(!empty($path))
		{
			# Clean it up.
			$path=trim($path);
			# Check if this is a file.
			if(is_file($path)===FALSE)
			{
				# Set the data member.
				$path=NULL;
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$path=NULL;
		}
		# Set the data member.
		$this->template=$path;
	}

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * Returns the data member $allowed_types.
	 */
	public function getAllowedTypes()
	{
		return $this->allowed_types;
	}

	/**
	 * Returns the data member $attachment.
	 */
	public function getAttachment()
	{
		return $this->attachment;
	}

	/**
	 * Returns the data member $confirmation_template.
	 */
	public function getConfirmationTemplate()
	{
		return $this->confirmation_template;
	}

	/**
	 * Returns the data member $display.
	 */
	public function getDisplay()
	{
		return $this->display;
	}

	/**
	 * Returns the data member $email_page.
	 */
	public function getEmailPage()
	{
		return $this->email_page;
	}

	/**
	 * Returns the data member $is_html.
	 */
	public function getIsHTML()
	{
		return $this->is_html;
	}

	/**
	 * Returns the data member $max_file_size.
	 */
	public function getMaxFileSize()
	{
		return $this->max_file_size;
	}

	/**
	 * Returns the data member $message.
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Returns the data member $recipients.
	 */
	public function getRecipients()
	{
		return $this->recipients;
	}

	/**
	 * Returns the data member $sender_email.
	 */
	public function getSenderEmail()
	{
		return $this->sender_email;
	}

	/**
	 * Returns the data member $sender_name.
	 */
	public function getSenderName()
	{
		return $this->sender_name;
	}

	/**
	 * Returns the data member $site_name.
	 */
	public function getSiteName()
	{
		return $this->site_name;
	}

	/**
	 * Returns the data member $subject.
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Returns the data member $template.
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * Adds email addresses to the InvalidEmail.log file.
	 *
	 * @param mixed $email A string of comma separated email addresses to add to a new "Recipient".
	 * @param bool $reset  Indicates if the file should get erased.
	 * @return bool The number of bytes written on success, FALSE on failure.
	 */
	public function editInvalidEmail($email=NULL, $reset=FALSE)
	{
		# Get the FileHandler class.
		require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
		# Instantate a new instance of FileHandler.
		$file_handler=new FileHandler();
		# Create a default string to write to the file.
		$string='';
		# Check if there was an email address passed.
		if(!empty($email))
		{
			# Create a string with the email addresses.
			$string=$email.' :: '.date("Y-m-d")."\n";
		}
		elseif($email!==NULL)
		{
			# Create a string with the email addresses.
			$string='ERROR :: '.date("Y-m-d")."\n";
			$string.="\t".'"'.$email.'" is not acceptable data.';
		}

		# Edit the the InvalidEmail.log file.
		return $file_handler->editFile(LOGS.'InvalidEmail.log', $string, $reset);
	}

	/**
	 * sendEmail
	 *
	 * Handles all emailing from one place.
	 *
	 * @param string $subject
	 * @param string $to
	 * @param string $body
	 * @param string $reply_to     Optional
	 * @param array $attachment    Optional
	 * @param bool|string $is_html Optional
	 * @return bool TRUE/FALSE
	 * @throws Exception
	 */
	public function sendEmail($subject, $to, $body, $reply_to=SMTP_FROM, $attachment=NULL, $is_html=MAIL_IS_HTML)
	{
		try
		{
			# Get the Utility Class.
			require_once UTILITY_CLASS;
			# Get the PHPMailer class.
			//require_once Utility::locateFile(MODULES.'Vendor'.DS.'PHPMailer'.DS.'PHPMailerAutoload.php');
			# Instantiate a new PHPMailer object.
			$mail=new PHPMailer;

			# Check if SMTP is enabled.
			if(USE_SMTP===TRUE)
			{
				$mail->SMTPDebug=0;
				$mail->IsSMTP();
				$mail->SMTPAuth=TRUE;
				$mail->Host=SMTP_HOST;
				$mail->Port=SMTP_PORT;
				$mail->Password=SMTP_PASS;
				$mail->Username=SMTP_USER;
			}

			$mail->From=SMTP_FROM;
			$mail->FromName=DOMAIN_NAME;
			$mail->AddAddress($to);
			$mail->AddReplyTo($reply_to, DOMAIN_NAME);
			$mail->Subject=$subject;
			$mail->Body=$body;
			$mail->WordWrap=100;
			$mail->IsHTML($is_html);
			$mail->SMTPSecure=SMTP_SECURE;
			$mail->Timeout=30;
			$mail->AltBody=Utility::htmlToText($body);

			# Check if there is an attachment.
			if($attachment!==NULL)
			{
				$mail->AddAttachment($attachment);
			}

			if(!$mail->send())
			{
				if(RUN_ON_DEVELOPMENT)
				{
					# Spit that bug out.
					throw new Exception($mail->ErrorInfo, E_RECOVERABLE_ERROR);
				}

				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		catch(phpmailerException $pe)
		{
			throw new Exception($pe, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Executes the Tectite FormMail script. (See Tectite.com for more on the script.)
	 *
	 */
	public function sendFormEmail()
	{
		# Set all the globals needed for the formmail script to work inside a class method.
		global
			$aAlertInfo,
			$aAllRawValues,
			$aCleanedValues,
			$aEnvVars,
			$aFieldOrder,
			$aFileVars,
			$aFormVars,
			$aGetMessageValues,
			$aGetMessageSubstituteErrors,
			$aGetMessageSubstituteFound,
			$aGetVars,
			$aMessages,
			$aRawDataValues,
			$aServerVars,
			$aSessionVarNames,
			$aStrippedFormVars,
			$aSubstituteErrors,
			$b_in_here,
			$bGetMessageSubstituteNoErrors,
			$bGotGoBack,
			$bGotNextForm,
			$bHasGetData,
			$bIsGetMethod,
			$bMultiForm,
			$bPathSaved,
			$bReverseCaptchaCompleted,
			$bShowMesgNumbers,
			$BuiltinFunctions,
			$bUseOldVars,
			$iAddLineNumbersCounter,
			$iFormIndex,
			$lNow,
			$sFormMailScript,
			$sHTMLCharSet,
			$sLangID,
			$sSavePath,
			$sSubstituteMissing,
			$SubstituteFields,
			$sUserAgent,
			$ADVANCED_TEMPLATES,
			$ALERT_ON_ATTACK_DETECTION,
			$ALERT_ON_USER_ERROR,
			$ALLOW_GET_METHOD,
			$AR_OPTS,
			$AT_MANGLE,
			$ATTACK_DETECTION_DUPS,
			$ATTACK_DETECTION_IGNORE_ERRORS,
			$ATTACK_DETECTION_JUNK,
			$ATTACK_DETECTION_JUNK_CONSONANTS,
			$ATTACK_DETECTION_JUNK_CONSEC_CONSONANTS,
			$ATTACK_DETECTION_JUNK_CONSEC_VOWELS,
			$ATTACK_DETECTION_JUNK_IGNORE_FIELDS,
			$ATTACK_DETECTION_JUNK_LANG_STRIP,
			$ATTACK_DETECTION_JUNK_TRIGGER,
			$ATTACK_DETECTION_JUNK_VOWELS,
			$ATTACK_DETECTION_MANY_URLS,
			$ATTACK_DETECTION_MANY_URL_FIELDS,
			$ATTACK_DETECTION_MIME,
			$ATTACK_DETECTION_REVERSE_CAPTCHA,
			$ATTACK_DETECTION_SPECIALS,
			$ATTACK_DETECTION_SPECIALS_ANY_EMAIL,
			$ATTACK_DETECTION_SPECIALS_ONLY_EMAIL,
			$ATTACK_DETECTION_URL,
			$ATTACK_DETECTION_URL_PATTERNS,
			$AUTH_PW,
			$AUTH_USER,
			$AUTHENTICATE,
			$AUTORESPONDLOG,
			$BODY_LF,
			$CHECK_DAYS,
			$CHECK_FILE,
			$CHECK_FOR_NEW_VERSION,
			$CLEANUP_CHANCE,
			$CLEANUP_TIME,
			$CONFIG_CHECK,
			$CRM_OPTS,
			$CSVDIR,
			$CSVINTSEP,
			$CSVLINE,
			$CSVOPEN,
			$CSVQUOTE,
			$CSVSEP,
			$DB_SEE_INI,
			$DB_SEE_INPUT,
			$DEF_ALERT,
			$DESTROY_SESSION,
			$EMAIL_ADDRS,
			$EMAIL_NAME,
			$ENABLE_ATTACK_DETECTION,
			$ExecEnv,
			$FILE_MODE,
			$FILE_OVERWRITE,
			$FILE_REPOSITORY,
			$FILEUPLOADS,
			$FILTER_ATTRIBS,
			$FILTER_ATTRIBS_LOOKUP,
			$FILTER_OPTS,
			$FILTERS,
			$FIXED_SENDER,
			$FM_UserErrors,
			$FM_VERS,
			$FMCalc,
			$FMCOMPUTE,
			$FMCTemplProc,
			$FMGEOIP,
			$FORM_INI_FILE,
			$FORMATTED_INPUT,
			$FROM_USER,
			$GEOIP_LIC,
			$HEAD_CRLF,
			$HOOK_DIR,
			$INI_SET_FROM,
			$LIMITED_IMPORT,
			$LOGDIR,
			$MAIL_OPTS,
			$MAX_FILE_UPLOAD_SIZE,
			$MAXSTRING,
			$MODULEDIR,
			$MULTIFORMDIR,
			$MULTIFORMURL,
			$NEXT_NUM_FILE,
			$PEAR_SMTP_HOST,
			$PEAR_SMTP_PORT,
			$PEAR_SMTP_PWD,
			$PEAR_SMTP_USER,
			$PUT_DATA_IN_URL,
			$REAL_DOCUMENT_ROOT,
			$RECAPTCHA_PRIVATE_KEY,
			$reCaptchaProcessor,
			$REQUIRE_CAPTCHA,
			$SCHEME,
			$SCRATCH_PAD,
			$SENDMAIL_F_OPTION,
			$SENDMAIL_F_OPTION_LINE,
			$SERVER,
			$SESSION_ACCESS,
			$SESSION_NAME,
			$SessionAccessor,
			$SET_REAL_DOCUMENT_ROOT,
			$SET_SENDER_FROM_EMAIL,
			$SITE_DOMAIN,
			$SOCKET_FILTERS,
			$SPECIAL_ARRAYS,
			$SPECIAL_FIELDS,
			$SPECIAL_MULTI,
			$SPECIAL_NOSTRIP,
			$SPECIAL_VALUES,
			$TARGET_EMAIL,
			$TARGET_URLS,
			$TEMPLATEDIR,
			$TEMPLATEURL,
			$TEXT_SUBS,
			$VALID_AR_OPTIONS,
			$VALID_CRM_OPTIONS,
			$VALID_ENV,
			$VALID_FILTER_OPTIONS,
			$VALID_MAIL_OPTIONS,
			$ValidEmails,
			$ZERO_IS_EMPTY;
		try
		{
			require_once Utility::locateFile(MODULES.'Vendor'.DS.'FormMail'.DS.'formmail.php');
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Sends multiple an email to multiple users with a set time interval wait between emails.
	 *
	 * @param array $email_data An array of (probably POST) data about the email, ie sender address, subject, message, etc.
	 * @throws Exception
	 */
	public function sendMultipleEmails($email_data=NULL)
	{
		try
		{
			# Get the Utility Class.
			require_once UTILITY_CLASS;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();

			# Set the passed email data to the appropriate data member.
			$this->setEmailData($email_data);

			/*
			# Check if there was an attachment.
			if($email_data['file']!=0)
			{
				# Set the attachment to a variable.
				$attachment=((!is_array($email_data['file'])) ? unserialize($email_data['file']) : $email_data['file']);
			}
			else
			{
				$attachment=0;
			}
			# Set the email message to a variable.
			$message=htmlentities($email_data['message'], ENT_QUOTES, 'UTF-8', FALSE);
			# Set the sender's email to a variable.
			$sender_email=$email_data['email'];
			# Get the email sender's name and set it to a variable.
			$sender_name=htmlentities($email_data['realname'], ENT_QUOTES, 'UTF-8', FALSE);
			# Set the subject to a variable.
			$subject=htmlentities($email_data['subject'], ENT_QUOTES, 'UTF-8', FALSE);
			# Set the "to" array to a variable.
			$to=((!is_array($email_data['to'])) ? unserialize($email_data['to']) : $email_data['to']);
			*/

			# Set the attachment value to a variable.
			$attachment=$this->getAttachment();
			# Set the MAILQUEUE_BATCH_SIZE to a variable.
			$batch=MAILQUEUE_BATCH_SIZE;
			$body='';
			# Set the html value to a variable.
			$html=$this->getIsHTML();
			# Set the maximum acceptable file size to a variable.
			$max_size=$this->getMaxFileSize();
			# Set the email message to a variable.
			$message=nl2br(htmlentities($this->getMessage(), ENT_QUOTES, 'UTF-8', FALSE));
			# Get the email sender's name and set it to a variable.
			$sender_name=htmlentities($this->getSenderName(), ENT_QUOTES, 'UTF-8', FALSE);
			# Check if there is a sender name.
			if(empty($sender_name))
			{
				$sender_name='The '.DOMAIN_NAME.' Web Team';
			}
			# Get the email sender's email and set it to a variable.
			$sender_email=$this->getSenderEmail();
			# Set the current time as the start time.
			$start_time=time();
			# Set the subject to a variable.
			$subject=htmlentities($this->getSubject(), ENT_QUOTES, 'UTF-8', FALSE);
			# Set the site name to a variable.
			$site_name=$this->getSiteName();
			# Set the "to" array to a variable.
			$to=$this->getRecipients();
			# Create an array of user levels.
			$level=explode(',', $to);
			# Explode the ALL_USERS constant string into an array.
			$all_users=explode(' ', ALL_USERS);

			/*
			# Unserialize the to data is it is not an array already.
			$to=((!is_array($to)) ? unserialize($to) : $to);

			# Create a session with the email info.
			$_SESSION['email']=$sender_email;
			$_SESSION['email_body']=$message;
			$_SESSION['email_file']=$attachment;
			$_SESSION['email_html']=$html;
			$_SESSION['email_subject']=$subject;
			$_SESSION['email_to']=$to;
			$_SESSION['MAX_FILE_SIZE']=$max_size;
			$_SESSION['realname']=$sender_name;
			$sesh_id=session_id();
			*/
			# Check if there is a message, a subject, and recipients.
			if(!empty($message) && !empty($to) && !empty($subject))
			{
				$all_user_like=array();
				$good_email=array();
				$invalid_email=array();
				$level_like=array();
				$notify_like=array();
				$subscription_values=array();
				$used_branch_ids=array();
				$used_emails=array();
				$where='';
				$and_where='';

				# Loop through the user levels and build the "WHERE" statement.
				foreach($level as $user_level)
				{
					if(in_array($user_level, $all_users))
					{
						# Check if the user level matches the "level" field of a user in the "users" table and that the user has elected to recieve "newsletters".
						$all_user_like[]='`level` LIKE \'%-'.$user_level.'-%\'';
					}
					# Check if it is actually a user level (must be an integer.)
					elseif($validator->isInt($user_level)===TRUE)
					{
						# Check if the user level matches the "level" field of a user in the "users" table.
						$level_like[]='`level` LIKE \'%-'.$user_level.'-%\'';
						$branch_id=substr_replace($user_level, 0, -1, 1);
						# Check if this branch id has already been check for.
						if(!in_array($branch_id, $used_branch_ids))
						{
							$used_branch_ids[]=$branch_id;
							# Check if the user has elected to recieve notifications about the branch related to the user level.
							$notify_like[]='`notify` LIKE \'%-'.$branch_id.'-%\'';
						}
					}
					elseif($validator->isInt($user_level)===FALSE)
					{
						# Not a user level. Must be a subscription.
						$subscription_values[]='`'.$user_level.'` IS NOT NULL';
					}
				}
				# Join the all_user_like array into a String with " OR ".
				$all_user_like=implode(' OR ', $all_user_like);
				# Join the level_like array into a String with " OR ".
				$level_like=implode(' OR ', $level_like);
				# Join the subscription_values array into a String with " OR ".
				$subscription_values=implode(' OR ', $subscription_values);
				# Join the notify_like array into a String with " OR ".
				$notify_like=implode(' OR ', $notify_like);
				# Check if there is a "WHERE" statement for user levels.
				if(!empty($level_like))
				{
					$where.=$level_like;
				}
				# Check if there is a "WHERE" statement for subscribed users or all users.
				if(!empty($subscription_values) OR !empty($all_user_like))
				{
					$where.=((!empty($where)) ? ' OR ' : '').'(('.$subscription_values.((!empty($subscription_values) && !empty($all_user_like)) ? ' OR ' : '').$all_user_like.') AND `newsletter` = 0)';
				}
				# Check if there should be an "AND WHERE" statement.
				if(!empty($notify_like))
				{
					$and_where=' AND ('.$notify_like.')';
				}
				# Make certain that the WHERE statement is not empty.
				if(!empty($where))
				{
					# Get the users that match the level and assign them to the array.
					$recipients=$db->get_results('SELECT DISTINCT `display`, `email` FROM `'.DBPREFIX.'users` WHERE ('.$where.')'.$and_where.' AND `active` = 1');
					# Check if the query returned any results.
					if(!empty($recipients))
					{
						set_time_limit(0);
						$recipients_count=count($recipients);
						if($recipients_count<$batch)
						{
							$batch=$recipients_count;
						}
						$recipient_array_keys=array_keys($recipients);
						$last_key=end($recipient_array_keys);
						$message.='<br/>';
						# Get the email template. (Creates and populates the $body variable.)
						require Utility::locateFile($this->getTemplate());

						# Send emails until batch number is reached and then sleep.
						$i=1;
						# Loop through the recipients.
						foreach($recipients as $key=>$row)
						{
							# Check if this user's email was already sent.
							if(!in_array($row->email, $used_emails))
							{
								# Add this user's email to the used_emails array.
								$used_emails[]=$row->email;
								# Clean up the user's display name.
								$display_name=' '.htmlentities($row->display, ENT_QUOTES, 'UTF-8', FALSE);
								# Check if there is still a display name.
								if(empty($display_name))
								{
									# Explicitly set the display name to an empty string.
									$display_name='';
								}
								# Replace the first variable in the template. (Puts the display name in the salutation.)
								$current_body=sprintf($body, $display_name.',');
								# Validate the users email.
								$valid_email=$validator->validEmail($row->email);
								if($valid_email===TRUE)
								{
									# Add the email to the good_email array.
									$good_email[$row->display]=$row->email;

									### DEBUG ###
									if(DEBUG_APP===TRUE)
									{
										# Send email.
										$this->sendEmail($subject, ADMIN_EMAIL, $current_body);
									}
									else
									{
										# Send email.
										$this->sendEmail($subject, $row->email, $current_body);
									}

									# Increment the email counter by one.
									$i++;
									# Check if the current number of emails sent equals (or is greater than) the batch number.
									#	Don't sleep if it's the last element in the array.
									if($i>=$batch && $key!=$last_key)
									{
										# Pause for the passed amount of time.
										sleep(MAILQUEUE_THROTTLE);
										# Reset the email counter.
										$i=1;
									}
								}
								else
								{
									# Add the email to the invalid email list.
									$this->editInvalidEmail($row->email);
									# Add the invalid email to the invalid_email array.
									$invalid_email[$row->display]=$row->email;
								}
							}
						}
					}
				}

				# Set the elapsed time since the set start time to a variable.
				$elapsed_time=Utility::getElapsedTime($start_time, time());
				$sent_message=$message;
				# Set the email message to a variable.
				$message='The email you initiated to users of '.DOMAIN_NAME.' has been sent.';
				$message.='<br/>'."\n";
				$message.='You successfully emailed '.count($good_email).' users.'.((count($invalid_email)>0) ? ' '.count($invalid_email).' users were not emailed as their email addresses were invalid.' : '').' It took a total of '.$elapsed_time.' to send all of the emails. For more details, please see the attached email report.';
				$message.='<br/>'."\n";
				$string='EMAIL REPORT:'."\n\n";
				# Check if there were good email addresses.
				if(!empty($good_email))
				{
					$string.=count($good_email).' emails successfully sent to the following users:'."\n";
					foreach($good_email as $name=>$email_address)
					{
						$string.=$name.':'."\t\t".$email_address."\n";
					}
				}
				# Check if there were any invaild email addresses.
				if(!empty($invalid_email))
				{
					$string.="\n\n";
					$string.=count($invalid_email).' emails were NOT sent to the following users because their email addresses were invalid:'."\n";
					foreach($invalid_email as $name=>$email_address)
					{
						$string.=$name.':'."\t\t".$email_address."\n";
					}
				}
				$string.="\n\n".'The message you emailed was:'."\n";
				$string.=html_entity_decode($sent_message, ENT_QUOTES, 'UTF-8');
				$subject='Results of your group emailing from '.DOMAIN_NAME;
				$email_report=TEMP.date('Ymd').'EmailReport.txt';
				# Get the confirmation email template. (Resets and populates the $body variable.)
				require Utility::locateFile($this->getConfirmationTemplate());
				# Get the FileHandler class.
				require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
				# Instantate a new instance of FileHandler.
				$file_handler=new FileHandler();
				# Edit the the InvalidEmail.log file.
				$file_handler->editFile($email_report, $string, TRUE);
				# Replace the first variable in the template. (Puts the display name in the salutation.)
				$body=sprintf($body, ' '.$sender_name.',');
				# Send a confirmation email to the sender.
				$this->sendEmail($subject, $sender_email, $body, SMTP_FROM, $email_report);

				### DEBUG ###
				if(DEBUG_APP===TRUE)
				{
					$this->sendEmail($subject, ADMIN_EMAIL, $body, SMTP_FROM, $email_report);
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * Sets received email data to the appropriate data member.
	 *
	 * @param array $data
	 * @throws Exception
	 */
	protected function setEmailData($data=NULL)
	{
		try
		{
			# Check if there was email data passed to the method.
			if($data!==NULL)
			{
				$this->setAttachment($data['Attachment']);
				$this->setConfirmationTemplate($data['ConfirmationTemplate']);
				$this->setIsHTML($data['IsHTML']);
				$this->setMaxFileSize($data['MaxFileSize']);
				$this->setMessage($data['Message']);
				$this->setRecipients($data['Recipients']);
				$this->setSenderEmail($data['SenderEmail']);
				$this->setSenderName($data['SenderName']);
				$this->setSiteName($data['SiteName']);
				$this->setSubject($data['Subject']);
				$this->setTemplate($data['Template']);
			}
			# Check if there is session data.
			if(isset($_SESSION['email_subject']))
			{
				$this->setAttachment(((isset($_SESSION['email_file'])) ? $_SESSION['email_file'] : NULL));
				$this->setIsHTML(((isset($_SESSION['email_html'])) ? $_SESSION['email_html'] : NULL));
				$this->setMaxFileSize(((isset($_SESSION['MAX_FILE_SIZE'])) ? $_SESSION['MAX_FILE_SIZE'] : NULL));
				$this->setMessage(((isset($_SESSION['email_body'])) ? $_SESSION['email_body'] : NULL));
				$this->setSenderEmail(((isset($_SESSION['email'])) ? $_SESSION['email'] : NULL));
				$this->setSenderName(((isset($_SESSION['realname'])) ? $_SESSION['realname'] : NULL));
				$this->setSiteName(((isset($_SESSION['sitename'])) ? $_SESSION['sitename'] : NULL));
				$this->setSubject(((isset($_SESSION['email_subject'])) ? $_SESSION['email_subject'] : NULL));
				$this->setRecipients(((isset($_SESSION['email_to'])) ? $_SESSION['email_to'] : NULL));
			}
			# Check if there is POST data.
			if(array_key_exists('_submit_check', $_POST))
			{
				$this->setAttachment(((isset($_POST['file'])) ? $_POST['file'] : NULL));
				$this->setIsHTML(((isset($_POST['html'])) ? $_POST['html'] : NULL));
				$this->setMaxFileSize(((isset($_POST['MAX_FILE_SIZE'])) ? $_POST['MAX_FILE_SIZE'] : NULL));
				$this->setMessage(((isset($_POST['message'])) ? $_POST['message'] : NULL));
				$this->setSenderEmail(((isset($_POST['email'])) ? $_POST['email'] : NULL));
				$this->setSenderName(((isset($_POST['realname'])) ? $_POST['realname'] : NULL));
				$this->setSiteName(((isset($_POST['sitename'])) ? $_POST['sitename'] : NULL));
				$this->setSubject(((isset($_POST['subject'])) ? $_POST['subject'] : NULL));
				$this->setRecipients(((isset($_POST['to'])) ? $_POST['to'] : NULL));
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End protected methods ***/
}