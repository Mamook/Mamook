<?php /* public/secure/login/ResendEmail/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/login/ResendEmail/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../../settings.php';

	# Get the Controller.
	require_once Utility::locateFile(CONTROLLERS.'secure'.DS.'login'.DS.'ResendEmail'.DS.'index.php');
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.