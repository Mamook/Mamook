<?php /* templates/fm/email_users_template.php */

# Create the body of the message.
$body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$body.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
$body.='<head>';
$body.='<title>'.$subject.'</title>'."\n";
$body.='<meta http-equiv="content-type" content="text/html; charset=UTF-8" />';
$body.='<meta http-equiv="content-language" content="english" />';
$body.='<meta name="copyright" content="Copyright &copy; '.date('Y').' Jam The Force" />';
$body.='</head>'."\n";
$body.='<body style="background:#fff;border:1px solid #ef4123;border-radius:12px;padding-left:8px">';
$body.='<table style="width:100&#37;;font-size:12px;line-height:18px;font-family:Helvetica,Arial,Verdana,sans-serif;color:#444;">';
$body.='<tr>';
$body.='<td>';
$body.='<p>Hello%s</p>'."\n\n\n";
$body.='<p>'.$message.'</p>'."\n\n\n";
$body.='Thank you,<br />'."\n";
$body.=$sender_name.'<br /><br />'."\n\n\n\n";
$body.='</td>';
$body.='</tr>';
$body.='<tr>';
$body.='<td>';
$body.='<p>* This email was sent to you because you have opted in to receive emails from <a href="'.APPLICATION_URL.'" style="color:#f05033;" title="'.DOMAIN_NAME.' website">'.DOMAIN_NAME.'</a>. If you would prefer not to receive these emails in the future, simply go to your privacy settings at <a href="'.SECURE_URL.'MyAccount/privacy.php" style="color:#f05033;" title="Your Privacy Settings at '.DOMAIN_NAME.'">'.SECURE_URL.'MyAccount/privacy.php</a>, de-select the information you would not like to receive and click "Update". Learn more about '.$main_content->getSiteName().'\'s privacy policy at <a href="'.APPLICATION_URL.'policy/" style="color:#f05033;" title="'.DOMAIN_NAME.' privacy policy">'.APPLICATION_URL.'policy</a></p>'."\n";
$body.='</td>';
$body.='</tr>';
$body.='</table>';
$body.='</body>';
$body.='</html>';