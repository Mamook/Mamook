<?php /* public/error/index.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the EmailFormProcessor Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'EmailFormProcessor.php');

# Get the email form default values.
require Utility::locateFile(TEMPLATES.'forms'.DS.'email_form_defaults.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$address='';
$display='';
$get_query=GET_QUERY;
$good_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?success';
$bad_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?mail_error';
$head='<p class="h-form">Please use the form below to send the Webmaster an email.</p>';
$page_class='errorpage';
$recipients='webmaster';

if(isset($_GET['success']))
{
	$get_query='';
	$doc->setError('Thank you for helping make '.DOMAIN_NAME.' better. Your message has been sent to the webmaster. They will look into the issue as soon as they can.');
}

if(isset($_GET['mail_error']))
{
	$get_query='';
	$doc->setError('<h3>There was an error sending you\'re email...</h3>
	Please make sure you entered your name and a valid email address. If it still isn\'t working, rest assured that the webmaster has received an email and will work out the issue as soon as possible. You may try again later. Thanks.');
}

# Instantiate a new FormProcessor object.
$fp=new EmailFormProcessor();
$fp->setFormAction(APPLICATION_URL.WebUtility::removeIndex(HERE).$get_query);
$fp->setUpload(TRUE);

# Get the form mail template.
require Utility::locateFile(TEMPLATES.'forms'.DS.'email_form.php');

# Instantiate a new FormProcessor object.
// 	$fp=new FormProcessor();
// 	$fp->setFormAction(APPLICATION_URL.WebUtility::removeIndex(HERE));
// 	$fp->setUpload(FALSE);
// 	# Get the email form default values.
// 	require Utility::locateFile(TEMPLATES.'forms'.DS.'email_form_defaults.php');
// 	# Process the email form.
// 	$send_to_formmail=$fp->processEmail($default_data);
// 	# Instantiate a new Email object.
// 	$email=$fp->getEmail();
// 	$email->setRecipients('webmaster');
// 	if(isset($_GET['mail']))
// 	{
// 		$recipients=trim(strip_tags($_GET['mail']));
// 		$email->setRecipients($recipients);
// 		$fp->setFormAction(APPLICATION_URL.WebUtility::removeIndex(HERE).'?mail='.$recipients);
// 		$good_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?mail='.$recipients.'&success=yes';
// 		$bad_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?mail='.$recipients.'&mail_error=true';
// 	}
//
// 	# Get the form mail template.
// 	require Utility::locateFile(TEMPLATES.'forms'.DS.'email_form.php');

// # Check if this is the development server.
// 	if(RUN_ON_DEVELOPMENT===TRUE)
// 	{
// 		if(isset($_GET['msg']))
// 		{
// 			$dev_display.='<div class="error_box">';
// 			$dev_display.='<h4>'.$_GET['time'].'</h4>';
// 			$dev_display.='<p>'.$_GET['msg'].'<br />';
// 			$dev_display.='Code: '.$_GET['code'].'</p>';
// 			$dev_display.='<p>File: '.$_GET['file'].'<br />';
// 			$dev_display.='On line: '.$_GET['line'].'</p>';
// 			$dev_display.='<p>URL: '.$_GET['url'].'</p>';
// 			$dev_display.='<p>Referer: '.$_GET['referer'].'</p>';
// 			$error_context=unserialize($_GET['context']);
// 			# Check if the context array is empty.
// 			if(!empty($error_context))
// 			{
// 				$dev_display.='<strong>The context was:</strong>'."\n";
// 				$dev_display.='<ul>'."\n";
// 				$context_display='';
// 				# Loop through the array.
// 				foreach($error_context as $context_array)
// 				{
// 					# Check if the context array is empty.
// 					if(!empty($context_array))
// 					{
// 						# Loop through the context array.
// 						foreach($context_array as $key=>$value)
// 						{
// 							$context_display.='<li>'."\n";
// 							# Check if the key equals "args".
// 							if($key=='args')
// 							{
// 								# Check if the arguments array is empty.
// 								if(!empty($context_array['args']))
// 								{
// 									$context_display.='<ul>'."\n";
// 									$context_display.='<strong>Arguments:</strong>';
// 									$context_display.='<ul>'."\n";
// 									# Loop through the arguments.
// 									foreach($context_array['args'] as $arg_key=>$argument)
// 									{
// 										$context_display.='<li>'."\n";
// 										$context_display.=$arg_key.': '.$argument;
// 										$context_display.='</li>'."\n";
// 									}
// 									$context_display.='</ul>'."\n";
// 								}
// 							}
// 							else
// 							{
// 								$context_display.=$key.': '.$value;
// 							}
// 							$context_display.='</li>'."\n";
// 						}
// 					}
// 				}
// 				$dev_display.=$context_display;
// 				$dev_display.='</ul>'."\n";
// 				$dev_display.='</div>';
// 			}
// 		}
// 	}

# Instantiate a new ExceptionHandler object.
$error_handler=new ExceptionHandler();
# Capture any error sent via GET Data for debugging
$error_handler->captureError();

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
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