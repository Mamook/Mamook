<?php /* framework/application/modules/Search/Search.php */

/**
 * Search
 *
 * The Search Class is used to search through a field in a MYSQL database for matching (or similar) text.
 * Thanks goes to Cal Henderson at iamcal.com for the great php search script example (http://www.iamcal.com/publish/articles/php/search/).
 *
 */
class Search
{
	/*** data members ***/

	private static $search_obj;
	private $all_results=NULL;
	private $fields=NULL;
	private $search_branch=NULL;
	private $search_terms=NULL;
	private $search_type;
	private $tables=NULL;
	/*** End data members ***/

	/*** mutator methods ***/

	/**
	 * Gets the singleton instance of this class.
	 */
	public static function getInstance()
	{
		if(!self::$search_obj)
		{
			self::$search_obj=new Search();
		}

		return self::$search_obj;
	}

	/**
	 * Replaces any whitespace or comma in the string ($term) with a holder token.
	 * Returns the transformed string.
	 *
	 * @param string $term The string we're escaping
	 * @return mixed
	 */
	protected static function change2Token($term)
	{
		# Replace any whitespace ( ) with a holder token (ie. {WHITESPACE-1}).
		$term=preg_replace_callback(
			"/(\s)/",
			function($matches)
			{
				foreach($matches as $match)
				{
					return '{WHITESPACE-'.ord($match).'}';
				}
			},
			$term
		);
		# Replace any comma (,) with a holder token ({COMMA}).
		$term=preg_replace("/,/", "{COMMA}", $term);

		return $term;
	}

	/**
	 * Sets the data member $all_results.
	 *
	 * @param array $all_results The results or the search.
	 */
	public function setAllResults($all_results)
	{
		# Set the variable.
		$this->all_results=$all_results;
	}

	/**
	 * Sets the data member $fields.
	 *
	 * @param array $fields An array of the fields to search.
	 */
	public function setFields($fields)
	{
		# Set the variable.
		$this->fields=$fields;
	}

	/**
	 * Sets the data member $search_branch.
	 *
	 * @param string $search_branch
	 */
	public function setSearchBranch($search_branch)
	{
		# Set the variable.
		$this->search_branch=$search_branch;
	}

	/**
	 * Sets the data member $search_terms.
	 *
	 * @param string $search_terms
	 */
	public function setSearchTerms($search_terms)
	{
		# Set the variable.
		$this->search_terms=$search_terms;
	}

	/*** End mutator methods ***/

	/*** accessor methods ***/

	/**
	 * Sets the data member $search_type.
	 *
	 * @param string $search_type An array of the type of search.
	 */
	public function setSearchType($search_type)
	{
		# Set the variable.
		$this->search_type=$search_type;
	}

	/**
	 * Sets the data member $tables.
	 *
	 * @param string $tables An array of the tables to search.
	 */
	public function setTables($tables)
	{
		# Set the variable.
		$this->tables=$tables;
	}

	/**
	 * Returns the data member $all_results.
	 */
	public function getAllResults()
	{
		return $this->all_results;
	}

	/**
	 * Returns the data member $search_branch.
	 */
	public function getSearchBranch()
	{
		return $this->search_branch;
	}

	/**
	 * Returns the data member $search_terms.
	 */
	public function getSearchTerms()
	{
		return $this->search_terms;
	}

	/**
	 * Returns the data member $search_type.
	 */
	public function getSearchType()
	{
		return $this->search_type;
	}

	/*** End accessor methods ***/

	/*** public methods ***/

	/**
	 * Returns the data member $tables.
	 */
	public function getTables()
	{
		return $this->tables;
	}

	/**
	 * Processes search, returning the results of the search.
	 *
	 * @param array $search_type
	 */
	public function processSearch($search_type)
	{
		# Loop through search types.
		foreach($search_type as $type)
		{
			switch($type)
			{
				case "users":
					# Set the fields to the data member.
					$this->setFields(array('ID', 'display', 'username', 'title', 'fname', 'lname', 'email'));
					# Set the tables to the data member.
					$this->setTables('users');
					# Perform search.
					$this->searchUsers();
					break;
				case "subcontent":
					# Set the fields to the data member.
					$this->setFields(array('id', 'title', 'link', 'file', 'availability', 'visibility', 'date', 'premium', 'branch', 'institution', 'publisher', 'text_language', 'text', 'trans_language', 'text_trans', 'hide', 'image', 'contributor'));
					# Set the tables to the data member.
					$this->setTables('subcontent');
					# Perform search.
					$this->searchSubContent();
					break;
				/*
				case "videos":
					# Set the fields to the data member.
					$this->setFields(array('title'));
					# Set the tables to the data member.
					$this->setTables('videos');
					# Perform search.
					$this->searchVideos();
					break;
				*/
				case "all":
					# NOTE! Not finished yet.
					# Search entire site.
					break;
			}
		}
	}

	/**
	 * displayResults
	 *
	 * Displays the results of the search.
	 *
	 * @param string $filter Fields and or terms we would like exluded.
	 */
	/*
	public function displayResults($fields, $display_field)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		$tables=$this->getTables();
		$id=$this->getID_Names();
		$all_results=$this->getAllResults();
		$display_results='Your search returned ';
		$display_list='<ul>';
		$result_count=0;
		if($all_results!==NULL)
		{
			foreach($tables as $table)
			{
				$result_count=$result_count+count($all_results[$table]);
				foreach($all_results[$table] as $result_id)
				{
					$num_select_fields=count($fields[$table]);
					$select_fields=implode('`, `', $fields[$table]);
					$results[$table]=$db->get_row('SELECT `'.$select_fields.'` FROM '.DBPREFIX.'`'.$table.'` WHERE `'.$id[$table].'` = '.$db->quote($db->escape($result_id->$id[$table])));
					$display_list.='<li>';
					for($i=0; $i<$num_select_fields; $i++)
					{
						$display_search_results[$result_id->$id[$table]]=array($fields[$table][$i]=>$results[$table]->$fields[$table][$i]);
						$display_list.=$results[$table]->$fields[$table][$i];
					}
					$display_list.='</li>';
				}
			}
		}
		$display_search_results['result_count']=$result_count;
		$display_results.=$result_count.' results:';
		$display_results.=$display_list;
		$display_results.='</ul>';
		return $display_search_results;
	}
	*/

	/**
	 * Returns the results of the search
	 *
	 * @param mixed $search_terms The term we're searching for.
	 * @param string $table       The table we're searching in.
	 * @param string $fields      The fields we're searching in.
	 * @param string $branch      Optional.
	 * @param array $filter       Optional. Fields and or terms we would like exluded.
	 * @throws Exception
	 */
	public function performSearch($search_terms, $table, $fields, $branch=NULL, $filter=NULL)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Create comma separated field string.
			$select_fields='`'.rtrim(implode('`, `', $fields), ', ').'`';
			# Create where string.
			$where=$this->prepareWhere($search_terms, $fields, $branch, $filter);

			# $sql="SELECT `id` FROM `users` WHERE `Party` = 'yes' AND `Username` RLIKE '%Joey%' OR `fname` RLIKE '%Joey%';
			$sql='SELECT '.$select_fields.' FROM '.DBPREFIX.'`'.$table.'` WHERE '.$where;
			$search_results=$db->get_results($sql);
			# Set results to the data member.
			$this->setAllResults($search_results);
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * Searches the users table.
	 */
	public function searchSubContent()
	{
		# Set tables to search to a variable.
		$tables=$this->getTables();
		# Set fields to search to a variable.
		$fields=$this->getFields();
		# Set branch to search to a variable.
		$branch=$this->getSearchBranch();
		# Set search terms to a variable.
		$search_terms=$this->getSearchTerms();
		# Perform search.
		$this->performSearch($search_terms, $tables, $fields, $branch);
	}

	/**
	 * Searches the users table.
	 */
	public function searchUsers()
	{
		# Set tables to search to a variable.
		$tables=$this->getTables();
		# Set fields to search to a variable.
		$fields=$this->getFields();
		# Set search terms to a variable.
		$search_terms=$this->getSearchTerms();
		# Perform search.
		$this->performSearch($search_terms, $tables, $fields);
	}

	/*** End public methods ***/

	/*** protected methods ***/

	/**
	 * getFields
	 *
	 * Returns the data member $fields.
	 *
	 * @access    protected
	 */
	protected function getFields()
	{
		return $this->fields;
	}

	/**
	 * Splits the string ($terms) and puts each searchable term into an array.
	 * Returns an array of search terms based on the string ($terms).
	 *
	 * @param array $terms The string splitting
	 * @return array
	 */
	protected function splitTerms($terms)
	{
		# TODO: NEEDS MORE WORK (add more characters ie tilde n, accented e, a, o, etc)

		# Explicitly make $terms an array.
		$terms=(array)$terms;

		# Create new array for our output.
		$out=array();
		# Create a new array for our alternate terms.
		$alt_terms=array();
		# Create a new array for our interim output.
		$interim_out=array();

		# Define excluded words.
		$exclude=' the if it to a I but no so of are and';

		# Create a variable to hold the reg ex pattern that finds all pair of double quotes (").
		$pattern='/\"(.*?)\"/';
		# Create a variable to hold the method call that replaces any whitespaces or commas with a holder token.
		//$replacement="Search::change2Token('\$1')";
		# Find all pair of double quotes (") and pass their contents to the change2Token() method for processing.
		$terms=preg_replace_callback(
			$pattern,
			function($matches)
			{
				foreach($matches as $match)
				{
					return Search::change2Token($match);
				}
			},
			$terms
		);
		# Take out parentheses
		$terms=preg_replace('/\)|\(/', '', $terms);

		# Loop through the terms
		foreach($terms as $term)
		{
			if(!empty($term))
			{
				# Split searchable terms on whitespace and commas and put into an array.
				$term=preg_split("/\s+|,/", $term);
				foreach($term as $split)
				{
					# If the term is not in the excluded list add it to the $interim_out array.
					if(!empty($split) && (strpos($exclude, $split)===FALSE))
					{
						$interim_out[]=$split;
					}
				}
			}
		}
		# Rename $interim_out as $terms.
		$terms=$interim_out;

		# Loop through the array.
		foreach($terms as $term)
		{
			# For each searchable term, replace the holding tokens with their original contents (whitespace or comma).
			$term=preg_replace_callback(
				"/\{WHITESPACE-([\d]+)\}/",
				function($matches)
				{
					foreach($matches as $match)
					{
						return chr($match);
					}
				},
				$term
			);
			$term=preg_replace("/\{COMMA\}/", ",", $term);

			# If the term is not in the excluded list add it to the $out array.
			if(!empty($term) && (strpos($exclude, $term)===FALSE))
			{
				$out[]=$term;
			}
		}
		# Loop through the array again.
		foreach($out as $term)
		{
			# First, replace HTML entities
			$alt_term=Utility::htmlToText($term, FALSE);

			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Second, replace with HTML entities
			$dblquote_search=chr(34);
			$search[]='/('.$dblquote_search.')/';
			$snglquote_search="'";
			$search[]='/('.$snglquote_search.')/';
			$dash_search=chr(45);
			$search[]='/('.$dash_search.')/';
			$ampersand_search=chr(38);
			$search[]='/('.$ampersand_search.')/';
			$lessthan_search=chr(60);
			$search[]='/('.$lessthan_search.')/';
			$greaterthan_search=chr(62);
			$search[]='/('.$greaterthan_search.')/';
			$space_search=' ';
			$search[]='/('.$space_search.')/';
			$inverted_exclamation_mark_search='¡';
			$search[]='/('.$inverted_exclamation_mark_search.')/';
			$inverted_question_mark_search='¿';
			$search[]='/('.$inverted_question_mark_search.')/';
			$cent_search='¢';
			$search[]='/('.$cent_search.')/';
			$pound_search='£';
			$search[]='/('.$pound_search.')/';
			$copyright_search='©';
			$search[]='/('.$copyright_search.')/';
			$registered_search='®';
			$search[]='/('.$registered_search.')/';
			$degrees_search='°';
			$search[]='/('.$degrees_search.')/';
			$apostrophe_search=chr(39);
			$search[]='/('.$apostrophe_search.')/';
			$euro_search='€';
			$search[]='/('.$euro_search.')/';
			$umlaut_a_search='ä|a(^(&a(uml|UML)))';
			$search[]='/('.$umlaut_a_search.')/';
			$umlaut_o_search='ö|o(^(&o(uml|UML)))';
			$search[]='/('.$umlaut_o_search.')/';
			$umlaut_u_search='ü|u(^(&u(uml|UML)))';
			$search[]='/('.$umlaut_u_search.')/';
			$umlaut_y_search='ÿ|y(^(&y(uml|UML)))';
			$search[]='/('.$umlaut_y_search.')/';
			$umlaut_A_search='Ä|A(^(&A(uml|UML)))';
			$search[]='/('.$umlaut_A_search.')/';
			$umlaut_O_search='Ö|O(^(&O(uml|UML)))';
			$search[]='/('.$umlaut_O_search.')/';
			$umlaut_U_search='Ü|U(^(&U(uml|UML)))';
			$search[]='/('.$umlaut_U_search.')/';
			$umlaut_Y_search='Ÿ|Y(^(&Y(uml|UML)))';
			$search[]='/('.$umlaut_Y_search.')/';
			$latin_small_letter_sharp_s_search='ß';
			$search[]='/('.$latin_small_letter_sharp_s_search.')/';

			$dblquote_replace='&ldquo;';
			$replace[]=$dblquote_replace;
			$snglquote_replace='&lsquo;';
			$replace[]=$snglquote_replace;
			$dash_replace='&ndash;';
			$replace[]=$dash_replace;
			$ampersand_replace='&amp;';
			$replace[]=$ampersand_replace;
			$lessthan_replace='&lt;';
			$replace[]=$lessthan_replace;
			$greaterthan_replace='&gt;';
			$replace[]=$greaterthan_replace;
			$space_replace='&nbsp;';
			$replace[]=$space_replace;
			$inverted_exclamation_mark_replace='&iexcl;';
			$replace[]=$inverted_exclamation_mark_replace;
			$inverted_question_mark_replace='&iquest;';
			$replace[]=$inverted_question_mark_replace;
			$cent_replace='&cent;';
			$replace[]=$cent_replace;
			$pound_replace='&pound;';
			$replace[]=$pound_replace;
			$copyright_replace='&copy;';
			$replace[]=$copyright_replace;
			$registered_replace='&reg;';
			$replace[]=$registered_replace;
			$degrees_replace='&deg;';
			$replace[]=$degrees_replace;
			$apostrophe_replace='&apos;';
			$replace[]=$apostrophe_replace;
			$euro_replace='&euro;';
			$replace[]=$euro_replace;
			$umlaut_a_replace='&auml;';
			$replace[]=$umlaut_a_replace;
			$umlaut_o_replace='&ouml;';
			$replace[]=$umlaut_o_replace;
			$umlaut_u_replace='&uuml;';
			$replace[]=$umlaut_u_replace;
			$umlaut_y_replace='&yuml;';
			$replace[]=$umlaut_y_replace;
			$umlaut_A_replace='&Auml;';
			$replace[]=$umlaut_A_replace;
			$umlaut_O_replace='&Ouml;';
			$replace[]=$umlaut_O_replace;
			$umlaut_U_replace='&Uuml;';
			$replace[]=$umlaut_U_replace;
			$umlaut_Y_replace='&Yuml;';
			$replace[]=$umlaut_Y_replace;
			$latin_small_letter_sharp_s_replace='&szlig;';
			$replace[]=$latin_small_letter_sharp_s_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search array.
			$replace=array();

			# Third with alternate HTML entities
			$dblquote_replace='&#8220;';
			$replace[]=$dblquote_replace;
			$snglquote_replace='&#8216;';
			$replace[]=$snglquote_replace;
			$dash_replace='&#x2013;';
			$replace[]=$dash_replace;
			$ampersand_replace='&#38;';
			$replace[]=$ampersand_replace;
			$lessthan_replace='&#60;';
			$replace[]=$lessthan_replace;
			$greaterthan_replace='&#62;';
			$replace[]=$greaterthan_replace;
			$space_replace='&#160;';
			$replace[]=$space_replace;
			$inverted_exclamation_mark_replace='&#161;';
			$replace[]=$inverted_exclamation_mark_replace;
			$inverted_question_mark_replace='&#191;';
			$replace[]=$inverted_question_mark_replace;
			$cent_replace='&#162;';
			$replace[]=$cent_replace;
			$pound_replace='&#163;';
			$replace[]=$pound_replace;
			$copyright_replace='&#169;';
			$replace[]=$copyright_replace;
			$registered_replace='&#174;';
			$replace[]=$registered_replace;
			$degrees_replace='&#176;';
			$replace[]=$degrees_replace;
			$apostrophe_replace='&#39;';
			$replace[]=$apostrophe_replace;
			$euro_replace='&#8364;';
			$replace[]=$euro_replace;
			$umlaut_a_replace='&aUML;';
			$replace[]=$umlaut_a_replace;
			$umlaut_o_replace='&oUML;';
			$replace[]=$umlaut_o_replace;
			$umlaut_u_replace='&uUML;';
			$replace[]=$umlaut_u_replace;
			$umlaut_y_replace='&yUML;';
			$replace[]=$umlaut_y_replace;
			$umlaut_A_replace='&AUML;';
			$replace[]=$umlaut_A_replace;
			$umlaut_O_replace='&OUML;';
			$replace[]=$umlaut_O_replace;
			$umlaut_U_replace='&UUML;';
			$replace[]=$umlaut_U_replace;
			$umlaut_Y_replace='&YUML;';
			$replace[]=$umlaut_Y_replace;
			$latin_small_letter_sharp_s_replace='&#xdf;';
			$replace[]=$latin_small_letter_sharp_s_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Fourth, we do it again
			$dblquote_search=chr(34);
			$search[]='/('.$dblquote_search.')/';
			$snglquote_search="'";
			$search[]='/('.$snglquote_search.')/';
			$dash_search=chr(45);
			$search[]='/('.$dash_search.')/';
			$ampersand_search=chr(38);
			$search[]='/('.$ampersand_search.')/';
			$lessthan_search=chr(60);
			$search[]='/('.$lessthan_search.')/';
			$greaterthan_search=chr(62);
			$search[]='/('.$greaterthan_search.')/';
			$space_search=' ';
			$search[]='/('.$space_search.')/';
			$apostrophe_search=chr(39);
			$search[]='/('.$apostrophe_search.')/';
			$latin_small_letter_sharp_s_search='ß';
			$search[]='/('.$latin_small_letter_sharp_s_search.')/';

			$dblquote_replace='&rdquo;';
			$replace[]=$dblquote_replace;
			$snglquote_replace='&rsquo;';
			$replace[]=$snglquote_replace;
			$dash_replace='&#8211;';
			$replace[]=$dash_replace;
			$ampersand_replace='&#038;';
			$replace[]=$ampersand_replace;
			$lessthan_replace='&#060;';
			$replace[]=$lessthan_replace;
			$greaterthan_replace='&#062;';
			$replace[]=$greaterthan_replace;
			$space_replace='&#xa0;';
			$replace[]=$space_replace;
			$apostrophe_replace='&#039;';
			$replace[]=$apostrophe_replace;
			$latin_small_letter_sharp_s_replace='&#223;';
			$replace[]=$latin_small_letter_sharp_s_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Fifth, again.
			$dblquote_search=chr(34);
			$search[]='/('.$dblquote_search.')/';
			$snglquote_search="'";
			$search[]='/('.$snglquote_search.')/';
			$dash_search=chr(45);
			$search[]='/('.$dash_search.')/';
			$ampersand_search=chr(38);
			$search[]='/('.$ampersand_search.')/';
			$lessthan_search=chr(60);
			$search[]='/('.$lessthan_search.')/';
			$greaterthan_search=chr(62);
			$search[]='/('.$greaterthan_search.')/';
			$apostrophe_search=chr(39);
			$search[]='/('.$apostrophe_search.')/';

			$dblquote_replace='&#8221;';
			$replace[]=$dblquote_replace;
			$snglquote_replace='&#8217;';
			$replace[]=$snglquote_replace;
			$dash_replace='&mdash;';
			$replace[]=$dash_replace;
			$ampersand_replace='&#x26;';
			$replace[]=$ampersand_replace;
			$lessthan_replace='&#x3c;';
			$replace[]=$lessthan_replace;
			$greaterthan_replace='&#x3e;';
			$replace[]=$greaterthan_replace;
			$apostrophe_replace='&#x27;';
			$replace[]=$apostrophe_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Sixth, again
			$dblquote_search=chr(34);
			$search[]='/('.$dblquote_search.')/';
			$dash_search=chr(45);
			$search[]='/('.$dash_search.')/';

			$dblquote_replace='&quot;';
			$replace[]=$dblquote_replace;
			$dash_replace='&#x2014;';
			$replace[]=$dash_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Seventh, again
			$dblquote_search=chr(34);
			$search[]='/('.$dblquote_search.')/';
			$dash_search=chr(45);
			$search[]='/('.$dash_search.')/';

			$dblquote_replace='&#34;';
			$replace[]=$dblquote_replace;
			$dash_replace='&#8212;';
			$replace[]=$dash_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Eighth, once again
			$dblquote_search=chr(34);
			$search[]='/('.$dblquote_search.')/';
			$dash_search=chr(45);
			$search[]='/('.$dash_search.')/';

			$dblquote_replace='&#034;';
			$replace[]=$dblquote_replace;
			$dash_replace='&#150;';
			$replace[]=$dash_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Ninth, again
			$alt_term=preg_replace('/('.chr(34).')/', '&#x22;', $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Tenth, again
			$umlaut_a_search='/a&(^(&a(uml|UML)))/';
			$search[]=$umlaut_a_search;
			$umlaut_o_search='/o&(^(&o(uml|UML)))/';
			$search[]=$umlaut_o_search;
			$umlaut_u_search='/u&(^(&u(uml|UML)))/';
			$search[]=$umlaut_u_search;
			$umlaut_y_search='/y&(^(&y(uml|UML)))/';
			$search[]=$umlaut_y_search;
			$umlaut_A_search='/A&(^(&A(uml|UML)))/';
			$search[]=$umlaut_A_search;
			$umlaut_O_search='/O&(^(&O(uml|UML)))/';
			$search[]=$umlaut_O_search;
			$umlaut_U_search='/U&(^(&U(uml|UML)))/';
			$search[]=$umlaut_U_search;
			$umlaut_Y_search='/Y&(^(&Y(uml|UML)))/';
			$search[]=$umlaut_Y_search;

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
			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Eleventh, again
			$umlaut_a_search='/ä/';
			$search[]=$umlaut_a_search;
			$umlaut_o_search='/ö/';
			$search[]=$umlaut_o_search;
			$umlaut_u_search='/ü/';
			$search[]=$umlaut_u_search;
			$umlaut_y_search='/ÿ/';
			$search[]=$umlaut_y_search;
			$umlaut_A_search='/Ä/';
			$search[]=$umlaut_A_search;
			$umlaut_O_search='/O/';
			$search[]=$umlaut_O_search;
			$umlaut_U_search='/Ü/';
			$search[]=$umlaut_U_search;
			$umlaut_Y_search='/Ÿ/';
			$search[]=$umlaut_Y_search;

			$umlaut_a_replace='a';
			$replace[]=$umlaut_a_replace;
			$umlaut_o_replace="o";
			$replace[]=$umlaut_o_replace;
			$umlaut_u_replace="u";
			$replace[]=$umlaut_u_replace;
			$umlaut_y_replace="y";
			$replace[]=$umlaut_y_replace;
			$umlaut_A_replace="A";
			$replace[]=$umlaut_A_replace;
			$umlaut_O_replace="Ö";
			$replace[]=$umlaut_O_replace;
			$umlaut_U_replace="U";
			$replace[]=$umlaut_U_replace;
			$umlaut_Y_replace="Y";
			$replace[]=$umlaut_Y_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Twelfth, again
			$umlaut_a_search='/&a(uml|UML);/';
			$search[]=$umlaut_a_search;
			$umlaut_o_search='/&o(uml|UML);/';
			$search[]=$umlaut_o_search;
			$umlaut_u_search='/&u(uml|UML);/';
			$search[]=$umlaut_u_search;
			$umlaut_y_search='/&y(uml|UML);/';
			$search[]=$umlaut_y_search;
			$umlaut_A_search='/&A(uml|UML);/';
			$search[]=$umlaut_A_search;
			$umlaut_O_search='/&O(uml|UML);/';
			$search[]=$umlaut_O_search;
			$umlaut_U_search='/&U(uml|UML);/';
			$search[]=$umlaut_U_search;
			$umlaut_Y_search='/&Y(uml|UML);/';
			$search[]=$umlaut_Y_search;

			$umlaut_a_replace='a';
			$replace[]=$umlaut_a_replace;
			$umlaut_o_replace="o";
			$replace[]=$umlaut_o_replace;
			$umlaut_u_replace="u";
			$replace[]=$umlaut_u_replace;
			$umlaut_y_replace="y";
			$replace[]=$umlaut_y_replace;
			$umlaut_A_replace="A";
			$replace[]=$umlaut_A_replace;
			$umlaut_O_replace="O";
			$replace[]=$umlaut_O_replace;
			$umlaut_U_replace="U";
			$replace[]=$umlaut_U_replace;
			$umlaut_Y_replace="Y";
			$replace[]=$umlaut_Y_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}

			# Reset the search and replace arrays.
			$search=array();
			$replace=array();

			# Thirteenth, again
			$a_search='/a/';
			$search[]=$a_search;
			$o_search='/o/';
			$search[]=$o_search;
			$u_search='/u/';
			$search[]=$u_search;
			$y_search='/y/';
			$search[]=$y_search;
			$A_search='/A/';
			$search[]=$A_search;
			$O_search='/O/';
			$search[]=$O_search;
			$U_search='/U/';
			$search[]=$U_search;
			$Y_search='/Y/';
			$search[]=$Y_search;

			$a_replace='ä';
			$replace[]=$a_replace;
			$o_replace="ö";
			$replace[]=$o_replace;
			$u_replace="ü";
			$replace[]=$u_replace;
			$y_replace="ÿ";
			$replace[]=$y_replace;
			$A_replace="Ä";
			$replace[]=$A_replace;
			$O_replace="Ö";
			$replace[]=$O_replace;
			$U_replace="Ü";
			$replace[]=$U_replace;
			$Y_replace="Ÿ";
			$replace[]=$Y_replace;

			$alt_term=preg_replace($search, $replace, $term);
			if(!in_array($alt_term, $alt_terms))
			{
				$alt_terms[]=$alt_term;
			}
		}
		# Loop through the alternate terms.
		foreach($alt_terms as $alt_term)
		{
			if(!empty($alt_term) && (strpos($exclude, $alt_term)===FALSE))
			{
				# Add the term to the output array ($out).
				if(!in_array($alt_term, $out))
				{
					$out[]=$alt_term;
				}
			}
		}

		return $out;
	}

	/**
	 * Emphasize the search terms in the returned results search.
	 *
	 * @param array $terms    The terms to search for.
	 * @param string $content The returned content in which to emphasize terms.
	 * @return mixed
	 */
	protected function emphasizeTerms($terms, $content)
	{
		# Create an array of all search terms.
		$a_terms=$this->splitTerms($terms);
		$r_terms=array();
		$s_terms=array();

		# Loop through all search terms and surround them with a <span> with a css class.
		foreach($a_terms as $term)
		{
			$r_terms[]='<span class="emphasize">'.$term.'</span>';
		}
		# Empty the $term variable
		$term=NULL;

		# Prepare the search terms for preg_replace.
		foreach($a_terms as $term)
		{
			$s_terms[]='/'.$term.'/i';
		}

		# pre_replace all search terms with "emphasized" terms.
		$content=preg_replace($s_terms, $r_terms, $content);

		return $content;
	}

	/**
	 * Escapes the string ($string) in-case some of the characters in the search term contain a MySQL regular expression meta-character.
	 * Returns the escaped string.
	 *
	 * @param string $string The string we're escaping
	 * @return mixed
	 */
	protected function escapeMetaChars($string)
	{
		# Insert a slash before each meta-character that MySQL uses.
		return preg_replace("/([.\[\]*^\$])/", '\\\$1', $string);
	}

	/**
	 * Turns an array of search terms ($terms) into a list of regular expressions suitable for MYSQL.
	 * Returns an array of regular expressions suitable for MYSQL.
	 *
	 * @param array $terms The array of search terms
	 * @return array
	 */
	protected function convertTerms2RegEx($terms)
	{
		# Make certain that the passed variable is an array.
		$terms=(array)$terms;
		# Create a new array for our output.
		$out=array();
		# Loop through the search terms.
		foreach($terms as $term)
		{
			# Using the escapeMetaChars method, escape the search term and add it to our output array ($out).
			$out[]=addslashes($this->escapeMetaChars($term));
			//$out[]='[[:<:]]'.addslashes($this->escapeMetaChars($term)).'[[:>:]]';
			//$out[]='%'.addslashes($this->escapeMetaChars($term)).'%';
		}

		return $out;
	}

	/**
	 * Converts any special characters to html entities.
	 *
	 * @param array $terms The array of search terms
	 * @return array
	 */
	protected function convertChars2Entities($terms)
	{
		# TODO: NEEDS FUNCTIONALITY!

		return $terms;
	}

	/**
	 * Builds and returns the "where" portion of the search query.
	 *
	 * @param array $terms  The term we're searching for.
	 * @param array $fields The fields we're searching in.
	 * @param array $branch
	 * @param array $filter Fields and or terms we would like exluded.
	 * @return string
	 */
	protected function prepareWhere($terms, $fields, $branch, $filter)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		$terms=$this->splitTerms($terms);
		//$terms=iconv('UTF-8', 'ASCII//TRANSLIT', $terms);
		/*
		$terms=strtr(utf8_decode($terms),
			utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
			'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
		*/
		//print_r($terms);exit;

		$terms_db=$this->convertTerms2RegEx($terms);

		$filter_sql='';
		# TODO: Filter needs work.
		if($filter!==NULL)
		{
			if(isset($filter['filter_sql']))
			{
				# $filter="`Party` = 'yes' AND "
				$filter_sql=$filter['filter_sql'].' AND ';
			}
		}

		$field_parts=array();
		$branch_parts=array();
		$terms_db=implode('|', $terms_db);

		# $parts[]="`Username` RLIKE '$term_db'";
		foreach($fields as $field)
		{
			if(($filter===NULL) || (isset($filter['filter_fields']) && !in_array($field, $filter['filter_fields'])))
			{
				$field_parts[]='`'.$field.'` RLIKE '.$db->quote($terms_db);
			}
		}
		$field_parts=implode(' OR ', $field_parts);

		if($branch!==NULL)
		{
			$search_branch=explode(' ', $branch);
			if(is_array($search_branch))
			{
				# $branch_parts[]="`branch` LIKE '%-$branch_id-%'";
				foreach($search_branch as $branch_id)
				{
					$branch_parts[]='`branch` LIKE \'%-'.$branch_id.'-%\'';
				}
				$branch_parts=implode(' OR ', $branch_parts);
			}
		}

		return $filter_sql.((!empty($field_parts)) ? '('.$field_parts.')' : '').(!empty($branch_parts) ? (!empty($field_parts) ? ' AND ' : '').'('.$branch_parts.')' : '');
	}
	/*** End protected methods ***/
}