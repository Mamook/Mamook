<?php /* templates/page.php */

$display_content=$main_content->displayContent($image_link);
$display_quote=$main_content->displayQuote();

if($main_content->getArchive()===NULL)
{
	/*
	 ** In the header template we
	 ** set DOCTYPE
	 ** open html tag
	 ** open head tag
	 ** title tags
	 ** meta tags
	 ** get CSS (web stantards, IE, IE6, and IE5 for Mac, etc.)
	 ** get Javascripts
	 ** close head tag
	 ** open body tag
	 */
	require TEMPLATES.'header.php';

	# The masthead
	require TEMPLATES.'masthead.php';

	# The SubNavigation
	require TEMPLATES.'subnavbar.php';

	# Navigation bar
	# Check if we are at an Admin page
	if((($validator->isSSL()===TRUE) && (strpos(FULL_URL, WebUtility::removeSchemeName(LOGIN_PAGE))===FALSE)) ||
	(strpos(FULL_DOMAIN.HERE, WebUtility::removeSchemeName(ADMIN_URL))!==FALSE) ||
	((strpos(FULL_DOMAIN.HERE, WebUtility::removeSchemeName(SECURE_URL))!==FALSE) && (strpos(FULL_DOMAIN.HERE, WebUtility::removeSchemeName(LOGIN_PAGE))===FALSE)))
	{
		require TEMPLATES.'secure_navbar.php';
	}
	else
	{
		# Navigation bar
		require TEMPLATES.'navbar.php';
	}

	# Main view
	require VIEWS.Document::findDomainFolder().HERE;

	# Quick Registration Box
	# Check if we are at an Admin page
	/*
	if(($validator->isSSL()!==TRUE))
	{
		require TEMPLATES.'quick_reg_box.php';
	}
	*/

	//require TEMPLATES.'ad_box01.php';

	//require TEMPLATES.'whats_new.php';

	/*
	 ** In the footer template we
	 ** display any relavent text
	 ** include any necessary JavaScripts
	 ** close body tag
	 ** close html tag
	 */
	require TEMPLATES.'footer.php';
}
else
{
	$doc->redirect(ERROR_PAGE.'404.php');
}