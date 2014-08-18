<?php

# Get the Utility Class.
require_once MODULES.'Utility'.DS.'Utility.php';

function myErrorHandler($code, $msg, $file, $line)
{
	$error=new ExceptionHandler($code, $msg, $file, $line);
	if($error===TRUE)
	{
		# Check if the link is to an index page.
		if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], ERROR_PAGE)===FALSE)
		{
			# Don't execute PHP internal error handler
			return TRUE;
		}
		else
		{
			#Execute PHP internal error handler
			return FALSE;
		}
	}
}

/**
* ExceptionHandler
*
* Custom error handler code. It must be set by calling set_error_handler('myErrorHandler');. When an error occurs, this function is automatically called, and passed four arguments: the error code and message, the name of the script that generated the error, and the line number of the statement that generated the error. The function then becomes responsible for managing the error.
*
* @param	none
* @access	public
*/

class ExceptionHandler
{

	/*** data members ***/

	protected $message = 'Unknown exception';     // Exception message
	private   $string;                            // Unknown
	protected $code    = 0;                       // User-defined exception code
	protected $file;                              // Source filename of exception
	protected $line;                              // Source line of exception
	private   $trace;                             // Unknown

	/*** End data members ***/



	/*** magic methods ***/

	public function __construct($code=NULL, $msg=NULL, $file=NULL, $line=NULL, $context=array('nothing'))
	{
		if($code===NULL)
		{
			return;
		}
		else
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();

			$send_an_email=TRUE;

			$time=MONTH_DD_YEAR_TIME;

			$header='<h3>Error details for debugging:</h3><br />'."\n";
			$body='<span style="color:red;">';
			$body2="The error originated at: <span style=\"color:blue;\">".FULL_URL."</span><br />\n<br />\n";
			$body2.="The server is running: <span style=\"color:blue;\">PHP ".PHP_VERSION." (".PHP_OS.")</span><br />\n<br />\n";
			$body2.=((isset($_SERVER['HTTP_REFERER'])) ? "Refered from: <span style=\"color:blue;\">".$_SERVER['HTTP_REFERER']."</span><br />\n" : '');
			$body2.=((isset($_SERVER['HTTP_USER_AGENT'])) ? "User Agent: <span style=\"color:blue;\">".$_SERVER['HTTP_USER_AGENT']."</span><br />\n<br />\n" : '');
			$body2.="Here's the context:<br />\n<br />\n";
			$body2.=$this->processContext($context, $body2);
			$redirect=FALSE;

			switch($code)
			{
				case E_ERROR: # 1
					# email error to admin
					$body.='E_USER_ERROR ['.$code."]: ";
					$body.="\"<span style=\"color:red;\">Had to abort!\"</span><br />\n<br />\n";
					$body.=$msg."<br />\n<br />\n";
					$body.="Fatal error at: <br />\n";
					$body.='<span style="color:green;">'.$file."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$line."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					$redirect=TRUE;
					break;

				case E_WARNING: # 2
					# email error to admin
					$body.='E_WARNING ['.$code."]: ";
					$body.="\"<span style=\"color:red;\">Script was not halted.\"</span><br />\n<br />\n";
					$body.=$msg."<br />\n<br />\n";
					$body.="Non-fatal run-time error at: <br />\n";
					$body.='<span style="color:green;">'.$file."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$line."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				case E_PARSE: # 4
					# email error to admin
					$body.='E_PARSE ['.$code."]: ";
					$body.=$msg."<br />\n<br />\n";
					$body.="Parse error at: <br />\n";
					$body.='<span style="color:green;">'.$file."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$line."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					$redirect=TRUE;
					break;

				case E_NOTICE: # 8
					# email error to admin
					$body.='E_NOTICE ['.$code."]: ";
					$body.="\"<span style=\"color:red;\">The script encountered something that could indicate an error, but could also happen in the normal course of running a script.\"</span><br />\n<br />\n";
					$body.=$msg."<br />\n<br />\n";
					$body.="Run-time notice for: <br />\n";
					$body.='<span style="color:green;">'.$file."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$line."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				case E_STRICT: # 2048
					# email error to admin
					$body.='E_STRICT ['.$code."]: ";
					$body.="\"<span style=\"color:red;\">PHP suggests changes to your code which will ensure the best interoperability and forward compatibility of your code.\"</span><br />\n<br />\n";
					$body.=$msg."<br />\n<br />\n";
					$body.="Little nit-picky error at: <br />\n";
					$body.='<span style="color:green;">'.$file."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$line."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					$send_an_email=FALSE;
					break;

				case E_RECOVERABLE_ERROR: # 4096
					# email error to admin
					$body.='E_RECOVERABLE_ERROR ['.$code."]: ";
					$body.="\"<span style=\"color:red;\">A probably dangerous error occured, but did not leave the Engine in an unstable state.\"</span><br />\n<br />\n";
					$body.=$msg."<br />\n<br />\n";
					$body.="Catchable fatal error at: <br />\n";
					$body.='<span style="color:green;">'.$file."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$line."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					$redirect=TRUE;
					break;

				# only in php5.3+
				case (defined(E_DEPRECATED) && ($code==E_DEPRECATED)): # 8192
					# email error to admin
					$body.='E_DEPRECATED ['.$code."]: ";
					$body.="\"<span style=\"color:red;\">PHP suggests changing this code as it will not work in future versions.\"</span><br />\n<br />\n";
					$body.=$msg."<br />\n<br />\n";
					$body.="Run-time notice for</span>: <br />\n";
					$body.='<span style="color:green;">'.$file."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$line."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					$send_an_email=FALSE;
					break;

				default:
					# email error to admin
					$body.='Error code ['.$code."]: ";
					$body.=$msg."<br />\n<br />\n";
					$body.="Unknown error type at</span>: <br />\n";
					$body.='<span style="color:green;">'.$file."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$line."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;
			}

			if(RUN_ON_DEVELOPMENT===FALSE)
			{
				if($send_an_email===TRUE)
				{
					$doc->sendEmail("Web site error", ADMIN_EMAIL, $body);
				}
			}
			else
			{
				if(defined('WP_FOLDER') && strpos(HERE, WP_FOLDER)!==FALSE)
				{
						return;
				}
				$doc->redirect(ERROR_PAGE.'?code='.$code.'&msg='.$msg.((isset($_SERVER['HTTP_REFERER'])) ? '&referer='.Utility::removeIndex($_SERVER['HTTP_REFERER']) : '').'&file='.Utility::removeIndex($file).((isset($_SERVER['HTTP_USER_AGENT'])) ? '&agent='.$_SERVER['HTTP_USER_AGENT'] : '').'&file='.Utility::removeIndex($file).'&line='.$line.'&time='.$time.'&url='.Utility::removeIndex(FULL_URL).'&context='.urlencode(serialize($context)));
				# Don't execute PHP internal error handler
				return TRUE;
			}

			if($redirect===TRUE)
			{
				$doc->redirect(ERROR_PAGE);
				# Don't execute PHP internal error handler
				return TRUE;
			}
		}
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** public methods ***/

	/**
	 * captureError
	 *
	 * Captures an error sent via GET Data, converts it to html and sets it to $doc-setError()
	 *
	 * @access	public
	 */
	public function captureError()
	{
		if(isset($_GET['code']))
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();

			$time=MONTH_DD_YEAR_TIME;

			$header='<h3>Error details for debugging:</h3><br />'."\n";
			$body='<span style="color:red;">';
			$body2="<hr style=\"background:red;margin:4px 0;height:1px\" />The error originated at: <span style=\"color:blue;\">".$_GET['file']."</span><br />\n<br />\n";
			$body2.="The server is running: <span style=\"color:blue;\">PHP ".PHP_VERSION." (".PHP_OS.")</span><br />\n<br />\n";
			$body2.=((isset($_GET['referer'])) ? "Refered from: <span style=\"color:blue;\">".$_GET['referer']."</span><br />\n" : '');
			$body2.=((isset($_GET['agent'])) ? "User Agent: <span style=\"color:blue;\">".$_GET['agent']."</span><br />\n<br />\n" : '');
			$body2.="Here's the context:<br />\n<br />\n";
			$context=unserialize(urldecode($_GET['context']));
			$body2.=$this->processContext($context, $body2);
			switch($_GET['code'])
			{
				case NULL:
					return;

				case E_ERROR: # 1
					# email error to admin
					$body.='E_USER_ERROR ['.$_GET['code']."]: ";
					$body.="\"<span style=\"color:red;\">Had to abort!\"</span><br />\n<br />\n";
					$body.=$_GET['msg']."<br />\n<br />\n";
					$body.='</span>';
					$body.="Fatal error at: <br />\n";
					$body.='<span style="color:green;">'.$_GET['file']."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$_GET['line']."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				case E_WARNING: # 2
					# email error to admin
					$body.='E_WARNING ['.$_GET['code']."]: ";
					$body.="\"<span style=\"color:red;\">Script was not halted.\"</span><br />\n<br />\n";
					$body.=$_GET['msg']."<br />\n<br />\n";
					$body.="Non-fatal run-time error at: <br />\n";
					$body.='<span style="color:green;">'.$_GET['file']."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$_GET['line']."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				case E_PARSE: # 4
					# email error to admin
					$body.='E_PARSE ['.$_GET['code']."]: ";
					$body.=$_GET['msg']."<br />\n<br />\n";
					$body.="Parse error at: <br />\n";
					$body.='<span style="color:green;">'.$_GET['file']."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$_GET['line']."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				case E_NOTICE: # 8
					# email error to admin
					$body.='E_NOTICE ['.$_GET['code']."]: ";
					$body.="\"<span style=\"color:red;\">The script encountered something that could indicate an error, but could also happen in the normal course of running a script.\"</span><br />\n<br />\n";
					$body.=$_GET['msg']."<br />\n<br />\n";
					$body.="Run-time notice for: <br />\n";
					$body.='<span style="color:green;">'.$_GET['file']."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$_GET['line']."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				case E_STRICT: # 2048
					# email error to admin
					$body.='E_STRICT ['.$_GET['code']."]: ";
					$body.="\"<span style=\"color:red;\">PHP suggests changes to your code which will ensure the best interoperability and forward compatibility of your code.\"</span><br />\n<br />\n";
					$body.=$_GET['msg']."<br />\n<br />\n";
					$body.="Little nit-picky error at: <br />\n";
					$body.='<span style="color:green;">'.$_GET['file']."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$_GET['line']."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				case E_RECOVERABLE_ERROR: # 4096
					# email error to admin
					$body.='E_RECOVERABLE_ERROR ['.$_GET['code']."]: ";
					$body.="\"<span style=\"color:red;\">A probably dangerous error occured, but did not leave the Engine in an unstable state.\"</span><br />\n<br />\n";
					$body.=$_GET['msg']."<br />\n<br />\n";
					$body.="Catchable fatal error at: <br />\n";
					$body.='<span style="color:green;">'.$_GET['file']."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$_GET['line']."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				# only in php5.3+
				case E_DEPRECATED: # 8192
					# email error to admin
					$body.='E_DEPRECATED ['.$_GET['code']."]: ";
					$body.="\"<span style=\"color:red;\">PHP suggests changing this code as it will not work in future versions.\"</span><br />\n<br />\n";
					$body.=$_GET['msg']."<br />\n<br />\n";
					$body.="Run-time notice for</span>: <br />\n";
					$body.='<span style="color:green;">'.$_GET['file']."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$_GET['line']."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;

				default:
					# email error to admin
					$body.='Error code ['.$_GET['code']."]: ";
					$body.="\"<span style=\"color:red;\">Hmmm...\"</span><br />\n<br />\n";
					$body.=$_GET['msg']."<br />\n<br />\n";
					$body.="Unknown error type at</span>: <br />\n";
					$body.='<span style="color:green;">'.$_GET['file']."</span><br />\n<br />\n";
					$body.="In line: <span style=\"color:green;\">".$_GET['line']."</span><br />\n<br />\n";
					$body.='Timed at: <span style="color:green;">'.$time."</span><br />\n<br />\n";
					$body=$header.$body.$body2;
					break;
			}
			$doc->setError($body);
		}
	} #==== End -- captureError

	/*** End public methods ***/



	/*** private methods ***/

	/**
	 * processContext
	 *
	 * Loops through the context. Returns a concatenated string.
	 *
	 * @access	private
	 */
	private function processContext($context, $string='')
	{
		if(is_array($context))
		{
			foreach($context as $key=>$value)
			{
				if(!is_array($context[$key]))
				{
					$string.=$key.' => <span style="color:green;">'.$value.'</span><br />';
				}
				else
				{
					$string.=$this->processContext($value, $string);
				}
			}
		}
		else
		{
			$string.='<span style="color:green;">'.$context.'</span><br />';
		}
		$string.='<hr style="background:red;margin:4px 0;height:1px" />';
		return $string;
	} #==== End -- processContext

	/*** End private methods ***/


} #=== End ExceptionHandler class.