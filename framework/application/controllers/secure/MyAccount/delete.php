<?php /* framework/application/controllers/secure/MyAccount/delete.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormProcessor.php');

# Check if the User is logged in.
$login->checkLogin(ALL_USERS);

$page_class='myaccount-delete';

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';
$head='<h3 class="h-3">Are you sure you want to delete this account? (It will be permanently removed from the system)</h3>';
$who='my';

# Instantiate a new User object.
$user=new User();

# Instantiate a new FormProcessor object.
$form_processor=new FormProcessor();
# Process the delete form if it has been submitted.
$form_processor->processDeleteAccount();

# Get the delete_user form.
require Utility::locateFile(TEMPLATES.'forms'.DS.'delete_user.php');

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