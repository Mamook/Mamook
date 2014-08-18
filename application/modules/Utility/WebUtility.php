<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


# Get the Utility Class.
require_once MODULES.'Utility'.DS.'Utility.php';


/**
 * WebUtility
 *
 * The WebUtility class is used to for miscellaneous utility
 * methods that must be used on scripts in a browser. Most methods
 * here are static.
 */
class WebUtility extends Utility
{
	/*** data members ***/

	/*** End data members ***/



	/*** mutator methods ***/

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * removeGetQuery
	 *
	 * Removes GET query from the passed URL. Must be called before removeIndex method.
	 *
	 * @param 	$url 		(The URL to check.)
	 * @access	public
	 */
	public static function removeGetQuery($url)
	{
		# Create a variable wth the default value of the passed URL.
		$alt_url=str_ireplace(GET_QUERY, '', $url);
		# Check if the link has a page query.
		if(!empty($alt_url))
		{
			$url=$alt_url;
		}
		return $url;
	} #==== End -- removeGetQuery

	/**
	 * removePageQuery
	 *
	 * Removes "?page=#" query from the passed URL.
	 *
	 * @param 	$url 		(The URL to check.)
	 * @access	public
	 */
	public static function removePageQuery($url)
	{
		# Check if the link has a page query.
		if(strpos($url, '?page=')!==FALSE)
		{
			$url=preg_replace('/page\=[0-9]+\&/', '', $url);
		}
		return $url;
	} #==== End -- removePageQuery

	/**
	 * removeSchemeName
	 *
	 * Removes scheme name (ie http://) from the passed URL.
	 *
	 * @param 	$url 		(The URL to check.)
	 * @access	public
	 */
	public static function removeSchemeName($url)
	{
		# Check if the link has a scheme name.
		if(preg_match('/^((https?|s?ftp)\:\/\/)|(mailto\:)/', $url)!==0)
		{
			$url=preg_replace('/^((https?|s?ftp)\:\/\/)|(mailto\:)/', '', $url, 1);
		}
		return $url;
	} #==== End -- removeSchemeName

	/*** End public methods ***/



	/*** protected methods ***/

	/*** End protected methods ***/



	/*** private methods ***/

	/*** End private methods ***/

} #=== End WebUtility class.