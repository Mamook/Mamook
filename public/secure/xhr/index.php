<?php /* public/secure/xhr/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/xhr/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../settings.php';
	# Get the Social Class.
	require_once MODULES.'Social'.DS.'Twitter.php';
	# Set the Twitter constructor params to an array.
	$params=array(
		'consumer_key'=>TWITTER_CONSUMER_KEY,
		'consumer_secret'=>TWITTER_CONSUMER_SECRET
	);
	$twitter=new Twitter($params);
	$twitter->TwitterCallback();
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.