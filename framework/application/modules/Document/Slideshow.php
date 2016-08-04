<?php /* framework/application/modules/Document/Slideshow.php */

class Slideshow
{
	/*** data members ***/

	private static $slideshow;
	private $after_end='function(){}';
	private $auto='null';
	private $before_start='function(){}';
	private $button_next='arrow-next';
	private $button_previous='arrow-prev';
	private $circular='false';
	private $scroll=2;
	private $selector='.slideshow';
	private $speed=800;
	private $start=2;
	private $vertical='false';
	private $visible=3;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * setAfterEnd
	 *
	 * Sets the data member $after_end.
	 *
	 * @param		string			$js_function
	 * @access	public
	 */
	public function setAfterEnd($js_function)
	{
		# Set the data member.
		$this->after_end=$js_function;
	} #==== End -- setAfterEnd

	/**
	 * setAuto
	 *
	 * Sets the data member $auto.
	 *
	 * @param		int			$millisecond (string if "null")
	 * @access	public
	 */
	public function setAuto($millisecond)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $int is empty.
		if(!empty($millisecond))
		{
			# Check if the passed $int is an integer.
			if($validator->isInt($millisecond)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->auto=(int)$millisecond;
			}
			else
			{
				throw new Exception('The passed Slideshow auto value was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->auto='\'null\'';
		}
	} #==== End -- setAuto

	/**
	 * setBeforeStart
	 *
	 * Sets the data member $before_start.
	 *
	 * @param		string			$js_function
	 * @access	public
	 */
	public function setBeforeStart($js_function)
	{
		# Set the data member.
		$this->before_start=$js_function;
	} #==== End -- setBeforeStart

	/**
	 * setButtonNext
	 *
	 * Sets the data member $button_next.
	 *
	 * @param		string			$selector
	 * @access	public
	 */
	public function setButtonNext($selector)
	{
		# Set the data member.
		$this->button_next=$selector;
	} #==== End -- setButtonNext

	/**
	 * setButtonPrevious
	 *
	 * Sets the data member $button_previous.
	 *
	 * @param		string			$selector
	 * @access	public
	 */
	public function setButtonPrevious($selector)
	{
		# Set the data member.
		$this->button_previous=$selector;
	} #==== End -- setButtonPrevious

	/**
	 * setCircular
	 *
	 * Sets the data member $circular.
	 *
	 * @param		string			$boolean
	 * @access	public
	 */
	public function setCircular($boolean)
	{
		# Set the data member.
		$this->circular=$boolean;
	} #==== End -- setCircular

	/**
	 * setScroll
	 *
	 * Sets the data member $scroll.
	 *
	 * @param		int					$int
	 * @access	public
	 */
	public function setScroll($int)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $int is empty.
		if(!empty($int))
		{
			# Check if the passed $int is an integer.
			if($validator->isInt($int)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->scroll=(int)$int;
			}
			else
			{
				throw new Exception('The passed Slideshow scroll value was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->scroll=2;
		}
	} #==== End -- setScroll

	/**
	 * setSelector
	 *
	 * Sets the data member $selector.
	 *
	 * @param		string			$selector
	 * @access	public
	 */
	public function setSelector($selector)
	{
		# Set the data member.
		$this->selector=$selector;
	} #==== End -- setSelector

	/**
	 * setSpeed
	 *
	 * Sets the data member $speed.
	 *
	 * @param		int					$millisecond
	 * @access	public
	 */
	public function setSpeed($millisecond)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $millisecond is empty.
		if(!empty($millisecond))
		{
			# Check if the passed $millisecond is an integer.
			if($validator->isInt($millisecond)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->speed=(int)$millisecond;
			}
			else
			{
				throw new Exception('The passed Slideshow speed value was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->speed=800;
		}
	} #==== End -- setSpeed

	/**
	 * setStart
	 *
	 * Sets the data member $start.
	 *
	 * @param		int					$int
	 * @access	public
	 */
	public function setStart($int)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $int is empty.
		if(!empty($int))
		{
			# Check if the passed $int is an integer.
			if($validator->isInt($int)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->start=(int)$int;
			}
			else
			{
				throw new Exception('The passed Slideshow start value was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->start=0;
		}
	} #==== End -- setStart

	/**
	 * setVertical
	 *
	 * Sets the data member $vertical.
	 *
	 * @param		string			$boolean
	 * @access	public
	 */
	public function setVertical($boolean)
	{
		# Set the data member.
		$this->vertical=$boolean;
	} #==== End -- setVertical

	/**
	 * setVisible
	 *
	 * Sets the data member $visible.
	 *
	 * @param		int					$int
	 * @access	public
	 */
	public function setVisible($int)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $int is empty.
		if(!empty($int))
		{
			# Check if the passed $int is an integer.
			if($validator->isInt($int)===TRUE)
			{
				# Set the data member explicitly making it an integer.
				$this->visible=(int)$int;
			}
			else
			{
				throw new Exception('The passed Slideshow visible value was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to the default.
			$this->visible=3;
		}
	} #==== End -- setVisible

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAfterEnd
	 *
	 * Returns the data member $after_end.
	 *
	 * @access	public
	 */
	public function getAfterEnd()
	{
		return $this->after_end;
	} #==== End -- getAfterEnd

	/**
	 * getAuto
	 *
	 * Returns the data member $auto.
	 *
	 * @access	public
	 */
	public function getAuto()
	{
		return $this->auto;
	} #==== End -- getAuto

	/**
	 * getBeforeStart
	 *
	 * Returns the data member $before_start.
	 *
	 * @access	public
	 */
	public function getBeforeStart()
	{
		return $this->before_start;
	} #==== End -- getBeforeStart

	/**
	 * getButtonNext
	 *
	 * Returns the data member $button_next.
	 *
	 * @access	public
	 */
	public function getButtonNext()
	{
		return $this->button_next;
	} #==== End -- getButtonNext

	/**
	 * getButtonPrevious
	 *
	 * Returns the data member $button_previous.
	 *
	 * @access	public
	 */
	public function getButtonPrevious()
	{
		return $this->button_previous;
	} #==== End -- getButtonPrevious

	/**
	 * getCircular
	 *
	 * Returns the data member $circular.
	 *
	 * @access	public
	 */
	public function getCircular()
	{
		return $this->circular;
	} #==== End -- getCircular

	/**
	 * getScroll
	 *
	 * Returns the data member $scroll.
	 *
	 * @access	public
	 */
	public function getScroll()
	{
		return $this->scroll;
	} #==== End -- getScroll

	/**
	 * getSelector
	 *
	 * Returns the data member $selector.
	 *
	 * @access	public
	 */
	public function getSelector()
	{
		return $this->selector;
	} #==== End -- getSelector

	/**
	 * getSpeed
	 *
	 * Returns the data member $speed.
	 *
	 * @access	public
	 */
	public function getSpeed()
	{
		return $this->speed;
	} #==== End -- getSpeed

	/**
	 * getStart
	 *
	 * Returns the data member $start.
	 *
	 * @access	public
	 */
	public function getStart()
	{
		return $this->start;
	} #==== End -- getStart

	/**
	 * getVertical
	 *
	 * Returns the data member $vertical.
	 *
	 * @access	public
	 */
	public function getVertical()
	{
		return $this->vertical;
	} #==== End -- getVertical

	/**
	 * getVisible
	 *
	 * Returns the data member $visible.
	 *
	 * @access	public
	 */
	public function getVisible()
	{
		return $this->visible;
	} #==== End -- getVisible

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$slideshow)
		{
			self::$slideshow=new Slideshow();
		}
		return self::$slideshow;
	} #==== End -- getInstance

	/***
	 * getSlideshow
	 *
	 * Get content from slideshow table.
	 *
	 * @access	public
	 */
	public function makeSlideshow($position=NULL, $foci=NULL, $max_char=110)
	{
		# Bring the DB object into scope.
		global $db;
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		try
		{
			# Set the position id by default.
			$position_id=$position;
			# Check if the passed $position value is an id.
			if($validator->isInt($position)!==TRUE)
			{
				# Get the Position class.
				require_once Utility::locateFile(MODULES.'Content'.DS.'Position.php');
				# Instantiate a new Position object.
				$position_obj=new Position();
				# Get the position data from the `positions` table.
				$position_obj->getThisPosition($position, FALSE);
				# Set the position id.
				$position_id=$position_obj->getID();
			}
			# Get the Staff class.
			require_once Utility::locateFile(MODULES.'User'.DS.'Staff.php');
			# Instantiate a new Staff object.
			$staff=new Staff();
			# Create an empty variable to hold the AND SQL statement.
			$and_sql='AND `archive` IS NULL';
			# Create an empty variable to hold the display XHTML.
			$display=NULL;
			# Check if there are foci passed.
			if(!empty($foci))
			{
				# Begin the AND SQL statement.
				$and_sql.=' AND (';
				# Check if the passed focus is an array. If not, make it so.
				if(!is_array($foci))
				{
					$foci=(array)$foci;
				}
				# Loop through the foci.
				foreach($foci as $focus)
				{
					# Create the AND SQL statement.
					$sql_array[]='`position` REGEXP \''.$focus.'\'';
				}
				# Create the AND SQL statement.
				$and_sql.=implode(' OR ', $sql_array);
				# Create the AND SQL statement.
				$and_sql.=')';
			}
			# Retrieve the staff.
			$staff->getStaff($position, NULL, 'id', 'id', 'ASC', $and_sql);
			# Set the retrieved records to a variable.
			$records=$staff->getAllStaff();
			# Check if there were records retrieved.
			if(!empty($records))
			{
				$display='<div class="slideshow">';
				$display.='<ul class="slides">';
				# Loop through the records.
				foreach($records as $row)
				{
					# Set the staff id to a variable.
					$id=$row->id;
					# Get this person from the `staff` table.
					$staff->displayStaff($id);
					# Set the Staff data members to variables
					$affiliation=$staff->getAffiliation();
					$img=$staff->getImage();
					$img_title=$staff->getImageTitle();
					$name=$staff->getStaffName();
					# Decode json string in `position` field.
					$position_decoded=json_decode($staff->getPosition(), TRUE);
					# Create an empty variable.
					$position_desc=NULL;
					# Check if $position_decoded is an array.
					if(is_array($position_decoded))
					{
						# Loop through the $position_decoded array.
						foreach($position_decoded as $position_value)
						{
							# If the position equals the ID in the `position` table.
							if($position_value['position']==$position_id)
							{
								# Set the position description.
								$position_desc=$position_value['description'];
							}
							# ID matched so stop the loop.
							break;
						}
					}
					$region=$staff->getRegion();
					$text=$staff->getText();
					$title=$staff->getTitle();
					$user_id=$staff->getUser();
					$display.='<li class="slide">';
					# Get the Image class.
					require_once Utility::locateFile(MODULES.'Media'.DS.'Image.php');
					# Instantiate a new Image object.
					$image=new Image();
					$display.=$image->displayImage(TRUE, $img, $img_title);
					$display.='<h4 class="h-4"><a href="'.APPLICATION_URL.'profile/?person='.$id.'" title="'.$name.'">'.$name.'</a></h4>';
					$display.='<p class="user-focus">'.$position_desc.'</p>';
					$display.='</li>';
				}
				$display.='</ul>';
				$display.='<button class="'.$this->getButtonPrevious().'">Previous</button>';
				$display.='<button class="'.$this->getButtonNext().'">Next</button>';
				$display.='</div>';
			}
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** public methods ***/

} # End Slideshow class.