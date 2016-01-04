<?php /* framework/application/controllers/index.php */

# Get the SubContent Class (also includes Content Class).
require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';

$page_class='homepage';

# Set a default variable for the "AND" portion of the sql statement (1=have the legal rights to display this material 2=Internal document only).
$and_sql=' AND `availability` = 1 AND `visibility` IS NULL';
# Check if the User is logged in.
if($login->isLoggedIn()===TRUE)
{
	# Get the User's access levels and set the array to a variable.
	$u_level=$login->findUserLevel();
	# Implode the Level array to a dash(-) separated string.
	$u_level=implode('-', $u_level);
	# Replace dashes(-) with '-)|(-' in preparation for a REGEXP statement.
	$u_level=str_replace('-', '-)|(-', $u_level);
	# Enclose the User level string in '(-' and '-)'.
	$u_level='(-'.$u_level.'-)';
	$and_sql=' AND `availability` = 1 AND (`visibility` IS NULL OR `visibility` = 0 OR `visibility` REGEXP '.$db->quote($u_level).')';
}
# Check if the logged in User is a Managing User.
if($login->checkAccess(MAN_USERS)===TRUE)
{
	$and_sql=' AND (`availability` = 1 OR `availability` = 2) AND (`visibility` IS NULL OR `visibility` = 0 OR `visibility` REGEXP '.$db->quote($u_level).')';
}
# Check if the logged in User is an Admin.
if($login->checkAccess(ADMIN_USERS)===TRUE)
{
	$and_sql='';
}
# Create a new SubContent object.
$subcontent=new SubContent();
# Get the Announcement SubContent.
$subcontent->getSubContent('Announcement', 1, '*', 'date', 'DESC', $and_sql);
# Set the Announcement subcontent to a variable.
$announcement_subcontent=$subcontent->displaySubContent(120, MAN_USERS, TRUE, 3, FALSE, 0);
if(!empty($announcement_subcontent))
{
	# Loop through the announcement subcontent array.
	foreach($announcement_subcontent as $display_subcontent)
	{
		$display.='<article class="announcement post">';
		$display.=$display_subcontent['date'];
		$display.='<h1 class="header1">'.$display_subcontent['title'].'</h1>';
		$display.=$display_subcontent['text'];
		$display.=$display_subcontent['text_trans'];
		$display.=$display_subcontent['more'];
		$display.=$display_subcontent['download'];
		$display.='</article>';
	}
}

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