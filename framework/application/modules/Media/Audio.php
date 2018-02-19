<?php /* framework/application/modules/Media/Audio.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the Media class.
require_once Utility::locateFile(MODULES.'Media'.DS.'Media.php');


/**
 * Audio
 *
 * The Audio class is used to access and manipulate audio.
 *
 * @dependencies		Requires "data/path_definitions.php".
 * @dependencies		Requires "{MODULES}/Content/Category.php".
 * @dependencies		Requires "{MODULES}/Content/Language.php".
 * @dependencies		Requires "{MODULES}/FileHandler/FileHandler.php".
 * @dependencies		Requires "{MODULES}/Media/Image.php".
 * @dependencies		Requires "{MODULES}/API/SoundcloudAPI.php".
 * @dependencies		Requires "{MODULES}/User/Contributor.php".
 */
class Audio extends Media
{
	/*** data members ***/

	private $all_audio=array();
	private $api=NULL;
	private $audio_id=NULL;
	private static $audio_obj;
	private $audio_type=NULL;
	private $audio_url=NULL;
	private $confirmation_template=NULL;
	private $embed_code=NULL;
	private $file_name=NULL;
	private $new_audio=TRUE;
	private $is_playlist=FALSE;
	private $soundcloud_obj=NULL;
	private $thumbnail_url=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllAudio
	 *
	 * Sets the data member $all_audio.
	 *
	 * @param array $audio					May be an array or a string. The method makes it into an array regardless.
	 */
	protected function setAllAudio($audio)
	{
		# Check if the passed value is empty.
		if(!empty($audio))
		{
			# Explicitly make it an array.
			$audio=(array)$audio;
			# Set the data member.
			$this->all_audio=$audio;
		}
		else
		{
			# Explicitly set the data member to an empty array.
			$this->all_audio=array();
		}
	} #==== End -- setAllAudio

	/**
	 * setAPI
	 *
	 * Sets the data member $api.
	 *
	 * @param int $api
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
	 * setAudioId
	 *
	 * Set the data member $audio_id
	 * This is the ID on Soundcloud.
	 *
	 * @param	string $audio_id
	 */
	public function setAudioId($audio_id)
	{
		$this->audio_id=$audio_id;
	} #==== End -- setAudioId

	/**
	 * setAudioType
	 *
	 * Sets the data member $audio_type.
	 *
	 * @param	$audio_type
	 */
	public function setAudioType($audio_type)
	{
		# Set the data member.
		$this->audio_type=$audio_type;
	} #==== End -- setAudioType

	/**
	 * setAudioUrl
	 *
	 * Set the data member $audio_url
	 *
	 * @param	string $audio_url
	 */
	public function setAudioUrl($audio_url)
	{
		$this->audio_url=$audio_url;
	} #==== End -- setAudioUrl

	/**
	 * setConfirmationTemplate
	 *
	 * Sets the data member $confirmation_template.
	 *
	 * @param	$path
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
	 * setID
	 *
	 * Sets the data member $id.
	 * Extends setID in Media.
	 *
	 * @param int $id            A numeric ID representing the audio.
	 * @param string $media_type The type of media that the ID represents. Default is "audio".
	 * @throws Exception
	 */
	public function setID($id, $media_type='audio')
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
	 */
	private function setIsPlaylist($is_playlist)
	{
		$this->is_playlist=$is_playlist;
	} #==== End -- setIsPlaylist

	/**
	 * setNewAudio
	 *
	 * Set the data member $new_audio
	 *
	 * @param	boolean $new_audio
	 * @access	public
	 */
	public function setNewAudio($new_audio)
	{
		$this->new_audio=$new_audio;
	} #==== End -- setNewAudio

	/**
	 * setSoundcloudObject
	 *
	 * Set the data member $soundcloud_obj
	 *
	 * @param	string $object
	 * @access	private
	 */
	private function setSoundcloudObject($object)
	{
		# Check if the passed value is an object.
		if(is_object($object))
		{
			$this->soundcloud_obj=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->soundcloud_obj=NULL;
		}
	} #==== End -- setSoundcloudObject

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

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllAudio
	 *
	 * Returns the data member $all_audio.
	 *
	 * @access	public
	 */
	public function getAllAudio()
	{
		return $this->all_audio;
	} #==== End -- getAllAudio

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
	 * getAudioId
	 *
	 * Gets the data member $audio_id
	 * This is the ID on Soundcloud.
	 *
	 * @access	public
	 */
	public function getAudioId()
	{
		return $this->audio_id;
	} #==== End -- getAudioId

	/**
	 * getAudioType
	 *
	 * Returns the data member $audio_type.
	 *
	 * @access	public
	 */
	public function getAudioType()
	{
		return $this->audio_type;
	} #==== End -- getAudioType

	/**
	 * getAudioUrl
	 *
	 * Gets the data member $audio_url
	 *
	 * @access	public
	 */
	public function getAudioUrl()
	{
		return $this->audio_url;
	} #==== End -- getAudioUrl

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
	 * getNewAudio
	 *
	 * Gets the data member $new_audio
	 *
	 * @access	public
	 */
	public function getNewAudio()
	{
		return $this->new_audio;
	} #==== End -- getNewAudio

	/**
	 * getSoundcloudObject
	 *
	 * Returns the data member $soundcloud_obj.
	 *
	 * @param	$domain						Optional. This is set for cron and CommandLine scripts.
	 * @access	public
	 */
	public function getSoundcloudObject($domain=NULL)
	{
		# Check if there is a Soundcloud object.
		if(empty($this->soundcloud_obj) OR !is_object($this->soundcloud_obj))
		{
			# Get the SoundcloudAPI Class.
			require_once Utility::locateFile(MODULES.'API'.DS.'SoundcloudAPI.php');

			# Instantiate a new Soundcloud object.
			$soundcloud_obj=Soundcloud::getInstance();

			# Set the Soundcloud object to the data member.
			$this->setSoundcloudObject($soundcloud_obj);

			$soundcloud_obj->setSoundcloudClientId(SOUNDCLOUD_CLIENT_ID);
			$soundcloud_obj->setSoundcloudClientSecret(SOUNDCLOUD_CLIENT_SECRET);
			if($domain!==NULL)
			{
				$soundcloud_obj->setSoundcloudRedirectUri($domain);
			}
			else
			{
				$soundcloud_obj->setSoundcloudRedirectUri(APPLICATION_URL.HERE);
			}
			//$soundcloud_obj->setSoundcloudDevKey(SOUNDCLOUD_DEV_KEY);
			//$soundcloud_obj->setSoundcloudRefreshToken(SOUNDCLOUD_REFRESH_TOKEN);

			# Start the Soundcloud Service.
			//$soundcloud_obj->startSoundcloudService();
		}
		return $this->soundcloud_obj;
	} #==== End -- getSoundcloudObject

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

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllAudio
	 *
	 * Returns the number of audio files in the database.
	 *
	 * @param	$where					The WHERE statements in the query.
	 * @param	$limit					The limit of records to count.
	 * @access	public
	 */
	public function countAllAudio($where=NULL, $limit=NULL)
	{
		try
		{
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Count the records.
			$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'audio`'.($where===NULL ? '' : ' WHERE '.$where).(($limit===NULL) ? '' : ' LIMIT '.$limit));
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
	} #==== End -- countAllAudio

	/**
	 * createPlaylistMenu
	 *
	 * Creates media XHTML elements and sets them to an array for display.
	 *
	 * @param array $playlists
	 * @return string
	 * @throws Exception
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
					$url=AUDIO_URL.'?playlist='.$playlists_data->id;
					$here_class=$doc->addHereClass($url, FALSE, FALSE);
					$playlist_items.='<li class="list-nav-1'.$here_class.'">'.
						'<a href="'.$url.'" title="'.$name.' audio playlist">'.
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
	 * deleteAudio
	 *
	 * Removes an audio record from the `audio` table and the actual audio file from the system.
	 *
	 * @param int $id The id of the audio in the `audio` table.
	 * @param $redirect
	 * @return bool
	 * @throws Exception
	 */
	public function deleteAudio($id, $redirect=NULL)
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
					$redirect=NULL;
				}
				# Validate the passed id as an integer.
				if($validator->isInt($id)===TRUE)
				{
					# Set the post's id to a variable and explicitly make it an interger.
					$id=(int)$id;
					# Check if the audio is premium content or not.
					$this_audio=$this->getThisAudio($id);
					# Check if the audio was found.
					if($this_audio===FALSE)
					{
						# Set a nice message to the session.
						$_SESSION['message']='The audio was not found.';
						# Redirect the user back to the page without GET or POST data.
						$doc->redirect($redirect);
						# If there is no redirect, return FALSE.
						return FALSE;
					}
					# Set the audio's name data member to a local variable.
					$audio_name=$this->getFileName();
					# Get the FileHandler class.
					require_once Utility::locateFile(MODULES.'FileHandler'.DS.'FileHandler.php');
					# Instantiate a new FileHandler object.
					$file_handler=new FileHandler();
					# Remove the file extension.
					$audio_name_no_ext=substr($audio_name, 0, strrpos($audio_name, '.'));
					# If $audio_name is not set then there shouldn't be a copy on the server.
					if(!empty($audio_name))
					{
						# Delete the audio.
						if(($file_handler->deleteFile(AUDIO_PATH.'files'.DS.$audio_name_no_ext.'.mp3')===TRUE) && ($file_handler->deleteFile(BODEGA.'audio'.DS.$audio_name)===TRUE))
						{
							try
							{
								# Delete the audio from the `audio` table.
								$db->query('DELETE FROM `'.DBPREFIX.'audio` WHERE `id` = '.$db->quote($id).' LIMIT 1');
								# Set a nice message to display to the user.
								$_SESSION['message']='The audio '.$audio_name.' was successfully deleted.';
								# Redirect the user back to the page without GET or POST data.
								$doc->redirect($redirect);
								# If there is no redirect, return TRUE.
								return TRUE;
							}
							catch(ezDB_Error $ez)
							{
								throw new Exception('Error occured: '.$ez->error.', but the audio file itself was deleted.<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
							}
							catch(Exception $e)
							{
								throw $e;
							}
						}
						else
						{
							# Set a message to display to the user.
							$_SESSION['message']='That was not a valid audio for deletion.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
						}
					}
					else
					{
						# Set the audio's name data member to a local variable.
						$audio_name=$this->getTitle();
						# Delete the audio.
						try
						{
							# Delete the audio from the `audio` table.
							$deleted=$db->query('DELETE FROM `'.DBPREFIX.'audio` WHERE `id` = '.$db->quote($id).' LIMIT 1');
							# Set a nice message to display to the user.
							$_SESSION['message']='The audio '.$audio_name.' was successfully deleted.';
							# Redirect the user back to the page without GET or POST data.
							$doc->redirect($redirect);
							# If there is no redirect, return TRUE.
							return TRUE;
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('Error occured: '.$ez->error.', but the audio itself was deleted.<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
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
					$_SESSION['message']='That audio was not valid.';
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
	} #==== End -- deleteAudio

	/**
	 * displayAudioFeed
	 *
	 * Prints the users audio feed.
	 *
	 * @access	public
	 */
	public function displayAudioFeed()
	{
		# Bring the Login Class into scope.
		global $login;

		try
		{
			if($login->checkAccess(MAN_USERS)===TRUE)
			{
				# Count the returned files.
				$audio_count=$this->countAllAudio();
			}
			else
			{
				# Count the returned files.
				$audio_count=$this->countAllAudio('`availability`=1 AND `new`=0');
			}
			# Check if there was returned content.
			if($audio_count>0)
			{
				$audio_id=NULL;
				$no_audio=NULL;
				$playlist=NULL;

				if(isset($_GET['audio']))
				{
					$audio_id=$_GET['audio'];
				}

				if(isset($_GET['playlist']))
				{
					# Assign playlist ID to a variable.
					$playlist='-'.$_GET['playlist'].'-';

					# Get the Audio from the database.
					$this->getAudio(NULL, '*', 'date', 'DESC', ' WHERE `new` = 0 AND `playlist` LIKE \'%'.$playlist.'%\'');
					# Set the returned Audio records to a variable.
					$all_audio=$this->getAllAudio();

					# No audio in the playlist. Return error image.
					if(empty($all_audio))
					{
						return $display='<div class="no_video"></div>';
					}
					else
					{
						# If audio_id is set in the URL.
						if($audio_id!==NULL)
						{
							# Loop through the audio.
							foreach($all_audio as $audio_key=>$audio)
							{
								# If the $audio_id does not match the audio Id set the $no_audio to TRUE.
								if($audio_id!=$audio->id)
								{
									$no_audio=TRUE;
								}
								else
								{
									$no_audio=FALSE;

									# Display the large audio.
									$display=$this->getFirstAudio($all_audio, $audio_key);

									# Remove the audio from the array.
									unset($all_audio[$audio_key]);
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
						# Get the Audio.
						$this->getAudio(NULL, '*', 'id', 'DESC');
					}
					else
					{
						# Get the Audio.
						$this->getAudio(NULL, '*', 'id', 'DESC', ' WHERE `availability`=1 AND `new`=0');
					}
					# Set the returned Audio records to a variable.
					$all_audio=$this->getAllAudio();

					# If audio_id is set in the URL.
					if(isset($audio_id))
					{
						# Loop through the audio
						foreach($all_audio as $audio_key=>$audio)
						{
							# If the $audio_id does not match the ID set $no_audio to TRUE
							if($audio_id!=$audio->id)
							{
								$no_audio=TRUE;
							}
							else
							{
								$no_audio=FALSE;

								# Display the large audio
								$display=$this->getFirstAudio($all_audio, $audio_key);

								# Remove the audio from the array
								unset($all_audio[$audio_key]);
								break;
							}
						}
					}
				}

				# If the audio doesn't exist ($no_audio=TRUE)
				if($no_audio)
				{
					return $display='<div class="no_video"></div>';
				}

				if(APPLICATION_URL.Utility::removeIndex(HERE)==AUDIO_URL)
				{
					# If there is no audio_id in the URL
					if(!isset($audio_id))
					{
						# Large Audio
						$display=$this->getFirstAudio($all_audio);
						# Remove the first audio from the array
						unset($all_audio[0]);
					}

					# Check if there are more audio to display.
					if(count($all_audio)>0)
					{
						# Remove the first array element
						$display.=$this->markupSmallAudio(
							$all_audio,
							((isset($_GET['playlist']) ? $_GET['playlist'] : NULL)),
							array($audio_id)
						);
					}
				}
				elseif(SECURE_AUDIO_PATH==Utility::removeIndex(HERE))
				{
					$display=$this->markupManageAudio($all_audio);
				}
			}
			else
			{
				$display='<h3 class="h-3">There is no audio to display.</h3>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayAudioFeed

	/**
	 * getAudio
	 *
	 * Retrieves records from the `audio` table.
	 *
	 * @param	$limit						The LIMIT of the records.
	 * @param	$fields						The name of the field(s) to be retrieved.
	 * @param	$order						The name of the field to order the records by.
	 * @param	$direction					The direction to order the records.
	 * @param	$and_sql					Extra AND statements in the query.
	 * @return	boolean						TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getAudio($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Retrieve the records from the `audio` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'audio`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			if($records!==NULL)
			{
				# Set the returned records to the data member (explicitly turning it into an array.)
				$this->setAllAudio($records);
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
	} #==== End -- getAudio

	/**
	 * getFirstAudio
	 *
	 * Get's the first audio in an array element.
	 *
	 * @param	array $audio_search			Response array from either the database or an API.
	 * @param	int $audio_key				Optional - Array key of $audio_search
	 * @access	public
	 */
	public function getFirstAudio($audio_search, $audio_key=NULL)
	{
		# Grab the first audio.
		if(isset($audio_key))
		{
			$large_audio=array($audio_search[$audio_key]);
		}
		else
		{
			$large_audio=array_slice($audio_search, 0, 1);
		}
		# Get large audio markup.
		$single_audio=$this->markupLargeAudio($large_audio);
		# Display the audio details.
		$audio_display='<div class="audio-lg">';
		$audio_display.=$single_audio['audio'];
		$audio_display.='<h3 class="h-3">'.$single_audio['title'].'</h3>';
		$audio_display.=$single_audio['description'];
		$audio_display.='<div>';

		return $audio_display;
	} #==== End -- getFirstAudio

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$audio_obj)
		{
			self::$audio_obj=new Audio();
		}
		return self::$audio_obj;
	} #==== End -- getInstance

	/**
	 * getThisAudio
	 *
	 * Retrieves audio info from the `audio` table in the Database for the passed id or audio name and sets it to the data member.
	 *
	 * @param	string $value				The name or id of the audio to retrieve.
	 * @param	boolean $id					TRUE if the passed $value is an id, FALSE if not.
	 * @return	boolean						TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisAudio($value, $id=TRUE)
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
				# Set the audio id to the data member "cleaning" it.
				$this->setID($value);
				# Get the audio id and reset it to the variable.
				$id=$this->getID();
			}
			else
			{
				# Set the field to search for $value.
				$field='file_name';
				# Set the audio name to the data member "cleaning" it.
				$this->setFileName($value);
				# Get the audio name and reset it to the variable.
				$value=$this->getFileName();
			}
			# Get the audio info from the database.
			$audio=$db->get_row('SELECT `id`, `title`, `description`, `file_name`, `api`, `author`, `year`, `category`, `playlist`, `availability`, `date`, `image`, `institution`, `publisher`, `language`, `contributor` FROM `'.DBPREFIX.'audio` WHERE `'.$field.'` = '.$db->quote($db->escape($value)).' LIMIT 1');
			# Check if a row was returned.
			if($audio!==NULL)
			{
				# Set the audio API to the data member.
				$this->setAPI($audio->api);
				# Set the audio author to the data member.
				$this->setAuthor($audio->author);
				# Set the audio availability to the data member.
				$this->setAvailability($audio->availability);
				# Pass the audio category id(s) to the setCategories method, thus setting the data member with the category name(s).
				$this->setCategories($audio->category);
				# Set the audio contributor id to the data member.
				$this->setContID($audio->contributor);
				# Set the audio post/edit date to the data member.
				$this->setDate($audio->date);
				# Set the audio description to the data member.
				$this->setDescription($audio->description);
				# Set the audio name to the data member.
				$this->setFileName($audio->file_name);
				# Set the audio name to the data member.
				$this->setID($audio->id);
				# Set the audio's image ID to the data member.
				$this->setImageID($audio->image);
				# Pass the audio institution id to the setInstitution method, thus setting the data member with the institution name.
				$this->setInstitution($audio->institution);
				# Pass the audio language id to the setLanguage method, thus setting the data member with the language name.
				$this->setLanguage($audio->language);
				# Pass the audio playlist id(s) to the setPlaylists method, thus setting the data member with the playlist name(s).
				$this->setPlaylists($audio->playlist);
				# Pass the audio publisher id to the setPublisher method, thus setting the data member with the publisher name.
				$this->setPublisher($audio->publisher);
				# Set the audio title to the data member.
				$this->setTitle($audio->title);
				# Set the audio publish year to the data member.
				$this->setYear($audio->year);
				return TRUE;
			}
			# Return FALSE because the audio wasn't in the table.
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
	} #==== End -- getThisAudio

	/**
	 * markupManageAudio
	 *
	 * Returns the HTML markup that lists audio from the database or API.
	 *
	 * @param	array $audio_search
	 * @access	public
	 */
	public function markupManageAudio($audio_search)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Soundcloud instance to a variable.
		//$soundcloud_obj=$this->getSoundcloudObject();

		$display='<table class="table-audio">'.
			'<th>'.
				'<a href="'.ADMIN_URL.'ManageMedia/audio/?by_audio_name=DESC" title="Order by audio name">View</a>'.
			'</th>'.
			'<th>'.
				'<a href="'.ADMIN_URL.'ManageMedia/audio/?by_title=DESC" title="Order by title">Title</a>'.
			'</th>'.
			'<th>'.
				'Options'.
			'</th>';

		foreach($audio_search as $audio)
		{
			# Get the audio ID and assign it to a variable.
			$this->setID($audio->id);
			# Set the title to a variable
			$this->setTitle($db->sanitize($audio->title));
			# Decode the `api` field.
			$api_decoded=json_decode($audio->api);

			# If the soundcloud_id is in the `api` field then this audio is on Soundcloud.
			if(isset($api_decoded->soundcloud_id))
			{
				# Set Soundcloud ID
				$this->setAudioId($api_decoded->soundcloud_id);
				# Create audio_url variable.
				//$audio_url=$soundcloud_obj->getSoundCloudUrl().$this->getAudioId();
				# Temp variable.
				$audio_url='';
			}
			# If it's not on Soundcloud, stream from the server.
			else
			{
				# Remove the file extension.
				$file_name_no_ext=substr($audio->file_name, 0, strrpos($audio->file_name, '.'));
				# Create audio_url variable.
				$audio_url=AUDIO_URL.'files'.DS.$file_name_no_ext.'.mp3';
			}
			# Create audio URL.
			$this->setAudioUrl($audio_url);

			# If we have thumbnails from SoundCloud in our database...
			if(isset($api_decoded->soundcloud_thumbnails->default->url))
			{
				# Set the image path to a variable.
				$image_path='';
				# Use the SoundCloud thumbnail.
				$image_url=$api_decoded->soundcloud_thumbnails->default->url;
			}
			else
			{
				# Set the image ID.
				$this->setImageID($audio->image);
				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());
				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();
				# Set the image path to a variable.
				$image_path=IMAGES_PATH.$image_obj->getImage();
				# Set the thumbnail to a variable.
				$image_url=$db->sanitize(IMAGES.(file_exists($image_path)===TRUE && $image_obj->getImage()!==NULL ? $image_obj->getImage() : DEFAULT_AUDIO_THUMBNAIL));
			}
			# Set the image path to the data member.
			$this->setThumbnailUrl($image_url);

			# Set the markup to a variable
			$display.='<tr>'.
				'<td>'.
					'<a class="image-link" href="'.$this->getAudioUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'" rel="'.FW_POPUP_HANDLE.'" data-image="'.$this->getThumbnailUrl().'">'.
						'<img src="'.$this->getThumbnailUrl().'" class="image" alt="Cover for '.$this->getTitle().'"/>'.
					'</a>'.
				'</td>'.
				'<td>'.
					'<a href="'.AUDIO_URL.'?audio='.$this->getID().'" title="View \''.$this->getTitle().'\' on '.DOMAIN_NAME.'" target="_blank">'.$this->getTitle().'</a>'.
				'</td>'.
				'<td>'.
					'<a href="'.ADMIN_URL.'ManageMedia/audio/?audio='.$this->getID().'" class="button-edit" title="Edit this audio entry">Edit</a><a href="'.ADMIN_URL.'ManageMedia/audio/?audio='.$this->getID().'&delete" class="button-delete" title="Delete this audio entry">Delete</a>'.
				'</td>'.
			'</tr>';
		}
		$display.='</table>';

		return $display;
	} #==== End -- markupManageAudio

	/**
	 * markupLargeAudio
	 *
	 * Returns the HTML markup to display a large audio.
	 *
	 * @param	array $large_audio		The array for the large audio.
	 * @access	public
	 */
	public function markupLargeAudio($large_audio)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		$display=array();

		if(!empty($large_audio))
		{
			# Get the audio ID and assign it to a variable.
			$this->setID($large_audio[0]->id);

			# Decode the `api` field.
			$api_decoded=json_decode($large_audio[0]->api);
			# If the soundcloud_id is in the `api` field then this audio is on Soundcloud.
			if(isset($api_decoded->soundcloud_id))
			{
				# Set the Soundcloud instance to a variable.
				$soundcloud_obj=$this->getSoundcloudObject();
				# Set Soundcloud ID
				$this->setAudioId($api_decoded->soundcloud_id);
				# Create audio_url variable.
				$audio_url=$soundcloud_obj->getSoundCloudUrl().$this->getAudioId();
			}
			else
			{
				$audio_name=$large_audio[0]->file_name;
				# Remove the file extension.
				$file_name_no_ext=substr($audio_name, 0, strrpos($audio_name, '.'));
				# Create audio_url variable.
				$audio_url=AUDIO_URL.'files'.DS.$file_name_no_ext.'.mp3';
			}
			# Create audio URL.
			$this->setAudioUrl($audio_url);

			# Set the availability.
			$this->setAvailability($large_audio[0]->availability);
			# Set the title
			$this->setTitle($db->sanitize($large_audio[0]->title));

			if(isset($api_decoded->soundcloud_thumbnails->medium->url))
			{
				# Set the thumbnail to a variable.
				$image_url=$api_decoded->soundcloud_thumbnails->medium->url;
			}
			else
			{
				# Set the image ID.
				$this->setImageID($large_audio[0]->image);
				# Get the image information from the database, and set them to data members.
				$this->getThisImage($this->getImageID());
				# Set the Image object to a variable.
				$image_obj=$this->getImageObj();
				# Set the image path to a variable.
				$image_path=IMAGES_PATH.$image_obj->getImage();
				# Set the thumbnail to a variable.
				$image_url=$db->sanitize(IMAGES.(file_exists($image_path)===TRUE && $image_obj->getImage()!==NULL ? $image_obj->getImage() : DEFAULT_AUDIO_THUMBNAIL));
			}
			# Set the image path to the data member.
			$this->setThumbnailUrl($image_url);

			# Set the description
			$this->setDescription($db->sanitize($large_audio[0]->description, 5));

			# Set the markup to the display array.
			$display['audio']='<a class="image-link" href="'.$this->getAudioUrl().'" title="Play '.$this->getTitle().'" data-image="'.$this->getThumbnailUrl().'"'.($this->getAvailability()==1 ? '  rel="'.FW_POPUP_HANDLE.'"' : ' target="_blank"').'>'.
				'<img src="'.$this->getThumbnailUrl().'" class="image" alt="Cover for '.$this->getTitle().'"/>'.
				'<span class="play-static"></span>'.
			'</a>';
			$display['title']='<a href="'.AUDIO_URL.'?audio='.$this->getID().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'" target="_blank">'.$this->getTitle().'</a>';
			$display['description']='<p>'.$this->getDescription().'</p>';
		}

		return $display;
	} #==== End -- markupLargeAudio

	/**
	 * markupSmallAudio
	 *
	 * Returns the HTML markup for the small audio.
	 *
	 * @param	array $small_audio			The array for the small audio.
	 * @access	public
	 */
	public function markupSmallAudio($small_audio, $playlist_value=NULL, $exclude_audio=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Small Audio.
		$display='<div class="feed_wrapper-audio">'.
			'<button class="arrow-prev">Previous Audio</button>'.
			'<div class="feed_list-audio">'.
				'<ul class="feed-audio">';

		$playlist_param='';
		# Check if the audio belong to a playlist.
		if($playlist_value!=NULL)
		{
			$playlist_param='playlist='.$playlist_value.'&';
		}

		foreach($small_audio as $audio)
		{
			# Get the audio ID and assign it to a variable.
			$this->setID($audio->id);

			$include_it=TRUE;

			# Check if audio should be excluded.
			if(!empty($exclude_audio) && in_array($this->getID(), $exclude_audio))
			{
				$include_it=FALSE;
			}

			# Check if this specific audio should be included.
			if($include_it===TRUE)
			{
				# Create audio URL.
				$this->setAudioUrl(AUDIO_URL.'?'.$playlist_param.'audio='.$this->getID());
				# Set the title to a variable
				$this->setTitle($db->sanitize($audio->title));

				# Decode the `api` field.
				$api_decoded=json_decode($audio->api);
				if(isset($api_decoded->soundcloud_thumbnails->default->url))
				{
					# Set the thumbnail to a variable.
					$image_url=$api_decoded->soundcloud_thumbnails->default->url;
				}
				else
				{
					# Set the image ID.
					$this->setImageID($audio->image);
					# Get the image information from the database, and set them to data members.
					$this->getThisImage($this->getImageID());
					# Set the Image object to a variable.
					$image_obj=$this->getImageObj();
					# Set the image path to a variable.
					$image_path=IMAGES_PATH.$image_obj->getImage();
					# Set the thumbnail to a variable.
					$image_url=$db->sanitize(IMAGES.(file_exists($image_path)===TRUE && $image_obj->getImage()!==NULL ? $image_obj->getImage() : DEFAULT_AUDIO_THUMBNAIL));
				}
				# Set the image path to the data member.
				$this->setThumbnailUrl($image_url);

				# Set the markup to a variable
				$display.='<li>'.
					'<a href="'.$this->getAudioUrl().'" title="'.$this->getTitle().' on '.DOMAIN_NAME.'">'.
						'<img src="'.$this->getThumbnailUrl().'" alt="Cover for '.$this->getTitle().'" class="thumbnail-small"/>'.
					'</a>'.
				'</li>';
			}
		}

		$display.='</ul>'.
			'</div>'.
			'<button class="arrow-next">Next Audio</button>'.
		'</div>';

		return $display;
	} #==== End -- markupSmallAudio

	/*** End public methods ***/

} # end Audio class