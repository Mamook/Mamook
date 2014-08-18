<?php /* application/views/policy/dispute.php */

echo '<section id="main" class="main">',
	'<div class="main-1"></div>',
	'<div class="main-2">',
		$display_content,
		'<p>',$main_content->getAddress1(),'<br />',
		$main_content->getAddress2(),'<br />',
		$main_content->getCity(),', '.$main_content->getState(),' ',$main_content->getZipcode(),'</p>',
		'<h3>Our phone number is:</h3><p>USA ',$main_content->getPhone(),'</p>',
		$display,
	'</div>',
	'<div class="main-3"></div>',
'</section>',

'<section id="box1" class="box1">',
	'<div id="box1a">',
	'</div>',
	'<div id="box1b">',
	'</div>',
	'<div id="box1c">',
	'</div>',
'</section>',

'<section id="menu2" class="box2">';
	require TEMPLATES.'policy_nav.php';
echo '</section>';