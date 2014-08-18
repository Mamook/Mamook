<?php /* applications/views/secure/MyAccount/privacy.php */

echo '<section id="main" class="main secure privacy">',
	$display_content;
	require TEMPLATES.'forms'.DS.'privacy.php';
	echo $display_privacy_form,
'</section>',

'<section id="box1" class="box1">',
	'<div id="box1a">',
	'</div>',
	'<div id="box1b">',
	'</div>',
	'<div id="box1c">',
	'</div>',
'</section>',

'<section id="menu2" class="box2">',
'</section>';