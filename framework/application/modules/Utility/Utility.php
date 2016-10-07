<?php /* framework/application/modules/Utility/Utility.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Utility
 *
 * The Utility class is used to for miscellaneous utility
 * methods. Most methods here are static.
 */
class Utility
{
	/*** data members ***/

	private $key='';

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setkey
	 *
	 * Sets the data member $key.
	 *
	 * @param	$key
	 * @access	public
	 */
	public function setkey($key)
	{
		# Check if the passed value is empty.
		if(!empty($key) OR ($key===0))
		{
			# Set the data member.
			$this->key=$key;
		}
		else
		{
			# Explicitly set the data member to blank ('').
			$this->key='';
		}
	} #==== End -- setkey

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getKey
	 *
	 * Returns the data member $key.
	 *
	 * @access	public
	 */
	public function getKey()
	{
		return $this->key;
	} #==== End -- getKey

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * flattenArray
	 *
	 * Turns a multidimensional array into a single.
	 *
	 * @param    array $array
	 * @access    public
	 * @return array|bool
	 */
	public static function flattenArray($array)
	{
		# Return FALSE if not an array.
		if(!is_array($array))
		{
			return FALSE;
  		}

  		# Create an empty array.
		$result=array();
		# Loop through the array.
		foreach($array as $key=>$value)
		{
			# If the element is an array, run it through the method again to make it a single.
			if(is_array($value))
			{
				$result=array_merge($result, self::flattenArray($value));
			}
			else
			{
				$result[$key]=$value;
			}
		}
		return $result;
	} #==== End -- flattenArray

	/**
	 * getElapsedTime
	 *
	 * Returns a string stating the elapsed time in years, months, days, weeks, hours, minutes, and seconds.
	 *
	 * @param int $start_time The unix timestamp of the start time.
	 * @param int $end_time   The unix timestamp of the end time.
	 * @return string ie. 2 years, 1 week, 0 days, 4 hours, 1 minute, and 58 seconds.
	 * @throws Exception
	 */
	public static function getElapsedTime($start_time, $end_time)
	{
		try
		{
			# Set the elapsed time (in seconds) to a variable.
			$elapsed_seconds=round($end_time-$start_time);
			$years=$elapsed_seconds/31556926 % 12;
			$weeks=$elapsed_seconds/604800 % 52;
			$days=$elapsed_seconds/86400 % 7;
			$hours=$elapsed_seconds/3600 % 24;
			$minutes=$elapsed_seconds/60 % 60;
			$seconds=$elapsed_seconds % 60;

			$years=((!empty($years)) ? $years.' year'.(($years>1) ? 's' : '') : '');
			$weeks=((!empty($years)) ? ',' : '').((!empty($years) OR !empty($weeks)) ? $weeks.' week'.(($weeks>1) ? 's' : '') : '');
			$days=((!empty($weeks)) ? ',' : '').((!empty($weeks) OR !empty($days)) ? $days.' day'.(($days>1) ? 's' : '') : '');
			$hours=((!empty($days)) ? ',' : '').((!empty($days) OR !empty($hours)) ? $hours.' hour'.(($hours>1) ? 's' : '') : '');
			$minutes=((!empty($hours)) ? ',' : '').((!empty($hours) OR !empty($minutes)) ? $minutes.' minute'.(($minutes>1) ? 's' : '') : '');
			$seconds=((!empty($hours)) ? ',' : '').((!empty($minutes)) ? ' and ' : '').$seconds.' second'.(($seconds!==1) ? 's' : '');

			return $years.$weeks.$days.$hours.$minutes.$seconds;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getElapsedTime

	/**
	 * getMicrotime
	 *
	 * Returns the microtime.
	 *
	 * @access	public
	 */
	public static function getMicrotime()
	{
		list($us,$s)=explode(' ',microtime());
		return (float)$us+(float)$s;
	} #==== End -- getMicrotime

	/**
	 * htmlToText
	 *
	 * Converts passed HTML to text
	 *
	 * @param	$document 				An HTML document or a string of html.
	 * @param	$utf8 					True will return UTF-8 encoded text, FALSE returns ASCII text
	 * @param	$remove_white_space		True will remove whitespace, tabs, carriage returns, and line breaks
	 * @access	public
	 * @return	string
	 */
	public static function htmlToText($document, $utf8=TRUE, $remove_white_space=FALSE)
	{
		$search=array();
		# Strip out javascript
		$js_search="'<(script[^>]*?)>.*?</script>'si";
		$search[]=$js_search;
		# Strip out HTML tags
		$html_search="'<([\/\!]*?[^<>]*?)>'si";
		$search[]=$html_search;
		# Strip out white space
		if($remove_white_space===TRUE)
		{
			$ws_search1="'([\r\n])[\s]+'";
			$search[]=$ws_search1;
			$ws_search2="'@<![\s\S]*?ñ[ \t\n\r]*>@'";
			$search[]=$ws_search2;
		}
		if($utf8===TRUE)
		{
			# Replace unacceptable characters with acceptable characters or HTML entities
			$dblquote_search="'(\“|\”)'i";
			$search[]=$dblquote_search;
			$snglquote_search="'(\‘|\’)'i";
			$search[]=$snglquote_search;
		}
		else
		{
			# Replace HTML entities
			$dblquote_search="'&(ldquo|#8220|rdquo|#8221|quot|#34|#034|#x22);'i";
			$search[]=$dblquote_search;
			$snglquote_search="'&(lsquo|#8216|rsquo|#8217);'i";
			$search[]=$snglquote_search;
			$dash_search="'&(ndash|#x2013|#8211|mdash|#x2014|#8212|#150);'i";
			$search[]=$dash_search;
			$ampersand_search="'&(amp|#38|#038|#x26);'i";
			$search[]=$ampersand_search;
			$lessthan_search="'&(lt|#60|#060|#x3c);'i";
			$search[]=$lessthan_search;
			$greaterthan_search="'&(gt|#62|#062|#x3e);'i";
			$search[]=$greaterthan_search;
			$space_search="'&(nbsp|#160|#xa0);'i";
			$search[]=$space_search;
			$inverted_exclamation_mark_search="'&(iexcl|#161);'i";
			$search[]=$inverted_exclamation_mark_search;
			$inverted_question_mark_search="'&(iquest|#191);'i";
			$search[]=$inverted_question_mark_search;
			$cent_search="'&(cent|#162);'i";
			$search[]=$cent_search;
			$pound_search="'&(pound|#163);'i";
			$search[]=$pound_search;
			$copyright_search="'&(copy|#169);'i";
			$search[]=$copyright_search;
			$registered_search="'&(reg|#174);'i";
			$search[]=$registered_search;
			$degrees_search="'&(deg|#176);'i";
			$search[]=$degrees_search;
			$apostrophe_search="'&(apos|#39|#039|#x27);'";
			$search[]=$apostrophe_search;
			$euro_search="'&(euro|#8364);'i";
			$search[]=$euro_search;
			$umlaut_a_search="'&a(uml|UML);'";
			$search[]=$umlaut_a_search;
			$umlaut_o_search="'&o(uml|UML);'";
			$search[]=$umlaut_o_search;
			$umlaut_u_search="'&u(uml|UML);'";
			$search[]=$umlaut_u_search;
			$umlaut_y_search="'&y(uml|UML);'";
			$search[]=$umlaut_y_search;
			$umlaut_A_search="'&A(uml|UML);'";
			$search[]=$umlaut_A_search;
			$umlaut_O_search="'&O(uml|UML);'";
			$search[]=$umlaut_O_search;
			$umlaut_U_search="'&U(uml|UML);'";
			$search[]=$umlaut_U_search;
			$umlaut_Y_search="'&Y(uml|UML);'";
			$search[]=$umlaut_Y_search;
			$latin_small_letter_sharp_s_search="'&(szlig|#xdf|#223);'i";
			$search[]=$latin_small_letter_sharp_s_search;
		}

		$replace=array();
		# Strip out javascript
		$js_replace="";
		$replace[]=$js_replace;
		# Strip out HTML tags
		$html_replace="";
		$replace[]=$html_replace;
		# Strip out white space
		if($remove_white_space===TRUE)
		{
			$ws_replace1=" ";
			$replace[]=$ws_replace1;
			$ws_replace2=" ";
			$replace[]=$ws_replace2;
		}
		if($utf8===TRUE)
		{
			# Replace HTML entities
			$dblquote_replace="\"";
			$replace[]=$dblquote_replace;
			$snglquote_replace="'";
			$replace[]=$snglquote_replace;
		}
		else
		{
			# Replace HTML entities
			$dblquote_replace=chr(34);
			$replace[]=$dblquote_replace;
			$snglquote_replace="'";
			$replace[]=$snglquote_replace;
			$dash_replace=chr(45);
			$replace[]=$dash_replace;
			$ampersand_replace=chr(38);
			$replace[]=$ampersand_replace;
			$lessthan_replace=chr(60);
			$replace[]=$lessthan_replace;
			$greaterthan_replace=chr(62);
			$replace[]=$greaterthan_replace;
			$space_replace=' ';
			$replace[]=$space_replace;
			$inverted_exclamation_mark_replace='¡';
			$replace[]=$inverted_exclamation_mark_replace;
			$inverted_question_mark_replace='¿';
			$replace[]=$inverted_question_mark_replace;
			$cent_replace='¢';
			$replace[]=$cent_replace;
			$pound_replace='£';
			$replace[]=$pound_replace;
			$copyright_replace='©';
			$replace[]=$copyright_replace;
			$registered_replace='®';
			$replace[]=$registered_replace;
			$degrees_replace='°';
			$replace[]=$degrees_replace;
			$apostrophe_replace=chr(39);
			$replace[]=$apostrophe_replace;
			$euro_replace='€';
			$replace[]=$euro_replace;
			$umlaut_a_replace='ä';
			$replace[]=$umlaut_a_replace;
			$umlaut_o_replace="ö";
			$replace[]=$umlaut_o_replace;
			$umlaut_u_replace="ü";
			$replace[]=$umlaut_u_replace;
			$umlaut_y_replace="ÿ";
			$replace[]=$umlaut_y_replace;
			$umlaut_A_replace="Ä";
			$replace[]=$umlaut_A_replace;
			$umlaut_O_replace="Ö";
			$replace[]=$umlaut_O_replace;
			$umlaut_U_replace="Ü";
			$replace[]=$umlaut_U_replace;
			$umlaut_Y_replace="Ÿ";
			$replace[]=$umlaut_Y_replace;
			$latin_small_letter_sharp_s_replace="ß";
			$replace[]=$latin_small_letter_sharp_s_replace;
		}

		$text=preg_replace($search, $replace, $document);

		if($utf8===TRUE)
		{
			$text=htmlentities($text, ENT_NOQUOTES, 'UTF-8', FALSE);
		}

		return trim($text);
	} #==== End -- htmlToText

	/**
	 * locateFile
	 *
	 * Checks if the file is located in the client directory. If not, it changes the path to point to the framework folder.
	 *
	 * @param    string $file The path to the file.
	 * @return mixed|string
	 */
	public static function locateFile($file)
	{
		# Check if the file is available in the client directory. If not, get it from the Framework folder.
		if(file_exists($file)===FALSE)
		{
			$new_file_path=str_replace(BASE_PATH, FW_FOLDER, $file);
			# Check if the is available in the framework directory.
			if(file_exists($new_file_path)===FALSE)
			{
				# Check if the file is a view.
				if(strpos($new_file_path, FW_VIEWS)!==FALSE)
				{
					# Get the view template.
					$new_file_path=Utility::locateFile(TEMPLATES.'view.php');
				}
				elseif(strpos($file, VENDOR_FOLDER)!==FALSE)
				{
					$new_file_path=str_replace(VENDOR_FOLDER, ROOT_VENDOR_FOLDER, $file);
				}
			}
			$file=$new_file_path;
		}
		# Return the file path.
		return $file;
	} #==== End -- locateFile

	/**
	 * removeIndex
	 *
	 * Removes "index.php" from the passed URL.
	 *
	 * @param string $url The URL to check.
	 * @return mixed|string
	 */
	public static function removeIndex($url)
	{
		# Check if the link is to an index page.
		if(strpos($url, 'index.php')===FALSE)
		{
			$url=$url;
		}
		else
		{
			$url=str_replace('index.php', '', $url);
		}
		return $url;
	} #==== End -- removeIndex

	/**
	 * returnSessionData
	 *
	 * Retrieves the session data from the session file, parses it, and returns it as an array.
	 *
	 * @param string $session_id   The id of the session.
	 * @param string $session_path The path to the session files (does NOT end with a slash.)
	 * @return array The Session array.
	 * @throws Exception
	 */
	public static function returnSessionData($session_id, $session_path)
	{
		try
		{
			# Get the session data.
			$session_data=file_get_contents($session_path.DIRECTORY_SEPARATOR.'sess_'.$session_id);
			# Create a new array to hold the session.
			$session=array();
			# Set the initial offset to zero.
			$offset=0;
			# Loop through the session data.
			while($offset<strlen($session_data))
			{
				if(!strstr(substr($session_data, $offset), '|'))
				{
					throw new Exception('invalid data, remaining: '.substr($session_data, $offset));
				}
				$pos=strpos($session_data, '|', $offset);
				$num=$pos-$offset;
				$varname=substr($session_data, $offset, $num);
				$offset+=$num+1;
				$data=unserialize(substr($session_data, $offset));
				$session[$varname]=$data;
				$offset+=strlen(serialize($data));
			}
			return $session;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- returnSessionData

	/**
	 * saveSessionData
	 *
	 * Retrieves the session data from the session file, parses it, and returns it as an array.
	 *
	 * @access	public
	 */
	public static function saveSessionData($session_data, $session_id, $session_path)
	{
		try
		{
			$session=array();

			foreach($session_data as $data_key=>$data_value)
			{

			}

			/*
			# Create a new array to hold the session.
			$session=array();
			# Set the initial offset to zero.
			$offset=0;
			# Loop through the session data.
			while($offset<strlen($session_data))
			{
				if(!strstr(substr($session_data, $offset), '|'))
				{
					throw new Exception('invalid data, remaining: '.substr($session_data, $offset));
				}
				$pos=strpos($session_data, '|', $offset);
				$num=$pos-$offset;
				$varname=substr($session_data, $offset, $num);
				$offset+=$num+1;
				$data=unserialize(substr($session_data, $offset));
				$session[$varname]=$data;
				$offset+=strlen(serialize($data));
			}
			*/

			# Get the session data.
			if(file_put_contents($session_path.DIRECTORY_SEPARATOR.'sess_'.$session_id, $session)===FALSE)
			{
				return TRUE;
			}
			return FALSE;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- saveSessionData

	/**
	 * sortByDate
	 *
	 * Returns the array of social data sorted by date.
	 *
	 * @param	array $data_array		An array of values to sort
	 * @param	string $key				Key to sort
	 * @param	string $first_key		First key in the $data_array
	 * @param	string $look_in			The key to look for $key in (Not working yet)
	 * @access	public
	 * @return	array
	 */
	public function sortByDate($data_array, $key='time', $first_key=NULL, $look_in=NULL)
	{
		# Create a callback function for usort to compare the dates.
		$this->setKey($key);
		# Sort the array by date
		if($first_key!==NULL)
		{
			usort($data_array[$first_key], array($this, 'dateCompare'));
			# Reverse the array the that the most recent date is first.
			$new_data_array[$first_key]=array_reverse($data_array[$first_key]);
			$data_array=$new_data_array;
		}
		else
		{
			usort($data_array, array($this, 'dateCompare'));
			# Reverse the array the that the most recent date is first.
			$data_array=array_reverse($data_array);
		}
		return $data_array;
	} #==== End -- sortByDate

	/**
	 * truncate
	 *
	 * Truncates a string to the passed length. Respects html.
	 *
	 * @param    string $text    The string to truncated.
	 * @param    int $length     The maximum length of the cut string.
	 * @param    string $suffix  The string to indicate the string has been truncated.
	 * @param    boolean $isHTML TRUE is the string contains HTML.
	 * @param    boolean $exact  TRUE words may be cut in the middle.
	 * @param    $max_br
	 * @return string
	 */
	public static function truncate($text, $length, $suffix='&hellip;', $isHTML=TRUE, $exact=FALSE, $max_br=NULL)
	{
		if($isHTML===TRUE)
		{
			# If the plain text is shorter than the maximum length, return the whole text.
			if(strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
			{
				return $text;
			}
			# Splits all html-tags to scanable lines.
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
			$total_length=strlen($suffix);
			$open_tags=array();
			$truncate='';
			$br_count=NULL;
			foreach($lines as $line_matchings)
			{
				if($br_count<=$max_br)
				{
					# If there is any html-tag in this line, handle it and add it (uncounted) to the output.
					if(!empty($line_matchings[1]))
					{
						# If it's an "empty element" with or without xhtml-conform closing slash.
						if(preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1]))
						{
							if($max_br!==NULL)
							{
								switch($line_matchings[1])
								{
									case '<br />':
										$br_count++;
										break;
									case '<br>':
										$br_count++;
										break;
								}
							}
							# Do nothing.
							# If tag is a closing tag.
						}
						elseif(preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings))
						{
							# Delete tag from $open_tags list.
							$pos=array_search($tag_matchings[1], $open_tags);
							if($pos !== false)
							{
								unset($open_tags[$pos]);
							}
						# If tag is an opening tag.
						} else if(preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
							# add tag to the beginning of $open_tags list
							array_unshift($open_tags, strtolower($tag_matchings[1]));
						}
						# Add html-tag to $truncate'd text.
						$truncate .= $line_matchings[1];
					}
				}
				# Calculate the length of the plain text part of the line; handle entities as one character.
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if($total_length+$content_length> $length)
				{
					# The number of characters which are left.
					$left=$length - $total_length;
					$entities_length=0;
					# Search for html entities.
					if(preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE))
					{
						# Calculate the real length of all entities in the legal range.
						foreach($entities[0] as $entity)
						{
							if($entity[1]+1-$entities_length <= $left)
							{
								$left--;
								$entities_length += strlen($entity[0]);
							}
							else
							{
								# No more characters left.
								break;
							}
						}
					}
					$truncate.=substr($line_matchings[2], 0, $left+$entities_length);
					# Maximum lenght is reached, so get off the loop.
					break;
				}
				else
				{
					$truncate.=$line_matchings[2];
					$total_length += $content_length;
				}
				# If the maximum length is reached, get off the loop.
				if($total_length >= $length)
				{
					break;
				}
			}
		}
		else
		{
			if(strlen($text) <= $length)
			{
				return $text;
			}
			else
			{
				$truncate=substr($text, 0, $length - strlen($suffix));
			}
		}
		# If the words shouldn't be cut in the middle...
		if(!$exact)
		{
			# ...search the last occurance of a space...
			$spacepos=strrpos($truncate, ' ');
			if(isset($spacepos))
			{
				# ...and cut the text in this position
				$truncate=substr($truncate, 0, $spacepos);
			}
		}
		if($isHTML===TRUE)
		{
			# Count the open tags.
			$num_tags=count($open_tags);
			$incrementor=1;
			# Close all unclosed html-tags
			foreach($open_tags as $tag)
			{
				if($incrementor===$num_tags && $tag!='a')
				{
					# Add the defined ending to the text.
					$truncate.=$suffix;
				}
				$truncate.='</'.$tag.'>';
				if($incrementor===$num_tags && $tag=='a')
				{
					# Add the defined ending to the text.
					$truncate.=$suffix;
				}
				$incrementor++;
			}
			if($num_tags==0)
			{
				$truncate.=$suffix;
			}
		}
		else
		{
			# Add the defined ending to the text.
			$truncate.=$suffix;
		}
		return $truncate;
	} #==== End -- truncate

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * dateCompare
	 *
	 * Returns the array of social data sorted by date.
	 *
	 * @access	private
	 */
	private function dateCompare($a, $b)
	{
		$t1=strtotime($a[$this->getKey()]);
		$t2=strtotime($b[$this->getKey()]);
		return $t1-$t2;
	} #==== End -- dateCompare

	/*** End private methods ***/

} # End Utility class.