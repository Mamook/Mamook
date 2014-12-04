<?php /* public/media/audio/index.php */

# Get the Media Class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Media.php');
# Get the Slideshow Class.
require_once Utility::locateFile(MODULES.'Document'.DS.'Slideshow.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

# Set the meta discription for this page.
$meta_desc='Audio featured on '.DOMAIN_NAME;
$page_class='audiopage';

# Instantiate a new Media object.
$media=new Media();

# Instantiate the new Audio object.
$audio_obj=$media->getAudioObject();

# Get the audio feed and set it to a variable for display.
$audio_feed=$audio_obj->displayAudioFeed();

# Instantiate a new Slideshow object.
$slideshow=Slideshow::getInstance();
$slideshow->setSelector('.audio-feed-list');
$slideshow->setVertical('true');
$slideshow->setStart(0);

# Sub title of the page.
$main_content->setSubTitle('Spotlight Audio');

# Get the page title and subtitle to display in main-1.
$display_main1=$main_content->displayTitles();

# Get the main content to display in main-2. The "image_link" variable is defined in data/init.php.
$display_main2=$main_content->displayContent($image_link);
# Add the audio feed to main-2.
$display_main2.=$audio_feed;

# Get the quote text to display in main-3.
$display_main3=$main_content->displayQuote();

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
require Utility::locateFile(TEMPLATES.'page.php');