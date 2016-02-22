<?php /* framework/application/templates/view.php */

echo '<main id="main" class="main',((empty($display_main1) && empty($display_main2) && empty($display_main3)) ? ' no_content' : ''),'" role="main" tabindex="-1">',
	'<div class="main-1',((empty($display_main1)) ? ' no_content' : ''),'">',
		$display_main1,
	'</div>',
	'<div class="main-2',((empty($display_main2)) ? ' no_content' : ''),'">',
		$display_main2,
	'</div>',
	'<div class="main-3',((empty($display_main3)) ? ' no_content' : ''),'">',
		$display_main3,
	'</div>',
'</main>',

'<section id="box1" class="box1',((empty($display_box1a) && empty($display_box1b) && empty($display_box1c)) ? ' no_content' : ''),'">',
	'<div id="box1a" class="box1-a',((empty($display_box1a)) ? ' no_content' : ''),'">',
		$display_box1a,
	'</div>',
	'<div id="box1b" class="box1-b',((empty($display_box1b)) ? ' no_content' : ''),'">',
		$display_box1b,
	'</div>',
	'<div id="box1c" class="box1-c',((empty($display_box1c)) ? ' no_content' : ''),'">',
		$display_box1c,
	'</div>',
'</section>',

'<section id="box2" class="box2',((empty($display_box2)) ? ' no_content' : ''),'">',
	$display_box2,
'</section>';