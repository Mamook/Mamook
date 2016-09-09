<?php /* framework/application/controllers/secure/login/register/index.php */

if($login->isLoggedIn()===TRUE)
{
	$doc->redirect(REDIRECT_AFTER_LOGIN);
}

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the RegisterFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'RegisterFormProcessor.php');

# Find out where to redirect the user to after they login.
$login->capturePostLogin();

$fp=new RegisterFormProcessor();

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';
$file_details='';
$head='<p>Enter your information to register with '.DOMAIN_NAME.'. Registered users have access to free and purchaseable materials. Your information is safe with us. We will <em>never</em> share your information with 3rd parties.</p>';
$page_class='loginpage-register';

# Check if cookies are enabled in the user's browser. This creates the variable $error.
$no_cookie_msg='You do not have cookies enabled in your browser. To login to this site and for many of the features to work correctly, you must have cookies enabled.<br />For more information on this site\'s use of cookies, please see our <a href="'.APPLICATION_URL.'policy/#_Use_of_cookies">policy page</a>.';
$cookies=$session->checkCookies(TRUE, $no_cookie_msg);
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

require Utility::locateFile(TEMPLATES.'forms'.DS.'register_form.php');

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add content to main-2.
$display_main2.='<p>If you are have trouble registering or logging in, please send us an <a href="'.APPLICATION_URL.'webSupport/" title="Email web support">email</a>.</p>';
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