<?php /* application/js/bsmSelect-multiple.php */
$js='$("select[multiple]").bsmSelect({addItemTarget:"original",hideWhenAdded:!1,removeLabel:"X",animate:!0,plugins:[$.bsmSelect.plugins.compatibility()],containerClass:"bsm",selectClass:"select",listClass:"bsm-list",listItemClass:"bsm-item",listItemLabelClass:"bsm-label",removeClass:"remove"});$(".remove").attr({title:"Remove This"});$("select[multiple]").change(function(){$(".remove").attr({title:"Remove This"})});';

# Big
/* $js='$("select[multiple]").bsmSelect({'.
		'addItemTarget:"original",'.
		'hideWhenAdded:false,'.
		'removeLabel:"X",'.
		'animate:true,'.
		'plugins:[$.bsmSelect.plugins.compatibility()],'.
		'containerClass:"bsm",'.						// Class for container that wraps this widget
		'selectClass:"select",'.						// Class for the <select>
		'listClass:"bsm-list",'.						// Class for the list ($ol)
		'listItemClass:"bsm-item",'.				// Class for the <li> list items
		'listItemLabelClass:"bsm-label",'.	// Class for the label text that appears in list items
		'removeClass:"remove"'.							// Class given to the "remove" link
	'});'.
	'$(".remove").attr({title:"Remove This"});'.
	'$("select[multiple]").change(function(){'.
		'$(".remove").attr({title:"Remove This"})'.
	'});'; */