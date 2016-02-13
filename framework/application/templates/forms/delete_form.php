<?php /* framework/application/templates/forms/delete_form.php */

if(isset($_GET['audio'])) $text_var='audio';
if(isset($_GET['category'])) $text_var='category';
if(isset($_GET['file'])) $text_var='file';
if(isset($_GET['image'])) $text_var='image';
if(isset($_GET['institution'])) $text_var='institution';
if(isset($_GET['language'])) $text_var='language';
if(isset($_GET['post'])) $text_var='post';
if(isset($_GET['product'])) $text_var='product';
if(isset($_GET['publisher'])) $text_var='publisher';
if(isset($_GET['video'])) $text_var='video';

$display='<div id="delete_form" class="form">';
if(!isset($text_var))
{
	$_SESSION['message']='You can\'t delete this item';
}
else
{
	$display.='<h3>Are you sure you want to delete this '.$text_var.'? It will be permanently removed from the system along with anything associated with it (audio, file, image, post, video, product).</h3>';
	$display.='<p>You may also use the form below to edit the '.$text_var.'.</p>';
	# Instantiate form generator object.
	$d_form=new FormGenerator('delete');
	$d_form->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
	$d_form->addFormPart('<fieldset>');
	$d_form->addFormPart('<ul>');
	$d_form->addFormPart('<li>');
	$d_form->addElement('radio', array('name'=>'delete_'.$text_var, 'value'=>'delete', 'id'=>'delete'.ucfirst($text_var)));
	$d_form->addFormPart('<label class="box_label" for="delete'.ucfirst($text_var).'">Yes, delete this '.$text_var.'.</label>');
	$d_form->addFormPart('</li>');
	$d_form->addFormPart('<li>');
	$d_form->addElement('radio', array('name'=>'delete_'.$text_var, 'value'=>'keep', 'id'=>'keep'.ucfirst($text_var)));
	$d_form->addFormPart('<label class="box_label" for="keep'.ucfirst($text_var).'">No, do NOT delete this '.$text_var.'.</label>');
	$d_form->addFormPart('</li>');
	$d_form->addFormPart('<li>');
	$d_form->addElement('submit', array('name'=>'do_not', 'value'=>'Do NOT Delete'), '', NULL, 'submit-delete');
	$d_form->addElement('submit', array('name'=>'do', 'value'=>'Delete '.ucfirst($text_var)), '', NULL, 'submit-delete');
	$d_form->addFormPart('</li>');
	$d_form->addFormPart('</ul>');
	$d_form->addFormPart('</fieldset>');
	$display.=$d_form->display();
}
$display.='</div>';