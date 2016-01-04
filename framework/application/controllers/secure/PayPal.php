<?php /* framework/application/controllers/secure/PayPal.php */

# Get the PayPal Class.
require_once Utility::locateFile(MODULES.'PayPal'.DS.'PayPal.php');

$page_class='PayPal';

if($login->isLoggedIn()!==TRUE)
{
	$_SESSION['message']='Please <a href="'.REDIRECT_TO_LOGIN.'">login</a> to the site first. If you don\'t already have an account, please <a href="'.REDIRECT_TO_LOGIN.'register/">create an account</a>. Registering with '.DOMAIN_NAME.' is free and easy. Registered users have access to downloads and special content.';
	$doc->redirect(REDIRECT_TO_LOGIN);
}
# Instantiate a new Document object.
$paypal=new PayPal();

# Process!
$paypal->redirectToPayPal();
if(($_SERVER['REQUEST_METHOD']!='POST') || ($_SERVER['REQUEST_METHOD']!='GET'))
{
	$doc->redirect(DEFAULT_REDIRECT);
}