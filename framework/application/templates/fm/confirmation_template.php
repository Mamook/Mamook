<?php /* framework/application/templates/fm/confirmation_template.php */

# Create the body of the message.
$body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.
'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">'.
	'<head>'.
		'<title>'.$subject.'</title>'."\n".
		'<meta http-equiv="content-type" content="text/html; charset=UTF-8" />'.
		'<meta http-equiv="content-language" content="english" />'.
		'<meta name="copyright" content="Copyright &copy; '.date('Y').' '.$site_name.'" />'.
	'</head>'."\n".
	'<body style="background:#fff;border:1px solid #ef4123;border-radius:12px;padding-left:8px">'.
		'<table style="width:100&#37;font-size:12px;line-height:18px;font-family:Helvetica,Arial,Verdana,sans-serif;color:#444;">'.
			'<tr>'.
				'<td>'.
					'<p>Hello%s</p>'."\n\n\n".
					'<p>'.$message.'</p>'."\n\n\n".
					'Thank you,<br />'."\n".
					DOMAIN_NAME.' Automated Email System<br /><br />'."\n\n\n\n".
				'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>'.
					'<p>* This email was sent to you because you initiated an event on '.DOMAIN_NAME.' that was continued after you left the page. This email is sent automatically upon completion of the event and is intended for your information only. If you believe you have recieved this email in error please contact the webmaster at <a href="'.APPLICATION_URL.'webSupport/" style="color:#f05033;" title="Contact the '.DOMAIN_NAME.' webmaster">'.APPLICATION_URL.'webSupport</a>. Learn more about '.$site_name.'\'s privacy policy at <a href="'.APPLICATION_URL.'policy" style="color:#f05033;" title="'.DOMAIN_NAME.' privacy policy">'.APPLICATION_URL.'policy</a></p>'."\n".
				'</td>'.
			'</tr>'.
		'</table>'.
	'</body>'.
'</html>';