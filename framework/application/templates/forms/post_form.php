<?php /* framework/application/templates/forms/post_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'post_form_defaults.php');
$delete_form_display=$fp->processPost($default_data);

# Set the PostFormPopulator object from the PostFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the SubContent object from the PostFormPopulator data member to a variable.
$sc_object=$populator->getSubContentObject();
# Do we need some javascripts? (Use the script file name before the ".js".)
$doc->setJavaScripts('uniform,bsmSelect');
# Add JavaScripts to the footer. (Use the script file name before the ".php".)
# This form needs fileOption-submit, bsmSelect-multiple, uniform-select, and (if there is the right GET data) disable-social-checkboxes. uniform-select MUST come after bsmSelect-multiple.
$doc->setFooterJS('fileOption-submit,bsmSelect-multiple,uniform-select'.((!isset($_GET['post'])) ? ',disable-social-checkboxes' : ''));

$duplicates=$fp->getDuplicates();

if(empty($duplicates))
{
	if(isset($_GET['delete']))
	{
		# Set the page's sub title.
		$sub_title='Delete Content';

		if(isset($_GET['post']))
		{
			# Set the page's sub title for editing this post.
			$sub_title='Delete <span>"'.$sc_object->getTitle().'"</span>';
		}
	}
	else
	{
		# Set the page's sub title.
		$sub_title='New Post';

		if(isset($_GET['edit']))
		{
			# Set the page's sub title.
			$sub_title='Edit Content';
		}

		if(isset($_GET['post']))
		{
			# Set the page's sub title for editing this post.
			$sub_title='Edit <span>"'.$sc_object->getTitle().'"</span>';
			$head=$post_edit_head;
			$general_edit_head='';
		}

		if(!isset($_GET['edit']) OR (isset($_GET['edit']) && isset($_GET['post'])))
		{
			$general_edit_head='';

			$post_form_display='<div id="post_form" class="form">';

			# Create and display form.
			$post_form_display.='<h3 class="h-3">'.$head.'</h3>';

			# Add the statement about requirements.
			$post_form_display.='<span class="required">* = required field</span>';

			# Create an array to hold the available availability options.
			$available_options=array(0=>'This site does not yet have the legal rights to display', 1=>'This site has the legal rights to display', 2=>'Internal document only', 3=>'Can not distribute');
			# Loop through the available availability options.
			foreach($available_options as $value=>$option)
			{
				# Check if the POST data equals the index of the current option.
				if($sc_object->getAvailability()==$value)
				{
					# Set the selected availability to the default.
					$availability_options['selected']=$option;
				}
				# Set the option to the options array.
				$availability_options[$value]=$option;
			}

			# Set the record's branches from the SubContent data member to a variable.
			$record_branches=$sc_object->getRecordBranches();
			# Trim the dashes off the ends.
			$record_branches=trim($record_branches, '-');
			# Explode the branches into an array.
			$record_branches=explode('-', $record_branches);
			# Create an empty array to hold the record's branches.
			$the_branches=array();
			# Loop through the record branches.
			foreach($record_branches as $record_branch)
			{
				# Get the branch names from the `branches` table.
				$branch->getThisBranch($record_branch);
				# Set the branch id and name to the array.
				$the_branches[$record_branch]=$branch->getBranch();
			}
			# Loop through the returned branch rows.
			foreach($branch->getAllBranches() as $row)
			{
				# Check if the user has access to this branch.
				if($login->checkAccess(constant(str_replace(' ', '_', strtoupper($row->branch)).'_USERS'))===TRUE)
				{
					# Create the branch name variable.
					$branch_name_variable=str_replace(' ', '_', strtolower($row->branch));
					# Create an option for each branch.
					$branch_options[$row->id]=$row->branch;
					# Check if this branch is in the $the_branches array. If so, set TRUE to the $checked_branch variable, otherwise set FALSE.
					$checked_branch=in_array($row->branch, $the_branches);
					# Check if the current branch is default or has been selected by the user.
					if(in_array($row->branch, $the_branches)===TRUE)
					{
						# Set the selected branches to the default.
						$branch_options['multiple_selected'][$row->id]=$row->branch;
					}
				}
			}

			$file_options[0]='';
			$file_options['select']='Select Existing File (submit this form to select a file from the database)';
			$file_options['add']='Upload File (submit this form to select and upload your file)';
			# Set the file id in the SubContent data member to a variable.
			$file_id=$sc_object->getFileID();
			if(!empty($file_id))
			{
				$file_options['remove']='Remove Current File (submit this form to remove this file)';
			}

			$image_options[0]='';
			$image_options['select']='Select Existing Image (submit this form to select an image from the database)';
			$image_options['add']='Upload Image (submit this form to select and upload your image)';
			# Set the image id in the SubContent data member to a variable.
			$image_id=$sc_object->getImageID();
			if(!empty($image_id))
			{
				$image_options['remove']='Remove Current Image (submit this form to remove this image)';
			}

			$sc_object->getInstitutions(NULL, '`id`, `institution`', 'institution', 'ASC');
			$institutions=$sc_object->getAllInstitutions();
			$inst_options['add']='Add Institution';
			foreach($institutions as $row)
			{
				$inst_options[$row->id]=$row->institution;
				if($row->id==$sc_object->getInstitutionID())
				{
					# Set the selected institution to the default.
					$inst_options['selected']=$row->institution;
				}
			}
			if($sc_object->getInstitutionID()==='add')
			{
				$inst_options['selected']='Add Institution';
			}

			$sc_object->getLanguages(NULL, '`id`, `language`', 'language', 'ASC');
			$languages=$sc_object->getAllLanguages();
			$language_options['add']='Add Language';
			foreach($languages as $row)
			{
				$language_options[$row->id]=$row->language;
				if(($row->id==$sc_object->getTextLanguage()) OR ($row->language==$sc_object->getTextLanguage()))
				{
					$text_language=$row->language;
				}
				if(($row->id==$sc_object->getTransLanguage()) OR ($row->language==$sc_object->getTransLanguage()))
				{
					$trans_language=$row->language;
				}
			}
			if($sc_object->getTextLanguage()==='add')
			{
				$text_language='Add Language';
			}
			if($sc_object->getTransLanguage()==='add')
			{
				$trans_language='Add Language';
			}

			$premium=$sc_object->getPremium();
			if($premium===0)
			{
				$premium='premium';
			}
			else
			{
				$premium='';
			}

			$sc_object->getPublishers(NULL, '`id`, `name`', 'name', 'ASC');
			$publishers=$sc_object->getAllPublishers();
			$pub_options[0]='';
			$pub_options['add']='Add Publisher';
			if(!empty($publishers))
			{
				foreach($publishers as $row)
				{
					$pub_options[$row->id]=$row->name;
					if($row->id==$sc_object->getPublisherID())
					{
						# Set the selected institution to the default.
						$pub_options['selected']=$row->name;
					}
				}
			}
			if($sc_object->getPublisherID()==='add')
			{
				$pub_options['selected']='Add Publisher';
			}

			$visibility=$sc_object->getVisibility();
			if($visibility===NULL)
			{
				$visibility='all_users';
			}
			elseif($visibility===0)
			{
				$visibility='members';
			}
			else
			{
				$visibility=trim($visibility, '-');
				$visibility=explode('-', $visibility);
				if(in_array($branch->getID(), $visibility))
				{
					$visibility=$branch->getID();
				}
				else
				{
					$visibility='';
				}
			}

			# Instantiate a new FormGenerator object.
			$fg=new FormGenerator('post', $fp->getFormAction());
			$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
			$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
			$fg->addElement('hidden', array('name'=>'_contributor', 'value'=>$sc_object->getContID()));
			$fg->addFormPart('<fieldset>');
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li class="date">');
			$fg->addFormPart('<label class="label">The date of this posting</label>');
			# Get the dat from the SubContent data member.
			$date=$sc_object->getDate();
			# Check if the date is unknown (0000-00-00).
			if($date=='0000-00-00')
			{
				# Set the date to the default "impossible" date.
				$date='1970-02-31';
			}
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
			$fg->addFormPart('<li>');
			# Get whether or not the post should be hidden from the SubContent data member and set it to a variable.
			$hide=$sc_object->getHide();
			# Make the hide value digestible to the form.
			$hide=(($hide===NULL) ? '' : 'hide');
			$fg->addFormPart('<label class="label" for="hide">Hide Event</label>');
			$fg->addElement('checkbox', array('name'=>'hide', 'value'=>'hide', 'id'=>'hide', 'checked'=>$hide));
			$fg->addFormPart('</li>');
			# Check if there is GET data and it is for the post.
			if(!isset($_GET['post']))
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
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="title"><span class="required">*</span> Title</label>');
			$fg->addElement('text', array('name'=>'title', 'value'=>$sc_object->getTitle(), 'id'=>'title'));
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="link">Link to Source</label>');
			# Set the link to a variable.
			$link=$sc_object->getLink();
			$fg->addElement('text', array('name'=>'link', 'value'=>((empty($link)) ? 'http://' : $link), 'id'=>'link'));
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="fileOption">Document</label>');
			$fg->addElement('select', array('name'=>'file_option', 'id'=>'fileOption'), $file_options, NULL, 'select');
			if(!empty($file_id))
			{
				# Get the file info.
				$sc_object->getThisFile($file_id);
				# Set the File object to a variable.
				$file=$sc_object->getFile();
				$fg->addFormPart('<ul>');
				$fg->addFormPart('<li class="file-current">');
				$fg->addFormPart('<a href="'.APPLICATION_URL.'download/?f='.$file->getFile().(($file->getPremium()!==NULL) ? '&amp;t=premium' : '').'" title="Current Associated File">'.$file->getFile().' - "'.$file->getTitle().'"</a>');
				$fg->addElement('hidden', array('name'=>'_file_id', 'value'=>$file_id));
				$fg->addFormPart('</li>');
				$fg->addFormPart('</ul>');
			}
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li class="mult">');
			$fg->addFormPart('<label class="label" for="branches"><span class="required">*</span> Post On</label>');
			$fg->addElement('select', array('name'=>'branch[]', 'multiple'=>'multiple', 'title'=>'Select a Branch', 'id'=>'branches'), $branch_options);
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="premium">Subscription Only Content</label>');
			$fg->addElement('checkbox', array('name'=>'premium', 'id'=>'premium', 'value'=>'premium', 'checked'=>$premium));
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="availability">Availability</label>');
			$fg->addElement('select', array('name'=>'availability', 'id'=>'availability'), $availability_options);
			$fg->addFormPart('</li>');
			if($login->checkAccess(constant(str_replace(' ', '_', strtoupper($branch_name)).'_USERS'))===TRUE):
			$fg->addFormPart('<li class="vis">');
			$fg->addFormPart('<span class="label">Visibility</span>');
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li>');
			$fg->addElement('radio', array('name'=>'visibility', 'id'=>'visibility-members', 'value'=>'members', 'checked'=>$visibility));
			$fg->addFormPart('<label class="label-radio" for="visibility-members">'.DOMAIN_NAME.' Members Only</label>');
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addElement('radio', array('name'=>'visibility', 'id'=>'visibility-all_users', 'value'=>'all_users', 'checked'=>$visibility));
			$fg->addFormPart('<label class="label-radio" for="visibility-all_users">All Users</label>');
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addElement('radio', array('name'=>'visibility', 'id'=>'visibility-'.str_replace(' ', '_', strtolower($branch_name)), 'value'=>$branch->getID(), 'checked'=>$visibility));
			$fg->addFormPart('<label class="label-radio" for="visibility-'.str_replace(' ', '_', strtolower($branch_name)).'">'.$branch_name.' Users Only</label>');
			$fg->addFormPart('</li>');
			$fg->addFormPart('</ul>');
			$fg->addFormPart('</li>');
			endif;
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="institution">Institution</label>');
			$fg->addElement('select', array('name'=>'institution', 'id'=>'institution'), $inst_options);
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="publisher">Publisher</label>');
			$fg->addElement('select', array('name'=>'publisher', 'id'=>'publisher'), $pub_options);
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="text">Text</label>');
			$fg->addElement('textarea', array('name'=>'text', 'id'=>'text', 'text'=>$sc_object->getText()), '', NULL, 'textarea tinymce');
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="text_language">Text Language</label>');
			# Set the selected text language to the default.
			$language_options['selected']=$text_language;
			$fg->addElement('select', array('name'=>'text_language', 'id'=>'text_language'), $language_options, NULL, 'select');
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="text_trans">Text Translation</label>');
			$fg->addElement('textarea', array('name'=>'text_trans', 'id'=>'text_trans', 'text'=>$sc_object->getTextTrans()), '', NULL, 'textarea tinymce');
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="trans_language">Translation Language</label>');
			# Set the selected translation language to the default.
			$language_options['selected']=$trans_language;
			$fg->addElement('select', array('name'=>'trans_language', 'id'=>'trans_language'), $language_options, NULL, 'select');
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="imageOption">Image</label>');
			$fg->addElement('select', array('name'=>'image_option', 'id'=>'imageOption'), $image_options, NULL, 'select');
			if(!empty($image_id))
			{
				# Get the file info.
				$sc_object->getThisImage($image_id);
				# Set the File object to a variable.
				$image_obj=$sc_object->getImageObj();
				$image_name=$image_obj->getImage();
				$fg->addFormPart('<ul>');
				$fg->addFormPart('<li class="file-current">');
				$fg->addFormPart('<a href="'.IMAGES.'original/'.$image_name.'" title="Current Image" rel="'.FW_POPUP_HANDLE.'"><img src="'.IMAGES.$image_name.'" alt="'.$image_obj->getTitle().'" /><span>'.$image_name.' - "'.$image_obj->getTitle().'"</span></a>');
				$fg->addElement('hidden', array('name'=>'_image_id', 'value'=>$image_id));
				$fg->addFormPart('</li>');
				$fg->addFormPart('</ul>');
			}
			$fg->addFormPart('</li>');
			$fg->addFormPart('<li>');
			$button_value='Post';
			if(isset($_GET['post']))
			{
				# Set the button's value to "update".
				$button_value='Update';
			}
			$fg->addElement('submit', array('name'=>'post', 'value'=>$button_value), '', NULL, 'submit-post');
			# Check if this is an edit page.
			if(isset($_GET['post']) && !isset($_GET['delete']))
			{
				$fg->addFormPart('<a href="'.SECURE_URL.SECURE_HERE.'?delete&amp;post='.$sc_object->getID().'" class="submit-delete" title="Delete This">Delete</a>');
			}
			$fg->addElement('submit', array('name'=>'post', 'value'=>'Reset'), '', NULL, 'submit-reset');
			$fg->addFormPart('</li>');
			$fg->addFormPart('</ul>');
			$fg->addFormPart('</fieldset>');
			$post_form_display.=$fg->display();
			$post_form_display.='</div>';

			$display.=$post_form_display;
		}
	}
	# Get all subcontent.
	$display.=$general_edit_head;
	# Create a new SubContent object.
	$subc=new SubContent();
	# Get the branch subcontent display and set it to a variable.
	$display.=$subc->displayBranchSubContent($branch_name, 242, 10, TRUE);
	$display=$delete_form_display.$display;
}
else
{
	# Set the page's sub title.
	$sub_title=$branch_name.' Duplicate Content';
	# Set the duplicates to the SubContent all_subcontent data member.
	$sc_object->setAllSubcontent($duplicates);
	# Create an empty array to hold the wnated branches.
	$dup_branches=array();
	# Loop through the duplicates to get their branches.
	foreach($sc_object->getAllSubContent() as $subcontent_duplicate)
	{
		# Check if the branch is an array.
		if(!is_array($subcontent_duplicate['branch']))
		{
			$subcontent_duplicate['branch']=trim($subcontent_duplicate['branch'], '-');
			$subcontent_duplicate['branch']=explode('-', $subcontent_duplicate['branch']);
		}
		# Loop through the new branch array.
		foreach($subcontent_duplicate['branch'] as $branch_id)
		{
			# Check if this id is already in the wanted branches aray.
			if(!in_array($branch_id, $dup_branches))
			{
				# Add the id to the wanted branches array.
				$dup_branches[$branch_id]=$branch_id;
			}
		}
	}
	# Set the wnated branches to the appropriate SubContent data member.
	$sc_object->setWantedBranches($dup_branches);
	# Display the SubContent.
	$display_array=$sc_object->displaySubContent(255, constant(strtoupper(str_replace(' ', '_', $branch_name)).'_USERS'));
	$display.='<h3 class="h-3">The following post(s) seem to closely resemble the post you are submitting. If you feel your post is unique and would like to continue posting it, simply click on the "Back" button below. Conversely, you may choose to edit an existing post or click <a href="'.SECURE_URL.WebUtility::removeIndex(preg_replace('/'.$branch_name.'/i', '', SECURE_HERE)).str_replace(GET_QUERY, '', GET_QUERY).'">here</a> to continue without posting.</h3>';

	# Instantiate a new formGenerator object.
	$fg=new formGenerator('back_button');
	# Add a hidden input called '_submit_check' to the form.
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	# Add a hidden input called '_unique' to the form.
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>'1'));
	# Add the button to the form.
	$fg->addElement('submit', array('name'=>'post', 'value'=>'Back to the form!'), '', NULL, 'submit-back');
	# Concatenate the "back button" to the duplicates to be displayed.
	$display.=$fg->display();

	# Start an unordered list of the "subcontent" class and set it to a variable.
	$display.='<ul class="post">';
	# Loop through the display subcontent array.
	foreach($display_array as $display_duplicate)
	{
		# Add the post content to the post_form_display variable.
		$display.='<li>';
		$display.=$display_duplicate['date'];
		$display.=$display_duplicate['title'];
		$display.=$display_duplicate['text'];
		$display.=$display_duplicate['text_trans'];
		$display.=$display_duplicate['more'];
		$display.=$display_duplicate['edit'];
		$display.=$display_duplicate['delete'];
		$display.=$display_duplicate['download'];
		$display.='</li>';
	}
	# Close the unordered list.
	$display.='</ul>';
	# Concatenate the Back button to the duplicates to be displayed.
	$display.=$fg->display();
}
# Set the sub title.
$main_content->setSubTitle($sub_title);