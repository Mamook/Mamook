<?php /* applications/views/secure/MyAccount/change_username.php */

echo '<section id="main" class="main secure username">',
	$display_content;
	# Get the change_username form.
	require TEMPLATES.'forms/change_username.php';
	echo $display_username_form,
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