<?php /* public/secure/PayPal.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/PayPal.php');
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

	if($login->isLoggedIn()!==TRUE)
	{
		$_SESSION['message']='Please <a href="'.REDIRECT_TO_LOGIN.'">login</a> to the site first. If you don\'t already have an account, please <a href="'.REDIRECT_TO_LOGIN.'register/">create an account</a>. Registering with '.DOMAIN_NAME.' is free and easy. Registered users have access to downloads and special content.';
		$doc->redirect(REDIRECT_TO_LOGIN);
	}
	# Instantiate a new Document object.
	$paypal=new PayPal();

	# Process!
	$paypal->redirectToPayPal();
	if(($_SERVER['REQUEST_METHOD']!='POST') || ($_SERVER['REQUEST_METHOD']!='GET'))
	{
		$doc->redirect(DEFAULT_REDIRECT);
	}
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.