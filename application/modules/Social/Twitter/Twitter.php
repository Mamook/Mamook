<?php /* Requires PHP5+ */

if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the TwitterOAuth class.
require_once MODULES.'Social'.DS.'Twitter'.DS.'twitteroauth.php';


/***
 * Twitter
 *
 * The Twitter class accesses Twitter data info. It is a wrapper class for
 * @abraham's PHP twitteroauth Library.
 */
class Twitter extends TwitterOAuth
{
	/*** data members ***/

	private $data=NULL;
	private $tweets=NULL;
	private $twitter_site='http://Twitter.com/';

	/*** End data members ***/



	/*** magic methods ***/

	public function __construct($params)
	{
		# Check if oath_token and oath_token_secret were passed.
		if(!isset($params['oauth_token']) OR !isset($params['oauth_token_secret']))
		{
			# Explicitly set them to NULL.
			$params['oauth_token']=NULL;
			$params['oauth_token_secret']=NULL;
		}
		# Make sure the parent constructor is called.
		parent::__construct($params['consumer_key'], $params['consumer_secret'], $params['oauth_token'], $params['oauth_token_secret']);
	} #==== End -- __construct

	/**
	 * TwitterCallback
	 *
	 * Initial action when controller is accessed.
	 *
	 * @access	public
	 */
	public function TwitterCallback($consumer_key=TWITTER_CONSUMER_KEY, $consumer_secret=TWITTER_CONSUMER_SECRET)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		# If the oauth_token is old redirect to the connect page.
		if(isset($_REQUEST['oauth_token']) && $_SESSION['twitter_oauth_token'] !== $_REQUEST['oauth_token'])
		{
			# Remove the user's oauth status.
			$_SESSION['twitter_oauth_status']='';
			$doc->setError('Twitter authentication expired.');
		}

		# Load the Twitter library with app key/secret and token key/secret from default phase.
		$params=array(
			'consumer_key'=>$consumer_key,
			'consumer_secret'=>$consumer_secret,
			'oauth_token'=>$_SESSION['twitter_oauth_token'],
			'oauth_token_secret'=>$_SESSION['twitter_oauth_token_secret']
		);

		# Request access tokens from twitter.
		$access_token=$this->getAccessToken($_REQUEST['oauth_verifier']);

		# Save the access tokens. Normally these would be saved in a database for future use.
		$_SESSION['access_token']=$access_token;

		# Remove no longer needed request tokens.
		$_SESSION['oauth_token']='';
		$_SESSION['oauth_token_secret']='';

		# If HTTP response is 200 continue otherwise send to connect page to retry.
		if(200==$this->http_code)
		{
			# The user has been verified and the access tokens can be saved for future use.
			$_SESSION['status']='verified';
		}
		else
		{
		  # Save HTTP status for error dialog on connect page.
		  $doc->setError('Not able to connect to Twitter with that authentication.');
		}

	} #==== End -- TwitterCallback

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setData
	 *
	 * Sets the data member $data.
	 *
	 * @param	$data
	 * @access	public
	 */
	public function setData($data)
	{
		$this->data=$data;
	} #==== End -- setData

	/**
	 * setTweet
	 *
	 * Sets data to the the data member array $tweets.
	 * The "aspect" is used as the index (ie message, time, image, user).
	 *
	 * @param	$post
	 * @access	public
	 */
	public function setTweet($post, $index, $aspect=NULL)
	{
		if($aspect!==NULL)
		{
			$this->tweets[$index][$aspect]=$post;
		}
		else
		{
			$this->tweets[$index]=$post;
		}
	} #==== End -- setTweet

	/**
	 * setTwitterSite
	 *
	 * Sets the data member $twitter_site.
	 *
	 * @param		$url
	 * @access	public
	 */
	public function setTwitterSite($url)
	{
		$this->twitter_site=$url;
	} #==== End -- setTwitterSite

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getData
	 *
	 * Returns the data member $data.
	 *
	 * @access	public
	 */
	public function getData()
	{
		return $this->data;
	} #==== End -- getData

	/**
	 * getTweets
	 *
	 * Returns the data member $tweets.
	 *
	 * @access	public
	 */
	public function getTweets()
	{
		return $this->tweets;
	} #==== End -- getTweets

	/**
	 * getTwitterSite
	 *
	 * Returns the data member $twitter_site.
	 *
	 * @access	public
	 */
	public function getTwitterSite()
	{
		return $this->twitter_site;
	} #==== End -- getTwitterSite

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * getMaxShortURL_Length
	 *
	 * Returns the maximum length of t.co URLs.
	 *
	 * @access	public
	 */
	public function getMaxShortURL_Length()
	{
		# Send an API request to verify credentials.
		$credentials=$this->get('account/verify_credentials');
		# Send the data to Twitter.
		$config=$this->get('help/configuration');
		# Return the longest length between the two.
		return max($config->short_url_length_https, $config->short_url_length);
	} #==== End -- getMaxShortURL_Length

	/**
	 * getTwitterFeed
	 *
	 * Gets Twitter feeds.
	 *
	 * @param	$consumer_key
	 * @param	$consumer_secret
	 * @param	$limit
	 * @param	$user_only
	 * @access	public
	 */
	public function getTwitterFeed($limit=20, $user_only=FALSE, $username=TWITTER_USERNAME)
	{
		# Set the params and get the Twitter data.
		$params=array('screen_name'=>$username, 'include_entities'=>TRUE, 'include_rts'=>TRUE, 'count'=>$limit);
		$posts=$this->loadTwitter('statuses/user_timeline', $params);

		# Variable used to count how many we've loaded.
		$count=0;

		# Loop through the posts returned from facebook.
		foreach($posts as $post)
		{
			# Check if only posts posted by the user are to be displayed.
			if($user_only===TRUE)
			{
				# Only show posts that are posted by the page admin.
				if($post->user->screen_name==$username)
				{
					# Set the useable Twitter data to the data member array.
					$this->extractTwitterData($post, $count);
					# Increment counter
					$count++;
					# Check if we've outputted the number set above in $limit.
					if($count >= $limit) { break; }
				}
			}
			else
			{
 				# Set the useable Twitter data to the data member array
 				$this->extractTwitterData($post, $count);
 				# Increment counter
 				$count++;
 				# Check if we've outputted the number set above in $limit.
 				if($count >= $limit) { break; }
			}
			# Set the number of Tweets retrieved to the data member array.
			$this->setTweet($count, 'post_count');
		}

		return $this->getTweets();
	} #==== End -- getTwitterFeed

	/**
	 * postToTwitter
	 *
	 * Posts to Twitter feed.
	 *
	 * @param	$consumer_key
	 * @param	$consumer_secret
	 * @param	$limit
	 * @param	$user_only
	 * @access	public
	 */
	public function postToTwitter($msg)
	{
		# Set the params and get the Twitter data.
		$params=array('include_entities'=>TRUE, 'status'=>$msg);
		return $this->sendTweet('statuses/update', $params);
	} #==== End -- postToTwitter

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * extractTwitterData
	 *
	 * Pulls returned Twitter data out of the passed object and sets it to a usable data member array.
	 *
	 * @param		$twitter_data
	 * @param		$count
	 * @access	private
	 */
	private function extractTwitterData($twitter_data, $count)
	{
		# Set the $twitter_data object to the data member array.
		$this->setTweet($twitter_data, $count, 'social_data');
		# Get the post date / time and convert to unix time.
		$time=strtotime($twitter_data->created_at);
		# Format the date / time into something human readable.
		$twitter_time=date("M j, Y g:ia", $time);
		# Set the date / time to a variable.
		$this->setTweet($twitter_time, $count, 'time');
		# Set the poster's name to a variable.
		$this->setTweet(((isset($twitter_data->user->name)) ? $twitter_data->user->name : NULL), $count, 'poster');
		# Set the message body to a variable.
		$this->setTweet(((isset($twitter_data->text)) ? $twitter_data->text : NULL), $count, 'msg');
		# Set the user's url to a variable.
		$this->setTweet(((isset($twitter_data->user->screen_name)) ? $this->getTwitterSite().$twitter_data->user->screen_name.'/' : NULL), $count, 'user_url');
		# Set the social network name (Twitter) to the data member array.
		$this->setTweet('Twitter', $count, 'network');
	} #==== End -- extractTwitterData

	/**
	 * loadTwitter
	 *
	 * Retrieves posts from Twitter's server.
	 *
	 * @param	$method
	 * @param	$params
	 * @access	private
	 */
	private function loadTwitter($method, $params)
	{
		# Get the data from Twitter.
		$posts=$this->get($method, $params);
		# Set the returned data to the data member.
		$this->setData($posts);

		return $this->getData();
	} #==== End -- loadTwitter

	/**
	 * sendTweet
	 *
	 * Sends a Tweet to Twitter's server.
	 *
	 * @param		$method
	 * @param		$params
	 * @access	private
	 */
	private function sendTweet($method, $params)
	{
		# Send an API request to verify credentials.
		$credentials=$this->get('account/verify_credentials');
		# Send the data to Twitter.
		$tweet=$this->post($method, $params);
		return $tweet;
	} #==== End -- sendTweet

	/*** End private methods ***/

} #=== End Twitter class.