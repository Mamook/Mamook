<?php /* application/js/tinymce-textarea.php */

$js='(function(a){a(function(){a("textarea.tinymce").tinymce({script_url:"'.SCRIPTS.'tinymce/jquery.tinymce.js",mode:"textareas",theme:"advanced",skin:"'.$theme->getName().'",plugins:"advimage,advlink,advlist,autosave,contextmenu,fullscreen,inlinepopups,media,paste,preview,print,safari,searchreplace,spellchecker,tabfocus,xhtmlxtras",dialog_type:"modal",theme_advanced_buttons1:"formatselect,fontsizeselect,bold,italic,underline",theme_advanced_buttons2:"justifyleft,justifycenter,justifyright,justifyfull,cut,copy,paste,pastetext,pasteword,cleanup,code",
theme_advanced_buttons3:"search,replace,bullist,numlist,outdent,indent,blockquote,cite,abbr,acronym",theme_advanced_buttons4:"undo,redo,|,link,unlink,anchor,image,media,|,del,ins",theme_advanced_buttons5:"print,|,spellchecker,preview,fullscreen",theme_advanced_toolbar_location:"top",theme_advanced_toolbar_align:"left",theme_advanced_statusbar_location:"bottom",theme_advanced_resizing:!0,theme_advanced_resizing_max_width:474,extended_valid_elements:"img[!src|border:0|alt|title|width|height|style]a[name|href|target|title|onclick]",
gecko_spellcheck:!0,paste_auto_cleanup_on_paste:!0,plugin_preview_width:600,plugin_preview_height:500,content_css:"'.THEME.'css/bbcode.css",entity_encoding:"raw",fullscreen_new_window:!0,fullscreen_settings:{theme_advanced_path_location:"top"}})})})(jQuery);';


/* Big version
// Wrap the script to protect the global namespace.
(function ($) {
	// Wait for document ready.
	$(function () {
		$("textarea.tinymce").tinymce({
			// Location of TinyMCE script
			script_url: "'.SCRIPTS.'tinymce/jquery.tinymce.js",
			// General options
			mode: "textareas",
			theme: "advanced",
			// The skin name should match the site theme name.
			skin: "'.$theme->getName().'",
			plugins: "advimage,advlink,advlist,autosave,contextmenu,fullscreen,inlinepopups,media,paste,preview,print,safari,searchreplace,spellchecker,tabfocus,xhtmlxtras",
			dialog_type: "modal",
			// Theme options
			theme_advanced_buttons1: "formatselect,fontsizeselect,bold,italic,underline",
			theme_advanced_buttons2: "justifyleft,justifycenter,justifyright,justifyfull,cut,copy,paste,pastetext,pasteword,cleanup,code",
			theme_advanced_buttons3: "search,replace,bullist,numlist,outdent,indent,blockquote,cite,abbr,acronym",
			theme_advanced_buttons4: "undo,redo,|,link,unlink,anchor,image,media,|,del,ins",
			theme_advanced_buttons5: "print,|,spellchecker,preview,fullscreen",
			theme_advanced_toolbar_location: "top",
			theme_advanced_toolbar_align: "left",
			theme_advanced_statusbar_location: "bottom",
			theme_advanced_resizing: true,
			theme_advanced_resizing_max_width: 474,
			extended_valid_elements: "img[!src|border:0|alt|title|width|height|style]a[name|href|target|title|onclick]",
			gecko_spellcheck: true,
			paste_auto_cleanup_on_paste: true,
			plugin_preview_width: 600,
			plugin_preview_height: 500,
			// Content CSS (should be your site CSS)
			content_css: "'.THEME.'css/bbcode.css",
			entity_encoding: "raw",
			fullscreen_new_window: true,
			fullscreen_settings: {
				theme_advanced_path_location: "top"
			}
		})
	});
})(jQuery);
*/