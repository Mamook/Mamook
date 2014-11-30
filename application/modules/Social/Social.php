<?php

if(!defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * Social
 *
 * The Social class is used to check if the Social button is active, and to get the button image.
 *
 */
class Social
{
	/*** data members ***/

	protected $social;
	protected $name;
	protected $url;
	protected $image;

	/*** End data members ***/



	/*** magic methods ***/

	/*** End magic methods ***/



	/*** mutator methods ***/

	/***
	 * setSocial
	 *
	 * Sets the data member $social.
	 *
	 * @param	$social
	 * @access	protected
	 */
	protected function setSocial($social)
	{
		$this->social=$social;
	} #==== End -- setSocial

	/***
	 * setName
	 *
	 * Sets the data member $name.
	 *
	 * @param	$name
	 * @access	protected
	 */
	protected function setName($name)
	{
		$this->name=$name;
	} #==== End -- setName

	/***
	 * setUrl
	 *
	 * Sets the data member $url.
	 *
	 * @param	$url
	 * @access	protected
	 */
	protected function setUrl($url)
	{
		$this->url=$url;
	} #==== End -- setUrl

	/***
	 * setImage
	 *
	 * Sets the data member $image.
	 *
	 * @param	$image
	 * @access	protected
	 */
	protected function setImage($image)
	{
		$this->image=$image;
	} #==== End -- setImage

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/***
	 * getSocial
	 *
	 * Returns the data member $social.
	 *
	 * @access	protected
	 */
	protected function getSocial()
	{
		return $this->social;
	} #==== End -- getSocial

	/***
	 * getName
	 *
	 * Returns the data member $name.
	 *
	 * @access	protected
	 */
	protected function getName()
	{
		return $this->name;
	} #==== End -- getName

	/***
	 * getUrl
	 *
	 * Returns the data member $url.
	 *
	 * @access	protected
	 */
	protected function getUrl()
	{
		return $this->url;
	} #==== End -- getUrl

	/***
	 * getImage
	 *
	 * Returns the data member $image.
	 *
	 * @access	protected
	 */
	protected function getImage()
	{
		return $this->image;
	} #==== End -- getImage

	/*** End accessor methods ***/



	/*** public methods ***/

	/***
	 * displaySocial
	 *
	 * Displays the social buttons.
	 *
	 * @access	public
	 */
	public function displaySocial()
	{
		# Set the Document instance to a variable.
		$doc=Document::getInstance();
		# Include JavaScripts in the footer. (Use the script file name before the ".php".)
		$doc->setFooterJS('AddThis');
		$display=
			'<!-- AddThis Button BEGIN -->'.
			'<div class="addthis_toolbox addthis_default_style">'.
				'<a class="addthis_button_preferred_1"></a>'.
				'<a class="addthis_button_preferred_2"></a>'.
				'<a class="addthis_button_google_plusone"></a>'.
				'<a class="addthis_button_preferred_3"></a>'.
				'<a class="addthis_button_preferred_4"></a>'.
				'<a class="addthis_button_compact"></a>'.
				'<a class="addthis_counter addthis_bubble_style"></a>'.
			'</div>'.
			'<!-- AddThis Button END -->';
		return $display;
	} #==== End -- displaySocial

	/***
	 * displaySocialFeeds
	 *
	 * Displays the social buttons.
	 *
	 * @access	public
	 */
	public function displaySocialFeeds($social_data)
	{
		# Get the Facebook class.
		require_once MODULES.'Social'.DS.'Facebook'.DS.'CustomFacebook.php';
		# Instantiate a new CustomFacebook object.
		$facebook=new CustomFacebook();

		$display='<ul class="post">';
		# Loop through the passed social data.
		for($i=0; $i<$social_data['post_count']; $i++)
		{
			# Set the social network to a variable.
			$network=$social_data[$i]['network'];
			# Check which social network was passed and set the appropriate social network url to a variable.
			switch($social_data[$i]['network'])
			{
				case 'Facebook':
					$url=FB_URL;
					break;
				case 'Twitter':
					$url=TWITTER_URL;
			}
			$display.='<li class="soc-content '.strtolower($network).Document::addAlternatingClass($i, 'other').'">';
			$display.='<article>';
			$display.='<h1 class="h1">'.$network.' Post</h1>';
			$display.='<div>'.$facebook::getFB_PostImage($social_data[$i]['social_data']);
			$display.='<span class="post-date">'.$social_data[$i]['time'].'</span>';
			$display.='- ';
			$display.='<a href="'.$social_data[$i]['user_url'].'" class="post-author" target="_blank">'.$social_data[$i]['poster'].'</a> posted on <a href="'.$url.'" target="_blank">'.$network.'</a>';
			$display.=((!empty($social_data[$i]['msg'])) ? '<span class="entry">'.$social_data[$i]['msg'].'</span>' : '');
			$display.=((!empty($social_data[$i]['link'])) ? '<a href="'.$social_data[$i]['link'].'" class="post-link" title="'.((!empty($social_data[$i]['link_name'])) ? $social_data[$i]['link_name'] : $social_data[$i]['link']).'" target="_blank">'.((!empty($social_data[$i]['link_name'])) ? $social_data[$i]['link_name'] : $social_data[$i]['link']).'</a>' : '');
			$display.='</div>';
			$display.='</article>';
			$display.='</li>';
			# Unset the social network variable.
			unset($network);
		}
		$display.='</ul>';
		if($social_data['post_count']<1)
		{
			$display=NULL;
		}
		return $display;
	} #==== End -- displaySocialFeeds

	/*** End public methods ***/



	/*** private methods ***/

	/*
	 * getSocialData
	 *
	 * Retrieves posts from social network's servers.
	 *
	 * @access	private
	 */
	public function getSocialData($num_posts=10)
	{
		# Get the Facebook Class.
		require_once MODULES.'Social'.DS.'Facebook'.DS.'CustomFacebook.php';
		# Instantiate a new Facebook object.
		$facebook=new CustomFacebook();

		# Get the Facebook feed.
		$fb_feed=(array)$facebook->getFB_Feed($num_posts);

		# Set the Twitter constructor params to an array.
		$params=array(
			'consumer_key'=>TWITTER_CONSUMER_KEY,
			'consumer_secret'=>TWITTER_CONSUMER_SECRET,
			'oauth_token'=>TWITTER_TOKEN,
			'oauth_token_secret'=>TWITTER_TOKEN_SECRET
		);
		# Get the Twitter class.
		require_once MODULES.'Social'.DS.'Twitter'.DS.'Twitter.php';
		# Instantiate a new Twitter object.
		$twitter=new Twitter($params);

		# Get the Twitter feed.
		$tweets=(array)$twitter->getTwitterFeed($num_posts);

		# Combine the social data into a single array and sort by date.
		$social_data=array_merge($fb_feed, $tweets);

		# Remove the post count data.
		unset($social_data['post_count']);

		# Sort the data by date.
		$social_data=$this->SortByDate($social_data);

		# Count the elements in the new array.
		$count=count($social_data);

		# Replace the 'post_count' key in the new array with the new count value (minus one, the 'post_count' key itself).
		$social_data['post_count']=$count;

		return $social_data;
	} #==== End -- getSocialData

	/**
	 * SortByDate
	 *
	 * Returns the array of social data sorted by date.
	 *
	 * @access	private
	 * @param	array $social_data		An array of values to sort
	 * @return	array
	 */
	private function SortByDate($social_data, $key='time')
	{
		# Instantiate a new Utility object.
		$utility = new Utility();
		# Sort the playlist array by date.
		$social_data=$utility->SortByDate($social_data, $key);
		return $social_data;
	} #==== End -- SortByDate


	/*** End private methods ***/

} # End Social class.