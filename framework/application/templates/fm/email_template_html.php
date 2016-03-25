<?php /* framework/application/templates/fm/email_template_html.php */

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
		'<table style="width:100&#37;;font-size:12px;line-height:18px;font-family:Helvetica,Arial,Verdana,sans-serif;color:#444;">'.
			'<tr>'.
				'<td>'.
					'<p>Hello%s</p>'."\n\n\n".
					'<p>'.$message.'</p>'."\n\n\n".
					'Thank you,<br />'."\n".
					$sender_name."\n\n\n\n".
				'</td>'.
			'</tr>'.
		'</table>'.
	'</body>'.
'</html>';