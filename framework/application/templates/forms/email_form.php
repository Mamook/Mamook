<?php /* framework/application/templates/forms/email_form.php */

# Process the certificate application form.
$fp->processEmail($default_data);
# Set the CertificateAppFormPopulator object to a variable.
$populator=$fp->getPopulator();
# Set the Email object created in CertificateAppFormPopulator to a variable.
$email_obj=$populator->getEmailObject();
if(isset($_GET['mail']))
{
	$recipients=trim(strip_tags($_GET['mail']));
	$fp->setFormAction(APPLICATION_URL.WebUtility::removeIndex(HERE).'?mail='.$recipients);
	$good_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?mail='.$recipients.'&success';
	$bad_url=APPLICATION_URL.WebUtility::removeIndex(HERE).'?mail='.$recipients.'&mail_error';
}
elseif(isset($_GET['member']) || isset($_GET['contributor']))
{
	# Get the recipients from the Email class that was instantiated in the Populator.
	$recipients=$email_obj->getRecipients();
}
else
{
	$recipients=$recipients;
}
# Set the email recipient(s). (See data/formmail.ini)
$email_obj->setRecipients($recipients);

$display.='<div id="email_form" class="form">';
# Create and display form
$display.=$head;
# Instantiate a new FormGenerator object.
$form_mail=new FormGenerator('email_form', $fp->getFormAction(), 'POST', $fp->getTarget(), $fp->getUpload(), 'form-email');
$form_mail->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$form_mail->addElement('hidden', array('name'=>'recipients', 'value'=>$email_obj->getRecipients()));
$form_mail->addElement('hidden', array('name'=>'mail_options', 'value'=>'CharSet=UTF-8,'.((isset($html_template)) ? 'HTMLTemplate='.$html_template : ((isset($plain_template)) ? 'PlainTemplate='.$plain_template : 'PlainTemplate=form_email_template.txt')).',FromAddr='.preg_replace('/@/', '_form_', SMTP_USER).',FromLineStyle=QuotedNameRouteAddr,AlwaysList'));
$form_mail->addElement('hidden', array('name'=>'good_url', 'value'=>$good_url));
$form_mail->addElement('hidden', array('name'=>'bad_url', 'value'=>$bad_url));
$form_mail->addElement('hidden', array('name'=>'required', 'value'=>'realname:Name'));
$form_mail->addElement('hidden', array('name'=>'required', 'value'=>'email:E-mail'));
$form_mail->addElement('hidden', array('name'=>'required', 'value'=>'mesg:Message'));
# Set the domain name for the form_email_template.txt to use.
$form_mail->addElement('hidden', array('name'=>'domain_name', 'value'=>DOMAIN_NAME));
$form_mail->addFormPart('<fieldset>');
$form_mail->addFormPart('<ul>');
$form_mail->addFormPart('<li>');
$form_mail->addFormPart('<label class="label" for="realname">Name</label>');
$form_mail->addElement('text', array('id'=>'realname', 'name'=>'realname', 'value'=>$email_obj->getSenderName()));
$form_mail->addFormPart('</li>');
$form_mail->addFormPart('<li>');
$form_mail->addFormPart('<label class="label" for="email">E-mail</label>');
$form_mail->addElement('text', array('id'=>'email', 'name'=>'email', 'value'=>$email_obj->getSenderEmail()));
$form_mail->addFormPart('</li>');
$form_mail->addFormPart('<li>');
$form_mail->addFormPart('<label class="label" for="subject">Subject</label>');
$form_mail->addElement('text', array('id'=>'subject', 'name'=>'subject', 'value'=>$email_obj->getSubject()));
$form_mail->addFormPart('</li>');
if($fp->getUpload()===TRUE)
{
	$max_file_size=$fp->getMaxFileSize();
	$form_mail->addElement('hidden', array('name'=>'MAX_FILE_SIZE', 'value'=>((!empty($max_file_size)) ? $max_file_size : 5242880)));
	$form_mail->addFormPart('<li>');
	$form_mail->addFormPart('<label class="label" for="file">File</label>');
	$form_mail->addElement('file', array('id'=>'file', 'name'=>'file'));
	/*
	if(isset($file_name))
	{
		$form_mail->addFormPart('<ul>');
		$form_mail->addFormPart('<li class="file-current">');
		$form_mail->addFormPart('<a href="'.APPLICATION_URL.'download/?f='.$file_name.'&amp;t=tmp" title="Current Attachment">'.$file_name.'"</a>');
		$form_mail->addElement('hidden', array('name'=>'_file', 'value'=>$file_name));
		$form_mail->addFormPart('</li>');
		$form_mail->addFormPart('</ul>');
	}
	*/
	$form_mail->addFormPart('</li>');
	# Include javascripts for styling the upload field.
	$doc->setJavaScripts('uniform');
	$doc->setFooterJS('uniform-file');
}
$form_mail->addFormPart('<li>');
$form_mail->addFormPart('<label class="label msg" for="mesg">Message</label>');
$form_mail->addElement('textarea', array('id'=>'mesg', 'name'=>'mesg', 'text'=>$email_obj->getMessage()));
$form_mail->addFormPart('</li>');
$form_mail->addFormPart('<li>');
$form_mail->addElement('submit', array('name'=>'send', 'value'=>'Send Email'), '', NULL, 'submit-email');
$form_mail->addFormPart('</li>');
$form_mail->addFormPart('</ul>');
$form_mail->addFormPart('</fieldset>');
$display.=$form_mail->display();
$display.='</div>';