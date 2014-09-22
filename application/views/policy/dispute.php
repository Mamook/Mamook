<?php /* application/views/policy/dispute.php */

echo '<section id="main" class="main">',
	'<div class="main-1">',
		# Get the main content.
		$display_content,
		$address,
	'</div>',
	'<div class="main-2">',
		# Display other content (forms).
		$display,
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
	require TEMPLATES.'policy_nav.php';
echo '</section>';