<?php /* framework/application/modules/API/FacebookAPI.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH'))
{
	exit('No direct script access allowed');
}

/**
 * FacebookAPI
 *
 * The FacebookAPI accesses Facebook data info.
 *
 */
class FacebookAPI
{
	/*** data members ***/

	private $facebook_obj;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * Sets the data member $facebook_obj.
	 *
	 * @param    object $facebook_obj
	 */
	public function setFacebookObj($facebook_obj)
	{
		# Check if the passed value is empty.
		if(!empty($facebook_obj))
		{
			# Set the data member.
			$this->facebook_obj=$facebook_obj;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->facebook_obj=NULL;
		}
	}

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * Returns the data member $facebook_obj.
	 */
	private function getFacebookObj()
	{
		return $this->facebook_obj;
	}

	/*** End accessor methods ***/



	/*** magic methods ***/

	/**
	 * Loads the Facebook PHP library and instantiates it.
	 */
	public function __construct()
	{
		# Get the Facebook API Class.
		require_once Utility::locateFile(MODULES.'Vendor'.DS.'Facebook'.DS.'src'.DS.'Facebook'.DS.'autoload.php');
		# Check if there is a Facebook object.
		if(empty($this->facebook_obj) OR !is_object($this->facebook_obj))
		{
			# Instantiate a new Facebook object.
			$facebook_obj=new Facebook\Facebook([
				'app_id'=>FB_APP_ID,
				'app_secret'=>FB_APP_SECRET,
				'default_graph_version'=>'v2.5',
				'default_access_token'=>FB_PAGE_ACCESS_TOKEN
			]);
			$this->setFacebookObj($facebook_obj);
		}

		return $this->getFacebookObj();
	}

	/*** End magic methods ***/



	/*** public methods ***/

	/**
	 * Gets Facebook feeds.
	 *
	 * @param int $limit
	 * @return array|mixed|object
	 * @throws Exception
	 */
	public function getFeed($limit=20)
	{
		try
		{
			$response=$this->getFacebookObj()->get('/me/posts?fields=caption,created_time,id,link,name,message,picture,status_type,story'.(!empty($limit) ? '&limit='.$limit : ''));
			$json_response=$response->getGraphEdge();

			# Return the JSON Decoded response (returns an array).
			return json_decode($json_response);
		}
		catch(Facebook\Exceptions\FacebookResponseException $e)
		{
			throw new Exception('Graph returned an error: '.$e->getMessage(), E_RECOVERABLE_ERROR);
		}
		catch(Facebook\Exceptions\FacebookSDKException $e)
		{
			throw new Exception('Facebook SDK returned an error: '.$e->getMessage(), E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Wrapper function for the Facebook API.
	 * Posts content to Facebook.
	 *
	 * @param    array $data                          Array of data to post on Facebook.
	 *                                                Example:
	 *                                                $data=array(
	 *                                                'link'=>'http://www.example.com',
	 *                                                'message'=>'User provided message',
	 *                                                );
	 * @throws Exception
	 */
	public function post($data)
	{
		try
		{
			$this->getFacebookObj()->post('/me/feed', $data);
		}
		catch(Facebook\Exceptions\FacebookResponseException $e)
		{
			throw new Exception('Graph returned an error: '.$e->getMessage(), E_RECOVERABLE_ERROR);
		}
		catch(Facebook\Exceptions\FacebookSDKException $e)
		{
			throw new Exception('Facebook SDK returned an error: '.$e->getMessage(), E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End public methods ***/
}
