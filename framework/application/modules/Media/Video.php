<?php /* framework/application/modules/Media/Video.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the Media class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Media.php');


/**
 * Video
 *
 * The Video class is used to access and manipulate data from the YouTube API & Vimeo API.
 *
 * @dependencies		Requires "data/path_definitions.php".
 * @dependencies		Requires "{MODULES}/Content/Category.php".
 * @dependencies		Requires "{MODULES}/Content/Language.php".
 * @dependencies		Requires "{MODULES}/FileHandler/FileHandler.php".
 * @dependencies		Requires "{MODULES}/Media/Image.php".
 * @dependencies		Requires "{MODULES}/API/YouTubeAPI.php".
 * @dependencies		Requires "{MODULES}/User/Contributor.php".
 */
class Video extends Media
{
	/*** data members ***/

	private $all_videos=array();
	private $api=NULL;
	private $confirmation_template=NULL;
	private $embed_code=NULL;
	private $file_name=NULL;
	private $google_client=NULL;
	private $is_playlist=FALSE;
	private $new_video=TRUE;
	private $thumbnail_url=NULL;
	private $video_id=NULL;
	private static $video_obj;
	private $video_type=NULL;
	private $video_url=NULL;
	private $youtube_obj=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllVideos
	 *
	 * Sets the data member $all_videos.
	 *
	 * @param	$videos					May be an array or a string. The method makes it into an array regardless.)
	 * @access	protected
	 */
	protected function setAllVideos($videos)
	{
		# Check if the passed value is empty.
		if(!empty($videos))
		{
			# Explicitly make it an array.
			$videos=(array)$videos;
			# Set the data member.
			$this->all_videos=$videos;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->all_videos=array();
		}
	} #==== End -- setAllVideos

	/**
	 * setAPI
	 *
	 * Sets the data member $api.
	 *
	 * @param	int $api
	 * @access	public
	 */
	public function setAPI($api)
	{
		# Check if the passed value is empty.
		if(!empty($api))
		{
			# Clean it up.
			$api=trim($api);
			# Set the data member.
			$this->api=$api;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->api=NULL;
		}
	} #==== End -- setAPI

	/**
	 * setConfirmationTemplate
	 *
	 * Sets the data member $confirmation_template.
	 *
	 * @param	$path
	 * @access	public
	 */
	public function setConfirmationTemplate($path)
	{
		# Check if the passed value is empty.
		if(!empty($path))
		{
			# Clean it up.
			$path=trim($path);
			# Check if this is a file.
			if(is_file($path)===FALSE)
			{
				# Set the data member.
				$path=NULL;
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$path=NULL;
		}
		# Set the data member.
		$this->confirmation_template=$path;
	} #==== End -- setConfirmationTemplate

	/**
	 * setEmbedCode
	 *
	 * Sets the data member $embed_code.
	 *
	 * @param	string $embed_code
	 * @access	public
	 */
	public function setEmbedCode($embed_code)
	{
		# Check if the passed value is empty.
		if(!empty($embed_code))
		{
			# Strip slashes and decode any html entities.
			$embed_code=html_entity_decode(stripslashes($embed_code), ENT_COMPAT, 'UTF-8');
			# Clean it up.
			$embed_code=trim($embed_code);
		}
		else
		{
			# Explicitly set the value to NULL.
			$embed_code=NULL;
		}
		# Set the data member.
		$this->embed_code=$embed_code;
	} #==== End -- setEmbedCode

	/**
	 * setFileName
	 *
	 * Sets the data member $file_name.
	 *
	 * @param	$file_name
	 * @access	public
	 */
	public function setFileName($file_name)
	{
		# Check if the passed value is empty.
		if(!empty($file_name))
		{
			# Clean it up.
			$file_name=trim($file_name);

			# Set the data member.
			$this->file_name=$file_name;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->file_name=NULL;
		}
	} #==== End -- setFileName

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
	} #==== End -- setGoogleClient

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 * Extends setID in Media.
	 *
	 * @param	int $id					A numeric ID representing the video.
	 * @param	string $media_type		The type of media that the ID represents. Default is "video".
	 * @access	public
	 */
	public function setID($id, $media_type='video')
	{
		try
		{
			# Check if the passed $id is empty.
			if($id=='add' OR $id=='select')
			{
				# Explicitly set the data member to NULL.
				$id=NULL;
			}
			parent::setID($id, $media_type);
		}
		catch(Exception $error)
		{
			throw $error;
		}
	} #==== End -- setID

	/**
	 * setIsPlaylist
	 *
	 * Set the data member $is_playlist
	 *
	 * @param	boolean $is_playlist
	 * @access	private
	 */
	private function setIsPlaylist($is_playlist)
	{
		$this->is_playlist=$is_playlist;
	} #==== End -- setIsPlaylist

	/**
	 * setNewVideo
	 *
	 * Set the data member $new_video
	 *
	 * @param	boolean $new_video
	 * @access	public
	 */
	public function setNewVideo($new_video)
	{
		$this->new_video=$new_video;
	} #==== End -- setNewVideo

	/**
	 * setThumbnailUrl
	 *
	 * Set the data member $thumbnail_url
	 *
	 * @param	string $thumbnail_url
	 * @access	public
	 */
	public function setThumbnailUrl($thumbnail_url)
	{
		$this->thumbnail_url=$thumbnail_url;
	} #==== End -- setThumbnailUrl

	/**
	 * setVideoId
	 *
	 * Set the data member $video_id
	 * This is the ID on YouTube.
	 *
	 * @param	string $video_id
	 * @access	public
	 */
	public function setVideoId($video_id)
	{
		$this->video_id=$video_id;
	} #==== End -- setVideoId

	/**
	 * setVideoType
	 *
	 * Sets the data member $video_type.
	 *
	 * @param	$video_type
	 * @access	public
	 */
	public function setVideoType($video_type)
	{
		# Set the data member.
		$this->video_type=$video_type;
	} #==== End -- setVideoType

	/**
	 * setVideoUrl
	 *
	 * Set the data member $video_url
	 *
	 * @param	string $video_url
	 * @access	public
	 */
	public function setVideoUrl($video_url)
	{
		$this->video_url=$video_url;
	} #==== End -- setVideoUrl

	/**
	 * setYouTubeObject
	 *
	 * Set the data member $youtube_obj
	 *
	 * @param	string $object
	 * @access	private
	 */
	private function setYouTubeObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->youtube_obj=$object;
	} #==== End -- setYouTubeObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllVideos
	 *
	 * Returns the data member $all_videos.
	 *
	 * @access	public
	 */
	public function getAllVideos()
	{
		return $this->all_videos;
	} #==== End -- getAllVideos

	/**
	 * getAPI
	 *
	 * Returns the data member $api.
	 *
	 * @access	public
	 */
	public function getAPI()
	{
		return $this->api;
	} #==== End -- getAPI

	/**
	 * getConfirmationTemplate
	 *
	 * Returns the data member $confirmation_template.
	 *
	 * @access	public
	 */
	public function getConfirmationTemplate()
	{
		return $this->confirmation_template;
	} #==== End -- getConfirmationTemplate

	/**
	 * getEmbedCode
	 *
	 * Gets the data member $embed_code
	 *
	 * @access	public
	 */
	public function getEmbedCode()
	{
		return $this->embed_code;
	} #==== End -- getEmbedCode

	/**
	 * getFileName
	 *
	 * Returns the data member $file_name.
	 *
	 * @access	public
	 */
	public function getFileName()
	{
		return $this->file_name;
	} #==== End -- getFileName

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
	} #==== End -- getGoogleClien

	/**
	 * getIsPlaylist
	 *
	 * Gets the data member $is_playlist
	 *
	 * @access	public
	 */
	public function getIsPlaylist()
	{
		return $this->is_playlist;
	} #==== End -- getIsPlaylist

	/**
	 * getNewVideo
	 *
	 * Gets the data member $new_video
	 *
	 * @access	public
	 */
	public function getNewVideo()
	{
		return $this->new_video;
	} #==== End -- getNewVideo

	/**
	 * getThumbnailUrl
	 *
	 * Gets the data member $thumbnail_url
	 *
	 * @access	public
	 */
	public function getThumbnailUrl()
	{
		return $this->thumbnail_url;
	} #==== End -- getThumbnailUrl

	/**
	 * getVideoId
	 *
	 * Gets the data member $video_id
	 * This is the ID on YouTube.
	 *
	 * @access	public
	 */
	public function getVideoId()
	{
		return $this->video_id;
	} #==== End -- getVideoId

	/**
	 * getVideoType
	 *
	 * Returns the data member $video_type.
	 *
	 * @access	public
	 */
	public function getVideoType()
	{
		return $this->video_type;
	} #==== End -- getVideoType

	/**
	 * getVideoUrl
	 *
	 * Gets the data member $video_url
	 *
	 * @access	public
	 */
	public function getVideoUrl()
	{
		return $this->video_url;
	} #==== End -- getVideoUrl

	/**
	 * getYouTubeObject
	 *
	 * Returns the data member $youtube_obj.
	 *
	 * @param	$domain						Optional. This is set for cron and CommandLine scripts.
	 * @access	public
	 */
	public function getYouTubeObject($domain=NULL)
	{
		# Check if there is a YouTube object.
		if(empty($this->youtube_obj) OR !is_object($this->youtube_obj))
		{
			# Get the YouTube Class.
			require_once Utility::locateFile(MODULES.'API'.DS.'YouTubeAPI.php');

			# Instantiate a new YouTube object.
			$yt=YouTube::getInstance();

			# Set the YouTube object to the data member.
			$this->setYouTubeObject($yt);

			$yt->setYouTubeClientId(YOUTUBE_CLIENT_ID);
			$yt->setYouTubeClientSecret(YOUTUBE_CLIENT_SECRET);
			if($domain!==NULL)
			{
				$yt->setYouTubeRedirectUri($domain);
			}
			else
			{
				$yt->setYouTubeRedirectUri(APPLICATION_URL.HERE);
			}
			$yt->setYouTubeDevKey(YOUTUBE_DEV_KEY);
			$yt->setYouTubeRefreshToken(YOUTUBE_REFRESH_TOKEN);

			# Start the YouTube Service.
			$yt->startYouTubeService();

			# Set the Google Client object to the data member.
			$this->setGoogleClient($yt->getGoogleClient());
		}
		return $this->youtube_obj;
	} #==== End -- getYouTubeObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllVideos
	 *
	 * Returns the number of videos in the database.
	 *
	 * @param	$where					The WHERE statements in the query.
	 * @param	$limit					The limit of records to count.
	 * @access	public
	 */
	public function countAllVideos($where=NULL, $limit=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'videos`'.($where===NULL ? '' : ' WHERE '.$where).(($limit===NULL) ? '' : ' LIMIT '.$limit));
			return $count;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- countAllVideos

	/**
	 * createPlaylistMenu
	 *
	 * Creates media XHTML elements and sets them to an array for display.
	 *
	 * @param	array $playlists
	 * @access	public
	 */
	public function createPlaylistMenu($playlists)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();

		try
		{
			$playlist_items='<li class="list-nav-1">No playlists</li>';
			if(!empty($playlists))
			{
				$playlist_items='';
				foreach($playlists as $playlists_data)
				{
					$name=$playlists_data->name;
					$url=VIDEOS_URL.'?playlist='.$playlists_data->id;
					$here_class=$doc->addHereClass($url, FALSE, FALSE);
					$playlist_items.='<li class="list-nav-1'.$here_class.'">'.
						'<a href="'.$url.'" title="'.$name.' video playlist">'.
							$name.
						'</a>'.
					'</li>';
				}
			}
			return $playlist_items;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- createPlaylistMenu

	/**
	 * deleteVideo
	 *
	 * Removes an video record from the `videos` table and the actual video file from the system.
	 *
	 * @param	int $id					The id of the video in the `videos` table.
	 * @param	$redirect
	 * @access	public
	 */
	public function deleteVideo($id, $redirect=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed id was empty.
			if(!empty($id))
			{
				# Check if a redirect URL was passed.
				if($redirect===NULL)
				{
					# Set the redirect to the default.
					$redirect=PROTOCAL.FULL_DOMAIN.HERE;
				}
				# Check if the passed redirect URL was FALSE.
				if($redirect===FALSE)
				{
					# Set the value to NULL (no redirect).
					$redirect===NULL;
				}
				# Validate the passed id as an integer.
				if($validator->isInt($id)===TRUE)
				{
					# Set the post's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					# Check if the video is premium content or not.
					$this_video=$this->getThisVideo($id);
					# Check if the video was found.
					if($this_video===FALSE)
					{
						# Set a nice message to the session.
						$_SESSION['message']='The video was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
						# If there is no redirect, return FALSE.
						return FALSE;
					}
					# Create a FALSE variable.
					$delete_video=FALSE;
					# Set the video's name data member to a local variable.
					$video_name=$this->getFileName();
					# Get the FileHandler class.
					require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
					# Instantiate a new FileHandler object.
					$file_handler=new FileHandler();
					# Remove the file extension.
					$video_name_no_ext=substr($video_name, 0, strrpos($video_name, '.'));
					# If $video_name is not set then there shouldn't be a copy on the server.
					if(!empty($video_name))
					{
						# Get the YouTube instance. Starts the YouTubeService if it's not already started.
						$yt=$this->getYouTubeObject();

						# Decode the `api` field.
						$api_decoded=json_decode($this->getAPI());
						# If there is a YouTube ID.
						if(isset($api_decoded->youtube_id))
						{
							# Set the YouTube ID to a variable.
							$yt_id=$api_decoded->youtube_id;
						}

						# NOTE: Why doesn't this delete all the videos? Deletes either the video in BODEGA or the one if media/files/ and moves on...
						# Delete the video.
						/*
						if(($file_handler->deleteFile(VIDEOS_PATH.'files'.DS.$video_name_no_ext.'.*', TRUE)===TRUE) || ($file_handler->deleteFile(BODEGA.'videos'.DS.$video_name)===TRUE) || (isset($yt_id) && $yt->deleteVideo($yt_id)===TRUE))
						{
							$delete_video=TRUE;
						}
						else
						{
							# Set a message to display to the user.
							$_SESSION['message']='That was not a valid video for deletion.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
						}
						*/
						if($file_handler->deleteFile(VIDEOS_PATH.'files'.DS.$video_name_no_ext.'.*', TRUE)===TRUE)
						{
							$delete_video=TRUE;
						}
						if($file_handler->deleteFile(BODEGA.'videos'.DS.$video_name)===TRUE)
						{
							$delete_video=TRUE;
						}
						if(isset($yt_id) && $yt->deleteVideo($yt_id)===TRUE)
						{
							$delete_video=TRUE;
						}
						if($delete_video!==TRUE)
						{
							# Set a message to display to the user.
							$_SESSION['message']='That was not a valid video for deletion.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
						}
					}
					else
					{
						# Set the video's name data member to a local variable.
						$video_name=$this->getTitle();
						$delete_video=TRUE;
					}

					# Delete the video.
					if(isset($delete_video) && $delete_video===TRUE)
					{
						try
						{
							# Delete the video from the `videos` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'videos` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Set a nice message to display to the user.
							$_SESSION['message']='The video '.$video_name.' was successfully deleted.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
							# If there is no redirect, return TRUE.
							return TRUE;
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('Error occured: '.$ez->error.', but the video itself was deleted.<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
						}
						catch(Exception $e)
						{
							throw $e;
						}
					}
				}
				else
				{
					# Set a nice message to the session.
					$_SESSION['message']='That video was not valid.';
					# Redirect the user back to the page without GET or POST data.
					$doc->redirect($redirect);
				}
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteVideo

	/**
	 * displayRecentVideo
	 *
	 * Generate markup for most recent video posted.
	 *
	 * @acess	public
	 */
	public function displayRecentVideo()
	{
		# Get's the Recent Video from the Video class.
		$single_video=$this->getRecentVideo();

		# Display the video details.
		$video_display='<div class="video-lg">';
		$video_display.=$single_video['video'];
		$video_display.='<h3 class="h-3">'.$single_video['title'].'</h3>';
		$video_display.=$single_video['description'];
		$video_display.='<div>';

		return $video_display;
	} #==== End -- displayRecentVideo

	/**
	 * displayVideoFeed
	 *
	 * Prints the users video feed.
	 *
	 * @access	public
	 */
	public function displayVideoFeed()
	{
		# Bring the Login Class into scope.
		global $login;

		try
		{
			if($login->checkAccess(MAN_USERS)===TRUE)
			{
				# Count the returned files.
				$video_count=$this->countAllVideos();
			}
			else
			{
				# Count the returned files.
				$video_count=$this->countAllVideos('`availability`=1 AND `new`=0');
			}
			# Check if there was returned content.
			if($video_count>0)
			{
				$video_id=NULL;
				$no_video=NULL;
				$playlist=NULL;

				if(isset($_GET['video']))
				{
					$video_id=$_GET['video'];
				}

				if(isset($_GET['playlist']))
				{
					# Assign playlist ID to a variable.
					$playlist='-'.$_GET['playlist'].'-';

					# Get the Videos from the database.
					$this->getVideos(NULL, '*', 'date', 'DESC', ' WHERE `new` = 0 AND `playlist` LIKE \'%'.$playlist.'%\'');
					# Set the returned Video records to a variable.
					$all_videos=$this->getAllVideos();

					# No videos in the playlist. Return error image.
					if(empty($all_videos))
					{
						return $display='<div class="no_video"></div>';
					}
					else
					{
						# If video_id is set in the URL.
						if($video_id!==NULL)
						{
							# Loop through the videos.
							foreach($all_videos as $video_key=>$video)
							{
								# If the $video_id does not match the videoId set the $no_video to TRUE.
								if($video_id!=$video->id)
								{
									$no_video=TRUE;
								}
								else
								{
									$no_video=FALSE;

									# Display the large video.
									$display=$this->getFirstVideo($all_videos, $video_key);

									# Remove the video from the array.
									unset($all_videos[$video_key]);
									break;
								}
							}
						}
					}
				}
				else
				{
					# This is not a playlist.
					$this->setIsPlaylist(FALSE);

					if($login->checkAccess(MAN_USERS)===TRUE)
					{
						# Get the Videos.
						$this->getVideos(NULL, '*', 'id', 'DESC');
					}
					else
					{
						# Get the Videos.
						$this->getVideos(NULL, '*', 'id', 'DESC', ' WHERE `availability`=1 AND `new`=0');
					}
					# Set the returned Video records to a variable.
					$all_videos=$this->getAllVideos();

					# If video_id is set in the URL.
					if(isset($video_id))
					{
						# Loop through the videos
						foreach($all_videos as $video_key=>$video)
						{
							# If the $video_id does not match the ID set $no_video to TRUE
							if($video_id!=$video->id)
							{
								$no_video=TRUE;
							}
							else
							{
								$no_video=FALSE;

								# Display the large video
								$display=$this->getFirstVideo($all_videos, $video_key);

								# Remove the video from the array
								unset($all_videos[$video_key]);
								break;
							}
						}
					}
				}

				# If the video doesn't exist ($no_video=TRUE)
				if($no_video)
				{
					return $display='<div class="no_video"></div>';
				}

				if(APPLICATION_URL.Utility::removeIndex(HERE)==VIDEOS_URL)
				{
					# If there is no video_id in the URL
					if(!isset($video_id))
					{
						# Large Video
						$display=$this->getFirstVideo($all_videos);

						# Remove the first video from the array
						unset($all_videos[0]);
					}

					# Check if there are more videos to display.
					if(count($all_videos)>0)
					{
						# Remove the first array element
						$display.=$this->markupSmallVideos(
							$all_videos,
							((isset($_GET['playlist']) ? $_GET['playlist'] : NULL)),
							array($video_id)
						);
					}
				}
				elseif(SECURE_VIDEOS_PATH==Utility::removeIndex(HERE))
				{
					$display=$this->markupManageVideos($all_videos);
				}
			}
			else
			{
				$display='<h3 class="h-3">There are no videos to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayVideoFeed

	/**
	 * getFirstVideo
	 *
	 * Get's the first video in an array element.
	 *
	 * @param	array $video_search			Response array from either the database or an API.
	 * @param	int $video_key				Optional - Array key of $video_search
	 * @access	public
	 */
	public function getFirstVideo($video_search, $video_key=NULL)
	{
		# Grab the first video.
		if(isset($video_key))
		{
			$large_video=array($video_search[$video_key]);
		}
		else
		{
			$large_video=array_slice($video_search, 0, 1);
		}
		# Get large video markup.
		$single_video=$this->markupLargeVideo($large_video);
		# Display the video details.
		$video_display='<div class="video-lg">';
		$video_display.=$single_video['video'];
		$video_display.='<h3 class="h-3">'.$single_video['title'].'</h3>';
		$video_display.=$single_video['description'];
		$video_display.='</div>';

		return $video_display;
	} #==== End -- getFirstVideo

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$video_obj)
		{
			self::$video_obj=new Video();
		}
		return self::$video_obj;
	} #==== End -- getInstance

	/**
	 * getRecentVideo
	 *
	 * Get's the recent video from YouTube or Vimeo.
	 *
	 * @access	public
	 */
	public function getRecentVideo()
	{
		global $login;

		if($login->checkAccess(MAN_USERS)===TRUE)
		{
			# Get the Videos.
			$this->getVideos('1', '*', 'id', 'DESC', ' WHERE `new` = 0');
		}
		else
		{
			# Get the Videos.
			$this->getVideos('1', '*', 'id', 'DESC', ' WHERE `availability` = 1 AND `new` = 0');
		}
		# Set the returned Video records to a variable.
		$recent_video=$this->getAllVideos();

		return $this->markupLargeVideo($recent_video);
	} #==== End -- getRecentVideo

	/**
	 * getThisVideo
	 *
	 * Retrieves video info from the `videos` table in the Database for the passed id or video name and sets it to the data member.
	 *
	 * @param	string $value				The name or id of the video to retrieve.
	 * @param	boolean $id					TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean						TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisVideo($value, $id=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed $value is an id.
			if($id===TRUE)
			{
				# Set the field to search for $value.
				$field='id';
				# Set the video id to the data member "cleaning" it.
				$this->setID($value);
				# Get the video id and reset it to the variable.
				$id=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='file_name';
				# Set the video name to the data member "cleaning" it.
				$this->setFileName($value);
				# Get the video name and reset it to the variable.
				$value=$this->getFileName();
			}
			# Get the video info from the database.
			$video=$db->get_row('SELECT `id`, `title`, `description`, `file_name`, `api`, `author`, `year`, `category`, `playlist`, `availability`, `date`, `image`, `institution`, `publisher`, `language`, `contributor` FROM `'.DBPREFIX.'videos` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($video!==NULL)
			{
				# Set the video API to the data member.
				$this->setAPI($video->api);
				# Set the video author to the data member.
				$this->setAuthor($video->author);
				# Set the video availability to the data member.
				$this->setAvailability($video->availability);
				# Pass the video category id(s) to the setCategories method, thus setting the data member with the category name(s).
				$this->setCategories($video->category);
				# Set the video contributor id to the data member.
				$this->setContID($video->contributor);
				# Set the video post/edit date to the data member.
				$this->setDate($video->date);
				# Set the video description to the data member.
				$this->setDescription($video->description);
				# Set the video name to the data member.
				$this->setFileName($video->file_name);
				# Set the video name to the data member.
				$this->setID($video->id);
				# Set the video's image ID to the data member.
				$this->setImageID($video->image);
				# Pass the video institution id to the setInstitution method, thus setting the data member with the institution name.
				$this->setInstitution($video->institution);
				# Pass the video language id to the setLanguage method, thus setting the data member with the language name.
				$this->setLanguage($video->language);
				# Pass the video playlist id(s) to the setPlaylists method, thus setting the data member with the playlist name(s).
				$this->setPlaylists($video->playlist);
				# Pass the video publisher id to the setPublisher method, thus setting the data member with the publisher name.
				$this->setPublisher($video->publisher);
				# Set the video title to the data member.
				$this->setTitle($video->title);
				# Set the video publish year to the data member.
				$this->setYear($video->year);
				return TRUE;
			}
			# Return FALSE because the video wasn't in the table.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			# Throw an exception because there was a Database connection error.
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			# Re-throw any caught exceptions.
			throw $e;
		}
	} #==== End -- getThisVideo

	/**
	 * getVideos
	 *
	 * Retrieves records from the `videos` table.
	 *
	 * @param	$limit						The LIMIT of the records.
	 * @param	$fields						The name of the field(s) to be retrieved.
	 * @param	$order						The name of the field to order the records by.
	 * @param	$direction					The direction to order the records.
	 * @param	$and_sql					Extra AND statements in the query.
	 * @return	Boolean						TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getVideos($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `videos` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'videos`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllVideos($records);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getVideos

	/**
	 * markupManageVideos
	 *
	 * Returns the HTML markup that lists video from the database or API.
	 *
	 * @param	array $video_search
	 * @access	public
	 */
	public function markupManageVideos($video_search)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the YouTube instance to a variable.
		$yt=$this->getYouTubeObject();

		$display='<table class="table-video">'.
			'<th>'.
				'<a href="'.ADMIN_URL.'ManageMedia/videos/?by_video_name=DESC" title="Order by video name">View</a>'.
			'</th>'.
			'<th>'.
				'<a href="'.ADMIN_URL.'ManageMedia/videos/?by_title=DESC" title="Order by title">Title</a>'.
			'</th>'.
			'<th>'.
				'Options'.
			'</th>';

		foreach($video_search as $videos)
		{
			# Get the video ID and assign it to a variable.
			$this->setID($videos->id);
			# Set the title to a variable
			$this->setTitle($db->sanitize($videos->title));
			# Decode the `api` field.
			$api_decoded=json_decode($videos->api);

			# If the youtube_id is in the `api` field then this video is on YouTube.
			if(isset($api_decoded->youtube_id))
			{
				# Set YouTube ID
				$this->setVideoId($api_decoded->youtube_id);
				# Create video_url variable.
				$video_url=$yt->getYoutubeUrl().$this->getVideoId();
			}
			/*
			# If vimeo_id is in the `api` then this video is on Vimeo.
			elseif(isset($api_decoded->vimeo_id))
			{
				$video_url='vimeo_url';
			}
			*/
			# If it's not on YouTube or Vimeo, stream from the server.
			else
			{
				$video_url=VIDEOS_URL.$videos->file_name;
			}
			# Create video URL.
			$this->setVideoUrl($video_url);

			# If we have thumbnails from YouTube in our database...
			if(isset($api_decoded->youtube_thumbnails->default->url))
			{
				# Set the image path to a variable.
				$image_path='';
				# Use the YouTube thumbnail.
				$image_url=$api_decoded->youtube_thumbnails->default->url;
			}
			else
			{
				# Set the image ID.
				$this->setImageID($videos->image);
				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());
				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();
				# Set the image path to a variable.
				$image_path=IMAGES_PATH.$image_obj->getImage();
				# Set the thumbnail to a variable.
				$image_url=$db->sanitize(IMAGES.(file_exists($image_path)===TRUE && $image_obj->getImage()!==NULL ? $image_obj->getImage() : DEFAULT_VIDEO_THUMBNAIL));
			}
			# Set the image path to the data member.
			$this->setThumbnailUrl($image_url);

			# Set the markup to a variable
			$display.='<tr>'.
				'<td>'.
					'<a class="image-link" href="'.$this->getVideoUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'" rel="'.FW_POPUP_HANDLE.'" data-image="'.$this->getThumbnailUrl().'">'.
						'<img src="'.$this->getThumbnailUrl().'" class="image" alt="Poster for '.$this->getTitle().'"/>'.
					'</a>'.
				'</td>'.
				'<td>'.
					'<a href="'.VIDEOS_URL.'?video='.$this->getID().'" title="View \''.$this->getTitle().'\' on '.DOMAIN_NAME.'" target="_blank">'.$this->getTitle().'</a>'.
				'</td>'.
				'<td>'.
					'<a href="'.ADMIN_URL.'ManageMedia/videos/?video='.$this->getID().'" class="button-edit" title="Edit this video entry">Edit</a><a href="'.ADMIN_URL.'ManageMedia/videos/?video='.$this->getID().'&delete" class="button-delete" title="Delete this video entry">Delete</a>'.
				'</td>'.
			'</tr>';
		}
		$display.='</table>';

		return $display;
	} #==== End -- markupManageVideos

	/**
	 * markupLargeVideo
	 *
	 * Returns the HTML markup to display a large video.
	 *
	 * @param	array $large_video		The array for the large video.
	 * @access	public
	 */
	public function markupLargeVideo($large_video)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		$display=array();

		if(!empty($large_video))
		{
			# Get the video ID and assign it to a variable.
			$this->setID($large_video[0]->id);

			# Decode the `api` field.
			$api_decoded=json_decode($large_video[0]->api);
			# If the youtube_id is in the `api` field then this video is on YouTube.
			if(isset($api_decoded->youtube_id))
			{
				# Set the YouTube instance to a variable.
				$yt=$this->getYouTubeObject();
				# Set YouTube ID
				$this->setVideoId($api_decoded->youtube_id);
				# Create video_url variable.
				$video_url=$yt->getYoutubeUrl().$this->getVideoId();
			}
			/*
			elseif(isset($api_decoded->vimeo_id))
			{
				# Create video_url variable.
				$video_url='vimeo_url';
			}
			*/
			else
			{
				$video_name=$large_video[0]->file_name;
				# Remove the file extension.
				$video_name_no_ext=substr($video_name, 0, strrpos($video_name, '.'));
				# NOTE: Figure out which video to serve them here when we create multiple video versions.
				# Create video_url variable.
				$video_url=VIDEOS_URL.'files/'.$video_name_no_ext.'.mp4';
			}
			# Create video URL.
			$this->setVideoUrl($video_url);

			# Set the availability.
			$this->setAvailability($large_video[0]->availability);
			# Set the title.
			$this->setTitle($db->sanitize($large_video[0]->title));

			if(isset($api_decoded->youtube_thumbnails->medium->url))
			{
				# Set the thumbnail to a variable.
				$image_url=$api_decoded->youtube_thumbnails->medium->url;
			}
			else
			{
				# Set the image ID.
				$this->setImageID($large_video[0]->image);
				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());
				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();
				# Set the image path to a variable.
				$image_path=IMAGES_PATH.$image_obj->getImage();
				# Set the thumbnail to a variable.
				$image_url=$db->sanitize(IMAGES.(file_exists($image_path)===TRUE && $image_obj->getImage()!==NULL ? $image_obj->getImage() : DEFAULT_VIDEO_THUMBNAIL));
			}
			# Set the image path to the data member.
			$this->setThumbnailUrl($image_url);

			# Set the description
			$this->setDescription($db->sanitize($large_video[0]->description, 5));

			# Set the markup to the display array.
			$display['video']='<a class="image-link" href="'.$this->getVideoUrl().'" title="Play '.$this->getTitle().'" data-image="'.$this->getThumbnailUrl().'"'.($this->getAvailability()==1 ? '  rel="'.FW_POPUP_HANDLE.'"' : ' target="_blank"').'>'.
				'<img src="'.$this->getThumbnailUrl().'" class="image" alt="Poster for '.$this->getTitle().'"/>'.
				'<span class="play-static"></span>'.
			'</a>';
			$display['title']='<a href="'.VIDEOS_URL.'?video='.$this->getID().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'" target="_blank">'.$this->getTitle().'</a>';
			$display['description']='<p>'.$this->getDescription().'</p>';
		}

		return $display;
	} #==== End -- markupLargeVideo

	/**
	 * markupSmallVideos
	 *
	 * Returns the HTML markup for the small videos.
	 *
	 * @param	array $small_videos			The array for the small videos.
	 * @access	public
	 */
	public function markupSmallVideos($small_videos, $playlist_value=NULL, $exclude_video=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Small Videos.
		$display='<div class="feed_wrapper-video">'.
			'<button class="arrow-prev">Previous Video</button>'.
			'<div class="feed_list-video">'.
				'<ul class="feed-video">';

		$playlist_param='';
		# Check if the videos belong to a playlist.
		if($playlist_value!=NULL)
		{
			$playlist_param='playlist='.$playlist_value.'&';
		}

		foreach($small_videos as $videos)
		{
			# Get the video ID and assign it to a variable.
			$this->setID($videos->id);

			$include_it=TRUE;

			# Check if video should be excluded.
			if(!empty($exclude_video) && in_array($this->getID(), $exclude_video))
			{
				$include_it=FALSE;
			}

			# Check if this specific video should be included.
			if($include_it===TRUE)
			{
				# Create video URL.
				$this->setVideoUrl(VIDEOS_URL.'?'.$playlist_param.'video='.$this->getID());
				# Set the title to a variable
				$this->setTitle($db->sanitize($videos->title));

				# Decode the `api` field.
				$api_decoded=json_decode($videos->api);
				if(isset($api_decoded->youtube_thumbnails->default->url))
				{
					# Set the thumbnail to a variable.
					$image_url=$api_decoded->youtube_thumbnails->default->url;
				}
				else
				{
					# Set the image ID.
					$this->setImageID($videos->image);
					# Get the image information from the database, and set them to data members.
					$this->getThisImage($this->getImageID());
					# Set the Image object to a variable.
					$image_obj=$this->getImageObj();
					# Set the image path to a variable.
					$image_path=IMAGES_PATH.$image_obj->getImage();
					# Set the thumbnail to a variable.
					$image_url=$db->sanitize(IMAGES.(file_exists($image_path)===TRUE && $image_obj->getImage()!==NULL ? $image_obj->getImage() : DEFAULT_VIDEO_THUMBNAIL));
				}
				# Set the image path to the data member.
				$this->setThumbnailUrl($image_url);

				# Set the markup to a variable
				$display.='<li>'.
					'<a href="'.$this->getVideoUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'">'.
						'<img src="'.$this->getThumbnailUrl().'" alt="Poster for '.$this->getTitle().'" class="thumbnail-small"/>'.
					'</a>'.
				'</li>';
			}
		}

		$display.='</ul>'.
			'</div>'.
			'<button class="arrow-next">Next Video</button>'.
		'</div>';

		return $display;
	} #==== End -- markupSmallVideos

	/*** End public methods ***/

} # end Video class