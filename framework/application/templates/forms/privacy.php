<?php /* framework/application/templates/forms/privacy.php */

$person='me';
# Check if the user was passed in the URL.
if(isset($current_username))
{
	$person=$current_username;
}

$newsletter='';
if($user_obj->getNewsletter()!==NULL)
{
	$newsletter='checked';
}
$questions='';
if($user_obj->getQuestions()!==NULL)
{
	$questions='checked';
}

# Instantiate FormGenerator object.
$fg=new FormGenerator('privacy');

$display='<div id="privacy_form" class="form">';
	# Create the form and set the xhtml to a variable for display.
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addFormPart('<ul>');
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<li>');
	$fg->addElement('checkbox',array('name'=>'newsletter', 'checked'=>$newsletter, 'id'=>'newsletter'));
	$fg->addFormPart('<label class="label-box" for="newsletter">Send '.$person.' the '.DOMAIN_NAME.' newsletter</label>');
	# Is the `newsletter` field set to 1 (pending)?
	if((int)$user_obj->getNewsletter()===1)
	{
		$fg->addFormPart(' (pending)');
	}
	$fg->addFormPart('</li>');

	# Loop through the branch id's.
	foreach($branch_ids as $branch_num)
	{
		# Create an empty variable to hold the notify checkbox "checked" value.
		$notify='';

		# Create a variable to hold the User's notify setting.
		$notify_setting=$user_obj->getNotify();
		# Check if the User's notify setting is empty(NULL).
		if(!empty($notify_setting))
		{
			# Check if this branch id is in the User's notify setting.
			if(in_array($branch_num, $notify_setting))
			{
				# Set the notify checkbox "checked" value to "checked'.
				$notify='checked';
			}
		}

		# Get the data for this branch.
		$branch_obj->getThisBranch($branch_num);
		# Set the branch id to a variable.
		$branch_id=$branch_obj->getID();
		# Set the branch name to a variable.
		$branch_name=$branch_obj->getBranch();
		# Create the User privelege constant.
		$constant=str_replace(' ', '_', strtoupper($branch_name)).'_USERS';
		if($login->checkAccess(constant($constant))===TRUE)
		{
			$fg->addFormPart('<li>');
			$fg->addElement('checkbox', array('name'=>$branch_id, 'checked'=>$notify, 'id'=>$branch_id));
			$fg->addFormPart('<label class="label-box" for="'.$branch_id.'">Send '.$person.' <a href="http://'.$branch_obj->getDomain().'" target="_blank">'.$branch_name.'</a> updates</label>');
			$fg->addFormPart('</li>');
		}
	}

	$fg->addFormPart('<li>');
	$fg->addElement('checkbox', array('name'=>'questions', 'checked'=>$questions, 'id'=>'questions'));
	$fg->addFormPart('<label class="label-box" for="questions">Allow '.DOMAIN_NAME.' Users to email '.$person.' via a form on '.((isset($current_username)) ? 'his/her' : 'my').' <a href="'.APPLICATION_URL.'profile/?member='.$user_obj->findUserID().'" title="View '.$user_obj->findDisplayName().'\'s profile" target="_blank">profile page</a>.</label>');
	$fg->addFormPart('</li>');

	# Get the Contributor class.
	require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
	# Instantiate a new Contributor object.
	$contributor=new Contributor();
	# Check if the User is a contributor.
	if($contributor->getThisContributor($user_obj->findUserID())===TRUE)
	{
		$fg->addFormPart('<li>');
		$hide_contributor='';
		$show_contributor='';
		$show_user_contributor='';
		$cont_privacy=$contributor->getContPrivacy();
		if($cont_privacy===NULL)
		{
			$hide_contributor='hide';
		}
		if(($cont_privacy===1) || ($cont_privacy==='1'))
		{
			$show_user_contributor='users';
		}
		if(($cont_privacy===0) || ($cont_privacy==='0'))
		{
			$show_contributor='all';
		}
		$fg->addFormPart('<h4>Contributions I make to '.DOMAIN_NAME.' should</h4>');
		$fg->addFormPart('<ul class="radio_list1">');
		$fg->addFormPart('<li>');
		$fg->addElement('radio', array('name'=>'cont_privacy', 'value'=>'hide', 'checked'=>$hide_contributor, 'id'=>'contFalse'));
		$fg->addFormPart('<label class="label-radio" for="contFalse">not display my name as a contributor</span>');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addElement('radio', array('name'=>'cont_privacy', 'value'=>'users', 'checked'=>$show_user_contributor, 'id'=>'contTrueUsers'));
		$fg->addFormPart('<label class="label-radio" for="contTrueUsers">display my name as a contributor to '.DOMAIN_NAME.' Users only</label>');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addElement('radio', array('name'=>'cont_privacy', 'value'=>'all', 'checked'=>$show_contributor, 'id'=>'contTrue'));
		$fg->addFormPart('<label class="label-radio" for="contTrue">display my name as a contributor to everyone</label>');
		$fg->addFormPart('</li>');
		$fg->addFormPart('</ul>');
		$fg->addFormPart('</li>');
	}
	$fg->addFormPart('</fieldset>');

	$fg->addFormPart('<li>');
	$fg->addElement('submit', array('name'=>'send', 'value'=>'Update'), '', NULL, 'submit-privacy');
	$fg->addFormPart('</li>');
	$display.=$fg->display();
$display.='</div>';