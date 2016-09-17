<?php /* framework/application/modules/ExceptionHandler/CommandLineException.php */

# Get the ExceptionHandler class.
require_once Utility::locateFile(MODULES.'ExceptionHandler'.DS.'ExceptionHandler.php');

class CommandLineException extends ExceptionHandler
{
	/*** magic methods ***/

	/**
	 * CommandLineException constructor.
	 *
	 * @param int $code
	 * @param null $msg
	 * @param null $file
	 * @param null $line
	 * @param array $context
	 */
	public function __construct($code=0, $msg=NULL, $file=NULL, $line=NULL, $context=array())
	{
		if($code!==NULL)
		{
			$captured_error=$this->captureError($code, $msg, $file, $line, $context);
			$body=$captured_error['body'];
			$send_an_email=$captured_error['send_an_email'];

			# Do not email if this is being run on development site.
			if(RUN_ON_DEVELOPMENT===FALSE)
			{
				if($send_an_email===TRUE)
				{
					# Get the Email class.
					require_once Utility::locateFile(MODULES.'Email'.DS.'Email.php');
					# Instantiate a new Email object.
					$email_obj=new Email();
					$email_obj->sendEmail('Web site error', ADMIN_EMAIL, $body);
				}
			}

			# Get the Logger class.
			require_once Utility::locateFile(MODULES.'Logger'.DS.'Logger.php');
			# Create a new Logger object, and set the log file to use.
			$logger_obj=new Logger('cron.log');
			# Write exec() output to log file.
			$logger_obj->writeLogFile(strip_tags(str_replace(array('<br>', '<br />', '<br/>'), PHP_EOL, $body)));
			# Close log file.
			$logger_obj->closeLogFile();
		}
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
		$context=((!empty($context)) ? $context : '');
		$send_an_email=TRUE;
		//$set_error=FALSE;

		$header='<h3>Error details for debugging:</h3><br />';
		$body='<span style="color:red;">';
		$body2='<hr style=\"background:red;margin:4px 0;height:1px\" />The error originated at: <span style=\"color:blue;\">'.$file.'</span><br /><br />';
		$body2.='The server is running: <span style=\"color:blue;\">PHP '.PHP_VERSION.' ('.PHP_OS.')</span><br /><br />';
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
				$body=$header.$body.$body2;
				break;

			case E_WARNING: # 2
				# email error to admin
				$body.='E_WARNING ['.$code.']: ';
				$body.='"<span style="color:red;">Script was not halted."</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.="Non-fatal run-time error at: <br />";
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body=$header.$body.$body2;
				break;

			case E_PARSE: # 4
				# email error to admin
				$body.='E_PARSE ['.$code.']: ';
				$body.=$msg.'<br /><br />';
				$body.='Parse error at: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
				$body=$header.$body.$body2;
				break;

			case E_NOTICE: # 8
				# email error to admin
				$body.='E_NOTICE ['.$code.']: ';
				$body.='"<span style="color:red;">The script encountered something that could indicate an error, but could also happen in the normal course of running a script."</span><br /><br />';
				$body.=$msg.'<br /><br />';
				$body.='Run-time notice for: <br />';
				$body.='<span style="color:green;">'.$file.'</span><br /><br />';
				$body.='In line: <span style="color:green;">'.$line.'</span><br /><br />';
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
				$body=$header.$body.$body2;
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
				$body=$header.$body.$body2;
				break;
		}

		return ['body'=>$body, 'send_an_email'=>$send_an_email];
	}

	/*** End public methods ***/
}