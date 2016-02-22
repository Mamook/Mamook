<?php /* framework/application/templates/forms/language_form_defaults.php */

# Create defaults.
$language_id=NULL;
$language_iso=NULL;
$language_name=NULL;
$language_unique=0; # Set the default to "Not Unique" (0)

# Check if there is GET data called "language".
if(isset($_GET['language']))
{
	# Get the Language class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
	# Instantiate a new instance of the Language class.
	$language=new Language();
	# Set the passed language ID to the Language data member, effectively "cleaning" it.
	$language->setID($_GET['language']);
	# Set the cleaned SubContent id to a local variable.
	$inst_id=$language->getID();
	# Get the language's content from the `language` table.
	if($language->getThisLanguage($inst_id)===TRUE)
	{
		# Reset the defaults.
		$language_id=$inst_id;
		$language_iso=$language->getISO();
		$language_name=$language->getLanguage();
		$language_unique=1; # Set to "Unique" (1) since it is already a language.
	}
}

# The key MUST be the name of a "set" mutator method in either the Language, LanguageFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
		'ID'=>$language_id,
		'ISO'=>$language_iso,
		'Language'=>$language_name,
		'Unique'=>$language_unique
	);