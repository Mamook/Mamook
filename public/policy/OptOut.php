<?php /* public/policy/OptOut.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'policy/OptOut.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../settings.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the EmailFormProcessor Class.
	require_once MODULES.'Form'.DS.'EmailFormProcessor.php';

	# Get the email form default values.
	require TEMPLATES.'forms'.DS.'email_form_defaults.php';

	$address='';
	$display='';
	$get_query=GET_QUERY;
	$good_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?success=yes';
	$bad_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?error=true';
	$head='<h1>You may use the form below to send us an email.</h1>';

	### DEBUGGING ###
	if(DEBUG_APP===TRUE)
	{
		$recipients=ADMIN_EMAIL;
	}
	else
	{
		$recipients='Privacy';
	}

	if(isset($_GET['success']) && ($_GET['success']=='yes'))
	{
		$get_query='';
		$doc->setError("Thank you! We'll be in contact with you soon.");
	}
	if(isset($_GET['error']) && ($_GET['error']=='true'))
	{
		$get_query='';
		$doc->setError('<h3>There was an error sending your email...</h3>
		Please make sure you entered your name and a valid email address. If it still isn\'t working, rest assured that the webmaster has received an email and will work out the issue as soon as possible. You may try again later. Thank you.');
	}

	# Instantiate a new EmailFormProcessor object.
	$fp=new EmailFormProcessor();
	$fp->setFormAction(APPLICATION_URL.WebUtility::removeIndex(HERE).$get_query);
	$fp->setUpload(TRUE);

	if($main_content->getAddress1()!==NULL)
	{
		$address.='<h3>The '.$main_content->getSiteName().' mailing address is:</h3>';
		$address.='<p>'.$main_content->getAddress1().'<br />';
		$address.=(($main_content->getAddress2()!==NULL) ? $main_content->getAddress2().'<br />' : '');
		$address.=$main_content->getCity().', '.$main_content->getState().' '.$main_content->getZipcode().'</p>';
	}
	if($main_content->getPhone()!==NULL)
	{
		$address.='<h3>Our phone number is:</h3><p>USA '.$main_content->getPhone().'</p>';
	}

	# Get the form mail template.
	require TEMPLATES.'forms'.DS.'email_form.php';
	# Get the policy navigation.
	require TEMPLATES.'policy_nav.php';

	/*
	** In the page template we
	** get the header
	** get the masthead
	** get the subnavbar
	** get the navbar
	** get the page view
	** get the quick registration box
	** get the footer
	*/
	require TEMPLATES.'page.php';
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.