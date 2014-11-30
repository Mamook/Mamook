//<![CDATA[
function checkFields(selector){
	var value=$(selector).val();
	$('#embed').toggle(value == 'embed');
	$('#file').toggle(value == 'file');
};
$(function(){
	checkFields($('.video_type_radio:checked'));
	$('.video_type_radio').click(function(){checkFields(this)});
});
//]]>