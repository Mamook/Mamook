<?php /* framework/application/js/AddThis.php */

$facebook_id=((defined('FB_PAGE_ID')) ? FB_PAGE_ID : '');
$rss_id=((defined('RSS_ID')) ? RSS_ID : '');
$twitter_id=((defined('TWITTER_USERNAME')) ? TWITTER_USERNAME : '');
$vimeo_id=((defined('VIMEO_ID')) ? VIMEO_ID : '');
$youtube_id=((defined('YOUTUBE_CHANNELID')) ? YOUTUBE_CHANNELID : '');

$js='(function(c){';

$js.='var d=document.getElementsByTagName("head")[0],c=document.createElement("script");c.type="text/javascript";c.src="//s7.addthis.com/js/300/addthis_widget.js?pubid='.ADDTHIS_ID.'";d.appendChild(c);a(window).load(function(){function a(b){0!==b&&(window.addthis?addthis.layers({theme:"transparent",share:{position:"right",numPreferredServices:5},follow:{services:[{service:"facebook",id:"'.$facebook_id.'"},{service:"twitter",id:"'.$twitter_id.'"},{service:"youtube",id:"'.$youtube_id.'"},{service:"vimeo",
id:"'.$vimeo_id.'"},{service:"rss",id:"'.$rss_id.'"}]},whatsnext:{},recommended:{title:"'.DOMAIN_NAME.' recommends for you:"}}):(b||(b=30),setTimeout(function(){a(b-1)},7>b?100*b:100)))}a()});a.fwPopup.social_tools=\'<div class="addthis_default_style pp_social_box"><a class="addthis_button_preferred_1"></a><a class="addthis_button_preferred_2"></a><a class="addthis_button_google_plusone"></a><a class="addthis_button_preferred_4"></a><a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a></div>\';
a.fwPopup.changepicturecallback=function(){addthis.toolbox(".pp_social_box")}';

$js.='})(jQuery);'

/* Big Version
$js='// Wrap the script to protect the global namespace.
(function ($) {
	// Include the addThis script in the head of the page.
	var head = document.getElementsByTagName("head")[0];
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.src = "//s7.addthis.com/js/300/addthis_widget.js?pubid='.ADDTHIS_ID.'";
	head.appendChild(script);

	// Wait for the window to be loaded.
	$(window).load(function () {
		AddThisLoaded();

		function AddThisLoaded(numOfReps) {
			if(numOfReps!==0)
			{
				if(window.addthis)
				{
					init();
				}
				else
				{
					if(!numOfReps)
						numOfReps=30;
					var delay=numOfReps<7?(numOfReps)*100:100;
					setTimeout(function () {
						AddThisLoaded(numOfReps-1)
					}, delay);
				}
			}
		};

		function init() {
			addthis.layers({
				theme: "transparent",
				share: { position:"right", numPreferredServices:5 },
				follow: {
					services: [
						{
							service: "facebook",
							id: "'.$facebook_id.'"
						},
						{
							service: "twitter",
							id: "'.$twitter_id.'"
						},
						{
							service: "youtube",
							id: "'.$youtube_id.'"
						},
						{
							service: "vimeo",
							id: "'.$vimeo_id.'"
						},
						{
							service: "rss",
							id: "'.$rss_id.'"
						}
					]
				},
				whatsnext: {},
				recommended: { title:"'.DOMAIN_NAME.' recommends for you:" }
			});
		};
	});

	$.fwPopup.social_tools="<div class=\"addthis_default_style pp_social_box\"><a class=\"addthis_button_preferred_1\"></a><a class=\"addthis_button_preferred_2\"></a><a class=\"addthis_button_google_plusone\"></a><a class=\"addthis_button_preferred_4\"></a><a class=\"addthis_button_compact\"></a><a class=\"addthis_counter addthis_bubble_style\"></a></div>";
	$.fwPopup.changepicturecallback=function(){addthis.toolbox(".pp_social_box")};

})(jQuery);';
*/