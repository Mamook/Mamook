<?php /* framework/application/controllers/secure/admin/ManageUsers/NewsletterSubscribers/index.php */

# Get the PDF Class.
require_once Utility::locateFile(MODULES.'PDF'.DS.'PDF.php');

$login->checkLogin(ADMIN_USERS);

$page_class='manageUserspage-newslettersubscribers';

$login->findUserData();

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';

# Get the users that are opted-in to receive the newsletter.
$users_newsletter=$db->get_results('SELECT INET6_NTOA(`ip`) AS ip, `date` FROM `user_newsletter`');
# If there are subscribers...
if($users_newsletter)
{
	$display.='<a href="'.APPLICATION_URL.Utility::removeIndex(HERE).'?generate_pdf" target="_blank">Generate PDF File</a>'.
	'<table>'.
		'<tbody>'.
			'<tr>'.
				'<th>'.
					'<strong>IP</strong>'.
				'</th>'.
				'<th>'.
					'<strong>DATE</strong>'.
				'</th>'.
			'</tr>';
			# Loop through the data.
			foreach($users_newsletter as $user_data)
			{
				$display.=
			'<tr>'.
				'<td>'.
					$user_data->ip.
				'</td>'.
				'<td>'.
					$user_data->date.
				'</td>'.
			'</tr>';
			}
		$display.=
		'</tbody>'.
	'</table>';
}
# If there is no subscribers...
else
{
	$display.='There are no subscribers';
}

# If the "Generate PDF File" link was clicked.
if(isset($_GET['generate_pdf']))
{
	# Instantiate a new PDF object.
	$pdf=new PDF();
	$title=DOMAIN_NAME.' Newsletter Subscribers';
	$pdf->SetTitle($title);
	# Defines an alias for the total number of pages.
	#	It will be substituted as the document is closed.
	$pdf->AliasNbPages();
	# Add a PDF page.
	$pdf->AddPage();
	# Set the font to use for the haders in the PDF file.
	$pdf->SetFont('Arial', 'B', 12);
	# Table Headers.
	$pdf->Cell(90, 12, 'IP', 1);
	$pdf->Cell(90, 12, 'DATE', 1);
	# Set the font to use for the table body in the PFF file.
	$pdf->SetFont('Arial', '', 12);
	# Loop through the data.
	foreach($users_newsletter as $user_data)
	{
		# Line break.
		$pdf->Ln();
		# Table body data.
		$pdf->Cell(90, 12, $user_data->ip, 1);
		$pdf->Cell(90, 12, $user_data->date, 1);
	}
	# Output data to the PDF file.
	$pdf->Output('I', DOMAIN_NAME.'_Newsletter_Subscribers.pdf');
}

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