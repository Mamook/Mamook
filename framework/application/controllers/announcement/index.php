<?php /* public/announcement/index.php */

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

$page_class='announcementpage';
$branch='Announcement';
$branch_nav='';
$display='';
$display_file='';
$display_subcontent='';
$display_main_content='';

# Create a new SubContent object.
$subcontent_obj=new SubContent();
if(isset($_GET['search']) && isset($_SESSION['form']['search']['AllResults']))
{
	# Just a hack... move along.
	$subcontent_obj->setWantedBranches(array(50=>'Announcement'));
	# Set the search results to the all_subcontent data member to be analyzed by displaySubContent().
	$subcontent_obj->setAllSubContent($_SESSION['form']['search']['AllResults']);
	$max_char=242;
	$display_array=$subcontent_obj->displaySubContent($max_char, constant(strtoupper(str_replace(' ', '_', $branch)).'_USERS'), TRUE);
	# Start an unordered list of the "subcontent" class and set it to a variable.
	$display_subcontent='<ul class="post">';
	# Loop through the display subcontent array.
	foreach($display_array as $content)
	{
		# Create a variable for the hidden class.
		$hidden=(($content['hidden']===NULL) ? NULL : ' class="hide"');
		# Add the post content to the display variable.
		$display_subcontent.='<li'.$hidden.'>';
		# Open the article tag.
		$display_subcontent.='<article>';
		$display_subcontent.='<h1 class="h-1">'.$content['title'].'</h1>';
		$display_subcontent.=$content['date'];
		if($hidden!==NULL)
		{
			$display_subcontent.='<p class="hide">This post is hidden.</p>';
		}
		$display_subcontent.=$content['text'];
		$display_subcontent.=$content['text_trans'];
		$display_subcontent.=$content['more'];
		$display_subcontent.=$content['edit'];
		$display_subcontent.=$content['delete'];
		$display_subcontent.=$content['download'];
		# Close the article tag.
		$display_subcontent.='</article>';
		$display_subcontent.='</li>';
	}
	# Close the unordered list.
	$display_subcontent.='</ul>';
}
else
{
	# Get the branch subcontent display and set it to a variable.
	$display_subcontent=$subcontent_obj->displayBranchSubContent($branch);
	# Get the main content to display. The "image_link" variable is defined in data/init.php. This should ONLY display if the page is NOT displaying a single post.
	$display_main_content=$main_content->displayContent($image_link);
}

# Check if this is a list of a specific time range.
if(isset($_GET['year']))
{
	$main_content->setSubTitle($_GET['year']);
}

# Check if this is a single post.
if(isset($_GET['post']))
{
	# Set the page title to the post's title.
	$page_title=$subcontent_obj->getPostTitleDisplay();
	if(!empty($page_title))
	{
		$main_content->setPageTitle($page_title);
	}
	# Set the page subtitle to the post's subtitle.
	$subtitle=$subcontent_obj->getSubTitle();
	if(!empty($subtitle))
	{
		$main_content->setSubTitle($subtitle);
	}
	# Since this is a single post, clear the previously set content.
	$display_main_content='';
}

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$display_main_content;
# Add the subcontent to main-2.
$display_main2.=$display_subcontent;
# Add any display content to main-2.
$display_main2.=$display;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Display the file info in box1a.
$display_box1a.=$display_file;

# Get the announcement navigation list.
require Utility::locateFile(TEMPLATES.'announcement_nav.php');
# Set the "branch_nav" variable from the announcement_nav template to the display_box2 variable for display in the view.
$display_box2.=$branch_nav;

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