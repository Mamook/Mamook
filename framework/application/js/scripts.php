<?php /* application/js/scripts.php */

	# If Javascript is enabled in the user's browser, display errors in a java created div.
	$js=$this->addJSErrorBox();

	$js.=
	'$("#wrapper").removeClass("noscript");$(function(){clearInput("#emailGo","value")});'.
	'$(function(){$("[rel^='.FW_POPUP_HANDLE.']").fwPopup({opacity:1,theme:"",social_tools:null})});'.
	'$(".addthis_button_google_plusone").attr("g:plusone:annotation","none");';