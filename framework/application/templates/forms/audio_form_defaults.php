<?php /* framework/application/templates/forms/audio_form_defaults.php */

# Get the Contributor Class.
require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
# Instantiate a new Contributor object.
$contributor=new Contributor();
# Add/Update the contributor table in the Database to reflect this user.
$contributor->addContributor();

# Create defaults.
$audio_id=NULL;
$audio_api=NULL;
$audio_author=NULL;
$audio_availability=1; # Set the default to "This site has the legal right to display" (1)
$audio_categories=25; # Set the default to "Education" (25)
$audio_contributor=$contributor->getContID();
$audio_date=date('Y-m-d'); # Set the default to todays date.
$audio_description=NULL;
$audio_embed_code=NULL;
$audio_facebook='post'; # Set the default to "post" to Facebook.
$audio_file_name='';
$audio_image_id=NULL;
$audio_institution=9; # Set the default to "Other" (9)
$audio_language=3; # Set the default to "English" (3)
$audio_last_edit_date=NULL;
$audio_playlists=NULL;
$audio_publisher=NULL;
$audio_recent_contributor_id=NULL;
$audio_title=NULL;
$audio_twitter='tweet'; # Set the default to "tweet" to Twitter.
$audio_unique=0; # Set the default to "Not Unique" (0)
$audio_audio_type='file';
$audio_year=date('Y'); # Set the default year that the audio was originally published to the current year.

$max_file_size=1073741824; # Set the default max file size in bytes to "1073741824" (1GB).

# Check if there is GET data called "audio".
if(isset($_GET['audio']))
{
	# Instantiate a new instance of the Audio class.
	$audio_obj=new Audio();
	# Set the passed audio ID to the Audio data member, effectively "cleaning" it.
	$audio_obj->setID($_GET['audio']);
	# Get the audio from the `audio` table.
	if($audio_obj->getThisAudio($audio_obj->getID())!==FALSE)
	{
		# Reset the defaults.
		$audio_id=$audio_obj->getID();
		$audio_api=$audio_obj->getAPI();
		$audio_author=$audio_obj->getAuthor();
		$audio_availability=$audio_obj->getAvailability();
		# Get the audio's categories and set them to a local variable as a dash (-) separated string of the category id's.
		# Set the categories to a local variable.
		$categories_array=$audio_obj->getCategories();
		# Check if there are any categories.
		if(!empty($categories_array))
		{
			# Create a local variable to hold the first dash (-).
			$audio_categories='-';
			# Loop through the categories.
			foreach($categories_array as $key=>$value)
			{
				# Add the category id to the string appended with a dash (-).
				$audio_categories.=$key.'-';
			}
		}
		$audio_categories=$audio_categories;
		$audio_contributor=$audio_obj->getContID();
		$audio_date=$audio_obj->getDate();
		$audio_description=$audio_obj->getDescription();
		$audio_embed_code=$audio_obj->getEmbedCode();
		$audio_facebook=NULL; # Set the default to NOT to "post" to Facebook since it may have already been posted.
		$audio_file_name=$audio_obj->getFileName();
		$audio_image_id=$audio_obj->getImageID();
		$audio_institution=$audio_obj->getInstitution();
		$audio_language=$audio_obj->getLanguage();
		$audio_last_edit_date=date('Y-m-d');
		# Get the audio's playlists and set them to a local variable as a dash (-) separated string of the playlist id's.
		# Set the categories to a local variable.
		$playlists_array=$audio_obj->getPlaylists();
		# Check if there are any playlists.
		if(!empty($playlists_array))
		{
			# Create a local variable to hold the first dash (-).
			$audio_playlists='-';
			# Loop through the playlists.
			foreach($playlists_array as $key=>$value)
			{
				# Add the playlist id to the string appended with a dash (-).
				$audio_playlists.=$key.'-';
			}
		}
		$audio_playlists=$audio_playlists;
		$audio_publisher=$audio_obj->getPublisher();
		$audio_recent_contributor_id=$contributor->getContID();
		$audio_title=$audio_obj->getTitle();
		$audio_twitter=NULL; # Set the default to NOT to "tweet" to Twitter since it may have already been tweeted.
		$audio_unique=1;
		if(!empty($audio_file_name))
		{
			$audio_audio_type='file';
		}
		else $audio_audio_type='embed';
		$audio_year=$audio_obj->getYear();
	}
}

# The key MUST be the name of a "set" mutator method in the Audio class (ie setID).
$default_data=array(
		'ID'=>$audio_id,
		'API'=>$audio_api,
		'Author'=>$audio_author,
		'Availability'=>$audio_availability,
		'Categories'=>$audio_categories,
		'ContID'=>$audio_contributor,
		'Date'=>$audio_date,
		'Description'=>$audio_description,
		'EmbedCode'=>$audio_embed_code,
		'Facebook'=>$audio_facebook,
		'FileName'=>$audio_file_name,
		'ImageID'=>$audio_image_id,
		'Institution'=>$audio_institution,
		'Language'=>$audio_language,
		'LastEdit'=>$audio_last_edit_date,
		'Playlists'=>$audio_playlists,
		'Publisher'=>$audio_publisher,
		'RecentContID'=>$audio_recent_contributor_id,
		'Title'=>$audio_title,
		'Twitter'=>$audio_twitter,
		'Unique'=>$audio_unique,
		'AudioType'=>$audio_audio_type,
		'Year'=>$audio_year
	);