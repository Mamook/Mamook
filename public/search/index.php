<?php /* public/search/index.php */

ob_start(); # Begin output buffering

# Increase the allowed PHP execution time for large searches. (300 seconds = 5 minutes)
ini_set('max_execution_time', 300);

try
{
	# Define the location of this page.
	define('HERE_PATH', 'search/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../settings.php';

	# Get the Controller.
	require_once Utility::locateFile(CONTROLLERS.'search'.DS.'index.php');
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.