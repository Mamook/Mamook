<?php /* application/views/SiteMap/index.php */

echo '<section id="main" class="main">',
	'<div class="main-1"></div>',
	'<div class="main-2">',
		$display_content;
		require ROOT_PATH.DS.'SiteMap'.DS.'gwsitemap.php';
		echo $display_quote,
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

'<section id="menu2" class="box2">',
'</section>';
