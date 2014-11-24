/* Copyright (c) 2011 Arron Bailiss <arron@arronbailiss.com> | Based on original code from Steve Chipman (slayeroffice.com)
Permission to use, copy, modify, and distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies. */

/*
 * customAlert
 *
 */
(function(a){
	jQuery.fn.customAlert=function(b){
		var c = {
			alertOk:		'OK',
			draggable:	!1
		};
		b && jQuery.extend(c, b);
		document.getElementById && (window.defaultAlert = window.alert, window.alert = function(b, d){
			if(!(0<jQuery('.overlay').length || void 0===b||void 0===d))
			{
				var overlay = jQuery('<div>').addClass('overlay')
					.show();
				var title = jQuery('<div>').addClass('title')
					.html(b);
				var message = jQuery('<div>').addClass('message')
					.html(d);
				var okButton = jQuery('<a>').addClass('okBtn')
					.text(c.alertOk)
					.attr('href', '#');
				var alertBox = jQuery('<div>').addClass('alertBox')
					.append(title)
					.append(message)
					.append(okButton);
				jQuery('body').append(alertBox)
					.append(overlay);
				alertBox.css({
					top:	(jQuery(window).height()/2-alertBox.outerHeight(true)/2) + 'px',
					left:	(jQuery(window).width()/2-alertBox.outerWidth(true)/2) + 'px'
				});
				c.draggable && alertBox.draggable && (alertBox.draggable({
						handle:		'.title',
						opacity:	0.4
					}),
					jQuery('.alertBox .title').css('cursor', 'move')
				);
				jQuery('.alertBox .okBtn, .overlay').click(function(b){
					b.preventDefault();
					jQuery('.alertBox, .overlay').remove();
				});
				jQuery(window).keydown(function(b){
					'13' == b.keyCode && (jQuery('.alertBox .okBtn').click(),
					jQuery(this).unbind('keydown'))
				});
			}
		});
	};
})(jQuery);