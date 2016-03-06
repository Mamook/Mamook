<?php /* framework/application/controllers/social/index.php */

# Get the API Class.
require_once Utility::locateFile(MODULES.'API'.DS.'API.php');

# Create display variables.
$display_main1='';
$display_main2='';
$display_main3='';
$display_box1a='';
$display_box1b='';
$display_box1c='';
$display_box2='';

$social_display='';
$display='';
$page_class='social';

# Number of posts to display.
$num_posts=10;

# Create empty arrays.
$facebook_feed=array();
$twitter_feed=array();

if(
	FB_APP_ID!="" &&
	FB_APP_SECRET!='' &&
	FB_PAGE_ID!='' &&
	FB_PAGE_ACCESS_TOKEN!=''
	)
{
	# Instantiate a new API object.
	$facebook_obj=new API('facebook');
	# Get the Facebook feed.
	$facebook_feed=$facebook_obj->getFeed($num_posts);
}
if(
	TWITTER_CONSUMER_KEY!='' &&
	TWITTER_CONSUMER_SECRET!='' &&
	TWITTER_TOKEN!='' &&
	TWITTER_TOKEN_SECRET!=''
	)
{
	# Instantiate a new API object.
	$twitter_obj=new API('twitter');
	# Get the Twitter feed.
	$twitter_feed=$twitter_obj->getFeed($num_posts);
}

# Combine the social data into a single array and sort by date.
$social_data=array_merge($facebook_feed, $twitter_feed);

# If there is social data.
if(!empty($social_data))
{
	# Sort the data by date.
	$social_data=$facebook_obj->sortByDate($social_data);

	$social_display.='<ul class="post">';
	# Loop through the passed social data.
	foreach($social_data as $data)
	{
		# Set the social network to a variable.
		$network=$data['network'];
		# Check which social network was passed and set the appropriate social network url to a variable.
		switch($data['network'])
		{
			case 'Facebook':
				$url=FB_URL;
				break;
			case 'Twitter':
				$url=TWITTER_URL.'statuses/';
		}
		$social_display.='<li>';
		$social_display.='<article>';
		$social_display.='Posted on <span class="post-date">'.$data['time'].'</span> via <a href="'.$url.$data['id'].'" target="_blank">'.$network.'</a>: ';
		$social_display.=(!empty($data['message']) ? '<span class="entry">'.$data['message'].'</span>' : '');
		$social_display.=(!empty($data['link']) ? '<a href="'.$data['link'].'" title="'.(!empty($data['link_name']) ? $data['link_name'] : $data['link']).'" target="_blank">'.(!empty($data['link_name']) && $data['story']===NULL ? ($data['status_type']=='added_photos' ? '' : $data['link_name']) : (isset($data['story']) ? $data['story'] : $data['link'])).'</a>' : '');
		$social_display.='</article>';
		$social_display.='</li>';
	}
	$social_display.='</ul>';
}

# Get the main image to display in main-1. The "image_link" variable is defined in data/init.php.
$display_main1.=$main_content->displayImage($image_link);
# Get the page title and subtitle to display in main-1.
$display_main1.=$main_content->displayTitles();

# Get the main content to display in main-2.
$display_main2.=$main_content->displayContent();
# Add social content to main-2.
$display_main2.=$social_display;
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