<?php /* framework/application/modules/ExceptionHandler/ExceptionHandler.php */

# Get the Utility Class.
require_once UTILITY_CLASS;

/**
 * @param $code
 * @param $msg
 * @param $file
 * @param $line
 * @return bool
 */
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
			# Execute PHP internal error handler
			return FALSE;
		}
	}

	return FALSE;
}

/**
 * Class ExceptionHandler
 *
 * Custom error handler code. It must be set by calling set_error_handler('myErrorHandler');.
 *
 * When an error occurs, this function is automatically called, and passed four arguments:
 *        the error code and message,
 *        the name of the script that generated the error,
 *        and the line number of the statement that generated the error.
 * The function then becomes responsible for managing the error.
 *
 * @param none
 */
class ExceptionHandler
{
	/*** data members ***/

	protected $code=0;                          # User-defined exception code
	protected $file;                            # Source filename of exception
	protected $line;                            # Source line of exception
	protected $message='Unknown exception';     # Exception message
	private $string;                            # Unknown
	private $trace;                             # Unknown

	/*** End -- data members ***/



	/*** magic methods ***/

	/**
	 * ExceptionHandler constructor.
	 *
	 * @param null $code
	 * @param null $msg
	 * @param null $file
	 * @param null $line
	 * @param array $context
	 */
	public function __construct($code=NULL, $msg=NULL, $file=NULL, $line=NULL, $context=array())
	{
		if($code!==NULL OR isset($_GET['error']))
		{
			# Set the Document instance to a variable.
			$doc=Document::getInstance();
			$captured_error=$this->captureError($code, $msg, $file, $line, $context);
			$body=$captured_error['body'];
			$redirect=$captured_error['redirect'];
			$send_an_email=$captured_error['send_an_email'];
			$time=MONTH_DD_YEAR_TIME;
			if(isset($_GET['error']))
			{
				# Set the Document instance to a variable.
				$doc=Document::getInstance();
				$doc->setError($body);
			}

			if(RUN_ON_DEVELOPMENT===FALSE)
			{
				if($send_an_email===TRUE)
				{
					$doc->sendEmail('Web site error', ADMIN_EMAIL, $body);
				}
			}
			else
			{
				if(defined('WP_FOLDER') && strpos(HERE, WP_FOLDER)!==FALSE)
				{
					return FALSE;
				}
				if(!isset($_GET['error']))
				{
					$doc->redirect(ERROR_PAGE.'?error&code='.$code.'&msg='.$msg.((isset($_SERVER['HTTP_REFERER'])) ? '&referer='.Utility::removeIndex($_SERVER['HTTP_REFERER']) : '').'&file='.Utility::removeIndex($file).((isset($_SERVER['HTTP_USER_AGENT'])) ? '&agent='.$_SERVER['HTTP_USER_AGENT'] : '').'&file='.Utility::removeIndex($file).'&line='.$line.'&time='.$time.'&url='.Utility::removeIndex(FULL_URL).'&context='.urlencode(serialize($context)));

					# Don't execute PHP internal error handler
					return TRUE;
				}
			}

			if($redirect===TRUE)
			{
				$doc->redirect(ERROR_PAGE);

				# Don't execute PHP internal error handler
				return TRUE;
			}
		}

		return FALSE;
	}

	/*** End magic methods ***/



	/*** public methods ***/

	/**
	 * Captures an error sent via GET Data, converts it to html and sets it to $doc-setError()
	 *
	 * @param $code
	 * @param $msg
	 * @param $file
	 * @param $line
	 * @param $context
	 * @return array
	 */
	public function captureError($code, $msg, $file, $line, $context)
	{
		$agent=((isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '');
		$context=((!empty($context)) ? $context : '');
		$redirect=FALSE;
		$referer=((isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '');
		$send_an_email=TRUE;
		//$set_error=FALSE;
		$time=MONTH_DD_YEAR_TIME;

		if(isset($_GET['error']))
		{
			$agent=((isset($_GET['agent'])) ? $_GET['agent'] : $agent);
			$code=((isset($_GET['code'])) ? $_GET['code'] : $code);
			$context=((isset($_GET['context'])) ? unserialize(urldecode($_GET['context'])) : $context);
			$file=((isset($_GET['file'])) ? $_GET['file'] : $file);
			$line=((isset($_GET['line'])) ? $_GET['line'] : $line);
			$msg=((isset($_GET['msg'])) ? $_GET['msg'] : $msg);
			$referer=((isset($_GET['referer'])) ? $_GET['referer'] : $referer);
			$send_an_email=FALSE;
		}

		$header='<h3>Error details for debugging:</h3><br />';
		$body='<span style="color:red;">';
		$body2='<hr style=\"background:red;margin:4px 0;height:1px\" />The error originated at: <span style=\"color:blue;\">'.$file.'</span><br /><br />';
		$body2.='The server is running: <span style=\"color:blue;\">PHP '.PHP_VERSION.' ('.PHP_OS.')</span><br /><br />';
		$body2.=((!empty($referer)) ? 'Refered from: <span style=\"color:blue;\">'.$referer.'</span><br />' : '');
		$body2.=((!empty($agent)) ? 'User Agent: <span style=\"color:blue;\">'.$agent.'</span><br /><br />' : '');
		if(!empty($context))
		{
			$body2.="Here's the context:<br /><br />";
			$body2.=$this->processContext($context, '');
		}

		switch($code)
		{
			case E_ERROR: # 1
				# email error to admin
				$body.='E_USER_ERROR ['.$code.']: ';
				$body.='"<span style="color:red;">Had to abort!"</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.='Fatal error at: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style=\"color:green;\">'.$line.'</span><br /><br />';
				$body.='Timed at: <span style="color:green;">'.$time.'</span><br /><br />';
				$body=$header.$body.$body2;
				$redirect=((isset($_GET['error'])) ? FALSE : TRUE);
				break;

			case E_WARNING: # 2
				# email error to admin
				$body.='E_WARNING ['.$code.']: ';
				$body.='"<span style="color:red;">Script was not halted."</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.="Non-fatal run-time error at: <br />";
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body.='Timed at: <span style="color:green;">'.$time.'</span><br /><br />';
				$body=$header.$body.$body2;
				break;

			case E_PARSE: # 4
				# email error to admin
				$body.='E_PARSE ['.$code.']: ';
				$body.=$msg.'<br /><br />';
				$body.='Parse error at: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body.='Timed at: <span style="color:green;">'.$time.'</span><br /><br />';
				$body=$header.$body.$body2;
				$redirect=((isset($_GET['error'])) ? FALSE : TRUE);
				break;

			case E_NOTICE: # 8
				# email error to admin
				$body.='E_NOTICE ['.$code.']: ';
				$body.='"<span style="color:red;">The script encountered something that could indicate an error, but could also happen in the normal course of running a script."</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.='Run-time notice for: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body.='Timed at: <span style="color:green;">'.$time.'</span><br /><br />';
				$body=$header.$body.$body2;
				break;

			case E_STRICT: # 2048
				# email error to admin
				$body.='E_STRICT ['.$code.']: ';
				$body.='"<span style="color:red;">PHP suggests changes to your code which will ensure the best interoperability and forward compatibility of your code."</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.='Little nit-picky error at: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body.='Timed at: <span style="color:green;">'.$time.'</span><br /><br />';
				$body=$header.$body.$body2;
				$send_an_email=FALSE;
				break;

			case E_RECOVERABLE_ERROR: # 4096
				# email error to admin
				$body.='E_RECOVERABLE_ERROR ['.$code.']: ';
				$body.='"<span style="color:red;">A probably dangerous error occured, but did not leave the Engine in an unstable state."</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.='Catchable fatal error at: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body.='Timed at: <span style="color:green;">'.$time.'</span><br /><br />';
				$body=$header.$body.$body2;
				$redirect=((isset($_GET['error'])) ? FALSE : TRUE);
				break;

			# only in php5.3+
			case (defined(E_DEPRECATED) && ($code==E_DEPRECATED)): # 8192
				# email error to admin
				$body.='E_DEPRECATED ['.$code.']: ';
				$body.='"<span style="color:red;">PHP suggests changing this code as it will not work in future versions."</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.='Run-time notice for</span>: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body.='Timed at: <span style="color:green;">'.$time.'</span><br /><br />';
				$body=$header.$body.$body2;
				$send_an_email=FALSE;
				break;

			default:
				# email error to admin
				$body.='Error code ['.$code.']: ';
				$body.='"<span style="color:red;">Hmmm..."</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.='Unknown error type at</span>: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body.='Timed at: <span style="color:green;">'.$time.'</span><br /><br />';
				$body=$header.$body.$body2;
				break;
		}

		return ['body'=>$body, 'redirect'=>$redirect, 'send_an_email'=>$send_an_email];
	}

	/*** End public methods ***/



	/*** protected methods ***/

	/**
	 * Loops through the context. Returns a concatenated string.
	 *
	 * @param $context
	 * @param string $string
	 * @return string
	 */
	protected function processContext($context, $string='')
	{
		$separator=FALSE;
		if(is_array($context) && !empty($context))
		{
			foreach($context as $key=>$value)
			{
				if(!is_array($context[$key]) && !empty($value))
				{
					$string.=$key.' => <span style="color:green;">'.$value.'</span><br />';
					$separator=TRUE;
				}
				elseif(is_array($context[$key]) && !empty($value))
				{
					$string.=$this->processContext($value, $string);
				}
			}
		}
		else
		{
			$string.='<span style="color:green;">'.((empty($context)) ? 'No context available.</span>' : $context).'</span><br />';
			$separator=TRUE;
		}
		$string.=(($separator===TRUE) ? '<hr style="background:red;margin:4px 0;height:1px" />' : '');

		return $string;
	}

	/*** End protected methods ***/
}