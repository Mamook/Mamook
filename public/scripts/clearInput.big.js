/*
 * clearInput
 *
 * @copyright (c) 2011 Jon Ryser	http://JonRyser.com
 * @author		Jon Ryser		http://JonRyser.com
 * @version		1.0.0
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


