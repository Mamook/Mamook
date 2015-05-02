<?php /* framework/application/templates/videos_nav.php */

$videos_nav='<ul class="nav-1">'.
	'<li class="list-nav-1'.Document::addHereClass(VIDEOS_URL, TRUE, FALSE).'">'.
		'<a href="'.VIDEOS_URL.'"'.Document::addHereClass(VIDEOS_URL, TRUE).' title="Spotlight Videos">Spotlight Videos</a>'.
	'</li>'.
	(APPLICATION_URL.Utility::removeIndex(HERE)==VIDEOS_URL ? $playlist_items : '').
'</ul>';