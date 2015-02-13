<?php /* public/search/index.php */

# Get the FormGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
# Get the Search Class.
require_once Utility::locateFile(MODULES.'Search'.DS.'Search.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$page_class='searchpage';

$search=new Search();

$head='<p class="h-form">Use the form below to search the site.</p>';

# Get the search form.
require_once Utility::locateFile(TEMPLATES.'forms'.DS.'search_form.php');

$subcontent_fields=array('title', 'text', 'text_trans');
//$display_search.=$search->displayPageResults(array('page_title', 'sub_title', 'content'), $and_sql='`sub_domain` = '.$db->quote($db->escape('NULL')), $order='page', $direction='DESC', $limit=NULL);

$search_count='';
if(array_key_exists('_submit_check', $_POST))
{
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