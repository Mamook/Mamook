/* DO NOT MODIFY THIS FILE! IT IS GENERATED. CHANGES SHOULD BE MADE IN THE SOURCE FILES. */

/**
 * fwPopup
 *
 * Lightbox clone for jQuery.
 * Originally written by Stephane Caron (http://www.no-margin-for-errors.com). Re-written by BigTalk Jon Ryser
 *
 * @author		Jon Ryser 	http://JonRyser.com
 * @version		1.0.2
 */
(function($){
	var jQuery_fwPopup = $.fwPopup = {
		// Used for the deep linking to make sure not to call the same function several times.
		initialized:	false,
		version:			'1.0.2'
	};

	// Create a variable to hold the String that hooks an element to the plugin, making it globally available throughout the plugin.
	var hookWord;

	var DOC = document;
	var WIN = $(window);
	var popupDimensions;
	var elementIndex;
	var isOpen;
	// Create a variable to hold the passed settings, making them globally available throughout the plugin.
	var settings;

	// fwPopup container specific
	var fwPopupContentHeight;
	var fwPopupContentWidth;
	var fwPopupContainerHeight;
	var fwPopupContainerWidth;

	var $fwPopupHolder;
	var $fwPopupTitle;
	var $fwPopupGallery;
	var $fwPopupGalleryList;
	var $overlay;

	// Window size
	var windowHeight = WIN.height();
	var windowWidth = WIN.width();

	var slideshowIntervalId = null;

	$.fn.fwPopup = fwPopup;


	function fwPopup(fwPopup_settings){
		// Create a variable to hold the current gallery page position. Start at 0.
		var currentGalleryPage = 0;
		// Create a variable to hold the Array of descriptions collected from the passed sources.
		var descriptions;
		// Create a variable to indicate is various values are set. Set with false by default.
		var isSet = false;
		// Create a variable to hold the number of gallery items per page.
		var itemsPerPage;
		// Create a variable to hold the width of gallery items. 52 beign the thumb width, 5 being the right margin.
		var itemWidth = 52+5;
		// Create a variable to hold the markup to add to the general markup. By default, set it to an empty String.
		var markupToAdd = '';
		var matchedObjects = this;
		// Create a variable to hold the JavaScript object of data collected from the passed sources.
		var mediaData;
		// Create a variable to hold the Array of the paths to the media to display collected from the passed sources.
		var paths;
		// Create a variable indicating if the widths and heights are percentages. false by default.
		var percentBased = false;
		// Create a variable that indicates whether the popup should be resized or not. Default is false.
		var resize = true;
		var scrollPosition = getScrollPosition();
		// Create a variable to hold the position of the media to be displayed in a slideshow.
		var setPosition;
		// Create a variable to hold the Array of titles collected from the passed sources.
		var titles;
		// Create a variable to hold the total number of pages in the popup. The default is 0.
		var totalPage = 0;
		// Create a variable to hold the media type for use with html5 audio and video.
		var type;

		// Merge the passed settings with the default settings.
		fwPopup_settings = jQuery.extend(
			{
				hook: 'data-popUp', /* the attribute tag to use for fwPopup hooks. default: 'data-popUp'. For HTML5, use "data-popUp" or similar. For pre-HTML5, use "rel". */
				hookWord:	'fwPopup',
				animation_speed: 'fast', /* fast/slow/normal */
				ajaxcallback: function() {},
				slideshow: 5000, /* false OR interval time in ms */
				autoplay_slideshow: false, /* true/false */
				opacity: 0.80, /* Value between 0 and 1 */
				show_title: true, /* true/false */
				allow_resize: true, /* Resize the photos bigger than viewport. true/false */
				allow_expand: true, /* Allow the user to expand a resized image. true/false */
				default_width: 500,
				default_height: 344,
				counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
				theme: 'default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
				horizontal_padding: 20, /* The padding on each side of the picture */
				hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over fwPopup */
				wmode: 'opaque', /* Set the flash wmode attribute */
				autoplay: true, /* Automatically start videos: True/False */
				modal: false, /* If set to true, only the close button will close the window */
				deeplinking: true, /* Allow fwPopup to update the url to enable deeplinking. */
				overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
				overlay_gallery_max: 30, /* Maximum number of pictures in the overlay gallery */
				keyboard_shortcuts: true, /* Set to false if you open forms inside fwPopup */
				changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
				callback: function(){}, /* Called when fwPopup is closed */
				ie6_fallback: true,
				markup: (function(){
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
									markupArray.push('<div class="fwpRight">');
										markupArray.push('<div class="fwpContent">');
											markupArray.push('<div class="fwpLoader"></div>');
											markupArray.push('<div class="fwpFade">');
												markupArray.push('<a href="javascript:void(0)" class="button-expand" title="Expand the image">Expand</a>');
												markupArray.push('<div class="fwpHoverContainer">');
													markupArray.push('<a class="fwpNext" href="javascript:void(0)">next</a>');
													markupArray.push('<a class="fwpPrevious" href="javascript:void(0)">previous</a>');
												markupArray.push('</div>');
												markupArray.push('<div id="fwpFullRes"></div>');
												markupArray.push('<div class="fwpDetails">');
													markupArray.push('<div class="fwpNav">');
														markupArray.push('<a href="javascript:void(0)" class="fwpArrow-previous">Previous</a>');
														markupArray.push('<p class="currentTextHolder">0/0</p>');
														markupArray.push('<a href="javascript:void(0)" class="fwpArrow-next">Next</a>');
													markupArray.push('</div>');
													markupArray.push('<p class="fwpDescription"></p>');
													markupArray.push('<div class="fwpSocial">{social_buttons}</div>');
													markupArray.push('<a class="button-close" href="javascript:void(0)">Close</a>');
												markupArray.push('</div>');
											markupArray.push('</div>');
										markupArray.push('</div>');
									markupArray.push('</div>');
								markupArray.push('</div>');
								markupArray.push('<div class="fwpBottom">');
									markupArray.push('<div class="fwpLeft"></div>');
									markupArray.push('<div class="fwpMiddle"></div>');
									markupArray.push('<div class="fwpRight"></div>');
								markupArray.push('</div>');
							markupArray.push('</div>');
						markupArray.push('</div>');
						markupArray.push('<div class="overlay"></div>');
						return markupArray.join('');
					})(),
				audio_markup: '{image}<audio controls autoplay class="audioPlayback"><source src="{path}" type="audio/{type}" codec="{codec}"/></audio>',
				custom_markup: '',
				flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
				gallery_markup: (function(){
					var markupArray = [];
						markupArray.push('<div class="fwpGallery">');
							markupArray.push('<a href="javascript:void(0)" class="fwpArrow-previous">Previous</a>');
							markupArray.push('<div>');
								markupArray.push('<ul>');
									markupArray.push('{gallery}');
								markupArray.push('</ul>');
							markupArray.push('</div>');
							markupArray.push('<a href="javascript:void(0)" class="fwpArrow-next">Next</a>');
						markupArray.push('</div>');
						return markupArray.join('');
					})(),
				iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
				image_markup: '<img id="fullResImage" src="{path}"/>',
				inline_markup: '<div class="fwpInline">{content}</div>',
				quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="//www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
				social_tools: '<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="//www.facebook.com/plugins/like.php?locale=en_US&href={location_href}&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden;width:500px;height:23px;" allowTransparency="true"></iframe></div>' /* html or false to disable */
			},
			fwPopup_settings
		);

		hookWord = fwPopup_settings.hookWord;

		// Window/Keyboard events. Please note that events are namespaced ("resize.fwPopup").
		WIN.off('resize.fwPopup')
			.on('resize.fwPopup', function(){
				centerPopup();
				resizeOverlay();
			});

		if(fwPopup_settings.keyboard_shortcuts)
		{
			$(DOC).off('keydown.fwPopup')
				.on('keydown.fwPopup', function(event){
					if($fwPopupHolder)
					{
						if($fwPopupHolder.is(':visible'))
						{
							switch(event.keyCode)
							{
								case 37:
									jQuery_fwPopup.changePage('previous');
									event.preventDefault();
									break;
								case 39:
									jQuery_fwPopup.changePage('next');
									event.preventDefault();
									break;
								case 27:
									if(!settings.modal)
										jQuery_fwPopup.close();
									event.preventDefault();
									break;
							}
							// return false;
						}
					}
				});
		}


		/**
		 * initialize
		 *
		 * Initialize fwPopup.
		 *
		 * @public
		 */
		jQuery_fwPopup.initialize = function(){
			// Set the passed settings to the global(within the plugin) variable.
			settings = fwPopup_settings;
			var caller = this;
			var hook = settings.hook;
			var galleryRegExp = /\[(?:.*)\]/;

			if(settings.theme == 'default')
				settings.horizontal_padding = 16;

			// Check if the image is part of a set.
			hookWord = $(caller).attr(hook);
			isSet = galleryRegExp.exec(hookWord);

			// Put the SRCs, TITLEs, and ALTs into an array.
			paths = (isSet) ? jQuery.map(matchedObjects, function(value, index){
					if($(value).attr(hook).indexOf(hookWord)+1)
						return $(value).attr('href');
				})
				: $.makeArray($(caller).attr('href'));
			titles = (isSet) ? jQuery.map(matchedObjects, function(value, index){
					if($(value).attr(hook).indexOf(hookWord)+1)
						return ($(valuen).find('img').attr('alt')) ? $(value).find('img').attr('alt') : '';
				})
				: $.makeArray($(caller).find('img').attr('alt'));
			mediaData = (isSet) ? jQuery.map(matchedObjects, function(value, index){
					if($(value).attr(hook).indexOf(hookWord)+1)
						return ($(value).data()) ? $(value).data() : '';
				})
				: $.makeArray($(caller).data());
			descriptions = (isSet) ? jQuery.map(matchedObjects, function(value, index){
					if($(value).attr(hook).indexOf(hookWord)+1)
						return ($(value).attr('title')) ? $(value).attr('title') : '';
				})
				: $.makeArray($(caller).attr('title'));

			if(paths.length>settings.overlay_gallery_max)
				settings.overlay_gallery = false;

			// Define where in the array the clicked item is positioned.
			setPosition = jQuery.inArray($(caller).attr('href'), paths);
			elementIndex = (isSet) ? setPosition : $( 'a[' + hook + "^='" + hookWord + "']").index($(caller));

			// Build the popup.
			buildPopup(caller);

			if(settings.allow_resize)
				WIN.on('scroll.fwPopup', centerPopup);

			jQuery_fwPopup.open();

			return false;
		};


		/**
		 * open
		 *
		 * Opens the fwPopup modal box.
		 *
		 * @param		event					{Event}					The JavaScript Event triggering this method.
		 * @param		arguments[0]	{String,Array}	Full path to the media to be displayed, may also be an Array containing full paths.
		 * @param		arguments[1]	{String,Array}	The title to be displayed with the media, may also be an Array of titles.
		 * @param		arguments[2]	{String,Array}	The description to be displayed with the media, may also be an Array of descriptions.
		 * @param		arguments[3]	{Integer}				The position of the media to be displayed in a slideshow.
		 * @public
		 */
		jQuery_fwPopup.open = function(event){
			// Means it's an API call, need to manually get the settings and set the variables
			if(typeof settings == 'undefined')
			{
				settings = fwPopup_settings;
				paths = $.makeArray(arguments[0]);
				titles = (arguments[1]) ? $.makeArray(arguments[1]) : $.makeArray('');
				descriptions = (arguments[2]) ? $.makeArray(arguments[2]) : $.makeArray('');
				isSet = paths.length>1;
				setPosition = (arguments[3])? arguments[3]: 0;
				// Build the popup; "target" being the caller.
				buildPopup(event.target);
			}

			// Hide the flash
			if(settings.hideflash)
				$('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css({visibility:'hidden'});

			// Hide the next/previous links if on first or last images.
			checkPosition($(paths).size());

			$('.fwpLoader').show();

			if(settings.deeplinking)
				setHashtag();

			// Rebuild Facebook Like Button with updated href
			if(settings.social_tools)
			{
				var facebook_like_link = settings.social_tools.replace('{location_href}', encodeURIComponent(location.href));
				$fwPopupHolder.find('.fwpSocial')
					.html(facebook_like_link);
			}

			// Fade the content in
			if($fwPopupTitle.is(':hidden'))
				$fwPopupTitle.css('opacity', 0).show();
			$overlay.show()
				.fadeTo(settings.animation_speed, settings.opacity);

			// Display the current position
			$fwPopupHolder.find('.currentTextHolder')
				.text((setPosition+1) + settings.counter_separator_label + $(paths).size());

			// Set the description
			if(typeof descriptions[setPosition] != 'undefined' && descriptions[setPosition] != '')
			{
				$fwPopupHolder.find('.fwpDescription')
					.show()
					.html(unescape(descriptions[setPosition]));
			}
			else
			{
				$fwPopupHolder.find('.fwpDescription')
					.hide();
			}

			// Get the dimensions
			var mediaWidth = (parseFloat(getParam('width', paths[setPosition]))) ? getParam('width', paths[setPosition]) : settings.default_width.toString();
			var mediaHeight = (parseFloat(getParam('height', paths[setPosition]))) ? getParam('height', paths[setPosition]) : settings.default_height.toString();

			// If the size is % based, calculate according to window dimensions.
			percentBased=false;
			if(mediaHeight.indexOf('%')+1)
			{
				mediaHeight = parseFloat((WIN.height()*parseFloat(mediaHeight)/100)-150);
				percentBased = true;
			}
			if(mediaWidth.indexOf('%')+1)
			{
				mediaWidth = parseFloat((WIN.width()*parseFloat(mediaWidth)/100)-150);
				percentBased = true;
			}

			// Fade the holder
			$fwPopupHolder.fadeIn(function(){
				// Set the title
				(settings.show_title && titles[setPosition] != '' && typeof titles[setPosition] != 'undefined') ? $fwPopupTitle.html(unescape(titles[setPosition])) : $fwPopupTitle.addClass('no_content').html('');

				// Create variables and set the value to 0 so they evaluate to false when checked.
				var imgPreloader = 0;
				var skipInjection = 0;

				// Inject the proper content
				switch(getFileType(paths[setPosition]))
				{
					case 'ajax':
						// Make sure the dimensions are not resized.
						resize = false;
						popupDimensions = fitToViewport(mediaWidth, mediaHeight);
						// Reset the resize variable to true.
						resize = true;

						skipInjection = true;
						$.get(paths[setPosition], function(responseHTML){
							markupToAdd = settings.inline_markup.replace(/{content}/g, responseHTML);
							$fwPopupHolder.find('#fwpFullRes')[0].innerHTML = markupToAdd;
							showContent();
						});
						break;

					case 'audio':
						var imgPreloader = new Image();

						imgPreloader.onload = function(){
							var audio = new Audio();
							var image = ((mediaData[setPosition].image) ? '<img src="' + mediaData[setPosition].image + '" alt="Cover for ' + descriptions[setPosition] + '"/>' : '');
							var tempMarkup = settings.audio_markup.replace(/{type}/g, type.type);
							tempMarkup = tempMarkup.replace(/{codec}/g, type.codec);
							tempMarkup = tempMarkup.replace(/{image}/g, image);
							$fwPopupHolder.find('#fwpFullRes')[0].innerHTML = tempMarkup.replace(/{path}/g, paths[setPosition]);
							$('.fwpContainer audio').width(imgPreloader.width);
							audio.setAttribute('src', paths[setPosition]);
							// Required for 'older' browsers.
							audio.load();
							// Fit item to viewport.
							popupDimensions = fitToViewport(imgPreloader.width, (imgPreloader.height+30));
							showContent();
						};
						imgPreloader.src = mediaData[setPosition].image;
						break;

					case 'custom':
						// Fit item to viewport
						popupDimensions = fitToViewport(mediaWidth, mediaHeight);
						markupToAdd = settings.custom_markup;
						break;

					case 'flash':
						// Fit item to viewport
						popupDimensions = fitToViewport(mediaWidth, mediaHeight);

						var flash_vars = paths[setPosition];
						var filename = paths[setPosition];
						flash_vars = flash_vars.substring(paths[setPosition].indexOf('flashvars')+10, paths[setPosition].length);
						filename = filename.substring(0, filename.indexOf('?'));

						markupToAdd =  settings.flash_markup.replace(/{width}/g, popupDimensions['width'])
							.replace(/{height}/g, popupDimensions['height'])
							.replace(/{wmode}/g, settings.wmode)
							.replace(/{path}/g, filename + '?' + flash_vars);
						break;

					case 'iframe':
						// Fit item to viewport
						popupDimensions = fitToViewport(mediaWidth, mediaHeight);

						var frame_url = paths[setPosition];
						frame_url = frame_url.substr(0, frame_url.indexOf('iframe')-1);

						markupToAdd = settings.iframe_markup.replace(/{width}/g, popupDimensions['width'])
							.replace(/{height}/g, popupDimensions['height'])
							.replace(/{path}/g, frame_url);
						break;

					case 'image':
						imgPreloader = new Image();

						// Preload the neighboring images.
						var nextImage = new Image();
						var prevImage = new Image();
						if(isSet && setPosition<$(paths).size()-1)
							nextImage.src = paths[setPosition+1];
						if(isSet && paths[setPosition-1])
							prevImage.src = paths[setPosition-1];

						$fwPopupHolder.find('#fwpFullRes')[0].innerHTML = settings.image_markup.replace(/{path}/g, paths[setPosition]);

						imgPreloader.onload = function(){
							// Fit item to viewport.
							popupDimensions = fitToViewport(imgPreloader.width, imgPreloader.height);
							showContent();
						};

						imgPreloader.onerror = function(){
							alert('Image cannot be loaded. Make sure the path is correct and image exist.');
							jQuery_fwPopup.close();
						};

						imgPreloader.src = paths[setPosition];
						break;

					case 'inline':
						// To get the item height clone it, apply default width, wrap it in the fwPopup containers, then delete the clone.
						var clone = $(paths[setPosition]).clone()
							.append('<br clear="all">')
							.css({width:settings.default_width})
							.wrapInner('<div id="fwpFullRes"><div class="fwpInline"></div></div>')
							.appendTo($('body'))
							.show();
						// Make sure the dimensions are not resized.
						resize = false;
						popupDimensions = fitToViewport($(clone).width(), $(clone).height());
						// Reset the resize variable to true.
						resize = true;
						// Delete the clone.
						$(clone).remove();
						markupToAdd = settings.inline_markup.replace(/{content}/g, $(paths[setPosition]).html());
						break;

					case 'quicktime':
						// Fit item to viewport.
						popupDimensions = fitToViewport(mediaWidth, mediaHeight);
						popupDimensions['height'] += 15;
						popupDimensions['contentHeight'] += 15;
						// Add space for the control bar
						popupDimensions['containerHeight'] += 15;

						markupToAdd = settings.quicktime_markup.replace(/{width}/g, popupDimensions['width'])
							.replace(/{height}/g, popupDimensions['height'])
							.replace(/{wmode}/g, settings.wmode)
							.replace(/{path}/g, paths[setPosition])
							.replace(/{autoplay}/g, settings.autoplay);
						break;

					case 'vimeo':
						// Fit item to viewport
						popupDimensions = fitToViewport(mediaWidth, mediaHeight);

						var vimeoId = paths[setPosition];
						var regExp = /http(s?):\/\/(www\.)?vimeo.com\/(\d+)/;
						var match = vimeoId.match(regExp);

						movie = '//player.vimeo.com/video/'+ match[3] + '?title=0&byline=0&portrait=0';
						if(settings.autoplay)
							movie += '&autoplay=1;';

						vimeo_width = popupDimensions['width'] + '/embed/?moog_width=' + popupDimensions['width'];

						markupToAdd = settings.iframe_markup.replace(/{width}/g, vimeo_width)
							.replace(/{height}/g, popupDimensions['height'])
							.replace(/{path}/g, movie);
						break;

					case 'youtube':
						// Fit item to viewport
						popupDimensions = fitToViewport(mediaWidth, mediaHeight);

						// Regular youtube link
						var youtubeId = getParam('v', paths[setPosition]);

						// youtu.be link
						if(youtubeId == ''){
							youtubeId = paths[setPosition].split('youtu.be/');
							youtubeId = youtubeId[1];
							// Strip anything after the ?
							if(youtubeId.indexOf('?')>0)
								youtubeId = youtubeId.substr(0, youtubeId.indexOf('?'));
							// Strip anything after the &
							if(youtubeId.indexOf('&')>0)
								youtubeId = youtubeId.substr(0, youtubeId.indexOf('&'));
						}

						movie = '//www.youtube.com/embed/' + youtubeId;
						(getParam('rel', paths[setPosition])) ? movie += '?rel=' + getParam('rel', paths[setPosition]) : movie += '?rel=1';

						if(settings.autoplay) movie += '&autoplay=1';

						markupToAdd = settings.iframe_markup.replace(/{width}/g, popupDimensions['width'])
							.replace(/{height}/g, popupDimensions['height'])
							.replace(/{wmode}/g, settings.wmode)
							.replace(/{path}/g, movie);
						break;
				};

				if(!imgPreloader && !skipInjection)
				{
					$fwPopupHolder.find('#fwpFullRes')[0].innerHTML = markupToAdd;
					// Show content
					showContent();
				}
			});

			return false;
		};


		/**
		 * changePage
		 *
		 * Change page in the popup.
		 *
		 * @param	direction		{String}	Direction of the paging; "previous" or "next".
		 * @public
		 */
		jQuery_fwPopup.changePage = function(direction){
			// Reset the current gallery page to 0.
			currentGalleryPage = 0;
			if(direction == 'previous')
			{
				setPosition--;
				if(setPosition<0)
					setPosition = $(paths).size()-1;
			}
			else if(direction == 'next')
			{
				setPosition++;
				if(setPosition>$(paths).size()-1)
					setPosition = 0;
			}
			else
				setPosition=direction;

			elementIndex = setPosition;

			// Allow the resizing of the images
			if(!resize)
				resize = true;
			if(settings.allow_expand)
			{
				$('.button-contract').removeClass('button-contract')
					.addClass('button-expand');
			}

			hideContent(function(){
				jQuery_fwPopup.open();
			});
		};


		/**
		 * changeGalleryPage
		 *
		 * Change gallery page in the popup.
		 *
		 * @param	direction		{String}	Direction of the paging; "previous" or "next".
		 * @public
		 */
		jQuery_fwPopup.changeGalleryPage = function(direction){
			if(direction=='next')
			{
				currentGalleryPage ++;
				if(currentGalleryPage>totalPage)
					currentGalleryPage = 0;
			}
			else if(direction=='previous')
			{
				currentGalleryPage --;
				if(currentGalleryPage<0)
					currentGalleryPage = totalPage;
			}
			else
				currentGalleryPage = direction;

			var slideSpeed = (direction == 'next' || direction == 'previous') ? settings.animation_speed : 0;
			var slideTo = currentGalleryPage*(itemsPerPage*itemWidth);

			$fwPopupGallery.find('ul')
				.animate({left:-slideTo}, slideSpeed);
		};


		/**
		 * startSlideshow
		 *
		 * Start the slideshow.
		 *
		 * @public
		 */
		jQuery_fwPopup.startSlideshow = function(){
			if(slideshowIntervalId === null)
			{
				$fwPopupHolder.find('.fwp_play')
					.off('click')
					.removeClass('fwp_play')
					.addClass('fwp_pause')
					.click(function(){
						jQuery_fwPopup.stopSlideshow();
						return false;
					});
				slideshowIntervalId = setInterval(jQuery_fwPopup.startSlideshow, settings.slideshow);
			}
			else
				jQuery_fwPopup.changePage('next');
		};


		/**
		 * stopSlideshow
		 *
		 * Stops the slideshow.
		 *
		 * @public
		 */
		jQuery_fwPopup.stopSlideshow = function(){
			$fwPopupHolder.find('.fwp_pause')
				.off('click')
				.removeClass('fwp_pause')
				.addClass('fwp_play')
				.click(function(){
					jQuery_fwPopup.startSlideshow();
					return false;
				});
			clearInterval(slideshowIntervalId);
			slideshowIntervalId=null;
		};


		/**
		 * close
		 *
		 * Closes the popup.
		 *
		 * @public
		 */
		jQuery_fwPopup.close = function(){
			if($overlay.is(':animated'))
				return;

			jQuery_fwPopup.stopSlideshow();

			$fwPopupHolder.stop()
				.find('object,embed')
				.css({visibility:'hidden'});

			var animationSpeed = settings.animation_speed;
			$('div.fwpHolder,div.fwpTitle,.fwpFade').fadeOut(animationSpeed, function(){ $(this).remove(); });

			$overlay.fadeOut(animationSpeed, function(){
				// Show the flash
				if(settings.hideflash)
					$('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css({visibility:'visible'});

				// Remove the popup markup from the page.
				$(this).remove();

				WIN.off('scroll.fwPopup');

				clearHashtag();

				settings.callback();

				resize = true;

				isOpen = false;

				settings = undefined;
			});
		};


		/**
		 * buildPopup
		 *
		 * Create the popup including the overlay and add it to the page.
		 *
		 * @private
		 */
		function buildPopup(caller){
			// Inject Social Tool markup into General markup.
			if(settings.social_tools)
			{
				facebook_like_link = settings.social_tools.replace('{location_href}', encodeURIComponent(location.href));
				settings.markup = settings.markup.replace('{social_buttons}', settings.social_tools);
			}
			else
				settings.markup = settings.markup.replace('{social_buttons}', '');

			// Inject the markup
			$('body').append(settings.markup);

			// Set the global selectors
			$fwPopupHolder = $('.fwpHolder');
			$fwPopupTitle = $('.fwpTitle');
			$overlay = $('div.overlay');

			// Inject the inline gallery!
			if(isSet && settings.overlay_gallery)
			{
				// Reset the markupToAdd String to an empty String.
				markupToAdd = '';
				for(var i=0; i<paths.length; i++)
				{
					var classname = '';
					var mediaPath = paths[i];
					if(!paths[i].match(/\b(jpg|jpeg|png|gif)\b/gi))
					{
						classname = 'default';
						mediaPath = '';
					}
					markupToAdd += '<li class="' + classname + '"><a href="#"><img src="' + mediaPath + '" width="50" alt=""/></a></li>';
				}

				markupToAdd = settings.gallery_markup.replace(/{gallery}/g, markupToAdd);

				$fwPopupHolder.find('#fwpFullRes')
					.after(markupToAdd);

				$fwPopupGallery = $('.fwpHolder .fwpGallery');
				// Set the gallery selectors
				$fwPopupGalleryList = $fwPopupGallery.find('li');

				$fwPopupGallery.find('.fwpArrow-next')
					.click(function(){
						jQuery_fwPopup.changeGalleryPage('next');
						jQuery_fwPopup.stopSlideshow();
						return false;
					});

				$fwPopupGallery.find('.fwpArrow-previous')
					.click(function(){
						jQuery_fwPopup.changeGalleryPage('previous');
						jQuery_fwPopup.stopSlideshow();
						return false;
					});

				$fwPopupHolder.find('.fwpContent')
					.hover(
						function(){
							$fwPopupHolder.find('.fwpGallery:not(.disabled)')
								.fadeIn();
						},
						function(){
							$fwPopupHolder.find('.fwpGallery:not(.disabled)')
								.fadeOut();
						}
					);

				$fwPopupGalleryList.each(function(index){
					$(this).find('a')
						.click(function(){
							jQuery_fwPopup.changePage(index);
							jQuery_fwPopup.stopSlideshow();
							return false;
						});
				});
			}


			// Inject the play/pause if it's a slideshow
			if(settings.slideshow)
			{
				$fwPopupHolder.find('.fwpNav')
					.prepend('<a href="#" class="fwp_play">Play</a>');
				$fwPopupHolder.find('.fwpNav .fwp_play')
					.click(function(){
						jQuery_fwPopup.startSlideshow();
						return false;
					});
			}

			// Set the proper theme
			$fwPopupHolder.attr('class', 'fwpHolder ' + settings.theme);

			$overlay.css({
					opacity:	0,
					height:		$(DOC).height(),
					width:		WIN.width()
				})
				.on('click',function(){
					if(!settings.modal)
						jQuery_fwPopup.close();
				});

			$('a.button-close').on('click', function(){
				jQuery_fwPopup.close();
				return false;
			});

			if(settings.allow_expand)
			{
				$('a.button-expand').on('click', function(){
					// Expand the image
					if($(this).hasClass('button-expand'))
					{
						$(this).removeClass('button-expand')
							.addClass('button-contract');
						resize = false;
					}
					else
					{
						$(this).removeClass('button-contract')
							.addClass('button-expand');
						resize = true;
					};

					hideContent(function(){
						jQuery_fwPopup.open();
					});

					return false;
				});
			}

			$fwPopupHolder.find('.fwpPrevious, .fwpNav .fwpArrow-previous').on('click', function(){
				jQuery_fwPopup.changePage('previous');
				jQuery_fwPopup.stopSlideshow();
				return false;
			});

			$fwPopupHolder.find('.fwpNext, .fwpNav .fwpArrow-next').on('click', function(){
				jQuery_fwPopup.changePage('next');
				jQuery_fwPopup.stopSlideshow();
				return false;
			});

			// Center it
			centerPopup();
		};


		/**
		 * calculateTop
		 *
		 * Calculates the distance to the top of the screen based on the height of the container.
		 *
		 * @param		popupHeight	{Integer}	The height (in pixels) of the popup itself.
		 * @private
		 */
		function calculateTop(popupHeight){
			var top = (windowHeight/2)+scrollPosition['scrollTop']-(popupHeight/2);
			if(top<0)
				top = 0;
			return top;
		};


		/**
		 * centerPopup
		 *
		 * Centers the popup on the screen.
		 *
		 * @private
		 */
		function centerPopup(){
			if(resize && $fwPopupHolder)
			{
				scrollPosition = getScrollPosition();
				var popupHeight = $fwPopupHolder.height();
				var popupWidth = $fwPopupHolder.width();
				var top = calculateTop(popupHeight);

				if(popupHeight>windowHeight)
					return;

				$fwPopupHolder.css({
					top:	top,
					left:	(windowWidth/2)+scrollPosition['scrollLeft']-(popupWidth/2)
				});
			}
		};


		/**
		 * checkPosition
		 *
		 * Check the item position in the gallery array, hide or show the navigation links
		 *
		 * @param		setCount	{Integer}		The total number of items in the set.
		 * @private
		 */
		function checkPosition(setCount){
			// Hide the bottom nav if it's not a set.
			(setCount>1) ? $('.fwpNav').show() : $('.fwpNav').hide();
		};


		/**
		 * fitToViewport
		 *
		 * Resize the item dimensions if it's bigger than the viewport
		 *
		 * @param		width			{Integer}	Width of the item to be opened.
		 * @param		height		{Integer}	Height of the item to be opened.
		 * @return	JSON			The "fitted" dimensions with the properties "width", "height", containerHeight", containerWidth", "contentHeight", "contentWidth", and "resized".
		 * @private
		 */
		function fitToViewport(width, height){
			var resized = false;

			getDimensions(width, height);

			// Define them in case there's no resize needed
			imageWidth = width;
			imageHeight = height;

			if(((fwPopupContainerWidth>windowWidth) || (fwPopupContainerHeight>windowHeight)) && resize && settings.allow_resize && !percentBased) {
				resized = true;
				var fitting = false;

				while(!fitting)
				{
					if(fwPopupContainerWidth>windowWidth)
					{
						imageWidth = (windowWidth-200);
						imageHeight = (height/width)*imageWidth;
					}
					else if(fwPopupContainerHeight>windowHeight)
					{
						imageHeight = (windowHeight-200);
						imageWidth = (width/height)*imageHeight;
					}
					else
						fitting = true;

					fwPopupContainerHeight = imageHeight;
					fwPopupContainerWidth = imageWidth;
				}

				if((fwPopupContainerWidth>windowWidth) || (fwPopupContainerHeight>windowHeight))
					fitToViewport(fwPopupContainerWidth, fwPopupContainerHeight)

				getDimensions(imageWidth, imageHeight);
			}

			return {
				width:						Math.floor(imageWidth),
				height:						Math.floor(imageHeight),
				containerHeight:	Math.floor(fwPopupContainerHeight),
				containerWidth:		Math.floor(fwPopupContainerWidth)+(settings.horizontal_padding*2),
				contentHeight:		Math.floor(fwPopupContentHeight),
				contentWidth:			Math.floor(fwPopupContentWidth),
				resized:					resized
			};
		};


		/**
		 * getDimensions
		 *
		 * Get the containers dimensions according to the item size
		 *
		 * @param		width		{Integer}	Width of the item to be opened.
		 * @param		height	{Integer}	Height of the item to be opened.
		 * @private
		 */
		function getDimensions(width, height){
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
					position:	'absolute',
					top:			-10000
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
					position:	'absolute',
					top:			-10000
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
		 * @param		source	{String}	The path to the media to be opened.
		 * @return	String	The type of media to be displayed.
		 * @private
		 */
		function getFileType(source){
			if(source.match(/youtube\.com\/watch/i) || source.match(/youtu\.be/i))
				return 'youtube';
			if(source.match(/vimeo\.com/i))
				return 'vimeo';
			if(source.match(/\b.mov\b/i))
				return 'quicktime';
			if(source.match(/\b.swf\b/i))
				return 'flash';
			if(source.match(/\b.(mp3|ogg)\b/i))
			{
				type = {type:'mpeg',codec:'mp3'};
				if(source.match(/\b.ogg\b/i))
					type = {type:'ogg',codec:'vorbis'};
				return 'audio';
			}
			if(source.match(/\biframe=true\b/i))
				return 'iframe';
			if(source.match(/\bajax=true\b/i))
				return 'ajax';
			if(source.match(/\bcustom=true\b/i))
				return 'custom';
			if(source.substr(0, 1) == '#')
				return 'inline';
			// By default, return "image".
			return 'image';
		};


		/**
		 * hideContent
		 *
		 * Hide the content.
		 *
		 * @param		callback	{function}	A callback function to call after hiding the content.
		 * @private
		 */
		function hideContent(callback){
			// Fade out the current picture
			$fwPopupHolder.find('#fwpFullRes object,#fwpFullRes embed').css({visibility:'hidden'});
			$fwPopupHolder.find('.fwpFade').fadeOut(settings.animation_speed, function(){
				$('.fwpLoader').show();
				callback();
			});
		};


		/**
		 * insertGallery
		 *
		 * Show the Gallery nav.
		 *
		 * @private
		 */
		function insertGallery(){
			if(isSet && settings.overlay_gallery && getFileType(paths[setPosition])=='image')
			{
				// Define the arrow width depending on the theme
				var navWidth = (settings.theme == 'facebook' || settings.theme == 'default') ? 50 : 30;

				itemsPerPage = Math.floor((popupDimensions['containerWidth']-100-navWidth)/itemWidth);
				itemsPerPage = (itemsPerPage<paths.length) ? itemsPerPage : paths.length;
				totalPage = Math.ceil(paths.length/itemsPerPage)-1;

				// Hide the nav in the case there's no need for links
				if(totalPage == 0)
				{
					// No nav means no width!
					navWidth = 0;
					$fwPopupGallery.find('.fwpArrow-next,.fwpArrow-previous').hide();
				}
				else
					$fwPopupGallery.find('.fwpArrow-next,.fwpArrow-previous').show();

				var galleryWidth = itemsPerPage*itemWidth;
				var fullGalleryWidth = paths.length*itemWidth;
				var goToPage = (Math.floor(setPosition/itemsPerPage)<totalPage) ? Math.floor(setPosition/itemsPerPage) : totalPage;

				// Set the proper width to the gallery items
				$fwPopupGallery.css('margin-left', -((galleryWidth/2)+(navWidth/2)))
					.find('div:first').width(galleryWidth+5)
					.find('ul').width(fullGalleryWidth)
					.find('li.selected').removeClass('selected');

				jQuery_fwPopup.changeGalleryPage(goToPage);

				$fwPopupGalleryList.filter(':eq(' + setPosition + ')')
					.addClass('selected');
			}
			else
			{
				$fwPopupHolder.find('.fwpContent')
					.off('mouseenter mouseleave');
			}
		};


		/**
		 * resizeOverlay
		 *
		 * Initialize fwPopup.
		 *
		 * @private
		 */
		function resizeOverlay(){
			windowHeight = WIN.height();
			windowWidth = WIN.width();

			if($overlay)
				$overlay.height($(DOC).height()).width(windowWidth);
		};


		/**
		 * showContent
		 *
		 * Set the proper sizes on the containers and animate the content in.
		 *
		 * @private
		 */
		function showContent(){
			$('.fwpLoader').hide();

			// Calculate the opened top position of the pic holder
			var top = calculateTop(popupDimensions['containerHeight']);
			var animationSpeed = settings.animation_speed;

			$fwPopupTitle.fadeTo(animationSpeed, 1);

			// Resize the content holder
			$fwPopupHolder.find('.fwpContent')
				.animate({
					height:	popupDimensions['contentHeight'],
					width:	popupDimensions['contentWidth']
				}, animationSpeed);

			// Resize picture the holder
			$fwPopupHolder.animate(
				{
					top:		top,
					left:		((windowWidth/2) - (popupDimensions['containerWidth']/2)<0) ? 0 : (windowWidth/2)-(popupDimensions['containerWidth']/2),
					width:	popupDimensions['containerWidth']
				},
				animationSpeed,
				function(){
					$fwPopupHolder.find('.fwpHoverContainer,#fullResImage')
						.height(popupDimensions['height'])
						.width(popupDimensions['width']);
					// Fade the new content.
					$fwPopupHolder.find('.fwpFade')
						.fadeIn(animationSpeed);
					// Show the nav.
					if(isSet && getFileType(paths[setPosition])=="image")
						$fwPopupHolder.find('.fwpHoverContainer').show();
					else
						$fwPopupHolder.find('.fwpHoverContainer').hide();

					if(settings.allow_expand)
					{
						// Fade the resizing link if the image is resized
						if(popupDimensions['resized'])
							$('a.button-expand,a.button-contract').show();
						else
							$('a.button-expand').hide();
					}

					if(settings.autoplay_slideshow && (slideshowIntervalId === null) && !isOpen)
						jQuery_fwPopup.startSlideshow();

					// Callback!
					settings.changepicturecallback();

					isOpen = true;
				}
			);

			insertGallery();
			fwPopup_settings.ajaxcallback();
		};


		if(!jQuery_fwPopup.initialized && getHashtag())
		{
			jQuery_fwPopup.initialized = true;

			// Grab the rel index to trigger the click on the correct element.
			var hashRel = getHashtag();
			var hashIndex = hashRel.substring(hashRel.indexOf('/')+1, hashRel.length-1);
			hashRel = hashRel.substring(0, hashRel.indexOf('/'));

			// Little timeout to make sure all the fwPopup initialize scripts has been run.
			// Useful in the event the page contain several init scripts.
			setTimeout(function(){
				$('a[' + fwPopup_settings.hook+ "^='" + hashRel + "']:eq(" + hashIndex + ')').trigger('click');
			}, 50);
		}

		// Return the jQuery object for chaining. The off method is used to avoid click conflict when the plugin is called more than once.
		return matchedObjects.off('click.fwPopup')
			.on('click.fwPopup', jQuery_fwPopup.initialize);
	};


	function clearHashtag(){
		if(location.href.indexOf('#' + hookWord)+1)
			location.hash = hookWord;
	};


	function getParam(name, url){
	  name = name.replace(/[\[]/, '\\\[')
	  	.replace(/[\]]/, '\\\]');
	  var regexString = '[\\?&]' + name + '=([^&#]*)';
	  var regex = new RegExp(regexString);
	  var results = regex.exec(url);
	  return (results == null) ? '' : results[1];
	};


	function getHashtag(){
		var url = location.href;
		var hookWordIndex = url.indexOf('#' + hookWord)+1;
		var hashtag = (hookWordIndex) ? decodeURI(url.substring(hookWordIndex, url.length)) : false;
		return hashtag;
	};


	function getScrollPosition(){
		if(self.pageYOffset)
			return {scrollTop:self.pageYOffset, scrollLeft:self.pageXOffset};
		// Explorer 6 Strict
		if(DOC.documentElement && DOC.documentElement.scrollTop)
			return {scrollTop:DOC.documentElement.scrollTop, scrollLeft:DOC.documentElement.scrollLeft};
		// all other Explorers
		if(DOC.body)
			return {scrollTop:DOC.body.scrollTop, scrollLeft:DOC.body.scrollLeft};
	};


	function setHashtag(){
		// hookWord is set on normal calls, it's impossible to deeplink using the API
		if(typeof hookWord == 'undefined')
			return;
		location.hash = hookWord + '/' + elementIndex + '/';
	};
})(jQuery);


/////////////////
// END fwPopup //
/////////////////