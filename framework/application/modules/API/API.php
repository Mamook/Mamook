<?php /* framework/application/modules/API/API.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * API
 *
 * Interacts with the application.
 *
 */
class API
{
	/*** data members ***/

	private $api_obj;
	private $description=NULL;
	private $image_id=NULL;
	private $loaded_api;
	private $message;
	private $response=NULL;
	private $title=NULL;
	private $url=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAPIObj
	 *
	 * Sets the data member $api_obj.
	 *
	 * @param	obj $api_obj
	 * @access	private
	 */
	private function setAPIObj($api_obj)
	{
		# Check if the passed value is empty.
		if(!empty($api_obj))
		{
			# Set the data member.
			$this->api_obj=$api_obj;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->api_obj=NULL;
		}
	} #==== End -- setAPIObj

	/**
	 * setDescription
	 *
	 * Sets the data member $description.
	 *
	 * @param	string $description
	 * @access	private
	 */
	private function setDescription($description)
	{
		# Check if the passed value is empty.
		if(!empty($description))
		{
			# Strip slashes and decode any html entities.
			$description=html_entity_decode(stripslashes($description), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$description=trim($description);
			# Replace any tokens with their correlating value.
			$description=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $description);
			# Set the data member.
			$this->description=$description;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->description=NULL;
		}
	} #==== End -- setDescription

	/**
	 * setImageID
	 *
	 * Sets the data member $image_id.
	 *
	 * @param	int $image_id
	 * @access	private
	 */
	private function setImageID($image_id)
	{
		# Check if the passed $id is empty.
		if(!empty($image_id))
		{
			# Get the Validator Class.
			require_once Utility::locateFile(MODULES.'Validator'.DS.'Validator.php');
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed $id is an integer.
			if($validator->isInt($image_id)===TRUE)
			{
				# Explicitly make it an integer.
				$image_id=(int)$image_id;
			}
			elseif($image_id!='add' && $image_id!='select' && $image_id!='remove')
			{
				throw new Exception('The passed image id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$image_id=NULL;
		}
		# Set the data member.
		$this->image_id=$image_id;
	} #==== End -- setImageID

	/**
	 * setLoadedAPI
	 *
	 * Sets the data member $loaded_api.
	 *
	 * @param	string $loaded_api
	 * @access	private
	 */
	private function setLoadedAPI($loaded_api)
	{
		# Check if the passed value is empty.
		if(!empty($loaded_api))
		{
			# Set the data member. Make it lowercase.
			$this->loaded_api=strtolower($loaded_api);
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->loaded_api=NULL;
		}
	} #==== End -- setLoadedAPI

	/**
	 * setMessage
	 *
	 * Sets the data member $message.
	 *
	 * @param	string $message
	 * @access	private
	 */
	private function setMessage($message)
	{
		# Check if the passed value is empty.
		if(!empty($message))
		{
			# Strip slashes and decode any html entities.
			$message=html_entity_decode(stripslashes($message), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$message=trim($message);
			# Replace any tokens with their correlating value.
			$message=str_ireplace(array('%{domain_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, FW_POPUP_HANDLE), $message);
			# Set the data member.
			$this->message=$message;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->message=NULL;
		}
	} #==== End -- setMessage

	/**
	 * setResponse
	 *
	 * Sets the data member $response.
	 *
	 * @param	$response
	 * @access	private
	 */
	private function setResponse($response)
	{
		# Check if the passed value is empty.
		if(!empty($response))
		{
			# Set the data member.
			$this->response=$response;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->response=NULL;
		}
	} #==== End -- setResponse

	/**
	 * setTitle
	 *
	 * Sets the data member $title.
	 *
	 * @param	string $title
	 * @access	private
	 */
	private function setTitle($title)
	{
		# Check if the passed value is empty.
		if(!empty($title))
		{
			# Strip slashes, decode any html entities, and strip tags.
			$title=strip_tags(html_entity_decode(stripslashes($title), ENT_COMPAT, 'UTF-8'));
			# Re-encde any special characters to html entities in UTF-8 encoding including quotes.
			$title=htmlentities($title, ENT_QUOTES, 'UTF-8');
			# Clean it up.
			$title=trim($title);
			# Set the data member.
			$this->title=$title;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->title=NULL;
		}
	} #==== End -- setTitle

	/**
	 * setURL
	 *
	 * Sets the data member $url.
	 *
	 * @param	string $url
	 * @access	private
	 */
	private function setURL($url)
	{
		# Clean it up.
		$url=trim($url);
		# Check if the passed value is empty or only the sheme name was passed.
		if(empty($url) || ($url=='http://') || ($url=='https://'))
		{
			# Explicitly set the value to NULL.
			$url=NULL;
		}
		else
		{
			# Replace any domain token with the current domain name.
			$url=str_ireplace('%{domain_name}', DOMAIN_NAME, $url);
		}
		# Set the data member.
		$this->url=$url;
	} #==== End -- setURL

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAPIObj
	 *
	 * Returns the data member $api_obj.
	 *
	 * @access	private
	 */
	public function getAPIObj()
	{
		return $this->api_obj;
	} #==== End -- getAPIObj

	/**
	 * getDescription
	 *
	 * Returns the data member $description.
	 *
	 * @access	private
	 */
	private function getDescription()
	{
		return $this->description;
	} #==== End -- getDescription

	/**
	 * getImageID
	 *
	 * Returns the data member $image_id.
	 *
	 * @access	private
	 */
	private function getImageID()
	{
		return $this->image_id;
	} #==== End -- getImageID

	/**
	 * getLoadedAPI
	 *
	 * Returns the data member $loaded_api.
	 *
	 * @access	private
	 */
	private function getLoadedAPI()
	{
		return $this->loaded_api;
	} #==== End -- getLoadedAPI

	/**
	 * getMessage
	 *
	 * Returns the data member $message.
	 *
	 * @access	private
	 */
	private function getMessage()
	{
		return $this->message;
	} #==== End -- getMessage

	/**
	 * getResponse
	 *
	 * Returns the data member $response.
	 *
	 * @access	private
	 */
	private function getResponse()
	{
		return $this->response;
	} #==== End -- getResponse

	/**
	 * getTitle
	 *
	 * Returns the data member $title.
	 *
	 * @access	private
	 */
	private function getTitle()
	{
		return $this->title;
	} #==== End -- getTitle

	/**
	 * getURL
	 *
	 * Returns the data member $url.
	 *
	 * @access	private
	 */
	private function getURL()
	{
		return $this->url;
	} #==== End -- getURL

	/*** End accessor methods ***/



	/*** magic methods ***/

	/**
	 * __construct
	 *
	 * Description of the constructor.
	 *
	 * @param	$api_to_load			The API to use.
	 * @access	public
	 */
	public function __construct($api_to_load='framework')
	{
		try
		{
			# Set the loaded API to a data member (to use in the post() function).
			$this->setLoadedAPI($api_to_load);
			# The FW API is loaded.
			if($this->getLoadedAPI()=='framework')
			{
				# NOTE! Must be a better way to do this?
				$api_obj=$this;
			}
			# The AddThis API is loaded.
			elseif($this->getLoadedAPI()=='addthis')
			{
				# Get the AddThisAPI Class.
				require_once Utility::locateFile(MODULES.'API'.DS.'AddThisAPI.php');
				# Instantiate a new AddThisAPI object.
				$api_obj=new AddThisAPI();
			}
			# The Facebook API is loaded.
			elseif($this->getLoadedAPI()=='facebook')
			{
				# Get the FacebookAPI Class.
				require_once Utility::locateFile(MODULES.'API'.DS.'FacebookAPI.php');
				# Instantiate a new FacebookAPI object.
				$api_obj=new FacebookAPI();
			}
			# The Twitter API is loaded.
			elseif($this->getLoadedAPI()=='twitter')
			{
				# Get the TwitterAPI class.
				require_once Utility::locateFile(MODULES.'API'.DS.'TwitterAPI.php');
				# Instantiate a new TwitterAPI object.
				$api_obj=new TwitterAPI();
			}
			# The YouTube API is loaded.
			elseif($this->getLoadedAPI()=='youtube')
			{
			}
			# Everything else, returns this class.
			else
			{
				# NOTE! Must be a better way to do this?
				$api_obj=$this;
			}
			# Set the api to the data member.
			$this->setAPIObj($api_obj);
			# Return the API class object.
			return $this->getAPIObj();
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** public methods ***/

	/**
	 * displaySocial
	 *
	 * Displays the default social share buttons.
	 * NOTE: This method exists for backward compatibility. To display custom buttons,
	 * use the AddThisAPI class via this API class as indicated in the constructor.
	 *
	 * @access	public
	 */
	public function displaySocial()
	{
		# Set the AddThisAPI instance to a local variable.
		$addthis_api_obj=$this->getAPIObj();
		# Return the default share button markup.
		return $addthis_api_obj->getDisplayableShareButtonMarkup();
	} #==== End -- displaySocial

	/**
	 * getFeed
	 *
	 * Description.
	 *
	 * @param	int $num_posts			Optional.
	 * @access	public
	 */
	public function getFeed($num_posts=20)
	{
		try
		{
			# Send the array to the appropriate class.
			$response=$this->getAPIObj()->getFeed($num_posts);
			$extracted_data=$this->extractData($response);
			$this->setResponse($extracted_data);
			return $this->getResponse();
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getFeed


	/**
	 * getWebPage
	 *
	 * Wrapper function for the API service that is loaded.
	 * Posts content to the API service.
	 *
	 * @param	string $url
	 * @access	public
	 */
	public function getWebPage($url)
	{
		$options = array(
			CURLOPT_RETURNTRANSFER => TRUE,     	# return web page
			CURLOPT_HEADER         => FALSE,    	# don't return headers
			CURLOPT_FOLLOWLOCATION => TRUE,     	# follow redirects
			CURLOPT_ENCODING       => "",       	# handle compressed
			CURLOPT_USERAGENT      => "spider", 	# who am i
			CURLOPT_AUTOREFERER    => TRUE,     	# set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      	# timeout on connect
			CURLOPT_TIMEOUT        => 120,      	# timeout on response
			CURLOPT_MAXREDIRS      => 10,       	# stop after 10 redirects
		);

		$ch      = curl_init($url);
		curl_setopt_array($ch, $options);
		$content = curl_exec($ch);
		$err     = curl_errno($ch);
		$errmsg  = curl_error($ch);
		$header  = curl_getinfo($ch);
		curl_close($ch);

		$header['errno'] = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
	} #==== End -- getWebPage


	/**
	 * fileGetContentsCurl
	 *
	 * Get a file on the web.  The file may be an (X)HTML page, an image, etc.
	 * Return an associative array containing the page header, contents,
	 * and HTTP status code.
	 *
	 * Values in the returned array are as defined by the CURL curl_getinfo()
	 * function, and include:
	 *
	 * 	"url"		the last effective URL after redirects
	 * 	"http_code"	the last error/status code
	 * 	"content_type"	the content type from the header
	 *
	 * This function adds a few more:
	 *
	 * 	"content"	the page content (text, image, etc.)
	 * 	"errno"		the CURL error code
	 * 	"errmsg"	the CURL error message
	 *
	 * On success, "errno" is 0, "http_code" is 200, and "content" has the
	 * web page.
	 *
	 * On an error with the URL, such as a redirect limit, or timeout,
	 * "errno" will be non-zero and "errmsg" will contain an error message.
	 * There other fields will be missing.
	 *
	 * On an error with the web site, such as a missing page, no permissions,
	 * or no service, "errno" will be 0, "http_code" will be the HTTP error
	 * code, and "content" will be missing.
	 *
	 * Parameters:
	 * 	url		the URL of the page to get
	 *
	 * Return values:
	 * 	An associative array containing the page text and error codes,
	 * 	as described above.
	 *
	 * See also:
	 *	http://nadeausoftware.com/articles/2007/06/php_tip_how_get_web_page_using_curl
	 *
	 * @param	string $url
	 * @access	public
	 *
	 * Copyright (c) 2008, David R. Nadeau, NadeauSoftware.com.
	 * All rights reserved.
	 *
	 * Redistribution and use in source and binary forms, with or without
	 * modification, are permitted provided that the following conditions
	 * are met:
	 *
	 *	* Redistributions of source code must retain the above copyright
	 *	  notice, this list of conditions and the following disclaimer.
	 *
	 *	* Redistributions in binary form must reproduce the above
	 *	  copyright notice, this list of conditions and the following
	 *	  disclaimer in the documentation and/or other materials provided
	 *	  with the distribution.
	 *
	 *	* Neither the names of David R. Nadeau or NadeauSoftware.com, nor
	 *	  the names of its contributors may be used to endorse or promote
	 *	  products derived from this software without specific prior
	 *	  written permission.
	 *
	 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
	 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
	 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
	 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
	 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
	 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
	 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
	 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY
	 * WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
	 * OF SUCH DAMAGE.
	 *
	 * This is a BSD License approved by the Open Source Initiative (OSI).
	 * See:  http://www.opensource.org/licenses/bsd-license.php
	 */
	public function fileGetContentsCurl($url)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		# Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	} #==== End -- fileGetContentsCurl


	/**
	 * post
	 *
	 * Wrapper function for the API service that is loaded.
	 * Posts content to the API service.
	 *
	 * @param	string $message
	 * @param	string $url				Optional.
	 * @param	string $title			Optional.
	 * @param	string $description		Optional.
	 * @param	string $image_id		Optional.
	 * @access	public
	 */
	public function post($message, $url=NULL, $title=NULL, $description=NULL, $image_id=NULL)
	{
		try
		{
			# Set the description to the data member.
			$this->setDescription($description);
			# Set the image to the data member.
			$this->setImageID($image_id);
			# Set the message to the data member.
			$this->setMessage($message);
			# Set the title to the data member.
			$this->setTitle($title);
			# Set the url to the data member.
			$this->setURL($url);
			# Set the post's link description to a variable.
			$description=$this->getDescription();
			# Set the post's associated image id to a variable.
			$image_id=$this->getImageID();
			# Set the post's message to a variable.
			$message=$this->getMessage();
			# Set the post's link title to a variable.
			$title=$this->getTitle();
			# Set the posts URL to a variable.
			$url=$this->getURL();
			# Check if there was an image associated with the post.
			if($image_id!==NULL)
			{
				# get the Image class.
				require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
				# Instantiate a new Image object.
				$image_obj=new Image();
				# Get the image info.
				$image_obj->getThisImage($image_id);
				$image_name=$image_obj->getImage();
			}
			else
			{
				$image_name='SiteShot.jpg';
			}
			# The Framework API is loaded.
			if($this->getLoadedAPI()=='framework')
			{
			}
			# The Facebook API is loaded.
			elseif($this->getLoadedAPI()=='facebook')
			{
				# Set the content to an array.
				$data=array(
					'caption'=>$url,
					'description'=>$description,
					'link'=>$url,
					'message'=>$message,
					'name'=>WebUtility::truncate($title, 420, '&hellip;', FALSE, TRUE),
					'picture'=>IMAGES.$image_name
				);
				# Send the array to the FacebookAPI class.
				$this->getAPIObj()->post($data);
			}
			# The Twitter API is loaded.
			elseif($this->getLoadedAPI()=='twitter')
			{
				$max_short_url_length=$this->getAPIObj()->getMaxShortURLLength();
				$this->getAPIObj()->post(WebUtility::truncate($message, 139-$max_short_url_length, '&hellip;', FALSE, TRUE).' '.$url);
			}
			# The YouTube API is loaded.
			elseif($this->getLoadedAPI()=='youtube')
			{
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- post


	/**
	 * sortByDate
	 *
	 * Returns the array of social data sorted by date.
	 * Wrapper function for Utility->sortByDate() method.
	 *
	 * @param	array $social_data		An array of values to sort
	 * @access	public
	 * @return	array
	 */
	public function sortByDate($social_data, $key='time')
	{
		# Instantiate a new Utility object.
		$utility_obj=new Utility();
		# Sort the playlist array by date.
		$social_data=$utility_obj->sortByDate($social_data, $key);
		return $social_data;
	} #==== End -- sortByDate

	/**
	 * validateServerAPIKey
	 *
	 * Validates the clients API key against the server's API key.
	 *
	 * @param	$client_api_key			Client's API key
	 * @access	public
	 */
    public function validateServerAPIKey($client_api_key)
    {
        # If the server's API key does not match the clients API key.
        if(FW_API_KEY!=$client_api_key)
        {
        	# Create an error array to return to the client.
            $error_array=array('error'=>1, 'message'=>'Invalid Key');
            # Return the error in JSON encoded format.
            echo json_encode($error_array);
            # Exit the application.
            exit;
        }
    } #==== End -- validateServerAPIKey

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * extractData
	 *
	 * Pulls returned data out of the passed object and sets it to a usable data member array.
	 *
	 * @param	$raw_data
	 * @access	private
	 * @return	array
	 */
	private function extractData($raw_data)
	{
		$extracted_data=array();
		$i=0;
		# Loop through the response data.
		foreach($raw_data as $data)
		{
			# The Facebook API is loaded.
			if($this->getLoadedAPI()=='facebook')
			{
				# Set the id to a variable.
				$extracted_data[$i]['id']=$data->id;
				# Check if there was a link posted.
				if(isset($data->link))
				{
					# Set the link to a variable.
					$extracted_data[$i]['link']=$data->link;
					# Check if a link name was passed.
					if(isset($data->name))
					{
						# Set the link_name to a variable.
						$extracted_data[$i]['link_name']=$data->name;
					}
					# Set the link_caption to a variable.
					$extracted_data[$i]['link_caption']=(isset($data->caption) ? $data->caption : NULL);
				}
				# Set the message body to a variable.
				$extracted_data[$i]['message']=(isset($data->message) ? $data->message : NULL);
				# Set the social network name (Facebook) to the data member array.
				$extracted_data[$i]['network']='Facebook';
				# Check if there was a picture posted.
				if(isset($data->picture))
				{
					# Set the picture to a variable.
					$extracted_data[$i]['picture']=$data->picture;
				}
				# Set the status_type to a variable.
				$extracted_data[$i]['status_type']=(isset($data->status_type) ? $data->status_type : NULL);
				# Set the story to a variable.
				$extracted_data[$i]['story']=(isset($data->story) ? $data->story : NULL);
				# Get the post date / time and convert to unix time.
				$time=strtotime($data->created_time->date);
				# Format the date / time into something human readable.
				$fb_time=date("M j, Y g:ia", $time);
				# Set the date / time to a variable.
				$extracted_data[$i]['time']=$fb_time;
			}
			# The Twitter API is loaded.
			elseif($this->getLoadedAPI()=='twitter')
			{
				# Set the id to a variable.
				$extracted_data[$i]['id']=$data->id_str;
				# Set the message body to a variable.
				$extracted_data[$i]['message']=$data->text;
				# Set the social network name (Twitter) to the data member array.
				$extracted_data[$i]['network']='Twitter';
				# Get the post date / time and convert to unix time.
				$time=strtotime($data->created_at);
				# Format the date / time into something human readable.
				$twitter_time=date("M j, Y g:ia", $time);
				# Set the date / time to a variable.
				$extracted_data[$i]['time']=$twitter_time;
			}
			$i++;
		}
		return $extracted_data;
	} #==== End -- extractData

    /*** End private methods ***/

} # End API class.