<?php /* public/SiteMap/index.php */

# Get SiteMap class.
require_once Utility::locateFile(MODULES.'SiteMap'.DS.'SiteMap.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$display='';
$page_class='sitemappage';

# Create a new SubContent object
$site_map=new SiteMap();

# Get the sitemap script.
require Utility::locateFile(MODULES.'SiteMap'.DS.'gwsitemap.php');

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add any display content to main-2.
$display_main2.=$display;
# Set the "sitemap_display" variable from the gwsitemap script to the display_main2 variable for display in the view.
$display_main2.=$sitemap_display;

# Get the quote text to display in main-3.
$display_main3.=$main_content->displayQuote();

# Do we need some more CSS?
$doc->setStyle(THEME.'css/sitemap.css');
# Do we need some javascripts? (Use the script file name before the ".js".)
$doc->setJavaScripts('sitemap');

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