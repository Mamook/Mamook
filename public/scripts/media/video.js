//<![CDATA[
function checkFields(el){
	var sel=$(el).val();
	$('#embed').toggle(sel=='embed');
	$('#file').toggle(sel=='file');
}
$(function(){
	checkFields($('.video_type_radio:checked'));
	$('.video_type_radio').click(function(){checkFields(this)});
});
//]]>