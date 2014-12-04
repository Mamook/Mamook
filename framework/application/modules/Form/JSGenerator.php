<?php

abstract class JSGenerator
{
	private static $js=array();

	# load external JavaScript validating functions
	public function initializeFunctions()
	{
		self::$js['ext']='<script language="javascript" src="'.SCRIPTS.'JSForms.js"></script>';
	}

	# add JavaScript validating function to selected field
	public function addValidation($fieldName, $valData)
	{
		if(!is_array($valData))
		{
			throw new Exception('Invalid client-side validation array', E_RECOVERABLE_ERROR);
		}
		$obj='document.getElementsByTagName("form")[0].elements["'.$fieldName.'"]';
		# obtain client-side validating function & error message
		list($valFunction, $errorMessage)=$valData;
		self::$js['int']='if(!'.$valFunction.'('.$obj.',"'.$errorMessage.'")){'.$obj.'.focus();return false};';
	}

	# return JavaScript code
	public function getCode()
	{
		return self::$js['ext'].'<script language="javascript">function validate(){'.self::$js['int'].'}</script>';
	}
}