/*
 * fwAlert
 *
 * Based on original code from Steve Chipman (slayeroffice.com) Permission to use,
 * copy, modify, and distribute this software for any purpose with or without fee
 * is hereby granted, provided that the above copyright notice and this permission
 * notice appear in all copies. Adapted by Arron Bailiss <arron@arronbailiss.com>
 * in 2011. Re-written by Jon Ryser (JonRyser.com).
 *
 * @copyright   (c) 2015 Jon Ryser      http://JonRyser.com
 * @author      Jon Ryser               http://JonRyser.com
 * @version     1.0.2
 */
(function ($, window) {
    'use strict';

    $.fn.fwAlert = function (additionalOptions) {
        var options = {
            close: 'Close',
            draggable: false
        };
        if (additionalOptions) {
            $.extend(options, additionalOptions);
        }
        window.defaultAlert = window.alert;
        window.alert = alert;
        function alert(title, message) {
            if (title || message) {
                var overlay = $('.overlay');
                var header = $('<h1>').addClass('h-1')
                    .html(title);
                var content = $('<div>').addClass('message')
                    .html(message);
                var closeButton = $('<button>').addClass('button-close')
                    .attr({
                        'aria-label': 'Close ' + title + ' Modal',
                        role: 'button',
                        type: 'button'
                    })
                    .html(options.close);
                var alertBox = $('<section>').addClass('alertBox')
                    .appendTo($('body'))
                    .append(header)
                    .append(content)
                    .append(closeButton);
                if (overlay.length<1) {
                    overlay = $('<div>').addClass('overlay').appendTo($('body'));
                }
                overlay.show();
                if (options.draggable && alertBox.draggable) {
                    alertBox.draggable({
                        handle: '.alertBox .h-1',
                        opacity: 0.4
                    });
                    $('.alertBox .h-1').css({
                        cursor: 'move'
                    });
                }
                $('.alertBox .button-close, .overlay').click(function (event) {
                    event.preventDefault();
                    $('.alertBox, .overlay').remove();
                });
                $(window).keydown(keydownHandler);
                positionBox(alertBox, content);
            }
        }
        function keydownHandler(event) {
            if ('13' == event.keyCode || '32' == event.keyCode || '27' == event.keyCode) {
                $('.alertBox .button-close').click();
                $(window).unbind(keydownHandler);
            }
        }
        function positionBox(alertBox, content) {
            var topPosition = ($(window).height()/2)-(alertBox.outerHeight(false)/2);
            var height = 'auto';
            if (topPosition<0) {
                topPosition = 0;
                height = '90%';
                content.css({
                    'overflow-y': 'scroll',
                    height: '98%'
                });
            }
            alertBox.css({
                top: topPosition,
                left: ($(window).width()/2)-(alertBox.outerWidth(false)/2),
                height: height
            });
        }
    };
})(jQuery, window);