<?php /* framework/application/modules/API/TwitterAPI.php */

if(!defined('BASE_PATH')) exit('No direct script access allowed');


/***
 * TwitterAPI
 *
 * The Twitter class accesses Twitter data info. It is a wrapper class for
 * @abraham's PHP twitteroauth Library.
 */
class TwitterAPI
{
	/*** data members ***/

	private $twitter_obj;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setTwitterObj
	 *
	 * Sets the data member $twitter_obj.
	 *
	 * @param	obj $twitter_obj
	 * @access	public
	 */
	public function setTwitterObj($twitter_obj)
	{
		# Check if the passed value is empty.
		if(!empty($twitter_obj))
		{
			# Set the data member.
			$this->twitter_obj=$twitter_obj;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->twitter_obj=NULL;
		}
	} #==== End -- setTwitterObj

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getTwitterObj
	 *
	 * Returns the data member $twitter_obj.
	 *
	 * @access	private
	 */
	private function getTwitterObj()
	{
		return $this->twitter_obj;
	} #==== End -- getTwitterObj

	/*** End accessor methods ***/



	/*** magic methods ***/

	/**
	 * __contruct
	 *
	 * Loads the Twitter PHP library and instantiates it.
	 *
	 * @access	public
	 */
	public function __construct()
	{
		# Get the Twitter API Class.
		require_once Utility::locateFile(MODULES.'Social'.DS.'Twitter'.DS.'autoload.php');
		# Check if there is a Twitter object.
		if(empty($this->twitter_obj) OR !is_object($this->twitter_obj))
		{
			# Instantiate a new Twitter object.
			$twitter_obj=new Abraham\TwitterOAuth\TwitterOAuth(
				TWITTER_CONSUMER_KEY,
				TWITTER_CONSUMER_SECRET,
				TWITTER_TOKEN,
				TWITTER_TOKEN_SECRET
			);
			$this->setTwitterObj($twitter_obj);
		}
		return $this->getTwitterObj();
	} #==== End -- __construct

	/*** End magic methods ***/



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
		$credentials=$this->getTwitterObj()->get('account/verify_credentials');
		# Send the data to Twitter.
		$config=$this->getTwitterObj()->get('help/configuration');
		# Return the longest length between the two.
		return max($config->short_url_length_https, $config->short_url_length);
	} #==== End -- getMaxShortURL_Length

	/**
	 * postToTwitter
	 *
	 * Posts to Twitter feed.
	 *
	 * @param	string $msg
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
	 * sendTweet
	 *
	 * Sends a Tweet to Twitter's server.
	 *
	 * @param	string $method
	 * @param	array $params
	 * @access	private
	 */
	private function sendTweet($method, $params)
	{
		# Send an API request to verify credentials.
		$credentials=$this->getTwitterObj()->get('account/verify_credentials');
		# Send the data to Twitter.
		$tweet=$this->getTwitterObj()->post($method, $params);
		return $tweet;
	} #==== End -- sendTweet

	/*** End private methods ***/

} #=== End Twitter class.