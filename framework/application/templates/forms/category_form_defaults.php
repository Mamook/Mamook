<?php /* framework/application/templates/forms/category_form_defaults.php */

# Create defaults.
$category_id=NULL;
$category_name=NULL;
$category_unique=0; # Set the default to "Not Unique" (0)

# Check if there is GET data called "category".
if(isset($_GET['category']))
{
	# Get the Category class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
	# Instantiate a new instance of the Category class.
	$category=new Category();
	# Set the passed category ID to the Category data member, effectively "cleaning" it.
	$category->setID($_GET['category']);
	# Set the cleaned SubContent id to a local variable.
	$inst_id=$category->getID();
	# Get the category's content from the `category` table.
	if($category->getThisCategory($inst_id)===TRUE)
	{
		# Reset the defaults.
		$category_id=$inst_id;
		$category_name=$category->getName();
		$category_unique=1; # Set to "Unique" (1) since it is already an category.
	}
}

# The key MUST be the name of a "set" mutator method in either the Category, CategoryFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
		'ID'=>$category_id,
		'Category'=>$category_name,
		'Unique'=>$category_unique
	);