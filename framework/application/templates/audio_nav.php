<?php /* framework/application/templates/audio_nav.php */

$audio_nav='<h1 class="h-1'.((empty($playlist_items)) ? ' no_content' : '').'">Playlists</h1>'.
'<ul class="nav-1'.((empty($playlist_items)) ? ' no_content' : '').'">'.
	$playlist_items.
'</ul>';