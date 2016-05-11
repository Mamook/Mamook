<?php /* framework/application/templates/page.php */

# Check if the site is in Maintenance mode. If it is, redirect to maintenance page.
if(($main_content->getMaintenance()!==NULL) && (strstr(FULL_URL, 'maintenance')===FALSE))
{
	$doc->redirect(APPLICATION_URL.'maintenance.php');
}
else
{
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
		require Utility::locateFile(TEMPLATES.'header.php');

		# The masthead
		require Utility::locateFile(TEMPLATES.'masthead.php');

		# The SubNavigation
		require Utility::locateFile(TEMPLATES.'subnavbar.php');

		# Search box
		require Utility::locateFile(TEMPLATES.'search.php');

		# Navigation bar
		# Check if we are at an Admin page
		if((($validator->isSSL()===TRUE) && (strpos(FULL_URL, WebUtility::removeSchemeName(LOGIN_PAGE))===FALSE)) ||
		(strpos(FULL_DOMAIN.HERE, WebUtility::removeSchemeName(ADMIN_URL))!==FALSE) ||
		((strpos(FULL_DOMAIN.HERE, WebUtility::removeSchemeName(SECURE_URL))!==FALSE) && (strpos(FULL_DOMAIN.HERE, WebUtility::removeSchemeName(LOGIN_PAGE))===FALSE)))
		{
			require Utility::locateFile(TEMPLATES.'secure_navbar.php');
		}
		else
		{
			# Navigation bar
			require Utility::locateFile(TEMPLATES.'navbar.php');
		}

		# The Breadcrumb
		//require Utility::locateFile(TEMPLATES.'breadcrumb.php');

		# Main view
		require Utility::locateFile(VIEWS.Document::findDomainFolder().HERE);

		# Quick Registration Box
		# Check if we are at an Admin page
		/*
		if(($validator->isSSL()!==TRUE))
		{
			require Utility::locateFile(TEMPLATES.'quick_reg_box.php');
		}
		*/

		//require Utility::locateFile(TEMPLATES.'ad_box01.php');

		//require Utility::locateFile(TEMPLATES.'whats_new.php');

		/*
		 ** In the footer template we
		 ** display any relavent text
		 ** include any necessary JavaScripts
		 ** close body tag
		 ** close html tag
		 */
		require Utility::locateFile(TEMPLATES.'footer.php');
	}
	else
	{
		$doc->redirect(ERROR_PAGE.'404.php');
	}
}