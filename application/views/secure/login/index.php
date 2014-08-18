<?php /* application/views/secure/login/index.php */

echo '<section id="main" class="main secure login">',
	'<div class="main-1"></div>',
	'<div class="main-2">',
		$display_content,
		$display_register;
		require TEMPLATES.'forms'.DS.'login.php';
echo '</div>',
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

'<section id="menu2" class="box2">',
'</section>';