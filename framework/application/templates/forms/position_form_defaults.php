<?php /* framework/application/templates/forms/position_form_defaults.php */

# Create defaults.
$position_description='';
$position_id=NULL;
$position_position=NULL;
$position_unique=0; # Set the default to "Not Unique" (0)

# Check if there is GET data called "position".
if(isset($_GET['position']))
{
	# Get thePposition class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Position.php');
	# Instantiate a new instance of the Position class.
	$position_obj=new Position();
	# Set the passed position ID to the position data member, effectively "cleaning" it.
	$position_obj->setID($_GET['position']);
	# Set the cleaned Position id to a local variable.
	$pos_id=$position_obj->getID();
	# Get the position's content from the `position` table.
	if($position_obj->getThisPosition($pos_id)===TRUE)
	{
		# Reset the defaults.
		$position_description=$position_obj->getDescription();
		$position_id=$pos_id;
		$position_position=$position_obj->getPosition();
		$position_unique=1; # Set to "Unique" (1) since it is already a position.
	}
}

# The key MUST be the name of a "set" mutator method in either the position, positionFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
		'Description'=>$position_description,
		'ID'=>$position_id,
		'Position'=>$position_position,
		'Unique'=>$position_unique
	);