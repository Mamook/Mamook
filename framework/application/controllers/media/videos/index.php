<?php /* framework/application/controllers/media/videos/index.php */

# Get the Category class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
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

$video_display='';
$videos_nav='';

# Set the meta discription for this page.
$meta_desc='Video featured on '.DOMAIN_NAME;
$page_class='videopage';

$large_video=NULL;
# Set the "Videos" playlist as the default.
$playlist=DEFAULT_VIDEO_PLAYLIST;
# Set a variable to indicate whether the passed video should be displayed or not. Set the default as FALSE.
$display_it=FALSE;
# Create an empty variable to hold potential additional information to append to the "video not available" message.
$message_append='';
$empty_playlist=FALSE;

# Instantiate a new Video object.
$video_obj=Video::getInstance();

# Get the video feed and set it to a variable for display.
$video_feed=$video_obj->displayVideoFeed();

# Check if a playlist was passed in the URL.
if(isset($_GET['playlist']))
{
	$playlist=$_GET['playlist'];
}
# Instantiate a new Category object.
$category_obj=new Category();
# Get the playlist from the "categories" table.
$category_obj->getThisCategory($playlist, $validator->isInt($playlist));
# Ensure theat the playlist value is an ID.
$playlist=$category_obj->getID();
# Check if the following is NOT true: a video ID was passed AND a playlist was NOT passed.
if(!(isset($_GET['video']) && !isset($_GET['playlist'])))
{
	# Create the "WHERE" clause.
	$category_obj->createWhereSQL($category_obj->getID(), 'playlist');
	# If the Playlist is the generic "Videos" playlist, don't set it as the page subtitle.
	if(!((DEFAULT_VIDEO_PLAYLIST=='Videos') && ($category_obj->getCategory()==DEFAULT_VIDEO_PLAYLIST)))
	{
		# Set the page subtitle with the playlist name.
		$main_content->setSubTitle($category_obj->getCategory());
	}
	# Get the Videos from the database.
	$video_retreived=$video_obj->getVideos(NULL, '*', 'date', 'DESC', ' WHERE `new` = 0 AND '.$category_obj->getWhereSQL());
	# Check if there was video retreived for this playlist.
	if($video_retreived!==FALSE)
	{
		# Set the returned Video records to a variable.
		$all_video=$video_obj->getAllVideo();
		# Check if there is more than a single video in this playlist.
		if(count($all_video)>1)
		{
			# Display the list of video in this playlist.
			$video_display.=$video_obj->markupSmallVideo(
				$all_video,
				$playlist,
				((isset($_GET['video'])) ? array($_GET['video']) : $all_video[0]->id)
			);
		}
		# Check if video was passed in the URL to display.
		if(!isset($_GET['video']))
		{
			# Indicate that the video should be displayed.
			$display_it=TRUE;
			$large_video=$all_video[0];
		}
	}
	else
	{
		$empty_playlist=TRUE;
		$video_display.='<p>That playlist contains no videos.</p>';
	}
}
else
{
	$playlist=NULL;
}

# Check if an video ID was passed in the URL.
if(isset($_GET['video']))
{
	# Set a variable to indicate whether the passed video should be displayed or not. Set the default as FALSE.
	$display_it=FALSE;
	# Create an empty variable to hold potential additional information to append to the "video not available" message.
	$message_append='';
	# Set the ID to the ID data member.
	$video_obj->setID($_GET['video']);
	# Retreive the passed video from the DB.
	$video_retreived=$video_obj->getThisVideo($video_obj->getID());
	# Check if the video info was retreived.
	if($video_retreived!==FALSE)
	{
		$associated_playlists=$video_obj->getCategories();
		$large_video=$video_retreived;
		if(isset($_GET['playlist']))
		{
			# Check if the passed video is associated with the passed playlist.
			if(array_key_exists($_GET['playlist'], $associated_playlists))
			{
				# Indicate that the video should be displayed.
				$display_it=TRUE;
			}
			else
			{
				$message_append=' in this playlist, please choose another.';
			}
		}
		else
		{
			# Indicate that the video should be displayed.
			$display_it=TRUE;
		}
	}
}

# Check if video was passed in the URL to display.
if(isset($large_video))
{
	# Indicate that the video should be displayed.
	$display_it=TRUE;
	$single_video=$video_obj->markupLargeVideo(array($large_video));
	if(isset($_GET['playlist']))
	{
		# Set the page title as the title of the video.
		$main_content->setPageTitle($main_content->getSubTitle());
		# Set the subtitle to the title of the video.
		$main_content->setSubTitle($single_video['title']);
	}
	if(isset($_GET['video']) && !isset($_GET['playlist']))
	{
		# Set the subtitle to the title of the video.
		$main_content->setSubTitle($single_video['title']);
	}
}

# Check if the passed video should be displayed.
if($display_it===TRUE)
{
	# Display the video details.
	$video_display.='<div class="video-lg">';
	$video_display.=$single_video['video'];
	$video_display.=$single_video['description'];
	$video_display.='<div>';
}
elseif($empty_playlist===FALSE)
{
	$video_display.='<p>That video file is not available'.$message_append.'.</p>';
}

# Create playlist menu. This will be used in the videos_nav template.
$playlist_items=$video_obj->createPlaylistMenu($playlist, array(2));
# Check if there are any playlists to display in the videos nav.
if(!empty($playlist_items))
{
	# Get the videos navigation.
	require Utility::locateFile(TEMPLATES.'videos_nav.php');
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

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Set the "videos_nav" variable from the videos_nav template to the display_box2 variable for display in the view.
$display_box2.=$videos_nav;

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