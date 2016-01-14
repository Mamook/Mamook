<?php
$FM_VERS = "9.10"; // script version

/* ex:set ts=4 sw=4 et:
 * FormMail PHP script from Tectite.com.  This script requires PHP 5 or later.
 * Versions of Tectite FormMail are available for PHP 4 (look for versions 8 and below).
 * Copyright (c) 2001-2015 Open Concepts (Vic) Pty Ltd
 * (ABN 12 130 429 248), Melbourne, Australia.
 * This script is free for all use as described in the "Copying and Use" and
 * "Warranty and Disclaimer" sections below.
 *
 * Visit us at http://www.tectite.com/ for updates and more information.
 *
 *** If you use Tectite FormMail, please support its development and other
 *** freeware products by putting the following link on your website:
 ***  Visit www.tectite.com for free <a href="http://www.tectite.com/">FormMail</a>.
 *
 * Author: Russell Robinson
 * First released: 2nd October 2001 
 *
 * Read This First
 * ~~~~~~~~~~~~~~~
 *  This script is very well documented and quite large!  It looks daunting,
 *  but really isn't.
 *  If you have experience with PHP or other scripting languages,
 *  here's what you *need* to read:
 *      - Configuration (TARGET_EMAIL & DEF_ALERT)
 *      - Creating Forms
 *  That's it!  (Alternatively, just read the Quick Start and/or
 *  Quicker Start section below).
 *  Full configuration documentation is here:
 *      http://www.tectite.com/fmdoc/index.php
 *
 *  NOTE: do not read or modify this script or any PHP script
 *  with DreamWeaver or FrontPage!
 *  Many versions of those programs silently corrupt PHP scripts.
 *
 * Purpose:
 * ~~~~~~~~
 *  To accept information from an HTML form via HTTP and mail it to recipients.
 *
 * What does this PHP script do?
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *  On your web site, you may have one or more HTML forms that accept
 *  information from people visiting your website.  Your aim is for your
 *  website to email that information to you and/or add it to a database.
 *  FormMail performs those functions.
 *
 * Quick Start
 * ~~~~~~~~~~~
 *  1. Edit this file and set TARGET_EMAIL for your requirements (near
 *      line 402 in this file - replace "yourhost\.com" with your mail server's
 *      name).  We also strongly recommend you set DEF_ALERT (the next
 *      configuration below TARGET_EMAIL).
 *  2. Install this file as formmail.php (or other name ending in .php)
 *     on your web server.
 *     Test alerts by using your browser to open a URL to the script:
 *          http://www.yourhost.com/formmail.php?testalert=1
 *     Alerts are the only way FormMail can tell you the details of
 *     errors or faults.
 *  3. Create an HTML form and:
 *      - specify a hidden field called "recipients" with the email address
 *        of the person to receive the form's results.
 *      - in the your form tag set the action attribute to
 *        the formmail.php you uploaded to your web server
 *
 *  Once you have FormMail working, you may be interested in some advanced
 *  usage and features.  We have HOW-TO guides at www.tectite.com which
 *  describe many of the advanced processing you can do with FormMail.
 *      http://www.tectite.com/fmhowto/guides.php
 *
 * Quicker Start
 * ~~~~~~~~~~~~~
 *  Use the FormMail Configuration Wizard here:
 *      http://www.tectite.com/wizards/fmconf.php
 *  By answering a few questions you'll get a configured FormMail and
 *  a sample HTML form ready to upload and use on your server.
 *
 * Features
 * ~~~~~~~~
 *  For a list of features go to: http://www.tectite.com/formmailpage.php
 *
 * Security
 * ~~~~~~~~
 *  Security is the primary concern in accepting data from your website
 *  visitors.
 *  Tectite FormMail has several security features designed into it.  Note,
 *  however, it requires configuration for your particular web site.
 *
 * Configuration
 * ~~~~~~~~~~~~~
 *  To configure this script, go to the section titled "CONFIGURATION"
 *  (after reading the legal stuff below).
 *
 *  There is only one mandatory setting: TARGET_EMAIL
 *  and one strongly recommended setting: DEF_ALERT
 *
 *  Full configuration information is available here:
 *      http://www.tectite.com/fmdoc/index.php
 *
 * Creating Forms
 * ~~~~~~~~~~~~~~
 *  Go to this URL to learn how to write HTML forms for use with
 *  Tectite FormMail: http://www.tectite.com/fmdoc/creating_forms.php
 *
 * Copying and Use (Software License)
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *  Tectite FormMail is provided free of charge and may be freely distributed
 *  and used provided that you:
 *      1. keep this header, including copyright and comments,
 *         in place and unmodified; and,
 *      2. do not charge a fee for distributing it, without an agreement
 *         in writing with Root Software allowing you to do so; and,
 *      3. if you modify FormMail before distributing it, you clearly
 *         identify:
 *              a) who you are
 *              b) how to contact you
 *              c) what changes you have made
 *              d) why you have made those changes.
 *
 *  By using any of our products, including this script, you are
 *  agreeing to our standard Terms and Conditions, available here:
 *      http://www.tectite.com/TermsAndConditions.pdf
 *
 *  This is free software and the Software License shown above
 *  is to be read in conjunction with our standard Terms and Conditions.
 *
 * Warranty and Disclaimer
 * ~~~~~~~~~~~~~~~~~~~~~~~
 *  Tectite FormMail is provided free-of-charge and with ABSOLUTELY NO WARRANTY.
 *  It has not been verified for use in critical applications, including,
 *  but not limited to, medicine, defense, aircraft, space exploration,
 *  or any other potentially dangerous activity.
 *
 *  By using Tectite FormMail you agree to indemnify Root Software and
 *  Open Concepts (Vic) Pty Ltd, their agents, employees, directors and
 *  associated companies and businesses from any liability whatsoever.
 *
 * We still care
 * ~~~~~~~~~~~~~
 *  If you find a bug or fault in FormMail, please report it to us.
 *  We will respond to your report and make endeavours to rectify any
 *  faults you've detected as soon as possible.
 *
 *  To contact us please register on our forums at:
 *      http://www.tectite.com/vbforums/
 *  or view our contact information:
 *      http://www.tectite.com/contacts.php
 *
 * Version History
 * ~~~~~~~~~~~~~~~
 *  Near the top of this file, you'll find its version. The version
 *  line looks like this:
 *       $FM_VERS = "N.MM";     /* script version ...
 *
 *  The version history used to be located within this file.  However,
 *  starting with Version 8.00 we've moved it...
 *
 *  You can read the complete version history of FormMail on our
 *  main website here:
 *   http://www.tectite.com/fmdoc/version_history.php
 */

FMDebug('Submission to: ' . (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '') . ' from: ' .
        (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));

if (isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'OPTIONS') {
	FMDebug('CORS OPTIONS request');
	CORS_Response();
	exit();
}

//
// Capture the current date and time, for various purposes.
//
date_default_timezone_set('UTC'); /* prevent notice in PHP 5.1+ */
$lNow = time();

ini_set('track_errors',1); // enable $php_errormsg

$aAlertInfo = array();

$sLangID   = ""; // the language ID
$aMessages = array(); // all FormMail messages in the appropriate language

/**
 * Interrogate and manage the execution environment.
 *
 * @package    tectite_formmail
 * @subpackage setup
 */
class ExecEnv
{
	/**
	 * Value of phpversion().
	 *
	 * @var string
	 */
	private $_sPHPVersionString;

	/**
	 * Array containing the elements of the PHP version
	 *
	 * @var array
	 */
	private $_aPHPVersion;

	/**
	 * The URL for the script.
	 *
	 * @var string
	 */
	private $_sScript;

	/**
	 * Construct the class, and check PHP version.
	 */
	function __construct()
	{
		$this->_Init();
		$this->_CheckVersion();
	}

	/**
	 * Initialise the object.
	 * Sets {@link $_aPHPVersion} and {@link $_sPHPVersionString}
	 */
	private function _Init()
	{
		$this->_sPHPVersionString = phpversion();
		$this->_aPHPVersion       = explode(".",$this->_sPHPVersionString);
	}

	/**
	 * @return array
	 */
	public function getPHPVersion()
	{
		return $this->_aPHPVersion;
	}

	/**
	 * @return string
	 */
	public function getPHPVersionString()
	{
		return $this->_sPHPVersionString;
	}

	/**
	 * Check for old version of PHP - die with a message if too old.
	 *
	 * This is actually not required because PHP 4 won't even accept
	 * the syntax of this PHP 5 script. However, we might need some
	 * other version check in the future, so this is a useful method
	 * to have around in that case.
	 */
	private function _CheckVersion()
	{
		$s_req_string = "5.0.0"; // We only support PHP 5 onward.
		$a_too_old    = explode(".",$s_req_string);

		$i_cannot_use = ($a_too_old[0] * 10000) + ($a_too_old[1] * 100) + $a_too_old[2];

		$i_this_num = ($this->_aPHPVersion[0] * 10000) + ($this->_aPHPVersion[1] * 100) +
		              $this->_aPHPVersion[2];

		if ($i_this_num <= $i_cannot_use) {
			die(
			GetMessage(MSG_SCRIPT_VERSION,
			           array("PHPREQ" => $s_req_string,"PHPVERS" => $this->_sPHPVersionString)));
		}
	}

	/**
	 * Test PHP version against a particular version string.
	 *
	 * @return boolean true if the PHP version is at or later than the version
	 *         specified
	 */
	public function IsPHPAtLeast($s_vers)
	{
		$a_test_version = explode(".",$s_vers);
		if (count($a_test_version) < 3) {
			return (false);
		}
		return ($this->_sPHPVersionString[0] > $a_test_version[0] || ($this->_sPHPVersionString[0] ==
		                                                              $a_test_version[0] &&
		                                                              ($this->_sPHPVersionString[1] >
		                                                               $a_test_version[1] ||
		                                                               $this->_sPHPVersionString[1] ==
		                                                               $a_test_version[1] &&
		                                                               $this->_sPHPVersionString[2] >=
		                                                               $a_test_version[2])));
	}

	public function GetScript()
	{
		if (!isset($this->_sScript)) {
			if (isset($_SERVER["PHP_SELF"]) &&
			    !empty($_SERVER["PHP_SELF"]) &&
			    isset($_SERVER["SERVER_NAME"]) &&
			    !empty($_SERVER["SERVER_NAME"])
			) {
				if (isset($_SERVER["SERVER_PORT"]) &&
				    $_SERVER["SERVER_PORT"] != 80
				) {
					if ($_SERVER["SERVER_PORT"] == 443) // SSL port
						//
						// just use https prefix
						//
					{
						$this->_sScript = "https://" . $_SERVER["SERVER_NAME"] .
						                  $_SERVER["PHP_SELF"];
					} else
						//
						// use http with port number
						//
					{
						$this->_sScript = "http://" . $_SERVER["SERVER_NAME"] .
						                  ":" . $_SERVER["SERVER_PORT"] .
						                  $_SERVER["PHP_SELF"];
					}
				} else {
					$this->_sScript = "http://" . $_SERVER["SERVER_NAME"] .
					                  $_SERVER["PHP_SELF"];
				}
			} else {
				Error("no_php_self",GetMessage(MSG_NO_PHP_SELF),false,false);
			}
		}
		if ($b_with_qry) {
			return ($this->_sScript . (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ?
					'?' . $_SERVER['QUERY_STRING'] : ''));
		} else {
			return ($this->_sScript);
		}
	}

	/**
	 * Get a boolean PHP setting.
	 *
	 * @param string $s_name the name of the setting
	 *
	 * @return bool|null    the value of the setting
	 */
	public function getINIBool($s_name)
	{
		$m_val = ini_get($s_name);
		if ($m_val !== null) {
			if (is_numeric($m_val)) {
				$m_val = (int)$m_val;
			} elseif (is_string($m_val)) {
				$m_val = strtolower($m_val);
				switch ($m_val) {
					case "1":
					case "on":
					case "true":
						$m_val = true;
						break;
					default:
						$m_val = false;
						break;
				}
			}
		}
		return ($m_val);
	}

	/**
	 * Return whether the session can be passed in a URL.
	 *
	 * @return bool true if the session can be passed in a URL
	 */
	public function allowSessionURL()
	{
		$m_only_cookies = $this->getINIBool('session.use_only_cookies');
		if ($m_only_cookies === null) {
			$m_only_cookies = $ExecEnv->IsPHPAtLeast('5.3.0') ? true : false;
		}
		return (!$m_only_cookies);
	}
}

$ExecEnv = new ExecEnv();
if (!$ExecEnv->IsPHPAtLeast("5.3.0")) {
	//
	// disable this silly setting (usually not enabled)
	// it's also deprecated from PHP version 5.3.0
	//
	@set_magic_quotes_runtime(0);
}

//
// We set references to the appropriate arrays to handle PHP version differences
// Session vars are selected after we start the session.
//
$aServerVars = &$_SERVER;
$aGetVars    = &$_GET;
$aFormVars   = &$_POST;
$aFileVars   = &$_FILES;
$aEnvVars    = &$_ENV;

$bIsGetMethod = false;
$bHasGetData  = false;

if (!isset($REAL_DOCUMENT_ROOT)) {
	SetRealDocumentRoot();
}

if (isset($aServerVars['SERVER_PORT'])) {
	$SCHEME = ($aServerVars['SERVER_PORT'] == 80) ? "http://" : "https://";
} else {
	$SCHEME = "";
}
if (isset($aServerVars['SERVER_NAME']) && $aServerVars['SERVER_NAME'] !== "") {
	$SERVER = $aServerVars['SERVER_NAME'];
} elseif (isset($aServerVars['SERVER_ADDR']) && $aServerVars['SERVER_ADDR'] !== "") {
	$SERVER = $aServerVars['SERVER_ADDR'];
} else {
	$SERVER = "";
}

/*
 * Load an optional include file before the configuration.
 * You can use this to set variables that can be used in the
 * configuration section.
 */
@include("formmail-preconfig.inc.php");

/*****************************************************************************/
/* CONFIGURATION (do not alter this line in any way!!!)                      */
/*****************************************************************************
 * This is the *only* place where you need to modify things to use formmail.php
 * on your particular system.  This section finishes at "END OF CONFIGURATION".
 * Help for all settings can be found on our website:
 *  http://www.tectite.com/fmdoc/index.php
 *
 * Also, above each setting is a direct URL to the help information for the
 * setting.
 *****************************************************************************/

/* Help: http://www.tectite.com/fmdoc/email_name.php */
$EMAIL_NAME = "^[-a-z0-9._]+"; /* the '^' is an important security feature! */

/* Help: http://www.tectite.com/fmdoc/target_email.php */
$TARGET_EMAIL = array($EMAIL_NAME . "@yourhost\.com$");

/* Help: http://www.tectite.com/fmdoc/def_alert.php */
$DEF_ALERT = "";

/* Help: http://www.tectite.com/fmdoc/site_domain.php */
$SITE_DOMAIN = ""; /* your website domain name */

/* Help: http://www.tectite.com/fmdoc/set_real_document_root.php */
$SET_REAL_DOCUMENT_ROOT = ""; /* overrides the value set by SetRealDocumentRoot function */

//
// override $REAL_DOCUMENT_ROOT from the $SET_REAL_DOCUMENT_ROOT value (if any)
// Do not alter the following code (next 3 lines)!
//
if (isset($SET_REAL_DOCUMENT_ROOT) && $SET_REAL_DOCUMENT_ROOT !== "") {
	$REAL_DOCUMENT_ROOT = $SET_REAL_DOCUMENT_ROOT;
}

/* Help: http://www.tectite.com/fmdoc/config_check.php */
$CONFIG_CHECK = array("TARGET_EMAIL");

/* Help: http://www.tectite.com/fmdoc/at_mangle.php */
$AT_MANGLE = "";

/* Help: http://www.tectite.com/fmdoc/target_urls.php */
$TARGET_URLS = array(); /* default; no URLs allowed */

/* Help: http://www.tectite.com/fmdoc/head_crlf.php */
$HEAD_CRLF = "\r\n";

/* Help: http://www.tectite.com/fmdoc/body_lf.php */
$BODY_LF = "\r\n"; /* the new default: use this for CR+LF */
//$BODY_LF = "\n";       /* the old default: just LF */

/* Help: http://www.tectite.com/fmdoc/from_user.php */
$FROM_USER = ""; /* the default - setting not used */

/* Help: http://www.tectite.com/fmdoc/sendmail_f_option.php */
$SENDMAIL_F_OPTION      = false;
$SENDMAIL_F_OPTION_LINE = __LINE__ - 1; /* don't modify this line! */

/* Help: http://www.tectite.com/fmdoc/fixed_sender.php */
$FIXED_SENDER = "";

/* Help: http://www.tectite.com/fmdoc/set_sender_from_email.php */
$SET_SENDER_FROM_EMAIL = false;

/* Help: http://www.tectite.com/fmdoc/ini_set_from.php */
$INI_SET_FROM = false;

/* Help: http://www.tectite.com/fmdoc/logdir.php */
$LOGDIR = ""; /* directory for log files; empty string to disallow log files */

/* Help: http://www.tectite.com/fmdoc/autorespondlog.php */
$AUTORESPONDLOG = ""; /* file name in $LOGDIR for the auto responder log; empty string for no auto responder log */

/* Help: http://www.tectite.com/fmdoc/csv_file_settings.php */
$CSVDIR    = ""; /* directory for csv files; empty string to disallow csv files */
$CSVSEP    = ","; /* comma separator between fields (columns) */
$CSVINTSEP = ";"; /* semicolon is the separator for fields (columns) with multiple values (checkboxes, etc.) */
$CSVQUOTE  = '"'; /* all fields in the CSV are quoted with this character; default is double quote.  You can change it to single quote or leave it empty for no quotes. */
//$CSVQUOTE = "'";		/* use this if you want single quotes */
$CSVOPEN = ""; /* set to "b" to force line terminations to be kept as $CSVLINE setting below, regardless of operating
	                    system.  Keep as empty string and leave $CSVLINE unchanged, to get text file
	                    terminations for your server's operating system. (Line feed on UNIX, carriage-return line feed on Windows). */
$CSVLINE = "\n"; /* line termination for CSV files.  The default is a single line feed, which may be modified for your
						server's operating system.  If you want to change
	                    this value, you *must* set $CSVOPEN = "b". */

/* Help: http://www.tectite.com/fmdoc/templatedir.php */
$TEMPLATEDIR = ""; /* directory for template files; empty string if you don't have any templates */

/* Help: http://www.tectite.com/fmdoc/templateurl.php */
$TEMPLATEURL = ""; /* default; no template URL */

/* Help: http://www.tectite.com/fmdoc/multiformdir.php */
$MULTIFORMDIR = ""; /* directory for multi-form template files; empty string if you're not using multi-forms */

/* Help: http://www.tectite.com/fmdoc/multiformurl.php */
$MULTIFORMURL = ""; /* default; no multi-forms templates URL */

/* Help: http://www.tectite.com/fmdoc/text_subs.php */
$TEXT_SUBS = array(
	array("srch" => "/\\\\r\\\\n/","repl" => "\r\n",),
	array("srch" => "/\\\\n/","repl" => "\n",),
	array("srch" => "/\\\\t/","repl" => "\t",),
	array("srch" => "/\\[NL\\]/","repl" => "\n",),
	array("srch" => "/\\[TAB\\]/","repl" => "\t",),
	array("srch" => "/\\[NBSP\\]/","repl" => "&nbsp;",),
	array("srch" => "/\\[DQUOT\\]/","repl" => '"',),
	array("srch" => "/\\[SQUOT\\]/","repl" => "'",),
	array("srch" => "/\\[COLON\\]/","repl" => ":",),
	array("srch" => "/\\[SLOSH\\]/","repl" => "\\",),
	array("srch" => "/\\[OPCURL\\]/","repl" => "{",),
	array("srch" => "/\\[CLCURL\\]/","repl" => "}",),
	array("srch" => "/(on[a-z]*|href|src)\\s*=\\s*/i","repl" => ""), /* strip html attributes that could be unsafe */
	array("srch" => "/<\\s*(table|tr|td|th|p|ul|ol|li|b|i|u|strong|pre|h[1-6]|em|dl|dd|dt|hr|span|br)(\\b[^>]*?)>/i",
	      "repl" => "<\$1\$2>",
	),
	array("srch" => "#<\\s*/\\s*(table|tr|td|th|p|ul|ol|li|b|i|u|strong|pre|h[1-6]|em|dl|dd|dt|hr|span|br)\\s*>#i",
	      "repl" => "</\$1>",
	),
);

/* Help: http://www.tectite.com/fmdoc/authentication_settings.php */
$AUTHENTICATE = "";
//$AUTHENTICATE = "Basic cnVzc2VsbHI6dGVzdA==";        // example
$AUTH_USER = "";
$AUTH_PW   = "";

/* Help: http://www.tectite.com/fmdoc/form_ini_file.php */
$FORM_INI_FILE = "";

/* Help: http://www.tectite.com/fmdoc/moduledir.php */
$MODULEDIR = ".";

/* Help: http://www.tectite.com/fmdoc/fmcompute.php */
$FMCOMPUTE = "fmcompute.php";

/* Help: http://www.tectite.com/fmdoc/fmgeoip.php */
$FMGEOIP = "fmgeoip.php";

/* Help: http://www.tectite.com/fmdoc/advanced_templates.php */
$ADVANCED_TEMPLATES = false; /* set to true for advanced templates */

/* Help: http://www.tectite.com/fmdoc/limited_import.php */
$LIMITED_IMPORT = true; /* set to true if your database cannot handle escaped quotes or newlines within imported data.  Microsoft Access is one example. */

/* Help: http://www.tectite.com/fmdoc/valid_env.php */
$VALID_ENV = array('HTTP_REFERER','REMOTE_HOST','REMOTE_ADDR','REMOTE_USER',
                   'HTTP_USER_AGENT'
);

/* Help: http://www.tectite.com/fmdoc/fileuploads.php */
$FILEUPLOADS = false; /* set to true to allow file attachments */

/* Help: http://www.tectite.com/fmdoc/max_file_upload_size.php */
$MAX_FILE_UPLOAD_SIZE = 0; /* default of 0 means that other software */
// controls the maximum file upload size
// (FormMail doesn't test the file size)

/* Help: http://www.tectite.com/fmdoc/file_repository.php */
$FILE_REPOSITORY = "";

/* Help: http://www.tectite.com/fmdoc/file_mode.php */
$FILE_MODE = 0664; /* always precede with 0 to specify octal! */

/* Help: http://www.tectite.com/fmdoc/file_overwrite.php */
$FILE_OVERWRITE = true;

/* Help: http://www.tectite.com/fmdoc/next_num_file.php */
$NEXT_NUM_FILE = "";

/* Help: http://www.tectite.com/fmdoc/put_data_in_url.php */
$PUT_DATA_IN_URL = true; /* set to true to place data in the URL */
// for bad_url redirects

/* Help: http://www.tectite.com/fmdoc/allow_get_method.php */
$ALLOW_GET_METHOD = false;

/* Help: http://www.tectite.com/fmdoc/db_see_input.php */
$DB_SEE_INPUT = false; /* set to true to just see the input values */

/* Help: http://www.tectite.com/fmdoc/db_see_ini.php */
$DB_SEE_INI = false; /* set to true to just see the ini file */

/* Help: http://www.tectite.com/fmdoc/maxstring.php */
$MAXSTRING = 1024; /* maximum string length for a value */

/* Help: http://www.tectite.com/fmdoc/require_captcha.php */
$REQUIRE_CAPTCHA = ""; /* set to a message string if your forms */
// must provide a CAPTCHA string

/* Help: http://www.tectite.com/fmdoc/recaptcha_private_key.php */
$RECAPTCHA_PRIVATE_KEY = "";

/* Help: http://www.tectite.com/fmdoc/bshowmesgnumbers.php */
$bShowMesgNumbers = false;

/* Help: http://www.tectite.com/fmdoc/filters.php */
/* Note for Tectite personnel: the upgrade Wizard will merge new values
 * but be careful of $var usage and quoting in new entries.
 */
$FILTERS = array("encode" => "$REAL_DOCUMENT_ROOT/cgi-bin/fmencoder -kpubkey.txt",
                 "null"   => "null",
                 "csv"    => "csv"
);

/* Help: http://www.tectite.com/fmdoc/socket_filters.php */
$SOCKET_FILTERS = array(
	"httpencode" => array("site"   => "YourSiteHere",
	                      "port"   => 80,
	                      "path"   => "/cgi-bin/fmencoder",
	                      "params" => array(array("name" => "key",
	                                              "file" => "$REAL_DOCUMENT_ROOT/cgi-bin/pubkey.txt"
	                                        )
	                      )
	),
	"sslencode"  => array("site"   => "ssl://YourSecureSiteHere",
	                      "port"   => 443,
	                      "path"   => "/cgi-bin/fmencoder",
	                      "params" => array(array("name" => "key",
	                                              "file" => "$REAL_DOCUMENT_ROOT/cgi-bin/pubkey.txt"
	                                        )
	                      )
	),
);

/* Help: http://www.tectite.com/fmdoc/filter_attribs.php */
$FILTER_ATTRIBS = array("encode"     => "Strips,MIME=application/vnd.fmencoded,Encrypts",
                        "httpencode" => "Strips,MIME=application/vnd.fmencoded,Encrypts",
                        "sslencode"  => "Strips,MIME=application/vnd.fmencoded,Encrypts",
                        "csv"        => "Strips,MIME=text/csv",
);

/* Help: http://www.tectite.com/fmdoc/check_for_new_version.php */
$CHECK_FOR_NEW_VERSION = true;
$CHECK_DAYS            = 30;

/* Help: http://www.tectite.com/fmdoc/scratch_pad.php */
$SCRATCH_PAD = "";

/* Help: http://www.tectite.com/fmdoc/cleanup_time.php */
$CLEANUP_TIME = 60; /* cleanup time in minutes */

/* Help: http://www.tectite.com/fmdoc/cleanup_chance.php */
$CLEANUP_CHANCE = 20; /* percentage probability that cleanup will be performed */

/* Help: http://www.tectite.com/fmdoc/pear_settings.php */
$PEAR_SMTP_HOST = "";
$PEAR_SMTP_PORT = 25;
$PEAR_SMTP_USER = "";
$PEAR_SMTP_PWD  = "";

/* Help: http://www.tectite.com/fmdoc/alert_on_user_error.php */
$ALERT_ON_USER_ERROR = true;

/* Help: http://www.tectite.com/fmdoc/enable_attack_detection.php */
$ENABLE_ATTACK_DETECTION = true;

/* Help: http://www.tectite.com/fmdoc/attack_detection_url.php */
$ATTACK_DETECTION_URL = "";

/* Help: http://www.tectite.com/fmdoc/alert_on_attack_detection.php */
$ALERT_ON_ATTACK_DETECTION = false;

/* Help: http://www.tectite.com/fmdoc/attack_detection_mime.php */
$ATTACK_DETECTION_MIME = true;

/* Help: http://www.tectite.com/fmdoc/attack_detection_junk.php */
$ATTACK_DETECTION_JUNK                   = false;
$ATTACK_DETECTION_JUNK_CONSONANTS        = "bcdfghjklmnpqrstvwxz";
$ATTACK_DETECTION_JUNK_VOWELS            = "aeiouy";
$ATTACK_DETECTION_JUNK_CONSEC_CONSONANTS = 5;
$ATTACK_DETECTION_JUNK_CONSEC_VOWELS     = 4;
$ATTACK_DETECTION_JUNK_TRIGGER           = 2;
$ATTACK_DETECTION_JUNK_LANG_STRIP        = array(
	"aiia", /* Hawaiian */
	"aeoa", /* palaeoanthropic */
	"aeoe", /* palaeoethnic */
	"ayou", /* layout */
	"ayee", /* payee */
	"buyout", /* buyout */
	"clayey", /* clayey */
	"hooey", /* hooey */
	"ioau", /* radioautograph */
	"opoeia", /* pharmacopoeia, onomatopoeia */
	"ooee", /* cooee */
	"oyee", /* employee */
	"ioau", /* radioautograph */
	"uaia", /* guaiac */
	"uaya", /* uruguayan */
	"ueou", /* aqueous */
	"uiou", /* obsequious */
	"uoya", /* buoyant */
	"queue", /* queue, queueing */
	"earth", /* earthquake, earthslide */
	"cks", /* jockstrap, backscratcher */
	"ngth", /* strengths, length */
	"ndths", /* thousandths */
	"ght", /* nightclub, knightsbridge */
	"phth", /* ophthalmology */
	"sch", /* rothschild */
	"shch", /* borshch */
	"scr", /* corkscrew */
	"spr", /* wingspread, offspring */
	"str", /* armstrong, songstress */
	"sts", /* bursts, postscript */
	"tch", /* catchphrase, scratchproof */
	"thst", /* northstar, birthstone */
	"http", /* https, http */
	"html", /* HTML, XHTML */
);
$ATTACK_DETECTION_JUNK_IGNORE_FIELDS     = array();

/* Help: http://www.tectite.com/fmdoc/attack_detection_dups.php */
$ATTACK_DETECTION_DUPS = array("realname","address1","address2","country","zip",
                               "phone","postcode","state","email"
);

/* Help: http://www.tectite.com/fmdoc/attack_detection_specials.php */
$ATTACK_DETECTION_SPECIALS = true;

/* Help: http://www.tectite.com/fmdoc/attack_detection_specials.php */
$ATTACK_DETECTION_SPECIALS_ONLY_EMAIL = array("derive_fields","required",
                                              "mail_options","good_url","bad_url","good_template",
                                              "bad_template"
);

/* Help: http://www.tectite.com/fmdoc/attack_detection_specials.php */
$ATTACK_DETECTION_SPECIALS_ANY_EMAIL = array("subject");

/* Help: http://www.tectite.com/fmdoc/attack_detection_many_urls.php */
$ATTACK_DETECTION_MANY_URLS = 0;

/* Help: http://www.tectite.com/fmdoc/attack_detection_many_url_fields.php */
$ATTACK_DETECTION_MANY_URL_FIELDS = 0;

/* Help: http://www.tectite.com/fmdoc/attack_detection_url_patterns.php */
$ATTACK_DETECTION_URL_PATTERNS = array(
	'(^|[^-a-z_.0-9]+)(?<!@)([-a-z0-9]+\.)+(com|org|net|biz|info|name|pro|tel|asia|cat)\b',
	'(^|[^-a-z_.0-9]+)(?<!@)([-a-z0-9]+\.)+(com{0,1}|org|net)\.[a-z][a-z]\b'
);

/* Help: http://www.tectite.com/fmdoc/attack_detection_ignore_errors.php */
$ATTACK_DETECTION_IGNORE_ERRORS = false;

/* Help: http://www.tectite.com/fmdoc/attack_detection_reverse_captcha.php */
$ATTACK_DETECTION_REVERSE_CAPTCHA = array();

/* Help: http://www.tectite.com/fmdoc/geoip_lic.php */
$GEOIP_LIC = ""; /* default - no GeoIP */

/* Help: http://www.tectite.com/fmdoc/zero_is_empty.php */
$ZERO_IS_EMPTY = false;

/* Help: http://www.tectite.com/fmdoc/session_name.php */
$SESSION_NAME = "";

/* Help: http://www.tectite.com/fmdoc/session_access.php */
$SESSION_ACCESS = array();

/* Help: http://www.tectite.com/fmdoc/destroy_session.php */
$DESTROY_SESSION = true;

/* Help: http://www.tectite.com/fmdoc/hook_dir.php */
$HOOK_DIR = "";

/* UPGRADE CONTROL
**
** FILTERS:lt:8.04:merge:The FILTERS configuration has
** been modified to include some new standard filters.:
**
** FILTER_ATTRIBS:lt:8.04:no_keep:The FILTER_ATTRIBS configuration has
** been modified to include new information about the standard filters.:
**
** ATTACK_DETECTION_URL_PATTERNS:eq:8.02:no_keep:The ATTACK_DETECTION_URL_PATTERNS
** configuration has been modified to fix a bug.:
**
** FILTER_ATTRIBS:lt:4.00:no_keep:The FILTER_ATTRIBS configuration has
** been modified to include new information about the standard filters.:
**
** SET_REAL_DOCUMENT_ROOT:gt:4.07:copy_from=REAL_DOCUMENT_ROOT:The
** REAL_DOCUMENT_ROOT configuration has been renamed to SET_REAL_DOCUMENT_ROOT.:
**
** EMAIL_NAME:lt:6.01:no_keep:The EMAIL_NAME configuration has
** been modified to match hyphens ('-') in email addresses.:
**
** ZERO_IS_EMPTY:le:6.01:set_to=true:ZERO_IS_EMPTY has been
** set to a value that duplicates previous behaviour.:
**
** TEXT_SUBS:lt:8.30:no_keep:The TEXT_SUBS configuration has
** been modified to be secure with new features released in this version.:
**
** EMAIL_NAME:lt:9.08:no_keep:The EMAIL_NAME configuration has
** been modified to match underscores ('_') in email addresses.:
** END OF CONTROL
*/

/*****************************************************************************/
/* END OF CONFIGURATION (do not alter this line in any way!!!)               */
/*****************************************************************************/

/**
 * Class to access and modify configuration settings.
 *
 * @author russellr
 */
class Settings
{
	/**
	 * Check that the configuration setting exists.
	 * If it doesn't exist, then the script dies immediately.
	 *
	 * @param string $s_name the name of the config setting
	 */
	private static function _check($s_name)
	{
		if (!array_key_exists($s_name,$GLOBALS)) {
			echo '<pre>';
			debug_print_backtrace();
			echo '</pre>';
			die("No FormMail setting called '$s_name' exists.");
		}
	}

	/**
	 * Tests if the given config setting is empty.
	 *
	 * @param string $s_name the name of the config setting
	 *
	 * @return boolean          true if the config setting is empty
	 */
	public static function isEmpty($s_name)
	{
		Settings::_check($s_name);
		if (gettype($GLOBALS[$s_name]) == 'string') {
			return ($GLOBALS[$s_name] === '');
		} else {
			return (empty($GLOBALS[$s_name]));
		}
	}

	/**
	 * Returns the value of the given config setting.
	 *
	 * @param string $s_name the name of the config setting
	 *
	 * @return mixed            the value of the config setting
	 */
	public static function get($s_name)
	{
		Settings::_check($s_name);
		return ($GLOBALS[$s_name]);
	}

	/**
	 * Set the value of a config setting.
	 *
	 * @param string $s_name  the name of the config setting
	 * @param mixed  $m_value the value to set for the config setting
	 */
	public static function set($s_name,$m_value)
	{
		Settings::_check($s_name);
		if (($s_orig_type = gettype($GLOBALS[$s_name])) != ($s_new_type = gettype($m_value))) {
			echo '<pre>';
			debug_print_backtrace();
			echo '</pre>';
			die("You cannot set FormMail setting '$s_name' to type '$s_new_type'.  It should be type '$s_orig_type'.");
		}
		$GLOBALS[$s_name] = $m_value;
	}
}

/*
 * Load an optional include file after the configuration.
 * You can use this to set variables or make adjustments
 * based on the results of the configuration section.
 */
@include("formmail-preconfigdefs.inc.php");

/*
 * Many configuration settings used to be constants.  However, constants
 * cannot be re-defined in PHP.  This limitation reduced the value
 * of "formmail-postconfig.inc.php" and other hook scripts.
 * Therefore, all configuration settings have been changed to be global
 * variables (no define's).
 * 
 * The following defines are for backward-compatibility with any existing
 * hook scripts that are expecting the old constants.
 */
define("EMAIL_NAME",Settings::get("EMAIL_NAME"));
define("DEF_ALERT",Settings::get("DEF_ALERT"));
define("AT_MANGLE",Settings::get("AT_MANGLE"));
define("HEAD_CRLF",Settings::get("HEAD_CRLF"));
define("BODY_LF",Settings::get("BODY_LF"));
define("SENDMAIL_F_OPTION",Settings::get("SENDMAIL_F_OPTION"));
define("SENDMAIL_F_OPTION_LINE",Settings::get("SENDMAIL_F_OPTION_LINE"));
define("SET_SENDER_FROM_EMAIL",Settings::get("SET_SENDER_FROM_EMAIL"));
define("INI_SET_FROM",Settings::get("INI_SET_FROM"));
define("ADVANCED_TEMPLATES",Settings::get("ADVANCED_TEMPLATES"));
define("LIMITED_IMPORT",Settings::get("LIMITED_IMPORT"));
define("FILEUPLOADS",Settings::get("FILEUPLOADS"));
define("MAX_FILE_UPLOAD_SIZE",Settings::get("MAX_FILE_UPLOAD_SIZE"));
define("FILE_MODE",Settings::get("FILE_MODE"));
define("FILE_OVERWRITE",Settings::get("FILE_OVERWRITE"));
define("PUT_DATA_IN_URL",Settings::get("PUT_DATA_IN_URL"));
define("DB_SEE_INPUT",Settings::get("DB_SEE_INPUT"));
define("DB_SEE_INI",Settings::get("DB_SEE_INI"));
define("MAXSTRING",Settings::get("MAXSTRING"));
define("CHECK_FOR_NEW_VERSION",Settings::get("CHECK_FOR_NEW_VERSION"));
define("CHECK_DAYS",Settings::get("CHECK_DAYS"));
define("ALERT_ON_USER_ERROR",Settings::get("ALERT_ON_USER_ERROR"));
define("ENABLE_ATTACK_DETECTION",Settings::get("ENABLE_ATTACK_DETECTION"));
define("ATTACK_DETECTION_URL",Settings::get("ATTACK_DETECTION_URL"));
define("ALERT_ON_ATTACK_DETECTION",Settings::get("ALERT_ON_ATTACK_DETECTION"));
define("ATTACK_DETECTION_MIME",Settings::get("ATTACK_DETECTION_MIME"));
define("ATTACK_DETECTION_JUNK",Settings::get("ATTACK_DETECTION_JUNK"));
define("ATTACK_DETECTION_JUNK_CONSONANTS",Settings::get("ATTACK_DETECTION_JUNK_CONSONANTS"));
define("ATTACK_DETECTION_JUNK_VOWELS",Settings::get("ATTACK_DETECTION_JUNK_VOWELS"));
define("ATTACK_DETECTION_JUNK_CONSEC_CONSONANTS",Settings::get("ATTACK_DETECTION_JUNK_CONSEC_CONSONANTS"));
define("ATTACK_DETECTION_JUNK_CONSEC_VOWELS",Settings::get("ATTACK_DETECTION_JUNK_CONSEC_VOWELS"));
define("ATTACK_DETECTION_JUNK_TRIGGER",Settings::get("ATTACK_DETECTION_JUNK_TRIGGER"));
define("ATTACK_DETECTION_SPECIALS",Settings::get("ATTACK_DETECTION_SPECIALS"));
define("ATTACK_DETECTION_MANY_URLS",Settings::get("ATTACK_DETECTION_MANY_URLS"));
define("ATTACK_DETECTION_MANY_URL_FIELDS",Settings::get("ATTACK_DETECTION_MANY_URL_FIELDS"));
define("ATTACK_DETECTION_IGNORE_ERRORS",Settings::get("ATTACK_DETECTION_IGNORE_ERRORS"));
define("ZERO_IS_EMPTY",Settings::get("ZERO_IS_EMPTY"));
define("DESTROY_SESSION",Settings::get("DESTROY_SESSION"));

//
// for Ajax allow GET method for cross site JSONP
//
if (IsAjax()) {
	Settings::set('ALLOW_GET_METHOD',true);
}

/*
 * Load an optional include file after the configuration.
 * You can use this to set variables or make adjustments
 * based on the results of the configuration section.
 */
@include("formmail-postconfig.inc.php");

//
// the following constants define all FormMail messages
//
// To re-align comments, use these search and replace patterns:
//      Search                  Replace
//      ^(\S{35,39})\s+//        $1\t//
//      ^(\S{31,35})\s+//        $1\t\t//
//      ^(\S{27,31})\s+//        $1\t\t\t//
//      ^(\S{23,27})\s+//        $1\t\t\t\t//
//      ^(\S{19,23})\s+//        $1\t\t\t\t\t//
//
define('MSG_SCRIPT_VERSION',0); // This script requires at least PHP version...
define('MSG_END_VERS_CHK',1); // If you're happy...
define('MSG_VERS_CHK',2); // A later version of FormMail is available...
define('MSG_CHK_FILE_ERROR',3); // Unable to create check file...
define('MSG_UNK_VALUE_SPEC',4); // derive_fields: unknown value specification...
define('MSG_INV_VALUE_SPEC',5); // derive_fields: invalid value specification...
define('MSG_DERIVED_INVALID',6); // Some derive_fields specifications...
define('MSG_INT_FORM_ERROR',7); // Internal form error...
define('MSG_OPTIONS_INVALID',8); // Some mail_options settings...
define('MSG_PLSWAIT_REDIR',9); // Please wait while you are redirected...
define('MSG_IFNOT_REDIR',10); // If you are not redirected...
define('MSG_PEAR_OBJ',11); // Failed to create PEAR Mail object...
define('MSG_PEAR_ERROR',12); // PEAR Mail error...
define('MSG_NO_FOPT_ADDR',13); // You have specified "SendMailFOption"...
define('MSG_MORE_INFO',14); // More information...
define('MSG_INFO_STOPPED',15); // Extra alert information suppressed...
define('MSG_FM_ALERT',16); // FormMail alert
define('MSG_FM_ERROR',17); // FormMail script error
define('MSG_FM_ERROR_LINE',18); // The following error occurred...
define('MSG_USERDATA_STOPPED',19); // User data suppressed...
define('MSG_FILTERED',20); // This alert has been filtered...
define('MSG_TEMPLATES',21); // You must set either TEMPLATEDIR or TEMPLATEURL...
define('MSG_OPEN_TEMPLATE',22); // Failed to open template...
define('MSG_ERROR_PROC',23); // An error occurred while processing...
define('MSG_ALERT_DONE',24); // Our staff have been alerted...
define('MSG_PLS_CONTACT',25); // Please contact us directly...
define('MSG_APOLOGY',26); // We apologize for any inconvenience...
define('MSG_ABOUT_FORMMAIL',27); // Your form submission was processed by...
define('MSG_PREG_FAILED',28); // preg_match_all failed in FindCRMFields...
define('MSG_URL_INVALID',29); // CRM URL "$URL" is not valid...
define('MSG_URL_OPEN',30); // Failed to open Customer Relationship...
define('MSG_CRM_FAILED',31); // Failure report from CRM...
define('MSG_CRM_FORM_ERROR',32); // Your form submission was not...
define('MSG_OR',33); // "$ITEM1" or "$ITEM2"
define('MSG_NOT_BOTH',34); // not both "$ITEM1" and "$ITEM2"
define('MSG_XOR',35); // "$ITEM1" or "$ITEM2" (but not both)
define('MSG_IS_SAME_AS',36); // "$ITEM1" is the same as "$ITEM2"
define('MSG_IS_NOT_SAME_AS',37); // "$ITEM1" is not the same as "$ITEM2"
define('MSG_REQD_OPER',38); // Operator "$OPER" is not valid for "required"
define('MSG_PAT_FAILED',39); // Pattern operator "$OPER" failed: pattern...
define('MSG_COND_OPER',40); // Operator "$OPER" is not valid...
define('MSG_INV_COND',41); // Invalid "conditions" field...
define('MSG_COND_CHARS',42); // The conditions field "$FLD" is not valid...
define('MSG_COND_INVALID',43); // The conditions field "$FLD" is not valid...
define('MSG_COND_TEST_LONG',44); // Field "$FLD" has too many components...
define('MSG_COND_IF_SHORT',45); // Field "$FLD" has too few components for...
define('MSG_COND_IF_LONG',46); // Field "$FLD" has too many components for...
define('MSG_COND_UNK',47); // Field "$FLD" has an unknown command word...
define('MSG_MISSING',48); // Missing "$ITEM"...
define('MSG_NEED_ARRAY',49); // "$ITEM" must be an array...
define('MSG_SUBM_FAILED',50); // Your form submission has failed...
define('MSG_FILTER_WRONG',51); // Filter "$FILTER" is not properly...
define('MSG_FILTER_CONNECT',52); // Could not connect to site "$SITE"...
define('MSG_FILTER_PARAM',53); // Filter "$FILTER" has invalid parameter...
define('MSG_FILTER_OPEN_FILE',54); // Filter "$FILTER" cannot open file...
define('MSG_FILTER_FILE_ERROR',55); // Filter "$FILTER": read error on file...
define('MSG_FILTER_READ_ERROR',56); // Filter '$filter' failed: read error...
define('MSG_FILTER_NOT_OK',57); // Filter 'FILTER' failed...
define('MSG_FILTER_UNK',58); // Unknown filter...
define('MSG_FILTER_CHDIR',59); // Cannot chdir...
define('MSG_FILTER_NOTFOUND',60); // Cannot execute...
define('MSG_FILTER_ERROR',61); // Filter "$FILTER" failed...
define('MSG_SPARE',62); // this value is now spare
define('MSG_TEMPLATE_ERRORS',63); // Template "$NAME" caused the...
define('MSG_TEMPLATE_FAILED',64); // Failed to process template "$NAME"...
define('MSG_MIME_PREAMBLE',65); // (Your mail reader should not show this...
define('MSG_MIME_HTML',66); // This message has been generated by FormMail...
define('MSG_FILE_OPEN_ERROR',67); // Failed to open file "$NAME"...
define('MSG_ATTACH_DATA',68); // Internal error: AttachFile requires...
define('MSG_PHP_HTML_TEMPLATES',69); // HTMLTemplate option is only ...
define('MSG_PHP_FILE_UPLOADS',70); // For security reasons, file upload...
define('MSG_FILE_UPLOAD',71); // File upload attempt ignored...
define('MSG_FILE_UPLOAD_ATTACK',72); // Possible file upload attack...
define('MSG_PHP_PLAIN_TEMPLATES',73); // PlainTemplate option is only...
define('MSG_ATTACH_NAME',74); // filter_options: Attach must contain a name...
define('MSG_PHP_BCC',75); // Warning: BCC is probably not supported...
define('MSG_CSVCOLUMNS',76); // The "csvcolumns" setting is not...
define('MSG_CSVFILE',77); // The "csvfile" setting is not...
define('MSG_TARG_EMAIL_PAT_START',78); // Warning: Your TARGET_EMAIL pattern...
define('MSG_TARG_EMAIL_PAT_END',79); // Warning: Your TARGET_EMAIL pattern...
define('MSG_CONFIG_WARN',80); // The following potential problems...
define('MSG_PHP_AUTORESP',81); // Autorespond is only supported...
define('MSG_ALERT',82); // This is a test alert message...
define('MSG_NO_DEF_ALERT',83); // No DEF_ALERT value has been set....
define('MSG_TEST_SENT',84); // Test message sent.  Check your email.....
define('MSG_TEST_FAILED',85); // FAILED to send alert message...
define('MSG_NO_DATA_PAGE',86); // This URL is a Form submission program...
define('MSG_REQD_ERROR',87); // The form required some values that you...
define('MSG_COND_ERROR',88); // Some of the values you provided...
define('MSG_CRM_FAILURE',89); // The form submission did not succeed...
define('MSG_FOPTION_WARN',90); // Warning: You've used SendMailFOption in...
define('MSG_NO_ACTIONS',91); // The form has an internal error...
define('MSG_NO_RECIP',92); // The form has an internal error...
define('MSG_INV_EMAIL',93); // Invalid email addresses...
define('MSG_FAILED_SEND',94); // Failed to send email...
define('MSG_ARESP_EMAIL',96); // No "email" field was found. Autorespond...
define('MSG_ARESP_SUBJ',97); // Your form submission...
define('MSG_LOG_NO_VERIMG',98); // No VerifyImgString in session...
define('MSG_ARESP_NO_AUTH',99); // Failed to obtain authorization...
define('MSG_LOG_NO_MATCH',100); // User did not match image...
define('MSG_ARESP_NO_MATCH',101); // Your entry did not match...
define('MSG_LOG_FAILED',102); // Failed
define('MSG_ARESP_FAILED',103); // Autoresponder failed
define('MSG_LOG_OK',104); // OK
define('MSG_THANKS_PAGE',105); // Thanks!  We've received your....
define('MSG_LOAD_MODULE',106); // Cannot load module....
define('MSG_LOAD_FMCOMPUTE',107); // Cannot load FMCompute....
define('MSG_REGISTER_MODULE',108); // Cannot register module....
define('MSG_COMP_PARSE',109); // These parse errors occurred....
define('MSG_COMP_REG_DATA',110); // Failed to register data field....
define('MSG_COMP_ALERT',111); // The following alert messages....
define('MSG_COMP_DEBUG',112); // The following debug messages...
define('MSG_COMP_EXEC',113); // The following errors occurred....
define('MSG_REG_FMCOMPUTE',114); // Cannot register function...
define('MSG_USER_ERRORS',115); // A number of errors occurred...
define('MSG_CALL_PARAM_COUNT',116); // Invalid parameter count...
define('MSG_CALL_UNK_FUNC',117); // Unknown function...
define('MSG_SAVE_FILE',118); // Failed to save file....
define('MSG_CHMOD',119); // Failed to chmod file....
define('MSG_VERIFY_MISSING',120); // Image verification string missing...
define('MSG_VERIFY_MATCH',121); // Your entry did not match...
define('MSG_FILE_NAMES_INVALID',122); // Some file_names specifications...
define('MSG_FILE_NAMES_NOT_FILE',123); // Your file_names specification...
define('MSG_TEMPL_ALERT',124); // The following alert messages....
define('MSG_TEMPL_DEBUG',125); // The following debug messages...
define('MSG_TEMPL_PROC',126); // The following errors occurred....
define('MSG_SAVE_FILE_EXISTS',127); // Cannot save file....
define('MSG_EMPTY_ADDRESSES',128); // $COUNT empty addresses
define('MSG_CALL_INVALID_PARAM',129); // Invalid parameter....
define('MSG_INI_PARSE_WARN',130); // Warning: your INI
define('MSG_INI_PARSE_ERROR',131); // The FormMail INI...
define('MSG_RECAPTCHA_MATCH',132); // reCaptcha verification failed...

define('MSG_AND',133); // "$ITEM1" and "$ITEM2"
define('MSG_NEXT_PLUS_GOOD',134); // The form specifies both next_form and....
define('MSG_MULTIFORM',135); // You must set either MULTIFORMDIR or MULTIFORMURL...
define('MSG_MULTIFORM_FAILED',136); // Failed to process multi-page form template "$NAME"...
define('MSG_NEED_THIS_FORM',137); // Multi-page forms require "this_form" field...
define('MSG_NO_PHP_SELF',138); // PHP on the server is not providing "PHP_SELF"
define('MSG_RETURN_URL_INVALID',139); // Return "$URL" is not valid...
define('MSG_GO_BACK',140); // Cannot 'go back' if not a multi-page form...
define('MSG_OPEN_URL',141); // Cannot open URL...
define('MSG_CANNOT_RETURN',142); // Cannot return to page....
define('MSG_ATTACK_DETECTED',143); // Server attack detected....
define('MSG_ATTACK_PAGE',144); // Your form submission....
define('MSG_ATTACK_MIME_INFO',145); // The field "$FLD" contained...
define('MSG_ATTACK_DUP_INFO',146); // The fields "$FLD1" and...
define('MSG_ATTACK_SPEC_INFO',147); // Special field "$FLD"...
define('MSG_NEED_SCRATCH_PAD',148); // You need to set SCRATCH_PAD...
define('MSG_MULTI_UPLOAD',149); // File upload processing failed during multi-page form processing.
define('MSG_OPEN_SCRATCH_PAD',150); // Cannot open directory...
define('MSG_NO_NEXT_NUM_FILE',151); // You cannot use the %nextnum% feature...
define('MSG_NEXT_NUM_FILE',152); // Cannot process next number...
define('MSG_ATTACK_MANYURL_INFO',153); // Field "$FLD"...
define('MSG_ATTACK_MANYFIELDS_INFO',154); // $NUM fields have URLs....
define('MSG_REV_CAP',155); // ATTACK_DETECTION_REVERSE_CAPTCHA setting....
define('MSG_ATTACK_REV_CAP_INFO',156); // The field "$FLD" contained...
define('MSG_ATTACK_JUNK_INFO',157); // The field "$FLD" contained...
define('MSG_ARESP_EMPTY',158); // The autoresponse...
define('MSG_LOG_RECAPTCHA',159); // reCaptcha process failed...

define('MSG_URL_PARSE',160); // URL parse failed
define('MSG_URL_SCHEME',161); // Unsupported URL scheme...
define('MSG_SOCKET',162); // Socket error ...
define('MSG_GETURL_OPEN',163); // Open URL failed: ...
define('MSG_RESOLVE',164); // Cannot resolve...

define('MSG_FORM_OK',170); // Form Submission Succeeded
define('MSG_FORM_ERROR',171); // Form Submission Error
define('MSG_GET_DISALLOWED',172); // GET method has...
//
// The following are PHP's file upload error messages
//
define('MSG_FILE_UPLOAD_ERR_UNK',180); // Unknown error code.
define('MSG_FILE_UPLOAD_ERR1',181); // The uploaded file exceeds the upload_max_filesize directive in php.ini.
define('MSG_FILE_UPLOAD_ERR2',182); // The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.
define('MSG_FILE_UPLOAD_ERR3',183); // The uploaded file was only partially uploaded.
define('MSG_FILE_UPLOAD_ERR4',184); // No file was uploaded.
define('MSG_FILE_UPLOAD_ERR6',186); // Missing a temporary folder.
define('MSG_FILE_UPLOAD_ERR7',187); // Failed to write file to disk.
define('MSG_FILE_UPLOAD_ERR8',188); // File upload stopped by extension.
define('MSG_FILE_UPLOAD_SIZE',189); // Uploaded file "$NAME" is too big... (not a PHP error code - internal maximum file size error)

//
// following are for derive_fields functions
//
define('MSG_DER_FUNC_ERROR',200); // derive_fields: invalid function....
define('MSG_DER_FUNC_SIZE_FMT',201); // function 'size' requires....
define('MSG_DER_FUNC_IF_FMT',202); // function 'if' requires....
define('MSG_DER_FUNC_NEXTNUM_FMT',203); // function 'nextnum' requires....
define('MSG_DER_FUNC_EXT_FMT',204); // function 'ext' requires....
define('MSG_DER_FUNC1_FMT',205); // function 'FUNC' requires....
define('MSG_DER_FUNC_SUBSTR_FMT',206); // function 'substr' requires....

define('MSG_USER_ATTACK_JUNK',220); // The following input ...
define('MSG_USER_ATTACK_REV_CAP',221); // Your input ...
define('MSG_USER_ATTACK_DUP',222); // You have ...
define('MSG_USER_ATTACK_MANY_URLS',223); // Your input ...
define('MSG_USER_ATTACK_MANY_URL_FIELDS',224); // Your input ...

// <A NAME="MessageNumbers"> Jump to: <A HREF="#BuiltinMessages">

//
// Return true if using the built-in language
//
function IsBuiltInLanguage()
{
	global $sLangID;

	return (strpos($sLangID,"builtin") !== false);
}

$sSavePath  = "";
$bPathSaved = false;
//
// Set include path to include the given directory.
//
function AddIncludePath($s_dir = ".")
{
	global $sSavePath,$bPathSaved;

	$s_path     = ini_get('include_path');
	$i_path_len = strlen($s_path);
	$s_sep      = IsServerWindows() ? ";" : ":"; // get path separator
	//
	// look for it in the include_path
	//
	$b_found = false;
	$i_pos   = 0;
	$i_len   = strlen($s_dir);
	while (!$b_found && ($i_pos = strpos($s_path,$s_dir,$i_pos)) !== false) {
		if ($i_pos == 0) {
			if ($i_len == $i_path_len) {
				$b_found = true;
			} // the path only has $s_dir
			elseif ($s_path[$i_len] == $s_sep) {
				$b_found = true;
			}
		} elseif ($s_path[$i_pos - 1] == $s_sep &&
		          ($i_pos + $i_len == $i_path_len ||
		           $s_path[$i_pos + $i_len] == $s_sep)
		) {
			$b_found = true;
		}
		if (!$b_found) {
			$i_pos++;
		}
	}
	if (!$b_found) {
		//
		// allow multiple calls, but only store the original path once
		//
		if (!$bPathSaved) {
			$sSavePath = $s_path;
		}
		if (empty($s_path)) {
			$s_path = $s_dir;
		} else
			//
			// prepend the directory
			//
		{
			$s_path = $s_dir . $s_sep . $s_path;
		}
		ini_set('include_path',$s_path);
		$bPathSaved = true;
	}
}

//
// Reset the include path after a call to AddIncludePath.
//
function ResetIncludePath()
{
	global $sSavePath,$bPathSaved;

	if ($bPathSaved) {
		ini_set('include_path',$sSavePath);
		$bPathSaved = false;
	}
}

//
// Load a language file
//
function LoadLanguageFile()
{
	global $aMessages,$sLangID,$sHTMLCharSet;

	AddIncludePath();
	if (!@include("language.inc.php")) {
		@include("language.inc");
	}
	ResetIncludePath();
	if (isset($sHTMLCharSet) && $sHTMLCharSet !== "") {
		header("Content-Type: text/html; charset=$sHTMLCharSet");
	}
}

//
// Load the messages array from the default language, and then
// override with an optional language file.
// Note: all messages get the MNUM parameter sent which they can use.
// If they don't use it, the message number is appended.
//
function LoadBuiltinLanguage()
{
	global $aMessages,$sLangID;

	$sLangID = "English (builtin)";
	// MSG_SCRIPT_VERSION is shown if the PHP version is too old to run
	// FormMail
	// Parameters:
	//  $PHPREQ     is the minimum required PHP version
	//  $PHPVERS    is  the version the server currently has installed.
	$aMessages[MSG_SCRIPT_VERSION] = 'This script requires at least PHP version ' .
	                                 '$PHPREQ.  You have PHP version $PHPVERS.';

	// MSG_END_VERS_CHK is sent at the end of an Alert message when
	// FormMail detects that there's a newer version available
	// Parameters: none
	$aMessages[MSG_END_VERS_CHK] = '***************************************************\n' .
	                               'If you are happy with your current version and want\n' .
	                               'to stop these reminders, edit formmail.php and\n' .
	                               'set CHECK_FOR_NEW_VERSION to false.\n' .
	                               '***************************************************\n';

	// MSG_VERS_CHK is sent in an Alert message when
	// FormMail detects that there's a newer version available
	// Parameters:
	//  $TECTITE    the website to go to
	//  $FM_VERS    the current FormMail version
	//  $NEWVERS    the new FormMail version that's available
	$aMessages[MSG_VERS_CHK] = 'A later version of FormMail is available from $TECTITE.\n' .
	                           'You are currently using version $FM_VERS.\n' .
	                           'The new version available is $NEWVERS.\n';

	// MSG_CHK_FILE_ERROR is sent in an Alert message when
	// FormMail cannot create a file to record the time of version check.
	// Parameters:
	//  $FILE   the file name that could not be created
	//  $ERROR  the actual error message
	$aMessages[MSG_CHK_FILE_ERROR] = 'Unable to create check file "$FILE": $ERROR';

	// MSG_UNK_VALUE_SPEC is sent in an Alert message when
	// a form uses an unknown value specification in derive_fields.
	// Parameters:
	//  $SPEC   the unknown value specification
	//  $MSG    additional message
	$aMessages[MSG_UNK_VALUE_SPEC] = 'derive_fields: unknown value specification ' .
	                                 '"$SPEC"$MSG';

	// MSG_INV_VALUE_SPEC is sent in an Alert message when
	// a form uses a value specification in derive_fields that's
	// formatted incorrectly (missing terminating '%')
	// Parameters:
	//  $SPEC   the invalid value specification
	$aMessages[MSG_INV_VALUE_SPEC] = 'derive_fields: invalid value specification ' .
	                                 '"$SPEC" (possibly missing a "%")';

	// MSG_DERIVED_INVALID is sent in an Alert message when
	// a form's derive_fields setting has errors
	// Parameters: none
	// A list of errors is appended on separate lines
	$aMessages[MSG_DERIVED_INVALID] = 'Some derive_fields specifications are invalid $MNUM:\n';

	// MSG_INT_FORM_ERROR is sent in an Alert message and displayed
	// to the form user
	// Parameters: none
	$aMessages[MSG_INT_FORM_ERROR] = 'Internal form error';

	// MSG_OPTIONS_INVALID is sent in an Alert message when
	// a form's options settings are invalid.  This applies to
	// mail_options, filter_options, crm_options, and autorespond
	// Parameters:
	//  $OPT    the name of the options field
	// A list of errors is appended on separate lines
	$aMessages[MSG_OPTIONS_INVALID] = 'Some $OPT settings are undefined $MNUM:\n';

	// MSG_PLSWAIT_REDIR is shown to the user for a redirect
	// with JavaScript
	// Parameters: none
	$aMessages[MSG_PLSWAIT_REDIR] = 'Please wait while you are redirected...';

	// MSG_IFNOT_REDIR is shown to the user for a redirect
	// with JavaScript
	// Parameters:
	//  $URL    the URL to redirect to
	$aMessages[MSG_IFNOT_REDIR] = 'If you are not automatically redirected, ' .
	                              'please <a href="$URL">click here</a>.';

	// MSG_PEAR_OBJ is shown to the user if the PEAR Mail object
	// cannot be created
	// Parameters: none
	$aMessages[MSG_PEAR_OBJ] = 'Failed to create PEAR Mail object';

	// MSG_PEAR_ERROR is sent in an Alert message if the PEAR Mail processing
	// reports an error
	// Parameters:
	//  $MSG    the error message from PEAR
	$aMessages[MSG_PEAR_ERROR] = 'PEAR Mail error: $MSG';

	// MSG_NO_FOPT_ADDR is sent in an Alert message SendMailFOption is
	// specified in the form and no email address has been provided
	// Parameters: none
	$aMessages[MSG_NO_FOPT_ADDR] = 'You have specified "SendMailFOption" in your ' .
	                               'form, but there is no email address to use';

	// MSG_MORE_INFO is sent in an Alert message on a line by itself, just
	// before extra information about the FormMail processing that may have
	// led to the alert message
	// Parameters: none
	$aMessages[MSG_MORE_INFO] = 'More information:';

	// MSG_INFO_STOPPED is sent in an Alert message to say that extra
	// alert information has been suppressed because of potential security
	// problems with showing it.
	// Parameters: none
	$aMessages[MSG_INFO_STOPPED] = '(Extra alert information suppressed for ' .
	                               'security purposes. $MNUM)';

	// MSG_FM_ALERT is sent as the subject line of an Alert message
	// Parameters: none
	$aMessages[MSG_FM_ALERT] = 'FormMail alert';

	// MSG_FM_ERROR is sent as the subject line of an Alert message
	// Parameters: none
	$aMessages[MSG_FM_ERROR] = 'FormMail script error';

	// MSG_FM_ERROR_LINE is sent in an Alert message on a
	// separate line to introduce the actual error message
	// Parameters: none
	$aMessages[MSG_FM_ERROR_LINE] = 'The following error occurred in FormMail $MNUM:';

	// MSG_USERDATA_STOPPED is sent in an Alert message to say that the
	// user's data has been suppressed because of potential security
	// problems with showing it.
	// Parameters: none
	$aMessages[MSG_USERDATA_STOPPED] = '(User data suppressed for security ' .
	                                   'purposes. $MNUM)';

	// MSG_FILTERED is sent in an Alert message to show what filter
	// has been used on the message
	// Parameters:
	//  $FILTER     the name of the filter
	$aMessages[MSG_FILTERED] = 'This alert has been filtered through "$FILTER" ' .
	                           'for security purposes.';

	// MSG_TEMPLATES is sent in an Alert message when a form tries
	// to use a template, but templates have not been configured in
	// formmail.php
	// Parameters: none
	$aMessages[MSG_TEMPLATES] = 'You must set either TEMPLATEDIR or TEMPLATEURL ' .
	                            'in formmail.php before you can specify ' .
	                            'templates in your forms.';

	// MSG_OPEN_TEMPLATE is sent in an Alert message when FormMail cannot
	// open a template file
	// Parameters:
	//  $NAME   the name of the template file
	//  $ERROR  information about the error
	$aMessages[MSG_OPEN_TEMPLATE] = 'Failed to open template "$NAME" $MNUM: $ERROR';

	// MSG_ERROR_PROC is shown to the user as part of an error
	// page.  This message introduces the error.
	// Parameters: none
	$aMessages[MSG_ERROR_PROC] = 'An error occurred while processing the ' .
	                             'form $MNUM.\n\n';

	// MSG_ALERT_DONE is shown to the user as part of an error
	// page if an Alert message has been sent to the website owner.
	// Parameters:
	//  SERVER      the name of the server (website)
	$aMessages[MSG_ALERT_DONE] = 'The staff at $SERVER have been alerted to the error $MNUM.\n';

	// MSG_PLS_CONTACT is shown to the user as part of an error
	// page if an Alert message could *not* be sent to the website owner.
	// Parameters:
	//  SERVER      the name of the server (website)
	$aMessages[MSG_PLS_CONTACT] = 'Please contact us ($SERVER) directly since this form ' .
	                              'is not working $MNUM.\n';

	// MSG_APOLOGY is shown to the user as part of an error
	// page as an apology for a problem with the form.
	// Parameters:
	//  SERVER      the name of the server (website)
	$aMessages[MSG_APOLOGY] = '$SERVER apologizes for any inconvenience this error ' .
	                          'may have caused.';

	// MSG_ABOUT_FORMMAIL is shown to the user at the foot of pages
	// generated by FormMail (e.g. the default "Thanks" page and default
	// error page).
	// Parameters:
	//  $FM_VERS    the FormMail version number
	//  $TECTITE    www.tectite.com
	$aMessages[MSG_ABOUT_FORMMAIL] = 'Your form submission was processed by ' .
	                                 '<a href="http://$TECTITE/">FormMail</a> ' .
	                                 '($FM_VERS), a PHP script available from ' .
	                                 '<a href="http://$TECTITE/">$TECTITE</a>.';

	// MSG_PREG_FAILED is sent in an Alert message if the TectiteCRM
	// system failed to return the expected result.
	// Parameters: none
	$aMessages[MSG_PREG_FAILED] = 'preg_match_all failed in FindCRMFields';

	// MSG_URL_INVALID is sent in an Alert message if the specified
	// URL for TectiteCRM is not valid according to the TARGET_URLS
	// configuration setting
	// Parameters:
	//  $URL        the invalid URL
	$aMessages[MSG_URL_INVALID] = 'The URL "$URL" to access the Customer ' .
	                              'Relationship Management System is not valid ' .
	                              '(see TARGET_URLS in formmail.php)';

	// MSG_URL_OPEN is sent in an Alert message if the specified
	// URL for TectiteCRM cannot be opened
	// Parameters:
	//  $URL    the invalid URL
	//  $ERROR  information about the error
	$aMessages[MSG_URL_OPEN] = 'Failed to open Customer Relationship ' .
	                           'Management System URL "$URL" $MNUM: $ERROR';

	// MSG_CRM_FAILED is sent in an Alert message if the TectiteCRM
	// system doesn't return an OK message
	// Parameters:
	//  $URL    the invalid URL
	//  $MSG    more information
	$aMessages[MSG_CRM_FAILED] = 'Failure report from Customer Relationship ' .
	                             'Management System (url="$URL") $MNUM: $MSG';

	// MSG_CRM_FORM_ERROR is shown to the user if the information
	// passed to TectiteCRM was not accepted
	// Parameters: none
	$aMessages[MSG_CRM_FORM_ERROR] = 'Your form submission was not accepted';

	// MSG_AND is shown to the user; it shows two items separated
	// by "and"
	// Parameters:
	//  $ITEM1      the first item
	//  $ITEM2      the second item
	$aMessages[MSG_AND] = '"$ITEM1" and "$ITEM2"';

	// MSG_OR is shown to the user; it shows two items separated
	// by "or"
	// Parameters:
	//  $ITEM1      the first item
	//  $ITEM2      the second item
	$aMessages[MSG_OR] = '"$ITEM1" or "$ITEM2"';

	// MSG_NOT_BOTH is shown to the user; it shows two items that must
	// be specified together
	// Parameters:
	//  $ITEM1      the first item
	//  $ITEM2      the second item
	$aMessages[MSG_NOT_BOTH] = 'not both "$ITEM1" and "$ITEM2"';

	// MSG_XOR is shown to the user; it shows two items that must
	// not be specified together
	// Parameters:
	//  $ITEM1      the first item
	//  $ITEM2      the second item
	$aMessages[MSG_XOR] = '"$ITEM1" or "$ITEM2" (but not both)';

	// MSG_IS_SAME_AS is shown to the user; it shows two items that must
	// not be the same value
	// Parameters:
	//  $ITEM1      the first item
	//  $ITEM2      the second item
	$aMessages[MSG_IS_SAME_AS] = '"$ITEM1" is the same as "$ITEM2"';

	// MSG_IS_NOT_SAME_AS is shown to the user; it shows two items that must
	// be the same value
	// Parameters:
	//  $ITEM1      the first item
	//  $ITEM2      the second item
	$aMessages[MSG_IS_NOT_SAME_AS] = '"$ITEM1" is not the same as "$ITEM2"';

	// MSG_REQD_OPER is sent in an Alert message when an unknown
	// operator has been used in a "required" specification
	// Parameters:
	//  $OPER       the unknown operator
	$aMessages[MSG_REQD_OPER] = 'Operator "$OPER" is not valid for "required"';

	// MSG_PAT_FAILED is sent in an Alert message when a "conditions" pattern
	// match has not matched anything (this isn't necessarily an error)
	// Parameters:
	//  $OPER       the "conditions" operator
	//  $PAT        the "conditions" pattern
	//  $VALUE      the value that was searched
	$aMessages[MSG_PAT_FAILED] = 'Pattern operator "$OPER" failed: pattern ' .
	                             '"$PAT", value searched was "$VALUE".';

	// MSG_COND_OPER is sent in an Alert message when a "conditions"
	// operator is not value
	// Parameters:
	//  $OPER       the "conditions" operator
	$aMessages[MSG_COND_OPER] = 'Operator "$OPER" is not valid for "conditions"';

	// MSG_INV_COND is sent in an Alert message when a "conditions"
	// field is not valid
	// Parameters:
	//  FLD     the field name
	$aMessages[MSG_INV_COND] = 'Invalid "conditions" field "$FLD" - not a string or array.';

	// MSG_COND_CHARS is sent in an Alert message when a "conditions"
	// field is missing the mandatory first 2 characters (the separators)
	// Parameters:
	//  FLD     the field name
	//  COND    the conditions field value
	$aMessages[MSG_COND_CHARS] = 'The conditions field "$FLD" is not valid. ' .
	                             'You must provide the two separator ' .
	                             'characters at the beginning. You had "$COND".';

	// MSG_COND_INVALID is sent in an Alert message when a "conditions"
	// field has the wrong format
	// Parameters:
	//  FLD     the field name
	//  COND    the conditions field value
	//  SEP     the internal separator character for the field.
	$aMessages[MSG_COND_INVALID] = 'The conditions field "$FLD" is not valid. ' .
	                               'There must be at least 5 components ' .
	                               'separated by "$SEP". Your value was "$COND".';

	// MSG_COND_TEST_LONG is sent in an Alert message when a "conditions"
	// TEST value has too many components
	// Parameters:
	//  FLD     the field name
	//  COND    the conditions field value
	//  SEP     the list separator character for the field.
	$aMessages[MSG_COND_TEST_LONG] = 'Field "$FLD" has too many components for ' .
	                                 'a "TEST" command: "$COND".\nAre you missing ' .
	                                 'a "$SEP"?';

	// MSG_COND_IF_SHORT is sent in an Alert message when a "conditions"
	// IF value has too few components
	// Parameters:
	//  FLD     the field name
	//  COND    the conditions field value
	//  SEP     the internal separator character for the field.
	$aMessages[MSG_COND_IF_SHORT] = 'Field "$FLD" has too few components for ' .
	                                'an "IF" command: "$COND".\nThere must be ' .
	                                'at least 6 components separated by "$SEP"';

	// MSG_COND_IF_LONG is sent in an Alert message when a "conditions"
	// IF value has too many components
	// Parameters:
	//  FLD     the field name
	//  COND    the conditions field value
	//  SEP     the list separator character for the field.
	$aMessages[MSG_COND_IF_LONG] = 'Field "$FLD" has too many components for ' .
	                               'an "IF" command: "$COND".\nAre you missing ' .
	                               'a "$SEP"?';

	// MSG_COND_UNK is sent in an Alert message when a "conditions"
	// value has an unknown command
	// Parameters:
	//  FLD     the field name
	//  COND    the conditions field value
	//  CMD     the unknown command
	$aMessages[MSG_COND_UNK] = 'Field "$FLD" has an unknown command word ' .
	                           '"$CMD": "$COND".';

	// MSG_MISSING is sent in an Alert message when
	// a socket filter is incorrectly defined
	// Parameters:
	//  ITEM    the missing item
	$aMessages[MSG_MISSING] = 'Missing "$ITEM"';

	// MSG_NEED_ARRAY is sent in an Alert message when
	// a socket filter is incorrectly defined
	// Parameters:
	//  ITEM    the item that should be an array
	$aMessages[MSG_NEED_ARRAY] = '"$ITEM" must be an array';

	// MSG_SUBM_FAILED is shown to the user when an internal error
	// as occurred and that error is not to be shown
	// Parameters: none
	$aMessages[MSG_SUBM_FAILED] = 'Your form submission has failed due to ' .
	                              'an error on our server.';

	// MSG_FILTER_WRONG is sent in an Alert message when
	// a socket filter is incorrectly defined
	// Parameters:
	//  FILTER  the filter name
	//  ERRORS  a string containing a list of errors
	$aMessages[MSG_FILTER_WRONG] = 'Filter "$FILTER" is not properly defined: ' .
	                               '$ERRORS';

	// MSG_FILTER_CONNECT is sent in an Alert message when FormMail
	// cannot connect to a socket filter
	// Parameters:
	//  FILTER  the filter name
	//  SITE    the site
	//  ERRNUM  socket error number
	//  ERRSTR  socket error message
	$aMessages[MSG_FILTER_CONNECT] = 'Could not connect to site "$SITE" ' .
	                                 'for filter "$FILTER" ($ERRNUM): $ERRSTR';

	// MSG_FILTER_PARAM is sent in an Alert message when a socket
	// filter has an invalid parameter specification
	// Parameters:
	//  FILTER  the filter name
	//  NUM     parameter number
	//  NAME    parameter name
	$aMessages[MSG_FILTER_PARAM] = 'Filter "$FILTER" has invalid parameter ' .
	                               '#$NUM: no "$NAME"';

	// MSG_FILTER_OPEN_FILE is sent in an Alert message when a socket
	// filter cannot open the required file
	// Parameters:
	//  FILTER  the filter name
	//  FILE    the file that could not be opened
	//  ERROR   the error message
	$aMessages[MSG_FILTER_OPEN_FILE] = 'Filter "$FILTER" cannot open file ' .
	                                   '"$FILE": $ERROR';

	// MSG_FILTER_FILE_ERROR is sent in an Alert message when a socket
	// filter gets an error message during reading a file
	// Parameters:
	//  FILTER  the filter name
	//  FILE    the file that could not be opened
	//  ERROR   the error message
	//  NLINES  the number of lines that were read successfully
	$aMessages[MSG_FILTER_FILE_ERROR] = 'Filter "$FILTER": read error on file ' .
	                                    '"$FILE" after $NLINES lines: $ERROR';

	// MSG_FILTER_READ_ERROR is sent in an Alert message when a socket
	// filter gets an error during reading from the socket
	// Parameters:
	//  FILTER  the filter name
	//  ERROR   the error message
	$aMessages[MSG_FILTER_READ_ERROR] = 'Filter "$FILTER" failed: read error: ' .
	                                    '$ERROR';

	// MSG_FILTER_NOT_OK is sent in an Alert message when a socket
	// filter fails to return the agreed __OK__ indicator
	// Parameters:
	//  FILTER  the filter name
	//  DATA    the data returned from the filter
	$aMessages[MSG_FILTER_NOT_OK] = 'Filter "$FILTER" failed (missing ' .
	                                '__OK__ line): $DATA';

	// MSG_FILTER_UNK is sent in an Alert message
	// when an unknown filter is specified by a form
	// Parameters:
	//  FILTER  the filter name
	$aMessages[MSG_FILTER_UNK] = 'Unknown filter "$FILTER"';

	// MSG_FILTER_CHDIR is sent in an Alert message
	// when FormMail cannot change to the filter's directory
	// Parameters:
	//  FILTER  the filter name
	//  DIR     the directory name
	//  ERROR   an error message from the system
	$aMessages[MSG_FILTER_CHDIR] = 'Cannot chdir to "$DIR" to run filter ' .
	                               '"$FILTER": $ERROR';

	// MSG_FILTER_NOTFOUND is sent in an Alert message
	// when FormMail cannot execute the filter
	// Parameters:
	//  FILTER  the filter name
	//  CMD     the command line being executed
	//  ERROR   an error message from the system
	$aMessages[MSG_FILTER_NOTFOUND] = 'Cannot execute filter "$FILTER" with ' .
	                                  'command "$CMD": $ERROR';

	// MSG_FILTER_ERROR is sent in an Alert message
	// when a filter returns a non-zero status
	// Parameters:
	//  FILTER  the filter name
	//  ERROR   an error message from the system
	//  STATUS  the status return from the command
	$aMessages[MSG_FILTER_ERROR] = 'Filter "$FILTER" failed (status $STATUS): ' .
	                               '$ERROR';

	// MSG_SPARE is a spare message
	$aMessages[MSG_SPARE] = '';

	// MSG_TEMPLATE_ERRORS is sent as part of an Alert message
	// when a template has generated some errors.  The message
	// should end with a new line and the actual errors are
	// output after it.
	// Parameters:
	//  NAME    the template name
	$aMessages[MSG_TEMPLATE_ERRORS] = 'Template "$NAME" caused the ' .
	                                  'following errors $MNUM:\n';

	// MSG_TEMPLATE_FAILED is sent in an Alert message
	// when processing a template has failed.
	// Parameters:
	//  NAME    the template name
	$aMessages[MSG_TEMPLATE_FAILED] = 'Failed to process template "$NAME"';

	// MSG_MIME_PREAMBLE is sent in the preamble of MIME emails
	// Parameters: none
	$aMessages[MSG_MIME_PREAMBLE] = '(Your mail reader should not show this ' .
	                                'text.\nIf it does you may need to ' .
	                                'upgrade to more modern software.)';

	// MSG_MIME_HTML is sent in the preamble of HTML emails
	// Parameters:
	//  NAME    the template name
	$aMessages[MSG_MIME_HTML] = 'This message has been generated by FormMail ' .
	                            'using an HTML template\ncalled "$NAME". The ' .
	                            'raw text of the form results\nhas been ' .
	                            'included below, but your mail reader should ' .
	                            'display the HTML\nversion only (unless it\'s ' .
	                            'not capable of doing so).';

	// MSG_FILE_OPEN_ERROR is sent in an Alert message when FormMail
	// cannot open a file
	// Parameters:
	//  NAME    the file name
	//  TYPE    the type of file
	//  ERROR   the system error message
	$aMessages[MSG_FILE_OPEN_ERROR] = 'Failed to open $TYPE file "$NAME": $ERROR';

	// MSG_ATTACH_DATA is sent in an Alert message when the file
	// attachment through 'data' has gone wrong.
	// Parameters: none
	$aMessages[MSG_ATTACH_DATA] = 'Internal error: AttachFile requires ' .
	                              '"tmp_name" or "data"';

	// MSG_PHP_HTML_TEMPLATES is sent in an Alert message when an
	// HTML template is used but the PHP version is too old. (deprecated)
	// Parameters:
	//  $PHPVERS    the current PHP version
	$aMessages[MSG_PHP_HTML_TEMPLATES] = '';

	// MSG_PHP_FILE_UPLOADS is sent in an Alert message when
	// file upload is used but the PHP version is too old. (deprecated)
	// Parameters:
	//  $PHPVERS    the current PHP version
	$aMessages[MSG_PHP_FILE_UPLOADS] = '';

	// MSG_FILE_UPLOAD is sent in an Alert message when
	// file upload is attempted but FormMail is not configured to allow
	// it
	// Parameters: none
	$aMessages[MSG_FILE_UPLOAD] = 'File upload attempt ignored';

	// MSG_FILE_UPLOAD_ATTACK is sent in an Alert message when
	// possible file upload attack is detected
	// Parameters:
	//  NAME    file name
	//  TEMP    temporary file name
	//  FLD     name of the file upload field
	$aMessages[MSG_FILE_UPLOAD_ATTACK] = 'Possible file upload attack ' .
	                                     'detected: field="$FLD", name="$NAME" ' .
	                                     'temp name="$TEMP"';

	// MSG_PHP_PLAIN_TEMPLATES is sent in an Alert message when a
	// Plain template is used but the PHP version is too old. (deprecated)
	// Parameters:
	//  $PHPVERS    the current PHP version
	$aMessages[MSG_PHP_PLAIN_TEMPLATES] = '';

	// MSG_ATTACH_NAME is sent in an Alert message when a
	// the form uses the Attach feature without specifying a file name
	// Parameters: none
	$aMessages[MSG_ATTACH_NAME] = 'filter_options: Attach must contain a name ' .
	                              '(e.g. Attach=data.txt)';

	// MSG_PHP_BCC is sent in an Alert message when a
	// the form uses the BCC feature and the PHP version may not support it
	// (deprecated)
	// Parameters:
	//  $PHPVERS    the current PHP version
	$aMessages[MSG_PHP_BCC] = '';

	// MSG_CSVCOLUMNS is sent in an Alert message when a csvcolumns field
	// is not correct
	// Parameters:
	//  $VALUE  the csvcolumns field value
	$aMessages[MSG_CSVCOLUMNS] = 'The "csvcolumns" setting is not ' .
	                             'valid: "$VALUE"';

	// MSG_CSVFILE is sent in an Alert message when a csvfile field
	// is not correct
	// Parameters:
	//  $VALUE  the csvfile field value
	$aMessages[MSG_CSVFILE] = 'The "csvfile" setting is not valid: "$VALUE"';

	// MSG_TARG_EMAIL_PAT_START is sent in an Alert message when a
	// $TARGET_EMAIL pattern is insecure because of a missing '^'
	// at the beginning
	// Parameters:
	//  $PAT    the pattern
	$aMessages[MSG_TARG_EMAIL_PAT_START] = 'Warning: Your TARGET_EMAIL pattern ' .
	                                       '"$PAT" is missing a ^ at the ' .
	                                       'beginning.';

	// MSG_TARG_EMAIL_PAT_END is sent in an Alert message when a
	// $TARGET_EMAIL pattern is insecure because of a missing '$'
	// at the end
	// Parameters:
	//  $PAT    the pattern
	$aMessages[MSG_TARG_EMAIL_PAT_END] = 'Warning: Your TARGET_EMAIL pattern ' .
	                                     '"$PAT" is missing a $ at the end.';

	// MSG_CONFIG_WARN is sent in an Alert message when the FormMail
	// configuration may have some problems.  The messages are
	// passed on separate lines, so the line terminations below
	// are important.
	// Parameters:
	//  $MESGS  lines of messages
	$aMessages[MSG_CONFIG_WARN] = 'The following potential problems were found ' .
	                              'in your configuration:\n$MESGS\n\n' .
	                              'These are not necessarily errors, but you ' .
	                              'should review the documentation\n' .
	                              'inside formmail.php.  If you are sure your ' .
	                              'configuration is correct\n' .
	                              'you can disable the above messages by ' .
	                              'changing the CONFIG_CHECK settings.';

	// MSG_PHP_AUTORESP is sent in an Alert message when the PHP version
	// does not support autoresponding (deprecated)
	// Parameters:
	//  $PHPVERS    current PHP version
	$aMessages[MSG_PHP_AUTORESP] = '';

	// MSG_ALERT is the test alert message (formmail.php?testalert=1)
	// Parameters:
	//  $LANG               the language ID
	//  $PHPVERS            PHP version
	//  $FM_VERS            FormMail version
	//  $SERVER             server type
	//  $DOCUMENT_ROOT      PHP's DOCUMENT_ROOT value
	//  $SCRIPT_FILENAME    PHP's SCRIPT_FILENAME value
	//  $PATH_TRANSLATED    PHP's PATH_TRANSLATED value
	//  $REAL_DOCUMENT_ROOT the REAL_DOCUMENT_ROOT value
	$aMessages[MSG_ALERT] = 'This is a test alert message $MNUM\n' .
	                        'Loaded language is $LANG\n' .
	                        'PHP version is $PHPVERS\n' .
	                        'FormMail version is $FM_VERS\n' .
	                        'Server type: $SERVER\n' .
	                        '\n' .
	                        'DOCUMENT_ROOT: $DOCUMENT_ROOT\n' .
	                        'SCRIPT_FILENAME: $SCRIPT_FILENAME\n' .
	                        'PATH_TRANSLATED: $PATH_TRANSLATED\n' .
	                        'REAL_DOCUMENT_ROOT: $REAL_DOCUMENT_ROOT';

	// MSG_NO_DEF_ALERT is displayed if you use the testalert feature
	// and no DEF_ALERT setting has been provided.
	// Parameters: none
	$aMessages[MSG_NO_DEF_ALERT] = 'No DEF_ALERT value has been set.';

	// MSG_TEST_SENT is displayed if when use the testalert feature
	// Parameters: none
	$aMessages[MSG_TEST_SENT] = 'Test message sent.  Check your email.';

	// MSG_TEST_FAILED is displayed if when use the testalert feature
	// and the mail sending fails.
	// Parameters: none
	$aMessages[MSG_TEST_FAILED] = 'FAILED to send alert message.  Check your ' .
	                              'server error logs.';

	// MSG_NO_DATA_PAGE is the page that's displayed if the user
	// just opens the URL to FormMail directly.
	// Parameters: none
	$aMessages[MSG_NO_DATA_PAGE] = 'This URL is a Form submission program.\n' .
	                               'It appears the form is not working ' .
	                               'correctly as there was no data found.\n' .
	                               'You\'re not supposed to browse to this ' .
	                               'URL; it should be accessed from a form.';

	// MSG_REQD_ERROR is displayed to the user as a default error
	// message when they haven't supplied some required fields
	// Parameters: none
	$aMessages[MSG_REQD_ERROR] = 'The form required some values that you ' .
	                             'did not seem to provide.';

	// MSG_COND_ERROR is displayed to the user as a default error
	// message when some form conditions have failed
	// Parameters: none
	$aMessages[MSG_COND_ERROR] = 'Some of the values you provided are not valid.';

	// MSG_CRM_FAILURE is displayed to the user when submission
	// to the CRM has failed.
	// Parameters:
	//      $URL    the URL that was used
	//      $DATA   data returned from the CRM
	$aMessages[MSG_CRM_FAILURE] = 'The form submission did not succeed due to ' .
	                              'a CRM failure. URL was \'$URL\'. ' .
	                              'Returned CRM data:\n$DATA';

	// MSG_FOPTION_WARN is sent in an Alert message when the form
	// uses the superseded SendMailFOption feature
	// Parameters:
	//  $LINE   line number for SENDMAIL_F_OPTION
	$aMessages[MSG_FOPTION_WARN] = 'Warning: You\'ve used SendMailFOption in ' .
	                               '"mail_options" in your form. This has been ' .
	                               'superseded with a configuration setting ' .
	                               'inside formmail.php.  Please update your ' .
	                               'formmail.php configuration (look for ' .
	                               'SENDMAIL_F_OPTION on line $LINE) and set ' .
	                               'it to "true", then remove SendMailFOption ' .
	                               'from your form(s).';

	// MSG_NO_ACTIONS is sent in an Alert message when there is no
	// action to perform or email address to send to
	// Parameters: none
	$aMessages[MSG_NO_ACTIONS] = 'The form has an internal error - no actions ' .
	                             'or recipients were specified.';

	// MSG_NO_RECIP is sent in an Alert message when there are no
	// valid recipients to send to
	// Parameters: none
	$aMessages[MSG_NO_RECIP] = 'The form has an internal error - no valid ' .
	                           'recipients were specified.';

	// MSG_INV_EMAIL is sent in an Alert message when there are errors
	// in the email addresses specified in the form
	// Parameters:
	//  $ERRORS     list of errors
	$aMessages[MSG_INV_EMAIL] = 'Invalid email addresses were specified ' .
	                            'in the form $MNUM:\n$ERRORS';

	// MSG_FAILED_SEND is sent in an Alert message when the mail sending fails.
	// Parameters: none
	$aMessages[MSG_FAILED_SEND] = 'Failed to send email';

	// MSG_ARESP_EMAIL is sent in an Alert message when
	// no email address has been specified for an autoreponse
	// Parameters: none
	$aMessages[MSG_ARESP_EMAIL] = 'No "email" field was found. Autorespond ' .
	                              'requires the submitter\'s email address.';

	// MSG_ARESP_SUBJ is the default subject for the auto response email
	// Parameters: none
	$aMessages[MSG_ARESP_SUBJ] = 'Your form submission';

	// MSG_LOG_NO_VERIMG is written to the auto respond log file
	// if no VerifyImgString session variable was found
	// Parameters: none
	$aMessages[MSG_LOG_NO_VERIMG] = 'No VerifyImgString or turing_string in session, ' .
	                                'no reverse CAPTCHA, no reCaptcha';

	// MSG_ARESP_NO_AUTH is shown to the user
	// if no VerifyImgString session variable was found
	// Parameters: none
	$aMessages[MSG_ARESP_NO_AUTH] = 'Failed to obtain authorization to send ' .
	                                'you email. This is probably a fault on ' .
	                                'the server.';

	// MSG_LOG_NO_MATCH is written to the auto respond log file
	// if the user's entry did not match the image verification
	// Parameters: none
	$aMessages[MSG_LOG_NO_MATCH] = 'User did not match image';

	// MSG_LOG_RECAPTCHA is written to the auto respond log file
	// if the reCaptcha process fails
	// Parameters:
	//  ERR the reCaptcha error code
	$aMessages[MSG_LOG_RECAPTCHA] = 'reCaptcha process failed ($ERR)';

	// MSG_ARESP_NO_MATCH is shown to the user
	// if the user's entry did not match the image verification
	// Parameters: none
	$aMessages[MSG_ARESP_NO_MATCH] = 'Your entry did not match the image';

	// MSG_LOG_FAILED is written to the auto respond log file
	// if the autoresponding failed
	// Parameters: none
	$aMessages[MSG_LOG_FAILED] = 'Failed';

	// MSG_ARESP_FAILED is sent in an Alert message
	// if the autoresponding failed
	// Parameters: none
	$aMessages[MSG_ARESP_FAILED] = 'Autoresponder failed';

	// MSG_LOG_OK is written to the auto respond log file
	// if the autoresponding succeeded
	// Parameters: none
	$aMessages[MSG_LOG_OK] = 'OK';

	// MSG_THANKS_PAGE is the default page that's displayed if the
	// submission is successful
	// Parameters: none
	$aMessages[MSG_THANKS_PAGE] = 'Thanks!  We\'ve received your information ' .
	                              'and, if it\'s appropriate, we\'ll be in ' .
	                              'contact with you soon.';

	// MSG_LOAD_MODULE is sent in an alert message if a module
	// could not be loaded.
	// Parameters:
	//  $FILE    the file name
	//  $ERROR   the error message
	$aMessages[MSG_LOAD_MODULE] = 'Cannot load module from file \'$FILE\': $ERROR';

	// MSG_LOAD_FMCOMPUTE is sent in an alert message if the form
	// specifies at least one "fmcompute" field and the FMCompute
	// module cannot be loaded.
	// Parameters:
	//  $FILE    the file name
	//  $ERROR   the error message
	$aMessages[MSG_LOAD_FMCOMPUTE] = 'Cannot load FMCompute module from file ' .
	                                 '\'$FILE\': $ERROR';

	// MSG_REGISTER_MODULE is sent in an alert message if a module
	// could not register with FMCompute
	// Parameters:
	//  $NAME    the name of the module
	//  $ERROR   the error message
	$aMessages[MSG_REGISTER_MODULE] = 'Cannot register module $NAME with ' .
	                                  'FMCompute: $ERROR';

	// MSG_COMP_PARSE is sent in an alert message if a parse error
	// occurs in an fmcompute field
	// Parameters:
	//  $CODE    the code with an error
	//  $ERRORS  the error messages
	$aMessages[MSG_COMP_PARSE] = 'These parse errors occurred in the following ' .
	                             'code:\n$ERRORS\n$CODE';

	// MSG_COMP_REG_DATA is sent in an alert message if FormMail cannot
	// register a data field with the FMCompute module
	// Parameters:
	//  $NAME    the field name
	//  $ERROR  the error message
	$aMessages[MSG_COMP_REG_DATA] = 'Failed to register data field \'$NAME\': ' .
	                                '$ERROR';

	// MSG_COMP_ALERT is sent in an alert message if the FMCompute
	// module has generated some alert messages.
	// Parameters:
	//  $ALERTS  the alerts
	$aMessages[MSG_COMP_ALERT] = 'The following alert messages were reported ' .
	                             'from the FMCompute module: $ALERTS';

	// MSG_COMP_DEBUG is sent in an alert message if the FMCompute
	// module has generated some debug messages.
	// Parameters:
	//  $DEBUG  the alerts
	$aMessages[MSG_COMP_DEBUG] = 'The following debug messages were reported ' .
	                             'from the FMCompute module: $DEBUG';

	// MSG_COMP_EXEC is sent in an alert message if the FMCompute
	// module has generated some error messages during execution
	// Parameters:
	//  $ERRORS  the errors
	$aMessages[MSG_COMP_EXEC] = 'The following error messages were reported ' .
	                            'from the FMCompute module: $ERRORS';

	// MSG_TEMPL_ALERT is sent in an alert message if Advanced Template
	// Processing has generated some alert messages.
	// Parameters:
	//  $ALERTS  the alerts
	$aMessages[MSG_TEMPL_ALERT] = 'The following alert messages were reported ' .
	                              'from the Advanced Template Processor: $ALERTS';

	// MSG_TEMPL_DEBUG is sent in an alert message if Advanced Template
	// Processing has generated some debug messages.
	// Parameters:
	//  $DEBUG  the alerts
	$aMessages[MSG_TEMPL_DEBUG] = 'The following debug messages were reported ' .
	                              'from the Advanced Template Processor: $DEBUG';

	// MSG_TEMPL_PROC is sent in an alert message if Advanced Template Processing
	// has generated some error messages during processing
	// Parameters:
	//  $ERRORS  the errors
	$aMessages[MSG_TEMPL_PROC] = 'The following error messages were reported ' .
	                             'from the Advanced Template Processor: $ERRORS';

	// MSG_REG_FMCOMPUTE is sent in an Alert message when FormMail
	// cannot register an external function with FMCompute.
	// Parameters:
	//  FUNC    the function that could not be registered
	//  ERROR   the error message
	$aMessages[MSG_REG_FMCOMPUTE] = 'Cannot register function "$FUNC" with ' .
	                                'FMCompute: $ERROR';

	// MSG_USER_ERRORS is shown as part of a user error when an FMCompute
	// has called the "UserError" function one or more times.
	// Parameters:
	//  NONE
	$aMessages[MSG_USER_ERRORS] = 'One or more errors occurred in your form submission';

	// MSG_CALL_PARAM_COUNT is sent in an alert when a call to a FormMail
	// function from FMCompute has the wrong number of parameters
	// Parameters:
	//  FUNC    the function name
	//  COUNT   the actual number of parameters passed
	$aMessages[MSG_CALL_PARAM_COUNT] = 'FMCompute called FormMail function ' .
	                                   '\'$FUNC\' with wrong number of ' .
	                                   'parameters: $COUNT';

	// MSG_CALL_UNK_FUNC is sent in an alert when FMCompute calls an
	// unknown FormMail function
	// Parameters:
	//  FUNC    the function name
	$aMessages[MSG_CALL_UNK_FUNC] = 'FMCompute called unknown FormMail function ' .
	                                '\'$FUNC\'';

	// MSG_SAVE_FILE is sent in an alert when saving a file to
	// the server has failed
	// Parameters:
	//  FILE    the source file name (usually a temporary file name)
	//  DEST    the destination file name
	//  ERR     the error message
	$aMessages[MSG_SAVE_FILE] = 'Failed to save file \'$FILE\' to \'$DEST\': $ERR';

	// MSG_SAVE_FILE_EXISTS is sent as part of an alert when saving a file to
	// the repository ($FILE_REPOSITORY) has failed because the file
	// already exists and FILE_OVERWRITE is set to false.
	// Parameters:
	//  FILE    the destination file name
	$aMessages[MSG_SAVE_FILE_EXISTS] = 'Cannot save file to repository as this would ' .
	                                   'overwrite \'$FILE\' and you have ' .
	                                   'set FILE_OVERWRITE to false.';

	// MSG_EMPTY_ADDRESSES is sent as part of an alert when a number of empty
	// email addresses have been specified in recipients, cc, or bcc
	// *and* there are no valid addresses provided
	// in the list
	// Parameters:
	//  COUNT    the number of empty addresses
	$aMessages[MSG_EMPTY_ADDRESSES] = '$COUNT empty addresses';

	// MSG_CALL_INVALID_PARAM is sent in an alert when a call to a FormMail
	// function from FMCompute has an invalid parameter
	// Parameters:
	//  FUNC    the function name
	//  PARAM   the parameter number
	//  CORRECT information about correct values
	$aMessages[MSG_CALL_INVALID_PARAM] = 'FMCompute called FormMail function ' .
	                                     '\'$FUNC\' with an invalid parameter ' .
	                                     'number $PARAM. Correct values are: $CORRECT';

	// MSG_INI_PARSE_WARN is sent in an alert when the INI file
	// may have a syntax error and cannot be parsed.
	// Parameters:
	//  FILE    the file name
	$aMessages[MSG_INI_PARSE_WARN] = 'Warning: your INI file \'$FILE\' appears ' .
	                                 'to be empty.  This may indicate a syntax error.';

	// MSG_INI_PARSE_ERROR is shown as an error message when the INI file
	// has a syntax error and cannot be parsed.
	// Parameters:
	//  FILE    the file name
	$aMessages[MSG_INI_PARSE_ERROR] = 'The FormMail INI file \'$FILE\' has a syntax error';

	// MSG_CHMOD is sent in an alert when changing the protection
	// mode of a file to has failed
	// Parameters:
	//  FILE    the file name
	//  MODE    the mode
	//  ERR     the error message
	$aMessages[MSG_CHMOD] = 'Failed to change protection mode of file \'$FILE\' ' .
	                        'to $MODE: $ERR';

	// MSG_VERIFY_MISSING is shown to the user image verification string
	// was not found
	// Parameters: none
	$aMessages[MSG_VERIFY_MISSING] = 'Image verification string missing. This' .
	                                 ' is probably a fault on the server.';

	// MSG_VERIFY_MATCH is shown to the user
	// if the user's entry did not match the image verification for the
	// imgverify option
	// Parameters: none
	$aMessages[MSG_VERIFY_MATCH] = 'Your entry did not match the image';

	// MSG_RECAPTCHA_MATCH is shown to the user
	// if using the reCaptcha system and there was an error
	// Parameters:
	//  ERR the error code from the reCaptcha API
	$aMessages[MSG_RECAPTCHA_MATCH] = 'reCaptcha verification failed ($ERR)';

	// MSG_FILE_NAMES_INVALID is sent in an Alert message when
	// a form's file_names setting has errors
	// Parameters: none
	// A list of errors is appended on separate lines
	$aMessages[MSG_FILE_NAMES_INVALID] = 'Some file_names specifications are invalid $MNUM:\n';

	// MSG_FILE_NAMES_NOT_FILE is sent in an Alert message when
	// a form's file_names setting refers to a file field that doesn't
	// exist
	// Parameters:
	//      NAME    the name of the file field that doesn't exist
	$aMessages[MSG_FILE_NAMES_NOT_FILE] = 'Your file_names specification has ' .
	                                      'an error. \'$NAME\' is not the name ' .
	                                      'of a file upload field\n';

	// MSG_NEXT_PLUS_GOOD is sent in an alert message if the form is
	// ambiguous and specifies both "next_form" and "good_url" or
	// "good_template"
	// Parameters:
	//  $WHICH  the "good_" field that was specified
	$aMessages[MSG_NEXT_PLUS_GOOD] = 'The form has specified both "next_form" ' .
	                                 'and "$WHICH" fields - the action to ' .
	                                 'to perform is ambiguous';

	// MSG_MULTIFORM is sent in an Alert message when a form tries
	// to use a multi-form template, but templates have not been configured in
	// formmail.php
	// Parameters: none
	$aMessages[MSG_MULTIFORM] = 'You must set either MULTIFORMDIR or MULTIFORMURL ' .
	                            'in formmail.php before you can use ' .
	                            'multi-page forms.';

	// MSG_MULTIFORM_FAILED is sent in an Alert message
	// when processing a multi-page form template has failed.
	// Parameters:
	//  NAME    the template name
	$aMessages[MSG_MULTIFORM_FAILED] = 'Failed to process multi-page form template "$NAME"';

	// MSG_NEED_THIS_FORM is sent in an Alert message
	// when a multi-page form does not specify the "this_form" field.
	// Parameters:
	//  none
	$aMessages[MSG_NEED_THIS_FORM] = 'Multi-page forms require "this_form" field';

	// MSG_NO_PHP_SELF is sent in an Alert message
	// when FormMail requires the "PHP_SELF" server variable and PHP is not
	// providing it.
	// Parameters:
	//  none
	$aMessages[MSG_NO_PHP_SELF] = 'PHP on the server is not providing "PHP_SELF"';

	// MSG_RETURN_URL_INVALID is sent in an Alert message
	// when "this_form" is not a valid return URL.  This occurs for
	// multi-page forms.
	// Parameters:
	//  URL     the invalid URL
	$aMessages[MSG_RETURN_URL_INVALID] = 'Return URL "$URL" is not valid';

	// MSG_GO_BACK is sent in an Alert message
	// when "multi_go_back" has been submitted but this isn't part of a
	// multi-page form.
	// Parameters:
	//  none
	$aMessages[MSG_GO_BACK] = 'Cannot "go back" if not in a multi-page form ' .
	                          'sequence or at the first page of the form ' .
	                          'sequence';

	// MSG_OPEN_URL is sent in an Alert message when a URL cannot
	// be opened.
	// Parameters:
	//  URL     the invalid URL
	//  ERROR   error message
	$aMessages[MSG_OPEN_URL] = 'Cannot open URL "$URL": $ERROR';

	// MSG_CANNOT_RETURN is sent in an Alert message when an invalid return
	// request is made in a multi-page form sequence.
	// Parameters:
	//  TO          the requested page index
	//  TOPINDEX    the top page index
	$aMessages[MSG_CANNOT_RETURN] = 'Cannot return to page $TO.  The top page ' .
	                                'index is $TOPINDEX';

	// MSG_ATTACK_DETECTED is sent in an Alert message when an attack on
	// the server has been detected
	// Parameters:
	//  ATTACK      name or description of the attack
	//  INFO        more information about the attack
	$aMessages[MSG_ATTACK_DETECTED] = 'Server attack "$ATTACK" detected. ' .
	                                  'Your server is safe as FormMail is ' .
	                                  'invulnerable to this attack.  You can ' .
	                                  'disable these messages by setting ' .
	                                  'ALERT_ON_ATTACK_DETECTION to false ' .
	                                  'in FormMail\'s configuration section.' .
	                                  '\nMore information:\n$INFO';

	// MSG_ATTACK_PAGE is the contents of the browser page displayed to the
	// user when an attack is detected
	// Parameters:
	//  SERVER      the name of the server (website)
	//  USERINFO    details of the error
	$aMessages[MSG_ATTACK_PAGE] = 'Your form submission has been rejected ' .
	                              'as it appears to be an abuse of our server (' .
	                              '$SERVER).<br />' .
	                              'Our supplier of forms processing software has ' .
	                              'provided <a href="http://www.tectite.com/serverabuse.php" ' .
	                              ' target="_blank">more information about this error</a>.<br /><br />' .
	                              '$USERINFO';

	// MSG_ATTACK_MIME_INFO is the contents of the INFO parameter
	// to the MSG_ATTACK_DETECTED message for the MIME attack
	// Parameters:
	//  FLD     name of the field
	//  CONTENT the invalid content found in the field
	$aMessages[MSG_ATTACK_MIME_INFO] = 'The field "$FLD" contained invalid ' .
	                                   'content "$CONTENT"';

	// MSG_ATTACK_DUP_INFO is the contents of the INFO parameter
	// to the MSG_ATTACK_DETECTED message for the Duplicate Data attack
	// Parameters:
	//  FLD1     name of the first field
	//  FLD2     name of the second field
	$aMessages[MSG_ATTACK_DUP_INFO] = 'The fields "$FLD1" and "$FLD2" contained ' .
	                                  'duplicate data';

	// MSG_ATTACK_SPEC_INFO is the contents of the INFO parameter
	// to the MSG_ATTACK_DETECTED message for the Special Field attack
	// Parameters:
	//  FLD     name of the special field
	$aMessages[MSG_ATTACK_SPEC_INFO] = 'Special field "$FLD" contained an email address';

	// MSG_ATTACK_MANYURL_INFO is the contents of the INFO parameter
	// to the MSG_ATTACK_DETECTED message for the Many URLs attack
	// Parameters:
	//  FLD     name of the field
	//  NUM     number of URLs detected
	$aMessages[MSG_ATTACK_MANYURL_INFO] = 'Field "$FLD" contained $NUM URLs';

	// MSG_ATTACK_MANYFIELDS_INFO is the contents of the INFO parameter
	// to the MSG_ATTACK_DETECTED message for the Many Fields with URLs
	// attack
	// Parameters:
	//  NUM     number of fields detected with URLs
	//  FLDS    list of fields with URLs in them
	$aMessages[MSG_ATTACK_MANYFIELDS_INFO] = '$NUM fields contained URLs: $FLDS';

	// MSG_REV_CAP is an error regarding the setting of ATTACK_DETECTION_REVERSE_CAPTCHA
	// Parameters: none
	$aMessages[MSG_REV_CAP] = 'ATTACK_DETECTION_REVERSE_CAPTCHA is not set correctly, ' .
	                          'and will be ignored. Please refer to the documentation ' .
	                          'to make the correct setting.';

	// MSG_ATTACK_REV_CAP_INFO is the contents of the INFO parameter
	// to the MSG_ATTACK_DETECTED message for the Reverse Captcha attack
	// detection
	// Parameters:
	//  FLD     name of the field
	//  CONTENT the invalid content found in the field
	$aMessages[MSG_ATTACK_REV_CAP_INFO] = 'The field "$FLD" contained unexpected ' .
	                                      'content "$CONTENT".';

	// MSG_ATTACK_JUNK_INFO is the contents of the INFO parameter
	// to the MSG_ATTACK_DETECTED message for the JUNK attack
	// Parameters:
	//  FLD     name of the field
	//  JUNK    the junk that was found
	$aMessages[MSG_ATTACK_JUNK_INFO] = 'The field "$FLD" contained junk ' .
	                                   'data "$JUNK"';

	// MSG_ARESP_EMPTY is an alert message sent when
	// an autoresponse template or file is empty
	// Parameters:
	//  TYPE    the type of autoreponse requested
	$aMessages[MSG_ARESP_EMPTY] = 'The autoresponse is empty.  The form ' .
	                              'requested $TYPE';

	// MSG_NEED_SCRATCH_PAD is an alert message when processing requires
	// SCRATCH_PAD configuration for file upload processing.  This occurs
	// when you upload files in pages of a multi page form sequence.
	// Parameters:
	//  none
	$aMessages[MSG_NEED_SCRATCH_PAD] = 'You need to set SCRATCH_PAD in the ' .
	                                   'configuration section to process ' .
	                                   'uploaded files.';

	// MSG_OPEN_SCRATCH_PAD is an alert message when the SCRATCH_PAD
	// directory cannot be opened for cleanup.
	// Parameters:
	//  DIR     name of the directory
	//  ERR     more error information
	$aMessages[MSG_OPEN_SCRATCH_PAD] = 'Cannot open SCRATCH_PAD directory ' .
	                                   '"$DIR".  Open failed: $ERR';

	// MSG_NO_NEXT_NUM_FILE is an alert message when a form tries to
	// use the %nextnum% derivation feature but you haven't
	// setup FormMail's configuration correctly.
	// Parameters:
	//  none
	$aMessages[MSG_NO_NEXT_NUM_FILE] = 'You cannot use the %nextnum% feature: ' .
	                                   'you have not configured NEXT_NUM_FILE';

	// MSG_NEXT_NUM_FILE is an alert message when a form tries to
	// use the %nextnum% derivation feature but the next number file cannot
	// be processed.
	// Parameters:
	//  FILE    name of the file
	//  ACT     action
	//  ERR     more error information
	$aMessages[MSG_NEXT_NUM_FILE] = 'Cannot $ACT next number file ' .
	                                '\'$FILE\': $ERR';

	// MSG_MULTI_UPLOAD is an alert message when processing of uploaded
	// fails during a multi-page form sequence
	// Parameters:
	//  none
	$aMessages[MSG_MULTI_UPLOAD] = 'File upload processing failed during ' .
	                               'multi-page form processing.';

	// MSG_URL_PARSE is an error message when a URL to be opened
	// cannot be parsed
	// Parameters:
	//  none
	$aMessages[MSG_URL_PARSE] = 'Failed to parse URL';

	// MSG_URL_SCHEME is an error message when a URL to be opened
	// has an unsupported "scheme" value
	// Parameters:
	//  SCHEME     the scheme that was seen
	$aMessages[MSG_URL_SCHEME] = 'Unsupported URL scheme "$SCHEME"';

	// MSG_SOCKET is an error message when opening a socket for a URL
	// fails
	// Parameters:
	//  ERRNO     the error code
	//  ERRSTR    the error string
	//  PHPERR    the value of $php_errormsg
	$aMessages[MSG_SOCKET] = 'Socket error $ERRNO: $ERRSTR: $PHPERR';

	// MSG_GETURL_OPEN is an error message when the web server reports
	// a failure on opening a URL
	// Parameters:
	//  STATUS     the HTTP status value (number + string)
	$aMessages[MSG_GETURL_OPEN] = 'Open URL failed: $STATUS URL=$URL';

	// MSG_RESOLVE is an error message when a host name cannot be
	// resolved to an IP address
	// Parameters:
	//  NAME    the host name that could not be resolved
	$aMessages[MSG_RESOLVE] = 'Cannot resolve host name "$NAME"';

	// MSG_FORM_OK is the page title for the default
	// "thank you" page
	// Parameters:
	//  none
	$aMessages[MSG_FORM_OK] = 'Form Submission Succeeded';

	// MSG_FORM_ERROR is the page title for default
	// error pages
	// Parameters:
	//  none
	$aMessages[MSG_FORM_ERROR] = 'Form Submission Error';

	// MSG_GET_DISALLOWED is the message shown when GET method is used
	// but has been disabled.
	// Parameters:
	//  none
	$aMessages[MSG_GET_DISALLOWED] = 'GET method has been disabled.  Forms must use ' .
	                                 'the POST method. Alternatively, reconfigure ' .
	                                 'FormMail to allow the GET method.';

	// MSG_FILE_UPLOAD_ERRn are the error messages corresponding to the
	// PHP file upload error code n.
	// Parameters:
	//  none
	$aMessages[MSG_FILE_UPLOAD_ERR1] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
	$aMessages[MSG_FILE_UPLOAD_ERR2] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.';
	$aMessages[MSG_FILE_UPLOAD_ERR3] = 'The uploaded file was only partially uploaded.';
	$aMessages[MSG_FILE_UPLOAD_ERR4] = 'No file was uploaded.';
	$aMessages[MSG_FILE_UPLOAD_ERR6] = 'Missing a temporary folder.';
	$aMessages[MSG_FILE_UPLOAD_ERR7] = 'Failed to write file to disk.';
	$aMessages[MSG_FILE_UPLOAD_ERR8] = 'File upload stopped by extension.';

	// MSG_FILE_UPLOAD_ERR_UNK is displayed when an unknown error code
	// is provided by PHP for a file upload
	// Parameters:
	//  ERRNO   the error code
	$aMessages[MSG_FILE_UPLOAD_ERR_UNK] = 'Unknown file upload error code $ERRNO';

	// MSG_FILE_UPLOAD_SIZE is displayed when an uploaded file exceeds
	// the configured maximum size
	// Parameters:
	//  NAME   the uploaded file's name
	//  SIZE   the size of the uploaded file
	//  MAX    the maximum size that was exceeded
	$aMessages[MSG_FILE_UPLOAD_SIZE] = 'Uploaded file "$NAME" is too big (' .
	                                   '$SIZE bytes). The maximum permitted ' .
	                                   'size is $MAX kilobytes.';

	// MSG_DER_FUNC_ERROR is sent in an Alert message when
	// a form uses a derive_fields function that's
	// formatted incorrectly
	// Parameters:
	//  $SPEC   the invalid value specification
	//  $MSG    a message describing the error or providing an example
	$aMessages[MSG_DER_FUNC_ERROR] = 'derive_fields: invalid function specification ' .
	                                 '"$SPEC": $MSG';

	// MSG_DER_FUNC_SIZE_FMT describes the right syntax for the "size" function
	// Parameters:
	//  none
	$aMessages[MSG_DER_FUNC_SIZE_FMT] = '"size" function requires this format: ' .
	                                    'size(file_field)';

	// MSG_DER_FUNC_IF_FMT describes the right syntax for the "if" function
	// Parameters:
	//  none
	$aMessages[MSG_DER_FUNC_IF_FMT] = '"if" function requires this format: ' .
	                                  'if(field;spec;spec)';

	// MSG_DER_FUNC_NEXTNUM_FMT describes the right syntax for the "nextnum" function
	// Parameters:
	//  none
	$aMessages[MSG_DER_FUNC_NEXTNUM_FMT] = '"nextnum" function requires this format: ' .
	                                       'nextnum(pad) or nextnum(pad;base).  pad and base ' .
	                                       'must be numbers. base must be 2 to 36 inclusive';

	// MSG_DER_FUNC_EXT_FMT describes the right syntax for the "ext" function
	// Parameters:
	//  none
	$aMessages[MSG_DER_FUNC_EXT_FMT] = '"ext" function requires this format: ' .
	                                   'ext(file_field)';

	// MSG_DER_FUNC1_FMT describes the right syntax for a function
	// requiring one parameter
	// Parameters:
	//  FUNC    name of the function
	$aMessages[MSG_DER_FUNC1_FMT] = '"$FUNC" function requires this format: ' .
	                                '$FUNC(fieldname)';

	// MSG_DER_FUNC_SUBSTR_FMT describes the right syntax for the "substr" function
	// Parameters:
	//  none
	$aMessages[MSG_DER_FUNC_SUBSTR_FMT] = '"substr" function requires this format: ' .
	                                      'substr(fieldname;start) or ' .
	                                      'substr(fieldname;start;length) - ' .
	                                      'start and length must be numbers.';

	// MSG_USER_ATTACK_JUNK is a message shown to the user when a junk
	// attack has been detected
	// Parameters:
	//  INPUT   the data the user input
	$aMessages[MSG_USER_ATTACK_JUNK] = 'The following input looks like a junk attack ' .
	                                   'on our server.  Please avoid scientific ' .
	                                   'or technical terms with long sequences ' .
	                                   'of consonants or vowels: $INPUT';

	// MSG_USER_ATTACK_REV_CAP is a message shown to the user when a reverse
	// captcha attack has been detected
	// Parameters:
	//  none
	$aMessages[MSG_USER_ATTACK_REV_CAP] = 'Your input looks like an automated spambot ' .
	                                      'attacking our server.  Some automatic form ' .
	                                      'fillers can trigger this detection. Try ' .
	                                      'filling in our form manually. If you use the ' .
	                                      'back button to go back, make sure you ' .
	                                      'refresh the page before trying again.';

	// MSG_USER_ATTACK_DUP is a message shown to the user when a duplicate
	// data attack has been detected
	// Parameters:
	//  none
	$aMessages[MSG_USER_ATTACK_DUP] = 'You have input the same information in ' .
	                                  'several fields in the form. Please ' .
	                                  're-submit the form without duplication';

	// MSG_USER_ATTACK_MANY_URLS is a message shown to the user when a many urls
	// attack has been detected
	// Parameters:
	//  none
	$aMessages[MSG_USER_ATTACK_MANY_URLS] = 'Your input includes a number of URLs. ' .
	                                        'This server has been configured to reject ' .
	                                        'form submissions with too many URLs. ' .
	                                        'Please re-submit the form without URLs or ' .
	                                        'with fewer URLs.';

	// MSG_USER_ATTACK_MANY_URL_FIELDS is a message shown to the user when a many urls
	// attack has been detected
	// Parameters:
	//  none
	$aMessages[MSG_USER_ATTACK_MANY_URL_FIELDS] = $aMessages[MSG_USER_ATTACK_MANY_URLS];
} // <A NAME="BuiltinMessages"> Jump to: <A HREF="#MessageNumbers">

//
// If the form submission was using the GET method, switch to the
// GET vars instead of the POST vars
//
if (isset($aServerVars["REQUEST_METHOD"]) && $aServerVars["REQUEST_METHOD"] === "GET") {
	$bIsGetMethod = true;
	if (Settings::get('ALLOW_GET_METHOD')) {
		$aFormVars = &$_GET;
	} elseif (count($_GET) > 0) {
		$bHasGetData = true;
	}
}

//
// Load the default language, and then override with an optional language file.
//
function LoadLanguage()
{
	LoadBuiltinLanguage();
	LoadLanguageFile();
}

//
// To return the value of a string or empty string if not set.
//
function CheckString($ss)
{
	return (isset($ss) ? $ss : "");
}

$aGetMessageSubstituteErrors   = array();
$aGetMessageSubstituteFound    = array();
$bGetMessageSubstituteNoErrors = false;

//
// Worker function for GetMessage's preg_replace_callback calls.
// Returns the value of the matched variable name.
// Variables are searched for in the global $aGetMessageValues.
// If no such variable exists, an empty string is returned and the
// global variable $aGetMessageSubstituteErrors lists the missing names.
//
function GetMessageSubstituteParam($a_matches)
{
	global $aGetMessageValues,$aGetMessageSubstituteErrors;
	global $aGetMessageSubstituteFound,$bGetMessageSubstituteNoErrors;

	$s_name                       = $a_matches[1];
	$aGetMessageSubstituteFound[] = $s_name;
	$s_value                      = "";
	if (isset($aGetMessageValues[$s_name])) {
		$s_value = $aGetMessageValues[$s_name];
	} elseif ($bGetMessageSubstituteNoErrors) {
		$s_value = '$' . $s_name;
	} else {
		$aGetMessageSubstituteErrors[] = $s_name;
	}
	return ($s_value);
}

//
// Returns message text from a message number, with optional parameters.
//
function GetMessage($i_msg_num,$a_params = array(),
                    $b_show_mnum = true,$b_no_errors = false)
{
	global $aMessages,$sLangID;

	if (!isset($aMessages[$i_msg_num])) {
		SendAlert("Unknown Message Number $i_msg_num was used",false,true);
		$s_text = "<UNKNOWN MESSAGE NUMBER>";
	} else {
		$s_text = $aMessages[$i_msg_num];
	}
	$s_mno = Settings::get('bShowMesgNumbers') ? "[M$i_msg_num]" : "";

	$s_orig_text = $s_text;
	//
	// substitute parameters; only works with PHP version 4.0.5 or later
	//
	if (strpos($s_text,'$') !== false) {
		global $aGetMessageValues,$aGetMessageSubstituteErrors;
		global $aGetMessageSubstituteFound,$bGetMessageSubstituteNoErrors;

		$aGetMessageSubstituteErrors   = array();
		$aGetMessageSubstituteFound    = array();
		$aGetMessageValues             = HTMLEntitiesArray($a_params,true);
		$bGetMessageSubstituteNoErrors = $b_no_errors;
		$aGetMessageValues["MNUM"]     = $s_mno; // add the message number
		//
		// search for words in this form:
		//      $word
		// where word begins with an alphabetic character and
		// consists of alphanumeric and underscore
		//
		$s_text = preg_replace_callback('/\$([a-z][a-z0-9_]*)/i',
		                                'GetMessageSubstituteParam',$s_text);
		if (count($aGetMessageSubstituteErrors) > 0) {
			SendAlert("Message Number $i_msg_num ('$s_orig_text') in language $sLangID " .
			          "specified the following unsupported parameters: " .
			          implode(',',$aGetMessageSubstituteErrors));
		}
		if (!in_array("MNUM",$aGetMessageSubstituteFound))
			//
			// append the message number
			//
		{
			$s_text .= $b_show_mnum ? " $s_mno" : "";
		}
	} else
		//
		// append the message number
		//
	{
		$s_text .= $b_show_mnum ? " $s_mno" : "";
	}
	//
	// replace '\n' sequences with new lines
	//
	return (str_replace('\n',"\n",$s_text));
}

//
// Check if the server is Windows
//
function IsServerWindows()
{
	static $bGotAnswer = false;
	static $bAnswer;

	if (!$bGotAnswer) {
		if ((isset($_ENV["OS"]) && stristr($_ENV["OS"],"windows") !== false) ||
		    (isset($_SERVER["PATH"]) && stristr($_SERVER["PATH"],"winnt") !== false) ||
		    (isset($_SERVER["PATH"]) && stristr($_SERVER["PATH"],"windows") !== false) ||
		    (isset($_SERVER["SystemRoot"]) && stristr($_SERVER["SystemRoot"],"winnt") !== false) ||
		    (isset($_ENV["SystemRoot"]) && stristr($_ENV["SystemRoot"],"winnt") !== false) ||
		    (isset($_SERVER["SystemRoot"]) && stristr($_SERVER["SystemRoot"],"windows") !== false) ||
		    (isset($_ENV["SystemRoot"]) && stristr($_ENV["SystemRoot"],"windows") !== false) ||
		    (isset($_SERVER["Path"]) && stristr($_SERVER["Path"],"windows") !== false)
		) {
			$bAnswer = true;
		} else {
			$bAnswer = false;
		}
		$bGotAnswer = true;
	}
	return ($bAnswer);
}

//
// To return a temporary file name from $SCRATCH_PAD
//
function GetScratchPadFile($s_prefix)
{

	switch (substr(Settings::get('SCRATCH_PAD'),-1)) {
		case '/':
		case '\\':
			$s_dir = substr(Settings::get('SCRATCH_PAD'),0,-1);
			break;
		default:
			$s_dir = Settings::get('SCRATCH_PAD');
			break;
	}
	//
	// Ideally, we could use tempnam. But,
	// tempnam is system dependent and might not use the
	// SCRATCH_PAD directory even if we tell it to.
	// So, we'll force the file into SCRATCH_PAD.
	//
	// Note that we do *not* create the file, even though tempnam
	// does create it in PHP version 4.0.3 and above. (The reason is
	// we can't guarantee a non-race condition anyway.)
	//
	do {
		$i_rand = mt_rand(0,16777215); // 16777215 is FFFFFF in hex
		$s_name = $s_dir . "/" . $s_prefix . sprintf("%06X",$i_rand);
	} while (file_exists($s_name));
	return ($s_name);
}

//
// To return a temporary file name.
//
function GetTempName($s_prefix)
{
	if (!Settings::isEmpty('SCRATCH_PAD')) {
		$s_name = GetScratchPadFile($s_prefix);
	} else {
		$s_name = tempnam("/tmp",$s_prefix);
	}
	return ($s_name);
}

//
// To find a directory on the server for temporary files.
//
function GetTempDir()
{
	$s_name = GetTempName("fm");
	if (file_exists($s_name)) {
		unlink($s_name);
	}
	$s_dir = dirname($s_name);
	return ($s_dir);
}

define('DEBUG',false); // for production
//define('DEBUG',true);         // for development and debugging
define('RFCLINELEN',76); // recommend maximum line length from RFC 2822

//
// The user agent string to use when opening URLs
//
$sUserAgent = "FormMail/$FM_VERS (from www.tectite.com)";

if (DEBUG) {
	error_reporting(E_ALL); // trap everything!
	ini_set("display_errors","stdout");
	ini_set("display_startup_errors","1");
	assert_options(ASSERT_ACTIVE,true);
	assert_options(ASSERT_BAIL,true);
	LoadLanguage();
} else {
	$iOldLevel = error_reporting(E_ALL ^ E_WARNING);
	LoadLanguage();
	//
	// report everyting except warnings and notices
	//
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
}

function SetRealDocumentRoot()
{
	global $aServerVars,$REAL_DOCUMENT_ROOT;

	if (isset($aServerVars['SCRIPT_FILENAME'])) {
		$REAL_DOCUMENT_ROOT = $aServerVars['SCRIPT_FILENAME'];
	} elseif (isset($aServerVars['PATH_TRANSLATED'])) {
		$REAL_DOCUMENT_ROOT = $aServerVars['PATH_TRANSLATED'];
	} else {
		$REAL_DOCUMENT_ROOT = "";
	}
	//
	// look for 'www' or 'public_html' and strip back to that if found,
	// otherwise just get the directory name
	//
	if (($i_pos = strpos($REAL_DOCUMENT_ROOT,"/www/")) !== false) {
		$REAL_DOCUMENT_ROOT = substr($REAL_DOCUMENT_ROOT,0,$i_pos + 4);
	} elseif (($i_pos = strpos($REAL_DOCUMENT_ROOT,"/public_html/")) !== false) {
		$REAL_DOCUMENT_ROOT = substr($REAL_DOCUMENT_ROOT,0,$i_pos + 12);
	} elseif (!empty($REAL_DOCUMENT_ROOT)) {
		$REAL_DOCUMENT_ROOT = dirname($REAL_DOCUMENT_ROOT);
	} elseif (isset($aServerVars['DOCUMENT_ROOT']) &&
	          !empty($aServerVars['DOCUMENT_ROOT'])
	) {
		$REAL_DOCUMENT_ROOT = $aServerVars['DOCUMENT_ROOT'];
	}
}

//
// Hook system: before initialization (but after configuration)
//
if (!Settings::isEmpty('HOOK_DIR')) {
	if (!@include(Settings::get('HOOK_DIR') . "/fmhookpreinit.inc.php")) {
		@include(Settings::get('HOOK_DIR') . "/fmhookpreinit.inc");
	}
}

if (!Settings::isEmpty('SESSION_NAME')) {
	session_name(Settings::get('SESSION_NAME'));
}

/**
 * Session data access
 *
 * @return mixed null session variable's value or null if not set
 */
function GetSession($s_name)
{
	return (isset($_SESSION) ? $_SESSION[$s_name] : null);
}

/**
 * Session data isset
 *
 * @return boolean true if the given session variable is set
 */
function IsSetSession($s_name)
{
	return (isset($_SESSION) && isset($_SESSION[$s_name]));
}

/**
 * Session data setting
 *
 * Sets the given session variable to the given value.
 *
 * @param $s_name  string
 *                 name of the session variable
 * @param $m_value mixed
 *                 value to set
 */
function SetSession($s_name,$m_value)
{
	$_SESSION[$s_name] = $m_value;
}

/**
 * Session data un-setting
 *
 * Unsets the given session variable.
 *
 * @param $s_name string
 *                name of the session variable
 */
function UnsetSession($s_name)
{
	$_SESSION[$s_name] = null;
	unset($_SESSION[$s_name]);
}

function ZapSession()
{
	global $aSessionVarNames;

	if (Settings::get('DESTROY_SESSION')) {
		if (session_id() != '') {
			session_destroy();
		}
	} else {
		foreach ($aSessionVarNames as $s_var_name) {
			UnsetSession($s_var_name);
		}
	}
}

$bReverseCaptchaCompleted = false; // records whether ATTACK_DETECTION_REVERSE_CAPTCHA has been completed successfully
session_start();

//
// Hook system: after session initialization
//
if (!Settings::isEmpty('HOOK_DIR')) {
	if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostsess.inc.php")) {
		@include(Settings::get('HOOK_DIR') . "/fmhookpostsess.inc");
	}
}

//
// This array lists the private variables used by FormMail.
// We use the names here to cleanup the session when FormMail has
// finished its processing.
//
$aSessionVarNames = array("FormError","FormErrorInfo","FormErrorCode",
                          "FormErrorItems","FormData","FormIsUserError",
                          "FormAlerted","FormSavedFiles","FormIndex",
                          "FormList","FormKeep","VerifyImgString",
                          "turing_string"
);

UnsetSession("FormError"); // start with no error
UnsetSession("FormErrorInfo"); // start with no error
UnsetSession("FormErrorCode"); // start with no error
UnsetSession("FormErrorItems"); // start with no error
UnsetSession("FormData"); // start with no data
UnsetSession("FormIsUserError"); // start with no data
UnsetSession("FormAlerted"); // start with no data

//
// Note that HTTP_REFERER is easily spoofed, so there's no point in
// using it for security.
//

//
// SPECIAL_FIELDS is the list of fields that formmail.php looks for to
// control its operation
//
$SPECIAL_FIELDS = array(
	"email", // email address of the person who filled in the form
	"realname", // the real name of the person who filled in the form
	"recipients", // comma-separated list of email addresses to which we'll send the results
	"cc", // comma-separated list of email addresses to which we'll CC the results
	"bcc", // comma-separated list of email addresses to which we'll BCC the results
	"replyto", // comma-separated list of email addresses to whom replies should be sent
	"required", // comma-separated list of fields that must be found in the input
	"conditions", // complex condition tests
	"fmcompute", // computations
	"fmmodules", // list of modules required
	"fmmode", // mode of operation
	"mail_options", // comma-separated list of options
	"good_url", // URL to go to on success
	"good_template", // template file to display on success
	"bad_url", // URL to go to on error
	"bad_template", // template file to display on error
	"template_list_sep", // separator when expanding lists in templates
	"this_form", // the URL of the form (can be used by bad_url)
	"subject", // subject for the email
	"env_report", // comma-separated list of environment variables to report
	"filter", // a supported filter to use
	"filter_options", // options for using the filter
	"filter_fields", // list of fields to filter (default is to filter all fields)
	"filter_files", // list of file fields to filter (default is to filter no file fields)
	"logfile", // log file to write to
	"csvfile", // file to write CSV records to
	"csvcolumns", // columns to save in the csvfile
	"crm_url", // URL for sending data to the CRM; note that the
	// value must have a valid prefix specified in TARGET_URLS
	"crm_spec", // CRM specification (field mapping)
	"crm_options", // comma-separated list of options to control CRM processing
	"derive_fields", // a list of fields to derive from other fields
	"file_names", // specifies names for files being uploaded
	"autorespond", // specification for auto-responding
	"arverify", // verification field to allow auto-responding
	"imgverify", // verification field to allow submission
	"multi_start", // set this field on the first page of a multi-page form sequence
	"multi_keep", // set this field on the pages of a multi-page form sequence
	// to the list of fields that should be kept when moving
	// forward after going backwards
	"next_form", // next form name or empty for last form
	"multi_go_back", // this field should be set when the user clicks the
	// back button or link in a multi-page form sequence
	"alert_to", // email address to send alerts (errors) to
	//
	// fields for reCaptcha implementation
	//
	"recaptcha_response_field", // verification field to allow submission
	"recaptcha_challenge_field", // challenge field
	//
	// reCaptcha version 2
	//
	"g-recaptcha-response",
);

//
// $SPECIAL_MULTI is the list of fields from $SPECIAL_FIELDS that can
// have multiple values, for example:
//      name="conditions1"
//      name="conditions2"
//
$SPECIAL_MULTI = array(
	"conditions",
	"fmcompute",
);

//
// $SPECIAL_ARRAYS is the list of fields from $SPECIAL_FIELDS that can
// be submitted as arrays of values, for example:
//      <select name="recipients[]">
//      <option value="sales">Sales</option>
//      <option value="service">Service</option>
//      </select>
//
$SPECIAL_ARRAYS = array(
	"recipients",
	"cc",
	"bcc",
	"replyto",
);

//
// $SPECIAL_NOSTRIP is the list of fields from $SPECIAL_FIELDS that
// should not be stripped (other than for magic_quotes_gpc reasons).
//
$SPECIAL_NOSTRIP = array(
	"conditions",
	"fmcompute",
	"recaptcha_response_field",
	"recaptcha_challenge_field",
);

//
// VALID_MAIL_OPTIONS lists the valid mail_options words
//
$VALID_MAIL_OPTIONS = array(
	"AlwaysEmailFiles" => true,
	"AlwaysList"       => true,
	"CharSet"          => true,
	"DupHeader"        => true,
	"Exclude"          => true,
	"FromAddr"         => true,
	"FromLineStyle"    => true,
	"HTMLTemplate"     => true,
	"KeepLines"        => true,
	"NoEmpty"          => true,
	"NoPlain"          => true,
	"PlainTemplate"    => true,
	"SendMailFOption"  => true,
	"StartLine"        => true,
	"TemplateMissing"  => true,
);

//
// VALID_CRM_OPTIONS lists the valid crm_options words
//
$VALID_CRM_OPTIONS = array(
	"ErrorOnFail" => true,
);

//
// VALID_AR_OPTIONS lists the valid autorespond words
//
$VALID_AR_OPTIONS = array(
	"Subject"         => true,
	"HTMLTemplate"    => true,
	"PlainTemplate"   => true,
	"TemplateMissing" => true,
	"PlainFile"       => true,
	"HTMLFile"        => true,
	"FromAddr"        => true,
);

//
// VALID_FILTER_OPTIONS lists the valid filter_options words
//
$VALID_FILTER_OPTIONS = array(
	"Attach"       => true,
	"KeepInLine"   => true,
	"CSVHeading"   => true,
	"CSVSep"       => true,
	"CSVIntSep"    => true,
	"CSVQuote"     => true,
	"CSVEscPolicy" => true,
	"CSVRaw"       => true,
);

//
// SPECIAL_VALUES is set to the value of the fields we've found
//  usage: $SPECIAL_VALUES["email"] is the value of the email field
//
$SPECIAL_VALUES = array();
//
// Array of mail options; set by the function 'ProcessMailOptions'
//
$MAIL_OPTS = array();
//
// Array of crm options; set by the function 'ProcessCRMOptions'
//
$CRM_OPTS = array();
//
// Array of autorespond options; set by the function 'ProcessAROptions'
//
$AR_OPTS = array();
//
// Array of filter options; set by the function 'ProcessFilterOptions'
//
$FILTER_OPTS = array();

//
// initialise $SPECIAL_VALUES so that we don't fail on using unset values
//
foreach ($SPECIAL_FIELDS as $sFieldName) {
	$SPECIAL_VALUES[$sFieldName] = "";
}

//
// Defaults for some special fields....
//
$SPECIAL_VALUES['template_list_sep'] = ",";

//
// FORMATTED_INPUT contains the input variables formatted nicely
// This is used for error reporting and debugging only.
//
$FORMATTED_INPUT = array();

//
// $FILTER_ATTRIBS_LOOKUP is the parsed $FILTER_ATTRIBS array
//
$FILTER_ATTRIBS_LOOKUP = array();

//
// $EMAIL_ADDRS is the array of email addresses from the $FORM_INI_FILE
//
$EMAIL_ADDRS = array();

//
// BuiltinFunctions provides additional functions that can be called via derive_fields
//
class   BuiltinFunctions
{
	private $_aFunctions;

	function __construct()
	{
		$this->_aFunctions = array();
	}

	/**
	 * Adds a builtin function.
	 *
	 * @param string $s_name   name of the function
	 * @param string $n_params number of parameters the function expects
	 */
	public function Add($s_name,$n_params)
	{
		$this->_aFunctions[$s_name] = array('nparams' => $n_params);
	}

	/**
	 * Calls a builtin function.
	 *
	 * @param string $s_name    name of the function
	 * @param array  $a_params  list of parameters
	 * @param string &$s_result result of the function or error message
	 *
	 * @return boolean true on success, otherwise false
	 */
	public function Call($s_name,$a_params,&$s_result)
	{
		if (!isset($this->_aFunctions[$s_name])) {
			$s_result = "Function '$s_name' is not a builtin function";
			return (false);
		}
		$a_func = $this->_aFunctions[$s_name];
		if (count($a_params) != $a_func['nparams']) {
			$s_result = "Function '$s_name' expects " . $a_func['nparams'] . " parameters, " . count($a_params) .
			            " given.";
			return (false);
		}
		$s_result = call_user_func_array($s_name,$a_params);
		return (true);
	}
}

$BuiltinFunctions = new BuiltinFunctions();

$reCaptchaProcessor = null;
if (Settings::get('RECAPTCHA_PRIVATE_KEY') !== "") {
	//
	// Assume version 1 unless g-recaptcha-response is found in the form data.
	// If we cannot load recaptchalib, then we assume it's version 2.
	//
	$bRecaptchaVersion = 1;
	if (isset($aFormVars['g-recaptcha-response']) && $aFormVars['g-recaptcha-response'] != '') {
		$bRecaptchaVersion = 2;
	}
	if ($bRecaptchaVersion == 1 && !include_once("recaptchalib.php")) {
		$bRecaptchaVersion = 2;
	}

	if ($bRecaptchaVersion == 2) {
		if (!function_exists('json_decode')) {
			SendAlert("reCaptcha version 2 requires PHP version 5.2.0 or later",false,false);
		}

		/*
		 * Class:       reCaptchaWrapper
		 * Description:
			 *  Wraps processing of reCaptcha version 2.
		 */

		class   reCaptchaWrapperV2
		{
			var $_sPrivate; // the private key
			var $_bDone; // true when done
			var $_Resp; // the response from reCaptcha

			/*
			 * Method:      reCaptchaWrapperV2 ctor
			 * Parameters:  $s_priv     the private key
			 * Returns:     n/a
			 * Description:
			 *  Initializes the wrapper ready to process reCaptcha.
			 */
			function    __construct($s_priv)
			{
				$this->_sPrivate = $s_priv;
				$this->_bDone    = false;
			}

			/*
			 * Method:      reCaptchaWrapper::Check
			 * Parameters:  $s_response the reCaptcha response value
			 *              $a_values   field values
			 *              $s_error    returns the reCaptcha error code
			 * Returns:     bool        true on success, otherwise false
			 * Description:
			 *  Performs the reCaptcha check and caches the result so it's
			 *  only done once.
			 */
			function    Check($s_response,$a_values,&$s_error)
			{
				if (!$this->_bDone) {
					$s_url     = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $this->_sPrivate .
					             "&response=$s_response";
					$recaptcha = new HTTPGet($s_url);
					$s_resp    = $recaptcha->Read();
					if ($s_resp === false) {
						$s_resp = '{"success":false,"error_codes":["reCaptcha failed"]}';
					} else {
						$s_resp = implode('',$s_resp);
					}
					$this->_Resp = json_decode($s_resp,true);
				}
				$this->_bDone = true;
				$s_error      = "";
				if (!$this->_Resp['success']) {
					$s_error = $this->_Resp->error_codes[0];
					if (!isset($this->_Resp['error_codes']) || count($this->_Resp['error_codes']) == 0 ||
					    !$this->_Resp['error_codes'][0]
					) {
						$s_error = 'verification failed';
					}
				}
				return ($this->_Resp['success']);
			}
		}
		$reCaptchaProcessor = new reCaptchaWrapperV2(Settings::get('RECAPTCHA_PRIVATE_KEY'));
	} else {

		/*
		 * Class:       reCaptchaWrapper
		 * Description:
		 *  Wraps processing of reCaptcha version 1.
		 */

		class   reCaptchaWrapper
		{
			var $_sPrivate; // the private key
			var $_bDone; // true when done
			var $_Resp; // the response from reCaptcha

			/*
			 * Method:      reCaptchaWrapper ctor
			 * Parameters:  $s_priv     the private key
			 * Returns:     n/a
			 * Description:
			 *  Initializes the wrapper ready to process reCaptcha.
			 */
			function    __construct($s_priv)
			{
				$this->_sPrivate = $s_priv;
				$this->_bDone    = false;
			}

			/*
			 * Method:      reCaptchaWrapper::Check
				 * Parameters:  $s_response the reCaptcha response value
			 *              $a_values   field values
			 *              $s_error    returns the reCaptcha error code
			 * Returns:     bool        true on success, otherwise false
			 * Description:
			 *  Performs the reCaptcha check and caches the result so it's
			 *  only done once.
			 */
			function    Check($s_response,$a_values,&$s_error)
			{
				if (!$this->_bDone) {
					$this->_Resp = recaptcha_check_answer($this->_sPrivate,
					                                      $_SERVER["REMOTE_ADDR"],
					                                      $a_values["recaptcha_challenge_field"],
					                                      $s_response);
				}
				$this->_bDone = true;
				$s_error      = "";
				if (!$this->_Resp->is_valid) {
					$s_error = $this->_Resp->error;
				}
				return ($this->_Resp->is_valid);
			}
		}
		$reCaptchaProcessor = new reCaptchaWrapper(Settings::get('RECAPTCHA_PRIVATE_KEY'));
	}
}

/**
 * Contains a list of valid email addresses and
 * email address patterns. Provides methods for checking the validity of an
 * email address.
 */
class EmailChecker
{

	var $_aAddresses; // valid email addresses (as keys)
	var $_aTargetPatterns; // valid email address patterns

	/**
	 * Constructs the object.
	 *
	 * @param array $a_patterns an array of email address patterns
	 */
	function EmailChecker($a_patterns = array())
	{
		$this->_aAddresses      = array();
		$this->_aTargetPatterns = $a_patterns;
	}

	/**
	 * Adds an email address to the list of valid * email addresses.
	 *
	 * @param string $s_addr an email address
	 */
	function AddAddress($s_addr)
	{
		$this->_aAddresses[$s_addr] = true;
	}

	/**
	 * Adds a comma-separated list of email * addresses to the list of valid email addresses.
	 *
	 * @param string $s_list a list of email addresses
	 */
	function AddAddresses($s_list)
	{
		$a_addrs = TrimArray(explode(",",$s_list));
		foreach ($a_addrs as $s_addr) {
			$this->AddAddress($s_addr);
		}
	}

	/**
	 * Checks an email address for validity.
	 *
	 * @param string $s_email an email address
	 *
	 * @return boolean true if the address is valid, otherwise false
	 */
	function CheckAddress($s_email)
	{
		$b_is_valid = false;
		if (isset($this->_aAddresses[$s_email])) {
			$b_is_valid = true;
		} else {
			for ($ii = 0 ; $ii < count($this->_aTargetPatterns) ; $ii++) {
				//
				// prepend / with \
				//
				$s_pat = "/" . str_replace('/','\\/',$this->_aTargetPatterns[$ii]) . "/i";
				if (preg_match($s_pat,$s_email)) {
					$b_is_valid = true;
					break;
				}
			}
		}
		return ($b_is_valid);
	}
}

//
// Create the object for checking emails
//
$ValidEmails = new EmailChecker(Settings::get('TARGET_EMAIL'));

/**
 * Encapsulates storage and lookup of field data.
 *
 * NOTE: this is initial code implemented in version 8.27 and is not complete.
 * It's part of our transition to a more complete Object Oriented code base
 * which is targeted for version 9.??.
 */
class FieldManager
{

	/**
	 * list of fields keyed by field name
	 *
	 * @var array
	 */
	private $_aFields;

	/**
	 * list of file fields keyed by field name (not currently used)
	 *
	 * @var array
	 */
	private $_aFileFields;

	/**
	 * last array separator specified
	 *
	 * @var string
	 */
	private $_sArraySep;

	/**
	 * array separator to use (after substitutions)
	 *
	 * @var string
	 */
	private $_sArraySepValue;

	/**
	 * counter for unique generation
	 *
	 * @var int
	 */
	private $_nUnique;

	/**
	 * Constructs the object.
	 *
	 * @param array $a_fields      list of fields
	 * @param array $a_file_fields list of file fields
	 */
	public function __construct($a_fields = array(),$a_file_fields = array())
	{
		$this->_sArraySepValue = $this->_sArraySep = "";
		$this->_aFields        = $this->_aFileFields = array();
		$this->_nUnique        = 0;
		$this->Init($a_fields,$a_file_fields);
	}

	/**
	 * Initializes the object with the field data.
	 *
	 * @param array $a_fields      list of fields
	 * @param array $a_file_fields list of file fields
	 *
	 * @return void
	 */
	public function Init($a_fields,$a_file_fields)
	{
		$this->_aFields     = $a_fields;
		$this->_aFileFields = $a_file_fields;
	}

	/**
	 * Return a field value.
	 * Empty string is returned if the field is
	 * not found. File fields return the original name of the uploaded file.
	 *
	 * @param string $s_fld       name of the field
	 * @param string $s_array_sep string to use to separate array values
	 *
	 * @return string the field's value
	 */
	public function GetFieldValue($s_fld,$s_array_sep = ";")
	{
		if (!isset($this->_aFields[$s_fld])) {
			if (($s_name = GetFileName($s_fld)) === false) {
				$s_name = "";
			}
		}
		if (is_array($this->_aFields[$s_fld])) {
			$s_value = implode($this->_GetArraySep($s_array_sep),$this->_aFields[$s_fld]);
		} else {
			$s_value = (string)$this->_aFields[$s_fld];
		}
		return ($s_value);
	}

	/**
	 * Return a field value.
	 * Empty string is returned if the field is
	 * not found. File fields return the original name of the uploaded file.
	 * The returned value is HTML-safe.
	 * b_text_subs performs text substitutions on the field value
	 * that are not affected by HTML-safety replacement. This means
	 *
	 * @uses $TEXT_SUBS can be used to force allowance of particular HTML tags. Note
	 *            that b_text_subs is not yet implemented for array * field
	 *            values.
	 *
	 * @param string $s_fld       name of the field
	 * @param bool   $b_text_subs perform text substitutions
	 * @param string $s_array_sep string to use to separate array values
	 *
	 * @return string the field's value
	 */
	public function GetSafeFieldValue($s_fld,$b_text_subs = false,$s_array_sep = ";")
	{
		//
		// for array values, insert the array separator after making
		// the individual values HTML-safe
		// The equivalent logic up to and including version 8.24 used
		// htmlspecialchars not htmlentities.
		// The use of htmlentities broke UTF-8 template processing,
		// and this was reported in version 8.28.
		// By specifying the character set, we trigger the use of
		// htmlspecialchars
		// so the logic is equivalent to the old logic.
		//
		if (isset($this->_aFields[$s_fld]) && is_array($this->_aFields[$s_fld])) {
			$s_value = implode($this->_GetArraySep($s_array_sep),
			                   HTMLEntitiesArray($this->_aFields[$s_fld],false,
			                                     GetMailOption("CharSet")));
		} else {
			if (!isset($this->_aFields[$s_fld])) {
				if (($s_name = GetFileName($s_fld)) === false) {
					$s_name = "";
				}
				$s_value = $s_name;
			} else {
				$s_value = (string)$this->_aFields[$s_fld];
			}
			if ($b_text_subs) {
				list ($s_value,$a_subs_data) = $this->_PrepareTextSubstitute($s_value);
			}
			$s_value = FixedHTMLEntities($s_value,GetMailOption("CharSet"));
			if ($b_text_subs) {
				$s_value = $this->_CompleteTextSubstitute($s_value,$a_subs_data);
			}
		}
		return ($s_value);
	}

	/**
	 * Prepares a value for text substitution using $TEXT_SUBS.
	 *
	 * @param string $s_value
	 *            the value to perform substitutions on
	 *
	 * @return array [0]=>the processed value, [1]=>array of substitution data
	 */
	private function _PrepareTextSubstitute($s_value)
	{

		$a_subs_data = array();
		$a_text_subs = Settings::get('TEXT_SUBS');
		for ($ii = 0 ; $ii < count($a_text_subs) ; $ii++) {
			$a_match_data = array();
			if (($n_matches = preg_match_all($a_text_subs[$ii]["srch"],$s_value,$a_matches,
			                                 PREG_OFFSET_CAPTURE)) !== false && $n_matches > 0
			) {
				$a_match_data["srch"] = $a_text_subs[$ii]["srch"];
				$a_match_data["repl"] = $a_text_subs[$ii]["repl"];
				$s_value              = $this->_HTMLSafeSubstitute($s_value,$a_matches,$a_match_data);
			}
			$a_subs_data[$ii] = $a_match_data;
		}
		return (array($s_value,$a_subs_data));
	}

	/**
	 * Completes text substitution started by _PrepareTextSubstitute.
	 *
	 * @param string $s_value
	 *            the value to perform substitutions on
	 * @param array  $a_subs_data
	 *            data that describes the substitutions to perform
	 *
	 * @return string the new value
	 */
	private function _CompleteTextSubstitute($s_value,$a_subs_data)
	{
		//
		// because later substitutions can capture earlier ones,
		// we have to process them all in reverse order
		//
		for ($ii = count($a_subs_data) ; --$ii >= 0 ;) {
			$a_subs_list = $a_subs_data[$ii];
			for ($jj = count($a_subs_list) ; --$jj >= 0 ;) {
				$s_code  = $a_subs_list[$jj]["code"];
				$s_subs  = $a_subs_list[$jj]["subs"];
				$s_value = str_replace($s_code,$s_subs,$s_value);
			}
		}
		return ($s_value);
	}

	/**
	 * Generates a unique string from a base string
	 *
	 * @param string $s_base
	 *            a base of the unique string
	 *
	 * @return string a unique string
	 */
	private function _MakeUniqueString($s_base)
	{
		$n_uniq = $this->_nUnique++;
		return ($s_base . "_" . str_pad("$n_uniq",5,"0",STR_PAD_LEFT));
	}

	/**
	 * Performs a temporary substitution on a string of the given matches with
	 * the given replacement specification.
	 * This makes the replacement using
	 * a special indicator string that can be substituted for the real value
	 * later. This allows non-replaced parts of the string to be processed
	 * and made safe for HTML entities, without affecting our actual
	 * replacements.
	 *
	 * @param string $s_value
	 *            the string to substitute
	 * @param array  $a_matches
	 *            list of matches and offsets from preg_match_all
	 * @param array  $a_match_data
	 *            contains * some data, and returns replacement data for the
	 *            temporary substitution
	 *
	 * @return string the temporarily substituted string
	 */
	private function _HTMLSafeSubstitute($s_value,$a_matches,&$a_match_data)
	{
		$a_matches = $a_matches[0]; // we're only interested in the full pattern
		// matches
		$s_srch = $a_match_data["srch"];
		$s_repl = $a_match_data["repl"];

		//
		// to preserve offsets, we must process the string in reverse order
		// of the matches; since we don't assume the array is ordered
		// by ascending offset, we'll sort it now
		//
		usort($a_matches,create_function('$a,$b','return $b[1] - $a[1];'));
		$a_match_data = array();
		for ($ii = 0 ; $ii < count($a_matches) ; $ii++) {
			$s_match  = $a_matches[$ii][0];
			$i_offset = $a_matches[$ii][1];
			$i_len    = strlen($s_match);

			$s_subs = preg_replace($s_srch,$s_repl,$s_match);
			//
			// the code string must e HTML safe so it doesn't get altered
			// before we can replace it; we use ! at the edges so that
			// other patterns can successfully match word boundaries
			// An improvement would be to determine the type of characters
			// at the edges of the matched string, then choose the substitution
			// edges accordingly.
			//
			$s_code            = "!" . $this->_MakeUniqueString("SUBS") . "!";
			$a_match_data[$ii] = array("subs" => $s_subs,"code" => $s_code);

			$s_value = substr($s_value,0,$i_offset) . $s_code . substr($s_value,$i_offset + $i_len);
		}
		return ($s_value);
	}

	/**
	 * Test if a field is set in the $_aFields array or in the uploaded
	 * files.
	 *
	 * @param string $s_fld name of the field
	 *
	 * @return bool true if the field has a value
	 */
	public function IsFieldSet($s_fld)
	{
		global $aFileVars; // temporary code until this class is complete

		if (isset($this->_aFields[$s_fld])) {
			return (true);
		}
		if (Settings::get('FILEUPLOADS')) {
			if (isset($aFileVars[$s_fld])) {
				return (true);
			}
			if (IsSetSession("FormSavedFiles")) {
				$a_saved_files = GetSession("FormSavedFiles");
				if (isset($a_saved_files[$s_fld])) {
					return (true);
				}
			}
		}
		return (false);
	}

	/**
	 * Tests a field against the $_aFields array for emptiness.
	 * If the var isn't found there, then the POSTed files array is checked.
	 * Returns true if the field is empty (a specific error may
	 * be returned in the $s_mesg parameter).
	 *
	 * @param string $s_fld
	 *            name of the field
	 * @param string $s_mesg
	 *            returns an error message, where possible
	 *
	 * @return bool true if the field is empty
	 */
	public function TestFieldEmpty($s_fld,&$s_mesg)
	{
		global $aFileVars; // temporary until code completed

		$s_mesg  = "";
		$b_empty = TRUE;
		if (!isset($this->_aFields[$s_fld])) {
			//
			// Each file var is an array with these elements:
			// "name" => The original name of the file on the client machine.
			// "type" => The mime type of the file, if the browser provided this
			// information.
			// "tmp_name" => The temporary filename of the file in which the
			// uploaded file was stored on the server.
			// "error" => The error code associated with this file upload.
			// NOTE: "error" was added in PHP 4.2.0
			// "size" => The size, in bytes, of the uploaded file.
			//
			// Error codes (the constants are only available from PHP 4.3.0 so
			// we have to use the raw numbers):
			// UPLOAD_ERR_OK
			// Value: 0; There is no error, the file uploaded with success.
			// UPLOAD_ERR_INI_SIZE
			// Value: 1; The uploaded file exceeds the upload_max_filesize
			// directive in php.ini.
			// UPLOAD_ERR_FORM_SIZE
			// Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive
			// that was specified in the html form.
			// UPLOAD_ERR_PARTIAL
			// Value: 3; The uploaded file was only partially uploaded.
			// UPLOAD_ERR_NO_FILE
			// Value: 4; No file was uploaded.
			//
			if (Settings::get('FILEUPLOADS')) {
				if (IsSetSession("FormSavedFiles")) {
					$a_saved_files = GetSession("FormSavedFiles");
					if (isset($a_saved_files[$s_fld])) {
						$a_upload = $a_saved_files[$s_fld];
					} elseif (isset($aFileVars[$s_fld])) {
						$a_upload = $aFileVars[$s_fld];
					}
				} elseif (isset($aFileVars[$s_fld])) {
					$a_upload = $aFileVars[$s_fld];
				}
			}
			if (isset($a_upload)) {
				if (isset($a_upload["tmp_name"]) && !empty($a_upload["tmp_name"]) &&
				    isset($a_upload["name"]) && !empty($a_upload["name"])
				) {
					if (IsUploadedFile($a_upload)) {
						$b_empty = false;
					}
				}
				if ($b_empty && isset($a_upload["error"])) {
					switch ($a_upload["error"]) {
						case 1:
							$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR1);
							break;
						case 2:
							$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR2);
							break;
						case 3:
							$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR3);
							break;
						case 4:
							$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR4);
							break;
						case 6:
							$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR6);
							break;
						case 7:
							$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR7);
							break;
						case 8:
							$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR8);
							break;
						default:
							$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR_UNK,
							                     array("ERRNO" => $a_upload["error"]));
							break;
					}
				}
			}
		} else {
			$b_empty = FieldManager::IsEmpty($this->_aFields[$s_fld]);
		}
		return ($b_empty);
	}

	/**
	 * Same as "empty" but checks for true emptiness if ZERO_IS_EMPTY is
	 * set to false.
	 *
	 * @param string $s_value the value to test
	 *
	 * @return bool true if the value is "empty"
	 */
	public static function IsEmpty($s_value)
	{
		if (Settings::get('ZERO_IS_EMPTY') || is_array($s_value)) {
			return (empty($s_value));
		} else {
			return ($s_value === "");
		}
	}

	/**
	 * Makes substitutions on strings as specified in the configuration setting $TEXT_SUBS.
	 *
	 * @param string $s_str a string on which to perform substitutions
	 *
	 * @return string the string, substituted
	 */
	public static function Substitute($s_str)
	{

		$a_srch = $a_repl = array();
		foreach (Settings::get('TEXT_SUBS') as $a_sub) {
			if (isset($a_sub["srch"]) && isset($a_sub["repl"]) && $a_sub["srch"] !== "") {
				$a_srch[] = $a_sub["srch"];
				$a_repl[] = $a_sub["repl"];
			}
		}
		return (preg_replace($a_srch,$a_repl,$s_str));
	}

	/**
	 * Computes the array separation string.
	 * $s_sep is subject to
	 * subsititions specified in $TEXT_SUBS, and then returned.
	 *
	 * @param string $s_sep the string specified for array separations
	 *
	 * @return string the string to use for array separations
	 */
	private function _GetArraySep($s_sep)
	{
		//
		// check for cached (previously calculated) value
		//
		if ($s_sep !== $this->_sArraySep) {
			$this->_sArraySep      = $s_sep;
			$this->_sArraySepValue = FieldManager::Substitute($this->_sArraySep);
		}
		return ($this->_sArraySepValue);
	}
}

//
// Peform general line folding.
// This function can be used for RFC 2822 line folding, as well
// Quoted Printable soft line breaks (RFC 2045).
// $s_before lists the characters before which we should fold the line.
// $s_after lists the characters after which we should fold the line.
// $s_fold is the string to insert to fold the line.
//
function    LineFolding($s_str,$i_max_line,$s_before,$s_after,$s_fold)
{
	$i_str_len  = strlen($s_str);
	$ii         = $i_start = 0;
	$i_line_len = 0;
	while ($ii < $i_str_len) {
		if ($i_line_len == $i_max_line) {
			//
			// fold this line:
			// search backwards for a character at which we can
			// fold the line
			//
			$b_done = false;
			for ($jj = $ii ; !$b_done && $jj > $i_start ; $jj--) {
				$b_found = false;
				if (strpos($s_before,$s_str[$jj]) !== false) {
					//
					// fold before this character
					//
					$b_found = true;
				} elseif (strpos($s_after,$s_str[$jj]) !== false) {
					//
					// fold after this character
					//
					$jj++;
					$b_found = true;
				}
				if ($b_found) {
					$s_str      = substr($s_str,0,$jj) . $s_fold . substr($s_str,$jj);
					$i_fold_len = strlen($s_fold);
					$i_str_len += $i_fold_len; // the additional chars we inserted
					$i_start = $jj + $i_fold_len; // start of the next line
					$b_done  = true;
				}
			}
			//
			// if we cannot fold and shorten the line, 
			// ignore this and try for the next line
			//
			if ($b_done) {
				$ii = $i_start;
			} else {
				$i_start = $ii;
			}
			$i_line_len = 0;
		} elseif (substr($s_str,$ii,2) == "\r\n") {
			//
			// end of line found - reset counters
			//
			$i_line_len = 0;
			$ii += 2;
			$i_start = $ii;
		} else {
			$ii++;
			$i_line_len++;
		}
	}
	return ($s_str);
}

//
// Quoted Printable Encoding with soft line breaks.
//
// Process a string to fit the requirements of RFC2045 section 6.7. Note that
// this works, but replaces more characters than the minimum set.
// Prior to version 8.34, for readability the spaces were not encoded, which was
// WRONG (see http://tools.ietf.org/html/rfc2047#section-2).  Spaces must
// be encoded.
//
// Adapted from:
//  http://www.php.net/manual/en/function.quoted-printable-decode.php
// Note that we *must* split long lines because a QP string might not
// contain any Folding White Space (FWS). In this case, it would
// not be possible to fold the line according to RFC 2822.
// Therefore, we need to use Soft Line Breaks as defined by the
// Quoted Printable definition in RFC 2045.
//
// Set $i_max_line to -ve to skip the line folding.
//
function    QPEncode($s_str,$i_max_line)
{
	//
	// According to RFC2045 section 6.7 point (4), we need to keep any
	// actual \r\n breaks encoded in the QP.  The original code replaced
	// them with CRLF pairs.
	//
	$s_str = str_replace('%','=',rawurlencode($s_str));
	if ($i_max_line < 0) {
		return ($s_str);
	} else {
		$s_before = "="; // characters before which we can fold the line
		return (LineFolding($s_str,$i_max_line,$s_before,"","=\r\n"));
	}
}

//
// Peform header line folding according to RFC 2822.
// $s_before lists the characters before which we should fold the line.
// $s_after lists the characters after which we should fold the line.
// Characters left out of folding:
//      [] are part of no-fold-literal
//      () are part of comments, and should work, but don't
//
function    HeaderFolding($s_str,$i_max_line = RFCLINELEN,$s_before = "<",$s_after = ">;, ")
{
	return (LineFolding($s_str,$i_max_line,$s_before,$s_after,"\r\n "));
}

//
// Access the www.tectite.com website to get the current version.
//
function CheckVersion()
{
	global $FM_VERS;

	$http_get     = new HTTPGet("http://www.tectite.com/fmversion.txt");
	$php_errormsg = ""; // clear this out in case we get an error that doesn't set it
	FMDebug("CheckVersion");
	if (($a_lines = $http_get->Read()) !== false) {
		//
		// version file looks like this:
		//      Version=versionumber
		//      Message=a message to send in the alert
		//
		$s_version = "";
		$s_message = "";
		$s_line    = "";
		$b_in_mesg = false;
		foreach ($a_lines as $s_line) {
			if ($b_in_mesg) {
				$s_message .= $s_line;
			} else {
				$s_prefix = substr($s_line,0,8);
				if ($s_prefix == "Message=") {
					$s_message .= substr($s_line,8);
					$b_in_mesg = true;
				} elseif ($s_prefix == "Version=") {
					$s_version = substr($s_line,8);
				}
			}
		}
		$s_version   = str_replace("\r","",$s_version);
		$s_version   = str_replace("\n","",$s_version);
		$s_stop_mesg = GetMessage(MSG_END_VERS_CHK);
		FMDebug("CheckVersion: vers=$s_version");
		if ((float)$s_version > (float)$FM_VERS) {
			SendAlert(GetMessage(MSG_VERS_CHK,array(
				                                      "TECTITE" => "www.tectite.com",
				                                      "FM_VERS" => "$FM_VERS",
				                                      "NEWVERS" => $s_version,
			                                      )) .
			          "\n$s_message\n$s_stop_mesg",true,true);
		}
	}
}

//
// Check for new FormMail version
//
function Check4Update($s_chk_file,$s_id = "")
{
	global $lNow,$php_errormsg;

	@   $l_last_chk = filemtime($s_chk_file);
	if ($l_last_chk === false || $lNow - $l_last_chk >= (Settings::get('CHECK_DAYS') * 24 * 60 * 60)) {
		CheckVersion();
		//
		// update the check file's time stamp
		//
		@   $fp = fopen($s_chk_file,"w");
		if ($fp !== false) {
			fwrite($fp,"FormMail version check " .
			           (empty($s_id) ? "" : "for identifier '$s_id' ") .
			           "at " . date("H:i:s d-M-Y",$lNow) . "\n");
			fclose($fp);
		} else {
			SendAlert(GetMessage(MSG_CHK_FILE_ERROR,array("FILE"  => $s_chk_file,
			                                              "ERROR" => CheckString($php_errormsg)
			)));
		}
	}
}

//
// Perform various processing at the end of the script's execution.
//
function OnExit()
{
	FMDebug("OnExit");

	//
	// Check the www.tectite.com website for a new version, but only
	// do this check once every CHECK_DAYS days (or on server reboot).
	//
	if (Settings::get('CHECK_FOR_NEW_VERSION')) {
		global $SERVER;

		$a_targets = Settings::get('TARGET_EMAIL');
		if (isset($a_targets[0])) {
			//
			// use the first few characters of the MD5 of first email
			// address pattern from $TARGET_EMAIL to get a unique file
			// for the server
			//
			$s_id = "";
			if (isset($SERVER) && !empty($SERVER)) {
				$s_id = $SERVER;
			}
			$s_dir      = GetTempDir();
			$s_md5      = md5($a_targets[0]);
			$s_uniq     = substr($s_md5,0,6);
			$s_chk_file = "fm" . "$s_uniq" . ".txt";
			Check4Update($s_dir . "/" . $s_chk_file,$s_id);
		}
	}
}

register_shutdown_function('OnExit');

//
// Return the array with each string processed by htmlentities
//
function HTMLEntitiesArray($a_array,$b_equals_processing = false,$s_charset = NULL)
{
	foreach ($a_array as $m_key => $s_str) {
		//
		// only encode the value after the '='
		//
		if ($b_equals_processing && ($i_pos = strpos($s_str,'=')) !== false) {
			$a_array[$m_key] = substr($s_str,0,$i_pos + 1) .
			                   FixedHTMLEntities(substr($s_str,$i_pos + 1),$s_charset);
		} else {
			$a_array[$m_key] = FixedHTMLEntities($s_str,$s_charset);
		}
	}
	return ($a_array);
}

//
// Unfortunately, htmlentities (in some versions of PHP) gets
// some characters wrong and converts them even when the
// charset is provided.
// This function overcomes this problem.
//
function    FixedHTMLEntities($s_str,$s_charset = NULL)
{
	global $sHTMLCharSet;

	if (isset($s_charset) && $s_charset != "") {
		return (htmlspecialchars($s_str,ENT_COMPAT,$s_charset));
	}
	if (isset($sHTMLCharSet) && $sHTMLCharSet != "") {
		return (htmlspecialchars($s_str,ENT_COMPAT,$sHTMLCharSet));
	}
	return (htmlentities($s_str));
}

//
// Return the array with each string urlencode'd.
//
function URLEncodeArray($a_array)
{
	foreach ($a_array as $m_key => $s_str) {
		//
		// only encode the value after the '='
		//
		if (($i_pos = strpos($s_str,'=')) !== false) {
			$a_array[$m_key] = substr($s_str,0,$i_pos + 1) .
			                   urlencode(substr($s_str,$i_pos + 1));
		} else {
			$a_array[$m_key] = urlencode($s_str);
		}
	}
	return ($a_array);
}

//
// Performs charset encoding for header line text.
// This operates according to RFC 2047, but without
// imposing the 75 character limit on an encoding.
// I haven't implemented that because of all the dramas
// with *trying* to obey the header line length rules that
// don't seem to work with PHP, the MTA, and email clients.
//
function    EncodeHeaderText($s_text,$i_max_line = -1)
{
	global $sHTMLCharSet;

	//
	// RFCLINELEN is the RFC recommended maximum line length, but we don't know
	// what the front part of the line will be at this point.
	// So, we'll be conservative and reduce it.
	//
	if ($i_max_line == 0) {
		$i_max_line = RFCLINELEN - 15;
	}
	$s_charset = "";
	if (isset($sHTMLCharSet) && $sHTMLCharSet != "") {
		$s_charset = $sHTMLCharSet;
	} else {
		if (IsMailOptionSet("CharSet")) {
			$s_charset = GetMailOption("CharSet");
		}
	}
	if ($s_charset != "") {
		//
		// this is the (unused) code for base64 encoding.
		// quoted printable is a better choice for human readability
		//
		//return ("=?".$s_charset."?B?".base64_encode($s_text)."?=");
		$s_prefix = "=?" . $s_charset . "?Q?";
		$s_suffix = "?=";
		//
		// pick a line length that allows a line split with the prefix or suffix added
		// to be within the RFC maximum recommended line length
		//
		return ($s_prefix . QPEncode($s_text,$i_max_line - strlen($s_prefix)) . $s_suffix);
	} else {
		return ($s_text);
	}
}

//
// Add a parameter or list of parameters to a URL.
//
function AddURLParams($s_url,$m_params,$b_encode = true)
{
	if (!empty($m_params)) {
		if (!is_array($m_params)) {
			$m_params = array($m_params);
		}
		$s_anchor = "";
		if (($i_pos = strpos($s_url,'#')) !== false) {
			//
			// extract the anchor
			//
			$s_anchor = substr($s_url,$i_pos);
			$s_url    = substr($s_url,0,$i_pos);
		}
		if (strpos($s_url,'?') === false) {
			$s_url .= '?';
		} else {
			$s_url .= '&';
		}
		$s_url .= implode('&',($b_encode) ? URLEncodeArray($m_params) : $m_params);
		if ($s_anchor !== "") {
			$s_url .= "$s_anchor";
		}
	}
	return ($s_url);
}

//
// Recursively trim an array of strings (non string values are converted
// to a string first).
//
function TrimArray($a_list)
{
	foreach ($a_list as $m_key => $m_item) {
		if (is_array($m_item)) {
			$a_list[$m_key] = TrimArray($m_item);
		} elseif (is_scalar($m_item)) {
			$a_list[$m_key] = trim("$m_item");
		} else {
			$a_list[$m_key] = "";
		}
	}
	return ($a_list);
}

//
// Parse a derivation specification and return an array of
// field names and operators.
//
function ParseDerivation($a_form_data,$s_fld_spec,$s_name,&$a_errors)
{
	$a_deriv = array();
	while (($i_len = strlen($s_fld_spec)) > 0) {
		//
		// we support the following operators:
		//      +   concatenate with a single space between, but skip the space
		//          if the next field is empty
		//      *   concatenate with a single space between
		//      .   concatenate with no space between
		//
		$i_span = strcspn($s_fld_spec,'+*.');
		if ($i_span == 0) {
			$a_errors[] = $s_name;
			return (false);
		}
		$a_deriv[] = trim(substr($s_fld_spec,0,$i_span));
		if ($i_span < $i_len) {
			$a_deriv[]  = substr($s_fld_spec,$i_span,1);
			$s_fld_spec = substr($s_fld_spec,$i_span + 1);
		} else {
			$s_fld_spec = "";
		}
	}
	return ($a_deriv);
}

//
// Test if a character is an alphabetic.
//
function IsAlpha($ch)
{
	return (strpos("abcdefghijklmnopqrstuvwxyz",strtolower($ch)) !== false);
}

//
// Test if a character is a digit.
//
function IsNumeric($ch)
{
	return (strpos("0123456789",$ch) !== false);
}

//
// Test if a character is an alphanumeric
//
function IsAlnum($ch)
{
	return (IsAlpha($ch) || IsNumeric($ch));
}

//
// Return an array of tokens extracted from the given string.
// A token is:
//  - a word (begins with alpha or _, and is followed by any number
//    of alphanumerics or _ chars)
//  - a number (any number of consecutive digits with up to one period)
//  - a string enclosed in specified quotes (this can be disabled)
//  - any punctuation character
//
// Anything not matching the above is silently ignored!
//
function GetTokens($s_str,$s_quotes = "'\"")
{
	$b_allow_strings = ($s_quotes !== "") ? true : false;
	$ii              = 0;
	$i_len           = strlen($s_str);
	$a_toks          = array();

	while ($ii < $i_len) {
		switch ($ch = $s_str[$ii]) {
			case " ":
			case "\t":
			case "\n":
			case "\r":
				$ii++;
				continue;
		}
		//
		// start of a token
		//
		$i_start = $ii;
		if ($ch == "_" || IsAlpha($ch)) {
			//
			// a word
			//
			$i_count = 1;
			while (++$ii < $i_len &&
			       ($s_str[$ii] == "_" || IsAlnum($s_str[$ii]))) {
				++$i_count;
			}
			$a_toks[] = substr($s_str,$i_start,$i_count);
		} elseif (($ch == "." && $ii < ($i_len - 1) && IsNumeric($s_str[$ii + 1])) ||
		          IsNumeric($ch)
		) {
			//
			// a number
			//
			$b_had_dot = ($ch == ".");
			$i_count   = 1;
			while (++$ii < $i_len) {
				if (IsNumeric($s_str[$ii])) {
					++$i_count;
				} elseif ($s_str[$ii] == "." && !$b_had_dot) {
					++$i_count;
					$b_had_dot = true;
				} else {
					break;
				}
			}
			$a_toks[] = substr($s_str,$i_start,$i_count);
		} elseif ($b_allow_strings && strpos($s_quotes,$ch) !== false) {
			$c_quote = $ch;
			//
			// a quoted string
			//
			while (++$ii < $i_len) {
				if ($s_str[$ii] == $c_quote) {
					++$ii; // include the terminating quote
					break;
				}
			}
			$a_toks[] = substr($s_str,$i_start,$ii - $i_start);
		} else {
			$s_punct = "~!@#$%^&*()-+={}[]|:;<>,.?/`\\";
			if (!$b_allow_strings) {
				$s_punct .= "'\"";
			}
			if (strpos($s_punct,$ch) !== false) {
				$a_toks[] = $ch;
			}
			++$ii;
		}
	}
	return ($a_toks);
}

//
// Return the value from a derive_fields specification.
// Specifications are in this format:
//      %info%
// where info is a predefined word or a literal in quotes
// (e.g. 'the time is ')
//
function ValueSpec($s_spec,$a_form_data,&$a_errors)
{
	global $lNow;

	$s_value = "";
	switch (trim($s_spec)) {
		case 'date': // "standard" date format: DD-MMM-YYYY
			$s_value = date('d-M-Y',$lNow);
			break;
		case 'time': // "standard" time format: HH:MM:SS
			$s_value = date('H:i:s',$lNow);
			break;
		case 'ampm': // am or pm
			$s_value = date('a',$lNow);
			break;
		case 'AMPM': // AM or PM
			$s_value = date('A',$lNow);
			break;
		case 'dom0': // day of month with possible leading zero
			$s_value = date('d',$lNow);
			break;
		case 'dom': // day of month with no leading zero
			$s_value = date('j',$lNow);
			break;
		case 'day': // day name (abbreviated)
			$s_value = date('D',$lNow);
			break;
		case 'dayname': // day name (full)
			$s_value = date('l',$lNow);
			break;
		case 'daysuffix': // day number suffix for English (st for 1st, nd for 2nd, etc.)
			$s_value = date('S',$lNow);
			break;
		case 'moy0': // month of year with possible leading zero
			$s_value = date('m',$lNow);
			break;
		case 'moy': // month of year with no leading zero
			$s_value = date('n',$lNow);
			break;
		case 'month': // month name (abbreviated)
			$s_value = date('M',$lNow);
			break;
		case 'monthname': // month name (full)
			$s_value = date('F',$lNow);
			break;
		case 'year': // year (two digits)
			$s_value = date('y',$lNow);
			break;
		case 'fullyear': // year (full)
			$s_value = date('Y',$lNow);
			break;
		case 'rfcdate': // date formatted according to RFC 822
			$s_value = date('r',$lNow);
			break;
		case 'tzname': // timezone name
			$s_value = date('T',$lNow);
			break;
		case 'tz': // timezone difference from Greenwich +NNNN or -NNNN
			$s_value = date('O',$lNow);
			break;
		case 'hour120': // hour of day (01-12) with possible leading zero
			$s_value = date('h',$lNow);
			break;
		case 'hour240': // hour of day (00-23) with possible leading zero
			$s_value = date('H',$lNow);
			break;
		case 'hour12': // hour of day (1-12) with no leading zero
			$s_value = date('g',$lNow);
			break;
		case 'hour24': // hour of day (0-23) with no leading zero
			$s_value = date('G',$lNow);
			break;
		case 'min': // minute of hour (00-59)
			$s_value = date('i',$lNow);
			break;
		case 'sec': // seconds of minute (00-59)
			$s_value = date('s',$lNow);
			break;
		default:
			if ($s_spec[0] == "'") {
				//
				// to get a quote, use 3 quotes:
				//      '''
				//
				if ($s_spec == "'''") {
					$s_value = "'";
				} elseif (substr($s_spec,-1,1) == "'") {
					$s_value = substr($s_spec,1,-1);
				} else
					//
					// missing final quote is OK
					//
				{
					$s_value = substr($s_spec,1);
				}
			} elseif (strspn($s_spec,"0123456789ABCDEF") == 2) {
				//
				// insert the ASCII character corresponding to
				// the hexadecimal value
				//
				$i_val   = intval(substr($s_spec,0,2),16);
				$s_value = chr($i_val);
			} else {
				//
				// look for supported functions, start by getting all
				// the tokens
				//
				$a_toks = GetTokens($s_spec);
				if (count($a_toks) > 0) {
					switch ($a_toks[0]) {
						case "if":
							//
							// "if" function: test first field
							//       if not empty, then use second field
							//       else, use third field
							//
							// Example: if(fld1 ; fld2 ; fld3)
							//
							// tokens are:
							//      1               (
							//      2               the field name to test (first)
							//      3               ;
							//      4               the "then" spec (can be missing)
							//      5               ;
							//      6               the "else" spec (can be missing)
							//      7               )
							//
							if (($n_tok = count($a_toks)) < 6 ||
							    $a_toks[1] != "(" ||
							    $a_toks[3] != ";" ||
							    $a_toks[$n_tok - 1] != ")"
							) {
								SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
								                     array("SPEC" => $s_spec,
								                           "MSG"  => GetMessage(MSG_DER_FUNC_IF_FMT)
								                     )));
							} else {
								$b_ok        = true;
								$s_fld_name  = $a_toks[2];
								$s_then_spec = $s_else_spec = "";
								for ($ii = 4 ; $ii < $n_tok && $a_toks[$ii] != ';' ; $ii++) {
									$s_then_spec .= $a_toks[$ii];
								}
								if ($ii == $n_tok) {
									$b_ok = false;
								} else {
									//
									// Concatenate tokens until the ')'.
									// This provides the "else" spec.
									//
									for (; ++$ii < $n_tok && $a_toks[$ii] != ')' ;) {
										$s_else_spec .= $a_toks[$ii];
									}
									if ($ii == $n_tok) {
										$b_ok = false;
									}
								}
								$s_mesg = "";
								if ($b_ok) {
									if (!TestFieldEmpty($s_fld_name,$a_form_data,$s_mesg)) {
										$s_fld_spec = $s_then_spec;
									} else {
										$s_fld_spec = $s_else_spec;
									}
									$s_value = GetDerivedValue($a_form_data,$s_fld_spec,$a_errors);
								} else {
									SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
									                     array("SPEC" => $s_spec,
									                           "MSG"  => GetMessage(MSG_DER_FUNC_IF_FMT)
									                     )));
								}
							}
							break;
						case "size":
							//
							// "size" function: return size of uploaded file
							//
							// Example: size(fieldname)
							//
							// tokens are:
							//      1               (
							//      2               the field name for the file upload
							//      3               )
							//
							if (count($a_toks) != 4 ||
							    $a_toks[1] != "(" ||
							    $a_toks[3] != ")"
							) {
								SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
								                     array("SPEC" => $s_spec,
								                           "MSG"  => GetMessage(MSG_DER_FUNC_SIZE_FMT)
								                     )));
							} elseif (($i_size = GetFileSize($a_toks[2])) !== false) {
								$s_value = "$i_size";
							}
							break;
						case "ext":
							//
							// "ext" function: return filename extension of uploaded file
							//
							// Example: ext(fieldname)
							//
							// tokens are:
							//      1               (
							//      2               the field name for the file upload
							//      3               )
							//
							if (count($a_toks) != 4 ||
							    $a_toks[1] != "(" ||
							    $a_toks[3] != ")"
							) {
								SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
								                     array("SPEC" => $s_spec,
								                           "MSG"  => GetMessage(MSG_DER_FUNC_EXT_FMT)
								                     )));
							} elseif (($s_name = GetFileName($a_toks[2])) !== false) {
								if (($i_pos = strrpos($s_name,".")) !== false) {
									$s_value = substr($s_name,$i_pos + 1);
								}
							}
							break;
						case "ucase":
						case "lcase":
							//
							// "ucase" and "lcase" functions: return field
							// converted to upper or lower case
							//
							// Example: lcase(fieldname)
							//
							// tokens are:
							//      1               (
							//      2               the field name to convert
							//      3               )
							//
							if (count($a_toks) != 4 ||
							    $a_toks[1] != "(" ||
							    $a_toks[3] != ")"
							) {
								SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
								                     array("SPEC" => $s_spec,
								                           "MSG"  => GetMessage(MSG_DER_FUNC1_FMT,
								                                                array("FUNC" => $a_toks[0]))
								                     )));
							} elseif ($a_toks[0] == "ucase") {
								$s_value = strtoupper(GetFieldValue($a_toks[2],$a_form_data));
							} else {
								$s_value = strtolower(GetFieldValue($a_toks[2],$a_form_data));
							}
							break;
						case "ltrim":
						case "rtrim":
						case "trim":
							//
							// trim functions: return field with whitespace removed
							// from left (ltrim), right (rtrim), or both (trim)
							// ends.
							//
							// Example: ltrim(fieldname)
							//
							// tokens are:
							//      1               (
							//      2               the field name to trim
							//      3               )
							//
							if (count($a_toks) != 4 ||
							    $a_toks[1] != "(" ||
							    $a_toks[3] != ")"
							) {
								SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
								                     array("SPEC" => $s_spec,
								                           "MSG"  => GetMessage(MSG_DER_FUNC1_FMT,
								                                                array("FUNC" => $a_toks[0]))
								                     )));
							} else {
								$s_value = $a_toks[0](GetFieldValue($a_toks[2],$a_form_data));
							}
							break;
						case "ltrim0":
							//
							// ltrim0 function: return field with blanks and
							// leading 0's removed from the left.
							//
							// Example: ltrim0(fieldname)
							//
							// tokens are:
							//      1               (
							//      2               the field name to trim
							//      3               )
							//
							if (count($a_toks) != 4 ||
							    $a_toks[1] != "(" ||
							    $a_toks[3] != ")"
							) {
								SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
								                     array("SPEC" => $s_spec,
								                           "MSG"  => GetMessage(MSG_DER_FUNC1_FMT,
								                                                array("FUNC" => $a_toks[0]))
								                     )));
							} else {
								$s_value = GetFieldValue($a_toks[2],$a_form_data);
								$s_value = ltrim($s_value); // trim blanks on left
								$i_len   = strspn($s_value,"0");
								//
								// if the whole string is zeroes, make sure we leave
								// one of them!
								//
								if ($i_len == strlen($s_value)) {
									if (--$i_len < 0) {
										$i_len = 0;
									}
								}
								$s_value = substr($s_value,$i_len);
							}
							break;
						case "nextnum":
							//
							// "nextnum" function: return a unique number (next number)
							//
							// Usage: nextnum[(pad[,base])]
							//
							// Examples:
							//      %nextnum%
							//      %nextnum(8)%
							//      %nextnum(5;16)%
							//
							// You can provide a padding amount. In this case, the
							// number is padded on the left with zeroes to the number
							// of digits specified.
							//
							// You can also provide a base for your numbers.  Valid
							// values for base are 2 to 36, inclusive.
							//
							// tokens are:
							//      1               (
							//      2               the padding amount
							//      3               ;
							//      4               the base
							//      5               )
							//
							$i_pad  = 0; // no padding
							$i_base = 10; // base 10
							if (($n_tok = count($a_toks)) > 1) {
								if (($n_tok != 4 && $n_tok != 6) ||
								    $a_toks[1] != "(" ||
								    $a_toks[$n_tok - 1] != ")"
								) {
									SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
									                     array("SPEC" => $s_spec,
									                           "MSG"  => GetMessage(MSG_DER_FUNC_NEXTNUM_FMT) . " (T1)"
									                     )));
								}
								if ($n_tok == 6 && $a_toks[3] != ";") {
									SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
									                     array("SPEC" => $s_spec,
									                           "MSG"  => GetMessage(MSG_DER_FUNC_NEXTNUM_FMT) . " (T2)"
									                     )));
								}
								if (!is_numeric($a_toks[2]) ||
								    ($n_tok == 6 && !is_numeric($a_toks[4]))
								) {
									SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
									                     array("SPEC" => $s_spec,
									                           "MSG"  => GetMessage(MSG_DER_FUNC_NEXTNUM_FMT) . " (T3)"
									                     )));
								}
								$i_pad = intval($a_toks[2]);
								if ($n_tok == 6) {
									$i_base = intval($a_toks[4]);
									if ($i_base < 2 || $i_base > 36) {
										SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
										                     array("SPEC" => $s_spec,
										                           "MSG"  =>
											                           GetMessage(MSG_DER_FUNC_NEXTNUM_FMT) . " (T4)"
										                     )));
										$i_base = 10;
									}
								}
								$s_value = GetNextNum($i_pad,$i_base);
							} else {
								$s_value = GetNextNum($i_pad,$i_base);
							}
							break;
						case "substr":
							//
							// "substr" function: return part of a string
							//
							// Usage: substr(fieldname;start;length)
							//
							// Examples:
							//      %substr(field1;0;5)%
							//      %substr(field1;4)%
							//      %substr(field1;-4;-1)%
							//
							// The start is the number of characters to skip
							// at the beginning of the string, if positive.
							// If negative, the start is calculated from the
							// end of the string, and says how many characters
							// to go backward from the end.
							//
							// The length is the number of characters to return,
							// if positive.  If not specified, then the remainder
							// of the string is returned.  If negative, it
							// specifies the number of characters to be omitted
							// from the end of the string.
							//
							// Examples:
							//  Assume "f1" has the value "abcdef":
							//      %substr(f1;0;5)%    returns "abcde"
							//      %substr(f1;4)%      returns "ef"
							//      %substr(f1;1;-1)%   returns "bcde"
							//      %substr(f1;-4;-1)%  returns "cde"
							//
							// tokens are:
							//      1               (
							//      2               the field name
							//      3               ;
							//      4               the start
							//      5               ;
							//      6               the length
							//      7               )
							//
							$i_start = 0; // default start
							$i_len   = null; // no length
							if (($n_tok = count($a_toks)) > 1) {
								if (($n_tok != 6 && $n_tok != 8) ||
								    $a_toks[1] != "(" ||
								    $a_toks[$n_tok - 1] != ")"
								) {
									SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
									                     array("SPEC" => $s_spec,
									                           "MSG"  => GetMessage(MSG_DER_FUNC_SUBSTR_FMT) . " (T1)"
									                     )));
								}
								if ($n_tok == 8 &&
								    ($a_toks[3] != ";" || $a_toks[5] != ";")
								) {
									SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
									                     array("SPEC" => $s_spec,
									                           "MSG"  => GetMessage(MSG_DER_FUNC_SUBSTR_FMT) . " (T2)"
									                     )));
								}
								if ($n_tok == 6 && $a_toks[3] != ";") {
									SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
									                     array("SPEC" => $s_spec,
									                           "MSG"  => GetMessage(MSG_DER_FUNC_SUBSTR_FMT) . " (T3)"
									                     )));
								}
								if (!is_numeric($a_toks[4]) ||
								    ($n_tok == 8 && !is_numeric($a_toks[6]))
								) {
									SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
									                     array("SPEC" => $s_spec,
									                           "MSG"  => GetMessage(MSG_DER_FUNC_SUBSTR_FMT) . " (T4)"
									                     )));
								}
								$i_start = intval($a_toks[4]);
								$s_value = GetFieldValue($a_toks[2],$a_form_data);
								if ($n_tok == 8) {
									$i_len = intval($a_toks[6]);
								}
								if (isset($i_len)) {
									$s_value = substr($s_value,$i_start,$i_len);
								} else {
									$s_value = substr($s_value,$i_start);
								}
								if ($s_value === FALSE) {
									$s_value = "";
								}
							} else {
								SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
								                     array("SPEC" => $s_spec,
								                           "MSG"  => GetMessage(MSG_DER_FUNC_SUBSTR_FMT) . " (T5)"
								                     )));
							}
							break;
						case "call":
							//
							// "call" function: calls a builtin function
							//
							// Example: call(name ; p1 ; p2 ; ...)
							//
							// tokens are:
							//      1               (
							//      2               the function name
							//      3               ;
							//      4               first parameter
							//      5               ;
							//      6               second parameter
							//      ...             ...
							//      n               )
							//
							if (($n_tok = count($a_toks)) < 3 ||
							    $a_toks[1] != "(" ||
							    $a_toks[$n_tok - 1] != ")"
							) {
								SendAlert(GetMessage(MSG_DER_FUNC_ERROR,
								                     array("SPEC" => $s_spec,
								                           "MSG"  => 'Incorrect format for call function'
								                     )));
							} else {
								$b_ok        = true;
								$s_func_name = $a_toks[2];
								$a_params    = array();
								if (3 < $n_tok && $a_toks[3] == ';') {
									$a_params = CollectParams($a_toks,$n_tok,3);
								}
								for ($ii = 0 ; $ii < count($a_params) ; $ii++) {
									$a_params[$ii] = GetDerivedValue($a_form_data,$a_params[$ii],$a_errors);
								}
								global $BuiltinFunctions;

								if (!$BuiltinFunctions->Call($s_func_name,$a_params,$s_result)) {
									SendAlert($s_result);
								} else {
									$s_value = $s_result;
								}
							}
							break;
						default:
							SendAlert(GetMessage(MSG_UNK_VALUE_SPEC,
							                     array("SPEC" => $s_spec,"MSG" => "")));
							break;
					}
				} else {
					SendAlert(GetMessage(MSG_UNK_VALUE_SPEC,array("SPEC" => $s_spec,
					                                              "MSG"  => ""
					)));
				}
			}
			break;
	}
	return ($s_value);
}

//
// Collect parameters for a builtin function call
//
function CollectParams($a_toks,$n_tok,$i_tok)
{
	$a_params = array();
	$i_param  = 0;
	do {
		$i_tok++;
		$a_params[$i_param] = '';
		for (; $i_tok < $n_tok && $a_toks[$i_tok] != ';' && $a_toks[$i_tok] != ')' ; $i_tok++) {
			$a_params[$i_param] .= $a_toks[$i_tok];
		}
		$i_param++;
	} while ($a_toks[$i_tok] != ')');
	return ($a_params);
}

//
// Return the next number or fail on error
//
function GetNextNum($i_pad,$i_base)
{
	global $php_errormsg;

	if (Settings::isEmpty('NEXT_NUM_FILE') || Settings::get('NEXT_NUM_FILE') === "") {
		ErrorWithIgnore("next_num_config",GetMessage(MSG_NO_NEXT_NUM_FILE));
		exit;
	}
	if (($fp = @fopen(Settings::get('NEXT_NUM_FILE'),"r+")) === false) {
		Error("next_num_file",GetMessage(MSG_NEXT_NUM_FILE,
		                                 array("FILE" => Settings::get('NEXT_NUM_FILE'),
		                                       "ACT"  => "open",
		                                       "ERR"  => $php_errormsg
		                                 )));
		exit;
	}
	if (!flock($fp,defined("LOCK_EX") ? LOCK_EX : 2)) {
		Error("next_num_file",GetMessage(MSG_NEXT_NUM_FILE,
		                                 array("FILE" => Settings::get('NEXT_NUM_FILE'),
		                                       "ACT"  => "flock",
		                                       "ERR"  => $php_errormsg
		                                 )));
		exit;
	}
	//
	// read the first line only
	//
	if (!feof($fp)) {
		if (($s_line = fread($fp,1024)) === false) {
			$i_next = 1;
		} elseif (($i_next = intval($s_line)) <= 0) {
			$i_next = 1;
		}
	} else {
		$i_next = 1;
	}
	if (rewind($fp) == 0) {
		Error("next_num_file",GetMessage(MSG_NEXT_NUM_FILE,
		                                 array("FILE" => Settings::get('NEXT_NUM_FILE'),
		                                       "ACT"  => "rewind",
		                                       "ERR"  => $php_errormsg
		                                 )));
		exit;
	}
	$s_ret = strval($i_next++);
	if (fputs($fp,"$i_next\r\n") <= 0) {
		Error("next_num_file",GetMessage(MSG_NEXT_NUM_FILE,
		                                 array("FILE" => Settings::get('NEXT_NUM_FILE'),
		                                       "ACT"  => "fputs",
		                                       "ERR"  => $php_errormsg
		                                 )));
		exit;
	}
	fclose($fp);
	if ($i_base != 10) {
		$s_ret = base_convert($s_ret,10,$i_base);
		$s_ret = strtoupper($s_ret); // always upper case if alphas are used
	}
	if ($i_pad != 0) {
		$s_ret = str_pad($s_ret,$i_pad,"0",STR_PAD_LEFT);
	}
	return ($s_ret);
}

//
// Return the value of an object or array as a string.
//
function GetObjectAsString($m_value)
{
	ob_start();
	print_r($m_value);
	$s_ret = ob_get_contents();
	ob_end_clean();
	return ($s_ret);
}

//
// Return a Server or Environment variable value.  Returns false if
// not found, otherwise a string value.
//
function GetEnvValue($s_name)
{
	global $aServerVars,$aEnvVars;

	if (isset($aEnvVars[$s_name])) {
		$m_value = $aEnvVars[$s_name];
	} elseif (isset($aServerVars[$s_name])) {
		$m_value = $aServerVars[$s_name];
	}
	//
	// some values might not be strings - so convert
	//
	if (isset($m_value) && !is_scalar($m_value)) {
		$m_value = GetObjectAsString($m_value);
	}
	return (isset($m_value) ? ((string)$m_value) : false);
}

//
// Test if a field is set in the given vars array or in the uploaded
// files.
//
function IsFieldSet($s_fld,$a_main_vars)
{
	global $aFileVars;

	if (isset($a_main_vars[$s_fld])) {
		return (true);
	}
	if (Settings::get('FILEUPLOADS')) {
		if (isset($aFileVars[$s_fld])) {
			return (true);
		}
		if (IsSetSession("FormSavedFiles")) {
			$a_saved_files = GetSession("FormSavedFiles");
			if (isset($a_saved_files[$s_fld])) {
				return (true);
			}
		}
	}
	return (false);
}

/*
 * Function:    IsFileField
 * Parameters:  $s_fld  the field name
 * Returns:     bool    true if this is a file upload field
 * Description:     
 *  Checks if a field is a file upload field (regardless of whether
 *  file uploads are being allowed, or whether the actual upload
 *  is valid in any way).
 */
function    IsFileField($s_fld)
{
	global $aFileVars;

	return (isset($aFileVars[$s_fld]));
}

//
// Delete the info for an uploaded file
//
function DeleteFileInfo($s_fld)
{
	global $aFileVars;
	global $aCleanedValues,$aRawDataValues,$aAllRawValues;

	if (Settings::get('FILEUPLOADS')) {
		if (IsSetSession("FormSavedFiles")) {
			$a_saved_files = GetSession("FormSavedFiles");
			unset($a_saved_files[$s_fld]);
			SetSession("FormSavedFiles",$a_saved_files);
		}
		if (isset($aFileVars[$s_fld])) {
			unset($aFileVars[$s_fld]);
		}
		//
		// zap any "name_of" field that has been created
		//
		$s_name = "name_of_$s_fld";
		unset($aCleanedValues[$s_name]);
		unset($aRawDataValues[$s_name]);
		unset($aAllRawValues[$s_name]);
	}
}

//
// Return the info for the uploaded file, or false on error.
//
function GetFileInfo($s_fld)
{
	global $aFileVars;

	if (Settings::get('FILEUPLOADS')) {
		//
		// Must look at new file uploads first.
		//
		if (isset($aFileVars[$s_fld]) && !empty($aFileVars[$s_fld])) {
			$a_upload = $aFileVars[$s_fld];
		} elseif (IsSetSession("FormSavedFiles")) {
			$a_saved_files = GetSession("FormSavedFiles");
			if (isset($a_saved_files[$s_fld])) {
				$a_upload = $a_saved_files[$s_fld];
			}
		}
	}
	if (isset($a_upload)) {
		if (isset($a_upload["tmp_name"]) && !empty($a_upload["tmp_name"]) &&
		    isset($a_upload["name"]) && !empty($a_upload["name"]) &&
		    IsUploadedFile($a_upload)
		) {
			return ($a_upload);
		}
	}
	return (false);
}

//
// Return the original name of the uploaded file or false on error.
//
function GetFileName($s_fld)
{
	if (($a_upload = GetFileInfo($s_fld)) !== false) {
		return ($a_upload["name"]);
	}
	return (false);
}

//
// Return the size of the uploaded file or false on error.
//
function GetFileSize($s_fld)
{
	if (($a_upload = GetFileInfo($s_fld)) !== false) {
		return ($a_upload["size"]);
	}
	return (false);
}

//
// Return a field value.  Empty string is returned if the field is
// not found. File fields return the original name of the uploaded file.
//
function GetFieldValue($s_fld,$a_main_vars,$s_array_sep = ";")
{
	if (!isset($a_main_vars[$s_fld])) {
		if (($s_name = GetFileName($s_fld)) === false) {
			$s_name = "";
		}
		return ($s_name);
	}
	if (is_array($a_main_vars[$s_fld])) {
		return (implode($s_array_sep,$a_main_vars[$s_fld]));
	} else {
		return ((string)$a_main_vars[$s_fld]);
	}
}

//
// Tests a field against an array of vars for emptyness.
// If the var isn't found there, then the POSTed files array is checked.
// Returns true if the field is empty (a specific error may
// be returned in the $s_mesg parameter).
//
function TestFieldEmpty($s_fld,$a_main_vars,&$s_mesg)
{
	global $aFileVars;

	$s_mesg  = "";
	$b_empty = TRUE;
	if (!isset($a_main_vars[$s_fld])) {
		//
		// Each file var is an array with these elements:
		//      "name" => The original name of the file on the client machine.
		//      "type" => The mime type of the file, if the browser provided this information.
		//      "tmp_name" => The temporary filename of the file in which the uploaded file was stored on the server.
		//      "error" => The error code associated with this file upload.
		//                  NOTE: "error" was added in PHP 4.2.0
		//      "size" => The size, in bytes, of the uploaded file.
		//
		// Error codes (the constants are only available from PHP 4.3.0 so
		// we have to use the raw numbers):
		//  UPLOAD_ERR_OK
		//      Value: 0; There is no error, the file uploaded with success.
		//  UPLOAD_ERR_INI_SIZE
		//      Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
		//  UPLOAD_ERR_FORM_SIZE
		//      Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.
		//  UPLOAD_ERR_PARTIAL
		//      Value: 3; The uploaded file was only partially uploaded.
		//  UPLOAD_ERR_NO_FILE
		//      Value: 4; No file was uploaded.
		//
		if (Settings::get('FILEUPLOADS')) {
			if (IsSetSession("FormSavedFiles")) {
				$a_saved_files = GetSession("FormSavedFiles");
				if (isset($a_saved_files[$s_fld])) {
					$a_upload = $a_saved_files[$s_fld];
				} elseif (isset($aFileVars[$s_fld])) {
					$a_upload = $aFileVars[$s_fld];
				}
			} elseif (isset($aFileVars[$s_fld])) {
				$a_upload = $aFileVars[$s_fld];
			}
		}
		if (isset($a_upload)) {
			if (isset($a_upload["tmp_name"]) && !empty($a_upload["tmp_name"]) &&
			    isset($a_upload["name"]) && !empty($a_upload["name"])
			) {
				if (IsUploadedFile($a_upload)) {
					$b_empty = false;
				}
			}
			if ($b_empty && isset($a_upload["error"])) {
				switch ($a_upload["error"]) {
					case 1:
						$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR1);
						break;
					case 2:
						$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR2);
						break;
					case 3:
						$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR3);
						break;
					case 4:
						$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR4);
						break;
					case 6:
						$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR6);
						break;
					case 7:
						$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR7);
						break;
					case 8:
						$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR8);
						break;
					default:
						$s_mesg = GetMessage(MSG_FILE_UPLOAD_ERR_UNK,
						                     array("ERRNO" => $a_upload["error"]));
						break;
				}
			}
		}
	} else {
		$b_empty = FieldManager::IsEmpty($a_main_vars[$s_fld]);
	}
	return ($b_empty);
}

//
// Return a derived field value or value specification.
//
function GetDerivedValue($a_form_data,$s_word,&$a_errors)
{
	$s_value = "";
	//
	// a field name or a value specification
	// value specifications have the following format:
	//      %spec%
	//
	if (substr($s_word,0,1) == '%') {
		if (substr($s_word,-1,1) != '%') {
			SendAlert(GetMessage(MSG_INV_VALUE_SPEC,array("SPEC" => $s_word)));
			$s_value = $s_word;
		} else {
			$s_spec  = substr($s_word,1,-1);
			$s_value = ValueSpec($s_spec,$a_form_data,$a_errors);
		}
	} else {
		$s_fld_name = $s_word;
		//
		// try form data first, then the environment/server data
		//
		if (IsFieldSet($s_fld_name,$a_form_data)) {
			$s_value = GetFieldValue($s_fld_name,$a_form_data);
		} elseif (($s_value = GetEnvValue($s_fld_name)) === false) {
			$s_value = "";
		}
		$s_value = trim($s_value);
	}
	return ($s_value);
}

//
// Derive a value from the form data using the specification returned
// from ParseDerivation.
//
function DeriveValue($a_form_data,$a_value_spec,$s_name,&$a_errors)
{
	$s_value = "";
	for ($ii = 0 ; $ii < count($a_value_spec) ; $ii++) {
		switch ($a_value_spec[$ii]) {
			case '+':
				//
				// concatenate with a single space between, but skip the space
				// if the next field is empty
				//
				if ($ii < count($a_value_spec) - 1) {
					$s_temp = GetDerivedValue($a_form_data,$a_value_spec[$ii + 1],$a_errors);
					if (!FieldManager::IsEmpty($s_temp)) {
						$s_value .= ' ';
					}
				}
				break;
			case '.':
				//
				// concatenate with no space between
				//
				break;
			case '*':
				//
				// concatenate with a single space between
				//
				$s_value .= ' ';
				break;
			default:
				//
				// a field name or a value specification
				// value specifications have the following format:
				//      %name%
				//
				$s_value .= GetDerivedValue($a_form_data,$a_value_spec[$ii],$a_errors);
				break;
		}
	}
	return ($s_value);
}

//
// Create derived fields specified by the "derive_fields" value.
//
function CreateDerived($a_form_data)
{
	if (isset($a_form_data["derive_fields"])) {
		$a_errors = array();
		//
		// get the list of derived field specifications
		//
		$a_list = TrimArray(explode(",",$a_form_data["derive_fields"]));
		foreach ($a_list as $s_fld_spec) {
			if ($s_fld_spec === "")
				//
				// silently ignore empty derivations
				//
			{
				continue;
			}
			if (($i_pos = strpos($s_fld_spec,"=")) === false) {
				$a_errors[] = $s_fld_spec;
				continue;
			}
			$s_name     = trim(substr($s_fld_spec,0,$i_pos));
			$s_fld_spec = substr($s_fld_spec,$i_pos + 1);

			if (($a_value_spec = ParseDerivation($a_form_data,$s_fld_spec,
			                                     $s_name,$a_errors)) === false
			) {
				break;
			}
			$a_form_data[$s_name] = DeriveValue($a_form_data,$a_value_spec,$s_name,$a_errors);
		}
		if (count($a_errors) > 0) {
			SendAlert(GetMessage(MSG_DERIVED_INVALID) . implode("\n",$a_errors));
			Error("derivation_failure",GetMessage(MSG_INT_FORM_ERROR));
		}
	}
	return ($a_form_data);
}

//
// To process the name specification for files and update the
// array of file variables accordingly.
//
function SetFileNames($s_name_spec,$a_order,$a_fields,$a_raw_fields,$a_all_raw_values,$a_file_vars)
{
	$a_errors = array();
	//
	// get the list of file name derivations
	//
	$a_list = TrimArray(explode(",",$s_name_spec));
	foreach ($a_list as $s_fld_spec) {
		if ($s_fld_spec === "")
			//
			// silently ignore empty file name derivations
			//
		{
			continue;
		}
		if (($i_pos = strpos($s_fld_spec,"=")) === false) {
			$a_errors[] = $s_fld_spec;
			continue;
		}
		$s_name     = trim(substr($s_fld_spec,0,$i_pos));
		$s_fld_spec = substr($s_fld_spec,$i_pos + 1);

		if (($a_value_spec = ParseDerivation($a_raw_fields,$s_fld_spec,
		                                     $s_name,$a_errors)) === false
		) {
			break;
		}
		if (isset($a_file_vars[$s_name]) && IsUploadedFile($a_file_vars[$s_name])) {
			//
			// we create our own special entry in the file variable's data
			//
			$a_file_vars[$s_name]["new_name"] = DeriveValue($a_raw_fields,
			                                                $a_value_spec,$s_name,
			                                                $a_errors);
			//
			// we also create (derive) a new field called 'name_of_X'
			// where X is the file fields's name
			//
			ProcessField("name_of_$s_name",$a_file_vars[$s_name]["new_name"],
			             $a_order,$a_fields,$a_raw_fields);
			$a_all_raw_values["name_of_$s_name"] = $a_file_vars[$s_name]["new_name"];
		}
		/* This is annoying if a file upload is optional.  Just ignore missing
            file upload fields.
        else
            SendAlert(GetMessage(MSG_FILE_NAMES_NOT_FILE,
                                            array("NAME"=>$s_name)));*/
	}
	if (count($a_errors) > 0) {
		SendAlert(GetMessage(MSG_FILE_NAMES_INVALID) . implode("\n",$a_errors));
		Error("file_names_derivation_failure",GetMessage(MSG_INT_FORM_ERROR));
	}
	return (array($a_order,$a_fields,$a_raw_fields,$a_all_raw_values,$a_file_vars));
}

$aProcessSpecsFormData  = array();
$sProcessSpecsFieldName = "";

//
// Callback function for ProcessSpecs
//
function    ProcessSpecsMatch($a_matches)
{
	global $aProcessSpecsFormData,$sProcessSpecsFieldName;

	//
	// strip % at either end
	//
	$s_spec   = substr($a_matches[0],1,-1);
	$a_errors = array();
	$s_value  = ValueSpec($s_spec,$aProcessSpecsFormData,$a_errors);
	return ($s_value);
}

//
// Process %...% specifications in a string.
// This function is used for processing options values, such as in "autorespond"
// and "mail_options".
//
function    ProcessSpecs($s_fld_name,$a_form_data,$s_value)
{
	global $aProcessSpecsFormData,$sProcessSpecsFieldName;

	$aProcessSpecsFormData  = $a_form_data;
	$sProcessSpecsFieldName = $s_fld_name;
	//
	// un-greedy pattern match
	// Note that this means we can't use %..% within a %if(..)%, for example.
	//
	$s_value                = preg_replace_callback('/%.+?%/',"ProcessSpecsMatch",$s_value);
	$aProcessSpecsFormData  = array();
	$sProcessSpecsFieldName = "";
	return ($s_value);
}

//
// Process a list of attributes or options.
// Format for each attribute/option:
//      name
// or
//      name=value
//
// Values can be simple values or semicolon (;) separated lists:
//          avalue
//          value1;value2;value3;...
//
// Returns attribute/options in the associative array $a_attribs.
// Optionally, valid attributes can be provided in $a_valid_attribs
// (if empty, all attributes found are considered valid).
// Errors are returned in $a_errors.
//
function ProcessAttributeList($s_fld_name,$a_form_data,$a_list,&$a_attribs,&$a_errors,
                              $a_valid_attribs = array())
{
	$b_got_valid_list = (count($a_valid_attribs) > 0);
	foreach ($a_list as $s_attrib) {
		//
		// if the name begins with '.' then silently ignore it;
		// this allows you to temporarily disable an option without
		// getting an alert message
		//
		if (($i_pos = strpos($s_attrib,"=")) === false) {
			$s_name = trim($s_attrib);
			if (empty($s_name) || $s_name[0] == '.') {
				continue;
			}
			//
			// option is a simple "present" value
			//
			$a_attribs[$s_name] = true;
		} else {
			$s_name = trim(substr($s_attrib,0,$i_pos));
			if (empty($s_name) || $s_name[0] == '.') {
				continue;
			}
			$s_value_list = substr($s_attrib,$i_pos + 1);
			if (($i_pos = strpos($s_value_list,";")) === false)
				//
				// single value
				//
			{
				$a_attribs[$s_name] = ProcessSpecs($s_fld_name,$a_form_data,trim($s_value_list));
			} else
				//
				// list of values
				//
			{
				$a_attribs[$s_name] = TrimArray(explode(";",$s_value_list));
			}
		}
		if ($b_got_valid_list && !isset($a_valid_attribs[$s_name])) {
			$a_errors[] = $s_name;
		}
	}
}

//
// Process the options specified in the form.
// Options can be specified in this format:
//          option1,option2,option3,...
// Each option can be a simple word or a word and value:
//          name
//          name=value
// No name or value can contain a comma.
// Values can be simple values or semicolon (;) separated lists:
//          avalue
//          value1;value2;value3;...
// No value can contain a semicolon.
// Be careful of values beginning and ending with whitespace characters;
// they will be trimmed.
//
function ProcessOptions($s_name,$a_form_data,&$a_options,$a_valid_options)
{
	$a_errors  = array();
	$a_options = array();
	if (isset($a_form_data[$s_name])) {
		//
		// get the options list and trim each one
		//
		$a_list = TrimArray(explode(",",$a_form_data[$s_name]));
		ProcessAttributeList($s_name,$a_form_data,$a_list,$a_options,$a_errors,$a_valid_options);
	}
	if (count($a_errors) > 0) {
		SendAlert(GetMessage(MSG_OPTIONS_INVALID,array("OPT" => $s_name)) .
		          implode("\n",$a_errors));
	}
}

//
// Process the mail_options specified in the form.
//
function ProcessMailOptions($a_form_data)
{
	global $MAIL_OPTS,$VALID_MAIL_OPTIONS;

	ProcessOptions("mail_options",$a_form_data,$MAIL_OPTS,$VALID_MAIL_OPTIONS);
}

//
// Check if an option is set
//
function IsMailOptionSet($s_name)
{
	global $MAIL_OPTS;

	return (isset($MAIL_OPTS[$s_name]));
}

//
// Return an option's value or NULL if not set.
//
function GetMailOption($s_name)
{
	global $MAIL_OPTS;

	return (isset($MAIL_OPTS[$s_name]) ? $MAIL_OPTS[$s_name] : NULL);
}

//
// Process the crm_options specified in the form.
//
function ProcessCRMOptions($a_form_data)
{
	global $CRM_OPTS,$VALID_CRM_OPTIONS;

	ProcessOptions("crm_options",$a_form_data,$CRM_OPTS,$VALID_CRM_OPTIONS);
}

//
// Check if an option is set
//
function IsCRMOptionSet($s_name)
{
	global $CRM_OPTS;

	return (isset($CRM_OPTS[$s_name]));
}

//
// Return an option's value or NULL if not set.
//
function GetCRMOption($s_name)
{
	global $CRM_OPTS;

	return (isset($CRM_OPTS[$s_name]) ? $CRM_OPTS[$s_name] : NULL);
}

//
// Check if a field is in the mail exclusion list.
//
function IsMailExcluded($s_name)
{
	$a_list = GetMailOption("Exclude");
	if (!isset($a_list)) {
		return (false);
	}
	if (is_array($a_list)) {
		return (in_array($s_name,$a_list));
	} else {
		return ($s_name === $a_list);
	}
}

//
// Process the autorespond specified in the form.
//
function ProcessAROptions($a_form_data)
{
	global $AR_OPTS,$VALID_AR_OPTIONS;

	ProcessOptions("autorespond",$a_form_data,$AR_OPTS,$VALID_AR_OPTIONS);
}

//
// Check if an option is set
//
function IsAROptionSet($s_name)
{
	global $AR_OPTS;

	return (isset($AR_OPTS[$s_name]));
}

//
// Return an option's value or NULL if not set.
//
function GetAROption($s_name)
{
	global $AR_OPTS;

	return (isset($AR_OPTS[$s_name]) ? $AR_OPTS[$s_name] : NULL);
}

//
// Process the mail_options specified in the form.
//
function ProcessFilterOptions($a_form_data)
{
	global $FILTER_OPTS,$VALID_FILTER_OPTIONS;

	ProcessOptions("filter_options",$a_form_data,$FILTER_OPTS,$VALID_FILTER_OPTIONS);
}

//
// Check if an option is set
//
function IsFilterOptionSet($s_name)
{
	global $FILTER_OPTS;

	return (isset($FILTER_OPTS[$s_name]));
}

//
// Return an option's value or NULL if not set.
//
function GetFilterOption($s_name)
{
	global $FILTER_OPTS;

	return (isset($FILTER_OPTS[$s_name]) ? $FILTER_OPTS[$s_name] : NULL);
}

//
// Lookup a filter attribute for the given filter.
// Return it's value or false if not set.
//
function GetFilterAttrib($s_filter,$s_attrib)
{
	global $FILTER_ATTRIBS_LOOKUP;

	$a_attribs = Settings::get('FILTER_ATTRIBS');
	if (!isset($a_attribs[$s_filter]))
		//
		// no attributes for the filter
		//
	{
		return (false);
	}
	if (!isset($FILTER_ATTRIBS_LOOKUP[$s_filter])) {
		//
		// the attributes have not yet been parsed - create the lookup table
		//
		$a_list                           = TrimArray(explode(",",$a_attribs[$s_filter]));
		$FILTER_ATTRIBS_LOOKUP[$s_filter] = array();
		$a_errors                         = array();

		ProcessAttributeList('FILTER_ATTRIBS',array(),$a_list,$FILTER_ATTRIBS_LOOKUP[$s_filter],$a_errors);
	}
	//
	// perform the lookup and return the value
	//
	if (!isset($FILTER_ATTRIBS_LOOKUP[$s_filter][$s_attrib])) {
		return (false);
	}
	return ($FILTER_ATTRIBS_LOOKUP[$s_filter][$s_attrib]);
}

//
// Check the filter attributes for the given filter.
// Return true if the given attribute is set otherwise false.
//
function IsFilterAttribSet($s_filter,$s_attrib)
{
	return (GetFilterAttrib($s_filter,$s_attrib));
}

//
// Process the given .ini file.
//
function ProcessFormIniFile($s_file)
{
	global $EMAIL_ADDRS,$ValidEmails;

	$a_sections = parse_ini_file($s_file,TRUE);
	//
	// from PHP 5.2.7, parse_ini_file returns false on syntax problems
	// prior to that, an empty array.  So, on previous versions of PHP
	// we cannot detect an actual error (an empty array is perfectly valid).
	//
	if ($a_sections === false) {
		Error("bad_ini",GetMessage(MSG_INI_PARSE_ERROR,array("FILE" => $s_file)));
	} elseif (empty($a_sections)) {
		SendAlert(GetMessage(MSG_INI_PARSE_WARN,array("FILE" => $s_file)),false,true);
	}
	if (Settings::get('DB_SEE_INI')) {
		//
		// just display the ini file
		//
		$s_text = "<p><b>The following settings were found in the file '$s_file':</b></p>";
		foreach ($a_sections as $s_sect => $a_settings) {
			$s_text .= "<p>[$s_sect]\n";
			foreach ($a_settings as $s_name => $s_value) {
				$s_text .= "$s_name = \"$s_value\"\n";
			}
			$s_text .= "</p>";
		}
		CreatePage($s_text,"Debug Output - INI File Display");
		exit;
	}
	//
	// Load the email_addresses section.
	//
	if (isset($a_sections["email_addresses"])) {
		$EMAIL_ADDRS = $a_sections["email_addresses"];
		//
		// make these addresses valid
		//
		foreach ($EMAIL_ADDRS as $s_list) {
			$ValidEmails->AddAddresses($s_list);
		}
	}
	//
	// Process special fields
	//
	if (isset($a_sections["special_fields"])) {
		foreach ($a_sections["special_fields"] as $s_name => $m_value) {
			if (IsSpecialField($s_name)) {
				SetSpecialField($s_name,$m_value);
				//
				// if this is the recipients, cc, or bcc field,
				// make the addresses valid
				//
				if ($s_name === "recipients" || $s_name === "cc" || $s_name === "bcc")
					//
					// coming from the INI file, the values can only be strings
					//
				{
					if (is_string($m_value)) {
						$ValidEmails->AddAddresses($m_value);
					}
				}
			}
			//
			// check for multiple valued special fields
			//
			if (($a_multi_fld = IsSpecialMultiField($s_name)) !== false) {
				SetSpecialMultiField($a_multi_fld[0],$a_multi_fld[1],$m_value);
			}
		}
	}
}

//
// UnMangle an email address
//
function UnMangle($email)
{
	global $EMAIL_ADDRS;

	//
	// map from a name to the real email address
	//
	if (isset($EMAIL_ADDRS[$email])) {
		$email = $EMAIL_ADDRS[$email];
	}
	//
	// unmangle
	//
	if (Settings::get('AT_MANGLE') != "") {
		$email = str_replace(Settings::get('AT_MANGLE'),"@",$email);
	}
	return ($email);
}

//
// Check a list of email addresses (comma separated); returns a list
// of valid email addresses (comma separated).
// The list can be an array of comma separated lists.
// The return value is true if there is at least one valid email address.
//
function CheckEmailAddress($m_addr,&$s_valid,&$s_invalid,$b_check = true)
{
	global $ValidEmails;

	$s_invalid = $s_valid = "";
	if (is_array($m_addr)) {
		$a_list = array();
		foreach ($m_addr as $s_addr_list) {
			$a_list = array_merge($a_list,TrimArray(explode(",",$s_addr_list)));
		}
	} else {
		$a_list = TrimArray(explode(",",$m_addr));
	}
	$a_invalid = array();
	$n_empty   = 0;
	for ($ii = 0 ; $ii < count($a_list) ; $ii++) {
		if ($a_list[$ii] === "") {
			//
			// ignore, but count empty addresses
			//
			$n_empty++;
			continue;
		}
		$s_email = UnMangle($a_list[$ii]);
		//
		// UnMangle works with INI files too, and a single
		// word can expand to a list of email addresses.
		//
		$a_this_list = TrimArray(explode(",",$s_email));
		foreach ($a_this_list as $s_email) {
			if ($s_email === "") {
				//
				// ignore, but count empty addresses
				//
				$n_empty++;
				continue;
			}
			if ($b_check) {
				$b_is_valid = $ValidEmails->CheckAddress($s_email);
			} else {
				$b_is_valid = true;
			}
			if ($b_is_valid) {
				if (empty($s_valid)) {
					$s_valid = $s_email;
				} else {
					$s_valid .= "," . $s_email;
				}
			} else {
				$a_invalid[] = $s_email;
			}
		}
	}
	//
	// just ignore empty recipients unless there are *no* valid recipients
	//
	if (empty($s_valid) && $n_empty > 0) {
		$a_invalid[] = GetMessage(MSG_EMPTY_ADDRESSES,array("COUNT" => $n_empty));
	}
	if (count($a_invalid) > 0) {
		$s_invalid = implode(",",$a_invalid);
	}
	return (!empty($s_valid));
}

//
// Redirect to another URL
//
function Redirect($url,$title)
{
	global $ExecEnv;

	//
	// for browsers without cookies enabled, append the Session ID
	//
	if ($ExecEnv->allowSessionURL()) {
		if (session_id() !== "") {
			$url = AddURLParams($url,session_name() . "=" . urlencode(session_id()));
		} elseif (defined("SID")) {
			$url = AddURLParams($url,SID);
		}
	}

	//FMDebug("Before redirecting, FormData = ".(isset($_SESSION["FormData"]) ? var_export($_SESSION["FormData"],true) : "NULL"));

	//
	// this is probably a good idea to ensure the session data
	// is written away
	//
	if (function_exists('session_write_close')) {
		session_write_close();
	}

	header("Location: $url");
	//
	// if the header doesn't work, try JavaScript.
	// if that doesn't work, provide a manual link
	//
	$s_text = GetMessage(MSG_PLSWAIT_REDIR) . "\n\n";
	$s_text .= "<script language=\"JavaScript\" type=\"text/javascript\">";
	$s_text .= "window.location.href = '$url';";
	$s_text .= "</script>";
	$s_text .= "\n\n" . GetMessage(MSG_IFNOT_REDIR,array("URL" => $url));
	CreatePage($s_text,$title);
	exit;
}

class   JSON
{
	function    _Format($m_val)
	{
		if (is_bool($m_val)) {
			$s_value = ($m_val) ? "true" : "false";
		} elseif (is_string($m_val)) {
			//
			// convert literal line breaks into JavaScript escape sequences
			//
			$s_value = '"' . str_replace(array("\r","\n"),array('\\r','\\n'),addslashes($m_val)) . '"';
		} elseif (is_numeric($m_val)) {
			$s_value = $m_val;
		} elseif (is_array($m_val)) {
			$s_value = $this->_FormatArray($m_val);
		} else {
			$s_value = "null";
		}
		return ($s_value);
	}

	function _FormatArray($a_array)
	{
		if ($this->_IsNumericArray($a_array)) {
			$a_values = array();
			foreach ($a_array as $m_val) {
				$a_values[] = $this->_Format($m_val);
			}
			$s_value = "[" . implode(",",$a_values) . "]";
		} else {
			//
			// associative arrays are objects
			//
			$s_value = $this->MakeObject($a_array);
		}
		return ($s_value);
	}

	//
	// check if we have a numeric array or an associative array
	// numeric arrays may have holes; numeric array indexes must
	// be integers
	//
	function _IsNumericArray($a_data)
	{
		if (empty($a_data)) {
			return (true);
		} // empty array - treat as numeric
		//
		// check all the keys for numeric
		//
		$a_keys = array_keys($a_data);
		foreach ($a_keys as $m_index) {
			if (!is_int($m_index)) {
				return (false);
			}
		}
		return (true);
	}

	function    MakeObject($a_data)
	{
		$a_members = array();
		foreach ($a_data as $s_key => $m_val) {
			$a_members[] = '"' . $s_key . '":' . $this->_Format($m_val);
		}
		return ("{" . implode(",",$a_members) . "}");
	}
}

function    CORS_Response()
{
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Max-Age: 36000');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
	header('Access-Control-Allow-Headers: X-Requested-With');
}

function    JSON_Result($s_result,$a_data = array())
{
	global $aGetVars;

	FMDebug("Sending JSON_Result: $s_result");
	$a_data["Result"] = $s_result;
	$json             = new JSON();
	$s_ret            = $json->MakeObject($a_data);
	CORS_Response();
	//
	// handle JSONP request
	//
	if (isset($aGetVars['callback']) && $aGetVars['callback'] != '') {
		header('Content-Type: text/javascript; charset=utf-8');
		$s_ret = $aGetVars['callback'] . "($s_ret);";
		FMDebug('JSONP request callback=' . $aGetVars['callback']);
	} else {
		header('Content-Encoding: utf-8');
		header('Content-Type: application/json; charset=utf-8');
	}
	FMDebug("JSON_Result output: " . $s_ret);
	echo $s_ret;
}

//
// JoinLines is just like "implode" except that it checks
// the end of each array for the separator already being
// there. This allows us to join a mixture of mail
// header lines (already terminated) with body lines.
// This logic works if HEAD_CRLF, for example, is the same
// as BODY_LF (i.e. both "\r\n") or if BODY_LF is the
// same as the last character in HEAD_CRLF (i.e.
// HEAD_CRLF = "\r\n" and BODY_LF = "\n").
// Other value combinations may break things.
//
function JoinLines($s_sep,$a_lines)
{
	$s_str = "";
	if (($i_sep_len = strlen($s_sep)) == 0)
		//
		// no separator
		//
	{
		return (implode("",$a_lines));
	}
	$n_lines = count($a_lines);
	for ($ii = 0 ; $ii < $n_lines ; $ii++) {
		$s_line = $a_lines[$ii];
		if (substr($s_line,-$i_sep_len) == $s_sep) {
			$s_str .= $s_line;
		} else {
			$s_str .= $s_line;
			//
			// don't append a separator to the last line
			//
			if ($ii < $n_lines - 1) {
				$s_str .= $s_sep;
			}
		}
	}
	return ($s_str);
}

//
// Re-orders an array of email headers into the
// order recommended by RFC822, section 4.1:
//      It  is  recommended that,  if  present,
//      headers be sent in the order "Return-
//      Path", "Received", "Date",  "From",  "Subject",
//      "Sender", "To", "cc", etc.
//
// Note that RFC822 is obsoleted by RFC2822 and
// the latter states that field order doesn't
// matter (except for some tracing fields).
// However, a FormMail user reported that Yahoo doesn't like
// email where the CC header appears before the From
// header. So, as always, we try to work with broken
// servers too...
//
// Returns an array indexed by the require numerical
// order.  Each element is an array containing the
// header value (name,value pair).
//
function OrderHeaders($a_headers)
{
	//
	// we list the headers we're responsible for
	// in the order suggested
	//
	$a_ordering        = array("From","Subject","To","Cc","Bcc","Reply-To");
	$a_ordered_headers = array();
	foreach ($a_ordering as $s_name) {
		if (isset($a_headers[$s_name])) {
			$a_ordered_headers[] = array($s_name => $a_headers[$s_name]);
			unset($a_headers[$s_name]);
		}
	}
	//
	// now add in the remaining headers
	//
	foreach ($a_headers as $s_name => $s_value) {
		$a_ordered_headers[] = array($s_name => $a_headers[$s_name]);
	}
	return ($a_ordered_headers);
}

//
// Makes a mail header field body "safe".
// This simply places a backslash in front of every double-quote.
// There's probably more we could do if required, but this
// attempts to provide the same protection that was in the first
// version of FormMail.  In that version, every incoming
// field had double-quotes replaced with single quotes.
// That processing is no longer performed, and this
// function is used to protect against potential attacks in
// header fields - not by replacing double quotes with single quotes,
// but by using the backslash "quoting" feature of RFC2822.
//
// This code could be improved by parsing the header and rewriting
// it to be valid, possibly removing junk.
//
// That's a lot of code, though!
//
function    SafeHeader($s_str)
{
	return (str_replace('"','\\"',$s_str));
}

//
// makes a string safe to put as words in a header
//
function    SafeHeaderWords($s_str)
{
	//
	// We zap various characters and replace them with a question mark.
	// Also, we don't handle quoted strings, which are valid words.
	//
	$s_specials = '()<>@,;:\\".[]'; // special characters defined by RFC822
	$s_str      = preg_replace('/[[:cntrl:]]+/',"?",$s_str); // zap all control chars
	$s_str      = preg_replace("/[" . preg_quote($s_specials,"/") . "]/","?",$s_str); // zap all specials
	return ($s_str);
}

//
// makes a string safe to put as a quoted string in a header
//
function    SafeHeaderQString($s_str)
{
	return (str_replace('"','\\"',
	                    str_replace("\\","\\\\",
	                                str_replace("\r"," ",
	                                            str_replace("\r\n"," ",$s_str)))));
}

//
// makes a string safe to put in a header comment
//
function    SafeHeaderComment($s_str)
{
	return (str_replace("(","\\(",
	                    str_replace(")","\\)",
	                                str_replace("\\","\\\\",
	                                            str_replace("\r"," ",
	                                                        str_replace("\r\n"," ",$s_str))))));
}

//
// makes a string safe to put in a header as an email address
//
function    SafeHeaderEmail($s_str)
{
	//
	// An email address is made up of local and domain parts
	// each of these is made up of "words" separated by "."
	// each "word" can be a sequence of characters excluding
	// specials, space and control characters OR it can be
	// a quoted string.
	//
	// The correct processing would be to completely
	// parse the address, strip junk, double-quote
	// words that need to be turned into quote strings,
	// and return a well-formed email address.
	//
	// That's a lot of code!
	//
	// So, instead, we opt for stripping out control characters.
	//
	$s_str = preg_replace('/[[:cntrl:]]+/',"",$s_str); // zap all control chars
	return ($s_str);
}

//
// Expands an array of mail headers into mail header lines.
//
function ExpandMailHeaders($a_headers,$b_fold = false)
{
	$s_hdrs            = "";
	$a_ordered_headers = OrderHeaders($a_headers);
	for ($ii = 0 ; $ii < count($a_ordered_headers) ; $ii++) {
		foreach ($a_ordered_headers[$ii] as $s_name => $s_value) {
			if ($s_name != "") {
				if ($s_hdrs != "") {
					$s_hdrs .= Settings::get('HEAD_CRLF');
				}
				if ($b_fold) {
					$s_hdrs .= HeaderFolding($s_name . ": " . $s_value);
				} else {
					$s_hdrs .= $s_name . ": " . $s_value;
				}
			}
		}
	}
	//FMDebug("Headers are: $s_hdrs");
	return ($s_hdrs);
}

//
// Expands an array of mail headers into an array containing header lines.
//
function ExpandMailHeadersArray($a_headers)
{
	$a_hdrs            = array();
	$a_ordered_headers = OrderHeaders($a_headers);
	for ($ii = 0 ; $ii < count($a_ordered_headers) ; $ii++) {
		foreach ($a_ordered_headers[$ii] as $s_name => $s_value) {
			if ($s_name != "") {
				$a_hdrs[] = $s_name . ": " . $s_value . Settings::get('HEAD_CRLF');
			}
		}
	}
	return ($a_hdrs);
}

//
// Low-level email send function; either calls PHP's mail function
// or uses the PEAR Mail object.
// NOTE: for some errors, there's no point trying to email
// an alert message!  So, in these cases, we just display the error to
// the user.
// $s_options are ignored for PEAR sending.
//
function DoMail($s_to,$s_subject,$s_mesg,$a_headers,$s_options)
{

	//
	// Encode the subject line.
	// Ideally, we want to encode the relevant parts of To, From, Cc,
	// Reply-To, and this is the right place to do it.
	// However, it's another 1000 lines of code!
	// So, we must compromise the code quality because of this cost.
	// We encode subject here, and we encode the From line where it's
	// created.  The rest remain for a future version where code size
	// can be controlled.
	//
	$s_subject = EncodeHeaderText($s_subject);
	if (!Settings::isEmpty('PEAR_SMTP_HOST')) {
		//
		// Note that PEAR Mail seems to take responsibility for header line folding
		//
		require_once("Mail.php");

		$a_params = array("host" => Settings::get('PEAR_SMTP_HOST'),
		                  "port" => Settings::get('PEAR_SMTP_PORT')
		);
		if (!Settings::isEmpty('PEAR_SMTP_USER')) {
			$a_params["auth"]     = TRUE;
			$a_params["username"] = Settings::get('PEAR_SMTP_USER');
			$a_params["password"] = Settings::get('PEAR_SMTP_PWD');
		}
		$mailer = Mail::factory("smtp",$a_params);
		if (!is_object($mailer)) {
			ShowError("pear_error",GetMessage(MSG_PEAR_OBJ),FALSE,FALSE);
			exit;
		}
		if (strtolower(get_class($mailer)) === 'pear_error') {
			ShowError("pear_error",$mailer->getMessage(),FALSE,FALSE);
			exit;
		}
		if (!isset($a_headers['To']) && !isset($a_headers['to'])) {
			$a_headers['To'] = SafeHeader($s_to);
		}
		if (!isset($a_headers['Subject']) && !isset($a_headers['subject'])) {
			$a_headers['Subject'] = SafeHeader($s_subject);
		}
		$res = $mailer->send($s_to,$a_headers,$s_mesg);
		if ($res === TRUE) {
			return (TRUE);
		}

		global $aAlertInfo;

		$aAlertInfo[] = GetMessage(MSG_PEAR_ERROR,array("MSG" => $res->getMessage()));
		return (FALSE);
	} else {
		//$s_subject = HeaderFolding($s_subject,RFCLINELEN-10);   // "Subject: " is about 10 chars
		//
		// Notes from Feb 2010....
		//
		// PHP's mail function (tested in version 5.2.6) does folding of the
		// To line and the Subject line.
		// If we do it, then things break.
		//
		// This area is quite confusing.  It's not clear whether the script
		// should be folding header lines or whether the MTA should do it.
		// We *do know* (as stated above) that folding To and Subject breaks things.
		//
		// But folding other header lines properly, seems to be OK.
		//
		// However, for years FormMail never did header line folding (except for the
		// soft line breaks inserted by the quoted_printable_encode function we had used),
		// and we didn't seem to get any reports of breakage (except for problems
		// with the quoted_printable_encode soft line breaks!).
		//
		// So, even though we've implemented all the code for header line folding,
		// we'll not use it.
		// No header line folding will be performed in version 8.22 onwards.
		//
		if ($s_options !== "") {
			return (mail($s_to,$s_subject,$s_mesg,ExpandMailHeaders($a_headers),$s_options));
		} else {
			return (mail($s_to,$s_subject,$s_mesg,ExpandMailHeaders($a_headers)));
		}
	}
}

//
// Send an email
//
function SendCheckedMail($to,$subject,$mesg,$sender,$a_headers = array())
{

	$b_f_option    = false;
	$b_form_option = IsMailOptionSet("SendMailFOption"); // this is superseded, but still supported
	if (Settings::get('SENDMAIL_F_OPTION') || $b_form_option) {
		if (empty($sender)) {
			//
			// SENDMAIL_F_OPTION with no sender is silently ignored
			//
			if ($b_form_option) {
				//
				// form has specified SendMailFOption, but there's no
				// sender address
				//
				static $b_in_here = false;
				global $SERVER;

				if (!$b_in_here) // prevent infinite recursion
				{
					$b_in_here = true;
					SendAlert(GetMessage(MSG_NO_FOPT_ADDR));
					$b_in_here = false;
				}
				//
				// if there's no from address, create a dummy one
				//
				$sender            = "dummy@" . (isset($SERVER) ? $SERVER : "UnknownServer");
				$a_headers['From'] = $sender;
				$b_f_option        = true;
			}
		} else {
			$b_f_option = true;
		}
	}
	if (Settings::get('INI_SET_FROM') && !empty($sender)) {
		ini_set('sendmail_from',$sender);
	}

	return (DoMail($to,$subject,$mesg,$a_headers,($b_f_option ? "-f$sender" : "")));
}

//
// Send an alert email
//
function SendAlert($s_error,$b_filter = true,$b_non_error = false)
{
	global $SPECIAL_VALUES,$FORMATTED_INPUT,$aServerVars,$aStrippedFormVars;
	global $aAlertInfo,$aCleanedValues,$aFieldOrder,$sHTMLCharSet;

	$s_error      = str_replace("\n",Settings::get('BODY_LF'),$s_error);
	$b_got_filter = GetFilterSpec($s_filter_name,$a_filter_list);

	//
	// if there is a filter specified and we're not sending the alert
	// through the filter, don't show the user's data.  This is
	// on the assumption that the filter is an encryption program; so,
	// we don't want to send the user's data in clear text inside the
	// alerts.
	//
	$b_show_data = true;
	if ($b_got_filter && !$b_filter) {
		$b_show_data = false;
	}

	$s_form_subject = $s_alert_to = "";
	$b_check        = true;
	//
	// might be too early to have $SPECIAL_VALUES set, so
	// look in the form vars too
	//
	if (isset($SPECIAL_VALUES["alert_to"])) {
		$s_alert_to = trim($SPECIAL_VALUES["alert_to"]);
	}
	if (empty($s_alert_to) && isset($aStrippedFormVars["alert_to"])) {
		$s_alert_to = trim($aStrippedFormVars["alert_to"]);
	}

	if (isset($SPECIAL_VALUES["subject"])) {
		$s_form_subject = trim($SPECIAL_VALUES["subject"]);
	}
	if (empty($s_form_subject) && isset($aStrippedFormVars["subject"])) {
		$s_form_subject = trim($aStrippedFormVars["subject"]);
	}

	if (empty($s_alert_to)) {
		$s_alert_to = Settings::get('DEF_ALERT');
		$b_check    = false;
	}
	if (!empty($s_alert_to)) {
		$s_from_addr = $s_from = "";
		$a_headers   = array();
		if (!Settings::isEmpty('FROM_USER')) {
			if (Settings::get('FROM_USER') != "NONE") {
				$a_headers['From'] = Settings::get('FROM_USER');
				$s_from_addr       = Settings::get('FROM_USER');
				$s_from            = "From: $s_from_addr";
			}
		} else {
			global $SERVER;

			$s_from_addr       = "FormMail@" . $SERVER;
			$a_headers['From'] = $s_from_addr;
			$s_from            = "From: $s_from_addr";
		}
		$s_mesg = "To: " . UnMangle($s_alert_to) . Settings::get('BODY_LF');
		//
		// if a language pack has been included, a lot of error messages
		// may need the character set to be provided.
		// If that's available from the language pack, use it,
		// otherwise, if it's a mail_option, use it from there.
		//
		$s_charset = "";
		if (isset($sHTMLCharSet) && $sHTMLCharSet !== "") {
			$s_charset = $sHTMLCharSet;
		} else {
			if (IsMailOptionSet("CharSet")) {
				$s_charset = GetMailOption("CharSet");
			}
		}

		//
		// Alerts are plain text emails, so convert any HTML entities
		// back to their original characters.  Note, this will only work on PHP
		// version 4.3.0 and above.
		//
		if (function_exists("html_entity_decode")) {
			$s_error = @html_entity_decode($s_error,ENT_COMPAT,$s_charset);
		}

		if ($s_charset !== "") {
			$a_headers['Content-Type'] = SafeHeader("text/plain; charset=$s_charset");
		}

		if (!empty($s_from)) {
			$s_mesg .= $s_from . Settings::get('BODY_LF');
		}
		$s_mesg .= Settings::get('BODY_LF');
		if (count($aAlertInfo) > 0) {
			if ($b_show_data) {
				$s_error .= Settings::get('BODY_LF') . GetMessage(MSG_MORE_INFO) . Settings::get('BODY_LF');
				$s_error .= implode(Settings::get('BODY_LF'),$aAlertInfo);
			} else {
				$s_error .= Settings::get('BODY_LF') . GetMessage(MSG_INFO_STOPPED) . Settings::get('BODY_LF');
			}
		}
		//
		// some fields aren't security issues - show those in the alert
		//
		$a_safe_fields = array(
			"email: " . $SPECIAL_VALUES["email"],
			"realname: " . $SPECIAL_VALUES["realname"],
		);
		$s_safe_data   = implode(Settings::get('BODY_LF'),$a_safe_fields);

		if ($b_non_error) {
			$s_preamble = $s_error . Settings::get('BODY_LF') . Settings::get('BODY_LF');
			$s_mesg .= $s_preamble;
			$s_subj = GetMessage(MSG_FM_ALERT);
			if (!empty($s_form_subject)) {
				$s_subj .= " ($s_form_subject)";
			}
		} else {
			$s_preamble = GetMessage(MSG_FM_ERROR_LINE) . Settings::get('BODY_LF') .
			              $s_error . Settings::get('BODY_LF') . Settings::get('BODY_LF');
			$s_mesg .= $s_preamble;
			$s_subj = GetMessage(MSG_FM_ERROR);
			if (!empty($s_form_subject)) {
				$s_subj .= " ($s_form_subject)";
			}
			$s_mesg .= $s_safe_data;
			$s_mesg .= Settings::get('BODY_LF') . Settings::get('BODY_LF');
			if ($b_show_data) {
				$s_mesg .= implode(Settings::get('BODY_LF'),$FORMATTED_INPUT);
			} else {
				$s_mesg .= GetMessage(MSG_USERDATA_STOPPED);
			}
		}

		/*
             * We only need to filter the form fields if the filter that
             * is specified is an encrypting filter.
             */
		if ($b_filter && $b_got_filter &&
		    IsFilterAttribSet($SPECIAL_VALUES["filter"],"Encrypts")
		) {
			$s_new_mesg = $s_preamble . $s_safe_data;
			$s_new_mesg .= Settings::get('BODY_LF') . Settings::get('BODY_LF');

			if ($a_filter_list !== false) {
				//
				// just filter the critical fields
				//
				list($s_unfiltered,$s_filtered_results) =
					GetFilteredOutput($aFieldOrder,$aCleanedValues,
					                  $s_filter_name,$a_filter_list);
				$s_new_mesg .= $s_unfiltered;
			} else {
				//
				// filter everything
				//
				$s_filtered_results = Filter($s_filter_name,$s_mesg);
			}
			$s_new_mesg .= GetMessage(MSG_FILTERED,array("FILTER" => $s_filter_name)) .
			               Settings::get('BODY_LF') . Settings::get('BODY_LF') .
			               $s_filtered_results;
			$s_mesg = $s_new_mesg;
		}
		$s_mesg .= Settings::get('BODY_LF');

		if (isset($aServerVars['HTTP_REFERER'])) {
			$s_mesg .= "Referring page was " . $aServerVars['HTTP_REFERER'];
		} elseif (isset($SPECIAL_VALUES['this_form']) && $SPECIAL_VALUES['this_form'] !== "") {
			$s_mesg .= "Referring form was " . $SPECIAL_VALUES['this_form'];
		}

		$s_mesg .= Settings::get('BODY_LF');

		if (isset($aServerVars['SERVER_NAME'])) {
			$s_mesg .= "SERVER_NAME was " . $aServerVars['SERVER_NAME'] . Settings::get('BODY_LF');
		}
		if (isset($aServerVars['REQUEST_URI'])) {
			$s_mesg .= "REQUEST_URI was " . $aServerVars['REQUEST_URI'] . Settings::get('BODY_LF');
		}

		$s_mesg .= Settings::get('BODY_LF');

		if (isset($aServerVars['REMOTE_ADDR'])) {
			$s_mesg .= "User IP address was " . $aServerVars['REMOTE_ADDR'] . Settings::get('BODY_LF');
		}
		if (isset($aServerVars['HTTP_USER_AGENT'])) {
			$s_mesg .= "User agent was " . $aServerVars['HTTP_USER_AGENT'] . Settings::get('BODY_LF');
		}

		if ($b_check) {
			if (CheckEmailAddress($s_alert_to,$s_valid,$s_invalid)) {
				return (SendCheckedMail($s_valid,$s_subj,$s_mesg,$s_from_addr,$a_headers));
			}
		} else {
			return (SendCheckedMail($s_alert_to,$s_subj,$s_mesg,$s_from_addr,$a_headers));
		}
	}
	return (false);
}

//
// Read the lines in a file and return an array.
// Each line is stripped of line termination characters.
//
function ReadLines($fp)
{
	$a_lines = array();
	while (!feof($fp)) {
		$s_line = fgets($fp,4096);
		//
		// strip carriage returns and line feeds
		//
		$s_line    = str_replace("\r","",$s_line);
		$s_line    = str_replace("\n","",$s_line);
		$a_lines[] = $s_line;
	}
	return ($a_lines);
}

//
// Open a URL and return the data from it as a string or array of lines.
// Returns false on failure ($s_error has the error string)
//
function GetURL($s_url,&$s_error,$b_ret_lines = false,$n_depth = 0)
{
	global $php_errormsg,$aServerVars,$sUserAgent,$ExecEnv;

	//
	// open the URL with the same session as we have
	//
	if ($ExecEnv->allowSessionURL()) {
		if (session_id() !== "") {
			$s_url = AddURLParams($s_url,session_name() . "=" . urlencode(session_id()));
		}
		if (defined("SID")) {
			$s_url = AddURLParams($s_url,SID);
		}
	}

	$http_get = new HTTPGet($s_url);

	//
	// Determine authentication requirements
	//
	if (Settings::get('AUTHENTICATE') !== "" || Settings::get('AUTH_USER') !== "" || Settings::get('AUTH_PW') !== "") {
		if (Settings::get('AUTHENTICATE') === "") {
			$http_get->SetAuthentication("Basic",Settings::get('AUTH_USER'),Settings::get('AUTH_PW'));
		} else {
			$http_get->SetAuthenticationLine(Settings::get('AUTHENTICATE'));
		}
	} else {
		$a_parts = $http_get->GetURLSplit();
		if (isset($a_parts["user"]) || isset($a_parts["pass"])) {
			$s_auth_user = isset($a_parts["user"]) ? $a_parts["user"] : "";
			$s_auth_pass = isset($a_parts["pass"]) ? $a_parts["pass"] : "";
		} else {
			$s_auth_type = isset($aServerVars["PHP_AUTH_TYPE"]) ? $aServerVars["PHP_AUTH_TYPE"] : "";
			$s_auth_user = isset($aServerVars["PHP_AUTH_USER"]) ? $aServerVars["PHP_AUTH_USER"] : "";
			$s_auth_pass = isset($aServerVars["PHP_AUTH_PW"]) ? $aServerVars["PHP_AUTH_PW"] : "";
		}
		if (!isset($s_auth_type) || $s_auth_type === "") {
			$s_auth_type = "Basic";
		}
		if ($s_auth_user !== "" || $s_auth_pass !== "") {
			$http_get->SetAuthentication($s_auth_type,$s_auth_user,$s_auth_pass);
		}
	}
	//
	// set the user agent
	//
	$http_get->SetAgent($sUserAgent);
	//
	// resolve the name now so the DNS cache can be written to the session
	//
	$http_get->Resolve();

	//
	// Since we might be opening a URL within the same session, we can
	// get locks.  So, close the session for writing to prevent this.
	//
	$b_closed = false;
	if (function_exists('session_write_close')) {
		session_write_close();
		$b_closed = true;
		//ob_flush();             // this prevents automatic redirects if $TEMPLATEURL
		// is in use and JavaScript is switched off
	}

	$m_buf = FALSE;

	//FMDebug("Begin read");
	if (($a_lines = $http_get->Read()) === FALSE) {
		$http_get->Close();
		//
		// get the error code and send the appropriate alert
		//
		list($i_error,$i_sys_err,$s_sys_msg) = $http_get->GetError();
		switch ($i_error) {
			case $http_get->nErrParse:
				$s_error = GetMessage(MSG_URL_PARSE);
				break;
			case $http_get->nErrScheme:
				$a_parts = $http_get->GetURLSplit();
				$s_error = GetMessage(MSG_URL_SCHEME,array("SCHEME" => $a_parts["scheme"]));
				break;
			default:
				$s_error = GetMessage(MSG_SOCKET,
				                      array("ERRNO"  => $i_sys_err,
				                            "ERRSTR" => $s_sys_msg,
				                            "PHPERR" => isset($php_errormsg) ? $php_errormsg : ""
				                      ));
				break;
		}
	} else {
		$http_get->Close();
		//
		// check the HTTP response for actual status.  Anything outside
		// 200-299 is a failure, but we also handle redirects.
		//
		list($i_http_code,$s_http_status) = $http_get->GetHTTPStatus();

		if ($i_http_code < 200 || $i_http_code > 299) {
			switch ($i_http_code) {
				case 300: // multiple choices (we'll take the first)
				case 301: // moved permanently
				case 302: // found
				case 303: // see other
				case 307: // temporary redirect
					//
					// a "location" header must be present for us to continue
					// In the case of infinite redirects, we need to stop.
					// So, we limit to a maximum of 10 redirects.
					//
					if ($n_depth < 10) {
						if (($s_location = $http_get->FindHeader("location")) !== false) {
							FMDebug("Redirect from '$s_url' to '$s_location'");
							$m_buf    = GetURL($s_location,$s_error,$b_ret_lines,$n_depth + 1);
							$b_closed = false;
							break;
						}
						FMDebug("Redirect FAILED - no location header");
					} else {
						FMDebug("Redirect FAILED depth=$n_depth");
					}
				// FALL THRU
				default:
					$s_error = GetMessage(MSG_GETURL_OPEN,array("STATUS" => $s_http_status,"URL" => $s_url));
					break;
			}
		} elseif ($b_ret_lines) {
			$m_buf = $a_lines;
		} else
			//
			// return lines as one big string buffer
			//
		{
			$m_buf = implode("",$a_lines);
		}
	}
	//
	// re-open our session
	//
	if ($b_closed) {
		session_start();
	}

	return ($m_buf);
}

//
// Write to the debug log if it exists and is writable.
//
function    FMDebug($s_mesg)
{
	static $fDebug = NULL;

	if (!isset($fDebug)) {
		$fDebug    = false; // only initialize once
		$s_db_file = "fmdebug.log"; // look for debug file in current directory
		//
		// we only open an existing file - we don't create one
		//
		if (file_exists($s_db_file)) {
			if (($fDebug = fopen($s_db_file,"a")) === false) {
				return;
			}
		}
	}
	if ($fDebug !== false) {
		fwrite($fDebug,date('r') . ": " . $s_mesg . "\n");
		fflush($fDebug);
	}
}

/*
 * Class:       NetIO
 * Description:     
 *  A class to provide internet input/output capabilities.
 *  Use as a base class for more specific functions.
 */

class   NetIO
{
	var $_sHost;
	var $_iPort;
	var $_sPrefix;

	var $_iConnTimeout;
	var $_fSock;

	var $_aIPs;

	var $_iError = 0;
	var $_iSysErr;
	var $_sSysMesg;

	var $nErrInit = -1; // not initialized
	var $nErrRead = -2; // read error
	var $nErrWrite = -3; // write error
	var $nErrWriteShort = -4; // failed to write all bytes

	var $nErrSocket = -100; // error in socket open

	function    NetIO($s_host = NULL,$i_port = NULL,$s_prefix = "")
	{
		if (isset($s_host)) {
			$this->_sHost = $s_host;
		}
		if (isset($i_port)) {
			$this->_iPort = $i_port;
		}
		$this->_sPrefix      = $s_prefix;
		$this->_iConnTimeout = 30;
		$this->_iSysErr      = 0;
		$this->_sSysMesg     = "";
	}

	function    _SetError($i_error,$i_sys_err = 0,$s_sys_mesg = "")
	{
		$this->_iError   = $i_error;
		$this->_iSysErr  = $i_sys_err;
		$this->_sSysMesg = $s_sys_mesg;
		return (FALSE);
	}

	function    IsError()
	{
		return ($this->_iError != 0 ? TRUE : FALSE);
	}

	function    ClearError()
	{
		$this->_SetError(0);
	}

	function    GetError()
	{
		return (array($this->_iError,$this->_iSysErr,$this->_sSysMesg));
	}

	function    SetHost($s_host)
	{
		$this->_sHost = $s_host;
	}

	function    SetPort($i_port)
	{
		$this->_iPort = $i_port;
	}

	function    SetConnectionTimeout($i_secs)
	{
		$this->_iConnTimeout = $i_secs;
	}

	function    SetPrefix($s_prefix)
	{
		$this->_sPrefix = $s_prefix;
	}

	function    GetHost()
	{
		return (isset($this->_sHost) ? $this->_sHost : "");
	}

	function    GetPort()
	{
		return (isset($this->_iPort) ? $this->_iPort : 0);
	}

	function    GetPrefix()
	{
		return ($this->_sPrefix);
	}

	function    GetConnectionTimeout()
	{
		return ($this->_iConnTimeout);
	}

	function    _CacheIt()
	{
		FMDebug("Caching " . implode(",",$this->_aIPs));
		if (IsSetSession("FormNetIODNSCache")) {
			$a_cache = GetSession("FormNetIODNSCache");
		} else {
			$a_cache = array();
		}
		$a_cache[$this->_sHost] = $this->_aIPs;
		SetSession("FormNetIODNSCache",$a_cache);
	}

	/*
     * Some versions of PHP seem to have a major slowdown when resolving
     * names with gethostbyname (5 seconds with PHP 4.3.9).
     * So, in the case of multi-page forms using MULTIFORMURL, we get a big speed up
     * by caching the IP address of the server.
     */
	function    _CheckCache()
	{
		if (!IsSetSession("FormNetIODNSCache")) {
			return (FALSE);
		}
		$a_cache = GetSession("FormNetIODNSCache");
		if (!is_array($a_cache) || !isset($a_cache[$this->_sHost]) || !is_array($a_cache[$this->_sHost])) {
			return (FALSE);
		}
		$this->_aIPs = $a_cache[$this->_sHost];
		return (TRUE);
	}

	function    Resolve()
	{
		$this->ClearError();
		if (!isset($this->_sHost)) {
			return ($this->_SetError($this->nErrInit));
		}
		if ($this->_CheckCache()) {
			return (TRUE);
		}
		FMDebug("Start resolve of " . $this->_sHost);
		//
		// if host is an actual IP address, then it is returned unchanged, which is good!
		//
		if (($a_ip_list = gethostbynamel($this->_sHost)) === FALSE) {
			FMDebug("Resolve failed");
			return ($this->_SetError($this->nErrInit,0,
			                         GetMessage(MSG_RESOLVE,array("NAME" => $this->_sHost))));
		}
		FMDebug("Done resolve: " . implode(",",$a_ip_list));
		$this->_aIPs = $a_ip_list;
		$this->_CacheIt();
		return (TRUE);
	}

	function    _SSLOpen($s_ip,&$errno,&$errstr,$i_timeout)
	{
		FMDebug("Using _SSLOpen (stream_socket_client), SNI, host=" . $this->GetHost());
		$context = stream_context_create();
		$result  = stream_context_set_option($context,'ssl','verify_host',true);
		$result  = stream_context_set_option($context,'ssl','verify_peer',false);
		$result  = stream_context_set_option($context,'ssl','allow_self_signed',true);
		$result  = stream_context_set_option($context,'ssl','SNI_enabled',true);
		$result  = stream_context_set_option($context,'ssl','SNI_server_name',$this->GetHost());
		//
		// Note that even if SNI fails, the socket will still open, but the
		// web server should send a 400 error.
		//
		return (stream_socket_client($this->GetPrefix() . $s_ip . ":" . $this->GetPort(),
		                             $errno,$errstr,$i_timeout,STREAM_CLIENT_CONNECT,$context));
	}

	function    Open()
	{
		$this->ClearError();
		if (!isset($this->_sHost) || !isset($this->_iPort)) {
			return ($this->_SetError($this->nErrInit));
		}
		if (!$this->Resolve()) {
			return (FALSE);
		}
		FMDebug("Starting socket open");
		$f_sock = FALSE;
		//
		// Now, run through the list of IPs until we find one that connects.
		// However, this can cause problems with SNI in SSL/TLS connections.
		// If there is only one IP address, use the host name.
		// Otherwise, if we can specify SNI and it's an SSL connection
		// use streams, otherwise try each IP individually.
		//
		if (count($this->_aIPs) == 1) {
			FMDebug("Trying host " . $this->_sHost . ", timeout " . $this->GetConnectionTimeout());
			$f_sock = @fsockopen($this->GetPrefix() . $this->_sHost,$this->GetPort(),
			                     $errno,$errstr,$this->GetConnectionTimeout());
		} else {
			foreach ($this->_aIPs as $s_ip) {
				global $ExecEnv;

				FMDebug("Trying IP $s_ip, timeout " . $this->GetConnectionTimeout());
				if ($ExecEnv->IsPHPAtLeast("5.3.2") && substr($this->GetPrefix(),0,3) == "ssl") {
					if (($f_sock = $this->_SSLOpen($s_ip,$errno,$errstr,
					                               $this->GetConnectionTimeout())) !== FALSE
					) {
						break;
					}
				} elseif (($f_sock = @fsockopen($this->GetPrefix() . $s_ip,$this->GetPort(),
				                                $errno,$errstr,$this->GetConnectionTimeout())) !== FALSE
				) {
					break;
				}
			}
		}
		if ($f_sock === FALSE) {
			FMDebug("open failed: $errno $errstr");
			return ($this->_SetError($this->nErrSocket,$errno,$errstr));
		}
		$this->_fSock = $f_sock;
		FMDebug("Done socket open");
		return (TRUE);
	}

	function    Read()
	{
		$this->ClearError();
		$a_lines = array();
		while (($s_line = fgets($this->_fSock)) !== FALSE) {
			$a_lines[] = $s_line;
		}
		FMDebug("Read " . count($a_lines) . " lines");
		return ($a_lines);
	}

	function    Write($s_str,$b_flush = TRUE)
	{
		$this->ClearError();
		if (!isset($this->_fSock)) {
			return ($this->_SetError($this->nErrInit));
		}
		if (($n_write = fwrite($this->_fSock,$s_str)) === FALSE) {
			return ($this->_SetError($this->nErrWrite));
		}
		if ($n_write != strlen($s_str)) {
			return ($this->_SetError($this->nErrWriteShort));
		}
		if ($b_flush) {
			if (fflush($this->_fSock) === FALSE) {
				return ($this->_SetError($this->nErrWriteShort));
			}
		}
		return (TRUE);
	}

	function    Close()
	{
		if (isset($this->_fSock)) {
			fclose($this->_fSock);
			unset($this->_fSock);
		}
	}
}

/*
 * Class:       HTTPGet
 * Description:     
 *  A class that implements HTTP GET method.
 */

class   HTTPGet extends NetIO
{
	var $_sURL;
	var $_aURLSplit;

	var $_sRequest;
	var $_aResponse;
	var $_aRespHeaders;

	var $_sAuthLine;
	var $_sAuthType;
	var $_sAuthUser;
	var $_sAuthPass;

	var $_sAgent;

	var $nErrParse = -1000; // failed to parse URL
	var $nErrScheme = -1001; // unsupported URL scheme

	function    HTTPGet($s_url = "")
	{
		NetIO::NetIO();
		$this->_aURLSplit = array();
		if (($this->_sURL = $s_url) !== "") {
			$this->_SplitURL();
		}
	}

	function    _SplitURL()
	{
		FMDebug("URL: " . $this->_sURL);
		if (($this->_aURLSplit = parse_url($this->_sURL)) === FALSE) {
			$this->_aURLSplit = array();
			return ($this->_SetError($this->nErrParse));
		}
		return (TRUE);
	}

	function    GetURLSplit()
	{
		return ($this->_aURLSplit);
	}

	function    SetURL($s_url)
	{
		$this->_aURLSplit = array();
		$this->_sURL      = $s_url;
		return ($this->_SplitURL());
	}

	function    _Init()
	{
		if (!isset($this->_aURLSplit["host"])) {
			return ($this->_SetError($this->nErrInit));
		}
		$this->SetHost($this->_aURLSplit["host"]);
		$i_port    = 80;
		$b_use_ssl = false;
		if (isset($this->_aURLSplit["scheme"])) {
			switch (strtolower($this->_aURLSplit["scheme"])) {
				case "http":
					break;
				case "https":
					$b_use_ssl = true;
					$i_port    = 443;
					break;
				default:
					return ($this->_SetError($this->nErrScheme));
			}
		}
		if (isset($this->_aURLSplit["port"])) {
			$i_port = $this->_aURLSplit["port"];
		}
		if ($b_use_ssl)
			//
			// we require ssl:// for port 443
			//
		{
			$this->SetPrefix("ssl://");
		}
		$this->SetPort($i_port);
		return (TRUE);
	}

	function    _SendRequest()
	{
		$this->_PrepareRequest();
		return (parent::Write($this->_sRequest));
	}

	function    _PrepareRequest($s_method = 'GET')
	{
		FMDebug("Path: " . $this->_aURLSplit["path"]);
		if (!isset($this->_aURLSplit["path"]) || $this->_aURLSplit["path"] === "") {
			$s_path = "/";
		} // default path
		else {
			$s_path = $this->_aURLSplit["path"];
		}
		if (isset($this->_aURLSplit["query"])) {
			//
			// add the query to the path
			// Note that parse_url decodes the query string (urldecode), so
			// we need to split it into its component parameters
			// are re-encode their values.  Calling urlencode($this->_aURLSplit["query"])
			// encodes the '=' between parameters and this breaks things.
			//
			$a_params = explode('&',$this->_aURLSplit["query"]);
			foreach ($a_params as $i_idx => $s_param) {
				if (($i_pos = strpos($s_param,"=")) === false) {
					$a_params[$i_idx] = urlencode($s_param);
				} else {
					$a_params[$i_idx] = substr($s_param,0,$i_pos) . '=' .
					                    urlencode(substr($s_param,$i_pos + 1));
				}
			}
			$s_path .= "?" . implode('&',$a_params);
		}
		//
		// add the fragment to the path.
		//
		if (isset($this->_aURLSplit["fragment"])) {
			$s_path .= '#' . urlencode($this->_aURLSplit["fragment"]);
		}
		//
		// build the request
		//
		$s_req = $s_method . " $s_path HTTP/1.0\r\n";
		//
		// Add authentication
		//
		if (isset($this->_sAuthLine)) {
			$s_req .= "Authorization: $this->_sAuthLine\r\n";
		} elseif (isset($this->_sAuthType)) {
			$s_req .= "Authorization: " . $this->_sAuthType . " " .
			          base64_encode($this->_sAuthUser . ":" . $this->_sAuthPass) . "\r\n";
		}
		//
		// Specify the host name
		//
		$s_req .= "Host: " . $this->GetHost() . "\r\n";
		//
		// Specify the user agent
		//
		if (isset($this->_sAgent)) {
			$s_req .= "User-Agent: " . $this->_sAgent . "\r\n";
		}
		//
		// Accept any output
		//
		$s_req .= "Accept: */*\r\n";
		$s_req .= $this->_AdditionalHeaders();
		//
		// End of request headers
		//
		$s_req .= "\r\n";
		$this->_sRequest = $s_req;
	}

	function    _AdditionalHeaders()
	{
		return ('');
	}

	function    _GetResponse()
	{
		FMDebug("Reading");
		if (($a_lines = parent::Read()) === FALSE) {
			return (FALSE);
		}

		$this->_aRespHeaders = $this->_aResponse = array();
		$b_body              = FALSE;
		for ($ii = 0 ; $ii < count($a_lines) ; $ii++) {
			if ($b_body) {
				//FMDebug("Body line: ".rtrim($a_lines[$ii]));
				$this->_aResponse[] = $a_lines[$ii];
			} elseif ($a_lines[$ii] == "\r\n" || $a_lines[$ii] == "\n") {
				$b_body = TRUE;
			} else {
				//FMDebug("Header line: ".rtrim($a_lines[$ii]));
				$this->_aRespHeaders[] = $a_lines[$ii];
			}
		}
		return (TRUE);
	}

	function    GetResponseHeaders()
	{
		return ($this->_aRespHeaders);
	}

	function    FindHeader($s_name)
	{
		$s_name = strtolower($s_name);
		$i_len  = strlen($s_name);
		for ($ii = 0 ; $ii < count($this->_aRespHeaders) ; $ii++) {
			$s_line = $this->_aRespHeaders[$ii];
			if (($s_hdr = substr($s_line,0,$i_len)) !== false) {
				$s_hdr = strtolower($s_hdr);
				if ($s_hdr === $s_name && substr($s_line,$i_len,1) === ":") {
					return (trim(substr($s_line,$i_len + 1)));
				}
			}
		}
		return (false);
	}

	function    GetHTTPStatus()
	{
		$i_http_code = 0;
		$s_status    = "";
		for ($ii = 0 ; $ii < count($this->_aRespHeaders) ; $ii++) {
			$s_line = $this->_aRespHeaders[$ii];
			if (substr($s_line,0,4) == "HTTP") {
				$i_pos     = strpos($s_line," ");
				$s_status  = substr($s_line,$i_pos + 1);
				$i_end_pos = strpos($s_status," ");
				if ($i_end_pos === false) {
					$i_end_pos = strlen($s_status);
				}
				$i_http_code = (int)substr($s_status,0,$i_end_pos);
			}
		}
		return (array($i_http_code,$s_status));
	}

	function    Resolve()
	{
		if (!$this->_Init()) {
			return (FALSE);
		}
		return (parent::Resolve());
	}

	function    Read()
	{
		if (!$this->_Init()) {
			return (FALSE);
		}
		FMDebug("Init done");
		if (!$this->Open()) {
			return (FALSE);
		}
		FMDebug("Open done");
		if (!$this->_SendRequest()) {
			return (FALSE);
		}
		FMDebug("Send done");
		if (!$this->_GetResponse()) {
			return (FALSE);
		}
		FMDebug("Get done");
		$this->Close();
		return ($this->_aResponse);
	}

	function    SetAuthenticationLine($s_auth)
	{
		$this->_sAuthLine = $s_auth;
	}

	function    SetAuthentication($s_type,$s_user,$s_pass)
	{
		$this->_sAuthType = $s_type;
		$this->_sAuthUser = $s_user;
		$this->_sAuthPass = $s_pass;
	}

	function    SetAgent($s_agent)
	{
		$this->_sAgent = $s_agent;
	}
}

/*
 * Class:       HTTPPost
 * Description:     
 *  A class that implements HTTP POST method.
 */

class   HTTPPost extends HTTPGet
{
	var $_sPostData; /* data to POST */

	function    HTTPPost($s_url = "")
	{
		$this->_sPostData = '';
		HTTPGet::HTTPGet($s_url);
	}

	function    _SendRequest()
	{
		$this->_PrepareRequest();
		return (NetIO::Write($this->_sRequest));
	}

	function    _PrepareRequest($s_method = 'POST')
	{
		parent::_PrepareRequest($s_method);
		$this->_AddData();
	}

	function    _AdditionalHeaders()
	{
		//
		// we don't handle file uploads yet
		//
		$a_hdrs = array(
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($this->_sPostData),
		);
		return (implode("\r\n",$a_hdrs));
	}

	function    _AddData()
	{
		$this->_sRequest .= "\r\n"; // blank line after headers
		$this->_sRequest .= $this->_sPostData;
	}

	function    _EncodeData($a_fields)
	{
		$s_data = '';
		foreach ($a_fields as $s_name => $s_value) {
			if ($s_data != '') {
				$s_data .= '&';
			}
			$s_data .= urlencode($s_name) . '=' . urlencode($s_value);
		}
		return ($s_data);
	}

	function    Post($a_fields)
	{
		//
		// we don't handle file uploads yet
		//
		$this->_sPostData = $this->_EncodeData($a_fields);
		return ($this->Read());
	}
}

//
// Load a template file into a string.
//
function LoadTemplate($s_name,$s_dir,$s_url,$b_ret_lines = false)
{
	global $php_errormsg;

	$s_buf   = "";
	$a_lines = array();
	if (!empty($s_dir)) {
		$s_name = "$s_dir/" . basename($s_name);
		@       $fp = fopen($s_name,"r");
		if ($fp === false) {
			SendAlert(GetMessage(MSG_OPEN_TEMPLATE,array("NAME"  => $s_name,
			                                             "ERROR" => CheckString($php_errormsg)
			)));
			return (false);
		}
		if ($b_ret_lines) {
			$a_lines = ReadLines($fp);
		} else
			//
			// load the whole template into a string
			//
		{
			$s_buf = fread($fp,filesize($s_name));
		}
		fclose($fp);
	} else {
		if (substr($s_url,-1) == '/') {
			$s_name = "$s_url" . basename($s_name);
		} else {
			$s_name = "$s_url/" . basename($s_name);
		}
		if (($m_data = GetURL($s_name,$s_error,$b_ret_lines)) === false) {
			SendAlert($s_error);
			return (false);
		}
		if ($b_ret_lines) {
			$a_lines = $m_data;
			//
			// strip line terminations from each line
			//
			for ($ii = count($a_lines) ; --$ii >= 0 ;) {
				$s_line       = $a_lines[$ii];
				$s_line       = str_replace("\r","",$s_line);
				$s_line       = str_replace("\n","",$s_line);
				$a_lines[$ii] = $s_line;
			}
		} else {
			$s_buf = $m_data;
		}
	}
	return ($b_ret_lines ? $a_lines : $s_buf);
}

//
// To show an error template.  The template must be HTML and, for security
// reasons, must be a file on the server in the directory specified
// by $TEMPLATEDIR or $TEMPLATEURL.
// $a_specs is an array of substitutions to perform, as follows:
//      tag-name    replacement string
//
// For example:
//      "fmerror"=>"An error message"
//
function ShowErrorTemplate($s_name,$a_specs,$b_user_error)
{

	if (Settings::isEmpty('TEMPLATEDIR') && Settings::isEmpty('TEMPLATEURL')) {
		SendAlert(GetMessage(MSG_TEMPLATES));
		return (false);
	}
	if (($s_buf = LoadTemplate($s_name,Settings::get('TEMPLATEDIR'),Settings::get('TEMPLATEURL'))) === false) {
		return (false);
	}

	//
	// now look for the tags to replace
	//
	foreach ($a_specs as $s_tag => $s_value)
		//
		// search for
		//      <tagname/>
		// or
		//      [tagname/]
		// with optional whitespace
		//
	{
		$s_buf = preg_replace('/[<\[]\s*' . preg_quote($s_tag,"/") . '\s*\/\s*[>\]]/ims',
		                      nl2br($s_value),$s_buf);
	}
	if ($b_user_error) {
		// strip any <fmusererror> and </fmusererror> tags
		// or [fmusererrors] and [/fmusererror] tags
		//
		// You can show information that's specific to user
		// errors between these special tags.
		//
		$s_buf = preg_replace('/[<\[]\s*\/?\s*fmusererror\s*[>\]]/ims','',$s_buf);
		//
		// since this isn't a system error, strip anything between
		// <fmsyserror> and </fmsyserror>
		// or [fmsyserrors] and [/fmsyserror] tags
		//
		$s_buf = preg_replace('/[<\[]\s*fmsyserror\s*[>\]].*[<\[]\s*\/\s*fmsyserror\s*[>\]]/ims','',$s_buf);
	} else {
		// strip any <fmsyserror> and </fmsyserror> tags
		// or [fmsyserrors] and [/fmsyserror] tags
		//
		// You can show information that's specific to system
		// errors between these special tags.
		//
		$s_buf = preg_replace('/[<\[]\s*\/?\s*fmsyserror\s*[>\]]/ims','',$s_buf);
		//
		// since this isn't a user error, strip anything between
		// <fmusererror> and </fmusererror>
		// or [fmusererrors] and [/fmusererror] tags
		//
		$s_buf = preg_replace('/[<\[]\s*fmusererror\s*[>\]].*[<\[]\s*\/\s*fmusererror\s*[>\]]/ims','',$s_buf);
	}
	//
	// just output the page
	//
	echo $s_buf;
	return (true);
}

//
// To show an error to the user.
//
function ShowError($error_code,$error_mesg,$b_user_error,
                   $b_alerted = false,$a_item_list = array(),$s_extra_info = "")
{
	global $SPECIAL_FIELDS,$SPECIAL_MULTI,$SPECIAL_VALUES;
	global $aServerVars,$aStrippedFormVars;

	//
	// Testing with PHP 4.0.6 indicates that sessions don't always work.
	// So, we'll also add the error to the URL, unless
	// PUT_DATA_IN_URL is false.
	//
	SetSession("FormError",$error_mesg);
	SetSession("FormErrorInfo",$s_extra_info);
	SetSession("FormErrorCode",$error_code);
	SetSession("FormErrorItems",$a_item_list);
	SetSession("FormIsUserError",$b_user_error);
	SetSession("FormAlerted",$b_alerted);
	SetSession("FormData",array());

	$bad_url      = $SPECIAL_VALUES["bad_url"];
	$bad_template = $SPECIAL_VALUES["bad_template"];
	$this_form    = $SPECIAL_VALUES["this_form"];
	if (IsAjax()) {
		JSON_Result("ERROR",array("ErrorCode"  => $error_code,
		                          "UserError"  => $b_user_error,
		                          "ErrorMesg"  => $error_mesg,
		                          "Alerted"    => $b_alerted,
		                          "ErrorItems" => $a_item_list
		));
		ZapSession();
	} elseif (!empty($bad_url)) {
		$a_params = array();
		if (Settings::get('PUT_DATA_IN_URL')) {
			$a_params[] = "this_form=" . urlencode("$this_form");
			$a_params[] = "bad_template=" . urlencode("$bad_template");
			$a_params[] = "error=" . urlencode("$error_mesg");
			$a_params[] = "extra=" . urlencode("$s_extra_info");
			$a_params[] = "errcode=" . urlencode("$error_code");
			$a_params[] = "isusererror=" . ($b_user_error ? "1" : "0");
			$a_params[] = "alerted=" . ($b_alerted ? "1" : "0");
			$i_count    = 1;
			foreach ($a_item_list as $s_item) {
				$a_params[] = "erroritem$i_count=" . urlencode("$s_item");
				$i_count++;
			}
		} else {
			$a_sess_data                 = GetSession("FormData");
			$a_sess_data["this_form"]    = "$this_form";
			$a_sess_data["bad_template"] = "$bad_template";
			SetSession("FormData",$a_sess_data);
			//
			// tell the bad_url to look in the session only
			//
			$a_params[] = "insession=1";
		}
		//
		// Add the posted data to the URL so that an intelligent
		// $bad_url can call the form again
		//
		foreach ($aStrippedFormVars as $s_name => $m_value) {
			//
			// skip special fields
			//
			$b_special = false;
			if (in_array($s_name,$SPECIAL_FIELDS)) {
				$b_special = true;
			} else {
				foreach ($SPECIAL_MULTI as $s_multi_fld) {
					$i_len = strlen($s_multi_fld);
					if (substr($s_name,0,$i_len) == $s_multi_fld) {
						$i_index = (int)substr($s_name,$i_len);
						if ($i_index > 0) {
							$b_special = true;
							break;
						}
					}
				}
			}
			if (!$b_special) {
				if (Settings::get('PUT_DATA_IN_URL')) {
					if (is_array($m_value)) {
						foreach ($m_value as $s_value) {
							$a_params[] = "$s_name" . '[]=' .
							              urlencode(substr($s_value,0,Settings::get('MAXSTRING')));
						}
					} else {
						$a_params[] = "$s_name=" . urlencode(substr($m_value,0,Settings::get('MAXSTRING')));
					}
				} else {
					$a_sess_data = GetSession("FormData");
					if (is_array($m_value)) {
						$a_sess_data["$s_name"] = $m_value;
					} else {
						$a_sess_data["$s_name"] = substr($m_value,0,Settings::get('MAXSTRING'));
					}
					SetSession("FormData",$a_sess_data);
				}
			}
		}
		//
		// Now add the authentication data, if any
		//
		if ((isset($aServerVars["PHP_AUTH_USER"]) &&
		     $aServerVars["PHP_AUTH_USER"] !== "") ||
		    (isset($aServerVars["PHP_AUTH_PW"]) &&
		     $aServerVars["PHP_AUTH_PW"] !== "")
		) {
			if (Settings::get('PUT_DATA_IN_URL')) {
				if (isset($aServerVars["PHP_AUTH_USER"])) {
					$a_params[] = "PHP_AUTH_USER=" . urlencode($aServerVars["PHP_AUTH_USER"]);
				}

				if (isset($aServerVars["PHP_AUTH_PW"])) {
					$a_params[] = "PHP_AUTH_PW=" . urlencode($aServerVars["PHP_AUTH_PW"]);
				}

				if (isset($aServerVars["PHP_AUTH_TYPE"])) {
					$a_params[] = "PHP_AUTH_TYPE=" . urlencode($aServerVars["PHP_AUTH_TYPE"]);
				}
			} else {
				$a_sess_data = GetSession("FormData");
				if (isset($aServerVars["PHP_AUTH_USER"])) {
					$a_sess_data["PHP_AUTH_USER"] = $aServerVars["PHP_AUTH_USER"];
				}

				if (isset($aServerVars["PHP_AUTH_PW"])) {
					$a_sess_data["PHP_AUTH_PW"] = $aServerVars["PHP_AUTH_PW"];
				}

				if (isset($aServerVars["PHP_AUTH_TYPE"])) {
					$a_sess_data["PHP_AUTH_TYPE"] = $aServerVars["PHP_AUTH_TYPE"];
				}
				SetSession("FormData",$a_sess_data);
			}
		}
		$bad_url = AddURLParams($bad_url,$a_params,false);
		Redirect($bad_url,GetMessage(MSG_FORM_ERROR));
	} else {
		if (!empty($bad_template)) {
			$a_specs = array("fmerror"      => htmlspecialchars("$error_mesg"),
			                 "fmerrorcode"  => htmlspecialchars("$error_code"),
			                 "fmfullerror"  => htmlspecialchars("$error_mesg") . "\n" .
			                                   htmlspecialchars("$s_extra_info"),
			                 "fmerrorextra" => htmlspecialchars("$s_extra_info"),
			);
			for ($i_count = 1 ; $i_count <= 20 ; $i_count++) {
				$a_specs["fmerroritem$i_count"] = "";
			}
			$i_count = 1;
			foreach ($a_item_list as $s_item) {
				$a_specs["fmerroritem$i_count"] = htmlspecialchars($s_item);
				$i_count++;
			}
			$s_list = "";
			foreach ($a_item_list as $s_item) {
				$s_list .= "<li>" . htmlspecialchars($s_item) . "</li>";
			}
			$a_specs["fmerroritemlist"] = $s_list;
			if (ShowErrorTemplate($bad_template,$a_specs,$b_user_error)) {
				return;
			}
		}
		$s_text = GetMessage(MSG_ERROR_PROC);
		if ($b_user_error) {
			$s_text .= $error_mesg . "\n" . FixedHTMLEntities($s_extra_info);
		} else {
			global $SERVER;

			if ($b_alerted) {
				$s_text .= GetMessage(MSG_ALERT_DONE,array("SERVER" => $SERVER));
			} else {
				$s_text .= GetMessage(MSG_PLS_CONTACT,array("SERVER" => $SERVER));
			}
			$s_text .= GetMessage(MSG_APOLOGY,array("SERVER" => $SERVER));
		}
		CreatePage($s_text,GetMessage(MSG_FORM_ERROR),false);
		//
		// the session data is not needed now
		//
		ZapSession();
	}
}

//
// Report an error.  Same as Error, but implements
// ATTACK_DETECTION_IGNORE_ERRORS.
//
function ErrorWithIgnore($error_code,$error_mesg,$b_filter = true,$show = true,$int_mesg = "")
{
	if (function_exists('FMHookErrorWithIgnore')) {
		FMHookErrorWithIgnore($error_code,$error_mesg,$b_filter,$show,$int_mesg);
	}

	$b_alerted = false;
	if (!Settings::get('ATTACK_DETECTION_IGNORE_ERRORS')) {
		if (SendAlert("$error_code\n *****$int_mesg*****\nError=$error_mesg\n",$b_filter)) {
			$b_alerted = true;
		}
	}
	if ($show) {
		ShowError($error_code,$error_mesg,false,$b_alerted);
	} else
		//
		// show something to the user
		//
	{
		ShowError($error_code,GetMessage(MSG_SUBM_FAILED),false,$b_alerted);
	}
	exit;
}

//
// Report an error
//
function Error($error_code,$error_mesg,$b_filter = true,$show = true,$int_mesg = "")
{
	if (function_exists('FMHookError')) {
		FMHookError($error_code,$error_mesg,$b_filter,$show,$int_mesg);
	}

	$b_alerted = false;
	if (SendAlert("$error_code\n *****$int_mesg*****\nError=$error_mesg\n",$b_filter)) {
		$b_alerted = true;
	}
	if ($show) {
		ShowError($error_code,$error_mesg,false,$b_alerted);
	} else
		//
		// show something to the user
		//
	{
		ShowError($error_code,GetMessage(MSG_SUBM_FAILED),false,$b_alerted);
	}
	exit;
}

//
// Report a user error
//
function UserError($s_error_code,$s_error_mesg,
                   $s_extra_info = "",$a_item_list = array())
{
	if (function_exists('FMHookUserError')) {
		FMHookUserError($s_error_code,$s_error_mesg,$s_extra_info,$a_item_list);
	}
	$b_alerted = false;
	if (Settings::get('ALERT_ON_USER_ERROR') &&
	    SendAlert("$s_error_code\nError=$s_error_mesg\n$s_extra_info\n")
	) {
		$b_alerted = true;
	}
	ShowError($s_error_code,$s_error_mesg,true,$b_alerted,$a_item_list,$s_extra_info);
	exit;
}

//
// Create a simple page with the given text.
//
function CreatePage($text,$title = "",$b_show_about = true)
{
	global $FM_VERS,$sHTMLCharSet;

	if (IsAjax()) {
		//
		// CreatePage should not be called in Ajax mode.
		// If it is, it must be an error or debugging state.
		//
		JSON_Result("ERROR",array("ErrorCode" => $title,
		                          "ErrorMesg" => $text
		));
	} else {
		echo
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
			"\n";
		echo '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
		echo "<head>\n";
		if (isset($sHTMLCharSet) && $sHTMLCharSet !== "") {
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$sHTMLCharSet\" />\n";
		}
		if ($title != "") {
			echo "<title>" . FixedHTMLEntities($title) . "</title>\n";
		}
		echo "</head>\n";
		echo "<body>\n";
		echo nl2br($text);
		echo "<p />";
		if ($b_show_about) {
			echo "<p><small>\n";
			echo GetMessage(MSG_ABOUT_FORMMAIL,array("FM_VERS" => $FM_VERS,
			                                         "TECTITE" => "www.tectite.com"
			));
			echo "</small></p>\n";
		}
		echo "</body>\n";
		echo "</html>\n";
	}
}

//
// Strip slashes if magic_quotes_gpc is set.
//
function StripGPC($s_value)
{
	if (get_magic_quotes_gpc() != 0) {
		$s_value = stripslashes($s_value);
	}
	return ($s_value);
}

//
// return an array, stripped of slashes if magic_quotes_gpc is set
//
function StripGPCArray($a_values)
{
	if (get_magic_quotes_gpc() != 0) {
		foreach ($a_values as $m_key => $m_value) {
			if (is_array($m_value))
				//
				// strip arrays recursively
				//
			{
				$a_values[$m_key] = StripGPCArray($m_value);
			} else
				//
				// convert scalar to string and strip
				//
			{
				$a_values[$m_key] = stripslashes("$m_value");
			}
		}
	}
	return ($a_values);
}

//
// Strip a value of unwanted characters, which might be hacks.
// The stripping of \r and \n is a *critical* security feature.
//
function Strip($value)
{
	//
	// When working with character sets such as UTF-8, stripping
	// control characters is a *really bad idea* and breaks things.
	// From version 8.22, FormMail only strips \r and \n as these
	// are really the only characters that can cause header hacks
	// to be inserted. (Strip means replace with a single space).
	// We also handle multiple spaces.
	//
	$value = preg_replace('/[ \r\n]+/'," ",$value); // zap all CRLF and multiple blanks
	return ($value);
}

//
// Clean a value.  This means:
//  1. convert to string
//  2. truncate to maximum length
//  3. strip the value of unwanted or dangerous characters (hacks)
//  4. trim both ends of whitespace
// Each element of an array is cleaned as above.  This process occurs
// recursively, so arrays of arrays work OK too (though there's no
// need for that in this program).
//
// Non-scalar values are changed to the string "<X>" where X is the type.
// In general, FormMail won't receive non-scalar non-array values, so this
// is more a future-proofing measure.
//
function CleanValue($m_value)
{
	if (is_array($m_value)) {
		foreach ($m_value as $m_key => $m_item) {
			$m_value[$m_key] = CleanValue($m_item);
		}
	} elseif (!is_scalar($m_value)) {
		$m_value = "<" . gettype($m_value) . ">";
	} else {
		//
		// convert to string and truncate
		//
		$m_value = substr("$m_value",0,Settings::get('MAXSTRING'));
		//
		// strip unwanted chars and trim
		//
		$m_value = trim(Strip($m_value));
	}
	return ($m_value);
}

//
// Clean a special value.  Special values listed in $SPECIAL_NOSTRIP
// will not be cleaned.
//
function SpecialCleanValue($s_name,$m_value)
{
	global $SPECIAL_NOSTRIP;

	if (!in_array($s_name,$SPECIAL_NOSTRIP)) {
		$m_value = CleanValue($m_value);
	}
	return ($m_value);
}

//
// Return the fields and their values in a string containing one
// field per line.
//
function MakeFieldOutput($a_order,$a_fields,$s_line_feed = null)
{
	if ($s_line_feed === null) {
		$s_line_feed = Settings::get('BODY_LF');
	}
	$n_order  = count($a_order);
	$s_output = "";
	for ($ii = 0 ; $ii < $n_order ; $ii++) {
		$s_name = $a_order[$ii];
		if (isset($a_fields[$s_name])) {
			$s_output .= "$s_name: " . $a_fields[$s_name] . $s_line_feed;
		}
	}
	return ($s_output);
}

//
// Check if a field name is special. Returns true if it is.
//
function IsSpecialField($s_name)
{
	global $SPECIAL_FIELDS;

	return (in_array($s_name,$SPECIAL_FIELDS));
}

//
// Set a special field value.
//
function SetSpecialField($s_name,$m_value)
{
	global $SPECIAL_VALUES;

	//
	// most special values cannot be arrays; ignore them if they are
	//
	if (is_array($m_value)) {
		global $SPECIAL_ARRAYS;

		if (!in_array($s_name,$SPECIAL_ARRAYS)) {
			return;
		}
	}
	$SPECIAL_VALUES[$s_name] = SpecialCleanValue($s_name,$m_value);
}

//
// Check if a field name is a special "multi" field.
// A multi field is the name plus a sequence number.  For example,
//      conditions1
//      conditions2
// Returns a list (array) if it is, otherwise false if not.
// The list contains:
//      1. the name of the special multi field
//      2. the index number for multi field
//
function IsSpecialMultiField($s_name)
{
	global $SPECIAL_MULTI;

	foreach ($SPECIAL_MULTI as $s_multi_fld) {
		$i_len = strlen($s_multi_fld);
		//
		// look for nameN where N is a number starting from 1
		//
		if (substr($s_name,0,$i_len) == $s_multi_fld) {
			$i_index = (int)substr($s_name,$i_len);
			if ($i_index > 0) {
				//
				// re-index to zero
				//
				--$i_index;
				return (array($s_multi_fld,$i_index));
			}
		}
	}
	return (false);
}

//
// Set a multi field value.
//
function SetSpecialMultiField($s_name,$i_index,$m_value)
{
	global $SPECIAL_VALUES;

	//
	// these special fields cannot be arrays - ignore if it is
	//
	if (!is_array($m_value)) {
		$SPECIAL_VALUES[$s_name][$i_index] = SpecialCleanValue($s_name,$m_value);
	}
}

//
// Check if a field is part of Reverse Captcha processing.
//
function    IsReverseCaptchaField($s_name)
{
	$a_rev_captcha = Settings::get('ATTACK_DETECTION_REVERSE_CAPTCHA');
	return (isset($a_rev_captcha[$s_name]));
}

//
// Process a field
//
function ProcessField($s_name,$raw_value,&$a_order,&$a_fields,&$a_raw_fields)
{
	global $FORMATTED_INPUT;

	//
	// fields go into an array of special values or an array of other values
	// or get completely ignored.
	//
	$b_ignore = $b_special = false;
	if (IsSpecialField($s_name)) {
		SetSpecialField($s_name,$raw_value);
		$b_special = true;
	}
	//
	// check for multiple valued special fields
	//
	if (($a_multi_fld = IsSpecialMultiField($s_name)) !== false) {
		SetSpecialMultiField($a_multi_fld[0],$a_multi_fld[1],$raw_value);
		$b_special = true;
	}
	if (!$b_special) {
		if (IsReverseCaptchaField($s_name)) {
			$b_ignore = true;
		}
	}
	if (!$b_special && !$b_ignore) {
		//
		// return the raw value unchanged in the $a_raw_fields array
		//
		$a_raw_fields[$s_name] = $raw_value;
		//
		// handle checkboxes and multiple-selection lists
		// Thanks go to Theodore Boardman for the suggestion
		// and initial working code.
		//
		if (is_array($raw_value)) {
			if (empty($raw_value)) {
				$s_cleaned_value = "";
			} else {
				$a_cleaned_values = CleanValue($raw_value);
				//
				// the output is a comma separated list of values for the
				// checkbox.  For example,
				//          events: Diving,Cycling,Running
				//
				// Set the clean value to the list of cleaned checkbox
				// values.
				// First, remove any commas in the values themselves.
				//
				$a_cleaned_values = str_replace(",","",$a_cleaned_values);
				$s_cleaned_value  = implode(",",$a_cleaned_values);
			}
		} else {
			//
			// NOTE: there is a minor bug here now that we support
			// $FORM_INI_FILE. The INI file is processed at the end
			// so if you set the mail_options below in the INI file they
			// won't get processed here.  This means you must set
			// the following mail_options in the HTML form for now.
			// (To be fixed at a later date.  RJR 17-Feb-06).
			//

			//
			// if the form specifies the "KeepLines" option,
			// don't strip new lines
			//
			if (IsMailOptionSet("KeepLines") && strpos($raw_value,"\n") !== false) {
				//
				// truncate first
				//
				$s_truncated = substr("$raw_value",0,Settings::get('MAXSTRING'));
				//
				// split into lines, clean each individual line,
				// then put it back together again
				//
				$a_lines         = explode("\n",$s_truncated);
				$a_lines         = CleanValue($a_lines);
				$s_cleaned_value = implode(Settings::get('BODY_LF'),$a_lines);
				//
				// and, for this special case, prepend a new line
				// so that the value is shown on a fresh line
				//
				$s_cleaned_value = Settings::get('BODY_LF') . $s_cleaned_value;
			} else {
				$s_cleaned_value = CleanValue($raw_value);
			}
		}
		//
		// if the form specifies the "NoEmpty" option, skip
		// empty values.
		//
		if (!IsMailOptionSet("NoEmpty") || !FieldManager::IsEmpty($s_cleaned_value)) {
			if (!IsMailExcluded($s_name)) {
				//
				// by default, we maintain the order as passed in
				// the HTTP request
				//
				$a_order[]         = $s_name;
				$a_fields[$s_name] = $s_cleaned_value;
			}
		}

		//
		// add to the $FORMATTED_INPUT array for debugging and
		// error reporting
		//
		array_push($FORMATTED_INPUT,"$s_name: '$s_cleaned_value'");
	}
}

//
// Parse the input variables and return:
//  1. an array that specifies the required field order in the output
//  2. an array containing the non-special cleaned field values indexed
//     by field name.
//  3. an array containing the non-special raw field values indexed by
//     field name.
//
function ParseInput($a_vars)
{
	$a_order      = array();
	$a_fields     = array();
	$a_raw_fields = array();
	//
	// scan the array of values passed in (name-value pairs) and
	// produce slightly formatted (not HTML) textual output
	// and extract any special values found.
	//
	foreach ($a_vars as $s_name => $raw_value) {
		ProcessField($s_name,$raw_value,$a_order,$a_fields,$a_raw_fields);
	}

	return (array($a_order,$a_fields,$a_raw_fields));
}

//
// Get the URL for sending to the CRM.
//
function GetCRMURL($spec,$vars,$url)
{
	$bad  = false;
	$list = TrimArray(explode(",",$spec));
	$map  = array();
	for ($ii = 0 ; $ii < count($list) ; $ii++) {
		$name = $list[$ii];
		if ($name) {
			//
			// the specification must be in this format:
			//      form-field-name:CRM-field-name
			//
			if (($i_crm_name_pos = strpos($name,":")) > 0) {
				$s_crm_name = substr($name,$i_crm_name_pos + 1);
				$name       = substr($name,0,$i_crm_name_pos);
				if (isset($vars[$name])) {
					$map[] = $s_crm_name . "=" . urlencode($vars[$name]);
					$map[] = "Orig_" . $s_crm_name . "=" . urlencode($name);
				}
			} else {
				//
				// not the right format, so just include as a parameter
				// check for name=value format to choose encoding
				//
				$a_values = explode("=",$name);
				if (count($a_values) > 1) {
					$map[] = urlencode($a_values[0]) . "=" . urlencode($a_values[1]);
				} else {
					$map[] = urlencode($a_values[0]);
				}
			}
		}
	}
	if (count($map) == 0) {
		return ("");
	}
	return (AddURLParams($url,$map,false));
}

//
// strip the HTML from a string or array of strings
//
function StripHTML($m_value,$s_line_feed = "\n")
{
	if (is_array($m_value)) {
		foreach ($m_value as $m_key => $s_str) {
			$m_value[$m_key] = StripHTML($s_str);
		}
		return ($m_value);
	}
	$s_str = $m_value;
	//
	// strip HTML comments (s option means include new lines in matches)
	//
	$s_str = preg_replace('/<!--([^-]*([^-]|-([^-]|-[^>])))*-->/s','',$s_str);
	//
	// strip any scripts (i option means case-insensitive)
	//
	$s_str = preg_replace('/<script[^>]*?>.*?<\/script[^>]*?>/si','',$s_str);
	//
	// replace paragraphs with new lines (line feeds)
	//
	$s_str = preg_replace('/<p[^>]*?>/i',$s_line_feed,$s_str);
	//
	// replace breaks with new lines (line feeds)
	//
	$s_str = preg_replace('/<br[[:space:]]*\/?[[:space:]]*>/i',$s_line_feed,$s_str);
	//
	// overcome this bug: http://bugs.php.net/bug.php?id=21311
	//
	$s_str = preg_replace('/<![^>]*>/s','',$s_str);
	//
	// get rid of all HTML tags
	//
	$s_str = strip_tags($s_str);
	return ($s_str);
}

//
// Check for valid URL in TARGET_URLS
//
function CheckValidURL($s_url)
{

	foreach (Settings::get('TARGET_URLS') as $s_prefix) {
		if (!empty($s_prefix) &&
		    strtolower(substr($s_url,0,strlen($s_prefix))) ==
		    strtolower($s_prefix)
		) {
			return (true);
		}
	}
	return (false);
}

//
// Scan the given data for fields returned from the CRM.
// A field has this following format:
//      __FIELDNAME__=value
// terminated by a line feed.
//
function FindCRMFields($s_data)
{
	$a_ret = array();
	if (preg_match_all('/^__([A-Za-z][A-Za-z0-9_]*)__=(.*)$/m',$s_data,$a_matches) === false) {
		SendAlert(GetMessage(MSG_PREG_FAILED));
	} else {
		$n_matches = count($a_matches[0]);
		//  SendAlert("$n_matches on '$s_data'");
		for ($ii = 0 ; $ii < $n_matches ; $ii++) {
			if (isset($a_matches[1][$ii]) && isset($a_matches[2][$ii])) {
				$a_ret[$a_matches[1][$ii]] = $a_matches[2][$ii];
			}
		}
	}
	return ($a_ret);
}

//
// open the given URL to send data to it, we expect the response
// to contain at least '__OK__=' followed by true or false
//
function SendToCRM($s_url,&$a_data)
{
	global $php_errormsg;

	if (!CheckValidURL($s_url)) {
		SendAlert(GetMessage(MSG_URL_INVALID,array("URL" => $s_url)));
		return (false);
	}
	@   $fp = fopen($s_url,"r"); // RJR: TO DO: re-implement using NetIO
	if ($fp === false) {
		SendAlert(GetMessage(MSG_URL_OPEN,array("URL"   => $s_url,
		                                        "ERROR" => CheckString($php_errormsg)
		)));
		return (false);
	}
	$s_mesg = "";
	while (!feof($fp)) {
		$s_line = fgets($fp,4096);
		$s_mesg .= $s_line;
	}
	fclose($fp);
	$s_mesg   = StripHTML($s_mesg);
	$s_result = preg_match('/__OK__=(.*)/',$s_mesg,$a_matches);
	if (count($a_matches) < 2 || $a_matches[1] === "") {
		//
		// no agreed __OK__ value returned - assume system error
		//
		SendAlert(GetMessage(MSG_CRM_FAILED,array("URL" => $s_url,
		                                          "MSG" => $s_mesg
		)));
		return (false);
	}
	//
	// look for fields to return
	//
	$a_data = FindCRMFields($s_mesg);
	//
	// check for success or user error
	//
	switch (strtolower($a_matches[1])) {
		case "true":
			break;
		case "false":
			//
			// check for user error
			//
			if (isset($a_data["USERERRORCODE"])) {
				$s_error_code = "crm_error";
				$s_error_mesg = GetMessage(MSG_CRM_FORM_ERROR);
				$s_error_code .= $a_data["USERERRORCODE"];
				if (isset($a_data["USERERRORMESG"])) {
					$s_error_mesg = $a_data["USERERRORMESG"];
				}
				UserError($s_error_code,$s_error_mesg);
				// no return
			}
			return (false);
	}
	return (true);
}

//
// Split into field name and friendly name; returns an array with
// two elements.
// Format is:
//      fieldname:Nice printable name for displaying
//
function GetFriendlyName($s_name)
{
	if (($i_pos = strpos($s_name,':')) === false) {
		return (array(trim($s_name),trim($s_name)));
	}
	return (array(trim(substr($s_name,0,$i_pos)),trim(substr($s_name,$i_pos + 1))));
}

define("REQUIREDOPS","|^!="); // operand characters for advanced required processing

//
// Perform a field comparison test.
//
function FieldTest($s_oper,$s_fld1,$s_fld2,$a_vars,&$s_error_mesg,
                   $s_friendly1 = "",$s_friendly2 = "")
{
	$b_ok     = true;
	$s_empty1 = $s_empty2 = "";
	//
	// perform the test
	//
	switch ($s_oper) {
		case '&': // both fields must be present
			if (!TestFieldEmpty($s_fld1,$a_vars,$s_empty1) &&
			    !TestFieldEmpty($s_fld2,$a_vars,$s_empty2)
			) {
				;
			} // OK
			else {
				//
				// failed
				//
				$s_error_mesg = GetMessage(MSG_AND,array("ITEM1" => $s_friendly1,
				                                         "ITEM2" => $s_friendly2
				));
				$b_ok         = false;
			}
			break;
		case '|': // either field or both must be present
			if (!TestFieldEmpty($s_fld1,$a_vars,$s_empty1) ||
			    !TestFieldEmpty($s_fld2,$a_vars,$s_empty2)
			) {
				;
			} // OK
			else {
				//
				// failed
				//
				$s_error_mesg = GetMessage(MSG_OR,array("ITEM1" => $s_friendly1,
				                                        "ITEM2" => $s_friendly2
				));
				$b_ok         = false;
			}
			break;
		case '^': // either field but not both must be present
			$b_got1 = !TestFieldEmpty($s_fld1,$a_vars,$s_empty1);
			$b_got2 = !TestFieldEmpty($s_fld2,$a_vars,$s_empty2);
			if ($b_got1 || $b_got2) {
				if ($b_got1 && $b_got2) {
					//
					// failed
					//
					$s_error_mesg = GetMessage(MSG_NOT_BOTH,
					                           array("ITEM1" => $s_friendly1,
					                                 "ITEM2" => $s_friendly2
					                           ));
					$b_ok         = false;
				}
			} else {
				//
				// failed
				//
				$s_error_mesg = GetMessage(MSG_XOR,
				                           array("ITEM1" => $s_friendly1,
				                                 "ITEM2" => $s_friendly2
				                           ));
				$b_ok         = false;
			}
			break;
		case '!=':
		case '=':
			$b_got1 = !TestFieldEmpty($s_fld1,$a_vars,$s_empty1);
			$b_got2 = !TestFieldEmpty($s_fld2,$a_vars,$s_empty2);
			if ($b_got1 && $b_got2) {
				$b_match = (GetFieldValue($s_fld1,$a_vars) ==
				            GetFieldValue($s_fld2,$a_vars));
			} elseif (!$b_got1 && !$b_got2)
				//
				// haven't got either value - they match
				//
			{
				$b_match = true;
			} else
				//
				// got one value, but not the other - they don't match
				//
			{
				$b_match = false;
			}
			if ($s_oper != '=') {
				//
				// != operator
				//
				$b_match = !$b_match;
				$s_desc  = GetMessage(MSG_IS_SAME_AS,
				                      array("ITEM1" => $s_friendly1,
				                            "ITEM2" => $s_friendly2
				                      ));
			} else {
				$s_desc = GetMessage(MSG_IS_NOT_SAME_AS,
				                     array("ITEM1" => $s_friendly1,
				                           "ITEM2" => $s_friendly2
				                     ));
			}
			if (!$b_match) {
				//
				// failed
				//
				$s_error_mesg = $s_desc;
				$b_ok         = false;
			}
			break;
	}
	return ($b_ok);
}

//
// Process advanced "required" conditionals
//
function AdvancedRequired($s_cond,$i_span,$a_vars,&$s_missing,&$a_missing_list)
{
	$b_ok = true;
	//
	// get first field name
	//
	list($s_fld1,$s_friendly1) = GetFriendlyName(substr($s_cond,0,$i_span));
	//
	// get the operator
	//
	$s_rem  = substr($s_cond,$i_span);
	$i_span = strspn($s_rem,REQUIREDOPS);
	$s_oper = substr($s_rem,0,$i_span);
	switch ($s_oper) {
		case '|':
		case '^':
		case '=':
		case '!=':
			//
			// second component is a field name
			//
			list($s_fld2,$s_friendly2) = GetFriendlyName(substr($s_rem,$i_span));
			if (!FieldTest($s_oper,$s_fld1,$s_fld2,$a_vars,$s_error_mesg,
			               $s_friendly1,$s_friendly2)
			) {
				//
				// failed
				//
				$s_missing .= "$s_error_mesg\n";
				$a_missing_list[$s_fld1] = "$s_error_mesg";
				$b_ok                    = false;
			}
			break;
		default:
			SendAlert(GetMessage(MSG_REQD_OPER,array("OPER" => $s_oper)));
			break;
	}
	return ($b_ok);
}

//
// Check the input for required values.  The list of required fields
// is a comma-separated list of field names or conditionals
//
function CheckRequired($s_reqd,$a_vars,&$s_missing,&$a_missing_list)
{
	global $reCaptchaProcessor;

	$b_bad          = false;
	$a_list         = TrimArray(explode(",",$s_reqd));
	$s_missing      = "";
	$a_missing_list = array();
	$s_mesg         = "";
	for ($ii = 0 ; $ii < count($a_list) ; $ii++) {
		$s_cond = $a_list[$ii];
		$i_len  = strlen($s_cond);
		if ($i_len <= 0) {
			continue;
		}
		if (($i_span = strcspn($s_cond,REQUIREDOPS)) >= $i_len) {
			//
			// no advanced operator; just a field name
			//
			list($s_fld,$s_friendly) = GetFriendlyName($s_cond);
			if (TestFieldEmpty($s_fld,$a_vars,$s_mesg)) {
				if ($s_mesg === "") {
					$s_mesg = "$s_friendly";
				} else {
					$s_mesg = "$s_friendly ($s_mesg)";
				}
				$b_bad = true;
				$s_missing .= "$s_mesg\n";
				$a_missing_list[$s_fld] = "$s_mesg";
			}
		} elseif (!AdvancedRequired($s_cond,$i_span,$a_vars,
		                            $s_missing,$a_missing_list)
		) {
			$b_bad = true;
		}
	}

	global $SPECIAL_VALUES;

	//
	// implement REQUIRE_CAPTCHA feature
	//
	if (!Settings::isEmpty('REQUIRE_CAPTCHA')) {
		if ($SPECIAL_VALUES["imgverify"] === "") {
			$s_missing .= Settings::get('REQUIRE_CAPTCHA') . "\n";
			$a_missing_list['imgverify'] = Settings::get('REQUIRE_CAPTCHA');
			$b_bad                       = true;
		}
	}
	return (!$b_bad);
}

//
// Run a condition test
//
function RunTest($s_test,$a_vars)
{
	global $aAlertInfo;

	$s_op_chars = "&|^!=~#<>"; // these are the characters for the operators
	$i_len      = strlen($s_test);
	$b_ok       = true;
	$s_mesg     = "";
	if ($i_len <= 0)
		//
		// empty test - true
		//
	{
		;
	} elseif ($s_test == "!")
		//
		// test asserts false
		//
	{
		$b_ok = false;
	} elseif (($i_span = strcspn($s_test,$s_op_chars)) >= $i_len)
		//
		// no operator - just check field presence
		//
	{
		$b_ok = !TestFieldEmpty($s_test,$a_vars,$s_mesg);
	} else {
		//
		// get first field name
		//
		$s_fld1 = trim(substr($s_test,0,$i_span));
		//
		// get the operator
		//
		$s_rem  = substr($s_test,$i_span);
		$i_span = strspn($s_rem,$s_op_chars);
		$s_oper = substr($s_rem,0,$i_span);
		switch ($s_oper) {
			case '&':
			case '|':
			case '^':
			case '=':
			case '!=':
				//
				// get the second field name
				//
				$s_fld2 = trim(substr($s_rem,$i_span));
				$b_ok   = FieldTest($s_oper,$s_fld1,$s_fld2,$a_vars,$s_error_mesg);
				break;
			case '~':
			case '!~':
				//
				// get the regular expression
				//
				$s_pat = trim(substr($s_rem,$i_span));
				if (!TestFieldEmpty($s_fld1,$a_vars,$s_mesg)) {
					$s_value = GetFieldValue($s_fld1,$a_vars);
				} else {
					$s_value = "";
				}
				//echo "<p>Pattern: '".htmlspecialchars($s_pat)."': count=".preg_match($s_pat,$s_value)."<br /></p>";
				//
				// match the regular expression
				//
				if (preg_match($s_pat,$s_value) > 0) {
					$b_ok = ($s_oper == '~');
				} else {
					$b_ok = ($s_oper == '!~');
				}
				if (!$b_ok) {
					$aAlertInfo[] = GetMessage(MSG_PAT_FAILED,array("OPER"  => $s_oper,
					                                                "PAT"   => $s_pat,
					                                                "VALUE" => $s_value
					));
				}
				break;
			case '#=':
			case '#!=':
			case '#<':
			case '#>':
			case '#<=':
			case '#>=':
				//
				// numeric tests
				//
				$s_num = trim(substr($s_rem,$i_span));
				//
				// if this is a file field, get the size of the file for
				// numeric tests
				//
				if (($s_value = GetFileSize($s_fld1)) === false) {
					$s_value = $a_vars[$s_fld1];
				}
				if (strpos($s_num,'.') === false) {
					//
					// treat as integer
					//
					$m_num = (int)$s_num;
					$m_fld = (int)$s_value;
				} else {
					//
					// treat as floating point
					//
					$m_num = (float)$s_num;
					$m_fld = (float)$s_value;
				}
				switch ($s_oper) {
					case '#=':
						$b_ok = ($m_fld == $m_num);
						break;
					case '#!=':
						$b_ok = ($m_fld != $m_num);
						break;
					case '#<':
						$b_ok = ($m_fld < $m_num);
						break;
					case '#>':
						$b_ok = ($m_fld > $m_num);
						break;
					case '#<=':
						$b_ok = ($m_fld <= $m_num);
						break;
					case '#>=':
						$b_ok = ($m_fld >= $m_num);
						break;
				}
				break;
			default:
				SendAlert(GetMessage(MSG_COND_OPER,array("OPER" => $s_oper)));
				break;
		}
	}
	return ($b_ok);
}

//
// Check the input for condition tests.
//
function CheckConditions($m_conditions,$a_vars,&$s_missing,&$a_missing_list,$m_id = false)
{
	if (is_array($m_conditions)) {
		//
		// Sort the conditions by their numeric value.  This ensures
		// conditions are executed in the right order.
		//
		ksort($m_conditions,SORT_NUMERIC);
		foreach ($m_conditions as $m_key => $s_cond) {
			if (!CheckConditions($s_cond,$a_vars,$s_missing,$a_missing_list,$m_key)) {
				return (false);
			}
		}
		return (true);
	}
	$s_fld_name = "conditions" . ($m_id === false ? "" : ($m_id + 1));
	if (!is_string($m_conditions)) {
		SendAlert(GetMessage(MSG_INV_COND,array("FLD" => $s_fld_name)));
		return (true); // pass invalid conditions
	}
	if ($m_conditions == "") {
		return (true);
	} // pass empty conditions
	$s_cond = $m_conditions;
	//
	// extract the separator characters
	//
	if (strlen($s_cond) < 2) {
		SendAlert(GetMessage(MSG_COND_CHARS,
		                     array("FLD" => $s_fld_name,"COND" => $s_cond)));
		return (true); // pass invalid conditions
	}
	$s_list_sep     = $s_cond[0];
	$s_int_sep      = $s_cond[1];
	$s_full_cond    = $s_cond = substr($s_cond,2);
	$b_bad          = false;
	$a_list         = TrimArray(explode($s_list_sep,$s_cond));
	$s_missing      = "";
	$a_missing_list = array();
	for ($ii = 0 ; $ii < count($a_list) ; $ii++) {
		$s_cond = $a_list[$ii];
		$i_len  = strlen($s_cond);
		if ($i_len <= 0) {
			continue;
		}
		//
		// split the condition into its internal components
		//
		$a_components = TrimArray(explode($s_int_sep,$s_cond));
		if (count($a_components) < 5) {
			SendAlert(GetMessage(MSG_COND_INVALID,
			                     array("FLD" => $s_fld_name,"COND" => $s_cond,
			                           "SEP" => $s_int_sep
			                     )));
			//
			// the smallest condition has 5 components
			//
			continue;
		}
		//
		// first component is ignored (it's blank)
		//
		$a_components = array_slice($a_components,1);
		switch ($a_components[0]) {
			case "TEST":
				if (count($a_components) > 5) {
					SendAlert(GetMessage(MSG_COND_TEST_LONG,
					                     array("FLD" => $s_fld_name,"COND" => $s_cond,
					                           "SEP" => $s_list_sep
					                     )));
					continue;
				}
				if (!RunTest($a_components[1],$a_vars)) {
					$s_missing .= $a_components[2] . "\n";
					$a_missing_list[] = $a_components[2];
					$b_bad            = true;
				}
				break;
			case "IF":
				if (count($a_components) < 6) {
					SendAlert(GetMessage(MSG_COND_IF_SHORT,
					                     array("FLD" => $s_fld_name,"COND" => $s_cond,
					                           "SEP" => $s_int_sep
					                     )));
					continue;
				}
				if (count($a_components) > 7) {
					SendAlert(GetMessage(MSG_COND_IF_LONG,
					                     array("FLD" => $s_fld_name,"COND" => $s_cond,
					                           "SEP" => $s_list_sep
					                     )));
					continue;
				}
				if (RunTest($a_components[1],$a_vars)) {
					$b_test = RunTest($a_components[2],$a_vars);
				} else {
					$b_test = RunTest($a_components[3],$a_vars);
				}
				if (!$b_test) {
					$s_missing .= $a_components[4] . "\n";
					$a_missing_list[] = $a_components[4];
					$b_bad            = true;
				}
				break;
			default:
				SendAlert(GetMessage(MSG_COND_UNK,
				                     array("FLD" => $s_fld_name,"COND" => $s_cond,
				                           "CMD" => $a_components[0]
				                     )));
				break;
		}
	}
	return (!$b_bad);
}

//
// Return a formatted list of the given environment variables.
//
function GetEnvVars($list,$s_line_feed)
{
	global $aServerVars;

	$output = "";
	for ($ii = 0 ; $ii < count($list) ; $ii++) {
		$name = $list[$ii];
		if ($name && array_search($name,Settings::get('VALID_ENV'),true) !== false) {
			//
			// if the environment variable is empty or non-existent, try
			// looking for the value in the server vars.
			//
			if (($s_value = getenv($name)) === "" || $s_value === false) {
				if (isset($aServerVars[$name])) {
					$s_value = $aServerVars[$name];
				} else {
					$s_value = "";
				}
			}
			$output .= $name . "=" . $s_value . $s_line_feed;
		}
	}
	return ($output);
}

//
// open a socket connection to a filter and post the data there
// RJR: TO DO: re-implement using NetIO
//
function SocketFilter($filter,$a_filter_info,$m_data)
{
	static $b_in_here = false;
	global $php_errormsg;

	//
	// prevent recursive errors
	//
	if ($b_in_here) {
		return ("<DATA DISCARDED>");
	}
	$b_in_here = true;

	$a_errors = array();
	if (!isset($a_filter_info["site"])) {
		$a_errors[] = GetMessage(MSG_MISSING,array("ITEM" => "site"));
	} else {
		$s_site = $a_filter_info["site"];
	}

	if (!isset($a_filter_info["port"])) {
		$a_errors[] = GetMessage(MSG_MISSING,array("ITEM" => "port"));
	} else {
		$i_port = (int)$a_filter_info["port"];
	}

	if (!isset($a_filter_info["path"])) {
		$a_errors[] = GetMessage(MSG_MISSING,array("ITEM" => "path"));
	} else {
		$s_path = $a_filter_info["path"];
	}

	if (!isset($a_filter_info["params"])) {
		$a_params = array();
	} elseif (!is_array($a_filter_info["params"])) {
		$a_errors[] = GetMessage(MSG_NEED_ARRAY,array("ITEM" => "params"));
	} else {
		$a_params = $a_filter_info["params"];
	}

	if (!empty($a_errors)) {
		Error("bad_filter",GetMessage(MSG_FILTER_WRONG,array(
			"FILTER" => $filter,
			"ERRORS" => implode(', ',$a_errors)
		)),false,false);
		exit;
	}

	//
	// ready to build the socket - we need a longer time limit for the
	// script if we're doing this; we allow 30 seconds for the connection
	// (should be instantaneous, especially if it's the same domain)
	//
	set_time_limit(60);
	@   $f_sock = fsockopen($s_site,$i_port,$i_errno,$s_errstr,30);
	if ($f_sock === false) {
		Error("filter_connect",GetMessage(MSG_FILTER_CONNECT,array(
			"FILTER" => $filter,
			"SITE"   => $s_site,
			"ERRNUM" => $i_errno,
			"ERRSTR" => "$s_errstr (" . CheckString($php_errormsg) . ")"
		)),
		      false,false);
		exit;
	}
	//
	// build the data to send
	//
	$m_request_data = array();
	$i_count        = 0;
	foreach ($a_params as $m_var) {
		$i_count++;
		//
		// if the parameter spec is an array, process it specially;
		// it must have "name" and "file" elements
		//
		if (is_array($m_var)) {
			if (!isset($m_var["name"])) {
				Error("bad_filter",GetMessage(MSG_FILTER_PARAM,
				                              array("FILTER" => $filter,
				                                    "NUM"    => $i_count,
				                                    "NAME"   => "name"
				                              )),false,false);
				fclose($f_sock);
				exit;
			}
			$s_name = $m_var["name"];
			if (!isset($m_var["file"])) {
				Error("bad_filter",GetMessage(MSG_FILTER_PARAM,
				                              array("FILTER" => $filter,
				                                    "NUM"    => $i_count,
				                                    "NAME"   => "file"
				                              )),false,false);
				fclose($f_sock);
				exit;
			}
			//
			// open the file and read its contents
			//
			@           $fp = fopen($m_var["file"],"r");
			if ($fp === false) {
				Error("filter_error",GetMessage(MSG_FILTER_OPEN_FILE,
				                                array("FILTER" => $filter,
				                                      "FILE"   => $m_var["file"],
				                                      "ERROR"  => CheckString($php_errormsg)
				                                )),false,false);
				fclose($f_sock);
				exit;
			}
			$s_data  = "";
			$n_lines = 0;
			while (!feof($fp)) {
				if (($s_line = fgets($fp,2048)) === false) {
					if (feof($fp)) {
						break;
					} else {
						Error("filter_error",GetMessage(MSG_FILTER_FILE_ERROR,
						                                array("FILTER" => $filter,
						                                      "FILE"   => $m_var["file"],
						                                      "ERROR"  => CheckString($php_errormsg),
						                                      "NLINES" => $n_lines
						                                )),false,false);
						fclose($f_sock);
						exit;
					}
				}
				$s_data .= $s_line;
				$n_lines++;
			}

			fclose($fp);
			$m_request_data[] = "$s_name=" . urlencode($s_data);
		} else {
			$m_request_data[] = (string)$m_var;
		}
	}
	//
	// add the data
	//
	if (is_array($m_data)) {
		$m_request_data[] = "data=" . urlencode(implode(Settings::get('BODY_LF'),$m_data));
	} else {
		$m_request_data[] = "data=" . urlencode($m_data);
	}
	$s_request = implode("&",$m_request_data);

	if (($i_pos = strpos($s_site,"://")) !== false) {
		$s_site_name = substr($s_site,$i_pos + 3);
	} else {
		$s_site_name = $s_site;
	}

	fputs($f_sock,"POST $s_path HTTP/1.0\r\n");
	fputs($f_sock,"Host: $s_site_name\r\n");
	fputs($f_sock,"Content-Type: application/x-www-form-urlencoded\r\n");
	fputs($f_sock,"Content-Length: " . strlen($s_request) . "\r\n");
	fputs($f_sock,"\r\n");
	fputs($f_sock,"$s_request\r\n");

	//
	// now read the response
	//
	$m_hdr    = "";
	$m_data   = "";
	$b_in_hdr = true;
	$b_ok     = false;
	while (!feof($f_sock)) {
		if (($s_line = fgets($f_sock,2048)) === false) {
			if (feof($f_sock)) {
				break;
			} else {
				Error("filter_failed",GetMessage(MSG_FILTER_READ_ERROR,
				                                 array("FILTER" => $filter,
				                                       "ERROR"  => CheckString($php_errormsg)
				                                 )),false,false);
				fclose($f_sock);
				exit;
			}
		}
		//
		// look for an "__OK__" line
		//
		if (trim($s_line) == "__OK__") {
			$b_ok = true;
		} elseif ($b_in_hdr) {
			//
			// blank line signals end of header
			//
			if (trim($s_line) == "") {
				$b_in_hdr = false;
			} else {
				$m_hdr .= $s_line;
			}
		} else {
			$m_data .= $s_line;
		}
	}
	//
	// if not OK, then report error
	//
	if (!$b_ok) {
		Error("filter_failed",GetMessage(MSG_FILTER_NOT_OK,
		                                 array("FILTER" => $filter,
		                                       "DATA"   => $m_data
		                                 )),false,false);
		fclose($f_sock);
		exit;
	}
	fclose($f_sock);
	$b_in_here = false;
	return ($m_data);
}

//
// run data through a supported filter
//
function Filter($filter,$m_data)
{

	global $php_errormsg;
	static $b_in_here = false;

	//
	// prevent recursive errors
	//
	if ($b_in_here) {
		return ("<DATA DISCARDED>");
	}
	$b_in_here = true;

	//
	// Any errors sent in an alert are flagged to not run through the
	// filter - this also means the user's data won't be included in the
	// alert.
	// The reason for this is that the Filter is typically an encryption
	// program. If the filter fails, then sending the user's data in
	// clear text in an alert breaks the security of having encryption
	// in the first place!
	//

	//
	// find the filter
	//
	$a_filters = Settings::get('FILTERS');
	if (!isset($a_filters[$filter]) || $a_filters[$filter] == "") {
		//
		// check for SOCKET_FILTERS
		//
		$a_filters = Settings::get('SOCKET_FILTERS');
		if (!isset($a_filters[$filter]) || $a_filters[$filter] == "") {
			ErrorWithIgnore("bad_filter",GetMessage(MSG_FILTER_UNK,
			                                        array("FILTER" => $filter)),false,false);
			exit;
		}
		$m_data = SocketFilter($filter,$a_filters[$filter],$m_data);
	} elseif ($a_filters[$filter] == "null")
		//
		// do nothing - just return the original data unchanged
		//
	{
		;
	} elseif ($a_filters[$filter] == "csv") {
		$m_data = BuiltinFilterCSV();
	} else {
		$cmd = $a_filters[$filter];
		//
		// get the program name - assumed to be the first blank-separated word
		//
		$a_words = preg_split('/\s+/',$cmd);
		$prog    = $a_words[0];

		$s_cwd = getcwd();
		//
		// change to the directory that contains the filter program
		//
		$dirname = dirname($prog);
		if ($dirname != "" && $dirname != "." && !chdir($dirname)) {
			Error("chdir_filter",GetMessage(MSG_FILTER_CHDIR,
			                                array("DIR"   => $dirname,"FILTER" => $filter,
			                                      "ERROR" => CheckString($php_errormsg)
			                                )),false,false);
			exit;
		}

		//
		// the output of the filter goes to a temporary file; this works
		// OK on Windows too, even with the Unix shell syntax.
		//
		$temp_file       = GetTempName("FMF");
		$temp_error_file = GetTempName("FME");
		$cmd             = "$cmd >$temp_file 2>$temp_error_file";
		//
		// start the filter
		//
		$pipe = popen($cmd,"w");
		if ($pipe === false) {
			$s_sv_err = CheckString($php_errormsg);
			$err      = join('',file($temp_error_file));
			unlink($temp_file);
			unlink($temp_error_file);
			Error("filter_not_found",GetMessage(MSG_FILTER_NOTFOUND,
			                                    array("CMD"   => $cmd,"FILTER" => $filter,
			                                          "ERROR" => $s_sv_err
			                                    )),false,false,$err);
			exit;
		}
		//
		// write the data to the filter
		//
		if (is_array($m_data)) {
			fwrite($pipe,implode(Settings::get('BODY_LF'),$m_data));
		} else {
			fwrite($pipe,$m_data);
		}
		if (($i_st = pclose($pipe)) != 0) {
			$s_sv_err = CheckString($php_errormsg);
			$err      = join('',file($temp_error_file));
			unlink($temp_file);
			unlink($temp_error_file);
			Error("filter_failed",GetMessage(MSG_FILTER_ERROR,
			                                 array("FILTER" => $filter,
			                                       "ERROR"  => $s_sv_err,
			                                       "STATUS" => $i_st
			                                 )),false,false,$err);
			exit;
		}
		//
		// read in the filter's output and return as the data to be sent
		//
		$m_data = join('',file($temp_file));
		unlink($temp_error_file);
		unlink($temp_file);

		//
		// return to previous directory
		//
		chdir($s_cwd);
	}
	$b_in_here = false;
	return ($m_data);
}

/*
 * Function:    FilterFiles
 * Parameters:  $a_files    list of file uploads to filter
 * Returns:     void
 * Description:     
 *  Run the given files through any filter for which they are specified.
 */
function    FilterFiles(&$a_files)
{
	global $SPECIAL_VALUES;

	FMDebug("FilterFiles " . count($a_files));
	if (!GetFilterSpec($s_filter,$a_filter_list,true) || $a_filter_list === false)
		//
		// no filter or file fields to filter
		//
	{
		return;
	}
	if (($s_mime = GetFilterAttrib($s_filter,"MIME")) === false) {
		$s_mime = "";
	}
	//
	// now, for each file, check if it is specified in the filter list
	//
	foreach ($a_files as $s_fld => $a_upload) {
		FMDebug("Checking $s_fld");
		if (!IsUploadedFile($a_upload)) {
			FMDebug("Not uploaded");
			//
			// failed security check
			//
			continue;
		}
		if (!in_array($s_fld,$a_filter_list,true)) {
			FMDebug("Not to be filtered");
			continue;
		}
		/*** not sure what to do with this....
		 * if (isset($a_upload["error"]))
		 * //
		 * // there was an upload error
		 * //
		 * continue;
		 ***/
		//
		// this file upload has been specified for filtering
		//
		$s_file_name = $a_upload["tmp_name"];
		//
		// check if the file has been saved elsewhere
		//
		if (isset($a_upload["saved_as"]) && !empty($a_upload["saved_as"])) {
			$s_file_name = $a_upload["saved_as"];
		}
		FMDebug("File name is $s_file_name");
		//
		// read in the file
		//
		if (($s_data = ReadInFile($s_file_name,"upload")) === false) {
			Error("filter_files",GetMessage(MSG_FILE_UPLOAD_ERR_UNK,array("ERRNO" => "reading $s_fld")),false,false);
		}
		//
		// filter and write it back out to the same file
		//
		$s_data = Filter($s_filter,$s_data);
		if (!WriteOutFile($s_file_name,$s_data,"upload")) {
			Error("filter_files",GetMessage(MSG_FILE_UPLOAD_ERR_UNK,array("ERRNO" => "writing $s_fld")),false,false);
		}
		//
		// update size and MIME type for this upload
		//
		$a_upload["size"] = strlen($s_data);
		if ($s_mime !== "") {
			$a_upload["type"] = $s_mime;
		}
		$a_files[$s_fld] = $a_upload;
	}
}

/* 
 * Function:    ReadInFile
 * Parameters:  $s_file_name    the name of the file
 *              $s_file_error_type type of file for any error message
 *              $b_text         if true, read file as text
 * Returns:     mixed           the entire contents of the file
 *                              as a string, or false on error
 * Description:     
 *  Reads the contents of a file into a string.
 */
function    ReadInFile($s_file_name,$s_file_error_type,$b_text = false)
{
	global $php_errormsg;

	if (($fp = @fopen($s_file_name,"r" . ($b_text ? "t" : "b"))) === false) {
		SendAlert(GetMessage(MSG_FILE_OPEN_ERROR,array("NAME"  => $s_file_name,
		                                               "TYPE"  => "read " . $s_file_error_type,
		                                               "ERROR" => CheckString($php_errormsg)
		)));
		return (false);
	}
	$s_data = "";
	while (!feof($fp)) {
		$s_data .= fread($fp,8192);
	}
	@fclose($fp);
	return ($s_data);
}

/* 
 * Function:    WriteOutFile
 * Parameters:  $s_file_name    the name of the file
 *              $s_data         the data to write
 *              $s_file_error_type type of file for any error message
 *              $b_text         if true, read file as text
 * Returns:     bool            true on success, otherwise false
 * Description:     
 *  Writes the contents of a file from a string.
 */
function    WriteOutFile($s_file_name,$s_data,$s_file_error_type,$b_text = false)
{
	global $php_errormsg;

	if (($fp = @fopen($s_file_name,"w" . ($b_text ? "t" : "b"))) === false) {
		SendAlert(GetMessage(MSG_FILE_OPEN_ERROR,array("NAME"  => $s_file_name,
		                                               "TYPE"  => "write " . $s_file_error_type,
		                                               "ERROR" => CheckString($php_errormsg)
		)));
		return (false);
	}
	if (fwrite($fp,$s_data) < strlen($s_data)) {
		@fclose($fp);
		return (false);
	}
	@fclose($fp);
	return (true);
}

/*
 * Class:       CSVFormat
 * Description:     
 *  Manages formatting of CSV content.
 */

class   CSVFormat
{
	var $_cSep; /* field separator character */
	var $_cQuote; /* field quote character */
	var $_cIntSep; /* internal separator character (for lists) */
	var $_sEscPolicy; /* escape processing policy */
	var $_sCleanFunc; /* cleaning function for fields */

	/*
     * Method:      CSVFormat ctor
     * Parameters:  $c_sep          the field separator
     *              $c_quote        the quote character to use
     *              $c_int_sep      the internal field separator to use
     *              $s_esc_policy   escape processing policy to use
     *              $s_clean_func   a cleaning function
     * Returns:     n/a
     * Description: 
     *  Constructs the object.
     */
	function    CSVFormat($c_sep = ',',$c_quote = '"',$c_int_sep = ';',
	                      $s_esc_policy = "backslash",$s_clean_func = NULL)
	{
		$this->SetSep($c_sep);
		$this->SetQuote($c_quote);
		$this->SetIntSep($c_int_sep);
		$this->SetEscPolicy($s_esc_policy);
		$this->SetCleanFunc($s_clean_func);
	}

	/*
     * Method:      SetEscPolicy
     * Parameters:  $s_esc_policy   a string specifying the escape processing
     *                              policy to use
     * Returns:     void
     * Description: 
     *  Set the escape processing policy.
     */
	function    SetEscPolicy($s_esc_policy)
	{
		switch ($s_esc_policy) {
			default: /* should generate a warning */
			case "backslash":
				$this->_sEscPolicy = "b";
				break;
			case "double":
				$this->_sEscPolicy = "d";
				break;
			case "strip":
				$this->_sEscPolicy = "s";
				break;
			case "conv":
				$this->_sEscPolicy = "c";
				break;
		}
	}

	/*
     * Method:      SetSep
     * Parameters:  $c_sep      the separator character to use
     * Returns:     void
     * Description: 
     *  Set the separator character for between fields.
     */
	function    SetSep($c_sep)
	{
		$this->_cSep = $c_sep;
	}

	/*
     * Method:      SetQuote
     * Parameters:  $c_quote      the quote character to use
     * Returns:     void
     * Description: 
     *  Set the quote character for quoting fields.
     */
	function    SetQuote($c_quote)
	{
		$this->_cQuote = $c_quote;
	}

	/*
     * Method:      SetIntSep
     * Parameters:  $c_int_sep      the internal separator character to use
     * Returns:     void
     * Description: 
     *  Set the internal separator character for inside fields.
     */
	function    SetIntSep($c_int_sep)
	{
		$this->_cIntSep = $c_int_sep;
	}

	/*
     * Method:      SetCleanFunc
     * Parameters:  $s_clean_func   the name of a cleaning function (can be NULL)
     * Returns:     void
     * Description: 
     *  Set the cleaning function for fields.
     */
	function    SetCleanFunc($s_clean_func)
	{
		$this->_sCleanFunc = $s_clean_func;
	}

	/*
     * Method:      _Escape
     * Parameters:  $m_value    the field value; string or array of strings 
     * Returns:     mixed       the field value escaped according to the
     *                          escape processing policy
     * Description: 
     *  Escapes a field value according to the configured requirements.
     */
	function    _Escape($m_value)
	{
		switch ($this->_sEscPolicy) {
			default: /* should generate an error */
			case "b":
				/*
                     * 'backslash' escape policy: replace \ with \\ and
                     * " with \"
                     */
				$m_value = str_replace("\\","\\\\",$m_value);
				$m_value = str_replace($this->_cQuote,"\\" . $this->_cQuote,
				                       $m_value);
				break;
			case "d":
				/*
                     * 'double' escape policy: replace " with ""
                     * This is suitable for Microsoft apps such as Excel
                     * and Access. It also meets the specification of
                     * RFC4180, though this RFC only specified double
                     * quotes whereas we handle any quote character.
                     */
				$m_value = str_replace($this->_cQuote,
				                       $this->_cQuote . $this->_cQuote,$m_value);
				break;
			case "s":
				/*
                     * 'strip' escape policy: strip quotes
                     */
				$m_value = str_replace($this->_cQuote,"",$m_value);
				break;
			case "c":
				/*
                     * 'conv' escape policy: convert quotes to the other quotes
                     */
				switch ($this->_cQuote) {
					case '"':
						/*
                         * convert double quotes in the data to single quotes
                         */
						$m_value = str_replace("\"","'",$m_value);
						break;
					case '\'':
						/*
                         * convert single quotes in the data to double quotes
                         */
						$m_value = str_replace("'","\"",$m_value);
						break;
					default:
						/*
                         * otherwise, leave the data unchanged
                         */
						break;
				}
				break;
		}
		return ($m_value);
	}

	/*
     * Method:      _Format
     * Parameters:  $s_value    the string value to format
     *              $s_format   a format specification
     *                          a list of characters:
     *                              c   CleanValue
     *                              s   force to be a string
     *                              r   remove carriage returns
     * Returns:     string      the formatted value
     * Description: 
     *  Formats a value.
     */
	function    _Format($s_value,$s_format = "")
	{
		$s_value  = $this->_Escape($s_value);
		$s_prefix = "";
		/*
             * now implement any special formatting to overcome
             * problems with importing
             */
		$i_len = strlen($s_format);
		for ($ii = 0 ; $ii < $i_len ; $ii++) {
			switch ($s_format[$ii]) {
				case "c":
					/*
                     * implement "c" formatting - CleanValue
                     */
					$s_value = CleanValue($s_value);
					break;
				case "r":
					/*
                     * implement "r" formatting - remove
                     * carriage returns.  Useful for Microsoft Excel
                     */
					$s_value = str_replace("\r","",$s_value);
					break;
				case "s":
					/*
                     * implement "s" formatting - force
                     * a value to be a string (by making it a string
                     * formula).  Useful for Microsoft Excel and OpenOffice
                     * spreadsheet, which don't understand numeric phone
                     * numbers, for example.
                     */
					if (strlen($s_value) > 0) {
						$s_prefix = "=";
					}
					break;
			}
		}

		return ($s_prefix . $this->_cQuote . $s_value . $this->_cQuote);
	}

	/*
     * Method:      _GetColumn
     * Parameters:  $s_col_spec a column specification
     * Returns:     array       the column name and the format specifier, if
     *                          any
     * Description: 
     *  Returns the column name and any format specifier.
     */
	function    _GetColumn($s_col_spec)
	{
		$s_format = "";
		if (($i_pos = strpos($s_col_spec,":")) !== false) {
			$s_col_name = trim(substr($s_col_spec,0,$i_pos));
			$s_format   = trim(substr($s_col_spec,$i_pos + 1));
		} else {
			$s_col_name = $s_col_spec;
		}
		return (array($s_col_name,$s_format));
	}

	/*
     * Method:      MakeCSVRecord
     * Parameters:  $a_column_list  a list of column names (field names) to
     *                              include; can include format specifiers
     *              $a_vars         raw data array indexed by column name
     *                              (field name).
     *                              A data value can be a string or an array
     *                              of strings.
     * Returns:     string          the comma-separated value    
     * Description: 
     *  Creates a single CSV record for a list of columns.
     */
	function    MakeCSVRecord($a_column_list,$a_vars)
	{
		$s_rec     = "";
		$n_columns = count($a_column_list);
		for ($ii = 0 ; $ii < $n_columns ; $ii++) {
			list($s_col_name,$s_format) = $this->_GetColumn($a_column_list[$ii]);
			/*
                 * if a column is specified it must be included, even if there
                 * is no data for it.
                 */
			$s_value = GetFieldValue($s_col_name,$a_vars,$this->_cIntSep);
			if (isset($this->_sCleanFunc)) {
				$s_func  = $this->_sCleanFunc;
				$s_value = $s_func($s_value);
			}

			$s_value = $this->_Format($s_value,$s_format);
			if ($ii > 0) /*
                     * prepend the separator from the second field onwards
                     */ {
				$s_rec .= $this->_cSep;
			}
			$s_rec .= $s_value;
		}
		return ($s_rec);
	}

	/*
     * Method:      MakeHeading
     * Parameters:  $a_column_list  a list of column names (field names) to
     *                              include
     * Returns:     string          the comma-separated heading record    
     * Description: 
     *  Creates a heading record for the CSV data.
     */
	function    MakeHeading($a_column_list)
	{
		$s_rec     = "";
		$n_columns = count($a_column_list);
		for ($ii = 0 ; $ii < $n_columns ; $ii++) {
			list($s_col_name,$s_format) = $this->_GetColumn($a_column_list[$ii]);
			$s_value = $this->_Format($s_col_name);
			if ($ii > 0) /*
                     * prepend the separator from the second field onwards
                     */ {
				$s_rec .= $this->_cSep;
			}
			$s_rec .= $s_value;
		}
		return ($s_rec);
	}
}

/*
     * Built-in filter.  Generates CSV (comma separated values) content from
     * the submitted fields. The special field "filter_fields" determines
     * which fields to include in the CSV content.
     * The following options are support in "filter_options":
     *      CSVHeading  if set, includes a heading line first with the field names
     *      CSVSep      specifies a separator character instead of comma
     *      CSVIntSep   specifies an internal separator character for lists
     *      CSVQuote    specifies the character to use to quote each column; default
     *                  is double quotes
     *      CSVEscPolicy controls the way quotes are escaped in the data.  Supported
     *                  values are: backslash (the default),double,strip
     *      CSVRaw      if set, then the fields are recorded as raw values and
     *                  are *not* cleaned according to FormMail's normal field
     *                  cleaning process.
     * If the "filter_fields" field does not exist, then the "csvcolumns" field is
     * used instead.  If neither exist, then all fields are included along with
     * a Heading line.
     */
function BuiltinFilterCSV()
{
	global $aAllRawValues,$aRawDataValues,$SPECIAL_VALUES;

	$b_heading     = false;
	$a_column_list = array();
	$s_cols        = $SPECIAL_VALUES["filter_fields"];
	if (!isset($s_cols) || empty($s_cols) || !is_string($s_cols)) {
		$s_cols = $SPECIAL_VALUES["csvcolumns"];
		if (!isset($s_cols) || empty($s_cols) || !is_string($s_cols)) {
			/*
                 * neither filter_fields nor csvcolumns defined - get all
                 * columns
                 */
			$s_cols = "";
			/*
                 * special case - include these two special fields
                 */
			$a_column_list = array("email","realname");
			/*
                 * now include all the data fields
                 */
			$a_column_list = array_merge($a_column_list,
			                             array_keys($aRawDataValues));
			$b_heading     = true;
		}
	}
	if (empty($a_column_list)) {
		$a_column_list = TrimArray(explode(",",$s_cols));
	}

	$csv_format = new CSVFormat();

	/*
         * get the various options and set them
         */
	$m_temp = GetFilterOption("CSVQuote");
	if (isset($m_temp)) {
		$csv_format->SetQuote($m_temp);
	}
	$m_temp = GetFilterOption("CSVSep");
	if (isset($m_temp)) {
		$csv_format->SetSep($m_temp);
	}
	$m_temp = GetFilterOption("CSVIntSep");
	if (isset($m_temp)) {
		$csv_format->SetIntSep($m_temp);
	}
	$m_temp = GetFilterOption("CSVEscPolicy");
	if (isset($m_temp)) {
		$csv_format->SetEscPolicy($m_temp);
	}
	$m_temp = GetFilterOption("CSVHeading");
	if (isset($m_temp)) {
		$b_heading = true;
	}

	/*
         * clean fields unless CSVRaw is specified
         */
	$m_temp = GetFilterOption("CSVRaw");
	if (!isset($m_temp)) {
		$csv_format->SetCleanFunc(create_function('$m_value',
		                                          'return CleanValue($m_value);'));
	}

	$s_csv = $csv_format->MakeCSVRecord($a_column_list,$aAllRawValues);

	if ($b_heading) {
		$s_head = $csv_format->MakeHeading($a_column_list);
		/*
             * return the heading and the record with $CSVLINE as record separator
             */
		return ($s_head . Settings::get('CSVLINE') . $s_csv . Settings::get('CSVLINE'));
	} else /*
             * return this record with $CSVLINE appended
             */ {
		return ($s_csv . Settings::get('CSVLINE'));
	}
}

$aSubstituteErrors  = array();
$SubstituteFields   = NULL;
$sSubstituteMissing = NULL;

//
// Run htmlspecialchars on every value in an array.
//
function ArrayHTMLSpecialChars($a_list)
{
	$a_new = array();
	foreach ($a_list as $m_key => $m_value) {
		if (is_array($m_value)) {
			$a_new[$m_key] = ArrayHTMLSpecialChars($m_value);
		} else {
			$a_new[$m_key] = htmlspecialchars($m_value);
		}
	}
	return ($a_new);
}

//
// Truncate a value based on the specified maximums.
//
function Truncate($s_value,$n_max_chars,$n_max_lines)
{
	if ($n_max_lines > 0) {
		$a_lines = explode("\n",$s_value);
		if (count($a_lines) > $n_max_lines) {
			$a_lines = array_slice($a_lines,0,$n_max_lines);
			$s_value = implode("\n",$a_lines);
			$s_value .= "...";
		}
	}
	if ($n_max_chars > 0) {
		$a_lines = explode("\n",$s_value);
		for ($ii = 0 ; $ii < count($a_lines) ; $ii++) {
			$n_len = strlen($a_lines[$ii]);
			$s_eol = "";
			if (substr($a_lines[$ii],-1) == "\n") {
				$n_len--;
				$s_eol = "\n";
			}
			if ($n_len > $n_max_chars) {
				$a_lines[$ii] = substr($a_lines[$ii],0,$n_max_chars) . "..." . $s_eol;
			}
		}
		$s_value = implode("\n",$a_lines);
	}
	return ($s_value);
}

//
// Worker function for SubstituteValue and SubstituteValueForPage.
// Returns the value of the matched variable name.
// Variables are searched for in the global $SubstituteFields.
// If no such variable exists, an error is reported or the given
// replacement string is used.
// Errors are stored in the global $aSubstituteErrors.
//
function SubstituteValueWorker($a_matches,$s_repl,$b_html = true)
{
	/**
	 * @global FieldManager $SubstituteFields
	 */
	global $aSubstituteErrors,$SubstituteFields,$SPECIAL_VALUES;

	$b_insert_br = true; // option to put "<br />" tags before newlines in HTML templates
	$n_max_chars = 0;
	$n_max_lines = 0;
	$s_list_sep  = $SPECIAL_VALUES['template_list_sep'];
	$b_text_subs = false;

	$s_name = $a_matches[0];
	assert(strlen($s_name) > 1 && $s_name[0] == '$');
	$s_name = substr($s_name,1);
	if (($i_len = strlen($s_name)) > 0 && $s_name[0] == '{') {
		assert($s_name[$i_len - 1] == '}');
		$s_name = substr($s_name,1,-1);
		//
		// grab any processing options
		//
		$a_args = explode(":",$s_name);
		$s_name = $a_args[0];
		if (($n_args = count($a_args)) > 1) {
			for ($ii = 1 ; $ii < $n_args ; $ii++) {
				//
				// some options are followed by =X
				// where X is a value
				//
				$s_param = "";
				if (($i_pos = strpos($a_args[$ii],'=')) !== false) {
					$s_param = substr($a_args[$ii],$i_pos + 1);
					$s_opt   = substr($a_args[$ii],0,$i_pos);
				} else {
					$s_opt = $a_args[$ii];
				}
				switch ($s_opt) {
					case "nobr":
						$b_insert_br = false;
						break;
					case "chars":
						if ($s_param !== "") {
							$n_max_chars = (int)$s_param;
						}
						break;
					case "lines":
						if ($s_param !== "") {
							$n_max_lines = (int)$s_param;
						}
						break;
					case "sep":
						if ($s_param !== "") {
							$s_list_sep = $s_param;
						}
						break;
					case "subs":
						$b_text_subs = true;
						break;
				}
			}
		}
	}
	$s_value = "";
	$s_mesg  = "";
	if ($SubstituteFields->IsFieldSet($s_name) &&
	    !$SubstituteFields->TestFieldEmpty($s_name,$s_mesg)
	) {
		if ($b_html)
			//
			// Up to and including version 8.24, the code used
			// htmlspecialchars.  Version 8.28 caused UTF-8 template
			// processing to break, because it started using htmlentities
			// without specifying the charset.
			//
		{
			$s_value = $SubstituteFields->GetSafeFieldValue($s_name,$b_text_subs,$s_list_sep);
		} else {
			$s_value = $SubstituteFields->GetFieldValue($s_name,$s_list_sep);
		}
		$s_value = Truncate($s_value,$n_max_chars,$n_max_lines);
		if ($b_html && $b_insert_br)
			//
			// Insert HTML line breaks before newlines.
			//
		{
			$s_value = nl2br($s_value);
		}
	} elseif (isset($SPECIAL_VALUES[$s_name])) {
		$s_value = $b_html ?
			htmlspecialchars((string)$SPECIAL_VALUES[$s_name]) :
			(string)$SPECIAL_VALUES[$s_name];
		$s_value = Truncate($s_value,$n_max_chars,$n_max_lines);
	} elseif (isset($s_repl))
		//
		// If a replacement value has been specified use it, and
		// don't call htmlspecialchars.  This allows the use
		// of HTML tags in a replacement string.
		//
	{
		$s_value = $s_repl;
	} else {
		$s_value = "";
	}
	return ($s_value);
}

//
// Callback function for preg_replace_callback.  Returns the value
// of the matched variable name.
// Variables are searched for in the global $SubstituteFields.
// If no such variable exists, an error is reported or an special
// replacement string is used.
// Errors are stored in the global $aSubstituteErrors.
//
function SubstituteValue($a_matches)
{
	global $sSubstituteMissing;

	return (SubstituteValueWorker($a_matches,$sSubstituteMissing));
}

//
// Callback function for preg_replace_callback.  Returns the value
// of the matched variable name.
// Variables are searched for in the global $SubstituteFields.
// If no such variable exists, an error is reported or an special
// replacement string is used.
// Errors are stored in the global $aSubstituteErrors.
//
function SubstituteValuePlain($a_matches)
{
	global $sSubstituteMissing;

	return (SubstituteValueWorker($a_matches,$sSubstituteMissing,false));
}

//
// Callback function for preg_replace_callback.  Returns the value
// of the matched variable name.
// Variables are searched for in the global $SubstituteFields.
// If no such variable exists, the empty string is substituted.
// Errors are stored in the global $aSubstituteErrors.
//
function SubstituteValueForPage($a_matches)
{
	return (SubstituteValueWorker($a_matches,""));
}

//
// Callback function for preg_replace_callback.  Returns
// exactly what was matched.
//
function SubstituteValueDummy($a_matches)
{
	return ($a_matches[0]);
}

//
// Process the given HTML template and fill the fields.
//
function DoProcessTemplate($s_dir,$s_url,$s_template,&$a_lines,
                           $a_values,$s_missing,$s_subs_func)
{
	global $aSubstituteErrors,$SubstituteFields,$sSubstituteMissing;

	if (($a_template_lines = LoadTemplate($s_template,$s_dir,
	                                      $s_url,true)) === false
	) {
		return (false);
	}
	FMDebug("Template '$s_template' contains " . count($a_template_lines) . " lines");

	$b_ok = true;
	//
	// initialize the errors list
	//
	$aSubstituteErrors = array();
	//
	// initialize the values
	//
	$SubstituteFields   = new FieldManager($a_values,array());
	$sSubstituteMissing = $s_missing;

	foreach ($a_template_lines as $s_line) {
		//
		// search for words in these forms:
		//      $word
		//      ${word:options}
		// where word begins with an alphabetic character and
		// consists of alphanumeric and underscore
		//
		$a_lines[] = preg_replace_callback('/\$[a-z][a-z0-9_]*|\$\{[a-z][a-z0-9_]*(:[^\}]*)*\}/i',
		                                   $s_subs_func,$s_line);
	}

	FMDebug("DoProcessTemplate error count=" . count($aSubstituteErrors));
	if (count($aSubstituteErrors) != 0) {
		SendAlert(GetMessage(MSG_TEMPLATE_ERRORS,array("NAME" => $s_template)) .
		          implode("\n",$aSubstituteErrors));
		$b_ok = false;
	}
	global $FMCTemplProc;

	//
	// note that it's possible for an old version of FormMail Computation
	// module to get loaded which doesn't provide FMCTemplProc
	//
	if ($b_ok && Settings::get('ADVANCED_TEMPLATES') && isset($FMCTemplProc)) {
		$s_buf = implode("\n",$a_lines);
		/*
             * Look for a string that means we can skip advanced template
             * processing on this template.  The string is "FormMail-Basic-Template".
             */
		if (strpos($s_buf,"FormMail-Basic-Template") === FALSE) {
			$a_mesgs = array();
			/*foreach ($a_lines as $i_lno=>$s_line)
                if (strpos($s_line,"\n") !== false)
                    SendAlert("Line $i_lno has a newline");*/
			set_time_limit(60);
			if (($m_result = $FMCTemplProc->Process($s_buf,$a_mesgs)) === false) {
				$s_msgs = "\n";
				foreach ($a_mesgs as $a_msg) {
					$s_msgs .= "Line " . $a_msg["LINE"];
					$s_msgs .= ", position " . $a_msg["CHAR"] . ": ";
					$s_msgs .= $a_msg["MSG"] . "\n";
				}
				Error("fmadvtemplates",GetMessage(MSG_TEMPL_PROC,
				                                  array("ERRORS" => $s_msgs)),false,false);
				$b_ok = false;
			} else {
				/*foreach ($m_result as $i_lno=>$s_line)
                    if (($nn = substr_count($s_line,"\n")) > 1)
                        SendAlert("Result line $i_lno has $nn newlines");*/
				//
				// strip the new lines
				//
				$a_lines = explode("\n",implode("",$m_result));
			}
			$a_alerts = $FMCTemplProc->GetAlerts();
			if (count($a_alerts) > 0) {
				SendAlert(GetMessage(MSG_TEMPL_ALERT,
				                     array("ALERTS" => implode("\n",$a_alerts))));
			}
			$a_debug = $FMCTemplProc->GetDebug();
			if (count($a_debug) > 0) {
				SendAlert(GetMessage(MSG_TEMPL_DEBUG,
				                     array("DEBUG" => implode("\n",$a_debug))));
			}
		}
	}

	return ($b_ok);
}

//
// Process the given HTML template and fill the fields.
//
function ProcessTemplate($s_template,&$a_lines,$a_values,$s_missing = NULL,
                         $s_subs_func = 'SubstituteValue')
{

	if (Settings::isEmpty('TEMPLATEDIR') && Settings::isEmpty('TEMPLATEURL')) {
		SendAlert(GetMessage(MSG_TEMPLATES));
		return (false);
	}
	return (DoProcessTemplate(Settings::get('TEMPLATEDIR'),Settings::get('TEMPLATEURL'),$s_template,$a_lines,
	                          $a_values,$s_missing,$s_subs_func));
}

//
// Output the given HTML template after filling in the fields.
//
function OutputTemplate($s_template,$a_values)
{
	$a_lines = array();
	if (!ProcessTemplate($s_template,$a_lines,$a_values,"",'SubstituteValueForPage')) {
		Error("template_failed",GetMessage(MSG_TEMPLATE_FAILED,
		                                   array("NAME" => $s_template)),false,false);
	} else {
		for ($ii = 0 ; $ii < count($a_lines) ; $ii++) {
			echo $a_lines[$ii] . "\n";
		}
	}
}

//
// This function handles input type fields.
//
function RemoveFieldValue($s_name,$s_buf)
{
	//
	// we search for:
	//  <input ... name="thename" ... >
	// and change it to:
	//  <!-- disabled by FormMail: input ... name="thename" ... -->
	//

	// handle name attribute first
	$s_pat = '/<(\s*input[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*)>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'<!-- disabled by FormMail: $1 -->',$s_buf);

	return ($s_buf);
}

//
// Quote special characters in a replacement expression
// for preg_replace.
//
function RegReplaceQuote($s_value)
{
	return (str_replace('$','\\$',str_replace('\\','\\\\',$s_value)));
}

//
// This function handles input type "text" and "password"
//
function FixInputText($s_name,$s_value,$s_buf)
{
	//
	// we search for:
	//  <input type="text" name="thename"...
	// and change it to:
	//  <input type="text" name="thename" value="thevalue" ...
	//
	// Note that the value attribute must appear *after* the
	// type and name attributes.
	//

	//
	// first strip any current value attribute for the field
	//

	//
	// (?:) is a grouping subpattern that does no capturing
	//

	// handle type attribute first
	$s_pat = '/(<\s*input[^>]*type="(?:text|password)"[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*)(value="[^"]*")([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$3$4>',$s_buf);

	// handle name attribute first
	$s_pat = '/(<\s*input[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*type="(?:text|password)"[^>]*)(value="[^"]*")([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$3$4>',$s_buf);

	//
	// now add in the new value
	//
	$s_repl = '$1 value="' . htmlspecialchars(RegReplaceQuote($s_value)) . '" $2>';

	// handle type attribute first
	$s_pat = '/(<\s*input[^>]*type="(?:text|password)"[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,$s_repl,$s_buf);

	// handle name attribute first
	$s_pat = '/(<\s*input[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*type="(?:text|password)"[^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,$s_repl,$s_buf);

	return ($s_buf);
}

//
// This function handles textareas.
//
function FixTextArea($s_name,$s_value,$s_buf)
{
	//
	// we search for:
	//  <textarea name="thename"...>value</textarea>
	// and change it to:
	//  <textarea name="thename"...>new value</textarea>
	//

	$s_pat = '/(<\s*textarea[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*)>.*?<\s*\/\s*textarea\s*>';
	$s_pat .= '/ims';
	//
	// we exclude the closing '>' from the match above so that
	// we can put it below.  We need to do this so that the replacement
	// string is not faulty if the value begins with a digit:
	//      $19 Some Street
	//
	$s_repl = '$1>' . htmlspecialchars(RegReplaceQuote($s_value)) . '</textarea>';
	$s_buf  = preg_replace($s_pat,$s_repl,$s_buf);

	return ($s_buf);
}

//
// This function handles radio buttons and non-array checkboxes.
//
function FixButton($s_name,$s_value,$s_buf)
{
	//
	// we search for:
	//  <input type="radio" name="thename" value="thevalue" ...
	// and change it to:
	//  <input type="radio" name="thename" value="thevalue" checked="checked"
	//
	// Note that the value attribute must appear *after* the
	// type and name attributes.
	//

	//
	// first strip any current checked attributes
	//

	//
	// (?:) is a grouping subpattern that does no capturing
	//

	// handle type attribute first
	// match: input tag with type 'radio' or 'checkbox' with attribute
	// 'checked' or 'checked="checked"'
	//              <A NAME="PatternInfo">
	//      [^>]*?[^"\w] matches up to a word boundary starting with
	//      'checked' but not '"checked'
	//      (="checked"|(?=[^"\w]))? this matches:
	//              nothing
	//              ="checked"
	//              any character except a word character or " (without
	//              consuming it)
	//
	$s_pat = '/(<\s*input[^>]*type="(?:radio|checkbox)"[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*?[^"\w])checked(="checked"|(?=[^"\w]))?([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$3$4>',$s_buf);

	// handle name attribute first
	$s_pat = '/(<\s*input[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*type="(?:radio|checkbox)"[^>]*?[^"\w])checked(="checked"|(?=[^"\w]))?([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$3$4>',$s_buf);

	// handle type attribute first
	$s_pat = '/(<\s*input[^>]*type="(?:radio|checkbox)"[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*value="';
	$s_pat .= preg_quote($s_value,"/");
	$s_pat .= '")([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$2 checked="checked" $3>',$s_buf);

	// handle name attribute first
	$s_pat = '/(<\s*input[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '"[^>]*type="(?:radio|checkbox)"[^>]*value="';
	$s_pat .= preg_quote($s_value,"/");
	$s_pat .= '")([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$2 checked="checked" $3>',$s_buf);

	return ($s_buf);
}

//
// This function handles checkboxes as an array of values.
//
function FixCheckboxes($s_name,$a_values,$s_buf)
{
	//global $aDebug;

	//
	// we search for:
	//  <input type="checkbox" name="thename" value="thevalue" ...
	// and change it to:
	//  <input type="checkbox" name="thename" value="thevalue" checked
	//
	// Note that the value attribute must appear *after* the
	// type and name attributes.
	//

	//
	// first strip any current checked attributes
	//
	//$aDebug[] = "FixCheckboxes: Name='$s_name'";

	// handle type attribute first
	// see <A HREF="fmbadhandler.php#PatternInfo">
	$s_pat = '/(<\s*input[^>]*type="checkbox"[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '\[]"[^>]*?[^"\w])checked(="checked"|(?=[^"\w]))?([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$3$4>',$s_buf);

	// handle name attribute first
	$s_pat = '/(<\s*input[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '\[]"[^>]*type="checkbox"[^>]*?[^"\w])checked(="checked"|(?=[^"\w]))?([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$3$4>',$s_buf);

	foreach ($a_values as $s_value) {
		// handle type attribute first
		$s_pat = '/(<\s*input[^>]*type="checkbox"[^>]*name="';
		$s_pat .= preg_quote($s_name,"/");
		$s_pat .= '\[\]"[^>]*value="';
		$s_pat .= preg_quote($s_value,"/");
		$s_pat .= '")([^>]*?)(\s*\/\s*)?>';
		$s_pat .= '/ims';
		$s_buf = preg_replace($s_pat,'$1$2 checked="checked"$3>',$s_buf);
		//$aDebug[] = "Name='$s_name', pat='$s_pat'";

		// handle name attribute first
		$s_pat = '/(<\s*input[^>]*name="';
		$s_pat .= preg_quote($s_name,"/");
		$s_pat .= '\[\]"[^>]*type="checkbox"[^>]*value="';
		$s_pat .= preg_quote($s_value,"/");
		$s_pat .= '")([^>]*?)(\s*\/\s*)?>';
		$s_pat .= '/ims';
		$s_buf = preg_replace($s_pat,'$1$2 checked="checked">',$s_buf);
	}
	return ($s_buf);
}

//
// This function handles selects.
//
function FixSelect($s_name,$s_value,$s_buf)
{
	//
	// we search for:
	//  <select name="thename"...>
	//  <option value="thevalue">...</option>
	//  </select>
	//

	$s_pat = '/(<\s*select[^>]*name="';
	$s_pat .= preg_quote($s_name,"/");
	$s_pat .= '".*?<\s*option[^>]*value="';
	$s_pat .= preg_quote($s_value,"/");
	$s_pat .= '"[^>]*)>';
	$s_pat .= '/ims';
	$s_repl = '$1 selected="selected">';
	//  echo "<p>pat: ".htmlspecialchars($s_pat);
	$s_buf = preg_replace($s_pat,$s_repl,$s_buf);

	return ($s_buf);
}

//
// This function handles multiple selects.
//
function FixMultiSelect($s_name,$a_values,$s_buf)
{
	//
	// we search for:
	//  <select name="thename"...>
	//  <option value="thevalue">...</option>
	//  </select>
	//

	foreach ($a_values as $s_value) {
		$s_pat = '/(<\s*select[^>]*name="';
		$s_pat .= preg_quote($s_name,"/");
		$s_pat .= '\[\]".*?<\s*option[^>]*value="';
		$s_pat .= preg_quote($s_value,"/");
		$s_pat .= '"[^>]*)>';
		$s_pat .= '/ims';
		$s_repl = '$1 selected="selected">';
		//  echo "<p>pat: ".htmlspecialchars($s_pat);
		$s_buf = preg_replace($s_pat,$s_repl,$s_buf);
	}
	return ($s_buf);
}

//
// This function unchecks all checkboxes and select options.
//
function UnCheckStuff($s_buf)
{
	global $php_errormsg;

	//
	// we search for:
	//  <input type="checkbox" ... checked
	// and remove "checked" (checked="checked" is OK too)
	//
	// Note that the check attribute must appear *after* the
	// type attribute.
	// see <A HREF="fmbadhandler.php#PatternInfo">
	//

	$s_pat = '/(<\s*input[^>]*type="checkbox"[^>]*?[^"\w])checked(="checked"|(?=[^"\w]))?([^>]*?)(\s*\/\s*)?>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$3$4>',$s_buf);

	//
	// we search for:
	//  <option... selected
	// and remove "selected" (selected="selected" is OK too)
	// see <A HREF="fmbadhandler.php#PatternInfo">
	//

	$s_pat = '/(<\s*option[^>]*?[^"\w])selected(="selected"|(?=[^"\w]))?([^>]*)>';
	$s_pat .= '/ims';
	$s_buf = preg_replace($s_pat,'$1$3>',$s_buf);

	return ($s_buf);
}

//
// Add the user agent to the url as a parameter called USER_AGENT.
// This allows dynamic web sites to know what the user's browser is.
//
function AddUserAgent($s_url)
{
	global $aServerVars,$aGetVars;

	//
	// check if the USER_AGENT has been passed as a URL parameter,
	// if so, use it
	//
	$s_agent = "";
	if (isset($aGetVars['USER_AGENT']) && $aGetVars['USER_AGENT'] !== "") {
		$s_agent = $aGetVars['USER_AGENT'];
		//
		// check for URL encoding, and if not, then encode it
		//
		if (!IsURLEncoded($s_agent)) {
			$s_agent = urlencode($s_agent);
		}
	} elseif (isset($aServerVars['HTTP_USER_AGENT'])) {
		$s_agent = urlencode($aServerVars['HTTP_USER_AGENT']);
	}

	if ($s_agent !== "") {
		return (AddURLParams($s_url,"USER_AGENT=$s_agent",false));
	} else {
		return ($s_url);
	}
}

//
// Try to determine if the given string is already URL-encoded
//
function    IsURLEncoded($s_str)
{
	//
	// the only non-alphanumeric characters we'd expect
	// to see are defined as safe or extra in RFC 1738:
	//  safe           = "$" | "-" | "_" | "." | "+"
	//  extra          = "!" | "*" | "'" | "(" | ")" | ","
	// plus the encoding character %
	//
	if (preg_match('/[^a-z0-9$_.+!*\'(),%-]/i',$s_str,$a_matches)) {
		FMDebug("IsURLEncoded: '$s_str' matched '" . $a_matches[0] . "' and is therefore not URL-encoded");
		return (false);
	}
	return (true);
}

//
// Sets previous values in a form.
//
function SetPreviousValues($s_form_buf,$a_values,$a_strip = array())
{
	//
	// Uncheck any checkboxes and select options
	//
	$s_form_buf = UnCheckStuff($s_form_buf);
	foreach ($a_values as $s_name => $m_value) {
		if (is_array($m_value)) {
			//
			// note that if no values are selected for a field,
			// then we will never get here for that field
			//
			$s_form_buf = FixCheckboxes($s_name,$m_value,$s_form_buf);
			$s_form_buf = FixMultiSelect($s_name,$m_value,$s_form_buf);
		} else {
			//
			// Fix the field if it's an input type "text" or "password".
			//
			$s_form_buf = FixInputText($s_name,$m_value,$s_form_buf);
			//
			// Fix the field if it's radio button.
			//
			$s_form_buf = FixButton($s_name,$m_value,$s_form_buf);
			//
			// Fix the field if it's a "textarea".
			//
			$s_form_buf = FixTextArea($s_name,$m_value,$s_form_buf);
			//
			// Fix the field if it's a "select".
			//
			$s_form_buf = FixSelect($s_name,$m_value,$s_form_buf);
		}
	}
	//
	// Now strip particular field values.
	//
	foreach ($a_strip as $s_name) {
		$s_form_buf = RemoveFieldValue($s_name,$s_form_buf);
	}
	return ($s_form_buf);
}

//
// Open a URL, do value substitutions, and send to browser.
// The a_strip array provides a list of fields (usually
// hidden fields) to remove from the form (their values are
// set to empty).
//
function ProcessReturnToForm($s_url,$a_values,$a_strip = array())
{
	global $php_errormsg;

	//
	// read the original form, and modify it to provide values
	// for the fields
	//
	if (!CheckValidURL($s_url)) {
		Error("invalid_url",GetMessage(MSG_RETURN_URL_INVALID,
		                               array("URL" => $s_url)),false,false);
	}

	$s_form_url = AddUserAgent($s_url);
	$s_error    = "";
	$s_form_buf = GetURL($s_form_url,$s_error);
	if ($s_form_buf === false) {
		Error("invalid_url",GetMessage(MSG_OPEN_URL,
		                               array("URL"   => $s_form_url,
		                                     "ERROR" => $s_error . ": " . (isset($php_errormsg) ?
				                                     $php_errormsg : "")
		                               )),false,false);
	}

	//
	// Next, we replace or set actual field values.
	//
	echo SetPreviousValues($s_form_buf,$a_values,$a_strip);
}

//
// To return the URL for returning to a particular multi-page form URL.
//
function GetReturnLink($s_this_script,$i_form_index)
{
	if (!CheckValidURL($s_this_script)) {
		Error("not_valid_url",GetMessage(MSG_RETURN_URL_INVALID,
		                                 array("URL" => $s_this_script)),false,false);
	}

	$a_params   = array();
	$a_params[] = "return=$i_form_index";
	if (isset($aServerVars["QUERY_STRING"])) {
		$a_params[] = $aServerVars["QUERY_STRING"];
	}
	$a_params[] = session_name() . "=" . session_id();
	return (AddURLParams($s_this_script,$a_params));
}

//
// Process a multi-page form template.
//
function ProcessMultiFormTemplate($s_template,$a_values,&$a_lines)
{
	global $SPECIAL_VALUES;

	if (Settings::isEmpty('MULTIFORMDIR') && Settings::isEmpty('MULTIFORMURL')) {
		SendAlert(GetMessage(MSG_MULTIFORM));
		return (false);
	}
	//
	// create the "this_form_url" field
	//

	$i_index                   = GetSession("FormIndex");
	$a_list                    = GetSession("FormList");
	$a_values["this_form_url"] = $a_list[$i_index]["URL"];
	//
	// get the persistent file fields
	//
	$a_values = GetSavedFileNames($a_values);
	//$a_values["prev_form"] = GetReturnLink($SPECIAL_VALUES["this_form"]);
	return (DoProcessTemplate(Settings::get('MULTIFORMDIR'),Settings::get('MULTIFORMURL'),$s_template,$a_lines,
	                          $a_values,"",'SubstituteValueForPage'));
}

//
// Output the multi-form template after filling in the fields.
//
function OutputMultiFormTemplate($s_template,$a_values)
{
	$a_lines = array();
	if (!ProcessMultiFormTemplate($s_template,$a_values,$a_lines)) {
		Error("multi_form_failed",GetMessage(MSG_MULTIFORM_FAILED,
		                                     array("NAME" => $s_template)),false,false);
	} else {
		$n_lines = count($a_lines);
		$s_buf   = "";
		for ($ii = 0 ; $ii < $n_lines ; $ii++) {
			$s_buf .= $a_lines[$ii] . "\n";
			unset($a_lines[$ii]); // free memory (hopefully)
		}
		unset($a_lines); // free memory (hopefully)

		if (IsSetSession("FormKeep"))
			//
			// put in any values that are being forward-remembered
			//
		{
			echo SetPreviousValues($s_buf,GetSession("FormKeep"));
		} else {
			echo $s_buf;
		}
	}
}

//
// Insert a preamble into a MIME message.
//
function MimePreamble(&$a_lines,$a_mesg = array())
{
	$a_preamble = explode("\n",GetMessage(MSG_MIME_PREAMBLE));
	foreach ($a_preamble as $s_line) {
		$a_lines[] = $s_line . Settings::get('HEAD_CRLF');
	}

	$a_lines[]    = Settings::get('HEAD_CRLF'); // blank line
	$b_need_blank = false;
	foreach ($a_mesg as $s_line) {
		$a_lines[] = $s_line . Settings::get('HEAD_CRLF');
		if (!empty($s_line)) {
			$b_need_blank = true;
		}
	}
	if ($b_need_blank) {
		$a_lines[] = Settings::get('HEAD_CRLF');
	} // blank line
}

//
// Create the HTML mail
//
function HTMLMail(&$a_lines,&$a_headers,$s_body,$s_template,$s_missing,$s_filter,
                  $s_boundary,$a_raw_fields,$b_no_plain,$b_process_template)
{
	$s_charset = GetMailOption("CharSet");
	if (!isset($s_charset)) {
		$s_charset = "ISO-8859-1";
	}
	if ($b_no_plain) {
		$b_multi = false;
		//
		// don't provide a plain text version - just the HTML
		//
		$a_headers['Content-Type'] = SafeHeader("text/html; charset=$s_charset");
	} else {
		$b_multi                   = true;
		$a_headers['Content-Type'] = "multipart/alternative; boundary=\"$s_boundary\"";

		$a_pre_lines = explode("\n",GetMessage(MSG_MIME_HTML,
		                                       array("NAME" => $s_template)));

		MimePreamble($a_lines,$a_pre_lines);

		//
		// first part - the text version only
		//
		$a_lines[] = "--$s_boundary" . Settings::get('HEAD_CRLF');
		$a_lines[] = "Content-Type: text/plain; charset=$s_charset" . Settings::get('HEAD_CRLF');
		$a_lines[] = Settings::get('HEAD_CRLF'); // blank line
		//
		// treat the body like one line, even though it isn't
		//
		$a_lines[] = $s_body;
		$a_lines[] = Settings::get('HEAD_CRLF'); // blank line
		//
		// second part - the HTML version
		//
		$a_lines[] = "--$s_boundary" . Settings::get('HEAD_CRLF');
		$a_lines[] = "Content-Type: text/html; charset=$s_charset" . Settings::get('HEAD_CRLF');
		$a_lines[] = Settings::get('HEAD_CRLF'); // blank line
	}

	$a_html_lines = array();
	if (!$b_process_template) {
		if (!ProcessTemplate($s_template,$a_html_lines,$a_raw_fields,NULL,'SubstituteValueDummy')) {
			return (false);
		}
	} elseif (!ProcessTemplate($s_template,$a_html_lines,$a_raw_fields,$s_missing)) {
		return (false);
	}

	if (!empty($s_filter))
		//
		// treat the data like one line, even though it isn't
		//
	{
		$a_lines[] = Filter($s_filter,$a_html_lines);
	} else {
		foreach ($a_html_lines as $s_line) {
			$a_lines[] = $s_line;
		}
	}

	if ($b_multi) {
		//
		// end
		//
		$a_lines[] = "--$s_boundary--" . Settings::get('HEAD_CRLF');
		$a_lines[] = Settings::get('HEAD_CRLF'); // blank line
	}
	return (true);
}

//
// Add the contents of a file in base64 encoding.
//
function AddFile(&$a_lines,$s_file_name,$i_file_size,$b_remove = true)
{
	global $php_errormsg;

	@   $fp = fopen($s_file_name,"rb");
	if ($fp === false) {
		SendAlert(GetMessage(MSG_FILE_OPEN_ERROR,array("NAME"  => $s_file_name,
		                                               "TYPE"  => "attachment",
		                                               "ERROR" => CheckString($php_errormsg)
		)));
		return (false);
	}
	//
	// PHP under IIS has problems with the filesize function when
	// the file is on another drive.  So, we replaced a call
	// to filesize with the $i_file_size parameter (this occurred
	// in version 3.01).
	//
	$s_contents = fread($fp,$i_file_size);
	//
	// treat as a single line, even though it isn't
	//
	$a_lines[] = chunk_split(base64_encode($s_contents));
	fclose($fp);
	if ($b_remove) {
		@unlink($s_file_name);
	}
	return (true);
}

//
// Add the contents of a string in base64 encoding.
//
function AddData(&$a_lines,$s_data)
{
	//
	// treat as a single line, even though it isn't
	//
	$a_lines[] = chunk_split(base64_encode($s_data));
	return (true);
}

//
// Check if a file is a valid uploaded file.
//
function IsUploadedFile($a_file_spec)
{
	//
	// $a_file_spec["moved"] is our own internal flag to say we've
	// saved the file
	//
	if (isset($a_file_spec["moved"]) && $a_file_spec["moved"]) {
		return (true);
	}
	return (is_uploaded_file($a_file_spec["tmp_name"]));
}

//
// Save an uploaded file to the repository directory.
//
function SaveFileInRepository(&$a_file_spec)
{
	global $php_errormsg;

	//
	// if a replacement name has been specified, use that, otherwise
	// use the original name
	//
	if (isset($a_file_spec["new_name"])) {
		$s_file_name = basename($a_file_spec["new_name"]);
	} else {
		$s_file_name = basename($a_file_spec["name"]);
	}
	$s_dest = Settings::get('FILE_REPOSITORY') . "/" . $s_file_name;

	$b_ok    = true;
	$s_error = "";

	if (isset($a_file_spec["saved_as"]) && !empty($a_file_spec["saved_as"])) {
		FMDebug("SaveFileInRepository: saved_as");
		$s_srce = $a_file_spec["saved_as"];
	} else {
		$s_srce = $a_file_spec["tmp_name"];
	}

	FMDebug("SaveFileInRepository: $s_srce");
	if (!Settings::get('FILE_OVERWRITE')) {
		clearstatcache();
		if (@file_exists($s_dest)) {
			$b_ok    = false;
			$s_error = GetMessage(MSG_SAVE_FILE_EXISTS,array("FILE" => $s_dest));
		}
	}
	if (Settings::get('MAX_FILE_UPLOAD_SIZE') != 0 &&
	    $a_file_spec["size"] > Settings::get('MAX_FILE_UPLOAD_SIZE') * 1024
	)
		//
		// this exits
		//
	{
		UserError("upload_size",GetMessage(MSG_FILE_UPLOAD_SIZE,
		                                   array("NAME" => $a_file_spec["name"],
		                                         "SIZE" => $a_file_spec["size"],
		                                         "MAX"  => Settings::get('MAX_FILE_UPLOAD_SIZE')
		                                   )));
	}
	if ($b_ok) {
		if (isset($a_file_spec["saved_as"]) && !empty($a_file_spec["saved_as"])) {
			if (!copy($s_srce,$s_dest) || !@unlink($s_srce)) {
				$b_ok = false;
			}
		} else {
			if (!move_uploaded_file($s_srce,$s_dest)) {
				$b_ok = false;
			}
		}
		if ($b_ok) {
			//
			// Flag to say it's been put in the repository.
			//
			$a_file_spec["in_repository"] = true;
			//
			// Its new location
			//
			$a_file_spec["saved_as"] = $s_dest;
			//
			// Now that the file has been saved, "is_uploaded_file"
			// will return false.  So, we create a flag to say it was
			// valid.
			//
			$a_file_spec["moved"] = true;
		} else {
			$s_error = $php_errormsg;
		}
	}
	if (!$b_ok) {
		SendAlert(GetMessage(MSG_SAVE_FILE,array(
			"FILE" => $s_srce,
			"DEST" => $s_dest,
			"ERR"  => $s_error
		)));
		return (false);
	}
	//
	// ignore chmod fails (other than reporting them)
	//
	if (Settings::get('FILE_MODE') != 0 && !chmod($s_dest,Settings::get('FILE_MODE'))) {
		SendAlert(GetMessage(MSG_CHMOD,array(
			"FILE" => $s_dest,
			"MODE" => Settings::get('FILE_MODE'),
			"ERR"  => $s_error
		)));
	}
	return (true);
}

//
// Save all uploaded files to the repository directory.
//
function SaveAllFilesToRepository()
{
	global $aFileVars;

	if (!Settings::get('FILEUPLOADS') || Settings::get('FILE_REPOSITORY') === "")
		//
		// nothing to do
		//
	{
		return (true);
	}

	foreach ($aFileVars as $m_file_key => $a_upload) {
		//
		// One customer reported:
		//  Possible file upload attack detected: name='' temp name='none'
		// on PHP 4.1.2 on RAQ4.
		// So, we now also test for "name".
		//
		if (!isset($a_upload["tmp_name"]) || empty($a_upload["tmp_name"]) ||
		    !isset($a_upload["name"]) || empty($a_upload["name"])
		) {
			continue;
		}
		if (isset($a_upload["in_repository"]) && $a_upload["in_repository"])
			//
			// already saved
			//
		{
			continue;
		}
		if (!IsUploadedFile($a_upload)) {
			SendAlert(GetMessage(MSG_FILE_UPLOAD_ATTACK,
			                     array("NAME" => $a_upload["name"],
			                           "TEMP" => $a_upload["tmp_name"],
			                           "FLD"  => $m_file_key
			                     )));
			continue;
		}
		if (!SaveFileInRepository($aFileVars[$m_file_key])) {
			return (false);
		}
		//
		// Now the file has been saved in the repository, make
		// the field persistent through all further processing
		// (e.g. all movements in a multi-page form)
		//
		if (IsSetSession("FormSavedFiles")) {
			$a_saved_files = GetSession("FormSavedFiles");
		} else {
			$a_saved_files = array();
		}
		$a_saved_files["repository_" . $m_file_key] = $aFileVars[$m_file_key];
		SetSession("FormSavedFiles",$a_saved_files);
	}
	return (true);
}

//
// Delete an uploaded file from the repository directory.
// For security reasons, only the field name can be used.  This
// uniquely identifies an uploaded file by this form process.
//
function DeleteFileFromRepository($s_fld)
{
	global $aFileVars;

	if (!Settings::get('FILEUPLOADS') || Settings::get('FILE_REPOSITORY') === "")
		//
		// nothing to do
		//
	{
		return (false);
	}

	if (($a_upload = GetFileInfo($s_fld)) === false) {
		return (false);
	}

	if (isset($a_upload["in_repository"]) && $a_upload["in_repository"]) {
		if (isset($a_upload["saved_as"]) && !empty($a_upload["saved_as"])) {
			@unlink($a_upload["saved_as"]);
		}
	}
	DeleteFileInfo($s_fld);
	return (true);
}

//
// Save an uploaded file for later processing.
//
function SaveUploadedFile(&$a_file_spec,$s_prefix)
{
	global $php_errormsg;

	FMDebug("SaveUploadedFile");
	$s_dest = GetScratchPadFile($s_prefix);
	if (!move_uploaded_file($a_file_spec["tmp_name"],$s_dest)) {
		SendAlert(GetMessage(MSG_SAVE_FILE,array(
			"FILE" => $a_file_spec["tmp_name"],
			"DEST" => $s_dest,
			"ERR"  => $php_errormsg
		)));
		return (false);
	}
	$a_file_spec["saved_as"] = $s_dest;
	$a_file_spec["moved"]    = true;
	return (true);
}

//
// Remove old files from the scratchpad directory.
//
function CleanScratchPad($s_prefix = "")
{
	global $lNow;
	global $php_errormsg;

	if (Settings::isEmpty('SCRATCH_PAD'))
		//
		// no scratchpad to cleanup!
		//
	{
		return;
	}
	if (Settings::get('CLEANUP_TIME') <= 0)
		//
		// cleanup disabled
		//
	{
		return;
	}
	//
	// compute chance of cleanup
	//
	if (Settings::get('CLEANUP_CHANCE') < 100) {
		$i_rand = mt_rand(1,100);
		if ($i_rand > Settings::get('CLEANUP_CHANCE')) {
			return;
		}
	}
	if (($f_dir = @opendir(Settings::get('SCRATCH_PAD'))) === false) {
		Error("open_scratch_pad",GetMessage(MSG_OPEN_SCRATCH_PAD,array(
			"DIR" => Settings::get('SCRATCH_PAD'),
			"ERR" => $php_errormsg
		)),false,false);
		return;
	}
	$i_len = strlen($s_prefix);
	while (($s_file = readdir($f_dir)) !== false) {
		$s_path = Settings::get('SCRATCH_PAD') . "/" . $s_file;
		if (is_file($s_path) && ($i_len == 0 || substr($s_file,0,$i_len) == $s_prefix)) {
			if (($a_stat = @stat($s_path)) !== false) {
				if (isset($a_stat['mtime'])) {
					$l_time = $a_stat['mtime'];
				} else {
					$l_time = $a_stat[9];
				}
				if (($lNow - $l_time) / 60 >= Settings::get('CLEANUP_TIME')) {
					@unlink($s_path);
				}
			}
		}
	}
	closedir($f_dir);
}

//
// Save all uploaded files for later processing.
//
function SaveAllUploadedFiles(&$a_file_vars)
{
	global $php_errormsg;

	$s_prefix = "UPLD";
	if (Settings::isEmpty('SCRATCH_PAD')) {
		Error("need_scratch_pad",GetMessage(MSG_NEED_SCRATCH_PAD),false,false);
		return (false);
	}

	//
	// remove old uploaded files that have not been moved out.
	//
	CleanScratchPad($s_prefix);

	foreach (array_keys($a_file_vars) as $m_file_key) {
		$a_upload = &$a_file_vars[$m_file_key];
		//
		// One customer reported:
		//  Possible file upload attack detected: name='' temp name='none'
		// on PHP 4.1.2 on RAQ4.
		// So, we now also test for "name".
		//
		if (!isset($a_upload["tmp_name"]) || empty($a_upload["tmp_name"]) ||
		    !isset($a_upload["name"]) || empty($a_upload["name"])
		) {
			continue;
		}
		//
		// ensure we don't move the file more than once
		//
		if (!isset($a_upload["saved_as"]) || empty($a_upload["saved_as"])) {
			if (!IsUploadedFile($a_upload)) {
				SendAlert(GetMessage(MSG_FILE_UPLOAD_ATTACK,
				                     array("NAME" => $a_upload["name"],
				                           "TEMP" => $a_upload["tmp_name"],
				                           "FLD"  => $m_file_key
				                     )));
			} elseif (!SaveUploadedFile($a_upload,$s_prefix)) {
				return (false);
			}
		}
	}
	return (true);
}

//
// Attach a file to the body of a MIME formatted email.  $a_lines is the
// current body, and is modified to include the file.
// $a_file_spec must have the following values (just like an uploaded
// file specification):
//      name        the name of the file
//      type        the mime type
//      tmp_name    the name of the temporary file
//      size        the size of the temporary file
//
// Alternatively, you supply the following instead of tmp_name and size:
//      data        the data to attach
//
function AttachFile(&$a_lines,$s_att_boundary,$a_file_spec,$s_charset,$b_remove = true)
{
	$a_lines[] = "--$s_att_boundary" . Settings::get('HEAD_CRLF');
	//
	// if a replacement name has been specified, use that, otherwise
	// use the original name
	//
	if (isset($a_file_spec["new_name"])) {
		$s_file_name = $a_file_spec["new_name"];
	} else {
		$s_file_name = $a_file_spec["name"];
	}
	$s_file_name = str_replace('"','',$s_file_name);
	$s_mime_type = $a_file_spec["type"];
	//
	// The following says that the data is encoded in
	// base64 and is an attachment and that once decoded the
	// character set of the decoded data is $s_charset.
	// (See RFC 1521 Section 5.)
	//
	$a_lines[] = "Content-Type: $s_mime_type; name=\"$s_file_name\"; charset=$s_charset" . Settings::get('HEAD_CRLF');
	$a_lines[] = "Content-Transfer-Encoding: base64" . Settings::get('HEAD_CRLF');
	$a_lines[] = "Content-Disposition: attachment; filename=\"$s_file_name\"" . Settings::get('HEAD_CRLF');
	$a_lines[] = Settings::get('HEAD_CRLF'); // blank line
	if (isset($a_file_spec["tmp_name"]) && isset($a_file_spec["size"])) {
		$s_srce = $a_file_spec["tmp_name"];
		//
		// check if the file has been saved elsewhere
		//
		if (isset($a_file_spec["saved_as"]) && !empty($a_file_spec["saved_as"])) {
			$s_srce = $a_file_spec["saved_as"];
		}
		FMDebug("AttachFile: $s_srce");
		return (AddFile($a_lines,$s_srce,$a_file_spec["size"],$b_remove));
	}
	if (!isset($a_file_spec["data"])) {
		SendAlert(GetMessage(MSG_ATTACH_DATA));
		return (false);
	}
	return (AddData($a_lines,$a_file_spec["data"]));
}

//
// Reformat the email to be in MIME format.
// Process file attachments and and fill out any
// specified HTML template.
//
function MakeMimeMail(&$s_body,&$a_headers,$a_raw_fields,$s_template = "",
                      $s_missing = NULL,$b_no_plain = false,
                      $s_filter = "",$a_file_vars = array(),
                      $a_attach_spec = array(),$b_process_template = true)
{
	global $FM_VERS;
	global $SPECIAL_VALUES;

	$s_charset = GetMailOption("CharSet");
	if (!isset($s_charset)) {
		$s_charset = "ISO-8859-1";
	}
	$b_att        = $b_html = false;
	$b_got_filter = (isset($s_filter) && !empty($s_filter));
	if (isset($s_template) && !empty($s_template)) {
		$b_html = true;
	}
	if (count($a_file_vars) > 0) {
		if (!Settings::get('FILEUPLOADS')) {
			SendAlert(GetMessage(MSG_FILE_UPLOAD));
			//
			// if storing files in the server repository, don't attach
			// unless the mail_options insist
			//
		} elseif (Settings::get('FILE_REPOSITORY') === "" || IsMailOptionSet("AlwaysEmailFiles")) {
			foreach ($a_file_vars as $a_upload) {
				//
				// One customer reported:
				//  Possible file upload attack detected: name='' temp name='none'
				// on PHP 4.1.2 on RAQ4.
				// So, we now also test for "name".
				//
				if (isset($a_upload["tmp_name"]) && !empty($a_upload["tmp_name"]) &&
				    isset($a_upload["name"]) && !empty($a_upload["name"])
				) {
					$b_att = true;
					break;
				}
			}
		}
	}
	//
	// check for an internally-generated attachment
	//
	if (isset($a_attach_spec["Data"])) {
		$b_att = true;
	}

	$s_uniq                    = md5($s_body);
	$s_body_boundary           = "BODY$s_uniq";
	$s_att_boundary            = "PART$s_uniq";
	$a_headers['MIME-Version'] = "1.0 (produced by FormMail $FM_VERS from www.tectite.com)";

	//
	// if the filter strips formatting, then we'll only have plain text
	// to send, even after the template has been used
	//
	if ($b_got_filter && IsFilterAttribSet($s_filter,"Strips"))
		//
		// no HTML if the filter strips the formatting
		//
	{
		$b_html = false;
	}
	$a_new = array();
	if ($b_att) {
		$a_headers['Content-Type'] = "multipart/mixed; boundary=\"$s_att_boundary\"";

		MimePreamble($a_new);
		//
		// add the body of the email
		//
		$a_new[] = "--$s_att_boundary" . Settings::get('HEAD_CRLF');
		if ($b_html) {
			$a_lines = $a_local_headers = array();
			if (!HTMLMail($a_lines,$a_local_headers,$s_body,$s_template,
			              $s_missing,($b_got_filter) ? $s_filter : "",
			              $s_body_boundary,$a_raw_fields,$b_no_plain,
			              $b_process_template)
			) {
				return (false);
			}
			$a_new   = array_merge($a_new,ExpandMailHeadersArray($a_local_headers));
			$a_new[] = Settings::get('HEAD_CRLF'); // blank line after header
			$a_new   = array_merge($a_new,$a_lines);
		} else {
			$a_new[] = "Content-Type: text/plain; charset=$s_charset" . Settings::get('HEAD_CRLF');
			$a_new[] = Settings::get('HEAD_CRLF'); // blank line
			//
			// treat the body like one line, even though it isn't
			//
			$a_new[] = $s_body;
		}
		//
		// now add the attachments or save to the $FILE_REPOSITORY
		//
		if (Settings::get('FILEUPLOADS') &&
		    (Settings::get('FILE_REPOSITORY') === "" || IsMailOptionSet("AlwaysEmailFiles"))
		) {
			foreach ($a_file_vars as $m_file_key => $a_upload) {
				//
				// One customer reported:
				//  Possible file upload attack detected: name='' temp name='none'
				// on PHP 4.1.2 on RAQ4.
				// So, we now also test for "name".
				//
				if (!isset($a_upload["tmp_name"]) || empty($a_upload["tmp_name"]) ||
				    !isset($a_upload["name"]) || empty($a_upload["name"])
				) {
					continue;
				}
				if (!IsUploadedFile($a_upload)) {
					SendAlert(GetMessage(MSG_FILE_UPLOAD_ATTACK,
					                     array("NAME" => $a_upload["name"],
					                           "TEMP" => $a_upload["tmp_name"],
					                           "FLD"  => $m_file_key
					                     )));
					continue;
				}
				if (Settings::get('MAX_FILE_UPLOAD_SIZE') != 0 &&
				    $a_upload["size"] > Settings::get('MAX_FILE_UPLOAD_SIZE') * 1024
				) {
					UserError("upload_size",GetMessage(MSG_FILE_UPLOAD_SIZE,
					                                   array("NAME" => $a_upload["name"],
					                                         "SIZE" => $a_upload["size"],
					                                         "MAX"  => Settings::get('MAX_FILE_UPLOAD_SIZE')
					                                   )));
				}
				if (!AttachFile($a_new,$s_att_boundary,$a_upload,$s_charset,
				                (Settings::get('FILE_REPOSITORY') === "") ? true : false)
				) {
					return (false);
				}
			}
		}
		if (isset($a_attach_spec["Data"])) {
			//
			// build a specification similar to a file upload
			//
			$a_file_spec["name"] = isset($a_attach_spec["Name"]) ?
				$a_attach_spec["Name"] :
				"attachment.dat";
			$a_file_spec["type"] = isset($a_attach_spec["MIME"]) ?
				$a_attach_spec["MIME"] :
				"text/plain";
			$a_file_spec["data"] = $a_attach_spec["Data"];
			if (!AttachFile($a_new,$s_att_boundary,$a_file_spec,
			                isset($a_attach_spec["CharSet"]) ?
				                $a_attach_spec["CharSet"] :
				                $s_charset)
			) {
				return (false);
			}
		}
		$a_new[] = "--$s_att_boundary--" . Settings::get('HEAD_CRLF'); // the end
		$a_new[] = Settings::get('HEAD_CRLF'); // blank line
	} elseif ($b_html) {
		if (!HTMLMail($a_new,$a_headers,$s_body,$s_template,
		              $s_missing,($b_got_filter) ? $s_filter : "",
		              $s_body_boundary,$a_raw_fields,$b_no_plain,
		              $b_process_template)
		) {
			return (false);
		}
	} else {
		$a_headers['Content-Type'] = SafeHeader("text/plain; charset=$s_charset");
		//
		// treat the body like one line, even though it isn't
		//
		$a_new[] = $s_body;
	}

	$s_body = JoinLines(Settings::get('BODY_LF'),$a_new);
	return (true);
}

//
// to make a From line for the email
//
function MakeFromLine($s_email,$s_name)
{
	$s_style = GetMailOption("FromLineStyle");
	$s_line  = "";
	if (!isset($s_style)) {
		$s_style = "";
	}
	//
	// the following From line styles are in accordance with RFC 822
	//
	switch ($s_style) {
		default:
		case "":
		case "default":
		case "AddrSpecName":
			//
			// this is the original From line style that FormMail produced
			// e.g.
			//      jack@nowhere.com (Jack Smith)
			// this is an addr-spec with a trailing comment with the name
			//
			if (!empty($s_email)) {
				$s_line .= SafeHeaderEmail($s_email) . " ";
			}
			if (!empty($s_name)) {
				$s_line .= "(" . SafeHeaderComment(EncodeHeaderText($s_name)) . ")";
			}
			break;
		case "NameAddrSpec":
			//
			// email address as an addr-spec preceded by a comment with the name
			// e.g.
			//      (Jack Smith) jack@nowhere.com
			//
			if (!empty($s_name)) {
				$s_line .= "(" . SafeHeaderComment(EncodeHeaderText($s_name)) . ") ";
			}
			if (!empty($s_email)) {
				$s_line .= SafeHeaderEmail($s_email);
			}
			break;
		case "RouteAddr":
			//
			// just the email address as a route-addr
			// e.g.
			//      <jack@nowhere.com>
			//
			if (!empty($s_email)) {
				$s_line .= "<" . SafeHeaderEmail($s_email) . ">";
			}
			break;
		case "QuotedNameRouteAddr":
			//
			// email address as a route-addr preceded
			// by the name of the user as a quoted string
			// e.g.
			//      "Jack Smith" <jack@nowhere.com>
			//
			if (!empty($s_name)) {
				$s_line .= '"' . SafeHeaderQString(EncodeHeaderText($s_name)) . '" ';
			}
			if (!empty($s_email)) {
				$s_line .= "<" . SafeHeaderEmail($s_email) . ">";
			}
			break;
		case "NameRouteAddr":
			//
			// email address as a route-addr preceded
			// by the name of the user as words
			// e.g.
			//      Jack Smith <jack@nowhere.com>
			//
			if (!empty($s_name)) {
				$s_line .= SafeHeaderWords(EncodeHeaderText($s_name)) . ' ';
			}
			if (!empty($s_email)) {
				$s_line .= "<" . SafeHeaderEmail($s_email) . ">";
			}
			break;
	}
	return ($s_line);
}

//
// Return two sets of plain text output: the filtered fields and the
// non-filtered fields.
//
function GetFilteredOutput($a_fld_order,$a_clean_fields,$s_filter,$a_filter_list)
{
	//
	// find the non-filtered fields and make unfiltered text from them
	//
	$a_unfiltered_list = array();
	$n_flds            = count($a_fld_order);
	for ($ii = 0 ; $ii < $n_flds ; $ii++) {
		if (!in_array($a_fld_order[$ii],$a_filter_list)) {
			$a_unfiltered_list[] = $a_fld_order[$ii];
		}
	}
	$s_unfiltered_results = MakeFieldOutput($a_unfiltered_list,$a_clean_fields);
	//
	// filter the specified fields only
	//
	$s_filtered_results = MakeFieldOutput($a_filter_list,$a_clean_fields);
	$s_filtered_results = Filter($s_filter,$s_filtered_results);
	return (array($s_unfiltered_results,$s_filtered_results));
}

//
// Make a plain text email body
//
function MakePlainEmail($a_fld_order,$a_clean_fields,
                        $s_to,$s_cc,$s_bcc,$a_raw_fields,$s_filter,$a_filter_list)
{
	global $SPECIAL_VALUES;

	$s_unfiltered_results = $s_filtered_results = "";
	$b_got_filter         = (isset($s_filter) && !empty($s_filter));
	if ($b_got_filter) {
		if (isset($a_filter_list) && count($a_filter_list) > 0) {
			$b_limited_filter = true;
		} else {
			$b_limited_filter = false;
		}
	}
	$b_used_template = false;
	if (IsMailOptionSet("PlainTemplate")) {
		$s_template = GetMailOption("PlainTemplate");
		if (ProcessTemplate($s_template,$a_lines,$a_raw_fields,GetMailOption('TemplateMissing'),
		                    'SubstituteValuePlain')
		) {
			$b_used_template      = true;
			$s_unfiltered_results = implode(Settings::get('BODY_LF'),$a_lines);
			if ($b_got_filter) {
				//
				// with a limited filter, the template goes unfiltered
				// and the named fields get filtered
				//
				if ($b_limited_filter) {
					list ($s_discard,$s_filtered_results) = GetFilteredOutput($a_fld_order,
					                                                          $a_clean_fields,
					                                                          $s_filter,$a_filter_list);
				} else {
					$s_filtered_results   = Filter($s_filter,$s_unfiltered_results);
					$s_unfiltered_results = "";
				}
			}
		}
	}
	if (!$b_used_template) {
		$res_hdr = "";

		if (IsMailOptionSet("DupHeader")) {
			//
			// write some standard mail headers
			//
			$res_hdr = "To: $s_to" . Settings::get('BODY_LF');
			if (!empty($s_cc)) {
				$res_hdr .= "Cc: $s_cc" . Settings::get('BODY_LF');
			}
			if (!empty($SPECIAL_VALUES["email"])) {
				$res_hdr .= "From: " . MakeFromLine($SPECIAL_VALUES["email"],
				                                    $SPECIAL_VALUES["realname"]) . Settings::get('BODY_LF');
			}
			$res_hdr .= Settings::get('BODY_LF');
			if (IsMailOptionSet("StartLine")) {
				$res_hdr .= "--START--" . Settings::get('BODY_LF');
			} // signals the beginning of the text to filter
		}

		//
		// put the realname and the email address at the top of the results
		// (if not excluded)
		//
		if (!IsMailExcluded("realname")) {
			array_unshift($a_fld_order,"realname");
			$a_clean_fields["realname"] = $SPECIAL_VALUES["realname"];
		}
		if (!IsMailExcluded("email")) {
			array_unshift($a_fld_order,"email");
			$a_clean_fields["email"] = $SPECIAL_VALUES["email"];
		}
		if ($b_got_filter) {
			if ($b_limited_filter) {
				list($s_unfiltered_results,$s_filtered_results) =
					GetFilteredOutput($a_fld_order,$a_clean_fields,
					                  $s_filter,$a_filter_list);
			} else {
				//
				// make text output and filter it (filter all fields)
				//
				$s_filtered_results = MakeFieldOutput($a_fld_order,$a_clean_fields);
				$s_filtered_results = Filter($s_filter,$s_filtered_results);
			}
		} else {
			//SendAlert("There are ".count($a_fld_order)." fields in the order array");
			//SendAlert("Here is the clean fields array:\r\n".var_export($a_clean_fields,true));
			$s_unfiltered_results = MakeFieldOutput($a_fld_order,$a_clean_fields);
		}
		$s_unfiltered_results = $res_hdr . $s_unfiltered_results;
	}
	$s_results = $s_unfiltered_results;
	if ($b_got_filter && !empty($s_filtered_results)) {
		if (!empty($s_results)) {
			$s_results .= Settings::get('BODY_LF');
		}
		$s_results .= $s_filtered_results;
	}
	//
	// append the environment variables report
	//
	if (isset($SPECIAL_VALUES["env_report"]) && !empty($SPECIAL_VALUES["env_report"])) {
		$s_results .= Settings::get('BODY_LF') . "==================================" . Settings::get('BODY_LF');
		$s_results .= Settings::get('BODY_LF') . GetEnvVars(TrimArray(explode(",",$SPECIAL_VALUES["env_report"])),
		                                                    Settings::get('BODY_LF'));
	}
	return (array($s_results,$s_unfiltered_results,$s_filtered_results));
}

//
// Return the list of fields to be filtered, FALSE if no list provided.
//
function GetFilterList($b_file_fields)
{
	global $SPECIAL_VALUES;

	//
	// no filter means no list of fields
	//
	if (!empty($SPECIAL_VALUES["filter"])) {
		if ($b_file_fields) {
			if (isset($SPECIAL_VALUES["filter_files"]) && !empty($SPECIAL_VALUES["filter_files"])) {
				return (TrimArray(explode(",",$SPECIAL_VALUES["filter_files"])));
			}
		} else {
			if (isset($SPECIAL_VALUES["filter_fields"]) && !empty($SPECIAL_VALUES["filter_fields"])) {
				return (TrimArray(explode(",",$SPECIAL_VALUES["filter_fields"])));
			}
		}
	}
	return (false);
}

/* 
 * Function:    GetFilterSpec
 * Parameters:  $s_filter       returns the filter name
 *              $m_filter_list  returns the list of fields to filter (an array)
 *                              or is set to false if there is no filter list
 *              $b_file_fields  if true, return file fields, otherwise return non-file fields
 * Returns:     bool            true if filtering a list of fields of the specified type
 * Description:     
 *  Checks whether the form has specified to filter a list of 
 *  fields of the specified type (file fields or non-file fields).
 */
function    GetFilterSpec(&$s_filter,&$m_filter_list,$b_file_fields = false)
{
	global $SPECIAL_VALUES;

	if (isset($SPECIAL_VALUES["filter"]) && !empty($SPECIAL_VALUES["filter"])) {
		$s_filter      = $SPECIAL_VALUES["filter"];
		$m_filter_list = GetFilterList($b_file_fields);
		return (true);
	}
	return (false);
}

//
// send the given results to the given email addresses
//
function SendResults($a_fld_order,$a_clean_fields,$s_to,$s_cc,$s_bcc,$a_raw_fields)
{
	global $SPECIAL_VALUES,$aFileVars;

	//
	// check for a filter and how to use it
	//
	$b_filter_attach = false;
	$a_attach_spec   = array();
	$s_filter        = "";
	$a_filter_list   = array();
	if ($b_got_filter = GetFilterSpec($s_filter,$a_filter_list)) {
		if ($a_filter_list === false) {
			//
			// not a limited filter, so filter all fields
			//
			$b_limited_filter = false;
			$a_filter_list    = array();
		} else {
			$b_limited_filter = true;
		}
		FMDebug("SendResults: got filter '$s_filter', limited=$b_limited_filter");
		$s_filter_attach_name = GetFilterOption("Attach");
		if (isset($s_filter_attach_name)) {
			if (!is_string($s_filter_attach_name) || empty($s_filter_attach_name)) {
				SendAlert(GetMessage(MSG_ATTACH_NAME));
			} else {
				$b_filter_attach = true;
				$a_attach_spec   = array("Name" => $s_filter_attach_name);
				if (($s_mime = GetFilterAttrib($s_filter,"MIME")) !== false) {
					$a_attach_spec["MIME"] = $s_mime;
				}
				//
				// Regarding the character set...
				// A filter will not generally change the character set
				// of the message, however, if it does, then we
				// provide that information to the MIME encoder.
				// Remember: this character set specification refers
				// to the data *after* the effect of the filter
				// has been reversed (e.g. an encrypted message
				// in UTF-8 is in UTF-8 when it is decrypted).
				//
				if (($s_cset = GetFilterAttrib($s_filter,"CharSet")) !== false) {
					$a_attach_spec["CharSet"] = $s_cset;
				}
			}
		}
	}

	//
	// check the need for MIME formatted mail
	//
	$b_mime_mail = (IsMailOptionSet("HTMLTemplate") || count($aFileVars) > 0 ||
	                $b_filter_attach);

	//
	// create the email header lines - CC, BCC, From, and Reply-To
	//
	$a_headers = array();
	if (!empty($s_cc)) {
		$a_headers['Cc'] = SafeHeader($s_cc);
	}
	if (!empty($SPECIAL_VALUES["replyto"])) {
		//
		// expand replyto list
		//
		CheckEmailAddress($SPECIAL_VALUES["replyto"],$s_list,$s_invalid,false);
		if (!empty($s_list)) {
			$a_headers['Reply-To'] = SafeHeader($s_list);
		}
	}
	if (!empty($s_bcc)) {
		$a_headers['Bcc'] = SafeHeader($s_bcc);
	}
	//
	// create the From address
	//
	// Some servers won't let you set the email address to the
	// submitter of the form.  Therefore, use FromAddr if it's been
	// specified to set the sender and the "From" address.
	//
	$s_sender = GetMailOption("FromAddr");
	if (!isset($s_sender)) {
		$s_sender = "";
		if (!empty($SPECIAL_VALUES["email"])) {
			$a_headers['From'] = MakeFromLine($SPECIAL_VALUES["email"],
			                                  $SPECIAL_VALUES["realname"]);
		}
	} elseif ($s_sender !== "") {
		$s_sender = $a_headers['From'] = SafeHeader(UnMangle($s_sender));
	}

	/* 
         * Override sender if $FIXED_SENDER is set.
         */
	if (Settings::get('FIXED_SENDER') !== "") {
		$s_sender = Settings::get('FIXED_SENDER');
	}

	if ($s_sender === "") {
		if (Settings::get('SET_SENDER_FROM_EMAIL')) {
			$s_sender = $SPECIAL_VALUES["email"];
		}
	}

	//
	// special case: if there is only one non-special string value, then
	// format it as an email (unless an option says not to)
	//
	$a_keys = array_keys($a_raw_fields);
	if (count($a_keys) == 1 && is_string($a_raw_fields[$a_keys[0]]) &&
	    !IsMailOptionSet("AlwaysList") && !IsMailOptionSet("DupHeader")
	) {
		if (IsMailExcluded($a_keys[0])) {
			SendAlert("Exclusion of single field '" . $a_keys[0] . "' ignored");
		}
		$s_value = $a_raw_fields[$a_keys[0]];
		//
		// replace carriage return/linefeeds with <br>
		//
		$s_value = str_replace("\r\n",'<br />',$s_value);
		//
		// replace lone linefeeds with <br>
		//
		$s_value = str_replace("\n",'<br />',$s_value);
		//
		// remove lone carriage returns
		//
		$s_value = str_replace("\r","",$s_value);
		//
		// replace all control chars with <br />
		//
		$s_value = preg_replace('/[[:cntrl:]]+/','<br />',$s_value);
		//
		// strip HTML (note that all the <br> above will now be
		// replaced with BODY_LF)
		//
		$s_value = StripHTML($s_value,Settings::get('BODY_LF'));

		if ($b_mime_mail) {
			if ($b_got_filter) {
				//
				// filter the whole value (ignore filter_fields for this
				// special case) if a filter has been specified
				//
				$s_results = Filter($s_filter,$s_value);
				if ($b_filter_attach) {
					$a_attach_spec["Data"] = $s_results;
					//
					// KeepInLine keeps the filtered version inline as well
					// as an attachment
					//
					if (!IsFilterOptionSet("KeepInLine")) {
						$s_results = "";
					}
					$s_filter = ""; // no more filtering
				}
			} else {
				$s_results = $s_value;
			}

			//
			// send this single value off to get formatted in a MIME
			// email
			//
			if (!MakeMimeMail($s_results,$a_headers,$a_raw_fields,
			                  GetMailOption('HTMLTemplate'),
			                  GetMailOption('TemplateMissing'),
			                  IsMailOptionSet("NoPlain"),
			                  $s_filter,$aFileVars,$a_attach_spec)
			) {
				return (false);
			}
		} elseif ($b_got_filter)
			//
			// filter the whole value (ignore filter_fields for this special case)
			// if a filter has been specified
			//
		{
			$s_results = Filter($s_filter,$s_value);
		} else {
			$s_results = $s_value;
			if (IsMailOptionSet("CharSet"))
				//
				// sending plain text email, and the CharSet has been
				// specified; include a header
				//
			{
				$a_headers['Content-Type'] = "text/plain; charset=" . SafeHeader(GetMailOption("CharSet"));
			}
		}
	} else {
		if ($b_mime_mail) {
			//
			// get the plain text version of the email then send it
			// to get MIME formatted
			//
			list($s_results,$s_unfiltered_results,$s_filtered_results) =
				MakePlainEmail($a_fld_order,$a_clean_fields,
				               $s_to,$s_cc,$s_bcc,$a_raw_fields,$s_filter,
				               $a_filter_list);
			if ($b_filter_attach) {
				//
				// attached the filtered results
				//
				$a_attach_spec["Data"] = $s_filtered_results;
				//
				// KeepInLine keeps the filtered version inline as well
				// as an attachment
				//
				if (!IsFilterOptionSet("KeepInLine"))
					//
					// put the unfiltered results in the body of the message
					//
				{
					$s_results = $s_unfiltered_results;
				}
				$s_filter = ""; // no more filtering
			}
			if (!MakeMimeMail($s_results,$a_headers,$a_raw_fields,
			                  GetMailOption('HTMLTemplate'),
			                  GetMailOption('TemplateMissing'),
			                  IsMailOptionSet("NoPlain"),
			                  $s_filter,$aFileVars,$a_attach_spec)
			) {
				return (false);
			}
		} else {
			list($s_results,$s_unfiltered_results,$s_filtered_results) =
				MakePlainEmail($a_fld_order,$a_clean_fields,
				               $s_to,$s_cc,$s_bcc,$a_raw_fields,$s_filter,
				               $a_filter_list);
			if (!$b_got_filter && IsMailOptionSet("CharSet"))
				//
				// sending plain text email, and the CharSet has been
				// specified; include a header
				//
			{
				$a_headers['Content-Type'] = "text/plain; charset=" . SafeHeader(GetMailOption("CharSet"));
			}
		}
	}

	//
	// now save uploaded files to the repository
	//
	if (Settings::get('FILEUPLOADS') && Settings::get('FILE_REPOSITORY') !== "") {
		if (!SaveAllFilesToRepository()) {
			return (false);
		}
	}

	//
	// send the mail - assumes the email addresses have already been checked
	//
	return (SendCheckedMail($s_to,$SPECIAL_VALUES["subject"],$s_results,
	                        $s_sender,$a_headers));
}

//
// append an entry to a log file
//
function WriteLog($log_file)
{
	global $SPECIAL_VALUES,$php_errormsg;

	@   $log_fp = fopen($log_file,"a");
	if ($log_fp === false) {
		SendAlert(GetMessage(MSG_FILE_OPEN_ERROR,array("NAME"  => $log_file,
		                                               "TYPE"  => "log",
		                                               "ERROR" => CheckString($php_errormsg)
		)));
		return;
	}
	$date  = gmdate("H:i:s d-M-y T");
	$entry = $date . ":" . $SPECIAL_VALUES["email"] . "," .
	         $SPECIAL_VALUES["realname"] . "," . $SPECIAL_VALUES["subject"] . "\n";
	fwrite($log_fp,$entry);
	fclose($log_fp);
}

//
// write the data to a comma-separated-values file
//
function WriteCSVFile($s_csv_file,$a_vars)
{
	global $SPECIAL_VALUES;

	//
	// create an array of column values in the order specified
	// in $SPECIAL_VALUES["csvcolumns"]
	//
	$a_column_list = $SPECIAL_VALUES["csvcolumns"];
	if (!isset($a_column_list) || empty($a_column_list) || !is_string($a_column_list)) {
		SendAlert(GetMessage(MSG_CSVCOLUMNS,array("VALUE" => $a_column_list)));
		return;
	}
	if (!isset($s_csv_file) || empty($s_csv_file) || !is_string($s_csv_file)) {
		SendAlert(GetMessage(MSG_CSVFILE,array("VALUE" => $s_csv_file)));
		return;
	}

	@   $fp = fopen($s_csv_file,"a" . Settings::get('CSVOPEN'));
	if ($fp === false) {
		SendAlert(GetMessage(MSG_FILE_OPEN_ERROR,array("NAME"  => $s_csv_file,
		                                               "TYPE"  => "CSV",
		                                               "ERROR" => CheckString($php_errormsg)
		)));
		return;
	}

	//
	// convert the column list to an array, trim the names too
	//
	$a_column_list = TrimArray(explode(",",$a_column_list));
	$n_columns     = count($a_column_list);

	//
	// if the file is currently empty, put the column names in the first line
	//
	$b_heading = false;
	if (filesize($s_csv_file) == 0) {
		$b_heading = true;
	}

	$csv_format = new CSVFormat();

	//
	// now configure the CSVFormat object
	// according to FormMail's configuration settings
	//
	$csv_format->SetQuote(Settings::get('CSVQUOTE'));
	$csv_format->SetEscPolicy("conv");
	$csv_format->SetSep(Settings::get('CSVSEP'));
	$csv_format->SetIntSep(Settings::get('CSVINTSEP'));
	if (Settings::get('LIMITED_IMPORT')) {
		$csv_format->SetCleanFunc(create_function('$m_value',
		                                          'return CleanValue($m_value);'));
	}

	$s_csv = $csv_format->MakeCSVRecord($a_column_list,$a_vars);

	if ($b_heading) {
		fwrite($fp,$csv_format->MakeHeading($a_column_list) . Settings::get('CSVLINE'));
	}

	fwrite($fp,$s_csv . Settings::get('CSVLINE'));
	fclose($fp);
	//  CreatePage($debug);
	//  exit;
}

function CheckConfig()
{

	$a_mesgs = array();
	if (in_array("TARGET_EMAIL",Settings::get('CONFIG_CHECK'))) {
		//
		// $TARGET_EMAIL values should begin with ^ and end with $
		//
		$a_target_email = Settings::get('TARGET_EMAIL');
		for ($ii = 0 ; $ii < count($a_target_email) ; $ii++) {
			$s_pattern = $a_target_email[$ii];
			if (substr($s_pattern,0,1) != '^') {
				$a_mesgs[] = GetMessage(MSG_TARG_EMAIL_PAT_START,
				                        array("PAT" => $s_pattern));
			}
			if (substr($s_pattern,-1) != '$') {
				$a_mesgs[] = GetMessage(MSG_TARG_EMAIL_PAT_END,
				                        array("PAT" => $s_pattern));
			}
		}
	}
	if (count($a_mesgs) > 0) {
		SendAlert(GetMessage(MSG_CONFIG_WARN,
		                     array("MESGS" => implode("\n",$a_mesgs))),false,true);
	}
}

//
// append an entry to the Auto Responder log file
//
function WriteARLog($s_to,$s_subj,$s_info)
{
	global $aServerVars,$php_errormsg;

	if (Settings::isEmpty('LOGDIR') || Settings::isEmpty('AUTORESPONDLOG')) {
		return;
	}

	$log_file = Settings::get('LOGDIR') . "/" . Settings::get('AUTORESPONDLOG');
	@   $log_fp = fopen($log_file,"a");
	if ($log_fp === false) {
		SendAlert(GetMessage(MSG_FILE_OPEN_ERROR,array("NAME"  => $log_file,
		                                               "TYPE"  => "log",
		                                               "ERROR" => CheckString($php_errormsg)
		)));
		return;
	}
	$a_entry   = array();
	$a_entry[] = gmdate("H:i:s d-M-y T"); // date/time in GMT
	$a_entry[] = $aServerVars['REMOTE_ADDR']; // remote IP address
	$a_entry[] = $s_to; // target email address
	$a_entry[] = $s_subj; // subject line
	$a_entry[] = $s_info; // information

	$s_log_entry = implode(",",$a_entry) . "\n";
	fwrite($log_fp,$s_log_entry);
	fclose($log_fp);
}

/*
 * The main logic starts here....
 */

//
// First, a special case; if formmail.php is called like this:
//  http://.../formmail.php?testalert=1
// it sends a test message to the default alert address with some
// information about your PHP version and the DOCUMENT_ROOT.
//
if (isset($aGetVars["testalert"]) && $aGetVars["testalert"] == 1) {
	function ShowServerVar($s_name)
	{
		global $aServerVars;

		return (isset($aServerVars[$s_name]) ? $aServerVars[$s_name] : "-not set-");
	}

	$sAlert = GetMessage(MSG_ALERT,
	                     array("LANG"               => $sLangID,
	                           "PHPVERS"            => $ExecEnv->getPHPVersionString(),
	                           "FM_VERS"            => $FM_VERS,
	                           "SERVER"             => (IsServerWindows() ? "Windows" : "non-Windows"),
	                           "DOCUMENT_ROOT"      => ShowServerVar('DOCUMENT_ROOT'),
	                           "SCRIPT_FILENAME"    => ShowServerVar('SCRIPT_FILENAME'),
	                           "PATH_TRANSLATED"    => ShowServerVar('PATH_TRANSLATED'),
	                           "REAL_DOCUMENT_ROOT" => CheckString($REAL_DOCUMENT_ROOT),
	                     ));

	if (Settings::get('DEF_ALERT') == "") {
		echo "<p>" . GetMessage(MSG_NO_DEF_ALERT) . "</p>";
	} elseif (SendAlert($sAlert,false,true)) {
		echo "<p>" . GetMessage(MSG_TEST_SENT) . "</p>";
	} else {
		echo "<p>" . GetMessage(MSG_TEST_FAILED) . "</p>";
	}
	exit;
}

if (isset($aGetVars["testlang"]) && $aGetVars["testlang"] == 1) {

	function ShowMessages()
	{
		global $aMessages,$sLangID,$aGetVars,$sHTMLCharSet;

		//
		// force message numbers on unless "mnums=no"
		//
		if (isset($aGetVars["mnums"]) && $aGetVars["mnums"] == "no") {
			Settings::set('bShowMesgNumbers',false);
		} else {
			Settings::set('bShowMesgNumbers',true);
		}
		LoadBuiltinLanguage();

		$s_def_lang  = $sLangID;
		$a_def_mesgs = $aMessages;

		LoadLanguageFile();

		$s_active_lang  = $sLangID;
		$a_active_mesgs = $aMessages;

		$a_list = get_defined_constants();

		echo "<html>\n";
		echo "<head>\n";
		if (isset($sHTMLCharSet) && $sHTMLCharSet !== "") {
			echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$sHTMLCharSet\">\n";
		}
		echo "</head>\n";
		echo "<body>\n";

		echo "<table border=\"1\" cellpadding=\"10\" width=\"95%\">\n";
		echo "<tr>\n";
		echo "<th>\n";
		echo "Message Number";
		echo "</th>\n";
		echo "<th>\n";
		echo "$s_def_lang";
		echo "</th>\n";
		echo "<th>\n";
		echo "$s_active_lang";
		echo "</th>\n";
		echo "</tr>\n";
		foreach ($a_list as $s_name => $i_value) {
			if (substr($s_name,0,4) == "MSG_") {
				//
				// some PHP constants begin with MSG_, so we try to skip them
				// too
				//
				switch ($s_name) {
					case "MSG_IPC_NOWAIT":
					case "MSG_EAGAIN":
					case "MSG_ENOMSG":
					case "MSG_NOERROR":
					case "MSG_EXCEPT":
					case "MSG_OOB":
					case "MSG_PEEK":
					case "MSG_DONTROUTE":
					case "MSG_EOR":
						continue 2;
				}
				if ($i_value >= 256) {
					continue;
				}
				echo "<tr>\n";
				echo "<td valign=\"top\">\n";
				echo "$s_name ($i_value)";
				echo "</td>\n";
				echo "<td valign=\"top\">\n";
				$aMessages = $a_def_mesgs;
				$s_def_msg = GetMessage((int)$i_value,array(),true,true);
				echo nl2br(htmlentities($s_def_msg)); // English - don't need
				// FixedHTMLEntities
				echo "</td>\n";
				echo "<td valign=\"top\">\n";
				$aMessages = $a_active_mesgs;
				$s_act_msg = GetMessage((int)$i_value,array(),true,true);
				if ($s_def_msg == $s_act_msg) {
					echo "<i>identical</i>\n";
				} else {
					echo nl2br(FixedHTMLEntities($s_act_msg));
				}
				echo "</td>\n";
				echo "</tr>\n";
			}
		}
		echo "</table>\n";

		echo "</body>\n";
		echo "</html>\n";
	}

	ShowMessages();
	exit();
}

//
// For saved files, add in the "new_name" values to the given
// array.
//
function    GetSavedFileNames($a_values)
{
	if (IsSetSession("FormSavedFiles")) {
		$a_saved_files = GetSession("FormSavedFiles");
		foreach ($a_saved_files as $s_fld => $a_upload) {
			if (isset($a_upload["name"])) {
				$a_values[$s_fld] = $a_upload["name"];
			}
			if (isset($a_upload["new_name"])) {
				$a_values["name_of_$s_fld"] = $a_upload["new_name"];
			}
		}
	}
	return ($a_values);
}

//
// Scan the Multi form sequence values and build up the values that
// were submitted to the given form index.
//
function GetMultiValues($a_form_list,$i_form_index,$a_order = array(),
                        $a_clean = array(),
                        $a_raw_data = array(),
                        $a_all_data = array(),
                        $a_file_data = array())
{
	$a_ret_clean = $a_ret_raw = $a_ret_all = $a_ret_files = array();
	for ($ii = 0 ; $ii < $i_form_index ; $ii++) {
		//
		// only add a field to the order if it's not already there
		//
		$a_form_order = $a_form_list[$ii]["ORDER"];
		$n_order      = count($a_form_order);
		for ($jj = 0 ; $jj < $n_order ; $jj++) {
			if (array_search($a_form_order[$jj],$a_order) === false) {
				$a_order[] = $a_form_order[$jj];
			}
		}
		$a_ret_clean = array_merge($a_ret_clean,$a_form_list[$ii]["CLEAN"]);
		$a_ret_raw   = array_merge($a_ret_raw,$a_form_list[$ii]["RAWDATA"]);
		$a_ret_all   = array_merge($a_ret_all,$a_form_list[$ii]["ALLDATA"]);
		$a_ret_files = array_merge($a_ret_files,$a_form_list[$ii]["FILES"]);
	}
	//
	// later values must take precedence to earlier values,
	// so merge in the passed-in data last
	//
	$a_ret_clean = array_merge($a_ret_clean,$a_clean);
	$a_ret_raw   = array_merge($a_ret_raw,$a_raw_data);
	$a_ret_all   = array_merge($a_ret_all,$a_all_data);
	$a_ret_files = array_merge($a_ret_files,$a_file_data);
	return (array($a_order,$a_ret_clean,$a_ret_raw,$a_ret_all,$a_ret_files));
}

$bMultiForm = false;

//
// Multi-page form sequencing is complicated....
// Requirements:
//      1. The first page in a multi-page form sequence must provide:
//          - multi_start field set to 1
//          - this_form field (it's own URL)
//          - next_form field (multi-page form template name)
//      2. Subsequent pages must provide either:
//          next_form (the next template name in the sequence)
//         OR
//          good_url or good_template or neither; this terminates
//          the multi-page processing and provides the final URL.
// Logic:
//      1. We create session variables to contain information about
//         the sequence.
//      2. On the first submission from the starting form, we record
//         its "this_form" URL and the data submitted.
//         We also create a URL (to FormMail) that will allow return
//         to the *next* form in the sequence.
//      3. On submission from other forms in the sequence, we record
//         the data that was submitted so we can return to that
//         form with the user's data re-filled into the form.
//         We also create a URL (to FormMail) that will allow return
//         to the *next* form in the sequence.
//      4. A return URL contains "return=index" where index is the
//         form sequence index number.  This is a URL to FormMail.
//         FormMail gets the template name or URL (URL only for the
//         starting form) and outputs the requested HTML form.  It also
//         truncates the session data for forms after the returned-to
//         one.
//

//
// Return to a previous form in a sequence.
//
function MultiFormReturn($i_return_to)
{
	global $iFormIndex;
	global $SessionAccessor;

	if (!IsSetSession("FormList") ||
	    !IsSetSession("FormIndex") ||
	    $i_return_to < 0 ||
	    $i_return_to > GetSession("FormIndex")
	) {
		Error("cannot_return",GetMessage(MSG_CANNOT_RETURN,
		                                 array("TO"       => $i_return_to,
		                                       "TOPINDEX" => (
		                                       IsSetSession("FormIndex") ?
			                                       GetSession("FormIndex") :
			                                       "<undefined>")
		                                 )),
		      false,false);
	}
	$a_list = GetSession("FormList");
	assert($i_return_to < count($a_list));
	$a_form_def = $a_list[$i_return_to];
	SetSession("FormList",$a_list = array_slice($a_list,0,$i_return_to + 1));
	SetSession("FormIndex",$iFormIndex = $i_return_to);
	if (isset($a_form_def["FORM"])) {
		//
		// get the values that were originally submitted to this form
		//
		list(,,$a_values,,) = GetMultiValues($a_list,$i_return_to);
		//
		// get the session values
		//
		$SessionAccessor->CopyIn($a_values,true);
		$a_lines = array();
		//
		// process the page as a template
		//
		if (ProcessMultiFormTemplate($a_form_def["FORM"],$a_values,$a_lines)) {
			$n_lines = count($a_lines);
			$s_buf   = "";
			for ($ii = 0 ; $ii < $n_lines ; $ii++) {
				$s_buf .= $a_lines[$ii] . "\n";
				unset($a_lines[$ii]); // free memory (hopefully)
			}
			unset($a_lines); // free memory (hopefully)
			//
			// put in the values that the user previously submitted
			// to this form
			//
			echo SetPreviousValues($s_buf,$a_form_def["RAWDATA"]);
		} else {
			Error("multi_form_failed",GetMessage(MSG_MULTIFORM_FAILED,
			                                     array("NAME" => $a_form_def["FORM"])),false,false);
		}
	} else
		//
		// we probably should include 
		//  $SessionAccessor->CopyIn(...,true);
		// at some stage in the future to get the session values....need to think about this
		// and run some case studies.
		//
	{
		ProcessReturnToForm($a_form_def["URL"],$a_form_def["RAWDATA"],array("multi_start"));
	}
	//echo "Returned to $i_return_to";
}

//
// Store any data just submitted and specified as "multi_keep".
//
function MultiKeep()
{
	global $SPECIAL_VALUES,$aRawDataValues;

	if (isset($SPECIAL_VALUES["multi_keep"]) &&
	    !empty($SPECIAL_VALUES["multi_keep"])
	) {
		$a_list = TrimArray(explode(",",$SPECIAL_VALUES["multi_keep"]));
		if (IsSetSession("FormKeep")) {
			$a_keep = GetSession("FormKeep");
		} else {
			$a_keep = array();
		}
		//
		// For each data field specified in "multi_keep" store its
		// value in the FormKeep session variable.
		// If a field is specified and does not exist in the
		// recent submission, its value is discarded.
		//
		foreach ($a_list as $s_fld_name) {
			if (!empty($s_fld_name)) {
				if (isset($aRawDataValues[$s_fld_name])) {
					$a_keep[$s_fld_name] = $aRawDataValues[$s_fld_name];
				} else {
					unset($a_keep[$s_fld_name]);
				}
			}
		}
		SetSession("FormKeep",$a_keep);
	}
}

//
// Perform Logic for Multi-Page form sequences
//
function MultiFormLogic()
{
	global $bMultiForm,$SPECIAL_VALUES,$aServerVars,$aFileVars,$ExecEnv;
	global $sFormMailScript,$bGotGoBack,$bGotNextForm,$iFormIndex;
	global $aFieldOrder,$aCleanedValues,$aRawDataValues,$aAllRawValues;

	if ($SPECIAL_VALUES["multi_start"] == 1) {
		if (empty($SPECIAL_VALUES["this_form"])) {
			ErrorWithIgnore("need_this_form",GetMessage(MSG_NEED_THIS_FORM),false,false);
		}

		$bMultiForm = true;
		//
		// Start of multi-page form sequence
		//
		$a_list     = array();
		$a_list[0]  = array("URL"     => $SPECIAL_VALUES["this_form"],
		                    "ORDER"   => $aFieldOrder,
		                    "CLEAN"   => $aCleanedValues,
		                    "RAWDATA" => $aRawDataValues,
		                    "ALLDATA" => $aAllRawValues,
		                    "FILES"   => $aFileVars
		);
		$iFormIndex = 0; // zero is the first form, which was just submitted
		SetSession("FormList",$a_list);
		SetSession("FormIndex",$iFormIndex);
		//
		// this is a fresh session, so remove any remembered values
		//
		UnsetSession("FormSavedFiles");
		UnsetSession("FormKeep");
	} elseif (IsSetSession("FormList")) {
		$bMultiForm = true;
	}

	if ($bMultiForm) {
		$sFormMailScript = $ExecEnv->GetScript();
		$iFormIndex      = GetSession("FormIndex");
	}

	//
	// If we're going forward in a multi-page form sequence,
	// compute a URL to return to the form we're about to display.
	//
	if ($bMultiForm && !$bGotGoBack) {
		//
		// record the data that was just submitted by the previous form
		//
		$iFormIndex                     = GetSession("FormIndex");
		$a_list                         = GetSession("FormList");
		$a_list[$iFormIndex]["ORDER"]   = $aFieldOrder;
		$a_list[$iFormIndex]["CLEAN"]   = $aCleanedValues;
		$a_list[$iFormIndex]["RAWDATA"] = $aRawDataValues;
		$a_list[$iFormIndex]["ALLDATA"] = $aAllRawValues;
		if (count($aFileVars) > 0 && !Settings::get('FILEUPLOADS')) {
			SendAlert(GetMessage(MSG_FILE_UPLOAD));
		} elseif (count($aFileVars) > 0 && !SaveAllUploadedFiles($aFileVars)) {
			Error("upload_save_failed",GetMessage(MSG_MULTI_UPLOAD),false,false);
		}
		$a_list[$iFormIndex]["FILES"] = $aFileVars;
		$iFormIndex++;
		$s_url               = GetReturnLink($sFormMailScript,$iFormIndex);
		$a_list[$iFormIndex] = array("URL"     => $s_url,
		                             "FORM"    => $SPECIAL_VALUES["next_form"],
		                             "ORDER"   => $aFieldOrder,
		                             "CLEAN"   => $aCleanedValues,
		                             "RAWDATA" => $aRawDataValues,
		                             "ALLDATA" => $aAllRawValues,
		                             "FILES"   => $aFileVars
		);
		SetSession("FormList",$a_list);
		SetSession("FormIndex",$iFormIndex);
		MultiKeep();
	}
}

//
// Check for the MIME Attack
//
function DetectMimeAttack($a_fields,&$s_attack,&$s_info,&$s_user_info)
{
	//
	// if any of the recipient fields contain "MIME-Version" or
	// "Content-Type" then this is the MIME attack
	// Now also checks the subject field.
	//
	$a_rec_flds = array("recipients","cc","bcc","replyto","subject");
	foreach ($a_rec_flds as $s_fld) {
		if (isset($a_fields[$s_fld])) {
			//
			// some of the fields can be arrays
			//
			if (is_array($a_fields[$s_fld])) {
				$s_data = implode(",",$a_fields[$s_fld]);
			} else {
				$s_data = $a_fields[$s_fld];
			}
			$s_data = strtolower($s_data);
			if (($i_mime = strpos($s_data,"mime-version")) !== false ||
			    ($i_cont = strpos($s_data,"content-type")) !== false
			) {
				$s_attack = "MIME";
				$s_info   = GetMessage(MSG_ATTACK_MIME_INFO,
				                       array("FLD"     => $s_fld,
				                             "CONTENT" => ($i_mime !== false) ?
					                             "mime-version" :
					                             "content-type"
				                       ),false);
				return (true);
			}
		}
	}
	return (false);
}

//
// Strip common language sequences from the data
// that might otherwise look like a junk attack.
// We replace them with a space so that the stripping
// cannot create more junk accidentally.
//
function    AttackDetectionStripLang($s_data)
{

	foreach (Settings::get('ATTACK_DETECTION_JUNK_LANG_STRIP') as $s_seq) {
		$s_data = str_replace($s_seq," ",$s_data);
	}
	return ($s_data);
}

//
// Find strings of $n_consec characters from the alphabet of $s_alpha
// in $s_data.  Return the count of instances found.
//
function    AttackDetectionFindJunk($s_data,$s_alpha,$n_consec,&$a_matches)
{
	$s_pat = "/[" . preg_quote($s_alpha,"/") . "]{" . "$n_consec,$n_consec" . "}/";
	if (($n_count = preg_match_all($s_pat,$s_data,$a_matches)) === false) {
		$n_count   = 0;
		$a_matches = array();
	} else {
		$a_matches = $a_matches[0];
	}
	return ($n_count);
}

//
// Check for the Junk Attack
//
function DetectJunkAttack($a_fields,&$s_attack,&$s_info,&$s_user_info)
{

	//
	// If any field contains junk data, trigger detection.
	//
	$n_count        = 0;
	$a_matches      = array();
	$a_user_matches = array();
	foreach ($a_fields as $s_fld => $m_value) {
		if (IsSpecialField($s_fld) || IsSpecialMultiField($s_fld)) {
			//
			// Skip special fields because they don't contain
			// normal user input.
			// But, some special fields do contain normal user
			// input, so we don't skip them.
			//
			$b_skip = true;
			switch ($s_fld) {
				case "realname":
				case "subject":
					$b_skip = false;
					break;
			}
			if ($b_skip) {
				continue;
			}
		}
		//
		// Ignore fields that are specified to contain
		// technical information.
		//
		if (in_array($s_fld,Settings::get('ATTACK_DETECTION_JUNK_IGNORE_FIELDS'),true)) {
			continue;
		}
		if (isset($m_value) && !FieldManager::IsEmpty($m_value)) {
			if (!is_array($m_value)) {
				$m_value = array($m_value);
			}
			foreach ($m_value as $s_data) {
				$s_orig_data = $s_data = strtolower($s_data);
				//
				// Strip out sequences that might be common to
				// the user's language.
				// For example, in English, there are a lot of common
				// words that can easily be protected from our
				// tests. For example, "thousandths" has 5 consecutive consonants
				// but is a reasonable word.
				// Similarly, "queue" has 4 consecutive vowels.
				//
				$s_data = AttackDetectionStripLang($s_data);
				//
				// Look for strings of too many vowels or too many consonants.
				// For safety, we must detect more than one instance, because there
				// are a lot of normal words that may be caught by this test.
				// The number of instances is controlled by configuration.
				//
				$n_match = AttackDetectionFindJunk($s_data,Settings::get('ATTACK_DETECTION_JUNK_CONSONANTS'),
				                                   Settings::get('ATTACK_DETECTION_JUNK_CONSEC_CONSONANTS'),
				                                   $a_match_cons);
				if ($n_match > 0) {
					$a_user_matches[] = $s_orig_data;
				}
				$n_count += $n_match;
				$n_match = AttackDetectionFindJunk($s_data,Settings::get('ATTACK_DETECTION_JUNK_VOWELS'),
				                                   Settings::get('ATTACK_DETECTION_JUNK_CONSEC_VOWELS'),
				                                   $a_match_vow);
				if ($n_match > 0) {
					$a_user_matches[] = $s_orig_data;
				}
				$n_count += $n_match;
				if ($n_count >= Settings::get('ATTACK_DETECTION_JUNK_TRIGGER')) {
					$a_matches   = array_merge($a_matches,$a_match_cons,$a_match_vow);
					$s_user_info = GetMessage(MSG_USER_ATTACK_JUNK,
					                          array("INPUT" => implode(", ",$a_user_matches)),false);
					$s_attack    = "JUNK";
					$s_info      = GetMessage(MSG_ATTACK_JUNK_INFO,
					                          array("FLD"  => $s_fld,
					                                "JUNK" => implode(" ",$a_matches)
					                          ),false);
					return (true);
				}
			}
		}
	}
	return (false);
}

//
// Check for the duplicate data attack
//
function DetectDupAttack($a_fields,&$s_attack,&$s_info,&$s_user_info)
{
	//
	// if any of the configured fields contain duplicate data,
	// then this lame attack has been detected
	//

	$a_data_map = array();
	foreach (Settings::get('ATTACK_DETECTION_DUPS') as $s_fld) {
		if (isset($a_fields[$s_fld]) &&
		    is_scalar($a_fields[$s_fld]) && // can only work with string data
		    !empty($a_fields[$s_fld])
		) {
			$s_data = (string)$a_fields[$s_fld];
			if (isset($a_data_map[$s_data])) {
				//
				// duplicate found!
				//
				$s_attack    = "Duplicate Fields";
				$s_info      = GetMessage(MSG_ATTACK_DUP_INFO,
				                          array("FLD1" => $a_data_map[$s_data],
				                                "FLD2" => $s_fld
				                          ),false);
				$s_user_info = GetMessage(MSG_USER_ATTACK_DUP,array(),false);
				return (true);
			}
			$a_data_map[$s_data] = $s_fld;
		}
	}
	return (false);
}

//
// Check for the email addresses in specials attack
//
function DetectSpecialsAttack($a_fields,&$s_attack,&$s_info,&$s_user_info)
{
	//
	// look for email addresses in whole fields
	//
	foreach (Settings::get('ATTACK_DETECTION_SPECIALS_ONLY_EMAIL') as $s_fld) {
		if (isset($a_fields[$s_fld]) &&
		    is_scalar($a_fields[$s_fld]) && // can only work with string data
		    !empty($a_fields[$s_fld])
		) {
			$s_data = $a_fields[$s_fld];
			if (preg_match("/^\b[-a-z0-9._%]+@[-.%_a-z0-9]+\.[a-z]{2,9}\b$/i",
			               $s_data) === 1
			) {
				//
				// email address found in wrong field
				//
				$s_attack = "Special Fields Only";
				$s_info   = GetMessage(MSG_ATTACK_SPEC_INFO,
				                       array("FLD" => $s_fld),false);
				return (true);
			}
		}
	}
	//
	// look for email addresses in any part of fields
	//
	foreach (Settings::get('ATTACK_DETECTION_SPECIALS_ANY_EMAIL') as $s_fld) {
		if (isset($a_fields[$s_fld]) &&
		    is_scalar($a_fields[$s_fld]) && // can only work with string data
		    !empty($a_fields[$s_fld])
		) {
			$s_data = $a_fields[$s_fld];
			if (preg_match("/\b[-a-z0-9._%]+@[-.%_a-z0-9]+\.[a-z]{2,9}\b/i",
			               $s_data) > 0
			) {
				//
				// email address found in wrong field
				//
				$s_attack = "Special Fields Any";
				$s_info   = GetMessage(MSG_ATTACK_SPEC_INFO,
				                       array("FLD" => $s_fld),false);
				return (true);
			}
		}
	}
	return (false);
}

//
// Check for "many URLs in a field" and "many fields with URLs" attacks
//
function DetectManyURLsAttack($a_fields,&$s_attack,&$s_info,&$s_user_info)
{

	$a_fld_names = array();
	//
	// actual URL link patterns
	//
	$s_srch = '((\bhttps{0,1}:\/\/|<\s*a\s+href=["' . "'" . ']{0,1})[-a-z0-9.]+\b)';

	//
	// now add configurable patterns
	//
	if (!Settings::isEmpty('ATTACK_DETECTION_URL_PATTERNS') &&
	    is_array(Settings::get('ATTACK_DETECTION_URL_PATTERNS'))
	) {
		//
		// escape / characters and build up a string
		// of alternate patterns
		//
		foreach (Settings::get('ATTACK_DETECTION_URL_PATTERNS') as $s_pat) {
			if ($s_pat == "") {
				continue;
			}
			$s_srch .= "|" . str_replace('/','\/',$s_pat);
		}
	}

	foreach ($a_fields as $s_fld => $s_data) {
		if (IsSpecialField($s_fld) || IsSpecialMultiField($s_fld))
			//
			// skip special fields because some are supposed to
			// have URLs in them
			//
		{
			continue;
		}
		if (isset($s_data) &&
		    is_scalar($s_data) && // can only work with string data
		    !empty($s_data)
		) {
			$n_match = preg_match_all("/$s_srch/msi",$s_data,$a_matches);
			if (!is_int($n_match)) {
				$n_match = 0;
			}
			/*
             * debugging code....
             if ($n_match > 0)
                echo "Pattern is '".htmlentities($s_srch)."' with '".
                    htmlentities($s_data)."' field '$s_fld', matched=$n_match<br />";
             */
			if (Settings::get('ATTACK_DETECTION_MANY_URLS') > 0) {
				if ($n_match >= Settings::get('ATTACK_DETECTION_MANY_URLS')) {
					$s_attack    = "Many URLS in a field";
					$s_info      = GetMessage(MSG_ATTACK_MANYURL_INFO,
					                          array("FLD" => $s_fld,"NUM" => ($n_match)),false);
					$s_user_info = GetMessage(MSG_USER_ATTACK_MANY_URLS,array(),false);
					return (true);
				}
			}
			if ($n_match > 0) {
				$a_fld_names[] = $s_fld;
			}
		}
	}
	if (Settings::get('ATTACK_DETECTION_MANY_URL_FIELDS') > 0) {
		if (count($a_fld_names) >= Settings::get('ATTACK_DETECTION_MANY_URL_FIELDS')) {
			$s_attack    = "Many fields with URLs";
			$s_info      = GetMessage(MSG_ATTACK_MANYFIELDS_INFO,
			                          array("FLDS" => implode(",",$a_fld_names),
			                                "NUM"  => (count($a_fld_names))
			                          ),false);
			$s_user_info = GetMessage(MSG_USER_ATTACK_MANY_URL_FIELDS,array(),false);
			return (true);
		}
	}
	return (false);
}

function    IsAjax()
{
	global $SPECIAL_VALUES,$aFormVars,$aGetVars;

	//
	// this may be called too early to have SPECIAL_VALUES loaded,
	// so we check the submitted form vars too.
	//
	if ($SPECIAL_VALUES["fmmode"] == "ajax") {
		return (true);
	}
	if (isset($aFormVars["fmmode"])) {
		return ($aFormVars["fmmode"] == "ajax");
	}
	if (isset($aGetVars["fmmode"])) {
		return ($aGetVars["fmmode"] == "ajax");
	}
	return (false);
}

//
// Detect annoying attacks and prevent them from sending spurious
// alert messages.
//
function DetectAttacks($a_fields)
{

	$s_info      = $s_attack = "";
	$b_attacked  = false;
	$s_user_info = "";
	if (Settings::get('ATTACK_DETECTION_MIME')) {
		if (DetectMimeAttack($a_fields,$s_attack,$s_info,$s_user_info)) {
			$b_attacked = true;
		}
	}
	if (!$b_attacked && !Settings::isEmpty('ATTACK_DETECTION_DUPS')) {
		if (DetectDupAttack($a_fields,$s_attack,$s_info,$s_user_info)) {
			$b_attacked = true;
		}
	}
	if (!$b_attacked && Settings::get('ATTACK_DETECTION_SPECIALS')) {
		if (DetectSpecialsAttack($a_fields,$s_attack,$s_info,$s_user_info)) {
			$b_attacked = true;
		}
	}
	if (!$b_attacked && (Settings::get('ATTACK_DETECTION_MANY_URLS') ||
	                     Settings::get('ATTACK_DETECTION_MANY_URL_FIELDS'))
	) {
		if (DetectManyURLsAttack($a_fields,$s_attack,$s_info,$s_user_info)) {
			$b_attacked = true;
		}
	}
	if (Settings::get('ATTACK_DETECTION_JUNK')) {
		if (DetectJunkAttack($a_fields,$s_attack,$s_info,$s_user_info)) {
			$b_attacked = true;
		}
	}

	if (!$b_attacked && !Settings::isEmpty('ATTACK_DETECTION_REVERSE_CAPTCHA')) {
		if (DetectRevCaptchaAttack(Settings::get('ATTACK_DETECTION_REVERSE_CAPTCHA'),$a_fields,$s_attack,$s_info,
		                           $s_user_info)
		) {
			$b_attacked = true;
		}
	}

	if ($b_attacked) {
		if (function_exists('FMHookAttacked')) {
			FMHookAttacked('');
		} /* in the future, pass the type of attack */
		if (Settings::get('ALERT_ON_ATTACK_DETECTION')) {
			SendAlert(GetMessage(MSG_ATTACK_DETECTED,
			                     array("ATTACK" => $s_attack,
			                           "INFO"   => $s_info,
			                     )),
			          false);
		}
		if (!IsAjax() && Settings::get('ATTACK_DETECTION_URL') !== "") {
			Redirect(Settings::get('ATTACK_DETECTION_URL'),GetMessage(MSG_FORM_ERROR));
		} else {
			global $SERVER;

			CreatePage(GetMessage(MSG_ATTACK_PAGE,array("SERVER" => $SERVER,"USERINFO" => $s_user_info)),
			           GetMessage(MSG_FORM_ERROR));
		}
		exit;
	}
}

//
// Implement reverse captcha.  At least two fields must be provided.
// At least one of the fields should have a non-empty value.
//
function    DetectRevCaptchaAttack($a_revcap_spec,$a_form_data,&$s_attack,&$s_info,&$s_user_info)
{
	global $bReverseCaptchaCompleted;

	if (count($a_revcap_spec) < 2) {
		SendAlert(GetMessage(MSG_REV_CAP));
		return (false);
	}
	//
	// check the reverse captcha fields
	//
	$n_empty    = $n_non_empty = 0;
	$b_attacked = false;
	$s_info     = "";
	foreach ($a_revcap_spec as $s_fld_name => $s_value) {
		if ($s_value === "") {
			$n_empty++;
			if (isset($a_form_data[$s_fld_name]) &&
			    $a_form_data[$s_fld_name] !== ""
			) {
				$b_attacked = true;
				$s_info .= "\n" . GetMessage(MSG_ATTACK_REV_CAP_INFO,
				                             array("FLD"     => $s_fld_name,
				                                   "CONTENT" => $a_form_data[$s_fld_name]
				                             ),false);
			}
		} else {
			$n_non_empty++;
			if (!isset($a_form_data[$s_fld_name]) ||
			    $a_form_data[$s_fld_name] !== $s_value
			) {
				$b_attacked = true;
				$s_info .= "\n" . GetMessage(MSG_ATTACK_REV_CAP_INFO,
				                             array("FLD"     => $s_fld_name,
				                                   "CONTENT" =>
					                                   isset($a_form_data[$s_fld_name]) ?
						                                   $a_form_data[$s_fld_name] :
						                                   ""
				                             ),false);
			}
		}
	}
	//
	// check that the rev captcha specification is correct:
	//      at least two fields, at least one empty
	//      and at least one non-empty
	//
	if ($n_empty + $n_non_empty < 2 ||
	    $n_empty == 0 || $n_non_empty == 0
	) {
		SendAlert(GetMessage(MSG_REV_CAP));
		return (false);
	}
	if ($b_attacked) {
		$s_attack    = "Reverse Captcha";
		$s_user_info = GetMessage(MSG_USER_ATTACK_REV_CAP,array(),false);
	}
	$bReverseCaptchaCompleted = !$b_attacked;
	//FMDebug("RevCaptcha done: ".($bReverseCaptchaCompleted ? "success" : "failure"));
	return ($b_attacked);
}

//
// Check whether a form submission is allowed based on any Captcha provided
//
function    CheckCaptchaSubmit()
{
	global $SPECIAL_VALUES,$reCaptchaProcessor;

	if ($SPECIAL_VALUES["imgverify"] !== "") {
		//
		// implement reCaptcha
		//
		if (isset($reCaptchaProcessor)) {
			$s_error = '';
			if (!$reCaptchaProcessor->Check($SPECIAL_VALUES["imgverify"],$SPECIAL_VALUES,$s_error)) {
				$s_error_mesg = GetMessage(MSG_RECAPTCHA_MATCH,array("ERR" => $s_error));
				UserError("recaptcha",$s_error_mesg,array(),array('imgverify' => $s_error_mesg));
			}
		}
		//
		// implement other CAPTCHA
		//
		else {
			//
			// VerifyImgString is from Tectite's simple verifyimg.php CAPTCHA
			// turing_string is from Captcha Creator
			//
			if (!IsSetSession("VerifyImgString") &&
			    !IsSetSession("turing_string")
			) {
				ErrorWithIgnore("verify_failed",GetMessage(MSG_VERIFY_MISSING),false);
			}
			//
			// the user's entry must match the value in the session; allow
			// spaces in the user's input
			//
			if (IsSetSession("VerifyImgString")) {
				if (strtoupper(str_replace(" ","",$SPECIAL_VALUES["imgverify"])) !==
				    strtoupper(GetSession("VerifyImgString"))
				) {
					$s_error_mesg = GetMessage(MSG_VERIFY_MATCH);
					UserError("img_verify",$s_error_mesg,array(),array('imgverify' => $s_error_mesg));
				}
			} else {
				if (strtoupper(str_replace(" ","",$SPECIAL_VALUES["imgverify"])) !==
				    strtoupper(GetSession("turing_string"))
				) {
					$s_error_mesg = GetMessage(MSG_VERIFY_MATCH);
					UserError("img_verify",$s_error_mesg,array(),array('imgverify' => $s_error_mesg));
				}
			}
		}
	}
}

/*
 * Class:       AutoResponder
 * Description:     
 *  Implements the auto responding feature of FormMail.
 *  The object must only be created after special fields have been
 *  processed.
 */

class   AutoResponder
{
	var $_bRequested; // true if requested by the form
	var $_sTo; // to-address for auto response
	var $_sSubject; // subject for auto response

	var $_iNone = 0; // must be zero - initializes iType and iCaptchaType

	var $_iCaptchaType; // type of CAPTCHA that's been successfully processed
	var $_bCaptchaOK; // true if CAPTCHA processing is OK, otherwise false

	//
	// values of _iCaptchaType
	//
	var $_iFull = 1; // full captcha
	var $_iRev = 2; // reverse captcha

	var $_iType; // type of autoresponse (template or plain)

	//
	// values of _iType
	//
	var $_iSendTemplate = 1; // send a template
	var $_iSendPlain = 2; // send a plain file

	/*
     * Method:      AutoResponder ctor
     * Parameters:  void
     * Returns:     n/a 
     * Description: 
     *  Constructs the object.
     */
	function    AutoResponder()
	{
		global $SPECIAL_VALUES;

		$this->_bCaptchaOK   = $this->_bRequested = false;
		$this->_sTo          = "";
		$this->_sSubject     = "";
		$this->_iType        = $this->_iNone;
		$this->_iCaptchaType = $this->_iNone;

		//
		// An autoreponse is sometimes optional in the sense the user
		// can choose whether they want it.
		// It can also be mandatory, and enforced by the form owner.
		// Here are the rules:
		//  1.  If there is no return email address (email field), no autoresponse.
		//      In this case, if autoresponse has been requested by the form, send
		//      an alert message to the form owner, but otherwise ignore the problem
		//      (it's optional).
		//  2.  If no autoresponse has been requested by the form, skip.
		//  3.  If HTMLTemplate or PlainTemplate is set:
		//      3a. If full CAPTCHA has been performed, send autoresponse.
		//      3b  If no full CAPTCHA has been performed, no autoresponse.
		//  4.  If HTMLTemplate and PlainTemplate are *not* set and either
		//      PlainFile or HTMLFile is set:
		//      4a. If full CAPTCHA has been performed, send autoresponse.
		//      4b. If Reverse CAPTCHA has been performed, send autoresponse.
		//      4c. Otherwise, no autoresponse.
		//  5.  If full CAPTCHA is attempted but fails (e.g. wrong input), display
		//      error.
		//
		// The form owner can enforce autoresponse by making "email" required,
		// and making "arverify" or "recaptcha_response_field" required (for templates)
		// or by ensuring Reverse CAPTCHA is performed (for PlainFile or HTMLFile).
		//
		if (IsAROptionSet('HTMLTemplate') ||
		    IsAROptionSet('PlainTemplate')
		) {
			$this->_iType = $this->_iSendTemplate;
		}
		if (IsAROptionSet('PlainFile') ||
		    IsAROptionSet('HTMLFile')
		) {
			$this->_iType = $this->_iSendPlain;
		}
		if ($this->_iType) {
			//
			// the form has requested autoresponse....
			// we need an email address to send to
			//
			if (!isset($SPECIAL_VALUES["email"]) || empty($SPECIAL_VALUES["email"])) {
				SendAlert(GetMessage(MSG_ARESP_EMAIL));
			} else {
				$this->_bRequested = true;
				$this->_sTo        = $SPECIAL_VALUES["email"];
				if (IsAROptionSet('Subject')) {
					$this->_sSubject = GetAROption('Subject');
				} else {
					$this->_sSubject = GetMessage(MSG_ARESP_SUBJ,array(),false);
				}
			}
		}
	}

	/*
     * Method:      AutoResponder::IsRequested
     * Parameters:  void
     * Returns:     bool    true if autoresponding has been requested
     * Description: 
     *  Determines if autoresponding has been requested by the HTML.  
     */
	function    IsRequested()
	{
		return ($this->_bRequested);
	}

	/*
     * Method:      AutoResponder::Process
     * Parameters:  $b_check_only   if true, perform checks but do not send
     * Returns:     void
     * Description: 
     *  Processes the autorespond.
     */
	function    Process($b_check_only = false)
	{
		global $SPECIAL_VALUES;

		FMDebug("AutoResponder::Process: check=" . ($b_check_only ? "Y" : "N"));
		if ($this->IsRequested()) {
			FMDebug("AutoResponder::Process: requested");
			//
			// verify CAPTCHA or that Reverse CAPTCHA has been completed
			// (unless we've already done that)
			//
			$this->_CheckCaptcha();
			if (!$b_check_only && $this->_bCaptchaOK) {
				FMDebug("AutoResponder::Process: proceeding, type=" . $this->_iType);
				//
				// for a template, full CAPTCHA must have been processed
				//
				if ($this->_iType == $this->_iSendTemplate) {
					if ($this->_iCaptchaType == $this->_iFull) {
						$this->_Send(true);
					}
				}
				//
				// for a plain file, reverse CAPTCHA is sufficient, any CAPTCHA is OK
				//
				elseif ($this->_iType == $this->_iSendPlain) {
					if ($this->_iCaptchaType) {
						$this->_Send(false);
					}
				}
			}
		}
	}

	/*
     * Method:      AutoResponder::_CheckCaptcha
     * Parameters:  void
     * Returns:     void 
     * Description: 
     *  Checks the type of CAPTCHA that has been processed.
     *  This method should only be called if autoresponse has been requested
     *  by the form (i.e. IsRequested returns true).
     */
	function    _CheckCaptcha()
	{
		global $SPECIAL_VALUES,$bReverseCaptchaCompleted;
		global $reCaptchaProcessor;

		//
		// only check for CAPTCHA once
		//
		if (!$this->_iCaptchaType) {
			//
			// check for full CAPTCHA attempt
			// first, check for reCaptcha
			//
			if (isset($reCaptchaProcessor) && $SPECIAL_VALUES["arverify"] !== "") {
				$this->_iCaptchaType = $this->_iFull;
				$s_error             = '';
				if ($reCaptchaProcessor->Check($SPECIAL_VALUES["arverify"],$SPECIAL_VALUES,$s_error)) {
					$this->_bCaptchaOK = true;
				} else {
					$this->_bCaptchaOK = false;
					//
					// report the error
					//
					WriteARLog($this->_sTo,$this->_sSubject,
					           GetMessage(MSG_LOG_RECAPTCHA,array("ERR" => $s_error),false));
					UserError("recaptcha",GetMessage(MSG_RECAPTCHA_MATCH,array("ERR" => $s_error)));
				}
			}
			//
			// now check for our verifyimg or Captcha Creator
			//
			elseif ($SPECIAL_VALUES["arverify"] !== "") {
				//
				// allow spaces in the user's input, except for reCaptcha
				//
				$s_arverify          = str_replace(" ","",$SPECIAL_VALUES["arverify"]);
				$this->_iCaptchaType = $this->_iFull;
				//
				// full CAPTCHA has been attempted
				// VerifyImgString is from Tectite's simple verifyimg.php CAPTCHA.
				// turing_string is from Captcha Creator
				//
				if (IsSetSession("VerifyImgString") || IsSetSession("turing_string")) {
					$b_match = false;
					//
					// the user's entry must match the value in the session
					//
					if (IsSetSession("VerifyImgString")) {
						if (strtoupper($s_arverify) === strtoupper(GetSession("VerifyImgString"))) {
							$b_match = true;
						}
					} else {
						if (strtoupper($s_arverify) === strtoupper(GetSession("turing_string"))) {
							$b_match = true;
						}
					}
					if ($b_match) {
						$this->_bCaptchaOK = true;
					} else {
						WriteARLog($this->_sTo,$this->_sSubject,
						           GetMessage(MSG_LOG_NO_MATCH,array(),false));
						UserError("ar_verify",GetMessage(MSG_ARESP_NO_MATCH));
					}
				} else {
					//
					// ...and it has failed because there's no session data
					//
					WriteARLog($this->_sTo,$this->_sSubject,
					           GetMessage(MSG_LOG_NO_VERIMG,array(),false));
					ErrorWithIgnore("verify_failed",GetMessage(MSG_ARESP_NO_AUTH),true);
				}
			} elseif (Settings::get('ENABLE_ATTACK_DETECTION') &&
			          !Settings::isEmpty('ATTACK_DETECTION_REVERSE_CAPTCHA')
			) {
				//
				// Reverse CAPTCHA has been configured
				//
				$this->_iCaptchaType = $this->_iRev;
				$this->_bCaptchaOK   = $bReverseCaptchaCompleted;
			}
		}
	}

	/*
     * Method:      AutoResponder::_Send
     * Parameters:  $b_use_template  if true, allow template, otherwise file
     * Returns:     void
     * Description: 
     *  Sends an autoreponse using a template.
     */
	function    _Send($b_use_template)
	{
		global $SPECIAL_VALUES;
		//
		// declare some global vars for the hook system calls below
		//
		global $aFieldOrder,$aCleanedValues,$aRawDataValues,$aAllRawValues,$aFileVars;

		FMDebug("Sending auto response: " . ($b_use_template ? "template" : "plain"));

		//
		// Hook system: before sending auto response
		//
		if (!Settings::isEmpty('HOOK_DIR')) {
			if (!@include(Settings::get('HOOK_DIR') . "/fmhookprearesp.inc.php")) {
				@include(Settings::get('HOOK_DIR') . "/fmhookprearesp.inc");
			}
		}
		if (!$this->_SendEmail($this->_sTo,$this->_sSubject,$aRawDataValues,$b_use_template)) {
			WriteARLog($this->_sTo,$this->_sSubject,
			           GetMessage(MSG_LOG_FAILED,array(),false));
			SendAlert(GetMessage(MSG_ARESP_FAILED));
		} else {
			WriteARLog($this->_sTo,$this->_sSubject,
			           GetMessage(MSG_LOG_OK,array(),false));
			//
			// Hook system: after sending auto response
			//
			if (!Settings::isEmpty('HOOK_DIR')) {
				if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostaresp.inc.php")) {
					@include(Settings::get('HOOK_DIR') . "/fmhookpostaresp.inc");
				}
			}
		}
	}

	/*
     * Method:      AutoResponder::_SendEmail
     * Parameters:  $s_to       the email address to send to
     *              $s_subj     the subject line
     *              $a_values   field values to access
     *              $b_use_template if true, use a template, otherwise plain file
     * Returns:     bool        true on success, otherwise false
     * Description: 
     *  Sends an autoresponse email to the user.
     */
	function _SendEmail($s_to,$s_subj,$a_values,$b_use_template)
	{
		global $SPECIAL_VALUES;

		$a_headers   = array();
		$s_mail_text = "";
		$s_from_addr = GetAROption("FromAddr");

		if (!isset($s_from_addr)) {
			$s_from_addr = "";
			if (!Settings::isEmpty('FROM_USER')) {
				if (Settings::get('FROM_USER') != "NONE") {
					$s_from_addr = Settings::get('FROM_USER');
				}
			} else {
				global $SERVER;

				$s_from_addr = "FormMail@" . $SERVER;
			}
		} else {
			$s_from_addr = UnMangle($s_from_addr);
		}

		if (!empty($s_from_addr)) {
			$a_headers['From'] = SafeHeader($s_from_addr);
		}

		$s_type = "";
		if ($b_use_template) {
			if (IsAROptionSet('PlainTemplate')) {
				$s_type .= "PlainTemplate ";
				$s_template = GetAROption("PlainTemplate");
				if (!ProcessTemplate($s_template,$a_lines,$a_values,
				                     GetAROption('TemplateMissing'),
				                     'SubstituteValuePlain')
				) {
					return (false);
				}
				FMDebug("AutoRespond: PlainTemplate " . count($a_lines) . " lines");
				$s_mail_text = implode(Settings::get('BODY_LF'),$a_lines);
			}
			if (IsAROptionSet("HTMLTemplate")) {
				$s_type .= "HTMLTemplate ";
				if (!MakeMimeMail($s_mail_text,$a_headers,$a_values,
				                  GetAROption("HTMLTemplate"),
				                  GetAROption('TemplateMissing'))
				) {
					return (false);
				}
				FMDebug("AutoRespond: HTMLTemplate " . strlen($s_mail_text) . " bytes");
			}
		} else {

			if (IsAROptionSet('PlainFile')) {
				$s_type .= "PlainFile ";
				//
				// load the plain text file from the templates area
				//
				if (Settings::isEmpty('TEMPLATEDIR') && Settings::isEmpty('TEMPLATEURL')) {
					SendAlert(GetMessage(MSG_TEMPLATES));
					return (false);
				}
				$s_file = GetAROption("PlainFile");
				if (($a_lines = LoadTemplate($s_file,Settings::get('TEMPLATEDIR'),Settings::get('TEMPLATEURL'),
				                             true)) === false
				) {
					return (false);
				}
				$s_mail_text = implode(Settings::get('BODY_LF'),$a_lines);
				FMDebug("AutoRespond: PlainFile " . count($a_lines) . " lines");
			}
			if (IsAROptionSet("HTMLFile")) {
				$s_type .= "HTMLFile ";
				if (!MakeMimeMail($s_mail_text,$a_headers,$a_values,
				                  GetAROption("HTMLFile"),"",
				                  false,"",array(),array(),false)
				) {
					return (false);
				}
				FMDebug("AutoRespond: HTMLTemplate " . strlen($s_mail_text) . " bytes");
			}
		}
		if (strlen($s_mail_text) == 0) {
			SendAlert(GetMessage(MSG_ARESP_EMPTY),array("TYPE" => $s_type));
		}
		FMDebug("AutoRespond: message is " . strlen($s_mail_text) . " bytes");
		return (SendCheckedMail($s_to,$s_subj,$s_mail_text,$s_from_addr,$a_headers));
	}
}

/* 
 * Class:       SessionAccess
 * Description:     
 *  Implements access to the general PHP session.
 *  This provides a secure way of copy data to and from the
 *  user's PHP session.
 */

class   SessionAccess
{
	var $_aAccessList;

	/*
     * Method:      SessionAccess ctor
     * Parameters:  $a_access_list      list of variables that can be accessed
     * Returns:     n/a
     * Description: 
     *  Constructs the object.
     */
	function    SessionAccess($a_access_list)
	{
		$this->_aAccessList = $a_access_list;
	}

	/*
     * Method:      SessionAccess::CopyIn
     * Parameters:  $a_vars     reference to an array of values (keyed on name)
     *              $b_overwrite_empty  if true, the session value will overwrite
     *                          an empty array variable
     * Returns:     int         number of values copied
     * Description: 
     *  Copies in the list of variables from the session to the given array.
     */
	function    CopyIn(&$a_vars,$b_overwrite_empty)
	{
		//$s_db = "Session CopyIn:\n";
		$n_copied = 0;
		foreach ($this->_aAccessList as $s_var_name) {
			if (IsSetSession($s_var_name)) {
				if (!isset($a_vars[$s_var_name]) ||
				    ($b_overwrite_empty &&
				     FieldManager::IsEmpty($a_vars[$s_var_name]))
				) {
					$a_vars[$s_var_name] = GetSession($s_var_name);
					//$s_db .= "$s_var_name='".$a_vars[$s_var_name]."'\n";
					$n_copied++;
				}
			}
		};
		//SendAlert($s_db);
		return ($n_copied);
	}

	/*
     * Method:      SessionAccess::CopyOut
     * Parameters:  $a_vars     reference to an array of values (keyed on name)
     *              $a_fields   an array of fields to copy
     * Returns:     int         number of values copied
     * Description: 
     *  Copies the variables from the given array into the session.
     *  The list of fields to copy is specified in _aAccessList.
     *  If $a_fields is provided, it contains a list of fields to copy, which
     *  limits the fields selected from _aAccessList, otherwise all fields
     *  listed in _aAccessList are copied.
     */
	function    CopyOut(&$a_vars,$a_fields = array())
	{
		//$s_db = "Session CopyOut:\n";

		$n_copied = 0;
		foreach ($this->_aAccessList as $s_var_name) {
			if (isset($a_vars[$s_var_name])) {
				if (empty($a_fields) || in_array($s_var_name,$a_fields)) {
					SetSession($s_var_name,$a_vars[$s_var_name]);
					//$s_db .= "$s_var_name='".GetSession($s_var_name)."'\n";
					$n_copied++;
				}
			}
		};
		//SendAlert($s_db);
		return ($n_copied);
	}
}

$SessionAccessor = new SessionAccess(Settings::get('SESSION_ACCESS'));

$bAdvTemplates = false;
if (Settings::get('ADVANCED_TEMPLATES') &&
    (!Settings::isEmpty('TEMPLATEDIR') || !Settings::isEmpty('TEMPLATEURL') ||
     !Settings::isEmpty('MULTIFORMDIR') || !Settings::isEmpty('MULTIFORMURL'))
) {
	$bAdvTemplates = true;
}

if (isset($aGetVars["return"]) && is_numeric($aGetVars["return"])) {
	//
	// if advanced templates are in use, we will need them for
	// performing a multi-page form return
	//
	if ($bAdvTemplates) {
		$FMCTEMPLATE_PROC = true;
		if (!include_once("$MODULEDIR/$FMCOMPUTE")) {
			Error("load_fmcompute",GetMessage(MSG_LOAD_FMCOMPUTE,
			                                  array("FILE"  => "$MODULEDIR/$FMCOMPUTE",
			                                        "ERROR" => $php_errormsg
			                                  )),false,false);
		}
	}
	MultiFormReturn($aGetVars["return"]);
	//
	// Hook system: after multi-page return to form output
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostreturnform.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookpostreturnform.inc");
		}
	}
	exit;
}

//
// Hook system: after initialization
//
if (!Settings::isEmpty('HOOK_DIR')) {
	if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostinit.inc.php")) {
		@include(Settings::get('HOOK_DIR') . "/fmhookpostinit.inc");
	}
}

//
// check configuration values for potential security problems
//
CheckConfig();

//
// otherwise, do the real processing of FormMail
//
$aStrippedFormVars = $aAllRawValues = StripGPCArray($aFormVars);

if (Settings::get('ENABLE_ATTACK_DETECTION')) {
	DetectAttacks($aAllRawValues);
}

//
// Copy in configured session variables, overwriting any
// fields that are empty.
//
$SessionAccessor->CopyIn($aAllRawValues,true);
$SessionAccessor->CopyIn($aStrippedFormVars,true);

//
// process the options
//
ProcessMailOptions($aAllRawValues);
ProcessCRMOptions($aAllRawValues);
ProcessAROptions($aAllRawValues);
ProcessFilterOptions($aAllRawValues);

//
// create any derived fields
// NOTE: it's important that derivation occurs before cleaned values
// are created.  If derivation occurred after cleaning or it created
// fields that were assumed to be clean, then an attack could create
// forged email headers using derive_fields.  The code is safe with
// the logic sequence below.
//
$aAllRawValues = CreateDerived($aAllRawValues);

list($aFieldOrder,$aCleanedValues,$aRawDataValues) = ParseInput($aAllRawValues);

FilterFiles($aFileVars);

//
// if we're processing multi-forms, then merge in the raw data from previous
// forms unless this is the first page of the form sequence
//
if (IsSetSession("FormList") && $SPECIAL_VALUES["multi_start"] != 1) {
	list($aFieldOrder,$aCleanedValues,
		$aRawDataValues,$aAllRawValues,$aFileVars) = GetMultiValues(
		GetSession("FormList"),
		GetSession("FormIndex"),
		$aFieldOrder,$aCleanedValues,
		$aRawDataValues,$aAllRawValues,
		$aFileVars);
}

if ($SPECIAL_VALUES["file_names"] !== "") {
	list($aFieldOrder,$aCleanedValues,$aRawDataValues,$aAllRawValues,$aFileVars) =
		SetFileNames($SPECIAL_VALUES["file_names"],$aFieldOrder,
		             $aCleanedValues,$aRawDataValues,$aAllRawValues,$aFileVars);
}

//
// Hook system: after loading and processing data
//
if (!Settings::isEmpty('HOOK_DIR')) {
	if (!@include(Settings::get('HOOK_DIR') . "/fmhookload.inc.php")) {
		@include(Settings::get('HOOK_DIR') . "/fmhookload.inc");
	}
}

if (Settings::get('FORM_INI_FILE') !== "") {
	ProcessFormIniFile(Settings::get('FORM_INI_FILE'));
	//
	// Hook system: after processing INI file
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookinifile.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookinifile.inc");
		}
	}
}

$bDoneSomething = false;
if (Settings::get('DB_SEE_INPUT')) {
	/***
	 * echo "<pre>";
	 * print_r($aFormVars);
	 * print_r($aServerVars);
	 * echo "</pre>";
	 * exit;
	 ****/
	CreatePage(implode("\n",$FORMATTED_INPUT),"Debug Output - Fields Submitted");
	ZapSession();
	exit;
}

if (!empty($SPECIAL_VALUES["fmcompute"]) || $bAdvTemplates) {
	$FM_UserErrors = array();
	//
	// Generalized interface between FMCompute and FormMail functions
	//
	function FM_CallFunction($s_func,$a_params,&$m_return,
	                         &$s_mesg,&$a_debug,&$a_alerts)
	{
		switch ($s_func) {
			case "FMFatalError":
				SendComputeAlerts();
				if (count($a_params) < 3) {
					Error("fmcompute_call",GetMessage(MSG_CALL_PARAM_COUNT,
					                                  array("FUNC"  => $s_func,
					                                        "COUNT" => count($a_params)
					                                  )),
					      false,false);
				} else {
					Error("fmcompute_error",$a_params[0],$a_params[1],$a_params[2]);
				}
				break;

			case "FMFatalUserError":
				SendComputeAlerts();
				if (count($a_params) < 1) {
					Error("fmcompute_call",GetMessage(MSG_CALL_PARAM_COUNT,
					                                  array("FUNC"  => $s_func,
					                                        "COUNT" => count($a_params)
					                                  )),
					      false,false);
				} else {
					UserError("fmcompute_usererror",$a_params[0]);
				}
				break;

			case "FMUserError":
				if (count($a_params) < 1) {
					SendComputeAlerts();
					Error("fmcompute_call",GetMessage(MSG_CALL_PARAM_COUNT,
					                                  array("FUNC"  => $s_func,
					                                        "COUNT" => count($a_params)
					                                  )),
					      false,false);
				} else {
					global $FM_UserErrors;

					$FM_UserErrors[] = $a_params[0];
				}
				break;

			case "FMSaveAllFilesToRepository":
				if (count($a_params) != 0) {
					SendComputeAlerts();
					Error("fmcompute_call",GetMessage(MSG_CALL_PARAM_COUNT,
					                                  array("FUNC"  => $s_func,
					                                        "COUNT" => count($a_params)
					                                  )),
					      false,false);
				} else {
					$m_return = SaveAllFilesToRepository();
				}
				break;

			case "FMDeleteFileFromRepository":
				if (count($a_params) != 1) {
					SendComputeAlerts();
					Error("fmcompute_call",GetMessage(MSG_CALL_PARAM_COUNT,
					                                  array("FUNC"  => $s_func,
					                                        "COUNT" => count($a_params)
					                                  )),
					      false,false);
				} else {
					$m_return = DeleteFileFromRepository($a_params[0]);
				}
				break;

			case "FMNextNum":
				if (count($a_params) != 2) {
					SendComputeAlerts();
					Error("fmcompute_call",GetMessage(MSG_CALL_PARAM_COUNT,
					                                  array("FUNC"  => $s_func,
					                                        "COUNT" => count($a_params)
					                                  )),
					      false,false);
				} else {
					$i_pad  = $a_params[0];
					$i_base = $a_params[1];
					if ($i_base < 2 || $i_base > 36) {
						Error("fmcompute_call",GetMessage(MSG_CALL_INVALID_PARAM,
						                                  array("FUNC"    => $s_func,
						                                        "PARAM"   => 2,
						                                        "CORRECT" => 2. . .36
						                                  )),
						      false,false);
					}
					$m_return = GetNextNum($i_pad,$i_base);
				}
				break;

			default:
				$s_mesg = GetMessage(MSG_CALL_UNK_FUNC,array("FUNC" => $s_func));
				return (false);
		}
		return (true);
	}

	//
	// register useful FormMail functions with FMCompute module
	//
	function RegisterFormMailFunctions(&$fmc)
	{
		//
		// Allows the user to call "Error" from within a computation
		//
		if (($s_msg = $fmc->RegisterExternalFunction("PHP","void",
		                                             "FMFatalError",
		                                             array("string","bool","bool"),
		                                             "FM_CallFunction")) !== true
		) {
			Error("fmcompute_reg",GetMessage(MSG_REG_FMCOMPUTE,
			                                 array("FUNC"  => "FMFatalError",
			                                       "ERROR" => $s_msg
			                                 )),false,false);
		}
		//
		// Allows the user to call "UserError" from within a computation
		//
		if (($s_msg = $fmc->RegisterExternalFunction("PHP","void",
		                                             "FMFatalUserError",
		                                             array("string"),
		                                             "FM_CallFunction")) !== true
		) {
			Error("fmcompute_reg",GetMessage(MSG_REG_FMCOMPUTE,
			                                 array("FUNC"  => "FMFatalUserError",
			                                       "ERROR" => $s_msg
			                                 )),false,false);
		}

		//
		// Allows the user to record an error to be displayed
		// from within a computation.
		//
		if (($s_msg = $fmc->RegisterExternalFunction("PHP","void",
		                                             "FMUserError",
		                                             array("string"),
		                                             "FM_CallFunction")) !== true
		) {
			Error("fmcompute_reg",GetMessage(MSG_REG_FMCOMPUTE,
			                                 array("FUNC"  => "FMUserError",
			                                       "ERROR" => $s_msg
			                                 )),false,false);
		}

		//
		// Saves files to the repository.
		//
		if (($s_msg = $fmc->RegisterExternalFunction("PHP","bool",
		                                             "FMSaveAllFilesToRepository",
		                                             array(),
		                                             "FM_CallFunction")) !== true
		) {
			Error("fmcompute_reg",GetMessage(MSG_REG_FMCOMPUTE,
			                                 array("FUNC"  => "FMSaveAllFilesToRepository",
			                                       "ERROR" => $s_msg
			                                 )),false,false);
		}

		//
		// Delete a file from the repository.
		//
		if (($s_msg = $fmc->RegisterExternalFunction("PHP","bool",
		                                             "FMDeleteFileFromRepository",
		                                             array("string"),
		                                             "FM_CallFunction")) !== true
		) {
			Error("fmcompute_reg",GetMessage(MSG_REG_FMCOMPUTE,
			                                 array("FUNC"  => "FMDeleteFileFromRepository",
			                                       "ERROR" => $s_msg
			                                 )),false,false);
		}

		//
		// Generate a Next Number
		//
		if (($s_msg = $fmc->RegisterExternalFunction("PHP","string",
		                                             "FMNextNum",
		                                             array("int","int"),
		                                             "FM_CallFunction")) !== true
		) {
			Error("fmcompute_reg",GetMessage(MSG_REG_FMCOMPUTE,
			                                 array("FUNC"  => "FMNextNum",
			                                       "ERROR" => $s_msg
			                                 )),false,false);
		}
	}

	//
	// load the fmcompute module
	//
	if (!empty($SPECIAL_VALUES["fmcompute"])) {
		$FMCOMPUTE_CLASS   = true;
		$FMCOMPUTE_NODEBUG = true;
	}
	if ($bAdvTemplates) {
		$FMCTEMPLATE_PROC = true;
	}
	if (!include_once("$MODULEDIR/$FMCOMPUTE")) {
		Error("load_fmcompute",GetMessage(MSG_LOAD_FMCOMPUTE,
		                                  array("FILE"  => "$MODULEDIR/$FMCOMPUTE",
		                                        "ERROR" => $php_errormsg
		                                  )),false,false);
	}
	if (!empty($SPECIAL_VALUES["fmcompute"])) {
		RegisterFormMailFunctions($FMCalc);

		//
		// if GeoIP support is specified, load that module now
		//
		if (!Settings::isEmpty('GEOIP_LIC')) {
			$FMMODULE_LOAD = true; // signal module load
			if (!include_once("$MODULEDIR/$FMGEOIP")) {
				Error("load_module",GetMessage(MSG_LOAD_MODULE,
				                               array("FILE"  => "$MODULEDIR/$FMGEOIP",
				                                     "ERROR" => $php_errormsg
				                               )),false,false);
			}
			//
			// load the license and register the module
			//
			$GeoIP = new FMGeoIP(Settings::get('GEOIP_LIC'));
			if (!$GeoIP->RegisterModule($FMCalc)) {
				Error("reg_module",GetMessage(MSG_REGISTER_MODULE,
				                              array("NAME"  => "FMGeoIP",
				                                    "ERROR" => $GeoIP->GetError()
				                              )),false,false);
			}
		}
	}
}

if (isset($SPECIAL_VALUES["multi_go_back"]) && !empty($SPECIAL_VALUES["multi_go_back"])) {
	if (!IsSetSession("FormList") || GetSession("FormIndex") == 0) {
		ErrorWithIgnore("go_back",GetMessage(MSG_GO_BACK),false,false);
	}
	MultiKeep(); // store any "multi_keep" data just submitted
	//
	// save back to the session any variables that have been specified in
	// multi_keep
	//
	if (isset($SPECIAL_VALUES["multi_keep"]) && !empty($SPECIAL_VALUES["multi_keep"])) {
		$SessionAccessor->CopyOut($aAllRawValues,$SPECIAL_VALUES["multi_keep"]);
	}
	MultiFormReturn(GetSession("FormIndex") - 1);
	//    echo "Form index = ".GetSession("FormIndex");
	//
	// Hook system: after multi-page return to form output
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostreturnform.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookpostreturnform.inc");
		}
	}
	exit;
}

//
// This is the check for spiders; I can't imagine a spider will
// ever use the POST method.
//
if ($bIsGetMethod && count($aFormVars) == 0) {
	if (!Settings::get('ALLOW_GET_METHOD') && $bHasGetData) {
		CreatePage(GetMessage(MSG_GET_DISALLOWED),GetMessage(MSG_FORM_ERROR));
	} else {
		CreatePage(GetMessage(MSG_NO_DATA_PAGE),GetMessage(MSG_FORM_ERROR));
	}
	ZapSession();
	exit;
}

//
// Hook system: before performing required and conditions etc.
//
if (!Settings::isEmpty('HOOK_DIR')) {
	if (!@include(Settings::get('HOOK_DIR') . "/fmhookprechecks.inc.php")) {
		@include(Settings::get('HOOK_DIR') . "/fmhookprechecks.inc");
	}
}

//
// check for required fields
//
if (!CheckRequired($SPECIAL_VALUES["required"],$aAllRawValues,$sMissing,$aMissingList)) {
	UserError("missing_fields",GetMessage(MSG_REQD_ERROR),$sMissing,$aMissingList);
}

//
// check complex conditions
//
if (!CheckConditions($SPECIAL_VALUES["conditions"],$aAllRawValues,$sMissing,$aMissingList)) {
	UserError("failed_conditions",GetMessage(MSG_COND_ERROR),$sMissing,$aMissingList);
}

//
// check CAPTCHA
//
CheckCaptchaSubmit();

//
// Hook system: after performing required and conditions etc.
//
if (!Settings::isEmpty('HOOK_DIR')) {
	if (!@include(Settings::get('HOOK_DIR') . "/fmhookchecks.inc.php")) {
		@include(Settings::get('HOOK_DIR') . "/fmhookchecks.inc");
	}
}

if (!empty($SPECIAL_VALUES["fmmodules"])) {
	$aModuleList   = TrimArray(explode(",",$SPECIAL_VALUES["fmmodules"]));
	$FMMODULE_LOAD = true; // signal module load
	foreach ($aModuleList as $sModule) {
		if (!include_once("$MODULEDIR/$sModule")) {
			Error("load_module",GetMessage(MSG_LOAD_MODULE,
			                               array("FILE"  => "$MODULEDIR/$sModule",
			                                     "ERROR" => $php_errormsg
			                               )),false,false);
		}
	}
}

if (!empty($SPECIAL_VALUES["fmcompute"])) {
	//
	// Callback function for preg_replace_callback to add line
	// numbers on each match
	//
	function AddLineNumbersCallback($a_matches)
	{
		global $iAddLineNumbersCounter;

		return (sprintf("%d:",++$iAddLineNumbersCounter) . $a_matches[0]);
	}

	//
	// Add line numbers to some code
	//
	function AddLineNumbers($s_code)
	{
		global $iAddLineNumbersCounter;

		$iAddLineNumbersCounter = 0;
		return (preg_replace_callback('/^/m','AddLineNumbersCallback',$s_code));
	}

	//
	// Load some more code into FMCalc
	//
	function Load($s_code)
	{
		global $FMCalc;

		$a_mesgs = array();
		// echo "Loading '$s_code'";
		if ($FMCalc->Parse($s_code,$a_mesgs) === false) {
			$s_msgs = "";
			foreach ($a_mesgs as $a_msg) {
				$s_msgs .= "Line " . $a_msg["LINE"];
				$s_msgs .= ", position " . $a_msg["CHAR"] . ": ";
				$s_msgs .= $a_msg["MSG"] . "\n";
			}
			Error("fmcompute_parse",GetMessage(MSG_COMP_PARSE,
			                                   array("CODE"   => AddLineNumbers($s_code),
			                                         "ERRORS" => $s_msgs
			                                   )),false,false);
		}
	}

	//
	// Send any alerts found in FMCalc
	//
	function SendComputeAlerts()
	{
		global $FMCalc;

		$a_alerts = $FMCalc->GetAlerts();
		if (count($a_alerts) > 0) {
			SendAlert(GetMessage(MSG_COMP_ALERT,
			                     array("ALERTS" => implode("\n",StripHTML($a_alerts)))));
		}
		$a_debug = $FMCalc->GetDebug();
		if (count($a_debug) > 0) {
			SendAlert(GetMessage(MSG_COMP_DEBUG,
			                     array("DEBUG" => implode("\n",StripHTML($a_debug)))));
		}
	}

	//
	// Perform the computations in FMCalc
	//
	function Compute(&$a_field_order,&$a_cleaned_values,&$a_raw_data_values,
	                 &$a_values)
	{
		global $FMCalc,$FM_UserErrors;

		$a_mesgs       = array();
		$FM_UserErrors = array();
		if (($a_flds = $FMCalc->Execute($a_mesgs)) !== false) {
			SendComputeAlerts();
			foreach ($a_flds as $s_name => $s_value) {
				$a_values[$s_name] = $s_value;
				ProcessField($s_name,$s_value,$a_field_order,
				             $a_cleaned_values,$a_raw_data_values);
			}
			if (count($FM_UserErrors) > 0) {
				UserError("fmcompute_usererrors",GetMessage(MSG_USER_ERRORS),
				          "",$FM_UserErrors);
			}
		} else {
			SendComputeAlerts();
			Error("fmcompute_exec",GetMessage(MSG_COMP_EXEC,
			                                  array("ERRORS" => implode("\n",$a_mesgs))),false,false);
		}
	}

	//
	// Register all fields; a future improvement should use a call-back
	// function so as not to copy all the data into the FMCompute object
	//
	function RegisterData($a_form_data,$a_file_vars)
	{
		global $FMCalc;

		foreach ($a_form_data as $s_name => $s_value) {
			if (isset($s_name) && isset($s_value)) {
				if (($s_msg = $FMCalc->RegisterExternalData("PHP","string",
				                                            $s_name,"c",$s_value)) !== true
				) {
					Error("fmcompute_regdata",GetMessage(MSG_COMP_REG_DATA,
					                                     array("NAME" => $s_name,"ERROR" => $s_msg)),false,false);
				}
			}
		}

		foreach ($a_file_vars as $s_fld_name => $a_file_spec) {
			if (IsUploadedFile($a_file_spec)) {
				if (isset($a_file_spec["new_name"])) {
					//
					// we ignore errors here, because name_of_ field often already
					// exists from the above loop
					//
					$FMCalc->RegisterExternalData("PHP","string",
					                              "name_of_" . $s_fld_name,"c",$a_file_spec["new_name"]);
				}
				$s_value = $a_file_spec["name"];
			} else {
				$s_value = "";
			}
			if (($s_msg = $FMCalc->RegisterExternalData("PHP","string",
			                                            $s_fld_name,"c",$s_value)) !== true
			) {
				Error("fmcompute_regdata",GetMessage(MSG_COMP_REG_DATA,
				                                     array("NAME" => $s_fld_name,"ERROR" => $s_msg)),false,false);
			}
		}
	}

	/* 
     * Function:    MergeFileArrays
     * Parameters:  $a_new_files    the list of files just submitted by the form
     *              $a_saved_files  the list of files that have been previously saved (can be NULL)
     * Returns:     array           a merged array
     * Description:     
     *  Intelligently merges two arrays of file definitions.
     *  If a file has been newly uploaded, its definition takes precedence.
     */
	function    MergeFileArrays($a_new_files,$a_saved_files)
	{
		if (isset($a_saved_files)) {
			foreach ($a_saved_files as $s_key => $a_def) {
				if (isset($a_new_files[$s_key])) {
					if (!IsUploadedFile($a_new_files[$s_key])) {
						$a_new_files[$s_key] = $a_def;
					}
				} else {
					$a_new_files[$s_key] = $a_def;
				}
			}
		}
		return ($a_new_files);
	}

	RegisterData($aAllRawValues,MergeFileArrays($aFileVars,IsSetSession("FormSavedFiles") ?
		GetSession("FormSavedFiles") : array()));
	//
	// parse all fmcompute fields
	//
	if (is_array($SPECIAL_VALUES["fmcompute"])) {
		$nCompute = count($SPECIAL_VALUES["fmcompute"]);
		for ($iCompute = 0 ; $iCompute < $nCompute ; $iCompute++) {
			Load($SPECIAL_VALUES["fmcompute"][$iCompute]);
		}
	} else {
		Load($SPECIAL_VALUES["fmcompute"]);
	}

	//
	// run computations
	//
	Compute($aFieldOrder,$aCleanedValues,$aRawDataValues,$aAllRawValues);
	//
	// Hook system: after computations
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookcompute.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookcompute.inc");
		}
	}
}

$bGotGoBack = $bGotNextForm = $bGotGoodTemplate = $bGotGoodUrl = false;
if (isset($SPECIAL_VALUES["good_url"]) &&
    !empty($SPECIAL_VALUES["good_url"])
) {
	$bGotGoodUrl = true;
}

if (isset($SPECIAL_VALUES["good_template"]) &&
    !empty($SPECIAL_VALUES["good_template"])
) {
	$bGotGoodTemplate = true;
}

if (isset($SPECIAL_VALUES["next_form"]) &&
    !empty($SPECIAL_VALUES["next_form"])
) {
	$bGotNextForm = true;
}

if (isset($SPECIAL_VALUES["multi_go_back"]) &&
    !empty($SPECIAL_VALUES["multi_go_back"])
) {
	$bGotGoBack = true;
}

//
// it's not valid to specify "next_form" as well as "good_url" or
// "good_template"; this is because it's ambiguous - do we go to the
// next form or the final 'thank you' page?
//
if ($bGotNextForm && ($bGotGoodTemplate || $bGotGoodUrl)) {
	ErrorWithIgnore("next_plus_good",GetMessage(MSG_NEXT_PLUS_GOOD,array("WHICH" =>
		                                                                     ($bGotGoodUrl ? "good_url" :
			                                                                     "good_template")
	)),false,false);
}

MultiFormLogic();

//
// Hook system: after multi-page form logic
//
if (!Settings::isEmpty('HOOK_DIR')) {
	if (!@include(Settings::get('HOOK_DIR') . "/fmhookmulti.inc.php")) {
		@include(Settings::get('HOOK_DIR') . "/fmhookmulti.inc");
	}
}

//
// write to the CSV database
//
if (!Settings::isEmpty('CSVDIR') && isset($SPECIAL_VALUES["csvfile"]) &&
    !empty($SPECIAL_VALUES["csvfile"])
) {
	//
	// Hook system: before writing CSV file
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookprecsv.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookprecsv.inc");
		}
	}
	WriteCSVFile(Settings::get('CSVDIR') . "/" . basename($SPECIAL_VALUES["csvfile"]),$aAllRawValues);
	//
	// Hook system: after writing CSV file
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostcsv.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookpostcsv.inc");
		}
	}
	$bDoneSomething = true;
}

//
// write to the log file
//
if (!Settings::isEmpty('LOGDIR') && isset($SPECIAL_VALUES["logfile"]) && !empty($SPECIAL_VALUES["logfile"])) {
	//
	// Hook system: before writing log file
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookprelog.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookprelog.inc");
		}
	}
	WriteLog(Settings::get('LOGDIR') . "/" . basename($SPECIAL_VALUES["logfile"]));
	//
	// Hook system: after writing log file
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostlog.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookpostlog.inc");
		}
	}
	$bDoneSomething = true;
}

//
// send to the CRM
//
if (isset($SPECIAL_VALUES["crm_url"]) && isset($SPECIAL_VALUES["crm_spec"]) &&
    !empty($SPECIAL_VALUES["crm_url"]) && !empty($SPECIAL_VALUES["crm_spec"])
) {
	$sCRM = GetCRMURL($SPECIAL_VALUES["crm_spec"],$aAllRawValues,$SPECIAL_VALUES["crm_url"]);
	if (!empty($sCRM)) {
		$aCRMReturnData = array();
		//
		// Hook system: before sending to CRM
		//
		if (!Settings::isEmpty('HOOK_DIR')) {
			if (!@include(Settings::get('HOOK_DIR') . "/fmhookprecrm.inc.php")) {
				@include(Settings::get('HOOK_DIR') . "/fmhookprecrm.inc");
			}
		}
		if (!SendToCRM($sCRM,$aCRMReturnData)) {
			//
			// CRM interface failed, check if the form wants an error
			// displayed
			//
			if (IsCRMOptionSet("ErrorOnFail")) {
				Error("crm_failed",GetMessage(MSG_CRM_FAILURE,
				                              array("URL"  => $sCRM,
				                                    "DATA" => GetObjectAsString($aCRMReturnData)
				                              )),
				      false,false);
			}
		} else
			//
			// append the returned data to the raw data values of the form
			//
		{
			$aRawDataValues = array_merge($aRawDataValues,$aCRMReturnData);
		}
		//
		// Hook system: after sending to CRM
		//
		if (!Settings::isEmpty('HOOK_DIR')) {
			if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostcrm.inc.php")) {
				@include(Settings::get('HOOK_DIR') . "/fmhookpostcrm.inc");
			}
		}
		$bDoneSomething = true;
	}
}

//
// Check obsolete SendMailFOption
//
if (IsMailOptionSet("SendMailFOption")) {
	SendAlert(GetMessage(MSG_FOPTION_WARN,array("LINE" => Settings::get('SENDMAIL_F_OPTION_LINE'))),
	          false,true);
}

$AutoResp = new AutoResponder();
//
// check for autoresponse problems
//
$AutoResp->Process(true);

//
// Hook system: before completion
//
if (!Settings::isEmpty('HOOK_DIR')) {
	if (!@include(Settings::get('HOOK_DIR') . "/fmhookprecomplete.inc.php")) {
		@include(Settings::get('HOOK_DIR') . "/fmhookprecomplete.inc");
	}
}

//
// send email
//
if (!isset($SPECIAL_VALUES["recipients"]) || empty($SPECIAL_VALUES["recipients"])) {
	//
	// No recipients - don't email anyone...
	// If nothing has been done above (CSV, logging, or CRM),
	// then report an error.
	//
	if (!$bDoneSomething) {
		if (!$bGotGoBack && !$bGotNextForm) {
			ErrorWithIgnore("no_recipients",GetMessage(MSG_NO_ACTIONS));
		}
	}
} else {
	//
	// Hook system: before sending emails
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookpreemail.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookpreemail.inc");
		}
	}
	$s_invalid = $s_invalid_cc = $s_invalid_bcc = "";
	if (!CheckEmailAddress($SPECIAL_VALUES["recipients"],$s_valid_recipients,$s_invalid)) {
		ErrorWithIgnore("no_valid_recipients",GetMessage(MSG_NO_RECIP));
	} else {
		$s_valid_cc = $s_valid_bcc = "";

		//
		// check CC and BCC addresses
		//
		if (isset($SPECIAL_VALUES["cc"]) && !empty($SPECIAL_VALUES["cc"])) {
			CheckEmailAddress($SPECIAL_VALUES["cc"],$s_valid_cc,$s_invalid_cc);
		}
		if (isset($SPECIAL_VALUES["bcc"]) && !empty($SPECIAL_VALUES["bcc"])) {
			CheckEmailAddress($SPECIAL_VALUES["bcc"],$s_valid_bcc,$s_invalid_bcc);
		}

		//
		// send an alert for invalid addresses
		//
		$s_error = "";
		if (!empty($s_invalid)) {
			$s_error .= "recipients: $s_invalid\r\n";
		}
		if (!empty($s_invalid_cc)) {
			$s_error .= "cc: $s_invalid_cc\r\n";
		}
		if (!empty($s_invalid_bcc)) {
			$s_error .= "bcc: $s_invalid_bcc\r\n";
		}
		if (!empty($s_error)) {
			SendAlert(GetMessage(MSG_INV_EMAIL,array("ERRORS" => $s_error)));
		}

		//
		// send the actual results
		//
		if (!SendResults($aFieldOrder,$aCleanedValues,$s_valid_recipients,$s_valid_cc,
		                 $s_valid_bcc,$aRawDataValues)
		) {
			Error("mail_failed",GetMessage(MSG_FAILED_SEND));
		}
		//
		// Hook system: after sending emails
		//
		if (!Settings::isEmpty('HOOK_DIR')) {
			if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostemail.inc.php")) {
				@include(Settings::get('HOOK_DIR') . "/fmhookpostemail.inc");
			}
		}
	}
}

//
// Process autoresponse
//
$AutoResp->Process();

$SessionAccessor->CopyOut($aAllRawValues);
//
// multi-form processing
//
if ($bGotNextForm) {
	OutputMultiFormTemplate($SPECIAL_VALUES["next_form"],$aRawDataValues);
	//    echo "Form index = ".GetSession("FormIndex");
	//
	// Hook system: after multi-page next form output
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostnextform.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookpostnextform.inc");
		}
	}
} else {
	//
	// Hook system: before finishing
	//
	if (!Settings::isEmpty('HOOK_DIR')) {
		if (!@include(Settings::get('HOOK_DIR') . "/fmhookprefinish.inc.php")) {
			@include(Settings::get('HOOK_DIR') . "/fmhookprefinish.inc");
		}
	}
	//
	// redirect to the good URL page, or create a default page;
	// we're no longer processing a multi-page form sequence
	//
	UnsetSession("FormList");
	UnsetSession("FormIndex");
	UnsetSession("FormKeep");
	if (!$bGotGoodUrl) {
		if (IsAjax()) {
			JSON_Result("OK");
		} else {
			if ($bGotGoodTemplate) {
				OutputTemplate($SPECIAL_VALUES["good_template"],$aRawDataValues);
			} else {
				CreatePage(GetMessage(MSG_THANKS_PAGE),GetMessage(MSG_FORM_OK));
			}
		}
		//
		// Hook system: after finishing (before session is cleared)
		//
		if (!Settings::isEmpty('HOOK_DIR')) {
			if (!@include(Settings::get('HOOK_DIR') . "/fmhookpostfinish.inc.php")) {
				@include(Settings::get('HOOK_DIR') . "/fmhookpostfinish.inc");
			}
		}
		//
		// everything's good, so we don't need the session any more
		//
		ZapSession();
	} elseif (IsAjax()) {
		JSON_Result("OK");
	} else

		//
		// Note that a redirect terminates the script and prevents
		// the processing of fmhookpostfinish.inc.  Also, the session
		// is left intact (which the good_url can use).
		//
	{
		Redirect($SPECIAL_VALUES["good_url"],GetMessage(MSG_FORM_OK));
	}
}
