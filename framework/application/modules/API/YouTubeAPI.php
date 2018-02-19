<?php /* framework/application/modules/Media/YouTube.php */

namespace Mamook\API;

use Google_Client;
use Google_Service_YouTube;

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * YouTube
 *
 * The YouTube class is used to access and manipulate data from the YouTube API.
 *
 */
class YouTube
{
	/*** data members ***/

	private static $youtube;
	private $google_client=NULL;
	private $search;
	private $youtube_url='http://www.youtube.com/watch?v=';
	private $yt_client_id=NULL;
	private $yt_client_secret=NULL;
	private $yt_redirect_uri=NULL;
	private $yt_dev_key=NULL;
	private $yt_refresh_token=NULL;
	private $youtube_service=NULL;
	private $yt_username=YOUTUBE_USERNAME;
	private $yt_channel_id=YOUTUBE_CHANNELID;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setGoogleClient
	 *
	 * Sets the data member $google_client.
	 *
	 * @param	$google_client
	 * @access	public
	 */
	public function setGoogleClient($google_client)
	{
		# Check if the passed value is empty and an object.
		if(!empty($google_client) && is_object($google_client))
		{
			$this->google_client=$google_client;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->google_client=NULL;
		}
	}

	/**
	 * setYouTubeClientId
	 *
	 * Set the data member $yt_client_id
	 *
	 * @param	string $yt_client_id
	 * @access	public
	 */
	public function setYouTubeClientId($yt_client_id)
	{
		# Check if the passed value is empty.
		if(!empty($yt_client_id))
		{
			# Clean it up.
			$yt_client_id=trim($yt_client_id);

			# Set the data member.
			$this->yt_client_id=$yt_client_id;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->yt_client_id=NULL;
		}
	}

	/**
	 * setYouTubeClientSecret
	 *
	 * Set the data member $yt_client_secret
	 *
	 * @param	string $yt_client_secret
	 * @access	public
	 */
	public function setYouTubeClientSecret($yt_client_secret)
	{
		# Check if the passed value is empty.
		if(!empty($yt_client_secret))
		{
			# Clean it up.
			$yt_client_secret=trim($yt_client_secret);

			# Set the data member.
			$this->yt_client_secret=$yt_client_secret;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->yt_client_secret=NULL;
		}
	}

	/**
	 * setYouTubeRedirectUri
	 *
	 * Set the data member $yt_redirect_uri
	 *
	 * @param	string $yt_redirect_uri
	 * @access	public
	 */
	public function setYouTubeRedirectUri($yt_redirect_uri)
	{
		# Check if the passed value is empty.
		if(!empty($yt_redirect_uri))
		{
			# Clean it up.
			$yt_redirect_uri=trim($yt_redirect_uri);

			# Set the data member.
			$this->yt_redirect_uri=$yt_redirect_uri;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->yt_redirect_uri=NULL;
		}
	}

	/**
	 * setYouTubeDevKey
	 *
	 * Set the data member $yt_dev_key
	 *
	 * @param	string $yt_dev_key
	 * @access	public
	 */
	public function setYouTubeDevKey($yt_dev_key)
	{
		# Check if the passed value is empty.
		if(!empty($yt_dev_key))
		{
			# Clean it up.
			$yt_dev_key=trim($yt_dev_key);

			# Set the data member.
			$this->yt_dev_key=$yt_dev_key;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->yt_dev_key=NULL;
		}
	}

	/**
	 * setYouTubeRefreshToken
	 *
	 * Set the data member $yt_refresh_token
	 *
	 * @param	string $yt_refresh_token
	 * @access	public
	 */
	public function setYouTubeRefreshToken($yt_refresh_token)
	{
		# Check if the passed value is empty.
		if(!empty($yt_refresh_token))
		{
			# Clean it up.
			$yt_refresh_token=trim($yt_refresh_token);

			# Set the data member.
			$this->yt_refresh_token=$yt_refresh_token;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->yt_refresh_token=NULL;
		}
	}

	/**
	 * setYouTubeService
	 *
	 * Sets the data member $youtube_service.
	 *
	 * @param	$youtube_service
	 * @access	public
	 */
	public function setYouTubeService($youtube_service)
	{
		# Check if the passed value is empty and an object.
		if(!empty($youtube_service) && is_object($youtube_service))
		{
			$this->youtube_service=$youtube_service;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->youtube_service=NULL;
		}
	}

	/**
	 * setYouTubeUsername
	 *
	 * Sets the data member $yt_username.
	 *
	 * @param	$username
	 * @access	public
	 */
	public function setYouTubeUsername($username)
	{
		# Check if the passed value is empty.
		if(!empty($username))
		{
			# Clean it up.
			$username=trim($username);
			# Set the data member.
			$this->yt_username=$username;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->yt_username=NULL;
		}
	}

	/**
	 * setYouTubeChannelId
	 *
	 * Sets the data member $yt_channel_id.
	 *
	 * @param	$channel_id
	 * @access	public
	 */
	public function setYouTubeChannelId($channel_id)
	{
		# Check if the passed value is empty.
		if(!empty($channel_id))
		{
			# Clean it up.
			$channel_id=trim($channel_id);
			# Set the data member.
			$this->yt_channel_id=$channel_id;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->yt_channel_id=NULL;
		}
	}

	/**
	 * setSearch
	 *
	 * Set the data member $search
	 *
	 * @param	string $search
	 * @access	private
	 */
	private function setSearch($search)
	{
		$this->search=$search;
	}

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getGoogleClient
	 *
	 * Returns the data member $google_client.
	 *
	 * @access	public
	 */
	public function getGoogleClient()
	{
		return $this->google_client;
	}

	/**
	 * getYouTubeClientId
	 *
	 * Returns the data member $yt_client_id.
	 *
	 * @access	public
	 */
	public function getYouTubeClientId()
	{
		return $this->yt_client_id;
	}

	/**
	 * getYouTubeClientSecret
	 *
	 * Returns the data member $yt_cient_secret.
	 *
	 * @access	public
	 */
	public function getYouTubeClientSecret()
	{
		return $this->yt_client_secret;
	}

	/**
	 * getYouTubeRedirectUri
	 *
	 * Returns the data member $yt_redirect_uri.
	 *
	 * @access	public
	 */
	public function getYouTubeRedirectUri()
	{
		return $this->yt_redirect_uri;
	}

	/**
	 * getYouTubeDevKey
	 *
	 * Returns the data member $yt_dev_key.
	 *
	 * @access	public
	 */
	public function getYouTubeDevKey()
	{
		return $this->yt_dev_key;
	}

	/**
	 * getYouTubeRefreshToken
	 *
	 * Returns the data member $yt_refresh_token.
	 *
	 * @access	public
	 */
	public function getYouTubeRefreshToken()
	{
		return $this->yt_refresh_token;
	}

	/**
	 * getYouTubeService
	 *
	 * Returns the data member $youtube_service.
	 *
	 * @access	public
	 */
	public function getYouTubeService()
	{
		if(empty($this->youtube_service) OR !is_object($this->youtube_service))
		{
			# Explicitly set the value to NULL.
			$this->startYouTubeService();
		}
		return $this->youtube_service;
	}

	/**
	 * getYouTubeUsername
	 *
	 * Returns the data member $yt_username.
	 *
	 * @access	public
	 */
	public function getYouTubeUsername()
	{
		return $this->yt_username;
	}

	/**
	 * getYouTubeChannelId
	 *
	 * Returns the data member $yt_channel_id.
	 *
	 * @access	public
	 */
	public function getYouTubeChannelId()
	{
		return $this->yt_channel_id;
	}

	/**
	 * getSearch
	 *
	 * Gets the data member $search
	 *
	 * @access	protected
	 */
	protected function getSearch()
	{
		return $this->search;
	}

	/**
	 * getYoutubeUrl
	 *
	 * Gets the data member $youtube_url
	 *
	 * @access	public
	 */
	public function getYoutubeUrl()
	{
		return $this->youtube_url;
	}

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * deleteVideo
	 *
	 * Returns details of $video_id
	 *
	 * @param int $video_id    The video's ID on YouTube.
	 * @param array $optParams Optional parameters.
	 * @return bool
	 */
	public function deleteVideo($video_id, $optParams=array())
	{
		# Bring $youtube_service into the scope
		$youtube_service=$this->getYouTubeService();
		# Check if the video still exists on YouTube.
		$video_details=$this->videoDetails($video_id);
		# Video exists.
		if(!empty($video_details))
		{
			# Delete the video.
			$delete_response=$youtube_service->videos->delete($video_id, $optParams);
			# $delete_response gets set if there was an error.
			if(isset($delete_response))
			{
				return FALSE;
			}
		}
		# No errors so return TRUE.
		return TRUE;
	}

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 */
	public static function getInstance()
	{
		if(!self::$youtube)
		{
			self::$youtube=new YouTube();
		}
		return self::$youtube;
	}

	/**
	 * getYouTubeIdFromEmbedCode
	 *
	 * Converts string to UTF-8. If the string is HTML then it strips the HTML and get's the first URL.
	 * Then it get's the value after the last slash (/) in the URL which will be the Video ID (on YouTube).
	 *
	 * @param string $embed_code
	 * @return bool|string
	 */
	public function getYouTubeIdFromEmbedCode($embed_code)
	{
		$embed=FALSE;

		# Check if the passed value is empty.
		if(!empty($embed_code))
		{
			# Clean it up.
			$embed_code=htmlspecialchars(str_replace('"', '', $embed_code), ENT_COMPAT, 'UTF-8');

			# Is $embed_code an HTML tag?
			if($embed_code==strip_tags($embed_code))
			{
				# Extract the first URL from $embed.
				preg_match('/\b(?:(?:https?):\/\/|www\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $embed_code, $matches);
				# Get's the value after the last slash in the URL (which will be the YouTube Video ID).
				$embed=substr(strrchr(rtrim($matches[0], '/'), '/'), 1);
			}
		}

		return $embed;
	}

	/**
	 * insertThumbnail
	 *
	 * Create a request for the API's thumbnails.set method to upload the image and associate it with the appropriate video.
	 *
     * @param string $video_id			The video_id parameter specifies a YouTube video ID for which the
     *											custom video thumbnail is being provided.
     * @param array $optParams			Optional parameters.
	 * @return mixed
	 */
	public function insertThumbnail($video_id, $optParams=array())
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();

		$thumbnail_set_response=$youtube_service->thumbnails->set($video_id, $optParams);

		return $thumbnail_set_response;
	}

	/**
	 * insertVideo
	 *
	 * Returns details of $video_id
	 *
	 * @param string $part				The part parameter serves two purposes in this operation.
	 *											It identifies the properties that the write operation will set
	 *											as well as the properties that the API response will include.
     *										The part names that you can include in the parameter value are snippet,
     *											contentDetails, player, statistics, status, and topicDetails.
     *											However, not all of those parts contain properties that can be set
     *											when setting or updating a video's metadata. For example, the
     *											statistics object encapsulates statistics that YouTube calculates
     *											for a video and does not contain values that you can set or modify.
     *											If the parameter value specifies a part that does not contain
     *											mutable values, that part will still be included in the API response.
     * @param $postBody
     * @param array $optParams			Optional parameters.
	 * @return mixed
	 */
	public function insertVideo($part='status,snippet', $postBody, $optParams=array())
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();

		$insert_response=$youtube_service->videos->insert($part, $postBody, $optParams);

		return $insert_response;
	}

	/**
	 * listVideoCategories
	 *
	 * Returns a list of videos that match the API request parameters. (videos.list)
	 *
	 * @param	string $part				The part parameter specifies a comma-separated list of
	 * 											one or more video resource properties that the API response
	 * 											will include. The part names that you can include in the
	 * 											parameter value are id, snippet, contentDetails, player,
	 * 											statistics, status, and topicDetails.
	 *										If the parameter identifies a property that contains child
	 *											properties, the child properties will be included in the
	 *											response. For example, in a video resource, the snippet
	 *											property contains the channelId, title, description, tags,
	 *											and categoryId properties. As such, if you set part=snippet,
	 *											the API response will contain all of those properties.
	 * @param	array $optParams			Optional parameters.
	 *			string hl					The hl parameter specifies the language that should be used
	 *											for text values in the API response.
	 * 			string id					The id parameter specifies a comma-separated list of video category
	 *											IDs for the resources that you are retrieving.
	 *			string regionCode			The regionCode parameter instructs the API to return the list of
	 *											video categories available in the specified country.
	 *											The parameter value is an ISO 3166-1 alpha-2 country code.
	 * @return mixed
	 */
	public function listVideoCategories($part='snippet', $optParams=array())
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();

		$list_categories_response=$youtube_service->videoCategories->listVideoCategories($part, $optParams);

		return $list_categories_response;
	}

	/**
	 * listVideos
	 *
	 * Returns a list of videos that match the API request parameters. (videos.list)
	 *
	 * @param	string $part				The part parameter specifies a comma-separated list of
	 * 											one or more video resource properties that the API response
	 * 											will include. The part names that you can include in the
	 * 											parameter value are id, snippet, contentDetails, player,
	 * 											statistics, status, and topicDetails.
	 *										If the parameter identifies a property that contains child
	 *											properties, the child properties will be included in the
	 *											response. For example, in a video resource, the snippet
	 *											property contains the channelId, title, description, tags,
	 *											and categoryId properties. As such, if you set part=snippet,
	 *											the API response will contain all of those properties.
	 * @param	array $optParams			Optional parameters.
	 *			@opt_param string id
     *										The id parameter specifies a comma-separated list of the YouTube
     *											video ID(s) for the resource(s) that are being retrieved.
     *											In a video resource, the id property specifies the video's ID.
	 * @return mixed
	 */
	public function listVideos($part='snippet', $optParams=array())
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();

		$list_response=$youtube_service->videos->listVideos($part, $optParams);

		return $list_response;
	}

	/**
	 * PlaylistsListFeed
	 *
	 * Retrieves playlists from the YouTube user's channelId.
	 *
	 * @param string $part				What part of the video to get from YouTube.
	 * @return array
	 */
	public function PlaylistsListFeed($part='snippet', $optParams=array('channelId'=>YOUTUBE_CHANNELID, 'maxResults'=>50))
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();

		$playlists=$youtube_service->playlists->listPlaylists($part, $optParams);

		$new_array=$this->rebuildArray($playlists, NULL, NULL, TRUE);

		return $new_array;
	}

	/**
	 * PlaylistItems
	 *
	 * Retrieves videos from a playlist.
	 *
	 * @param	int $playlist_id			The ID of the playlist.
	 * @param	string $part				The part parameter specifies a comma-separated list of
	 * 											one or more playlistItem resource properties that the API
	 * 											response will include. The part names that you can include
	 * 											in the parameter value are id, snippet, and contentDetails.
	 */
	public function PlaylistItems($playlist_id, $part='snippet', $playlistsItems_optParams=array('maxResults'=>50))
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();

		$optParams=array('playlistId'=>$playlist_id);
		$optParams=array_merge($optParams, $playlistsItems_optParams);
		$playlistsItems=$youtube_service->playlistItems->listPlaylistItems($part, $optParams);

		$new_array=$this->rebuildArray($playlistsItems, TRUE);

		# Instantiate a new Utility object.
		$utility=new Utility();
		# Sort the playlist array by date.
		$items=$utility->sortByDate($new_array, 'publishedAt');

		return $items;
	}

	/**
	 * PlaylistItemsInsert
	 *
	 * Inserts a video into playlists.
	 *
	 * @param string $part				The part parameter specifies a comma-separated list of
	 * 											one or more playlistItem resource properties that the API
	 * 											response will include. The part names that you can include
	 * 											in the parameter value are id, snippet, and contentDetails.
     * @param $postBody
     * @param array $optParams			Optional parameters.
	 * @return mixed
	 */
	public function PlaylistItemsInsert($part='snippet', $postBody, $optParams=array())
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();

		$playlistsItemsInsert_response=$youtube_service->playlistItems->insert($part, $postBody, $optParams);

		return $playlistsItemsInsert_response;
	}

	/**
	 * searchYouTube
	 *
	 * Searches YouTube for $username's videos
	 *
	 * @param	string $channel_id			The user's Channel ID to search YouTube for.
	 * @param	string $type				Optional - Content we want to search YouTube for.
	 * @param	string $part				The part parameter specifies a comma-separated list of
	 * 											one or more search resource properties that the API response
	 * 											will include. The part names that you can include in the
	 * 											parameter value are id and snippet.
	 * @param	string $order				Optional - The order parameter specifies the method that
	 * 											will be used to order resources in the API response.
	 * 											Acceptable values are: date, rating, relevance, viewCount
	 * @return mixed
	 */
	public function searchYouTube($channel_id, $type=NULL, $part='snippet', $order='date', $search_optParams=array('maxResults'=>50))
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();

		# Array request to sent to YouTube.
		$optParams=array('channelId'=>$channel_id, 'order'=>$order);

		# If searching for specific content.
		if($type!==NULL)
		{
			$type_array=array('type'=>$type);
			$optParams=array_merge($search_optParams, $optParams, $type_array);
		}

		$optParams=array_merge($search_optParams, $optParams);

		# Send array to YouTube.
		$search=$youtube_service->search->listSearch($part, $optParams);

		$new_array=$this->rebuildArray($search);

		return $new_array;
	}

	/**
	 * startYouTubeService
	 *
	 * Set the data member $youtube_service
	 *
	 * @access	public
	 */
	public function startYouTubeService()
	{
		# Get the Google API.
		//require_once Utility::locateFile(MODULES.'Vendor'.DS.'Google'.DS.'src'.DS.'Google'.DS.'autoload.php');

		$client=new Google_Client();
		$client->setApplicationName("API Project");
		$client->setClientId($this->getYouTubeClientId());
		$client->setClientSecret($this->getYouTubeClientSecret());
		$client->setScopes('https://www.googleapis.com/auth/youtube');
		$client->setRedirectUri($this->getYouTubeRedirectUri());
		$client->setDeveloperKey($this->getYouTubeDevKey());
		$client->refreshToken($this->getYouTubeRefreshToken());

		$youtube_service=new Google_Service_YouTube($client);

		$this->setGoogleClient($client);
		$this->setYouTubeService($youtube_service);
	}

	/**
	 * updateVideo
	 *
	 * Updates a video's metadata. (videos.update)
	 *
	 * @param string $part			The part parameter specifies a comma-separated list of
	 * 										one or more search resource properties that the API response
	 * 										will include. The part names that you can include in the
	 * 										parameter value are id and snippet.
	 * @param $postBody
	 * @param array $optParams		Optional parameters.
	 * @return mixed
	 */
	public function updateVideo($part, $postBody, $optParams=array())
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();
		$update_video=$youtube_service->videos->update($part, $postBody, $optParams);
		return $update_video;
	}

	/**
	 * videoDetails
	 *
	 * Returns details of $video_id
	 *
	 * @param	int $video_id			The video's ID on YouTube.
	 * @param	string $part			The part parameter specifies a comma-separated list of
	 * 										one or more video resource properties that the API response
	 * 										will include. The part names that you can include in the
	 * 										parameter value are id, snippet, contentDetails, player,
	 * 										statistics, status, and topicDetails.
	 * @return	array
	 */
	public function videoDetails($video_id, $part='snippet', $optParams=array())
	{
		# Bring $youtube_service into the scope.
		$youtube_service=$this->getYouTubeService();
		$optParams=array_merge($optParams, array('id'=>$video_id));
		# Get the video details from YouTube.
		$video=$youtube_service->videos->listVideos($part, $optParams);
		# Rebuild the array to our own format.
		$new_array=$this->rebuildArray($video, FALSE, TRUE);
		# Return the new array.
		return $new_array;
	}

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * rebuildArray
	 *
	 * Changes the array output.
	 *
	 * @param array $array				Original array
	 * @param boolean $is_playlist_items			Is this array PlaylistItems?
	 * @param boolean $is_video_details			Is this array videoDetails()?
	 * @param boolean $is_playlist_feed			Is this array PlaylistsListFeed()?
	 * @return array
	 */
	private function rebuildArray($array, $is_playlist_items=FALSE, $is_video_details=FALSE, $is_playlist_feed=FALSE)
	{
		# Create a new array
		$new_array=array();

		# Loop through the array.
		foreach($array['items'] as $items)
		{
			for($i=0; $i<count($array['items']); $i++)
			{
				if($is_playlist_items!==FALSE || $is_playlist_feed!==FALSE)
				{
					$new_array[$i]['id']=$array['items'][$i]['id'];
				}
				else
				{
					$new_array[$i]['videoId']=$array['items'][$i]['id'];
				}
				$new_array[$i]['publishedAt']=$array['items'][$i]['snippet']['publishedAt'];
				$new_array[$i]['channelId']=$array['items'][$i]['snippet']['channelId'];
				$new_array[$i]['title']=$array['items'][$i]['snippet']['title'];
				//if($is_playlist_items===NULL)
				//{
					$new_array[$i]['description']=$array['items'][$i]['snippet']['description'];
				//}
				$new_array[$i]['thumbnails']=$array['items'][$i]['snippet']['thumbnails'];
				$new_array[$i]['channelTitle']=$array['items'][$i]['snippet']['channelTitle'];
				if($is_video_details!==FALSE)
				{
					$new_array[$i]['categoryId']=$array['items'][$i]['snippet']['categoryId'];
				}
				if($is_playlist_items!==FALSE)
				{
					$new_array[$i]['playlistId']=$array['items'][$i]['snippet']['playlistId'];
					$new_array[$i]['position']=$array['items'][$i]['snippet']['position'];
					$new_array[$i]['videoId']=$array['items'][$i]['snippet']['resourceId']['videoId'];
				}
			}
		}

		return $new_array;
	}

	/*** End protected methods ***/

}