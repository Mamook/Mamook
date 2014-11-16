//<![CDATA[
function checkFields(selector){
	var value = $(selector).val();
	$('#amazon').toggle(value == 'amazon');
	$('#external').toggle(value == 'external');
	$('#internal').toggle(value == 'internal');
};
$(function(){
	checkFields($('.product_type_radio:checked'));
	$('.product_type_radio').on('click', function(){
		checkFields(this);
	});
});
//]]>