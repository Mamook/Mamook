<?php /* public/announcement/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'announcement/index.php');
	/*
	 ** In settings we
	 ** define application settings
	 ** define system settings
	 ** start a new session
	 ** connect to the Database
	 */
	require_once '../../settings.php';
	# Get the SubContent Class.
	require_once MODULES.'Content'.DS.'SubContent.php';

	$branch='Announcement';
	$branch_nav='';
	$display_file='';

	# Create a new SubContent object.
	$subcontent=new SubContent();
	# Get the branch subcontent display and set it to a variable..
	$display=$subcontent->displayBranchSubContent($branch);
	# Set the page title to the post's title.
	$page_title=$subcontent->getPostTitleDisplay();
	if(!empty($page_title))
	{
		$main_content->setPageTitle($page_title);
	}

	# Get the announcement navigation list.
	require TEMPLATES.'announcement_nav.php';

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