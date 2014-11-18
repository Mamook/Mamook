<?php /* public/profile/index.php */

ob_start(); # Begin output buffering

try
{
	# Define the location of this page.
	define('HERE_PATH', 'profile/index.php');
	/*
	** In settings we
	** define application settings
	** define system settings
	** start a new session
	** connect to the Database
	*/
	require_once '../../settings.php';

	# Instantiate a new User object.
	$user=new User();

	# Check if the GET data was "contributor".
	if(isset($_GET['contributor']) && ($validator->isInt(trim($_GET['contributor']))===TRUE))
	{
		# Set the contributor's id to a variable.
		$id=(int)$_GET['contributor'];
		# Set `contributor` as the table to search for this person.
		$table='contributor';
	}
	# Check if the GET data was "member".
	elseif(isset($_GET['member']) && ($validator->isInt(trim($_GET['member']))===TRUE))
	{
		# Set the User's ID to a variable.
		$id=$_GET['member'];
		# Set `user` as the table to search for this person.
		$table='user';
	}
	# Check if the GET data was "person".
	elseif(isset($_GET['person']) && ($validator->isInt(trim($_GET['person']))===TRUE))
	{
		# Set the person's id to a variable.
		$id=(int)$_GET['person'];
		# Set `staff` as the table to search for this person.
		$table='staff';
	}
	# Check if the GET data was "publisher".
	elseif(isset($_GET['publisher']))
	{
		# Set the GET data to the $value variable.
		$value=trim($_GET['publisher']);
		# Set the $id variable to FALSE as default.
		$id=FALSE;
		# Check if $value is an integer.
		if($validator->isInt($value)===TRUE)
		{
			# Set the GET data to a variable explicitly making it an integer.
			$id=TRUE;
		}
		# Get the Publisher class.
		require_once MODULES.'Content'.DS.'Publisher.php';
		# Instantiate a new Publisher object.
		$pub=new Publisher();
		# Get the Publisher display XHTML and set it to a variable.
		$publisher=$pub->displayPublisher($value, $id);
		# Check if there was a publisher to display.
		if(!empty($publisher))
		{
			# Set the display xhtml to a variable for display to the user.
			$display='<div id="profile" class="profile post">';
			$display.=$publisher['info'];
			$display.=$publisher['contributor'];
			$display.=$publisher['recent_contributor'];
			$display.='</div>';
			# Set the page title to the publisher's name.
			$page_title=$publisher['publisher'];
		}
		else
		{
			$_SESSION['message']='That publisher can not be displayed.';
			$doc->redirect(DEFAULT_REDIRECT);
		}
	}
	else
	{
		$doc->redirect(DEFAULT_REDIRECT);
	}

	# Create a variable to hold the display of the cv.
	$cv_display='';

	# Check to make sure the GET data isn't "publisher".
	if(!isset($_GET['publisher']))
	{
		# Get the member display XHTML and set it to a variable.
		$member=$user->displayProfile($id, $table);
		# Check if there was a member to display.
		if(!empty($member))
		{
			# Set the display xhtml to a variable for display to the user.
			$display='<div id="profile" class="profile">';
			$display.=$member['image'];
			$display.=$member['organization'];
			# Count the number of positions held.
			$num_position=count($member['position']);
			$position_fix='<span class="profile-position">'.
					'<span class="label">Position'.(($num_position>1) ? 's' : '').':</span>'.
					'<ul>%s</ul>'.
					'</span>';
			# Check if there are positions.
			if(!empty($member['position']))
			{
				# Get the Position class.
				require_once MODULES.'Content'.DS.'Position.php';
				# Instantiate a new Position object.
				$position_obj=new Position();
				# Create an empty array.
				$position_array=array();
				# Loop through the member's positions.
				foreach($member['position'] as $position_key=>$position_value)
				{
					# Get the position data from the `positions` table.
					$position_obj->getThisPosition($position_value['position']);
					# Set the position.
					$position=$position_obj->getPosition();
					# Store the position in the new array.
					$position_array[$position_key]['position']=$position;
					# Store the position description in the new array.
					$position_array[$position_key]['description']=$position_value['description'];
				}
				# Create an empty array.
				$pos_focus=array();
				# Loop through $position_array we created above.
				foreach($position_array as $position)
				{
					# Store the HTML markup in the new array.
					$pos_focus[]='<li>'.$position['position'].(!empty($position['description']) ? ' - '.$position['description'] : '').'</li>';
				}
				$display.=sprintf($position_fix, implode('', $pos_focus));
			}
			$display.=$member['website'];
			$display.=$member['affiliation'];
			$display.=$member['region'];
			$display.=$member['country'];
			$display.=$member['interests'];
			$display.='<div class="empty"></div>';
			$display.=$member['bio'];
			$display.='</div>';
			var_dump($member['website']);exit;
			# Check if the person accepts emails from other users.
			if($member['questions']===0)
			{
				# Get the FormGenerator Class.
				require_once MODULES.'Form'.DS.'FormGenerator.php';
				# Get the EmailFormProcessor Class.
				require_once MODULES.'Form'.DS.'EmailFormProcessor.php';
				# Instantiate a new FormProcessor object.
				$fp=new EmailFormProcessor();
				# Create a recipient name for the person based in their display name.
				$r_name=preg_replace('/[\ \,\.]/', '', $member['display_name']);
				# Make sure the Custom recipents potion of the FormMail ini file is clean.
				$fp->editFormMailIni(NULL, $r_name, TRUE);
				# Check if the User is logged in.
				if($login->isLoggedIn()===TRUE)
				{
					# Add the person's email address to the FormMail ini file.
					$fp->editFormMailIni($member['email'], $r_name);

					$good_url=WebUtility::removeIndex('http://'.FULL_URL).'&success=yes';
					$bad_url=WebUtility::removeIndex('http://'.FULL_URL).'&mail_error=true';

					# Set the email form to a variable.
					$head='<h3>Use the form below to ask '.$member['display_name'].' a question.</h3>';

					if(isset($_GET['success']) && ($_GET['success']=='yes'))
					{
						$doc->setError('Thank you! Your mail has been sent to '.$member['display_name'].'.');
					}

					if(isset($_GET['mail_error']) && ($_GET['mail_error']=='true'))
					{
						$doc->setError('<h3>There was an error sending you\'re email...</h3>
						Please make sure you entered your name and a valid email address. If it still isn\'t working, rest assured that the webmaster has received an email and will work out the issue as soon as possible. You may try again later. Thanks.');
					}

					$fp->setFormAction(WebUtility::removeIndex('http://'.FULL_URL));
					$fp->setUpload(FALSE);
					# Get the email form default values.
					require TEMPLATES.'forms'.DS.'email_form_defaults.php';
					# Add the recipient name to the "to" property of the default data.
					$default_data['Recipients']=$r_name;
					# Process the email form.
					$send_to_formmail=$fp->processEmail($default_data);

					# Get the form mail template.
					require TEMPLATES.'forms'.DS.'email_form.php';
				}
			}
			# Set the page title as the person's display name.
			$page_title=$member['name'];
			if(!empty($page_title))
			{
				$main_content->setPageTitle($page_title);
			}
			# Set the cv to the display variable.
			$cv_display=((isset($member['cv'])) ? $member['cv'] : '');
		}
		else
		{
			$_SESSION['message']='That User can not be displayed.';
			$doc->redirect(DEFAULT_REDIRECT);
		}
	}

	# Set the meta discription for this page.
	$meta_desc='The profile for '.strip_tags($page_title).' on '.DOMAIN_NAME.'.';

	# Set the page title to the post's title.
	$main_content->setPageTitle($page_title);

	/*
	** In the page template we
	** get the header
	** get the masthead
	** get the subnavbar
	** get the navbar
	** get the page view
	** get the quick registration box
	** get the footer
	*/
	require TEMPLATES.'page.php';
}
catch(Exception $e)
{
	$exception=new ExceptionHandler($e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
}

ob_flush(); # Send the buffer to the user's browser.