<?php /* framework/application/templates/header.php */

# Define the DOCTYPE. This site is using html5
$header='<!DOCTYPE html>';

# Open the html tag and define the default language.
$header.='<html prefix="og: http://ogp.me/ns# fog: http://www.facebook.com/2008/fbml fb: http://ogp.me/ns/fb#" lang="'.((!isset($meta_language) OR empty($meta_language)) ? 'en' : $meta_language).'">';
	# Open the head tag.
	$header.='<head>';
		# Define the character set.
		$header.='<meta charset="'.((!isset($charset) OR empty($charset)) ? 'utf-8' : $charset).'">';
		# Set the IE emulation to "edge". Even though Chrome Frame has been discontinued, offer support for those who still have it installed in IE (chrome=1).
		$header.='<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
		# Define the default language. If the $meta_language variable is not set on the page, it defaults to "english".
		$header.='<meta http-equiv="content-language" content="'.((!isset($meta_language) OR empty($meta_language)) ? 'english' : $meta_language).'">';
		# Set the viewport.
		$header.='<meta name="viewport" content="width=device-width, initial-scale=1">';
		# Give a description of the page. If the $meta_desc variable is not set on the page, use this default.
		$header.='<meta name="description" content="'.((!isset($meta_desc) OR empty($meta_desc)) ? 'The official website of the '.$main_content->getSiteName().'.' : $meta_desc).'">';
		# Set keywords for the page. If the $meta_keywords variable is not set on the page, we have none.
		$header.=((!isset($meta_keywords) OR empty($meta_keywords)) ? '' : '<meta name="keywords" content="'.$meta_keywords.'">');
		# Define the author of the page. If the $meta_author variable is not set on the page, use this default.
		$header.='<meta name="author" content="'.((!isset($meta_author) OR empty($meta_author)) ? 'BigTalk Jon Rÿser, JonRyser.com & Michael Delle, michaeldelle.com' : $meta_author).'">';
		# Define the designer of the page. If the $meta_designer variable is not set on the page, use this default.
		$header.='<meta name="designer" content="'.((!isset($meta_designer) OR empty($meta_designer)) ? 'BigTalk Jon Rÿser, JonRyser.com' : $meta_designer).'">';
		# Define the copyright of the page.
		$header.='<meta name="copyright" content="'.((!isset($copyright) OR empty($copyright)) ? '© '.date('Y').' '.$main_content->getSiteName() : $copyright).'">';
		# Define the page-topic of the page. Use the page title.
		$header.='<meta name="page-topic" content="'.((!isset($page_topic) OR empty($page_topic)) ? strip_tags($main_content->getPageTitle()) : $page_topic).'">';
		# Facebook meta data.
		$header.=((defined('FB_APP_ID') && FB_APP_ID!='') ? '<meta property="fb:app_id" content="'.FB_APP_ID.'">' : '');
		$header.='<meta property="og:url" content="'.WebUtility::removeIndex(COMPLETE_URL).'">';
		$header.='<meta property="og:type" content="website">';
		$header.='<meta property="og:title" content="'.strip_tags($main_content->getPageTitle()).'">';
		$header.='<meta property="og:image" content="'.((!isset($og_image) OR empty($og_image)) ? THEME.'images/Facebook.png' : $og_image).'" />';
		$header.='<meta property="og:description" content="'.((!isset($meta_desc) OR empty($meta_desc)) ? 'The official website of the '.$main_content->getSiteName().'.' : $meta_desc).'">';
		$header.='<meta property="og:locale" content="EN_US">';
		$header.='<meta property="og:site_name" content="'.$main_content->getSiteName().'">';
		# The title for each page is filled by a variable set on each page.
		$header.='<title>'.strip_tags($main_content->getPageTitle()).'</title>';
		# Use a custom favicon.
		$header.='<link rel="shortcut icon" type="image/x-icon" href="'.THEME.'images/favicon.ico">';
		# Add a pingback link tag.
		$header.=((!isset($pingback_url) OR empty($pingback_url)) ? '' : '<link rel="pingback" href="'.$pingback_url.'">');
		# Add a profile link tag.
		$header.=((!isset($microformat_url) OR empty($microformat_url)) ? '' : '<link rel="profile" href="'.$microformat_url.'">');

		# Add the CSS for the page.
		$header.=$doc->addStyle();
		# Include IE Style Sheets if that is the user's browser.
		$header.=$doc->addIEStyle('ie8,ie7,ie6,ie5mac');

		# Add a javascript variable that indicates whether the user is on a mobile device or not.
		$header.=$doc->addMobileJavaScriptVariable();

		# Add HTML5Shiv if lower then IE9
		$header.='<!--[if lt IE 9]><script src="'.THEME.'js/html5shiv.min.js"></script><![endif]-->';
		# Add the JavaScripts for the page.
		$header.=$doc->addJavaScript();
		echo $header;

		# If this function exists, WordPress is active.
		if((WP_INSTALLED===TRUE) && function_exists('wp_head'))
		{
			//comments_popup_script(500, 400);
			# We add some JavaScript to pages with the comment form
			#		to support sites with threaded comments (when in use).
			if(is_singular() && get_option('thread_comments'))
				wp_enqueue_script('comment-reply');
			# Always have wp_head() just before the closing </head>
			#		tag of your theme, or you will break many plugins, which
			#		generally use this hook to add elements to <head> such
			#		as styles, scripts, and meta tags.
			wp_head();
		}

	# Close the head tag.
	$header2='</head>';
	$header2.='<body class="'.((!isset($page_class) OR empty($page_class)) ? '' : $page_class.' ').'noscript">';
		$header2.='<div id="distance"></div>';
		$header2.='<div id="wrapper">';
		echo $header2;