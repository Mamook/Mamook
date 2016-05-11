<?php /* framework/application/controllers/search/index.php */

# Get the SubContent Class.
require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';
$page_class='searchpage';
$display_subcontent='';

# Create a new SubContent object.
$subcontent_obj=new SubContent();
if(isset($_SESSION['form']['search']['AllResults']))
{
	$results=$_SESSION['form']['search']['AllResults'];
	$max_char=242;

	# Start an unordered list of the "subcontent" class and set it to a variable.
	$display_subcontent='<ul class="post">';
	# Loop through the display subcontent array.
	foreach($results as $content)
	{
		$subcontent_obj->setDataMembers($content);

		$availability=$subcontent_obj->getAvailability();
		$branch=$subcontent_obj->getRecordBranches();
		# Trim the dashes off the ends of the string.
		$branch_ids=trim($branch, '-');
		# Explode the branch(es) into an array.
		$branch_ids_array=explode('-', $branch_ids);
		# Get the correct URL to link the post to based on the branch and set it to a variable.
		$branch_info=$subcontent_obj->getThisBranch($branch_ids_array[0]);
		$branch_obj=$subcontent_obj->getBranch();
		$domain='http://'.$branch_obj->getDomain();
		$contributor=$subcontent_obj->getContID();
		$date=$subcontent_obj->getDate();
		# Create variable for the file.
		$file=$subcontent_obj->getFile();
		# Set the file's author to a variable.
		$file_author=NULL;
		# Set the file's availability to a variable.
		$file_availability=NULL;
		# Set the file's category to a variable.
		$file_category=NULL;
		# Set the file contributor's id to a variable.
		$file_cont_id=NULL;
		# Set the file's language to a variable.
		$file_language=NULL;
		# Set the file's location to a variable.
		$file_location=NULL;
		# Set the file's publisher to a variable.
		$file_publisher=NULL;
		# Set the file's premium status to a variable.
		$file_premium=NULL;
		# Set the file's title to a variable.
		$file_title=NULL;
		# Set the file's publish year to a variable.
		$file_year=NULL;
		# Check if there is a File object.
		if($file!==NULL)
		{
			# Set the file's name to a variable.
			$file_name=$file->getFile();
			# Set the file's author to a variable.
			$file_author=$file->getAuthor();
			# Set the file's availability to a variable.
			$file_availability=$file->getAvailability();
			# Set the file's category to a variable.
			$file_category=$file->getCategories();
			# Set the file contributor's id to a variable.
			$file_cont_id=$file->getContID();
			# Set the file's language to a variable.
			$file_language=$file->getLanguage();
			# Set the file's location to a variable.
			$file_location=$file->getLocation();
			# Set the file's publisher to a variable.
			$file_publisher=$file->getPublisher();
			# Set the file's premium status to a variable.
			$file_premium=$file->getPremium();
			# Set the file's title to a variable.
			$file_title=$file->getTitle();
			# Set the file's publish year to a variable.
			$file_year=$file->getYear();
		}
		$hide=$subcontent_obj->getHide();
		$id=$subcontent_obj->getID();
		# Check if there is an image id.
		if($subcontent_obj->getImageID()!==NULL)
		{
			# Set this Image object to a variable.
			$image_obj=$subcontent_obj->getImageObj();
			$image_cats=$image_obj->getCategories();
			# Replace any domain name tokens with the current domain name.
			$image_name=str_ireplace('%{domain_name}', DOMAIN_NAME, $image_obj->getImage());
			# Set the displayed image to a variable.
			$image_content=$image_obj->displayImage(TRUE, NULL, NULL);
			# Set the image content to the array.
			$image=$image_content;
		}
		$link=$subcontent_obj->getLink();
		$premium=$subcontent_obj->getPremium();
		$text=$subcontent_obj->getText();
		$text_language_iso=$subcontent_obj->getTextLanguageISO();
		$text_language=$subcontent_obj->getTransLanguage();
		$text_trans=$subcontent_obj->getTextTrans();
		$trans_language=$subcontent_obj->getTransLanguage();
		# Create variable for text translation's language ISO Code.
		$trans_language_iso=$subcontent_obj->getTransLanguageISO();
		$title=$subcontent_obj->getTitle();
		$visibility=$subcontent_obj->getVisibility();

		# Check if the visibility value of this record is 0.
		if($visibility===0)
		{
			# Check if the User is logged in.
			if($login->isLoggedIn()===FALSE)
			{
				# Set the hide value to 0, hiding the record.
				$hide=0;
			}
		}
		# Check if the visibility value of this record is empty.
		elseif(!empty($visibility))
		{
			# Trim any dashes(-) off the ends.
			$visibility=trim($visibility, '-');
			# Replace any dashes(-) with spaces.
			$visibility=str_replace('-', ' ', $visibility);
			# Check if the logged in User has access to view this record.
			if($login->checkAccess('1 '.$visibility)===FALSE)
			{
				# Set the hide value to 0, hiding the record.
				$hide=0;
			}
		}
		# Create a variable to hold whether or not a text translation statment should be displayed in lieu of the actual translation. Default is FALSE.
		$text_trans_statement=FALSE;
		# Check if this record should be hidden.
		if($hide===NULL)
		{
			# Add the post content to the display variable.
			$display_subcontent.='<li>';
			# Open the article tag.
			$display_subcontent.='<article>';
			$display_subcontent.='<h1 class="h-1"><a href="'.$domain.'?post='.$id.'" class="post-title" title="'.str_replace('"', '&quot;', $title).'">'.$title.'</a></h1>';
			# Check if the date value is NULL.
			if($date!='0000-00-00')
			{
				# Convert the date to a timestamp.
				$date=strtotime($date);
				$display_subcontent.='<span class="post-date"><span class="post-month">'.date("F", $date).'</span> <span class="post-day">'.date("d", $date).'</span>, <span class="post-year">'.date("Y", $date).'</span>'.'</span>';
			}

			# Check if a maximum number of characters to be displayed has been passed.
			if($max_char!==NULL)
			{
				# Check if there is text to display.
				if(!empty($text))
				{
					# Check if there is a translation.
					if($text_trans!==NULL)
					{
						$max_char-=30;
					}
					# Check if the length of the title is more than 25 characters.
					# Strip tags from the text and the text translation and see if combined they contain more characters than allotted in the maximum characters variable.
					if(strlen(preg_replace('/<.*?>/', '', $text.$text_trans)) > $max_char)
					{
						# Strip tags from the text and see if it contains more characters than allotted in the maximum characters variable.
						if(strlen(strip_tags($text)) > $max_char)
						{
							# Ensure that percent signs (%) aren't interpreted as type specifiers by sprintf. Do this BEFORE the actual type specifier is added to the truncated text.
							$text=str_replace('%', '&percnt;', $text);
							# Use truncate from the Document class to truncate the text.
							$text=WebUtility::truncate($text, $max_char, '&hellip;%1$s', TRUE, FALSE, NULL);
							# Add a "more" link to the text.
							$text=sprintf($text, ' <a class="more" href="'.$domain.'?post='.$id.'" title="more on: '.str_replace('"', '`', $title).'">'.$subcontent_obj->getMore().'</a>');
						}
						# Set the $text_trans_statement value to TRUE.
						$text_trans_statement=TRUE;
					}
				}
			}
			# Check if there is text to display.
			if(!empty($text))
			{
				# Set the text to a variable.
				$text_content='<div class="entry"'.((!empty($text_language_iso)) ? ' lang="'.$text_language_iso.'"' : '').'>';
				$text_content.=$text;
				$text_content.='</div>';
				# Set the text content to the array.
				$display_subcontent.=$text_content;
			}

			# Check if there is a text translation.
			if(!empty($text_trans))
			{
				# Check if the $text_trans_statement variable equals TRUE. If it does, then a max number of characters in the text has been reached and the translation will be too long to display.
				if($text_trans_statement===TRUE)
				{
					# Set the text translation to a variable.
					$text_trans_content='<div class="entry-trans">';
					# Set the text translation statment instead of the actual translation.
					$text_trans_content.='Translation to '.$trans_language.' available!';
					$text_trans_content.='</div>';
					# Set the text translation content to the array.
					$display_subcontent.=$text_trans_content;
				}
				else
				{
					# Set the text translation to a variable.
					$text_trans_content='<div class="entry-trans">';
					$text_trans_content.='<span class="label">Translated to '.$trans_language.':</span>';
					$text_trans_content.='<span lang="'.$trans_language_iso.'">'.$text_trans.'</span>';
					$text_trans_content.='</div>';
					# Set the text translation content to the array.
					$display_subcontent.=$text_trans_content;
				}
			}

			# Check if there is a link.
			if(!empty($link))
			{
				# Replace the link with the button.
				$more_content='<a href="'.$link.'" class="button-more" target="_blank" title="Read More">More</a>';
				# Set the more content to the array.
				$display_subcontent.=$more_content;
			}

			# Check if there is a file and it should be displayed.
			if($file!==NULL)
			{
				# Check if the User is an admin user.
				if($login->checkAccess(ADMIN_USERS)===TRUE)
				{
					# Set the availability to 1(Yes, display) for this user. An admin may see anything.
					$file_availability=1;
				}
				# Check if the User is a managing user.
				elseif($login->checkAccess(MAN_USERS)===TRUE)
				{
					# Check if the files availability is 2(Internal document only).
					if($file_availability===2)
					{
						# Set the availability to 1(Yes, display) for this user.
						$file_availability=1;
					}
				}
				# Check if the file's availability is 1(Yes, display).
				if($file_availability===1)
				{
					# Set the download button to a variable.
					$download_content='<a href="'.APPLICATION_URL.'download/?f='.$file_name.(($premium===NULL) ? '' : '&amp;t=premium').'" class="button-download" title="Download '.str_replace('"', '`', $file_title).'">Download</a>';
					# Set the delete content to the array.
					$display_subcontent.=$download_content;
				}
			}

			# Close the article tag.
			$display_subcontent.='</article>';
			$display_subcontent.='</li>';
		}
	}
	# Close the unordered list.
	$display_subcontent.='</ul>';
}

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add the subcontent to main-2.
$display_main2.=$display_subcontent;
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