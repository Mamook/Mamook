<?php /* public/secure/admin/ManageContent/announcement/index.php */

# Get the SubContent Class.
require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the PostFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'PostFormProcessor.php');

$login->checkLogin(ANNOUNCEMENT_USERS);

$page_class='manageContentpage-announcement';

$login->findUserData();

$fp=new PostFormProcessor();

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$branch_name='Announcement';
$display='';
$file_details='';
$head='';
$general_edit_head='';
$post_edit_head='';

# Get the form template.
require Utility::locateFile(TEMPLATES.'forms'.DS.'post_form.php');

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