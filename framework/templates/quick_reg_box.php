<?php /* templates/quick_reg_box.php */

if(WebUtility::removeSchemeName(WebUtility::removeIndex(LOGIN_PAGE.'register/'))!==WebUtility::removeIndex(FULL_URL))
{
	require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');

	try
	{
		$action=REDIRECT_TO_LOGIN.'register/';
		$box3_class='box3';
		if($login->isLoggedIn()===TRUE)
		{
			$action='http://store.'.DOMAIN_NAME.'/subscriptions/';
			$box3_class+=' ssl';
		}
		# Instantiate a new formGenerator object.
		$quick_reg=new formGenerator('register', $action, 'POST', '_top', FALSE, $box3_class, 'box3');
		$quick_reg->addElement('hidden',array('name'=>'_submit_check','value'=>'1'));
		$quick_reg->addFormPart('<fieldset>'."\n");
		$quick_reg->addFormPart('<ul>'."\n");
		if($login->isLoggedIn()!==TRUE)
		{
			$quick_reg->addFormPart('<li>'."\n");
			$quick_reg->addFormPart('<label class="label h" for="emailGo">Register Now!</label>'."\n");
			$quick_reg->addElement('text',array('name'=>'email', 'value'=>'youremail@somewhere.com', 'id'=>'emailGo'));
			$quick_reg->addFormPart('<span id="pointer">>></span>'."\n");
			$quick_reg->addFormPart('</li>');
		}
		$quick_reg->addFormPart('<li>');
		$quick_reg->addFormPart('<label class="label" for="go">Member Only Content</label>');
		if($login->isLoggedIn()!==TRUE)
		{
			$quick_reg->addElement('submit', array('name'=>'go', 'value'=>'Go', 'id'=>'go'), NULL, NULL, 'submit-go');
		}
		else
		{
			$quick_reg->addFormPart('<a href="'.APPLICATION_URL.'store/subscriptions/" id="go" class="submit-go" title="Get Member Only Content">Go</a>');
		}
		$quick_reg->addFormPart('</li>');
		$quick_reg->addFormPart('</ul>');
		$quick_reg->addFormPart('</fieldset>');
		echo $quick_reg->display();
		# Clean up
		$quick_reg=NULL;
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
}