<?php /* application/js/bsmSelect-multiple.php */

$js='$(\'select[multiple]\').bsmSelect({'.
		'addItemTarget: \'original\','."\n".
		'hideWhenAdded: false,'."\n".
		'removeLabel: \'X\','."\n".
		'animate: true,'."\n".
		'plugins: [ $.bsmSelect.plugins.compatibility() ],'."\n".
		'containerClass: \'bsm\','."\n".						// Class for container that wraps this widget
		'selectClass: \'select\','."\n".						// Class for the <select>
		'listClass: \'bsm-list\','."\n".						// Class for the list ($ol)
		'listItemClass: \'bsm-item\','."\n".				// Class for the <li> list items
		'listItemLabelClass: \'bsm-label\','."\n".	// Class for the label text that appears in list items
		'removeClass: \'remove\''.									// Class given to the "remove" link
	'});'.
	'$(\'.remove\').attr(\'title\', \'Remove This\');'.
	'$(\'select[multiple]\').change(function(){'.
		'$(\'.remove\').attr(\'title\', \'Remove This\')'.
	'});';