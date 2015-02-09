<?php /* public/media/videos/index.php */

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
$meta_desc='Video featured on '.DOMAIN_NAME;
$page_class='videopage';

# Instantiate a new Media object.
$media=new Media();
# Instantiate the new Video object.
$video_obj=$media->getVideoObject();
# Create playlist menu. This will be used in the videos_nav template.
$playlist_items=$video_obj->createPlaylistMenu();
# Get the video feed and set it to a variable for display.
$video_feed=$video_obj->displayVideoFeed();

# Instantiate a new Slideshow object.
$slideshow=Slideshow::getInstance();
$slideshow->setSelector('.video-feed-list');
$slideshow->setVertical('true');
$slideshow->setStart(0);

# Sub title of the page.
$main_content->setSubTitle('Spotlight Videos');

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add the video feed to main-2.
$display_main2.=$video_feed;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Get the videos navigation.
require Utility::locateFile(TEMPLATES.'videos_nav.php');
# Set the "videos_nav" variable from the videos_nav template to the display_box2 variable for display in the view.
$display_box2.=$videos_nav;

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
require Utility::locateFile(TEMPLATES.'page.php');