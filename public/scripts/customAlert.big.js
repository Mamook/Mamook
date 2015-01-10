/*
 * customAlert
 *
 * Based on original code from Steve Chipman (slayeroffice.com) Permission to use, copy, modify, and distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies.
 *
 * @copyright (c) 2011 Arron Bailiss <arron@arronbailiss.com>
 */
(function($){
	$.fn.customAlert=function(additionalOptions){
		var options = {
			alertOk:		'OK',
			draggable:	!1
		};
		additionalOptions && $.extend(options, additionalOptions);
		document.getElementById && (window.defaultAlert = window.alert, window.alert = function(title, message){
			if(!(void 0===b || void 0===d))
			{
				var header = $('<h1>').addClass('title')
					.html(title);
				var content = $('<span>').addClass('message')
					.html(message);
				var okButton = $('<button>').addClass('okBtn')
					.attr({
						'aria-label':	'Close ' + title + ' Modal',
						role:					'button',
						type:					'button',
						value:				options.alertOk
					});
				var alertBox = $('<section>').addClass('alertBox')
					.prepend(header)
					.append(content)
					.append(okButton);
				var overlay = $('.overlay');
				if(overlay.length == 0)
					overlay = $('<div>').addClass('overlay');
				$('body').append(alertBox)
					.append(overlay.show());
				alertBox.css({
					top:	($(window).height()/2-alertBox.outerHeight(true)/2) + 'px',
					left:	($(window).width()/2-alertBox.outerWidth(true)/2) + 'px'
				});
				options.draggable && alertBox.draggable && (alertBox.draggable({
						handle:		'.alertBox .title',
						opacity:	0.4
					}),
					header.css({cursor:'move'})
				);
				$('.alertBox .okBtn, .overlay').click(function(event){
					event.preventDefault();
					$('.alertBox, .overlay').remove();
				});
				$(window).keydown(function(event){
					('13' == event.keyCode || '32' == event.keyCode) && ($('.alertBox .okBtn').click(),
					$(this).off('keydown'))
				});
			}
		});
	};
})(jQuery);


