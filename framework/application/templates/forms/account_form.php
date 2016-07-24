<?php /* framework/application/templates/forms/account_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'account_form_defaults.php');
$display_delete_form=$form_processor->processAccount($default_data);

# Set the AccountFormPopulator object from the AccountFormProcessor data member to a variable.
$populator=$form_processor->getPopulator();
# Set the Staff object from the AccountFormPopulator data member to a variable.
$user_obj=$populator->getUserObject();

# Do we need some javascripts? (Use the script video name before the ".js".)
$doc->setJavaScripts('uniform,bsmSelect');
# Add JavaScripts to the footer. (Use the script file name before the ".php".)
# This form needs uniform-file, fileOption-submit, bsmSelect-multiple, uniform-select, and removeCurrentItem. uniform-select MUST come after bsmSelect-multiple.
$doc->setFooterJS('uniform-file,fileOption-submit,bsmSelect-multiple,uniform-select,removeCurrentItem');

$display.='<div id="profile_form" class="form">';
# create and display form
$display.=$head;
# Add the statement about requirements.
$display.='<span class="required">* = required field</span>';
# Instantiate a new FormGenerator object.
$fg=new FormGenerator('account', NULL, 'POST', '_top', TRUE);
$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
$fg->addElement('hidden', array('name'=>'_unique', 'value'=>(string)$populator->getUnique()));
$fg->addFormPart('<fieldset>');
$fg->addFormPart('<h4>Name:</h4>');
$fg->addFormPart('<ul>');
# Check if there is a WordPress installation.
if(WP_INSTALLED===TRUE)
{
	$fg->addFormPart('<li>');
	$fg->addFormPart('<label class="label" for="nickname"><span class="required">*</span> Nickname</label>');
	$fg->addElement('text', array('name'=>'nickname', 'value'=>$user_obj->getNickname(), 'id'=>'nickname'));
	$fg->addFormPart('</li>');
}
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="title">Title</label>');
$fg->addElement('text', array('name'=>'title', 'value'=>$user_obj->getTitle(), 'id'=>'title'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="fname"><span class="required">*</span> First Name</label>');
$fg->addElement('text', array('name'=>'fname', 'value'=>$user_obj->getFirstName(), 'id'=>'fname'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="lname"><span class="required">*</span> Last Name</label>');
$fg->addElement('text', array('name'=>'lname', 'value'=>$account_last_name, 'id'=>'lname'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="display"><span class="required">*</span> Display name publicly as</label>');
$fg->addElement('text', array('name'=>'display', 'value'=>$user_obj->getDisplayName(), 'id'=>'display'));
$fg->addFormPart('</li>');
$fg->addFormPart('</fieldset>');
$fg->addFormPart('<fieldset>');
$fg->addFormPart('<h4>Contact Info:</h4>');
$fg->addFormPart('<ul>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="address">Address</label>');
$fg->addElement('text', array('name'=>'address', 'value'=>$user_obj->getAddress(), 'id'=>'address'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="address2">Address 2</label>');
$fg->addElement('text', array('name'=>'address2', 'value'=>$user_obj->getAddress2(), 'id'=>'address2'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="city">City</label>');
$fg->addElement('text', array('name'=>'city', 'value'=>$user_obj->getCity(), 'id'=>'city'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="state">State</label>');
$fg->addElement('text', array('name'=>'state', 'value'=>$user_obj->getState(), 'id'=>'state'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="country">Country</label>');
$fg->addElement('text', array('name'=>'country', 'value'=>$user_obj->getCountry(), 'id'=>'country'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="zipcode">Zipcode</label>');
$fg->addElement('text', array('name'=>'zipcode', 'value'=>$user_obj->getZipcode(), 'id'=>'zipcode'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="phone">Phone Number</label>');
$fg->addElement('text', array('name'=>'phone', 'value'=>$user_obj->getPhone(), 'id'=>'phone'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="email"><span class="required">*</span> Email</label>');
$fg->addElement('text', array('name'=>'email', 'value'=>$user_obj->getEmail(), 'id'=>'email'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="website">Website</label>');
$fg->addElement('text', array('name'=>'website', 'value'=>$user_obj->getWebsite(), 'id'=>'website'));
$fg->addFormPart('</li>');
$fg->addFormPart('</ul>');
$fg->addFormPart('</fieldset>');
$fg->addFormPart('<fieldset>');
$fg->addFormPart('<h4>About Yourself:</h4>');
$fg->addFormPart('<ul>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="cv">Curriculum Vitae (CV)</label>');
$fg->addElement('file', array('name'=>'cv', 'id'=>'cv'));
$cv=$user_obj->getCV();
if(!empty($cv))
{
	$fg->addFormPart('<div class="file-current">');
	$fg->addFormPart('<a href="'.DOWNLOADS.'?f='.$cv.'&t=cv" title="Download your current cv">'.$cv.'</a>');
	$fg->addElement('hidden', array('name'=>'_cv_current', 'value'=>$cv));
	$fg->addFormPart('<a class="remove" href="?removeFile='.$cv.'" title="Remove your current cv.">X</a>');
	$fg->addFormPart('</div>');
}
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="interests">Interests</label>');
$fg->addElement('text', array('name'=>'interests', 'value'=>$user_obj->getInterests(), 'id'=>'interests'));
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$use_html=NULL;
if($login->checkAccess(ALL_ADMIN_MAN)===TRUE)
{
	$use_html='<p>(You may use (x)html)</p>';
}
$fg->addFormPart('<label class="label" for="bio">Biographical info'.$use_html.'</label>');
$fg->addElement('textarea', array('name'=>'bio', 'text'=>$user_obj->getBio(), 'id'=>'bio'), '', NULL, 'textarea');
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="image">Image</label>');
$img=$user_obj->getImg();
$fg->addElement('file', array('name'=>'image', 'value'=>$img, 'id'=>'image'));
if(!empty($img))
{
	$fg->addFormPart('<div class="file-current">');
	$fg->addFormPart('<a href="'.IMAGES.'original/'.$img.'" title="Current Image" rel="'.FW_POPUP_HANDLE.'"><img src="'.IMAGES.$img.'" alt="'.$user_obj->getImgTitle().'" /><span>'.$img.' - "'.$user_obj->getImgTitle().'"</span></a>');
	$fg->addElement('hidden', array('name'=>'_image_current', 'value'=>$img));
	$fg->addFormPart('<a class="remove" href="?removeFile='.$img.'" title="Remove your current profile image.">X</a>');
	$fg->addFormPart('</div>');
}
$fg->addFormPart('</li>');
$fg->addFormPart('<li>');
$fg->addFormPart('<label class="label" for="imgTitle">Image Caption</label>');
$fg->addElement('text', array('name'=>'img_title', 'value'=>$user_obj->getImgTitle(), 'id'=>'imgTitle'));
$fg->addFormPart('</li>');
$fg->addFormPart('<ul>');
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
$display=$display_delete_form.$display;