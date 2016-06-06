<?php /* framework/application/modules/Content/WordPressSubContent.php */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');

# Get the parent Content class.
require_once Utility::locateFile(MODULES.'Content'.DS.'SubContent.php');

/**
 * WordPressSubContent
 *
 * The WordPressSubContent Class is used access WordPress content outside of a WordPress installation.
 *
 */
class WordPressSubContent extends SubContent
{
	/*** data members ***/

	private $all_wp_posts=NULL;
	private $wp_author_id=NULL;
	private $wp_excerpt=NULL;

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
	 * setAllWPPosts
	 *
	 * Sets the data member $all_wp_posts.
	 *
	 * @param		$wp_posts
	 * @access	protected
	 */
	protected function setAllWPPosts($wp_posts)
	{
		# Check if the passed value is empty.
		if(!empty($wp_posts))
		{
			# Explicitly make it an array.
			$wp_posts=(array)$wp_posts;
			# Set the data member.
			$this->all_wp_posts=$wp_posts;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->all_wp_posts=NULL;
		}
	} #==== End -- setAllWPPosts

	/**
	 * setWPAuthorID
	 *
	 * Sets the data member $wp_author_id.
	 *
	 * @param		$id 			(The WordPress author id number.)
	 * @access	protected
	 */
	protected function setWPAuthorID($id)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed $id is empty.
		if(!empty($id))
		{
			# Clean it up.
			$id=trim($id);
			# Check if the passed $id is an integer.
			if($validator->isInt($id)===TRUE)
			{
				# Explicitly make it an integer.
				$id=(int)$id;
				# Set the data member
				$this->wp_author_id=$id;
			}
			else
			{
				throw new Exception('The passed WordPress author id was not an integer!', E_RECOVERABLE_ERROR);
			}
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->wp_author_id=NULL;
		}
	} #==== End -- setWPAuthorID

	/**
	 * setWPExcerpt
	 *
	 * Sets the data member $wp_excerpt.
	 *
	 * @param		$excerpt
	 * @access	protected
	 */
	protected function setWPExcerpt($excerpt)
	{
		# Check if the passed value is empty.
		if(!empty($excerpt))
		{
			# Set the data member.
			$this->wp_excerpt=$excerpt;
		}
		else
		{
			# Explicitly set the data member to NULL.
			$this->wp_excerpt=NULL;
		}
	} #==== End -- setWPExcerpt

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getAllWPPosts
	 *
	 * Returns the data member $all_wp_posts.
	 *
	 * @access	public
	 */
	public function getAllWPPosts()
	{
		return $this->all_wp_posts;
	} #==== End -- getAllWPPosts

	/**
	 * getWPAuthorID
	 *
	 * Returns the data member $wp_author_id.
	 *
	 * @access	public
	 */
	public function getWPAuthorID()
	{
		return $this->wp_author_id;
	} #==== End -- getWPAuthorID

	/**
	 * getWPExcerpt
	 *
	 * Returns the data member $wp_excerpt.
	 *
	 * @access	public
	 */
	public function getWPExcerpt()
	{
		return $this->wp_excerpt;
	} #==== End -- getWPExcerpt

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * deleteWP_Post
	 *
	 * Deletes a WordPress post from the Database.
	 *
	 * @param		$post_id 	(The id of the post to delete.)
	 * @access	public
	 */
	public function deleteWP_Post($post_id)
	{
		try
		{
			/*** THIS NEEDS IMPLEMENTATION!!! ***/
			return;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- deleteWP_Post

	/**
	 * displayWPPosts
	 *
	 * Creates WordPress post XHTML elements and sets them to an array for display
	 *
	 * @param		$max_char 		(The maximum number of characters to display.)
	 * @param		$buttons 			(TRUE if other buttons should be displayed, ie "download", "more", FLASE if not.)
	 * @access	public
	 */
	public function displayWPPosts($max_char=NULL, $buttons=TRUE)
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Bring the Login object into scope.
		global $login;

		try
		{
			# Set the retrieved WordPress posts to a variable.
			$all_wpcontent=$this->getAllWPPosts();
			# Check if there is wpcontent to display.
			if($all_wpcontent!==NULL)
			{
				# Create an empty array to hold WordPress post id's after that record has been added to the $display_content variable.
				$used_ids=array();
				# Create new array to hold all display content.
				$display_content=array();
				# Loop throught the wpcontent.
				foreach($all_wpcontent as $row)
				{
					# Set all relevant Data members.
					$this->setWPDataMembers($row);
					# Create a variable for the id.
					$id=$this->getID();
					# Check if this id has already been used.
					if(!in_array($id, $used_ids))
					{
						# Add this id to the used id's.
						$used_ids[]=$id;
						# Make the display content array multi-dimensional.
						$display_content[$id]=array('date'=>NULL, 'title'=>NULL, 'text'=>NULL, 'excerpt'=>NULL);
						# Create variable for the author.
						$author=$this->getWPAuthorID();
						# Create variable for date.
						$date=$this->getDate();
						# Convert the date string to time.
						$date=strtotime($date);
						# Create variable for title.
						$title=$this->getTitle();
						# Create variable for text.
						$text=$this->getText();
						#
						# Create variable for the excerpt.
						$excerpt=$this->getWPExcerpt();
						# Create a variable to hold whether or not a "more" link should be displayed. Default is FALSE.
						$more=FALSE;
						# Check if a maximum number of characters to be displayed has been passed.
						if($max_char!==NULL)
						{
							# Check if there is text to display.
							if(!empty($text))
							{
								# Strip tags from the text and see if it contains more characters than allotted in the maximum characters variable.
								if(strlen(strip_tags($text)) > $max_char)
								{
									# Use truncate from the Document class to truncate the text.
									$text=WebUtility::truncate($text, $max_char, '...%1s', TRUE);
									# Add a "more" link to the text.
									$text.='<a class="more" href="'.WP_SITEURL.'?p='.$id.'" title="more on: '.$title.'">'.$this->getMore().'</a>'."\n";
									# Set the $more value to TRUE.
									$more=TRUE;
								}
							}
						}
						if($author!==NULL)
						{
							# Get the WordPressUser class.
							require_once Utility::locateFile(MODULES.'User'.DS.'WordPressUser.php');
							# Instantiate a new WordPressUser object.
							$wp_user=new WordPressUser();
							# Get the WordPress User's nickname.
							$nickname=$wp_user->getWP_Nickname($author);
							# Create a variable to hold the contributor display XHTML and open a list tag.
							$author_content='<div class="post-author">'."\n";
							$author_content.='<span class="label">Posted by: </span><a href="'.WP_SITEURL.'/author/'.$nickname.'/" title="More posts by '.$nickname.'">'.$nickname.'</a>'."\n";
							$author_content.='</div>'."\n";
							# Set the author content to the array.
							$display_content[$id]['author']=$author_content;
						}
						# Check if the date value is NULL.
						if($date!==NULL)
						{
							# Set the date to a variable.
							$date_content='<span class="post-date"><span class="post-month">'.date("F", $date).'</span> <span class="post-day">'.date("d", $date).'</span>, <span class="post-year">'.date("Y", $date).'</span>'."\n".'</span>'."\n";
							# Set the date content to the array.
							$display_content[$id]['date']=$date_content;
						}
						# Set the title to a variable.
						$title_content='<span class="post-title"><a href="'.WP_SITEURL.'?p='.$id.'">'.$title.'</a></span>'."\n";
						# Set the title content to the array.
						$display_content[$id]['title']=$title_content;
						# Check if there is text to display.
						if(!empty($text))
						{
							# Set the text to a variable.
							$text_content='<div class="entry">'."\n";
							$text_content.=$text."\n";
							$text_content.='</div>'."\n";
							# Set the text content to the array.
							$display_content[$id]['text']=$text_content;
						}
					}
				}
				return $display_content;
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- displayWPPosts

	/**
	 * getWPPosts()
	 *
	 * Retrieves records from the a worpress posts table.
	 *
	 * @param	$prefix (The WordPress table prefix. Default is none.)
	 * @param	$limit (The LIMIT of the records.)
	 * @param	$fields (The name of the field(s) to be retrieved.)
	 * @param	$order (The name of the field to order the records by.)
	 * @param	$direction (The direction to order the records.)
	 * @param	$and_sql (Extra AND statements in the query.)
	 * @access	public
	 */
	public function getWPPosts($limit=NULL, $fields='*', $order='post_date', $direction='DESC', $and_sql=NULL, $only_published=TRUE)
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		# Check if only published posts are to be retrieved.
		if($only_published===TRUE)
		{
			# Add to the and_sql variable.
			$and_sql.=' AND `post_status` = '.$db->quote($db->escape('publish'));
		}
		try
		{
			# Retrieve the posts from the Database.
			$posts=$db->get_results('SELECT '.$fields.' FROM `'.WP_DBPREFIX.'posts` WHERE `post_type` = '.$db->quote($db->escape('post')).((!empty($and_sql)) ? $and_sql : '').' ORDER BY `'.$order.'` '.$direction.(($limit===NULL) ? '' : ' LIMIT '.$limit));
			# Set the returned posts to the data member.
			$this->setAllWPPosts($posts);
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.'<br />Code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getWPPosts

	/**
	 * setWPDataMembers
	 *
	 * Sets all the data returned in a row from a WordPress `posts` table to the appropriate Data members.
	 *
	 * @param		$row 		(The returned row of data from a record to set to the data members.)
	 * @access	public
	 */
	public function setWPDataMembers($row)
	{
		try
		{
			# Set the Wordpress post row data to Data members.
			$this->setID($row->ID);
			$this->setDate($row->post_date);
			$text=$row->post_content;
			$charset=mb_detect_encoding($text, 'auto');
			if($charset===FALSE) $charset='windows-1252';
			$text=iconv($charset, 'UTF-8//TRANSLIT', $text);
			$this->setText($text);
			$title=$row->post_title;
			$charset=mb_detect_encoding($title, 'auto');
			if($charset===FALSE) $charset='windows-1252';
			$title=iconv($charset, 'UTF-8//TRANSLIT', $title);
			$this->setTitle($title);
			$this->setWPAuthorID($row->post_author);
			$this->setWPExcerpt($row->post_excerpt);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- setWPDataMembers

	/*** End public methods ***/

} #=== End WordPressSubContent class.