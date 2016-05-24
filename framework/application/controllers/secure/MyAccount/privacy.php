<?php /* framework/application/controllers/secure/MyAccount/privacy.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');
# Get the Branch Class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');

$login->checkLogin(ALL_USERS);

$page_class='myaccount-privacy';

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';

# Instantiate a new User object.
$user_obj=new User();

# Confirm Newsletter subscription.
if(isset($_GET['confirm_newsletter']) && isset($_GET['ID']))
{
	$user_obj->confirmNewsletter($_GET['ID']);
}
# Unsubscribe user from newletters.
elseif(isset($_GET['unsubscribe']) && isset($_GET['ID']))
{
	$user_obj->unsubscribeNewsletter($_GET['ID']);
}

# Instantiate a new Branch object.
$branch_obj=new Branch();
# Get all branch id's.
$branch_obj->getBranches(NULL, '`id`');
# Create an empty array to hold the branch id's.
$branch_ids=array();
# Set the retrieved branch rows to a variable.
$branch_rows=$branch_obj->getAllBranches();
# Loop through the branch rows.
foreach($branch_rows as $row)
{
	# Set the id's to the branch id's array.
	$branch_ids[]=$row->id;
}

# Instantiate a new FormProcessor object.
$form_processor=new FormProcessor();
# Process the privacy form if it has been submitted.
$form_processor->processPrivacy($branch_ids);

$user_obj->findPrivacySettings();

# Get the privacy form.
require Utility::locateFile(TEMPLATES.'forms'.DS.'privacy.php');

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add any display content to main-2.
$display_main2.=$display;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

/*
 ** In the page template we
 ** get the header
 ** get the masthead
 ** get the subnavbar
 ** get the navbar
 ** get the page view
 ** get the quick registration box
 ** get the footer
 */
require Utility::locateFile(TEMPLATES.'page.php');