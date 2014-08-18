<?php /* public/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../settings.php';

	# Get the SubContent Class (also includes Content Class).
	require_once MODULES.'Content'.DS.'SubContent.php';

	$announcement='';
	$display_content='';

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
	$announcement_subcontent=$subcontent->displaySubContent(120, MAN_USERS, FALSE, 3, FALSE, 0);
	if(!empty($announcement_subcontent))
	{
		# Loop through the announcement subcontent array.
		foreach($announcement_subcontent as $display_subcontent)
		{
			$announcement='<div class="post">'."\n";
			$announcement.=$display_subcontent['date'];
			$announcement.=$display_subcontent['title'];
			$announcement.=$display_subcontent['text'];
			$announcement.=$display_subcontent['text_trans'];
			$announcement.=$display_subcontent['more'];
			$announcement.=$display_subcontent['download'];
			$announcement.='</div>'."\n";
		}
	}

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
	require TEMPLATES.'page.php';
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.