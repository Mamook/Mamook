<?php
# Define the location of this page.
define('HERE_PATH', 'phpinfo.php');

# Get the settings file so anything set in the app will show up in the info.
require_once '../settings.php';

# If the settings file is NOT included, set up the timezone.
if(!defined('TIMEZONE'))
{
	# Define the timezone.
	define('TIMEZONE','US/Pacific');
	# First, the timezone.
	putenv('TZ='.TIMEZONE);
	date_default_timezone_set(TIMEZONE);
}

$display='';
$php_info='';

# Get just the general php info.
ob_start();
phpinfo(1);
$php_info_general = ob_get_contents();
ob_clean();
# Get just the php credit info.
ob_start();
phpinfo(2);
$php_info_credits = ob_get_contents();
ob_clean();
# Get just the php configuration info.
ob_start();
phpinfo(4);
$php_info_configuration = ob_get_contents();
ob_clean();
# Get just the php module info.
ob_start();
phpinfo(8);
$php_info_modules = ob_get_contents();
ob_clean();
# Get just the php environment info.
ob_start();
phpinfo(16);
$php_info_environment = ob_get_contents();
ob_clean();
# Get just the php variable info.
ob_start();
phpinfo(32);
$php_info_variables = ob_get_contents();
ob_clean();
# Get just the php license info.
ob_start();
phpinfo(64);
$php_info_license = ob_get_contents();
ob_clean();

# Uncomment the following lines to put php info into a single display variable.
#$php_info.=$php_info_general;
#$php_info.=$php_info_credits;
#$php_info.=$php_info_configuration;
#$php_info.=$php_info_modules;
#$php_info.=$php_info_environment;
#$php_info.=$php_info_variables;
#$php_info.=$php_info_license;

# Set required extensions.
$required_extensions=array(
	'gd',				// graphics library
	'xml',			// xml
	'mysql',		// database
	'curl',			// networking
	'openssl',	// site will need SSL
	'pecl'			// pear
);
natcasesort($required_extensions);

# Get loaded modules.
$loaded_extensions=get_loaded_extensions();

# Analyze results.
foreach($required_extensions as $ext)
{
	if(in_array($ext, $loaded_extensions))
	{
	 $matches[]=strtolower($ext);
	}
	else
	{
	 $missings[]=strtolower($ext);
	}
	unset($loaded_extensions[$ext]);
}
natcasesort($matches);
if(count($missings))
{
	natcasesort($missings);
}
natcasesort($loaded_extensions);

$display_extension_info='';
foreach($matches as $match)
{
	$display_extension_info.='<tr>'.
		'<td class="e">'.
			$match.
		'</td>'.
		'<td class="v found">'.
			'found!'.
		'</td>'.
	'</tr>';
}
if(count($missings))
{
	foreach($missings as $miss)
	{
		$display_extension_info.='<tr>'.
		'<td class="e">'.
			$miss.
		'</td>'.
		'<td class="v miss">'.
			'missing!'.
		'</td>'.
	'</tr>';
	}
}
foreach($loaded_extensions as $extension)
{
	if(!in_array($extension, $matches) && !in_array($extension, $missings))
	{
		$display_extension_info.='<tr>'.
		'<td class="e">'.
			$extension.
		'</td>'.
		'<td class="v extra">'.
			'available'.
		'</td>'.
	'</tr>';
	}
}

# Set the page markup to a variable.
$display.='<!DOCTYPE html>'.
'<html xmlns="http://www.w3.org/1999/xhtml">'.
	'<head>'.
		'<title>*AMP Setup Specs</title>'.
		'<meta http-equiv="content-type" content="text/html; charset=utf-8"/>'.
		'<style type="text/css">'.
			'body {font-size:1.2em;}'.
			'.header tr {border:1px solid #666;}'.
			'.header td {border:0;}'.
			'.header h1 {font-size:200%;}'.
			'td.date {text-align:right;}'.
			'.v.found {background:lightgreen;padding:5px 10px;margin:0 0 10px 0;}'.
			'.v.miss {background:pink;padding:5px 10px;margin:0 0 10px 0;}'.
		'</style>'.
	'</head>'.
	'<body>'.
		'<header class="center header">'.
			'<h1>'.$_SERVER['HTTP_HOST'].'</h1>'.
			'<span>'.date('M d, Y').'</span>'.
		'</header>'.
		$php_info_general.
		'<section class="center">'.
			'<h1>General Information</h1>'.
			'<table>'.
				'<tbody>'.
					'<tr>'.
						'<td class="e">'.
							'Server Software'.
						'</td>'.
						'<td class="v">'.
							$_SERVER['SERVER_SOFTWARE'].
						'</td>'.
					'</tr>'.
					'<tr>'.
						'<td class="e">'.
							'Document Root:'.
						'</td>'.
						'<td class="v">'.
							$_SERVER['DOCUMENT_ROOT'].
						'</td>'.
					'</tr>'.
					'<tr>'.
						'<td class="e">'.
							'PHP Version'.
						'</td>'.
						'<td class="v">'.
							phpversion().
						'</td>'.
					'</tr>'.
					'<tr>'.
						'<td class="e">'.
							'PHP as CGI or Module?'.
						'</td>'.
						'<td class="v">'.
							php_sapi_name().((php_sapi_name()=='apache2handler') ? ' (module)' : '').
						'</td>'.
					'</tr>'.
				'</tbody>'.
			'</table>'.
		'</section>'.
		$php_info_configuration.
		'<section class="center">'.
			'<h1>Extension Check</h1>'.
			'<table>'.
				'<tbody>'.
					'<tr class="h">'.
						'<th>Extension</th>'.
						'<th>Status</th>'.
					'</tr>'.
					$display_extension_info.
				'</tbody>'.
			'</table>'.
		'</section>'.
		$php_info_modules.
		$php_info_environment.
		$php_info_variables.
		#$php_info_credits.
		$php_info_license.
	'</body>'.
'</html>';

# Display the page.
echo $display;