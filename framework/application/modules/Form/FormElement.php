<?php

# Get the JSGenerator Class.
require_once Utility::locateFile(MODULES.'Form'.DS.'JSGenerator.php');

class FormElement
{
	/*** data members ***/

	private $html;

	/*** End data members ***/



	/*** magic methods ***/

	public function __construct($type='text', $attributes=array('name'=>'default'), $options=array(), $img_src=NULL, $class=NULL)
	{
		# Convert $type to lowercase.
		$type=strtolower($type);
		# check for <input> elements
		if(preg_match('/^(text|email|radio|checkbox|password|hidden|submit|reset|button|file)$/',$type))
		{
			if($class===NULL)
			{
				$class=$type;
			}
			$openTag='<input'.(($type!='hidden') ? ' class="'.$class.'"' : '').' type="'.$type.'" ';
			$closeChar=' ';
			$closeTag='/>';
		}
		elseif($type=='image')
		{
			if($class===NULL)
			{
				$class='submit';
			}
			$openTag='<input type="'.$type.'" src="'.$img_src.'"'.(($type!='hidden') ? ' class="'.$class.'"' : '').' class="'.$class.'" ';
			$closeChar=' ';
			$closeTag='/>';
		}
		# check for <textarea> and <select> elements
		elseif(preg_match('/^(textarea|select)$/', $type))
		{
			if($class===NULL)
			{
				$class=$type;
			}
			$openTag='<'.$type.' class="'.$class.'" ';
			$closeChar='>';
			$closeTag='</'.$type.'>';
		}
		else
		{
			throw new Exception('Invalid element type', E_RECOVERABLE_ERROR);
		}
		if(!is_array($attributes) || (count($attributes)<1))
		{
			throw new Exception('Invalid number of attributes for <'.$type.'> element', E_RECOVERABLE_ERROR);
		}
		# loop over element attributes
		$elemAttributes='';
		foreach($attributes as $attribute=>$value)
		{
			if(empty($attribute))
			{
				throw new Exception('Invalid attribute for <'.$type.'> element', E_RECOVERABLE_ERROR);
			}
			if(($attribute=='text')||($attribute=='required'))
			{
				# check for 'required' attribute - add client-side validation
				if($attribute=='required')
				{
					JSGenerator::addValidation($attributes['name'],$value);
				}
				# check for 'required' attribute - add client-side validation
				elseif($attribute=='text')
				{
					$closeChar='>'.$value;
					$elemAttributes.='';
				}
			}
			elseif($attribute=='class')
			{
				$class=preg_match('/(class\=\"[\w\d\ \-_\.]+\")/i', $openTag, $match);
				$class=substr($match[1], 0, -1) .' '.$value. substr($match[1], -1);
				$openTag=str_replace($match[1], $class, $openTag);
			}
			elseif(($type=='radio') && ($attribute=='checked'))
			{
				if($value==$attributes['value'])
				{
					$elemAttributes.='checked="checked" ';
				}
			}
			else
			{
				$elemAttributes.=(($value!='') ? $attribute.'="'.$value.'" ' : '');
			}
		}
		# Create an empty variable for any <select> options.
		$selOptions='';
		if($type==='select' || $type==='checkbox')
		{
			# Loop throught the attributes to check if this is a date input.
			foreach($attributes as $attribute=>$value)
			{
				if($value==='month')
				{
					for($mn=1; $mn<13; $mn++)
					{
						$month=date('F', mktime(12, 3, 9, $mn, 9, 1970));
						$options[$mn]=$month;
					}
				}
				elseif($value==='day')
				{
					for($day=1; $day<=31; $day++)
					{
						$options[$day]=$day;
					}
				}
				elseif(preg_match('/(year$)/i', $value)>0)
				{
					$date=date('Y');
					for($year=1967; $year<=$date+5; $year++)
					{
						$options[$year]=$year;
					}
				}
			}
			# Check for <select> options.
			if(!empty($options) && (count($options)>0))
			{
				# Create an empty variable for the default "selected" value.
				$selected='';
				# Loop through the passed options.
				foreach($options as $key=>$text)
				{
					# Check if the key is 'selected'.
					if(isset($options['selected']))
					{
						# Check if the selected option matches the $text.
						if($options['selected']==$text)
						{
							$selected=$options['selected'];
						}
					}
					# Check if the key is 'multiple_selected'.
					elseif(isset($options['multiple_selected']))
					{
						# Loop through the 'multiple_selected' options.
						foreach($options['multiple_selected'] as $selected_option)
						{
							# Check if the selected option matches $text.
							if($selected_option==$text)
							{
								$selected=$selected_option;
							}
						}
					}
					# Check if the key is 'checked'.
					elseif(isset($options['checked']))
					{
						# Check if the checked option is true or "on" (1).
						if($options['checked']==1)
						{
							$closeChar=' checked="checked" ';
						}
					}
					# Check if the array key or the index have no value
					if($key===NULL || $key==='')
					{
						# Send an error.
						throw new Exception('Invalid value for <'.$type.'> element', E_RECOVERABLE_ERROR);
					}
					else
					{
						# Check if the key is 'selected' or 'multiple_selected'.
						if($key!=='selected' && $key!=='multiple_selected' && $key!=='checked')
						{
							$selOptions.='<option class="option" value="'.$key.'"'.(($text==$selected) ? ' selected="selected"' : '').'>'.$text.'</option>';
						}
					}
				}
			}
			if(empty($options))
			{
				foreach($attributes as $attribute=>$value)
				{
					if(($type==='checkbox') && ($attribute==='checked'))
					{
						if($value==1)
						{
							$closeChar=' checked="checked" ';
						}
					}
				}
			}
		}
		# build form element(X)HTML output
		$this->setHTML($openTag.trim($elemAttributes).$closeChar.$selOptions.$closeTag);
	} #==== End -- __construct

	/*** End magic methods ***/



	/*** mutator methods ***/

	/***
	 * setHTML
	 *
	 * Sets the data member html
	 *
	 * @param	$html
	 * @access	protected
	 */
	protected function setHTML($html)
	{
		$this->html=$html;
	} #==== End -- setHTML

	/*** End mutator methods ***/



	/*** accessor methods ***/

	/***
	 * getHTML
	 *
	 * Returns the data member $html.
	 *
	 * @access	protected
	 */
	public function getHTML()
	{
		if(!empty($this->html))
		{
			return $this->html;
		}
		else
		{
			throw new Exception('HTML is not set in FormElement', E_RECOVERABLE_ERROR);
		}
	} #==== End -- getHTML

	/*** End accessor methods ***/

} #=== End FormElement class.