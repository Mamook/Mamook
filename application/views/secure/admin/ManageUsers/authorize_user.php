<?php /* applications/views/secure/admin/ManageUsers/authorize_user.php */

echo '<section id="main" class="main secure auth">',
	$display_content,
	'<p>Use the form below to authorize or de-authorize ',$current_username,' to contribute and/or edit content for various aspects or "branches" of ',DOMAIN_NAME,'.</p>';
	# Get the form mail template.
	require TEMPLATES.'forms'.DS.'request_auth.php';
	echo $display_request_auth_form,
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