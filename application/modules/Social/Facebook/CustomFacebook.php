<?php if(!defined('BASE_PATH')) exit('No direct script access allowed');

/***
 * CustomFacebook
 *
 * The CustomFacebook accesses Facebook data info.
 *
 */
class CustomFacebook
{
	/*** data members ***/

	private $data=NULL;
	private $fb_app_id=NULL;
	private $fb_app_secret=NULL;
	private $fb_id=NULL;
	private $fb_posts=array();
	private $fb_session=NULL;
	private $fb_token=NULL;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/*
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

	/*
	 * setFB_AppID
	 *
	 * Sets data to the the data member $fb_app_id.
	 *
	 * @param		$app_id
	 * @access	public
	 */
	public function setFB_AppID($app_id=NULL)
	{
		if(!empty($app_id))
		{
			$this->fb_app_id=$app_id;
		}
		else
		{
			$this->fb_app_id=NULL;
		}
	} #==== End -- setFB_AppID

	/*
	 * setFB_AppSecret
	 *
	 * Sets data to the the data member $fb_app_secret.
	 *
	 * @param		$secret
	 * @access	public
	 */
	public function setFB_AppSecret($secret=NULL)
	{
		if(!empty($secret))
		{
			$this->fb_app_secret=$secret;
		}
		else
		{
			$this->fb_app_secret=NULL;
		}
	} #==== End -- setFB_AppSecret

	/*
	 * setFB_ID
	 *
	 * Sets the data member $fb_id.
	 *
	 * @param		$fb_id
	 * @access	public
	 */
	public function setFB_ID($fb_id)
	{
		$this->fb_id=$fb_id;
	} #==== End -- setFB_ID

	/*
	 * setFB_Post
	 *
	 * Sets data to the the data member array $fb_posts. The aspect is used as the index (ie message, time, image, user).
	 *
	 * @param		$post
	 * @access	public
	 */
	public function setFB_Post($post, $index, $aspect=NULL)
	{
		if($aspect!==NULL)
		{
			$this->fb_posts[$index][$aspect]=$post;
		}
		else
		{
			$this->fb_posts[$index]=$post;
		}
	} #==== End -- setFB_Post

	/*
	 * setFB_Session
	 *
	 * Sets data to the the data member $fb_session.
	 *
	 * @param		$fb_session
	 * @access	public
	 */
	public function setFB_Session($session=NULL)
	{
		if(!empty($session))
		{
			$this->fb_session=$session;
		}
		else
		{
			$this->fb_session=NULL;
		}
	} #==== End -- setFB_Session

	/*
	 * setFB_Token
	 *
	 * Sets data to the the data member $fb_token.
	 *
	 * @param		$token
	 * @access	public
	 */
	public function setFB_Token($token=NULL)
	{
		if(!empty($token))
		{
			$this->fb_token=$token;
		}
		else
		{
			$this->fb_token=NULL;
		}
	} #==== End -- setFB_Token

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/*
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

	/*
	 * getFB_AppID
	 *
	 * Returns the data member $fb_app_id.
	 *
	 * @access	public
	 */
	public function getFB_AppID()
	{
		# Check if the id was set to the data member.
		if(empty($this->fb_app_id))
		{
			# Check if there is a defined Facebook id constant.
			if(defined('FB_APP_ID'))
			{
				# Set the Facebook id to a variable.
				$this->setFB_AppID(FB_APP_ID);
			}
			else
			{
				# Throw an error.
				throw new Exception('There was no Facebook application id set.');
			}
		}
		return $this->fb_app_id;
	} #==== End -- getFB_AppID

	/*
	 * getFB_AppSecret
	 *
	 * Returns the data member $fb_app_secret.
	 *
	 * @access	public
	 */
	public function getFB_AppSecret()
	{
		# Check if the id was set to the data member.
		if(empty($this->fb_app_secret))
		{
			# Check if there is a defined Facebook application secret constant.
			if(defined('FB_APP_SECRET'))
			{
				# Set the Facebook application secret to a variable.
				$this->setFB_AppSecret(FB_APP_SECRET);
			}
			else
			{
				# Throw an error.
				throw new Exception('There was no Facebook application secret set.');
			}
		}
		return $this->fb_app_secret;
	} #==== End -- getFB_AppSecret

	/*
	 * getFB_ID
	 *
	 * Returns the data member $fb_id.
	 *
	 * @access	public
	 */
	public function getFB_ID()
	{
		# Check if the id was set to the data member.
		if(empty($this->fb_id))
		{
			# Check if there is a defined Facebook id constant.
			if(defined('FB_ID'))
			{
				# Set the Facebook id to a variable.
				$this->setFB_ID(FB_ID);
			}
			else
			{
				# Throw an error.
				throw new Exception('There was no Facebook id set.');
			}
		}
		return $this->fb_id;
	} #==== End -- getFB_ID

	/*
	 * getFB_Posts
	 *
	 * Returns the data member $fp_posts.
	 *
	 * @access	public
	 */
	public function getFB_Posts()
	{
		return $this->fb_posts;
	} #==== End -- getFB_Posts

	/*
	 * getFB_Session
	 *
	 * Returns the data member $fb_session.
	 *
	 * @access	public
	 */
	public function getFB_Session()
	{
		# Check if the id was set to the data member.
		if(empty($this->fb_session))
		{
			# Check if there is a defined Facebook session constant.
			if(defined('FB_SESSION'))
			{
				# Set the Facebook session to a variable.
				$this->setFB_Session(FB_SESSION);
			}
			else
			{
				# Throw an error.
				throw new Exception('There was no Facebook session set.');
			}
		}
		return $this->fb_session;
	} #==== End -- getFB_Session

	/*
	 * getFB_Token
	 *
	 * Returns the data member $fb_token.
	 *
	 * @access	public
	 */
	public function getFB_Token()
	{
		# Check if the id was set to the data member.
		if(empty($this->fb_token))
		{
			# Check if there is a defined Facebook token constant.
			if(defined('FB_TOKEN'))
			{
				# Set the Facebook token to a variable.
				$this->setFB_Token(FB_TOKEN);
			}
			else
			{
				# Throw an error.
				throw new Exception('There was no Facebook token set.');
			}
		}
		return $this->fb_token;
	} #==== End -- getFB_Token

	/*** End accessor methods ***/



	/*** public methods ***/

	/*
	 * getFB_Feed
	 *
	 * Gets Facebook feeds.
	 *
	 * @access	public
	 */
	public function getFB_Feed($limit=20, $user_only=FALSE)
	{
		# Get the Facebook id from the data member.
		$fb_id=$this->getFB_ID();
		# Get the Facebook token from the data member.
		$fb_token=$this->getFB_Token();
		# Get the Facebook post data.
		$posts=$this->loadFB($fb_id, $fb_token, $limit);

		# Variable used to count how many we've loaded.
		$count=0;

		# Loop through the posts returned from facebook.
		foreach($posts as $post)
		{
			# Check if only posts posted by the user are to be displayed.
			if($user_only===TRUE)
			{
				# Only show posts that are posted by the page admin.
				if($post->from->id==FB_ID)
				{
					# Set the useable Facebook data to the data member array.
					$this->extractFB_Data($post, $count);
					# Increment counter
					$count++;
					# Check if we've outputted the number set above in $limit.
					if($count >= $limit) { break; }
				}
			}
			else
			{
 				# Set the useable Facebook data to the data member array
 				$this->extractFB_Data($post, $count);
 				# Increment counter
 				$count++;
 				# Check if we've outputted the n    umber set above in $limit.
 				if($count >= $limit) { break; }
			}
			# Set the number of Facebook posts retrieved to the data member array.
			$this->setFB_Post($count, 'post_count');
		}

		return $this->getFB_Posts();
	} #==== End -- getFB_Feed

	/**
	 * getFB_PostImage
	 *
	 * Returns the html for the image if the image is present.
	 *
	 * @access	public
	 * @param		array
	 * @return	string or NULL
	 */
	public static function getFB_PostImage($social_data)
	{
		# Check if there was a picture.
		if(isset($social_data->picture))
		{

			$image_url=trim(urldecode($social_data->picture), '"');
			$url_pos=strpos($image_url, 'url=');
			if($url_pos!==FALSE)
			{
				$image_url=substr($image_url, $url_pos+4);
			}

			return '<a href="'.$image_url.'" title="'.((isset($social_data->link_name)) ? $social_data->link_name : ((isset($social_data->name)) ? $social_data->name : $social_data->link)).'" target="_blank" rel="lightbox"><img src="'.$image_url.'" alt="'.((isset($social_data->name)) ? $social_data->name : $social_data->link).'" /></a>';
		}
		return NULL;
	} #==== End -- getFB_PostImage

	/*
	 * postToFB
	 *
	 * Post to a Facebook account.
	 *
	 * @access	public
	 */
	public function postToFB($post, $link, $description, $image=NULL)
	{
		try
		{
			# Get the Facebook application id from the data member.
			$fb_app_id=$this->getFB_AppID();
			# Get the Facebook application secret from the data member.
			$fb_app_secret=$this->getFB_AppSecret();
			# Get the Facebook id from the data member.
			$fb_id=$this->getFB_ID();
			# Get the Facebook token from the data member.
			$fb_token=$this->getFB_Token();

			# Get the Facebook class.
			require_once MODULES.'Social'.DS.'Facebook'.DS.'Facebook.php';
			# Instantiate a new Facebook object.
			$facebook=new Facebook(array('appId'=>$fb_app_id, 'secret'=>$fb_app_secret, 'fileUpload'=>FALSE));

			# Get an array of the apps available to this Facebook token.
			//$accounts=$facebook->api('/me/applications/developer', array('access_token'=>$fb_token));
			$account=$facebook->api('/me', array('access_token'=>$fb_token));
			# Check if this account can post.
			if($account['can_post']===TRUE)
			{
				$params=array('access_token'=>$fb_token, 'name'=>$post, 'link'=>$link, 'caption'=>$link, 'description'=>$description, 'picture'=>$image);

				$post_id=$facebook->api('/'.$fb_id.'/feed', 'POST', $params);
				return $post_id;
			}
			else
			{
				return FALSE;
			}
		}
  	catch(Exception $e)
  	{
  		throw $e;
  	}
	} #==== End -- postToFB

	/*** End public methods ***/



	/*** private methods ***/

	/*
	 * createURL
	 *
	 * Creates the URL that gets the data from facebook.
	 *
	 * @access	private
	 */
	private function createURL($fb_id, $fb_token, $og_obj='feed', $query=NULL)
	{
	     # Must be https when using an access token.
	     return 'https://graph.facebook.com/'.$fb_id.'/'.$og_obj.'?access_token='.$fb_token.((!empty($query)) ? '&amp;'.$query : '');
	} #==== End -- createURL

	/*
	 * curlToFB
	 *
	 * cUrls to the passed Facebook url and sets the returned data to the data member.
	 *
	 * @access	private
	 */
	private function curlToFB($url)
	{
		# Load and setup cURL.
		$curl=curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		# Don't verify SSL (required for some servers).
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		# Get data from Network and decode JSON.
		$json=json_decode(curl_exec($curl));
		# Close the connection.
		curl_close($curl);
		# Set the data as an object to the data member.
		$this->setData($json->data);
	} #==== End -- curlToFB

	/*
	 * extractFB_Data
	 *
	 * Pulls returned Facebook data out of the passed object and sets it to a usable data member array.
	 *
	 * @access	private
	 */
	private function extractFB_Data($fb_data, $count)
	{
		# Set the $fb_data object to the data member array.
		$this->setFB_Post($fb_data, $count, 'social_data');
		# Get the post date / time and convert to unix time.
		$time=strtotime($fb_data->created_time);
		# Format the date / time into something human readable.
		$fb_time=date("M j, Y g:ia", $time);
		# Set the date / time to a variable.
		$this->setFB_Post($fb_time, $count, 'time');
		# Set the poster's name to a variable.
		$this->setFB_Post($fb_data->from->name, $count, 'poster');
		# Set the message body to a variable.
		$this->setFB_Post(((isset($fb_data->message)) ? $fb_data->message : NULL), $count, 'msg');
		# Set the user's url to a variable.
		$this->setFB_Post('http://Facebook.com/'.$fb_data->from->id.'/', $count, 'user_url');
		# Check if there was a picture posted.
		if(isset($fb_data->picture))
		{
			# Set the picture to a variable.
			$this->setFB_Post($fb_data->picture, $count, 'picture');
		}
		# Check if there was a link posted.
		if(isset($fb_data->link))
		{
			# Set the link to a variable.
			$this->setFB_Post($fb_data->link, $count, 'link');
			# Check if a link name was passed.
			if(isset($fb_data->name))
			{
				# Set the link_name to a variable.
				$this->setFB_Post($fb_data->name, $count, 'link_name');
			}
			# Set the link_caption to a variable.
			$this->setFB_Post(((isset($fb_data->caption)) ? $fb_data->caption : NULL), $count, 'link_caption');
		}
	### FIX THIS ###
		# Set the comments count to a variable.
// 		$comment_count=$fb_data->comments->count;
// 		# Check if there are comments with this post.
// 		if($comment_count > 0)
// 		{
// 			$this->setFB_Post($comment_count, $count, 'comment_count');
// 			# Get the comments
// 			for($i=0; $i < $comment_count; $i++)
// 			{
// 				if(isset($fb_data->comments->data[$i]))
// 				{
// 					# Set the data aspect to a variable.
// 					$comment_data=$fb_data->comments->data[$i];
// 					# Set the commentor's name to a variable.
// 					$this->setFB_Post($comment_data->from->name, $count, 'comment_'.$i.'_name');
// 					# Set the commentor's comment to a variable.
// 					$this->setFB_Post($comment_data->message, $count, 'comment_'.$i.'_msg');
// 					# Get the comment date / time and convert to unix time.
// 					$comment_time=strtotime($comment_data->created_time);
// 					# Format the date / time into something human readable.
// 					$comment_time=date("M j, Y g:ia", $comment_time);
// 					# Set the comment time to a variable.
// 					$this->setFB_Post($comment_time, $count, 'comment_'.$i.'_time');
// 					# Set the commentor's profile image to a variable.
// 					$this->setFB_Post('https://graph.facebook.com/'.$fb_data->comments->data[$i]->from->id.'/picture', $count, 'comment_'.$i.'_img');
// 				}
// 			}
// 		}
		# Set the social network name (Facebook) to the data member array.
		$this->setFB_Post('Facebook', $count, 'network');
	} #==== End -- extractFB_Data

	/*
	 * loadFB
	 *
	 * Retrieves posts from facebook's server.
	 *
	 * @param	$fb_id
	 * @param	$fb_token
	 * @param	$limit
	 * @access	private
	 */
	private function loadFB($fb_id, $fb_token, $limit=20)
	{
		# Create the url that gets the data from Facebook.
		$url=$this->createURL($fb_id, $fb_token, 'feed', ((!empty($limit)) ? 'limit='.$limit : NULL));
		# cUrl to Facebook.
		$this->curlToFB($url);
		return $this->getData();
	} #==== End -- loadFB

	/*** End private methods ***/

} #=== End Facebook class.