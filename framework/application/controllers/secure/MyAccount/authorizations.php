<?php /* framework/application/controllers/secure/MyAccount/authorizations.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');
# Get the Branch Class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');

# Check if the User is logged in.
$login->checkLogin(ALL_USERS);

$page_class='myaccount-authorizations';

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='<p>You may request extended privileges, authorizing you to contribute and/or edit content for various aspects or "branches" of '.DOMAIN_NAME.'.</p>';

# Instantiate a new User object.
$user_obj=new User();
# Get the logged in User's ID.
$id=$user_obj->findUserID();

# Instantiate a new FormProcessor object.
$form_processor=new FormProcessor();
# Process the request_auth_form.
$form_processor->processAuthRequest();

# Instantiate a new Branch object.
$branch_obj=new Branch();
# Get all the branch id's.
$branch_obj->getBranches(NULL, 'id');
# Create an empty array to hold the branch id's.
$branch_ids=array();
# Loop through the returned branch rows.
foreach($branch_obj->getAllBranches() as $row)
{
	$branch_ids[]=$row->id;
}
$auth=$form_processor->findAuthorization($branch_ids);

$head='Where would you like to be Authorized?';

# Get the request authorization form.
require Utility::locateFile(TEMPLATES.'forms'.DS.'request_auth.php');

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