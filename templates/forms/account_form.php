<?php /* templates/forms/account_form.php */

require TEMPLATES.'forms'.DS.'account_form_defaults.php';
$display_delete_form=$form_processor->processAccount($default_data);

# Set the AccountFormPopulator object from the AccountFormProcessor data member to a variable.
$populator=$form_processor->getPopulator();
# Set the Staff object from the AccountFormPopulator data member to a variable.
$staff_obj=$populator->getStaffObject();

//print_r($staff_obj);
//print_r($form_processor);
//print_r($_SESSION);

if(isset($_GET['add_desc']))
{
	$display.='<div id="profile_form" class="form">';

	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';

	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('account_desc', $form_processor->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	# Get the Position class.
	require_once MODULES.'Content'.DS.'Position.php';
	# Instantiate a new Position object.
	$position_obj=new Position();
	# Get all positions.
	$position_obj->getPositions();

##### FIX THE LINE BELOW #####
	foreach($_SESSION['form']['account']['NewPosition'] as $position_key=>$position_value)
	{
		# Get the position data from the `positions` table.
		$position_obj->getThisPosition($position_value['position']);
		# Get the position name.
		$position=$position_obj->getPosition();
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="position_desc'.$position_key.'">'.$position.'</label>');
		$fg->addElement('hidden', array('name'=>'position_desc['.$position_key.'][position]', 'value'=>$position_value['position']));
		$fg->addElement('text', array('name'=>'position_desc['.$position_key.'][description]', 'value'=>'', 'id'=>'position_desc'.$position_key));
		$fg->addFormPart('</li>');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addElement('submit', array('name'=>'account_desc', 'value'=>'Update'), '', NULL, 'submit-account');
	if(isset($_GET['add_desc']))
	{
		$fg->addElement('submit', array('name'=>'account_desc', 'value'=>'Go Back'), '', NULL, 'submit-back');
	}
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';
}
else
{
	# Do we need some javascripts? (Use the script video name before the ".js".)
	$doc->setJavaScripts('uniform,bsmSelect');
	# Do we need some JavaScripts in the footer? (Use the script video name before the ".php".)
	$doc->setFooterJS('uniform-select,fileOption-submit,bsmSelect-multiple');

	$display.='<div id="profile_form" class="form">';
	# create and display form
	$display.=$head;
	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';
	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('account', NULL, 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
	if($staff_obj->getStaffID()!==NULL && $staff_obj->getArchive()!==0)
	{
		$fg->addElement('hidden', array('name'=>'_staff_id', 'value'=>$account_staff_id));
	}
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<h4>Name:</h4>');
	$fg->addFormPart('<ul>');
	# Check if there is a WordPress installation.
	if(WP_INSTALLED===TRUE)
	{
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="nickname"><span class="required">*</span> Nickname</label>');
		$fg->addElement('text', array('name'=>'nickname', 'value'=>$staff_obj->getNickname(), 'id'=>'nickname'));
		$fg->addFormPart('</li>');
	}
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="title">Title</label>');
	$fg->addElement('text', array('name'=>'title', 'value'=>$staff_obj->getTitle(), 'id'=>'title'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="fname"><span class="required">*</span> First Name</label>');
	$fg->addElement('text', array('name'=>'fname', 'value'=>$staff_obj->getFirstName(), 'id'=>'fname'));
	$fg->addFormPart('</li>');
	if($staff_obj->getStaffID()!==NULL && $staff_obj->getArchive()!==0)
	{
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="mname">Middle Name</label>');
		$fg->addElement('text', array('name'=>'mname', 'value'=>$account_middle_name, 'id'=>'mname'));
		$fg->addFormPart('</li>');
	}
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="lname"><span class="required">*</span> Last Name</label>');
	$fg->addElement('text', array('name'=>'lname', 'value'=>$account_last_name, 'id'=>'lname'));
	$fg->addFormPart('</li>');
	if($staff_obj->getStaffID()!==NULL && $staff_obj->getArchive()!==0)
	{
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="credentials">Credentials</label>');
		$fg->addElement('text', array('name'=>'credentials', 'value'=>$staff_obj->getCredentials(), 'id'=>'credentials'));
		$fg->addFormPart('</li>');
	}
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="display"><span class="required">*</span> Display name publicly as</label>');
	$fg->addElement('text', array('name'=>'display', 'value'=>$staff_obj->getDisplayName(), 'id'=>'display'));
	$fg->addFormPart('</li>');
	if($staff_obj->getStaffID()!==NULL && $login->checkAccess(ADMIN_USERS)===TRUE)
	{
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="archive">Archive Staff</label>');
		$archive=(($staff_obj->getArchive()===0) ? TRUE : '');
		$fg->addElement('checkbox', array('name'=>'archive', 'value'=>'archive', 'checked'=>$archive, 'id'=>'archive'));
		$fg->addFormPart('</li>');
		if($staff_obj->getArchive()===0)
		{
			$fg->addElement('hidden', array('name'=>'_update_staff', 'value'=>'no'));
		}
	}
	$fg->addFormPart('</fieldset>');
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<h4>Contact Info:</h4>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="address">Address</label>');
	$fg->addElement('text', array('name'=>'address', 'value'=>$staff_obj->getAddress(), 'id'=>'address'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="address2">Address 2</label>');
	$fg->addElement('text', array('name'=>'address2', 'value'=>$staff_obj->getAddress2(), 'id'=>'address2'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="city">City</label>');
	$fg->addElement('text', array('name'=>'city', 'value'=>$staff_obj->getCity(), 'id'=>'city'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="state">State</label>');
	$fg->addElement('text', array('name'=>'state', 'value'=>$staff_obj->getState(), 'id'=>'state'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="country">Country</label>');
	$fg->addElement('text', array('name'=>'country', 'value'=>$staff_obj->getCountry(), 'id'=>'country'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="zipcode">Zipcode</label>');
	$fg->addElement('text', array('name'=>'zipcode', 'value'=>$staff_obj->getZipcode(), 'id'=>'zipcode'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="phone">Phone Number</label>');
	$fg->addElement('text', array('name'=>'phone', 'value'=>$staff_obj->getPhone(), 'id'=>'phone'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="email"><span class="required">*</span> Email</label>');
	$fg->addElement('text', array('name'=>'email', 'value'=>$staff_obj->getEmail(), 'id'=>'email'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="website">Website</label>');
	$fg->addElement('text', array('name'=>'website', 'value'=>$staff_obj->getWebsite(), 'id'=>'website'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<h4>About Yourself:</h4>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="cv">Curriculum Vitae (CV)</label>');
	$fg->addElement('file', array('name'=>'cv', 'id'=>'cv'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="region">Region</label>');
	$fg->addElement('text', array('name'=>'region', 'value'=>$staff_obj->getRegion(), 'id'=>'region'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="interests">Interests</label>');
	$fg->addElement('text', array('name'=>'interests', 'value'=>$staff_obj->getInterests(), 'id'=>'interests'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$use_html=NULL;
	if($login->checkAccess(ALL_ADMIN_MAN)===TRUE)
	{
		$use_html='<p>(You may use (x)html)</p>';
	}
	$fg->addFormPart('<label class="label" for="bio">Biographical info'.$use_html.'</label>');
	$fg->addElement('textarea', array('name'=>'bio', 'text'=>$staff_obj->getBio(), 'id'=>'bio'), '', NULL, 'textarea');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="image">Image</label>');
	$fg->addElement('file', array('name'=>'image', 'value'=>$staff_obj->getImg(), 'id'=>'image'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="imgTitle">Image Caption</label>');
	$fg->addElement('text', array('name'=>'img_title', 'value'=>$staff_obj->getImgTitle(), 'id'=>'imgTitle'));
	$fg->addFormPart('</li>');
	if($staff_obj->getStaffID()!==NULL && $staff_obj->getArchive()!==TRUE)
	{
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="affiliation">Affiliation</label>');
		$fg->addElement('text', array('name'=>'affiliation', 'value'=>$staff_obj->getAffiliation(), 'id'=>'affiliation'));
		$fg->addFormPart('</li>');
		$fg->addFormPart('</ul>');
		$fg->addFormPart('</fieldset>');
		$fg->addFormPart('<fieldset>');
		$fg->addFormPart('<h4>Position:</h4>');
		# Check if the User is an admin.
		if($login->checkAccess(ADMIN_USERS)===TRUE)
		{
			# Get the Position class.
			require_once MODULES.'Content'.DS.'Position.php';
			# Instantiate a new Position object.
			$position_obj=new Position();
			# Get all positions.
			$position_obj->getPositions();
			$positions=$position_obj->getAllPositions();
			# If there are positions results.
			if(!empty($positions))
			{
				# Decode the `position` field in the `staff` table and return an array.
				$account_positions_decoded=(!is_array($staff_obj->getPosition()) ? json_decode($staff_obj->getPosition(), TRUE) : $staff_obj->getPosition());
				# Loop through the positions in the `positions` table.
				foreach($positions as $row)
				{
					# Create an option for each playlist.
					$position_options[$row->id]=$row->position;
					# Check if this video currently has a playlist.
					if(!empty($account_positions_decoded))
					{
						# Loop through the json data.
						foreach($account_positions_decoded as $account_positions)
						{
							# Check if the current playlist is default or has been selected by the user.
							if(in_array($row->id, $account_positions)===TRUE)
							{
								# Set the selected playlist to the default.
								$position_options['multiple_selected'][$row->id]=$row->position;
							}
						}
					}
				}
			}
			else
			{
				$position_options[]='No Positions';
			}
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li>');
			$fg->addFormPart('<label class="label" for="position">Position</label>');
			$fg->addElement('select', array('name'=>'position[]', 'multiple'=>'multiple', 'title'=>'Select a Position', 'id'=>'position'), $position_options);
			$fg->addFormPart('</li>');
			/*
			if(isset($account_positions_decoded))
			{
				$fg->addFormPart('<li>');
				$fg->addFormPart('<label class="label">Position Description');
				//$fg->addFormPart('<a href="'.ADMIN_URL.'ManageUsers/?user='.$account_id.'&amp;edit_desc" class="submit-edit-desc" title="Edit Description">Edit Description</a>');
				$fg->addElement('submit', array('name'=>'account', 'value'=>'Edit Description'), '', NULL, 'submit-edit-desc');
				$fg->addFormPart('</label>');
				$fg->addFormPart('<div class="bsm" id="bsm1">');
				$fg->addFormPart('<ol id="bsm-listbsm1" class="bsm-list">');
				foreach($account_positions_decoded as $position_key=>$position_value)
				{
					# Get the position data from the `positions` table.
					$position_obj->getThisPosition($position_value['position']);
					# Set the position.
					$position=$position_obj->getPosition();
					$fg->addFormPart('<li class="bsm-item">');
					$fg->addFormPart('<span class="bsm-label">'.$position.': '.$position_value['description'].'</span>');
					$fg->addFormPart('</li>');
				}
				$fg->addFormPart('</ol>');
				$fg->addFormPart('</div>');
				$fg->addFormPart('</li>');
			}
			*/

			if(isset($account_positions_decoded))
			{
				$fg->addFormPart('</ul>');
				$fg->addFormPart('</fieldset>');
				$fg->addFormPart('<fieldset>');
				$fg->addFormPart('<h4>Position Description:</h4>');
				$fg->addFormPart('<ul>');
				foreach($account_positions_decoded as $position_key=>$position_value)
				{
					# Get the position data from the `positions` table.
					$position_obj->getThisPosition($position_value['position']);
					# Set the position.
					$position=$position_obj->getPosition();
					$fg->addFormPart('<li>');
					$fg->addFormPart('<label class="label" for="position_desc">'.$position.'</label>');
					$fg->addElement('hidden', array('name'=>'position_desc['.$position_key.'][position]', 'value'=>$position_value['position']));
					$fg->addElement('text', array('name'=>'position_desc['.$position_key.'][description]', 'value'=>htmlspecialchars($position_value['description']), 'id'=>'position_desc'));
					$fg->addFormPart('</li>');
				}
				$fg->addFormPart('</ul>');
				$fg->addFormPart('</fieldset>');
				$fg->addFormPart('<fieldset>');
			}

		}
	}
	$fg->addFormPart('<li>');
	$button_value='Update';
	$fg->addElement('submit', array('name'=>'account', 'value'=>$button_value), '', NULL, 'submit-account');
	# Check if this is an edit page.
	if(isset($_GET['user']) && !isset($_GET['delete']))
	{
		$fg->addFormPart('<a href="'.ADMIN_URL.'ManageUsers/?user='.$account_id.'&amp;delete" class="submit-delete" title="Delete User">Delete</a>');
	}
	$fg->addElement('submit', array('name'=>'account', 'value'=>'Reset'), '', NULL, 'submit-reset');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';
}
$display=$display_delete_form.$display;