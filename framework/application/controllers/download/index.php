<?php /* public/download/index.php */

$_SESSION['message']='You must login to download. If you\'re not already registered with the site, please create an account using the form below. Registering with '.DOMAIN_NAME.' is free and easy! Registered users have access to special content and downloads.';
$login->checkLogin(ALL_USERS);
unset($_SESSION['message']);

# Get the Download Class.
require_once Utility::locateFile(MODULES.'FileHandler'.DS.'Download.php');
$t='';
$auth=TRUE;
# Catch GET Data.
if(isset($_GET['t']))
{
	if($_GET['t']=='premium')
	{
		$auth=FALSE;
		$user_auth=$db->get_row('SELECT `subscription`, `product` FROM `'.DBPREFIX.'users` WHERE `ID` = '.$db->quote($login->findUserID()));
		# Get the File class.
		require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
		# Instantiate a new File object.
		$file=new File();
		# Attempt to get this file's info from the database.
		if($file->getThisFile($_GET['f'], FALSE)===TRUE)
		{
			# Set the file's id to a variable.
			$file_id=$file->getID();
			if($file->getPremium()!==NULL)
			{
				if($user_auth->subscription!==NULL)
				{
					if($user_auth->subscription>=date('Y-m-d'))
					{
						$auth=TRUE;
					}
					else
					{
						$_SESSION['message']='You\'re subscription has expired. You may renew your subscription at: <a href="http://store.'.DOMAIN_NAME.'/subscriptions/">http://store.'.DOMAIN_NAME.'/subscriptions/</a>';
					}
				}
				else
				{
					$_SESSION['message']='You need a subscription to download that file. You may purchase one at our <a href="http://store.'.DOMAIN_NAME.'/subscriptions/">store</a>. Simply go to: <a href="http://store.'.DOMAIN_NAME.'/subscriptions/">http://store.'.DOMAIN_NAME.'/subscriptions/</a> and purchase the subscription(s) you desire using a credit card or PayPal&trade; account.';
				}
				if($user_auth->product!==NULL)
				{
					$products=trim($user_auth->product, '-');
					$products=explode('-', $products);
					if(in_array($user_auth->id, $products))
					{
						$auth=TRUE;
					}
				}
			}
			else
			{
				$auth=TRUE;
			}
		}
	}
	$t=$_GET['t'].'/';
}
if($auth===TRUE)
{
	# Instantiate a Download object.
	$download=new Download($t);
}
else
{
	if(isset($_SESSION['_post_login']))
	{
		$doc->redirect('http://'.$_SESSION['_post_login']);
	}
	$doc->redirect(DEFAULT_REDIRECT);
}