<?php /* public/test.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'test.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../settings.php';

	# Create display variables.
	$display_main1='';
	$display_main2='';
	$display_main3='';
	$display_box1a='';
	$display_box1b='';
	$display_box1c='';
	$display_box2='';

	$display='';

	# Get the TestLogin Class.
// 	require_once MODULES.'Test'.DS.'TestLogin.php';
// 	require_once MODULES.'Media'.DS.'Media.php';

	require_once MODULES.'Encryption'.DS.'Encryption.php';
	# Instantiate a new Encryption object.
	$encrypt=new Encryption(MYKEY);
// 	$encrypted_password=$encrypt->enCodeIt('IamHugh');
	$decrypted_password=$encrypt->deCodeIt('Enter Encrypted Password Here');
	$display=$decrypted_password;

	//$random=$login->randomString('alnum', 32);
// 	$display='That didn\'t really work now, did it?';
// 	$media=new Media();
// 	$yt->setClientID(GOOGLE_CLIENT_ID);
// 	$yt->setClientSecret(GOOGLE_CLIENT_SECRET);
// 	$yt->setAccessToken(GOOGLE_ACCESS_TOKEN);
// 	$yt->setRefreshToken(GOOGLE_REFRESH_TOKEN);
// 	$yt->setNextURL(APPLICATION_URL.HERE);
// 	if($yt->pullFeed('uploads')===TRUE)
// 	{
// 		$display='';
// 	}
// 	var_dump($yt->getFeed());exit;

	# Get the page title and subtitle to display in main-1.
	$display_main1=$main_content->displayTitles();

	# Get the main content to display in main-2. The "image_link" variable is defined in data/init.php.
	$display_main2=$main_content->displayContent($image_link);
	# Add any display content to main-2.
	$display_main2.=$display;

	# Get the quote text to display in main-3.
	$display_main3=$main_content->displayQuote();

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