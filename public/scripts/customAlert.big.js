/*
 * customAlert
 *
 * Based on original code from Steve Chipman (slayeroffice.com) Permission to use, copy, modify, and distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies.
 *
 * @copyright (c) 2011 Arron Bailiss <arron@arronbailiss.com>
 */
(function($){
	$.fn.customAlert=function(b){
		var c = {
			alertOk:		'OK',
			draggable:	!1
		};
		b && $.extend(c, b);
		document.getElementById && (window.defaultAlert = window.alert, window.alert = function(b, d){
			if(!(0<$('.overlay').length || void 0===b||void 0===d))
			{
				var overlay = $('<div>').addClass('overlay')
					.show();
				var title = $('<div>').addClass('title')
					.html(b);
				var message = $('<div>').addClass('message')
					.html(d);
				var okButton = $('<a>').addClass('okBtn')
					.text(c.alertOk)
					.attr('href', '#');
				var alertBox = $('<div>').addClass('alertBox')
					.append(title)
					.append(message)
					.append(okButton);
				$('body').append(alertBox)
					.append(overlay);
				alertBox.css({
					top:	($(window).height()/2-alertBox.outerHeight(true)/2) + 'px',
					left:	($(window).width()/2-alertBox.outerWidth(true)/2) + 'px'
				});
				c.draggable && alertBox.draggable && (alertBox.draggable({
						handle:		'.title',
						opacity:	0.4
					}),
					$('.alertBox .title').css('cursor', 'move')
				);
				$('.alertBox .okBtn, .overlay').click(function(b){
					b.preventDefault();
					$('.alertBox, .overlay').remove();
				});
				$(window).keydown(function(b){
					'13' == b.keyCode && ($('.alertBox .okBtn').click(),
					$(this).unbind('keydown'))
				});
			}
		});
	};
})(jQuery);


