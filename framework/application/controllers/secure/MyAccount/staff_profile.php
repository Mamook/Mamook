<?php /* framework/application/controllers/secure/MyAccount/staff_profile.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the StaffFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'StaffFormProcessor.php');
# Get the Staff class.
require_once Utility::locateFile(MODULES.'User'.DS.'Staff.php');

$login->checkLogin(ALL_USERS);

$page_class='myaccount-staffprofile';

# Instantiate a new Staff object.
$staff_obj=new Staff();

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

# Create and empty variable to hold the view $display variable.
$display='';
# Get the user's staff ID.
$staff_id=$login->findStaffID();
$head='<h3 class="h-3">Please use the form below to update your staff information!</h3>';

# Set the Staff data members.
$staff_obj->getThisStaff($staff_id);
# Instantiate a new StaffFormProcessor object.
$form_processor=new StaffFormProcessor();
# Get the User's display name and set it to a variable.
$display_name=$staff_obj->getStaffName();
# Set the page title.
$main_content->setPageTitle($display_name.'\'s Profile');

# Get the staff profile form template.
require Utility::locateFile(TEMPLATES.'forms'.DS.'staff_form.php');

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

# Do we need some javascripts? (Use the script file name before the ".js".)
$doc->setJavaScripts('uniform');
# Do we need some JavaScripts in the footer? (Use the script file name before the ".php".)
$doc->setFooterJS('uniform-file');

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