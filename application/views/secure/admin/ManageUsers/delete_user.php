<?php /* applications/views/secure/admin/ManageUsers/delete_user.php */

echo '<section id="main" class="main secure">',
	$display_content;
	require TEMPLATES.'forms'.DS.'delete_user.php';
	echo $display_delete_form,
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