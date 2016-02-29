
    /*** Private Methods ***/

    /**
     * fwPopup
     *
     * The "controller" of the app. All the main functionality is here.
     *
     * @private
     */
    function fwPopup(fwPopupSettings) {
        var $fwPopupHolder;
        var $fwPopupTitle;
        // Create a variable to hold the Array of descriptions collected from the passed sources.
        var description;
        var elementIndex;
        // fwPopup container specific
        var fwPopupContentHeight;
        var fwPopupContentWidth;
        var fwPopupContainerHeight;
        var fwPopupContainerWidth;
        var fwPopupDimensions;
        // Create a variable to hold the String that hooks an element to the plugin, making it globally available throughout the plugin.
        var hookWord;
        // Create a variable to hold the markup to add to the general markup. By default, set it to an empty String.
        var markupToAdd = '';
        var matchedObjects = this;
        // Create a variable to hold the JavaScript object of data collected from the passed sources.
        var mediaData;
        // Create a variable to hold the Array of the paths to the media to display collected from the passed sources.
        var path;
        // Create a variable indicating if the widths and heights are percentages. false by default.
        var percentBased = false;
        // Create a variable that indicates whether the popup should be resized or not. Default is false.
        var resize = true;
        var scrollPosition = getScrollPosition();
        // Create a variable to hold the passed settings, making them available throughout the plugin instance.
        var settings;
        // Create a variable to hold the Array of titles collected from the passed sources.
        var title;
        // Create a variable to hold the media type for use with html5 audio and video.
        var type;

        // Merge the passed settings with the default settings.
        fwPopupSettings = $.extend({
            /* the attribute tag to use for fwPopup hooks. default: 'data-popUp'. For HTML5, use "data-popUp" or similar. For pre-HTML5, use "rel". */
            hook: 'data-fwPopup',
            hookWord: 'fwPopup',
            /* The speed of animation like fading in and out. fast/slow/normal */
            animationSpeed: 'fast',
            ajaxCallback: function () {},
            /* Value between 0 and 1 */
            opacity: 0.80,
            /* Show the title. true/false */
            showTitle: true,
            /* Allow the user to expand a resized image. true/false */
            allowExpand: true,
            /* Resize the photos bigger than viewport. true/false */
            allowResize: true,
            /* The default width in pixels of the media. */
            defaultWidth: 500,
            /* The default height in pixels of the media. */
            defaultHeight: 344,
            /* light_rounded / dark_rounded / light_square / dark_square / facebook */
            theme: 'default',
            /* The padding on each side of the picture */
            horizontalPadding: 20,
            /* Hides all the flash object on a page, set to TRUE if flash appears over fwPopup */
            hideFlash: false,
            /* Set the flash wmode attribute */
            wmode: 'opaque',
            /* Automatically start videos: True/False */
            autoPlay: true,
            /* If set to true, only the close button will close the window */
            modal: false,
            /* Allow fwPopup to update the url to enable deep linking. */
            deepLinking: true,
            /* Set to false if you open forms inside fwPopup */
            keyboardAccessible: true,
            /* Called when fwPopup is closed */
            callback: function () {},
            ie6Fallback: true,
            markup: (function () {
                var markupArray = [];
                markupArray.push('<div class="fwpHolder">');

                markupArray.push('<div class="fwpTitle"></div>');
                markupArray.push('<div class="fwpTop">');

                markupArray.push('<div class="fwpLeft"></div>');
                markupArray.push('<div class="fwpMiddle"></div>');
                markupArray.push('<div class="fwpRight"></div>');

                markupArray.push('</div>');
                markupArray.push('<div class="fwpContainer">');

                markupArray.push('<div class="fwpLeft">');
                markupArray.push('</div>');
                markupArray.push('<div class="fwpContent fwpMiddle">');

                markupArray.push('<div class="fwpLoader"></div>');
                markupArray.push('<div class="fwpFade">');

                markupArray.push('<a tabindex="0" class="button-expand" title="Expand the image" aria-label="Expand the image">Expand</a>');
                markupArray.push('<div id="fwpFullRes"></div>');
                markupArray.push('<div class="fwpDetails">');

                markupArray.push('<p class="fwpDescription"></p>');
                markupArray.push('<div class="fwpSocial">{social_buttons}</div>');
                markupArray.push('<a tabindex="0" class="button-close" title="Close the modal" aria-label="Close the modal">Close</a>');

                markupArray.push('</div>');

                markupArray.push('</div>');

                markupArray.push('</div>');
                markupArray.push('<div class="fwpRight">');
                markupArray.push('</div>');

                markupArray.push('</div>');
                markupArray.push('<div class="fwpBottom">');

                markupArray.push('<div class="fwpLeft"></div>');
                markupArray.push('<div class="fwpMiddle"></div>');
                markupArray.push('<div class="fwpRight"></div>');

                markupArray.push('</div>');

                markupArray.push('</div>');
                markupArray.push('<div class="overlay"></div>');
                return markupArray.join('');
            })(),
            audioMarkup: '{image}<audio controls autoplay class="audioPlayback"><source src="{path}" type="audio/{type}" codec="{codec}"/></audio>',
            customMarkup: '',
            flashMarkup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
            iframeMarkup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
            imageMarkup: '<img id="fullResImage" src="{path}"/>',
            inlineMarkup: '<div class="fwpInline">{content}</div>',
            quicktimeMarkup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="//www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
            socialTools: '<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="//www.facebook.com/plugins/like.php?locale=en_US&href={location_href}&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden;width:500px;height:23px;" allowTransparency="true"></iframe></div>' /* html or false to disable */
        }, fwPopupSettings);

        hookWord = fwPopupSettings.hookWord;

        // Window/Keyboard events. Please note that events are namespaced ("resize.fwPopup").
        $WIN.off('resize.fwPopup')
            .on('resize.fwPopup', function () {
                resizeOverlay();
                centerPopup();
            });

        if (fwPopupSettings.keyboardAccessible) {
            $(document).off('keydown.fwPopup')
                .on('keydown.fwPopup', function (event) {
                    if ($fwPopupHolder) {
                        if ($fwPopupHolder.is(':visible')) {
                            switch (event.keyCode) {
                                case 27:
                                    event.preventDefault();
                                    if (!settings.modal) {
                                        closeModal();
                                    }
                                    break;
                            }
                        }
                    }
                });
        }

        /* Make some methods public */
        $fwPopup.close = closeModal;
        $fwPopup.initialize = initialize;
        $fwPopup.open = openModal;

        if (!$fwPopup.initialized && getHashtag(hookWord)) {
            $fwPopup.initialized = true;

            // Grab the rel index to trigger the click on the correct element.
            var hashRel = getHashtag(hookWord);
            var hashIndex = hashRel.substring(hashRel.indexOf('/')+1, hashRel.length-1);
            hashRel = hashRel.substring(0, hashRel.indexOf('/'));

            // Little timeout to make sure all the fwPopup initialize scripts has been run.
            // Useful in the event the page contains several init scripts.
            setTimeout(function () {
                $('a[' + fwPopupSettings.hook + "^='" + hashRel + "']:eq(" + hashIndex + ')').trigger('click');
            }, 50);
        }

        // Return the jQuery object for chaining. The off method is used to avoid click conflict when the plugin is called more than once.
        return matchedObjects.off('click.fwPopup')
            .on('click.fwPopup', initialize);

        /**
         * buildPopup
         *
         * Create the popup including the overlay and add it to the page.
         *
         * @private
         */
        function buildPopup() {
            // Inject Social Tool markup into General markup.
            if (settings.socialTools) {
                settings.socialTools = settings.socialTools.replace('{location_href}', encodeURIComponent(location.href));
                settings.markup = settings.markup.replace('{social_buttons}', settings.socialTools);
            } else {
                settings.markup = settings.markup.replace('{social_buttons}', '');
            }

            // Inject the markup
            $('body').append(settings.markup);

            // Set the global selectors
            $fwPopupHolder = $('.fwpHolder').addClass(settings.theme);
            $fwPopupTitle = $('.fwpTitle');
            $overlay = $('.overlay').css({
                    opacity: 0,
                    height: $(document).height(),
                    width: $WIN.width()
                })
                // On click of the overlay, close the modal (if "modal" is set to true in settings).
                .on('click', function () {
                    if (!settings.modal) {
                        closeModal();
                    }
                });

            $('a.button-close').off('click')
                .on('click', function (event) {
                    event.preventDefault();
                    closeModal();
                });

            if (settings.allowExpand) {
                $('.fwpContent .button-expand').off('click')
                    .on('click', function () {
                        // Expand the image
                        if ($(this).hasClass('button-expand')) {
                            $(this).removeClass('button-expand')
                                .addClass('button-contract')
                                .attr({
                                    'aria-label': 'Contract the image',
                                    title: 'Contract the image'
                                });
                            resize = false;
                        } else {
                            $(this).removeClass('button-contract')
                                .addClass('button-expand')
                                .attr({
                                    'aria-label': 'Expand the image',
                                    title: 'Expand the image'
                                });
                            resize = true;
                        }

                        hideContent(openModal);

                        return false;
                    });
            }

            // Hide the flash
            if (settings.hideFlash) {
                $('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css({ visibility:'hidden' });
            }

            // Set the title
            if (settings.showTitle && title) {
                $fwPopupTitle.html(unescape(title));
            } else {
                $fwPopupTitle.addClass('no_content').html('');
            }

            // Set the description
            if (description) {
                $fwPopupHolder.find('.fwpDescription')
                    .show()
                    .html(unescape(description));
            } else {
                $fwPopupHolder.find('.fwpDescription')
                    .hide();
            }

            // Center it
            centerPopup();
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
            if (resize && $fwPopupHolder) {
                scrollPosition = getScrollPosition();

                $fwPopupHolder.css({
                    top: calculateTop($fwPopupHolder.height()),
                    left: calculateLeft($fwPopupHolder.width())
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
        function closeModal() {
            if ($overlay.is(':animated')) {
                return;
            }

            $fwPopupHolder.stop()
                .find('object,embed')
                .css({ visibility:'hidden' });

            var animationSpeed = settings.animationSpeed;
            $('div.fwpHolder,div.fwpTitle,.fwpFade').fadeOut(animationSpeed, function () { $(this).remove(); });

            $overlay.fadeOut(animationSpeed, function () {
                // Show the flash
                if (settings.hideFlash) {
                    $('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css({ visibility:'visible' });
                }

                // Remove the popup markup from the page.
                $(this).remove();

                $WIN.off('scroll.fwPopup');

                clearHashtag(hookWord);

                settings.callback();

                resize = true;

                isOpen = false;
            });
        };

        /**
         * fitToViewport
         *
         * Resize the item dimensions if it's bigger than the viewport
         *
         * @param        width        {Integer}    Width of the item to be opened.
         * @param        height       {Integer}    Height of the item to be opened.
         * @return    JSON            The "fitted" dimensions with the properties "width", "height", containerHeight", containerWidth", "contentHeight", "contentWidth", and "resized".
         * @private
         */
        function fitToViewport(width, height) {
            getDimensions(width, height);

            var resized = false;
            // Define them in case there's no resize needed
            var imageWidth = width;
            var imageHeight = height;

            if (((fwPopupContainerWidth>windowWidth) || (fwPopupContainerHeight>windowHeight)) && resize && settings.allowResize && !percentBased) {
                resized = true;
                var fitting = false;

                while (!fitting) {
                    if (fwPopupContainerWidth>windowWidth) {
                        imageWidth = (windowWidth-200);
                        imageHeight = (height/width)*imageWidth;
                    } else if (fwPopupContainerHeight>windowHeight) {
                        imageHeight = (windowHeight-200);
                        imageWidth = (width/height)*imageHeight;
                    } else {
                        fitting = true;
                    }

                    fwPopupContainerHeight = imageHeight;
                    fwPopupContainerWidth = imageWidth;
                }

                if ((fwPopupContainerWidth>windowWidth) || (fwPopupContainerHeight>windowHeight)) {
                    fitToViewport(fwPopupContainerWidth, fwPopupContainerHeight);
                }

                getDimensions(imageWidth, imageHeight);
            }

            return {
                width: Math.floor(imageWidth),
                height: Math.floor(imageHeight),
                containerHeight: Math.floor(fwPopupContainerHeight),
                containerWidth: Math.floor(fwPopupContainerWidth)+(settings.horizontalPadding*2),
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
         * @param        width     {Integer}    Width of the item to be opened.
         * @param        height    {Integer}    Height of the item to be opened.
         * @private
         */
        function getDimensions(width, height) {
            width = parseFloat(width);
            height = parseFloat(height);

            // Get the details height; to do so, clone it since it's invisible
            var $details = $fwPopupHolder.find('.fwpDetails');
            $details.width(width);
            var detailsHeight = parseFloat($details.css('marginTop'))+parseFloat($details.css('marginBottom'));

            $details = $details.clone()
                .addClass(settings.theme)
                .width(width)
                .appendTo($('body'))
                .css({
                    position: 'absolute',
                    top: -10000
                });
            detailsHeight += $details.height();
            // Min-height for the details.
            detailsHeight = (detailsHeight<=34) ? 36 : detailsHeight;
            // Remove the clone.
            $details.remove();

            // Get the titles height; to do so, clone it since it's invisible
            var $title = $fwPopupHolder.find('.fwpTitle');
            $title.width(width);
            var titleHeight = parseFloat($title.css('marginTop'))+parseFloat($title.css('marginBottom'));
            $title = $title.clone()
                .appendTo($('body'))
                .css({
                    position: 'absolute',
                    top: -10000
                });
            titleHeight += $title.height();
            // Remove the clone.
            $title.remove();

            // Get the container size to resize the holder to the correct dimensions.
            fwPopupContentHeight = height+detailsHeight;
            fwPopupContentWidth = width;
            fwPopupContainerHeight = fwPopupContentHeight+titleHeight+$fwPopupHolder.find('.fwpTop').height()+$fwPopupHolder.find('.fwpBottom').height();
            fwPopupContainerWidth = width;
        };

        /**
         * getFileType
         *
         * Get the type of media to be opened.
         *
         * @param        source    {String}    The path to the media to be opened.
         * @return    String    The type of media to be displayed.
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
         * @param        callback    {function}    A callback function to call after hiding the content.
         * @private
         */
        function hideContent(callback) {
            // Fade out the current picture
            $fwPopupHolder.find('#fwpFullRes object,#fwpFullRes embed').css({ visibility:'hidden' });
            $fwPopupHolder.find('.fwpFade').fadeOut(settings.animationSpeed, function () {
                $('.fwpLoader').show();
                callback();
            });
        };

        /**
         * initialize
         *
         * Initialize fwPopup.
         *
         * @private
         */
        function initialize() {
            // Set the passed settings to the global(within the plugin) variable.
            settings = fwPopupSettings;
            var $initiatingElement = $(this);
            var hook = settings.hook;

            // Put the SRCs, TITLEs, and ALTs into variables.
            path = $initiatingElement.attr('href');
            title = $initiatingElement.find('img').attr('alt');
            mediaData = $initiatingElement.data();
            description = $initiatingElement.attr('title');

            // Define where in the array the clicked item is positioned.
            elementIndex = $( 'a[' + hook + "^='" + hookWord + "']").index($initiatingElement);

            // Build the popup.
            buildPopup();

            if (settings.allowResize) {
                $WIN.on('scroll.fwPopup', centerPopup);
            }

            openModal();

            return false;
        };

        /**
         * openModal
         *
         * Opens the fwPopup modal box.
         *
         * @param        event           {Event}           The JavaScript Event triggering this method.
         * @param        arguments[0]    {String,Array}    Full path to the media to be displayed, may also be an Array containing full paths.
         * @param        arguments[1]    {String,Array}    The title to be displayed with the media, may also be an Array of titles.
         * @param        arguments[2]    {String,Array}    The description to be displayed with the media, may also be an Array of descriptions.
         * @param        arguments[3]    {Integer}         The position of the media to be displayed in a slideshow.
         * @private
         */
        function openModal() {
            $('.fwpLoader').show();

            if (settings.deepLinking) {
                setHashtag(hookWord, elementIndex);
            }

            // Fade the content in
            if ($fwPopupTitle.is(':hidden')) {
                $fwPopupTitle.css('opacity', 0).show();
            }
            $overlay.show()
                .fadeTo(settings.animationSpeed, settings.opacity);

            // Get the dimensions
            var mediaWidth = (parseFloat(getParam('width', path))) ? getParam('width', path) : settings.defaultWidth.toString();
            var mediaHeight = (parseFloat(getParam('height', path))) ? getParam('height', path) : settings.defaultHeight.toString();

            // If the size is % based, calculate according to window dimensions.
            percentBased=false;
            if (mediaHeight.indexOf('%')+1) {
                mediaHeight = parseFloat(($WIN.height()*parseFloat(mediaHeight)/100)-150);
                percentBased = true;
            }
            if (mediaWidth.indexOf('%')+1) {
                mediaWidth = parseFloat(($WIN.width()*parseFloat(mediaWidth)/100)-150);
                percentBased = true;
            }

            // Fade the holder
            $fwPopupHolder.fadeIn(settings.animationSpeed, function () {
                // Create variables and set the value to 0 so they evaluate to false when checked.
                var imgPreloader = 0;
                var skipInjection = 0;

                var movie;

                // Inject the proper content
                switch (getFileType(path)) {
                    case 'ajax':
                        // Make sure the dimensions are not resized.
                        resize = false;
                        fwPopupDimensions = fitToViewport(mediaWidth, mediaHeight);
                        // Reset the resize variable to true.
                        resize = true;

                        skipInjection = true;
                        $.get(path, function (responseHTML) {
                            markupToAdd = settings.inlineMarkup.replace(/{content}/g, responseHTML);
                            $fwPopupHolder.find('#fwpFullRes')[0].innerHTML = markupToAdd;
                            showContent();
                        });
                        break;

                    case 'audio':
                        imgPreloader = new Image();

                        imgPreloader.onload = function () {
                            var audio = new Audio();
                            var image = ((mediaData.image) ? '<img src="' + mediaData.image + '" alt="Cover for ' + description + '"/>' : '');
                            var tempMarkup = settings.audioMarkup.replace(/{type}/g, type.type);
                            tempMarkup = tempMarkup.replace(/{codec}/g, type.codec);
                            tempMarkup = tempMarkup.replace(/{image}/g, image);
                            $fwPopupHolder.find('#fwpFullRes')[0].innerHTML = tempMarkup.replace(/{path}/g, path);
                            $('.fwpContainer audio').width(imgPreloader.width);
                            audio.setAttribute('src', path);
                            // Required for 'older' browsers.
                            audio.load();
                            // Fit item to viewport.
                            fwPopupDimensions = fitToViewport(imgPreloader.width, (imgPreloader.height+30));
                            showContent();
                        };
                        imgPreloader.src = mediaData.image;
                        break;

                    case 'custom':
                        // Fit item to viewport
                        fwPopupDimensions = fitToViewport(mediaWidth, mediaHeight);
                        markupToAdd = settings.customMarkup;
                        break;

                    case 'flash':
                        // Fit item to viewport
                        fwPopupDimensions = fitToViewport(mediaWidth, mediaHeight);

                        var flashVars = path.substring(path.indexOf('flashvars')+10, path.length);
                        var fileName = path.substring(0, path.indexOf('?'));

                        markupToAdd = settings.flashMarkup.replace(/{width}/g, fwPopupDimensions.width)
                            .replace(/{height}/g, fwPopupDimensions.height)
                            .replace(/{wmode}/g, settings.wmode)
                            .replace(/{path}/g, fileName + '?' + flashVars);
                        break;

                    case 'iframe':
                        // Fit item to viewport
                        fwPopupDimensions = fitToViewport(mediaWidth, mediaHeight);

                        var frameUrl = path.substr(0, path.indexOf('iframe')-1);

                        markupToAdd = settings.iframeMarkup.replace(/{width}/g, fwPopupDimensions.width)
                            .replace(/{height}/g, fwPopupDimensions.height)
                            .replace(/{path}/g, frameUrl);
                        break;

                    case 'image':
                        imgPreloader = new Image();

                        $fwPopupHolder.find('#fwpFullRes')[0].innerHTML = settings.imageMarkup.replace(/{path}/g, path);

                        imgPreloader.onload = function () {
                            // Fit item to viewport.
                            fwPopupDimensions = fitToViewport(imgPreloader.width, imgPreloader.height);
                            showContent();
                        };

                        imgPreloader.onerror = function () {
                            alert('Image cannot be loaded. Make sure the path is correct and image exist.');
                            closeModal();
                        };

                        imgPreloader.src = path;
                        break;

                    case 'inline':
                        // To get the item height clone it, apply default width, wrap it in the fwPopup containers, then delete the clone.
                        var clone = $(path).clone()
                            .append('<br clear="all">')
                            .css({ width:settings.defaultWidth })
                            .wrapInner('<div id="fwpFullRes"><div class="fwpInline"></div></div>')
                            .appendTo($('body'))
                            .show();
                        // Make sure the dimensions are not resized.
                        resize = false;
                        fwPopupDimensions = fitToViewport($(clone).width(), $(clone).height());
                        // Reset the resize variable to true.
                        resize = true;
                        // Delete the clone.
                        $(clone).remove();
                        markupToAdd = settings.inlineMarkup.replace(/{content}/g, $(path).html());
                        break;

                    case 'quicktime':
                        // Fit item to viewport.
                        fwPopupDimensions = fitToViewport(mediaWidth, mediaHeight);
                        fwPopupDimensions.height += 15;
                        fwPopupDimensions.contentHeight += 15;
                        // Add space for the control bar
                        fwPopupDimensions.containerHeight += 15;

                        markupToAdd = settings.quicktimeMarkup.replace(/{width}/g, fwPopupDimensions.width)
                            .replace(/{height}/g, fwPopupDimensions.height)
                            .replace(/{wmode}/g, settings.wmode)
                            .replace(/{path}/g, path)
                            .replace(/{autoplay}/g, settings.autoPlay);
                        break;

                    case 'vimeo':
                        // Fit item to viewport
                        fwPopupDimensions = fitToViewport(mediaWidth, mediaHeight);

                        var vimeoId = path;
                        var regExp = /http(s?):\/\/(www\.)?vimeo.com\/(\d+)/;
                        var match = vimeoId.match(regExp);
                        var vimeoWidth = fwPopupDimensions.width + '/embed/?moog_width=' + fwPopupDimensions.width;

                        movie = '//player.vimeo.com/video/'+ match[3] + '?title=0&byline=0&portrait=0';
                        if (settings.autoPlay) {
                            movie += '&autoplay=1;';
                        }

                        markupToAdd = settings.iframeMarkup.replace(/{width}/g, vimeoWidth)
                            .replace(/{height}/g, fwPopupDimensions.height)
                            .replace(/{path}/g, movie);
                        break;

                    case 'youtube':
                        // Fit item to viewport
                        fwPopupDimensions = fitToViewport(mediaWidth, mediaHeight);

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

                        if (settings.autoPlay) {
                            movie += '&autoplay=1';
                        }

                        markupToAdd = settings.iframeMarkup.replace(/{width}/g, fwPopupDimensions.width)
                            .replace(/{height}/g, fwPopupDimensions.height)
                            .replace(/{wmode}/g, settings.wmode)
                            .replace(/{path}/g, movie);
                        break;
                }

                if (!imgPreloader && !skipInjection) {
                    $fwPopupHolder.find('#fwpFullRes')[0].innerHTML = markupToAdd;
                    // Show content
                    showContent();
                }
            });

            return false;
        };

        /**
         * resizeOverlay
         *
         * Initialize fwPopup.
         *
         * @private
         */
        function resizeOverlay() {
            windowHeight = $WIN.height();
            windowWidth = $WIN.width();

            if ($overlay) {
                $overlay.height($(document).height()).width(windowWidth);
            }
        };

        /**
         * showContent
         *
         * Set the proper sizes on the containers and animate the content in.
         *
         * @private
         */
        function showContent() {
            $('.fwpLoader').hide();

            // Calculate the opened top position of the pic holder
            var top = calculateTop(fwPopupDimensions.containerHeight);
            var animationSpeed = settings.animationSpeed;

            $fwPopupTitle.fadeTo(animationSpeed, 1);

            // Resize the content holder
            $fwPopupHolder.find('.fwpContent')
                .animate({
                    height: fwPopupDimensions.contentHeight,
                    width: fwPopupDimensions.contentWidth
                }, animationSpeed);

            // Resize picture the holder
            $fwPopupHolder.animate(
                {
                    top: top,
                    left: ((windowWidth/2) - (fwPopupDimensions.containerWidth/2)<0) ? 0 : (windowWidth/2)-(fwPopupDimensions.containerWidth/2),
                    width: fwPopupDimensions.containerWidth
                },
                animationSpeed,
                function () {
                    $fwPopupHolder.find('.fwpHoverContainer,#fullResImage')
                        .height(fwPopupDimensions.height)
                        .width(fwPopupDimensions.width);
                    // Fade the new content.
                    $fwPopupHolder.find('.fwpFade')
                        .fadeIn(animationSpeed);

                    if (settings.allowExpand) {
                        // Fade the resizing link if the image is resized
                        if (fwPopupDimensions.resized) {
                            $('a.button-expand,a.button-contract').show();
                        } else {
                            $('a.button-expand').hide();
                        }
                    }

                    isOpen = true;
                }
            );

            fwPopupSettings.ajaxCallback();
        };
    };

    /**
     * clearHashtag
     *
     * Clears all hash values from the URL except the hook word.
     *
     * @private
     */
    function clearHashtag(hookWord) {
        if (location.href.indexOf('#' + hookWord)+1) {
            location.hash = hookWord;
        }
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
     * getHashtag
     *
     * Gets the value of the hook word from the URL hash. If there is
     * no hook word in the hash, this returns false.
     *
     * @private
     */
    function getHashtag(hookWord) {
        var url = location.href;
        var hookWordIndex = url.indexOf('#' + hookWord)+1;
        var hashtag = (hookWordIndex) ? decodeURI(url.substring(hookWordIndex, url.length)) : false;
        return hashtag;
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
        if (window.pageYOffset) {
            return { scrollTop:window.pageYOffset, scrollLeft:window.pageXOffset };
        }
        // Explorer 6 Strict
        if (document.documentElement && document.documentElement.scrollTop) {
            return { scrollTop:document.documentElement.scrollTop, scrollLeft:document.documentElement.scrollLeft };
        }
        // all other Explorers
        if (document.body) {
            return { scrollTop:document.body.scrollTop, scrollLeft:document.body.scrollLeft };
        }
    };

    /**
     * setHashtag
     *
     * Sets the current element index to the hash.
     *
     * @private
     */
    function setHashtag(hookWord, elementIndex) {
        // hookWord is set on normal calls, it's impossible to deeplink using the API
        if (typeof hookWord == 'undefined') {
            return;
        }
        location.hash = hookWord + ((elementIndex || elementIndex === 0) ? '/' + elementIndex + '/' : '');
    };