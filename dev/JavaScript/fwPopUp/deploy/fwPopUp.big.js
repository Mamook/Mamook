/* DO NOT MODIFY THIS FILE! IT IS GENERATED. CHANGES SHOULD BE MADE IN THE SOURCE FILES. */

/**
 * fwPopup
 *
 * Lightbox clone for jQuery.
 * Originally written by Stephane Caron (http://www.no-margin-for-errors.com). Re-written by BigTalk Jon Ryser
 *
 * @author		Jon Ryser 	http://JonRyser.com
 * @version		1.0.0
 */
(function($){
	var jQuery_fwPopup = $.fwPopup = {
		// Used for the deep linking to make sure not to call the same function several times.
		initialized:	false,
		version:			'1.0.0'
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
	var pp_contentHeight;
	var pp_contentWidth
	var pp_containerHeight
	var pp_containerWidth;

	var $pp_pic_holder;
	var $ppt;
	var $pp_overlay;

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
				theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
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
						markupArray.push('<div class="pp_pic_holder">');
							markupArray.push('<div class="ppt">&nbsp;</div>');
							markupArray.push('<div class="pp_top">');
								markupArray.push('<div class="pp_left"></div>');
								markupArray.push('<div class="pp_middle"></div>');
								markupArray.push('<div class="pp_right"></div>');
							markupArray.push('</div>');
							markupArray.push('<div class="pp_content_container">');
								markupArray.push('<div class="pp_left">');
									markupArray.push('<div class="pp_right">');
										markupArray.push('<div class="pp_content">');
											markupArray.push('<div class="pp_loaderIcon"></div>');
											markupArray.push('<div class="pp_fade">');
												markupArray.push('<a href="javascript:void(0)" class="pp_expand" title="Expand the image">Expand</a>');
												markupArray.push('<div class="pp_hoverContainer">');
													markupArray.push('<a class="pp_next" href="javascript:void(0)">next</a>');
													markupArray.push('<a class="pp_previous" href="javascript:void(0)">previous</a>');
												markupArray.push('</div>');
												markupArray.push('<div id="pp_full_res"></div>');
												markupArray.push('<div class="pp_details">');
													markupArray.push('<div class="pp_nav">');
														markupArray.push('<a href="javascript:void(0)" class="pp_arrow_previous">Previous</a>');
														markupArray.push('<p class="currentTextHolder">0/0</p>');
														markupArray.push('<a href="javascript:void(0)" class="pp_arrow_next">Next</a>');
													markupArray.push('</div>');
													markupArray.push('<p class="pp_description"></p>');
													markupArray.push('<div class="pp_social">{pp_social}</div>');
													markupArray.push('<a class="pp_close" href="javascript:void(0)">Close</a>');
												markupArray.push('</div>');
											markupArray.push('</div>');
										markupArray.push('</div>');
									markupArray.push('</div>');
								markupArray.push('</div>');
								markupArray.push('<div class="pp_bottom">');
									markupArray.push('<div class="pp_left"></div>');
									markupArray.push('<div class="pp_middle"></div>');
									markupArray.push('<div class="pp_right"></div>');
								markupArray.push('</div>');
							markupArray.push('</div>');
						markupArray.push('</div>');
						markupArray.push('<div class="pp_overlay"></div>');
						return markupArray.join('');
					})(),
				audio_markup: '{image}<audio controls autoplay class="audioPlayback"><source src="{path}" type="audio/{type}" codec="{codec}"/></audio>',
				custom_markup: '',
				flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
				gallery_markup: (function(){
					var markupArray = [];
						markupArray.push('<div class="pp_gallery">');
							markupArray.push('<a href="javascript:void(0)" class="pp_arrow_previous">Previous</a>');
							markupArray.push('<div>');
								markupArray.push('<ul>');
									markupArray.push('{gallery}');
								markupArray.push('</ul>');
							markupArray.push('</div>');
							markupArray.push('<a href="javascript:void(0)" class="pp_arrow_next">Next</a>');
						markupArray.push('</div>');
						return markupArray.join('');
					})(),
				iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
				image_markup: '<img id="fullResImage" src="{path}"/>',
				inline_markup: '<div class="pp_inline">{content}</div>',
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
					if($pp_pic_holder)
					{
						if($pp_pic_holder.is(':visible'))
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

			if(settings.theme == 'pp_default')
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

			$('.pp_loaderIcon').show();

			if(settings.deeplinking)
				setHashtag();

			// Rebuild Facebook Like Button with updated href
			if(settings.social_tools)
			{
				var facebook_like_link = settings.social_tools.replace('{location_href}', encodeURIComponent(location.href));
				$pp_pic_holder.find('.pp_social')
					.html(facebook_like_link);
			}

			// Fade the content in
			if($ppt.is(':hidden'))
				$ppt.css('opacity', 0).show();
			$pp_overlay.show()
				.fadeTo(settings.animation_speed, settings.opacity);

			// Display the current position
			$pp_pic_holder.find('.currentTextHolder')
				.text((setPosition+1) + settings.counter_separator_label + $(paths).size());

			// Set the description
			if(typeof descriptions[setPosition] != 'undefined' && descriptions[setPosition] != '')
			{
				$pp_pic_holder.find('.pp_description')
					.show()
					.html(unescape(descriptions[setPosition]));
			}
			else
			{
				$pp_pic_holder.find('.pp_description')
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
			$pp_pic_holder.fadeIn(function(){
				// Set the title
				(settings.show_title && titles[setPosition] != '' && typeof titles[setPosition] != 'undefined') ? $ppt.html(unescape(titles[setPosition])) : $ppt.addClass('no_content').html('');

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
							$pp_pic_holder.find('#pp_full_res')[0].innerHTML = markupToAdd;
							showContent();
						});
						break;

					case 'audio':
						// Fit item to viewport
						popupDimensions = fitToViewport(mediaData[setPosition].width, mediaData[setPosition].height);

						var audio = new Audio();
						var image = ((mediaData[setPosition].image) ? '<img src="' + mediaData[setPosition].image + '" alt="Cover for ' + descriptions[setPosition] + '"/>' : '');
						var tempMarkup = settings.audio_markup.replace(/{type}/g, type.type);
						tempMarkup = tempMarkup.replace(/{codec}/g, type.codec);
						tempMarkup = tempMarkup.replace(/{image}/g, image);
						markupToAdd = tempMarkup.replace(/{path}/g, paths[setPosition]);

						audio.setAttribute('src', paths[setPosition]);
						// Required for 'older' browsers.
						audio.load();
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

						$pp_pic_holder.find('#pp_full_res')[0].innerHTML = settings.image_markup.replace(/{path}/g, paths[setPosition]);

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
							.wrapInner('<div id="pp_full_res"><div class="pp_inline"></div></div>')
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
					$pp_pic_holder.find('#pp_full_res')[0].innerHTML = markupToAdd;
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
				$('.pp_contract').removeClass('pp_contract')
					.addClass('pp_expand');
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

			$pp_gallery.find('ul')
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
				$pp_pic_holder.find('.pp_play')
					.off('click')
					.removeClass('pp_play')
					.addClass('pp_pause')
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
			$pp_pic_holder.find('.pp_pause')
				.off('click')
				.removeClass('pp_pause')
				.addClass('pp_play')
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
			if($pp_overlay.is(':animated'))
				return;

			jQuery_fwPopup.stopSlideshow();

			$pp_pic_holder.stop()
				.find('object,embed')
				.css({visibility:'hidden'});

			var animationSpeed = settings.animation_speed;
			$('div.pp_pic_holder,div.ppt,.pp_fade').fadeOut(animationSpeed, function(){ $(this).remove(); });

			$pp_overlay.fadeOut(animationSpeed, function(){
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
				settings.markup = settings.markup.replace('{pp_social}', settings.social_tools);
			}
			else
				settings.markup = settings.markup.replace('{pp_social}', '');

			// Inject the markup
			$('body').append(settings.markup);

			// Set the global selectors
			$pp_pic_holder = $('.pp_pic_holder');
			$ppt = $('.ppt');
			$pp_overlay = $('div.pp_overlay');

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

				$pp_pic_holder.find('#pp_full_res')
					.after(markupToAdd);

				$pp_gallery = $('.pp_pic_holder .pp_gallery');
				// Set the gallery selectors
				$pp_gallery_li = $pp_gallery.find('li');

				$pp_gallery.find('.pp_arrow_next')
					.click(function(){
						jQuery_fwPopup.changeGalleryPage('next');
						jQuery_fwPopup.stopSlideshow();
						return false;
					});

				$pp_gallery.find('.pp_arrow_previous')
					.click(function(){
						jQuery_fwPopup.changeGalleryPage('previous');
						jQuery_fwPopup.stopSlideshow();
						return false;
					});

				$pp_pic_holder.find('.pp_content')
					.hover(
						function(){
							$pp_pic_holder.find('.pp_gallery:not(.disabled)')
								.fadeIn();
						},
						function(){
							$pp_pic_holder.find('.pp_gallery:not(.disabled)')
								.fadeOut();
						}
					);

				$pp_gallery_li.each(function(index){
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
				$pp_pic_holder.find('.pp_nav')
					.prepend('<a href="#" class="pp_play">Play</a>');
				$pp_pic_holder.find('.pp_nav .pp_play')
					.click(function(){
						jQuery_fwPopup.startSlideshow();
						return false;
					});
			}

			// Set the proper theme
			$pp_pic_holder.attr('class', 'pp_pic_holder ' + settings.theme);

			$pp_overlay.css({
					opacity:	0,
					height:		$(DOC).height(),
					width:		WIN.width()
				})
				.on('click',function(){
					if(!settings.modal)
						jQuery_fwPopup.close();
				});

			$('a.pp_close').on('click', function(){
				jQuery_fwPopup.close();
				return false;
			});

			if(settings.allow_expand)
			{
				$('a.pp_expand').on('click', function(){
					// Expand the image
					if($(this).hasClass('pp_expand'))
					{
						$(this).removeClass('pp_expand')
							.addClass('pp_contract');
						resize = false;
					}
					else
					{
						$(this).removeClass('pp_contract')
							.addClass('pp_expand');
						resize = true;
					};

					hideContent(function(){
						jQuery_fwPopup.open();
					});

					return false;
				});
			}

			$pp_pic_holder.find('.pp_previous, .pp_nav .pp_arrow_previous').on('click', function(){
				jQuery_fwPopup.changePage('previous');
				jQuery_fwPopup.stopSlideshow();
				return false;
			});

			$pp_pic_holder.find('.pp_next, .pp_nav .pp_arrow_next').on('click', function(){
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
			if(resize && $pp_pic_holder)
			{
				scrollPosition = getScrollPosition();
				var popupHeight = $pp_pic_holder.height();
				var popupWidth = $pp_pic_holder.width();
				var top = calculateTop(popupHeight);

				if(popupHeight>windowHeight)
					return;

				$pp_pic_holder.css({
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
			(setCount>1) ? $('.pp_nav').show() : $('.pp_nav').hide();
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

			if(((pp_containerWidth>windowWidth) || (pp_containerHeight>windowHeight)) && resize && settings.allow_resize && !percentBased) {
				resized = true;
				var fitting = false;

				while(!fitting)
				{
					if(pp_containerWidth>windowWidth)
					{
						imageWidth = (windowWidth-200);
						imageHeight = (height/width)*imageWidth;
					}
					else if(pp_containerHeight>windowHeight)
					{
						imageHeight = (windowHeight-200);
						imageWidth = (width/height)*imageHeight;
					}
					else
						fitting = true;

					pp_containerHeight = imageHeight;
					pp_containerWidth = imageWidth;
				}

				if((pp_containerWidth>windowWidth) || (pp_containerHeight>windowHeight))
					fitToViewport(pp_containerWidth, pp_containerHeight)

				getDimensions(imageWidth, imageHeight);
			}

			return {
				width:						Math.floor(imageWidth),
				height:						Math.floor(imageHeight),
				containerHeight:	Math.floor(pp_containerHeight),
				containerWidth:		Math.floor(pp_containerWidth)+(settings.horizontal_padding*2),
				contentHeight:		Math.floor(pp_contentHeight),
				contentWidth:			Math.floor(pp_contentWidth),
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
			$pp_details = $pp_pic_holder.find('.pp_details');
			$pp_details.width(width);
			var detailsHeight = parseFloat($pp_details.css('marginTop'))+parseFloat($pp_details.css('marginBottom'));

			$pp_details = $pp_details.clone()
				.addClass(settings.theme)
				.width(width)
				.appendTo($('body'))
				.css({
					position:	'absolute',
					top:			-10000
				});
			detailsHeight += $pp_details.height();
			// Min-height for the details.
			detailsHeight = (detailsHeight<=34) ? 36 : detailsHeight;
			// Remove the clone.
			$pp_details.remove();

			// Get the titles height; to do so, clone it since it's invisible
			$pp_title = $pp_pic_holder.find('.ppt');
			$pp_title.width(width);
			var titleHeight = parseFloat($pp_title.css('marginTop'))+parseFloat($pp_title.css('marginBottom'));
			$pp_title = $pp_title.clone()
				.appendTo($('body'))
				.css({
					position:	'absolute',
					top:			-10000
				});
			titleHeight += $pp_title.height();
			// Remove the clone.
			$pp_title.remove();

			// Get the container size to resize the holder to the correct dimensions.
			pp_contentHeight = height+detailsHeight;
			pp_contentWidth = width;
			pp_containerHeight = pp_contentHeight+titleHeight+$pp_pic_holder.find('.pp_top').height()+$pp_pic_holder.find('.pp_bottom').height();
			pp_containerWidth = width;
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
			$pp_pic_holder.find('#pp_full_res object,#pp_full_res embed').css({visibility:'hidden'});
			$pp_pic_holder.find('.pp_fade').fadeOut(settings.animation_speed, function(){
				$('.pp_loaderIcon').show();
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
				var navWidth = (settings.theme == 'facebook' || settings.theme == 'pp_default') ? 50 : 30;

				itemsPerPage = Math.floor((popupDimensions['containerWidth']-100-navWidth)/itemWidth);
				itemsPerPage = (itemsPerPage<paths.length) ? itemsPerPage : paths.length;
				totalPage = Math.ceil(paths.length/itemsPerPage)-1;

				// Hide the nav in the case there's no need for links
				if(totalPage == 0)
				{
					// No nav means no width!
					navWidth = 0;
					$pp_gallery.find('.pp_arrow_next,.pp_arrow_previous').hide();
				}
				else
					$pp_gallery.find('.pp_arrow_next,.pp_arrow_previous').show();

				var galleryWidth = itemsPerPage*itemWidth;
				var fullGalleryWidth = paths.length*itemWidth;
				var goToPage = (Math.floor(setPosition/itemsPerPage)<totalPage) ? Math.floor(setPosition/itemsPerPage) : totalPage;

				// Set the proper width to the gallery items
				$pp_gallery
					.css('margin-left', -((galleryWidth/2)+(navWidth/2)))
					.find('div:first').width(galleryWidth+5)
					.find('ul').width(fullGalleryWidth)
					.find('li.selected').removeClass('selected');

				jQuery_fwPopup.changeGalleryPage(goToPage);

				$pp_gallery_li.filter(':eq(' + setPosition + ')')
					.addClass('selected');
			}
			else
			{
				$pp_pic_holder.find('.pp_content')
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

			if($pp_overlay)
				$pp_overlay.height($(DOC).height()).width(windowWidth);
		};


		/**
		 * showContent
		 *
		 * Set the proper sizes on the containers and animate the content in.
		 *
		 * @private
		 */
		function showContent(){
			$('.pp_loaderIcon').hide();

			// Calculate the opened top position of the pic holder
			var top = calculateTop(popupDimensions['containerHeight']);
			var animationSpeed = settings.animation_speed;

			$ppt.fadeTo(animationSpeed, 1);

			// Resize the content holder
			$pp_pic_holder.find('.pp_content')
				.animate({
					height:	popupDimensions['contentHeight'],
					width:	popupDimensions['contentWidth']
				}, animationSpeed);

			// Resize picture the holder
			$pp_pic_holder.animate(
				{
					top:		top,
					left:		((windowWidth/2) - (popupDimensions['containerWidth']/2)<0) ? 0 : (windowWidth/2)-(popupDimensions['containerWidth']/2),
					width:	popupDimensions['containerWidth']
				},
				animationSpeed,
				function(){
					$pp_pic_holder.find('.pp_hoverContainer,#fullResImage')
						.height(popupDimensions['height'])
						.width(popupDimensions['width']);
					// Fade the new content.
					$pp_pic_holder.find('.pp_fade')
						.fadeIn(animationSpeed);
					// Show the nav.
					if(isSet && getFileType(paths[setPosition])=="image")
						$pp_pic_holder.find('.pp_hoverContainer').show();
					else
						$pp_pic_holder.find('.pp_hoverContainer').hide();

					if(settings.allow_expand)
					{
						// Fade the resizing link if the image is resized
						if(popupDimensions['resized'])
							$('a.pp_expand,a.pp_contract').show();
						else
							$('a.pp_expand').hide();
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





////////////////////////////////
// END MAIN NAMESPACE (class) //
////////////////////////////////