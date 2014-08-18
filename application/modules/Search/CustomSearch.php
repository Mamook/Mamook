<?php

require_once 'Search.php';

/**
* CustomSearch
*
* The CustomSearch Class is used to search through tables specific to the website in a MYSQL database for matching (or similar) text.
*
*/
class CustomSearch extends Search
{
	/*** data members ***/

	protected $announcement_num=0;
	protected $page_num=0;
	protected $errors=array();

	/*** End data members ***/



	/*** magic methods ***/

	/**
	* __construct
	*
	* @access	public
	*/
	public function __construct()
	{
		return;
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setANNOUNCEMENT_Num
	 *
	 * Sets the data member $announcement_num.
	 *
	 * @param		$announcement_num (The number of results from the "Announcement" search.)
	 * @access	protected
	 */
	protected function setANNOUNCEMENT_Num($announcement_num)
	{
		# Set the variable.
		$this->announcement_num=$announcement_num;
	} #==== End -- setANNOUNCEMENT_Num

	/**
	 * setPageNum
	 *
	 * Sets the data member $page_num.
	 *
	 * @param		$page_num (The number of results from the general page search.)
	 * @access	protected
	 */
	protected function setPageNum($page_num)
	{
		# Set the variable.
		$this->page_num=$page_num;
	} #==== End -- setPageNum

	/**
	* setErrors
	*
	* Sets the data member $errors.
	*
	* @param	$error (The error string to set.)
	* @access	public
	*/
	public function setErrors($error)
	{
		$error=trim($error);
		$this->errors[]=$error;
	} #==== End -- setErrors

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getANNOUNCEMENT_Num
	 *
	 * Returns the data member $announcement_num.
	 *
	 * @access	public
	 */
	public function getANNOUNCEMENT_Num($text=TRUE)
	{
		$num=(int)$this->announcement_num;
		if($num>0)
		{
			if($text===TRUE)
			{
				$num='<h3><a href="#Announcement">You search returned '.$this->announcement_num.' results from Announcements</a></h3>';
			}
		}
		elseif(($num<1) && ($text===TRUE))
		{
			$num='<h3>You search returned no results from Announcements</h3>';
		}
		return $num;
	} #==== End -- getANNOUNCEMENT_Num

	/**
	 * getPageNum
	 *
	 * Returns the data member $page_num.
	 *
	 * @access	public
	 */
	public function getPageNum($text=TRUE)
	{
		$num=(int)$this->page_num;
		if($num>0)
		{
			if($text===TRUE)
			{
				$num='<h3><a href="#page">Your search returned '.$this->page_num.' results from the general website pages</h3>';
			}
		}
		elseif(($num<1) && ($text===TRUE))
		{
			$num='<h3>Your search returned no results from the general website pages</h3>';
		}
		return $num;
	} #==== End -- getPageNum

	/**
	* getErrors
	*
	* Returns the data member $errors.
	*
	* @access	public
	*/
	public function getErrors()
	{
		return $this->errors;
	} #==== End -- getErrors

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * displayPageResults
	 *
	 * Displays the results of an ASP search.
	 *
	 * @access	public
	 */
	public function displayPageResults($fields, $and_sql=NULL, $order='page', $direction='DESC', $limit=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Check if the search form has been submitted.
		if(array_key_exists('_submit_check', $_POST))
		{
			$display_results='';
			if(!empty($_POST['searchterms']))
			{
				$where=$this->prepareWhere($_POST['searchterms'], $fields);

				try
				{
					$records=$db->get_results('SELECT `id`, `page_title`, `sub_title`, `content`, `sub_domain`, `page` FROM `'.DBPREFIX.'content` WHERE '.(($and_sql===NULL) ? '' : $and_sql.' AND').' '.$where.' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
					//$this->setAllPageContent($records);
				}
				catch(ezDB_Error $ez)
				{
					throw new Exception($ez, E_RECOVERABLE_ERROR);
				}
				catch(Exception $e)
				{
					throw $e;
				}
				$num=num_rows();
				$this->setPageNum($num);
				if($num>0)
				{
					$display_results.='<div id="page">';
					$display_results.='<h3><abbr title="General Site Results:</h3>';
					$display_results.='';
					$display_results.='</div>';
				}
			}
			return $display_results;
		}
	} #==== End -- displayPageResults

	/**
	 * duplicateSearch
	 *
	 * Displays the results of an ASP search.
	 *
	 * @access	public
	 */
	public function duplicateSearch($terms, $fields, $branches, $availability)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();
		# Bring the variable for the error_box title into scope
		global $alert_title;

		# Get the SubContent class.
		require_once MODULES.'Content'.DS.'SubContent.php';
		# Instantiate a new SubContent object.
		$subcontent=new SubContent();

		# Create an array of the passed branches.
		$branches=explode(' ', $branches);
		# Create an empty variable to hold the content to display.
		$display_results='';

		# Create a varible to hold the total number of matching records (initially start at zero).
		$total_num=0;
		# Create a variable to hold any error messages to send to the user.
		$error='';

		$iteration=0;
		$used_ids=array();
		# Loop through the passed projects.
		foreach($branches as $branch)
		{
			# Create a variable to hold the name of the variable that holds the branch's matching record.
			$content=$branch.'_content';
			# Change the branch name to uppercase.
			$branch_caps=strtoupper($branch);
			# Create a variable to hold the name of the method used to retrieve the number of records retrieved for this branch.
			$count_method='get'.$branch_caps.'_Num';

			$and_sql='';
			if(!empty($used_ids))
			{
				foreach($used_ids as $used_id)
				{
					$and_sql.=' AND `id` != '.$db->quote($used_id);
				}
			}
			# Search the SubContent table and put the results in the variable that holds the branches' matching records.
			$$content=$this->searchSubContent($terms, $fields, $branch, $availability, $and_sql);

			if(!empty($$content))
			{
				foreach($$content as $row)
				{
					if(!empty($row))
					{
						$used_ids[]=$row->id;
					}
				}
			}

			# Use the method to retrieve the number of records retrieved for this branch, telling it to not return text (just an integer) and set it to the $num variable.
			$num=$this->$count_method('no text');
			# Check if $num is greater than zero.
			if($num>0)
			{
				# Set the project to the branch Data member in the SubContent object.
				$subcontent->setBranch($branch);
				# Pass the retrieved records to the data member via the set method.
				$subcontent->setAllSubContent($$content);
				# Begin concatenating display content to the $display_results variable.
				$display_results.='<div id="'.$branch_caps.'">';
				$display_results.='<h3>'.$num.' record'.(($num>1) ? 's' : '').' in '.$branch_caps.' seemed to closely match the record you are adding. Please review '.(($num>1) ? 'them' : 'it').' below. If you feel that the record you are adding is actually unique, click the "Back" button below and re-submit your form (if you are uploading a file, you may need to re-locate it on you computer.) Click "Edit" if you would like to add the displayed post to your branch or to edit any of its content</h3>';
				# Set a title for the error_box.
				$alert_title='Alert!';
				# Display the record data without images.
				$display_results.=$subcontent->displaySubContent(FALSE, NULL, TRUE, TRUE);
				$display_results.='</div>';
				# Increment $total_num by the value of $num (the number of retrieved records in the current branch).
				$total_num=$total_num+$num;
			}
		}

		# Check if the total number of records retrieved is greater than zero.
		if($total_num>0)
		{
			# Set the $errors data member with an error message.
			$this->setErrors($total_num.' record'.(($num>1) ? 's' : '').' seemed to closely match the record you are adding.');
		}

		return $display_results;
	} #==== End -- duplicateSearch

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * searchSubContent
	 *
	 * Displays the results of an SubContent search.
	 *
	 * @param		$fields (The fileds in the DB to search through.)
	 * @param		$project (The name of the project to be retrieved, ie. 'fge')
	 * @access	protected
	 */
	protected function searchSubContent($terms, $fields, $project=NULL, $availability=' `availability` = 1', $and_sql='')
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Check if $availability was passed empty.
		if(empty($availability))
		{
			# Explicitly make it NULL.
			$availability=NULL;
		}
		# Prepare the WHERE portion of the sql query.
		$where=$this->prepareWhere($terms, $fields, $availability);

		# Get the SubContent Class
		require_once MODULES.'Content'.DS.'SubContent.php';
		# Instantiate a new SubContent object.
		$subcontent=new SubContent();
		# Retrieve all "available" records in the passed $project from the Database.
		$subcontent->getSubContent($project, NULL, '*', 'date', 'DESC', 'AND '.$where.((!empty($availability)) ? ' AND '.$availability : '').((!empty($and_sql)) ? $and_sql : ''));
		# Set the retrieved records to the data member in the SubContent object.
		$results=$subcontent->getAllSubContent();

		# Count the results.
		$num=count($results);
		# Convert the project to capital letters.
		$project_caps=strtoupper($project);
		# Creat a variable that is the name of the method for setting the number of returned results for this project.
		$count_method='set'.$project_caps.'_Num';
		# Set the data member that hold the count value for the project.
		$this->$count_method($num);
		return $results;
	} #==== End -- searchSubContent

	/*** End protected methods ***/

} # End CustomSearch class.