<?php /* public/secure/login/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'secure/login/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../../settings.php';
	# Get the FormGenerator Class.
	require_once MODULES.'Form'.DS.'FormGenerator.php';
	# Find out where to redirect the user to after they login.
	$login->capturePostLogin();
	# Process the login if it has been submitted.
	$login->processLogin();
	$head='';
	# Check if cookiews are enabled in the user's browser. This creates the variable $error. And tells Javascript to check for cookies if that is enabled.
	$no_cookie_msg='You do not have cookies enabled in your browser. To login to this site and for many of the features to work correctly, you must have cookies enabled.<br />For more information on this site\'s use of cookies, please see our <a href="'.APPLICATION_URL.'policy/#_Use_of_cookies">policy page</a>.';
	$cookies=$session->checkCookies(TRUE);
	if($cookies===FALSE)
	{
		$login_error=$login->getError();
		$login_error=$no_cookie_msg.'<br />If you believe that you do in fact have cookies enabled, simply refresh this page and this error should go away.'.((!empty($error)) ? '</p><br /><p>' : '').$error.$login_error;
		$login->setError($login_error);
		if(empty($alert_title))
		{
			$alert_title='Alert!';
		}
	}

	if($main_content->getRegistration()===NULL)
	{
		# Instantiate form generator object
		$register=new FormGenerator('register', REDIRECT_TO_LOGIN.'register/', 'POST', '_top', FALSE, 'form');
		$register->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		if($login->getPostLogin() !== NULL)
		{
			$register->addElement('hidden', array('name'=>'_post_login', 'value'=>$login->getPostLogin()));
		}
		$register->addElement('submit', array('name'=>'register', 'value'=>'Register'), '', NULL, 'submit-register');
		$display='<div id="register" class="register">'.
			'<h3>Register</h3>'.
			'<p>Enter your information to register with '.DOMAIN_NAME.'. Registered users have access to free and purchaseable materials. Your information is safe with us. We will <em>never</em> share your information with 3rd parties.</p>'.
			$register->display().
			'<a href="'.REDIRECT_TO_LOGIN.'ResendEmail/" class="helper" title="Resend my activation email">Resend my activation email</a>'.
		'</div>';
	}
	else
	{
		$main_content->setPageTitle('Login to '.DOMAIN_NAME);
		$display='';
	}

	# Get the login form.
	require TEMPLATES.'forms'.DS.'login.php';

	# Capture any errors.
	$doc->setError($login->getError());

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