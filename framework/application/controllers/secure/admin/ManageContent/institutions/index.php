<?php /* public/secure/admin/ManageContent/institutions/index.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the InstitutionFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'InstitutionFormProcessor.php');

$login->checkLogin(ALL_BRANCH_USERS);

$page_class='manageContentpage-institutions';

$login->findUserData();

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$audio_nav='';
$display='';
$file_details='';
$general_edit_head='';
$head='';
$post_edit_head='';

$fp=new InstitutionFormProcessor();

# Get the form template.
require Utility::locateFile(TEMPLATES.'forms'.DS.'institution_form.php');

//require Utility::locateFile(TEMPLATES.'forms'.DS.'search_form.php');

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