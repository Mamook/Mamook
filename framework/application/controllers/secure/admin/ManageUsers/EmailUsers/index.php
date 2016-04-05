<?php /* framework/application/controllers/secure/admin/ManageUsers/EmailUsers/index.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the EmailFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'EmailFormProcessor.php');

$login->checkLogin(ADMIN_USERS);

$page_class='manageUserspage-emailusers';

$login->findUserData();

# Get the email form default values.
require Utility::locateFile(TEMPLATES.'forms'.DS.'email_users_form_defaults.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$address='';
$display='';
$head='<h3>Please use the form below to send an email!</h3>';
$head.='(Note that because of hosting restrictions, the maximum number of emails that can be sent in an hour is 100. This mailer will send '.MAILQUEUE_BATCH_SIZE.' email once every '.MAILQUEUE_THROTTLE.' seconds to stay within that quota. Please do not use this form a second time until you have recieved the confrimation email from your previous mailing.)';
$recipients=array(
	'ALL_USERS'=>'All Users',
	'MAN_USERS'=>DOMAIN_NAME.' Managers',
	'ALL_ADMIN_MAN'=>'All Branch Admins',
	'ALL_BRANCH_USERS'=>'All Branch Users',
	'ANNOUNCEMENT_ADMIN_USERS'=>'Announcement Admins',
	'ANNOUNCEMENT_USERS'=>'Announcement Members',
	'GAPPS_USERS'=>'Google Apps Users'
);

# Instantiate a new EmailFormProcessor object.
$fp=new EmailFormProcessor();
$fp->setFormAction(SECURE_URL.WebUtility::removeIndex(SECURE_HERE));
//$fp->setUpload(TRUE);

# Get the form mail template.
require Utility::locateFile(TEMPLATES.'forms'.DS.'email_users_form.php');

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