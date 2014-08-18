<?php /* templates/forms/file_form.php */

require TEMPLATES.'forms'.DS.'file_form_defaults.php';
$display_delete_form=$form_processor->processFile($default_data);

# Set the FileFormPopulator object from the FileFormProcessor data member to a variable.
$populator=$form_processor->getPopulator();
# Set the File object from the FileFormPopulator data member to a variable.
$file=$populator->getFileObject();

$select=TRUE;
$file_form_display='';

if(!isset($_GET['select']))
{
	$select=FALSE;

	$duplicates=$form_processor->getDuplicates();
	if(empty($duplicates))
	{
		# Do we need some javascripts? (Use the script file name before the ".js".)
		$doc->setJavaScripts('uniform,bsmSelect');
		# Do we need some JavaScripts in the footer? (Use the script file name before the ".php".)
		$doc->setFooterJS('uniform-select,uniform-file,bsmSelect-multiple');

		# Set the file name to a variable.
		$file_name=$file->getFile();
		# Check if this is an edit or delete page.
		if(isset($_GET['file']))
		{
			# Set the page's subtitle as an edit page.
			$sub_title='Files - Edit <span>'.$file_name.'</span>';
			# Check if this is a delete page.
			if(isset($_GET['delete']))
			{
				# Set the page's subtitle as a delete page.
				$sub_title='Files - Delete <span>'.$file_name.'</span>';
			}
		}

		$file_form_display='<div id="file_form" class="form">';

		# create and display form.
		$file_form_display.='<h3>'.$head.'</h3>';

		# Add the statement about requirements.
		$file_form_display.='<span class="required">* = required field</span>';

		# Create an array to hold the available availability options.
		$available_options=array(0=>'This site does not yet have the legal rights to display', 1=>'This site has the legal rights to display', 2=>'Internal document only', 3=>'Can not distribute');
		# Loop through the available availability options.
		foreach($available_options as $value=>$option)
		{
			# Check if the POST data equals the index of the current option.
			if($file->getAvailability()==$value)
			{
				# Set the selected availability to the default.
				$availability_options['selected']=$option;
			}
			# Set the option to the options array.
			$availability_options[$value]=$option;
		}

		# Get the publish year from the File data member.
		$file_year=$file->getYear();
		# Check if the publish year value is empty.
		if(empty($file_year))
		{
			# Reset the value to "Unknown".
			$file_year='Unknown';
		}
		# Set the selected year to the options array and create the "Unknown" option.
		$select_file_year=array('selected'=>$file_year, 'unknown'=>'Unknown');

		# Get the Category class.
		require_once MODULES.'Content'.DS.'Category.php';
		# Instantiate a new Category object.
		$category=new Category();
		# get the categories from the `categories` table.
		$category->getCategories(NULL, '`id`, `category`', 'category', 'ASC', ' WHERE `product` IS NULL');
		# Set the categories to a variable.
		$categories=$category->getAllCategories();
		# Create the "Add Category" option.
		//$cat_options['add']='Add Category';
		# Set the current categories to a variable.
		$file_categories=array_flip((array)$file->getCategories());
		# Loop through the categories.
		foreach($categories as $row)
		{
			# Create an option for each category.
			$cat_options[$row->id]=$row->category;
			# Check if this file currently has a category.
			if(!empty($file_categories))
			{
				# Check if the current category is default or has been selected by the user.
				if(in_array($row->id, $file_categories)===TRUE)
				{
					# Set the selected category to the default.
					$cat_options['multiple_selected'][$row->id]=$row->category;
				}
				elseif($populator->getCategoryOption()==='add')
				{
					# Set the "Add Category" option as selected.
					$cat_options['multiple_selected']['add']='Add Category';
				}
			}
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
			if($row->institution==$file->getInstitution())
			{
				# Set the selected institution to the default.
				$inst_options['selected']=$row->institution;
			}
			elseif($populator->getInstitutionOption()==='add')
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
			if($row->language==$file->getLanguage())
			{
				# Set the selected language to the default.
				$language_options['selected']=$row->language;
			}
			elseif($populator->getLanguageOption()==='add')
			{
				$language_options['selected']='Add Language';
			}
		}

		$premium=$file->getPremium();
		if($premium===0)
		{
			$premium=TRUE;
		}
		else
		{
			$premium='';
		}

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
			if($row->name==$file->getPublisher())
			{
				# Set the selected publisher to the default.
				$pub_options['selected']=$row->name;
			}
			elseif($populator->getPublisherOption()==='add')
			{
				$pub_options['selected']='Add Publisher';
			}
		}

		# Instantiate a new FormGenerator object.
		$fg=new FormGenerator('file', $form_processor->getFormAction(), 'POST', '_top', TRUE);
		$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
		$fg->addElement('hidden', array('name'=>'MAX_FILE_SIZE', 'value'=>((isset($max_file_size)) ? $max_file_size : 104857600)));
		$fg->addFormPart('<fieldset>');
		$fg->addFormPart('<ul>');
		$fg->addFormPart('<li class="date">');
		# Get the dat from the SubContent data member.
		$date=$file->getDate();
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
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="title"><span class="required">*</span> Title</label>');
		$fg->addElement('text', array('name'=>'title', 'id'=>'title', 'value'=>$file->getTitle()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="file"><span class="required">*</span> File</label>');
		$fg->addElement('file', array('name'=>'file', 'id'=>'file'));
		if(!empty($file_name))
		{
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li class="file-current">');
			$fg->addFormPart('<a href="'.APPLICATION_URL.'download/?f='.$file_name.(($file->getPremium()!==NULL) ? '&amp;t=premium' : '').'" title="Current File">'.$file_name.' - "'.$file->getTitle().'"</a>');
			$fg->addElement('hidden', array('name'=>'_file', 'value'=>$file_name));
			$fg->addFormPart('</li>');
			$fg->addFormPart('</ul>');
		}
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="premium">Subscription Only Content</label>');
		$fg->addElement('checkbox', array('name'=>'premium', 'value'=>'premium', 'id'=>'premium', 'checked'=>$premium));
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
		$fg->addElement('text', array('name'=>'author', 'id'=>'author', 'value'=>$file->getAuthor()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="file_year">Publish Year</label>');
		$fg->addElement('select', array('name'=>'file_year', 'id'=>'file_year'), $select_file_year);
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="location"><span class="required">*</span> Publish Location</label>');
		$fg->addElement('text', array('name'=>'location', 'id'=>'location', 'value'=>$file->getLocation()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li class="mult">');
		$fg->addFormPart('<label class="label" for="category"><span class="required">*</span> Category</label>');
		$fg->addElement('select', array('name'=>'category[]', 'multiple'=>'multiple', 'title'=>'Select a Catagory', 'id'=>'category'), $cat_options);
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
		$button_value='Add File';
		# Check if this is an edit page.
		if(isset($_GET['file']))
		{
			$button_value='Update';
		}
		$fg->addElement('submit', array('name'=>'file', 'value'=>$button_value), '', NULL, 'submit-file');
		# Check if this is an edit page.
		if(isset($_GET['file']) && !isset($_GET['delete']))
		{
			$fg->addFormPart('<a href="'.ADMIN_URL.'ManageMedia/files/?file='.$file->getID().'&amp;delete" class="submit-delete" title="Delete This">Delete</a>');
		}
		$fg->addElement('submit', array('name'=>'file', 'value'=>'Reset'), '', NULL, 'submit-reset');
		if(isset($_GET['add']))
		{
			$fg->addElement('submit', array('name'=>'file', 'value'=>'Go Back'), '', NULL, 'submit-back');
		}
		$fg->addFormPart('</li>');
		$fg->addFormPart('</ul>');
		$fg->addFormPart('</fieldset>');
		$file_form_display.=$fg->display();
		$file_form_display.='</div>';
	}
	else
	{
		# Set the page's sub title.
		$sub_title='Duplicate File';
		$dup_display[$dup_file->getID()]=array(
									'id'=>$dup_file->getID(),
									'author'=>$dup_file->getAuthor(),
									'availability'=>$dup_file->getAvailability(),
									'categories'=>$dup_file->getCategories(),
									'contributor'=>$dup_file->getContID(),
									'date'=>$dup_file->getDate(),
									'file'=>$dup_file->getFile(),
									'institution'=>$dup_file->getInstitution(),
									'language'=>$dup_file->getLanguage(),
									'location'=>$dup_file->getLocation(),
									'premium'=>$dup_file->getPremium(),
									'publisher'=>$dup_file->getPublisher(),
									'title'=>$dup_file->getTitle(),
									'year'=>$dup_file->getYear()
									);

		# Set the duplicates to the File all_files data member.
		$file->setAllFiles($duplicates);
		# Display the SubContent.
		$display_array=$sc->displaySubContent(255, constant(strtoupper(str_replace(' ', '_', $branch_name)).'_USERS'));
		$file_form_display.='<h3>The following file(s) seem to closely resemble the file you are submitting. If you feel your file is unique and would like to continue uploading it, simply click on the "Back" button below. Conversely, you may choose to edit an existing file or click <a href="'.SECURE_URL.WebUtility::removeIndex(SECURE_HERE).str_replace(GET_QUERY, '', GET_QUERY).'">here</a> to continue without uploading.</h3>';

		# Instantiate a new formGenerator object.
		$fg=new formGenerator('back_button');
		# Add a hidden input called '_submit_check' to the form.
		$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		# Add a hidden input called '_unique' to the form.
		$fg->addElement('hidden', array('name'=>'_unique', 'value'=>'1'));
		# Add the button to the form.
		$fg->addElement('submit', array('name'=>'file', 'value'=>'Back to the form!'), '', NULL, 'submit-back');
		# Concatenate the "back button" to the duplicates to be displayed.
		$file_form_display.=$fg->display();

		# Start an unordered list of the "subcontent" class and set it to a variable.
		$file_form_display.='<ul class="file">';
		# Loop through the display subcontent array.
		foreach($display_array as $display_duplicate)
		{
			# Add the post content to the post_form_display variable.
			$file_form_display.='<li>';
			$file_form_display.=$display_duplicate['date'];
			$file_form_display.=$display_duplicate['title'];
			$file_form_display.=$display_duplicate['text'];
			$file_form_display.=$display_duplicate['text_trans'];
			$file_form_display.='<div class="empty"></div>';
			$file_form_display.=$display_duplicate['more'];
			$file_form_display.=$display_duplicate['edit'];
			$file_form_display.=$display_duplicate['delete'];
			$file_form_display.=$display_duplicate['download'];
			$file_form_display.='<div class="empty"></div>';
			$file_form_display.='</li>';
		}
		# Close the unordered list.
		$file_form_display.='</ul>';
		# Concatenate the Back button to the duplicates to be displayed.
		$file_form_display.=$fg->display();
	}
}

$file_form_display.=$file->displayFileList('!1-!2-!3-!4-!5', $select);

$file_form_display=$display_delete_form.$file_form_display;