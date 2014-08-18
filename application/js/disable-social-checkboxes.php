<?php /* application/js/disable-social-checkboxes.php */

$js='$(\'[id|=visibility]\').change(function(){
    if(this.checked){
    	$(\'#facebook\').attr(\'checked\', false).attr(\'disabled\', \'disabled\');
    	$(\'#twitter\').attr(\'checked\', false).attr(\'disabled\', \'disabled\');
    }
    else{
    	$(\'#facebook\').removeAttr(\'disabled\');
    	$(\'#twitter\').removeAttr(\'disabled\');
    }
	});'.
	'$(\'#visibility-all_users\').change(function(){
    if(this.checked){
    	$(\'#facebook\').removeAttr(\'disabled\');
    	$(\'#twitter\').removeAttr(\'disabled\');
    }
	});';