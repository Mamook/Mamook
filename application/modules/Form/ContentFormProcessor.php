<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the FormValidator Class.
require_once MODULES.'Form'.DS.'FormValidator.php';

# Get the FormProcessor Class.
require_once MODULES.'Form'.DS.'FormProcessor.php';


/**
 * ContentFormProcessor
 *
 * The ContentFormProcessor Class is used to create and process main site content.
 *
 */
class ContentFormProcessor extends FormProcessor
{

	/*** public methods ***/

	/**
	 * processContent
	 *
	 * Processes a submitted content for upload.
	 *
	 * @param		$data			An array of values to populate the form with.
	 * @access	public
	 * @return	string
	 */
	public function processContent($data)
	{
		try
		{
			# Bring the alert-title variable into scope.
			global $alert_title;
			# Set the Database instance to a variable.
			$db=DB::get_instance();
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			# Get the ContentFormPopulator Class.
			require_once MODULES.'Form'.DS.'ContentFormPopulator.php';

			# Remove any un-needed CMS session data. (This needs to happen before populateContentForm is called but AFTER the Populator has been included so that the getCurrentURL method will be available.)
			$this->loseSessionData('content');

			# Reset the form if the "Reset" button was submitted.
			$this->processReset('content');

			# Instantiate a new instance of ContentFormPopulator.
			$populator=new ContentFormPopulator();
			# Populate the form and set the SubContent data members for this post.
			$populator->populateContentForm($data);
			# Set the Populator object to the data member.
			$this->setPopulator($populator);
			# Set the Content object from the ContentFormPopulator object to a local variable.
			$content=$populator->getContentObject();

			# Set the content's id to a variable.
			$id=$content->getID();
			# Set the content's `page_title` to a variable.
			$page_title=$content->getPageTitle(TRUE);
			# Set the content's `sub_title` to a variable.
			$sub_title=$content->getSubTitle();
			# Set the content's `hide_title` to a variable.
			$hide_title=$content->getHideTitle();
			# Set the content's `content` to a variable.
			$text=$content->getText();
			# Set the content's `quote` to a variable.
			$quote=$content->getQuote();
			# Set the content's `topic` to a variable.
			$topic=$content->getTopic();
			# Set the content's `image` to a variable.
			$image=$content->getImage();
			# Set the content's `image_title` to a variable.
			$image_title=$content->getImageTitle();
			# Set the content's `sub_domain` to a variable.
			$sub_domain=$content->getSubDomain();
			# Set the content's `page` to a variable.
			$page=$content->getPage();
			# Set the content's `archive` to a variable.
			$archive=$content->getArchive();
			# Set the content's `social` to a variable.
			$social=$content->getUseSocial();

			# Check if the form has been submitted and the submit button was the "Update" button.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['content']) && $_POST['content']==='Update'))
			{
				# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
				$this->setSession();

				# Redirect the user to the appropriate page if their post data indicates that another form is needed to add content.
				$this->contentRedirect('content');

				# Instantiate FormValidator object
				$fv=new FormValidator();
				# Check if the page_title field was empty (or less than 2 characters or more than 255 characters long).
				$empty_title=$fv->validateEmpty('page_title', 'Please enter a page title for the content.', 2, 255);

				# Check for errors to display so that the script won't go further.
				if($fv->checkErrors()===TRUE)
				{
					# Create a variable to the error heading.
					$alert_title='Resubmit the form after correcting the following errors:';
					# Concatenate the errors to the heading.
					$error=$fv->displayErrors();
					# Set the error message to the Document object datamember so that it me be displayed on the page.
					$doc->setError($error);
				}
				else
				{
					# Check if this is an UPDATE.
					if(!empty($id))
					{
						# Reset the value for the message action.
						$message_action='updated';
						# Reset the sql variable with the UPDATE sql.
						$sql='UPDATE `'.DBPREFIX.'content` SET
							`page_title` = '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?(\n)?)/i", "$1\n\n", str_replace("\r", '', htmlspecialchars_decode($page_title))))).','.
							' `sub_title` = '.((empty($sub_title)) ? 'NULL' : $db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?(\n)?)/i", "$1\n\n", str_replace("\r", '', htmlspecialchars_decode($sub_title)))))).','.
							' `hide_title` = '.(($hide_title===NULL) ? 'NULL' : 0).','.
							' `content` = '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?(\n)?)/i", "$1\n\n", str_replace("\r", '', htmlspecialchars_decode($text))))).','.
							' `quote` = '.$db->quote($db->escape(preg_replace("/<p>(.*?)<\/p>(\n?(\n)?)/i", "$1\n\n", str_replace("\r", '', htmlspecialchars_decode($quote))))).','.
							' `topic` = '.$db->quote($topic).','.
							' `image` = '.((empty($image)) ? 'NULL' : $db->quote($db->escape($image))).','.
							' `image_title` = '.((empty($image_title)) ? 'NULL' : $db->quote($db->escape($image_title))).','.
							' `sub_domain` = '.((empty($sub_domain)) ? 'NULL' : $db->quote($db->escape($sub_domain))).','.
							' `page` = '.((empty($page)) ? 'NULL' : $db->quote($db->escape($page))).','.
							' `archive` = '.(($archive===NULL) ? 'NULL' : 0).','.
							' `social` = '.(($social===NULL) ? 'NULL' : 0).
							' WHERE `id` = '.$db->quote($id).
							' LIMIT 1';
						try
						{
							# Run the sql query.
							$db_post=$db->query($sql);
							# Check if the query was successful.
							if($db_post>0)
							{
								# Remove the content session.
								unset($_SESSION['form']['content']);
								# Set a nice message for the user in a session.
								$_SESSION['message']='The content page was successfully '.$message_action.'!';
								# Redirect the user to the page they were on.
								//$doc->redirect(SECURE_URL.SECURE_HERE.((isset($_GET['content'])) ? '?content='.$_GET['content'] : ''));
							}
							else
							{
								if(!empty($id))
								{
									# Set a nice message for the user in a session.
									$_SESSION['message']='The content\'s record was unchanged.';
								}
							}
						}
						catch(ezDB_Error $ez)
						{
							throw new Exception('There was an error updating the `content` table in the Database: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
						}
					}
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- processContent

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setSession
	 *
	 * Creates a session that holds all the POST data (it will be destroyed if it is not needed.)
	 *
	 * @access	private
	 */
	private function setSession()
	{
		try
		{
			# Get the Populator object and set it to a local variable.
			$populator=$this->getPopulator();
			# Get the Content object and set it to a local variable.
			$content=$populator->getContentObject();

			# Set the form URL's to a variable.
			$form_url=$populator->getFormURL();
			# Set the current URL to a variable.
			$current_url=FormPopulator::getCurrentURL();
			# Check if the current URL is already in the form_url array. If not, add the current URL to the form_url array.
			if(!in_array($current_url, $form_url)) $form_url[]=$current_url;

			# Create a session that holds all the POST data (it will be destroyed if it is not needed.)
			$_SESSION['form']['content']=
				array(
					'ID'=>$content->getID(),
					'FormURL'=>$form_url,
					'PageTitle'=>$content->getPageTitle(),
					'SubTitle'=>$content->getSubTitle(),
					'HideTitle'=>$content->getHideTitle(), # 0=Hide Title | NULL=Don't Hide Title
					'Text'=>$content->getText(),
					'Quote'=>$content->getQuote(),
					'Topic'=>$content->getTopic(), # For the "page-topic" meta tag.
					'Image'=>$content->getImage(),
					'ImageTitle'=>$content->getImageTitle(),
					'SubDomain'=>$content->getSubDomain(),
					'Page'=>$content->getPage(), # NULL if not currently assigned to a page
					'Archive'=>$content->getArchive(), # NULL=Not Archived | 0=Archived
					'UseSocial'=>$content->getUseSocial() # NULL=Don't use Social buttons | 0=Use social buttons
				);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setSession

	/*** End private methods ***/

} # End ContentFormProcessor class.