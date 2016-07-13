/*! DO NOT MODIFY THIS FILE! IT IS GENERATED. CHANGES SHOULD BE MADE IN THE SOURCE FILES. !*/

/**
 * fwPopup
 *
 * Lightbox clone for jQuery.<br>
 * Based on code written by Stephane Caron (http://www.no-margin-for-errors.com).
 * Written by BigTalk Jon Ryser
 *
 * @copyright   (c) 2011 Jon Ryser  http://JonRyser.com
 * @author		Jon Ryser           http://JonRyser.com
 * @version		2.0.2
 */
(function ($, document, window) {
    'use strict';
    /*** Global (app) Private Variables ***/

    // Create a public Object as a property of the jQuery Object. This will have values indicating if the app has been initialized, the version number, and publicly accessible methods.
    var $fwPopup = $.fwPopup = {
        // Used for the deep linking to make sure not to call the same function several times.
        initialized: false,
        version: '2.0.2'
    };

    var $WIN = $(window);
    var isOpen;
    var $overlay;

    // Window size
    var windowHeight = $WIN.height();
    var windowWidth = $WIN.width();


    /*** Public Variables ***/

    // Make the "controller" method public by adding it to the jQuery Object.
    $.fn.fwPopup = fwPopup;


    /*** Private Methods ***/

    /**
     * fwPopup
     *
     * The "controller" of the app. All the main functionality is here.
     *
     * @private
     */
    function fwPopup(fwPopupSettings) {
        /* Private variables */
        // The description to be used in the popup to describe the content.
        var description = '';
        // Data store on the clickable element (in a data- attribute).
        var elementData;
        // An integer indicating the position of the initiating element relative to the matchedElements.
        var elementIndex;
        // A variable to hold the popup holder itself.
        var $fwPopupHolder;
        // A variable to hold the full resolution containing element.
        var $fwPopupFullRes;
        // A variable to hold the popup content element.
        var $fwPopupContent;
        // fwPopup container specific
        var fwPopupContentHeight;
        var fwPopupContentWidth;
        var fwPopupHolderHeight;
        var fwPopupHolderWidth;
        var fwPopupDimensions;
        var hashtagHook;
        var hook;
        var hookWord;
        // All the elements that matched the jQuery selector when fwPopup was called.
        var matchedElements = this;
        // The href value from the clicked element.
        var path;
        // Create a variable indicating if the widths and heights are percentages. false by default.
        var percentBased = false;
        var scrollPosition = getScrollPosition();
        // The title to be used in the popup to represent the content.
        var title = '';
        var type;

        /* Set tokens to local variables for ease of reading/editing. */
        var autoplayToken = '{%autoplay}';
        var codecToken = '{%codec}';
        var contentToken = '{%content}';
        var heightToken = '{%height}';
        var imageToken = '{%image}';
        var locationHrefToken = '{%location_href}';
        var mediaTypeToken = '{%type}';
        var pathToken = '{%path}';
        var socialToolsToken = '{%social_tools}';
        var titleToken = '{%title}';
        var widthToken = '{%width}';
        var wmodeToken = '{%wmode}';

        /* Set default markup to local variables for ease of reading/editing. */
        var audioMarkup = '' + imageToken + '<audio controls autoplay class="audioPlayback"><source src="' + pathToken + '" type="audio/' + mediaTypeToken + '" codec="' + codecToken + '"/></audio>';
        var videoMarkup = '<video id="videoPlayer" controls autoplay class="videoPlayback"><source src="' + pathToken + '.webm" type="video/wemb"><source src="' + pathToken + '.mp4" type="video/mp4"></video>';
        var flashMarkup = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' + widthToken + '" height="' + heightToken + '"><param name="wmode" value="' + wmodeToken + '" /><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always" /><param name="movie" value="' + pathToken + '" /><embed src="' + pathToken + '" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="' + widthToken + '" height="' + heightToken + '" wmode="' + wmodeToken + '"></embed></object>';
        var generalMarkup = (function () {
            var markupArray = [];
            // Open fwpHolder
            markupArray.push('<div class="fwpHolder">');

            markupArray.push('<div class="fwpTitle no_content"></div>');
            // Open fwpTop
            markupArray.push('<div class="fwpTop">');

            markupArray.push('<div class="fwpLeft"></div>');
            markupArray.push('<div class="fwpMiddle"></div>');
            markupArray.push('<div class="fwpRight"></div>');

            // Close fwpTop
            markupArray.push('</div>');
            // Open fwpContainer
            markupArray.push('<div class="fwpContainer">');

            markupArray.push('<div class="fwpLeft">');
            markupArray.push('</div>');
            // Open fwpContent (fwpMiddle)
            markupArray.push('<div class="fwpContent fwpMiddle fwpLoading">');

            markupArray.push('<a tabindex="0" class="button-expand" title="Expand the image" aria-label="Expand the image">Expand</a>');
            markupArray.push('<div id="fwpFullRes" class="fwpFullRes"></div>');
            // Open fwpDetails
            markupArray.push('<div class="fwpDetails">');

            markupArray.push('<p class="fwpDescription no_content"></p>');
            markupArray.push('<div class="fwpSocial no_content">' + socialToolsToken + '</div>');
            markupArray.push('<a tabindex="0" class="button-close" title="Close the modal" aria-label="Close the modal">Close</a>');

            // Close fwpDetails
            markupArray.push('</div>');

            // Close fwpContent (fwpMiddle)
            markupArray.push('</div>');
            markupArray.push('<div class="fwpRight">');
            markupArray.push('</div>');

            // Close fwpContainer
            markupArray.push('</div>');
            // Open fwpBottom
            markupArray.push('<div class="fwpBottom">');

            markupArray.push('<div class="fwpLeft"></div>');
            markupArray.push('<div class="fwpMiddle"></div>');
            markupArray.push('<div class="fwpRight"></div>');

            // Close fwpBottom
            markupArray.push('</div>');

            // Close fwpHolder
            markupArray.push('</div>');
            markupArray.push('<div class="overlay fwp"></div>');
            return markupArray.join('');
        })();
        var iframeMarkup = '<iframe src ="' + pathToken + '" width="' + widthToken + '" height="' + heightToken + '" frameborder="no"></iframe>';
        var imageMarkup = '<img class="fwpFullResImage" src="' + pathToken + '"/>';
        var inlineMarkup = '<div class="fwpInline">' + contentToken + '</div>';
        var quicktimeMarkup = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="//www.apple.com/qtactivex/qtplugin.cab" height="' + heightToken + '" width="' + widthToken + '"><param name="src" value="' + pathToken + '"><param name="autoplay" value="' + autoplayToken + '"><param name="type" value="video/quicktime"><embed src="' + pathToken + '" height="' + heightToken + '" width="' + widthToken + '" autoplay="' + autoplayToken + '" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>';
        var socialTools = '<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-text="' + titleToken + '">Tweet</a><script src="//platform.twitter.com/widgets.js"></script></div><div class="facebook"><div class="fb-share-button" data-href="' + locationHrefToken + '" data-layout="button" data-caption="' + titleToken + '"></div><script>(function($){$.ajaxSetup({cache:true});$.getScript("//connect.facebook.net/en_US/sdk.js",function(){FB.init({xfbml:1,version:"v2.5"})});})(jQuery)</script></div>';

        // Merge the passed settings with the default settings.
        fwPopupSettings = $.extend(true, {
            ajaxCallback: function () {},
            /* Allow the user to expand a resized image. true/false */
            allowExpand: true,
            /* Resize the photos bigger than viewport. true/false */
            allowResize: true,
            /* The speed of animation like fading in and out. fast/slow/normal */
            animationSpeed: 500,
            /* Automatically start videos: True/False */
            autoPlay: true,
            /* Called when fwPopup is closed */
            callback: function () {},
            /* The default height in pixels of the media. */
            defaultHeight: 344,
            /* The default width in pixels of the media. */
            defaultWidth: 500,
            /* Allow fwPopup to update the url to enable deep linking. */
            deepLinking: true,
            /* Hides all the flash object on a page, set to TRUE if flash appears over fwPopup */
            hideFlash: false,
            /* the attribute tag to use for fwPopup hooks. default: 'data-popUp'. For HTML5, use "data-popUp" or similar. For pre-HTML5, use "rel". */
            hook: 'data-fwPopup',
            hookWord: 'fwPopup',
            /* The padding on each side of the picture */
            horizontalPadding: 20,
            ie6Fallback: true,
            /* Set to false if you open forms inside fwPopup */
            keyboardAccessible: true,
            markup: {
                general: generalMarkup,
                audio: audioMarkup,
                custom: '',
                flash: flashMarkup,
                iframe: iframeMarkup,
                image: imageMarkup,
                inline: inlineMarkup,
                quicktime: quicktimeMarkup,
                // html or empty (false, null, '') to disable
                socialTools: socialTools,
                video: videoMarkup
            },
            /* If set to true, only the close button will close the window */
            modal: false,
            /* Value between 0 and 1 */
            opacity: 1,
            /* Show the title. true/false */
            showTitle: true,
            /* light_rounded / dark_rounded / light_square / dark_square / facebook */
            theme: 'default',
            /* Set the flash wmode attribute */
            wmode: 'opaque'
        }, fwPopupSettings);

        hook = fwPopupSettings.hook;
        hookWord = fwPopupSettings.hookWord;

        hashtagHook = getHashtagHookValue(hookWord);
        if (hashtagHook) {
            initialize();
        }

        // Return the jQuery object for chaining. The off method is used to avoid click conflict when the plugin is called more than once.
        return matchedElements.off('click.fwPopup')
            .on('click.fwPopup', function (event) {
                event.preventDefault();
                initialize(this);
            });

        function addAccessibility() {
            if (fwPopupSettings.keyboardAccessible) {
                $(document).off('keydown.fwPopup')
                    .on('keydown.fwPopup', function (event) {
                        // Check if the modal is present and visible.
                        if ($fwPopupHolder && $fwPopupHolder.is(':visible')) {
                            switch (event.keyCode) {
                                // Check if the "esc" key (27) was pressed.
                                case 27:
                                    if (!fwPopupSettings.modal) {
                                        closeModal();
                                    }
                                    break;
                            }
                        }
                    });
            }
        };

        /**
         * buildPopup
         *
         * Create the popup including the overlay and add it to the page.
         *
         * @private
         */
        function buildPopup() {
            var $fwPopupDescription;
            var $fwPopupTitle;
            var markup = fwPopupSettings.markup;
            // Create a variable to hold the markup to add to the general markup. By default, set it to an empty String.
            var markupToAdd = '';

            // Get the dimensions
            var mediaWidth = (parseFloat(getParam('width', path))) ? getParam('width', path) : fwPopupSettings.defaultWidth;
            var mediaHeight = (parseFloat(getParam('height', path))) ? getParam('height', path) : fwPopupSettings.defaultHeight;

            var imgPreloader;
            var skipInjection;
            var movie;

            // Inject Social Tool markup into General markup.
            if (markup.socialTools) {
                markup.socialTools = markup.socialTools.replace(new RegExp(locationHrefToken, 'g'), window.location)
                    .replace(new RegExp(titleToken, 'g'), title);
            } else {
                markup.socialTools = '';
            }
            markup.general = markup.general.replace(socialToolsToken, markup.socialTools);

            // Inject the markup
            $('body').append(markup.general);

            // Set the global selectors
            $fwPopupHolder = $('.fwpHolder').addClass(fwPopupSettings.theme);
            $fwPopupContent = $fwPopupHolder.find('.fwpContent');
            $fwPopupDescription = $fwPopupHolder.find('.fwpDescription');
            $fwPopupFullRes = $fwPopupHolder.find('#fwpFullRes');
            $fwPopupTitle = $fwPopupHolder.find('.fwpTitle');
            $overlay = $('.overlay.fwp').css({
                    opacity: 0
                })
                // On click of the overlay, close the modal (if "modal" is set to true in fwPopupSettings).
                .on('click', closeModal);

            $fwPopupHolder.find('.button-close')
                .off('click', closeModal)
                .on('click', closeModal);

            if (fwPopupSettings.allowExpand) {
                $fwPopupHolder.find('.button-expand')
                    .off('click', expandContent)
                    .on('click', expandContent);
            }

            // Hide the flash on the page (not in the popup).
            if (fwPopupSettings.hideFlash) {
                $('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css({ visibility:'hidden' });
            }

            // Set the title
            if (fwPopupSettings.showTitle && title) {
                $fwPopupTitle.html(decodeURI(title))
                    .removeClass('no_content');
            }

            if (markup.socialTools) {
                $fwPopupHolder.find('.fwpSocial').removeClass('no_content');
            }

            // Set the description
            $fwPopupDescription.hide();
            if (description) {
                $fwPopupDescription.show()
                    .html(decodeURI(description));
            }

            // If the size is % based, calculate according to window dimensions.
            percentBased = false;
            if (mediaHeight.toString().indexOf('%')+1) {
                mediaHeight = parseFloat(($WIN.height()*parseFloat(mediaHeight)/100)-150);
                percentBased = true;
            }
            if (mediaWidth.toString().indexOf('%')+1) {
                mediaWidth = parseFloat(($WIN.width()*parseFloat(mediaWidth)/100)-150);
                percentBased = true;
            }


            // Inject the proper content
            switch (getFileType(path)) {
                case 'ajax':
                    skipInjection = true;

                    fwPopupDimensions = fitToViewport(mediaHeight, mediaWidth);

                    $.get(path, function (responseHTML) {
                        markupToAdd = markup.inline.replace(contentToken, responseHTML);
                        $fwPopupFullRes[0].innerHTML = markupToAdd;
                        showContent();
                    });
                    break;

                case 'audio':
                    imgPreloader = new Image();

                    imgPreloader.onload = function () {
                        var audio = new Audio();
                        var image = ((elementData && elementData.image) ? '<img src="' + elementData.image + '" alt="Cover for ' + description + '"/>' : '');
                        $fwPopupFullRes[0].innerHTML = markup.audio.replace(codecToken, type.codec)
                            .replace(imageToken, image)
                            .replace(mediaTypeToken, type.type)
                            .replace(new RegExp(pathToken, 'g'), path);
                        $fwPopupFullRes.find('audio').width(imgPreloader.width);
                        audio.setAttribute('src', path);
                        // Required for 'older' browsers.
                        audio.load();
                        // Fit item to viewport.
                        fwPopupDimensions = fitToViewport((imgPreloader.height+30), imgPreloader.width);
                        showContent();
                    };
                    imgPreloader.src = elementData.image;
                    break;

                case 'custom':
                    // Fit item to viewport
                    fwPopupDimensions = fitToViewport(mediaHeight, mediaWidth);
                    markupToAdd = markup.custom;
                    break;

                case 'flash':
                    // Fit item to viewport
                    fwPopupDimensions = fitToViewport(mediaHeight, mediaWidth);

                    var flashVars = path.substring(path.indexOf('flashvars')+10, path.length);
                    var fileName = path.substring(0, path.indexOf('?'));

                    markupToAdd = markup.flash.replace(new RegExp(widthToken, 'g'), fwPopupDimensions.width)
                        .replace(new RegExp(heightToken, 'g'), fwPopupDimensions.height)
                        .replace(new RegExp(wmodeToken, 'g'), fwPopupSettings.wmode)
                        .replace(new RegExp(pathToken, 'g'), fileName + ((flashVars) ? '?' + flashVars : ''));
                    break;

                case 'iframe':
                    // Fit item to viewport
                    fwPopupDimensions = fitToViewport(mediaHeight, mediaWidth);

                    var frameUrl = path.substr(0, path.indexOf('iframe')-1);

                    markupToAdd = markup.iframe.replace(new RegExp(widthToken, 'g'), fwPopupDimensions.width)
                        .replace(new RegExp(heightToken, 'g'), fwPopupDimensions.height)
                        .replace(new RegExp(pathToken, 'g'), frameUrl);
                    break;

                case 'image':
                    imgPreloader = new Image();

                    $fwPopupFullRes[0].innerHTML = markup.image.replace(new RegExp(pathToken, 'g'), path);

                    imgPreloader.onload = function () {
                        // Fit item to viewport.
                        fwPopupDimensions = fitToViewport(imgPreloader.height, imgPreloader.width);
                        showContent();
                    };

                    imgPreloader.onerror = function () {
                        alert('Image cannot be loaded. Make sure the path is correct and image exist.');
                        closeModal(true);
                    };

                    imgPreloader.src = path;
                    break;

                case 'inline':
                    $fwPopupFullRes.addClass('fwpInline');
                    // To get the item height clone it, apply default width, wrap it in the fwPopup containers, then delete the clone.
                    var $clone = $('div').addClass('fwpFullRes fwpInline')
                        .css({
                            position: 'absolute',
                            top: -10000,
                            width: fwPopupSettings.defaultWidth,
                            'max-height': windowHeight
                        })
                        .appendTo($('body'));
                    $clone[0].innerHTML = $(path).html();
                    fwPopupDimensions = fitToViewport($clone.outerWidth(false), $clone.outerHeight(false));
                    // Delete the clone.
                    $clone.remove();
                    markupToAdd = markup.inline.replace(contentToken, $(path).html());
                    break;

                case 'quicktime':
                    // Fit item to viewport.
                    fwPopupDimensions = fitToViewport(mediaHeight, mediaWidth);
                    fwPopupDimensions.height += 15;
                    fwPopupDimensions.contentHeight += 15;
                    // Add space for the control bar
                    fwPopupDimensions.containerHeight += 15;

                    markupToAdd = markup.quicktime.replace(new RegExp(widthToken, 'g'), fwPopupDimensions.width)
                        .replace(new RegExp(heightToken, 'g'), fwPopupDimensions.height)
                        .replace(new RegExp(wmodeToken, 'g'), fwPopupSettings.wmode)
                        .replace(new RegExp(pathToken, 'g'), path)
                        .replace(new RegExp(autoplayToken, 'g'), fwPopupSettings.autoPlay);
                    break;

				case 'video':
                    imgPreloader = new Image();

                    imgPreloader.onload = function () {
                    	// NOTE: There is no Video object for video's yet.
                    	//var video_ele = new Video();
                    	//console.log(video_ele);
                    	// Remove the file extension from the path.
                    	var pathToken_noExt = path.substring(0, path.lastIndexOf('.'));
                    	$fwPopupFullRes[0].innerHTML = markup.video.replace(new RegExp(pathToken, 'g'), pathToken_noExt);
                    	$fwPopupFullRes.find('video').width(mediaWidth);
                    	//video_ele.setAttribute('src', path);
                    	// Required for 'older' browsers.
                    	//video_ele.load();
                    	// Fit item to viewport.
                    	fwPopupDimensions = fitToViewport(mediaHeight, mediaWidth);
                    	showContent();
                    };
                    imgPreloader.src = elementData.image;
                    break;

                case 'vimeo':
                    // Fit item to viewport
                    fwPopupDimensions = fitToViewport(mediaHeight, mediaWidth);

                    var regExp = /http(s?):\/\/(www\.)?vimeo.com\/(\d+)/;
                    var vimeoId = path.match(regExp)[3];
                    var vimeoWidth = fwPopupDimensions.width + '/embed/?moog_width=' + fwPopupDimensions.width;

                    movie = '//player.vimeo.com/video/'+ vimeoId + '?title=0&byline=0&portrait=0';
                    if (fwPopupSettings.autoPlay) {
                        movie += '&autoplay=1;';
                    }

                    markupToAdd = markup.iframe.replace(new RegExp(widthToken, 'g'), vimeoWidth)
                        .replace(new RegExp(heightToken, 'g'), fwPopupDimensions.height)
                        .replace(new RegExp(pathToken, 'g'), movie);
                    break;

                case 'youtube':
                    // Fit item to viewport
                    fwPopupDimensions = fitToViewport(mediaHeight, mediaWidth);

                    // Regular youtube link
                    var youtubeId = getParam('v', path);

                    // youtu.be link
                    if (!youtubeId) {
                        youtubeId = path.split('youtu.be/');
                        youtubeId = youtubeId[1];
                        // Strip anything after the ?
                        if (youtubeId.indexOf('?')>0) {
                            youtubeId = youtubeId.substr(0, youtubeId.indexOf('?'));
                        }
                        // Strip anything after the &
                        if (youtubeId.indexOf('&')>0) {
                            youtubeId = youtubeId.substr(0, youtubeId.indexOf('&'));
                        }
                    }

                    movie = '//www.youtube.com/embed/' + youtubeId;
                    if (getParam('rel', path)) {
                        movie += '?rel=' + getParam('rel', path);
                    } else {
                        movie += '?rel=1';
                    }

                    if (fwPopupSettings.autoPlay) {
                        movie += '&autoplay=1';
                    }

                    markupToAdd = markup.iframe.replace(new RegExp(widthToken, 'g'), fwPopupDimensions.width)
                        .replace(new RegExp(heightToken, 'g'), fwPopupDimensions.height)
                        .replace(new RegExp(wmodeToken, 'g'), fwPopupSettings.wmode)
                        .replace(new RegExp(pathToken, 'g'), movie);
                    break;
            }
            hideContent();
            if (!imgPreloader && !skipInjection) {
                $fwPopupFullRes[0].innerHTML = markupToAdd;
                // Show content
                showContent();
            }
        };

        /**
         * calculateLeft
         *
         * Calculates the distance to the left of the screen based on the width of the container.
         *
         * @param        popupWidth    {Integer}    The width (in pixels) of the popup itself.
         * @private
         */
        function calculateLeft(popupWidth) {
            var left = (windowWidth/2)+scrollPosition.scrollLeft-(popupWidth/2);
            if (left<0) {
                left = 0;
            }
            return left;
        };

        /**
         * calculateTop
         *
         * Calculates the distance to the top of the screen based on the height of the container.
         *
         * @param        popupHeight    {Integer}    The height (in pixels) of the popup itself.
         * @private
         */
        function calculateTop(popupHeight) {
            var top = (windowHeight/2)+scrollPosition.scrollTop-(popupHeight/2);
            if (top<0) {
                top = 0;
            }
            return top;
        };

        /**
         * centerPopup
         *
         * Centers the popup on the screen.
         *
         * @private
         */
        function centerPopup() {
            if ($fwPopupHolder) {
                scrollPosition = getScrollPosition();
                windowHeight = $WIN.height();
                windowWidth = $WIN.width();

                $fwPopupHolder.css({
                    top: calculateTop($fwPopupHolder.outerHeight(false)),
                    left: calculateLeft($fwPopupHolder.outerWidth(false))
                });
            }
        };

        /**
         * closeModal
         *
         * Closes the popup.
         *
         * @private
         */
        function closeModal(force) {
            if (!fwPopupSettings.modal || force === true) {
                var animationSpeed = fwPopupSettings.animationSpeed;

                if ($overlay.is(':animated')) {
                    return;
                }

                $fwPopupHolder.stop()
                    .find('object,embed')
                    .css({ visibility:'hidden' });

                $fwPopupHolder.fadeOut(animationSpeed, function () {
                    $(this).remove();
                });

                $overlay.fadeOut(animationSpeed, function () {
                    // Show the flash
                    if (fwPopupSettings.hideFlash) {
                        $('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css({ visibility:'visible' });
                    }

                    // Remove the popup markup from the page.
                    $(this).remove();

                    $WIN.off('scroll.fwPopup');

                    clearHashtagHook(hookWord);

                    isOpen = false;

                    $fwPopup.isInitialized = false;

                    fwPopupSettings.callback();
                });
            }
        };

        function expandContent(event) {
            var $clickedElement = $(event.target);
            // Expand the content
            if ($clickedElement.hasClass('button-expand')) {
                $clickedElement.removeClass('button-expand')
                    .addClass('button-contract')
                    .attr({
                        'aria-label': 'Contract the image',
                        title: 'Contract the image'
                    });
            } else {
                $clickedElement.removeClass('button-contract')
                    .addClass('button-expand')
                    .attr({
                        'aria-label': 'Expand the image',
                        title: 'Expand the image'
                    });
            }

            hideContent(openModal);

            return false;
        }

        /**
         * fitToViewport
         *
         * Resize the item dimensions if it's bigger than the viewport
         *
         * @param       height          {Integer}           Height of the item to be opened.
         * @param       width           {Integer}           Width of the item to be opened.
         * @return      JSON            The "fitted" dimensions with the properties "width", "height", containerHeight", containerWidth", "contentHeight", "contentWidth", and "resized".
         * @private
         */
        function fitToViewport(height, width) {
            getDimensions(height, width);

            var resized = false;
            // Define them in case there's no resize needed
            var contentHeight = height;
            var contentWidth = width;

            if (((fwPopupHolderWidth>windowWidth) || (fwPopupHolderHeight>windowHeight)) && fwPopupSettings.allowResize && !percentBased) {
                resized = true;
                var fitting = false;

                while (!fitting) {
                    if (fwPopupHolderWidth>windowWidth) {
                        contentWidth = (windowWidth-200);
                        contentHeight = (height/width)*contentWidth;
                    } else if (fwPopupHolderHeight>windowHeight) {
                        contentHeight = (windowHeight-200);
                        contentWidth = (width/height)*contentHeight;
                    } else {
                        fitting = true;
                    }

                    fwPopupHolderHeight = contentHeight;
                    fwPopupHolderWidth = contentWidth;
                }

                if ((fwPopupHolderWidth>windowWidth) || (fwPopupHolderHeight>windowHeight)) {
                    fitToViewport(fwPopupHolderWidth, fwPopupHolderHeight);
                }

                getDimensions(contentHeight, contentWidth);
            }

            return {
                width: Math.floor(contentWidth),
                height: Math.floor(contentHeight),
                containerHeight: Math.floor(fwPopupHolderHeight),
                containerWidth: Math.floor(fwPopupHolderWidth)+(fwPopupSettings.horizontalPadding*2),
                contentHeight: Math.floor(fwPopupContentHeight),
                contentWidth: Math.floor(fwPopupContentWidth),
                resized: resized
            };
        };

        /**
         * getDimensions
         *
         * Get the containers dimensions according to the item size
         *
         * @param       height          {Integer}           Height of the item to be opened.
         * @param       width           {Integer}           Width of the item to be opened.
         * @private
         */
        function getDimensions(height, width) {
            height = parseFloat(height);
            width = parseFloat(width);

            var $clone = $fwPopupHolder.clone()
                .appendTo($('body'))
                .css({
                    position: 'absolute',
                    top: -10000
                })
                .addClass('testDiv')
                .show();
            var $cloneContent = $clone.find('.fwpContent');

            $clone.find('.fwpDetails').addClass(fwPopupSettings.theme);
            $clone.find('#fwpFullRes').css({ height:height, width:width });

            // Get the container size to resize the holder to the correct dimensions.
            fwPopupContentHeight = $cloneContent.outerHeight(false);
            fwPopupContentWidth = width;
            fwPopupHolderHeight = $clone.outerHeight(false);
            fwPopupHolderWidth = width;

            $clone.remove();
        };

        /**
         * getFileType
         *
         * Get the type of media to be opened.
         *
         * @param       source          {String}            The path to the media to be opened.
         * @return      String          The type of media to be displayed.
         * @private
         */
        function getFileType(source) {
            if (source.match(/youtube\.com\/watch/i) || source.match(/youtu\.be/i)) {
                return 'youtube';
            }
            if (source.match(/vimeo\.com/i)) {
                return 'vimeo';
            }
            if (source.match(/\b.mov\b/i)) {
                return 'quicktime';
            }
            if (source.match(/\b.swf\b/i)) {
                return 'flash';
            }
            if (source.match(/\b.(mp3|ogg)\b/i)) {
                type = { type:'mpeg', codec:'mp3' };
                if (source.match(/\b.ogg\b/i)) {
                    type = { type:'ogg', codec:'vorbis' };
                }
                return 'audio';
            }
            if (source.match(/\b.(mp4)\b/i)) {
                type = { type:'mp4' };
                return 'video';
            }
            if (source.match(/\biframe=true\b/i)) {
                return 'iframe';
            }
            if (source.match(/\bajax=true\b/i)) {
                return 'ajax';
            }
            if (source.match(/\bcustom=true\b/i)) {
                return 'custom';
            }
            if (source.substr(0, 1) == '#') {
                return 'inline';
            }
            // By default, return "image".
            return 'image';
        };

        /**
         * hideContent
         *
         * Hide the content.
         *
         * @param       callback        {function}          A callback function to call after hiding the content.
         * @private
         */
        function hideContent(callback) {
            callback = callback || function () {};
            $fwPopupContent.addClass('fwpLoading');
            $fwPopupFullRes.find('object,embed').css({ visibility:'hidden' });
            $fwPopupFullRes.css({ opacity:0 });
            callback();
        };

        /**
         * initialize
         *
         * Initialize fwPopup.
         *
         * @private
         */
        function initialize(initializingElement) {
            addAccessibility();
            hashtagHook = getHashtagHookValue(hookWord);
            var $initiatingElement;
            // Check if there is a passed initializing element.
            if (!initializingElement && hashtagHook) {
                // Check if the
                elementIndex = hashtagHook[hookWord] || 0;
                $initiatingElement = $(matchedElements[elementIndex]);
            } else {
                $initiatingElement = $(initializingElement);
                // Where in the array the initiating element is positioned relative to all the other matched elements on the page.
                elementIndex = matchedElements.index($initiatingElement);
            }

            // Put the SRCs, TITLEs, and ALTs into variables.
            path = $initiatingElement.attr('href');
            elementData = $initiatingElement.data();
            description = (elementData && elementData.desc) ? elementData.desc : $initiatingElement.attr('title');
            title = (elementData && elementData.title) ? elementData.title : $initiatingElement.find('img').attr('alt');

            if (fwPopupSettings.deepLinking) {
                setHashtagHook(hookWord, elementIndex);
            }

            $fwPopup.isInitialized = true;

            // Build the popup.
            buildPopup();
        };

        /**
         * openModal
         *
         * Opens the fwPopup modal box.
         *
         * @param       event           {Event}             The JavaScript Event triggering this method.
         * @param       arguments[0]    {String,Array}      Full path to the media to be displayed, may also be an Array containing full paths.
         * @param       arguments[1]    {String,Array}      The title to be displayed with the media, may also be an Array of titles.
         * @param       arguments[2]    {String,Array}      The description to be displayed with the media, may also be an Array of descriptions.
         * @param       arguments[3]    {Integer}           The position of the media to be displayed in a slideshow.
         * @private
         */
        function openModal() {
            var animationSpeed = fwPopupSettings.animationSpeed;
            if (fwPopupSettings.allowResize) {
                $WIN.off('scroll.fwPopup', centerPopup)
                    .on('scroll.fwPopup', function () {
                        centerPopup();
                    });
            }

            // Window/Keyboard events. Please note that events are namespaced ("resize.fwPopup").
            $WIN.off('resize.fwPopup', centerPopup)
                .on('resize.fwPopup', centerPopup);

            $overlay.show()
                .fadeTo(animationSpeed, fwPopupSettings.opacity, function () {
                    $fwPopupFullRes.animate({ opacity:1 }, animationSpeed/2);
                });

            // Fade the holder
            $fwPopupHolder.fadeIn(animationSpeed, centerPopup);
            $fwPopupHolder.css({
                    display: 'block',
                    opacity: 0
                })
                .animate({
                    opacity: 1,
                    top: top,
                    left: ((windowWidth/2)-(fwPopupDimensions.containerWidth/2)<0) ? 0 : (windowWidth/2)-(fwPopupDimensions.containerWidth/2)
                },
                animationSpeed,
                function () {
                    isOpen = true;
                });
        };

        /**
         * showContent
         *
         * Set the proper sizes on the containers and animate the content in.
         *
         * @private
         */
        function showContent() {
            $fwPopupContent.removeClass('fwpLoading');

            $fwPopupHolder.find('.fwpFullResImage')
                .height(fwPopupDimensions.height)
                .width(fwPopupDimensions.width);

            if (fwPopupSettings.allowExpand) {
                // Fade the resizing link if the image is resized
                if (fwPopupDimensions.resized) {
                    $('a.button-expand,a.button-contract').show();
                } else {
                    $('a.button-expand').hide();
                }
            }

            openModal();

            fwPopupSettings.ajaxCallback();
        };
    };

    /**
     * clearHashtagHook
     *
     * Clears the hook word from the URL.
     *
     * @private
     */
    function clearHashtagHook(hookWord) {
        var hashes = getHashtag();
        var hashString = '';
        if (hashes && hashes[hookWord]) {
            delete hashes[hookWord];
            for (var index in hashes) {
                hashString += (index + ((hashes[index] === true) ? '' : '=' + encodeURIComponent(hashes[index])));
            }
            window.location.hash = hashString;
            return true;
        }
        return false;
    };

    /**
     * getParam
     *
     * Gets the value of a passed param name from the passed
     * URL. If the passed param doesn't exist or has no value,
     * an empty String is returned.
     *
     * @private
     */
    function getParam(name, url) {
        name = name.replace(/[\[]/, '\\\[')
          .replace(/[\]]/, '\\\]');
        var regexString = '[\\?&]' + name + '=([^&#]*)';
        var regex = new RegExp(regexString);
        var results = regex.exec(url);
        return (results === null) ? '' : results[1];
    };

    /**
     * getHashtagHookValue
     *
     * Gets the value of the hook word from the URL hash. If there is
     * no hook word in the hash, this returns false.
     *
     * @private
     */
    function getHashtagHookValue(hookWord) {
        var hashes = getHashtag();
        var hashtag = {};
        if (hashes && hashes[hookWord]) {
            if (hashes[hookWord] === true) {
                hashtag[hookWord] = 0;
            } else {
                hashtag[hookWord] = hashes[hookWord];
            }
            return hashtag;
        }
        return false;
    };

    /**
     * getHashtag
     *
     * Gets the value of the hook word from the URL hash. If there is
     * no hook word in the hash, this returns false.
     *
     * @private
     */
    function getHashtag() {
        var hash = window.location.hash;
        var hashArray = ((hash) ? hash.replace(/^#/, '').split('&') : []);
        var hashes = {};

        for (var i = 0; i<hashArray.length; i++) {
            var currentPair = hashArray[i].split('=');
            hashes[ currentPair[0] ] = decodeURIComponent(currentPair[1]) || true;
        }
        if ($.isEmptyObject(hashes)) {
            hashes = false;
        }
        return hashes;
    };

    /**
     * getScrollPosition
     *
     * Returns the position if the page is scrolled. Supports allowExpand
     * major browsers and IE6 +.
     *
     * @private
     */
    function getScrollPosition() {
        // Use window.pageYOffset first. If not available, use document.documentElement (Explorer 6 Strict). If not available, use document.body (all other Explorers).
        if (window.pageYOffset) {
            return { scrollTop:window.pageYOffset, scrollLeft:window.pageXOffset };
        } else if (document.documentElement && document.documentElement.scrollTop) {
            return { scrollTop:document.documentElement.scrollTop, scrollLeft:document.documentElement.scrollLeft };
        } else if (document.body) {
            return { scrollTop:document.body.scrollTop, scrollLeft:document.body.scrollLeft };
        } else {
            return { scrollTop:0, scrollLeft:0 };
        }
    };

    /**
     * setHashtagHook
     *
     * Sets the current element index to the hash.
     *
     * @private
     */
    function setHashtagHook(hookWord, elementIndex) {
        clearHashtagHook(hookWord);
        var hashes = getHashtag();
        var hashString = '';
        if (!hashes) {
            hashes = {};
        }
        hashes[hookWord] = elementIndex;
        for (var index in hashes) {
            hashString += (index + ((hashes[index] === true) ? '' : '=' + encodeURIComponent(hashes[index])));
        }
        window.location.hash = hashString;
        return true;
    };
})(jQuery, document, window);
