<?php /* application/views/error/index.php */

echo '<section id="main" class="main">',
	'<div class="main-1"></div>',
	'<div class="main-2">',
		$display_content,
		$display,
		# Display the error passed via GET data if this is a development server.
		$dev_display,
	'</div>',
	'<div class="main-3"></div>',
'</section>',

'<div id="box1" class="box1">',
	'<div id="box1a">',
	'</div>',
	'<div id="box1b">',
	'</div>',
	'<div id="box1c">',
	'</div>',
'</div>',

'<div id="menu2" class="box2">',
'</div>';
