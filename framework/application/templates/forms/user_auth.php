<?php /* framework/application/templates/forms/user_auth.php */

$display_request_auth_form='<div id="request_auth_form" class="form">';
# Create and display form.
$display_request_auth_form.='<h3>'.$head.'</h3>';
# Instantiate FormGenerator object.
$fg=new FormGenerator('user_auth');
$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$fg->addFormPart('<fieldset>');
$fg->addFormPart('<ul class="branch-items">');

# Loop through the branch id's.
foreach($branch_ids as $branch_id)
{
	# Retrieve the data for this branch from the `branches` table.
	$branch->getThisBranch($branch_id);
	$fg->addFormPart('<li>');
	$fg->addFormPart('<span class="label"><a href="http://'.$branch->getDomain().'" target="_blank">'.$branch->getBranch().'</a> Access Levels</span>');

	$authorized_checked=(($auth[$branch_id]===TRUE) ? '2' : '');
	$candidate_checked=(($auth[$branch_id]==='candidate') ? '4' : '');
	$never_checked=(($auth[$branch_id]==='not authorized') ? '3' : '');
	$not_checked=(($auth[$branch_id]===FALSE) ? '0' : '');

	$fg->addElement('radio', array('name'=>$branch_id, 'id'=>$branch_id.'_no', 'value'=>'0', 'checked'=>$not_checked));
	$fg->addFormPart('<label class="box_label" for="'.$branch_id.'_no">Not Authorized</label>');

	$fg->addElement('radio', array('name'=>$branch_id, 'id'=>$branch_id.'_authorized', 'value'=>'2', 'checked'=>$authorized_checked));
	$fg->addFormPart('<label class="box_label" for="'.$branch_id.'_authorized">Authorized</label>');

	$fg->addElement('radio', array('name'=>$branch_id, 'id'=>$branch_id.'_candidate', 'value'=>'4', 'checked'=>$candidate_checked));
	$fg->addFormPart('<label class="box_label" for="'.$branch_id.'_candidate">Candidate</label>');

	$fg->addElement('radio', array('name'=>$branch_id, 'id'=>$branch_id.'_never', 'value'=>'3', 'checked'=>$never_checked));
	$fg->addFormPart('<label class="box_label" for="'.$branch_id.'_never">Never</label>');

	$fg->addFormPart('</li>');
}
$fg->addFormPart('<li>');
$fg->addElement('submit', array('name'=>'send', 'value'=>'Send'), '', NULL, 'submit-auth');
$fg->addFormPart('</li>');
$fg->addFormPart('</ul>');
$fg->addFormPart('</fieldset>');
$display_request_auth_form.=$fg->display();
$display_request_auth_form.='</div>';