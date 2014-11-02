<?php /* templates/subnavbar.php */

# Check if the user is logged in.
if($login->isLoggedIn()===TRUE)
{
	# Create the logout link.
	$login_link='<li class="list-nav-1'.Document::addHereClass(REDIRECT_TO_LOGIN.'logout/', FALSE, FALSE).'">';
	$login_link.='<a href="'.REDIRECT_TO_LOGIN.'logout/"'.Document::addHereClass(REDIRECT_TO_LOGIN.'logout/').' title="Logout">Logout</a>';
	$login_link.='</li>';
	# Create the link to the user's admin pages.
	if($login->checkAccess(ALL_ADMIN_MAN)===TRUE)
	{
		$login_link.='<li class="list-nav-1'.Document::addHereClass(ADMIN_URL, FALSE, FALSE).'">';
		$login_link.='<a href="'.ADMIN_URL.'"'.Document::addHereClass(ADMIN_URL).' title="Admin">Admin</a>';
	}
	else
	{
		$login_link.='<li class="list-nav-1'.Document::addHereClass(SECURE_URL.'MyAccount/', FALSE, FALSE).'">';
		$login_link.='<a href="'.SECURE_URL.'MyAccount/"'.Document::addHereClass(SECURE_URL.'MyAccount/').' title="MyAccount">MyAccount</a>';
	}
	$login_link.='</li>';

	# Display the subnavbar.
	echo '<nav id="subnavbar" class="nav subnav">',
		'<ol class="nav-1">',
			$login_link,
		'</ol>',
	'</nav>';
}