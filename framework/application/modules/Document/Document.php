<?php /* Requires PHP5+ */

# Make sure the script is not accessed directly.
if(!defined('BASE_PATH')) exit('No direct script access allowed');


/**
 * Document
 *
 * The Document Class is used to add data scripts and data to a document.
 *
 */
class Document
{
	/*** data members ***/

	private static $document;
	private $error=NULL;
	private $footer_js=array();
	private $java_scripts=array();
	private $style=array();

	/*** End data members ***/



	/*** mutator methods ***/

	/**
	 * setError
	 *
	 * Sets the data member $error.
	 *
	 * @param		$error
	 * @access	public
	 */
	public function setError($error)
	{
		$this->error=$error;
	} #==== End -- setError

	/**
	 * setFooterJS
	 *
	 * Sets the data member $footer_js.
	 *
	 * @param		$script_name (Must be the name of the script. May be an array of java scripts.)
	 * @access	public
	 */
	public function setFooterJS($script_name)
	{
		$script_name=explode(',', $script_name);
		foreach($script_name as $script)
		{
			$this->footer_js[]=$script;
		}
	} #==== End -- setFooterJS

	/**
	 * setJavaScripts
	 *
	 * Sets the data member $java_scripts. The script name must be in the format of -'FolderPath/FileName' or 'FileName'. If there is more than one, they may be comma separated ('FileName1,FolderPath2/FileName2, FileName3') or an Array (array(0=>'FileName1', 1=>'FolderPath2/FileName2', 3->' FileName3')). If there is whitespace at the beginning or end of the fileneame, it will be trimmed off.
	 *
	 * @param		$scripts (Must be in the format of -'FolderPath/FileName' or 'FileName')
	 * @access	public
	 */
	public function setJavaScripts($scripts)
	{
		if(!is_array($scripts))
		{
			$scripts=explode(',', $scripts);
		}
		foreach($scripts as $script_name)
		{
			$this->java_scripts[]=trim($script_name);
		}
	} #==== End -- setJavaScripts

	/**
	 * setStyle
	 *
	 * Sets the data member $style. Returns FALSE on failure. Accepts comma separated stylesheet paths. If specific stylesheets for a responsive design are to be included, a JSON String may be appended to the stylesheet pathname. The properties in the JSON may be: "device", "property", "value", and "tag". The "device" value being the device the specific CSS is targeting (ie "screen"). The "property" value being the property to measure against (ie "max-width"). The "value" value being the property value to measure against (ie "1000em"). The "tag" value being a string that is appended to the end of the stylesheet name to make it locatable and unique (ie if "path/stylesheet.css" is the passed stylesheet name, a "tag" value of "screen1000" would indicate "path/stylesheet.screen1000.css".)
	 *
	 * @example
	 *
	 * @param		string $style_sheet		Must be a url to a style sheet. May be comma seperated style sheets or an array of stylesheets.
	 * @access	public
	 */
	public function setStyle($style_sheet)
	{
		if(!empty($style_sheet))
		{
			# Check if the passed value is NOT an Array.
			if(!is_array($style_sheet))
			{
				# Split the passed string on commas returning an array of stylesheets. Do NOT split on commas  inside of JSON.
				$style_sheet=preg_split('/,(?![^{}]*+\\})/i', $style_sheet);
			}
			# Loop through the Array of stylesheets.
			foreach($style_sheet as $style)
			{
				# Get the position of the last backslash.
				$pos=strrpos($style, '/');
				# Check if there was NO backslash. (If not, it's not a path, just a filename.)
				if($pos===FALSE)
				{
					# Prepend the path to the current Theme's CSS folder to the stylesheet filename.
					$style=THEME.'css/'.$style;
				}
				# Add each stylesheet to the "style" data member Array.
				$this->style[]=$style;
			}
		}
		else
		{
			throw new Exception('The Style Sheet was not set!', E_RECOVERABLE_ERROR);
		}
	} #==== End -- setStyle

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/**
	 * getError
	 *
	 * Returns the data member $error.
	 *
	 * @access	public
	 */
	public function getError()
	{
		return $this->error;
	} #==== End -- getError

	/**
	 * getFooterJS
	 *
	 * Returns the data member $footer_js.
	 *
	 * @access	protected
	 */
	protected function getFooterJS()
	{
		return $this->footer_js;
	} #==== End -- getFooterJS

	/**
	 * getJavaScripts
	 *
	 * Returns the data member $java_scripts.
	 *
	 * @access	protected
	 */
	protected function getJavaScripts()
	{
		return $this->java_scripts;
	} #==== End -- getJavaScripts

	/**
	 * getStyle
	 *
	 * Returns the data member $style. Returns FALSE on failure.
	 *
	 * @access	protected
	 */
	protected function getStyle()
	{
		if(isset($this->style) && !empty($this->style))
		{
			return $this->style;
		}
		else
		{
			throw new Exception('The Style is not set', E_RECOVERABLE_ERROR);
		}
	} #==== End -- getStyle

	/*** End accessor methods ***/



	/*** public methods ***/

	/**
	 * addAlternatingClass
	 *
	 * Returns the passed class name if the passed number is odd. Returns NULL if passed number is even.
	 *
	 * @access	public
	 * @param		array
	 * @return	string or NULL
	 */
	public static function addAlternatingClass($number, $class_name)
	{
		/* The modulous operator (%) returns the remainder of a division.
		The remainder of any even integer that is divided by two will always be zero.  PHP will conveniently interpret 0 as false and any non-zero as true. */
		if($number % 2) { return ' '.$class_name; }
		else { return NULL; }
	} #==== End -- addAlternatingClass

	/**
	 * addErrorBox
	 *
	 * Adds a div for errors and messages.
	 *
	 * @access	public
	 */
	public function addErrorBox()
	{
		global $alert_title;

		$error_box='';
		$error=$this->getError();
		$js_errors='';

		if(!empty($error) || (isset($_SESSION['message']) && !empty($_SESSION['message'])))
		{
			$error_box.='<noscript>';
			$error_box.='<div class="empty"></div>';
			$error_box.='<div class="alertBox">';
			$error_box.='<h3>'.$alert_title.'</h3>';
			if(!empty($error))
			{
				# Format the message for xhtml display and set it to a variable.
				//$error='<p>'.$error.'</p>';
				# Concatenate the error message to the error box.
				$error_box.=$error;
				# Concatenate the error message to the errorvariable for Javascript error display.
				$js_errors.=$error;
				# clear the error
				unset($error);
			}
			if(isset($_SESSION['message']))
			{
				# Format the message for xhtml display and set it to a variable.
				$message='<p>'.$_SESSION['message'].'</p>';
				# Concatenate the error message to the error box.
				$error_box.=$message;
				# Concatenate the error message to the errorvariable for Javascript error display.
				$js_errors.=$message;
				# Clear the message
				unset($_SESSION['message']);
			}
			$error_box.='</div><div class="empty"></div>';
			$error_box.='</noscript>';
			# Set the error data member for Javascript display.
			$this->setError($js_errors);
		}
		return $error_box;
	} #==== End -- addErrorBox

	/**
	 * addFooterJS
	 *
	 * Puts the correct javascripts into the footer for the page.
	 *
	 * @access	public
	 */
	public function addFooterJS()
	{
		try
		{
			return $this->addJavaScript($this->getFooterJS());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- addFooterJS

	/**
	 * addJSErrorBox
	 *
	 * Adds a div for errors and messages via Javascript.
	 *
	 * @param		check_cookies (should we check if cookies are enabled? Default is FALSE)
	 * @access	public
	 */
	public function addJSErrorBox()
	{
		# $error and $no_cookie_msg may be set outside of this method. If they are not set, defaults are created in this method.
		global $error, $no_cookie_msg, $alert_title, $check_cookies;

		if(!isset($error) || empty($error))
		{
			$error=$this->getError();
		}

		$js_no_cookie_msg='';
		if(!isset($no_cookie_msg) || empty($no_cookie_msg))
		{
			$js_no_cookie_msg='You do not have cookies enabled in you browser. Some aspects of this website may not function correctly. You may enable or disable cookies in your browser. You may find out more about cookies at <a href="http://en.wikipedia.org/wiki/HTTP_cookie" target="_blank">http://en.wikipedia.org/wiki/HTTP_cookie</a>';
			$js_no_cookie_msg=preg_replace('/\r?\n/', '\\n', addslashes($js_no_cookie_msg));
		}

		$js_error_msg='var noCookie=null;';
		$js_error_msg.='$().customAlert({
		alertOk: \'OK\',
		draggable: false});';
		$js_error_msg.='var noCookieMsg="";';
		$js_error_msg.='var pageError="";';
		$js_error_msg.='if(navigator.cookieEnabled == 0){noCookie=true}';
		if($check_cookies!==FALSE)
		{
			$js_error_msg.='if(noCookie!==null){noCookieMsg="'.$js_no_cookie_msg.'"}';
		}
		if(!empty($error))
		{
			$error=preg_replace('/\r?\n/', '\\n', addslashes($error));
			$js_error_msg.='pageError="'.$error.'";';
		}
		$js_error_msg.='if((noCookieMsg!=\'\') || (pageError!=\'\')){
			alert(\''.$alert_title.'\', noCookieMsg + pageError)
		}';

		return $js_error_msg;
	} #==== End -- addJSErrorBox

	/**
	 * addHereClass
	 *
	 * Adds the "here" css class if we are already at the page that the link sends us to.
	 *
	 * @param 	$link					The link to check
	 * @param	$exact_match
	 * @param	$add_attribute			Adds 'class="'
	 * @access	public
	 */
	public static function addHereClass($link, $exact_match=FALSE, $add_attribute=TRUE)
	{
		# Create and empty variable to hold the potential class tag.
		$class='';
		# Create an empty variable to hold the "class" attribute.
		$attribute=(($add_attribute===TRUE) ? 'class="' : '');
		# Remove any Scheme Name (ie http://) from the passed link.
		$link=WebUtility::removeSchemeName($link);
		# Remove any index page from the passed link.
		$current_page=WebUtility::removeIndex(FULL_URL);
		# Remove www. from $link.
		$link=str_replace('www.', '', $link);
		# Remove www. from $current_page.
		$current_page=str_replace('www.', '', $current_page);
		# Check if this is an error page.
		if(strpos($current_page, WebUtility::removeSchemeName(ERROR_PAGE))!==FALSE)
		{
			# Explicitly set the error page as the current page without the query portion.
			$current_page=WebUtility::removeSchemeName(ERROR_PAGE);
		}
		# Remove any Query (ie GET data) from the passed link.
		$current_page=WebUtility::removePageQuery($current_page);
		# Are we looking for an exact match?
		if($exact_match===TRUE)
		{
			# Check if the url passed matches the current page exactly.
			if($current_page==$link)
			{
				# Set the class tag to the variable.
				$class=' '.$attribute.'here'.(($add_attribute===TRUE) ? '"' : '');
			}
		}
		# Check if the url passed is part of the current page.
		elseif(strpos($current_page, $link)!==FALSE)
		{
			# Set the class tag to the variable.
			$class=' '.$attribute.'here'.(($add_attribute===TRUE) ? '"' : '');
		}
		return $class;
	} #==== End -- addHereClass

	/**
	 * addJavaScript
	 *
	 * Loops through the array of JavaScript names set in the java_scripts data member and includes the php file. The required php file will live in the path associated with the JAVASCRIPTS constant in a folder with the current JavaScript name. The php file itself will also have the passed JavaScript name. Each php file will set a String value to the temporary variable $js. The values of $js will be concatenated together and returned as $display.
	 * Any errors thrown in the course of the loop will be caight and rethrown. Any errors thrown in the course of the getJavaScripts method will be caught and rethrown.
	 *
	 * @access	public
	 * @see			#setJavaScripts()
	 * @see			#getJavaScripts()
	 */
	public function addJavaScript($scripts=NULL)
	{
		try
		{
			if($scripts===NULL)
			{
				# Get the String of all passed php files. (will be in the format of - 'FolderPath/FileName' or 'FileName')
				$scripts=$this->getJavaScripts();
			}
			$display='';
			$js='';
			if(!empty($scripts))
			{
				foreach($scripts as $script_name)
				{
					# Create a variable to hold the name uf the sub-folder in the scripts folder.
					$sub_folder='';
					# Get the position of the last occurrence of the current directory separator in the current JavaScript name.
					$last_ds=strrchr($script_name, DS);
					# Check if the JavaScript name contains an instance of the value of the DS constant (a String).
					if($last_ds!==FALSE)
					{
						# Set the sub folder path the the sub_folder variable. (This will leave the directory separator off the end.)
						$sub_folder=substr($script_name, 0, $last_ds);
						# Check if the directory separator is empty (FALSE, NULL, 0, '', or ' ').
						if(!empty($sub_folder))
						{
							# Append the directory separator to the end of the path.
							$sub_folder.=DS;
							# Reset the script_name variable replacing the subfolder path with an empty String.
							$script_name=strtr($script_name, array($sub_folder=>''));
						}
						else
						{
							# Reset the sub_folder to an empty String to normailize the empty value.
							$sub_folder='';
						}
					}
					# Require the passed php file.
					require Utility::locateFile(JAVASCRIPTS.$sub_folder.$script_name.'.php');
					# Add the returned value of $js to the display variable (will be returned as the conclusion of the method).
					$display.=$js;
					# Reset the $js variable to an empty String to go through the foreach loop again.
					$js='';
				}
			}
			# Return the accumulated values from the passed php files.
			return $display;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- addJavaScript

	/**
	 * addStyle
	 *
	 * Puts the correct stylesheets into the header for the page beigh viewed.
	 *
	 * @access	public
	 */
	public function addStyle()
	{
		# Create an empty variable to hold the link tags.
		$links='';
		try
		{
			# Get the passed styles. They will be in an Array.
			$styles=$this->getStyle();
			# Loop through the styles.
			foreach($styles as $css)
			{
				# Get the Style sheet.
				$stylesheet_returned=preg_match('/^[^{]+/i', $css, $stylesheet);
				$stylesheet=$stylesheet[0];
				# Check if the stylesheet ends with a ".css".
				$has_extension=preg_match('/\.css$/i', $stylesheet);
				if($has_extension===0)
				{
					# Append the extension.
					$stylesheet.='.css';
				}
				# Get the stylesheet properties.
				$number_returned=preg_match_all('/({[^}]*})/i', $css, $value_array, PREG_SET_ORDER);

				# Check if there were stylesheet properties.
				if($number_returned>0)
				{
					# Loop through the properties.
					for($key=0;$key<$number_returned;$key++)
					{
						# The properties are in JSON. Decode them to convert them to objects.
						$current_properties=json_decode($value_array[$key][0]);
						# Set the device the CSS should be targeting. The default is "all".
						$device=((empty($current_properties->device)) ? 'all' : $current_properties->device);
						# Set the property to be measuring against.
						$property=((empty($current_properties->property)) ? '' : $current_properties->property);
						# Set the tag that is appended to the end of the css filename to make it locatable and unique.
						$tag=((empty($current_properties->tag)) ? '' : $current_properties->tag);
						# Set the value of the property to be measurting against.
						$value=((empty($current_properties->value)) ? '' : $current_properties->value);
						# Build the value of the media attribute for the link tag.
						$media=$device.((empty($property)) ? '' : ' and ('.$property.':'.$value.')');
						# Build the name of the stylesheet. If there is no tag, use the passed name without appending anything.
						$media_css=str_ireplace('.css', ((empty($tag)) ? '' : '.').$tag.'.css', $stylesheet);
						# Create the link tag and concatenate it to the links variable.
						$links.='<link rel="stylesheet" type="text/css" media="'.$media.'" href="'.$media_css.'">';
					}
				}
				else
				{
					# Build the link tag with no specific target or properties and concatenate it to the links variable.
					$links.='<link rel="stylesheet" type="text/css" href="'.$stylesheet.'">';
				}
			}
			# Return the built link tag(s).
			return $links;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- addStyle

	/**
	 * addIEStyle
	 *
	 * Inserts proper Internet Explorer label into CSS filename (ie turns 'path/style.css' to 'path/'.$ie_version.'.style.css').
	 *
	 * @param 	string		$ie_version		(The versions of Internet Explorer(comma separated values). Acceptable values are:
	 *																	"ie", "ie11", "ie10", "ie9", "ie8", "ie7", "ie6", "ie5mac"
	 * @access	public
	 */
	public function addIEStyle($ie_versions)
	{
		try
		{
			# Remove any white space from the passed string (allows developers to be lazy).
			$ie_versions=preg_replace('/\s/', '', $ie_versions);
			# Explode ie versions into an array.
			$ie_versions=explode(',', $ie_versions);
			# Create an empty array to hold
			$ie_style=array('ie'=>'', 'ie11'=>'', 'ie10'=>'', 'ie9'=>'', 'ie8'=>'', 'ie7'=>'', 'ie6'=>'', 'ie5mac'=>'');
			# Get the set styles.
			$styles=$this->getStyle();
			# Create an empty variable to hold the link markup display.
			$display_ie_style='';
			# loop through the stylesheets.
			foreach($styles as $css)
			{
				# Get the Style sheet.
				$stylesheet_returned=preg_match('/^[^{]+/i', $css, $stylesheet);
				$stylesheet=$stylesheet[0];
				# Check if the stylesheet ends with a ".css".
				$has_extension=preg_match('/\.css$/i', $stylesheet);
				if($has_extension===0)
				{
					# Append the extension.
					$stylesheet.='.css';
				}
				# Get the stylesheet properties.
				$number_returned=preg_match_all('/({[^}]*})/i', $css, $value_array, PREG_SET_ORDER);

				# Get the position of the last backslash.
				$pos=strrpos($stylesheet, '/');
				# Loop through the ie versions array.
				foreach($ie_versions as $ie_version)
				{
					# Extract the filename.
					$filename=substr($stylesheet, $pos+1);
					# Extract the filepath.
					$filepath=substr($stylesheet, 0, $pos+1);
					# Rebuild the stylesheet name with the ie version prepended to the filename.
					$stylesheet_name=$filepath.$ie_version.'.'.$filename;

					# Check if the ie version is the VERY OLD IE5 for Mac.
					if($ie_version == 'ie5mac')
					{
						# Create the appropriate tag to pull in the stylesheet ONLY if the browser is IE5 for Mac.
						$ie_style[$ie_version].='@import("'.$stylesheet_name.'");';
					}
					else
					{
						# Check if there were stylesheet properties.
						if($number_returned>0)
						{
							# Loop through the properties.
							for($key=0;$key<$number_returned;$key++)
							{
								# The properties are in JSON. Decode them to convert them to objects.
								$current_properties=json_decode($value_array[$key][0]);
								# Set the device the CSS should be targeting. The default is "all".
								$device=((empty($current_properties->device)) ? 'all' : $current_properties->device);
								# Set the property to be measuring against.
								$property=((empty($current_properties->property)) ? '' : $current_properties->property);
								# Set the tag that is appended to the end of the css filename to make it locatable and unique.
								$tag=((empty($current_properties->tag)) ? '' : $current_properties->tag);
								# Set the value of the property to be measurting against.
								$value=((empty($current_properties->value)) ? '' : $current_properties->value);
								# Build the value of the media attribute for the link tag.
								$media=$device.((empty($property)) ? '' : ' and ('.$property.':'.$value.')');
								# Build the name of the stylesheet. If there is no tag, use the passed name without appending anything.
								$media_css=str_ireplace('.css', ((empty($tag)) ? '' : '.').$tag.'.css', $stylesheet_name);
								# Create the link tag and concatenate it to the links variable.
								$ie_style[$ie_version].='<link rel="stylesheet" type="text/css" media="'.$media.'" href="'.$media_css.'">';
							}
						}
						else
						{
							# Build the link tag with no specific target or properties and concatenate it to the links variable.
							$ie_style[$ie_version].='<link rel="stylesheet" type="text/css" href="'.$stylesheet_name.'">';
						}
					}
				}
			}
			# Build the IE conditionals.
			$display_ie_style.=((!empty($ie_style['ie'])) ? '<!--[if IE]>'.$ie_style['ie'].'<![endif]-->' : '');
			$display_ie_style.=((!empty($ie_style['ie11'])) ? '<!--[if IE 11]>'.$ie_style['ie11'].'<![endif]-->' : '');
			$display_ie_style.=((!empty($ie_style['ie10'])) ? '<!--[if IE 10]>'.$ie_style['ie10'].'<![endif]-->' : '');
			$display_ie_style.=((!empty($ie_style['ie9'])) ? '<!--[if IE 9]>'.$ie_style['ie9'].'<![endif]-->' : '');
			$display_ie_style.=((!empty($ie_style['ie8'])) ? '<!--[if IE 8]>'.$ie_style['ie8'].'<![endif]-->' : '');
			$display_ie_style.=((!empty($ie_style['ie7'])) ? '<!--[if IE 7]>'.$ie_style['ie7'].'<![endif]-->' : '');
			$display_ie_style.=((!empty($ie_style['ie6'])) ? '<!--[if IE 6]>'.$ie_style['ie6'].'<![endif]-->' : '');
			$display_ie_style.=((!empty($ie_style['ie5mac'])) ?
					'<style type="text/css">'."\n".
						'/*\*//*/'."\n".
							$ie_style['ie5mac']."\n".
						'/**/'."\n".
					'</style>'
				: '');
			# Return the built markup.
			return $display_ie_style;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- addIEStyle

	/***
	 * findDomainFolder
	 *
	 * Check if URL is a sub-domain and, based off that information, returns the views folder for that subdomain.
	 *
	 * @access	public
	 */
	public static function findDomainFolder()
	{
		# Find the sub-domain and set it.
		if(SUB_DOMAIN=="www" || SUB_DOMAIN=="")
		{
			$folder='';
		}
		else
		{
			$folder=SUB_DOMAIN.DS;
		}
		return $folder;
	} #==== End -- findDomainFolder

	/**
	 * getInstance
	 *
	 * Gets the singleton instance of this class.
	 *
	 * @access	public
	 */
	public static function getInstance()
	{
		if(!self::$document)
		{
			self::$document=new Document();
		}
		return self::$document;
	} #==== End -- getInstance

	/**
	 * pageExists
	 *
	 * Checks if an URL really leads to a valid page (as opposed to generating “404 Not Found” or some other kind of error)
	 *
	 * @param		string 	$url	  	(The URL to check.)
	 * @access	public
	 */
	public function pageExists($url)
	{
		$parts=parse_url($url);
		if(!$parts) return FALSE; /* the URL was seriously wrong */

		$curl=curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);

		/* set the user agent - might help, doesn't hurt */
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		/* try to follow redirects */
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

		/* timeout after the specified number of seconds. assuming that this script runs
			on a server, 20 seconds should be plenty of time to verify a valid URL.  */
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);

		/* don't download the page, just the header (much faster in this case) */
		curl_setopt($curl, CURLOPT_NOBODY, TRUE);
		curl_setopt($curl, CURLOPT_HEADER, TRUE);

		/* handle HTTPS links */
		if($parts['scheme']=='https')
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		}

		$response = curl_exec($curl);
		curl_close($curl);

		/*  get the status code from HTTP headers */
		if(preg_match('/HTTP\/1\.\d+\s+(\d+)/', $response, $matches))
		{
			$code=intval($matches[1]);
		}
		else
		{
			return FALSE;
		}

		/* see if code indicates success */
		return (($code>=200) && ($code<400));
	} #==== End -- pageExists

	/**
	 * redirect
	 *
	 * Try PHP header redirect, then Java redirect, then try http redirect.
	 *
	 * @param		$url 		(The url to redirect to.)
	 * @param		$delay 	(The delay in seconds before redirecting.)
	 * @access	public
	 */
	public function redirect($url, $delay=0)
	{
		# Check if the URL is empty.
		if(!empty($url))
		{
			# Parse the passed url.
			$url_parsed=parse_url($url);
			# Check if the domain is this site.
			if(isset($url_parsed['host']) && ($url_parsed['host']===FULL_DOMAIN))
			{
				$url=$url_parsed['path'].$url_parsed['query'];
			}
			# Check if headers have already been sent.
			if(headers_sent()===FALSE)
			{
				# Do a PHP redirect.
				header('Refresh: '.$delay.'; url='.$url);
			}
			# Do a Javascript redirect.
			$script='<script type="text/javascript">';
			$script.='window.setTimeout(\'window.location="'.$url.'"; \','.($delay*1000).');';
			$script.='</script>';
			# Do an HTML redirect.
			$script.='<noscript>';
			$script.='<meta http-equiv="refresh" content="'.$delay.';url='.$url.'" />';
			$script.='</noscript>';
			echo $script;
			die;
		}
		return FALSE;
	} #==== End -- Redirect

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
		return WebUtility::removeGetQuery($url);
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
		return WebUtility::removePageQuery($url);
	} #==== End -- removePageQuery

	/**
	 * removeIndex
	 *
	 * Removes "index.php" from the passed URL.
	 *
	 * @param 	$url 		(The URL to check.)
	 * @access	public
	 */
	public static function removeIndex($url)
	{
		return WebUtility::removeIndex($url);
	} #==== End -- removeIndex

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
		return WebUtility::removeSchemeName($url);
	} #==== End -- removeSchemeName

	/**
	 * sendEmail
	 *
	 * Handles all emailing from one place. A wrapper method for sendMail of the Email class.
	 *
	 * @access	public
	 * @param		string
	 * @return	bool 		TRUE/FALSE
	 */
	public function sendEmail($subject, $to, $body, $reply_to=SMTP_FROM)
	{
		try
		{
			# Get the Email class.
			require_once Utility::locateFile(MODULES.'Email'.DS.'Email.php');
			# Instantiate a new Email object.
			$email=new Email();
			# Send the mail.
			$sent=$email->sendEmail($subject, $to, $body, $reply_to);
			return $sent;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	} #==== End -- sendEmail

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * closeTags
	 *
	 * Closes any tags that are not-closed.
	 *
	 * @access	private
	 * @param		string		$html
	 * @return	boolean		TRUE/FALSE
	 */
	private function closeTags($html)
	{
		# Strip any mangled tags off the end
		$html=preg_replace('#]*$#', ' ', $html);
		# Put all opened tags into an array.
		preg_match_all('#<([a-z]+)(?<!br)( [^/]*)?(?!/)>#iU', $html, $result);
		$openedtags=$result[1];
		$openedtags=array_diff($openedtags, array('img', 'hr', 'br'));
		$openedtags=array_values($openedtags);

		# Put all closed tags into an array.
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags=$result[1];
		$len_opened=count($openedtags);

		# All tags are closed.
		if(count($closedtags) == $len_opened)
		{
			return $html;
		}
		$openedtags=array_reverse($openedtags);
		# close tags
		for($i=0; $i<$len_opened; $i++)
		{
			if(!in_array($openedtags[$i], $closedtags))
			{
				$html.='</'.$openedtags[$i].'>';
			}
			else
			{
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
		return $html;
	} #==== End -- closeTags

	/*** End public methods ***/

} # End Document class.