<?php /* framework/application/templates/forms/post_form_defaults.php */

# Get the Branch class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
# Instantiate a new Branch object.
$branch=new Branch();
# Get all the branches.
$branch->getBranches();
# Get the current branch's info.
$branch->getThisBranch($branch_name, FALSE);

# Get the Contributor Class.
require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
# Instantiate a new Contributor object.
$contributor=new Contributor();
# Add/Update the contributor table in the Database to reflect this user.
$contributor->addContributor();

# Create defaults.
$post_id=NULL;
$post_availability=1;
# Set the current branch to the $record_branches array.
$post_record_branches='-'.$branch->getID().'-';
$post_contributor_id=$contributor->getContID();
$post_recent_contributor_id=NULL;
$post_date=date('Y-m-d');
$post_last_edit_date=NULL;
$post_facebook='post'; # Set the default to "post" to Facebook.
$post_file_id=NULL;
$post_hide=NULL;
$post_image_id=NULL;
$post_institution_id=9; # Set the default to "Other" (9)
$post_link=NULL;
$post_premium=NULL; # NULL=Not premium content | 0=Premium content (subscription only)
$post_publisher_id=NULL;
$post_text=NULL;
$post_text_language='English'; # Set the default to "English" (3)
$post_text_translation=NULL;
$post_text_translation_language='English'; # Set the default to "English" (3)
$post_title=NULL;
$post_twitter='tweet'; # Set the default to "tweet" to Twitter.
$post_unique=0; # Set the default to "Not Unique" (0)
$post_visibility=NULL; # Set the default to "all_users" (NULL)

# Check if there is GET data called "post".
if(isset($_GET['post']))
{
	# Get the SubContent class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
	# Instantiate a new instance of the SubContent class.
	$sc=new SubContent();
	# Set the passed content ID to the SubContent data member, effectively "cleaning" it.
	$sc->setID($_GET['post']);
	# Set the cleaned SubContent id to a local variable.
	$sc_id=$sc->getID();
	# Get the subcontent from the `subcontent` table.
	if($sc->getThisSubContent($sc_id)===TRUE)
	{
		# Reset the defaults.
		$post_id=$sc_id;
		$post_availability=$sc->getAvailability();
		$post_record_branches=$sc->getRecordBranches();
		$post_contributor_id=$sc->getContID();
		$post_recent_contributor_id=$contributor->getContID();
		$post_date=$sc->getDate();
		$post_last_edit_date=date('Y-m-d');
		$post_facebook=NULL; # Set the default to NOT to "post" to Facebook since it may have already been posted.
		$post_file_id=$sc->getFileID();
		$post_hide=$sc->getHide(); # 0=Hide post | NULL=Don't Hide post
		$post_image_id=$sc->getImageID();
		$post_institution_id=$sc->getInstitutionID();
		$post_link=$sc->getLink();
		$post_premium=$sc->getPremium();
		$post_publisher_id=$sc->getPublisherID();
		$post_text=$sc->getText();
		$post_text_language=$sc->getTextLanguage();
		$post_text_translation=$sc->getTextTrans();
		$post_text_translation_language=$sc->getTransLanguage();
		# If the translation language is NULL, set it to 3 (English) by default.
		$post_text_translation_language=((empty($post_text_translation_language)) ? 3 : $post_text_translation_language);
		$post_title=$sc->getTitle();
		$post_twitter=NULL; # Set the default to NOT to "tweet" to Twitter since it may have already been tweeted.
		$post_unique=1; # Set to "Unique" (1) since it is already a post.
		$post_visibility=$sc->getVisibility();
	}
}

# The key MUST be the name of a "set" mutator method in either the SubContent, PostFormPopulator, or FormPopulator classes (ie setID, setUnique).
$default_data=array(
		'ID'=>$post_id,
		'Availability'=>$post_availability,
		'RecordBranches'=>$post_record_branches,
		'ContID'=>$post_contributor_id,
		'RecentContID'=>$post_recent_contributor_id,
		'Date'=>$post_date,
		'LastEdit'=>$post_last_edit_date,
		'Facebook'=>$post_facebook,
		'FileID'=>$post_file_id,
		'Hide'=>$post_hide,
		'ImageID'=>$post_image_id,
		'InstitutionID'=>$post_institution_id,
		'Link'=>$post_link,
		'Premium'=>$post_premium,
		'PublisherID'=>$post_publisher_id,
		'Text'=>$post_text,
		'TextLanguage'=>$post_text_language,
		'TextTrans'=>$post_text_translation,
		'Title'=>$post_title,
		'TransLanguage'=>$post_text_translation_language,
		'Twitter'=>$post_twitter,
		'Unique'=>$post_unique,
		'Visibility'=>$post_visibility
	);