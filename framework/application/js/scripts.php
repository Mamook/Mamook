<?php /* framework/application/js/scripts.php */

# Set the Document instance to a variable.
$doc=Document::getInstance();


# Open an anoymous auto executing function to put all JavaScript calls inside of.
$js.=
	'(function($){';


# Open "document ready" to put all JavaScript calls that need that inside of.
$js.=
	'$(function(){';

# Remove the "noscript" class. If there is no JavaScript available, this class will remain.
$js.=
	'$("body").removeClass("noscript");';

# If the user's device is mobile, add the "mobile" class.
$js.=
	'if(fwIsMobile)$("body").addClass("mobile");';

# If Javascript is enabled in the user's browser, display errors in a java created div.
$js.=
	$doc->addJSErrorBox();

# Execute "clearInput".
$js.=
	'clearInput("#emailGo","value");';

# Instantiate "fwPopup".
$js.=
	'$("[rel='.FW_POPUP_HANDLE.'],[data-fwPopup='.FW_POPUP_HANDLE.']").fwPopup('.$doc->getFwPopUpSettings().');';

# Close "document ready".
$js.=
	'});';


# Open "window load" to put all JavaScript calls that need that  inside of.
$js.=
	'$(window).load(function(){';

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


# Close the anoymous auto executing function.
$js.=
	'})(jQuery);';