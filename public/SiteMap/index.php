<?php /* public/SiteMap/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'SiteMap/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../settings.php';

	# Get SiteMap class.
	require_once MODULES.'SiteMap'.DS.'SiteMap.php';

	# Create a new SubContent object
	$site_map=new SiteMap();

	# Do we need some more CSS?
	$doc->setStyle(THEME.'css/sitemap.css');
	# Do we need some javascripts? (Use the script file name before the ".js".)
	$doc->setJavaScripts('sitemap');

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