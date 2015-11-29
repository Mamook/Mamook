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
	private function getAPIObj()
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
				$max_short_url_length=$this->getAPIObj()->getMaxShortURL_Length();
				$this->getAPIObj()->postToTwitter(WebUtility::truncate($message, 139-$max_short_url_length, '&hellip;', FALSE, TRUE).' '.$url);
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

} # End API class.