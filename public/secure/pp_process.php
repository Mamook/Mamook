<?php /* public/secure/pp_process.php paypal processing script */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/pp_process.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../settings.php';
	# Get the PayPal Class.
	require_once MODULES.'PayPal'.DS.'PayPal.php';

	# Instantiate a new Paypal object.
	$paypal=new PayPal();

	# Process!
	$paypal->processPayPal(TRUE, NULL, array(ACCOUNTING_EMAIL, ADMIN_EMAIL));

	if($_SERVER['REQUEST_METHOD']!='POST')
	{
		$doc->redirect(DEFAULT_REDIRECT);
	}
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.