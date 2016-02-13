<?php /* framework/application/templates/breadcrumb.php */

# Display the breadcrumb.
echo '<section id="breadcrumb" class="breadcrumb">',
	'<a class="breadcrumb-1" href="',APPLICATION_URL,'">Home</a>',
	'<span class="crumbSeparator"></span>',
	$main_content->getPageTitle(),
'</section>';