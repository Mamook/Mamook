<?php /* framework/application/templates/subnavbar.php */

# Create the login link.
$login_link='<li class="list-nav-1'.Document::addHereClass(REDIRECT_TO_LOGIN, TRUE, FALSE).'">';
$login_link.='<a href="'.REDIRECT_TO_LOGIN.'"'.Document::addHereClass(REDIRECT_TO_LOGIN, TRUE, FALSE).' class="link-login" title="Login">Login</a>';
$login_link.='</li>';
# Check if the user is logged in.
if($login->isLoggedIn()===TRUE)
{
	# Create the logout link.
	$login_link='<li class="list-nav-1'.Document::addHereClass(REDIRECT_TO_LOGIN.'logout/', FALSE, FALSE).'">';
	$login_link.='<a href="'.REDIRECT_TO_LOGIN.'logout/"'.Document::addHereClass(REDIRECT_TO_LOGIN.'logout/', FALSE, FALSE).' class="link-logout" title="Logout">Logout</a>';
	$login_link.='</li>';
	# Create the link to the user's admin pages.
	if($login->checkAccess(ALL_ADMIN_MAN)===TRUE)
	{
		$login_link.='<li class="list-nav-1'.Document::addHereClass(ADMIN_URL, FALSE, FALSE).'">';
		$login_link.='<a href="'.ADMIN_URL.'"'.Document::addHereClass(ADMIN_URL).' class="link-admin" title="Admin">Admin</a>';
	}
	else
	{
		$login_link.='<li class="list-nav-1'.Document::addHereClass(SECURE_URL.'MyAccount/', FALSE, FALSE).'">';
		$login_link.='<a href="'.SECURE_URL.'MyAccount/"'.Document::addHereClass(SECURE_URL.'MyAccount/').' class="link-myaccount" title="MyAccount">MyAccount</a>';
	}
	$login_link.='</li>';
}

# Check if social network buttons are enabled on the current page.
$social_item=$main_content->displaySocial();
if(!empty($social_item))
{
	# Create the social list item.
	$social_item='<li class="list-nav-1 social-buttons">'.$social_item.'</li>';
}

# Display the subnavbar.
echo '<nav id="subnavbar" class="nav subnav">',
	'<ol class="nav-1">',
		$login_link,
		$social_item,
	'</ol>',
'</nav>';