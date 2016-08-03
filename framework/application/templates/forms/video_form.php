<?php /* framework/application/templates/forms/video_form.php */

# Get the video form defaults.
require Utility::locateFile(TEMPLATES.'forms'.DS.'video_form_defaults.php');

$display_delete_form=$form_processor->processVideo($default_data, $max_file_size);

# Set the VideoFormPopulator object from the VideoFormProcessor data member to a variable.
$populator=$form_processor->getPopulator();
# Set the Video object from the FormProcessor data member to a variable.
$video_obj=$populator->getVideoObject();
# Check if the YouTube credentials are available.
if(YOUTUBE_CLIENT_ID!=='')
{
	# Get the YouTube instance. Starts the YouTubeService if it's not already started.
	$yt=$video_obj->getYouTubeObject();
}

$select=TRUE;

if(isset($_GET['create_playlist']))
{
	$display.='<div id="playlist_form" class="form">';

	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';

	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('video', $form_processor->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$form_processor->getUnique()));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="title"><span class="required">*</span> Title</label>');
	$fg->addElement('text', array('name'=>'title', 'id'=>'title', 'value'=>$video_obj->getTitle()));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$button_value='Create Playlist';
	# Check if this is an edit page.
	if(isset($_GET['playlist']))
	{
		$button_value='Update';
	}
	$fg->addElement('submit', array('name'=>'video', 'value'=>$button_value), '', NULL, 'submit-video');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';

	# Get the playlists feed.
	$playlists=$yt->PlaylistsListFeed();

	# Loop through the playlists
	foreach($playlists as $row)
	{
		# Display playlists - Temporary.
		$display.=$row['title'].'<br>';
	}
}
elseif(!isset($_GET['select']))
{
	$head=(!isset($head) ? '' : $head);
	$select=FALSE;

	$duplicates=$form_processor->getDuplicates();
	if(empty($duplicates))
	{
		# Do we need some javascripts? (Use the script video name before the ".js".)
		$doc->setJavaScripts('uniform,bsmSelect');
		# Add JavaScripts to the footer. (Use the script file name before the ".php".)
		# This form needs fileOption-submit, uniform-video, bsmSelect-multiple, uniform-select, video, and (if there is the right GET data) disable-social-checkboxes. uniform-select MUST come after bsmSelect-multiple.
		$doc->setFooterJS('fileOption-submit,uniform-video,bsmSelect-multiple,uniform-select,video'.((!isset($_GET['video'])) ? ',disable-social-checkboxes' : ''));

		# Set the video name to a variable.
		$file_name=$video_obj->getFileName();

		# Check if this is an edit or delete page.
		if(isset($_GET['video']))
		{
			# Set the page's subtitle as an edit page.
			$sub_title='Videos - Edit '.$video_obj->getTitle();
			# Check if this is a delete page.
			if(isset($_GET['delete']))
			{
				# Set the page's subtitle as a delete page.
				$sub_title='Videos - Delete '.$video_obj->getTitle();
			}
			# Set the sub title.
			$main_content->setSubTitle($sub_title);
		}

		$display.='<div id="video_form" class="form">';

		# Create and display form.
		$display.=$head;

		# Add the statement about requirements.
		$display.='<span class="required">* = required field</span>';

		# Create an array to hold the availability options.
		$available_options=array(0=>'This site does not yet have the legal rights to display', 1=>'This site has the legal rights to display', 2=>'Internal document only', 3=>'Can not distribute');
		# Loop through the availability options.
		foreach($available_options as $value=>$option)
		{
			# Check if the POST data equals the index of the current option.
			if($video_obj->getAvailability()==$value)
			{
				# Set the selected availability to the default.
				$availability_options['selected']=$option;
			}
			# Set the option to the options array.
			$availability_options[$value]=$option;
		}

		# Get the Category class.
		require_once Utility::locateFile(MODULES.'Content'.DS.'Category.php');
		# Instantiate a new Category object.
		$category_obj=new Category();
		# get the categories from the `categories` table.
		$category_obj->getCategories(NULL, '`id`, `name`', 'name', 'ASC');
		# Set the categories to a variable.
		$all_categories=$category_obj->getAllCategories();
		# Loop through the categories.
		foreach($all_categories as $row)
		{
			# Create an option for each category.
			$categories[$row->id]=$row->name;
		}
		# Flip the categories.
		$categories=array_flip($categories);
		if(YOUTUBE_CLIENT_ID!=='')
		{
			# Get all the YouTube categories.
			$all_youtube_categories=$yt->listVideoCategories('snippet', array('regionCode'=>'US'));
			# Loop through the YouTube categories.
			foreach($all_youtube_categories['items'] as $option)
			{
				# Set the option to the options array.
				$youtube_categories[$option['id'].'-YouTube']=$option['snippet']['title'];
			}
			# Flip the YouTube categories.
			$youtube_categories_flip=array_flip($youtube_categories);
			# Merge $categories with the YouTube categories.
			$categories=array_merge($youtube_categories_flip, $categories);
		}
		# Set the current categories to a variable.
		$video_categories=(array)$video_obj->getCategories();
		$category_options[]='';
		# Loop through the categories.
		foreach($categories as $category_name=>$category_id)
		{
			# Create an option for each category.
			$category_options[$category_id]=$category_name;
			# Check if this video currently has a category.
			if(!empty($video_categories))
			{
				# Check if the current category is default or has been selected by the user.
				if(in_array($category_name, $video_categories, TRUE)===TRUE)
				{
					# Set the selected category to the default.
					$category_options['selected']=$category_name;
				}
				elseif(
						(in_array('add', $video_categories)===TRUE) &&
						(
							isset($category_options['selected']) &&
							in_array('Add Category', $category_options['selected']!==TRUE)
						)
					)
				{
					# Set the "Add Category" option as selected.
					$category_options['selected']['add']='Add Category';
				}
			}
		}

		$image_options[0]='';
		$image_options['select']='Select Existing Image (submit this form to select an image from the database)';
		$image_options['add']='Upload Image (submit this form to select and upload your image)';
		# Set the image id in the SubContent data member to a variable.
		$image_id=$video_obj->getImageID();
		if(!empty($image_id))
		{
			$image_options['remove']='Remove Current Image (submit this form to remove this image)';
		}

		# Get the Institution class.
		require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
		# Instantiate a new Institution object.
		$institution=new Institution();
		$institution->getInstitutions(NULL, '`id`, `institution`', 'institution', 'ASC');
		$institutions=$institution->getAllInstitutions();
		//$inst_options['add']='Add Institution';
		foreach($institutions as $row)
		{
			$inst_options[$row->id]=$row->institution;
			if($row->institution==$video_obj->getInstitution())
			{
				# Set the selected institution to the default.
				$inst_options['selected']=$row->institution;
			}
			elseif($video_obj->getInstitution()==='add')
			{
				$inst_options['selected']='Add Institution';
			}
		}

		# Get the Language class.
		require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
		# Instantiate a new Language object.
		$language=new Language();
		$language->getLanguages(NULL, '`id`, `language`', 'language', 'ASC');
		$languages=$language->getAllLanguages();
		//$language_options['add']='Add Language';
		foreach($languages as $row)
		{
			$language_options[$row->id]=$row->language;
			if($row->language==$video_obj->getLanguage())
			{
				# Set the selected language to the default.
				$language_options['selected']=$row->language;
			}
			elseif($video_obj->getLanguage()==='add')
			{
				$language_options['selected']='Add Language';
			}
		}

		# Get the Publisher class.
		require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
		# Instantiate a new Publisher object.
		$publisher=new Publisher();
		$publisher->getPublishers(NULL, '`id`, `name`', 'name', 'ASC');
		$publishers=$publisher->getAllPublishers();
		$pub_options[0]='';
		$pub_options['add']='Add Publisher';
		if(!empty($publishers))
		{
			foreach($publishers as $row)
			{
				$pub_options[$row->id]=$row->name;
				if($row->name==$video_obj->getPublisher())
				{
					# Set the selected publisher to the default.
					$pub_options['selected']=$row->name;
				}
				elseif($video_obj->getPublisher()==='add')
				{
					$pub_options['selected']='Add Publisher';
				}
			}
		}

		# Get the Playlist class.
		require_once Utility::locateFile(MODULES.'Content'.DS.'Playlist.php');
		# Instantiate a new Playlist object.
		$playlist_obj=Playlist::getInstance();
		$where=NULL;
		# Decode the returned API JSON.
		$content_api_decode=json_decode($main_content->getAPI(), TRUE);
		# Asign playlists to a variable.
		$content_playlists=$content_api_decode['Site']['Playlists'];
		if($content_playlists!==NULL)
		{
			# Creates the SQL from an array of Playlist IDs.
			$playlist_obj->createWhereSQL($content_playlists);
			$where=' WHERE '.$playlist_obj->getWhereSQL();
		}
		# Get the playlists from the `playlists` table.
		$playlist_obj->getPlaylists(NULL, '`id`, `name`, `api`', 'name', 'ASC', $where);
		# Set the playlists to a variable.
		$playlists=$playlist_obj->getAllPlaylists();
		$playlist_options['add']='Add Playlist';
		# If there are playlist results.
		if(!empty($playlists))
		{
			# Set the current playlists to a variable.
			$video_playlists=array_flip((array)$video_obj->getPlaylists());
			# Loop through the playlists.
			foreach($playlists as $row)
			{
				# Create an option for each playlist.
				$playlist_options[$row->id]=$row->name;
				# Check if this video currently has a playlist.
				if(!empty($video_playlists))
				{
					# Check if the current playlist is default or has been selected by the user.
					if(in_array($row->id, $video_playlists)===TRUE)
					{
						# Set the selected playlist to the default.
						$playlist_options['multiple_selected'][$row->id]=$row->name;
					}
					elseif(
							(in_array('add', $video_playlists)===TRUE) &&
							(
								isset($playlist_options['multiple_selected']) &&
								in_array('Add Playlist', $playlist_options['multiple_selected']!==TRUE)
							)
						)
					{
						# Set the "Add Playlist" option as selected.
						$playlist_options['multiple_selected']['add']='Add Playlist';
					}
				}
			}
		}

		# Get the publish year from the Video data member.
		$video_year=$video_obj->getYear();
		# Check if the publish year value is empty.
		if(empty($video_year) OR ($video_year=='0000'))
		{
			# Reset the value to "Unknown".
			$video_year='Unknown';
		}
		# Set the selected year to the options array and create the "Unknown" option.
		$select_video_year=array('selected'=>$video_year, 'unknown'=>'Unknown');

		# Instantiate a new FormGenerator object.
		$fg=new FormGenerator('video', $form_processor->getFormAction(), 'POST', '_top', TRUE);
		$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
		$fg->addElement('hidden', array('name'=>'MAX_FILE_SIZE', 'value'=>$max_file_size));
		$fg->addElement('hidden', array('name'=>'_contributor', 'value'=>$video_obj->getContID()));
		$fg->addFormPart('<fieldset>');
		$fg->addFormPart('<ul>');
		$fg->addFormPart('<li class="date">');
		# Get the dat from the SubContent data member.
		$date=$video_obj->getDate();
		# Create an empty variable for date comment.
		$date_comment='';
		# Check if the date is unknown (0000-00-00).
		if($date=='0000-00-00')
		{
			# Set the date to the default "impossible" date.
			$date='1970-02-31';
			# Set the date comment.
			$date_comment='<span class="comment">(Upload date unknown)</span>';
		}
		$fg->addFormPart('<label class="label">The date of this uploading'.$date_comment.'</label>');
		# Explode the date into an array of month/day/year.
		$date=explode('-', $date);
		$fg->addFormPart('<span class="month">');
		# Set the month to a variable.
		$month=$date[1];
		$select_month=array('selected'=>date('F', mktime(0, 0, 0, $month, 1, 1970)));
		$fg->addElement('select', array('name'=>'month'), $select_month, NULL, 'month select');
		$fg->addFormPart('</span>');
		$fg->addFormPart('<span class="day">');
		# Set the day to a variable.
		$day=$date[2];
		$select_day=array('selected'=>$day);
		$fg->addElement('select', array('name'=>'day'), $select_day, NULL, 'day select');
		$fg->addFormPart('</span>');
		$fg->addFormPart('<span class="year">');
		# Set the year to a variable.
		$year=$date[0];
		$select_year=array('selected'=>$year);
		$fg->addElement('select', array('name'=>'year'), $select_year, NULL, 'year select');
		$fg->addFormPart('</span>');
		$fg->addFormPart('</li>');
		# Check if there is GET data and it is for the video.
		if(!isset($_GET['video']))
		{
			if(
				FB_APP_ID!="" &&
				FB_APP_SECRET!='' &&
				FB_PAGE_ID!='' &&
				FB_PAGE_ACCESS_TOKEN!=''
				)
			{
				$fg->addFormPart('<li>');
				# Get whether or not the post should be posted to Facebook from the data member and set it to a variable.
				$facebook=$populator->getFacebook();
				# Make the Facebook value digestible to the form.
				$facebook=(($facebook===NULL) ? '' : 'post');
				$fg->addFormPart('<label class="label" for="facebook">Post on <span class="facebook" title="Facebook">Facebook</span></label>');
				$fg->addElement('checkbox', array('name'=>'facebook', 'value'=>'post', 'id'=>'facebook', 'checked'=>$facebook, 'title'=>'Post on Facebook'));
				$fg->addFormPart('</li>');
			}
			if(
				TWITTER_CONSUMER_KEY!='' &&
				TWITTER_CONSUMER_SECRET!='' &&
				TWITTER_TOKEN!='' &&
				TWITTER_TOKEN_SECRET!=''
				)
			{
				$fg->addFormPart('<li>');
				# Get whether or not the post should be posted to Twitter from the data member and set it to a variable.
				$twitter=$populator->getTwitter();
				# Make the Twitter value digestible to the form.
				$twitter=(($twitter===NULL) ? '' : 'tweet');
				$fg->addFormPart('<label class="label" for="twitter">Tweet on <span class="twitter" title="Twitter">Twitter</span></label>');
				$fg->addElement('checkbox', array('name'=>'twitter', 'value'=>'tweet', 'id'=>'twitter', 'checked'=>$twitter, 'title'=>'Tweet on Twitter'));
				$fg->addFormPart('</li>');
			}
		}
		if(
			YOUTUBE_CLIENT_ID!="" &&
			YOUTUBE_CLIENT_SECRET!='' &&
			YOUTUBE_DEV_KEY!='' &&
			YOUTUBE_REFRESH_TOKEN!=''
		)
		{
			$fg->addFormPart('<li>');
			# Get whether or not the post should be posted to YouTube from the data member and set it to a variable.
			$youtube=$populator->getYouTube();
			# Make the YouTube value digestible to the form.
			$youtube=(($youtube===NULL) ? '' : TRUE);
			$fg->addFormPart('<label class="label" for="youtube">'.(!isset($_GET['video']) ? 'Post' : 'Edit').' on <span class="youtube" title="YouTube">YouTube</span></label>');
			$fg->addElement('checkbox', array('name'=>'youtube', 'value'=>'post_youtube', 'id'=>'youtube', 'checked'=>$youtube, 'title'=>'Post on YouTube'));
			$fg->addFormPart('</li>');
		}
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="title"><span class="required">*</span> Title</label>');
		$fg->addElement('text', array('name'=>'title', 'id'=>'title', 'value'=>$video_obj->getTitle()));
		$fg->addFormPart('</li>');
		# Check if there is GET data. If there is, it's a video edit, and don't show this part of the form.
		if(!isset($_GET['video']))
		{
			$fg->addFormPart('<li class="vis">');
			$fg->addFormPart('<span class="label">Video Type</span>');
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li>');
			$fg->addElement('radio', array('name'=>'video-type', 'id'=>'video-type-file', 'value'=>'file', 'checked'=>$video_obj->getVideoType()), NULL, NULL, 'radio video_type_radio');
			$fg->addFormPart('<label class="label-radio" for="video-type-file">File</label>');
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addElement('radio', array('name'=>'video-type', 'id'=>'video-type-embed', 'value'=>'embed', 'checked'=>$video_obj->getVideoType()), NULL, NULL, 'radio video_type_radio');
			$fg->addFormPart('<label class="label-radio" for="video-type-embed">Embed</label>');
			$fg->addFormPart('</li>');
			$fg->addFormPart('</ul>');
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li id="file">');
			$fg->addFormPart('<label class="label" for="video"><span class="required">*</span> Video</label>');
			$fg->addElement('file', array('name'=>'video', 'id'=>'video'));
			if(!empty($file_name))
			{
				# Get the image information from the database, and set them to data members.
				$video_obj->getThisImage($video_obj->getImageID());
				# Set the Image object to a variable.
				$image_obj=$video_obj->getImageObj();
				# Set the thumbnail to a variable.
				$video_obj->setThumbnailUrl($db->sanitize(IMAGES.$image_obj->getImage()));
				$fg->addFormPart('<ul>');
				$fg->addFormPart('<li class="file-current">');
				if($video_obj->getAPI()!==NULL)
				{
					# Decode the `api` field.
					$api_decoded=json_decode($video_obj->getAPI());
					# Set YouTube ID
					$video_obj->setVideoId($api_decoded->youtube_id);
					# Create video URL.
					$video_obj->setVideoUrl($yt->getYoutubeUrl().$video_obj->getVideoId());
					$fg->addFormPart('<a href="'.$video_obj->getVideoUrl().'" title="Current Video" rel="'.FW_POPUP_HANDLE.'"><img src="'.$video_obj->getThumbnailUrl().'" alt="Poster for '.$video_obj->getTitle().'"/><span>'.$file_name.' - "'.$video_obj->getTitle().'"</span></a>');
				}
				else
				{
					# NOTE: This video is not in a public directory.
					//$fg->addFormPart('<a href="'.APPLICATION_URL.'videos/files/'.$file_name.'" title="Current Video" rel="'.FW_POPUP_HANDLE.'" data-image="'.$video_obj->getThumbnailUrl().'"><img class="image" src="'.$video_obj->getThumbnailUrl().'" alt="Cover for '.$video_obj->getTitle().'"/><span>'.$file_name.' - "'.$video_obj->getTitle().'"</span></a>');
					$fg->addFormPart('<span>'.$file_name.' - "'.$video_obj->getTitle().'"</span>');
				}
				$fg->addElement('hidden', array('name'=>'_video', 'value'=>$file_name));
				$fg->addFormPart('</li>');
				$fg->addFormPart('</ul>');
			}
			$fg->addFormPart('</li>');
		}
		# Check if there is GET data. If there is, it's a video edit, and don't show this part of the form.
		if(!isset($_GET['video']) || $video_obj->getVideoType()!='file')
		{
			# Set the video type to a variable.
			$video_type=$video_obj->getVideoType();
			# Style the list element if this is not an empty form or if this edited video is an embed video.
			$embed_style=((!empty($video_type) || $video_obj->getVideoType()=='embed') ? ' id="embed"' : '');
			$fg->addFormPart('<li'.$embed_style.'>');
			$fg->addFormPart('<label class="label" for="embed_code"><span class="required">*</span> Embed</label>');
			$fg->addElement('text', array('name'=>'embed_code', 'id'=>'embed_code', 'value'=>htmlspecialchars($video_obj->getEmbedCode())));
			$fg->addFormPart('</li>');
		}
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="imageOption">Thumbnail</label>');
		$fg->addElement('select', array('name'=>'image_option', 'id'=>'imageOption'), $image_options, NULL, 'select');
		if(!empty($image_id))
		{
			# Get the file info.
			$video_obj->getThisImage($image_id);
			# Set the Image object to a variable.
			$image_obj=$video_obj->getImageObj();
			$image_name=$image_obj->getImage();
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li class="file-current">');
			$fg->addFormPart('<a href="'.IMAGES.'original/'.$image_name.'" title="Current Image" class="image-link" rel="'.FW_POPUP_HANDLE.'"><img src="'.IMAGES.$image_name.'" class="image" alt="'.$image_obj->getTitle().'"/><span>'.$image_name.' - "'.$image_obj->getTitle().'"</span></a>');
			$fg->addElement('hidden', array('name'=>'_image_id', 'value'=>$image_id));
			$fg->addFormPart('</li>');
			$fg->addFormPart('</ul>');
		}
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="availability">Availability</label>');
		$fg->addElement('select', array('name'=>'availability', 'id'=>'availability'), $availability_options);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="language">Language</label>');
		$fg->addElement('select', array('name'=>'language', 'id'=>'language'), $language_options);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="author"><span class="required">*</span> Author</label>');
		$fg->addElement('text', array('name'=>'author', 'id'=>'author', 'value'=>$video_obj->getAuthor()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="video_year">Publish Year</label>');
		$fg->addElement('select', array('name'=>'video_year', 'id'=>'video_year'), $select_video_year);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="description">Description</label>');
		$fg->addElement('textarea', array('name'=>'description', 'id'=>'description', 'text'=>$video_obj->getDescription()), '', NULL, 'textarea');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="category"><span class="required">*</span> Category</label>');
		$fg->addElement('select', array('name'=>'category[]', 'id'=>'category'), $category_options);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li class="mult">');
		$fg->addFormPart('<label class="label" for="playlist"> Playlist</label>');
		$fg->addElement('select', array('name'=>'playlist[]', 'multiple'=>'multiple', 'title'=>'Select a Playlist', 'id'=>'playlist'), $playlist_options);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="institution">Institution</label>');
		$fg->addElement('select', array('name'=>'institution', 'id'=>'institution'), $inst_options);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="publisher">Publisher</label>');
		$fg->addElement('select', array('name'=>'publisher', 'id'=>'publisher'), $pub_options);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$button_value='Add Video';
		# Check if this is an edit page.
		if(isset($_GET['video']))
		{
			$button_value='Update';
		}
		$fg->addElement('submit', array('name'=>'video', 'value'=>$button_value), '', NULL, 'submit-video');
		# Check if this is an edit page.
		if(isset($_GET['video']) && !isset($_GET['delete']))
		{
			$fg->addFormPart('<a href="'.ADMIN_URL.'ManageMedia/videos/?video='.$video_obj->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
		}
		$fg->addElement('submit', array('name'=>'video', 'value'=>'Reset'), '', NULL, 'submit-reset');
		$fg->addFormPart('</li>');
		$fg->addFormPart('</ul>');
		$fg->addFormPart('</fieldset>');
		$display.=$fg->display();
		$display.='</div>';
		if(isset($_GET['video']))
		{
			# Display pages using this video. Acceptable parameter is 'audio', 'file', 'image', or 'video'.
			$display.=$video_obj->displayMediaUsage('video');
		}
		# Display the videos in the `videos` table.
		$display.=$video_obj->displayVideoFeed();
	}
	else
	{
		# Set the page's sub title.
		$sub_title='Duplicate Content';
		# Set the sub title.
		$main_content->setSubTitle($sub_title);
		$display.='<h3 class="h-3">The following video(s) seem to closely resemble the video you are submitting. If you feel your video is unique and would like to continue uploading it, simply click on the "Back" button below. Conversely, you may choose to edit an existing video or click <a href="'.APPLICATION_URL.WebUtility::removeIndex(HERE).str_replace(GET_QUERY, '', GET_QUERY).'">here</a> to continue without uploading.</h3>';

		# Instantiate a new formGenerator object.
		$fg=new formGenerator('back_button');
		# Add a hidden input called '_submit_check' to the form.
		$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		# Add a hidden input called '_unique' to the form.
		$fg->addElement('hidden', array('name'=>'_unique', 'value'=>'1'));
		# Add the button to the form.
		$fg->addElement('submit', array('name'=>'video', 'value'=>'Back to the form!'), '', NULL, 'submit-back');
		# Concatenate the "back button" to the duplicates to be displayed.
		$display.=$fg->display();

		# Convert the multidimensional array to an object.
		$duplicates=json_decode(json_encode($duplicates));
		# Display the results.
		$display.=$video_obj->markupManageVideos($duplicates);

		# Concatenate the Back button to the duplicates to be displayed.
		$display.=$fg->display();
	}
}
$display=$display_delete_form.$display;