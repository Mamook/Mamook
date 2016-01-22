<?php /* public/secure/admin/Logs/index.php */

$login->checkLogin(ALL_ADMIN_MAN);

$page_class='logpage';

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';

# Check if there is GET data and that the passed variable is $_GET['log'].
if(isset($_GET['log']))
{
	$display.='<h3 class="h-3">'.$_GET['log'].'</h3>';
	# If file exists.
	if(file_exists(LOGS.$_GET['log']))
	{
		# Get log file contents and convert newlines to <br>'s.
		$display.=nl2br(file_get_contents(LOGS.$_GET['log']));
	}
	# If file exists and if log file is the CHANGELOG file.
	elseif(file_exists(BASE_PATH.$_GET['log']) && $_GET['log']==CHANGELOG)
	{
		# Get log file contents.
		$log_file=file_get_contents(BASE_PATH.$_GET['log']);
		# Explode the log file to an array and get the first 5 elements.
		$explode_log_file=array_slice(explode("\n\n", $log_file), 0, 5, TRUE);
		# Loop through the array.
		foreach($explode_log_file as $log_data)
		{
			# Convert newlines to <br>'s.
			$display.=nl2br($log_data).'<br><br>';
		}
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