<?php /* templates/forms/image_form_defaults.php */

# Get the Contributor Class.
require_once MODULES.'User'.DS.'Contributor.php';
# Instantiate a new Contributor object.
$contributor=new Contributor();
# Add/Update the contributor table in the Database to reflect this user.
$contributor->addContributor();

# Create defaults.
$image_id=NULL;
$image_categories=NULL;
$image_contributor=$contributor->getContID();
$image_description=NULL;
$image_file_name='';
$image_hide=''; # 0=Hide image | NULL=Don't Hide image
$image_last_edit=date('Y-m-d');
$image_location='';
$image_recent_contributor=NULL;
$image_title=NULL;
$image_unique=0;

# Check if there is GET data called "image".
if(isset($_GET['image']))
{
	# Instantiate a new instance of the Image class.
	$image=new Image();
	# Set the passed image ID to the Image data member, effectively "cleaning" it.
	$image->setID($_GET['image']);
	# Get the image from the `images` table.
	if($image->getThisImage($image->getID())===TRUE)
	{
		/* Get the image's categories and set them to a local variable as a dash (-) separated string of the category id's. */
		# Set the categories to a local variable.
		$categories_array=$image->getCategories();
		# Check if there are any categories.
		if(!empty($categories_array))
		{
			# Create a local variable to hold the first dash (-).
			$image_categories='-';
			# Loop through the categories.
			foreach($categories_array as $key => $value)
			{
				# Add the category id to the string appended with a dash (-).
				$image_categories.=$key.'-';
			}
		}
		# Reset the defaults.
		$image_id=$image->getID();
		$image_categories=$image_categories;
		$image_contributor=$image->getContID();
		$image_description=$image->getDescription();
		$image_file_name=$image->getImage();
		$image_hide=$image->getHide(); # 0=Hide image | NULL=Don't Hide image
		$image_last_edit=date('Y-m-d');
		$image_location=$image->getLocation();
		$image_recent_contributor=$contributor->getContID();
		$image_title=$image->getTitle();
		$image_unique=1;
	}
}

# The key MUST be the name of a "set" mutator method in the Image class (ie setID).
$default_data=array(
		'ID'=>$image_id,
		'Categories'=>$image_categories,
		'ContID'=>$image_contributor,
		'Description'=>$image_description,
		'Image'=>$image_file_name,
		'Hide'=>$image_hide, # 0=Hide image | NULL=Don't Hide image
		'LastEdit'=>$image_last_edit,
		'Location'=>$image_location,
		'RecentContID'=>$image_recent_contributor,
		'Title'=>$image_title,
		'Unique'=>$image_unique
	);