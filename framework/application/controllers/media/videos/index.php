<?php /* framework/application/controllers/media/videos/index.php */

# Get the Playlist class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Playlist.php');
# Get the Slideshow Class.
require_once Utility::locateFile(MODULES.'Document'.DS.'Slideshow.php');
# Get the Video Class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$videos_nav='';

# Set the meta discription for this page.
$meta_desc='Video featured on '.DOMAIN_NAME;
$page_class='videopage';
# Set the page subtitle.
$main_content->setSubTitle('Spotlight Videos');

# Instantiate a new Playlist object.
$playlist_obj=Playlist::getInstance();
# Instantiate a new Video object.
$video_obj=Video::getInstance();

# Get the video feed and set it to a variable for display.
$video_feed=$video_obj->displayVideoFeed();

# Decode the returned API JSON.
$content_api_decode=json_decode($main_content->getAPI(), TRUE);
# Asign playlists to a variable.
$content_playlists=$content_api_decode['Site']['Playlists'];
if($content_playlists!==NULL)
{
	# Creates the SQL from an array of Playlist IDs.
	$playlist_obj->createWhereSQL($content_playlists);
	# Get the playlists from the `playlists` table.
	$playlist_obj->getPlaylists(NULL, '`id`, `name`, `api`', 'name', 'ASC', ' WHERE '.$playlist_obj->getWhereSQL());
	# Set the playlists to a variable.
	$playlists=$playlist_obj->getAllPlaylists();
	# Create playlist menu. This will be used in the videos_nav template.
	$playlist_items=$video_obj->createPlaylistMenu($playlists);
	# Check if there are any playlists to display in the videos nav.
	if(!empty($playlist_items))
	{
		# Get the videos navigation.
		require Utility::locateFile(TEMPLATES.'videos_nav.php');
	}
}
# If this page is a playlist.
if(isset($_GET['playlist']))
{
	# Get this playlist information from the `playlist` table.
	$playlist_obj->getThisPlaylist($_GET['playlist']);
	# Set the subtitle to the title of the video.
	$main_content->setSubTitle($playlist_obj->getName());
}

# Instantiate a new Slideshow object.
$slideshow=Slideshow::getInstance();
$slideshow->setSelector('.feed_list-video');
$slideshow->setVertical('true');
$slideshow->setStart(0);

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add the video feed to main-2.
$display_main2.=$video_feed;
# Set the "videos_nav" variable from the videos_nav template to the display_box2 variable for display in the view.
$display_box2.=$videos_nav;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Add additional CSS documents.
$doc->setStyle('media');
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