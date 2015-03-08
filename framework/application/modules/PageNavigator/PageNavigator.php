<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * PageNavigator
 *
 * Class for navigating over multiple pages.
 */
class PageNavigator
{
	/*** data members ***/

	private $pagename;
	private $total_pages;
	private $total_records;
	private $records_per_page;
	private $offset;
	private $record_offset;
	private $max_pages_shown;
	private $current_start_page;
	private $current_end_page;
	private $current_page;

	# next and previous inactive
	private $span_next_inactive;
	private $span_previous_inactive;

	# first and last inactive
	private $first_inactive_span;
	private $last_inactive_span;

	# must match $_GET['offset'] in calling page
	private $first_param_name='offset';

	# use as "&name=value" pair for getting
	private $params;

	# css class names
	private $inactive_span_name='inactive';
	private $page_display_div_name='total_pagesdisplay';
	private $div_wrapper_name='pagenavigator';

	# text for navigation
	private $str_first='First';
	private $str_next='Next';
	private $str_previous='Prev';
	private $str_last='Last';

	# for error reporting
	private $error_string;

	/*** End data members ***/



	/*** magic methods ***/

	# constructor
	public function __construct($perpage, $maxshown, $page_name, $first_param_name, $total_records, $params='')
	{
		# Max records per page
		$this->setRecordsPerPage($perpage);
		# Set the Maximum pages shown.
		$this->setMaxPagesShown($maxshown);
		# What page are we at?
		$this->setPageName($page_name);
		# Already the default value but make explicit
		$this->setFirstParamName($first_param_name);
		# Get number of records for pagination.
		$this->setTotalRecords($total_records);
		$this->calculateTotalPages($this->getTotalRecords(), $this->getRecordsPerPage());
		$this->setOffset($offset=NULL);
		$this->calculateRecordOffset();
		# already urlencoded
		$this->setParams($params);
		# check record_offset a multiple of records_per_page
		$this->checkRecordOffset($this->getRecordOffset(), $this->getRecordsPerPage());
		$this->calculateCurrentPage();
		$this->createInactiveSpans();
		$this->calculateCurrentStartPage();
		$this->calculateCurrentEndPage();
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	public function setPageName($page)
	{
		if(!empty($page))
		{
			$this->pagename=$page;
		}
		else
		{
			throw new Exception('You must set the name of the page!');
		}
	} #==== End -- setPageName

	private function setTotalPages($total_pages)
	{
		$this->total_pages=(int)$total_pages;
	} #==== End -- setTotalPages

	public function setTotalRecords($total_records)
	{
		if(!empty($total_records))
		{
			$this->total_records=(int)$total_records;
		}
		else
		{
			# Explicitly set the value to 0.
			$this->total_records=0;
		}
	} #==== End -- setTotalRecords

	public function setRecordsPerPage($perpage)
	{
		if(!empty($perpage))
		{
			$this->records_per_page=(int)$perpage;
		}
		else
		{
			throw new Exception('You must set the RecordsPerPage with an integer!');
		}
	} #==== End -- setRecordsPerPage

	/**
	 * setOffset
	 *
	 * Sets the data member $offset.
	 * Calculates the total offset. (If $offset is 0, 0 times anything is zero so the total offset will be 0.)
	 *
	 * @access	private
	 */
	private function setOffset($offset=NULL)
	{
		# Check if the offset was passed.
		if($offset!==NULL)
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed value is an integer that doesn't equal 0.
			if($validator->isInt($offset)===TRUE && $offset!=0)
			{
				# Reset the variable explicitly making it an integer minus one.
				$offset=(int)$_GET[$this->getFirstParamName()];
			}
			else
			{
				# Explicitly set the data member to 0.
				$offset=0;
			}
		}
		else
		{
			$offset=$this->captureGetData();
		}
		if($offset>$this->getTotalPages())
		{
			$offset=$this->getTotalPages();
		}
		# Set the data member.
		$this->offset=(($offset!=0) ? $offset-1 : 0);
	} #==== End -- setOffset

	private function setRecordOffset($record_offset)
	{
		$this->record_offset=(int)$record_offset;
	} #==== End -- setRecordOffset

	public function setMaxPagesShown($max_pages_shown)
	{
		$this->max_pages_shown=(int)$max_pages_shown;
	} #==== End -- setMaxPagesShown

	private function setCurrentStartPage($current_start_page)
	{
		$this->current_start_page=(int)$current_start_page;
	} #==== End -- setCurrentStartPage

	private function setCurrentEndPage($current_end_page)
	{
		$this->current_end_page=(int)$current_end_page;
	} #==== End -- setCurrentStartPage

	public function setCurrentPage($current_page)
	{
		$this->current_page=(int)$current_page;
	} #==== End -- setCurrentPage

	public function setSpanNextInactive($span_next_inactive)
	{
		$this->span_next_inactive=$span_next_inactive;
	} #==== End -- setSpanNextInactive

	public function setSpanPreviousInactive($span_previous_inactive)
	{
		$this->span_previous_inactive=$span_previous_inactive;
	} #==== End -- setSpanPreviousInactive

	public function setFirstInactiveSpan($first_inactive_span)
	{
		$this->first_inactive_span=$first_inactive_span;
	} #==== End -- setFirstInactiveSpan

	public function setLastInactiveSpan($last_inactive_span)
	{
		$this->last_inactive_span=$last_inactive_span;
	} #==== End -- setLastInactiveSpan

	public function setFirstParamName($name)
	{
		$this->first_param_name=$name;
	} #==== End -- setFirstParamName

	public function setParams($params)
	{
		$this->params=$params;
	} #==== End -- setParams

	# give css class name to inactive span
	public function setInactiveSpanName($name)
	{
		$this->inactive_span_name=$name;
		# call function to rename span
		$this->createInactiveSpans();
	} #==== End -- setInactiveSpanName

	public function setPageDisplayDivName($name)
	{
		$this->page_display_div_name=$name;
	} #==== End -- setPageDisplayDivName

	public function setDivWrapperName($name)
	{
		$this->div_wrapper_name=$name;
	} #==== End -- setDivWrapperName

	public function setStrFirst($str_first)
	{
		$this->str_first=$str_first;
	} #==== End -- setStrFirst

	public function setStrNext($str_next)
	{
		$this->str_next=$str_next;
	} #==== End -- setStrNext

	public function setStrPrevious($str_previous)
	{
		$this->str_previous=$str_previous;
	} #==== End -- setStrPrevious

	public function setStrLast($str_last)
	{
		$this->str_last=$str_last;
	} #==== End -- setStrLast

	public function setErrorString($error_string)
	{
		$this->error_string=$error_string;
	} #==== End -- setErrorString

	/*** End mutator methods ***/



	/*** accessor methods ***/

	private function getPageName()
	{
		return $this->pagename;
	} #==== End -- getPageName

	public function getTotalPages()
	{
		return $this->total_pages;
	} #==== End -- getTotalPages

	public function getTotalRecords()
	{
		return $this->total_records;
	} #==== End -- getTotalRecords

	public function getRecordsPerPage()
	{
		return $this->records_per_page;
	} #==== End -- getRecordsPerPage

	private function getOffset()
	{
		return $this->offset;
	} #==== End -- getOffset

	public function getRecordOffset()
	{
		return $this->record_offset;
	} #==== End -- getRecordOffset

	private function getMaxPagesShown()
	{
		return $this->max_pages_shown;
	} #==== End -- getMaxPagesShown

	private function getCurrentStartPage()
	{
		return $this->current_start_page;
	} #==== End -- getCurrentStartPage

	private function getCurrentEndPage()
	{
		return $this->current_end_page;
	} #==== End -- getCurrentEndPage

	public function getCurrentPage()
	{
		return $this->current_page;
	} #==== End -- getCurrentPage

	private function getSpanNextInactive()
	{
		return $this->span_next_inactive;
	} #==== End -- getSpanNextInactive

	private function getSpanPreviousInactive()
	{
		return $this->span_previous_inactive;
	} #==== End -- getSpanPreviousInactive

	private function getFirstInactiveSpan()
	{
		return $this->first_inactive_span;
	} #==== End -- getFirstInactiveSpan

	private function getLastInactiveSpan()
	{
		return $this->last_inactive_span;
	} #==== End -- getLastInactiveSpan

	public function getFirstParamName()
	{
		return $this->first_param_name;
	} #==== End -- getFirstParamName

	public function getParams()
	{
		return $this->params;
	} #==== End -- getParams

	private function getInactiveSpanName()
	{
		return $this->inactive_span_name;
	} #==== End -- getInactiveSpanName

	private function getPageDisplayDivName()
	{
		return $this->page_display_div_name;
	} #==== End -- getPageDisplayDivName

	private function getDivWrapperName()
	{
		return $this->div_wrapper_name;
	} #==== End -- getDivWrapperName

	private function getStrFirst()
	{
		return $this->str_first;
	} #==== End -- getStrFirst

	private function getStrNext()
	{
		return $this->str_next;
	} #==== End -- getStrNext

	private function getStrPrevious()
	{
		return $this->str_previous;
	} #==== End -- getStrPrevious

	private function getStrLast()
	{
		return $this->str_last;
	} #==== End -- getStrLast

	private function getErrorString()
	{
		return $this->error_string;
	} #==== End -- getErrorString

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * calculateNumberPages
	 *
	 * Calculates the total number of pages in our pagination.
	 *
	 * @access	public
	 */
	public function calculateNumberPages()
	{
		$numberpages= ceil($this->getTotalRecords()/$this->getRecordsPerPage());
		return $numberpages;
	} #==== End -- calculateNumberPages

	/**
	 * getNavigator
	 *
	 * Returns HTML code for the navigator if there is more than one page to display.
	 *
	 * @access	public
	 */
	public function getNavigator()
	{
		if($this->calculateNumberPages()>1)
		{
			# wrap in div tag
			$strnavigator='<div class="'.$this->div_wrapper_name.'">';
			# Check if the curent page is the very first page.
			if($this->getCurrentPage()==0)
			{
				# Output movefirst button.
				$strnavigator.=$this->getFirstInactiveSpan();
				# Output moveprevious button.
				$strnavigator.=$this->getSpanPreviousInactive();
			}
			else
			{
				$strnavigator.=$this->createLink(0, $this->getStrFirst(), 'First Page', 'page-first');
				$strnavigator.=$this->createLink($this->getCurrentPage()-1, $this->getStrPrevious());
			}
			# Loop through displayed pages from $currentstart.
			for($x=$this->getCurrentStartPage(); $x<$this->getCurrentEndPage(); $x++)
			{
				# Make the current page inactive.
				if($x==$this->getCurrentPage())
				{
					$strnavigator.='<span class="'.$this->getInactiveSpanName().'">';
					$strnavigator.=$x+1;
					$strnavigator.='</span>';
				}
				else
				{
					$strnavigator.=$this->createLink($x, $x+1);
				}
			}
			# Output the "next" button.
			if($this->getCurrentPage()==$this->getTotalPages()-1)
			{
				$strnavigator.=$this->getSpanNextInactive();
			}
			else
			{
				$strnavigator.=$this->createLink($this->getCurrentPage()+1,$this->getStrNext());
			}
			# move last button
			if($this->getCurrentPage()==$this->getTotalPages()-1)
			{
				$strnavigator.=$this->getLastInactiveSpan();
			}
			else
			{
				$strnavigator.=$this->createLink($this->getTotalPages()-1, $this->getStrLast(), 'Last Page', 'page-last');
			}
			$strnavigator.=$this->getPageNumberDisplay();
			$strnavigator.='</div>';
			return $strnavigator;
		}
		else { return FALSE; }
	} #==== End -- getNavigator

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * captureGetData
	 *
	 * Returns GET data for the page number.
	 *
	 * @access	private
	 */
	private function captureGetData()
	{
		# Check if the passed GET value is set and not empty.
		if(isset($_GET[$this->getFirstParamName()]) && !empty($_GET[$this->getFirstParamName()]))
		{
			# Set the Validator instance to a variable.
			$validator=Validator::getInstance();
			# Check if the passed GET value is an integer.
			if($validator->isInt($_GET[$this->getFirstParamName()])===TRUE)
			{
				# Set the data member explicitly making it an integer minus one.
				$get=(int)$_GET[$this->getFirstParamName()];
				return $get;
			}
		}
		# Explicitly return 0.
		return 0;
	} #==== End -- captureGetData

	private function createLink($offset, $strdisplay, $title=NULL, $class=NULL)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the offset is greater than 0.
		if($offset!=0)
		{
			# Build the GET query.
			$url_end=$this->getPageName().'?'.$this->getFirstParamName().'='.($offset+1).(($this->getParams()!='') ? '&amp;'.$this->getParams() : '');
		}
		else
		{
			# Build the GET query.
			$url_end=$this->getPageName().(($this->getParams()!='') ? '?'.$this->getParams() : '');
		}
		# Build the default URL with the new GET query.
		$url=APPLICATION_URL.HERE.$url_end;
		# Check if this is a secure page.
		if($validator->isSSL()===TRUE)
		{
			# Rebuild the URL with the new GET query.
			$url=SECURE_URL.SECURE_HERE.$url_end;
		}
		# Remove the index.
		$url=WebUtility::removeIndex($url);
		$strtemp='<a href="'.$url.'"'.(($title!==NULL) ? ' title="'.$title.'"' : '').(($class!==NULL) ? ' class="'.$class.'"' : '').'>'.$strdisplay.'</a>';
		return $strtemp;
	} #==== End -- createLink

	private function getPageNumberDisplay()
	{
		$str='<span class="'.$this->getPageDisplayDivName().'">Page ';
		$str .= $this->getCurrentPage()+1;
		$str .= ' of '.$this->getTotalPages();
		$str .= '</span>';
		return $str;
	} #==== End -- getPageNumberDisplay

	private function calculateTotalPages($total_records, $records_per_page)
	{
		$this->setTotalPages(ceil($total_records/$records_per_page));
	} #==== End -- calculateTotalPages

	/**
	 * calculateRecordOffset
	 *
	 * Calculates the total offset. (If $offset is 0, 0 times anything is zero so the total offset will be 0.)
	 *
	 * @access	private
	 */
	private function calculateRecordOffset()
	{
		$offset=$this->getOffset();
		$perpage=$this->getRecordsPerPage();
		$record_offset=($offset * $perpage);
		$this->setRecordOffset($record_offset);
	} #==== End -- calculateRecordOffset

	private function checkRecordOffset($record_offset, $records_per_page)
	{
		$bln=TRUE;
		if($record_offset%$records_per_page != 0)
		{
			throw new Exception('Error - not a multiple of records per page.');
			$bln=FALSE;
		}
		return $bln;
	} #==== End -- checkRecordOffset

	private function calculateCurrentPage()
	{
		$record_offset=$this->getRecordOffset();
		$records_per_page=$this->getRecordsPerPage();
		$this->setCurrentPage($record_offset/$records_per_page);
	} #==== End -- calculateCurrentPage

	# not always needed but create anyway
	private function createInactiveSpans()
	{
		if(($this->getCurrentPage()+1)!=$this->getTotalPages())
		{
			$this->setSpanNextInactive('<span class="'.$this->getInactiveSpanName().'">'.$this->getStrNext().'</span>');
		}
		$this->getLastInactiveSpan('<span class="'.$this->getInactiveSpanName().'">'.$this->getStrLast().'</span>');
		if(($this->getCurrentPage())!=0)
		{
			$this->setSpanPreviousInactive('<span class="'.$this->getInactiveSpanName().'">'.$this->getStrPrevious().'</span>');
		}
		$this->getFirstInactiveSpan('<span class="'.$this->getInactiveSpanName().'">'.$this->getStrFirst().'</span>');
	} #==== End -- createInactiveSpans

	# find start page based on current page
	private function calculateCurrentStartPage()
	{
		$temp=floor($this->getCurrentPage()/$this->getMaxPagesShown());
		$this->setCurrentStartPage($temp * $this->getMaxPagesShown());
	} #==== End -- calculateCurrentStartPage

	private function calculateCurrentEndPage()
	{
		$this->setCurrentEndPage($this->getCurrentStartPage()+$this->getMaxPagesShown());
		if ($this->getCurrentEndPage()>$this->getTotalPages())
		{
			$this->setCurrentEndPage($this->getTotalPages());
		}
	} #==== End -- calculateCurrentEndPage

	/*** End private methods ***/

} #=== End PageNavigator class.