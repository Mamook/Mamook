<?php

/***
 * Theme
 *
 * The Theme class is used to access and manipulate the `theme` table as well as to get and display the theme for the application.
 *
 */
class Theme
{
	/*** data members ***/

	protected $name;
	protected $designer;
	protected $website;
	/*** End data members ***/



	/*** magic methods ***/

	public function __construct()
	{
		try
		{
			$this->getTheme();
		}
		catch(Exception $e)
		{
		 throw $e;
		}
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** mutator methods ***/

	/***
	* setName
	*
	* Sets the theme's name
	*
	* @param	$name
	* @access	public
	*/
	public function setName($name)
	{
		$this->name=$name;
	} #==== End -- setName

	/***
	* setDesigner
	*
	* Sets the Theme's designer
	*
	* @param	$designer
	* @access	public
	*/
	public function setDesigner($designer)
	{
		$this->designer=$designer;
	} #==== End -- setDesigner

	/***
	* setWebsite
	*
	* Sets the Theme's website
	*
	* @param	$website
	* @access	public
	*/
	public function setWebsite($website)
	{
		$this->website=$website;
	} #==== End -- setWebsite

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/***
	* getName
	*
	* Returns the data member $name.
	*
	* @access	protected
	*/
	public function getName()
	{
		if(!empty($this->name))
		{
			return $this->name;
		}
		else
		{
			throw new Exception('Content Theme name is not set', E_WARNING);
		}
	} #==== End -- getName

	/***
	* getDesigner
	*
	* Returns the data member $designer.
	*
	* @access	protected
	*/
	public function getDesigner()
	{
		return $this->designer;
	} #==== End -- getDesigner

	/***
	* getWebsite
	*
	* Returns the data member $website.
	*
	* @access	protected
	*/
	public function getWebsite()
	{
		return $this->website;
	} #==== End -- getWebsite

	/*** End accessor methods ***/



	/*** public methods ***/

	/***
	* displayMasthead
	*
	* Displays the application masthead.
	*
	* @access	public
	*/
	public function displayMasthead()
	{
		# Bring the content instance into scope.
		$main_content=Content::getInstance();
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		$site_name=$main_content->getSiteName();
		$site_name=str_ireplace('%{domain_name}', DOMAIN_NAME, $site_name);

		# Get the Site's slogan.
		$slogan=$main_content->getSlogan();
		# Check if there is a slogan.
		if(!empty($slogan))
		{
			# Reset the slogan varaible with the slogan markup.
			$slogan=' <span class="slogan">'.$slogan.'</span>';
		}
		else
		{
			# Explicitly set the slogan variable to NULL.
			$slogan=NULL;
		}

		# Get the name of the theme we are using.
		$theme_name=$this->getName();

		# Set theme variables.
		$theme_path=THEME_PATH;
		$theme_url=THEME;

		# Display masthead. (Go to www.addthis.com/dashboard to customize the AddThis tools.)
		$display='<h1 class="h-mast"><a href="'.APPLICATION_URL.'" title="'.$site_name.'">'.$site_name.$slogan.'</a></h1><a class="accessaid" href="#main" title="Skip Navigation">Skip to content</a><div class="addthis_horizontal_follow_toolbox"></div>';

		return $display;
	} #==== End -- displayMasthead

	/*** End public methods ***/



	/*** protected methods ***/

	/***
	* getTheme
	*
	* Get the default theme from the theme table
	*
	* @access	protected
	*/
	protected function getTheme()
	{
		# Set the Database instance to a variable.
		$db=DB::get_instance();

		try
		{
			# Get the theme info from the Database.
			$theme=$db->get_row('SELECT `name`, `designer`, `website` FROM `'.DBPREFIX.'themes` WHERE `default` IS NOT NULL LIMIT 1');
			# Set the theme name to the Data member.
			$this->setName($theme->name);
			# Set the theme designer to the Data member.
			$this->setDesigner($theme->designer);
			# Set the theme designer's website to the Data member.
			$this->setWebsite($theme->website);
		}
		catch(ezDB_Error $ez)
		{
			throw new Exception('Error occured: '.$ez->error.', code: '.$ez->errno.'<br />Last query: '.$ez->last_query, E_RECOVERABLE_ERROR);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- getTheme

	/*** End protected methods ***/

} #=== End Theme class.