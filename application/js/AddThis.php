<?php /* application/js/AddThis.php */
# Set the Validator instance to a variable.
$validator=Validator::getInstance();
$js='var head=document.getElementsByTagName("head")[0];'.
'var script=document.createElement("script");'.
'script.type="text/javascript";'.
'script.src="//s7.addthis.com/js/300/addthis_widget.js?pubid='.ADDTHIS_ID.'";'.
'head.appendChild(script);';
//$js.='var addthis_config={"data_track_addressbar":true};';

$js.='addthis.layers({'.
	"theme:'transparent',".
	"share:{".
		"position:'right',".
		"numPreferredServices:5".
	'},'.
	'follow:{'.
  'services:['.
	'{'.
		"service:'facebook',".
		"id:''".
	'},'.
	'{'.
		"service:'twitter',".
		"id:''".
	'},'.
	'{'.
		"service:'youtube',".
		"id:''".
	'},'.
	'{'.
		"service:'vimeo',".
		"id:''".
	'},'.
	'{'.
		"service:'rss',".
		"id:''".
	'}'.
  ']'.
'},'.
'whatsnext:{},'.
'recommended:{'.
  "title:'".DOMAIN_NAME." recommends for you:'".
'}'.
'});';