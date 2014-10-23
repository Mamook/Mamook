<?php /* public/secure/admin/ManageMedia/audio/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageMedia/audio/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../../../settings.php';
	# Get the Audio Class.
	require_once MODULES.'Media'.DS.'Audio.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the AudioFormProcessor Class.
	require_once MODULES.'Form'.DS.'AudioFormProcessor.php';
	# Get the PageNavigator Class.
	require_once MODULES.DS.'PageNavigator'.DS.'PageNavigator.php';

	$login->checkLogin(ALL_BRANCH_USERS);

	$login->findUserData();

	$display='';
	$head='';

	$form_processor=new AudioFormProcessor();

	require TEMPLATES.'forms'.DS.'audio_form.php';

	# Do we need some more CSS?
	$doc->setStyle(THEME.'css/media.css');

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