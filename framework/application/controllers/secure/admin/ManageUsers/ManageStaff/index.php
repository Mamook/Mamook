<?php /* application/controllers/secure/admin/ManageUsers/ManageStaff/index.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the StaffFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'StaffFormProcessor.php');
# Get the Staff class.
require_once Utility::locateFile(MODULES.'User'.DS.'Staff.php');

# Check if the logged in User is an Admin.
$login->checkLogin(ADMIN_USERS);

$page_class='manageUserspage-manageStaffpage';

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';
$head='';

# Create a new Staff object.
$staff_obj=new Staff();

if(isset($_GET['user']) OR isset($_GET['person']))
{
	if(isset($_GET['user']))
	{
		# Create a new User object.
		$user_obj=new User();
		# Set the User ID to the data member; effectively cleaning it.
		$staff_obj->setUser($_GET['user']);
		# Set the data member to a variable.
		$user_id=$staff_obj->getUser();
		# Get this user's staff ID from the `users` table.
		$staff_id=$user_obj->findStaffID($user_id);
		# Get this user's username.
		$current_username=$user_obj->findUsername($user_id);
	}
	if(isset($_GET['person']))
	{
		$staff_id=$_GET['person'];
	}
	# Set the Staff ID to the data member; effectively cleaning it.
	$staff_obj->setID($staff_id);
	# Make sure the User is an admin.
	if($login->checkAccess(ADMIN_USERS)===TRUE)
	{
		# Get the staff's name and set it to a variable.
		$staff_name=$staff_obj->getStaffName($staff_id);
		$head='<h3 class="h-3">Please use the form below to update staff: '.$staff_name.'</h3>';
		# Set the page title.
		$page_title=$staff_name.'\'s Staff Profile';
	}
}
else
{

}
# Instantiate a new StaffFormProcessor object.
$form_processor=new StaffFormProcessor();
# Get the staff profile form.
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