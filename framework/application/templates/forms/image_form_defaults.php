<?php /* framework/application/templates/forms/image_form_defaults.php */

# Get the Contributor Class.
require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
# Instantiate a new Contributor object.
$contributor=new Contributor();
# Add/Update the contributor table in the Database to reflect this user.
$contributor->addContributor();

# If redirected to images form from the audio or video form.
if(
	isset($_SESSION['form']['audio']) ||
	isset($_SESSION['form']['video'])
	)
{
	# Set the default image height to:
	$height='360';
	# Set the default image width to:
	$width='480';
}

# Create defaults.
$image_id=NULL;
$image_categories=NULL;
$image_contributor=$contributor->getContID();
$image_description=NULL;
$image_file_name='';
$image_height=(isset($height) ? $height : '90');
$image_hide=''; # 0=Hide image | NULL=Don't Hide image
$image_last_edit=date('Y-m-d');
$image_location='';
$image_recent_contributor=NULL;
$image_title=NULL;
$image_unique=0;
$image_width=(isset($width) ? $width : '120');

# Check if there is GET data called "image".
if(isset($_GET['image']))
{
	# Instantiate a new instance of the Image class.
	$image_obj=new Image();
	# Set the passed image ID to the Image data member, effectively "cleaning" it.
	$image_obj->setID($_GET['image']);
	# Get the image from the `images` table.
	if($image_obj->getThisImage($image_obj->getID())===TRUE)
	{
		# Reset the defaults.
		$image_id=$image_obj->getID();
		# Get the image's categories and set them to a local variable as a dash (-) separated string of the category id's.
		# Set the categories to a local variable.
		$categories_array=$image_obj->getCategories();
		# Check if there are any categories.
		if(!empty($categories_array))
		{
			# Create a local variable to hold the first dash (-).
			$image_categories='-';
			# Loop through the categories.
			foreach($categories_array as $key=>$value)
			{
				# Add the category id to the string appended with a dash (-).
				$image_categories.=$key.'-';
			}
		}
		$image_categories=$image_categories;
		$image_contributor=$image_obj->getContID();
		$image_description=$image_obj->getDescription();
		$image_file_name=$image_obj->getImage();
		# Get the FileHandler class.
		require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
		# Instantiate the new FileHandler object.
		$file_handler=new FileHandler();
		# Get the image info.
		$file_handler->getImageInfo(IMAGES_PATH.DS.$image_file_name);
		$image_obj->setHeight($file_handler->getHeight());
		$image_obj->setWidth($file_handler->getWidth());
		$image_height=$image_obj->getHeight();
		$image_hide=$image_obj->getHide(); # 0=Hide image | NULL=Don't Hide image
		$image_last_edit=date('Y-m-d');
		$image_location=$image_obj->getLocation();
		$image_recent_contributor=$contributor->getContID();
		$image_title=$image_obj->getTitle();
		$image_unique=1;
		$image_width=$image_obj->getWidth();
	}
}

# The key MUST be the name of a "set" mutator method in the Image class (ie setID).
$default_data=array(
		'ID'=>$image_id,
		'Categories'=>$image_categories,
		'ContID'=>$image_contributor,
		'Description'=>$image_description,
		'Image'=>$image_file_name,
		'Height'=>$image_height,
		'Hide'=>$image_hide, # 0=Hide image | NULL=Don't Hide image
		'LastEdit'=>$image_last_edit,
		'Location'=>$image_location,
		'RecentContID'=>$image_recent_contributor,
		'Title'=>$image_title,
		'Unique'=>$image_unique,
		'Width'=>$image_width
	);