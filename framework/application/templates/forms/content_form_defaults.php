<?php /* framework/application/templates/forms/content_form_defaults.php */

# Get the Contributor Class.
//require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
# Instantiate a new Contributor object.
//$contributor=new Contributor();
# Add/Update the contributor table in the Database to reflect this user.
//$contributor->addContributor();

# Create defaults.
$content_id=NULL;
$content_archive=NULL; # NULL=Not Archived | 0=Archived
$content_image=NULL;
$content_image_title=NULL;
$content_hide_title=NULL; # 0=Hide Title | NULL=Don't Hide Title
$content_page=NULL; # NULL if not currently assigned to a page
$content_page_title=NULL;
$content_quote='';
$content_subdomain=NULL;
$content_sub_title=NULL;
$content_text='';
$content_topic=''; # For the "page-topic" meta tag.
$content_use_social=NULL; # NULL=Don't use Social buttons | 0=Use social buttons

# Check if there is GET data called "content".
if(isset($_GET['content']))
{
	# Get the Content class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Content.php');
	# Instantiate a new instance of the Content class.
	$content=new Content();
	# Set the passed content ID to the Content data member, effectively "cleaning" it.
	$content->setID($_GET['content']);
	# Set the cleaned Content id to a local variable.
	$returned_content_id=$content->getID();
	# Get the content from the `content` table.
	if($content->getThisContent($returned_content_id)===TRUE)
	{
		# Reset the defaults.
		$content_id=$returned_content_id;
		$content_archive=$content->getArchive(); # NULL=Not Archived | 0=Archived
		$content_image=$content->getImage();
		$content_image_title=$content->getImageTitle();
		$content_hide_title=$content->getHideTitle(); # 0=Hide Title | NULL=Don't Hide Title
		$content_page=$content->getPage(); # NULL if not currently assigned to a page
		$content_page_title=$content->getPageTitle();
		$content_quote=$content->getQuote();
		$content_subdomain=$content->getSubDomain();
		$content_sub_title=$content->getSubTitle();
		$content_text=$content->getText();
		$content_topic=$content->getTopic(); # For the "page-topic" meta tag.
		$content_use_social=$content->getUseSocial(); # NULL=Don't use Social buttons | 0=Use social buttons
	}
}

# The key MUST be the name of a "set" mutator method in the Content class (ie setID).
$default_data=array(
		'ID'=>$content_id,
		'Archive'=>$content_archive, # NULL=Not Archived | 0=Archived
		'Image'=>$content_image,
		'ImageTitle'=>$content_image_title,
		'HideTitle'=>$content_hide_title, # 0=Hide Title | NULL=Don't Hide Title
		'Page'=>$content_page, # NULL if not currently assigned to a page
		'PageTitle'=>$content_page_title,
		'Quote'=>$content_quote,
		'SubDomain'=>$content_subdomain,
		'SubTitle'=>$content_sub_title,
		'Text'=>$content_text,
		'Topic'=>$content_topic, # For the "page-topic" meta tag.
		'UseSocial'=>$content_use_social # NULL=Don't use Social buttons | 0=Use social buttons
	);