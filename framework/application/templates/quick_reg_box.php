<?php /* framework/application/templates/quick_reg_box.php */

if(WebUtility::removeSchemeName(WebUtility::removeIndex(LOGIN_PAGE.'register/'))!==WebUtility::removeIndex(FULL_URL))
{
	require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');

	try
	{
		$action=REDIRECT_TO_LOGIN.'register/';
		$box3_class='box3';
		$go_button_class='submit-go';
		$display_quick_reg='';
		$common_label='Member Only Content';
		if($login->isLoggedIn()===TRUE)
		{
			$action='http://store.'.DOMAIN_NAME.'/subscriptions/';
			$box3_class.=' in';
			$display_quick_reg.='<section class="'.$box3_class.'">';
				$display_quick_reg.='<h1 class="h-1">';
				$display_quick_reg.=$common_label;
				$display_quick_reg.='</h1>';
				$display_quick_reg.='<a href="'.APPLICATION_URL.'store/subscriptions/" id="go" class="'.$go_button_class.'" title="Get Member Only Content">Go</a>';
			$display_quick_reg.='</section>';
		}
		else
		{
			# Instantiate a new formGenerator object.
			$quick_reg=new formGenerator('register', $action, 'POST', '_top', FALSE, $box3_class, 'box3');
			$quick_reg->addElement('hidden',array('name'=>'_submit_check','value'=>'1'));
			$quick_reg->addFormPart('<fieldset>');
				$quick_reg->addFormPart('<ul>');
					$quick_reg->addFormPart('<li>');
						$quick_reg->addFormPart('<label class="h-1 label" for="emailGo">Register Now!</label>');
						$quick_reg->addElement('email',array('name'=>'email', 'value'=>'youremail@somewhere.com', 'id'=>'emailGo'));
					$quick_reg->addFormPart('</li>');
					$quick_reg->addFormPart('<li>');
						$quick_reg->addFormPart('<label class="h-2 label" for="go">'.$common_label.'</label>');
						 $quick_reg->addElement('submit', array('name'=>'go', 'value'=>'Go', 'id'=>'go'), NULL, NULL, $go_button_class);
					$quick_reg->addFormPart('</li>');
				$quick_reg->addFormPart('</ul>');
			$quick_reg->addFormPart('</fieldset>');
			$display_quick_reg.=$quick_reg->display();
			# Clean up
			$quick_reg=NULL;
		}
		echo $display_quick_reg;
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
}