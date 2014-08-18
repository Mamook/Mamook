<?php /* templates/subnavbar.php */

# Check if the user is logged in.
if($login->isLoggedIn()===TRUE)
{
	# Create the logout link.
	$login_link='<li class="list-nav-sub">';
	$login_link.='<a href="'.REDIRECT_TO_LOGIN.'logout/" id="sublink2"'.Document::addHereClass(REDIRECT_TO_LOGIN.'logout/').' title="Login">Logout</a>';
	$login_link.='</li>';
	# Create the link to the user's admin pages.
	$login_link.='<li class="list-nav-sub">';
	if($login->checkAccess(ALL_ADMIN_MAN)===TRUE)
	{
		$login_link.='<a href="'.ADMIN_URL.'" id="sublink2a"'.Document::addHereClass(ADMIN_URL).' title="Admin">Admin</a>';
	}
	else
	{
		$login_link.='<a href="'.SECURE_URL.'MyAccount/profile.php"'.Document::addHereClass(SECURE_URL.'MyAccount/').' title="MyAccount">MyAccount</a>';
	}
	$login_link.='</li>';

	# Display the subnavbar.
	echo '<nav id="subnavbar" class="nav subnav">',
		'<ol>',
			$login_link,
		'</ol>',
	'</nav>';
}