<?php /* application/js/AddThis.php */

$js='var head=document.getElementsByTagName("head")[0];'.
'var script=document.createElement("script");'.
'script.type="text/javascript";'.
'script.src="//s7.addthis.com/js/300/addthis_widget.js?pubid='.ADDTHIS_ID.'";'.
'head.appendChild(script);';
//$js.='var addthis_config={"data_track_addressbar":true};';

$js.='addthis.layers({theme:transparent,share:{position:"right",numPreferredServices:5},follow:{services:[{service:"facebook",id:""},{service:"twitter",id:""},{service:"youtube",id:""},{service:"vimeo",id:""},{service:"rss",id:""}]},whatsnext:{},recommended:{title:"'.DOMAIN_NAME.' recommends for you:"}});';

$js.='$.prettyPhoto.social_tools=\'<\!-- AddThis Button BEGIN --\><div class="addthis_default_style pp_social_box"><a class="addthis_button_preferred_1"></a><a class="addthis_button_preferred_2"></a><a class="addthis_button_google_plusone"></a><a class="addthis_button_preferred_4"></a><a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a></div><\!-- AddThis Button END --\>\','.
'$.prettyPhoto.changepicturecallback:function(){addthis.toolbox(".pp_social_box")}})});';

/* $bigVersion=
'// Include the addThis script in the head of the page.
var head = document.getElementsByTagName("head")[0];
var script = document.createElement("script");
script.type = "text/javascript";
script.src = "//s7.addthis.com/js/300/addthis_widget.js?pubid='.ADDTHIS_ID.'";
head.appendChild(script);';
$bigVersion.=
'// Set the addthis options.
addthis.layers({
	theme:			transparent,
	share:			{
								position:							"right",
								numPreferredServices:	5
							},
	follow:			{
  							services:	[
														{
															service:	"facebook",
															id:				""
														},
														{
															service:	"twitter",
															id:				""
														},
														{
															service:	"youtube",
															id:				""
														},
														{
															service:	"vimeo",
															id:				""
														},
														{
															service:	"rss",
															id:				""
														}
  												]
							},
	whatsnext:	{},
	recommended:{
  							title:		"'.DOMAIN_NAME.' recommends for you:"
							}
});'; */