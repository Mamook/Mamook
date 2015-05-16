<?php /* framework/application/controllers/media/audio/index.php */

# Get the Audio Class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
# Get the Category class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
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

$audio_display='';
$audio_nav='';

# Set the meta discription for this page.
$meta_desc='Audio featured on '.DOMAIN_NAME;
$page_class='audiopage';
# Set the page subtitle.
$main_content->setSubTitle('Spotlight Audio');

$large_audio=NULL;
# Set the "Audio" playlist as the default.
$playlist=DEFAULT_AUDIO_PLAYLIST;
# Set a variable to indicate whether the passed audio should be displayed or not. Set the default as FALSE.
$display_it=FALSE;
# Create an empty variable to hold potential additional information to append to the "audio not available" message.
$message_append='';
$empty_playlist=FALSE;

# Instantiate a new Audio object.
$audio_obj=Audio::getInstance();

# Get the audio feed and set it to a variable for display.
$audio_feed=$audio_obj->displayAudioFeed();

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
# Check if the following is NOT true: an audio ID was passed AND a playlist was NOT passed.
if(!(isset($_GET['audio']) && !isset($_GET['playlist'])))
{
	# Create the "WHERE" clause.
	$category_obj->createWhereSQL($category_obj->getID(), 'playlist');
	# If the Playlist is the generic "Audio" playlist, don't set it as the page subtitle.
	if(!((DEFAULT_AUDIO_PLAYLIST=='Audio') && ($category_obj->getName()==DEFAULT_AUDIO_PLAYLIST)))
	{
		# Set the page subtitle with the playlist name.
		$main_content->setSubTitle($category_obj->getName());
	}
	# Get the Audio from the database.
	$audio_retreived=$audio_obj->getAudio(NULL, '*', 'date', 'DESC', ' WHERE `new` = 0 AND '.$category_obj->getWhereSQL());
	# Check if there was audio retreived for this playlist.
	if($audio_retreived!==FALSE)
	{
		# Set the returned Audio records to a variable.
		$all_audio=$audio_obj->getAllAudio();
		# Check if there is more than a single audio in this playlist.
		if(count($all_audio)>1)
		{
			# Display the list of audio in this playlist.
			$audio_display.=$audio_obj->markupSmallAudio(
				$all_audio,
				$playlist,
				((isset($_GET['audio'])) ? array($_GET['audio']) : $all_audio[0]->id)
			);
		}
		# Check if audio was passed in the URL to display.
		if(!isset($_GET['audio']))
		{
			# Indicate that the audio should be displayed.
			$display_it=TRUE;
			$large_audio=$all_audio[0];
		}
	}
	else
	{
		$empty_playlist=TRUE;
		$audio_display.='<p>That playlist contains no audio files.</p>';
	}
}
else
{
	$playlist=NULL;
}

# Check if an audio ID was passed in the URL.
if(isset($_GET['audio']))
{
	# Set a variable to indicate whether the passed audio should be displayed or not. Set the default as FALSE.
	$display_it=FALSE;
	# Create an empty variable to hold potential additional information to append to the "audio not available" message.
	$message_append='';
	# Set the ID to the ID data member.
	$audio_obj->setID($_GET['audio']);
	# Retreive the passed audio from the DB.
	$audio_retreived=$audio_obj->getThisAudio($audio_obj->getID());
	# Check if the audio info was retreived.
	if($audio_retreived!==FALSE)
	{
		$associated_playlists=$audio_obj->getCategories();
		$large_audio=$audio_retreived;
		if(isset($_GET['playlist']))
		{
			# Check if the passed audio is associated with the passed playlist.
			if(array_key_exists($_GET['playlist'], $associated_playlists))
			{
				# Indicate that the audio should be displayed.
				$display_it=TRUE;
			}
			else
			{
				$message_append=' in this playlist, please choose another.';
			}
		}
		else
		{
			# Indicate that the audio should be displayed.
			$display_it=TRUE;
		}
	}
}

# Check if audio was passed in the URL to display.
if(isset($large_audio))
{
	# Indicate that the audio should be displayed.
	$display_it=TRUE;
	$single_audio=$audio_obj->markupLargeAudio(array($large_audio));
	if(isset($_GET['playlist']))
	{
		# Set the page title as the title of the audio.
		$main_content->setPageTitle($main_content->getSubTitle());
		# Set the subtitle to the title of the audio.
		$main_content->setSubTitle($single_audio['title']);
	}
	if(isset($_GET['audio']) && !isset($_GET['playlist']))
	{
		# Set the subtitle to the title of the audio.
		$main_content->setSubTitle($single_audio['title']);
	}
}

# Check if the passed audio should be displayed.
if($display_it===TRUE)
{
	# Display the audio details.
	$audio_display.='<div class="audio-lg">';
	$audio_display.=$single_audio['audio'];
	$audio_display.=$single_audio['description'];
	$audio_display.='<div>';
}
elseif($empty_playlist===FALSE)
{
	$audio_display.='<p>That audio file is not available'.$message_append.'.</p>';
}

# Create playlist menu. This will be used in the audio_nav template.
$playlist_items=$audio_obj->createPlaylistMenu($playlist, array(5));
# Check if there are any playlists to display in the audio nav.
if(!empty($playlist_items))
{
	# Get the audio navigation.
	require Utility::locateFile(TEMPLATES.'audio_nav.php');
}

# Instantiate a new Slideshow object.
$slideshow=Slideshow::getInstance();
$slideshow->setSelector('.feed_list-audio');
$slideshow->setVertical('true');
$slideshow->setStart(0);

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add the audio feed to main-2.
$display_main2.=$audio_feed;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Set the "audio_nav" variable from the audio_nav template to the display_box2 variable for display in the view.
$display_box2.=$audio_nav;

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