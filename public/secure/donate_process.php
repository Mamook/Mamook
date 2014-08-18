<?php /* public/secure/donate_process.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/donate_process.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../settings.php';
	# Get the PayPal Class.
	require_once MODULES.'PayPal'.DS.'CustomPayPal.php';

	# Instantiate a new Document object.
	$paypal=new CustomPayPal();

	# Process!
	$paypal->processPayPal(FALSE, array('donation'=>TRUE), ACCOUNTING_EMAIL);
	//$paypal->processPayPal(FALSE, array('donation'=>TRUE), ADMIN_EMAIL);
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