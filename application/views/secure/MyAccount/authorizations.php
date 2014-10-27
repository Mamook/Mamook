<?php /* applications/views/secure/MyAccount/authorizations.php */

echo '<main id="main" class="main secure auth" role="main">',
	'<div class="main-1">',
		# Get the main content.
		$display_content,
	'</div>',
	'<div class="main-2">',
		'<p>You may request extended privileges, authorizing you to contribute and/or edit content for various aspects or "branches" of ',DOMAIN_NAME,'.</p>',
		# Display other content (forms).
		$display,
		$display_quote,
	'</div>',
	'<div class="main-3"></div>',
'</main>',

'<section id="box1" class="box1">',
	'<div id="box1a" class="box1-a">',
	'</div>',
	'<div id="box1b" class="box1-b">',
	'</div>',
	'<div id="box1c" class="box1-c">',
	'</div>',
'</section>',

'<section id="box2" class="box2">',
'</section>';