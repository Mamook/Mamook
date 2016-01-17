<?php /* framework/application/templates/forms/video_form_defaults.php */

# Get the Contributor Class.
require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
# Instantiate a new Contributor object.
$contributor=new Contributor();
# Add/Update the contributor table in the Database to reflect this user.
$contributor->addContributor();

# Create defaults.
$video_id=NULL;
$video_api=NULL;
$video_author=NULL;
$video_availability=1; # Set the default to "This site has the legal right to display" (1)
$video_categories=DEFAULT_VIDEO_CATEGORY;
$video_contributor=$contributor->getContID();
$video_date=date('Y-m-d'); # Set the default to todays date.
$video_description=NULL;
$video_embed_code=NULL;
$video_facebook='post'; # Set the default to "post" to Facebook.
$video_file_name='';
$video_image_id=NULL;
$video_institution=9; # Set the default to "Other" (9)
$video_language=3; # Set the default to "English" (3)
$video_last_edit_date=NULL;
$video_playlists=NULL;
$video_publisher=NULL;
$video_recent_contributor_id=NULL;
$video_title=NULL;
$video_twitter='tweet'; # Set the default to "tweet" to Twitter.
$video_unique=0; # Set the default to "Not Unique" (0)
$video_video_type='file';
$video_year=date('Y'); # Set the default year that the video was originally published to the current year.
$video_youtube='post_youtube'; # Set the default to 'post_youtube' to post to YouTube.

$max_file_size=1073741824; # Set the default max file size in bytes to "1073741824" (1GB).

# Check if there is GET data called "video".
if(isset($_GET['video']))
{
	# Instantiate a new instance of the Video class.
	$video_obj=new Video();
	# Set the passed video ID to the Video data member, effectively "cleaning" it.
	$video_obj->setID($_GET['video']);
	# Get the video from the `videos` table.
	if($video_obj->getThisVideo($video_obj->getID())!==FALSE)
	{
		# Reset the defaults.
		$video_id=$video_obj->getID();
		$video_api=$video_obj->getAPI();
		$video_author=$video_obj->getAuthor();
		$video_availability=$video_obj->getAvailability();
		# Get the video's categories and set them to a local variable as a dash (-) separated string of the category id's.
		# Set the categories to a local variable.
		$categories_array=$video_obj->getCategories();
		# Check if there are any categories.
		if(!empty($categories_array))
		{
			# Create a local variable to hold the first dash (-).
			$video_categories='-';
			# Loop through the categories.
			foreach($categories_array as $key=>$value)
			{
				# Add the category id to the string appended with a dash (-).
				$video_categories.=$key.'-';
			}
		}
		$video_categories=$video_categories;
		$video_contributor=$video_obj->getContID();
		$video_date=$video_obj->getDate();
		$video_description=$video_obj->getDescription();
		$video_embed_code=$video_obj->getEmbedCode();
		$video_facebook=NULL; # Set the default to NOT to "post" to Facebook since it may have already been posted.
		$video_file_name=$video_obj->getFileName();
		$video_image_id=$video_obj->getImageID();
		$video_institution=$video_obj->getInstitution();
		$video_language=$video_obj->getLanguage();
		$video_last_edit_date=date('Y-m-d');
		# Get the video's playlists and set them to a local variable as a dash (-) separated string of the playlist id's.
		# Set the categories to a local variable.
		$playlists_array=$video_obj->getPlaylists();
		# Check if there are any playlists.
		if(!empty($playlists_array))
		{
			# Create a local variable to hold the first dash (-).
			$video_playlists='-';
			# Loop through the playlists.
			foreach($playlists_array as $key=>$value)
			{
				# Add the playlist id to the string appended with a dash (-).
				$video_playlists.=$key.'-';
			}
		}
		$video_playlists=$video_playlists;
		$video_publisher=$video_obj->getPublisher();
		$video_recent_contributor_id=$contributor->getContID();
		$video_title=$video_obj->getTitle();
		$video_twitter=NULL; # Set the default to NOT to "tweet" to Twitter since it may have already been tweeted.
		$video_unique=1;
		if(!empty($video_file_name))
		{
			$video_video_type='file';
		}
		else $video_video_type='embed';
		$video_year=$video_obj->getYear();
	}
}

# The key MUST be the name of a "set" mutator method in the Video class (ie setID).
$default_data=array(
		'ID'=>$video_id,
		'API'=>$video_api,
		'Author'=>$video_author,
		'Availability'=>$video_availability,
		'Categories'=>$video_categories,
		'ContID'=>$video_contributor,
		'Date'=>$video_date,
		'Description'=>$video_description,
		'EmbedCode'=>$video_embed_code,
		'Facebook'=>$video_facebook,
		'FileName'=>$video_file_name,
		'ImageID'=>$video_image_id,
		'Institution'=>$video_institution,
		'Language'=>$video_language,
		'LastEdit'=>$video_last_edit_date,
		'Playlists'=>$video_playlists,
		'Publisher'=>$video_publisher,
		'RecentContID'=>$video_recent_contributor_id,
		'Title'=>$video_title,
		'Twitter'=>$video_twitter,
		'Unique'=>$video_unique,
		'VideoType'=>$video_video_type,
		'Year'=>$video_year,
		'YouTube'=>$video_youtube
	);