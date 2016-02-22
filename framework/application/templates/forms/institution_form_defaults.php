<?php /* framework/application/templates/forms/institution_form_defaults.php */

# Create defaults.
$institution_id=NULL;
$institution_name=NULL;
$institution_unique=0; # Set the default to "Not Unique" (0)

# Check if there is GET data called "institution".
if(isset($_GET['institution']))
{
	# Get the Institution class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
	# Instantiate a new instance of the Institution class.
	$institution=new Institution();
	# Set the passed institution ID to the Institution data member, effectively "cleaning" it.
	$institution->setID($_GET['institution']);
	# Set the cleaned SubContent id to a local variable.
	$inst_id=$institution->getID();
	# Get the institution's content from the `institution` table.
	if($institution->getThisInstitution($inst_id)===TRUE)
	{
		# Reset the defaults.
		$institution_id=$inst_id;
		$institution_name=$institution->getInstitution();
		$institution_unique=1; # Set to "Unique" (1) since it is already an institution.
	}
}

# The key MUST be the name of a "set" mutator method in either the Institution, InstitutionFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
		'ID'=>$institution_id,
		'Institution'=>$institution_name,
		'Unique'=>$institution_unique
	);