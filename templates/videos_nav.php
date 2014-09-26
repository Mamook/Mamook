<?php /* templates/videos_nav.php */
$videos_nav='<ul>'.
	'<li'.Document::addHereClass(VIDEOS_URL, TRUE).'>'.
		'<a href="'.VIDEOS_URL.'"'.Document::addHereClass(VIDEOS_URL, TRUE).' title="Spotlight Videos">Spotlight Videos</a>'.
	'</li>'.
	(APPLICATION_URL.Utility::removeIndex(HERE)==VIDEOS_URL ? $playlist_items : '').
'</ul>';