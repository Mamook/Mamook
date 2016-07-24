<?php /* framework/application/templates/forms/staff_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'staff_form_defaults.php');
$display_delete_form=$form_processor->processStaff($default_data);

# Set the StaffFormPopulator object from the StaffFormProcessor data member to a variable.
$populator=$form_processor->getPopulator();
# Set the Staff object from the StaffFormPopulator data member to a variable.
$staff_obj=$populator->getStaffObject();

if(isset($_GET['add_desc']))
{
	$display.='<div id="profile_form" class="form">';

	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';

	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('staff_desc', $form_processor->getFormAction(), 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	# Get the Position class.
	require_once Utility::locateFile(MODULES.'Content'.DS.'Position.php');
	# Instantiate a new Position object.
	$position_obj=new Position();
	# Get all positions.
	$position_obj->getPositions();

	foreach($_SESSION['form']['staff']['NewPosition'] as $position_key=>$position_value)
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
	$fg->addElement('submit', array('name'=>'staff_desc', 'value'=>'Update'), '', NULL, 'submit-staff');
	if(isset($_GET['add_desc']))
	{
		$fg->addElement('submit', array('name'=>'staff_desc', 'value'=>'Go Back'), '', NULL, 'submit-back');
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
	# Add JavaScripts to the footer. (Use the script file name before the ".php".)
	# This form needs fileOption-submit, uniform-file, bsmSelect-multiple, and uniform-select. uniform-select MUST come after bsmSelect-multiple.
	$doc->setFooterJS('fileOption-submit,uniform-file,bsmSelect-multiple,uniform-select');

	$display.='<a href="'.APPLICATION_URL.'profile/?person='.$staff_id.'" target="_blank" title="View '.$staff_obj->getStaffName().'\'s Profile" class="view">view</a>';
	$display.='<div id="profile_form" class="form">';
	# create and display form
	$display.=$head;
	# Add the statement about requirements.
	$display.='<span class="required">* = required field</span>';
	# Instantiate a new FormGenerator object.
	$fg=new FormGenerator('staff', NULL, 'POST', '_top', TRUE);
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<h4>Name:</h4>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="title">Title</label>');
	$fg->addElement('text', array('name'=>'title', 'value'=>$staff_obj->getTitle(), 'id'=>'title'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="fname"><span class="required">*</span> First Name</label>');
	$fg->addElement('text', array('name'=>'fname', 'value'=>$staff_obj->getFirstName(), 'id'=>'fname'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="mname">Middle Name</label>');
	$fg->addElement('text', array('name'=>'mname', 'value'=>$staff_obj->getMiddleName(), 'id'=>'mname'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="lname"><span class="required">*</span> Last Name</label>');
	$fg->addElement('text', array('name'=>'lname', 'value'=>$staff_obj->getLastName(), 'id'=>'lname'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="credentials">Credentials</label>');
	$fg->addElement('text', array('name'=>'credentials', 'value'=>$staff_obj->getCredentials(), 'id'=>'credentials'));
	$fg->addFormPart('</li>');
	if($staff_obj->getID()!==NULL && $login->checkAccess(ADMIN_USERS)===TRUE)
	{
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="archive">Archive Staff</label>');
		$archive=(($staff_obj->getArchive()===NULL) ? '' : 'archive');
		$fg->addElement('checkbox', array('name'=>'archive', 'value'=>'archive', 'checked'=>$archive, 'id'=>'archive'));
		$fg->addFormPart('</li>');
		if($staff_obj->getArchive()===0)
		{
			$fg->addElement('hidden', array('name'=>'_update_staff', 'value'=>'no'));
		}
	}
	$fg->addFormPart('</fieldset>');
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<h4>About Yourself:</h4>');
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="region">Region</label>');
	$fg->addElement('text', array('name'=>'region', 'value'=>$staff_obj->getRegion(), 'id'=>'region'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$use_html=NULL;
	if($login->checkAccess(ALL_ADMIN_MAN)===TRUE)
	{
		$use_html='<p>(You may use (x)html)</p>';
	}
	$fg->addFormPart('<label class="label" for="text">Biographical info'.$use_html.'</label>');
	$fg->addElement('textarea', array('name'=>'text', 'text'=>$staff_obj->getText(), 'id'=>'text'), '', NULL, 'textarea');
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="image">Image</label>');
	$fg->addElement('file', array('name'=>'image', 'value'=>$staff_obj->getImage(), 'id'=>'image'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="imageTitle">Image Caption</label>');
	$fg->addElement('text', array('name'=>'image_title', 'value'=>$staff_obj->getImageTitle(), 'id'=>'imageTitle'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="affiliation">Affiliation</label>');
	$fg->addElement('text', array('name'=>'affiliation', 'value'=>$staff_obj->getAffiliation(), 'id'=>'affiliation'));
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	if($staff_obj->getID()!==NULL && $staff_obj->getArchive()!==TRUE)
	{
		$fg->addFormPart('<fieldset>');
		$fg->addFormPart('<h4>Position:</h4>');
		# Check if the User is an admin.
		if($login->checkAccess(ADMIN_USERS)===TRUE)
		{
			# Get the Position class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Position.php');
			# Instantiate a new Position object.
			$position_obj=new Position();
			# Get all positions.
			$position_obj->getPositions();
			$positions=$position_obj->getAllPositions();
			# If there are positions results.
			if(!empty($positions))
			{
				# Decode the `position` field in the `staff` table and return an array.
				$staff_positions_decoded=(!is_array($staff_obj->getPosition()) ? json_decode($staff_obj->getPosition(), TRUE) : $staff_obj->getPosition());
				//print_r($staff_positions_decoded);exit;
				# Loop through the positions in the `positions` table.
				foreach($positions as $row)
				{
					# Create an option for each playlist.
					$position_options[$row->id]=$row->position;
					# Check if this video currently has a playlist.
					if(!empty($staff_positions_decoded))
					{
						# Loop through the json data.
						foreach($staff_positions_decoded as $staff_positions)
						{
							# Check if the current playlist is default or has been selected by the user.
							if(in_array($row->id, $staff_positions)===TRUE)
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
			if(isset($staff_positions_decoded))
			{
				$fg->addFormPart('</ul>');
				$fg->addFormPart('</fieldset>');
				$fg->addFormPart('<fieldset>');
				$fg->addFormPart('<h4>Position Description:</h4>');
				$fg->addFormPart('<ul>');
				foreach($staff_positions_decoded as $position_key=>$position_value)
				{
					# Check if the description is set.
					$position_desc=(isset($position_value['description']) ? htmlspecialchars($position_value['description']) : '');
					# If there is no descritpion, don't show the description field.
					//if(!empty($position_desc))
					//{
						# Get the position data from the `positions` table.
						$position_obj->getThisPosition($position_value['position']);
						# Set the position.
						$position=$position_obj->getPosition();
						$fg->addFormPart('<li>');
						$fg->addFormPart('<label class="label" for="position_desc">'.$position.'</label>');
						$fg->addElement('hidden', array('name'=>'position_desc['.$position_key.'][position]', 'value'=>$position_value['position']));
						$fg->addElement('text', array('name'=>'position_desc['.$position_key.'][description]', 'value'=>$position_desc, 'id'=>'position_desc'));
						$fg->addFormPart('</li>');
					//}
				}
				$fg->addFormPart('</ul>');
				$fg->addFormPart('</fieldset>');
				$fg->addFormPart('<fieldset>');
			}

		}
	}
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<li>');
	$button_value='Update';
	$fg->addElement('submit', array('name'=>'staff', 'value'=>$button_value), '', NULL, 'submit-staff');
	# Check if this is an edit page.
	/*
	if(isset($_GET['user']) && !isset($_GET['delete']))
	{
		$fg->addFormPart('<a href="'.ADMIN_URL.'ManageUsers/?user='.$staff_obj->getID().'&amp;delete" class="submit-delete" title="Delete Staff">Delete</a>');
	}
	*/
	$fg->addElement('submit', array('name'=>'staff', 'value'=>'Reset'), '', NULL, 'submit-reset');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
	$display.='</div>';
}
$display=$display_delete_form.$display;