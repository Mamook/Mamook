<?php /* public/secure/admin/ManageUsers/EmailUsers/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/admin/ManageUsers/EmailUsers/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../../../settings.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Get the EmailFormProcessor Class.
	require_once MODULES.'Form'.DS.'EmailFormProcessor.php';

	$login->checkLogin(ADMIN_USERS);
	$login->findUserData();

	# Get the email form default values.
	require TEMPLATES.'forms'.DS.'email_users_form_defaults.php';

	$address='';
	$display='';
	$head='<h3>Please use the form below to send an email!</h3>';
	$head.='(Note that because of hosting restrictions, the maximum number of emails that can be sent in an hour is 100. This mailer will send an email once every 40 seconds to stay within that quota. Please do not use this form a second time until you have recieved the confrimation email from your previous mailing.)';
	$recipients=array(
			'ALL_USERS'=>'All Users',
			'gml_subscription'=>'GML Subscribers',
			'fwj_subscription'=>'FWJ Subscribers',
			'MAN_USERS'=>DOMAIN_NAME.' Managers',
			'ALL_ADMIN_MAN'=>'All Branch Admins',
			'ALL_BRANCH_USERS'=>'All Branch Users',
			'ASP_ADMIN_USERS'=>'ASP Admins',
			'ASP_USERS'=>'ASP Members',
			'BIAFRA_ADMIN_USERS'=>'Biafra Admins',
			'BIAFRA_USERS'=>'Biafra Members',
			'EDP_ADMIN_USERS'=>'EDP Admins',
			'EDP_USERS'=>'EDP Authorized Users',
			'ESP_ADMIN_USERS'=>'ESP Admins',
			'ESP_USERS'=>'ESP Authorized Users',
			'FGE_ADMIN_USERS'=>'FGE Admins',
			'FGE_USERS'=>'FGE Authorized Users',
			'FWE_ADMIN_USERS'=>'FWE Admins',
			'FWE_USERS'=>'FWE Authors',
			'GML_ADMIN_USERS'=>'GML Admins',
			'GML_USERS'=>'GML Authorized Users',
			'CURRENT_RESEARCH_ADMIN_USERS'=>'Current Research Admins',
			'CURRENT_RESEARCH_USERS'=>'Current Research Members',
			'COMPLETED_RESEARCH_ADMIN_USERS'=>'Complete Research Admins',
			'COMPLETED_RESEARCH_USERS'=>'Complete Research Members',
			'ANNOUNCEMENT_ADMIN_USERS'=>'Announcement Admins',
			'ANNOUNCEMENT_USERS'=>'Announcement Members',
			'HERMES_USERS'=>'Hermes Users',
			'GAPPS_USERS'=>'Google Apps Users'
		);

	# Instantiate a new EmailFormProcessor object.
	$fp=new EmailFormProcessor();
	$fp->setFormAction(SECURE_URL.WebUtility::removeIndex(SECURE_HERE));
	//$fp->setUpload(TRUE);

	# Get the form mail template.
	require TEMPLATES.'forms'.DS.'email_users_form.php';

	# Set the default style sheet(s) we are using for the site. (must be absolute location)
	//$doc->setStyle(THEME.'css/secure.css');

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