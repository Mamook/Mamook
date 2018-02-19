<?php /* framework/application/modules/API/TwitterAPI.php */

namespace Mamook\API;

use Abraham\TwitterOAuth\TwitterOAuth;
use Exception;

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# TODO: Add error handling.

/**
 * TwitterAPI
 *
 * The Twitter class accesses Twitter data info.
 * It is a wrapper class for @abraham's PHP twitteroauth Library.
 *		https://github.com/abraham/twitteroauth
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
	 * @param object $twitter_obj
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
	}

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getTwitterObj
	 *
	 * Returns the data member $twitter_obj.
	 */
	private function getTwitterObj()
	{
		return $this->twitter_obj;
	}

	/*** End accessor methods ***/



	/*** magic methods ***/

	/**
	 * __contruct
	 *
	 * Loads the Twitter PHP library and instantiates it.
	 */
	public function __construct()
	{
		# Get the Twitter API Class.
		//require_once Utility::locateFile(MODULES.'Vendor'.DS.'Twitter'.DS.'autoload.php');
		# Check if there is a Twitter object.
		if(empty($this->twitter_obj) OR !is_object($this->twitter_obj))
		{
			# Instantiate a new Twitter object.
			$twitter_obj=new TwitterOAuth(
				TWITTER_CONSUMER_KEY,
				TWITTER_CONSUMER_SECRET,
				TWITTER_TOKEN,
				TWITTER_TOKEN_SECRET
			);
			$this->setTwitterObj($twitter_obj);
		}

		return $this->getTwitterObj();
	}

	/*** End magic methods ***/



	/*** public methods ***/

	/**
	 * getFeed
	 *
	 * Gets Twitter feeds.
	 *
	 * @param int $limit
	 * @return
	 * @throws Exception
	 */
	public function getFeed($limit=20)
	{
		try
		{
			# Set the params and get the Twitter data.
			$params=array('screen_name'=>TWITTER_USERNAME, 'include_rts'=>TRUE);
			# A limit was set.
			if(!empty($limit))
			{
				# Create an array to hold the limit count.
				$count=array('count'=>$limit);
				# Merge the limit count array into the $params array.
				$params=array_merge($count, $params);
			}
			$response=$this->getTwitterObj()->get('statuses/user_timeline', $params);

			return $response;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * getMaxShortURLLength
	 *
	 * Returns the maximum length of t.co URLs.
	 */
	public function getMaxShortURLLength()
	{
		# Send an API request to verify credentials.
		$credentials=$this->getTwitterObj()->get('account/verify_credentials');
		# Send the data to Twitter.
		$config=$this->getTwitterObj()->get('help/configuration');

		# Return the longest length between the two.
		return max($config->short_url_length_https, $config->short_url_length);
	}

	/**
	 * post
	 *
	 * Posts to Twitter feed.
	 *
	 * @param	string $msg
	 */
	public function post($msg)
	{
		# Set the params and get the Twitter data.
		$params=array('include_entities'=>TRUE, 'status'=>$msg);

		return $this->sendTweet('statuses/update', $params);
	}

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * sendTweet
	 *
	 * Sends a Tweet to Twitter's server.
	 *
	 * @param	string $method
	 * @param	array $params
	 */
	private function sendTweet($method, $params)
	{
		# Send an API request to verify credentials.
		$credentials=$this->getTwitterObj()->get('account/verify_credentials');
		# Send the data to Twitter.
		$tweet=$this->getTwitterObj()->post($method, $params);

		return $tweet;
	}

	/*** End private methods ***/

} #=== End Twitter class.