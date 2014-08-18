<?php /* applications/views/secure/admin/ManageUsers/change_password.php */

echo '<section id="main" class="main secure username">',
	$display_content,
	'<h3>Use the form below to  change the password for ',$current_username,'</h3>';
	# Get the form mail template.
	require TEMPLATES.'forms'.DS.'change_password.php';
	echo $display_pasword_form,
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