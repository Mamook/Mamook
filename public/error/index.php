<?php /* public/error/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'error/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../settings.php';

	# Get the Controller.
	require_once Utility::locateFile(CONTROLLERS.'error'.DS.'index.php');
}
catch(Exception $e)
{
	echo '<div class="error_box">
		<p>There was an error:<br />'.
		$e->getMessage().
		'Code: '.$e->getCode().
		'File: '.$e->getFile().
		'Line:'.$e->getLine().
		'Trace: ';
		print_r($e->getTrace());
	echo '</div>';
}

ob_flush(); # Send the buffer to the user's browser.