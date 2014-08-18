<?php /* public/media/audio/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'media/audio/index.php');
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
	$sub_title='Spotlight Audio';

	# Set the meta discription for this page.
	$meta_desc='Audio featured on '.DOMAIN_NAME.'!';

	# Instantiate a new Media object.
	$media=new Media();

	# Instantiate the new Audio object.
	$audio_obj=$media->getAudioObject();

	# Display audio
	$display=$audio_obj->displayAudioFeed();

	if($display=='<h3>There is no audio to display.</h3>')
	{
		$display='<div class="main-1"></div>'.
			'<div class="main-2">'.
				'There is no audio to display.'.
			'</div>'.
			'<div class="main-3"></div>';
	}

	# Instantiate a new Slideshow object.
	$slideshow=Slideshow::getInstance();
	$slideshow->setSelector('.audio-feed-list');
	$slideshow->setVertical('true');
	$slideshow->setStart(0);

	# Do we need some more CSS?
	$doc->setStyle(THEME.'css/media.css');
	# Do we need some JavaScripts? (Use the script file name before the ".js".)
	$doc->setJavaScripts('jCarouselLite,audio');
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