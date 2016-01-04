<?php /* framework/application/controllers/secure/pp_process.php */

# Get the PayPal Class.
require_once Utility::locateFile(MODULES.'PayPal'.DS.'PayPal.php');

# Instantiate a new Paypal object.
$paypal=new PayPal();

# Process!
$paypal->processPayPal(TRUE, NULL, array(ACCOUNTING_EMAIL, ADMIN_EMAIL));

if($_SERVER['REQUEST_METHOD']!='POST')
{
	$doc->redirect(DEFAULT_REDIRECT);
}