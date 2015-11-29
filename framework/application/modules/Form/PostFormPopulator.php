<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the FormPopulator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormPopulator.php');


/**
 * PostFormPopulator
 *
 * The PostFormPopulator Class is used populate post forms.
 *
 */
class PostFormPopulator extends FormPopulator
{
	/*** data members ***/

	private $subcontent_object=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setSubContentObject
	 *
	 * Sets the data member $subcontent_object.
	 *
	 * @param		$object
	 * @access	private
	 */
	private function setSubContentObject($object)
	{
		# Check if the passed value is empty and an object.
		if(empty($object) OR !is_object($object))
		{
			# Explicitly set the value to NULL.
			$object=NULL;
		}
		# Set the data member.
		$this->subcontent_object=$object;
	} #==== End -- setSubContentObject

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getSubContentObject
	 *
	 * Returns the data member $subcontent_object.
	 *
	 * @access	public
	 */
	public function getSubContentObject()
	{
		return $this->subcontent_object;
	} #==== End -- getSubContentObject

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * populatePostForm
	 *
	 * Populates a post form with content from the Subcontent table in the Databse using the id passed via GET data.
	 *
	 * @param	$data					An array of values to populate the form with.
	 * @access	public
	 */
	public function populatePostForm($data=array())
	{
		try
		{
			# Get the SubContent class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');
			# Instantiate a new SubContent object.
			$subcontent=new SubContent();
			# Set the SubContent object to the data member.
			$this->setSubContentObject($subcontent);

			# Set the passed data array to the data member.
			$this->setData($data);

			# Process any post data held in SESSION and set it to the data data member. This overwrites any passed data.
			$this->setSessionDataToDataArray('post');
			# Remove any "content" sessions.
			unset($_SESSION['form']['content']);
			# Remove any "product" sessions.
			unset($_SESSION['form']['product']);

			# Set any POST values to the appropriate data array indexes.
			$this->setPostDataToDataArray();

			# Populate the data members with defaults, passed values, or data saved in SESSION.
			$this->setDataToDataMembers($this->getSubContentObject());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- populatePostForm

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * setPostDataToDataArray
	 *
	 * If there are new post data values from POST data, they are set to the appropriate data
	 * member (PostFormPopulator or SubContent).
	 *
	 * @access	private
	 */
	private function setPostDataToDataArray()
	{
		try
		{
			# Check if the form has been submitted.
			if(array_key_exists('_submit_check', $_POST) && (isset($_POST['post']) && (($_POST['post']==='Post') OR ($_POST['post']==='Update'))))
			{
				# Set the Validator instance to a variable.
				$validator=Validator::getInstance();
				# Set the data array to a local variable.
				$data=$this->getData();
				# Set the SubContent object to a local variable.
				$subcontent=$this->getSubContentObject();

				# Check if availability was passed via POST data.
				if(isset($_POST['availability']))
				{
					# Set the availability data array.
					$data['Availability']=$_POST['availability'];
				}

				# Create an empty variable for the record branches.
				$record_branches=NULL;
				if(isset($_POST['branch']))
				{
					# Get all the branches from the `branches` table.
					$subcontent->getBranches();
					# Loop through the returned branches.
					foreach($subcontent->getAllBranches() as $row)
					{
						# Create a variable using the branch name.
						$branch_name_variable=str_replace(' ', '_', strtolower($row->branch));
						# Check if POST data was passed for this branch.
						if(in_array($row->id, $_POST['branch']))
						{
							# Add it to the record branches.
							$record_branches.=$row->id.'-';
						}
					}
				}
				# Set the record_branches to the data array.
				$data['RecordBranches']='-'.$record_branches;

				# Check if the hidden input value for contributor is available.
				if(isset($_POST['_contributor']))
				{
					# Set the passed contributor's id to the data array.
					$data['ContID']=$_POST['_contributor'];
				}

				# Explicitly make the month an integer.
				$month=(int)$_POST['month'];
				$month=((strlen($month)==1) ? '0'.$month : $month);
				# Explicitly make the day an integer.
				$day=(int)$_POST['day'];
				$day=((strlen($day)==1) ? '0'.$day : $day);
				# Explicitly make the year an integer.
				$year=(int)$_POST['year'];
				# Concatenate the month, day, year into the proper date format and set it to the SubContent data member.
				$data['Date']=$year.'-'.$month.'-'.$day;

				# If the Facebook POST data was sent and the value is "post" set 'post' to the data array.
				if(isset($_POST['facebook']) && ($_POST['facebook']==='post'))
				{
					# Set the Facebook value to the data array as "post".
					$data['Facebook']='post';
				}
				else
				{
					# Set the Facebook value to 0 in the data array.
					$data['Facebook']=NULL;
				}

				# Check if the file id POST data was sent.
				if(isset($_POST['_file_id']))
				{
					# Set the file id to the data array.
					$data['FileID']=$_POST['_file_id'];
				}

				# Check if the file option POST data was sent.
				if(isset($_POST['file_option']))
				{
					# Set the file option to the data array.
					$data['FileOption']=$_POST['file_option'];
				}

				# If the hide POST data was sent and the value is "hide" set 0 to the variable, otherwise set NULL.
				if(isset($_POST['hide']) && ($_POST['hide']==='hide'))
				{
					# Set the hide value to the data array.
					$data['Hide']=0;
				}
				else
				{
					$data['Hide']=NULL;
				}

				# Check if the image id POST data was sent.
				if(isset($_POST['_image_id']))
				{
					# Set the image id to the data array.
					$data['ImageID']=$_POST['_image_id'];
				}

				# Check if the image option POST data was sent.
				if(isset($_POST['image_option']) && !empty($_POST['image_option']))
				{
					# Set the image option to the data array.
					$data['ImageOption']=$_POST['image_option'];
				}

				# Check if the institution id POST data was sent.
				if(isset($_POST['institution']))
				{
					# Check if the institution option POST data was sent.
					if($_POST['institution']==='add')
					{
						# Set the institution option to the data array.
						$data['InstitutionOption']='add';
					}
					else
					{
						# Set the institution id to the data array.
						$data['InstitutionID']=$_POST['institution'];
					}
				}

				# Check if the link POST data was sent.
				if(isset($_POST['link']))
				{
					# Set the link to the data array.
					$data['Link']=$_POST['link'];
				}

				# If the premium POST data was sent and the value is "premium" set 0 to the variable, otherwise set NULL.
				if(isset($_POST['premium']) && ($_POST['premium']=='premium'))
				{
					# Set the premium value to the data array.
					$data['Premium']=0;
				}
				else
				{
					$data['Premium']=NULL;
				}

				# Check if the publisher id POST data was sent.
				if(isset($_POST['publisher']))
				{
					# Check if the publisher option POST data was sent.
					if($_POST['publisher']==='add')
					{
						# Set the publisher option to the data array.
						$data['PublisherOption']='add';
					}
					else
					{
						# Set the publisher id to the data array.
						$data['PublisherID']=$_POST['publisher'];
					}
				}

				# Check if text POST data was sent.
				if(isset($_POST['text']))
				{
					# Set the text to the data array.
					$data['Text']=$_POST['text'];
				}

				# Check if the language option POST data was sent.
				if($_POST['text_language']==='add')
				{
					# Set the language option to the data array.
					$data['LanguageOption']='add';
				}
				else
				{
					# Set the text language to the data array.
					$data['TextLanguage']=$_POST['text_language'];
				}

				# Check if text translation POST data was sent.
				if(isset($_POST['text_trans']))
				{
					# Set the text translation to the data array.
					$data['TextTrans']=$_POST['text_trans'];
				}

				# Check if the language option POST data was sent.
				if($_POST['trans_language']==='add')
				{
					# Set the language option to the data array.
					$data['LanguageOption']='add';
				}
				else
				{
					# Set the text translation language id to the data array.
					$data['TransLanguage']=$_POST['trans_language'];
				}

				# Check if title POST data was sent.
				if(isset($_POST['title']))
				{
					# Set the title to the data array.
					$data['Title']=$_POST['title'];
				}

				# If the Twitter POST data was sent and the value is "tweet" set 0 to the variable, otherwise set NULL.
				if(isset($_POST['twitter']) && ($_POST['twitter']==='tweet'))
				{
					# Set the Twitter value to the data array as "tweet".
					$data['Twitter']='tweet';
				}
				else
				{
					# Set the Twitter value to 0 in the data array.
					$data['Twitter']=NULL;
				}

				# Check if the unique POST data was sent.
				if(isset($_POST['_unique']))
				{
					$unique='1';
					if(empty($_POST['_unique']))
					{
						$unique='0';
					}
					# Set the unique value to the data array.
					$data['Unique']=$unique;
				}

				# Check if the visibility POST data was sent.
				if(isset($_POST['visibility']))
				{
					# Create a variable to hold the visibility value with a default of NULL (visible to all users.)
					$visibility=NULL;
					# Check if the value is "members".
					switch($_POST['visibility'])
					{
						case 'members':
							$visibility=0;
							break;
						case 'all_users':
							$visibility=NULL;
							break;
						default:
						# Validate the value as an integer.
						if($validator->isInt($_POST['visibility'])===TRUE)
						{
							$visibility='-'.$_POST['visibility'].'-';
						}
					}
					# Set the visibility value to the data array.
					$data['Visibility']=$visibility;
				}
				# Reset the data array to the data member.
				$this->setData($data);
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setPostDataToDataArray

	/*** End private methods ***/

} # End PostFormPopulator class.