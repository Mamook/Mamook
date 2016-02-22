<?php /* framework/application/templates/masthead.php */
echo '
<!--[if lt IE 7]><div class="banner"><a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home" title="You are using an outdated browser. For a faster, safer browsing experience, upgrade Internet Explorer today." target="_blank"><img src="',THEME,'images/old_IE_warning.jpg" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade Internet Explorer today." /></a></div><![endif]-->
<!--[if (gt IE 6)&(lt IE 10)]><div class="banner"><a href="http://www.theie9countdown.com/ie-users-info" title="You are using Internet Explorer. This site doesn\'t work as well with Internet Explorer. Try a different browser." target="_blank"><img src="',THEME,'images/no_IE_warning.png" alt="You are using Internet Explorer. This site doesn\'t work as well with Internet Explorer. Try a different browser." /></a></div><![endif]-->
<header id="masthead" class="masthead" role="banner">',
	$theme->displayMasthead(),
'</header>';