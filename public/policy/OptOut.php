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
	# Get the FormProcessor Class.
	require_once MODULES.'Form'.DS.'FormProcessor.php';

	$address='';
	$good_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?success=yes';
	$bad_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?error=true';
	$head='<h3>You may use the form below to send us an email.</h3>';

	if(isset($_GET['success']) && ($_GET['success']=='yes'))
	{
		$doc->setError('Thank you! We\'ll be in contact with you soon.');
	}

	if(isset($_GET['error']) && ($_GET['error']=='true'))
	{
		$doc->setError('<h3>There was an error sending you\'re email...</h3>
		Please make sure you entered your name and a valid email address. If it still isn\'t working, rest assured that the webmaster has received an email and will work out the issue as soon as possible. You may try again later. Thank you.');
	}

	# Instantiate a new FormProcessor object.
	$fp=new FormProcessor();
	$fp->setFormAction(APPLICATION_URL.WebUtility::removeIndex(HERE));
	$fp->setUpload(FALSE);
	# Get the email form default values.
	require TEMPLATES.'forms'.DS.'email_form_defaults.php';
	# Process the email form.
	$send_to_formmail=$fp->processEmail($default_data);
	# Instantiate a new Email object.
	$email=$fp->getEmail();
	$email->setRecipients('Privacy');

	# Get the form mail template.
	require TEMPLATES.'forms'.DS.'email_form.php';

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