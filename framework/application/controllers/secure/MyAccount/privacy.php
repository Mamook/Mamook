<?php /* public/secure/MyAccount/privacy.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');
# Get the Branch Class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');

$login->checkLogin(ALL_USERS);

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
$user=new User();

# Instantiate a new Branch object.
$branch=new Branch();
# Get all branch id's.
$branch->getBranches(NULL, '`id`');
# Create an empty array to hold the branch id's.
$branch_ids=array();
# Set the retrieved branch rows to a variable.
$branch_rows=$branch->getAllBranches();
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

$user->findPrivacySettings();

# Get the privacy form.
require Utility::locateFile(TEMPLATES.'forms'.DS.'privacy.php');

# Get the page title and subtitle to display in main-1.
$display_main1=$main_content->displayTitles();

# Get the main content to display in main-2. The "image_link" variable is defined in data/init.php.
$display_main2=$main_content->displayContent($image_link);
# Add any display content to main-2.
$display_main2.=$display;

# Get the quote text to display in main-3.
$display_main3=$main_content->displayQuote();

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