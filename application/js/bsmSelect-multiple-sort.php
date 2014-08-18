<?php /* application/js/bsmSelect-multiple-sort.php */

$js='$(\'select[multiple]\').bsmSelect({'.
		'addItemTarget: \'top\','.
		'hideWhenAdded: true,'.
		'removeLabel: \'X\','.
		'animate: true,'.
		'highlight: true,'.
		'plugins: ['.
			'$.bsmSelect.plugins.sortable({ axis : \'y\', opacity : 0.5 }, { listSortableClass : \'bsmListSortableCustom\' }),'.
			'$.bsmSelect.plugins.compatibility()'.
		']'.
	'});';