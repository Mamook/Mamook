<?php /* framework/application/js/AddThis.php Hi Draven */

$facebook_id=((defined('FB_PAGE_ID')) ? FB_PAGE_ID : '');
$rss_id=((defined('RSS_ID')) ? RSS_ID : '');
$twitter_id=((defined('TWITTER_USERNAME')) ? TWITTER_USERNAME : '');
$vimeo_id=((defined('VIMEO_ID')) ? VIMEO_ID : '');
$youtube_id=((defined('YOUTUBE_CHANNELID')) ? YOUTUBE_CHANNELID : '');

$js='var head=document.getElementsByTagName("head")[0];'.
'var script=document.createElement("script");'.
'script.type="text/javascript";'.
'script.src="//s7.addthis.com/js/300/addthis_widget.js?pubid='.ADDTHIS_ID.'";'.
'head.appendChild(script);';
//$js.='var addthis_config={"data_track_addressbar":true};';

$js.='$(window).load(function(){function b(a){0!==a&&(window.addthis?addthis.layers({theme:"transparent",share:{position:"right",numPreferredServices:5},follow:{services:[{service:"facebook",id:"'.$facebook_id.'"},{service:"twitter",id:"'.$twitter_id.'"},{service:"youtube",id:"'.$youtube_id.'"},{service:"vimeo",id:"'.$vimeo_id.'"},{service:"rss",id:"'.$rss_id.'"}]},whatsnext:{},recommended:{title:"'.DOMAIN_NAME.' recommends for you:"}}):(a||(a=30),setTimeout(function(){b(a-1)},7>a?100*a:100)))}b()});';


$js.='$.fwPopup.social_tools=\'<div class="addthis_default_style pp_social_box"><a class="addthis_button_preferred_1"></a><a class="addthis_button_preferred_2"></a><a class="addthis_button_google_plusone"></a><a class="addthis_button_preferred_4"></a><a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a></div>\';'.
'$.fwPopup.changepicturecallback=function(){addthis.toolbox(".pp_social_box")};';

/* $bigVersion=
'// Include the addThis script in the head of the page.
var head = document.getElementsByTagName("head")[0];
var script = document.createElement("script");
script.type = "text/javascript";
script.src = "//s7.addthis.com/js/300/addthis_widget.js?pubid='.ADDTHIS_ID.'";
head.appendChild(script);';

$bigVersion.=
'$(window).load(function(){

	AddThisLoaded();

	function AddThisLoaded(numOfReps){
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
				setTimeout(function(){
					AddThisLoaded(numOfReps-1)
				}, delay);
			}
		}
	};

	function init(){
		addthis.layers({
			theme:"transparent",
			share:{position:"right",numPreferredServices:5},
			follow:{services:[{
					service:"facebook",
					id:"'.$facebook_id.'"
				},
				{
					service:"twitter",
					id:"'.$twitter_id.'"
				},
				{
					service:"youtube",
					id:"'.$youtube_id.'"
				},
				{
					service:"vimeo",
					id:"'.$vimeo_id.'"
				},
				{
					service:"rss",
					id:"'.$rss_id.'"
				}
			]},
			whatsnext:{},
			recommended:{title:"'.DOMAIN_NAME.' recommends for you:"}
		});
	};
});'; */