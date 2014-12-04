<?php

# Get the FormElement Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'FormElement.php');

class FormGenerator
{
	/*** data members ***/

	private $action;
	private $class=NULL;
	private $html=array();
	private $id=NULL;
	private $method;
	private $name;
	private $target;
	private $upload;
	# If you want to use JS errors in your form add $fg->setUseJS(TRUE);
	# 	after you instantiate the formGenerator class
	private $use_js=FALSE;

	/*** End data members ***/



	/*** magic methods ***/

	public function __construct($name='', $action=NULL, $method='post', $target='_top', $upload=FALSE, $class=NULL, $id=NULL)
	{
		try
		{
			# setup form attributes
			$this->setAction($action);
			$this->setMethod($method);
			$this->setName($name);
			$this->setTarget($target);
			$this->setUpload($upload);
			$this->setClass($class);
			$this->setID($id);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*** End magic methods ***/



	/*** mutator methods ***/

	/**
	 * Set the option to throw exceptions or not
	 *
	 * @param bool $bool TRUE or FALSE
	 * @return none
	 *
	 */
	public function setUseJS($use_js)
	{
		$this->use_js=$use_js;
	}

	/***
	* setAction
	*
	* Sets the data member action
	*
	* @param	$action
	* @access	protected
	*/
	protected function setAction($action=NULL)
	{
		# Set the Validator instance to a variable.
		$validator=Validator::getInstance();

		# Check if the passed action is empty.
		if(empty($action))
		{
			# Set the current page as the action.
			$action='http://'.FULL_URL;
			# Check if this is a secure page.
			if($validator->isSSL())
			{
				# Set the current page as the action using https.
				$action=SECURE_URL.SECURE_HERE.GET_QUERY;
			}
		}
		# Remove any index page from the url.
		$action=WebUtility::removeIndex($action);
		$this->action=$action;
	}

	/***
	* setClass
	*
	* Sets the data member $class
	*
	* @param	$class
	* @access	protected
	*/
	protected function setClass($class)
	{
		# Check if the passed value is empty.
		if(!empty($class))
		{
			# Clean it up.
			$class=trim($class);
			# Set the data member.
			$this->class=$class;
		}
	}

	/***
	* setHTML
	*
	* Adds an array element to the data member html
	*
	* @param	$html
	* @access	protected
	*/
	protected function setHTML($html)
	{
		$this->html[]=$html;
	}

	/***
	* setID
	*
	* Sets the data member $id
	*
	* @param	$id
	* @access	protected
	*/
	protected function setID($id)
	{
		# Check if the passed value is empty.
		if(!empty($id))
		{
			# Clean it up.
			$id=trim($id);
			# Remove any spaces.
			$id=preg_replace('/\S/', '', $id);
			# Set the data member.
			$this->id=$id;
		}
	}

	/***
	* setMethod
	*
	* Sets the data member method
	*
	* @param	$method
	* @access	protected
	*/
	protected function setMethod($method='post')
	{
		# Lowercase the method.
		$method=strtolower($method);
		$method=((empty($method)) ? 'post' : $method);
		$this->method=$method;
	}

	/***
	* setName
	*
	* Sets the data member name
	*
	* @param	$name
	* @access	protected
	*/
	protected function setName($name)
	{
		$this->name=$name;
	}

	/***
	* setTarget
	*
	* Sets the data member target
	*
	* @param	$target
	* @access	protected
	*/
	protected function setTarget($target)
	{
		$this->target=$target;
	}

	/***
	* setUpload
	*
	* Sets the data member upload
	*
	* @param	$upload
	* @access	protected
	*/
	protected function setUpload($upload)
	{
		$this->upload=$upload;
	}

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/***
	* getAction
	*
	* Returns the data member $action.
	*
	* @access	protected
	*/
	public function getAction()
	{
		if(isset($this->action) && !empty($this->action))
		{
			return $this->action;
		}
		else
		{
			throw new Exception('Action is not set in FormGenerator', E_RECOVERABLE_ERROR);
		}
	}

	/***
	* getClass
	*
	* Returns the data member $class.
	*
	* @access	protected
	*/
	public function getClass()
	{
		return $this->class;
	}

	/***
	* getID
	*
	* Returns the data member $id.
	*
	* @access	protected
	*/
	public function getID()
	{
		return $this->id;
	}

	/***
	* getHTML
	*
	* Returns the data member $html.
	*
	* @access	protected
	*/
	public function getHTML()
	{
		if(isset($this->html) && !empty($this->html))
		{
			return $this->html;
		}
		else
		{
			throw new Exception('HTML is not set in FormGenerator', E_RECOVERABLE_ERROR);
		}
	}

	/***
	* getMethod
	*
	* Returns the data member $method.
	*
	* @access	protected
	*/
	public function getMethod()
	{
		if(isset($this->method) && !empty($this->method))
		{
			return $this->method;
		}
		else
		{
			throw new Exception('Method is not set in FormGenerator', E_RECOVERABLE_ERROR);
		}
	}

	/***
	* getName
	*
	* Returns the data member $name.
	*
	* @access	protected
	*/
	public function getName()
	{
		if(isset($this->name) && !empty($this->name))
		{
			return $this->name;
		}
		else
		{
			throw new Exception('Name is not set in FormGenerator', E_RECOVERABLE_ERROR);
		}
	}

	/***
	* getTarget
	*
	* Returns the data member $target.
	*
	* @access	protected
	*/
	public function getTarget()
	{
		return $this->target;
	}

	/***
	* getUpload
	*
	* Returns the data member $upload.
	*
	* @access	protected
	*/
	public function getUpload()
	{
		return $this->upload;
	}

	/*** End accessor methods ***/



	/*** public methods ***/

	# Add a form element.
	public function addElement($input_type='text', $attributes=array('name'=>'default'), $options=array(), $img_src=NULL, $class=NULL, $id=NULL)
	{
		if(!$elem=new FormElement($input_type, $attributes, $options, $img_src, $class, $id))
		{
			throw new Exception('Failed to instantiate '.$input_type.'object', E_RECOVERABLE_ERROR);
		}
		$this->setHTML($elem->getHTML());
	}

	# Add a form part.
	public function addFormPart($formPart='<br />')
	{
		$this->setHTML(((trim($formPart)=='') ? '<br />' : $formPart));
	}

	# Add reCaptch HTML.
	public function reCaptchaGetHTML($pubkey, $error=NULL, $use_ssl=FALSE, $height=NULL, $width=NULL)
	{
		# Get the reCaptcha Library.
		require_once Utility::locateFile(MODULES.'Form'.DS.'recaptchalib.php');
		return recaptcha_get_html($pubkey, $error, $use_ssl, $height, $width);
	}

	# Display the form.
	public function display()
	{
		$upload='';
		if($this->getUpload()===TRUE)
		{
			$upload=' enctype="multipart/form-data"';
		}
		# Get the class data member and set it to a variable.
		$class=$this->getClass();
		# Get the id data member and set it to a variable.
		$id=$this->getID();
		$formOutput='<form'.$upload.' action="'.$this->getAction().'" method="'.$this->getMethod().'" name="'.$this->getName().'" target="'.$this->getTarget().'"'.((!empty($id)) ? ' id="'.$id.'"' : '').((!empty($class)) ? ' class="'.$class.'"' : '').'>';
		foreach($this->getHTML() as $html)
		{
			$formOutput.=$html;
		}
		$formOutput.='</form>';
		if($this->use_js===TRUE)
		{
			# load global JavaScript checking functions
			JSGenerator::initializeFunctions();
			# append JavaScript code to general (X)HTML output
			$formOutput.=JSGenerator::getCode();
		}
		return $formOutput;
	}

	/*** End public methods ***/

} #=== End FormGenerator class.