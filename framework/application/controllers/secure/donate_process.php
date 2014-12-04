<?php /* public/secure/donate_process.php */

# Get the PayPal Class.
require_once Utility::locateFile(MODULES.'PayPal'.DS.'CustomPayPal.php');

# Instantiate a new Document object.
$paypal=new CustomPayPal();

# Process!
$paypal->processPayPal(FALSE, array('donation'=>TRUE), ACCOUNTING_EMAIL);
//$paypal->processPayPal(FALSE, array('donation'=>TRUE), ADMIN_EMAIL);
if($_SERVER['REQUEST_METHOD']!='POST')
{
	$doc->redirect(DEFAULT_REDIRECT);
}