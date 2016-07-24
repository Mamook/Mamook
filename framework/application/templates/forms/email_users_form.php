<?php /* framework/application/templates/forms/email_users_form.php */

# Process the certificate application form.
$fp->processEmail($default_data);
# Set the EmailFormPopulator object from the EmailFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the Email object created in EmailFormPopulator to a variable.
$email=$populator->getEmailObject();

# Do we need some javascripts? (Use the script file name before the ".js".)
$doc->setJavaScripts('uniform,bsmSelect');
# Add JavaScripts to the footer. (Use the script file name before the ".php".)
# This form needs uniform-file, bsmSelect-multiple, and uniform-select. uniform-select MUST come after bsmSelect-multiple.
$doc->setFooterJS('uniform-file,bsmSelect-multiple,uniform-select');

$display.='<div id="email_form" class="form">';
# Create and display form
$display.=$head;
# instantiate a new FormGenerator object
$form_mail=new FormGenerator('user_email', $fp->getFormAction(), 'post', $fp->getTarget(), $fp->getUpload(), 'form-email');
$form_mail->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$form_mail->addElement('hidden', array('name'=>'html', 'value'=>'yes2yes'));
$form_mail->addFormPart('<fieldset>');
$form_mail->addFormPart('<ul>');
# Check if there is more than a single recipient in the recipients array.
if(count($recipients)>1)
{
	$form_mail->addFormPart('<li class="mult">');
	$form_mail->addFormPart('<label class="label" for="to"><span class="required">*</span> Send to</label>');
	$form_mail->addElement('select', array('name'=>'to[]', 'multiple'=>'multiple', 'title'=>'Choose a group or groups...', 'id'=>'to'), $recipients);
	$form_mail->addFormPart('</li>');
}
else
{
	foreach($recipients as $key=>$value)
	{
		$form_mail->addElement('hidden', array('name'=>'to[]', 'value'=>$key));
	}
}
$form_mail->addFormPart('<li>');
$form_mail->addFormPart('<label class="label" for="realname">Name</label>');
$form_mail->addElement('text', array('id'=>'realname', 'name'=>'realname', 'value'=>$email->getSenderName()));
$form_mail->addFormPart('</li>');
$form_mail->addFormPart('<li>');
$form_mail->addFormPart('<label class="label" for="email">E-mail</label>');
$form_mail->addElement('text', array('id'=>'email', 'name'=>'email', 'value'=>$email->getSenderEmail()));
$form_mail->addFormPart('</li>');
$form_mail->addFormPart('<li>');
$form_mail->addFormPart('<label class="label" for="subject">Subject</label>');
$form_mail->addElement('text', array('id'=>'subject', 'name'=>'subject', 'value'=>$email->getSubject()));
$form_mail->addFormPart('</li>');
# Check if a file upload is allowed.
if($fp->getUpload()===TRUE)
{
	$form_mail->addFormPart('<li>');
	$form_mail->addFormPart('<label class="label" for="file">File</label>');
	$form_mail->addElement('file', array('id'=>'file', 'name'=>'file'));
	$form_mail->addElement('hidden', array('name'=>'MAX_FILE_SIZE', 'value'=>$email->getMaxFileSize()));
	$form_mail->addFormPart('</li>');
}
$form_mail->addFormPart('<li>');
$form_mail->addFormPart('<label class="label msg" for="mesg">Message</label>');
$form_mail->addElement('textarea', array('id'=>'mesg', 'name'=>'mesg', 'text'=>$email->getMessage()));
$form_mail->addFormPart('</li>');
$form_mail->addFormPart('<li>');
$form_mail->addElement('submit', array('name'=>'send', 'value'=>'Send Email'), '', NULL, 'submit-email');
$form_mail->addFormPart('</li>');
$form_mail->addFormPart('</ul>');
$form_mail->addFormPart('</fieldset>');
$display.=$form_mail->display();
$display.='</div>';