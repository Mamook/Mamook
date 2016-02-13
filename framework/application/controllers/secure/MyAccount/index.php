<?php /* framework/application/controllers/secure/MyAccount/index.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the FormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'AccountFormProcessor.php');
# Get the Staff class.
require_once Utility::locateFile(MODULES.'User'.DS.'Staff.php');

$login->checkLogin(ALL_USERS);

$page_class='myaccount';

# Instantiate a new User object.
$user_obj=new User();
# Instantiate a new Staff object. Being used in secure_navbar.php.
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
# Create a variable to hold the User's username and set it to NULL.
$username=NULL;
# Get the logged in User's ID.
$id=$user_obj->findUserID();
$head='<h3 class="h-3">Please use the form below to update your personal information!</h3>';

# Set the User data members.
$user_obj->findUserData($username);
# Instantiate a new AccountFormProcessor object.
$form_processor=new AccountFormProcessor();
# Get the User's display name and set it to a variable.
$display_name=$user_obj->getDisplayName();
# Set the page title.
$main_content->setPageTitle($display_name.'\'s Profile');

# Get the profile form template.
require Utility::locateFile(TEMPLATES.'forms'.DS.'account_form.php');

$img=$user_obj->getImg();
if(!empty($img))
{
	$display_box1b.='<a href="'.IMAGES.'original/'.$img.'" class="profile-image" rel="'.FW_POPUP_HANDLE.'" title="'.((!empty($img_title)) ? $img_title : $display_name).'" target="_blank"><img src="'.IMAGES.$img.'?vers='.mt_rand().'" alt="'.((!empty($img_title)) ? $img_title : $display_name).'"/></a>';
}

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add content to main-2.
$display_main2.='<a href="'.APPLICATION_URL.'profile/?member='.$account_id.'" target="_blank" title="View '.$user_obj->getDisplayName().'\'s Profile" class="view">view</a>';
# Add any display content to main-2.
$display_main2.=$display;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Do we need some javascripts? (Use the script file name before the ".js".)
#$doc->setJavaScripts('uniform');
# Do we need some JavaScripts in the footer? (Use the script file name before the ".php".)
#$doc->setFooterJS('uniform-file');

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