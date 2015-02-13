<?php /* public/secure/admin/ManageMedia/audio/index.php */

# Get the Audio Class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the AudioFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'AudioFormProcessor.php');
# Get the PageNavigator Class.
require_once Utility::locateFile(MODULES.'PageNavigator'.DS.'PageNavigator.php');

$login->checkLogin(ALL_BRANCH_USERS);

$login->findUserData();

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';
$head='';

$form_processor=new AudioFormProcessor();

require Utility::locateFile(TEMPLATES.'forms'.DS.'audio_form.php');

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add any display content to main-2.
$display_main2.=$display;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

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
require Utility::locateFile(TEMPLATES.'page.php');