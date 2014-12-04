<?php /* public/secure/xhr/index.php */

# Get the Social Class.
require_once Utility::locateFile(MODULES.'Social'.DS.'Twitter.php');
# Set the Twitter constructor params to an array.
$params=array(
	'consumer_key'=>TWITTER_CONSUMER_KEY,
	'consumer_secret'=>TWITTER_CONSUMER_SECRET
);
$twitter=new Twitter($params);
$twitter->TwitterCallback();