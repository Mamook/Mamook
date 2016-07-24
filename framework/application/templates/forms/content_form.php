<?php /* framework/application/templates/forms/content_form.php */

require Utility::locateFile(TEMPLATES.'forms'.DS.'content_form_defaults.php');
$fp->processContent($default_data);

# Set the ContentFormPopulator object from the ContentFormProcessor data member to a variable.
$populator=$fp->getPopulator();
# Set the Content object from the ContentFormPopulator data member to a variable.
$content=$populator->getContentObject();

if(isset($_GET['content']))
{
	if(empty($display))
	{
		# Do we need some javascripts? (Use the script content name before the ".js".)
		$doc->setJavaScripts('uniform');
		# Do we need some JavaScripts in the footer? (Use the script content name before the ".php".)
		$doc->setFooterJS('uniform-select,fileOption-submit');

		# Set the page title name to a variable.
		$content_page_title=$content->getPageTitle(TRUE);
		# Check if this is an edit or delete page.
		if(isset($_GET['content']))
		{
			# Set the page's subtitle as an edit page.
			$sub_title='Edit <span>"'.$content_page_title.'"</span>';
			# Check if this is a delete page.
			if(isset($_GET['delete']))
			{
				# Set the page's subtitle as a delete page.
				$sub_title='Delete <span>"'.$content_page_title.'"</span>';
			}
			# Set the sub title.
			$main_content->setSubTitle($sub_title);
		}

		$text=$content->getText(TRUE);
		//$text=str_ireplace('%{domain_name}', DOMAIN_NAME, $content->getText(TRUE));
		$text=str_ireplace(array('<br />','&lt;br /&gt;'), '', $text);

		$image_options[0]='';
		$image_options['select']='Select Existing Image (submit this form to select a image from the database)';
		$image_options['add']='Upload Image (submit this form to select and upload your image)';
		# Set the image name in the Content data member to a variable.
		$image_name=$content->getImage();
		if(!empty($image_name))
		{
			$image_options['remove']='Remove Current Image (submit this form to remove this image)';
		}

		$display.='<div id="content_form" class="form">';

		# create and display form.
		$display.=$head;

		# Add the statement about requirements.
		$display.='<span class="required">* = required field</span>';

		# Instantiate a new FormGenerator object.
		$fg=new FormGenerator('content', $fp->getFormAction(), 'POST', '_top', TRUE);
		$fg->addElement('hidden', array('name'=>'_submit_check', 'value'=>'1'));
		$fg->addFormPart('<fieldset>');
		$fg->addFormPart('<ul>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="page_title"><span class="required">*</span> Page Title</label>');
		$fg->addElement('text', array('name'=>'page_title', 'id'=>'page_title', 'value'=>$content->getPageTitle(TRUE)));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="sub_title">Sub Title</label>');
		$fg->addElement('text', array('name'=>'sub_title', 'id'=>'sub_title', 'value'=>$content->getSubTitle(TRUE)));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		# Get whether or not the page title should be hidden and set it to a variable.
		$hide_title=$content->getHideTitle();
		# Make the hide value digestible to the form.
		$hide_title=(($hide_title===NULL) ? '' : 'hide_title');
		$fg->addFormPart('<label class="label" for="hide_title">Hide Title</label>');
		$fg->addElement('checkbox', array('name'=>'hide_title', 'value'=>'hide_title', 'id'=>'hide_title', 'checked'=>$hide_title));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="text">Text</label>');
		$fg->addElement('textarea', array('name'=>'text', 'id'=>'text', 'text'=>$text), '', NULL, 'textarea tinymce');
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="quote">Quote</label>');
		$fg->addElement('text', array('name'=>'quote', 'id'=>'quote', 'value'=>$content->getQuote(TRUE)));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="topic">Topic</label>');
		$fg->addElement('text', array('name'=>'topic', 'id'=>'topic', 'value'=>$content->getTopic()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="imageOption">Image</label>');
		$fg->addElement('select', array('name'=>'image_option', 'id'=>'imageOption'), $image_options, NULL, 'select');
		if(!empty($image_name))
		{
			$image_title=$content->getImageTitle();
			$fg->addFormPart('<ul>');
			$fg->addFormPart('<li class="file-current">');
			$fg->addFormPart('<a href="'.IMAGES.'original/'.$image_name.'" title="Current Image" rel="'.FW_POPUP_HANDLE.'"><img src="'.IMAGES.$image_name.'" alt="'.$content->getImageTitle().'" /><span>'.$image_name.((empty($image_title)) ? '' : ' - "'.$content->getImageTitle().'"').'</span></a>');
			$fg->addElement('hidden', array('name'=>'_image', 'value'=>$image_name));
			$fg->addFormPart('</li>');
			$fg->addFormPart('</ul>');
		}
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="image_title">Image Title</label>');
		$fg->addElement('text', array('name'=>'image_title', 'id'=>'image_title', 'value'=>$content->getImageTitle()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="sub_domain">Sub Domain</label>');
		$fg->addElement('text', array('name'=>'sub_domain', 'id'=>'sub_domain', 'value'=>$content->getSubDomain()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addFormPart('<label class="label" for="page">Page</label>');
		$fg->addElement('text', array('name'=>'page', 'id'=>'page', 'value'=>$content->getPage()));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		# Get whether or not the page should be archived and set it to a variable.
		$archive=$content->getArchive();
		# Make the hide value digestible to the form.
		$archive=(($archive===NULL) ? '' : 'archive');
		$fg->addFormPart('<label class="label" for="archive">Archive</label>');
		$fg->addElement('checkbox', array('name'=>'archive', 'value'=>'archive', 'id'=>'archive', 'checked'=>$archive));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		# Get whether or not the page should use social buttons and set it to a variable.
		$social=$content->getUseSocial();
		# Make the social value digestible to the form.
		$social=(($social===NULL) ? '' : 'social');
		$fg->addFormPart('<label class="label" for="social">Use Social</label>');
		$fg->addElement('checkbox', array('name'=>'social', 'value'=>'social', 'id'=>'social', 'checked'=>$social));
		$fg->addFormPart('</li>');
		$fg->addFormPart('<li>');
		$fg->addElement('submit', array('name'=>'content', 'value'=>'Update'), '', NULL, 'submit-content');
		$fg->addElement('submit', array('name'=>'content', 'value'=>'Reset'), '', NULL, 'submit-reset');
		$fg->addFormPart('</li>');
		$fg->addFormPart('</ul>');
		$fg->addFormPart('</fieldset>');
		$display.=$fg->display();
		$display.='</div>';
	}
}
$display.=$content->displayContentList();