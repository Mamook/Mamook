<?php

# Get the Soundcloud API class.
require_once Utility::locateFile(MODULES.'Vendor'.DS.'Soundcloud'.DS.'Services'.DS.'Soundcloud.php');

/**
 * Soundcloud
 *
 * The Soundcloud class is used to access and manipulate data from the Soundcloud API.
 *
 */
class Soundcloud extends Services_Soundcloud
{
	/*** data members ***/

	private static $soundcloud;
	private $search;
	private $soundcloud_url='http://www.soundcloud.com/watch?v=';
	private $sc_client_id=NULL;
	private $sc_client_secret=NULL;
	private $sc_redirect_uri=NULL;
	private $sc_dev_key=NULL;
	private $soundcloud_service=NULL;
	private $sc_username=SOUNDCLOUD_USERNAME;
	//private $sc_channel_id=SOUNDCLOUD_CHANNELID;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setSoundcloudClientId
	 *
	 * Set the data member $sc_client_id
	 *
	 * @param	string $sc_client_id
	 * @access	public
	 */
	public function setSoundcloudClientId($sc_client_id)
	{
		# Check if the passed value is empty.
		if(!empty($sc_client_id))
		{
			# Clean it up.
			$sc_client_id=trim($sc_client_id);

			# Set the data member.
			$this->sc_client_id=$sc_client_id;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sc_client_id=NULL;
		}
	} #==== End -- setSoundcloudClientId

	/**
	 * setSoundcloudClientSecret
	 *
	 * Set the data member $sc_client_secret
	 *
	 * @param	string $sc_client_secret
	 * @access	public
	 */
	public function setSoundcloudClientSecret($sc_client_secret)
	{
		# Check if the passed value is empty.
		if(!empty($sc_client_secret))
		{
			# Clean it up.
			$sc_client_secret=trim($sc_client_secret);

			# Set the data member.
			$this->sc_client_secret=$sc_client_secret;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sc_client_secret=NULL;
		}
	} #==== End -- setSoundcloudClientSecret

	/**
	 * setSoundcloudRedirectUri
	 *
	 * Set the data member $sc_redirect_uri
	 *
	 * @param	string $sc_redirect_uri
	 * @access	public
	 */
	public function setSoundcloudRedirectUri($sc_redirect_uri)
	{
		# Check if the passed value is empty.
		if(!empty($sc_redirect_uri))
		{
			# Clean it up.
			$sc_redirect_uri=trim($sc_redirect_uri);

			# Set the data member.
			$this->sc_redirect_uri=$sc_redirect_uri;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sc_redirect_uri=NULL;
		}
	} #==== End -- setSoundcloudRedirectUri

	/**
	 * setSoundcloudUsername
	 *
	 * Sets the data member $sc_username.
	 *
	 * @param	$username
	 * @access	public
	 */
	public function setSoundcloudUsername($username)
	{
		# Check if the passed value is empty.
		if(!empty($username))
		{
			# Clean it up.
			$username=trim($username);
			# Set the data member.
			$this->sc_username=$username;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->sc_username=NULL;
		}
	} #==== End -- setSoundcloudUsername

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
	} #==== End -- setSearch

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getSoundcloudClientId
	 *
	 * Returns the data member $sc_client_id.
	 *
	 * @access	public
	 */
	public function getSoundcloudClientId()
	{
		return $this->sc_client_id;
	} #==== End -- getSoundcloudClientId

	/**
	 * getSoundcloudClientSecret
	 *
	 * Returns the data member $sc_cient_secret.
	 *
	 * @access	public
	 */
	public function getSoundcloudClientSecret()
	{
		return $this->sc_client_secret;
	} #==== End -- getSoundcloudClientSecret

	/**
	 * getSoundcloudRedirectUri
	 *
	 * Returns the data member $sc_redirect_uri.
	 *
	 * @access	public
	 */
	public function getSoundcloudRedirectUri()
	{
		return $this->sc_redirect_uri;
	} #==== End -- getSoundcloudRedirectUri

	/**
	 * getSoundcloudUsername
	 *
	 * Returns the data member $sc_username.
	 *
	 * @access	public
	 */
	public function getSoundcloudUsername()
	{
		return $this->sc_username;
	} #==== End -- getSoundcloudUsername

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
	} #==== End -- getSearch

	/**
	 * getSoundcloudUrl
	 *
	 * Gets the data member $soundcloud_url
	 *
	 * @access	public
	 */
	public function getSoundcloudUrl()
	{
		return $this->soundcloud_url;
	} #==== End -- getSoundcloudUrl

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$soundcloud)
		{
			self::$soundcloud=new Soundcloud();
		}
		return self::$soundcloud;
	} #==== End -- getInstance

	/**
	 * searchSoundcloud
	 *
	 * Searches Soundcloud for $username's audios
	 *
	 * @param	string $channel_id			The user's Channel ID to search Soundcloud for.
	 * @param	string $type				Optional - Content we want to search Soundcloud for.
	 * @param	string $part				The part parameter specifies a comma-separated list of
	 * 											one or more search resource properties that the API response
	 * 											will include. The part names that you can include in the
	 * 											parameter value are id and snippet.
	 * @param	string $order				Optional - The order parameter specifies the method that
	 * 											will be used to order resources in the API response.
	 * 											Acceptable values are: date, rating, relevance, viewCount
	 * @access	public
	 */
	public function searchSoundcloud($channel_id, $type=NULL, $part='snippet', $order='date')
	{
		# Bring $soundcloud_service into the scope
		//$soundcloud_service=$this->startSoundcloudService();
		global $soundcloud_service;

		# Array request to sent to Soundcloud
		$search_optParams = array('channelId' => $channel_id, 'maxResults' => 50, 'order' => $order);

		# If searching for specific content
		if($type!==NULL)
		{
			$type_array = array('type' => $type);
			$search_optParams = array_merge($search_optParams, $type_array);
		}

		# Send array to Soundcloud
		$search = $soundcloud_service->search->listSearch($part, $search_optParams);

		return $search;
	} #==== End -- searchSoundcloud

	/**
	 * PlaylistsListFeed
	 *
	 * Retrieves playlists from the Soundcloud user's channelId.
	 *
	 * @param	string $part			What part of the audio to get from Soundcloud.
	 * @return	array
	 */
	public function PlaylistsListFeed($part='snippet')
	{
		# Bring $soundcloud_service into the scope
		//$soundcloud_service=$this->startSoundcloudService();
		global $soundcloud_service;

		$playlists_optParams = array('channelId' => YOUTUBE_CHANNELID, 'maxResults' => 50);
		$playlists = $soundcloud_service->playlists->listPlaylists($part, $playlists_optParams);

		return $playlists;
	} #==== End -- PlaylistsListFeed

	/**
	 * PlaylistItems
	 *
	 * Retrieves audios from a playlist.
	 *
	 * @param	int $playlist_id			The ID of the playlist.
	 * @param	string $part				The part parameter specifies a comma-separated list of
	 * 											one or more playlistItem resource properties that the API
	 * 											response will include. The part names that you can include
	 * 											in the parameter value are id, snippet, and contentDetails.
	 * @access	public
	 */
	public function PlaylistItems($playlist_id, $part='snippet')
	{
		# Bring $soundcloud_service into the scope
		//$soundcloud_service=$this->startSoundcloudService();
		global $soundcloud_service;

		$playlistsItems_optParams = array('maxResults' => 50, 'playlistId' => $playlist_id);
		$playlistsItems = $soundcloud_service->playlistItems->listPlaylistItems($part, $playlistsItems_optParams);

		# Instantiate a new Utility object.
		$utility = new Utility();
		# Sort the playlist array by date.
		$items=$this->sortByDate($playlistsItems);

		return $items;
	} #==== End -- PlaylistItems

	/**
	 * audioDetails
	 *
	 * Returns details of $audio_id
	 *
	 * @param	int $audio_id			The audio's ID on Soundcloud.
	 * @param	string $part			The part parameter specifies a comma-separated list of
	 * 										one or more audio resource properties that the API response
	 * 										will include. The part names that you can include in the
	 * 										parameter value are id, snippet, contentDetails, player,
	 * 										statistics, status, and topicDetails.
	 * @access	public
	 */
	public function audioDetails($audio_id, $part='snippet')
	{
		# Bring $soundcloud_service into the scope
		global $soundcloud_service;

		$audio_optParams = array();
		$audio = $soundcloud_service->audios->listAudios($audio_id, $part, $audio_optParams);

		return $audio;
	} #==== End -- audioDetails

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * startSoundcloudService
	 *
	 * Set the data member $soundcloud_service
	 *
	 * @access	protected
	 */
	protected function startSoundcloudService()
	{
		$sc = new Services_Soundcloud(
			$this->getSoundcloudClientId(),
			$this->getSoundcloudClientSecret(),
			$this->getSoundcloudRedirectUri()
		);

		return $sc;
	} #==== End -- startSoundcloudService

	/**
	 * sortByDate
	 *
	 * Returns the array of social data sorted by date.
	 *
	 * @param	array $playlistsItems		An array of values to sort
	 * @param	string $key					Key to sort
	 * @access	private
	 * @return	array
	 */
	private function sortByDate($playlistsItems, $key='publishedAt')
	{
		$mew_items=array();
		for($i=0; $i < count($playlistsItems['items']); $i++)
		{
			$new_items['kind']=$playlistsItems['kind'];
			$new_items['etag']=$playlistsItems['etag'];
			$new_items['pageInfo']['totalResults']=$playlistsItems['pageInfo']['totalResults'];
			$new_items['pageInfo']['resultsPerPage']=$playlistsItems['pageInfo']['resultsPerPage'];
			$new_items['items'][$i]['id']=$playlistsItems['items'][$i]['id'];
			$new_items['items'][$i]['kind']=$playlistsItems['items'][$i]['kind'];
			$new_items['items'][$i]['etag']=$playlistsItems['items'][$i]['etag'];
			$new_items['items'][$i]['publishedAt']=$playlistsItems['items'][$i]['snippet']['publishedAt'];
			$new_items['items'][$i]['snippet']['channelId']=$playlistsItems['items'][$i]['snippet']['channelId'];
			$new_items['items'][$i]['snippet']['title']=$playlistsItems['items'][$i]['snippet']['title'];
			$new_items['items'][$i]['snippet']['thumbnails']=$playlistsItems['items'][$i]['snippet']['thumbnails'];
			$new_items['items'][$i]['snippet']['playlistId']=$playlistsItems['items'][$i]['snippet']['playlistId'];
			$new_items['items'][$i]['snippet']['position']=$playlistsItems['items'][$i]['snippet']['position'];
			$new_items['items'][$i]['snippet']['resourceId']=$playlistsItems['items'][$i]['snippet']['resourceId'];
		}

		# Instantiate a new Utility object.
		$utility = new Utility();
		# Sort the playlist array by date.
		//$items=$utility->sortByDate($playlistsItems, $key, 'items', 'snippet');
		$items=$utility->sortByDate($new_items, $key, 'items');

		return $items;
	} #==== End -- sortByDate

	/*** End protected methods ***/

} # end Soundcloud class