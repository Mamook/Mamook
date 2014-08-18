<?php /* Order By Class - orderby.class.php */

class OrderBy
{
	// Data members
	private $value=''; // The string that will be clickable for the link.
	private $key=''; // The sort key (what we will be sorting by.)
	private $column=''; // The sort key (what we will be sorting by.)
	private $direction=''; // The direction to sort (Ascending or Descending)

	// Constructor
	public function __construct()
	{
		return;
	}



	/*** mutator methods ***/

	/**
	 * setValue
	 *
	 * Sets the data member $value. Returns FALSE on failure.
	 *
	 * @param	$value
	 * @access	private
	 */
	private function setValue($value)
	{
		// Clean it up...
		$value=trim($value);
		if (!empty($value))
		{
			$this->value=$value;
		}
		else { return FALSE; }
	} #==== End -- setValue

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getValue
	 *
	 * Returns the data member $value. Returns FALSE on failure.
	 *
	 * @access	public
	 */
	public function getValue()
	{
		if (isset($this->value) && !empty($this->value))
		{
			return $this->value;
		}
		else { return FALSE; }
	} #==== End -- getValue

	/*** End accessor methods ***/


	public function makeHeaderLink($value, $key, $column, $direction)
	{
		$header_link = "<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?c=";
		//set column query string value
		switch($key)
		{
		case "Username":
			$header_link .= "1";
			break;
		case "Level_access":
			$header_link .= "2";
			break;
		default:
			$header_link .= "0";
		}

		$header_link .= "&d=";

		//reverse sort if the current column is clicked
		if($key == $column)
		{
			switch($direction)
			{
			case "ASC":
				$header_link .= "1";
				break;
			default:
				$header_link .= "0";
			}
		}
		else
		{
			//pass on current sort direction
			switch($direction)
			{
			case "ASC":
				$header_link .= "0";
				break;
			default:
				$header_link .= "1";
			}
		}

		//complete link
		$header_link .= "\">$value</a>";

		return $header_link;
	}
}

?>
