<?php /* framework/application/templates/secure_navbar.php */

# Check if the User is logged in.
$login->checkLogin(ALL_USERS);

echo '<nav id="navbar" class="nav nav1">',
	'<ol>',
		'<li class="list-nav-1">',
			'<a href="',APPLICATION_URL,'" title="Home">Home</a>',
		'</li>',
		'<li class="list-nav-1', ((strstr(FULL_URL, 'MyAccount/')!== FALSE) ? '' : ' hover'), Document::addHereClass(SECURE_URL.'MyAccount/', FALSE, FALSE), '">',
			'<a href="', SECURE_URL ,'MyAccount/" title="My Account">My Account</a>',
			'<ul class="nav-2">',
				'<li class="list-nav-2',Document::addHereClass(SECURE_URL.'MyAccount/', TRUE, FALSE),'">',
					'<a href="',SECURE_URL,'MyAccount/" title="Update Profile">Update Profile</a>',
				'</li>',
				# Check if logged in user is in the `staff` table.
				(($login->isStaff()===TRUE) ?
				'<li class="list-nav-2'.Document::addHereClass(SECURE_URL.'MyAccount/staff_profile.php', FALSE, FALSE).'">'.
					'<a href="'.SECURE_URL.'MyAccount/staff_profile.php" title="Update Staff Profile">Update Staff Profile</a>'.
				'</li>' : ''),
				'<li class="list-nav-2', Document::addHereClass(SECURE_URL.'MyAccount/change_username.php', FALSE, FALSE), '">',
					'<a href="', SECURE_URL, 'MyAccount/change_username.php" title="Change Username">Change Username</a>',
				'</li>',
				'<li class="list-nav-2', Document::addHereClass(SECURE_URL.'MyAccount/change_password.php', FALSE, FALSE), '">',
					'<a href="', SECURE_URL, 'MyAccount/change_password.php" title="Change Password">Change Password</a>',
				'</li>',
				'<li class="list-nav-2', Document::addHereClass(SECURE_URL.'MyAccount/authorizations.php', FALSE, FALSE), '">',
					'<a href="', SECURE_URL, 'MyAccount/authorizations.php" title="Request Authorization">Authorizations</a>',
				'</li>',
				'<li class="list-nav-2', Document::addHereClass(SECURE_URL.'MyAccount/delete.php', FALSE, FALSE), '">',
					'<a href="', SECURE_URL, 'MyAccount/delete.php" title="Delete Account">Delete Account</a>',
				'</li>',
				'<li class="list-nav-2', Document::addHereClass(SECURE_URL.'MyAccount/privacy.php', FALSE, FALSE), '">',
					'<a href="', SECURE_URL, 'MyAccount/privacy.php" title="Privacy Settings">Privacy Settings</a>',
				'</li>',
			'</ul>',
		'</li>';
	if($login->checkAccess(ALL_ADMIN_MAN)===TRUE)
	{
		echo '<li class="list-nav-1', (((strstr(FULL_URL, 'ManageUsers/')!==FALSE)) ? '' : ' hover'), Document::addHereClass(ADMIN_URL.'ManageUsers/', FALSE, FALSE), '">',
			'<a href="', ADMIN_URL, 'ManageUsers/" title="Manage Users">Manage Users</a>',
			(((strstr(GET_QUERY, '?user=')===FALSE) && $login->checkAccess(ADMIN_USERS)===FALSE) ? '' :
			'<ul class="nav-2">'),
			(((strstr(FULL_URL, 'ManageUsers/')!==FALSE) && (strstr(GET_QUERY, '?user=')!==FALSE)) ?
				'<li class="list-nav-2 hover'.Document::addHereClass(ADMIN_URL.'ManageUsers/', FALSE, FALSE).'">'.
					((isset($current_username)) ?
						'<a href="'.ADMIN_URL.'ManageUsers/?user='.$_GET['user'].'" title="Update '.$current_username.'">'.$current_username.'</a>'
					: '').
					'<ul class="nav-3">'.
						(($login->checkAccess(ADMIN_USERS)===TRUE) ?
						'<li class="list-nav-3'.Document::addHereClass(ADMIN_URL.'ManageUsers/?user='.$_GET['user'], TRUE, FALSE).'">'.
							'<a href="'.ADMIN_URL.'ManageUsers/?user='.$_GET['user'].'" title="Update User">Update User</a>'.
						'</li>'.
						# Check if logged in user is in the `staff` table.
						(($login->isStaff($_GET['user'])===TRUE) ?
						'<li class="list-nav-2'.Document::addHereClass(ADMIN_URL.'ManageUsers/ManageStaff/index.php', FALSE, FALSE).'">'.
							'<a href="'.ADMIN_URL.'ManageUsers/ManageStaff/index.php'.GET_QUERY.'" title="Update Staff Profile">Update Staff Profile</a>'.
						'</li>' : '').
						'<li class="list-nav-3'.Document::addHereClass(ADMIN_URL.'ManageUsers/change_username.php', FALSE, FALSE).'">'.
							'<a href="'.ADMIN_URL.'ManageUsers/change_username.php'.GET_QUERY.'" title="Change Username">Change Username</a>'.
						'</li>'.
						'<li class="list-nav-3'.Document::addHereClass(ADMIN_URL.'ManageUsers/change_password.php', FALSE, FALSE).'">'.
							'<a href="'.ADMIN_URL.'ManageUsers/change_password.php'.GET_QUERY.'" title="Change Password">Change Password</a>'.
						'</li>' : '').
						'<li class="list-nav-3'.Document::addHereClass(ADMIN_URL.'ManageUsers/authorize_user.php', FALSE, FALSE).'">'.
							'<a href="'.ADMIN_URL.'ManageUsers/authorize_user.php'.GET_QUERY.'" title="Authorize User">Authorize User</a>'.
						'</li>'.
						(($login->checkAccess(ADMIN_USERS)===TRUE) ?
						'<li class="list-nav-3'.Document::addHereClass(ADMIN_URL.'ManageUsers/delete_user.php', FALSE, FALSE).'">'.
							'<a href="'.ADMIN_URL.'ManageUsers/delete_user.php'.GET_QUERY.'" title="Delete User">Delete User</a>'.
						'</li>'.
						'<li class="list-nav-3'.Document::addHereClass(ADMIN_URL.'ManageUsers/privacy.php', FALSE, FALSE).'">'.
							'<a href="'.ADMIN_URL.'ManageUsers/privacy.php'.GET_QUERY.'" title="Privacy Settings">Privacy Settings</a>'.
						'</li>' : '').
					'</ul>'.
				'</li>' : '').
				(($login->checkAccess(ADMIN_USERS)===TRUE) ?
				'<li class="list-nav-2'.Document::addHereClass(ADMIN_URL.'ManageUsers/EmailUsers/', FALSE, FALSE).'">'.
					'<a href="'.ADMIN_URL.'ManageUsers/EmailUsers/" title="Email Users">Email Users</a>'.
				'</li>' : ''),
				(($login->checkAccess(ADMIN_USERS)===TRUE) ?
				'<li class="list-nav-2'.Document::addHereClass(ADMIN_URL.'ManageUsers/NewsletterSubscribers/', FALSE, FALSE).'">'.
					'<a href="'.ADMIN_URL.'ManageUsers/NewsletterSubscribers/" title="Newsletter Subscribers">Newsletter Subscribers</a>'.
				'</li>' : ''),
			(((strstr(GET_QUERY, '?user=')===FALSE) && $login->checkAccess(ADMIN_USERS)===FALSE) ? '' :
			'</ul>'),
		'</li>';
	}
	if($login->checkAccess(ALL_BRANCH_USERS)===TRUE)
	{
		echo '<li class="list-nav-1', (((strstr(FULL_URL, 'ManageContent/')!==FALSE)) ? '' : ' hover'), Document::addHereClass(ADMIN_URL.'ManageContent/', FALSE, FALSE),'">',
			'<a href="', ADMIN_URL,'ManageContent/" title="Manage Content">Manage Content</a>';
			require Utility::locateFile(TEMPLATES.'content_nav.php');
		echo '</li>';
	}

	if($login->checkAccess(MAN_USERS)===TRUE)
	{
		echo '<li class="list-nav-1', (((strstr(FULL_URL, 'ManageMedia/')!==FALSE)) ? '' : ' hover'), Document::addHereClass(ADMIN_URL.'ManageMedia/', FALSE, FALSE), '">',
			'<a href="', ADMIN_URL, 'ManageMedia/" title="Manage Media">Manage Media</a>',
				'<ul class="nav-2">',
					'<li class="list-nav-2', Document::addHereClass(ADMIN_URL.'ManageMedia/audio/', FALSE, FALSE), '">',
						'<a href="', ADMIN_URL, 'ManageMedia/audio/" title="Manage Audio">Audio</a>',
					'</li>',
					'<li class="list-nav-2', Document::addHereClass(ADMIN_URL.'ManageMedia/files/', FALSE, FALSE), '">',
						'<a href="', ADMIN_URL, 'ManageMedia/files/" title="Manage Files">Files</a>',
					'</li>',
					'<li class="list-nav-2', Document::addHereClass(ADMIN_URL.'ManageMedia/images/', FALSE, FALSE), '">',
						'<a href="', ADMIN_URL, 'ManageMedia/images/" title="Manage Images">Images</a>',
					'</li>',
					'<li class="list-nav-2', Document::addHereClass(ADMIN_URL.'ManageMedia/videos/', FALSE, FALSE), '">',
						'<a href="', ADMIN_URL, 'ManageMedia/videos/" title="Manage Video">Videos</a>',
					'</li>',
				'</ul>',
			'</li>',
			'<li class="list-nav-1', (((strstr(FULL_URL, 'Logs/')!==FALSE)) ? '' : ' hover'), Document::addHereClass(ADMIN_URL.'Logs/', FALSE, FALSE), '">',
			'<a href="', ADMIN_URL, 'Logs/" title="Logs">Logs</a>',
				'<ul class="nav-2">',
					'<li class="list-nav-2', Document::addHereClass(ADMIN_URL.'Logs/?log='.COMMAND_LINE_LOG, TRUE, FALSE), '">',
						'<a href="', ADMIN_URL, 'Logs/?log=',COMMAND_LINE_LOG,'" title="Command Line Log">Command Line Log</a>',
					'</li>',
					'<li class="list-nav-2', Document::addHereClass(ADMIN_URL.'Logs/?log='.CRON_LOG, TRUE, FALSE), '">',
						'<a href="', ADMIN_URL, 'Logs/?log=',CRON_LOG,'" title="Cron Log">Cron Log</a>',
					'</li>',
					'<li class="list-nav-2', Document::addHereClass(ADMIN_URL.'Logs/?log='.CHANGELOG, TRUE, FALSE), '">',
						'<a href="', ADMIN_URL, 'Logs/?log=',CHANGELOG,'" title="Changelog">Changelog</a>',
					'</li>',
				'</ul>',
			'</li>';
	}

	if(GOOGLE_CLIENT_ID!="" && ($login->checkAccess(GAPPS_USERS)===TRUE))
	{
		echo '<li class="list-nav-1">',
			'<a href="http://'.GOOGLE_APPS_DRIVE.'" title="Go to your Google Drive" target="_blank">Documents</a>',
		'</li>',
		'<li class="list-nav-1">',
			'<a href="http://'.GOOGLE_APPS_MAIL.'" title="Go to your Web Mail" target="_blank">eMail</a>',
		'</li>',
		'<li class="list-nav-1">',
			'<a href="http://'.GOOGLE_APPS_CALENDAR.'" title="Go to your Google Calendar" target="_blank">Calendar</a>',
		'</li>',
		'<li class="list-nav-1">',
			'<a href="http://'.GOOGLE_APPS_TALK.'" title="Go to your Google Talk/Hangouts" target="_blank">Talk/Hangouts</a>',
		'</li>';
	}

		echo '<li class="list-nav-1', Document::addHereClass(APPLICATION_URL.'contact/', FALSE, FALSE), '">',
			'<a href="', APPLICATION_URL, 'contact/" title="Contact '.DOMAIN_NAME.'">Contact</a>',
		'</li>',
	'</ol>',
'</nav>';