<?php /* templates/forms/video_form.php */

require TEMPLATES.'forms'.DS.'video_form_defaults.php';
$display_delete_form=$form_processor->processVideo($default_data);

# Set the VideoFormPopulator object from the VideoFormProcessor data member to a variable.
$populator=$form_processor->getPopulator();
# Set the Video object from the FormProcessor data member to a variable.
$video_obj=$populator->getVideoObject();
# Get the YouTube instance. Starts the YouTubeService if it's not already started.
$yt=$video_obj->getYouTubeObject();

$select=TRUE;

if(isset($_GET['create_playlist']))
{
	$display.='<div id="file_form" class="form">';

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
	$head='';
	$select=FALSE;

	$duplicates=$form_processor->getDuplicates();
	if(empty($duplicates))
	{
		# Do we need some javascripts? (Use the script video name before the ".js".)
		$doc->setJavaScripts('uniform,bsmSelect,video');
		# Do we need some JavaScripts in the footer? (Use the script video name before the ".php".)
		$doc->setFooterJS('uniform-select,fileOption-submit,uniform-video,bsmSelect-multiple'.((!isset($_GET['video'])) ? ',disable-social-checkboxes' : ''));

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
		}

		$display.='<div id="file_form" class="form">';

		# create and display form.
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

		# Get all the YouTube categories.
		$youtube_category_options=$yt->listVideoCategories('snippet', array('regionCode'=>'US'));
		# Loop through the YouTube categories.
		foreach($youtube_category_options['items'] as $option)
		{
			# Check if the POST data equals the index of the current option.
			if($video_obj->getCategory()==$option['id'])
			{
				# Set the selected category to the default.
				$category_options['selected']=$option['snippet']['title'];
			}
			# Set the option to the options array.
			$category_options[$option['id']]=$option['snippet']['title'];
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
		require_once MODULES.'Content'.DS.'Institution.php';
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
		require_once MODULES.'Content'.DS.'Language.php';
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

		# Get the publish year from the Video data member.
		$video_year=$video_obj->getYear();
		# Check if the publish year value is empty.
		if(empty($video_year))
		{
			# Reset the value to "Unknown".
			$video_year='Unknown';
		}
		# Set the selected year to the options array and create the "Unknown" option.
		$select_video_year=array('selected'=>$video_year, 'unknown'=>'Unknown');

		# Get the Publisher class.
		require_once MODULES.'Content'.DS.'Publisher.php';
		# Instantiate a new Publisher object.
		$publisher=new Publisher();
		$publisher->getPublishers(NULL, '`id`, `name`', 'name', 'ASC');
		$publishers=$publisher->getAllPublishers();
		$pub_options[0]='';
		$pub_options['add']='Add Publisher';
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

		# Get the Category class.
		require_once MODULES.'Content'.DS.'Category.php';
		# Instantiate a new Category object.
		$playlist=new Category();
		# get the categories from the `categories` table.
		$playlist->getCategories(NULL, '`id`, `category`', 'category', 'ASC', ' WHERE `product` IS NULL AND `api` IS NOT NULL');
		# Set the playlists to a variable.
		$playlists=$playlist->getAllCategories();
		# If there are playlist results.
		if(!empty($playlists))
		{
			# Create the "Add Playlist" option.
			//$playlist_options['add']='Add Playlist';
			# Set the current playlists to a variable.
			$video_playlists=array_flip((array)$video_obj->getPlaylists());
			foreach($playlists as $row)
			{
				# Create an option for each playlist.
				$playlist_options[$row->id]=$row->category;
				# Check if this video currently has a playlist.
				if(!empty($video_playlists))
				{
					# Check if the current playlist is default or has been selected by the user.
					if(in_array($row->id, $video_playlists)===TRUE)
					{
						# Set the selected playlist to the default.
						$playlist_options['multiple_selected'][$row->id]=$row->category;
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
		else
		{
			$playlist_options[]='No Playlists';
		}

		# Instantiate a new FormGenerator object.
		$fg=new FormGenerator('video', $form_processor->getFormAction(), 'POST', '_top', TRUE);
		$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
		$fg->addElement('hidden', array('name'=>'MAX_FILE_SIZE', 'value'=>((isset($max_file_size)) ? $max_file_size : 314572800)));
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
			# Set the date to the defaul "impossible" date.
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
			$fg->addFormPart('<li>');
			# Get whether or not the post should be posted to Facebook from the data member and set it to a variable.
			$facebook=$populator->getFacebook();
			# Make the Facebook value digestible to the form.
			$facebook=(($facebook===NULL) ? '' : 'post');
			$fg->addFormPart('<label class="label" for="facebook">Post on <span class="facebook" title="Facebook">Facebook</span></label>');
			$fg->addElement('checkbox', array('name'=>'facebook', 'value'=>'post', 'id'=>'facebook', 'checked'=>$facebook, 'title'=>'Post on Facebook'));
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			# Get whether or not the post should be posted to Twitter from the data member and set it to a variable.
			$twitter=$populator->getTwitter();
			# Make the Twitter value digestible to the form.
			$twitter=(($twitter===NULL) ? '' : 'tweet');
			$fg->addFormPart('<label class="label" for="twitter">Tweet on <span class="twitter" title="Twitter">Twitter</span></label>');
			$fg->addElement('checkbox', array('name'=>'twitter', 'value'=>'tweet', 'id'=>'twitter', 'checked'=>$twitter, 'title'=>'Tweet on Twitter'));
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
				$video_obj->setThumbnailUrl($db->sanitize(IMAGES.'videos/'.$image_obj->getImage()));

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

					$fg->addFormPart('<a href="'.$video_obj->getVideoUrl().'" title="Current Video" rel="lightbox"><img src="'.$video_obj->getThumbnailUrl().'" alt="'.$video_obj->getTitle().' poster" /><span>'.$file_name.' - "'.$video_obj->getTitle().'"</span></a>');
				}
				else
				{
					# DRAVEN: This video is not in a public directory.
					//$fg->addFormPart('<a href="'.APPLICATION_URL.'videos/files/'.$file_name.'" title="Current Video" rel="lightbox"><span>'.$file_name.' - "'.$video_obj->getTitle().'"</span></a>');
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
			$fg->addFormPart('<a href="'.IMAGES.'original/'.$image_name.'" title="Current Image" rel="lightbox"><img src="'.IMAGES.$image_name.'" alt="'.$image_obj->getTitle().'" /><span>'.$image_name.' - "'.$image_obj->getTitle().'"</span></a>');
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
		$fg->addElement('textarea', array('name'=>'description', 'id'=>'description', 'wrap'=>'physical', 'text'=>$video_obj->getDescription()), '', NULL, 'textarea');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="category"><span class="required">*</span> YouTube Category</label>');
		$fg->addElement('select', array('name'=>'category', 'id'=>'category'), $category_options);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li class="mult">');
		$fg->addFormPart('<label class="label" for="playlist"><span class="required">*</span> Playlist</label>');
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
	}
	$display.=$video_obj->displayVideoFeed();
}

$display=$display_delete_form.$display;