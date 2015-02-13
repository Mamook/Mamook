<?php /* public/contact/index.php */

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
$head='<p class="h-form">Use the form below to send an email</p>';
$page_class='contactpage';
$meta_desc='Contact form';

### DEBUGGING ###
if(DEBUG_APP===TRUE)
{
	$recipients=ADMIN_EMAIL;
}
else
{
	$recipients='General';
}

if(isset($_GET['success']))
{
	$get_query='';
	$doc->setError("Thank you! We'll be in contact with you soon.");
}
if(isset($_GET['mail_error']))
{
	$get_query='';
	$doc->setError('<h3 class="h-3">There was an error sending your email...</h3>
	Please make sure you entered your name and a valid email address. If it still isn\'t working, rest assured that the webmaster has received an email and will work out the issue as soon as possible. You may try again later. Thanks.');
}

# Instantiate a new EmailFormProcessor object.
$fp=new EmailFormProcessor();
$fp->setFormAction(APPLICATION_URL.WebUtility::removeIndex(HERE).$get_query);
$fp->setUpload(TRUE);

# Get thWebUtility::locateFile(e email form.
require Utility::locateFile(TEMPLATES.'forms'.DS.'email_form.php');

# Check if there is an address to display.
if(
		$main_content->getAddress1()!==NULL OR
		$main_content->getAddress2()!==NULL OR
		$main_content->getCity()!==NULL OR
		$main_content->getState()!==NULL OR
		$main_content->getZipcode()!==NULL OR
		$main_content->getCountry()!==NULL OR
		$main_content->getPhone()!==NULL OR
		$main_content->getFax()!==NULL OR
		$main_content->getEmail()!==NULL
	)
{
	# Set the microformat url for the <link profile> tag in the header.
	$microformat_url='http://microformats.org/profile/hcard';
	$address.='<div class="address">';
	# Create a header for the address.
	$address.='<h3 class="h-3">Address &amp; contact details</h3>';
	# Create the address block.
	$address.='<address class="vcard">';
	# Check if there is a postal address.
	if(
			$main_content->getAddress1()!==NULL OR
			$main_content->getAddress2()!==NULL OR
			$main_content->getCity()!==NULL OR
			$main_content->getState()!==NULL OR
			$main_content->getZipcode()!==NULL
		)
	{
		$address.='<div class="adr">';
		$address.=(($main_content->getAddress1()!==NULL) ? '<div class="street-address">'.$main_content->getAddress1().'</div>' : '');
		$address.=(($main_content->getAddress2()!==NULL) ? '<div class="extended-address">'.$main_content->getAddress2().'</div>' : '');
		$address.=(($main_content->getCity()!==NULL) ? '<span class="locality">'.$main_content->getCity().'</span>' : '');
		$address.=(($main_content->getState()!==NULL) ? '<span class="region">'.$main_content->getState().'</span>' : '');
		$address.=(($main_content->getZipcode()!==NULL) ? '<span class="postal-code">'.$main_content->getZipcode().'</span>' : '');
		$address.=(($main_content->getCountry()!==NULL) ? '<div class="country-name">'.$main_content->getCountry().'</div>' : '');
		# Close the postal address block.
		$address.='</div>';
	}
	$address.=(($main_content->getPhone()!==NULL) ? '<div class="tel">Tel: <a href="tel:'.$main_content->getPhone().'" class="value" title="Call via phone">'.$main_content->getPhone().'</a></div>' : '');
	$address.=(($main_content->getFax()!==NULL) ? '<div class="tel"><span class="type">Fax</span>: <span class="value">'.$main_content->getFax().'</span></div>' : '');
	$address.=(($main_content->getEmail()!==NULL) ? '<div class="email">Email: <a href="mailto:'.$main_content->getEmail().'" class="value" title="send an email">'.$main_content->getEmail().'</a></div>' : '');
	# Close the address block.
	$address.='</address>';
	# Close the address container block.
	$address.='</div>';
}

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add the form display to main-2.
$display_main2.=$display;
# Add the address to main-2.
$display_main2.=$address;

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