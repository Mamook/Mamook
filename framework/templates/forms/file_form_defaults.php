<?php /* templates/forms/file_form_defaults.php */

# Get the Contributor Class.
require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
# Instantiate a new Contributor object.
$contributor=new Contributor();
# Add/Update the contributor table in the Database to reflect this user.
$contributor->addContributor();

# Create defaults.
$file_id=NULL;
$file_author=NULL;
$file_availability=1; # Set the default to "This site has the legal right to display" (1)
$file_categories=array(6); # Set the default to "General" (6)
$file_contributor=$contributor->getContID();
$file_date=date('Y-m-d'); # Set the default to todays date.
$file_file_name=NULL;
$file_institution=9; # Set the default to "Other" (9)
$file_language=3; # Set the default to "English" (3)
$file_last_edit=NULL;
$file_location=NULL;
$file_premium='premium'; # Set the default to "premium" (0)
$file_publisher=NULL;
$file_recent_contributor=NULL;
$file_title=NULL;
$file_unique=0; # Set the default to "Not Unique" (0)
$file_year='unknown'; # Set the default year that the file was originally published to "unknown".

# Check if there is GET data called "file".
if(isset($_GET['file']))
{
	# Instantiate a new instance of the File class.
	$file=new File();
	# Set the passed image ID to the File data member, effectively "cleaning" it.
	$file->setID($_GET['file']);
	# Get the file from the `files` table.
	if($file->getThisFile($file->getID())===TRUE)
	{
		/* Get the file's categories and set them to a local variable as a dash (-) separated string of the category id's. */
		# Set the categories to a local variable.
		$categories_array=$file->getCategories();
		# Check if there are any categories.
		if(!empty($categories_array))
		{
			# Create a local variable to hold the first dash (-).
			$file_categories='-';
			# Loop through the categories.
			foreach($categories_array as $key => $value)
			{
				# Add the category id to the string appended with a dash (-).
				$file_categories.=$key.'-';
			}
		}
		# Reset the defaults.
		$file_id=$file->getID();
		$file_author=$file->getAuthor();
		$file_availability=$file->getAvailability();
		$file_categories=$file_categories;
		$file_contributor=$file->getContID();
		$file_date=$file->getDate();
		$file_file_name=$file->getFile();
		$file_institution=$file->getInstitution();
		$file_language=$file->getLanguage();
		$file_last_edit=date('Y-m-d');
		$file_location=$file->getLocation();
		$file_premium=$file->getPremium();
		$file_publisher=$file->getPublisher();
		$file_recent_contributor=$contributor->getContID();
		$file_title=$file->getTitle();
		$file_unique=1;
		$file_year=$file->getYear();
	}
}

# The key MUST be the name of a "set" mutator method in the File class (ie setID).
$default_data=array(
		'ID'=>$file_id,
		'Author'=>$file_author,
		'Availability'=>$file_availability,
		'Categories'=>$file_categories,
		'ContID'=>$file_contributor,
		'Date'=>$file_date,
		'File'=>$file_file_name,
		'Institution'=>$file_institution,
		'Language'=>$file_language,
		'LastEdit'=>$file_last_edit,
		'Location'=>$file_location,
		'Premium'=>$file_premium,
		'Publisher'=>$file_publisher,
		'RecentContID'=>$file_recent_contributor,
		'Title'=>$file_title,
		'Unique'=>$file_unique,
		'Year'=>$file_year
	);