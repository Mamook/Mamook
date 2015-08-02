/*
 * fwAlert
 *
 * Based on original code from Steve Chipman (slayeroffice.com) Permission to use,
 * copy, modify, and distribute this software for any purpose with or without fee
 * is hereby granted, provided that the above copyright notice and this permission
 * notice appear in all copies. Adapted by Arron Bailiss <arron@arronbailiss.com>
 * in 2011. Re-written by Jon Ryser (JonRyser.com).
 *
 * @copyright (c) 2015 Jon Ryser	http://JonRyser.com
 * @author		Jon Ryser		http://JonRyser.com
 * @version		1.0.1
 */
(function($){
	$.fn.fwAlert=function(additionalOptions){
		var WIN = window;
		var options = {
			close:			'Close',
			draggable:	false
		};
		additionalOptions && $.extend(options, additionalOptions);
		document.getElementById && (WIN.defaultAlert = WIN.alert, WIN.alert = function(title, message){
			if(!(void 0===title || void 0===message))
			{
				var overlay = $('.overlay');
				var header = $('<h1>').addClass('h-1')
					.html(title);
				var content = $('<div>').addClass('message')
					.html(message);
				var closeButton = $('<button>').addClass('button-close')
					.attr({
						'aria-label':	'Close ' + title + ' Modal',
						role:					'button',
						type:					'button'
					})
					.html(options.close);
				var alertBox = $('<section>').addClass('alertBox')
					.append(header)
					.append(content)
					.append(closeButton);
				if(!(0<overlay.length))
					overlay = $('<div>').addClass('overlay').appendTo($('body'));
				overlay.show();
				$('body').append(alertBox);
				var topPosition = $(WIN).height()/2-alertBox.outerHeight(!0)/2;
				console.log('topPosition: ', topPosition);
				var height = 'auto';
				if(topPosition<0)
				{
					topPosition = 0;
					height = '90%';
					content.css({
						'overflow-y':	'scroll',
						height:				'98%'
					});
				}
				alertBox.css({
					top:		topPosition + 'px',
					left:		$(WIN).width()/2-alertBox.outerWidth(!0)/2 + 'px',
					height:	height
				});
				options.draggable && alertBox.draggable && (alertBox.draggable({handle:'.alertBox .h-1', opacity:0.4}),
				$('.alertBox .h-1').css({cursor:'move'}));
				$('.alertBox .button-close, .overlay').click(function(event){
					event.preventDefault();
					$('.alertBox, .overlay').remove();
				});
				$(WIN).keydown(function(event){
					('13' == event.keyCode || '32' == event.keyCode || '27' == event.keyCode) && ($('.alertBox .button-close').click(),
					$(this).unbind('keydown'))
				});
			}
		});
	};
})(jQuery);