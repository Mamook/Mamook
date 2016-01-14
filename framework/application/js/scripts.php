<?php /* application/js/scripts.php */

	# If Javascript is enabled in the user's browser, display errors in a java created div.
	$js=$this->addJSErrorBox();

	# Open "window load" (better than "document ready") to put all JavaScript calls inside of.
	$js.=
	'$(window).load(function(){';

	# Remove the "noscript" class. If there is no JavaScript available, this class will remain.
	$js.=
	'$("body").removeClass("noscript");';

	# If the user's device is mobile, add the "mobile" class.
	$js.=
	'if(fwIsMobile)$("body").addClass("mobile");';

	# Execute "clearInput".
	$js.=
	'clearInput("#emailGo","value");';

	# Instantiate "fwPopup".
	$js.=
	'$(function(){$("[rel^='.FW_POPUP_HANDLE.']").fwPopup({opacity:1,theme:"",social_tools:null})});';

	# Add the Google+ button to the AddThis buttons.
	$js.=
	'$(".addthis_button_google_plusone").attr("g:plusone:annotation","none");';

	# Instantiate the "fwNav" on the main nav for small screens (mobile).
	//$js.=
	//'$(".mainnav").fwNav({navList:".mainnav .nav-1",navHandle:"mainnavHandle",maxContainerWidth:"100%",maxContainerHeight:"300%",minContainerWidth:69,minContainerHeight:43});';

	# Instantiate the "fwNav" on the box2 nav for small screens (mobile)
	//$js.=
	//'$(".box2").fwNav({navList:".box2 .nav-1",navHandle:"box2Handle",maxContainerWidth:"100%",maxContainerHeight:"300%",minContainerWidth:47,minContainerHeight:29});';

	# Close "window load".
	$js.=
	'});';