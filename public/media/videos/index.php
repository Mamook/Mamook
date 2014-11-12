<?php /* public/media/videos/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'media/videos/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../settings.php';

	# Get the Media Class.
	require_once MODULES.'Media'.DS.'Media.php';
	# Get the Slideshow Class.
	require_once MODULES.'Document'.DS.'Slideshow.php';

	# Sub title of the page.
	$main_content->setSubTitle('Spotlight Videos');

	# Instantiate a new Media object.
	$media=new Media();
	# Instantiate the new Video object.
	$video_obj=$media->getVideoObject();
	# Create playlist menu
	$playlist_items=$video_obj->createPlaylistMenu();
	# Display videos
	$display=$video_obj->displayVideoFeed();

	# Instantiate a new Slideshow object.
	$slideshow=Slideshow::getInstance();
	//$slideshow->setButtonNext('NextVideo');
	//$slideshow->setButtonPrevious('PreviousVideo');
	$slideshow->setSelector('.video-feed-list');
	$slideshow->setVertical('true');
	$slideshow->setStart(0);

	# Get the videos navigation.
	require TEMPLATES.'videos_nav.php';

	# Do we need some more CSS?
	$doc->setStyle(THEME.'css/media.css');
	# Do we need some JavaScripts? (Use the script file name before the ".js".)
	$doc->setJavaScripts('jCarouselLite');
	# Do we need some JavaScripts in the footer? (Use the script file name before the ".php".)
	$doc->setFooterJS('jCarouselLite-call,media');

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