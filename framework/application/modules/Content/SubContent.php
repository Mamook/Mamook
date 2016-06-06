<?php /* framework/application/modules/Content/SubContent.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the parent Content class.
require_once Utility::locateFile(MODULES.'Content'.DS.'Content.php');


/**
 * SubContent
 *
 * The SubContent Class is used access and maintain the `subcontent` table in the database.
 *
 */
class SubContent extends Content
{
	/*** data members ***/

	private $all_audio=NULL;
	private $all_contributors=NULL;
	private $all_files=NULL;
	private $all_images=NULL;
	private $all_institutions=NULL;
	private $all_languages=NULL;
	private $all_publishers=NULL;
	private $all_subcontent=NULL;
	private $all_videos=NULL;
	private $audio_obj=NULL;
	private $audio_id=NULL;
	private $availability;
	private $all_branches=NULL;
	private $branch=NULL;
	private $branch_id=NULL;
	private $branch_domain=NULL;
	private $branch_where_sql=NULL;
	private $contributor=NULL;
	private $cont_id=NULL;
	protected $file=NULL;
	private $file_id=NULL;
	private $file_info_display=array('header'=>NULL, 'name'=>NULL, 'title'=>NULL, 'author'=>NULL, 'publisher'=>NULL, 'language'=>NULL, 'year'=>NULL, 'location'=>NULL, 'contributor'=>NULL, 'date'=>NULL, 'recent_contributor'=>NULL, 'last_edit'=>NULL, 'all'=>NULL);
	private $hide;
	private $image_obj=NULL;
	private $image_id=NULL;
	private $institution=NULL;
	private $institution_id=NULL;
	private $language=NULL;
	private $language_id=NULL;
	private $language_iso=NULL;
	private $link=NULL;
	private $last_edit='0000-00-00';
	private $more='more';
	private $post_title_display=NULL;
	private $premium;
	private $publisher=NULL;
	private $publisher_id=NULL;
	private $recent_cont_id=NULL;
	private $record_branches=NULL;
	private $text_language=NULL;
	private $text_language_iso=NULL;
	private $trans_language=NULL;
	private $trans_language_iso=NULL;
	private $text_trans=NULL;
	private $title=NULL;
	private $user=NULL;
	private $video_obj=NULL;
	private $video_id=NULL;
	private $visibility;
	private $wanted_branches=NULL;

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setAllSubContent
	 *
	 * Sets the data member $all_subcontent.
	 *
	 * @param	$all_subcontent
	 * @access	public
	 */
	public function setAllSubContent($all_subcontent)
	{
		# Check if the passed value is empty.
		if(!empty($all_subcontent))
		{
			# Explicitly make it an array.
			$all_subcontent=(array)$all_subcontent;
			# Set the data member.
			$this->all_subcontent=$all_subcontent;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_subcontent=NULL;
		}
	} #==== End -- setAllSubContent

	/**
	 * setAllAudio
	 *
	 * Sets the data member $all_audio.
	 *
	 * @param	$all_audio
	 * @access	protected
	 */
	protected function setAllAudio($all_audio)
	{
		# Set the data member.
		$this->all_audio=$all_audio;
	} #==== End -- setAllAudio

	/**
	 * setAudioObj
	 *
	 * Sets the data member $audio_obj.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setAudioObj($object)
	{
		# Set the data member.
		$this->audio_obj=$object;
	} #==== End -- setAudioObj

	/**
	 * setAudioID
	 *
	 * Sets the data member $audio_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setAudioID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
			}
			elseif($id!=='add' && $id!=='select' && $id!=='remove')
			{
				throw new Exception('The passed audio id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->audio_id=$id;
	} #==== End -- setAudioID

	/**
	 * setAvailability
	 *
	 * Sets the data member $availability.
	 *
	 * @param	$availability
	 * @access	public
	 */
	public function setAvailability($availability)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $availability is empty.
		if(!empty($availability))
		{
			# Check if the passed $availability is an integer.
			if($validator->isInt($availability)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->availability=(int)$availability;
			}
			else
			{
				throw new Exception('The passed subcontent availability was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->availability=NULL;
		}
	} #==== End -- setAvailability

	/**
	 * setAllBranches
	 *
	 * Sets the data member $all_branches.
	 *
	 * @param	$branches
	 * @access	protected
	 */
	protected function setAllBranches($branches)
	{
		$this->all_branches=$branches;
	} #==== End -- setAllBranches

	/**
	 * setBranch
	 *
	 * Sets the data member $branch.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setBranch($object)
	{
		# Set the data member.
		$this->branch=$object;
	} #==== End -- setBranch

	/**
	 * setBranchID
	 *
	 * Sets the data member $branch_id.
	 *
	 * @param	$id
	 * @access	protected
	 */
	protected function setBranchID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->branch_id=(int)$id;
			}
			else
			{
				throw new Exception('The passed branch id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->branch_id=NULL;
		}
	} #==== End -- setBranchID

	/**
	 * setRecordBranches
	 *
	 * Sets the data member $record_branches.
	 *
	 * @param	$branches
	 * @access	public
	 */
	public function setRecordBranches($branches)
	{
		# Set the data member.
		$this->record_branches=$branches;
	} #==== End -- setRecordBranches

	/**
	 * setWantedBranches
	 *
	 * Sets the data member $wanted_branches.
	 *
	 * @param	$branches
	 * @access	public
	 */
	public function setWantedBranches($branches)
	{
		$this->wanted_branches=$branches;
	} #==== End -- setWantedBranches

	/**
	 * setAllContributors
	 *
	 * Sets the data member $all_contributors.
	 *
	 * @param	$contributors
	 * @access	protected
	 */
	protected function setAllContributors($contributors)
	{
		# Set the data member.
		$this->all_contributors=$contributors;
	} #==== End -- setAllContributors

	/**
	 * setContributor
	 *
	 * Sets the data member $contributor.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setContributor($object)
	{
		# Set the data member.
		$this->contributor=$object;
	} #==== End -- setContributor

	/**
	 * setContID
	 *
	 * Sets the data member $cont_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setContID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer and set the data member.
				$this->cont_id=(int)$id;
			}
			else
			{
				throw new Exception('The passed contributor id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->cont_id=NULL;
		}
	} #==== End -- setContID

	/**
	 * setRecentContID
	 *
	 * Sets the data member $recent_cont_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setRecentContID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer and set the data member.
				$this->recent_cont_id=(int)$id;
			}
			else
			{
				throw new Exception('The passed recent contributor id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->recent_cont_id=NULL;
		}
	} #==== End -- setRecentContID

	/**
	 * setLastEdit
	 *
	 * Sets the data member $last_edit.
	 *
	 * @param	$date
	 * @access	public
	 */
	public function setLastEdit($date)
	{
		# Check if the passed value is empty.
		if(!empty($date) && ($date!=='0000-00-00'))
		{
			# Explode the date into an array casting each as an integer.
			$date=explode('-', $date);
			$year=(int)$date[0];
			$month=(int)$date[1];
			$day=(int)$date[2];
			if(checkdate($month, $day, $year)===TRUE)
			{
				# Make sure the day is the correct length.
				if(strlen($day)!=2)
				{
					$day='0'.$day;
				}
				# Make sure the month is the correct length.
				if(strlen($month)!=2)
				{
					$month='0'.$month;
				}
				# Put the date back together in the correct format.
				$date=$year.'-'.$month.'-'.$day;
				# Set the data member.
				$this->last_edit=$date;
			}
			else
			{
				throw new Exception('The passed last edit date was not an acceptable date.', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->last_edit='0000-00-00';
		}
	} #==== End -- setLastEdit

	/**
	 * setAllFiles
	 *
	 * Sets the data member $all_files.
	 *
	 * @param	$files
	 * @access	protected
	 */
	protected function setAllFiles($files)
	{
		# Set the data member.
		$this->all_files=$files;
	} #==== End -- setAllFiles

	/**
	 * setFile
	 *
	 * Sets the data member $file.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setFile($object)
	{
		# Check if the passed value is empty and an object.
		if(!empty($object) && is_object($object))
		{
			$this->file=$object;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->file=NULL;
		}
	} #==== End -- setFile

	/**
	 * setFileID
	 *
	 * Sets the data member $file_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setFileID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
			}
			elseif($id!=='add' && $id!=='select' && $id!=='remove')
			{
				throw new Exception('The passed file id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->file_id=$id;
	} #==== End -- setFileID

	/**
	 * setFileInfoDisplay
	 *
	 * Sets the data member $file_info_display.
	 *
	 * @param	$file_info_display
	 * @access	protected
	 */
	protected function setFileInfoDisplay($file_info_display)
	{
		# Check if the passed value is empty.
		if(!empty($file_info_display))
		{
			# Clean it up.
			$file_info_display=$file_info_display;
			# Set the data member.
			$this->file_info_display=$file_info_display;
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->file_info_display=array('header'=>NULL, 'name'=>NULL, 'title'=>NULL, 'author'=>NULL, 'publisher'=>NULL, 'language'=>NULL, 'year'=>NULL, 'location'=>NULL, 'contributor'=>NULL, 'all'=>NULL);
		}
	} #==== End -- setFileInfoDisplay

	/**
	 * setHide
	 *
	 * Sets the data member $hide.
	 *
	 * @param	$hide
	 * @access	public
	 */
	public function setHide($hide)
	{
		# Check if the passed value is not NULL.
		if($hide!==NULL)
		{
			# Explicitly set the data member to 0.
			$this->hide=0;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->hide=NULL;
		}
	} #==== End -- setHide

	/**
	 * setID
	 *
	 * Sets the data member $id.
	 * This is a wrapper method for setID of the parent class.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setID($id, $class='SubContent')
	{
		# Set the data member using the parent class method.
		parent::setID($id, $class);
	} #==== End -- setID

	/**
	 * setAllImages
	 *
	 * Sets the data member $images.
	 *
	 * @param	$images
	 * @access	protected
	 */
	protected function setAllImages($images)
	{
		# Set the data member.
		$this->all_images=$images;
	} #==== End -- setAllImages

	/**
	 * setImageObj
	 *
	 * Sets the data member $image_obj.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setImageObj($object)
	{
		# Set the data member.
		$this->image_obj=$object;
	} #==== End -- setImageObj

	/**
	 * setImageID
	 *
	 * Sets the data member $image_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setImageID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
			}
			elseif($id!=='add' && $id!=='select' && $id!=='remove')
			{
				throw new Exception('The passed image id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->image_id=$id;
	} #==== End -- setImageID

	/**
	 * setAllInstitutions
	 *
	 * Sets the data member $all_institutions.
	 *
	 * @param	$institutions
	 * @access	protected
	 */
	protected function setAllInstitutions($institutions)
	{
		$this->all_institutions=$institutions;
	} #==== End -- setAllInstitutions

	/**
	 * setInstitution
	 *
	 * Sets the data member $institution.
	 *
	 * @param	$institution
	 * @access	protected
	 */
	protected function setInstitution($institution)
	{
		# Check if the passed value is empty.
		if(!empty($institution))
		{
			# Check if the passed value is an object.
			if(!is_object($institution))
			{
				# Clean it up.
				$institution=trim($institution);
			}
			# Set the data member.
			$this->institution=$institution;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->institution=NULL;
		}
	} #==== End -- setInstitution

	/**
	 * setInstitutionID
	 *
	 * Sets the data member $institution_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setInstitutionID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer and set the data member.
				$id=(int)$id;
			}
			elseif($id!=='add')
			{
				throw new Exception('The passed institution id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->institution_id=$id;
	} #==== End -- setInstitutionID

	/**
	 * setAllLanguages
	 *
	 * Sets the data member $all_languages.
	 *
	 * @param	$languages
	 * @access	protected
	 */
	protected function setAllLanguages($languages)
	{
		$this->all_languages=$languages;
	} #==== End -- setAllLanguages

	/**
	 * setLanguage
	 *
	 * Sets the data member $language.
	 *
	 * @param	$language
	 * @access	protected
	 */
	protected function setLanguage($language)
	{
		# Set the data member.
		$this->language=$language;
	} #==== End -- setLanguage

	/**
	 * setLanguageID
	 *
	 * Sets the data member $language_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setLanguageID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
			}
			elseif($id!=='add')
			{
				throw new Exception('The passed language id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->language_id=$id;
	} #==== End -- setLanguageID

	/**
	 * setLanguageISO
	 *
	 * Sets the data member $language_iso.
	 *
	 * @param	$iso
	 * @access	protected
	 */
	protected function setLanguageISO($iso)
	{
		# Check if the passed value is empty.
		if(!empty($iso))
		{
			# Clean it up.
			$iso=trim($iso);
			# Set the data member.
			$this->language_iso=$iso;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->language_iso=NULL;
		}
	} #==== End -- setLanguageISO

	/**
	 * setLink
	 *
	 * Sets the data member $link.
	 *
	 * @param	$link
	 * @access	public
	 */
	public function setLink($link)
	{
		# Clean it up.
		$link=trim($link);
		# Check if the passed value is empty or only the sheme name was passed.
		if(empty($link) || ($link=='http://') || ($link=='https://'))
		{
			# Explicitly set the value to NULL.
			$link=NULL;
		}
		else
		{
			# Replace any domain token with the current domain name.
			$link=str_ireplace('%{domain_name}', DOMAIN_NAME, $link);
		}
		# Set the data member.
		$this->link=$link;
	} #==== End -- setLink

	/**
	 * setMore
	 *
	 * Sets the data member $more.
	 *
	 * @param	$more
	 * @access	public
	 */
	public function setMore($more)
	{
		# Check if the passed value is empty.
		if(!empty($more))
		{
			# Clean it up.
			$more=trim($more);
			# Set the data member.
			$this->tmoreitle=$more;
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->more='more';
		}
	} #==== End -- setMore

	/**
	 * setPostTitleDisplay
	 *
	 * Sets the data member $post_title_display.
	 *
	 * @param	$post_title_display
	 * @access	protected
	 */
	protected function setPostTitleDisplay($post_title_display)
	{
		$this->post_title_display=$post_title_display;
	} #==== End -- setPostTitleDisplay

	/**
	 * setPremium
	 *
	 * Sets the data member $premium.
	 *
	 * @param	$premium
	 * @access	public
	 */
	public function setPremium($premium)
	{
		if($premium!==NULL)
		{
			$premium=0;
		}
		$this->premium=$premium;
	} #==== End -- setPremium

	/**
	 * setAllPublishers
	 *
	 * Sets the data member $all_publishers.
	 *
	 * @param	$publishers
	 * @access	protected
	 */
	protected function setAllPublishers($publishers)
	{
		$this->all_publishers=$publishers;
	} #==== End -- setAllPublishers

	/**
	 * setPublisher
	 *
	 * Sets the data member $publisher.
	 *
	 * @param	$publisher
	 * @access	protected
	 */
	protected function setPublisher($publisher)
	{
		# Check if the passed value is empty.
		if(!empty($publisher))
		{
			# Check if the passed value is an object.
			if(!is_object($publisher))
			{
				# Clean it up.
				$publisher=trim($publisher);
			}
			# Set the data member.
			$this->publisher=$publisher;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->publisher=NULL;
		}
	} #==== End -- setPublisher

	/**
	 * setPublisherID
	 *
	 * Sets the data member $publisher_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setPublisherID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$id=(int)$id;
			}
			elseif($id!=='add')
			{
				throw new Exception('The passed publisher id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->publisher_id=$id;
	} #==== End -- setPublisherID

	/**
	 * setTextLanguage
	 *
	 * Sets the data member $text_language.
	 *
	 * @param	string $text_language
	 * @access	public
	 */
	public function setTextLanguage($text_language)
	{
		$this->text_language=$text_language;
	} #==== End -- setTextLanguage

	/**
	 * setTextLanguageISO
	 *
	 * Sets the data member $text_language_iso.
	 *
	 * @param	$iso
	 * @access	protected
	 */
	protected function setTextLanguageISO($iso)
	{
		# Check if the passed value is empty.
		if(!empty($iso))
		{
			# Clean it up.
			$iso=trim($iso);
			# Set the data member.
			$this->text_language_iso=$iso;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->text_language_iso=NULL;
		}
	} #==== End -- setTextLanguageISO

	/**
	 * setTextTrans
	 *
	 * Sets the data member $text_trans.
	 *
	 * @param	string $text_trans
	 * @access	public
	 */
	public function setTextTrans($text_trans)
	{
		# Bring the content instance into scope.
		$main_content=Content::getInstance();

		# Check if the value is empty.
		if(!empty($text_trans))
		{
			# The the site name.
			$site_name=$main_content->getSiteName();
			# Strip slashes and decode any html entities in UTF-8 charset.
			$text_trans=html_entity_decode(stripslashes($text_trans), ENT_NOQUOTES, 'UTF-8');
			# Clean it up.
			$text_trans=trim($text_trans);
			# Replace any tokens with their correlating value.
			$text_trans=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $text_trans);
		}
		else
		{
			# Explicitly set it to NULL.
			$text_trans=NULL;
		}
		# Set the data member.
		$this->text_trans=$text_trans;
	} #==== End -- setTextTrans

	/**
	 * setTransLanguage
	 *
	 * Sets the data member $trans_language.
	 *
	 * @param	string $trans_language
	 * @access	public
	 */
	public function setTransLanguage($trans_language)
	{
		$this->trans_language=$trans_language;
	} #==== End -- setTransLanguage

	/**
	 * setTransLanguageISO
	 *
	 * Sets the data member $trans_language_iso.
	 *
	 * @param	$iso
	 * @access	protected
	 */
	protected function setTransLanguageISO($iso)
	{
		# Check if the passed value is empty.
		if(!empty($iso))
		{
			# Clean it up.
			$iso=trim($iso);
			# Set the data member.
			$this->trans_language_iso=$iso;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->trans_language_iso=NULL;
		}
	} #==== End -- setTransLanguageISO

	/**
	 * setTitle
	 *
	 * Sets the data member $title.
	 *
	 * @param	$title
	 * @access	public
	 */
	public function setTitle($title)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Bring the content instance into scope.
		$main_content=Content::getInstance();

		# Check if the value is empty.
		if(!empty($title))
		{
			# The the site name.
			$site_name=$main_content->getSiteName();
			# Sanitize
			# 	Turn any html entities back to special characters
			#	Strip any html or php tags
			#	Trim blank space off the front and back
			#	Turn any special characters into html entities including quotes
			#	Then decode all html entities making sure they are all UTF-8 encoded.
			$title=$db->sanitize($title);
			# Clean it up.
			$title=trim($title);
			# Replace any tokens with their correlating value.
			$title=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $title);
		}
		else
		{
			# Explicitly set it to NULL.
			$title=NULL;
		}
		# Set the data member.
		$this->title=$title;
	} #==== End -- setTitle

	/**
	 * setUser
	 *
	 * Sets the data member $user.
	 *
	 * @param	int $user				The User ID.
	 * @access	protected
	 */
	protected function setUser($user)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $user is NULL.
		if($user!==NULL)
		{
			# Check if the passed $user is an integer.
			if($validator->isInt($user)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->user=(int)$user;
			}
			else
			{
				throw new Exception('The passed User ID was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
	} #==== End -- setUser

	/**
	 * setAllVideos
	 *
	 * Sets the data member $all_videos.
	 *
	 * @param	$videos
	 * @access	protected
	 */
	protected function setAllVideos($videos)
	{
		# Set the data member.
		$this->all_videos=$videos;
	} #==== End -- setAllVideos

	/**
	 * setVideoObj
	 *
	 * Sets the data member $video_obj.
	 *
	 * @param	$object
	 * @access	protected
	 */
	protected function setVideoObj($object)
	{
		# Set the data member.
		$this->video_obj=$object;
	} #==== End -- setVideoObj

	/**
	 * setVideoID
	 *
	 * Sets the data member $video_id.
	 *
	 * @param	$id
	 * @access	public
	 */
	public function setVideoID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
			}
			elseif($id!=='add' && $id!=='select' && $id!=='remove')
			{
				throw new Exception('The passed video id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the value to NULL.
			$id=NULL;
		}
		# Set the data member.
		$this->video_id=$id;
	} #==== End -- setVideoID

	/**
	 * setVisibility
	 *
	 * Sets the data member $visibility.
	 *
	 * @param	$visibility
	 * @access	public
	 */
	public function setVisibility($visibility)
	{
		# Check if the value is NULL.
		if($visibility!==NULL && $visibility!==0)
		{
			# Clean it up.
			$visibility=trim($visibility);
		}
		# Set the data member.
		$this->visibility=$visibility;
	} #==== End -- setVisibility

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllSubContent
	 *
	 * Returns the data member $all_subcontent.
	 *
	 * @access	public
	 */
	public function getAllSubContent()
	{
		return $this->all_subcontent;
	} #==== End -- getAllSubContent

	/**
	 * getAllAudio
	 *
	 * Returns the data member $all_audio.
	 *
	 * @access	public
	 */
	public function getAllAudio()
	{
		return $this->all_audio;
	} #==== End -- getAllAudio

	/**
	 * getAudioObj
	 *
	 * Returns the data member $audio_obj.
	 *
	 * @access	public
	 */
	public function getAudioObj()
	{
		return $this->audio_obj;
	} #==== End -- getAudioObj

	/**
	 * getAudioID
	 *
	 * Returns the data member $audio_id.
	 *
	 * @access	public
	 */
	public function getAudioID()
	{
		return $this->audio_id;
	} #==== End -- getAudioID

	/**
	 * getAvailability
	 *
	 * Returns the data member $availability.
	 *
	 * @access	public
	 */
	public function getAvailability()
	{
		return $this->availability;
	} #==== End -- getAvailability

	/**
	 * getAllBranches
	 *
	 * Returns the data member $all_branches.
	 *
	 * @access	public
	 */
	public function getAllBranches()
	{
		return $this->all_branches;
	} #==== End -- getAllBranches

	/**
	 * getBranch
	 *
	 * Returns the data member $branch.
	 *
	 * @access	public
	 */
	public function getBranch()
	{
		return $this->branch;
	} #==== End -- getBranch

	/**
	 * getBranchID
	 *
	 * Returns the data member $branch_id.
	 *
	 * @access	public
	 */
	public function getBranchID()
	{
		return $this->branch_id;
	} #==== End -- getBranchID

	/**
	 * getRecordBranches
	 *
	 * Returns the data member $record_branches.
	 *
	 * @access	public
	 */
	public function getRecordBranches()
	{
		return $this->record_branches;
	} #==== End -- getRecordBranches

	/**
	 * getWantedBranches
	 *
	 * Returns the data member $wanted_branches.
	 *
	 * @access	public
	 */
	public function getWantedBranches()
	{
		return $this->wanted_branches;
	} #==== End -- getWantedBranches

	/**
	 * getAllContributors
	 *
	 * Returns the data member $all_contributors.
	 *
	 * @access	public
	 */
	public function getAllContributors()
	{
		return $this->all_contributors;
	} #==== End -- getAllContributors

	/**
	 * getContributor
	 *
	 * Returns the data member $contributor.
	 *
	 * @access	public
	 */
	public function getContributor()
	{
		return $this->contributor;
	} #==== End -- getContributor

	/**
	 * getContID
	 *
	 * Returns the data member $cont_id.
	 *
	 * @access	public
	 */
	public function getContID()
	{
		return $this->cont_id;
	} #==== End -- getContID

	/**
	 * getRecentContID
	 *
	 * Returns the data member $recent_cont_id.
	 *
	 * @access	public
	 */
	public function getRecentContID()
	{
		return $this->recent_cont_id;
	} #==== End -- getRecentContID

	/**
	 * getLastEdit
	 *
	 * Returns the data member $date.
	 *
	 * @access	public
	 */
	public function getLastEdit()
	{
		return $this->last_edit;
	} #==== End -- getLastEdit

	/**
	 * getAllFiles
	 *
	 * Returns the data member $all_files.
	 *
	 * @access	public
	 */
	public function getAllFiles()
	{
		return $this->all_files;
	} #==== End -- getAllFiles

	/**
	 * getFile
	 *
	 * Returns the data member $file.
	 *
	 * @access	public
	 */
	public function getFile()
	{
		return $this->file;
	} #==== End -- getFile

	/**
	 * getFileID
	 *
	 * Returns the data member $file_id.
	 *
	 * @access	public
	 */
	public function getFileID()
	{
		return $this->file_id;
	} #==== End -- getFileID

	/**
	 * getFileInfoDisplay
	 *
	 * Returns the data member $file_info_display.
	 *
	 * @access	public
	 */
	public function getFileInfoDisplay()
	{
		return $this->file_info_display;
	} #==== End -- getFileInfoDisplay

	/**
	 * getHide
	 *
	 * Returns the data member $hide.
	 *
	 * @access	public
	 */
	public function getHide()
	{
		return $this->hide;
	} #==== End -- getHide

	/**
	 * getAllImages
	 *
	 * Returns the data member $all_images.
	 *
	 * @access	public
	 */
	public function getAllImages()
	{
		return $this->all_images;
	} #==== End -- getAllImages

	/**
	 * getImageObj
	 *
	 * Returns the data member $image_obj.
	 *
	 * @access	public
	 */
	public function getImageObj()
	{
		return $this->image_obj;
	} #==== End -- getImageObj

	/**
	 * getImageID
	 *
	 * Returns the data member $image_id.
	 *
	 * @access	public
	 */
	public function getImageID()
	{
		return $this->image_id;
	} #==== End -- getImageID

	/**
	 * getAllInstitutions
	 *
	 * Returns the data member $all_institutions.
	 *
	 * @access	public
	 */
	public function getAllInstitutions()
	{
		return $this->all_institutions;
	} #==== End -- getAllInstitutions

	/**
	 * getInstitution
	 *
	 * Returns the data member $institution.
	 *
	 * @access	public
	 */
	public function getInstitution()
	{
		return $this->institution;
	} #==== End -- getInstitution

	/**
	 * getInstitutionID
	 *
	 * Returns the data member $institution_id.
	 *
	 * @access	public
	 */
	public function getInstitutionID()
	{
		return $this->institution_id;
	} #==== End -- getInstitutionID

	/**
	 * getAllLanguages
	 *
	 * Returns the data member $all_languages.
	 *
	 * @access	public
	 */
	public function getAllLanguages()
	{
		return $this->all_languages;
	} #==== End -- getAllLanguages

	/**
	 * getLanguage
	 *
	 * Returns the data member $language.
	 *
	 * @access	public
	 */
	public function getLanguage()
	{
		return $this->language;
	} #==== End -- getLanguage

	/**
	 * getLanguageID
	 *
	 * Returns the data member $language_id.
	 *
	 * @access	public
	 */
	public function getLanguageID()
	{
		return $this->language_id;
	} #==== End -- getLanguageID

	/**
	 * getLanguageISO
	 *
	 * Returns the data member $language_iso.
	 *
	 * @access	public
	 */
	public function getLanguageISO()
	{
		return $this->language_iso;
	} #==== End -- getLanguageISO

	/**
	 * getLink
	 *
	 * Returns the data member $link.
	 *
	 * @access	public
	 */
	public function getLink()
	{
		return $this->link;
	} #==== End -- getLink

	/**
	 * getMore
	 *
	 * Returns the data member $more.
	 *
	 * @access	public
	 */
	public function getMore()
	{
		return $this->more;
	} #==== End -- getMore

	/**
	 * getPostTitleDisplay
	 *
	 * Returns the data member $post_title_display.
	 *
	 * @access	public
	 */
	public function getPostTitleDisplay()
	{
		return $this->post_title_display;
	} #==== End -- getPostTitleDisplay

	/**
	 * getPremium
	 *
	 * Returns the data member $premium.
	 *
	 * @access	public
	 */
	public function getPremium()
	{
		return $this->premium;
	} #==== End -- getPremium

	/**
	 * getAllPublishers
	 *
	 * Returns the data member $all_publishers.
	 *
	 * @access	public
	 */
	public function getAllPublishers()
	{
		return $this->all_publishers;
	} #==== End -- getAllPublishers

	/**
	 * getPublisher
	 *
	 * Returns the data member $publisher.
	 *
	 * @access	public
	 */
	public function getPublisher()
	{
		return $this->publisher;
	} #==== End -- getPublisher

	/**
	 * getPublisherID
	 *
	 * Returns the data member $publisher_id.
	 *
	 * @access	public
	 */
	public function getPublisherID()
	{
		return $this->publisher_id;
	} #==== End -- getPublisherID

	/**
	 * getTextLanguage
	 *
	 * Returns the data member $text_language.
	 *
	 * @access	public
	 */
	public function getTextLanguage()
	{
		return $this->text_language;
	} #==== End -- getTextLanguage

	/**
	 * getTextLanguageISO
	 *
	 * Returns the data member $text_language_iso.
	 *
	 * @access	public
	 */
	public function getTextLanguageISO()
	{
		return $this->text_language_iso;
	} #==== End -- getTextLanguageISO

	/**
	 * getTextTrans
	 *
	 * Returns the data member $text_trans.
	 *
	 * @access	public
	 */
	public function getTextTrans()
	{
		return $this->text_trans;
	} #==== End -- getTextTrans

	/**
	 * getTransLanguage
	 *
	 * Returns the data member $trans_language.
	 *
	 * @access	public
	 */
	public function getTransLanguage()
	{
		return $this->trans_language;
	} #==== End -- getTransLanguage

	/**
	 * getTransLanguageISO
	 *
	 * Returns the data member $trans_language_iso.
	 *
	 * @access	public
	 */
	public function getTransLanguageISO()
	{
		return $this->trans_language_iso;
	} #==== End -- getTransLanguageISO

	/**
	 * getTitle
	 *
	 * Returns the data member $title.
	 *
	 * @access	public
	 */
	public function getTitle()
	{
		return $this->title;
	} #==== End -- getTitle

	/**
	 * getUser
	 *
	 * Returns the data member $user.
	 *
	 * @access	public
	 */
	public function getUser()
	{
		return $this->user;
	} #==== End -- getUser

	/**
	 * getAllVideos
	 *
	 * Returns the data member $all_videos.
	 *
	 * @access	public
	 */
	public function getAllVideos()
	{
		return $this->all_videos;
	} #==== End -- getAllVideos

	/**
	 * getVideoObj
	 *
	 * Returns the data member $video_obj.
	 *
	 * @access	public
	 */
	public function getVideoObj()
	{
		return $this->video_obj;
	} #==== End -- getVideoObj

	/**
	 * getVideoID
	 *
	 * Returns the data member $video_id.
	 *
	 * @access	public
	 */
	public function getVideoID()
	{
		return $this->video_id;
	} #==== End -- getVideoID

	/**
	 * getVisibility
	 *
	 * Returns the data member $visibility.
	 *
	 * @access	public
	 */
	public function getVisibility()
	{
		return $this->visibility;
	} #==== End -- getVisibility

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * countAllSubContent
	 *
	 * Returns the number of subcontent in the database that are marked available.
	 *
	 * @param	$branches				The names and/or id's of the branch(es) to be retrieved.
	 *										May be multiple branches - separate with dash, ie. '50-60-Archives-110'.
	 *										"!" may be used to exlude branches, ie. '50-!60-Archives-110'
	 * @param	int $limit				The limit of records to count.)
	 * @param	string $and_sql			Extra AND statements in the query.
	 * @access	public
	 */
	public function countAllSubContent($branches=NULL, $limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Check if there were branches passed.
		if($branches===NULL)
		{
			throw new Exception('You must provide a branch!', E_RECOVERABLE_ERROR);
		}
		else
		{
			try
			{
				# Get the Branch class.
				require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
				# Instantiate a new Branch object.
				$branch=new Branch();
				# Create the WHERE clause for the passed $branches string.
				$branch->createWhereSQL($branches);
				# Set the newly created WHERE clause to a variable.
				$where=$branch->getWhereSQL();
				try
				{
					# Count the records.
					$count=$db->query('SELECT `id` FROM `'.DBPREFIX.'subcontent` WHERE '.$where.' '.(($and_sql===NULL) ? '' : $and_sql).(($limit===NULL) ? '' : ' LIMIT '.$limit));
					return $count;
				}
				catch(ezDB_Error $ez)
				{
					throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
				}
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
	} #==== End -- countAllSubContent

	/**
	 * deleteSubContent
	 *
	 * Deletes a  post from the `subcontent` table.
	 *
	 * @param		$post_id 	(The id of the post to delete.)
	 * @access	public
	 */
	public function deleteSubContent($post_id)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Check if the passed id is an integer.
			if($validator->isInt($post_id)===TRUE)
			{
				# Delete the record.
				$delete=$db->query('DELETE FROM `'.DBPREFIX.'subcontent` WHERE `id`= '.$db->quote($post_id).' LIMIT 1');
				# Check if anything was deleted.
				if(!empty($delete))
				{
					return TRUE;
				}
			}
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteSubContent

	/**
	 * displayBranchSubContent
	 *
	 * Returns the display of a single branch's subcontent.
	 *
	 * @param		$branch 	(The name or id of the branch to display.)
	 * @access	public
	 */
	public function displayBranchSubContent($branch=NULL, $max_char=242, $min_word=10, $show_hidden=FALSE, $max_br=NULL)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the Login object into scope.
		global $login;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Set a default variable for the "AND" portion of the sql statement (1=have the legal rights to display this material 2=Internal document only).
			$and_sql=' AND `availability` = 1 AND `visibility` IS NULL';
			# Set the edit and delete variable to FALSE by default.
			$edit=FALSE;
			$delete=FALSE;
			$params='';
			# Check if the User is logged in.
			if($login->isLoggedIn()===TRUE)
			{
				# Set a variable for the "AND" portion of the sql statement.
				$and_sql=' AND `availability` = 1';
				# Set the edit and delete variables to TRUE.
				$edit=TRUE;
				$delete=TRUE;
			}
			# Check if the logged in User is a Managing User.
			if($login->checkAccess(MAN_USERS)===TRUE)
			{
				# Set a variable for the "AND" portion of the sql statement.
				$and_sql=' AND (`availability` = 1 || `availability` = 2)';
				# Set the edit and delete variables to TRUE.
				$edit=TRUE;
				$delete=TRUE;
			}
			# Check if the logged in User is an Admin.
			if($login->checkAccess(ADMIN_USERS)===TRUE)
			{
				# Set a variable for the "AND" portion of the sql statement.
				$and_sql='';
				# Set the edit and delete variables to TRUE.
				$edit=TRUE;
				$delete=TRUE;
			}

			# Capture the post's id from GET Data if we have it.
			if(isset($_GET['post']) && !isset($_GET['edit']))
			{
				# Clean it up and set the POSt data to a variable.
				$post_id=trim($_GET['post']);
				# Check if the post id is an integer.
				if($validator->isInt($post_id)===TRUE)
				{
					# Concatenate the "AND" portion of the sql statement to select only the post associated with the passed id.
					$and_sql.=' AND `id`='.$post_id;

					# Get the SubContent.
					$this->getSubContent($branch, 1, '*', 'date', 'DESC', $and_sql);
					# Set the subcontent display to a variable.
					$display_subcontent=$this->displaySubContent(NULL, constant(strtoupper(str_replace(' ', '_', $branch)).'_USERS'), TRUE, 3, $show_hidden);
					# Check if there is actually content to display.
					if(empty($display_subcontent[$post_id]))
					{
						# Redirect the user to the main page (no GET Data).
						$doc->redirect(WebUtility::removeIndex('http://'.FULL_DOMAIN.HERE));
					}
					# Set the file info to the data member for display.
					$this->setFileInfoDisplay($display_subcontent[$post_id]['file']);
					# Create a variable for the hidden class.
					$hidden=$display_subcontent[$post_id]['hidden'];
					# Display the subcontent.
					$display='<div class="detailed">';
					$display.='<article class="post">';
					if($hidden!==NULL)
					{
						$display.='<p class="hide">This post is hidden.</p>';
					}
					$display.=$display_subcontent[$post_id]['date'];
					$display.=$display_subcontent[$post_id]['contributor'];
					$display.=$display_subcontent[$post_id]['recent_contributor'];
					$display.=$display_subcontent[$post_id]['publisher'];
					$display.=$display_subcontent[$post_id]['image'];
					$display.=$display_subcontent[$post_id]['text'];
					$display.=$display_subcontent[$post_id]['text_trans'];
					$file_info=$this->getFileInfoDisplay();
					# Create an empty variable to hold any file details.
					$file_details=NULL;
					# Check if there is file info.
					if(!empty($file_info['name']))
					{
						$file_details='<ul class="entry-file">';
						$file_details.=((!empty($file_info['header'])) ? '<li>'.$file_info['header'].'</li>' : '');
						$file_details.='<li>';
						$file_details.=$file_info['name'];
						$file_details.='</li>';
						$file_details.=((!empty($file_info['title'])) ? '<li>'.$file_info['title'].'</li>' : '');
						$file_details.=((!empty($file_info['author'])) ? '<li>'.$file_info['author'].'</li>' : '');
						$file_details.=((!empty($file_info['publisher'])) ? '<li>'.$file_info['publisher'].'</li>' : '');
						$file_details.=((!empty($file_info['language'])) ? '<li>'.$file_info['language'].'</li>' : '');
						$file_details.=((!empty($file_info['year'])) ? '<li>'.$file_info['year'].'</li>' : '');
						$file_details.=((!empty($file_info['location'])) ? '<li>'.$file_info['location'].'</li>' : '');
						$file_details.=((!empty($file_info['contributor'])) ? '<li>'.$file_info['contributor'].'</li>' : '');
						$file_details.='</ul>';
					}
					$display.=$file_details;
					$display.=$display_subcontent[$post_id]['more'];
					$display.=$display_subcontent[$post_id]['edit'];
					$display.=$display_subcontent[$post_id]['delete'];
					$display.=$display_subcontent[$post_id]['download'];
					$display.='</article>';
					$display.='</div>';

					# Set the post's title to the data member for display.
					$this->setPostTitleDisplay($display_subcontent[$post_id]['title']);
				}
				else
				{
					# Redirect the user to the main page (no GET Data).
					$doc->redirect(WebUtility::removeIndex('http://'.FULL_DOMAIN.HERE));
				}
			}
			else
			{
				# Capture the year of posts to display from GET Data if we have it.
				if(isset($_GET['year']))
				{
					# Clean it up.
					$year=trim($_GET['year']);
					# Check if the year is an integer.
					if($validator->isInt($year)===TRUE)
					{
						# Check if the year is empty.
						if(empty($year))
						{
							$year=0;
						}
						# Set the extra params for the sql statement to a variable.
						$params='year='.$year;
						# Set a variable for the "AND" portion of the sql statement.
						$and_sql.=' AND YEAR(`date`) = '.$year;
						# Set the page's sub title to the sought year.
						$this->setSubTitle((($year===0) ? 'Undated' : $year));
					}
					else
					{
						# Redirect the user to the main page (no GET Data).
						$doc->redirect(WebUtility::removeIndex('http://'.FULL_DOMAIN.HERE));
					}
				}
				# Count the returned subcontent.
				$content_count=$this->countAllSubContent($branch, NULL, $and_sql);
				if($content_count>0)
				{
					# Get the PageNavigator Class.
					require_once Utility::locateFile(MODULES.'PageNavigator'.DS.'PageNavigator.php');
					# Create a new PageNavigator object.
					$paginator=new PageNavigator(7, 4, CURRENT_PAGE, 'page', $content_count, $params);
					$paginator->setStrFirst('First Page');
					$paginator->setStrLast('Last Page');
					$paginator->setStrNext('Next Page');
					$paginator->setStrPrevious('Previous Page');

					# Get the SubContent.
					$this->getSubContent($branch, $paginator->getRecordOffset().', '.$paginator->getRecordsPerPage(), '*', 'date', 'DESC', $and_sql);
					# Display the SubContent.
					$display_array=$this->displaySubContent($max_char, constant(strtoupper(str_replace(' ', '_', $branch)).'_USERS'), TRUE, $min_word, $show_hidden);
					# Start an unordered list of the "subcontent" class and set it to a variable.
					$display='<ul class="post">';
					# Loop through the display subcontent array.
					foreach($display_array as $id=>$display_subcontent)
					{
						# Create a variable for the hidden class.
						$hidden=(($display_subcontent['hidden']===NULL) ? NULL : ' class="hide"');
						# Add the post content to the display variable.
						$display.='<li'.$hidden.'>';
						# Open the article tag.
						$display.='<article>';
						//$display.=$display_subcontent['image'];
						$display.='<h1 class="h-1">'.$display_subcontent['title'].'</h1>';
						$display.=$display_subcontent['date'];
						if($hidden!==NULL)
						{
							$display.='<p class="hide">This post is hidden.</p>';
						}
						$display.=$display_subcontent['text'];
						$display.=$display_subcontent['text_trans'];
						$display.=$display_subcontent['more'];
						$display.=$display_subcontent['edit'];
						$display.=$display_subcontent['delete'];
						$display.=$display_subcontent['download'];
						# Close the article tag.
						$display.='</article>';
						$display.='</li>';
					}
					# Close the unordered list.
					$display.='</ul>';
					# Display the pagenavigator.
					$display.=$paginator->getNavigator();
				}
				else
				{
					$display='<h3 class="h-3">There are no records to display.</h3>';
				}
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayBranchSubContent

	/**
	 * displaySubContent
	 *
	 * Creates SubContent XHTML elements and sets them to an array for display.
	 *
	 * @param	$max_char				The maximum number of characters to display.
	 * @param	$access_level			The access levels needed for a logged in User to modify the posts - must be a space sepparated string of numbers.
	 * @param	boolean $buttons		TRUE if other buttons should be displayed, ie "download", "more", FLASE if not.
	 * @param	int $min_word
	 * @param	boolean $show_hidden
	 * @param	$max_br
	 * @access	public
	 */
	public function displaySubContent($max_char=NULL, $access_level=ADMIN_USERS, $buttons=TRUE, $min_word=3, $show_hidden=FALSE, $max_br=NULL)
	{
		# Bring the Login object into scope.
		global $login;

		try
		{
			# Set the retrieved `subcontent` records to a variable.
			$all_subcontent=$this->getAllSubContent();
			# Check if there is subcontent to display.
			if($all_subcontent!==NULL)
			{
				# Create an empty array to hold subcontent record id's after that record has been added to the $display_content variable.
				$used_ids=array();
				# Create new array to hold all display content.
				$display_content=array();
				# Loop through the subcontent.
				foreach($all_subcontent as $row)
				{
					# Set all relevant Data members.
					$this->setDataMembers($row);
					# Create a variable for the id.
					$id=$this->getID();
					# Check if this id has already been used.
					if(!in_array($id, $used_ids))
					{
						# Add this id to the used id's.
						$used_ids[]=$id;
						# Make the display content array multi-dimensional.
						$display_content[$id]=array('image'=>NULL, 'date'=>NULL, 'last_edit'=>NULL, 'title'=>NULL, 'text'=>NULL, 'publisher'=>NULL, 'contributor'=>NULL, 'recent_contributor'=>NULL, 'text_trans'=>NULL, 'more'=>NULL, 'read_more'=>NULL, 'edit'=>NULL, 'delete'=>NULL, 'download'=>NULL, 'file'=>NULL, 'hidden'=>NULL);
						# Set the branch id(s) to a variable.
						$branch_ids=$this->getRecordBranches();
						# Trim the dashes off the ends of the string.
						$branch_ids=trim($branch_ids, '-');
						# Explode the branch(es) into an array.
						$branch_ids=explode('-', $branch_ids);
						# Create variable for the contributor id.
						$cont_id=$this->getContID();
						# Create variable for the recent contributor id.
						$recent_cont_id=$this->getRecentContID();
						# Create variable for the last edit date.
						$last_edit=$this->getLastEdit();
						# Create variable for date. If there is no last edit date, use the original date.
						$date=$this->getDate();
						# Set the User's ability to edit this post to FALSE as default.
						$edit=FALSE;
						# Set the User's ability to delete this post to FALSE as default.
						$delete=FALSE;
						# Check if the logged in User has the privileges to modify this post.
						if($login->checkAccess($access_level)===TRUE)
						{
							# Set the User's ability to modify this post to TRUE.
							$edit=TRUE;
							$delete=TRUE;
						}
						# Create variable for the file.
						$file=$this->getFile();
						# Set the file's author to a variable.
						$file_author=NULL;
						# Set the file's availability to a variable.
						$file_availability=NULL;
						# Set the file's category to a variable.
						$file_category=NULL;
						# Set the file contributor's id to a variable.
						$file_cont_id=NULL;
						# Set the file's language to a variable.
						$file_language=NULL;
						# Set the file's location to a variable.
						$file_location=NULL;
						# Set the file's publisher to a variable.
						$file_publisher=NULL;
						# Set the file's premium status to a variable.
						$file_premium=NULL;
						# Set the file's title to a variable.
						$file_title=NULL;
						# Set the file's publish year to a variable.
						$file_year=NULL;
						# Check if there is a File object.
						if($file!==NULL)
						{
							# Set the file's name to a variable.
							$file_name=$file->getFile();
							# Set the file's author to a variable.
							$file_author=$file->getAuthor();
							# Set the file's availability to a variable.
							$file_availability=$file->getAvailability();
							# Set the file's category to a variable.
							$file_category=$file->getCategories();
							# Set the file contributor's id to a variable.
							$file_cont_id=$file->getContID();
							# Set the file's language to a variable.
							$file_language=$file->getLanguage();
							# Set the file's location to a variable.
							$file_location=$file->getLocation();
							# Set the file's publisher to a variable.
							$file_publisher=$file->getPublisher();
							# Set the file's premium status to a variable.
							$file_premium=$file->getPremium();
							# Set the file's title to a variable.
							$file_title=$file->getTitle();
							# Set the file's publish year to a variable.
							$file_year=$file->getYear();
						}
						# Create variable for hide.
						$hide=$this->getHide();
						# Create variable for the link.
						$link=$this->getLink();
						# Create variable for premium.
						$premium=$this->getPremium();
						# Create variable for title.
						$title=$this->getTitle();
						# Create variable for text.
						$text=$this->getText();
						$text_language_iso=$this->getTextLanguageISO();
						# Create variable for text translation.
						$text_trans=$this->getTextTrans();
						# Create variable for text translation's language.
						$trans_language=$this->getTransLanguage();
						# Create variable for text translation's language ISO Code.
						$trans_language_iso=$this->getTransLanguageISO();
						# Create variable for visibility.
						$visibility=$this->getVisibility();
						# Check if the visibility value of this record is 0.
						if($visibility===0)
						{
							# Check if the User is logged in.
							if($login->isLoggedIn()===FALSE)
							{
								# Set the hide value to 0, hiding the record.
								$hide=0;
							}
						}
						# Check if the visibility value of this record is empty.
						elseif(!empty($visibility))
						{
							# Trim any dashes(-) off the ends.
							$visibility=trim($visibility, '-');
							# Replace any dashes(-) with spaces.
							$visibility=str_replace('-', ' ', $visibility);
							# Check if the logged in User has access to view this record.
							if($login->checkAccess('1 '.$visibility)===FALSE)
							{
								# Set the hide value to 0, hiding the record.
								$hide=0;
							}
						}
						# Get the correct URL to link the post to based on the branch and set it to a variable.
						$domain=$this->getPostDomain($branch_ids);
						# Get the correct folder name for the branch and set it to a variable.
						$branch_folder=$this->getBranchFolder($branch_ids);
						# Create a variable to hold whether or not a "more" link should be displayed. Default is FALSE.
						$more=FALSE;
						# Create a variable to hold whether or not a text translation statment should be displayed in lieu of the actual translation. Default is FALSE.
						$text_trans_statement=FALSE;
						# Check if this record should be hidden.
						if($hide===NULL || $show_hidden===TRUE)
						{
							# Check if a maximum number of characters to be displayed has been passed.
							if($max_char!==NULL)
							{
								# Check if there is text to display.
								if(!empty($text))
								{
									# Check if there is a translation.
									if($text_trans!==NULL)
									{
										$max_char-=30;
									}
									# Check if the length of the title is more than 25 characters.
									# Strip tags from the text and the text translation and see if combined they contain more characters than allotted in the maximum characters variable.
									if(strlen(preg_replace('/<.*?>/', '', $text.$text_trans)) > $max_char)
									{
										# Strip tags from the text and see if it contains more characters than allotted in the maximum characters variable.
										if(strlen(strip_tags($text)) > $max_char)
										{
											# Ensure that percent signs (%) aren't interpreted as type specifiers by sprintf. Do this BEFORE the actual type specifier is added to the truncated text.
											$text=str_replace('%', '&percnt;', $text);
											# Use truncate from the Document class to truncate the text.
											$text=WebUtility::truncate($text, $max_char, '&hellip;%1$s', TRUE, FALSE, $max_br);

											# Add a "more" link to the text.
											$text=sprintf($text, ' <a class="more" href="'.$domain.'?post='.$id.'" title="more on: '.str_replace('"', '`', $title).'">'.$this->getMore().'</a>');

											# Set the $more value to TRUE.
											$more=TRUE;
										}
										# Set the $text_trans_statement value to TRUE.
										$text_trans_statement=TRUE;
									}
								}
							}

							# Check if there is an image id.
							if($this->getImageID()!==NULL)
							{
								# Set this Image object to a variable.
								$image_obj=$this->getImageObj();
								$image_cats=$image_obj->getCategories();
								# Replace any domain name tokens with the current domain name.
								$image_name=str_ireplace('%{domain_name}', DOMAIN_NAME, $image_obj->getImage());
								# Set the displayed image to a variable.
								$image_content=$image_obj->displayImage(TRUE, NULL, NULL);
								# Set the image content to the array.
								$display_content[$id]['image']=$image_content;
							}

							# Check if there is a contributor id.
							if($cont_id!==NULL)
							{
								# Set the Contributor object to a variable.
								$contributor=$this->getContributor();
								# Set the contributor's privacy setting to a variable.
								$cont_privacy=$contributor->getContPrivacy();
								# Check if the contributor should be hidden.
								if($cont_privacy!==NULL)
								{
									# Create a variable to hold the contributor display XHTML and open a list tag.
									$display_cont='<div class="post-author">';
									$display_cont.='<span class="label">Posted by</span> <a href="'.APPLICATION_URL.'profile/?contributor='.$cont_id.'" title="'.$contributor->getContName().'">'.$contributor->getContName().'</a>';
									$display_cont.='</div>';
									# Check if the contributor should be displayed to all.
									if($cont_privacy==0)
									{
										# Set the contributor content to the array.
										$display_content[$id]['contributor']=$display_cont;
									}
									# Check if the contributor should be displayed to logged in users only.
									elseif($cont_privacy==1)
									{
										# Check if the User is logged in.
										if($login->isLoggedIn()===TRUE)
										{
											# Set the contributor content to the array.
											$display_content[$id]['contributor']=$display_cont;
										}
									}
								}
								# Check if there is a recent contributor id.
								if($recent_cont_id!==NULL)
								{
									# Get the recent contributor's info.
									$contributor->getThisContributor($recent_cont_id, 'id', FALSE);
									# Set the recent contributor's privacy setting to a variable.
									$recent_cont_privacy=$contributor->getContPrivacy();
									# Convert the last edit date to a timestamp.
									$last_edit=strtotime($last_edit);
									# Check if the recent contributor should be hidden.
									if($recent_cont_privacy!==NULL)
									{
										# Create a variable to hold the recent contributor display XHTML and open a list tag.
										$display_recent_cont='<div class="post-editor">';
										$display_recent_cont.='<span class="label">Edited by</span> <a href="'.APPLICATION_URL.'profile/?contributor='.$recent_cont_id.'" title="'.$contributor->getContName().'">'.$contributor->getContName().'</a> on <span class="edit-date"><span class="edit-month">'.date("F", $last_edit).'</span> <span class="edit-day">'.date("d", $last_edit).'</span>, <span class="edit-year">'.date("Y", $last_edit).'</span>'.'</span>';
										$display_recent_cont.='</div>';
										# Check if the recent contributor should be displayed to all.
										if($recent_cont_privacy==0)
										{
											# Set the contributor content to the array.
											$display_content[$id]['recent_contributor']=$display_recent_cont;
										}
										# Check if the recent contributor should be displayed to logged in users only.
										elseif($recent_cont_privacy==1)
										{
											# Check if the User is logged in.
											if($login->isLoggedIn()===TRUE)
											{
												# Set the contributor content to the array.
												$display_content[$id]['recent_contributor']=$display_recent_cont;
											}
										}
									}
								}
							}

							# Check if the date value is NULL.
							if($date!='0000-00-00')
							{
								# Convert the date to a timestamp.
								$date=strtotime($date);
								# Set the date to a variable.
								$date_content='<span class="post-date"><span class="post-month">'.date("F", $date).'</span> <span class="post-day">'.date("d", $date).'</span>, <span class="post-year">'.date("Y", $date).'</span>'.'</span>';
								# Set the date content to the array.
								$display_content[$id]['date']=$date_content;
							}

							# Check if this post is hidden.
							if($hide!==NULL)
							{
								# Set the hidden value in the array to TRUE.
								$display_content[$id]['hidden']=TRUE;
							}

							# Set the title to a variable.
							$title_content='<a href="'.$domain.'?post='.$id.'" class="post-title" title="'.str_replace('"', '`', $title).'">'.$title.'</a>';
							# Set the title content to the array.
							$display_content[$id]['title']=$title_content;

							# Check if there is text to display.
							if(!empty($text))
							{
								# Set the text to a variable.
								$text_content='<div class="entry"'.((!empty($text_language_iso)) ? ' lang="'.$text_language_iso.'"' : '').'>';
								$text_content.=$text;
								$text_content.='</div>';
								# Set the text content to the array.
								$display_content[$id]['text']=$text_content;
							}

							# Check if there is a text translation.
							if(!empty($text_trans))
							{
								# Check if the $text_trans_statement variable equals TRUE. If it does, then a max number of characters in the text has been reached and the translation will be too long to display.
								if($text_trans_statement===TRUE)
								{
									# Set the text translation to a variable.
									$text_trans_content='<div class="entry-trans">';
									# Set the text translation statment instead of the actual translation.
									$text_trans_content.='Translation to '.$trans_language.' available!';
									$text_trans_content.='</div>';
									# Set the text translation content to the array.
									$display_content[$id]['text_trans']=$text_trans_content;
								}
								else
								{
									# Set the text translation to a variable.
									$text_trans_content='<div class="entry-trans">';
									$text_trans_content.='<span class="label">Translated to '.$trans_language.':</span>';
									$text_trans_content.='<span lang="'.$trans_language_iso.'">'.$text_trans.'</span>';
									$text_trans_content.='</div>';
									# Set the text translation content to the array.
									$display_content[$id]['text_trans']=$text_trans_content;
								}
							}

							# Check if there is a link.
							if(!empty($link))
							{
								# Set the link to a variable.
								$more_content='<a href="'.$link.'" class="more" target="_blank">'.$this->getMore().'</a>';
								# Check if there should be a button.
								if($buttons===TRUE)
								{
									# Replace the link with the button.
									$more_content='<a href="'.$link.'" class="button-more" target="_blank" title="Read More">More</a>';
								}
								# Set the more content to the array.
								$display_content[$id]['more']=$more_content;
							}

							# Check if there should be an edit button displayed.
							if($edit===TRUE)
							{
								# Set the edit button to a variable.
								$edit_content='<a href="'.ADMIN_URL.'ManageContent/'.$branch_folder.'/?edit&amp;post='.$id.'" class="button-edit" title="Edit this">Edit</a>';
								# Set the edit content to the array.
								$display_content[$id]['edit']=$edit_content;
							}

							# Check f there should be a delete button displayed.
							if($delete===TRUE)
							{
								# Set the delete button to a variable.
								$delete_content='<a href="'.ADMIN_URL.'ManageContent/'.$branch_folder.'/?delete&amp;post='.$id.'" class="button-delete" title="Delete This">Delete</a>';
								# Set the delete content to the array.
								$display_content[$id]['delete']=$delete_content;
							}

							# Check if there is a file and it should be displayed.
							if($file!==NULL && $buttons===TRUE)
							{
								# Check if the User is an admin user.
								if($login->checkAccess(ADMIN_USERS)===TRUE)
								{
									# Set the availability to 1(Yes, display) for this user. An admin may see anything.
									$file_availability=1;
								}
								# Check if the User is a managing user.
								elseif($login->checkAccess(MAN_USERS)===TRUE)
								{
									# Check if the files availability is 2(Internal document only).
									if($file_availability===2)
									{
										# Set the availability to 1(Yes, display) for this user.
										$file_availability=1;
									}
								}
								# Check if the file's availability is 1(Yes, display).
								if($file_availability===1)
								{
									# Set the download button to a variable.
									$download_content='<a href="'.APPLICATION_URL.'download/?f='.$file_name.(($premium===NULL) ? '' : '&amp;t=premium').'" class="button-download" title="Download '.str_replace('"', '`', $file_title).'">Download</a>';
									# Set the delete content to the array.
									$display_content[$id]['download']=$download_content;

									$file_content='<div class="file-info">';
									$file_header_content='<h4>File Details</h4>';
									$file_content.=$file_header_content;
									# Set the file info content to the array.
									$display_content[$id]['file']['header']=$file_header_content;
									$file_content.='<ul>';
									$file_content.='<li>';

									$file_name_content='<span class="file-name">';
									$file_name_content.='<span class="label">Name:</span> <a href="'.APPLICATION_URL.'download/?f='.$file_name.(($file_premium===NULL) ? '' : '&amp;t=premium').'" title="Download '.$file_name.'">'.$file_name.'</a>';
									$file_name_content.='</span>';
									$file_content.=$file_name_content;
									# Set the file info content to the array.
									$display_content[$id]['file']['name']=$file_name_content;

									$file_content.='</li>';
									$file_content.='<li>';

									$file_title_content='<span class="file-title">';
									$file_title_content.='<span class="label">Title:</span> <span>'.$file_title.'</span></span>';
									$file_content.=$file_title_content;
									# Set the file info content to the array.
									$display_content[$id]['file']['title']=$file_title_content;

									$file_content.='</li>';
									# Set the array value for the file author to NULL.
									$display_content[$id]['file']['author']=NULL;
									if($file_author!==NULL)
									{
										$file_content.='<li>';

										$file_author_content='<span class="file-author">';
										$file_author_content.='<span class="label">Author:</span> <span>'.$file_author.'</span></span>';
										$file_content.=$file_author_content;
										# Set the file info content to the array.
										$display_content[$id]['file']['author']=$file_author_content;

										$file_content.='</li>';
									}
									# Set the array value for the file publisher to NULL.
									$display_content[$id]['file']['publisher']=NULL;
									if($file_publisher!==NULL)
									{
										$file_content.='<li>';

										$file_publisher_content='<span class="file-publisher">';
										$file_publisher_content.='<span class="label">Publisher:</span> <a href="'.APPLICATION_URL.'profile/?publisher='.$file_publisher.'" title="'.$file_publisher.'">'.$file_publisher.'</a>';
										$file_publisher_content.='</span>';
										$file_content.=$file_publisher_content;
										# Set the file info content to the array.
										$display_content[$id]['file']['publisher']=$file_publisher_content;

										$file_content.='</li>';
									}

									# Set the array value for the file language to NULL.
									$display_content[$id]['file']['language']=NULL;
									# Check if there is a file language.
									if(!empty($file_language))
									{
										$file_content.='<li>';
										$file_language_content='<span class="file-language">';
										$file_language_content.='<span class="label">Language:</span> <span>'.$file_language.'</span></span>';
										$file_content.=$file_language_content;
										# Set the file info content to the array.
										$display_content[$id]['file']['language']=$file_language_content;
										$file_content.='</li>';
									}

									# Set the array value for the file publish year to NULL.
									$display_content[$id]['file']['year']=NULL;
									if($file_year!==NULL)
									{
										$file_content.='<li>';

										$file_year_content='<span class="file-year">';
										$file_year_content.='<span class="label">Publish Year:</span> <span>'.$file_year.'</span></span>';
										$file_content.=$file_year_content;
										# Set the file info content to the array.
										$display_content[$id]['file']['year']=$file_year_content;

										$file_content.='</li>';
									}
									# Set the array value for the file location to NULL.
									$display_content[$id]['file']['location']=NULL;
									if($file_location!==NULL)
									{
										$file_content.='<li>';

										$file_location_content='<span class="file-location">';
										$file_location_content.='<span class="label">Publish Location:</span> <span>'.$file_location.'</span></span>';
										$file_content.=$file_location_content;
										# Set the file info content to the array.
										$display_content[$id]['file']['location']=$file_location_content;

										$file_content.='</li>';
									}
									# Set the array value for the file contributor to NULL.
									$display_content[$id]['file']['contributor']=NULL;
									if($file_cont_id!==NULL)
									{
										# Set the file Contributor object to a variable.
										$file_contributor=$file->getContributor();
										# Set the file contributor's privacy setting to a variable.
										$file_cont_privacy=$file_contributor->getContPrivacy();
										# Check if the contributor should be hidden.
										if($file_cont_privacy!==NULL)
										{
											# Create a variable to hold the file contributor display XHTML and open a list tag.
											$display_file_cont='<li>';

											$file_contributor_content='<span class="file-contributor"><span class="label">File uploaded by:</span> <a href="'.APPLICATION_URL.'profile/?contributor='.$file_cont_id.'" title="'.$file_contributor->getContName().'">'.$file_contributor->getContName().'</a></span>';
											$display_file_cont.=$file_contributor_content;

											$display_file_cont.='</li>';
											# Check if the contributor should be displayed to all.
											if($file_cont_privacy==0)
											{
												# Add the file contributor display to the display content.
												$file_content.=$display_file_cont;
												# Set the file info content to the array.
												$display_content[$id]['file']['contributor']=$file_contributor_content;
											}
											# Check if the contributor should be displayed to logged in users only.
											elseif($file_cont_privacy==1)
											{
												# Check if the User is logged in.
												if($login->isLoggedIn()===TRUE)
												{
													# Add the file contributor display to the display content.
													$file_content.=$display_file_cont;
													# Set the file info content to the array.
													$display_content[$id]['file']['contributor']=$file_contributor_content;
												}
											}
										}
									}
									# Close the unordered list.
									$file_content.='</ul>';
									# Close the "file-info" div.
									$file_content.='</div>';
									# Set the file info content to the array.
									$display_content[$id]['file']['all']=$file_content;
								}
							}
						}
						else
						{
							# Make the display content array empty.
							$display_content[$id]=NULL;
						}
					}
				}
				return $display_content;
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displaySubContent

	/**
	 * getAudio
	 *
	 * Retrieves records from the `audio` table.
	 * A wrapper method for getAudio from the Audio class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	array $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	$and_sql				Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getAudio($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
 			# Get the Audio class.
 			require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
 			# Instantiate a new Audio object.
 			$audio_obj=new Audio();
 			# Get the audio.
 			$audio_obj->getAudio($limit, $fields, $order, $direction, $where);
 			# Set the retrieved audio to a variable.
 			$audio=$audio_obj->getAllAudio();
 			# Check if there were records retrieved.
 			if($audio!==NULL)
 			{
 				# Set the audio to the data member.
 				$this->setAllAudio($audio);
 				return TRUE;
 			}
 			# Return FALSE because no records were returned.
 			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getAudio

	/**
	 * getThisAudio
	 *
	 * Retrieves audio info from the `audio` table in the database for the passed id or audio name and sets it to the data member.
	 * A wrapper method for getThisAudio from the Audio class.
	 *
	 * @param	string $value			The name or id of the audio to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisAudio($value, $id=TRUE)
	{
		try
		{
 			# Get the Audio class.
 			require_once Utility::locateFile(MODULES.'Media'.DS.'Audio.php');
 			# Instantiate a new Audio object.
 			$audio_obj=new Audio();
 			# Get the info for this audio and set the return boolean to a variable.
 			$record_retrieved=$audio_obj->getThisAudio($value, $id);
 			# Set the audio object to the data member.
 			$this->setAudioObj($audio_obj);
 			# Check if there was an video retrieved.
 			if($record_retrieved===TRUE)
 			{
 				# Set the id to the data member.
 				$this->setAudioID($audio_obj->getID());
 				return TRUE;
 			}
 			# Set the audio id data member to NULL.
 			$this->setAudioID(NULL);
 			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisAudio

	/**
	 * getSubContent
	 *
	 * Retrieves SubContent records from the DataBase.
	 *
	 * @param	int/string $branches	The names and/or id's of the branch(es) to be retrieved.
	 *										May be multiple branches - separate with dash, ie. '50-60-Archives-110'
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $and_sql 		Extra AND statements in the query.
	 * @access	public
	 */
	public function getSubContent($branches=NULL, $limit=NULL, $fields='*', $order='date', $direction='DESC', $and_sql=NULL)
	{
		# Bring the Database object into scope.
		$db=DB::get_instance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Get the Branch class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
			# Instantiate a new Branch object.
			$branch_obj=new Branch();
			# Check if all categories are requested.
			if(strtolower($branches)!=='all')
			{
				# Create the WHERE portion of the SQL statement for the branches requested.
				$branch_obj->createWhereSQL($branches);
			}
			# Set the WHERE portion of the SQL statement for the branches requested to a variable.
			$where=$branch_obj->getWhereSQL();
			# Check if there should be a WHERE portion of the SQL statement.
			if(!empty($where) || !empty($and_sql))
			{
				$where=' WHERE'.((empty($where)) ? '' : ' '.$where).((empty($and_sql)) ? '' : ' '.$and_sql);
			}
			# Retrieve the records from the `subcontent` table.
			$records=$db->get_results('SELECT '.$fields.' FROM `'.DBPREFIX.'subcontent`'.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			# Check if there were records returned.
			if($records!==NULL)
			{
				# Set the records to the data member.
				$this->setAllSubContent($records);
				# Exploded the wanted branches into an array.
				$branches=explode('-', $branches);
				# Create a new array to hold the branch id's.
				$branch_ids=array();
				# Loop through the $branches array.
				foreach($branches as $branch)
				{
					# Clean it up.
					$branch=trim($branch);
					# Check if the branch is unwanted (beginning with an "!".)
					if(strpos($branch, '!')!==0)
					{
						if($validator->isInt($branch)===TRUE)
						{
							# Get the branch info.
							$this->getThisBranch($branch);
						}
						else
						{
							# Get the branch info.
							$this->getThisBranch($branch, FALSE);
						}
						# Get the Branch object.
						$branch_obj=$this->getBranch();
						# Set the id and name to the branch id's array.
						$branch_ids[$branch_obj->getID()]=$branch_obj->getBranch();
					}
				}
				# Set the branch id's array to the wanted_branches data member.
				$this->setWantedBranches($branch_ids);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getSubContent

	/**
	 * getThisSubContent
	 *
	 * Retrieves a post's info from the `subcontent` table in the Database for the passed value and related field and sets it to the data member.
	 *
	 * @param	string $value			The post ID.
	 * @param	string $field			The field in the `subcontent` table that $value is associated with.
	 * @return	boolean 				TRUE if a record is returned, FALSE if not.
	 * @access	public
	 */
	public function getThisSubContent($value=NULL, $field='id')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Check if the passed User ID is empty.
			if(!empty($value))
			{
				# Check if the passed $value is the contributor's id.
				if($field=='id')
				{
					# Set the post's id to the data member.
					$this->setID($value);
					# Reset the $value variable with the post's id.
					$value=$this->getID();
				}
				# Reset the $value variable as an array with the field as the key and the value as the value.
				$value=array($field=>$value);
				# Create the WHERE sql statement.
					# Create an empty array to hold the WHERE statement pieces.
					$where=array();
					# Loop throught the $value array.
					foreach($value as $field=>$f_value)
					{
						# Check if $t_value is NULL.
						if($f_value===NULL)
						{
							# Reset $t_value to search for NULL fields.
							$f_value='IS NULL';
						}
						else
						{
							# Set $t_value to search for the $t_value.
							$f_value='= '.$db->quote($db->escape($f_value));
						}
						$where[]='`'.$field.'` '.$f_value;
					}
					# Implode the $where array to join the pieces with "AND".
					$where=implode(' AND ', $where);
				# Retrieve the contributor from the `contributors` table.
				$row=$db->get_row('SELECT `id`, `title`, `link`, `file`, `availability`, `visibility`, `date`, `premium`, `branch`, `institution`, `publisher`, `text_language`, `text`, `trans_language`, `text_trans`, `hide`, `image`, `contributor`, `recent_contributor`, `last_edit` FROM `'.DBPREFIX.'subcontent` WHERE '.$where.' LIMIT 1');
				# Check if a record was returned.
				if($row!==NULL)
				{
					# Set the returned values to the data members.
					$this->setDataMembers($row);
					return TRUE;
				}
			}
			# Return FALSE because there was no record returned.
			return FALSE;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('There was an error retrieveing the contributor\'s data: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisSubContent

	/**
	 * getBranches
	 *
	 * Retrieves records from the `branches` table.
	 * A wrapper method for getBranches from the Branch class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where			Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getBranches($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Branch class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
			# Instantiate a new Branch object.
			$branch=new Branch();
			# Get the branches.
			$branch->getBranches($limit, $fields, $order, $direction, $where);
			# Set the retrieved catagories to a variable.
			$branches=$branch->getAllBranches();
			# Check if there were records retrieved.
			if(!empty($branches))
			{
				# Set the branches to the data member.
				$this->setAllBranches($branches);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getBranches

	/**
	 * getBranchFolder
	 *
	 * Returns the folder name for a branch based on the best suited branch id.
	 *
	 * @param	array $branch_ids		The branch id's associated with the post.
	 * @access	protected
	 */
	public function getBranchFolder($branch_ids)
	{
		try
		{
			# Get the common branch id.
			$branch_id=$this->getCommonBranchID($branch_ids);
			# Get the data for this branch.
			$this->getThisBranch($branch_id);
			# Set the Branch object to a variable.
			$branch=$this->getBranch();
			# Set the branch name to a variable.
			$branch_name=$branch->getBranch();
			# Check if the branch name is NOT all capital letter (an acronym).
			if(strtoupper($branch_name)!==$branch_name)
			{
				# Convert the branch name to all lowercase letters.
				$branch_name=strtolower($branch_name);
			}
			# Check if there are any spaces in the branch name.
			if(strpos($branch_name, ' ')!==FALSE)
			{
				# Capitalize the first letter of each word in the branch name.
				$branch_name=ucwords($branch_name);
				# Remove any spaces in the branch name and set it as the folder name.
				$branch_name=str_ireplace(' ', '', $branch_name);
			}
			# Set the branch name as the folder name.
			$branch_folder=$branch_name;
			return $branch_folder;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getBranchFolder

	/**
	 * getThisBranch
	 *
	 * Retrieves branch info from the `branches` table in the Database for the passed id or branch name and sets it to the data member.
	 * A wrapper method for getThisBranch from the Branch class.
	 *
	 * @param	string $value			The name or id of the branch to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisBranch($value, $id=TRUE)
	{
		try
		{
			# Get the Branch class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
			# Instantiate a new Branch object.
			$branch=new Branch();
			# Get the branch info.
			$branch->getThisBranch($value, $id);
			# Set the Branch object to the data member.
			$this->setBranch($branch);
			# Set the branch id to the data member.
			$this->setBranchID($branch->getID());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisBranch

	/**
	 * getContributors
	 *
	 * Retrieves records from the `contributors` table.
	 * A wrapper method for getContributors from the Contributor class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where			Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getContributors($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Contributor class.
			require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
			# Instantiate a new Contributor object.
			$contributor=new Contributor();
			# Get the contributors.
			$contributor->getContributors($limit, $fields, $order, $direction, $where);
			# Set the retrieved catagories to a variable.
			$contributors=$contributor->getAllContributors();
			# Check if there were records retrieved.
			if($contributors!==NULL)
			{
				# Set the contributors to the data member.
				$this->setAllContributors($contributors);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getContributors

	/**
	 * getThisContributor
	 *
	 * Retrieves a contributor's info from the `contributors` table in the Database for the passed value and related field and sets it to the data member.
	 * A wrapper method for getThisContributor from the Contributor class.
	 *
	 * @param	string $value 	May be the contributor ID, the contributor's User ID, the contributor's email, or the contributor's first and last names.
	 *								Names must be in an array.
	 * @param	string $field 	The field in the `contributors` table that $value is associated with.
	 * @param	boolean $find
	 * @access	public
	 */
	public function getThisContributor($value, $field='user', $find=TRUE)
	{
		try
		{
			# Get the Contributor class.
			require_once Utility::locateFile(MODULES.'User'.DS.'Contributor.php');
			# Instantiate a new Contributor object.
			$contributor=new Contributor();
			# Get the contributor info.
			$contributor->getThisContributor($value, $field, $find);
			# Set the contributor data to the data members.
			$this->setContributor($contributor);
			$this->setContID($contributor->getContID());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisContributor

	/**
	 * getFiles
	 *
	 * Retrieves records from the `files` table.
	 * A wrapper method for getFiles from the File class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where			Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getFiles($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the File class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
			# Instantiate a new File object.
			$file=new File();
			# Get the files.
			$file->getFiles($limit, $fields, $order, $direction, $where);
			# Set the retrieved files to a variable.
			$files=$file->getAllFiles();
			# Check if there were records retrieved.
			if($files!==NULL)
			{
				# Set the categories to the data member.
				$this->setAllFiles($files);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getFiles

	/**
	 * getThisFile
	 *
	 * Retrieves file info from the `files` table in the Database for the passed id or file name and sets it to the data member.
	 * A wrapper method for getThisFile from the File class.
	 *
	 * @param	string $value			The name or id of the file to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisFile($value, $id=TRUE)
	{
		try
		{
			# Get the File class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'File.php');
			# Instantiate a new File object.
			$file=new File();
			# Get the info for this file and set the return boolean to a variable.
			$record_retrieved=$file->getThisFile($value, $id);
			# Set the File object to the data member.
			$this->setFile($file);
			# Check if there was a file retrieved.
			if($record_retrieved===TRUE)
			{
				# Set the id to the data member.
				$this->setFileID($file->getID());
				return TRUE;
			}
			# Set the file id data member to NULL.
			$this->setFileID(NULL);
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisFile

	/**
	 * getImages
	 *
	 * Retrieves records from the `images` table.
	 * A wrapper method for getImages from the Image class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where			Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getImages($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Image class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
			# Instantiate a new Image object.
			$image=new Image();
			# Get the institutions.
			$image->getImages($limit, $fields, $order, $direction, $where);
			# Set the retrieved images to a variable.
			$images=$image->getAllImages();
			# Check if there were records retrieved.
			if($images!==NULL)
			{
				# Set the institutions to the data member.
				$this->setAllImages($images);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getImages

	/**
	 * getThisImage
	 *
	 * Retrieves image info from the `images` table in the Database for the passed id or image name and sets it to the data member.
	 * A wrapper method for getThisImage from the Image class.
	 *
	 * @param	string $value			The name or id of the image to retrieve.
	 * @param	boolean $id 			TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisImage($value, $id=TRUE)
	{
		try
		{
			# Get the Image class.
			require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
			# Instantiate a new Image object.
			$image_obj=new Image();
			# Get the info for this image and set the return boolean to a variable.
			$record_retrieved=$image_obj->getThisImage($value, $id);
			# Set the image object to the data member.
			$this->setImageObj($image_obj);
			# Check if there was an image retrieved.
			if($record_retrieved===TRUE)
			{
				# Set the id to the data member.
				$this->setImageID($image_obj->getID());
				return TRUE;
			}
			# Set the image id data member to NULL.
			$this->setImageID(NULL);
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisImage

	/**
	 * getInstitutions
	 *
	 * Retrieves records from the `institutions` table.
	 * A wrapper method for getInstitutions from the Institution class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where			Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getInstitutions($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Institution class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
			# Instantiate a new Institution object.
			$institution=new Institution();
			# Get the institutions.
			$institution->getInstitutions($limit, $fields, $order, $direction, $where);
			# Set the retrieved institutions to a variable.
			$institutions=$institution->getAllInstitutions();
			# Check if there were records retrieved.
			if($institutions!==NULL)
			{
				# Set the institutions to the data member.
				$this->setAllInstitutions($institutions);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getInstitutions

	/**
	 * getThisInstitution
	 *
	 * Retrieves institution info from the `institutions` table in the Database for the passed id or institution name and sets it to the data member.
	 * A wrapper method for getThisInstitution from the Institution class.
	 *
	 * @param	string $value 			The name or id of the institution to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisInstitution($value, $id=TRUE)
	{
		try
		{
			# Get the Institution class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Institution.php');
			# Instantiate a new Institution object.
			$institution=new Institution();
			# Get the info for this institution and set the return boolean to a variable.
			$record_retrieved=$institution->getThisInstitution($value, $id);
			# Set the institution object to the data member.
			$this->setInstitution($institution);
			# Check if there was a institution retrieved.
			if($record_retrieved===TRUE)
			{
				# Set the id to the data member.
				$this->setInstitutionID($institution->getID());
				return TRUE;
			}
			# Set the institution id data member to NULL.
			$this->setInstitutionID(NULL);
			return FALSE;

			# Set the institution id to the data member.
			$this->setInstitutionID($institution->getID());
			# Set the institution name to the data member.
			$this->setInstitution($institution->getInstitution());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisInstitution

	/**
	 * getLanguages
	 *
	 * Retrieves records from the `languages` table.
	 * A wrapper method for getLanguages from the Language class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where			Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getLanguages($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Language class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
			# Instantiate a new Language object.
			$language=new Language();
			# Get the languages.
			$language->getLanguages($limit, $fields, $order, $direction, $where);
			# Set the retrieved languages to a variable.
			$languages=$language->getAllLanguages();
			# Check if there were records retrieved.
			if($languages!==NULL)
			{
				# Set the categories to the data member.
				$this->setAllLanguages($languages);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getLanguages

	/**
	 * getThisLanguage
	 *
	 * Retrieves language info from the `languages` table in the Database for the passed id or language name and sets it to the data member.
	 * A wrapper method for getThisLanguage from the Language class.
	 *
	 * @param	string $value 			The name or id of the language to retrieve.
	 * @param	boolean $id 			TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisLanguage($value, $id=TRUE)
	{
		try
		{
			# Get the Language class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Language.php');
			# Instantiate a new Language object.
			$language=new Language();
			# Get the language info.
			$record_retrieved=$language->getThisLanguage($value, $id);
			# Check if there was a publisher retrieved.
			if($record_retrieved===TRUE)
			{
				# Set the language id to the data member.
				$this->setLanguageID($language->getID());
				# Set the language ISO Code to the data member.
				$this->setLanguageISO($language->getISO());
				# Set the language name to the data member.
				$this->setLanguage($language->getLanguage());
				return TRUE;
			}
			# Set the language id data member to NULL.
			$this->setLanguageID(NULL);
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisLanguage

	/**
	 * getPublishers
	 *
	 * Retrieves records from the `publishers` table.
	 * A wrapper method for getPublishers from the Publisher class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	string $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	string $where			Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getPublishers($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher=new Publisher();
			# Get the publishers.
			$publisher->getPublishers($limit, $fields, $order, $direction, $where);
			# Set the retrieved publishers to a variable.
			$publishers=$publisher->getAllPublishers();
			# Check if there were records retrieved.
			if($publishers!==NULL)
			{
				# Set the categories to the data member.
				$this->setAllPublishers($publishers);
				return TRUE;
			}
			# Return FALSE because no records were returned.
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getPublishers

	/**
	 * getThisPublisher
	 *
	 * Retrieves publisher info from the `publishers` table in the Database for the passed id or publisher name and sets it to the data member.
	 * A wrapper method for getThisPublisher from the Publisher class.
	 *
	 * @param	string $value			The name or id of the publisher to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisPublisher($value, $id=TRUE)
	{
		try
		{
			# Get the Publisher class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Publisher.php');
			# Instantiate a new Publisher object.
			$publisher=new Publisher();
			# Get the info for this publisher and set the return boolean to a variable.
			$record_retrieved=$publisher->getThisPublisher($value, $id);
			# Set the publisher object to the data member.
			$this->setPublisher($publisher);
			# Check if there was a publisher retrieved.
			if($record_retrieved===TRUE)
			{
				# Set the id to the data member.
				$this->setPublisherID($publisher->getID());
				return TRUE;
			}
			# Set the publisher id data member to NULL.
			$this->setPublisherID(NULL);
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisPublisher

	/**
	 * getVideos
	 *
	 * Retrieves records from the `videos` table.
	 * A wrapper method for getVideos from the Video class.
	 *
	 * @param	int $limit				The LIMIT of the records.
	 * @param	array $fields			The name of the field(s) to be retrieved.
	 * @param	string $order			The name of the field to order the records by.
	 * @param	string $direction		The direction to order the records.
	 * @param	$and_sql				Extra AND statements in the query.
	 * @return	boolean					TRUE if records are returned, FALSE if not.
	 * @access	public
	 */
	public function getVideos($limit=NULL, $fields='*', $order='id', $direction='ASC', $where='')
	{
		try
		{
 			# Get the Video class.
 			require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
 			# Instantiate a new Video object.
 			$video_obj=new Video();
 			# Get the institutions.
 			$video_obj->getVideos($limit, $fields, $order, $direction, $where);
 			# Set the retrieved videos to a variable.
 			$videos=$video_obj->getAllVideos();
 			# Check if there were records retrieved.
 			if($videos!==NULL)
 			{
 				# Set the institutions to the data member.
 				$this->setAllVideos($videos);
 				return TRUE;
 			}
 			# Return FALSE because no records were returned.
 			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getVideos

	/**
	 * getThisVideo
	 *
	 * Retrieves video info from the `videos` table in the Database for the passed id or video name and sets it to the data member.
	 * A wrapper method for getThisVideo from the Video class.
	 *
	 * @param	string $value			The name or id of the video to retrieve.
	 * @param	boolean $id				TRUE if the passed $value is an id, FALSE if not.
	 * @access	public
	 */
	public function getThisVideo($value, $id=TRUE)
	{
		try
		{
 			# Get the Video class.
 			require_once Utility::locateFile(MODULES.'Media'.DS.'Video.php');
 			# Instantiate a new Video object.
 			$video_obj=new Video();
 			# Get the info for this video and set the return boolean to a variable.
 			$record_retrieved=$video_obj->getThisVideo($value, $id);
 			# Set the video object to the data member.
 			$this->setVideoObj($video_obj);
 			# Check if there was an video retrieved.
 			if($record_retrieved===TRUE)
 			{
 				# Set the id to the data member.
 				$this->setVideoID($video_obj->getID());
 				return TRUE;
 			}
 			# Set the video id data member to NULL.
 			$this->setVideoID(NULL);
 			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getThisVideo

	/**
	 * returnYears
	 *
	 * Returns the years in the database.
	 *
	 * @param	$branches
	 * @param	string $direction		The direction to ORDER BY.
	 * @param	int $limit				The limit of records to query.
	 * @param	string $and_sql			Extra AND statements in the query.
	 * @access	public
	 */
	public function returnYears($branches=NULL, $direction='DESC', $limit=NULL, $and_sql=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Get the Branch class.
			require_once Utility::locateFile(MODULES.'Content'.DS.'Branch.php');
			# Instantiate a new Branch object.
			$branch=new Branch();
			# Create the WHERE clause for the passed $branches string.
			$branch->createWhereSQL($branches);
			# Set the newly created WHERE clause to a variable.
			$where=$branch->getWhereSQL();
			# Retrieve records by year.
			$years=$db->get_results('SELECT DISTINCT YEAR(`date`) AS `year` FROM `'.DBPREFIX.'subcontent` WHERE '.$where.' '.(($and_sql===NULL) ? '' : $and_sql).' ORDER BY `date` '.$direction.' '.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			# Check if results were retruned.
			if($years===NULL)
			{
				# Explicitly return NULL.
				return NULL;
			}
			return $years;
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- returnYears

	/**
	 * setDataMembers
	 *
	 * Sets all the data returned in a row from the `subcontent` table to the appropriate Data members.
	 *
	 * @param	$row					The returned row of data from a record to set to the data members.
	 * @access	public
	 */
	public function setDataMembers($row)
	{
		# Bring the content instance into scope.
		$main_content=Content::getInstance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Explicitly make the row an object.
			$row=(object)$row;

			# Set SubContent id to the data member.
			$this->setID($row->id);

			# Set SubContent availability to the data member.
			$this->setAvailability($row->availability);

			# Set the branch id(s) to the data member.
			$this->setRecordBranches($row->branch);

			# Check if there is a recent contributor value.
			if(isset($row->recent_contributor))
			{
				# Retrieve the recent contributor info from the `contributors` table via the recent contributor id returned in the $row data.
				$this->getThisContributor($row->recent_contributor, 'id', FALSE);
				# Set the recent_cont_id data member to the cont_id data member.
				$this->setRecentContID($this->getContID());
				# Reset contributor and cont_id data members to NULL.
				$this->setContributor(NULL);
				$this->setContID(NULL);
			}
			else
			{
				# Explicitly set the recent contributor id to NULL.
				$this->setRecentContID(NULL);
			}

			# Check if there is a contributor value.
			if(isset($row->contributor))
			{
				# Retrieve the contributor info from the `contributors` table via the contributor id returned in the $row data.
				$this->getThisContributor($row->contributor, 'id', FALSE);
			}
			else
			{
				# Explicitly set the contributor id to NULL.
				$this->setContID(NULL);
			}

			# Set the file value to a variable.
			$file_id=$row->file;
			# Create an empty variable for the file date.
			$file_date=NULL;
			# Check if the file id is empty.
			if(!empty($file_id))
			{
				# Retrieve the file info from the `files` table via the file id returned in the $row data.
				$this->getThisFile($file_id);
				$file=$this->getFile();
				# Set the file date to a variable.
				$file_date=$file->getDate();
			}
			else
			{
				# Explicitly set the file data members to NULL.
				$this->setFile(NULL);
				$this->setFileID(NULL);
				$this->setFileInfoDisplay(NULL);
			}

			# Set the date to NULL.
			$this->setDate(NULL);
			# Check if there is an actual date returned.
			if($row->date!='0000-00-00')
			{
				# Set SubContent's post/edit date to the data member.
				$this->setDate($row->date);
			}
			# Check if there is a date associated with a file associated with this row.
			elseif($file_date!='0000-00-00')
			{
				# Check if the file date is empty.
				if(!empty($file_date))
				{
					# Set SubContent's post/edit date from the file date to the data member.
					$this->setDate($file_date);
				}
			}

			# Check if there is a last edit value.
			if(isset($row->last_edit))
			{
				# Set the last edit date.
				$this->setLastEdit($row->last_edit);
			}
			else
			{
				# Explicitly set the last_edit value to NULL.
				$this->setLastEdit(NULL);
			}

			# Retrieve the image info from the `images` table via the image id returned in the $row data.
			$this->getThisImage($row->image);

			# Set the institution to a variable.
			$inst_id=$row->institution;
			# Check if the institution is empty.
			if(!empty($inst_id))
			{
				# Retrieve the institution info from the `institutions` table via the institution id returned in the $row data.
				$this->getThisInstitution($inst_id);
			}
			else
			{
				$this->setInstitutionID(NULL);
			}

			# The the site name.
			$site_name=$main_content->getSiteName();
			# Set the SubContent link to a variable.
			$link=$row->link;
			# Replace any tokens with their correlating value.
			$link=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $link);
			# Set SubContent link to the data member.
			$this->setLink($link);

			# Set whether this SubContent is "premium" content or not to the data member.
			$this->setPremium($row->premium);
			# Check if there is a file.
			if(!empty($file_id))
			{
				# Set the file's premium value to the SubContent data member.
				$this->setPremium($file->getPremium());
			}

			# Retrieve the publisher info from the `publishers` table via the publisher id returned in the $row data.
			$this->getThisPublisher($row->publisher);

			# Set the SubContent text to a variable.
			$text=$row->text;
			# Replace any tokens with their correlating value.
			$text=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $text);
			# Strip slashes and decode any html entities.
			$text=((empty($text)) ? '' : html_entity_decode(stripslashes($text), ENT_COMPAT, 'UTF-8'));
			# Convert new lines to <br />.
			$text=nl2br($text);
			# Set the SubContent text to the data member.
			$this->setText($text);

			# Set the text language id to a variable.
			$text_language_id=$row->text_language;
			# Check if a text language is empty.
			if(!empty($text_language_id))
			{
				# Set the id variable to FALSE by default.
				$is_id=FALSE;
				# Check if the text language id is an integer.
				if($validator->isInt($text_language_id)===TRUE)
				{
					# Set the id variable to TRUE.
					$is_id=TRUE;
				}
				# Retrieve the text language from the `languages` table via the language id returned in the $row data.
				$this->getThisLanguage($text_language_id, $is_id);
				# Set the SubContent text's language to the data member.
				$this->setTextLanguage($this->getLanguage());
				# Set the SubContent text's language ISO Code to the data member.
				$this->setTextLanguageISO($this->getLanguageISO());
			}

			# Set the SubContent translated text to a variable.
			$text_trans=$row->text_trans;
			# Replace any tokens with their correlating value.
			$text_trans=str_ireplace(array('%{domain_name}', '%{site_name}', '%{fw_popup_handle}'), array(DOMAIN_NAME, $site_name, FW_POPUP_HANDLE), $text_trans);
			# Strip slashes and decode any html entities.
			$text_trans=((empty($text_trans)) ? '' : html_entity_decode(stripslashes($text_trans), ENT_COMPAT, 'UTF-8'));
			# Convert new lines to <br />.
			$text_trans=nl2br($text_trans);
			# Set SubContent translated text to the data member.
			$this->setTextTrans($text_trans);

			# Set the translated text language id to a variable.
			$trans_language_id=$row->trans_language;
			# Check if a translated text language is empty.
			if(!empty($trans_language_id))
			{
				# Set the id variable to FALSE by default.
				$is_id=FALSE;
				# Check if the text translation language id is an integer.
				if($validator->isInt($trans_language_id)===TRUE)
				{
					# Set the id variable to TRUE.
					$is_id=TRUE;
				}
				# Retrieve the translated text language from the `languages` table via the language id returned in the $row data.
				$this->getThisLanguage($trans_language_id, $is_id);
				# Set the SubContent translated text's language to the data member.
				$this->setTransLanguage($this->getLanguage());

				# Set the SubContent translated text's language ISO Code to the data member.
				$this->setTransLanguageISO($this->getLanguageISO());
			}

			# Set the SubContent title to a variable.
			$title=$row->title;
			# Strip slashes and decode any html entities.
			$title=((empty($title)) ? '' : html_entity_decode(stripslashes($title), ENT_COMPAT, 'UTF-8'));
			# Set SubContent title to the data member.
			$this->setTitle($title);

			# Set whether to hide this SubContent or not to the data member.
			$this->setHide($row->hide);

			# Set SubContent visibility to the data member.
			$this->setVisibility($row->visibility);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setDataMembers

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * getCommonBranchID
	 *
	 * Compares the "wanted branches", the passed ids, and a logged in User's access levels and returns the first most common id it finds.
	 * Priority is given to the "wanted branch" to User level over the passed branch id to User level relationships.
	 *
	 * @param	array $branch_ids		The branch id's associated with the post.
	 * @access	protected
	 */
	protected function getCommonBranchID($branch_ids)
	{
		# Bring the Login object onto scope.
		global $login;

		try
		{
			# Get the wanted branches.
			$wanted_branches=$this->getWantedBranches();
			# Create an emty array to hold the branch id candidates.
			$id_candidates=array();
			# Loop through the record's branch ids.
			foreach($branch_ids as $branch_id)
			{
				# Loop through the wanted branches.
				foreach($wanted_branches as $wanted_branch_id=>$wanted_branch)
				{
					# Check if the branch id is the wanted branch id.
					if($wanted_branch_id==$branch_id)
					{
						# Set the branch id to the cndidates array.
						$id_candidates[]=$branch_id;
					}
				}
			}
			# Check if the User is logged in.
			if($login->isLoggedIn()===TRUE)
			{
				# Find the User's access level(s) and set the array to a variable.
				$user_levels=$login->findUserLevel();
				# Loop through the User's access levels.
				foreach($user_levels as $level)
				{
					# Change the value of the level number to the branch id number (always ends with '0'.)
					$level=substr_replace($level, 0, -1, 1);
					# Check if the User level matches any of the branch id candidates.
					if(in_array($level, $id_candidates))
					{
						# Return the first branch id that matches all three ids.
						return $level;
					}
					# Check if the User level matches any of the post's branch ids.
					elseif(in_array($level, $branch_ids))
					{
						# Return the first branch id that matches.
						return $level;
					}
				}
			}
			# Return the first branch id that matches a "wanted branch".
			return $id_candidates[0];
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getCommonBranchID

	/**
	 * getPostDomain
	 *
	 * Returns a domain for a post based on the best suited branch id.
	 *
	 * @param	array $branch_ids		The branch id's associated with the post.
	 * @access	protected
	 */
	protected function getPostDomain($branch_ids)
	{
		try
		{
			# Get the common branch id.
			$branch_id=$this->getCommonBranchID($branch_ids);
			# Get the data for this branch.
			$this->getThisBranch($branch_id);
			# Set the Branch object to a variable.
			$branch=$this->getBranch();
			# Set the branch's domain to a variable.
			$branch_domain='http://'.$branch->getDomain().'/';
			return $branch_domain;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getPostDomain

	/*** End protected methods ***/

} #=== End SubContent class.