<?php /* application/views/webSupport/index.php */

echo '<section id="main" class="main contact">',
	'<div class="main-1"></div>',
	'<div class="main-2">',
		$display_content,
		$display_quote,
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
require TEMPLATES.'webSupport_nav.php';
echo '</section>';