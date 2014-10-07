<?php /* templates/navbar.php */
echo '<nav id="navbar" class="nav1">',
	'<ol>',
		'<li class="list-nav-1',(($doc->removeIndex(FULL_URL)===DOMAIN_NAME.'/') ? '' : ' hover'),Document::addHereClass(APPLICATION_URL, TRUE, FALSE),'">',
			'<a href="',APPLICATION_URL, '" title="Home">home</a>',
		'</li>',
		'<li class="list-nav-1',((strstr(FULL_URL, 'store')!==FALSE) ? '' : ' hover'),Document::addHereClass(APPLICATION_URL.'store/', FALSE, FALSE),'">',
			'<a href="',APPLICATION_URL,'store/"',Document::addHereClass(APPLICATION_URL.'store/'),' title="Store">Store</a>',
			'<ul class="nav-2">',
				'<li class="list-nav-2',Document::addHereClass(APPLICATION_URL.'store/books/', FALSE, FALSE),'">',
					'<a href="',APPLICATION_URL,'store/books/" title="Books">Books</a>',
				'</li>',
			'</ul>',
		'</li>',
		'<li class="list-nav-1',((strstr(FULL_URL, 'media')!==FALSE) ? '' : ' hover'),Document::addHereClass(APPLICATION_URL.'media/audio/', FALSE, FALSE),Document::addHereClass(APPLICATION_URL.'media/videos/', FALSE, FALSE),'">',
			'<a href="',APPLICATION_URL,'media/videos/" title="Media">Media</a>',
			'<ul class="nav-2">',
				'<li class="list-nav-2',Document::addHereClass(APPLICATION_URL.'media/audio/', FALSE, FALSE),'">',
					'<a href="',APPLICATION_URL,'media/audio/" title="Audio">Audio</a>',
				'</li>',
				'<li class="list-nav-2',Document::addHereClass(APPLICATION_URL.'media/videos/', FALSE, FALSE),'">',
					'<a href="',APPLICATION_URL,'media/videos/" title="Videos">Videos</a>',
				'</li>',
			'</ul>',
		'</li>',
		'<li class="list-nav-1',Document::addHereClass(APPLICATION_URL.'contact/', FALSE, FALSE),'">',
			'<a href="',APPLICATION_URL,'contact/" title="Contact">contact</a>',
		'</li>',
	'</ol>',
'</nav>';