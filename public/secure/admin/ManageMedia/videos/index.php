<?php /* public/secure/admin/ManageMedia/videos/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageMedia/videos/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../../../settings.php';
	# Get the Video Class.
	require_once MODULES.'Media'.DS.'Video.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the VideoFormProcessor Class.
	require_once MODULES.'Form'.DS.'VideoFormProcessor.php';
	# Get the PageNavigator Class.
	require_once MODULES.DS.'PageNavigator/PageNavigator.php';

	$login->checkLogin(ALL_BRANCH_USERS);

	$login->findUserData();

	$display='';
	$head='';
	$video_nav='';

	# Check if YouTube has been set up. (There won't be a value for YOUTUBE_CLIENT_ID if it hasn't.)
	if(YOUTUBE_CLIENT_ID!=='')
	{
		$form_processor=new VideoFormProcessor();

		# Get the video form template.
		require TEMPLATES.'forms'.DS.'video_form.php';
	}
	else
	{
		$display='<div>Video has not been set up for this site yet. Please <a href="'.APPLICATION_URL.'webSupport/" title="Send an email to the Webmaster">contact the Webmaster</a> to set it up.</div>';
	}

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