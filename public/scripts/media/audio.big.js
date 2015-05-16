//<![CDATA[
$(function(){
	checkFields($('.audio_type_radio:checked'));
	$('.audio_type_radio').click(function(){checkFields(this)});
	function checkFields(selector){
		var value=$(selector).val();
		$('#embed').toggle(value == 'embed');
		$('#file').toggle(value == 'file');
	};
});
//]]>