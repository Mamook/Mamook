<?php

# Get the ExceptionInterface
require_once Utility::locateFile(MODULES.'ExceptionHandler'.DS.'ExceptionInterface.php');

abstract class CustomException extends Exception implements ExceptionInterface
{
	/*** data members ***/

	protected $code=0;							# User-defined exception code
	protected $file;							# Source filename of exception
	protected $line;							# Source line of exception
	protected $message='Unknown exception';		# Exception message
	private $string;							# Unknown
	private $trace;

	/*** End data members ***/



	/*** magic methods ***/

	public function __construct($message=NULL, $code=0)
	{
			if(!$message)
			{
				throw new $this('Unknown '.get_class($this));
			}
			parent::__construct($message, $code);
	}

	public function __toString()
	{
		return get_class($this)." '{$this->message}' in {$this->file}({$this->line})\n".
									"{$this->getTraceAsString()}";
	}

	/*** End magic methods ***/

} #=== End CustomException class.