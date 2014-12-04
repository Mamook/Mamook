<?php /* application/js/tinymce-textarea.php */

$js='$(function(){$(\'textarea.tinymce\').tinymce({'."\n".
		# Location of TinyMCE script
		'script_url: \''.SCRIPTS.'tinymce/jquery.tinymce.js\','."\n".
		# General options
		'mode: \'textareas\','."\n".
		'theme: \'advanced\','."\n".
		'skin: \'MijoBrandsOrange\','."\n".
		'plugins: \'advimage,advlink,advlist,autosave,contextmenu,fullscreen,inlinepopups,media,paste,preview,print,safari,searchreplace,spellchecker,tabfocus,xhtmlxtras\','."\n".
		'dialog_type: \'modal\','."\n".
		# Theme options
		'theme_advanced_buttons1: \'formatselect,fontsizeselect,bold,italic,underline\','."\n".
		'theme_advanced_buttons2: \'justifyleft,justifycenter,justifyright,justifyfull,cut,copy,paste,pastetext,pasteword,cleanup,code\','."\n".
		'theme_advanced_buttons3: \'search,replace,bullist,numlist,outdent,indent,blockquote,cite,abbr,acronym\','."\n".
		'theme_advanced_buttons4: \'undo,redo,|,link,unlink,anchor,image,media,|,del,ins\','."\n".
		'theme_advanced_buttons5: \'print,|,spellchecker,preview,fullscreen\','."\n".
		'theme_advanced_toolbar_location: \'top\','."\n".
		'theme_advanced_toolbar_align: \'left\','."\n".
		'theme_advanced_statusbar_location: \'bottom\','."\n".
		'theme_advanced_resizing: true,'."\n".
		'theme_advanced_resizing_max_width: 474,'."\n".
		'extended_valid_elements: \'img[!src|border:0|alt|title|width|height|style]a[name|href|target|title|onclick]\','."\n".
		'gecko_spellcheck: true,'."\n".
		'paste_auto_cleanup_on_paste: true,'."\n".
		'plugin_preview_width: \'600\','."\n".
		'plugin_preview_height: \'500\','."\n".
		# Content CSS (should be your site CSS)
		'content_css: \''.THEME.'css/bbcode.css\','."\n".
		'entity_encoding: \'raw\','."\n".
		'fullscreen_new_window: true,'."\n".
		'fullscreen_settings: {'.
			'theme_advanced_path_location: \'top\''.
		'}'.
	'})});';