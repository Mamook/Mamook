<?php /* application/js/scripts.php */

	# If Javascript is enabled in the user's browser, display errors in a java created div.
	$js=$this->addJSErrorBox();

	$js.=
	'$("#wrapper").removeClass("noscript");$(function(){clearInput("#emailGo","value")});'.
	'$(function(){$("[rel^='.FW_POPUP_HANDLE.']").fwPopup({opacity:1,theme:"",social_tools:null})});'.
	'$(".addthis_button_google_plusone").attr("g:plusone:annotation","none");';

/*	$bigVersion.=
	'// Remove the "noscript" class from the wrapper indicating that the browser does support JavaScript.
	$("#wrapper").removeClass("noscript");
	// Initiate the clearInput function on the "emailGo" element.
	$(function(){clearInput("#emailGo", "value")});
	// Initiate the fwPopup function to support popup play of videos and display of images.
	$(function(){
		$("[rel^='.FW_POPUP_HANDLE.']").fwPopup({
			opacity:									1,
			theme:										"",
			show_title:								false,
			markup:										\'<div class="fwpHolder">\' +
																	\'<div class="fwpTitle">&nbsp;</div>\' +
																	\'<div class="fwpContainer">\' +
																		\'<div class="fwpContent">\' +
																			\'<div class="fwpLoader"></div>\' +
																			\'<div class="fwp_fade">\' +
																				\'<a href="#" class="button-expand" title="Expand the image">Expand</a>\' +
																				\'<div class="fwp_hoverContainer">\' +
																					\'<a class="fwpPrevious" href="#">previous</a>\' +
																					\'<a class="fwpNext" href="#">next</a>\' +
																				\'</div>\' +
																				\'<div id="fwpFullRes"></div>\' +
																				\'<div class="fwpDetails">\' +
																					\'<p class="fwpDescription"></p>\' +
																					\'{social_buttons}\' +
																					\'<a class="button-close" href="#">Close</a>\' +
																					\'<div class="fwpNav">\' +
																						\'<a href="#" class="fwpArrow-previous">Previous</a>\' +
																						\'<p class="currentTextHolder">0/0</p>\' +
																						\'<a href="#" class="fwpArrow-next">Next</a>\' +
																					\'</div>\' +
																				\'</div>\' +
																			\'</div>\' +
																		\'</div>\' +
																	\'</div>\' +
																\'</div>\' +
																\'<div class="overlay"></div>\',
			social_tools:'.
			(($main_content->getUseSocial()!==NULL) ?
																'\'<!-- AddThis Button BEGIN -->\' +
																\'<div class="addthis_default_style fwpSocialBox">\' +
																	\'<a class="addthis_button_preferred_1"></a>\' +
																	\'<a class="addthis_button_preferred_2"></a>\' +
																	\'<a class="addthis_button_google_plusone"></a>\' +
																	\'<a class="addthis_button_preferred_4"></a>\' +
																	\'<a class="addthis_button_compact"></a>\' +
																	\'<a class="addthis_counter addthis_bubble_style"></a>\' +
																\'</div>\' +
																\'<!-- AddThis Button END -->\',
			changepicturecallback:		function(){
																	addthis.toolbox(".fwpSocialBox")
																}'
		:
			'null'
		).
	'})});'.
	'$(".addthis_button_google_plusone").attr("g:plusone:annotation", "none");'; */