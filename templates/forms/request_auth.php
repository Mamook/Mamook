<?php /* templates/forms/request_auth.php */

$display='<div id="request_auth_form" class="form">';
# Check if the user is an admin.
if((!isset($_GET['user'])) && $login->isAdmin()===TRUE || in_array(1, $user->findUserLevel($id))===TRUE)
{
	$display.='<h3>You are an admin on this site. You are authorized to do anything.</h3>';
}
else
{
	# Create and display form.
	$display.=$head;
	# Instantiate FormGenerator object.
	$fg=new FormGenerator('request_auth');
	$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$fg->addFormPart('<fieldset>');
	$fg->addFormPart('<ul>');

	# Loop through the branch id's.
	foreach($branch_ids as $branch_id)
	{
		# Check if the User has been explicitly "Not Authorized" for this branch.
		if($auth[$branch_id]!=='not authorized')
		{
			# Retrieve the data for this branch from the `branches` table.
			$branch->getThisBranch($branch_id);
			$fg->addFormPart('<li>');
			if($auth[$branch_id]===FALSE)
			{
				$fg->addElement('checkbox', array('name'=>$branch_id));
				$fg->addFormPart('<label class="box_label" for="'.$branch_id.'"><a href="http://'.$branch->getDomain().'" target="_blank">'.$branch->getBranch().'</a></label>');
			}
			elseif($auth[$branch_id]===TRUE)
			{
				$fg->addFormPart('You are currently Authorized to post and edit <a href="http://'.$branch->getDomain().'" target="_blank">'.$branch->getBranch().'</a>');
			}
			elseif($auth[$branch_id]==='candidate')
			{
				$fg->addFormPart('You are currently being considered for Authorization to post and edit <a href="http://'.$branch->getDomain().'" target="_blank">'.$branch->getBranch().'</a>');
			}
			$fg->addFormPart('</li>');
		}
	}
	$fg->addFormPart('<li>');
	$fg->addElement('submit', array('name'=>'send', 'value'=>'Send'), '', NULL, 'submit-auth');
	$fg->addFormPart('</li>');
	$fg->addFormPart('</ul>');
	$fg->addFormPart('</fieldset>');
	$display.=$fg->display();
}
$display.='</div>';