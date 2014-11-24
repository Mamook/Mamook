/*
 * clearInput
 *
 *
 */
function clearInput(selector, attribute){
	var element = $(selector);
	var b = [];
	element.addClass('off')
		.focus(function(){
			if(!b[selector])
				b[selector] = element.attr(attribute);
			if(element.attr(attribute)==b[selector])
				element.attr(attribute, '').removeClass('off').addClass('on');
			element.blur(function(){
				element.attr(attribute)=="" && element.removeClass('on').addClass('off').attr(attribute, b[selector]);
			});
		});
};